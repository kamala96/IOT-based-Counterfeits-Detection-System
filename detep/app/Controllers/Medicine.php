<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MedicineModel;
use App\Models\DistributionstationModel;
use App\Models\PercelsentModel;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

use phpseclib3\Crypt\RSA;

class Medicine extends BaseController
{
	public function index()
	{
		helper(['form']);
		$model = new MedicineModel();
		$model2 = new DistributionstationModel();
		$issecondLast = session()->get('level_text') == 'secondlast' ? true : false;
		$data = [];
		if ($this->request->getMethod() == 'post')
		{
			$rules = [
				'title' => 'required|alpha_numeric_space|min_length[3]|max_length[255]|is_unique[medicines.title]',
			];
			$errors =
			[   
				'title' => [
					'alpha_numeric_space' => 'Please include alphanumeric or space characters only',
					'min_length' => 'Title: too short...please write a bit more',
					'is_unique' => 'The tile arleady exist',
				],
			];
			if(! $this->validate($rules, $errors))
			{
				$data = [
					'title' => 'Parcels',
					'medicines' => $this->processMedicines(),
					'immediateStations' => $model2->immediateStations(session()->get('level_int'), session()->get('system_id'), $issecondLast),
					'validation' => $this->validator,
				];
				return view('pages/percels_page', $data);
			}
			else
			{
				// $this->generateKeys();
				$uuid = \Ramsey\Uuid\Uuid::uuid4();
				// $signature = $this->generateSignature($uuid);
				// $encryptedUuid = $this->encryptText($uuid);
				// $content = $signature;
				
				######### format title
				$title = $this->seoUrl($this->request->getVar('title'));
				
				$content = $uuid;
				$content = $this->encryptText($content);
				$content = bin2hex($content);
				$qrcodeLink = $this->qrcodeGenerator($content, $title.'/', $title);
				$savedata = [
					'id' => $uuid,
					'title' => $title,
					'qrcodelink' => $qrcodeLink ? $qrcodeLink : 'Failed',
					'medicine_system' => session()->get('system_id'),
				];
				if($model->save($savedata))
				return redirect()->route("medicines_list")->with("sucess", "Successfully, data has been saved");			
			}		
			return redirect()->route("medicines_list")->with("error", "Oops an error occured, data has not been saved");			
		}
		else
		{
			$data = [
				'title' => 'Parcels',
				'medicines' => $this->processMedicines(),
				'immediateStations' => $model2->immediateStations(session()->get('level_int'), session()->get('system_id'), $issecondLast),
				'success_redirect' => session()->get("sucess"),
				'error_redirect' => session()->get("error"),
			];
			return view('pages/percels_page', $data);
		}
	}
	
	// PRIVATE FUNCTIONS START
	
	protected function seoUrl($string) {
		//Lower case everything
		$string = strtolower($string);
		//Make alphanumeric (removes all other characters)
		$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
		//Clean up multiple dashes or whitespaces
		$string = preg_replace("/[\s-]+/", " ", $string);
		//Convert whitespaces and underscore to dash
		$string = preg_replace("/[\s_]/", "-", $string);
		return $string;
	}
	
	
	protected function processMedicines()
	{
		$session = session();
		$model = new MedicineModel();
		$levelString = $session->get('level_text');
		if($levelString == 'first')
		{
			$data = $model->getFirstLevelPercels(false, $session->get('system_id'));
			if($data) return $data;
		}
		else
		{
			// $data = $model->getFollowingLevelPercels();
			// if($data) return $data;
		}
		return false;		
	}
	
	protected function qrcodeGenerator($content, $path, $imageName)
	{
		helper('filesystem');
		// QR image name
		// $imageName = str_replace(' ', '_', $imageName);
		// $imageName = date("YmdHis_").$imageName;
		$imageName = $imageName;
		
		$writer = new PngWriter();
		
		// Create QR code
		$qrCode = QrCode::create($content)
		->setEncoding(new Encoding('UTF-8'))
		->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
		->setSize(150)
		->setMargin(2)
		->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
		->setForegroundColor(new Color(0, 0, 0))
		->setBackgroundColor(new Color(255, 255, 255));
		
		// Create generic logo
		// $logo = Logo::create(APPPATH.'Assets/qr_codes/logo.png')
		// ->setResizeToWidth(50);
		
		// Create generic label
		// $label = Label::create('DeTeP')
		// ->setTextColor(new Color(255, 0, 0))
		// ->setBackgroundColor(new Color(0, 0, 0));
		
		// $result = $writer->write($qrCode, $logo, $label);
		$result = $writer->write($qrCode);
		
		// Save it to a file
		$directory = APPPATH."Assets/qr_codes/".$path;
		if( ! is_dir($directory)) mkdir($directory, 0700, TRUE);
		$save = $result->saveToFile($directory.$imageName.'.png');
		return $path.$imageName.'.png';
		
		// Generate a data URI to include image data inline (i.e. inside an <img> tag)
		// $dataUri = $result->getDataUri();
		// echo '<img src="'.$dataUri.'">';
	}
	
	protected function generateKeys()
	{
		$private = RSA::createKey(1024);
		if(! file_put_contents(APPPATH.'Assets/Keys/keys.pem', $private)) return false;
		return true;
		// $public = $private->getPublicKey();
		// $key = RSA::load(file_get_contents(APPPATH.'Assets/Keys/keys.pem'), $password=false);
		// $new = $key->getPublicKey();
	}
	
	protected function encryptText($plaintext)
	{
		$private = RSA::load(file_get_contents(APPPATH.'Assets/Keys/keys.pem'), $password=false);
		$ciphertext = $private->getPublicKey()->encrypt($plaintext);
		return $ciphertext;
		// echo bin2hex($ciphertext); 
	}
	
	protected function decryptText($ciphertext)
	{
		$ciphertext = $ciphertext;
		$private = RSA::load(file_get_contents(APPPATH.'Assets/Keys/keys.pem'), $password=false);
		$plaintext = $private->decrypt($ciphertext);
		return $plaintext;
	}
	
	protected function generateSignature ($message)
	{
		$private = RSA::load(file_get_contents(APPPATH.'Assets/Keys/keys.pem'), $password=false);
		$signature = $private->sign($message);
		return $signature;
	}
	
	protected function verifySignature()
	{
		$private = RSA::load(file_get_contents(APPPATH.'Assets/Keys/keys.pem'), $password=false);
		return $private->getPublicKey()->verify($message, $signature) ?
		true : false;
	}
	
	protected function isValidUuid($uuid)
	{
		if (is_string($uuid) && preg_match('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i', $uuid))
		{
			return true;
		}
		return false;
	}
	
	protected function createArchive($folder = false, $source, $outputFilename)
	{
		$zipFile = new \PhpZip\ZipFile();
		try{
			$folder ? 
			$zipFile->addDirRecursive($source)
			->saveAsFile($outputFilename)
			->close()
			: 
			$zipFile
			// ->addFromString('dir/file.txt', 'This is a Test file')
			->addFile($source)
			->saveAsFile($outputFilename)
			->close();
			
			$zipFile
			->openFile($outputFilename) // open archive from file
			// ->extractTo($outputDirExtract) // extract files to the specified directory
			// ->deleteFromRegex('~^\.~') // delete all hidden (Unix) files
			// ->addFromString('dir/file.txt', 'Test file') // add a new entry from the string
			->setPassword('@detep') // set password for all entries
			->outputAsAttachment('detep.zip'); // output to the browser without saving to a file
			
		}
		catch(\PhpZip\Exception\ZipException $e){
			// handle exception
			// print_r($e);
		}
		finally{
			$zipFile->close();
		}		
	}
	// PRIVATE FUNCTIONS END
	
	public function medicineDelete($id, $page, $image, $is_directory)
	{
		if($this->isValidUuid($id))
		{
			helper('filesystem');
			$model = new MedicineModel();
			$root_directory = APPPATH."Assets/qr_codes/";
			if($page == 0) 
			{
				// Percels list page
				$explode = explode("_", $image);
				$directory = $root_directory.$explode[0];
				$delete = $model->delete($id);
				if($delete)
				{
					if( ! delete_files($directory, TRUE))
					{
						// return redirect()->route("medicines_list")->with("error", "data has been deleted but not its files");
						echo json_encode(array("status" => true, "data" => "Partial success!, related files has not been deleted"));
					}
					else
					{
						rmdir($directory);
						// return redirect()->back();
						echo json_encode(array("status" => true, "data" => "Success!, data and its related files has been deleted"));
					}
				}
				else
				{
					// return redirect()->route("medicines_list")->with("error", "Oops an error occured, data has not been deleted");
					echo json_encode(array("status" => false, "data" => "Internal server error"));
				}
			}
			else if($page == 1)
			{
				// Single percel page
				if($is_directory == 1)
				{
					// delete a directory with subdirectories
					$directory = preg_replace('/_[^_]*$/', '', $image);
					$directory = $root_directory.str_replace('_', '/', $directory).'/';
					// echo $directory;
					$delete = $model->delete($id);
					if($delete)
					{
						if( ! delete_files($directory, TRUE))
						{
							// return redirect()->route("medicines_list")->with("error", "data has been deleted but not its files");
							echo json_encode(array("status" => true, "data" => "Partial success!, related files has not been deleted"));
						}
						else
						{
							rmdir($directory);
							echo json_encode(array("status" => true, "data" => "Success!, data and its related files has been deleted"));
							// return redirect()->back();
						}
					}
					else
					{
						echo json_encode(array("status" => false, "data" => "Internal server error"));
						// return redirect()->route("medicines_list")->with("error", "Oops an error occured, data has not been deleted");
					}
				}
				else
				{
					$image = $root_directory.str_replace('_', '/', $image);
					$delete = $model->delete($id);
					if($delete)
					{
						if( ! unlink($image)) 
						{
							echo json_encode(array("status" => true, "data" => "Partial success!, related files has not been deleted"));
							// return redirect()->route("medicines_list")->with("error", "data has been deleted but not its file");
						}
						else
						{
							echo json_encode(array("status" => true, "data" => "Success!, data and its related files has been deleted"));
							// return redirect()->back();
						}
					}
					else 
					{
						echo json_encode(array("status" => false, "data" => "Internal server error"));
						// return redirect()->route("medicines_list")->with("error", "Oops an error occured, data has not been deleted");
					}
				}
			}
			
			// delete_files('./path/to/directory/');
			// delete_files('./path/to/directory/', TRUE);
			// if($model->delete($id))
			// return redirect()->route("medicines_list")->with("sucess", "Successfully, data has been deleted");
			// return redirect()->route("medicines_list")->with("error", "Oops an error occured, data has not been deleted");
		}
		else
		{
			// return redirect()->route("medicines_list")->with("error", "Invalid Operation");
			echo json_encode(array("status" => false, "data" => "Invalid format"));
		}
	}
	
	public function explorePercel($segment1 = false, $segment2 = false)
	{
		if($segment2 && $this->isValidUuid($segment2))
		{
			helper(['form']);
			$data = [];
			$model = new MedicineModel();
			if ($this->request->getMethod() == 'post')
			{
				$rules = [
					'count' => 'required|integer|min_length[1]|max_length[255]',
					'type' => 'required|integer|min_length[1]|max_length[255]',
					'selectParent' => 'required',
				];
				if(! $this->validate($rules))
				{
					$data = [
						'title' => $segment1,
						'percel' => $segment2,
						'percel_tree' => $model->getPercelById($segment2),
						'medicines' => $this->processMedicines(),
						'validation' => $this->validator,
					];
					return view('pages/percel_page', $data);
				}
				else
				{
					$count = (int)$this->request->getVar('count');
					$type = $this->request->getVar('type');
					$parent = $this->request->getVar('selectParent');
					$explode = explode("*", $parent);
					$parent_id = $explode[0];
					$parent_qrpath = $explode[1];
					
					$is_package = ($type == 1) ? true : (($type == 2) ? false : "");
					
					$savedata = [];
					if($is_package)
					{
						// FOR PACK INSERT
						// Create a package first, later then insert units according to count
						$existing_count = $model->countExisting($parent_id);
						$existing_count = $existing_count+1;
						// $package_name = "Package_".$existing_count;
						$package_name = $this->seoUrl('Package-'.$existing_count);
						$uuid = \Ramsey\Uuid\Uuid::uuid4();
						// $title = date("YmdHisS_").$segment1."_Package";
						$content = $uuid;
						$content = $this->encryptText($content);
						$content = bin2hex($content);
						$qrcodeLink = $this->qrcodeGenerator($content, $parent_qrpath.$package_name.'/', $package_name);
						$package_data = [
							'id' => $uuid,
							'title' => $package_name,
							'parent_id' => $parent_id,
							'qrcodelink' => $qrcodeLink ? $qrcodeLink : 'Failed',
							'medicine_system' => session()->get('system_id'),
						];
						$model->insert($package_data);
						
						STATIC $num = 0;
						$num = $model->countExisting($uuid);
						for ($i=0; $i < $count; $i++)
						{ 
							$inner_uuid = \Ramsey\Uuid\Uuid::uuid4();
							// $inner_title = date("YmdHisS_").$segment1;
							$num = $num + 1;
							$inner_title = explode("-", $segment1);
							$inner_title = $inner_title[0]."_".$existing_count."_".$num;
							$inner_title = $this->seoUrl($inner_title);
							$content = $inner_uuid;
							$content = $this->encryptText($content);
							$content = bin2hex($content);
							$qrcodeLink = $this->qrcodeGenerator($content, $parent_qrpath.$package_name.'/', $inner_title);
							$additional_data = [
								'id' => $inner_uuid,
								'title' => $inner_title,
								'parent_id' => $uuid,
								'qrcodelink' => $qrcodeLink ? $qrcodeLink : 'Failed',
								'medicine_system' => session()->get('system_id'),
							];
							$savedata[] = $additional_data;
						}
					}
					else
					{
						// UNITS INSERT
						STATIC $existing_count = 0;
						$existing_count = $model->countExisting($parent_id);
						
						$string = $parent_qrpath;
						$last_word_start = strrpos($string, '-') + 1; // +1 so we don't include the space in our result
						$parent_name = substr($string, $last_word_start); // $last_word.
						
						// $data = $model->find($parent_id);
						// $parent_name = $data["title"];
						// get string after underscore
						// if (($pos = strpos($data, "_")) !== FALSE)
						// {
							// 	$whatIWant = substr($data, $pos+1);
							// }
							// $parent_name = substr($parent_name, strpos($parent_name, "-") + 1); 
							for ($i=0; $i < $count; $i++)
							{ 
								$inner_uuid = \Ramsey\Uuid\Uuid::uuid4();
								$existing_count = $existing_count  + 1;
								$inner_title = explode("-", $segment1);
								$inner_title = $inner_title[0]."_".$parent_name."_".$existing_count;
								$inner_title = $this->seoUrl($inner_title);
								$content = $inner_uuid;
								$content = $this->encryptText($content);
								$content = bin2hex($content);
								$qrcodeLink = $this->qrcodeGenerator($content, $parent_qrpath, $inner_title);
								$additional_data = [
									'id' => $inner_uuid,
									'title' => $inner_title,
									'parent_id' => $parent_id,
									'qrcodelink' => $qrcodeLink ? $qrcodeLink : 'Failed',
									'medicine_system' => session()->get('system_id'),
								];
								$savedata[] = $additional_data;
							}
						}
						if($model->insertBatch($savedata))
						return redirect()->back();
						// return redirect()->route("view_medicine")->with("sucess", "Successfully, data has been saved");			
					}
					return redirect()->back();	
					// return redirect()->route("view_medicine")->with("error", "Oops an error occured, data has not been saved");
				}
				else
				{
					$data = [
						'title' => $segment1,
						'percel' => $segment2,
						'percel_tree' => $model->getPercelById($segment2),
						'success_redirect' => session()->get("sucess"),
						'error_redirect' => session()->get("error"),
					];
					return view('pages/percel_page', $data);
				}
			}
			return redirect()->route("medicines_list")->with("error", "Invalid Operation");
		}
		
		public function downloadFiles($is_directory, $path)
		{
			$root_directory = APPPATH."Assets/qr_codes/";
			$outputFilename = $root_directory.'compressed.zip';
			
			if($is_directory == 1)
			{
				$directory = preg_replace('/_[^_]*$/', '', $path);
				$directory = $root_directory.str_replace('_', '/', $directory).'/';
				return $this->createArchive(true, $directory, $outputFilename);
			}
			else
			{
				$image = $root_directory.str_replace('_', '/', $path);
				// return $this->response->download($image, null, TRUE);
				return $this->createArchive(false, $image, $outputFilename);
			}
		}
		
		public function sendPercel()
		{
			helper(['form']);
			$model = new PercelsentModel();
			$model2 = new DistributionstationModel();
			$data = [];
			$rules = [
				'percel' => 'required|min_length[1]|max_length[255]',
				'send_to' => 'required|integer|min_length[1]|max_length[255]',
			];
			if($this->validate($rules) && $this->isValidUuid($this->request->getVar('percel')))
			{
				$data = [
					'sender' => session()->get('id'),
					'percel_sent'  => $this->request->getVar('percel'),
					'send_to'  => $this->request->getVar('send_to'),
				];
				$save = $model->save($data); 
				if($save != false)
				{
					// $data = $model->where('id', $save)->first();
					$data = $model2->where('id', $this->request->getVar('send_to'))->first();
					// print_r($data);
					echo json_encode(array("status" => true , 'data' => $data));
				}
				else
				{
					// print_r($data);
					echo json_encode(array("status" => false , 'data' => $data));
				}
			}
			else
			{
				// print_r($this->validator->listErrors());
				$data = $this->validator->listErrors();
				echo json_encode(array("status" => false , 'data' => $data));
			}
		}
	}
	
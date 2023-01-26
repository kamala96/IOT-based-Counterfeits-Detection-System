<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\I18n\Time;
use App\Models\ParcelModel;
use App\Models\ProductModel;
use App\Models\StationModel;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

use phpseclib3\Crypt\RSA;

class Parcel extends BaseController
{
    public function index()
    {
        $model1 = new ParcelModel();
        $model2 = new ProductModel();
        $model3 = new StationModel();
        $session = session();
        helper(['form']);
        $data = [];
        $pageTitle = 'Parcels Tracker';
        if ($this->request->getMethod() == 'post')
        {
            $rules = [
                'product' => 'required',
                'items' => 'required|is_natural',
            ];
            if(! $this->validate($rules))
            {
                $data = $this->validator->listErrors();
                echo json_encode(array("status" => false , 'data' => $data));
            }
            else
            {
                $insertData = [];
                $mainUuid = \Ramsey\Uuid\Uuid::uuid4();
                
                $items = $this->request->getVar('items');
                
                $unixTime = time();
                
                $product = $this->request->getVar('product');
                $explode_id_name = explode("*", $product);
                $productID = $explode_id_name[0];
                $preTitle = $this->slugCreator($explode_id_name[1]);
                $title = $preTitle. "_". $unixTime;
                $product_name = $preTitle;
                
                $qrContent = $this->encryptionService($mainUuid);
                $qrcodeLink = $this->qrcodeGenerator($qrContent, $title.'/', $title);
                
                $mainData = [
                    'parc_id' => $mainUuid,
                    'parc_title' => $title,
                    'parc_parent' => NULL,
                    'parc_product' => $productID,
                    'parc_level' => $session->get('lv_id'),
                    'parc_station_path' => $session->get('int_station'),
                    // 'parc_next_station' => session()->get('system_id'),
                    // 'parc_sent_dates' => session()->get('system_id'),
                    'parc_arrival_dates' => $unixTime,
                    'parc_qrcodelink' => $qrcodeLink,
                ];
                
                $insertData[] = $mainData;
                
                if($items > 0) {
                    STATIC $num = 0;
                    for ($i=0; $i < $items; $i++)
                    {
                        $subUuid = \Ramsey\Uuid\Uuid::uuid4();
                        $num = $num + 1;
                        $finalName = time()."_".$product_name.$num;
                        
                        $content = $this->encryptionService($subUuid);
                        $qrcodeLink = $this->qrcodeGenerator($content, $title.'/', $finalName);
                        
                        $subData = [
                            'parc_id' => $subUuid,
                            'parc_title' => $product_name,
                            'parc_parent' => $mainUuid,
                            'parc_product' => $productID,
                            'parc_level' => $session->get('lv_id'),
                            'parc_station_path' => $session->get('int_station'),
                            // 'parc_next_station' => session()->get('system_id'),
                            // 'parc_sent_dates' => session()->get('system_id'),
                            'parc_arrival_dates' => $unixTime,
                            'parc_qrcodelink' => $qrcodeLink,
                        ];
                        
                        $insertData[] = $subData;
                    }
                }
                
                if($model1->insertBatch($insertData))
                {
                    echo json_encode(array("status" => true , 'data' => 'Success!, Parcel created'));               
                }
                else
                {
                    echo json_encode(array("status" => false , 'data' => 'Oops!, Not Created'));
                }
            }           
        }
        else
        {
            if($session->get('isTopUser'))
            {
                if($session->get('mg'))
                {
                    $parcels = $model1->getParcels(false, $session->get('mg'));}
                    else
                    {
                        $parcels = $model1->getParcels($session->get('system_id'), false);
                    }
                }
                else
                {
                    if($session->get('mg'))
                    {
                        $parcels =$model1->getParcels2(false, $session->get('mg'));
                    }
                    else
                    {
                        $parcels =$model1->getParcels2($session->get('int_station'), false);
                    }
                }
                $data = [
                    'title' => $pageTitle,
                    'products' => $model2->getProducts($session->get('system_id'), $session->get('mg')),
                    'stations' => $model3->getOnlyImmidiateStations($session->get('system_id'), $session->get('level_int'), $session->get('isSecondLast')),
                    'parcels' => $parcels,
                    'readyParcels' => $model1->readyParcels($session->get('int_station')),
                ];
                // sijafanya ku-retrieve parcels according to level, 
                //otherwise restrict top users only-no gud practice.
                return view('pages/parcel_page', $data);
            }
        }   
        
        public function test()
        {
            $model1 = new ParcelModel();
            echo json_encode($model1->readyParcels(session()->get('int_station')));
        }
        
        ################## PROTECTED AREA START ################### 
        
        protected function slugCreator($string) {
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
            $encoded = bin2hex(\CodeIgniter\Encryption\Encryption::createKey(32));
            return $encoded;
        }
        
        protected function encryptionService($content, $action=0)
        {
            $encrypter = \Config\Services::encrypter();
            if($action == 0)
            {
                try
                {
                    $ciphertext = $encrypter->encrypt($content);
                    $data =  bin2hex($ciphertext);
                    return $data;
                }
                catch (\Throwable $e)
                {
                    return false;
                }
            }
            else 
            {
                try
                {
                    $ciphertext = hex2bin($content);
                    $plainText = $encrypter->decrypt($ciphertext);
                    return $plainText;
                }
                catch (\Throwable $e)
                {
                    return false;
                }
            }
        }
        
        protected function isValidUuid($uuid)
        {
            if (is_string($uuid) && preg_match('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i', $uuid))
            {
                return true;
            }
            return false;
        }
        
//      protected function createArchive($folder = false, $source, $outputFilename)
//      {
//          $zipFile = new \PhpZip\ZipFile();
//          try{
//              $folder ? 
//              $zipFile->addDirRecursive($source)
//              ->saveAsFile($outputFilename)
//              ->close()
//              : 
//              $zipFile
//              // ->addFromString('dir/file.txt', 'This is a Test file')
//              ->addFile($source)
//              ->saveAsFile($outputFilename)
//              ->close();

//              $zipFile
//              ->openFile($outputFilename) // open archive from file
//              // ->extractTo($outputDirExtract) // extract files to the specified directory
//              // ->deleteFromRegex('~^\.~') // delete all hidden (Unix) files
//              // ->addFromString('dir/file.txt', 'Test file') // add a new entry from the string
//              ->setPassword('@detep') // set password for all entries
//              ->outputAsAttachment('detep.zip'); // output to the browser without saving to a file

//          }
//          catch(\PhpZip\Exception\ZipException $e){
//              // handle exception
//              // print_r($e);
//          }
//          finally{
//              $zipFile->close();
//          }       
//      }
        
        protected function nestedToSingle(array $array)
        {
            $singleDimArray = [];
            
            foreach ($array as $item) {

                if (is_array($item)) {
                    $singleDimArray = array_merge($singleDimArray, $this->nestedToSingle($item));
                    
                } else {
                    $singleDimArray[] = $item;
                }
            }
            
            return $singleDimArray;
        }
        
        protected function checkBool($string)
        {
            $string = strtolower($string);
            return (in_array($string, array("true", "false", "1", "0", "yes", "no"), true));
        }
        
        
        public function fpdf($dir)
        {
            $root = APPPATH."Assets/qr_codes/".$dir."/";
            if(is_dir($root))
            {
                $pdf = new \FPDF();
                $arrImg = [];
                $images = glob($root."*.png");
                foreach($images as $image)
                {
                    $arrImg[] = $image;
                }
                reset($arrImg);
                $pdf->AddPage();
                $pdf->SetFont('Arial','B',14);
                // $pdf->Write(5,$dir);
                $pdf->Cell(0, 5, $dir . ' parcel - the one with no border, is a QR-Code for a package', 0, 0, 'C');
                $pdf->Ln();
                $pdf->Ln();
                $image_height = 30;
                $image_width = 30;
                $array = ["5","11","17","23","29","35","41","47","53","59"];
                foreach($arrImg as $key => $image)
                {
                    $break = 0;
                    $border = 1;
                    if(in_array($key, $array)) $break = 1;
                    $imageInfo = pathinfo($image);
                    if($imageInfo["filename"] == $dir) $border=0;
                    $pdf->Cell( 30, 30, $pdf->Image($image, $pdf->GetX(), $pdf->GetY(),$image_width,$image_height), $border, $break, 'L', false );
                    $this->response->setHeader('Content-Type', 'application/pdf');
                }
                $pdf->Output();
            }
            else
            {
                echo "not exist";
            }
        }
        
        ################## PROTECTED AREA END ###################
        
        public function parcelPath()
        {
            $model1 = new StationModel();
            $station = $this->request->getVar('id');
            $action_date = $this->request->getVar('action_date');
            $station = (int)$station;
            $data = $model1->stationDetails($station);
            $myTime = Time::now();
            $unixTime = time();
            $unixToNormal = Time::createFromTimestamp($action_date);
            $html = '<div>';        
            $html .= "<span class='head text-info'>Name : </span><span>".$data['st_title']."</span><br/>";
            $html .= "<span class='head text-info'>Level : </span><span>".strtoupper($data['lv_title'][0])."</span><br/>";
            $html .= "<span class='head text-info'>Manager : </span><span>".$data['int_fname']."&nbsp;".$data['int_lname']."</span><br/>";
            $html .= "<span class='head text-info'>Date : </span><span>".$unixToNormal."</span><br/>";
            $html .= '</div>';
            echo $html;
        }
        
        public function printFile($directory)
        {
            $this->fpdf($directory);
        }
        
        public function sendPercel()
        {
            helper(['form']);
            $model = new ParcelModel();
            $model2 = new StationModel();
            $session = session();
            $rules = [
                'parcel' => 'required|min_length[1]|max_length[255]',
                'send_to' => 'required|integer|min_length[1]|max_length[255]',
            ];
            if($this->validate($rules) && $this->isValidUuid($this->request->getVar('parcel')))
            {
                $unixTime = time();
                $sentId = $this->request->getVar('parcel');
                
                $recipient = $this->request->getVar('send_to');
                $recipient_lv = $model2->where('st_id', $recipient)->first();
                $recipient_level = $recipient_lv['st_level'];
                
                $level = $session->get('lv_id');
                
                $hasSub = $model->getParcelsIDs($sentId);
                $hasSub = array_unique($this->nestedToSingle($hasSub));
                $finalPKs = [];
                foreach($hasSub as $row)
                {
                    if($this->isValidUuid($row))
                    {
                        $finalPKs[] = $row;
                    }
                }
                
                $current = $model->where('parc_id', $sentId)->first();
                if($current['parc_level'] != NULL) 
                {
                    $level = $current['parc_level'];
                    $level2 = $level.'_';
                    $recipient_level = $level2 . $recipient_level;
                }
                if($current['parc_sent_dates'] != NULL) 
                {
                    $unixTime = $current['parc_sent_dates']."_". $unixTime;
                }
                $updateData =  [
                    'parc_next_station'  => $recipient,
                    'parc_level'  => $recipient_level,
                    'parc_sent_dates'  => $unixTime,
                ];
                
                $update = $model->update($finalPKs, $updateData);
                
                if($update)
                {
                    echo json_encode(array("status" => true , 'data' => $recipient_lv['st_title']));
                }
                else
                {
                    echo json_encode(array("status" => false , 'data' => 'Success'));
                }
            }
            else
            {
                $data = $this->validator->listErrors();
                echo json_encode(array("status" => false , 'data' => $data));
            }
        }
        
        public function deleteParcel()
        {
            $rules = [
                'id' => 'required|alpha_dash|max_length[40]',
                'qrcode' => 'required',
                'isDir' => 'required|in_list[0,1]',
            ];
            
            if( ! $this->validate($rules))
            {   
                $data = $this->validator->listErrors();
                echo json_encode(array("status" => false , 'data' => $data));
            }
            else
            {
                $deleteId = $this->request->getVar('id');
                $qrcode = $this->request->getVar('qrcode');
                $isDir = $this->request->getVar('isDir');
                if(! $this->isValidUuid($deleteId))
                {
                    echo json_encode(array("status" => false, "data" => "Invalid delete ID format"));
                }
                else
                {
                    if(! $this->checkBool($isDir))
                    {
                        echo json_encode(array("status" => false, "data" => "Invalid delete boolean value"));
                    }
                    else
                    {
                        helper('filesystem');
                        $model = new ParcelModel();
                        $qrRootDir = APPPATH."Assets/qr_codes/";
                        
                        if($isDir == 1)
                        {
                            // delete a directory with subdirectories
                            $dirToRemove = explode("/", $qrcode);
                            $dirToRemove = $qrRootDir.$dirToRemove[0];
                            // echo $directory;
                            $delete = $model->delete($deleteId);
                            if(! $delete)
                            {
                                echo json_encode(array("status" => false, "data" => "Internal server error"));
                            }
                            else
                            {
                                if( ! delete_files($dirToRemove, TRUE))
                                {
                                    echo json_encode(array("status" => true, "data" => "Partial success!, related files has not been deleted"));
                                }
                                else
                                {
                                    rmdir($dirToRemove);
                                    echo json_encode(array("status" => true, "data" => "Success!, data and its related files has been deleted"));
                                }
                            }
                        }
                        else
                        {
                            $fileToRemove = $qrcode;
                            $fileToRemove = $qrRootDir.$fileToRemove;
                            $delete = $model->delete($deleteId);
                            if( ! $delete)
                            {
                                echo json_encode(array("status" => false, "data" => "Internal server error"));
                            }
                            else 
                            {
                                if( ! unlink($fileToRemove)) 
                                {
                                    echo json_encode(array("status" => true, "data" => "Partial success!, related files has not been deleted"));
                                }
                                else
                                {
                                    echo json_encode(array("status" => true, "data" => "Success!, data and its related files has been deleted"));
                                }
                            }
                        }
                    }
                }
            }
        }
        
        public function moveParcel()
        {
            $rules = [
                'id' => 'required|alpha_dash|max_length[40]',
                'moveTo' => 'required|alpha_dash|max_length[40]',
            ];
            if( ! $this->validate($rules))
            {   
                $data = $this->validator->listErrors();
                echo json_encode(array("status" => false , 'data' => $data));
            }
            else
            {   
                $id = $this->request->getVar('id');
                $moveTo = $this->request->getVar('moveTo');
                if(! $this->isValidUuid($id) || ! $this->isValidUuid($id))
                {
                    echo json_encode(array("status" => false, "data" => "Invalid ID format"));
                }
                else
                {
                    $model = new ParcelModel();
                    $update = $model->moveParcel($id, $moveTo);
                    if( ! $update)
                    {
                        echo json_encode(array("status" => false, "data" => "Internal server error"));
                    }
                    else 
                    {
                        echo json_encode(array("status" => true, "data" => "Success!"));                        
                    }
                }
            }   
        }
        
        public function reset()
        {
            $model = new ParcelModel();
            $model2 = new StationModel();
            $session = session();

            $parcel_name = $this->request->getVar('value');

            $unixTime = time();

            $ID_of_parcel_name = $model->where('parc_title', $parcel_name)->first();
            $ID_of_parcel_name = $ID_of_parcel_name['parc_id'];
            $user_level = $session->get('lv_id');
            $user_station = $session->get('int_station');

            $hasSub = $model->getIDsToReset($ID_of_parcel_name);
            $hasSub = array_unique($this->nestedToSingle($hasSub));
            $finalPKs = [];

            foreach($hasSub as $row)
            {
                if($this->isValidUuid($row))
                {
                    $finalPKs[] = $row;
                }
            }

            $updateData =  [
                'parc_next_station'  => NULL,
                'parc_level'  => $user_level,
                'parc_sent_dates'  => NULL,
                'parc_station_path' => $user_station,
                'parc_arrival_dates' => $unixTime,
                'parc_sold' => 0,
            ];

            try
            {
                $update = $model->update($finalPKs, $updateData);
                return redirect()->to(base_url('dashboard/parcels'));
            }
            catch(Exception $e)
            {
                return redirect()->to(base_url('dashboard/parcels'));
            }
        }
    }
<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use Exception;
use \Firebase\JWT\JWT;

use CodeIgniter\I18n\Time;
use App\Models\ParcelModel;
use App\Models\ProductModel;
use App\Models\StationModel;
use App\Models\IntermediaryModel;
use App\Models\ResultModel;

// headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control");

class Api extends ResourceController
{
	public function getBeacons()
	{
		$model = new StationModel();
		$beacons = $model->getBeacons();
		if($beacons == false)
		{
			$response = [
				'status' => 500,
				'error' => TRUE,
				'messages' => 'No beacons'
			];
			return $this->respond($response);
		}
		else
		{
			$response = [
				'status' => 200,
				'error' => FALSE,
				'messages' => "Beacons Found",
				'data' => $beacons,
			];
			return $this->respond($response);
		}
	}
	
	public function checkIfPro() 
	{
		$rules = [
			'device' => 'required',
		];
		
		if( ! $this->validate($rules))
		{	
			$data = $this->validator->listErrors();
			$response = [
				'status' => 500,
				'error' => TRUE,
				'messages' => 'Oops! No data supplied',
				'data' => $data,
			];
			return $this->respondCreated($response);
		}
		else
		{		
			$model = new StationModel();
			$model2 = new IntermediaryModel();
			$deviceInfo = $model2->getDeviceInfo($this->request->getVar("device"));
			$beacons = $model->getBeacons();
			if(! empty($deviceInfo))
			{
				$isLast = $deviceInfo['lv_int'] == 100 ? true : false;
				$station_data[] = array('st_title'=>$deviceInfo['st_title'], 'st_beacon'=>$deviceInfo['st_beacon']);
				$data = array('device' => $this->request->getVar("device"), 'beacon' => $station_data, 'beacons' => $beacons, 'isLast' => $isLast);
				$response = [
					'status' => 200,
					'error' => FALSE,
					'messages' => "Success",
					'data' => $data,
				];
				return $this->respondCreated($response);
			} 
			else
			{
				$array = array('beacons' => $beacons);
				$response = [
					'status' => 500,
					'error' => TRUE,
					'messages' => 'Oops! Not Found',
					'data' => $array,
				];
				return $this->respondCreated($response);
			}
		}		
	}
	
	public function submitPro() 
	{
		$model = new IntermediaryModel();
		
		$device = $this->request->getVar("device");
		$mail = $this->request->getVar("mail");
		$key = $this->request->getVar("key");
		
		$mailData = $model->where('int_mail', $mail)->first();
		$validKey = password_verify($key, $mailData['int_password']);
		if($validKey)
		{
			$update = $model->submitPro((int)$mailData['int_id'], $device);
			if($update)
			{
				$response = [
					'status' => 200,
					'error' => FALSE,
					'messages' => "Success",
					'data' => $this->request->getVar("device"),
				];
				return $this->respondCreated($response);
			}
			else
			{
				$response = [
					'status' => 500,
					'error' => TRUE,
					'messages' => 'Oops! Internal server error'
				];
				return $this->respondCreated($response);
			}
		} 
		else
		{
			$response = [
				'status' => 404,
				'error' => TRUE,
				'messages' => 'Oops! Invalid data supplied',
			];
			return $this->respondCreated($response);
		}
		
	}
	
	public function resetPro()
	{
		
		$model = new IntermediaryModel();
		
		$device = $this->request->getVar("device");
		$deviceInfo = $model->where('int_device', $device)->first();
		if(empty($deviceInfo))
		{
			$response = [
				'status' => 404,
				'error' => TRUE,
				'messages' => 'Oops! Information Not Found'
			];
			return $this->respondCreated($response);
		}
		else
		{
			if($model->resetPro($deviceInfo['int_id']))
			{
				$response = [
					'status' => 200,
					'error' => FALSE,
					'messages' => "Success",
					"data" => $device,
				];
				return $this->respondCreated($response);
			}
			else
			{
				$response = [
					'status' => 404,
					'error' => TRUE,
					'messages' => 'Oops! Internal Server Error'
				];
				return $this->respondCreated($response);
			}
		}
	}
	
	public function getParcelsToReceive($userDevice)
	{
		$model1 = new ParcelModel();
		$model2 = new IntermediaryModel();
		$deviceInfo = $model2->where('int_device', $userDevice)->first();
		if(! empty($deviceInfo))
		{
			$parcels = $model1->apiCallParcelsToReceive($deviceInfo['int_station']);
			if( !empty($parcels)) 
			{
				$response = [
					'status' => 200,
					'error' => FALSE,
					'messages' => "Success",
					"data" => $parcels,
				];
				return $this->respondCreated($response);
			} 
			else 
			{
				$response = [
					'status' => 404,
					'error' => TRUE,
					'messages' => 'Oops! Not Found'
				];
				return $this->respondCreated($response);
			}
		}
		else
		{
			$response = [
				'status' => 403,
				'error' => TRUE,
				'messages' => 'Oops! Device Not Authenticated',
				'data' => $userDevice,
			];
			return $this->respondCreated($response);
		}
		
	}
	
	public function getParcelsToSend($userDevice)
	{
		$model1 = new ParcelModel();
		$model2 = new IntermediaryModel();
		$deviceInfo = $model2->where('int_device', $userDevice)->first();
		if(! empty($deviceInfo))
		{
			$parcels = $model1->apiCallParcelsToSend($deviceInfo['int_station']);
			if( !empty($parcels)) 
			{
				$response = [
					'status' => 200,
					'error' => FALSE,
					'messages' => "Success",
					"data" => $parcels,
				];
				return $this->respondCreated($response);
			} 
			else 
			{
				$response = [
					'status' => 500,
					'error' => TRUE,
					'messages' => 'Oops! Not Found'
				];
				return $this->respondCreated($response);
			}
		}
		else
		{
			$response = [
				'status' => 500,
				'error' => TRUE,
				'messages' => 'Oops! Device Not Authenticated'
			];
			return $this->respondCreated($response);
		}
		
	}
	
	
	public function immediateStations($device)
	{
		$model1 = new StationModel();
		$model2 = new IntermediaryModel();
		$deviceInfo = $model2->getDeviceInfo($device);
		if($deviceInfo)
		{
			$getSlug = $deviceInfo['sy_slug'];
			$getLeveTitle = $deviceInfo['lv_title'];
			$isSecondLast = false;
			if (strpos($getLeveTitle, $getSlug) !== false) {
				$isSecondLast = true;
			}
			$getStations = $model1->getImmidiateForAPI($deviceInfo['st_system'], $deviceInfo['lv_int'], $isSecondLast);
			if($getStations)
			{
				$response = [
					'status' => 200,
					'error' => FALSE,
					'messages' => "Success",
					"data" => $getStations,
				];
				return $this->respondCreated($response);
			}
			else
			{
				$response = [
					'status' => 500,
					'error' => TRUE,
					'messages' => 'Oops! Stores Not Found'
				];
				return $this->respondCreated($response);
			}
		}
		else
		{
			$response = [
				'status' => 500,
				'error' => TRUE,
				'messages' => 'Oops! User Not Found'
			];
			return $this->respondCreated($response);
		}
		
	}
	
	protected function compareBeaconWithDevice($beacon, $device)
	{
		$model1 = new IntermediaryModel();
		$model2 = new StationModel();
		$compare1 = $model1->where('int_device', $device)->first();
		$array = array('st_id' => $compare1['int_station'], 'st_beacon' => $beacon);
		$compare2 = $model2->where($array)->first();
		if(! empty($compare2)) return true;
		else return false;
	}
	
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
	
	protected function isValidUuid($uuid)
	{
		if (is_string($uuid) && preg_match('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i', $uuid))
		{
			return true;
		}
		return false;
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
	
	protected function geoDistance($lat1, $lon1, $lat2, $lon2, $unit)
	{
		if (($lat1 == $lat2) && ($lon1 == $lon2)) {
			return 0;
		}
		else {
			$theta = $lon1 - $lon2;
			$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
			$dist = acos($dist);
			$dist = rad2deg($dist);
			$miles = $dist * 60 * 1.1515;
			$unit = strtoupper($unit);
			
			if ($unit == "K") {
				return ($miles * 1.609344);
			} else if ($unit == "N") {
				return ($miles * 0.8684);
			} else {
				return $miles;
			}
		}
	}
	
	protected function recordEvaluationData($data)
	{
		try 
		{
			$model = new ResultModel();
			$model->save($data);
		}
		catch (\Throwable $e)
		{
	        // return false;
		}
	}
	
	public function detectProduct()
	{
		$rules = [
			'beacon' => 'required',
			'device' => 'required',
			'code' => 'required',
			'receive' => 'required',
			'send' => 'required',
			'normal' => 'required',
			'recipient' => 'required',
			'latitude' => 'required',
			'longitude' => 'required',
		];
		
		if( ! $this->validate($rules))
		{	
			$data = $this->validator->listErrors();
			$messages = 'No data supplied';
			
			$result = [
				'res_device' => $this->request->getVar("device") ?: 'NULL',
				'res_station' => $this->request->getVar('beacon') ?: 'NULL',
				'res_action' => 'NULL',
				'res_responce' => $messages,
			];
			$this->recordEvaluationData($result);
			
			$response = [
				'status' => 500,
				'error' => TRUE,
				'messages' => $messages,
				'data' => $data,
			];
			return $this->respondCreated($response);
		}
		else
		{
			
			$model1 = new StationModel();
			$model2 = new ParcelModel();
			$model3 = new IntermediaryModel();
			$currentStation = $model1->where('st_beacon', $this->request->getVar("beacon"))->first();
			$code =  $this->encryptionService($this->request->getVar("code"), 1);
			if($code == false)
			{
				$result = [
					'res_device' => $this->request->getVar("device") ?: 'NULL',
					'res_station' => $currentStation['st_title'] ?: 'NULL',
					'res_action' => 'NULL',
					'res_responce' => 'Oops! Product Not Found',
					'res_time' => Time::now(),
				];
				$this->recordEvaluationData($result);
				
				$response = [
					'status' => 500,
					'error' => TRUE,
					'messages' => 'Oops! Product Not Found',
					'station' => $currentStation['st_title'],
				];
				return $this->respondCreated($response);
			}
			else
			{
				if( ! $this->isValidUuid($code))
				{
					$result = [
						'res_device' => $this->request->getVar("device") ?: 'NULL',
						'res_station' => $currentStation['st_title'] ?: 'NULL',
						'res_action' => 'NULL',
						'res_responce' => 'Oops! Invalid Data Format',
						'res_time' => Time::now(),
					];
					$this->recordEvaluationData($result);
					
					$response = [
						'status' => 500,
						'error' => TRUE,
						'messages' => 'Oops!, Invalid Data Format',
						'station' => $currentStation['st_title'],
					];
					return $this->respondCreated($response);
				}
				else
				{
					$beacon = $this->request->getVar("beacon");
					$device = $this->request->getVar("device");
					$receive = $this->request->getVar("receive");
					$send = $this->request->getVar("send");
					$normal = $this->request->getVar("normal");
					$recipient = $this->request->getVar("recipient");
					$latitude = $this->request->getVar("latitude");
					$longitude = $this->request->getVar("longitude");

					$unixTime = time();

					if($receive == '1')
					{
												// receiving
						$compare = $this->compareBeaconWithDevice($beacon, $device);
						if(! $compare)
						{
							$result = [
								'res_device' => $this->request->getVar("device") ?: 'NULL',
								'res_station' => $currentStation['st_title'] ?: 'NULL',
								'res_action' => 'Receiving a product',
								'res_responce' => 'The user is using invalid beacon or not at his place',
								'res_time' => Time::now(),
							];
							$this->recordEvaluationData($result);
							
							$response = [
								'status' => 500,
								'error' => TRUE,
								'messages' => 'Error, Please Use your Store',
								'station' => $currentStation['st_title'],
							];
							return $this->respondCreated($response);
						}
						else
						{
							$deviceInfo = $model3->where('int_device', $device)->first();
							$parcelsToReceive = $model2->apiCallParcelsIDsToReceive($deviceInfo['int_station']);
							$parcelsToReceive = array_unique($this->nestedToSingle($parcelsToReceive));

							if(in_array($code, $parcelsToReceive))
							{					
								$parcelExistingInfo = $model2->where('parc_id', $code)->first();

								$parcelExistingInfoPath = $parcelExistingInfo['parc_station_path'];
								$sub = $parcelExistingInfoPath.'_';
								$newPath = $sub . $deviceInfo['int_station'];

								$unixTime = $parcelExistingInfo['parc_arrival_dates']."_". $unixTime;

								$childsPKs = [];
								$hasChild = $model2->getParcelsIDs($code);
								$hasChild = array_unique($this->nestedToSingle($hasChild));
								foreach($hasChild as $row)
								{
									if($this->isValidUuid($row))
									{
										$childsPKs[] = $row;
									}
								}

								$updateData =  [
									'parc_next_station'  => NULL,
									'parc_station_path'  => $newPath,
									'parc_arrival_dates'  => $unixTime,
								];

								$update = $model2->update($childsPKs, $updateData);
								if($update)
								{
									$result = [
										'res_device' => $this->request->getVar("device") ?: 'NULL',
										'res_station' => $currentStation['st_title'] ?: 'NULL',
										'res_action' => 'Receiving a product',
										'res_responce' => 'The products was received',
										'res_time' => Time::now(),
									];
									$this->recordEvaluationData($result);
									
									$response = [
										'status' => 200,
										'error' => FALSE,
										'messages' => "Received Successfully",
										'station' => $currentStation['st_title'],
									];
									return $this->respondCreated($response);
								}
								else
								{  
									$result = [
										'res_device' => $this->request->getVar("device") ?: 'NULL',
										'res_station' => $currentStation['st_title'] ?: 'NULL',
										'res_action' => 'Receiving a product',
										'res_responce' => 'The products not received, an internal server error occurred!',
										'res_time' => Time::now(),
									];
									$this->recordEvaluationData($result);
									
									$response = [
										'status' => 500,
										'error' => TRUE,
										'messages' => 'Oops! An Internal Server Error Occured',
										'station' => $currentStation['st_title'],
									];
									return $this->respondCreated($response);
								}
							}
							else
							{
								$result = [
									'res_device' => $this->request->getVar("device") ?: 'NULL',
									'res_station' => $currentStation['st_title'] ?: 'NULL',
									'res_action' => 'Receiving a product',
									'res_responce' => 'The products was not sent to this station',
									'res_time' => Time::now(),
								];
								$this->recordEvaluationData($result);
								
								$response = [
									'status' => 500,
									'error' => TRUE,
									'messages' => 'Oops! This is Not Available at your Store',
									'station' => $currentStation['st_title'],
								];
								return $this->respondCreated($response);
							}
						}
					}
					else if($send == '1')
					{
												// sending
						$compare = $this->compareBeaconWithDevice($beacon, $device);
						if( ! $compare)
						{
							$result = [
								'res_device' => $this->request->getVar("device") ?: 'NULL',
								'res_station' => $currentStation['st_title'] ?: 'NULL',
								'res_action' => 'Selling products',
								'res_responce' => 'The user is using invalid beacon or not at his place',
								'res_time' => Time::now(),
							];
							$this->recordEvaluationData($result);
							
							$response = [
								'status' => 500,
								'error' => TRUE,
								'messages' => 'Oops! Invalid Store Address, It Seems Like your Not a Distributor',
								'station' => $currentStation['st_title'],
							];
							return $this->respondCreated($response);
						}
						else
						{
							$deviceInfo = $model3->where('int_device', $device)->first();
							$parcelsToSend = $model2->apiCallParcelsIDsToSend($deviceInfo['int_station']);
							$parcelsToSend = array_unique($this->nestedToSingle($parcelsToSend));
							if(! in_array($code, $parcelsToSend))
							{
								$result = [
									'res_device' => $this->request->getVar("device") ?: 'NULL',
									'res_station' => $currentStation['st_title'] ?: 'NULL',
									'res_action' => 'Selling products',
									'res_responce' => 'The products was not available at this station',
									'res_time' => Time::now(),
								];
								$this->recordEvaluationData($result);
								
								$response = [
									'status' => 500,
									'error' => TRUE,
									'messages' => 'Oops! This is Not Available At your Store',
									'station' => $currentStation['st_title'],
								];
								return $this->respondCreated($response);					
							}
							else
							{
								$getRecipient = $model1->where('st_id', $recipient)->first();
								$recipientLevel = $getRecipient['st_level'];

								$parcelExistingInfo = $model2->where('parc_id', $code)->first();

								$parcelExistingInfoLevel = $parcelExistingInfo['parc_level'];
								$sub = $parcelExistingInfoLevel.'_';
								$recipientLevel = $sub . $recipientLevel;

								$unixTime = $parcelExistingInfo['parc_sent_dates']."_". $unixTime;

								$childsPKs = [];
								$hasChild = $model2->getParcelsIDs($code);
								$hasChild = array_unique($this->nestedToSingle($hasChild));
								foreach($hasChild as $row)
								{
									if($this->isValidUuid($row))
									{
										$childsPKs[] = $row;
									}
								}

								$updateData =  [
									'parc_next_station'  => $recipient,
									'parc_level'  => $recipientLevel,
									'parc_sent_dates'  => $unixTime,
								];

								$update = $model2->update($childsPKs, $updateData);
								if($update)
								{
									$result = [
										'res_device' => $this->request->getVar("device") ?: 'NULL',
										'res_station' => $currentStation['st_title'] ?: 'NULL',
										'res_action' => 'Selling products',
										'res_responce' => 'The products was sold',
										'res_time' => Time::now(),
									];
									$this->recordEvaluationData($result);
									
									$response = [
										'status' => 200,
										'error' => FALSE,
										'messages' => "Success, Sold Successfully",
										'station' => $currentStation['st_title'],
									];
									return $this->respondCreated($response);
								}
								else
								{
									$result = [
										'res_device' => $this->request->getVar("device") ?: 'NULL',
										'res_station' => $currentStation['st_title'] ?: 'NULL',
										'res_action' => 'Selling products',
										'res_responce' => 'Oops! An Internal Server Error Occurred',
										'res_time' => Time::now(),
									];
									$this->recordEvaluationData($result);
									
									$response = [
										'status' => 500,
										'error' => TRUE,
										'messages' => 'Oops! Not Sent, An Internal Server Error Occurred',
										'station' => $currentStation['st_title'],
									];
									return $this->respondCreated($response);
								}
							}
						}

					} 
					else
					{
						$beaconInfo = "";
						try
						{
							$beaconInfo = $model1->where('st_beacon', $beacon)->first();
						}
						catch (\Throwable $e)
						{
							$result = [
								'res_device' => $this->request->getVar("device") ?: 'NULL',
								'res_station' => 'NULL',
								'res_action' => 'End-consumer',
								'res_responce' => 'Internal server error occurred while validating station',
								'res_time' => Time::now(),
							];
							$this->recordEvaluationData($result);
							
							$message = 'An Error Occured While Validating your Store';
				// 			$message = $message . '[' .$e->getMessage() .']';
							$response = [
								'status' => 500,
								'error' => TRUE,
								'messages' => $message,
								'data' => null,
								'station' => $currentStation['st_title'],
							];
							return $this->respondCreated($response);
						}
						if( empty($beaconInfo))
						{
							$result = [
								'res_device' => $this->request->getVar("device") ?: 'NULL',
								'res_station' => 'NULL',
								'res_action' => 'End-consumer',
								'res_responce' => 'The station was not found',
								'res_time' => Time::now(),
							];
							$this->recordEvaluationData($result);
							
							$response = [
								'status' => 500,
								'error' => TRUE,
								'messages' => 'Oops! Store Not Found',
								'data' => $beacon,
							];
							return $this->respondCreated($response);
						}
						else
						{
							$isLastLevelStation = $model1->checkIfIsLastLevelStation($beaconInfo['st_id']);
							if(empty($isLastLevelStation))
							{
								$result = [
									'res_device' => $this->request->getVar("device") ?: 'NULL',
									'res_station' => $currentStation['st_title'] ?: 'NULL',
									'res_action' => 'End-consumer',
									'res_responce' => 'The found station was not registered to sell products to the final consumers',
									'res_time' => Time::now(),
								];
								$this->recordEvaluationData($result);
								
								$response = [
									'status' => 500,
									'error' => TRUE,
									'messages' => 'This Store in Not Authorized to Sell Products to Consumers, Only a Retailer is Authorized',
									'data' => null,
									'station' => $currentStation['st_title'],
								];
								return $this->respondCreated($response);					
							}
							else
							{
								$parcelExistingInfo = "";
								try
								{
									$parcelExistingInfo = $model2->availableForSale($code);  
								}
								catch (\Throwable $e)
								{
									$result = [
										'res_device' => $this->request->getVar("device") ?: 'NULL',
										'res_station' => $currentStation['st_title'] ?: 'NULL',
										'res_action' => 'End-consumer',
										'res_responce' => 'Internal server error while validating if a product is not sold',
										'res_time' => Time::now(),
									];
									$this->recordEvaluationData($result);
									
									$message = 'Oops! An Internal Server Error Occured';
									$message = $message . '[' .$e->getMessage() .']';
									$response = [
										'status' => 500,
										'error' => TRUE,
										'messages' => $message,
										'data' => null,
										'station' => $currentStation['st_title'],
									];
									return $this->respondCreated($response); 
								}

								if($parcelExistingInfo == false)
								{
									$result = [
										'res_device' => $this->request->getVar("device") ?: 'NULL',
										'res_station' => $currentStation['st_title'] ?: 'NULL',
										'res_action' => 'End-consumer',
										'res_responce' => 'Oops! Arleady Sold',
										'res_time' => Time::now(),
									];
									$this->recordEvaluationData($result);
									
									$response = [
										'status' => 500,
										'error' => TRUE,
										'messages' => 'Oops! This Product is Arleady Sold',
										'data' => null,
										'station' => $currentStation['st_title'],
									];
									return $this->respond($response, 200);
								}
								else
								{
									$parcelsToSell = $model2->apiCallParcelsIDsToSend($beaconInfo['st_id']);
									$parcelsToSell2 = array_unique($this->nestedToSingle($parcelsToSell));
									if(! in_array($code, $parcelsToSell2))
									{
										$result = [
											'res_device' => $this->request->getVar("device") ?: 'NULL',
											'res_station' => $currentStation['st_title'] ?: 'NULL',
											'res_action' => 'End-consumer',
											'res_responce' => 'Assuming a counterfeit or misplaced',
											'res_time' => Time::now(),
										];
										$this->recordEvaluationData($result);
										
										$response = [
											'status' => 500,
											'error' => TRUE,
											'messages' => 'This Product Is Not Found, May Be It is Counterfeited or Wrong-Directed',
											'station' => $currentStation['st_title'],
										];
										return $this->respondCreated($response);					
									}
									else
									{
										$unixTime = $parcelExistingInfo['parc_sent_dates']."_". $unixTime;

										$childsPKs = [];
										$hasChild = $model2->getParcelsIDs($code);
										$hasChild = array_unique($this->nestedToSingle($hasChild));
										foreach($hasChild as $row)
										{
											if($this->isValidUuid($row))
											{
												$childsPKs[] = $row;
											}
										}

										if(count($childsPKs) > 6) 
										{
											$result = [
												'res_device' => $this->request->getVar("device") ?: 'NULL',
												'res_station' => $currentStation['st_title'] ?: 'NULL',
												'res_action' => 'End-consumer',
												'res_responce' => 'The user was limited to only five contents',
												'res_time' => Time::now(),
											];
											$this->recordEvaluationData($result);
											
											$response = [
												'status' => 500,
												'error' => TRUE,
												'messages' => 'Too much One-Time Buy, Please Buy Atmost Five Products At a Time',
												'station' => $currentStation['st_title'],
											];
											return $this->respondCreated($response);
										} 
										else
										{
											$updateData =  [
												'parc_sold'  => 1,
												'parc_sent_dates'  => $unixTime,
											];

											$update = $model2->update($childsPKs, $updateData);
											if($update)
											{  
												$result = [
													'res_device' => $this->request->getVar("device") ?: 'NULL',
													'res_station' => $currentStation['st_title'] ?: 'NULL',
													'res_action' => 'End-consumer',
													'res_responce' => 'The product was valid and removed from sell-group',
													'res_time' => Time::now(),
												];
												$this->recordEvaluationData($result);
												
												$name = $model2->where('parc_id', $code)->first();
												$response = [
													'status' => 200,
													'error' => FALSE,
													'messages' => "Congrats!, This Product is Genuine",
													'data' => $name['parc_title'],
													'station' => $currentStation['st_title'],
												];
												return $this->respondCreated($response);
											}
											else
											{
												$result = [
													'res_device' => $this->request->getVar("device") ?: 'NULL',
													'res_station' => $currentStation['st_title'] ?: 'NULL',
													'res_action' => 'End-consumer',
													'res_responce' => 'Internal server error while finalizing to validate a product',
													'res_time' => Time::now(),
												];
												$this->recordEvaluationData($result);
												
												$response = [
													'status' => 500,
													'error' => TRUE,
													'messages' => 'Oops! An Internal Server Error Occured, Try Again',
													'station' => $currentStation['st_title'],
												];
												return $this->respondCreated($response);
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
}

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

class ApiOld extends ResourceController
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
				'messages' => 'No data supplied',
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
					'messages' => "User Found",
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
					'messages' => 'Not Pro user',
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
					'messages' => 'Internal server error'
				];
				return $this->respondCreated($response);
			}
		} 
		else
		{
			$response = [
				'status' => 404,
				'error' => TRUE,
				'messages' => 'Invalid informatios',
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
				'messages' => 'Your information is not found'
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
					'messages' => 'Internal Server Error'
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
					'messages' => "Parcels-Receive Found",
					"data" => $parcels,
				];
				return $this->respondCreated($response);
			} 
			else 
			{
				$response = [
					'status' => 404,
					'error' => TRUE,
					'messages' => 'Parcels-Receive not found'
				];
				return $this->respondCreated($response);
			}
		}
		else
		{
			$response = [
				'status' => 403,
				'error' => TRUE,
				'messages' => 'Device not found',
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
					'messages' => "Parcels-Send Found",
					"data" => $parcels,
				];
				return $this->respondCreated($response);
			} 
			else 
			{
				$response = [
					'status' => 500,
					'error' => TRUE,
					'messages' => 'Parcels-Send not found'
				];
				return $this->respondCreated($response);
			}
		}
		else
		{
			$response = [
				'status' => 500,
				'error' => TRUE,
				'messages' => 'Device not found'
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
					'messages' => "Stations Found",
					"data" => $getStations,
				];
				return $this->respondCreated($response);
			}
			else
			{
				$response = [
					'status' => 500,
					'error' => TRUE,
					'messages' => 'Stations not found'
				];
				return $this->respondCreated($response);
			}
		}
		else
		{
			$response = [
				'status' => 500,
				'error' => TRUE,
				'messages' => 'No such user'
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
			$response = [
				'status' => 500,
				'error' => TRUE,
				'messages' => 'No data supplied',
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
			
			// 			$location = $model1->getBeaconLocation($this->request->getVar("beacon"));
			// 			if(!$location)
			// 			{
				// 				$response = [
					// 					'status' => 500,
					// 					'error' => TRUE,
					// 					'messages' => 'Your station has no location details, please ask station manager to contact as',
					// 				];
					// 				return $this->respondCreated($response);
					// 			}
					// 			else
					// 			{	
						// 				$lat1 = (float)$location['st_lat'];
						// 				$lon1 = (float)$location['st_lon'];
						// 				$lat2 = $this->request->getVar("latitude");
						// 				$lon2 = $this->request->getVar("longitude");
						
						// 				$distance = $this->geoDistance($lat1, $lon1, $lat2, $lon2, "K");
						// 				if($distance > 0.003)
						// 				{
							// 				    $distance = number_format((float)$distance, 2, '.', '');
							// 				    $message = '[' .$distance . 'Km]' . 'Oops!, you must be within 3 metres distance form your station.';
							// 					$response = [
								// 						'status' => 500,
								// 						'error' => TRUE,
								// 						'messages' => $message,
								// 					];
								// 					return $this->respondCreated($response);
								// 				}
								// 				else
								// 				{
									$code =  $this->encryptionService($this->request->getVar("code"), 1);
									if($code == false)
									{
										$response = [
											'status' => 500,
											'error' => TRUE,
											'messages' => 'Oops!, we did not created such type of content',
											'station' => $currentStation['st_title'],
										];
										return $this->respondCreated($response);
									}
									else
									{
										if( ! $this->isValidUuid($code))
										{
											$response = [
												'status' => 500,
												'error' => TRUE,
												'messages' => 'Oops!, Invalid data format',
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
													$response = [
														'status' => 500,
														'error' => TRUE,
														'messages' => 'Seems like you are not at your place',
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
															$response = [
																'status' => 500,
																'error' => TRUE,
																'messages' => 'Not Received, internal server error',
																'station' => $currentStation['st_title'],
															];
															return $this->respondCreated($response);
														}
													}
													else
													{
														$response = [
															'status' => 500,
															'error' => TRUE,
															'messages' => 'Not sent to your station',
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
													$response = [
														'status' => 500,
														'error' => TRUE,
														'messages' => 'Invalid Station, it seems like your not a distributor',
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
														$response = [
															'status' => 500,
															'error' => TRUE,
															'messages' => 'Not available at your station',
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
															$response = [
																'status' => 200,
																'error' => FALSE,
																'messages' => "Sent Successfully",
																'station' => $currentStation['st_title'],
															];
															return $this->respondCreated($response);
														}
														else
														{
															$response = [
																'status' => 500,
																'error' => TRUE,
																'messages' => 'Not sent, internal server error',
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
													$message = 'An error occured while validating your station';
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
												if(! empty($beaconInfo))
												{
													$isLastLevelStation = $model1->checkIfIsLastLevelStation($beaconInfo['st_id']);
													if(empty($isLastLevelStation))
													{
														$response = [
															'status' => 500,
															'error' => TRUE,
															'messages' => 'Station Found, but not authorized to serve final consumers',
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
													      $parcelExistingInfo = $model2->checkIfNotSold($code);  
													    }
													    catch (\Throwable $e)
													    {
													        $message = 'An error occured';
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
															$response = [
																'status' => 500,
																'error' => TRUE,
																'messages' => 'Product Not Found, may be sold/removed from a supply chain',
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
																$response = [
																	'status' => 500,
																	'error' => TRUE,
																	'messages' => 'Not available here, may be it is a counterfeit or misplaced',
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
																
																if(count($childsPKs) > 4) 
																{
																	
																	$response = [
																		'status' => 500,
																		'error' => TRUE,
																		'messages' => 'Too much contents, you\'re limited to four contents',
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
																		$name = $model2->where('parc_id', $code)->first();
																		$response = [
																			'status' => 200,
																			'error' => FALSE,
																			'messages' => "Product is valid",
																			'data' => $name['parc_title'],
																			'station' => $currentStation['st_title'],
																		];
																		return $this->respondCreated($response);
																	}
																	else
																	{
																		$response = [
																			'status' => 500,
																			'error' => TRUE,
																			'messages' => 'Not validated, internal server error',
																			'station' => $currentStation['st_title'],
																		];
																		return $this->respondCreated($response);
																	}
																}
															}
														}
													}
												}
												else
												{
													$response = [
														'status' => 500,
														'error' => TRUE,
														'messages' => 'Station Not Found',
														'data' => $beacon,
													];
													return $this->respondCreated($response);
												}
											}
										}
									}
									// }
									// 			}
								}
							}
							
							
							// /**
							//  * Return an array of resource objects, themselves in array format
							//  *
							//  * @return mixed
							//  */
							// public function index()
							// {
								// 	//
								// }
								
								// /**
								//  * Return the properties of a resource object
								//  *
								//  * @return mixed
								//  */
								// public function show($id = null)
								// {
									// 	//
									// }
									
									// /**
									//  * Return a new resource object, with default properties
									//  *
									//  * @return mixed
									//  */
									// public function new()
									// {
										// 	//
										// }
										
										// /**
										//  * Create a new resource object, from "posted" parameters
										//  *
										//  * @return mixed
										//  */
										// public function create()
										// {
											// 	//
											// }
											
											// /**
											//  * Return the editable properties of a resource object
											//  *
											//  * @return mixed
											//  */
											// public function edit($id = null)
											// {
												// 	//
												// }
												
												// /**
												//  * Add or update a model resource, from "posted" properties
												//  *
												//  * @return mixed
												//  */
												// public function update($id = null)
												// {
													// 	//
													// }
													
													// /**
													//  * Delete the designated resource object from the model
													//  *
													//  * @return mixed
													//  */
													// public function delete($id = null)
													// {
														// 	//
														// }
													}
													
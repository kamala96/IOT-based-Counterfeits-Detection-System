<?php

namespace App\Controllers;
use App\Models\IntermediaryModel;
use App\Models\StationModel;

use App\Controllers\BaseController;

class Intermediary extends BaseController
{	
	public function index()
	{
		$model1 = new IntermediaryModel();
		$model2 = new StationModel();
		$session = session();
		helper(['form']);
		$data = [];
		$pageTitle = 'Administrators';
		if ($this->request->getMethod() == 'post')
		{
			$rules = [
				'first_name' => 'required|min_length[3]|max_length[20]',
				'last_name' => 'required|min_length[3]|max_length[20]',
				'email' => 'required|min_length[6]|max_length[50]|valid_email|is_unique[intermediaries.int_mail]',
				'phone' => 'required|min_length[10]|max_length[15]|is_unique[intermediaries.int_phone]',
				'station' => 'required|integer',
				'password' => 'required|min_length[6]|max_length[15]',
				'confpassword'  => 'matches[password]',
			];
			$errors =
			[   
				'password' => [
					'min_length' => 'Your password is too short. It will be easy to get hacked?',
					'max_length' => 'Come on we don\'t have that much space, only provide your password with 8 characters',
				],
				'email' => [
					'is_unique' => 'We\'ve this email arleady, please choose another.',
				],
				'station' => [
					'numeric' => 'Please provide a valid station.',
				],
				'confpassword' => [
					'matches' => 'Password did not match.',
				],
			];
			if(! $this->validate($rules, $errors))
			{
				// $data = [
					// 	'title' => $pageTitle,
					// 	'managers' => $this->processManagersAccordingToUser(),
					// 	'stations' => $this->processStationsAccordingToUser(),
					// 	'validation' => $this->validator,
					// ];
					// return view('pages/managers_page', $data);
					$data = $this->validator->listErrors();
					echo json_encode(array("status" => false , 'data' => $data));
				}
				else
				{
					$savedata = [
						'int_fname' => $this->request->getVar('first_name'),
						'int_lname' => $this->request->getVar('last_name'),
						'int_phone' => $this->request->getVar('phone'),
						'int_mail' => $this->request->getVar('email'),
						'int_password' => $this->request->getVar('password'),
						'int_station' => $this->request->getVar('station'),
					];
					if($model1->save($savedata))
					{
						// return redirect()->route("managers_list")->with("sucess", "Successfully, data has been saved");
						echo json_encode(array("status" => true , 'data' => 'Success!, data has been saved'));				
					}
					else
					{
						echo json_encode(array("status" => false , 'data' => 'Oops!, data not saved'));
					}
				}			
			}
			else
			{
				$data = [
					'title' => $pageTitle,
					'intermediaries' => $model1->getIntermediaries($session->get('system_id'), $session->get('level_int')),
					'stations' => $model2->getStations($session->get('system_id'), $session->get('level_int')),
				];
				return view('pages/intermediary_page', $data);
			}
		}
		
		public function deleteIntermediary()
		{
			$model = new IntermediaryModel();
			$rules = [
				'int_id' => 'required',
			];
			if( ! $this->validate($rules))
			{	
				$data = $this->validator->listErrors();
				echo json_encode(array("status" => false , 'data' => $data));
			}
			else
			{
				$deleteId = $this->request->getVar('int_id');
				$delete = $model->delete($deleteId);
				if(! $delete)
				{
					echo json_encode(array("status" => false, "data" => "Internal server error"));
				}
				else
				{
					echo json_encode(array("status" => true, "data" => "Success!, station deleted successfully"));
				}
			}
			
		}
	}
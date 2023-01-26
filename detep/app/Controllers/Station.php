<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StationModel;
use App\Models\LevelModel;

class Station extends BaseController
{
	public function index()
	{
		$model1 = new StationModel();
		$model2 = new LevelModel();
		$session = session();
		helper(['form']);
		$data = [];
		$pageTitle = 'Stores';
		if ($this->request->getMethod() == 'post')
		{
			$rules = [
				'name' => 'required|min_length[3]|max_length[100]',
				'level' => 'required|integer',
				'descriptions' => 'max_length[200]',
			];
			$errors =
			[   
				'name' => [
					'required' => 'Title is required!',
					'min_length' => 'Oops!, too short',
					'max_length' => 'Ooops!, too long',
					]
				];
				if(! $this->validate($rules, $errors))
				{
					$data = $this->validator->listErrors();
					echo json_encode(array("status" => false , 'data' => $data));
				}
				else
				{
					$savedata = [
						'st_title' => $this->request->getVar('name'),
						'st_level' => $this->request->getVar('level'),
						'st_description' => $this->request->getVar('descriptions') != "" ? $this->request->getVar('descriptions') : NULL,
						'st_system' => $session->get('system_id'),
					];
					if($model1->save($savedata))
					{
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
					'stations' => $model1->getStations($session->get('system_id'), $session->get('level_int')),
					'levels' => $model2->where('lv_int >', $session->get('level_int'))->findAll(),
				];
				return view('pages/station_page', $data);
			}
		}
		
		public function deleteStation()
		{
			$model = new StationModel();
			$rules = [
				'station_id' => 'required',
			];
			if( ! $this->validate($rules))
			{	
				$data = $this->validator->listErrors();
				echo json_encode(array("status" => false , 'data' => $data));
			}
			else
			{
				$deleteId = $this->request->getVar('station_id');
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
	
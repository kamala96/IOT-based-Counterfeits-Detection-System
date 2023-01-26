<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LevelModel;

class Level extends BaseController
{
	public function index()
	{
		$model = new LevelModel();
		$session = session();
		helper(['form']);
		$data = [];
		$pageTitle = 'Levels';
		if ($this->request->getMethod() == 'post')
		{
			$rules = [
				'count' => 'required|is_natural_no_zero|greater_than[1]|less_than[7]',
			];
			$errors =
			[   
				'count' => [
					'required' => 'Please enter level count',
					'is_natural_no_zero' => 'This field must only contain digits and must be greater than zer0',
					'greater_than' => 'Oops!, out of range',
					'less_than' => 'Ooops!, out of range-contact us',
					]
				];
				if(! $this->validate($rules, $errors))
				{
					$data = $this->validator->listErrors();
					echo json_encode(array("status" => false , 'data' => $data));
				}
				else
				{
					$current = $model->getLevelCount($session->get('slug'));
					$new = $this->request->getVar('count');
					if($current != $new)
					{
						$save = $model->updateDistributionLevels($session->get('slug'), $new);
						if($save)
						{
							$current = $model->getLevelCount($session->get('slug'));
							echo json_encode(array("status" => true , 'data' => $current));				
						}
						else
						{
							echo json_encode(array("status" => false , 'data' => 'Oops!, data not saved'));
						}
					}
					else
					{
						echo json_encode(array("status" => false , 'data' => 'Seems like you have changed nothing'));
					}
				}			
			}
			else
			{
				$data = [
					'title' => $pageTitle,
					'levels' => $model->getLevelCount($session->get('slug')),
				];
				return view('pages/level_page', $data);
			}
		}
	}
	
<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\ProductModel;

class Product extends BaseController
{
	public function index()
	{
		$model1 = new ProductModel();
		$model2 = new CategoryModel();
		$session = session();
		helper(['form']);
		$data = [];
		$pageTitle = 'Products';
		if ($this->request->getMethod() == 'post')
		{
			$rules = [
				'name' => 'required|min_length[3]|max_length[100]',
				'category' => 'required|integer',
			];
			$errors =
			[   
				'name' => [
					'required' => 'Please a product name',
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
						'prod_name' => $this->request->getVar('name'),
						'prod_slug' => $this->slugCreator($this->request->getVar('name')),
						'prod_category' => $this->request->getVar('category'),
						'prod_system' => $session->get('system_id'),
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
					'categories' => $model2->findAll(),
					'products' => $model1->getProducts($session->get('system_id'), $session->get('mg')),
				];
				return view('pages/product_page', $data);
			}
		}
		
		protected function slugCreator($string) 
		{
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
		
		public function deleteProduct()
		{
			$model = new ProductModel();
			$rules = [
				'product_id' => 'required',
			];
			if( ! $this->validate($rules))
			{	
				$data = $this->validator->listErrors();
				echo json_encode(array("status" => false , 'data' => $data));
			}
			else
			{
				$deleteId = $this->request->getVar('product_id');
				$delete = $model->delete($deleteId);
				if(! $delete)
				{
					echo json_encode(array("status" => false, "data" => "Internal server error"));
				}
				else
				{
					echo json_encode(array("status" => true, "data" => "Success!, product deleted successfully"));
				}
			}
			
		}
	}
	
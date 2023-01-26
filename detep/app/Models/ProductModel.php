<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'products';
	protected $primaryKey           = 'prod_id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDelete        = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'prod_name',
		'prod_slug',
		'prod_category',
		'prod_system',
		'prod_regdate',
		'prod_updatedat',
		'prod_deletedat',
	];

	// Dates
	protected $useTimestamps        = true;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'prod_regdate';
	protected $updatedField         = 'prod_updatedat';
	protected $deletedField         = 'prod_deletedat';

	// Validation
	protected $validationRules      = [];
	protected $validationMessages   = [];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks       = true;
	protected $beforeInsert         = [];
	protected $afterInsert          = [];
	protected $beforeUpdate         = [];
	protected $afterUpdate          = [];
	protected $beforeFind           = [];
	protected $afterFind            = [];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];

	function getProducts($system, $additional_system)
	{
		$result = null;
		$this->join('categories c', 'c.cat_id = products.prod_category', 'LEFT');
		$this->join('systems s', 's.sy_id = products.prod_system', 'LEFT');
		$this->select('s.sy_title');
		$this->select('c.cat_name');
		$this->select('products.*');
		if($additional_system != false)
		{
			$systems = explode("-", $additional_system);
			$this->whereIn('s.sy_slug', $systems);
			$result = $this->findAll();	
		}
		else
		{
			$this->where('products.prod_system', $system);
			$result = $this->findAll();	
		}
		return $result;
	}
}

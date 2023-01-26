<?php

namespace App\Models;

use CodeIgniter\Model;

class MedicineModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'medicines';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = false;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDelete        = true;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id',
		'title',
		'parent_id',
		'qrcodelink',
		'medicine_system',
		'created_at',
		'updated_at',
		'deleted_at',
	];
	
	// Dates
	protected $useTimestamps        = true;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';
	protected $deletedField         = 'deleted_at';
	
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
	
	public function getFirstLevelPercels($tmda=false,$system=null)
	{
		$this->join('distributionsystems s', 's.id = medicines.medicine_system', 'LEFT');
		// $this->join('paercelsent ps', 'ps.id = medicines.medicine_system', 'LEFT');
		$this->select('medicines.*');
		$this->select('s.title AS system');
		if(! $tmda) $this->where('medicines.medicine_system', $system);
		$this->where('medicines.parent_id IS NULL', NULL);

		$result = $this->find();
		return $result;
	}
	
	public function getPercelById($id)
	{
		$this->where('parent_id', $id);
		$parent = $this->asObject()->findAll();
		$categories = $parent;
		$i=0;
		foreach($categories as $p_cat)
		{
			$categories[$i]->sub = $this->sub_percel($p_cat->id);
			$i++;
		}
		return $categories;
	}
	
	protected function sub_percel($id)
	{
		$this->where('parent_id', $id);
		$child = $this->asObject()->findAll();
		$categories = $child;
		$i=0;
		foreach($categories as $p_cat){
			
			$categories[$i]->sub = $this->sub_percel($p_cat->id);
			$i++;
		}
		return $categories;       
	}
	
	public function countExisting($parent)
	{
		$this->where('parent_id', $parent);
		$query = $this->countAllResults();
		return $query;
	}
	
	public function countLeaf()
	{
		$this->join('medicines m2', 'm2.parent_id = medicines.id', 'LEFT');
		$this->select('medicines.*');
		$this->where('m2.id IS NULL', NULL);
		$query = $this->countAllResults();
		return $query;
	}
}
<?php

namespace App\Models;

use CodeIgniter\Model;

class LevelModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'levels';
	protected $primaryKey           = 'lv_id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDelete        = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'lv_title',
		'lv_int',
		'lv_description',
	];
	
	// Dates
	protected $useTimestamps        = false;
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
	
	protected function findSecondLast($slug)
	{
		$this->like('lv_title', $slug, 'both');
		$result = $this->first();
		if($result) return $result;
		else return false;
	}
	
	public function getLevelCount($system_slug)
	{
		if($this->findSecondLast($system_slug) != false)
		{
			$data = $this->findSecondLast($system_slug);
			$level_int = $data['lv_int'];
			$level_count = $level_int + 1;
			return $level_count;
		}
		else
		{
			return false;
		}
	}
	
	public function updateDistributionLevels($slug, $update)
	{
		$initialize = $this->findSecondLast($slug);
		if($initialize)
		{
			$where = $update - 1;
			$whereToUpdate = $this->where('lv_int', $where)->first();
			$id = $whereToUpdate['lv_id'];
			if($id != '')
			{
				$appendTitle = $whereToUpdate['lv_title'];
				$appendTitle = $appendTitle . '-';
				$appendTitle = $appendTitle . $slug;
				$this->set('lv_title', $appendTitle);
				$this->where('lv_id', $id);
				$update1 = $this->update();
				if($update1)
				{
					$level_id = $initialize['lv_id'];
					$level_int = $initialize['lv_int'];
					$lv_title = $initialize['lv_title'];
					$to_replace = '-' . $slug;
					$lv_title = str_replace($to_replace, '', $lv_title);
					$this->set('lv_title', $lv_title);
					$this->where('lv_id', $level_id);
					$update2 = $this->update();
					if($update2)
					{
						return true;
					}
					else
					{
						return false;
					}
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
}
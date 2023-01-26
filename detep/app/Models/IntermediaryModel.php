<?php

namespace App\Models;

use CodeIgniter\Model;

class IntermediaryModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'intermediaries';
	protected $primaryKey           = 'int_id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDelete        = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'int_fname',
		'int_lname',
		'int_phone',
		'int_mail',
		'int_password',
		'int_station',
		'int_device',
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
	protected $beforeInsert         = ["beforeInsert"];
	protected $afterInsert          = [];
	protected $beforeUpdate         = [];
	protected $afterUpdate          = [];
	protected $beforeFind           = [];
	protected $afterFind            = [];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];
	
	protected function beforeInsert(array $data)
	{
		$data = $this->passwordHash($data);
		return $data;
	}
	
	protected function passwordHash(array $data)
	{
		if (isset($data['data']['int_password'])) {
			$data['data']['int_password'] = password_hash($data['data']['int_password'], PASSWORD_DEFAULT);
		}
		
		return $data;
	}
	
	public function getIntermediary($email)
	{
		$this->join('stations st', 'st.st_id = intermediaries.int_station', 'LEFT');
		$this->join('levels lv', 'lv.lv_id = st.st_level', 'LEFT');
		$this->join('systems sy', 'sy.sy_id = st.st_system', 'LEFT');
		$this->select('st.*');
		$this->select('sy.*');
		$this->select('lv.*');
		$this->select('intermediaries.*');
		$this->where('intermediaries.int_mail', $email);
		$result = $this->first();	
		return $result;
	}
	
	public function getIntermediaries($system, $level)
	{
		$this->join('stations st', 'st.st_id = intermediaries.int_station', 'LEFT');
		$this->join('levels lv', 'lv.lv_id = st.st_level', 'LEFT');
		$this->join('systems sy', 'sy.sy_id = st.st_system', 'LEFT');
		$this->select('st.*');
		$this->select('sy.*');
		$this->select('lv.*');
		$this->select('intermediaries.*');
		$this->where('sy.sy_id', $system);
		$this->where('lv.lv_int >', $level);
		$result = $this->findAll();	
		return $result;
	}

	public function submitPro($id, $data)
	{
		$this->set('int_device', $data);
		$this->where('int_id', $id);;
		$update = $this->update();
		return $update;
	}

	public function getDeviceInfo($device)
	{
		$this->join('stations st', 'st.st_id = intermediaries.int_station', 'LEFT');
		$this->join('levels lv', 'lv.lv_id = st.st_level', 'LEFT');
		$this->join('systems sy', 'sy.sy_id = st.st_system', 'LEFT');
		$this->select('st.*');
		$this->select('sy.*');
		$this->select('lv.*');
		$this->select('intermediaries.*');
		$this->where('intermediaries.int_device', $device);
		$result = $this->first();	
		return $result;
	}

	public function resetPro($id)
	{
		$this->set('int_device', NULL);
		$this->where('int_id', $id);
		$update = $this->update();
		return $update;
	}
}

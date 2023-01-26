<?php

namespace App\Models;

use CodeIgniter\Model;

class PercelsentModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'percelsent';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDelete        = true;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'percel_sent',
		'sender',
		'send_from',
		'send_to',
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

	// public function insert_data($data)
	// {
	// 	if($this->db->table($this->table)->insert($data))
    //     {
    //         return $this->db->insertID();
    //     }
    //     else
    //     {
    //         return false;
	// 	}        
	// }
}

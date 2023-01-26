<?php

namespace App\Models;

use CodeIgniter\Model;

class DownloadModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'downloads';
	protected $primaryKey           = 'down_id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDelete        = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'down_count',
		'down_last',
	];

	// Dates
	protected $useTimestamps        = false;
	protected $dateFormat           = 'datetime';
	protected $createdField         = '';
	protected $updatedField         = '';
	protected $deletedField         = '';

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

	function addDownloads($last_download)
	{
	    
			$this->set('down_count', 'down_count+1', false);
			$this->set('down_last', $last_download);
			$this->where('down_id', 1);
			$result = $this->update();
			return $result;
	}
}

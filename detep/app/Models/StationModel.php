<?php

namespace App\Models;

use CodeIgniter\Model;

class StationModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'stations';
	protected $primaryKey           = 'st_id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDelete        = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'st_title',
		'st_description',
		'st_level',
		'st_system',
		'st_beacon',
		'st_lat',
		'st_lon',
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
	
	public function getBeacons()
	{
		$this->select('st_beacon, st_title');
		$this->where('st_beacon IS NOT NULL', NULL);
		$result = $this->findAll();
		$data = [];
		if(!empty($result))
		{
			// foreach($result as $row)
			// {
				// 	$data[] = $row['st_beacon'];
				// }
				// return $data;
				return $result;
			}
			else
			{
				return false;
			}
		}
		
		public function getStations($system, $level)
		{
			$this->join('levels lv', 'lv.lv_id = stations.st_level', 'LEFT');
			$this->join('systems sy', 'sy.sy_id = stations.st_system', 'LEFT');
			$this->select('lv.lv_title');
			$this->select('stations.*');
			$this->where('stations.st_system', $system);
			// if(strpos($level_text, '-') !== false) 
			// {
				// 	$this->where('lv.lv_int', 100);
				// }
				// else
				// {
					// 	$this->where('lv.lv_int >', $level);
					// 	$this->where('lv.lv_int', 100);
					// }
					$this->where('lv.lv_int >', $level);
					$result = $this->findAll();	
					return $result;
				}
				
				public function getOnlyImmidiateStations($system, $level, $isSecondLast)
				{
					$level = $level + 1;
					$this->join('levels lv', 'lv.lv_id = stations.st_level', 'LEFT');
					$this->join('systems sy', 'sy.sy_id = stations.st_system', 'LEFT');
					$this->select('lv.*');
					$this->select('stations.*');
					$isSecondLast == true ? $this->where('lv.lv_int', 100) 
					: $this->where('lv.lv_int', $level);
					$this->where('sy.sy_id', $system);
					$result = $this->findAll();	
					return $result;
				}
				
				public function stationDetails($id)
				{
					$this->join('levels lv', 'lv.lv_id = stations.st_level', 'LEFT');
					$this->join('intermediaries i', 'i.int_station = stations.st_id', 'LEFT');
					$this->select('i.*');
					$this->select('lv.*');
					$this->select('stations.*');
					$this->where('stations.st_id', $id);
					$result = $this->first();	
					return $result;
				}
				
				public function getImmidiateForAPI($system, $level, $isSecondLast)
				{
					$level = $level + 1;
					$this->join('levels lv', 'lv.lv_id = stations.st_level', 'LEFT');
					$this->join('systems sy', 'sy.sy_id = stations.st_system', 'LEFT');
					// $this->select('lv.*');
					$this->select('stations.st_id,stations.st_title,stations.st_description');
					$isSecondLast == true ? $this->where('lv.lv_int', 100) 
					: $this->where('lv.lv_int', $level);
					$this->where('sy.sy_id', $system);
					$result = $this->findAll();	
					return $result;
				}
				
				public function checkIfIsLastLevelStation($station)
				{
					$this->join('levels lv', 'lv.lv_id = stations.st_level', 'LEFT');
					$this->select('lv.*');
					$this->select('stations.*');
					$this->where('lv.lv_int', 100);
					$this->where('stations.st_id', $station);
					$result = $this->first();	
					return $result;
				}
				
				public function getBeaconLocation($beacon)
				{
					$this->select('st_lat, st_lon');
					$this->where('st_beacon', $beacon);
					$this->where('st_lat IS NOT NULL', NULL);
					$this->where('st_lon IS NOT NULL', NULL);
					$result = $this->first();
					if( ! empty($result))
					{
						return $result;
					}
					else
					{
						return false;
					}
				}
			}
			
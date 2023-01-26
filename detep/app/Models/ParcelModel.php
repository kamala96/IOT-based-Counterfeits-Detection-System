<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class ParcelModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'parcels';
	protected $primaryKey           = 'parc_id';
	protected $useAutoIncrement     = false;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDelete        = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'parc_id',
		'parc_title',
		'parc_parent',
		'parc_product',
		'parc_level',
		'parc_station_path',
		'parc_next_station',
		'parc_sent_dates',
		'parc_arrival_dates',
		'parc_qrcodelink',
		'parc_sold',
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
	
	protected function subParcels($id)
	{
		$this->join('products p', 'p.prod_id = parcels.parc_product', 'LEFT');
		$this->join('systems s', 's.sy_id = p.prod_system', 'LEFT');
		$this->select('s.sy_title');
		$this->select('p.prod_name');
		$this->select('parcels.*');
		$this->where('parcels.parc_parent', $id);
		$child = $this->asArray()->findAll();
		$categories = $child;
		$i=0;
		foreach($categories as $p_cat){
			
			$categories[$i]['sub'] = $this->subParcels($p_cat['parc_id']);
			$i++;
		}
		return $categories;       
	}
	
	protected function subParcelsIDs($id)
	{
		$this->select('parcels.parc_id');
		$this->where('parcels.parc_parent', $id);
		$this->where('parcels.parc_sold', 0);
		$child = $this->asArray()->findAll();
		$categories = $child;
		$i=0;
		foreach($categories as $p_cat){
			
			$categories[$i]['sub'] = $this->subParcelsIDs($p_cat['parc_id']);
			$i++;
		}
		return $categories;       
	}
	
	protected function subIDsToReset($id)
	{
		$this->select('parcels.parc_id');
		$this->where('parcels.parc_parent', $id);
		// $this->where('parcels.parc_sold', 0);
		$child = $this->asArray()->findAll();
		$categories = $child;
		$i=0;
		foreach($categories as $p_cat){
			
			$categories[$i]['sub'] = $this->subIDsToReset($p_cat['parc_id']);
			$i++;
		}
		return $categories;       
	}
	
	protected function subApiCallParcels($id, $receive=true)
	{
		$this->join('products p', 'p.prod_id = parcels.parc_product', 'LEFT');
		$this->join('systems s', 's.sy_id = p.prod_system', 'LEFT');
		$this->select('s.sy_title');
		$this->select('p.prod_name');
		$this->select('parcels.parc_id, parcels.parc_title, parcels.parc_sent_dates, parcels.parc_arrival_dates');
		$this->where('parcels.parc_parent', $id);
		$this->where('parcels.parc_sold', 0);
		$child = $this->findAll();
		$categories = $child;
		// $i=0;
		foreach($categories as $key => $p_cat){
			$date_string = $receive ? $p_cat['parc_sent_dates'] : $p_cat['parc_arrival_dates'];
			$date = explode("_", $date_string);
			$required_date = end($date);
			$final_date = Time::createFromTimestamp($required_date);
			$newKey = $receive ? 'parc_sent_dates' : 'parc_arrival_dates';
			$removeKey = $receive ? 'parc_arrival_dates' : 'parc_sent_dates';
			$categories[$key][$newKey] = $final_date;
			
			$recallVale = $receive ? true : false;
			$categories[$key]['sub'] = $this->subApiCallParcels($p_cat['parc_id'], $recallVale);
			//$i++;
			unset($categories[$key]['parc_id']);
			unset($categories[$key][$removeKey]);
		}
		return $categories;
		
	}
	
	
	protected function subApiCallParcelsIDS($id)
	{
		$this->select('parc_id');
		$this->where('parc_parent', $id);
		$this->where('parc_sold', 0);
		$child = $this->asArray()->findAll();
		$categories = $child;
		foreach($categories as $key => $p_cat){
			$categories[$key]['sub'] = $this->subApiCallParcelsIDS($p_cat['parc_id']);
		}
		return $categories;
		
	}
	
	protected function nestedToSingle(array $array)
	{
		$singleDimArray = [];
		
		foreach ($array as $item) {
			
			if (is_array($item)) {
				$singleDimArray = array_merge($singleDimArray, $this->nestedToSingle($item));
				
			} else {
				$singleDimArray[] = $item;
			}
		}
		
		return $singleDimArray;
	}
	
	
	
	
	
	public function getParcels($singleSystem=false, $multipleSystems=false)
	{
		$this->join('products p', 'p.prod_id = parcels.parc_product', 'LEFT');
		$this->join('systems s', 's.sy_id = p.prod_system', 'LEFT');
		$this->select('s.sy_title');
		$this->select('p.prod_name');
		$this->select('parcels.*');
		$this->where('parcels.parc_parent IS NULL', NULL);
		if($singleSystem != false)
		{
			$this->where('p.prod_system', $singleSystem);
		}
		else
		{
			$systems = explode("-", $multipleSystems);
			$this->whereIn('s.sy_slug', $systems);			
		}
		
		$parent = $this->asArray()->findAll();
		$categories = $parent;
		$i=0;
		foreach($categories as $p_cat)
		{
			$categories[$i]['sub'] = $this->subParcels($p_cat['parc_id']);
			$i++;
		}
		return $categories;
	}
	
	public function getParcels2($singleSystem=false, $multipleSystems=false)
	{
		$this->join('products p', 'p.prod_id = parcels.parc_product', 'LEFT');
		$this->join('systems s', 's.sy_id = p.prod_system', 'LEFT');
		$this->select('s.sy_title');
		$this->select('p.prod_name');
		$this->select('parcels.*');
		if($singleSystem != false)
		{
			$this->like('parcels.parc_next_station', $singleSystem, 'both'); //what sent to you
			$this->orLike('parcels.parc_station_path', $singleSystem, 'before'); // what passed to you
		}
		else
		{
			$systems = explode("-", $multipleSystems);
			$this->whereIn('s.sy_slug', $systems);
		}
		$parent = $this->asArray()->findAll();
		$categories = $parent;
		$parents = [];
		
		foreach($categories as $key => $p_cat)
		{
			$categories[$key]['sub'] = $this->subParcels($p_cat['parc_id']);
			if(!empty($categories[$key]['sub']))
			{
				$parents[] = $p_cat['parc_id'];
			}
		}
		
		foreach($categories as $key => $cat)
		{
			if(empty($cat['sub']))
			{
				// $new_parents = array_map(function($piece){
					// 	return (string) $piece;
					// }, $parents);
					if (in_array($cat['parc_parent'], $parents)) {
						unset($categories[$key]);
					}
				}
			}
			return $categories;
		}
		
		public function readyParcels($station)
		{
			$this->select('parc_id, parc_title');
			$this->where('parc_parent IS NULL', NULL);
			$this->where('parc_station_path', $station);
			$this->where('parc_next_station IS NULL', NULL);
			$results = $this->findAll();
			
			$results = array_map(function($result) {
				return array(
					'value' => $result['parc_id'],
					'text' => $result['parc_title']
				);
			}, $results);
			return $results;
		}
		
		public function getParcelsIDs($id)
		{
			$this->select('parcels.parc_id');
			$this->where('parcels.parc_id', $id);
			$this->where('parcels.parc_sold', 0);
			$parent = $this->asArray()->findAll();
			$categories = $parent;
			$i=0;
			foreach($categories as $p_cat)
			{
				$categories[$i]['sub'] = $this->subParcelsIDs($p_cat['parc_id']);
				$i++;
			}
			return $categories;
		}
		
		public function getIDsToReset($id)
		{
			$this->select('parcels.parc_id');
			$this->where('parcels.parc_id', $id);
			// $this->where('parcels.parc_sold', 0);
			$parent = $this->asArray()->findAll();
			$categories = $parent;
			$i=0;
			foreach($categories as $p_cat)
			{
				$categories[$i]['sub'] = $this->subIDsToReset($p_cat['parc_id']);
				$i++;
			}
			return $categories;
		}
		
		public function apiCallParcelsIDsToReceive($station)
		{
			$this->select('parc_id');
			$this->where('parc_next_station', $station);
			$this->where('parc_sold', 0);
			$parent = $this->asArray()->findAll();
			$categories = $parent;
			
			foreach($categories as $key => $p_cat)
			{
				$categories[$key]['sub'] = $this->subApiCallParcelsIDS($p_cat['parc_id']);
			}
			return $categories;
		}
		
		public function apiCallParcelsIDsToSend($station)
		{
			$this->select('parc_id, parc_station_path');
			$this->where('parc_next_station IS NULL', NULL);
			$this->where('parc_sold', 0);
			$this->havingLike('parc_station_path', $station, 'before');
			$parent = $this->asArray()->findAll();
			$categories = $parent;
			foreach($categories as $key => $p_cat)
			{
				$categories[$key]['sub'] = $this->subApiCallParcelsIDS($p_cat['parc_id']);
				unset($categories[$key]['parc_station_path']);
			}
			return $categories;
		}
		
		public function moveParcel($id, $moveTo)
		{
			$this->set('parc_parent', $moveTo);
			$this->where('parc_id', $id);
			$result = $this->update();
			return $result;
		}
		
		public function apiCallParcelsToReceive($station)
		{
			$this->join('products p', 'p.prod_id = parcels.parc_product', 'LEFT');
			$this->join('systems s', 's.sy_id = p.prod_system', 'LEFT');
			$this->select('s.sy_title');
			$this->select('p.prod_name');
			$this->select('parcels.parc_id, parcels.parc_title, parcels.parc_sent_dates, parcels.parc_parent');
			$this->where('parcels.parc_next_station', $station);
			$this->where('parcels.parc_sold', 0);
			$parent = $this->findAll();
			$categories = $parent;
			$i=0;
			$parents = [];
			foreach($categories as $p_cat)
			{
				$categories[$i]['sub'] = $this->subApiCallParcels($p_cat['parc_id']);
				if(!empty($categories[$i]['sub']))
				{
					$parents[] = $p_cat['parc_id'];
				}
				$i++;
			}
			
			foreach($categories as $key => $cat)
			{
				$date = explode("_", $cat['parc_sent_dates']);
				$required_date = end($date);
				$final_date = Time::createFromTimestamp($required_date);
				$categories[$key]['parc_sent_dates'] = $final_date;
				if(empty($cat['sub']))
				{
					if (in_array($cat['parc_parent'], $parents)) {
						unset($categories[$key]);
					}
				}
				unset($categories[$key]['parc_id']);
				unset($categories[$key]['parc_parent']);
			}
			return $categories;
		}
		
		public function apiCallParcelsToSend($station)
		{
			$this->join('products p', 'p.prod_id = parcels.parc_product', 'LEFT');
			$this->join('systems s', 's.sy_id = p.prod_system', 'LEFT');
			$this->select('s.sy_title');
			$this->select('p.prod_name');
			$this->select('parcels.parc_id, parcels.parc_title, parcels.parc_arrival_dates, parcels.parc_parent, parcels.parc_station_path');
			$this->where('parcels.parc_next_station IS NULL', NULL);
			$this->where('parcels.parc_sold', 0);
			$this->havingLike('parcels.parc_station_path', $station, 'before');
			$parent = $this->asArray()->findAll();
			$categories = $parent;
			$i=0;
			foreach($categories as $p_cat)
			{
				$categories[$i]['sub'] = $this->subApiCallParcels($p_cat['parc_id'], false);
				$i++;
			}
			
			$parents = [];
			foreach($categories as $key => $cat)
			{
				$date = explode("_", $cat['parc_arrival_dates']);
				$required_date = end($date);
				$final_date = Time::createFromTimestamp($required_date);
				$categories[$key]['parc_arrival_dates'] = $final_date;
				if(!empty($cat['sub']))
				{
					$parents[] = $cat['parc_id'];
				}
				else
				{
					if (in_array($cat['parc_parent'], $parents)) {
						unset($categories[$key]);
					}
				}
				unset($categories[$key]['parc_id']);
				unset($categories[$key]['parc_parent']);
				unset($categories[$key]['parc_station_path']);
			}
			return $categories;
		}

		public function availableForSale($id)
		{
			$is_available = $this->where('parc_id', $id)->first();
			if(!empty($is_available))
			{
				if($is_available['parc_sold'] == 0) return $is_available;
				else return false;
			}
			else
			{
				return false;
			}
		}		
	}
	
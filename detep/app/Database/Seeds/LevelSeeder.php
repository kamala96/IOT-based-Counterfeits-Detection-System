<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LevelSeeder extends Seeder
{
	public function run()
	{
		$data = [
			[
				'lv_title' => 'first',
				'lv_int' => 1,
				'lv_description' => 'First distribution level.'
			],
			[
				'lv_title' => 'second',
				'lv_int' => 2,
				'lv_description' => 'Second distribution level.'
			],
			[
				'lv_title' => 'third-pub-prv',
				'lv_int' => 3,
				'lv_description' => 'Third distribution level.'
			],
			[
				'lv_title' => 'fourth',
				'lv_int' => 4,
				'lv_description' => 'Fourth distribution level.'
			],
			[
				'lv_title' => 'fifth',
				'lv_int' => 5,
				'lv_description' => 'Fifth distribution level.'
			],
			[
				'lv_title' => 'sixth',
				'lv_int' => 6,
				'lv_description' => 'Sixth distribution level.'
			],
			[
				'lv_title' => 'last',
				'lv_int' => 100,
				'lv_description' => 'Final distribution level for all product systems, e.g., health-centres, pharmacies, or any other retail point.'
			],
		];
		$this->db->table('levels')->insertBatch($data);
	}
}

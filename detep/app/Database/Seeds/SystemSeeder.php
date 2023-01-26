<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SystemSeeder extends Seeder
{
	public function run()
	{
		$data = 
		[
			[
				'sy_title' => 'Public Health',
				'sy_slug' => 'pub',
				'sy_description' => 'This is a medical dsitribution system for the public health.',
				'sy_action' => 1,
				'sy_mg' => NULL,
			],
			[
				'sy_title' => 'Private Health',
				'sy_slug' => 'prv',
				'sy_description' => 'This is a medical dsitribution system for the private health.',
				'sy_action' => 1,
				'sy_mg' => NULL,
			],
			[
				'sy_title' => 'Private Sector',
				'sy_slug' => 'prvs',
				'sy_description' => 'This is a dsitribution system for the private sector.',
				'sy_action' => 1,
				'sy_mg' => NULL,
			],
			[
				'sy_title' => 'TMDA',
				'sy_slug' => 'tmda',
				'sy_description' => 'Regulators of all medical systems.',
				'sy_action' => 0,
				'sy_mg' => 'pub-prv',
			],
		];
		$this->db->table('systems')->insertBatch($data);
	}
}

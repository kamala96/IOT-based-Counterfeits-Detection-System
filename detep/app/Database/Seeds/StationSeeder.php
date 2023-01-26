<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StationSeeder extends Seeder
{
	public function run()
	{
		$data = 
		[
			[
				'st_title' => 'Medical Store Department HeadQuarters',
				'st_description' => 'Public health medical distribution headquarters.',
				'st_level' => 1,
				'st_system' =>  1,
				'st_beacon' =>  NULL,
			],
			[
				'st_title' => 'Mzizima Pharmaceuticals',
				'st_description' => 'Manufacturer\'s national subsidiary.',
				'st_level' => 1,
				'st_system' =>  2,
				'st_beacon' =>  NULL,
			],
			[
				'st_title' => 'TMDA HQ',
				'st_description' => 'TMDA top manager.',
				'st_level' => 1,
				'st_system' =>  3,
				'st_beacon' =>  NULL,
			],
			[
				'st_title' => 'Mwanza Zonal Store',
				'st_description' => 'Zonal Store',
				'st_level' => 2,
				'st_system' =>  1,
				'st_beacon' =>  NULL,
			],
			[
				'st_title' => 'Mbeya Zonal Store',
				'st_description' => 'Zonal Store',
				'st_level' => 2,
				'st_system' =>  1,
				'st_beacon' =>  NULL,
			],
			[
				'st_title' => 'Kongwe Distributors',
				'st_description' => 'Distributor',
				'st_level' => 2,
				'st_system' =>  2,
				'st_beacon' =>  'E9:98:49:5A:AC:E6',
			],
			[
				'st_title' => 'Bukoba Medical Store',
				'st_description' => 'District Store',
				'st_level' => 3,
				'st_system' =>  1,
				'st_beacon' =>  NULL,
			],
			[
				'st_title' => 'Kashai Health Centre',
				'st_description' => 'Final consumer point',
				'st_level' => 7,
				'st_system' =>  1,
				'st_beacon' =>  NULL,
			],
			[
				'st_title' => 'Bazir Sub-wholesale',
				'st_description' => 'Sub-wholesale',
				'st_level' => 3,
				'st_system' =>  2,
				'st_beacon' => 'D0:B2:7C:9E:2D:87',
			],
			[
				'st_title' => 'Dhoe Retailer',
				'st_description' => 'Retailer',
				'st_level' => 7,
				'st_system' =>  2,
				'st_beacon' => 'C6:7D:14:24:93:F9',
			],
		];
		$this->db->table('stations')->insertBatch($data);
	}
}

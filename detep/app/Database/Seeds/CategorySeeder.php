<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategorySeeder extends Seeder
{
	public function run()
	{
		$data = [
			[
				'cat_name' => 'Medical or Pharmaceutical Products',
				'cat_slug' => 'medical-products'
			],
			[
				'cat_name' => 'Foood and Nutrition Products',
				'cat_slug' => 'food-and-nutrition-products'
			],
		];
		$this->db->table('categories')->insertBatch($data);
	}
}

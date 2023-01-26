<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class IntermediarySeeder extends Seeder
{
	public function run()
	{
		$data = [
			[
				'int_fname' => 'John',
				'int_lname' => 'Dhoe',
				'int_phone' => '+255765764538',
				'int_mail' =>  'john.dhoe@gmail.com',
				'int_password' => password_hash('johndhoe', PASSWORD_DEFAULT),
				'int_station' =>  2,
				'int_device' => NULL,
			],
			[
				'int_fname' => 'Asha',
				'int_lname' => 'Hussein',
				'int_phone' => '+255769864538',
				'int_mail' =>  'ashahussein@yahoo.com',
				'int_password' => password_hash('ashahussein', PASSWORD_DEFAULT),
				'int_station' =>  6,
				'int_device' => '9fa19b0c-4e2c-4969-aedd-bf52123a9116',
			],
			[
				'int_fname' => 'Mariam',
				'int_lname' => 'Kimario',
				'int_phone' => '+255629864538',
				'int_mail' =>  'mariamkimario@yahoo.com',
				'int_password' => password_hash('mariamkimario', PASSWORD_DEFAULT),
				'int_station' =>  10,
				'int_device' => NULL,
			],
			[
				'int_fname' => 'Reila',
				'int_lname' => 'Ramshid',
				'int_phone' => '+255767364552',
				'int_mail' =>  'reila.ramshid@gmail.com',
				'int_password' => password_hash('reilaramshid', PASSWORD_DEFAULT),
				'int_station' =>  1,
				'int_device' => NULL,
			],
			[
				'int_fname' => 'Halima',
				'int_lname' => 'Fue',
				'int_phone' => '+255784829902 ',
				'int_mail' =>  'halimafue@gmail.com',
				'int_password' => password_hash('halimafue', PASSWORD_DEFAULT),
				'int_station' =>  11,
				'int_device' => NULL,
			],
		];
		$this->db->table('intermediaries')->insertBatch($data);
	}
}

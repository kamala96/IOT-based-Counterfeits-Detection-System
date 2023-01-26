<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Seed extends BaseController
{
	public function index()
	{
		$seeder = \Config\Database::seeder();

		try
		{
			$seeder->call('SystemSeeder');
			$seeder->call('CategorySeeder');
			$seeder->call('LevelSeeder');
			$seeder->call('StationSeeder');
			$seeder->call('IntermediarySeeder');
			echo 1;
		}
		catch (\Throwable $e)
		{
			// Do something with the error here...
			echo "<pre>";
			print_r($e);
			echo "</pre>";
		}
	}
}

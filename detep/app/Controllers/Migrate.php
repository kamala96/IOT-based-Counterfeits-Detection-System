<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Migrate extends BaseController
{
	public function index()
	{
		$migrate = \Config\Services::migrations();

		try
		{
				$migrate->latest();
				echo 1;
		}
		catch (\Throwable $e)
		{
				// Do something with the error here...
				echo 0;
		}
	}
}

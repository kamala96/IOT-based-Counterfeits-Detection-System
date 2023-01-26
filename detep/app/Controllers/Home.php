<?php

namespace App\Controllers;
use App\Models\IntermediaryModel;
use App\Models\DownloadModel;
use CodeIgniter\I18n\Time;

class Home extends BaseController
{
	public function index()
	{
		helper(['form']);
		$model = new DownloadModel();
		$totalDownloads = $model->where('down_id', 1)->first();
		$data = [
			'title' => 'Login',
			'downloads' => $totalDownloads['down_count'],
		];
		return view('pages/login_page', $data);
	}
	
	public function auth()
	{
		$data = [];
		helper(['form']);
		if ($this->request->getMethod() == 'post')
		{
			$rules = 
			[
				'email' => 'required|min_length[6]|max_length[50]|valid_email',
				'password' => 'required|min_length[6]|max_length[20]|validateUser[email,password]',
			];
			$errors =
			[   
				'password' => [
					'min_length' => 'Your password is too short. You want to get hacked?',
                    'validateUser' => 'Email or Password don\'t match',
				]
			];
			if(! $this->validate($rules, $errors))
            {
                $data = [
                    'title' => 'Login',
                    'validation' => $this->validator,
                ];
                return view('pages/login_page', $data);
			}
            else
            {
                $model = new IntermediaryModel();
				// $user = $model->where('email', $this->request->getVar('email'))->first();
				$user = $model->getIntermediary($this->request->getVar('email'));
				$this->setUserSession($user);
				return redirect()->to(base_url('dashboard'));
            }			
		}
		return redirect()->to(base_url('/'));
	}

	private function setUserSession($user)
    {
		// Make sure each entry in the system table has a slug
		$getSlug = $user['sy_slug'];
		$getLeveTitle = $user['lv_title'];
		$isSecondLast = false;
		if (strpos($getLeveTitle, $getSlug) !== false) {
			$isSecondLast = true;
		}

        $data = [
            'id' => $user['int_id'],
            'full_name' => $user['int_fname'].'&nbsp;'.$user['int_lname'],
			'lv_id' => $user['st_level'],
            'level_int' => $user['lv_int'],
            'level_text' => $user['lv_title'],
			'system_id' => $user['sy_id'],
			'system_name' => $user['sy_title'],
			'mg' => $user['sy_mg'] != NULL ? $user['sy_mg'] : false,
			'slug' => $user['sy_slug'] != NULL ? $user['sy_slug'] : false,
			'int_station' => $user['int_station'],
			'station_id' => $user['st_id'],
			'station_name' => $user['st_title'],
            'read_only' => $user['sy_action'] == 1 ? false : true,
			'isTopUser' => $user['lv_int'] == 1 ? true : false,
			'isSecondLast' => $isSecondLast,
            'isLoggedIn' => true,
        ];
        session()->set($data);
        return true;
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('/'));
    }
    

	public function getApp()
	{
	    try
	    {
	        $model = new DownloadModel();
	        $myTime = Time::now();
	        $model->addDownloads($myTime);
		    return $this->response->download('app-debug.apk', null, TRUE);
	    }
	   catch (\Exception $e)
	   {
	       // die($e->getMessage());
		    return $this->response->download('app-debug.apk', null, TRUE);
       }
	}
}
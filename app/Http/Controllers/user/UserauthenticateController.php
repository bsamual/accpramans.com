<?php namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Validator;
use Input;
use DB;
use Response;
use Mail;
use Session;
use PHPMailer;
use Hash;
use Auth;

class UserauthenticateController extends Controller {

	public function __construct()
	{
	    $this->flag = 0;
		$this->middleware('userredirect', ['except' => 'getLogout']);
	}
	public function login()
	{	
		return view('user/login', array('title' => 'Welcome'));
	}

	public function postLogin()
	{
		

		  $validator = Validator::make(Input::all(),
		                            ['userid' => 'required',
									 'password' => 'required']
									);
									
		if($validator->fails())
		{
			
			return redirect('/')->withInput(Input::all())->with('login_error',$validator->errors());
		}
		else
		{
			$username = Input::get('userid');
			$password = Input::get('password');
			if(Auth::attempt(['username' => $username, 'password' => $password]))
			{
				$details = [];
				
				$user = DB::table('user_login')->where('userid', Auth::id())->first();
				if(!empty($user))
				{
					$details = $user;
				}
				if(!empty($details))
				{	
					$sessn=array('userid' => $details->userid);
					Session::put($sessn); 
					return redirect('/user/dashboard');
				}
				else
				{
					return redirect('/')->withInput()->with('message','Invalid Username or Password');
				}
			}
			else{
				return redirect('/')->withInput()->with('message','Invalid Username or Password');
			}
		}
	}
}

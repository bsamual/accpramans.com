<?php namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Validator;
use Input;
use DB;
use Response;
use Mail;
use Session;
use PHPMailer;
use AdminAuth;
use Hash;

class AdminauthenticateController extends Controller {

	public function __construct()
	{
	    $this->flag = 0;
		$this->middleware('adminredirect', ['except' => 'getLogout']);
	}
	public function login()
	{	
		return view('admin/login');
	}

	public function postLogin()
	{
		

		  $validator = Validator::make(Input::all(),
		                            [
		                             'username' => 'required',
									 'password' => 'required']
									);
									
		if($validator->fails())
		{
			
			return redirect('/admin')->withInput(Input::all())->with('login_error',$validator->errors());
		}
		else
		{
			$username = Input::get('username');
			$password = Input::get('password');
			
			$admin_details = DB::table('admin')->first();
			$pin = $admin_details->password;

			if ((Hash::check($password, $pin)) && ($username == $admin_details->username)) {
				$details = [];
				$admin = DB::table('admin')->where('id',$admin_details->id)->first();  
				if(!empty($admin))
				{
					$details = $admin;
				}
				if(!empty($details))
				{	
					$sessn=array('admin_userid' => $details->id);
					Session::put($sessn); 
					return redirect('/admin/manage_task');
				}
				else
				{
					return redirect('/admin')->withInput()->with('message','Invalid Username or Password');
				}
			}
			else{
				return redirect('/admin')->withInput()->with('message','Invalid Username or Password');
			}	
		}
	}
}

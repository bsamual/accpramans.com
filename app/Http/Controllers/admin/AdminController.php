<?php namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\Admin;
use Session;
use Hash;
class AdminController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(Admin $admin)
	{
		$this->middleware('adminauth');
		$this->admin = $admin;
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function manageyear()
	{
		return view('admin/year', array('title' => 'Dashboard'));
	}
	public function profile()
	{
		$admin_details = DB::table('admin')->where('id',1)->first();
		$user_details = DB::table('user_login')->where('userid',1)->first();
		return view('admin/profile', array('admin_details' => $admin_details,'user_details' => $user_details));
	}
	public function vatprofile()
	{
		$admin_details = DB::table('admin')->where('id',1)->first();
		$user_details = DB::table('user_login')->where('userid',1)->first();
		return view('admin/vatprofile', array('admin_details' => $admin_details,'user_details' => $user_details));
	}
	public function email_settings()
	{
		$admin_details = DB::table('admin')->where('id',1)->first();
		$user_details = DB::table('user_login')->where('userid',1)->first();
		return view('admin/emailsettings', array('admin_details' => $admin_details,'user_details' => $user_details));
	}
	public function logout(){
		Session::forget("admin_userid");
		return redirect('/admin');
	}
	public function update_admin_setting()
	{
		$username=Input::get('admin_username');
		$currentpassword=Input::get('admin_password');
		$password=Hash::make(Input::get('newadmin_password'));
		$admin = DB::table('admin')->first();

		if(Hash::check($currentpassword,$admin->password))
		{
			DB::table('admin')->where('id',1)->update(['username'=>$username,'password'=>$password]);
			return Redirect::back()->with('message', 'Settings Updated Successfully');
		}
		else{
			return Redirect::back()->with('message', 'Current Password is wrong. please try again later');
		}
		
	}
	public function update_user_notification()
	{
		$notification=Input::get('user_notification');
		DB::table('admin')->where('id',1)->update(['notify_message'=>$notification]);
		return Redirect::back()->with('message', 'Settings Updated Successfully');
	}
	public function update_user_signature()
	{
		$signature=Input::get('user_signature');
		DB::table('admin')->where('id',1)->update(['signature'=>$signature]);
		return Redirect::back()->with('message', 'Settings Updated Successfully');
	}
	public function update_user_setting()
	{
		$username=Input::get('user_username');
		$currentpassword=Input::get('user_password');
		$password=Hash::make(Input::get('newuser_password'));
		$user = DB::table('user_login')->first();

		if(Hash::check($currentpassword,$user->password))
		{
			DB::table('user_login')->where('userid',1)->update(['username'=>$username,'password'=>$password]);
			return Redirect::back()->with('message', 'Settings Updated Successfully');
		}
		else{
			return Redirect::back()->with('message', 'Current Password is wrong. please try again later');
		}
	}
	public function update_email_setting(){
		$email = Input::get('email');
		$ccemail = Input::get('ccemail');

		$taskccemail = Input::get('taskccemail');
		$p30ccemail = Input::get('p30ccemail');
		$cmccemail = Input::get('cmccemail');
		$vatccemail = Input::get('vatccemail');

		$deleteemail = Input::get('deleteemail');
		
		DB::table('admin')->where('id',1)->update(['email' =>$email, 'cc_email' =>$ccemail,'task_cc_email' =>$taskccemail,'p30_cc_email' =>$p30ccemail,'cm_cc_email' =>$cmccemail,'vat_cc_email' =>$vatccemail,'delete_email' =>$deleteemail]);
		return Redirect::back()->with('emailmessage', 'Email Settings Updated Successfully');
	}
	public function central_locations()
	{
		$central_locations = DB::table('central_locations')->first();
		return view('admin/central_locations', array('selectval' => $central_locations));
	}
	public function update_central_locations()
	{
		$status = Input::get('id');
		DB::table('admin')->where('id',1)->update(["central_locations" => $status]);
	}
	public function update_central_locations_form()
	{
		$data['client_management'] = Input::get('client_management');
		$data['invoice_management'] = Input::get('invoice_management');
		$data['client_statements'] = Input::get('client_statements');
		$data['weekly_monthly'] = Input::get('weekly_monthly');
		$data['p30'] = Input::get('p30');
		$data['vat'] = Input::get('vat');
		$data['rct'] = Input::get('rct');
		$data['year_end'] = Input::get('year_end');
		$data['time_location'] = Input::get('time_location');

		DB::table('central_locations')->where('id',1)->update($data);
		return Redirect::back()->with('message', 'Central Locations Updated Successfully');
	}
}

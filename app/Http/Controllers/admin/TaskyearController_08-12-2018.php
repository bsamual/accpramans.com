<?php namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\Taskyear;
use App\Year;
use App\User;
use Session;
class TaskyearController extends Controller {

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
	public function __construct(Taskyear $taskyear, Year $year, User $user )
	{
		$this->middleware('adminauth');
		$this->taskyear = $taskyear;
		$this->year = $year;
		$this->user = $user;
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function taskyear()
	{
		$taskyear = DB::table('taskyear')->where('delete_status',0)->get();
		$year = DB::table('year')->where('delete_status',0)->get();
		$user = DB::table('user')->get();

		if(count($taskyear)){
			foreach ($taskyear as $singletask) {
				$yearn = $this->year->getdetail($singletask->taskyear);
				if(count($yearn)){
					$singletask->year_name = $yearn->year_name;
					$singletask->end_date = $yearn->end_date;
				}
				else{
					$singletask->year_name = '';
					$singletask->end_date = '';
				}				
				$commonuser = explode(',',$singletask->taskyear_user);
				$uname = '';
				if(count($commonuser)){
					foreach($commonuser as $singlesuer){
						$usertext = $this->user->getdetails($singlesuer);
						if(count($usertext))
						{
							if($uname == '')
							{
								$uname = $usertext->firstname.''.$usertext->lastname;
							}
							else{
								$uname = $uname.','.$usertext->firstname.''.$usertext->lastname;
							}
						}
					}
				}
				$singletask->userdetails = $uname;
			}
		}
		return view('admin/taskyear', array('title' => 'User', 'tasklist' => $taskyear, 'yearlist' => $year, 'userlist' => $user));
	}
	public function addtaskyear(){
		$year = Input::get('year');
		$end_date = Input::get('end_date');

		$exp = explode('-',$end_date);
		$date = $exp[2].'-'.$exp[0].'-'.$exp[1];
		$check_year = DB::table('year')->where('year_name',$year)->first();

		if(count($check_year))
		{
			DB::table('year')->where('year_id',$check_year->year_id)->update(['delete_status'=>0]);
			DB::table('taskyear')->where('taskyear',$check_year->year_id)->update(['delete_status'=>0]);
			return redirect('admin/manage_task')->with('message','Year Recovered Successfully');
		}
		else{
			$check_prev = DB::table('year')->orderBy('year_id','desc')->first();
			$count_month = DB::table('month')->where('year',$check_prev->year_id)->count();
			$count_week = DB::table('week')->where('year',$check_prev->year_id)->count();

			if($count_week < 52 && $count_month < 12)
			{
				return redirect('admin/manage_task')->with('message','Year '.$year.' cannot be created unless all the 12 Months and 52 weeks of the previous year '.$check_prev->year_name.' have been created and closed.');
			}
			else{
				$now = date('Y-m-d H:i:s');
				DB::table('week')->where('week_closed','=','0000-00-00 00:00:00')->update(['week_closed' => $now]);
				DB::table('month')->where('month_closed','=','0000-00-00 00:00:00')->update(['month_closed' => $now]);
				DB::table('p30_month')->where('month_closed','=','0000-00-00 00:00:00')->update(['month_closed' => $now]);
				DB::table('gbs_p30_month')->where('month_closed','=','0000-00-00 00:00:00')->update(['month_closed' => $now]);

				$byear = $year - 1;
				$beforeyear = DB::table('year')->where('year_name',$byear)->first();
				$getlastweek = DB::table('week')->where('year',$beforeyear->year_id)->orderBy('week_id','desc')->first();
				
				$getlastmonth = DB::table('month')->where('year',$beforeyear->year_id)->orderBy('month_id','desc')->first();
				$getlastp30month = DB::table('p30_month')->where('year',$beforeyear->year_id)->orderBy('month_id','desc')->first();
				$getlastgbs_p30month = DB::table('gbs_p30_month')->where('year',$beforeyear->year_id)->orderBy('month_id','desc')->first();

				$yid = DB::table('year')->insertGetId(['year_name' => $year,'end_date' => $date]);
				$weekid = DB::table('week')->insertGetId(['year' => $yid,'week' => 1]);

				$gettasks = DB::table('task')->where('task_year',$getlastweek->year)->where('task_week',$getlastweek->week_id)->get();

				if(count($gettasks))
				{
					foreach($gettasks as $tasks)
					{
						$data['task_year'] = $yid;
						$data['task_week'] = $weekid;
						$data['task_name'] = $tasks->task_name;
						$data['task_classified'] = $tasks->task_classified;
						$data['enterhours'] = ($tasks->enterhours == 2)?2:0;
						$data['holiday'] = ($tasks->holiday == 2)?2:0;
						$data['process'] = ($tasks->process == 2)?2:0;
						$data['payslips'] = ($tasks->payslips == 2)?2:0;
						$data['email'] = ($tasks->email == 2)?2:0;
						$data['upload'] = ($tasks->upload == 2)?2:0;
						$data['date'] = $tasks->date;
						$data['task_email'] = $tasks->task_email;
						$data['comments'] = $tasks->comments;
						$data['attached'] = $tasks->attached;
						$data['network'] = $tasks->network;

						$data['secondary_email'] = $tasks->secondary_email;
						$data['salutation'] = $tasks->salutation;
						$data['task_status'] = 0;

						$data['client_id'] = $tasks->client_id;
						$data['tasklevel'] = $tasks->tasklevel;
						$data['p30_pay'] = $tasks->p30_pay;
						$data['p30_email'] = $tasks->p30_email;

						DB::table('task')->insert($data);
					}
				}
				$monthid = DB::table('month')->insertGetId(['year' => $yid,'month' => 1]);
				$getmonthtasks = DB::table('task')->where('task_year',$getlastmonth->year)->where('task_month',$getlastmonth->month_id)->get();
				if(count($getmonthtasks))
				{
					foreach($getmonthtasks as $monthtasks)
					{
						$datamonth['task_year'] = $yid;
						$datamonth['task_month'] = $monthid;
						$datamonth['task_name'] = $monthtasks->task_name;
						$datamonth['task_classified'] = $monthtasks->task_classified;
						$datamonth['enterhours'] = ($monthtasks->enterhours == 2)?2:0;
						$datamonth['holiday'] = ($monthtasks->holiday == 2)?2:0;
						$datamonth['process'] = ($monthtasks->process == 2)?2:0;
						$datamonth['payslips'] = ($monthtasks->payslips == 2)?2:0;
						$datamonth['email'] = ($monthtasks->email == 2)?2:0;
						$datamonth['upload'] = ($monthtasks->upload == 2)?2:0;
						$datamonth['date'] = $monthtasks->date;
						$datamonth['task_email'] = $monthtasks->task_email;
						$datamonth['comments'] = $monthtasks->comments;
						$datamonth['attached'] = $monthtasks->attached;
						$datamonth['network'] = $monthtasks->network;

						$datamonth['secondary_email'] = $monthtasks->secondary_email;
						$datamonth['salutation'] = $monthtasks->salutation;
						$datamonth['task_status'] = 0;

						$datamonth['client_id'] = $monthtasks->client_id;
						$datamonth['tasklevel'] = $monthtasks->tasklevel;
						$datamonth['p30_pay'] = $monthtasks->p30_pay;
						$datamonth['p30_email'] = $monthtasks->p30_email;

						DB::table('task')->insert($datamonth);
					}
				}	

				$p30monthid = DB::table('p30_month')->insertGetId(['year' => $yid,'month' => 1]);
				$gbs_p30monthid = DB::table('gbs_p30_month')->insertGetId(['year' => $yid,'month' => 1]);

				$userlist = DB::table('user')->get();
				$commouser = '';
				if(count($userlist)) {
					foreach($userlist as $singleuser){
						if($commouser == ''){
							$commouser = $singleuser->user_id;
						}
						else{
							$commouser = $commouser.','. $singleuser->user_id;
						}
					}
				}
				DB::table('taskyear')->insert(['taskyear' => $yid, 'taskyear_user' => $commouser]);
				$yearuseupdate = 1;
				DB::table('year')->where('year_id', $yid)->update(['year_used' => $yearuseupdate]);
				return redirect('admin/manage_task')->with('message','Year Added Successfully');
			}
		}
	}
	public function updatetaskyear(){
		$id = Input::get('taskyear_id');
		return redirect('admin/manage_task')->with('message','Update Success');
	}
	public function deactivetaskyear($id=""){
		$id = base64_decode($id);
		$deactive = 1;
		$details = DB::table('taskyear')->where('taskyear_id', $id)->first();
		DB::table('taskyear')->where('taskyear_id', $id)->update(['taskyear_status' => $deactive]);
		DB::table('year')->where('year_id', $details->taskyear)->update(['year_status' => $deactive]);
		return redirect('admin/manage_task')->with('message','Deactive Success');
	}
	public function activetaskyear($id=""){
		$id = base64_decode($id);
		$active = 0;
		$details = DB::table('taskyear')->where('taskyear_id', $id)->first();
		DB::table('taskyear')->where('taskyear_id', $id)->update(['taskyear_status' => $active]);
		DB::table('year')->where('year_id', $details->taskyear)->update(['year_status' => $active]);
		return redirect('admin/manage_task')->with('message','Active Success');
	}
	public function deletetaskyear($id=''){
		$id = base64_decode($id);
		$year_id = DB::table('taskyear')->where('taskyear_id', $id)->first();
		$year_update_use = $year_id->taskyear;
		$yearuseupdate = 0;
		DB::table('year')->where('year_id', $year_update_use)->update(['year_used' => $yearuseupdate,'delete_status' => 1]);
		DB::table('taskyear')->where('taskyear_id', $id)->update(['delete_status' => 1]);
		return redirect('admin/manage_task')->with('message','Delete Success');
	}
	public function edittaskyear($id=""){
		$id = base64_decode($id);
		$result = DB::table('taskyear')->where('taskyear_id', $id)->first();		
		$year = DB::table('year')->where('year_id', $result->taskyear)->first();
		echo json_encode(array('year' => $year->year_name,'end_date'=> $year->end_date,'user' => $result->taskyear_user,'id' => $result->taskyear_id));
	}


}

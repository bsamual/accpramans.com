<?php namespace App\Http\Controllers\user;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\Year;
use App\Week;
use App\Task;
use App\Classified;
use App\User;
use App\Vatclients;
use Session;
use URL;
use PDF;
use Response;
use PHPExcel; 
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class Gbspayep30Controller extends Controller {

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
	public function __construct(year $year, week $week, task $task, classified $classified, user $user, vatclients $vatclients)
	{
		$this->middleware('userauth');
		$this->year = $year;
		$this->week = $week;
		$this->task = $task;
		$this->classified = $classified;
		$this->user = $user;
		$this->vatclients = $vatclients;
		date_default_timezone_set("Europe/Dublin");
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */	
	public function gbs_update_paye_p30_first_year()
	{
		$year = Input::get('year');
		$data['year_name'] = $year;
		$yearid = DB::table('paye_p30_year')->insertGetid($data);
		$datamonth['year'] = $yearid;
		$datamonth['month'] = "1";
		DB::table('paye_p30_month')->insert($datamonth);
	}
	public function gbs_paye_p30month_manage($id)
	{
		$id = base64_decode($id);
		$year = DB::table('paye_p30_year')->where('year_id',$id)->first();
		$month = DB::table('paye_p30_month')->where('year', $id)->orderBy('month_id','dec')->get();
		return view('user/gbs_paye_p30/paye_p30_month_manage', array('monthlist' => $month,'year' => $year));
	}
	public function gbs_paye_p30_select_month($id="")
	{
		$id =base64_decode($id);
		$month_id = DB::table('paye_p30_month')->where('month_id', $id)->first();
		$year_id = DB::table('paye_p30_year')->where('year_id', $month_id->year)->first();
		$user_year = $year_id->year_id;
		
		$month2 = $month_id->month_id;
		$year2 = $month_id->year;
		$result_task = DB::table('paye_p30_task')->where('task_month', $month2)->get();

		
		return view('user/gbs_paye_p30/paye_p30_select_month', array('title' => 'Paye M.R.S Month Task', 'yearname' => $year_id, 'monthid' => $month_id, 'resultlist' => $result_task));
	}
	public function gbs_paye_p30_review_month($id="")
	{
		$current_week = DB::table('week')->orderBy('week_id','desc')->first();
		$current_month = DB::table('month')->orderBy('month_id','desc')->first();
		$tasks = DB::table('task')->where('task_week',$current_week->week_id)->orWhere('task_month',$current_month->month_id)->groupBy('task_enumber')->get();

		if(count($tasks))
		{
			foreach($tasks as $task)
			{
				if($task->task_enumber != "")
				{
					$check_task = DB::table('paye_p30_task')->where('task_id',$task->task_id)->where('task_month',$id)->count();
					$task_eno = DB::table('paye_p30_task')->where('task_enumber',$task->task_enumber)->where('task_month',$id)->count();
					if($check_task == 0 && $task_eno == 0)
					{
						$data['task_id'] = $task->task_id;
						$data['task_year'] = $task->task_year;
						$data['task_month'] = $id;
						$data['task_name'] = $task->task_name;
						$data['task_classified'] = $task->task_classified;
						$data['date'] = $task->date;
						$data['task_enumber'] = $task->task_enumber;
						$data['task_email'] = $task->task_email;
						$data['secondary_email'] = $task->secondary_email;
						$data['salutation'] = $task->salutation;
						$data['network'] = $task->network;
						$data['users'] = $task->users;

						$data['task_level'] = $task->tasklevel;
						$data['pay'] = $task->p30_pay;
						$data['email'] = $task->p30_email;

						for($i=1; $i<=53; $i++) { $data['week'.$i] = '0'; }
						for($i=1; $i<=12; $i++) { $data['month'.$i] = '0'; }
						
						$tasks_all_per_year = DB::table('task')
											->join('week', 'task.task_week', '=', 'week.week_id')
											->where('task_year',$current_month->year)
											->where('task_enumber',$task->task_enumber)
											->where('task_week','!=',0)
											->get();

						$week_n_array = array();
						$week_value = '';
						if(count($tasks_all_per_year))
						{
							foreach($tasks_all_per_year as $task_year)
							{
								if (in_array($task_year->task_week, $week_n_array))
								{
									$ww = ($task_year->liability == "")?0:$task_year->liability;
									$ww = str_replace(",", "", $ww);
									$ww = str_replace(",", "", $ww);
									$ww = str_replace(",", "", $ww);

									$week_value = $week_value + $ww;

								}
								else{
									$ww = ($task_year->liability == "")?0:$task_year->liability;
									$ww = str_replace(",", "", $ww);
									$ww = str_replace(",", "", $ww);
									$ww = str_replace(",", "", $ww);

									$week_value = $ww;
									array_push($week_n_array,$task_year->task_week);
								}
								$week_value = str_replace(",", "", $week_value);
								$week_value = str_replace(",", "", $week_value);
								$week_value = str_replace(",", "", $week_value);
								$data['week'.$task_year->week] = $week_value;
							}
						}

						$tasks_all_per_year_month = DB::table('task')
											->join('month', 'task.task_month', '=', 'month.month_id')
											->where('task_year',$current_month->year)
											->where('task_enumber',$task->task_enumber)
											->where('task_month','!=',0)
											->get();
						$month_n_array = array();
						$month_value = '';
						if(count($tasks_all_per_year_month))
						{
							foreach($tasks_all_per_year_month as $task_year_month)
							{
								if (in_array($task_year_month->task_month, $month_n_array))
								{
									$mm = ($task_year_month->liability == "")?0:$task_year_month->liability;
									$mm = str_replace(",", "", $mm);
									$mm = str_replace(",", "", $mm);
									$mm = str_replace(",", "", $mm);
									$month_value = $month_value + $mm;
								}
								else{
									$mm = ($task_year_month->liability == "")?0:$task_year_month->liability;
									$mm = str_replace(",", "", $mm);
									$mm = str_replace(",", "", $mm);
									$mm = str_replace(",", "", $mm);

									$month_value = $mm;
									array_push($month_n_array,$task_year_month->task_month);
								}
								$month_value = str_replace(",", "", $month_value);
								$month_value = str_replace(",", "", $month_value);
								$month_value = str_replace(",", "", $month_value);
								$data['month'.$task_year_month->month] = $month_value;
							}
						}

						$data['task_liability'] = $data['week1']+$data['week2']+$data['week3']+$data['week4']+$data['week5']+$data['week6']+$data['week7']+$data['week8']+$data['week9']+$data['week10']+$data['week11']+$data['week12']+$data['week13']+$data['week14']+$data['week15']+$data['week16']+$data['week17']+$data['week18']+$data['week19']+$data['week20']+$data['week21']+$data['week22']+$data['week23']+$data['week24']+$data['week25']+$data['week26']+$data['week27']+$data['week28']+$data['week29']+$data['week30']+$data['week31']+$data['week32']+$data['week33']+$data['week34']+$data['week35']+$data['week36']+$data['week37']+$data['week38']+$data['week39']+$data['week40']+$data['week41']+$data['week42']+$data['week43']+$data['week44']+$data['week45']+$data['week46']+$data['week47']+$data['week48']+$data['week49']+$data['week50']+$data['week51']+$data['week52']+$data['week53']+$data['month1']+$data['month2']+$data['month3']+$data['month4']+$data['month5']+$data['month6']+$data['month7']+$data['month8']+$data['month9']+$data['month10']+$data['month11']+$data['month12'];


						DB::table('paye_p30_task')->insert($data); 
					}
				}
			}
		}
		return redirect('user/gbs_paye_p30_select_month/'.base64_encode($id))->with('message', 'Reviewed Successfully.');
	}
	public function gbs_update_paye_p30_task_status()
	{
		$task_id = explode(",",Input::get('task_id'));
		$data['task_status'] = Input::get('status');
		DB::table('paye_p30_task')->whereIn('id', $task_id)->update($data);
	}
	public function gbs_update_paye_p30_hide_task_status()
	{
		$data['paye_hide_task'] = Input::get('status');
		DB::table('user_login')->where('id', 1)->update($data);
	}

	public function gbs_update_paye_p30_hide_columns_status()
	{
		$status = Input::get('status');
		$data['paye_hide_columns'] = $status;
		DB::table('user_login')->where('id', 1)->update($data);
	}
	public function gbs_update_paye_p30_columns_status()
	{
		$col_id = Input::get('col_id');
		$status = Input::get('status');

		$data[$col_id.'_hide'] = $status;
		DB::table('user_login')->where('id', 1)->update($data);
	}

	public function gbs_update_paye_p30_columns_status_selectall()
	{
		$col_id = explode(",",Input::get('col_id'));
		$status = Input::get('status');

		if(count($col_id))
		{
			foreach($col_id as $col)
			{
				$data[$col.'_hide'] = $status;
				DB::table('user_login')->where('id', 1)->update($data);
			}
		}
	}
	public function gbs_paye_p30_ros_liability_update()
	{
		$ros_liability = Input::get('liability');
		$task_id = Input::get('task_id');
		$data['ros_liability'] = $ros_liability;
		DB::table('paye_p30_task')->where('id',$task_id)->update($data);

		$calc_diff = DB::table('paye_p30_task')->where('id',$task_id)->first();
		$diff = $calc_diff->ros_liability - $calc_diff->task_liability;
		echo json_encode(array("ros_liability" => (number_format_invoice($ros_liability) == 0.00)?'':number_format_invoice($ros_liability), "diff" => (number_format_invoice($diff) == 0.00)?'':number_format_invoice($diff)));
	}
	public function gbs_refresh_paye_p30_liability()
	{
		$task_id = Input::get('task_id');

		$current_week = DB::table('week')->orderBy('week_id','desc')->first();
		$current_month = DB::table('month')->orderBy('month_id','desc')->first();
		
		$task = DB::table('paye_p30_task')->where('id',$task_id)->first();

		for($i=1; $i<=53; $i++) { $data['week'.$i] = '0'; }
		for($i=1; $i<=12; $i++) { $data['month'.$i] = '0'; }
		
		$tasks_all_per_year = DB::table('task')
							->join('week', 'task.task_week', '=', 'week.week_id')
							->where('task_year',$current_month->year)
							->where('task_enumber',$task->task_enumber)
							->where('task_week','!=',0)
							->get();

		$week_n_array = array();
		$week_value = '';
		if(count($tasks_all_per_year))
		{
			foreach($tasks_all_per_year as $task_year)
			{
				if (in_array($task_year->task_week, $week_n_array))
				{
					$ww = ($task_year->liability == "")?0:$task_year->liability;
					$ww = str_replace(",", "", $ww);
					$ww = str_replace(",", "", $ww);
					$ww = str_replace(",", "", $ww);

					$week_value = $week_value + $ww;

				}
				else{
					$ww = ($task_year->liability == "")?0:$task_year->liability;
					$ww = str_replace(",", "", $ww);
					$ww = str_replace(",", "", $ww);
					$ww = str_replace(",", "", $ww);

					$week_value = $ww;
					array_push($week_n_array,$task_year->task_week);
				}
				$week_value = str_replace(",", "", $week_value);
				$week_value = str_replace(",", "", $week_value);
				$week_value = str_replace(",", "", $week_value);
				$data['week'.$task_year->week] = $week_value;
			}
		}

		$tasks_all_per_year_month = DB::table('task')
							->join('month', 'task.task_month', '=', 'month.month_id')
							->where('task_year',$current_month->year)
							->where('task_enumber',$task->task_enumber)
							->where('task_month','!=',0)
							->get();
		$month_n_array = array();
		$month_value = '';
		if(count($tasks_all_per_year_month))
		{
			foreach($tasks_all_per_year_month as $task_year_month)
			{
				if (in_array($task_year_month->task_month, $month_n_array))
				{
					$mm = ($task_year_month->liability == "")?0:$task_year_month->liability;
					$mm = str_replace(",", "", $mm);
					$mm = str_replace(",", "", $mm);
					$mm = str_replace(",", "", $mm);
					$month_value = $month_value + $mm;
				}
				else{
					$mm = ($task_year_month->liability == "")?0:$task_year_month->liability;
					$mm = str_replace(",", "", $mm);
					$mm = str_replace(",", "", $mm);
					$mm = str_replace(",", "", $mm);

					$month_value = $mm;
					array_push($month_n_array,$task_year_month->task_month);
				}
				$month_value = str_replace(",", "", $month_value);
				$month_value = str_replace(",", "", $month_value);
				$month_value = str_replace(",", "", $month_value);
				$data['month'.$task_year_month->month] = $month_value;
			}
		}

		$data['task_liability'] = $data['week1']+$data['week2']+$data['week3']+$data['week4']+$data['week5']+$data['week6']+$data['week7']+$data['week8']+$data['week9']+$data['week10']+$data['week11']+$data['week12']+$data['week13']+$data['week14']+$data['week15']+$data['week16']+$data['week17']+$data['week18']+$data['week19']+$data['week20']+$data['week21']+$data['week22']+$data['week23']+$data['week24']+$data['week25']+$data['week26']+$data['week27']+$data['week28']+$data['week29']+$data['week30']+$data['week31']+$data['week32']+$data['week33']+$data['week34']+$data['week35']+$data['week36']+$data['week37']+$data['week38']+$data['week39']+$data['week40']+$data['week41']+$data['week42']+$data['week43']+$data['week44']+$data['week45']+$data['week46']+$data['week47']+$data['week48']+$data['week49']+$data['week50']+$data['week51']+$data['week52']+$data['week53']+$data['month1']+$data['month2']+$data['month3']+$data['month4']+$data['month5']+$data['month6']+$data['month7']+$data['month8']+$data['month9']+$data['month10']+$data['month11']+$data['month12'];

			DB::table('paye_p30_task')->where('id',$task_id)->update($data); 
			echo json_encode($data);		
	}
	public function gbs_paye_p30_edit_email_unsent_files()
	{
		$task_id = Input::get('task_id');
		$result = DB::table('paye_p30_task')->where('id',$task_id)->first();
		if($result->users != 0)
		{
			$user_details = DB::table('user')->where('user_id',$result->users)->first();
			$from = $user_details->email;
		}
		else{
			$from = '';
		}

		if($result->secondary_email != '')
	    {
	      	$to_email = $result->task_email.', '.$result->secondary_email;
	    }
	    else{
	      	$to_email = $result->task_email;
        }

		$date = date('d F Y', strtotime($result->last_email_sent));
		$time = date('H:i', strtotime($result->last_email_sent));
		$last_date = $date.' @ '.$time;
		
		$admin_details = Db::table('admin')->first();
		$admin_cc = $admin_details->p30_cc_email;
		
		$data['sentmails'] = $to_email.', '.$admin_cc;
		$data['logo'] = URL::to('assets/images/easy_payroll_logo.png');
		$task_details = DB::table('paye_p30_task')->where('id',$task_id)->first();
		
		$data['salutation'] = $task_details->salutation;
		if($task_details->task_liability == "")
		{
			$task_liability_val = '0.00';
		}
		else{
			$task_liability_val = $task_details->task_liability;
		}

		if($task_details->ros_liability == "")
		{
			$ros_liability_val = '0.00';
		}
		else{
			$ros_liability_val = $task_details->ros_liability;
		}

		$ros_liability_val = str_replace(",", "", $ros_liability_val);
		$ros_liability_val = str_replace(",", "", $ros_liability_val);
		$ros_liability_val = str_replace(",", "", $ros_liability_val);

		$task_liability_val = str_replace(",", "", $task_liability_val);
		$task_liability_val = str_replace(",", "", $task_liability_val);
		$task_liability_val = str_replace(",", "", $task_liability_val);

		$data['task_liability'] = $task_liability_val;
		$data['ros_liability'] = $ros_liability_val;

		$data['pay'] = ($task_details->pay == 1)?'Yes':'No';
		$data['email'] = ($task_details->email == 1)?'Yes':'No';

		$data['task_name'] = $task_details->task_name;
		$data['task_enumber'] = $task_details->task_enumber;

		if($task_details->task_level == 0)
		{
			$data['task_level'] = 'Nil';
		}
		else{
			$task_level = DB::table('p30_tasklevel')->where('id',$task_details->task_level)->first();
			$data['task_level'] = $task_level->name;
		}

	      $month_details = Db::table('paye_p30_month')->where('month_id',$task_details->task_month)->first();
	      $year_details = DB::table('year')->where('year_id',$month_details->year)->first();
	      
	      if($month_details->month == 1) { $month_name = "February"; }
          if($month_details->month == 2) { $month_name = "March"; }
          if($month_details->month == 3) { $month_name = "April"; }
          if($month_details->month == 4) { $month_name = "May"; }
          if($month_details->month == 5) { $month_name = "June"; }
          if($month_details->month == 6) { $month_name = "July"; }
          if($month_details->month == 7) { $month_name = "August"; }
          if($month_details->month == 8) { $month_name = "September"; }
          if($month_details->month == 9) { $month_name = "October"; }
          if($month_details->month == 10) { $month_name = "November"; }
          if($month_details->month == 11) { $month_name = "December"; }
          if($month_details->month == 12) { $month_name = "January"; }

            

		$data['period'] = $month_name;
		$contentmessage = view('user/paye_p30_email_content', $data)->render();
	      
      	$month_details = DB::table('p30_month')->where('month_id',$result->task_month)->first();
      	$subject = 'Easypayroll.ie : '.$result->task_name.' Paye MRS Submission';

	     echo json_encode(["html" => $contentmessage,"from" => $from, "to" => $to_email,'subject' => $subject,'last_email_sent' => $last_date]);
	}
	public function gbs_paye_p30_email_unsent_files()
	{
		$task_id = Input::get('hidden_email_task_id');
		$det_task = DB::table('paye_p30_task')->where('id',$task_id)->first();
		$encoded_month_id = base64_encode($det_task->task_month);

		$from = Input::get('from_user');
		$toemails = Input::get('to_user').','.Input::get('cc_unsent');
		$sentmails = Input::get('to_user').', '.Input::get('cc_unsent');
		$subject = Input::get('subject_unsent'); 
		$message = Input::get('message_editor');
		
		$explode = explode(',',$toemails);
		$data['sentmails'] = $sentmails;

		
		if(count($explode))
		{
			foreach($explode as $exp)
			{
				$to = trim($exp);
				$data['logo'] = URL::to('assets/images/easy_payroll_logo.png');
				$data['message'] = $message;

				$contentmessage = view('user/p30_email_share_paper', $data);

				echo $contentmessage;
				exit;
				$email = new PHPMailer();
				$email->SetFrom($from); //Name is optional
				$email->Subject   = $subject;
				$email->Body      = $contentmessage;
				$email->IsHTML(true);
				$email->AddAddress( $to );
				$email->Send();			
			}
			$date = date('Y-m-d H:i:s');
			DB::table('paye_p30_task')->where('id',$task_id)->update(['last_email_sent' => $date]);
			return redirect('user/gbs_paye_p30_select_month/'.$encoded_month_id.'?divid=taskidtr_'.$task_id)->with('message', 'Email Sent Successfully');
		}
		else{
			return redirect('user/gbs_paye_p30_select_month/'.$encoded_month_id.'?divid=taskidtr_'.$task_id)->with('error', 'Email Field is empty so email is not sent');
		}
	}
}

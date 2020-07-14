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

class UserController extends Controller {

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
	public function unavailable()
	{
		return view('user/unavailable');
	}
	public function manageweek()
	{
		$year = DB::table('year')->where('delete_status',0)->where('year_status', 0)->orderBy('year_name','dec')->get();
		return view('user/week', array('title' => 'Select Year', 'yearlist' => $year));
	}
	public function managemonth()
	{
		$year = DB::table('year')->where('delete_status',0)->where('year_status', 0)->orderBy('year_name','dec')->get();
		return view('user/month', array('title' => 'Select Year', 'yearlist' => $year));
	}
	public function weekmanage($id=""){
		$id = base64_decode($id);
		$year = DB::table('year')->where('year_id',$id)->first();
		$week = DB::table('week')->where('year', $id)->orderBy('week_id','dec')->get();
		return view('user/week_manage', array('title' => 'Select Week', 'weeklist' => $week,'year' => $year));
	}
	public function monthmanage($id=""){
		$id = base64_decode($id);
		$year = DB::table('year')->where('year_id',$id)->first();
		$month = DB::table('month')->where('year', $id)->orderBy('month_id','dec')->get();
		return view('user/month_manage', array('title' => 'Select Week', 'monthlist' => $month,'year' => $year));
	}
	public function logout(){
		Session::forget("userid");
		return redirect('/');
	}
	public function selectweek($id=""){
		$id =base64_decode($id);

		$year = DB::table('week')->where('week_id', $id)->first();
		$year_id = $this->year->getdetail($year->year);
		$user_year = $year_id->year_id;
		$year_user = DB::table('taskyear')->where('taskyear', $user_year)->first();

		$week_id = DB::table('week')->where('week_id', $id)->first();
		$week2 = $week_id->week_id;
		$year2 = $week_id->year;	

		$result_task_standard = DB::table('task')->where('task_year', $year2)->where('task_week', $week2)->where('task_classified',1)->get();
		$result_task_enhanced = DB::table('task')->where('task_year', $year2)->where('task_week', $week2)->where('task_classified',2)->get();
		$result_task_complex = DB::table('task')->where('task_year', $year2)->where('task_week', $week2)->where('task_classified',3)->get();
		if($year_user->taskyear_user != '')
		{
			$commonuser = explode(',',$year_user->taskyear_user);
			$uname = '<option value="">Select Username</option>';
			$email = '<option value="">Select Email</option>';
			$initial = '<option value="">Select Initial</option>';
			if(count($commonuser)){
				foreach ($commonuser as $singleuser) {
					$usertext = $this->user->getdetails($singleuser);
					if(count($usertext))
					{
						if($uname == '')
						{
							$uname = '<option value="'.$usertext->user_id.'">'.$usertext->firstname.' '.$usertext->lastname.'</option>';
							$email = '<option value="'.$usertext->user_id.'">'.$usertext->email.'</option>';
							$initial = '<option value="'.$usertext->user_id.'">'.$usertext->initial.'</option>';
						}
						else{
							$uname = $uname.'<option value="'.$usertext->user_id.'">'.$usertext->firstname.' '.$usertext->lastname.'</option>';
							$email = $email.'<option value="'.$usertext->user_id.'">'.$usertext->email.'</option>';
							$initial = $initial.'<option value="'.$usertext->user_id.'">'.$usertext->initial.'</option>';
						}
					}
				}
			}
		}
		else{
			$uname = '<option value="">Select Username</option>';
			$email = '<option value="">Select Email</option>';
			$initial = '<option value="">Select Initial</option>';
		}
		
		$classified = DB::table('classified')->get();
		return view('user/select_week', array('title' => 'Week Task', 'yearname' => $year_id, 'weekid' => $year, 'classifiedlist' => $classified, 'unamelist' => $uname,'emaillist' => $email,'initiallist' => $initial,'resultlist_standard' => $result_task_standard,'resultlist_enhanced' => $result_task_enhanced,'resultlist_complex' => $result_task_complex,'year_user' => $year_user));
	}
	public function selectmonth($id=""){
		$id =base64_decode($id);

		$year = DB::table('month')->where('month_id', $id)->first();
		$year_id = $this->year->getdetail($year->year);
		$user_year = $year_id->year_id;
		$year_user = DB::table('taskyear')->where('taskyear', $user_year)->first();

		$month_id = DB::table('month')->where('month_id', $id)->first();
		$month2 = $month_id->month_id;
		$year2 = $month_id->year;	

		$result_task_standard = DB::table('task')->where('task_year', $year2)->where('task_month', $month2)->where('task_classified',1)->get();
		$result_task_enhanced = DB::table('task')->where('task_year', $year2)->where('task_month', $month2)->where('task_classified',2)->get();
		$result_task_complex = DB::table('task')->where('task_year', $year2)->where('task_month', $month2)->where('task_classified',3)->get();

		if($year_user->taskyear_user != '')
		{
			$commonuser = explode(',',$year_user->taskyear_user);
			$uname = '<option value="">Select Username</option>';
			$email = '<option value="">Select Email</option>';
			$initial = '<option value="">Select Initial</option>';
			if(count($commonuser)){
				foreach ($commonuser as $singleuser) {
					$usertext = $this->user->getdetails($singleuser);
					if(count($usertext))
					{
						if($uname == '')
						{
							$uname = '<option value="'.$usertext->user_id.'">'.$usertext->firstname.' '.$usertext->lastname.'</option>';
							$email = '<option value="'.$usertext->user_id.'">'.$usertext->email.'</option>';
							$initial = '<option value="'.$usertext->user_id.'">'.$usertext->initial.'</option>';
						}
						else{
							$uname = $uname.'<option value="'.$usertext->user_id.'">'.$usertext->firstname.' '.$usertext->lastname.'</option>';
							$email = $email.'<option value="'.$usertext->user_id.'">'.$usertext->email.'</option>';
							$initial = $initial.'<option value="'.$usertext->user_id.'">'.$usertext->initial.'</option>';
						}
					}
				}
			}
		}
		else{
			$uname = '<option value="">Select Username</option>';
			$email = '<option value="">Select Email</option>';
			$initial = '<option value="">Select Initial</option>';
		}

		$classified = DB::table('classified')->get();
		return view('user/select_month', array('title' => 'Month Task', 'yearname' => $year_id, 'monthid' => $year, 'classifiedlist' => $classified, 'unamelist' => $uname, 'emaillist' => $email,'initiallist' => $initial,'resultlist_standard' => $result_task_standard,'resultlist_enhanced' => $result_task_enhanced,'resultlist_complex' => $result_task_complex,'year_user' => $year_user));
	}
	public function addnewtask(){
		$year = Input::get('year');
		$week = Input::get('weekid');
		$weekid = Input::get('weekid');
		$tastname =  Input::get('tastname');
		$classified =  Input::get('classified');
		$enterhours =  Input::get('enterhours');
		$holiday =  Input::get('holiday');
		$process =  Input::get('process');
		$payslips =  Input::get('payslips');
		$email =  Input::get('email');
		$secondary_email =  Input::get('secondary_email');
		$salutation =  Input::get('salutation');
		$upload =  Input::get('uploadd');
		$task_email =  Input::get('task_email');
		
		$location =  Input::get('location');	

		$task_enumber =  trim(Input::get('task_enumber'));

		$tasklevel =  trim(Input::get('tasklevel'));
		$p30_pay =  trim(Input::get('pay_p30'));
		$p30_email =  trim(Input::get('email_p30'));

		
		DB::table('task')->insert(['task_year' => $year, 'task_week' => $week, 'task_name' => $tastname, 'task_classified' => $classified, 'enterhours' => $enterhours, 'holiday' => $holiday, 'process' => $process, 'payslips' => $payslips, 'email' => $email, 'secondary_email' => $secondary_email, 'salutation' => $salutation, 'upload' => $upload, 'task_email' => $task_email,'network' => $location, 'task_enumber' => $task_enumber, 'tasklevel' => $tasklevel, 'p30_pay' => $p30_pay, 'p30_email' => $p30_email]);
		return redirect('user/select_week/'.base64_encode($weekid))->with('message', 'Task Created Success');
	}
	public function addnewtask_month(){
		$year = Input::get('year');
		$month = Input::get('monthid');
		$monthid = Input::get('monthid');
		$tastname =  Input::get('tastname');
		$classified =  Input::get('classified');
		$enterhours =  Input::get('enterhours');
		$holiday =  Input::get('holiday');
		$process =  Input::get('process');
		$payslips =  Input::get('payslips');
		$email =  Input::get('email');
		$secondary_email =  Input::get('secondary_email');
		$salutation =  Input::get('salutation');
		$upload =  Input::get('uploadd');
		$task_email =  Input::get('task_email');
		
		
		$location =  Input::get('location');

		$task_enumber =  trim(Input::get('task_enumber'));

		$tasklevel =  trim(Input::get('tasklevel'));
		$p30_pay =  trim(Input::get('pay_p30'));
		$p30_email =  trim(Input::get('email_p30'));
		
		DB::table('task')->insert(['task_year' => $year, 'task_month' => $month, 'task_name' => $tastname, 'task_classified' => $classified, 'enterhours' => $enterhours, 'holiday' => $holiday, 'process' => $process, 'payslips' => $payslips, 'email' => $email, 'secondary_email' => $secondary_email, 'salutation' => $salutation,  'upload' => $upload, 'task_email' => $task_email,'network' => $location, 'task_enumber' => $task_enumber, 'tasklevel' => $tasklevel, 'p30_pay' => $p30_pay, 'p30_email' => $p30_email]);
		return redirect('user/select_month/'.base64_encode($monthid))->with('message', 'Task Created Success');
	}
	public function deletetask($id=""){
		$id = base64_decode($id);
		DB::table('task')->where('task_id',$id)->delete();
		return Redirect::back();
	}
	public function task_enterhours()
	{
		$id = Input::get('id');
		$enterhouse = Input::get('enterhouse');
		DB::table('task')->where('task_id',$id)->update(['enterhours' => $enterhouse]);
	}
	public function task_started_checkbox()
	{
		$id = Input::get('id');
		$task_started = Input::get('task_started');
		DB::table('task')->where('task_id',$id)->update(['task_started' => $task_started]);
	}
	public function task_holiday()
	{
		$id = Input::get('id');
		$holiday = Input::get('holiday');
		DB::table('task')->where('task_id',$id)->update(['holiday' => $holiday]);
	}
	public function task_process()
	{
		$id = Input::get('id');
		$process = Input::get('process');
		DB::table('task')->where('task_id',$id)->update(['process' => $process]);
	}
	public function task_payslips()
	{
		$id = Input::get('id');
		$payslips = Input::get('payslips');
		DB::table('task')->where('task_id',$id)->update(['payslips' => $payslips]);
	}
	public function task_email()
	{
		$id = Input::get('id');
		$email = Input::get('email');
		DB::table('task')->where('task_id',$id)->update(['email' => $email]);
	}
	public function task_upload()
	{
		$id = Input::get('id');
		$upload = Input::get('upload');
		DB::table('task')->where('task_id',$id)->update(['upload' => $upload]);
	}
	public function task_date_update()
	{
		$id = Input::get('id');
		$date = Input::get('date');
		$exp = explode('-',$date);
		$date = $exp[2].'-'.$exp[0].'-'.$exp[1];
		DB::table('task')->where('task_id',$id)->update(['date' => $date]);
	}
	public function task_email_update()
	{
		$id = Input::get('id');
		$email = Input::get('email');
		DB::table('task')->where('task_id',$id)->update(['task_email' => $email]);
	}
	public function task_users_update()
	{
		$id = Input::get('id');
		$users = Input::get('users');
		DB::table('task')->where('task_id',$id)->update(['users' => $users]);
	}
	public function task_classified_update()
	{
		$id = Input::get('id');
		$classified = Input::get('classified');
		DB::table('task')->where('task_id',$id)->update(['task_classified' => $classified]);
	}
	
	public function task_comments_update()
	{
		$id = Input::get('id');
		$comments = Input::get('comments');
		DB::table('task')->where('task_id',$id)->update(['comments' => $comments]);
	}
	public function task_image_upload()
	{
		$total = count($_FILES['image_file']['name']);
		$id = Input::get('hidden_id');
		for($i=0; $i<$total; $i++) {
		 	$filename = $_FILES['image_file']['name'][$i];
			$data_img = DB::table('task')->where('task_id',$id)->first();
			
			$tmp_name = $_FILES['image_file']['tmp_name'][$i];
			$upload_dir = 'uploads/task_image';
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
			$upload_dir = $upload_dir.'/'.base64_encode($data_img->users);
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
			$upload_dir = $upload_dir.'/'.base64_encode($data_img->task_id);
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
			move_uploaded_file($tmp_name, $upload_dir.'/'.$filename);	
			$data['task_id'] = $data_img->task_id;
			$data['attachment'] = $filename;
			$data['url'] = $upload_dir;
			DB::table('task_attached')->insert($data);
		}
		if($data_img->task_week != 0)
		{
			return redirect('user/select_week/'.base64_encode($data_img->task_week).'?divid=taskidtr_'.$id);
		}
		else{
			return redirect('user/select_month/'.base64_encode($data_img->task_month).'?divid=taskidtr_'.$id);
		}
	}
	public function task_delete_image()
	{
		$imgid = Input::get('imgid');
		DB::table('task_attached')->where('id',$imgid)->delete();
	}
	public function task_delete_all_image()
	{
		$taskid = Input::get('taskid');
		DB::table('task_attached')->where('task_id',$taskid)->delete();
	}
	public function task_status_update()
	{
		$id = Input::get('id');
		$status = Input::get('status');
		DB::table('task')->where('task_id',$id)->update(['task_status' => $status,'updatetime' => date('Y-m-d H:i:s')]);
		$details = DB::table('task')->where('task_id',$id)->first();

      $seperatedate = explode(' ',$details->updatetime);
      $explodedate = explode('-',$seperatedate[0]);
      $explodetime = explode(':',$seperatedate[1]);
      $date = $explodedate[1].'-'.$explodedate[2].'-'.$explodedate[0];
      $time = $explodetime[0].':'.$explodetime[1];

      echo json_encode(["date" => $date,"time" => $time]);
	}
	public function get_week_by_year()
	{
		$id = Input::get('id');
		$year = Input::get('year');
		$weeks = DB::table('week')->where('year', $year)->where('week_closed','=', '0000-00-00 00:00:00')->get();
		$output = '<h5 style="font-weight:800">CHOOSE WEEK : </h5>';
		$output.='<ul>';
            if(count($weeks)){
              foreach($weeks as $week){
              	$output.='<li><a href="javascript:" class="week_button" data-element="'.$week->week_id.'">Week '.$week->week.'</a></li>';
              }
            }
            else{
              $output.='Week Not Found';
            }  
        $output.='</ul>';
        echo $output;
	}
	public function get_month_by_year()
	{
		$id = Input::get('id');
		$year = Input::get('year');
		$months = DB::table('month')->where('year', $year)->where('month_closed','=', '0000-00-00 00:00:00')->get();
		$output = '<h5 style="font-weight:800">CHOOSE MONTH : </h5>';
		$output.='<ul>';
            if(count($months)){
              foreach($months as $month){
              	$output.='<li><a href="javascript:" class="month_button" data-element="'.$month->month_id.'">Month '.$month->month.'</a></li>';
              }
            }
            else{
              $output.='Month Not Found';
            }  
        $output.='</ul>';
        echo $output;
	}
	public function copy_task()
	{
		$id = Input::get('hidden_task_id');
		$year = Input::get('hidden_copy_year');
		$week = Input::get('hidden_copy_week');
		$month = Input::get('hidden_copy_month');
		$category = Input::get('category_type_copy');

		$taskdetails = DB::table('task')->where('task_id',$id)->first();
		$data['task_year'] = $year;
		$data['task_week'] = ($week != '')?$week:0;
		$data['task_month'] = ($month != '')?$month:0;
		$data['task_name'] = $taskdetails->task_name;
		$data['task_classified'] = $category;

		$data['enterhours'] = ($taskdetails->enterhours == 2)?2:0;
		$data['holiday'] = ($taskdetails->holiday == 2)?2:0;
		$data['process'] = ($taskdetails->process == 2)?2:0;
		$data['payslips'] = ($taskdetails->payslips == 2)?2:0;
		$data['email'] = ($taskdetails->email == 2)?2:0;
		$data['upload'] = ($taskdetails->upload == 2)?2:0;

		$data['task_email'] = $taskdetails->task_email;
		$data['secondary_email'] = $taskdetails->secondary_email;
		$data['salutation'] = $taskdetails->salutation;
		$data['comments'] = $taskdetails->comments;
		$data['attached'] = $taskdetails->attached;
		$data['network'] = $taskdetails->network;
		$data['task_enumber'] = $taskdetails->task_enumber;
		$data['task_status'] = 0;

		$data['task_enumber'] = $taskdetails->task_enumber;

		DB::table('task')->where('task_id',$id)->insert([$data]);
		if($week != '')
		{
			return redirect('user/select_week/'.base64_encode($week))->with('message','Task Copied Successfully');
		}
		elseif($month != '')
		{
			return redirect('user/select_month/'.base64_encode($month))->with('message','Task Copied Successfully');
		}
	}
	public function notify_tasks()
	{
		$id = $_GET['id'];
		$value = $_GET['value'];
		$admin_details = DB::table('admin')->where('id',1)->first();
		$year = DB::table('week')->where('week_id', $id)->first();
		$year_id = $this->year->getdetail($year->year);
		$year_user = DB::table('taskyear')->where('taskyear', $year_id->year_id)->first();
		$result_task = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',$value)->get();
		$output = '<table class="table_bg" style="width:100%">';
		if(count($result_task))
		{
			$output.= '<tr class="background_bg"><td>Task Name</td><td>Notify</td><td>Primary Email</td><td>Secondary Email</td></tr>';
			foreach($result_task as $task)
			{
				if($task->task_status == 1) { $task_label='style="color:#f00 !important;font-weight:800;text-align:left;"'; } elseif($task->task_started == 1) { $task_label='style="color:#89ff00 !important;font-weight:800;text-align:left;"'; } else{ $task_label='style="color:#000 !important;font-weight:600;text-align:left;"'; } 
				$output.='<tr>
					<td '.$task_label.'>'.$task->task_name.'</td><td style="text-align:center">';
					if($task->task_status == 1 || $task->task_started == 1)
					{
						$output.='<input type="checkbox" name="notify_option" class="notify_option" data-element="'.$task->task_id.'" value="1"><label >&nbsp;</label>';
					}
					else{
						$output.='<input type="checkbox" name="notify_option" class="notify_option" data-element="'.$task->task_id.'" value="1" checked><label >&nbsp;</label>';
					}
				$output.='</td>
				<td style="text-align:center;color:#000 !important;"><input type="text" name="notify_primary_email" class="notify_primary_email form-control" value="'.$task->task_email.'" data-element="'.$task->task_id.'" readonly></td>
				<td style="text-align:center;color:#000 !important;"><input type="text" name="notify_secondary_email" class="notify_secondary_email form-control" value="'.$task->secondary_email.'" data-element="'.$task->task_id.'" readonly></td>
				</tr>';
			}
			$output.='';
		}
		$output.='</table>
		<h5 style="font-weight:800; margin-top:15px">MESSAGE :</h5>
		<textarea class="form-control input-sm" id="editor_1" name="notify_message" style="height:120px">'.$admin_details->notify_message.'</textarea>';
		echo $output;
	}
	public function notify_tasks_month()
	{
		$id = $_GET['id'];
		$value = $_GET['value'];
		$admin_details = DB::table('admin')->where('id',1)->first();
		$year = DB::table('month')->where('month_id', $id)->first();
		$year_id = $this->year->getdetail($year->year);
		$year_user = DB::table('taskyear')->where('taskyear', $year_id->year_id)->first();
		$result_task = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',$value)->get();
		$output = '<table class="table_bg" style="width:100%">';
		if(count($result_task))
		{
			$output.= '<tr class="background_bg"><td>Task Name</td><td>Notify</td><td>Primary Email</td><td>Secondary Email</td></tr>';
			foreach($result_task as $task)
			{
				if($task->task_status == 1) { $task_label='style="color:#f00 !important;font-weight:800;text-align:left;"'; } elseif($task->task_started == 1) { $task_label='style="color:#89ff00 !important;font-weight:800;text-align:left;"'; } else{ $task_label='style="color:#000 !important;font-weight:600;text-align:left;"'; } 
				$output.='<tr>
					<td '.$task_label.'>'.$task->task_name.'</td><td style="text-align:center">';
					if($task->task_status == 1 || $task->task_started == 1)
					{
						$output.='<input type="checkbox" name="notify_option" class="notify_option" data-element="'.$task->task_id.'" value="1"><label >&nbsp;</label>';
					}
					else{
						$output.='<input type="checkbox" name="notify_option" class="notify_option" data-element="'.$task->task_id.'" value="1" checked><label >&nbsp;</label>';
					}
				$output.='</td>
				<td style="text-align:center;color:#000 !important;"><input type="text" name="notify_primary_email" class="notify_primary_email form-control" value="'.$task->task_email.'" data-element="'.$task->task_id.'" readonly></td>
				<td style="text-align:center;color:#000 !important;"><input type="text" name="notify_secondary_email" class="notify_secondary_email form-control" value="'.$task->secondary_email.'" data-element="'.$task->task_id.'" readonly></td>
				</tr>';
			}
			$output.='';
		}
		$output.='</table>

		<h5 style="font-weight:800; margin-top:15px">MESSAGE :</h5>
		<textarea class="form-control input-sm" id="editor_1" name="notify_message" style="height:120px">'.$admin_details->notify_message.'</textarea>
		';
		echo $output;
	}
	public function email_unsent_files()
	{
		$from_input = Input::get('select_user');
		$details = DB::table('user')->where('user_id',$from_input)->first();
		$from = $details->email;
		$toemails = Input::get('to_user').','.Input::get('cc_unsent');
		$sentmails = Input::get('to_user').', '.Input::get('cc_unsent');
		$subject = Input::get('subject_unsent'); 
		$message = Input::get('message_editor');
		$attachments = Input::get('check_attachment');
		$explode = explode(',',$toemails);
		$data['sentmails'] = $sentmails;
		if(count($attachments))
		{
			if(count($explode))
			{
				foreach($explode as $exp)
				{
					$to = trim($exp);
					$data['logo'] = URL::to('assets/images/easy_payroll_logo.png');
					$data['message'] = $message;
					$contentmessage = view('user/email_share_paper', $data);
					require_once 'AttachMailer.php';
					$mailer = new AttachMailer($from, $to, $subject, $contentmessage);
					foreach($attachments as $attachment)
					{
						$attachment_details = DB::table('task_attached')->where('id',$attachment)->first();
						$path = $attachment_details->url.'/'.$attachment_details->attachment;
						$mailer->attachFile($path);
						DB::table('task_attached')->where('id',$attachment)->update(['status' => 1]);
						$task_id = $attachment_details->task_id;
					}
					$mailer->send() ? "envoye": "probleme envoi";
					
				}
				$date = date('Y-m-d H:i:s');
				DB::table('task')->where('task_id',$task_id)->update(['last_email_sent' => $date]);
				return Redirect::back()->with('message', 'Email Sent Successfully');
			}
			else{
				return Redirect::back()->with('error', 'Email Field is empty so email is not sent');
			}
		}
		else{
			return Redirect::back()->with('error', 'Attachments are empty so Email is not sent');
		}
	}
	public function email_report_send($id='')
	{
		$type = Input::get('type');
		$week = Input::get('week');
		$month = Input::get('month');
		$year = Input::get('year');
		$hidden_report_type = Input::get('hidden_report_type');
		$from_input = Input::get('select_user_report');
		$details = DB::table('user')->where('user_id',$from_input)->first();
		$from = $details->email;
		$toemails = Input::get('to_user_report').','.Input::get('cc_report');
		$sentemails = Input::get('to_user_report').', '.Input::get('cc_report');
		$subject = Input::get('subject_report'); 
		$message = Input::get('message_report');
		$explode = explode(',',$toemails);
		$data['sentmails'] = $sentemails;
		if(count($explode))
		{
			foreach($explode as $exp)
			{
				$to = trim($exp);
				$data['logo'] = URL::to('assets/images/easy_payroll_logo.png');
				$data['message'] = $message;
				$contentmessage = view('user/email_share_paper', $data);
				require_once 'AttachMailer.php';
				$mailer = new AttachMailer($from, $to, $subject, $contentmessage);
				if($hidden_report_type == "task_report")
				{
					if($type == 'week')
					{
						$path = 'papers/Task_Report_For_Year-'.$year.'_Week-'.$week.'.pdf';
					}
					elseif($type == 'month'){
						$path = 'papers/Task_Report_For_Year-'.$year.'_Month-'.$month.'.pdf';
					}
				}
				elseif($hidden_report_type == "notify_report"){
					if($type == 'week')
					{
						$path = 'papers/Notify_Report_For_Year-'.$year.'_Week-'.$week.'.pdf';
					}
					elseif($type == 'month'){
						$path = 'papers/Notify_Report_For_Year-'.$year.'_Month-'.$month.'.pdf';
					}
				}
				$mailer->attachFile($path);
				$mailer->send() ? "envoye": "probleme envoi";
			}
		}
		else{
			return Redirect::back()->with('error', 'Email Field is empty so email is not sent');
		}
		return Redirect::back()->with('message', 'Email Sent Successfully');
	}
	public function email_report_pdf(){
		$id = Input::get('id');
		$year = DB::table('week')->where('week_id', $id)->first();
		$year_id = $this->year->getdetail($year->year);
		$year_user = DB::table('taskyear')->where('taskyear', $year_id->year_id)->first();
		$result_task_completed = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',1)->where('task_status',1)->get();
		$result_task_completed_enh = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',2)->where('task_status',1)->get();
		$result_task_completed_cmp = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',3)->where('task_status',1)->get();
		
		$result_task_incomplete = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',1)->where('task_status',0)->get();
		$result_task_incomplete_enh = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',2)->where('task_status',0)->get();
		$result_task_incomplete_cmp = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',3)->where('task_status',0)->get();
		$output = '<style>
						.fa {
						    display: inline-block;
						    font: normal normal normal 14px/1 FontAwesome;
						    font-size: inherit;
						    text-rendering: auto;
						    -webkit-font-smoothing: antialiased;
						    -moz-osx-font-smoothing: grayscale;
						}
						.fa-check:before {
						    content: "\f00c";
						}
						body{
							font-size:8px !important;
						}
						input[type=checkbox]{
							margin-top:10px !important;
							border:0px !important
						}
					</style>
					<p style="font-size:18px !important;">Task Completed</p>
					<p style="font-size:18px !important;">Standard</p>
					<table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        
				        ';
		             if(count($result_task_completed)){
		                $i=1;
		                foreach ($result_task_completed as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;">'.$result->updatetime.'</td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>

		    		<p style="font-size:18px !important;">Enhanced</p>
					<table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        
				        ';
		             if(count($result_task_completed_enh)){
		                $i=1;
		                foreach ($result_task_completed_enh as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;">'.$result->updatetime.'</td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>

		    		<p style="font-size:18px !important;">Complex</p>
					<table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        
				        ';
		             if(count($result_task_completed_cmp)){
		                $i=1;
		                foreach ($result_task_completed_cmp as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;">'.$result->updatetime.'</td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>

			    	 <p style="font-size:18px !important; margin-top:10px !important">Task Incomplete</p>
			    	 <p style="font-size:18px !important; margin-top:10px !important">Standard</p>
			    	 <table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        
				        ';
		             if(count($result_task_incomplete)){
		                $i=1;
		                foreach ($result_task_incomplete as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>

		    		<p style="font-size:18px !important; margin-top:10px !important">Enhanced</p>
			    	 <table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        
				        ';
		             if(count($result_task_incomplete_enh)){
		                $i=1;
		                foreach ($result_task_incomplete_enh as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>

		    		<p style="font-size:18px !important; margin-top:10px !important">Complex</p>
			    	 <table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        
				        ';
		             if(count($result_task_incomplete_cmp)){
		                $i=1;
		                foreach ($result_task_incomplete_cmp as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>';

			    $pdf = PDF::loadHTML($output);
			    $pdf->save('papers/Task_Report_For_Year-'.$year_id->year_name.'_Week-'.$year->week.'.pdf');

	   		 	$subject = 'EasyPayroll.ie: Task Report For Year '.$year_id->year_name.' Week'.$year->week.'';	
	   		 	echo $subject;
	}
	public function email_notify_pdf(){
		$email = Input::get('email');
		$message = Input::get('message');
		$admin_details = Db::table('admin')->where('id',1)->first();
		$admin_cc = $admin_details->task_cc_email;

		$from = $admin_details->email;
		$to = trim($email);
		$cc = $admin_cc;
		$data['sentmails'] = $to.' , '.$cc;
		$data['logo'] = URL::to('assets/images/easy_payroll_logo.png');
		$data['message'] = $message;
		$contentmessage = view('user/email_notify', $data);

		$subject = 'EasyPayroll.ie: Notification';
		require_once 'AttachMailer.php';
		if($to != '')
		{
			$mailer = new AttachMailer($from, $to, $subject, $contentmessage);
			$mailer->send($cc) ? "envoye": "probleme envoi";
		}
	}
	public function email_notify_pdf_month(){
		$id = Input::get('id');
		$commaval = Input::get('commaval');
		$explode = explode(',',$commaval);
		$type = Input::get('type');

		$year = DB::table('month')->where('month_id', $id)->first();
		$year_id = $this->year->getdetail($year->year);
		$year_user = DB::table('taskyear')->where('taskyear', $year_id->year_id)->first();
		$result_task = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',$type)->get();
		
		$output = '<style>
						.fa {
						    display: inline-block;
						    font: normal normal normal 14px/1 FontAwesome;
						    font-size: inherit;
						    text-rendering: auto;
						    -webkit-font-smoothing: antialiased;
						    -moz-osx-font-smoothing: grayscale;
						}
						.fa-check:before {
						    content: "\f00c";
						}
						body{
							font-size:8px !important;
						}
						input[type=checkbox]{
							margin-top:10px !important;
							border:0px !important
						}
					</style>
					<table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Checkbox</td>
				          </tr>
				        
				        ';
		             if(count($result_task)){
		                foreach ($result_task as $result) {
				            $output.= '<tr>';
				                $output.= '<td style="text-align: center;border:1px solid #000;"><label class="task_label">'.$result->task_name.'</label></td><td style="text-align: center;border:1px solid #000;">';
				                if(in_array($result->task_id,$explode))
				                {
				                	$output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                }
				                else{
				                	$output.='<input type="checkbox"><label >&nbsp;</label>';
				                }   
				            $output.='</td></tr>';
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td>
		                </tr>";
		             }
			        $output.='
		    		</table>';

			    $pdf = PDF::loadHTML($output);
			    $pdf->save('papers/Notify_Report_For_Year-'.$year_id->year_name.'_Month-'.$year->month.'.pdf');

	   		 	$subject = 'EasyPayroll.ie: Notify Report For Year '.$year_id->year_name.' Month'.$year->month.'';	
	   		 	echo $subject;
	}
	public function alltask_report_pdf(){
		$id = Input::get('id');
		$year = DB::table('week')->where('week_id', $id)->first();
		$year_id = $this->year->getdetail($year->year);
		$year_user = DB::table('taskyear')->where('taskyear', $year_id->year_id)->first();
		$result_task_completed = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',1)->where('task_status',1)->get();
		$result_task_completed_enh = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',2)->where('task_status',1)->get();
		$result_task_completed_cmp = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',3)->where('task_status',1)->get();
		
		$result_task_incomplete = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',1)->where('task_status',0)->get();
		$result_task_incomplete_enh = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',2)->where('task_status',0)->get();
		$result_task_incomplete_cmp = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',3)->where('task_status',0)->get();
		$output = '<style>
						.fa {
						    display: inline-block;
						    font: normal normal normal 14px/1 FontAwesome;
						    font-size: inherit;
						    text-rendering: auto;
						    -webkit-font-smoothing: antialiased;
						    -moz-osx-font-smoothing: grayscale;
						}
						.fa-check:before {
						    content: "\f00c";
						}
						body{
							font-size:8px !important;
						}
						input[type=checkbox]{
							margin-top:10px !important;
							border:0px !important
						}
					</style>
					<p style="font-size:18px !important;">Task Completed</p>
					<p style="font-size:18px !important;">Standard</p>
					<table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        
				        ';
		             if(count($result_task_completed)){
		                $i=1;
		                foreach ($result_task_completed as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;">'.$result->updatetime.'</td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>

		    		<p style="font-size:18px !important;">Enhanced</p>
					<table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        
				        ';
		             if(count($result_task_completed_enh)){
		                $i=1;
		                foreach ($result_task_completed_enh as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;">'.$result->updatetime.'</td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>

		    		<p style="font-size:18px !important;">Complex</p>
					<table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        
				        ';
		             if(count($result_task_completed_cmp)){
		                $i=1;
		                foreach ($result_task_completed_cmp as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;">'.$result->updatetime.'</td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>

			    	 <p style="font-size:18px !important; margin-top:10px !important">Task Incomplete</p>
			    	 <p style="font-size:18px !important; margin-top:10px !important">Standard</p>
			    	 <table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        
				        ';
		             if(count($result_task_incomplete)){
		                $i=1;
		                foreach ($result_task_incomplete as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>

		    		<p style="font-size:18px !important; margin-top:10px !important">Enhanced</p>
			    	 <table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        
				        ';
		             if(count($result_task_incomplete_enh)){
		                $i=1;
		                foreach ($result_task_incomplete_enh as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>

		    		<p style="font-size:18px !important; margin-top:10px !important">Complex</p>
			    	 <table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        
				        ';
		             if(count($result_task_incomplete_cmp)){
		                $i=1;
		                foreach ($result_task_incomplete_cmp as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>';

			    $pdf = PDF::loadHTML($output);
			    $pdf->save('papers/Task_Report_For_Year-'.$year_id->year_name.'_Week-'.$year->week.'.pdf');

	   		 	$name = 'Task_Report_For_Year-'.$year_id->year_name.'_Week-'.$year->week.'.pdf';	
	   		 	echo $name;
	}
	public function task_complete_report_pdf(){
		$id = Input::get('id');
		$year = DB::table('week')->where('week_id', $id)->first();
		$year_id = $this->year->getdetail($year->year);
		$year_user = DB::table('taskyear')->where('taskyear', $year_id->year_id)->first();
		
		$result_task_completed = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',1)->where('task_status',1)->get();
		$result_task_completed_enh = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',2)->where('task_status',1)->get();
		$result_task_completed_cmp = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',3)->where('task_status',1)->get();
		
		$output = '<style>
						.fa {
						    display: inline-block;
						    font: normal normal normal 14px/1 FontAwesome;
						    font-size: inherit;
						    text-rendering: auto;
						    -webkit-font-smoothing: antialiased;
						    -moz-osx-font-smoothing: grayscale;
						}
						.fa-check:before {
						    content: "\f00c";
						}
						body{
							font-size:8px !important;
						}
						input[type=checkbox]{
							margin-top:10px !important;
							border:0px !important
						}
					</style>
					<p style="font-size:18px !important;">Task Completed</p>
					<p style="font-size:18px !important;">Standard</p>
					<table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        
				        ';
		             if(count($result_task_completed)){
		                $i=1;
		                foreach ($result_task_completed as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;">'.$result->updatetime.'</td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>

		    		<p style="font-size:18px !important;">Enhanced</p>
					<table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        
				        ';
		             if(count($result_task_completed_enh)){
		                $i=1;
		                foreach ($result_task_completed_enh as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;">'.$result->updatetime.'</td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>

		    		<p style="font-size:18px !important;">Complex</p>
					<table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        
				        ';
		             if(count($result_task_completed_cmp)){
		                $i=1;
		                foreach ($result_task_completed_cmp as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;">'.$result->updatetime.'</td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>';

			    $pdf = PDF::loadHTML($output);
			    $pdf->save('papers/Task_Report_For_Year-'.$year_id->year_name.'_Week-'.$year->week.'.pdf');

	   		 	$name = 'Task_Report_For_Year-'.$year_id->year_name.'_Week-'.$year->week.'.pdf';	
	   		 	echo $name;
	}
	public function task_incomplete_report_pdf(){
		$id = Input::get('id');
		$year = DB::table('week')->where('week_id', $id)->first();
		$year_id = $this->year->getdetail($year->year);
		$year_user = DB::table('taskyear')->where('taskyear', $year_id->year_id)->first();
		
		$result_task_incomplete = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',1)->where('task_status',0)->get();
		$result_task_incomplete_enh = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',2)->where('task_status',0)->get();
		$result_task_incomplete_cmp = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',3)->where('task_status',0)->get();
		$output = '<style>
						.fa {
						    display: inline-block;
						    font: normal normal normal 14px/1 FontAwesome;
						    font-size: inherit;
						    text-rendering: auto;
						    -webkit-font-smoothing: antialiased;
						    -moz-osx-font-smoothing: grayscale;
						}
						.fa-check:before {
						    content: "\f00c";
						}
						body{
							font-size:8px !important;
						}
						input[type=checkbox]{
							margin-top:10px !important;
							border:0px !important
						}
					</style>
			    	 <p style="font-size:18px !important; margin-top:10px !important">Task Incomplete</p>
			    	 <p style="font-size:18px !important; margin-top:10px !important">Standard</p>
			    	 <table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        
				        ';
		             if(count($result_task_incomplete)){
		                $i=1;
		                foreach ($result_task_incomplete as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>

		    		<p style="font-size:18px !important; margin-top:10px !important">Enhanced</p>
			    	 <table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        
				        ';
		             if(count($result_task_incomplete_enh)){
		                $i=1;
		                foreach ($result_task_incomplete_enh as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>

		    		<p style="font-size:18px !important; margin-top:10px !important">Complex</p>
			    	 <table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        
				        ';
		             if(count($result_task_incomplete_cmp)){
		                $i=1;
		                foreach ($result_task_incomplete_cmp as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>';

			    $pdf = PDF::loadHTML($output);
			    $pdf->save('papers/Task_Report_For_Year-'.$year_id->year_name.'_Week-'.$year->week.'.pdf');

	   		 	$name = 'Task_Report_For_Year-'.$year_id->year_name.'_Week-'.$year->week.'.pdf';	
	   		 	echo $name;
	}
	public function email_report_pdf_month(){
		$id = Input::get('id');
		$year = DB::table('month')->where('month_id', $id)->first();
		$year_id = $this->year->getdetail($year->year);
		$year_user = DB::table('taskyear')->where('taskyear', $year_id->year_id)->first();
		$result_task_completed = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',1)->where('task_status',1)->get();
		$result_task_completed_enh = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',2)->where('task_status',1)->get();
		$result_task_completed_cmp = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',3)->where('task_status',1)->get();
		
		$result_task_incomplete = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',1)->where('task_status',0)->get();
		$result_task_incomplete_enh = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',2)->where('task_status',0)->get();
		$result_task_incomplete_cmp = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',3)->where('task_status',0)->get();
		$output = '<style>
						.fa {
						    display: inline-block;
						    font: normal normal normal 14px/1 FontAwesome;
						    font-size: inherit;
						    text-rendering: auto;
						    -webkit-font-smoothing: antialiased;
						    -moz-osx-font-smoothing: grayscale;
						}
						.fa-check:before {
						    content: "\f00c";
						}
						body{
							font-size:8px !important;
						}
						input[type=checkbox]{
							margin-top:10px !important;
							border:0px !important
						}
					</style>
					<p style="font-size:18px !important;">Task Completed</p>
					<p style="font-size:18px !important;">Standard</p>
					<table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        
				        ';
		             if(count($result_task_completed)){
		                $i=1;
		                foreach ($result_task_completed as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;">'.$result->updatetime.'</td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>

		    		<p style="font-size:18px !important;">Enhanced</p>
					<table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        
				        ';
		             if(count($result_task_completed_enh)){
		                $i=1;
		                foreach ($result_task_completed_enh as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;">'.$result->updatetime.'</td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>

		    		<p style="font-size:18px !important;">Complex</p>
					<table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        
				        ';
		             if(count($result_task_completed_cmp)){
		                $i=1;
		                foreach ($result_task_completed_cmp as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;">'.$result->updatetime.'</td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>

			    	 <p style="font-size:18px !important; margin-top:10px !important">Task Incomplete</p>
			    	 <p style="font-size:18px !important; margin-top:10px !important">Standard</p>
			    	 <table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        
				        ';
		             if(count($result_task_incomplete)){
		                $i=1;
		                foreach ($result_task_incomplete as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>

		    		<p style="font-size:18px !important; margin-top:10px !important">Enhanced</p>
			    	 <table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        
				        ';
		             if(count($result_task_incomplete_enh)){
		                $i=1;
		                foreach ($result_task_incomplete_enh as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>

		    		<p style="font-size:18px !important; margin-top:10px !important">Complex</p>
			    	 <table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        
				        ';
		             if(count($result_task_incomplete_cmp)){
		                $i=1;
		                foreach ($result_task_incomplete_cmp as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>';

			    $pdf = PDF::loadHTML($output);
			    $pdf->save('papers/Task_Report_For_Year-'.$year_id->year_name.'_Month-'.$year->month.'.pdf');
				$subject = 'EasyPayroll.ie: Task Report For Year '.$year_id->year_name.' Month'.$year->month.'';	
	   		 	echo $subject;
	}
	public function alltask_report_pdf_month(){
		$id = Input::get('id');
		$year = DB::table('month')->where('month_id', $id)->first();
		$year_id = $this->year->getdetail($year->year);
		$year_user = DB::table('taskyear')->where('taskyear', $year_id->year_id)->first();
		$result_task_completed = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',1)->where('task_status',1)->get();
		$result_task_completed_enh = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',2)->where('task_status',1)->get();
		$result_task_completed_cmp = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',3)->where('task_status',1)->get();
		
		$result_task_incomplete = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',1)->where('task_status',0)->get();
		$result_task_incomplete_enh = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',2)->where('task_status',0)->get();
		$result_task_incomplete_cmp = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',3)->where('task_status',0)->get();
		$output = '<style>
						.fa {
						    display: inline-block;
						    font: normal normal normal 14px/1 FontAwesome;
						    font-size: inherit;
						    text-rendering: auto;
						    -webkit-font-smoothing: antialiased;
						    -moz-osx-font-smoothing: grayscale;
						}
						.fa-check:before {
						    content: "\f00c";
						}
						body{
							font-size:8px !important;
						}
						input[type=checkbox]{
							margin-top:10px !important;
							border:0px !important
						}
					</style>
					<p style="font-size:18px !important;">Task Completed</p>
					<p style="font-size:18px !important;">Standard</p>
					<table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        
				        ';
		             if(count($result_task_completed)){
		                $i=1;
		                foreach ($result_task_completed as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;">'.$result->updatetime.'</td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>

		    		<p style="font-size:18px !important;">Enhanced</p>
					<table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        
				        ';
		             if(count($result_task_completed_enh)){
		                $i=1;
		                foreach ($result_task_completed_enh as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;">'.$result->updatetime.'</td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>

		    		<p style="font-size:18px !important;">Complex</p>
					<table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        
				        ';
		             if(count($result_task_completed_cmp)){
		                $i=1;
		                foreach ($result_task_completed_cmp as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;">'.$result->updatetime.'</td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>

			    	 <p style="font-size:18px !important; margin-top:10px !important">Task Incomplete</p>
			    	 <p style="font-size:18px !important; margin-top:10px !important">Standard</p>
			    	 <table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        
				        ';
		             if(count($result_task_incomplete)){
		                $i=1;
		                foreach ($result_task_incomplete as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>

		    		<p style="font-size:18px !important; margin-top:10px !important">Enhanced</p>
			    	 <table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        
				        ';
		             if(count($result_task_incomplete_enh)){
		                $i=1;
		                foreach ($result_task_incomplete_enh as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>

		    		<p style="font-size:18px !important; margin-top:10px !important">Complex</p>
			    	 <table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        
				        ';
		             if(count($result_task_incomplete_cmp)){
		                $i=1;
		                foreach ($result_task_incomplete_cmp as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>';

			    $pdf = PDF::loadHTML($output);
			    $pdf->save('papers/Task_Report_For_Year-'.$year_id->year_name.'_Month-'.$year->month.'.pdf');
	   		 	echo 'Task_Report_For_Year-'.$year_id->year_name.'_Month-'.$year->month.'.pdf';
	}
	public function task_complete_report_pdf_month(){
		$id = Input::get('id');
		$year = DB::table('month')->where('month_id', $id)->first();
		$year_id = $this->year->getdetail($year->year);
		$year_user = DB::table('taskyear')->where('taskyear', $year_id->year_id)->first();
		$result_task_completed = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',1)->where('task_status',1)->get();
		$result_task_completed_enh = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',2)->where('task_status',1)->get();
		$result_task_completed_cmp = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',3)->where('task_status',1)->get();
		
		
		
		$output = '<style>
						.fa {
						    display: inline-block;
						    font: normal normal normal 14px/1 FontAwesome;
						    font-size: inherit;
						    text-rendering: auto;
						    -webkit-font-smoothing: antialiased;
						    -moz-osx-font-smoothing: grayscale;
						}
						.fa-check:before {
						    content: "\f00c";
						}
						body{
							font-size:8px !important;
						}
						input[type=checkbox]{
							margin-top:10px !important;
							border:0px !important
						}
					</style>
					<p style="font-size:18px !important;">Task Completed</p>
					<p style="font-size:18px !important;">Standard</p>
					<table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        
				        ';
		             if(count($result_task_completed)){
		                $i=1;
		                foreach ($result_task_completed as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;">'.$result->updatetime.'</td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>

		    		<p style="font-size:18px !important;">Enhanced</p>
					<table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        
				        ';
		             if(count($result_task_completed_enh)){
		                $i=1;
		                foreach ($result_task_completed_enh as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;">'.$result->updatetime.'</td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>

		    		<p style="font-size:18px !important;">Complex</p>
					<table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        
				        ';
		             if(count($result_task_completed_cmp)){
		                $i=1;
		                foreach ($result_task_completed_cmp as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;">'.$result->updatetime.'</td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>';

			    $pdf = PDF::loadHTML($output);
			    $pdf->save('papers/Task_Report_For_Year-'.$year_id->year_name.'_Month-'.$year->month.'.pdf');	
	   		 	echo 'Task_Report_For_Year-'.$year_id->year_name.'_Month-'.$year->month.'.pdf';
	}
	public function task_incomplete_report_pdf_month(){
		$id = Input::get('id');
		$year = DB::table('month')->where('month_id', $id)->first();
		$year_id = $this->year->getdetail($year->year);
		$year_user = DB::table('taskyear')->where('taskyear', $year_id->year_id)->first();
		
		$result_task_incomplete = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',1)->where('task_status',0)->get();
		$result_task_incomplete_enh = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',2)->where('task_status',0)->get();
		$result_task_incomplete_cmp = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',3)->where('task_status',0)->get();
		$output = '<style>
						.fa {
						    display: inline-block;
						    font: normal normal normal 14px/1 FontAwesome;
						    font-size: inherit;
						    text-rendering: auto;
						    -webkit-font-smoothing: antialiased;
						    -moz-osx-font-smoothing: grayscale;
						}
						.fa-check:before {
						    content: "\f00c";
						}
						body{
							font-size:8px !important;
						}
						input[type=checkbox]{
							margin-top:10px !important;
							border:0px !important
						}
					</style>

			    	 <p style="font-size:18px !important; margin-top:10px !important">Task Incomplete</p>
			    	 <p style="font-size:18px !important; margin-top:10px !important">Standard</p>
			    	 <table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        
				        ';
		             if(count($result_task_incomplete)){
		                $i=1;
		                foreach ($result_task_incomplete as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>

		    		<p style="font-size:18px !important; margin-top:10px !important">Enhanced</p>
			    	 <table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        
				        ';
		             if(count($result_task_incomplete_enh)){
		                $i=1;
		                foreach ($result_task_incomplete_enh as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>

		    		<p style="font-size:18px !important; margin-top:10px !important">Complex</p>
			    	 <table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: center;border:1px solid #000;">S.No</td>
				              <td style="text-align: center;border:1px solid #000;">Task Name</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>
				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>
				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>
				              <td style="text-align: center;border:1px solid #000;">Completed On</td>
				          </tr>
				        
				        ';
		             if(count($result_task_incomplete_cmp)){
		                $i=1;
		                foreach ($result_task_incomplete_cmp as $result) {
				            $output.= '<tr>';
				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 
				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>
				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->enterhours == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->enterhours == 1){
				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->holiday == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->holiday == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->process == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->process == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->payslips == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->payslips == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';
				                    if($result->email == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->email == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td>
				                <td style="text-align: center;border:1px solid #000;">';
				                    if($result->upload == 0){
				                      $output.='<label> - </label>';
				                    }
				                    else if($result->upload == 1){
				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';
				                    }
				                    else{
				                      $output.= 'N/A';
				                    }
				                $output.= '</td> 
				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       
				            </tr>';
		                $i++;
		                }
		             }
		             else{
		                $output.="<tr>
		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";
		             }
			        $output.='
		    		</table>';

			    $pdf = PDF::loadHTML($output);
			    $pdf->save('papers/Task_Report_For_Year-'.$year_id->year_name.'_Month-'.$year->month.'.pdf');
	   		 	echo 'Task_Report_For_Year-'.$year_id->year_name.'_Month-'.$year->month.'.pdf';
	}
	public function close_create_new_week($id = '')
	{
		$week_details = DB::table('week')->where('week_id',$id)->first();
		$check_week = DB::table('week')->where('year',$week_details->year)->get();
		$yearname = DB::table('year')->where('year_id',$week_details->year)->first();
		if(count($check_week) <= 52)
		{
			$count_weeks = $week_details->week + 1;
			$current = date('Y-m-d H:i:s');
			DB::table('week')->where('week_id',$id)->update(['week_closed' => $current]);
			$check_weeks = DB::table('week')->where('year',$week_details->year)->where('week',$count_weeks)->first();
			if(!count($check_weeks))
			{
				$weekid = DB::table('week')->insertGetId(['year' => $week_details->year,'week' => $count_weeks]);
				$gettasks = DB::table('task')->where('task_year',$week_details->year)->where('task_week',$id)->get();
				if(count($gettasks))
				{
					foreach($gettasks as $tasks)
					{
						$data['task_year'] = $tasks->task_year;
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
						$data['task_enumber'] = $tasks->task_enumber;
						$data['secondary_email'] = $tasks->secondary_email;
						$data['salutation'] = $tasks->salutation;
						$data['task_status'] = 0;

						DB::table('task')->insert($data);
					}
				}
				return redirect('user/select_week/'.base64_encode($weekid))->with('message','New Week Created Successfully');
			}
			else{
				return Redirect::back()->with('error', 'Sorry! the new week you are trying to create is already exists. Please click Ok to go to that week.<a href="'.URL::to('user/select_week/'.base64_encode($check_weeks->week_id)).'" class="btn btn-sm common_black_button">OK</a><a href="javascript:" class="btn btn-sm common_black_button cancel_week">Cancel</a>');
			}
		}
		else{
			return Redirect::back()->with('error', 'This is the Last week of this year '.$yearname->year_name.' so please contact your Admin to create a new year');
		}
	}
	public function close_create_new_month($id = '')
	{
		$month_details = DB::table('month')->where('month_id',$id)->first();
		$check_month = DB::table('month')->where('year',$month_details->year)->get();
		$yearname = DB::table('year')->where('year_id',$month_details->year)->first();
		if(count($check_month) <= 11)
		{
			$count_months = $month_details->month + 1;
			$current = date('Y-m-d H:i:s');
			DB::table('month')->where('month_id',$id)->update(['month_closed' => $current]);
			$check_months = DB::table('month')->where('year',$month_details->year)->where('month',$count_months)->first();
			if(!count($check_months))
			{
				$monthid = DB::table('month')->insertGetId(['year' => $month_details->year,'month' => $count_months]);
				$gettasks = DB::table('task')->where('task_year',$month_details->year)->where('task_month',$id)->get();
				if(count($gettasks))
				{
					foreach($gettasks as $tasks)
					{
						$data['task_year'] = $tasks->task_year;
						$data['task_month'] = $monthid;
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
						$data['task_enumber'] = $tasks->task_enumber;
						$data['secondary_email'] = $tasks->secondary_email;
						$data['salutation'] = $tasks->salutation;
						$data['task_status'] = 0;

						DB::table('task')->insert($data);
					}
				}
				return redirect('user/select_month/'.base64_encode($monthid))->with('message','New Month Created Successfully');
			}
			else{
				return Redirect::back()->with('error', 'Sorry! the new month you are trying to create is already exists. Please click Ok to go to that month.<a href="'.URL::to('user/select_month/'.base64_encode($check_months->month_id)).'" class="btn btn-sm common_black_button">OK</a><a href="javascript:" class="btn btn-sm common_black_button cancel_month">Cancel</a>');
			}
		}
		else{
			return Redirect::back()->with('error', 'This is the Last month of this year '.$yearname->year_name.' so please contact your Admin to create a new year');
		}
	}
	public function edit_task_name()
	{
		$task_id = Input::get('task_id');
		$details = DB::table('task')->where('task_id',$task_id)->first();
		echo json_encode(["task_name" => $details->task_name,"task_email" => $details->task_email,"secondary_email" => $details->secondary_email,"salutation" => $details->salutation,"network" => $details->network,"category" => $details->task_classified,"task_id" => $details->task_id,'holiday' => $details->holiday,'process' => $details->process,'payslips' => $details->payslips,'email' => $details->email,'upload' => $details->upload,'enterhours' => $details->enterhours, 'enumber' => $details->task_enumber,'tasklevel' => $details->tasklevel,'p30_pay' => $details->p30_pay,'p30_email' => $details->p30_email]);
	}
	public function edit_email_unsent_files()
	{
		$task_id = Input::get('task_id');
		$result = DB::table('task')->where('task_id',$task_id)->first();
		$files = '';
		$html = '';
		$date = date('d F Y', strtotime($result->last_email_sent));
		$time = date('H:i', strtotime($result->last_email_sent));
		$last_date = $date.' @ '.$time;
		if($result->users != '')
		{
			$user_details = DB::table('user')->where('user_id',$result->users)->first();
			$html.='<strong>On '.date('m-d-Y H:i').', '.$user_details->firstname.' '.$user_details->lastname.' wrote</strong><br/><br/>';  
		}
		else{
			$html.='<strong>On '.date('m-d-Y H:i').', wrote</strong><br/><br/>';  
		}
		$html.='<strong>'.$result->salutation.'</strong><br/>';  
	      $check_attached = DB::table('task_attached')->where('task_id',$task_id)->where('status',0)->get();
	      if(count($check_attached))
	      {
	        $html.='<strong>Task Name :</strong> <spam>'.$result->task_name.'</spam>';  
	        $html.='<ul>';
	        foreach($check_attached as $attch)
	        {
	            $html.='<li>'.$attch->attachment.'</li>';
	            $files.='<p><input type="checkbox" name="check_attachment[]" value="'.$attch->id.'" id="label_'.$attch->id.'" checked><label for="label_'.$attch->id.'">'.$attch->attachment.'</label></p>';
	        }
	        $html.='</ul>';
	      }
	      if($result->task_week != 0)
	      {
	      	$week_details = DB::table('week')->where('week_id',$result->task_week)->first();
	      	$subject = 'EasyPayroll.ie: '.$result->task_name.' Payroll for WEEK '.$week_details->week.'';
	      }
	      else{
	      	$month_details = DB::table('month')->where('month_id',$result->task_month)->first();
	      	$subject = 'EasyPayroll.ie: '.$result->task_name.' Payroll for MONTH '.$month_details->month.'';
	      }
	      if($result->secondary_email != '')
	      {
	      	$to_email = $result->task_email.','.$result->secondary_email;
	      }
	      else{
	      	$to_email = $result->task_email;
	      }

	     echo json_encode(["files" => $files,"html" => $html,"from" => $result->users,"to" => $to_email,'subject' => $subject,'last_email_sent' => $last_date]);
	}
	
	public function edit_task_details()
	{
		$id = Input::get('hidden_taskname_id');
		$task_name = Input::get('task_name');
		$task_network = Input::get('task_network');
		$task_category = Input::get('task_category');
		$task_email = Input::get('task_email_edit');
		$secondary_email = Input::get('secondary_email_edit');
		$salutation = Input::get('salutation_edit');
		$enterhours = Input::get('enterhours_edit');
		$holiday = Input::get('holiday_edit');
		$process = Input::get('process_edit');
		$payslips = Input::get('payslips_edit');
		$email = Input::get('email_edit');
		$upload = Input::get('uploadd_edit');

		$enumber = trim(Input::get('enumber'));

		$tasklevel =  trim(Input::get('tasklevel_edit'));
		$p30_pay =  trim(Input::get('pay_p30_edit'));
		$p30_email =  trim(Input::get('email_p30_edit'));

		$details = DB::table('task')->where('task_id',$id)->first();
		if($details->enterhours == 2)
		{
			if($enterhours == 0)
			{
				$enterhours = 0;
			}
			else{
				$enterhours = $details->enterhours;
			}
		}
		else{
			if($enterhours == 2)
			{
				$enterhours = 2;
			}
			else{
				$enterhours = $details->enterhours;
			}
		}

		if($details->holiday == 2)
		{
			if($holiday == 0)
			{
				$holiday = 0;
			}
			else{
				$holiday = $details->holiday;
			}
		}
		else{
			if($holiday == 2)
			{
				$holiday = 2;
			}
			else{
				$holiday = $details->holiday;
			}
		}

		if($details->process == 2)
		{
			if($process == 0)
			{
				$process = 0;
			}
			else{
				$process = $details->process;
			}
		}
		else{
			if($process == 2)
			{
				$process = 2;
			}
			else{
				$process = $details->process;
			}
		}

		if($details->payslips == 2)
		{
			if($payslips == 0)
			{
				$payslips = 0;
			}
			else{
				$payslips = $details->payslips;
			}
		}
		else{
			if($payslips == 2)
			{
				$payslips = 2;
			}
			else{
				$payslips = $details->payslips;
			}
		}

		if($details->email == 2)
		{
			if($email == 0)
			{
				$email = 0;
			}
			else{
				$email = $details->email;
			}
		}
		else{
			if($email == 2)
			{
				$email = 2;
			}
			else{
				$email = $details->email;
			}
		}

		if($details->upload == 2)
		{
			if($upload == 0)
			{
				$upload = 0;
			}
			else{
				$upload = $details->upload;
			}
		}
		else{
			if($upload == 2)
			{
				$upload = 2;
			}
			else{
				$upload = $details->upload;
			}
		}
		DB::table('task')->where('task_id',$id)->update(['task_name'=>$task_name,'task_email' => $task_email,'secondary_email' => $secondary_email,'salutation' => $salutation,'network' => $task_network,'task_classified' => $task_category,'enterhours' => $enterhours,'holiday' => $holiday,'process' => $process,'payslips' => $payslips,'email' => $email,'upload' => $upload, 'task_enumber' => $enumber,'tasklevel' =>$tasklevel,'p30_email' => $p30_email,'p30_pay' => $p30_pay]);
		return Redirect::back()->with('message', 'Task Name and Network Updated successfully');
	}
	public function downloadpdf()
	{
		$filepath = $_GET['filename'];
		//header("Content-Type: application/pdf");
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
		header('Content-Transfer-Encoding: binary');
		header('Cache-Control: must-revalidate');
		header('Content-Length: '.filesize($filepath));
		//ob_clean();
		//flush();
		try{
			$page = file_get_contents($filepath);
			echo $page;
			header('Set-Cookie: fileDownload=true; path=/');
		}catch(Exception $e){
			header('Set-Cookie: fileDownload=false; path=/');	
		}
		exit;
	}
	public function update_incomplete_status()
	{
		$data['week_incomplete'] = Input::get('value');
		DB::table('user_login')->where('userid',1)->update($data);
	}
	public function update_incomplete_status_month()
	{
		$data['month_incomplete'] = Input::get('value');
		DB::table('user_login')->where('userid',1)->update($data);
	}
	public function vatclients()
	{	
		$clients = DB::table('vat_clients')->get();
		return view('user/vatclients', array('clientlist' => $clients));
	}

	public function deactivevatclients($id=""){
		$id = base64_decode($id);
		$deactive =  1;
		DB::table('vat_clients')->where('client_id', $id)->update(['status' => $deactive ]);
		return redirect::back()->with('message','Deactive Success');
	}
	public function activevatclients($id=""){
		$id = base64_decode($id);
		$active =  0;
		DB::table('vat_clients')->where('client_id', $id)->update(['status' => $active ]);
		return redirect::back()->with('message','Active Success');
	}
	public function editvatclients($id=""){
		$id = base64_decode($id);
		$result = DB::table('vat_clients')->where('client_id', $id)->first();
		echo json_encode(array('name' => $result->name, 'taxnumber' => $result->taxnumber, 'pemail' =>  $result->pemail, 'semail' =>  $result->semail, 'salutation' =>  $result->salutation, 'self_manage' =>  $result->self_manage,'always_nil' =>  $result->always_nil, 'id' => $result->client_id));
	}
	public function updatevatclients(){
		$name = Input::get('name');		
		$pemail = Input::get('pemail');
		$semail = Input::get('semail');
		$salutation = Input::get('salutation');
		$self_manage = Input::get('self');
		$always_nil = Input::get('always_nil');
		$id = Input::get('id');

		DB::table('vat_clients')->where('client_id', $id)->update(['name' => $name, 'pemail' => $pemail, 'semail' => $semail, 'salutation' => $salutation, 'self_manage' => $self_manage, 'always_nil' => $always_nil]);

		$check_status = DB::table('vat_clients')->where('client_id',$id)->first();

		$red = DB::table('vat_clients')->where('status',1)->count();
		$green = DB::table('vat_clients')->where('status',0)->where('pemail','!=', '')->where('self_manage','no')->count();
		$yellow = DB::table('vat_clients')->where('status',0)->where('pemail', '')->where('self_manage','no')->count();
		$purple = DB::table('vat_clients')->where('status',0)->where('self_manage','yes')->count();
		echo json_encode(array('status' => $check_status->status, 'red' => $red, 'green' =>  $green, 'yellow' =>  $yellow, 'purple' =>  $purple));
	}
	public function addvatclients(){
		$data['name'] = Input::get('name');		
		$data['taxnumber'] = Input::get('taxnumber');	
		$data['pemail'] = Input::get('pemail');
		$data['semail'] = Input::get('semail');
		$data['salutation'] = Input::get('salutation');
		$data['tax_type'] = Input::get('tax_type');
		$data['document_type'] = Input::get('document_type');
		$data['ros_filer'] = Input::get('ros_filer');
		$data['period'] = Input::get('period_add');
		$explodedate = explode('-',Input::get('due_add'));
		if(count($explodedate) == 3)
		{
			$data['due_date'] = $explodedate[2].'-'.$explodedate[0].'-'.$explodedate[1];
		}
		$data['self_manage'] = Input::get('self');
		$data['always_nil'] = Input::get('always_nil');
		DB::table('vat_clients')->insert($data);
		return redirect::back()->with('message','Added Successfully');
	}
	public function checkclientemail()
	{
		$email = Input::get('email');
		$client_id = Input::get('client_id');
		$type = Input::get('type');
		if($client_id == "")
		{
			$check = DB::table('vat_clients')->where('pemail', $email)->first();
			$check_sec = DB::table('vat_clients')->where('semail', $email)->first();
		}
		else{
			$check = DB::table('vat_clients')->where('client_id','!=', $client_id)->where('pemail', $email)->first();
			$check_sec = DB::table('vat_clients')->where('client_id','!=', $client_id)->where('semail', $email)->first();
		}
		if(count($check) || count($check_sec))
		{
			echo 1;
		}
		else{
			echo 0;
		}
	}
	public function checkclienttaxnumber()
	{
		$tax = Input::get('taxnumber');
		$check = DB::table('vat_clients')->where('taxnumber', $tax)->first();
		if(count($check))
		{
			echo 1;
		}
		else{
			echo 0;
		}
	}
	
	public function in_array_r($item , $array){
	    return preg_match('/"'.$item.'"/i' , json_encode($array));
	}
	public function import_form()
	{
		if($_FILES['import_file']['name']!='')
		{
			$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';
			$tmp_name = $_FILES['import_file']['tmp_name'];
			$name=$_FILES['import_file']['name'];
			$errorlist = array();
			if(move_uploaded_file($tmp_name, "$uploads_dir/$name")){

				$filepath = $uploads_dir.'/'.$name;
				$objPHPExcel = PHPExcel_IOFactory::load($filepath);
				foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
					$worksheetTitle     = $worksheet->getTitle();
					$highestRow         = $worksheet->getHighestRow(); // e.g. 10
					$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
					$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
					$nrColumns = ord($highestColumn) - 64;
					if($highestRow > 500)
					{
						$height = 500;
					}
					else{
						$height = $highestRow;
					}
					$insertrows = array();
					for ($row = 2; $row <= $height; ++ $row) {
						$client_name = $worksheet->getCellByColumnAndRow(0, $row); $client_name = trim($client_name->getValue());
						$ros_filter = $worksheet->getCellByColumnAndRow(1, $row); $ros_filter = trim($ros_filter->getValue());
						$tax_type = $worksheet->getCellByColumnAndRow(2, $row); $tax_type = trim($tax_type->getValue());
						$doc_type = $worksheet->getCellByColumnAndRow(3, $row); $doc_type = trim($doc_type->getValue());
						$tax_no = $worksheet->getCellByColumnAndRow(4, $row);  $tax_no = trim($tax_no->getValue());
						$period = $worksheet->getCellByColumnAndRow(5, $row);  $period = trim($period->getValue());
						$due_date = $worksheet->getCellByColumnAndRow(6, $row);  $due_date = trim($due_date->getValue());

						if($tax_type != 'VAT')
						{
							Session::forget('insertrows');
							return redirect('user/vat_clients')->with('message_import_not_valid', 'This is not a valid VAT import file');
						}
						$check_tax = DB::table('vat_clients')->where('taxnumber',$tax_no)->first();
						$check_array = $this->in_array_r($tax_no,$insertrows);
						if($client_name != "Name not found" && $tax_no != "")
						{
							if(!count($check_tax) && $check_array == 0)
							{
								$data['name'] = $client_name;
								$data['ros_filer'] = $ros_filter;
								$data['tax_type'] = $tax_type;
								$data['document_type'] = $doc_type;
								$data['taxnumber'] = $tax_no;
								$data['period'] = $period;
								$due = explode('-',$due_date);
								if(count($due) == 3)
								{
									$data['due_date'] = $due[2].'-'.$due[1].'-'.$due[0];
								}
								else{
									$due = explode('/',$due_date);
									if(count($due) == 3)
									{
										$data['due_date'] = $due[2].'-'.$due[1].'-'.$due[0];
									}
								}
								$data['self_manage'] = 'no';
								$data['always_nil'] = 'no';
								array_push($insertrows,$data);								
							}
						}
					}
				}
				$sessn=array('insertrows' => $insertrows);
				Session::put($sessn);

				if($height >= $highestRow)
				{
					return redirect('user/vat_clients')->with('message_import', 'Items Listed successfully.');
				}
				else{
					return redirect('user/vat_clients?filename='.$name.'&height='.$height.'&round=2&highestrow='.$highestRow.'&import_type=1');
				}
			}
		}
	}
	public function import_form_one()
	{
		$name = Input::get('filename');
		$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';
		$filepath = $uploads_dir.'/'.$name;
		$objPHPExcel = PHPExcel_IOFactory::load($filepath);

		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$worksheetTitle     = $worksheet->getTitle();
			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
			$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
			$nrColumns = ord($highestColumn) - 64;
			$round = Input::get('round');
			$last_height = Input::get('height');

			$offset = $round - 1;
			$offsetcount = $last_height + 1;
			$roundcount = $round * 500;
			$nextround = $round + 1;

			if($highestRow > $roundcount)
			{
				$height = $roundcount;
			}
			else{
				$height = $highestRow;
			}
			$insertrows = Session::get('insertrows');
			for ($row = $offsetcount; $row <= $height; ++ $row) {
				$client_name = $worksheet->getCellByColumnAndRow(0, $row); $client_name = trim($client_name->getValue());
				$ros_filter = $worksheet->getCellByColumnAndRow(1, $row); $ros_filter = trim($ros_filter->getValue());
				$tax_type = $worksheet->getCellByColumnAndRow(2, $row); $tax_type = trim($tax_type->getValue());
				$doc_type = $worksheet->getCellByColumnAndRow(3, $row); $doc_type = trim($doc_type->getValue());
				$tax_no = $worksheet->getCellByColumnAndRow(4, $row);  $tax_no = trim($tax_no->getValue());
				$period = $worksheet->getCellByColumnAndRow(5, $row);  $period = trim($period->getValue());
				$due_date = $worksheet->getCellByColumnAndRow(6, $row);  $due_date = trim($due_date->getValue());

				if($tax_type != 'VAT')
				{
					Session::forget('insertrows');
					return redirect('user/vat_clients')->with('message_import_not_valid', 'This is not a valid VAT import file');
				}
				$check_tax = DB::table('vat_clients')->where('taxnumber',$tax_no)->first();
				$check_array = $this->in_array_r($tax_no,$insertrows);
				if($client_name != "Name not found" && $tax_no != "")
				{
					if(!count($check_tax) && $check_array == 0)
					{
						$data['name'] = $client_name;
						$data['ros_filer'] = strtolower($ros_filter);
						$data['tax_type'] = $tax_type;
						$data['document_type'] = $doc_type;
						$data['taxnumber'] = $tax_no;
						$data['period'] = $period;
						$due = explode('-',$due_date);
						if(count($due) == 3)
						{
							$data['due_date'] = $due[2].'-'.$due[1].'-'.$due[0];
						}
						else{
							$due = explode('/',$due_date);
							if(count($due) == 3)
							{
								$data['due_date'] = $due[2].'-'.$due[1].'-'.$due[0];
							}
						}
						$data['self_manage'] = 'no';
						$data['always_nil'] = 'no';
						array_push($insertrows,$data);								
					}
				}
			}
		}
		Session::forget("insertrows");
		$sessn=array('insertrows' => $insertrows);
		Session::put($sessn);
		if($height >= $highestRow)
		{
			return redirect('user/vat_clients')->with('message_import', 'Items Listed successfully.');
		}
		else{
			return redirect('user/vat_clients?filename='.$name.'&height='.$height.'&round='.$nextround.'&highestrow='.$highestRow.'&import_type=1');
		}
	}
	public function compare_form()
	{
		DB::table('vat_clients_compare')->truncate();
		if($_FILES['compare_file']['name']!='')
		{
			$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';
			$tmp_name = $_FILES['compare_file']['tmp_name'];
			$name=$_FILES['compare_file']['name'];
			$errorlist = array();
			if(move_uploaded_file($tmp_name, "$uploads_dir/$name")){

				$filepath = $uploads_dir.'/'.$name;
				$objPHPExcel = PHPExcel_IOFactory::load($filepath);
				foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
					$worksheetTitle     = $worksheet->getTitle();
					$highestRow         = $worksheet->getHighestRow(); // e.g. 10
					$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
					$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
					$nrColumns = ord($highestColumn) - 64;
					if($highestRow > 500)
					{
						$height = 500;
					}
					else{
						$height = $highestRow;
					}
					$customer_name_label = $worksheet->getCellByColumnAndRow(0, 1); $customer_name_label = trim($customer_name_label->getValue());
					$ros_filter_label = $worksheet->getCellByColumnAndRow(1, 1); $ros_filter_label = trim($ros_filter_label->getValue());

					if($customer_name_label == "Customer Name" && $ros_filter_label == "Mandatory ROS filer")
					{
						$insertrows = array();
						$alreadyinsertedrows = array();
						for ($row = 2; $row <= $height; ++ $row) {
							$client_name = $worksheet->getCellByColumnAndRow(0, $row); $client_name = trim($client_name->getValue());
							$ros_filter = $worksheet->getCellByColumnAndRow(1, $row); $ros_filter = trim($ros_filter->getValue());
							$tax_type = $worksheet->getCellByColumnAndRow(2, $row); $tax_type = trim($tax_type->getValue());
							$doc_type = $worksheet->getCellByColumnAndRow(3, $row); $doc_type = trim($doc_type->getValue());
							$tax_no = $worksheet->getCellByColumnAndRow(4, $row);  $tax_no = trim($tax_no->getValue());
							$period = $worksheet->getCellByColumnAndRow(5, $row);  $period = trim($period->getValue());
							$due_date = $worksheet->getCellByColumnAndRow(6, $row);  $due_date = trim($due_date->getValue());
							if($tax_type != 'VAT')
							{
								Session::forget('comparerows');
								Session::forget('alreadyinsertedrows');
								return redirect('user/vat_clients')->with('message_import_not_valid', 'This is not a valid VAT import file');
							}
							$check_tax = DB::table('vat_clients')->where('taxnumber',$tax_no)->first();
							if($client_name != "Name not found" && $tax_no != "")
							{
								if(!count($check_tax))
								{
									$data['name'] = $client_name;
									$data['ros_filer'] = $ros_filter;
									$data['tax_type'] = $tax_type;
									$data['document_type'] = $doc_type;
									$data['taxnumber'] = $tax_no;
									$data['period'] = $period;
									$data['due_date'] = $due_date;
									array_push($insertrows,$data);								
								}
								else{
									array_push($alreadyinsertedrows,$tax_no);
								}

								$dataall['name'] = $client_name;
								$dataall['ros_filer'] = $ros_filter;
								$dataall['tax_type'] = $tax_type;
								$dataall['document_type'] = $doc_type;
								$dataall['taxnumber'] = $tax_no;
								$dataall['period'] = $period;
								$due = explode('-',$due_date);
								if(count($due) == 3)
								{
									$dataall['due_date'] = $due[2].'-'.$due[1].'-'.$due[0];
								}
								else{
									$due = explode('/',$due_date);
									if(count($due) == 3)
									{
										$dataall['due_date'] = $due[2].'-'.$due[1].'-'.$due[0];
									}
								}
								DB::table('vat_clients_compare')->insert($dataall);
							}
						}
					}
					else{
						return redirect('user/vat_clients')->with('message', 'The File Format is not supported.');
					}
				}
				$sessn=array('comparerows' => $insertrows,'alreadyinsertedrows' => $alreadyinsertedrows);
				Session::put($sessn);

				if($height >= $highestRow)
				{
					return redirect('user/vat_clients')->with('message_compare', 'Items Listed successfully.');
				}
				else{
					return redirect('user/vat_clients?filename='.$name.'&height='.$height.'&round=2&highestrow='.$highestRow.'&compare_type=1');
				}
			}
		}
	}
	public function compare_form_one()
	{
		$name = Input::get('filename');
		$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';
		$filepath = $uploads_dir.'/'.$name;
		$objPHPExcel = PHPExcel_IOFactory::load($filepath);

		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$worksheetTitle     = $worksheet->getTitle();
			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
			$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
			$nrColumns = ord($highestColumn) - 64;
			$round = Input::get('round');
			$last_height = Input::get('height');

			$offset = $round - 1;
			$offsetcount = $last_height + 1;
			$roundcount = $round * 500;
			$nextround = $round + 1;

			if($highestRow > $roundcount)
			{
				$height = $roundcount;
			}
			else{
				$height = $highestRow;
			}
			$insertrows = Session::get('comparerows');
			$alreadyinsertedrows = Session::get('alreadyinsertedrows');

			for ($row = $offsetcount; $row <= $height; ++ $row) {
				$client_name = $worksheet->getCellByColumnAndRow(0, $row); $client_name = trim($client_name->getValue());
				$ros_filter = $worksheet->getCellByColumnAndRow(1, $row); $ros_filter = trim($ros_filter->getValue());
				$tax_type = $worksheet->getCellByColumnAndRow(2, $row); $tax_type = trim($tax_type->getValue());
				$doc_type = $worksheet->getCellByColumnAndRow(3, $row); $doc_type = trim($doc_type->getValue());
				$tax_no = $worksheet->getCellByColumnAndRow(4, $row);  $tax_no = trim($tax_no->getValue());
				$period = $worksheet->getCellByColumnAndRow(5, $row);  $period = trim($period->getValue());
				$due_date = $worksheet->getCellByColumnAndRow(6, $row);  $due_date = trim($due_date->getValue());
				if($tax_type != 'VAT')
				{
					Session::forget('comparerows');
					Session::forget('alreadyinsertedrows');
					return redirect('user/vat_clients')->with('message_import_not_valid', 'This is not a valid VAT import file');
				}
				$check_tax = DB::table('vat_clients')->where('taxnumber',$tax_no)->first();
				if($client_name != "Name not found" && $tax_no != "")
				{
					if(!count($check_tax))
					{
						$data['name'] = $client_name;
						$data['ros_filer'] = strtolower($ros_filter);
						$data['tax_type'] = $tax_type;
						$data['document_type'] = $doc_type;
						$data['taxnumber'] = $tax_no;
						$data['period'] = $period;
						$explode = explode("-",$due_date);
						$data['due_date'] = $explode[2].'-'.$explode[1].'-'.$explode[0];
						array_push($insertrows,$data);								
					}
					else{
						array_push($alreadyinsertedrows,$tax_no);
					}
					$dataall['name'] = $client_name;
					$dataall['ros_filer'] = $ros_filter;
					$dataall['tax_type'] = $tax_type;
					$dataall['document_type'] = $doc_type;
					$dataall['taxnumber'] = $tax_no;
					$dataall['period'] = $period;
					$due = explode('-',$due_date);
								if(count($due) == 3)
								{
									$dataall['due_date'] = $due[2].'-'.$due[1].'-'.$due[0];
								}
								else{
									$due = explode('/',$due_date);
									if(count($due) == 3)
									{
										$dataall['due_date'] = $due[2].'-'.$due[1].'-'.$due[0];
									}
								}
					DB::table('vat_clients_compare')->insert($dataall);
				}
			}
		}
		Session::forget("comparerows");
		$sessn=array('comparerows' => $insertrows,'alreadyinsertedrows' => $alreadyinsertedrows);
		Session::put($sessn);
		if($height >= $highestRow)
		{
			return redirect('user/vat_clients')->with('message_compare', 'Items Listed successfully.');
		}
		else{
			return redirect('user/vat_clients?filename='.$name.'&height='.$height.'&round='.$nextround.'&highestrow='.$highestRow.'&compare_type=1');
		}
	}
	public function vat_notifications()
	{
		$without_emailed = DB::table('vat_clients')->where('pemail','')->where('semail','')->where('status',0)->get();
		$disabled = DB::table('vat_clients')->where('status',1)->get();
		$with_emailed = DB::table('vat_clients')->where('status',0)->where('self_manage','!=','')->where('status',0)->whereRaw('(pemail != "" OR semail != "")')->get();
		return view('user/vatnotifications', array('without_emailed' => $without_emailed, 'disabled' => $disabled, 'with_emailed' => $with_emailed));
	}
	public function import_sessions()
	{
		$sessions = Session::get('insertrows');
		if(count($sessions))
		{
			foreach($sessions as $key => $sess)
			{
				DB::table('vat_clients')->insert($sess);
			}
			return redirect('user/vat_clients')->with('message', 'Clients Imported successfully.');
			Session::forget('insertrows');
		}
		else{
			Session::forget('insertrows');
			return redirect('user/vat_clients')->with('message', 'Sorry there is no datas to upload.');
		}
	}
	public function email_vatnotifications(){
		$admin_details = Db::table('admin')->first();
		$admin_cc = $admin_details->task_cc_email;

		$client_id = Input::get('client_id');
		$pemail = Input::get('pemail');
		$semail = Input::get('semail');

		$emails = Input::get('emails');
		$explode = explode(",",$emails);
		$exp_emails = array_values(array_filter($explode, 'strlen'));
		$email_sent_to =  implode(" , ",$exp_emails).' , '.$admin_cc;

		$salutation = Input::get('salutation');
		$self_manage = Input::get('self_manage');

		$period = Input::get('period');
		$due_date = Input::get('due_date');

		$admin_details = DB::table('admin')->where('id',1)->first();
		$from = $admin_details->email;

		$client_details = DB::table('vat_clients')->where('client_id',$client_id)->first();
		$cc = $admin_cc;
		if($pemail != "" && $semail != '')
		{
			$sentemails = $pemail.' , '.$semail.' , '.$cc;
		}
		elseif($pemail != "" && $semail == '')
		{
			$sentemails = $pemail.' , '.$cc;
		}
		elseif($pemail == "" && $semail != '')
		{
			$sentemails = $semail.' , '.$cc;
		}
		if($pemail != "")
		{
			$to = trim($pemail);
			
			$data['sentmails'] = $sentemails;
			$data['logo'] = URL::to('assets/images/logo.png');
			$data['salutation'] = $salutation;
			$data['self_manage'] = $self_manage;
			$data['period'] = $period;
			$data['due_date'] = $due_date;
			$data['signature'] = $admin_details->signature;
			$contentmessage = view('user/email_vat_notify', $data);

			$subject = 'GBS & Co: Vat Reminder for '.$client_details->name.'';
			require_once 'AttachMailer.php';
			$mailer = new AttachMailer($from, $to, $subject, $contentmessage);
			$mailer->send($cc) ? "envoye": "probleme envoi";

			$date = date('Y-m-d H:i:s');
			Db::table('vat_clients')->where('client_id',$client_id)->update(['last_email_sent' => $date]);
		}
		if($semail != "")
		{
			$to = trim($semail);
			$data['sentmails'] = $sentemails;
			$data['logo'] = URL::to('assets/images/logo.png');
			$data['salutation'] = $salutation;
			$data['self_manage'] = $self_manage;
			$data['period'] = $period;
			$data['due_date'] = $due_date;
			$data['signature'] = $admin_details->signature;
			$contentmessage = view('user/email_vat_notify', $data);

			$subject = 'GBS & Co: Vat Reminder for '.$client_details->name.'';
			require_once 'AttachMailer.php';
			$mailer = new AttachMailer($from, $to, $subject, $contentmessage);
			$mailer->send($cc) ? "envoye": "probleme envoi";

			$date = date('Y-m-d H:i:s');
			Db::table('vat_clients')->where('client_id',$client_id)->update(['last_email_sent' => $date]);
			
		}
		if($pemail != "" || $semail !="")
		{
			Db::table('vat_clients_email')->insert(['email_sent' => $date,'client_id' => $client_id]);
		}
	}
	public function email_sents()
	{
		$id = base64_decode(Input::get('id'));
		$emails_dates = DB::table('vat_clients_email')->where('client_id',$id)->get();
		$output = '';
		if(count($emails_dates))
		{
			$i = 1;
			foreach($emails_dates as $emails)
			{
				$output.='<tr>
				<td>'.$i.'</td>
				<td>'.date('d F Y', strtotime($emails->email_sent)).'</td>
				<td>'.date('H:i', strtotime($emails->email_sent)).'</td>
				</tr>';
				$i++;
			}
		}
		else{
			$output.='<tr><td colspan="3">No Data Found</td></tr>';
		}
		echo $output;
	}
	public function email_sents_save_pdf()
	{
		$id = base64_decode(Input::get('id'));
		$emails_dates = DB::table('vat_clients_email')->where('client_id',$id)->get();
		$client_details = DB::table('vat_clients')->where('client_id',$id)->first();
		$output = '<h4>Email Sent Date & Time For Client : '.$client_details->name.' and Taxnumber : '.$client_details->taxnumber.'</h4>
					<table style="width: 100%;border-collapse:collapse">
				        
				          <tr>
				              <td style="text-align: left;border:1px solid #000;">S.No</td>
				              <td style="text-align: left;border:1px solid #000;">Date</td>
				              <td style="text-align: left;border:1px solid #000;">Time</td>
				          </tr>
				        
				        ';
						if(count($emails_dates))
						{
							$i = 1;
							foreach($emails_dates as $emails)
							{
								$output.='<tr>
								<td style="text-align: left;border:1px solid #000;">'.$i.'</td>
								<td style="text-align: left;border:1px solid #000;">'.date('d F Y', strtotime($emails->email_sent)).'</td>
								<td style="text-align: left;border:1px solid #000;">'.date('H:i', strtotime($emails->email_sent)).'</td>
								</tr>';
								$i++;
							}
						}
						else{
							$output.='<tr><td colspan="3" style="text-align: center;border:1px solid #000;">No Data Found</td></tr>';
						}
		$output.='
		</table>';
		$pdf = PDF::loadHTML($output);
	    $pdf->save('papers/'.$client_details->name.'_'.$client_details->taxnumber.'.pdf');

	 	$filename = $client_details->name.'_'.$client_details->taxnumber.'.pdf';	
	 	echo $filename;
	}
	public function pdf_without_email(){
		$taxnumber_compared = Session::get('alreadyinsertedrows');
		$without_emailed = DB::table('vat_clients')->where('pemail','')->where('semail','')->where('status',0)->get();
		$logo = URL::to('assets/images/logo.png');
		$output = '<div style="width:100%;"><img src="'.$logo.'" style="width:178px; margin:0px auto;margin-left:38%"/></div><br/><br/><br/><br/>
						<h4>Clients Without Email Address: </h4><br/>
					<table style="width:100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: left;border:1px solid #000;">#</td>
				              <td style="text-align: left;border:1px solid #000;">Client Name</td>
				              <td style="text-align: left;border:1px solid #000;">Tax Regn./Trader No</td>
				          </tr>';
						$i=1;
                		if(count($without_emailed)){              
                  			foreach($without_emailed as $without){       
                  				if(in_array($without->taxnumber,$taxnumber_compared)) {  
            							$output.='<tr>
						                <td style="text-align: left;border:1px solid #000"><label>'.$i.'</label></td>
						                <td style="text-align: left; border:1px solid #000"><label>'.$without->name.'</label></td>
						                <td style="text-align: left; border:1px solid #000"><label>'.$without->taxnumber.'</label></td>
            							</tr>';
               							$i++;      
                				}                        
                			}              
              			}
			            if($i == 1)
			            {
			                $output.='<tr><td colspan="3" align="left">Empty</td></tr>';
			            }
						$output.='</table>';
			    $pdf = PDF::loadHTML($output);
			    $pdf->save('papers/Vat_Clients_Without_Email.pdf');
	   		 	echo 'Vat_Clients_Without_Email.pdf';
	}
	public function pdf_disabled(){
		$taxnumber_compared = Session::get('alreadyinsertedrows');
		$disabled = DB::table('vat_clients')->where('status',1)->get();
		$logo = URL::to('assets/images/logo.png');
		$output = '<div style="width:100%;"><img src="'.$logo.'" style="width:178px; margin:0px auto;margin-left:38%"/></div><br/><br/><br/><br/>
						<h4>Clients Without Email Address: </h4><br/>
					<table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: left;border:1px solid #000;">#</td>
				              <td style="text-align: left;border:1px solid #000;">Client Name</td>
				              <td style="text-align: left;border:1px solid #000;">Tax Regn./Trader No</td>
				          </tr>';
						$i=1;
                		if(count($disabled)){              
                  			foreach($disabled as $disable){       
                  				if(in_array($disable->taxnumber,$taxnumber_compared)) {  
            							$output.='<tr style="text-align:left">
						                <td style="text-align: left; border:1px solid #000"><label>'.$i.'</label></td>
						                <td style="text-align: left; border:1px solid #000"><label>'.$disable->name.'</label></td>
						                <td style="text-align: left; border:1px solid #000"><label>'.$disable->taxnumber.'</label></td>
            							</tr>';
               							$i++;      
                				}                        
                			}              
              			}
			            if($i == 1)
			            {
			                $output.='<tr><td colspan="3" align="left">Empty</td></tr>';
			            }
						$output.='</table>';
			    $pdf = PDF::loadHTML($output);
			    $pdf->save('papers/Vat_Clients_Disabled.pdf');
	   		 	echo 'Vat_Clients_Disabled.pdf';
	}
	public function pdf_with_email(){
		$taxnumber_compared = Session::get('alreadyinsertedrows');
		$with_emailed = DB::table('vat_clients')->where('status',0)->where('self_manage','!=','')->where('status',0)->whereRaw('(pemail != "" OR semail != "")')->get();
		$logo = URL::to('assets/images/logo.png');
		$output = '<div style="width:100%;"><img src="'.$logo.'" style="width:178px; margin:0px auto;margin-left:38%"/></div><br/><br/><br/><br/>
						<h4>Clients that we Can Notify : </h4><br/>
					<table style="width: 100%;border-collapse:collapse;">
				          <tr>
				                <td style="text-align: left; border:1px solid #000; font-size:12px;">#</td>
					            <td style="text-align: left; border:1px solid #000; word-wrap: break-word; font-size:12px;">Client Name</td>
					            <td style="text-align: left; border:1px solid #000; font-size:12px;">Tax Regn./Trader No</td>
					            <td style="text-align: left; border:1px solid #000; font-size:12px;">Email</td>
					            <td style="text-align: left; border:1px solid #000; font-size:12px;">Secondary Email</td> 
					            <td style="text-align: left; border:1px solid #000; font-size:12px;">Last Email Sent</td> 
				          </tr>';
						$i=1;
                		if(count($with_emailed)){              
                  			foreach($with_emailed as $with){       
                  				if(in_array($with->taxnumber,$taxnumber_compared)) {  
                  					if($with->last_email_sent == "0000-00-00 00:00:00")
                  					{
                  						$dd = '-';
                  					}
                  					else{
                  						$dd = date('d F Y', strtotime($with->last_email_sent));
                  					}
        							$output.='<tr style="text-align:left">
					                <td style="text-align: left; border:1px solid #000; font-size:12px;">'.$i.'</td>
					                <td style="width: 180px;  word-wrap: break-word;text-align: left; border:1px solid #000;font-size:12px;">'.$with->name.'</td>
					                <td style="text-align: left; border:1px solid #000; font-size:12px;">'.$with->taxnumber.'</td>
					                <td style="text-align: left; border:1px solid #000; font-size:12px;">'.$with->pemail.'</td>
					                <td style="text-align: left; border:1px solid #000; font-size:12px;">'.$with->semail.'</td>
					                <td style="width: 75px; text-align: left; border:1px solid #000; font-size:12px;">'.$dd.'</td>
        							</tr>';
           							$i++;      
                				}                        
                			}              
              			}
			            if($i == 1)
			            {
			                $output.='<tr><td colspan="6" align="left">Empty</td></tr>';
			            }
						$output.='</table>';
			    $pdf = PDF::loadHTML($output);
			    $pdf->save('papers/Vat_Clients_With_Email.pdf');
	   		 	echo 'Vat_Clients_With_Email.pdf';
	}
}

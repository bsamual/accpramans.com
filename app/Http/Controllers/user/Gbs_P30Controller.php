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
class Gbs_P30Controller extends Controller {

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
	public function gbs_p30()
	{
		$year = DB::table('year')->where('delete_status',0)->where('year_status', 0)->orderBy('year_name','dec')->get();
		$paye_p30_year = DB::table('paye_p30_year')->where('delete_status',0)->where('year_status', 0)->orderBy('year_name','dec')->get();
		return view('user/gbs_p30/month', array('title' => 'Select Year', 'yearlist' => $year, 'paye_p30_year' => $paye_p30_year));
	}
	public function gbs_p30monthmanage($id=""){
		$id = base64_decode($id);
		$year = DB::table('year')->where('year_id',$id)->first();
		$month = DB::table('p30_month')->where('year', $id)->orderBy('month_id','dec')->get();
		return view('user/gbs_p30/month_manage', array('monthlist' => $month,'year' => $year));
	}
	public function gbs_p30selectmonth($id="")
	{
		$id =base64_decode($id);
		$month_id = DB::table('p30_month')->where('month_id', $id)->first();
		$year_id = $this->year->getdetail($month_id->year);
		$user_year = $year_id->year_id;
		
		$month2 = $month_id->month_id;
		$year2 = $month_id->year;
		$result_task = DB::table('p30_task')->where('task_year', $year2)->where('task_month', $month2)->get();
		return view('user/gbs_p30/select_month', array('title' => 'gbs_P30 Month Task', 'yearname' => $year_id, 'monthid' => $month_id, 'resultlist' => $result_task));
	}
	public function review_month($id="")
	{
		$month_id = DB::table('p30_month')->where('month_id', $id)->first();
		$current_week = DB::table('week')->orderBy('week_id','desc')->first();
		$current_month = DB::table('month')->orderBy('month_id','desc')->first();
		$tasks = DB::table('task')->where('task_week',$current_week->week_id)->orWhere('task_month',$current_month->month_id)->groupBy('task_enumber')->get();
		if(count($tasks))
		{
			foreach($tasks as $task)
			{
				if($task->task_enumber != "")
				{
					$check_task = DB::table('p30_task')->where('task_id',$task->task_id)->where('task_month',$id)->count();
					$task_eno = DB::table('p30_task')->where('task_enumber',$task->task_enumber)->where('task_month',$id)->count();
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

						DB::table('p30_task')->insert($data); 
					}
				}
			}
		}
		return redirect('user/gbs_p30_select_month/'.base64_encode($id))->with('message', 'Reviewed Successfully.');
	}
	public function close_create_new_month($id="")
	{
		$year = DB::table('year')->where('year_id', $id)->first();

		$month_id = Db::table('p30_month')->where('year',$year->year_id)->orderBy('month_id','desc')->first();

		if($month_id->month == "12")
		{
			return redirect('user/gbs_p30_select_month/'.base64_encode($month_id->month_id))->with('message', 'Already 12 months has been created in this year. So Please contact admin to create new year.');
		}
		else
		{
			$next_month = $month_id->month + 1;
			$month['year'] = $month_id->year;
			$month['month'] = $next_month;
			$month['month_status'] = 0;

			$new_id = DB::table('p30_month')->insertGetId($month);
			DB::table('p30_month')->where('month_id',$month_id->month_id)->update(['month_closed' => date('Y-m-d H:i:s')]);
		}
		
		$current_week = DB::table('week')->orderBy('week_id','desc')->first();
		$current_month = DB::table('month')->orderBy('month_id','desc')->first();
		$tasks = DB::table('task')->where('task_week',$current_week->week_id)->orWhere('task_month',$current_month->month_id)->groupBy('task_enumber')->get();
		if(count($tasks))
		{
			foreach($tasks as $task)
			{
				if($task->task_enumber != "")
				{
						$data['task_id'] = $task->task_id;
						$data['task_year'] = $task->task_year;
						$data['task_month'] = $new_id;
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
						
						DB::table('p30_task')->insert($data); 
				}
			}
		}
		return redirect('user/gbs_p30_select_month/'.base64_encode($new_id))->with('message', 'Month created Successfully.');
	}
	public function gbs_p30tasklevelupdate($id=""){
		$id = Input::get('id');		
		$value = Input::get('value');
		
		DB::table('p30_task')->where('id', $id)->update(['task_level' => $value]);
	}
	public function gbs_p30periodupdate($id=""){
		$id = Input::get('id');		
		$value = Input::get('value');
		DB::table('p30_task')->where('id', $id)->update(['task_period' => $value]);
	}
	public function paygbs_p30()
	{
		$id = Input::get('id');
		$pay = Input::get('pay');
		DB::table('p30_task')->where('id',$id)->update(['pay' => $pay]);
	}
	public function nagbs_p30()
	{
		$id = Input::get('id');
		$pay = Input::get('na');
		DB::table('p30_task')->where('id',$id)->update(['na' => $pay]);
	}
	public function emailgbs_p30()
	{
		$id = Input::get('id');
		$email = Input::get('email');
		DB::table('p30_task')->where('id',$id)->update(['email' => $email]);
	}
	public function gbs_p30_task_image_upload()
	{
		$total = count($_FILES['image_file']['name']);
		$id = Input::get('hidden_id');
		for($i=0; $i<$total; $i++) {
		 	$filename = $_FILES['image_file']['name'][$i];
			$data_img = DB::table('p30_task')->where('id',$id)->first();
			
			$tmp_name = $_FILES['image_file']['tmp_name'][$i];
			$upload_dir = 'uploads/gbs_p30_task_image';
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
			$upload_dir = $upload_dir.'/'.base64_encode($data_img->id);
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
			move_uploaded_file($tmp_name, $upload_dir.'/'.$filename);	
			$data['task_id'] = $data_img->id;
			$data['attachment'] = $filename;
			$data['url'] = $upload_dir;
			DB::table('p30_task_attached')->insert($data);
		}
		return redirect('user/gbs_p30_select_month/'.base64_encode($data_img->task_month).'?divid=taskidtr_'.$id);
	}
	public function gbs_p30_task_automatic_image_upload()
	{
		$id = Input::get('hidden_id');
	 	$filename = $_FILES['image_file']['name'];
		$data_img = DB::table('p30_task')->where('id',$id)->first();
		
		$tmp_name = $_FILES['image_file']['tmp_name'];
		$upload_dir = 'uploads/gbs_p30_task_xml';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/'.base64_encode($data_img->id);
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		move_uploaded_file($tmp_name, $upload_dir.'/'.$filename);
		$data['task_id'] = $data_img->id;
		$data['attachment'] = $filename;
		$data['url'] = $upload_dir;
		DB::table('p30_task_xml')->insert($data);

		$xml=simplexml_load_file($upload_dir.'/'.$filename) or die("Error: Cannot create object");
		$xml_array = $xml->P30;
		
		$paye = floatval($xml_array[0]['PAYE']); 
		$prsi = floatval($xml_array[0]['PRSI']);
		$usc = floatval($xml_array[0]['USC']);
		$lpt = floatval($xml_array[0]['LPT']);

		$count = $paye+$prsi+$usc+$lpt;
		$liability = sprintf('%0.2f', $count);
		DB::table('p30_task')->where('id',$id)->update(["liability" => $liability]);
		return redirect('user/gbs_p30_select_month/'.base64_encode($data_img->task_month).'?divid=taskidtr_'.$id);
	}
	public function gbs_p30_task_delete_image()
	{
		$imgid = Input::get('imgid');
		DB::table('p30_task_attached')->where('id',$imgid)->delete();
	}
	public function gbs_p30_task_delete_xml()
	{
		$imgid = Input::get('imgid');
		DB::table('p30_task_xml')->where('id',$imgid)->delete();
	}
	public function task_liability_update()
	{
		$value = Input::get('liability');
		$explode = explode(".",$value);
		if(!isset($explode[1]))
		{
			$value = $explode[0].'.00';
		}
		$task_id = Input::get('task_id');
		DB::table('p30_task')->where('id',$task_id)->update(["liability" => $value]);
	}
	public function gbs_p30_edit_email_unsent_files()
	{
		$task_id = Input::get('task_id');
		$result = DB::table('p30_task')->where('id',$task_id)->first();
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

		$files = '';
		$date = date('d F Y', strtotime($result->last_email_sent));
		$time = date('H:i', strtotime($result->last_email_sent));
		$last_date = $date.' @ '.$time;

		$admin_details = Db::table('admin')->first();
		$admin_cc = $admin_details->p30_cc_email;

		$data['sentmails'] = $to_email.', '.$admin_cc;
		$data['logo'] = URL::to('assets/images/easy_payroll_logo.png');
		$task_details = DB::table('p30_task')->where('id',$task_id)->first();
		$period = DB::table('p30_period')->where('id',$task_details->task_period)->first();
		$data['salutation'] = $task_details->salutation;
		if($task_details->liability == "")
		{
			$liability_val = '0.00';
		}
		else{
			$liability_val = $task_details->liability;
		}
		$data['liability'] = number_format($liability_val,2, '.', ',');
		$data['pay'] = $task_details->pay;

		  $due_date = DB::table('p30_due_date')->first(); 
	      $month_details = Db::table('p30_month')->where('month_id',$task_details->task_month)->first();
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

            if($month_name == "January") { $next_year = $year_details->year_name + 1; 
				$data['due_date'] = $due_date->date.' '.$month_name.' '.$next_year;
			}
			else{
				$data['due_date'] = $due_date->date.' '.$month_name.' '.$year_details->year_name;
			}

			$data['period'] = $period->name;
		$contentmessage = view('user/gbs_p30_email_content', $data)->render();

		
	      $check_attached = DB::table('p30_task_attached')->where('task_id',$task_id)->where('status',0)->get();
	      if(count($check_attached))
	      {
	        foreach($check_attached as $attch)
	        {
	            $files.='<p><input type="checkbox" name="check_attachment[]" value="'.$attch->id.'" id="label_'.$attch->id.'" class="check_attachment_cls" checked><label for="label_'.$attch->id.'">'.$attch->attachment.'</label></p>';
	        }
	      }
	      
	      
      	$month_details = DB::table('p30_month')->where('month_id',$result->task_month)->first();
      	$subject = 'Easypayroll.ie : '.$result->task_name.' P30 Submission';

	     echo json_encode(["files" => $files,"html" => $contentmessage,"from" => $from, "to" => $to_email,'subject' => $subject,'last_email_sent' => $last_date]);
	}
	public function gbs_p30_email_unsent_files()
	{
		$task_id = Input::get('hidden_email_task_id');
		$det_task = DB::table('p30_task')->where('id',$task_id)->first();
		$encoded_month_id = base64_encode($det_task->task_month);

		$from = Input::get('from_user');
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
					if(count($attachments))
					{
						$det = DB::table('p30_task_attached')->where('id',$attachments[0])->first();
					}
					
					$task_details = DB::table('p30_task')->where('id',$det->task_id)->first();
					$data['salutation'] = $task_details->salutation;
					$data['liability'] = $task_details->liability;
					$data['pay'] = $task_details->pay;

					  $due_date = DB::table('p30_due_date')->first(); 
	                  $month_details = Db::table('p30_month')->where('month_id',$task_details->task_month)->first();
	                  $year_details = DB::table('year')->where('year_id',$month_details->year)->first();
	                  if($month_details->month == 1) { $month_name = "January"; }
	                  if($month_details->month == 2) { $month_name = "February"; }
	                  if($month_details->month == 3) { $month_name = "March"; }
	                  if($month_details->month == 4) { $month_name = "April"; }
	                  if($month_details->month == 5) { $month_name = "May"; }
	                  if($month_details->month == 6) { $month_name = "June"; }
	                  if($month_details->month == 7) { $month_name = "July"; }
	                  if($month_details->month == 8) { $month_name = "August"; }
	                  if($month_details->month == 9) { $month_name = "September"; }
	                  if($month_details->month == 10) { $month_name = "October"; }
	                  if($month_details->month == 11) { $month_name = "November"; }
	                  if($month_details->month == 12) { $month_name = "December"; }

					$data['due_date'] = $due_date->date.' '.$month_name.' '.$year_details->year_name;

					$contentmessage = view('user/gbs_p30_email_share_paper', $data)->render();
					
					$email = new PHPMailer();
					$email->SetFrom($from); //Name is optional
					$email->Subject   = $subject;
					$email->Body      = $contentmessage;
					$email->IsHTML(true);
					$email->AddAddress( $to );

					if(count($attachments))
					{
						foreach($attachments as $attachment)
						{
							$attachment_details = DB::table('p30_task_attached')->where('id',$attachment)->first();
							$path = $attachment_details->url.'/'.$attachment_details->attachment;
							$email->AddAttachment( $path , $attachment_details->attachment );
							DB::table('p30_task_attached')->where('id',$attachment)->update(['status' => 1]);
							$task_id = $attachment_details->task_id;
						}
					}
					$email->Send();	
				}
				$date = date('Y-m-d H:i:s');
				DB::table('p30_task')->where('id',$task_id)->update(['last_email_sent' => $date]);
				return redirect('user/gbs_p30_select_month/'.$encoded_month_id.'?divid=taskidtr_'.$task_id)->with('message', 'Email Sent Successfully');
			}
			else{
				return redirect('user/gbs_p30_select_month/'.$encoded_month_id.'?divid=taskidtr_'.$task_id)->with('error', 'Email Field is empty so email is not sent');
			}
		}
		else{
			return redirect('user/gbs_p30_select_month/'.$encoded_month_id.'?divid=taskidtr_'.$task_id)->with('error', 'Attachments are empty so Email is not sent');
		}
	}
	public function gbs_p30_task_status_update()
	{
		$id = Input::get('id');
		$status = Input::get('status');
		DB::table('p30_task')->where('id',$id)->update(['task_status' => $status,'updatetime' => date('Y-m-d H:i:s')]);
		$details = DB::table('task')->where('task_id',$id)->first();

      $seperatedate = explode(' ',$details->updatetime);
      $explodedate = explode('-',$seperatedate[0]);
      $explodetime = explode(':',$seperatedate[1]);
      $date = $explodedate[1].'-'.$explodedate[2].'-'.$explodedate[0];
      $time = $explodetime[0].':'.$explodetime[1];

      echo json_encode(["date" => $date,"time" => $time]);
	}
	public function gbs_p30_report_task($id=""){
		$id = Input::get('month_id');
		$month = DB::table('p30_month')->where('month_id', $id)->first();
		$tasks = DB::table('p30_task')->where('task_month',$month->month_id)->get();
		$output = '';
		if(count($tasks))
		{
			foreach($tasks as $task)
			{
				$task_level = DB::table('p30_tasklevel')->where('id',$task->task_level)->first();
				if(count($task_level))
				{
					$task_level_val = $task_level->name;
				}	
				else{
					$task_level_val = '';
				}
				$period = DB::table('p30_period')->where('id',$task->task_period)->first();
				if(count($period))
				{
					$period_val = $period->name;
				}	
				else{
					$period_val = '';
				}
				if($task->pay == 1) { $pay = 'Yes';} else { $pay = 'No'; }

				$output.='<tr>
				<td style="text-align:center"><input type="checkbox" class="report_task" id="report_'.$task->id.'" data-element="'.$task->id.'"><label for="report_'.$task->id.'">&nbsp</label></td>
				<td>'.$task->task_name.'</td>
				<td>'.$task_level_val.'</td>
				<td>'.$pay.'</td>
				<td>'.$period_val.'</td>
				<td>'.$task->task_email.'</td>
				<td>'.$task->liability.'</td>
				</tr>';
			}
		}
		echo $output;
	}
	public function download_gbs_p30_pdf_report()
	{
		$ids = explode(",",Input::get('task_id'));
		$tasks = DB::table('p30_task')->whereIn('id', $ids)->get();
		$output = '<table style="width:100%; border-collapse:collapse; border:1px solid #000">
			<tr>
				<td style="border:1px solid #000; text-align:left">S.No</td>
				<td style="border:1px solid #000; text-align:left">Task Name</td>
				<td style="border:1px solid #000; text-align:left">Task Level</td>
				<td style="border:1px solid #000; text-align:left">Pay</td>
				<td style="border:1px solid #000; text-align:left">Period</td>
				<td style="border:1px solid #000; text-align:left">EMail</td>
				<td style="border:1px solid #000; text-align:left">Liability</td>
			</tr>';
			$i = 1;
		if(count($tasks))
		{
			foreach($tasks as $task)
			{
				$task_level = DB::table('p30_tasklevel')->where('id',$task->task_level)->first();
				if(count($task_level))
				{
					$task_level_val = $task_level->name;
				}	
				else{
					$task_level_val = '';
				}
				$period = DB::table('p30_period')->where('id',$task->task_period)->first();
				if(count($period))
				{
					$period_val = $period->name;
				}	
				else{
					$period_val = '';
				}
				if($task->pay == 1) { $pay = 'Yes';} else { $pay = 'No'; }

				$output.='<tr>
				<td style="border:1px solid #000; text-align:left">'.$i.'</td>
				<td style="border:1px solid #000; text-align:left">'.$task->task_name.'</td>
				<td style="border:1px solid #000; text-align:left">'.$task_level_val.'</td>
				<td style="border:1px solid #000; text-align:left">'.$pay.'</td>
				<td style="border:1px solid #000; text-align:left">'.$period_val.'</td>
				<td style="border:1px solid #000; text-align:left">'.$task->task_email.'</td>
				<td style="border:1px solid #000; text-align:left">'.$task->liability.'</td>
				</tr>';
				$i++;
			}
		}
		else{
			$output.='<tr><td colspan="7">No Task Found</td></tr>';
		}
		$year = DB::table('year')->where('year_id',$tasks[0]->task_year)->first();
		$month = DB::table('p30_month')->where('month_id',$tasks[0]->task_month)->first();
		$name = 'P30 Report for the year '.$year->year_name.' - Month '.$month->month.'.pdf';
		$pdf = PDF::loadHTML($output);
		$pdf->setPaper('A4', 'landscape');
		$pdf->save('papers/'.$name.'');
		echo $name;
	}
	public function import_gbs_p30_review_due()
	{
		$monthid = Input::get('hidden_month_id');
		if($_FILES['import_csv']['name']!='')
		{
			$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';
			$tmp_name = $_FILES['import_csv']['tmp_name'];
			$name=$_FILES['import_csv']['name'];
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
					
					$height = $highestRow;
					
					$insertrows = array();
					$ernumber_label = $worksheet->getCellByColumnAndRow(4, 1); $ernumber_label = trim($ernumber_label->getValue());
					if($ernumber_label != "Tax Regn./Trader No.")
					{
						return redirect('user/gbs_p30_select_month/'.base64_encode($monthid))->with('error_import', 'You have tried to upload a wrong csv file.');
					}
					else{
						DB::table('p30_task')->where('task_month',$monthid)->update(['na' => 1]);
						for ($row = 2; $row <= $height; ++ $row) {
							$ernumber = $worksheet->getCellByColumnAndRow(4, $row); $ernumber = trim($ernumber->getValue());
							$task = DB::table('p30_task')->where('task_month',$monthid)->where('task_enumber',$ernumber)->first();
							if(count($task))
							{
								DB::table('p30_task')->where('id',$task->id)->update(['na' => 2]);
								$sessn=array('p30_session_task_id' => $task->id);
							}
						}
					}
				}
				Session::put($sessn);
				return redirect('user/gbs_p30_select_month/'.base64_encode($monthid))->with('message_import', 'Reviewed successfully.');
			}
		}
	}
	public function download_gbs_p30_review()
	{
		$type = Input::get('type');
		$month_id = Input::get('monthid');

		if($type == 1)
		{
			$tasks = DB::table('p30_task')->where('task_month', $month_id)->where('na',1)->get();
		}
		if($type == 2)
		{
			$tasks = DB::table('p30_task')->where('task_month', $month_id)->where('na',1)->get();
		}
		if($type == 3)
		{
			$tasks = DB::table('p30_task')->where('task_month', $month_id)->where('na',2)->get();
		}

		$output = '<table style="width:100%; border-collapse:collapse; border:1px solid #000">
			<tr>
				<td style="border:1px solid #000; text-align:left">S.No</td>
				<td style="border:1px solid #000; text-align:left">Task Name</td>
				<td style="border:1px solid #000; text-align:left">Task Level</td>
				<td style="border:1px solid #000; text-align:left">Pay</td>
				<td style="border:1px solid #000; text-align:left">Period</td>
				<td style="border:1px solid #000; text-align:left">EMail</td>
				<td style="border:1px solid #000; text-align:left">Liability</td>
			</tr>';
			$i = 1;
		if(count($tasks))
		{
			foreach($tasks as $task)
			{
				$task_level = DB::table('p30_tasklevel')->where('id',$task->task_level)->first();
				if(count($task_level))
				{
					$task_level_val = $task_level->name;
				}	
				else{
					$task_level_val = '';
				}
				$period = DB::table('p30_period')->where('id',$task->task_period)->first();
				if(count($period))
				{
					$period_val = $period->name;
				}	
				else{
					$period_val = '';
				}
				if($task->pay == 1) { $pay = 'Yes';} else { $pay = 'No'; }

				$output.='<tr>
				<td style="border:1px solid #000; text-align:left">'.$i.'</td>
				<td style="border:1px solid #000; text-align:left">'.$task->task_name.'</td>
				<td style="border:1px solid #000; text-align:left">'.$task_level_val.'</td>
				<td style="border:1px solid #000; text-align:left">'.$pay.'</td>
				<td style="border:1px solid #000; text-align:left">'.$period_val.'</td>
				<td style="border:1px solid #000; text-align:left">'.$task->task_email.'</td>
				<td style="border:1px solid #000; text-align:left">'.$task->liability.'</td>
				</tr>';
				$i++;
			}
		}
		else{
			$output.='<tr><td colspan="7">No Task Found</td></tr>';
		}
		$year = DB::table('year')->where('year_id',$tasks[0]->task_year)->first();
		$month = DB::table('p30_month')->where('month_id',$tasks[0]->task_month)->first();

		$name = 'P30 Review Due for the year '.$year->year_name.' - Month '.$month->month.'.pdf';
		$pdf = PDF::loadHTML($output);
		$pdf->setPaper('A4', 'landscape');
		$pdf->save('papers/'.$name.'');
		echo $name;
	}
	public function update_gbs_p30_incomplete_status_month()
	{
		$data['p30_incomplete'] = Input::get('value');
		DB::table('user_login')->where('userid',1)->update($data);
	}
}

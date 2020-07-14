<?php namespace App\Http\Controllers\user;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\Task_Job;
use App\Job_Break_Time;
use App\User;
use Session;
use URL;
use PDF;
use Response;
use PHPExcel; 
use PHPExcel_IOFactory;
use PHPExcel_Cell;
class TimejobController extends Controller {
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
	public function __construct(task_job $task_job, job_break_time  $job_break_time, user $user)
	{
		$this->middleware('userauth');		
		$this->task_job = $task_job;
		$this->job_break_time = $job_break_time;
		$this->user = $user;
		date_default_timezone_set("Europe/Dublin");
		//date_default_timezone_set("Asia/Calcutta");
	}
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */

	public function timesystem_client_search()

	{

		$value = Input::get('term');
		$details = DB::table('cm_clients')->Where('client_id','like','%'.$value.'%')->orWhere('company','like','%'.$value.'%')->Where('status', 0)->get();

		$data=array();

		foreach ($details as $single) {
				if($single->company != "")
				{
					$company = $single->company;
				}
				else{
					$company = $single->firstname.' & '.$single->surname;
				}
                $data[]=array('value'=>$company.'-'.$single->client_id,'id'=>$single->client_id,'active_status'=>$single->active);

        }

         if(count($data))
             return $data;
        else
            return ['value'=>'No Result Found','id'=>''];
	}
	
	public function timesystem_clientsearchselect(){

		$id = Input::get('value');


		$tasks = DB::table('time_task')->where('clients','like','%'.$id.'%')->orderBy('task_name', 'asc')->get();
		
		$output = '';
		if(count($tasks)){
			foreach ($tasks as $single_task) {
				if($single_task->task_type == 0){
					$icon = '<i class="fa fa-desktop" style="margin-right:10px;"></i>';
				}
				else if($single_task->task_type == 1){
					$icon = '<i class="fa fa-users" style="margin-right:10px;"></i>';
				}
				else{
					$icon = '<i class="fa fa-globe" style="margin-right:10px;"></i>';
				}

				$output.= '<li><a tabindex="-1" href="javascript:" class="tasks_li" data-element="'.$single_task->id.'">'.$icon.$single_task->task_name.'</a></li>';
			}
		}
		else{
			$output.= '<li><a tabindex="-1" href="javascript:">Empty</a></li>';
		}
		
		echo $output;
	}

	public function timesystem_client_search_select_tasks(){

		$id = Input::get('value');


		$tasks = DB::table('time_task')->where('clients','like','%'.$id.'%')->get();
		
		$output = '';
		if(count($tasks)){
			foreach ($tasks as $single_task) {
				

				$output.= '<option value="'.$single_task->id.'">'.$single_task->task_name.'</option>';
			}
		}
		else{
			$output.= '<option value="'.$single_task->id.'">'.$icon.$single_task->task_name.'</option>';
		}
		
		echo $output;
	}

	public function timejobadd_old(){

		$type = Input::get("internal_type");
		$quick_job_stop = Input::get("quick_job");
		$dateverify = date('Y-m-d', strtotime(Input::get("date")));
		//$dateverify = $date[2].'-'.$date[0].'-'.$date[1];

		
		$jobdetails = DB::table('task_job')->where('user_id', Input::get("user_id"))->where('job_date', $dateverify)->where('quick_job',0)->where('status',0)->first();

		if($quick_job_stop == 0)
		{
			if(count($jobdetails))
			{
				return Redirect::back()->with('error-message', 'Active job have that date. If you want create active job, stop old active job');
			}
		}
		else{
			if(count($jobdetails))
			{
				$setstoptime['stop_time'] = date('H:i:s', strtotime(Input::get("starttime")));
				$setstoptime['status'] = 1;

				$stoptime = date('H:i:s', strtotime(Input::get("starttime")));
				$created_date = $jobdetails->job_created_date;
				$jobstart = strtotime($created_date.' '.$jobdetails->start_time);
		        $jobstop   = strtotime($created_date.' '.$stoptime);
		        if($jobstop < $jobstart)
		        {
		        	 $todate = date('Y-m-d', strtotime("+1 day", $jobstop));
		             $jobstop   = strtotime($todate.' '.$stoptime);
		        }
		        $jobdiff  = $jobstop - $jobstart;
				//-----------Job Time Start----------------

		        $hours = floor($jobdiff / (60 * 60));
		        $minutes = $jobdiff - $hours * (60 * 60);
		        $minutes = floor( $minutes / 60 );
		        $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
		        if($hours <= 9)
		        {
		          $hours = '0'.$hours;
		        }
		        else{
		          $hours = $hours;
		        }
		        if($minutes <= 9)
		        {
		          $minutes = '0'.$minutes;
		        }
		        else{
		          $minutes = $minutes;
		        }
		        if($second <= 9)
		        {
		          $second = '0'.$second;
		        }
		        else{
		          $second = $second;
		        }
		        $jobtime =   $hours.':'.$minutes.':'.$second;

		        //-----------Job Time End----------------

		        $setstoptime['job_time'] = $jobtime;

				DB::table('task_job')->where('id',$jobdetails->id)->update($setstoptime);

				$setduplicate_active['job_type'] = $jobdetails->job_type;
				$setduplicate_active['user_id'] = $jobdetails->user_id;
				$setduplicate_active['task_id'] = $jobdetails->task_id;
				$setduplicate_active['start_time'] =  date('H:i:s', strtotime(Input::get("stoptime")));
				$setduplicate_active['quick_job'] = $jobdetails->quick_job;
				$setduplicate_active['job_created_date'] = $jobdetails->job_created_date;
				$setduplicate_active['job_date'] = $jobdetails->job_date;
				$setduplicate_active['client_id'] = $jobdetails->client_id;

				DB::table('task_job')->insert($setduplicate_active);
			}
		}
		

		$data['job_type'] = Input::get("internal_type");
		$data['user_id'] = Input::get("user_id");
		$data['task_id'] = Input::get("task_id");						
		$data['start_time'] = date('H:i:s', strtotime(Input::get("starttime")));
		$data['quick_job'] = Input::get("quick_job");
		$data['job_created_date'] = date('Y-m-d');
		$data['job_date'] = $dateverify;

		if(Input::get('quick_job') == 1)
		{
			$starttime = date('H:i:s', strtotime(Input::get("starttime")));
			$stoptime = date('H:i:s', strtotime(Input::get("stoptime")));

			$created_date = $data['job_date'];
			$jobstart = strtotime($created_date.' '.$starttime);
	        $jobstop   = strtotime($created_date.' '.$stoptime);
	        if($jobstop < $jobstart)
	        {
	        	 $todate = date('Y-m-d', strtotime("+1 day", $jobstop));
	             $jobstop   = strtotime($todate.' '.$stoptime);
	        }
	        $jobdiff  = $jobstop - $jobstart;
			//-----------Job Time Start----------------

	        $hours = floor($jobdiff / (60 * 60));
	        $minutes = $jobdiff - $hours * (60 * 60);
	        $minutes = floor( $minutes / 60 );
	        $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
	        if($hours <= 9)
	        {
	          $hours = '0'.$hours;
	        }
	        else{
	          $hours = $hours;
	        }
	        if($minutes <= 9)
	        {
	          $minutes = '0'.$minutes;
	        }
	        else{
	          $minutes = $minutes;
	        }
	        if($second <= 9)
	        {
	          $second = '0'.$second;
	        }
	        else{
	          $second = $second;
	        }
	        $jobtime =   $hours.':'.$minutes.':'.$second;

	        //-----------Job Time End----------------

	        $data['job_time'] = $jobtime;
		}


		if($quick_job_stop == 1){
			$data['stop_time'] = date('H:i:s', strtotime(Input::get("stoptime")));
		}
		else{
			$data['stop_time'] = '';			
		}

		if($type == 0){
			$data['client_id'] = '';
		}
		else{
			$data['client_id'] = Input::get("clientid");
		}



		
		DB::table('task_job')->insert($data);
		return Redirect::back()->with('message', 'Job added Successfully');
	}


	public function timejobadd(){
		$add_edit_job = Input::get("add_edit_job");


		$type = Input::get("internal_type");
		$quick_job_stop = Input::get("quick_job");
		$dateverify = date('Y-m-d', strtotime(Input::get("date")));

		if($type == 0){
			$data['client_id'] = '';
		}
		else{
			$data['client_id'] = Input::get("clientid");
		}

		if($quick_job_stop == 0)
		{
			$userid = Input::get('userid');
			$check_db = DB::table('task_job')->where('user_id',Input::get("user_id"))->where('quick_job',0)->where('status',0)->count();
			if($add_edit_job == 0 && $check_db > 0){
				return Redirect::back()->with('message', 'You can only have 1 active job! You must stop the current active job before you create a New Active Job');
				exit;
			}

			$data['job_type'] = Input::get("internal_type");
			$data['user_id'] = Input::get("user_id");
			$data['task_id'] = Input::get("task_id");						
			$data['start_time'] = date('H:i:s', strtotime(Input::get("starttime")));
			$data['quick_job'] = Input::get("quick_job");
			$data['job_created_date'] = date('Y-m-d');
			$data['job_date'] = $dateverify;
		}
		else
		{
			$active_id = Input::get('acive_id');
			if($add_edit_job == 0){
				DB::table('task_job')->where('id', $active_id)->update(['color' => 0]);
				DB::table('task_job')->where('active_id', $active_id)->update(['color' => 0]);
			}
			$data['job_type'] = Input::get("internal_type");
			$data['user_id'] = Input::get("user_id");
			$data['task_id'] = Input::get("task_id");						
			$data['start_time'] = date('H:i:s', strtotime(Input::get("starttime")));
			$data['quick_job'] = Input::get("quick_job");
			$data['job_created_date'] = date('Y-m-d');
			$data['job_date'] = $dateverify;
			$data['active_id'] = $active_id;	

		}
		
		if($add_edit_job == 0){
			$data['color'] = 1;
			$task_new_id = DB::table('task_job')->insertGetid($data);
			//$task_new_id = mysql_insert_id();
			// if($type !=  0)
			// {
			// 	$client_id = Input::get("clientid");
			// 	$get_invoice = DB::table('ta_auto_allocation')->where('auto_client_id',$client_id)->first();
			// 	$get_client_invoice = DB::table('ta_client_invoice')->where('client_id',$client_id)->first();
			// 	if(count($get_invoice))
			// 	{
			// 		$unserialize = unserialize($get_invoice->auto_tasks);
			// 		$inv_no = '';
			// 		if(count($unserialize))
			// 		{
			// 			foreach($unserialize as $key => $arrayval)
			// 			{
			// 				if(in_array(Input::get("task_id"), $arrayval))
			// 				{
			// 					$inv_no = $key;
			// 				}
			// 			}
			// 		}
			// 		if($inv_no != "")
			// 		{	
			// 			if(count($get_client_invoice))
			// 			{
			// 				$unserialize_tasks = unserialize($get_client_invoice->tasks);
			// 				$get_invoice_tasks = $unserialize_tasks[$inv_no];
			// 				array_push($get_invoice_tasks,$task_new_id);

			// 				$unserialize_tasks[$inv_no] = $get_invoice_tasks;
			// 				$serialize_tasks = serialize($unserialize_tasks);
			// 				$datatask['tasks'] = $serialize_tasks;
			// 				DB::table('ta_client_invoice')->where('client_id',$client_id)->update($datatask);
			// 			}
			// 			else{
			// 				$unserialize_tasks[$inv_no] = array($task_new_id);
			// 				$serialize_tasks = serialize($unserialize_tasks);
			// 				$datatask['tasks'] = $serialize_tasks;
			// 				$datatask['client_id'] = $client_id;
			// 				$datatask['invoice'] = $inv_no;
			// 				DB::table('ta_client_invoice')->where('client_id',$client_id)->insert($datatask);
			// 			}
			// 		}
			// 	}
			// }
			return Redirect::back()->with('message', 'Job added Successfully');
		}
		else{
			$job_id = Input::get('taskjob_id');
			$jobs = DB::table('task_job')->where('id',$job_id)->first();
			if($jobs->quick_job == 1 && $jobs->stop_time != '00:00:00'){
				$stoptime = $jobs->stop_time;
				$created_date = $jobs->job_created_date;
				$starttime = date('H:i:s', strtotime(Input::get("starttime")));
				
				$jobstart = strtotime($created_date.' '.$starttime);
		        $jobstop   = strtotime($created_date.' '.$stoptime);

		        if($jobstop < $jobstart)
		        {
		        	 $todate = date('Y-m-d', strtotime("+1 day", $jobstop));
		             $jobstop   = strtotime($todate.' '.$stoptime);
		        }

		        $jobdiff  = $jobstop - $jobstart;


				//-----------Job Time Start----------------

		        $hours = floor($jobdiff / (60 * 60));
		        $minutes = $jobdiff - $hours * (60 * 60);
		        $minutes = floor( $minutes / 60 );
		        $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
		        if($hours <= 9)
		        {
		          $hours = '0'.$hours;
		        }
		        else{
		          $hours = $hours;
		        }
		        if($minutes <= 9)
		        {
		          $minutes = '0'.$minutes;
		        }
		        else{
		          $minutes = $minutes;
		        }
		        if($second <= 9)
		        {
		          $second = '0'.$second;
		        }
		        else{
		          $second = $second;
		        }
		        $jobtime =   $hours.':'.$minutes.':'.$second;

		        //-----------Job Time End----------------

		        $data['job_time'] = $jobtime;
		    }

			DB::table('task_job')->where('id',$job_id)->update($data);
			return Redirect::back()->with('message', 'Job updated Successfully');
		}


		

	}


	public function time_job_edit(){
		$jobid = Input::get("hidden_job_id");
		$type = Input::get("internal_type");
		$quick_job_stop = Input::get("quick_job");
		$dateverify = date('Y-m-d', strtotime(Input::get("date")));


		$data['job_type'] = Input::get("internal_type");
		$data['user_id'] = Input::get("user_id");
		$data['task_id'] = Input::get("task_id");						
		$data['start_time'] = date('H:i:s', strtotime(Input::get("starttime")));
		$data['quick_job'] = Input::get("quick_job");
		$data['job_created_date'] = date('Y-m-d');
		$data['job_date'] = date('Y-m-d', strtotime(Input::get("date")));
		$data['stop_time'] = date('H:i:s', strtotime(Input::get("stoptime")));
		

		if($type == 0){
			$data['client_id'] = '';
		}
		else{
			$data['client_id'] = Input::get("clientid");
		}
		
		
		$stoptime = date('H:i:s', strtotime(Input::get("stoptime")));
		$jobs = DB::table('task_job')->where('id', $jobid)->first();
		$created_date = $jobs->job_created_date;
		
		$jobstart = strtotime($created_date.' '.$data['start_time']);
        $jobstop   = strtotime($created_date.' '.$stoptime);


        if($jobstop < $jobstart)
        {
        	 $todate = date('Y-m-d', strtotime("+1 day", $jobstop));
             $jobstop   = strtotime($todate.' '.$stoptime);
        }

        $jobdiff  = $jobstop - $jobstart;


		//-----------Job Time Start----------------

        $hours = floor($jobdiff / (60 * 60));
        $minutes = $jobdiff - $hours * (60 * 60);
        $minutes = floor( $minutes / 60 );
        $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
        if($hours <= 9)
        {
          $hours = '0'.$hours;
        }
        else{
          $hours = $hours;
        }
        if($minutes <= 9)
        {
          $minutes = '0'.$minutes;
        }
        else{
          $minutes = $minutes;
        }
        if($second <= 9)
        {
          $second = '0'.$second;
        }
        else{
          $second = $second;
        }
        $jobtime =   $hours.':'.$minutes.':'.$second;
        //-----------Job Time End----------------
        
        	$data['job_time'] = $jobtime;
        
		DB::table('task_job')->where('id',$jobid)->update($data);

		if($data['job_type'] !=  0)
		{
			$client_id = $data['client_id'];
			$get_invoice = DB::table('ta_auto_allocation')->where('auto_client_id',$client_id)->first();
			$get_client_invoice = DB::table('ta_client_invoice')->where('client_id',$client_id)->first();
			if(count($get_invoice))
			{
				$unserialize = unserialize($get_invoice->auto_tasks);
				$inv_no = '';
				if(count($unserialize))
				{
					foreach($unserialize as $key => $arrayval)
					{
						if(in_array($data['task_id'], $arrayval))
						{
							$inv_no = $key;
						}
					}
				}
				if($inv_no != "")
				{	
					if(count($get_client_invoice))
					{
						$unserialize_tasks = unserialize($get_client_invoice->tasks);
						$get_invoice_tasks = $unserialize_tasks[$inv_no];
						if(!in_array($jobid,$get_invoice_tasks))
						{
							array_push($get_invoice_tasks,$jobid);
							$unserialize_tasks[$inv_no] = $get_invoice_tasks;
							$serialize_tasks = serialize($unserialize_tasks);
							$datatask['tasks'] = $serialize_tasks;
							DB::table('ta_client_invoice')->where('client_id',$client_id)->update($datatask);
						}
					}
					else{
						$unserialize_tasks[$inv_no] = array($jobid);
						$serialize_tasks = serialize($unserialize_tasks);
						$datatask['tasks'] = $serialize_tasks;
						$datatask['client_id'] = $client_id;
						$datatask['invoice'] = $inv_no;
						DB::table('ta_client_invoice')->where('client_id',$client_id)->insert($datatask);
					}
				}
			}
		}

		
		$searchdate = Input::get('hidden_search_date');
		return redirect('user/time_me_joboftheday?date_search='.$searchdate)->with('message', 'Job Updated Successfully');
	}

	public function stop_job_details(){
		$id = Input::get('jobid');
		
		$job_details = DB::table('task_job')->where('id', $id)->first();
		$job_details_child = DB::table('task_job')->where('active_id', $job_details->id)->get();
		if(count($job_details_child))
		{
			$job_times = 0;
			foreach($job_details_child as $child)
			{
				$time = explode(':', $child->job_time);
				$minutes = ($time[0]*60) + ($time[1]) + ($time[2]/60);
				if($job_times == 0)
				{
					$job_times = $minutes;
				}
				else{
					$job_times = $job_times + $minutes;
				}
			}
			if($job_times > 0)
			{
			  if(floor($job_times / 60) <= 9)
	          {
	            $h = '0'.floor($job_times / 60);
	          }
	          else{
	            $h = floor($job_times / 60);
	          }
	          if(($job_times -   floor($job_times / 60) * 60) <= 9)
	          {
	            $m = '0'.($job_times -   floor($job_times / 60) * 60);
	          }
	          else{
	            $m = ($job_times -   floor($job_times / 60) * 60);
	          }
	          $quick_job_times = $h.':'.$m.':00';
	      	}
	      	else{
	      		$quick_job_times = '00:00:00';
	      	}
		}
		else{
			$quick_job_times = '00:00:00';
		}

		$explode = explode(":",$job_details->start_time);
		$hour = $explode[0];
		$min = $explode[1];


		$curr_date = date('Y-m-d');
		$jobstart = strtotime($curr_date.' '.date('H:i', strtotime($job_details->start_time)).':00');
		$jobend = strtotime($curr_date.' '.date('H:i').':00');
		if($jobend < $jobstart)
		{
			$stop_time = date('H:i', strtotime($job_details->start_time));
			$jobend = strtotime($curr_date.' '.$stop_time.':00');
		}
		else{
			$stop_time = date('H:i');
		}

		$jobdiff  = $jobend - $jobstart;

        $hours = floor($jobdiff / (60 * 60));
        $minutes = $jobdiff - $hours * (60 * 60);
        $minutes = floor( $minutes / 60 );
        $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
        if($hours <= 9)
        {
          $hours = '0'.$hours;
        }
        else{
          $hours = $hours;
        }
        if($minutes <= 9)
        {
          $minutes = '0'.$minutes;
        }
        else{
          $minutes = $minutes;
        }
        if($second <= 9)
        {
          $second = '0'.$second;
        }
        else{
          $second = $second;
        }

        $jobtime =   $hours.':'.$minutes.':'.$second;

        $total_quick_jobs = $quick_job_times;
        

        if($total_quick_jobs == "" || $total_quick_jobs == "00:00:00")
        {
        	$total_quick_jobs_minutes = 0;
        }
        else{
        	$total_quick_jobs_1 = explode(':', $total_quick_jobs);
			$minutes = ($total_quick_jobs_1[0]*60) + ($total_quick_jobs_1[1]) + ($total_quick_jobs_1[2]/60);
			$total_quick_jobs_minutes = $minutes;
        }
		
		
        $total_breaks_minutes = 0;
        

		$sum_of_breaks = $total_breaks_minutes + $total_quick_jobs_minutes;

		$jobtime_1 = explode(':', $jobtime);
		$job_minutes = ($jobtime_1[0]*60) + ($jobtime_1[1]) + ($jobtime_1[2]/60);
		$jobtime_minutes = $job_minutes;

		$total_time_minutes = $jobtime_minutes - $sum_of_breaks;

		if($total_time_minutes < 0)
		{
			if(floor($total_time_minutes / 60) <= 9)
	          {
	            $h = floor($total_time_minutes / 60);
	            $h = str_replace("-","",$h);
	          }
	          else{
	            $h = floor($total_time_minutes / 60);
	            $h = str_replace("-","",$h);
	          }
	          if(($total_time_minutes -   floor($total_time_minutes / 60) * 60) <= 9)
	          {
	            $m = '0'.($total_time_minutes -   floor($total_time_minutes / 60) * 60);
	          }
	          else{
	            $m = ($total_time_minutes -   floor($total_time_minutes / 60) * 60);
	          }
	          $total_time_minutes_format = '-'.$h.':'.$m.':00';
	          $alert =1;
		}
		else{
			if(floor($total_time_minutes / 60) <= 9)
	          {
	            $h = '0'.floor($total_time_minutes / 60);
	          }
	          else{
	            $h = floor($total_time_minutes / 60);
	          }
	          if(($total_time_minutes -   floor($total_time_minutes / 60) * 60) <= 9)
	          {
	            $m = '0'.($total_time_minutes -   floor($total_time_minutes / 60) * 60);
	          }
	          else{
	            $m = ($total_time_minutes -   floor($total_time_minutes / 60) * 60);
	          }
	          $total_time_minutes_format = $h.':'.$m.':00';
	          $alert =0;
		}

		$explode_job_minutes = explode(":",$jobtime);
                    $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);

		echo json_encode(array('id' => $job_details->id, 'quick_job_times' => $quick_job_times, 'start_time' =>date('H:i', strtotime($job_details->start_time)), 'stop_time' =>$stop_time, 'jobtime' => $jobtime.' ('.$total_minutes.')', 'total_time_minutes_format' => $total_time_minutes_format, 'alert' => $alert, 'start_hour' => $hour, 'start_min' => $min,'date'=>date('d-M-Y', strtotime($job_details->job_date))));
	}

	public function timejobstop(){
		$id = Input::get("id");
		$data['stop_time'] = date('H:i:s', strtotime(Input::get("stoptime")));
		$data['comments'] = Input::get("comments");
		$data['status'] = 1;

		$stoptime = date('H:i:s', strtotime(Input::get("stoptime")));

		$jobs = DB::table('task_job')->where('id', $id)->first();
		$created_date = $jobs->job_created_date;
		
		$jobstart = strtotime($created_date.' '.$jobs->start_time);
        $jobstop   = strtotime($created_date.' '.$stoptime);

        if($jobstop < $jobstart)
        {
        	 $todate = date('Y-m-d', strtotime("+1 day", $jobstop));
             $jobstop   = strtotime($todate.' '.$stoptime);
        }

        $jobdiff  = $jobstop - $jobstart;


		//-----------Job Time Start----------------

        $hours = floor($jobdiff / (60 * 60));
        $minutes = $jobdiff - $hours * (60 * 60);
        $minutes = floor( $minutes / 60 );
        $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
        if($hours <= 9)
        {
          $hours = '0'.$hours;
        }
        else{
          $hours = $hours;
        }
        if($minutes <= 9)
        {
          $minutes = '0'.$minutes;
        }
        else{
          $minutes = $minutes;
        }
        if($second <= 9)
        {
          $second = '0'.$second;
        }
        else{
          $second = $second;
        }
        $jobtime =   $hours.':'.$minutes.':'.$second;

        //-----------Job Time End----------------

        $data['job_time'] = $jobtime;
		DB::table('task_job')->where('id', $id)->update($data);

		$statsdata['status'] = 1;
		DB::table('task_job')->where('active_id', $id)->update($statsdata);

		$count_minues = Input::get('break_time_val');


          if(floor($count_minues / 60) <= 9)
          {
            $h = '0'.floor($count_minues / 60);
          }
          else{
            $h = floor($count_minues / 60);
          }
          if(($count_minues -   floor($count_minues / 60) * 60) <= 9)
          {
            $m = '0'.($count_minues -   floor($count_minues / 60) * 60);
          }
          else{
            $m = ($count_minues -   floor($count_minues / 60) * 60);
          }
          $break_hours = $h.':'.$m.':00';
          $dataval['break_time'] = $break_hours;
          $dataval['job_id'] = $id;
          DB::table('job_break_time')->insert($dataval);
         
        if($jobs->job_type !=  0)
		{
			$client_id = $jobs->client_id;
			$get_invoice = DB::table('ta_auto_allocation')->where('auto_client_id',$client_id)->first();
			$get_client_invoice = DB::table('ta_client_invoice')->where('client_id',$client_id)->first();
			if(count($get_invoice))
			{
				$unserialize = unserialize($get_invoice->auto_tasks);
				$inv_no = '';
				if(count($unserialize))
				{
					foreach($unserialize as $key => $arrayval)
					{
						if(in_array($jobs->task_id, $arrayval))
						{
							$inv_no = $key;
						}
					}
				}
				if($inv_no != "")
				{	
					if(count($get_client_invoice))
					{
						$unserialize_tasks = unserialize($get_client_invoice->tasks);
						$get_invoice_tasks = $unserialize_tasks[$inv_no];
						if(!in_array($jobs->id,$get_invoice_tasks))
						{
							array_push($get_invoice_tasks,$jobs->id);
							$unserialize_tasks[$inv_no] = $get_invoice_tasks;
							$serialize_tasks = serialize($unserialize_tasks);
							$datatask['tasks'] = $serialize_tasks;
							DB::table('ta_client_invoice')->where('client_id',$client_id)->update($datatask);
						}
					}
					else{
						$unserialize_tasks[$inv_no] = array($jobs->id);
						$serialize_tasks = serialize($unserialize_tasks);
						$datatask['tasks'] = $serialize_tasks;
						$datatask['client_id'] = $client_id;
						$datatask['invoice'] = $inv_no;
						DB::table('ta_client_invoice')->where('client_id',$client_id)->insert($datatask);
					}
				}
			}
		}

		return Redirect::back()->with('message', 'Job Stopped Successfully');

	}

	public function edit_time_job_update(){
		$id = Input::get("hidden_edit_job_id");

		$data['stop_time'] = date('H:i:s', strtotime(Input::get("stoptime")));
		$data['comments'] = Input::get("comments");
		$data['status'] = 1;

		$stoptime = date('H:i:s', strtotime(Input::get("stoptime")));

		$jobs = DB::table('task_job')->where('id', $id)->first();
		$created_date = $jobs->job_created_date;
		
		$jobstart = strtotime($created_date.' '.$jobs->start_time);
        $jobstop   = strtotime($created_date.' '.$stoptime);

        if($jobstop < $jobstart)
        {
        	 $todate = date('Y-m-d', strtotime("+1 day", $jobstop));
             $jobstop   = strtotime($todate.' '.$stoptime);
        }

        $jobdiff  = $jobstop - $jobstart;


		//-----------Job Time Start----------------

        $hours = floor($jobdiff / (60 * 60));
        $minutes = $jobdiff - $hours * (60 * 60);
        $minutes = floor( $minutes / 60 );
        $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
        if($hours <= 9)
        {
          $hours = '0'.$hours;
        }
        else{
          $hours = $hours;
        }
        if($minutes <= 9)
        {
          $minutes = '0'.$minutes;
        }
        else{
          $minutes = $minutes;
        }
        if($second <= 9)
        {
          $second = '0'.$second;
        }
        else{
          $second = $second;
        }
        $jobtime =   $hours.':'.$minutes.':'.$second;

        //-----------Job Time End----------------

        $data['job_time'] = $jobtime;
        $data['task_id'] = Input::get('task_id');

		DB::table('task_job')->where('id', $id)->update($data);

		$statsdata['status'] = 1;
		DB::table('task_job')->where('active_id', $id)->update($statsdata);

		$count_minues = Input::get('edit_break_time_val');


          if(floor($count_minues / 60) <= 9)
          {
            $h = '0'.floor($count_minues / 60);
          }
          else{
            $h = floor($count_minues / 60);
          }
          if(($count_minues -   floor($count_minues / 60) * 60) <= 9)
          {
            $m = '0'.($count_minues -   floor($count_minues / 60) * 60);
          }
          else{
            $m = ($count_minues -   floor($count_minues / 60) * 60);
          }
          $break_hours = $h.':'.$m.':00';
          $dataval['break_time'] = $break_hours;
          $dataval['job_id'] = $id;
          $check_break_time = DB::table('job_break_time')->where('job_id',$id)->first();
          if(count($check_break_time))
          {
          	DB::table('job_break_time')->where('job_id', $id)->update($dataval);
          }
          else{
          	DB::table('job_break_time')->insert($dataval);
          }

        if($jobs->job_type !=  0)
		{
			$client_id = $jobs->client_id;
			$get_invoice = DB::table('ta_auto_allocation')->where('auto_client_id',$client_id)->first();
			$get_client_invoice = DB::table('ta_client_invoice')->where('client_id',$client_id)->first();
			if(count($get_invoice))
			{
				$unserialize = unserialize($get_invoice->auto_tasks);
				$inv_no = '';
				if(count($unserialize))
				{
					foreach($unserialize as $key => $arrayval)
					{
						if(in_array($data['task_id'], $arrayval))
						{
							$inv_no = $key;
						}
					}
				}
				if($inv_no != "")
				{	
					if(count($get_client_invoice))
					{
						$unserialize_tasks = unserialize($get_client_invoice->tasks);
						$get_invoice_tasks = $unserialize_tasks[$inv_no];
						if(!in_array($jobs->id,$get_invoice_tasks))
						{
							array_push($get_invoice_tasks,$jobs->id);
							$unserialize_tasks[$inv_no] = $get_invoice_tasks;
							$serialize_tasks = serialize($unserialize_tasks);
							$datatask['tasks'] = $serialize_tasks;
							DB::table('ta_client_invoice')->where('client_id',$client_id)->update($datatask);
						}
					}
					else{
						$unserialize_tasks[$inv_no] = array($jobs->id);
						$serialize_tasks = serialize($unserialize_tasks);
						$datatask['tasks'] = $serialize_tasks;
						$datatask['client_id'] = $client_id;
						$datatask['invoice'] = $inv_no;
						DB::table('ta_client_invoice')->where('client_id',$client_id)->insert($datatask);
					}
				}
			}
		}
		return Redirect::back()->with('message', 'Job Updated Successfully');

	}



	public function timejobstopquick(){
		$id = Input::get("id");
		$data['stop_time'] = date('H:i:s', strtotime(Input::get("stoptime")));
		$data['comments'] = Input::get("comments");
		$data['color'] = 0;
		

		$stoptime = date('H:i:s', strtotime(Input::get("stoptime")));

		$jobs = DB::table('task_job')->where('id', $id)->first();
		$created_date = $jobs->job_created_date;
		
		$jobstart = strtotime($created_date.' '.$jobs->start_time);
        $jobstop   = strtotime($created_date.' '.$stoptime);

        if($jobstop < $jobstart)
        {
        	 $todate = date('Y-m-d', strtotime("+1 day", $jobstop));
             $jobstop   = strtotime($todate.' '.$stoptime);
        }

        $jobdiff  = $jobstop - $jobstart;


		//-----------Job Time Start----------------

        $hours = floor($jobdiff / (60 * 60));
        $minutes = $jobdiff - $hours * (60 * 60);
        $minutes = floor( $minutes / 60 );
        $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
        if($hours <= 9)
        {
          $hours = '0'.$hours;
        }
        else{
          $hours = $hours;
        }
        if($minutes <= 9)
        {
          $minutes = '0'.$minutes;
        }
        else{
          $minutes = $minutes;
        }
        if($second <= 9)
        {
          $second = '0'.$second;
        }
        else{
          $second = $second;
        }
        $jobtime =   $hours.':'.$minutes.':'.$second;

        //-----------Job Time End----------------

        $data['job_time'] = $jobtime;

        $id_quick = DB::table('task_job')->where('id', $id)->first();
        $active_id = $id_quick->active_id;

        $activedata['color'] = 1;

       DB::table('task_job')->where('id', $active_id)->update($activedata);

		DB::table('task_job')->where('id', $id)->update($data);

		return Redirect::back()->with('message', 'Job Stopped Successfully');

	}








	public function jobaddbreak(){
		$id = Input::get("id");
		$data['job_id'] = Input::get("id");		
		$data['break_time'] = Input::get("breaktime");
		

		DB::table('job_break_time')->insert($data);

		return Redirect::back()->with('message', 'Break added Successfully');
	}

	public function breaktimedetails(){

		$id = Input::get('jobid');
		$break_details = DB::table('job_break_time')->where('job_id', $id)->get();

		$i=1;
		$output='';

		if(count($break_details)){
			foreach ($break_details as $break) {
				if($break->break_time == '00:15:00'){
					$time_minutes = '15';
				}
				else if($break->break_time == '00:30:00'){
					$time_minutes = '30';
				}
				else if($break->break_time == '00:45:00'){
					$time_minutes = '45';	
				}
				else{
					$time_minutes = '60';
				}
				$output.='<tr>
						<td>'.$i.'</td>
						<td>'.$time_minutes.' Minutes</td>
				</td>';
				$i++;
			}			
		}
		else{
			$output='
				<tr>
					<td></td>
					<td align="left">Empty</td>					
				</tr>
			';
		}
		
		echo $output;		
	}

	public function jobuserfilter(){
		$id = Input::get('userid');
		$sessn=array('task_job_user' => $id);
		Session::put($sessn); 
		$userdetails = DB::table('user')->where('user_id',$id)->orderBy('firstname','asc')->first();
		$currentdate = date('Y-m-d');
		$currentdatetime = date('Y-m-d H:i:s');
		

		$joblist = DB::table('task_job')->where('user_id', $id)->where('active_id',0)->get();
		$i=1;
		$output='';
		if(count($joblist)){              
              foreach ($joblist as $jobs) {
                if($jobs->quick_job == 0 || $jobs->quick_job == 1){
                  if($jobs->status == 0){

                  	$client_details = DB::table('cm_clients')->where('client_id', $jobs->client_id)->first();
                    if(count($client_details) != ''){
                      $client_name = $client_details->company.' ('.$jobs->client_id.')';
                    }
                    else{
                      $client_name = 'N/A';
                    }

                    $task_details = DB::table('time_task')->where('id', $jobs->task_id)->first();

                    if(count($task_details) != ''){
                      $task_name = $task_details->task_name;
                      $task_type = $task_details->task_type;

                      if($task_type == 0){
                        $task_type = '<i class="fa fa-desktop" style="margin-right:10px;"></i> Internal Task';
                      }
                      else if($task_type == 1){
                        $task_type = '<i class="fa fa-users" style="margin-right:10px;"></i> Client Task';
                      }
                      else{
                        $task_type = '<i class="fa fa-globe" style="margin-right:10px;"></i> Global Task';
                      }
                    }
                    else{
                      $task_name = 'N/A';
                      $task_type = 'N/A';
                    }

                    //-----------Job Time Start----------------

                    $created_date = $jobs->job_created_date;

                    $jobstart = strtotime($created_date.' '.$jobs->start_time);
                    $jobend   = strtotime($created_date.' '.date('H:i:s'));
                    

                    if($jobend < $jobstart)
                    {
                      $todate = date('Y-m-d', strtotime("+1 day", $jobend));
                      $jobend   = strtotime($todate.' '.date('H:i:s'));
                    }

                    $jobdiff  = $jobend - $jobstart;



                    $hours = floor($jobdiff / (60 * 60));
                    $minutes = $jobdiff - $hours * (60 * 60);
                    $minutes = floor( $minutes / 60 );
                    $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
                    if($hours <= 9)
                    {
                      $hours = '0'.$hours;
                    }
                    else{
                      $hours = $hours;
                    }
                    if($minutes <= 9)
                    {
                      $minutes = '0'.$minutes;
                    }
                    else{
                      $minutes = $minutes;
                    }
                    if($second <= 9)
                    {
                      $second = '0'.$second;
                    }
                    else{
                      $second = $second;
                    }

                    $jobtime =   $hours.':'.$minutes.':'.$second;

                    $explode_job_minutes = explode(":",$jobtime);
                    $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);

                    //-----------Job Time End----------------

                    $current_date = date('Y-m-d');
                    if($current_date != $jobs->job_date)
                    {
                      $redcolor = 'color:#f00;';
                    }
                    elseif($jobs->color == 1){
                      $redcolor = 'color:#0f9600';
                    }

                    elseif($jobs->color == 0){
                      $redcolor = 'color:#666';
                    }

                    else{
                      $redcolor = '';
                    }

                    if($jobs->quick_job == 0){

                      $quick_job = 'No';                     

                      if($jobs->color == '1'){
                        $buttons = '<a style="'.$redcolor.'" href="javascript:" class="stop_class" data-element="'.$jobs->id.'" style="'.$redcolor.'">Stop</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a style="'.$redcolor.'" href="javascript:" class="create_new_quick" data-element="'.$jobs->id.'">Quick Job</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript:" class="edit_active_job" data-element="'.$jobs->id.'" style="'.$redcolor.'">Edit Job</a>';
                      }
                      else{
                        $buttons = '<a style="'.$redcolor.'; cursor:not-allowed" href="javascript:" data-element="'.$jobs->id.'" style="'.$redcolor.'">Stop</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a style="'.$redcolor.'; cursor:not-allowed" href="javascript:" data-element="'.$jobs->id.'">Quick Job</a>';
                      }
                    }
                    elseif($jobs->stop_time == '00:00:00'){
                      $quick_job = 'Yes'; 
                      $buttons = '<a style="'.$redcolor.'" href="javascript:" class="stop_class_quick" data-element="'.$jobs->id.'" style="'.$redcolor.'">Stop</a>';
                    }
                    else{
                      $quick_job = 'Yes'; 
                      $buttons = '';
                    }

                    $ratelist = DB::table('user_cost')->where('user_id', $jobs->user_id)->get();

                    $rate_result = '0';
                    $cost = '0';

                    if(count($ratelist)){
                      foreach ($ratelist as $rate) {
                        $job_date = strtotime($jobs->job_date);
                        $from_date = strtotime($rate->from_date);
                        $to_date = strtotime($rate->to_date);

                        if($rate->to_date != '0000-00-00'){                         
                          if($job_date >= $from_date  && $job_date <= $to_date){
                            
                            $rate_result = $rate->cost;                            
                            
                          }
                        }
                        else{
                          if($job_date >= $from_date){
                            $rate_result = $rate->cost;
                          }
                        }
                      }                      
                    }

                    $cost = ($rate_result/60)*$total_minutes;
                    $inv_no = '';
                    if($jobs->job_type !=  0)
					{
						$client_id = $jobs->client_id;
						$get_invoice = DB::table('ta_auto_allocation')->where('auto_client_id',$client_id)->first();
						$get_client_invoice = DB::table('ta_client_invoice')->where('client_id',$client_id)->first();
						if(count($get_invoice))
						{
							$unserialize = unserialize($get_invoice->auto_tasks);
							if(count($unserialize))
							{
								foreach($unserialize as $key => $arrayval)
								{
									if(in_array($jobs->task_id, $arrayval))
									{
										$inv_no = '&nbsp;&nbsp;|&nbsp;&nbsp;<i class="fa fa-at" title="Allocated to Invoice Number #'.$key.'"></i>';
									}
								}
							}
						}
					}

                  	$output.='
                  			<tr>
				              <td align="left" style="'.$redcolor.'">'.$i.'</td>
				              <td align="left" style="'.$redcolor.'">'.$client_name.'</td>
				              <td align="left" style="'.$redcolor.'">'.$task_name.'</td>
				              <td align="left" style="'.$redcolor.'">'.$task_type.'</td>
				              <td align="left" style="'.$redcolor.'">'.$quick_job.'</td>
				              <td align="left" style="'.$redcolor.'">'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
				              <td align="left" style="'.$redcolor.'">'.date('H:i:s', strtotime($jobs->start_time)).'</td>
				              <td align="left" style="'.$redcolor.'">N/A</td>
				              <td align="left" style="'.$redcolor.'">'.number_format_invoice_without_decimal($rate_result).'</td>
				              <td align="left" style="'.$redcolor.'"><span id="job_bep_refresh_'.$jobs->id.'" style="'.$redcolor.'">'.number_format_invoice_without_decimal($cost).'</span></td>
				              <td align="left" style="'.$redcolor.'">
				              <span id="job_time_refresh_'.$jobs->id.'" style="'.$redcolor.'">'.$jobtime.' ('.$total_minutes.')</span> &nbsp;&nbsp;<a href="javascript:"><i class="fa fa-refresh job_time_refresh" aria-hidden="true" data-element="'.$jobs->id.'"></i></a>
				              </td>
				              
				              <td align="center" style="'.$redcolor.'">'.$buttons.' '.$inv_no.'</td>
				            </tr>';
                  	$joblist_child = DB::table('task_job')->where('user_id',$id)->where('active_id',$jobs->id)->get();
                      $childi = 1;
                      if(count($joblist_child)){              
                        foreach ($joblist_child as $child) {
                          if($child->quick_job == 0 || $child->quick_job == 1){
                            if($child->status == 0){
                              $client_details = DB::table('cm_clients')->where('client_id', $child->client_id)->first();
			                    if(count($client_details) != ''){
			                      $client_name = $client_details->company.' ('.$child->client_id.')';
			                    }
			                    else{
			                      $client_name = 'N/A';
			                    }

			                    $task_details = DB::table('time_task')->where('id', $child->task_id)->first();

			                    if(count($task_details) != ''){
			                      $task_name = $task_details->task_name;
			                      $task_type = $task_details->task_type;

			                      if($task_type == 0){
			                        $task_type = '<i class="fa fa-desktop" style="margin-right:10px;"></i> Internal Task';
			                      }
			                      else if($task_type == 1){
			                        $task_type = '<i class="fa fa-users" style="margin-right:10px;"></i> Client Task';
			                      }
			                      else{
			                        $task_type = '<i class="fa fa-globe" style="margin-right:10px;"></i> Global Task';
			                      }
			                    }
			                    else{
			                      $task_name = 'N/A';
			                      $task_type = 'N/A';
			                    }

			                    //-----------Job Time Start----------------

			                    $created_date = $child->job_created_date;
								$jobstart = strtotime($created_date.' '.$child->start_time);
								$jobend   = strtotime($created_date.' '.date('H:i:s'));
								if($jobend < $jobstart)
								{
									if($created_date == date('Y-m-d'))
									{
									    $childnegative = '-';
									    $jobdiff  = $jobstart - $jobend;
									}
									else{
									  $childnegative = '';
									  $todate = date('Y-m-d', strtotime("+1 day", $jobend));
									  $jobend   = strtotime($todate.' '.date('H:i:s'));
									  $jobdiff  = $jobend - $jobstart;
									}
								}
								else{
									$childnegative = '';
									$jobdiff  = $jobend - $jobstart;
								}



			                    $hours = floor($jobdiff / (60 * 60));
			                    $minutes = $jobdiff - $hours * (60 * 60);
			                    $minutes = floor( $minutes / 60 );
			                    $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
			                    if($hours <= 9)
			                    {
			                      $hours = '0'.$hours;
			                    }
			                    else{
			                      $hours = $hours;
			                    }
			                    if($minutes <= 9)
			                    {
			                      $minutes = '0'.$minutes;
			                    }
			                    else{
			                      $minutes = $minutes;
			                    }
			                    if($second <= 9)
			                    {
			                      $second = '0'.$second;
			                    }
			                    else{
			                      $second = $second;
			                    }

			                    $jobtime =   $hours.':'.$minutes.':'.$second;
			                    $explode_job_minutes = explode(":",$jobtime);
                    			$total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);

                    			if($child->stop_time != "00:00:00")
	                            {
	                            	$explode_job_minutes = explode(":",$child->job_time);
	                    			$total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
	                    		}
			                    //-----------Job Time End----------------

			                    $current_date = date('Y-m-d');
			                    if($current_date != $child->job_date)
			                    {
			                      $redcolor = 'color:#f00;';
			                    }
			                    elseif($child->color == 1){
			                      $redcolor = 'color:#0f9600';
			                    }

			                    elseif($child->color == 0){
			                      $redcolor = 'color:#666';
			                    }

			                    else{
			                      $redcolor = '';
			                    }

			                    if($child->quick_job == 0){

			                      $quick_job = 'No';                     

			                      if($child->color == '1'){
			                        $buttons = '<a style="'.$redcolor.'" href="javascript:" class="stop_class" data-element="'.$child->id.'" style="'.$redcolor.'">Stop</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a style="'.$redcolor.'" href="javascript:" class="create_new_quick" data-element="'.$child->id.'">Quick Job</a>';
			                      }
			                      else{
			                        $buttons = '<a style="'.$redcolor.'; cursor:not-allowed" href="javascript:" data-element="'.$child->id.'" style="'.$redcolor.'">Stop</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a style="'.$redcolor.'; cursor:not-allowed" href="javascript:" data-element="'.$child->id.'">Quick Job</a>';
			                      }
			                    }
			                    elseif($child->stop_time == '00:00:00'){
			                      $quick_job = 'Yes'; 
			                      $buttons = '<a style="'.$redcolor.'" href="javascript:" class="stop_class_quick" data-element="'.$child->id.'" style="'.$redcolor.'">Stop</a>|&nbsp;&nbsp;<a href="javascript:" class="edit_quick_job" data-element="'.$child->id.'" style="'.$redcolor.'">Edit Job</a>';
			                    }
			                    else{
			                      $quick_job = 'Yes'; 
			                      $buttons = '<a href="javascript:" class="edit_quick_job" data-element="'.$child->id.'" style="'.$redcolor.'">Edit Job</a>';
			                    }



			                    $ratelist_child = DB::table('user_cost')->where('user_id', $child->user_id)->get();

			                    $rate_child_result = '0';
			                    $child_cost = '0';

			                    if(count($ratelist_child)){
			                      foreach ($ratelist_child as $rate_child) {
			                        $job_date = strtotime($child->job_date);
			                        $from_date = strtotime($rate_child->from_date);
			                        $to_date = strtotime($rate_child->to_date);

			                        if($rate_child->to_date != '0000-00-00'){                         
			                          if($job_date >= $from_date  && $job_date <= $to_date){
			                            
			                            $rate_child_result = $rate_child->cost;                            
			                            
			                          }
			                        }
			                        else{
			                          if($job_date >= $from_date){
			                            $rate_child_result = $rate_child->cost;
			                          }
			                        }
			                      }                      
			                    }

			                    $child_cost = ($rate_child_result/60)*$total_minutes;

			                    $inv_no_child = '';
			                    if($child->job_type !=  0)
								{
									$client_id = $child->client_id;
									$get_invoice = DB::table('ta_auto_allocation')->where('auto_client_id',$client_id)->first();
									$get_client_invoice = DB::table('ta_client_invoice')->where('client_id',$client_id)->first();
									if(count($get_invoice))
									{
										$unserialize = unserialize($get_invoice->auto_tasks);
										if(count($unserialize))
										{
											foreach($unserialize as $key => $arrayval)
											{
												if(in_array($child->task_id, $arrayval))
												{
													$inv_no_child = '&nbsp;&nbsp;|&nbsp;&nbsp;<i class="fa fa-at" title="Allocated to Invoice Number #'.$key.'"></i>';
												}
											}
										}
									}
								}

                          $output.='
                          <tr>
                            <td align="left" style="'.$redcolor.'">'.$i.'.'.$childi.'</td>
                            <td align="left" style="'.$redcolor.'">'.$client_name.'</td>
                            <td align="left" style="'.$redcolor.'">'.$task_name.'</td>
                            <td align="left" style="'.$redcolor.'">'.$task_type.'</td>
                            <td align="left" style="'.$redcolor.'">'.$quick_job.'</td>
                            <td align="left" style="'.$redcolor.'">'.date('d-M-Y', strtotime($child->job_date)).'</td>
                            <td align="left" style="'.$redcolor.'">'.date('H:i:s', strtotime($child->start_time)).'</td>

                            ';

                            if($child->stop_time != "00:00:00")
                            {
                              $output.='<td align="left" style="'.$redcolor.'">'.date('H:i:s', strtotime($child->stop_time)).'</td>';
                            }
                            else{
                              $output.='<td align="left" style="'.$redcolor.'">N/A</td>';
                            }

                            $output.='
                            <td align="left" style="'.$redcolor.'">'.number_format_invoice_without_decimal($rate_child_result).'</td>
				              <td align="left" style="'.$redcolor.'"><span id="job_bep_refresh_'.$child->id.'" style="'.$redcolor.'">'.number_format_invoice_without_decimal($child_cost).'</span></td>
                            <td align="left" style="'.$redcolor.'">';
                            if($child->stop_time != "00:00:00")
                            {
                              	$output.='<span style="'.$redcolor.'">'.$child->job_time.' ('.$total_minutes.')</span>';
                            }
                            else{
                              $output.='<span id="job_time_refresh_'.$child->id.'" style="'.$redcolor.'">'.$childnegative.' '.$jobtime.' ('.$total_minutes.')</span> &nbsp;&nbsp;<a href="javascript:"><i class="fa fa-refresh job_time_refresh" aria-hidden="true" data-element="'.$child->id.'"></i></a>';
                            }
                            $output.='</td>
                            <td align="center" style="'.$redcolor.'">'.$buttons.' '.$inv_no_child.'</td>
                          </tr>';
                            $childi++;
                          }
                        }
                      }
                    }
                  	$i++;
                  }
              }
          }
      }
      if($i == 1){
      	$output = '
      		<tr>
	            <td align="left"></td>
	            <td align="left"></td>
	            <td align="left"></td>
	            <td align="left"></td>
	            <td align="left"></td>
	            <td align="center">Empty</td>
	            <td align="left"></td>	            
	            <td align="left"></td>
	            <td align="left"></td>
	            <td align="left"></td>
	            <td align="left"></td>
	            <td align="left"></td>                        
            </tr>
      	';
      }




      	$outputclose='';
	    $iclose=1;            
	    if(count($joblist)){              
	      foreach ($joblist as $jobs) {
	      	$current_date = date('Y-m-d');
            if($current_date == $jobs->job_date)
            {
			        if($jobs->status == 1 ){
			        $client_details = DB::table('cm_clients')->where('client_id', $jobs->client_id)->first();

			        if(count($client_details) != ''){
			          $client_name = $client_details->company.' ('.$jobs->client_id.')';
			        }
			        else{
			          $client_name = 'N/A';
			        }
			        $task_details = DB::table('time_task')->where('id', $jobs->task_id)->first();

			        if(count($task_details) != ''){
			          $task_name = $task_details->task_name;
			          $task_type = $task_details->task_type;

			          if($task_type == 0){
			            $task_type = '<i class="fa fa-desktop" style="margin-right:10px;"></i> Internal Task';
			          }
			          else if($task_type == 1){
			            $task_type = '<i class="fa fa-users" style="margin-right:10px;"></i> Client Task';
			          }
			          else{
			            $task_type = '<i class="fa fa-globe" style="margin-right:10px;"></i> Global Task';
			          }
			        }
			        else{
			          $task_name = 'N/A';
			          $task_type = 'N/A';
			        }

			        if($jobs->quick_job == 0){

                      $quick_job = 'No';

                      $get_quick_jobs = DB::table('task_job')->where('active_id',$jobs->id)->get();
                      $quick_minutes = 0;
                      if(count($get_quick_jobs))
                      {
                        foreach($get_quick_jobs as $quickjobs_single)
                        {
                          $total_quick_jobs_1 = explode(':', $quickjobs_single->job_time);
                          $minutes = ($total_quick_jobs_1[0]*60) + ($total_quick_jobs_1[1]) + ($total_quick_jobs_1[2]/60);
                          if($quick_minutes == 0)
                          {
                            $quick_minutes = $minutes;
                          }
                          else{
                            $quick_minutes = $quick_minutes + $minutes;
                          }
                        }
                      }

                      $break_time_min = DB::table('job_break_time')->where('job_id',$jobs->id)->where('break_time','!=','00:00:00')->first();
                      $break_timee_minutes = 0;
                      if(count($break_time_min))
                      {
                        $break_timee = explode(':', $break_time_min->break_time);
                        $break_timee_minutes = ($break_timee[0]*60) + ($break_timee[1]) + ($break_timee[2]/60);
                      }


                      $quick_minutes = $quick_minutes + $break_timee_minutes;

                      $job_timee = explode(':', $jobs->job_time);
                      $job_timee_minutes = ($job_timee[0]*60) + ($job_timee[1]) + ($job_timee[2]/60);

                      $job_time_min = $job_timee_minutes - $quick_minutes;

                      if(floor($job_time_min / 60) <= 9)
                      {
                        $h = '0'.floor($job_time_min / 60);
                      }
                      else{
                        $h = floor($job_time_min / 60);
                      }
                      if(($job_time_min -   floor($job_time_min / 60) * 60) <= 9)
                      {
                        $m = '0'.($job_time_min -   floor($job_time_min / 60) * 60);
                      }
                      else{
                        $m = ($job_time_min -   floor($job_time_min / 60) * 60);
                      }
                      $job_time = $h.':'.$m.':00';
                    }
                    else{
                      $quick_job = 'Yes'; 
                      $quick_minutes = 0;
                      $break_time_min = DB::table('job_break_time')->where('job_id',$jobs->id)->where('break_time','!=','00:00:00')->first();
                      $break_timee_minutes = 0;
                      if(count($break_time_min))
                      {
                        $break_timee = explode(':', $break_time_min->break_time);
                        $break_timee_minutes = ($break_timee[0]*60) + ($break_timee[1]) + ($break_timee[2]/60);
                      }


                      $quick_minutes = $quick_minutes + $break_timee_minutes;

                      $job_timee = explode(':', $jobs->job_time);
                      $job_timee_minutes = ($job_timee[0]*60) + ($job_timee[1]) + ($job_timee[2]/60);

                      $job_time_min = $job_timee_minutes - $quick_minutes;

                      if(floor($job_time_min / 60) <= 9)
                      {
                        $h = '0'.floor($job_time_min / 60);
                      }
                      else{
                        $h = floor($job_time_min / 60);
                      }
                      if(($job_time_min -   floor($job_time_min / 60) * 60) <= 9)
                      {
                        $m = '0'.($job_time_min -   floor($job_time_min / 60) * 60);
                      }
                      else{
                        $m = ($job_time_min -   floor($job_time_min / 60) * 60);
                      }
                      $job_time = $h.':'.$m.':00';
                    }

                    $explode_job_minutes = explode(":",$job_time);
                    $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);

			        if($jobs->comments != "") { $comments = $jobs->comments; } else { $comments = 'No Comments Found'; }


			        $ratelist_close = DB::table('user_cost')->where('user_id', $jobs->user_id)->get();

                    $rate_close_result = '0';
                    $close_cost = '0';

                    if(count($ratelist_close)){
                      foreach ($ratelist_close as $rate_close) {
                        $job_date = strtotime($jobs->job_date);
                        $from_date = strtotime($rate_close->from_date);
                        $to_date = strtotime($rate_close->to_date);

                        if($rate_close->to_date != '0000-00-00'){                         
                          if($job_date >= $from_date  && $job_date <= $to_date){
                            
                            $rate_close_result = $rate_close->cost;                            
                            $close_cost = ($rate_close_result/60)*$total_minutes;
                          }
                        }
                        else{
                          if($job_date >= $from_date){
                            $rate_close_result = $rate_close->cost;
                            $close_cost = ($rate_close_result/60)*$total_minutes;
                          }
                        }
                      }                      
                    }

                    $inv_no = '';
                    if($jobs->job_type !=  0)
					{
						$client_id = $jobs->client_id;
						$get_invoice = DB::table('ta_auto_allocation')->where('auto_client_id',$client_id)->first();
						$get_client_invoice = DB::table('ta_client_invoice')->where('client_id',$client_id)->first();
						if(count($get_invoice))
						{
							$unserialize = unserialize($get_invoice->auto_tasks);
							if(count($unserialize))
							{
								foreach($unserialize as $key => $arrayval)
								{
									if(in_array($jobs->task_id, $arrayval))
									{
										$inv_no = '&nbsp;&nbsp;|&nbsp;&nbsp;<i class="fa fa-at" title="Allocated to Invoice Number #'.$key.'"></i>';
									}
								}
							}
						}
					}

			    $outputclose.='
			    <tr>
			      <td align="left">'.$iclose.'</td>
			      <td align="left">'.$client_name.'</td>
			      <td align="left">'.$task_name.'</td>
			      <td align="left">'.$task_type.'</td>
			      <td align="left">'.$quick_job.'</td>
			      <td align="left">'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
			      <td align="left">'.date('H:i:s', strtotime($jobs->start_time)).'</td>
			      <td align="left">'.$job_time.' ('.$total_minutes.')</td>
			      <td align="left">'.date('H:i:s', strtotime($jobs->stop_time)).'</td>
			      <td align="left">'.number_format_invoice_without_decimal($rate_close_result).'</td>
			      <td align="left">'.number_format_invoice_without_decimal($close_cost).'</td>
			      <td align="center">
			      <a href="javascript:" class="fa fa-comment" data-toggle="modal" data-target="#comments_'.$jobs->id.'" title="View Comments"></a>
			      &nbsp;&nbsp;|&nbsp;&nbsp;
			      <a href="javascript:" class="edit_close_job" data-element="'.$jobs->id.'">Edit Job</a>
			     	'.$inv_no.'
                        <div id="comments_'.$jobs->id.'" class="modal fade" role="dialog" >
                            <div class="modal-dialog" style="width:20%">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                  <h4 class="modal-title">Comments</h4>
                                </div>
                                <div class="modal-body">
                                  '.$comments.'
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                              </div>
                            </div>
                          </div>
                      </td>
			    </tr>';
			    $joblist_child = DB::table('task_job')->where('user_id',$id)->where('active_id',$jobs->id)->get();
                $childiclose = 1;
                $iclose=1;            
				    if(count($joblist_child)){              
				      foreach ($joblist_child as $child) {
				      	$current_date = date('Y-m-d');
			            if($current_date == $child->job_date)
			            {
						        if($child->status == 1 ){
						        $client_details = DB::table('cm_clients')->where('client_id', $child->client_id)->first();

						        if(count($client_details) != ''){
						          $client_name = $client_details->company.' ('.$child->client_id.')';
						        }
						        else{
						          $client_name = 'N/A';
						        }
						        $task_details = DB::table('time_task')->where('id', $child->task_id)->first();

						        if(count($task_details) != ''){
						          $task_name = $task_details->task_name;
						          $task_type = $task_details->task_type;

						          if($task_type == 0){
						            $task_type = '<i class="fa fa-desktop" style="margin-right:10px;"></i> Internal Task';
						          }
						          else if($task_type == 1){
						            $task_type = '<i class="fa fa-users" style="margin-right:10px;"></i> Client Task';
						          }
						          else{
						            $task_type = '<i class="fa fa-globe" style="margin-right:10px;"></i> Global Task';
						          }
						        }
						        else{
						          $task_name = 'N/A';
						          $task_type = 'N/A';
						        }

						        if($child->quick_job == 0){
						          $quick_job = 'No';
						          $job_time = $child->job_time;
						        }
						        else{
						          $quick_job = 'Yes';
						          $job_time = $child->job_time;
						        }

						        $explode_job_minutes = explode(":",$job_time);
                    $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);

						        if($child->comments != "") { $comments = $child->comments; } else { $comments = 'No Comments Found'; }


						        $ratelist_close_child = DB::table('user_cost')->where('user_id', $child->user_id)->get();

			                    $rate_close_result_child = '0';
			                    $close_cost_child = '0';

			                    if(count($ratelist_close_child)){
			                      foreach ($ratelist_close_child as $rate_close_child) {
			                        $job_date = strtotime($child->job_date);
			                        $from_date = strtotime($rate_close_child->from_date);
			                        $to_date = strtotime($rate_close_child->to_date);

			                        if($rate_close_child->to_date != '0000-00-00'){                         
			                          if($job_date >= $from_date  && $job_date <= $to_date){
			                            
			                            $rate_close_result_child = $rate_close_child->cost;                            
			                            $close_cost_child = ($rate_close_result_child/60)*$total_minutes;
			                          }
			                        }
			                        else{
			                          if($job_date >= $from_date){
			                            $rate_close_result_child = $rate_close_child->cost;
			                            $close_cost_child = ($rate_close_result_child/60)*$total_minutes;
			                          }
			                        }
			                      }                      
			                    }

			                    $inv_no = '';
			                    if($child->job_type !=  0)
								{
									$client_id = $child->client_id;
									$get_invoice = DB::table('ta_auto_allocation')->where('auto_client_id',$client_id)->first();
									$get_client_invoice = DB::table('ta_client_invoice')->where('client_id',$client_id)->first();
									if(count($get_invoice))
									{
										$unserialize = unserialize($get_invoice->auto_tasks);
										if(count($unserialize))
										{
											foreach($unserialize as $key => $arrayval)
											{
												if(in_array($child->task_id, $arrayval))
												{
													$inv_no = '&nbsp;&nbsp;|&nbsp;&nbsp;<i class="fa fa-at" title="Allocated to Invoice Number #'.$key.'"></i>';
												}
											}
										}
									}
								}

						    $outputclose.='
						    <tr>
						      <td align="left">'.$iclose.'.'.$childiclose.'</td>
						      <td align="left">'.$client_name.'</td>
						      <td align="left">'.$task_name.'</td>
						      <td align="left">'.$task_type.'</td>
						      <td align="left">'.$quick_job.'</td>
						      <td align="left">'.date('d-M-Y', strtotime($child->job_date)).'</td>
						      <td align="left">'.date('H:i:s', strtotime($child->start_time)).'</td>
						      <td align="left">'.$job_time.' ('.$total_minutes.')</td>
						      <td align="left">'.date('H:i:s', strtotime($child->stop_time)).'</td>
						      <td align="left">'.number_format_invoice_without_decimal($rate_close_result_child).'</td>
						      <td align="left">'.number_format_invoice_without_decimal($close_cost_child).'</td>
						      <td align="center">
						      <a href="javascript:" class="fa fa-comment" data-toggle="modal" data-target="#comments_'.$child->id.'" title="View Comments"></a>
						      &nbsp;&nbsp;|&nbsp;&nbsp;
			      				<a href="javascript:" class="edit_close_job" data-element="'.$child->id.'">Edit Job</a>
			      				'.$inv_no.'
			                        <div id="comments_'.$child->id.'" class="modal fade" role="dialog" >
			                            <div class="modal-dialog" style="width:20%">
			                              <div class="modal-content">
			                                <div class="modal-header">
			                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
			                                  <h4 class="modal-title">Comments</h4>
			                                </div>
			                                <div class="modal-body">
			                                  '.$comments.'
			                                </div>
			                                <div class="modal-footer">
			                                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			                                </div>
			                              </div>
			                            </div>
			                          </div>
			                      </td>
						    </tr>';
						    $childiclose++;
								}
							}
						}
					}
			      $iclose++;
			          
			        }
			}
	      }     
	         
	    }
	    if($iclose == 1){
	      $outputclose.= '<tr>
	                <td align="left"></td>
	                <td align="left"></td>
	                <td align="left"></td>
	                <td align="left"></td>
	                <td align="right">Empty</td>
	                <td align="left"></td>
	                <td align="left"></td>
	                <td align="left"></td>
	                <td align="left"></td>
	                <td align="left"></td>
	                </tr>';

	        
	    }
	    if($id != "")
	    {
	    	$username = $userdetails->lastname.' '.$userdetails->firstname;
		    $curr_date = date('Y-m-d');
		    $check_date_available = DB::table('task_job')->where('user_id',$id)->where('quick_job',0)->where('status',0)->first();
		    if(count($check_date_available))
		    {
		    	$job_available = 1;
		    }
		    else{
		    	$job_available = 0;
		    }     

		    $getdetails_active_jobs = DB::table('task_job')->where('user_id',$id)->where('job_date',$currentdate)->where('quick_job',0)->where('stop_time','!=','00:00:00')->get();
            $getdetails_active_jobs_num = DB::table('task_job')->where('user_id',$id)->where('job_date',$currentdate)->where('stop_time','00:00:00')->count();


            $quick_jobs_count = DB::table('task_job')->where('user_id',$id)->where('job_date',$currentdate)->where('quick_job',1)->count();

            $getdetails_quick_jobs = DB::table('task_job')->where('user_id',$id)->where('job_date',$currentdate)->where('quick_job',1)->get();

            $currentdatetime = date('Y-m-d H:i:s');
            $spendminutes = 0;
            $spendquickminutes = 0;
            $primary_active_job_text = '';

            if($getdetails_active_jobs_num > 0)
            {
              $primary_active_job_text = 'Not Available as you have '.$getdetails_active_jobs_num.' active job(s)';
            }
            else{
              if(count($getdetails_active_jobs))
              {
                foreach($getdetails_active_jobs as $activejobs)
                {
                  $todaystarttime = strtotime($currentdate.' '.$activejobs->start_time);
                  $currenttime = strtotime($currentdate.' '.$activejobs->stop_time);
                  $diff = $currenttime - $todaystarttime;
                  if($spendminutes == 0) {
                    $spendminutes = round(abs($diff) / 60);
                  }
                  else {
                    $spendminutes = $spendminutes + round(abs($diff) / 60);
                  }
                }
              }
            }

            if(count($getdetails_quick_jobs))
            {
              foreach($getdetails_quick_jobs as $quickjobs)
              {
                if($quickjobs->stop_time == "00:00:00")
                {
                  $todaystarttime = strtotime($currentdate.' '.$quickjobs->start_time);
                  $currenttime = strtotime($currentdatetime);

                  if($currenttime < $todaystarttime)
                  {
                    $diff = 0;
                  }
                  else{
                    $diff = $currenttime - $todaystarttime;
                  }

                  if($spendquickminutes == 0) {
                    $spendquickminutes = round(abs($diff) / 60);
                  }
                  else {
                    $spendquickminutes = $spendquickminutes + round(abs($diff) / 60);
                  }
                }
                else{
                  $todaystarttime = strtotime($currentdate.' '.$quickjobs->start_time);
                  $currenttime = strtotime($currentdate.' '.$quickjobs->stop_time);
                  $diff = $currenttime - $todaystarttime;

                  if($spendquickminutes == 0) {
                    $spendquickminutes = round(abs($diff) / 60);
                  }
                  else {
                    $spendquickminutes = $spendquickminutes + round(abs($diff) / 60);
                  }
                }
              }
            }

            $actual_primary_job_time = $spendminutes - $spendquickminutes;

            if(floor($actual_primary_job_time / 60) <= 9)
            {
              $h = '0'.floor($actual_primary_job_time / 60);
            }
            else{
              $h = floor($actual_primary_job_time / 60);
            }
            if(($actual_primary_job_time -   floor($actual_primary_job_time / 60) * 60) <= 9)
            {
              $m = '0'.($actual_primary_job_time -   floor($actual_primary_job_time / 60) * 60);
            }
            else{
              $m = ($actual_primary_job_time -   floor($actual_primary_job_time / 60) * 60);
            }

            if($primary_active_job_text == "")
            {
              if($actual_primary_job_time < 60)
              {
                $summary_total_time = $m.' Minutes';
              }
              else{
                $summary_total_time = $h.':'.$m.' Hours';
              }
            }
            else{
              $summary_total_time = $primary_active_job_text;
            }

            if(floor($spendquickminutes / 60) <= 9)
            {
              $h = '0'.floor($spendquickminutes / 60);
            }
            else{
              $h = floor($spendquickminutes / 60);
            }
            if(($spendquickminutes -   floor($spendquickminutes / 60) * 60) <= 9)
            {
              $m = '0'.($spendquickminutes -   floor($spendquickminutes / 60) * 60);
            }
            else{
              $m = ($spendquickminutes -   floor($spendquickminutes / 60) * 60);
            }

            if($spendquickminutes < 60)
            {
              $summary_quick_jobs_time = $m.' Minutes';
            }
            else{
              $summary_quick_jobs_time = $h.':'.$m.' Hours';
            } 
	    }
	    else{
	     	$username = '';
	        $job_available = 0;
	        $spendminutes = 0;
            $spendquickminutes = 0;
            $primary_active_job_text = '';
            $quick_jobs_count = 0;
            $summary_quick_jobs_time = 0;
            $summary_total_time = 0;
	    }

      echo json_encode(array('activejob' => $output, 'closejob' => $outputclose,'username' => $username,'job_available' => $job_available,'quick_jobs' => $quick_jobs_count,'summary_quick_jobs_time' => $summary_quick_jobs_time,'summary_total_time' => $summary_total_time));

	}

	public function time_active_job(){

		$time_job = DB::table('task_job')->where('stop_time','00:00:00')->get();
		$tasks = DB::table('time_task')->where('task_type', 0)->get();
		$user = DB::table('user')->where('user_status', 0)->orderBy('firstname','asc')->get();
		return view('user/time_system/active_job', array('title' => 'Active Job', 'joblist' => $time_job, 'userlist' => $user, 'taskslist' => $tasks));

	}

	public function time_joboftheday(){

		$time_job = DB::table('task_job')->where('active_id',0)->get();
		$tasks = DB::table('time_task')->where('task_type', 0)->get();
		$user = DB::table('user')->where('user_status', 0)->orderBy('firstname','asc')->get();
		return view('user/time_system/job_of_the_day', array('title' => 'Job of the day', 'joblist' => $time_job, 'userlist' => $user, 'taskslist' => $tasks));

	}

	public function time_client_review(){

		$time_job = DB::table('task_job')->get();
		$tasks = DB::table('time_task')->where('task_type', 0)->get();
		$user = DB::table('user')->where('user_status', 0)->orderBy('firstname','asc')->get();
		return view('user/time_system/client_review', array('title' => 'Client Review', 'joblist' => $time_job, 'userlist' => $user, 'taskslist' => $tasks));

	}

	public function time_all_job(){

		$time_job = DB::table('task_job')->where('active_id',0)->get();
		$tasks = DB::table('time_task')->where('task_type', 0)->get();
		$user = DB::table('user')->where('user_status', 0)->orderBy('firstname','asc')->get();
		return view('user/time_system/all_job', array('title' => 'Client Review', 'joblist' => $time_job, 'userlist' => $user, 'taskslist' => $tasks));

	}

	public function staff_review(){

		$time_job = DB::table('task_job')->where('active_id',0)->get();
		$tasks = DB::table('time_task')->where('task_type', 0)->get();
		$user = DB::table('user')->where('user_status', 0)->orderBy('firstname','asc')->get();
		return view('user/time_system/staff_review', array('title' => 'Staff Review', 'joblist' => $time_job, 'userlist' => $user, 'taskslist' => $tasks));

	}

	public function get_job_details_test(){
		$jobid = Input::get('jobid');
		$job = DB::table('task_job')->where('id',$jobid)->first();
		if($job->client_id != "")
		{
			$client_details = DB::table('cm_clients')->where('client_id',$job->client_id)->first();
			$client_name = $client_details->company.'-'.$client_details->client_id;


			$tasks = DB::table('time_task')->where('clients','like','%'.$job->client_id.'%')->get();
		
			$output = '';
			if(count($tasks)){
				foreach ($tasks as $single_task) {
					if($single_task->task_type == 0){
						$icon = '<i class="fa fa-desktop" style="margin-right:10px;"></i>';
					}
					else if($single_task->task_type == 1){
						$icon = '<i class="fa fa-users" style="margin-right:10px;"></i>';
					}
					else{
						$icon = '<i class="fa fa-globe" style="margin-right:10px;"></i>';
					}

					$output.= '<li><a tabindex="-1" href="javascript:" class="tasks_li" data-element="'.$single_task->id.'">'.$icon.$single_task->task_name.'</a></li>';
				}
			}
			else{
				$output.= '<li><a tabindex="-1" href="javascript:">Empty</a></li>';
			}
		}
		else{
			$client_name = '';
			$output = '';
		}
		$task_name_details = DB::table('time_task')->where('id',$job->task_id)->first();

		$explode_job_minutes = explode(":",$job->job_time);
                    $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
		echo json_encode(array('id' => $job->id,'client_id' => $job->client_id,'user_id' => $job->user_id,'task_id' => $job->task_id,'start_time' => date('H:i', strtotime($job->start_time)),'stop_time' => date('H:i', strtotime($job->stop_time)),'job_time' => $job->job_time.' ('.$total_minutes.')','job_date' => date('F d, Y', strtotime($job->job_date)),'job_type' => $job->job_type,'quick_job' => $job->quick_job,'job_created_date' => $job->job_created_date,'comments' => $job->comments,'updated' => $job->updated,'status' => $job->status,'client_name' => $client_name,'tasks_group' => $output, 'task_name' => $task_name_details->task_name));
	}
	public function get_job_details(){
		$jobid = Input::get('jobid');
		$job = DB::table('task_job')->where('id',$jobid)->first();
		if($job->quick_job == 1)
		{
			$jobidd = $job->active_id;
		}
		else{
			$jobidd = $job->id;
		}
		$job_details_child = DB::table('task_job')->where('active_id', $jobidd)->get();
		if(count($job_details_child))
		{
			$job_times = 0;
			foreach($job_details_child as $child)
			{
				$time = explode(':', $child->job_time);
				$minutes = ($time[0]*60) + ($time[1]) + ($time[2]/60);
				if($job_times == 0)
				{
					$job_times = $minutes;
				}
				else{
					$job_times = $job_times + $minutes;
				}
			}
			if($job_times > 0)
			{
			  if(floor($job_times / 60) <= 9)
	          {
	            $h = '0'.floor($job_times / 60);
	          }
	          else{
	            $h = floor($job_times / 60);
	          }
	          if(($job_times -   floor($job_times / 60) * 60) <= 9)
	          {
	            $m = '0'.($job_times -   floor($job_times / 60) * 60);
	          }
	          else{
	            $m = ($job_times -   floor($job_times / 60) * 60);
	          }
	          $quick_job_times = $h.':'.$m.':00';
	      	}
	      	else{
	      		$quick_job_times = '00:00:00';
	      	}
		}
		else{
			$quick_job_times = '00:00:00';
		}



		$total_quick_jobs = $quick_job_times;
		if($total_quick_jobs == "" || $total_quick_jobs == "00:00:00")
        {
        	$total_quick_jobs_minutes = 0;
        }
        else{
        	$total_quick_jobs_1 = explode(':', $total_quick_jobs);
			$minutes = ($total_quick_jobs_1[0]*60) + ($total_quick_jobs_1[1]) + ($total_quick_jobs_1[2]/60);
			$total_quick_jobs_minutes = $minutes;
        }
        $break_time = DB::table('job_break_time')->where('job_id', $jobidd)->first();

      
		if(count($break_time)){
			$breaktime = $break_time->break_time;
			$total_break_time_1 = explode(':', $breaktime);
			$break_minutes = ($total_break_time_1[0]*60) + ($total_break_time_1[1]) + ($total_break_time_1[2]/60);
			$total_breaks_minutes = $break_minutes;
		}
		else{
			$breaktime = '';
			$total_breaks_minutes = 0;
		}

		$sum_of_breaks = $total_breaks_minutes + $total_quick_jobs_minutes;

		if($job->quick_job == 1)
		{
			$jobtime_calc = DB::table('task_job')->where('id',$job->active_id)->first();
			$jobtime_1 = explode(':', $jobtime_calc->job_time);
		}
		else{
			$jobtime_1 = explode(':', $job->job_time);
		}
		$job_minutes = ($jobtime_1[0]*60) + ($jobtime_1[1]) + ($jobtime_1[2]/60);
		$jobtime_minutes = $job_minutes;

		$total_time_minutes = $jobtime_minutes - $sum_of_breaks;

		if($job->quick_job == 1)
		{
			$till_stoptime = explode(':', $job->stop_time);
			$stop_minutes = ($till_stoptime[0]*60) + ($till_stoptime[1]) + ($till_stoptime[2]/60);
			$stoptime_till = $stop_minutes + $total_time_minutes;

			if(floor($stoptime_till / 60) <= 9)
	          {
	            $hs = '0'.floor($stoptime_till / 60);
	          }
	          else{
	            $hs = floor($stoptime_till / 60);
	          }
	          if(($stoptime_till -   floor($stoptime_till / 60) * 60) <= 9)
	          {
	            $ms = '0'.($stoptime_till -   floor($stoptime_till / 60) * 60);
	          }
	          else{
	            $ms = ($stoptime_till -   floor($stoptime_till / 60) * 60);
	          }
	          $stoptime_till_val = $hs.':'.$ms;
		}
		else{
			$stoptime_till_val = '00:00';
		}

		if($total_time_minutes < 0)
		{
			if(floor($total_time_minutes / 60) <= 9)
	          {
	            $h = floor($total_time_minutes / 60);
	            $h = str_replace("-","",$h);
	          }
	          else{
	            $h = floor($total_time_minutes / 60);
	            $h = str_replace("-","",$h);
	          }
	          if(($total_time_minutes -   floor($total_time_minutes / 60) * 60) <= 9)
	          {
	            $m = '0'.($total_time_minutes -   floor($total_time_minutes / 60) * 60);
	          }
	          else{
	            $m = ($total_time_minutes -   floor($total_time_minutes / 60) * 60);
	          }
	          $total_time_minutes_format = '-'.$h.':'.$m.':00';
	          $alert =1;
		}
		else{
			if(floor($total_time_minutes / 60) <= 9)
	          {
	            $h = '0'.floor($total_time_minutes / 60);
	          }
	          else{
	            $h = floor($total_time_minutes / 60);
	          }
	          if(($total_time_minutes -   floor($total_time_minutes / 60) * 60) <= 9)
	          {
	            $m = '0'.($total_time_minutes -   floor($total_time_minutes / 60) * 60);
	          }
	          else{
	            $m = ($total_time_minutes -   floor($total_time_minutes / 60) * 60);
	          }
	          $total_time_minutes_format = $h.':'.$m.':00';
	          $alert =0;
		}

		if($job->client_id != "")
		{
			$client_details = DB::table('cm_clients')->where('client_id',$job->client_id)->first();
			$client_name = $client_details->company.'-'.$client_details->client_id;

			$tasks = DB::table('time_task')->where('clients','like','%'.$job->client_id.'%')->get();
		
			$output = ' <li><a tabindex="-1" href="javascript:" class="tasks_li">Select Task</a></li>';
			if(count($tasks)){
				foreach ($tasks as $single_task) {
					if($single_task->task_type == 0){
						$icon = '<i class="fa fa-desktop" style="margin-right:10px;"></i>';
					}
					else if($single_task->task_type == 1){
						$icon = '<i class="fa fa-users" style="margin-right:10px;"></i>';
					}
					else{
						$icon = '<i class="fa fa-globe" style="margin-right:10px;"></i>';
					}

					$output.= '<li><a tabindex="-1" href="javascript:" class="tasks_li" data-element="'.$single_task->id.'">'.$icon.$single_task->task_name.'</a></li>';
				}
			}
			else{
				$output.= '<li><a tabindex="-1" href="javascript:">Empty</a></li>';
			}
		}
		else{
			$client_name = '';
			$output = '';
		}


		$task_name_details = DB::table('time_task')->where('id',$job->task_id)->first();

		if($job->active_id == 0)
		{
			$quick_details = DB::table('task_job')->where('active_id',$job->id)->orderBy('start_time','asc')->first();
			if(count($quick_details))
			{
				$quick_start_time = $quick_details->start_time;
			}
			else{
				$quick_start_time = '';
			}
			$active_start_time = '';
			$active_job_time = '';
		}
		else{
			$active_details = DB::table('task_job')->where('id',$job->active_id)->first();
			$active_start_time = $active_details->start_time;
			$active_job_time = $active_details->job_time;
			$quick_start_time = '';
		}

		$explode_job_minutes = explode(":",$job->job_time);
                    $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
		echo json_encode(array('id' => $job->id,'client_id' => $job->client_id,'user_id' => $job->user_id,'task_id' => $job->task_id,'start_time' => date('H:i', strtotime($job->start_time)),'stop_time' => date('H:i', strtotime($job->stop_time)),'job_time' => $job->job_time.' ('.$total_minutes.')','job_date' => date('d-M-Y', strtotime($job->job_date)),'job_type' => $job->job_type,'quick_job' => $job->quick_job,'job_created_date' => $job->job_created_date,'comments' => $job->comments,'updated' => $job->updated,'status' => $job->status,'client_name' => $client_name,'tasks_group' => $output, 'task_name' => $task_name_details->task_name, 'active_id' => $job->active_id, 'breaktime' => $breaktime,'quick_job_times' => $quick_job_times,'total_time_minutes_format' => $total_time_minutes_format,'alert' => $alert,'total_breaks_minutes' => $total_breaks_minutes,'active_start_time' => $active_start_time,'quick_start_time' => $quick_start_time,'active_job_time' => $active_job_time,'stoptime_till_val' => $stoptime_till_val));
	}

	public function edit_time_job(){
		$jobid = Input::get('jobid');
		$job = DB::table('task_job')->where('id',$jobid)->first();
		if($job->quick_job == 1)
		{
			$jobidd = $job->active_id;
		}
		else{
			$jobidd = $job->id;
		}
		$job_details_child = DB::table('task_job')->where('active_id', $jobidd)->get();
		if(count($job_details_child))
		{
			$job_times = 0;
			foreach($job_details_child as $child)
			{
				$time = explode(':', $child->job_time);
				$minutes = ($time[0]*60) + ($time[1]) + ($time[2]/60);
				if($job_times == 0)
				{
					$job_times = $minutes;
				}
				else{
					$job_times = $job_times + $minutes;
				}
			}
			if($job_times > 0)
			{
			  if(floor($job_times / 60) <= 9)
	          {
	            $h = '0'.floor($job_times / 60);
	          }
	          else{
	            $h = floor($job_times / 60);
	          }
	          if(($job_times -   floor($job_times / 60) * 60) <= 9)
	          {
	            $m = '0'.($job_times -   floor($job_times / 60) * 60);
	          }
	          else{
	            $m = ($job_times -   floor($job_times / 60) * 60);
	          }
	          $quick_job_times = $h.':'.$m.':00';
	      	}
	      	else{
	      		$quick_job_times = '00:00:00';
	      	}
		}
		else{
			$quick_job_times = '00:00:00';
		}



		$total_quick_jobs = $quick_job_times;
		if($total_quick_jobs == "" || $total_quick_jobs == "00:00:00")
        {
        	$total_quick_jobs_minutes = 0;
        }
        else{
        	$total_quick_jobs_1 = explode(':', $total_quick_jobs);
			$minutes = ($total_quick_jobs_1[0]*60) + ($total_quick_jobs_1[1]) + ($total_quick_jobs_1[2]/60);
			$total_quick_jobs_minutes = $minutes;
        }
        $break_time = DB::table('job_break_time')->where('job_id', $jobidd)->first();

      
		if(count($break_time)){
			$breaktime = $break_time->break_time;
			$total_break_time_1 = explode(':', $breaktime);
			$break_minutes = ($total_break_time_1[0]*60) + ($total_break_time_1[1]) + ($total_break_time_1[2]/60);
			$total_breaks_minutes = $break_minutes;
		}
		else{
			$breaktime = '';
			$total_breaks_minutes = 0;
		}

		$sum_of_breaks = $total_breaks_minutes + $total_quick_jobs_minutes;

		if($job->quick_job == 1)
		{
			$jobtime_calc = DB::table('task_job')->where('id',$job->active_id)->first();
			$jobtime_1 = explode(':', $jobtime_calc->job_time);
		}
		else{
			$jobtime_1 = explode(':', $job->job_time);
		}
		$job_minutes = ($jobtime_1[0]*60) + ($jobtime_1[1]) + ($jobtime_1[2]/60);
		$jobtime_minutes = $job_minutes;

		$total_time_minutes = $jobtime_minutes - $sum_of_breaks;

		if($job->quick_job == 1)
		{
			$till_stoptime = explode(':', $job->stop_time);
			$stop_minutes = ($till_stoptime[0]*60) + ($till_stoptime[1]) + ($till_stoptime[2]/60);
			$stoptime_till = $stop_minutes + $total_time_minutes;

			if(floor($stoptime_till / 60) <= 9)
	          {
	            $hs = '0'.floor($stoptime_till / 60);
	          }
	          else{
	            $hs = floor($stoptime_till / 60);
	          }
	          if(($stoptime_till -   floor($stoptime_till / 60) * 60) <= 9)
	          {
	            $ms = '0'.($stoptime_till -   floor($stoptime_till / 60) * 60);
	          }
	          else{
	            $ms = ($stoptime_till -   floor($stoptime_till / 60) * 60);
	          }
	          $stoptime_till_val = $hs.':'.$ms;
		}
		else{
			$stoptime_till_val = '00:00';
		}

		if($total_time_minutes < 0)
		{
			if(floor($total_time_minutes / 60) <= 9)
	          {
	            $h = floor($total_time_minutes / 60);
	            $h = str_replace("-","",$h);
	          }
	          else{
	            $h = floor($total_time_minutes / 60);
	            $h = str_replace("-","",$h);
	          }
	          if(($total_time_minutes -   floor($total_time_minutes / 60) * 60) <= 9)
	          {
	            $m = '0'.($total_time_minutes -   floor($total_time_minutes / 60) * 60);
	          }
	          else{
	            $m = ($total_time_minutes -   floor($total_time_minutes / 60) * 60);
	          }
	          $total_time_minutes_format = '-'.$h.':'.$m.':00';
	          $alert =1;
		}
		else{
			if(floor($total_time_minutes / 60) <= 9)
	          {
	            $h = '0'.floor($total_time_minutes / 60);
	          }
	          else{
	            $h = floor($total_time_minutes / 60);
	          }
	          if(($total_time_minutes -   floor($total_time_minutes / 60) * 60) <= 9)
	          {
	            $m = '0'.($total_time_minutes -   floor($total_time_minutes / 60) * 60);
	          }
	          else{
	            $m = ($total_time_minutes -   floor($total_time_minutes / 60) * 60);
	          }
	          $total_time_minutes_format = $h.':'.$m.':00';
	          $alert =0;
		}

		if($job->client_id != "")
		{
			$client_details = DB::table('cm_clients')->where('client_id',$job->client_id)->first();
			$client_name = $client_details->company.'-'.$client_details->client_id;

			$tasks = DB::table('time_task')->where('clients','like','%'.$job->client_id.'%')->get();
		
			$output = ' <li><a tabindex="-1" href="javascript:" class="tasks_li">Select Task</a></li>';
			if(count($tasks)){
				foreach ($tasks as $single_task) {
					if($single_task->task_type == 0){
						$icon = '<i class="fa fa-desktop" style="margin-right:10px;"></i>';
					}
					else if($single_task->task_type == 1){
						$icon = '<i class="fa fa-users" style="margin-right:10px;"></i>';
					}
					else{
						$icon = '<i class="fa fa-globe" style="margin-right:10px;"></i>';
					}

					$output.= '<li><a tabindex="-1" href="javascript:" class="tasks_li" data-element="'.$single_task->id.'">'.$icon.$single_task->task_name.'</a></li>';
				}
			}
			else{
				$output.= '<li><a tabindex="-1" href="javascript:">Empty</a></li>';
			}
		}
		else{
			$client_name = '';
			$output = '';
		}


		$task_name_details = DB::table('time_task')->where('id',$job->task_id)->first();

		if($job->active_id == 0)
		{
			$quick_details = DB::table('task_job')->where('active_id',$job->id)->orderBy('start_time','asc')->first();
			if(count($quick_details))
			{
				$quick_start_time = $quick_details->start_time;
			}
			else{
				$quick_start_time = '';
			}
			$active_start_time = '';
			$active_job_time = '';
		}
		else{
			$active_details = DB::table('task_job')->where('id',$job->active_id)->first();
			$active_start_time = $active_details->start_time;
			$active_job_time = $active_details->job_time;
			$quick_start_time = '';
		}

		$explode_job_minutes = explode(":",$job->job_time);
                    $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
		echo json_encode(array('id' => $job->id,'client_id' => $job->client_id,'user_id' => $job->user_id,'task_id' => $job->task_id,'start_time' => date('H:i', strtotime($job->start_time)),'stop_time' => date('H:i', strtotime($job->stop_time)),'job_time' => $job->job_time,'job_date' => date('d-M-Y', strtotime($job->job_date)),'job_type' => $job->job_type,'quick_job' => $job->quick_job,'job_created_date' => $job->job_created_date,'comments' => $job->comments,'updated' => $job->updated,'status' => $job->status,'client_name' => $client_name,'tasks_group' => $output, 'task_name' => $task_name_details->task_name, 'active_id' => $job->active_id, 'breaktime' => $breaktime,'quick_job_times' => $quick_job_times,'total_time_minutes_format' => $total_time_minutes_format,'alert' => $alert,'total_breaks_minutes' => $total_breaks_minutes,'active_start_time' => $active_start_time,'quick_start_time' => $quick_start_time,'active_job_time' => $active_job_time,'stoptime_till_val' => $stoptime_till_val));
	}
	public function active_job_report_csv(){
		
		$ids = explode(',', Input::get('value'));

		
		$job_details = DB::table('task_job')->whereIn('id', $ids)->get();
		



		$headers = array(
	        "Content-type" => "text/csv",
	        "Content-Disposition" => "attachment; filename=CM_Report.csv",
	        "Pragma" => "no-cache",
	        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
	        "Expires" => "0"
	    );      	

		$columns = array('#', 'User', 'Client Name', 'Task Name', 'Task Type', 'Date', 'Start Time', 'Job Time');
		$title = 'Active Job';
		$title_row = array('', '', '', $title, '', '', '', '');
		$callback = function() use ($job_details, $columns, $title_row)
    	{
	       	$file = fopen('job_file/active_job_Report.csv', 'w');	       	
	       	fputcsv($file, $title_row);
		    fputcsv($file, $columns);
			$i=1;
			foreach ($job_details as $single) {
				if($single->client_id != ''){
					$company_details = DB::table('cm_clients')->where('client_id', $single->client_id)->first();
					$companyname = $company_details->company;
				}
				else{
					$companyname = 'N/A';
				}				
				$user_details = DB::table('user')->where('user_id', $single->user_id)->first();
				$task_details = DB::table('time_task')->where('id', $single->task_id)->first();

				if($task_details->task_type == 0){
					$task_type = 'Internal Task';
				}
				else if($task_details->task_type == 1){
					$task_type = 'Client Task';
				}
				else{
					$task_type = 'Global Task';
				}


				$jobs = DB::table('task_job')->where('id', $single->id)->first();


				//-----------Job Time Start----------------

		        $created_date = $jobs->job_created_date;

                $jobstart = strtotime($created_date.' '.$jobs->start_time);
                $jobend   = strtotime($created_date.' '.date('H:i:s'));
                

                if($jobend < $jobstart)
                {
                  $todate = date('Y-m-d', strtotime("+1 day", $jobend));
                  $jobend   = strtotime($todate.' '.date('H:i:s'));
                }

                $jobdiff  = $jobend - $jobstart;



		        $hours = floor($jobdiff / (60 * 60));
		        $minutes = $jobdiff - $hours * (60 * 60);
		        $minutes = floor( $minutes / 60 );
		        $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
		        if($hours <= 9)
		        {
		          $hours = '0'.$hours;
		        }
		        else{
		          $hours = $hours;
		        }
		        if($minutes <= 9)
		        {
		          $minutes = '0'.$minutes;
		        }
		        else{
		          $minutes = $minutes;
		        }
		        if($second <= 9)
		        {
		          $second = '0'.$second;
		        }
		        else{
		          $second = $second;
		        }

		        $jobtime =   $hours.':'.$minutes.':'.$second;
		      	$columns_2 = array($i, $user_details->lastname.' '.$user_details->firstname, $companyname, $task_details->task_name, $task_type, date('d-M-Y', strtotime($single->job_date)), date('H:i:s', strtotime($single->start_time)), $jobtime );
				fputcsv($file, $columns_2);
				
				$i++;
			}
			fclose($file);
		};
		return Response::stream($callback, 200, $headers);
		//return $filename.'_InvoiceReport.csv';

	}

	public function active_jobreportpdf(){
		
		$ids = explode(',', Input::get('value'));			
		$joblist = DB::table('task_job')->whereIn('id', $ids)->get();

		$output='<style>
		  .table_style {
		      width: 100%;
		      border-collapse:collapse;
		      border:1px solid #c5c5c5;
		  }
		</style>
		<h3 id="pdf_title_all_ivoice" style="width: 100%; text-align: center; margin: 15px 0px; float: left;">Active Job</h3>
		<table class="table_style">
		    <thead>
		      <tr style="background: #fff;">        
		        <th width="2%" style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">S.No</th>
		        <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">User Name</th>
		        <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Client Name</th>
		        <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Task Name</th>
		        <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Task Type</th>
		        <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Date</th>
		        <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Start Time</th>
		        <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Job Time</th>             
		    </tr>
		    </thead>
		    <tbody id="report_pdf_type_two_tbody">';
        $i=1;            
        if(count($joblist)){              
          foreach ($joblist as $jobs) {
              if($jobs->status == 0){
                $client_details = DB::table('cm_clients')->where('client_id', $jobs->client_id)->first();
                if(count($client_details) != ''){
                  $client_name = $client_details->company.' ('.$jobs->client_id.')';
                }
                else{
                  $client_name = 'N/A';
                }

                $task_details = DB::table('time_task')->where('id', $jobs->task_id)->first();

                if(count($task_details) != ''){
                  $task_name = $task_details->task_name;
                  $task_type = $task_details->task_type;

                  if($task_type == 0){
                    $task_type = '<i class="fa fa-desktop" style="margin-right:10px;"></i> Internal Task';
                  }
                  else if($task_type == 1){
                    $task_type = '<i class="fa fa-users" style="margin-right:10px;"></i> Client Task';
                  }
                  else{
                    $task_type = '<i class="fa fa-globe" style="margin-right:10px;"></i> Global Task';
                  }
                }
                else{
                  $task_name = 'N/A';
                  $task_type = 'N/A';
                }

                $break_time_count = DB::table('job_break_time')->where('job_id', $jobs->id)->get();
                                    
                

                $user_details = DB::table('user')->where('user_id', $jobs->user_id)->first();

                //-----------Job Time Start----------------

                $created_date = $jobs->job_created_date;

	            $jobstart = strtotime($created_date.' '.$jobs->start_time);
	            $jobend   = strtotime($created_date.' '.date('H:i:s'));
	            

	            if($jobend < $jobstart)
	            {
	              $todate = date('Y-m-d', strtotime("+1 day", $jobend));
	              $jobend   = strtotime($todate.' '.date('H:i:s'));
	            }

	            $jobdiff  = $jobend - $jobstart;



                $hours = floor($jobdiff / (60 * 60));
                $minutes = $jobdiff - $hours * (60 * 60);
                $minutes = floor( $minutes / 60 );
                $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
                if($hours <= 9)
                {
                  $hours = '0'.$hours;
                }
                else{
                  $hours = $hours;
                }
                if($minutes <= 9)
                {
                  $minutes = '0'.$minutes;
                }
                else{
                  $minutes = $minutes;
                }
                if($second <= 9)
                {
                  $second = '0'.$second;
                }
                else{
                  $second = $second;
                }

                $jobtime =   $hours.':'.$minutes.':'.$second;
                $explode_job_minutes = explode(":",$jobtime);
                    $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);

                //-----------Job Time End----------------
                

        $output.='
        <tr>          
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$i.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$user_details->lastname.' '.$user_details->firstname.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$client_name.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$task_name.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$task_type.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.date('H:i:s', strtotime($jobs->start_time)).'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">

          <span id="job_time_refresh_'.$jobs->id.'">'.$jobtime.' ('.$total_minutes.')</span> &nbsp;&nbsp;<a href="javascript:"><i class="fa fa-refresh job_time_refresh" aria-hidden="true" data-element="'.$jobs->id.'"></i></a>

          </td>
          
          
        </tr>';
        
          $i++;
              }
          }              
        }
        if($i == 1){
          $output.= '<tr>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left"></td>                        
                    <td align="left"></td>
                    <td align="center">Empty</td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left"></td>
                    </tr>';
        }
        $output.='</tbody>
		</table>';
        $pdf = PDF::loadHTML($output);
		$pdf->setPaper('A4', 'landscape');
		$pdf->save('job_file/Active Job Report.pdf');
		echo 'Active Job Report.pdf';
	}


	public function active_jobreportpdfdownload(){

		$htmlval = Input::get('htmlval');
		$pdf = PDF::loadHTML($htmlval);
		$pdf->setPaper('A4', 'landscape');
		$pdf->save('job_file/Active Job Report.pdf');
		echo 'Active Job Report.pdf';


	}


	public function all_job_report_csv(){
		
		$ids = explode(',', Input::get('value'));

		
		$job_details = DB::table('task_job')->whereIn('id', $ids)->get();
		



		$headers = array(
	        "Content-type" => "text/csv",
	        "Content-Disposition" => "attachment; filename=CM_Report.csv",
	        "Pragma" => "no-cache",
	        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
	        "Expires" => "0"
	    );      	

		$columns = array('#', 'User', 'Client Name', 'Task Name', 'Task Type', 'Date', 'Start Time', 'Job Time');
		$callback = function() use ($job_details, $columns)
    	{
	       	$file = fopen('job_file/all_job_Report.csv', 'w');
		    fputcsv($file, $columns);
			$i=1;
			if(count($job_details)) {
			foreach ($job_details as $single) {				

				

				if($single->client_id != ''){
					$company_details = DB::table('cm_clients')->where('client_id', $single->client_id)->first();
					$companyname = $company_details->company;
				}
				else{
					$companyname = 'N/A';
				}				
				$user_details = DB::table('user')->where('user_id', $single->user_id)->first();
				$task_details = DB::table('time_task')->where('id', $single->task_id)->first();
				if(count($task_details)) {
					if($task_details->task_type == 0){
						$task_type = 'Internal Task';
					}
					else if($task_details->task_type == 1){
						$task_type = 'Client Task';
					}
					else{
						$task_type = 'Global Task';
					}
					$taskname = $task_details->task_name;
				}
				else{
					$task_type ='N/A';
					$taskname = 'N/A';
				}

				if(count($task_details)) {
					$userfirstname = $user_details->lastname.' '.$user_details->firstname;
				}
				else{
					$userfirstname = 'N/A';
				}

				$jobs = DB::table('task_job')->where('id', $single->id)->first();


				//-----------Job Time Start----------------

		        $created_date = $jobs->job_created_date;

                $jobstart = strtotime($created_date.' '.$jobs->start_time);
                $jobend   = strtotime($created_date.' '.date('H:i:s'));
                

                if($jobend < $jobstart)
                {
                  $todate = date('Y-m-d', strtotime("+1 day", $jobend));
                  $jobend   = strtotime($todate.' '.date('H:i:s'));
                }

                $jobdiff  = $jobend - $jobstart;



		        $hours = floor($jobdiff / (60 * 60));
		        $minutes = $jobdiff - $hours * (60 * 60);
		        $minutes = floor( $minutes / 60 );
		        $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
		        if($hours <= 9)
		        {
		          $hours = '0'.$hours;
		        }
		        else{
		          $hours = $hours;
		        }
		        if($minutes <= 9)
		        {
		          $minutes = '0'.$minutes;
		        }
		        else{
		          $minutes = $minutes;
		        }
		        if($second <= 9)
		        {
		          $second = '0'.$second;
		        }
		        else{
		          $second = $second;
		        }

		        $jobtime =   $hours.':'.$minutes.':'.$second;

		        //-----------Job Time End----------------

		          if($jobs->status == 0){
	                $job_time_checked = $jobtime;
	              }
	              else if($jobs->status == 1){
	                  $get_quick_jobs = DB::table('task_job')->where('active_id',$jobs->id)->get();
	                    $quick_minutes = 0;
	                    if(count($get_quick_jobs))
	                    {
	                      foreach($get_quick_jobs as $quickjobs_single)
	                      {
	                        $total_quick_jobs_1 = explode(':', $quickjobs_single->job_time);
	                        $minutes = ($total_quick_jobs_1[0]*60) + ($total_quick_jobs_1[1]) + ($total_quick_jobs_1[2]/60);
	                        if($quick_minutes == 0)
	                        {
	                          $quick_minutes = $minutes;
	                        }
	                        else{
	                          $quick_minutes = $quick_minutes + $minutes;
	                        }
	                      }
	                    }

	                    $break_time_min = DB::table('job_break_time')->where('job_id',$jobs->id)->where('break_time','!=','00:00:00')->first();
                        $break_timee_minutes = 0;
                        if(count($break_time_min))
                        {
                          $break_timee = explode(':', $break_time_min->break_time);
                          $break_timee_minutes = ($break_timee[0]*60) + ($break_timee[1]) + ($break_timee[2]/60);
                        }


                        $quick_minutes = $quick_minutes + $break_timee_minutes;

	                    $job_timee = explode(':', $jobs->job_time);
	                    $job_timee_minutes = ($job_timee[0]*60) + ($job_timee[1]) + ($job_timee[2]/60);

	                    $job_time_min = $job_timee_minutes - $quick_minutes;

	                    if(floor($job_time_min / 60) <= 9)
	                    {
	                      $h = '0'.floor($job_time_min / 60);
	                    }
	                    else{
	                      $h = floor($job_time_min / 60);
	                    }
	                    if(($job_time_min -   floor($job_time_min / 60) * 60) <= 9)
	                    {
	                      $m = '0'.($job_time_min -   floor($job_time_min / 60) * 60);
	                    }
	                    else{
	                      $m = ($job_time_min -   floor($job_time_min / 60) * 60);
	                    }
	                    $job_time_checked = $h.':'.$m.':00';
	              }
	              else{
	                $job_time_checked = 'N/A';
	              }




		      	$columns_2 = array($i, $userfirstname, $companyname, $taskname, $task_type, date('d-M-Y', strtotime($single->job_date)), date('H:i:s', strtotime($single->start_time)), $job_time_checked );
				fputcsv($file, $columns_2);
				$job_details_child = DB::table('task_job')->where('active_id', $single->id)->get();
				$ichild=1;
			if(count($job_details_child)) {
			foreach ($job_details_child as $child) {				

				

				if($child->client_id != ''){
					$company_details = DB::table('cm_clients')->where('client_id', $child->client_id)->first();
					$companyname = $company_details->company;
				}
				else{
					$companyname = 'N/A';
				}				
				$user_details = DB::table('user')->where('user_id', $child->user_id)->first();
				$task_details = DB::table('time_task')->where('id', $child->task_id)->first();
				if(count($task_details)) {
					if($task_details->task_type == 0){
						$task_type = 'Internal Task';
					}
					else if($task_details->task_type == 1){
						$task_type = 'Client Task';
					}
					else{
						$task_type = 'Global Task';
					}
					$taskname = $task_details->task_name;
				}
				else{
					$task_type ='N/A';
					$taskname = 'N/A';
				}

				if(count($task_details)) {
					$userfirstname = $user_details->lastname.' '.$user_details->firstname;
				}
				else{
					$userfirstname = 'N/A';
				}

				$jobs = DB::table('task_job')->where('id', $child->id)->first();


				//-----------Job Time Start----------------

		        $created_date = $jobs->job_created_date;

                $jobstart = strtotime($created_date.' '.$jobs->start_time);
                $jobend   = strtotime($created_date.' '.date('H:i:s'));
                

                if($jobend < $jobstart)
                {
                  $todate = date('Y-m-d', strtotime("+1 day", $jobend));
                  $jobend   = strtotime($todate.' '.date('H:i:s'));
                }

                $jobdiff  = $jobend - $jobstart;



		        $hours = floor($jobdiff / (60 * 60));
		        $minutes = $jobdiff - $hours * (60 * 60);
		        $minutes = floor( $minutes / 60 );
		        $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
		        if($hours <= 9)
		        {
		          $hours = '0'.$hours;
		        }
		        else{
		          $hours = $hours;
		        }
		        if($minutes <= 9)
		        {
		          $minutes = '0'.$minutes;
		        }
		        else{
		          $minutes = $minutes;
		        }
		        if($second <= 9)
		        {
		          $second = '0'.$second;
		        }
		        else{
		          $second = $second;
		        }

		        $jobtime =   $hours.':'.$minutes.':'.$second;

		        //-----------Job Time End----------------

		        if($jobs->status == 0){
		        	$job_time_checked = $jobtime;
		        }
		        else{
		        	$job_time_checked = $jobs->job_time;
		        }
		        
		      	$columns_2 = array($i.'.'.$ichild, $userfirstname, $companyname, $taskname, $task_type, date('d-M-Y', strtotime($child->job_date)), date('H:i:s', strtotime($child->start_time)), $job_time_checked );
				fputcsv($file, $columns_2);
				$ichild++;
			}
		}
				$i++;
			}
			}
			fclose($file);
		};
		return Response::stream($callback, 200, $headers);
		//return $filename.'_InvoiceReport.csv';

	}


	public function all_jobreportpdf(){
		
		$ids = explode(',', Input::get('value'));			
		$joblist = DB::table('task_job')->whereIn('id', $ids)->get();

		$output='';
        $i=1;            
        if(count($joblist)){              
          foreach ($joblist as $jobs) {
            
                $client_details = DB::table('cm_clients')->where('client_id', $jobs->client_id)->first();
                if(count($client_details) != ''){
                  $client_name = $client_details->company.' ('.$jobs->client_id.')';
                }
                else{
                  $client_name = 'N/A';
                }

                $task_details = DB::table('time_task')->where('id', $jobs->task_id)->first();

                if(count($task_details) != ''){
                  $task_name = $task_details->task_name;
                  $task_type = $task_details->task_type;

                  if($task_type == 0){
                    $task_type = '<i class="fa fa-desktop" style="margin-right:10px;"></i> Internal Task';
                  }
                  else if($task_type == 1){
                    $task_type = '<i class="fa fa-users" style="margin-right:10px;"></i> Client Task';
                  }
                  else{
                    $task_type = '<i class="fa fa-globe" style="margin-right:10px;"></i> Global Task';
                  }
                }
                else{
                  $task_name = 'N/A';
                  $task_type = 'N/A';
                }

               

                $user_details = DB::table('user')->where('user_id', $jobs->user_id)->first();

                //-----------Job Time Start----------------

                $created_date = $jobs->job_created_date;

                $jobstart = strtotime($created_date.' '.$jobs->start_time);
                $jobend   = strtotime($created_date.' '.date('H:i:s'));
                

                if($jobend < $jobstart)
                {
                  $todate = date('Y-m-d', strtotime("+1 day", $jobend));
                  $jobend   = strtotime($todate.' '.date('H:i:s'));
                }

                $jobdiff  = $jobend - $jobstart;



                $hours = floor($jobdiff / (60 * 60));
                $minutes = $jobdiff - $hours * (60 * 60);
                $minutes = floor( $minutes / 60 );
                $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
                if($hours <= 9)
                {
                  $hours = '0'.$hours;
                }
                else{
                  $hours = $hours;
                }
                if($minutes <= 9)
                {
                  $minutes = '0'.$minutes;
                }
                else{
                  $minutes = $minutes;
                }
                if($second <= 9)
                {
                  $second = '0'.$second;
                }
                else{
                  $second = $second;
                }

                $jobtime =   $hours.':'.$minutes.':'.$second;

                //-----------Job Time End----------------


                if($jobs->status == 0){
	                $job_time_checked = $jobtime;
	              }
	              else if($jobs->status == 1){
	                  $get_quick_jobs = DB::table('task_job')->where('active_id',$jobs->id)->get();
	                    $quick_minutes = 0;
	                    if(count($get_quick_jobs))
	                    {
	                      foreach($get_quick_jobs as $quickjobs_single)
	                      {
	                        $total_quick_jobs_1 = explode(':', $quickjobs_single->job_time);
	                        $minutes = ($total_quick_jobs_1[0]*60) + ($total_quick_jobs_1[1]) + ($total_quick_jobs_1[2]/60);
	                        if($quick_minutes == 0)
	                        {
	                          $quick_minutes = $minutes;
	                        }
	                        else{
	                          $quick_minutes = $quick_minutes + $minutes;
	                        }
	                      }
	                    }

	                    $break_time_min = DB::table('job_break_time')->where('job_id',$jobs->id)->where('break_time','!=','00:00:00')->first();
                        $break_timee_minutes = 0;
                        if(count($break_time_min))
                        {
                          $break_timee = explode(':', $break_time_min->break_time);
                          $break_timee_minutes = ($break_timee[0]*60) + ($break_timee[1]) + ($break_timee[2]/60);
                        }


                        $quick_minutes = $quick_minutes + $break_timee_minutes;

	                    $job_timee = explode(':', $jobs->job_time);
	                    $job_timee_minutes = ($job_timee[0]*60) + ($job_timee[1]) + ($job_timee[2]/60);

	                    $job_time_min = $job_timee_minutes - $quick_minutes;

	                    if(floor($job_time_min / 60) <= 9)
	                    {
	                      $h = '0'.floor($job_time_min / 60);
	                    }
	                    else{
	                      $h = floor($job_time_min / 60);
	                    }
	                    if(($job_time_min -   floor($job_time_min / 60) * 60) <= 9)
	                    {
	                      $m = '0'.($job_time_min -   floor($job_time_min / 60) * 60);
	                    }
	                    else{
	                      $m = ($job_time_min -   floor($job_time_min / 60) * 60);
	                    }
	                    $job_time_checked = $h.':'.$m.':00';
	              }
	              else{
	                $job_time_checked = 'N/A';
	              }
                

        $output.='
        <tr>          
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$i.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$user_details->lastname.' '.$user_details->firstname.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$client_name.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$task_name.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$task_type.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.date('H:i', strtotime($jobs->start_time)).'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.date('H:i', strtotime($jobs->stop_time)).'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">

          '.$job_time_checked.'

          </td>
          
          
        </tr>';
        $joblist_child = DB::table('task_job')->where('active_id', $jobs->id)->get();
        $ichild=1;            
        if(count($joblist_child)){              
          foreach ($joblist_child as $child) {
            
                $client_details = DB::table('cm_clients')->where('client_id', $child->client_id)->first();
                if(count($client_details) != ''){
                  $client_name = $client_details->company.' ('.$child->client_id.')';
                }
                else{
                  $client_name = 'N/A';
                }

                $task_details = DB::table('time_task')->where('id', $child->task_id)->first();

                if(count($task_details) != ''){
                  $task_name = $task_details->task_name;
                  $task_type = $task_details->task_type;

                  if($task_type == 0){
                    $task_type = '<i class="fa fa-desktop" style="margin-right:10px;"></i> Internal Task';
                  }
                  else if($task_type == 1){
                    $task_type = '<i class="fa fa-users" style="margin-right:10px;"></i> Client Task';
                  }
                  else{
                    $task_type = '<i class="fa fa-globe" style="margin-right:10px;"></i> Global Task';
                  }
                }
                else{
                  $task_name = 'N/A';
                  $task_type = 'N/A';
                }

               

                $user_details = DB::table('user')->where('user_id', $child->user_id)->first();

                //-----------Job Time Start----------------

                $created_date = $child->job_created_date;

                $jobstart = strtotime($created_date.' '.$child->start_time);
                $jobend   = strtotime($created_date.' '.date('H:i:s'));
                

                if($jobend < $jobstart)
                {
                  $todate = date('Y-m-d', strtotime("+1 day", $jobend));
                  $jobend   = strtotime($todate.' '.date('H:i:s'));
                }

                $jobdiff  = $jobend - $jobstart;



                $hours = floor($jobdiff / (60 * 60));
                $minutes = $jobdiff - $hours * (60 * 60);
                $minutes = floor( $minutes / 60 );
                $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
                if($hours <= 9)
                {
                  $hours = '0'.$hours;
                }
                else{
                  $hours = $hours;
                }
                if($minutes <= 9)
                {
                  $minutes = '0'.$minutes;
                }
                else{
                  $minutes = $minutes;
                }
                if($second <= 9)
                {
                  $second = '0'.$second;
                }
                else{
                  $second = $second;
                }

                $jobtime =   $hours.':'.$minutes.':'.$second;

                //-----------Job Time End----------------


                if($child->status == 0){
		        	$job_time_checked = $jobtime;
		        }
		        else{
		        	$job_time_checked = $child->job_time;
		        }
                

        $output.='
        <tr>          
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$i.'.'.$ichild.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$user_details->lastname.' '.$user_details->firstname.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$client_name.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$task_name.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$task_type.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.date('d-M-Y', strtotime($child->job_date)).'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.date('H:i', strtotime($child->start_time)).'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.date('H:i', strtotime($child->stop_time)).'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">

          '.$job_time_checked.'

          </td>
          
          
        </tr>';
        $ichild++;
    }
}
          $i++;
              
          }              
        }
        if($i == 1){
          $output.= '<tr>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left"></td>                        
                    <td align="left"></td>
                    <td align="center">Empty</td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left"></td>
                    </tr>';
        }
        echo $output;
        
	}


	public function all_jobreportpdfdownload(){

		$htmlval = Input::get('htmlval');
		$pdf = PDF::loadHTML($htmlval);
		$pdf->setPaper('A4', 'landscape');
		$pdf->save('job_file/All Job Report.pdf');
		echo 'All Job Report.pdf';
	}





	public function jobtimecountrefresh(){
		$id = Input::get("id");
		
		$jobs = DB::table('task_job')->where('id', $id)->first();

		//-----------Job Time Start----------------

        $created_date = $jobs->job_created_date;

        $jobstart = strtotime($created_date.' '.$jobs->start_time);
        $jobend   = strtotime($created_date.' '.date('H:i:s'));
        
        if($jobend < $jobstart)
	    {
	        if($created_date == date('Y-m-d'))
	        {
	            $childnegative = '- ';
	            $jobdiff  = $jobstart - $jobend;
	        }
	        else{
	          $childnegative = '';
	          $todate = date('Y-m-d', strtotime("+1 day", $jobend));
	          $jobend   = strtotime($todate.' '.date('H:i:s'));
	          $jobdiff  = $jobend - $jobstart;
	        }
	    }
	    else{
	        $childnegative = '';
	        $jobdiff  = $jobend - $jobstart;
	    }
        $hours = floor($jobdiff / (60 * 60));
        $minutes = $jobdiff - $hours * (60 * 60);
        $minutes = floor( $minutes / 60 );
        $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
        if($hours <= 9)
        {
          $hours = '0'.$hours;
        }
        else{
          $hours = $hours;
        }
        if($minutes <= 9)
        {
          $minutes = '0'.$minutes;
        }
        else{
          $minutes = $minutes;
        }
        if($second <= 9)
        {
          $second = '0'.$second;
        }
        else{
          $second = $second;
        }

        $jobtime =   $childnegative.$hours.':'.$minutes.':'.$second;
        $explode_job_minutes = explode(":",$jobtime);
                    $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);

        //-----------Job Time End----------------

        $ratelist = DB::table('user_cost')->where('user_id', $jobs->user_id)->get();

        $rate_result = '0';
        $cost = '0';

        if(count($ratelist)){
          foreach ($ratelist as $rate) {
            $job_date = strtotime($jobs->job_date);
            $from_date = strtotime($rate->from_date);
            $to_date = strtotime($rate->to_date);

            if($rate->to_date != '0000-00-00'){                         
              if($job_date >= $from_date  && $job_date <= $to_date){
                
                $rate_result = $rate->cost;                            
                $cost = ($rate_result/60)*$total_minutes;
              }
            }
            else{
              if($job_date >= $from_date){
                $rate_result = $rate->cost;
                $cost = ($rate_result/60)*$total_minutes;
              }
            }
          }                      
        }


        echo json_encode(array('id' => $jobs->id, 'refreshcount' => $jobtime.' ('.$total_minutes.')', 'bep' => number_format_invoice_without_decimal($cost) ));
	}

	public function joboftheday_report_csv(){
		
		$ids = explode(',', Input::get('value'));
		
		$job_details = DB::table('task_job')->whereIn('id', $ids)->get();

		$headers = array(
	        "Content-type" => "text/csv",
	        "Content-Disposition" => "attachment; filename=CM_Report.csv",
	        "Pragma" => "no-cache",
	        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
	        "Expires" => "0"
	    );      	

		$columns = array('#', 'User', 'Client Name', 'Task Name', 'Task Type', 'Date', 'Start Time','Stop Time', 'Job Time');
		$current_date = date('d-M-Y');
		$title = 'Jobs of the Day - '.$current_date;
		$title_row = array('', '', '', '', $title, '', '','', '');
		$callback = function() use ($job_details, $columns, $title_row)
    	{
	       	$file = fopen('job_file/joboftheday_Report.csv', 'w');
	       	fputcsv($file, $title_row);
		    fputcsv($file, $columns);
			$i=1;
			foreach ($job_details as $single) {				

				

				if($single->client_id != ''){
					$company_details = DB::table('cm_clients')->where('client_id', $single->client_id)->first();
					$companyname = $company_details->company;
				}
				else{
					$companyname = 'N/A';
				}				
				$user_details = DB::table('user')->where('user_id', $single->user_id)->first();
				$task_details = DB::table('time_task')->where('id', $single->task_id)->first();

				if($task_details->task_type == 0){
					$task_type = 'Internal Task';
				}
				else if($task_details->task_type == 1){
					$task_type = 'Client Task';
				}
				else{
					$task_type = 'Global Task';
				}


				$jobs = DB::table('task_job')->where('id', $single->id)->first();


				//-----------Job Time Start----------------

		        $created_date = $jobs->job_created_date;

                $jobstart = strtotime($created_date.' '.$jobs->start_time);
                $jobend   = strtotime($created_date.' '.date('H:i:s'));
                

                if($jobend < $jobstart)
                {
                  $todate = date('Y-m-d', strtotime("+1 day", $jobend));
                  $jobend   = strtotime($todate.' '.date('H:i:s'));
                }

                $jobdiff  = $jobend - $jobstart;



		        $hours = floor($jobdiff / (60 * 60));
		        $minutes = $jobdiff - $hours * (60 * 60);
		        $minutes = floor( $minutes / 60 );
		        $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
		        if($hours <= 9)
		        {
		          $hours = '0'.$hours;
		        }
		        else{
		          $hours = $hours;
		        }
		        if($minutes <= 9)
		        {
		          $minutes = '0'.$minutes;
		        }
		        else{
		          $minutes = $minutes;
		        }
		        if($second <= 9)
		        {
		          $second = '0'.$second;
		        }
		        else{
		          $second = $second;
		        }

		        $jobtime =   $hours.':'.$minutes.':'.$second;

		        //-----------Job Time End----------------

		        if($jobs->status == 0){
	                $job_time_checked = $jobtime;
	              }
	              else if($jobs->status == 1){
	                  $get_quick_jobs = DB::table('task_job')->where('active_id',$jobs->id)->get();
	                    $quick_minutes = 0;
	                    if(count($get_quick_jobs))
	                    {
	                      foreach($get_quick_jobs as $quickjobs_single)
	                      {
	                        $total_quick_jobs_1 = explode(':', $quickjobs_single->job_time);
	                        $minutes = ($total_quick_jobs_1[0]*60) + ($total_quick_jobs_1[1]) + ($total_quick_jobs_1[2]/60);
	                        if($quick_minutes == 0)
	                        {
	                          $quick_minutes = $minutes;
	                        }
	                        else{
	                          $quick_minutes = $quick_minutes + $minutes;
	                        }
	                      }
	                    }

	                    $break_time_min = DB::table('job_break_time')->where('job_id',$jobs->id)->where('break_time','!=','00:00:00')->first();
                        $break_timee_minutes = 0;
                        if(count($break_time_min))
                        {
                          $break_timee = explode(':', $break_time_min->break_time);
                          $break_timee_minutes = ($break_timee[0]*60) + ($break_timee[1]) + ($break_timee[2]/60);
                        }
                        $quick_minutes = $quick_minutes + $break_timee_minutes;

	                    $job_timee = explode(':', $jobs->job_time);
	                    $job_timee_minutes = ($job_timee[0]*60) + ($job_timee[1]) + ($job_timee[2]/60);

	                    $job_time_min = $job_timee_minutes - $quick_minutes;

	                    if(floor($job_time_min / 60) <= 9)
	                    {
	                      $h = '0'.floor($job_time_min / 60);
	                    }
	                    else{
	                      $h = floor($job_time_min / 60);
	                    }
	                    if(($job_time_min -   floor($job_time_min / 60) * 60) <= 9)
	                    {
	                      $m = '0'.($job_time_min -   floor($job_time_min / 60) * 60);
	                    }
	                    else{
	                      $m = ($job_time_min -   floor($job_time_min / 60) * 60);
	                    }
	                    $job_time_checked = $h.':'.$m.':00';
	              }
	              else{
	                $job_time_checked = 'N/A';
	              }




		      	$columns_2 = array($i, $user_details->lastname.' '.$user_details->firstname, $companyname, $task_details->task_name, $task_type, date('d-M-Y', strtotime($single->job_date)), date('H:i:s', strtotime($single->start_time)),date('H:i:s', strtotime($single->stop_time)), $job_time_checked );
				fputcsv($file, $columns_2);
				$job_details_child = DB::table('task_job')->where('active_id',$single->id)->get();
				if(count($job_details_child)) {
				$ichild=1;
			foreach ($job_details_child as $child) {				

				

				if($child->client_id != ''){
					$company_details = DB::table('cm_clients')->where('client_id', $child->client_id)->first();
					$companyname = $company_details->company;
				}
				else{
					$companyname = 'N/A';
				}				
				$user_details = DB::table('user')->where('user_id', $child->user_id)->first();
				$task_details = DB::table('time_task')->where('id', $child->task_id)->first();

				if($task_details->task_type == 0){
					$task_type = 'Internal Task';
				}
				else if($task_details->task_type == 1){
					$task_type = 'Client Task';
				}
				else{
					$task_type = 'Global Task';
				}


				$jobs = DB::table('task_job')->where('id', $child->id)->first();


				//-----------Job Time Start----------------

		        $created_date = $jobs->job_created_date;

                $jobstart = strtotime($created_date.' '.$jobs->start_time);
                $jobend   = strtotime($created_date.' '.date('H:i:s'));
                

                if($jobend < $jobstart)
                {
                  $todate = date('Y-m-d', strtotime("+1 day", $jobend));
                  $jobend   = strtotime($todate.' '.date('H:i:s'));
                }

                $jobdiff  = $jobend - $jobstart;



		        $hours = floor($jobdiff / (60 * 60));
		        $minutes = $jobdiff - $hours * (60 * 60);
		        $minutes = floor( $minutes / 60 );
		        $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
		        if($hours <= 9)
		        {
		          $hours = '0'.$hours;
		        }
		        else{
		          $hours = $hours;
		        }
		        if($minutes <= 9)
		        {
		          $minutes = '0'.$minutes;
		        }
		        else{
		          $minutes = $minutes;
		        }
		        if($second <= 9)
		        {
		          $second = '0'.$second;
		        }
		        else{
		          $second = $second;
		        }

		        $jobtime =   $hours.':'.$minutes.':'.$second;

		        //-----------Job Time End----------------

		        if($child->status == 0){
	                $job_time_checked = $jobtime;
	              }
	              else if($child->status == 1){
	                  $job_time_checked = $child->job_time;
	              }
	              else{
	                $job_time_checked = 'N/A';
	              }




		      	$columns_2 = array($i.'.'.$ichild, $user_details->lastname.' '.$user_details->firstname, $companyname, $task_details->task_name, $task_type, date('d-M-Y', strtotime($child->job_date)), date('H:i:s', strtotime($child->start_time)),date('H:i:s', strtotime($child->stop_time)), $job_time_checked );
				fputcsv($file, $columns_2);
				$ichild++;
				}
			}
				$i++;
			}
			fclose($file);
		};
		return Response::stream($callback, 200, $headers);
		//return $filename.'_InvoiceReport.csv';

	}
	

	public function searchjobofday(){
		$date = date('Y-m-d', strtotime(Input::get('value')));
		$joblist = DB::table('task_job')->where('job_date', $date)->where('active_id',0)->get();		

		$output='';
        $i=1;            
        if(count($joblist)){              
          foreach ($joblist as $jobs) {
            if($jobs->quick_job == 1 || $jobs->status == 1 ){
                $client_details = DB::table('cm_clients')->where('client_id', $jobs->client_id)->first();
                if(count($client_details) != ''){
                  $client_name = $client_details->company.' ('.$jobs->client_id.')';
                }
                else{
                  $client_name = 'N/A';
                }

                $task_details = DB::table('time_task')->where('id', $jobs->task_id)->first();

                if(count($task_details) != ''){
                  $task_name = $task_details->task_name;
                  $task_type = $task_details->task_type;

                  if($task_type == 0){
                    $task_type = '<i class="fa fa-desktop" style="margin-right:10px;"></i> Internal Task';
                  }
                  else if($task_type == 1){
                    $task_type = '<i class="fa fa-users" style="margin-right:10px;"></i> Client Task';
                  }
                  else{
                    $task_type = '<i class="fa fa-globe" style="margin-right:10px;"></i> Global Task';
                  }
                }
                else{
                  $task_name = 'N/A';
                  $task_type = 'N/A';
                }

                

                $user_details = DB::table('user')->where('user_id', $jobs->user_id)->first();

                //-----------Job Time Start----------------

                $jobstart = strtotime($jobs->updated);
                $jobend   = strtotime(date('Y-m-d H:i:s'));
                $jobdiff  = $jobend - $jobstart;



                $hours = floor($jobdiff / (60 * 60));
                $minutes = $jobdiff - $hours * (60 * 60);
                $minutes = floor( $minutes / 60 );
                $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
                if($hours <= 9)
                {
                  $hours = '0'.$hours;
                }
                else{
                  $hours = $hours;
                }
                if($minutes <= 9)
                {
                  $minutes = '0'.$minutes;
                }
                else{
                  $minutes = $minutes;
                }
                if($second <= 9)
                {
                  $second = '0'.$second;
                }
                else{
                  $second = $second;
                }

                $jobtime =   $hours.':'.$minutes.':'.$second;

                //-----------Job Time End----------------


                if($jobs->stop_time != '00:00:00')
                {
                	$get_quick_jobs = DB::table('task_job')->where('active_id',$jobs->id)->get();
	                  $quick_minutes = 0;
	                  if(count($get_quick_jobs))
	                  {
	                    foreach($get_quick_jobs as $quickjobs_single)
	                    {
	                      $total_quick_jobs_1 = explode(':', $quickjobs_single->job_time);
	                      $minutes = ($total_quick_jobs_1[0]*60) + ($total_quick_jobs_1[1]) + ($total_quick_jobs_1[2]/60);
	                      if($quick_minutes == 0)
	                      {
	                        $quick_minutes = $minutes;
	                      }
	                      else{
	                        $quick_minutes = $quick_minutes + $minutes;
	                      }
	                    }
	                  }

	                    $break_time_min = DB::table('job_break_time')->where('job_id',$jobs->id)->where('break_time','!=','00:00:00')->first();
	                    $break_timee_minutes = 0;
	                    if(count($break_time_min))
	                    {
	                      $break_timee = explode(':', $break_time_min->break_time);
	                      $break_timee_minutes = ($break_timee[0]*60) + ($break_timee[1]) + ($break_timee[2]/60);
	                    }


	                    $quick_minutes = $quick_minutes + $break_timee_minutes;

	                  $job_timee = explode(':', $jobs->job_time);
	                  $job_timee_minutes = ($job_timee[0]*60) + ($job_timee[1]) + ($job_timee[2]/60);

	                  $job_time_min = $job_timee_minutes - $quick_minutes;

	                  if(floor($job_time_min / 60) <= 9)
	                  {
	                    $h = '0'.floor($job_time_min / 60);
	                  }
	                  else{
	                    $h = floor($job_time_min / 60);
	                  }
	                  if(($job_time_min -   floor($job_time_min / 60) * 60) <= 9)
	                  {
	                    $m = '0'.($job_time_min -   floor($job_time_min / 60) * 60);
	                  }
	                  else{
	                    $m = ($job_time_min -   floor($job_time_min / 60) * 60);
	                  }
	                  $explode_job_minutes = explode(":",$h.':'.$m.':00');
    					$total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
	                  $job_time_checked = $h.':'.$m.':00 ('.$total_minutes.')';
                }
                else{
                	$job_time_checked = $jobs->job_time;
                }
                
                if($jobs->comments != "") { $comments = $jobs->comments; } else { $comments = 'No Comments Found'; }
                if($jobs->quick_job != 0) { $quick_job = 'YES'; } else { $quick_job = 'NO'; }

                 $ratelist = DB::table('user_cost')->where('user_id', $jobs->user_id)->get();

                $rate_result = '0';
                $cost = '0';

                if(count($ratelist)){
                  foreach ($ratelist as $rate) {
                    $job_date = strtotime($jobs->job_date);
                    $from_date = strtotime($rate->from_date);
                    $to_date = strtotime($rate->to_date);

                    if($rate->to_date != '0000-00-00'){                         
                      if($job_date >= $from_date  && $job_date <= $to_date){
                        
                        $rate_result = $rate->cost;                            
                        $cost = ($rate_result/60)*$total_minutes;
                      }
                    }
                    else{
                      if($job_date >= $from_date){
                        $rate_result = $rate->cost;
                        $cost = ($rate_result/60)*$total_minutes;
                      }
                    }
                  }                      
                }

        $output.='
        <tr>
    	   <td>
          <input type="checkbox" name="select_job" class="select_job class_'.$jobs->id.'" data-element="'.$jobs->id.'" value="'.$jobs->id.'"><label>&nbsp</label>
          </td>
          <td align="left">'.$i.'</td>
          <td align="left">'.$user_details->lastname.' '.$user_details->firstname.'</td>
          <td align="left">'.number_format_invoice_without_decimal($rate_result).'</td>
          <td align="left">'.$client_name.'</td>
          <td align="left">'.$task_name.'</td>
          <td align="left">'.$task_type.'</td>
          <td align="left">'.$quick_job.'</td>
          <td align="left">'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
          <td align="left">'.date('H:i:s', strtotime($jobs->start_time)).'</td>
          <td align="left">'.date('H:i:s', strtotime($jobs->stop_time)).'</td>
          <td align="left">'.number_format_invoice_without_decimal($cost).'</td>
          <td align="left">'.$job_time_checked.'</td>          
          <td>
          <a href="javascript:"><i class="fa fa-pencil edit_task" data-element="'.$jobs->id.'" data-toggle="tooltip" title="Edit Job" aria-hidden="true"></i></a>
          <a href="javascript:" class="fa fa-comment" data-toggle="modal" data-target="#comments_'.$jobs->id.'" title="View Comments"></a>
            <div id="comments_'.$jobs->id.'" class="modal fade" role="dialog" >
                <div class="modal-dialog" style="width:20%">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Comments</h4>
                    </div>
                    <div class="modal-body">
                      '.$comments.'
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
              </div>
          </td>
          
        </tr>';
        $joblist_child = DB::table('task_job')->where('active_id',$jobs->id)->get();
        $ichild=1;            
        if(count($joblist_child)){              
          foreach ($joblist_child as $child) {
            if($child->quick_job == 1 || $child->status == 1 ){
                $client_details = DB::table('cm_clients')->where('client_id', $child->client_id)->first();
                if(count($client_details) != ''){
                  $client_name = $client_details->company.' ('.$child->client_id.')';
                }
                else{
                  $client_name = 'N/A';
                }

                $task_details = DB::table('time_task')->where('id', $child->task_id)->first();

                if(count($task_details) != ''){
                  $task_name = $task_details->task_name;
                  $task_type = $task_details->task_type;

                  if($task_type == 0){
                    $task_type = '<i class="fa fa-desktop" style="margin-right:10px;"></i> Internal Task';
                  }
                  else if($task_type == 1){
                    $task_type = '<i class="fa fa-users" style="margin-right:10px;"></i> Client Task';
                  }
                  else{
                    $task_type = '<i class="fa fa-globe" style="margin-right:10px;"></i> Global Task';
                  }
                }
                else{
                  $task_name = 'N/A';
                  $task_type = 'N/A';
                }

                

                $user_details = DB::table('user')->where('user_id', $child->user_id)->first();

                //-----------Job Time Start----------------

                $jobstart = strtotime($child->updated);
                $jobend   = strtotime(date('Y-m-d H:i:s'));
                $jobdiff  = $jobend - $jobstart;



                $hours = floor($jobdiff / (60 * 60));
                $minutes = $jobdiff - $hours * (60 * 60);
                $minutes = floor( $minutes / 60 );
                $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
                if($hours <= 9)
                {
                  $hours = '0'.$hours;
                }
                else{
                  $hours = $hours;
                }
                if($minutes <= 9)
                {
                  $minutes = '0'.$minutes;
                }
                else{
                  $minutes = $minutes;
                }
                if($second <= 9)
                {
                  $second = '0'.$second;
                }
                else{
                  $second = $second;
                }

                $jobtime =   $hours.':'.$minutes.':'.$second;
                $explode_job_minutes = explode(":",$jobtime);
                    $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
                //-----------Job Time End----------------


                if($child->quick_job == 0 && $child->status == 0){
                  $job_time_checked = '<span id="job_time_refresh_'.$child->id.'">'.$jobtime.' ('.$total_minutes.')</span></span> &nbsp;&nbsp;<a href="javascript:"><i class="fa fa-refresh job_time_refresh" aria-hidden="true" data-element="'.$child->id.'"></i></a>';
                }
                else if($child->quick_job == 1 && $child->status == 0){
                  $job_time_checked = $child->job_time;
                }
                else if($child->quick_job == 0 && $child->status == 1){
                  $job_time_checked = $child->job_time;
                }
                
                if($child->comments != "") { $comments = $child->comments; } else { $comments = 'No Comments Found'; }
                if($child->quick_job != 0) { $quick_job = 'YES'; } else { $quick_job = 'NO'; }

                $explode_job_minutes = explode(":",$child->job_time);
                    $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);


                $ratelist_child = DB::table('user_cost')->where('user_id', $child->user_id)->get();

                $rate_child_result = '0';
                $child_cost = '0';

                if(count($ratelist_child)){
                  foreach ($ratelist_child as $rate) {
                    $job_date = strtotime($child->job_date);
                    $from_date = strtotime($rate->from_date);
                    $to_date = strtotime($rate->to_date);

                    if($rate->to_date != '0000-00-00'){                         
                      if($job_date >= $from_date  && $job_date <= $to_date){
                        
                        $rate_child_result = $rate->cost;                            
                        $child_cost = ($rate_child_result/60)*$total_minutes;
                      }
                    }
                    else{
                      if($job_date >= $from_date){
                        $rate_child_result = $rate->cost;
                        $child_cost = ($rate_child_result/60)*$total_minutes;
                      }
                    }
                  }                      
                }



        $output.='
        <tr>
    	   <td>
          <input type="checkbox" name="select_jobaaaaaaa" class="select_jobaaaaa classaaaaa_'.$child->id.'" data-element="'.$child->id.'" value="'.$child->id.'" style="display:none"><label style="display:none">&nbsp</label>
          </td>
          <td align="left">'.$i.'.'.$ichild.'</td>
          <td align="left">'.$user_details->lastname.' '.$user_details->firstname.'</td>
          <td align="left">'.number_format_invoice_without_decimal($rate_child_result).'</td>
          <td align="left">'.$client_name.'</td>
          <td align="left">'.$task_name.'</td>
          <td align="left">'.$task_type.'</td>
          <td align="left">'.$quick_job.'</td>
          <td align="left">'.date('d-M-Y', strtotime($child->job_date)).'</td>
          <td align="left">'.date('H:i:s', strtotime($child->start_time)).'</td>
          <td align="left">'.date('H:i:s', strtotime($child->stop_time)).'</td>
          <td align="left">'.number_format_invoice_without_decimal($child_cost).'</td>
          <td align="left">'.$child->job_time.' ('.$total_minutes.')</td>
          <td>
          <a href="javascript:"><i class="fa fa-pencil edit_task" data-element="'.$child->id.'" data-toggle="tooltip" title="Edit Job" aria-hidden="true"></i></a>
          <a href="javascript:" class="fa fa-comment" data-toggle="modal" data-target="#comments_'.$child->id.'" title="View Comments"></a>

            <div id="comments_'.$child->id.'" class="modal fade" role="dialog" >
                <div class="modal-dialog" style="width:20%">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Comments</h4>
                    </div>
                    <div class="modal-body">
                      '.$comments.'
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
              </div>
          </td>
          
        </tr>';
        $ichild++;
    }
}
}
          $i++;
             }
          }              
        }
        if($i == 1){
          $output.= '<tr>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left"></td>                        
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="center">Empty</td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left"></td>
                    </tr>';
        }
        echo $output;

		
	}

	public function search_client_review(){

		$search_checkbox = Input::get('search_checkbox');

		$client_id = Input::get('client_id');
		$tasks = explode(',',Input::get('tasks'));
		$tasks_implode = Input::get('tasks');

		$users = explode(',',Input::get('users'));
		$users_implode = Input::get('users');

		

		if($search_checkbox == 0){

			$start_date = strtotime(Input::get('start_date'));
			$stop_date = strtotime(Input::get('stop_date'));

			$joblist = DB::select('SELECT * from `task_job` WHERE task_id IN ('.$tasks_implode.') AND client_id = "'.$client_id.'" AND UNIX_TIMESTAMP(`job_date`) >= "'.$start_date.'" AND UNIX_TIMESTAMP(`job_date`) <= "'.$stop_date.'"');

			$output='';
	        $i=1;            
	        if(count($joblist)){              
	          foreach ($joblist as $jobs) {
	          	if(in_array($jobs->user_id,$users))
	          	{
	          		if($jobs->quick_job == 1 || $jobs->status == 1 ){
		                $client_details = DB::table('cm_clients')->where('client_id', $jobs->client_id)->first();
		                if(count($client_details) != ''){
		                  $client_name = $client_details->company.' ('.$jobs->client_id.')';
		                }
		                else{
		                  $client_name = 'N/A';
		                }

		                $task_details = DB::table('time_task')->where('id', $jobs->task_id)->first();

		                if(count($task_details) != ''){
		                  $task_name = $task_details->task_name;
		                  $task_type = $task_details->task_type;

		                  if($task_type == 0){
		                    $task_type = '<i class="fa fa-desktop" style="margin-right:10px;"></i> Internal Task';
		                  }
		                  else if($task_type == 1){
		                    $task_type = '<i class="fa fa-users" style="margin-right:10px;"></i> Client Task';
		                  }
		                  else{
		                    $task_type = '<i class="fa fa-globe" style="margin-right:10px;"></i> Global Task';
		                  }
		                }
		                else{
		                  $task_name = 'N/A';
		                  $task_type = 'N/A';
		                }

		                

		                $user_details = DB::table('user')->where('user_id', $jobs->user_id)->first();

		                //-----------Job Time Start----------------

		                $jobstart = strtotime($jobs->updated);
		                $jobend   = strtotime(date('Y-m-d H:i:s'));
		                $jobdiff  = $jobend - $jobstart;



		                $hours = floor($jobdiff / (60 * 60));
		                $minutes = $jobdiff - $hours * (60 * 60);
		                $minutes = floor( $minutes / 60 );
		                $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
		                if($hours <= 9)
		                {
		                  $hours = '0'.$hours;
		                }
		                else{
		                  $hours = $hours;
		                }
		                if($minutes <= 9)
		                {
		                  $minutes = '0'.$minutes;
		                }
		                else{
		                  $minutes = $minutes;
		                }
		                if($second <= 9)
		                {
		                  $second = '0'.$second;
		                }
		                else{
		                  $second = $second;
		                }

		                $jobtime =   $hours.':'.$minutes.':'.$second;
		                $explode_job_minutes = explode(":",$jobtime);
                    $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
		                //-----------Job Time End----------------


		                if($jobs->status == 0){
		                  $job_time_checked = '<span id="job_time_refresh_'.$jobs->id.'">'.$jobtime.' ('.$total_minutes.')</span></span> &nbsp;&nbsp;<a href="javascript:"><i class="fa fa-refresh job_time_refresh" aria-hidden="true" data-element="'.$jobs->id.'"></i></a>';
		                }
		                else if($jobs->status == 1){
			                	$get_quick_jobs = DB::table('task_job')->where('active_id',$jobs->id)->get();
				                  $quick_minutes = 0;
				                  if(count($get_quick_jobs))
				                  {
				                    foreach($get_quick_jobs as $quickjobs_single)
				                    {
				                      $total_quick_jobs_1 = explode(':', $quickjobs_single->job_time);
				                      $minutes = ($total_quick_jobs_1[0]*60) + ($total_quick_jobs_1[1]) + ($total_quick_jobs_1[2]/60);
				                      if($quick_minutes == 0)
				                      {
				                        $quick_minutes = $minutes;
				                      }
				                      else{
				                        $quick_minutes = $quick_minutes + $minutes;
				                      }
				                    }
				                  }

				                  $break_time_min = DB::table('job_break_time')->where('job_id',$jobs->id)->where('break_time','!=','00:00:00')->first();
			                        $break_timee_minutes = 0;
			                        if(count($break_time_min))
			                        {
			                          $break_timee = explode(':', $break_time_min->break_time);
			                          $break_timee_minutes = ($break_timee[0]*60) + ($break_timee[1]) + ($break_timee[2]/60);
			                        }
			                        $quick_minutes = $quick_minutes + $break_timee_minutes;

				                  $job_timee = explode(':', $jobs->job_time);
				                  $job_timee_minutes = ($job_timee[0]*60) + ($job_timee[1]) + ($job_timee[2]/60);

				                  $job_time_min = $job_timee_minutes - $quick_minutes;

				                  if(floor($job_time_min / 60) <= 9)
				                  {
				                    $h = '0'.floor($job_time_min / 60);
				                  }
				                  else{
				                    $h = floor($job_time_min / 60);
				                  }
				                  if(($job_time_min -   floor($job_time_min / 60) * 60) <= 9)
				                  {
				                    $m = '0'.($job_time_min -   floor($job_time_min / 60) * 60);
				                  }
				                  else{
				                    $m = ($job_time_min -   floor($job_time_min / 60) * 60);
				                  }
				                  $explode_job_minutes = explode(":",$h.':'.$m.':00');
                					$total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
				                  $job_time_checked = $h.':'.$m.':00 ('.$total_minutes.')';
		                }
		                else{
		                	$job_time_checked = 'N/A';
		                }
		                
		                if($jobs->comments != "") { $comments = $jobs->comments; } else { $comments = 'No Comments Found'; }

		                $ratelist = DB::table('user_cost')->where('user_id', $jobs->user_id)->get();

		                $rate_result = '0';
	                    $cost = '0';

	                    if(count($ratelist)){
	                      foreach ($ratelist as $rate) {
	                        $job_date = strtotime($jobs->job_date);
	                        $from_date = strtotime($rate->from_date);
	                        $to_date = strtotime($rate->to_date);

	                        if($rate->to_date != '0000-00-00'){                         
	                          if($job_date >= $from_date  && $job_date <= $to_date){
	                            
	                            $rate_result = $rate->cost;                            
	                            $cost = ($rate_result/60)*$total_minutes;
	                          }
	                        }
	                        else{
	                          if($job_date >= $from_date){
	                            $rate_result = $rate->cost;
	                            $cost = ($rate_result/60)*$total_minutes;
	                          }
	                        }
	                      }                      
	                    }

		        $output.='
		        <tr>
		    	   <td>
		          <input type="checkbox" name="select_job" class="select_job class_'.$jobs->id.'" data-element="'.$jobs->id.'" value="'.$jobs->id.'"><label>&nbsp</label>
		          </td>
		          <td align="left">'.$i.'</td>
		          <td align="left">'.$user_details->lastname.' '.$user_details->firstname.'</td>
		          <td align="left">'.number_format_invoice_without_decimal($rate_result).'</td>
		          <td align="left">'.$client_name.'</td>
		          <td align="left">'.$task_name.'</td>
		          <td align="left">'.$task_type.'</td>
		          <td align="left">'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
		          <td align="left">'.date('H:i:s', strtotime($jobs->start_time)).'</td>
		          <td align="left">'.date('H:i:s', strtotime($jobs->stop_time)).'</td>
		          <td align="left">'.number_format_invoice_without_decimal($cost).'</td>
		          <td align="left">'.$job_time_checked.'</td>
		          <td align="center">
		          <a href="javascript:" class="fa fa-comment" data-toggle="modal" data-target="#comments_'.$jobs->id.'" title="View Comments"></a>
			        <div id="comments_'.$jobs->id.'" class="modal fade" role="dialog" >
			            <div class="modal-dialog" style="width:20%">
			              <div class="modal-content">
			                <div class="modal-header">
			                  <button type="button" class="close" data-dismiss="modal">&times;</button>
			                  <h4 class="modal-title">Comments</h4>
			                </div>
			                <div class="modal-body">
			                  '.$comments.'
			                </div>
			                <div class="modal-footer">
			                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			                </div>
			              </div>
			            </div>
			          </div>
			      </td>
		        </tr>';
		          $i++;
		             }
	          	}
	            
	          }              
	        }
	        if($i == 1){
	          $output.= '<tr>
	                    <td align="left"></td>
	                    <td align="left"></td>
	                    <td align="left"></td>                        
	                    <td align="left"></td>
	                    <td align="left"></td>
	                    <td align="left"></td>
	                    <td align="center">Empty</td>
	                    <td align="left"></td>
	                    <td align="left"></td>
	                    <td align="left"></td>
	                    <td align="left"></td>
	                    <td align="left"></td>
	                    <td align="left"></td>
	                    </tr>';
	        }
		}
		elseif($search_checkbox == 1){	

			$joblist = DB::select('SELECT * from `task_job` WHERE task_id IN ('.$tasks_implode.') AND client_id = "'.$client_id.'"');

			$output='';
	        $i=1;            
	        if(count($joblist)){              
	          foreach ($joblist as $jobs) {
	          	if(in_array($jobs->user_id,$users))
	          	{
	          		if($jobs->quick_job == 1 || $jobs->status == 1 ){
		                $client_details = DB::table('cm_clients')->where('client_id', $jobs->client_id)->first();
		                if(count($client_details) != ''){
		                  $client_name = $client_details->company.' ('.$jobs->client_id.')';
		                }
		                else{
		                  $client_name = 'N/A';
		                }

		                $task_details = DB::table('time_task')->where('id', $jobs->task_id)->first();

		                if(count($task_details) != ''){
		                  $task_name = $task_details->task_name;
		                  $task_type = $task_details->task_type;

		                  if($task_type == 0){
		                    $task_type = '<i class="fa fa-desktop" style="margin-right:10px;"></i> Internal Task';
		                  }
		                  else if($task_type == 1){
		                    $task_type = '<i class="fa fa-users" style="margin-right:10px;"></i> Client Task';
		                  }
		                  else{
		                    $task_type = '<i class="fa fa-globe" style="margin-right:10px;"></i> Global Task';
		                  }
		                }
		                else{
		                  $task_name = 'N/A';
		                  $task_type = 'N/A';
		                }

		                

		                $user_details = DB::table('user')->where('user_id', $jobs->user_id)->first();

		                //-----------Job Time Start----------------

		                $jobstart = strtotime($jobs->updated);
		                $jobend   = strtotime(date('Y-m-d H:i:s'));
		                $jobdiff  = $jobend - $jobstart;



		                $hours = floor($jobdiff / (60 * 60));
		                $minutes = $jobdiff - $hours * (60 * 60);
		                $minutes = floor( $minutes / 60 );
		                $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
		                if($hours <= 9)
		                {
		                  $hours = '0'.$hours;
		                }
		                else{
		                  $hours = $hours;
		                }
		                if($minutes <= 9)
		                {
		                  $minutes = '0'.$minutes;
		                }
		                else{
		                  $minutes = $minutes;
		                }
		                if($second <= 9)
		                {
		                  $second = '0'.$second;
		                }
		                else{
		                  $second = $second;
		                }

		                $jobtime =   $hours.':'.$minutes.':'.$second;
		                $explode_job_minutes = explode(":",$jobtime);
                    $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
		                //-----------Job Time End----------------


		                if($jobs->status == 0){
		                  $job_time_checked = '<span id="job_time_refresh_'.$jobs->id.'">'.$jobtime.' ('.$total_minutes.')</span></span> &nbsp;&nbsp;<a href="javascript:"><i class="fa fa-refresh job_time_refresh" aria-hidden="true" data-element="'.$jobs->id.'"></i></a>';
		                }
		                else if($jobs->status == 1){
			                	$get_quick_jobs = DB::table('task_job')->where('active_id',$jobs->id)->get();
				                  $quick_minutes = 0;
				                  if(count($get_quick_jobs))
				                  {
				                    foreach($get_quick_jobs as $quickjobs_single)
				                    {
				                      $total_quick_jobs_1 = explode(':', $quickjobs_single->job_time);
				                      $minutes = ($total_quick_jobs_1[0]*60) + ($total_quick_jobs_1[1]) + ($total_quick_jobs_1[2]/60);
				                      if($quick_minutes == 0)
				                      {
				                        $quick_minutes = $minutes;
				                      }
				                      else{
				                        $quick_minutes = $quick_minutes + $minutes;
				                      }
				                    }
				                  }

				                  $break_time_min = DB::table('job_break_time')->where('job_id',$jobs->id)->where('break_time','!=','00:00:00')->first();
			                        $break_timee_minutes = 0;
			                        if(count($break_time_min))
			                        {
			                          $break_timee = explode(':', $break_time_min->break_time);
			                          $break_timee_minutes = ($break_timee[0]*60) + ($break_timee[1]) + ($break_timee[2]/60);
			                        }
			                        $quick_minutes = $quick_minutes + $break_timee_minutes;

				                  $job_timee = explode(':', $jobs->job_time);
				                  $job_timee_minutes = ($job_timee[0]*60) + ($job_timee[1]) + ($job_timee[2]/60);

				                  $job_time_min = $job_timee_minutes - $quick_minutes;

				                  if(floor($job_time_min / 60) <= 9)
				                  {
				                    $h = '0'.floor($job_time_min / 60);
				                  }
				                  else{
				                    $h = floor($job_time_min / 60);
				                  }
				                  if(($job_time_min -   floor($job_time_min / 60) * 60) <= 9)
				                  {
				                    $m = '0'.($job_time_min -   floor($job_time_min / 60) * 60);
				                  }
				                  else{
				                    $m = ($job_time_min -   floor($job_time_min / 60) * 60);
				                  }
				                  $explode_job_minutes = explode(":",$h.':'.$m.':00');
                					$total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
				                  $job_time_checked = $h.':'.$m.':00 ('.$total_minutes.')';
		                }
		                else{
		                	$job_time_checked = 'N/A';
		                }
		                
		                if($jobs->comments != "") { $comments = $jobs->comments; } else { $comments = 'No Comments Found'; }

		                $ratelist = DB::table('user_cost')->where('user_id', $jobs->user_id)->get();

		                $rate_result = '0';
	                    $cost = '0';

	                    if(count($ratelist)){
	                      foreach ($ratelist as $rate) {
	                        $job_date = strtotime($jobs->job_date);
	                        $from_date = strtotime($rate->from_date);
	                        $to_date = strtotime($rate->to_date);

	                        if($rate->to_date != '0000-00-00'){                         
	                          if($job_date >= $from_date  && $job_date <= $to_date){
	                            
	                            $rate_result = $rate->cost;                            
	                            $cost = ($rate_result/60)*$total_minutes;
	                          }
	                        }
	                        else{
	                          if($job_date >= $from_date){
	                            $rate_result = $rate->cost;
	                            $cost = ($rate_result/60)*$total_minutes;
	                          }
	                        }
	                      }                      
	                    }


		        $output.='
		        <tr>
		    	   <td>
		          <input type="checkbox" name="select_job" class="select_job class_'.$jobs->id.'" data-element="'.$jobs->id.'" value="'.$jobs->id.'"><label>&nbsp</label>
		          </td>
		          <td align="left">'.$i.'</td>
		          <td align="left">'.$user_details->lastname.' '.$user_details->firstname.'</td>
		          <td align="left">'.number_format_invoice_without_decimal($rate_result).'</td>
		          <td align="left">'.$client_name.'</td>
		          <td align="left">'.$task_name.'</td>
		          <td align="left">'.$task_type.'</td>
		          <td align="left">'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
		          <td align="left">'.date('H:i:s', strtotime($jobs->start_time)).'</td>
		          <td align="left">'.date('H:i:s', strtotime($jobs->stop_time)).'</td>
		          <td align="left">'.number_format_invoice_without_decimal($cost).'</td>
		          <td align="left">'.$job_time_checked.'</td>
		          <td align="center">
		          <a href="javascript:" class="fa fa-comment" data-toggle="modal" data-target="#comments_'.$jobs->id.'" title="View Comments"></a>
			        <div id="comments_'.$jobs->id.'" class="modal fade" role="dialog" >
			            <div class="modal-dialog" style="width:20%">
			              <div class="modal-content">
			                <div class="modal-header">
			                  <button type="button" class="close" data-dismiss="modal">&times;</button>
			                  <h4 class="modal-title">Comments</h4>
			                </div>
			                <div class="modal-body">
			                  '.$comments.'
			                </div>
			                <div class="modal-footer">
			                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			                </div>
			              </div>
			            </div>
			          </div>
			      </td>
		        </tr>';
		          $i++;
		             }
	          	}
	            
	          }              
	        }
	        if($i == 1){
	          $output.= '<tr>
	                    <td align="left"></td>
	                    <td align="left"></td>
	                    <td align="left"></td>                        
	                    <td align="left"></td>
	                    <td align="left"></td>
	                    <td align="left"></td>
	                    <td align="center">Empty</td>
	                    <td align="left"></td>
	                    <td align="left"></td>
	                    <td align="left"></td>
	                    <td align="left"></td>
	                    <td align="left"></td>
	                    <td align="left"></td>
	                    </tr>';
	        }
		}
		elseif($search_checkbox == 2){

			$joblist = DB::table('task_job')->where('client_id', $client_id)->get();

			$output='';
	        $i=1;            
	        if(count($joblist)){              
	          foreach ($joblist as $jobs) {
	          	if(count($jobs->user_id))
	          	{
	          		if($jobs->quick_job == 1 || $jobs->status == 1 ){
		                $client_details = DB::table('cm_clients')->where('client_id', $jobs->client_id)->first();
		                if(count($client_details) != ''){
		                  $client_name = $client_details->company.' ('.$jobs->client_id.')';
		                }
		                else{
		                  $client_name = 'N/A';
		                }

		                $task_details = DB::table('time_task')->where('id', $jobs->task_id)->first();

		                if(count($task_details) != ''){
		                  $task_name = $task_details->task_name;
		                  $task_type = $task_details->task_type;

		                  if($task_type == 0){
		                    $task_type = '<i class="fa fa-desktop" style="margin-right:10px;"></i> Internal Task';
		                  }
		                  else if($task_type == 1){
		                    $task_type = '<i class="fa fa-users" style="margin-right:10px;"></i> Client Task';
		                  }
		                  else{
		                    $task_type = '<i class="fa fa-globe" style="margin-right:10px;"></i> Global Task';
		                  }
		                }
		                else{
		                  $task_name = 'N/A';
		                  $task_type = 'N/A';
		                }

		                

		                $user_details = DB::table('user')->where('user_id', $jobs->user_id)->first();

		                //-----------Job Time Start----------------

		                $jobstart = strtotime($jobs->updated);
		                $jobend   = strtotime(date('Y-m-d H:i:s'));
		                $jobdiff  = $jobend - $jobstart;



		                $hours = floor($jobdiff / (60 * 60));
		                $minutes = $jobdiff - $hours * (60 * 60);
		                $minutes = floor( $minutes / 60 );
		                $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
		                if($hours <= 9)
		                {
		                  $hours = '0'.$hours;
		                }
		                else{
		                  $hours = $hours;
		                }
		                if($minutes <= 9)
		                {
		                  $minutes = '0'.$minutes;
		                }
		                else{
		                  $minutes = $minutes;
		                }
		                if($second <= 9)
		                {
		                  $second = '0'.$second;
		                }
		                else{
		                  $second = $second;
		                }

		                $jobtime =   $hours.':'.$minutes.':'.$second;
		                $explode_job_minutes = explode(":",$jobtime);
                    $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
		                //-----------Job Time End----------------


		                if($jobs->status == 0){
		                  $job_time_checked = '<span id="job_time_refresh_'.$jobs->id.'">'.$jobtime.' ('.$total_minutes.')</span></span> &nbsp;&nbsp;<a href="javascript:"><i class="fa fa-refresh job_time_refresh" aria-hidden="true" data-element="'.$jobs->id.'"></i></a>';
		                }
		                else if($jobs->status == 1){
			                	$get_quick_jobs = DB::table('task_job')->where('active_id',$jobs->id)->get();
				                  $quick_minutes = 0;
				                  if(count($get_quick_jobs))
				                  {
				                    foreach($get_quick_jobs as $quickjobs_single)
				                    {
				                      $total_quick_jobs_1 = explode(':', $quickjobs_single->job_time);
				                      $minutes = ($total_quick_jobs_1[0]*60) + ($total_quick_jobs_1[1]) + ($total_quick_jobs_1[2]/60);
				                      if($quick_minutes == 0)
				                      {
				                        $quick_minutes = $minutes;
				                      }
				                      else{
				                        $quick_minutes = $quick_minutes + $minutes;
				                      }
				                    }
				                  }

				                  $break_time_min = DB::table('job_break_time')->where('job_id',$jobs->id)->where('break_time','!=','00:00:00')->first();
			                        $break_timee_minutes = 0;
			                        if(count($break_time_min))
			                        {
			                          $break_timee = explode(':', $break_time_min->break_time);
			                          $break_timee_minutes = ($break_timee[0]*60) + ($break_timee[1]) + ($break_timee[2]/60);
			                        }
			                        $quick_minutes = $quick_minutes + $break_timee_minutes;

				                  $job_timee = explode(':', $jobs->job_time);
				                  $job_timee_minutes = ($job_timee[0]*60) + ($job_timee[1]) + ($job_timee[2]/60);

				                  $job_time_min = $job_timee_minutes - $quick_minutes;

				                  if(floor($job_time_min / 60) <= 9)
				                  {
				                    $h = '0'.floor($job_time_min / 60);
				                  }
				                  else{
				                    $h = floor($job_time_min / 60);
				                  }
				                  if(($job_time_min -   floor($job_time_min / 60) * 60) <= 9)
				                  {
				                    $m = '0'.($job_time_min -   floor($job_time_min / 60) * 60);
				                  }
				                  else{
				                    $m = ($job_time_min -   floor($job_time_min / 60) * 60);
				                  }
				                  $explode_job_minutes = explode(":",$h.':'.$m.':00');
                					$total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
				                  $job_time_checked = $h.':'.$m.':00 ('.$total_minutes.')';
		                }
		                else{
		                	$job_time_checked = 'N/A';
		                }
		                
		                if($jobs->comments != "") { $comments = $jobs->comments; } else { $comments = 'No Comments Found'; }


		                $ratelist = DB::table('user_cost')->where('user_id', $jobs->user_id)->get();

		                $rate_result = '0';
	                    $cost = '0';

	                    if(count($ratelist)){
	                      foreach ($ratelist as $rate) {
	                        $job_date = strtotime($jobs->job_date);
	                        $from_date = strtotime($rate->from_date);
	                        $to_date = strtotime($rate->to_date);

	                        if($rate->to_date != '0000-00-00'){                         
	                          if($job_date >= $from_date  && $job_date <= $to_date){
	                            
	                            $rate_result = $rate->cost;                            
	                            $cost = ($rate_result/60)*$total_minutes;
	                          }
	                        }
	                        else{
	                          if($job_date >= $from_date){
	                            $rate_result = $rate->cost;
	                            $cost = ($rate_result/60)*$total_minutes;
	                          }
	                        }
	                      }                      
	                    }


		        $output.='
		        <tr>
		    	   <td>
		          <input type="checkbox" name="select_job" class="select_job class_'.$jobs->id.'" data-element="'.$jobs->id.'" value="'.$jobs->id.'"><label>&nbsp</label>
		          </td>
		          <td align="left">'.$i.'</td>
		          <td align="left">'.$user_details->lastname.' '.$user_details->firstname.'</td>
		          <td align="left">'.number_format_invoice_without_decimal($rate_result).'</td>
		          <td align="left">'.$client_name.'</td>
		          <td align="left">'.$task_name.'</td>
		          <td align="left">'.$task_type.'</td>
		          <td align="left">'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
		          <td align="left">'.date('H:i:s', strtotime($jobs->start_time)).'</td>
		          <td align="left">'.date('H:i:s', strtotime($jobs->stop_time)).'</td>
		          <td align="left">'.number_format_invoice_without_decimal($cost).'</td>
		          <td align="left">'.$job_time_checked.'</td>
		          <td align="center">
		          <a href="javascript:" class="fa fa-comment" data-toggle="modal" data-target="#comments_'.$jobs->id.'" title="View Comments"></a>
			        <div id="comments_'.$jobs->id.'" class="modal fade" role="dialog" >
			            <div class="modal-dialog" style="width:20%">
			              <div class="modal-content">
			                <div class="modal-header">
			                  <button type="button" class="close" data-dismiss="modal">&times;</button>
			                  <h4 class="modal-title">Comments</h4>
			                </div>
			                <div class="modal-body">
			                  '.$comments.'
			                </div>
			                <div class="modal-footer">
			                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			                </div>
			              </div>
			            </div>
			          </div>
			      </td>
		        </tr>';
		          $i++;
		             }
	          	}
	            
	          }              
	        }
	        if($i == 1){
	          $output.= '<tr>
	                    <td align="left"></td>
	                    <td align="left"></td>
	                    <td align="left"></td>                        
	                    <td align="left"></td>
	                    <td align="left"></td>
	                    <td align="left"></td>
	                    <td align="center">Empty</td>
	                    <td align="left"></td>
	                    <td align="left"></td>
	                    <td align="left"></td>
	                    <td align="left"></td>
	                    <td align="left"></td>
	                    <td align="left"></td>
	                    </tr>';
	        }			

		}





		

		
        echo $output;

		
	}

	public function joboftheday_reportpdf(){
		
		$ids = explode(',', Input::get('value'));			
		$joblist = DB::table('task_job')->whereIn('id', $ids)->get();

		$output='';
        $i=1;            
        if(count($joblist)){              
          foreach ($joblist as $jobs) {
            
                $client_details = DB::table('cm_clients')->where('client_id', $jobs->client_id)->first();
                if(count($client_details) != ''){
                  $client_name = $client_details->company.' ('.$jobs->client_id.')';
                }
                else{
                  $client_name = 'N/A';
                }

                $task_details = DB::table('time_task')->where('id', $jobs->task_id)->first();

                if(count($task_details) != ''){
                  $task_name = $task_details->task_name;
                  $task_type = $task_details->task_type;

                  if($task_type == 0){
                    $task_type = '<i class="fa fa-desktop" style="margin-right:10px;"></i> Internal Task';
                  }
                  else if($task_type == 1){
                    $task_type = '<i class="fa fa-users" style="margin-right:10px;"></i> Client Task';
                  }
                  else{
                    $task_type = '<i class="fa fa-globe" style="margin-right:10px;"></i> Global Task';
                  }
                }
                else{
                  $task_name = 'N/A';
                  $task_type = 'N/A';
                }

                

                $user_details = DB::table('user')->where('user_id', $jobs->user_id)->first();

                //-----------Job Time Start----------------

                $created_date = $jobs->job_created_date;

                $jobstart = strtotime($created_date.' '.$jobs->start_time);
                $jobend   = strtotime($created_date.' '.date('H:i:s'));
                

                if($jobend < $jobstart)
                {
                  $todate = date('Y-m-d', strtotime("+1 day", $jobend));
                  $jobend   = strtotime($todate.' '.date('H:i:s'));
                }

                $jobdiff  = $jobend - $jobstart;



                $hours = floor($jobdiff / (60 * 60));
                $minutes = $jobdiff - $hours * (60 * 60);
                $minutes = floor( $minutes / 60 );
                $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
                if($hours <= 9)
                {
                  $hours = '0'.$hours;
                }
                else{
                  $hours = $hours;
                }
                if($minutes <= 9)
                {
                  $minutes = '0'.$minutes;
                }
                else{
                  $minutes = $minutes;
                }
                if($second <= 9)
                {
                  $second = '0'.$second;
                }
                else{
                  $second = $second;
                }

                $jobtime =   $hours.':'.$minutes.':'.$second;

                //-----------Job Time End----------------


                if($jobs->status == 0){
	                $job_time_checked = $jobtime;
	              }
	              else if($jobs->status == 1){
	                  $get_quick_jobs = DB::table('task_job')->where('active_id',$jobs->id)->get();
	                    $quick_minutes = 0;
	                    if(count($get_quick_jobs))
	                    {
	                      foreach($get_quick_jobs as $quickjobs_single)
	                      {
	                        $total_quick_jobs_1 = explode(':', $quickjobs_single->job_time);
	                        $minutes = ($total_quick_jobs_1[0]*60) + ($total_quick_jobs_1[1]) + ($total_quick_jobs_1[2]/60);
	                        if($quick_minutes == 0)
	                        {
	                          $quick_minutes = $minutes;
	                        }
	                        else{
	                          $quick_minutes = $quick_minutes + $minutes;
	                        }
	                      }
	                    }

	                    $break_time_min = DB::table('job_break_time')->where('job_id',$jobs->id)->where('break_time','!=','00:00:00')->first();
                        $break_timee_minutes = 0;
                        if(count($break_time_min))
                        {
                          $break_timee = explode(':', $break_time_min->break_time);
                          $break_timee_minutes = ($break_timee[0]*60) + ($break_timee[1]) + ($break_timee[2]/60);
                        }


                        $quick_minutes = $quick_minutes + $break_timee_minutes;

	                    $job_timee = explode(':', $jobs->job_time);
	                    $job_timee_minutes = ($job_timee[0]*60) + ($job_timee[1]) + ($job_timee[2]/60);

	                    $job_time_min = $job_timee_minutes - $quick_minutes;

	                    if(floor($job_time_min / 60) <= 9)
	                    {
	                      $h = '0'.floor($job_time_min / 60);
	                    }
	                    else{
	                      $h = floor($job_time_min / 60);
	                    }
	                    if(($job_time_min -   floor($job_time_min / 60) * 60) <= 9)
	                    {
	                      $m = '0'.($job_time_min -   floor($job_time_min / 60) * 60);
	                    }
	                    else{
	                      $m = ($job_time_min -   floor($job_time_min / 60) * 60);
	                    }
	                    $job_time_checked = $h.':'.$m.':00';
	              }
	              else{
	                $job_time_checked = 'N/A';
	              }
                

        $output.='
        <tr>          
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$i.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$user_details->lastname.' '.$user_details->firstname.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$client_name.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$task_name.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$task_type.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.date('H:i:s', strtotime($jobs->start_time)).'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.date('H:i:s', strtotime($jobs->stop_time)).'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">

          '.$job_time_checked.'

          </td>
          
          
        </tr>';
        $joblist_child = DB::table('task_job')->where('active_id',$jobs->id)->get();
        $ichild=1;            
        if(count($joblist_child)){              
          foreach ($joblist_child as $child) {
            
                $client_details = DB::table('cm_clients')->where('client_id', $child->client_id)->first();
                if(count($client_details) != ''){
                  $client_name = $client_details->company.' ('.$child->client_id.')';
                }
                else{
                  $client_name = 'N/A';
                }

                $task_details = DB::table('time_task')->where('id', $child->task_id)->first();

                if(count($task_details) != ''){
                  $task_name = $task_details->task_name;
                  $task_type = $task_details->task_type;

                  if($task_type == 0){
                    $task_type = '<i class="fa fa-desktop" style="margin-right:10px;"></i> Internal Task';
                  }
                  else if($task_type == 1){
                    $task_type = '<i class="fa fa-users" style="margin-right:10px;"></i> Client Task';
                  }
                  else{
                    $task_type = '<i class="fa fa-globe" style="margin-right:10px;"></i> Global Task';
                  }
                }
                else{
                  $task_name = 'N/A';
                  $task_type = 'N/A';
                }

                

                $user_details = DB::table('user')->where('user_id', $child->user_id)->first();

                //-----------Job Time Start----------------

                $created_date = $child->job_created_date;

                $jobstart = strtotime($created_date.' '.$child->start_time);
                $jobend   = strtotime($created_date.' '.date('H:i:s'));
                

                if($jobend < $jobstart)
                {
                  $todate = date('Y-m-d', strtotime("+1 day", $jobend));
                  $jobend   = strtotime($todate.' '.date('H:i:s'));
                }

                $jobdiff  = $jobend - $jobstart;



                $hours = floor($jobdiff / (60 * 60));
                $minutes = $jobdiff - $hours * (60 * 60);
                $minutes = floor( $minutes / 60 );
                $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
                if($hours <= 9)
                {
                  $hours = '0'.$hours;
                }
                else{
                  $hours = $hours;
                }
                if($minutes <= 9)
                {
                  $minutes = '0'.$minutes;
                }
                else{
                  $minutes = $minutes;
                }
                if($second <= 9)
                {
                  $second = '0'.$second;
                }
                else{
                  $second = $second;
                }

                $jobtime =   $hours.':'.$minutes.':'.$second;

                //-----------Job Time End----------------

                if($child->status == 0){
	                $job_time_checked = $jobtime;
	              }
	              else if($child->status == 1){
	                  $job_time_checked = $child->job_time;
	              }
	              else{
	                $job_time_checked = 'N/A';
	              }
                

        $output.='
        <tr>          
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$i.'.'.$ichild.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$user_details->lastname.' '.$user_details->firstname.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$client_name.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$task_name.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$task_type.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.date('d-M-Y', strtotime($child->job_date)).'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.date('H:i:s', strtotime($child->start_time)).'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.date('H:i:s', strtotime($child->stop_time)).'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">

          '.$job_time_checked.'

          </td>
          
          
        </tr>';
        $ichild++;
		    }
		}
          $i++;
              
          }              
        }
        if($i == 1){
          $output.= '<tr>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left"></td>                        
                    <td align="left"></td>
                    <td align="center">Empty</td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left"></td>
                    </tr>';
        }
        echo $output;
	}


	public function joboftheday_report_pdf_download(){

		$htmlval = Input::get('htmlval');
		$pdf = PDF::loadHTML($htmlval);
		$pdf->setPaper('A4', 'landscape');
		$pdf->save('job_file/Job of the day.pdf');
		echo 'Job of the day.pdf';
	}
	public function clientreview_report_pdf(){
		
		$ids = explode(',', Input::get('value'));			
		$joblist = DB::table('task_job')->whereIn('id', $ids)->get();
		$client_id = Input::get('client_id');

		$client_details = DB::table('cm_clients')->where('client_id', $client_id)->first();

		$search_checkbox = Input::get('search_checkbox');

		if($search_checkbox == 0){

			$tasks = explode(',',Input::get('tasks'));			
			$tasks_implode = Input::get('tasks');			

			$users = explode(',',Input::get('users'));
			$users_implode = Input::get('users');	

			$start_date = Input::get('start_date');
			$stop_date = Input::get('stop_date');


			$output_task='';

			if(count($tasks)){
				foreach ($tasks as $task) {
					$task_details = DB::table('time_task')->where('id', $task)->first();
					$output_task.=$task_details->task_name.'<br/>';
				}
			}

			$output_user='';
			if(count($users)){
				foreach ($users as $user) {
					$user_details = DB::table('user')->where('user_id', $user)->first();
					$output_user.=$user_details->lastname.' '.$user_details->firstname.'<br/>';
				}
			}

			$title_result = '
			<h3 id="pdf_title_all_ivoice" style="width: 100%; text-align: center; margin: 15px 0px; float: left;">Client Review for '.$client_details->company.'</h3>
			<table class="table_style">
			<tr>
				<td align="center" colspan="4" valign="top"  style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px"><b>Task</b><br/>'.$output_task.'</td>
				<td align="center" colspan="4" valign="top" style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px"><b>Staff</b><br/>'.$output_user.'</td>
				<td align="center" valign="top" style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px"><b>Start Date:</b><br/>'.$start_date.'<br/>End Date: '.$stop_date.'</td>
			</tr>

			
      <tr style="background: #fff;">        
        <td width="2%" style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">S.No</td>
        <td style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">User Name</td>
        <td style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Client Name</td>
        <td style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Task Name</td>
        <td style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Task Type</td>
        <td style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Date</td>
        <td style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Start Time</td>
        <td style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Stop Time</td>
        <td style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Job Time</td>             
    </tr>';
		}

		elseif($search_checkbox == 1){

			$tasks = explode(',',Input::get('tasks'));			
			$tasks_implode = Input::get('tasks');			

			$users = explode(',',Input::get('users'));
			$users_implode = Input::get('users');	

			$output_task='';

			if(count($tasks)){
				foreach ($tasks as $task) {
					$task_details = DB::table('time_task')->where('id', $task)->first();
					$output_task.=$task_details->task_name.'<br/>';
				}
			}

			$output_user='';
			if(count($users)){
				foreach ($users as $user) {
					$user_details = DB::table('user')->where('user_id', $user)->first();
					$output_user.=$user_details->lastname.' '.$user_details->firstname.'<br/>';
				}
			}

			$title_result = '
			<h3 id="pdf_title_all_ivoice" style="width: 100%; text-align: center; margin: 15px 0px; float: left;">Client Review for '.$client_details->company.'</h3>
			<table class="table_style">
			<tr>
				<td align="center" colspan="4" valign="top"  style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px"><b>Task</b><br/>'.$output_task.'</td>
				<td align="center" colspan="4" valign="top" style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px"><b>Staff</b><br/>'.$output_user.'</td>
				<td align="center" valign="top" style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px"><b>All Dates</b></td>
			</tr>

			
      <tr style="background: #fff;">        
        <td width="2%" style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">S.No</td>
        <td style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">User Name</td>
        <td style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Client Name</td>
        <td style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Task Name</td>
        <td style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Task Type</td>
        <td style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Date</td>
        <td style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Start Time</td>
        <td style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Stop Time</td>
        <td style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Job Time</td>             
    </tr>';
		}

		elseif($search_checkbox == 2){

					

			
			

			$title_result = '
			<h3 id="pdf_title_all_ivoice" style="width: 100%; text-align: center; margin: 15px 0px; float: left;">Client Review for '.$client_details->company.'</h3>
			<table class="table_style">
			<tr>
				<td align="center" colspan="9" valign="top"  style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px; text-align:center"><b>Select All Task, Staffs and Dates</b></td>
				
			</tr>

			
      <tr style="background: #fff;">        
        <td width="2%" style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">S.No</td>
        <td style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">User Name</td>
        <td style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Client Name</td>
        <td style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Task Name</td>
        <td style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Task Type</td>
        <td style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Date</td>
        <td style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Start Time</td>
        <td style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Stop Time</td>
        <td style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Job Time</td>             
    </tr>';
		}

			

		$output=$title_result;
        $i=1;            
        if(count($joblist)){              
          foreach ($joblist as $jobs) {
            
                $client_details = DB::table('cm_clients')->where('client_id', $jobs->client_id)->first();
                if(count($client_details) != ''){
                  $client_name = $client_details->company.' ('.$jobs->client_id.')';
                }
                else{
                  $client_name = 'N/A';
                }

                $task_details = DB::table('time_task')->where('id', $jobs->task_id)->first();

                if(count($task_details) != ''){
                  $task_name = $task_details->task_name;
                  $task_type = $task_details->task_type;

                  if($task_type == 0){
                    $task_type = '<i class="fa fa-desktop" style="margin-right:10px;"></i> Internal Task';
                  }
                  else if($task_type == 1){
                    $task_type = '<i class="fa fa-users" style="margin-right:10px;"></i> Client Task';
                  }
                  else{
                    $task_type = '<i class="fa fa-globe" style="margin-right:10px;"></i> Global Task';
                  }
                }
                else{
                  $task_name = 'N/A';
                  $task_type = 'N/A';
                }

                $break_time_count = DB::table('job_break_time')->where('job_id', $jobs->id)->get();
                                    
                

                $user_details = DB::table('user')->where('user_id', $jobs->user_id)->first();

                //-----------Job Time Start----------------

                $created_date = $jobs->job_created_date;

                $jobstart = strtotime($created_date.' '.$jobs->start_time);
                $jobend   = strtotime($created_date.' '.date('H:i:s'));
                

                if($jobend < $jobstart)
                {
                  $todate = date('Y-m-d', strtotime("+1 day", $jobend));
                  $jobend   = strtotime($todate.' '.date('H:i:s'));
                }

                $jobdiff  = $jobend - $jobstart;



                $hours = floor($jobdiff / (60 * 60));
                $minutes = $jobdiff - $hours * (60 * 60);
                $minutes = floor( $minutes / 60 );
                $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
                if($hours <= 9)
                {
                  $hours = '0'.$hours;
                }
                else{
                  $hours = $hours;
                }
                if($minutes <= 9)
                {
                  $minutes = '0'.$minutes;
                }
                else{
                  $minutes = $minutes;
                }
                if($second <= 9)
                {
                  $second = '0'.$second;
                }
                else{
                  $second = $second;
                }

                $jobtime =   $hours.':'.$minutes.':'.$second;

                //-----------Job Time End----------------


                if($jobs->status == 0){
	                $job_time_checked = $jobtime;
	              }
	              else if($jobs->status == 1){
	                  $get_quick_jobs = DB::table('task_job')->where('active_id',$jobs->id)->get();
	                    $quick_minutes = 0;
	                    if(count($get_quick_jobs))
	                    {
	                      foreach($get_quick_jobs as $quickjobs_single)
	                      {
	                        $total_quick_jobs_1 = explode(':', $quickjobs_single->job_time);
	                        $minutes = ($total_quick_jobs_1[0]*60) + ($total_quick_jobs_1[1]) + ($total_quick_jobs_1[2]/60);
	                        if($quick_minutes == 0)
	                        {
	                          $quick_minutes = $minutes;
	                        }
	                        else{
	                          $quick_minutes = $quick_minutes + $minutes;
	                        }
	                      }
	                    }

	                    $break_time_min = DB::table('job_break_time')->where('job_id',$jobs->id)->where('break_time','!=','00:00:00')->first();
                        $break_timee_minutes = 0;
                        if(count($break_time_min))
                        {
                          $break_timee = explode(':', $break_time_min->break_time);
                          $break_timee_minutes = ($break_timee[0]*60) + ($break_timee[1]) + ($break_timee[2]/60);
                        }
                        $quick_minutes = $quick_minutes + $break_timee_minutes;

	                    $job_timee = explode(':', $jobs->job_time);
	                    $job_timee_minutes = ($job_timee[0]*60) + ($job_timee[1]) + ($job_timee[2]/60);

	                    $job_time_min = $job_timee_minutes - $quick_minutes;

	                    if(floor($job_time_min / 60) <= 9)
	                    {
	                      $h = '0'.floor($job_time_min / 60);
	                    }
	                    else{
	                      $h = floor($job_time_min / 60);
	                    }
	                    if(($job_time_min -   floor($job_time_min / 60) * 60) <= 9)
	                    {
	                      $m = '0'.($job_time_min -   floor($job_time_min / 60) * 60);
	                    }
	                    else{
	                      $m = ($job_time_min -   floor($job_time_min / 60) * 60);
	                    }
	                    $job_time_checked = $h.':'.$m.':00';
	              }
	              else{
	                $job_time_checked = 'N/A';
	              }
                

        $output.='
        <tr>          
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$i.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$user_details->lastname.' '.$user_details->firstname.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$client_name.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$task_name.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$task_type.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.date('H:i:s', strtotime($jobs->start_time)).'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.date('H:i:s', strtotime($jobs->stop_time)).'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">

          '.$job_time_checked.'

          </td>
          
          
        </tr>';
          $i++;
              
          }              
        }
        if($i == 1){
          $output.= '<tr>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left"></td>                        
                    <td align="left"></td>
                    <td align="center">Empty</td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left"></td>
                    </tr>';
        }
        $output.='</table>';
        echo $output;
	}


	public function clientreview_report_pdf_download(){

		$htmlval = Input::get('htmlval');
		$pdf = PDF::loadHTML($htmlval);
		$pdf->setPaper('A4', 'landscape');
		$pdf->save('job_file/Client Review.pdf');
		echo 'Client Review.pdf';
	}

	public function clientreview_report_csv(){
		
		$ids = explode(',', Input::get('value'));
		$job_details = DB::table('task_job')->whereIn('id', $ids)->get();
		$client_id = Input::get('client_id');

		$client_details = DB::table('cm_clients')->where('client_id', $client_id)->first();
		$search_checkbox = Input::get('search_checkbox');
		if($search_checkbox == 0){
			$tasks = explode(',',Input::get('tasks'));			
			$tasks_implode = Input::get('tasks');			

			$users = explode(',',Input::get('users'));
			$users_implode = Input::get('users');	

			$start_date = Input::get('start_date');
			$stop_date = Input::get('stop_date');

			$headers = array(
		        "Content-type" => "text/csv",
		        "Content-Disposition" => "attachment; filename=CM_Report.csv",
		        "Pragma" => "no-cache",
		        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
		        "Expires" => "0"
		    );      	
			
			$callback = function() use ($job_details, $client_details,$start_date,$stop_date,$tasks,$users)
	    	{
	    		$file = fopen('job_file/clientreview_Report.csv', 'w');
				$columns_title= array('', '', '', '', 'Client Review for '.$client_details->company.'', '', '','', '');
				fputcsv($file, $columns_title);

				$output_task='';
				$length_tasks = count($tasks);
				$length_users = count($users);

				if($length_tasks > $length_users){
					$len = $length_tasks;
				}
				else{
					$len = $length_users;
				}
				for($i = 0; $i<=$len; $i++)
				{
					if(isset($tasks[$i]))
					{
						$task = $tasks[$i];
						if($task != "")
						{
							$task_details = DB::table('time_task')->where('id', $task)->first();
							$task_name = $task_details->task_name;
						}
						else{
							$task_name = '';
						}
					}
					else{
						$task_name = '';
					}
					if(isset($users[$i]))
					{
						$user = $users[$i];
						if($user != "")
						{
							$user_details = DB::table('user')->where('user_id', $user)->first();
							$username_csv = $user_details->lastname.' '.$user_details->firstname;
						}
						else{
							$username_csv = '';
						}
					}
					else{
						$username_csv = '';
					}

					if($i == 0)
					{
						$columns_title_heading= array('', '', '', 'TASKS', 'USERS', 'DATE', '','', '');
						fputcsv($file, $columns_title_heading);
						$date = 'StartDate:'.$start_date.'  EndDate:'.$stop_date.'';

						$columns_title= array('', '', '', $task_name, $username_csv, $date, '','', '');
					}
					else{
						$columns_title= array('', '', '', $task_name, $username_csv, '', '','', '');
					}
					fputcsv($file, $columns_title);

				}

				$columns = array('#', 'User', 'Client Name', 'Task Name', 'Task Type', 'Date', 'Start Time','Stop Time', 'Job Time');

			    fputcsv($file, $columns);
				$i=1;
				foreach ($job_details as $single) {				

					

					if($single->client_id != ''){
						$company_details = DB::table('cm_clients')->where('client_id', $single->client_id)->first();
						$companyname = $company_details->company;
					}
					else{
						$companyname = 'N/A';
					}				
					$user_details = DB::table('user')->where('user_id', $single->user_id)->first();
					$task_details = DB::table('time_task')->where('id', $single->task_id)->first();

					if($task_details->task_type == 0){
						$task_type = 'Internal Task';
					}
					else if($task_details->task_type == 1){
						$task_type = 'Client Task';
					}
					else{
						$task_type = 'Global Task';
					}


					$jobs = DB::table('task_job')->where('id', $single->id)->first();


					//-----------Job Time Start----------------

			        $created_date = $jobs->job_created_date;

	                $jobstart = strtotime($created_date.' '.$jobs->start_time);
	                $jobend   = strtotime($created_date.' '.date('H:i:s'));
	                

	                if($jobend < $jobstart)
	                {
	                  $todate = date('Y-m-d', strtotime("+1 day", $jobend));
	                  $jobend   = strtotime($todate.' '.date('H:i:s'));
	                }

	                $jobdiff  = $jobend - $jobstart;



			        $hours = floor($jobdiff / (60 * 60));
			        $minutes = $jobdiff - $hours * (60 * 60);
			        $minutes = floor( $minutes / 60 );
			        $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
			        if($hours <= 9)
			        {
			          $hours = '0'.$hours;
			        }
			        else{
			          $hours = $hours;
			        }
			        if($minutes <= 9)
			        {
			          $minutes = '0'.$minutes;
			        }
			        else{
			          $minutes = $minutes;
			        }
			        if($second <= 9)
			        {
			          $second = '0'.$second;
			        }
			        else{
			          $second = $second;
			        }

			        $jobtime =   $hours.':'.$minutes.':'.$second;

			        //-----------Job Time End----------------

			        if($jobs->status == 0){
		                $job_time_checked = $jobtime;
		              }
		              else if($jobs->status == 1){
		                  $get_quick_jobs = DB::table('task_job')->where('active_id',$jobs->id)->get();
		                    $quick_minutes = 0;
		                    if(count($get_quick_jobs))
		                    {
		                      foreach($get_quick_jobs as $quickjobs_single)
		                      {
		                        $total_quick_jobs_1 = explode(':', $quickjobs_single->job_time);
		                        $minutes = ($total_quick_jobs_1[0]*60) + ($total_quick_jobs_1[1]) + ($total_quick_jobs_1[2]/60);
		                        if($quick_minutes == 0)
		                        {
		                          $quick_minutes = $minutes;
		                        }
		                        else{
		                          $quick_minutes = $quick_minutes + $minutes;
		                        }
		                      }
		                    }

		                    $break_time_min = DB::table('job_break_time')->where('job_id',$jobs->id)->where('break_time','!=','00:00:00')->first();
	                        $break_timee_minutes = 0;
	                        if(count($break_time_min))
	                        {
	                          $break_timee = explode(':', $break_time_min->break_time);
	                          $break_timee_minutes = ($break_timee[0]*60) + ($break_timee[1]) + ($break_timee[2]/60);
	                        }
	                        $quick_minutes = $quick_minutes + $break_timee_minutes;

		                    $job_timee = explode(':', $jobs->job_time);
		                    $job_timee_minutes = ($job_timee[0]*60) + ($job_timee[1]) + ($job_timee[2]/60);

		                    $job_time_min = $job_timee_minutes - $quick_minutes;

		                    if(floor($job_time_min / 60) <= 9)
		                    {
		                      $h = '0'.floor($job_time_min / 60);
		                    }
		                    else{
		                      $h = floor($job_time_min / 60);
		                    }
		                    if(($job_time_min -   floor($job_time_min / 60) * 60) <= 9)
		                    {
		                      $m = '0'.($job_time_min -   floor($job_time_min / 60) * 60);
		                    }
		                    else{
		                      $m = ($job_time_min -   floor($job_time_min / 60) * 60);
		                    }
		                    $job_time_checked = $h.':'.$m.':00';
		              }
		              else{
		                $job_time_checked = 'N/A';
		              }




			      	$columns_2 = array($i, $user_details->lastname.' '.$user_details->firstname, $companyname, $task_details->task_name, $task_type, date('d-M-Y', strtotime($single->job_date)), date('H:i:s', strtotime($single->start_time)),date('H:i:s', strtotime($single->stop_time)), $job_time_checked );
					fputcsv($file, $columns_2);
					$i++;
				}
				fclose($file);
			};
		}
		elseif($search_checkbox == 1){
			$tasks = explode(',',Input::get('tasks'));			
			$tasks_implode = Input::get('tasks');			

			$users = explode(',',Input::get('users'));
			$users_implode = Input::get('users');

			$headers = array(
		        "Content-type" => "text/csv",
		        "Content-Disposition" => "attachment; filename=CM_Report.csv",
		        "Pragma" => "no-cache",
		        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
		        "Expires" => "0"
		    );      	
			
			$callback = function() use ($job_details, $client_details,$tasks,$users)
	    	{
	    		$file = fopen('job_file/clientreview_Report.csv', 'w');
				$columns_title= array('', '', '', '', 'Client Review for '.$client_details->company.'', '', '','', '');
				fputcsv($file, $columns_title);

				$output_task='';
				$length_tasks = count($tasks);
				$length_users = count($users);

				if($length_tasks > $length_users){
					$len = $length_tasks;
				}
				else{
					$len = $length_users;
				}


				for($i=0; $i<=$len; $i++)
				{
					if(isset($tasks[$i]))
					{
						$task = $tasks[$i];
						if($task != "")
						{
							$task_details = DB::table('time_task')->where('id', $task)->first();
							$task_name = $task_details->task_name;
						}
						else{
							$task_name = '';
						}
					}
					else{
						$task_name = '';
					}
					if(isset($users[$i]))
					{
						$user = $users[$i];
						if($user != "")
						{
							$user_details = DB::table('user')->where('user_id', $user)->first();
							$username_csv = $user_details->lastname.' '.$user_details->firstname;
						}
						else{
							$username_csv = '';
						}
					}
					else{
						$username_csv = '';
					}

					if($i == 0)
					{
						$columns_title_heading= array('', '', '', 'TASKS', 'USERS', 'DATE', '','', '');
						fputcsv($file, $columns_title_heading);
						$columns_title= array('', '', '', $task_name, $username_csv, 'All Dates', '','', '');
					}
					else{
						$columns_title= array('', '', '', $task_name, $username_csv, '', '','', '');
					}
					fputcsv($file, $columns_title);
				}

				$columns = array('#', 'User', 'Client Name', 'Task Name', 'Task Type', 'Date', 'Start Time','Stop Time', 'Job Time');

			    fputcsv($file, $columns);
				$i=1;
				foreach ($job_details as $single) {				

					

					if($single->client_id != ''){
						$company_details = DB::table('cm_clients')->where('client_id', $single->client_id)->first();
						$companyname = $company_details->company;
					}
					else{
						$companyname = 'N/A';
					}				
					$user_details = DB::table('user')->where('user_id', $single->user_id)->first();
					$task_details = DB::table('time_task')->where('id', $single->task_id)->first();

					if($task_details->task_type == 0){
						$task_type = 'Internal Task';
					}
					else if($task_details->task_type == 1){
						$task_type = 'Client Task';
					}
					else{
						$task_type = 'Global Task';
					}


					$jobs = DB::table('task_job')->where('id', $single->id)->first();


					//-----------Job Time Start----------------

			        $created_date = $jobs->job_created_date;

	                $jobstart = strtotime($created_date.' '.$jobs->start_time);
	                $jobend   = strtotime($created_date.' '.date('H:i:s'));
	                

	                if($jobend < $jobstart)
	                {
	                  $todate = date('Y-m-d', strtotime("+1 day", $jobend));
	                  $jobend   = strtotime($todate.' '.date('H:i:s'));
	                }

	                $jobdiff  = $jobend - $jobstart;



			        $hours = floor($jobdiff / (60 * 60));
			        $minutes = $jobdiff - $hours * (60 * 60);
			        $minutes = floor( $minutes / 60 );
			        $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
			        if($hours <= 9)
			        {
			          $hours = '0'.$hours;
			        }
			        else{
			          $hours = $hours;
			        }
			        if($minutes <= 9)
			        {
			          $minutes = '0'.$minutes;
			        }
			        else{
			          $minutes = $minutes;
			        }
			        if($second <= 9)
			        {
			          $second = '0'.$second;
			        }
			        else{
			          $second = $second;
			        }

			        $jobtime =   $hours.':'.$minutes.':'.$second;

			        //-----------Job Time End----------------

			        if($jobs->status == 0){
		                $job_time_checked = $jobtime;
		              }
		              else if($jobs->status == 1){
		                  $get_quick_jobs = DB::table('task_job')->where('active_id',$jobs->id)->get();
		                    $quick_minutes = 0;
		                    if(count($get_quick_jobs))
		                    {
		                      foreach($get_quick_jobs as $quickjobs_single)
		                      {
		                        $total_quick_jobs_1 = explode(':', $quickjobs_single->job_time);
		                        $minutes = ($total_quick_jobs_1[0]*60) + ($total_quick_jobs_1[1]) + ($total_quick_jobs_1[2]/60);
		                        if($quick_minutes == 0)
		                        {
		                          $quick_minutes = $minutes;
		                        }
		                        else{
		                          $quick_minutes = $quick_minutes + $minutes;
		                        }
		                      }
		                    }

		                    $break_time_min = DB::table('job_break_time')->where('job_id',$jobs->id)->where('break_time','!=','00:00:00')->first();
	                        $break_timee_minutes = 0;
	                        if(count($break_time_min))
	                        {
	                          $break_timee = explode(':', $break_time_min->break_time);
	                          $break_timee_minutes = ($break_timee[0]*60) + ($break_timee[1]) + ($break_timee[2]/60);
	                        }
	                        $quick_minutes = $quick_minutes + $break_timee_minutes;

		                    $job_timee = explode(':', $jobs->job_time);
		                    $job_timee_minutes = ($job_timee[0]*60) + ($job_timee[1]) + ($job_timee[2]/60);

		                    $job_time_min = $job_timee_minutes - $quick_minutes;

		                    if(floor($job_time_min / 60) <= 9)
		                    {
		                      $h = '0'.floor($job_time_min / 60);
		                    }
		                    else{
		                      $h = floor($job_time_min / 60);
		                    }
		                    if(($job_time_min -   floor($job_time_min / 60) * 60) <= 9)
		                    {
		                      $m = '0'.($job_time_min -   floor($job_time_min / 60) * 60);
		                    }
		                    else{
		                      $m = ($job_time_min -   floor($job_time_min / 60) * 60);
		                    }
		                    $job_time_checked = $h.':'.$m.':00';
		              }
		              else{
		                $job_time_checked = 'N/A';
		              }




			      	$columns_2 = array($i, $user_details->lastname.' '.$user_details->firstname, $companyname, $task_details->task_name, $task_type, date('d-M-Y', strtotime($single->job_date)), date('H:i:s', strtotime($single->start_time)),date('H:i:s', strtotime($single->stop_time)), $job_time_checked );
					fputcsv($file, $columns_2);
					$i++;
				}
				fclose($file);
			};
		}
		elseif($search_checkbox == 2){
			$headers = array(
		        "Content-type" => "text/csv",
		        "Content-Disposition" => "attachment; filename=CM_Report.csv",
		        "Pragma" => "no-cache",
		        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
		        "Expires" => "0"
		    );      	
			$callback = function() use ($job_details, $client_details)
	    	{
	    		$file = fopen('job_file/clientreview_Report.csv', 'w');
				$columns_title= array('', '', '', '', 'Client Review for '.$client_details->company.'', '', '','', '');
				fputcsv($file, $columns_title);

				$columns_tasks_users= array('', '', '', '', 'Select All Task, Staffs and Dates', '', '','', '');
				fputcsv($file, $columns_tasks_users);

				$columns = array('#', 'User', 'Client Name', 'Task Name', 'Task Type', 'Date', 'Start Time','Stop Time', 'Job Time');

			    fputcsv($file, $columns);
				$i=1;
				foreach ($job_details as $single) {
					if($single->client_id != ''){
						$company_details = DB::table('cm_clients')->where('client_id', $single->client_id)->first();
						$companyname = $company_details->company;
					}
					else{
						$companyname = 'N/A';
					}				
					$user_details = DB::table('user')->where('user_id', $single->user_id)->first();
					$task_details = DB::table('time_task')->where('id', $single->task_id)->first();

					if($task_details->task_type == 0){
						$task_type = 'Internal Task';
					}
					else if($task_details->task_type == 1){
						$task_type = 'Client Task';
					}
					else{
						$task_type = 'Global Task';
					}


					$jobs = DB::table('task_job')->where('id', $single->id)->first();


					//-----------Job Time Start----------------

			        $created_date = $jobs->job_created_date;

	                $jobstart = strtotime($created_date.' '.$jobs->start_time);
	                $jobend   = strtotime($created_date.' '.date('H:i:s'));
	                

	                if($jobend < $jobstart)
	                {
	                  $todate = date('Y-m-d', strtotime("+1 day", $jobend));
	                  $jobend   = strtotime($todate.' '.date('H:i:s'));
	                }

	                $jobdiff  = $jobend - $jobstart;



			        $hours = floor($jobdiff / (60 * 60));
			        $minutes = $jobdiff - $hours * (60 * 60);
			        $minutes = floor( $minutes / 60 );
			        $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
			        if($hours <= 9)
			        {
			          $hours = '0'.$hours;
			        }
			        else{
			          $hours = $hours;
			        }
			        if($minutes <= 9)
			        {
			          $minutes = '0'.$minutes;
			        }
			        else{
			          $minutes = $minutes;
			        }
			        if($second <= 9)
			        {
			          $second = '0'.$second;
			        }
			        else{
			          $second = $second;
			        }

			        $jobtime =   $hours.':'.$minutes.':'.$second;

			        //-----------Job Time End----------------

			        if($jobs->status == 0){
		                $job_time_checked = $jobtime;
		              }
		              else if($jobs->status == 1){
		                  $get_quick_jobs = DB::table('task_job')->where('active_id',$jobs->id)->get();
		                    $quick_minutes = 0;
		                    if(count($get_quick_jobs))
		                    {
		                      foreach($get_quick_jobs as $quickjobs_single)
		                      {
		                        $total_quick_jobs_1 = explode(':', $quickjobs_single->job_time);
		                        $minutes = ($total_quick_jobs_1[0]*60) + ($total_quick_jobs_1[1]) + ($total_quick_jobs_1[2]/60);
		                        if($quick_minutes == 0)
		                        {
		                          $quick_minutes = $minutes;
		                        }
		                        else{
		                          $quick_minutes = $quick_minutes + $minutes;
		                        }
		                      }
		                    }

		                    $break_time_min = DB::table('job_break_time')->where('job_id',$jobs->id)->where('break_time','!=','00:00:00')->first();
	                        $break_timee_minutes = 0;
	                        if(count($break_time_min))
	                        {
	                          $break_timee = explode(':', $break_time_min->break_time);
	                          $break_timee_minutes = ($break_timee[0]*60) + ($break_timee[1]) + ($break_timee[2]/60);
	                        }
	                        $quick_minutes = $quick_minutes + $break_timee_minutes;

		                    $job_timee = explode(':', $jobs->job_time);
		                    $job_timee_minutes = ($job_timee[0]*60) + ($job_timee[1]) + ($job_timee[2]/60);

		                    $job_time_min = $job_timee_minutes - $quick_minutes;

		                    if(floor($job_time_min / 60) <= 9)
		                    {
		                      $h = '0'.floor($job_time_min / 60);
		                    }
		                    else{
		                      $h = floor($job_time_min / 60);
		                    }
		                    if(($job_time_min -   floor($job_time_min / 60) * 60) <= 9)
		                    {
		                      $m = '0'.($job_time_min -   floor($job_time_min / 60) * 60);
		                    }
		                    else{
		                      $m = ($job_time_min -   floor($job_time_min / 60) * 60);
		                    }
		                    $job_time_checked = $h.':'.$m.':00';
		              }
		              else{
		                $job_time_checked = 'N/A';
		              }




			      	$columns_2 = array($i, $user_details->lastname.' '.$user_details->firstname, $companyname, $task_details->task_name, $task_type, date('d-M-Y', strtotime($single->job_date)), date('H:i:s', strtotime($single->start_time)),date('H:i:s', strtotime($single->stop_time)), $job_time_checked );
					fputcsv($file, $columns_2);
					$i++;
				}
				fclose($file);
			};
		}
		return Response::stream($callback, 200, $headers);
		//return $filename.'_InvoiceReport.csv';

	}

	public function get_quick_break_details()
	{
		$jobid = Input::get('jobid');
		$getdetails = DB::table('task_job')->where('id',$jobid)->first();
		$explode = explode(":",$getdetails->start_time);
		echo json_encode(array('userid' => $getdetails->user_id,'start_time' => $getdetails->start_time,'hour' => $explode[0],'min' => $explode[1]));
	}
	public function calculate_job_time()
	{
		$curr_date = date('Y-m-d');
		$jobstart = strtotime($curr_date.' '.Input::get('start_time').':00');
		$jobend = strtotime($curr_date.' '.Input::get('stop_time').':00');

		$jobdiff  = $jobend - $jobstart;

        $hours = floor($jobdiff / (60 * 60));
        $minutes = $jobdiff - $hours * (60 * 60);
        $minutes = floor( $minutes / 60 );
        $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
        if($hours <= 9)
        {
          $hours = '0'.$hours;
        }
        else{
          $hours = $hours;
        }
        if($minutes <= 9)
        {
          $minutes = '0'.$minutes;
        }
        else{
          $minutes = $minutes;
        }
        if($second <= 9)
        {
          $second = '0'.$second;
        }
        else{
          $second = $second;
        }

        $jobtime =   $hours.':'.$minutes.':'.$second;

        $total_quick_jobs = $_GET['total_quick_jobs'];
        $total_breaks = $_GET['total_breaks'];

        if($total_quick_jobs == "" || $total_quick_jobs == "00:00:00")
        {
        	$total_quick_jobs_minutes = 0;
        }
        else{
        	$total_quick_jobs_1 = explode(':', $total_quick_jobs);
			$minutes = ($total_quick_jobs_1[0]*60) + ($total_quick_jobs_1[1]) + ($total_quick_jobs_1[2]/60);
			$total_quick_jobs_minutes = $minutes;
        }
		
		if($total_breaks == "" || $total_breaks == "00:00:00")
        {
        	$total_breaks_minutes = 0;
        }
        else{
			$total_breaks_1 = explode(':', $total_breaks);
			$breaks_minutes = ($total_breaks_1[0]*60) + ($total_breaks_1[1]) + ($total_breaks_1[2]/60);
			$total_breaks_minutes = $breaks_minutes;
		}

		$sum_of_breaks = $total_breaks_minutes + $total_quick_jobs_minutes;

		$jobtime_1 = explode(':', $jobtime);
		$job_minutes = ($jobtime_1[0]*60) + ($jobtime_1[1]) + ($jobtime_1[2]/60);
		$jobtime_minutes = $job_minutes;

		$total_time_minutes = $jobtime_minutes - $sum_of_breaks;

		if($total_time_minutes < 0)
		{
			$alert =1;
			$total_time_minutes = str_replace("-","",$total_time_minutes);
			if(floor($total_time_minutes / 60) <= 9)
	          {
	            $h = floor($total_time_minutes / 60);
	          }
	          else{
	            $h = floor($total_time_minutes / 60);
	          }
	          if(($total_time_minutes -   floor($total_time_minutes / 60) * 60) <= 9)
	          {
	            $m = '0'.($total_time_minutes -   floor($total_time_minutes / 60) * 60);
	          }
	          else{
	            $m = ($total_time_minutes -   floor($total_time_minutes / 60) * 60);
	          }
	          $total_time_minutes_format = '-'.$h.':'.$m.':00';
		}
		else{
			if(floor($total_time_minutes / 60) <= 9)
	          {
	            $h = '0'.floor($total_time_minutes / 60);
	          }
	          else{
	            $h = floor($total_time_minutes / 60);
	          }
	          if(($total_time_minutes -   floor($total_time_minutes / 60) * 60) <= 9)
	          {
	            $m = '0'.($total_time_minutes -   floor($total_time_minutes / 60) * 60);
	          }
	          else{
	            $m = ($total_time_minutes -   floor($total_time_minutes / 60) * 60);
	          }
	          $total_time_minutes_format = $h.':'.$m.':00';
	          $alert =0;
		}
		$explode_job_minutes = explode(":",$jobtime);
                    $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
        echo json_encode(array('jobtime' => $jobtime, 'total_time_minutes_format' => $total_time_minutes_format,'alert' => $alert));
	}
	public function calculate_break_time()
	{
		$added_time = Input::get('element');
		$already_time = Input::get('break_time');
		$jobtime = Input::get('jobtime');
		$total_quick_jobs = Input::get('total_quick_jobs');



		$time = explode(':', $jobtime);
		$count_jon_time_minutes = ($time[0]*60) + ($time[1]) + ($time[2]/60);
		$count_minues = $already_time + $added_time;
			if($count_minues == 0)
	        {
	          $break_hours = '';
	          $break_hours_another = '';
	        }
	        elseif($count_minues < 60)
	        {
	          $break_hours = $count_minues.' Minutes';
	          $break_hours_another = '00:'.$count_minues.':00';
	        }
	        elseif($count_minues == 60)
	        {
	          $break_hours = '1 Hour';
	          $break_hours_another = '01:00:00';
	        }
	        else{
	          if(floor($count_minues / 60) <= 9)
	          {
	            $h = floor($count_minues / 60);
	          }
	          else{
	            $h = floor($count_minues / 60);
	          }
	          if(($count_minues -   floor($count_minues / 60) * 60) <= 9)
	          {
	            $m = ($count_minues -   floor($count_minues / 60) * 60);
	          }
	          else{
	            $m = ($count_minues -   floor($count_minues / 60) * 60);
	          }
	          if($m == "00")
	          {
	            $break_hours = $h.' Hours';
	            $break_hours_another = $h.':00:00';
	          }
	          else{
	            $break_hours = $h.':'.$m.' Hours';
	            $break_hours_another = $h.':'.$m.':00';
	          }
	        }

	        $total_quick_jobs_1 = explode(':', $total_quick_jobs);
			$minutes = ($total_quick_jobs_1[0]*60) + ($total_quick_jobs_1[1]) + ($total_quick_jobs_1[2]/60);
			$total_quick_jobs_minutes = $minutes;
			
			$total_breaks_minutes = $count_minues;
			$sum_of_breaks = $total_breaks_minutes + $total_quick_jobs_minutes;


			$jobtime_1 = explode(':', $jobtime);
			$job_minutes = ($jobtime_1[0]*60) + ($jobtime_1[1]) + ($jobtime_1[2]/60);
			$jobtime_minutes = $job_minutes;

			$total_time_minutes = $jobtime_minutes - $sum_of_breaks;

			


			if($total_time_minutes < 0)
			{
				$alert = 1;
				$total_time_minutes = str_replace("-","",$total_time_minutes);
				if(floor($total_time_minutes / 60) <= 9)
		          {
		            $h = floor($total_time_minutes / 60);
		          }
		          else{
		            $h = floor($total_time_minutes / 60);
		          }
		          if(($total_time_minutes -   floor($total_time_minutes / 60) * 60) <= 9)
		          {
		            $m = '0'.($total_time_minutes -   floor($total_time_minutes / 60) * 60);
		          }
		          else{
		            $m = ($total_time_minutes -   floor($total_time_minutes / 60) * 60);
		          }
		          $total_time_minutes_format = '-'.$h.':'.$m.':00';
			}
			else{
				if(floor($total_time_minutes / 60) <= 9)
		          {
		            $h = '0'.floor($total_time_minutes / 60);
		          }
		          else{
		            $h = floor($total_time_minutes / 60);
		          }
		          if(($total_time_minutes -   floor($total_time_minutes / 60) * 60) <= 9)
		          {
		            $m = '0'.($total_time_minutes -   floor($total_time_minutes / 60) * 60);
		          }
		          else{
		            $m = ($total_time_minutes -   floor($total_time_minutes / 60) * 60);
		          }
		          $total_time_minutes_format = $h.':'.$m.':00';
		          $alert = 0;
			}
	        echo json_encode(array('alert' => $alert, 'break_hours' => $break_hours,'break_hours_another' => $break_hours_another, 'count' => $count_minues,'total_time_minutes_format' => $total_time_minutes_format));
		
	}
	public function check_time_me_user_active_job()
	{
		$userid = Input::get('userid');
		$check_db = DB::table('task_job')->where('user_id',$userid)->where('quick_job',0)->where('status',0)->count();
		echo $check_db;
	}
	public function check_last_finished_job_time()
	{
		$userid = Input::get('userid');
		$date = date('Y-m-d');
		$check_db = DB::table('task_job')->where('user_id',$userid)->where('job_date',$date)->where('active_id',0)->orderBy('id','desc')->first();
		if(count($check_db))
		{
			if($check_db->stop_time == "00:00:00")
			{
				$check_db_quick = DB::table('task_job')->where('user_id',$userid)->where('job_date',$date)->where('stop_time','!=','00:00:00')->orderBy('id','desc')->first();
				if(count($check_db_quick))
				{
					$exp_time = explode(":",$check_db_quick->stop_time);
					echo $exp_time[0].':'.$exp_time[1];
				}
				else{
					echo date('H:i');
				}
			}
			else{
				$exp_time = explode(":",$check_db->stop_time);
				echo $exp_time[0].':'.$exp_time[1];
			}
		}
		else{
			echo date('H:i');
		}
	}
	public function search_staff_review()
	{
		$user = Input::get('user');
		$from_date = strtotime(Input::get('from_date'));
		$to_date = strtotime(Input::get('to_date'));

		$get_tasks = DB::select('SELECT * from `task_job` WHERE user_id IN ('.$user.') AND UNIX_TIMESTAMP(`job_date`) >= "'.$from_date.'" AND UNIX_TIMESTAMP(`job_date`) <= "'.$to_date.'" GROUP BY `task_id`');
		$output = '';
		$outputval = '';
		if(count($get_tasks))
		{
			$i = 1;
			$j = 1;
			$hour_total = '';
			$minute_total = '';
			$minutes_sub_total = '';
			foreach($get_tasks as $task)
			{
				$get_tasks_time_count = DB::select('SELECT * from `task_job` WHERE user_id IN ('.$user.') AND task_id = "'.$task->task_id.'" AND UNIX_TIMESTAMP(`job_date`) >= "'.$from_date.'" AND UNIX_TIMESTAMP(`job_date`) <= "'.$to_date.'"');
				$tot_mins = 0;
				
				
				if(count($get_tasks_time_count)){
					foreach($get_tasks_time_count as $count)
					{
						$client_details = DB::table('cm_clients')->where('client_id', $count->client_id)->first();
			            if(count($client_details) != ''){
			              $client_name = $client_details->company.' ('.$count->client_id.')';
			            }
			            else{
			              $client_name = 'N/A';
			            }

			            $client_details = DB::table('cm_clients')->where('client_id', $count->client_id)->first();
		                if(count($client_details) != ''){
		                  $client_name = $client_details->company.' ('.$count->client_id.')';
		                }
		                else{
		                  $client_name = 'N/A';
		                }

		                $task_details = DB::table('time_task')->where('id', $count->task_id)->first();

		                if(count($task_details) != ''){
		                  $task_name = $task_details->task_name;
		                  $task_type = $task_details->task_type;

		                  if($task_type == 0){
		                    $task_type = '<i class="fa fa-desktop" style="margin-right:10px;"></i> Internal Task';
		                  }
		                  else if($task_type == 1){
		                    $task_type = '<i class="fa fa-users" style="margin-right:10px;"></i> Client Task';
		                  }
		                  else{
		                    $task_type = '<i class="fa fa-globe" style="margin-right:10px;"></i> Global Task';
		                  }
		                }
		                else{
		                  $task_name = 'N/A';
		                  $task_type = 'N/A';
		                }

		                

		                $user_details = DB::table('user')->where('user_id', $count->user_id)->first();

		                //-----------Job Time Start----------------

		                $jobstart = strtotime($count->updated);
		                $jobend   = strtotime(date('Y-m-d H:i:s'));
		                $jobdiff  = $jobend - $jobstart;



		                $hours = floor($jobdiff / (60 * 60));
		                $minutes = $jobdiff - $hours * (60 * 60);
		                $minutes = floor( $minutes / 60 );
		                $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
		                if($hours <= 9)
		                {
		                  $hours = '0'.$hours;
		                }
		                else{
		                  $hours = $hours;
		                }
		                if($minutes <= 9)
		                {
		                  $minutes = '0'.$minutes;
		                }
		                else{
		                  $minutes = $minutes;
		                }
		                if($second <= 9)
		                {
		                  $second = '0'.$second;
		                }
		                else{
		                  $second = $second;
		                }

		                $jobtime =   $hours.':'.$minutes.':'.$second;

		                //-----------Job Time End----------------


		                if($count->stop_time != '00:00:00')
		                {
		                	$get_quick_jobs = DB::table('task_job')->where('active_id',$count->id)->get();
			                  $quick_minutes = 0;
			                  if(count($get_quick_jobs))
			                  {
			                    foreach($get_quick_jobs as $quickjobs_single)
			                    {
			                      $total_quick_jobs_1 = explode(':', $quickjobs_single->job_time);
			                      $minutes = ($total_quick_jobs_1[0]*60) + ($total_quick_jobs_1[1]) + ($total_quick_jobs_1[2]/60);
			                      if($quick_minutes == 0)
			                      {
			                        $quick_minutes = $minutes;
			                      }
			                      else{
			                        $quick_minutes = $quick_minutes + $minutes;
			                      }
			                    }
			                  }

			                    $break_time_min = DB::table('job_break_time')->where('job_id',$count->id)->where('break_time','!=','00:00:00')->first();
			                    $break_timee_minutes = 0;
			                    if(count($break_time_min))
			                    {
			                      $break_timee = explode(':', $break_time_min->break_time);
			                      $break_timee_minutes = ($break_timee[0]*60) + ($break_timee[1]) + ($break_timee[2]/60);
			                    }


			                    $quick_minutes = $quick_minutes + $break_timee_minutes;

			                  $job_timee = explode(':', $count->job_time);
			                  $job_timee_minutes = ($job_timee[0]*60) + ($job_timee[1]) + ($job_timee[2]/60);

			                  $job_time_min = $job_timee_minutes - $quick_minutes;

			                  if(floor($job_time_min / 60) <= 9)
			                  {
			                    $h = '0'.floor($job_time_min / 60);
			                  }
			                  else{
			                    $h = floor($job_time_min / 60);
			                  }
			                  if(($job_time_min -   floor($job_time_min / 60) * 60) <= 9)
			                  {
			                    $m = '0'.($job_time_min -   floor($job_time_min / 60) * 60);
			                  }
			                  else{
			                    $m = ($job_time_min -   floor($job_time_min / 60) * 60);
			                  }
			                  $explode_job_minutes = explode(":",$h.':'.$m.':00');
		    					$total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
			                  $job_time_checked = $h.':'.$m.':00 ('.$total_minutes.')';
		                }
		                else{
		                	$job_time_checked = $count->job_time;
		                }

		                $time_spent = explode(":",$job_time_checked);
						$hour = $time_spent[0] * 60;
						$mins = $time_spent[1];
						$total_mins = $hour + $mins;
						$tot_mins = $tot_mins + $total_mins;
		                
		                if($count->comments != "") { $comments = $count->comments; } else { $comments = 'No Comments Found'; }
		                if($count->quick_job != 0) { $quick_job = 'YES'; } else { $quick_job = 'NO'; }

		                $ratelist = DB::table('user_cost')->where('user_id', $count->user_id)->get();

		                $rate_result = '0';
	                    $cost = '0';

	                    if(count($ratelist)){
	                      foreach ($ratelist as $rate) {
	                        $job_date = strtotime($count->job_date);
	                        $from_date = strtotime($rate->from_date);
	                        $to_date = strtotime($rate->to_date);

	                        if($rate->to_date != '0000-00-00'){                         
	                          if($job_date >= $from_date  && $job_date <= $to_date){
	                            
	                            $rate_result = $rate->cost;                            
	                            $cost = ($rate_result/60)*$total_minutes;
	                          }
	                        }
	                        else{
	                          if($job_date >= $from_date){
	                            $rate_result = $rate->cost;
	                            $cost = ($rate_result/60)*$total_minutes;
	                          }
	                        }
	                      }                      
	                    }


						$outputval.='<tr>
				          <td align="left">'.$j.'</td>
				          <td align="left">'.$client_name.'</td>
				          <td align="left">'.$task_name.'</td>
				          <td align="left">'.$task_type.'</td>
				          <td align="left">'.$quick_job.'</td>
				          <td align="left">'.date('d-M-Y', strtotime($count->job_date)).'</td>
				          <td align="left">'.date('H:i:s', strtotime($count->start_time)).'</td>
				          <td align="left">'.date('H:i:s', strtotime($count->stop_time)).'</td>
				          <td align="left">'.number_format_invoice_without_decimal($rate_result).'</td>
				          <td align="left">'.number_format_invoice_without_decimal($cost).'</td>
				          <td align="left">'.$job_time_checked.'</td>
				          <td>
				          <a href="javascript:" class="fa fa-comment" data-toggle="modal" data-target="#comments_'.$count->id.'" title="View Comments"></a>
				            <div id="comments_'.$count->id.'" class="modal fade" role="dialog" >
				                <div class="modal-dialog" style="width:20%">
				                  <div class="modal-content">
				                    <div class="modal-header">
				                      <button type="button" class="close" data-dismiss="modal">&times;</button>
				                      <h4 class="modal-title">Comments</h4>
				                    </div>
				                    <div class="modal-body">
				                      '.$comments.'
				                    </div>
				                    <div class="modal-footer">
				                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				                    </div>
				                  </div>
				                </div>
				              </div>
				          </td>
				          
				        </tr>';
						$j++;
					}
				}

				$hours = floor($tot_mins / 60);
  				$minutes = ($tot_mins -   floor($tot_mins / 60) * 60);
		        if($hours <= 9)
		        {
		          $hours = '0'.$hours;
		        }
		        else{
		          $hours = $hours;
		        }
		        if($minutes <= 9)
		        {
		          $minutes = '0'.$minutes;
		        }
		        else{
		          $minutes = $minutes;
		        }
		        
		        $jobtime =   $hours.':'.$minutes.':00';

		        $hour_total = $hours+$hour_total;
		        $minute_total = $minutes+$minute_total;
		        $minutes_sub_total = $tot_mins+$minutes_sub_total;

				$task_details = DB::table('time_task')->where('id',$task->task_id)->first();
				$output.='<div class="row">
					<div class="col-md-2 table_padding">'.$i.'</div>
					<div class="col-md-4 table_padding">'.$task_details->task_name.'</div>
					<div class="col-md-6 table_padding">'.$jobtime.' ('.$tot_mins.' Mins)</div>
				</div>';				
				$i++;

				/*$hour_total = $total_hour;
				$minute_total = $total_minutes;
				$minutes_sub_total = $total_sub_minutes;*/

				if($hour_total < 9){
					$hour_total ='0'.$hour_total;
				}
				else{
					$hour_total = $hour_total;
				}

				if($minute_total < 9){
					$minute_total ='0'.$minute_total;
				}
				else{
					$minute_total = $minute_total;
				}



			}
			$output.='
				<div class="col-md-2 table_padding"></div>
				<div class="col-md-4 table_padding text-right"><b>Total</b></div>
				<div class="col-md-6 table_padding">'.$hour_total.':'.$minute_total.':00 ('.$minutes_sub_total.' Mins)</div>';
		}
		echo json_encode(array("task_html" => $output, "job_html" => $outputval));
	}
	public function staff_review_download_as_pdf()
	{
		$user = Input::get('user');
		$from_date = strtotime(Input::get('from_date'));
		$to_date = strtotime(Input::get('to_date'));

		$frm = Input::get('from_date');
		$too = Input::get('to_date');
		$user_details = DB::table('user')->where('user_id',$user)->first();
		$firstname = $user_details->lastname.' '.$user_details->firstname;
		$get_tasks = DB::select('SELECT * from `task_job` WHERE user_id IN ('.$user.') AND UNIX_TIMESTAMP(`job_date`) >= "'.$from_date.'" AND UNIX_TIMESTAMP(`job_date`) <= "'.$to_date.'" GROUP BY `task_id`');
		$output = '<style>
				.table_style {
				    width: 100%;
				    border-collapse:collapse;
				    border:0px solid #c5c5c5;
				}
				.table_style_no_border {
				    width: 100%;
				    border-collapse:collapse;
				    border:0px solid #c5c5c5;
				}
				</style>
				<table class="table_style">					
					<tr style="background:#000;color:#fff;">
		          		<td style="width:10%;background:#000;color:#fff;height:35px">S.No</td>
		          		<td style="width:50%;background:#000;color:#fff;height:35px">Staff Review Summary</td>
		          		<td style="width:25%;background:#000;color:#fff;height:35px">Total Time</td>
		          		<td style="width:5%;background:#000;color:#fff;height:35px"></td>
		          		<td style="width:5%;background:#000;color:#fff;height:35px"></td>
		          		<td style="width:5%;background:#000;color:#fff;height:35px"></td>
			        </tr>';
		$outputval = '<style>
						.table_style {
						    width: 100%;
						    border-collapse:collapse;
						    border:0px solid #c5c5c5;
						}
						.table_style_no_border {
						    width: 100%;
						    border-collapse:collapse;
						    border:0px solid #c5c5c5;
						}
						</style>
						<table class="table_style">
						<tr>
							<td align="center" colspan="9" style="padding-bottom:10px;">
								Staff Review for '.$user_details->lastname.' '.$user_details->firstname.'
							</td>
						</tr>
						<tr>
							<td align="center" colspan="9" style="padding-bottom:10px;">
								From Date: '.date('M-d-Y', strtotime($frm)).' To Date: '.date('M-d-Y', strtotime($too)).'
							</td>
						</tr>

						<tr>
							<td style="width:5%;background:#dfdfdf;color:#000;height:35px;font-size:12px">S.No</td>
							<td style="width:25%;background:#dfdfdf;color:#000;height:35px;font-size:12px">Client Name</td>
							<td style="width:10%;background:#dfdfdf;color:#000;height:35px;font-size:12px"Task Name</td>
							<td style="width:10%;background:#dfdfdf;color:#000;height:35px;font-size:12px"Task Type</td>
							<td style="width:10%;background:#dfdfdf;color:#000;height:35px;font-size:12px"Quick Job</td>
							<td style="width:10%;background:#dfdfdf;color:#000;height:35px;font-size:12px">Job date</td>
							<td style="width:10%;background:#dfdfdf;color:#000;height:35px;font-size:12px">Start Time</td>
							<td style="width:10%;background:#dfdfdf;color:#000;height:35px;font-size:12px">Stop Time</td>
							<td style="width:10%;background:#dfdfdf;color:#000;height:35px;font-size:12px">Job Time</td>
						</tr>';
		if(count($get_tasks))
		{
			$i = 1;
			$j = 1;
			foreach($get_tasks as $key => $task)
			{
				$get_tasks_time_count = DB::select('SELECT * from `task_job` WHERE user_id IN ('.$user.') AND task_id = "'.$task->task_id.'" AND UNIX_TIMESTAMP(`job_date`) >= "'.$from_date.'" AND UNIX_TIMESTAMP(`job_date`) <= "'.$to_date.'"');
				$tot_mins = 0;
				
				if(count($get_tasks_time_count)){
					foreach($get_tasks_time_count as $count)
					{
						$client_details = DB::table('cm_clients')->where('client_id', $count->client_id)->first();
			            if(count($client_details) != ''){
			              $client_name = $client_details->company.' ('.$count->client_id.')';
			            }
			            else{
			              $client_name = 'N/A';
			            }

			            $client_details = DB::table('cm_clients')->where('client_id', $count->client_id)->first();
		                if(count($client_details) != ''){
		                  $client_name = $client_details->company.' ('.$count->client_id.')';
		                }
		                else{
		                  $client_name = 'N/A';
		                }

		                $task_details = DB::table('time_task')->where('id', $count->task_id)->first();

		                if(count($task_details) != ''){
		                  $task_name = $task_details->task_name;
		                  $task_type = $task_details->task_type;

		                  if($task_type == 0){
		                    $task_type = '<i class="fa fa-desktop" style="margin-right:10px;"></i> Internal Task';
		                  }
		                  else if($task_type == 1){
		                    $task_type = '<i class="fa fa-users" style="margin-right:10px;"></i> Client Task';
		                  }
		                  else{
		                    $task_type = '<i class="fa fa-globe" style="margin-right:10px;"></i> Global Task';
		                  }
		                }
		                else{
		                  $task_name = 'N/A';
		                  $task_type = 'N/A';
		                }

		                

		                $user_details = DB::table('user')->where('user_id', $count->user_id)->first();

		                //-----------Job Time Start----------------

		                $jobstart = strtotime($count->updated);
		                $jobend   = strtotime(date('Y-m-d H:i:s'));
		                $jobdiff  = $jobend - $jobstart;



		                $hours = floor($jobdiff / (60 * 60));
		                $minutes = $jobdiff - $hours * (60 * 60);
		                $minutes = floor( $minutes / 60 );
		                $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
		                if($hours <= 9)
		                {
		                  $hours = '0'.$hours;
		                }
		                else{
		                  $hours = $hours;
		                }
		                if($minutes <= 9)
		                {
		                  $minutes = '0'.$minutes;
		                }
		                else{
		                  $minutes = $minutes;
		                }
		                if($second <= 9)
		                {
		                  $second = '0'.$second;
		                }
		                else{
		                  $second = $second;
		                }

		                $jobtime =   $hours.':'.$minutes.':'.$second;

		                //-----------Job Time End----------------


		                if($count->stop_time != '00:00:00')
		                {
		                	$get_quick_jobs = DB::table('task_job')->where('active_id',$count->id)->get();
			                  $quick_minutes = 0;
			                  if(count($get_quick_jobs))
			                  {
			                    foreach($get_quick_jobs as $quickjobs_single)
			                    {
			                      $total_quick_jobs_1 = explode(':', $quickjobs_single->job_time);
			                      $minutes = ($total_quick_jobs_1[0]*60) + ($total_quick_jobs_1[1]) + ($total_quick_jobs_1[2]/60);
			                      if($quick_minutes == 0)
			                      {
			                        $quick_minutes = $minutes;
			                      }
			                      else{
			                        $quick_minutes = $quick_minutes + $minutes;
			                      }
			                    }
			                  }

			                    $break_time_min = DB::table('job_break_time')->where('job_id',$count->id)->where('break_time','!=','00:00:00')->first();
			                    $break_timee_minutes = 0;
			                    if(count($break_time_min))
			                    {
			                      $break_timee = explode(':', $break_time_min->break_time);
			                      $break_timee_minutes = ($break_timee[0]*60) + ($break_timee[1]) + ($break_timee[2]/60);
			                    }


			                    $quick_minutes = $quick_minutes + $break_timee_minutes;

			                  $job_timee = explode(':', $count->job_time);
			                  $job_timee_minutes = ($job_timee[0]*60) + ($job_timee[1]) + ($job_timee[2]/60);

			                  $job_time_min = $job_timee_minutes - $quick_minutes;

			                  if(floor($job_time_min / 60) <= 9)
			                  {
			                    $h = '0'.floor($job_time_min / 60);
			                  }
			                  else{
			                    $h = floor($job_time_min / 60);
			                  }
			                  if(($job_time_min -   floor($job_time_min / 60) * 60) <= 9)
			                  {
			                    $m = '0'.($job_time_min -   floor($job_time_min / 60) * 60);
			                  }
			                  else{
			                    $m = ($job_time_min -   floor($job_time_min / 60) * 60);
			                  }
			                  $explode_job_minutes = explode(":",$h.':'.$m.':00');
		    					$total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
			                  $job_time_checked = $h.':'.$m.':00 ('.$total_minutes.')';
		                }
		                else{
		                	$job_time_checked = $count->job_time;
		                }

		                $time_spent = explode(":",$job_time_checked);
						$hour = $time_spent[0] * 60;
						$mins = $time_spent[1];
						$total_mins = $hour + $mins;
						$tot_mins = $tot_mins + $total_mins;
		                
		                if($count->comments != "") { $comments = $count->comments; } else { $comments = 'No Comments Found'; }
		                if($count->quick_job != 0) { $quick_job = 'YES'; } else { $quick_job = 'NO'; }

						$outputval.='<tr>
							<td style="width:5%;background:#f3f3f3;height:35px;font-size:12px">'.$j.'</td>
							<td style="width:25%;background:#f3f3f3;height:35px;font-size:12px">'.$client_name.'</td>
							<td style="width:10%;background:#f3f3f3;height:35px;font-size:12px">'.$task_name.'</td>
							<td style="width:10%;background:#f3f3f3;height:35px;font-size:12px">'.$task_type.'</td>
							<td style="width:10%;background:#f3f3f3;height:35px;font-size:12px">'.$quick_job.'</td>
							<td style="width:10%;background:#f3f3f3;height:35px;font-size:12px">'.date('d-M-Y', strtotime($count->job_date)).'</td>
							<td style="width:10%;background:#f3f3f3;height:35px;font-size:12px">'.date('H:i:s', strtotime($count->start_time)).'</td>
							<td style="width:10%;background:#f3f3f3;height:35px;font-size:12px">'.date('H:i:s', strtotime($count->stop_time)).'</td>
							<td style="width:10%;background:#f3f3f3;height:35px;font-size:12px">'.$job_time_checked.'</td>
						</tr>';
						$j++;
					}
				}

				$hours = floor($tot_mins / 60);
  				$minutes = ($tot_mins -   floor($tot_mins / 60) * 60);
		        if($hours <= 9)
		        {
		          $hours = '0'.$hours;
		        }
		        else{
		          $hours = $hours;
		        }
		        if($minutes <= 9)
		        {
		          $minutes = '0'.$minutes;
		        }
		        else{
		          $minutes = $minutes;
		        }
		        
		        $jobtime =   $hours.':'.$minutes.':00';

				$task_details = DB::table('time_task')->where('id',$task->task_id)->first();
				$output.='<tr>
						<td style="width:10%;height:35px">'.$i.'</td>
						<td style="width:50%;height:35px">'.$task_details->task_name.'</td>
						<td style="width:25%;height:35px">'.$jobtime.' ('.$tot_mins.' Mins)</td>
						<td style="width:5%;height:35px"></td>
						<td style="width:5%;height:35px"></td>
						<td style="width:5%;height:35px"></td>
				    </tr>';
				$i++;
			}
		}
		$output.='</table>';
		$outputval.='</table>';
		$pdf = PDF::loadHTML($outputval.$output);
		$pdf->save('papers/Staff Review Summary for '.$firstname.' from '.$frm.' to '.$too.'.pdf');
		echo 'Staff Review Summary for '.$firstname.' from '.$frm.' to '.$too.'.pdf';
	}
	
}

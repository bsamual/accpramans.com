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
		return Redirect::back()->with('message', 'Job added Succefully');
	}


	public function timejobadd(){

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
			$data['job_type'] = Input::get("internal_type");
			$data['user_id'] = Input::get("user_id");
			$data['task_id'] = Input::get("task_id");						
			$data['start_time'] = date('H:i:s', strtotime(Input::get("starttime")));
			$data['quick_job'] = Input::get("quick_job");
			$data['job_created_date'] = date('Y-m-d');
			$data['job_date'] = $dateverify;
			$data['color'] = 1;
		}
		else
		{
			$acive_id = Input::get('acive_id');

			DB::table('task_job')->where('id', $acive_id)->update(['color' => 0]);
			DB::table('task_job')->where('active_id', $acive_id)->update(['color' => 0]);


			$data['job_type'] = Input::get("internal_type");
			$data['user_id'] = Input::get("user_id");
			$data['task_id'] = Input::get("task_id");						
			$data['start_time'] = date('H:i:s', strtotime(Input::get("starttime")));
			$data['quick_job'] = Input::get("quick_job");
			$data['job_created_date'] = date('Y-m-d');
			$data['job_date'] = $dateverify;
			$data['color'] = 1;
			$data['active_id'] = $acive_id;		

		}


		DB::table('task_job')->insert($data);
		return Redirect::back()->with('message', 'Job added Succefully');

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
		$searchdate = Input::get('hidden_search_date');
		return redirect('user/time_me_joboftheday?date_search='.$searchdate)->with('message', 'Job Updated Succefully');
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

		echo json_encode(array('id' => $job_details->id, 'quick_job_times' => $quick_job_times, 'start_time' =>date('H:i', strtotime($job_details->start_time)), 'stop_time' =>$stop_time, 'jobtime' => $jobtime, 'total_time_minutes_format' => $total_time_minutes_format, 'alert' => $alert, 'start_hour' => $hour, 'start_min' => $min,'date'=>date('d-M-Y', strtotime($job_details->job_date))));
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
          

		return Redirect::back()->with('message', 'Job Stop Succefully');

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

		return Redirect::back()->with('message', 'Job Stop Succefully');

	}








	public function jobaddbreak(){
		$id = Input::get("id");
		$data['job_id'] = Input::get("id");		
		$data['break_time'] = Input::get("breaktime");
		

		DB::table('job_break_time')->insert($data);

		return Redirect::back()->with('message', 'Break add Succefully');
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
		$userdetails = DB::table('user')->where('user_id',$id)->first();
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
                      $client_name = $client_details->company;
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
                        $buttons = '<a style="'.$redcolor.'" href="javascript:" class="stop_class" data-element="'.$jobs->id.'" style="'.$redcolor.'">Stop</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a style="'.$redcolor.'" href="javascript:" class="create_new_quick" data-element="'.$jobs->id.'">Quick Job</a>';
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
				              <td align="left" style="'.$redcolor.'">
				              <span id="job_time_refresh_'.$jobs->id.'" style="'.$redcolor.'">'.$jobtime.'</span> &nbsp;&nbsp;<a href="javascript:"><i class="fa fa-refresh job_time_refresh" aria-hidden="true" data-element="'.$jobs->id.'"></i></a>
				              </td>
				              
				              <td align="center" style="'.$redcolor.'">'.$buttons.'</td>
				            </tr>';
                  	$joblist_child = DB::table('task_job')->where('user_id',$id)->where('active_id',$jobs->id)->get();
                      $childi = 1;
                      if(count($joblist_child)){              
                        foreach ($joblist_child as $child) {
                          if($child->quick_job == 0 || $child->quick_job == 1){
                            if($child->status == 0){
                              $client_details = DB::table('cm_clients')->where('client_id', $child->client_id)->first();
			                    if(count($client_details) != ''){
			                      $client_name = $client_details->company;
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
			                      $buttons = '<a style="'.$redcolor.'" href="javascript:" class="stop_class_quick" data-element="'.$child->id.'" style="'.$redcolor.'">Stop</a>';
			                    }
			                    else{
			                      $quick_job = 'Yes'; 
			                      $buttons = '';
			                    }
                          $output.='
                          <tr>
                            <td align="left" style="'.$redcolor.'">'.$i.'.'.$childi.'</td>
                            <td align="left" style="'.$redcolor.'">'.$client_name.'</td>
                            <td align="left" style="'.$redcolor.'">'.$task_name.'</td>
                            <td align="left" style="'.$redcolor.'">'.$task_type.'</td>
                            <td align="left" style="'.$redcolor.'">'.$quick_job.'</td>
                            <td align="left" style="'.$redcolor.'">'.date('d-M-Y', strtotime($child->job_date)).'</td>
                            <td align="left" style="'.$redcolor.'">'.date('H:i:s', strtotime($child->start_time)).'</td>';

                            if($child->stop_time != "00:00:00")
                            {
                              $output.='<td align="left" style="'.$redcolor.'">'.date('H:i:s', strtotime($child->stop_time)).'</td>';
                            }
                            else{
                              $output.='<td align="left" style="'.$redcolor.'">N/A</td>';
                            }

                            $output.='<td align="left" style="'.$redcolor.'">';
                            if($child->job_time != "00:00:00")
                            {
                              $output.='<span style="'.$redcolor.'">'.$child->job_time.'</span>';
                            }
                            else{
                              $output.='<span id="job_time_refresh_'.$child->id.'" style="'.$redcolor.'">'.$jobtime.'</span> &nbsp;&nbsp;<a href="javascript:"><i class="fa fa-refresh job_time_refresh" aria-hidden="true" data-element="'.$child->id.'"></i></a>';
                            }
                            $output.='</td>
                            <td align="center" style="'.$redcolor.'">'.$buttons.'</td>
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
			          $client_name = $client_details->company;
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
			          $job_time = $jobs->job_time;
			        }
			        else{
			          $quick_job = 'Yes';
			          $job_time = $jobs->job_time;
			        }

			        if($jobs->comments != "") { $comments = $jobs->comments; } else { $comments = 'No Comments Found'; }
			    $outputclose.='
			    <tr>
			      <td align="left">'.$iclose.'</td>
			      <td align="left">'.$client_name.'</td>
			      <td align="left">'.$task_name.'</td>
			      <td align="left">'.$task_type.'</td>
			      <td align="left">'.$quick_job.'</td>
			      <td align="left">'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
			      <td align="left">'.date('H:i:s', strtotime($jobs->start_time)).'</td>
			      <td align="left">'.$job_time.'</td>
			      <td align="left">'.date('H:i:s', strtotime($jobs->stop_time)).'</td>
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
						          $client_name = $client_details->company;
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

						        if($child->comments != "") { $comments = $child->comments; } else { $comments = 'No Comments Found'; }
						    $outputclose.='
						    <tr>
						      <td align="left">'.$iclose.'.'.$childiclose.'</td>
						      <td align="left">'.$client_name.'</td>
						      <td align="left">'.$task_name.'</td>
						      <td align="left">'.$task_type.'</td>
						      <td align="left">'.$quick_job.'</td>
						      <td align="left">'.date('d-M-Y', strtotime($child->job_date)).'</td>
						      <td align="left">'.date('H:i:s', strtotime($child->start_time)).'</td>
						      <td align="left">'.$job_time.'</td>
						      <td align="left">'.date('H:i:s', strtotime($child->stop_time)).'</td>
						      <td align="center">
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
	    	$username = $userdetails->firstname;
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
		$user = DB::table('user')->where('user_status', 0)->get();
		return view('user/time_system/active_job', array('title' => 'Active Job', 'joblist' => $time_job, 'userlist' => $user, 'taskslist' => $tasks));

	}

	public function time_joboftheday(){

		$time_job = DB::table('task_job')->where('active_id',0)->get();
		$tasks = DB::table('time_task')->where('task_type', 0)->get();
		$user = DB::table('user')->where('user_status', 0)->get();
		return view('user/time_system/job_of_the_day', array('title' => 'Job of the day', 'joblist' => $time_job, 'userlist' => $user, 'taskslist' => $tasks));

	}

	public function time_client_review(){

		$time_job = DB::table('task_job')->get();
		$tasks = DB::table('time_task')->where('task_type', 0)->get();
		$user = DB::table('user')->where('user_status', 0)->get();
		return view('user/time_system/client_review', array('title' => 'Client Review', 'joblist' => $time_job, 'userlist' => $user, 'taskslist' => $tasks));

	}

	public function time_all_job(){

		$time_job = DB::table('task_job')->where('active_id',0)->get();
		$tasks = DB::table('time_task')->where('task_type', 0)->get();
		$user = DB::table('user')->where('user_status', 0)->get();
		return view('user/time_system/all_job', array('title' => 'Client Review', 'joblist' => $time_job, 'userlist' => $user, 'taskslist' => $tasks));

	}

	public function get_job_details(){
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


		echo json_encode(array('id' => $job->id,'client_id' => $job->client_id,'user_id' => $job->user_id,'task_id' => $job->task_id,'start_time' => date('H:i', strtotime($job->start_time)),'stop_time' => date('H:i', strtotime($job->stop_time)),'job_time' => $job->job_time,'job_date' => date('F d, Y', strtotime($job->job_date)),'job_type' => $job->job_type,'quick_job' => $job->quick_job,'job_created_date' => $job->job_created_date,'comments' => $job->comments,'updated' => $job->updated,'status' => $job->status,'client_name' => $client_name,'tasks_group' => $output, 'task_name' => $task_name_details->task_name));
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
		$callback = function() use ($job_details, $columns)
    	{
	       	$file = fopen('job_file/active_job_Report.csv', 'w');
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
		      	$columns_2 = array($i, $user_details->firstname, $companyname, $task_details->task_name, $task_type, date('d-M-Y', strtotime($single->job_date)), date('H:i:s', strtotime($single->start_time)), $jobtime );
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
		<h3 id="pdf_title_all_ivoice" style="width: 100%; text-align: center; margin: 15px 0px; float: left;">Active Job Report</h3>
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
                  $client_name = $client_details->company;
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
                

        $output.='
        <tr>          
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$i.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$user_details->firstname.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$client_name.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$task_name.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$task_type.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.date('H:i:s', strtotime($jobs->start_time)).'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">

          <span id="job_time_refresh_'.$jobs->id.'">'.$jobtime.'</span> &nbsp;&nbsp;<a href="javascript:"><i class="fa fa-refresh job_time_refresh" aria-hidden="true" data-element="'.$jobs->id.'"></i></a>

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
					$userfirstname = $user_details->firstname;
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
					$userfirstname = $user_details->firstname;
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
                  $client_name = $client_details->company;
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
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$user_details->firstname.'</td>
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
                  $client_name = $client_details->company;
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
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$user_details->firstname.'</td>
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

        //-----------Job Time End----------------


        echo json_encode(array('id' => $jobs->id, 'refreshcount' => $jobtime ));
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
		$callback = function() use ($job_details, $columns)
    	{
	       	$file = fopen('job_file/joboftheday_Report.csv', 'w');
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




		      	$columns_2 = array($i, $user_details->firstname, $companyname, $task_details->task_name, $task_type, date('d-M-Y', strtotime($single->job_date)), date('H:i:s', strtotime($single->start_time)),date('H:i:s', strtotime($single->stop_time)), $job_time_checked );
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




		      	$columns_2 = array($i.'.'.$ichild, $user_details->firstname, $companyname, $task_details->task_name, $task_type, date('d-M-Y', strtotime($child->job_date)), date('H:i:s', strtotime($child->start_time)),date('H:i:s', strtotime($child->stop_time)), $job_time_checked );
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
                  $client_name = $client_details->company;
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
                	$job_time_checked = $jobs->job_time;
                }
                
                if($jobs->comments != "") { $comments = $jobs->comments; } else { $comments = 'No Comments Found'; }
                if($jobs->quick_job != 0) { $quick_job = 'YES'; } else { $quick_job = 'NO'; }
        $output.='
        <tr>
    	   <td>
          <input type="checkbox" name="select_job" class="select_job class_'.$jobs->id.'" data-element="'.$jobs->id.'" value="'.$jobs->id.'"><label>&nbsp</label>
          </td>
          <td align="left">'.$i.'</td>
          <td align="left">'.$user_details->firstname.'</td>
          <td align="left">'.$client_name.'</td>
          <td align="left">'.$task_name.'</td>
          <td align="left">'.$task_type.'</td>
          <td align="left">'.$quick_job.'</td>
          <td align="left">'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
          <td align="left">'.date('H:i:s', strtotime($jobs->start_time)).'</td>
          <td align="left">'.date('H:i:s', strtotime($jobs->stop_time)).'</td>
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
                  $client_name = $client_details->company;
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

                //-----------Job Time End----------------


                if($child->quick_job == 0 && $child->status == 0){
                  $job_time_checked = '<span id="job_time_refresh_'.$child->id.'">'.$jobtime.'</span></span> &nbsp;&nbsp;<a href="javascript:"><i class="fa fa-refresh job_time_refresh" aria-hidden="true" data-element="'.$child->id.'"></i></a>';
                }
                else if($child->quick_job == 1 && $child->status == 0){
                  $job_time_checked = $child->job_time;
                }
                else if($child->quick_job == 0 && $child->status == 1){
                  $job_time_checked = $child->job_time;
                }
                
                if($child->comments != "") { $comments = $child->comments; } else { $comments = 'No Comments Found'; }
                if($child->quick_job != 0) { $quick_job = 'YES'; } else { $quick_job = 'NO'; }
        $output.='
        <tr>
    	   <td>
          <input type="checkbox" name="select_jobaaaaaaa" class="select_jobaaaaa classaaaaa_'.$child->id.'" data-element="'.$child->id.'" value="'.$child->id.'" style="display:none"><label style="display:none">&nbsp</label>
          </td>
          <td align="left">'.$i.'.'.$ichild.'</td>
          <td align="left">'.$user_details->firstname.'</td>
          <td align="left">'.$client_name.'</td>
          <td align="left">'.$task_name.'</td>
          <td align="left">'.$task_type.'</td>
          <td align="left">'.$quick_job.'</td>
          <td align="left">'.date('d-M-Y', strtotime($child->job_date)).'</td>
          <td align="left">'.date('H:i:s', strtotime($child->start_time)).'</td>
          <td align="left">'.date('H:i:s', strtotime($child->stop_time)).'</td>
          <td align="left">'.$child->job_time.'</td>
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
		$client_id = Input::get('client_id');
		$tasks = explode(',',Input::get('tasks'));
		$tasks_implode = Input::get('tasks');

		$users = explode(',',Input::get('users'));
		$users_implode = Input::get('users');
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
	                  $client_name = $client_details->company;
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


	                if($jobs->status == 0){
	                  $job_time_checked = '<span id="job_time_refresh_'.$jobs->id.'">'.$jobtime.'</span></span> &nbsp;&nbsp;<a href="javascript:"><i class="fa fa-refresh job_time_refresh" aria-hidden="true" data-element="'.$jobs->id.'"></i></a>';
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
	                
	                if($jobs->comments != "") { $comments = $jobs->comments; } else { $comments = 'No Comments Found'; }
	        $output.='
	        <tr>
	    	   <td>
	          <input type="checkbox" name="select_job" class="select_job class_'.$jobs->id.'" data-element="'.$jobs->id.'" value="'.$jobs->id.'"><label>&nbsp</label>
	          </td>
	          <td align="left">'.$i.'</td>
	          <td align="left">'.$user_details->firstname.'</td>
	          <td align="left">'.$client_name.'</td>
	          <td align="left">'.$task_name.'</td>
	          <td align="left">'.$task_type.'</td>
	          <td align="left">'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
	          <td align="left">'.date('H:i:s', strtotime($jobs->start_time)).'</td>
	          <td align="left">'.date('H:i:s', strtotime($jobs->stop_time)).'</td>
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

	public function joboftheday_reportpdf(){
		
		$ids = explode(',', Input::get('value'));			
		$joblist = DB::table('task_job')->whereIn('id', $ids)->get();

		$output='';
        $i=1;            
        if(count($joblist)){              
          foreach ($joblist as $jobs) {
            
                $client_details = DB::table('cm_clients')->where('client_id', $jobs->client_id)->first();
                if(count($client_details) != ''){
                  $client_name = $client_details->company;
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
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$user_details->firstname.'</td>
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
                  $client_name = $client_details->company;
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
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$user_details->firstname.'</td>
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

		$output='';
        $i=1;            
        if(count($joblist)){              
          foreach ($joblist as $jobs) {
            
                $client_details = DB::table('cm_clients')->where('client_id', $jobs->client_id)->first();
                if(count($client_details) != ''){
                  $client_name = $client_details->company;
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
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$user_details->firstname.'</td>
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

		$headers = array(
	        "Content-type" => "text/csv",
	        "Content-Disposition" => "attachment; filename=CM_Report.csv",
	        "Pragma" => "no-cache",
	        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
	        "Expires" => "0"
	    );      	

		$columns = array('#', 'User', 'Client Name', 'Task Name', 'Task Type', 'Date', 'Start Time','Stop Time', 'Job Time');
		$callback = function() use ($job_details, $columns)
    	{
	       	$file = fopen('job_file/clientreview_Report.csv', 'w');
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




		      	$columns_2 = array($i, $user_details->firstname, $companyname, $task_details->task_name, $task_type, date('d-M-Y', strtotime($single->job_date)), date('H:i:s', strtotime($single->start_time)),date('H:i:s', strtotime($single->stop_time)), $job_time_checked );
				fputcsv($file, $columns_2);
				$i++;
			}
			fclose($file);
		};
		return Response::stream($callback, 200, $headers);
		//return $filename.'_InvoiceReport.csv';

	}

	public function get_quick_break_details()
	{
		$jobid = Input::get('jobid');
		$getdetails = DB::table('task_job')->where('id',$jobid)->first();
		echo json_encode(array('userid' => $getdetails->user_id,'start_time' => $getdetails->start_time));
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
}

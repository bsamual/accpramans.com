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


		$tasks = DB::table('time_task')->where('clients','like','%'.$id.'%')->get();
		
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

	public function timejobadd(){

		$type = Input::get("internal_type");
		$quick_job_stop = Input::get("quick_job");

		$dateverify = date('Y-m-d', strtotime(Input::get("date")));

		$jobdetails = DB::table('task_job')->where('job_date', $dateverify)->first();

		if(count($jobdetails) == ''){
			echo 'Available';
			exit;
		}
		else{
			if($jobdetails->status == 1){
				echo 'Available';
				exit;
			}
			else{
				echo 'Not Available';
				exit;
				return Redirect::back()->with('message', 'active job have that date. If you want create active job, stop old active job');
			}
		}

				
		if($type == 0){
			$data['job_type'] = Input::get("internal_type");
			$data['user_id'] = Input::get("user_id");
			$data['task_id'] = Input::get("task_id");						
			$data['start_time'] = date('H:i:s', strtotime(Input::get("starttime")));
			$data['quick_job'] = Input::get("quick_job");
			$data['job_created_date'] = date('Y-m-d');
			$data['job_date'] = date('Y-m-d', strtotime(Input::get("date")));


			if($quick_job_stop == 1){
				$data['stop_time'] = Input::get("stoptime");			
			}
			else{
				$data['stop_time'] = '';			
			}

		}
		else{
			
			$data['job_type'] = Input::get("internal_type");
			$data['user_id'] = Input::get("user_id");
			$data['task_id'] = Input::get("task_id");			
			$data['start_time'] = date('H:i:s', strtotime(Input::get("starttime")));
			$data['quick_job'] = Input::get("quick_job");			
			$data['job_created_date'] = date('Y-m-d');
			$data['job_date'] = date('Y-m-d', strtotime(Input::get("date")));

			if($quick_job_stop == 1){
				$data['stop_time'] = Input::get("stoptime");				
			}
			else{
				$data['stop_time'] = '';				
			}

			$data['client_id'] = Input::get("clientid");

		}
		DB::table('task_job')->insert($data);
		return Redirect::back()->with('message', 'Job added Succefully');
	}

	public function stop_job_details(){
		$id = Input::get('jobid');
		
		$job_details = DB::table('task_job')->where('id', $id)->first();
		$explode = explode(":",$job_details->start_time);
		$hour = $explode[0];
		$min = $explode[1];
		echo json_encode(array('id' => $job_details->id, 'start_hour' => $hour, 'start_min' => $min,'date'=>date('m-d-Y', strtotime($job_details->job_date))));
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

		$joblist = DB::table('task_job')->where('user_id', $id)->get();
		$i=1;
		$output='';
		if(count($joblist)){              
              foreach ($joblist as $jobs) {
                if($jobs->quick_job == 0){
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
                        $task_type = '<i class="fa fa-globe" style="margin-right:10px;"></i> Globel Task';
                      }
                    }
                    else{
                      $task_name = 'N/A';
                      $task_type = 'N/A';
                    }

                    $break_time_count = DB::table('job_break_time')->where('job_id', $jobs->id)->get();

                    $count_minues = 0;
                    if(count($break_time_count)){
                      foreach ($break_time_count as $break_time1) {
                        if($break_time1->break_time == "01:00:00") { $minval = 60; }
                        elseif($break_time1->break_time == "00:45:00") { $minval = 45; }
                        elseif($break_time1->break_time == "00:30:00") { $minval = 30; }
                        elseif($break_time1->break_time == "00:15:00") { $minval = 15; }
                        if($count_minues == 0)
                        {
                          $count_minues = $minval;
                        }
                        else{
                          $count_minues = $count_minues + $minval;
                        }
                        
                      }
                    }

                    if($count_minues == 0)
                    {
                      $break_hours = '';
                    }
                    elseif($count_minues < 60)
                    {
                      $break_hours = $count_minues.' Minutes';
                    }
                    elseif($count_minues == 60)
                    {
                      $break_hours = '1 Hour';
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
                      }
                      else{
                        $break_hours = $h.':'.$m.' Hours';
                      }
                      
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

                    


                  	$output.='
                  			<tr>
				              <td align="left">'.$i.'</td>
				              <td align="left">'.$client_name.'</td>
				              <td align="left">'.$task_name.'</td>
				              <td align="left">'.$task_type.'</td>
				              <td align="left">'.date('h:i A', strtotime($jobs->start_time)).'</td>
				              <td align="left">
				              <span id="job_time_refresh_'.$jobs->id.'">'.$jobtime.'</span> &nbsp;&nbsp;<a href="javascript:"><i class="fa fa-refresh job_time_refresh" aria-hidden="true" data-element="'.$jobs->id.'"></i></a>
				              </td>
				              
				              <td align="center"><a href="javascript:" class="stop_class" data-element="'.$jobs->id.'">Stop</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript:" class="take_break_class" data-element="'.$jobs->id.'">Take Break</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript:" class="break_time_class" data-element="'.$jobs->id.'">Break Time</a> '.$break_hours.'</td>
				            </tr>
                  	';
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
	            <td align="center">Empty</td>
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
	            $task_type = '<i class="fa fa-globe" style="margin-right:10px;"></i> Globel Task';
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
	          $job_time = 'N/A';
	        }


	    $outputclose.='
	    <tr>
	      <td align="left">'.$iclose.'</td>
	      <td align="left">'.$client_name.'</td>
	      <td align="left">'.$task_name.'</td>
	      <td align="left">'.$task_type.'</td>
	      <td align="left">'.$quick_job.'</td>
	      <td align="left">'.date('h:i A', strtotime($jobs->start_time)).'</td>
	      <td align="left">'.$job_time.'</td>
	      <td align="left">'.date('h:i A', strtotime($jobs->stop_time)).'</td>
	    </tr>';
	      $iclose++;
	          
	        }
	      }              
	    }
	    if($iclose == 1){
	      $outputclose.= '<tr>
	                <td align="left"></td>
	                <td align="left"></td>
	                <td align="left"></td>
	                <td align="right">Empty</td>
	                <td align="left"></td>
	                <td align="left"></td>
	                <td align="left"></td>
	                <td align="left"></td>
	                </tr>';
	    }




      echo json_encode(array('activejob' => $output, 'closejob' => $outputclose));

	}

	public function time_active_job(){

		$time_job = DB::table('task_job')->get();
		$tasks = DB::table('time_task')->where('task_type', 0)->get();
		$user = DB::table('user')->where('user_status', 0)->get();
		return view('user/time_system/active_job', array('title' => 'Active Job', 'joblist' => $time_job, 'userlist' => $user, 'taskslist' => $tasks));

	}

	public function time_joboftheday(){

		$time_job = DB::table('task_job')->get();
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

		$time_job = DB::table('task_job')->get();
		$tasks = DB::table('time_task')->where('task_type', 0)->get();
		$user = DB::table('user')->where('user_status', 0)->get();
		return view('user/time_system/all_job', array('title' => 'Client Review', 'joblist' => $time_job, 'userlist' => $user, 'taskslist' => $tasks));

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
					$companyname = '';
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
					$task_type = 'Globel Task';
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




		      	$columns_2 = array($i, $user_details->firstname, $companyname, $task_details->task_name, $task_type, date('d-M-Y', strtotime($single->job_date)), date('h:i A', strtotime($single->start_time)), $jobtime );
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

		$output='';
        $i=1;            
        if(count($joblist)){              
          foreach ($joblist as $jobs) {
            if($jobs->quick_job == 0){
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
                    $task_type = '<i class="fa fa-globe" style="margin-right:10px;"></i> Globel Task';
                  }
                }
                else{
                  $task_name = 'N/A';
                  $task_type = 'N/A';
                }

                $break_time_count = DB::table('job_break_time')->where('job_id', $jobs->id)->get();
                                    
                $count_minues = 0;
                if(count($break_time_count)){
                  foreach ($break_time_count as $break_time1) {
                    if($break_time1->break_time == "01:00:00") { $minval = 60; }
                    elseif($break_time1->break_time == "00:45:00") { $minval = 45; }
                    elseif($break_time1->break_time == "00:30:00") { $minval = 30; }
                    elseif($break_time1->break_time == "00:15:00") { $minval = 15; }
                    if($count_minues == 0)
                    {
                      $count_minues = $minval;
                    }
                    else{
                      $count_minues = $count_minues + $minval;
                    }
                    
                  }
                }

                if($count_minues == 0)
                {
                  $break_hours = '';
                }
                elseif($count_minues < 60)
                {
                  $break_hours = $count_minues.' Minutes';
                }
                elseif($count_minues == 60)
                {
                  $break_hours = '1 Hour';
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
                  }
                  else{
                    $break_hours = $h.':'.$m.' Hours';
                  }
                  
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
                

        $output.='
        <tr>          
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$i.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$user_details->firstname.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$client_name.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$task_name.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$task_type.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.date('h:i A', strtotime($jobs->start_time)).'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">

          <span id="job_time_refresh_'.$jobs->id.'">'.$jobtime.'</span> &nbsp;&nbsp;<a href="javascript:"><i class="fa fa-refresh job_time_refresh" aria-hidden="true" data-element="'.$jobs->id.'"></i></a>

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
                    <td align="center">Empty</td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left"></td>
                    </tr>';
        }
        echo $output;
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
			foreach ($job_details as $single) {				

				

				if($single->client_id != ''){
					$company_details = DB::table('cm_clients')->where('client_id', $single->client_id)->first();
					$companyname = $company_details->company;
				}
				else{
					$companyname = '';
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
					$task_type = 'Globel Task';
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

		        if($jobs->quick_job == 0 && $jobs->status == 0){
                  $job_time_checked = $jobtime;
                }
                else if($jobs->quick_job == 1 && $jobs->status == 0){
                  $job_time_checked = 'N/A';
                }
                else if($jobs->quick_job == 0 && $jobs->status == 1){
                  $job_time_checked = $jobs->job_time;
                }




		      	$columns_2 = array($i, $user_details->firstname, $companyname, $task_details->task_name, $task_type, date('d-M-Y', strtotime($single->job_date)), date('h:i A', strtotime($single->start_time)), $job_time_checked );
				fputcsv($file, $columns_2);
				$i++;
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
                    $task_type = '<i class="fa fa-globe" style="margin-right:10px;"></i> Globel Task';
                  }
                }
                else{
                  $task_name = 'N/A';
                  $task_type = 'N/A';
                }

                $break_time_count = DB::table('job_break_time')->where('job_id', $jobs->id)->get();
                                    
                $count_minues = 0;
                if(count($break_time_count)){
                  foreach ($break_time_count as $break_time1) {
                    if($break_time1->break_time == "01:00:00") { $minval = 60; }
                    elseif($break_time1->break_time == "00:45:00") { $minval = 45; }
                    elseif($break_time1->break_time == "00:30:00") { $minval = 30; }
                    elseif($break_time1->break_time == "00:15:00") { $minval = 15; }
                    if($count_minues == 0)
                    {
                      $count_minues = $minval;
                    }
                    else{
                      $count_minues = $count_minues + $minval;
                    }
                    
                  }
                }

                if($count_minues == 0)
                {
                  $break_hours = '';
                }
                elseif($count_minues < 60)
                {
                  $break_hours = $count_minues.' Minutes';
                }
                elseif($count_minues == 60)
                {
                  $break_hours = '1 Hour';
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
                  }
                  else{
                    $break_hours = $h.':'.$m.' Hours';
                  }
                  
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


                if($jobs->quick_job == 0 && $jobs->status == 0){
                  $job_time_checked = $jobtime;
                }
                else if($jobs->quick_job == 1 && $jobs->status == 0){
                  $job_time_checked = 'N/A';
                }
                else if($jobs->quick_job == 0 && $jobs->status == 1){
                  $job_time_checked = $jobs->job_time;
                }
                

        $output.='
        <tr>          
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$i.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$user_details->firstname.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$client_name.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$task_name.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$task_type.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.date('h:i A', strtotime($jobs->start_time)).'</td>
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

		$columns = array('#', 'User', 'Client Name', 'Task Name', 'Task Type', 'Date', 'Start Time', 'Job Time');
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
					$companyname = '';
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
					$task_type = 'Globel Task';
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

		        if($jobs->quick_job == 0 && $jobs->status == 0){
                  $job_time_checked = $jobtime;
                }
                else if($jobs->quick_job == 1 && $jobs->status == 0){
                  $job_time_checked = 'N/A';
                }
                else if($jobs->quick_job == 0 && $jobs->status == 1){
                  $job_time_checked = $jobs->job_time;
                }




		      	$columns_2 = array($i, $user_details->firstname, $companyname, $task_details->task_name, $task_type, date('d-M-Y', strtotime($single->job_date)), date('h:i A', strtotime($single->start_time)), $job_time_checked );
				fputcsv($file, $columns_2);
				$i++;
			}
			fclose($file);
		};
		return Response::stream($callback, 200, $headers);
		//return $filename.'_InvoiceReport.csv';

	}


	public function searchjobofday(){
		$date = date('Y-m-d', strtotime(Input::get('value')));
		$joblist = DB::table('task_job')->where('job_date', $date)->get();		

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
                    $task_type = '<i class="fa fa-globe" style="margin-right:10px;"></i> Globel Task';
                  }
                }
                else{
                  $task_name = 'N/A';
                  $task_type = 'N/A';
                }

                $break_time_count = DB::table('job_break_time')->where('job_id', $jobs->id)->get();
                                    
                $count_minues = 0;
                if(count($break_time_count)){
                  foreach ($break_time_count as $break_time1) {
                    if($break_time1->break_time == "01:00:00") { $minval = 60; }
                    elseif($break_time1->break_time == "00:45:00") { $minval = 45; }
                    elseif($break_time1->break_time == "00:30:00") { $minval = 30; }
                    elseif($break_time1->break_time == "00:15:00") { $minval = 15; }
                    if($count_minues == 0)
                    {
                      $count_minues = $minval;
                    }
                    else{
                      $count_minues = $count_minues + $minval;
                    }
                    
                  }
                }

                if($count_minues == 0)
                {
                  $break_hours = '';
                }
                elseif($count_minues < 60)
                {
                  $break_hours = $count_minues.' Minutes';
                }
                elseif($count_minues == 60)
                {
                  $break_hours = '1 Hour';
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
                  }
                  else{
                    $break_hours = $h.':'.$m.' Hours';
                  }
                  
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


                if($jobs->quick_job == 0 && $jobs->status == 0){
                  $job_time_checked = '<span id="job_time_refresh_'.$jobs->id.'">'.$jobtime.'</span></span> &nbsp;&nbsp;<a href="javascript:"><i class="fa fa-refresh job_time_refresh" aria-hidden="true" data-element="'.$jobs->id.'"></i></a>';
                }
                else if($jobs->quick_job == 1 && $jobs->status == 0){
                  $job_time_checked = 'N/A';
                }
                else if($jobs->quick_job == 0 && $jobs->status == 1){
                  $job_time_checked = $jobs->job_time;
                }
                

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
          <td align="left">'.date('h:i A', strtotime($jobs->start_time)).'</td>
          <td align="left">'.date('h:i A', strtotime($jobs->stop_time)).'</td>
          <td align="left">'.$job_time_checked.'</td>
          <td><a href="javascript:"><i class="fa fa-pencil edit_task " data-toggle="tooltip" title="Edit Job" aria-hidden="true"></i></a></td>
          
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
                    $task_type = '<i class="fa fa-globe" style="margin-right:10px;"></i> Globel Task';
                  }
                }
                else{
                  $task_name = 'N/A';
                  $task_type = 'N/A';
                }

                $break_time_count = DB::table('job_break_time')->where('job_id', $jobs->id)->get();
                                    
                $count_minues = 0;
                if(count($break_time_count)){
                  foreach ($break_time_count as $break_time1) {
                    if($break_time1->break_time == "01:00:00") { $minval = 60; }
                    elseif($break_time1->break_time == "00:45:00") { $minval = 45; }
                    elseif($break_time1->break_time == "00:30:00") { $minval = 30; }
                    elseif($break_time1->break_time == "00:15:00") { $minval = 15; }
                    if($count_minues == 0)
                    {
                      $count_minues = $minval;
                    }
                    else{
                      $count_minues = $count_minues + $minval;
                    }
                    
                  }
                }

                if($count_minues == 0)
                {
                  $break_hours = '';
                }
                elseif($count_minues < 60)
                {
                  $break_hours = $count_minues.' Minutes';
                }
                elseif($count_minues == 60)
                {
                  $break_hours = '1 Hour';
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
                  }
                  else{
                    $break_hours = $h.':'.$m.' Hours';
                  }
                  
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


                if($jobs->quick_job == 0 && $jobs->status == 0){
                  $job_time_checked = $jobtime;
                }
                else if($jobs->quick_job == 1 && $jobs->status == 0){
                  $job_time_checked = 'N/A';
                }
                else if($jobs->quick_job == 0 && $jobs->status == 1){
                  $job_time_checked = $jobs->job_time;
                }
                

        $output.='
        <tr>          
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$i.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$user_details->firstname.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$client_name.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$task_name.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$task_type.'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
          <td align="left" style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.date('h:i A', strtotime($jobs->start_time)).'</td>
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


	public function joboftheday_report_pdf_download(){

		$htmlval = Input::get('htmlval');
		$pdf = PDF::loadHTML($htmlval);
		$pdf->setPaper('A4', 'landscape');
		$pdf->save('job_file/Job of the day.pdf');
		echo 'Job of the day.pdf';
	}

	

	

}

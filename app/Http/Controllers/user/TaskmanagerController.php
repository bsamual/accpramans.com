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
use App\Task_Job;
use App\CmClients;
use App\Job_Break_Time;
use Session;
use DateTime;
use URL;
use PDF;
use Response;
use PHPExcel; 
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class TaskmanagerController extends Controller {
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
	public function __construct(year $year, week $week, task $task, classified $classified, user $user, vatclients $vatclients, task_job $task_job, cmclients $cmclients, job_break_time  $job_break_time)
	{
		$this->middleware('userauth');
		$this->year = $year;
		$this->week = $week;
		$this->task = $task;
		$this->classified = $classified;
		$this->user = $user;
		$this->vatclients = $vatclients;
		$this->task_job = $task_job;
		$this->cmclients = $cmclients;
		$this->job_break_time = $job_break_time;
		date_default_timezone_set("Europe/Dublin");
		//date_default_timezone_set("Asia/Calcutta");
	}
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function task_manager()
	{
		if(Session::has('taskmanager_user'))
		{
			$userid = Session::get('taskmanager_user');
			if($userid == "")
			{
				$user_tasks = array();
				$open_task_count = array();
				$authored_task_count = 0;
				$park_task_count = array();
			}
			else{
				$user_tasks = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND (`allocated_to` = '".$userid."' OR `author` = '".$userid."' OR `allocated_to` = '0')");
				$open_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND (`allocated_to` = '".$userid."' OR (`allocated_to` = '0' AND `author` != '".$userid."'))");
				$authored_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND `author` = '".$userid."'");
				$park_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '2' AND (`allocated_to` = '".$userid."' OR `allocated_to` = '0' OR `author` = '".$userid."')");

				
			}
		}
		else{
			$user_tasks = array();
			$open_task_count = array();
			$authored_task_count = 0;
			$park_task_count = array();
		}


		$tasks = DB::table('time_task')->where('task_type', 0)->orderBy('task_name', 'asc')->get();
		$user = DB::table('user')->where('user_status', 0)->where('disabled',0)->orderBy('firstname','asc')->get();
		return view('user/task_manager/task_manager', array('title' => 'Easypayroll - Task Manager', 'userlist' => $user, 'taskslist' => $tasks,'user_tasks' => $user_tasks,'open_task_count' => $open_task_count,'authored_task_count' => $authored_task_count,'park_task_count' =>$park_task_count));
	}
	public function park_task()
	{
		if(Session::has('taskmanager_user'))
		{
			$userid = Session::get('taskmanager_user');
			if($userid == "")
			{
				$user_tasks = array();
				$open_task_count = array();
				$authored_task_count = 0;
				$park_task_count = array();
			}
			else{
				$user_tasks = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '2' AND (`allocated_to` = '".$userid."' OR `author` = '".$userid."' OR `allocated_to` = '0')");
				$open_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND (`allocated_to` = '".$userid."' OR (`allocated_to` = '0' AND `author` != '".$userid."'))");
				$park_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '2' AND (`allocated_to` = '".$userid."' OR `allocated_to` = '0' OR `author` = '".$userid."')");
				$authored_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND `author` = '".$userid."'");
			}
		}
		else{
			$user_tasks = array();
			$open_task_count = array();
			$authored_task_count = 0;
			$park_task_count = array();
		}


		$tasks = DB::table('time_task')->where('task_type', 0)->orderBy('task_name', 'asc')->get();
		$user = DB::table('user')->where('user_status', 0)->where('disabled',0)->orderBy('firstname','asc')->get();
		return view('user/task_manager/park_task', array('title' => 'Easypayroll - Task Manager', 'userlist' => $user, 'taskslist' => $tasks,'user_tasks' => $user_tasks,'open_task_count' => $open_task_count,'authored_task_count' => $authored_task_count,'park_task_count' =>$park_task_count));
	}
	public function taskmanager_search()
	{
		if(Session::has('taskmanager_user'))
		{
			$userid = Session::get('taskmanager_user');
			if($userid == "")
			{
				$user_tasks = array();
				$open_task_count = array();
				$authored_task_count = 0;
				$park_task_count = array();
			}
			else{
				$user_tasks = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND (`allocated_to` = '".$userid."' OR `author` = '".$userid."' OR `allocated_to` = '0')");
				$open_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND `author` != '".$userid."' AND (`allocated_to` = '".$userid."' OR `allocated_to` = '0')");
				$authored_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND `author` = '".$userid."'");
				$park_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '2' AND (`allocated_to` = '".$userid."' OR `allocated_to` = '0' OR `author` = '".$userid."')");
			}
		}
		else{
			$user_tasks = array();
			$open_task_count = array();
			$authored_task_count = 0;
			$park_task_count = array();
		}

		$tasks = DB::table('time_task')->where('task_type', 0)->orderBy('task_name', 'asc')->get();
		$user = DB::table('user')->where('user_status', 0)->where('disabled',0)->orderBy('firstname','asc')->get();
		return view('user/task_manager/task_search', array('title' => 'Easypayroll - Task Manager', 'userlist' => $user, 'taskslist' => $tasks,'user_tasks' => $user_tasks,'open_task_count' => $open_task_count,'authored_task_count' => $authored_task_count,'park_task_count' => $park_task_count));
	}
	public function task_administration()
	{
		if(Session::has('taskmanager_user'))
		{
			$userid = Session::get('taskmanager_user');
			if($userid == "")
			{
				$user_tasks = array();
				$open_task_count = array();
				$authored_task_count = 0;
				$park_task_count = array();
			}
			else{
				$user_tasks = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND (`allocated_to` = '".$userid."' OR `author` = '".$userid."' OR `allocated_to` = '0')");
				$open_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND (`allocated_to` = '".$userid."' OR (`allocated_to` = '0' AND `author` != '".$userid."'))");
				$authored_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND `author` = '".$userid."'");
				$park_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '2' AND (`allocated_to` = '".$userid."' OR `allocated_to` = '0' OR `author` = '".$userid."')");
			}
		}
		else{
			$user_tasks = array();
			$open_task_count = array();
			$authored_task_count = 0;
			$park_task_count = array();
		}
		$tasks = DB::table('taskmanager')->orderBy('id','desc')->offset(0)->limit(500)->get();

		$user = DB::table('user')->where('user_status', 0)->where('disabled',0)->orderBy('firstname','asc')->get();
		return view('user/task_manager/task_administration', array('title' => 'Easypayroll - Task Manager', 'taskslist' => $tasks,'userlist' => $user,'user_tasks' => $user_tasks,'open_task_count' => $open_task_count,'authored_task_count' => $authored_task_count,'park_task_count' => $park_task_count));
	}
	
	public function show_infiles()
	{
		$client_id = Input::get('client_id');
		$ids = explode(",",Input::get('ids'));

		$infiles = DB::table('in_file')->where('client_id',$client_id)->get();
		$output = '
		<input type="checkbox" name="show_incomplete_files" class="show_incomplete_files" id="show_incomplete_files" value=""><label for="show_incomplete_files">Hide Complete files</label>
		<table class="table">
		<thead>
			<tr>
				<th>S.No</th>
				<th style="text-align:left">Date Received</th>
				<th style="text-align:left">Description</th>
				<th></th>
			</tr>
		</thead>
		<tbody>';
		if(count($infiles))
		{
			$i = 1;
			foreach($infiles as $file)
			{
				if($file->status == 1)
				{
					$red = 'color:#f00';
					$display = '';
					$tr = 'tr_incomplete';
				}
				else{
					$red = 'color:#000';
					$display = '';
					$tr = 'tr_complete';
				}
				$ele = URL::to('user/infile_search?client_id='.$client_id.'&fileid='.$file->id.'');
				if(in_array($file->id,$ids))
				{
					$checked = 'checked';
				}
				else{
					$checked = '';
				}
				$output.='<tr class="'.$tr.'" '.$display.'>
					<td style="'.$red.'">'.$i.'</td>
					<td style="text-align:left"><a href="javascript:" style="'.$red.'" class="link_infile" data-element="'.$ele.'">'.date('d-M-Y', strtotime($file->data_received)).'</a></td>
					<td style="text-align:left"><a href="javascript:" style="'.$red.'" class="link_infile" data-element="'.$ele.'">'.$file->description.'</a></td>
					<td style="text-align:left"><input type="checkbox" name="infile_check" class="infile_check" value="'.$file->id.'" '.$checked.'><label>&nbsp;</label></td>
				</tr>';
				$i++;
			}
		}
		else{
			$output.='<tr>
					<td colspan="4">No Files Found</td>
				</tr>';
		}
		$output.='</tbody>
		</table>';

		echo $output;
	}
	public function show_progress_infiles()
	{
		$client_id = Input::get('client_id');
		$ids = explode(",",Input::get('ids'));

		$infiles = DB::table('in_file')->where('client_id',$client_id)->get();
		$output = '
		<input type="checkbox" name="show_incomplete_files" class="show_incomplete_files" id="show_incomplete_files" value=""><label for="show_incomplete_files">Hide Complete files</label>
		<table class="table">
		<thead>
			<tr>
				<th>S.No</th>
				<th style="text-align:left">Date Received</th>
				<th style="text-align:left">Description</th>
				<th></th>
			</tr>
		</thead>
		<tbody>';
		if(count($infiles))
		{
			$i = 1;
			foreach($infiles as $file)
			{
				if($file->status == 1)
				{
					$red = 'color:#f00';
					$display = '';
					$tr = 'tr_incomplete';
				}
				else{
					$red = 'color:#000';
					$display = '';
					$tr = 'tr_complete';
				}
				$ele = URL::to('user/infile_search?client_id='.$client_id.'&fileid='.$file->id.'');
				if(in_array($file->id,$ids))
				{
					$checked = 'checked';
				}
				else{
					$checked = '';
				}
				$output.='<tr class="'.$tr.'" '.$display.'>
					<td style="'.$red.'">'.$i.'</td>
					<td style="text-align:left"><a href="javascript:" style="'.$red.'" class="link_infile" data-element="'.$ele.'">'.date('d-M-Y', strtotime($file->data_received)).'</a></td>
					<td style="text-align:left"><a href="javascript:" style="'.$red.'" class="link_infile" data-element="'.$ele.'">'.$file->description.'</a></td>
					<td style="text-align:left"><input type="checkbox" name="infile_check" class="infile_progress_check" value="'.$file->id.'" '.$checked.'><label>&nbsp;</label></td>
				</tr>';
				$i++;
			}
		}
		else{
			$output.='<tr>
					<td colspan="4">No Files Found</td>
				</tr>';
		}
		$output.='</tbody>
		</table>';

		echo $output;
	}
	public function show_completion_infiles()
	{
		$client_id = Input::get('client_id');
		$ids = explode(",",Input::get('ids'));

		$infiles = DB::table('in_file')->where('client_id',$client_id)->get();
		$output = '
		<input type="checkbox" name="show_incomplete_files" class="show_incomplete_files" id="show_incomplete_files" value=""><label for="show_incomplete_files">Hide Complete files</label>
		<table class="table">
		<thead>
			<tr>
				<th>S.No</th>
				<th style="text-align:left">Date Received</th>
				<th style="text-align:left">Description</th>
				<th></th>
			</tr>
		</thead>
		<tbody>';
		if(count($infiles))
		{
			$i = 1;
			foreach($infiles as $file)
			{
				if($file->status == 1)
				{
					$red = 'color:#f00';
					$display = '';
					$tr = 'tr_incomplete';
				}
				else{
					$red = 'color:#000';
					$display = '';
					$tr = 'tr_complete';
				}
				$ele = URL::to('user/infile_search?client_id='.$client_id.'&fileid='.$file->id.'');
				if(in_array($file->id,$ids))
				{
					$checked = 'checked';
				}
				else{
					$checked = '';
				}
				$output.='<tr class="'.$tr.'" '.$display.'>
					<td style="'.$red.'">'.$i.'</td>
					<td style="text-align:left"><a href="javascript:" style="'.$red.'" class="link_infile" data-element="'.$ele.'">'.date('d-M-Y', strtotime($file->data_received)).'</a></td>
					<td style="text-align:left"><a href="javascript:" style="'.$red.'" class="link_infile" data-element="'.$ele.'">'.$file->description.'</a></td>
					<td style="text-align:left"><input type="checkbox" name="infile_check" class="infile_completion_check" value="'.$file->id.'" '.$checked.'><label>&nbsp;</label></td>
				</tr>';
				$i++;
			}
		}
		else{
			$output.='<tr>
					<td colspan="4">No Files Found</td>
				</tr>';
		}
		$output.='</tbody>
		</table>';

		echo $output;
	}
	public function infile_upload_images_taskmanager_add()
	{
		$upload_dir = 'uploads/taskmanager_image';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/temp';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}

		if (!empty($_FILES)) {
			 $tmpFile = $_FILES['file']['tmp_name'];
			 $fname = str_replace("#","",$_FILES['file']['name']);
			 $fname = str_replace("#","",$fname);
			 $fname = str_replace("#","",$fname);
			 $fname = str_replace("#","",$fname);

			 $fname = str_replace("%","",$fname);
			 $fname = str_replace("%","",$fname);
			 $fname = str_replace("%","",$fname);

			 $filename = $upload_dir.'/'.$fname;

			 $arrayval = array('attachment' => $fname,'url' => $upload_dir);

	 		if(Session::has('task_file_attach_add'))
			{
				$getsession = Session::get('task_file_attach_add');
			}
			else{
				$getsession = array();
			}
			$getsession = array_values($getsession);
			array_push($getsession,$arrayval);

			$sessn=array('task_file_attach_add' => $getsession);
			Session::put($sessn);

			move_uploaded_file($tmpFile,$filename);
			$key = count($getsession) - 1;
		 	echo json_encode(array('id' => 0,'filename' => $fname,'file_id' => $key,'count'=>count($getsession)));
		}
	}
	public function infile_upload_images_taskmanager_progress()
	{
		$task_id = Input::get('hidden_task_id_progress');
		$upload_dir = 'uploads/taskmanager_image';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/'.base64_encode($task_id);
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}

		if (!empty($_FILES)) {
			$tmpFile = $_FILES['file']['tmp_name'];
			$fname = str_replace("#","",$_FILES['file']['name']);
			$fname = str_replace("#","",$fname);
			$fname = str_replace("#","",$fname);
			$fname = str_replace("#","",$fname);

			$fname = str_replace("%","",$fname);
			$fname = str_replace("%","",$fname);
			$fname = str_replace("%","",$fname);

			$filename = $upload_dir.'/'.$fname;
			move_uploaded_file($tmpFile,$filename);

			$data['task_id'] = $task_id;
			$data['filename'] = $fname;
			$data['url'] = $upload_dir; 
			$data['status'] = 1;

			$insertedid = DB::table('taskmanager_files')->insertGetid($data);

			$download_url = URL::to($filename);
			$delete_url = URL::to('user/delete_taskmanager_files?file_id='.$insertedid.'');
			
		 	echo json_encode(array('id' => $insertedid,'filename' => $fname,'task_id' => $task_id, 'download_url' => $download_url, 'delete_url' => $delete_url));
		}
	}
	public function infile_upload_images_taskmanager_completion()
	{
		$task_id = Input::get('hidden_task_id_completion');
		$upload_dir = 'uploads/taskmanager_image';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/'.base64_encode($task_id);
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}

		if (!empty($_FILES)) {
			$tmpFile = $_FILES['file']['tmp_name'];
			$fname = str_replace("#","",$_FILES['file']['name']);
			$fname = str_replace("#","",$fname);
			$fname = str_replace("#","",$fname);
			$fname = str_replace("#","",$fname);

			$fname = str_replace("%","",$fname);
			$fname = str_replace("%","",$fname);
			$fname = str_replace("%","",$fname);

			$filename = $upload_dir.'/'.$fname;
			move_uploaded_file($tmpFile,$filename);

			$data['task_id'] = $task_id;
			$data['filename'] = $fname;
			$data['url'] = $upload_dir; 
			$data['status'] = 2;

			$insertedid = DB::table('taskmanager_files')->insertGetid($data);

			$download_url = URL::to($filename);
			$delete_url = URL::to('user/delete_taskmanager_files?file_id='.$insertedid.'');
			
		 	echo json_encode(array('id' => $insertedid,'filename' => $fname,'task_id' => $task_id, 'download_url' => $download_url, 'delete_url' => $delete_url));
		}
	}
	public function add_taskmanager_notepad_contents()
	{
		$contents = Input::get('contents');
		$upload_dir = 'uploads/taskmanager_image';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/temp';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}

		if(Session::has('notepad_attach_task_add'))
		{
			$count = count(Session::get('notepad_attach_task_add'));
			$counts = $count + 1;
			$file_name =  time();
			$filename = $file_name.'-'.$counts;	

			$arrayval = array('attachment' => $filename.".txt",'url' => $upload_dir);
			$getsession = Session::get('notepad_attach_task_add');
			$getsession = array_values($getsession);
			array_push($getsession,$arrayval);

		}
		else{
			$count = 0;
			$counts = $count + 1;
			$file_name =  time();
			$filename = $file_name.'-'.$counts;	

			$arrayval = array('attachment' => $filename.".txt",'url' => $upload_dir);
			$getsession = array();
			array_push($getsession,$arrayval);
		}

		

		$myfile = fopen($upload_dir."/".$filename.".txt", "w") or die("Unable to open file!");
		fwrite($myfile, $contents);
		fclose($myfile);

		$sessn=array('notepad_attach_task_add' => $getsession);
		Session::put($sessn);
		$key = count($getsession) - 1;
		echo json_encode(array('filename' => $filename.".txt",'file_id' => $key));
	}

	public function taskmanager_notepad_contents_progress()
	{
		$contents = Input::get('contents');
		$task_id = Input::get('task_id');

		$upload_dir = 'uploads/taskmanager_image';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/'.base64_encode($task_id);
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}

		$count = 0;
		$counts = $count + 1;
		$file_name =  time();
		$filename = $file_name.'-'.$counts;	
		
		$myfile = fopen($upload_dir."/".$filename.".txt", "w") or die("Unable to open file!");
		fwrite($myfile, $contents);
		fclose($myfile);

		$data['task_id'] = $task_id;
		$data['url'] = $upload_dir;
		$data['filename'] = $filename.".txt";
		$data['status'] = 1;
		$insertedid = DB::table('taskmanager_notepad')->insertGetid($data);

		$download_url = URL::to($upload_dir."/".$filename.".txt");
		$delete_url = URL::to('user/delete_taskmanager_notepad?file_id='.$insertedid.'');
		
	 	echo json_encode(array('id' => $insertedid,'filename' => $filename.".txt",'task_id' => $task_id, 'download_url' => $download_url, 'delete_url' => $delete_url));
	}
	public function taskmanager_notepad_contents_completion()
	{
		$contents = Input::get('contents');
		$task_id = Input::get('task_id');

		$upload_dir = 'uploads/taskmanager_image';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/'.base64_encode($task_id);
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}

		$count = 0;
		$counts = $count + 1;
		$file_name =  time();
		$filename = $file_name.'-'.$counts;	
		
		$myfile = fopen($upload_dir."/".$filename.".txt", "w") or die("Unable to open file!");
		fwrite($myfile, $contents);
		fclose($myfile);

		$data['task_id'] = $task_id;
		$data['url'] = $upload_dir;
		$data['filename'] = $filename.".txt";
		$data['status'] = 2;
		$insertedid = DB::table('taskmanager_notepad')->insertGetid($data);

		$download_url = URL::to($upload_dir."/".$filename.".txt");
		$delete_url = URL::to('user/delete_taskmanager_notepad?file_id='.$insertedid.'');
		
	 	echo json_encode(array('id' => $insertedid,'filename' => $filename.".txt",'task_id' => $task_id, 'download_url' => $download_url, 'delete_url' => $delete_url));
	}
	public function clear_session_task_attachments()
	{
		if(Session::has('notepad_attach_task_add'))
		{
			Session::forget("notepad_attach_task_add");
		}
		if(Session::has('task_file_attach_add'))
		{
			Session::forget("task_file_attach_add");
		}
		$dir = "uploads/taskmanager_image/temp";//"path/to/targetFiles";
	    
	    // Open a known directory, and proceed to read its contents
	    if (is_dir($dir)) {
	        if ($dh = opendir($dir)) {
	            while (($file = readdir($dh)) !== false) {
		            if ($file==".") continue;
		            if ($file=="..")continue;
		            unlink($dir.'/'.$file);
	            }
	            closedir($dh);
	        }
	    }
	    $fileid = Input::get('fileid');
		if($fileid == "0")
		{
			echo '';
		}
		else{
			$file = DB::table('in_file')->where('id',$fileid)->first();
			$output = '<table class="table">
			<tbody>';
			if(count($file))
			{
				$i = 1;
				if($file->status == 1)
				{
					$red = 'color:#f00';
				}
				else{
					$red = 'color:#000';
				}

				$ele = URL::to('user/infile_search?client_id='.$file->client_id.'&fileid='.$file->id.'');
				
				$output.='<tr>
					<td>'.$i.'</td>
					<td style="text-align:left"><a href="javascript:" class="link_infile" data-element="'.$ele.'">'.date('d-M-Y', strtotime($file->data_received)).'</a></td>
					<td style="text-align:left"><a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$file->description.'</a></td>
					<td style="text-align:left"><a href="javascript:" class="fa fa-trash remove_infile_link_add" data-element="'.$file->id.'"></a></td>
				</tr>';
				$i++;
			}
			else{
				$output.='<tr>
						<td colspan="3">No Files Found</td>
					</tr>';
			}
			$output.='</tbody>
			</table>';

			echo $output;
		}
	}
	public function tasks_remove_dropzone_attachment()
	{
		$file_id = $_POST['file_id'];
		if(Session::has('task_file_attach_add'))
		{
			$files = Session::get('task_file_attach_add');
			unset($files[$file_id]);
			$getsession = array_values($files);

			$sessn=array('task_file_attach_add' => $getsession);
			Session::put($sessn);
		}
	}
	public function tasks_remove_notepad_attachment()
	{
		$file_id = $_POST['file_id'];
		if(Session::has('notepad_attach_task_add'))
		{
			$files = Session::get('notepad_attach_task_add');
			unset($files[$file_id]);
			$getsession = array_values($files);

			$sessn=array('notepad_attach_task_add' => $getsession);
			Session::put($sessn);
		}
	}
	public function show_linked_infiles()
	{
		$ids = explode(",",Input::get('ids'));
		$infiles = DB::table('in_file')->whereIn('id',$ids)->get();
		$output = '<table class="table">
		<tbody>';
		if(count($infiles))
		{
			$i = 1;
			foreach($infiles as $file)
			{
				if($file->status == 1)
				{
					$red = 'color:#f00';
				}
				else{
					$red = 'color:#000';
				}

				$ele = URL::to('user/infile_search?client_id='.$file->client_id.'&fileid='.$file->id.'');
				
				$output.='<tr>
					<td>'.$i.'</td>
					<td style="text-align:left"><a href="javascript:" class="link_infile" data-element="'.$ele.'">'.date('d-M-Y', strtotime($file->data_received)).'</a></td>
					<td style="text-align:left"><a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$file->description.'</a></td>
					<td style="text-align:left"><a href="javascript:" class="fa fa-trash remove_infile_link_add" data-element="'.$file->id.'"></a></td>
				</tr>';
				$i++;
			}
		}
		else{
			$output.='<tr>
					<td colspan="3">No Files Found</td>
				</tr>';
		}
		$output.='</tbody>
		</table>';

		echo $output;
	}
	public function show_linked_progress_infiles()
	{
		$ids = explode(",",Input::get('ids'));
		$task_id = Input::get('task_id');
		
		DB::table('taskmanager_infiles')->where('task_id',$task_id)->where('status',1)->delete();
		$output = 'Linked Infiles:<br/>';
		if(count($ids))
		{
			$i = 1;
			foreach($ids as $id)
			{
				$dataval['task_id'] = $task_id;
				$dataval['infile_id'] = $id;
				$dataval['status'] = 1;
				$insertedid = DB::table('taskmanager_infiles')->insertGetid($dataval);

				$file = DB::table('in_file')->where('id',$id)->first();
				$ele = URL::to('user/infile_search?client_id='.$file->client_id.'&fileid='.$file->id.'');

				$output.='<p class="link_infile_p"><a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$i.'</a>
                <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.date('d-M-Y', strtotime($file->data_received)).'</a>
                <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$file->description.'</a>
                <a href="'.URL::to('user/delete_taskmanager_infiles?file_id='.$insertedid.'').'" class="fa fa-trash delete_attachments"></a>
                </p>';
				$i++;
			}
		}
		echo $output;
	}
	public function show_linked_completion_infiles()
	{
		$ids = explode(",",Input::get('ids'));
		$task_id = Input::get('task_id');
		
		DB::table('taskmanager_infiles')->where('task_id',$task_id)->where('status',2)->delete();
		$output = 'Linked Infiles:<br/>';
		if(count($ids))
		{
			$i = 1;
			foreach($ids as $id)
			{
				$dataval['task_id'] = $task_id;
				$dataval['infile_id'] = $id;
				$dataval['status'] = 2;

				$insertedid = DB::table('taskmanager_infiles')->insertGetid($dataval);

				$file = DB::table('in_file')->where('id',$id)->first();
				$ele = URL::to('user/infile_search?client_id='.$file->client_id.'&fileid='.$file->id.'');

				$output.='<p class="link_infile_p"><a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$i.'</a>
                <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.date('d-M-Y', strtotime($file->data_received)).'</a>
                <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$file->description.'</a>
                <a href="'.URL::to('user/delete_taskmanager_infiles?file_id='.$insertedid.'').'" class="fa fa-trash delete_attachments"></a>
                </p>';
				$i++;
			}
		}
		echo $output;
	}
	public function create_new_taskmanager_task()
	{
		$action_type = Input::get('action_type');
		if($action_type == "1")
		{
			$author = Input::get('select_user');
			$creation_date = Input::get('created_date');
			$allocate_user = Input::get('allocate_user');
			$internal_checkbox = Input::get('internal_checkbox');
			$task_type = Input::get('idtask');
			$clientid = Input::get('clientid');
			$subject_class = Input::get('subject_class');
			$task_specifics = Input::get('task_specifics');
			$due_date = Input::get('due_date');
			$recurring = Input::get('recurring_checkbox');
			$accept_recurring = Input::get('accept_recurring');

			$task_specifics_val = strip_tags($task_specifics);
			if($subject_class == "")
			{
				$subject_cls = substr($task_specifics_val,0,30);
			}
			else{
				$subject_cls = $subject_class;
			}

			if($recurring == "1"){ $days = '30'; }
			elseif($recurring == "2"){ $days = '7'; }
			elseif($recurring == "3"){ $days = '1'; }
			else{ $days = Input::get('specific_recurring'); }

			$creation_date_change = DateTime::createFromFormat('d-M-Y', $creation_date);
			$creation_date_change = $creation_date_change->format('Y-m-d');

			$due_date_change = DateTime::createFromFormat('d-M-Y', $due_date);
			$due_date_change = $due_date_change->format('Y-m-d');

			$data['author'] = $author;
			$data['creation_date'] = $creation_date_change;
			$data['allocated_to'] = $allocate_user;
			$data['internal'] = ($internal_checkbox=="")?0:$internal_checkbox;
			$data['task_type'] = ($task_type=="")?0:$task_type;
			$data['client_id'] = $clientid;
			$data['subject'] = $subject_class;
			$data['task_specifics'] = $task_specifics;
			$data['due_date'] = $due_date_change;
			if($accept_recurring == "1")
			{
				$data['recurring_task'] = $recurring;
				$data['recurring_days'] = $days;
			}
			else{
				$data['recurring_task'] = 0;
				$data['recurring_days'] = 0;
			}

			$task_id = DB::table('taskmanager')->insertGetid($data);

			$taskids = 'A'.sprintf("%04d", $task_id);
			$dataupdate['taskid'] = $taskids;
			DB::table('taskmanager')->where('id',$task_id)->update($dataupdate);

			if(Session::has('task_file_attach_add'))
			{
				$files = Session::get('task_file_attach_add');
				$upload_dir = 'uploads/taskmanager_image';
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
				$upload_dir = $upload_dir.'/'.base64_encode($task_id);
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
	     		$dir = "uploads/taskmanager_image/temp";
			    $dirNew = $upload_dir;

			    if (is_dir($dir)) {
			        if ($dh = opendir($dir)) {
			            while (($file = readdir($dh)) !== false) {
				            if ($file==".") continue;
				            if ($file=="..")continue;
				            if(file_exists($dir.'/'.$file)){
				            	rename($dir.'/'.$file,$dirNew.'/'.$file);
				            }
			            }
			            closedir($dh);
			        }
			    }

				foreach($files as $file)
				{
					$dataval['task_id'] = $task_id;
	     			$dataval['url'] = $upload_dir;
					$dataval['filename'] = $file['attachment'];

					DB::table('taskmanager_files')->insert($dataval);
				}
			}
			if(Session::has('notepad_attach_task_add'))
			{
				$files = Session::get('notepad_attach_task_add');
				foreach($files as $file)
				{
					$upload_dir = 'uploads/taskmanager_image';
					if (!file_exists($upload_dir)) {
						mkdir($upload_dir);
					}
					$upload_dir = $upload_dir.'/'.base64_encode($task_id);
					if (!file_exists($upload_dir)) {
						mkdir($upload_dir);
					}
					if(file_exists("uploads/taskmanager_image/temp/".$file['attachment']))
					{
						rename("uploads/taskmanager_image/temp/".$file['attachment'], $upload_dir.'/'.$file['attachment']);
					}

					$dataval_notepad['task_id'] = $task_id;
					$dataval_notepad['filename'] = $file['attachment'];
					$dataval_notepad['url'] = $upload_dir;

					DB::table('taskmanager_notepad')->insert($dataval_notepad);
				}
			}
			$infiles = explode(",",Input::get('hidden_infiles_id'));
			if(count($infiles))
			{
				foreach($infiles as $infile)
				{
					if($infile != "" && $infile != "0")
					{
						$dataval_infile['task_id'] = $task_id;
						$dataval_infile['infile_id'] = $infile;
						DB::table('taskmanager_infiles')->insert($dataval_infile);
					}
				}
			}

			$dataupdate_spec_status['author_spec_status'] = 0;
			$dataupdate_spec_status['allocated_spec_status'] = 1;
			DB::table('taskmanager')->where('id',$task_id)->update($dataupdate_spec_status);
			
			$author = $author;
			$user_details = DB::table('user')->where('user_id',$author)->first();
			$task_specifics = $task_specifics.PHP_EOL;
			if($allocate_user != "")
			{
				$allocated_user = DB::table('user')->where('user_id',$allocate_user)->first();
				$message = '<spam style="color:#006bc7">---TASK CREATED - <strong>'.$creation_date.'</strong> BY <strong>'.$user_details->lastname.' '.$user_details->firstname.'</strong> AND ALLOCATED TO <strong>'.$allocated_user->lastname.' '.$allocated_user->firstname.'</strong> DUE BY <strong>'.$due_date.'</strong>---</spam>';

				$task_specifics.=$message;
				$data_specifics['to_user'] = $allocate_user;
				$data_specifics['allocated_date'] = date('Y-m-d H:i:s');

				$dataemail['author_name'] = $user_details->lastname.' '.$user_details->firstname;
				$dataemail['allocated_name'] = $allocated_user->lastname.' '.$allocated_user->firstname;
				$dataemail['creation_date'] = $creation_date;
				$dataemail['due_date'] = $due_date;

				$author_email = $user_details->email;
				$allocated_email = $allocated_user->email;
			}
			else{
				$message = '<spam style="color:#006bc7">---TASK CREATED - <strong>'.$creation_date.'</strong> BY <strong>'.$user_details->lastname.' '.$user_details->firstname.'</strong> DUE BY <strong>'.$due_date.'</strong>---</spam>';
				$task_specifics.=$message;
				$dataemail['author_name'] = $user_details->lastname.' '.$user_details->firstname;
				$dataemail['allocated_name'] = '';
				$dataemail['creation_date'] = $creation_date;
				$dataemail['due_date'] = $due_date;

				$author_email = $user_details->email;
				$allocated_email = '';
			}

			$data_specifics['task_id'] = $task_id;
			$data_specifics['message'] = $message;
			$data_specifics['from_user'] = $author;
			$data_specifics['created_date'] = $creation_date_change;
			$data_specifics['due_date'] = $due_date;
			$data_specifics['status'] = 1;

			DB::table('taskmanager_specifics')->insert($data_specifics);

			$task_specifics = strip_tags($task_specifics);

			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);
			$task_specifics = str_replace("&amp;", "&", $task_specifics);

			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);

			$uploads = 'papers/task_specifics.txt';
			$myfile = fopen($uploads, "w") or die("Unable to open file!");
			fwrite($myfile, $task_specifics);
			fclose($myfile);

			$dataemail['logo'] = URL::to('assets/images/easy_payroll_logo.png');
			$dataemail['subject'] = $subject_cls;

			$subject_email = 'Task Manager: New Task has been created: '.$subject_cls;
			$contentmessage = view('emails/task_manager/create_new_task_email_author', $dataemail)->render();
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			
			$email = new PHPMailer();
			$email->SetFrom('info@gbsco.ie');
			$email->Subject   = $subject_email;
			$email->Body      = $contentmessage;
			$email->AddCC('tasks@gbsco.ie');
			$email->IsHTML(true);
			$email->AddAddress( $author_email );
			$email->AddAttachment( $uploads , 'task_specifics.txt' );
			$email->Send();		

			if($allocate_user != "")
			{
				$contentmessage2 = view('emails/task_manager/create_new_task_email_allocated', $dataemail)->render();
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);

				$email = new PHPMailer();
				$email->SetFrom('info@gbsco.ie');
				$email->Subject   = $subject_email;
				$email->Body      = $contentmessage2;
				$email->IsHTML(true);
				$email->AddAddress( $allocated_email );
				$email->AddAttachment( $uploads , 'task_specifics.txt' );
				$email->Send();		
			}

			return redirect::back()->with('message', 'Task Created successfully');
		}
		else{
			$specific_type = Input::get('hidden_specific_type');
			$attachment_type = Input::get('hidden_attachment_type');
			$taskidval = Input::get('hidden_task_id_copy_task');

			$task_details = DB::table('taskmanager')->where('id',$taskidval)->first();

			if($specific_type == "1")
			{
				$task_specifics = $task_details->task_specifics;
			}
			else{
				$task_specifics = Input::get('task_specifics');
			}

			$author = Input::get('select_user');
			$creation_date = Input::get('created_date');
			$allocate_user = Input::get('allocate_user');
			$internal_checkbox = Input::get('internal_checkbox');
			$task_type = Input::get('idtask');
			$clientid = Input::get('clientid');
			$subject_class = Input::get('subject_class');
			$due_date = Input::get('due_date');
			$recurring = Input::get('recurring_checkbox');

			$task_specifics_val = strip_tags($task_specifics);
			
			if($subject_class == "")
			{
				$subject_cls = substr($task_specifics_val,0,30);
			}
			else{
				$subject_cls = $subject_class;
			}

			if($recurring == "1"){ $days = '30'; }
			elseif($recurring == "2"){ $days = '7'; }
			elseif($recurring == "3"){ $days = '1'; }
			else{ $days = Input::get('specific_recurring'); }

			$creation_date_change = DateTime::createFromFormat('d-M-Y', $creation_date);
			$creation_date_change = $creation_date_change->format('Y-m-d');

			$due_date_change = DateTime::createFromFormat('d-M-Y', $due_date);
			$due_date_change = $due_date_change->format('Y-m-d');

			$data['author'] = $author;
			$data['creation_date'] = $creation_date_change;
			$data['allocated_to'] = $allocate_user;
			$data['internal'] = ($internal_checkbox=="")?0:$internal_checkbox;
			$data['task_type'] = ($task_type=="")?0:$task_type;
			$data['client_id'] = $clientid;
			$data['subject'] = $subject_class;
			$data['task_specifics'] = $task_specifics;
			$data['due_date'] = $due_date_change;
			$data['recurring_task'] = $recurring;
			$data['recurring_days'] = $days;

			$task_id = DB::table('taskmanager')->insertGetid($data);

			$taskids = 'A'.sprintf("%04d", $task_id);
			$dataupdate['taskid'] = $taskids;
			DB::table('taskmanager')->where('id',$task_id)->update($dataupdate);

			if(Session::has('task_file_attach_add'))
			{
				$files = Session::get('task_file_attach_add');
				$upload_dir = 'uploads/taskmanager_image';
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
				$upload_dir = $upload_dir.'/'.base64_encode($task_id);
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
	     		$dir = "uploads/taskmanager_image/temp";
			    $dirNew = $upload_dir;

			    if (is_dir($dir)) {
			        if ($dh = opendir($dir)) {
			            while (($file = readdir($dh)) !== false) {
				            if ($file==".") continue;
				            if ($file=="..")continue;
				            if(file_exists($dir.'/'.$file)){
				            	rename($dir.'/'.$file,$dirNew.'/'.$file);
				            }
			            }
			            closedir($dh);
			        }
			    }

				foreach($files as $file)
				{
					$dataval['task_id'] = $task_id;
	     			$dataval['url'] = $upload_dir;
					$dataval['filename'] = $file['attachment'];

					DB::table('taskmanager_files')->insert($dataval);
				}
			}
			if(Session::has('notepad_attach_task_add'))
			{
				$files = Session::get('notepad_attach_task_add');
				foreach($files as $file)
				{
					$upload_dir = 'uploads/taskmanager_image';
					if (!file_exists($upload_dir)) {
						mkdir($upload_dir);
					}
					$upload_dir = $upload_dir.'/'.base64_encode($task_id);
					if (!file_exists($upload_dir)) {
						mkdir($upload_dir);
					}
					if(file_exists("uploads/taskmanager_image/temp/".$file['attachment']))
					{
						rename("uploads/taskmanager_image/temp/".$file['attachment'], $upload_dir.'/'.$file['attachment']);
					}

					$dataval_notepad['task_id'] = $task_id;
					$dataval_notepad['filename'] = $file['attachment'];
					$dataval_notepad['url'] = $upload_dir;

					DB::table('taskmanager_notepad')->insert($dataval_notepad);
				}
			}
			$infiles = explode(",",Input::get('hidden_infiles_id'));
			if(count($infiles))
			{
				foreach($infiles as $infile)
				{
					if($infile != "" && $infile != "0")
					{
						$dataval_infile['task_id'] = $task_id;
						$dataval_infile['infile_id'] = $infile;
						DB::table('taskmanager_infiles')->insert($dataval_infile);
					}
				}
			}

			if($specific_type == "1")
			{
				$task_details = DB::table('taskmanager')->where('id',$taskidval)->first();

				$specifics = DB::table('taskmanager_specifics')->where('task_id',$taskidval)->get();
				if(count($specifics))
				{
					foreach($specifics as $specific)
					{
						$datacopyspec['task_id'] = $task_id;
						$datacopyspec['message'] = $specific->message;
						$datacopyspec['from_user'] = $specific->from_user;
						$datacopyspec['to_user'] = $specific->to_user;
						$datacopyspec['created_date'] = $specific->created_date;
						$datacopyspec['allocated_date'] = $specific->allocated_date;
						$datacopyspec['due_date'] = $specific->due_date;
						$datacopyspec['status'] = $specific->status;

						DB::table('taskmanager_specifics')->insert($datacopyspec);
					}
				}
			}

			if($attachment_type == "1")
			{
				$copied_files = Input::get('copy_files');
				$copied_notes = Input::get('copy_notes');
				$copied_infiles = Input::get('copy_infiles');
				if(count($copied_files))
				{
					foreach($copied_files as $file)
					{
						$detailsval = DB::table('taskmanager_files')->where('id',$file)->first();
						$datafile['task_id'] = $task_id;
						$datafile['url'] = $detailsval->url;
						$datafile['filename'] = $detailsval->filename;
						$datafile['status'] = $detailsval->status;

						DB::table('taskmanager_files')->insert($datafile);
					}
				}

				if(count($copied_notes))
				{
					foreach($copied_notes as $note)
					{
						$detailsval = DB::table('taskmanager_notepad')->where('id',$note)->first();
						$datanote['task_id'] = $task_id;
						$datanote['url'] = $detailsval->url;
						$datanote['filename'] = $detailsval->filename;
						$datanote['status'] = $detailsval->status;

						DB::table('taskmanager_notepad')->insert($datanote);
					}
				}

				if(count($copied_infiles))
				{
					foreach($copied_infiles as $infile)
					{
						$detailsval = DB::table('taskmanager_infiles')->where('id',$infile)->first();
						$datainfile['task_id'] = $task_id;
						$datainfile['infile_id'] = $detailsval->infile_id;
						$datainfile['status'] = $detailsval->status;

						DB::table('taskmanager_infiles')->insert($datainfile);
					}
				}
			}

			$dataupdate_spec_status['author_spec_status'] = 0;
			$dataupdate_spec_status['allocated_spec_status'] = 1;
			DB::table('taskmanager')->where('id',$task_id)->update($dataupdate_spec_status);

			$author = $author;
			$user_details = DB::table('user')->where('user_id',$author)->first();

			$task_specifics = $task_specifics.PHP_EOL;
			
			if($allocate_user != "")
			{
				$allocated_user = DB::table('user')->where('user_id',$allocate_user)->first();
				$message = '<spam style="color:#006bc7">---TASK CREATED - <strong>'.$creation_date.'</strong> BY <strong>'.$user_details->lastname.' '.$user_details->firstname.'</strong> AND ALLOCATED TO <strong>'.$allocated_user->lastname.' '.$allocated_user->firstname.'</strong> DUE BY <strong>'.$due_date.'</strong>---</spam>';
				$task_specifics.=$message;
				$data_specifics['to_user'] = $allocate_user;
				$data_specifics['allocated_date'] = date('Y-m-d H:i:s');

				$dataemail['author_name'] = $user_details->lastname.' '.$user_details->firstname;
				$dataemail['allocated_name'] = $allocated_user->lastname.' '.$allocated_user->firstname;
				$dataemail['creation_date'] = $creation_date;
				$dataemail['due_date'] = $due_date;

				$author_email = $user_details->email;
				$allocated_email = $allocated_user->email;
			}
			else{
				$message = '<spam style="color:#006bc7">---TASK CREATED - <strong>'.$creation_date.'</strong> BY <strong>'.$user_details->lastname.' '.$user_details->firstname.'</strong> DUE BY <strong>'.$due_date.'</strong>---</spam>';
				$task_specifics.=$message;
				$dataemail['author_name'] = $user_details->lastname.' '.$user_details->firstname;
				$dataemail['allocated_name'] = '';
				$dataemail['creation_date'] = $creation_date;
				$dataemail['due_date'] = $due_date;

				$author_email = $user_details->email;
				$allocated_email = '';
			}


			$data_specifics['task_id'] = $task_id;
			$data_specifics['message'] = $message;
			$data_specifics['from_user'] = $author;
			$data_specifics['created_date'] = $creation_date_change;
			$data_specifics['due_date'] = $due_date;
			$data_specifics['status'] = 1;

			DB::table('taskmanager_specifics')->insert($data_specifics);

			$task_specifics = strip_tags($task_specifics);

			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			$task_specifics = str_replace("&nbsp;", " ", $task_specifics);
			
			$uploads = 'papers/task_specifics.txt';
			$myfile = fopen($uploads, "w") or die("Unable to open file!");
			fwrite($myfile, $task_specifics);
			fclose($myfile);

			$dataemail['logo'] = URL::to('assets/images/easy_payroll_logo.png');
			$dataemail['subject'] = $subject_cls;

			$subject_email = 'Task Manager: New Task has been created: '.$subject_cls;
			$contentmessage = view('emails/task_manager/create_new_task_email_author', $dataemail)->render();

			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);

			$email = new PHPMailer();
			$email->SetFrom('info@gbsco.ie');
			$email->Subject   = $subject_email;
			$email->Body      = $contentmessage;
			$email->AddCC('tasks@gbsco.ie');
			$email->IsHTML(true);
			$email->AddAddress( $author_email );
			$email->AddAttachment( $uploads , 'task_specifics.txt' );
			$email->Send();		

			if($allocate_user != "")
			{
				$contentmessage2 = view('emails/task_manager/create_new_task_email_allocated', $dataemail)->render();
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);
				$contentmessage2 = str_replace("â€“", "-", $contentmessage2);

				$email = new PHPMailer();
				$email->SetFrom('info@gbsco.ie');
				$email->Subject   = $subject_email;
				$email->Body      = $contentmessage2;
				$email->IsHTML(true);
				$email->AddAddress( $allocated_email );
				$email->AddAttachment( $uploads , 'task_specifics.txt' );
				$email->Send();		
			}

			return redirect::back()->with('message', 'Task Created successfully');
		}
	}
	public function change_taskmanager_user()
	{
		$user = Input::get('user');
		$sessn=array('taskmanager_user' => $user);
		Session::put($sessn);
	}
	public function delete_taskmanager_files()
	{
		$file_id = Input::get('file_id');
		$get_details = DB::table('taskmanager_files')->where('id',$file_id)->first();
		DB::table('taskmanager_files')->where('id',$file_id)->delete();
		//return redirect('user/task_manager?tr_task_id='.$get_details->task_id);
	}
	public function delete_taskmanager_notepad()
	{
		$file_id = Input::get('file_id');
		$get_details = DB::table('taskmanager_notepad')->where('id',$file_id)->first();
		DB::table('taskmanager_notepad')->where('id',$file_id)->delete();
		//return redirect('user/task_manager?tr_task_id='.$get_details->task_id);
	}
	public function delete_taskmanager_infiles()
	{
		$file_id = Input::get('file_id');
		$get_details = DB::table('taskmanager_infiles')->where('id',$file_id)->first();
		DB::table('taskmanager_infiles')->where('id',$file_id)->delete();
		//return redirect('user/task_manager?tr_task_id='.$get_details->task_id);
	}
	public function taskmanager_change_due_date()
	{
		$task_id = Input::get('task_id');
		$new_date = Input::get('new_date');

		$task_details = DB::table('taskmanager')->where('id',$task_id)->first();
		$old_due_date = date('d-M-Y',strtotime($task_details->due_date));

		$new_change_date = DateTime::createFromFormat('d-M-Y', $new_date);
		$new_change_date = $new_change_date->format('Y-m-d');
		$data['due_date'] = $new_change_date;
		DB::table('taskmanager')->where('id',$task_id)->update($data);

		$allocated = Session::get('taskmanager_user');
		$user_details = DB::table('user')->where('user_id',$allocated)->first();
		$message = '<spam style="color:#006bc7">****<strong>'.$user_details->lastname.' '.$user_details->firstname.'</strong> has changed the due date of this task to <strong>'.$new_date.'</strong>****</spam>';

		$dataval['task_id'] = $task_id;
		$dataval['message'] = $message;
		$dataval['from_user'] = $allocated;
		$dataval['due_date'] = $new_change_date;
		$dataval['status'] = 3;
		DB::table('taskmanager_specifics')->insert($dataval);

	    $date1=date_create(date('Y-m-d'));
	    $date2=date_create($new_change_date);
	    $diff=date_diff($date1,$date2);
	    $diffdays = $diff->format("%R%a");

	    if($diffdays == 0 || $diffdays== 1) { $due_color = '#e89701'; }
	    elseif($diffdays < 0) { $due_color = '#f00'; }
	    elseif($diffdays > 7) { $due_color = '#000'; }
	    elseif($diffdays <= 7) { $due_color = '#00a91d'; }
	    else{ $due_color = '#000'; }
	    
	    if(Session::has('taskmanager_user'))
	    {
	    	$sess_user = Session::get('taskmanager_user');
	    	if($sess_user == $task_details->author)
	    	{
	    		$dataupdate_spec_status['author_spec_status'] = 0;
				$dataupdate_spec_status['allocated_spec_status'] = 1;
	    	}
	    	else{
	    		$dataupdate_spec_status['author_spec_status'] = 1;
				$dataupdate_spec_status['allocated_spec_status'] = 0;
	    	}
	    	DB::table('taskmanager')->where('id',$task_id)->update($dataupdate_spec_status);
	    }

	    if($task_details->avoid_email == "0")
	    {
	    	$task_specifics_val = strip_tags($task_details->task_specifics);
	    	if($task_details->subject == "")
	    	{
	    		$subject_cls = substr(htmlspecialchars_decode($task_specifics_val),0,30);
	    	}
	    	else{
	    		$subject_cls = htmlspecialchars_decode($task_details->subject);
	    	}

	    	$author_details = DB::table('user')->where('user_id',$task_details->author)->first();
	    	$author_email = $author_details->email;
	    	if($task_details->allocated_to != 0)
	    	{
	    		$allocated_details = DB::table('user')->where('user_id',$task_details->allocated_to)->first();
	    		$dataemail['allocated_name'] = $allocated_details->lastname.' '.$allocated_details->firstname;
	    		$allocated_email = $allocated_details->email;
	    	}
	    	else{
	    		$dataemail['allocated_name'] = $author_details->lastname.' '.$author_details->firstname;
	    		$allocated_email = '';
	    	}

			$task_specifics = '';
			$specifics_first = DB::table('taskmanager_specifics')->where('task_id',$task_id)->orderBy('id','asc')->first();
			if(count($specifics_first))
			{
				$task_specifics.=$specifics_first->message;
			}
			$task_specifics.= PHP_EOL.$task_details->task_specifics;
			$specifics = DB::table('taskmanager_specifics')->where('task_id',$task_id)->where('id','!=',$specifics_first->id)->get();
			if(count($specifics))
			{
				foreach($specifics as $specific)
				{
					$task_specifics.=PHP_EOL.$specific->message;
				}
			}

			$task_specifics = strip_tags($task_specifics);

			$uploads = 'papers/task_specifics.txt';
			$myfile = fopen($uploads, "w") or die("Unable to open file!");
			fwrite($myfile, $task_specifics);
			fclose($myfile);
	    	
	    	$dataemail['logo'] = URL::to('assets/images/easy_payroll_logo.png');
			$dataemail['subject'] = $subject_cls;
			$dataemail['author_name'] = $author_details->lastname.' '.$author_details->firstname;
			$dataemail['new_due_date'] = $new_date;
			$dataemail['old_due_date'] = $old_due_date;
			

			$subject_email = 'Task Manager: Due Date Change: '.$subject_cls;
			$contentmessage = view('emails/task_manager/task_manager_due_date_change', $dataemail)->render();

			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);

			$email = new PHPMailer();
			$email->SetFrom('info@gbsco.ie');
			$email->Subject   = $subject_email;
			$email->Body      = $contentmessage;
			if($task_details->allocated_to != 0)
	    	{
				$email->AddCC($allocated_email);
			}
			$email->AddCC('tasks@gbsco.ie');
			$email->IsHTML(true);
			$email->AddAddress($author_email);
			$email->AddAttachment( $uploads , 'task_specifics.txt' );
			$email->Send();		
	    }

                          
		echo json_encode(array("new_date" => $new_date,"new_change_date" => $new_change_date, "color" => $due_color));
	}
	public function taskmanager_change_allocations()
	{
		$task_id = Input::get('task_id');
		$task_details = DB::table('taskmanager')->where('id',$task_id)->first();

		if($task_details->allocated_to == "" || $task_details->allocated_to == "0")
		{
			$from_details = DB::table('user')->where('user_id',$task_details->author)->first();
			$from = $from_details->lastname.' '.$from_details->firstname;

			$data_specifics['from_user'] = $task_details->author;
		}
		else{
			$from_details = DB::table('user')->where('user_id',$task_details->allocated_to)->first();
			$from = $from_details->lastname.' '.$from_details->firstname;

			$data_specifics['from_user'] = $task_details->allocated_to;
		}

		$new_allocation = Input::get('new_allocation');
		$data['allocated_to'] = $new_allocation;
		DB::table('taskmanager')->where('id',$task_id)->update($data);

		$to_details = DB::table('user')->where('user_id',$new_allocation)->first();
		$to = $to_details->lastname.' '.$to_details->firstname;

		$message = '<spam style="color:#006bc7">++++<strong>'.$from.'</strong> has allocated this task to <strong>'.$to.'</strong> on <strong>'.date('d-M-Y').'</strong>++++</spam>';

		if(Session::has('taskmanager_user'))
	    {
	    	$sess_user = Session::get('taskmanager_user');
	    	if($sess_user == $task_details->author)
	    	{
	    		$dataupdate_spec_status['author_spec_status'] = 0;
				$dataupdate_spec_status['allocated_spec_status'] = 1;
	    	}
	    	else{
	    		$dataupdate_spec_status['author_spec_status'] = 1;
				$dataupdate_spec_status['allocated_spec_status'] = 0;
	    	}
	    	DB::table('taskmanager')->where('id',$task_id)->update($dataupdate_spec_status);
	    }

		$data_specifics['task_id'] = $task_id;
		$data_specifics['message'] = $message;
		$data_specifics['to_user'] = $new_allocation;
		$data_specifics['allocated_date'] = date('Y-m-d H:i:s');
		$data_specifics['status'] = 2;

		DB::table('taskmanager_specifics')->insert($data_specifics);

		if($task_details->avoid_email == "0")
	    {
	    	$task_specifics_val = strip_tags($task_details->task_specifics);
	    	if($task_details->subject == "")
	    	{
	    		$subject_cls = substr($task_specifics_val,0,30);
	    	}
	    	else{
	    		$subject_cls = $task_details->subject;
	    	}
	    	$allocated_person = DB::table('user')->where('user_id',Session::get('taskmanager_user'))->first();

	    	$author_details = DB::table('user')->where('user_id',$task_details->author)->first();
	    	$author_email = $author_details->email;
    		
    		$allocated_details = DB::table('user')->where('user_id',$new_allocation)->first();
    		$dataemail['allocated_name'] = $allocated_details->lastname.' '.$allocated_details->firstname;
    		$allocated_email = $allocated_details->email;


    		$task_specifics = '';
			$specifics_first = DB::table('taskmanager_specifics')->where('task_id',$task_id)->orderBy('id','asc')->first();
			if(count($specifics_first))
			{
				$task_specifics.=$specifics_first->message;
			}
			$task_specifics.= PHP_EOL.$task_details->task_specifics;
			$specifics = DB::table('taskmanager_specifics')->where('task_id',$task_id)->where('id','!=',$specifics_first->id)->get();
			if(count($specifics))
			{
				foreach($specifics as $specific)
				{
					$task_specifics.=PHP_EOL.$specific->message;
				}
			}

			$task_specifics = strip_tags($task_specifics);

			$uploads = 'papers/task_specifics.txt';
			$myfile = fopen($uploads, "w") or die("Unable to open file!");
			fwrite($myfile, $task_specifics);
			fclose($myfile);
	    	
	    	$dataemail['logo'] = URL::to('assets/images/easy_payroll_logo.png');
			$dataemail['subject'] = $subject_cls;
			$dataemail['author_name'] = $author_details->lastname.' '.$author_details->firstname;
			$dataemail['due_date'] = $task_details->due_date;
			$dataemail['allocated_person'] = $allocated_person->lastname.' '.$allocated_person->firstname;
			$dataemail['allocation_date'] = date('d-M-Y');
			
			$subject_email = 'Task Manager: A Task has been allocated to you: '.$subject_cls;
			$contentmessage = view('emails/task_manager/task_manager_allocation_change', $dataemail)->render();

			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);

			$email = new PHPMailer();
			$email->SetFrom('info@gbsco.ie');
			$email->Subject   = $subject_email;
			$email->Body      = $contentmessage;
			$email->AddCC('tasks@gbsco.ie');
			$email->IsHTML(true);
			$email->AddAddress( $allocated_email );
			$email->AddAttachment( $uploads , 'task_specifics.txt' );
			$email->Send();		
	    }
        $task_specifics_val = strip_tags($task_details->task_specifics);
        if($task_details->subject == "") { $subject = substr($task_specifics_val,0,30); }
        else{ $subject = $task_details->subject; }

		$pval = '<p data-element="'.$task_details->id.'" data-subject="'.$subject.'" data-author="'.$task_details->author.'" data-allocated="'.$task_details->allocated_to.'"  class="edit_allocate_user edit_allocate_user_'.$task_details->id.'" title="Allocate User">'.$from.'->'.$to.'('.date('d-M-Y H:i').')</p>';
		$trval = '<tr><td colspan="2">'.$from.'->'.$to.'('.date('d-M-Y H:i').')</td></tr>';
		echo json_encode(array("pval" => $pval, 'trval' => $trval,'to' => $to));
	}
	public function show_existing_comments()
	{
		$task_id = Input::get('task_id');
		$task_details = DB::table('taskmanager')->where('id',$task_id)->first();
		$output = '';
		$specifics_first = DB::table('taskmanager_specifics')->where('task_id',$task_id)->orderBy('id','asc')->first();
		if(count($specifics_first))
		{
			$output.='<strong style="width:100%;float:left;text-align:justify;font-weight:400;margin-bottom:20px">'.$specifics_first->message.'</strong>';
		}
		$specficsval1 = str_replace("<p>&nbsp;</p>", "", $task_details->task_specifics);
		$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
		$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
		$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
		$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
		$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
		$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
		$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
		$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
		$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);
		$specficsval1 = str_replace("<p>&nbsp;</p>", "", $specficsval1);

		$specficsval1 = str_replace("<p></p>", "", $specficsval1);
		$specficsval1 = str_replace("<p></p>", "", $specficsval1);
		$specficsval1 = str_replace("<p></p>", "", $specficsval1);
		$specficsval1 = str_replace("<p></p>", "", $specficsval1);
		$specficsval1 = str_replace("<p></p>", "", $specficsval1);
		$specficsval1 = str_replace("<p></p>", "", $specficsval1);
		$specficsval1 = str_replace("<p></p>", "", $specficsval1);
		$specficsval1 = str_replace("<p></p>", "", $specficsval1);
		$specficsval1 = str_replace("<p></p>", "", $specficsval1);
		$specficsval1 = str_replace("<p></p>", "", $specficsval1);
		$specficsval1 = str_replace("<p></p>", "", $specficsval1);

		$output.= '<strong style="width:100%;float:left;text-align:justify;font-weight:400;margin-bottom:20px">'.$specficsval1.'</strong>';
		$specifics = DB::table('taskmanager_specifics')->where('task_id',$task_id)->where('id','!=',$specifics_first->id)->get();
		if(count($specifics))
		{
			foreach($specifics as $specific)
			{
				$specficsval = str_replace("<p>&nbsp;</p>", "", $specific->message);
				$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
				$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
				$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
				$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
				$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
				$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
				$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
				$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
				$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
				$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);

				$specficsval = str_replace("<p></p>", "", $specficsval);
				$specficsval = str_replace("<p></p>", "", $specficsval);
				$specficsval = str_replace("<p></p>", "", $specficsval);
				$specficsval = str_replace("<p></p>", "", $specficsval);
				$specficsval = str_replace("<p></p>", "", $specficsval);
				$specficsval = str_replace("<p></p>", "", $specficsval);
				$specficsval = str_replace("<p></p>", "", $specficsval);
				$specficsval = str_replace("<p></p>", "", $specficsval);
				$specficsval = str_replace("<p></p>", "", $specficsval);
				$specficsval = str_replace("<p></p>", "", $specficsval);
				$specficsval = str_replace("<p></p>", "", $specficsval);

				$output.='<strong style="width:100%;float:left;text-align:justify;font-weight:400;margin-bottom:20px">'.$specficsval.'</strong>';
			}
		}

		if(Session::has('taskmanager_user'))
		{
			$session_user = Session::get('taskmanager_user');
			if($task_details->author == $session_user)
			{
				$dataupdate_spec_status['author_spec_status'] = 0;
				if($task_details->author == $task_details->allocated_to)
				{
					$dataupdate_spec_status['allocated_spec_status'] = 0;
				}
			}
			else{
				$dataupdate_spec_status['allocated_spec_status'] = 0;
				if($task_details->author == $task_details->allocated_to)
				{
					$dataupdate_spec_status['author_spec_status'] = 0;
				}
			}
			DB::table('taskmanager')->where('id',$task_id)->update($dataupdate_spec_status);
		}
		echo $output;
	}
	public function add_comment_specifics()
	{
		$comment = Input::get('comments');
		$task_id = Input::get('task_id');

		$user = Session::get('taskmanager_user');
		$details = DB::table('user')->where('user_id',$user)->first();
		$username = $details->lastname.' '.$details->firstname;
		$message = '<spam style="color:#006bc7">####<strong>'.$username.'</strong> has added the following comment to this Task on <strong>'.date('d M Y').'</strong>####</spam> <br/><spam style="font-weight:400">'.$comment.'</spam>';

		$specficsval = str_replace("&amp;", "&", $message);

		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);

		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);

		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);

		$dataval['task_id'] = $task_id;
		$dataval['from_user'] = $user;
		$dataval['created_date'] = date('Y-m-d');
		$dataval['message'] = $specficsval;
		$dataval['status'] = 4;

		DB::table('taskmanager_specifics')->insert($dataval);

		if(Session::has('taskmanager_user'))
		{
			$sess_user = Session::get('taskmanager_user');
			$task_details = DB::table('taskmanager')->where('id',$task_id)->first();

			if($sess_user == $task_details->author)
			{
				$dataupdate_spec_status['allocated_spec_status'] = 1;
			}
			elseif($sess_user == $task_details->allocated_to)
			{
				$dataupdate_spec_status['author_spec_status'] = 1;
			}
			else{
				$dataupdate_spec_status['author_spec_status'] = 1;
				$dataupdate_spec_status['allocated_spec_status'] = 0;
			}

			DB::table('taskmanager')->where('id',$task_id)->update($dataupdate_spec_status);
		}
		echo '<strong style="width:100%;float:left;text-align:justify;font-weight:400;margin-bottom:20px">'.$specficsval.'</strong>';
	}
	public function add_comment_and_allocate()
	{
		$comment = Input::get('comments');
		$task_id = Input::get('task_id');

		$user = Session::get('taskmanager_user');
		$details = DB::table('user')->where('user_id',$user)->first();
		$username = $details->lastname.' '.$details->firstname;
		$message = '<spam style="color:#006bc7">####<strong>'.$username.'</strong> has added the following comment to this Task on <strong>'.date('d M Y').'</strong>####</spam> <br/><spam style="font-weight:400">'.$comment.'</spam>';

		$specficsval = str_replace("&amp;", "&", $message);

		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);
		$specficsval = str_replace("&amp;", "&", $specficsval);

		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);
		$specficsval = str_replace("<p>&nbsp;</p>", "", $specficsval);

		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);
		$specficsval = str_replace("<p></p>", "", $specficsval);

		$dataval['task_id'] = $task_id;
		$dataval['from_user'] = $user;
		$dataval['created_date'] = date('Y-m-d');
		$dataval['message'] = $specficsval;
		$dataval['status'] = 4;

		DB::table('taskmanager_specifics')->insert($dataval);

		if(Session::has('taskmanager_user'))
		{
			$sess_user = Session::get('taskmanager_user');
			$task_details = DB::table('taskmanager')->where('id',$task_id)->first();

			if($sess_user == $task_details->author)
			{
				$dataupdate_spec_status['allocated_spec_status'] = 1;
			}
			elseif($sess_user == $task_details->allocated_to)
			{
				$dataupdate_spec_status['author_spec_status'] = 1;
			}
			else{
				$dataupdate_spec_status['author_spec_status'] = 1;
				$dataupdate_spec_status['allocated_spec_status'] = 0;
			}

			DB::table('taskmanager')->where('id',$task_id)->update($dataupdate_spec_status);
		}

		$allocations = DB::table('taskmanager_specifics')->where('task_id',$task_id)->where('to_user','!=','')->where('status','<',3)->limit(1)->orderBy('id','desc')->first();
		if(count($allocations))
		{
			$new_allocation = $allocations->from_user;
		}
		else{
			$new_allocation = '0';
		}
		echo $new_allocation;
	}
	public function download_pdf_specifics()
	{
		$task_id = Input::get('task_id');
		$task_details = DB::table('taskmanager')->where('id',$task_id)->first();
		$output = '';
		$specifics_first = DB::table('taskmanager_specifics')->where('task_id',$task_id)->orderBy('id','asc')->first();
		if(count($specifics_first))
		{
			$output.='<strong style="width:100%;float:left;text-align:justify;font-weight:400">'.$specifics_first->message.'</strong>';
		}
		$output.= $task_details->task_specifics.'<br/>';
		$specifics = DB::table('taskmanager_specifics')->where('task_id',$task_id)->where('id','!=',$specifics_first->id)->get();
		if(count($specifics))
		{
			foreach($specifics as $specific)
			{
				$output.='<strong style="width:100%;float:left;text-align:justify;font-weight:400">'.$specific->message.'</strong><br/>';
			}
		}

		$pdf = PDF::loadHTML($output);
	    $pdf->setPaper('A4', 'portrait');
	    $pdf->save('papers/task_specifics.pdf');
	    echo 'task_specifics.pdf';
	}
	public function show_all_allocations()
	{
		$task_id = Input::get('task_id');
		$task_details = DB::table('taskmanager')->where('id',$task_id)->first();
		$author = $task_details->author;
		$author_details = DB::table('user')->where('user_id',$author)->first();
		$output = '<table class="table">
		<thead>
		<tr>
			<td style="width:35%">Author</td>
			<td style="width:65%">'.$author_details->lastname.' '.$author_details->firstname.'</td>
		</tr>
		<tr>
			<td>Task Created On:</td>
			<td>'.date('d-M-Y', strtotime($task_details->creation_date)).'</td>
		</tr>
		<tr>
			<td>Task Subject:</td>
			<td>'.$task_details->subject.'</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2">Allocations History:</td>
		</tr>
		<thead>
		<tbody>';
        $allocations = DB::table('taskmanager_specifics')->where('task_id',$task_id)->where('to_user','!=','')->where('status','<',3)->orderBy('id','asc')->get();
        if(count($allocations))
        {
          foreach($allocations as $allocate)
          {
            $fromuser = DB::table('user')->where('user_id',$allocate->from_user)->first();
            $touser = DB::table('user')->where('user_id',$allocate->to_user)->first();
            if($allocate->status == "0")
            {
            	$date1=date_create($task_details->creation_date);
                $date2=date_create($allocate->allocated_date);
                $diff=date_diff($date1,$date2);
                $diffdays = $diff->format("%R%a");

            	$output.='<tr>
	            	<td colspan="2">'.$fromuser->lastname.' '.$fromuser->firstname.' -> Task Closed on '.date('d-M-Y H:i', strtotime($allocate->allocated_date)).'</td>
	            </tr>
	            <tr>
	            	<td colspan="2">This task was open for '.$diffdays.' days</td>
	            </tr>';
            }
            else{
            	$output.='<tr>
	            	<td colspan="2">'.$fromuser->lastname.' '.$fromuser->firstname.' -> '.$touser->lastname.' '.$touser->firstname.' ('.date('d-M-Y H:i', strtotime($allocate->allocated_date)).')</td>
	            </tr>';
            }
          }
        }
        $output.='</tbody></table>';
        echo $output;
	}
	public function download_pdf_history()
	{
		$task_id = Input::get('task_id');
		$task_details = DB::table('taskmanager')->where('id',$task_id)->first();
		$author = $task_details->author;
		$author_details = DB::table('user')->where('user_id',$author)->first();
		$output = '<table class="table">
		<tr>
			<td>Author</td>
			<td>'.$author_details->lastname.' '.$author_details->firstname.'</td>
		</tr>
		<tr>
			<td>Task Created On:</td>
			<td>'.date('d-M-Y', strtotime($task_details->creation_date)).'</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2">Allocations History:</td>
		</tr>';
        $allocations = DB::table('taskmanager_specifics')->where('task_id',$task_id)->where('to_user','!=','')->where('status','<',3)->limit(5)->orderBy('id','desc')->get();
        if(count($allocations))
        {
          foreach($allocations as $allocate)
          {
            $fromuser = DB::table('user')->where('user_id',$allocate->from_user)->first();
            $touser = DB::table('user')->where('user_id',$allocate->to_user)->first();
            $output.='<tr>
            	<td colspan="2">'.$fromuser->lastname.' '.$fromuser->firstname.' -> '.$touser->lastname.' '.$touser->firstname.' ('.date('d-M-Y H:i', strtotime($allocate->allocated_date)).')</td>
            </tr>';
          }
        }
        $output.='</table>';

		$pdf = PDF::loadHTML($output);
	    $pdf->setPaper('A4', 'portrait');
	    $pdf->save('papers/Task Allocation History.pdf');
	    echo 'Task Allocation History.pdf';
	}
	public function download_csv_history()
	{
		$task_id = Input::get('task_id');
		$task_details = DB::table('taskmanager')->where('id',$task_id)->first();
		$author = $task_details->author;
		$author_details = DB::table('user')->where('user_id',$author)->first();

		$file = fopen('papers/Task Allocation History.csv', 'w');
		$columns = array('TASK ALLOCATION HISTORY', '');
	    fputcsv($file, $columns);

	    $columns1 = array('', '');
	    fputcsv($file, $columns1);

	    $columns2 = array('Author', $author_details->lastname.' '.$author_details->firstname);
	    fputcsv($file, $columns2);

	    $columns3 = array('Task Created On:', date('d-M-Y', strtotime($task_details->creation_date)));
	    fputcsv($file, $columns3);

	    $columns4 = array('', '');
	    fputcsv($file, $columns4);

	    $columns5 = array('Allocations History:', '');
	    fputcsv($file, $columns5);

        $allocations = DB::table('taskmanager_specifics')->where('task_id',$task_id)->where('to_user','!=','')->where('status','<',3)->limit(5)->orderBy('id','desc')->get();
        if(count($allocations))
        {
          foreach($allocations as $allocate)
          {
            $fromuser = DB::table('user')->where('user_id',$allocate->from_user)->first();
            $touser = DB::table('user')->where('user_id',$allocate->to_user)->first();

            $columns6 = array($fromuser->lastname.' '.$fromuser->firstname.' -> '.$touser->lastname.' '.$touser->firstname.' ('.date('d-M-Y H:i', strtotime($allocate->allocated_date)).')','');
	    	fputcsv($file, $columns6);
          }
        }
        fclose($file);
   	 	echo 'Task Allocation History.csv';
	}
	public function copy_task_details()
	{
		$task_id = Input::get('task_id');
		$task_specifics = Input::get('task_specifics');
		$task_files = Input::get('task_files');

		$spec_output = '';
		$attach_output = '';
		$task_details = DB::table('taskmanager')->where('id',$task_id)->first();
		if($task_specifics == "1")
		{
			$specifics_first = DB::table('taskmanager_specifics')->where('task_id',$task_id)->orderBy('id','asc')->first();
			if(count($specifics_first))
			{
				$spec_output.='<strong style="width:100%;float:left;text-align:justify;font-weight:400">'.$specifics_first->message.'</strong><br/>';
			}
			$spec_output.= $task_details->task_specifics.'<br/>';
			$specifics = DB::table('taskmanager_specifics')->where('task_id',$task_id)->where('id','!=',$specifics_first->id)->get();
			if(count($specifics))
			{
				foreach($specifics as $specific)
				{
					$spec_output.='<strong style="width:100%;float:left;text-align:justify;font-weight:400">'.$specific->message.'</strong><br/>';
				}
			}
			$data['task_specifics_type'] = "1";
			$data['task_specifics'] = $spec_output;
		}
		else{
			$data['task_specifics_type'] = "2";
			$data['task_specifics'] = $task_details->task_specifics;
		}

		if($task_files == "1")
		{
			$files = DB::table('taskmanager_files')->where('task_id',$task_id)->where('status',0)->get();
			$notepad = DB::table('taskmanager_notepad')->where('task_id',$task_id)->where('status',0)->get();
			$infiles = DB::table('taskmanager_infiles')->where('task_id',$task_id)->where('status',0)->get();

			$attach_output = '<h5 style="margin-top:20px">Attachments from Copied Task</h5>';
			if(count($files))
			{
				foreach($files as $file)
				{
					$attach_output.='<input type="checkbox" name="copy_files[]" class="copy_files" id="file_'.$file->id.'" value="'.$file->id.'"><label for="file_'.$file->id.'">'.$file->filename.'</label><br/>';
				}
			}
			if(count($notepad))
			{
				foreach($notepad as $note)
				{
					$attach_output.='<input type="checkbox" name="copy_notes[]" class="copy_notes" id="note_'.$note->id.'" value="'.$note->id.'"><label for="note_'.$note->id.'">'.$note->filename.'</label><br/>';
				}
			}
			if(count($infiles))
			{
				$i = 1;
				$attach_output.='<p>Linked InFiles</p>';
				foreach($infiles as $infile)
				{
					$infile_details = DB::table('in_file')->where('id',$infile->infile_id)->first();

					$attach_output.='<input type="checkbox" name="copy_infiles" class="copy_infiles" id="infile_'.$infile->id.'" value="'.$infile->id.'"><label for="infile_'.$infile->id.'">'.$i.'&nbsp;&nbsp;'.date('d-M-Y', strtotime($infile_details->date_received)).'&nbsp;&nbsp;'.$infile_details->description.'</label><br/>';
					$i++;
				}
			}
			$data['task_attachment_type'] = "1";
			$data['attached_files'] = $attach_output;
		}
		else{
			$data['task_attachment_type'] = "2";
			$data['attached_files'] = '';
		}

		$task_details = DB::table('taskmanager')->where('id',$task_id)->first();
		if(count($task_details))
		{
			$task_specifics = DB::table('taskmanager_specifics')->where('task_id',$task_id)->where('status',"1")->orderBy('id','asc')->first();
			$data['creation_date'] = date('d-M-Y');
			$data['allocated_to'] = $task_specifics->to_user;
			$data['client_id'] = $task_details->client_id;
			if($task_details->client_id != "")
			{
				$client_details = DB::table('cm_clients')->where('client_id',$task_details->client_id)->first();
				$data['client_name'] = $client_details->company.' ('.$client_details->client_id.')';
			}
			else{
				$data['client_name'] = '';
			}
			$data['internal'] = $task_details->internal;
			$data['task_type'] = $task_details->task_type;
			$data['subject'] = $task_details->subject;
		}

		echo json_encode($data);
	}
	public function refresh_taskmanager()
	{
		$user_id = Input::get('user_id');
		$user_tasks = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND (`allocated_to` = '".$user_id."' OR `author` = '".$user_id."' OR `allocated_to` = '0')");
		$open_tasks = '';
		$layout = '';
		if(count($user_tasks))
	    {
	        foreach($user_tasks as $task)
	        {
	          if($task->status == 1){ $disabled = 'disabled'; $disabled_icon = 'disabled_icon'; }
	          else{ $disabled = ''; $disabled_icon = ''; }

	          $taskfiles = DB::table('taskmanager_files')->where('task_id',$task->id)->get();
	          $tasknotepad = DB::table('taskmanager_notepad')->where('task_id',$task->id)->get();
	          $taskinfiles = DB::table('taskmanager_infiles')->where('task_id',$task->id)->get();

	          if($task->client_id == "")
	          {
	            $title_lable = 'Task Name:';
	            $task_details = DB::table('time_task')->where('id', $task->task_type)->first();
	            if(count($task_details))
	            {
	            	$title = '<spam class="task_name_'.$task->id.'">'.$task_details->task_name.'</spam>';
	            }
	            else{
	            	$title = '<spam class="task_name_'.$task->id.'"></spam>';
	            }
	          }
	          else{
	            $title_lable = 'Client:';
	            $client_details = DB::table('cm_clients')->where('client_id', $task->client_id)->first();
	            if(count($client_details))
                {
                  $title = $client_details->company.' ('.$task->client_id.')';
                }
                else{
                  $title = '';
                }
	          }
	          $author = DB::table('user')->where('user_id',$task->author)->first();
	          $task_specifics_val = strip_tags($task->task_specifics);
	          if($task->subject == "") { $subject = substr($task_specifics_val,0,30); }
	          else{ $subject = $task->subject; }

	          if($task->allocated_to == 0) { $allocated_to = ''; }
	          else{ $allocated = DB::table('user')->where('user_id',$task->allocated_to)->first(); $allocated_to = $allocated->lastname.' '.$allocated->firstname; }
	          if(Session::has('taskmanager_user'))
              {
                if(Session::get('taskmanager_user') == $task->author) {
                    if(Session::get('taskmanager_user') == $task->allocated_to)
                    {
                      $author_cls = 'author_tr allocated_tr'; $hidden_author_cls = 'hidden_author_tr hidden_allocated_tr'; 
                    }
                    else{
                      $author_cls = 'author_tr'; $hidden_author_cls = 'hidden_author_tr'; 
                    }
                }
                else{ 
                    $author_cls = 'allocated_tr'; $hidden_author_cls = 'hidden_allocated_tr'; 
                }
              }
              else{
                $author_cls = '';
                $hidden_author_cls = '';
              }
	          $open_tasks.='<tr class="tasks_tr '.$author_cls.'" id="task_tr_'.$task->id.'">
	            <td style="vertical-align: baseline;background: #2fd9ff;width:35%;padding:0px">';
                  $statusi = 0;
                  if(Session::has('taskmanager_user'))
                  {
                    if(Session::get('taskmanager_user') == $task->author) { 
                      if($task->author_spec_status == "1")
                      {
                        $open_tasks.='<p class="redlight_indication redline_indication redlight_indication_'.$task->id.'" style="border: 4px solid #f00;margin-top:0px;background: #f00;"></p>';
                        $statusi++;
                      }
                    }
                    else{
                      if($task->allocated_spec_status == "1")
                      {
                        $open_tasks.='<p class="redlight_indication redline_indication redlight_indication_'.$task->id.'" style="border: 4px solid #f00;margin-top:0px;background: #f00;"></p>';
                        $statusi++;
                      }
                    }
                  }
                  if($statusi == 0)
                  {
                    $open_tasks.='<p class="redlight_indication redlight_indication_'.$task->id.'" style="border: 4px solid #f00;margin-top:0px;background: #f00;display:none"></p>';
                  }
	                $open_tasks.='<table class="table">
	                <tr>
	                  <td style="width:25%;background:#2fd9ff;font-weight:700;text-decoration: underline;">'.$title_lable.'</td>
	                  <td style="width:75%;background:#2fd9ff">'.$title.'';
	                  if($task->recurring_task > 0)
	                  {
	                    $open_tasks.='<img src="'.URL::to('assets/images/recurring.png').'" style="width:30px;" title="This is a Recurring Task">';
	                  }
	                  $open_tasks.='</td>
	                </tr>
	                <tr>
	                  <td style="background: #2fd9ff;font-weight:700;text-decoration: underline;">Subject:</td>
	                  <td style="background: #2fd9ff">'.$subject.'</td>
	                </tr>
	                <tr>';
	                    $date1=date_create(date('Y-m-d'));
	                    $date2=date_create($task->due_date);
	                    $diff=date_diff($date1,$date2);
	                    $diffdays = $diff->format("%R%a");

	                    if($diffdays == 0 || $diffdays== 1) { $due_color = '#e89701'; }
	                    elseif($diffdays < 0) { $due_color = '#f00'; }
	                    elseif($diffdays > 7) { $due_color = '#000'; }
	                    elseif($diffdays <= 7) { $due_color = '#00a91d'; }
	                    else{ $due_color = '#000'; }
	                  $open_tasks.='<td style="background: #2fd9ff;font-weight:700;text-decoration: underline;">Due Date:</td>
	                  <td style="background: #2fd9ff" class="'.$disabled_icon.'">
	                    <spam style="color:'.$due_color.' !important;font-weight:800" id="due_date_task_'.$task->id.'">'.date('d-M-Y', strtotime($task->due_date)).'</spam>
	                    <a href="javascript:" data-element="'.$task->id.'" data-subject="'.$subject.'" data-value="'.date('d-M-Y', strtotime($task->due_date)).'" data-duedate="'.$task->due_date.'" data-color="'.$due_color.'" class="fa fa-edit edit_due_date edit_due_date_'.$task->id.' '.$disabled.'" style="font-weight:800"></a>
	                  </td>
	                </tr>
	                <tr>
                        <td style="background: #2fd9ff;font-weight:700;text-decoration: underline;">Date Created:</td>
                        <td style="background: #2fd9ff">
                          <spam>'.date('d-M-Y', strtotime($task->creation_date)).'</spam>
                        </td>
                    </tr>
	                <tr>
	                  <td style="background: #2fd9ff;font-weight:700;text-decoration: underline;">Task Specifics:</td>
	                  <td style="background: #2fd9ff"><a href="javascript:" class="link_to_task_specifics" data-element="'.$task->id.'">'.substr($task_specifics_val,0,30).'...</a></td>
	                </tr>
	                <tr>
	                  <td style="background: #2fd9ff;font-weight:700;text-decoration: underline;">Task files:</td>
	                  <td style="background: #2fd9ff">';
	                    $fileoutput = '';
	                    if(count($taskfiles))
	                    {
	                      foreach($taskfiles as $file)
	                      {
	                        if($file->status == 0)
	                        {
	                          $fileoutput.='<p><a href="'.URL::to($file->url).'/'.$file->filename.'" class="file_attachments" download>'.$file->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_files?file_id='.$file->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
	                        }
	                      }
	                    }
	                    if(count($tasknotepad))
	                    {
	                      foreach($tasknotepad as $note)
	                      {
	                        if($note->status == 0)
	                        {
	                          $fileoutput.='<p><a href="'.URL::to($note->url).'/'.$note->filename.'" class="file_attachments" download>'.$note->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_notepad?file_id='.$note->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
	                        }
	                      }
	                    }
	                    if(count($taskinfiles))
	                    {
	                      $i=1;
	                      foreach($taskinfiles as $infile)
	                      {
	                        if($infile->status == 0)
	                        {
	                          if($i == 1) { $fileoutput.='Linked Infiles:<br/>'; }
	                          $file = DB::table('in_file')->where('id',$infile->infile_id)->first();
	                          $ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
	                          $fileoutput.='<p class="link_infile_p"><a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$i.'</a>
	                          <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.date('d-M-Y', strtotime($file->data_received)).'</a>
	                          <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$file->description.'</a>

	                          <a href="'.URL::to('user/delete_taskmanager_infiles?file_id='.$infile->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a>
	                          </p>';
	                          $i++;
	                        }
	                      }
	                    }
	                    $open_tasks.=$fileoutput;
	                  $open_tasks.='</td>
	                </tr>
	              </table>
	            </td>
	            <td style="vertical-align: baseline;background: #dcdcdc;width:30%">
	              <table class="table">
	                <tr>
	                  <td style="width:25%;font-weight:700;text-decoration: underline;">Author:</td>
	                  <td style="width:75%">'.$author->lastname.' '.$author->firstname.'';
                      if($task->avoid_email == 0) {
                        $open_tasks.='<a href="javascript:" class="fa fa-envelope avoid_email" data-element="'.$task->id.'" title="Avoid Emails for this task"></a>';
                      }
                      else{
                        $open_tasks.='<a href="javascript:" class="fa fa-envelope avoid_email retain_email" data-element="'.$task->id.'" title="Avoid Emails for this task"></a>';
                      }
	                  $open_tasks.='</td>
	                </tr>
	                <tr>
	                  <td style="font-weight:700;text-decoration: underline;">Allocated to:</td>
	                  <td id="allocated_to_name_'.$task->id.'">'.$allocated_to.'</td>
	                </tr>
	                <tr>
	                  <td colspan="2" class="'.$disabled_icon.'">
	                    <spam style="font-weight:700;text-decoration: underline;">Allocations:</spam>&nbsp;
	                    <a href="javascript:" data-element="'.$task->id.'" data-subject="'.$subject.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="fa fa-sitemap edit_allocate_user edit_allocate_user_'.$task->id.' '.$disabled.'" title="Allocate User" style="font-weight:800"></a>
	                    &nbsp;
	                    <a href="javascript:" data-element="'.$task->id.'" data-subject="'.$subject.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="fa fa-history show_task_allocation_history show_task_allocation_history_'.$task->id.'" title="Allocation history" style="font-weight:800"></a>
	                    &nbsp;
                        <a href="javascript:" data-element="'.$task->id.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="request_update request_update_'.$task->id.'" title="Request Update" '.$disabled.' style="font-weight:800">
                        	<img src="'.URL::to('assets/images/request.png').'" data-element="'.$task->id.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="request_update request_update_'.$task->id.'" '.$disabled.' style="width:16px;">
                        </a>
	                  </td>
	                </tr>
	                <tr>
	                  <td colspan="2" id="allocation_history_div_'.$task->id.'">';
	                    $allocations = DB::table('taskmanager_specifics')->where('task_id',$task->id)->where('to_user','!=','')->where('status','<',3)->limit(5)->orderBy('id','desc')->get();
	                    $output = '';
	                    if(count($allocations))
	                    {
	                      foreach($allocations as $allocate)
	                      {
	                        $output.='<p data-element="'.$task->id.'" data-subject="'.$subject.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="edit_allocate_user edit_allocate_user_'.$task->id.' '.$disabled.'" title="Allocate User">';
	                          $fromuser = DB::table('user')->where('user_id',$allocate->from_user)->first();
	                          $touser = DB::table('user')->where('user_id',$allocate->to_user)->first();
	                          $output.=$fromuser->lastname.' '.$fromuser->firstname.' -> '.$touser->lastname.' '.$touser->firstname.' ('.date('d-M-Y H:i', strtotime($allocate->allocated_date)).')';
	                        $output.='</p>';
	                      }
	                    }
	                    $open_tasks.=$output;
	                  $open_tasks.='</td>
	                </tr>
	              </table>
	            </td>
	            <td style="vertical-align: baseline;background: #dcdcdc;width:20%">
	              <table class="table">
	                <tr>
	                  <td style="font-weight:700;text-decoration: underline;">Progress Files:</td>
	                  <td></td>
	                </tr>
	                <tr>
	                  <td class="'.$disabled_icon.'">
	                    <a href="javascript:" class="fa fa-plus faplus_progress '.$disabled.'" data-element="'.$task->id.'" style="padding:5px;background: #dfdfdf;"></a>
	                    <a href="javascript:" class="fa fa-edit fanotepad_progress '.$disabled.'" style="padding:5px;background: #dfdfdf;"></a>';
	                    if($task->client_id != "")
	                    {
	                      $open_tasks.='<a href="javascript:" class="infiles_link_progress '.$disabled.'" data-element="'.$task->id.'">Infiles</a>';
	                    }
	                    $open_tasks.='<input type="hidden" name="hidden_progress_client_id" id="hidden_progress_client_id_'.$task->id.'" value="'.$task->client_id.'">
	                    <input type="hidden" name="hidden_infiles_progress_id" id="hidden_infiles_progress_id_'.$task->id.'" value="">
	                    
	                    <div class="notepad_div_progress_notes" style="z-index:9999; position:absolute">
	                      <textarea name="notepad_contents_progress" class="form-control notepad_contents_progress" placeholder="Enter Contents"></textarea>
	                      <input type="hidden" name="hidden_task_id_progress_notepad" id="hidden_task_id_progress_notepad" value="'.$task->id.'">
	                      <input type="button" name="notepad_progress_submit" class="btn btn-sm btn-primary notepad_progress_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
	                      <spam class="error_files_notepad"></spam>
	                    </div>
	                  </td>
	                  <td></td>
	                </tr>
	                <tr>
	                  <td colspan="2" class="'.$disabled_icon.'">';
	                    $fileoutput ='<div id="add_files_attachments_progress_div_'.$task->id.'">';
	                    if(count($taskfiles))
	                    {
	                      foreach($taskfiles as $file)
	                      {
	                        if($file->status == 1)
	                        {
	                          $fileoutput.='<p><a href="'.URL::to($file->url).'/'.$file->filename.'" class="file_attachments" download>'.$file->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_files?file_id='.$file->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
	                        }
	                      }
	                    }
	                    $fileoutput.='</div>';
	                    $fileoutput.='<div id="add_notepad_attachments_progress_div_'.$task->id.'">';
	                    if(count($tasknotepad))
	                    {
	                      foreach($tasknotepad as $note)
	                      {
	                        if($note->status == 1)
	                        {
	                          $fileoutput.='<p><a href="'.URL::to($note->url).'/'.$note->filename.'" class="file_attachments" download>'.$note->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_notepad?file_id='.$note->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
	                        }
	                      }
	                    }
	                    $fileoutput.='</div>';
	                    $fileoutput.='<div id="add_infiles_attachments_progress_div_'.$task->id.'">';
	                    if(count($taskinfiles))
	                    {
	                      $i=1;
	                        foreach($taskinfiles as $infile)
	                        {
	                          if($infile->status == 1)
	                          {
	                            if($i == 1) { $fileoutput.='Linked Infiles:<br/>'; }
	                            $file = DB::table('in_file')->where('id',$infile->infile_id)->first();
	                            $ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
	                            $fileoutput.='<p class="link_infile_p"><a href="javascript:" class="link_infile '.$disabled.'" data-element="'.$ele.'">'.$i.'</a>
	                            <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.date('d-M-Y', strtotime($file->data_received)).'</a>
	                            <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$file->description.'</a>

	                            <a href="'.URL::to('user/delete_taskmanager_infiles?file_id='.$infile->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a>
	                            </p>';
	                            $i++;
	                          }
	                        }
	                    }
	                    $fileoutput.='</div>';
	                    $open_tasks.= $fileoutput;
	                  $open_tasks.='</td>
	                </tr>
	              </table>
	            </td>
	            <td style="vertical-align: baseline;background: #2fd9ff;width:15%">
	              <table class="table">
	                <tr>
	                  <td style="background:#2fd9ff" class="'.$disabled_icon.'">
	                  <a href="javascript:" class="fa fa-file-pdf-o download_pdf_task" data-element="'.$task->id.'" title="Download PDF" style="padding:5px;font-size:20px;font-weight: 800">
                              </a>
	                  <a href="javascript:" class="fa fa-files-o copy_task" data-element="'.$task->id.'" title="Copy this Task" style="padding:5px;font-size:20px;font-weight: 800"></a></td>

	                </tr>
	                <tr>
	                  <td style="background:#2fd9ff"><spam style="font-weight:700;text-decoration: underline;">Task ID:</spam> '.$task->taskid.'</td>
	                </tr>
	                <tr>
	                  <td style="background:#2fd9ff">
	                    <spam style="font-weight:700;text-decoration: underline;">Progress:</spam> 
	                    <a href="javascript:" class="fa fa-sliders" data-placement="bottom" data-popover-content="#a1_'.$task->id.'" data-toggle="popover" data-trigger="click" tabindex="0" title="Set Progress" data-original-title="Set Progress"  style="padding:5px;font-weight:700"></a>

                            <!-- Content for Popover #1 -->
                            <div class="hidden" id="a1_'.$task->id.'">
                              <div class="popover-heading">
                                Set Progress Percentage
                              </div>
                              <div class="popover-body">
                                <input type="number" class="form-control input-sm progress_value" id="progress_value_'.$task->id.'" value="" style="width:60%;float:left">
                                <a href="javascript:" class="common_black_button set_progress" data-element="'.$task->id.'" style="font-size: 11px;line-height: 29px;">Set</a>
                              </div>
                            </div>

	                    <br/>
                            <div class="progress progress_'.$task->id.'" style="margin-top:20px">
                              <div class="progress-bar" role="progressbar" aria-valuenow="'.$task->progress.'" aria-valuemin="0" aria-valuemax="100" style="width:'.$task->progress.'%">
                                '.$task->progress.'%
                              </div>
                            </div>
	                  </td>
	                </tr>
	                <tr>
	                  <td style="background:#2fd9ff">';
	                    if($task->status == 1)
	                    {
	                      $open_tasks.='<a href="javascript:" class="common_black_button mark_as_incomplete" data-element="'.$task->id.'">Completed</a>';
	                    }
	                    elseif($task->status == 1)
	                    {
	                      if(Session::has('taskmanager_user'))
                            {
                                $allocated_person = Session::get('taskmanager_user');
                                if($task->author == $allocated_person)
                                {
                                  $complete_button = 'mark_as_complete_author';
                                }
                                else{
                                  $complete_button = 'mark_as_complete';
                                }
                            }
                            else{
                                $complete_button = 'mark_as_complete';
                            }
	                      $open_tasks.='<a href="javascript:" class="common_black_button '.$complete_button.'" data-element="'.$task->id.'">Mark Complete</a>
	                      <a href="javascript:" class="common_black_button activate_task_button" data-element="'.$task->id.'">Activate</a>';
	                    }
	                    else{
	                    	if(Session::has('taskmanager_user'))
                            {
                                $allocated_person = Session::get('taskmanager_user');
                                if($task->author == $allocated_person)
                                {
                                  $complete_button = 'mark_as_complete_author';
                                }
                                else{
                                  $complete_button = 'mark_as_complete';
                                }
                            }
                            else{
                                $complete_button = 'mark_as_complete';
                            }
	                      $open_tasks.='<a href="javascript:" class="common_black_button '.$complete_button.'" data-element="'.$task->id.'">Mark Complete</a>
	                      <a href="javascript:" class="common_black_button park_task_button" data-element="'.$task->id.'">Park Task</a>';
	                    }
	                  $open_tasks.='</td>
	                </tr>
	                <tr>
	                  <td style="background:#2fd9ff" class="'.$disabled_icon.'">
	                    <spam style="font-weight:700;text-decoration: underline;">Completion Files:</spam><br/>
	                    <a href="javascript:" class="fa fa-plus faplus_completion '.$disabled.'" data-element="'.$task->id.'" style="padding:5px"></a>
	                    <a href="javascript:" class="fa fa-plus faplus '.$disabled.'" style="padding:5px"></a>
	                    <a href="javascript:" class="fa fa-edit fanotepad_completion '.$disabled.'" style="padding:5px"></a>';
	                    if($task->client_id != "")
	                    {
	                      $open_tasks.='<a href="javascript:" class="infiles_link_completion '.$disabled.'" data-element="'.$task->id.'">Infiles</a>';
	                    }
	                    $open_tasks.='<input type="hidden" name="hidden_completion_client_id" id="hidden_completion_client_id_'.$task->id.'" value="'.$task->client_id.'">
	                    <input type="hidden" name="hidden_infiles_completion_id" id="hidden_infiles_completion_id_'.$task->id.'" value="">

	                    
	                    <div class="notepad_div_completion_notes" style="z-index:9999; position:absolute">
	                      <textarea name="notepad_contents_completion" class="form-control notepad_contents_completion" placeholder="Enter Contents"></textarea>
	                      <input type="hidden" name="hidden_task_id_completion_notepad" id="hidden_task_id_completion_notepad" value="'.$task->id.'">
	                      <input type="button" name="notepad_completion_submit" class="btn btn-sm btn-primary notepad_completion_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
	                      <spam class="error_files_notepad"></spam>
	                    </div>';
	                    $fileoutput ='<div id="add_files_attachments_completion_div_'.$task->id.'">';
	                    if(count($taskfiles))
	                    {
	                      foreach($taskfiles as $file)
	                      {
	                        if($file->status == 2)
	                        {
	                          $fileoutput.='<p><a href="'.URL::to($file->url).'/'.$file->filename.'" class="file_attachments" download>'.$file->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_files?file_id='.$file->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
	                        }
	                      }
	                    }
	                    $fileoutput.='</div>';
	                    $fileoutput.='<div id="add_notepad_attachments_completion_div_'.$task->id.'">';
	                    if(count($tasknotepad))
	                    {
	                      foreach($tasknotepad as $note)
	                      {
	                        if($note->status == 2)
	                        {
	                          $fileoutput.='<p><a href="'.URL::to($note->url).'/'.$note->filename.'" class="file_attachments" download>'.$note->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_notepad?file_id='.$note->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
	                        }
	                      }
	                    }
	                    $fileoutput.='</div>';
	                    $fileoutput.='<div id="add_infiles_attachments_completion_div_'.$task->id.'">';
	                    if(count($taskinfiles))
	                    {
	                      $i=1;
	                        foreach($taskinfiles as $infile)
	                        {
	                          if($infile->status == 2)
	                          {
	                            if($i == 1) { $fileoutput.='Linked Infiles:<br/>'; }
	                            $file = DB::table('in_file')->where('id',$infile->infile_id)->first();
	                            $ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
	                            $fileoutput.='<p class="link_infile_p"><a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$i.'</a>
	                            <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.date('d-M-Y', strtotime($file->data_received)).'</a>
	                            <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$file->description.'</a>

	                            <a href="'.URL::to('user/delete_taskmanager_infiles?file_id='.$infile->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a>
	                            </p>';
	                            $i++;
	                          }
	                        }
	                    }
	                    $fileoutput.='</div>';
	                    $open_tasks.= $fileoutput;
	                  $open_tasks.='</td>
	                </tr>
	              </table>
	            </td>
	          </tr>
	          <tr class="empty_tr" style="background: #fff;height:30px">
	            <td style="padding:0px;background: #fff;"></td>
                <td colspan="3" style="background: #fff;height:30px"></td>
	          </tr>';

	          $layout.= '<tr class="hidden_tasks_tr '.$hidden_author_cls.'" id="hidden_tasks_tr_'.$task->id.'" data-element="'.$task->id.'" style="display:none">
                    <td style="background: #dcdcdc;padding:0px;">
                    	<table style="width:100%">
	                    	<tr>';
	                    	$statusi = 0;
			                  if(Session::has('taskmanager_user'))
			                  {
			                    if(Session::get('taskmanager_user') == $task->author) { 
			                      if($task->author_spec_status == "1")
			                      {
			                        $redlight_indication_layout ='<spam class="fa fa-star redlight_indication_layout redline_indication_layout redlight_indication_layout_'.$task->id.'" style="color:#f00;font-weight:800"></spam>';
			                        $redlight_value = 1;
			                        $statusi++;
			                      }
			                    }
			                    else{
			                      if($task->allocated_spec_status == "1")
			                      {
			                        $redlight_indication_layout ='<spam class="fa fa-star redlight_indication_layout redline_indication_layout redlight_indication_layout_'.$task->id.'" style="color:#f00;font-weight:800"></spam>';
			                        $redlight_value = 1;
			                        $statusi++;
			                      }
			                    }
			                  }
			                  if($statusi == 0)
			                  {
			                    $redlight_indication_layout ='<spam class="fa fa-star redlight_indication_layout redlight_indication_layout_'.$task->id.'" style="color:#f00;font-weight:800;display:none"></spam>';
			                    $redlight_value = 0;
			                  }
	                    		$layout.= '
	                    		<td style="width:5%;padding:10px; font-size:14px; font-weight:800;" class="redlight_sort_val">
	                    		<spam class="hidden_redlight_value" style="display:none">'.$redlight_value.'</spam>
	                    		'.$redlight_indication_layout.'
	                    		</td>
	                    		<td style="width:45%;padding:10px; font-size:14px; font-weight:800;" class="taskname_sort_val">'.$title.'</td>
	                    		
	                    		<td style="width:50%;padding:10px; font-size:14px; font-weight:800" class="subject_sort_val">'.$subject.'</td>
	                    	</tr>
	                    </table>
                    </td>
                    <td style="background: #dcdcdc;padding:0px;">
                    	<table style="width:100%">
	                    	<tr>
	                    		<td style="width:50%;padding:10px; font-size:14px; font-weight:800;" class="author_sort_val">'.$author->lastname.' '.$author->firstname.'</td>
	                    		<td style="width:50%;padding:10px; font-size:14px; font-weight:800" class="allocated_sort_val">'.$allocated_to.'</td>
	                    	</tr>
                    	</table>
                    </td>
                    <td style="background: #dcdcdc;padding:0px;">
                    	<table style="width:100%">
	                    	<tr>
	                    		<td style="width:50%;padding:10px; font-size:14px; font-weight:800;" class="duedate_sort_val">
	                    			<spam class="hidden_due_date_layout" style="display:none">'.strtotime($task->due_date).'</spam>
	                    			<spam class="layout_due_date_task" style="color:'.$due_color.' !important;font-weight:800" id="layout_due_date_task_'.$task->id.'">'.date('d-M-Y', strtotime($task->due_date)).'</spam>
	                    		</td>
	                    		<td style="width:50%;padding:10px; font-size:14px; font-weight:800" class="createddate_sort_val">
	                    		<spam class="hidden_created_date_layout" style="display:none">'.strtotime($task->creation_date).'</spam>
	                    		'.date('d-M-Y', strtotime($task->creation_date)).'
	                    		</td>
	                    	</tr>
	                    </table>
                    </td>
                    <td style="background: #dcdcdc;padding:0px;">
                    	<table style="width:100%">
	                    	<tr>
	                    		<td style="width:40%;padding:10px; font-size:14px; font-weight:800;" class="taskid_sort_val">'.$task->taskid.'</td>
	                    		<td class="layout_progress_'.$task->id.'" class="progress_sort_val" style="width:40%;padding:10px; font-size:14px; font-weight:800;">'.$task->progress.'%</td>
	                    		<td style="width:20%;padding:10px; font-size:14px; font-weight:800">
	                    			<a href="javascript:" class="fa fa-file-pdf-o download_pdf_task" data-element="'.$task->id.'" title="Download PDF" style="padding:5px;font-size:20px;font-weight: 800">
                              		</a> 
	                    		</td>
	                    	</tr>
	                    </table>
                    </td>
                  </tr>';
	        }
	    }
	    else{
            $open_tasks.='<tr><td colspan="4" style="text-align: center;padding:20px">No Tasks Found</td></tr>';
            $layout.='<tr><td colspan="4" style="text-align: center;padding:20px">No Tasks Found</td></tr>';
        }

        $outputlayout =$layout;

        if(Session::has('taskmanager_user'))
		{
			$sess_userid = Session::get('taskmanager_user');
			if($sess_userid == "")
			{
				$open_task_count = 0;
				$authored_task_count = 0;
			}
			else{
				$open_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND `author` != '".$sess_userid."' AND (`allocated_to` = '".$sess_userid."' OR `allocated_to` = '0')");
				$authored_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND `author` = '".$sess_userid."'");
			}
		}
		else{
			$open_task_count = 0;
			$authored_task_count = 0;
		}

		echo json_encode(array("open_tasks" => $open_tasks,"layout" => $outputlayout,"open_task_count" => count($open_task_count),"authored_task_count" => count($authored_task_count)));
	}
	public function refresh_parktask()
	{
		$user_id = Input::get('user_id');
		$user_tasks = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '2' AND (`allocated_to` = '".$user_id."' OR `author` = '".$user_id."' OR `allocated_to` = '0')");
		$open_tasks = '';
		$layout = '';
		if(count($user_tasks))
	    {
	        foreach($user_tasks as $task)
	        {
	          if($task->status == 1){ $disabled = 'disabled'; $disabled_icon = 'disabled_icon'; }
	          else{ $disabled = ''; $disabled_icon = ''; }

	          $taskfiles = DB::table('taskmanager_files')->where('task_id',$task->id)->get();
	          $tasknotepad = DB::table('taskmanager_notepad')->where('task_id',$task->id)->get();
	          $taskinfiles = DB::table('taskmanager_infiles')->where('task_id',$task->id)->get();

	          if($task->client_id == "")
	          {
	            $title_lable = 'Task Name:';
	            $task_details = DB::table('time_task')->where('id', $task->task_type)->first();
	            if(count($task_details))
	            {
	            	$title = '<spam class="task_name_'.$task->id.'">'.$task_details->task_name.'</spam>';
	            }
	            else{
	            	$title = '<spam class="task_name_'.$task->id.'"></spam>';
	            }
	          }
	          else{
	            $title_lable = 'Client:';
	            $client_details = DB::table('cm_clients')->where('client_id', $task->client_id)->first();
	            if(count($client_details))
                {
                  $title = $client_details->company.' ('.$task->client_id.')';
                }
                else{
                  $title = '';
                }
	          }
	          $author = DB::table('user')->where('user_id',$task->author)->first();
	          $task_specifics_val = strip_tags($task->task_specifics);
	          if($task->subject == "") { $subject = substr($task_specifics_val,0,30); }
	          else{ $subject = $task->subject; }

	          if($task->allocated_to == 0) { $allocated_to = ''; }
	          else{ $allocated = DB::table('user')->where('user_id',$task->allocated_to)->first(); $allocated_to = $allocated->lastname.' '.$allocated->firstname; }
	          if(Session::has('taskmanager_user'))
              {
                $author_cls = 'allocated_tr'; $hidden_author_cls = 'hidden_allocated_tr';
              }
              else{
                $author_cls = '';
                $hidden_author_cls = '';
              }
	          $open_tasks.='<tr class="tasks_tr '.$author_cls.'" id="task_tr_'.$task->id.'">
	            <td style="vertical-align: baseline;background: #2fd9ff;width:35%;padding:0px">';
                  $statusi = 0;
                  if(Session::has('taskmanager_user'))
                  {
                    if(Session::get('taskmanager_user') == $task->author) { 
                      if($task->author_spec_status == "1")
                      {
                        $open_tasks.='<p class="redlight_indication redline_indication redlight_indication_'.$task->id.'" style="border: 4px solid #f00;margin-top:0px;background: #f00;"></p>';
                        $statusi++;
                      }
                    }
                    else{
                      if($task->allocated_spec_status == "1")
                      {
                        $open_tasks.='<p class="redlight_indication redline_indication redlight_indication_'.$task->id.'" style="border: 4px solid #f00;margin-top:0px;background: #f00;"></p>';
                        $statusi++;
                      }
                    }
                  }
                  if($statusi == 0)
                  {
                    $open_tasks.='<p class="redlight_indication redlight_indication_'.$task->id.'" style="border: 4px solid #f00;margin-top:0px;background: #f00;display:none"></p>';
                  }
	                $open_tasks.='<table class="table">
	                <tr>
	                  <td style="width:25%;background:#2fd9ff;font-weight:700;text-decoration: underline;">'.$title_lable.'</td>
	                  <td style="width:75%;background:#2fd9ff">'.$title.'';
	                  if($task->recurring_task > 0)
	                  {
	                    $open_tasks.='<img src="'.URL::to('assets/images/recurring.png').'" style="width:30px;" title="This is a Recurring Task">';
	                  }
	                  $open_tasks.='</td>
	                </tr>
	                <tr>
	                  <td style="background: #2fd9ff;font-weight:700;text-decoration: underline;">Subject:</td>
	                  <td style="background: #2fd9ff">'.$subject.'</td>
	                </tr>
	                <tr>';
	                    $date1=date_create(date('Y-m-d'));
	                    $date2=date_create($task->due_date);
	                    $diff=date_diff($date1,$date2);
	                    $diffdays = $diff->format("%R%a");

	                    if($diffdays == 0 || $diffdays== 1) { $due_color = '#e89701'; }
	                    elseif($diffdays < 0) { $due_color = '#f00'; }
	                    elseif($diffdays > 7) { $due_color = '#000'; }
	                    elseif($diffdays <= 7) { $due_color = '#00a91d'; }
	                    else{ $due_color = '#000'; }
	                  $open_tasks.='<td style="background: #2fd9ff;font-weight:700;text-decoration: underline;">Due Date:</td>
	                  <td style="background: #2fd9ff" class="'.$disabled_icon.'">
	                    <spam style="color:'.$due_color.' !important;font-weight:800" id="due_date_task_'.$task->id.'">'.date('d-M-Y', strtotime($task->due_date)).'</spam>
	                    <a href="javascript:" data-element="'.$task->id.'" data-subject="'.$subject.'" data-value="'.date('d-M-Y', strtotime($task->due_date)).'" data-duedate="'.$task->due_date.'" data-color="'.$due_color.'" class="fa fa-edit edit_due_date edit_due_date_'.$task->id.' '.$disabled.'" style="font-weight:800"></a>
	                  </td>
	                </tr>
	                <tr>
                        <td style="background: #2fd9ff;font-weight:700;text-decoration: underline;">Date Created:</td>
                        <td style="background: #2fd9ff">
                          <spam>'.date('d-M-Y', strtotime($task->creation_date)).'</spam>
                        </td>
                    </tr>
	                <tr>
	                  <td style="background: #2fd9ff;font-weight:700;text-decoration: underline;">Task Specifics:</td>
	                  <td style="background: #2fd9ff"><a href="javascript:" class="link_to_task_specifics" data-element="'.$task->id.'">'.substr($task_specifics_val,0,30).'...</a></td>
	                </tr>
	                <tr>
	                  <td style="background: #2fd9ff;font-weight:700;text-decoration: underline;">Task files:</td>
	                  <td style="background: #2fd9ff">';
	                    $fileoutput = '';
	                    if(count($taskfiles))
	                    {
	                      foreach($taskfiles as $file)
	                      {
	                        if($file->status == 0)
	                        {
	                          $fileoutput.='<p><a href="'.URL::to($file->url).'/'.$file->filename.'" class="file_attachments" download>'.$file->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_files?file_id='.$file->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
	                        }
	                      }
	                    }
	                    if(count($tasknotepad))
	                    {
	                      foreach($tasknotepad as $note)
	                      {
	                        if($note->status == 0)
	                        {
	                          $fileoutput.='<p><a href="'.URL::to($note->url).'/'.$note->filename.'" class="file_attachments" download>'.$note->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_notepad?file_id='.$note->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
	                        }
	                      }
	                    }
	                    if(count($taskinfiles))
	                    {
	                      $i=1;
	                      foreach($taskinfiles as $infile)
	                      {
	                        if($infile->status == 0)
	                        {
	                          if($i == 1) { $fileoutput.='Linked Infiles:<br/>'; }
	                          $file = DB::table('in_file')->where('id',$infile->infile_id)->first();
	                          $ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
	                          $fileoutput.='<p class="link_infile_p"><a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$i.'</a>
	                          <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.date('d-M-Y', strtotime($file->data_received)).'</a>
	                          <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$file->description.'</a>

	                          <a href="'.URL::to('user/delete_taskmanager_infiles?file_id='.$infile->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a>
	                          </p>';
	                          $i++;
	                        }
	                      }
	                    }
	                    $open_tasks.=$fileoutput;
	                  $open_tasks.='</td>
	                </tr>
	              </table>
	            </td>
	            <td style="vertical-align: baseline;background: #dcdcdc;width:30%">
	              <table class="table">
	                <tr>
	                  <td style="width:25%;font-weight:700;text-decoration: underline;">Author:</td>
	                  <td style="width:75%">'.$author->lastname.' '.$author->firstname.'';
                      if($task->avoid_email == 0) {
                        $open_tasks.='<a href="javascript:" class="fa fa-envelope avoid_email" data-element="'.$task->id.'" title="Avoid Emails for this task"></a>';
                      }
                      else{
                        $open_tasks.='<a href="javascript:" class="fa fa-envelope avoid_email retain_email" data-element="'.$task->id.'" title="Avoid Emails for this task"></a>';
                      }
	                  $open_tasks.='</td>
	                </tr>
	                <tr>
	                  <td style="font-weight:700;text-decoration: underline;">Allocated to:</td>
	                  <td id="allocated_to_name_'.$task->id.'">'.$allocated_to.'</td>
	                </tr>
	                <tr>
	                  <td colspan="2" class="'.$disabled_icon.'">
	                    <spam style="font-weight:700;text-decoration: underline;">Allocations:</spam>&nbsp;
	                    <a href="javascript:" data-element="'.$task->id.'" data-subject="'.$subject.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="fa fa-sitemap edit_allocate_user edit_allocate_user_'.$task->id.' '.$disabled.'" title="Allocate User" style="font-weight:800"></a>
	                    &nbsp;
	                    <a href="javascript:" data-element="'.$task->id.'" data-subject="'.$subject.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="fa fa-history show_task_allocation_history show_task_allocation_history_'.$task->id.'" title="Allocation history" style="font-weight:800"></a>
	                    &nbsp;
                        <a href="javascript:" data-element="'.$task->id.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="request_update request_update_'.$task->id.'" title="Request Update" '.$disabled.' style="font-weight:800">
                        	<img src="'.URL::to('assets/images/request.png').'" data-element="'.$task->id.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="request_update request_update_'.$task->id.'" '.$disabled.' style="width:16px;">
                        </a>
	                  </td>
	                </tr>
	                <tr>
	                  <td colspan="2" id="allocation_history_div_'.$task->id.'">';
	                    $allocations = DB::table('taskmanager_specifics')->where('task_id',$task->id)->where('to_user','!=','')->where('status','<',3)->limit(5)->orderBy('id','desc')->get();
	                    $output = '';
	                    if(count($allocations))
	                    {
	                      foreach($allocations as $allocate)
	                      {
	                        $output.='<p data-element="'.$task->id.'" data-subject="'.$subject.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="edit_allocate_user edit_allocate_user_'.$task->id.' '.$disabled.'" title="Allocate User">';
	                          $fromuser = DB::table('user')->where('user_id',$allocate->from_user)->first();
	                          $touser = DB::table('user')->where('user_id',$allocate->to_user)->first();
	                          $output.=$fromuser->lastname.' '.$fromuser->firstname.' -> '.$touser->lastname.' '.$touser->firstname.' ('.date('d-M-Y H:i', strtotime($allocate->allocated_date)).')';
	                        $output.='</p>';
	                      }
	                    }
	                    $open_tasks.=$output;
	                  $open_tasks.='</td>
	                </tr>
	              </table>
	            </td>
	            <td style="vertical-align: baseline;background: #dcdcdc;width:20%">
	              <table class="table">
	                <tr>
	                  <td style="font-weight:700;text-decoration: underline;">Progress Files:</td>
	                  <td></td>
	                </tr>
	                <tr>
	                  <td class="'.$disabled_icon.'">
	                    <a href="javascript:" class="fa fa-plus faplus_progress '.$disabled.'" data-element="'.$task->id.'" style="padding:5px;background: #dfdfdf;"></a>
	                    <a href="javascript:" class="fa fa-edit fanotepad_progress '.$disabled.'" style="padding:5px;background: #dfdfdf;"></a>';
	                    if($task->client_id != "")
	                    {
	                      $open_tasks.='<a href="javascript:" class="infiles_link_progress '.$disabled.'" data-element="'.$task->id.'">Infiles</a>';
	                    }
	                    $open_tasks.='<input type="hidden" name="hidden_progress_client_id" id="hidden_progress_client_id_'.$task->id.'" value="'.$task->client_id.'">
	                    <input type="hidden" name="hidden_infiles_progress_id" id="hidden_infiles_progress_id_'.$task->id.'" value="">
	                    
	                    <div class="notepad_div_progress_notes" style="z-index:9999; position:absolute">
	                      <textarea name="notepad_contents_progress" class="form-control notepad_contents_progress" placeholder="Enter Contents"></textarea>
	                      <input type="hidden" name="hidden_task_id_progress_notepad" id="hidden_task_id_progress_notepad" value="'.$task->id.'">
	                      <input type="button" name="notepad_progress_submit" class="btn btn-sm btn-primary notepad_progress_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
	                      <spam class="error_files_notepad"></spam>
	                    </div>
	                  </td>
	                  <td></td>
	                </tr>
	                <tr>
	                  <td colspan="2" class="'.$disabled_icon.'">';
	                    $fileoutput ='<div id="add_files_attachments_progress_div_'.$task->id.'">';
	                    if(count($taskfiles))
	                    {
	                      foreach($taskfiles as $file)
	                      {
	                        if($file->status == 1)
	                        {
	                          $fileoutput.='<p><a href="'.URL::to($file->url).'/'.$file->filename.'" class="file_attachments" download>'.$file->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_files?file_id='.$file->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
	                        }
	                      }
	                    }
	                    $fileoutput.='</div>';
	                    $fileoutput.='<div id="add_notepad_attachments_progress_div_'.$task->id.'">';
	                    if(count($tasknotepad))
	                    {
	                      foreach($tasknotepad as $note)
	                      {
	                        if($note->status == 1)
	                        {
	                          $fileoutput.='<p><a href="'.URL::to($note->url).'/'.$note->filename.'" class="file_attachments" download>'.$note->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_notepad?file_id='.$note->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
	                        }
	                      }
	                    }
	                    $fileoutput.='</div>';
	                    $fileoutput.='<div id="add_infiles_attachments_progress_div_'.$task->id.'">';
	                    if(count($taskinfiles))
	                    {
	                      $i=1;
	                        foreach($taskinfiles as $infile)
	                        {
	                          if($infile->status == 1)
	                          {
	                            if($i == 1) { $fileoutput.='Linked Infiles:<br/>'; }
	                            $file = DB::table('in_file')->where('id',$infile->infile_id)->first();
	                            $ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
	                            $fileoutput.='<p class="link_infile_p"><a href="javascript:" class="link_infile '.$disabled.'" data-element="'.$ele.'">'.$i.'</a>
	                            <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.date('d-M-Y', strtotime($file->data_received)).'</a>
	                            <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$file->description.'</a>

	                            <a href="'.URL::to('user/delete_taskmanager_infiles?file_id='.$infile->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a>
	                            </p>';
	                            $i++;
	                          }
	                        }
	                    }
	                    $fileoutput.='</div>';
	                    $open_tasks.= $fileoutput;
	                  $open_tasks.='</td>
	                </tr>
	              </table>
	            </td>
	            <td style="vertical-align: baseline;background: #2fd9ff;width:15%">
	              <table class="table">
	                <tr>
	                  <td style="background:#2fd9ff" class="'.$disabled_icon.'">
	                  <a href="javascript:" class="fa fa-file-pdf-o download_pdf_task" data-element="'.$task->id.'" title="Download PDF" style="padding:5px;font-size:20px;font-weight: 800">
                              </a>
	                  <a href="javascript:" class="fa fa-files-o copy_task" data-element="'.$task->id.'" title="Copy this Task" style="padding:5px;font-size:20px;font-weight: 800"></a></td>

	                </tr>
	                <tr>
	                  <td style="background:#2fd9ff"><spam style="font-weight:700;text-decoration: underline;">Task ID:</spam> '.$task->taskid.'</td>
	                </tr>
	                <tr>
	                  <td style="background:#2fd9ff">
	                    <spam style="font-weight:700;text-decoration: underline;">Progress:</spam> 
	                    <a href="javascript:" class="fa fa-sliders" data-placement="bottom" data-popover-content="#a1_'.$task->id.'" data-toggle="popover" data-trigger="click" tabindex="0" title="Set Progress" data-original-title="Set Progress"  style="padding:5px;font-weight:700"></a>

                            <!-- Content for Popover #1 -->
                            <div class="hidden" id="a1_'.$task->id.'">
                              <div class="popover-heading">
                                Set Progress Percentage
                              </div>
                              <div class="popover-body">
                                <input type="number" class="form-control input-sm progress_value" id="progress_value_'.$task->id.'" value="" style="width:60%;float:left">
                                <a href="javascript:" class="common_black_button set_progress" data-element="'.$task->id.'" style="font-size: 11px;line-height: 29px;">Set</a>
                              </div>
                            </div>

	                    <br/>
                            <div class="progress progress_'.$task->id.'" style="margin-top:20px">
                              <div class="progress-bar" role="progressbar" aria-valuenow="'.$task->progress.'" aria-valuemin="0" aria-valuemax="100" style="width:'.$task->progress.'%">
                                '.$task->progress.'%
                              </div>
                            </div>
	                  </td>
	                </tr>
	                <tr>
	                  <td style="background:#2fd9ff">';
	                    if($task->status == 1)
	                    {
	                      $open_tasks.='<a href="javascript:" class="common_black_button mark_as_incomplete" data-element="'.$task->id.'">Completed</a>';
	                    }
	                    elseif($task->status == 1)
	                    {
	                      if(Session::has('taskmanager_user'))
                            {
                                $allocated_person = Session::get('taskmanager_user');
                                if($task->author == $allocated_person)
                                {
                                  $complete_button = 'mark_as_complete_author';
                                }
                                else{
                                  $complete_button = 'mark_as_complete';
                                }
                            }
                            else{
                                $complete_button = 'mark_as_complete';
                            }
	                      $open_tasks.='<a href="javascript:" class="common_black_button '.$complete_button.'" data-element="'.$task->id.'">Mark Complete</a>
	                      <a href="javascript:" class="common_black_button activate_task_button" data-element="'.$task->id.'">Activate</a>';
	                    }
	                    else{
	                    	if(Session::has('taskmanager_user'))
                            {
                                $allocated_person = Session::get('taskmanager_user');
                                if($task->author == $allocated_person)
                                {
                                  $complete_button = 'mark_as_complete_author';
                                }
                                else{
                                  $complete_button = 'mark_as_complete';
                                }
                            }
                            else{
                                $complete_button = 'mark_as_complete';
                            }
	                      $open_tasks.='<a href="javascript:" class="common_black_button '.$complete_button.'" data-element="'.$task->id.'">Mark Complete</a>
	                      <a href="javascript:" class="common_black_button park_task_button" data-element="'.$task->id.'">Park Task</a>';
	                    }
	                  $open_tasks.='</td>
	                </tr>
	                <tr>
	                  <td style="background:#2fd9ff" class="'.$disabled_icon.'">
	                    <spam style="font-weight:700;text-decoration: underline;">Completion Files:</spam><br/>
	                    <a href="javascript:" class="fa fa-plus faplus_completion '.$disabled.'" data-element="'.$task->id.'" style="padding:5px"></a>
	                    <a href="javascript:" class="fa fa-plus faplus '.$disabled.'" style="padding:5px"></a>
	                    <a href="javascript:" class="fa fa-edit fanotepad_completion '.$disabled.'" style="padding:5px"></a>';
	                    if($task->client_id != "")
	                    {
	                      $open_tasks.='<a href="javascript:" class="infiles_link_completion '.$disabled.'" data-element="'.$task->id.'">Infiles</a>';
	                    }
	                    $open_tasks.='<input type="hidden" name="hidden_completion_client_id" id="hidden_completion_client_id_'.$task->id.'" value="'.$task->client_id.'">
	                    <input type="hidden" name="hidden_infiles_completion_id" id="hidden_infiles_completion_id_'.$task->id.'" value="">

	                    
	                    <div class="notepad_div_completion_notes" style="z-index:9999; position:absolute">
	                      <textarea name="notepad_contents_completion" class="form-control notepad_contents_completion" placeholder="Enter Contents"></textarea>
	                      <input type="hidden" name="hidden_task_id_completion_notepad" id="hidden_task_id_completion_notepad" value="'.$task->id.'">
	                      <input type="button" name="notepad_completion_submit" class="btn btn-sm btn-primary notepad_completion_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
	                      <spam class="error_files_notepad"></spam>
	                    </div>';
	                    $fileoutput ='<div id="add_files_attachments_completion_div_'.$task->id.'">';
	                    if(count($taskfiles))
	                    {
	                      foreach($taskfiles as $file)
	                      {
	                        if($file->status == 2)
	                        {
	                          $fileoutput.='<p><a href="'.URL::to($file->url).'/'.$file->filename.'" class="file_attachments" download>'.$file->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_files?file_id='.$file->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
	                        }
	                      }
	                    }
	                    $fileoutput.='</div>';
	                    $fileoutput.='<div id="add_notepad_attachments_completion_div_'.$task->id.'">';
	                    if(count($tasknotepad))
	                    {
	                      foreach($tasknotepad as $note)
	                      {
	                        if($note->status == 2)
	                        {
	                          $fileoutput.='<p><a href="'.URL::to($note->url).'/'.$note->filename.'" class="file_attachments" download>'.$note->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_notepad?file_id='.$note->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
	                        }
	                      }
	                    }
	                    $fileoutput.='</div>';
	                    $fileoutput.='<div id="add_infiles_attachments_completion_div_'.$task->id.'">';
	                    if(count($taskinfiles))
	                    {
	                      $i=1;
	                        foreach($taskinfiles as $infile)
	                        {
	                          if($infile->status == 2)
	                          {
	                            if($i == 1) { $fileoutput.='Linked Infiles:<br/>'; }
	                            $file = DB::table('in_file')->where('id',$infile->infile_id)->first();
	                            $ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
	                            $fileoutput.='<p class="link_infile_p"><a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$i.'</a>
	                            <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.date('d-M-Y', strtotime($file->data_received)).'</a>
	                            <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$file->description.'</a>

	                            <a href="'.URL::to('user/delete_taskmanager_infiles?file_id='.$infile->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a>
	                            </p>';
	                            $i++;
	                          }
	                        }
	                    }
	                    $fileoutput.='</div>';
	                    $open_tasks.= $fileoutput;
	                  $open_tasks.='</td>
	                </tr>
	              </table>
	            </td>
	          </tr>
	          <tr class="empty_tr" style="background: #fff;height:30px">
	            <td style="padding:0px;background: #fff;"></td>
                <td colspan="3" style="background: #fff;height:30px"></td>
	          </tr>';

	          $layout.= '<tr class="hidden_tasks_tr '.$hidden_author_cls.'" id="hidden_tasks_tr_'.$task->id.'" data-element="'.$task->id.'" style="display:none">
                    <td style="background: #dcdcdc;padding:0px;">
                    	<table style="width:100%">
	                    	<tr>';
	                    	$statusi = 0;
			                  if(Session::has('taskmanager_user'))
			                  {
			                    if(Session::get('taskmanager_user') == $task->author) { 
			                      if($task->author_spec_status == "1")
			                      {
			                        $redlight_indication_layout ='<spam class="fa fa-star redlight_indication_layout redline_indication_layout redlight_indication_layout_'.$task->id.'" style="color:#f00;font-weight:800"></spam>';
			                        $redlight_value = 1;
			                        $statusi++;
			                      }
			                    }
			                    else{
			                      if($task->allocated_spec_status == "1")
			                      {
			                        $redlight_indication_layout ='<spam class="fa fa-star redlight_indication_layout redline_indication_layout redlight_indication_layout_'.$task->id.'" style="color:#f00;font-weight:800"></spam>';
			                        $redlight_value = 1;
			                        $statusi++;
			                      }
			                    }
			                  }
			                  if($statusi == 0)
			                  {
			                    $redlight_indication_layout ='<spam class="fa fa-star redlight_indication_layout redlight_indication_layout_'.$task->id.'" style="color:#f00;font-weight:800;display:none"></spam>';
			                    $redlight_value = 0;
			                  }
	                    		$layout.= '
	                    		<td style="width:5%;padding:10px; font-size:14px; font-weight:800;" class="redlight_sort_val">
	                    		<spam class="hidden_redlight_value" style="display:none">'.$redlight_value.'</spam>
	                    		'.$redlight_indication_layout.'
	                    		</td>
	                    		<td style="width:45%;padding:10px; font-size:14px; font-weight:800;" class="taskname_sort_val">'.$title.'</td>
	                    		
	                    		<td style="width:50%;padding:10px; font-size:14px; font-weight:800" class="subject_sort_val">'.$subject.'</td>
	                    	</tr>
	                    </table>
                    </td>
                    <td style="background: #dcdcdc;padding:0px;">
                    	<table style="width:100%">
	                    	<tr>
	                    		<td style="width:50%;padding:10px; font-size:14px; font-weight:800;" class="author_sort_val">'.$author->lastname.' '.$author->firstname.'</td>
	                    		<td style="width:50%;padding:10px; font-size:14px; font-weight:800" class="allocated_sort_val">'.$allocated_to.'</td>
	                    	</tr>
                    	</table>
                    </td>
                    <td style="background: #dcdcdc;padding:0px;">
                    	<table style="width:100%">
	                    	<tr>
	                    		<td style="width:50%;padding:10px; font-size:14px; font-weight:800;" class="duedate_sort_val">
	                    			<spam class="hidden_due_date_layout" style="display:none">'.strtotime($task->due_date).'</spam>
	                    			<spam class="layout_due_date_task" style="color:'.$due_color.' !important;font-weight:800" id="layout_due_date_task_'.$task->id.'">'.date('d-M-Y', strtotime($task->due_date)).'</spam>
	                    		</td>
	                    		<td style="width:50%;padding:10px; font-size:14px; font-weight:800" class="createddate_sort_val">
	                    		<spam class="hidden_created_date_layout" style="display:none">'.strtotime($task->creation_date).'</spam>
	                    		'.date('d-M-Y', strtotime($task->creation_date)).'
	                    		</td>
	                    	</tr>
	                    </table>
                    </td>
                    <td style="background: #dcdcdc;padding:0px;">
                    	<table style="width:100%">
	                    	<tr>
	                    		<td style="width:40%;padding:10px; font-size:14px; font-weight:800;" class="taskid_sort_val">'.$task->taskid.'</td>
	                    		<td class="layout_progress_'.$task->id.'" class="progress_sort_val" style="width:40%;padding:10px; font-size:14px; font-weight:800;">'.$task->progress.'%</td>
	                    		<td style="width:20%;padding:10px; font-size:14px; font-weight:800">
	                    			<a href="javascript:" class="fa fa-file-pdf-o download_pdf_task" data-element="'.$task->id.'" title="Download PDF" style="padding:5px;font-size:20px;font-weight: 800">
                              		</a> 
	                    		</td>
	                    	</tr>
	                    </table>
                    </td>
                  </tr>';
	        }
	    }
	    else{
            $open_tasks.='<tr><td colspan="4" style="text-align: center;padding:20px">No Tasks Found</td></tr>';
            $layout.='<tr><td colspan="4" style="text-align: center;padding:20px">No Tasks Found</td></tr>';
        }

        $outputlayout =$layout;

        if(Session::has('taskmanager_user'))
		{
			$sess_userid = Session::get('taskmanager_user');
			if($sess_userid == "")
			{
				$open_task_count = 0;
				$authored_task_count = 0;
			}
			else{
				$open_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND `author` != '".$sess_userid."' AND (`allocated_to` = '".$sess_userid."' OR `allocated_to` = '0')");
				$authored_task_count = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND `author` = '".$sess_userid."'");
			}
		}
		else{
			$open_task_count = 0;
			$authored_task_count = 0;
		}

		echo json_encode(array("open_tasks" => $open_tasks,"layout" => $outputlayout,"open_task_count" => count($open_task_count),"authored_task_count" => count($authored_task_count)));
	}
	public function taskmanager_mark_complete()
	{
		$taskidval = Input::get('task_id');
		$task_details = DB::table('taskmanager')->where('id',$taskidval)->first();
		if(count($task_details))
		{
			$author = $task_details->author;
			$allocate_user = Session::get('taskmanager_user');

			if($author == $allocate_user)
			{
				$data['status'] = 1;
				$data['progress'] = "100";
				DB::table('taskmanager')->where('id',$taskidval)->update($data);
				
				$user_details = DB::table('user')->where('user_id',$author)->first();
				$allocated_details = DB::table('user')->where('user_id',$allocate_user)->first();

				$message = '<spam style="color:#006bc7">~~~The Task was closed on <strong>'.date('d-M-Y').'</strong> by <strong>'.$allocated_details->lastname.' '.$allocated_details->firstname.'</strong>~~~</spam>';

				$dataupdate_spec_status['author_spec_status'] = 0;
				$dataupdate_spec_status['allocated_spec_status'] = 0;

				DB::table('taskmanager')->where('id',$taskidval)->update($dataupdate_spec_status);

				$data_specifics['from_user'] = $author;
				$data_specifics['to_user'] = $author;

				$data_specifics['task_id'] = $taskidval;
				$data_specifics['message'] = $message;
				$data_specifics['allocated_date'] = date('Y-m-d H:i:s');
				$data_specifics['status'] = 0;

				DB::table('taskmanager_specifics')->insert($data_specifics);

				if($task_details->recurring_task > 0)
				{
					if($task_details->recurring_task == 1)
					{
						$today = $task_details->creation_date;
						$creation_date = date('Y-m-d', strtotime($today. ' + 1 month'));
						$creation_date = date('Y-m-d', strtotime($creation_date. ' + 1 days'));
					}
					elseif($task_details->recurring_task == 2)
					{
						$today = $task_details->creation_date;
						$creation_date = date('Y-m-d', strtotime($today. ' + 7 days'));
					}
					elseif($task_details->recurring_task == 3)
					{
						$today = $task_details->creation_date;
						$creation_date = date('Y-m-d', strtotime($today. ' + 1 days'));
					}
					else
					{
						$days = $task_details->recurring_days;
						$today = $task_details->creation_date;
						$creation_date = date('Y-m-d', strtotime($today. ' + '.$days.' days'));
					}

					$from_date=date_create($task_details->creation_date);
					$to_date=date_create($task_details->due_date);
					$diff=date_diff($from_date,$to_date);
					$difference = $diff->format("%R%a");

					$due_date = date('Y-m-d', strtotime($creation_date. ' + '.$difference.' days'));

					$data['author'] = $task_details->author;
					$data['creation_date'] = $creation_date;
					$data['allocated_to'] = $task_details->allocated_to;
					$data['internal'] = $task_details->internal;
					$data['task_type'] = $task_details->task_type;
					$data['client_id'] = $task_details->client_id;
					$data['subject'] = $task_details->subject;
					$data['task_specifics'] = $task_details->task_specifics;
					$data['due_date'] = $due_date;
					$data['recurring_task'] = $task_details->recurring_task;
					$data['recurring_days'] = $task_details->recurring_days;
					$data['author_spec_status'] = 1;
					$data['allocated_spec_status'] = 1;
					$task_id = DB::table('taskmanager')->insertGetid($data);

					$taskids = 'A'.sprintf("%04d", $task_id);
					$dataupdate['taskid'] = $taskids;
					DB::table('taskmanager')->where('id',$task_id)->update($dataupdate);

					if($task_details->retain_specifics == 1)
					{
						$specifics = DB::table('taskmanager_specifics')->where('task_id',$taskidval)->get();
						if(count($specifics))
						{
							foreach($specifics as $specific)
							{
								$datacopyspec['task_id'] = $task_id;
								$datacopyspec['message'] = $specific->message;
								$datacopyspec['from_user'] = $specific->from_user;
								$datacopyspec['to_user'] = $specific->to_user;
								$datacopyspec['created_date'] = $specific->created_date;
								$datacopyspec['allocated_date'] = $specific->allocated_date;
								$datacopyspec['due_date'] = $specific->due_date;
								$datacopyspec['status'] = $specific->status;

								DB::table('taskmanager_specifics')->insert($datacopyspec);
							}
						}
					}
					
					if($task_details->retain_files == 1)
					{
						$files = DB::table('taskmanager_files')->where('task_id',$taskidval)->where('status',0)->get();
						if(count($files))
						{
							foreach($files as $file)
							{
								$dataval['task_id'] = $task_id;
				     			$dataval['url'] = $file->url;
								$dataval['filename'] = $file->filename;
								$dataval['status'] = $file->status;

								DB::table('taskmanager_files')->insert($dataval);
							}
						}
						$notes = DB::table('taskmanager_notepad')->where('task_id',$taskidval)->where('status',0)->get();
						if(count($notes))
						{
							foreach($notes as $note)
							{
								$dataval['task_id'] = $task_id;
				     			$dataval['url'] = $note->url;
								$dataval['filename'] = $note->filename;
								$dataval['status'] = $note->status;

								DB::table('taskmanager_notepad')->insert($dataval);
							}
						}
						$infiles = DB::table('taskmanager_infiles')->where('task_id',$taskidval)->where('status',0)->get();
						if(count($infiles))
						{
							foreach($infiles as $infile)
							{
								$dataval['task_id'] = $task_id;
								$dataval['infile_id'] = $infile->infile_id;
								$dataval['status'] = $infile->status;

								DB::table('taskmanager_infiles')->insert($dataval);
							}
						}
					}
				}
			}
			else{
				$data['allocated_to'] = $author;
				DB::table('taskmanager')->where('id',$taskidval)->update($data);
				
				$user_details = DB::table('user')->where('user_id',$author)->first();
				$allocated_details = DB::table('user')->where('user_id',$allocate_user)->first();

				$message = '<spam style="color:#006bc7">@@@<strong>'.$allocated_details->lastname.' '.$allocated_details->firstname.'</strong> has stated they have Finished with the task on <strong>'.date('d-M-Y').'</strong> and reallocated it to <strong>'.$user_details->lastname.' '.$user_details->firstname.'</strong>@@@</spam>';

				$data_specifics['from_user'] = $allocate_user;
				$data_specifics['to_user'] = $author;

				$data_specifics['task_id'] = $taskidval;
				$data_specifics['message'] = $message;
				$data_specifics['allocated_date'] = date('Y-m-d H:i:s');
				$data_specifics['status'] = 2;

				DB::table('taskmanager_specifics')->insert($data_specifics);

				$dataupdate_spec_status['author_spec_status'] = 1;
				$dataupdate_spec_status['allocated_spec_status'] = 0;

				DB::table('taskmanager')->where('id',$taskidval)->update($dataupdate_spec_status);


				if($task_details->avoid_email == "0")
			    {
			    	$task_specifics_val = strip_tags($task_details->task_specifics);
			    	if($task_details->subject == "")
			    	{
			    		$subject_cls = substr($task_specifics_val,0,30);
			    	}
			    	else{
			    		$subject_cls = $task_details->subject;
			    	}
			    	$allocated_person = DB::table('user')->where('user_id',Session::get('taskmanager_user'))->first();

			    	$author_details = DB::table('user')->where('user_id',$task_details->author)->first();
			    	$author_email = $author_details->email;
		    		
		    		$allocated_details = DB::table('user')->where('user_id',$task_details->author)->first();
		    		$dataemail['allocated_name'] = $allocated_details->lastname.' '.$allocated_details->firstname;
		    		$allocated_email = $allocated_details->email;


		    		$task_specifics = '';
					$specifics_first = DB::table('taskmanager_specifics')->where('task_id',$taskidval)->orderBy('id','asc')->first();
					if(count($specifics_first))
					{
						$task_specifics.=$specifics_first->message;
					}
					$task_specifics.= PHP_EOL.$task_details->task_specifics;
					$specifics = DB::table('taskmanager_specifics')->where('task_id',$taskidval)->where('id','!=',$specifics_first->id)->get();
					if(count($specifics))
					{
						foreach($specifics as $specific)
						{
							$task_specifics.=PHP_EOL.$specific->message;
						}
					}

					$task_specifics = strip_tags($task_specifics);

					$uploads = 'papers/task_specifics.txt';
					$myfile = fopen($uploads, "w") or die("Unable to open file!");
					fwrite($myfile, $task_specifics);
					fclose($myfile);
			    	
			    	$dataemail['logo'] = URL::to('assets/images/easy_payroll_logo.png');
					$dataemail['subject'] = $subject_cls;
					$dataemail['author_name'] = $author_details->lastname.' '.$author_details->firstname;
					$dataemail['due_date'] = $task_details->due_date;
					$dataemail['allocated_person'] = $allocated_person->lastname.' '.$allocated_person->firstname;
					$dataemail['allocation_date'] = date('d-M-Y');
					
					$subject_email = 'Task Manager: A Task has been allocated to you: '.$subject_cls;
					$contentmessage = view('emails/task_manager/task_manager_allocation_change', $dataemail)->render();

					$contentmessage = str_replace("â€“", "-", $contentmessage);
					$contentmessage = str_replace("â€“", "-", $contentmessage);
					$contentmessage = str_replace("â€“", "-", $contentmessage);
					$contentmessage = str_replace("â€“", "-", $contentmessage);
					$contentmessage = str_replace("â€“", "-", $contentmessage);
					$contentmessage = str_replace("â€“", "-", $contentmessage);
					$contentmessage = str_replace("â€“", "-", $contentmessage);
					$contentmessage = str_replace("â€“", "-", $contentmessage);
					$contentmessage = str_replace("â€“", "-", $contentmessage);

					$email = new PHPMailer();
					$email->SetFrom('info@gbsco.ie');
					$email->Subject   = $subject_email;
					$email->Body      = $contentmessage;
					$email->AddCC('tasks@gbsco.ie');
					$email->IsHTML(true);
					$email->AddAddress( $allocated_email );
					$email->AddAttachment( $uploads , 'task_specifics.txt' );
					$email->Send();		
			    }
			}
		}
	}
	public function taskmanager_mark_incomplete()
	{
		$taskidval = Input::get('task_id');
		$task_details = DB::table('taskmanager')->where('id',$taskidval)->first();
		if(count($task_details))
		{
			$data['status'] = 0;
			DB::table('taskmanager')->where('id',$taskidval)->update($data);
		}
	}
	public function search_taskmanager_task()
	{
		$author = Input::get('author');
		$open_task = Input::get('open_task');
		$client_id = Input::get('client_id');
		$subject = Input::get('subject');
		$recurring = Input::get('recurring');
		$due_date = Input::get('due_date');
		$creation_date = Input::get('creation_date');

		$allocated_to_val = Session::get('taskmanager_user');

		$query = '';
		if($author != ""){ $query.= "`author` = '".$author."'";  }

		if($open_task != "0"){ 
			if($query == "") { $query.= "(`status` = '0' OR `status` = '2')"; } else { $query.= " AND (`status` = '0' OR `status` = '2')"; }
		}
		else{
			if($query == "") { $query.= "(`status` = '1' OR `status` = '2')"; } else { $query.= " AND (`status` = '1' OR `status` = '2')"; }
		}

		if($client_id != ""){ if($query == "") { $query.= "`client_id` = '".$client_id."'"; } else { $query.= " AND `client_id` = '".$client_id."'"; } }
		if($recurring != "0"){ if($query == "") { $query.= "`recurring_task` > '0'"; } else { $query.= " AND `recurring_task` > '0'"; } }
		else{ if($query == "") { $query.= "`recurring_task` = '0'"; } else { $query.= " AND `recurring_task` = '0'"; } }

		if($due_date != ""){
			$due_date_change = DateTime::createFromFormat('d-M-Y', $due_date);
			$due_date_change = $due_date_change->format('Y-m-d');

			if($query == "") { $query.= "`due_date` = '".$due_date_change."'"; } else { $query.= " AND `due_date` = '".$due_date_change."'"; } 
		}
		if($creation_date != ""){ 
			$creation_date_change = DateTime::createFromFormat('d-M-Y', $creation_date);
			$creation_date_change = $creation_date_change->format('Y-m-d');

			if($query == "") { $query.= "`creation_date` = '".$creation_date_change."'"; } else { $query.= " AND `creation_date` = '".$creation_date_change."'"; }
		}
		if($subject != "") { 
			if($query == "") { $query.= "`subject` LIKE '%".$subject."%' OR `task_specifics` LIKE '%".$subject."%' OR `taskid` LIKE '%".$subject."%'"; } 
			else { $query.= " AND (`subject` LIKE '%".$subject."%' OR `task_specifics` LIKE '%".$subject."%' OR `taskid` LIKE '%".$subject."%')"; } 
		}

		$query = "SELECT * FROM `taskmanager` WHERE ".$query."";
		$user_tasks = DB::select($query);

		$open_tasks = '';
		if(count($user_tasks))
	    {
	        foreach($user_tasks as $task)
	        {
	        	if($task->status == 1){ $disabled = 'disabled'; $disabled_icon = 'disabled_icon'; }
	          	else{ 
	          		if($allocated_to_val == $task->allocated_to) { $disabled = ''; $disabled_icon = ''; }
		        	elseif($allocated_to_val == $task->author) { $disabled = ''; $disabled_icon = ''; }
		        	else{ $disabled = 'cant_edit_task'; $disabled_icon = 'cant_edit_task_tr'; }
	          	}

	          $taskfiles = DB::table('taskmanager_files')->where('task_id',$task->id)->get();
	          $tasknotepad = DB::table('taskmanager_notepad')->where('task_id',$task->id)->get();
	          $taskinfiles = DB::table('taskmanager_infiles')->where('task_id',$task->id)->get();

	          if($task->client_id == "")
	          {
	            $title_lable = 'Task Name:';
	            $task_details = DB::table('time_task')->where('id', $task->task_type)->first();
	            if(count($task_details))
	            {
	            	$title = '<spam class="task_name_'.$task->id.'">'.$task_details->task_name.'</spam>';
	            }
	            else{
	            	$title = '<spam class="task_name_'.$task->id.'"></spam>';
	            }
	          }
	          else{
	            $title_lable = 'Client:';
	            $client_details = DB::table('cm_clients')->where('client_id', $task->client_id)->first();
	            if(count($client_details))
                {
                  $title = $client_details->company.' ('.$task->client_id.')';
                }
                else{
                  $title = '';
                }
	          }
	          $author = DB::table('user')->where('user_id',$task->author)->first();
	          $task_specifics_val = strip_tags($task->task_specifics);
	          if($task->subject == "") { $subject = substr($task_specifics_val,0,30); }
	          else{ $subject = $task->subject; }

	          if($task->allocated_to == 0) { $allocated_to = ''; }
	          else{ $allocated = DB::table('user')->where('user_id',$task->allocated_to)->first(); $allocated_to = $allocated->lastname.' '.$allocated->firstname; }
	          $open_tasks.='<tr id="task_tr_'.$task->id.'">
	            <td style="vertical-align: baseline;background: #2fd9ff;width:35%">
	              <table class="table">
	                <tr>
	                  <td style="width:25%;background:#2fd9ff;font-weight:700;text-decoration: underline;">'.$title_lable.'</td>
	                  <td style="width:75%;background:#2fd9ff">'.$title.'';
	                  if($task->recurring_task > 0)
	                  {
	                    $open_tasks.='<img src="'.URL::to('assets/images/recurring.png').'" style="width:30px;" title="This is a Recurring Task">';
	                  }
	                  $open_tasks.='</td>
	                </tr>
	                <tr>
	                  <td style="background:#2fd9ff;font-weight:700;text-decoration: underline;">Subject:</td>
	                  <td style="background:#2fd9ff">'.$subject.'</td>
	                </tr>
	                <tr>';
	                    $date1=date_create(date('Y-m-d'));
	                    $date2=date_create($task->due_date);
	                    $diff=date_diff($date1,$date2);
	                    $diffdays = $diff->format("%R%a");

	                    if($diffdays == 0 || $diffdays== 1) { $due_color = '#e89701'; }
	                    elseif($diffdays < 0) { $due_color = '#f00'; }
	                    elseif($diffdays > 7) { $due_color = '#000'; }
	                    elseif($diffdays <= 7) { $due_color = '#00a91d'; }
	                    else{ $due_color = '#000'; }
	                  $open_tasks.='<td style="background:#2fd9ff;font-weight:700;text-decoration: underline;">Due Date:</td>
	                  <td style="background:#2fd9ff" class="'.$disabled_icon.'">
	                    <spam style="color:'.$due_color.' !important;font-weight:800" id="due_date_task_'.$task->id.'">'.date('d-M-Y', strtotime($task->due_date)).'</spam>
	                    <a href="javascript:" data-element="'.$task->id.'" data-subject="'.$subject.'" data-value="'.date('d-M-Y', strtotime($task->due_date)).'" data-duedate="'.$task->due_date.'" data-color="'.$due_color.'" class="fa fa-edit edit_due_date edit_due_date_'.$task->id.' '.$disabled.'" style="font-weight:800"></a>
	                  </td>
	                </tr>
	                <tr>
                        <td style="background: #2fd9ff;font-weight:700;text-decoration: underline;">Date Created:</td>
                        <td style="background: #2fd9ff">
                          <spam>'.date('d-M-Y', strtotime($task->creation_date)).'</spam>
                        </td>
                    </tr>';
                    if($task->status == 1)
                    {
                    	$task_spec_closed = DB::table('taskmanager_specifics')->where('task_id',$task->id)->orderBy('id','desc')->first();
                    	$open_tasks.='<tr>
	                        <td style="background: #2fd9ff;font-weight:700;text-decoration: underline;">Date Closed:</td>
	                        <td style="background: #2fd9ff">
	                          <spam>'.date('d-M-Y', strtotime($task_spec_closed->allocated_date)).'</spam>
	                        </td>
	                    </tr>';
                    }
	                $open_tasks.='<tr>
	                  <td style="background:#2fd9ff;font-weight:700;text-decoration: underline;">Task Specifics:</td>
	                  <td style="background:#2fd9ff"><a href="javascript:" class="link_to_task_specifics" data-element="'.$task->id.'">'.substr($task_specifics_val,0,30).'...</a></td>
	                </tr>
	                <tr>
	                  <td style="background:#2fd9ff;font-weight:700;text-decoration: underline;">Task files:</td>
	                  <td style="background:#2fd9ff">';
	                    $fileoutput = '';
	                    if(count($taskfiles))
	                    {
	                      foreach($taskfiles as $file)
	                      {
	                        if($file->status == 0)
	                        {
	                          $fileoutput.='<p><a href="'.URL::to($file->url).'/'.$file->filename.'" class="file_attachments" download>'.$file->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_files?file_id='.$file->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
	                        }
	                      }
	                    }
	                    if(count($tasknotepad))
	                    {
	                      foreach($tasknotepad as $note)
	                      {
	                        if($note->status == 0)
	                        {
	                          $fileoutput.='<p><a href="'.URL::to($note->url).'/'.$note->filename.'" class="file_attachments" download>'.$note->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_notepad?file_id='.$note->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
	                        }
	                      }
	                    }
	                    if(count($taskinfiles))
	                    {
	                      $i=1;
	                      foreach($taskinfiles as $infile)
	                      {
	                        if($infile->status == 0)
	                        {
	                          if($i == 1) { $fileoutput.='Linked Infiles:<br/>'; }
	                          $file = DB::table('in_file')->where('id',$infile->infile_id)->first();
	                          if(count($file))
	                          {
	                          	$ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
		                          $fileoutput.='<p class="link_infile_p"><a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$i.'</a>
		                          <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.date('d-M-Y', strtotime($file->data_received)).'</a>
		                          <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$file->description.'</a>

		                          <a href="'.URL::to('user/delete_taskmanager_infiles?file_id='.$infile->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a>
		                          </p>';
		                          $i++;
	                          }
	                        }
	                      }
	                    }
	                    $open_tasks.=$fileoutput;
	                  $open_tasks.='</td>
	                </tr>
	              </table>
	            </td>
	            <td style="vertical-align: baseline;background: #dcdcdc;width:30%">
	              <table class="table">
	                <tr>
	                  <td style="width:25%;font-weight:700;text-decoration: underline;">Author:</td>
	                  <td style="width:75%">'.$author->lastname.' '.$author->firstname.'';
                      if($task->avoid_email == 0) {
                        $open_tasks.='<a href="javascript:" class="fa fa-envelope avoid_email" data-element="'.$task->id.'" title="Avoid Emails for this task"></a>';
                      }
                      else{
                        $open_tasks.='<a href="javascript:" class="fa fa-envelope avoid_email retain_email" data-element="'.$task->id.'" title="Avoid Emails for this task"></a>';
                      }
	                  $open_tasks.='</td>
	                </tr>
	                <tr>
	                  <td><spam style="font-weight:700;text-decoration: underline;">Allocated to:</spam></td>
	                  <td id="allocated_to_name_'.$task->id.'">'.$allocated_to.'</td>
	                </tr>
	                <tr>
	                  <td colspan="2">
	                    <spam style="font-weight:700;text-decoration: underline;">Allocations:</spam>&nbsp;
	                    <a href="javascript:" data-element="'.$task->id.'" data-subject="'.$subject.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="fa fa-sitemap edit_allocate_user edit_allocate_user_'.$task->id.' '.$disabled.'" title="Allocate User" style="font-weight:800"></a>
	                    &nbsp;
	                    <a href="javascript:" data-element="'.$task->id.'" data-subject="'.$subject.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="fa fa-history show_task_allocation_history show_task_allocation_history_'.$task->id.'" title="Allocation history" style="font-weight:800"></a>
	                    &nbsp;
                        <a href="javascript:" data-element="'.$task->id.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="request_update request_update_'.$task->id.'" '.$disabled.' title="Request Update" style="font-weight:800">
                        	<img src="'.URL::to('assets/images/request.png').'" data-element="'.$task->id.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'" class="request_update request_update_'.$task->id.'" '.$disabled.' style="width:16px;">
                        </a>
	                  </td>
	                </tr>
	                <tr>
	                  <td colspan="2" id="allocation_history_div_'.$task->id.'">';
	                    $allocations = DB::table('taskmanager_specifics')->where('task_id',$task->id)->where('to_user','!=','')->where('status','<',3)->limit(5)->orderBy('id','desc')->get();
	                    $output = '';
	                    if(count($allocations))
	                    {
	                      foreach($allocations as $allocate)
	                      {
	                        $output.='<p data-element="'.$task->id.'" data-subject="'.$subject.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="edit_allocate_user edit_allocate_user_'.$task->id.' '.$disabled.'" title="Allocate User">';
	                          $fromuser = DB::table('user')->where('user_id',$allocate->from_user)->first();
	                          $touser = DB::table('user')->where('user_id',$allocate->to_user)->first();
	                          $output.=$fromuser->lastname.' '.$fromuser->firstname.' -> '.$touser->lastname.' '.$touser->firstname.' ('.date('d-M-Y H:i', strtotime($allocate->allocated_date)).')';
	                        $output.='</p>';
	                      }
	                    }
	                    $open_tasks.=$output;
	                  $open_tasks.='</td>
	                </tr>
	              </table>
	            </td>
	            <td style="vertical-align: baseline;background: #dcdcdc;width:20%">
	              <table class="table">
	                <tr>
	                  <td><spam style="font-weight:700;text-decoration: underline;">Progress Files:</spam></td>
	                  <td></td>
	                </tr>
	                <tr>
	                  <td class="'.$disabled_icon.'">
	                    <a href="javascript:" class="fa fa-plus faplus_progress '.$disabled.'" data-element="'.$task->id.'" style="padding:5px;background: #dfdfdf;"></a>
	                    <a href="javascript:" class="fa fa-edit fanotepad_progress '.$disabled.'" style="padding:5px;background: #dfdfdf;"></a>';
	                    if($task->client_id != "")
	                    {
	                      $open_tasks.='<a href="javascript:" class="infiles_link_progress '.$disabled.'" data-element="'.$task->id.'">Infiles</a>';
	                    }
	                    $open_tasks.='<input type="hidden" name="hidden_progress_client_id" id="hidden_progress_client_id_'.$task->id.'" value="'.$task->client_id.'">
	                    <input type="hidden" name="hidden_infiles_progress_id" id="hidden_infiles_progress_id_'.$task->id.'" value="">
	                    
	                    <div class="notepad_div_progress_notes" style="z-index:9999; position:absolute">
	                      <textarea name="notepad_contents_progress" class="form-control notepad_contents_progress" placeholder="Enter Contents"></textarea>
	                      <input type="hidden" name="hidden_task_id_progress_notepad" id="hidden_task_id_progress_notepad" value="'.$task->id.'">
	                      <input type="button" name="notepad_progress_submit" class="btn btn-sm btn-primary notepad_progress_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
	                      <spam class="error_files_notepad"></spam>
	                    </div>
	                  </td>
	                  <td></td>
	                </tr>
	                <tr>
	                  <td colspan="2">';
	                    $fileoutput ='<div id="add_files_attachments_progress_div_'.$task->id.'">';
	                    if(count($taskfiles))
	                    {
	                      foreach($taskfiles as $file)
	                      {
	                        if($file->status == 1)
	                        {
	                          $fileoutput.='<p><a href="'.URL::to($file->url).'/'.$file->filename.'" class="file_attachments" download>'.$file->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_files?file_id='.$file->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
	                        }
	                      }
	                    }
	                    $fileoutput.='</div>';
	                    $fileoutput.='<div id="add_notepad_attachments_progress_div_'.$task->id.'">';
	                    if(count($tasknotepad))
	                    {
	                      foreach($tasknotepad as $note)
	                      {
	                        if($note->status == 1)
	                        {
	                          $fileoutput.='<p><a href="'.URL::to($note->url).'/'.$note->filename.'" class="file_attachments" download>'.$note->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_notepad?file_id='.$note->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
	                        }
	                      }
	                    }
	                    $fileoutput.='</div>';
	                    $fileoutput.='<div id="add_infiles_attachments_progress_div_'.$task->id.'">';
	                    if(count($taskinfiles))
	                    {
	                      $i=1;
	                        foreach($taskinfiles as $infile)
	                        {
	                          if($infile->status == 1)
	                          {
	                            if($i == 1) { $fileoutput.='Linked Infiles:<br/>'; }
	                            $file = DB::table('in_file')->where('id',$infile->infile_id)->first();
	                            if(count($file))
	                            {
	                            	$ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
		                            $fileoutput.='<p class="link_infile_p"><a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$i.'</a>
		                            <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.date('d-M-Y', strtotime($file->data_received)).'</a>
		                            <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$file->description.'</a>

		                            <a href="'.URL::to('user/delete_taskmanager_infiles?file_id='.$infile->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a>
		                            </p>';
		                            $i++;
	                            }
	                          }
	                        }
	                    }
	                    $fileoutput.='</div>';
	                    $open_tasks.= $fileoutput;
	                  $open_tasks.='</td>
	                </tr>
	              </table>
	            </td>
	            <td style="vertical-align: baseline;background: #2fd9ff;width:15%">
	              <table class="table">
	                <tr>
	                  <td style="background:#2fd9ff" class="'.$disabled_icon.'">
	                  <a href="javascript:" class="fa fa-file-pdf-o download_pdf_task" data-element="'.$task->id.'" title="Download PDF" style="padding:5px;font-size:20px;font-weight: 800">
                              </a> 
	                  <a href="javascript:" class="fa fa-files-o copy_task '.$disabled.'" data-element="'.$task->id.'" title="Copy this Task" style="padding:5px;font-size:20px;font-weight: 800"></a></td>
	                </tr>
	                <tr>
	                  <td style="background:#2fd9ff"><spam style="font-weight:700;text-decoration: underline;">Task ID:</spam> '.$task->taskid.'</td>
	                </tr>
	                <tr>
	                  <td style="background:#2fd9ff">
	                    <spam style="font-weight:700;text-decoration: underline;">Progress:</spam> 
	                    <a href="javascript:" class="fa fa-sliders" data-placement="bottom" data-popover-content="#a1_'.$task->id.'" data-toggle="popover" data-trigger="click" tabindex="0" title="Set Progress" data-original-title="Set Progress"  style="padding:5px;font-weight:700"></a>

                            <!-- Content for Popover #1 -->
                            <div class="hidden" id="a1_'.$task->id.'">
                              <div class="popover-heading">
                                Set Progress Percentage
                              </div>
                              <div class="popover-body">
                                <input type="number" class="form-control input-sm progress_value" id="progress_value_'.$task->id.'" value="" style="width:60%;float:left">
                                <a href="javascript:" class="common_black_button set_progress" data-element="'.$task->id.'" style="font-size: 11px;line-height: 29px;">Set</a>
                              </div>
                            </div>

	                    <br/>
                            <div class="progress progress_'.$task->id.'" style="margin-top:20px">
                              <div class="progress-bar" role="progressbar" aria-valuenow="'.$task->progress.'" aria-valuemin="0" aria-valuemax="100" style="width:'.$task->progress.'%">
                                '.$task->progress.'%
                              </div>
                            </div>
	                  </td>
	                </tr>
	                <tr>
	                  <td style="background:#2fd9ff">';
	                    if($task->status == 1)
	                    {
	                      $open_tasks.='<a href="javascript:" class="common_black_button mark_as_incomplete" data-element="'.$task->id.'">Completed</a>';
	                    }
	                    else{
	                      	if(Session::has('taskmanager_user'))
                            {
                                $allocated_person = Session::get('taskmanager_user');
                                if($task->author == $allocated_person)
                                {
                                  $complete_button = 'mark_as_complete_author';
                                }
                                else{
                                  $complete_button = 'mark_as_complete';
                                }
                            }
                            else{
                                $complete_button = 'mark_as_complete';
                            }
	                      $open_tasks.='<a href="javascript:" class="common_black_button '.$complete_button.'" data-element="'.$task->id.'">Mark Complete</a>';
	                    }
	                  $open_tasks.='</td>
	                </tr>
	                <tr>
	                  <td style="background:#2fd9ff">
	                    <spam style="font-weight:700;text-decoration: underline;">Completion Files:</spam><br/>
	                    <a href="javascript:" class="fa fa-plus faplus_completion '.$disabled.'" data-element="'.$task->id.'" style="padding:5px"></a>
	                    <a href="javascript:" class="fa fa-edit fanotepad_completion '.$disabled.'" style="padding:5px;"></a>';
	                    if($task->client_id != "")
	                    {
	                      $open_tasks.='<a href="javascript:" class="infiles_link_completion '.$disabled.'" data-element="'.$task->id.'">Infiles</a>';
	                    }
	                    $open_tasks.='<input type="hidden" name="hidden_completion_client_id" id="hidden_completion_client_id_'.$task->id.'" value="'.$task->client_id.'">
	                    <input type="hidden" name="hidden_infiles_completion_id" id="hidden_infiles_completion_id_'.$task->id.'" value="">

	                    
	                    <div class="notepad_div_completion_notes" style="z-index:9999; position:absolute">
	                      <textarea name="notepad_contents_completion" class="form-control notepad_contents_completion" placeholder="Enter Contents"></textarea>
	                      <input type="hidden" name="hidden_task_id_completion_notepad" id="hidden_task_id_completion_notepad" value="'.$task->id.'">
	                      <input type="button" name="notepad_completion_submit" class="btn btn-sm btn-primary notepad_completion_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
	                      <spam class="error_files_notepad"></spam>
	                    </div>';
	                    $fileoutput ='<div id="add_files_attachments_completion_div_'.$task->id.'">';
	                    if(count($taskfiles))
	                    {
	                      foreach($taskfiles as $file)
	                      {
	                        if($file->status == 2)
	                        {
	                          $fileoutput.='<p><a href="'.URL::to($file->url).'/'.$file->filename.'" class="file_attachments" download>'.$file->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_files?file_id='.$file->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
	                        }
	                      }
	                    }
	                    $fileoutput.='</div>';
	                    $fileoutput.='<div id="add_notepad_attachments_completion_div_'.$task->id.'">';
	                    if(count($tasknotepad))
	                    {
	                      foreach($tasknotepad as $note)
	                      {
	                        if($note->status == 2)
	                        {
	                          $fileoutput.='<p><a href="'.URL::to($note->url).'/'.$note->filename.'" class="file_attachments" download>'.$note->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_notepad?file_id='.$note->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
	                        }
	                      }
	                    }
	                    $fileoutput.='</div>';
	                    $fileoutput.='<div id="add_infiles_attachments_completion_div_'.$task->id.'">';
	                    if(count($taskinfiles))
	                    {
	                      $i=1;
	                        foreach($taskinfiles as $infile)
	                        {
	                          if($infile->status == 2)
	                          {
	                            if($i == 1) { $fileoutput.='Linked Infiles:<br/>'; }
	                            $file = DB::table('in_file')->where('id',$infile->infile_id)->first();
	                            $ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
	                            $fileoutput.='<p class="link_infile_p"><a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$i.'</a>
	                            <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.date('d-M-Y', strtotime($file->data_received)).'</a>
	                            <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$file->description.'</a>

	                            <a href="'.URL::to('user/delete_taskmanager_infiles?file_id='.$infile->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a>
	                            </p>';
	                            $i++;
	                          }
	                        }
	                    }
	                    $fileoutput.='</div>';
	                    $open_tasks.= $fileoutput;
	                  $open_tasks.='</td>
	                </tr>
	              </table>
	            </td>
	          </tr>
	          <tr class="empty_tr" style="background: #fff;height:30px">
                <td colspan="4" style="background: #fff;height:30px"></td>
	          </tr>';
	        }
	    }
	    else{
            $open_tasks.='<td colspan="4" style="text-align: center;padding:20px">No Tasks Found</td>';
        }
		echo $open_tasks;
	}
	public function update_taskmanager_details()
	{
		$task_id = Input::get('task_id');
		$author = Input::get('author');
		$allocated = Input::get('allocated');
		$specifics = Input::get('specifics');
		$files = Input::get('files');
		$recurring = Input::get('recurring');
		$days = Input::get('days');
		$specific_recurring = Input::get('specific_recurring');
		$task_type = Input::get('task_type');

		$data['author'] = $author;
		$data['allocated_to'] = $allocated;
		$data['retain_specifics'] = $specifics;
		$data['retain_files'] = $files;
		if($task_type == "0")
		{
			$data['task_type'] = 0;
			$dataval['task_name_val'] = '';
		}
		else{
			$data['task_type'] = $task_type;

			$task_details = DB::table('time_task')->where('id', $task_type)->first();
            if(count($task_details))
            {
              $title = $task_details->task_name;
            }
            else{
              $title = '';
            }
			$dataval['task_name_val'] = $title;
		}

		if($recurring == "1")
		{
			$data['recurring_task'] = $days;
			$dataval['recurring_task'] = 'YES';

			if($days == "1") { $data['recurring_days'] = "30"; $dataval['recurring_days'] = 'Monthly'; }
			elseif($days == "2") { $data['recurring_days'] = "7"; $dataval['recurring_days'] = 'Weekly'; }
			elseif($days == "3") { $data['recurring_days'] = "1"; $dataval['recurring_days'] = 'Daily'; }
			else { $data['recurring_days'] = $specific_recurring; $dataval['recurring_days'] = 'Specific'; }

		}
		else{
			$data['recurring_task'] = 0;
			$dataval['recurring_task'] = 'NO';
			$dataval['recurring_days'] = 'Specific';
		}

		DB::table('taskmanager')->where('id',$task_id)->update($data);

		$user_details = DB::table('user')->where('user_id',$author)->first();
		if(count($user_details))
		{
			$author = $user_details->lastname.' '.$user_details->firstname;
		}
		else{
			$author = '';
		}
		$allocated_details = DB::table('user')->where('user_id',$allocated)->first();
		if(count($allocated_details))
		{
			$allocated = $allocated_details->lastname.' '.$allocated_details->firstname;
		}
		else{
			$allocated = '';
		}

		if($specifics == "1") { $specificsval = '<i class="fa fa-check"></i>'; }
		else { $specificsval = ''; }

		if($files == "1") { $filesval = '<i class="fa fa-check"></i>'; }
		else { $filesval = ''; }

		$dataval['author'] = $author;
		$dataval['allocated'] = $allocated;
		$dataval['retain_spec'] = $specificsval;
		$dataval['retain_files'] = $filesval;
		$dataval['task_type'] = $task_type;

		echo json_encode($dataval);
	}
	public function show_more_tasks()
	{
		$page_no = Input::get('page_no');
		$offset = $page_no * 500;
		$tasks = DB::table('taskmanager')->orderBy('id','desc')->offset($offset)->limit(500)->get();
		$outputtask = '';
          if(count($tasks))
          {
            foreach($tasks as $task)
            {
              if($task->client_id == "")
              {
                $title_lable = 'Task Name:';
                $task_details = DB::table('time_task')->where('id', $task->task_type)->first();
                if(count($task_details))
                {
                  $title = $task_details->task_name;
                  $tasktitle = $task_details->task_name;
                  $internaltask = 'yes';
                }
                else{
                  $title = '';
                  $tasktitle = '';
                  $internaltask = '';
                }
              }
              else{
                $title_lable = 'Client:';
                $client_details = DB::table('cm_clients')->where('client_id', $task->client_id)->first();
                if(count($client_details))
                {
                  $title = $client_details->company.' ('.$task->client_id.')';
                  $tasktitle = '';
                  $internaltask = '';
                }
                else{
                  $title = '';
                  $tasktitle = '';
                  $internaltask = '';
                }
              }
              if($task->allocated_to != 0)
              {
                $allocated_details = DB::table('user')->where('user_id',$task->allocated_to)->first();
                $allocated_to = $allocated_details->lastname.' '.$allocated_details->firstname;
              }
              else{
                $allocated_to = '-';
              }

              if($task->author != 0)
              {
                $author_details = DB::table('user')->where('user_id',$task->author)->first();
                $author_to = $author_details->lastname.' '.$author_details->firstname;
              }
              else{
                $author_to = '-';
              }

              if($task->status == 1) { $color = 'color:#f00'; $tr_status= 'tr_closed'; } 
              else { $color = ''; $tr_status= ''; }

              if($task->recurring_days == "30"){ $recurring_days = 'Monthly'; }
              elseif($task->recurring_days == "7"){ $recurring_days = 'Weekly'; }
              elseif($task->recurring_days == "1"){ $recurring_days = 'Daily'; }
              else{ $recurring_days = 'Specific'; }

              if($task->recurring_task == "0"){ $recurring_task = 'NO'; }
              else{ $recurring_task = 'YES'; }

              if($task->retain_specifics == "0"){ $retain_specifics = '-'; }
              else{ $retain_specifics = '<i class="fa fa-check"></i>'; }

              if($task->retain_files == "0"){ $retain_files = '-'; }
              else{ $retain_files = '<i class="fa fa-check"></i>'; }

              $outputtask.='<tr id="tr_task_'.$task->id.'" class="tr_task '.$tr_status.'">
                <td class="taskid_td" style="'.$color.'">'.$task->taskid.'</td>
                <td class="taskid_td task_name_val" style="'.$color.'">'.$title.'</td>
                <td class="author_td" style="'.$color.'">'.$author_to.'</td>
                <td class="allocated_td" style="'.$color.'">'.$allocated_to.'</td>
                <td class="allocated_td" style="'.$color.';text-align:center">';
                  if($task->status == 2)
                  {
                    $outputtask.='<i class="fa fa-pause" aria-hidden="true"></i><br/>'.date('d-M-Y',strtotime($task->park_date)).'';
                  }
                  else{
                    $outputtask.='-';
                  }
                $outputtask.='</td>
                <td class="retain_spec_td" style="'.$color.'">'.$retain_specifics.'</td>
                <td class="retain_files_td" style="'.$color.'">'.$retain_files.'</td>
                <td class="subject_td" style="'.$color.'">'.$task->subject.'</td>
                <td class="recurring_days_td" style="'.$color.'">'.$recurring_days.'</td>
                <td class="recurring_task_td" style="'.$color.'">'.$recurring_task.'</td>
                <td class="due_date_td" style="'.$color.'">'.$task->due_date.'</td>
                <td style="'.$color.'">
                  <a href="javascript:" class="fa fa-download download_pdf_task" data-element="'.$task->id.'" title="Download PDF"></a>
                  <a href="javascript:" class="fa fa-edit edit_task edit_task_'.$task->id.'" data-element="'.$task->id.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'" data-specifics="'.$task->retain_specifics.'" data-files="'.$task->retain_files.'" data-recurring="'.$task->recurring_task.'" title="EDIT TASK"></a>
                </td>
              </tr>';
            }
          }
          echo $outputtask;
	}
	public function download_taskmanager_task_pdf()
	{
		$task_id = Input::get('task_id');
		$task = DB::table('taskmanager')->where('id',$task_id)->first();
		$open_tasks = '';
	          $taskfiles = DB::table('taskmanager_files')->where('task_id',$task->id)->get();
	          $tasknotepad = DB::table('taskmanager_notepad')->where('task_id',$task->id)->get();
	          $taskinfiles = DB::table('taskmanager_infiles')->where('task_id',$task->id)->get();

	          if($task->client_id == "")
	          {
	            $title_lable = 'Task Name:';
	            $task_details = DB::table('time_task')->where('id', $task->task_type)->first();
	            if(count($task_details))
	            {
	            	$title = $task_details->task_name;
	            }
	            else{
	            	$title = '';
	            }
	          }
	          else{
	            $title_lable = 'Client:';
	            $client_details = DB::table('cm_clients')->where('client_id', $task->client_id)->first();
	            if(count($client_details))
                {
                  $title = $client_details->company.' ('.$task->client_id.')';
                }
                else{
                  $title = '';
                }
	          }
	          $author = DB::table('user')->where('user_id',$task->author)->first();
	          $task_specifics_val = strip_tags($task->task_specifics);
	          if($task->subject == "") { $subject = substr($task_specifics_val,0,30); }
	          else{ $subject = $task->subject; }

	          if($task->allocated_to == 0) { $allocated_to = ''; }
	          else{ $allocated = DB::table('user')->where('user_id',$task->allocated_to)->first(); $allocated_to = $allocated->lastname.' '.$allocated->firstname; }

	          $spec_output = '<p>'.trim($task->task_specifics).'</p>';
				$specifics = DB::table('taskmanager_specifics')->where('task_id',$task_id)->get();
				if(count($specifics))
				{
					foreach($specifics as $specific)
					{
						$spec_output.='<p style="font-weight:400">'.trim($specific->message).'</p>';
					}
				}

	          $open_tasks.='
	          <div style="width:100%;border:1px solid #000">
	          		<div style="width:100%;padding:10px">
			          	<div style="width:20%">'.$title_lable.'</div>
			          	<div style="margin-left:20%;width:80%;padding-top:-20px">'.$title.'</div>
	          		</div>
	          		<div style="width:100%;padding:10px">
			          	<div style="width:20%">Author:</div>
			          	<div style="margin-left:20%;width:80%;padding-top:-20px">'.$author->lastname.' '.$author->firstname.'</div>
	          		</div>
	          		<div style="width:100%;padding:10px">
			          	<div style="width:20%">Allocated to:</div>
			          	<div style="margin-left:20%;width:80%;padding-top:-20px">'.$allocated_to.'</div>
	          		</div>
	          		<div style="width:100%;padding:10px">
			          	<div style="width:20%">Task ID:</div>
			          	<div style="margin-left:20%;width:80%;padding-top:-20px">'.$task->taskid.'</div>
	          		</div>
	          		<div style="width:100%;padding:10px">
			          	<div style="width:20%">Subject:</div>
			          	<div style="margin-left:20%;width:80%;padding-top:-20px">'.$subject.'</div>
	          		</div>
	          		<div style="width:100%;padding:10px">';
	          			$date1=date_create(date('Y-m-d'));
	                    $date2=date_create($task->due_date);
	                    $diff=date_diff($date1,$date2);
	                    $diffdays = $diff->format("%R%a");

	                    if($diffdays == 0 || $diffdays== 1) { $due_color = '#e89701'; }
	                    elseif($diffdays < 0) { $due_color = '#f00'; }
	                    elseif($diffdays > 7) { $due_color = '#000'; }
	                    elseif($diffdays <= 7) { $due_color = '#00a91d'; }
	                    else{ $due_color = '#000'; }

			          	$open_tasks.='<div style="width:20%">Due Date:</div>
			          	<div style="margin-left:20%;width:80%;padding-top:-20px;color:'.$due_color.' !important;font-weight:800">'.date('d-M-Y', strtotime($task->due_date)).'</div>
	          		</div>
	          		<div style="width:100%;padding:10px">
			          	<div style="width:20%">Task Specifics:</div>
			          	<div style="margin-left:20%;width:80%;padding-top:-20px">'.trim($spec_output).'</div>
	          		</div>

	          		<div style="width:100%;padding:10px">
	                  <div style="width:20%">Task files:</div>
	                  <div style="margin-left:20%;width:80%;padding-top:-20px">';
	                    $fileoutput = '';
	                    if(count($taskfiles))
	                    {
	                      foreach($taskfiles as $file)
	                      {
	                        if($file->status == 0)
	                        {
	                          $fileoutput.=$file->filename.'<br/>';
	                        }
	                      }
	                    }
	                    if(count($tasknotepad))
	                    {
	                      foreach($tasknotepad as $note)
	                      {
	                        if($note->status == 0)
	                        {
	                          $fileoutput.=$note->filename.'<br/>';
	                        }
	                      }
	                    }
	                    if(count($taskinfiles))
	                    {
	                      $i=1;
	                      foreach($taskinfiles as $infile)
	                      {
	                        if($infile->status == 0)
	                        {
	                          if($i == 1) { $fileoutput.='Linked Infiles:<br/>'; }
	                          $file = DB::table('in_file')->where('id',$infile->infile_id)->first();
	                          $ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
	                          $fileoutput.=$i.'&nbsp;'.date('d-M-Y', strtotime($file->data_received)).'&nbsp;'.$file->description.'<br/>';
	                          $i++;
	                        }
	                      }
	                    }
	                    $open_tasks.=$fileoutput;
	                  $open_tasks.='</div>
	                </div>
	                <div style="width:100%;padding:10px">
	                  <div style="width:20%">
	                    Allocations:
	                  </div>
	                  <div style="margin-left:20%;width:80%;padding-top:-20px">';
	                    $allocations = DB::table('taskmanager_specifics')->where('task_id',$task->id)->where('to_user','!=','')->where('status','<',3)->limit(5)->orderBy('id','desc')->get();
	                    $output = '';
	                    if(count($allocations))
	                    {
	                      foreach($allocations as $allocate)
	                      {
	                          $fromuser = DB::table('user')->where('user_id',$allocate->from_user)->first();
	                          $touser = DB::table('user')->where('user_id',$allocate->to_user)->first();
	                          $output.=$fromuser->lastname.' '.$fromuser->firstname.' -> '.$touser->lastname.' '.$touser->firstname.' ('.date('d-M-Y H:i', strtotime($allocate->allocated_date)).')';
	                        $output.='<br/>';
	                      }
	                    }
	                    $open_tasks.=$output;
	                  $open_tasks.='</div>
	                </div>
	                <div style="width:100%;padding:10px">
	                  <div style="width:20%">Progress Files:</div>
	                  <div style="margin-left:20%;width:80%;padding-top:-20px">';
	                    $fileoutput ='';
	                    if(count($taskfiles))
	                    {
	                      foreach($taskfiles as $file)
	                      {
	                        if($file->status == 1)
	                        {
	                          $fileoutput.=$file->filename.'<br/>';
	                        }
	                      }
	                    }
	                    if(count($tasknotepad))
	                    {
	                      foreach($tasknotepad as $note)
	                      {
	                        if($note->status == 1)
	                        {
	                          $fileoutput.=$note->filename.'<br/>';
	                        }
	                      }
	                    }
	                    if(count($taskinfiles))
	                    {
	                      $i=1;
	                        foreach($taskinfiles as $infile)
	                        {
	                          if($infile->status == 1)
	                          {
	                            if($i == 1) { $fileoutput.='Linked Infiles:<br/>'; }
	                            $file = DB::table('in_file')->where('id',$infile->infile_id)->first();
	                            $ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
	                            $fileoutput.=$i.'&nbsp;'.date('d-M-Y', strtotime($file->data_received)).'&nbsp;'.$file->description.'
	                            <br/>';
	                            $i++;
	                          }
	                        }
	                    }
	                    $open_tasks.= $fileoutput;
	                  $open_tasks.='</div>
	                </div>
	                <div style="width:100%;padding:10px">
	                  <div style="width:20%">Completion Files:</div>
	                  <div style="margin-left:20%;width:80%;padding-top:-20px">';
	                    $fileoutput ='';
	                    if(count($taskfiles))
	                    {
	                      foreach($taskfiles as $file)
	                      {
	                        if($file->status == 2)
	                        {
	                          $fileoutput.=$file->filename.'<br/>';
	                        }
	                      }
	                    }
	                    if(count($tasknotepad))
	                    {
	                      foreach($tasknotepad as $note)
	                      {
	                        if($note->status == 2)
	                        {
	                          $fileoutput.=$note->filename.'<br/>';
	                        }
	                      }
	                    }
	                    if(count($taskinfiles))
	                    {
	                      $i=1;
	                        foreach($taskinfiles as $infile)
	                        {
	                          if($infile->status == 2)
	                          {
	                            if($i == 1) { $fileoutput.='Linked Infiles:<br/>'; }
	                            $file = DB::table('in_file')->where('id',$infile->infile_id)->first();
	                            $ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
	                            $fileoutput.=$i.'&nbsp;'.date('d-M-Y', strtotime($file->data_received)).'&nbsp;'.$file->description.'
	                            <br/>';
	                            $i++;
	                          }
	                        }
	                    }
	                    $open_tasks.= $fileoutput;
	                  $open_tasks.='</div>
	                </div>
	          </div>
	          		';
	              $filename = $title.'.pdf';
	              $pdf = PDF::loadHTML($open_tasks);
				    $pdf->setPaper('A4', 'portrait');
				    $pdf->save('papers/'.$filename.'');
				    echo $filename;
	}
	public function set_progress_value()
	{
		$task_id = Input::get('task_id');
		$value = Input::get('value');
		$data['progress'] = $value;
		DB::table('taskmanager')->where('id',$task_id)->update($data);
	}
	public function set_avoid_email_taskmanager()
	{
		$task_id = Input::get('task_id');
		$status = Input::get('status');
		$data['avoid_email'] = $status;
		DB::table('taskmanager')->where('id',$task_id)->update($data);
	}
	public function get_task_redline_notification()
	{
		if(Session::has('taskmanager_user'))
		{
			$userid = Session::get('taskmanager_user');
			$user_tasks = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '0' AND (`allocated_to` = '".$userid."' OR `author` = '".$userid."' OR `allocated_to` = '0')");
			$ids = '';
			if(count($user_tasks))
			{
				foreach($user_tasks as $task)
				{
					if($task->author == $userid)
					{
						if($task->author_spec_status == "1")
						{
							if($ids == "")
							{
								$ids = $task->id;
							}
							else{
								$ids = $ids.','.$task->id;
							}
						}
					}
					else{
						if($task->allocated_to == $userid)
						{
							if($task->allocated_spec_status == "1")
							{
								if($ids == "")
								{
									$ids = $task->id;
								}
								else{
									$ids = $ids.','.$task->id;
								}
							}
						}
					}
				}
			}
			echo $ids;
		}
	}
	public function request_update()
	{
		$task_id = Input::get('task_id');
		$author = Input::get('author');
		$allocated = Input::get('allocated_to');

		$task_details = DB::table('taskmanager')->where('id',$task_id)->first();
		if($task_details->allocated_to != 0)
    	{
			$task_specifics_val = strip_tags($task_details->task_specifics);
	    	if($task_details->subject == "")
	    	{
	    		$subject_cls = substr($task_specifics_val,0,30);
	    	}
	    	else{
	    		$subject_cls = $task_details->subject;
	    	}

			$allocated = Session::get('taskmanager_user');
			$user_details = DB::table('user')->where('user_id',$author)->first();
			$message = '<spam style="color:#006bc7">****<strong>'.$user_details->lastname.' '.$user_details->firstname.'</strong>  has requested an Update of task <strong>'.$task_details->taskid.'</strong> - '.$subject_cls.'****</spam>';

			$dataval['task_id'] = $task_id;
			$dataval['from_user'] = $author;
			$dataval['created_date'] = date('Y-m-d');
			$dataval['message'] = $message;
			$dataval['status'] = 4;

			DB::table('taskmanager_specifics')->insert($dataval);

	    	$author_details = DB::table('user')->where('user_id',$author)->first();
	    	$author_email = $author_details->email;
    	
    		$allocated_details = DB::table('user')->where('user_id',$task_details->allocated_to)->first();
    		$dataemail['allocated_name'] = $allocated_details->lastname.' '.$allocated_details->firstname;
    		$allocated_email = $allocated_details->email;

    		if(Session::has('taskmanager_user'))
		    {
		    	$sess_user = Session::get('taskmanager_user');
		    	if($sess_user == $task_details->author)
		    	{
		    		$dataupdate_spec_status['author_spec_status'] = 0;
					$dataupdate_spec_status['allocated_spec_status'] = 1;
		    	}
		    	else{
		    		$dataupdate_spec_status['author_spec_status'] = 1;
					$dataupdate_spec_status['allocated_spec_status'] = 0;
		    	}
		    	DB::table('taskmanager')->where('id',$task_id)->update($dataupdate_spec_status);
		    }

			$task_specifics = '';
			$specifics_first = DB::table('taskmanager_specifics')->where('task_id',$task_id)->orderBy('id','asc')->first();
			if(count($specifics_first))
			{
				$task_specifics.=$specifics_first->message;
			}
			$task_specifics.= PHP_EOL.$task_details->task_specifics;
			$specifics = DB::table('taskmanager_specifics')->where('task_id',$task_id)->where('id','!=',$specifics_first->id)->get();
			if(count($specifics))
			{
				foreach($specifics as $specific)
				{
					$task_specifics.=PHP_EOL.$specific->message;
				}
			}

			$task_specifics = strip_tags($task_specifics);

			$uploads = 'papers/task_specifics.txt';
			$myfile = fopen($uploads, "w") or die("Unable to open file!");
			fwrite($myfile, $task_specifics);
			fclose($myfile);

			$dataemail['logo'] = URL::to('assets/images/easy_payroll_logo.png');
			$dataemail['subject'] = $subject_cls;
			$dataemail['author_name'] = $author_details->lastname.' '.$author_details->firstname;
			$dataemail['taskid'] = $task_details->taskid;

			$subject_email = 'Task Manager - Update Request for Task: '.$subject_cls;
			$contentmessage = view('emails/task_manager/task_manager_request_update', $dataemail)->render();

			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);
			$contentmessage = str_replace("â€“", "-", $contentmessage);

			$email = new PHPMailer();
			$email->SetFrom('info@gbsco.ie');
			$email->Subject   = $subject_email;
			$email->Body      = $contentmessage;
			$email->AddCC('tasks@gbsco.ie');
			$email->IsHTML(true);
			$email->AddAddress( $allocated_email );
			$email->AddAttachment( $uploads , 'task_specifics.txt' );
			$email->Send();	

			echo "1";
    	}	
    	else{
    		echo "0";
    	}
	}
	public function change_task_name_taskmanager()
	{
		$taskid = Input::get('taskid');
		$tasktype = Input::get('tasktype');
		$data['task_type'] = $tasktype;
		DB::table('taskmanager')->where('id',$taskid)->update($data);
		$details = DB::table('time_task')->where('id',$tasktype)->first();
		echo $details->task_name;
	}
	public function park_task_complete()
	{
		$taskid = Input::get('task_id');
		$task = DB::table('taskmanager')->where('id',$taskid)->first();
		$park_date = Input::get('park_date');

		$new_park_date = DateTime::createFromFormat('d-M-Y', $park_date);
		$new_park_date = $new_park_date->format('Y-m-d');

		$data['status'] = 2;
		$data['park_date'] = $new_park_date;

		if(Session::has('taskmanager_user'))
	    {
	    	$sess_user = Session::get('taskmanager_user');
	    	if($sess_user == $task->author)
	    	{
	    		$data['author_spec_status'] = 0;
				$data['allocated_spec_status'] = 1;
	    	}
	    	else{
	    		$data['author_spec_status'] = 1;
				$data['allocated_spec_status'] = 0;
	    	}
	    }
		DB::table('taskmanager')->where('id',$taskid)->update($data);

		$user = Session::get('taskmanager_user');
		$details = DB::table('user')->where('user_id',$user)->first();
		$username = $details->lastname.' '.$details->firstname;

		$message = '<spam style="color:#006bc7">####<strong>'.$username.'</strong> has parked this task <strong>'.date('d-M-Y').'</strong> until <strong>'.$park_date.'</strong>####</spam>';

		$data_specifics['from_user'] = $user;
		$data_specifics['to_user'] = 0;

		$data_specifics['task_id'] = $taskid;
		$data_specifics['message'] = $message;
		$data_specifics['allocated_date'] = date('Y-m-d H:i:s');
		$data_specifics['park_date'] = $new_park_date;
		$data_specifics['status'] = 0;

		DB::table('taskmanager_specifics')->insert($data_specifics);
	}
	public function park_task_incomplete()
	{
		$taskid = Input::get('task_id');
		$task = DB::table('taskmanager')->where('id',$taskid)->first();

		$data['status'] = 0;
		if(Session::has('taskmanager_user'))
	    {
	    	$sess_user = Session::get('taskmanager_user');
	    	if($sess_user == $task->author)
	    	{
	    		$data['author_spec_status'] = 0;
				$data['allocated_spec_status'] = 1;
	    	}
	    	else{
	    		$data['author_spec_status'] = 1;
				$data['allocated_spec_status'] = 0;
	    	}
	    }
		DB::table('taskmanager')->where('id',$taskid)->update($data);

		$user = Session::get('taskmanager_user');
		$details = DB::table('user')->where('user_id',$user)->first();
		$username = $details->lastname.' '.$details->firstname;

		$message = '<spam style="color:#006bc7">####<strong>'.$username.'</strong> has activated this task on <strong>'.date('d-M-Y').'</strong>####</spam>';

		$data_specifics['from_user'] = $user;
		$data_specifics['to_user'] = 0;

		$data_specifics['task_id'] = $taskid;
		$data_specifics['message'] = $message;
		$data_specifics['allocated_date'] = date('Y-m-d H:i:s');
		$data_specifics['status'] = 0;

		DB::table('taskmanager_specifics')->insert($data_specifics);
	}
	public function reactivate_park_task()
	{
		$userid = Input::get('userid');
		$date = date('Y-m-d');

		$park_task = DB::select("SELECT * FROM `taskmanager` WHERE `status` = '2' AND `due_date` = '".$date."' AND (`allocated_to` = '".$userid."' OR `allocated_to` = '0' OR `author` = '".$userid."')");
		if(count($park_task))
		{
			foreach($park_task as $task)
			{
				$taskid = $task->id;
				$data['status'] = 0;

				if(Session::has('taskmanager_user'))
			    {
			    	$sess_user = Session::get('taskmanager_user');
			    	if($sess_user == $task->author)
			    	{
			    		$data['author_spec_status'] = 0;
						$data['allocated_spec_status'] = 1;
			    	}
			    	else{
			    		$data['author_spec_status'] = 1;
						$data['allocated_spec_status'] = 0;
			    	}
			    }
				DB::table('taskmanager')->where('id',$taskid)->update($data);

				$user = Session::get('taskmanager_user');
				$details = DB::table('user')->where('user_id',$user)->first();
				$username = $details->lastname.' '.$details->firstname;

				$message = '<spam style="color:#006bc7">####<strong>'.$username.'</strong> has activated this task on <strong>'.date('d-M-Y').'</strong>####</spam>';

				$data_specifics['from_user'] = $user;
				$data_specifics['to_user'] = 0;

				$data_specifics['task_id'] = $taskid;
				$data_specifics['message'] = $message;
				$data_specifics['allocated_date'] = date('Y-m-d H:i:s');
				$data_specifics['status'] = 0;

				DB::table('taskmanager_specifics')->insert($data_specifics);



			}
		}

		

	      $dataval['park_status'] = 1;
	      DB::table('user')->where('user_id',$userid)->update($dataval);
	}
}


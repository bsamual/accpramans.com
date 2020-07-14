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
class MessageusController extends Controller {
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
	public function directmessaging()
	{
		if(isset($_GET['message_id']))
		{
			$message_details = DB::table('messageus')->where('id',$_GET['message_id'])->first();
			$subject = $message_details->subject;
			$message_body = $message_details->message;
		}
		else{
			$subject = "";
			$message_body = "";
		}
		Session::forget('message_file_attach_add');
		return view('user/messageus/directmessaging',array('subject' => $subject,'message_body' => $message_body));
	}
	public function directmessaging_page_two()
	{
		$clients = DB::table('cm_clients')->get();
		Session::forget('message_file_attach_add');
		return view('user/messageus/directmessaging_page_two',array('clients' => $clients));
	}
	public function directmessaging_page_three()
	{
		$message_id = $_GET['message_id'];
		$message_details = DB::table('messageus')->where('id',$message_id)->first();
		Session::forget('message_file_attach_add');
		return view('user/messageus/directmessaging_page_three',array('message_details' => $message_details));
	}
	public function messageus_groups()
	{
		$groups = DB::table('messageus_groups')->get();
		$clients = DB::table('cm_clients')->get();
		Session::forget('message_file_attach_add');
		return view('user/messageus/messageus_groups',array('groups' => $groups,'clients' => $clients));
	}
	public function messageus_saved_messages()
	{
		$messages = DB::table('messageus')->where('draft_status',1)->get();
		Session::forget('message_file_attach_add');
		return view('user/messageus/messageus_saved_messages',array('messages' => $messages));
	}
	public function messageus_upload_images_add()
	{
		$message_id = Input::get('message_id');
		if($message_id == "")
		{
			$upload_dir = 'uploads/messageus_image';
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

				 $arrayval = array('attachment' => $fname,'url' => $upload_dir,'content' => "");

		 		if(Session::has('message_file_attach_add'))
				{
					$getsession = Session::get('message_file_attach_add');
				}
				else{
					$getsession = array();
				}
				$getsession = array_values($getsession);
				array_push($getsession,$arrayval);

				$sessn=array('message_file_attach_add' => $getsession);
				Session::put($sessn);

				move_uploaded_file($tmpFile,$filename);
				$key = count($getsession) - 1;
			 	echo json_encode(array('id' => 0,'attachment' => $filename,'filename' => $fname,'file_id' => $key,'message_id' => 0));
			}
		}
		else{
			$upload_dir = 'uploads/messageus_image';
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
			$upload_dir = $upload_dir.'/'.base64_encode($message_id);
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

				 $data['filename'] = $fname;
				 $data['url'] = $upload_dir;
				 $data['content'] = "";
				 $data['message_id'] = $message_id;

				$id = DB::table('messageus_files')->insertGetid($data);
				move_uploaded_file($tmpFile,$filename);
				
			 	echo json_encode(array('id' => $id,'attachment' => $filename,'filename' => $fname,'file_id' => $id,'message_id' =>$message_id));
			}
		}
	}
	public function messageus_remove_dropzone_attachment()
	{
		$file_id = $_POST['file_id'];
		if(Session::has('message_file_attach_add'))
		{
			$files = Session::get('message_file_attach_add');
			unset($files[$file_id]);
			$getsession = array_values($files);

			$sessn=array('message_file_attach_add' => $getsession);
			Session::put($sessn);
			$output = '';
			if(count($getsession))
			{
				foreach($getsession as $key => $sess)
				{
					$output.="<div class='messageus_attachment' style='width:100%'><a href='".URL::to('/')."/".$sess['url']."/".$sess['attachment']."' class='messageus_attachment_a' download>".$sess['attachment']."</a> <a href='javascript:' class='fa fa-trash remove_dropzone_attach_add' data-task='".$key."'></a> <a href='javascript:' class='fa fa-text-width add_notes_attachment' data-task='".$key."'></a>
						<a href='javascript:' class='fa fa-ellipsis-h attach_content attach_content_".$key."' title='' style='display:none'></a></div>";
				}
			}
			echo $output;
		}
	}
	public function messageus_add_comment_to_attachment()
	{
		$fileid = Input::get('fileid');
		$content = Input::get('textarea');
		$type = Input::get('type');
		if($type == "0")
		{
			if(Session::has('message_file_attach_add'))
			{
				$sessionvalue = Session::get('message_file_attach_add');
				$sessionvalue[$fileid]['content'] = $content;
				$sessn=array('message_file_attach_add' => $sessionvalue);
				Session::put($sessn);
			}
		}
		else{
			$data['content'] = $content;
			DB::table('messageus_files')->where('id',$fileid)->update($data);
		}
	}
	public function get_attachment_notes()
	{
		$fileid = Input::get('fileid');
		$type = Input::get('type');

		if($type == "0")
		{
			$sessionvalue = Session::get('message_file_attach_add');
			echo $sessionvalue[$fileid]['content'];
		}
		else{
			$attachment = DB::table('messageus_files')->where('id',$fileid)->first();
			echo $attachment->content;
		}
	}
	public function message_remove_dropzone_attachment()
	{
		$file_id = $_POST['file_id'];
		DB::table('messageus_files')->where('id',$file_id)->delete();
	}
	public function save_message_page_one()
	{
		$message_id = Input::get('message_id');
		$subject = Input::get('subject');
		$message_body = Input::get('message_body');
		if($message_id == "")
		{
			$data['subject'] = $subject;
			$data['message'] = $message_body;
			$message_id = DB::table('messageus')->insertGetid($data);

			if(Session::has('message_file_attach_add'))
			{
				$files = Session::get('message_file_attach_add');
				if(count($files))
				{
					foreach($files as $file)
					{
						$datafiles['message_id'] = $message_id;
						$datafiles['url'] = $file['url'];
						$datafiles['filename'] = $file['attachment'];
						$datafiles['content'] = $file['content'];
						DB::table('messageus_files')->insert($datafiles);
					}
				}
			}
			echo $message_id;
		}
		else{
			$data['subject'] = $subject;
			$data['message'] = $message_body;
			DB::table('messageus')->where('id',$message_id)->update($data);
			echo $message_id;
		}
	}
	public function save_message_page_two()
	{
		$message_id = Input::get('message_id');
		$client_ids = Input::get('client_ids');
		$primary_emails = Input::get('primary_emails');
		$secondary_emails = Input::get('secondary_emails');
		$group_type = Input::get('group_type');
		$group_id = Input::get('group_id');

		$data['client_ids'] = $client_ids;
		$data['primary_emails'] = $primary_emails;
		$data['secondary_emails'] = $secondary_emails;
		$data['group_type'] = $group_type;
		if($group_type == "4")
		{
			$data['group_id'] = $group_id;
		}

		DB::table('messageus')->where('id',$message_id)->update($data);
	}
	public function send_message_later()
	{
		$message_id = Input::get('message_id');
		$data['draft_status'] = 1;
		$data['date_saved'] = date('Y-m-d H:i:s');
		DB::table('messageus')->where('id',$message_id)->update($data);
	}
	public function send_message_now()
	{
		$message_id = Input::get('message_id');
		$message_details = DB::table('messageus')->where('id',$message_id)->first();

		$from = Input::get('from');
		$email = Input::get('email');
		$to = trim($email);
		
		$datamessage['message_from'] = $from;
		$datamessage['date_sent'] = date('Y-m-d H:i:s');
		$datamessage['status'] = 1;
		$datamessage['draft_status'] = 0;
		DB::table('messageus')->where('id',$message_id)->update($datamessage);

		$user_details = DB::table('user')->where('user_id',$from)->first();
		$user_email = $user_details->email;
		
		$dataemail['logo'] = URL::to('assets/images/easy_payroll_logo.png');
		$dataemail['message'] = $message_details->message;
		$subject_email = $message_details->subject;

		$contentmessage = view('emails/messageus/create_messageus_email', $dataemail)->render();
		
		$email = new PHPMailer();
		$email->SetFrom($user_email);
		$email->Subject   = $subject_email;
		$email->Body      = $contentmessage;
		$email->AddCC('tasks@gbsco.ie');
		$email->IsHTML(true);
		$email->AddAddress( $to );
		$files = DB::table('messageus_files')->where('message_id',$message_id)->get();
		if(count($files))
		{
			foreach($files as $file)
			{
				$email->AddAttachment( $file->url.'/'.$file->filename , $file->filename );
			}
		}
		$email->Send();
	}
	public function create_group_name()
	{
		$grp_name = Input::get('grp_name');
		$data['group_name'] = $grp_name;
		$id = DB::table('messageus_groups')->insertGetid($data);

		$data['group_tr'] =  '<tr class="group_tr highlight_group" id="group_tr_'.$id.'" data-element="'.$id.'">
                      <td class="group_td" style="border-right:1px solid #000;width:60%">'.$grp_name.'</td>
                      <td class="group_td" style="width:40%;text-align:right">0</td>
                    </tr>';
        $data['group_name'] = $grp_name;
        $data['group_id'] = $id;
        echo json_encode($data);
	}
	public function select_messageus_group()
	{
		$group_id = Input::get('group_id');
		$group_details = DB::table('messageus_groups')->where('id',$group_id)->first();

		if($group_id == "1")
		{
			$data['group_name'] = $group_details->group_name;
	        $data['client_ids'] = $group_details->client_ids;
	        $selected = '';
	        $explode = explode(",",$group_details->client_ids);
	        if($group_details->client_ids != "")
	        {
	        	foreach($explode as $exp)
	        	{
	        		$client_details = DB::table('cm_clients')->where('client_id',$exp)->first();
	        		$get_week_status = DB::table('task')->where('task_week',$group_details->last_week)->where('client_id',$exp)->where('task_status',0)->count();
					$get_month_status = DB::table('task')->where('task_month',$group_details->last_month)->where('client_id',$exp)->where('task_status',0)->count();

					$total_count = $get_week_status + $get_month_status;

					if($total_count == 0)
					{
						$clss = 'selected_complete';
					}
					else{
						$clss = 'selected_donot_complete';
					}

	        		if($client_details->active == "2") { $cls= 'selected_inactive'; } 
	        		else { $cls = 'selected_active'; }
	        		$selected.='<tr class="selected_tr '.$clss.' '.$cls.'" id="selected_tr_'.$exp.'" data-element="'.$exp.'">
	        			<td class="selected_td"><input type="checkbox" name="client_include" class="client_include" value="'.$exp.'"><label>&nbsp;</label></td>
	        			<td class="selected_td">'.$exp.'</td>
	        			<td class="selected_td">'.$client_details->company.'</td>
	        			<td class="selected_td">'.$client_details->email.'</td>
	        		</tr>';
	        	}
	        }
	        $data['selected_clients'] = $selected;
	        echo json_encode($data);
		}
		else{
			$data['group_name'] = $group_details->group_name;
	        $data['client_ids'] = $group_details->client_ids;
	        $selected = '';
	        $explode = explode(",",$group_details->client_ids);
	        if($group_details->client_ids != "")
	        {
	        	foreach($explode as $exp)
	        	{
	        		$client_details = DB::table('cm_clients')->where('client_id',$exp)->first();
	        		if($client_details->active == "2") { $cls= 'selected_inactive'; } 
	        		else { $cls = 'selected_active'; }
	        		$selected.='<tr class="selected_tr '.$cls.'" id="selected_tr_'.$exp.'" data-element="'.$exp.'">
	        			<td class="selected_td"><input type="checkbox" name="client_include" class="client_include" value="'.$exp.'"><label>&nbsp;</label></td>
	        			<td class="selected_td">'.$exp.'</td>
	        			<td class="selected_td">'.$client_details->company.'</td>
	        			<td class="selected_td">'.$client_details->email.'</td>
	        		</tr>';
	        	}
	        }
	        $data['selected_clients'] = $selected;
	        echo json_encode($data);
		}
	}
	public function add_selected_member_to_group()
	{
		$grp_id = Input::get('grp_id');
		$client_ids = Input::get('client_ids');
		$selected = '';
		$explode = explode(",",$client_ids);
        if($client_ids != "")
        {
        	foreach($explode as $exp)
        	{
        		$client_details = DB::table('cm_clients')->where('client_id',$exp)->first();
        		if($client_details->active == "2") { $cls= 'selected_inactive'; } 
        		else { $cls = 'selected_active'; }
        		$selected.='<tr class="selected_tr '.$cls.'" id="selected_tr_'.$exp.'" data-element="'.$exp.'">
        			<td class="selected_td"><input type="checkbox" name="client_include" class="client_include" value="'.$exp.'"><label>&nbsp;</label></td>
        			<td class="selected_td">'.$exp.'</td>
        			<td class="selected_td">'.$client_details->company.'</td>
        			<td class="selected_td">'.$client_details->email.'</td>
        		</tr>';
        	}
        }
        $grp_details = DB::table('messageus_groups')->where('id',$grp_id)->first();
        $ids = $grp_details->client_ids;
        if($ids == "")
        {
        	$data['client_ids'] = $client_ids;
        }
        else{
        	$data['client_ids'] = $ids.','.$client_ids;
        }
        DB::table('messageus_groups')->where('id',$grp_id)->update($data);
        echo $selected;
	}
	public function remove_selected_member_to_group()
	{
		$grp_id = Input::get('grp_id');
		$client_ids = explode(",",Input::get('client_ids'));
		$grp_details = DB::table('messageus_groups')->where('id',$grp_id)->first();
        $ids = explode(",",$grp_details->client_ids);
        if($client_ids != "")
        {
        	foreach($client_ids as $client_id)
        	{
        		if(in_array($client_id, $ids))
        		{
        			$key = array_search($client_id, $ids);
        			unset($ids[$key]);
        		}
        	}
        }
        $implode = implode(",",$ids);
        $data['client_ids'] = $implode;
        DB::table('messageus_groups')->where('id',$grp_id)->update($data);
	}
	public function delete_messageus_groups()
	{
		$grp_id = Input::get('grp_id');
		DB::table('messageus_groups')->where('id',$grp_id)->delete();
	}
	public function delete_saved_message()
	{
		$message_id = $_GET['message_id'];
		DB::table('messageus')->where('id',$message_id)->delete();
		return redirect::back()->with('message', 'Message Deleted successfully');
	}
	public function choose_messageus_from()
	{
		$message_id = Input::get('message_id');
		$from = Input::get('from');

		$datamessage['message_from'] = $from;
		DB::table('messageus')->where('id',$message_id)->update($datamessage);
	}
	public function show_messageus_sample_screen()
	{
		$message_id = Input::get('message_id');
		$message_details = DB::table('messageus')->where('id',$message_id)->first();
		if($message_details->source == "")
		{
			$output = '<div class="row" style="background: #c7c7c7;padding:20px">
		        <div class="col-md-12">
		          <h5 style="font-weight:800">Message Summary:</h5>
		        </div>
		        <div class="col-md-12" style="margin-top:10px">
		          <h5 style="font-weight:800">Message Subject: </h5>
		          <input type="text" name="message_subject" class="form-control message_subject" value="'.$message_details->subject.'" disabled style="background: #fff !important">
		        </div>
		        <div class="col-md-12" style="margin-top:20px">
		          <h5 style="font-weight:800">Message Body: </h5>
		          <div style="width:100%;background: #fff;min-height:300px;padding:10px">
		            '.$message_details->message.'
		          </div>
		        </div>
		        <div class="col-md-12" style="margin-top:20px">
		          <h5 style="font-weight:800">Attached Files: </h5>';
		          $fileoutput = '';
		          $files = DB::table('messageus_files')->where('message_id',$message_id)->get();
		          if(count($files))
		          {
		            foreach($files as $file)
		            {
		              $fileoutput.="<div class='messageus_attachment' style='width:100%'><a href='".URL::to('/')."/".$file->url."/".$file->filename."' class='messageus_attachment_a' download>".$file->filename."</a></div>";
		            }
		          }
		          $output.=$fileoutput;
		        $output.='</div>
		        <div class="col-md-12" style="margin-top:20px">
		          <h5 style="font-weight:800">Clients: </h5>';
		          $clientoutput = '';
		          $clients = explode(",",$message_details->client_ids);
		          if(count($clients))
		          {
		            foreach($clients as $client)
		            {
		              $client_details = DB::table('cm_clients')->where('client_id',$client)->first();
		              $clientoutput.="<div class='messageus_attachment' style='width:100%'>".$client_details->company."</div>";
		            }
		          }
		          $output.=$clientoutput;
		        $output.='</div>
		    </div>';
		}
		else{
			$output = '<div class="row" style="background: #c7c7c7;padding:20px">
		        <div class="col-md-12">
		          <h5 style="font-weight:800">Message Summary:</h5>
		        </div>
		        <div class="col-md-12" style="margin-top:10px">
		          <h5 style="font-weight:800">Message Subject: </h5>
		          <input type="text" name="message_subject" class="form-control message_subject" value="'.$message_details->subject.'" disabled style="background: #fff !important">
		        </div>
		        <div class="col-md-12" style="margin-top:20px">
		          <h5 style="font-weight:800">Message Body: </h5>
		          <div id="message_body" style="width:100%;background: #fff;min-height:300px;padding:10px;float: left;">
		            '.$message_details->message.'
		          </div>
		        </div>
		        <div class="col-md-12" style="margin-top:20px">';
		          $fileoutput = '';
		          if($message_details->attachments != "")
		          {
		          	$fileoutput.='<h5 style="font-weight:800">Attached Files: </h5>';
		          	$attachments = explode("||",$message_details->attachments);
		          	if(count($attachments))
		          	{
		          		foreach($attachments as $attach)
		          		{
		          			$exp_attach = explode("/",$attach);
		          			$fileoutput.="<div class='messageus_attachment' style='width:100%'><a href='".URL::to('/')."/".$attach."' class='messageus_attachment_a' download>".end($exp_attach)."</a></div>";
		          		}
		          	}
		          }
		          $output.=$fileoutput;
		        $output.='</div>
		        <div class="col-md-12" style="margin-top:20px">
		          <h5 style="font-weight:800">Clients: </h5>';
		          $clientoutput = '';
		          $get_clients = DB::table('messageus')->where('message_id',$message_details->message_id)->get();
		          if(count($get_clients))
		          {
		          	foreach($get_clients as $clients)
		          	{
		          		$client_details = DB::table('cm_clients')->where('client_id',$clients->client_ids)->first();
		          		if(count($client_details))
		          		{
		          			$clientoutput.="<div class='messageus_attachment' style='width:100%'>".$client_details->company."</div>";
		          		}
		          	}
		          }
		          $output.=$clientoutput;
		        $output.='</div>
		      </div>';
		}
      echo $output;
	}
	public function update_pms_groups()
	{
		$current_week = DB::table('week')->orderBy('week_id','desc')->first();
		$current_month = DB::table('month')->orderBy('month_id','desc')->first();

		$tasks = DB::table('task')->where('task_week',$current_week->week_id)->orWhere('task_month',$current_month->month_id)->groupBy('client_id')->get();
		$client_ids = '';
		$selected = '';
		if(count($tasks))
		{
			foreach($tasks as $task)
			{
				if($task->client_id != "")
				{
					$get_week_status = DB::table('task')->where('task_week',$current_week->week_id)->where('client_id',$task->client_id)->where('task_status',0)->count();
					$get_month_status = DB::table('task')->where('task_month',$current_month->month_id)->where('client_id',$task->client_id)->where('task_status',0)->count();

					$total_count = $get_week_status + $get_month_status;
					$client_details = DB::table('cm_clients')->where('client_id',$task->client_id)->first();
					if($total_count == 0)
					{
						$clss = 'selected_complete';
					}
					else{
						$clss = 'selected_donot_complete';
					}
					
					if($client_details->active == "2")
					{
						$cls = 'selected_inactive';
					}
					else{
						$cls = 'selected_active';
					}

					$selected.='<tr class="selected_tr '.$clss.' '.$cls.'" id="selected_tr_'.$task->client_id.'" data-element="'.$task->client_id.'">
						<td class="selected_td"><input type="checkbox" name="client_include" class="client_include" value="'.$task->client_id.'"><label>&nbsp;</label></td>
	        			<td class="selected_td">'.$task->client_id.'</td>
	        			<td class="selected_td">'.$client_details->company.'</td>
	        			<td class="selected_td">'.$client_details->email.'</td>
	        		</tr>';

	        		if($client_ids == "")
	        		{
	        			$client_ids = $task->client_id;
	        		}
	        		else{
	        			$client_ids = $client_ids.','.$task->client_id;
	        		}
				}
			}
		}

		$data['client_ids'] = $client_ids;
		$data['last_week'] = $current_week->week_id;
		$data['last_month'] = $current_month->month_id;
		DB::table('messageus_groups')->where('id',1)->update($data);
		echo json_encode(array("client_ids" => $client_ids, 'selected' => $selected));
	}
}


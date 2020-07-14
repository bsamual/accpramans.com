<?php namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use Session;
use URL;
use PDF;
use Response;
use PHPExcel; 
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use Hash;
use ZipArchive;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class InfileController extends Controller {



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

	public function __construct()
	{
		$this->middleware('userauth');
		date_default_timezone_set("Europe/Dublin");
	}



	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */

	public function infile(){	
		if(Session::has('notepad_attach_add'))
		{
			Session::forget("notepad_attach_add");
		}
		if(Session::has('file_attach_add'))
		{
			Session::forget("file_attach_add");
		}
		$infiles = DB::table('in_file')->get();
		$userlist = DB::table('user')->where('user_status', 0)->get();
		$internal = 0;
		return view('user/infile/infile', array('title' => 'InFile', 'infiles' => $infiles, 'userlist' => $userlist, 'internal_search_check' => $internal));
	}
	public function infile_userupdate(){		
		$id = Input::get('id');	
		$data['complete_by'] = Input::get('users');
		DB::table('in_file')->where('id', $id)->update($data);		
	}
	public function infile_completedate(){
		$id = Input::get('id');	
		$date = date('Y-m-d', strtotime(Input::get("date"))); //explode('-',Input::get('date'));	
		$data['complete_date'] = $date;
		DB::table('in_file')->where('id', $id)->update($data);
	}

	public function in_file_statusupdate(){
		$id = Input::get('id');
		

		$data['status'] = Input::get('status');
		DB::table('in_file')->where('id', $id)->update($data);

	}

	public function in_file_showincomplete(){

		$value = Input::get('value');


		if($value == 0){
			$infiles = DB::table('in_file')->get();
		}
		else{
			$infiles = DB::table('in_file')->where('status', 0)->get();
		}

		$userlist = DB::table('user')->where('user_status', 0)->get();
		$i=1;
      	$output='';
      	if(count($infiles)){
	        foreach ($infiles as $file) {

	          if($file->status == 0){
                $staus = 'fa-check'; 
                $statustooltip = 'Complete Infile';
                $disable = '';
                $disable_class = '';
                $color='';
              } 
              else{
                $staus = 'fa-times';
                $statustooltip = 'InComplete Infile';
                $disable = 'disabled';
                $disable_class = 'disable_class';
                $color = 'style="color:#f00;"';
              }

	          $companydetails = DB::table('cm_clients')->where('client_id', $file->client_id)->first();

	          $attachments = DB::table('in_file_attachment')->where('file_id',$file->id)->where('status',0)->where('notes_type', 0)->get();

	          $downloadfile='';

	          if(count($attachments)){                        
                foreach($attachments as $attachment){

                    $downloadfile.= '<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disable_class.'"><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                }
              }
              else{
                $downloadfile ='';
              }

              if(count($attachments)){
                $deleteall = '<i class="fa fa-minus-square delete_all_image '.$disable_class.'" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Delete All Attachments"></i>';
              }
              else{
                $deleteall = '';
              }



              $notes_attachments = DB::table('in_file_attachment')->where('file_id',$file->id)->where('status',0)->where('notes_type', 1)->get();

              $download_notes='';

              if(count($notes_attachments)){                        
                foreach($notes_attachments as $attachment){

                    $download_notes.= '<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disable_class.'"><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                }
              }
              else{
                $download_notes ='';
              }

              if(count($notes_attachments)){
                $delete_notes_all = '<i class="fa fa-minus-square delete_all_notes '.$disable_class.'" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Delete All Attachments"></i>
                <i class="fa fa-download download_all_notes" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments"></i>
                ';
              }
              else{
                $delete_notes_all = '';
              }


              $attach_notes_only = DB::table('in_file_attachment')->where('file_id',$file->id)->where('status',1)->get();

              $notes_only='';

              if(count($attach_notes_only)){                        
                foreach($attach_notes_only as $attachment){

                    $notes_only.= '<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disable_class.'"><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                }
              }
              else{
                $notes_only ='';
              }

              if(count($attach_notes_only)){
                $delete_notes_only = '<i class="fa fa-minus-square delete_all_notes_only '.$disable_class.'" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Delete All Attachments"></i>
                <i class="fa fa-download download_all_notes_only" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments"></i>';
              }
              else{
                $delete_notes_only = '';
              }




              $userdrop='';

              if(count($userlist)){
                foreach ($userlist as $user) {
                  if($user->user_id == $file->complete_by ){ $selected = 'selected';} else{$selected = '';}
                  $userdrop.='<option value="'.$user->user_id.'" '.$selected.'>'.$user->firstname.'</option>';
                }
              }

	          
              $complete_date = ($file->complete_date != '0000-00-00')?date('d-M-Y', strtotime($file->complete_date)):'';

              $hard_files = ($file->hard_files != 0)?'YES':'NO';
	      $output.='
	        <tr id="infile_'.$file->id.'">
	          <td '.$color.' valign="top" >'.$i.'</td>
	          <td '.$color.' valign="top">'.$companydetails->company.'</td>
	          <td '.$color.' valign="top">Received:'.date('d-M-Y', strtotime($file->data_received)).'<br/>Added: '.date('d-M-Y', strtotime($file->date_added)).'</td>
	          <td '.$color.'>
	          
	          '.$downloadfile.'
	          <i class="fa fa-plus faplus '.$disable_class.'" style="margin-top:10px" aria-hidden="true" title="Add Attachment"></i>              
	          '.$deleteall.'<br/><br/>

	          <div style="width:100%; height:auto; float:left; padding-bottom:10px; border-bottom:1px solid #000;color: #0300c1;">Notes:</div>

	          <div class="clearfix"></div>

	          '.$download_notes.'
	          <i class="fa fa-pencil-square fanotepad '.$disable_class.'" style="margin-top:10px;" aria-hidden="true" title="Add Notepad"></i>
	          '.$delete_notes_all.'




	              <div class="img_div" style="z-index:9999999">
	                <form name="image_form" id="image_form" action="'.URL::to('user/infile_image_upload').'" method="post" enctype="multipart/form-data" style="text-align: left;">
	                  <input type="file" name="image_file[]" required class="form-control image_file" value="" multiple>
	                  <input type="hidden" name="hidden_id" value="'.$file->id.'">
	                  <input type="submit" name="image_submit" class="btn btn-sm btn-primary image_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
	                  <spam class="error_files"></spam>
	                </form>
	                <div style="width:100%;text-align:center;margin-top:-10px;margin-bottom:10px;color:#000"><label style="font-weight:800;">OR</label></div>
	                <div class="image_div_attachments">
	                  <form action="'.URL::to('user/infile_upload_images?file_id='.$file->id).'" method="post" enctype="multipart/form-data" class="dropzone" id="image-upload" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                          <input name="_token" type="hidden" value="'.$file->id.'">
                       
                      </form>
	                  <a href="'.URL::to('user/in_file/').'" class="btn btn-sm btn-primary" align="left" style="margin-left:7px; float:left;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
	                </div>
	              </div>


	              <div class="notepad_div" style="z-index:9999; position:absolute">

	                <form name="notepad_form" id="notepad_form" action="'.URL::to('user/infile_notepad_upload').'" method="post" enctype="multipart/form-data" style="text-align: left;">
	                  
	                  <textarea name="notepad_contents" class="form-control notepad_contents" placeholder="Enter Contents"></textarea>
	                  <input type="hidden" name="hidden_id" value="'.$file->id.'">
	                  <input type="submit" name="notepad_submit" class="btn btn-sm btn-primary notepad_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">

	                  <spam class="error_files_notepad"></spam>

	                </form>
	              </div>

	              
	              

	          
	          </td>
	          <td '.$color.' valign="top">
	          <select class="form-control user_select '.$disable_class.'" data-element="'.$file->id.'" '.$disable.'>
	          <option value="">Select User</option>'.$userdrop.'</select>
	          </td>
	          <td '.$color.' valign="top">
	          
	              <label class="input-group datepicker-only-init">
	                  <input type="text" class="form-control complete_date '.$disable_class.'" '.$disable.' value="'.$complete_date.'" data-element="'.$file->id.'" placeholder="Select Date" name="date" style="font-weight: 500;" required="">
	                  <span class="input-group-addon">
	                      <i class="glyphicon glyphicon-calendar"></i>
	                  </span>
	              </label>
	          
	          </td>
	          <td '.$color.' valign="top">

	          '.$notes_only.'
	          <i class="fa fa-pencil-square fanotepad_notes '.$disable_class.'" style="margin-top:10px;" aria-hidden="true" title="Add Notepad"></i>
	          '.$delete_notes_only.'

	          <div class="notepad_div_notes" style="z-index:9999; position:absolute">

	            <form name="notepad_form_notes" id="notepad_form_notes" action="'.URL::to('user/infile_notepad_upload_notes').'" method="post" enctype="multipart/form-data" style="text-align: left;">
	              
	              <textarea name="notepad_contents" class="form-control notepad_contents" placeholder="Enter Contents"></textarea>
	              <input type="hidden" name="hidden_id" value="'.$file->id.'">
	              <input type="submit" name="notepad_submit" class="btn btn-sm btn-primary notepad_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">

	              <spam class="error_files_notepad"></spam>

	            </form>

	          </div>

	          </td>
	          <td '.$color.' align="center" valign="top">'.$hard_files.'</td>
	          <td '.$color.' align="center" valign="top"><a href="javascript:"><i class="fa '.$staus.'" aria-hidden="true" data-element="'.$file->id.'" data-toggle="tooltip" title="'.$statustooltip.'"></i></a></td>
	        </tr>';          
	        $i++;
	        }
	      }
      echo $output;	
	}

	public function infile_client_search()
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
				$company = $single->firstname.' '.$single->surname;
			}
                $data[]=array('value'=>$company.'-'.$single->client_id,'id'=>$single->client_id);

        }
         if(count($data))
             return $data;
        else
            return ['value'=>'No Result Found','id'=>''];

	}

	public function infile_clientsearchselect(){

		$id = Input::get('value');
		$client_details = DB::table('cm_clients')->where('client_id', $id)->first();
		echo json_encode(["client_id" => $client_details->client_id]);

	}


	public function infile_imageupload()
	{
		$total = count($_FILES['image_file']['name']);
		$id = Input::get('hidden_id');	

		for($i=0; $i<$total; $i++) {
		 	$filename = $_FILES['image_file']['name'][$i];
			$data_img = DB::table('in_file')->where('id',$id)->first();
			$tmp_name = $_FILES['image_file']['tmp_name'][$i];
			$upload_dir = 'uploads/infile_image';
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
			$upload_dir = $upload_dir.'/'.base64_encode($data_img->id);
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}

			move_uploaded_file($tmp_name, $upload_dir.'/'.$filename);	
			$data['file_id'] = $data_img->id;
			$data['attachment'] = $filename;
			$data['url'] = $upload_dir;

			DB::table('in_file_attachment')->insert($data);

		}
		$dataval['task_notify'] = 1;
		DB::table('in_file')->where('id',$id)->update($dataval);
		return redirect::back();
	}

	public function infile_upload_images()
	{

		$id = $_GET['file_id'];

		$dataval['task_notify'] = 1;
		DB::table('in_file')->where('id',$id)->update($dataval);
		
		$data_img = DB::table('in_file')->where('id',$id)->first();
		$upload_dir = 'uploads/infile_image';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/'.base64_encode($data_img->id);
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}

		if (!empty($_FILES)) {
			 $tmpFile = $_FILES['file']['tmp_name'];
			 $filename = $upload_dir.'/'.$_FILES['file']['name'];
			 $fname = $_FILES['file']['name'];

		 	move_uploaded_file($tmpFile,$filename);
		 	$data['file_id'] = $data_img->id;
			$data['attachment'] = $fname;
			$data['url'] = $upload_dir;
			

			$id = DB::table('in_file_attachment')->insertGetId($data);
		}
		echo json_encode(array('id' => $id,'filename' => $fname,'file_id' => $data_img->id));
	}

	public function remove_dropzone_attachment()
	{
		$attachment_id = $_POST['attachment_id'];
		$file_id = $_POST['file_id'];
		$check_network = DB::table('in_file_attachment')->where('id',$attachment_id)->first();

		DB::table('in_file_attachment')->where('id',$attachment_id)->delete();
		if($check_network->status == 0)
		{

			$count = DB::table('in_file_attachment')->where('file_id',$check_network->file_id)->where('status',0)->count();

			if($count > 0)

			{

				

			}
			else{

				$dataval['task_notify'] = 0;
				DB::table('in_file')->where('id',$check_network->file_id)->update($dataval);

			}

		}	
	}

	public function infile_delete_image()
	{
		$imgid = Input::get('imgid');			
		$check_network = DB::table('in_file_attachment')->where('id',$imgid)->first();

		DB::table('in_file_attachment')->where('id',$imgid)->delete();
		if($check_network->status == 0)
		{
			$count = DB::table('in_file_attachment')->where('file_id',$check_network->file_id)->where('status',0)->count();
			if($count > 0)
			{

			}
			else{

				$dataval['task_notify'] = 0;
				DB::table('in_file')->where('id',$check_network->file_id)->update($dataval);

			}

		}

	}
	public function infile_delete_all_image()
	{
		$id = Input::get('id');	
		DB::table('in_file_attachment')->where('file_id',$id)->where('status', 0)->where('notes_type', 0)->delete();
		$count = DB::table('in_file_attachment')->where('file_id',$id)->where('status', 0)->count();
		if($count > 0)
		{

		}
		else{
			$dataval['task_notify'] = 0;
			DB::table('in_file')->where('id',$id)->update($dataval);
		}
	}
	public function infile_download_all_image()
	{
		$id = Input::get('id');	
		$details = DB::table('in_file')->where('id',$id)->first();
		$client_details = DB::table('cm_clients')->where('client_id',$details->client_id)->first();
		$files = DB::table('in_file_attachment')->where('file_id',$id)->where('status', 0)->where('notes_type', 0)->get();
		if(count($files))
		{
			$public_dir=public_path();
			$zipFileName = $client_details->company.'_'.$details->date_added.'.zip';
			foreach($files as $file)
			{
	            $zip = new ZipArchive;
	            if ($zip->open($public_dir . '/' . $zipFileName, ZipArchive::CREATE) === TRUE) {
	            	// Add File in ZipArchive
	                $zip->addFile($file->url.'/'.$file->attachment,$file->attachment);
	                // Close ZipArchive     
	                $zip->close();
	            }
			}
			echo $zipFileName;
		}
	}
	public function infile_download_rename_all_image()
	{
		$id = Input::get('id');	
		$details = DB::table('in_file')->where('id',$id)->first();
		$client_details = DB::table('cm_clients')->where('client_id',$details->client_id)->first();
		$files = DB::table('in_file_attachment')->where('file_id',$id)->where('status', 0)->where('notes_type', 0)->get();
		if(count($files))
		{
			$public_dir=public_path();
			$zipFileName = $client_details->company.'_'.$details->date_added.'.zip';
			foreach($files as $file)
			{
				if($file->textval != "" && $file->textstatus == 1)
				{
					$filename = "QuickID_".$file->textval."_".$file->attachment;
				}
				else{
					$filename = $file->attachment;
				}

	            $zip = new ZipArchive;
	            if ($zip->open($public_dir . '/' . $zipFileName, ZipArchive::CREATE) === TRUE) {
	            	// Add File in ZipArchive
	                $zip->addFile($file->url.'/'.$file->attachment,$filename);
	                // Close ZipArchive     
	                $zip->close();
	            }
			}
			echo $zipFileName;
		}
	}
	
	public function infile_notepad_upload()
	{
		$id = Input::get('hidden_id');
		$data_img = DB::table('in_file')->where('id',$id)->first();

		$company_detail = DB::table('cm_clients')->where('client_id', $data_img->client_id)->first();

		$count = DB::table('in_file_attachment')->where('file_id',$id)->where('status',0)->where('notes_type',1)->count();
		$counts = $count + 1;
		$file_name =  preg_replace('/[^A-Za-z0-9 \-]/', '', $company_detail->company); 

		
		$filename = $file_name.'-'.$counts;	

		
		$contents = Input::get('notepad_contents');

		

		$upload_dir = 'uploads/infile_image';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/'.base64_encode($data_img->id);
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}

		$myfile = fopen($upload_dir."/".$filename.".txt", "w") or die("Unable to open file!");
		fwrite($myfile, $contents);
		fclose($myfile);

		$data['file_id'] = $data_img->id;
		$data['attachment'] = $filename.".txt";
		$data['url'] = $upload_dir;
		$data['status'] = 0;
		$data['notes_type'] = 1;
		DB::table('in_file_attachment')->insert($data);

		$dataval['task_notify'] = 1;
		DB::table('in_file')->where('id',$id)->update($dataval);

		return redirect::back();

	}

	public function infile_notepad_upload_notes()
	{
		$id = Input::get('hidden_id');
		$data_img = DB::table('in_file')->where('id',$id)->first();

		$company_detail = DB::table('cm_clients')->where('client_id', $data_img->client_id)->first();

		$count = DB::table('in_file_attachment')->where('file_id',$id)->where('status',1)->where('notes_type',1)->count();
		$counts = $count + 1;
		$file_name =  preg_replace('/[^A-Za-z0-9 \-]/', '', $company_detail->company); 

		
		$filename = $file_name.' - infile - '.$counts;	

		
		$contents = Input::get('notepad_contents');

		

		$upload_dir = 'uploads/infile_image';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		
		$upload_dir = $upload_dir.'/'.base64_encode($data_img->id);
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}

		$myfile = fopen($upload_dir."/".$filename.".txt", "w") or die("Unable to open file!");
		fwrite($myfile, $contents);
		fclose($myfile);

		$data['file_id'] = $data_img->id;
		$data['attachment'] = $filename.".txt";
		$data['url'] = $upload_dir;
		$data['status'] = 1;
		$data['notes_type'] = 1;
		DB::table('in_file_attachment')->insert($data);

		return redirect::back();

	}

	public function infile_delete_all_notes_only()
	{
		$id = Input::get('id');	

		DB::table('in_file_attachment')->where('file_id',$id)->where('status', 1)->where('notes_type', 1)->delete();
	}
	public function infile_download_all_notes_only()
	{
		$id = Input::get('id');	

		$details = DB::table('in_file')->where('id',$id)->first();
		$client_details = DB::table('cm_clients')->where('client_id',$details->client_id)->first();
		$files = DB::table('in_file_attachment')->where('file_id',$id)->where('status', 1)->where('notes_type', 1)->get();
		if(count($files))
		{
			$public_dir=public_path();
			$zipFileName = $client_details->company.'_'.$details->date_added.'.zip';
			foreach($files as $file)
			{
	            $zip = new ZipArchive;
	            if ($zip->open($public_dir . '/' . $zipFileName, ZipArchive::CREATE) === TRUE) {
	            	// Add File in ZipArchive
	                $zip->addFile($file->url.'/'.$file->attachment,$file->attachment);
	                // Close ZipArchive     
	                $zip->close();
	            }
			}
			echo $zipFileName;
		}

		
	}

	public function infile_delete_all_notes()
	{
		$id = Input::get('id');	

		DB::table('in_file_attachment')->where('file_id',$id)->where('status', 0)->where('notes_type', 1)->delete();
		$count = DB::table('in_file_attachment')->where('file_id',$id)->where('status', 0)->count();
		if($count > 0)
		{

		}
		else{
			$dataval['task_notify'] = 0;
			DB::table('in_file')->where('id',$id)->update($dataval);
		}
	}
	public function infile_download_all_notes()
	{
		$id = Input::get('id');	
		$details = DB::table('in_file')->where('id',$id)->first();
		$client_details = DB::table('cm_clients')->where('client_id',$details->client_id)->first();
		$files = DB::table('in_file_attachment')->where('file_id',$id)->where('status', 0)->where('notes_type', 1)->get();
		if(count($files))
		{
			$public_dir=public_path();
			$zipFileName = $client_details->company.'_'.$details->date_added.'.zip';
			foreach($files as $file)
			{
	            $zip = new ZipArchive;
	            if ($zip->open($public_dir . '/' . $zipFileName, ZipArchive::CREATE) === TRUE) {
	            	// Add File in ZipArchive
	                $zip->addFile($file->url.'/'.$file->attachment,$file->attachment);
	                // Close ZipArchive     
	                $zip->close();
	            }
			}
			echo $zipFileName;
		}
	}

	public function infile_commonclient_search()
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
				$company = $single->firstname.' '.$single->surname;
			}
                $data[]=array('value'=>$company.'-'.$single->client_id,'id'=>$single->client_id);

        }
         if(count($data))
             return $data;
        else
            return ['value'=>'No Result Found','id'=>''];

	}

	public function infile_commonclientsearchselect(){

		$id = Input::get('value');
		$infiles = DB::table('in_file')->where('client_id', $id)->get();
		

		$userlist = DB::table('user')->where('user_status', 0)->get();
		$i=1;
	    $output='';
	    if(count($infiles)){
	       foreach ($infiles as $file) {
	          if($file->status == 0){
                $staus = 'fa-check'; 
                $statustooltip = 'Complete Infile';
                $disable = '';
                $disable_class = '';
                $color='';
              } 
              else{
                $staus = 'fa-times';
                $statustooltip = 'InComplete Infile';
                $disable = 'disabled';
                $disable_class = 'disable_class';
                $color = 'style="color:#f00;"';
              }

	          $companydetails = DB::table('cm_clients')->where('client_id', $file->client_id)->first();

	          $attachments = DB::table('in_file_attachment')->where('file_id',$file->id)->where('status',0)->where('notes_type', 0)->get();

	          $downloadfile='';

	          if(count($attachments)){                        
                foreach($attachments as $attachment){
                	if($attachment->textstatus == 1) { $texticon="display:none"; $hide = 'display:initial'; } else { $texticon="display:initial"; $hide = 'display:none'; }
                    if($attachment->check_file == 1) { $textdisabled ='disabled'; $checked = 'checked'; } else { $textdisabled =''; $checked = ''; }
                    $downloadfile.= '<div class="file_attachment_div"><input type="checkbox" name="fileattachment_checkbox" class="fileattachment_checkbox '.$disable_class.'" id="fileattach_'.$attachment->id.'" value="'.$attachment->id.'" '.$checked.' '.$disable.'><label for="fileattach_'.$attachment->id.'">&nbsp;</label> 
                    	<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'" '.$color.'>'.$attachment->attachment.'</a>
                    	<a href="javascript:" class="trash_icon '.$disable_class.'"><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a>
                    	<a href="javascript:" class="fa fa-text-width add_text_image '.$disable_class.'" data-element="'.$attachment->id.'" title="Add Text" style="'.$texticon.'"></a>
                    	<input type="text" name="add_text" class="add_text '.$disable_class.'" data-element="'.$attachment->id.'" value="'.$attachment->textval.'" placeholder="Add Text" '.$textdisabled.' style="'.$hide.'">
                    	<a href="javascript:" class="fa fa-minus-square remove_text_image '.$disable_class.'" data-element="'.$attachment->id.'" title="Remove Text" style="'.$hide.'"></a>
                    	<a href="javascript:" class="fa fa-download download_rename" data-src="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'" data-element="'.$attachment->id.'" title="Download & Rename" style="'.$hide.'"></a>
                    </div>';
                }
              }
              else{
                $downloadfile ='';
              }
              /*<i class="fa fa-download download_all_image" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments"></i>*/
              if(count($attachments)){
                $deleteall = '<i class="fa fa-minus-square delete_all_image '.$disable_class.'" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Delete All Attachments"></i>
                
                <i class="fa fa-cloud-download download_rename_all_image" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download & Rename All Attachments"></i>';
              }
              else{
                $deleteall = '';
              }
              if(count($attachments))
              {
                $span = '<span style="color:#000">There are '.count($attachments).' file(s)</span>';
              }
              else{
                $span = '';
              }


              $notes_attachments = DB::table('in_file_attachment')->where('file_id',$file->id)->where('status',0)->where('notes_type', 1)->get();

              $download_notes='';

              if(count($notes_attachments)){                        
                foreach($notes_attachments as $attachment){
                	if($attachment->check_file == 1) { $checked = 'checked'; } else { $checked = ''; }
                    $download_notes.= '<input type="checkbox" name="fileattachment_checkbox" class="fileattachment_checkbox '.$disable_class.'" id="fileattach_'.$attachment->id.'" value="'.$attachment->id.'" '.$checked.' '.$disable.'><label for="fileattach_'.$attachment->id.'">&nbsp;</label><a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disable_class.'"><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                }
              }
              else{
                $download_notes ='';
              }

              if(count($notes_attachments)){
                $delete_notes_all = '<i class="fa fa-minus-square delete_all_notes '.$disable_class.'" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Delete All Attachments"></i>
                <i class="fa fa-download download_all_notes" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments"></i>';
              }
              else{
                $delete_notes_all = '';
              }


              $attach_notes_only = DB::table('in_file_attachment')->where('file_id',$file->id)->where('status',1)->get();

              $notes_only='';

              if(count($attach_notes_only)){                        
                foreach($attach_notes_only as $attachment){

                    $notes_only.= '<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disable_class.'"><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                }
              }
              else{
                $notes_only ='';
              }

              if(count($attach_notes_only)){
                $delete_notes_only = '<i class="fa fa-minus-square delete_all_notes_only '.$disable_class.'" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Delete All Attachments"></i>
                <i class="fa fa-download download_all_notes_only" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments"></i>';
              }
              else{
                $delete_notes_only = '';
              }




              $userdrop='';

              if(count($userlist)){
                foreach ($userlist as $user) {
                  if($user->user_id == $file->complete_by ){ $selected = 'selected';} else{$selected = '';}
                  $userdrop.='<option value="'.$user->user_id.'" '.$selected.'>'.$user->firstname.'</option>';
                }
              }

              $complete_date = ($file->complete_date != '0000-00-00')?date('d-M-Y', strtotime($file->complete_date)):'';
              $hard_files = ($file->hard_files != 0)?'YES':'NO';

	      $output.='
	        <tr id="infile_'.$file->id.'">
              <td '.$color.' valign="top" >'.$i.'</td>
              <td '.$color.' valign="top">'.$companydetails->company.'</td>
              <td '.$color.' valign="top">Received:'.date('d-M-Y', strtotime($file->data_received)).'<br/>Added: '.date('d-M-Y', strtotime($file->date_added)).'</td>
              <td '.$color.'>
              
              '.$downloadfile.'
              <i class="fa fa-plus faplus '.$disable_class.'" style="margin-top:10px" aria-hidden="true" title="Add Attachment"></i>              
              '.$deleteall.'<br/><br/>

              <div style="width:100%; height:auto; float:left; padding-bottom:10px; border-bottom:1px solid #000">Notes</div>

              <div class="clearfix"></div>

              '.$download_notes.'
              <i class="fa fa-pencil-square fanotepad '.$disable_class.'" style="margin-top:10px;" aria-hidden="true" title="Add Notepad"></i>
              '.$delete_notes_all.'




                  <div class="img_div" style="z-index:9999999">
                    <form name="image_form" id="image_form" action="'.URL::to('user/infile_image_upload').'" method="post" enctype="multipart/form-data" style="text-align: left;">
                      <input type="file" name="image_file[]" required class="form-control image_file" value="" multiple>
                      <input type="hidden" name="hidden_id" value="'.$file->id.'">
                      <input type="submit" name="image_submit" class="btn btn-sm btn-primary image_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
                      <spam class="error_files"></spam>
                    </form>
                    <div style="width:100%;text-align:center;margin-top:-10px;margin-bottom:10px;color:#000"><label style="font-weight:800;">OR</label></div>
                    <div class="image_div_attachments">
                      <form action="'.URL::to('user/infile_upload_images?file_id='.$file->id).'" method="post" enctype="multipart/form-data" class="dropzone" id="image-upload" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                          <input name="_token" type="hidden" value="'.$file->id.'">
                       
                      </form>
                      <a href="'.URL::to('user/in_file/').'" class="btn btn-sm btn-primary" align="left" style="margin-left:7px; float:left;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
                    </div>
                  </div>


                  <div class="notepad_div" style="z-index:9999; position:absolute">

                    <form name="notepad_form" id="notepad_form" action="'.URL::to('user/infile_notepad_upload').'" method="post" enctype="multipart/form-data" style="text-align: left;">
                      
                      <textarea name="notepad_contents" class="form-control notepad_contents" placeholder="Enter Contents"></textarea>
                      <input type="hidden" name="hidden_id" value="'.$file->id.'">
                      <input type="submit" name="notepad_submit" class="btn btn-sm btn-primary notepad_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">

                      <spam class="error_files_notepad"></spam>

                    </form>
                  </div>

                  
                  

              
              </td>
              <td '.$color.' valign="top">
              <select class="form-control user_select '.$disable_class.'" data-element="'.$file->id.'" '.$disable.'>
              <option value="">Select User</option>'.$userdrop.'</select>
              </td>
              <td '.$color.' valign="top">
              
                  <label class="input-group auto_save_date">
                      <input type="text" class="form-control complete_date '.$disable_class.'" '.$disable.' value="'.$complete_date.'" data-element="'.$file->id.'" placeholder="Select Date" name="date" style="font-weight: 500;" required="">
                      <span class="input-group-addon">
                          <i class="glyphicon glyphicon-calendar"></i>
                      </span>
                  </label>
              
              </td>
              <td '.$color.' valign="top">

              '.$notes_only.'
              <i class="fa fa-pencil-square fanotepad_notes '.$disable_class.'" style="margin-top:10px;" aria-hidden="true" title="Add Notepad"></i>
              '.$delete_notes_only.'

              <div class="notepad_div_notes" style="z-index:9999; position:absolute">

                <form name="notepad_form_notes" id="notepad_form_notes" action="'.URL::to('user/infile_notepad_upload_notes').'" method="post" enctype="multipart/form-data" style="text-align: left;">
                  
                  <textarea name="notepad_contents" class="form-control notepad_contents" placeholder="Enter Contents"></textarea>
                  <input type="hidden" name="hidden_id" value="'.$file->id.'">
                  <input type="submit" name="notepad_submit" class="btn btn-sm btn-primary notepad_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">

                  <spam class="error_files_notepad"></spam>

                </form>

              </div>

              </td>
              <td '.$color.' align="center" valign="top">'.$hard_files.'</td>
              <td '.$color.' align="center" valign="top"><a href="javascript:"><i class="fa '.$staus.'" aria-hidden="true" data-element="'.$file->id.'" data-toggle="tooltip" title="'.$statustooltip.'"></i></a></td>
            </tr>';      
	        $i++;
	        }
	      }
      echo json_encode(array('result_row' => $output));	


	}

	public function add_notepad_contents()
	{
		$contents = Input::get('contents');
		$clientid = Input::get('clientid');

		$company_detail = DB::table('cm_clients')->where('client_id', $clientid)->first();
		$upload_dir = 'uploads/infile_image';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/temp';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}

		if(Session::has('notepad_attach_add'))
		{
			$count = count(Session::get('notepad_attach_add'));
			$counts = $count + 1;
			$file_name =  preg_replace('/[^A-Za-z0-9 \-]/', '', $company_detail->company);
			$filename = $file_name.'-'.$counts;	

			$arrayval = array('attachment' => $filename.".txt",'url' => $upload_dir);
			$getsession = Session::get('notepad_attach_add');
			array_push($getsession,$arrayval);

		}
		else{
			$count = 0;
			$counts = $count + 1;
			$file_name =  preg_replace('/[^A-Za-z0-9 \-]/', '', $company_detail->company);
			$filename = $file_name.'-'.$counts;	

			$arrayval = array('attachment' => $filename.".txt",'url' => $upload_dir);
			$getsession = array();
			array_push($getsession,$arrayval);
		}

		

		$myfile = fopen($upload_dir."/".$filename.".txt", "w") or die("Unable to open file!");
		fwrite($myfile, $contents);
		fclose($myfile);

		$sessn=array('notepad_attach_add' => $getsession);
		Session::put($sessn);
		echo $filename.".txt";
	}
	public function infile_upload_images_add()
	{
		$upload_dir = 'uploads/infile_image';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/temp';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}

		if (!empty($_FILES)) {
			 $tmpFile = $_FILES['file']['tmp_name'];
			 $filename = $upload_dir.'/'.$_FILES['file']['name'];
			 $fname = $_FILES['file']['name'];

		 	move_uploaded_file($tmpFile,$filename);


		 	if(Session::has('file_attach_add'))
			{
				$arrayval = array('attachment' => $fname,'url' => $upload_dir);
				$getsession = Session::get('file_attach_add');
				array_push($getsession,$arrayval);

			}
			else{
				$arrayval = array('attachment' => $fname,'url' => $upload_dir);
				$getsession = array();
				array_push($getsession,$arrayval);
			}

			$sessn=array('file_attach_add' => $getsession);
			Session::put($sessn);
		}

		echo json_encode(array('id' => 0,'filename' => $fname,'file_id' => 0));
	}
	public function create_new_file()
	{
		$clientid= Input::get('clientid');
		$received_date = date('Y-m-d', strtotime(Input::get("received_date"))); //explode('-',Input::get('received_date'));
		
		//$received_date = $date_received[2].'-'.$date_received[0].'-'.$date_received[1];

		//$date_added = explode('-',Input::get('added_date'));
		$added_date = date('Y-m-d', strtotime(Input::get("added_date"))); //$date_added[2].'-'.$date_added[0].'-'.$date_added[1];

		$data['client_id'] = $clientid;
		$data['data_received'] = $received_date;
		$data['date_added'] = $added_date;
		$data['description'] = Input::get('description');
		$data['hard_files'] = (Input::get("hard_files_checkbox") != 0)?1:0;

		$file_id = DB::table('in_file')->insertGetId($data);

		if(Session::has('file_attach_add'))
		{
			$files = Session::get('file_attach_add');
			foreach($files as $file)
			{
				$upload_dir = 'uploads/infile_image';
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
				$upload_dir = $upload_dir.'/'.base64_encode($file_id);
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
				rename("uploads/infile_image/temp/".$file['attachment'], $upload_dir.'/'.$file['attachment']);

				$dataval['file_id'] = $file_id;
				$dataval['attachment'] = $file['attachment'];
				$dataval['url'] = $upload_dir;

				Db::table('in_file_attachment')->insert($dataval);
			}
			$datavalinfile['task_notify'] = 1;
			DB::table('in_file')->where('id',$file_id)->update($datavalinfile);
		}
		if(Session::has('notepad_attach_add'))
		{
			$files = Session::get('notepad_attach_add');
			foreach($files as $file)
			{
				$upload_dir = 'uploads/infile_image';
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
				$upload_dir = $upload_dir.'/'.base64_encode($file_id);
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
				rename("uploads/infile_image/temp/".$file['attachment'], $upload_dir.'/'.$file['attachment']);

				$dataval['file_id'] = $file_id;
				$dataval['attachment'] = $file['attachment'];
				$dataval['url'] = $upload_dir;
				$dataval['notes_type'] = 1;

				Db::table('in_file_attachment')->insert($dataval);
			}
			$datavalinfile['task_notify'] = 1;
			DB::table('in_file')->where('id',$file_id)->update($datavalinfile);
		}

		$client_details = DB::table('cm_clients')->where('client_id', $clientid)->first();

		return redirect('user/in_file')->with('message', 'InFiles for "'.$client_details->company.'" is Created Successfully and saved the Selection.');
	}
	public function clear_session_attachments()
	{
		if(Session::has('notepad_attach_add'))
		{
			Session::forget("notepad_attach_add");
		}
		if(Session::has('file_attach_add'))
		{
			Session::forget("file_attach_add");
		}
	}
	public function delete_file_link()
	{
		$file = 'public/'.Input::get('result');
		if (!unlink($file))
		  {
		  echo ("Error deleting $file");
		  }
		else
		  {
		  echo ("Deleted $file");
		  }
	}

	public function infile_search(){
		$clientid = Input::get('client_id');
		if(Session::has('notepad_attach_add'))
		{
			Session::forget("notepad_attach_add");
		}
		if(Session::has('file_attach_add'))
		{
			Session::forget("file_attach_add");
		}
		$infiles = DB::table('in_file')->where('client_id', $clientid)->get();
		$userlist = DB::table('user')->where('user_status', 0)->get();
		$internal = 1;
		return view('user/infile/infile', array('title' => 'InFile', 'infiles' => $infiles, 'userlist' => $userlist, 'internal_search_check' => $internal));
	}

	public function infile_internal(){
		$id = Input::get('id');	
		$data['internal'] =  Input::get('internal');		
		DB::table('in_file')->where('id', $id)->update($data);
	}
	public function fileattachment_status()
	{
		$id = Input::get('id');	
		$status = Input::get('status');	
		$data['check_file'] =  $status;		
		DB::table('in_file_attachment')->where('id', $id)->update($data);
	}
	public function infile_email_notify_tasks_pdf()
	{
		$email = Input::get('email');
		$file_id = Input::get('file_id');
		$client_id = Input::get('clientid');
		$user_details = DB::table('user')->where('user_id',$client_id)->first();

		$file_details = DB::table('in_file')->where('id',$file_id)->first();
		$client_details = DB::table('cm_clients')->where('client_id',$file_details->client_id)->first();
		

		$admin_details = Db::table('admin')->where('id',1)->first();



		$admin_cc = $admin_details->task_cc_email;

		$from = $admin_details->email;

		$to = trim($email);

		$cc = $admin_cc;



		$data['sentmails'] = $to.' , '.$cc;

		$data['logo'] = URL::to('assets/images/easy_payroll_logo.png');





		$data['file_details'] = $file_details;
		$data['username'] = $user_details->firstname;

		$data['client_name'] = $client_details->company;



		$contentmessage = view('user/file_email_notify_tasks', $data);

		



		$subject = 'EasyPayroll.ie: Infile Task Notification';



		$email = new PHPMailer();



		if($to != '')



		{

			$email->SetFrom($from); //Name is optional

			$email->Subject   = $subject;

			$email->Body      = $contentmessage;

			$email->AddCC($cc);

			$email->IsHTML(true);

			$email->AddAddress( $to );

			$email->Send();	

		}

	}
	public function change_attachment_text_status()
	{
		$id = $_GET['id'];
		$dataval['textstatus'] = 1;
		DB::table('in_file_attachment')->where('id',$id)->update($dataval);
	}
	public function remove_attachment_text_status()
	{
		$id = $_GET['id'];
		$dataval['textstatus'] = 0;
		$dataval['textval'] = '';
		DB::table('in_file_attachment')->where('id',$id)->update($dataval);
	}
	public function update_fileattachment_textval()
	{
		$id = $_GET['id'];
		$dataval['textval'] = $_GET['input'];
		DB::table('in_file_attachment')->where('id',$id)->update($dataval);
	}
	public function get_attachment_details()
	{
		$id = $_GET['id'];
		$src = $_GET['element'];
		$details = DB::table('in_file_attachment')->where('id',$id)->first();
		if($details->textval != "" && $details->textstatus == 1)
		{
			$filename = "QuickID_".$details->textval."_".$details->attachment;
		}
		else{
			$filename = '';
		}
		echo $filename;
	}


	public function report_infile(){
		$id = Input::get('id');
		$infilelist = DB::table('in_file')->groupBy('client_id')->where('status',0)->get();
		if($id == 0){				
				$output = '';
				$i=1;
				if(count($infilelist)){ 
					foreach($infilelist as $infile){ 
						$client_details = DB::table('cm_clients')->where('client_id', $infile->client_id)->first();						
						$output.='
							<tr>
								<td>'.$i.'</td>
								<td><input type="radio" name="select_client" class="select_client class_'.$client_details->client_id.'" data-element="'.$client_details->client_id.'" value="'.$client_details->client_id.'"><label>&nbsp</label></td>
								<td align="left">'.$client_details->client_id.'</span></td>
								<td align="left">'.$client_details->firstname.'</td>
								<td align="left">'.$client_details->company.'</td>
							</tr>
						';
						$i++;
					}
				}
				if($i == 1){
		          $output.='<tr><td colspan="5" align="center">Empty</td></tr>';			
		        }
				echo $output;
		}
		else{				
				$output = '';
				$i=1;
				if(count($infilelist)){ 
					foreach($infilelist as $infile){ 
						$client_details = DB::table('cm_clients')->where('client_id', $infile->client_id)->first();
						$output.='
							<tr>
								<td>'.$i.'</td>
								<td><input type="checkbox" name="select_client" class="select_client class_'.$client_details->client_id.'" data-element="'.$client_details->client_id.'" value="'.$client_details->client_id.'"><label>&nbsp</label></td>
								<td align="left">'.$client_details->client_id.'</td>
								<td align="left">'.$client_details->firstname.'</td>
								<td align="left">'.$client_details->company.'</td>
							</tr>
						';
						$i++;
					}
				}
				if($i == 1){
		          $output.='<tr><td colspan="5" align="center">Empty</td></tr>';			
		        }
				echo $output;
		}

	}


	public function infile_report_incomplete(){
		$id = Input::get('id');	
		$type = Input::get('type');	
		
		if($id == 0){
				$infilelist = DB::table('in_file')->groupBy('client_id')->where('status', $id)->get();				
				$output = '';
				$i=1;
				if(count($infilelist)){ 
					foreach($infilelist as $infile){ 
						$client_details = DB::table('cm_clients')->where('client_id', $infile->client_id)->first();	
						if($type == 1){
						$output.='
							<tr>
								<td>'.$i.'</td>
								<td><input type="radio" name="select_client" class="select_client class_'.$client_details->client_id.'" data-element="'.$client_details->client_id.'" value="'.$client_details->client_id.'"><label>&nbsp</label></td>
								<td align="left">'.$client_details->client_id.'</span></td>
								<td align="left">'.$client_details->firstname.'</td>
								<td align="left">'.$client_details->company.'</td>
							</tr>
						';
						}
						else{
							$output.='
							<tr>
								<td>'.$i.'</td>
								<td><input type="checkbox" name="select_client" class="select_client class_'.$client_details->client_id.'" data-element="'.$client_details->client_id.'" value="'.$client_details->client_id.'"><label>&nbsp</label></td>
								<td align="left">'.$client_details->client_id.'</span></td>
								<td align="left">'.$client_details->firstname.'</td>
								<td align="left">'.$client_details->company.'</td>
							</tr>
						';
						}
						$i++;
					}
				}
				if($i == 1){
		          $output.='<tr><td colspan="5" align="center">Empty</td></tr>';			
		        }
				echo $output;
		}
		else{	$infilelist = DB::table('in_file')->groupBy('client_id')->get();
				$output = '';
				$i=1;
				if(count($infilelist)){ 
					foreach($infilelist as $infile){ 
						$client_details = DB::table('cm_clients')->where('client_id', $infile->client_id)->first();
						if($type == 1){
						$output.='
							<tr>
								<td>'.$i.'</td>
								<td><input type="radio" name="select_client" class="select_client class_'.$client_details->client_id.'" data-element="'.$client_details->client_id.'" value="'.$client_details->client_id.'"><label>&nbsp</label></td>
								<td align="left">'.$client_details->client_id.'</span></td>
								<td align="left">'.$client_details->firstname.'</td>
								<td align="left">'.$client_details->company.'</td>
							</tr>
						';
						}
						else{
							$output.='
							<tr>
								<td>'.$i.'</td>
								<td><input type="checkbox" name="select_client" class="select_client class_'.$client_details->client_id.'" data-element="'.$client_details->client_id.'" value="'.$client_details->client_id.'"><label>&nbsp</label></td>
								<td align="left">'.$client_details->client_id.'</span></td>
								<td align="left">'.$client_details->firstname.'</td>
								<td align="left">'.$client_details->company.'</td>
							</tr>
						';
						}
						$i++;
					}
				}
				if($i == 1){
		          $output.='<tr><td colspan="5" align="center">Empty</td></tr>';			
		        }
				echo $output;
		}

	}


	public function infile_report_pdf()
	{
		$ids = explode(",",Input::get('value'));
		$status = Input::get('status');

		if($status == 1){
			//$infile_client = DB::table('in_file')->join('cm_clients', 'in_file.client_id', '=', 'cm_clients.client_id')->whereIn('in_file.client_id', $ids)->where('in_file.status', 0)->orderBy('cm_clients.firstname','desc')->get();

			$infile_client = DB::table('in_file')->whereIn('client_id', $ids)->where('status', 0)->get();
		}
		else{
			//$infile_client = DB::table('in_file')->join('cm_clients', 'in_file.client_id', '=', 'cm_clients.client_id')->whereIn('in_file.client_id', $ids)->orderBy('cm_clients.firstname','desc')->get();
			$infile_client = DB::table('in_file')->whereIn('client_id', $ids)->get();
		}
		
		
		
		$output = '';
		$i=1;
		if(count($infile_client)){
			foreach ($infile_client as $file) {				
				
				$client_details = DB::table('cm_clients')->where('client_id', $file->client_id)->first();

				/*$file_count='';

				if(count($infile_details)){
					foreach ($infile_details as $single_file) {						
						$file_count.= DB::table('in_file_attachment')->where('file_id', $single_file->id)->count();
					}
				}*/

				$file_count = DB::table('in_file_attachment')->where('file_id', $file->id)->count();


				if(count($client_details)){
					$clientname = $client_details->firstname.'&nbsp;'.$client_details->surname;
				}
				else{
					$clientname = '';
				}			

				$output.='
					<tr>
						<td style="text-align: left;border:1px solid #000;">'.$i.'</td>
						<td style="text-align: left;border:1px solid #000;">'.$clientname.'</td>
						<td style="text-align: left;border:1px solid #000;">'.$file->data_received.'</td>
						<td style="text-align: left;border:1px solid #000;">'.$file->date_added.'</td>
						<td style="text-align: left;border:1px solid #000;">'.$file_count.'</td>

					</tr>
				';
				$i++;
			}
		}			
		else{
			$output='<td colspan="4" align="center">Empty</td>';
		}
		

		echo $output;
	}
	public function download_infile_report_pdf()
	{
		$htmlval = Input::get('htmlval');
		$pdf = PDF::loadHTML($htmlval);
		$pdf->setPaper('A4', 'landscape');
		
		$pdf->save('infile_report/Infile Report.pdf');
		echo 'Infile Report.pdf';
	}

	public function infile_report_csv($id=""){		

		$ids = explode(",",Input::get('value'));
		$status = INput::get('status');

		if($status == 1){
			//$infile_client = DB::table('in_file')->join('cm_clients', 'in_file.client_id', '=', 'cm_clients.client_id')->whereIn('in_file.client_id', $ids)->where('in_file.status', 0)->orderBy('cm_clients.firstname','asc')->get();

			$infile_client = DB::table('in_file')->whereIn('client_id', $ids)->where('status', 0)->get();
		}
		else{
			//$infile_client = DB::table('in_file')->join('cm_clients', 'in_file.client_id', '=', 'cm_clients.client_id')->whereIn('in_file.client_id', $ids)->orderBy('cm_clients.firstname','asc')->get();

			$infile_client = DB::table('in_file')->whereIn('client_id', $ids)->get();
		}

		$headers = array(
	        "Content-type" => "text/csv",
	        "Content-Disposition" => "attachment; filename=CM_Report.csv",
	        "Pragma" => "no-cache",
	        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
	        "Expires" => "0"
	    );
	    
      	
		$columns_1 = array('#', 'Client Name', 'Date Received', 'Date Added', 'No. of Files');
		

		$callback = function() use ($infile_client, $columns_1)
    	{
	       	$file = fopen('infile_report/Infile_Report.csv', 'w');
		    fputcsv($file, $columns_1);
			$i=1;
			foreach ($infile_client as $singlefile) {
				$file_count = DB::table('in_file_attachment')->where('file_id', $singlefile->id)->count();
				$client_details = DB::table('cm_clients')->where('client_id', $singlefile->client_id)->first();				
				
		      	$columns_2 = array($i, $client_details->firstname, $singlefile->data_received, $singlefile->date_added, $file_count);

				fputcsv($file, $columns_2);
				$i++;
			}
			fclose($file);	
		};
		return Response::stream($callback, 200, $headers);
	}

	public function infile_report_csv_single($id=""){		

		$ids = explode(",",Input::get('value'));
		$status = Input::get('status');

		if($status == 1){
			$infile_client = DB::table('in_file')->whereIn('client_id', $ids)->where('status',0)->orderBy('data_received','asc')->get();
		}
		else{
			$infile_client = DB::table('in_file')->whereIn('client_id', $ids)->orderBy('data_received','asc')->get();
		}
		
		$headers = array(
	        "Content-type" => "text/csv",
	        "Content-Disposition" => "attachment; filename=CM_Report.csv",
	        "Pragma" => "no-cache",
	        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
	        "Expires" => "0"
	    );

      	
		$columns_1 = array('#', 'Client Name', 'Date Received', 'Date Added', 'Complete By', 'Complete Date', 'Description', 'No. of Files', 'Attachments', 'Status');
		

		$callback = function() use ($infile_client, $columns_1)
    	{
	       	$file = fopen('infile_report/Infile_Report.csv', 'w');
		    fputcsv($file, $columns_1);
			$i=1;
			foreach ($infile_client as $singlefile) {
				$file_count = DB::table('in_file_attachment')->where('file_id', $singlefile->id)->count();
				$client_details = DB::table('cm_clients')->where('client_id', $singlefile->client_id)->first();
				$file_name = DB::table('in_file_attachment')->where('file_id', $singlefile->id)->orderBy('id','asc')->get();

				

				$user_details = DB::table('user')->where('user_id', $singlefile->complete_by)->first();

				if(count($user_details)){
					$user = $user_details->firstname.' '.$user_details->lastname;
				}
				else{
					$user = 'N/A';
				}

				if($singlefile->complete_date == '0000-00-00'){
					$complete_date = 'N/A';
				}
				else{
					$complete_date = $singlefile->complete_date;
				}

				if($singlefile->status == 0){
					$status = 'Incomplete';
				}
				else{
					$status = 'Completed';
				}
				


				$first_file_name = DB::table('in_file_attachment')->where('file_id', $singlefile->id)->orderBy('id','asc')->first();
				if(count($first_file_name))
				{
					if($first_file_name->textstatus == 0){
						$first_file = $first_file_name->attachment;
					}
					else{
						$first_file = $first_file_name->attachment.'('.$first_file_name->textval.')';
					}
					
				}
				else{
					$first_file = '';
				}

				if($singlefile->description == ''){
					$description = 'Description Not Available';
				}
				else{
					$description = $singlefile->description;
				}


				


		      	$columns_2 = array($i, $client_details->firstname, $singlefile->data_received, $singlefile->date_added, $user, $complete_date,$description, $file_count, $first_file, $status);
				fputcsv($file, $columns_2);

				if(count($file_name))
				{
					$isingle = 0;
					foreach($file_name as $single_file)
					{
						if($isingle != 0)
						{
							if($single_file->textstatus==0){
								$columns_3 = array('','','','','','','','',$single_file->attachment,'');
							}
							else{
								$columns_3 = array('','','','','','','','',$single_file->attachment.'('.$single_file->textval.')','');
							}
							
							fputcsv($file, $columns_3);
						}
						$isingle++;
					}
				}


				$i++;
			}
			fclose($file);	
		};
		return Response::stream($callback, 200, $headers);
	}

	public function infile_report_pdf_single()
	{
		$ids = explode(",",Input::get('value'));	
		$status = Input::get('status');

		

		if($status == 1){
			$infile_client = DB::table('in_file')->whereIn('client_id', $ids)->where('status', 0)->orderBy('data_received','asc')->get();
		}
		else{
			$infile_client = DB::table('in_file')->whereIn('client_id', $ids)->orderBy('data_received','asc')->get();
		}

		


		
		
		$output = '';
		$i=1;
		if(count($infile_client)){
			foreach ($infile_client as $file) {
				$file_count = DB::table('in_file_attachment')->where('file_id', $file->id)->count();
				$client_details = DB::table('cm_clients')->where('client_id', $file->client_id)->first();

				$infile_name = DB::table('in_file_attachment')->where('file_id', $file->id)->get();

				$username = DB::table('user')->where('user_id', $file->complete_by)->first();

				$infile_attached='';

				if(count($infile_name)){
					foreach ($infile_name as $file_single) {
						if($file_single->status == 0){
							if($file_single->notes_type == 0){
								if($file_single->textstatus == 0){
									$infile_attached.= $file_single->attachment.'<br/>';
								}
								else{
									$infile_attached.= $file_single->attachment.'('.$file_single->textval.')'.'<br/>';
								}
							}
						}
					}
				}
				else{
					$infile_attached ='';
				}



				$infile_attached_notes='';

				if(count($infile_name)){
					foreach ($infile_name as $file_single) {
						if($file_single->status == 0){
							if($file_single->notes_type == 1){
								if($file_single->textstatus == 0){
									$infile_attached_notes.= $file_single->attachment.'<br/>';
								}
								else{
									$infile_attached_notes.= $file_single->attachment.'('.$file_single->textval.')'.'<br/>';
								}
							}
						}
					}
				}
				else{
					$infile_attached_notes ='';
				}




				$compete_notes ='';

				if(count($infile_name)){
					foreach ($infile_name as $file_single) {
						if($file_single->status == 1){							
							$compete_notes.= $file_single->attachment.'<br/>';
						}
					}
				}
				else{
					$compete_notes ='';
				}


				if(count($client_details)){
					$clientname = $client_details->firstname.'&nbsp;'.$client_details->surname;
				}
				else{
					$clientname = 'N/A';
				}

				if($file->complete_date == '0000-00-00'){
					$complete_date = 'N/A';
				}
				else{
					$complete_date = $file->complete_date;
				}

				if(count($username)){
					$user = $username->firstname.' '.$username->lastname;
				}
				else{
					$user = 'N/A';
				}

				if($file->status == 0){
					$status = 'Incomplete';
				}
				else{
					$status = 'Completed';
				}


				if($file->description == ''){
					$description = 'Description Not Available';
				}
				else{
					$description = $file->description;
				}

				$output.='
					<style>									
					.table_td_class_left{text-align: left;border:1px solid #00; line-height:30px; height:20px; width:30%; float:left; padding:3px;}
					.table_td_class_right{text-align: left;border:1px solid #00; line-height:30px; height:20px; width:70%; float:left; padding:3px;}
					</style>
					<div style="width:100%; height:auto; margin-bottom:100px;">
						<table cellspacing="0" cellpadding="0" border="0px" style="width:80%; padding-left:10%; ">
						<tr>
							<td class="table_td_class_left">S.No</td>
							<td class="table_td_class_right">'.$i.'</td>
						</tr>
						<tr>
							<td class="table_td_class_left">Client Name</td>
							<td class="table_td_class_right">'.$clientname.'</td>
						</tr>
						<tr>
							<td class="table_td_class_left">Date Received</td>
							<td class="table_td_class_right">'.$file->data_received.'</td>
						</tr>
						<tr>
							<td class="table_td_class_left">Date Added</td>
							<td class="table_td_class_right">'.$file->date_added.'</td>						
						</tr>
						<tr>
							<td class="table_td_class_left">Complete By</td>
							<td class="table_td_class_right">'.$user.'</td>						
						</tr>
						<tr>
							<td class="table_td_class_left">Complete date</td>
							<td class="table_td_class_right">'.$complete_date.'</td>						
						</tr>
						<tr>
							<td class="table_td_class_left">Description</td>
							<td class="table_td_class_right">'.$description.'</td>						
						</tr>
						<tr>
							<td class="table_td_class_left">No. of Files</td>
							<td class="table_td_class_right">'.$file_count.'</td>
						</tr>
						<tr>
							<td class="table_td_class_left">Files List</td>
							<td class="table_td_class_right">Attachment(s):<br/>'.$infile_attached.'<br/>Note(s):<br/>'.$infile_attached_notes.'</td>
						</tr>
						<tr>
							<td class="table_td_class_left">Completion Notes:</td>
							<td class="table_td_class_right">'.$compete_notes.'</td>
						</tr>
						<tr>
							<td class="table_td_class_left">Status</td>
							<td class="table_td_class_right">'.$status.'</td>
						</tr>
							
						</table>
					</div>			
				';
				$i++;
			}
		}			
		else{
			$output='<tr><td colspan="2" align="center">Empty</td><td>';
		}
		

		echo $output;
	}
	public function download_infile_report_pdf_single()
	{
		$htmlval = Input::get('htmlval');
		$pdf = PDF::loadHTML($htmlval);
		$pdf->setPaper('A4', 'landscape');
		
		$pdf->save('infile_report/Infile Report.pdf');
		echo 'Infile Report.pdf';
	}


}



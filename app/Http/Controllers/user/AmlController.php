<?php namespace App\Http\Controllers\user;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\CmClass;
use App\CmClients;
use App\CmFields;
use App\CmPaper;
use App\Week;
use Session;
use URL;
use PDF;
use Response;
use PHPExcel; 
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use Hash;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class AmlController extends Controller {

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
	public function __construct(cmclass $cmclass, cmclients $cmclients, cmfields $cmfields, cmpaper $cmpaper, week $week)
	{
		$this->middleware('userauth');
		$this->cmclass = $cmclass;
		$this->cmclients = $cmclients;
		$this->cmfields = $cmfields;
		$this->cmpaper = $cmpaper;
		$this->week = $week;
		date_default_timezone_set("Europe/Dublin");
		require_once(app_path('Http/helpers.php'));
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function aml_system()
	{
		$client = DB::table('cm_clients')->select('client_id', 'firstname', 'surname', 'company', 'status', 'active', 'id')->orderBy('id','asc')->get();
		
		$class = DB::table('cm_class')->where('status', 0)->get();	
		$user = DB::table('user')->where('user_status', 0)->where('disabled',0)->orderBy('firstname','asc')->get();
		return view('user/aml/aml_system', array('title' => 'Client Management', 'clientlist' => $client, 'classlist' => $class, 'userlist' => $user));
	}

	public function update_aml_incomplete_status()
	{
		$data['aml_incomplete'] = Input::get('value');
		DB::table('user_login')->where('userid',1)->update($data);
	}

	public function aml_system_client_source_refresh(){
		$id = Input::get('id');
		$data['client_source'] = '0';
		$data['client_source_detail'] = '';
		DB::table('aml_system')->where('client_id', $id)->update($data);

		$client_source='
		<input type="radio" name="client_source" class="other_client" value="1" id="other_client_'.$id.'" data-element="'.$id.'" data-toggle="modal" data-target=".other_client_modal" value="1"><label for="other_client_'.$id.'">Other Client</label><br/>
		<input type="radio" name="client_source" class="partner_class" data-toggle="modal" data-target=".partner_modal" value="2"  data-element="'.$id.'" id="personal_partner_'.$id.'"><label for="personal_partner_'.$id.'">Personal Acquaintance of Partner</label><br/>
		<input type="radio" name="client_source"  class="reply_class" data-toggle="modal" data-target=".reply_modal"  value="3" data-element="'.$id.'" id="reply_note_'.$id.'"><label for="reply_note_'.$id.'">Reply to Advert / Walk in</label>';

		echo json_encode(array('output' => $client_source, 'id' => $id));
	}

	public function aml_system_risk_update(){
		$id = Input::get('id');
		$value = Input::get('value');

		$aml_details = DB::table('aml_system')->where('client_id', $id)->first();

		if(count($aml_details)){
			$data['risk_category'] = $value;
			DB::table('aml_system')->where('client_id', $id)->update($data);
		}
		else{
			$data['client_id'] = $id;
			$data['risk_category'] = $value;
			DB::table('aml_system')->where('client_id', $id)->insert($data);
		}
		
	}

	public function aml_client_search()

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
	
	public function aml_clientsearchselect(){
		$id = Input::get('value');		
		
	}
	
	public function aml_system_other_client(){
		$client_id = Input::get('client_id');
		$type = Input::get('type');
		$current_client_id = Input::get('current_client_id');

		$aml_details = DB::table('aml_system')->where('client_id', $current_client_id)->first();

		if(count($aml_details)){
			$data['client_source'] = $type;
			$data['client_source_detail'] = $client_id;			
			DB::table('aml_system')->where('client_id', $current_client_id)->update($data);
		}
		else{
			$data['client_id'] = $current_client_id;
			$data['client_source'] = $type;
			$data['client_source_detail'] = $client_id;	
			DB::table('aml_system')->where('client_id', $current_client_id)->insert($data);
		}

		$client = DB::table('aml_system')->where('client_id', $current_client_id)->first();		
		$client_details = DB::table('cm_clients')->where('client_id', $client->client_source_detail)->first();

		$client_source ='<a href="javascript:" data-text="Other Client - '.$client_details->company.' - '.$client_details->firstname.' '.$client_details->surname.'" class="download_client_source"> Other Client - '.$client_details->company.' - '.$client_details->firstname.' '.$client_details->surname.'.txt</a>
		<a href="javascript:" class="refresh_icon" data-toggle="tooltip" data-original-title="Edit Client Source"><i class="fa fa-edit refresh_client_source" data-element='.$client->client_id.'></i></a>

		<a href="javascript:" class="refresh_icon" data-toggle="tooltip" data-original-title="Delete Client Source"><i class="fa fa-trash refresh_client_source" data-element='.$client->client_id.'></i></a> ';

        echo json_encode(array('output' => $client_source, 'id' =>$current_client_id));
	}

	public function aml_system_partner(){
		$user_type = Input::get('user_type');
		$type = Input::get('type');
		$current_client_id = Input::get('current_client_id');

		$aml_details = DB::table('aml_system')->where('client_id', $current_client_id)->first();

		if(count($aml_details)){
			$data['client_source'] = $type;
			$data['client_source_detail'] = $user_type;			
			DB::table('aml_system')->where('client_id', $current_client_id)->update($data);
		}
		else{
			$data['client_id'] = $current_client_id;
			$data['client_source'] = $type;
			$data['client_source_detail'] = $user_type;	
			DB::table('aml_system')->where('client_id', $current_client_id)->insert($data);
		}

		$client = DB::table('aml_system')->where('client_id', $current_client_id)->first();		
		$user_details = DB::table('user')->where('user_id', $user_type)->first();

		$client_source ='<a href="javascript:" data-text="Partner - '.$user_details->firstname.' '.$user_details->lastname.'" class="download_client_source"> Partner - '.$user_details->firstname.' '.$user_details->lastname.'.txt</a>
		<a href="javascript:" class="refresh_icon" data-toggle="tooltip" data-original-title="Edit Client Source"><i class="fa fa-edit refresh_client_source" data-element='.$client->client_id.'></i></a>

		<a href="javascript:" class="refresh_icon" data-toggle="tooltip" data-original-title="Delete Client Source"><i class="fa fa-trash refresh_client_source" data-element='.$client->client_id.'></i></a>';

        echo json_encode(array('output' => $client_source, 'id' =>$current_client_id));
	}

	public function aml_system_note(){
		$reply_note = Input::get('reply_note');
		$type = Input::get('type');
		$current_client_id = Input::get('current_client_id');

		$aml_details = DB::table('aml_system')->where('client_id', $current_client_id)->first();

		if(count($aml_details)){
			$data['client_source'] = $type;
			$data['client_source_detail'] = $reply_note;			
			DB::table('aml_system')->where('client_id', $current_client_id)->update($data);
		}
		else{
			$data['client_id'] = $current_client_id;
			$data['client_source'] = $type;
			$data['client_source_detail'] = $reply_note;	
			DB::table('aml_system')->where('client_id', $current_client_id)->insert($data);
		}

		$client = DB::table('aml_system')->where('client_id', $current_client_id)->first();		
		

		$client_source ='<a href="javascript:" data-text="Note - '.$client->client_source_detail.'" class="download_client_source"> Note - '.$client->client_source_detail.'.txt</a> 
		<a href="javascript:" class="refresh_icon" data-toggle="tooltip" data-original-title="Edit Client Source"><i class="fa fa-edit refresh_client_source" data-element='.$client->client_id.'></i></a>

		<a href="javascript:" class="refresh_icon" data-toggle="tooltip" data-original-title="Delete Client Source"><i class="fa fa-trash refresh_client_source" data-element='.$client->client_id.'></i></a>';

        echo json_encode(array('output' => $client_source, 'id' =>$current_client_id));
	}

	public function aml_system_add_bank(){		
		$current_client_id = Input::get('current_client_id');
		$bank_name = Input::get('bank_name');
		$account_name = Input::get('account_name');
		$account_number = Input::get('account_number');

		

		$data['client_id'] = $current_client_id;
		$data['bank_name'] = $bank_name;
		$data['account_name'] = $account_name;
		$data['account_number'] = $account_number;
		DB::table('aml_bank')->where('client_id', $current_client_id)->insert($data);

		$client = DB::table('aml_system')->where('client_id', $current_client_id)->first();	

		$aml_bank_count = DB::table('aml_bank')->where('client_id', $current_client_id)->count();			

		$output = '<a href="javascript:" class="bank_detail_class" data-element="'.$current_client_id.'">'.$aml_bank_count.'</a><a href="javascript:" class="bank_class" data-toggle="modal" data-toggle="modal" data-target=".bank_modal"><i class="fa fa-plus add_bank" title="Add Bank" data-element="'.$current_client_id.'"  style="margin-left:10px;"></i></a>';

        echo json_encode(array('output' => $output, 'id' =>$current_client_id));
	}

	public function aml_system_bank_details(){		
		$client_id = Input::get('client_id');

		$aml_bank_list = DB::table('aml_bank')->where('client_id', $client_id)->get();
		$output='';
		$i=1;
		if(count($aml_bank_list)){
			foreach ($aml_bank_list as $bank) {
				$output.='
				<tr>
					<td>'.$i.'</td>
					<td>'.$bank->bank_name.'</td>
					<td>'.$bank->account_name.'</td>
					<td>'.$bank->account_number.'</td>
				</tr>
				';
				$i++;
			}
		}

		$company = DB::table('cm_clients')->where('client_id', $client_id)->first();

		echo json_encode(array('output' => $output, 'company_name' => $company->company));

	}

	public function aml_system_review(){

		$review_date = Input::get('review_date');
		$reivew_filed = Input::get('reivew_filed');
		$current_client_id = Input::get('current_client_id');

		$aml_details = DB::table('aml_system')->where('client_id', $current_client_id)->first();

		if(count($aml_details)){			
			$data['review'] = '1';
			$data['review_date'] = date('Y-m-d', strtotime($review_date));
			$data['file_review'] = $reivew_filed;			
			DB::table('aml_system')->where('client_id', $current_client_id)->update($data);
		}
		else{
			$data['client_id'] = $current_client_id;
			$data['review'] = '1';
			$data['review_date'] = date('Y-m-d', strtotime($review_date));
			$data['file_review'] = $reivew_filed;	
			DB::table('aml_system')->where('client_id', $current_client_id)->insert($data);
		}

		$aml_system = DB::table('aml_system')->where('client_id', $current_client_id)->first();	

		$output_reveiw = 'Date:'.date('d-M-Y', strtotime($aml_system->review_date)).'</br/>Review By: '.$aml_system->file_review.'<br/><a href="javascript:"><i class="fa fa-pencil-square edit_review" data-element="'.$aml_system->client_id.'"></i></a><a href="javascript:"  style="margin-left:10px;"><i class="fa fa-trash delete_review" data-element="'.$aml_system->client_id.'"></i></a>';

		echo json_encode(array('output' => $output_reveiw, 'id' => $current_client_id));

	}

	public function aml_system_review_edit(){
		$current_client_id = Input::get('current_client');
		$aml_details = DB::table('aml_system')->where('client_id', $current_client_id)->first();
		echo json_encode(array('output' => $aml_details->file_review, 'date' => date('d-M-Y', strtotime($aml_details->review_date))));
	}

	public function aml_system_review_edit_update(){

		$review_date = Input::get('review_date');
		$reivew_filed = Input::get('reivew_filed');
		$current_client_id = Input::get('current_client_id');

		$data['review_date'] = date('Y-m-d', strtotime($review_date));
		$data['file_review'] = $reivew_filed;

		DB::table('aml_system')->where('client_id', $current_client_id)->update($data);

		$aml_system = DB::table('aml_system')->where('client_id', $current_client_id)->first();

		$output_reveiw = 'Date:'.date('d-M-Y', strtotime($aml_system->review_date)).'</br/>Review By: '.$aml_system->file_review.'<br/><a href="javascript:"><i class="fa fa-pencil-square edit_review" data-element="'.$aml_system->client_id.'"></i></a><a href="javascript:"  style="margin-left:10px;"><i class="fa fa-trash delete_review" data-element="'.$aml_system->client_id.'"></i></a>';

		echo json_encode(array('output' => $output_reveiw, 'id' => $current_client_id));
	}

	public function aml_system_review_delete(){
		$current_client = Input::get('current_client');
		$data['review'] = '0';
		$data['file_review'] = '';

		DB::table('aml_system')->where('client_id', $current_client)->update($data);

		$output_reveiw ='
			<div class="select_button" style=" margin-left: 10px;">
            <ul>                                    
            <li><a href="javascript:" class="review_by" data-element="'.$current_client.'" style="font-size: 13px; font-weight: 500;">Review By</a></li>
          </ul>
        </div>';

        echo json_encode(array('output' => $output_reveiw, 'id' => $current_client));
	}

	public function aml_upload_images_add()
	{
		$current_client= Input::get('client_id');
		if (!empty($_FILES)) {
			 $tmpFile = $_FILES['file']['tmp_name'];
			 $fname = str_replace("#","",$_FILES['file']['name']);
			 $fname = str_replace("#","",$fname);
			 $fname = str_replace("#","",$fname);
			 $fname = str_replace("#","",$fname);

			 $fname = str_replace("%","",$fname);
			 $fname = str_replace("%","",$fname);
			 $fname = str_replace("%","",$fname);

			$upload_dir = 'uploads/aml_image';
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
			$upload_dir = $upload_dir.'/'.base64_encode($current_client);
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
     		
			$dataval['client_id'] = $current_client;
 			$dataval['url'] = $upload_dir;
			$dataval['attachment'] = $fname;
			$id = DB::table('aml_attachment')->insertGetid($dataval);
			
			$filename = $upload_dir.'/'.$fname;
			move_uploaded_file($tmpFile,$filename);

		 	echo json_encode(array('id' => $id,'filename' => $fname));
		}
	}
	public function aml_system_image_upload(){
		$current_client = Input::get('current_client');

		if(Session::has('aml_attach_add'))
		{
			$files = Session::get('aml_attach_add');

			$upload_dir = 'uploads/aml_image';
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
			$upload_dir = $upload_dir.'/'.base64_encode($current_client);
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}

     		$dir = "uploads/aml_image/temp";//"path/to/targetFiles";
		    $dirNew = $upload_dir;//path/to/destination/files
		    // Open a known directory, and proceed to read its contents
		    if (is_dir($dir)) {
		        if ($dh = opendir($dir)) {
		            while (($file = readdir($dh)) !== false) {
			            if ($file==".") continue;
			            if ($file=="..")continue;
			            rename($dir.'/'.$file,$dirNew.'/'.$file);
		            }
		            closedir($dh);
		        }
		    }
     		
			foreach($files as $file)
			{
				//rename("uploads/infile_image/temp/".$file['attachment'], $upload_dir.'/'.$file['attachment']);
				$dataval['client_id'] = $current_client;
     			$dataval['url'] = $upload_dir;
				$dataval['attachment'] = $file['attachment'];
				Db::table('aml_attachment')->insert($dataval);
			}
			
			
		}

		Session::forget("aml_attach_add");

		$images_list = DB::table('aml_attachment')->where('client_id', $current_client)->get();

		$output='';
		if(count($images_list)){
			foreach ($images_list as $image) {
				if($image->standard_name == "")
                {
					$output.='<a href="'.URL::to('/'.$image->url.'/'.$image->attachment).'" download>'.$image->attachment.'</a><i class="fa fa-trash delete_attached" style="cursor:pointer; margin-left:10px; color:#000;" data-element="'.$image->id.'"></i><br/>';
				}
				else{
					$output.='<a href="'.URL::to('/'.$image->url.'/'.$image->attachment).'" download="'.$image->standard_name.'">'.$image->standard_name.'</a><i class="fa fa-trash delete_attached" style="cursor:pointer; margin-left:10px; color:#000;" data-element="'.$image->id.'"></i><br/>';
				}
			}
		}
		echo json_encode(array('output' => $output, 'id' => $current_client));


	}

	public function aml_system_delete_attached(){
		$id = Input::get('id');
		$row = DB::table('aml_attachment')->where('id', $id)->first();
		$current_client = $row->client_id;
		DB::table('aml_attachment')->where('id', $id)->delete();

		$images_list = DB::table('aml_attachment')->where('client_id', $current_client)->get();

		$output='';
		if(count($images_list)){
			foreach ($images_list as $image) {
				if($image->standard_name == "")
                {
					$output.='<a href="'.URL::to('/'.$image->url.'/'.$image->attachment).'" download>'.$image->attachment.'</a><i class="fa fa-trash delete_attached" style="cursor:pointer; margin-left:10px; color:#000;" data-element="'.$image->id.'"></i><br/>';
				}
				else{
					$output.='<a href="'.URL::to('/'.$image->url.'/'.$image->attachment).'" download="'.$image->standard_name.'">'.$image->standard_name.'</a><i class="fa fa-trash delete_attached" style="cursor:pointer; margin-left:10px; color:#000;" data-element="'.$image->id.'"></i><br/>';
				}
			}
		}
		else{
			$output.='';
		}
		echo json_encode(array('output' => $output, 'id' => $current_client));

	}

	public function aml_system_client_since(){
		$current_client =Input::get('current_client');
		$date = Input::get('date');		

		$aml_details = DB::table('aml_system')->where('client_id', $current_client)->first();

		if(count($aml_details)){									
			$data['data_client'] = date('Y-m-d', strtotime($date));
			DB::table('aml_system')->where('client_id', $current_client)->update($data);
		}
		else{
			$data['client_id'] = $current_client;			
			$data['data_client'] = date('Y-m-d', strtotime($date));
			DB::table('aml_system')->where('client_id', $current_client)->insert($data);
		}

		echo json_encode(array('output' => $date, 'id' => $current_client));
	}

	public function aml_report_pdf(){
		$ids = explode(",",Input::get('value'));
		$type = Input::get('type');
		$clientlist = DB::table('cm_clients')->whereIn('client_id', $ids)->get();

		$output='';
		$i=1;
		if(count($clientlist)){
			foreach ($clientlist as $client) {

				$aml_system = DB::table('aml_system')->where('client_id', $client->client_id)->first();

				if(count($aml_system)){
					if($aml_system->client_source == 1){
						$client_details = DB::table('cm_clients')->where('client_id', $aml_system->client_source_detail)->first();
						$client_source = 'Other - '.$client_details->firstname;
					}
					elseif($aml_system->client_source == 2){
						$client_details = DB::table('user')->where('user_id', $aml_system->client_source_detail)->first();
						$client_source = 'Partner - '.$client_details->firstname.' '.$client_details->lastname;
					}
					elseif($aml_system->client_source == 3){						
						$client_source = 'Notes - '.$aml_system->client_source_detail;
					}
					else{
						$client_source = '';
					}
				}
				else{
					$client_source = '';
				}

				if(count($aml_system)){
	                if($aml_system->data_client !=   '0000-00-00'){
	                  $output_client_since = date('d-M-Y', strtotime($aml_system->data_client));
	                }
	                else{
	                  $output_client_since = '';
	                }
	              }
	              else{
	                  $output_client_since = '';
	                }

	             $aml_attachement = DB::table('aml_attachment')->where('client_id', $client->client_id)->get();
	             $output_attached='';
                  if(count($aml_attachement)){
                    foreach ($aml_attachement as $attached) {
                      $output_attached.=$attached->attachment.'<br/>';
                    }
                  }
                  else{
                    $output_attached.='';
                  }
                $aml_count = DB::table('aml_bank')->where('client_id', $client->client_id)->count();

                if(count($aml_count)){
                	$bank_count = $aml_count;
                }
                else{
                	$bank_count = '';
                }

                if(count($aml_system)){
                	if($aml_system->review == 1){
                		$review_output = 'Date: '.date('d-M-Y', strtotime($aml_system->review_date)).'<br/>Review: '.$aml_system->file_review;
                	}
                	else{
                		$review_output= '';
                	}                	
                	
                }
                else{
                	$review_output = '';
                }
                if(count($aml_system)){
                	if($aml_system->risk_category == 1){
                		$risk = 'Green';
                	}
                	elseif($aml_system->risk_category == 2){
                		$risk = 'Yellow';
                	}
                	elseif($aml_system->risk_category == 3){
                		$risk = 'Red';
                	}
                	elseif($aml_system->risk_category == 0){
                		$risk = 'Green';
                	}
                	

                }
                else{
                		$risk = 'Green';
                	}



				$output.='
				<tr>
					<td style="text-align: left;border:1px solid #000;">'.$i.'</td>
					<td style="text-align: left;border:1px solid #000;">'.$client->client_id.'</td>
					<td style="text-align: left;border:1px solid #000;">'.$client->company.'</td>
					<td style="text-align: left;border:1px solid #000;">'.$client->firstname.'</td>
					<td style="text-align: left;border:1px solid #000;">'.$client->surname.'</td>
					<td style="text-align: left;border:1px solid #000;">'.$client_source.'</td>
					<td style="text-align: left;border:1px solid #000;">'.$output_client_since.'</td>
					<td style="text-align: left;border:1px solid #000;">'.$output_attached.'</td>
					<td style="text-align: left;border:1px solid #000;">'.$bank_count.'</td>					
					<td style="text-align: left;border:1px solid #000;">'.$review_output.'</td>
					<td style="text-align: left;border:1px solid #000;">'.$risk.'</td>
				</tr>
				';
				$i++;
			}
		}

		echo $output;


	}


	public function aml_download_report_pdfs (){
		$htmlval = Input::get('htmlval');
		$pdf = PDF::loadHTML($htmlval);
		$pdf->setPaper('A4', 'landscape');
		$pdf->save('papers/AML Report.pdf');
		echo 'AML Report.pdf';
	}
	public function aml_remove_dropzone_attachment()
	{
		$attachment_id = $_POST['attachment_id'];
		$check_network = DB::table('aml_attachment')->where('id',$attachment_id)->first();

		DB::table('aml_attachment')->where('id',$attachment_id)->delete();
	}
	public function notify_tasks_aml()
	{
		$clientlist = DB::table('cm_clients')->select('client_id', 'firstname', 'surname', 'company', 'status', 'active', 'id','email','email2')->orderBy('id','asc')->get();
		$output = '<table class="table" style="width:100%">';
		if(count($clientlist))
		{
			$output.= '<tr class="background_bg"><td>Task Name</td><td>Notify</td><td>Primary Email</td><td>Secondary Email</td></tr>';
			foreach($clientlist as $client)
			{
				$disabled='';
				if($client->active != "")
              {
              	if($client->active == 2)
                {
                  $disabled='inactive';
                }
                $check_color = DB::table('cm_class')->where('id',$client->active)->first();
                $style="color:#".$check_color->classcolor.";font-weight:600";
                $class = '';
              }
              else{
                $style="color:#000 !important;font-weight:600";
              }
              $get_identity = DB::table('aml_attachment')->where('client_id',$client->client_id)->count();
              if($get_identity > 0)
              {
              	$identity_received = 'identity_received';
              }
              else{
              	$identity_received = '';
              }
				$output.='<tr class="'.$disabled.' '.$identity_received.'">
					<td style="'.$style.'">'.$client->company.'- '.$client->client_id.'</td>
					<td style="text-align:center">
						<input type="checkbox" name="notify_option" class="notify_option" data-element="'.$client->client_id.'" value="1"><label >&nbsp;</label>
					</td>
				<td style="text-align:center;color:#000 !important;"><input type="text" name="notify_primary_email" class="notify_primary_email form-control" value="'.$client->email.'" data-element="'.$client->client_id.'" readonly></td>
				<td style="text-align:center;color:#000 !important;"><input type="text" name="notify_secondary_email" class="notify_secondary_email form-control" value="'.$client->email2.'" data-element="'.$client->client_id.'" readonly></td>
				</tr>';
			}
		}
		$output.='</table>
		<h5 style="font-weight:800; margin-top:15px">MESSAGE :</h5>
		<textarea class="form-control input-sm" id="editor_1" name="notify_message" style="height:120px">
			<p>Hi</p>
			<p>As part of fraud and anti-money laundering legislation we are required to obtain a copy of your identification (a copy of your drivers license or passport is sufficient).</p>
			<p>Please can you reply to us with a copy of your ID (or the Directors & Secretaries ID if you are a limited company).</p>
			<p><strong>Cheers,</strong></p>
			<p><strong>Easypayroll.ie</strong></p>
		</textarea>';
		echo $output;
	}
	public function email_notify_aml()
	{
		$email = Input::get('email');
		$time = Input::get('timeval');
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
		$subject = 'GBS & Co: Fraud and Anti Money Laundering ID Required';
		$email = new PHPMailer();
		if($to != '')
		{
			$get_client = DB::table('cm_clients')->where('email',$to)->orwhere('email2',$to)->first();
			if(count($get_client))
			{
				$datamessage['message_id'] = $time;
				$datamessage['message_from'] = 0;
				$datamessage['subject'] = $subject;
				$datamessage['message'] = $contentmessage;
				$datamessage['client_ids'] = $get_client->client_id;
				if($get_client->email == $to)
				{
					$datamessage['primary_emails'] = $get_client->email;
				}
				else{
					$datamessage['secondary_emails'] = $get_client->email2;
				}
				$datamessage['source'] = "AML SYSTEM";
				$datamessage['attachments'] = "";
				$datamessage['date_sent'] = date('Y-m-d H:i:s');
				$datamessage['date_saved'] = date('Y-m-d H:i:s');
				$datamessage['status'] = 1;

				DB::table('messageus')->insert($datamessage);
			}
			$email->SetFrom($from); //Name is optional
			$email->Subject   = $subject;
			$email->Body      = $contentmessage;
			$email->AddCC($admin_cc);
			$email->IsHTML(true);
			$email->AddAddress( $to );
			$email->Send();	
		}
	}
	public function aml_edit_email_unsent_files()
	{
		$client_id = Input::get('client_id');
		$result = DB::table('cm_clients')->where('client_id',$client_id)->first();
		

		if($result->email2 != '')
	    {
	      	$to_email = $result->email.', '.$result->email2;
	    }
	    else{
	      	$to_email = $result->email;
        }

        $aml_system = DB::table('aml_system')->where('client_id',$client_id)->first();
        if(count($aml_system))
        {
        	$date = date('d F Y', strtotime($aml_system->last_email_sent));
			$time = date('H:i', strtotime($aml_system->last_email_sent));
			$last_date = $date.' @ '.$time;
        }
		else{
			$date = date('d F Y');
			$time = date('H:i');
			$last_date = $date.' @ '.$time;
		}
		
		$admin_details = Db::table('admin')->first();
		$admin_cc = $admin_details->p30_cc_email;
		
		$data['sentmails'] = $to_email.', '.$admin_cc;
		$data['logo'] = URL::to('assets/images/easy_payroll_logo.png');
		
		$data['salutation'] = $result->salutation;
		$contentmessage = view('user/aml_email_content', $data)->render();
      	$subject = 'GBS & Co: Fraud and Anti Money Laundering ID Required';

	     echo json_encode(["html" => $contentmessage, "to" => $to_email,'subject' => $subject,'last_email_sent' => $last_date]);
	}
	public function aml_email_unsent_files()
	{
		$client_id = Input::get('client_id');
		$det_task = DB::table('cm_clients')->where('client_id',$client_id)->first();
		

		$from = Input::get('from');
		$toemails = Input::get('to').','.Input::get('cc');
		$sentmails = Input::get('to').', '.Input::get('cc');
		$subject = Input::get('subject'); 
		$message = Input::get('content');
		
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

				$email = new PHPMailer();
				$email->SetFrom($from); //Name is optional
				$email->Subject   = $subject;
				$email->Body      = $contentmessage;
				$email->IsHTML(true);
				$email->AddAddress( $to );
				$email->Send();			
			}
			$user_details = DB::table('user')->where('email',$from)->first();
			if(count($user_details))
			{
				$user_from = $user_details->user_id;
			}
			else{
				$user_from = 0;
			}
			$datamessage['message_id'] = time();
			$datamessage['message_from'] = $user_from;
			$datamessage['subject'] = $subject;
			$datamessage['message'] = $contentmessage;
			$datamessage['source'] = "AML SYSTEM";
			$datamessage['client_ids'] = $client_id;
			$datamessage['primary_emails'] = $toemails;
			$datamessage['date_sent'] = date('Y-m-d H:i:s');
			$datamessage['date_saved'] = date('Y-m-d H:i:s');
			$datamessage['status'] = 1;

			DB::table('messageus')->insert($datamessage);

			$aml_system = DB::table('aml_system')->where('client_id',$client_id)->first();
			if(count($aml_system))
			{
				$date = date('Y-m-d H:i:s');
				DB::table('aml_system')->where('client_id',$client_id)->update(['last_email_sent' => $date]);
			}
			else{
				$date = date('Y-m-d H:i:s');
				$dataval['last_email_sent'] = $date;
				$dataval['client_id'] = $client_id;
				$dataval['client_source'] = 0;
				$dataval['review'] = 0;
				$dataval['risk_category'] = 0;
				DB::table('aml_system')->insert($dataval);
			}

			$dateformat = date('d M Y @ H:i', strtotime($date));
			echo $dateformat;
			// return redirect('user/paye_p30_manage/'.$encoded_year_id.'?divid=taskidtr_'.$det_task->paye_task)->with('message', 'Email Sent Successfully');
		}
		else{
			echo "0";
			// return redirect('user/paye_p30_manage/'.$encoded_year_id.'?divid=taskidtr_'.$det_task->paye_task)->with('error', 'Email Field is empty so email is not sent');
		}
	}
	public function standard_file_name()
	{
		$get_clients = DB::table('cm_clients')->get();
		if(count($get_clients))
		{
			foreach($get_clients as $client)
			{
				$get_attachments = DB::table('aml_attachment')->where('client_id',$client->client_id)->get();
				$i = 1;
				if(count($get_attachments))
				{
					foreach($get_attachments as $attach)
					{
						$get_ext = explode(".",$attach->attachment);
						$data['standard_name'] = $client->client_id.'_IDFile_'.$i.'.'.end($get_ext);
						DB::table('aml_attachment')->where('id',$attach->id)->update($data);
						$i++;
					}
				}
			}
		}
		return Redirect::back()->with('message',"Standard File Name has been set for all the clients' attachments.");
	}
	public function generate_aml_text_file()
	{
		$text = Input::get('text');
		$upload_dir = 'papers/aml_client_source';
		if(!file_exists($upload_dir))
		{
			mkdir($upload_dir);
		}
		$files = glob($upload_dir); // get all file names
		foreach($files as $file){ // iterate files
		  if(is_file($file))
		    unlink($file); // delete file
		}
		$myfile = fopen($upload_dir.'/'.$text.".txt", "w") or die("Unable to open file!");
		fwrite($myfile, $text);
		fclose($myfile);
		echo $text.".txt";
	}
	public function aml_system_add_trade(){		
		$current_client_id = Input::get('current_client_id');
		$products_services = Input::get('products_services');
		$transaction_type = Input::get('transaction_type');
		$risk_factors = Input::get('risk_factors');
		$geo_area = Input::get('geo_area');

		$upload_dir = 'uploads/aml_trade_details';
		if(!file_exists($upload_dir))
		{
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/'.$current_client_id;
		if(!file_exists($upload_dir))
		{
			mkdir($upload_dir);
		}

		$text = 'Products & Services :'.$products_services. PHP_EOL .'Type of Transaction :'.$transaction_type. PHP_EOL .'Risk Factors :'.$risk_factors. PHP_EOL .'Geo Area of Operation :'.$geo_area;
		$myfile = fopen($upload_dir.'/Trade Details.txt', "w") or die("Unable to open file!");
		fwrite($myfile, $text);
		fclose($myfile);

		$data['trade_details'] = 'Trade Details.txt';
		$data['products_services'] = $products_services;
		$data['transaction_type'] = $transaction_type;
		$data['risk_factors'] = $risk_factors;
		$data['geo_area'] = $geo_area;

		$check_aml = DB::table('aml_system')->where('client_id', $current_client_id)->first();
		if(count($check_aml))
		{
			DB::table('aml_system')->where('client_id', $current_client_id)->update($data);
		}
		else{
			$data['client_id'] = $current_client_id;
			DB::table('aml_system')->where('client_id', $current_client_id)->insert($data);
		}
		
		$output = '<a href="'.URL::to('uploads/aml_trade_details').'/'.$current_client_id.'/'.$data['trade_details'].'" download>'.$data['trade_details'].'</a><br/>
		<a href="javascript:" class="fa fa-edit trade_details_edit trade_details_'.$current_client_id.'" data-element="'.$current_client_id.'"></a>';

        echo json_encode(array('output' => $output, 'id' =>$current_client_id));
	}
	public function get_trade_details()
	{
		$client_id = Input::get('current_client');
		$get_aml = DB::table('aml_system')->where('client_id',$client_id)->first();
		if(count($get_aml))
		{
			echo json_encode(array('products_services' => $get_aml->products_services,'transaction_type' => $get_aml->transaction_type,'risk_factors' => $get_aml->risk_factors,'geo_area' => $get_aml->geo_area));
		}
	}
}
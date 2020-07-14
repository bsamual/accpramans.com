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
use PHPWord; 
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use Hash;
use ZipArchive;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class YearendController extends Controller {



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

	public function YearendController(){
		$year = DB::table('year_end_year')->where('status', 0)->orderBy('year','desc')->get();		
		return view('user/yearend/yearend', array('title' => 'Year End Manger', 'yearlist' => $year));
	}

	public function yearend_crypt_validdation(){

		$pin = Input::get('crypt');
		$admin_details = DB::table('admin')->first();
		$type = Input::get('type');
		if(Hash::check($pin,$admin_details->cm_crypt))
		{
			if($type == 0){
                        $result = true;
                        $drop = '
                        <div class="form-title">Choose Year</div>
                        <select class="form-control year_class" name="year" required>
                              <option value="">Select Year</option>
                              <option value="2000">2000</option>
                              <option value="2001">2001</option>
                              <option value="2002">2002</option>
                              <option value="2003">2003</option>
                              <option value="2004">2004</option>
                              <option value="2005">2005</option>
                              <option value="2006">2006</option>
                              <option value="2007">2007</option>
                              <option value="2008">2008</option>
                              <option value="2009">2009</option>
                              <option value="2010">2010</option>
                              <option value="2011">2011</option>
                              <option value="2012">2012</option>
                              <option value="2013">2013</option>
                              <option value="2014">2014</option>
                              <option value="2015">2015</option>
                              <option value="2016">2016</option>
                              <option value="2017">2017</option>
                              <option value="2018">2018</option>
                              <option value="2019">2019</option>
                              <option value="2020">2020</option>
                              <option value="2021">2021</option>
                              <option value="2022">2022</option>
                              <option value="2023">2023</option>
                              <option value="2024">2024</option>
                              <option value="2025">2025</option>
                              <option value="2026">2026</option>
                              <option value="2027">2027</option>
                              <option value="2028">2028</option>
                              <option value="2029">2029</option>
                              <option value="2030">2030</option>
                              <option value="2031">2031</option>
                              <option value="2032">2032</option>
                              <option value="2033">2033</option>
                              <option value="2034">2034</option>
                              <option value="2035">2035</option>
                              <option value="2036">2036</option>
                              <option value="2037">2037</option>
                              <option value="2038">2038</option>
                              <option value="2039">2039</option>
                              <option value="2040">2040</option>
                              <option value="2041">2041</option>
                              <option value="2042">2042</option>
                              <option value="2043">2043</option>
                              <option value="2044">2044</option>
                              <option value="2045">2045</option>
                              <option value="2046">2046</option>
                              <option value="2047">2047</option>
                              <option value="2048">2048</option>
                              <option value="2049">2049</option>
                              <option value="2050">2050</option>
                              <option value="2051">2051</option>
                              <option value="2052">2052</option>
                              <option value="2053">2053</option>
                              <option value="2054">2054</option>
                              <option value="2055">2055</option>
                              <option value="2056">2056</option>
                              <option value="2057">2057</option>
                              <option value="2058">2058</option>
                              <option value="2059">2059</option>
                              <option value="2060">2060</option>
                              <option value="2061">2061</option>
                              <option value="2062">2062</option>
                              <option value="2063">2063</option>
                              <option value="2064">2064</option>
                              <option value="2065">2065</option>
                              <option value="2066">2066</option>
                              <option value="2067">2067</option>
                              <option value="2068">2068</option>
                              <option value="2069">2069</option>
                              <option value="2070">2070</option>
                              <option value="2071">2071</option>
                              <option value="2072">2072</option>
                              <option value="2073">2073</option>
                              <option value="2074">2074</option>
                              <option value="2075">2075</option>
                              <option value="2076">2076</option>
                              <option value="2077">2077</option>
                              <option value="2078">2078</option>
                              <option value="2079">2079</option>
                              <option value="2080">2080</option>
                              <option value="2081">2081</option>
                              <option value="2082">2082</option>
                              <option value="2083">2083</option>
                              <option value="2084">2084</option>
                              <option value="2085">2085</option>
                              <option value="2086">2086</option>
                              <option value="2087">2087</option>
                              <option value="2088">2088</option>
                              <option value="2089">2089</option>
                              <option value="2090">2090</option>
                              <option value="2091">2091</option>
                              <option value="2092">2092</option>
                              <option value="2093">2093</option>
                              <option value="2094">2094</option>
                              <option value="2095">2095</option>
                              <option value="2096">2096</option>
                              <option value="2097">2097</option>
                              <option value="2098">2098</option>
                              <option value="2099">2090</option>
                              <option value="2100">2100</option>
                              </select>';
                        $button = '<input type="submit" class="common_black_button year_button" value="Create a Year">';
                  }
                  else{
                        $result = true;
                        $drop = '
                              <select class="form-control setting_type">
                                    <option value="">Select Type</option>
                                    <option value="1">Supplementary notes</option>
                                    <option value="2">Year End Documents</option>
                              </select>';
                        $button = '<a href="#" class="common_black_button setting_button">Submit</a>';
                  }

		}
		else{
			$result = false;
			$drop = '';
                  $button = '';
		}

		echo json_encode(array('security' => $result, 'drop' => $drop, 'create_button' => $button));
	}

      public function year_first_create(){

            $year = Input::get('year');          
            $client_details = DB::table('cm_clients')->where('active', 1)->get();
            if(count($client_details)){
                  foreach ($client_details as $client) {
                        $data['year'] = $year;
                        $data['client_id'] = $client->client_id;
                        $data['updatetime'] = date('Y-m-d H:i:s');
                        
                        DB::table('year_client')->insert($data);
                  }
            }


            $client_update = DB::table('cm_clients')->get();
            if(count($client_update)){
                  foreach ($client_update as $clients) {
                        $dataclient['yearend_updatetime'] = date('Y-m-d H:i:s');
                        DB::table('cm_clients')->where('client_id', $clients->client_id)->update($dataclient);
                  }
            }

            

            $id = DB::table('year_end_year')->insertGetid(['year' => $year, 'updatetime' => date('Y-m-d H:i:s')]);
            DB::table('year_end_year')->where('id',$id)->update(['updatetime' => date('Y-m-d H:i:s')]);
            return Redirect::back()->with('message', 'Year Created successfully');
      }
      public function yearend_create_new_year()
      {
            $get_last_created_year = DB::table('year_end_year')->orderBy('id', 'desc')->first();
            $current_year = $get_last_created_year->year + 1;

            $client_details = DB::table('cm_clients')->where('active', 1)->get();
            if(count($client_details)){
                  foreach ($client_details as $client) {
                        $data['year'] = $current_year;
                        $data['client_id'] = $client->client_id;
                        $data['setting_id'] = $get_last_created_year->setting_id;
                        $data['setting_active'] = $get_last_created_year->setting_active;
                        $data['updatetime'] = date('Y-m-d H:i:s');
                        
                        DB::table('year_client')->insert($data);
                  }
            }

            $client_update = DB::table('cm_clients')->get();
            if(count($client_update)){
                  foreach ($client_update as $clients) {
                        $dataclient['yearend_updatetime'] = date('Y-m-d H:i:s');
                        DB::table('cm_clients')->where('client_id', $clients->client_id)->update($dataclient);
                  }
            }

            $id = DB::table('year_end_year')->insertGetid(['year' => $current_year,'setting_id'=>$get_last_created_year->setting_id,'setting_active' => $get_last_created_year->setting_active, 'updatetime' => date('Y-m-d H:i:s')]);
            DB::table('year_end_year')->where('id',$id)->update(['updatetime' => date('Y-m-d H:i:s')]);
            return Redirect::back()->with('message', 'Year Created successfully');

      }
      public function review_get_clients()
      {            
            $yearid = Input::get('yearid');            

            /*$year_details = Db::table('year_end_year')->where('year',$yearid)->first();
            $time = strtotime($year_details->updatetime);
            $time = $time + (1 * 60);
			$date = date("Y-m-d H:i:s", $time);

			$time = strtotime($date);*/
            $output = '
            <input type="checkbox" class="hide_deactivate_clients" id="hide_deactivate_clients"><label for="hide_deactivate_clients" style="float: right;">Hide Deactivated Clients</label><br/>

            <table class="table table_bg">
                  <thead>
                        <tr class="background_bg">
                              <th style="width:133px"><input type="checkbox" name="select_all_clients" class="select_all_clients" id="select_all_clients" value="1"> <label for="select_all_clients">Select All</label></th>
                              <th style="width:128px"><i class="fa fa-sort review_sort_clientid"></i>Client Id</th>
                              <th><i class="fa fa-sort review_sort_company"></i>Company Name</th>
                              <th>Status</th>
                        <tr>
                  </thead>
                  <tbody id="review_task_body">';
            $check_clients = DB::table('cm_clients')->get();

            

            
            if(count($check_clients)){              
                  foreach ($check_clients as $clients) {
                        $check_db = DB::table('year_client')->where('client_id',$clients->client_id)->where('year',$yearid)->count();
                        if($check_db == 0)
                        {
                              if($clients->active == 2) { $status = 'Deactive'; } else { $status = 'Active'; }
                              if($clients->active == 2){ $hide = 'hidden_tr'; $color = 'color:#f00 !important'; } else{ $hide =''; $color = 'color:#000 !important'; }
                              $output.='<tr class="review_task_tr '.$hide.'">
                                    <td><input type="checkbox" name="review_clients_checkbox[]" class="review_clients_checkbox" id="review_clients_checkbox_'.$clients->id.'" value="'.$clients->id.'"> <label for="review_clients_checkbox_'.$clients->id.'">&nbsp;</label></td>
                                    <td class="review_clientid_sort_val" style="'.$color.'">'.$clients->client_id.'</td>
                                    <td class="review_company_sort_val" style="'.$color.'">'.$clients->company.'</td>
                                    <td style="'.$color.'">'.$status.'</td>
                              </tr>';
                        }
                  }
            }
            else{
                  $output.='<tr>
                        <td></td>
                        <td style="color:#000 !important">No New Clients Found since this year was created</td>
                        <td></td>
                        <td></td>
                  </tr>';
            }
            $output.='</tbody>
            </table>
            <input type="hidden" name="hidden_yearid" id="hidden_yearid" value="'.$yearid.'">
            <input type="submit" class="common_black_button submit_review_clients" value="Add Clients to this year">';
            echo $output;
      }
      /*public function review_get_clients()
      {            
            $yearid = Input::get('yearid');            
            $year_details = Db::table('year_end_year')->where('year',$yearid)->first();
            $time = strtotime($year_details->updatetime);
            $time = $time + (1 * 60);
                  $date = date("Y-m-d H:i:s", $time);

                  $time = strtotime($date);
            $output = '
            <input type="checkbox" class="hide_deactivate_clients" id="hide_deactivate_clients"><label for="hide_deactivate_clients" style="float: right;">Hide Deactivated Clients</label><br/>

            <table class="table table_bg">
                  <thead>
                        <tr class="background_bg">
                              <th style="width:133px"><input type="checkbox" name="select_all_clients" class="select_all_clients" id="select_all_clients" value="1"> <label for="select_all_clients">Select All</label></th>
                              <th style="width:128px"><i class="fa fa-sort review_sort_clientid"></i>Client Id</th>
                              <th><i class="fa fa-sort review_sort_company"></i>Company Name</th>
                              <th>Status</th>
                        <tr>
                  </thead>
                  <tbody id="review_task_body">';
            $check_clients = DB::select('SELECT * from `cm_clients` WHERE UNIX_TIMESTAMP(`yearend_updatetime`) >= "'.$time.'"');
            if(count($check_clients)){              
                  foreach ($check_clients as $clients) {
                        if($clients->active == 2) { $status = 'Deactive'; } else { $status = 'Active'; }
                        if($clients->active == 2){ $hide = 'hidden_tr'; $color = 'color:#f00 !important'; } else{ $hide =''; $color = 'color:#000 !important'; }
                        $output.='<tr class="review_task_tr '.$hide.'">
                              <td><input type="checkbox" name="review_clients_checkbox[]" class="review_clients_checkbox" id="review_clients_checkbox_'.$clients->id.'" value="'.$clients->id.'"> <label for="review_clients_checkbox_'.$clients->id.'">&nbsp;</label></td>
                              <td class="review_clientid_sort_val" style="'.$color.'">'.$clients->client_id.'</td>
                              <td class="review_company_sort_val" style="'.$color.'">'.$clients->company.'</td>
                              <td style="'.$color.'">'.$status.'</td>
                        </tr>';
                  }
            }
            else{
                  $output.='<tr>
                        <td></td>
                        <td style="color:#000 !important">No New Clients Found since this year was created</td>
                        <td></td>
                        <td></td>
                  </tr>';
            }
            $output.='</tbody>
            </table>
            <input type="hidden" name="hidden_yearid" id="hidden_yearid" value="'.$yearid.'">
            <input type="submit" class="common_black_button submit_review_clients" value="Add Clients to this year">';
            echo $output;
      }*/
      public function review_clients_update()
      {
            $yearid = Input::get('hidden_yearid');
            $ids = implode(",",Input::get('review_clients_checkbox'));
            $get_last_created_year = DB::table("year_end_year")->where('year',$yearid)->first();

            $client_details = DB::table('cm_clients')->whereIn('id', Input::get('review_clients_checkbox'))->get();
            if(count($client_details)){
                  foreach ($client_details as $client) {
                        $data['year'] = $yearid;
                        $data['client_id'] = $client->client_id;
                        $data['setting_id'] = $get_last_created_year->setting_id;
                        $data['setting_active'] = $get_last_created_year->setting_active;
                        $data['updatetime'] = date('Y-m-d H:i:s');
                        
                        DB::table('year_client')->insert($data);
                  }
            }

            $client_update = DB::table('cm_clients')->get();
            if(count($client_update)){
                  foreach ($client_update as $clients) {
                        $dataclient['yearend_updatetime'] = date('Y-m-d H:i:s');
                        DB::table('cm_clients')->where('client_id', $clients->client_id)->update($dataclient);
                  }
            }

            DB::table('year_end_year')->where('year',$yearid)->update(['updatetime' => date('Y-m-d H:i:s')]);
            return Redirect::back()->with('message', 'Reviewed Clients successfully.');
      }
      public function yearend_setting(){
            if(Session::has('yearend_attach_add'))
            {
                  Session::forget("yearend_attach_add");
            }
            $setting = DB::table('year_setting')->where('status', 0)->get();
            return view('user/yearend/yearend_setting', array('title' => 'Setting', 'setting_list' => $setting));
      }

      public function year_setting_create(){
            $data['document'] = Input::get('document');
            $data['introduction'] = Input::get('introduction');
            $data['active'] = 2;

            $id = DB::table('year_setting')->insertGetid($data);

            if(Session::has('yearend_attach_add'))
            {
                  $files = Session::get('yearend_attach_add');
                  foreach($files as $file)
                  {
                        $upload_dir = 'uploads/yearend_image';
                        if (!file_exists($upload_dir)) {
                              mkdir($upload_dir);
                        }
                        $upload_dir = $upload_dir.'/'.base64_encode($id);
                        if (!file_exists($upload_dir)) {
                              mkdir($upload_dir);
                        }
                        rename("uploads/yearend_image/temp/".$file['attachment'], $upload_dir.'/'.$file['attachment']);

                        $dataval['year_setting_id'] = $id;
                        $dataval['attachment'] = $file['attachment'];
                        $dataval['url'] = $upload_dir;

                        DB::table('year_setting_attachment')->insert($dataval);
                  }
            }
            return Redirect::back()->with('message', 'Document created successfully');
      }

      public function active_checkbox(){
            $id = Input::get('id');
            $data['active'] = Input::get('active');
            DB::table('year_setting')->where('id', $id)->update($data);
      }

      public function year_setting_edit(){
            $id= Input::get('id');

            $setting_details = DB::table('year_setting')->where('id', $id)->first();

            echo json_encode(array('id' => $setting_details->id, 'document' => $setting_details->document, 'introduction' => $setting_details->introduction ));
      }

      public function yearend_crypt_setting_add(){
            if(Session::has('yearend_attach_add'))
            {
                  Session::forget("yearend_attach_add");
            }
            $type = Input::get('type');
            
            $admin_details = DB::table('admin')->first();
            $id = Input::get('id');
            
            if($type == 0){
                   
                  $form_details = '
                    <div class="form-group">
                        <div class="form-title">Document Name:</div>
                        <input type="text" class="form-control" value="" placeholder="Enter Document Name" name="document" required>
                    </div>
                    <div class="form-group">
                        <div class="form-title">Introduction Note:</div>
                        <textarea class="form-control" value="" placeholder="Enter Introduction Note" name="introduction" required></textarea>
                    </div>';        
                    $attachments = '';                
            }
            else{
                  $id = Input::get('id');
                  $setting_details = DB::table('year_setting')->where('id', $id)->first();
                                  
                  
                  $form_details = '
                    <div class="form-group">
                        <div class="form-title">Document Name:</div>
                        <input type="text" class="form-control" value="'.$setting_details->document.'" placeholder="Enter Document Name" name="document" required>
                    </div>
                    <div class="form-group">
                        <div class="form-title">Introduction Note:</div>
                        <textarea class="form-control" placeholder="Enter Introduction Note" name="introduction" required>'.$setting_details->introduction.'</textarea>
                    </div>';

                  $attachments = '';
                  $files = DB::table('year_setting_attachment')->where('year_setting_id',$id)->get();
                  if(count($files))
                  {
                        foreach($files as $file)
                        {
                              if($attachments == "")
                              {
                                    $attachments = '<p id="attach_'.$file->id.'">'.$file->attachment.' <a href="javascript:" class="delete_file fa fa-trash" data-element="'.$file->id.'"></a> </p>';
                              }
                              else{
                                    $attachments = $attachments.' <p id="attach_'.$file->id.'">'.$file->attachment.' <a href="javascript:" class="delete_file fa fa-trash" data-element="'.$file->id.'"></a></p>';
                              }
                        }
                  }
            }

            echo json_encode(array('form_details' => $form_details,'attachments' => $attachments));
      }

      public function year_setting_update(){
            $id = Input::get('id');
            $data['document'] = Input::get('document');
            $data['introduction'] = Input::get('introduction');

            DB::table('year_setting')->where('id', $id)->update($data);


            if(Session::has('yearend_attach_add'))
            {
                  $files = Session::get('yearend_attach_add');
                  foreach($files as $file)
                  {
                        $upload_dir = 'uploads/yearend_image';
                        if (!file_exists($upload_dir)) {
                              mkdir($upload_dir);
                        }
                        $upload_dir = $upload_dir.'/'.base64_encode($id);
                        if (!file_exists($upload_dir)) {
                              mkdir($upload_dir);
                        }
                        rename("uploads/yearend_image/temp/".$file['attachment'], $upload_dir.'/'.$file['attachment']);

                        $dataval['year_setting_id'] = $id;
                        $dataval['attachment'] = $file['attachment'];
                        $dataval['url'] = $upload_dir;

                        DB::table('year_setting_attachment')->insert($dataval);
                  }
            }
            return Redirect::back()->with('message', 'Update Success');
      }

      public function yearend_upload_images_add()
      {
            $upload_dir = 'uploads/yearend_image';
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


                  if(Session::has('yearend_attach_add'))
                  {
                        $arrayval = array('attachment' => $fname,'url' => $upload_dir);
                        $getsession = Session::get('yearend_attach_add');
                        array_push($getsession,$arrayval);

                  }
                  else{
                        $arrayval = array('attachment' => $fname,'url' => $upload_dir);
                        $getsession = array();
                        array_push($getsession,$arrayval);
                  }

                  $sessn=array('yearend_attach_add' => $getsession);
                  Session::put($sessn);
            }

            echo json_encode(array('id' => 0,'filename' => $fname,'file_id' => 0));
      }
      public function yearend_upload_images_edit()
      {
            $id = Input::get('hidden_year_setting_id');
            $upload_dir = 'uploads/yearend_image';
            if (!file_exists($upload_dir)) {
                  mkdir($upload_dir);
            }
            $upload_dir = $upload_dir.'/'.base64_encode($id);
            if (!file_exists($upload_dir)) {
                  mkdir($upload_dir);
            }
            if (!empty($_FILES)) {
                   $tmpFile = $_FILES['file']['tmp_name'];
                   $filename = $upload_dir.'/'.$_FILES['file']['name'];
                   $fname = $_FILES['file']['name'];

                  move_uploaded_file($tmpFile,$filename);

                  $data['year_setting_id'] = $id;
                  $data['attachment'] = $fname;
                  $data['url'] = $upload_dir;
                  $id = DB::table('year_setting_attachment')->insertGetId($data);
            }
            echo json_encode(array('id' => 0,'filename' => $fname,'file_id' => $id));
      }
      
      public function yearend_clear_session_attachments()
      {
          
            if(Session::has('yearend_attach_add'))
            {
                  Session::forget("yearend_attach_add");
            }
      }
      public function remove_all_attachments()
      {
            $id = Input::get('year_setting_id'); 
            DB::table('year_setting_attachment')->where('year_setting_id',$id)->delete();
      }
      public function remove_year_setting_attachment()
      {
            $id = Input::get('id');
            DB::table('year_setting_attachment')->where('id',$id)->delete();
      }
      public function yeadend_clients($id=""){
            $id = base64_decode($id);
            $clients = DB::table('year_client')->where('year', $id)->get();          
            return view('user/yearend/yearend_clients', array('title' => 'Clients', 'clientslist' => $clients, 'yearid' => $id));
      }
      public function yearend_individualclient($id=""){
            $id = base64_decode($id);            
            $year_details = DB::table('year_client')->where('id', $id)->first();
            $client_details = DB::table('cm_clients')->where('client_id', $year_details->client_id)->first();
            
            return view('user/yearend/individualclient', array('title' => 'Clients', 'year_details' => $year_details, 'client_details' => $client_details, 'year_end_id' => $id));
      }
      public function year_setting_copy_to_year(){

            $pin = Input::get('crypt');
            $admin_details = DB::table('admin')->first();
            $id = Input::get('id');
            if(Hash::check($pin,$admin_details->cm_crypt))
            {
                  $id = base64_decode(Input::get('yearid'));
                  $end_year = DB::table('year_end_year')->where('year',$id)->first();
                  $check_clients = DB::table('year_client')->where('year', $id)->where('status','!=',2)->get();
                  if(count($check_clients))
                  {
                        foreach($check_clients as $clients)
                        {
                              $get_setting = $clients->setting_id;
                              $get_setting_active = $clients->setting_active;
                              $explode_setting = explode(",",$get_setting);

                              $setting = DB::table('year_setting')->get();
                              $commo = '';
                              if(count($setting)){
                                    foreach ($setting as $key => $single) {
                                          if(in_array($single->id, $explode_setting))
                                          {

                                          }
                                          else{
                                                if($commo == ''){
                                                      $commo = $single->id;
                                                }
                                                else{
                                                      $commo =  $commo.','.$single->id;
                                                }
                                          }
                                    }
                              }
                              if($get_setting == "")
                              {
                                    $year_setting['setting_id'] = $commo;
                              }
                              else{
                                    if($commo == "")
                                    {
                                          $year_setting['setting_id'] = $get_setting;
                                    }
                                    else{
                                          $year_setting['setting_id'] = $get_setting.','.$commo;
                                    }
                              }

                              $commoactive = '';
                              if(count($setting)){
                                    foreach ($setting as $key => $single) {
                                          if(in_array($single->id, $explode_setting))
                                          {

                                          }
                                          else{
                                                if($commoactive == ''){
                                                      $commoactive = $single->active;
                                                }
                                                else{
                                                      $commoactive =  $commoactive.','. $single->active;
                                                }
                                          }
                                    }
                              }

                              if($get_setting == "")
                              {
                                    $year_setting['setting_active'] = $commoactive; 
                              }
                              else{
                                    if($commoactive == "")
                                    {
                                          $year_setting['setting_active'] = $get_setting_active;
                                    }
                                    else{
                                          $year_setting['setting_active'] = $get_setting_active.','.$commoactive;
                                    }
                              }
                              
                              if($clients->setting_default == "")
                              {
                                 $array = array();
                              }
                              else{
                                 $array = unserialize($clients->setting_default);   
                              }
                              
                              if($commo != "")
                              {
                                    $explodecomma = explode(",",$commo);
                                    foreach($explodecomma as $comm)
                                    {
                                          $getattachments = DB::table('year_setting_attachment')->where('year_setting_id',$comm)->get();
                                          $attachids = '';
                                          $arrayval = array();
                                          if(count($getattachments))
                                          {
                                                foreach($getattachments as $attach_id)
                                                {
                                                      if($attachids == "")
                                                      {
                                                            $attachids = $attach_id->id;
                                                      }
                                                      else{
                                                            $attachids = $attachids.','.$attach_id->id;
                                                      }
                                                      
                                                      $datadist1['client_id'] = $clients->id;
                                                      $datadist1['setting_id'] = $comm;
                                                      $datadist1['attachments'] = $attach_id->attachment;
                                                      $datadist1['url'] = $attach_id->url;
                                                      $datadist1['distribution_type'] = 1;
                                                      $datadist1['attach_type'] = 0;
                                                      $datadist1['status'] = 0;
                                                      DB::table('yearend_distribution_attachments')->insert($datadist1);
                                                }
                                                $arrayval = array($comm => $attachids);
                                          }
                                          array_push($array,$arrayval);
                                    }
                              }      
                              $serialize = serialize($array);
                              $year_setting['setting_default'] = $serialize; 

                              DB::table('year_client')->where('id', $clients->id)->where('status','!=',2)->update($year_setting);

                              $commoactive = '';
                              if(count($setting)){
                                    foreach ($setting as $key => $single) {
                                          if($commoactive == ''){
                                                $commoactive = $single->active;
                                          }
                                          else{
                                                $commoactive =  $commoactive.','. $single->active;
                                          }
                                    }
                              }
                              

                              $year_setting['setting_active'] = $commoactive;
                              DB::table('year_end_year')->where('year', $id)->update($year_setting);                              
                        }
                  }

                  // if(count($end_year))
                  // {
                  //       $get_setting = $end_year->setting_id;
                  //       $get_setting_active = $end_year->setting_active;
                  //       $explode_setting = explode(",",$get_setting);
                  // }

                  // $setting = DB::table('year_setting')->get();
                  // $commo = '';
                  // if(count($setting)){
                  //       foreach ($setting as $key => $single) {
                  //             if(in_array($single->id, $explode_setting))
                  //             {

                  //             }
                  //             else{
                  //                   if($commo == ''){
                  //                         $commo = $single->id;
                  //                   }
                  //                   else{
                  //                         $commo =  $commo.','.$single->id;
                  //                   }
                  //             }
                  //       }
                  // }
                  // if($get_setting == "")
                  // {
                  //       $year_setting['setting_id'] = $commo;
                  // }
                  // else{
                  //       if($commo == "")
                  //       {
                  //             $year_setting['setting_id'] = $get_setting;
                  //       }
                  //       else{
                  //             $year_setting['setting_id'] = $get_setting.','.$commo;
                  //       }
                  // }

                  // $commoactive = '';
                  // if(count($setting)){
                  //       foreach ($setting as $key => $single) {
                  //             if(in_array($single->id, $explode_setting))
                  //             {

                  //             }
                  //             else{
                  //                   if($commoactive == ''){
                  //                         $commoactive = $single->active;
                  //                   }
                  //                   else{
                  //                         $commoactive =  $commoactive.','. $single->active;
                  //                   }
                  //             }
                  //       }
                  // }

                  // if($get_setting == "")
                  // {
                  //       $year_setting['setting_active'] = $commoactive; 
                  // }
                  // else{
                  //       if($commoactive == "")
                  //       {
                  //             $year_setting['setting_active'] = $get_setting_active;
                  //       }
                  //       else{
                  //             $year_setting['setting_active'] = $get_setting_active.','.$commoactive;
                  //       }
                  // }
                  
                  // if($end_year->setting_default == "")
                  // {
                  //    $array = array();
                  // }
                  // else{
                  //    $array = unserialize($end_year->setting_default);   
                  // }
                  
                  // if($commo != "")
                  // {
                  //       $explodecomma = explode(",",$commo);
                        
                  //       foreach($explodecomma as $comm)
                  //       {
                  //             $getattachments = DB::table('year_setting_attachment')->where('year_setting_id',$comm)->get();
                  //             $attachids = '';
                  //             $arrayval = array();
                  //             if(count($getattachments))
                  //             {
                  //                   foreach($getattachments as $attach_id)
                  //                   {
                  //                         if($attachids == "")
                  //                         {
                  //                               $attachids = $attach_id->id;
                  //                         }
                  //                         else{
                  //                               $attachids = $attachids.','.$attach_id->id;
                  //                         }
                  //                         $check_clients = DB::table('year_client')->where('year', $id)->get();
                  //                         if(count($check_clients))
                  //                         {
                  //                               foreach($check_clients as $cli)
                  //                               {
                  //                                     $datadist1['client_id'] = $cli->id;
                  //                                     $datadist1['setting_id'] = $comm;
                  //                                     $datadist1['attachments'] = $attach_id->attachment;
                  //                                     $datadist1['url'] = $attach_id->url;
                  //                                     $datadist1['distribution_type'] = 1;
                  //                                     $datadist1['attach_type'] = 0;
                  //                                     $datadist1['status'] = 0;
                  //                                     DB::table('yearend_distribution_attachments')->insert($datadist1);
                  //                               }
                  //                         }
                                          

                  //                   }
                  //                   $arrayval = array($comm => $attachids);
                  //             }
                  //             array_push($array,$arrayval);
                  //       }
                  // }      
                  // $serialize = serialize($array);
                  // $year_setting['setting_default'] = $serialize; 

                  // DB::table('year_end_year')->where('year', $id)->update($year_setting);
                  // DB::table('year_client')->where('year', $id)->where('status','!=',2)->update($year_setting);
                  return Redirect::back()->with('message', 'Successfully updated all existing clients with New Year end docs.');
            }
            else{
                 return Redirect::back()->with('message', 'CRYPT Pin is incorrect');
            }            
      }

      public function dist_emailupdate(){
            $id = Input::get('id');            
            $number = Input::get('number');

            if($number == 1){
                  $data['distribution1_email']= Input::get('value');
                  DB::table('year_client')->where('id', $id)->update($data);
            }
            elseif($number == 2){
                  $data['distribution2_email']= Input::get('value');
                  DB::table('year_client')->where('id', $id)->update($data);
            }
            elseif($number == 3){
                  $data['distribution3_email']= Input::get('value');
                  DB::table('year_client')->where('id', $id)->update($data);
            }

      }
      public function yearend_individual_attachment()
      {
            $total = count($_FILES['image_file']['name']);
            $clientid = Input::get('hidden_client_id');
            $settingid = Input::get('hidden_setting_id');

            $distribution_type = Input::get('distribution_type');
            $attach_type = Input::get('attach_type');

            for($i=0; $i<$total; $i++) {
                  $filename = $_FILES['image_file']['name'][$i];
                  $tmp_name = $_FILES['image_file']['tmp_name'][$i];
                  $upload_dir = 'uploads/yearend_image';
                  if (!file_exists($upload_dir)) {
                        mkdir($upload_dir);
                  }
                  $upload_dir = $upload_dir.'/clientid_'.$clientid;
                  if (!file_exists($upload_dir)) {
                        mkdir($upload_dir);
                  }
                  $upload_dir = $upload_dir.'/distribution_'.$distribution_type;
                  if (!file_exists($upload_dir)) {
                        mkdir($upload_dir);
                  }
                  move_uploaded_file($tmp_name, $upload_dir.'/'.$filename);   

                  $data['client_id'] = $clientid;
                  if($settingid == "")
                  {
                        $data['setting_id'] = 0;
                  }
                  else{
                        $data['setting_id'] = $settingid;
                  }
                  $data['attachments'] = $filename;
                  $data['url'] = $upload_dir;
                  $data['distribution_type'] = $distribution_type;
                  $data['attach_type'] = $attach_type;

                  
                  DB::table('yearend_distribution_attachments')->insert($data);
            }    
            DB::table('year_client')->where('id',$clientid)->update(['status' => 1]);        
            return redirect('user/yearend_individualclient/'.base64_encode($clientid))->with('message', 'Attachments Added successfully');
      }
      public function yearend_attachment_individual()
      {
            $clientid = Input::get('hidden_client_id');
            $settingid = Input::get('hidden_setting_id');

            $distribution_type = Input::get('distribution_type');
            $attach_type = Input::get('attach_type');

            $upload_dir = 'uploads/yearend_image';
            if (!file_exists($upload_dir)) {
                  mkdir($upload_dir);
            }
            $upload_dir = $upload_dir.'/clientid_'.$clientid;
            if (!file_exists($upload_dir)) {
                  mkdir($upload_dir);
            }
            $upload_dir = $upload_dir.'/distribution_'.$distribution_type;
            if (!file_exists($upload_dir)) {
                  mkdir($upload_dir);
            }

            if (!empty($_FILES)) {
                   $tmpFile = $_FILES['file']['tmp_name'];
                   $filename = $upload_dir.'/'.$_FILES['file']['name'];
                   $fname = $_FILES['file']['name'];

                  move_uploaded_file($tmpFile,$filename);

                  $data['client_id'] = $clientid;
                  if($settingid == "")
                  {
                        $data['setting_id'] = 0;
                  }
                  else{
                        $data['setting_id'] = $settingid;
                  }
                  $data['attachments'] = $fname;
                  $data['url'] = $upload_dir;
                  $data['distribution_type'] = $distribution_type;
                  $data['attach_type'] = $attach_type;

                  $id = DB::table('yearend_distribution_attachments')->insertGetId($data);
                  DB::table('year_client')->where('id',$clientid)->update(['status' => 1]);
            }
            echo json_encode(array('id' => $id,'filename' => $fname));
      }
      public function yearend_delete_image()
      {
            $imgid = Input::get('imgid');
            DB::table('yearend_distribution_attachments')->where('id',$imgid)->delete();
      }
      public function yearend_delete_all_image()
      {
            $clientid = Input::get('clientid');
            $settingid = Input::get('settingid');
            $distribution = Input::get('distribution');
            $type = Input::get('type');

            DB::table('yearend_distribution_attachments')->where('client_id',$clientid)->where('setting_id',$settingid)->where('distribution_type',$distribution)->where('attach_type',$type)->delete();
      }
      public function yearend_delete_note()
      {
            $imgid = Input::get('imgid');
            DB::table('yearend_notes_attachments')->where('id',$imgid)->delete();
      }
      public function yearend_delete_all_note()
      {
            $clientid = Input::get('clientid');
            $settingid = Input::get('settingid');
            $type = Input::get('type');
            $distribution = Input::get('distribution');

            DB::table('yearend_notes_attachments')->where('client_id',$clientid)->where('setting_id',$settingid)->where('attach_type',$type)->where('distribution_type',$distribution)->delete();
      }
      public function remove_yearend_dropzone_attachment()
      {
            $attachment_id = $_POST['attachment_id'];
            DB::table('yearend_distribution_attachments')->where('id',$attachment_id)->delete();
      }
      public function distribution_future()
      {
            $setting_active = Input::get('setting_active');
            $distribution1_future = Input::get('distribution1_future');
            $distribution2_future = Input::get('distribution2_future');
            $distribution3_future = Input::get('distribution3_future');
            $yearend_id = Input::get('yearend_id');
            $data['setting_active'] = $setting_active;
            $data['distribution1_future'] = $distribution1_future;
            $data['distribution2_future'] = $distribution2_future;
            $data['distribution3_future'] = $distribution3_future;
            DB::table('year_client')->where('id',$yearend_id)->update($data);
      }
      public function distribution1_future()
      {
            $distribution1_future = Input::get('distribution1_future');
            $yearend_id = Input::get('yearend_id');
            $data['distribution1_future'] = $distribution1_future;
            DB::table('year_client')->where('id',$yearend_id)->update($data);
      }
      public function distribution2_future()
      {
            $distribution2_future = Input::get('distribution2_future');
            $yearend_id = Input::get('yearend_id');
            $data['distribution2_future'] = $distribution2_future;
            DB::table('year_client')->where('id',$yearend_id)->update($data);
      }
      public function distribution3_future()
      {
            $distribution3_future = Input::get('distribution3_future');
            $yearend_id = Input::get('yearend_id');
            $data['distribution3_future'] = $distribution3_future;
            DB::table('year_client')->where('id',$yearend_id)->update($data);
      }
      public function setting_active_update()
      {
            $setting_active = Input::get('setting_active');
            $yearend_id = Input::get('yearend_id');
            $data['setting_active'] = $setting_active;
            DB::table('year_client')->where('id',$yearend_id)->update($data);
      }
      public function check_already_attached()
      {
            $client_id = Input::get('year_id');
            $setting_id = Input::get('setting_id');
            $type = Input::get('type');
            $distribution = Input::get('distribution');
            $check_db = Db::table('yearend_notes_attachments')->where('client_id',$client_id)->where('setting_id',$setting_id)->where('distribution_type',$distribution)->where('attach_type',$type)->get();
            $notesids = '';
            if(count($check_db))
            {
                  foreach($check_db as $db)
                  {
                        if($notesids == "")
                        {
                              $notesids = $db->note_id;
                        }
                        else{
                              $notesids = $notesids.','.$db->note_id;
                        }
                  }
            }
            $explode_notesids = explode(",",$notesids);
            $get_notes = DB::table('supplementary_formula')->where('name','!=','')->get();
            $output = '';
            if(count($get_notes))
            {
                  foreach($get_notes as $notes)
                  {
                        $output.='<p class="main_note">'.$notes->name.'</p>';
                        $check_subs = DB::table('supplementary_formula_attachments')->where('formula_id',$notes->id)->get();
                        $ii = 0;
                        if(count($check_subs))
                        {
                              foreach($check_subs as $sub)
                              {
                                    if(!in_array($sub->id, $explode_notesids))
                                    {
                                          $output.='<p><input type="checkbox" name="sub_note[]" class="sub_note" id="note_'.$sub->id.'" value="'.$sub->id.'"><label class="sub_note" for="note_'.$sub->id.'">'.$sub->name.'</label></p>';
                                          $ii++;
                                    }
                              }
                              if($ii == 0)
                              {
                                    $output.='<p><label>No Supplementary Notes Found</label></p>';
                              }
                        }
                        else{
                              $output.='<p><label>No Supplementary Notes Found</label></p>';
                        }
                  }
            }
            else{
                  $output.='<p class="main_note">No Supplementary Notes found</p>';
            }
            echo $output;
      }
      public function insert_notes_yearend()
      {
            $noteid = explode(",",Input::get('noteid'));
            $textval = explode("||",Input::get('textval'));
            if(count($noteid))
            {
                  $client_id = Input::get('year_id');
                  $setting_id = Input::get('setting_id');
                  $type = Input::get('type');
                  $distribution = Input::get('distribution');
                  foreach($noteid as $key=>$note)
                  {
                        $textvalue = $textval[$key];

                        $upload_dir = 'uploads/yearend_notes';
                        if (!file_exists($upload_dir)) {
                              mkdir($upload_dir);
                        }
                        $upload_dir = $upload_dir.'/'.base64_encode($client_id);
                        if (!file_exists($upload_dir)) {
                              mkdir($upload_dir);
                        }
                        $upload_dir = $upload_dir.'/'.base64_encode($setting_id);
                        if (!file_exists($upload_dir)) {
                              mkdir($upload_dir);
                        }
                        $upload_dir = $upload_dir.'/'.base64_encode($distribution);
                        if (!file_exists($upload_dir)) {
                              mkdir($upload_dir);
                        }
                        $upload_dir = $upload_dir.'/'.base64_encode($type);
                        if (!file_exists($upload_dir)) {
                              mkdir($upload_dir);
                        }

                        $words = explode(" ", $textvalue);
                        $first = join(" ", array_slice($words, 0, 5));

                        $name = $first.'...';

                        $myfile = fopen($upload_dir."/".$name.".txt", "w") or die("Unable to open file!");
                        $txt = $textvalue;
                        fwrite($myfile, $txt);
                        fclose($myfile);

                        $data['client_id'] = $client_id;
                        $data['setting_id'] = $setting_id;
                        $data['attachments'] = $name.".txt";
                        $data['url'] = $upload_dir;
                        $data['attach_type'] = $type;
                        $data['distribution_type'] = $distribution;

                        DB::table('yearend_notes_attachments')->insert($data);
                        
                  }
                  DB::table('year_client')->where('id',$client_id)->update(['status' => 1]);
            }
      }
      public function download_email_format()
      {
            $type = Input::get('type');
            $email = Input::get('email');
            $distribution = Input::get('distribution');
            $yearselected = Input::get('yearselected');
            $details = DB::table('year_client')->where('id',$yearselected)->first();
            $clientdetails = DB::table('cm_clients')->where('client_id',$details->client_id)->first();
            $setting_ids = explode(",",$details->setting_id);
            $setting_active = explode(",",$details->setting_active);
            if($distribution == "1") { $distribution_future = explode(",",$details->distribution1_future); }
            elseif($distribution == "2") { $distribution_future = explode(",",$details->distribution2_future); }
            elseif($distribution == "3") { $distribution_future = explode(",",$details->distribution3_future); }

            if($email == 1){ $attached = 'attached'; }
            else{ $attached = 'enclosed'; }
            if($type == 1)
            {

                  $output = '
                  <style>
                        p{ line-height:10px !important; }
                  </style>

                  <p>Subject: '.$details->year.' Year End</p>
                  <p>Dear '.$clientdetails->firstname.'</p>
                  <p>Please find '.$attached.' the following</p>
                  <p style="height:2px"></p>';
                  if(count($setting_ids))
                  {
                        foreach($setting_ids as $key => $ids)
                        {
                              $setting_name = DB::table('year_setting')->where('id',$ids)->first();
                              if($setting_active[$key] == 0)
                              {
                                    if($distribution_future[$key] == 1)
                                    {
                                          $output.='<p style="margin-left:20px">'.$setting_name->document.': will be sent to you under separate cover.</p>';
                                    }
                                    else{
                                          $attachments = DB::table('yearend_distribution_attachments')->where('distribution_type',$distribution)->where('setting_id',$ids)->where('client_id',$yearselected)->where('attach_type',0)->get();
                                          $outputattach = '';
                                          if(count($attachments))
                                          {
                                                foreach($attachments as $attach)
                                                {
                                                      if($outputattach == "")
                                                      {
                                                            $outputattach = $attach->attachments;
                                                      }
                                                      else{
                                                            $outputattach = $outputattach.'; '.$attach->attachments;
                                                      }
                                                }
                                                $output.='<p style="margin-left:20px">'.$setting_name->document.': '.$outputattach.'</p>';
                                          }
                                    }
                              }
                              
                        }
                  }
                  
                  $notes = DB::table('yearend_notes_attachments')->where('distribution_type',$distribution)->where('setting_id',0)->where('client_id',$yearselected)->where('attach_type',1)->get();
                  $output_closingnote = '';
                  if(count($notes))
                  {
                        foreach($notes as $note)
                        {
                              $note_details = DB::table('supplementary_formula_attachments')->where('id',$note->note_id)->first();
                              if($output_closingnote == '')
                              {
                                    $output_closingnote =$note_details->name;
                              }
                              else{
                                    $output_closingnote = $output_closingnote.'; '.$note_details->name;
                              }
                              
                        }
                        $output.='<p style="margin-left:20px">Closing Note: '.$output_closingnote.'</p>';
                  }

                  
                  $notes = DB::table('yearend_notes_attachments')->where('distribution_type',$distribution)->where('setting_id',0)->where('client_id',$yearselected)->where('attach_type',2)->get();
                  $output_feenote = '';
                  if(count($notes))
                  {
                        foreach($notes as $note)
                        {
                              $note_details = DB::table('supplementary_formula_attachments')->where('id',$note->note_id)->first();
                              if($output_feenote == '')
                              {
                                    $output_feenote =$note_details->name;
                              }
                              else{
                                    $output_feenote = $output_feenote.'; '.$note_details->name;
                              }
                              
                        }
                        $output.='<p style="margin-left:20px">Fee Note: '.$output_feenote.'</p>';
                  }

                  
                  $notes = DB::table('yearend_notes_attachments')->where('distribution_type',$distribution)->where('setting_id',0)->where('client_id',$yearselected)->where('attach_type',3)->get();
                  $output_signature = '';
                  if(count($notes))
                  {
                        foreach($notes as $note)
                        {
                              $note_details = DB::table('supplementary_formula_attachments')->where('id',$note->note_id)->first();
                              if($output_signature == '')
                              {
                                    $output_signature =$note_details->name;
                              }
                              else{
                                    $output_signature = $output_signature.'; '.$note_details->name;
                              }
                        }
                        $output.='<p style="margin-left:20px">Signature: '.$output_signature.'</p>';
                  }
            
                  $pdf = PDF::loadHTML($output);
                  $pdf->setPaper('A4', 'landscape');
                  $pdf->save('job_file/Distribution_Email_Format.pdf');
                  echo 'Distribution_Email_Format.pdf';
            }
            else{
                  $PHPWord = new \PhpOffice\PhpWord\PhpWord();
                  
                  $section = $PHPWord->addSection();

                  $section->addText('Subject: '.$details->year.' Year End');
                  $section->addText('Dear '.$clientdetails->firstname.'');
                  $section->addText('Please find '.$attached.' the following');
                  $section->addText('&nbsp;');
                  if(count($setting_ids))
                  {
                        foreach($setting_ids as $key => $ids)
                        {
                              $setting_name = DB::table('year_setting')->where('id',$ids)->first();
                              if($setting_active[$key] == 0)
                              {
                                    if($distribution_future[$key] == 1)
                                    {
                                          $section->addText($setting_name->document.': will be sent to you under separate cover.');
                                    }
                                    else{
                                          $attachments = DB::table('yearend_distribution_attachments')->where('distribution_type',$distribution)->where('setting_id',$ids)->where('client_id',$yearselected)->where('attach_type',0)->get();
                                          $outputattach = '';
                                          if(count($attachments))
                                          {
                                                foreach($attachments as $attach)
                                                {
                                                      if($outputattach == "")
                                                      {
                                                            $outputattach = $attach->attachments;
                                                      }
                                                      else{
                                                            $outputattach = $outputattach.'; '.$attach->attachments;
                                                      }
                                                }
                                                $section->addText($setting_name->document.': '.$outputattach);
                                          }
                                    }
                              }
                              
                        }
                  }
                  
                  
                  
                  $notes = DB::table('yearend_notes_attachments')->where('distribution_type',$distribution)->where('setting_id',0)->where('client_id',$yearselected)->where('attach_type',1)->get();
                  $output_closingnote = '';
                  if(count($notes))
                  {
                        foreach($notes as $note)
                        {
                              $note_details = DB::table('supplementary_formula_attachments')->where('id',$note->note_id)->first();
                              if($output_closingnote == '')
                              {
                                    $output_closingnote =$note_details->name;
                              }
                              else{
                                    $output_closingnote = $output_closingnote.'; '.$note_details->name;
                              }
                              
                        }
                        $section->addText('Closing Note: '.$output_closingnote);
                  }
                  
                  $notes = DB::table('yearend_notes_attachments')->where('distribution_type',$distribution)->where('setting_id',0)->where('client_id',$yearselected)->where('attach_type',2)->get();
                  $output_feenote = '';
                  if(count($notes))
                  {
                        foreach($notes as $note)
                        {
                              $note_details = DB::table('supplementary_formula_attachments')->where('id',$note->note_id)->first();
                              if($output_feenote == '')
                              {
                                    $output_feenote =$note_details->name;
                              }
                              else{
                                    $output_feenote = $output_feenote.'; '.$note_details->name;
                              }
                              
                        }
                        $section->addText('Fee Note: '.$output_feenote);
                  }
                  $notes = DB::table('yearend_notes_attachments')->where('distribution_type',$distribution)->where('setting_id',0)->where('client_id',$yearselected)->where('attach_type',3)->get();
                  $output_signature = '';
                  if(count($notes))
                  {
                        foreach($notes as $note)
                        {
                              $note_details = DB::table('supplementary_formula_attachments')->where('id',$note->note_id)->first();
                              if($output_signature == '')
                              {
                                    $output_signature =$note_details->name;
                              }
                              else{
                                    $output_signature = $output_signature.'; '.$note_details->name;
                              }
                        }
                        $section->addText('Signature: '.$output_signature);
                  }
                  $section->addText('&nbsp;');

                  $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($PHPWord, 'HTML');
                  $objWriter->save("job_file/Distribution_Email_Format.doc");
                  echo 'Distribution_Email_Format.doc';
            }
      }
      public function edit_yearend_email_unsent_files()
      {
            $yearid = Input::get('yearid');
            $distribution = Input::get('distribution');
            $email = 1;
            $attached = 'attached';
            $files = '';
            $type = Input::get('type');

            $details = DB::table('year_client')->where('id',$yearid)->first();
            $clientdetails = DB::table('cm_clients')->where('client_id',$details->client_id)->first();
            $setting_ids = explode(",",$details->setting_id);
            $setting_active = explode(",",$details->setting_active);
            if($distribution == "1") { $distribution_future = explode(",",$details->distribution1_future); }
            elseif($distribution == "2") { $distribution_future = explode(",",$details->distribution2_future); }
            elseif($distribution == "3") { $distribution_future = explode(",",$details->distribution3_future); }

            $subject = $details->year.' Year End';
            $output = '
            <style>
                  p{ line-height:10px !important; }
            </style>

            <p>Dear '.$clientdetails->firstname.'</p>
            <p>Please find '.$attached.' the following</p>';
            if(count($setting_ids))
            {
                  foreach($setting_ids as $key => $ids)
                  {
                        $setting_name = DB::table('year_setting')->where('id',$ids)->first();
                        if($setting_active[$key] == 0)
                        {
                              if($distribution_future[$key] == 1)
                              {
                                    $output.='<p style="margin-left:40px">'.$setting_name->document.' will be sent to you under separate cover.</p>';
                              }
                              else{
                                    
                                    if($type == "unsent")
                                    {
                                          $attachments = DB::table('yearend_distribution_attachments')->where('distribution_type',$distribution)->where('setting_id',$ids)->where('client_id',$yearid)->where('attach_type',0)->where('status',0)->get();
                                    }
                                    else{
                                          $attachments = DB::table('yearend_distribution_attachments')->where('distribution_type',$distribution)->where('setting_id',$ids)->where('client_id',$yearid)->where('attach_type',0)->where('status',1)->get();
                                    }

                                    $outputattach = '';
                                    if(count($attachments))
                                    {
                                          $files.='<p><input type="checkbox" class="check_all_setting" value="'.$setting_name->id.'" id="setting_'.$setting_name->id.'" checked><label for="setting_'.$setting_name->id.'">'.$setting_name->document.'</label></p>';
                                          foreach($attachments as $attach)
                                          {
                                                if($outputattach == "")
                                                {
                                                      $outputattach = $attach->attachments;
                                                }
                                                else{
                                                      $outputattach = $outputattach.'; '.$attach->attachments;
                                                }
                                                $files.='<p style="margin-left:30px"><input type="checkbox" name="check_attachment[]" value="'.$attach->id.'" id="label_'.$attach->id.'" class="attachments_setting_'.$setting_name->id.'" checked><label for="label_'.$attach->id.'">'.$attach->attachments.'</label></p>';
                                          }
                                          $output.='<p style="margin-left:40px">'.$setting_name->document.': '.$outputattach.'</p>';
                                    }
                              }
                        }
                        
                  }
            }

            
            if($type == "unsent")
            {
                  $notes = DB::table('yearend_notes_attachments')->where('distribution_type',$distribution)->where('setting_id',0)->where('client_id',$yearid)->where('attach_type',1)->where('status',0)->get();
            }
            else{
                  $notes = DB::table('yearend_notes_attachments')->where('distribution_type',$distribution)->where('setting_id',0)->where('client_id',$yearid)->where('attach_type',1)->where('status',1)->get();
            }
            $output_closing = '';
            if(count($notes))
            {
                  $files.='<p><input type="checkbox" id="check_all_closing_note" value="closing_note" checked><label for="check_all_closing_note">Closing Note</label></p>';
                  foreach($notes as $note)
                  {
                        $note_details = DB::table('supplementary_formula_attachments')->where('id',$note->note_id)->first();
                        if($output_closing == "")
                        {
                              $output_closing = $note_details->name;
                        }
                        else{
                              $output_closing = $output_closing.'; '.$note_details->name;
                        }
                       
                        $files.='<p style="margin-left:30px"><input type="checkbox" name="check_notes[]" value="'.$note->id.'" id="label_'.$note->id.'" class="check_all_closing_note" checked><label for="label_'.$note->id.'">'.$note_details->name.'</label></p>';
                  }
                   $output.='<p style="margin-left:40px">Closing Note: '.$output_closing.'</p>';
            }
            

            
            if($type == "unsent")
            {
                  $notes = DB::table('yearend_notes_attachments')->where('distribution_type',$distribution)->where('setting_id',0)->where('client_id',$yearid)->where('attach_type',2)->where('status',0)->get();
            }
            else{
                  $notes = DB::table('yearend_notes_attachments')->where('distribution_type',$distribution)->where('setting_id',0)->where('client_id',$yearid)->where('attach_type',2)->where('status',1)->get();
            }
            $output_fee = '';
            if(count($notes))
            {
                  $files.='<p><input type="checkbox" id="check_all_fee_note" value="closing_note" checked><label for="check_all_fee_note">Fee Note</label></p>';
                  foreach($notes as $note)
                  {
                        $note_details = DB::table('supplementary_formula_attachments')->where('id',$note->note_id)->first();
                        if($output_fee == "")
                        {
                              $output_fee = $note_details->name;
                        }
                        else{
                              $output_fee = $output_fee.'; '.$note_details->name;
                        }
                        
                        $files.='<p style="margin-left:30px"><input type="checkbox" name="check_notes[]" value="'.$note->id.'" id="label_'.$note->id.'" class="check_all_fee_note" checked><label for="label_'.$note->id.'">'.$note_details->name.'</label></p>';
                  }
                  $output.='<p style="margin-left:40px">Fee Note: '.$output_fee.'</p>';
            }
            

            
            if($type == "unsent")
            {
                  $notes = DB::table('yearend_notes_attachments')->where('distribution_type',$distribution)->where('setting_id',0)->where('client_id',$yearid)->where('attach_type',3)->where('status',0)->get();
            }
            else{
                  $notes = DB::table('yearend_notes_attachments')->where('distribution_type',$distribution)->where('setting_id',0)->where('client_id',$yearid)->where('attach_type',3)->where('status',1)->get();
            }
            $output_signature = '';
            if(count($notes))
            {
                  $files.='<p><input type="checkbox" id="check_all_signature" value="closing_note" checked><label for="check_all_signature">Signature</label></p>';
                  foreach($notes as $note)
                  {
                        $note_details = DB::table('supplementary_formula_attachments')->where('id',$note->note_id)->first();
                        if($output_signature == "")
                        {
                              $output_signature = $note_details->name;
                        }
                        else{
                              $output_signature = $output_signature.'; '.$note_details->name;
                        }
                        
                        $files.='<p style="margin-left:30px"><input type="checkbox" name="check_notes[]" value="'.$note->id.'" id="label_'.$note->id.'" class="check_all_signature" checked><label for="label_'.$note->id.'">'.$note_details->name.'</label></p>';
                  }
                  $output.='<p style="margin-left:40px">Signature: '.$output_signature.'</p>';
            }
            
      
            echo json_encode(["files" => $files,"html" => $output,'subject' => $subject,'to' => $clientdetails->email]);
      }
      public function yearend_email_unsent_files()
      {
            $from_input = Input::get('from_user');
            $toemails = Input::get('to_user');
            $sentmails = Input::get('to_user');
            $subject = Input::get('subject_unsent'); 
            $message = Input::get('message_editor');
            $attachments = Input::get('check_attachment');
            $notes = Input::get('check_notes');
            $distribution = Input::get('email_sent_option');

            $explode = explode(',',$toemails);
            $data['sentmails'] = $sentmails;
            $i = 0;
            if(count($attachments) || count($notes))
            {
                  if(count($explode))
                  {
                        foreach($explode as $exp)
                        {
                              $to = trim($exp);
                              $data['logo'] = URL::to('assets/images/easy_payroll_logo.png');
                              $data['message'] = $message;
                              $contentmessage = view('user/yearend_email_share_paper', $data);
                              $email = new PHPMailer();
                              $email->SetFrom($from_input); //Name is optional
                              $email->Subject   = $subject;
                              $email->Body      = $contentmessage;
                              $email->IsHTML(true);
                              $email->AddAddress( $to );
                              if(count($attachments))
                              {
                                    foreach($attachments as $attachment)
                                    {
                                          $attachment_details = DB::table('yearend_distribution_attachments')->where('id',$attachment)->first();
                                          $path = $attachment_details->url.'/'.$attachment_details->attachments;
                                          $email->AddAttachment( $path , $attachment_details->attachments );
                                          DB::table('yearend_distribution_attachments')->where('id',$attachment)->update(['status' => 1]);
                                          $year_id = $attachment_details->client_id;
                                    }
                              }
                              if(count($notes))
                              {
                                    foreach($notes as $note)
                                    {
                                          $note_content = DB::table('yearend_notes_attachments')->where('id',$note)->first();
                                          $note_details = DB::table('supplementary_formula_attachments')->where('id',$note_content->note_id)->first();

                                          $upload_dir = 'uploads/yearend_notes';
                                          if (!file_exists($upload_dir)) {
                                                mkdir($upload_dir);
                                          }
                                          $upload_dir = $upload_dir.'/'.base64_encode($note_content->note_id);
                                          if (!file_exists($upload_dir)) {
                                                mkdir($upload_dir);
                                          }

                                          $myfile = fopen($upload_dir."/".$note_details->name.".txt", "w") or die("Unable to open file!");
                                          $txt = $note_details->supplementary_text;
                                          fwrite($myfile, $txt);
                                          fclose($myfile);


                                          $path = $upload_dir.'/'.$note_details->name.'.txt';
                                          $email->AddAttachment( $path , $note_details->name.'.txt' );
                                          DB::table('yearend_notes_attachments')->where('id',$note)->update(['status' => 1]);
                                          $year_id = $note_content->client_id;
                                    }
                              }
                              $email->Send();
                              $i++;
                        }
                        $date = date('Y-m-d H:i:s');

                        if($distribution == 1) { $dataval['dist1_email_sent_date'] = $date; }
                        elseif($distribution == 2) { $dataval['dist2_email_sent_date'] = $date; }
                        elseif($distribution == 3) { $dataval['dist3_email_sent_date'] = $date; }

                        DB::table('year_client')->where('id',$year_id)->update($dataval);
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
      public function make_client_disable()
      {
            $status = $_POST['status'];
            $id = $_POST['year'];
            if($status == 1)
            {
                  DB::table('year_client')->where('id',$id)->update(['disabled' => $status,'status' => 2]);
            }
            else{
                  DB::table('year_client')->where('id',$id)->update(['disabled' => $status,'status' => 1]);
            }
      }
      public function select_template()
      {
            $templateid = $_POST['templateid'];
            $selectval = DB::table('supplementary_formula')->where('id',$templateid)->first();
            $value_1 = $selectval->value_1;
            $value_2 = $selectval->value_2;
            $value_3 = $selectval->value_3_output;
            $value_4 = $selectval->value_4_output;
            $value_5 = $selectval->value_5_output;
            $value_6 = $selectval->value_6_output;
            $invoice = $selectval->invoice_number;

            $placeholder1 = 'Enter Input value';
            $placeholder2 = 'Enter Input value';

            if($selectval->value_3 == 1) { 
                  $placeholder = 'Commutation of';
                  if($selectval->value_3_combo1 == 1) { $value3_placeholder1 = 'Value1'; }
                  elseif($selectval->value_3_combo1 == 2) { $value3_placeholder1 = 'Value2'; }
                  else{ $value3_placeholder1 = ''; }

                  if($selectval->value_3_combo2 == 1) { $value3_placeholder2 = 'Value1'; }
                  elseif($selectval->value_3_combo2 == 2) { $value3_placeholder2 = 'Value2'; }
                  else{ $value3_placeholder2 = ''; }

                  if($selectval->value_3_formula == 1) { $value_3_formula = "+"; }
                  elseif($selectval->value_3_formula == 2) { $value_3_formula = "-"; }
                  elseif($selectval->value_3_formula == 3) { $value_3_formula = "*"; }
                  elseif($selectval->value_3_formula == 4) { $value_3_formula = "/"; }
                  else { $value_3_formula = ''; }

                  $placeholder3 = $placeholder.' '.$value3_placeholder1.' '.$value_3_formula.' '.$value3_placeholder2;
            }
            else{
                  $placeholder3 = 'Enter Input value';
            }

            if($selectval->value_4 == 1) { 
                  $placeholder = 'Commutation of';
                  if($selectval->value_4_combo1 == 1) { $value4_placeholder1 = 'Value1'; }
                  elseif($selectval->value_4_combo1 == 2) { $value4_placeholder1 = 'Value2'; }
                  elseif($selectval->value_4_combo1 == 3) { $value4_placeholder1 = 'Value3'; }
                  else{ $value4_placeholder1 = ''; }

                  if($selectval->value_4_combo2 == 1) { $value4_placeholder2 = 'Value1'; }
                  elseif($selectval->value_4_combo2 == 2) { $value4_placeholder2 = 'Value2'; }
                  elseif($selectval->value_4_combo2 == 3) { $value4_placeholder2 = 'Value3'; }
                  else{ $value4_placeholder2 = ''; }

                  if($selectval->value_4_formula == 1) { $value_4_formula = "+"; }
                  elseif($selectval->value_4_formula == 2) { $value_4_formula = "-"; }
                  elseif($selectval->value_4_formula == 3) { $value_4_formula = "*"; }
                  elseif($selectval->value_4_formula == 4) { $value_4_formula = "/"; }
                  else { $value_4_formula = ''; }

                  $placeholder4 = $placeholder.' '.$value4_placeholder1.' '.$value_4_formula.' '.$value4_placeholder2;
            }
            else{
                  $placeholder4 = 'Enter Input value';
            }

            if($selectval->value_5 == 1) { 
                  $placeholder = 'Commutation of';
                  if($selectval->value_5_combo1 == 1) { $value5_placeholder1 = 'Value1'; }
                  elseif($selectval->value_5_combo1 == 2) { $value5_placeholder1 = 'Value2'; }
                  elseif($selectval->value_5_combo1 == 3) { $value5_placeholder1 = 'Value3'; }
                  elseif($selectval->value_5_combo1 == 4) { $value5_placeholder1 = 'Value4'; }
                  else{ $value5_placeholder1 = ''; }

                  if($selectval->value_5_combo2 == 1) { $value5_placeholder2 = 'Value1'; }
                  elseif($selectval->value_5_combo2 == 2) { $value5_placeholder2 = 'Value2'; }
                  elseif($selectval->value_5_combo2 == 3) { $value5_placeholder2 = 'Value3'; }
                  elseif($selectval->value_5_combo2 == 4) { $value5_placeholder2 = 'Value4'; }
                  else{ $value5_placeholder2 = ''; }

                  if($selectval->value_5_formula == 1) { $value_5_formula = "+"; }
                  elseif($selectval->value_5_formula == 2) { $value_5_formula = "-"; }
                  elseif($selectval->value_5_formula == 3) { $value_5_formula = "*"; }
                  elseif($selectval->value_5_formula == 4) { $value_5_formula = "/"; }
                  else { $value_5_formula = ''; }

                  $placeholder5 = $placeholder.' '.$value5_placeholder1.' '.$value_5_formula.' '.$value5_placeholder2;
            }
            else{
                  $placeholder5 = 'Enter Input value';
            }

            if($selectval->value_6 == 1) { 
                  $placeholder = 'Commutation of';
                  if($selectval->value_6_combo1 == 1) { $value6_placeholder1 = 'Value1'; }
                  elseif($selectval->value_6_combo1 == 2) { $value6_placeholder1 = 'Value2'; }
                  elseif($selectval->value_6_combo1 == 3) { $value6_placeholder1 = 'Value3'; }
                  elseif($selectval->value_6_combo1 == 4) { $value6_placeholder1 = 'Value4'; }
                  elseif($selectval->value_6_combo1 == 5) { $value6_placeholder1 = 'Value5'; }
                  else{ $value6_placeholder1 = ''; }

                  if($selectval->value_6_combo2 == 1) { $value6_placeholder2 = 'Value1'; }
                  elseif($selectval->value_6_combo2 == 2) { $value6_placeholder2 = 'Value2'; }
                  elseif($selectval->value_6_combo2 == 3) { $value6_placeholder2 = 'Value3'; }
                  elseif($selectval->value_6_combo2 == 4) { $value6_placeholder2 = 'Value4'; }
                  elseif($selectval->value_6_combo2 == 5) { $value6_placeholder2 = 'Value5'; }
                  else{ $value6_placeholder2 = ''; }

                  if($selectval->value_6_formula == 1) { $value_6_formula = "+"; }
                  elseif($selectval->value_6_formula == 2) { $value_6_formula = "-"; }
                  elseif($selectval->value_6_formula == 3) { $value_6_formula = "*"; }
                  elseif($selectval->value_6_formula == 4) { $value_6_formula = "/"; }
                  else { $value_6_formula = ''; }

                  $placeholder6 = $placeholder.' '.$value6_placeholder1.' '.$value_6_formula.' '.$value6_placeholder2;
            }
            else{
                  $placeholder6 = 'Enter Input value';
            }

            $placeholder7 = 'Enter Input value';

            $output = '<p>Supplementary Note :</p>';
            $attachments = DB::table('supplementary_formula_attachments')->where('formula_id',$selectval->id)->get();
            if(count($attachments))
            {
                  foreach($attachments as $attach)
                  {
                        $textval = str_replace("<<value1>>","<span class='classval' id='value1'>".$attach->value_1."</span>",$attach->magic_text);
                        $textval = str_replace("<<value2>>","<span class='classval' id='value2'>".$attach->value_2."</span>",$textval);
                        $textval = str_replace("<<value3>>","<span class='classval' id='value3'>".$attach->value_3_output."</span>",$textval);
                        $textval = str_replace("<<value4>>","<span class='classval' id='value4'>".$attach->value_4_output."</span>",$textval);
                        $textval = str_replace("<<value5>>","<span class='classval' id='value5'>".$attach->value_5_output."</span>",$textval);
                        $textval = str_replace("<<value6>>","<span class='classval' id='value6'>".$attach->value_6_output."</span>",$textval);
                        $textval = str_replace("<<invoice>>","<span class='classval' id='invoice'>".$attach->invoice_number."</span>",$textval);

                        $output.='<p><input type="checkbox" name="check_note" class="check_note" id="note_'.$attach->id.'" value="'.$attach->id.'"><label for="note_'.$attach->id.'">&nbsp;</label> <span class="notetxt_'.$attach->id.'">'.$textval.'</span></p>';
                  }
            }
            echo json_encode(array('value_1' => $value_1,'value_2' => $value_2,'value_3' => $value_3,'value_4' => $value_4,'value_5' => $value_5,'value_6' => $value_6,'invoice' => $invoice,'attachments' => $output,'placeholder1' => $placeholder1,'placeholder2' => $placeholder2,'placeholder3' => $placeholder3,'placeholder4' => $placeholder4,'placeholder5' => $placeholder5,'placeholder6' => $placeholder6,'placeholder7' => $placeholder7));
      }
      public function set_client_year_end_date()
      {
            $clientid = $_POST['yearid'];
            $date = $_POST['date'];

            DB::table('year_client')->where('id',$clientid)->update(['year_end_date' => $date]);
      }
      public function update_na_status()
      {
            $status = $_POST['status'];
            $yearend_id = $_POST['yearend_id'];
            DB::table('year_client')->where('id',$yearend_id)->update(['hide_na' => $status]);
      }

      public function yearendliabilityupdate(){
            $setting_id = base64_decode(Input::get('setting_id'));
            $value = Input::get('value');
            $year_id = Input::get('year_id');
            $row_id = Input::get('row_id');
            $client_id = Input::get('client_id');
            $type = Input::get('type');
            $current_time = date('Y-m-d H:i:s');

            $value = str_replace(",","",$value);
            $value = str_replace(",","",$value);
            $value = str_replace(",","",$value);
            $value = str_replace(",","",$value);
            $value = str_replace(",","",$value);


            $check_row_setting = DB::table('year_client_liability')->where('row_id', $row_id)->where('setting_id', $setting_id)->first();
            if($check_row_setting == ''){
                  $data['year_id'] = $year_id;
                  $data['client_id'] = $client_id;
                  $data['row_id'] = $row_id;
                  $data['setting_id'] = $setting_id;

                  if($type == 1){
                        $data['liability1'] = $value;
                        $data['liability1_updatetime'] = $current_time;
                  }
                  elseif($type == 2){
                        $data['liability2'] = $value;
                        $data['liability2_updatetime'] = $current_time;
                  }
                  elseif($type == 3){
                        $data['liability3'] = $value;
                        $data['liability3_updatetime'] = $current_time;
                  }
                  DB::table('year_client_liability')->insert($data);

            }
            else{
                  $data['year_id'] = $year_id;
                  $data['client_id'] = $client_id;
                  $data['row_id'] = $row_id;
                  $data['setting_id'] = $setting_id;

                  if($type == 1){
                        $data['liability1'] = $value;
                        $data['liability1_updatetime'] = $current_time;
                  }
                  elseif($type == 2){
                        $data['liability2'] = $value;
                        $data['liability2_updatetime'] = $current_time;
                  }
                  elseif($type == 3){
                        $data['liability3'] = $value;
                        $data['liability3_updatetime'] = $current_time;
                  }

                 DB::table('year_client_liability')->where('row_id', $row_id)->where('setting_id', $setting_id)->update($data);
            }       

            //echo number_format_invoice_without_decimal($value);    
            
      }

      public function yeadendliability($id=""){
            $id = base64_decode($id);
            $year_setting = DB::table('year_end_year')->where('year', $id)->first();

            $explode_setting = explode(',', $year_setting->setting_id);

            $output_setting='';

            if(count($explode_setting)){
                  foreach ($explode_setting as $setting) {
                        $setting_details = DB::table('year_setting')->where('id', $setting)->first();
                        $output_setting.='<option value="'.$setting.'">'.$setting_details->document.'</option>';
                  }
            }

            return view('user/yearend/yearliability', array('title' => 'Clients', 'yearid' => $id, 'setting_list' => $output_setting));
      }

      public function yearendliabilitysettingresult(){

            $setting_id = Input::get('id');
            $year_id = Input::get('yearid');
            $year_client = DB::table('year_client')->where('year', $year_id)->get();
            
            $prelim_year = $year_id+1;

            $output_result='<table class="display nowrap fullviewtablelist" id="liability_expand" width="100%" style="max-width: 100%;">
                <thead>
                  <tr class="background_bg">        
                    <th width="150px" style="text-align:left"><i class="fa fa-sort sort_clientid"></i>Client Id</th>
                    <th style="text-align:left">First Name</th>
                    <th style="text-align:left">Last Name</th>
                    <th style="text-align:left">Company</th>
                    <th style="text-align:left">Balance</th>
                    <th style="text-align:left">Liability</th>
                    <th style="text-align:left">Payments</th>
                    <th style="text-align:left">'.$prelim_year.' Prelim</th>
                    <th style="text-align:left">File Uploaded</th>
                  </tr>   
                </thead>
                <tbody id="task_body">';

            if(count($year_client)){
                  foreach ($year_client as $single_client) {

                        $client_details = DB::table('cm_clients')->where('client_id', $single_client->client_id)->first();                    

                        $attchement_client_id = $single_client->id;

                        $year_client_attachement_latest = DB::table('yearend_distribution_attachments')->where('client_id', $attchement_client_id)->where('setting_id',$setting_id)->orderBy('updatetime', 'DESC')->first();

                        if(count($year_client_attachement_latest)){
                              $final_attachement = DB::table('yearend_distribution_attachments')->where('client_id', $attchement_client_id)->where('setting_id',$setting_id)->where('distribution_type', $year_client_attachement_latest->distribution_type)->get();

                              $output_attachement='';
                              if(count($final_attachement)){
                                    foreach ($final_attachement as $single_attachement) {
                                          $output_attachement.='
                                          <a href="javascript:" class="fileattachment" data-element="'.URL::to("".$single_attachement->url.'/'.$single_attachement->attachments).'">'.$single_attachement->attachments.'</a><br/>';
                                    }
                              }
                              else{
                                    $output_attachement='';
                              }

                        }
                        else{
                              $final_attachement = '';
                              $output_attachement='';
                        }


                        $liability_details = DB::table('year_client_liability')->where('year_id', $year_id)->where('row_id', $single_client->id)->where('client_id', $single_client->client_id)->where('setting_id',$setting_id)->first();

                       
                        if(count($liability_details)){
                              //$liability = $liability_details->liability1;
                              $update_time1 = strtotime($liability_details->liability1_updatetime);
                              $update_time2 = strtotime($liability_details->liability2_updatetime);
                              $update_time3 = strtotime($liability_details->liability3_updatetime);

                              if($update_time1>$update_time2 && $update_time1>$update_time3){
                                $liability = $liability_details->liability1;
                              }
                              else{
                                if($update_time2>$update_time1 && $update_time2>$update_time3){
                                  $liability = $liability_details->liability2;
                                }
                                else
                                  $liability = $liability_details->liability3;
                              }
                              $payments = $liability_details->payments;
                              $balance = $liability_details->balance;
                              $prelim = $liability_details->prelim;

                              if($liability == ''){
                                    $liability = '0.00';
                              }
                              if($balance == ''){
                                    $balance = '0.00';
                              }
                              elseif($balance == 0){
                                    $balance = '0.00';
                              }
                              
                        }
                        else{
                              $liability ='0.00';
                              $payments = '';
                              $balance = '0.00';
                              $prelim = '';
                        }

                       

                        $setting_client = $single_client->setting_id;
                        $setting_checkbox = $single_client->setting_active;

                        $explode_client = explode(',', $setting_client);
                        $explode_checkbox = explode(',', $setting_checkbox);

                        $get_setting_key = array_search($setting_id, $explode_client);
                        $get_checkbox = $explode_checkbox[$get_setting_key];

                        if($single_client->status == 0)
                          {
                            $color = 'color:#f00 !important;';
                          }
                          elseif($single_client->status == 1)
                          {
                            $color = 'color:#f7a001 !important;';
                          }
                          elseif($single_client->status == 2)
                          {
                            $color = 'color:#0000fb !important;';
                          }
                          else{
                            $color = 'color:#f00 !important;';
                          }

                          if($liability == '0.00'){
                              $color_liability = 'color:#0000fb';
                          }
                          elseif($liability < 0){
                              $color_liability = 'color:#0dce00';
                          }
                          else{
                              $color_liability = 'color:#f00';
                          }

                          if($balance == '0.00'){
                              $color_balance = 'color:#0000fb';
                          }
                          elseif($balance < 0){
                              $color_balance = 'color:#0dce00';
                          }
                          else{
                              $color_balance = 'color:#f00';
                          }

                        if($get_checkbox == 0){
                              $output_result.='<tr id="client_'.$client_details->client_id.'" class="client_'.$client_details->active.'"">
                              <td style="'.$color.'">'.$client_details->client_id.'</td>
                              <td style="'.$color.'">'.$client_details->firstname.'</td>
                              <td style="'.$color.'">'.$client_details->surname.'</td>
                              <td style="'.$color.'">'.$client_details->company.'</td>
                              <td style="'.$color_balance.'"><span class="balance_class">'.number_format_invoice_without_decimal($balance).'</span></td>
                              <td style="'.$color_liability.'">'.number_format_invoice_without_decimal($liability).'</td>
                              <td><input type="text" class="form-control payment_class" data-element="'.$client_details->client_id.'" placeholder="Payments" value="'.number_format_invoice_without_decimal($payments).'" /></td>
                              <td><input type="text" class="form-control prelim_class" data-element="'.$client_details->client_id.'"  placeholder="Prelim" value="'.number_format_invoice_without_decimal($prelim).'" /></td>
                              <td>'.$output_attachement.'</td>
                              </tr>';
                        }

                  }
            }
            else{
                  $output_result.='<tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td align="center">Empty</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>                  
                  </tr>';
            }

            $output_result.=' </tbody></table>';

            echo json_encode(array('output_result' => $output_result));
      }

      public function yearendliabilitypayment(){
            $value = Input::get('value');
            $setting_id = Input::get('setting_id');
            $year_id = Input::get('year_id');
            $client_id = Input::get('client_id');


            $value = str_replace(",","",$value);
            $value = str_replace(",","",$value);
            $value = str_replace(",","",$value);
            $value = str_replace(",","",$value);
            $value = str_replace(",","",$value);



            $yearend_client = DB::table('year_client')->where('year', $year_id)->where('client_id', $client_id)->first();
            $row_id = $yearend_client->id;
            
            $liability_details = DB::table('year_client_liability')->where('year_id', $year_id)->where('client_id', $client_id)->where('row_id', $row_id)->where('setting_id', $setting_id)->first();

            if(count($liability_details)){
                  //$liability = $liability_details->liability1;
                  $update_time1 = strtotime($liability_details->liability1_updatetime);
                  $update_time2 = strtotime($liability_details->liability2_updatetime);
                  $update_time3 = strtotime($liability_details->liability3_updatetime);

                  if($update_time1>$update_time2 && $update_time1>$update_time3){
                    $balance = $liability_details->liability1-$value;
                    
                  }
                  else{
                    if($update_time2>$update_time1 && $update_time2>$update_time3){
                      $balance = $liability_details->liability2-$value;
                      
                    }
                    else
                      $balance = $liability_details->liability3-$value;
                
                  }                        
            }
            else{
                  $balance = '';
                  
            }
           
            if($liability_details == ''){
                  $data['payments'] = $value;
                  $data['year_id'] = $year_id;
                  $data['client_id'] = $client_id;
                  $data['row_id'] = $row_id;
                  $data['setting_id'] = $setting_id;
                  $data['balance'] = $balance;
                  
                  DB::table('year_client_liability')->where('year_id', $year_id)->where('client_id', $client_id)->where('row_id', $row_id)->where('setting_id', $setting_id)->insert($data);
            }
            else{
                  $data['payments'] = $value;
                  $data['balance'] = $balance;
                 DB::table('year_client_liability')->where('year_id', $year_id)->where('client_id', $client_id)->where('row_id', $row_id)->where('setting_id', $setting_id)->update($data);
            }

            if($balance == ''){
                  $balance_result = '<span style="color:#0000fb">0.00</span>';
            }
            elseif($balance == 0){
                  $balance_result = '<span style="color:#0000fb">0.00</span>';
            }
            elseif($balance < 0){
                  $balance_result = '<span style="color:#0dce00">'.number_format_invoice_without_decimal($balance).'</span>';
            }
            else{
                $balance_result = '<span style="color:#f00">'.number_format_invoice_without_decimal($balance).'</span>';
            }

            echo json_encode(array('balance' => $balance_result, 'client_id' => $client_id));
      }

      public function yearendliabilityprelim(){
            $value = Input::get('value');
            $setting_id = Input::get('setting_id');
            $year_id = Input::get('year_id');
            $client_id = Input::get('client_id');

            $value = str_replace(",","",$value);
            $value = str_replace(",","",$value);
            $value = str_replace(",","",$value);
            $value = str_replace(",","",$value);
            $value = str_replace(",","",$value);

            $yearend_client = DB::table('year_client')->where('year', $year_id)->where('client_id', $client_id)->first();
            $row_id = $yearend_client->id;
            
            $liability_details = DB::table('year_client_liability')->where('year_id', $year_id)->where('client_id', $client_id)->where('row_id', $row_id)->where('setting_id', $setting_id)->first();

            if($liability_details == ''){
                  $data['prelim'] = $value;
                  $data['year_id'] = $year_id;
                  $data['client_id'] = $client_id;
                  $data['row_id'] = $row_id;
                  $data['setting_id'] = $setting_id;                  
                  
                  DB::table('year_client_liability')->where('year_id', $year_id)->where('client_id', $client_id)->where('row_id', $row_id)->where('setting_id', $setting_id)->insert($data);
            }
            else{
                  $data['prelim'] = $value;
                 DB::table('year_client_liability')->where('year_id', $year_id)->where('client_id', $client_id)->where('row_id', $row_id)->where('setting_id', $setting_id)->update($data);
            }

      }

      public function yearendliabilityexport(){
            $setting_id = Input::get('setting_id');            
            $year_id = Input::get('year_id');
            $year_client = DB::table('year_client')->where('year', $year_id)->get();
            
            $prelim_year = $year_id+1;

            $headers = array(
              "Content-type" => "text/csv",
              "Content-Disposition" => "attachment; filename=CM_Report.csv",
              "Pragma" => "no-cache",
              "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
              "Expires" => "0"
            );

            $columns = array('Client Id', 'First Name', 'Last Name', 'Company Name', 'Balance', 'Liability', 'Payments', ''.$prelim_year.' Prelim', 'File Uploaded');

            


            $callback = function() use ($year_client, $columns, $year_id, $setting_id){
                  $file = fopen('papers/Export_Liability.csv', 'w');
            fputcsv($file, $columns);

            if(count($year_client)){
                  foreach ($year_client as $single_client) {
                        $client_details = DB::table('cm_clients')->where('client_id', $single_client->client_id)->first();

                        $attchement_client_id = $single_client->id;

                        /*$year_client_attachement_latest = DB::table('yearend_distribution_attachments')->where('client_id', $attchement_client_id)->where('setting_id',$setting_id)->orderBy('updatetime', 'DESC')->first();

                        if(count($year_client_attachement_latest)){
                              $final_attachement = DB::table('yearend_distribution_attachments')->where('client_id', $attchement_client_id)->where('setting_id',$setting_id)->where('distribution_type', $year_client_attachement_latest->distribution_type)->get();

                              $output_attachement='';
                              if(count($final_attachement)){
                                    foreach ($final_attachement as $single_attachement) {
                                          $output_attachement.= $single_attachement->attachments.'<br/>';
                                    }
                              }
                              else{
                                    $output_attachement='';
                              }

                        }
                        else{
                              $final_attachement = '';
                              $output_attachement='';
                        }*/

                        $setting_client = $single_client->setting_id;                        
                        $setting_checkbox = $single_client->setting_active;                        

                        $explode_client = explode(',', $setting_client);
                        $explode_checkbox = explode(',', $setting_checkbox);

                        $get_setting_key = array_search($setting_id, $explode_client);                       
                        $get_checkbox = $explode_checkbox[$get_setting_key];

                        if($get_checkbox == 0){


                              $liability_details = DB::table('year_client_liability')->where('year_id', $year_id)->where('row_id', $single_client->id)->where('client_id', $single_client->client_id)->where('setting_id',$setting_id)->first();                              
                       
                              if(count($liability_details)){
                                    //$liability = $liability_details->liability1;
                                    $update_time1 = strtotime($liability_details->liability1_updatetime);
                                    $update_time2 = strtotime($liability_details->liability2_updatetime);
                                    $update_time3 = strtotime($liability_details->liability3_updatetime);

                                    if($update_time1>$update_time2 && $update_time1>$update_time3){
                                      $liability = $liability_details->liability1;
                                    }
                                    else{
                                      if($update_time2>$update_time1 && $update_time2>$update_time3){
                                        $liability = $liability_details->liability2;
                                      }
                                      else
                                        $liability = $liability_details->liability3;
                                    }
                                    $payments = $liability_details->payments;
                                    $balance = $liability_details->balance;
                                    $prelim = $liability_details->prelim;

                                    if($liability == ''){
                                          $liability = '0.00';
                                    }
                                    if($balance == ''){
                                          $balance = '0.00';
                                    }
                                    elseif($balance == 0){
                                          $balance = '0.00';
                                    }
                                    
                              }
                              else{
                                    $liability ='0.00';
                                    $payments = '';
                                    $balance = '0.00';
                                    $prelim = '';
                              }

                              /*$columns_2 = array($client_details->client_id, $client_details->firstname, $client_details->surname, $client_details->company, $balance, $liability, $payments, $prelim,$output_attachement);
                              fputcsv($file, $columns_2);*/

                              $year_client_attachement_latest = DB::table('yearend_distribution_attachments')->where('client_id', $attchement_client_id)->where('setting_id',$setting_id)->orderBy('updatetime', 'DESC')->first();

                              if(count($year_client_attachement_latest)){
                                    $final_attachement = DB::table('yearend_distribution_attachments')->where('client_id', $attchement_client_id)->where('setting_id',$setting_id)->where('distribution_type', $year_client_attachement_latest->distribution_type)->get();

                                    $output_attachement='';
                                    if(count($final_attachement)){
                                          foreach ($final_attachement as $key => $single_attachement) {
                                                if($key == 0){
                                                      $columns_2 = array($client_details->client_id, $client_details->firstname, $client_details->surname, $client_details->company, number_format_invoice_without_decimal($balance), number_format_invoice_without_decimal($liability), number_format_invoice_without_decimal($payments), number_format_invoice_without_decimal($prelim),$single_attachement->attachments);
                                                      fputcsv($file, $columns_2);
                                                }
                                                else{
                                                      $columns_2 = array('', '', '', '', '', '', '', '',$single_attachement->attachments);
                                                      fputcsv($file, $columns_2);
                                                }
                                                
                                          }
                                    }
                                    else{
                                          $columns_2 = array($client_details->client_id, $client_details->firstname, $client_details->surname, $client_details->company, number_format_invoice_without_decimal($balance), number_format_invoice_without_decimal($liability), number_format_invoice_without_decimal($payments), number_format_invoice_without_decimal($prelim),'');
                                          fputcsv($file, $columns_2);
                                    }

                              }
                              else{
                                    $columns_2 = array($client_details->client_id, $client_details->firstname, $client_details->surname, $client_details->company, number_format_invoice_without_decimal($balance), number_format_invoice_without_decimal($liability), number_format_invoice_without_decimal($payments), number_format_invoice_without_decimal($prelim),'');
                                          fputcsv($file, $columns_2);
                              }

                        }                        
                        
                  }
            }
            
            fclose($file);
            };
            return Response::stream($callback, 200, $headers);
            
      }
      public function yearend_export_to_csv()
      {
            $year = Input::get('year');
            $file = fopen('papers/yearend_clients.csv', 'w');
            $columns = array('S.No', 'Client Id', 'First Name', 'Last Name', 'Company', 'Status');
            fputcsv($file, $columns);
            $i=1;
            $clientslist = DB::table('year_client')->where('year', $year)->get();
            if(count($clientslist)){
                  foreach ($clientslist as $client) {
                        $client_details = DB::table('cm_clients')->where('client_id', $client->client_id)->first();
                        if(count($client_details)){
                              $clientid = $client_details->client_id;
                              $firstname = $client_details->firstname;
                              $lastname = $client_details->surname;
                              $company = $client_details->company;
                        }
                        else{
                              $clientid = '';
                              $firstname = '';
                              $lastname = '';
                              $company = '';
                        }
                        if($client->status == 0)
                        {
                              $color = 'color:#f00 !important;';
                        }
                        elseif($client->status == 1)
                        {
                              $color = 'color:#f7a001 !important;';
                        }
                        elseif($client->status == 2)
                        {
                              $color = 'color:#0000fb !important;';
                        }
                        else{
                              $color = 'color:#f00 !important;';
                        }
                        if($i < 10)
                        {
                              $i = '0'.$i;
                        }
                        if($client->status == 0) { if($client_details->active == "2") { $stausval = 'Inactive & Not Started'; } else { $stausval = 'Not Started'; } }
                        elseif($client->status == 1) { $stausval = 'Inprogress'; }
                        elseif($client->status == 2) { $stausval = 'Completed'; }

                        $columns1 = array($i, $clientid,$firstname,$lastname,$company,$stausval);
                        fputcsv($file, $columns1);
                        $i++;
                  }
            }
            fclose($file);
            echo 'yearend_clients.csv';
      }
}
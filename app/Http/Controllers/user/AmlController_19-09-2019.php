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
		$user = DB::table('user')->where('user_status', 0)->get();
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

		$client_source ='Other Client - '.$client_details->firstname.'<a href="javascript:" class="refresh_icon" data-toggle="tooltip" data-original-title="Refresh"><i class="fa fa-refresh refresh_client_source" data-element='.$client->client_id.'></i></a>';

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

		$client_source ='Partner - '.$user_details->firstname.' '.$user_details->lastname.'<a href="javascript:" class="refresh_icon" data-toggle="tooltip" data-original-title="Refresh"><i class="fa fa-refresh refresh_client_source" data-element='.$client->client_id.'></i></a>';

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
		

		$client_source ='Note - '.$client->client_source_detail.' <a href="javascript:" class="refresh_icon" data-toggle="tooltip" data-original-title="Refresh"><i class="fa fa-refresh refresh_client_source" data-element='.$client->client_id.'></i></a>';

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
		

		$output = '<a href="javascript:" class="bank_detail_class" data-element="'.$client->client_id.'">'.$aml_bank_count.'</a><a href="javascript:" class="bank_class" data-toggle="modal" data-toggle="modal" data-target=".bank_modal"><i class="fa fa-plus faplus add_bank" title="Add Bank" data-element="'.$current_client_id.'"  style="margin-left:10px;"></i></a>';

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

		$client = DB::table('aml_system')->where('client_id', $current_client_id)->first();	

	}


}
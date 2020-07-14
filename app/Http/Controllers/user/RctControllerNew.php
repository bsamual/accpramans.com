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
use PHPExcel_Reader_HTML;
use PHPExcel_Cell;
use Hash;
use ZipArchive;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class RctControllerNew extends Controller {



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

  	public function rctsystem(){
  		$client = DB::table('cm_clients')->select('client_id', 'firstname', 'surname', 'company', 'status', 'active', 'id')->orderBy('id','asc')->get();
      return view('user/rct_system/rct_system', array('title' => 'TA System', 'clientlist' => $client));
  	}
    public function rctclientmanager($id=""){
      $client = DB::table('cm_clients')->where('client_id', $id)->first();
      $tax = DB::table('rct_tax_number')->where('tax_client_id', $id)->first();
      $rct_submission = DB::table('rct_submission')->where('client_id', $id)->first();

      $client_id = $id;

      return view('user/rct_system/rct_client_manager', array('title' => 'TA System', 'client_details' => $client, 'taxlist' => $tax, 'rctsubmission' => $rct_submission, 'client_id' => $client_id));
    }
    public function rctaddtax(){
      $tax_name_get = trim(Input::get('tax_name'));
      $tax_number_get = trim(Input::get('tax_number'));
      $client_id = Input::get('client_id');

      $tax_name = trim($tax_name_get);
      $tax_number = trim($tax_number_get);

      $tax_count = DB::table('rct_tax_number')->where('tax_client_id', $client_id)->first();

      if(count($tax_count)){
        $explode_tax_name = explode(',', $tax_count->tax_name);
        $explode_tax_number = explode(',', $tax_count->tax_number);
        
        if($tax_count->tax_name == ""){
          $data['tax_name'] = $tax_name;
          $data['tax_number'] = $tax_number;
        }
        else{
          $data['tax_name'] = $tax_count->tax_name.','.$tax_name;
          $data['tax_number'] = $tax_count->tax_number.','.$tax_number;
        }
        DB::table('rct_tax_number')->where('tax_client_id', $client_id)->update($data);
      }
      else{
        $data['tax_client_id'] = $client_id;
        $data['tax_name'] = $tax_name;
        $data['tax_number'] = $tax_number;

        DB::table('rct_tax_number')->insert($data);
      }

      return redirect::back()->with('message', 'Tax Name was add Successfully');      
    }

    public function rcttaxnumbercheck(){
      $tax_number_get = trim(Input::get('tax_number'));
      $tax_numbercheck = str_replace(' ', '', $tax_number_get);
      $client_id = Input::get('client_id');

      $tax_count = DB::table('rct_tax_number')->where('tax_client_id', $client_id)->first();
      if(count($tax_count)){
        $explode_ta_count = explode(',', $tax_count->tax_number);

        if(in_array($tax_numbercheck, $explode_ta_count)){
          $valid = false;
        }
        else{
          $valid = true;
        }
        echo json_encode($valid);
        exit;
      }
      else{
        $valid = true;
      }
      echo json_encode($valid);
      exit;    
  }
  public function rctaddsubmission(){
    $client_id = Input::get('client_id');
    $submission_type = Input::get('submission_type');
    $rct_trim = trim(Input::get('rct_id'));
    $rct_id = str_replace(' ', '', $rct_trim);    
    $principal_name = Input::get('principal_name');
    $start_date = date('Y-m-d', strtotime(Input::get('start_date')));
    $value_gross = Input::get('value_gross');   

    $submission_count = DB::table('rct_submission')->where('client_id', $client_id)->first();
    $serializeid = $rct_id.$submission_type;
    

    if(count($submission_count)){
      if($submission_type == 1){

        $site = Input::get('site');
        $finish_date = date('Y-m-d', strtotime(Input::get('finish_date')));

        $unserialize_type = unserialize($submission_count->type);
        $unserialize_type[$serializeid] = $submission_type;

        $unserialize_rctid = unserialize($submission_count->rct_id);
        $unserialize_rctid[$serializeid] = $rct_id;

        $unserialize_principal = unserialize($submission_count->principal_name);
        $unserialize_principal[$serializeid] = $principal_name;

        $unserialize_start_date = unserialize($submission_count->start_date);
        $unserialize_start_date[$serializeid] = $start_date;

        $unserialize_value_gross = unserialize($submission_count->value_gross);
        $unserialize_value_gross[$serializeid] = $value_gross;


        $unserialize_site = unserialize($submission_count->site);
        $unserialize_site[$serializeid] = $site;

        $unserialize_finish_date = unserialize($submission_count->finish_date);
        $unserialize_finish_date[$serializeid] = $finish_date;




        $data['type'] = serialize($unserialize_type);
        $data['rct_id'] = serialize($unserialize_rctid);
        $data['principal_name'] = serialize($unserialize_principal);
        $data['start_date'] = serialize($unserialize_start_date);
        $data['value_gross'] = serialize($unserialize_value_gross);

        $data['site'] = serialize($unserialize_site);
        $data['finish_date'] = serialize($unserialize_finish_date);        

        DB::table('rct_submission')->where('client_id', $client_id)->update($data);
      }
      else{

        $sub_contractor_name = Input::get('sub_contractor_name');
        $sub_contractor_id = Input::get('sub_contractor_id');
        $value_net = Input::get('value_net');
        $deduction = Input::get('deduction');

        $unserialize_type = unserialize($submission_count->type);
        $unserialize_type[$serializeid] = $submission_type;

        $unserialize_rctid = unserialize($submission_count->rct_id);
        $unserialize_rctid[$serializeid] = $rct_id;

        $unserialize_principal = unserialize($submission_count->principal_name);
        $unserialize_principal[$serializeid] = $principal_name;

        $unserialize_start_date = unserialize($submission_count->start_date);
        $unserialize_start_date[$serializeid] = $start_date;

        $unserialize_value_gross = unserialize($submission_count->value_gross);
        $unserialize_value_gross[$serializeid] = $value_gross;



        $unserialize_sub_contractor_name = unserialize($submission_count->sub_contractor);
        $unserialize_sub_contractor_name[$serializeid] = $sub_contractor_name;

        $unserialize_sub_contractor_id = unserialize($submission_count->sub_contractor_id);
        $unserialize_sub_contractor_id[$serializeid] = $sub_contractor_id;

        $unserialize_value_net = unserialize($submission_count->value_net);
        $unserialize_value_net[$serializeid] = $value_net;

        $unserialize_deduction = unserialize($submission_count->deduction);
        $unserialize_deduction[$serializeid] = $deduction;


        $data['type'] = serialize($unserialize_type);
        $data['rct_id'] = serialize($unserialize_rctid);
        $data['principal_name'] = serialize($unserialize_principal);
        $data['start_date'] = serialize($unserialize_start_date);
        $data['value_gross'] = serialize($unserialize_value_gross);

        $data['sub_contractor'] = serialize($unserialize_sub_contractor_name);
        $data['sub_contractor_id'] = serialize($unserialize_sub_contractor_id);
        $data['value_net'] = serialize($unserialize_value_net);
        $data['deduction'] = serialize($unserialize_deduction);
        

        DB::table('rct_submission')->where('client_id', $client_id)->update($data);

      }
    }
    else{
      if($submission_type == 1){
        $site = Input::get('site');
        $finish_date = date('Y-m-d', strtotime(Input::get('finish_date')));

        $type_serialize[$serializeid] = $submission_type;
        $rctid_serialize[$serializeid] = $rct_id;
        $principal_serialize[$serializeid] = $principal_name;
        $start_date_serialize[$serializeid] = $start_date;
        $value_gross_serialize[$serializeid] = $value_gross;

        $site_serialize[$serializeid] = $site;        
        $finish_date_serialize[$serializeid] = $finish_date;        

        $data['client_id'] = $client_id;
        $data['type'] = serialize($type_serialize);
        $data['rct_id'] = serialize($rctid_serialize);
        $data['principal_name'] = serialize($principal_serialize);
        $data['start_date'] = serialize($start_date_serialize);
        $data['value_gross'] = serialize($value_gross_serialize);

        $data['site'] = serialize($site_serialize);        
        $data['finish_date'] = serialize($finish_date_serialize);        
        DB::table('rct_submission')->insert($data);
      }
      else{
        $sub_contractor_name = Input::get('sub_contractor_name');
        $sub_contractor_id = Input::get('sub_contractor_id');
        $value_net = Input::get('value_net');
        $deduction = Input::get('deduction');

        $type_serialize[$serializeid] = $submission_type;
        $rctid_serialize[$serializeid] = $rct_id;
        $principal_serialize[$serializeid] = $principal_name;
        $start_date_serialize[$serializeid] = $start_date;
        $value_gross_serialize[$serializeid] = $value_gross;

        $sub_contractor_name_serialize[$serializeid] = $sub_contractor_name;
        $sub_contractor_id_serialize[$serializeid] = $sub_contractor_id;
        $value_net_serialize[$serializeid] = $value_net;
        $deduction_serialize[$serializeid] = $deduction;


        $data['client_id'] = $client_id;
        $data['type'] = serialize($type_serialize);
        $data['rct_id'] = serialize($rctid_serialize);
        $data['principal_name'] = serialize($principal_serialize);
        $data['start_date'] = serialize($start_date_serialize);
        $data['value_gross'] = serialize($value_gross_serialize);

        $data['sub_contractor'] = serialize($sub_contractor_name_serialize);
        $data['sub_contractor_id'] = serialize($sub_contractor_id_serialize);
        $data['value_net'] = serialize($value_net_serialize);
        $data['deduction'] = serialize($deduction_serialize);


        DB::table('rct_submission')->insert($data);
      }
    }

    return redirect::back()->with('message', 'RCT Submission was add Successfully');

  }

  public function rctsubmissioncheck(){
    $client_id = Input::get('client_id');
    $submission_type = Input::get('submission_type');
    $rct_trim = trim(Input::get('rct_id'));
    $rct_id = str_replace(' ', '', $rct_trim); 

    $rctidcheck = $rct_id.$submission_type;

    $submission_count = DB::table('rct_submission')->where('client_id', $client_id)->first();

    $valid = true;
    if(count($submission_count)){
      $unserialize = unserialize($submission_count->rct_id);
      if(count($unserialize)){
        foreach ($unserialize as $key => $unserializesingle) {
          if($key == $rctidcheck){
            $valid = false;            
          }          
        }
      }     
      else{
        $valid = true;
      }
      echo json_encode($valid);
      exit;
    }
    else{
      $valid = true;
    }
    echo json_encode($valid);
    exit;    
  }

  public function rctdeletesubmission(){
    $client_id = Input::get('client_id');
    $type = Input::get('type');
    $key = Input::get('key');

    $rct_submittsion = DB::table('rct_submission')->where('client_id', $client_id)->first();

    $unserialize_type = unserialize($rct_submittsion->type);
    unset($unserialize_type[$key]);    
    $serialize_type = serialize($unserialize_type);

    $unserialize_rctid = unserialize($rct_submittsion->rct_id);
    unset($unserialize_rctid[$key]);    
    $serialize_rctid = serialize($unserialize_rctid);

    $unserialize_principal_name = unserialize($rct_submittsion->principal_name);
    unset($unserialize_principal_name[$key]);    
    $serialize_principal_name = serialize($unserialize_principal_name);

    $unserialize_start_date = unserialize($rct_submittsion->start_date);
    unset($unserialize_start_date[$key]);    
    $serialize_start_date = serialize($unserialize_start_date);

    $unserialize_value_gross = unserialize($rct_submittsion->value_gross);
    unset($unserialize_value_gross[$key]);    
    $serialize_gross = serialize($unserialize_value_gross);

    if($type == 1){
      $unserialize_site = unserialize($rct_submittsion->site);
      unset($unserialize_site[$key]);      
      $serialize_site = serialize($unserialize_site);

      $unserialize_finish_date = unserialize($rct_submittsion->finish_date);
      unset($unserialize_finish_date[$key]);
      /*$finish_value = array_values($unserialize_finish_date);
      $unserialize_finish_date[$key] = $finish_value;*/
      $serialize_finish = serialize($unserialize_finish_date);

      $data['type'] = $serialize_type;
      $data['rct_id'] = $serialize_rctid;
      $data['principal_name'] = $serialize_principal_name;
      $data['start_date'] = $serialize_start_date;
      $data['value_gross'] = $serialize_gross;

      $data['site'] = $serialize_site;
      $data['finish_date'] = $serialize_finish;
      DB::table('rct_submission')->where('client_id', $client_id)->update($data);

    }
    else{

      $unserialize_sub_contractor = unserialize($rct_submittsion->sub_contractor);
      unset($unserialize_sub_contractor[$key]);      
      $serialize_sub_contractor = serialize($unserialize_sub_contractor);

      $unserialize_sub_contractor_id = unserialize($rct_submittsion->sub_contractor_id);
      unset($unserialize_sub_contractor_id[$key]);      
      $serialize_sub_contractor_id = serialize($unserialize_sub_contractor_id);

      $unserialize_value_net = unserialize($rct_submittsion->value_net);
      unset($unserialize_value_net[$key]);      
      $serialize_value_net = serialize($unserialize_value_net);

      $unserialize_deduction = unserialize($rct_submittsion->deduction);
      unset($unserialize_deduction[$key]);      
      $serialize_deduction = serialize($unserialize_deduction);




      $data['type'] = $serialize_type;
      $data['rct_id'] = $serialize_rctid;
      $data['principal_name'] = $serialize_principal_name;
      $data['start_date'] = $serialize_start_date;
      $data['value_gross'] = $serialize_gross;

      $data['sub_contractor'] = $serialize_sub_contractor;
      $data['sub_contractor_id'] = $serialize_sub_contractor_id;
      $data['value_net'] = $serialize_value_net;
      $data['deduction'] = $serialize_deduction;

      DB::table('rct_submission')->where('client_id', $client_id)->update($data);
    }

    $rctsubmission = DB::table('rct_submission')->where('client_id', $client_id)->first();

    $outputsubmission='';
    $site_view = '';
    $sub_cont_name = '';
    $sub_cont_id = '';
    $finish_date_view = '';
    $value_net_view = '';
    $deduction_view = '';
    $type_text='';
    $rct_id='';
    $key='';
    $principal_key='';
    $explode_tax_name='';
    $explode_tax_number='';
    if(count($rctsubmission)){            
      $rct_type = unserialize($rctsubmission->type);
      $rct_id = unserialize($rctsubmission->rct_id);
      $principal_name = unserialize($rctsubmission->principal_name);
      
      
      $start_date = unserialize($rctsubmission->start_date);
      
      $value_gross = unserialize($rctsubmission->value_gross);
      
      
      if(count($rct_type)){
        foreach ($rct_type as $key => $type) {
          if($type == 1){
            $type_text = 'CONTRACT';    
            
            $site = unserialize($rctsubmission->site);  
            $finish_date = unserialize($rctsubmission->finish_date); 
            $value_net = unserialize($rctsubmission->value_net);
            $deduction = unserialize($rctsubmission->deduction);           
          }
          elseif(($type == 2)){
            $type_text = 'PAYMENT';

            $sub_contractor = unserialize($rctsubmission->sub_contractor);
            $sub_contractor_id = unserialize($rctsubmission->sub_contractor_id);
          }
          else{
            $type_text='';
          }

          $tax_details = DB::table('rct_tax_number')->where('tax_client_id', $client_id)->first();
          $explode_tax_name = explode(',', $tax_details->tax_name);
          $explode_tax_number = explode(',', $tax_details->tax_number);

          $principal_key = $principal_name[$key];

          

          if(isset($sub_contractor[$key])){
            $sub_cont_name = $sub_contractor[$key];
          }
          else{
            $sub_cont_name = '';
          }

          if(isset($sub_contractor_id[$key])){
            $sub_cont_id = $sub_contractor_id[$key];
          }
          else{
            $sub_cont_id = '';
          }

          if(isset($site[$key])){                  
              $site_view = $site[$key];                  
          }
          else{
            $site_view = '';
          }

          if(isset($finish_date[$key])){
            $finish_date_view = date('d-M-Y', strtotime($finish_date[$key]));
          }
          else{
            $finish_date_view = '';
          }

          if(isset($value_net[$key])){
            $value_net_view = number_format_invoice_without_decimal($value_net[$key]);
          }
          else{
            $value_net_view = '';
          }

          if(isset($deduction[$key])){
            $deduction_view = number_format_invoice_without_decimal($deduction[$key]);
          }
          else{
            $deduction_view = '';
          }



          $outputsubmission.='
          <tr>
            <td>'.$type_text.'</td>
            <td>'.$rct_id[$key].'<br/></td>
            <td>'.$explode_tax_name[$principal_key].' - '.$explode_tax_number[$principal_key].'</td>
            <td>'.$sub_cont_name.'</td>
            <td>'.$sub_cont_id.'</td>
            <td>'.$site_view.'</td>
            <td>'.date('d-M-Y', strtotime($start_date[$key])).'</td>
            <td>'.$finish_date_view.'</td>
            <td>'.number_format_invoice_without_decimal($value_gross[$key]).'</td>
            <td>'.$value_net_view.'</td>
            <td>'.$deduction_view.'</td>
            <td>
              <a href="javascript:" title="Delete"><i class="fa fa-trash delete_submission" data-element="'.$type.'" id="'.$key.'"></i>'.$key.'</a>&nbsp;&nbsp;
              <a href="javascript:" title="View / Edit"><i class="fa fa-pencil edit_class"  data-element="'.$type.'" id="'.$key.'"></i></a>&nbsp;&nbsp;
              <a href="javascript:" title="Email"><i class="fa fa-envelope email_class_single"  data-element="'.$type.'" id="'.$key.'"></i></a>
            </td>
          </tr>
          ';
        }
      } 
      else{
        $outputsubmission='
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td align="left">Empty</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        ';
      }           
    }
    else{
      $outputsubmission='
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td align="left">Empty</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      ';
    }

    echo json_encode(array('result_submission' => $outputsubmission));

  }

  public function rcteditsubmission(){
    $client_id = Input::get('client_id');
    $type = Input::get('type');
    $key = Input::get('key');

    $rct_submittsion = DB::table('rct_submission')->where('client_id', $client_id)->first();
    $rctid_unserialize = unserialize($rct_submittsion->rct_id);
    $principal_unserialize = unserialize($rct_submittsion->principal_name);
    $start_unserialize = unserialize($rct_submittsion->start_date);
    $start_date = date('d-M-Y', strtotime($start_unserialize[$key]));
    $gross_unserialize = unserialize($rct_submittsion->value_gross);


    if($type == 1){

      $site_unserialize = unserialize($rct_submittsion->site);
      $finish_unserialize = unserialize($rct_submittsion->finish_date);
      $finish_date = date('d-M-Y', strtotime($finish_unserialize[$key]));

      echo json_encode(array('type' => $type, 'rct_id' => $rctid_unserialize[$key], 'principal' => $principal_unserialize[$key] , 'start_date' => $start_date, 'gross' => $gross_unserialize[$key], 'site' => $site_unserialize[$key], 'finish' => $finish_date, 'key' => $key));
    }
    else{
      $sub_contractor_unserialize = unserialize($rct_submittsion->sub_contractor);
      $sub_contractor_id_unserialize = unserialize($rct_submittsion->sub_contractor_id);
      $value_net_unserialize = unserialize($rct_submittsion->value_net);
      $deduction_unserialize = unserialize($rct_submittsion->deduction);

      echo json_encode(array('type' => $type, 'rct_id' => $rctid_unserialize[$key], 'principal' => $principal_unserialize[$key] , 'start_date' => $start_date, 'gross' => $gross_unserialize[$key], 'sub_contractor' => $sub_contractor_unserialize[$key], 'sub_contractor_id' => $sub_contractor_id_unserialize[$key], 'value_net' => $value_net_unserialize[$key], 'deduction' => $deduction_unserialize[$key] , 'key' => $key));
    }    
  }

  public function rcteditsubmissionupdate(){
    $client_id = Input::get('client_id');
    $submission_type = Input::get('submission_type');
    $key = Input::get('key');    
    $principal_name = Input::get('principal_name');
    $start_date = date('Y-m-d', strtotime(Input::get('start_date')));
    $value_gross = Input::get('value_gross');   

    $submission_count = DB::table('rct_submission')->where('client_id', $client_id)->first();

    $principal_serialize = unserialize($submission_count->principal_name);
    $principal_serialize[$key] = $principal_name;

    $start_date_serialize = unserialize($submission_count->start_date);
    $start_date_serialize[$key] = $start_date;

    $gross_serialize = unserialize($submission_count->value_gross);
    $gross_serialize[$key] = $value_gross;

    if($submission_type == 1){     

      $site = Input::get('site');
      $finish_date = date('Y-m-d', strtotime(Input::get('finish_date')));

      $site_serialize = unserialize($submission_count->site);
      $site_serialize[$key] = $site;

      $finish_serialize = unserialize($submission_count->finish_date);
      $finish_serialize[$key] = $finish_date;

      $data['principal_name'] = serialize($principal_serialize);
      $data['start_date'] = serialize($start_date_serialize);
      $data['value_gross'] = serialize($gross_serialize);

      $data['site'] = serialize($site_serialize);
      $data['finish_date'] = serialize($finish_serialize);
      
      DB::table('rct_submission')->where('client_id', $client_id)->update($data);
    }
    else{

      $sub_contractor_name = Input::get('sub_contractor_name');
      $sub_contractor_id = Input::get('sub_contractor_id');
      $value_net = Input::get('value_net');
      $deduction = Input::get('deduction');

      $sub_contractor_serialize = unserialize($submission_count->sub_contractor);
      $sub_contractor_serialize[$key] = $sub_contractor_name;

      $sub_contractor_id_serialize = unserialize($submission_count->sub_contractor_id);
      $sub_contractor_id_serialize[$key] = $sub_contractor_id;

      $value_net_serialize = unserialize($submission_count->value_net);
      $value_net_serialize[$key] = $value_net;

      $deduction_serialize = unserialize($submission_count->deduction);
      $deduction_serialize[$key] = $deduction;

      $data['principal_name'] = serialize($principal_serialize);
      $data['start_date'] = serialize($start_date_serialize);
      $data['value_gross'] = serialize($gross_serialize);

      $data['sub_contractor'] = serialize($sub_contractor_serialize);
      $data['sub_contractor_id'] = serialize($sub_contractor_id_serialize);
      $data['value_net'] = serialize($value_net_serialize);
      $data['deduction'] = serialize($deduction_serialize);
      
      DB::table('rct_submission')->where('client_id', $client_id)->update($data);
    }

   

    return redirect::back()->with('message', 'RCT Submission was update Successfully');
  }
  public function rctsaveaspdf(){
    $client_id = Input::get('client_id');
    $type = Input::get('type');
    $key = Input::get('key');

    $submission_count = DB::table('rct_submission')->where('client_id', $client_id)->first();

    $unserialize_rctid = unserialize($submission_count->rct_id);
    $unserialize_principal = unserialize($submission_count->principal_name);
    $unserialize_start = unserialize($submission_count->start_date);
    $unserialize_gross = unserialize($submission_count->value_gross);

    $tax_key = $unserialize_principal[$key];

    $tax_details = DB::table('rct_tax_number')->where('tax_client_id', $client_id)->first();
    $explode_tax_name = explode(',', $tax_details->tax_name);
    $explode_tax_number = explode(',', $tax_details->tax_number);


    if($type == 1){
      $unserialize_finish = unserialize($submission_count->finish_date);
      $unserialize_site = unserialize($submission_count->site);
      $outputprint='
      <style>
        @page { margin: 0in; }
        body {
            background-image: url('.URL::to('assets/invoice_letterpad.jpg').');
            background-position: top left right bottom;
          background-repeat: no-repeat;
          background-size:auto;
          font-family: Verdana,Geneva,sans-serif; 
        }
      </style>
      <body style="width:100%">
      <table style="width:95%; margin:230px 0px 0px 20px; height:auto; float:left; font-family: Open Sans, sans-serif !important; font-size:14px;">
        <tr>
        <td colspan="2" align="center" valign="top" style="height:35px;"><b>Contract Notification</b></td>
        </tr>
        <tr>
          <td style="height:35px; text-align:left">Revenue Contract ID:</td>
          <td style="text-align:right">'.$unserialize_rctid[$key].'</td>
        </tr>
        <tr>
          <td style="height:35px; text-align:left">Principal Contractor:</td>
          <td style="text-align:right">'.$explode_tax_name[$tax_key].'</td>
        </tr>
        <tr>
          <td style="height:35px; text-align:left">Principal Contractor Tax Id:</td>
          <td style="text-align:right">'.$explode_tax_number[$tax_key].'</td>
        </tr>
        <tr>
          <td style="height:35px; text-align:left">Start Date:</td>
          <td style="text-align:right">'.date('m/d/Y', strtotime($unserialize_start[$key])).'</td>
        </tr>
        <tr>
          <td style="height:35px; text-align:left">Finish Date:</td>
          <td style="text-align:right">'.date('m/d/Y', strtotime($unserialize_finish[$key])).'</td>
        </tr>
        <tr>
          <td style="height:35px; text-align:left">Contractor Value:</td>
          <td style="text-align:right">'.number_format_invoice_without_decimal($unserialize_gross[$key]).'</td>
        </tr>
        <tr>
          <td style="height:35px; text-align:left">Site ID:</td>
          <td style="text-align:right">'.$unserialize_site[$key].'</td>
        </tr>
        
      </table>
      </body>
      ';
    }
    else{
      $unserialize_sub_contractor = unserialize($submission_count->sub_contractor);
      $unserialize_sub_contractorid = unserialize($submission_count->sub_contractor_id);
      $unserialize_deduction = unserialize($submission_count->deduction);
      $unserialize_value_net = unserialize($submission_count->value_net);

      $rate = $unserialize_deduction[$key]/$unserialize_gross[$key];
      $rate_percentage = $rate*100;

      $outputprint='
      <table style="width:95%; margin:230px 0px 0px 20px; height:auto; float:left; font-family: Open Sans, sans-serif !important; font-size:14px;">
        <tr>
        <td colspan="2" align="center" valign="top" style="height:35px;"><b>Payment Notification</b></td>
        </tr>
        <tr>
          <td style="height:35px; text-align:left">Revenue Contract ID:</td>
          <td style="text-align:right">'.$unserialize_rctid[$key].'</td>
        </tr>
        <tr>
          <td style="height:35px; text-align:left">Principal Contractor:</td>
          <td style="text-align:right">'.$explode_tax_name[$tax_key].'</td>
        </tr>
        <tr>
          <td style="height:35px; text-align:left">Principal Contractor Tax Id:</td>
          <td style="text-align:right">'.$explode_tax_number[$tax_key].'</td>
        </tr>
        <tr>
          <td style="height:35px; text-align:left">Sub Contractor:</td>
          <td style="text-align:right">'.$unserialize_sub_contractor[$key].'</td>
        </tr>
        <tr>
          <td style="height:35px; text-align:left">Sub Contractor Id:</td>
          <td style="text-align:right">'.$unserialize_sub_contractorid[$key].'</td>
        </tr>
        <tr>
          <td style="height:35px; text-align:left">Payment Date:</td>
          <td style="text-align:right">'.date('m/d/Y', strtotime($unserialize_start[$key])).'</td>
        </tr>        
        <tr>
          <td style="height:35px; text-align:left">Gross Payment:</td>
          <td style="text-align:right">'.number_format_invoice_without_decimal($unserialize_gross[$key]).'</td>
        </tr>
        <tr>
          <td style="height:35px; text-align:left">Deduction:</td>
          <td style="text-align:right">'.number_format_invoice_without_decimal($unserialize_deduction[$key]).'</td>
        </tr>
        <tr>
          <td style="height:35px; text-align:left">Net Payment:</td>
          <td style="text-align:right">'.number_format_invoice_without_decimal($unserialize_value_net[$key]).'</td>
        </tr>
        <tr>
          <td style="height:35px; text-align:left">Rate:</td>
          <td style="text-align:right">'.number_format_invoice_without_decimal($rate_percentage).'%</td>
        </tr>
        <tr>
          <td style="height:35px; text-align:left"></td>
          <td style="text-align:right"></td>
        </tr>
        <tr>
          <td colspan="2">
          The Revenue Commissioners have been notified that you are about to make a relevant payment of '.number_format_invoice_without_decimal($unserialize_gross[$key]).' to the above subcontractor.
          </td>
        </tr>
        
      </table>
      ';
    }

    /*$pdf = PDF::loadHTML($outputprint);
    $pdf->setPaper('A4', 'portrait');*/

    if($type == 1){
      $filename = 'Contract Notification';      
    }
    else{
      $filename = 'Payment Notification';
    }

    $pdf = PDF::loadHTML($outputprint);
    $pdf->setPaper('A4', 'portrait');
    $pdf->save('papers/RCT_Notification.pdf');
    echo 'RCT_Notification.pdf';
  }
  public function rctsaveaspdf_multiple(){
    $client_id = Input::get('client_id');
    $keys = explode(',',Input::get('keys'));
    $outputprint = '';
    if(count($keys))
    {
      foreach($keys as $key)
      {
        $submission_count = DB::table('rct_submission')->where('client_id', $client_id)->first();

        $unserialize_rctid = unserialize($submission_count->rct_id);
        $unserialize_principal = unserialize($submission_count->principal_name);
        $unserialize_start = unserialize($submission_count->start_date);
        $unserialize_gross = unserialize($submission_count->value_gross);

        $unserialize_type = unserialize($submission_count->type);

        $tax_key = $unserialize_principal[$key];

        $tax_details = DB::table('rct_tax_number')->where('tax_client_id', $client_id)->first();
        $explode_tax_name = explode(',', $tax_details->tax_name);
        $explode_tax_number = explode(',', $tax_details->tax_number);


        if($unserialize_type[$key] == "1"){
          $unserialize_finish = unserialize($submission_count->finish_date);
          $unserialize_site = unserialize($submission_count->site);
          $outputprint.='
          <style>
            @page { margin: 0in; }
            body {
                background-image: url('.URL::to('assets/invoice_letterpad.jpg').');
                background-position: top left right bottom;
              background-repeat: no-repeat;
              background-size:auto;
              font-family: Verdana,Geneva,sans-serif; 
              
            }
          </style>
          <body style="width:100%">
          <table style="width:95%; margin:230px 0px 0px 20px; height:auto; float:left; font-family: Open Sans, sans-serif !important; font-size:14px;page-break:always;">
            <tr>
            <td colspan="2" align="center" valign="top" style="height:35px;"><b>Contract Notification</b></td>
            </tr>
            <tr>
              <td style="height:35px; text-align:left">Revenue Contract ID:</td>
              <td style="text-align:right">'.$unserialize_rctid[$key].'</td>
            </tr>
            <tr>
              <td style="height:35px; text-align:left">Principal Contractor:</td>
              <td style="text-align:right">'.$explode_tax_name[$tax_key].'</td>
            </tr>
            <tr>
              <td style="height:35px; text-align:left">Principal Contractor Tax Id:</td>
              <td style="text-align:right">'.$explode_tax_number[$tax_key].'</td>
            </tr>
            <tr>
              <td style="height:35px; text-align:left">Start Date:</td>
              <td style="text-align:right">'.date('m/d/Y', strtotime($unserialize_start[$key])).'</td>
            </tr>
            <tr>
              <td style="height:35px; text-align:left">Finish Date:</td>
              <td style="text-align:right">'.date('m/d/Y', strtotime($unserialize_finish[$key])).'</td>
            </tr>
            <tr>
              <td style="height:35px; text-align:left">Contractor Value:</td>
              <td style="text-align:right">'.number_format_invoice_without_decimal($unserialize_gross[$key]).'</td>
            </tr>
            <tr>
              <td style="height:35px; text-align:left">Site ID:</td>
              <td style="text-align:right">'.$unserialize_site[$key].'</td>
            </tr>
            
          </table>
          </body>
          ';
        }
        else{
          $unserialize_sub_contractor = unserialize($submission_count->sub_contractor);
          $unserialize_sub_contractorid = unserialize($submission_count->sub_contractor_id);
          $unserialize_deduction = unserialize($submission_count->deduction);
          $unserialize_value_net = unserialize($submission_count->value_net);

          $rate = $unserialize_deduction[$key]/$unserialize_gross[$key];
          $rate_percentage = $rate*100;

          $outputprint.='
          <style>
            @page { margin: 0in; }
            body {
                background-image: url('.URL::to('assets/invoice_letterpad.jpg').');
                background-position: top left right bottom;
              background-repeat: no-repeat;
              background-size:auto;
              font-family: Verdana,Geneva,sans-serif; 
            }
          </style>
          <body style="width:100%">
          <table style="width:95%; margin:230px 0px 0px 20px; height:auto; float:left; font-family: Open Sans, sans-serif !important; font-size:14px;page-break:always;">
            <tr>
            <td colspan="2" align="center" valign="top" style="height:35px;"><b>Payment Notification</b></td>
            </tr>
            <tr>
              <td style="height:35px; text-align:left">Revenue Contract ID:</td>
              <td style="text-align:right">'.$unserialize_rctid[$key].'</td>
            </tr>
            <tr>
              <td style="height:35px; text-align:left">Principal Contractor:</td>
              <td style="text-align:right">'.$explode_tax_name[$tax_key].'</td>
            </tr>
            <tr>
              <td style="height:35px; text-align:left">Principal Contractor Tax Id:</td>
              <td style="text-align:right">'.$explode_tax_number[$tax_key].'</td>
            </tr>
            <tr>
              <td style="height:35px; text-align:left">Sub Contractor:</td>
              <td style="text-align:right">'.$unserialize_sub_contractor[$key].'</td>
            </tr>
            <tr>
              <td style="height:35px; text-align:left">Sub Contractor Id:</td>
              <td style="text-align:right">'.$unserialize_sub_contractorid[$key].'</td>
            </tr>
            <tr>
              <td style="height:35px; text-align:left">Payment Date:</td>
              <td style="text-align:right">'.date('m/d/Y', strtotime($unserialize_start[$key])).'</td>
            </tr>        
            <tr>
              <td style="height:35px; text-align:left">Gross Payment:</td>
              <td style="text-align:right">'.number_format_invoice_without_decimal($unserialize_gross[$key]).'</td>
            </tr>
            <tr>
              <td style="height:35px; text-align:left">Deduction:</td>
              <td style="text-align:right">'.number_format_invoice_without_decimal($unserialize_deduction[$key]).'</td>
            </tr>
            <tr>
              <td style="height:35px; text-align:left">Net Payment:</td>
              <td style="text-align:right">'.number_format_invoice_without_decimal($unserialize_value_net[$key]).'</td>
            </tr>
            <tr>
              <td style="height:35px; text-align:left">Rate:</td>
              <td style="text-align:right">'.number_format_invoice_without_decimal($rate_percentage).'%</td>
            </tr>
            <tr>
              <td style="height:35px; text-align:left"></td>
              <td style="text-align:right"></td>
            </tr>
            <tr>
              <td colspan="2">
              The Revenue Commissioners have been notified that you are about to make a relevant payment of '.number_format_invoice_without_decimal($unserialize_gross[$key]).' to the above subcontractor.
              </td>
            </tr>
            
          </table>
          </body>
          ';
        }
      }
    }

    $pdf = PDF::loadHTML($outputprint);
    $pdf->setPaper('A4', 'portrait');
    $pdf->save('papers/RCT_Notification.pdf');
    echo 'RCT_Notification.pdf';
  }
  public function rct_send_bulk_email()
  {
    $client_id = Input::get('hidden_client_id');
    $keys = explode(',',Input::get('hidden_keys'));

    $submission_count = DB::table('rct_submission')->where('client_id', $client_id)->first();
    $unserialize_rctid = unserialize($submission_count->rct_id);
    $unserialize_principal = unserialize($submission_count->principal_name);
    $unserialize_start = unserialize($submission_count->start_date);
    $unserialize_gross = unserialize($submission_count->value_gross);
    $unserialize_type = unserialize($submission_count->type);

    $tax_details = DB::table('rct_tax_number')->where('tax_client_id', $client_id)->first();
    $explode_tax_name = explode(',', $tax_details->tax_name);
    $explode_tax_number = explode(',', $tax_details->tax_number);

    $outputprint = '';
    if(count($keys))
    {
      foreach($keys as $key)
      {
        $tax_key = $unserialize_principal[$key];

        if($unserialize_type[$key] == "1"){
          $unserialize_finish = unserialize($submission_count->finish_date);
          $unserialize_site = unserialize($submission_count->site);
          $outputprint.='
          <style>
            @page { margin: 0in; }
            body {
                background-image: url('.URL::to('assets/invoice_letterpad.jpg').');
                background-position: top left right bottom;
              background-repeat: no-repeat;
              background-size:auto;
              font-family: Verdana,Geneva,sans-serif; 
              
            }
          </style>
          <body style="width:100%">
          <table style="width:95%; margin:230px 0px 0px 20px; height:auto; float:left; font-family: Open Sans, sans-serif !important; font-size:14px;page-break:always;">
            <tr>
            <td colspan="2" align="center" valign="top" style="height:35px;"><b>Contract Notification</b></td>
            </tr>
            <tr>
              <td style="height:35px; text-align:left">Revenue Contract ID:</td>
              <td style="text-align:right">'.$unserialize_rctid[$key].'</td>
            </tr>
            <tr>
              <td style="height:35px; text-align:left">Principal Contractor:</td>
              <td style="text-align:right">'.$explode_tax_name[$tax_key].'</td>
            </tr>
            <tr>
              <td style="height:35px; text-align:left">Principal Contractor Tax Id:</td>
              <td style="text-align:right">'.$explode_tax_number[$tax_key].'</td>
            </tr>
            <tr>
              <td style="height:35px; text-align:left">Start Date:</td>
              <td style="text-align:right">'.date('m/d/Y', strtotime($unserialize_start[$key])).'</td>
            </tr>
            <tr>
              <td style="height:35px; text-align:left">Finish Date:</td>
              <td style="text-align:right">'.date('m/d/Y', strtotime($unserialize_finish[$key])).'</td>
            </tr>
            <tr>
              <td style="height:35px; text-align:left">Contractor Value:</td>
              <td style="text-align:right">'.number_format_invoice_without_decimal($unserialize_gross[$key]).'</td>
            </tr>
            <tr>
              <td style="height:35px; text-align:left">Site ID:</td>
              <td style="text-align:right">'.$unserialize_site[$key].'</td>
            </tr>
            
          </table>
          </body>
          ';
        }
        else{
          $unserialize_sub_contractor = unserialize($submission_count->sub_contractor);
          $unserialize_sub_contractorid = unserialize($submission_count->sub_contractor_id);
          $unserialize_deduction = unserialize($submission_count->deduction);
          $unserialize_value_net = unserialize($submission_count->value_net);

          $rate = $unserialize_deduction[$key]/$unserialize_gross[$key];
          $rate_percentage = $rate*100;

          $outputprint.='
          <style>
            @page { margin: 0in; }
            body {
                background-image: url('.URL::to('assets/invoice_letterpad.jpg').');
                background-position: top left right bottom;
              background-repeat: no-repeat;
              background-size:auto;
              font-family: Verdana,Geneva,sans-serif; 
            }
          </style>
          <body style="width:100%">
          <table style="width:95%; margin:230px 0px 0px 20px; height:auto; float:left; font-family: Open Sans, sans-serif !important; font-size:14px;page-break:always;">
            <tr>
            <td colspan="2" align="center" valign="top" style="height:35px;"><b>Payment Notification</b></td>
            </tr>
            <tr>
              <td style="height:35px; text-align:left">Revenue Contract ID:</td>
              <td style="text-align:right">'.$unserialize_rctid[$key].'</td>
            </tr>
            <tr>
              <td style="height:35px; text-align:left">Principal Contractor:</td>
              <td style="text-align:right">'.$explode_tax_name[$tax_key].'</td>
            </tr>
            <tr>
              <td style="height:35px; text-align:left">Principal Contractor Tax Id:</td>
              <td style="text-align:right">'.$explode_tax_number[$tax_key].'</td>
            </tr>
            <tr>
              <td style="height:35px; text-align:left">Sub Contractor:</td>
              <td style="text-align:right">'.$unserialize_sub_contractor[$key].'</td>
            </tr>
            <tr>
              <td style="height:35px; text-align:left">Sub Contractor Id:</td>
              <td style="text-align:right">'.$unserialize_sub_contractorid[$key].'</td>
            </tr>
            <tr>
              <td style="height:35px; text-align:left">Payment Date:</td>
              <td style="text-align:right">'.date('m/d/Y', strtotime($unserialize_start[$key])).'</td>
            </tr>        
            <tr>
              <td style="height:35px; text-align:left">Gross Payment:</td>
              <td style="text-align:right">'.number_format_invoice_without_decimal($unserialize_gross[$key]).'</td>
            </tr>
            <tr>
              <td style="height:35px; text-align:left">Deduction:</td>
              <td style="text-align:right">'.number_format_invoice_without_decimal($unserialize_deduction[$key]).'</td>
            </tr>
            <tr>
              <td style="height:35px; text-align:left">Net Payment:</td>
              <td style="text-align:right">'.number_format_invoice_without_decimal($unserialize_value_net[$key]).'</td>
            </tr>
            <tr>
              <td style="height:35px; text-align:left">Rate:</td>
              <td style="text-align:right">'.number_format_invoice_without_decimal($rate_percentage).'%</td>
            </tr>
            <tr>
              <td style="height:35px; text-align:left"></td>
              <td style="text-align:right"></td>
            </tr>
            <tr>
              <td colspan="2">
              The Revenue Commissioners have been notified that you are about to make a relevant payment of '.number_format_invoice_without_decimal($unserialize_gross[$key]).' to the above subcontractor.
              </td>
            </tr>
            
          </table>
          </body>
          ';
        }
      }
    }

    $pdf = PDF::loadHTML($outputprint);
    $pdf->setPaper('A4', 'portrait');
    $pdf->save('papers/RCT_Notification.pdf');

    $path = 'papers/RCT_Notification.pdf';

    $from = Input::get('from_user');
    $toemails = Input::get('to_user').','.Input::get('cc_unsent');
    $sentmails = Input::get('to_user').', '.Input::get('cc_unsent');
    $subject = Input::get('subject_unsent'); 
    $message = Input::get('message_editor');
    
    $explode = explode(',',$toemails);
    $data['sentmails'] = $sentmails;
    $time = time();
    
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
        $email->AddAttachment( $path ,'RCT_Notification.pdf');
        $email->Send();     
      }

      $too = $explode[0];
      $get_client = DB::table('cm_clients')->where('email',$too)->orwhere('email2',$too)->first();
      if(count($get_client))
      {
        $client_id = $get_client->client_id;
      }
      else{
        $client_id = '';
      }

      $user_details = DB::table('user')->where('email',$from)->first();
      if(count($user_details))
      {
        $user_from = $user_details->user_id;
      }
      else{
        $user_from = 0;
      }

      if($client_id != "")
      {
        $client_details = DB::table('cm_clients')->where('client_id',$client_id)->first();
        $datamessage['message_id'] = $time;
        $datamessage['message_from'] = $user_from;
        $datamessage['subject'] = $subject;
        $datamessage['message'] = $contentmessage;
        $datamessage['client_ids'] = $client_id;
        $datamessage['primary_emails'] = $client_details->email;
        $datamessage['secondary_emails'] = $client_details->email2;
        $datamessage['date_sent'] = date('Y-m-d H:i:s');
        $datamessage['date_saved'] = date('Y-m-d H:i:s');
        $datamessage['source'] = "RCT SYSTEM";
        $datamessage['attachments'] = $path;
        $datamessage['status'] = 1;

        DB::table('messageus')->insert($datamessage);
      }

      $date = date('Y-m-d H:i:s');
      if(count($keys) == 1)
      {
        $keyi = Input::get('hidden_keys');
        if(isset($unserialize_start[$keyi]))
        {
          $start_date_val = date('Y-m', strtotime($unserialize_start[$keyi]));
        }
        else{
          $start_date_val = date('Y-m');
        }

        $datainert['client_id'] = $client_id;
        $datainert['submission_id'] = Input::get('hidden_keys');
        $datainert['start_date'] = $start_date_val;
        DB::table('rct_submission_email')->insert($datainert);
      }
      else{
        $datainert['client_id'] = $client_id;
        $datainert['email_sent'] = $date;
        DB::table('rct_liability_assessment_email')->insert($datainert);
      }
    }
    return redirect::back()->with('message', 'Email Sent Successfully');
  }
  public function rctsaveascsv_multiple()
  {
    $client_id = Input::get('client_id');
    $client_details = DB::table('cm_clients')->where('client_id',$client_id)->first();

    $keys = explode(',',Input::get('keys'));
    $outputprint = '';

    $columns = array('', '', '', '', '', 'RCT LIABILITIES ASSESMENT','', '', '','', '');
    $file = fopen('papers/rct liabilities assessment for client '.$client_details->company.'-'.$client_details->client_id.'.csv', 'w');
    fputcsv($file, $columns);

    $columns2 = array('Type', 'RCT ID', 'Principal Name', 'Sub Contractor Name', 'Sub Contractor ID', 'Site', 'Start/Pay Date', 'Finish Date', 'Value Gross', 'Value Net', 'Deduction');
    fputcsv($file, $columns2);



    if(count($keys))
    {
      foreach($keys as $key)
      {
        $submission_count = DB::table('rct_submission')->where('client_id', $client_id)->first();

        $unserialize_rctid = unserialize($submission_count->rct_id);
        $unserialize_principal = unserialize($submission_count->principal_name);
        $unserialize_start = unserialize($submission_count->start_date);
        $unserialize_gross = unserialize($submission_count->value_gross);
        $unserialize_net = unserialize($submission_count->value_net);
        $unserialize_deduction = unserialize($submission_count->deduction);
        $unserialize_type = unserialize($submission_count->type);
        $unserialize_finish = unserialize($submission_count->finish_date);
        $unserialize_site = unserialize($submission_count->site);
        $unserialize_sub_contractor = unserialize($submission_count->sub_contractor);
        $unserialize_sub_contractorid = unserialize($submission_count->sub_contractor_id);

        $tax_key = $unserialize_principal[$key];
        $principal_key = $unserialize_principal[$key];
        $tax_details = DB::table('rct_tax_number')->where('tax_client_id', $client_id)->first();
        if(count($tax_details))
        {
          $explode_tax_name = explode(',', $tax_details->tax_name);
          $explode_tax_number = explode(',', $tax_details->tax_number);

          $tx_name = '';
          $tx_number = '';
          if(isset($explode_tax_name[$principal_key]))
          {
            $tx_name = $explode_tax_name[$principal_key];
          }
          if(isset($explode_tax_name[$principal_key]))
          {
            $tx_number = $explode_tax_number[$principal_key];
          }

          $prin_name = $tx_name.' - '.$tx_number;
        }
        else{
          $explode_tax_name = array();
          $explode_tax_name = array();

          $prin_name = '';
        }
        

        if(isset($unserialize_rctid[$key])) { $rct_id = $unserialize_rctid[$key]; } else { $rct_id = ''; }
        if(isset($unserialize_principal[$key])) { 
          $principal = $unserialize_principal[$key]; 

        } else { $principal = ''; }
        if(isset($unserialize_sub_contractor[$key])) { $sub_contractor = $unserialize_sub_contractor[$key]; } else { $sub_contractor = ''; }
        if(isset($unserialize_sub_contractorid[$key])) { $sub_contractorid = $unserialize_sub_contractorid[$key]; } else { $sub_contractorid = ''; }
        if(isset($unserialize_site[$key])) { $site = $unserialize_site[$key]; } else { $site = ''; }
        if(isset($unserialize_start[$key])) { $start = $unserialize_start[$key]; } else { $start = ''; }
        if(isset($unserialize_finish[$key])) { $finish = $unserialize_finish[$key]; } else { $finish = ''; }
        if(isset($unserialize_gross[$key])) { $gross = $unserialize_gross[$key]; } else { $gross = ''; }
        if(isset($unserialize_net[$key])) { $net = $unserialize_net[$key]; } else { $net = ''; }
        if(isset($unserialize_deduction[$key])) { $deduction = $unserialize_deduction[$key]; } else { $deduction = ''; }

        if($unserialize_type[$key] == "1"){
          $columns3 = array('CONTRACT', $rct_id, $prin_name, $sub_contractor, $sub_contractorid, $site, $start, $finish, $gross, $net, $deduction);
          fputcsv($file, $columns3);
        }
        else{
          $columns3 = array('PAYMENT', $rct_id, $prin_name, $sub_contractor, $sub_contractorid, $site, $start, $finish, $gross, $net, $deduction);
          fputcsv($file, $columns3);
        }
      }
    }
    fclose($file);

    echo 'rct liabilities assessment for client '.$client_details->company.'-'.$client_details->client_id.'.csv';
  }
  public function rctliabilityassessment($id=""){
    $client = DB::table('cm_clients')->where('client_id', $id)->first();
    $tax = DB::table('rct_tax_number')->where('tax_client_id', $id)->first();
    $rct_submission = DB::table('rct_submission')->where('client_id', $id)->first();

    $client_id = $id;

    return view('user/rct_system/rct_liability_assessment', array('title' => 'TA System', 'client_details' => $client, 'taxlist' => $tax, 'rctsubmission' => $rct_submission, 'client_id' => $client_id));
  }
  public function rctsubmissionview(){
    $date_get = Input::get('date');
    $client_id = Input::get('client_id');    
    $rct_submission = DB::table('rct_submission')->where('client_id', $client_id)->first();

    $unserializedate = unserialize($rct_submission->start_date);
    $type = unserialize($rct_submission->type);
    $rct_id = unserialize($rct_submission->rct_id);
    $principal_name = unserialize($rct_submission->principal_name);
    $sub_contractor = unserialize($rct_submission->principal_name);
    $start_date_unserialize = unserialize($rct_submission->start_date);
    $value_gross = unserialize($rct_submission->value_gross);

    $result_view_submission='';
    if(count($unserializedate)){
      foreach ($unserializedate as $key => $start_date) {
        
        $tax_details = DB::table('rct_tax_number')->where('tax_client_id', $client_id)->first();
        $explode_tax_name = explode(',', $tax_details->tax_name);
        $explode_tax_number = explode(',', $tax_details->tax_number);

        $principal_key = $principal_name[$key];        

        $date = substr($start_date,0,7);
        $sub_cont_name='';
        $sub_cont_id='';
        $site_view='';
        $finish_date_view = '';
        $value_net_view = '';
        $deduction_view = '';
        if($date_get == $date){
          if($type[$key] == 1){
            $type_view = 'Contract';

            $site = unserialize($rct_submission->site);  
            $finish_date = unserialize($rct_submission->finish_date); 
            

            if(isset($site[$key])){                  
              $site_view = $site[$key];                  
            }

            if(isset($finish_date[$key])){
              $finish_date_view = date('d-M-Y', strtotime($finish_date[$key]));
            }            
          }
          elseif($type[$key] == 2){
            $type_view = 'Payment';

            $sub_contractor = unserialize($rct_submission->sub_contractor);
            $sub_contractor_id = unserialize($rct_submission->sub_contractor_id);

            $value_net = unserialize($rct_submission->value_net);
            $deduction = unserialize($rct_submission->deduction); 

            if(isset($sub_contractor[$key])){
              $sub_cont_name = $sub_contractor[$key];
            }
            if(isset($sub_contractor_id[$key])){
              $sub_cont_id = $sub_contractor_id[$key];
            }
             if(isset($value_net[$key])){
              $value_net_view = number_format_invoice_without_decimal($value_net[$key]);
            }
            if(isset($deduction[$key])){
              $deduction_view = number_format_invoice_without_decimal($deduction[$key]);
            }
          }          

          $result_view_submission.='
          <tr>
            <td>'.$type_view.'</td>
            <td>'.$rct_id[$key].'</td>
            <td>'.$explode_tax_name[$principal_key].' - '.$explode_tax_number[$principal_key].'</td>
            <td>'.$sub_cont_name.'</td>
            <td>'.$sub_cont_id.'</td>
            <td>'.$site_view.'</td>
            <td>'.date('d-M-Y', strtotime($start_date_unserialize[$key])).'</td>
            <td>'.$finish_date_view.'</td>
            <td style="text-align:right">'.number_format_invoice_without_decimal($value_gross[$key]).'</td>
            <td style="text-align:right">'.$value_net_view.'</td>
            <td style="text-align:right">'.$deduction_view.'</td>
          </tr>
          ';
        }
        
        
      }
    }
    else{
      $result_view_submission = 'Empty';
    }

    $month_year = date('M-Y', strtotime($date_get));

    echo json_encode(array('result_view_submission' => $result_view_submission, 'month_year' => $month_year));
  }

  public function rctliabilityfilter(){
    $date_get = Input::get('date');
    $client_id = Input::get('client_id');    
    $rctsubmission = DB::table('rct_submission')->where('client_id', $client_id)->first();

    $outputsubmission='';
    $site_view = '';
    $sub_cont_name = '';
    $sub_cont_id = '';
    $finish_date_view = '';
    $value_net_view = '';
    $deduction_view = '';
    $type_text='';
    $rct_id='';
    $key='';
    $principal_key='';
    $explode_tax_name='';
    $explode_tax_number='';

    if(count($rctsubmission)){            
      $rct_type = unserialize($rctsubmission->type);
      $rct_id = unserialize($rctsubmission->rct_id);
      $principal_name = unserialize($rctsubmission->principal_name);
      $start_date = unserialize($rctsubmission->start_date);
      $value_gross = unserialize($rctsubmission->value_gross);

      $count_submitton = 0;
      $total_gross='';
      $total_net='';
      $total_deduction='';
      if(count($rct_type)){
        $counti = 0;
        foreach ($rct_type as $key => $type) {
          $check_date = date('Y-m', strtotime($start_date[$key]));
          if($check_date == $date_get)
          {
            $count_submitton++;
            if($type == 1){
              $type_text = 'CONTRACT';    
              
              $site = unserialize($rctsubmission->site);  
              $finish_date = unserialize($rctsubmission->finish_date); 
              $value_net = unserialize($rctsubmission->value_net);
              $deduction = unserialize($rctsubmission->deduction);           
            }
            elseif(($type == 2)){
              $type_text = 'PAYMENT';

              $sub_contractor = unserialize($rctsubmission->sub_contractor);
              $sub_contractor_id = unserialize($rctsubmission->sub_contractor_id);
              $value_net = unserialize($rctsubmission->value_net);
              $deduction = unserialize($rctsubmission->deduction);
            }
            else{
              $type_text='';
            }

            $tax_details = DB::table('rct_tax_number')->where('tax_client_id', $client_id)->first();
            $explode_tax_name = explode(',', $tax_details->tax_name);
            $explode_tax_number = explode(',', $tax_details->tax_number);
            $principal_key = $principal_name[$key];

            if(isset($sub_contractor[$key])){
              $sub_cont_name = $sub_contractor[$key];
            }
            else{
              $sub_cont_name = '';
            }

            if(isset($sub_contractor_id[$key])){
              $sub_cont_id = $sub_contractor_id[$key];
            }
            else{
              $sub_cont_id = '';
            }

            if(isset($site[$key])){                  
                $site_view = $site[$key];                  
            }
            else{
              $site_view = '';
            }

            if(isset($finish_date[$key])){
              $finish_date_view = date('d-M-Y', strtotime($finish_date[$key]));
              $finish_date_spam = strtotime($finish_date[$key]);
            }
            else{
              $finish_date_view = '';
              $finish_date_spam = '';
            }

            if(isset($value_net[$key])){
              $value_net_total = $value_net[$key];
              $value_net_view = number_format_invoice_without_decimal($value_net[$key]);
            }
            else{
              $value_net_total = 0;
              $value_net_view = '0.00';
            }

            if(isset($deduction[$key])){
              $value_deduction_total = $deduction[$key];
              $deduction_view = number_format_invoice_without_decimal($deduction[$key]);
            }
            else{
              $value_deduction_total = 0;
              $deduction_view = '0.00';
            }

            $total_gross = $total_gross+$value_gross[$key];
            $total_net = $total_net+$value_net_total;
            $total_deduction = $total_deduction+$value_deduction_total;

            $outputsubmission.='
            <tr>
              <td style="text-align:left"><input type="checkbox" class="select_class select_class_'.$type.'" value="'.$key.'"  /><label>&nbsp;</label></td>
              <td style="text-align:left">'.$type_text.'</td>
              <td style="text-align:left">'.$rct_id[$key].'<br/></td>
              <td style="text-align:left">'.$explode_tax_name[$principal_key].' - '.$explode_tax_number[$principal_key].'</td>
              <td style="text-align:left">'.$sub_cont_name.'</td>
              <td style="text-align:left">'.$sub_cont_id.'</td>
              <td style="text-align:left">'.$site_view.'</td>
              <td style="text-align:left"><spam style="display:none">'.strtotime($start_date[$key]).'</spam>'.date('d-M-Y', strtotime($start_date[$key])).'</td>
              <td style="text-align:left"><spam style="display:none">'.$finish_date_spam.'</spam>'.$finish_date_view.'</td>
              <td style="text-align:left"><input type="hidden" class="gross_value_class" value="'.$value_gross[$key].'"> 
              '.number_format_invoice_without_decimal($value_gross[$key]).'</td>
              <td style="text-align:left"><input type="hidden" class="net_value_class" value="'.$value_net_total.'"> '.$value_net_view.'</td>
              <td style="text-align:left"><input type="hidden" class="deduction_value_class" value="'.$value_deduction_total.'"> '.$deduction_view.'</td>
              <td style="text-align:left">
                <a href="javascript:" title="Download Pdf"><i class="fa fa-download download_submission" data-element="'.$type.'" id="'.$key.'"></i></a>&nbsp;&nbsp;
                <a href="javascript:" title="View / Edit"><i class="fa fa-pencil edit_class"  data-element="'.$type.'" id="'.$key.'"></i></a>&nbsp;&nbsp;
                <a href="javascript:" title="Email"><i class="fa fa-envelope email_class_single"  data-element="'.$type.'" id="'.$key.'"></i></a>
              </td>
            </tr>
            ';
            $counti++;
          }
          
        }
        if($counti == 0)
        {
          $outputsubmission='
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td align="left">Empty</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          ';
        }
      }  
      else{
        $outputsubmission='
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td align="left">Empty</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        ';
      }            
    }          
    else{
      $outputsubmission='
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td align="left">Empty</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      ';
      $total_gross='';
      $total_net='';
      $total_deduction='';
      $count_submitton=0;
    }
    $outputsubmission.='<tr style="border-top:1px solid #000;">
      <td style="border-top:0px;"></td>
      <td style="border-top:0px;"></td>
      <td style="border-top:0px;"></td>
      <td style="border-top:0px;"></td>
      <td style="border-top:0px;"></td>
      <td style="border-top:0px;"></td>
      <td style="border-top:0px;"></td>
      <td style="border-top:0px;"></td>
      <td align="left" style="width: 160px; border-top:0px;">Total</td>
      <td align="left" style="width: 130px; border-top:0px;">
        <input type="hidden" class="gross_total" value="'.$total_gross.'" name="">
        <span class="gross_total_span">'.number_format_invoice_without_decimal($total_gross).'</td></span>
        
      <td align="left" style="width: 130px; border-top:0px;">
        <input type="hidden" class="net_total" value="'.$total_net.'" name="">
        <span class="net_total_span">'.number_format_invoice_without_decimal($total_net).'</span>                
        </td>
      <td align="left" style="width: 130px; border-top:0px;">
        <input type="hidden" class="deduction_total" value="'.$total_deduction.'" name="">
        <span class="deduction_total_span">'.number_format_invoice_without_decimal($total_deduction).'</span>              
      </td>
      <td align="left" style="width: 120px; border-top:0px;"></td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td align="left" style="width: 160px;">Total of Selected</td>
      <td align="left" style="width: 130px;"><span class="input_readonly already_gross"></span></td>
      <td align="left" style="width: 130px;"><span class="input_readonly already_net"></span></td>
      <td align="left" style="width: 130px;"><span class="input_readonly already_deduction"></span></td>
      <td align="left"></td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td align="left" style="width: 160px;">Difference</td>
      <td align="left" style="width: 130px;"><span class="input_readonly difference_gross"></span></td>
      <td align="left" style="width: 130px;"><span class="input_readonly difference_net"></span></td>
      <td align="left" style="width: 130px;"><span class="input_readonly difference_deduction"></span></td>
      <td align="left"></td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td align="left" style="width: 160px;">Total Items</td>
      <td align="left" style="width: 130px;"><input type="text" class="input_readonly total_items" value="'.$count_submitton.'" readonly name=""></td>
      <td align="left" style="width: 130px;"></td>
      <td align="left" style="width: 130px;"></td>
      <td align="left"></td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td align="left" style="width: 160px;">Items Selected</td>
      <td align="left" style="width: 130px;"><input type="text" value="" class="input_readonly items_selected" readonly name=""></td>
      <td align="left" style="width: 130px;"></td>
      <td align="left" style="width: 130px;"></td>
      <td align="left"></td>
    </tr>';

    echo json_encode(array('result_liability_submission' => $outputsubmission, 'total_gross' => number_format_invoice_without_decimal($total_gross), 'total_net' => number_format_invoice_without_decimal($total_net), 'total_deduction' => number_format_invoice_without_decimal($total_deduction), 'count_submitton' => $count_submitton));
  }
  public function set_rct_active_month()
  {
    $dateval = Input::get('date');
      $ival=1;
      $output_result = '';
      $clientlist = DB::table('cm_clients')->select('client_id', 'firstname', 'surname', 'company', 'status', 'active', 'id')->orderBy('id','asc')->get();
      if(count($clientlist)){              
        foreach($clientlist as $client){
          if($client->active == 2)
          {
            $color='color:#f00;';
          }
          else{
            $color="";
          }

          $current_month = date('Y-m');
          $prevdate = date("Y-m-05", strtotime("-1 months"));
          $prev_date2 = date('Y-m', strtotime($prevdate));
          $active_drop='<option value="'.$current_month.'">'.date('M-Y', strtotime($current_month)).'</option>';
          for($i=0;$i<=22;$i++)
          {
            $month = $i + 1;
            $newdate = date("Y-m-05", strtotime("-".$month." months"));
            $formatted_date = date('M-Y', strtotime($newdate));
            $formatted_date2 = date('Y-m', strtotime($newdate));

            if($dateval == $formatted_date2){ $checked = 'selected'; } else { $checked = ''; }
            $active_drop.='<option value="'.$formatted_date2.'" '.$checked.'>'.$formatted_date.'</option>';
          }


          $deductionsum = 0;
          $grosssum = 0;
          $netsum = 0;
          $icount = 0;

          $rct_output = '';
          $rctsubmission = DB::table('rct_submission')->where('client_id', $client->client_id)->first();
          if(count($rctsubmission)){
            $start_date = unserialize($rctsubmission->start_date);
            $grossval = unserialize($rctsubmission->value_gross);
            $netval = unserialize($rctsubmission->value_net);
            $deductionval = unserialize($rctsubmission->deduction);

            $prevdate = date("Y-m-05", strtotime("-1 months"));
            $prev_date2 = date('Y-m', strtotime($prevdate));
            $data = array();
            if(count($start_date))
            {
              foreach($start_date as $key => $start)
              {
                $date = substr($start,0,7);
                if($date == $dateval)
                {
                  if(isset($data[$date]))
                  {
                    $implodeval = implode(",",$data[$date]);                  
                    $combineval = $implodeval.','.$key;
                    $data[$date] = explode(',',$combineval);

                  }
                  else{
                    $data[$date] = array($key);
                  }
                }
              }
            }
            krsort($data);
            if(count($data))
            {
              foreach($data as $key_date => $dataval)
              {
                $grosssum = 0;
                $netsum = 0;
                $deductionsum = 0;
                $icount = 0;
                if(count($dataval))
                {
                  foreach($dataval as $sumvalue)
                  {
                    if(isset($grossval[$sumvalue]))
                    {
                      $grosssum = $grosssum + $grossval[$sumvalue];
                    }
                    else{
                      $grosssum = $grosssum + 0;
                    }

                    if(isset($netval[$sumvalue]))
                    {
                      $netsum = $netsum + $netval[$sumvalue];
                    }
                    else{
                      $netsum = $netsum + 0;
                    }

                    if(isset($deductionval[$sumvalue]))
                    {
                      $deductionsum = $deductionsum + $deductionval[$sumvalue];
                    }
                    else{
                      $deductionsum = $deductionsum + 0;
                    }
                    $icount++;
                  }
                }
              }
            }
          }
          $deductionsum = ($deductionsum)?number_format_invoice_without_decimal($deductionsum):'-';
          $grosssum = ($grosssum)?number_format_invoice_without_decimal($grosssum):'-';
          $netsum = ($netsum)?number_format_invoice_without_decimal($netsum):'-';
          $icount = ($icount)?$icount:'-';
          $clientname = ($client->company == "")?$client->firstname.' & '.$client->surname:$client->company;

          $output_result.='<tr class="edit_task tr_client_td_'.$client->client_id.'">
            <td style="'.$color.'">'.$ival.'</td>
            <td align="left"><a href="javascript:" data-element="'.URL::to('user/rct_client_manager/'.$client->client_id).'" class="view_rct_manager" style="'.$color.'">'.$client->client_id.'</a></td>
            <td align="left"><a href="javascript:" data-element="'.URL::to('user/rct_client_manager/'.$client->client_id).'" class="view_rct_manager" style="'.$color.'">'.$clientname.'</a></td>
            <td align="left"><a href="javascript:" data-element="'.URL::to('user/rct_client_manager/'.$client->client_id).'" class="view_rct_manager" style="'.$color.'">'.$client->firstname.'</a></td>
            <td align="left"><a href="javascript:" data-element="'.URL::to('user/rct_client_manager/'.$client->client_id).'" class="view_rct_manager" style="'.$color.'">'.$client->surname.'</a></td>
            <td align="left"><select class="form-control select_active_month" data-element="'.$client->client_id.'" style="'.$color.'">'.$active_drop.'</select></td>
            <td align="left" class="deduction_clientid" style="'.$color.'">'.$deductionsum.'</td>
            <td align="left" class="gross_clientid" style="'.$color.'">'.$grosssum.'</td>
            <td align="left" class="net_clientid" style="'.$color.'">'.$netsum.'</td>
            <td align="left" class="count_clientid" style="'.$color.'">'.$icount.'</td>
            <td align="left" class="emails_clientid">';
            $emails = DB::table('rct_submission_email')->where('client_id',$client->client_id)->where('start_date',$dateval)->get();
            if(count($emails))
            {
              foreach($emails as $email)
              {
                $output_result.='<p style="'.$color.'">'.date('F d, Y', strtotime($email->email_sent)).'</p>';
              }
            }
            $output_result.='</td>
            <td align="center"><a href="'.URL::to('user/rct_liability_assessment/'.$client->client_id).'" class="fa fa-eye view_liability_assessment" style="'.$color.'"></a></td>
          </tr>';
          $ival++;
        }              
      }
      if($ival == 1)
      {
        $output_result.='<tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td align="center">Empty</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            </tr>';
      }
      echo $output_result;
  }
  public function set_rct_active_month_individual()
  {
    $dateval = Input::get('date');
    $client_id = Input::get('client_id');

    $ival=1;
    $output_result = '';
    $client = DB::table('cm_clients')->select('client_id', 'firstname', 'surname', 'company', 'status', 'active', 'id')->where('client_id',$client_id)->orderBy('id','asc')->first();
    $deductionsum = 0;
    $grosssum = 0;
    $netsum = 0;
    $icount = 0;
    if(count($client)){
        $current_month = date('Y-m');
        $prevdate = date("Y-m-05", strtotime("-1 months"));
        $prev_date2 = date('Y-m', strtotime($prevdate));
        $active_drop='<option value="'.$current_month.'">'.date('M-Y', strtotime($current_month)).'</option>';

        for($i=0;$i<=22;$i++)
        {
          $month = $i + 1;
          $newdate = date("Y-m-05", strtotime("-".$month." months"));
          $formatted_date = date('M-Y', strtotime($newdate));
          $formatted_date2 = date('Y-m', strtotime($newdate));

          if($dateval == $formatted_date2){ $checked = 'selected'; } else { $checked = ''; }
          $active_drop.='<option value="'.$formatted_date2.'" '.$checked.'>'.$formatted_date.'</option>';
        }

        

        $rct_output = '';
        $rctsubmission = DB::table('rct_submission')->where('client_id', $client->client_id)->first();
        if(count($rctsubmission)){
          $start_date = unserialize($rctsubmission->start_date);
          $grossval = unserialize($rctsubmission->value_gross);
          $netval = unserialize($rctsubmission->value_net);
          $deductionval = unserialize($rctsubmission->deduction);

          $prevdate = date("Y-m-05", strtotime("-1 months"));
          $prev_date2 = date('Y-m', strtotime($prevdate));
          $data = array();
          if(count($start_date))
          {
            foreach($start_date as $key => $start)
            {
              $date = substr($start,0,7);
              if($date == $dateval)
              {
                if(isset($data[$date]))
                {
                  $implodeval = implode(",",$data[$date]);                  
                  $combineval = $implodeval.','.$key;
                  $data[$date] = explode(',',$combineval);

                }
                else{
                  $data[$date] = array($key);
                }
              }
            }
          }
          krsort($data);
          if(count($data))
          {
            foreach($data as $key_date => $dataval)
            {
              $grosssum = 0;
              $netsum = 0;
              $deductionsum = 0;
              $icount = 0;
              if(count($dataval))
              {
                foreach($dataval as $sumvalue)
                {
                  if(isset($grossval[$sumvalue]))
                  {
                    $grosssum = $grosssum + $grossval[$sumvalue];
                  }
                  else{
                    $grosssum = $grosssum + 0;
                  }

                  if(isset($netval[$sumvalue]))
                  {
                    $netsum = $netsum + $netval[$sumvalue];
                  }
                  else{
                    $netsum = $netsum + 0;
                  }

                  if(isset($deductionval[$sumvalue]))
                  {
                    $deductionsum = $deductionsum + $deductionval[$sumvalue];
                  }
                  else{
                    $deductionsum = $deductionsum + 0;
                  }
                  $icount++;
                }
              }
            }
          }
        }
        $deductionsum = ($deductionsum)?number_format_invoice_without_decimal($deductionsum):'-';
        $grosssum = ($grosssum)?number_format_invoice_without_decimal($grosssum):'-';
        $netsum = ($netsum)?number_format_invoice_without_decimal($netsum):'-';
        $icount = ($icount)?$icount:'-';
        $clientname = ($client->company == "")?$client->firstname.' & '.$client->surname:$client->company;

        $email_text = '';
        $emails = DB::table('rct_submission_email')->where('client_id',$client->client_id)->where('start_date',$dateval)->get();
        if(count($emails))
        {
          foreach($emails as $email)
          {
            $email_text.='<p>'.date('F d, Y', strtotime($email->email_sent)).'</p>';
          }
        }
    }
    echo json_encode(array("deduction" => $deductionsum, "gross" => $grosssum, "net" => $netsum, "count" => $icount,'email_text' => $email_text));
  }
  public function rct_load_all_liabilities()
  {
    $client_id = Input::get('client_id');
    $rct_output = '';
    $rctsubmission = DB::table('rct_submission')->where('client_id', $client_id)->first();
    if(count($rctsubmission)){
      $start_date = unserialize($rctsubmission->start_date);
      $grossval = unserialize($rctsubmission->value_gross);
      $netval = unserialize($rctsubmission->value_net);
      $deductionval = unserialize($rctsubmission->deduction);

      $data = array();

      $email_date = Db::table('rct_submission_email')->where('client_id',$client_id)->groupBy('start_date')->get();
      if(count($email_date))
      {
        foreach($email_date as $email)
        {
          $data[$email->start_date] = array();
        }
      }

      if(count($start_date))
      {
        foreach($start_date as $key => $start)
        {
          $date = substr($start,0,7);
          if(isset($data[$date]))
          {
            if(count($data[$date]))
            {
              $implodeval = implode(",",$data[$date]);                  
              $combineval = $implodeval.','.$key;
              $data[$date] = explode(',',$combineval);
            }
            else{
              $data[$date] = array($key);
            }

          }
          else{
            $data[$date] = array($key);
          }
        }
      }
      krsort($data);
      if(count($data))
      {
        $keyival = 1;
        foreach($data as $key_date => $dataval)
        {
          $grosssum = 0;
          $netsum = 0;
          $deductionsum = 0;
          $icount = 0;
          if(count($dataval))
          {
            foreach($dataval as $sumvalue)
            {
              if(isset($grossval[$sumvalue]))
              {
                $grosssum = $grosssum + $grossval[$sumvalue];
              }
              else{
                $grosssum = $grosssum + 0;
              }

              if(isset($netval[$sumvalue]))
              {
                $netsum = $netsum + $netval[$sumvalue];
              }
              else{
                $netsum = $netsum + 0;
              }

              if(isset($deductionval[$sumvalue]))
              {
                $deductionsum = $deductionsum + $deductionval[$sumvalue];
              }
              else{
                $deductionsum = $deductionsum + 0;
              }
              $icount++;
            }
            $actions = '<a href="javascript:" title="Rebuild"><i class="fa fa-refresh rebuild_single" data-element="'.$key_date.'" aria-hidden="true"></i></a>&nbsp;&nbsp;
                      <a href="javascript:" title="View"><i class="fa fa-files-o view_class" data-element="'.$key_date.'" aria-hidden="true"></i></a>&nbsp;&nbsp;';
            $grosssum = number_format_invoice_without_decimal($grosssum);
            $netsum = number_format_invoice_without_decimal($netsum);
            $deductionsum = number_format_invoice_without_decimal($deductionsum);
            $icount = $icount;
          }
          else{
            $grosssum = '-';
            $netsum = '-';
            $deductionsum = '-';
            $icount = '-';
            $actions = '';
          }

          $email_date = Db::table('rct_submission_email')->where('client_id',$client_id)->where('start_date',$key_date)->get();
          $emails_sent = '';
          if(count($email_date))
          {
            foreach($email_date as $email)
            {
              $emails_sent.='<p class="p_email_sent">'.date('F d, Y', strtotime($email->email_sent)).'</p>';
            }
          }

          $rct_output.= '<tr>
            <td>'.date('M-Y', strtotime($key_date)).'</td>
            <td style="text-align:right">'.$grosssum.'</td>
            <td style="text-align:right">'.$deductionsum.'</td>
            <td style="text-align:right">'.$netsum.'</td>
            <td style="text-align:center">'.$icount.'</td>
            <td style="text-align:center">
              '.$actions.'
            </td>
            <td style="text-align:center">
            '.$emails_sent.'
            </td>
            
          </tr>';
          $keyival++;
        }
      }
    }
    echo $rct_output;
  }
  public function rct_extract_csv_liabilities()
  {
    $client_id = Input::get('client_id');
    $client_details = DB::table('cm_clients')->where('client_id',$client_id)->first();

    $rct_output = '';
    $rctsubmission = DB::table('rct_submission')->where('client_id', $client_id)->first();
    if(count($rctsubmission)){
      $start_date = unserialize($rctsubmission->start_date);
      $grossval = unserialize($rctsubmission->value_gross);
      $netval = unserialize($rctsubmission->value_net);
      $deductionval = unserialize($rctsubmission->deduction);

      $columns = array('', '', 'RCT LIABILITIES', '', '', '');
      $file = fopen('papers/rct liabilities for client '.$client_details->company.'-'.$client_details->client_id.'.csv', 'w');
      fputcsv($file, $columns);

      $columns2 = array('Month', 'Gross', 'Deduction', 'Net', 'Count', 'Email');
      fputcsv($file, $columns2);

      $data = array();

      $email_date = Db::table('rct_submission_email')->where('client_id',$client_details->client_id)->groupBy('start_date')->get();
      if(count($email_date))
      {
        foreach($email_date as $email)
        {
          $data[$email->start_date] = array();
        }
      }
      if(count($start_date))
      {
        foreach($start_date as $key => $start)
        {
          $date = substr($start,0,7);
          if(isset($data[$date]))
          {
            if(count($data[$date]))
            {
              $implodeval = implode(",",$data[$date]);                  
              $combineval = $implodeval.','.$key;
              $data[$date] = explode(',',$combineval);
            }
            else{
              $data[$date] = array($key);
            }

          }
          else{
            $data[$date] = array($key);
          }
        }
      }
      krsort($data);
      if(count($data))
      {
        $keyival = 1;
        foreach($data as $key_date => $dataval)
        {
          $grosssum = 0;
          $netsum = 0;
          $deductionsum = 0;
          $icount = 0;
          if(count($dataval))
          {
            foreach($dataval as $sumvalue)
            {
              if(isset($grossval[$sumvalue]))
              {
                $grosssum = $grosssum + $grossval[$sumvalue];
              }
              else{
                $grosssum = $grosssum + 0;
              }

              if(isset($netval[$sumvalue]))
              {
                $netsum = $netsum + $netval[$sumvalue];
              }
              else{
                $netsum = $netsum + 0;
              }

              if(isset($deductionval[$sumvalue]))
              {
                $deductionsum = $deductionsum + $deductionval[$sumvalue];
              }
              else{
                $deductionsum = $deductionsum + 0;
              }
              $icount++;
            }

            $grosssum = number_format_invoice_without_decimal($grosssum);
            $netsum = number_format_invoice_without_decimal($netsum);
            $deductionsum = number_format_invoice_without_decimal($deductionsum);
            $icount = $icount;
          }
          else{
            $grosssum = '-';
            $netsum = '-';
            $deductionsum = '-';
            $icount = '-';
          }

          $email_date = Db::table('rct_submission_email')->where('client_id',$client_details->client_id)->where('start_date',$key_date)->get();
          $emails_sent = '';
          if(count($email_date))
          {
            foreach($email_date as $email)
            {
              if($emails_sent == "")
              {
                $emails_sent = date('F d Y', strtotime($email->email_sent));
              }
              else{
                $emails_sent = $emails_sent.' , '.date('F d Y', strtotime($email->email_sent));
              }
            }
          }

          $columns3= array(date('M-Y', strtotime($key_date)), $grosssum, $deductionsum, $netsum, $icount, $emails_sent);
          fputcsv($file, $columns3);
        }
      }

      fclose($file);
    }
    echo 'rct liabilities for client '.$client_details->company.'-'.$client_details->client_id.'.csv';
  }
  public function rct_rebuild_all_liabilities()
  {
    $client_id = Input::get('client_id');
    $client_details = DB::table('cm_clients')->where('client_id',$client_id)->first();
    $rctsubmission = DB::table('rct_submission')->where('client_id',$client_id)->first();

    if(count($rctsubmission)){
      $start_date = unserialize($rctsubmission->start_date);
      $grossval = unserialize($rctsubmission->value_gross);
      $netval = unserialize($rctsubmission->value_net);
      $deductionval = unserialize($rctsubmission->deduction);


      $type = unserialize($rctsubmission->type);
      $rct_id = unserialize($rctsubmission->rct_id);
      $principal_name = unserialize($rctsubmission->principal_name);
      $sub_contractor = unserialize($rctsubmission->sub_contractor);
      $sub_contractor_id = unserialize($rctsubmission->sub_contractor_id);
      $site = unserialize($rctsubmission->site);
      $finish_date = unserialize($rctsubmission->finish_date);


      if(count($type))
      {
        foreach($type as $key => $value)
        {
          unset($start_date[$key]);
          unset($type[$key]);
          unset($rct_id[$key]);
          unset($principal_name[$key]);
          unset($sub_contractor[$key]);
          unset($sub_contractor_id[$key]);
          unset($site[$key]);
          unset($finish_date[$key]);

          unset($grossval[$key]);
          unset($netval[$key]);
          unset($deductionval[$key]);
        }
      }

      $data['start_date'] = serialize($start_date);
      $data['value_gross'] = serialize($grossval);
      $data['value_net'] = serialize($netval);
      $data['deduction'] = serialize($deductionval);


      $data['type'] = serialize($type);
      $data['rct_id'] = serialize($rct_id);
      $data['principal_name'] = serialize($principal_name);
      $data['sub_contractor'] = serialize($sub_contractor);
      $data['sub_contractor_id'] = serialize($sub_contractor_id);
      $data['site'] = serialize($site);
      $data['finish_date'] = serialize($finish_date);

      DB::table('rct_submission')->where('client_id',$client_id)->update($data);
    }
  }
  public function rct_rebuild_single_liabilities()
  {
    $client_id = Input::get('client_id');
    $date = Input::get('date');
    $client_details = DB::table('cm_clients')->where('client_id',$client_id)->first();
    $rctsubmission = DB::table('rct_submission')->where('client_id',$client_id)->first();

    if(count($rctsubmission)){
      $start_date = unserialize($rctsubmission->start_date);
      $grossval = unserialize($rctsubmission->value_gross);
      $netval = unserialize($rctsubmission->value_net);
      $deductionval = unserialize($rctsubmission->deduction);
      $type = unserialize($rctsubmission->type);
      $rct_id = unserialize($rctsubmission->rct_id);
      $principal_name = unserialize($rctsubmission->principal_name);
      $sub_contractor = unserialize($rctsubmission->sub_contractor);
      $sub_contractor_id = unserialize($rctsubmission->sub_contractor_id);
      $site = unserialize($rctsubmission->site);
      $finish_date = unserialize($rctsubmission->finish_date);


      if(count($type))
      {
        foreach($type as $key => $value)
        {
          $get_date = date('Y-m', strtotime($start_date[$key]));
          if($get_date == $date)
          {
            unset($start_date[$key]);
            unset($type[$key]);
            unset($rct_id[$key]);
            unset($principal_name[$key]);
            unset($sub_contractor[$key]);
            unset($sub_contractor_id[$key]);
            unset($site[$key]);
            unset($finish_date[$key]);

            unset($grossval[$key]);
            unset($netval[$key]);
            unset($deductionval[$key]);
          }
        }
      }

      $data['start_date'] = serialize($start_date);
      $data['value_gross'] = serialize($grossval);
      $data['value_net'] = serialize($netval);
      $data['deduction'] = serialize($deductionval);


      $data['type'] = serialize($type);
      $data['rct_id'] = serialize($rct_id);
      $data['principal_name'] = serialize($principal_name);
      $data['sub_contractor'] = serialize($sub_contractor);
      $data['sub_contractor_id'] = serialize($sub_contractor_id);
      $data['site'] = serialize($site);
      $data['finish_date'] = serialize($finish_date);

      DB::table('rct_submission')->where('client_id',$client_id)->update($data);
    }
  }
  public function get_ckeditor_content_single()
  {
    $client_id = Input::get('client_id');
    $get_salutation = DB::table('cm_clients')->where('client_id',$client_id)->first();
    $key = Input::get('key');

    $rct_submission = DB::table('rct_submission')->where('client_id',$client_id)->first();
    if(count($rct_submission))
    {
      $type = unserialize($rct_submission->type);
      $rct_id = unserialize($rct_submission->rct_id);
      $principal_name = unserialize($rct_submission->principal_name);
      $sub_contractor = unserialize($rct_submission->sub_contractor);
      $sub_contractor_id = unserialize($rct_submission->sub_contractor_id);
      $site = unserialize($rct_submission->site);
      $start_date = unserialize($rct_submission->start_date);
      $finish_date = unserialize($rct_submission->finish_date);
      $value_gross = unserialize($rct_submission->value_gross);
      $value_net = unserialize($rct_submission->value_net);
      $deduction = unserialize($rct_submission->deduction);

      if(isset($type[$key])) { $typeval =$type[$key]; } else { $typeval = ''; }
      if(isset($rct_id[$key])) { $rct_idval =$rct_id[$key]; } else { $rct_idval = ''; }
      if(isset($principal_name[$key])) { $principal_nameval =$principal_name[$key]; } else { $principal_nameval = ''; }
      if(isset($sub_contractor[$key])) { $sub_contractorval =$sub_contractor[$key]; } else { $sub_contractorval = ''; }
      if(isset($sub_contractor_id[$key])) { $sub_contractor_idval =$sub_contractor_id[$key]; } else { $sub_contractor_idval = ''; }
      if(isset($site[$key])) { $siteval =$site[$key]; } else { $siteval = ''; }
      if(isset($start_date[$key])) { $start_dateval =$start_date[$key]; } else { $start_dateval = ''; }
      if(isset($finish_date[$key])) { $finish_dateval =$finish_date[$key]; } else { $finish_dateval = ''; }
      if(isset($value_gross[$key])) { $value_grossval =$value_gross[$key]; } else { $value_grossval = ''; }
      if(isset($value_net[$key])) { $value_netval =$value_net[$key]; } else { $value_netval = ''; }
      if(isset($deduction[$key])) { $deductionval =$deduction[$key]; } else { $deductionval = ''; }

      if($typeval == "1")
      {
        $html = '<p>'.$get_salutation->salutation.', </p>
        <p>We have attached the RCT Contract Notification for '.$sub_contractorval.' - '.$sub_contractor_idval.' </p>
        <p>ROS CONTRACT ID : '.$rct_idval.'</p>
        <p>Site : '.$siteval.'</p>
        <p>START DATE : '.$start_dateval.'</p>
        <p>FINISH DATE : '.$finish_dateval.'</p>
        <p>VALUE : '.$value_grossval.'</p>';
      }
      else{
        $html = '<p>'.$get_salutation->salutation.', </p>
        <p>We have attached the RCT Payment Submission for '.$sub_contractorval.' - '.$sub_contractor_idval.' </p>
        <p>ROS PAYMENT ID : '.$rct_idval.'</p>
        <p>The GROSS payment of : '.$value_grossval.'</p>
        <p>Less Deductions of : '.$deductionval.'</p>
        <p>PAYMENT TO SUBCONTRACTOR : '.$value_netval.'</p>';
      }
      echo $html;
    }
  }
  public function upload_rct_html_form()
  {
    $client_id = Input::get('hidden_upload_client_id');

    $upload_dir = 'uploads/rct_html_files';
    if(!file_exists($upload_dir))
    {
      mkdir($upload_dir);
    }

    $tmp_name = $_FILES['file']['tmp_name'];
     $name = str_replace("#","",$_FILES['file']['name']);
     $name = str_replace("#","",$name);
     $name = str_replace("#","",$name);
     $name = str_replace("#","",$name);

     $name = str_replace("%","",$name);
     $name = str_replace("%","",$name);
     $name = str_replace("%","",$name);
     $filename = $upload_dir.'/'.$name;
     $error_content = '';
    if(move_uploaded_file($tmp_name,$filename))
    {
       $contents = file_get_contents($filename);

        // ----- remove HTML TAGs ----- 
        $string = preg_replace ('/<[^>]*>/', ' ', $contents); 
        // ----- remove control characters ----- 
        $string = str_replace("\r", '', $string);
        $string = str_replace("\n", ' ', $string);
        $string = str_replace("\t", ' ', $string);

        $string = str_replace("$(document).ready(function() { addAutocompleteAttr(); });","",$string);

        // ----- remove multiple spaces ----- 
        $string = trim(preg_replace('/\s+/', ' ',$string));

        $string = preg_replace("/\s|&nbsp;/",' ',$string);  
        $check_type = explode(" ",$string);
        
        if($check_type[0] == "Contract")
        {
          $type = 1;
          $explode_values = explode("Sub Tax Reference Number",$string);
          $site_values = explode("Site Identifier Number",$string);
          $start_date_values = explode("Start Date of Work",$string);
          $finish_date_values = explode("End Date of Work",$string);
          $gross_values = explode("Estimated Value Of Contract",$string);

          $explode = explode(" ",$explode_values[1]);
          $site_explode = explode(" ",$site_values[1]);
          $start_date_explode = explode(" ",$start_date_values[1]);
          $finish_date_explode = explode(" ",$finish_date_values[1]);
          $gross_explode = explode(" ",$gross_values[1]); 

         for($k=0; $k<=10; $k++)
         {
          if($explode[$k] == "")
          {

          }
          else{
            $rct_id = $explode[$k];
            break;
          }
         }

          $sub_name = $explode[$k+2];
          for($i=$k+3; $i<=20; $i++)
          {
            if(preg_match('/\\d/', $explode[$i]) > 0 && strlen($explode[$i]) == 9)
            {
              $sub_name_id = $explode[$i];
              break;
            }
            else{
              $sub_name = $sub_name.' '.$explode[$i];
            }
          }

          for($j=0; $j<=10; $j++)
         {
          if($site_explode[$j] == "")
          {

          }
          else{
            $site = $site_explode[$j];
            break;
          }
         }

         for($l=0; $l<=10; $l++)
         {
          if($start_date_explode[$l] == "")
          {

          }
          else{
            $start_date = $start_date_explode[$l];
            break;
          }
         }

         for($m=0; $m<=10; $m++)
         {
          if($finish_date_explode[$m] == "")
          {

          }
          else{
            $finish_date = $finish_date_explode[$m];
            break;
          }
         }
         for($n=0; $n<=10; $n++)
         {
          if($gross_explode[$n] == "")
          {

          }
          else{
            $gross = $gross_explode[$n];
            break;
          }
         }

         $net = '';
         $deduction = '';

         $start_date = explode("/",$start_date);
         $start_date_val = $start_date[2].'-'.$start_date[1].'-'.$start_date[0];

         $finish_date = explode("/",$finish_date);
         $finish_date_val = $finish_date[2].'-'.$finish_date[1].'-'.$finish_date[0];

          $submission_count = DB::table('rct_submission')->where('client_id', $client_id)->first();
          $serializeid = $rct_id.$type;

          if(count($submission_count)){
            $unserialize_rctid = unserialize($submission_count->rct_id);
            if(count($unserialize_rctid))
            {
              foreach($unserialize_rctid as $un_rct_id)
              {
                if($rct_id == $un_rct_id)
                {
                  $error_content = 'This RCT Contract ID has already been added and Can not be added again';
                  //return redirect::back()->with('message', "");
                }
              }
            }
            $site = $site;

            $unserialize_type = unserialize($submission_count->type);
            $unserialize_type[$serializeid] = $type;

            
            $unserialize_rctid[$serializeid] = $rct_id;

            $unserialize_principal = unserialize($submission_count->principal_name);
            $unserialize_principal[$serializeid] = "0";

            $unserialize_start_date = unserialize($submission_count->start_date);
            $unserialize_start_date[$serializeid] = $start_date_val;

            $unserialize_value_gross = unserialize($submission_count->value_gross);
            $unserialize_value_gross[$serializeid] = $gross;


            $unserialize_site = unserialize($submission_count->site);
            $unserialize_site[$serializeid] = $site;

            $unserialize_finish_date = unserialize($submission_count->finish_date);
            $unserialize_finish_date[$serializeid] = $finish_date_val;




            $data['type'] = serialize($unserialize_type);
            $data['rct_id'] = serialize($unserialize_rctid);
            $data['principal_name'] = serialize($unserialize_principal);
            $data['start_date'] = serialize($unserialize_start_date);
            $data['value_gross'] = serialize($unserialize_value_gross);

            $data['site'] = serialize($unserialize_site);
            $data['finish_date'] = serialize($unserialize_finish_date);        

            DB::table('rct_submission')->where('client_id', $client_id)->update($data);
          }
          else{
            $type_serialize[$serializeid] = $type;
            $rctid_serialize[$serializeid] = $rct_id;
            $principal_serialize[$serializeid] = "0";
            $start_date_serialize[$serializeid] = $start_date_val;
            $value_gross_serialize[$serializeid] = $gross;

            $site_serialize[$serializeid] = $site;        
            $finish_date_serialize[$serializeid] = $finish_date_val;        

            $data['client_id'] = $client_id;
            $data['type'] = serialize($type_serialize);
            $data['rct_id'] = serialize($rctid_serialize);
            $data['principal_name'] = serialize($principal_serialize);
            $data['start_date'] = serialize($start_date_serialize);
            $data['value_gross'] = serialize($value_gross_serialize);

            $data['site'] = serialize($site_serialize);        
            $data['finish_date'] = serialize($finish_date_serialize);        
            DB::table('rct_submission')->insert($data);
          }
        }
        elseif($check_type[0] == "Payment"){
          $type = 2;
          $explode_values = explode("Deduction Amount",$string);
          $explode = explode(" ",$explode_values[1]);
          
          $rct_id = $explode[3];
          for($k=0; $k<=10; $k++)
         {
          if($explode[$k] == "")
          {

          }
          else{
            $rct_id = $explode[$k];
            break;
          }
         }

          $sub_name_id = $explode[$k+1];
          $sub_name = $explode[$k+2];

          for($i=6; $i<=20; $i++)
          {
            if(preg_match('/\\d/', $explode[$i]) > 0 && strpos($explode[$i],'/') && strlen($explode[$i]) == 10)
            {
              $start_date = $explode[$i];
              break;
            }
            else{
              $sub_name = $sub_name.' '.$explode[$i];
            }
          }
          $site = '';
          $finish_date = '';
          $gross = $explode[$i + 1];
          $net = $explode[$i + 2];
          $deduction = $explode[$i + 3];

          $start_date = explode("/",$start_date);
          $start_date_val = $start_date[2].'-'.$start_date[1].'-'.$start_date[0];

          $submission_count = DB::table('rct_submission')->where('client_id', $client_id)->first();
          $serializeid = $rct_id.$type;

          if(count($submission_count)){
            $sub_contractor_name = $sub_name;
            $sub_contractor_id = $sub_name_id;
            $value_net = $net;
            $deduction = $deduction;

            $unserialize_rctid = unserialize($submission_count->rct_id);
            
            if(count($unserialize_rctid))
            {
              foreach($unserialize_rctid as $un_rct_id)
              {
                if($rct_id == $un_rct_id)
                {
                  $error_content = 'This RCT Payment ID has already been added and Can not be added again';
                  //return redirect::back()->with('message', "This RCT Payment ID has already been added and Can not be added again");
                }
              }
            }

            $unserialize_type = unserialize($submission_count->type);
            $unserialize_type[$serializeid] = $type;

            $unserialize_rctid[$serializeid] = $rct_id;

            $unserialize_principal = unserialize($submission_count->principal_name);
            $unserialize_principal[$serializeid] = "0";

            $unserialize_start_date = unserialize($submission_count->start_date);
            $unserialize_start_date[$serializeid] = $start_date_val;

            $unserialize_value_gross = unserialize($submission_count->value_gross);
            $unserialize_value_gross[$serializeid] = $gross;



            $unserialize_sub_contractor_name = unserialize($submission_count->sub_contractor);
            $unserialize_sub_contractor_name[$serializeid] = $sub_contractor_name;

            $unserialize_sub_contractor_id = unserialize($submission_count->sub_contractor_id);
            $unserialize_sub_contractor_id[$serializeid] = $sub_contractor_id;

            $unserialize_value_net = unserialize($submission_count->value_net);
            $unserialize_value_net[$serializeid] = $net;

            $unserialize_deduction = unserialize($submission_count->deduction);
            $unserialize_deduction[$serializeid] = $deduction;


            $data['type'] = serialize($unserialize_type);
            $data['rct_id'] = serialize($unserialize_rctid);
            $data['principal_name'] = serialize($unserialize_principal);
            $data['start_date'] = serialize($unserialize_start_date);
            $data['value_gross'] = serialize($unserialize_value_gross);

            $data['sub_contractor'] = serialize($unserialize_sub_contractor_name);
            $data['sub_contractor_id'] = serialize($unserialize_sub_contractor_id);
            $data['value_net'] = serialize($unserialize_value_net);
            $data['deduction'] = serialize($unserialize_deduction);
            

            DB::table('rct_submission')->where('client_id', $client_id)->update($data);
          }
          else{
            $sub_contractor_name = $sub_name;
            $sub_contractor_id = $sub_name_id;
            $value_net = $net;
            $deduction = $deduction;

            $type_serialize[$serializeid] = $type;
            $rctid_serialize[$serializeid] = $rct_id;
            $principal_serialize[$serializeid] = "0";
            $start_date_serialize[$serializeid] = $start_date_val;
            $value_gross_serialize[$serializeid] = $gross;

            $sub_contractor_name_serialize[$serializeid] = $sub_contractor_name;
            $sub_contractor_id_serialize[$serializeid] = $sub_contractor_id;
            $value_net_serialize[$serializeid] = $net;
            $deduction_serialize[$serializeid] = $deduction;


            $data['client_id'] = $client_id;
            $data['type'] = serialize($type_serialize);
            $data['rct_id'] = serialize($rctid_serialize);
            $data['principal_name'] = serialize($principal_serialize);
            $data['start_date'] = serialize($start_date_serialize);
            $data['value_gross'] = serialize($value_gross_serialize);

            $data['sub_contractor'] = serialize($sub_contractor_name_serialize);
            $data['sub_contractor_id'] = serialize($sub_contractor_id_serialize);
            $data['value_net'] = serialize($value_net_serialize);
            $data['deduction'] = serialize($deduction_serialize);


            DB::table('rct_submission')->insert($data);
          }
        }
        else{
          $error_content = 'Invalid File';
        }
    }

    echo json_encode(array('id' => 0, 'filename' => $name,'error_content' => $error_content));
  }
  public function delete_tax_number()
  {
    $key = Input::get('key');
    $client_id = Input::get('client_id');

    $get_tax_numbers = DB::table('rct_tax_number')->where('tax_client_id',$client_id)->first();
    if(count($get_tax_numbers))
    {
      $name = explode(",",$get_tax_numbers->tax_name);
      $numbers = explode(",",$get_tax_numbers->tax_number);

      $name[$key] = '';
      $numbers[$key] = '';

      $nameimplode = implode(",",$name);
      $numberimplode = implode(",",$numbers);

      $data['tax_name'] = $nameimplode;
      $data['tax_number'] = $numberimplode;

      DB::table('rct_tax_number')->where('tax_client_id',$client_id)->update($data);

      return redirect::back()->with('message', "Tax Number Deleted Successfully");
    }
  }
}
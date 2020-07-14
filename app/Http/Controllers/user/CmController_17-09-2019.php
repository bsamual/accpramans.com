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
class CmController extends Controller {

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
	public function clientmanagement()
	{
		$client = DB::table('cm_clients')->select('client_id', 'firstname', 'surname', 'company', 'email', 'status', 'active', 'id','address1','address2','address3','address4','address5','phone','statement')->orderBy('id','asc')->get();
		
		$class = DB::table('cm_class')->where('status', 0)->get();
		return view('user/cm/clients', array('title' => 'Client Management', 'clientlist' => $client, 'classlist' => $class));
	}
	
	public function addcmclients(){
		$pin = Input::get('crypt_pin');
		$admin_details = DB::table('admin')->first();
		if(Hash::check($pin,$admin_details->cm_crypt))
		{
			$check_clients = DB::table('cm_clients')->orderBy('id','desc')->first();
			$last_client_id = substr($check_clients->client_id, 3);
			$nextClientid = sprintf("%03d", $last_client_id + 1);
			$clientid = 'GBS'.$nextClientid;

			$data['client_id'] = 'GBS'.$nextClientid;
			$data['firstname'] = Input::get('name');
			$data['surname'] = Input::get('surname');

			$firstname = Input::get('name');
			$surname = Input::get('surname');

			$company = Input::get('cname');
			if($company != "")
			{
				$data['company'] = $company;
			}
			else
			{
				if($surname != ""){
					$surname = ' '.$surname;
				}
				$data['company'] = $firstname.$surname;
			}
			$data['address1'] = Input::get('address1');
			$data['address2'] = Input::get('address2');
			$data['address3'] = Input::get('address3');
			$data['address4'] = Input::get('address4');
			$data['address5'] = Input::get('address5');
			$data['email'] = Input::get('email');
			$data['tye'] = Input::get('tye');
			$data['active'] = (Input::get('class') == "")?'1':Input::get('class');
			$data['tax_reg1'] = Input::get('tax_reg1');
			$data['tax_reg2'] = Input::get('tax_reg2');
			$data['tax_reg3'] = Input::get('tax_reg3');
			$data['email2'] = Input::get('semail');
			$data['phone'] = Input::get('phone');
			$data['linkcode'] = Input::get('linkcode');
			$data['cro'] = Input::get('cro');
			$data['trade_status'] = Input::get('trade_status');
			$data['directory'] = Input::get('directory');

			


			$check_fields = DB::table('cm_fields')->where('status',0)->get();
			if(count($check_fields))
			{
				foreach($check_fields as $fields)
				{
					if($fields->field == 4)
					{
						$filename = $_FILES[$fields->name]['name'];
						$tmp_name = $_FILES[$fields->name]['tmp_name'];
						$uploads_dir = 'uploads/cm_fields';
						if(!file_exists($uploads_dir))
						{
							mkdir($uploads_dir);
						}
						$uploads_dir = $uploads_dir.'/'.$clientid;
						if(!file_exists($uploads_dir))
						{
							mkdir($uploads_dir);
						}
						$uploads_dir = $uploads_dir.'/'.$fields->name;
						if(!file_exists($uploads_dir))
						{
							mkdir($uploads_dir);
						}
						move_uploaded_file($tmp_name, $uploads_dir.'/'.$filename);
						$data[$fields->name] = $filename;
					}
					else{
						$data[$fields->name] = Input::get($fields->name);
					}
				}
				DB::table('cm_clients')->insert($data);
			}
			else{
				DB::table('cm_clients')->insert($data);
			}
			return redirect::back()->with('message','Add Success');
		}
		else{
			return redirect::back()->with('message','Crypt Pin You have entered is Incorrect.');
		}
	}
	public function editcmclient($id=""){
		$id = base64_decode($id);
		$result = DB::table('cm_clients')->where('id', $id)->first();
		$output = '';
            $getfields = DB::table('cm_fields')->where('status',0)->get();
            if(count($getfields))
            {
            	$i = 1;
            	$output.='<tr>';
              foreach($getfields as $field)
              {
              	if($i % 4 == 0) { $output.='</tr><tr>';}
              	$output.='<td> 
              		<label>'.$field->name.' : </label>
                    <div class="form-group">';
                if($field->field == 1)
                {
                	$fieldval = $field->name;
                    $output.='<input type="text" name="'.$field->name.'" class="form-control '.$field->name.'_add" placeholder="Enter '.$field->name.'" value="'.$result->$fieldval.'">';
                }
                elseif($field->field == 2)
                {
                	$fieldval = $field->name;
                        $output.='<input type="radio" name="'.$field->name.'" class="'.$field->name.'_add" id="'.$field->name.'_yes" value="yes" '; if($result->$fieldval == "yes") { $output.='checked'; } $output.='><label for="'.$field->name.'_yes"> YES </label>
                        <input type="radio" name="'.$field->name.'" class="'.$field->name.'_add" id="'.$field->name.'_no" value="no" '; if($result->$fieldval == "no") { $output.='checked'; } $output.='><label for="'.$field->name.'_no"> NO </label>';
                }
                elseif($field->field == 3)
                {
                	$fieldval = $field->name;
                    $output.='<textarea name="'.$field->name.'" class="form-control '.$field->name.'_add" placeholder="Enter '.$field->name.'">'.$result->$fieldval.'</textarea>';
                }
                elseif($field->field == 4)
                {
                	$fieldval = $field->name;
                    $output.='<input type="file" name="'.$field->name.'" class="form-control '.$field->name.'_add" placeholder="Enter '.$field->name.'">';
                    if($result->$fieldval != "") {
                    	$output.='<a href="javascript:" class="fileattachment" data-element="'.URL::to('uploads/cm_fields/'.$result->client_id.'/'.$fieldval.'/'.$result->$fieldval.'').'">'.$result->$fieldval.'</a>';
                	}
                }
                elseif($field->field == 5)
                {
                	$fieldval = $field->name;
                    $output.='<input type="email" name="'.$field->name.'" class="form-control '.$field->name.'_add" placeholder="Enter '.$field->name.'" value="'.$result->$fieldval.'">';
                }
                elseif($field->field == 6)
                {
                	$fieldval = $field->name;
                  $unserialize = unserialize($field->options);
                    $output.='<select name="'.$field->name.'" class="form-control '.$field->name.'_add">
                          <option value="">Select '.$field->name.'</option>';
                            if(count($unserialize))
                            {
                              foreach($unserialize as $key => $arrayval)
                              {
                                $output.='<option value="'.$arrayval.'" '; if($result->$fieldval == $arrayval) { $output.='selected'; } else { $output.=''; } $output.='>'.$key.'</option>';
                              }
                            }
                        $output.='</select>';
                }
                $output.='</div></td>';
                $i++; 
              }
              $output.='</tr>';
            }
		echo json_encode(array('clientid' => $result->client_id, 'firstname' => $result->firstname, 'surname' => $result->surname, 'company' => $result->company, 'address1' => $result->address1, 'address2' => $result->address2, 'address3' => $result->address3, 'address4' => $result->address4, 'address5' => $result->address5, 'email' => $result->email, 'tye' => $result->tye, 'active' => $result->active, 'tax_reg1' => $result->tax_reg1, 'tax_reg2' => $result->tax_reg2, 'tax_reg3' => $result->tax_reg3, 'email2' => $result->email2, 'phone' => $result->phone, 'linkcode' => $result->linkcode, 'cro' => $result->cro, 'trade_status' => $result->trade_status, 'directory' => $result->directory, 'status' => $result->status,   'id' => $result->id,'htmlcontent' => $output,'employer_no' => $result->employer_no,'salutation' => $result->salutation));
	}
	public function copycmclient($id=""){
		$id = base64_decode($id);
		$result = DB::table('cm_clients')->where('id', $id)->first();

		$check_clients = DB::table('cm_clients')->orderBy('id','desc')->first();
		$last_client_id = substr($check_clients->client_id, 3);
		$nextClientid = sprintf("%03d", $last_client_id + 1);
		$clientid = 'GBS'.$nextClientid;

		$fields = DB::table('cm_fields')->where('status',0)->get();
		$data2 = array();
		if(count($fields))
		{
			foreach($fields as $fld)
			{
				$fieldname = $fld->name;
				$data2[$fieldname] = $result->$fieldname;
			}
		}
		$data1 = array('clientid' => $clientid, 'firstname' => $result->firstname, 'surname' => $result->surname, 'company' => $result->company, 'address1' => $result->address1, 'address2' => $result->address2, 'address3' => $result->address3, 'address4' => $result->address4, 'address5' => $result->address5, 'email' => $result->email, 'tye' => $result->tye, 'active' => $result->active, 'tax_reg1' => $result->tax_reg1, 'tax_reg2' => $result->tax_reg2, 'tax_reg3' => $result->tax_reg3, 'email2' => $result->email2, 'phone' => $result->phone, 'linkcode' => $result->linkcode, 'cro' => $result->cro, 'trade_status' => $result->trade_status, 'directory' => $result->directory,    'id' => $result->id);

		$data3 = array_merge($data1,$data2);

		echo json_encode($data3);
	}
	
	public function updatecmclients(){
		$pin = Input::get('crypt_pin');
		$admin_details = DB::table('admin')->first();
		$id = Input::get('id');
		if(Hash::check($pin,$admin_details->cm_crypt))
		{
			$data['firstname'] = Input::get('name');
			$data['surname'] = Input::get('surname');
			$data['company'] = Input::get('cname');
			$data['address1'] = Input::get('address1');
			$data['address2'] = Input::get('address2');
			$data['address3'] = Input::get('address3');
			$data['address4'] = Input::get('address4');
			$data['address5'] = Input::get('address5');
			$data['email'] = Input::get('email');
			$data['tye'] = Input::get('tye');
			$data['active'] = (Input::get('class') == "")?'1':Input::get('class');
			$data['tax_reg1'] = Input::get('tax_reg1');
			$data['tax_reg2'] = Input::get('tax_reg2');
			$data['tax_reg3'] = Input::get('tax_reg3');

			

			$data['email2'] = Input::get('semail');
			$data['phone'] = Input::get('phone');
			$data['linkcode'] = Input::get('linkcode');
			$data['cro'] = Input::get('cro');
			$data['trade_status'] = Input::get('trade_status');
			$data['directory'] = Input::get('directory');
			$check_client = DB::table('cm_clients')->where('id', $id)->first();
			$check_fields = DB::table('cm_fields')->where('status',0)->get();
			if(count($check_fields))
			{
				foreach($check_fields as $fields)
				{
					if($fields->field == 4)
					{
						$filename = $_FILES[$fields->name]['name'];
						$tmp_name = $_FILES[$fields->name]['tmp_name'];
						$uploads_dir = 'uploads/cm_fields';
						if(!file_exists($uploads_dir))
						{
							mkdir($uploads_dir);
						}
						$uploads_dir = $uploads_dir.'/'.$check_client->client_id;
						if(!file_exists($uploads_dir))
						{
							mkdir($uploads_dir);
						}
						$uploads_dir = $uploads_dir.'/'.$fields->name;
						if(!file_exists($uploads_dir))
						{
							mkdir($uploads_dir);
						}
						move_uploaded_file($tmp_name, $uploads_dir.'/'.$filename);
						$data[$fields->name] = $filename;
					}
					else{
						$data[$fields->name] = Input::get($fields->name);
					}
				}
				DB::table('cm_clients')->where('id', $id)->update($data);
			}
			else{
				DB::table('cm_clients')->where('id', $id)->update($data);
			}
			
			return redirect('user/client_management?divid=clientidtr_'.$id)->with('edit_message','Client Updated Successfully.');
		}
		else{
			return redirect('user/client_management?divid=clientidtr_'.$id)->with('edit_error','Crypt Pin You have entered is Incorrect.');
		}
	}
	public function clientmanagement_paginate()
	{
		$page = Input::get('page');
		$prev_page = $page - 1;
		$offset = $prev_page * 1000;

		$clientlist = DB::table('cm_clients')->select('client_id', 'firstname', 'surname', 'company', 'email', 'status', 'active', 'id','address1','address2','address3','address4','address5','phone','statement')->orderBy('id','asc')->offset($offset)->limit(1000)->get();

		$output ='';
		$i = $offset+1;
		if(count($clientlist)){   
          foreach($clientlist as $key => $client){
            $address = $client->address1.' '.$client->address2.' '.$client->address3.' '.$client->address4.' '.$client->address5;
              if($client->status == 1) { $disabled='disabled'; $style="color:red"; } 
              else{ 
                $disabled='';
                if($client->active != "")
                {
                  $check_color = DB::table('cm_class')->where('id',$client->active)->first();
                  $style="color:#".$check_color->classcolor."";
                }
                else{
                  $style="color:#000";
                }
              }
              if($key == 999) { $class_load = 'class="load_more"'; } else { $class_load = ''; }
	            $output.='<tr class="edit_task '.$disabled.'">
	                <td style="'.$style.'">'.$i.'</td>
	                <td align="left"><a href="javascript:" id="'.base64_encode($client->id).'" class="invoice_class" style="'.$style.'">'.$client->client_id.'</a></td>
	                <td align="left" '.$class_load.'><a href="javascript:" id="'.base64_encode($client->id).'" class="invoice_class" style="'.$style.'">'.$client->firstname.'</a></td>
	                <td align="left"><a href="javascript:" id="'.base64_encode($client->id).'" class="invoice_class" style="'.$style.'">'.$client->surname.'</a></td>
	                <td align="left"><a href="javascript:" id="'.base64_encode($client->id).'" class="invoice_class" style="'.$style.'">'; $output.=($client->company == "")?$client->firstname.' '.$client->surname:$client->company; $output.='</a></td>
	                <td style="word-wrap: break-word; white-space:normal; min-width:300px; max-width: 300px;" align="left"><a href="javascript:" id="'.base64_encode($client->id).'" class="invoice_class" style="'.$style.'">'.$address.'</a></td>
	                <td align="left"><a style="'.$style.'" href="mailto:'.$client->email.'">'.$client->email.'</a></td>
	                <td align="left"><a href="javascript:" id="'.base64_encode($client->id).'" class="invoice_class" style="'.$style.'">'.$client->phone.'</a></td>

	                <td style="'.$style.'" align="left">
	                	<input type="checkbox" class="client_statement" id="statement_'.$client->client_id.'" data-element="'.$client->id.'"'; $output.=($client->statement == "yes")?'checked':''; $output.='><label for="statement_'.$client->client_id.'">Yes</label>
	                </td>
                </tr>';
                $i++;
            }              
        }
        if($i == 1)
        {
          $output.='<tr><td colspan="9" align="center">Empty</td></tr>';
        }
		
		echo $output;
	}
	public function cm_search_clients()
	{
		$input = Input::get('input');
		$select = Input::get('select');
		$incomplete_details = DB::table('user_login')->first();

		$output = '';
		if($select == "address1")
		{
			$clientlist = DB::table('cm_clients')->where('address1','like','%'.$input.'%')->orWhere('address2','like','%'.$input.'%')->orWhere('address3','like','%'.$input.'%')->orWhere('address4','like','%'.$input.'%')->orWhere('address5','like','%'.$input.'%')->get();
		}
		else{
			$clientlist = DB::table('cm_clients')->where($select,'like','%'.$input.'%')->get();
		}

		$i= 1;
        if(count($clientlist)){              
          foreach($clientlist as $client){
            $address = $client->address1.' '.$client->address2.' '.$client->address3.' '.$client->address4.' '.$client->address5;
              if($client->status == 1) { $disabled='disabled'; $style="color:red"; } 
              else{ 
                $disabled='';
                if($client->active != "")
                {
                  $check_color = DB::table('cm_class')->where('id',$client->active)->first();
                  $style="color:#".$check_color->classcolor."";
                }
                else{
                  $style="color:#000";
                }
              } 
	            $output.='<tr class="edit_task '.$disabled.'">
	                <td style="'.$style.'">'.$i.'</td>
	                <td align="left"><a href="javascript:" id="'.base64_encode($client->id).'" class="invoice_class" style="'.$style.'">'.$client->client_id.'</a></td>
	                <td align="left"><a href="javascript:" id="'.base64_encode($client->id).'" class="invoice_class" style="'.$style.'">'.$client->firstname.'</a></td>
	                <td align="left"><a href="javascript:" id="'.base64_encode($client->id).'" class="invoice_class" style="'.$style.'">'.$client->surname.'</a></td>
	                <td align="left"><a href="javascript:" id="'.base64_encode($client->id).'" class="invoice_class" style="'.$style.'">'; $output.=($client->company == "")?$client->firstname.' '.$client->surname:$client->company; $output.='</a></td>
	                <td style="word-wrap: break-word; white-space:normal; min-width:300px; max-width: 300px;" align="left"><a href="javascript:" id="'.base64_encode($client->id).'" class="invoice_class" style="'.$style.'">'.$address.'</a></td>
	                <td align="left"><a style="'.$style.'" href="mailto:'.$client->email.'">'.$client->email.'</a></td>
	                <td align="left"><a href="javascript:" id="'.base64_encode($client->id).'" class="invoice_class" style="'.$style.'">'.$client->phone.'</a></td>

	                <td style="'.$style.'" align="left">
	                	<input type="checkbox" class="client_statement" id="statement_'.$client->client_id.'" data-element="'.$client->id.'"'; $output.=($client->statement == "yes")?'checked':''; $output.='><label for="statement_'.$client->client_id.'">Yes</label>
	                </td>
                </tr>';
                $i++;
            }              
        }
        if($i == 1)
        {
          $output.='<tr><td colspan="9" align="center">Empty</td></tr>';
        }
		
		echo $output;
	}
	public function update_cm_incomplete_status()
	{
		$data['cm_incomplete'] = Input::get('value');
		DB::table('user_login')->where('userid',1)->update($data);
	}
	public function cm_report_pdf()
	{
		$ids = explode(",",Input::get('value'));
		$type = Input::get('type');
		$clients = DB::table('cm_clients')->whereIn('id', $ids)->get();
		
		$output = '';

		if($type == 2)
		{
			$output.= '<style>
					.table_style {
					    width: 100%;
					    border-collapse:collapse;
					    border:1px solid #c5c5c5;
					}
					</style>
			<table class="table_style">
				<tr>
				<td style="text-align: left;border:1px solid #000;">Client ID</td>
				<td style="text-align: left;border:1px solid #000;">FIrstName</td>
				<td style="text-align: left;border:1px solid #000;">Surname</td>
				<td style="text-align: left;border:1px solid #000;">Company</td>
				<td style="text-align: left;border:1px solid #000;">Address</td>
				<td style="text-align: left;border:1px solid #000;">EMail ID</td>
				</tr>';
				if(count($clients))
				{
					foreach($clients as $key => $client)
					{
						$output.='<tr>
							<td style="text-align: left;border:1px solid #000;">'.$client->client_id.'</td>
							<td style="text-align: left;border:1px solid #000;">'.$client->firstname.'</td>
							<td style="text-align: left;border:1px solid #000;">'.$client->surname.'</td>
							<td style="text-align: left;border:1px solid #000;">'; $output.=($client->company == "")?$client->firstname.' '.$client->surname:$client->company; $output.='</td>
							<td style="text-align: left;border:1px solid #000;">'.$client->address1.' '.$client->address2.' '.$client->address3.' '.$client->address4.' '.$client->address5.'</td>
							<td style="text-align: left;border:1px solid #000;">'.$client->email.'</td>
						</tr>';
					}
				}
			$output.='</table>';
			$end_id = count($ids) - 1;
			$first_client = DB::table('cm_clients')->where('id',$ids[0])->first();
			$end_client = DB::table('cm_clients')->where('id',$ids[$end_id])->first();
			if(count($end_client))
			{
				$name = 'CM Report of '.$first_client->client_id.' - '.$end_client->client_id.'.pdf';
			}
			else{
				$name = 'CM Report of '.$first_client->client_id.'.pdf';
			}
		}
		if($type == 1)
		{
			$output.= '<style>
					.table_style {
					    width: 100%;
					    border-collapse:collapse;
					    padding:6%;
					    border:1px solid #c5c5c5;
					}
					.table_style td{
						padding:8px;
					}
					</style>';
			if(count($clients))
			{
				foreach($clients as $key => $client)
				{
					$class_details = DB::table('cm_class')->where('id',$client->active)->first();
					if(count($clients) == ($key+1)) { $style=""; } else { $style= "page-break-after: always"; }
					$output.='<table class="table_style" style="'.$style.'"><tr>
						<td style="text-align: left;border:1px solid #000;">Client ID</td>
						<td style="text-align: left;border:1px solid #000;">'.$client->client_id.'</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">First Name</td>
						<td style="text-align: left;border:1px solid #000;">'.$client->firstname.'</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Surname</td>
						<td style="text-align: left;border:1px solid #000;">'.$client->surname.'</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Company</td>
						<td style="text-align: left;border:1px solid #000;">'.$client->company.'</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Address 1</td>
						<td style="text-align: left;border:1px solid #000;">'.$client->address1.'</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Address 2</td>
						<td style="text-align: left;border:1px solid #000;">'.$client->address2.'</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Address 3</td>
						<td style="text-align: left;border:1px solid #000;">'.$client->address3.'</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Address 4</td>
						<td style="text-align: left;border:1px solid #000;">'.$client->address4.'</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Address 5</td>
						<td style="text-align: left;border:1px solid #000;">'.$client->address5.'</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Primary Email</td>
						<td style="text-align: left;border:1px solid #000;">'.$client->email.'</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Type</td>
						<td style="text-align: left;border:1px solid #000;">'.$client->tye.'</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Class Name</td>
						<td style="text-align: left;border:1px solid #000;">'.$class_details->classname.'</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Tax Reg1</td>
						<td style="text-align: left;border:1px solid #000;">'.$client->tax_reg1.'</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Tax Reg2</td>
						<td style="text-align: left;border:1px solid #000;">'.$client->tax_reg2.'</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Tax Reg3</td>
						<td style="text-align: left;border:1px solid #000;">'.$client->tax_reg3.'</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Secondary Email</td>
						<td style="text-align: left;border:1px solid #000;">'.$client->email2.'</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Phone</td>
						<td style="text-align: left;border:1px solid #000;">'.$client->phone.'</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Link code</td>
						<td style="text-align: left;border:1px solid #000;">'.$client->linkcode.'</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">cro</td>
						<td style="text-align: left;border:1px solid #000;">'.$client->cro.'</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Trade Status</td>
						<td style="text-align: left;border:1px solid #000;">'.$client->trade_status.'</td>
					</tr>
					<tr>
						<td style="text-align: left;border:1px solid #000;">Directory</td>
						<td style="text-align: left;border:1px solid #000;">'.$client->directory.'</td>
					</tr>';
		            $getfields = DB::table('cm_fields')->where('status',0)->get();
		            if(count($getfields))
	            	{
	            		foreach($getfields as $field)
	              		{
	              			$fieldname = $field->name;
	              			if($field->field == 6)
	              			{
	              				$unserialize = unserialize($field->options);
	              				$key = array_search ($client->$fieldname, $unserialize);
	              				$output.='<tr>
									<td style="text-align: left;border:1px solid #000;">'.$fieldname.'</td>
									<td style="text-align: left;border:1px solid #000;">'.$key.'</td>
								</tr>';
	              			}
	              			else{
	              				$output.='<tr>
									<td style="text-align: left;border:1px solid #000;">'.$fieldname.'</td>
									<td style="text-align: left;border:1px solid #000;">'.$client->$fieldname.'</td>
								</tr>';
	              			}
	              		}
	            	}
					$output.='</table>';
			    }
			}
		}
		
		$pdf = PDF::loadHTML($output);
		$pdf->setPaper('A4', 'landscape');
		if($type == 1)
		{
			$file = $clients[0]->client_id.'_'.$clients[0]->firstname.'_'.$clients[0]->surname.'.pdf';
			$pdf->save('papers/'.$file.'');
			echo $file;
		}
		elseif($type == 2){
			$pdf->save('papers/'.$name.'');
			echo $name;
		}
	}
	public function cm_report_pdf_type_2()
	{
		$ids = explode(",",Input::get('value'));
		$type = Input::get('type');
		$clients = DB::table('cm_clients')->whereIn('id', $ids)->get();
		
		$output = '';

		if($type == 2)
		{
			if(count($clients))
			{
				foreach($clients as $key => $client)
				{
					$output.='<tr>
						<td style="text-align: left;border:1px solid #000;">'.$client->client_id.'</td>
						<td style="text-align: left;border:1px solid #000;">'.$client->firstname.'</td>
						<td style="text-align: left;border:1px solid #000;">'.$client->surname.'</td>
						<td style="text-align: left;border:1px solid #000;">'; $output.=($client->company == "")?$client->firstname.' '.$client->surname:$client->company; $output.='</td>
						<td style="text-align: left;border:1px solid #000;">'.$client->address1.' '.$client->address2.' '.$client->address3.' '.$client->address4.' '.$client->address5.'</td>
						<td style="text-align: left;border:1px solid #000;">'.$client->email.'</td>
					</tr>';
				}
			}
		}
		echo $output;
	}
	public function download_report_pdfs()
	{
		$htmlval = Input::get('htmlval');
		$pdf = PDF::loadHTML($htmlval);
		$pdf->setPaper('A4', 'landscape');
		
		$pdf->save('papers/CM Report.pdf');
		echo 'CM Report.pdf';
	}
	public function cm_report_csv($id=""){
		

		$ids = explode(",",Input::get('value'));
		$clients = DB::table('cm_clients')->whereIn('id', $ids)->get();
		$headers = array(
	        "Content-type" => "text/csv",
	        "Content-Disposition" => "attachment; filename=CM_Report.csv",
	        "Pragma" => "no-cache",
	        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
	        "Expires" => "0"
	    );
	    $getfields = DB::table('cm_fields')->where('status',0)->get();
	    $fieldname = array();
        if(count($getfields))
    	{
    		foreach($getfields as $field)
      		{
      			array_push($fieldname,$field->name);
      		}
      	}
      	
		$columns_1 = array('#', 'Client ID', 'First Name', 'Surname', 'Company', 'Address 1', 'Address 2', 'Address 3', 'Address 4', 'Address 5', 'Primary Email', 'Type', 'Class Name', 'Tax Reg1', 'Tax Reg2', 'Tax Reg3', 'Secondary Email', 'Phone', 'Link code', 'cro', 'Trade Status', 'Directory');
		$columns = array_merge($columns_1,$fieldname);

		$callback = function() use ($clients, $columns)
    	{
	       	$file = fopen('papers/CM_Report.csv', 'w');
		    fputcsv($file, $columns);
			$i=1;
			foreach ($clients as $single) {
				$class_details = DB::table('cm_class')->where('id', $single->active)->first();
				
				$getfields = DB::table('cm_fields')->where('status',0)->get();
			    $fieldval = array();
		        if(count($getfields))
		    	{
		    		foreach($getfields as $field)
		      		{
		      			$val = $field->name;
		      			if($val == "")
		      			{
		      				array_push($fieldval,"N/A");
		      			}
		      			else{
		      				array_push($fieldval,$single->$val);
		      			}
		      			
		      		}
		      	}
		      	$columns_2 = array($i, $single->client_id, $single->firstname, $single->surname, $single->company, $single->address1, $single->address2, $single->address3, $single->address4, $single->address5, $single->email, $single->tye, $class_details->classname, $single->tax_reg1,$single->tax_reg2,$single->tax_reg3, $single->email2, $single->phone, $single->linkcode, $single->cro,$single->trade_status,$single->directory);

		      	$columns_3 = array_merge($columns_2,$fieldval);
				fputcsv($file, $columns_3);
				$i++;
			}
			fclose($file);	
		};
		return Response::stream($callback, 200, $headers);
	}
	public function cm_status_clients()
	{
		$status = Input::get('hidden_status');
		$client = base64_decode(Input::get('client_id'));
		$pin = Input::get('crypt_pin');

		$admin_details = DB::table('admin')->first();

		if(Hash::check($pin,$admin_details->cm_crypt))
		{
			if($status == 1)
			{
				$data['active'] = 2;
			}
			elseif($status == 0)
			{
				$data['active'] = 1;
			}
			DB::table('cm_clients')->where('id',$client)->update($data);
			echo 1;
		}
		else{
			echo 0;
		}
	}
	public function save_image()
	{
		$img = $_POST['data'];
		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$fileData = base64_decode($img);
		//saving
		$fileName = 'uploads/print_image/photo.png';
		file_put_contents($fileName, $fileData);
	}
	public function cm_print_details()
	{
		if(file_exists('uploads/print_image/photo.png'))
		{
			unlink('uploads/print_image/photo.png');
		}
		
		$paper_details = DB::table('cm_paper')->where('status',1)->first();
		$editid = Input::get('editid');
		$details = DB::table('cm_clients')->where('id',base64_decode($editid))->first();
		$output = '<table style="width:100%">';
		if($paper_details->labels != '')
		{
			$explode = explode(',',$paper_details->labels);
			if(count($explode))
			{
				foreach($explode as $exp)
				{
					if($exp == "client_id") { $name = 'Client Id'; }
					if($exp == "firstname") { $name = 'Firstname'; }
					if($exp == "surname") { $name = 'Surname'; }
					if($exp == "company") { $name = 'Company'; }
					if($exp == "address") { $name = 'Address'; }
					if($exp == "email") { $name = 'Primary Email'; }
					if($exp == "tye") { $name = 'Type'; }
					if($exp == "active") { $name = 'Class'; }
					if($exp == "tax_reg1") { $name = 'Tax Reg1'; }
					if($exp == "tax_reg2") { $name = 'Tax Reg2'; }
					if($exp == "tax_reg3") { $name = 'Tax Reg3'; }
					if($exp == "email2") { $name = 'Secondary Email'; }
					if($exp == "phone") { $name = 'Phone'; }
					if($exp == "linkcode") { $name = 'Link Code'; }
					if($exp == "cro") { $name = 'Cro'; }
					if($exp == "trade_status") { $name = 'Trade Status'; }
					if($exp == "directory") { $name = 'Directory'; }

					if($exp != "address") {
						$output.='<tr>
							<td style="text-align: center;font-weight: 600;vertical-align: text-top;padding:5px">'.$details->$exp.'</td>
						</tr>';
					}
					else{
						$output.='<tr>
							<td style="text-align: center;font-weight: 600;vertical-align: text-top;padding:5px">';
								if($details->address1 != "") { $output.='<p style="line-height: 0.7;">'.$details->address1.'</p>'; }
								if($details->address2 != "") { $output.='<p style="line-height: 0.7;">'.$details->address2.'</p>'; }
								if($details->address3 != "") { $output.='<p style="line-height: 0.7;">'.$details->address3.'</p>'; }
								if($details->address4 != "") { $output.='<p style="line-height: 0.7;">'.$details->address4.'</p>'; }
								if($details->address5 != "") { $output.='<p style="line-height: 0.7;">'.$details->address5.'</p>'; }
							$output.='</td>
						</tr>';
					}
				}
			}
		}
		$output.='</table>';
		echo json_encode(array('width' => $paper_details->width, 'height' => $paper_details->height, 'htmlcontent' => $output));
	}
	public function cm_upload()
	{
		$images_arr = array();
		$output = '';
	    foreach($_FILES['images']['name'] as $key=>$val){
	        $target_dir = "uploads/cm_attachments/";
	        if (!file_exists($target_dir)) {
				mkdir($target_dir);
			}
	        $target_file = $target_dir.$_FILES['images']['name'][$key];
	        if(move_uploaded_file($_FILES['images']['tmp_name'][$key],$target_file)){
	            $images_arr[] = $target_file;
	            $attch['attachment'] = $_FILES['images']['name'][$key];
	            $attch['url'] = $target_dir;
	            DB::table('cm_email_attachment')->insert($attch);
	            $output.='<p class="email_attachments">'.$_FILES['images']['name'][$key].'</p>';
	        }
	    }
		echo $output;
	}
	public function email_check_crypt_pin()
	{
		$pin = Input::get('pin');
		$admin_details = DB::table('admin')->first();
		if(Hash::check($pin,$admin_details->cm_crypt))
		{
			echo 1;
		}
		else{
			echo 0;
		}
	}
	public function cm_bulk_email()
	{
		$client_id = Input::get('client_id');
		$secondary = Input::get('secondary');
		$content = Input::get('content');
		$sub = Input::get('subject');
		
		$client_details = DB::table('cm_clients')->where('id',$client_id)->first();
		$admin_details = Db::table('admin')->first();
		$from = $admin_details->email;
		$data['logo'] = URL::to('assets/images/logo.png');

		$admin_cc = $admin_details->cm_cc_email;

		if($secondary == 1)
		{
			if($client_details->email2 != '')
			{
				$sec_email = $client_details->email2;
				$data['sentemails'] = $client_details->email.' , '.$sec_email.' , '.$admin_cc;
			}
			else{
				if($client_details->email != "")
				{
					$data['sentemails'] = $client_details->email.' , '.$admin_cc;
				}
				else{
					$data['sentemails'] = $admin_cc;
				}
			}
		}
		else{
			if($client_details->email != "")
			{
				$data['sentemails'] = $client_details->email.' , '.$admin_cc;
			}
			else{
				$data['sentemails'] = $admin_cc;
			}
		}
		$attachments = DB::table('cm_email_attachment')->get();
		if(count($attachments))
		{
			$data['count_attachment'] = 1;
		}
		else{
			$data['count_attachment'] = 0;
		}
		$data['content'] = $content;
		$contentmessage = view('user/cm_bulk_email', $data);
		if($client_details->company == "")
		{
			$subject = 'GBS & CO. Note: '.$sub.' '.$client_details->firstname.$client_details->surname;
		}
		else{
			$subject = 'GBS & CO. Note: '.$sub.' '.$client_details->company;
		}

		
		if($client_details->email != '')
		{
			$to = $client_details->email;
			$email = new PHPMailer();

			$email->SetFrom($from); //Name is optional
			$email->Subject   = $subject;
			$email->Body      = $contentmessage;
			$email->IsHTML(true);
			$email->AddAddress( $to );

			$attachments = DB::table('cm_email_attachment')->get();
			if(count($attachments))
			{
				foreach($attachments as $attachment)
				{
					$path = $attachment->url.'/'.$attachment->attachment;
					$email->AddAttachment( $path , $attachment->attachment );
				}
			}
			$email->Send();
		}
		if($secondary == 1)
		{
			if($client_details->email2 != '')
			{
				$to = $client_details->email2;
				$email = new PHPMailer();
				
				$email->SetFrom($from); //Name is optional
				$email->Subject   = $subject;
				$email->Body      = $contentmessage;
				$email->IsHTML(true);
				$email->AddAddress( $to );

				$attachments = DB::table('cm_email_attachment')->get();
				if(count($attachments))
				{
					foreach($attachments as $attachment)
					{
						$path = $attachment->url.'/'.$attachment->attachment;
						
						$email->AddAttachment( $path , $attachment->attachment );
					}
				}
				$email->Send();
			}
		}
		if($client_details->email != '')
		{
			$to = $admin_cc;
			$email = new PHPMailer();
			

			$email->SetFrom($from); //Name is optional
			$email->Subject   = $subject;
			$email->Body      = $contentmessage;
			$email->IsHTML(true);
			$email->AddAddress( $to );
			$attachments = DB::table('cm_email_attachment')->get();
			if(count($attachments))
			{
				foreach($attachments as $attachment)
				{
					$path = $attachment->url.'/'.$attachment->attachment;
					
					$email->AddAttachment( $path , $attachment->attachment );
				}
			}
			$email->Send();
		}
	}
	public function get_cm_report_clients()
	{
		$clientlist = DB::table('cm_clients')->select('client_id', 'firstname', 'surname', 'company', 'status', 'active', 'id')->get();
        $i=1;
        $output = '';
        if(count($clientlist)){              
	        foreach($clientlist as $client){
	            if($client->status == 1) { $disabled='disabled'; $style="color:red"; } 
	            else{ 
	              $disabled='';
	              if($client->active != "")
	              {
	                $check_color = DB::table('cm_class')->where('id',$client->active)->first();
	                $style="color:#".$check_color->classcolor."";
	              }
	              else{
	                $style="color:#000";
	              }
	            } 
		      	$output.='<tr >
		          <td style="'.$style.'">'.$i.'</td>
		          <td><input type="checkbox" name="select_client" class="select_client class_'.$client->active.'" data-element="'.$client->active.'" value="'.$client->id.'"><label>&nbsp</label></td>
		          <td style="'.$style.'" align="left">'.$client->client_id.'</td>
		          <td style="'.$style.'" align="left">'.$client->firstname.'</td>
		          <td style="'.$style.'" align="left">'; $output.=($client->company == "")?$client->firstname.' '.$client->surname:$client->company; $output.='</td>
		      </tr>';
	        $i++;
	        }
	    }
	    if($i == 1)
	    {
	        $output.='<tr><td colspan="9" align="center">Empty</td></tr>';
	    }
        echo $output;
	}
	public function get_cm_bulk_clients()
	{
		$clientlist = DB::table('cm_clients')->select('client_id', 'firstname', 'surname', 'company', 'email', 'status', 'active', 'id')->get();
        $i=1;
        $output = '';
        if(count($clientlist)){              
	        foreach($clientlist as $client){
	            if($client->status == 1) { $disabled='disabled'; $style="color:red"; $hide_style = 'display:none'; $hide_class = 'deactive_tr'; } 
	            else{ 
	              $disabled='';
	              $hide_style='';
	              $hide_class = '';
	              if($client->active != "")
	              {
	                $check_color = DB::table('cm_class')->where('id',$client->active)->first();
	                $style="color:#".$check_color->classcolor."";
	              }
	              else{
	                $style="color:#000";
	              }
	            }
		      	$output.='<tr class="'.$hide_class.'" style="'.$hide_style.'">
		          <td><input type="checkbox" name="select_email_client" class="select_email_client email_class_'.$client->active.'" data-element="'.$client->active.'" value="'.$client->id.'"><label>&nbsp</label></td>
		          <td style="'.$style.'" align="left">'.$client->client_id.'</td>
		          <td style="'.$style.'" align="left">'.$client->firstname.'</td>
		          <td style="'.$style.'" align="left">'; $output.=($client->company == "")?$client->firstname.' '.$client->surname:$client->company; $output.='</td>
		      	</tr>';
	        $i++;
	        }
	    }
	    if($i == 1)
	    {
	        $output.='<tr><td colspan="9" align="center">Empty</td></tr>';
	    }
        echo $output;
	}
	
	public function import_new_clients()
	{
		if($_FILES['new_file']['name']!='')
		{
			$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';
			$tmp_name = $_FILES['new_file']['tmp_name'];
			$name=$_FILES['new_file']['name'];
			$errorlist = array();
			if(move_uploaded_file($tmp_name, "$uploads_dir/$name")){

				$filepath = $uploads_dir.'/'.$name;
				$objPHPExcel = PHPExcel_IOFactory::load($filepath);
				foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
					$worksheetTitle     = $worksheet->getTitle();
					$highestRow         = $worksheet->getHighestRow(); // e.g. 10
					$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
					$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
					$nrColumns = ord($highestColumn) - 64;
					if($highestRow > 50)
					{
						$height = 50;
					}
					else{
						$height = $highestRow;
					}

					$id = $worksheet->getCellByColumnAndRow(0, 1); $id = trim($id->getValue());
					$firstname = $worksheet->getCellByColumnAndRow(1, 1); $firstname = trim($firstname->getValue());
					$surname = $worksheet->getCellByColumnAndRow(2, 1); $surname = trim($surname->getValue());
					$company = $worksheet->getCellByColumnAndRow(3, 1); $company = trim($company->getValue());

					if($id == "ID" && $firstname == "First Name" && $surname == "Surname" && $company == "Company")
					{	
						$errorrow = array();
						$mainarray = array();
						for ($row = 4; $row <= $height; ++ $row) {
							$id = $worksheet->getCellByColumnAndRow(0, $row); $id = trim($id->getValue());
							$pemail = $worksheet->getCellByColumnAndRow(9, $row); $pemail = trim($pemail->getValue());
							if($id == "" )
							{

							}
							else{
								$check_gbsid = DB::table('cm_clients')->where('client_id',$id)->first();
								if(!count($check_gbsid))
								{
									$data['client_id'] = $id;
									$firstname = $worksheet->getCellByColumnAndRow(1, $row); $data['firstname'] = trim($firstname->getValue());
									$firstname = trim($firstname->getValue());
									$surname = $worksheet->getCellByColumnAndRow(2, $row); $data['surname'] = trim($surname->getValue());
									$surname = trim($surname->getValue());
									$company = $worksheet->getCellByColumnAndRow(3, $row); $company = trim($company->getValue());

									if($company != "")
									{
										$data['company'] = $company;
									}
									else{
										if($surname != ""){
											$surname = ' '.$surname;
										}
										$data['company'] = $firstname.$surname;
									}

									$address1 = $worksheet->getCellByColumnAndRow(4, $row); $data['address1'] = trim($address1->getValue());
									$address2 = $worksheet->getCellByColumnAndRow(5, $row); $data['address2'] = trim($address2->getValue());
									$address3 = $worksheet->getCellByColumnAndRow(6, $row); $data['address3'] = trim($address3->getValue());
									$address4 = $worksheet->getCellByColumnAndRow(7, $row); $data['address4'] = trim($address4->getValue());
									$address5 = $worksheet->getCellByColumnAndRow(8, $row); $data['address5'] = trim($address5->getValue());

									$email = $worksheet->getCellByColumnAndRow(9, $row); $data['email'] = trim($email->getValue());
									$tye = $worksheet->getCellByColumnAndRow(10, $row); $data['tye'] = trim($tye->getValue());
									$active = $worksheet->getCellByColumnAndRow(11, $row); $active = trim($active->getValue());

									if($active == 'N' || $active == 'n' || $active == "no" || $active == "No")
									{
										$data['active'] = 2;
									}
									else{
										$data['active'] = 1;
									}
									$tax_reg1 = $worksheet->getCellByColumnAndRow(12, $row); $data['tax_reg1'] = trim($tax_reg1->getValue());
									$tax_reg2 = $worksheet->getCellByColumnAndRow(13, $row); $data['tax_reg2'] = trim($tax_reg2->getValue());
									$tax_reg3 = $worksheet->getCellByColumnAndRow(14, $row); $data['tax_reg3'] = trim($tax_reg3->getValue());

									$semail = $worksheet->getCellByColumnAndRow(15, $row); $data['email2'] = trim($semail->getValue());
									$phone = $worksheet->getCellByColumnAndRow(16, $row); $data['phone'] = trim($phone->getValue());
									$linkcode = $worksheet->getCellByColumnAndRow(17, $row); $data['linkcode'] = trim($linkcode->getValue());
									$cro = $worksheet->getCellByColumnAndRow(18, $row); $data['cro'] = trim($cro->getValue());

									$trade_status = $worksheet->getCellByColumnAndRow(19, $row); $data['trade_status'] = trim($trade_status->getValue());
									$directory = $worksheet->getCellByColumnAndRow(21, $row); $data['directory'] = trim($directory->getValue());

									DB::table('cm_clients')->insert($data);
								}
							}
						}
					}
					else{
						return redirect('user/client_management')->with('message', 'Import Failed! Invalid Import File');
					}
					
				}
				$out = '';
				if(count($errorlist))
				{
					foreach($errorlist as $error) {
	                    $out.='<p class="error_class">'.$error.'</p>';
	                }
				}
				if($height >= $highestRow)
				{
					if($out != '')
					{
						return redirect('user/client_management')->with('success_error', $out);
					}
					else{
						return redirect('user/client_management')->with('message', 'Clients Imported successfully.');
					}
				}
				else{
					return redirect('user/client_management?filename='.$name.'&height='.$height.'&round=2&highestrow='.$highestRow.'&import_type_new=1&out='.$out.'');
				}
			}
		}

	}
	public function import_new_clients_one()
	{
		$name = Input::get('filename');
		$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';
		$filepath = $uploads_dir.'/'.$name;
		$objPHPExcel = PHPExcel_IOFactory::load($filepath);

		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$worksheetTitle     = $worksheet->getTitle();
			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
			$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
			$nrColumns = ord($highestColumn) - 64;
			
			$round = Input::get('round');
			$last_height = Input::get('height');

			$offset = $round - 1;
			$offsetcount = $last_height + 1;
			$roundcount = $round * 50;
			$nextround = $round + 1;

			if($highestRow > $roundcount)
			{
				$height = $roundcount;
			}
			else{
				$height = $highestRow;
			}
			$errorrow = array();
			$mainarray = array();
			$errorlist = array();
			for ($row = $offsetcount; $row <= $height; ++ $row) {
				$id = $worksheet->getCellByColumnAndRow(0, $row); $id = trim($id->getValue());
				$pemail = $worksheet->getCellByColumnAndRow(9, $row); $pemail = trim($pemail->getValue());
				if($id == "")
				{

				}
				else{
					$check_gbsid = DB::table('cm_clients')->where('client_id',$id)->first();
					if(!count($check_gbsid))
					{
						$data['client_id'] = $id;
						$firstname = $worksheet->getCellByColumnAndRow(1, $row); $data['firstname'] = trim($firstname->getValue());
						$firstname = trim($firstname->getValue());
						$surname = $worksheet->getCellByColumnAndRow(2, $row); $data['surname'] = trim($surname->getValue());
						$surname = trim($surname->getValue());
						$company = $worksheet->getCellByColumnAndRow(3, $row); $company = trim($company->getValue());

						if($company != "")
						{
							$data['company'] = $company;
						}
						else{
							if($surname != ""){
								$surname = ' '.$surname;
							}
							$data['company'] = $firstname.$surname;
						}

						$address1 = $worksheet->getCellByColumnAndRow(4, $row); $data['address1'] = trim($address1->getValue());
						$address2 = $worksheet->getCellByColumnAndRow(5, $row); $data['address2'] = trim($address2->getValue());
						$address3 = $worksheet->getCellByColumnAndRow(6, $row); $data['address3'] = trim($address3->getValue());
						$address4 = $worksheet->getCellByColumnAndRow(7, $row); $data['address4'] = trim($address4->getValue());
						$address5 = $worksheet->getCellByColumnAndRow(8, $row); $data['address5'] = trim($address5->getValue());

						$email = $worksheet->getCellByColumnAndRow(9, $row); $data['email'] = trim($email->getValue());
						$tye = $worksheet->getCellByColumnAndRow(10, $row); $data['tye'] = trim($tye->getValue());
						$active = $worksheet->getCellByColumnAndRow(11, $row); $active = trim($active->getValue());

						if($active == 'N' || $active == 'n' || $active == "no" || $active == "No")
						{
							$data['active'] = 2;
						}
						else{
							$data['active'] = 1;
						}

						$tax_reg1 = $worksheet->getCellByColumnAndRow(12, $row); $data['tax_reg1'] = trim($tax_reg1->getValue());
						$tax_reg2 = $worksheet->getCellByColumnAndRow(13, $row); $data['tax_reg2'] = trim($tax_reg2->getValue());
						$tax_reg3 = $worksheet->getCellByColumnAndRow(14, $row); $data['tax_reg3'] = trim($tax_reg3->getValue());

						$semail = $worksheet->getCellByColumnAndRow(15, $row); $data['email2'] = trim($semail->getValue());
						$phone = $worksheet->getCellByColumnAndRow(16, $row); $data['phone'] = trim($phone->getValue());
						$linkcode = $worksheet->getCellByColumnAndRow(17, $row); $data['linkcode'] = trim($linkcode->getValue());
						$cro = $worksheet->getCellByColumnAndRow(18, $row); $data['cro'] = trim($cro->getValue());

						$trade_status = $worksheet->getCellByColumnAndRow(19, $row); $data['trade_status'] = trim($trade_status->getValue());
						$directory = $worksheet->getCellByColumnAndRow(21, $row); $data['directory'] = trim($directory->getValue());

						DB::table('cm_clients')->insert($data);
					}
				}
			}
		}
		$out = Input::get('out');
		if(count($errorlist))
		{
			foreach($errorlist as $error) {
                $out.='<p class="error_class">'.$error.'</p>';
            }
		}
		if($height >= $highestRow)
		{
			if($out != '')
			{
				return redirect('user/client_management')->with('success_error', $out);
			}
			else{
				return redirect('user/client_management')->with('message', 'Clients Imported successfully.');
			}
		}
		else{
			return redirect('user/client_management?filename='.$name.'&height='.$height.'&round='.$nextround.'&highestrow='.$highestRow.'&out='.$out.'&import_type_new=1');
		}
	}
	public function import_existing_clients()
	{
		$checkbox = Input::get('import_field');
		$check = implode(",",$checkbox);

		if($_FILES['exists_file']['name']!='')
		{
			$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';
			$tmp_name = $_FILES['exists_file']['tmp_name'];
			$name=$_FILES['exists_file']['name'];
			$errorlist = array();

			if(move_uploaded_file($tmp_name, "$uploads_dir/$name")){

				$filepath = $uploads_dir.'/'.$name;
				$objPHPExcel = PHPExcel_IOFactory::load($filepath);
				foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
					$worksheetTitle     = $worksheet->getTitle();
					$highestRow         = $worksheet->getHighestRow(); // e.g. 10
					$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
					$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
					$nrColumns = ord($highestColumn) - 64;
					if($highestRow > 50)
					{
						$height = 50;
					}
					else{
						$height = $highestRow;
					}

					$id = $worksheet->getCellByColumnAndRow(0, 1); $id = trim($id->getValue());
					$firstname = $worksheet->getCellByColumnAndRow(1, 1); $firstname = trim($firstname->getValue());
					$surname = $worksheet->getCellByColumnAndRow(2, 1); $surname = trim($surname->getValue());
					$company = $worksheet->getCellByColumnAndRow(3, 1); $company = trim($company->getValue());

					if($id == "ID" && $firstname == "First Name" && $surname == "Surname" && $company == "Company")
					{	
						$errorrow = array();
						$mainarray = array();
						for ($row = 4; $row <= $height; ++ $row) {
							$id = $worksheet->getCellByColumnAndRow(0, $row); $id = trim($id->getValue());
							$pemail = $worksheet->getCellByColumnAndRow(9, $row); $pemail = trim($pemail->getValue());
							if($id == "" )
							{

							}
							else{
								$check_gbsid = DB::table('cm_clients')->where('client_id',$id)->first();

								$firstname = $worksheet->getCellByColumnAndRow(1, $row); $data['firstname'] = trim($firstname->getValue());
								$firstname = trim($firstname->getValue());
								$surname = $worksheet->getCellByColumnAndRow(2, $row); $data['surname'] = trim($surname->getValue());
								$surname = trim($surname->getValue());
								$company = $worksheet->getCellByColumnAndRow(3, $row); $company = trim($company->getValue());

								if($company != "")
								{
									$data['company'] = $company;
								}
								else{
									if($surname != ""){
										$surname = ' '.$surname;
									}
									$data['company'] = $firstname.$surname;
								}

								$address1 = $worksheet->getCellByColumnAndRow(4, $row); $data['address1'] = trim($address1->getValue());
								$address2 = $worksheet->getCellByColumnAndRow(5, $row); $data['address2'] = trim($address2->getValue());
								$address3 = $worksheet->getCellByColumnAndRow(6, $row); $data['address3'] = trim($address3->getValue());
								$address4 = $worksheet->getCellByColumnAndRow(7, $row); $data['address4'] = trim($address4->getValue());
								$address5 = $worksheet->getCellByColumnAndRow(8, $row); $data['address5'] = trim($address5->getValue());

								$email = $worksheet->getCellByColumnAndRow(9, $row); $data['email'] = trim($email->getValue());
								$tye = $worksheet->getCellByColumnAndRow(10, $row); $data['tye'] = trim($tye->getValue());
								$active = $worksheet->getCellByColumnAndRow(11, $row); $active = trim($active->getValue());
								if($active == 'N' || $active == 'n' || $active == "no" || $active == "No")
								{
									$data['active'] = 2;
								}
								else{
									$data['active'] = 1;
								}

								$tax_reg1 = $worksheet->getCellByColumnAndRow(12, $row); $data['tax_reg1'] = trim($tax_reg1->getValue());
								$tax_reg2 = $worksheet->getCellByColumnAndRow(13, $row); $data['tax_reg2'] = trim($tax_reg2->getValue());
								$tax_reg3 = $worksheet->getCellByColumnAndRow(14, $row); $data['tax_reg3'] = trim($tax_reg3->getValue());

								$semail = $worksheet->getCellByColumnAndRow(15, $row); $data['email2'] = trim($semail->getValue());
								$phone = $worksheet->getCellByColumnAndRow(16, $row); $data['phone'] = trim($phone->getValue());
								$linkcode = $worksheet->getCellByColumnAndRow(17, $row); $data['linkcode'] = trim($linkcode->getValue());
								$cro = $worksheet->getCellByColumnAndRow(18, $row); $data['cro'] = trim($cro->getValue());

								$trade_status = $worksheet->getCellByColumnAndRow(19, $row); $data['trade_status'] = trim($trade_status->getValue());
								$directory = $worksheet->getCellByColumnAndRow(21, $row); $data['directory'] = trim($directory->getValue());

								if(!count($check_gbsid))
								{
									$data['client_id'] = $id;
									DB::table('cm_clients')->insert($data);
								}
								else{
									if(count($checkbox))
									{
										foreach($checkbox as $field)
										{
											$update[$field] = $data[$field];
										}
										DB::table('cm_clients')->where('id',$check_gbsid->id)->update($update);
									}
								}
							}
						}
					}
					else{
						return redirect('user/client_management')->with('message', 'Import Failed! Invalid Import File');
					}
					
				}
				$out = '';
				if(count($errorlist))
				{
					foreach($errorlist as $error) {
	                    $out.='<p class="error_class">'.$error.'</p>';
	                }
				}
				if($height >= $highestRow)
				{
					if($out != '')
					{
						return redirect('user/client_management')->with('success_error', $out);
					}
					else{
						return redirect('user/client_management')->with('message', 'Clients Imported successfully.');
					}
				}
				else{
					return redirect('user/client_management?filename='.$name.'&height='.$height.'&round=2&highestrow='.$highestRow.'&import_type_existing=1&out='.$out.'&checkbox='.$check.'');
				}
			}
		}

	}
	public function import_existing_clients_one()
	{
		$check = Input::get('checkbox');
		$checkbox = explode(",",$check);

		$name = Input::get('filename');
		$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';
		$filepath = $uploads_dir.'/'.$name;
		$objPHPExcel = PHPExcel_IOFactory::load($filepath);

		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$worksheetTitle     = $worksheet->getTitle();
			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
			$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
			$nrColumns = ord($highestColumn) - 64;
			
			$round = Input::get('round');
			$last_height = Input::get('height');

			$offset = $round - 1;
			$offsetcount = $last_height + 1;
			$roundcount = $round * 50;
			$nextround = $round + 1;

			if($highestRow > $roundcount)
			{
				$height = $roundcount;
			}
			else{
				$height = $highestRow;
			}
			$errorrow = array();
			$mainarray = array();
			$errorlist = array();
			for ($row = $offsetcount; $row <= $height; ++ $row) {
				$id = $worksheet->getCellByColumnAndRow(0, $row); $id = trim($id->getValue());
				$pemail = $worksheet->getCellByColumnAndRow(9, $row); $pemail = trim($pemail->getValue());

				if($id == "" )
				{

				}
				else{
					$check_gbsid = DB::table('cm_clients')->where('client_id',$id)->first();

					$firstname = $worksheet->getCellByColumnAndRow(1, $row); $data['firstname'] = trim($firstname->getValue());
					$firstname = trim($firstname->getValue());
					$surname = $worksheet->getCellByColumnAndRow(2, $row); $data['surname'] = trim($surname->getValue());
					$surname = trim($surname->getValue());
					$company = $worksheet->getCellByColumnAndRow(3, $row); $company = trim($company->getValue());

					if($company != "")
					{
						$data['company'] = $company;
					}
					else{
						if($surname != ""){
							$surname = ' '.$surname;
						}
						$data['company'] = $firstname.$surname;
					}

					$address1 = $worksheet->getCellByColumnAndRow(4, $row); $data['address1'] = trim($address1->getValue());
					$address2 = $worksheet->getCellByColumnAndRow(5, $row); $data['address2'] = trim($address2->getValue());
					$address3 = $worksheet->getCellByColumnAndRow(6, $row); $data['address3'] = trim($address3->getValue());
					$address4 = $worksheet->getCellByColumnAndRow(7, $row); $data['address4'] = trim($address4->getValue());
					$address5 = $worksheet->getCellByColumnAndRow(8, $row); $data['address5'] = trim($address5->getValue());

					$email = $worksheet->getCellByColumnAndRow(9, $row); $data['email'] = trim($email->getValue());
					$tye = $worksheet->getCellByColumnAndRow(10, $row); $data['tye'] = trim($tye->getValue());
					$active = $worksheet->getCellByColumnAndRow(11, $row); $active = trim($active->getValue());
					if($active == 'N' || $active == 'n' || $active == "no" || $active == "No")
					{
						$data['active'] = 2;
					}
					else{
						$data['active'] = 1;
					}

					$tax_reg1 = $worksheet->getCellByColumnAndRow(12, $row); $data['tax_reg1'] = trim($tax_reg1->getValue());
					$tax_reg2 = $worksheet->getCellByColumnAndRow(13, $row); $data['tax_reg2'] = trim($tax_reg2->getValue());
					$tax_reg3 = $worksheet->getCellByColumnAndRow(14, $row); $data['tax_reg3'] = trim($tax_reg3->getValue());

					$semail = $worksheet->getCellByColumnAndRow(15, $row); $data['email2'] = trim($semail->getValue());
					$phone = $worksheet->getCellByColumnAndRow(16, $row); $data['phone'] = trim($phone->getValue());
					$linkcode = $worksheet->getCellByColumnAndRow(17, $row); $data['linkcode'] = trim($linkcode->getValue());
					$cro = $worksheet->getCellByColumnAndRow(18, $row); $data['cro'] = trim($cro->getValue());

					$trade_status = $worksheet->getCellByColumnAndRow(19, $row); $data['trade_status'] = trim($trade_status->getValue());
					$directory = $worksheet->getCellByColumnAndRow(21, $row); $data['directory'] = trim($directory->getValue());

					if(!count($check_gbsid))
					{
						$data['client_id'] = $id;
						DB::table('cm_clients')->insert($data);
					}
					else{
						if(count($checkbox))
						{
							foreach($checkbox as $field)
							{
								$update[$field] = $data[$field];
							}
							DB::table('cm_clients')->where('id',$check_gbsid->id)->update($update);
						}						
					}
				}
			}
		}
		$out = Input::get('out');
		if(count($errorlist))
		{
			foreach($errorlist as $error) {
                $out.='<p class="error_class">'.$error.'</p>';
            }
		}
		if($height >= $highestRow)
		{
			if($out != '')
			{
				return redirect('user/client_management')->with('success_error', $out);
			}
			else{
				return redirect('user/client_management')->with('message', 'Clients Imported successfully.');
			}
		}
		else{
			return redirect('user/client_management?filename='.$name.'&height='.$height.'&round='.$nextround.'&highestrow='.$highestRow.'&out='.$out.'&import_type_existing=1&checkbox='.$check.'');
		}
	}
	public function cm_statement_update()
	{
		$value = Input::get('value');
		$id = Input::get('id');
		DB::table('cm_clients')->where('id',$id)->update(['statement' => $value]);
	}

	public function cm_client_invoice(){

		$id = base64_decode(Input::get('id'));

		$client_id = DB::table('cm_clients')->where('id',$id)->first();
		$invoicelist = DB::table('invoice_system')->where('client_id', $client_id->client_id)->get();
		$payrolllist = DB::table('payroll_tasks')->where('client_id', $client_id->client_id)->orderBy('update_time','DESC')->get();

		$outputinvoice = '<table class="display nowrap fullviewtablelist" id="invoice_expand" width="100%">
                <thead>
                  <tr style="background: #fff;">
                      <th style="text-align: left;">S.No</th>
                      <th style="text-align: left;">Invoice #</th>
                      <th style="text-align: left;">Date</th>
                      <th style="text-align: right;">Net</th>
                      <th style="text-align: right;">VAT</th>
                      <th style="text-align: right;">Gross</th>                      
                  </tr>
                </thead>                            
                <tbody>';
		$i=1;

		if(count($invoicelist)){ 
			foreach($invoicelist as $invoice){ 
				$client_details = DB::table('cm_clients')->where('client_id', $invoice->client_id)->first();
				if($invoice->statement == "No"){
					$textcolor="color:#f00";
				}
				else{
					$textcolor="color:#00751a";
				}

				$outputinvoice.='
					<tr>
						<td>'.$i.'</td>
						<td align="left" style="'.$textcolor.'">'.$invoice->invoice_number.'</td>
						<td align="left" style="'.$textcolor.'"><spam style="display:none">'.strtotime($invoice->invoice_date).'</spam>'.date('d-M-Y', strtotime($invoice->invoice_date)).'</td>
						<td align="right" style="'.$textcolor.'">'.number_format_invoice($invoice->inv_net).'</td>
						<td align="right" style="'.$textcolor.'">'.number_format_invoice($invoice->vat_value).'</td>
						<td align="right" style="'.$textcolor.'">'.number_format_invoice($invoice->gross).'</td>						
					</tr>
				';
				$i++;
			}						
		}

		if($i == 1)
        {
          $outputinvoice.='<tr>
          	<td></td>
          	<td></td>
          	<td></td>
          	<td align="right">Empty</td>
          	<td></td>
          	<td></td>
          	<td></td>
          	<td></td>
          </tr>';
        }

        $outputinvoice.='                
                </tbody>
            </table>';
		


		
		$result = DB::table('cm_clients')->where('id', $id)->first();
		$output = '';
        $getfields = DB::table('cm_fields')->where('status',0)->get();
        if(count($getfields))
        {
        	$i = 1;
        	$output.='<tr>';
          foreach($getfields as $field)
          {
          	if($i % 4 == 0) { $output.='</tr><tr>';}
          	$output.='<td> 
          		<label>'.$field->name.' : </label>
                <div class="form-group">';
            if($field->field == 1)
            {
            	$fieldval = $field->name;
                $output.='<input type="text" name="'.$field->name.'" class="form-control '.$field->name.'_add" placeholder="Enter '.$field->name.'" value="'.$result->$fieldval.'">';
            }
            elseif($field->field == 2)
            {
            	$fieldval = $field->name;
                    $output.='<input type="radio" name="'.$field->name.'" class="'.$field->name.'_add" id="'.$field->name.'_yes" value="yes" '; if($result->$fieldval == "yes") { $output.='checked'; } $output.='><label for="'.$field->name.'_yes"> YES </label>
                    <input type="radio" name="'.$field->name.'" class="'.$field->name.'_add" id="'.$field->name.'_no" value="no" '; if($result->$fieldval == "no") { $output.='checked'; } $output.='><label for="'.$field->name.'_no"> NO </label>';
            }
            elseif($field->field == 3)
            {
            	$fieldval = $field->name;
                $output.='<textarea name="'.$field->name.'" class="form-control '.$field->name.'_add" placeholder="Enter '.$field->name.'">'.$result->$fieldval.'</textarea>';
            }
            elseif($field->field == 4)
            {
            	$fieldval = $field->name;
                $output.='<input type="file" name="'.$field->name.'" class="form-control '.$field->name.'_add" placeholder="Enter '.$field->name.'">';
                if($result->$fieldval != "") {
                	$output.='<a href="javascript:" class="fileattachment" data-element="'.URL::to('uploads/cm_fields/'.$result->client_id.'/'.$fieldval.'/'.$result->$fieldval.'').'">'.$result->$fieldval.'</a>';
            	}
            }
            elseif($field->field == 5)
            {
            	$fieldval = $field->name;
                $output.='<input type="email" name="'.$field->name.'" class="form-control '.$field->name.'_add" placeholder="Enter '.$field->name.'" value="'.$result->$fieldval.'">';
            }
            elseif($field->field == 6)
            {
            	$fieldval = $field->name;
              $unserialize = unserialize($field->options);
                $output.='<select name="'.$field->name.'" class="form-control '.$field->name.'_add">
                      <option value="">Select '.$field->name.'</option>';
                        if(count($unserialize))
                        {
                          foreach($unserialize as $key => $arrayval)
                          {
                            $output.='<option value="'.$arrayval.'" '; if($result->$fieldval == $arrayval) { $output.='selected'; } else { $output.=''; } $output.='>'.$key.'</option>';
                          }
                        }
                    $output.='</select>';
            }
            $output.='</div></td>';
            $i++; 
          }
          $output.='</tr>';
        }

        $clientid = $client_id->client_id;
        $timetasklist = DB::table('time_task')->where('clients','like','%'.$clientid.'%')->get();

        $i=1;
        $outputtimetask='<table class="display nowrap fullviewtablelist" id="timetask_expand" width="100%">
                <thead>
                  <tr style="background: #fff;">
                      <th style="text-align: left;" width="10%">S.No</th>
                      <th style="text-align: left;">Task Name</th>                     
                  </tr>
                </thead>                            
                <tbody>
        ';
        if(count($timetasklist)){
        	foreach ($timetasklist as $key => $timetask) {
        		$outputtimetask.='
        			<tr>
        				<td>'.$i.'</td>
        				<td>'.$timetask->task_name.'</td>
        			</tr>
        		';
        		$i++;
        	}
        }
        if($i == 1)
        {
          $outputtimetask.='<tr><td></td><td>Empty</td></tr>';
        }

        $outputtimetask.='</tbody></table>';

        $output_payroll = '<table class="display nowrap fullviewtablelist" id="payroll_expand" width="100%">
                <thead>
                  <tr style="background: #fff;">
                      <th width="2%" style="text-align: left;">S.No</th>
                      <th style="text-align: left;">Task Name</th>
                      <th style="text-align: left;">Year</th>
                      <th style="text-align: left;">Period</th>
                      <th style="text-align: left;">Email Sent</th>
                      <th style="text-align: left;">When the Task is Marked Complete</th>
                  </tr>
                </thead>                            
                <tbody>';
		$i=1;

		if(count($payrolllist)){ 
			foreach($payrolllist as $payroll){ 
				$task_details = DB::table('task')->where('task_id',$payroll->task_id)->first();
				$year = DB::table('year')->where('year_id', $payroll->year)->first();

				if($payroll->month == 0){
					$week = DB::table('week')->where('week_id', $payroll->week)->first();
					$period = $week->week;
					$text = 'Week';
				}
				else{
					$month = DB::table('month')->where('month_id', $payroll->month)->first();
					$period = $month->month;
					$text = 'Month';
				}

				if($payroll->email_sent != '0000-00-00 00:00:00'){
					$unsentfile = date('d F Y @ H : i', strtotime($payroll->email_sent));
				}
				else{
					$unsentfile = 'N/A';
				}

				$output_payroll.='
					<tr>
						<td>'.$i.'</td>
						<td align="left">'.$task_details->task_name.'</td>
						<td align="left">'.$year->year_name.'</td>
						<td align="left">'.$text.' '.$period.'</td>
						<td align="left">'.$unsentfile.'</td>
						<td align="left">'.date('d F Y @ H : i', strtotime($payroll->update_time)).'</td>
					</tr>
				';
				$i++;
			}						
		}

		if($i == 1)
        {
          $output_payroll.='<tr><td colspan="5" align="center">Empty</td></tr>';
        }

        $output_payroll.='                
                </tbody>
            </table>';

         $aml_bank_list = DB::table('aml_bank')->where('client_id', $result->client_id)->get();
        

        
         $outputbank='<table class="display nowrap fullviewtablelist"  id="bank_expand">
            <thead>
              <th>#</th>
              <th>Bank Name</th>
              <th>Account Name</th>
              <th>Account Number</th>
            </thead>
            <tbody>';
         $ibank=1;
         if(count($aml_bank_list)){
			foreach ($aml_bank_list as $bank) {
				$outputbank.='
				<tr>
					<td>'.$ibank.'</td>
					<td>'.$bank->bank_name.'</td>
					<td>'.$bank->account_name.'</td>
					<td>'.$bank->account_number.'</td>
				</tr>
				';
				$ibank++;
			}
		}

		$outputbank.='                
                </tbody>
            </table>';




	echo json_encode(array('clientid' => $result->client_id, 'firstname' => $result->firstname, 'surname' => $result->surname, 'company' => $result->company, 'address1' => $result->address1, 'address2' => $result->address2, 'address3' => $result->address3, 'address4' => $result->address4, 'address5' => $result->address5, 'email' => $result->email, 'tye' => $result->tye, 'active' => $result->active, 'tax_reg1' => $result->tax_reg1, 'tax_reg2' => $result->tax_reg2, 'tax_reg3' => $result->tax_reg3, 'email2' => $result->email2, 'phone' => $result->phone, 'linkcode' => $result->linkcode, 'cro' => $result->cro, 'trade_status' => $result->trade_status, 'directory' => $result->directory,'employer_no' => $result->employer_no,'salutation' => $result->salutation,'status' => $result->status,   'id' => $result->id,'htmlcontent' => $output, 'invoiceoutput' => $outputinvoice, 'payrolloutput' => $output_payroll, 'timetaskoutput' => $outputtimetask, 'client_note' => $result->notes, 'outputbank' => $outputbank));

		
	}


	public function cm_client_payroll(){

		$id = base64_decode(Input::get('id'));

		$payrolllist = DB::table('payroll_tasks')->where('client_id', $id)->orderBy('update_time','DESC')->get();

		$output = '<table class="display nowrap fullviewtablelist" id="payroll_expand" width="100%">
                <thead>
                  <tr style="background: #fff;">
                      <th width="2%" style="text-align: left;">S.No</th>
                      <th style="text-align: left;">Year</th>
                      <th style="text-align: left;">Period</th>
                      <th style="text-align: left;">Email Sent</th>
                      <th style="text-align: left;">When the Task is Marked Complete</th>
                  </tr>
                </thead>                            
                <tbody>';
		$i=1;

		if(count($payrolllist)){ 
			foreach($payrolllist as $payroll){ 
				$year = DB::table('year')->where('year_id', $payroll->year)->first();

				if($payroll->month == 0){
					$week = DB::table('week')->where('week_id', $payroll->week)->first();
					$period = $week->week;
					$text = 'Week';
				}
				else{
					$month = DB::table('month')->where('month_id', $payroll->month)->first();
					$period = $month->month;
					$text = 'Month';
				}

				if($payroll->email_sent != '0000-00-00'){
					$unsentfile = date('d F Y @ H : i', strtotime($payroll->email_sent));
				}
				else{
					$unsentfile = 'N/A';
				}

				$output.='
					<tr>
						<td>'.$i.'</td>
						<td align="left">'.$year->year_name.'</td>
						<td align="left">'.$text.' '.$period.'</td>
						<td align="left">'.$unsentfile.'</td>
						<td align="left">'.date('d F Y @ H : i', strtotime($payroll->update_time)).'</td>
					</tr>
				';
				$i++;
			}						
		}

		if($i == 1)
        {
          $output.='<tr><td colspan="5" align="center">Empty</td></tr>';
        }

        $output.='                
                </tbody>
            </table>';
		echo $output;


				
	}

	public function cm_invoice_report_csv($id=""){

		$id = Input::get('value');


		$client_name = DB::table('cm_clients')->where('client_id', $id)->first();
		$filename = $client_name->company;


		$invoice = DB::select('SELECT `id`, `invoice_number`, `invoice_date`, `client_id`, `inv_net`, `vat_rate`,`vat_value`, `gross`, `status`, `statement`,UNIX_TIMESTAMP(`invoice_date`) as inc_date from `invoice_system` WHERE client_id = "'.$id.'" ORDER BY client_id,inc_date DESC');

		


		$headers = array(
	        "Content-type" => "text/csv",
	        "Content-Disposition" => "attachment; filename=CM_Report.csv",
	        "Pragma" => "no-cache",
	        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
	        "Expires" => "0"
	    );      	

		$columns = array('#', 'Invoice Number', 'Invoice Date', 'Client ID', 'Company Name', 'Net', 'VAT', 'Gross');
		$callback = function() use ($invoice, $columns)
    	{
	       	$file = fopen('papers/CM_Report.csv', 'w');
		    fputcsv($file, $columns);
			$i=1;
			foreach ($invoice as $single) {
				$company_details = DB::table('cm_clients')->where('client_id', $single->client_id)->first();
		      	$columns_2 = array($i, $single->invoice_number, date('d-M-Y', strtotime($single->invoice_date)), $single->client_id, $company_details->company, number_format_invoice($single->inv_net), number_format_invoice($single->vat_value),  number_format_invoice($single->gross));
				fputcsv($file, $columns_2);
				$i++;
			}
			fclose($file);
		};
		return Response::stream($callback, 200, $headers);
		//return $filename.'_InvoiceReport.csv';
	}
	public function cm_get_csv_filename($id=""){
		$id = Input::get('value');


		$client_name = DB::table('cm_clients')->where('client_id', $id)->first();
		$filename = $client_name->company;
		echo $filename.'_InvoiceReport.csv';
	}


	public function cm_invoice_report_pdf()
	{

		$id = Input::get('value');

		$client_name = DB::table('cm_clients')->where('client_id', $id)->first();
		

		if($client_name->company != "")
		{

			$filename = $client_name->company.'-'.$id;
			$companyname = $client_name->company;
		}
		else{
			$filename = $client_name->company.'-'.$id;
			$companyname = $client_name->firstname.' '.$client_name->surname;
		}
		
		


		$invoicelist = DB::select('SELECT `id`, `invoice_number`, `invoice_date`, `client_id`, `inv_net`, `vat_rate`,`vat_value`, `gross`, `status`, `statement`,UNIX_TIMESTAMP(`invoice_date`) as inc_date from `invoice_system` WHERE client_id = "'.$id.'" ORDER BY client_id,inc_date DESC');

		

		$output = '';

		$i=1;



		if(count($invoicelist)){

				foreach($invoicelist as $key => $invoice)

				{

					$client_details = DB::table('cm_clients')->where('client_id', $invoice->client_id)->first();

					$output.='<tr>

									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$i.'</td>

									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px" align="left">'.$invoice->invoice_number.'</td>

									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px" align="left">'.date('d-M-Y', strtotime($invoice->invoice_date)).'</td>

									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px" align="left">'.$invoice->client_id.'</td>

									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px" align="left">'.$client_details->company.'</td>

									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px" align="right">'.number_format_invoice($invoice->inv_net).'</td>

									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px" align="right">'.number_format_invoice($invoice->vat_value).'</td>

									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px" align="right">'.number_format_invoice($invoice->gross).'</td>

								</tr>';

								$i++;

				}

		}

		echo json_encode(array("filename" => $filename,'output' => $output,'companyname'=>$companyname));

	}



	public function cm_invoice_download_report_pdfs()

	{



		$htmlval = Input::get('htmlval');

		$pdf = PDF::loadHTML($htmlval);

		$pdf->setPaper('A4', 'landscape');

		

		$pdf->save('papers/Invoice Report.pdf');

		echo 'Invoice Report.pdf';

	}

	public function cm_note_update(){
		$id = Input::get('client_id');
		$notes = Input::get('notes');

		DB::table('cm_clients')->where('client_id',  $id)->update(['notes' => $notes]);		
		return redirect::back()->with('message','Notes Update Success');
	}
	
}
<?php namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\Admin;
use App\CmClass;
use App\CmPaper;
use Session;
use Schema;
use Hash;
class CmsystemController extends Controller {

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
	public function __construct(Admin $admin, cmclass $cmclass, cmpaper $cmpaper)
	{
		$this->middleware('adminauth');
		$this->admin = $admin;
		$this->cmclass = $cmclass;
		$this->cmpaper = $cmpaper;
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */	
	public function cmprofile()
	{
		$admin_details = DB::table('admin')->where('id',1)->first();		
		return view('admin/cm/profile', array('admin_details' => $admin_details));
	}
	public function cm_clients_list()
	{
		$client = DB::table('cm_clients')->select('client_id', 'firstname', 'surname', 'company', 'email', 'status', 'active', 'id','address1','address2','address3','address4','address5','phone','statement')->get();
		$class = DB::table('cm_class')->where('status', 0)->get();
		return view('admin/cm/clients', array('title' => 'Client Management', 'clientlist' => $client, 'classlist' => $class));
	}
	public function updatecmcrypt()
	{
		$old=Input::get('oldcryptpassword');
		$password=Hash::make(Input::get('cryptpassword'));

		$admin = DB::table('admin')->first();
		
		if(Hash::check($old,$admin->cm_crypt))
		{
			DB::table('admin')->where('id',1)->update(['cm_crypt'=>$password,'view_pass'=>Input::get('cryptpassword')]);
			return Redirect::back()->with('message', 'Settings Updated Successfully');
		}
		else{
			return Redirect::back()->with('message', 'Current Password is wrong. please try again later');
		}
	}
	public function cmclass()
	{
		$cmclass = DB::table('cm_class')->get();
		return view('admin/cm/class', array('title' => 'CM Class', 'cmclasslist' => $cmclass));
	}
	public function addclass(){
		$name = Input::get('name');
		$color = Input::get('color');
		DB::table('cm_class')->insert(['classname' => $name, 'classcolor' => $color]);
		return redirect::back()->with('message','Add Success');
	}
	public function editcmclass($id=""){
		$id = base64_decode($id);
		$result = DB::table('cm_class')->where('id', $id)->first();
		echo json_encode(array('name' => $result->classname, 'color' => $result->classcolor, 'id' => $result->id));
	}

	public function updatecmclass(){
		$name = Input::get('name');
		$color = Input::get('color');
		$id = Input::get('id');

		
		DB::table('cm_class')->where('id', $id)->update(['classname' => $name, 'classcolor' => $color]);
		return redirect::back()->with('message','Update Success');
	}
	public function deactivecmclass($id=""){
		$id = base64_decode($id);
		$deactive =  1;
		DB::table('cm_class')->where('id', $id)->update(['status' => $deactive ]);
		return redirect::back()->with('message','Deactive Success');
	}
	public function activecmclass($id=""){
		$id = base64_decode($id);
		$active =  0;
		DB::table('cm_class')->where('id', $id)->update(['status' => $active ]);
		return redirect::back()->with('message','Active Success');
	}
	public function cmpaper()
	{
		$cmprint = DB::table('cm_paper')->get();
		return view('admin/cm/print', array('title' => 'CM Print', 'cmprintlist' => $cmprint));
	}
	public function addpaper(){
		$name = Input::get('name');
		$width = Input::get('width');
		$height = Input::get('height');

		$fields = Input::get('fields');
		$fld = '';
		if(count($fields))
		{
			foreach($fields as $field)
			{
				if($fld == "")
				{
					$fld = $field;
				}
				else{
					$fld = $fld.','.$field;
				}
			}
		}
		$status = Input::get('set_as_default');
		if($status == 1)
		{
			DB::table('cm_paper')->update(['status' => 0]);
		}
		else{
			$status = 0;
		}
		DB::table('cm_paper')->insert(['papername' => $name, 'width' => $width, 'height' => $height,'labels' => $fld,'status' => $status]);
		return redirect::back()->with('message','Add Success');
	}
	public function editcmpaper($id=""){
		$id = base64_decode($id);
		$result = DB::table('cm_paper')->where('id', $id)->first();
		echo json_encode(array('name' => $result->papername, 'width' => $result->width, 'height' => $result->height, 'fields' => $result->labels, 'status' => $result->status, 'id' => $result->id));
	}

	public function updatecmpaper(){
		$name = Input::get('name');
		$width = Input::get('width');
		$height = Input::get('height');
		$id = Input::get('id');
		$fields = Input::get('fields');
		$fld = '';
		if(count($fields))
		{
			foreach($fields as $field)
			{
				if($fld == "")
				{
					$fld = $field;
				}
				else{
					$fld = $fld.','.$field;
				}
			}
		}

		$status = Input::get('set_as_default');
		if($status == 1)
		{
			DB::table('cm_paper')->update(['status' => 0]);
		}
		
		DB::table('cm_paper')->where('id', $id)->update(['papername' => $name, 'width' => $width, 'height' => $height,'labels' => $fld,'status' => $status]);
		return redirect::back()->with('message','Update Success');
	}

	public function deactivecmpaper($id=""){
		$id = base64_decode($id);
		$deactive =  1;
			DB::table('cm_paper')->update(['status' => 0]);
		DB::table('cm_paper')->where('id', $id)->update(['status' => $deactive ]);
		return redirect::back()->with('message','Deactive Success');
	}
	public function activecmpaper($id=""){
		$id = base64_decode($id);
		$active =  0;
		DB::table('cm_paper')->where('id', $id)->update(['status' => $active ]);
		return redirect::back()->with('message','Active Success');
	}

	public function cmfields()
	{
		$cmfield = DB::table('cm_fields')->get();
		return view('admin/cm/field', array('title' => 'CM Class', 'cmfieldlist' => $cmfield));
	}

	public function addfield(){
		$name = Input::get('name_add');
		$field = Input::get('field');
		if($field == 6)
		{
			$options = Input::get('options');
			$value = Input::get('value');
			if(count($options))
			{
				$setarray = array();
				foreach($options as $key => $opt)
				{
					$setarray[$opt] = $value[$key];
				}
				$serialize = serialize($setarray);
				DB::table('cm_fields')->insert(['name' => $name, 'field' => $field,'options' => $serialize]);
			}
		}
		else{
			DB::table('cm_fields')->insert(['name' => $name, 'field' => $field]);
			
		}
		Schema::table('cm_clients', function($table) use ($name)
		{
		    $table->string($name);
		});
		return redirect::back()->with('message','Add Success');
	}

	public function editfield($id=""){
		$id = base64_decode($id);
		$result = DB::table('cm_fields')->where('id', $id)->first();
		$optionsval = '';
		if($result->field == 6)
		{
			if($result->options != "")
			{
				$unserialize = unserialize($result->options);
				if(count($unserialize))
				{
					$i = 1;
					foreach($unserialize as $key => $opt)
					{
						if($i == count($unserialize))
						{
							$optionsval.='<tr><td><input type="text" class="form-control input-sm" name="options[]" value="'.$key.'" required></td><td><input type="text" class="form-control input-sm" name="value[]" value="'.$opt.'" required></td><td class="action_icon_edit"><a href="javascript:" class="fa fa-plus add_option_edit"></a><a href="javascript:" class="fa fa-minus delete_option_edit"></a></td></tr>';
						}
						else{
							$optionsval.='<tr><td><input type="text" class="form-control input-sm" name="options[]" value="'.$key.'" required></td><td><input type="text" class="form-control input-sm" name="value[]" value="'.$opt.'" required></td><td class="action_icon_edit"><a href="javascript:" class="fa fa-minus delete_option_edit"></a></td></tr>';
						}
						$i++;
					}
				}
			}
		}
		echo json_encode(array('name' => $result->name, 'field' => $result->field, 'id' => $result->id, 'options' => $optionsval));
	}

	public function updatecmfield(){
		$name = Input::get('name');
		$field = Input::get('field');		
		$id = Input::get('id');

		if($field == 6)
		{
			$options = Input::get('options');
			$value = Input::get('value');
			if(count($options))
			{
				$setarray = array();
				foreach($options as $key => $opt)
				{
					$setarray[$opt] = $value[$key];
				}
				$serialize = serialize($setarray);
				$data['name'] = $name;
				$data['field'] = $field;
				$data['options'] = $serialize; 
				DB::table('cm_fields')->where('id', $id)->update($data);
			}
		}
		else{
			$data['name'] = $name;
			$data['field'] = $field;
			$data['options'] = ''; 
			DB::table('cm_fields')->where('id', $id)->update($data);
		}
		return redirect::back()->with('message','Update Success');
	}
	public function deactivefield($id=""){
		$id = base64_decode($id);
		$deactive =  1;
		DB::table('cm_fields')->where('id', $id)->update(['status' => $deactive ]);
		return redirect::back()->with('message','Deactive Success');
	}
	public function activefield($id=""){
		$id = base64_decode($id);
		$active =  0;
		DB::table('cm_fields')->where('id', $id)->update(['status' => $active ]);
		return redirect::back()->with('message','Active Success');
	}
	public function cm_client_checkfield()
	{
		$field = Input::get('name_add');
		$columns = Schema::getColumnListing('cm_clients');
		
		if(in_array($field,$columns))
			$valid = false;
		else
			$valid = true;
		
		echo json_encode($valid);
		exit;
	}
	public function update_cm_incomplete_status()
	{
		$data['cm_incomplete'] = Input::get('value');
		DB::table('user_login')->where('userid',1)->update($data);
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
	                <td align="left"><a href="javascript:" id="'.base64_encode($client->id).'" class="edit_client" style="'.$style.'">'.$client->client_id.'</a></td>
	                <td align="left"><a href="javascript:" id="'.base64_encode($client->id).'" class="edit_client" style="'.$style.'">'.$client->firstname.'</a></td>
	                <td style="'.$style.'" align="left">'.$client->surname.'</td>
	                <td style="'.$style.'" align="left">'; $output.=($client->company == "")?$client->firstname.' '.$client->surname:$client->company; $output.='</td>
	                <td align="left">
                        <select name="class_select" class="form-control class_select" data-element="'.$client->id.'">';
                              $class = DB::table('cm_class')->where('status', 0)->get();
                              if(count($class))
                              {
                                foreach($class as $cls)
                                {
                                  if($cls->id == $client->active) { $selected = 'selected'; } else { $selected = ''; }
                                  $output.='<option value="'.$cls->id.'" '.$selected.'>'.$cls->classname.'</option>';
                                }
                              }
                        $output.='</select>
                    </td>
	                <td style="word-wrap: break-word; white-space:normal; min-width:300px; max-width: 300px;'.$style.'" align="left">'.$address.'</td>
	                <td align="left"><a style="'.$style.'" href="mailto:'.$client->email.'">'.$client->email.'</a></td>
	                <td style="'.$style.'" align="left">'.$client->phone.'</td>
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
	public function change_cm_client_class()
	{
		$value = Input::get('val');
		$id = Input::get('id');
		$class_color = DB::table('cm_class')->where('id',$value)->first();
		DB::table('cm_clients')->where('id',$id)->update(['active' => $value]);
		echo $class_color->classcolor;
	}
}

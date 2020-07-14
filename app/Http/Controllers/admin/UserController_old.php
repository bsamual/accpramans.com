<?php namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\User;
use Session;
class UserController extends Controller {

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
	public function __construct(User $user)
	{
		$this->middleware('adminauth');
		$this->user = $user;
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function manageuser()
	{
		$user = DB::table('user')->get();
		return view('admin/user', array('title' => 'User', 'userlist' => $user));
	}
	public function deactiveuser($id=""){
		$id = base64_decode($id);
		$deactive =  1;
		DB::table('user')->where('user_id', $id)->update(['user_status' => $deactive ]);
		return redirect('admin/manage_user')->with('message','Deactive Success');
	}
	public function activeuser($id=""){
		$id = base64_decode($id);
		$active =  0;
		DB::table('user')->where('user_id', $id)->update(['user_status' => $active ]);
		return redirect('admin/manage_user')->with('message','Active Success');
	}
	public function adduser(){
		$name = Input::get('name');
		$lname = Input::get('lname');
		$email = Input::get('email');
		$initial = Input::get('initial');
		$id = DB::table('user')->insertGetId(['firstname' => $name, 'lastname' => $lname, 'email' => $email,'initial' => $initial]);

		$years = DB::table('taskyear')->get();
		if(count($years))
		{
			foreach($years as $year)
			{
				$userid = $year->taskyear_user;
				if($userid == "")
				{
					$dataval['taskyear_user'] = $id;
				}
				else{
					$dataval['taskyear_user'] = $userid.','.$id;
				}
				DB::table('taskyear')->where('taskyear_id', $year->taskyear_id)->update($dataval);
			}
		}
		return redirect('admin/manage_user')->with('message','Add Success');
	}
	public function deleteuser($id=""){
		$id = base64_decode($id);
		$data['disabled'] = $_GET['status'];
		DB::table('user')->where('user_id', $id)->update($data);
		if($_GET['status'] == 1)
		{
			return redirect('admin/manage_user')->with('message','User Disabled Successfully');
		}
		else{
			return redirect('admin/manage_user')->with('message','User Enabled Successfully');
		}
	}
	public function edituser($id=""){
		$id = base64_decode($id);
		$result = DB::table('user')->where('user_id', $id)->first();
		echo json_encode(array('name' => $result->firstname, 'lname' => $result->lastname, 'email' =>  $result->email,'initial' =>  $result->initial, 'id' => $result->user_id));
	}
	public function updateuser(){
		$name = Input::get('name');
		$id = Input::get('id');
		$lname = Input::get('lname');
		$email = Input::get('email');
		$initial = Input::get('initial');
		DB::table('user')->where('user_id', $id)->update(['firstname' => $name, 'lastname' => $lname, 'email' => $email,'initial' => $initial]);
		return redirect('admin/manage_user')->with('message','Update Success');
	}

	public function manageusercosting(){
		$user_id = base64_decode(Input::get('id'));

		$calculate_count = DB::table('user_calculate_cost')->where('user_id', $user_id)->first();
		$user_details = DB::table('user')->where('user_id', $user_id)->first();
		$staff_name = $user_details->firstname.' '.$user_details->lastname;

		$staff_cost = DB::select('SELECT * from `user_cost` WHERE user_id = "'.$user_id.'" ORDER BY UNIX_TIMESTAMP(`from_date`) ASC'); 
		$output_cost='<div class="col-lg-12 padding_00"><b>Cost Analysis Summary</b></div>
				<table class="table"><thead><tr><th style="text-align:left">From</th><th style="text-align:left">To</th><th style="text-align:left">Cost</th><th>Action</th></tr></thead>';
		if(count($staff_cost)){
			foreach ($staff_cost as $cost) {
				if($cost->to_date != '0000-00-00'){
					$to_date = date('d-m-Y', strtotime($cost->to_date));
				}
				else{
					$to_date='';
				}
				$output_cost.='<tr>
					<td>'.date('d-m-Y', strtotime($cost->from_date)).'</td>
					<td>'.$to_date.'</td>
					<td>&euro; '.$cost->cost.'</td>
					<td align="center"><a href="javascript:"><i class="fa fa fa-trash delete_cost" data-element="'.base64_encode($cost->cost_id).'"></i></a></td>
				</tr>';
			}
		}
		else{
			$output_cost.='<td colspan="4" align="center">Empty</td>';
		}
		$output_cost.='</table>';

		if(count($calculate_count) == ''){
			$data['user_id'] = $user_id;
			DB::table('user_calculate_cost')->insert($data);
			$user_calculate = DB::table('user_calculate_cost')->where('user_id', $user_id)->first();

			echo json_encode(array('base_salary' => $user_calculate->base_salary, 'annual_bonus' => $user_calculate->annual_bonus, 'other_annual' => $user_calculate->other_annual, 'total_salary' => $user_calculate->total_salary, 'standard_hour' => $user_calculate->standard_hour, 'holiday_day' => $user_calculate->holiday_day, 'rate_social_insurance' => $user_calculate->rate_social_insurance, 'social_insurance' => $user_calculate->social_insurance, 'cost_per_hour' => $user_calculate->cost_per_hour, 'holiday_cost_per_hour' => $user_calculate->holiday_cost_per_hour, 'final_cost_per_hour' => $user_calculate->final_cost_per_hour, 'user_id' => base64_encode($user_id), 'staff_name' => $staff_name, 'output_cost' => $output_cost));
		}
		else{
			$user_calculate = DB::table('user_calculate_cost')->where('user_id', $user_id)->first();

			echo json_encode(array('base_salary' => $user_calculate->base_salary, 'annual_bonus' => $user_calculate->annual_bonus, 'other_annual' => $user_calculate->other_annual, 'total_salary' => $user_calculate->total_salary, 'standard_hour' => $user_calculate->standard_hour, 'holiday_day' => $user_calculate->holiday_day, 'rate_social_insurance' => $user_calculate->rate_social_insurance, 'social_insurance' => $user_calculate->social_insurance, 'cost_per_hour' => $user_calculate->cost_per_hour, 'holiday_cost_per_hour' => $user_calculate->holiday_cost_per_hour, 'final_cost_per_hour' => $user_calculate->final_cost_per_hour, 'user_id' => base64_encode($user_id), 'staff_name' => $staff_name, 'output_cost' => $output_cost));
		}
	}

	public function usercostingupdate(){
		$value = Input::get('value');
		$user_id = base64_decode(Input::get('user_id'));
		$type = Input::get('type');
		$calculate_details = DB::table('user_calculate_cost')->where('user_id', $user_id)->first();

		

		if($type == 1){
			$data['base_salary'] = $value;
			$data['total_salary'] = $value+$calculate_details->annual_bonus+$calculate_details->other_annual;
			DB::table('user_calculate_cost')->where('user_id', $user_id)->update($data);

			$row_details = DB::table('user_calculate_cost')->where('user_id', $user_id)->first();
			$social = $row_details->total_salary/100*$row_details->rate_social_insurance;	

			DB::table('user_calculate_cost')->where('user_id', $user_id)->update(['social_insurance' => $social]);	
			$row_details = DB::table('user_calculate_cost')->where('user_id', $user_id)->first();	

			if($row_details->standard_hour == ''){				
				$perhour_result = '0';
				$holiday_result = '0';
			}
			else{
				$perhour_total_insurance = $row_details->total_salary+$row_details->social_insurance;
				$perhour_result = $perhour_total_insurance/52/$row_details->standard_hour;

				DB::table('user_calculate_cost')->where('user_id', $user_id)->update(['cost_per_hour' => $perhour_result]);
				$row_details = DB::table('user_calculate_cost')->where('user_id', $user_id)->first();

				$holiday1 = $row_details->holiday_day/520;
				$holiday2 = $holiday1/$row_details->standard_hour;
				$holiday_result = $holiday2*$row_details->cost_per_hour;

				DB::table('user_calculate_cost')->where('user_id', $user_id)->update(['holiday_cost_per_hour' => $holiday_result]);

			}

			$row_details = DB::table('user_calculate_cost')->where('user_id', $user_id)->first();

			$final_cost_hour = $row_details->cost_per_hour+$row_details->holiday_cost_per_hour;

			

			DB::table('user_calculate_cost')->where('user_id', $user_id)->update(['final_cost_per_hour' => $final_cost_hour]);

			echo json_encode(array('result' => $row_details->total_salary, 'social_insurance' => $social, 'per_hour' => $perhour_result, 'holiday_result' => $holiday_result, 'final_cost_result' => $final_cost_hour));
		}
		elseif($type==2){
			$data['annual_bonus'] = $value;
			$data['total_salary'] = $calculate_details->base_salary+$value+$calculate_details->other_annual;

			DB::table('user_calculate_cost')->where('user_id', $user_id)->update($data);

			$row_details = DB::table('user_calculate_cost')->where('user_id', $user_id)->first();
			$social = $row_details->total_salary/100*$row_details->rate_social_insurance;

			DB::table('user_calculate_cost')->where('user_id', $user_id)->update(['social_insurance' => $social]);
			$row_details = DB::table('user_calculate_cost')->where('user_id', $user_id)->first();

			if($row_details->standard_hour == ''){
				$perhour_result = '0';
				$holiday_result = '0';
			}
			else{
				$perhour_total_insurance = $row_details->total_salary+$row_details->social_insurance;
				$perhour_result = $perhour_total_insurance/52/$row_details->standard_hour;

				DB::table('user_calculate_cost')->where('user_id', $user_id)->update(['cost_per_hour' => $perhour_result]);

				$row_details = DB::table('user_calculate_cost')->where('user_id', $user_id)->first();

				$holiday1 = $row_details->holiday_day/520;
				$holiday2 = $holiday1/$row_details->standard_hour;
				$holiday_result = $holiday2*$row_details->cost_per_hour;

				DB::table('user_calculate_cost')->where('user_id', $user_id)->update(['holiday_cost_per_hour' => $holiday_result]);				
			}

			$row_details = DB::table('user_calculate_cost')->where('user_id', $user_id)->first();

			$final_cost_hour = $row_details->cost_per_hour+$row_details->holiday_cost_per_hour;		


			DB::table('user_calculate_cost')->where('user_id', $user_id)->update(['cost_per_hour' => $perhour_result, 'holiday_cost_per_hour' => $holiday_result, 'final_cost_per_hour' => $final_cost_hour]);

			echo json_encode(array('result' => $row_details->total_salary, 'social_insurance' => $social, 'per_hour' => $perhour_result, 'holiday_result' => $holiday_result, 'final_cost_result' => $final_cost_hour ));
		}
		elseif($type==3){
			$data['other_annual'] = $value;
			$data['total_salary'] = $calculate_details->base_salary+$calculate_details->annual_bonus+$value;

			DB::table('user_calculate_cost')->where('user_id', $user_id)->update($data);

			$row_details = DB::table('user_calculate_cost')->where('user_id', $user_id)->first();
			$social = $row_details->total_salary/100*$row_details->rate_social_insurance;

			DB::table('user_calculate_cost')->where('user_id', $user_id)->update(['social_insurance' => $social]);
			$row_details = DB::table('user_calculate_cost')->where('user_id', $user_id)->first();

			if($row_details->standard_hour == ''){
				$perhour_result = '0';
				$holiday_result = '0';
			}
			else{
				$perhour_total_insurance = $row_details->total_salary+$row_details->social_insurance;
				$perhour_result = $perhour_total_insurance/52/$row_details->standard_hour;

				DB::table('user_calculate_cost')->where('user_id', $user_id)->update(['cost_per_hour' => $perhour_result]);

				$row_details = DB::table('user_calculate_cost')->where('user_id', $user_id)->first();

				$holiday1 = $row_details->holiday_day/520;
				$holiday2 = $holiday1/$row_details->standard_hour;
				$holiday_result = $holiday2*$row_details->cost_per_hour;

				DB::table('user_calculate_cost')->where('user_id', $user_id)->update(['holiday_cost_per_hour' => $holiday_result]);

			}

			$row_details = DB::table('user_calculate_cost')->where('user_id', $user_id)->first();

			$final_cost_hour = $row_details->cost_per_hour+$row_details->holiday_cost_per_hour;

			DB::table('user_calculate_cost')->where('user_id', $user_id)->update(['final_cost_per_hour' => $final_cost_hour]);

			echo json_encode(array('result' => $row_details->total_salary, 'social_insurance' => $social, 'per_hour' => $perhour_result, 'holiday_result' => $holiday_result, 'final_cost_result' => $final_cost_hour));
		}

		elseif($type==4){
			$data['standard_hour'] =$value;
			DB::table('user_calculate_cost')->where('user_id', $user_id)->update($data);

			$row_details = DB::table('user_calculate_cost')->where('user_id', $user_id)->first();

			if($row_details->standard_hour == ''){
				$perhour_result = '0';
				$holiday_result = '0';
			}
			else{
				$perhour_total_insurance = $row_details->total_salary+$row_details->social_insurance;
				$perhour_result = $perhour_total_insurance/52/$row_details->standard_hour;

				DB::table('user_calculate_cost')->where('user_id', $user_id)->update(['cost_per_hour' => $perhour_result]);

				$row_details = DB::table('user_calculate_cost')->where('user_id', $user_id)->first();

				$holiday1 = $row_details->holiday_day/520;
				$holiday2 = $holiday1/$row_details->standard_hour;
				$holiday_result = $holiday2*$row_details->cost_per_hour;

				DB::table('user_calculate_cost')->where('user_id', $user_id)->update(['holiday_cost_per_hour' => $holiday_result]);

			}

			$row_details = DB::table('user_calculate_cost')->where('user_id', $user_id)->first();

			$final_cost_hour = $row_details->cost_per_hour+$row_details->holiday_cost_per_hour;

 			

			DB::table('user_calculate_cost')->where('user_id', $user_id)->update(['final_cost_per_hour' => $final_cost_hour]);
			echo json_encode(array('per_hour' => $perhour_result, 'holiday_result' => $holiday_result, 'final_cost_result' => $final_cost_hour));

		}

		elseif($type==5){
			$data['holiday_day'] =$value;
			DB::table('user_calculate_cost')->where('user_id', $user_id)->update($data);

			$row_details = DB::table('user_calculate_cost')->where('user_id', $user_id)->first();

			if($row_details->standard_hour == ''){				
				$holiday_result = '0';
			}
			else{
				$holiday1 = $row_details->holiday_day/520;
				$holiday2 = $holiday1/$row_details->standard_hour;
				$holiday_result = $holiday2*$row_details->cost_per_hour;

				DB::table('user_calculate_cost')->where('user_id', $user_id)->update(['holiday_cost_per_hour' => $holiday_result]);

			}

			$row_details = DB::table('user_calculate_cost')->where('user_id', $user_id)->first();


			$final_cost_hour = $row_details->cost_per_hour+$row_details->holiday_cost_per_hour;

			DB::table('user_calculate_cost')->where('user_id', $user_id)->update(['final_cost_per_hour' => $final_cost_hour]);

			echo json_encode(array('holiday_result' => $holiday_result, 'final_cost_result' => $final_cost_hour));
		}
		elseif($type==6){
			$data['rate_social_insurance'] =$value;
			DB::table('user_calculate_cost')->where('user_id', $user_id)->update($data);

			$row_details = DB::table('user_calculate_cost')->where('user_id', $user_id)->first();

			$social = $row_details->total_salary/100*$row_details->rate_social_insurance;

			DB::table('user_calculate_cost')->where('user_id', $user_id)->update(['social_insurance' => $social]);

			$row_details = DB::table('user_calculate_cost')->where('user_id', $user_id)->first();


			if($row_details->standard_hour == ''){
				$perhour_result = '0';
				$holiday_result = '0';
			}
			else{
				$perhour_total_insurance = $row_details->total_salary+$row_details->social_insurance;
				$perhour_result = $perhour_total_insurance/52/$row_details->standard_hour;

				DB::table('user_calculate_cost')->where('user_id', $user_id)->update(['cost_per_hour' => $perhour_result]);

				$row_details = DB::table('user_calculate_cost')->where('user_id', $user_id)->first();

				$holiday1 = $row_details->holiday_day/520;
				$holiday2 = $holiday1/$row_details->standard_hour;
				$holiday_result = $holiday2*$row_details->cost_per_hour;

				DB::table('user_calculate_cost')->where('user_id', $user_id)->update(['holiday_cost_per_hour' => $holiday_result]);
			}

			$row_details = DB::table('user_calculate_cost')->where('user_id', $user_id)->first();

			$final_cost_hour = $row_details->cost_per_hour+$row_details->holiday_cost_per_hour;

			DB::table('user_calculate_cost')->where('user_id', $user_id)->update(['final_cost_per_hour' => $final_cost_hour]);

			echo json_encode(array('social_insurance' => $social, 'per_hour' => $perhour_result, 'holiday_result' => $holiday_result, 'final_cost_result' => $final_cost_hour));
		}	

	}

	public function manageusercostadd(){
		$user_id = base64_decode(Input::get('user_id'));
		$count_row = DB::table('user_cost')->where('user_id', $user_id)->count();

		if($count_row == ''){
			$data['user_id'] = base64_decode(Input::get('user_id'));
			$data['from_date'] = date('Y-m-d', strtotime(Input::get('from_date')));
			$data['cost'] = Input::get('new_cost');
		}
		else{
			$from_date = strtotime(date('Y-m-d', strtotime(Input::get('from_date'))));
			$date = date('Y-m-d', strtotime(Input::get('from_date')));
			$check_date = DB::select('SELECT * from `user_cost` WHERE user_id = "'.$user_id.'" AND UNIX_TIMESTAMP(`from_date`) <= "'.$from_date.'" AND UNIX_TIMESTAMP(`to_date`) >= "'.$from_date.'"');
			$check_fromdate_equals = DB::table('user_cost')->where('user_id',$user_id)->where('from_date',$date)->get();
			$check_todate_equals = DB::table('user_cost')->where('user_id',$user_id)->where('to_date',$date)->get();

			if(count($check_date))
			{
				echo json_encode(array('output_cost' => "", "alert" =>"The date is already added to this user. Please change the date to add the cost."));
				exit;
			}
			elseif(count($check_fromdate_equals))
			{
				echo json_encode(array('output_cost' => "", "alert" =>"The date is already added to this user. Please change the date to add the cost."));
				exit;
			}
			elseif(count($check_todate_equals))
			{
				echo json_encode(array('output_cost' => "", "alert" =>"The date is already added to this user. Please change the date to add the cost."));
				exit;
			}
			else{
				$check_date_from = DB::select('SELECT * from `user_cost` WHERE user_id = "'.$user_id.'" AND UNIX_TIMESTAMP(`from_date`) > "'.$from_date.'" ORDER BY UNIX_TIMESTAMP(`from_date`) ASC LIMIT 1');

				$check_date_to = DB::select('SELECT * from `user_cost` WHERE user_id = "'.$user_id.'" AND UNIX_TIMESTAMP(`to_date`) < "'.$from_date.'" ORDER BY UNIX_TIMESTAMP(`to_date`) DESC LIMIT 1');

				$data['user_id'] = base64_decode(Input::get('user_id'));
				$data['from_date'] = date('Y-m-d', strtotime(Input::get('from_date')));
				$data['cost'] = Input::get('new_cost');

				if(count($check_date_from))
				{
					$next_from_date = date('Y-m-d', strtotime('-1 day', strtotime($check_date_from[0]->from_date)));
					$data['to_date'] = $next_from_date;
				}
				else{
					$data['to_date'] = '0000-00-00';
					$last_row = DB::table('user_cost')->where('user_id', $user_id)->latest('updatetime')->first();
					$last_to_date = date('Y-m-d', strtotime('-1 day', strtotime(Input::get('from_date'))));
					DB::table('user_cost')->where('cost_id', $last_row->cost_id)->update(['to_date' => $last_to_date]);
				}
			}
		}
		DB::table('user_cost')->insert($data);

		$staff_cost = DB::select('SELECT * from `user_cost` WHERE user_id = "'.$user_id.'" ORDER BY UNIX_TIMESTAMP(`from_date`) ASC'); 
		$output_cost='<div class="col-lg-12 padding_00"><b>Cost Analysis Summary</b></div>
				<table class="table" style="margin-top:20px"><thead><tr><th style="text-align:left">From</th><th style="text-align:left">To</th><th style="text-align:left">Cost</th><th>Action</th></tr></thead>';
		if(count($staff_cost)){
			foreach ($staff_cost as $cost) {
				if($cost->to_date != '0000-00-00'){
					$to_date = date('d-m-Y', strtotime($cost->to_date));
				}
				else{
					$to_date='';
				}
				$output_cost.='<tr>
					<td>'.date('d-m-Y', strtotime($cost->from_date)).'</td>
					<td>'.$to_date.'</td>
					<td>&euro; '.$cost->cost.'</td>
					<td align="center"><a href="javascript:"><i class="fa fa fa-trash delete_cost" data-element="'.base64_encode($cost->cost_id).'"></i></a></td>
				</tr>';
			}
		}
		else{
			$output_cost.='<td colspan="4" align="center">Empty</td>';
		}
		$output_cost.='</table>';

		echo json_encode(array('output_cost' => $output_cost,"alert" => ""));
	}

	public function manageusercostingdelete(){
		$cost_id = base64_decode(Input::get('cost_id'));
		$user_id = base64_decode(Input::get('user_id'));

		DB::table('user_cost')->where('cost_id', $cost_id)->delete();

		$staff_cost = DB::select('SELECT * from `user_cost` WHERE user_id = "'.$user_id.'" ORDER BY UNIX_TIMESTAMP(`from_date`) ASC'); 
		$output_cost='<div class="col-lg-12 padding_00"><b>Cost Analysis Summary</b></div>
				<table class="table" style="margin-top:20px"><thead><tr><th style="text-align:left">From</th><th style="text-align:left">To</th><th style="text-align:left">Cost</th><th>Action</th></tr></thead>';
		if(count($staff_cost)){
			foreach ($staff_cost as $cost) {
				if($cost->to_date != '0000-00-00'){
					$to_date = date('d-m-Y', strtotime($cost->to_date));
				}
				else{
					$to_date='';
				}
				$output_cost.='<tr>
					<td>'.date('d-m-Y', strtotime($cost->from_date)).'</td>
					<td>'.$to_date.'</td>
					<td>&euro; '.$cost->cost.'</td>
					<td align="center"><a href="javascript:"><i class="fa fa fa-trash delete_cost" data-element="'.base64_encode($cost->cost_id).'"></i></a></td>
				</tr>';
			}
		}
		else{
			$output_cost.='<td colspan="4" align="center">Empty</td>';
		}
		$output_cost.='</table>';

		echo json_encode(array('output_cost' => $output_cost));

	}


}

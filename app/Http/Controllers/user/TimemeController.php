<?php namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;

use DB;

use Input;

use Redirect;

use App\TimeTask;

use App\CmClients;

use Session;

use URL;

use PDF;

use Response;

use PHPExcel; 

use PHPExcel_IOFactory;

use PHPExcel_Cell;

use Hash;



class TimemeController extends Controller {



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

	public function __construct(timetask $timetask)

	{

		$this->middleware('userauth');

		$this->timetask = $timetask;

		date_default_timezone_set("Europe/Dublin");

	}



	/**

	 * Show the application welcome screen to the user.

	 *

	 * @return Response

	 */

	public function time_task()

	{	
		$clients = DB::table('cm_clients')->get();
		$client_id_count = DB::table("cm_clients")->where("client_id","!=","")->count();
		$time_task = DB::table('time_task')->get();
		return view('user/timeme/timetask', array('title' => 'Time Task', 'tasklist' => $time_task, 'clientlist' => $clients,"client_id_count" => $client_id_count));

	}

	public function time_task_client_details(){
		
		$clientlist = DB::table('cm_clients')->get();
		$output = '';
		$i=1;
		if(count($clientlist)){
			foreach ($clientlist as $key => $clients) {
				$output.='
						<tr>
							<td>'.$i.'</td>
							<td><input type="checkbox" name="select_client[]" class="select_client class_'.$clients->client_id.'" data-element="'.$clients->client_id.'" value="'.$clients->client_id.'"><label>&nbsp</label></td>
							<td>'.$clients->client_id.'</td>
							<td>'.$clients->firstname.'</td>
							<td>'.$clients->surname.'</td>
							<td width="40%">'.$clients->company.'</td>
						</tr>
				';
				$i++;
			}
		}

		echo $output;
	}

	public function time_task_add(){


		$type = Input::get("task_type");
		
		if($type == 0){
			$data['clients'] ='';
			$data['task_name'] = Input::get("task_name");
			$data['task_type'] = Input::get("task_type");
		}
		else{
			$data['clients'] = implode(",",Input::get('select_client'));
			$data['task_name'] = Input::get("task_name");
			$data['task_type'] = Input::get("task_type");
		}
		DB::table('time_task')->insert($data);
		return Redirect::back()->with('message', 'Time Task added Succefully');
	}

	public function time_task_client_counts(){
		$taskid = base64_decode(Input::get('id'));
		
		$tasklist = DB::table('time_task')->where('id', $taskid)->first();
		$client_id = explode(",",$tasklist->clients);

		

		$i=1;
		$output='';


		if(count($client_id)){
			foreach ($client_id as $key => $client) {

				$clientdetails = DB::table('cm_clients')->where('client_id', $client)->first();

				if(count($clientdetails)){
						$client_id = $clientdetails->client_id;
						$firstname = $clientdetails->firstname;
						$surname = $clientdetails->surname;
						$company = $clientdetails->company;
				}
				else{
						$client_id = '';
						$firstname = '';
						$surname = '';
						$company = '';

				}


				$output.='
						<tr>
							<td>'.$i.'</td>
							<td>'.$client_id.'</td>
							<td>'.$firstname.'</td>
							<td>'.$surname.'</td>
							<td width="40%">'.$company.'</td>
						</tr>';
				$i++;
			}
		}

		echo $output;		
	}

	public function timetasklock_unlock(){


		$id = base64_decode(Input::get('id'));
		$status = Input::get('status');		
		$crypt = Input::get('crypt');		

		$admin_details = DB::table('admin')->first();

		if(Hash::check($crypt,$admin_details->cm_crypt))
		{
			DB::table('time_task')->where('id', $id)->update(['status' => $status]);
			if($status == '1'){
				return redirect::back()->with('message','Lock Success');
			}
			else{
				return redirect::back()->with('message','Unlock Success');
			}
			
		}
		else{
			return redirect::back()->with('message','Crypt Pin You have entered is Incorrect.');
		}
	}

	public function timetask_edit(){
		$id = base64_decode(Input::get('id'));
		$task_details = DB::table('time_task')->where('id',$id)->first();

		echo json_encode(array('taskname' => $task_details->task_name,'taskid' => $task_details->id,'clients' => $task_details->clients, 'tasktype' => $task_details->task_type));
	}

	public function time_task_update(){

		$taskid = Input::get("taskid");
		$type = Input::get("task_type");
		
		if($type == 0){
			$data['clients'] = '';
			$data['task_name'] = Input::get("task_name");
			$data['task_type'] = Input::get("task_type");
		}
		else{
			$data['clients'] = implode(",",Input::get('select_client'));
			$data['task_name'] = Input::get("task_name");
			$data['task_type'] = Input::get("task_type");
		}
		DB::table('time_task')->where('id', $taskid)->update($data);
		return Redirect::back()->with('message', 'Updated Succefully');
	}

	public function time_task_review(){
		$taskid = base64_decode(Input::get("id"));
		$client_ids = DB::table("cm_clients")->where("client_id","!=","")->get();

		$update_id ='';	
		$commo = '';

		if(count($client_ids)){
			foreach ($client_ids as $key => $clienid) {
				if($commo == ''){
					$commo = $clienid->client_id;
				}
				else{
					$commo =  $commo.','. $clienid->client_id;
				}
			}
		}
		$data['clients'] = $commo;
		DB::table('time_task')->where('id', $taskid)->update($data);

		$clients_count = DB::table('time_task')->where('id', $taskid)->first();
		$count = count(explode(',',$clients_count->clients));

		echo json_encode(array('reviewcount' => $count, 'taskid' => $taskid));
	}

	public function time_task_review_all(){
		$task_ids = DB::table('time_task')->where('task_type', 2)->get();
		$array = array();
		if(count($task_ids)){
			foreach ($task_ids as $key => $singletask) {
				$taskid = $singletask->id;


				$client_ids = DB::table("cm_clients")->where("client_id","!=","")->get();

				$update_id ='';	
				$commo = '';

				if(count($client_ids)){
					foreach ($client_ids as $key => $clienid) {
						if($commo == ''){
							$commo = $clienid->client_id;
						}
						else{
							$commo =  $commo.','. $clienid->client_id;
						}
					}
				}
				$data['clients'] = $commo;
				DB::table('time_task')->where('id', $taskid)->update($data);

				$clients_count = DB::table('time_task')->where('id', $taskid)->first();
				$count = count(explode(',',$clients_count->clients));
				array_push($array,array('reviewcount' => $count, 'taskid' => $taskid));
			}
		}
		echo json_encode($array);
	}

}



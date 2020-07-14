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
use Session;
use URL;
use PDF;
use Response;
use PHPExcel; 
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class Payep30Controller extends Controller {

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
	public function __construct(year $year, week $week, task $task, classified $classified, user $user, vatclients $vatclients)
	{
		$this->middleware('userauth');
		$this->year = $year;
		$this->week = $week;
		$this->task = $task;
		$this->classified = $classified;
		$this->user = $user;
		$this->vatclients = $vatclients;
		date_default_timezone_set("Europe/Dublin");
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */	
	public function update_paye_p30_first_year()
	{
		$year = Input::get('year');
		$data['year_name'] = $year;
		$yearid = DB::table('paye_p30_year')->insertGetid($data);
		
	}
	public function paye_p30_manage($id)
	{
		$id = base64_decode($id);
		$year = DB::table('paye_p30_year')->where('year_id',$id)->first();		
		if(isset($_GET['page']))
		{
			$page = $_GET['page'];
		}
		else{
			$page = 1;
		}

		if($page == 1)
		{
			$pay3_task = DB::table('paye_p30_task')->where('paye_year',$year->year_id)->limit(2)->get();
		}
		else{
			$page = $page - 1;
			$offset = $page * 50;
			$pay3_task = DB::table('paye_p30_task')->where('paye_year',$year->year_id)->offset($offset)->limit(2)->get();
		}
		
		return view('user/paye_p30/paye_p30_manage', array('year' => $year, 'payelist' => $pay3_task));
	}

	public function paye_p30_review_year($id="")
	{
		$paye_year = DB::table('paye_p30_year')->where('year_id', $id)->first();
		$task_year = DB::table('year')->where('year_name', $paye_year->year_name)->first();

		$current_week = DB::table('week')->orderBy('week_id','desc')->first();
		$current_month = DB::table('month')->orderBy('month_id','desc')->first();
		if(count($task_year)){		

		$tasks = DB::table('task')->where('task_year', $task_year->year_id)->where('task_week',$current_week->week_id)->orWhere('task_month',$current_month->month_id)->groupBy('task_enumber')->get();

		if(count($tasks))
		{
			foreach($tasks as $task)
			{
				if($task->task_enumber != "")
				{
					$check_task = DB::table('paye_p30_task')->where('task_id',$task->task_id)->where('paye_year',$id)->count();
					$task_eno = DB::table('paye_p30_task')->where('task_enumber',$task->task_enumber)->where('paye_year',$id)->count();
					if($check_task == 0 && $task_eno == 0)
					{
						$data['task_id'] = $task->task_id;
						$data['task_year'] = $task->task_year;
						$data['paye_year'] = $id;
						$data['task_name'] = $task->task_name;
						$data['task_classified'] = $task->task_classified;
						$data['date'] = $task->date;
						$data['task_enumber'] = $task->task_enumber;
						$data['task_email'] = $task->task_email;
						$data['secondary_email'] = $task->secondary_email;
						$data['salutation'] = $task->salutation;
						$data['network'] = $task->network;
						$data['users'] = $task->users;

						$data['task_level'] = $task->tasklevel;
						$data['pay'] = $task->p30_pay;
						$data['email'] = $task->p30_email;

						for($i=1; $i<=53; $i++) { $data['week'.$i] = '0'; }
						for($i=1; $i<=12; $i++) { $data['month'.$i] = '0'; }
						
						$tasks_all_per_year = DB::table('task')
											->join('week', 'task.task_week', '=', 'week.week_id')
											->where('task_year',$current_month->year)
											->where('task_enumber',$task->task_enumber)
											->where('task_week','!=',0)
											->get();

						$week_n_array = array();
						$week_value = '';
						if(count($tasks_all_per_year))
						{
							foreach($tasks_all_per_year as $task_year)
							{
								if (in_array($task_year->task_week, $week_n_array))
								{
									$ww = ($task_year->liability == "")?0:$task_year->liability;
									$ww = str_replace(",", "", $ww);
									$ww = str_replace(",", "", $ww);
									$ww = str_replace(",", "", $ww);

									$week_value = $week_value + $ww;

								}
								else{
									$ww = ($task_year->liability == "")?0:$task_year->liability;
									$ww = str_replace(",", "", $ww);
									$ww = str_replace(",", "", $ww);
									$ww = str_replace(",", "", $ww);

									$week_value = $ww;
									array_push($week_n_array,$task_year->task_week);
								}
								$week_value = str_replace(",", "", $week_value);
								$week_value = str_replace(",", "", $week_value);
								$week_value = str_replace(",", "", $week_value);
								$data['week'.$task_year->week] = $week_value;
							}
						}

						$tasks_all_per_year_month = DB::table('task')
											->join('month', 'task.task_month', '=', 'month.month_id')
											->where('task_year',$current_month->year)
											->where('task_enumber',$task->task_enumber)
											->where('task_month','!=',0)
											->get();
						$month_n_array = array();
						$month_value = '';
						if(count($tasks_all_per_year_month))
						{
							foreach($tasks_all_per_year_month as $task_year_month)
							{
								if (in_array($task_year_month->task_month, $month_n_array))
								{
									$mm = ($task_year_month->liability == "")?0:$task_year_month->liability;
									$mm = str_replace(",", "", $mm);
									$mm = str_replace(",", "", $mm);
									$mm = str_replace(",", "", $mm);
									$month_value = $month_value + $mm;
								}
								else{
									$mm = ($task_year_month->liability == "")?0:$task_year_month->liability;
									$mm = str_replace(",", "", $mm);
									$mm = str_replace(",", "", $mm);
									$mm = str_replace(",", "", $mm);

									$month_value = $mm;
									array_push($month_n_array,$task_year_month->task_month);
								}
								$month_value = str_replace(",", "", $month_value);
								$month_value = str_replace(",", "", $month_value);
								$month_value = str_replace(",", "", $month_value);
								$data['month'.$task_year_month->month] = $month_value;
							}
						}

						$data['task_liability'] = $data['week1']+$data['week2']+$data['week3']+$data['week4']+$data['week5']+$data['week6']+$data['week7']+$data['week8']+$data['week9']+$data['week10']+$data['week11']+$data['week12']+$data['week13']+$data['week14']+$data['week15']+$data['week16']+$data['week17']+$data['week18']+$data['week19']+$data['week20']+$data['week21']+$data['week22']+$data['week23']+$data['week24']+$data['week25']+$data['week26']+$data['week27']+$data['week28']+$data['week29']+$data['week30']+$data['week31']+$data['week32']+$data['week33']+$data['week34']+$data['week35']+$data['week36']+$data['week37']+$data['week38']+$data['week39']+$data['week40']+$data['week41']+$data['week42']+$data['week43']+$data['week44']+$data['week45']+$data['week46']+$data['week47']+$data['week48']+$data['week49']+$data['week50']+$data['week51']+$data['week52']+$data['week53']+$data['month1']+$data['month2']+$data['month3']+$data['month4']+$data['month5']+$data['month6']+$data['month7']+$data['month8']+$data['month9']+$data['month10']+$data['month11']+$data['month12'];

						$data['active_month'] = 1;


						$paye_id = DB::table('paye_p30_task')->insertGetid($data); 

						for($i=0; $i<=11; $i++)
						{
							$monthinc = $i + 1;
							$insertdata['paye_task'] = $paye_id;
							$insertdata['year_id'] = $id;
							$insertdata['month_id'] = $monthinc;

							DB::table('paye_p30_periods')->insert($insertdata);
						}
					}
				}
			}
		}
			
			return redirect('user/paye_p30_manage/'.base64_encode($id))->with('message', 'Reviewed Successfully.');

		}
		else{
			return redirect('user/paye_p30_manage/'.base64_encode($id))->with('message', 'No Year');
		}
	}

	public function paye_p30_periods_remove(){
		$task_id = Input::get('task_id');
		$week = Input::get('week');	

		$periods = DB::table('paye_p30_periods')->where('period_id', $task_id)->first();
		if(count($periods))
		{
			$p30_task = DB::table('paye_p30_task')->where('id',$periods->paye_task)->first();
			if(count($p30_task))
			{
				if($p30_task->changed_liability_week != "")
				{
					$unserialize = unserialize($p30_task->changed_liability_week);
					$pos = array_search($week, $unserialize);
					unset($unserialize[$pos]);
					$reindexed_array = array_values($unserialize);
					if(count($reindexed_array) > 0)
					{
						$dataser['changed_liability_week'] = serialize($reindexed_array);
					}
					else{
						$dataser['changed_liability_week'] = '';
					}
					DB::table('paye_p30_task')->where('id',$periods->paye_task)->update($dataser);
				}
			}
		}

		if($week == 1){
			$data['week1'] = '';
		}		
		if($week == 2){
			$data['week2'] = '';
		}
		if($week == 3){
			$data['week3'] = '';
		}
		if($week == 4){
			$data['week4'] = '';
		}
		if($week == 5){
			$data['week5'] = '';
		}
		if($week == 6){
			$data['week6'] = '';
		}
		if($week == 7){
			$data['week7'] = '';
		}
		if($week == 8){
			$data['week8'] = '';
		}
		if($week == 9){
			$data['week9'] = '';
		}
		if($week == 10){
			$data['week10'] = '';
		}
		if($week == 11){
			$data['week11'] = '';
		}
		if($week == 12){
			$data['week12'] = '';
		}
		if($week == 13){
			$data['week13'] = '';
		}
		if($week == 14){
			$data['week14'] = '';
		}
		if($week == 15){
			$data['week15'] = '';
		}
		if($week == 16){
			$data['week16'] = '';
		}
		if($week == 17){
			$data['week17'] = '';
		}
		if($week == 18){
			$data['week18'] = '';
		}
		if($week == 19){
			$data['week19'] = '';
		}
		if($week == 20){
			$data['week20'] = '';
		}
		if($week == 21){
			$data['week21'] = '';
		}
		if($week == 22){
			$data['week22'] = '';
		}
		if($week == 23){
			$data['week23'] = '';
		}
		if($week == 24){
			$data['week24'] = '';
		}
		if($week == 25){
			$data['week25'] = '';
		}
		if($week == 26){
			$data['week26'] = '';
		}
		if($week == 27){
			$data['week27'] = '';
		}
		if($week == 28){
			$data['week28'] = '';
		}
		if($week == 29){
			$data['week29'] = '';
		}
		if($week == 30){
			$data['week30'] = '';
		}
		if($week == 31){
			$data['week31'] = '';
		}
		if($week == 32){
			$data['week32'] = '';
		}
		if($week == 33){
			$data['week33'] = '';
		}
		if($week == 34){
			$data['week34'] = '';
		}
		if($week == 35){
			$data['week35'] = '';
		}
		if($week == 36){
			$data['week36'] = '';
		}
		if($week == 37){
			$data['week37'] = '';
		}
		if($week == 38){
			$data['week38'] = '';
		}
		if($week == 39){
			$data['week39'] = '';
		}
		if($week == 40){
			$data['week40'] = '';
		}
		if($week == 41){
			$data['week41'] = '';
		}
		if($week == 42){
			$data['week42'] = '';
		}
		if($week == 43){
			$data['week43'] = '';
		}
		if($week == 44){
			$data['week44'] = '';
		}
		if($week == 45){
			$data['week45'] = '';
		}
		if($week == 46){
			$data['week46'] = '';
		}
		if($week == 47){
			$data['week47'] = '';
		}
		if($week == 48){
			$data['week48'] = '';
		}
		if($week == 49){
			$data['week49'] = '';
		}
		if($week == 50){
			$data['week50'] = '';
		}
		if($week == 51){
			$data['week51'] = '';
		}
		if($week == 52){
			$data['week52'] = '';
		}
		if($week == 53){
			$data['week53'] = '';
		}

		DB::table('paye_p30_periods')->where('period_id', $task_id)->update($data);
		$result = DB::table('paye_p30_periods')->where('period_id', $task_id)->first();
		$result_value = '-';

		$task_liability = $result->week1+$result->week2+$result->week3+$result->week4+$result->week5+$result->week6+$result->week7+$result->week8+$result->week9+$result->week10+$result->week11+$result->week12+$result->week13+$result->week14+$result->week15+$result->week16+$result->week17+$result->week18+$result->week19+$result->week20+$result->week21+$result->week22+$result->week23+$result->week24+$result->week25+$result->week26+$result->week27+$result->week28+$result->week29+$result->week30+$result->week31+$result->week32+$result->week33+$result->week34+$result->week35+$result->week36+$result->week37+$result->week38+$result->week39+$result->week40+$result->week41+$result->week42+$result->week43+$result->week44+$result->week45+$result->week46+$result->week47+$result->week48+$result->week49+$result->week50+$result->week51+$result->week52+$result->week53+$result->month1+$result->month2+$result->month3+$result->month4+$result->month5+$result->month6+$result->month7+$result->month8+$result->month9+$result->month10+$result->month11+$result->month12;
		
		$different = $result->ros_liability-$task_liability;

		DB::table('paye_p30_periods')->where('period_id', $task_id)->update(['task_liability' => $task_liability, 'liability_diff' => $different ]);	

		echo json_encode(array('id' => $result->period_id, 'value' => $result_value, 'week' => $week, 'task_liability' => number_format_invoice($task_liability), 'different' => number_format_invoice($different), 'paye_task' => $result->paye_task));
	}
	public function paye_p30_periods_month_remove(){
		$task_id = Input::get('task_id');
		$month = Input::get('month');	

		$periods = DB::table('paye_p30_periods')->where('period_id', $task_id)->first();
		if(count($periods))
		{
			$p30_task = DB::table('paye_p30_task')->where('id',$periods->paye_task)->first();
			if(count($p30_task))
			{
				if($p30_task->changed_liability_month != "")
				{
					$unserialize = unserialize($p30_task->changed_liability_month);
					$pos = array_search($month, $unserialize);
					unset($unserialize[$pos]);
					$reindexed_array = array_values($unserialize);
					if(count($reindexed_array) > 0)
					{
						$dataser['changed_liability_month'] = serialize($reindexed_array);
					}
					else{
						$dataser['changed_liability_month'] = '';
					}
					DB::table('paye_p30_task')->where('id',$periods->paye_task)->update($dataser);
				}
			}
		}

		if($month == 1){
			$data['month1'] = '';
		}		
		if($month == 2){
			$data['month2'] = '';
		}
		if($month == 3){
			$data['month3'] = '';
		}
		if($month == 4){
			$data['month4'] = '';
		}
		if($month == 5){
			$data['month5'] = '';
		}
		if($month == 6){
			$data['month6'] = '';
		}
		if($month == 7){
			$data['month7'] = '';
		}
		if($month == 8){
			$data['month8'] = '';
		}
		if($month == 9){
			$data['month9'] = '';
		}
		if($month == 10){
			$data['month10'] = '';
		}
		if($month == 11){
			$data['month11'] = '';
		}
		if($month == 12){
			$data['month12'] = '';
		}
		

		DB::table('paye_p30_periods')->where('period_id', $task_id)->update($data);
		$result = DB::table('paye_p30_periods')->where('period_id', $task_id)->first();
		$result_value = '-';

		$task_liability = $result->week1+$result->week2+$result->week3+$result->week4+$result->week5+$result->week6+$result->week7+$result->week8+$result->week9+$result->week10+$result->week11+$result->week12+$result->week13+$result->week14+$result->week15+$result->week16+$result->week17+$result->week18+$result->week19+$result->week20+$result->week21+$result->week22+$result->week23+$result->week24+$result->week25+$result->week26+$result->week27+$result->week28+$result->week29+$result->week30+$result->week31+$result->week32+$result->week33+$result->week34+$result->week35+$result->week36+$result->week37+$result->week38+$result->week39+$result->week40+$result->week41+$result->week42+$result->week43+$result->week44+$result->week45+$result->week46+$result->week47+$result->week48+$result->week49+$result->week50+$result->week51+$result->week52+$result->week53+$result->month1+$result->month2+$result->month3+$result->month4+$result->month5+$result->month6+$result->month7+$result->month8+$result->month9+$result->month10+$result->month11+$result->month12;
		
		$different = $result->ros_liability-$task_liability;

		DB::table('paye_p30_periods')->where('period_id', $task_id)->update(['task_liability' => $task_liability, 'liability_diff' => $different ]);	

		echo json_encode(array('id' => $result->period_id, 'value' => $result_value, 'month' => $month, 'task_liability' => number_format_invoice($task_liability), 'different' => number_format_invoice($different), 'paye_task' => $result->paye_task));
	}

	public function paye_p30_periods_update(){
		$task_id = Input::get('task_id');
		$week = Input::get('week');
		$month_id = Input::get('month_id');
		$year_id = Input::get('year_id');


		$task_details = DB::table('paye_p30_task')->where('id', $task_id)->first();

		$select_week = 'week'.$week;		

		$data[$select_week] = $task_details->$select_week;
		

		/*if($week == 1){
			$data['week1'] = $task_details->week1;
			$result_value = '<a href="javascript:" class="payp30_green week_remove" value="'.$period_details->period_id.'" data-element="1">'.$task_details->week1.'</a>';
		}

		if($week == 2){
			$data['week2'] = $task_details->week2;
			$result_value = '<a href="javascript:" class="payp30_green week_remove" value="'.$period_details->period_id.'" data-element="2">'.$task_details->week2.'</a>';
		}

		if($week == 3){
			$data['week3'] = $task_details->week3;
			$result_value = '<a href="javascript:" class="payp30_green week_remove" value="'.$period_details->period_id.'" data-element="3">'.$task_details->week3.'</a>';	
		}*/

		DB::table('paye_p30_periods')->where('paye_task', $task_id)->where('month_id', $month_id)->update($data);
		$result = DB::table('paye_p30_periods')->where('paye_task', $task_id)->where('month_id', $month_id)->first();
		$result_value = '<a href="javascript:" class="payp30_green week_remove" value="'.$result->period_id.'" data-element="'.$week.'">'.number_format_invoice($task_details->$select_week).'</a>';

		$task_liability = $result->week1+$result->week2+$result->week3+$result->week4+$result->week5+$result->week6+$result->week7+$result->week8+$result->week9+$result->week10+$result->week11+$result->week12+$result->week13+$result->week14+$result->week15+$result->week16+$result->week17+$result->week18+$result->week19+$result->week20+$result->week21+$result->week22+$result->week23+$result->week24+$result->week25+$result->week26+$result->week27+$result->week28+$result->week29+$result->week30+$result->week31+$result->week32+$result->week33+$result->week34+$result->week35+$result->week36+$result->week37+$result->week38+$result->week39+$result->week40+$result->week41+$result->week42+$result->week43+$result->week44+$result->week45+$result->week46+$result->week47+$result->week48+$result->week49+$result->week50+$result->week51+$result->week52+$result->week53+$result->month1+$result->month2+$result->month3+$result->month4+$result->month5+$result->month6+$result->month7+$result->month8+$result->month9+$result->month10+$result->month11+$result->month12;

		$different = $result->ros_liability-$task_liability;

		DB::table('paye_p30_periods')->where('period_id', $result->period_id)->update(['task_liability' => $task_liability, 'liability_diff' => $different ]);

		echo json_encode(array('id' => $result->period_id, 'value' => $result_value, 'week' => $week,  'task_liability' => number_format_invoice($task_liability), 'different' => number_format_invoice($different)));

	}

	public function paye_p30_periods_month_update(){
		$task_id = Input::get('task_id');
		$month = Input::get('month');
		$month_id = Input::get('month_id');
		$year_id = Input::get('year_id');


		$task_details = DB::table('paye_p30_task')->where('id', $task_id)->first();

		$select_month = 'month'.$month;		

		$data[$select_month] = $task_details->$select_month;
		

		/*if($week == 1){
			$data['week1'] = $task_details->week1;
			$result_value = '<a href="javascript:" class="payp30_green week_remove" value="'.$period_details->period_id.'" data-element="1">'.$task_details->week1.'</a>';
		}

		if($week == 2){
			$data['week2'] = $task_details->week2;
			$result_value = '<a href="javascript:" class="payp30_green week_remove" value="'.$period_details->period_id.'" data-element="2">'.$task_details->week2.'</a>';
		}

		if($week == 3){
			$data['week3'] = $task_details->week3;
			$result_value = '<a href="javascript:" class="payp30_green week_remove" value="'.$period_details->period_id.'" data-element="3">'.$task_details->week3.'</a>';	
		}*/

		DB::table('paye_p30_periods')->where('paye_task', $task_id)->where('month_id', $month_id)->update($data);
		$result = DB::table('paye_p30_periods')->where('paye_task', $task_id)->where('month_id', $month_id)->first();

		$result_value = '<a href="javascript:" class="payp30_green month_remove" value="'.$result->period_id.'" data-element="'.$month.'">'.number_format_invoice($task_details->$select_month).'</a>';

		$task_liability = $result->week1+$result->week2+$result->week3+$result->week4+$result->week5+$result->week6+$result->week7+$result->week8+$result->week9+$result->week10+$result->week11+$result->week12+$result->week13+$result->week14+$result->week15+$result->week16+$result->week17+$result->week18+$result->week19+$result->week20+$result->week21+$result->week22+$result->week23+$result->week24+$result->week25+$result->week26+$result->week27+$result->week28+$result->week29+$result->week30+$result->week31+$result->week32+$result->week33+$result->week34+$result->week35+$result->week36+$result->week37+$result->week38+$result->week39+$result->week40+$result->week41+$result->week42+$result->week43+$result->week44+$result->week45+$result->week46+$result->week47+$result->week48+$result->week49+$result->week50+$result->week51+$result->week52+$result->week53+$result->month1+$result->month2+$result->month3+$result->month4+$result->month5+$result->month6+$result->month7+$result->month8+$result->month9+$result->month10+$result->month11+$result->month12;

		$different = $result->ros_liability-$task_liability;

		DB::table('paye_p30_periods')->where('period_id', $result->period_id)->update(['task_liability' => $task_liability, 'liability_diff' => $different ]);

		echo json_encode(array('id' => $result->period_id, 'value' => $result_value, 'month' => $month,  'task_liability' => number_format_invoice($task_liability), 'different' => number_format_invoice($different)));

	}

	public function paye_p30_ros_update(){
		$ros_value = Input::get('value');
		$id = Input::get('id');

		$details = DB::table('paye_p30_periods')->where('period_id', $id)->first();
		$different = $details->task_liability-$ros_value;
		
		DB::table('paye_p30_periods')->where('period_id', $id)->update(['ros_liability' => $ros_value, 'liability_diff' => $different]);

		echo json_encode(array('id' => $details->period_id, 'different' => number_format_invoice($different)));

		
	}

	public function paye_p30_apply(){
		$week_from = Input::get('week_from');
		$week_to = Input::get('week_to');
		$month_from = Input::get('month_from');
		$month_to = Input::get('month_to');
		// $active_month = Input::get('active_month');
		$year_id = Input::get('year_id');

		$paye_year = DB::table('paye_p30_year')->where('year_id', $year_id)->first();
		$task_year = DB::table('year')->where('year_name', $paye_year->year_name)->first();
		$row_details = DB::table('paye_p30_task')->where('task_year', $task_year->year_id)->get();
		
		foreach ($row_details as $details) {
			$check = '';
			for($i=$week_from; $i<=$week_to; $i++){
				$select_week = 'week'.$i;
				$paye_row = DB::table('paye_p30_periods')->where('paye_task', $details->id)->where('week'.$i,'!=','')->count();
				if($paye_row == 0)
				{
					$data[$select_week] = ($details->$select_week)?$details->$select_week:"";
				}
			}

			for($i=$month_from; $i<=$month_to; $i++){
				$select_month = 'month'.$i;
				$paye_row_month = DB::table('paye_p30_periods')->where('paye_task', $details->id)->where('month'.$i,'!=','')->count();
				if($paye_row_month == 0)
				{
					$data[$select_month] = ($details->$select_month)?$details->$select_month:"";
				}
			}
			DB::table('paye_p30_periods')->where('paye_task', $details->id)->where('month_id',$details->active_month)->update($data);		
		}

		DB::select('UPDATE paye_p30_periods SET task_liability = week1 + week2 + week3 + week4 + week5 + week6 + week7 + week8 + week9 + week10 + week11  + week12  + week13  + week14  + week15  + week16  + week17  + week18  + week19  + week20  + week21  + week22  + week23  + week24  + week25  + week26  + week27  + week28  + week29  + week30  + week31  + week32  + week33  + week34  + week35  + week36  + week37  + week38  + week39  + week40  + week41  + week42  + week43  + week44  + week45  + week46  + week47  + week48  + week49  + week50  + week51  + week52  + week53 + month1  + month2  + month3  + month4  + month5  + month6  + month7  + month8  + month9  + month10  + month11  + month12, liability_diff = ros_liability - week1 + week2 + week3 + week4 + week5 + week6 + week7 + week8 + week9 + week10 + week11  + week12  + week13  + week14  + week15  + week16  + week17  + week18  + week19  + week20  + week21  + week22  + week23  + week24  + week25  + week26  + week27  + week28  + week29  + week30  + week31  + week32  + week33  + week34  + week35  + week36  + week37  + week38  + week39  + week40  + week41  + week42  + week43  + week44  + week45  + week46  + week47  + week48  + week49  + week50  + week51  + week52  + week53 + month1  + month2  + month3  + month4  + month5  + month6  + month7  + month8  + month9  + month10  + month11  + month12  WHERE `year_id` = "'.$year_id.'"');

		$result = 'true';
		echo json_encode(array('result' => $result));

	}

	public function paye_p30_active_periods(){
		$week_from = Input::get('week_from');
		$week_to = Input::get('week_to');
		$month_from = Input::get('month_from');
		$month_to = Input::get('month_to');
		$year_id = Input::get('year_id');

		if($week_from == 1) { $week_from = 1; } else { $week_from = $week_from - 1; }
		if($week_to == 53) { $week_to = 53; } else { $week_to = $week_to + 1; }

		if($month_from == 1) { $month_from = 1; } else { $month_from = $month_from - 1; }
		if($month_to == 12) { $month_to = 12; } else { $month_to = $month_to + 1; }

		$data['show_active'] = 1;
		$data['week_from'] = $week_from;
		$data['week_to'] = $week_to;
		$data['month_from'] = $month_from;
		$data['month_to'] = $month_to;

		DB::table('paye_p30_year')->where('year_id', $year_id)->update($data);
		echo json_encode(array("week_from" => $week_from,"week_to" =>$week_to,"month_from" =>$month_from, "month_to" => $month_to));
	}

	public function paye_p30_all_periods(){
		
		$year_id = Input::get('year_id');

		$data['show_active'] = 0;
		$data['week_from'] = 0;
		$data['week_to'] = 0;
		$data['month_from'] = 0;
		$data['month_to'] = 0;

		DB::table('paye_p30_year')->where('year_id', $year_id)->update($data);
	}

	public function paye_p30_single_month(){
		$month_id = Input::get('month_id');
		$task_id = Input::get('task_id');

		DB::table('paye_p30_task')->where('id',$task_id)->update(['active_month' => $month_id]);
		$result = 'true';
		echo json_encode(array('result' => $result));
	}

	public function paye_p30_all_month(){
		$active = Input::get('active');

		DB::table('paye_p30_task')->update(['active_month' => $active]);
		$result = 'true';
		echo json_encode(array('result' => $result));


	}

	public function refresh_paye_p30_liability()
	{
		$task_id = Input::get('task_id');
		$year_id = Input::get('year_id');


		$paye_year = DB::table('paye_p30_year')->where('year_id', $year_id)->first();
		$task_year = DB::table('year')->where('year_name', $paye_year->year_name)->first();

		$current_week = DB::table('week')->where('year', $task_year->year_id)->orderBy('week_id','desc')->first();
		$current_month = DB::table('month')->where('year', $task_year->year_id)->orderBy('month_id','desc')->first();
		
		$task = DB::table('paye_p30_task')->where('id',$task_id)->first();

		for($i=1; $i<=53; $i++) { $data['week'.$i] = '0'; $dataformat['week'.$i] = '0'; }
		for($i=1; $i<=12; $i++) { $data['month'.$i] = '0'; $dataformat['month'.$i] = '0'; }
		
		$tasks_all_per_year = DB::table('task')
							->join('week', 'task.task_week', '=', 'week.week_id')
							->where('task_year',$current_month->year)
							->where('task_enumber',$task->task_enumber)
							->where('task_week','!=',0)
							->get();

		$week_n_array = array();
		$week_value = '';
		if(count($tasks_all_per_year))
		{
			foreach($tasks_all_per_year as $task_year)
			{
				if (in_array($task_year->task_week, $week_n_array))
				{
					$ww = ($task_year->liability == "")?0:$task_year->liability;
					$ww = str_replace(",", "", $ww);
					$ww = str_replace(",", "", $ww);
					$ww = str_replace(",", "", $ww);

					$week_value = $week_value + $ww;

				}
				else{
					$ww = ($task_year->liability == "")?0:$task_year->liability;
					$ww = str_replace(",", "", $ww);
					$ww = str_replace(",", "", $ww);
					$ww = str_replace(",", "", $ww);

					$week_value = $ww;
					array_push($week_n_array,$task_year->task_week);
				}
				$week_value = str_replace(",", "", $week_value);
				$week_value = str_replace(",", "", $week_value);
				$week_value = str_replace(",", "", $week_value);
				$data['week'.$task_year->week] = $week_value;

				$dataformat['week'.$task_year->week] = number_format_invoice($week_value);
			}
		}

		$tasks_all_per_year_month = DB::table('task')
							->join('month', 'task.task_month', '=', 'month.month_id')
							->where('task_year',$current_month->year)
							->where('task_enumber',$task->task_enumber)
							->where('task_month','!=',0)
							->get();
		$month_n_array = array();
		$month_value = '';
		if(count($tasks_all_per_year_month))
		{
			foreach($tasks_all_per_year_month as $task_year_month)
			{
				if (in_array($task_year_month->task_month, $month_n_array))
				{
					$mm = ($task_year_month->liability == "")?0:$task_year_month->liability;
					$mm = str_replace(",", "", $mm);
					$mm = str_replace(",", "", $mm);
					$mm = str_replace(",", "", $mm);
					$month_value = $month_value + $mm;
				}
				else{
					$mm = ($task_year_month->liability == "")?0:$task_year_month->liability;
					$mm = str_replace(",", "", $mm);
					$mm = str_replace(",", "", $mm);
					$mm = str_replace(",", "", $mm);

					$month_value = $mm;
					array_push($month_n_array,$task_year_month->task_month);
				}
				$month_value = str_replace(",", "", $month_value);
				$month_value = str_replace(",", "", $month_value);
				$month_value = str_replace(",", "", $month_value);
				$data['month'.$task_year_month->month] = $month_value;
				$dataformat['month'.$task_year_month->month] = number_format_invoice($month_value);
			}
		}

		$check_blue_week1 = DB::table('paye_p30_periods')->select("week1")->where('paye_task',$task->id)->where('week1','!=','')->first();
		$check_blue_week2 = DB::table('paye_p30_periods')->select("week2")->where('paye_task',$task->id)->where('week2','!=','')->first();
		$check_blue_week3 = DB::table('paye_p30_periods')->select("week3")->where('paye_task',$task->id)->where('week3','!=','')->first();
		$check_blue_week4 = DB::table('paye_p30_periods')->select("week4")->where('paye_task',$task->id)->where('week4','!=','')->first();
		$check_blue_week5 = DB::table('paye_p30_periods')->select("week5")->where('paye_task',$task->id)->where('week5','!=','')->first();
		$check_blue_week6 = DB::table('paye_p30_periods')->select("week6")->where('paye_task',$task->id)->where('week6','!=','')->first();
		$check_blue_week7 = DB::table('paye_p30_periods')->select("week7")->where('paye_task',$task->id)->where('week7','!=','')->first();
		$check_blue_week8 = DB::table('paye_p30_periods')->select("week8")->where('paye_task',$task->id)->where('week8','!=','')->first();
		$check_blue_week9 = DB::table('paye_p30_periods')->select("week9")->where('paye_task',$task->id)->where('week9','!=','')->first();
		$check_blue_week10 = DB::table('paye_p30_periods')->select("week10")->where('paye_task',$task->id)->where('week10','!=','')->first();
		$check_blue_week11 = DB::table('paye_p30_periods')->select("week11")->where('paye_task',$task->id)->where('week11','!=','')->first();
		$check_blue_week12 = DB::table('paye_p30_periods')->select("week12")->where('paye_task',$task->id)->where('week12','!=','')->first();
		$check_blue_week13 = DB::table('paye_p30_periods')->select("week13")->where('paye_task',$task->id)->where('week13','!=','')->first();
		$check_blue_week14 = DB::table('paye_p30_periods')->select("week14")->where('paye_task',$task->id)->where('week14','!=','')->first();
		$check_blue_week15 = DB::table('paye_p30_periods')->select("week15")->where('paye_task',$task->id)->where('week15','!=','')->first();
		$check_blue_week16 = DB::table('paye_p30_periods')->select("week16")->where('paye_task',$task->id)->where('week16','!=','')->first();
		$check_blue_week17 = DB::table('paye_p30_periods')->select("week17")->where('paye_task',$task->id)->where('week17','!=','')->first();
		$check_blue_week18 = DB::table('paye_p30_periods')->select("week18")->where('paye_task',$task->id)->where('week18','!=','')->first();
		$check_blue_week19 = DB::table('paye_p30_periods')->select("week19")->where('paye_task',$task->id)->where('week19','!=','')->first();
		$check_blue_week20 = DB::table('paye_p30_periods')->select("week20")->where('paye_task',$task->id)->where('week20','!=','')->first();
		$check_blue_week21 = DB::table('paye_p30_periods')->select("week21")->where('paye_task',$task->id)->where('week21','!=','')->first();
		$check_blue_week22 = DB::table('paye_p30_periods')->select("week22")->where('paye_task',$task->id)->where('week22','!=','')->first();
		$check_blue_week23 = DB::table('paye_p30_periods')->select("week23")->where('paye_task',$task->id)->where('week23','!=','')->first();
		$check_blue_week24 = DB::table('paye_p30_periods')->select("week24")->where('paye_task',$task->id)->where('week24','!=','')->first();
		$check_blue_week25 = DB::table('paye_p30_periods')->select("week25")->where('paye_task',$task->id)->where('week25','!=','')->first();
		$check_blue_week26 = DB::table('paye_p30_periods')->select("week26")->where('paye_task',$task->id)->where('week26','!=','')->first();
		$check_blue_week27 = DB::table('paye_p30_periods')->select("week27")->where('paye_task',$task->id)->where('week27','!=','')->first();
		$check_blue_week28 = DB::table('paye_p30_periods')->select("week28")->where('paye_task',$task->id)->where('week28','!=','')->first();
		$check_blue_week29 = DB::table('paye_p30_periods')->select("week29")->where('paye_task',$task->id)->where('week29','!=','')->first();
		$check_blue_week30 = DB::table('paye_p30_periods')->select("week30")->where('paye_task',$task->id)->where('week30','!=','')->first();
		$check_blue_week31 = DB::table('paye_p30_periods')->select("week31")->where('paye_task',$task->id)->where('week31','!=','')->first();
		$check_blue_week32 = DB::table('paye_p30_periods')->select("week32")->where('paye_task',$task->id)->where('week32','!=','')->first();
		$check_blue_week33 = DB::table('paye_p30_periods')->select("week33")->where('paye_task',$task->id)->where('week33','!=','')->first();
		$check_blue_week34 = DB::table('paye_p30_periods')->select("week34")->where('paye_task',$task->id)->where('week34','!=','')->first();
		$check_blue_week35 = DB::table('paye_p30_periods')->select("week35")->where('paye_task',$task->id)->where('week35','!=','')->first();
		$check_blue_week36 = DB::table('paye_p30_periods')->select("week36")->where('paye_task',$task->id)->where('week36','!=','')->first();
		$check_blue_week37 = DB::table('paye_p30_periods')->select("week37")->where('paye_task',$task->id)->where('week37','!=','')->first();
		$check_blue_week38 = DB::table('paye_p30_periods')->select("week38")->where('paye_task',$task->id)->where('week38','!=','')->first();
		$check_blue_week39 = DB::table('paye_p30_periods')->select("week39")->where('paye_task',$task->id)->where('week39','!=','')->first();
		$check_blue_week40 = DB::table('paye_p30_periods')->select("week40")->where('paye_task',$task->id)->where('week40','!=','')->first();
		$check_blue_week41 = DB::table('paye_p30_periods')->select("week41")->where('paye_task',$task->id)->where('week41','!=','')->first();
		$check_blue_week42 = DB::table('paye_p30_periods')->select("week42")->where('paye_task',$task->id)->where('week42','!=','')->first();
		$check_blue_week43 = DB::table('paye_p30_periods')->select("week43")->where('paye_task',$task->id)->where('week43','!=','')->first();
		$check_blue_week44 = DB::table('paye_p30_periods')->select("week44")->where('paye_task',$task->id)->where('week44','!=','')->first();
		$check_blue_week45 = DB::table('paye_p30_periods')->select("week45")->where('paye_task',$task->id)->where('week45','!=','')->first();
		$check_blue_week46 = DB::table('paye_p30_periods')->select("week46")->where('paye_task',$task->id)->where('week46','!=','')->first();
		$check_blue_week47 = DB::table('paye_p30_periods')->select("week47")->where('paye_task',$task->id)->where('week47','!=','')->first();
		$check_blue_week48 = DB::table('paye_p30_periods')->select("week48")->where('paye_task',$task->id)->where('week48','!=','')->first();
		$check_blue_week49 = DB::table('paye_p30_periods')->select("week49")->where('paye_task',$task->id)->where('week49','!=','')->first();
		$check_blue_week50 = DB::table('paye_p30_periods')->select("week50")->where('paye_task',$task->id)->where('week50','!=','')->first();
		$check_blue_week51 = DB::table('paye_p30_periods')->select("week51")->where('paye_task',$task->id)->where('week51','!=','')->first();
		$check_blue_week52 = DB::table('paye_p30_periods')->select("week52")->where('paye_task',$task->id)->where('week52','!=','')->first();
		$check_blue_week53 = DB::table('paye_p30_periods')->select("week53")->where('paye_task',$task->id)->where('week53','!=','')->first();

		$check_blue_month1 = DB::table('paye_p30_periods')->select("month1")->where('paye_task',$task->id)->where('month1','!=','')->first();
		$check_blue_month2 = DB::table('paye_p30_periods')->select("month2")->where('paye_task',$task->id)->where('month2','!=','')->first();
		$check_blue_month3 = DB::table('paye_p30_periods')->select("month3")->where('paye_task',$task->id)->where('month3','!=','')->first();
		$check_blue_month4 = DB::table('paye_p30_periods')->select("month4")->where('paye_task',$task->id)->where('month4','!=','')->first();
		$check_blue_month5 = DB::table('paye_p30_periods')->select("month5")->where('paye_task',$task->id)->where('month5','!=','')->first();
		$check_blue_month6 = DB::table('paye_p30_periods')->select("month6")->where('paye_task',$task->id)->where('month6','!=','')->first();
		$check_blue_month7 = DB::table('paye_p30_periods')->select("month7")->where('paye_task',$task->id)->where('month7','!=','')->first();
		$check_blue_month8 = DB::table('paye_p30_periods')->select("month8")->where('paye_task',$task->id)->where('month8','!=','')->first();
		$check_blue_month9 = DB::table('paye_p30_periods')->select("month9")->where('paye_task',$task->id)->where('month9','!=','')->first();
		$check_blue_month10 = DB::table('paye_p30_periods')->select("month10")->where('paye_task',$task->id)->where('month10','!=','')->first();
		$check_blue_month11 = DB::table('paye_p30_periods')->select("month11")->where('paye_task',$task->id)->where('month11','!=','')->first();
		$check_blue_month12 = DB::table('paye_p30_periods')->select("month12")->where('paye_task',$task->id)->where('month12','!=','')->first();

		$blueweek = array();
		$bluemonth = array();
		if(count($check_blue_week1)) { if($check_blue_week1->week1 !== $data['week1']) { array_push($blueweek, "1"); } }
		if(count($check_blue_week2)) { if($check_blue_week2->week2 !== $data['week2']) { array_push($blueweek, "2"); } }
		if(count($check_blue_week3)) { if($check_blue_week3->week3 !== $data['week3']) { array_push($blueweek, "3"); } }
		if(count($check_blue_week4)) { if($check_blue_week4->week4 !== $data['week4']) { array_push($blueweek, "4"); } }
		if(count($check_blue_week5)) { if($check_blue_week5->week5 !== $data['week5']) { array_push($blueweek, "5"); } }
		if(count($check_blue_week6)) { if($check_blue_week6->week6 !== $data['week6']) { array_push($blueweek, "6"); } }
		if(count($check_blue_week7)) { if($check_blue_week7->week7 !== $data['week7']) { array_push($blueweek, "7"); } }
		if(count($check_blue_week8)) { if($check_blue_week8->week8 !== $data['week8']) { array_push($blueweek, "8"); } }
		if(count($check_blue_week9)) { if($check_blue_week9->week9 !== $data['week9']) { array_push($blueweek, "9"); } }
		if(count($check_blue_week10)) { if($check_blue_week10->week10 !== $data['week10']) { array_push($blueweek, "10"); } }
		if(count($check_blue_week11)) { if($check_blue_week11->week11 !== $data['week11']) { array_push($blueweek, "11"); } }
		if(count($check_blue_week12)) { if($check_blue_week12->week12 !== $data['week12']) { array_push($blueweek, "12"); } }
		if(count($check_blue_week13)) { if($check_blue_week13->week13 !== $data['week13']) { array_push($blueweek, "13"); } }
		if(count($check_blue_week14)) { if($check_blue_week14->week14 !== $data['week14']) { array_push($blueweek, "14"); } }
		if(count($check_blue_week15)) { if($check_blue_week15->week15 !== $data['week15']) { array_push($blueweek, "15"); } }
		if(count($check_blue_week16)) { if($check_blue_week16->week16 !== $data['week16']) { array_push($blueweek, "16"); } }
		if(count($check_blue_week17)) { if($check_blue_week17->week17 !== $data['week17']) { array_push($blueweek, "17"); } }
		if(count($check_blue_week18)) { if($check_blue_week18->week18 !== $data['week18']) { array_push($blueweek, "18"); } }
		if(count($check_blue_week19)) { if($check_blue_week19->week19 !== $data['week19']) { array_push($blueweek, "19"); } }
		if(count($check_blue_week20)) { if($check_blue_week20->week20 !== $data['week20']) { array_push($blueweek, "20"); } }
		if(count($check_blue_week21)) { if($check_blue_week21->week21 !== $data['week21']) { array_push($blueweek, "21"); } }
		if(count($check_blue_week22)) { if($check_blue_week22->week22 !== $data['week22']) { array_push($blueweek, "22"); } }
		if(count($check_blue_week23)) { if($check_blue_week23->week23 !== $data['week23']) { array_push($blueweek, "23"); } }
		if(count($check_blue_week24)) { if($check_blue_week24->week24 !== $data['week24']) { array_push($blueweek, "24"); } }
		if(count($check_blue_week25)) { if($check_blue_week25->week25 !== $data['week25']) { array_push($blueweek, "25"); } }
		if(count($check_blue_week26)) { if($check_blue_week26->week26 !== $data['week26']) { array_push($blueweek, "26"); } }
		if(count($check_blue_week27)) { if($check_blue_week27->week27 !== $data['week27']) { array_push($blueweek, "27"); } }
		if(count($check_blue_week28)) { if($check_blue_week28->week28 !== $data['week28']) { array_push($blueweek, "28"); } }
		if(count($check_blue_week29)) { if($check_blue_week29->week29 !== $data['week29']) { array_push($blueweek, "29"); } }
		if(count($check_blue_week30)) { if($check_blue_week30->week30 !== $data['week30']) { array_push($blueweek, "30"); } }
		if(count($check_blue_week31)) { if($check_blue_week31->week31 !== $data['week31']) { array_push($blueweek, "31"); } }
		if(count($check_blue_week32)) { if($check_blue_week32->week32 !== $data['week32']) { array_push($blueweek, "32"); } }
		if(count($check_blue_week33)) { if($check_blue_week33->week33 !== $data['week33']) { array_push($blueweek, "33"); } }
		if(count($check_blue_week34)) { if($check_blue_week34->week34 !== $data['week34']) { array_push($blueweek, "34"); } }
		if(count($check_blue_week35)) { if($check_blue_week35->week35 !== $data['week35']) { array_push($blueweek, "35"); } }
		if(count($check_blue_week36)) { if($check_blue_week36->week36 !== $data['week36']) { array_push($blueweek, "36"); } }
		if(count($check_blue_week37)) { if($check_blue_week37->week37 !== $data['week37']) { array_push($blueweek, "37"); } }
		if(count($check_blue_week38)) { if($check_blue_week38->week38 !== $data['week38']) { array_push($blueweek, "38"); } }
		if(count($check_blue_week39)) { if($check_blue_week39->week39 !== $data['week39']) { array_push($blueweek, "39"); } }
		if(count($check_blue_week40)) { if($check_blue_week40->week40 !== $data['week40']) { array_push($blueweek, "40"); } }
		if(count($check_blue_week41)) { if($check_blue_week41->week41 !== $data['week41']) { array_push($blueweek, "41"); } }
		if(count($check_blue_week42)) { if($check_blue_week42->week42 !== $data['week42']) { array_push($blueweek, "42"); } }
		if(count($check_blue_week43)) { if($check_blue_week43->week43 !== $data['week43']) { array_push($blueweek, "43"); } }
		if(count($check_blue_week44)) { if($check_blue_week44->week44 !== $data['week44']) { array_push($blueweek, "44"); } }
		if(count($check_blue_week45)) { if($check_blue_week45->week45 !== $data['week45']) { array_push($blueweek, "45"); } }
		if(count($check_blue_week46)) { if($check_blue_week46->week46 !== $data['week46']) { array_push($blueweek, "46"); } }
		if(count($check_blue_week47)) { if($check_blue_week47->week47 !== $data['week47']) { array_push($blueweek, "47"); } }
		if(count($check_blue_week48)) { if($check_blue_week48->week48 !== $data['week48']) { array_push($blueweek, "48"); } }
		if(count($check_blue_week49)) { if($check_blue_week49->week49 !== $data['week49']) { array_push($blueweek, "49"); } }
		if(count($check_blue_week50)) { if($check_blue_week50->week50 !== $data['week50']) { array_push($blueweek, "50"); } }
		if(count($check_blue_week51)) { if($check_blue_week51->week51 !== $data['week51']) { array_push($blueweek, "51"); } }
		if(count($check_blue_week52)) { if($check_blue_week52->week52 !== $data['week52']) { array_push($blueweek, "52"); } }
		if(count($check_blue_week53)) { if($check_blue_week53->week53 !== $data['week53']) { array_push($blueweek, "53"); } }

		if(count($check_blue_month1)) { if($check_blue_month1->month1 !== $data['month1']) { array_push($bluemonth, "1"); } }
		if(count($check_blue_month2)) { if($check_blue_month2->month2 !== $data['month2']) { array_push($bluemonth, "2"); } }
		if(count($check_blue_month3)) { if($check_blue_month3->month3 !== $data['month3']) { array_push($bluemonth, "3"); } }
		if(count($check_blue_month4)) { if($check_blue_month4->month4 !== $data['month4']) { array_push($bluemonth, "4"); } }
		if(count($check_blue_month5)) { if($check_blue_month5->month5 !== $data['month5']) { array_push($bluemonth, "5"); } }
		if(count($check_blue_month6)) { if($check_blue_month6->month6 !== $data['month6']) { array_push($bluemonth, "6"); } }
		if(count($check_blue_month7)) { if($check_blue_month7->month7 !== $data['month7']) { array_push($bluemonth, "7"); } }
		if(count($check_blue_month8)) { if($check_blue_month8->month8 !== $data['month8']) { array_push($bluemonth, "8"); } }
		if(count($check_blue_month9)) { if($check_blue_month9->month9 !== $data['month9']) { array_push($bluemonth, "9"); } }
		if(count($check_blue_month10)) { if($check_blue_month10->month10 !== $data['month10']) { array_push($bluemonth, "10"); } }
		if(count($check_blue_month11)) { if($check_blue_month11->month11 !== $data['month11']) { array_push($bluemonth, "11"); } }
		if(count($check_blue_month12)) { if($check_blue_month12->month12 !== $data['month12']) { array_push($bluemonth, "12"); } }


		$data['task_liability'] = $data['week1']+$data['week2']+$data['week3']+$data['week4']+$data['week5']+$data['week6']+$data['week7']+$data['week8']+$data['week9']+$data['week10']+$data['week11']+$data['week12']+$data['week13']+$data['week14']+$data['week15']+$data['week16']+$data['week17']+$data['week18']+$data['week19']+$data['week20']+$data['week21']+$data['week22']+$data['week23']+$data['week24']+$data['week25']+$data['week26']+$data['week27']+$data['week28']+$data['week29']+$data['week30']+$data['week31']+$data['week32']+$data['week33']+$data['week34']+$data['week35']+$data['week36']+$data['week37']+$data['week38']+$data['week39']+$data['week40']+$data['week41']+$data['week42']+$data['week43']+$data['week44']+$data['week45']+$data['week46']+$data['week47']+$data['week48']+$data['week49']+$data['week50']+$data['week51']+$data['week52']+$data['week53']+$data['month1']+$data['month2']+$data['month3']+$data['month4']+$data['month5']+$data['month6']+$data['month7']+$data['month8']+$data['month9']+$data['month10']+$data['month11']+$data['month12'];

		$dataformat['task_liability'] = number_format_invoice($data['task_liability']);
		if(count($blueweek) > 0)
		{
			$data['changed_liability_week'] = serialize($blueweek);
		}
		if(count($bluemonth) > 0)
		{
			$data['changed_liability_month'] = serialize($bluemonth);
		}

			DB::table('paye_p30_task')->where('id',$task_id)->update($data); 

			$dataformat['changed_liability_week'] = $blueweek;
			$dataformat['changed_liability_month'] =$bluemonth;
			echo json_encode($dataformat);		
	}
	

	/*
	public function paye_p30_select_month($id="")
	{
		$id =base64_decode($id);
		$month_id = DB::table('paye_p30_month')->where('month_id', $id)->first();
		$year_id = DB::table('paye_p30_year')->where('year_id', $month_id->year)->first();
		$user_year = $year_id->year_id;
		
		$month2 = $month_id->month_id;
		$year2 = $month_id->year;
		$result_task = DB::table('paye_p30_task')->where('task_month', $month2)->get();

		
		return view('user/paye_p30/paye_p30_select_month', array('title' => 'Paye M.R.S Month Task', 'yearname' => $year_id, 'monthid' => $month_id, 'resultlist' => $result_task));
	}
	public function paye_p30_review_month($id="")
	{
		$current_week = DB::table('week')->orderBy('week_id','desc')->first();
		$current_month = DB::table('month')->orderBy('month_id','desc')->first();
		$tasks = DB::table('task')->where('task_week',$current_week->week_id)->orWhere('task_month',$current_month->month_id)->groupBy('task_enumber')->get();

		if(count($tasks))
		{
			foreach($tasks as $task)
			{
				if($task->task_enumber != "")
				{
					$check_task = DB::table('paye_p30_task')->where('task_id',$task->task_id)->where('task_month',$id)->count();
					$task_eno = DB::table('paye_p30_task')->where('task_enumber',$task->task_enumber)->where('task_month',$id)->count();
					if($check_task == 0 && $task_eno == 0)
					{
						$data['task_id'] = $task->task_id;
						$data['task_year'] = $task->task_year;
						$data['task_month'] = $id;
						$data['task_name'] = $task->task_name;
						$data['task_classified'] = $task->task_classified;
						$data['date'] = $task->date;
						$data['task_enumber'] = $task->task_enumber;
						$data['task_email'] = $task->task_email;
						$data['secondary_email'] = $task->secondary_email;
						$data['salutation'] = $task->salutation;
						$data['network'] = $task->network;
						$data['users'] = $task->users;

						$data['task_level'] = $task->tasklevel;
						$data['pay'] = $task->p30_pay;
						$data['email'] = $task->p30_email;

						for($i=1; $i<=53; $i++) { $data['week'.$i] = '0'; }
						for($i=1; $i<=12; $i++) { $data['month'.$i] = '0'; }
						
						$tasks_all_per_year = DB::table('task')
											->join('week', 'task.task_week', '=', 'week.week_id')
											->where('task_year',$current_month->year)
											->where('task_enumber',$task->task_enumber)
											->where('task_week','!=',0)
											->get();

						$week_n_array = array();
						$week_value = '';
						if(count($tasks_all_per_year))
						{
							foreach($tasks_all_per_year as $task_year)
							{
								if (in_array($task_year->task_week, $week_n_array))
								{
									$ww = ($task_year->liability == "")?0:$task_year->liability;
									$ww = str_replace(",", "", $ww);
									$ww = str_replace(",", "", $ww);
									$ww = str_replace(",", "", $ww);

									$week_value = $week_value + $ww;

								}
								else{
									$ww = ($task_year->liability == "")?0:$task_year->liability;
									$ww = str_replace(",", "", $ww);
									$ww = str_replace(",", "", $ww);
									$ww = str_replace(",", "", $ww);

									$week_value = $ww;
									array_push($week_n_array,$task_year->task_week);
								}
								$week_value = str_replace(",", "", $week_value);
								$week_value = str_replace(",", "", $week_value);
								$week_value = str_replace(",", "", $week_value);
								$data['week'.$task_year->week] = $week_value;
							}
						}

						$tasks_all_per_year_month = DB::table('task')
											->join('month', 'task.task_month', '=', 'month.month_id')
											->where('task_year',$current_month->year)
											->where('task_enumber',$task->task_enumber)
											->where('task_month','!=',0)
											->get();
						$month_n_array = array();
						$month_value = '';
						if(count($tasks_all_per_year_month))
						{
							foreach($tasks_all_per_year_month as $task_year_month)
							{
								if (in_array($task_year_month->task_month, $month_n_array))
								{
									$mm = ($task_year_month->liability == "")?0:$task_year_month->liability;
									$mm = str_replace(",", "", $mm);
									$mm = str_replace(",", "", $mm);
									$mm = str_replace(",", "", $mm);
									$month_value = $month_value + $mm;
								}
								else{
									$mm = ($task_year_month->liability == "")?0:$task_year_month->liability;
									$mm = str_replace(",", "", $mm);
									$mm = str_replace(",", "", $mm);
									$mm = str_replace(",", "", $mm);

									$month_value = $mm;
									array_push($month_n_array,$task_year_month->task_month);
								}
								$month_value = str_replace(",", "", $month_value);
								$month_value = str_replace(",", "", $month_value);
								$month_value = str_replace(",", "", $month_value);
								$data['month'.$task_year_month->month] = $month_value;
							}
						}

						$data['task_liability'] = $data['week1']+$data['week2']+$data['week3']+$data['week4']+$data['week5']+$data['week6']+$data['week7']+$data['week8']+$data['week9']+$data['week10']+$data['week11']+$data['week12']+$data['week13']+$data['week14']+$data['week15']+$data['week16']+$data['week17']+$data['week18']+$data['week19']+$data['week20']+$data['week21']+$data['week22']+$data['week23']+$data['week24']+$data['week25']+$data['week26']+$data['week27']+$data['week28']+$data['week29']+$data['week30']+$data['week31']+$data['week32']+$data['week33']+$data['week34']+$data['week35']+$data['week36']+$data['week37']+$data['week38']+$data['week39']+$data['week40']+$data['week41']+$data['week42']+$data['week43']+$data['week44']+$data['week45']+$data['week46']+$data['week47']+$data['week48']+$data['week49']+$data['week50']+$data['week51']+$data['week52']+$data['week53']+$data['month1']+$data['month2']+$data['month3']+$data['month4']+$data['month5']+$data['month6']+$data['month7']+$data['month8']+$data['month9']+$data['month10']+$data['month11']+$data['month12'];


						DB::table('paye_p30_task')->insert($data); 
					}
				}
			}
		}
		return redirect('user/paye_p30_select_month/'.base64_encode($id))->with('message', 'Reviewed Successfully.');
	}
	public function update_paye_p30_task_status()
	{
		$task_id = explode(",",Input::get('task_id'));
		$data['task_status'] = Input::get('status');
		DB::table('paye_p30_task')->whereIn('id', $task_id)->update($data);
	}
	public function update_paye_p30_hide_task_status()
	{
		$data['paye_hide_task'] = Input::get('status');
		DB::table('user_login')->where('id', 1)->update($data);
	}

	public function update_paye_p30_hide_columns_status()
	{
		$status = Input::get('status');
		$data['paye_hide_columns'] = $status;
		DB::table('user_login')->where('id', 1)->update($data);
	}
	public function update_paye_p30_columns_status()
	{
		$col_id = Input::get('col_id');
		$status = Input::get('status');

		$data[$col_id.'_hide'] = $status;
		DB::table('user_login')->where('id', 1)->update($data);
	}

	public function update_paye_p30_columns_status_selectall()
	{
		$col_id = explode(",",Input::get('col_id'));
		$status = Input::get('status');

		if(count($col_id))
		{
			foreach($col_id as $col)
			{
				$data[$col.'_hide'] = $status;
				DB::table('user_login')->where('id', 1)->update($data);
			}
		}
	}
	public function paye_p30_ros_liability_update()
	{
		$ros_liability = Input::get('liability');
		$task_id = Input::get('task_id');
		$data['ros_liability'] = $ros_liability;
		DB::table('paye_p30_task')->where('id',$task_id)->update($data);

		$calc_diff = DB::table('paye_p30_task')->where('id',$task_id)->first();
		$diff = $calc_diff->ros_liability - $calc_diff->task_liability;
		echo json_encode(array("ros_liability" => (number_format_invoice($ros_liability) == 0.00)?'':number_format_invoice($ros_liability), "diff" => (number_format_invoice($diff) == 0.00)?'':number_format_invoice($diff)));
	}
	public function refresh_paye_p30_liability()
	{
		$task_id = Input::get('task_id');

		$current_week = DB::table('week')->orderBy('week_id','desc')->first();
		$current_month = DB::table('month')->orderBy('month_id','desc')->first();
		
		$task = DB::table('paye_p30_task')->where('id',$task_id)->first();

		for($i=1; $i<=53; $i++) { $data['week'.$i] = '0'; }
		for($i=1; $i<=12; $i++) { $data['month'.$i] = '0'; }
		
		$tasks_all_per_year = DB::table('task')
							->join('week', 'task.task_week', '=', 'week.week_id')
							->where('task_year',$current_month->year)
							->where('task_enumber',$task->task_enumber)
							->where('task_week','!=',0)
							->get();

		$week_n_array = array();
		$week_value = '';
		if(count($tasks_all_per_year))
		{
			foreach($tasks_all_per_year as $task_year)
			{
				if (in_array($task_year->task_week, $week_n_array))
				{
					$ww = ($task_year->liability == "")?0:$task_year->liability;
					$ww = str_replace(",", "", $ww);
					$ww = str_replace(",", "", $ww);
					$ww = str_replace(",", "", $ww);

					$week_value = $week_value + $ww;

				}
				else{
					$ww = ($task_year->liability == "")?0:$task_year->liability;
					$ww = str_replace(",", "", $ww);
					$ww = str_replace(",", "", $ww);
					$ww = str_replace(",", "", $ww);

					$week_value = $ww;
					array_push($week_n_array,$task_year->task_week);
				}
				$week_value = str_replace(",", "", $week_value);
				$week_value = str_replace(",", "", $week_value);
				$week_value = str_replace(",", "", $week_value);
				$data['week'.$task_year->week] = $week_value;
			}
		}

		$tasks_all_per_year_month = DB::table('task')
							->join('month', 'task.task_month', '=', 'month.month_id')
							->where('task_year',$current_month->year)
							->where('task_enumber',$task->task_enumber)
							->where('task_month','!=',0)
							->get();
		$month_n_array = array();
		$month_value = '';
		if(count($tasks_all_per_year_month))
		{
			foreach($tasks_all_per_year_month as $task_year_month)
			{
				if (in_array($task_year_month->task_month, $month_n_array))
				{
					$mm = ($task_year_month->liability == "")?0:$task_year_month->liability;
					$mm = str_replace(",", "", $mm);
					$mm = str_replace(",", "", $mm);
					$mm = str_replace(",", "", $mm);
					$month_value = $month_value + $mm;
				}
				else{
					$mm = ($task_year_month->liability == "")?0:$task_year_month->liability;
					$mm = str_replace(",", "", $mm);
					$mm = str_replace(",", "", $mm);
					$mm = str_replace(",", "", $mm);

					$month_value = $mm;
					array_push($month_n_array,$task_year_month->task_month);
				}
				$month_value = str_replace(",", "", $month_value);
				$month_value = str_replace(",", "", $month_value);
				$month_value = str_replace(",", "", $month_value);
				$data['month'.$task_year_month->month] = $month_value;
			}
		}

		$data['task_liability'] = $data['week1']+$data['week2']+$data['week3']+$data['week4']+$data['week5']+$data['week6']+$data['week7']+$data['week8']+$data['week9']+$data['week10']+$data['week11']+$data['week12']+$data['week13']+$data['week14']+$data['week15']+$data['week16']+$data['week17']+$data['week18']+$data['week19']+$data['week20']+$data['week21']+$data['week22']+$data['week23']+$data['week24']+$data['week25']+$data['week26']+$data['week27']+$data['week28']+$data['week29']+$data['week30']+$data['week31']+$data['week32']+$data['week33']+$data['week34']+$data['week35']+$data['week36']+$data['week37']+$data['week38']+$data['week39']+$data['week40']+$data['week41']+$data['week42']+$data['week43']+$data['week44']+$data['week45']+$data['week46']+$data['week47']+$data['week48']+$data['week49']+$data['week50']+$data['week51']+$data['week52']+$data['week53']+$data['month1']+$data['month2']+$data['month3']+$data['month4']+$data['month5']+$data['month6']+$data['month7']+$data['month8']+$data['month9']+$data['month10']+$data['month11']+$data['month12'];

			DB::table('paye_p30_task')->where('id',$task_id)->update($data); 
			echo json_encode($data);		
	}*/
	public function paye_p30_edit_email_unsent_files()
	{
		$period_id = Input::get('period_id');
		$result = DB::table('paye_p30_periods')->where('period_id',$period_id)->first();
		$task = DB::table('paye_p30_task')->where('id',$result->paye_task)->first();
		if($task->users != 0)
		{
			$user_details = DB::table('user')->where('user_id',$task->users)->first();
			$from = $user_details->email;
		}
		else{
			$from = '';
		}

		if($task->secondary_email != '')
	    {
	      	$to_email = $task->task_email.', '.$task->secondary_email;
	    }
	    else{
	      	$to_email = $task->task_email;
        }

		$date = date('d F Y', strtotime($result->last_email_sent));
		$time = date('H:i', strtotime($result->last_email_sent));
		$last_date = $date.' @ '.$time;
		
		$admin_details = Db::table('admin')->first();
		$admin_cc = $admin_details->p30_cc_email;
		
		$data['sentmails'] = $to_email.', '.$admin_cc;
		$data['logo'] = URL::to('assets/images/easy_payroll_logo.png');
		
		$data['salutation'] = $task->salutation;
		if($result->task_liability == "")
		{
			$task_liability_val = '0.00';
		}
		else{
			$task_liability_val = $result->task_liability;
		}

		if($result->ros_liability == "")
		{
			$ros_liability_val = '0.00';
		}
		else{
			$ros_liability_val = $result->ros_liability;
		}

		$ros_liability_val = str_replace(",", "", $ros_liability_val);
		$ros_liability_val = str_replace(",", "", $ros_liability_val);
		$ros_liability_val = str_replace(",", "", $ros_liability_val);

		$task_liability_val = str_replace(",", "", $task_liability_val);
		$task_liability_val = str_replace(",", "", $task_liability_val);
		$task_liability_val = str_replace(",", "", $task_liability_val);

		$data['task_liability'] = number_format_invoice($task_liability_val);
		$data['ros_liability'] = number_format_invoice($ros_liability_val);

		$data['pay'] = ($task->pay == 1)?'Yes':'No';
		$data['email'] = ($task->email == 1)?'Yes':'No';

		$data['task_name'] = $task->task_name;
		$data['task_enumber'] = $task->task_enumber;
		$data['task_level'] = $task->task_level;
		$data['task_level_id'] = $task->task_level;

		if($task->task_level == 0)
		{
			$data['task_level'] = 'Nil';
		}
		else{
			$task_level = DB::table('p30_tasklevel')->where('id',$task->task_level)->first();
			$data['task_level'] = $task_level->name;
		}
	      
	      if($result->month_id == 1) { $next_month_name = "February"; }
          if($result->month_id == 2) { $next_month_name = "March"; }
          if($result->month_id == 3) { $next_month_name = "April"; }
          if($result->month_id == 4) { $next_month_name = "May"; }
          if($result->month_id == 5) { $next_month_name = "June"; }
          if($result->month_id == 6) { $next_month_name = "July"; }
          if($result->month_id == 7) { $next_month_name = "August"; }
          if($result->month_id == 8) { $next_month_name = "September"; }
          if($result->month_id == 9) { $next_month_name = "October"; }
          if($result->month_id == 10) { $next_month_name = "November"; }
          if($result->month_id == 11) { $next_month_name = "December"; }
          if($result->month_id == 12) { $next_month_name = "January"; }

          if($result->month_id == 1) { $month_name = "January"; }
          if($result->month_id == 2) { $month_name = "February"; }
          if($result->month_id == 3) { $month_name = "March"; }
          if($result->month_id == 4) { $month_name = "April"; }
          if($result->month_id == 5) { $month_name = "May"; }
          if($result->month_id == 6) { $month_name = "June"; }
          if($result->month_id == 7) { $month_name = "July"; }
          if($result->month_id == 8) { $month_name = "August"; }
          if($result->month_id == 9) { $month_name = "September"; }
          if($result->month_id == 10) { $month_name = "October"; }
          if($result->month_id == 11) { $month_name = "November"; }
          if($result->month_id == 12) { $month_name = "December"; }

            

		$data['period'] = $month_name;
		$data['next_period'] = $next_month_name;

		$contentmessage = view('user/paye_p30_email_content', $data)->render();
      	$subject = 'Easypayroll.ie: '.$task->task_name.' Paye MRS Submission';

	     echo json_encode(["html" => $contentmessage,"from" => $from, "to" => $to_email,'subject' => $subject,'last_email_sent' => $last_date]);
	}
	public function paye_p30_email_unsent_files()
	{
		$period_id = Input::get('task_id');
		$det_task = DB::table('paye_p30_periods')->where('period_id',$period_id)->first();
		$encoded_year_id = base64_encode($det_task->year_id);

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
			$date = date('Y-m-d H:i:s');
			DB::table('paye_p30_periods')->where('period_id',$period_id)->update(['last_email_sent' => $date]);

			$dateformat = date('d M Y @ H:i', strtotime($date));
			echo $dateformat;
			// return redirect('user/paye_p30_manage/'.$encoded_year_id.'?divid=taskidtr_'.$det_task->paye_task)->with('message', 'Email Sent Successfully');
		}
		else{
			echo "0";
			// return redirect('user/paye_p30_manage/'.$encoded_year_id.'?divid=taskidtr_'.$det_task->paye_task)->with('error', 'Email Field is empty so email is not sent');
		}
	}
}

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

		$pay3_task = DB::table('paye_p30_task')->where('paye_year',$year->year_id)->get();
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
		$ros_value = str_replace(",","",$ros_value);
		$ros_value = str_replace(",","",$ros_value);
		$ros_value = str_replace(",","",$ros_value);
		$ros_value = str_replace(",","",$ros_value);
		$ros_value = str_replace(",","",$ros_value);
		$ros_value = str_replace(",","",$ros_value);

		$id = Input::get('id');

		$details = DB::table('paye_p30_periods')->where('period_id', $id)->first();
		$different = $ros_value-$details->task_liability;
		
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
			$check_active_month = DB::table('paye_p30_periods')->where('paye_task', $details->id)->where('month_id',$details->active_month)->first();
			if(count($check_active_month))
			{
				if($week_from != "" && $week_to != "")
				{
					for($k=1; $k<=53; $k++){
						$selectweekval = 'week'.$k;
						$data['week'.$k] = ($check_active_month->$selectweekval)?$check_active_month->$selectweekval:"";
					}
				}
				if($month_from != "" && $month_to != "")
				{
					for($j=1; $j<=12; $j++){
						$selectmonthval = 'month'.$j;
						$data['month'.$j] = ($check_active_month->$selectmonthval)?$check_active_month->$selectmonthval:"";
					}
				}
			}
			else{
				for($k=1; $k<=53; $k++){
					$data['week'.$k] = '';
				}
				for($j=1; $j<=12; $j++){
					$data['month'.$j] = '';
				}
			}
			if($week_from != "" && $week_to != "")
			{
				for($i=$week_from; $i<=$week_to; $i++){
					$select_week = 'week'.$i;
					$paye_row = DB::table('paye_p30_periods')->where('paye_task', $details->id)->where('week'.$i,'!=','')->count();
					if($paye_row == 0)
					{
						$data[$select_week] = ($details->$select_week)?$details->$select_week:"";
					}
				}
			}
			if($month_from != "" && $month_to != "")
			{
				for($i=$month_from; $i<=$month_to; $i++){
					$select_month = 'month'.$i;
					$paye_row_month = DB::table('paye_p30_periods')->where('paye_task', $details->id)->where('month'.$i,'!=','')->count();
					if($paye_row_month == 0)
					{
						$data[$select_month] = ($details->$select_month)?$details->$select_month:"";
					}
				}
			}
			
			DB::table('paye_p30_periods')->where('paye_task', $details->id)->where('month_id',$details->active_month)->update($data);		
		}

		DB::update('UPDATE paye_p30_periods SET task_liability = (week1 + week2 + week3 + week4 + week5 + week6 + week7 + week8 + week9 + week10 + week11  + week12  + week13  + week14  + week15  + week16  + week17  + week18  + week19  + week20  + week21  + week22  + week23  + week24  + week25  + week26  + week27  + week28  + week29  + week30  + week31  + week32  + week33  + week34  + week35  + week36  + week37  + week38  + week39  + week40  + week41  + week42  + week43  + week44  + week45  + week46  + week47  + week48  + week49  + week50  + week51  + week52  + week53 + month1  + month2  + month3  + month4  + month5  + month6  + month7  + month8  + month9  + month10  + month11  + month12), liability_diff = ros_liability - (week1 + week2 + week3 + week4 + week5 + week6 + week7 + week8 + week9 + week10 + week11  + week12  + week13  + week14  + week15  + week16  + week17  + week18  + week19  + week20  + week21  + week22  + week23  + week24  + week25  + week26  + week27  + week28  + week29  + week30  + week31  + week32  + week33  + week34  + week35  + week36  + week37  + week38  + week39  + week40  + week41  + week42  + week43  + week44  + week45  + week46  + week47  + week48  + week49  + week50  + week51  + week52  + week53 + month1  + month2  + month3  + month4  + month5  + month6  + month7  + month8  + month9  + month10  + month11  + month12)  WHERE `year_id` = "'.$year_id.'"');

		$result = 'true';
		echo json_encode(array('result' => $result));

	}

	public function paye_p30_active_periods(){
		$week_from = Input::get('week_from');
		$week_to = Input::get('week_to');
		$month_from = Input::get('month_from');
		$month_to = Input::get('month_to');
		$year_id = Input::get('year_id');

		if($week_from != "" && $week_to != "")
		{
			if($week_from == 1) { $week_from = 1; } else { $week_from = $week_from - 1; }
			if($week_to == 53) { $week_to = 53; } else { $week_to = $week_to + 1; }
		}
		else{
			$week_from = 0;
			$week_to = 0;
		}
		if($month_from != "" && $month_to != "")
		{
			if($month_from == 1) { $month_from = 1; } else { $month_from = $month_from - 1; }
			if($month_to == 12) { $month_to = 12; } else { $month_to = $month_to + 1; }
		}
		else{
			$month_from = 0;
			$month_to = 0;
		}

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
		$year = Input::get('year');
		DB::table('paye_p30_task')->update(['active_month' => $active]);
		$data['active_month'] = $active;
		DB::table('paye_p30_year')->where('year_id',$year)->update($data);
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

		for($i=1; $i<=53; $i++) { $data['week'.$i] = '0'; $dataformat['week'.$i] = '-'; }
		for($i=1; $i<=12; $i++) { $data['month'.$i] = '0'; $dataformat['month'.$i] = '-'; }
		
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

				$dataformat['week'.$task_year->week] = (number_format_invoice($week_value) == 0 || number_format_invoice($week_value) == 0.00)?'-':number_format_invoice($week_value);
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
				$dataformat['month'.$task_year_month->month] = (number_format_invoice($month_value) == 0 || number_format_invoice($month_value) == 0.00)?'-':number_format_invoice($month_value);
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
			$dataformat['payep30_task'] = $task_id;
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
	public function load_table_info()
	{
		$task_id = Input::get('task_id');
		$year_id = Input::get('year_id');
		$output_row='';
		$periodlist = DB::table('paye_p30_periods')->where('paye_task', $task_id)->get();
		$task = DB::table('paye_p30_task')->where('id',$task_id)->first();

		$level_name = DB::table('p30_tasklevel')->where('id',$task->task_level)->first();

        if($task->task_level != 0){ $action = $level_name->name; }
        if($task->pay == 0){ $pay = 'No';}else{$pay = 'Yes';}
        if($task->email == 0){ $email = 'No';}else{$email = 'Yes';}

		$year = DB::table('paye_p30_year')->where('year_id',$year_id)->first();
        if(count($periodlist)){
            foreach ($periodlist as $period) { 
                if($task->active_month == $period->month_id){$month_active = 'checked';}else{$month_active = 'false';}

                if($period->month_id == 1) { $month_name = "Jan"; }
                if($period->month_id == 2) { $month_name = "Feb"; }
                if($period->month_id == 3) { $month_name = "Mar"; }
                if($period->month_id == 4) { $month_name = "Apr"; }
                if($period->month_id == 5) { $month_name = "May"; }
                if($period->month_id == 6) { $month_name = "Jun"; }
                if($period->month_id == 7) { $month_name = "Jul"; }
                if($period->month_id == 8) { $month_name = "Aug"; }
                if($period->month_id == 9) { $month_name = "Sep"; }
                if($period->month_id == 10) { $month_name = "Oct"; }
                if($period->month_id == 11) { $month_name = "Nov"; }
                if($period->month_id == 12) { $month_name = "Dec"; }                    

                if($period->week1 == 0){ 
                    $periodweek1 = '<div class="payp30_dash week1_class week1_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=1 && $year->week_to >=1) { $periodweek1.='hide_column_inner'; } else { $periodweek1.='show_column_inner'; } } $periodweek1.='">-</div>';
                }
                else{
                    $periodweek1 = '<a href="javascript:" class="payp30_green week1_class week1_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=1 && $year->week_to >=1) { $periodweek1.='hide_column_inner'; } else { $periodweek1.='show_column_inner'; } } $periodweek1.=' " value="'.$period->period_id.'" data-element="1">'.number_format_invoice($period->week1).'</a>';
                                       
                }

                if($period->week2 == 0){ 
                    $periodweek2 = '<div class="payp30_dash week2_class week2_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=2 && $year->week_to >=2) { $periodweek2.='hide_column_inner'; } else { $periodweek2.='show_column_inner'; } } $periodweek2.='">-</div>';
                    
                }
                else{
                    $periodweek2 = '<a href="javascript:" class="payp30_green week2_class week2_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=2 && $year->week_to >=2) { $periodweek2.='hide_column_inner'; } else { $periodweek2.='show_column_inner'; } } $periodweek2.='" value="'.$period->period_id.'" data-element="2">'.number_format_invoice($period->week2).'</a>';
                }

                if($period->week3 == 0){ 
                    $periodweek3 = '<div class="payp30_dash week3_class week3_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=3 && $year->week_to >=3) { $periodweek3.='hide_column_inner'; } else { $periodweek3.='show_column_inner'; } } $periodweek3.='">-</div>';
                }
                else{
                    $periodweek3 = '<a href="javascript:" class="payp30_green week3_class week3_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=3 && $year->week_to >=3) { $periodweek3.='hide_column_inner'; } else { $periodweek3.='show_column_inner'; } } $periodweek3.='"  value="'.$period->period_id.'" data-element="3">'.number_format_invoice($period->week3).'</a>';
                }

                if($period->week4 == 0){ 
                    $periodweek4 = '<div class="payp30_dash week4_class week4_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=4 && $year->week_to >=4) { $periodweek4.='hide_column_inner'; } else { $periodweek4.='show_column_inner'; } } $periodweek4.='">-</div>';
                }
                else{
                    $periodweek4 = '<a href="javascript:" class="payp30_green week4_class week4_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=4 && $year->week_to >=4) { $periodweek4.='hide_column_inner'; } else { $periodweek4.='show_column_inner'; } } $periodweek4.='"  value="'.$period->period_id.'" data-element="4">'.number_format_invoice($period->week4).'</a>';
                }

                if($period->week5 == 0){ 
                    $periodweek5 = '<div class="payp30_dash week5_class week5_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=5 && $year->week_to >=5) { $periodweek5.='hide_column_inner'; } else { $periodweek5.='show_column_inner'; } } $periodweek5.='">-</div>';
                }
                else{
                    $periodweek5 = '<a href="javascript:" class="payp30_green week5_class week5_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=5 && $year->week_to >=5) { $periodweek5.='hide_column_inner'; } else { $periodweek5.='show_column_inner'; } } $periodweek5.='"  value="'.$period->period_id.'" data-element="5">'.number_format_invoice($period->week5).'</a>';
                }

                if($period->week6 == 0){ 
                    $periodweek6 = '<div class="payp30_dash week6_class week6_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=6 && $year->week_to >=6) { $periodweek6.='hide_column_inner'; } else { $periodweek6.='show_column_inner'; } } $periodweek6.='">-</div>';
                }
                else{
                    $periodweek6 = '<a href="javascript:" class="payp30_green week6_class week6_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=6 && $year->week_to >=6) { $periodweek6.='hide_column_inner'; } else { $periodweek6.='show_column_inner'; } } $periodweek6.='"  value="'.$period->period_id.'" data-element="6">'.number_format_invoice($period->week6).'</a>';
                }

                if($period->week7 == 0){ 
                    $periodweek7 = '<div class="payp30_dash week7_class week7_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=7 && $year->week_to >=7) { $periodweek7.='hide_column_inner'; } else { $periodweek7.='show_column_inner'; } } $periodweek7.='">-</div>';
                }
                else{
                    $periodweek7 = '<a href="javascript:" class="payp30_green week7_class week7_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=7 && $year->week_to >=7) { $periodweek7.='hide_column_inner'; } else { $periodweek7.='show_column_inner'; } } $periodweek7.='"  value="'.$period->period_id.'" data-element="7">'.number_format_invoice($period->week7).'</a>';
                }

                if($period->week8 == 0){ 
                    $periodweek8 = '<div class="payp30_dash week8_class week8_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=8 && $year->week_to >=8) { $periodweek8.='hide_column_inner'; } else { $periodweek8.='show_column_inner'; } } $periodweek8.='">-</div>';
                }
                else{
                    $periodweek8 = '<a href="javascript:" class="payp30_green week8_class week8_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=8 && $year->week_to >=8) { $periodweek8.='hide_column_inner'; } else { $periodweek8.='show_column_inner'; } } $periodweek8.='"  value="'.$period->period_id.'" data-element="8">'.number_format_invoice($period->week8).'</a>';
                }

                if($period->week9 == 0){ 
                    $periodweek9 = '<div class="payp30_dash week9_class week9_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=9 && $year->week_to >=9) { $periodweek9.='hide_column_inner'; } else { $periodweek9.='show_column_inner'; } } $periodweek9.='">-</div>';
                }
                else{
                    $periodweek9 = '<a href="javascript:" class="payp30_green week9_class week9_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=9 && $year->week_to >=9) { $periodweek9.='hide_column_inner'; } else { $periodweek9.='show_column_inner'; } } $periodweek9.='"  value="'.$period->period_id.'" data-element="9">'.number_format_invoice($period->week9).'</a>';
                }

                if($period->week10 == 0){ 
                    $periodweek10 = '<div class="payp30_dash week10_class week10_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=10 && $year->week_to >=10) { $periodweek10.='hide_column_inner'; } else { $periodweek10.='show_column_inner'; } } $periodweek10.='">-</div>';
                }
                else{
                    $periodweek10 = '<a href="javascript:" class="payp30_green week10_class week10_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=10 && $year->week_to >=10) { $periodweek10.='hide_column_inner'; } else { $periodweek10.='show_column_inner'; } } $periodweek10.='"  value="'.$period->period_id.'" data-element="10">'.number_format_invoice($period->week10).'</a>';
                }

                if($period->week11 == 0){ 
                    $periodweek11 = '<div class="payp30_dash week11_class week11_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=11 && $year->week_to >=11) { $periodweek11.='hide_column_inner'; } else { $periodweek11.='show_column_inner'; } } $periodweek11.='">-</div>';
                }
                else{
                    $periodweek11 = '<a href="javascript:" class="payp30_green week11_class week11_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=11 && $year->week_to >=11) { $periodweek11.='hide_column_inner'; } else { $periodweek11.='show_column_inner'; } } $periodweek11.='"  value="'.$period->period_id.'" data-element="11">'.number_format_invoice($period->week11).'</a>';
                }

                if($period->week12 == 0){ 
                    $periodweek12 = '<div class="payp30_dash week12_class week12_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=12 && $year->week_to >=12) { $periodweek12.='hide_column_inner'; } else { $periodweek12.='show_column_inner'; } } $periodweek12.='">-</div>';
                }
                else{
                    $periodweek12 = '<a href="javascript:" class="payp30_green week12_class week12_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=12 && $year->week_to >=12) { $periodweek12.='hide_column_inner'; } else { $periodweek12.='show_column_inner'; } } $periodweek12.='"  value="'.$period->period_id.'" data-element="12">'.number_format_invoice($period->week12).'</a>';
                }

                if($period->week13 == 0){ 
                    $periodweek13 = '<div class="payp30_dash week13_class week13_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=13 && $year->week_to >=13) { $periodweek13.='hide_column_inner'; } else { $periodweek13.='show_column_inner'; } } $periodweek13.='">-</div>';
                }
                else{
                    $periodweek13 = '<a href="javascript:" class="payp30_green week13_class week13_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=13 && $year->week_to >=13) { $periodweek13.='hide_column_inner'; } else { $periodweek13.='show_column_inner'; } } $periodweek13.='"  value="'.$period->period_id.'" data-element="13">'.number_format_invoice($period->week13).'</a>';
                }

                if($period->week14 == 0){ 
                    $periodweek14 = '<div class="payp30_dash week14_class week14_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=14 && $year->week_to >=14) { $periodweek14.='hide_column_inner'; } else { $periodweek14.='show_column_inner'; } } $periodweek14.='">-</div>';
                }
                else{
                    $periodweek14 = '<a href="javascript:" class="payp30_green week14_class week14_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=14 && $year->week_to >=14) { $periodweek14.='hide_column_inner'; } else { $periodweek14.='show_column_inner'; } } $periodweek14.='"  value="'.$period->period_id.'" data-element="14">'.number_format_invoice($period->week14).'</a>';
                }

                if($period->week15 == 0){ 
                    $periodweek15 = '<div class="payp30_dash week15_class week15_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=15 && $year->week_to >=15) { $periodweek15.='hide_column_inner'; } else { $periodweek15.='show_column_inner'; } } $periodweek15.='">-</div>';
                }
                else{
                    $periodweek15 = '<a href="javascript:" class="payp30_green week15_class week15_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=15 && $year->week_to >=15) { $periodweek15.='hide_column_inner'; } else { $periodweek15.='show_column_inner'; } } $periodweek15.='"  value="'.$period->period_id.'" data-element="15">'.number_format_invoice($period->week15).'</a>';
                }

                if($period->week16 == 0){ 
                    $periodweek16 = '<div class="payp30_dash week16_class week16_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=16 && $year->week_to >=16) { $periodweek16.='hide_column_inner'; } else { $periodweek16.='show_column_inner'; } } $periodweek16.='">-</div>';
                }
                else{
                    $periodweek16 = '<a href="javascript:" class="payp30_green week16_class week16_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=16 && $year->week_to >=16) { $periodweek16.='hide_column_inner'; } else { $periodweek16.='show_column_inner'; } } $periodweek16.='"  value="'.$period->period_id.'" data-element="16">'.number_format_invoice($period->week16).'</a>';
                }

                if($period->week17 == 0){ 
                    $periodweek17 = '<div class="payp30_dash week17_class week17_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=17 && $year->week_to >=17) { $periodweek17.='hide_column_inner'; } else { $periodweek17.='show_column_inner'; } } $periodweek17.='">-</div>';
                }
                else{
                    $periodweek17 = '<a href="javascript:" class="payp30_green week17_class week17_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=17 && $year->week_to >=17) { $periodweek17.='hide_column_inner'; } else { $periodweek17.='show_column_inner'; } } $periodweek17.='"  value="'.$period->period_id.'" data-element="17">'.number_format_invoice($period->week17).'</a>';
                }

                if($period->week18 == 0){ 
                    $periodweek18 = '<div class="payp30_dash week18_class week18_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=18 && $year->week_to >=18) { $periodweek18.='hide_column_inner'; } else { $periodweek18.='show_column_inner'; } } $periodweek18.='">-</div>';
                }
                else{
                    $periodweek18 = '<a href="javascript:" class="payp30_green week18_class week18_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=18 && $year->week_to >=18) { $periodweek18.='hide_column_inner'; } else { $periodweek18.='show_column_inner'; } } $periodweek18.='"  value="'.$period->period_id.'" data-element="18">'.number_format_invoice($period->week18).'</a>';
                }

                if($period->week19 == 0){ 
                    $periodweek19 = '<div class="payp30_dash week19_class week19_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=19 && $year->week_to >=19) { $periodweek19.='hide_column_inner'; } else { $periodweek19.='show_column_inner'; } } $periodweek19.='">-</div>';
                }
                else{
                    $periodweek19 = '<a href="javascript:" class="payp30_green week19_class week19_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=19 && $year->week_to >=19) { $periodweek19.='hide_column_inner'; } else { $periodweek19.='show_column_inner'; } } $periodweek19.='"  value="'.$period->period_id.'" data-element="19">'.number_format_invoice($period->week19).'</a>';
                }

                if($period->week20 == 0){ 
                    $periodweek20 = '<div class="payp30_dash week20_class week20_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=20 && $year->week_to >=20) { $periodweek20.='hide_column_inner'; } else { $periodweek20.='show_column_inner'; } } $periodweek20.='">-</div>';
                }
                else{
                    $periodweek20 = '<a href="javascript:" class="payp30_green week20_class week20_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=20 && $year->week_to >=20) { $periodweek20.='hide_column_inner'; } else { $periodweek20.='show_column_inner'; } } $periodweek20.='"  value="'.$period->period_id.'" data-element="20">'.number_format_invoice($period->week20).'</a>';
                }

                if($period->week21 == 0){ 
                    $periodweek21 = '<div class="payp30_dash week21_class week21_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=21 && $year->week_to >=21) { $periodweek21.='hide_column_inner'; } else { $periodweek21.='show_column_inner'; } } $periodweek21.='">-</div>';
                }
                else{
                    $periodweek21 = '<a href="javascript:" class="payp30_green week21_class week21_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=21 && $year->week_to >=21) { $periodweek21.='hide_column_inner'; } else { $periodweek21.='show_column_inner'; } } $periodweek21.='"  value="'.$period->period_id.'" data-element="21">'.number_format_invoice($period->week21).'</a>';
                }

                if($period->week22 == 0){ 
                    $periodweek22 = '<div class="payp30_dash week22_class week22_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=22 && $year->week_to >=22) { $periodweek22.='hide_column_inner'; } else { $periodweek22.='show_column_inner'; } } $periodweek22.='">-</div>';
                }
                else{
                    $periodweek22 = '<a href="javascript:" class="payp30_green week22_class week22_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=22 && $year->week_to >=22) { $periodweek22.='hide_column_inner'; } else { $periodweek22.='show_column_inner'; } } $periodweek22.='"  value="'.$period->period_id.'" data-element="22">'.number_format_invoice($period->week22).'</a>';
                }

                if($period->week23 == 0){ 
                    $periodweek23 = '<div class="payp30_dash week23_class week23_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=23 && $year->week_to >=23) { $periodweek23.='hide_column_inner'; } else { $periodweek23.='show_column_inner'; } } $periodweek23.='">-</div>';
                }
                else{
                    $periodweek23 = '<a href="javascript:" class="payp30_green week23_class week23_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=23 && $year->week_to >=23) { $periodweek23.='hide_column_inner'; } else { $periodweek23.='show_column_inner'; } } $periodweek23.='"  value="'.$period->period_id.'" data-element="23">'.number_format_invoice($period->week23).'</a>';
                }

                if($period->week24 == 0){ 
                    $periodweek24 = '<div class="payp30_dash week24_class week24_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=24 && $year->week_to >=24) { $periodweek24.='hide_column_inner'; } else { $periodweek24.='show_column_inner'; } } $periodweek24.='">-</div>';
                }
                else{
                    $periodweek24 = '<a href="javascript:" class="payp30_green week24_class week24_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=24 && $year->week_to >=24) { $periodweek24.='hide_column_inner'; } else { $periodweek24.='show_column_inner'; } } $periodweek24.='"  value="'.$period->period_id.'" data-element="24">'.number_format_invoice($period->week24).'</a>';
                }

                if($period->week25 == 0){ 
                    $periodweek25 = '<div class="payp30_dash week25_class week25_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=25 && $year->week_to >=25) { $periodweek25.='hide_column_inner'; } else { $periodweek25.='show_column_inner'; } } $periodweek25.='">-</div>';
                }
                else{
                    $periodweek25 = '<a href="javascript:" class="payp30_green week25_class week25_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=25 && $year->week_to >=25) { $periodweek25.='hide_column_inner'; } else { $periodweek25.='show_column_inner'; } } $periodweek25.='"  value="'.$period->period_id.'" data-element="25">'.number_format_invoice($period->week25).'</a>';
                }

                if($period->week26 == 0){ 
                    $periodweek26 = '<div class="payp30_dash week26_class week26_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=26 && $year->week_to >=26) { $periodweek26.='hide_column_inner'; } else { $periodweek26.='show_column_inner'; } } $periodweek26.='">-</div>';
                }
                else{
                    $periodweek26 = '<a href="javascript:" class="payp30_green week26_class week26_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=26 && $year->week_to >=26) { $periodweek26.='hide_column_inner'; } else { $periodweek26.='show_column_inner'; } } $periodweek26.='"  value="'.$period->period_id.'" data-element="26">'.number_format_invoice($period->week26).'</a>';
                }

                if($period->week27 == 0){ 
                    $periodweek27 = '<div class="payp30_dash week27_class week27_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=27 && $year->week_to >=27) { $periodweek27.='hide_column_inner'; } else { $periodweek27.='show_column_inner'; } } $periodweek27.='">-</div>';
                }
                else{
                    $periodweek27 = '<a href="javascript:" class="payp30_green week27_class week27_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=27 && $year->week_to >=27) { $periodweek27.='hide_column_inner'; } else { $periodweek27.='show_column_inner'; } } $periodweek27.='"  value="'.$period->period_id.'" data-element="27">'.number_format_invoice($period->week27).'</a>';
                }

                if($period->week28 == 0){ 
                    $periodweek28 = '<div class="payp30_dash week28_class week28_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=28 && $year->week_to >=28) { $periodweek28.='hide_column_inner'; } else { $periodweek28.='show_column_inner'; } } $periodweek28.='">-</div>';
                }
                else{
                    $periodweek28 = '<a href="javascript:" class="payp30_green week28_class week28_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=28 && $year->week_to >=28) { $periodweek28.='hide_column_inner'; } else { $periodweek28.='show_column_inner'; } } $periodweek28.='"  value="'.$period->period_id.'" data-element="28">'.number_format_invoice($period->week28).'</a>';
                }

                if($period->week29 == 0){ 
                    $periodweek29 = '<div class="payp30_dash week29_class week29_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=29 && $year->week_to >=29) { $periodweek29.='hide_column_inner'; } else { $periodweek29.='show_column_inner'; } } $periodweek29.='">-</div>';
                }
                else{
                    $periodweek29 = '<a href="javascript:" class="payp30_green week29_class week29_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=29 && $year->week_to >=29) { $periodweek29.='hide_column_inner'; } else { $periodweek29.='show_column_inner'; } } $periodweek29.='"  value="'.$period->period_id.'" data-element="29">'.number_format_invoice($period->week29).'</a>';
                }

                if($period->week30 == 0){ 
                    $periodweek30 = '<div class="payp30_dash week30_class week30_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=30 && $year->week_to >=30) { $periodweek30.='hide_column_inner'; } else { $periodweek30.='show_column_inner'; } } $periodweek30.='">-</div>';
                }
                else{
                    $periodweek30 = '<a href="javascript:" class="payp30_green week30_class week30_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=30 && $year->week_to >=30) { $periodweek30.='hide_column_inner'; } else { $periodweek30.='show_column_inner'; } } $periodweek30.='"  value="'.$period->period_id.'" data-element="30">'.number_format_invoice($period->week30).'</a>';
                }

                if($period->week31 == 0){ 
                    $periodweek31 = '<div class="payp30_dash week31_class week31_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=31 && $year->week_to >=31) { $periodweek31.='hide_column_inner'; } else { $periodweek31.='show_column_inner'; } } $periodweek31.='">-</div>';
                }
                else{
                    $periodweek31 = '<a href="javascript:" class="payp30_green week31_class week31_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=31 && $year->week_to >=31) { $periodweek31.='hide_column_inner'; } else { $periodweek31.='show_column_inner'; } } $periodweek31.='"  value="'.$period->period_id.'" data-element="31">'.number_format_invoice($period->week31).'</a>';
                }

                if($period->week32 == 0){ 
                    $periodweek32 = '<div class="payp30_dash week32_class week32_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=32 && $year->week_to >=32) { $periodweek32.='hide_column_inner'; } else { $periodweek32.='show_column_inner'; } } $periodweek32.='">-</div>';
                }
                else{
                    $periodweek32 = '<a href="javascript:" class="payp30_green week32_class week32_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=32 && $year->week_to >=32) { $periodweek32.='hide_column_inner'; } else { $periodweek32.='show_column_inner'; } } $periodweek32.='"  value="'.$period->period_id.'" data-element="32">'.number_format_invoice($period->week32).'</a>';
                }

                if($period->week33 == 0){ 
                    $periodweek33 = '<div class="payp30_dash week33_class week33_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=33 && $year->week_to >=33) { $periodweek33.='hide_column_inner'; } else { $periodweek33.='show_column_inner'; } } $periodweek33.='">-</div>';
                }
                else{
                    $periodweek33 = '<a href="javascript:" class="payp30_green week33_class week33_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=33 && $year->week_to >=33) { $periodweek33.='hide_column_inner'; } else { $periodweek33.='show_column_inner'; } } $periodweek33.='"  value="'.$period->period_id.'" data-element="33">'.number_format_invoice($period->week33).'</a>';
                }

                if($period->week34 == 0){ 
                    $periodweek34 = '<div class="payp30_dash week34_class week34_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=34 && $year->week_to >=34) { $periodweek34.='hide_column_inner'; } else { $periodweek34.='show_column_inner'; } } $periodweek34.='">-</div>';
                }
                else{
                    $periodweek34 = '<a href="javascript:" class="payp30_green week34_class week34_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=34 && $year->week_to >=34) { $periodweek34.='hide_column_inner'; } else { $periodweek34.='show_column_inner'; } } $periodweek34.='"  value="'.$period->period_id.'" data-element="34">'.number_format_invoice($period->week34).'</a>';
                }

                if($period->week35 == 0){ 
                    $periodweek35 = '<div class="payp30_dash week35_class week35_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=35 && $year->week_to >=35) { $periodweek35.='hide_column_inner'; } else { $periodweek35.='show_column_inner'; } } $periodweek35.='">-</div>';
                }
                else{
                    $periodweek35 = '<a href="javascript:" class="payp30_green week35_class week35_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=35 && $year->week_to >=35) { $periodweek35.='hide_column_inner'; } else { $periodweek35.='show_column_inner'; } } $periodweek35.='"  value="'.$period->period_id.'" data-element="35">'.number_format_invoice($period->week35).'</a>';
                }

                if($period->week36 == 0){ 
                    $periodweek36 = '<div class="payp30_dash week36_class week36_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=36 && $year->week_to >=36) { $periodweek36.='hide_column_inner'; } else { $periodweek36.='show_column_inner'; } } $periodweek36.='">-</div>';
                }
                else{
                    $periodweek36 = '<a href="javascript:" class="payp30_green week36_class week36_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=36 && $year->week_to >=36) { $periodweek36.='hide_column_inner'; } else { $periodweek36.='show_column_inner'; } } $periodweek36.='"  value="'.$period->period_id.'" data-element="36">'.number_format_invoice($period->week36).'</a>';
                }

                if($period->week37 == 0){ 
                    $periodweek37 = '<div class="payp30_dash week37_class week37_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=37 && $year->week_to >=37) { $periodweek37.='hide_column_inner'; } else { $periodweek37.='show_column_inner'; } } $periodweek37.='">-</div>';
                }
                else{
                    $periodweek37 = '<a href="javascript:" class="payp30_green week37_class week37_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=37 && $year->week_to >=37) { $periodweek37.='hide_column_inner'; } else { $periodweek37.='show_column_inner'; } } $periodweek37.='"  value="'.$period->period_id.'" data-element="37">'.number_format_invoice($period->week37).'</a>';
                }

                if($period->week38 == 0){ 
                    $periodweek38 = '<div class="payp30_dash week38_class week38_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=38 && $year->week_to >=38) { $periodweek38.='hide_column_inner'; } else { $periodweek38.='show_column_inner'; } } $periodweek38.='">-</div>';
                }
                else{
                    $periodweek38 = '<a href="javascript:" class="payp30_green week38_class week38_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=38 && $year->week_to >=38) { $periodweek38.='hide_column_inner'; } else { $periodweek38.='show_column_inner'; } } $periodweek38.='"  value="'.$period->period_id.'" data-element="38">'.number_format_invoice($period->week38).'</a>';
                }

                if($period->week39 == 0){ 
                    $periodweek39 = '<div class="payp30_dash week39_class week39_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=39 && $year->week_to >=39) { $periodweek39.='hide_column_inner'; } else { $periodweek39.='show_column_inner'; } } $periodweek39.='">-</div>';
                }
                else{
                    $periodweek39 = '<a href="javascript:" class="payp30_green week39_class week39_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=39 && $year->week_to >=39) { $periodweek39.='hide_column_inner'; } else { $periodweek39.='show_column_inner'; } } $periodweek39.='"  value="'.$period->period_id.'" data-element="39">'.number_format_invoice($period->week39).'</a>';
                }

                if($period->week40 == 0){ 
                    $periodweek40 = '<div class="payp30_dash week40_class week40_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=40 && $year->week_to >=40) { $periodweek40.='hide_column_inner'; } else { $periodweek40.='show_column_inner'; } } $periodweek40.='">-</div>';
                }
                else{
                    $periodweek40 = '<a href="javascript:" class="payp30_green week40_class week40_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=40 && $year->week_to >=40) { $periodweek40.='hide_column_inner'; } else { $periodweek40.='show_column_inner'; } } $periodweek40.='"  value="'.$period->period_id.'" data-element="40">'.number_format_invoice($period->week40).'</a>';
                }

                if($period->week41 == 0){ 
                    $periodweek41 = '<div class="payp30_dash week41_class week41_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=41 && $year->week_to >=41) { $periodweek41.='hide_column_inner'; } else { $periodweek41.='show_column_inner'; } } $periodweek41.='">-</div>';
                }
                else{
                    $periodweek41 = '<a href="javascript:" class="payp30_green week41_class week41_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=41 && $year->week_to >=41) { $periodweek41.='hide_column_inner'; } else { $periodweek41.='show_column_inner'; } } $periodweek41.='"  value="'.$period->period_id.'" data-element="41">'.number_format_invoice($period->week41).'</a>';
                }

                if($period->week42 == 0){ 
                    $periodweek42 = '<div class="payp30_dash week42_class week42_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=42 && $year->week_to >=42) { $periodweek42.='hide_column_inner'; } else { $periodweek42.='show_column_inner'; } } $periodweek42.='">-</div>';
                }
                else{
                    $periodweek42 = '<a href="javascript:" class="payp30_green week42_class week42_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=42 && $year->week_to >=42) { $periodweek42.='hide_column_inner'; } else { $periodweek42.='show_column_inner'; } } $periodweek42.='"  value="'.$period->period_id.'" data-element="42">'.number_format_invoice($period->week42).'</a>';
                }

                if($period->week43 == 0){ 
                    $periodweek43 = '<div class="payp30_dash week43_class week43_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=43 && $year->week_to >=43) { $periodweek43.='hide_column_inner'; } else { $periodweek43.='show_column_inner'; } } $periodweek43.='">-</div>';
                }
                else{
                    $periodweek43 = '<a href="javascript:" class="payp30_green week43_class week43_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=43 && $year->week_to >=43) { $periodweek43.='hide_column_inner'; } else { $periodweek43.='show_column_inner'; } } $periodweek43.='"  value="'.$period->period_id.'" data-element="43">'.number_format_invoice($period->week43).'</a>';
                }

                if($period->week44 == 0){ 
                    $periodweek44 = '<div class="payp30_dash week44_class week44_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=44 && $year->week_to >=44) { $periodweek44.='hide_column_inner'; } else { $periodweek44.='show_column_inner'; } } $periodweek44.='">-</div>';
                }
                else{
                    $periodweek44 = '<a href="javascript:" class="payp30_green week44_class week44_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=44 && $year->week_to >=44) { $periodweek44.='hide_column_inner'; } else { $periodweek44.='show_column_inner'; } } $periodweek44.='"  value="'.$period->period_id.'" data-element="44">'.number_format_invoice($period->week44).'</a>';
                }

                if($period->week45 == 0){ 
                    $periodweek45 = '<div class="payp30_dash week45_class week45_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=45 && $year->week_to >=45) { $periodweek45.='hide_column_inner'; } else { $periodweek45.='show_column_inner'; } } $periodweek45.='">-</div>';
                }
                else{
                    $periodweek45 = '<a href="javascript:" class="payp30_green week45_class week45_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=45 && $year->week_to >=45) { $periodweek45.='hide_column_inner'; } else { $periodweek45.='show_column_inner'; } } $periodweek45.='"  value="'.$period->period_id.'" data-element="45">'.number_format_invoice($period->week45).'</a>';
                }

                if($period->week46 == 0){ 
                    $periodweek46 = '<div class="payp30_dash week46_class week46_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=46 && $year->week_to >=46) { $periodweek46.='hide_column_inner'; } else { $periodweek46.='show_column_inner'; } } $periodweek46.='">-</div>';
                }
                else{
                    $periodweek46 = '<a href="javascript:" class="payp30_green week46_class week46_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=46 && $year->week_to >=46) { $periodweek46.='hide_column_inner'; } else { $periodweek46.='show_column_inner'; } } $periodweek46.='"  value="'.$period->period_id.'" data-element="46">'.number_format_invoice($period->week46).'</a>';
                }

                if($period->week47 == 0){ 
                    $periodweek47 = '<div class="payp30_dash week47_class week47_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=47 && $year->week_to >=47) { $periodweek47.='hide_column_inner'; } else { $periodweek47.='show_column_inner'; } } $periodweek47.='">-</div>';
                }
                else{
                    $periodweek47 = '<a href="javascript:" class="payp30_green week47_class week47_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=47 && $year->week_to >=47) { $periodweek47.='hide_column_inner'; } else { $periodweek47.='show_column_inner'; } } $periodweek47.='"  value="'.$period->period_id.'" data-element="47">'.number_format_invoice($period->week47).'</a>';
                }

                if($period->week48 == 0){ 
                    $periodweek48 = '<div class="payp30_dash week48_class week48_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=48 && $year->week_to >=48) { $periodweek48.='hide_column_inner'; } else { $periodweek48.='show_column_inner'; } } $periodweek48.='">-</div>';
                }
                else{
                    $periodweek48 = '<a href="javascript:" class="payp30_green week48_class week48_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=48 && $year->week_to >=48) { $periodweek48.='hide_column_inner'; } else { $periodweek48.='show_column_inner'; } } $periodweek48.='"  value="'.$period->period_id.'" data-element="48">'.number_format_invoice($period->week48).'</a>';
                }

                if($period->week49 == 0){ 
                    $periodweek49 = '<div class="payp30_dash week49_class week49_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=49 && $year->week_to >=49) { $periodweek49.='hide_column_inner'; } else { $periodweek49.='show_column_inner'; } } $periodweek49.='">-</div>';
                }
                else{
                    $periodweek49 = '<a href="javascript:" class="payp30_green week49_class week49_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=49 && $year->week_to >=49) { $periodweek49.='hide_column_inner'; } else { $periodweek49.='show_column_inner'; } } $periodweek49.='"  value="'.$period->period_id.'" data-element="49">'.number_format_invoice($period->week49).'</a>';
                }

                if($period->week50 == 0){ 
                    $periodweek50 = '<div class="payp30_dash week50_class week50_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=50 && $year->week_to >=50) { $periodweek50.='hide_column_inner'; } else { $periodweek50.='show_column_inner'; } } $periodweek50.='">-</div>';
                }
                else{
                    $periodweek50 = '<a href="javascript:" class="payp30_green week50_class week50_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=50 && $year->week_to >=50) { $periodweek50.='hide_column_inner'; } else { $periodweek50.='show_column_inner'; } } $periodweek50.='"  value="'.$period->period_id.'" data-element="50">'.number_format_invoice($period->week50).'</a>';
                }

                if($period->week51 == 0){ 
                    $periodweek51 = '<div class="payp30_dash week51_class week51_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=51 && $year->week_to >=51) { $periodweek51.='hide_column_inner'; } else { $periodweek51.='show_column_inner'; } } $periodweek51.='">-</div>';
                }
                else{
                    $periodweek51 = '<a href="javascript:" class="payp30_green week51_class week51_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=51 && $year->week_to >=51) { $periodweek51.='hide_column_inner'; } else { $periodweek51.='show_column_inner'; } } $periodweek51.='"  value="'.$period->period_id.'" data-element="51">'.number_format_invoice($period->week51).'</a>';
                }

                if($period->week52 == 0){ 
                    $periodweek52 = '<div class="payp30_dash week52_class week52_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=52 && $year->week_to >=52) { $periodweek52.='hide_column_inner'; } else { $periodweek52.='show_column_inner'; } } $periodweek52.='">-</div>';
                }
                else{
                    $periodweek52 = '<a href="javascript:" class="payp30_green week52_class week52_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=52 && $year->week_to >=52) { $periodweek52.='hide_column_inner'; } else { $periodweek52.='show_column_inner'; } } $periodweek52.='"  value="'.$period->period_id.'" data-element="52">'.number_format_invoice($period->week52).'</a>';
                }

                if($period->week53 == 0){ 
                    $periodweek53 = '<div class="payp30_dash week53_class week53_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=53 && $year->week_to >=53) { $periodweek53.='hide_column_inner'; } else { $periodweek53.='show_column_inner'; } } $periodweek53.='">-</div>';
                }
                else{
                    $periodweek53 = '<a href="javascript:" class="payp30_green week53_class week53_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=53 && $year->week_to >=53) { $periodweek53.='hide_column_inner'; } else { $periodweek53.='show_column_inner'; } } $periodweek53.='"  value="'.$period->period_id.'" data-element="53">'.number_format_invoice($period->week53).'</a>';
                }



                if($period->month1 == 0){ 
                    $periodmonth1 = '<div class="payp30_dash month1_class month1_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->month_from <=1 && $year->month_to >=1) { $periodmonth1.='hide_column_inner'; } else { $periodmonth1.='show_column_inner'; } } $periodmonth1.='">-</div>';
                }
                else{
                    $periodmonth1 = '<a href="javascript:" class="payp30_green month1_class month1_class_'.$period->period_id.' month_remove '; if($year->show_active == 1) { if($year->month_from <=1 && $year->month_to >=1) { $periodmonth1.='hide_column_inner'; } else { $periodmonth1.='show_column_inner'; } } $periodmonth1.='"  value="'.$period->period_id.'" data-element="1">'.number_format_invoice($period->month1).'</a>';
                }

                if($period->month2 == 0){ 
                    $periodmonth2 = '<div class="payp30_dash month2_class month2_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->month_from <=2 && $year->month_to >=2) { $periodmonth2.='hide_column_inner'; } else { $periodmonth2.='show_column_inner'; } } $periodmonth2.='">-</div>';
                }
                else{
                    $periodmonth2 = '<a href="javascript:" class="payp30_green month2_class month2_class_'.$period->period_id.' month_remove '; if($year->show_active == 1) { if($year->month_from <=2 && $year->month_to >=2) { $periodmonth2.='hide_column_inner'; } else { $periodmonth2.='show_column_inner'; } } $periodmonth2.='"  value="'.$period->period_id.'" data-element="2">'.number_format_invoice($period->month2).'</a>';
                }

                if($period->month3 == 0){ 
                    $periodmonth3 = '<div class="payp30_dash month3_class month3_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->month_from <=3 && $year->month_to >=3) { $periodmonth3.='hide_column_inner'; } else { $periodmonth3.='show_column_inner'; } } $periodmonth3.='">-</div>';
                }
                else{
                    $periodmonth3 = '<a href="javascript:" class="payp30_green month3_class month3_class_'.$period->period_id.' month_remove '; if($year->show_active == 1) { if($year->month_from <=3 && $year->month_to >=3) { $periodmonth3.='hide_column_inner'; } else { $periodmonth3.='show_column_inner'; } } $periodmonth3.='"  value="'.$period->period_id.'" data-element="3">'.number_format_invoice($period->month3).'</a>';
                }

                if($period->month4 == 0){ 
                    $periodmonth4 = '<div class="payp30_dash month4_class month4_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->month_from <=4 && $year->month_to >=4) { $periodmonth4.='hide_column_inner'; } else { $periodmonth4.='show_column_inner'; } } $periodmonth4.='">-</div>';
                }
                else{
                    $periodmonth4 = '<a href="javascript:" class="payp30_green month4_class month4_class_'.$period->period_id.' month_remove '; if($year->show_active == 1) { if($year->month_from <=4 && $year->month_to >=4) { $periodmonth4.='hide_column_inner'; } else { $periodmonth4.='show_column_inner'; } } $periodmonth4.='"  value="'.$period->period_id.'" data-element="4">'.number_format_invoice($period->month4).'</a>';
                }

                if($period->month5 == 0){ 
                    $periodmonth5 = '<div class="payp30_dash month5_class month5_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->month_from <=5 && $year->month_to >=5) { $periodmonth5.='hide_column_inner'; } else { $periodmonth5.='show_column_inner'; } } $periodmonth5.='">-</div>';
                }
                else{
                    $periodmonth5 = '<a href="javascript:" class="payp30_green month5_class month5_class_'.$period->period_id.' month_remove '; if($year->show_active == 1) { if($year->month_from <=5 && $year->month_to >=5) { $periodmonth5.='hide_column_inner'; } else { $periodmonth5.='show_column_inner'; } } $periodmonth5.='"  value="'.$period->period_id.'" data-element="5">'.number_format_invoice($period->month5).'</a>';
                }

                if($period->month6 == 0){ 
                    $periodmonth6 = '<div class="payp30_dash month6_class month6_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->month_from <=6 && $year->month_to >=6) { $periodmonth6.='hide_column_inner'; } else { $periodmonth6.='show_column_inner'; } } $periodmonth6.='">-</div>';
                }
                else{
                    $periodmonth6 = '<a href="javascript:" class="payp30_green month6_class month6_class_'.$period->period_id.' month_remove '; if($year->show_active == 1) { if($year->month_from <=6 && $year->month_to >=6) { $periodmonth6.='hide_column_inner'; } else { $periodmonth6.='show_column_inner'; } } $periodmonth6.='"  value="'.$period->period_id.'" data-element="6">'.number_format_invoice($period->month6).'</a>';
                }

                if($period->month7 == 0){ 
                    $periodmonth7 = '<div class="payp30_dash month7_class month7_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->month_from <=7 && $year->month_to >=7) { $periodmonth7.='hide_column_inner'; } else { $periodmonth7.='show_column_inner'; } } $periodmonth7.='">-</div>';
                }
                else{
                    $periodmonth7 = '<a href="javascript:" class="payp30_green month7_class month7_class_'.$period->period_id.' month_remove '; if($year->show_active == 1) { if($year->month_from <=7 && $year->month_to >=7) { $periodmonth7.='hide_column_inner'; } else { $periodmonth7.='show_column_inner'; } } $periodmonth7.='"  value="'.$period->period_id.'" data-element="7">'.number_format_invoice($period->month7).'</a>';
                }

                if($period->month8 == 0){ 
                    $periodmonth8 = '<div class="payp30_dash month8_class month8_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->month_from <=8 && $year->month_to >=8) { $periodmonth8.='hide_column_inner'; } else { $periodmonth8.='show_column_inner'; } } $periodmonth8.='">-</div>';
                }
                else{
                    $periodmonth8 = '<a href="javascript:" class="payp30_green month8_class month8_class_'.$period->period_id.' month_remove '; if($year->show_active == 1) { if($year->month_from <=8 && $year->month_to >=8) { $periodmonth8.='hide_column_inner'; } else { $periodmonth8.='show_column_inner'; } } $periodmonth8.='"  value="'.$period->period_id.'" data-element="8">'.number_format_invoice($period->month8).'</a>';
                }

                if($period->month9 == 0){ 
                    $periodmonth9 = '<div class="payp30_dash month9_class month9_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->month_from <=9 && $year->month_to >=9) { $periodmonth9.='hide_column_inner'; } else { $periodmonth9.='show_column_inner'; } } $periodmonth9.='">-</div>';
                }
                else{
                    $periodmonth9 = '<a href="javascript:" class="payp30_green month9_class month9_class_'.$period->period_id.' month_remove '; if($year->show_active == 1) { if($year->month_from <=9 && $year->month_to >=9) { $periodmonth9.='hide_column_inner'; } else { $periodmonth9.='show_column_inner'; } } $periodmonth9.='"  value="'.$period->period_id.'" data-element="9">'.number_format_invoice($period->month9).'</a>';
                }

                if($period->month10 == 0){ 
                    $periodmonth10 = '<div class="payp30_dash month10_class month10_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->month_from <=10 && $year->month_to >=10) { $periodmonth10.='hide_column_inner'; } else { $periodmonth10.='show_column_inner'; } } $periodmonth10.='">-</div>';
                }
                else{
                    $periodmonth10 = '<a href="javascript:" class="payp30_green month10_class month10_class_'.$period->period_id.' month_remove '; if($year->show_active == 1) { if($year->month_from <=10 && $year->month_to >=10) { $periodmonth10.='hide_column_inner'; } else { $periodmonth10.='show_column_inner'; } } $periodmonth10.='"  value="'.$period->period_id.'" data-element="10">'.number_format_invoice($period->month10).'</a>';
                }

                if($period->month11 == 0){ 
                    $periodmonth11 = '<div class="payp30_dash month11_class month11_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->month_from <=11 && $year->month_to >=11) { $periodmonth11.='hide_column_inner'; } else { $periodmonth11.='show_column_inner'; } } $periodmonth11.='">-</div>';
                }
                else{
                    $periodmonth11 = '<a href="javascript:" class="payp30_green month11_class month11_class_'.$period->period_id.' month_remove '; if($year->show_active == 1) { if($year->month_from <=11 && $year->month_to >=11) { $periodmonth11.='hide_column_inner'; } else { $periodmonth11.='show_column_inner'; } } $periodmonth11.='"  value="'.$period->period_id.'" data-element="11">'.number_format_invoice($period->month11).'</a>';
                }

                if($period->month12 == 0){ 
                    $periodmonth12 = '<div class="payp30_dash month12_class month12_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->month_from <=12 && $year->month_to >=12) { $periodmonth12.='hide_column_inner'; } else { $periodmonth12.='show_column_inner'; } } $periodmonth12.='">-</div>';
                }
                else{
                    $periodmonth12 = '<a href="javascript:" class="payp30_green month12_class month12_class_'.$period->period_id.' month_remove '; if($year->show_active == 1) { if($year->month_from <=12 && $year->month_to >=12) { $periodmonth12.='hide_column_inner'; } else { $periodmonth12.='show_column_inner'; } } $periodmonth12.='"  value="'.$period->period_id.'" data-element="12">'.number_format_invoice($period->month12).'</a>';
                }


	            $output_row.='
	            <tr class="month_row_'.$period->period_id.'">
	                <td style="border-right: 1px solid #000 !important; border-left: 1px solid #000 !important; border: 1px solid #fff; border-bottom:1px solid #000"></td>
	                <td style="width: 100px; border-bottom: 0px; text-align: left;">
	                
	                </td>
	                
	                <td style="width: 40px; text-align: right; border-bottom: 0px;">
	                    <input type="radio" name="month_name_'.$task->id.'" class="month_class month_class_'.$period->month_id.'" value="'.$period->month_id.'" data-element="'.$period->paye_task.'" '.$month_active.' name="'.$period->paye_task.'"><label>&nbsp;</label>
	                </td>
	                <td style="width: 100px; border-bottom: 0px;">'.$month_name.'-'.$year->year_name.'</td>
	                <td style="width: 150px; border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control ros_class" data-element="'.$period->period_id.'" value="'.number_format_invoice($period->ros_liability).'"></td>
	                <td style="width: 150px; border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control liability_class" value="'.number_format_invoice($period->task_liability).'" readonly></td>
	                <td style="width: 150px; border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control diff_class" value="'.number_format_invoice($period->liability_diff).'" readonly></td>
	                <td colspan="3" style="width: 250px; "><a href="javascript:" class="fa fa-envelope email_unsent email_unsent_'.$period->period_id.'" data-element="'.$period->period_id.'"></a><br/>';
	                if($period->last_email_sent != '0000-00-00 00:00:00') { $email_sent_date = date('d M Y @ H:m', strtotime($period->last_email_sent)); } else { $email_sent_date = ''; }
	                $output_row.=''.$email_sent_date.'<br/></td>
	                                    
	                <td align="left" class="payp30_week_bg">'.$periodweek1.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek2.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek3.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek4.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek5.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek6.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek7.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek8.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek9.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek10.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek11.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek12.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek13.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek14.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek15.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek16.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek17.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek18.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek19.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek20.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek21.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek22.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek23.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek24.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek25.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek26.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek27.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek28.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek29.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek30.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek31.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek32.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek33.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek34.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek35.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek36.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek37.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek38.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek39.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek40.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek41.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek42.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek43.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek44.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek45.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek46.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek47.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek48.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek49.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek50.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek51.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek52.'</td>
	                <td align="left" class="payp30_week_bg">'.$periodweek53.'</td>
	                <td align="left" class="payp30_month_bg">'.$periodmonth1.'</td>
	                <td align="left" class="payp30_month_bg">'.$periodmonth2.'</td>
	                <td align="left" class="payp30_month_bg">'.$periodmonth3.'</td>
	                <td align="left" class="payp30_month_bg">'.$periodmonth4.'</td>
	                <td align="left" class="payp30_month_bg">'.$periodmonth5.'</td>
	                <td align="left" class="payp30_month_bg">'.$periodmonth6.'</td>
	                <td align="left" class="payp30_month_bg">'.$periodmonth7.'</td>
	                <td align="left" class="payp30_month_bg">'.$periodmonth8.'</td>
	                <td align="left" class="payp30_month_bg">'.$periodmonth9.'</td>
	                <td align="left" class="payp30_month_bg">'.$periodmonth10.'</td>
	                <td align="left" class="payp30_month_bg">'.$periodmonth11.'</td>
	                <td align="left" class="payp30_month_bg">'.$periodmonth12.'</td>
	            </tr>
	            ';

            }
        }

        $check_week1 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week1','!=','')->first();
        if($task->week1 == 0){ $week1 = '<div class="payp30_dash">-</div>';}else{$week1 = '<a href="javascript:" 
            class="';if(!count($check_week1)) {  $week1.= 'payp30_black task_class_colum'; }elseif($task->week1 !== $check_week1->week1) {  $week1.= 'payp30_red'; }else{ $week1.= 'payp30_red'; } $week1.=' " value="'.$task->id.'" data-element="1">'; if(!count($check_week1)) { $week1.= number_format_invoice($task->week1); } elseif($task->week1 !== $check_week1->week1) { $week1.= number_format_invoice($check_week1->week1).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week1.'" title="Liability Value ('.number_format_invoice($task->week1).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week1.= number_format_invoice($task->week1); } $week1.='</a>';}

        $check_week2 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week2','!=','')->first();
        if($task->week2 == 0){ $week2 = '<div class="payp30_dash">-</div>';}else{$week2 = '<a href="javascript:" 
            class="';if(!count($check_week2)) {  $week2.= 'payp30_black task_class_colum'; }elseif($task->week2 !== $check_week2->week2) {  $week2.= 'payp30_red'; }else{ $week2.= 'payp30_red'; } $week2.=' " value="'.$task->id.'" data-element="2">'; if(!count($check_week2)) { $week2.= number_format_invoice($task->week2); } elseif($task->week2 !== $check_week2->week2) { $week2.= number_format_invoice($check_week2->week2).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week2.'" title="Liability Value ('.number_format_invoice($task->week2).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week2.= number_format_invoice($task->week2); } $week2.='</a>';}

        $check_week3 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week3','!=','')->first();
        if($task->week3 == 0){ $week3 = '<div class="payp30_dash">-</div>';}else{$week3 = '<a href="javascript:" 
            class="';if(!count($check_week3)) {  $week3.= 'payp30_black task_class_colum'; }elseif($task->week3 !== $check_week3->week3) {  $week3.= 'payp30_red'; }else{ $week3.= 'payp30_red'; } $week3.=' " value="'.$task->id.'" data-element="3">'; if(!count($check_week3)) { $week3.= number_format_invoice($task->week3); } elseif($task->week3 !== $check_week3->week3) { $week3.= number_format_invoice($check_week3->week3).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week2.'" title="Liability Value ('.number_format_invoice($task->week3).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week3.= number_format_invoice($task->week3); } $week3.='</a>';}

        $check_week4 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week4','!=','')->first();
        if($task->week4 == 0){ $week4 = '<div class="payp30_dash">-</div>';}else{$week4 = '<a href="javascript:" 
            class="';if(!count($check_week4)) {  $week4.= 'payp30_black task_class_colum'; }elseif($task->week4 !== $check_week4->week4) {  $week4.= 'payp30_red'; }else{ $week4.= 'payp30_red'; } $week4.=' " value="'.$task->id.'" data-element="4">'; if(!count($check_week4)) { $week4.= number_format_invoice($task->week4); } elseif($task->week4 !== $check_week4->week4) { $week4.= number_format_invoice($check_week4->week4).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week4.'" title="Liability Value ('.number_format_invoice($task->week4).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week4.= number_format_invoice($task->week4); } $week4.='</a>';}

        $check_week5 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week5','!=','')->first();
        if($task->week5 == 0){ $week5 = '<div class="payp30_dash">-</div>';}else{$week5 = '<a href="javascript:" 
            class="';if(!count($check_week5)) {  $week5.= 'payp30_black task_class_colum'; }elseif($task->week5 !== $check_week5->week5) {  $week5.= 'payp30_red'; }else{ $week5.= 'payp30_red'; } $week5.=' " value="'.$task->id.'" data-element="5">'; if(!count($check_week5)) { $week5.= number_format_invoice($task->week5); } elseif($task->week5 !== $check_week5->week5) { $week5.= number_format_invoice($check_week5->week5).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week5.'" title="Liability Value ('.number_format_invoice($task->week5).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week5.= number_format_invoice($task->week5); } $week5.='</a>';}

        $check_week6 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week6','!=','')->first();
        if($task->week6 == 0){ $week6 = '<div class="payp30_dash">-</div>';}else{$week6 = '<a href="javascript:" 
            class="';if(!count($check_week6)) {  $week6.= 'payp30_black task_class_colum'; }elseif($task->week6 !== $check_week6->week6) {  $week6.= 'payp30_red'; }else{ $week6.= 'payp30_red'; } $week6.=' " value="'.$task->id.'" data-element="6">'; if(!count($check_week6)) { $week6.= number_format_invoice($task->week6); } elseif($task->week6 !== $check_week6->week6) { $week6.= number_format_invoice($check_week6->week6).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week6.'" title="Liability Value ('.number_format_invoice($task->week6).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week6.= number_format_invoice($task->week6); } $week6.='</a>';}

        $check_week7 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week7','!=','')->first();
        if($task->week7 == 0){ $week7 = '<div class="payp30_dash">-</div>';}else{$week7 = '<a href="javascript:" 
            class="';if(!count($check_week7)) {  $week7.= 'payp30_black task_class_colum'; }elseif($task->week7 !== $check_week7->week7) {  $week7.= 'payp30_red'; }else{ $week7.= 'payp30_red'; } $week7.=' " value="'.$task->id.'" data-element="7">'; if(!count($check_week7)) { $week7.= number_format_invoice($task->week7); } elseif($task->week7 !== $check_week7->week7) { $week7.= number_format_invoice($check_week7->week7).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week7.'" title="Liability Value ('.number_format_invoice($task->week7).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week7.= number_format_invoice($task->week7); } $week7.='</a>';}

        $check_week8 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week8','!=','')->first();
        if($task->week8 == 0){ $week8 = '<div class="payp30_dash">-</div>';}else{$week8 = '<a href="javascript:" 
            class="';if(!count($check_week8)) {  $week8.= 'payp30_black task_class_colum'; }elseif($task->week8 !== $check_week8->week8) {  $week8.= 'payp30_red'; }else{ $week8.= 'payp30_red'; } $week8.=' " value="'.$task->id.'" data-element="8">'; if(!count($check_week8)) { $week8.= number_format_invoice($task->week8); } elseif($task->week8 !== $check_week8->week8) { $week8.= number_format_invoice($check_week8->week8).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week8.'" title="Liability Value ('.number_format_invoice($task->week8).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week8.= number_format_invoice($task->week8); } $week8.='</a>';}

        $check_week9 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week9','!=','')->first();
        if($task->week9 == 0){ $week9 = '<div class="payp30_dash">-</div>';}else{$week9 = '<a href="javascript:" 
            class="';if(!count($check_week9)) {  $week9.= 'payp30_black task_class_colum'; }elseif($task->week9 !== $check_week9->week9) {  $week9.= 'payp30_red'; }else{ $week9.= 'payp30_red'; } $week9.=' " value="'.$task->id.'" data-element="9">'; if(!count($check_week9)) { $week9.= number_format_invoice($task->week9); } elseif($task->week9 !== $check_week9->week9) { $week9.= number_format_invoice($check_week9->week9).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week9.'" title="Liability Value ('.number_format_invoice($task->week9).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week9.= number_format_invoice($task->week9); } $week9.='</a>';}

        $check_week10 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week10','!=','')->first();
        if($task->week10 == 0){ $week10 = '<div class="payp30_dash">-</div>';}else{$week10 = '<a href="javascript:" 
            class="';if(!count($check_week10)) {  $week10.= 'payp30_black task_class_colum'; }elseif($task->week10 !== $check_week10->week10) {  $week10.= 'payp30_red'; }else{ $week10.= 'payp30_red'; } $week10.=' " value="'.$task->id.'" data-element="10">'; if(!count($check_week10)) { $week10.= number_format_invoice($task->week10); } elseif($task->week10 !== $check_week10->week10) { $week10.= number_format_invoice($check_week10->week10).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week10.'" title="Liability Value ('.number_format_invoice($task->week10).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week10.= number_format_invoice($task->week10); } $week10.='</a>';}

        $check_week11 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week11','!=','')->first();
        if($task->week11 == 0){ $week11 = '<div class="payp30_dash">-</div>';}else{$week11 = '<a href="javascript:" 
            class="';if(!count($check_week11)) {  $week11.= 'payp30_black task_class_colum'; }elseif($task->week11 !== $check_week11->week11) {  $week11.= 'payp30_red'; }else{ $week11.= 'payp30_red'; } $week11.=' " value="'.$task->id.'" data-element="11">'; if(!count($check_week11)) { $week11.= number_format_invoice($task->week11); } elseif($task->week11 !== $check_week11->week11) { $week11.= number_format_invoice($check_week11->week11).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week11.'" title="Liability Value ('.number_format_invoice($task->week11).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week11.= number_format_invoice($task->week11); } $week11.='</a>';}

        $check_week12 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week12','!=','')->first();
        if($task->week12 == 0){ $week12 = '<div class="payp30_dash">-</div>';}else{$week12 = '<a href="javascript:" 
            class="';if(!count($check_week12)) {  $week12.= 'payp30_black task_class_colum'; }elseif($task->week12 !== $check_week12->week12) {  $week12.= 'payp30_red'; }else{ $week12.= 'payp30_red'; } $week12.=' " value="'.$task->id.'" data-element="12">'; if(!count($check_week12)) { $week12.= number_format_invoice($task->week12); } elseif($task->week12 !== $check_week12->week12) { $week12.= number_format_invoice($check_week12->week12).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week12.'" title="Liability Value ('.number_format_invoice($task->week12).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week12.= number_format_invoice($task->week12); } $week12.='</a>';}

        $check_week13 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week13','!=','')->first();
        if($task->week13 == 0){ $week13 = '<div class="payp30_dash">-</div>';}else{$week13 = '<a href="javascript:" 
            class="';if(!count($check_week13)) {  $week13.= 'payp30_black task_class_colum'; }elseif($task->week13 !== $check_week13->week13) {  $week13.= 'payp30_red'; }else{ $week13.= 'payp30_red'; } $week13.=' " value="'.$task->id.'" data-element="13">'; if(!count($check_week13)) { $week13.= number_format_invoice($task->week13); } elseif($task->week13 !== $check_week13->week13) { $week13.= number_format_invoice($check_week13->week13).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week13.'" title="Liability Value ('.number_format_invoice($task->week13).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week13.= number_format_invoice($task->week13); } $week13.='</a>';}

        $check_week14 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week14','!=','')->first();
        if($task->week14 == 0){ $week14 = '<div class="payp30_dash">-</div>';}else{$week14 = '<a href="javascript:" 
            class="';if(!count($check_week14)) {  $week14.= 'payp30_black task_class_colum'; }elseif($task->week14 !== $check_week14->week14) {  $week14.= 'payp30_red'; }else{ $week14.= 'payp30_red'; } $week14.=' " value="'.$task->id.'" data-element="14">'; if(!count($check_week14)) { $week14.= number_format_invoice($task->week14); } elseif($task->week14 !== $check_week14->week14) { $week14.= number_format_invoice($check_week14->week14).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week14.'" title="Liability Value ('.number_format_invoice($task->week14).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week14.= number_format_invoice($task->week14); } $week14.='</a>';}

        $check_week15 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week15','!=','')->first();
        if($task->week15 == 0){ $week15 = '<div class="payp30_dash">-</div>';}else{$week15 = '<a href="javascript:" 
            class="';if(!count($check_week15)) {  $week15.= 'payp30_black task_class_colum'; }elseif($task->week15 !== $check_week15->week15) {  $week15.= 'payp30_red'; }else{ $week15.= 'payp30_red'; } $week15.=' " value="'.$task->id.'" data-element="15">'; if(!count($check_week15)) { $week15.= number_format_invoice($task->week15); } elseif($task->week15 !== $check_week15->week15) { $week15.= number_format_invoice($check_week15->week15).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week15.'" title="Liability Value ('.number_format_invoice($task->week15).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week15.= number_format_invoice($task->week15); } $week15.='</a>';}

        $check_week16 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week16','!=','')->first();
        if($task->week16 == 0){ $week16 = '<div class="payp30_dash">-</div>';}else{$week16 = '<a href="javascript:" 
            class="';if(!count($check_week16)) {  $week16.= 'payp30_black task_class_colum'; }elseif($task->week16 !== $check_week16->week16) {  $week16.= 'payp30_red'; }else{ $week16.= 'payp30_red'; } $week16.=' " value="'.$task->id.'" data-element="16">'; if(!count($check_week16)) { $week16.= number_format_invoice($task->week16); } elseif($task->week16 !== $check_week16->week16) { $week16.= number_format_invoice($check_week16->week16).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week16.'" title="Liability Value ('.number_format_invoice($task->week16).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week16.= number_format_invoice($task->week16); } $week16.='</a>';}

        $check_week17 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week17','!=','')->first();
        if($task->week17 == 0){ $week17 = '<div class="payp30_dash">-</div>';}else{$week17 = '<a href="javascript:" 
            class="';if(!count($check_week17)) {  $week17.= 'payp30_black task_class_colum'; }elseif($task->week17 !== $check_week17->week17) {  $week17.= 'payp30_red'; }else{ $week17.= 'payp30_red'; } $week17.=' " value="'.$task->id.'" data-element="17">'; if(!count($check_week17)) { $week17.= number_format_invoice($task->week17); } elseif($task->week17 !== $check_week17->week17) { $week17.= number_format_invoice($check_week17->week17).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week17.'" title="Liability Value ('.number_format_invoice($task->week17).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week17.= number_format_invoice($task->week17); } $week17.='</a>';}

        $check_week18 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week18','!=','')->first();
        if($task->week18 == 0){ $week18 = '<div class="payp30_dash">-</div>';}else{$week18 = '<a href="javascript:" 
            class="';if(!count($check_week18)) {  $week18.= 'payp30_black task_class_colum'; }elseif($task->week18 !== $check_week18->week18) {  $week18.= 'payp30_red'; }else{ $week18.= 'payp30_red'; } $week18.=' " value="'.$task->id.'" data-element="18">'; if(!count($check_week18)) { $week18.= number_format_invoice($task->week18); } elseif($task->week18 !== $check_week18->week18) { $week18.= number_format_invoice($check_week18->week18).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week18.'" title="Liability Value ('.number_format_invoice($task->week18).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week18.= number_format_invoice($task->week18); } $week18.='</a>';}


        $check_week19 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week19','!=','')->first();
        if($task->week19 == 0){ $week19 = '<div class="payp30_dash">-</div>';}else{$week19 = '<a href="javascript:" 
            class="';if(!count($check_week19)) {  $week19.= 'payp30_black task_class_colum'; }elseif($task->week19 !== $check_week19->week19) {  $week19.= 'payp30_red'; }else{ $week19.= 'payp30_red'; } $week19.=' " value="'.$task->id.'" data-element="19">'; if(!count($check_week19)) { $week19.= number_format_invoice($task->week19); } elseif($task->week19 !== $check_week19->week19) { $week19.= number_format_invoice($check_week19->week19).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week19.'" title="Liability Value ('.number_format_invoice($task->week19).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week19.= number_format_invoice($task->week19); } $week19.='</a>';}

        $check_week20 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week20','!=','')->first();
        if($task->week20 == 0){ $week20 = '<div class="payp30_dash">-</div>';}else{$week20 = '<a href="javascript:" 
            class="';if(!count($check_week20)) {  $week20.= 'payp30_black task_class_colum'; }elseif($task->week20 !== $check_week20->week20) {  $week20.= 'payp30_red'; }else{ $week20.= 'payp30_red'; } $week20.=' " value="'.$task->id.'" data-element="20">'; if(!count($check_week20)) { $week20.= number_format_invoice($task->week20); } elseif($task->week20 !== $check_week20->week20) { $week20.= number_format_invoice($check_week20->week20).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week20.'" title="Liability Value ('.number_format_invoice($task->week20).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week20.= number_format_invoice($task->week20); } $week20.='</a>';}

        $check_week21 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week21','!=','')->first();
        if($task->week21 == 0){ $week21 = '<div class="payp30_dash">-</div>';}else{$week21 = '<a href="javascript:" 
            class="';if(!count($check_week21)) {  $week21.= 'payp30_black task_class_colum'; }elseif($task->week21 !== $check_week21->week21) {  $week21.= 'payp30_red'; }else{ $week21.= 'payp30_red'; } $week21.=' " value="'.$task->id.'" data-element="21">'; if(!count($check_week21)) { $week21.= number_format_invoice($task->week21); } elseif($task->week21 !== $check_week21->week21) { $week21.= number_format_invoice($check_week21->week21).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week21.'" title="Liability Value ('.number_format_invoice($task->week21).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week21.= number_format_invoice($task->week21); } $week21.='</a>';}

        $check_week22 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week22','!=','')->first();
        if($task->week22 == 0){ $week22 = '<div class="payp30_dash">-</div>';}else{$week22 = '<a href="javascript:" 
            class="';if(!count($check_week22)) {  $week22.= 'payp30_black task_class_colum'; }elseif($task->week22 !== $check_week22->week22) {  $week22.= 'payp30_red'; }else{ $week22.= 'payp30_red'; } $week22.=' " value="'.$task->id.'" data-element="22">'; if(!count($check_week22)) { $week22.= number_format_invoice($task->week22); } elseif($task->week22 !== $check_week22->week22) { $week22.= number_format_invoice($check_week22->week22).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week22.'" title="Liability Value ('.number_format_invoice($task->week22).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week22.= number_format_invoice($task->week22); } $week22.='</a>';}

        $check_week23 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week23','!=','')->first();
        if($task->week23 == 0){ $week23 = '<div class="payp30_dash">-</div>';}else{$week23 = '<a href="javascript:" 
            class="';if(!count($check_week23)) {  $week23.= 'payp30_black task_class_colum'; }elseif($task->week23 !== $check_week23->week23) {  $week23.= 'payp30_red'; }else{ $week23.= 'payp30_red'; } $week23.=' " value="'.$task->id.'" data-element="23">'; if(!count($check_week23)) { $week23.= number_format_invoice($task->week23); } elseif($task->week23 !== $check_week23->week23) { $week23.= number_format_invoice($check_week23->week23).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week23.'" title="Liability Value ('.number_format_invoice($task->week23).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week23.= number_format_invoice($task->week23); } $week23.='</a>';}

        $check_week24 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week24','!=','')->first();
        if($task->week24 == 0){ $week24 = '<div class="payp30_dash">-</div>';}else{$week24 = '<a href="javascript:" 
            class="';if(!count($check_week24)) {  $week24.= 'payp30_black task_class_colum'; }elseif($task->week24 !== $check_week24->week24) {  $week24.= 'payp30_red'; }else{ $week24.= 'payp30_red'; } $week24.=' " value="'.$task->id.'" data-element="24">'; if(!count($check_week24)) { $week24.= number_format_invoice($task->week24); } elseif($task->week24 !== $check_week24->week24) { $week24.= number_format_invoice($check_week24->week24).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week24.'" title="Liability Value ('.number_format_invoice($task->week24).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week24.= number_format_invoice($task->week24); } $week24.='</a>';}

        $check_week25 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week25','!=','')->first();
        if($task->week25 == 0){ $week25 = '<div class="payp30_dash">-</div>';}else{$week25 = '<a href="javascript:" 
            class="';if(!count($check_week25)) {  $week25.= 'payp30_black task_class_colum'; }elseif($task->week25 !== $check_week25->week25) {  $week25.= 'payp30_red'; }else{ $week25.= 'payp30_red'; } $week25.=' " value="'.$task->id.'" data-element="25">'; if(!count($check_week25)) { $week25.= number_format_invoice($task->week25); } elseif($task->week25 !== $check_week25->week25) { $week25.= number_format_invoice($check_week25->week25).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week25.'" title="Liability Value ('.number_format_invoice($task->week25).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week25.= number_format_invoice($task->week25); } $week25.='</a>';}

        $check_week26 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week26','!=','')->first();
        if($task->week26 == 0){ $week26 = '<div class="payp30_dash">-</div>';}else{$week26 = '<a href="javascript:" 
            class="';if(!count($check_week26)) {  $week26.= 'payp30_black task_class_colum'; }elseif($task->week26 !== $check_week26->week26) {  $week26.= 'payp30_red'; }else{ $week26.= 'payp30_red'; } $week26.=' " value="'.$task->id.'" data-element="26">'; if(!count($check_week26)) { $week26.= number_format_invoice($task->week26); } elseif($task->week26 !== $check_week26->week26) { $week26.= number_format_invoice($check_week26->week26).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week26.'" title="Liability Value ('.number_format_invoice($task->week26).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week26.= number_format_invoice($task->week26); } $week26.='</a>';}

        $check_week27 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week27','!=','')->first();
        if($task->week27 == 0){ $week27 = '<div class="payp30_dash">-</div>';}else{$week27 = '<a href="javascript:" 
            class="';if(!count($check_week27)) {  $week27.= 'payp30_black task_class_colum'; }elseif($task->week27 !== $check_week27->week27) {  $week27.= 'payp30_red'; }else{ $week27.= 'payp30_red'; } $week27.=' " value="'.$task->id.'" data-element="27">'; if(!count($check_week27)) { $week27.= number_format_invoice($task->week27); } elseif($task->week27 !== $check_week27->week27) { $week27.= number_format_invoice($check_week27->week27).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week27.'" title="Liability Value ('.number_format_invoice($task->week27).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week27.= number_format_invoice($task->week27); } $week27.='</a>';}

        $check_week28 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week28','!=','')->first();
        if($task->week28 == 0){ $week28 = '<div class="payp30_dash">-</div>';}else{$week28 = '<a href="javascript:" 
            class="';if(!count($check_week28)) {  $week28.= 'payp30_black task_class_colum'; }elseif($task->week28 !== $check_week28->week28) {  $week28.= 'payp30_red'; }else{ $week28.= 'payp30_red'; } $week28.=' " value="'.$task->id.'" data-element="28">'; if(!count($check_week28)) { $week28.= number_format_invoice($task->week28); } elseif($task->week28 !== $check_week28->week28) { $week28.= number_format_invoice($check_week28->week28).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week28.'" title="Liability Value ('.number_format_invoice($task->week28).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week28.= number_format_invoice($task->week28); } $week28.='</a>';}

        $check_week29 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week29','!=','')->first();
        if($task->week29 == 0){ $week29 = '<div class="payp30_dash">-</div>';}else{$week29 = '<a href="javascript:" 
            class="';if(!count($check_week29)) {  $week29.= 'payp30_black task_class_colum'; }elseif($task->week29 !== $check_week29->week29) {  $week29.= 'payp30_red'; }else{ $week29.= 'payp30_red'; } $week29.=' " value="'.$task->id.'" data-element="29">'; if(!count($check_week29)) { $week29.= number_format_invoice($task->week29); } elseif($task->week29 !== $check_week29->week29) { $week29.= number_format_invoice($check_week29->week29).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week29.'" title="Liability Value ('.number_format_invoice($task->week29).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week29.= number_format_invoice($task->week29); } $week29.='</a>';}

        $check_week30 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week30','!=','')->first();
        if($task->week30 == 0){ $week30 = '<div class="payp30_dash">-</div>';}else{$week30 = '<a href="javascript:" 
            class="';if(!count($check_week30)) {  $week30.= 'payp30_black task_class_colum'; }elseif($task->week30 !== $check_week30->week30) {  $week30.= 'payp30_red'; }else{ $week30.= 'payp30_red'; } $week30.=' " value="'.$task->id.'" data-element="30">'; if(!count($check_week30)) { $week30.= number_format_invoice($task->week30); } elseif($task->week30 !== $check_week30->week30) { $week30.= number_format_invoice($check_week30->week30).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week30.'" title="Liability Value ('.number_format_invoice($task->week30).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week30.= number_format_invoice($task->week30); } $week30.='</a>';}

        $check_week31 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week31','!=','')->first();
        if($task->week31 == 0){ $week31 = '<div class="payp30_dash">-</div>';}else{$week31 = '<a href="javascript:" 
            class="';if(!count($check_week31)) {  $week31.= 'payp30_black task_class_colum'; }elseif($task->week31 !== $check_week31->week31) {  $week31.= 'payp30_red'; }else{ $week31.= 'payp30_red'; } $week31.=' " value="'.$task->id.'" data-element="31">'; if(!count($check_week31)) { $week31.= number_format_invoice($task->week31); } elseif($task->week31 !== $check_week31->week31) { $week31.= number_format_invoice($check_week31->week31).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week31.'" title="Liability Value ('.number_format_invoice($task->week31).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week31.= number_format_invoice($task->week31); } $week31.='</a>';}

        $check_week32 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week32','!=','')->first();
        if($task->week32 == 0){ $week32 = '<div class="payp30_dash">-</div>';}else{$week32 = '<a href="javascript:" 
            class="';if(!count($check_week32)) {  $week32.= 'payp30_black task_class_colum'; }elseif($task->week32 !== $check_week32->week32) {  $week32.= 'payp30_red'; }else{ $week32.= 'payp30_red'; } $week32.=' " value="'.$task->id.'" data-element="32">'; if(!count($check_week32)) { $week32.= number_format_invoice($task->week32); } elseif($task->week32 !== $check_week32->week32) { $week32.= number_format_invoice($check_week32->week32).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week32.'" title="Liability Value ('.number_format_invoice($task->week32).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week32.= number_format_invoice($task->week32); } $week32.='</a>';}

        $check_week33 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week33','!=','')->first();
        if($task->week33 == 0){ $week33 = '<div class="payp30_dash">-</div>';}else{$week33 = '<a href="javascript:" 
            class="';if(!count($check_week33)) {  $week33.= 'payp30_black task_class_colum'; }elseif($task->week33 !== $check_week33->week33) {  $week33.= 'payp30_red'; }else{ $week33.= 'payp30_red'; } $week33.=' " value="'.$task->id.'" data-element="33">'; if(!count($check_week33)) { $week33.= number_format_invoice($task->week33); } elseif($task->week33 !== $check_week33->week33) { $week33.= number_format_invoice($check_week33->week33).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week33.'" title="Liability Value ('.number_format_invoice($task->week33).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week33.= number_format_invoice($task->week33); } $week33.='</a>';}

        $check_week34 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week34','!=','')->first();
        if($task->week34 == 0){ $week34 = '<div class="payp30_dash">-</div>';}else{$week34 = '<a href="javascript:" 
            class="';if(!count($check_week34)) {  $week34.= 'payp30_black task_class_colum'; }elseif($task->week34 !== $check_week34->week34) {  $week34.= 'payp30_red'; }else{ $week34.= 'payp30_red'; } $week34.=' " value="'.$task->id.'" data-element="34">'; if(!count($check_week34)) { $week34.= number_format_invoice($task->week34); } elseif($task->week34 !== $check_week34->week34) { $week34.= number_format_invoice($check_week34->week34).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week34.'" title="Liability Value ('.number_format_invoice($task->week34).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week34.= number_format_invoice($task->week34); } $week34.='</a>';}

        $check_week35 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week35','!=','')->first();
        if($task->week35 == 0){ $week35 = '<div class="payp30_dash">-</div>';}else{$week35 = '<a href="javascript:" 
            class="';if(!count($check_week35)) {  $week35.= 'payp30_black task_class_colum'; }elseif($task->week35 !== $check_week35->week35) {  $week35.= 'payp30_red'; }else{ $week35.= 'payp30_red'; } $week35.=' " value="'.$task->id.'" data-element="35">'; if(!count($check_week35)) { $week35.= number_format_invoice($task->week35); } elseif($task->week35 !== $check_week35->week35) { $week35.= number_format_invoice($check_week35->week35).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week35.'" title="Liability Value ('.number_format_invoice($task->week35).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week35.= number_format_invoice($task->week35); } $week35.='</a>';}

        $check_week36 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week36','!=','')->first();
        if($task->week36 == 0){ $week36 = '<div class="payp30_dash">-</div>';}else{$week36 = '<a href="javascript:" 
            class="';if(!count($check_week36)) {  $week36.= 'payp30_black task_class_colum'; }elseif($task->week36 !== $check_week36->week36) {  $week36.= 'payp30_red'; }else{ $week36.= 'payp30_red'; } $week36.=' " value="'.$task->id.'" data-element="36">'; if(!count($check_week36)) { $week36.= number_format_invoice($task->week36); } elseif($task->week36 !== $check_week36->week36) { $week36.= number_format_invoice($check_week36->week36).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week36.'" title="Liability Value ('.number_format_invoice($task->week36).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week36.= number_format_invoice($task->week36); } $week36.='</a>';}

        $check_week37 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week37','!=','')->first();
        if($task->week37 == 0){ $week37 = '<div class="payp30_dash">-</div>';}else{$week37 = '<a href="javascript:" 
            class="';if(!count($check_week37)) {  $week37.= 'payp30_black task_class_colum'; }elseif($task->week37 !== $check_week37->week37) {  $week37.= 'payp30_red'; }else{ $week37.= 'payp30_red'; } $week37.=' " value="'.$task->id.'" data-element="37">'; if(!count($check_week37)) { $week37.= number_format_invoice($task->week37); } elseif($task->week37 !== $check_week37->week37) { $week37.= number_format_invoice($check_week37->week37).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week37.'" title="Liability Value ('.number_format_invoice($task->week37).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week37.= number_format_invoice($task->week37); } $week37.='</a>';}

        $check_week38 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week38','!=','')->first();
        if($task->week38 == 0){ $week38 = '<div class="payp30_dash">-</div>';}else{$week38 = '<a href="javascript:" 
            class="';if(!count($check_week38)) {  $week38.= 'payp30_black task_class_colum'; }elseif($task->week38 !== $check_week38->week38) {  $week38.= 'payp30_red'; }else{ $week38.= 'payp30_red'; } $week38.=' " value="'.$task->id.'" data-element="38">'; if(!count($check_week38)) { $week38.= number_format_invoice($task->week38); } elseif($task->week38 !== $check_week38->week38) { $week38.= number_format_invoice($check_week38->week38).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week38.'" title="Liability Value ('.number_format_invoice($task->week38).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week38.= number_format_invoice($task->week38); } $week38.='</a>';}

        $check_week39 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week39','!=','')->first();
        if($task->week39 == 0){ $week39 = '<div class="payp30_dash">-</div>';}else{$week39 = '<a href="javascript:" 
            class="';if(!count($check_week39)) {  $week39.= 'payp30_black task_class_colum'; }elseif($task->week39 !== $check_week39->week39) {  $week39.= 'payp30_red'; }else{ $week39.= 'payp30_red'; } $week39.=' " value="'.$task->id.'" data-element="39">'; if(!count($check_week39)) { $week39.= number_format_invoice($task->week39); } elseif($task->week39 !== $check_week39->week39) { $week39.= number_format_invoice($check_week39->week39).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week39.'" title="Liability Value ('.number_format_invoice($task->week39).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week39.= number_format_invoice($task->week39); } $week39.='</a>';}

        $check_week40 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week40','!=','')->first();
        if($task->week40 == 0){ $week40 = '<div class="payp30_dash">-</div>';}else{$week40 = '<a href="javascript:" 
            class="';if(!count($check_week40)) {  $week40.= 'payp30_black task_class_colum'; }elseif($task->week40 !== $check_week40->week40) {  $week40.= 'payp30_red'; }else{ $week40.= 'payp30_red'; } $week40.=' " value="'.$task->id.'" data-element="40">'; if(!count($check_week40)) { $week40.= number_format_invoice($task->week40); } elseif($task->week40 !== $check_week40->week40) { $week40.= number_format_invoice($check_week40->week40).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week40.'" title="Liability Value ('.number_format_invoice($task->week40).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week40.= number_format_invoice($task->week40); } $week40.='</a>';}

        $check_week41 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week41','!=','')->first();
        if($task->week41 == 0){ $week41 = '<div class="payp30_dash">-</div>';}else{$week41 = '<a href="javascript:" 
            class="';if(!count($check_week41)) {  $week41.= 'payp30_black task_class_colum'; }elseif($task->week41 !== $check_week41->week41) {  $week41.= 'payp30_red'; }else{ $week41.= 'payp30_red'; } $week41.=' " value="'.$task->id.'" data-element="41">'; if(!count($check_week41)) { $week41.= number_format_invoice($task->week41); } elseif($task->week41 !== $check_week41->week41) { $week41.= number_format_invoice($check_week41->week41).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week41.'" title="Liability Value ('.number_format_invoice($task->week41).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week41.= number_format_invoice($task->week41); } $week41.='</a>';}

        $check_week42 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week42','!=','')->first();
        if($task->week42 == 0){ $week42 = '<div class="payp30_dash">-</div>';}else{$week42 = '<a href="javascript:" 
            class="';if(!count($check_week42)) {  $week42.= 'payp30_black task_class_colum'; }elseif($task->week42 !== $check_week42->week42) {  $week42.= 'payp30_red'; }else{ $week42.= 'payp30_red'; } $week42.=' " value="'.$task->id.'" data-element="42">'; if(!count($check_week42)) { $week42.= number_format_invoice($task->week42); } elseif($task->week42 !== $check_week42->week42) { $week42.= number_format_invoice($check_week42->week42).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week42.'" title="Liability Value ('.number_format_invoice($task->week42).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week42.= number_format_invoice($task->week42); } $week42.='</a>';}

        $check_week43 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week43','!=','')->first();
        if($task->week43 == 0){ $week43 = '<div class="payp30_dash">-</div>';}else{$week43 = '<a href="javascript:" 
            class="';if(!count($check_week43)) {  $week43.= 'payp30_black task_class_colum'; }elseif($task->week43 !== $check_week43->week43) {  $week43.= 'payp30_red'; }else{ $week43.= 'payp30_red'; } $week43.=' " value="'.$task->id.'" data-element="43">'; if(!count($check_week43)) { $week43.= number_format_invoice($task->week43); } elseif($task->week43 !== $check_week43->week43) { $week43.= number_format_invoice($check_week43->week43).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week43.'" title="Liability Value ('.number_format_invoice($task->week43).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week43.= number_format_invoice($task->week43); } $week43.='</a>';}

        $check_week44 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week44','!=','')->first();
        if($task->week44 == 0){ $week44 = '<div class="payp30_dash">-</div>';}else{$week44 = '<a href="javascript:" 
            class="';if(!count($check_week44)) {  $week44.= 'payp30_black task_class_colum'; }elseif($task->week44 !== $check_week44->week44) {  $week44.= 'payp30_red'; }else{ $week44.= 'payp30_red'; } $week44.=' " value="'.$task->id.'" data-element="44">'; if(!count($check_week44)) { $week44.= number_format_invoice($task->week44); } elseif($task->week44 !== $check_week44->week44) { $week44.= number_format_invoice($check_week44->week44).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week44.'" title="Liability Value ('.number_format_invoice($task->week44).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week44.= number_format_invoice($task->week44); } $week44.='</a>';}

        $check_week45 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week45','!=','')->first();
        if($task->week45 == 0){ $week45 = '<div class="payp30_dash">-</div>';}else{$week45 = '<a href="javascript:" 
            class="';if(!count($check_week45)) {  $week45.= 'payp30_black task_class_colum'; }elseif($task->week45 !== $check_week45->week45) {  $week45.= 'payp30_red'; }else{ $week45.= 'payp30_red'; } $week45.=' " value="'.$task->id.'" data-element="45">'; if(!count($check_week45)) { $week45.= number_format_invoice($task->week45); } elseif($task->week45 !== $check_week45->week45) { $week45.= number_format_invoice($check_week45->week45).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week45.'" title="Liability Value ('.number_format_invoice($task->week45).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week45.= number_format_invoice($task->week45); } $week45.='</a>';}

        $check_week46 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week46','!=','')->first();
        if($task->week46 == 0){ $week46 = '<div class="payp30_dash">-</div>';}else{$week46 = '<a href="javascript:" 
            class="';if(!count($check_week46)) {  $week46.= 'payp30_black task_class_colum'; }elseif($task->week46 !== $check_week46->week46) {  $week46.= 'payp30_red'; }else{ $week46.= 'payp30_red'; } $week46.=' " value="'.$task->id.'" data-element="46">'; if(!count($check_week46)) { $week46.= number_format_invoice($task->week46); } elseif($task->week46 !== $check_week46->week46) { $week46.= number_format_invoice($check_week46->week46).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week46.'" title="Liability Value ('.number_format_invoice($task->week46).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week46.= number_format_invoice($task->week46); } $week46.='</a>';}

        $check_week47 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week47','!=','')->first();
        if($task->week47 == 0){ $week47 = '<div class="payp30_dash">-</div>';}else{$week47 = '<a href="javascript:" 
            class="';if(!count($check_week47)) {  $week47.= 'payp30_black task_class_colum'; }elseif($task->week47 !== $check_week47->week47) {  $week47.= 'payp30_red'; }else{ $week47.= 'payp30_red'; } $week47.=' " value="'.$task->id.'" data-element="47">'; if(!count($check_week47)) { $week47.= number_format_invoice($task->week47); } elseif($task->week47 !== $check_week47->week47) { $week47.= number_format_invoice($check_week47->week47).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week47.'" title="Liability Value ('.number_format_invoice($task->week47).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week47.= number_format_invoice($task->week47); } $week47.='</a>';}

        $check_week48 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week48','!=','')->first();
        if($task->week48 == 0){ $week48 = '<div class="payp30_dash">-</div>';}else{$week48 = '<a href="javascript:" 
            class="';if(!count($check_week48)) {  $week48.= 'payp30_black task_class_colum'; }elseif($task->week48 !== $check_week48->week48) {  $week48.= 'payp30_red'; }else{ $week48.= 'payp30_red'; } $week48.=' " value="'.$task->id.'" data-element="48">'; if(!count($check_week48)) { $week48.= number_format_invoice($task->week48); } elseif($task->week48 !== $check_week48->week48) { $week48.= number_format_invoice($check_week48->week48).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week48.'" title="Liability Value ('.number_format_invoice($task->week48).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week48.= number_format_invoice($task->week48); } $week48.='</a>';}

        $check_week49 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week49','!=','')->first();
        if($task->week49 == 0){ $week49 = '<div class="payp30_dash">-</div>';}else{$week49 = '<a href="javascript:" 
            class="';if(!count($check_week49)) {  $week49.= 'payp30_black task_class_colum'; }elseif($task->week49 !== $check_week49->week49) {  $week49.= 'payp30_red'; }else{ $week49.= 'payp30_red'; } $week49.=' " value="'.$task->id.'" data-element="49">'; if(!count($check_week49)) { $week49.= number_format_invoice($task->week49); } elseif($task->week49 !== $check_week49->week49) { $week49.= number_format_invoice($check_week49->week49).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week49.'" title="Liability Value ('.number_format_invoice($task->week49).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week49.= number_format_invoice($task->week49); } $week49.='</a>';}

        $check_week50 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week50','!=','')->first();
        if($task->week50 == 0){ $week50 = '<div class="payp30_dash">-</div>';}else{$week50 = '<a href="javascript:" 
            class="';if(!count($check_week50)) {  $week50.= 'payp30_black task_class_colum'; }elseif($task->week50 !== $check_week50->week50) {  $week50.= 'payp30_red'; }else{ $week50.= 'payp30_red'; } $week50.=' " value="'.$task->id.'" data-element="50">'; if(!count($check_week50)) { $week50.= number_format_invoice($task->week50); } elseif($task->week50 !== $check_week50->week50) { $week50.= number_format_invoice($check_week50->week50).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week50.'" title="Liability Value ('.number_format_invoice($task->week50).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week50.= number_format_invoice($task->week50); } $week50.='</a>';}

        $check_week51 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week51','!=','')->first();
        if($task->week51 == 0){ $week51 = '<div class="payp30_dash">-</div>';}else{$week51 = '<a href="javascript:" 
            class="';if(!count($check_week51)) {  $week51.= 'payp30_black task_class_colum'; }elseif($task->week51 !== $check_week51->week51) {  $week51.= 'payp30_red'; }else{ $week51.= 'payp30_red'; } $week51.=' " value="'.$task->id.'" data-element="51">'; if(!count($check_week51)) { $week51.= number_format_invoice($task->week51); } elseif($task->week51 !== $check_week51->week51) { $week51.= number_format_invoice($check_week51->week51).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week51.'" title="Liability Value ('.number_format_invoice($task->week51).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week51.= number_format_invoice($task->week51); } $week51.='</a>';}

        $check_week52 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week52','!=','')->first();
        if($task->week52 == 0){ $week52 = '<div class="payp30_dash">-</div>';}else{$week52 = '<a href="javascript:" 
            class="';if(!count($check_week52)) {  $week52.= 'payp30_black task_class_colum'; }elseif($task->week52 !== $check_week52->week52) {  $week52.= 'payp30_red'; }else{ $week52.= 'payp30_red'; } $week52.=' " value="'.$task->id.'" data-element="52">'; if(!count($check_week52)) { $week52.= number_format_invoice($task->week52); } elseif($task->week52 !== $check_week52->week52) { $week52.= number_format_invoice($check_week52->week52).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week52.'" title="Liability Value ('.number_format_invoice($task->week52).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week52.= number_format_invoice($task->week52); } $week52.='</a>';}

        $check_week53 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week53','!=','')->first();
        if($task->week53 == 0){ $week53 = '<div class="payp30_dash">-</div>';}else{$week53 = '<a href="javascript:" 
            class="';if(!count($check_week53)) {  $week53.= 'payp30_black task_class_colum'; }elseif($task->week53 !== $check_week53->week53) {  $week53.= 'payp30_red'; }else{ $week53.= 'payp30_red'; } $week53.=' " value="'.$task->id.'" data-element="53">'; if(!count($check_week53)) { $week53.= number_format_invoice($task->week53); } elseif($task->week53 !== $check_week53->week53) { $week53.= number_format_invoice($check_week53->week53).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->week53.'" title="Liability Value ('.number_format_invoice($task->week53).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week53.= number_format_invoice($task->week53); } $week53.='</a>';}




        $check_month1 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('month1','!=','')->first();
        if($task->month1 == 0){ $month1 = '<div class="payp30_dash">-</div>';}else{$month1 = '<a href="javascript:" 
            class="';if(!count($check_month1)) {  $month1.= 'payp30_black task_class_colum_month'; }elseif($task->month1 !== $check_month1->month1) {  $month1.= 'payp30_red'; }else{ $month1.= 'payp30_red'; } $month1.=' " value="'.$task->id.'" data-element="1">'; if(!count($check_month1)) { $month1.= number_format_invoice($task->month1); } elseif($task->month1 !== $check_month1->month1) { $month1.= number_format_invoice($check_month1->month1).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->month1.'" title="Liability Value ('.number_format_invoice($task->month1).') has been changed in this month so if you want to update the value please remove the value which is alloted below"></i>'; } else { $month1.= number_format_invoice($task->month1); } $month1.='</a>';}

        $check_month2 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('month2','!=','')->first();
        if($task->month2 == 0){ $month2 = '<div class="payp30_dash">-</div>';}else{$month2 = '<a href="javascript:" 
            class="';if(!count($check_month2)) {  $month2.= 'payp30_black task_class_colum_month'; }elseif($task->month2 !== $check_month2->month2) {  $month2.= 'payp30_red'; }else{ $month2.= 'payp30_red'; } $month2.=' " value="'.$task->id.'" data-element="2">'; if(!count($check_month2)) { $month2.= number_format_invoice($task->month2); } elseif($task->month2 !== $check_month2->month2) { $month2.= number_format_invoice($check_month2->month2).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->month2.'" title="Liability Value ('.number_format_invoice($task->month2).') has been changed in this month so if you want to update the value please remove the value which is alloted below"></i>'; } else { $month2.= number_format_invoice($task->month2); } $month2.='</a>';}

        $check_month3 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('month3','!=','')->first();
        if($task->month3 == 0){ $month3 = '<div class="payp30_dash">-</div>';}else{$month3 = '<a href="javascript:" 
            class="';if(!count($check_month3)) {  $month3.= 'payp30_black task_class_colum_month'; }elseif($task->month3 !== $check_month3->month3) {  $month3.= 'payp30_red'; }else{ $month3.= 'payp30_red'; } $month3.=' " value="'.$task->id.'" data-element="3">'; if(!count($check_month3)) { $month3.= number_format_invoice($task->month3); } elseif($task->month3 !== $check_month3->month3) { $month3.= number_format_invoice($check_month3->month3).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->month3.'" title="Liability Value ('.number_format_invoice($task->month3).') has been changed in this month so if you want to update the value please remove the value which is alloted below"></i>'; } else { $month3.= number_format_invoice($task->month3); } $month3.='</a>';}

        $check_month4 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('month4','!=','')->first();
        if($task->month4 == 0){ $month4 = '<div class="payp30_dash">-</div>';}else{$month4 = '<a href="javascript:" 
            class="';if(!count($check_month4)) {  $month4.= 'payp30_black task_class_colum_month'; }elseif($task->month4 !== $check_month4->month4) {  $month4.= 'payp30_red'; }else{ $month4.= 'payp30_red'; } $month4.=' " value="'.$task->id.'" data-element="4">'; if(!count($check_month4)) { $month4.= number_format_invoice($task->month4); } elseif($task->month4 !== $check_month4->month4) { $month4.= number_format_invoice($check_month4->month4).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->month4.'" title="Liability Value ('.number_format_invoice($task->month4).') has been changed in this month so if you want to update the value please remove the value which is alloted below"></i>'; } else { $month4.= number_format_invoice($task->month4); } $month4.='</a>';}

        $check_month5 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('month5','!=','')->first();
        if($task->month5 == 0){ $month5 = '<div class="payp30_dash">-</div>';}else{$month5 = '<a href="javascript:" 
            class="';if(!count($check_month5)) {  $month5.= 'payp30_black task_class_colum_month'; }elseif($task->month5 !== $check_month5->month5) {  $month5.= 'payp30_red'; }else{ $month5.= 'payp30_red'; } $month5.=' " value="'.$task->id.'" data-element="5">'; if(!count($check_month5)) { $month5.= number_format_invoice($task->month5); } elseif($task->month5 !== $check_month5->month5) { $month5.= number_format_invoice($check_month5->month5).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->month5.'" title="Liability Value ('.number_format_invoice($task->month5).') has been changed in this month so if you want to update the value please remove the value which is alloted below"></i>'; } else { $month5.= number_format_invoice($task->month5); } $month5.='</a>';}

        $check_month6 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('month6','!=','')->first();
        if($task->month6 == 0){ $month6 = '<div class="payp30_dash">-</div>';}else{$month6 = '<a href="javascript:" 
            class="';if(!count($check_month6)) {  $month6.= 'payp30_black task_class_colum_month'; }elseif($task->month6 !== $check_month6->month6) {  $month6.= 'payp30_red'; }else{ $month6.= 'payp30_red'; } $month6.=' " value="'.$task->id.'" data-element="6">'; if(!count($check_month6)) { $month6.= number_format_invoice($task->month6); } elseif($task->month6 !== $check_month6->month6) { $month6.= number_format_invoice($check_month6->month6).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->month6.'" title="Liability Value ('.number_format_invoice($task->month6).') has been changed in this month so if you want to update the value please remove the value which is alloted below"></i>'; } else { $month6.= number_format_invoice($task->month6); } $month6.='</a>';}

        $check_month7 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('month7','!=','')->first();
        if($task->month7 == 0){ $month7 = '<div class="payp30_dash">-</div>';}else{$month7 = '<a href="javascript:" 
            class="';if(!count($check_month7)) {  $month7.= 'payp30_black task_class_colum_month'; }elseif($task->month7 !== $check_month7->month7) {  $month7.= 'payp30_red'; }else{ $month7.= 'payp30_red'; } $month7.=' " value="'.$task->id.'" data-element="7">'; if(!count($check_month7)) { $month7.= number_format_invoice($task->month7); } elseif($task->month7 !== $check_month7->month7) { $month7.= number_format_invoice($check_month7->month7).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->month7.'" title="Liability Value ('.number_format_invoice($task->month7).') has been changed in this month so if you want to update the value please remove the value which is alloted below"></i>'; } else { $month7.= number_format_invoice($task->month7); } $month7.='</a>';}

        $check_month8 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('month8','!=','')->first();
        if($task->month8 == 0){ $month8 = '<div class="payp30_dash">-</div>';}else{$month8 = '<a href="javascript:" 
            class="';if(!count($check_month8)) {  $month8.= 'payp30_black task_class_colum_month'; }elseif($task->month8 !== $check_month8->month8) {  $month8.= 'payp30_red'; }else{ $month8.= 'payp30_red'; } $month8.=' " value="'.$task->id.'" data-element="8">'; if(!count($check_month8)) { $month8.= number_format_invoice($task->month8); } elseif($task->month8 !== $check_month8->month8) { $month8.= number_format_invoice($check_month8->month8).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->month8.'" title="Liability Value ('.number_format_invoice($task->month8).') has been changed in this month so if you want to update the value please remove the value which is alloted below"></i>'; } else { $month8.= number_format_invoice($task->month8); } $month8.='</a>';}

        $check_month9 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('month9','!=','')->first();
        if($task->month9 == 0){ $month9 = '<div class="payp30_dash">-</div>';}else{$month9 = '<a href="javascript:" 
            class="';if(!count($check_month9)) {  $month9.= 'payp30_black task_class_colum_month'; }elseif($task->month9 !== $check_month9->month9) {  $month9.= 'payp30_red'; }else{ $month9.= 'payp30_red'; } $month9.=' " value="'.$task->id.'" data-element="9">'; if(!count($check_month9)) { $month9.= number_format_invoice($task->month9); } elseif($task->month9 !== $check_month9->month9) { $month9.= number_format_invoice($check_month9->month9).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->month9.'" title="Liability Value ('.number_format_invoice($task->month9).') has been changed in this month so if you want to update the value please remove the value which is alloted below"></i>'; } else { $month9.= number_format_invoice($task->month9); } $month9.='</a>';}

        $check_month10 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('month10','!=','')->first();
        if($task->month10 == 0){ $month10 = '<div class="payp30_dash">-</div>';}else{$month10 = '<a href="javascript:" 
            class="';if(!count($check_month10)) {  $month10.= 'payp30_black task_class_colum_month'; }elseif($task->month10 !== $check_month10->month10) {  $month10.= 'payp30_red'; }else{ $month10.= 'payp30_red'; } $month10.=' " value="'.$task->id.'" data-element="10">'; if(!count($check_month10)) { $month10.= number_format_invoice($task->month10); } elseif($task->month10 !== $check_month10->month10) { $month10.= number_format_invoice($check_month10->month10).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->month10.'" title="Liability Value ('.number_format_invoice($task->month10).') has been changed in this month so if you want to update the value please remove the value which is alloted below"></i>'; } else { $month10.= number_format_invoice($task->month10); } $month10.='</a>';}

        $check_month11 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('month11','!=','')->first();
        if($task->month11 == 0){ $month11 = '<div class="payp30_dash">-</div>';}else{$month11 = '<a href="javascript:" 
            class="';if(!count($check_month11)) {  $month11.= 'payp30_black task_class_colum_month'; }elseif($task->month11 !== $check_month11->month11) {  $month11.= 'payp30_red'; }else{ $month11.= 'payp30_red'; } $month11.=' " value="'.$task->id.'" data-element="11">'; if(!count($check_month11)) { $month11.= number_format_invoice($task->month11); } elseif($task->month11 !== $check_month11->month11) { $month11.= number_format_invoice($check_month11->month11).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->month11.'" title="Liability Value ('.number_format_invoice($task->month11).') has been changed in this month so if you want to update the value please remove the value which is alloted below"></i>'; } else { $month11.= number_format_invoice($task->month11); } $month11.='</a>';}

        $check_month12 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('month12','!=','')->first();
        if($task->month12 == 0){ $month12 = '<div class="payp30_dash">-</div>';}else{$month12 = '<a href="javascript:" 
            class="';if(!count($check_month12)) {  $month12.= 'payp30_black task_class_colum_month'; }elseif($task->month12 !== $check_month12->month12) {  $month12.= 'payp30_red'; }else{ $month12.= 'payp30_red'; } $month12.=' " value="'.$task->id.'" data-element="12">'; if(!count($check_month12)) { $month12.= number_format_invoice($task->month12); } elseif($task->month12 !== $check_month12->month12) { $month12.= number_format_invoice($check_month12->month12).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->month12.'" title="Liability Value ('.number_format_invoice($task->month12).') has been changed in this month so if you want to update the value please remove the value which is alloted below"></i>'; } else { $month12.= number_format_invoice($task->month12); } $month12.='</a>';}


        $output ='
	      <div class="table-responsive" style="float: left;width:7000px">
	        <table class="table_bg table-fixed-header table_paye_p30" style="margin-bottom:20px;width:6700px;margin-top:40px">
	              <thead class="header">
	                <tr>
	                    <th style="border-right: 1px solid #000 !important; border-left: 1px solid #000 !important; border: 1px solid #000;width:50px" valign="top">S.No</th>                    
	                    <th colspan="7" style="text-align:left;width:500px">
	                        Clients
	                    </th>                    
	                    <th style="border-bottom: 0px; text-align:center;width:300px;" width="200px">
	                        Email Sent                        
	                    </th>                    
	                    <th style=""></th>
	                    <th align="right" class="payp30_week_bg week_td_1 '; if($year->show_active == 1) { if($year->week_from <=1 && $year->week_to >=1) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 1</th>
	                    <th align="right" class="payp30_week_bg week_td_2 '; if($year->show_active == 1) { if($year->week_from <=2 && $year->week_to >=2) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 2</th>
	                    <th align="right" class="payp30_week_bg week_td_3 '; if($year->show_active == 1) { if($year->week_from <=3 && $year->week_to >=3) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 3</th>
	                    <th align="right" class="payp30_week_bg week_td_4 '; if($year->show_active == 1) { if($year->week_from <=4 && $year->week_to >=4) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 4</th>
	                    <th align="right" class="payp30_week_bg week_td_5 '; if($year->show_active == 1) { if($year->week_from <=5 && $year->week_to >=5) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 5</th>
	                    <th align="right" class="payp30_week_bg week_td_6 '; if($year->show_active == 1) { if($year->week_from <=6 && $year->week_to >=6) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 6</th>
	                    <th align="right" class="payp30_week_bg week_td_7 '; if($year->show_active == 1) { if($year->week_from <=7 && $year->week_to >=7) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 7</th>
	                    <th align="right" class="payp30_week_bg week_td_8 '; if($year->show_active == 1) { if($year->week_from <=8 && $year->week_to >=8) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 8</th>
	                    <th align="right" class="payp30_week_bg week_td_9 '; if($year->show_active == 1) { if($year->week_from <=9 && $year->week_to >=9) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 9</th>
	                    <th align="right" class="payp30_week_bg week_td_10 '; if($year->show_active == 1) { if($year->week_from <=10 && $year->week_to >=10) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 10</th>
	                    <th align="right" class="payp30_week_bg week_td_11 '; if($year->show_active == 1) { if($year->week_from <=11 && $year->week_to >=11) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 11</th>
	                    <th align="right" class="payp30_week_bg week_td_12 '; if($year->show_active == 1) { if($year->week_from <=12 && $year->week_to >=12) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 12</th>
	                    <th align="right" class="payp30_week_bg week_td_13 '; if($year->show_active == 1) { if($year->week_from <=13 && $year->week_to >=13) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 13</th>
	                    <th align="right" class="payp30_week_bg week_td_14 '; if($year->show_active == 1) { if($year->week_from <=14 && $year->week_to >=14) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 14</th>
	                    <th align="right" class="payp30_week_bg week_td_15 '; if($year->show_active == 1) { if($year->week_from <=15 && $year->week_to >=15) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 15</th>
	                    <th align="right" class="payp30_week_bg week_td_16 '; if($year->show_active == 1) { if($year->week_from <=16 && $year->week_to >=16) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 16</th>
	                    <th align="right" class="payp30_week_bg week_td_17 '; if($year->show_active == 1) { if($year->week_from <=17 && $year->week_to >=17) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 17</th>
	                    <th align="right" class="payp30_week_bg week_td_18 '; if($year->show_active == 1) { if($year->week_from <=18 && $year->week_to >=18) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 18</th>
	                    <th align="right" class="payp30_week_bg week_td_19 '; if($year->show_active == 1) { if($year->week_from <=19 && $year->week_to >=19) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 19</th>
	                    <th align="right" class="payp30_week_bg week_td_20 '; if($year->show_active == 1) { if($year->week_from <=20 && $year->week_to >=20) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 20</th>
	                    <th align="right" class="payp30_week_bg week_td_21 '; if($year->show_active == 1) { if($year->week_from <=21 && $year->week_to >=21) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 21</th>
	                    <th align="right" class="payp30_week_bg week_td_22 '; if($year->show_active == 1) { if($year->week_from <=22 && $year->week_to >=22) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 22</th>
	                    <th align="right" class="payp30_week_bg week_td_23 '; if($year->show_active == 1) { if($year->week_from <=23 && $year->week_to >=23) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 23</th>
	                    <th align="right" class="payp30_week_bg week_td_24 '; if($year->show_active == 1) { if($year->week_from <=24 && $year->week_to >=24) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 24</th>
	                    <th align="right" class="payp30_week_bg week_td_25 '; if($year->show_active == 1) { if($year->week_from <=25 && $year->week_to >=25) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 25</th>
	                    <th align="right" class="payp30_week_bg week_td_26 '; if($year->show_active == 1) { if($year->week_from <=26 && $year->week_to >=26) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 26</th>
	                    <th align="right" class="payp30_week_bg week_td_27 '; if($year->show_active == 1) { if($year->week_from <=27 && $year->week_to >=27) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 27</th>
	                    <th align="right" class="payp30_week_bg week_td_28 '; if($year->show_active == 1) { if($year->week_from <=28 && $year->week_to >=28) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 28</th>
	                    <th align="right" class="payp30_week_bg week_td_29 '; if($year->show_active == 1) { if($year->week_from <=29 && $year->week_to >=29) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 29</th>
	                    <th align="right" class="payp30_week_bg week_td_30 '; if($year->show_active == 1) { if($year->week_from <=30 && $year->week_to >=30) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 30</th>
	                    <th align="right" class="payp30_week_bg week_td_31 '; if($year->show_active == 1) { if($year->week_from <=31 && $year->week_to >=31) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 31</th>
	                    <th align="right" class="payp30_week_bg week_td_32 '; if($year->show_active == 1) { if($year->week_from <=32 && $year->week_to >=32) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 32</th>
	                    <th align="right" class="payp30_week_bg week_td_33 '; if($year->show_active == 1) { if($year->week_from <=33 && $year->week_to >=33) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 33</th>
	                    <th align="right" class="payp30_week_bg week_td_34 '; if($year->show_active == 1) { if($year->week_from <=34 && $year->week_to >=34) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 34</th>
	                    <th align="right" class="payp30_week_bg week_td_35 '; if($year->show_active == 1) { if($year->week_from <=35 && $year->week_to >=35) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 35</th>
	                    <th align="right" class="payp30_week_bg week_td_36 '; if($year->show_active == 1) { if($year->week_from <=36 && $year->week_to >=36) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 36</th>
	                    <th align="right" class="payp30_week_bg week_td_37 '; if($year->show_active == 1) { if($year->week_from <=37 && $year->week_to >=37) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 37</th>
	                    <th align="right" class="payp30_week_bg week_td_38 '; if($year->show_active == 1) { if($year->week_from <=38 && $year->week_to >=38) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 38</th>
	                    <th align="right" class="payp30_week_bg week_td_39 '; if($year->show_active == 1) { if($year->week_from <=39 && $year->week_to >=39) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 39</th>
	                    <th align="right" class="payp30_week_bg week_td_40 '; if($year->show_active == 1) { if($year->week_from <=40 && $year->week_to >=40) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 40</th>
	                    <th align="right" class="payp30_week_bg week_td_41 '; if($year->show_active == 1) { if($year->week_from <=41 && $year->week_to >=41) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 41</th>
	                    <th align="right" class="payp30_week_bg week_td_42 '; if($year->show_active == 1) { if($year->week_from <=42 && $year->week_to >=42) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 42</th>
	                    <th align="right" class="payp30_week_bg week_td_43 '; if($year->show_active == 1) { if($year->week_from <=43 && $year->week_to >=43) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 43</th>
	                    <th align="right" class="payp30_week_bg week_td_44 '; if($year->show_active == 1) { if($year->week_from <=44 && $year->week_to >=44) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 44</th>
	                    <th align="right" class="payp30_week_bg week_td_45 '; if($year->show_active == 1) { if($year->week_from <=45 && $year->week_to >=45) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 45</th>
	                    <th align="right" class="payp30_week_bg week_td_46 '; if($year->show_active == 1) { if($year->week_from <=46 && $year->week_to >=46) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 46</th>
	                    <th align="right" class="payp30_week_bg week_td_47 '; if($year->show_active == 1) { if($year->week_from <=47 && $year->week_to >=47) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 47</th>
	                    <th align="right" class="payp30_week_bg week_td_48 '; if($year->show_active == 1) { if($year->week_from <=48 && $year->week_to >=48) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 48</th>
	                    <th align="right" class="payp30_week_bg week_td_49 '; if($year->show_active == 1) { if($year->week_from <=49 && $year->week_to >=49) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 49</th>
	                    <th align="right" class="payp30_week_bg week_td_50 '; if($year->show_active == 1) { if($year->week_from <=50 && $year->week_to >=50) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 50</th>
	                    <th align="right" class="payp30_week_bg week_td_51 '; if($year->show_active == 1) { if($year->week_from <=51 && $year->week_to >=51) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 51</th>
	                    <th align="right" class="payp30_week_bg week_td_52 '; if($year->show_active == 1) { if($year->week_from <=52 && $year->week_to >=52) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 52</th>
	                    <th align="right" class="payp30_week_bg week_td_53 '; if($year->show_active == 1) { if($year->week_from <=53 && $year->week_to >=53) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Week 53</th>
	                    <th align="right" class="payp30_month_bg month_td_1 '; if($year->show_active == 1) { if($year->month_from <=1 && $year->month_to >=1) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Jan '.$year->year_name.'</th>
	                    <th align="right" class="payp30_month_bg month_td_2 '; if($year->show_active == 1) { if($year->month_from <=2 && $year->month_to >=2) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Feb '.$year->year_name.'</th>
	                    <th align="right" class="payp30_month_bg month_td_3 '; if($year->show_active == 1) { if($year->month_from <=3 && $year->month_to >=3) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Mar '.$year->year_name.'</th>
	                    <th align="right" class="payp30_month_bg month_td_4 '; if($year->show_active == 1) { if($year->month_from <=4 && $year->month_to >=4) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Apr '.$year->year_name.'</th>
	                    <th align="right" class="payp30_month_bg month_td_5 '; if($year->show_active == 1) { if($year->month_from <=5 && $year->month_to >=5) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">May '.$year->year_name.'</th>
	                    <th align="right" class="payp30_month_bg month_td_6 '; if($year->show_active == 1) { if($year->month_from <=6 && $year->month_to >=6) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Jun '.$year->year_name.'</th>
	                    <th align="right" class="payp30_month_bg month_td_7 '; if($year->show_active == 1) { if($year->month_from <=7 && $year->month_to >=7) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Jul '.$year->year_name.'</th>
	                    <th align="right" class="payp30_month_bg month_td_8 '; if($year->show_active == 1) { if($year->month_from <=8 && $year->month_to >=8) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Aug '.$year->year_name.'</th>
	                    <th align="right" class="payp30_month_bg month_td_9 '; if($year->show_active == 1) { if($year->month_from <=9 && $year->month_to >=9) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Sep '.$year->year_name.'</th>
	                    <th align="right" class="payp30_month_bg month_td_10 '; if($year->show_active == 1) { if($year->month_from <=10 && $year->month_to >=10) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Oct '.$year->year_name.'</th>
	                    <th align="right" class="payp30_month_bg month_td_11 '; if($year->show_active == 1) { if($year->month_from <=11 && $year->month_to >=11) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Nov '.$year->year_name.'</th>
	                    <th align="right" class="payp30_month_bg month_td_12 '; if($year->show_active == 1) { if($year->month_from <=12 && $year->month_to >=12) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='" style="text-align:right;">Dec '.$year->year_name.'</th>
	                </tr>
	              </thead>
	              <tbody>
	                <tr class="task_row_'.$task->id.'">
	                    <td style="border-right: 1px solid #000 !important; border-left: 1px solid #000 !important; border: 1px solid #fff;" valign="top">1</td>
	                    <td colspan="3"  style="border-bottom: 0px; text-align: left; height:110px;"> 
	                      <div style="width:400px; position:absolute; margin-top:-50px;">
	                        <b style="font-size:18px;">'.$task->task_name.'</b><br/>
	                        Emp No. '.$task->task_enumber.'<br/>
	                        Action: '.$action.'<br/>
	                        PAY: '.$pay.'<br/>
	                        Email: '.$email.'                   
	                      </div>
	                    </td> 
	                    <td style="text-align: center;" valign="bottom">ROS Liability</td>
	                    <td style="text-align: center;" valign="bottom">Task Liability</td>
	                    <td valign="bottom">Diff</td>
	                    
	                    <td colspan="2" style="text-align:center; border-right:1px solid #000;"">
	                    
	                    <input type="hidden" class="active_month_class payetask_'.$task->id.'" value="'.$task->active_month.'" />
	                    </td>
	                    <td style="padding:0px 10px;"><a href="javascript:"><i class="fa fa-refresh refresh_liability" data-element="'.$task->id.'"></i></a></td>
	                    <td align="left" class="payp30_week_bg week1 '; if($year->show_active == 1) { if($year->week_from <=1 && $year->week_to >=1) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week1.'</td>
	                    <td align="left" class="payp30_week_bg week2 '; if($year->show_active == 1) { if($year->week_from <=2 && $year->week_to >=2) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week2.'</td>
	                    <td align="left" class="payp30_week_bg week3 '; if($year->show_active == 1) { if($year->week_from <=3 && $year->week_to >=3) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week3.'</td>
	                    <td align="left" class="payp30_week_bg week4 '; if($year->show_active == 1) { if($year->week_from <=4 && $year->week_to >=4) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week4.'</td>
	                    <td align="left" class="payp30_week_bg week5 '; if($year->show_active == 1) { if($year->week_from <=5 && $year->week_to >=5) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week5.'</td>
	                    <td align="left" class="payp30_week_bg week6 '; if($year->show_active == 1) { if($year->week_from <=6 && $year->week_to >=6) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week6.'</td>
	                    <td align="left" class="payp30_week_bg week7 '; if($year->show_active == 1) { if($year->week_from <=7 && $year->week_to >=7) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week7.'</td>
	                    <td align="left" class="payp30_week_bg week8 '; if($year->show_active == 1) { if($year->week_from <=8 && $year->week_to >=8) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week8.'</td>
	                    <td align="left" class="payp30_week_bg week9 '; if($year->show_active == 1) { if($year->week_from <=9 && $year->week_to >=9) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week9.'</td>
	                    <td align="left" class="payp30_week_bg week10 '; if($year->show_active == 1) { if($year->week_from <=10 && $year->week_to >=10) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week10.'</td>
	                    <td align="left" class="payp30_week_bg week11 '; if($year->show_active == 1) { if($year->week_from <=11 && $year->week_to >=11) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week11.'</td>
	                    <td align="left" class="payp30_week_bg week12 '; if($year->show_active == 1) { if($year->week_from <=12 && $year->week_to >=12) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week12.'</td>
	                    <td align="left" class="payp30_week_bg week13 '; if($year->show_active == 1) { if($year->week_from <=13 && $year->week_to >=13) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week13.'</td>
	                    <td align="left" class="payp30_week_bg week14 '; if($year->show_active == 1) { if($year->week_from <=14 && $year->week_to >=14) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week14.'</td>
	                    <td align="left" class="payp30_week_bg week15 '; if($year->show_active == 1) { if($year->week_from <=15 && $year->week_to >=15) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week15.'</td>
	                    <td align="left" class="payp30_week_bg week16 '; if($year->show_active == 1) { if($year->week_from <=16 && $year->week_to >=16) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week16.'</td>
	                    <td align="left" class="payp30_week_bg week17 '; if($year->show_active == 1) { if($year->week_from <=17 && $year->week_to >=17) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week17.'</td>
	                    <td align="left" class="payp30_week_bg week18 '; if($year->show_active == 1) { if($year->week_from <=18 && $year->week_to >=18) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week18.'</td>
	                    <td align="left" class="payp30_week_bg week19 '; if($year->show_active == 1) { if($year->week_from <=19 && $year->week_to >=19) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week19.'</td>
	                    <td align="left" class="payp30_week_bg week20 '; if($year->show_active == 1) { if($year->week_from <=20 && $year->week_to >=20) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week20.'</td>
	                    <td align="left" class="payp30_week_bg week21 '; if($year->show_active == 1) { if($year->week_from <=21 && $year->week_to >=21) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week21.'</td>
	                    <td align="left" class="payp30_week_bg week22 '; if($year->show_active == 1) { if($year->week_from <=22 && $year->week_to >=22) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week22.'</td>
	                    <td align="left" class="payp30_week_bg week23 '; if($year->show_active == 1) { if($year->week_from <=23 && $year->week_to >=23) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week23.'</td>
	                    <td align="left" class="payp30_week_bg week24 '; if($year->show_active == 1) { if($year->week_from <=24 && $year->week_to >=24) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week24.'</td>
	                    <td align="left" class="payp30_week_bg week25 '; if($year->show_active == 1) { if($year->week_from <=25 && $year->week_to >=25) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week25.'</td>
	                    <td align="left" class="payp30_week_bg week26 '; if($year->show_active == 1) { if($year->week_from <=26 && $year->week_to >=26) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week26.'</td>
	                    <td align="left" class="payp30_week_bg week27 '; if($year->show_active == 1) { if($year->week_from <=27 && $year->week_to >=27) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week27.'</td>
	                    <td align="left" class="payp30_week_bg week28 '; if($year->show_active == 1) { if($year->week_from <=28 && $year->week_to >=28) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week28.'</td>
	                    <td align="left" class="payp30_week_bg week29 '; if($year->show_active == 1) { if($year->week_from <=29 && $year->week_to >=29) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week29.'</td>
	                    <td align="left" class="payp30_week_bg week30 '; if($year->show_active == 1) { if($year->week_from <=30 && $year->week_to >=30) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week30.'</td>
	                    <td align="left" class="payp30_week_bg week31 '; if($year->show_active == 1) { if($year->week_from <=31 && $year->week_to >=31) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week31.'</td>
	                    <td align="left" class="payp30_week_bg week32 '; if($year->show_active == 1) { if($year->week_from <=32 && $year->week_to >=32) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week32.'</td>
	                    <td align="left" class="payp30_week_bg week33 '; if($year->show_active == 1) { if($year->week_from <=33 && $year->week_to >=33) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week33.'</td>
	                    <td align="left" class="payp30_week_bg week34 '; if($year->show_active == 1) { if($year->week_from <=34 && $year->week_to >=34) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week34.'</td>
	                    <td align="left" class="payp30_week_bg week35 '; if($year->show_active == 1) { if($year->week_from <=35 && $year->week_to >=35) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week35.'</td>
	                    <td align="left" class="payp30_week_bg week36 '; if($year->show_active == 1) { if($year->week_from <=36 && $year->week_to >=36) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week36.'</td>
	                    <td align="left" class="payp30_week_bg week37 '; if($year->show_active == 1) { if($year->week_from <=37 && $year->week_to >=37) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week37.'</td>
	                    <td align="left" class="payp30_week_bg week38 '; if($year->show_active == 1) { if($year->week_from <=38 && $year->week_to >=38) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week38.'</td>
	                    <td align="left" class="payp30_week_bg week39 '; if($year->show_active == 1) { if($year->week_from <=39 && $year->week_to >=39) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week39.'</td>
	                    <td align="left" class="payp30_week_bg week40 '; if($year->show_active == 1) { if($year->week_from <=40 && $year->week_to >=40) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week40.'</td>
	                    <td align="left" class="payp30_week_bg week41 '; if($year->show_active == 1) { if($year->week_from <=41 && $year->week_to >=41) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week41.'</td>
	                    <td align="left" class="payp30_week_bg week42 '; if($year->show_active == 1) { if($year->week_from <=42 && $year->week_to >=42) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week42.'</td>
	                    <td align="left" class="payp30_week_bg week43 '; if($year->show_active == 1) { if($year->week_from <=43 && $year->week_to >=43) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week43.'</td>
	                    <td align="left" class="payp30_week_bg week44 '; if($year->show_active == 1) { if($year->week_from <=44 && $year->week_to >=44) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week44.'</td>
	                    <td align="left" class="payp30_week_bg week45 '; if($year->show_active == 1) { if($year->week_from <=45 && $year->week_to >=45) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week45.'</td>
	                    <td align="left" class="payp30_week_bg week46 '; if($year->show_active == 1) { if($year->week_from <=46 && $year->week_to >=46) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week46.'</td>
	                    <td align="left" class="payp30_week_bg week47 '; if($year->show_active == 1) { if($year->week_from <=47 && $year->week_to >=47) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week47.'</td>
	                    <td align="left" class="payp30_week_bg week48 '; if($year->show_active == 1) { if($year->week_from <=48 && $year->week_to >=48) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week48.'</td>
	                    <td align="left" class="payp30_week_bg week49 '; if($year->show_active == 1) { if($year->week_from <=49 && $year->week_to >=49) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week49.'</td>
	                    <td align="left" class="payp30_week_bg week50 '; if($year->show_active == 1) { if($year->week_from <=50 && $year->week_to >=50) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week50.'</td>
	                    <td align="left" class="payp30_week_bg week51 '; if($year->show_active == 1) { if($year->week_from <=51 && $year->week_to >=51) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week51.'</td>
	                    <td align="left" class="payp30_week_bg week52 '; if($year->show_active == 1) { if($year->week_from <=52 && $year->week_to >=52) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week52.'</td>
	                    <td align="left" class="payp30_week_bg week53 '; if($year->show_active == 1) { if($year->week_from <=53 && $year->week_to >=53) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$week53.'</td>
	                    <td align="left" class="payp30_month_bg month1 '; if($year->show_active == 1) { if($year->month_from <=1 && $year->month_to >=1) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$month1.'</td>
	                    <td align="left" class="payp30_month_bg month2 '; if($year->show_active == 1) { if($year->month_from <=2 && $year->month_to >=2) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$month2.'</td>
	                    <td align="left" class="payp30_month_bg month3 '; if($year->show_active == 1) { if($year->month_from <=3 && $year->month_to >=3) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$month3.'</td>
	                    <td align="left" class="payp30_month_bg month4 '; if($year->show_active == 1) { if($year->month_from <=4 && $year->month_to >=4) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$month4.'</td>
	                    <td align="left" class="payp30_month_bg month5 '; if($year->show_active == 1) { if($year->month_from <=5 && $year->month_to >=5) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$month5.'</td>
	                    <td align="left" class="payp30_month_bg month6 '; if($year->show_active == 1) { if($year->month_from <=6 && $year->month_to >=6) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$month6.'</td>
	                    <td align="left" class="payp30_month_bg month7 '; if($year->show_active == 1) { if($year->month_from <=7 && $year->month_to >=7) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$month7.'</td>
	                    <td align="left" class="payp30_month_bg month8 '; if($year->show_active == 1) { if($year->month_from <=8 && $year->month_to >=8) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$month8.'</td>
	                    <td align="left" class="payp30_month_bg month9 '; if($year->show_active == 1) { if($year->month_from <=9 && $year->month_to >=9) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$month9.'</td>
	                    <td align="left" class="payp30_month_bg month10 '; if($year->show_active == 1) { if($year->month_from <=10 && $year->month_to >=10) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$month10.'</td>
	                    <td align="left" class="payp30_month_bg month11 '; if($year->show_active == 1) { if($year->month_from <=11 && $year->month_to >=11) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$month11.'</td>
	                    <td align="left" class="payp30_month_bg month12 '; if($year->show_active == 1) { if($year->month_from <=12 && $year->month_to >=12) { $output.='hide_column'; } else { $output.='show_column'; } } $output.='">'.$month12.'</td>
	                </tr>
	              </tbody>
	                '.$output_row.'
	            </table> 
	      </div>  
	            ';
	       echo json_encode(array("output" => $output, "show_active" => $year->show_active, "week_from" => $year->week_from,"week_to" => $year->week_to,"month_from" => $year->month_from,"month_to" => $year->month_to));
	}
	public function paye_p30_create_new_year()
	{
		$last_year = DB::table('paye_p30_year')->orderBy('year_id', 'desc')->first();
		$year_name = $last_year->year_name + 1;
		$data['year_name'] = $year_name;
		$id = DB::table('paye_p30_year')->insertGetid($data);
		return redirect('user/paye_p30_manage/'.base64_encode($id))->with('message', 'New Year Created Successfully.');
	}
	public function paye_p30_week_selected()
	{
		$status = Input::get("status");
		$value = Input::get("value");
		$year = Input::get("year");
		if($status == "from")
		{
			$data['selected_week_from'] = $value;
		}
		if($status == "to")
		{
			$data['selected_week_to'] = $value;
		}

		DB::table("paye_p30_year")->where('year_id',$year)->update($data);
	}
	public function paye_p30_month_selected()
	{
		$status = Input::get("status");
		$value = Input::get("value");
		$year = Input::get("year");
		if($status == "from")
		{
			$data['selected_month_from'] = $value;
		}
		if($status == "to")
		{
			$data['selected_month_to'] = $value;
		}

		DB::table("paye_p30_year")->where('year_id',$year)->update($data);
	}
}

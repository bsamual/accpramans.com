<?php namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\Year;
use Session;
class YearController extends Controller {

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
	public function __construct(Year $year)
	{
		$this->middleware('adminauth');
		$this->year = $year;
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function manageyear()
	{
		$year = DB::table('year')->get();
		return view('admin/year', array('title' => 'Dashboard', 'yearlist' => $year));
	}
	public function deactiveyear($id=""){
		$id = base64_decode($id);
		$deactive =  1;
		DB::table('year')->where('year_id', $id)->update(['year_status' => $deactive ]);
		return redirect('admin/manage_year')->with('message','Deactive Success');
	}
	public function activeyear($id=""){
		$id = base64_decode($id);
		$active =  0;
		DB::table('year')->where('year_id', $id)->update(['year_status' => $active ]);
		return redirect('admin/manage_year')->with('message','Active Success');
	}
	public function addyear(){
		$name = Input::get('name');
		$enddate = Input::get('end_date');
		$exp = explode('-',$enddate);
		$date = $exp[2].'-'.$exp[0].'-'.$exp[1];
		$id = DB::table('year')->insertGetId(['year_name' => $name,'end_date' => $date]);
		DB::table('week')->insert(['year' => $id,'week' => 1]);
		DB::table('month')->insert(['year' => $id,'month' => 1]);
		return redirect('admin/manage_year')->with('message','Add Success');
	}
	public function deleteyear($id=""){
		$id = base64_decode($id);
		DB::table('year')->where('year_id', $id)->delete();
		return redirect('admin/manage_year')->with('message','Delete Success');
	}
	public function edityear($id=""){
		$id = base64_decode($id);
		$result = DB::table('year')->where('year_id', $id)->first();
		echo json_encode(array('name' => $result->year_name, 'id' => $result->year_id));
	}
	public function updateyear(){
		$name = Input::get('name');
		$id = Input::get('id');

		DB::table('year')->where('year_id', $id)->update(['year_name' => $name]);
		return redirect('admin/manage_year')->with('message','Update Success');
	}
	public function checkyear(){		
		$ytext = Input::get('ytext');

		$validate = DB::table("year")->where('year_name', $ytext)->first();
		
		if(count($validate))
		{
			echo 1;
		}
		else{
			echo 0;
		}
	}


}

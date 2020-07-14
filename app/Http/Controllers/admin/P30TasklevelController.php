<?php namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\p30_tasklevel;
use Session;
class P30TasklevelController extends Controller {

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
	public function __construct(p30_tasklevel $p30_tasklevel)
	{
		$this->middleware('adminauth');
		$this->p30_tasklevel = $p30_tasklevel;
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function tasklevel()
	{
		$tasklevel = DB::table('p30_tasklevel')->get();
		return view('admin/p30/tasklevel', array('title' => 'P30 Task Level', 'tasklevellist' => $tasklevel));
	}
	public function deactivetasklevel($id=""){
		$id = base64_decode($id);
		$deactive =  1;
		DB::table('p30_tasklevel')->where('id', $id)->update(['status' => $deactive ]);
		return redirect::back()->with('message','Deactive Success');
	}
	public function activetasklevel($id=""){
		$id = base64_decode($id);
		$active =  0;
		DB::table('p30_tasklevel')->where('id', $id)->update(['status' => $active ]);
		return redirect::back()->with('message','Active Success');
	}
	public function addtasklevel(){
		$name = Input::get('name');		
		DB::table('p30_tasklevel')->insert(['name' => $name]);
		return redirect::back()->with('message','Add Success');
	}
	public function edittasklevel($id=""){
		$id = base64_decode($id);
		$result = DB::table('p30_tasklevel')->where('id', $id)->first();
		echo json_encode(array('name' => $result->name, 'id' => $result->id));
	}
	public function updatetasklevel(){
		$name = Input::get('name');
		$id = Input::get('id');
		
		DB::table('p30_tasklevel')->where('id', $id)->update(['name' => $name]);
		return redirect::back()->with('message','Update Success');
	}


}

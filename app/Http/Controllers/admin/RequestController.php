<?php namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\Taskyear;
use App\Year;
use App\User;
use URL;
use Session;
class RequestController extends Controller {

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
	public function __construct(Taskyear $taskyear, Year $year, User $user )
	{
		$this->middleware('adminauth');
		$this->taskyear = $taskyear;
		$this->year = $year;
		$this->user = $user;
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function setuprequestcategory()
	{
		$request = DB::table('request_category')->get();
		return view('admin/request/request', array('title' => 'Setup Request Categories', 'requestlist' => $request));
	}

	public function deactiverequest($id=""){
		$id = base64_decode($id);
		$deactive =  1;
		DB::table('request_category')->where('category_id', $id)->update(['status' => $deactive ]);
		return redirect::back()->with('message','Deactive Success');
		
	}
	public function activerequest($id=""){
		$id = base64_decode($id);
		$deactive =  0;
		DB::table('request_category')->where('category_id', $id)->update(['status' => $deactive ]);
		return redirect::back()->with('message','Active Success');		
	}

	public function deleterequest($id=""){
		$id = base64_decode($id);
		DB::table('request_category')->where('category_id', $id)->delete();
		DB::table('request_sub_category')->where('category_id', $id)->delete();
		return redirect::back()->with('message','Delete Success');

	}

	public function requestsignature(){
		$id = base64_decode(Input::get('id'));
		$value = Input::get('value');

		DB::table('request_category')->where('category_id', $id)->update(['signature' => $value]);
	}

	public function requestadd(){
		$data['category_name'] = Input::get('category');
		$id = DB::table('request_category')->insertGetid($data);
		$items = Input::get('request_item');
		if(count($items))
		{
			foreach($items as $item)
			{
				$dataitem['sub_category_name'] = $item;
				$dataitem['category_id'] = $id;
				DB::table('request_sub_category')->insert($dataitem);
			}
		}
		return redirect::back()->with('message','category Add Success');
	}
	public function request_edit_category()
	{
		$id = base64_decode(Input::get('id'));
		$category_details = DB::table('request_category')->where('category_id',$id)->first();
		$subcat_details = DB::table('request_sub_category')->where('category_id',$id)->get();
		$subcate = '';
		if(count($subcat_details))
		{
			foreach($subcat_details as $sub)
			{
				if($subcate == "")
				{
					$subcate = $sub->sub_category_name;
				}
				else{
					$subcate = $subcate.'||'.$sub->sub_category_name;
				}
			}
		}
		echo json_encode(array("category_name" => $category_details->category_name, 'sub_category_name' => $subcate));
	}
	public function request_edit_form()
	{
		$id = base64_decode(Input::get('category_id_edit'));
		$data['category_name'] = Input::get('category_edit');
		DB::table('request_category')->where('category_id',$id)->update($data);
		$subcat_details = DB::table('request_sub_category')->where('category_id',$id)->get();
		$items = Input::get('request_item_edit');
	
		if(count($items))
		{
			foreach($items as $key => $item)
			{
				$dataitem['sub_category_name'] = $item;
				$dataitem['category_id'] = $id;

				$subid = $subcat_details[$key]->sub_category_id;
				DB::table('request_sub_category')->where('sub_category_id',$subid)->update($dataitem);
			}
		}
		return redirect::back()->with('message','Category Update Success');
	}
}

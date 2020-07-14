<?php namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\Letterpad;
use Session;
use URL;
class LetterController extends Controller {

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
	public function __construct(letterpad $letterpad)
	{
		$this->middleware('adminauth');
		$this->letterpad = $letterpad;
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function manage_letterpad()
	{
		$user = DB::table('letterpad')->get();
		return view('admin/rct/letterpad', array('title' => 'Letterpad Background Image', 'userlist' => $user));
	}
	
	public function editletterpad($id=""){
		$id = base64_decode($id);
		$result = DB::table('letterpad')->where('id', $id)->first();
		$image = URL::to('uploads/letterpad/'.$result->image);
		echo json_encode(array('name' => $result->name, 'image' =>  $image, 'salution' => $result->salution, 'id' => $result->id));
	}
	public function updateletterpad(){		
		$id = Input::get('id');
		$salution = Input::get('salution');
		
		$imagesname = $_FILES['letterpadimage']['name'];
		$imagesname_temp = $_FILES['letterpadimage']['tmp_name'];
		$uploaddir = 'uploads/letterpad/';
		move_uploaded_file($imagesname_temp, $uploaddir.$imagesname);
		

		DB::table('letterpad')->where('id', $id)->update(['image' => $imagesname, 'salution' => $salution]);
		return redirect::back()->with('message','Update Success');
	}


}

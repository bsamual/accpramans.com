<?php namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\RctClients;
use Session;
use URL;
class RctClientsController extends Controller {

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
	public function __construct(rctclients $rctclients)
	{
		$this->middleware('adminauth');
		$this->rctclients = $rctclients;
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function managerctclients()
	{
		$user = DB::table('rctclients')->get();
		return view('admin/rct/clients', array('title' => 'rctclients', 'userlist' => $user));
	}
	public function deactiverctclients($id=""){
		$id = base64_decode($id);
		$deactive =  1;
		DB::table('rctclients')->where('client_id', $id)->update(['status' => $deactive ]);
		return redirect('admin/manage_rctclients')->with('message','Deactive Success');
	}
	public function activerctclients($id=""){
		$id = base64_decode($id);
		$active =  0;
		DB::table('rctclients')->where('client_id', $id)->update(['status' => $active ]);
		return redirect('admin/manage_rctclients')->with('message','Active Success');
	}
	public function addrctclients(){
		$name = Input::get('name');
		$lname = Input::get('lname');
		$taxnumber = Input::get('taxnumber');
		$email = Input::get('email');
		$secondary = Input::get('secondaryemail');
		$check_email = DB::table('rctclients')->where('email',$email)->first();
		$check_tax = DB::table('rctclients')->where('taxnumber',$taxnumber)->first();
		if(count($check_email))
		{
			return redirect('admin/manage_rctclients')->with('message','Email Already Exists');
		}
		
		elseif(count($check_tax))
		{
			return redirect('admin/manage_rctclients')->with('message','Tax Number Already Exists');
		}
		else{
			DB::table('rctclients')->insert(['firstname' => $name, 'lastname' => $lname, 'taxnumber' => $taxnumber, 'email' => $email,'secondary_email' => $secondary]);
			return redirect('admin/manage_rctclients')->with('message','Add Success');
		}
		
	}
	public function deleterctclients($id=""){
		$id = base64_decode($id);
		DB::table('rctclients')->where('client_id', $id)->delete();
		return redirect('admin/manage_rctclients')->with('message','Delete Success');
	}
	public function editrctclients($id=""){
		$id = base64_decode($id);
		$result = DB::table('rctclients')->where('client_id', $id)->first();
		echo json_encode(array('name' => $result->firstname, 'lname' => $result->lastname, 'taxnumber' => $result->taxnumber, 'email' =>  $result->email, 'secondaryemail' =>  $result->secondary_email, 'id' => $result->client_id));
	}
	public function updaterctclients(){
		$name = Input::get('name');
		$id = Input::get('id');
		$lname = Input::get('lname');
		$taxnumber = Input::get('taxnumber');
		$email = Input::get('email');
		$check_email = DB::table('rctclients')->where('email',$email)->where('client_id','!=',$id)->first();
		$check_tax = DB::table('rctclients')->where('taxnumber',$taxnumber)->where('client_id','!=',$id)->first();

		$secondary = Input::get('secondaryemail');
		if(count($check_email))
		{
			return redirect('admin/manage_rctclients')->with('message','Email Already Exists');
		}
		
		elseif(count($check_tax))
		{
			return redirect('admin/manage_rctclients')->with('message','Tax Number Already Exists');
		}
		else{
			DB::table('rctclients')->where('client_id', $id)->update(['firstname' => $name, 'lastname' => $lname, 'taxnumber' => $taxnumber,  'email' => $email,'secondary_email' => $secondary]);
			return redirect('admin/manage_rctclients')->with('message','Update Success');
		}
	}

	public function client_checkemail(){
		$id = Input::get('id');
		$email = Input::get('email');	
		if($id != "")
		{
			$validate = DB::table("rctclients")->where('email',$email)->where('client_id','!=', $id)->count();
			$secondary_validate = DB::table("rctclients")->where('secondary_email',$email)->where('client_id','!=', $id)->count();
		}
		else{
			$validate = DB::table("rctclients")->where('email',$email)->count();
			$secondary_validate = DB::table("rctclients")->where('secondary_email',$email)->count();
		}

		if($validate != 0 || $secondary_validate != 0)
			$valid = false;
		else
			$valid = true;
		echo json_encode($valid);
		exit;
	}
	public function client_checktax(){
		$id = Input::get('id');
		$tax = Input::get('taxnumber');	
		if($id != "")
		{
			$validate = DB::table("rctclients")->where('taxnumber',$tax)->where('client_id','!=', $id)->count();
		}
		else{
			$validate = DB::table("rctclients")->where('taxnumber',$tax)->count();
		}
		if($validate!=0)
			$valid = false;
		else
			$valid = true;
		echo json_encode($valid);
		exit;
	}
	public function rctclientsearch()
	{
		$value = Input::get('term');
		$details = DB::table('rctclients')->where('firstname', 'LIKE','%'.$value.'%')->groupBy('firstname')->get();

		$data=array();
		foreach ($details as $single) {
                $data[]=array('value'=>$single->firstname,'id'=>$single->client_id);
        }
         if(count($data))
             return $data;
        else
            return ['value'=>'No Result Found','id'=>''];
	}
	
	public function rctclientsearchselect(){
		$clientname = Input::get('value');
		$userlist = DB::table('rctclients')->where('firstname',$clientname)->get();
		$output = '';
            $i=1;
            if(count($userlist)){              
              foreach($userlist as $user){
                
                	
        $output.='<tr>';
        $output.='<td>'.$i.'</td>';
        $output.='<td align="center">'.$user->firstname.'</td>';
        $output.='<td align="center">'.$user->lastname.'</td>';
        $output.='<td align="center">'.$user->taxnumber.'</td>';
        $output.='<td align="center">'.$user->email.'</td>';
        $output.='<td align="center">'.$user->secondary_email.'</td>';
        $output.='<td align="center">';
        		if($user->status == 0){
        			 $output.='<a href='.URL::to('admin/deactive_rctclients',base64_encode($user->client_id)).' title="Hide Clients"><i style="color:#00b348;" class="fa fa-check" aria-hidden="true"></i></a>';
        		}
        		else{
        			$output.='<a href='.URL::to('admin/active_rctclients',base64_encode($user->client_id)).' title="Unhide Clients"><i style="color:#f00;" class="fa fa-times" aria-hidden="true"></i></a>';
        		}

        $output.='&nbsp; &nbsp
            	<a href="javascript:" class="editclass" id="'.base64_encode($user->client_id).'" title="Edit Clients"><i class="fa fa-pencil-square editclass" id="'.base64_encode($user->client_id).'" aria-hidden="true"></i></a>&nbsp; &nbsp;
                <a href="'.URL::to('user/delete_rctclients/'.base64_encode($user->client_id)).'" title="Delete Clients" class="delete_user"><i class="fa fa fa-trash delete_user" aria-hidden="true"></i></a>
         </td>';
         $output.='</tr>';
              $i++;
                }
              }              
            
            if($i == 1)
            {
              $output.='<tr><td colspan="9" align="center">Empty</td></tr>';
            }
            echo $output;              
	}
	public function clienttaxsearch()
	{
		$value = Input::get('term');
		$details = DB::table('rctclients')->where('taxnumber', 'LIKE','%'.$value.'%')->get();

		$data=array();
		foreach ($details as $single) {
                $data[]=array('value'=>$single->taxnumber,'id'=>$single->client_id);
        }
         if(count($data))
             return $data;
        else
            return ['value'=>'No Result Found','id'=>''];
	}
	
	public function clienttaxsearchselect(){
		$taxnumber = Input::get('value');
		$userlist = DB::table('rctclients')->where('taxnumber',$taxnumber)->get();
		$output = '';
            $i=1;
            if(count($userlist)){              
              foreach($userlist as $user){
                
                	
        $output.='<tr>';
        $output.='<td>'.$i.'</td>';
        $output.='<td align="center">'.$user->firstname.'</td>';
        $output.='<td align="center">'.$user->lastname.'</td>';
        $output.='<td align="center">'.$user->taxnumber.'</td>';
        $output.='<td align="center">'.$user->email.'</td>';
        $output.='<td align="center">'.$user->secondary_email.'</td>';
        $output.='<td align="center">';
        		if($user->status == 0){
        			 $output.='<a href='.URL::to('admin/deactive_rctclients',base64_encode($user->client_id)).' title="Hide Clients"><i style="color:#00b348;" class="fa fa-check" aria-hidden="true"></i></a>';
        		}
        		else{
        			$output.='<a href='.URL::to('admin/active_rctclients',base64_encode($user->client_id)).' title="Unhide Clients"><i style="color:#f00;" class="fa fa-times" aria-hidden="true"></i></a>';
        		}

        $output.='&nbsp; &nbsp
            	<a href="javascript:" class="editclass" id="'.base64_encode($user->client_id).'" title="Edit Clients"><i class="fa fa-pencil-square editclass" id="'.base64_encode($user->client_id).'" aria-hidden="true"></i></a>&nbsp; &nbsp;
                <a href="'.URL::to('user/delete_rctclients/'.base64_encode($user->client_id)).'" title="Delete Clients" class="delete_user"><i class="fa fa fa-trash delete_user" aria-hidden="true"></i></a>
         </td>';
         $output.='</tr>';
              $i++;
                }
              }              
            
            if($i == 1)
            {
              $output.='<tr><td colspan="9" align="center">Empty</td></tr>';
            }
            echo $output;              
	}
	public function clientemailsearch()
	{
		$value = Input::get('term');
		$details = DB::table('rctclients')->where('email', 'LIKE','%'.$value.'%')->get();

		$data=array();
		foreach ($details as $single) {
                $data[]=array('value'=>$single->email,'id'=>$single->client_id);
        }
         if(count($data))
             return $data;
        else
            return ['value'=>'No Result Found','id'=>''];
	}
	
	public function clientemailsearchselect(){
		$email = Input::get('value');
		$userlist = DB::table('rctclients')->where('email',$email)->get();
		$output = '';
            $i=1;
            if(count($userlist)){              
              foreach($userlist as $user){
                
                	
        $output.='<tr>';
        $output.='<td>'.$i.'</td>';
        $output.='<td align="center">'.$user->firstname.'</td>';
        $output.='<td align="center">'.$user->lastname.'</td>';
        $output.='<td align="center">'.$user->taxnumber.'</td>';
        $output.='<td align="center">'.$user->email.'</td>';
        $output.='<td align="center">'.$user->secondary_email.'</td>';
        $output.='<td align="center">';
        		if($user->status == 0){
        			 $output.='<a href='.URL::to('admin/deactive_rctclients',base64_encode($user->client_id)).' title="Hide Clients"><i style="color:#00b348;" class="fa fa-check" aria-hidden="true"></i></a>';
        		}
        		else{
        			$output.='<a href='.URL::to('admin/active_rctclients',base64_encode($user->client_id)).' title="Unhide Clients"><i style="color:#f00;" class="fa fa-times" aria-hidden="true"></i></a>';
        		}

        $output.='&nbsp; &nbsp
            	<a href="javascript:" class="editclass" id="'.base64_encode($user->client_id).'" title="Edit Clients"><i class="fa fa-pencil-square editclass" id="'.base64_encode($user->client_id).'" aria-hidden="true"></i></a>&nbsp; &nbsp;
                <a href="'.URL::to('user/delete_rctclients/'.base64_encode($user->client_id)).'" title="Delete Clients" class="delete_user"><i class="fa fa fa-trash delete_user" aria-hidden="true"></i></a>
         </td>';
         $output.='</tr>';
              $i++;
                }
              }              
            
            if($i == 1)
            {
              $output.='<tr><td colspan="9" align="center">Empty</td></tr>';
            }
            echo $output;              
	}




}

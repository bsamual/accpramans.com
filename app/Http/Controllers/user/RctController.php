<?php namespace App\Http\Controllers\user;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\RctClients;
use App\User;
use App\Rcttracker;
use App\Rcttype;
use Session;
use URL;
use PDF;
use Response;
use PHPExcel; 
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class RctController extends Controller {

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
	public function __construct(rctclients $rctclients, rcttracker $rcttracker, rcttype $rcttype)
	{
		$this->middleware('userauth');
		$this->rctclients = $rctclients;
		$this->rcttracker = $rcttracker;
		$this->rcttype = $rcttype;
		date_default_timezone_set("Europe/Dublin");
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function rctclients()
	{
		$user = DB::table('rctclients')->get();		
		return view('user/rct/clients', array('title' => 'rctclients', 'userlist' => $user));
	}
	public function logout(){
		Session::forget("userid");
		return redirect('/');
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
			return redirect('user/rctclients')->with('message','Email Already Exists');
		}
		
		elseif(count($check_tax))
		{
			return redirect('user/rctclients')->with('message','Tax Number Already Exists');
		}
		else{
			DB::table('rctclients')->insert(['firstname' => $name, 'lastname' => $lname, 'taxnumber' => $taxnumber, 'email' => $email,'secondary_email' => $secondary]);
			return redirect('user/rctclients')->with('message','Add Success');
		}
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
			return redirect('user/rctclients')->with('message','Email Already Exists');
		}
		
		elseif(count($check_tax))
		{
			return redirect('user/rctclients')->with('message','Tax Number Already Exists');
		}
		else{
			DB::table('rctclients')->where('client_id', $id)->update(['firstname' => $name, 'lastname' => $lname, 'taxnumber' => $taxnumber,  'email' => $email,'secondary_email' => $secondary]);
			return redirect('user/rctclients')->with('message','Update Success');
		}
	}

	public function clienthidden($id=""){		
		$id = base64_decode($id);
		$deactive =  1;
		DB::table('rctclients')->where('client_id', $id)->update(['status' => $deactive ]);
		return redirect('user/rctclients')->with('message','Client was Successfully hidden.');
	}
	public function expandclient($id="")
	{
		$id = base64_decode($id);
		$type = DB::table('rct_type')->get();
		$rctclients = DB::table('rctclients')->where('client_id', $id)->first();
		$tracker = DB::table('rct_tracker')->where('client_id', $id)->offset(0)->limit(10)->orderBy('id','desc')->get();	
		$count_tracker = DB::table('rct_tracker')->where('client_id', $id)->count();	
		return view('user/rct/clients_expand', array('title' => 'rctclients', 'trackerlist' => $tracker, 'typelist' => $type, 'clientdetails' => $rctclients,  'count_tracker' => $count_tracker));
	}

	public function addnewitem(){
		$clientid = Input::get('hidden_client_id_add');
		$itemtype = Input::get('item_type');
		$subcontractor = Input::get('add_subcontractor');
		$rctno = Input::get('add_rct_no');
		$reference = Input::get('add_reference');
		$explode = explode('-',Input::get('add_date'));
		$date = $explode[2].'-'.$explode[0].'-'.$explode[1];
		$gross = Input::get('add_gross');
		$rate = Input::get('add_rate');
		$deduction = Input::get('add_deduction');
		$net = Input::get('add_net');
		$invoice = Input::get('add_invoice');

		DB::table('rct_tracker')->insert(['client_id' => $clientid, 'rct_type' => $itemtype, 'subcontractor' => $subcontractor, 'rctno' => $rctno, 'reference' => $reference, 'date' => $date, 'gross' => $gross, 'rate' => $rate, 'deduction' => $deduction, 'net' => $net,'invoice' => $invoice]);		
		return Redirect::back()->with('message', 'Item Added Successfully');;
	}
	public function clientexpandcheckreference()
	{
		$reference = Input::get('value');
		$item_type = Input::get('item_type');
		$check_reference = DB::table('rct_tracker')->where('reference',$reference)->where('rct_type',$item_type)->first();
		if(count($check_reference))
		{
			echo 1;
		}
		else{
			echo 0;
		}
	}
	public function clientexpandtypeupdate()
	{
		$id = Input::get('id');
		$value = Input::get('value');
		DB::table('rct_tracker')->where('id',$id)->update(['rct_type' => $value]);
		$details = DB::table('rct_type')->where('id',$value)->first();
		echo $details->type_name;
	}

	public function clientexpandsubupdate()
	{		
		$id = Input::get('id');
		$value = Input::get('value');
		DB::table('rct_tracker')->where('id',$id)->update(['subcontractor' => $value]);

		$details = DB::table('rct_tracker')->where('id',$id)->first();
		echo $details->subcontractor;
	}
	public function clientexpanddateupdate()
	{		
		$id = Input::get('id');
		$date = Input::get('date');
		$exp = explode('-',$date);
		$date = $exp[2].'-'.$exp[0].'-'.$exp[1];
		DB::table('rct_tracker')->where('id',$id)->update(['date' => $date]);
		$details = DB::table('rct_tracker')->where('id',$id)->first();
		echo date('m-d-Y', strtotime($details->date));
	}
	public function clientexpandsubrctno()
	{	
		$id = Input::get('id');
		$value = Input::get('value');
		
		DB::table('rct_tracker')->where('id',$id)->update(['rctno' => $value]);

		$details = DB::table('rct_tracker')->where('id',$id)->first();
		echo $details->rctno;
	}

	public function clientexpandreference()
	{	
		$id = Input::get('id');
		$value = Input::get('value');
		$details = DB::table('rct_tracker')->where('id',$id)->first();
		$check_ref = DB::table('rct_tracker')->where('reference',$value)->where('rct_type',$details->rct_type)->where('id','!=',$id)->first();
		if($value == '')
		{
			DB::table('rct_tracker')->where('id',$id)->update(['reference' => $value]);
			$details = DB::table('rct_tracker')->where('id',$id)->first();
			echo $details->reference;
		}
		else{
			if(count($check_ref))
			{
				DB::table('rct_tracker')->where('id',$id)->update(['reference' => '']);
				echo 'exists';
			}
			else{
				DB::table('rct_tracker')->where('id',$id)->update(['reference' => $value]);
				$details = DB::table('rct_tracker')->where('id',$id)->first();
				echo $details->reference;
			}
		}
	}
	public function clientexpandgross()
	{	
		$id = Input::get('id');
		$value = Input::get('value');
		
		DB::table('rct_tracker')->where('id',$id)->update(['gross' => $value]);

		$details = DB::table('rct_tracker')->where('id',$id)->first();

		$gross = $details->gross;
		$deduction = $details->deduction;

		if(!empty($details->deduction)){

			if($gross == "" || $gross == "0") { $rate = '0'; $net = 0 - $deduction; } else { $rate = $deduction*100/$gross; $rate = number_format((float)$rate, 2, '.', ''); $net = $gross - $deduction; }
			
			DB::table('rct_tracker')->where('id',$id)->update(['rate' => $rate]);
			$net = number_format((float)$net, 2, '.', '');
			DB::table('rct_tracker')->where('id',$id)->update(['net' => $net]);

			echo json_encode(array('gross' => $gross, 'rate' => $rate, 'net' => $net));
		}
		else{	
			if($gross == "" || $gross == "0") { $rate = '0'; $net = 0 - $deduction; } else { $rate = ''; $rate = number_format((float)$rate, 2, '.', ''); $net = $gross - $deduction; }

			DB::table('rct_tracker')->where('id',$id)->update(['rate' => $rate]);
			$net = number_format((float)$net, 2, '.', '');
			DB::table('rct_tracker')->where('id',$id)->update(['net' => $net]);

			echo json_encode(array('gross' => $gross, 'rate' => $rate, 'net' => $net));
		}
	}
	public function clientexpandaddgross()
	{	
		$value = Input::get('value');
		$deduction = input::get('deduction');

		$gross = $value;
		$deduction = $deduction;

		if(!empty($deduction)){

			if($gross == "" || $gross == "0") { $rate = '0'; $net = 0 - $deduction; } else { $rate = $deduction*100/$gross; $rate = number_format((float)$rate, 2, '.', ''); $net = $gross - $deduction; }
			
			$net = number_format((float)$net, 2, '.', '');

			echo json_encode(array('gross' => $gross, 'rate' => $rate, 'net' => $net));
		}
		else{	
			if($gross == "" || $gross == "0") { $rate = '0'; $net = 0 - $deduction; } else { $rate = ''; $rate = number_format((float)$rate, 2, '.', ''); $net = $gross - $deduction; }
			$net = number_format((float)$net, 2, '.', '');
			echo json_encode(array('gross' => $gross, 'rate' => $rate, 'net' => $net));
		}
	}
	
	public function clientexpandadddeduction()
	{	
		$value = Input::get('value');
		$gross = Input::get('gross');
		$gross = $gross;
		$deduction = $value;
		if(!empty($gross)){
			$rate = $deduction*100/$gross;
			$rate = number_format((float)$rate, 2, '.', '');

			$net = $gross - $deduction;
			$net = number_format((float)$net, 2, '.', '');

			echo json_encode(array('deduction' => $deduction, 'rate' => $rate, 'net' => $net));
		}
		else{	

			$rate = '';
			$rate = number_format((float)$rate, 2, '.', '');
			$net = $gross - $deduction;
			$net = number_format((float)$net, 2, '.', '');
			echo json_encode(array('deduction' => $deduction, 'rate' => $rate, 'net' => $net));
		}
	}
	public function clientexpanddeduction()
	{	
		$id = Input::get('id');
		$value = Input::get('value');
		
		DB::table('rct_tracker')->where('id',$id)->update(['deduction' => $value]);
		$details = DB::table('rct_tracker')->where('id',$id)->first();
		$gross = $details->gross;
		$deduction = $details->deduction;

		if(!empty($details->gross)){
			$rate = $deduction*100/$gross;
			$rate = number_format((float)$rate, 2, '.', '');
			DB::table('rct_tracker')->where('id',$id)->update(['rate' => $rate]);

			$net = $gross - $deduction;
			$net = number_format((float)$net, 2, '.', '');
			DB::table('rct_tracker')->where('id',$id)->update(['net' => $net]);

			echo json_encode(array('deduction' => $deduction, 'rate' => $rate, 'net' => $net));
		}
		else{	

			$rate = '';
			$rate = number_format((float)$rate, 2, '.', '');
			DB::table('rct_tracker')->where('id',$id)->update(['rate' => $rate]);
			$net = $gross - $deduction;
			$net = number_format((float)$net, 2, '.', '');
			DB::table('rct_tracker')->where('id',$id)->update(['net' => $net]);

			echo json_encode(array('deduction' => $deduction, 'rate' => $rate, 'net' => $net));
		}

		

	}
	public function clientexpandinvoice()
	{	
		$id = Input::get('id');
		$value = Input::get('value');
		
		DB::table('rct_tracker')->where('id',$id)->update(['invoice' => $value]);

		$details = DB::table('rct_tracker')->where('id',$id)->first();
		echo $details->invoice;
	}

	public function clientexpaddeleteitem($id=""){
		$id = base64_decode($id);
		$item_details = DB::table('rct_tracker')->where('id',$id)->first();
		$admin_details = DB::table('admin')->where('id',1)->first();
		$client_details = DB::table('rctclients')->where('client_id',$item_details->client_id)->first();
		$salutation= DB::table('email_salution')->where('id',2)->first();
		$from = $admin_details->email;
		$toemails = $admin_details->delete_email;
		$subject = 'DELETED RCT Item – '.$client_details->firstname.'';
		
		$to = trim($toemails);
		$data['logo'] = URL::to('assets/common/img/letter_logo.jpg');
		$data['client_details'] = $client_details;
		$data['item_details'] = $item_details;
		$data['salutation'] = $salutation->description;
		$contentmessage = view('emails/email_delete_item', $data);

		$email = new PHPMailer();
		
		$email->SetFrom($from); //Name is optional
		$email->Subject   = $subject;
		$email->Body      = $contentmessage;
		$email->IsHTML(true);
		$email->AddAddress( $to );
		$email->Send();	
		
		
		DB::table('rct_tracker')->where('id', $id)->delete();
		return Redirect::back()->with('message', 'Item Delete Successfully');
	}

	public function clientitemview($id=""){
		$id = base64_decode($id);
		$details = DB::table('rct_tracker')->where('id', $id)->first();

		$client_details = DB::table('rctclients')->where('client_id', $details->client_id)->first();
		$letterpad = DB::table('letterpad')->where('id', $details->rct_type)->first();

		$image = URL::to('uploads/letterpad/'.$letterpad->image);

		echo json_encode(array('subcontractor' => $details->subcontractor, 'rctno' => $details->rctno, 'reference' => $details->reference, 'date' => date('m-d-Y', strtotime($details->date)), 'gross' => $details->gross, 'rate' => $details->rate, 'deduction' => $details->deduction, 'net' => $details->net, 'invoice' => $details->invoice, 'client_name' =>$client_details->firstname, 'client_taxnumber' => $client_details->taxnumber, 'image' => $image));
	}

	public function exportallpdf($id=""){
		$id = base64_decode($id);
		
		$details = DB::table('rct_tracker')->where('client_id', $id)->get();
		$client_details = DB::table('rctclients')->where('client_id', $id)->first();

        $output = '';
        $output.='<table><tr>';
        $output.='<td>'.'<img src="'.URL::to('assets/common/img/letter_logo.jpg').'" width="250px" />'.'</td>';
        $output.='<td width="430px">. .</td>';
        $output.='<td>'.'<img src="'.URL::to('assets/common/img/letter_right.jpg').'" width="350px" />'.'</td>';
        $output.='</tr></table>';
        
		$output.='
				<table style="font-size:13px; margin-top:50px;font-family:Arial, Helvetica, sans-serif;" cellpadding="0px" cellspacing="0px" border="0px">
					<thead>
					<tr style="background:#38354a; color:#fff;">
						<th width="30" align="center" height="30px">#</th>						
						<th width="100" align="center">Type</th>
                        <th width="100">SubContractor</th>
                        <th width="80">Sub Rct No</th>                                
                        <th width="80">Reference</th>
                        <th width="50">Date</th>
                        <th width="50">Gross</th>
                        <th width="50">Rate</th>
                        <th width="50">Deduction</th>
                        <th width="50">Net</th>
                        <th width="70">Email</th>
                        <th width="50">Invoice</th>
                        </tr>
					</thead>
					<tbody>';						
						if(count($details)){
							$i=1;
							foreach ($details as $single) {
								$rct_type_details = DB::table('rct_type')->where('id', $single->rct_type)->first();
								if(!empty($rct_type_details->type_name)){
									$type_name = $rct_type_details->type_name;
								}
								else{
									$type_name = '';
								}
								
								

								$output.='<tr>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; ">'.$i.'</td>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; ">'.$type_name.'</td>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; ">'.$single->subcontractor.'</td>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; ">'.$single->rctno.'</td>';								
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; ">'.$single->reference.'</td>';
								if($single->date == "0000-00-00") { $date_format = 'MM-DD-YYYY'; } else { $date_format = date('m-d-Y', strtotime($single->date)); }
								if($single->email != "0000-00-00 00:00:00") { $email_date = date('F d Y @ H:i',strtotime($single->email)); } else { $email_date = 'MM-DD-YYYY @ HH:MM'; }
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; " align="center">'.$date_format.'</td>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; " align="center">'.$single->gross.'</td>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; " align="center">'.$single->rate.'</td>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; " align="center">'.$single->deduction.'</td>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; " align="center">'.$single->net.'</td>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; ">'.$email_date.'</td>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; ">'.$single->invoice.'</td>';
								$output.='</tr>';
								$i++;
							}
						}	

						
			$output.='</tbody>
				</table>';					

		$pdf = PDF::loadHTML($output);
		$pdf->setPaper('A4','landscape');
		$pdf->save('export/pdf/'.$client_details->firstname.'_'.$client_details->taxnumber.'.pdf');
		echo $client_details->firstname.'_'.$client_details->taxnumber.'.pdf';

	}
	public function exportpdfrctc($id=""){
		$id = base64_decode($id);
		
		$details = DB::table('rct_tracker')->where('client_id', $id)->where('rct_type', 1)->get();
		$client_details = DB::table('rctclients')->where('client_id', $id)->first();

        $output = '';
        $output.='<table><tr>';
        $output.='<td>'.'<img src="'.URL::to('assets/common/img/letter_logo.jpg').'" width="250px" />'.'</td>';
        $output.='<td width="430px">. .</td>';
        $output.='<td>'.'<img src="'.URL::to('assets/common/img/letter_right.jpg').'" width="350px" />'.'</td>';
        $output.='</tr></table>';
        
		$output.='
				<table style="font-size:13px; margin-top:50px;font-family:Arial, Helvetica, sans-serif;" cellpadding="0px" cellspacing="0px" border="0px">
					<thead>
					<tr style="background:#38354a; color:#fff;">
						<th width="30" align="center" height="30px">#</th>						
						<th width="100" align="center">Type</th>
                        <th width="100">SubContractor</th>
                        <th width="80">Sub Rct No</th>                                
                        <th width="80">Reference</th>
                        <th width="50">Date</th>
                        <th width="50">Gross</th>
                        <th width="50">Rate</th>
                        <th width="50">Deduction</th>
                        <th width="50">Net</th>
                        <th width="70">Email</th>
                        <th width="50">Invoice</th>
                        </tr>
					</thead>
					<tbody>';						
						if(count($details)){
							$i=1;
							foreach ($details as $single) {
								$rct_type_details = DB::table('rct_type')->where('id', $single->rct_type)->first();
								if(!empty($rct_type_details->type_name)){
									$type_name = $rct_type_details->type_name;
								}
								else{
									$type_name = '';
								}
								
								

								$output.='<tr>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; ">'.$i.'</td>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; ">'.$type_name.'</td>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; ">'.$single->subcontractor.'</td>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; ">'.$single->rctno.'</td>';								
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; ">'.$single->reference.'</td>';
								if($single->date == "0000-00-00") { $date_format = 'MM-DD-YYYY'; } else { $date_format = date('m-d-Y', strtotime($single->date)); }
								if($single->email != "0000-00-00 00:00:00") { $email_date = date('F d Y @ H:i',strtotime($single->email)); } else { $email_date = 'MM-DD-YYYY @ HH:MM'; }
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; " align="center">'.$date_format.'</td>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; " align="center">'.$single->gross.'</td>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; " align="center">'.$single->rate.'</td>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; " align="center">'.$single->deduction.'</td>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; " align="center">'.$single->net.'</td>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; ">'.$email_date.'</td>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; ">'.$single->invoice.'</td>';
								$output.='</tr>';
								$i++;
							}
						}	

						
			$output.='</tbody>
				</table>';					

		$pdf = PDF::loadHTML($output);
		$pdf->setPaper('A4','landscape');
		$pdf->save('export/pdf/'.$client_details->firstname.'_'.$client_details->taxnumber.'_rct_contract'.'.pdf');
		echo $client_details->firstname.'_'.$client_details->taxnumber.'_rct_contract'.'.pdf';

	}
	public function exportpdfpctc($id=""){
		$id = base64_decode($id);
		
		$details = DB::table('rct_tracker')->where('client_id', $id)->where('rct_type', 2)->get();
		$client_details = DB::table('rctclients')->where('client_id', $id)->first();

        $output = '';
        $output.='<table><tr>';
        $output.='<td>'.'<img src="'.URL::to('assets/common/img/letter_logo.jpg').'" width="250px" />'.'</td>';
        $output.='<td width="430px">. .</td>';
        $output.='<td>'.'<img src="'.URL::to('assets/common/img/letter_right.jpg').'" width="350px" />'.'</td>';
        $output.='</tr></table>';
        
		$output.='
				<table style="font-size:13px; margin-top:50px;font-family:Arial, Helvetica, sans-serif;" cellpadding="0px" cellspacing="0px" border="0px">
					<thead>
					<tr style="background:#38354a; color:#fff;">
						<th width="30" align="center" height="30px">#</th>						
						<th width="100" align="center">Type</th>
                        <th width="100">SubContractor</th>
                        <th width="80">Sub Rct No</th>                                
                        <th width="80">Reference</th>
                        <th width="50">Date</th>
                        <th width="50">Gross</th>
                        <th width="50">Rate</th>
                        <th width="50">Deduction</th>
                        <th width="50">Net</th>
                        <th width="70">Email</th>
                        <th width="50">Invoice</th>
                        </tr>
					</thead>
					<tbody>';						
						if(count($details)){
							$i=1;
							foreach ($details as $single) {
								$rct_type_details = DB::table('rct_type')->where('id', $single->rct_type)->first();
								if(!empty($rct_type_details->type_name)){
									$type_name = $rct_type_details->type_name;
								}
								else{
									$type_name = '';
								}
								
								

								$output.='<tr>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; ">'.$i.'</td>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; ">'.$type_name.'</td>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; ">'.$single->subcontractor.'</td>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; ">'.$single->rctno.'</td>';								
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; ">'.$single->reference.'</td>';
								if($single->date == "0000-00-00") { $date_format = 'MM-DD-YYYY'; } else { $date_format = date('m-d-Y', strtotime($single->date)); }
								if($single->email != "0000-00-00 00:00:00") { $email_date = date('F d Y @ H:i',strtotime($single->email)); } else { $email_date = 'MM-DD-YYYY @ HH:MM'; }
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; " align="center">'.$date_format.'</td>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; " align="center">'.$single->gross.'</td>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; " align="center">'.$single->rate.'</td>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; " align="center">'.$single->deduction.'</td>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; " align="center">'.$single->net.'</td>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; ">'.$email_date.'</td>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; ">'.$single->invoice.'</td>';
								$output.='</tr>';
								$i++;
							}
						}	

						
			$output.='</tbody>
				</table>';					

		$pdf = PDF::loadHTML($output);
		$pdf->setPaper('A4','landscape');
		$pdf->save('export/pdf/'.$client_details->firstname.'_'.$client_details->taxnumber.'_rct_payment'.'.pdf');
		echo $client_details->firstname.'_'.$client_details->taxnumber.'_rct_payment'.'.pdf';

	}
	public function exportpdfhome($id=""){
		$id = base64_decode($id);
		
		$details = DB::table('rct_tracker')->where('client_id', $id)->where('rct_type', 3)->get();
		$client_details = DB::table('rctclients')->where('client_id', $id)->first();

        $output = '';
        $output.='<table><tr>';
        $output.='<td>'.'<img src="'.URL::to('assets/common/img/letter_logo.jpg').'" width="250px" />'.'</td>';
        $output.='<td width="430px">. .</td>';
        $output.='<td>'.'<img src="'.URL::to('assets/common/img/letter_right.jpg').'" width="350px" />'.'</td>';
        $output.='</tr></table>';
        
		$output.='
				<table style="font-size:13px; margin-top:50px;font-family:Arial, Helvetica, sans-serif;" cellpadding="0px" cellspacing="0px" border="0px">
					<thead>
					<tr style="background:#38354a; color:#fff;">
						<th width="30" align="center" height="30px">#</th>						
						<th width="100" align="center">Type</th>
                        <th width="100">SubContractor</th>
                        <th width="80">Sub Rct No</th>                                
                        <th width="80">Reference</th>
                        <th width="50">Date</th>
                        <th width="50">Gross</th>
                        <th width="50">Rate</th>
                        <th width="50">Deduction</th>
                        <th width="50">Net</th>
                        <th width="70">Email</th>
                        <th width="50">Invoice</th>
                        </tr>
					</thead>
					<tbody>';						
						if(count($details)){
							$i=1;
							foreach ($details as $single) {
								$rct_type_details = DB::table('rct_type')->where('id', $single->rct_type)->first();
								if(!empty($rct_type_details->type_name)){
									$type_name = $rct_type_details->type_name;
								}
								else{
									$type_name = '';
								}
								
								

								$output.='<tr>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; ">'.$i.'</td>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; ">'.$type_name.'</td>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; ">'.$single->subcontractor.'</td>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; ">'.$single->rctno.'</td>';								
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; ">'.$single->reference.'</td>';
								if($single->date == "0000-00-00") { $date_format = 'MM-DD-YYYY'; } else { $date_format = date('m-d-Y', strtotime($single->date)); }
								if($single->email != "0000-00-00 00:00:00") { $email_date = date('F d Y @ H:i',strtotime($single->email)); } else { $email_date = 'MM-DD-YYYY @ HH:MM'; }
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; " align="center">'.$date_format.'</td>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; " align="center">'.$single->gross.'</td>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; " align="center">'.$single->rate.'</td>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; " align="center">'.$single->deduction.'</td>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; " align="center">'.$single->net.'</td>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; ">'.$email_date.'</td>';
								$output.='<td style="border-bottom:1px solid #38354a; padding:7px; ">'.$single->invoice.'</td>';
								$output.='</tr>';
								$i++;
							}
						}	

						
			$output.='</tbody>
				</table>';					

		$pdf = PDF::loadHTML($output);
		$pdf->setPaper('A4','landscape');
		$pdf->save('export/pdf/'.$client_details->firstname.'_'.$client_details->taxnumber.'_home_renovation_incentive'.'.pdf');
		echo $client_details->firstname.'_'.$client_details->taxnumber.'_home_renovation_incentive'.'.pdf';

	}
	public function downloadpdf()
	{
		$filepath = $_GET['filename'];
		//header("Content-Type: application/pdf");
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
		header('Content-Transfer-Encoding: binary');
		header('Cache-Control: must-revalidate');
		header('Content-Length: '.filesize($filepath));
		//ob_clean();
		//flush();
		try{
			$page = file_get_contents($filepath);
			echo $page;
			header('Set-Cookie: fileDownload=true; path=/');
		}catch(Exception $e){
			header('Set-Cookie: fileDownload=false; path=/');	
		}
		exit;
	}
	public function rctclientsearch()
	{
		$value = Input::get('term');
		$details = DB::table('rctclients')->where('firstname', 'LIKE','%'.$value.'%')->where('status', 0)->groupBy('firstname')->get();

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
                if($user->status == 0){
                	$url = "return popitup('".URL::to('user/expand_rctclient/'.base64_encode($user->client_id))."')";
        $output.='<tr>
            <td>'.$i.'</td>
            <td align="center">'.$user->firstname.'</td>
            <td align="center">'.$user->taxnumber.'</td>
            <td align="center">'.$user->email.'</td>
            <td align="center">'.$user->secondary_email.'</td>
            <td align="center">';
              $count = DB::table('rct_tracker')->where('client_id', $user->client_id)->count();
              $output.=$count.'
            </td>
            <td align="center">';
              $countivoice = DB::table('rct_tracker')->where('client_id', $user->client_id)->where('invoice','!=', '')->count();
              $output.=$countivoice.'
            </td>
            <td align="center">20</td>
            <td align="center">
                <a href="'.URL::to('user/expand_rctclient/'.base64_encode($user->client_id)).'" title="View RCT Items"><i class="fa fa fa-plus" aria-hidden="true"></i></a>&nbsp;&nbsp;
                <a href="#" id="'.base64_encode($user->client_id).'" class="editclass" title="Edit Client"><i class="fa fa-pencil-square editclass" id="'.base64_encode($user->client_id).'" aria-hidden="true"></i></a>&nbsp; &nbsp;
                <a href="'.URL::to('user/client_hidden/'.base64_encode($user->client_id)).'" title="Hide Client"><i class="fa fa-eye-slash hidden_user" aria-hidden="true"></i></a>                                    
            </td>
        </tr>';
              $i++;
                }
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
		$details = DB::table('rctclients')->where('taxnumber', 'LIKE','%'.$value.'%')->where('status', 0)->get();

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
                if($user->status == 0){
                	$url = "return popitup('".URL::to('user/expand_rctclient/'.base64_encode($user->client_id))."')";
        $output.='<tr>
            <td>'.$i.'</td>
            <td align="center">'.$user->firstname.'</td>
            <td align="center">'.$user->taxnumber.'</td>
            <td align="center">'.$user->email.'</td>
            <td align="center">'.$user->secondary_email.'</td>
            <td align="center">';
              $count = DB::table('rct_tracker')->where('client_id', $user->client_id)->count();
              $output.=$count.'
            </td>
            <td align="center">';
              $countivoice = DB::table('rct_tracker')->where('client_id', $user->client_id)->where('invoice','!=', '')->count();
              $output.=$countivoice.'
            </td>
            <td align="center">20</td>
            <td align="center">
                <a href="'.URL::to('user/expand_rctclient/'.base64_encode($user->client_id)).'" title="View RCT Items"><i class="fa fa fa-plus" aria-hidden="true"></i></a>&nbsp;&nbsp;
                <a href="#" id="'.base64_encode($user->client_id).'" class="editclass" title="Edit Client"><i class="fa fa-pencil-square editclass" id="'.base64_encode($user->client_id).'" aria-hidden="true"></i></a>&nbsp; &nbsp;
                <a href="'.URL::to('user/client_hidden/'.base64_encode($user->client_id)).'" title="Hide Client"><i class="fa fa-eye-slash hidden_user" aria-hidden="true"></i></a>                                    
            </td>
        </tr>';
              $i++;
                }
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
		$details = DB::table('rctclients')->where('email', 'LIKE','%'.$value.'%')->where('status', 0)->get();

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
                if($user->status == 0){
                	$url = "return popitup('".URL::to('user/expand_rctclient/'.base64_encode($user->client_id))."')";
        $output.='<tr>
            <td>'.$i.'</td>
            <td align="center">'.$user->firstname.'</td>
            <td align="center">'.$user->taxnumber.'</td>
            <td align="center">'.$user->email.'</td>
            <td align="center">'.$user->secondary_email.'</td>
            <td align="center">';
              $count = DB::table('rct_tracker')->where('client_id', $user->client_id)->count();
              $output.=$count.'
            </td>
            <td align="center">';
              $countivoice = DB::table('rct_tracker')->where('client_id', $user->client_id)->where('invoice','!=', '')->count();
              $output.=$countivoice.'
            </td>
            <td align="center">20</td>
            <td align="center">
                <a href="'.URL::to('user/expand_rctclient/'.base64_encode($user->client_id)).'" title="View RCT Items"><i class="fa fa fa-plus" aria-hidden="true"></i></a>&nbsp;&nbsp;
                <a href="#" id="'.base64_encode($user->client_id).'" class="editclass" title="Edit Client"><i class="fa fa-pencil-square editclass" id="'.base64_encode($user->client_id).'" aria-hidden="true"></i></a>&nbsp; &nbsp;
                <a href="'.URL::to('user/client_hidden/'.base64_encode($user->client_id)).'" title="Hide Client"><i class="fa fa-eye-slash hidden_user" aria-hidden="true"></i></a>                                    
            </td>
        </tr>';
              $i++;
                }
              }              
            }
            if($i == 1)
            {
              $output.='<tr><td colspan="9" align="center">Empty</td></tr>';
            }
            echo $output;              
	}
	public function contractorsearch()
	{
		$value = Input::get('term');
		$client_id = Input::get('clientid');
		
		$details = DB::table('rct_tracker')->where('subcontractor', 'LIKE','%'.$value.'%')->where('client_id',$client_id)->groupBy('subcontractor')->get();

		$data=array();
		foreach ($details as $single) {
                $data[]=array('value'=>$single->subcontractor,'id'=>$single->id);
        }
         if(count($data))
             return $data;
        else
            return ['value'=>'No Result Found','id'=>''];
	}
	public function subrctsearch()
	{
		$value = Input::get('term');
		$client_id = Input::get('clientid');
		
		$details = DB::table('rct_tracker')->where('rctno', 'LIKE','%'.$value.'%')->where('client_id',$client_id)->groupBy('rctno')->get();

		$data=array();
		foreach ($details as $single) {
                $data[]=array('value'=>$single->rctno,'id'=>$single->id);
        }
         if(count($data))
             return $data;
        else
            return ['value'=>'No Result Found','id'=>''];
	}
	public function referencesearch()
	{
		$value = Input::get('term');
		$client_id = Input::get('clientid');
		
		$details = DB::table('rct_tracker')->where('reference', 'LIKE','%'.$value.'%')->where('client_id',$client_id)->groupBy('reference')->get();

		$data=array();
		foreach ($details as $single) {
                $data[]=array('value'=>$single->reference,'id'=>$single->id);
        }
         if(count($data))
             return $data;
        else
            return ['value'=>'No Result Found','id'=>''];
	}
	public function contractorsearchselect(){
		$contractor = Input::get('value');
		$client_id = Input::get('clientid');
		$trackerlist = DB::table('rct_tracker')->where('subcontractor',$contractor)->where('client_id',$client_id)->get();
		$typelist = DB::table('rct_type')->get();
		$output = '';
        $i=1;
        if(count($trackerlist)){
            foreach($trackerlist  as $single){
        $output.='<tr>
            <td style="line-height: 35px;">'.$i.'</td>
            <td>
                <select class="form-control type_class" data-element="'.$single->id.'">
                    <option value="">Select Type</option>';
                    if(count($typelist)){
                        foreach($typelist as $type){
                        $output.='<option value="'.$type->id.'"';
                        $output.=($type->id == $single->rct_type)?'selected':'';
                        $output.='>'.$type->type_name.'</option>';
                        }
                    }
                $output.='</select>';
                $name = DB::table('rct_type')->where('id',$single->rct_type)->first(); if(count($name)) { 
                $output.='<spam class="sort_class" style="display:none">'.$name->type_name.'</spam>';
                } else {
                $output.='<spam class="sort_class" style="display:none"></spam>';
                }
            $output.='</td>
            <td>
                <input type="text" value="'.$single->subcontractor.'" class="form-control sub_class" data-element="'.$single->id.'" name="" placeholder="SubContractor">
                <span class="sort_class" style="display: none;">'.$single->subcontractor.'</span>
            </td>
            <td>
                <input type="text" value="'.$single->rctno.'" data-element="'.$single->id.'" class="form-control subrct_class" name="" placeholder="Sub Rct No">
                <spam class="sort_class" style="display:none">'.$single->rctno.'</spam>
            </td>
            <td>
                <input type="text" value="'.$single->reference.'" data-element="'.$single->id.'" class="form-control reference_class" name="" placeholder="Enter Reference Number">
                <span class="sort_class" style="display: none;">'.$single->reference.'</span>
            </td>
            <td>
                <input type="text" value="'; if($single->date == "0000-00-00") { $output.='MM-DD-YYYY'; } else { $output.= date('m-d-Y', strtotime($single->date)); } $output.='" class="form-control datepicker date_input" data-element="'.$single->id.'" placeholder="Select Date" />
                <span class="sort_class" style="display: none;">'; if($single->date == "0000-00-00") { $output.='MM-DD-YYYY'; } else { $output.=date('m-d-Y', strtotime($single->date)); } $output.='</span>
            </td>
            <td>
                <div class="form-control">€ :
                    <input type="text" style="border: 0px; outline: 0px;" value="'.$single->gross.'" data-element="'.$single->id.'" class="gross_class" name="" placeholder="Enter Gross">
                    <span class="sort_class" style="display: none">'.$single->gross.'</span>
                </div>      
            </td>
            <td>
                <div class="form-control" style="background: #f2f4f8; width: 100%">
                    <span class="rate_class">';
                            if(!empty($single->rate)) { $output.=(substr($single->rate,-2) == "00") ? substr($single->rate,0,-3).'%' : $single->rate.'%'; } 
                            else{ $output.='N/A'; }
                    $output.='</span>
                </div>           
            </td>
            <td>
                <div class="form-control">€ :
                    <input type="text" style="border: 0px; outline: 0px;" value="'.$single->deduction.'"  data-element="'.$single->id.'" class="deduction_class" name="" placeholder="Enter Deduction" >
                    <span class="deduction_class_span" style="display: none;">'.$single->deduction.'</span>
                </div>
            </td>
            <td>
                <div class="form-control" style="background: #f2f4f8; width: 100%">€ :
                    <span class="net_class">';
                            if(!empty($single->net)) { $output.=(substr($single->net,-2) == "00") ? substr($single->net,0,-3) : $single->net; }
                            else{ $output.='N/A'; }
                    $output.='</span>
                 </div>
            </td>
            <td>
                <div class="form-control" style="background: #f2f4f8">'; 
                	if($single->email != "0000-00-00 00:00:00") { $output.=date('F d Y @ H:i',strtotime($single->email)); } else { $output.='MM-DD-YYYY @ HH:MM'; }
                $output.='</div>                                                
                <span style="display: none;">'.$single->email.'</span>
            </td>
            <td>
               <input type="text" class="form-control invoice_class" value="'.$single->invoice.'" data-element="'.$single->id.'" name="" placeholder="Enter Invoice">
               <span class="sort_class" style="display: none;">'.$single->invoice.'</span>
            </td>
            <td style="line-height: 40px;">
                <a href="#" data-toggle="modal" class="itemviewclass" id="'.base64_encode($single->id).'" data-target=".view_item_modal" title="View"><i class="fa fa-pencil-square-o itemviewclass" id="'.base64_encode($single->id).'" aria-hidden="true"></i></a>&nbsp;&nbsp;
                <a href="javascript:" class="itememailclass" id="'.base64_encode($single->id).'" title="Send as Email"><i class="fa fa-envelope itememailclass" id="'.base64_encode($single->id).'"></i></a>&nbsp;&nbsp;
                <a href="'.URL::to('user/rctclient_expad_delete_item/'.base64_encode($single->id)).'" class="delete_item" title="Delete"><i class="fa fa-trash delete_item" aria-hidden="true"></i></a>
            </td>
        </tr>';
            $i++;
            }                                            
        }
        if($i == 1)
        {
          $output.='<tr><td colspan="12" align="center">Empty</td></tr>';
        }   
        echo $output;   
	}
	public function subrctselect(){
		$rctno = Input::get('value');
		$trackerlist = DB::table('rct_tracker')->where('rctno',$rctno)->get();
		$typelist = DB::table('rct_type')->get();
		$output = '';
        $i=1;
        if(count($trackerlist)){
            foreach($trackerlist  as $single){
        $output.='<tr>
            <td style="line-height: 35px;">'.$i.'</td>
            <td>
                <select class="form-control type_class" data-element="'.$single->id.'">
                    <option value="">Select Type</option>';
                    if(count($typelist)){
                        foreach($typelist as $type){
                        $output.='<option value="'.$type->id.'"';
                        $output.=($type->id == $single->rct_type)?'selected':'';
                        $output.='>'.$type->type_name.'</option>';
                        }
                    }
                $output.='</select>';
                $name = DB::table('rct_type')->where('id',$single->rct_type)->first(); if(count($name)) { 
                $output.='<spam class="sort_class" style="display:none">'.$name->type_name.'</spam>';
                } else {
                $output.='<spam class="sort_class" style="display:none"></spam>';
                }
            $output.='</td>
            <td>
                <input type="text" value="'.$single->subcontractor.'" class="form-control sub_class" data-element="'.$single->id.'" name="" placeholder="SubContractor">
                <span class="sort_class" style="display: none;">'.$single->subcontractor.'</span>
            </td>
            <td>
                <input type="text" value="'.$single->rctno.'" data-element="'.$single->id.'" class="form-control subrct_class" name="" placeholder="Sub Rct No">
                <spam class="sort_class" style="display:none">'.$single->rctno.'</spam>
            </td>
            <td>
                <input type="text" value="'.$single->reference.'" data-element="'.$single->id.'" class="form-control reference_class" name="" placeholder="Enter Reference Number">
                <span class="sort_class" style="display: none;">'.$single->reference.'</span>
            </td>
            <td>
                <input type="text" value="'; if($single->date == "0000-00-00") { $output.='MM-DD-YYYY'; } else { $output.= date('m-d-Y', strtotime($single->date)); } $output.='" class="form-control datepicker date_input" data-element="'.$single->id.'" placeholder="Select Date" />
                <span class="sort_class" style="display: none;">'; if($single->date == "0000-00-00") { $output.='MM-DD-YYYY'; } else { $output.=date('m-d-Y', strtotime($single->date)); } $output.='</span>
            </td>
            <td>
                <div class="form-control">€ :
                    <input type="text" style="border: 0px; outline: 0px;" value="'.$single->gross.'" data-element="'.$single->id.'" class="gross_class" name="" placeholder="Enter Gross">
                    <span class="sort_class" style="display: none">'.$single->gross.'</span>
                </div>      
            </td>
            <td>
                <div class="form-control" style="background: #f2f4f8; width: 100%">
                    <span class="rate_class">';
                            if(!empty($single->rate)) { $output.=(substr($single->rate,-2) == "00") ? substr($single->rate,0,-3).'%' : $single->rate.'%'; } 
                            else{ $output.='N/A'; }
                    $output.='</span>
                </div>           
            </td>
            <td>
                <div class="form-control">€ :
                    <input type="text" style="border: 0px; outline: 0px;" value="'.$single->deduction.'"  data-element="'.$single->id.'" class="deduction_class" name="" placeholder="Enter Deduction" >
                    <span class="deduction_class_span" style="display: none;">'.$single->deduction.'</span>
                </div>
            </td>
            <td>
                <div class="form-control" style="background: #f2f4f8; width: 100%">€ :
                    <span class="net_class">';
                            if(!empty($single->net)) { $output.=(substr($single->net,-2) == "00") ? substr($single->net,0,-3) : $single->net; }
                            else{ $output.='N/A'; }
                    $output.='</span>
                 </div>
            </td>
            <td>
                <div class="form-control" style="background: #f2f4f8">'; 
                	if($single->email != "0000-00-00 00:00:00") { $output.=date('F d Y @ H:i',strtotime($single->email)); } else { $output.='MM-DD-YYYY @ HH:MM'; }
                $output.='</div>                                                  
                <span style="display: none;">'.$single->email.'</span>
            </td>
            <td>
               <input type="text" class="form-control invoice_class" value="'.$single->invoice.'" data-element="'.$single->id.'" name="" placeholder="Enter Invoice">
               <span class="sort_class" style="display: none;">'.$single->invoice.'</span>
            </td>
            <td style="line-height: 40px;">
                <a href="#" data-toggle="modal" class="itemviewclass" id="'.base64_encode($single->id).'" data-target=".view_item_modal" title="View"><i class="fa fa-pencil-square-o itemviewclass" id="'.base64_encode($single->id).'" aria-hidden="true"></i></a>&nbsp;&nbsp;
                <a href="javascript:" class="itememailclass" id="'.base64_encode($single->id).'" title="Send as Email"><i class="fa fa-envelope itememailclass" id="'.base64_encode($single->id).'"></i></a>&nbsp;&nbsp;
                <a href="'.URL::to('user/rctclient_expad_delete_item/'.base64_encode($single->id)).'" class="delete_item" title="Delete"><i class="fa fa-trash delete_item" aria-hidden="true"></i></a>
            </td>
        </tr>';
            $i++;
            }                                            
        }
        if($i == 1)
        {
          $output.='<tr><td colspan="12" align="center">Empty</td></tr>';
        }   
        echo $output;   
	}
	public function referenceselect(){
		$reference = Input::get('value');
		$trackerlist = DB::table('rct_tracker')->where('reference',$reference)->get();
		$typelist = DB::table('rct_type')->get();
		$output = '';
        $i=1;
        if(count($trackerlist)){
            foreach($trackerlist  as $single){
        $output.='<tr>
            <td style="line-height: 35px;">'.$i.'</td>
            <td>
                <select class="form-control type_class" data-element="'.$single->id.'">
                    <option value="">Select Type</option>';
                    if(count($typelist)){
                        foreach($typelist as $type){
                        $output.='<option value="'.$type->id.'"';
                        $output.=($type->id == $single->rct_type)?'selected':'';
                        $output.='>'.$type->type_name.'</option>';
                        }
                    }
                $output.='</select>';
                $name = DB::table('rct_type')->where('id',$single->rct_type)->first(); if(count($name)) { 
                $output.='<spam class="sort_class" style="display:none">'.$name->type_name.'</spam>';
                } else {
                $output.='<spam class="sort_class" style="display:none"></spam>';
                }
            $output.='</td>
            <td>
                <input type="text" value="'.$single->subcontractor.'" class="form-control sub_class" data-element="'.$single->id.'" name="" placeholder="SubContractor">
                <span class="sort_class" style="display: none;">'.$single->subcontractor.'</span>
            </td>
            <td>
                <input type="text" value="'.$single->rctno.'" data-element="'.$single->id.'" class="form-control subrct_class" name="" placeholder="Sub Rct No">
                <spam class="sort_class" style="display:none">'.$single->rctno.'</spam>
            </td>
            <td>
                <input type="text" value="'.$single->reference.'" data-element="'.$single->id.'" class="form-control reference_class" name="" placeholder="Enter Reference Number">
                <span class="sort_class" style="display: none;">'.$single->reference.'</span>
            </td>
            <td>
                <input type="text" value="'; if($single->date == "0000-00-00") { $output.='MM-DD-YYYY'; } else { $output.= date('m-d-Y', strtotime($single->date)); } $output.='" class="form-control datepicker date_input" data-element="'.$single->id.'" placeholder="Select Date" />
                <span class="sort_class" style="display: none;">'; if($single->date == "0000-00-00") { $output.='MM-DD-YYYY'; } else { $output.=date('m-d-Y', strtotime($single->date)); } $output.='</span>
            </td>
            <td>
                <div class="form-control">€ :
                    <input type="text" style="border: 0px; outline: 0px;" value="'.$single->gross.'" data-element="'.$single->id.'" class="gross_class" name="" placeholder="Enter Gross">
                    <span class="sort_class" style="display: none">'.$single->gross.'</span>
                </div>      
            </td>
            <td>
                <div class="form-control" style="background: #f2f4f8; width: 100%">
                    <span class="rate_class">';
                            if(!empty($single->rate)) { $output.=(substr($single->rate,-2) == "00") ? substr($single->rate,0,-3).'%' : $single->rate.'%'; } 
                            else{ $output.='N/A'; }
                    $output.='</span>
                </div>           
            </td>
            <td>
                <div class="form-control">€ :
                    <input type="text" style="border: 0px; outline: 0px;" value="'.$single->deduction.'"  data-element="'.$single->id.'" class="deduction_class" name="" placeholder="Enter Deduction" >
                    <span class="deduction_class_span" style="display: none;">'.$single->deduction.'</span>
                </div>
            </td>
            <td>
                <div class="form-control" style="background: #f2f4f8; width: 100%">€ :
                    <span class="net_class">';
                            if(!empty($single->net)) { $output.=(substr($single->net,-2) == "00") ? substr($single->net,0,-3) : $single->net; }
                            else{ $output.='N/A'; }
                    $output.='</span>
                 </div>
            </td>
            <td>
                <div class="form-control" style="background: #f2f4f8">'; 
                	if($single->email != "0000-00-00 00:00:00") { $output.=date('F d Y @ H:i',strtotime($single->email)); } else { $output.='MM-DD-YYYY @ HH:MM'; }
                $output.='</div>                                                
                <span style="display: none;">'.$single->email.'</span>
            </td>
            <td>
               <input type="text" class="form-control invoice_class" value="'.$single->invoice.'" data-element="'.$single->id.'" name="" placeholder="Enter Invoice">
               <span class="sort_class" style="display: none;">'.$single->invoice.'</span>
            </td>
            <td style="line-height: 40px;">
                <a href="#" data-toggle="modal" class="itemviewclass" id="'.base64_encode($single->id).'" data-target=".view_item_modal" title="View"><i class="fa fa-pencil-square-o itemviewclass" id="'.base64_encode($single->id).'" aria-hidden="true"></i></a>&nbsp;&nbsp;
                <a href="javascript:" class="itememailclass" id="'.base64_encode($single->id).'" title="Send as Email"><i class="fa fa-envelope itememailclass" id="'.base64_encode($single->id).'"></i></a>&nbsp;&nbsp;
                <a href="'.URL::to('user/rctclient_expad_delete_item/'.base64_encode($single->id)).'" class="delete_item" title="Delete"><i class="fa fa-trash delete_item" aria-hidden="true"></i></a>
            </td>
        </tr>';
            $i++;
            }                                            
        }
        if($i == 1)
        {
          $output.='<tr><td colspan="12" align="center">Empty</td></tr>';
        }   
        echo $output;   
	}
	public function clientitememail($id="")
	{
		$id = base64_decode($id);
		$details = DB::table('rct_tracker')->where('id', $id)->first();
		$client_details = DB::table('rctclients')->where('client_id', $details->client_id)->first();
		$letterpad = DB::table('letterpad')->where('id', $details->rct_type)->first();
		$image = URL::to('uploads/letterpad/'.$letterpad->image);

		$output = '<style></style><div class="letter_image">
			            <img src="'.$image.'" id="img_id" width="100%">
			        </div>
			      <div class="item_view_all">
			          		<div class="iteal_view_title" style="margin-top:-790px; text-align:center;font-family: "Lato", sans;">TaxRelevant Contracts Tax <br/><spam font-size:10px>Payment Notification Acknowledgement</spam></div>
			          <div class="table_view" >
			              <table width="500px " align="center" style="margin-top:-700px">
			              		
			                  <tr>
			                      <td height="40px" align="left" valign="top" style="font-family: "Lato", sans;">Payment Notification ID:</td>
			                      <td height="40px" align="left" valign="top"><span class="class_reference" style="font-family: "Lato", sans;">'.$details->reference.'</span></td>
			                  </tr>
			                  <tr>
			                      <td height="40px" align="left" valign="top" style="font-family: "Lato", sans;">Sub Tax Ref:</td>
			                      <td height="40px" align="left" valign="top"><span class="class_rctno" style="font-family: "Lato", sans;">'.$details->rctno.'</span></td>
			                  </tr>
			                  <tr>
			                      <td height="40px" align="left" valign="top" style="font-family: "Lato", sans;">Sub Name:</td>
			                      <td height="40px" align="left" valign="top"><span class="class_subcontractor" style="font-family: "Lato", sans;">'.$details->subcontractor.'</span></td>
			                  </tr>
			                  <tr>
			                      <td height="40px" align="left" valign="top" style="font-family: "Lato", sans;">Date:</td>
			                      <td height="40px" align="left" valign="top"><span class="class_date" style="font-family: "Lato", sans;">'.date('m-d-Y',strtotime($details->date)).'</span></td>
			                  </tr>
			                  <tr>
			                      <td height="40px" align="left" valign="top" style="font-family: "Lato", sans;">Gross Payment:</td>
			                      <td height="40px" align="left" valign="top">€ <span class="class_gross" style="font-family: "Lato", sans;">'.$details->gross.'</span></td>
			                  </tr>
			                  <tr>
			                      <td height="40px" align="left" valign="top" style="font-family: "Lato", sans;">Net Payment:</td>
			                      <td height="40px" align="left" valign="top">€ <span class="class_net" style="font-family: "Lato", sans;">'.$details->net.'</span></td>
			                  </tr>
			                  <tr>
			                      <td height="40px" align="left" valign="top" style="font-family: "Lato", sans;">Deduction Amount</td>
			                      <td height="40px" align="left" valign="top">€ <span class="class_deduction" style="font-family: "Lato", sans;">'.$details->deduction.'</span></td>
			                  </tr>
			                  <tr>
			                      <td colspan="2" height="50"></td>
			                  </tr>
			                  <tr>
			                      <td colspan="2" height="50" align="left" valign="top"><b><span class="class_client" style="font-family: "Lato", sans;">'.$client_details->firstname.'</span>: <span class="class_taxnumber" style="font-family: "Lato", sans;">'.$client_details->taxnumber.'</span></b></td>                      
			                  </tr>
			                  <tr>
			                      <td colspan="2" align="left" valign="top" height="100px" style="font-family: "Lato", sans;">You have notified the Revenue Commissioners that you are about to make a relevant payment of €<span class="class_gross">'.$details->gross.'</span> to the below subcontractor: 
			                        <span style="font-weight: bold;;" class="class_subcontractor" style="font-family: "Lato", sans;">'.$details->subcontractor.'</span>: <span class="class_rctno" style="font-family: "Lato", sans;">'.$details->rctno.'</span></td>                      
			                  </tr>
			                  <tr>
			                      <td colspan="2" height="40" align="left" valign="top" style="font-family: "Lato", sans;">SUBMITTED TO REVENUE VIA ROS BY GBS & Co <a href="http://www.gbsco.ie" target="_blank">www.gbsco.ie</a></td>
			                  </tr>
			              </table>
			          </div>
			      </div>';
		$pdf = PDF::loadHTML($output);
		$pdf->setPaper('A4','portrait');
		$pdf->save('export/pdf/'.$client_details->firstname.'_'.$client_details->taxnumber.'-'.$details->subcontractor.'-'.$details->rctno.'.pdf');
		echo $client_details->firstname.'_'.$client_details->taxnumber.'-'.$details->subcontractor.'-'.$details->rctno.'.pdf';
	}
	public function clientemailform()
	{
		$id = base64_decode(Input::get('hidden_item_id'));
		$from = Input::get('from_user');
		$toemails = Input::get('to_user');
		$subject = Input::get('subject_email'); 
		$message = Input::get('message_editor');
		$attachments = Input::get('hidden_attachment_pdf');
		$explode = explode(',',$toemails);
		if(count($explode))
		{
			foreach($explode as $exp)
			{
				$to = trim($exp);
				$data['logo'] = URL::to('assets/common/img/letter_logo.jpg');
				$data['message'] = $message;
				$contentmessage = view('emails/email_share_paper', $data);
				
				$email = new PHPMailer();
				$email->SetFrom($from); //Name is optional
				$email->Subject   = $subject;
				$email->Body      = $contentmessage;
				$email->IsHTML(true);
				$email->AddAddress( $to );
				

				$path = 'export/pdf/'.$attachments;
				$email->AddAttachment( $path , $attachments );
				$email->Send();	
				
			}
			$date = date('Y-m-d H:i:s');
			DB::table('rct_tracker')->where('id',$id)->update(['email' => $date]);
			return Redirect::back()->with('message', 'Email Sent Successfully');
		}
		else{
			return Redirect::back()->with('error', 'Email Field is empty so email is not sent');
		}
	}
	public function emailreportform()
	{
		$from = Input::get('from_user_report');
		$toemails = Input::get('to_user_report');
		$subject = Input::get('subject_email_report'); 
		$message = Input::get('message_editor_report');
		$attachments = Input::get('hidden_attachment_pdf_report');
		$explode = explode(',',$toemails);
		if(count($explode))
		{
			foreach($explode as $exp)
			{
				$to = trim($exp);
				$data['logo'] = URL::to('assets/common/img/letter_logo.jpg');
				$data['message'] = $message;
				$contentmessage = view('emails/email_share_paper', $data);
				$email = new PHPMailer();
				$email->SetFrom($from); //Name is optional
				$email->Subject   = $subject;
				$email->Body      = $contentmessage;
				$email->IsHTML(true);
				$email->AddAddress( $to );


				$path = 'export/pdf/'.$attachments;
				$email->AddAttachment( $path , $attachments );
				$email->Send();	
			}
			return Redirect::back()->with('message', 'Email Sent Successfully');
		}
		else{
			return Redirect::back()->with('error', 'Email Field is empty so email is not sent');
		}
	}
	public function exportallcsv($id=""){
		

		$id = base64_decode($id);
		
		$details = DB::table('rct_tracker')->where('client_id', $id)->get();
		$client_details = DB::table('rctclients')->where('client_id', $id)->first();
		$headers = array(
	        "Content-type" => "text/csv",
	        "Content-Disposition" => "attachment; filename=".$client_details->firstname."_".$client_details->taxnumber."_All.csv",
	        "Pragma" => "no-cache",
	        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
	        "Expires" => "0"
	    );
		$columns = array('#', 'Type', 'SubContractor', 'Sub Rct No', 'Reference', 'Date', 'Gross', 'Rate', 'Deduction', 'Net', 'Email', 'Invoice');
		$callback = function() use ($details, $columns)
    	{
	       	$file = fopen('php://output', 'w');
		    fputcsv($file, $columns);
			$i=1;
			foreach ($details as $single) {
				$rct_type_details = DB::table('rct_type')->where('id', $single->rct_type)->first();
				if(!empty($rct_type_details->type_name)){
					$type_name = $rct_type_details->type_name;
				}
				else{
					$type_name = '';
				}
				if($single->date == "0000-00-00") { $date_format = 'MM-DD-YYYY'; } else { $date_format = date('m-d-Y', strtotime($single->date)); }
				if($single->email != "0000-00-00 00:00:00") { $email_date = date('F d Y @ H:i',strtotime($single->email)); } else { $email_date = 'MM-DD-YYYY @ HH:MM'; }
				 fputcsv($file, array($i, $type_name, $single->subcontractor, $single->rctno, $single->reference, $date_format, $single->gross, $single->rate, $single->deduction, $single->net, $email_date, $single->invoice));
				$i++;
			}	
			fclose($file);	
		};
			
		return Response::stream($callback, 200, $headers);
	}
	public function exportcsvrctc($id=""){
		

		$id = base64_decode($id);
		
		$details = DB::table('rct_tracker')->where('client_id', $id)->where('rct_type', 1)->get();
		$client_details = DB::table('rctclients')->where('client_id', $id)->first();
		$headers = array(
	        "Content-type" => "text/csv",
	        "Content-Disposition" => "attachment; filename=".$client_details->firstname."_".$client_details->taxnumber."_RCT_CONTRACT.csv",
	        "Pragma" => "no-cache",
	        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
	        "Expires" => "0"
	    );
		$columns = array('#', 'Type', 'SubContractor', 'Sub Rct No', 'Reference', 'Date', 'Gross', 'Rate', 'Deduction', 'Net', 'Email', 'Invoice');
		$callback = function() use ($details, $columns)
    	{
	       	$file = fopen('php://output', 'w');
		    fputcsv($file, $columns);
			$i=1;
			foreach ($details as $single) {
				$rct_type_details = DB::table('rct_type')->where('id', $single->rct_type)->first();
				if(!empty($rct_type_details->type_name)){
					$type_name = $rct_type_details->type_name;
				}
				else{
					$type_name = '';
				}
				 if($single->date == "0000-00-00") { $date_format = 'MM-DD-YYYY'; } else { $date_format = date('m-d-Y', strtotime($single->date)); }
				if($single->email != "0000-00-00 00:00:00") { $email_date = date('F d Y @ H:i',strtotime($single->email)); } else { $email_date = 'MM-DD-YYYY @ HH:MM'; }
				 fputcsv($file, array($i, $type_name, $single->subcontractor, $single->rctno, $single->reference, $date_format, $single->gross, $single->rate, $single->deduction, $single->net, $email_date, $single->invoice));
				$i++;
			}	
			fclose($file);	
		};
			
		return Response::stream($callback, 200, $headers);
	}
	public function exportcsvpctc($id=""){
		

		$id = base64_decode($id);
		
		$details = DB::table('rct_tracker')->where('client_id', $id)->where('rct_type', 2)->get();
		$client_details = DB::table('rctclients')->where('client_id', $id)->first();
		$headers = array(
	        "Content-type" => "text/csv",
	        "Content-Disposition" => "attachment; filename=".$client_details->firstname."_".$client_details->taxnumber."_RCT_PAYMENT.csv",
	        "Pragma" => "no-cache",
	        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
	        "Expires" => "0"
	    );
		$columns = array('#', 'Type', 'SubContractor', 'Sub Rct No', 'Reference', 'Date', 'Gross', 'Rate', 'Deduction', 'Net', 'Email', 'Invoice');
		$callback = function() use ($details, $columns)
    	{
	       	$file = fopen('php://output', 'w');
		    fputcsv($file, $columns);
			$i=1;
			foreach ($details as $single) {
				$rct_type_details = DB::table('rct_type')->where('id', $single->rct_type)->first();
				if(!empty($rct_type_details->type_name)){
					$type_name = $rct_type_details->type_name;
				}
				else{
					$type_name = '';
				}
				 if($single->date == "0000-00-00") { $date_format = 'MM-DD-YYYY'; } else { $date_format = date('m-d-Y', strtotime($single->date)); }
				if($single->email != "0000-00-00 00:00:00") { $email_date = date('F d Y @ H:i',strtotime($single->email)); } else { $email_date = 'MM-DD-YYYY @ HH:MM'; }
				 fputcsv($file, array($i, $type_name, $single->subcontractor, $single->rctno, $single->reference, $date_format, $single->gross, $single->rate, $single->deduction, $single->net, $email_date, $single->invoice));
				$i++;
			}	
			fclose($file);	
		};
			
		return Response::stream($callback, 200, $headers);
	}
	public function exportcsvhome($id=""){
		

		$id = base64_decode($id);
		
		$details = DB::table('rct_tracker')->where('client_id', $id)->where('rct_type', 3)->get();
		$client_details = DB::table('rctclients')->where('client_id', $id)->first();
		$headers = array(
	        "Content-type" => "text/csv",
	        "Content-Disposition" => "attachment; filename=".$client_details->firstname."_".$client_details->taxnumber."_HOME_RENOVATION_INCENTIVE.csv",
	        "Pragma" => "no-cache",
	        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
	        "Expires" => "0"
	    );
		$columns = array('#', 'Type', 'SubContractor', 'Sub Rct No', 'Reference', 'Date', 'Gross', 'Rate', 'Deduction', 'Net', 'Email', 'Invoice');
		$callback = function() use ($details, $columns)
    	{
	       	$file = fopen('php://output', 'w');
		    fputcsv($file, $columns);
			$i=1;
			foreach ($details as $single) {
				$rct_type_details = DB::table('rct_type')->where('id', $single->rct_type)->first();
				if(!empty($rct_type_details->type_name)){
					$type_name = $rct_type_details->type_name;
				}
				else{
					$type_name = '';
				}
				 if($single->date == "0000-00-00") { $date_format = 'MM-DD-YYYY'; } else { $date_format = date('m-d-Y', strtotime($single->date)); }
				if($single->email != "0000-00-00 00:00:00") { $email_date = date('F d Y @ H:i',strtotime($single->email)); } else { $email_date = 'MM-DD-YYYY @ HH:MM'; }
				 fputcsv($file, array($i, $type_name, $single->subcontractor, $single->rctno, $single->reference, $date_format, $single->gross, $single->rate, $single->deduction, $single->net, $email_date, $single->invoice));
				$i++;
			}	
			fclose($file);	
		};
			
		return Response::stream($callback, 200, $headers);
	}
	public function import_form()
	{
		$type = Input::get('type_import');
		$client_id = Input::get('hidden_client_id');
		if($_FILES['file_import']['name']!='')
		{
			$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';
			$tmp_name = $_FILES['file_import']['tmp_name'];
			$name=$_FILES['file_import']['name'];
			$errorlist = array();
			if(move_uploaded_file($tmp_name, "$uploads_dir/$name")){

				$filepath = $uploads_dir.'/'.$name;
				$objPHPExcel = PHPExcel_IOFactory::load($filepath);
				foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
					$worksheetTitle     = $worksheet->getTitle();
					$highestRow         = $worksheet->getHighestRow(); // e.g. 10
					$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
					$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
					$nrColumns = ord($highestColumn) - 64;
					if($highestRow > 50)
					{
						$height = 50;
					}
					else{
						$height = $highestRow;
					}

					$contractor_label = $worksheet->getCellByColumnAndRow(8, 1); $contractor_label = trim($contractor_label->getValue());
					$rct_no_label = $worksheet->getCellByColumnAndRow(9, 1); $rct_no_label = trim($rct_no_label->getValue());
					$reference_label = $worksheet->getCellByColumnAndRow(4, 1); $reference_label = trim($reference_label->getValue());
					$dates_label = $worksheet->getCellByColumnAndRow(5, 1); $dates_label = trim($dates_label->getValue());

					if($contractor_label == "Subcontractor Name" && $rct_no_label == "Subcontractor RCT No." && $reference_label == "Payment ID" && $dates_label == "Payment Notification Date")
					{	
						$errorrow = array();
						$mainarray = array();
						for ($row = 2; $row <= $height; ++ $row) {
							$contractor = $worksheet->getCellByColumnAndRow(8, $row); $contractor = trim($contractor->getValue());
							$rct_no = $worksheet->getCellByColumnAndRow(9, $row); $rct_no = trim($rct_no->getValue());
							$reference = $worksheet->getCellByColumnAndRow(4, $row); $reference = trim($reference->getValue());
							$dates = $worksheet->getCellByColumnAndRow(5, $row); $explodedate = explode('-',$dates->getValue()); 

							if(count($explodedate) == 3)
							{
								$date = $explodedate[2].'-'.$explodedate[1].'-'.$explodedate[0];
							}
							else{
								$explodedate = explode('/',$dates->getValue());
								if(count($explodedate) == 3)
								{
									$date = $explodedate[2].'-'.$explodedate[1].'-'.$explodedate[0];
								}
							}
							

							$gross = $worksheet->getCellByColumnAndRow(6, $row);  $gross = trim($gross->getValue());
							$deduction = $worksheet->getCellByColumnAndRow(7, $row);  $deduction = trim($deduction->getValue());

							$net = $gross - $deduction;
							$rate = $deduction*100/$gross; 
							$rate = number_format((float)$rate, 2, '.', '');
							$invoice = $worksheet->getCellByColumnAndRow(1, $row); $invoice = trim($invoice->getValue());

							$check_reference = DB::table('rct_tracker')->where('reference',$reference)->where('rct_type',$type)->first();
							if(count($check_reference))
							{
								array_push($errorlist,'Row-'.$row.' : Reference No is already taken for this Rct type.');
							}
							else{
								$data['client_id'] = $client_id;
								$data['rct_type'] = $type;
								$data['subcontractor'] = $contractor;
								$data['rctno'] = $rct_no;
								$data['reference'] = $reference;
								$data['date'] = $date;
								$data['gross'] = $gross;
								$data['rate'] = $rate;
								$data['deduction'] = $deduction;
								$data['net'] = $net;
								$data['invoice'] = $invoice;

								DB::table('rct_tracker')->insert($data);
							}
						}
					}
					else{
						return redirect('user/expand_rctclient/'.base64_encode($client_id))->with('message', 'Import Failed! Wrong Input File');
					}
					
				}
				$out = '';
				if(count($errorlist))
				{
					foreach($errorlist as $error) {
	                    $out.='<p class="error_class">'.$error.'</p>';
	                }
				}
				if($height >= $highestRow)
				{
					if($out != '')
					{
						return redirect('user/expand_rctclient/'.base64_encode($client_id))->with('success_error', $out);
					}
					else{
						return redirect('user/expand_rctclient/'.base64_encode($client_id))->with('message', 'Items Imported successfully.');
					}
				}
				else{
					return redirect('user/expand_rctclient/'.base64_encode($client_id).'?filename='.$name.'&client_id='.$client_id.'&type='.$type.'&height='.$height.'&round=2&highestrow='.$highestRow.'&import_type=1&out='.$out.'');
				}
			}
		}
	}
	public function import_form_one()
	{
		$name = Input::get('filename');
		$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';
		$filepath = $uploads_dir.'/'.$name;
		$objPHPExcel = PHPExcel_IOFactory::load($filepath);

		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$worksheetTitle     = $worksheet->getTitle();
			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
			$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
			$nrColumns = ord($highestColumn) - 64;
			$client_id = Input::get('client_id');
			$type = Input::get('type');
			$round = Input::get('round');
			$last_height = Input::get('height');

			$offset = $round - 1;
			$offsetcount = $last_height + 1;
			$roundcount = $round * 50;
			$nextround = $round + 1;

			if($highestRow > $roundcount)
			{
				$height = $roundcount;
			}
			else{
				$height = $highestRow;
			}
			$errorrow = array();
			$mainarray = array();
			$errorlist = array();
			for ($row = $offsetcount; $row <= $height; ++ $row) {
				$contractor = $worksheet->getCellByColumnAndRow(8, $row); $contractor = trim($contractor->getValue());
				$rct_no = $worksheet->getCellByColumnAndRow(9, $row); $rct_no = trim($rct_no->getValue());
				$reference = $worksheet->getCellByColumnAndRow(4, $row); $reference = trim($reference->getValue());
				$dates = $worksheet->getCellByColumnAndRow(5, $row); $explodedate = explode('-',$dates->getValue()); 

				if(count($explodedate) == 3)
				{
					$date = $explodedate[2].'-'.$explodedate[1].'-'.$explodedate[0];
				}
				else{
					$explodedate = explode('/',$dates->getValue());
					if(count($explodedate) == 3)
					{
						$date = $explodedate[2].'-'.$explodedate[1].'-'.$explodedate[0];
					}
				}

				$gross = $worksheet->getCellByColumnAndRow(6, $row);  $gross = trim($gross->getValue());
				$deduction = $worksheet->getCellByColumnAndRow(7, $row);  $deduction = trim($deduction->getValue());

				$net = $gross - $deduction;
				$rate = $deduction*100/$gross; 
				$rate = number_format((float)$rate, 2, '.', '');
				$invoice = $worksheet->getCellByColumnAndRow(1, $row); $invoice = trim($invoice->getValue());

				$check_reference = DB::table('rct_tracker')->where('reference',$reference)->where('client_id',$client_id)->first();
				if(count($check_reference))
				{
					array_push($errorlist,'Row-'.$row.' : Reference No is already taken for this Client.');
				}
				else{
					$data['client_id'] = $client_id;
					$data['rct_type'] = $type;
					$data['subcontractor'] = $contractor;
					$data['rctno'] = $rct_no;
					$data['reference'] = $reference;
					$data['date'] = $date;
					$data['gross'] = $gross;
					$data['rate'] = $rate;
					$data['deduction'] = $deduction;
					$data['net'] = $net;
					$data['invoice'] = $invoice;

					DB::table('rct_tracker')->insert($data);
				}
			}
		}
		$out = Input::get('out');
		if(count($errorlist))
		{
			foreach($errorlist as $error) {
                $out.='<p class="error_class">'.$error.'</p>';
            }
		}
		if($height >= $highestRow)
		{
			if($out != '')
			{
				return redirect('user/expand_rctclient/'.base64_encode($client_id))->with('success_error', $out);
			}
			else{
				return redirect('user/expand_rctclient/'.base64_encode($client_id))->with('message', 'Items Imported successfully.');
			}
		}
		else{
			return redirect('user/expand_rctclient/'.base64_encode($client_id).'?filename='.$name.'&client_id='.$client_id.'&height='.$height.'&type='.$type.'&round='.$nextround.'&highestrow='.$highestRow.'&out='.$out.'&import_type=1');
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

	public function paginate_response()
	{
		$client_id = Input::get('client_id');
		$page = Input::get('page');
		$typelist = DB::table('rct_type')->get();
		$rctclients = DB::table('rctclients')->where('client_id', $client_id)->first();
		if($page == 1)
		{
			$trackerlist = DB::table('rct_tracker')->where('client_id', $client_id)->orderBy('id','desc')->offset(0)->limit(10)->get();
			$i = 1;
		}
		else{
			$pp = $page - 1;
			$offset = $pp * 10;
			$trackerlist = DB::table('rct_tracker')->where('client_id', $client_id)->orderBy('id','desc')->offset($offset)->limit(10)->get();
			$i = $offset + 1;
		}
		$count_tracker = DB::table('rct_tracker')->where('client_id', $client_id)->count();	
		$output = '';
		
        if(count($trackerlist)){
            foreach($trackerlist  as $single){
        $output.='<tr>
            <td style="line-height: 35px;">'.$i.'</td>
            <td>
                <select class="form-control type_class" data-element="'.$single->id.'">
                    <option value="">Select Type</option>';
                    if(count($typelist)){
                        foreach($typelist as $type){
                        $output.='<option value="'.$type->id.'"';
                        $output.=($type->id == $single->rct_type)?'selected':'';
                        $output.='>'.$type->type_name.'</option>';
                        }
                    }
                $output.='</select>';
                $name = DB::table('rct_type')->where('id',$single->rct_type)->first(); if(count($name)) { 
                $output.='<spam class="sort_class" style="display:none">'.$name->type_name.'</spam>';
                } else {
                $output.='<spam class="sort_class" style="display:none"></spam>';
                }
            $output.='</td>
            <td>
                <input type="text" value="'.$single->subcontractor.'" class="form-control sub_class" data-element="'.$single->id.'" name="" placeholder="SubContractor">
                <span class="sort_class" style="display: none;">'.$single->subcontractor.'</span>
            </td>
            <td>
                <input type="text" value="'.$single->rctno.'" data-element="'.$single->id.'" class="form-control subrct_class" name="" placeholder="Sub Rct No">
                <spam class="sort_class" style="display:none">'.$single->rctno.'</spam>
            </td>
            <td>
                <input type="text" value="'.$single->reference.'" data-element="'.$single->id.'" class="form-control reference_class" name="" placeholder="Enter Reference Number">
                <span class="sort_class" style="display: none;">'.$single->reference.'</span>
            </td>
            <td>
                <input type="text" value="'; if($single->date == "0000-00-00") { $output.='MM-DD-YYYY'; } else { $output.= date('m-d-Y', strtotime($single->date)); } $output.='" class="form-control datepicker date_input" data-element="'.$single->id.'" placeholder="Select Date" />
                <span class="sort_class" style="display: none;">'; if($single->date == "0000-00-00") { $output.='MM-DD-YYYY'; } else { $output.=date('m-d-Y', strtotime($single->date)); } $output.='</span>
            </td>
            <td>
                <div class="form-control">€ :
                    <input type="text" style="border: 0px; outline: 0px;" value="'.$single->gross.'" data-element="'.$single->id.'" class="gross_class" name="" placeholder="Enter Gross">
                    <span class="sort_class" style="display: none">'.$single->gross.'</span>
                </div>      
            </td>
            <td>
                <div class="form-control" style="background: #f2f4f8; width: 100%">
                    <span class="rate_class">';
                            if(!empty($single->rate)) { $output.=(substr($single->rate,-2) == "00") ? substr($single->rate,0,-3).'%' : $single->rate.'%'; } 
                            else{ $output.='N/A'; }
                    $output.='</span>
                </div>           
            </td>
            <td>
                <div class="form-control">€ :
                    <input type="text" style="border: 0px; outline: 0px;" value="'.$single->deduction.'"  data-element="'.$single->id.'" class="deduction_class" name="" placeholder="Enter Deduction" >
                    <span class="deduction_class_span" style="display: none;">'.$single->deduction.'</span>
                </div>
            </td>
            <td>
                <div class="form-control" style="background: #f2f4f8; width: 100%">€ :
                    <span class="net_class">';
                            if(!empty($single->net)) { $output.=(substr($single->net,-2) == "00") ? substr($single->net,0,-3) : $single->net; }
                            else{ $output.='N/A'; }
                    $output.='</span>
                 </div>
            </td>
            <td>
                <div class="form-control" style="background: #f2f4f8">'; 
                	if($single->email != "0000-00-00 00:00:00") { $output.=date('F d Y @ H:i',strtotime($single->email)); } else { $output.='MM-DD-YYYY @ HH:MM'; }
                $output.='</div>                                                  
                <span style="display: none;">'.$single->email.'</span>
            </td>
            <td>
               <input type="text" class="form-control invoice_class" value="'.$single->invoice.'" data-element="'.$single->id.'" name="" placeholder="Enter Invoice">
               <span class="sort_class" style="display: none;">'.$single->invoice.'</span>
            </td>
            <td style="line-height: 40px;">
                <a href="#" data-toggle="modal" class="itemviewclass" id="'.base64_encode($single->id).'" data-target=".view_item_modal" title="View"><i class="fa fa-pencil-square-o itemviewclass" id="'.base64_encode($single->id).'" aria-hidden="true"></i></a>&nbsp;&nbsp;
                <a href="javascript:" class="itememailclass" id="'.base64_encode($single->id).'" title="Send as Email"><i class="fa fa-envelope itememailclass" id="'.base64_encode($single->id).'"></i></a>&nbsp;&nbsp;
                <a href="'.URL::to('user/rctclient_expad_delete_item/'.base64_encode($single->id)).'" class="delete_item" title="Delete"><i class="fa fa-trash delete_item" aria-hidden="true"></i></a>
            </td>
        </tr>';
            $i++;
            }                                            
        }
        if($i == 1)
        {
          $output.='<tr><td colspan="12" align="center">Empty</td></tr>';
        }   
        echo $output;
	}
}

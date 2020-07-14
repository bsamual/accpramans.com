<?php namespace App\Http\Controllers\user;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\CmClass;
use App\CmClients;
use App\CmFields;
use App\CmPaper;
use App\Week;
use Session;
use URL;
use PDF;
use Response;
use PHPExcel; 
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use Hash;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class CrmController extends Controller {

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
	public function __construct(cmclass $cmclass, cmclients $cmclients, cmfields $cmfields, cmpaper $cmpaper, week $week)
	{
		$this->middleware('userauth');
		$this->cmclass = $cmclass;
		$this->cmclients = $cmclients;
		$this->cmfields = $cmfields;
		$this->cmpaper = $cmpaper;
		$this->week = $week;
		date_default_timezone_set("Europe/Dublin");
		require_once(app_path('Http/helpers.php'));
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function clientrequestsystem()
	{
		$client = DB::table('cm_clients')->select('client_id', 'firstname', 'surname', 'company', 'status', 'active', 'id')->orderBy('id','asc')->get();

		return view('user/crm/crm', array('title' => 'Client Request System', 'clientlist' => $client));
	}

	public function clientrequestmanager($id=""){
		$id = base64_decode($id);

		$client_details = DB::table('cm_clients')->where('client_id', $id)->first();
		$crm_list = DB::table('request_client')->where('client_id', $id)->get();

		return view('user/crm/crm_client', array('title' => 'Client Request Manager', 'client_details' => $client_details, 'crmlist' => $crm_list));
	}

	public function client_requestedit($id=""){

		if(Session::has('file_attach_purchase'))
		{
			Session::forget("file_attach_purchase");
		}
		$dir = "uploads/crm_image/purchase/temp";//"path/to/targetFiles";
	    
	    // Open a known directory, and proceed to read its contents
	    if (is_dir($dir)) {
	        if ($dh = opendir($dir)) {
	            while (($file = readdir($dh)) !== false) {
		            if ($file==".") continue;
		            if ($file=="..")continue;
		            unlink($dir.'/'.$file);
	            }
	            closedir($dh);
	        }
	    }
		if(Session::has('file_attach_sales'))
		{
			Session::forget("file_attach_sales");
		}
		$dir = "uploads/crm_image/sales/temp";//"path/to/targetFiles";
	    
	    // Open a known directory, and proceed to read its contents
	    if (is_dir($dir)) {
	        if ($dh = opendir($dir)) {
	            while (($file = readdir($dh)) !== false) {
		            if ($file==".") continue;
		            if ($file=="..")continue;
		            unlink($dir.'/'.$file);
	            }
	            closedir($dh);
	        }
	    }
		if(Session::has('file_attach_cheque'))
		{
			Session::forget("file_attach_cheque");
		}
		$dir = "uploads/crm_image/cheque/temp";//"path/to/targetFiles";
	    
	    // Open a known directory, and proceed to read its contents
	    if (is_dir($dir)) {
	        if ($dh = opendir($dir)) {
	            while (($file = readdir($dh)) !== false) {
		            if ($file==".") continue;
		            if ($file=="..")continue;
		            unlink($dir.'/'.$file);
	            }
	            closedir($dh);
	        }
	    }

		$id = base64_decode($id);
		$request = DB::table('request_client')->where('request_id', $id)->first();
		$category = DB::table('request_category')->get();
		$user = DB::table('user')->get();

		$bank_statement = DB::table('request_bank_statement')->where('request_id', $request->request_id)->get();

		$other_information = DB::table('request_others')->where('request_id', $request->request_id)->get();

		$cheque_book = DB::table('request_cheque')->where('request_id', $request->request_id)->get();

		$purchase_invoice = DB::table('request_purchase_invoice')->where('request_id', $request->request_id)->get();

		$sales_invoice = DB::table('request_sales_invoice')->where('request_id', $request->request_id)->get();

		

		return view('user/crm/crm_request', array('title' => 'Make / View a Client Request', 'request_details' => $request, 'categorylist' => $category, 'userlist' => $user, 'bankstatementlist' => $bank_statement, 'otherlist' => $other_information, 'chequebooklist' => $cheque_book, 'purchaseinvoicelist' => $purchase_invoice, 'salesinvoicelist' => $sales_invoice));

	}

	public function clientrequestmodal(){
		$id = base64_decode(Input::get('id'));
		$clientid = Input::get('clientid');
		$requestid = Input::get('requestid');


		$bank_details = DB::table('aml_bank')->where('client_id', $clientid)->get();

		$output_bank='';
		if(count($bank_details)){
			foreach ($bank_details as $bank) {
				$output_bank.='<option value="'.$bank->id.'">'.$bank->bank_name.' &#8212; '.$bank->account_number.' ('.$bank->account_name.')</option>';
			}
		}
		
		if($id == 1){
			$modal='
			<form action="'.URL::to('user/request_purchase_invoice_add').'" method="post">
			<div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">PURCHASE INVOICE REQUEST</h4>           
            
        </div>
        <div class="modal-body">
        	<div class="row">
        		<div class="col-lg-12">
	          		<div class="form-group">
	          			<label> - Specific Purchase Invoice: </label>
	          			<textarea class="form-control request_textarea" required placeholder="Enter Specific Purchase Invoice" name="specific_invoice"></textarea>
	          		</div>
	          	</div>
	          	<div class="col-lg-12">
	          		<div class="form-group"> - Attached List of Purchase Invoices: </div>
		            <div id="add_attachments_purchase_div">
		            </div>
		            <div class="form-group start_group">
		              <i class="fa fa-plus fa-plus-purchase" style="margin-top:10px;" aria-hidden="true" title="Add Attachment"></i> 
		              <i class="fa fa-trash trash_image_purchase" data-element="" style="margin-left: 10px;" aria-hidden="true"></i>
		              
		            </div>
	          	</div>
          	</div>
        </div>
        <div class="modal-footer">
        	<input type="hidden" value="'.$requestid.'" name="requestid" />
            <input type="submit" class="common_black_button client_request_button"  value="Add to Request">
        </div>
        </form>
        ';
        echo json_encode(array('modal' => $modal));
		}
		elseif($id == 2){
			$modal='
			<form action="'.URL::to('user/request_add_sales').'" method="post">
			<div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">SALES INVOICE REQUEST</h4>           
            
        </div>
        <div class="modal-body">
        	<div class="row">
        		<div class="col-lg-12">
	          		<div class="form-group">
	          			<label> - Specific Sales Invoice:</label>
	          			<textarea class="form-control request_textarea" name="specific_invoice" required placeholder="Enter Specific Sales Invoice"></textarea>
	          		</div>
	          	</div>
	          	<div class="col-lg-12">
	          		<div class="form-group"> - Attached List of Sales Invoices:</div>
	          		<div id="add_attachments_sales_div">
		            </div>
		            <div class="form-group start_group">
		              <i class="fa fa-plus fa-plus-sales" style="margin-top:10px;" aria-hidden="true" title="Add Attachment"></i> 
		              <i class="fa fa-trash trash_image_sales" data-element="" style="margin-left: 10px;" aria-hidden="true"></i>
		              
		            </div>
	          	</div>
	          	<div class="col-lg-12">
	          		<div class="form-group">
	          			<label> - Sales Invoices to Specific Customer:</label>
	          			<textarea class="form-control request_textarea" name="sales_invoices" required placeholder=" - Sales Invoices to Specific Customer"></textarea>
	          		</div>
	          	</div>	          	
	          	
          	</div>
        </div>
        <div class="modal-footer">
        	<input type="hidden" value="'.$requestid.'" name="requestid" />
            <input type="submit" class="common_black_button client_request_button"  value="Add to Request">
        </div>
        </form>
        ';
        echo json_encode(array('modal' => $modal));
		}
		elseif($id == 3){
			$modal='
			<form id="bank_form" action="'.URL::to('user/request_add_bank_statement').'" method="post">
			<div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">BANK STATEMENT REQUEST</h4>           
            
        </div>
        <div class="modal-body">
        	<div class="row">
	          	<div class="col-lg-6">Bank Account</div>
	          	<div class="col-lg-6">
	          		<div class="form-group">
		          		<select class="form-control" required name="bank_id">
		          			<option value="">Select Bank</option>
		          			'.$output_bank.'
		          		</select>
	          		</div>         		
	          	</div>
	          	<div class="col-lg-6">Statement Numbers</div>
	          	<div class="col-lg-6">
	          		<div class="form-group">
	          		<input type="number" name="statement_number" class="form-control statement_number" placeholder="Enter Statement Numbers" />
	          		</div>
	          			
	          	</div>
	          	<div class="col-lg-6">
	          		<div class="form-group">
	          			<label>From Date</label>
	          			<label class="input-group datepicker-only-init">
	          				<input type="text" class="form-control from_date" placeholder="Select From Date" name="from_date" style="font-weight: 500;"/>
	          				<span class="input-group-addon">
		                        <i class="glyphicon glyphicon-calendar"></i>
		                    </span>
		                </label>
	          		</div>
	          	</div>
	          	<div class="col-lg-6">
	          		<div class="form-group">
	          			<label>To Date</label>
	          			<label class="input-group datepicker-only-init">
	          				<input type="text" class="form-control to_date" placeholder="Select From Date" name="to_date" style="font-weight: 500;" autocomplete="off"/>
	          				<span class="input-group-addon">
		                        <i class="glyphicon glyphicon-calendar"></i>
		                    </span>
		                </label>
	          		</div>
	          	</div>
          	</div>
        </div>
        <div class="modal-footer">
        	<input type="hidden" value="'.$requestid.'" name="requestid" />
            <input type="submit" class="common_black_button client_request_button bank_statements_button"  value="Add to Request">
        </div>
        </form>
        ';
        echo json_encode(array('modal' => $modal));
		}
		elseif($id == 4){
			$modal='
			<form action="'.URL::to('user/request_add_cheque').'" method="post">
			<div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">CHEQUE BOOK REQUEST</h4>           
            
        </div>
        <div class="modal-body">
        	<div class="row">
	          	<div class="col-lg-6">Bank Account</div>
	          	<div class="col-lg-6">
	          		<div class="form-group">
		          		<select class="form-control" required name="bank_id">
		          			<option value="">Select Bank</option>
		          			'.$output_bank.'
		          		</select>
	          		</div>         		
	          	</div>
	          	<div class="col-lg-12">
	          		<div class="form-group"> - Attach List of Cheques: </div>
	          		<div id="add_attachments_cheque_div">
		            </div>
		            <div class="form-group start_group">
		              <i class="fa fa-plus fa-plus-cheque" style="margin-top:10px;" aria-hidden="true" title="Add Attachment"></i> 
		              <i class="fa fa-trash trash_image_cheque" data-element="" style="margin-left: 10px;" aria-hidden="true"></i>
		              
		            </div>
	          	</div>	          	
	          	<div class="col-lg-12">
	          		<div class="form-group">
	          			<label> - Specific Cheque Numbers: </label>
	          			<textarea class="form-control request_textarea" required placeholder="Enter Specific Cheque Numbers" name="specific_number"></textarea>
	          		</div>
	          	</div>
          	</div>
        </div>
        <div class="modal-footer">
        	<input type="hidden" value="'.$requestid.'" name="requestid" />
            <input type="submit" class="common_black_button client_request_button"  value="Add to Request">
        </div>
        </form>
        ';
        echo json_encode(array('modal' => $modal));
		}
		
		elseif($id == 5){
			$modal='
			<form action="'.URL::to('user/request_add_others').'" method="post">
			<div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">OTHER INFORMATION</h4>           
            
        </div>
        <div class="modal-body">
        	<div class="row">
        		<div class="col-lg-12">
	          		<div class="form-group">
	          			<label>Details:</label>
	          			<textarea class="form-control" style="height:150px;" required placeholder="Enter Details:" name="content"></textarea>
	          		</div>
	          	</div>	          		          	
	          	
          	</div>
        </div>
        <div class="modal-footer">
        	<input type="hidden" value="'.$requestid.'" name="requestid" />
            <input type="submit" class="common_black_button client_request_button"  value="Add to Request">
        </div>
        </form>
        ';
        echo json_encode(array('modal' => $modal));
		}
		else{
			$modal='
			<form>
			<div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">SORRY</h4>           
            
        </div>
        <div class="modal-body">
        	<div class="row">
        		<div class="col-lg-12">
	          		<label>No Functionality built yet for this Request Item.</label>
	          	</div>	          	
          	</div>
        </div>
        <div class="modal-footer">            
            
        </div>
        </form>
        ';
        echo json_encode(array('modal' => $modal));
		}
	}
	public function requestaddbankstatement(){		
		$statement_number = Input::get('statement_number');
		$from_date = Input::get("from_date");

		if($statement_number == ''){
			$data['request_id'] = Input::get('requestid');
			$data['bank_id'] = Input::get('bank_id');
			$data['from_date'] = date('Y-m-d', strtotime(Input::get("from_date")));
			$data['to_date'] = date('Y-m-d', strtotime(Input::get("to_date")));			
			
			DB::table('request_bank_statement')->insert($data);
			return redirect::back()->with('message', 'Bank Statements was add successfully');
		}
		elseif($from_date == ''){
			$data['request_id'] = Input::get('requestid');
			$data['bank_id'] = Input::get('bank_id');
			$data['statment_number'] = Input::get('statement_number');

			DB::table('request_bank_statement')->insert($data);
			return redirect::back()->with('message', 'Bank Statements was add successfully');
		}
		else{
			$data['request_id'] = Input::get('requestid');
			$data['bank_id'] = Input::get('bank_id');
			$data['from_date'] = date('Y-m-d', strtotime(Input::get("from_date")));
			$data['to_date'] = date('Y-m-d', strtotime(Input::get("to_date")));	
			$data['statment_number'] = Input::get('statement_number');

			DB::table('request_bank_statement')->insert($data);
			return redirect::back()->with('message', 'Bank Statements was add successfully');
		}

	}
	public function requestdeletestatement($id=""){
		$id = base64_decode($id);
		DB::table('request_bank_statement')->where('statement_id', $id)->delete();

		return redirect::back()->with('message', 'Bank Statements was delete successfully');
	}

	public function requestaddothers(){
		$data['request_id'] = Input::get('requestid');
		$data['other_content'] = Input::get('content');

		DB::table('request_others')->insert($data);
		return redirect::back()->with('message', 'Other Information was add successfully');
	}

	public function requestdeleteother($id=""){
		$id = base64_decode($id);
		DB::table('request_others')->where('other_id', $id)->delete();

		return redirect::back()->with('message', 'Other Information was delete successfully');

	}
	public function requestaddcheque(){
		$data['request_id'] = Input::get('requestid');
		$data['bank_id'] = Input::get('bank_id');
		$data['specific_number'] = Input::get('specific_number');

		$id = DB::table('request_cheque')->insertGetid($data);

		if(Session::has('file_attach_cheque'))
		{
			$files = Session::get('file_attach_cheque');

			$upload_dir = 'uploads/crm_image/cheque';
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
			$upload_dir = $upload_dir.'/'.base64_encode($id);
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}

     		$dir = "uploads/crm_image/cheque/temp";//"path/to/targetFiles";
		    $dirNew = $upload_dir;//path/to/destination/files
		    // Open a known directory, and proceed to read its contents
		    if (is_dir($dir)) {
		        if ($dh = opendir($dir)) {
		            while (($file = readdir($dh)) !== false) {
			            if ($file==".") continue;
			            if ($file=="..")continue;
			            rename($dir.'/'.$file,$dirNew.'/'.$file);
		            }
		            closedir($dh);
		        }
		    }
     		
			foreach($files as $file)
			{
				//rename("uploads/infile_image/temp/".$file['attachment'], $upload_dir.'/'.$file['attachment']);
				$dataval['request_id'] = Input::get('requestid');
				$dataval['cheque_id'] = $id;
     			$dataval['url'] = $upload_dir;
				$dataval['attachment'] = $file['attachment'];
				Db::table('request_cheque_attached')->insert($dataval);
			}
		}
		return redirect::back()->with('message', 'Cheque Books was add successfully');
	}
	public function requestdeletecheque($id=""){
		$id = base64_decode($id);
		DB::table('request_cheque')->where('cheque_id', $id)->delete();

		return redirect::back()->with('message', 'Cheque Books was delete successfully');

	}
	public function requestbankreceived($id=""){
		$id = base64_decode($id);
		$data['status'] = 1;
		DB::table('request_bank_statement')->where('statement_id', $id)->update($data);
		return redirect::back()->with('message', 'Bank Statements was received successfully');

	}
	public function requestchequereceived($id=""){
		$id = base64_decode($id);
		$data['status'] = 1;
		DB::table('request_cheque')->where('cheque_id', $id)->update($data);
		return redirect::back()->with('message', 'Cheque Books was received successfully');
	}
	public function requestotherreceived($id=""){
		$id = base64_decode($id);
		$data['status'] = 1;
		DB::table('request_others')->where('other_id', $id)->update($data);
		return redirect::back()->with('message', 'Other Information was received successfully');
	}
	public function requestpurchaseinvoiceadd(){
		$data['request_id'] = Input::get('requestid');
		$data['specific_invoice'] = Input::get('specific_invoice');

		$id = DB::table('request_purchase_invoice')->insertGetid($data);

		if(Session::has('file_attach_purchase'))
		{
			$files = Session::get('file_attach_purchase');

			$upload_dir = 'uploads/crm_image/purchase';
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
			$upload_dir = $upload_dir.'/'.base64_encode($id);
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}

     		$dir = "uploads/crm_image/purchase/temp";//"path/to/targetFiles";
		    $dirNew = $upload_dir;//path/to/destination/files
		    // Open a known directory, and proceed to read its contents
		    if (is_dir($dir)) {
		        if ($dh = opendir($dir)) {
		            while (($file = readdir($dh)) !== false) {
			            if ($file==".") continue;
			            if ($file=="..")continue;
			            rename($dir.'/'.$file,$dirNew.'/'.$file);
		            }
		            closedir($dh);
		        }
		    }
     		
			foreach($files as $file)
			{
				//rename("uploads/infile_image/temp/".$file['attachment'], $upload_dir.'/'.$file['attachment']);
				$dataval['request_id'] = Input::get('requestid');
				$dataval['purchase_id'] = $id;
     			$dataval['url'] = $upload_dir;
				$dataval['attachment'] = $file['attachment'];
				Db::table('request_purchase_attached')->insert($dataval);
			}
		}
		return redirect::back()->with('message', 'Purchase Invoice was added successfully');
	}

	public function requestpurchasereceived($id=""){
		$id = base64_decode($id);
		$data['status'] = 1;
		DB::table('request_purchase_invoice')->where('invoice_id', $id)->update($data);
		return redirect::back()->with('message', 'Purchase Invoice was received successfully');
	}
	public function requestdeletepurchase($id=""){
		$id = base64_decode($id);
		DB::table('request_purchase_invoice')->where('invoice_id', $id)->delete();

		return redirect::back()->with('message', 'Purchase Invoice was delete successfully');
	}

	public function requestdeletepurchaseattach($id=""){
		$id = base64_decode($id);

		DB::table('request_purchase_attached')->where('attached_id', $id)->delete();
		return redirect::back()->with('message', 'Purchase Invoice attachement was delete successfully');
	}

	public function requestdeletechequeattach($id=""){
		$id = base64_decode($id);

		DB::table('request_cheque_attached')->where('attached_id', $id)->delete();
		return redirect::back()->with('message', 'Cheque attachement was delete successfully');
	}
	public function requestchequereceivedattach($id=""){
		$id = base64_decode($id);
		$data['status'] = 1;
		DB::table('request_cheque_attached')->where('attached_id', $id)->update($data);
		return redirect::back()->with('message', 'Cheque attachement was received successfully');
	}

	public function requestsalesreceivedattach($id=""){
		$id = base64_decode($id);
		$data['status'] = 1;
		DB::table('request_sales_attached')->where('attached_id', $id)->update($data);
		return redirect::back()->with('message', 'Sales Invoice attachement was received successfully');
	}

	public function requestpurchasereceivedattach($id=""){
		$id = base64_decode($id);
		$data['status'] = 1;
		DB::table('request_purchase_attached')->where('attached_id', $id)->update($data);
		return redirect::back()->with('message', 'Purchase Invoice attachement was received successfully');
	}

	public function requestaddsales(){
		$data['request_id'] = Input::get('requestid');
		$data['specific_invoice'] = Input::get('specific_invoice');
		$data['sales_invoices'] = Input::get('sales_invoices');

		$id = DB::table('request_sales_invoice')->insertGetid($data);
		if(Session::has('file_attach_sales'))
		{
			$files = Session::get('file_attach_sales');

			$upload_dir = 'uploads/crm_image/sales';
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
			$upload_dir = $upload_dir.'/'.base64_encode($id);
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}

     		$dir = "uploads/crm_image/sales/temp";//"path/to/targetFiles";
		    $dirNew = $upload_dir;//path/to/destination/files
		    // Open a known directory, and proceed to read its contents
		    if (is_dir($dir)) {
		        if ($dh = opendir($dir)) {
		            while (($file = readdir($dh)) !== false) {
			            if ($file==".") continue;
			            if ($file=="..")continue;
			            rename($dir.'/'.$file,$dirNew.'/'.$file);
		            }
		            closedir($dh);
		        }
		    }
     		
			foreach($files as $file)
			{
				//rename("uploads/infile_image/temp/".$file['attachment'], $upload_dir.'/'.$file['attachment']);
				$dataval['request_id'] = Input::get('requestid');
				$dataval['sales_id'] = $id;
     			$dataval['url'] = $upload_dir;
				$dataval['attachment'] = $file['attachment'];
				Db::table('request_sales_attached')->insert($dataval);
			}
		}
		return redirect::back()->with('message', 'Sales Invoice was add successfully');
	}

	public function requestsalesreceived($id=""){
		$id = base64_decode($id);
		$data['status'] = 1;
		DB::table('request_sales_invoice')->where('invoice_id', $id)->update($data);
		return redirect::back()->with('message', 'Sales Invoice was received successfully');
	}

	public function requestbankstatement($id=""){
		$id = base64_decode($id);
		$data['status'] = 1;
		DB::table('request_bank_statement')->where('statement_id', $id)->update($data);
		return redirect::back()->with('message', 'Bank Statements attachement was received successfully');
	}

	public function requestdeletesales($id=""){
		$id = base64_decode($id);
		DB::table('request_sales_invoice')->where('invoice_id', $id)->delete();

		return redirect::back()->with('message', 'Sales Invoice was delete successfully');
	}

	public function requestdeletesalesattach($id=""){
		$id = base64_decode($id);

		DB::table('request_sales_attached')->where('attached_id', $id)->delete();
		return redirect::back()->with('message', 'Sales Invoice attachement was delete successfully');
	}

	public function clientrequestyearcategoryuser(){
		$requestid = Input::get('requestid');
		$type = Input::get('type');

		
		if($type == 1){
			$data['year'] = Input::get('value');
			DB::table('request_client')->where('request_id', $requestid)->update($data);

			$request = DB::table('request_client')->where('request_id', $requestid)->first();
			$category = DB::table('request_category')->where('category_id',$request->category_id)->first();		
			if($request->category_id != ''){
				$category = $category->category_name;
			}
			else{
				$category = '';
			}

			$result = 'Information Request: '.$request->year.'&#8212; '.$category;
			echo json_encode(array('content' => $result));
		}
		elseif($type == 2){
			$data['category_id'] = Input::get('value');
			DB::table('request_client')->where('request_id', $requestid)->update($data);

			$request = DB::table('request_client')->where('request_id', $requestid)->first();
			$category = DB::table('request_category')->where('category_id',$request->category_id)->first();

			if($request->category_id != ''){
				$category = $category->category_name;
				$request_item = DB::table('request_sub_category')->where('category_id',$request->category_id)->get();

				$outputitem = '';
				if(count($request_item)){
			        foreach ($request_item as $item) {
			          $outputitem.='<a href="javascript:" style="font-weight:normal;" class="item_class" data-element="'.base64_encode($item->sub_category_id).'">'.$item->sub_category_name.'</a><br/>';
			        }
			      }
			      else{
			        $outputitem='Item not found';
			      }
			}
			else{
				$category = '';
				$outputitem='Item not found';
			}		

			$result = 'Information Request: '.$request->year.' &#8212; '.$category;
			echo json_encode(array('content' => $result, 'outputitem' => $outputitem));
		}
		elseif($type == 3){
			$data['request_from'] = Input::get('value');
			DB::table('request_client')->where('request_id', $requestid)->update($data);
		}		
	}

	public function requestreceivedall($id=""){
		$id = base64_decode($id);
		
		$data['status'] = 1;
		DB::table('request_client')->where('request_id', $id)->update($data);
		DB::table('request_purchase_invoice')->where('request_id', $id)->update($data);
		DB::table('request_sales_invoice')->where('request_id', $id)->update($data);
		DB::table('request_bank_statement')->where('request_id', $id)->update($data);
		DB::table('request_cheque')->where('request_id', $id)->update($data);
		DB::table('request_others')->where('request_id', $id)->update($data);
		DB::table('request_cheque_attached')->where('request_id', $id)->update($data);
		DB::table('request_purchase_attached')->where('request_id', $id)->update($data);
		DB::table('request_sales_attached')->where('request_id', $id)->update($data);

		return redirect::back()->with('message', 'All files was received successfully');

	}

	public function requestnewadd($id=""){		
		$id = base64_decode($id);
		$data['client_id'] = $id;
		$data['request_date'] = date('Y-m-d');
		$request = DB::table('request_client')->insertGetid($data);		

		return redirect('user/client_request_edit/'.base64_encode($request))->with('message', 'New request was created successfully');

	}

	public function requestdelete($id=""){
		$id = base64_decode($id);
		DB::table('request_client')->where('request_id', $id)->delete();

		DB::table('request_purchase_invoice')->where('request_id', $id)->delete();
		DB::table('request_sales_invoice')->where('request_id', $id)->delete();
		DB::table('request_bank_statement')->where('request_id', $id)->delete();
		DB::table('request_cheque')->where('request_id', $id)->delete();
		DB::table('request_others')->where('request_id', $id)->delete();
		DB::table('request_cheque_attached')->where('request_id', $id)->delete();
		DB::table('request_purchase_attached')->where('request_id', $id)->delete();
		DB::table('request_sales_attached')->where('request_id', $id)->delete();

		return redirect::back()->with('message', 'Request was delete successfully');
	}

	public function client_requestview(){
		$id = Input::get('requestid');		
		$request_details = DB::table('request_client')->where('request_id', $id)->first();
		$category = DB::table('request_category')->where('category_id', $request_details->category_id)->first();
		$employee = DB::table('user')->where('user_id', $request_details->request_from)->first();

		$purchaseinvoicelist = DB::table('request_purchase_invoice')->where('request_id', $id)->get();
		$salesinvoicelist = DB::table('request_sales_invoice')->where('request_id', $id)->get();
		$bankstatementlist = DB::table('request_bank_statement')->where('request_id', $id)->get();
		$chequebooklist = DB::table('request_cheque')->where('request_id', $id)->get();
		$otherlist = DB::table('request_others')->where('request_id', $id)->get();


		$output_purchase_invoice='';
		if(count($purchaseinvoicelist)){
	      foreach ($purchaseinvoicelist as $invoice) {/*

	        if($request_details->status == 0){
	          $del_rec_pur='<a href="'.URL::to('user/request_delete_purchase').'/'.base64_encode($invoice->invoice_id).'"><i class="fa fa-trash"></i></a>';
	        }
	        else{
	          if($invoice->status == 0){
	            $del_rec_pur = '<a href="'.URL::to('user/request_purchase_received'.'/'.base64_encode($invoice->invoice_id)).'" style="color: #f00"><i class="fa fa-times"></i></a>';
	          }
	          else{
	            $del_rec_pur = '<a href="javascript:" style="color: #33CC66"><i class="fa fa fa-check"></i></a>';
	          }

	          
	        }*/

	        

	        $purchase_attached_list = DB::table('request_purchase_attached')->where('purchase_id', $invoice->invoice_id)->get();

	        $output_purchase_attach='<tr>
	          <td>Purchase Invoices</td>
	          <td>Attachements</td>
	          <td></td>
	        </tr>';

	        if(count($purchase_attached_list)){
	          foreach ($purchase_attached_list as $purchase_attach) {

	            /*if($request_details->status == 0){
	              $del_rec_pur_att='<a href="'.URL::to('user/request_delete_purchase_attach').'/'.base64_encode($purchase_attach->attached_id).'"><i class="fa fa-trash"></i></a>';
	            }
	            else{
	              if($purchase_attach->status == 0){
	                $del_rec_pur_att = '<a href="'.URL::to('user/request_purchase_received_attach'.'/'.base64_encode($purchase_attach->attached_id)).'" style="color: #f00"><i class="fa fa-times"></i></a>'; 
	              }
	              else{
	                $del_rec_pur_att = '<a href="javascript:" style="color: #33CC66"><i class="fa fa-check"></i></a>';
	              }
	            }*/
	           

	            $output_purchase_attach.='<tr>
	            <td></td>
	            <td>'.$purchase_attach->attachment.'</td>
	            <td></td>
	            </tr>
	            ';
	          }
	        }
	        else{
	          $output_purchase_attach='';
	        }



	        $output_purchase_invoice.='
	        <tr>
	          <td>Purchase Invoices</td>
	          <td>'.$invoice->specific_invoice.'</td>
	          <td>Note</td>
	                  
	        </tr>'.$output_purchase_attach.'
	        ';
	      }     
	    }
	    else{
	      $output_purchase_invoice='';
	    }


	    $output_sales_invoice='';
	    if(count($salesinvoicelist)){
	      foreach ($salesinvoicelist as $invoice) {


	        /*if($request_details->status == 0){
	          $del_rec_sal='<a href="'.URL::to('user/request_delete_sales').'/'.base64_encode($invoice->invoice_id).'"><i class="fa fa-trash"></i></a>';
	        }
	        else{
	          if($invoice->status == 0 ){
	            $del_rec_sal = '<a href="'.URL::to('user/request_sales_received'.'/'.base64_encode($invoice->invoice_id)).'" style="color: #f00"><i class="fa fa-times"></i></a>';
	          }
	          else{
	            $del_rec_sal = '<a href="javascript:" style="color: #33CC66"><i class="fa fa-check"></i></a>';
	          }
	        }*/


	        $sales_attached_list = DB::table('request_sales_attached')->where('sales_id', $invoice->invoice_id)->get();

	        $output_sales_attach='<tr>
	          <td>Sales Invoices</td>
	          <td>Attachements</td>          
	          <td></td>
	        </tr>';

	        if(count($sales_attached_list)){
	          foreach ($sales_attached_list as $sales_attach) {

	            /*if($request_details->status == 0){
	              $del_rec_sal_att ='<a href="'.URL::to('user/request_delete_sales_attach').'/'.base64_encode($sales_attach->attached_id).'"><i class="fa fa-trash"></i></a>';
	            }
	            else{
	              if($sales_attach->status == 0){
	                $del_rec_sal_att ='<a href="'.URL::to('user/request_sales_received_attach'.'/'.base64_encode($sales_attach->attached_id)).'" style="color: #f00"><i class="fa fa-times"></i></a>';
	              }
	              else{
	                $del_rec_sal_att = '<a href="javascript:" style="color: #33CC66"><i class="fa fa-check"></i></a>';
	              }

	            }*/

	            $output_sales_attach.='<tr>
	            <td></td>
	            <td>'.$sales_attach->attachment.'</td>
	            <td></td>
	            
	            </tr>
	            ';
	          }
	        }
	        else{
	          $output_sales_attach='';
	        }




	        $output_sales_invoice.='
	        <tr>
	          <td>Sales Invoices</td>
	          <td>'.$invoice->specific_invoice.'</td>
	          <td>Note</td>	                 
	        </tr>'.$output_sales_attach.'
	        ';
	      }     
	    }
	    else{
	      $output_sales_invoice='';
	    }

	    $output_statement='';
	    if(count($bankstatementlist)){
	      foreach ($bankstatementlist as $statement) {

	        /*if($request_details->status == 0){
	          $del_rec_bank ='<a href="'.URL::to('user/request_delete_statement').'/'.base64_encode($statement->statement_id).'"><i class="fa fa-trash"></i></a>';
	        }
	        else{
	          if($statement->status == 0){
	            $del_rec_bank = '<a href="'.URL::to('user/request_bank_statement'.'/'.base64_encode($statement->statement_id)).'" style="color: #f00"><i class="fa fa-times"></i></a>';
	          }
	          else{
	            $del_rec_bank = '<a href="javascript:" style="color: #33CC66"><i class="fa fa-check"></i></a>';
	          }
	        }*/


	        if($statement->statment_number == ''){
	          $result_bank = 'From '.date('d-M-Y', strtotime($statement->from_date)).' to '.date('d-M-Y', strtotime($statement->to_date));
	        }
	        elseif($statement->from_date == '0000-00-00'){
	          $result_bank = 'Statement Numbers '.$statement->statment_number;
	        }
	        else{
	           $result_bank = 'Statement Numbers '.$statement->statment_number.' From '.date('d-M-Y', strtotime($statement->from_date)).' to '.date('d-M-Y', strtotime($statement->to_date));
	        }


	        $output_statement.='
	        <tr>
	          <td>Bank Statements</td>
	          <td>'.$result_bank.'</td>
	          <td>Note</td>
	          
	        </tr>
	        ';
	      }     
	    }
	    else{
	      $output_statement='';
	    }

	    $output_cheque='';
	    if(count($chequebooklist)){
	      foreach ($chequebooklist as $cheque) {


	        /*if($request_details->status == 0){
	          $del_rec_che='<a href="'.URL::to('user/request_delete_cheque').'/'.base64_encode($cheque->cheque_id).'"><i class="fa fa-trash"></i></a>';
	        }
	        else{
	          if($cheque->status == 0){
	            $del_rec_che = '<a href="'.URL::to('user/request_cheque_received'.'/'.base64_encode($cheque->cheque_id)).'" style="color: #f00"><i class="fa fa-times"></i></a>';          
	          }
	          else{
	            $del_rec_che = '<a href="javascript:" style="color: #33CC66"><i class="fa fa-check"></i></a>';          
	          }
	        }*/


	        

	        $cheque_attached_list = DB::table('request_cheque_attached')->where('cheque_id', $cheque->cheque_id)->get();

	        $output_cheque_attach='<tr>
	          <td>Cheque Books</td>
	          <td>Attachements</td>
	          <td></td>
	          <td></td>
	          <td></td>
	        </tr>';

	        if(count($cheque_attached_list)){
	          foreach ($cheque_attached_list as $cheque_attach) {

	            /*if($request_details->status == 0){
	              $del_rec_che_att = '<a href="'.URL::to('user/request_delete_cheque_attach').'/'.base64_encode($cheque_attach->attached_id).'"><i class="fa fa-trash"></i></a>';
	            }
	            else{
	              if($cheque_attach->status == 0){
	                $del_rec_che_att = '<a href="'.URL::to('user/request_cheque_received_attach'.'/'.base64_encode($cheque_attach->attached_id)).'" style="color: #f00"><i class="fa fa-times"></i></a>';              
	              }
	              else{
	                $del_rec_che_att = '<a href="javascript:" style="color: #33CC66"><i class="fa fa-check"></i></a>';
	              }
	            }*/

	            

	            $output_cheque_attach.='<tr>
	            <td></td>
	            <td>'.$cheque_attach->attachment.'</td>
	            <td></td>	            
	            </tr>
	            ';
	          }
	        }
	        else{
	          $output_cheque_attach='';
	        }

	        


	        $output_cheque.='
	        <tr>
	          <td>Cheque Books</td>
	          <td>'.$cheque->specific_number.'</td>
	          <td>Note</td>	          
	        </tr>
	        '.$output_cheque_attach.'
	        ';
	      }     
	    }
	    else{
	      $output_cheque='';
	    }


	    $output_other='';
	    if(count($otherlist)){
	      foreach ($otherlist as $other) {       

	      /*if($request_details->status == 0){
	        $del_rec_oth='<a href="'.URL::to('user/request_delete_other').'/'.base64_encode($other->other_id).'"><i class="fa fa-trash"></i></a>';
	      }
	      else{
	        if($other->status == 0){
	          $del_rec_oth = '<a href="'.URL::to('user/request_other_received'.'/'.base64_encode($other->other_id)).'" style="color: #f00"><i class="fa fa-times"></i></a>';          
	        }
	        else{
	          $del_rec_oth = '<a href="javascript:" style="color: #33CC66"><i class="fa fa-check"></i></a>';          
	        }
	      }*/

	        
	        $output_other.='
	        <tr>
	          <td>Other Information</td>
	          <td>'.$other->other_content.'</td>
	          <td>Note</td>
	          
	        </tr>
	        ';
	      }     
	    }
	    else{
	      $output_other='';
	    }


		$output_information='
		<tr>
			<td>Year</td>
			<td>'.$request_details->year.'</td>
		</tr>
		<tr>
			<td>Category</td>
			<td>'.$category->category_name.'</td>
		</tr>
		<tr>
			<td>Employee</td>
			<td>'.$employee->firstname.' '.$employee->lastname.'</td>
		</tr>
		<tr>
			<td>Subject:</td>
			<td>Information Request: '.$request_details->year.' &#8212; '.$category->category_name.'</td>
		</tr>
		';

		$output='
		<div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">View Request</h4>           
            
        </div>
        <div class="modal-body">
        	<table class="table" style="width:500px">
        		'.$output_information.'
        	</table>
        	<table class="table">
        		<thead>
        			<tr>
        				<th style="text-align:left; width:100px;">Items on this request</th>
        				<th></th>
        				<th></th>
        			</tr>
        			'.$output_purchase_invoice.'
        			'.$output_sales_invoice.'
        			'.$output_statement.'
        			'.$output_cheque.'
        			'.$output_other.'
        		</thead>
        		<tbody>
        		</tbody>
        	</table>
        </div>
        <div class="modal-footer">
        	            
        </div>
		';

		

		echo json_encode(array('content' => $output ));

	}
	public function crm_upload_images_purchase()
	{
		$upload_dir = 'uploads/crm_image';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/purchase';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/temp';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}

		if (!empty($_FILES)) {
			 $tmpFile = $_FILES['file']['tmp_name'];
			 $fname = str_replace("#","",$_FILES['file']['name']);
			 $fname = str_replace("#","",$fname);
			 $fname = str_replace("#","",$fname);
			 $fname = str_replace("#","",$fname);

			 $fname = str_replace("%","",$fname);
			 $fname = str_replace("%","",$fname);
			 $fname = str_replace("%","",$fname);

			 $filename = $upload_dir.'/'.$fname;

			 $arrayval = array('attachment' => $fname,'url' => $upload_dir);

	 		if(Session::has('file_attach_purchase'))
			{
				$getsession = Session::get('file_attach_purchase');
			}
			else{
				$getsession = array();
			}
			
			array_push($getsession,$arrayval);

			$sessn=array('file_attach_purchase' => $getsession);
			Session::put($sessn);

			move_uploaded_file($tmpFile,$filename);

		 	echo json_encode(array('id' => 0,'filename' => $fname,'file_id' => 0,'count'=>count($getsession)));
		}
	}
	public function crm_upload_images_sales()
	{
		$upload_dir = 'uploads/crm_image';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/sales';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/temp';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}

		if (!empty($_FILES)) {
			 $tmpFile = $_FILES['file']['tmp_name'];
			 $fname = str_replace("#","",$_FILES['file']['name']);
			 $fname = str_replace("#","",$fname);
			 $fname = str_replace("#","",$fname);
			 $fname = str_replace("#","",$fname);

			 $fname = str_replace("%","",$fname);
			 $fname = str_replace("%","",$fname);
			 $fname = str_replace("%","",$fname);

			 $filename = $upload_dir.'/'.$fname;

			 $arrayval = array('attachment' => $fname,'url' => $upload_dir);

	 		if(Session::has('file_attach_sales'))
			{
				$getsession = Session::get('file_attach_sales');
			}
			else{
				$getsession = array();
			}
			
			array_push($getsession,$arrayval);

			$sessn=array('file_attach_sales' => $getsession);
			Session::put($sessn);

			move_uploaded_file($tmpFile,$filename);

		 	echo json_encode(array('id' => 0,'filename' => $fname,'file_id' => 0,'count'=>count($getsession)));
		}
	}
	public function crm_upload_images_cheque()
	{
		$upload_dir = 'uploads/crm_image';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/cheque';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/temp';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}

		if (!empty($_FILES)) {
			 $tmpFile = $_FILES['file']['tmp_name'];
			 $fname = str_replace("#","",$_FILES['file']['name']);
			 $fname = str_replace("#","",$fname);
			 $fname = str_replace("#","",$fname);
			 $fname = str_replace("#","",$fname);

			 $fname = str_replace("%","",$fname);
			 $fname = str_replace("%","",$fname);
			 $fname = str_replace("%","",$fname);

			 $filename = $upload_dir.'/'.$fname;

			 $arrayval = array('attachment' => $fname,'url' => $upload_dir);

	 		if(Session::has('file_attach_cheque'))
			{
				$getsession = Session::get('file_attach_cheque');
			}
			else{
				$getsession = array();
			}
			
			array_push($getsession,$arrayval);

			$sessn=array('file_attach_cheque' => $getsession);
			Session::put($sessn);

			move_uploaded_file($tmpFile,$filename);

		 	echo json_encode(array('id' => 0,'filename' => $fname,'file_id' => 0,'count'=>count($getsession)));
		}
	}
	public function clear_session_attachments_purchase()
	{
		if(Session::has('file_attach_purchase'))
		{
			Session::forget("file_attach_purchase");
		}
		$dir = "uploads/crm_image/purchase/temp";//"path/to/targetFiles";
	    
	    // Open a known directory, and proceed to read its contents
	    if (is_dir($dir)) {
	        if ($dh = opendir($dir)) {
	            while (($file = readdir($dh)) !== false) {
		            if ($file==".") continue;
		            if ($file=="..")continue;
		            unlink($dir.'/'.$file);
	            }
	            closedir($dh);
	        }
	    }
	}
	public function clear_session_attachments_sales()
	{
		if(Session::has('file_attach_sales'))
		{
			Session::forget("file_attach_sales");
		}
		$dir = "uploads/crm_image/sales/temp";//"path/to/targetFiles";
	    
	    // Open a known directory, and proceed to read its contents
	    if (is_dir($dir)) {
	        if ($dh = opendir($dir)) {
	            while (($file = readdir($dh)) !== false) {
		            if ($file==".") continue;
		            if ($file=="..")continue;
		            unlink($dir.'/'.$file);
	            }
	            closedir($dh);
	        }
	    }
	}
	public function clear_session_attachments_cheque()
	{
		if(Session::has('file_attach_cheque'))
		{
			Session::forget("file_attach_cheque");
		}
		$dir = "uploads/crm_image/cheque/temp";//"path/to/targetFiles";
	    
	    // Open a known directory, and proceed to read its contents
	    if (is_dir($dir)) {
	        if ($dh = opendir($dir)) {
	            while (($file = readdir($dh)) !== false) {
		            if ($file==".") continue;
		            if ($file=="..")continue;
		            unlink($dir.'/'.$file);
	            }
	            closedir($dh);
	        }
	    }
	}
	public function send_request_for_approval_edit()
	{
		$id = Input::get('requestid');
		$to_user = Input::get('to_user');		
		$request_details = DB::table('request_client')->where('request_id', $id)->first();
		$category = DB::table('request_category')->where('category_id', $request_details->category_id)->first();
		$employee = DB::table('user')->where('user_id', $request_details->request_from)->first();
		if($to_user != "")
		{
			$to_employee = DB::table('user')->where('user_id', $to_user)->first();
			$to_user_name = $to_employee->firstname;
		}
		else{
			$to_user_name = '';
		}

		$purchaseinvoicelist = DB::table('request_purchase_invoice')->where('request_id', $id)->get();
		$salesinvoicelist = DB::table('request_sales_invoice')->where('request_id', $id)->get();
		$bankstatementlist = DB::table('request_bank_statement')->where('request_id', $id)->get();
		$chequebooklist = DB::table('request_cheque')->where('request_id', $id)->get();
		$otherlist = DB::table('request_others')->where('request_id', $id)->get();


		$output_purchase_invoice='';
		if(count($purchaseinvoicelist)){
	      foreach ($purchaseinvoicelist as $invoice) {

	        $purchase_attached_list = DB::table('request_purchase_attached')->where('purchase_id', $invoice->invoice_id)->get();

	        $output_purchase_attach='<tr>
	          <td>Purchase Invoices</td>
	          <td>Attachements</td>
	          <td></td>
	        </tr>';

	        if(count($purchase_attached_list)){
	          foreach ($purchase_attached_list as $purchase_attach) {

	            $output_purchase_attach.='<tr>
	            <td></td>
	            <td>'.$purchase_attach->attachment.'</td>
	            <td></td>
	            </tr>
	            ';
	          }
	        }
	        else{
	          $output_purchase_attach='';
	        }



	        $output_purchase_invoice.='
	        <tr>
	          <td>Purchase Invoices</td>
	          <td>'.$invoice->specific_invoice.'</td>
	          <td>Note</td>
	                  
	        </tr>'.$output_purchase_attach.'
	        ';
	      }     
	    }
	    else{
	      $output_purchase_invoice='';
	    }


	    $output_sales_invoice='';
	    if(count($salesinvoicelist)){
	      foreach ($salesinvoicelist as $invoice) {

	        $sales_attached_list = DB::table('request_sales_attached')->where('sales_id', $invoice->invoice_id)->get();

	        $output_sales_attach='<tr>
	          <td>Sales Invoices</td>
	          <td>Attachements</td>          
	          <td></td>
	        </tr>';

	        if(count($sales_attached_list)){
	          foreach ($sales_attached_list as $sales_attach) {

	            $output_sales_attach.='<tr>
	            <td></td>
	            <td>'.$sales_attach->attachment.'</td>
	            <td></td>
	            
	            </tr>
	            ';
	          }
	        }
	        else{
	          $output_sales_attach='';
	        }




	        $output_sales_invoice.='
	        <tr>
	          <td>Sales Invoices</td>
	          <td>'.$invoice->specific_invoice.'</td>
	          <td>Note</td>	                 
	        </tr>'.$output_sales_attach.'
	        ';
	      }     
	    }
	    else{
	      $output_sales_invoice='';
	    }

	    $output_statement='';
	    if(count($bankstatementlist)){
	      foreach ($bankstatementlist as $statement) {


	        if($statement->statment_number == ''){
	          $result_bank = 'From '.date('d-M-Y', strtotime($statement->from_date)).' to '.date('d-M-Y', strtotime($statement->to_date));
	        }
	        elseif($statement->from_date == '0000-00-00'){
	          $result_bank = 'Statement Numbers '.$statement->statment_number;
	        }
	        else{
	           $result_bank = 'Statement Numbers '.$statement->statment_number.' From '.date('d-M-Y', strtotime($statement->from_date)).' to '.date('d-M-Y', strtotime($statement->to_date));
	        }


	        $output_statement.='
	        <tr>
	          <td>Bank Statements</td>
	          <td>'.$result_bank.'</td>
	          <td>Note</td>
	          
	        </tr>
	        ';
	      }     
	    }
	    else{
	      $output_statement='';
	    }

	    $output_cheque='';
	    if(count($chequebooklist)){
	      foreach ($chequebooklist as $cheque) {


	        $cheque_attached_list = DB::table('request_cheque_attached')->where('cheque_id', $cheque->cheque_id)->get();

	        $output_cheque_attach='<tr>
	          <td>Cheque Books</td>
	          <td>Attachements</td>
	          <td></td>
	          <td></td>
	          <td></td>
	        </tr>';

	        if(count($cheque_attached_list)){
	          foreach ($cheque_attached_list as $cheque_attach) {

	            $output_cheque_attach.='<tr>
	            <td></td>
	            <td>'.$cheque_attach->attachment.'</td>
	            <td></td>	            
	            </tr>
	            ';
	          }
	        }
	        else{
	          $output_cheque_attach='';
	        }

	        


	        $output_cheque.='
	        <tr>
	          <td>Cheque Books</td>
	          <td>'.$cheque->specific_number.'</td>
	          <td>Note</td>	          
	        </tr>
	        '.$output_cheque_attach.'
	        ';
	      }     
	    }
	    else{
	      $output_cheque='';
	    }


	    $output_other='';
	    if(count($otherlist)){
	      foreach ($otherlist as $other) {       

	        
	        $output_other.='
	        <tr>
	          <td>Other Information</td>
	          <td>'.$other->other_content.'</td>
	          <td>Note</td>
	          
	        </tr>
	        ';
	      }     
	    }
	    else{
	      $output_other='';
	    }

		$output='
		<p>Hi '.$to_user_name.', </p>
		<p>We have complied the following list or items / information related to SUBJECT that we require from you.  Please can you get for us:</p>
		<p>Subject: Information Request: '.$request_details->year.' &#8212; '.$category->category_name.'</p>
		<table class="table" style="border:0px solid;border-collapse:collapse">
    		<thead>
    			<tr>
    				<th style="text-align:left; width:160px;">Items on this request</th>
    				<th style="text-align:left; width:300px;"></th>
    				<th></th>
    			</tr>
    			'.$output_purchase_invoice.'
    			'.$output_sales_invoice.'
    			'.$output_statement.'
    			'.$output_cheque.'
    			'.$output_other.'
    		</thead>
    		<tbody>
    		</tbody>
    	</table>

    	<p>'.$category->signature.'</p>';

		echo json_encode(array('subject' => 'Information Request: '.$request_details->year.' &#8212; '.$category->category_name, 'content' => $output,'user_id' => $employee->user_id));
	}
	public function send_request_to_client_edit()
	{
		$id = Input::get('requestid');
		$to_user = Input::get('to_user');		
		$request_details = DB::table('request_client')->where('request_id', $id)->first();
		$category = DB::table('request_category')->where('category_id', $request_details->category_id)->first();
		$employee = DB::table('user')->where('user_id', $request_details->request_from)->first();
		if($to_user != "")
		{
			$to_employee = DB::table('cm_clients')->where('client_id', $to_user)->first();
			$to_user_name = $to_employee->firstname;
		}
		else{
			$to_user_name = '';
		}

		$purchaseinvoicelist = DB::table('request_purchase_invoice')->where('request_id', $id)->get();
		$salesinvoicelist = DB::table('request_sales_invoice')->where('request_id', $id)->get();
		$bankstatementlist = DB::table('request_bank_statement')->where('request_id', $id)->get();
		$chequebooklist = DB::table('request_cheque')->where('request_id', $id)->get();
		$otherlist = DB::table('request_others')->where('request_id', $id)->get();


		$output_purchase_invoice='';
		if(count($purchaseinvoicelist)){
	      foreach ($purchaseinvoicelist as $invoice) {

	        $purchase_attached_list = DB::table('request_purchase_attached')->where('purchase_id', $invoice->invoice_id)->get();

	        $output_purchase_attach='<tr>
	          <td>Purchase Invoices</td>
	          <td>Attachements</td>
	          <td></td>
	        </tr>';

	        if(count($purchase_attached_list)){
	          foreach ($purchase_attached_list as $purchase_attach) {

	            $output_purchase_attach.='<tr>
	            <td></td>
	            <td>'.$purchase_attach->attachment.'</td>
	            <td></td>
	            </tr>
	            ';
	          }
	        }
	        else{
	          $output_purchase_attach='';
	        }



	        $output_purchase_invoice.='
	        <tr>
	          <td>Purchase Invoices</td>
	          <td>'.$invoice->specific_invoice.'</td>
	          <td>Note</td>
	                  
	        </tr>'.$output_purchase_attach.'
	        ';
	      }     
	    }
	    else{
	      $output_purchase_invoice='';
	    }


	    $output_sales_invoice='';
	    if(count($salesinvoicelist)){
	      foreach ($salesinvoicelist as $invoice) {

	        $sales_attached_list = DB::table('request_sales_attached')->where('sales_id', $invoice->invoice_id)->get();

	        $output_sales_attach='<tr>
	          <td>Sales Invoices</td>
	          <td>Attachements</td>          
	          <td></td>
	        </tr>';

	        if(count($sales_attached_list)){
	          foreach ($sales_attached_list as $sales_attach) {

	            $output_sales_attach.='<tr>
	            <td></td>
	            <td>'.$sales_attach->attachment.'</td>
	            <td></td>
	            
	            </tr>
	            ';
	          }
	        }
	        else{
	          $output_sales_attach='';
	        }




	        $output_sales_invoice.='
	        <tr>
	          <td>Sales Invoices</td>
	          <td>'.$invoice->specific_invoice.'</td>
	          <td>Note</td>	                 
	        </tr>'.$output_sales_attach.'
	        ';
	      }     
	    }
	    else{
	      $output_sales_invoice='';
	    }

	    $output_statement='';
	    if(count($bankstatementlist)){
	      foreach ($bankstatementlist as $statement) {


	        if($statement->statment_number == ''){
	          $result_bank = 'From '.date('d-M-Y', strtotime($statement->from_date)).' to '.date('d-M-Y', strtotime($statement->to_date));
	        }
	        elseif($statement->from_date == '0000-00-00'){
	          $result_bank = 'Statement Numbers '.$statement->statment_number;
	        }
	        else{
	           $result_bank = 'Statement Numbers '.$statement->statment_number.' From '.date('d-M-Y', strtotime($statement->from_date)).' to '.date('d-M-Y', strtotime($statement->to_date));
	        }


	        $output_statement.='
	        <tr>
	          <td>Bank Statements</td>
	          <td>'.$result_bank.'</td>
	          <td>Note</td>
	          
	        </tr>
	        ';
	      }     
	    }
	    else{
	      $output_statement='';
	    }

	    $output_cheque='';
	    if(count($chequebooklist)){
	      foreach ($chequebooklist as $cheque) {


	        $cheque_attached_list = DB::table('request_cheque_attached')->where('cheque_id', $cheque->cheque_id)->get();

	        $output_cheque_attach='<tr>
	          <td>Cheque Books</td>
	          <td>Attachements</td>
	          <td></td>
	          <td></td>
	          <td></td>
	        </tr>';

	        if(count($cheque_attached_list)){
	          foreach ($cheque_attached_list as $cheque_attach) {

	            $output_cheque_attach.='<tr>
	            <td></td>
	            <td>'.$cheque_attach->attachment.'</td>
	            <td></td>	            
	            </tr>
	            ';
	          }
	        }
	        else{
	          $output_cheque_attach='';
	        }

	        


	        $output_cheque.='
	        <tr>
	          <td>Cheque Books</td>
	          <td>'.$cheque->specific_number.'</td>
	          <td>Note</td>	          
	        </tr>
	        '.$output_cheque_attach.'
	        ';
	      }     
	    }
	    else{
	      $output_cheque='';
	    }


	    $output_other='';
	    if(count($otherlist)){
	      foreach ($otherlist as $other) {       

	        
	        $output_other.='
	        <tr>
	          <td>Other Information</td>
	          <td>'.$other->other_content.'</td>
	          <td>Note</td>
	          
	        </tr>
	        ';
	      }     
	    }
	    else{
	      $output_other='';
	    }

		$output='
		<p>Hi '.$to_user_name.', </p>
		<p>We have complied the following list or items / information related to SUBJECT that we require from you.  Please can you get for us:</p>
		<p>Subject: Information Request: '.$request_details->year.' &#8212; '.$category->category_name.'</p>
		<table class="table" style="border:0px solid;border-collapse:collapse">
    		<thead>
    			<tr>
    				<th style="text-align:left; width:160px;">Items on this request</th>
    				<th style="text-align:left; width:300px;"></th>
    				<th></th>
    			</tr>
    			'.$output_purchase_invoice.'
    			'.$output_sales_invoice.'
    			'.$output_statement.'
    			'.$output_cheque.'
    			'.$output_other.'
    		</thead>
    		<tbody>
    		</tbody>
    	</table>

    	<p>'.$category->signature.'</p>';

		echo json_encode(array('subject' => 'Information Request: '.$request_details->year.' &#8212; '.$category->category_name, 'content' => $output,'user_id' => $employee->user_id));
	}
	public function send_request_to_client_some_not_edit()
	{
		$id = Input::get('requestid');
		$to_user = Input::get('to_user');		
		$request_details = DB::table('request_client')->where('request_id', $id)->first();
		$category = DB::table('request_category')->where('category_id', $request_details->category_id)->first();
		$employee = DB::table('user')->where('user_id', $request_details->request_from)->first();
		if($to_user != "")
		{
			$to_employee = DB::table('cm_clients')->where('client_id', $to_user)->first();
			$to_user_name = $to_employee->firstname;
		}
		else{
			$to_user_name = '';
		}
		$last_sent = DB::table('request_client_email_sent')->where('request_id',$id)->orderBy('id', 'DESC')->first();
		$last_email_sent = date('d-M-Y H:i', strtotime($last_sent->email_sent));
		$purchaseinvoicelist = DB::table('request_purchase_invoice')->where('request_id', $id)->get();
		$salesinvoicelist = DB::table('request_sales_invoice')->where('request_id', $id)->get();
		$bankstatementlist = DB::table('request_bank_statement')->where('request_id', $id)->where('status',0)->get();
		$chequebooklist = DB::table('request_cheque')->where('request_id', $id)->get();
		$otherlist = DB::table('request_others')->where('request_id', $id)->where('status',0)->get();


		$output_purchase_invoice='';
		if(count($purchaseinvoicelist)){
	      foreach ($purchaseinvoicelist as $invoice) {

	        $purchase_attached_list = DB::table('request_purchase_attached')->where('purchase_id', $invoice->invoice_id)->where('status',0)->get();

	        $output_purchase_attach='<tr>
	          <td>Purchase Invoices</td>
	          <td>Attachements</td>
	          <td></td>
	        </tr>';

	        if(count($purchase_attached_list)){
	          foreach ($purchase_attached_list as $purchase_attach) {

	            $output_purchase_attach.='<tr>
	            <td></td>
	            <td>'.$purchase_attach->attachment.'</td>
	            <td></td>
	            </tr>
	            ';
	          }
	        }
	        else{
	          $output_purchase_attach='';
	        }

	        if($invoice->status == 0)
	        {
	        	$output_purchase_invoice.='
		        <tr>
		          <td>Purchase Invoices</td>
		          <td>'.$invoice->specific_invoice.'</td>
		          <td>Note</td>
		                  
		        </tr>'.$output_purchase_attach.'
		        ';
	        }
	        else{
	        	$output_purchase_invoice.=$output_purchase_attach;
	        }
	      }     
	    }
	    else{
	      $output_purchase_invoice='';
	    }


	    $output_sales_invoice='';
	    if(count($salesinvoicelist)){
	      foreach ($salesinvoicelist as $invoice) {

	        $sales_attached_list = DB::table('request_sales_attached')->where('sales_id', $invoice->invoice_id)->where('status',0)->get();

	        $output_sales_attach='<tr>
	          <td>Sales Invoices</td>
	          <td>Attachements</td>          
	          <td></td>
	        </tr>';

	        if(count($sales_attached_list)){
	          foreach ($sales_attached_list as $sales_attach) {

	            $output_sales_attach.='<tr>
	            <td></td>
	            <td>'.$sales_attach->attachment.'</td>
	            <td></td>
	            
	            </tr>
	            ';
	          }
	        }
	        else{
	          $output_sales_attach='';
	        }



	        if($invoice->status == 0)
	        {
	        	$output_sales_invoice.='
		        <tr>
		          <td>Sales Invoices</td>
		          <td>'.$invoice->specific_invoice.'</td>
		          <td>Note</td>
		                  
		        </tr>'.$output_sales_attach.'
		        ';
	        }
	        else{
	        	$output_sales_invoice.=$output_sales_attach;
	        }
	      }     
	    }
	    else{
	      $output_sales_invoice='';
	    }

	    $output_statement='';
	    if(count($bankstatementlist)){
	      foreach ($bankstatementlist as $statement) {


	        if($statement->statment_number == ''){
	          $result_bank = 'From '.date('d-M-Y', strtotime($statement->from_date)).' to '.date('d-M-Y', strtotime($statement->to_date));
	        }
	        elseif($statement->from_date == '0000-00-00'){
	          $result_bank = 'Statement Numbers '.$statement->statment_number;
	        }
	        else{
	           $result_bank = 'Statement Numbers '.$statement->statment_number.' From '.date('d-M-Y', strtotime($statement->from_date)).' to '.date('d-M-Y', strtotime($statement->to_date));
	        }


	        $output_statement.='
	        <tr>
	          <td>Bank Statements</td>
	          <td>'.$result_bank.'</td>
	          <td>Note</td>
	          
	        </tr>
	        ';
	      }     
	    }
	    else{
	      $output_statement='';
	    }

	    $output_cheque='';
	    if(count($chequebooklist)){
	      foreach ($chequebooklist as $cheque) {


	        $cheque_attached_list = DB::table('request_cheque_attached')->where('cheque_id', $cheque->cheque_id)->where('status',0)->get();

	        $output_cheque_attach='<tr>
	          <td>Cheque Books</td>
	          <td>Attachements</td>
	          <td></td>
	          <td></td>
	          <td></td>
	        </tr>';

	        if(count($cheque_attached_list)){
	          foreach ($cheque_attached_list as $cheque_attach) {

	            $output_cheque_attach.='<tr>
	            <td></td>
	            <td>'.$cheque_attach->attachment.'</td>
	            <td></td>	            
	            </tr>
	            ';
	          }
	        }
	        else{
	          $output_cheque_attach='';
	        }

	        
	        if($cheque->status == 0)
	        {
	        	$output_cheque.='
		        <tr>
		          <td>Cheque Books</td>
		          <td>'.$cheque->specific_number.'</td>
		          <td>Note</td>
		                  
		        </tr>'.$output_cheque_attach.'
		        ';
	        }
	        else{
	        	$output_cheque.=$output_cheque_attach;
	        }
	      }     
	    }
	    else{
	      $output_cheque='';
	    }


	    $output_other='';
	    if(count($otherlist)){
	      foreach ($otherlist as $other) {       

	        
	        $output_other.='
	        <tr>
	          <td>Other Information</td>
	          <td>'.$other->other_content.'</td>
	          <td>Note</td>
	          
	        </tr>
	        ';
	      }     
	    }
	    else{
	      $output_other='';
	    }

		$output='
		<p>Hi '.$to_user_name.', </p>
		<p>On "'.$last_email_sent.'" we sent you a request for some information, we have not has a reply to that email yet – here is a list of what we require form you </p>

		<p>WE DID NOTE GET THE FOLLOWING ITEMS</p>
		<table class="table" style="border:0px solid;border-collapse:collapse">
    		<thead>
    			<tr>
    				<th style="text-align:left; width:160px;">ITEMS NOT RECEIVES</th>
    				<th style="text-align:left; width:300px;"></th>
    				<th></th>
    			</tr>
    			'.$output_purchase_invoice.'
    			'.$output_sales_invoice.'
    			'.$output_statement.'
    			'.$output_cheque.'
    			'.$output_other.'
    		</thead>
    		<tbody>
    		</tbody>
    	</table>';

    	$purchaseinvoicelist = DB::table('request_purchase_invoice')->where('request_id', $id)->get();
		$salesinvoicelist = DB::table('request_sales_invoice')->where('request_id', $id)->get();
		$bankstatementlist = DB::table('request_bank_statement')->where('request_id', $id)->get();
		$chequebooklist = DB::table('request_cheque')->where('request_id', $id)->get();
		$otherlist = DB::table('request_others')->where('request_id', $id)->get();


		$output_purchase_invoice='';
		if(count($purchaseinvoicelist)){
	      foreach ($purchaseinvoicelist as $invoice) {

	        $purchase_attached_list = DB::table('request_purchase_attached')->where('purchase_id', $invoice->invoice_id)->get();

	        $output_purchase_attach='<tr>
	          <td>Purchase Invoices</td>
	          <td>Attachements</td>
	          <td></td>
	        </tr>';

	        if(count($purchase_attached_list)){
	          foreach ($purchase_attached_list as $purchase_attach) {

	            $output_purchase_attach.='<tr>
	            <td></td>
	            <td>'.$purchase_attach->attachment.'</td>
	            <td></td>
	            </tr>
	            ';
	          }
	        }
	        else{
	          $output_purchase_attach='';
	        }



	        $output_purchase_invoice.='
	        <tr>
	          <td>Purchase Invoices</td>
	          <td>'.$invoice->specific_invoice.'</td>
	          <td>Note</td>
	                  
	        </tr>'.$output_purchase_attach.'
	        ';
	      }     
	    }
	    else{
	      $output_purchase_invoice='';
	    }


	    $output_sales_invoice='';
	    if(count($salesinvoicelist)){
	      foreach ($salesinvoicelist as $invoice) {

	        $sales_attached_list = DB::table('request_sales_attached')->where('sales_id', $invoice->invoice_id)->get();

	        $output_sales_attach='<tr>
	          <td>Sales Invoices</td>
	          <td>Attachements</td>          
	          <td></td>
	        </tr>';

	        if(count($sales_attached_list)){
	          foreach ($sales_attached_list as $sales_attach) {

	            $output_sales_attach.='<tr>
	            <td></td>
	            <td>'.$sales_attach->attachment.'</td>
	            <td></td>
	            
	            </tr>
	            ';
	          }
	        }
	        else{
	          $output_sales_attach='';
	        }




	        $output_sales_invoice.='
	        <tr>
	          <td>Sales Invoices</td>
	          <td>'.$invoice->specific_invoice.'</td>
	          <td>Note</td>	                 
	        </tr>'.$output_sales_attach.'
	        ';
	      }     
	    }
	    else{
	      $output_sales_invoice='';
	    }

	    $output_statement='';
	    if(count($bankstatementlist)){
	      foreach ($bankstatementlist as $statement) {


	        if($statement->statment_number == ''){
	          $result_bank = 'From '.date('d-M-Y', strtotime($statement->from_date)).' to '.date('d-M-Y', strtotime($statement->to_date));
	        }
	        elseif($statement->from_date == '0000-00-00'){
	          $result_bank = 'Statement Numbers '.$statement->statment_number;
	        }
	        else{
	           $result_bank = 'Statement Numbers '.$statement->statment_number.' From '.date('d-M-Y', strtotime($statement->from_date)).' to '.date('d-M-Y', strtotime($statement->to_date));
	        }


	        $output_statement.='
	        <tr>
	          <td>Bank Statements</td>
	          <td>'.$result_bank.'</td>
	          <td>Note</td>
	          
	        </tr>
	        ';
	      }     
	    }
	    else{
	      $output_statement='';
	    }

	    $output_cheque='';
	    if(count($chequebooklist)){
	      foreach ($chequebooklist as $cheque) {


	        $cheque_attached_list = DB::table('request_cheque_attached')->where('cheque_id', $cheque->cheque_id)->get();

	        $output_cheque_attach='<tr>
	          <td>Cheque Books</td>
	          <td>Attachements</td>
	          <td></td>
	          <td></td>
	          <td></td>
	        </tr>';

	        if(count($cheque_attached_list)){
	          foreach ($cheque_attached_list as $cheque_attach) {

	            $output_cheque_attach.='<tr>
	            <td></td>
	            <td>'.$cheque_attach->attachment.'</td>
	            <td></td>	            
	            </tr>
	            ';
	          }
	        }
	        else{
	          $output_cheque_attach='';
	        }

	        


	        $output_cheque.='
	        <tr>
	          <td>Cheque Books</td>
	          <td>'.$cheque->specific_number.'</td>
	          <td>Note</td>	          
	        </tr>
	        '.$output_cheque_attach.'
	        ';
	      }     
	    }
	    else{
	      $output_cheque='';
	    }


	    $output_other='';
	    if(count($otherlist)){
	      foreach ($otherlist as $other) {       

	        
	        $output_other.='
	        <tr>
	          <td>Other Information</td>
	          <td>'.$other->other_content.'</td>
	          <td>Note</td>
	          
	        </tr>
	        ';
	      }     
	    }
	    else{
	      $output_other='';
	    }

    	$output.='<p>A full list of what was sent to you on "'.$last_email_sent.'" </p>
		<table class="table" style="border:0px solid;border-collapse:collapse">
    		<thead>
    			<tr>
    				<th style="text-align:left; width:160px;">Items on this request</th>
    				<th style="text-align:left; width:300px;"></th>
    				<th></th>
    			</tr>
    			'.$output_purchase_invoice.'
    			'.$output_sales_invoice.'
    			'.$output_statement.'
    			'.$output_cheque.'
    			'.$output_other.'
    		</thead>
    		<tbody>
    		</tbody>
    	</table>
    	<p>'.$category->signature.'</p>';

		echo json_encode(array('subject' => 'Information Request: '.$request_details->year.' &#8212; '.$category->category_name, 'content' => $output,'user_id' => $employee->user_id));
	}
	public function email_to_client()
	{
		$request_id = Input::get('request_id_email_client');
		$from_input = Input::get('from_user_to_client');
		$details = DB::table('user')->where('user_id',$from_input)->first();
		$from = $details->email;
		$user_name = $details->firstname.' '.$details->lastname;

		$to_input = Input::get('client_search');
		$details_to = DB::table('cm_clients')->where('client_id',$to_input)->first();
		$to_user = $details_to->email;

		$toemails = $to_user.','.Input::get('cc_approval_to_client');
		$sentmails = $to_user.', '.Input::get('cc_approval_to_client');
		$subject = Input::get('subject_to_client'); 
		$message = Input::get('message_editor_to_client');
		$explode = explode(',',$toemails);
		$data['sentmails'] = $sentmails;

		if(count($explode))
		{
			foreach($explode as $exp)
			{
				$to = trim($exp);
				$data['logo'] = URL::to('assets/images/easy_payroll_logo.png');
				$data['message'] = $message;
				$contentmessage = view('user/email_share_paper_crm', $data);

				$email = new PHPMailer();
				$email->SetFrom($from, $user_name); //Name is optional
				$email->Subject   = $subject;
				$email->Body      = $contentmessage;
				$email->IsHTML(true);
				$email->AddAddress($to);
				$email->Send();
			}
			$date = date('Y-m-d H:i:s');
			$dataval['status'] = 1;
			$dataval['request_sent'] = $date;
			DB::table('request_client')->where('request_id',$request_id)->update($dataval);

			$dataemail['request_id'] = $request_id;
			$dataemail['email_sent'] = $date;
			DB::table('request_client_email_sent')->insert($dataemail);

			return Redirect::back()->with('message', 'Email Sent Successfully or Client.');
		}
		else{
			return Redirect::back()->with('error', 'Email Field is empty so email is not sent');
		}
	}

	public function email_for_approval()
	{
		$request_id = Input::get('request_id_email_approval');
		$from_input = Input::get('from_user');
		$details = DB::table('user')->where('user_id',$from_input)->first();
		$from = $details->email;
		$user_name = $details->firstname.' '.$details->lastname;

		$to_input = Input::get('to_user');
		$details_to =DB::table('user')->where('user_id',$to_input)->first();
		$to_user = $details_to->email;

		$toemails = $to_user.','.Input::get('cc_approval');
		$sentmails = $to_user.', '.Input::get('cc_approval');
		$subject = Input::get('subject_approval'); 
		$message = Input::get('message_editor');
		$explode = explode(',',$toemails);
		$data['sentmails'] = $sentmails;

		if(count($explode))
		{
			foreach($explode as $exp)
			{
				$to = trim($exp);
				$data['logo'] = URL::to('assets/images/easy_payroll_logo.png');
				$data['message'] = $message;
				$contentmessage = view('user/email_share_paper_crm', $data);
				$email = new PHPMailer();
				$email->SetFrom($from, $user_name); //Name is optional
				$email->Subject   = $subject;
				$email->Body      = $contentmessage;
				$email->IsHTML(true);
				$email->AddAddress($to);
				$email->Send();
			}
			return Redirect::back()->with('message', 'Email Sent Successfully for Approval');
		}
		else{
			return Redirect::back()->with('error', 'Email Field is empty so email is not sent');
		}
	}
}

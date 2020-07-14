<?php namespace App\Http\Controllers\user;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\InvoiceSystem;
use App\CmClients;
use Session;
use URL;
use PDF;
use Response;
use PHPExcel; 
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use Hash;

class InvoiceController extends Controller {

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
	public function __construct(invoicesystem $invoicesystem, cmclients $cmclients)
	{
		$this->middleware('userauth');
		$this->invoicesystem = $invoicesystem;
		$this->cmclients = $cmclients;
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function invoicemanagement()
	{
		$invoice = DB::table('invoice_system')->select('id', 'invoice_number', 'invoice_date', 'client_id', 'inv_net', 'vat_rate', 'status', 'statement')->orderBy('id','asc')->offset(0)->limit(1000)->get();		
		return view('user/invoice/invoice', array('title' => 'Invoice System Management', 'invoicelist' => $invoice));
	}
	
	public function invoice_search()
	{
		$input = Input::get('input');
		$select = Input::get('select');

		if($select == 'company_name'){

			
			$companydetail = DB::table('cm_clients')->where('company','like','%'.$input.'%')->get();

					
			$output = '';
			$i=1;
			if(count($companydetail)){
				foreach ($companydetail as $key => $company) {	

					$invoicelist = DB::table('invoice_system')->where('client_id', $company->client_id)->get();					
					
					if(count($invoicelist)){ 
						foreach($invoicelist as $invoice){ 
							$client_details = DB::table('cm_clients')->where('client_id', $invoice->client_id)->first();


							if($invoice->statement == "No"){
								$textcolor="color:#f00";
							}
							else{
								$textcolor="color:#00751a";
							}
							$output.='
								<tr>
									<td>'.$i.'</td>
									<td align="left" style="'.$textcolor.'">'.$invoice->invoice_number.'</td>
									<td align="left" style="'.$textcolor.'">'.$invoice->invoice_date.'</td>
									<td align="left" style="'.$textcolor.'">'.$invoice->client_id.'</td>
									<td align="left" style="'.$textcolor.'">'.$client_details->company.'</td>
									<td align="left" style="'.$textcolor.'">'.$invoice->inv_net.'</td>
									<td align="left" style="'.$textcolor.'">'.$invoice->vat_rate.'</td>
									<td align="left" style="'.$textcolor.'"></td>
									<td align="left" style="'.$textcolor.'">'. $invoice->statement.'</td>
								</tr>
							';
							$i++;
						}						
					}					
				}				
			}	
			if($i == 1)
		        {
		          $output.='<tr><td colspan="9" align="center">Empty</td></tr>';
		        }
				echo $output;				
		}
		else{
			$invoicelist = DB::table('invoice_system')->where($select,'like','%'.$input.'%')->get();
			$output = '';

			$i=1;
			if(count($invoicelist)){ 
				foreach($invoicelist as $key => $invoice){ 
					$client_details = DB::table('cm_clients')->where('client_id', $invoice->client_id)->first();
					if($invoice->statement == "No"){
						$textcolor="color:#f00";
					}
					else{
						$textcolor="color:#00751a";
					}
					$output.='
						<tr>
							<td>'.$i.'</td>
							<td align="left" style="'.$textcolor.'">'.$invoice->invoice_number.'</td>
							<td align="left" style="'.$textcolor.'">'.$invoice->invoice_date.'</td>
							<td align="left" style="'.$textcolor.'">'.$invoice->client_id.'</td>
							<td align="left" style="'.$textcolor.'">'.$client_details->company.'</td>
							<td align="left" style="'.$textcolor.'">'.$invoice->inv_net.'</td>
							<td align="left" style="'.$textcolor.'">'.$invoice->vat_rate.'</td>
							<td align="left" style="'.$textcolor.'"></td>
							<td align="left" style="'.$textcolor.'">'. $invoice->statement.'</td>
						</tr>
					';
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

	public function show_statement()
	{
		
		$statement = Input::get('value');
		if($statement != 1){
			$invoicelist = DB::table('invoice_system')->where('statement', 'yes')->Orwhere('statement', '')->get();
			$output = '';

			$i=1;
			if(count($invoicelist)){ 
				foreach($invoicelist as $key => $invoice){ 
					$client_details = DB::table('cm_clients')->where('client_id', $invoice->client_id)->first();

					if($invoice->statement ==''){
						$statementtext = 'N/A';
					}
					else{
						$statementtext = $invoice->statement;
					};



					if($invoice->statement == "No"){
						$textcolor="color:#f00";
					}
					else{
						$textcolor="color:#00751a";
					}
					$output.='
						<tr>
							<td>'.$i.'</td>
							<td align="left" style="'.$textcolor.'">'.$invoice->invoice_number.'</td>
							<td align="left" style="'.$textcolor.'">'.$invoice->invoice_date.'</td>
							<td align="left" style="'.$textcolor.'">'.$invoice->client_id.'</td>
							<td align="left" style="'.$textcolor.'">'.$client_details->company.'</td>
							<td align="left" style="'.$textcolor.'">'.$invoice->inv_net.'</td>
							<td align="left" style="'.$textcolor.'">'.$invoice->vat_rate.'</td>
							<td align="left" style="'.$textcolor.'"></td>
							<td align="left" style="'.$textcolor.'">'.$statementtext.'</td>
						</tr>
					';
					$i++;
				}
			}
			else
	        {
	          $output.='<tr><td colspan="9" align="center">Empty</td></tr>';
	        }
			echo $output;			
		}
		else{
			$invoicelist = DB::table('invoice_system')->get();
			$output = '';

			$i=1;
			if(count($invoicelist)){ 
				foreach($invoicelist as $key => $invoice){ 
					$client_details = DB::table('cm_clients')->where('client_id', $invoice->client_id)->first();


					if($invoice->statement ==''){
						$statementtext = 'N/A';
					}
					else{
						$statementtext = $invoice->statement;
					};



					if($invoice->statement == "No"){
						$textcolor="color:#f00";
					}
					else{
						$textcolor="color:#00751a";
					}
					$output.='
						<tr>
							<td>'.$i.'</td>
							<td align="left" style="'.$textcolor.'">'.$invoice->invoice_number.'</td>
							<td align="left" style="'.$textcolor.'">'.$invoice->invoice_date.'</td>
							<td align="left" style="'.$textcolor.'">'.$invoice->client_id.'</td>
							<td align="left" style="'.$textcolor.'">'.$client_details->company.'</td>
							<td align="left" style="'.$textcolor.'">'.$invoice->inv_net.'</td>
							<td align="left" style="'.$textcolor.'">'.$invoice->vat_rate.'</td>
							<td align="left" style="'.$textcolor.'"></td>
							<td align="left" style="'.$textcolor.'">'. $statementtext.'</td>
						</tr>
					';
					$i++;
				}
			}
			else
	        {
	          $output.='<tr><td colspan="9" align="center">Empty</td></tr>';
	        }
			echo $output;
		}		
	}

	public function report_client_invoice(){

		$id = Input::get('id');
		
		if($id != 1){

			
			$invoicelist = DB::table('invoice_system')->groupBy('client_id')->get();

			$output = '';
			$i=1;

			if(count($invoicelist)){ 
				foreach($invoicelist as $invoice){ 
					$client_details = DB::table('cm_clients')->where('client_id', $invoice->client_id)->first();

					$count = DB::table('invoice_system')->where('client_id', $client_details->client_id)->count();


					if($invoice->statement == "No"){
						$textcolor="color:#f00";
					}
					else{
						$textcolor="color:#00751a";
					}
					$output.='
						<tr>
							<td>'.$i.'</td>
							<td><input type="checkbox" name="select_client" class="select_client class_'.$invoice->client_id.'" data-element="'.$invoice->client_id.'" value="'.$invoice->client_id.'"><label>&nbsp</label></td>
							<td align="left" style="'.$textcolor.'">'.$invoice->client_id.'</td>							
							<td align="left" style="'.$textcolor.'">'.$client_details->firstname.'</td>
							<td align="left" style="'.$textcolor.'">'.$client_details->company.'</td>
							<td align="center" style="'.$textcolor.'">'.$count.'</td>
						</tr>
					';
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

	public function invoice_report_csv($id=""){	
		$ids = explode(",",Input::get('value'));

		$quoted_id = '';
		foreach($ids as $id)
		{
			if($quoted_id == "")
			{
				$quoted_id = '"'.$id.'"';
			}
			else{
				$quoted_id = $quoted_id.',"'.$id.'"';
			}
		}

		if(isset($_POST['fromdate']))
		{
			
			$from = explode("-",Input::get('fromdate'));
			$to = explode("-",Input::get('todate'));

			$fromdate = $from[2].'-'.$from[0].'-'.$from[1];
			$todate = $to[2].'-'.$to[0].'-'.$to[1];

			$startdate = strtotime($fromdate);
			$enddate = strtotime($todate);

			$invoice = DB::select('SELECT * from `invoice_system` WHERE UNIX_TIMESTAMP(invoice_date) >= '.$startdate.' AND UNIX_TIMESTAMP(invoice_date) <= '.$enddate.' AND client_id IN ('.$quoted_id.')');
		}
		else{
			$invoice = DB::table('invoice_system')->whereIn('client_id', $ids)->get();
		}

		$headers = array(
	        "Content-type" => "text/csv",
	        "Content-Disposition" => "attachment; filename=CM_Report.csv",
	        "Pragma" => "no-cache",
	        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
	        "Expires" => "0"
	    );

      	
		$columns = array('#', 'Invoice Number', 'Invoice Date', 'Client ID', 'Company Name', 'Net', 'VAT', 'Gross');

		$callback = function() use ($invoice, $columns)
    	{
	       	$file = fopen('papers/CM_Report.csv', 'w');
		    fputcsv($file, $columns);

			$i=1;
			foreach ($invoice as $single) {
				$company_details = DB::table('cm_clients')->where('client_id', $single->client_id)->first();

				

		      	$columns_2 = array($i, $single->invoice_number, $single->invoice_date, $single->client_id, $company_details->company, $single->inv_net, $single->vat_rate.'%' );
				fputcsv($file, $columns_2);
				$i++;
			}
			fclose($file);	
		};
		return Response::stream($callback, 200, $headers);
	}

	public function invoice_report_pdf()
	{

		/*$ids = 'GBS058';	

		$invoicelist = DB::table('invoice_system')->where('client_id', $ids)->first();

		$output='';

		$output.='<tr>
			
			<td align="left">'.$invoicelist->invoice_number.'</td>
			<td align="left">'.$invoicelist->invoice_date.'</td>
			<td align="left">'.$invoicelist->client_id.'</td>
			
			<td align="left">'.$invoicelist->inv_net.'</td>
			<td align="left">'.$invoicelist->vat_rate.'</td>
			<td align="left"></td>
			</tr>';

			echo $output;*/



		$ids = explode(",",Input::get('value'));

		$quoted_id = '';
		foreach($ids as $id)
		{
			if($quoted_id == "")
			{
				$quoted_id = '"'.$id.'"';
			}
			else{
				$quoted_id = $quoted_id.',"'.$id.'"';
			}
		}

		if(isset($_POST['fromdate']))
		{
			
			$from = explode("-",Input::get('fromdate'));
			$to = explode("-",Input::get('todate'));

			$fromdate = $from[2].'-'.$from[0].'-'.$from[1];
			$todate = $to[2].'-'.$to[0].'-'.$to[1];

			$startdate = strtotime($fromdate);
			$enddate = strtotime($todate);

			$invoicelist = DB::select('SELECT * from `invoice_system` WHERE UNIX_TIMESTAMP(invoice_date) >= '.$startdate.' AND UNIX_TIMESTAMP(invoice_date) <= '.$enddate.' AND client_id IN ('.$quoted_id.')');
		}
		else{
			$invoicelist = DB::table('invoice_system')->whereIn('client_id', $ids)->get();
		}
		
		$output = '';
		$i=1;

		if(count($invoicelist)){
				foreach($invoicelist as $key => $invoice)
				{
					$client_details = DB::table('cm_clients')->where('client_id', $invoice->client_id)->first();
					$output.='<tr>
									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$i.'</td>
									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px" align="left">'.$invoice->invoice_number.'</td>
									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px" align="left">'.$invoice->invoice_date.'</td>
									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px" align="left">'.$invoice->client_id.'</td>
									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px" align="left">'.$client_details->company.'</td>
									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px" align="left">'.$invoice->inv_net.'</td>
									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px" align="left">'.$invoice->vat_rate.'</td>
									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px" align="left"></td>
								</tr>';
								$i++;
				}
		}
		echo $output;
	}

	public function invoice_download_report_pdfs()
	{

		$htmlval = Input::get('htmlval');
		$pdf = PDF::loadHTML($htmlval);
		$pdf->setPaper('A4', 'landscape');
		
		$pdf->save('papers/Invoice Report.pdf');
		echo 'Invoice Report.pdf';
	}



	public function report_client_invoice_date_filter(){

		$id = Input::get('id');

		$from = explode("-",Input::get('fdate'));
		$to = explode("-",Input::get('tdate'));

		$fromdate = $from[2].'-'.$from[0].'-'.$from[1];
		$todate = $to[2].'-'.$to[0].'-'.$to[1];

		$startdate = strtotime($fromdate);
		$enddate = strtotime($todate);

		$invoicelist = DB::select('SELECT * from `invoice_system` WHERE UNIX_TIMESTAMP(invoice_date) >= '.$startdate.' AND UNIX_TIMESTAMP(invoice_date) <= '.$enddate.' GROUP BY `client_id`');
		
		$output = '';
		$i=1;

		if(count($invoicelist)){ 
			foreach($invoicelist as $invoice){ 
				
				$client_details = DB::table('cm_clients')->where('client_id', $invoice->client_id)->first();

				$count = DB::select('SELECT * from `invoice_system` WHERE client_id = "'.$client_details->client_id.'" AND UNIX_TIMESTAMP(invoice_date) >= '.$startdate.' AND UNIX_TIMESTAMP(invoice_date) <= '.$enddate.'');


				if($invoice->statement == "No"){
					$textcolor="color:#f00";
				}
				else{
					$textcolor="color:#00751a";
				}
				$output.='
					<tr>
						<td>'.$i.'</td>
						<td><input type="checkbox" name="select_client" class="select_client class_'.$invoice->client_id.'" data-element="'.$invoice->client_id.'" value="'.$invoice->client_id.'"><label>&nbsp</label></td>
						<td align="left" style="'.$textcolor.'">'.$invoice->client_id.'</td>							
						<td align="left" style="'.$textcolor.'">'.$client_details->firstname.'</td>
						<td align="left" style="'.$textcolor.'">'.$client_details->company.'</td>
						<td align="center" style="'.$textcolor.'">'.count($count).'</td>
					</tr>
				';
				$i++;
			}						
		}

		if($i == 1)
	    {
	      $output.='<tr><td colspan="9" align="center">Empty</td></tr>';
	    }
		echo $output;
		
			
	}

	public function import_new_invoice()
	{
		if($_FILES['new_file']['name']!='')
		{
			$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';
			$tmp_name = $_FILES['new_file']['tmp_name'];
			$name=$_FILES['new_file']['name'];
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

					$Invno = $worksheet->getCellByColumnAndRow(0, 1); $Invno = trim($Invno->getValue());
					$incdate = $worksheet->getCellByColumnAndRow(1, 1); $incdate = trim($incdate->getValue());
					$client = $worksheet->getCellByColumnAndRow(2, 1); $client = trim($client->getValue());
					$net = $worksheet->getCellByColumnAndRow(3, 1); $net = trim($net->getValue());
					$vatrate = $worksheet->getCellByColumnAndRow(4, 1); $vatrate = trim($vatrate->getValue());

					
					if($Invno == "Inv No" && $incdate == "Inv Date" && $client == "Client" && $net == "Inv Net" && $vatrate == "VAT Rate")
					{	
						
						$errorrow = array();
						$mainarray = array();
						for ($row = 2; $row <= $height; ++ $row) {
							$invoice_no = $worksheet->getCellByColumnAndRow(0, $row); $invoice_no = trim($invoice_no->getValue());
							if($invoice_no == "" )
							{

							}
							else{
								$check_invoice = DB::table('invoice_system')->where('invoice_number',$invoice_no)->first();
								if(!count($check_invoice))
								{
									$data['invoice_number'] = $invoice_no;
									$invoice_date = $worksheet->getCellByColumnAndRow(1, $row); $invoice_date = trim($invoice_date->getValue());

									


									$client = $worksheet->getCellByColumnAndRow(2, $row); $data['client_id'] = trim($client->getValue());
									$invoice_net = $worksheet->getCellByColumnAndRow(3, $row); $data['inv_net'] = trim($invoice_net->getValue());
									$invoice_vatrate = $worksheet->getCellByColumnAndRow(4, $row); $data['vat_rate'] = trim($invoice_vatrate->getValue());

									$inc_vat_rate = str_replace("%","",$invoice_vatrate);
									$vat_percentage = $inc_vat_rate / 100;
									$data['gross'] = $invoice_net + ($invoice_net * $vat_percentage);

									DB::table('cm_clients')->where('id',$check_invoice->id)->update($data);
								}
								else{
									$data['invoice_number'] = $invoice_no;
									$invoice_date = $worksheet->getCellByColumnAndRow(1, $row); $data['invoice_date'] = trim($invoice_date->getValue());

									echo $data['invoice_date'];
									exit;

									$client = $worksheet->getCellByColumnAndRow(2, $row); $data['client_id'] = trim($client->getValue());
									$invoice_net = $worksheet->getCellByColumnAndRow(3, $row); $data['inv_net'] = trim($invoice_net->getValue());
									$invoice_vatrate = $worksheet->getCellByColumnAndRow(4, $row); $data['vat_rate'] = trim($invoice_vatrate->getValue());

									$inc_vat_rate = str_replace("%","",$invoice_vatrate);
									$vat_percentage = $inc_vat_rate / 100;
									$data['gross'] = $invoice_net + ($invoice_net * $vat_percentage);
									DB::table('cm_clients')->insert($data);
								}
							}
						}
					}
					else{
						return redirect('user/client_management')->with('message', 'Import Failed! Wrong Input File');
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
						return redirect('user/client_management')->with('success_error', $out);
					}
					else{
						return redirect('user/client_management')->with('message', 'Clients Imported successfully.');
					}
				}
				else{
					return redirect('user/client_management?filename='.$name.'&height='.$height.'&round=2&highestrow='.$highestRow.'&import_type_new=1&out='.$out.'');
				}
			}
		}

	}
	public function import_new_clients_one()
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
				$id = $worksheet->getCellByColumnAndRow(0, $row); $id = trim($id->getValue());
				$pemail = $worksheet->getCellByColumnAndRow(9, $row); $pemail = trim($pemail->getValue());
				if($id == "")
				{

				}
				else{
					$check_gbsid = DB::table('cm_clients')->where('client_id',$id)->first();
					if(!count($check_gbsid))
					{
						$data['client_id'] = $id;
						$firstname = $worksheet->getCellByColumnAndRow(1, $row); $data['firstname'] = trim($firstname->getValue());
						$surname = $worksheet->getCellByColumnAndRow(2, $row); $data['surname'] = trim($surname->getValue());
						$company = $worksheet->getCellByColumnAndRow(3, $row); $data['company'] = trim($company->getValue());

						$address1 = $worksheet->getCellByColumnAndRow(4, $row); $data['address1'] = trim($address1->getValue());
						$address2 = $worksheet->getCellByColumnAndRow(5, $row); $data['address2'] = trim($address2->getValue());
						$address3 = $worksheet->getCellByColumnAndRow(6, $row); $data['address3'] = trim($address3->getValue());
						$address4 = $worksheet->getCellByColumnAndRow(7, $row); $data['address4'] = trim($address4->getValue());
						$address5 = $worksheet->getCellByColumnAndRow(8, $row); $data['address5'] = trim($address5->getValue());

						$email = $worksheet->getCellByColumnAndRow(9, $row); $data['email'] = trim($email->getValue());
						$tye = $worksheet->getCellByColumnAndRow(10, $row); $data['tye'] = trim($tye->getValue());
						$active = $worksheet->getCellByColumnAndRow(11, $row); $active = trim($active->getValue());

						if($active == 'N' || $active == 'n' || $active == "no" || $active == "No")
						{
							$data['active'] = 2;
						}
						else{
							$data['active'] = 1;
						}

						$tax_reg1 = $worksheet->getCellByColumnAndRow(12, $row); $data['tax_reg1'] = trim($tax_reg1->getValue());
						$tax_reg2 = $worksheet->getCellByColumnAndRow(13, $row); $data['tax_reg2'] = trim($tax_reg2->getValue());
						$tax_reg3 = $worksheet->getCellByColumnAndRow(14, $row); $data['tax_reg3'] = trim($tax_reg3->getValue());

						$semail = $worksheet->getCellByColumnAndRow(15, $row); $data['email2'] = trim($semail->getValue());
						$phone = $worksheet->getCellByColumnAndRow(16, $row); $data['phone'] = trim($phone->getValue());
						$linkcode = $worksheet->getCellByColumnAndRow(17, $row); $data['linkcode'] = trim($linkcode->getValue());
						$cro = $worksheet->getCellByColumnAndRow(18, $row); $data['cro'] = trim($cro->getValue());

						$trade_status = $worksheet->getCellByColumnAndRow(19, $row); $data['trade_status'] = trim($trade_status->getValue());
						$directory = $worksheet->getCellByColumnAndRow(21, $row); $data['directory'] = trim($directory->getValue());

						DB::table('cm_clients')->insert($data);
					}
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
				return redirect('user/client_management')->with('success_error', $out);
			}
			else{
				return redirect('user/client_management')->with('message', 'Clients Imported successfully.');
			}
		}
		else{
			return redirect('user/client_management?filename='.$name.'&height='.$height.'&round='.$nextround.'&highestrow='.$highestRow.'&out='.$out.'&import_type_new=1');
		}
	}

	
}

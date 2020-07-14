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



		date_default_timezone_set("Europe/Dublin");

		require_once(app_path('Http/helpers.php'));

	}







	/**



	 * Show the application welcome screen to the user.



	 *



	 * @return Response



	 */



	public function invoicemanagement()
	{

		$invoice = DB::table('invoice_system')->select('id', 'invoice_number', 'invoice_date', 'client_id', 'inv_net', 'vat_rate','vat_value', 'gross', 'status', 'statement')->orderBy('id','asc')->get();	








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


							if(count($client_details))
							{
								if($client_details->company !== "")
	                          {
	                            $company = $client_details->company;
	                          }
	                          else{
	                            $company = $client_details->firstname.' & '.$client_details->surname;
	                          }
							}
							else{
								$company = '';
							}
							






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



									<td align="left" style="'.$textcolor.'"><spam style="display:none">'.strtotime($invoice->invoice_date).'</spam>'.date('d-M-Y', strtotime($invoice->invoice_date)).'</td>



									<td align="left" style="'.$textcolor.'">'.$invoice->client_id.'</td>



									<td align="left" style="'.$textcolor.'">'.$company.'</td>



									<td align="right" style="'.$textcolor.'">'.number_format_invoice($invoice->inv_net).'</td>



									<td align="right" style="'.$textcolor.'">'.number_format_invoice($invoice->vat_value).'</td>



									<td align="right" style="'.$textcolor.'">'.number_format_invoice($invoice->gross).'</td>



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



			$invoicelist = DB::select('SELECT *,Replace('.$select.', ",", "") AS netval from `invoice_system` WHERE Replace('.$select.', ",", "") LIKE "%'.$input.'%" OR '.$select.' LIKE "%'.$input.'%"');







			$output = '';







			$i=1;



			if(count($invoicelist)){ 



				foreach($invoicelist as $key => $invoice){ 



					$client_details = DB::table('cm_clients')->where('client_id', $invoice->client_id)->first();

					if(count($client_details))
					{
						if($client_details->company !== "")
                      {
                        $company = $client_details->company;
                      }
                      else{
                        $company = $client_details->firstname.' & '.$client_details->surname;
                      }
					}
					else{
						$company = '';
					}

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



							<td align="left" style="'.$textcolor.'"><spam style="display:none">'.strtotime($invoice->invoice_date).'</spam>'.date('d-M-Y', strtotime($invoice->invoice_date)).'</td>



							<td align="left" style="'.$textcolor.'">'.$invoice->client_id.'</td>



							<td align="left" style="'.$textcolor.'">'.$company.'</td>



							<td align="right" style="'.$textcolor.'">'.number_format_invoice($invoice->inv_net).'</td>



							<td align="right" style="'.$textcolor.'">'.number_format_invoice($invoice->vat_value).'</td>



							<td align="right" style="'.$textcolor.'">'.number_format_invoice($invoice->gross).'</td>



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



	public function invoicemanagement_paginate()



	{



		$page = Input::get('page');



		$prev_page = $page - 1;



		$offset = $prev_page * 1000;







		$invoicelist = DB::table('invoice_system')->select('id', 'invoice_number', 'invoice_date', 'client_id', 'inv_net', 'vat_rate', 'vat_value', 'gross', 'status', 'statement')->offset($offset)->limit(1000)->get();	







		$output ='';



		$i = $offset+1;



		if(count($invoicelist)){ 



				foreach($invoicelist as $key => $invoice){ 



					if($invoice->client_id != "")



					{



						$client_details = DB::table('cm_clients')->where('client_id', $invoice->client_id)->first();



						if(count($client_details))



						{



							if($client_details->company !== "")
		                  {
		                    $company = $client_details->company;
		                  }
		                  else{
		                    $company = $client_details->firstname.' & '.$client_details->surname;
		                  }



						}



						else{



							$company = '';



						}



					}



					else{



						$company = '';



					}



					



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





	                  if($key == 999) { $class_load = 'class="load_more"'; } else { $class_load = ''; }

					$output.='







						<tr>



                                <td>'.$i.'</td>



                                <td align="left" style="'.$textcolor.'">'.$invoice->invoice_number.'</td>



                                <td align="left" style="'.$textcolor.'"><spam style="display:none">'.strtotime($invoice->invoice_date).'</spam>'.date('d-M-Y',strtotime($invoice->invoice_date)).'</td>



                                <td align="left" style="'.$textcolor.'">'.$invoice->client_id.'</td>



                                <td align="left" '.$class_load.' style="'.$textcolor.'">'.$company.'</td>



                                <td align="right" style="'.$textcolor.'">'.number_format_invoice($invoice->inv_net).'</td>



                                <td align="right" style="'.$textcolor.'">'.number_format_invoice($invoice->vat_value).'</td>



                                <td align="right" style="'.$textcolor.'">'.number_format_invoice($invoice->gross).'</td>



                                <td align="left" style="'.$textcolor.'">'.$statementtext.'</td>



                                



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



					if(count($client_details))



					{



						if($client_details->company !== "")
                      {
                        $companyname = $client_details->company;
                      }
                      else{
                        $companyname = $client_details->firstname.' & '.$client_details->surname;
                      }



					}



					else{



						$companyname = '';



					}







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



							<td align="left" style="'.$textcolor.'"><spam style="display:none">'.strtotime($invoice->invoice_date).'</spam>'.date('d-M-Y', strtotime($invoice->invoice_date)).'</td>



							<td align="left" style="'.$textcolor.'">'.$invoice->client_id.'</td>



							<td align="left" style="'.$textcolor.'">'.$companyname.'</td>



							<td align="right" style="'.$textcolor.'">'.number_format_invoice($invoice->inv_net).'</td>



							<td align="right" style="'.$textcolor.'">'.number_format_invoice($invoice->vat_value).'</td>



							<td align="right" style="'.$textcolor.'">'.number_format_invoice($invoice->gross).'</td>



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







					if(count($client_details))



					{



						if($client_details->company !== "")
                      {
                        $companyname = $client_details->company;
                      }
                      else{
                        $companyname = $client_details->firstname.' & '.$client_details->surname;
                      }



					}



					else{



						$companyname = '';



					}











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



							<td align="left" style="'.$textcolor.'"><spam style="display:none">'.strtotime($invoice->invoice_date).'</spam>'.date('d-M-Y', strtotime($invoice->invoice_date)).'</td>



							<td align="left" style="'.$textcolor.'">'.$invoice->client_id.'</td>



							<td align="left" style="'.$textcolor.'">'.$companyname.'</td>



							<td align="right" style="'.$textcolor.'">'.number_format_invoice($invoice->inv_net).'</td>



							<td align="right" style="'.$textcolor.'">'.number_format_invoice($invoice->vat_value).'</td>



							<td align="right" style="'.$textcolor.'">'.number_format_invoice($invoice->gross).'</td>



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







			



			$invoicelist = DB::table('invoice_system')->select('id', 'invoice_number', 'invoice_date', 'client_id', 'inv_net', 'vat_rate','vat_value', 'gross', 'status', 'statement')->groupBy('client_id')->get();







			$output = '';



			$i=1;







			if(count($invoicelist)){ 



				foreach($invoicelist as $invoice){ 



					$client_details = DB::table('cm_clients')->where('client_id', $invoice->client_id)->first();











					if(count($client_details))



					{



						$count = DB::table('invoice_system')->where('client_id', $client_details->client_id)->get();

						if($client_details->company !== "")
	                      {
	                        $company = $client_details->company;
	                      }
	                      else{
	                        $company = $client_details->firstname.' & '.$client_details->surname;
	                      }



						$clientname = $client_details->firstname;



					}



					else{



						$count = array();



						$company = '';



						$clientname = '';



					}











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



							<td align="left" style="'.$textcolor.'">'.$clientname.'</td>



							<td align="left" style="'.$textcolor.'">'.$company.'</td>



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







			$invoice = DB::select('SELECT `id`, `invoice_number`, `invoice_date`, `client_id`, `inv_net`, `vat_rate`,`vat_value`, `gross`, `status`, `statement`,UNIX_TIMESTAMP(`invoice_date`) as inc_date from `invoice_system` WHERE UNIX_TIMESTAMP(invoice_date) >= '.$startdate.' AND UNIX_TIMESTAMP(invoice_date) <= '.$enddate.' AND client_id IN ('.$quoted_id.') ORDER BY client_id,inc_date DESC');



		}



		else{



			$invoice = DB::select('SELECT `id`, `invoice_number`, `invoice_date`, `client_id`, `inv_net`, `vat_rate`,`vat_value`, `gross`, `status`, `statement`,UNIX_TIMESTAMP(`invoice_date`) as inc_date from `invoice_system` WHERE client_id IN ('.$quoted_id.') ORDER BY client_id,inc_date DESC');



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


				if($company_details->company !== "")
	              {
	                $company = $company_details->company;
	              }
	              else{
	                $company = $company_details->firstname.' & '.$company_details->surname;
	              }




				







		      	$columns_2 = array($i, $single->invoice_number, date('d-M-Y', strtotime($single->invoice_date)), $single->client_id, $company, number_format_invoice($single->inv_net), $single->vat_value,  number_format_invoice($single->gross) );



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







			$invoicelist = DB::select('SELECT `id`, `invoice_number`, `invoice_date`, `client_id`, `inv_net`, `vat_rate`,`vat_value`, `gross`, `status`, `statement`,UNIX_TIMESTAMP(`invoice_date`) as inc_date from `invoice_system` WHERE UNIX_TIMESTAMP(invoice_date) >= '.$startdate.' AND UNIX_TIMESTAMP(invoice_date) <= '.$enddate.' AND client_id IN ('.$quoted_id.') ORDER BY client_id,inc_date DESC');



		}



		else{



			$invoicelist = DB::select('SELECT `id`, `invoice_number`, `invoice_date`, `client_id`, `inv_net`, `vat_rate`,`vat_value`, `gross`, `status`, `statement`,UNIX_TIMESTAMP(`invoice_date`) as inc_date from `invoice_system` WHERE client_id IN ('.$quoted_id.') ORDER BY client_id,inc_date DESC');



		}



		



		$output = '';



		$i=1;







		if(count($invoicelist)){



				foreach($invoicelist as $key => $invoice)



				{



					$client_details = DB::table('cm_clients')->where('client_id', $invoice->client_id)->first();

					if(count($client_details))
					{
						if($client_details->company !== "")
                      {
                        $company = $client_details->company;
                      }
                      else{
                        $company = $client_details->firstname.' & '.$client_details->surname;
                      }
					}
					else{
						$company = '';
					}

					$output.='<tr>



									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">'.$i.'</td>



									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px" align="left">'.$invoice->invoice_number.'</td>



									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px" align="left">'.date('d-M-Y', strtotime($invoice->invoice_date)).'</td>



									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px" align="left">'.$invoice->client_id.'</td>



									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px" align="left">'.$company.'</td>



									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px" align="right">'.number_format_invoice($invoice->inv_net).'</td>



									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px" align="right">'.number_format_invoice($invoice->vat_value).'</td>



									<td style="border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px" align="right">'.number_format_invoice($invoice->gross).'</td>



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







		$invoicelist = DB::select('SELECT `id`, `invoice_number`, `invoice_date`, `client_id`, `inv_net`, `vat_rate`,`vat_value`, `gross`, `status`, `statement` from `invoice_system` WHERE UNIX_TIMESTAMP(invoice_date) >= '.$startdate.' AND UNIX_TIMESTAMP(invoice_date) <= '.$enddate.' GROUP BY `client_id`');



		



		$output = '';



		$i=1;







		if(count($invoicelist)){ 



			foreach($invoicelist as $invoice){ 



				



				$client_details = DB::table('cm_clients')->where('client_id', $invoice->client_id)->first();

				

				if(count($client_details))



				{

					if($client_details->company !== "")
	              {
	                $company = $client_details->company;
	              }
	              else{
	                $company = $client_details->firstname.' & '.$client_details->surname;
	              }

					$count = DB::select('SELECT `id`, `invoice_number`, `invoice_date`, `client_id`, `inv_net`, `vat_rate`,`vat_value`, `gross`, `status`, `statement` from `invoice_system` WHERE client_id = "'.$client_details->client_id.'" AND UNIX_TIMESTAMP(invoice_date) >= '.$startdate.' AND UNIX_TIMESTAMP(invoice_date) <= '.$enddate.'');



					



					$clientname = $client_details->firstname;



				}



				else{



					$count = array();



					$company = '';



					$clientname = '';



				}







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



						<td align="left" style="'.$textcolor.'">'.$clientname.'</td>



						<td align="left" style="'.$textcolor.'">'.$company.'</td>



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



								if(count($check_invoice))



								{



									$data['invoice_number'] = $invoice_no;



									$invoice_date = $worksheet->getCellByColumnAndRow(1, $row); $invoice_date = trim($invoice_date->getValue());

									$explode_invoice_date = explode("/",$invoice_date);
									if(count($explode_invoice_date) == 3)
									{
										$inc_date = $explode_invoice_date[0].'-'.$explode_invoice_date[1].'-'.$explode_invoice_date[2];
										$data['invoice_date'] = date('Y-m-d',strtotime($inc_date));

									}
									else{
										$data['invoice_date'] = date('Y-m-d',strtotime($invoice_date));
									}

									$client = $worksheet->getCellByColumnAndRow(2, $row); $data['client_id'] = trim($client->getValue());



									$invoice_net = $worksheet->getCellByColumnAndRow(3, $row); $data['inv_net'] = str_replace(',', '', trim($invoice_net->getValue()));



									$invoice_vatrate = $worksheet->getCellByColumnAndRow(4, $row); $invoice_vatrate = trim($invoice_vatrate->getValue());







									$f_row1 = $worksheet->getCellByColumnAndRow(5, $row); $data['f_row1'] = trim($f_row1->getValue());



									$g_row2 = $worksheet->getCellByColumnAndRow(6, $row); $data['g_row2'] = trim($g_row2->getValue());



									$h_row3 = $worksheet->getCellByColumnAndRow(7, $row); $data['h_row3'] = trim($h_row3->getValue());



									$i_row4 = $worksheet->getCellByColumnAndRow(8, $row); $data['i_row4'] = trim($i_row4->getValue());



									$j_row5 = $worksheet->getCellByColumnAndRow(9, $row); $data['j_row5'] = trim($j_row5->getValue());



									$k_row6 = $worksheet->getCellByColumnAndRow(10, $row); $data['k_row6'] = trim($k_row6->getValue());



									$l_row7 = $worksheet->getCellByColumnAndRow(11, $row); $data['l_row7'] = trim($l_row7->getValue());



									$m_row8 = $worksheet->getCellByColumnAndRow(12, $row); $data['m_row8'] = trim($m_row8->getValue());



									$n_row9 = $worksheet->getCellByColumnAndRow(13, $row); $data['n_row9'] = trim($n_row9->getValue());



									$o_row10 = $worksheet->getCellByColumnAndRow(14, $row); $data['o_row10'] = trim($o_row10->getValue());



									$p_row11 = $worksheet->getCellByColumnAndRow(15, $row); $data['p_row11'] = trim($p_row11->getValue());



									$q_row12 = $worksheet->getCellByColumnAndRow(16, $row); $data['q_row12'] = trim($q_row12->getValue());



									$r_row13 = $worksheet->getCellByColumnAndRow(17, $row); $data['r_row13'] = trim($r_row13->getValue());



									$s_row14 = $worksheet->getCellByColumnAndRow(18, $row); $data['s_row14'] = trim($s_row14->getValue());



									$t_row15 = $worksheet->getCellByColumnAndRow(19, $row); $data['t_row15'] = trim($t_row15->getValue());



									$u_row16 = $worksheet->getCellByColumnAndRow(20, $row); $data['u_row16'] = trim($u_row16->getValue());



									$v_row17 = $worksheet->getCellByColumnAndRow(21, $row); $data['v_row17'] = trim($v_row17->getValue());



									$w_row18 = $worksheet->getCellByColumnAndRow(22, $row); $data['w_row18'] = trim($w_row18->getValue());



									$x_row19 = $worksheet->getCellByColumnAndRow(23, $row); $data['x_row19'] = trim($x_row19->getValue());



									$y_row20 = $worksheet->getCellByColumnAndRow(24, $row); $data['y_row20'] = trim($y_row20->getValue());







									$z_row1 = $worksheet->getCellByColumnAndRow(25, $row); $data['z_row1'] = trim($z_row1->getValue());



									$aa_row2 = $worksheet->getCellByColumnAndRow(26, $row); $data['aa_row2'] = trim($aa_row2->getValue());



									$ab_row3 = $worksheet->getCellByColumnAndRow(27, $row); $data['ab_row3'] = trim($ab_row3->getValue());



									$ac_row4 = $worksheet->getCellByColumnAndRow(28, $row); $data['ac_row4'] = trim($ac_row4->getValue());



									$ad_row5 = $worksheet->getCellByColumnAndRow(29, $row); $data['ad_row5'] = trim($ad_row5->getValue());



									$ae_row6 = $worksheet->getCellByColumnAndRow(30, $row); $data['ae_row6'] = trim($ae_row6->getValue());



									$af_row7 = $worksheet->getCellByColumnAndRow(31, $row); $data['af_row7'] = trim($af_row7->getValue());



									$ag_row8 = $worksheet->getCellByColumnAndRow(32, $row); $data['ag_row8'] = trim($ag_row8->getValue());



									$ah_row9 = $worksheet->getCellByColumnAndRow(33, $row); $data['ah_row9'] = trim($ah_row9->getValue());



									$ai_row10 = $worksheet->getCellByColumnAndRow(34, $row); $data['ai_row10'] = trim($ai_row10->getValue());



									$aj_row11 = $worksheet->getCellByColumnAndRow(35, $row); $data['aj_row11'] = trim($aj_row11->getValue());



									$ak_row12 = $worksheet->getCellByColumnAndRow(36, $row); $data['ak_row12'] = trim($ak_row12->getValue());



									$al_row13 = $worksheet->getCellByColumnAndRow(37, $row); $data['al_row13'] = trim($al_row13->getValue());



									$am_row14 = $worksheet->getCellByColumnAndRow(38, $row); $data['am_row14'] = trim($am_row14->getValue());



									$an_row15 = $worksheet->getCellByColumnAndRow(39, $row); $data['an_row15'] = trim($an_row15->getValue());



									$ao_row16 = $worksheet->getCellByColumnAndRow(40, $row); $data['ao_row16'] = trim($ao_row16->getValue());



									$ap_row17 = $worksheet->getCellByColumnAndRow(41, $row); $data['ap_row17'] = trim($ap_row17->getValue());



									$aq_row18 = $worksheet->getCellByColumnAndRow(42, $row); $data['aq_row18'] = trim($aq_row18->getValue());



									$ar_row19 = $worksheet->getCellByColumnAndRow(43, $row); $data['ar_row19'] = trim($ar_row19->getValue());



									$as_row20 = $worksheet->getCellByColumnAndRow(44, $row); $data['as_row20'] = trim($as_row20->getValue());











									$at_row1 = $worksheet->getCellByColumnAndRow(45, $row); $data['at_row1'] = trim($at_row1->getValue());



									$au_row2 = $worksheet->getCellByColumnAndRow(46, $row); $data['au_row2'] = trim($au_row2->getValue());



									$av_row3 = $worksheet->getCellByColumnAndRow(47, $row); $data['av_row3'] = trim($av_row3->getValue());



									$aw_row4 = $worksheet->getCellByColumnAndRow(48, $row); $data['aw_row4'] = trim($aw_row4->getValue());



									$ax_row5 = $worksheet->getCellByColumnAndRow(49, $row); $data['ax_row5'] = trim($ax_row5->getValue());



									$ay_row6 = $worksheet->getCellByColumnAndRow(50, $row); $data['ay_row6'] = trim($ay_row6->getValue());



									$az_row7 = $worksheet->getCellByColumnAndRow(51, $row); $data['az_row7'] = trim($az_row7->getValue());



									$ba_row8 = $worksheet->getCellByColumnAndRow(52, $row); $data['ba_row8'] = trim($ba_row8->getValue());



									$bb_row9 = $worksheet->getCellByColumnAndRow(53, $row); $data['bb_row9'] = trim($bb_row9->getValue());



									$bc_row10 = $worksheet->getCellByColumnAndRow(54, $row); $data['bc_row10'] = trim($bc_row10->getValue());



									$bd_row11 = $worksheet->getCellByColumnAndRow(55, $row); $data['bd_row11'] = trim($bd_row11->getValue());



									$be_row12 = $worksheet->getCellByColumnAndRow(56, $row); $data['be_row12'] = trim($be_row12->getValue());



									$bf_row13 = $worksheet->getCellByColumnAndRow(57, $row); $data['bf_row13'] = trim($bf_row13->getValue());



									$bg_row14 = $worksheet->getCellByColumnAndRow(58, $row); $data['bg_row14'] = trim($bg_row14->getValue());



									$bh_row15 = $worksheet->getCellByColumnAndRow(59, $row); $data['bh_row15'] = trim($bh_row15->getValue());



									$bi_row16 = $worksheet->getCellByColumnAndRow(60, $row); $data['bi_row16'] = trim($bi_row16->getValue());



									$bj_row17 = $worksheet->getCellByColumnAndRow(61, $row); $data['bj_row17'] = trim($bj_row17->getValue());



									$bk_row18 = $worksheet->getCellByColumnAndRow(62, $row); $data['bk_row18'] = trim($bk_row18->getValue());



									$bl_row19 = $worksheet->getCellByColumnAndRow(63, $row); $data['bl_row19'] = trim($bl_row19->getValue());



									$bm_row20 = $worksheet->getCellByColumnAndRow(64, $row); $data['bm_row20'] = trim($bm_row20->getValue());







									$bn_row1 = $worksheet->getCellByColumnAndRow(65, $row); $data['bn_row1'] = trim($bn_row1->getValue());



									$bo_row2 = $worksheet->getCellByColumnAndRow(66, $row); $data['bo_row2'] = trim($bo_row2->getValue());



									$bp_row3 = $worksheet->getCellByColumnAndRow(67, $row); $data['bp_row3'] = trim($bp_row3->getValue());



									$bq_row4 = $worksheet->getCellByColumnAndRow(68, $row); $data['bq_row4'] = trim($bq_row4->getValue());



									$br_row5 = $worksheet->getCellByColumnAndRow(69, $row); $data['br_row5'] = trim($br_row5->getValue());



									$bs_row6 = $worksheet->getCellByColumnAndRow(70, $row); $data['bs_row6'] = trim($bs_row6->getValue());



									$bt_row7 = $worksheet->getCellByColumnAndRow(71, $row); $data['bt_row7'] = trim($bt_row7->getValue());



									$bu_row8 = $worksheet->getCellByColumnAndRow(72, $row); $data['bu_row8'] = trim($bu_row8->getValue());



									$bv_row9 = $worksheet->getCellByColumnAndRow(73, $row); $data['bv_row9'] = trim($bv_row9->getValue());



									$bw_row10 = $worksheet->getCellByColumnAndRow(74, $row); $data['bw_row10'] = trim($bw_row10->getValue());



									$bx_row11 = $worksheet->getCellByColumnAndRow(75, $row); $data['bx_row11'] = trim($bx_row11->getValue());



									$by_row12 = $worksheet->getCellByColumnAndRow(76, $row); $data['by_row12'] = trim($by_row12->getValue());



									$bz_row13 = $worksheet->getCellByColumnAndRow(77, $row); $data['bz_row13'] = trim($bz_row13->getValue());



									$ca_row14 = $worksheet->getCellByColumnAndRow(78, $row); $data['ca_row14'] = trim($ca_row14->getValue());



									$cb_row15 = $worksheet->getCellByColumnAndRow(79, $row); $data['cb_row15'] = trim($cb_row15->getValue());



									$cc_row16 = $worksheet->getCellByColumnAndRow(80, $row); $data['cc_row16'] = trim($cc_row16->getValue());



									$cd_row17 = $worksheet->getCellByColumnAndRow(81, $row); $data['cd_row17'] = trim($cd_row17->getValue());



									$ce_row18 = $worksheet->getCellByColumnAndRow(82, $row); $data['ce_row18'] = trim($ce_row18->getValue());



									$cf_row19 = $worksheet->getCellByColumnAndRow(83, $row); $data['cf_row19'] = trim($cf_row19->getValue());



									$cg_row20 = $worksheet->getCellByColumnAndRow(84, $row); $data['cg_row20'] = trim($cg_row20->getValue());







									$ch_invoice_number = $worksheet->getCellByColumnAndRow(85, $row); $data['ch_invoice_number'] = trim($ch_invoice_number->getValue());



									$ci_year_end = $worksheet->getCellByColumnAndRow(86, $row); $data['ci_year_end'] = trim($ci_year_end->getValue());



									$cj1 = $worksheet->getCellByColumnAndRow(87, $row); $data['cj1'] = trim($cj1->getValue());



									$ck2 = $worksheet->getCellByColumnAndRow(88, $row); $data['ck2'] = trim($ck2->getValue());



									$cl3 = $worksheet->getCellByColumnAndRow(89, $row); $data['cl3'] = trim($cl3->getValue());



									$cm4 = $worksheet->getCellByColumnAndRow(90, $row); $data['cm4'] = trim($cm4->getValue());



									$cn5 = $worksheet->getCellByColumnAndRow(91, $row); $data['cn5'] = trim($cn5->getValue());



									$co_abridgedinc = $worksheet->getCellByColumnAndRow(92, $row); $data['co_abridgedinc'] = trim($co_abridgedinc->getValue());



									$cp_sor = $worksheet->getCellByColumnAndRow(93, $row); $data['cp_sor'] = trim($cp_sor->getValue());



									$cq_adjnote = $worksheet->getCellByColumnAndRow(94, $row); $data['cq_adjnote'] = trim($cq_adjnote->getValue());







									$cr_position = $worksheet->getCellByColumnAndRow(95, $row); $data['cr_position'] = trim($cr_position->getValue());



									$cs_liability = $worksheet->getCellByColumnAndRow(96, $row); $data['cs_liability'] = trim($cs_liability->getValue());



									$ct_prelim = $worksheet->getCellByColumnAndRow(97, $row); $data['ct_prelim'] = trim($ct_prelim->getValue());



									$cu_paydate = $worksheet->getCellByColumnAndRow(98, $row); $data['cu_paydate'] = trim($cu_paydate->getValue());



									$cv_included = $worksheet->getCellByColumnAndRow(99, $row); $data['cv_included'] = trim($cv_included->getValue());



									$cw_liability = $worksheet->getCellByColumnAndRow(100, $row); $data['cw_liability'] = trim($cw_liability->getValue());



									$cx_prelim = $worksheet->getCellByColumnAndRow(101, $row); $data['cx_prelim'] = trim($cx_prelim->getValue());



									$cy_paydate = $worksheet->getCellByColumnAndRow(102, $row); $data['cy_paydate'] = trim($cy_paydate->getValue());



									$cz_invoice = $worksheet->getCellByColumnAndRow(103, $row); $data['cz_invoice'] = trim($cz_invoice->getValue());



									$da_blank1 = $worksheet->getCellByColumnAndRow(104, $row); $data['da_blank1'] = trim($da_blank1->getValue());



									$db_blank2 = $worksheet->getCellByColumnAndRow(105, $row); $data['db_blank2'] = trim($db_blank2->getValue());



									$dc_blank3 = $worksheet->getCellByColumnAndRow(106, $row); $data['dc_blank3'] = trim($dc_blank3->getValue());



									$statement = $worksheet->getCellByColumnAndRow(107, $row); $data['statement'] = trim($statement->getValue());


									$inc_vat_rate = str_replace("%","",$invoice_vatrate);



									$data['vat_rate'] = $inc_vat_rate;



									$vat_percentage = $inc_vat_rate / 100;


									$data['vat_value'] = $data['inv_net'] * $vat_percentage;
									$data['gross'] = $data['inv_net'] + ($data['inv_net'] * $vat_percentage);



									$db_id = $check_invoice->id;



									DB::table('invoice_system')->where('id',$db_id)->update($data);



								}



								else{



									$data['invoice_number'] = $invoice_no;



									$invoice_date = $worksheet->getCellByColumnAndRow(1, $row); $invoice_date = trim($invoice_date->getValue());


									$explode_invoice_date = explode("/",$invoice_date);
									if(count($explode_invoice_date) == 3)
									{
										$inc_date = $explode_invoice_date[0].'-'.$explode_invoice_date[1].'-'.$explode_invoice_date[2];
										$data['invoice_date'] = date('Y-m-d',strtotime($inc_date));

									}
									else{
										$data['invoice_date'] = date('Y-m-d',strtotime($invoice_date));
									}



									$client = $worksheet->getCellByColumnAndRow(2, $row); $data['client_id'] = trim($client->getValue());



									$invoice_net = $worksheet->getCellByColumnAndRow(3, $row); $data['inv_net'] = str_replace(',', '', trim($invoice_net->getValue()));



									$invoice_vatrate = $worksheet->getCellByColumnAndRow(4, $row); $invoice_vatrate = trim($invoice_vatrate->getValue());







									$f_row1 = $worksheet->getCellByColumnAndRow(5, $row); $data['f_row1'] = trim($f_row1->getValue());



									$g_row2 = $worksheet->getCellByColumnAndRow(6, $row); $data['g_row2'] = trim($g_row2->getValue());



									$h_row3 = $worksheet->getCellByColumnAndRow(7, $row); $data['h_row3'] = trim($h_row3->getValue());



									$i_row4 = $worksheet->getCellByColumnAndRow(8, $row); $data['i_row4'] = trim($i_row4->getValue());



									$j_row5 = $worksheet->getCellByColumnAndRow(9, $row); $data['j_row5'] = trim($j_row5->getValue());



									$k_row6 = $worksheet->getCellByColumnAndRow(10, $row); $data['k_row6'] = trim($k_row6->getValue());



									$l_row7 = $worksheet->getCellByColumnAndRow(11, $row); $data['l_row7'] = trim($l_row7->getValue());



									$m_row8 = $worksheet->getCellByColumnAndRow(12, $row); $data['m_row8'] = trim($m_row8->getValue());



									$n_row9 = $worksheet->getCellByColumnAndRow(13, $row); $data['n_row9'] = trim($n_row9->getValue());



									$o_row10 = $worksheet->getCellByColumnAndRow(14, $row); $data['o_row10'] = trim($o_row10->getValue());



									$p_row11 = $worksheet->getCellByColumnAndRow(15, $row); $data['p_row11'] = trim($p_row11->getValue());



									$q_row12 = $worksheet->getCellByColumnAndRow(16, $row); $data['q_row12'] = trim($q_row12->getValue());



									$r_row13 = $worksheet->getCellByColumnAndRow(17, $row); $data['r_row13'] = trim($r_row13->getValue());



									$s_row14 = $worksheet->getCellByColumnAndRow(18, $row); $data['s_row14'] = trim($s_row14->getValue());



									$t_row15 = $worksheet->getCellByColumnAndRow(19, $row); $data['t_row15'] = trim($t_row15->getValue());



									$u_row16 = $worksheet->getCellByColumnAndRow(20, $row); $data['u_row16'] = trim($u_row16->getValue());



									$v_row17 = $worksheet->getCellByColumnAndRow(21, $row); $data['v_row17'] = trim($v_row17->getValue());



									$w_row18 = $worksheet->getCellByColumnAndRow(22, $row); $data['w_row18'] = trim($w_row18->getValue());



									$x_row19 = $worksheet->getCellByColumnAndRow(23, $row); $data['x_row19'] = trim($x_row19->getValue());



									$y_row20 = $worksheet->getCellByColumnAndRow(24, $row); $data['y_row20'] = trim($y_row20->getValue());







									$z_row1 = $worksheet->getCellByColumnAndRow(25, $row); $data['z_row1'] = trim($z_row1->getValue());



									$aa_row2 = $worksheet->getCellByColumnAndRow(26, $row); $data['aa_row2'] = trim($aa_row2->getValue());



									$ab_row3 = $worksheet->getCellByColumnAndRow(27, $row); $data['ab_row3'] = trim($ab_row3->getValue());



									$ac_row4 = $worksheet->getCellByColumnAndRow(28, $row); $data['ac_row4'] = trim($ac_row4->getValue());



									$ad_row5 = $worksheet->getCellByColumnAndRow(29, $row); $data['ad_row5'] = trim($ad_row5->getValue());



									$ae_row6 = $worksheet->getCellByColumnAndRow(30, $row); $data['ae_row6'] = trim($ae_row6->getValue());



									$af_row7 = $worksheet->getCellByColumnAndRow(31, $row); $data['af_row7'] = trim($af_row7->getValue());



									$ag_row8 = $worksheet->getCellByColumnAndRow(32, $row); $data['ag_row8'] = trim($ag_row8->getValue());



									$ah_row9 = $worksheet->getCellByColumnAndRow(33, $row); $data['ah_row9'] = trim($ah_row9->getValue());



									$ai_row10 = $worksheet->getCellByColumnAndRow(34, $row); $data['ai_row10'] = trim($ai_row10->getValue());



									$aj_row11 = $worksheet->getCellByColumnAndRow(35, $row); $data['aj_row11'] = trim($aj_row11->getValue());



									$ak_row12 = $worksheet->getCellByColumnAndRow(36, $row); $data['ak_row12'] = trim($ak_row12->getValue());



									$al_row13 = $worksheet->getCellByColumnAndRow(37, $row); $data['al_row13'] = trim($al_row13->getValue());



									$am_row14 = $worksheet->getCellByColumnAndRow(38, $row); $data['am_row14'] = trim($am_row14->getValue());



									$an_row15 = $worksheet->getCellByColumnAndRow(39, $row); $data['an_row15'] = trim($an_row15->getValue());



									$ao_row16 = $worksheet->getCellByColumnAndRow(40, $row); $data['ao_row16'] = trim($ao_row16->getValue());



									$ap_row17 = $worksheet->getCellByColumnAndRow(41, $row); $data['ap_row17'] = trim($ap_row17->getValue());



									$aq_row18 = $worksheet->getCellByColumnAndRow(42, $row); $data['aq_row18'] = trim($aq_row18->getValue());



									$ar_row19 = $worksheet->getCellByColumnAndRow(43, $row); $data['ar_row19'] = trim($ar_row19->getValue());



									$as_row20 = $worksheet->getCellByColumnAndRow(44, $row); $data['as_row20'] = trim($as_row20->getValue());











									$at_row1 = $worksheet->getCellByColumnAndRow(45, $row); $data['at_row1'] = trim($at_row1->getValue());



									$au_row2 = $worksheet->getCellByColumnAndRow(46, $row); $data['au_row2'] = trim($au_row2->getValue());



									$av_row3 = $worksheet->getCellByColumnAndRow(47, $row); $data['av_row3'] = trim($av_row3->getValue());



									$aw_row4 = $worksheet->getCellByColumnAndRow(48, $row); $data['aw_row4'] = trim($aw_row4->getValue());



									$ax_row5 = $worksheet->getCellByColumnAndRow(49, $row); $data['ax_row5'] = trim($ax_row5->getValue());



									$ay_row6 = $worksheet->getCellByColumnAndRow(50, $row); $data['ay_row6'] = trim($ay_row6->getValue());



									$az_row7 = $worksheet->getCellByColumnAndRow(51, $row); $data['az_row7'] = trim($az_row7->getValue());



									$ba_row8 = $worksheet->getCellByColumnAndRow(52, $row); $data['ba_row8'] = trim($ba_row8->getValue());



									$bb_row9 = $worksheet->getCellByColumnAndRow(53, $row); $data['bb_row9'] = trim($bb_row9->getValue());



									$bc_row10 = $worksheet->getCellByColumnAndRow(54, $row); $data['bc_row10'] = trim($bc_row10->getValue());



									$bd_row11 = $worksheet->getCellByColumnAndRow(55, $row); $data['bd_row11'] = trim($bd_row11->getValue());



									$be_row12 = $worksheet->getCellByColumnAndRow(56, $row); $data['be_row12'] = trim($be_row12->getValue());



									$bf_row13 = $worksheet->getCellByColumnAndRow(57, $row); $data['bf_row13'] = trim($bf_row13->getValue());



									$bg_row14 = $worksheet->getCellByColumnAndRow(58, $row); $data['bg_row14'] = trim($bg_row14->getValue());



									$bh_row15 = $worksheet->getCellByColumnAndRow(59, $row); $data['bh_row15'] = trim($bh_row15->getValue());



									$bi_row16 = $worksheet->getCellByColumnAndRow(60, $row); $data['bi_row16'] = trim($bi_row16->getValue());



									$bj_row17 = $worksheet->getCellByColumnAndRow(61, $row); $data['bj_row17'] = trim($bj_row17->getValue());



									$bk_row18 = $worksheet->getCellByColumnAndRow(62, $row); $data['bk_row18'] = trim($bk_row18->getValue());



									$bl_row19 = $worksheet->getCellByColumnAndRow(63, $row); $data['bl_row19'] = trim($bl_row19->getValue());



									$bm_row20 = $worksheet->getCellByColumnAndRow(64, $row); $data['bm_row20'] = trim($bm_row20->getValue());







									$bn_row1 = $worksheet->getCellByColumnAndRow(65, $row); $data['bn_row1'] = trim($bn_row1->getValue());



									$bo_row2 = $worksheet->getCellByColumnAndRow(66, $row); $data['bo_row2'] = trim($bo_row2->getValue());



									$bp_row3 = $worksheet->getCellByColumnAndRow(67, $row); $data['bp_row3'] = trim($bp_row3->getValue());



									$bq_row4 = $worksheet->getCellByColumnAndRow(68, $row); $data['bq_row4'] = trim($bq_row4->getValue());



									$br_row5 = $worksheet->getCellByColumnAndRow(69, $row); $data['br_row5'] = trim($br_row5->getValue());



									$bs_row6 = $worksheet->getCellByColumnAndRow(70, $row); $data['bs_row6'] = trim($bs_row6->getValue());



									$bt_row7 = $worksheet->getCellByColumnAndRow(71, $row); $data['bt_row7'] = trim($bt_row7->getValue());



									$bu_row8 = $worksheet->getCellByColumnAndRow(72, $row); $data['bu_row8'] = trim($bu_row8->getValue());



									$bv_row9 = $worksheet->getCellByColumnAndRow(73, $row); $data['bv_row9'] = trim($bv_row9->getValue());



									$bw_row10 = $worksheet->getCellByColumnAndRow(74, $row); $data['bw_row10'] = trim($bw_row10->getValue());



									$bx_row11 = $worksheet->getCellByColumnAndRow(75, $row); $data['bx_row11'] = trim($bx_row11->getValue());



									$by_row12 = $worksheet->getCellByColumnAndRow(76, $row); $data['by_row12'] = trim($by_row12->getValue());



									$bz_row13 = $worksheet->getCellByColumnAndRow(77, $row); $data['bz_row13'] = trim($bz_row13->getValue());



									$ca_row14 = $worksheet->getCellByColumnAndRow(78, $row); $data['ca_row14'] = trim($ca_row14->getValue());



									$cb_row15 = $worksheet->getCellByColumnAndRow(79, $row); $data['cb_row15'] = trim($cb_row15->getValue());



									$cc_row16 = $worksheet->getCellByColumnAndRow(80, $row); $data['cc_row16'] = trim($cc_row16->getValue());



									$cd_row17 = $worksheet->getCellByColumnAndRow(81, $row); $data['cd_row17'] = trim($cd_row17->getValue());



									$ce_row18 = $worksheet->getCellByColumnAndRow(82, $row); $data['ce_row18'] = trim($ce_row18->getValue());



									$cf_row19 = $worksheet->getCellByColumnAndRow(83, $row); $data['cf_row19'] = trim($cf_row19->getValue());



									$cg_row20 = $worksheet->getCellByColumnAndRow(84, $row); $data['cg_row20'] = trim($cg_row20->getValue());







									$ch_invoice_number = $worksheet->getCellByColumnAndRow(85, $row); $data['ch_invoice_number'] = trim($ch_invoice_number->getValue());



									$ci_year_end = $worksheet->getCellByColumnAndRow(86, $row); $data['ci_year_end'] = trim($ci_year_end->getValue());



									$cj1 = $worksheet->getCellByColumnAndRow(87, $row); $data['cj1'] = trim($cj1->getValue());



									$ck2 = $worksheet->getCellByColumnAndRow(88, $row); $data['ck2'] = trim($ck2->getValue());



									$cl3 = $worksheet->getCellByColumnAndRow(89, $row); $data['cl3'] = trim($cl3->getValue());



									$cm4 = $worksheet->getCellByColumnAndRow(90, $row); $data['cm4'] = trim($cm4->getValue());



									$cn5 = $worksheet->getCellByColumnAndRow(91, $row); $data['cn5'] = trim($cn5->getValue());



									$co_abridgedinc = $worksheet->getCellByColumnAndRow(92, $row); $data['co_abridgedinc'] = trim($co_abridgedinc->getValue());



									$cp_sor = $worksheet->getCellByColumnAndRow(93, $row); $data['cp_sor'] = trim($cp_sor->getValue());



									$cq_adjnote = $worksheet->getCellByColumnAndRow(94, $row); $data['cq_adjnote'] = trim($cq_adjnote->getValue());







									$cr_position = $worksheet->getCellByColumnAndRow(95, $row); $data['cr_position'] = trim($cr_position->getValue());



									$cs_liability = $worksheet->getCellByColumnAndRow(96, $row); $data['cs_liability'] = trim($cs_liability->getValue());



									$ct_prelim = $worksheet->getCellByColumnAndRow(97, $row); $data['ct_prelim'] = trim($ct_prelim->getValue());



									$cu_paydate = $worksheet->getCellByColumnAndRow(98, $row); $data['cu_paydate'] = trim($cu_paydate->getValue());



									$cv_included = $worksheet->getCellByColumnAndRow(99, $row); $data['cv_included'] = trim($cv_included->getValue());



									$cw_liability = $worksheet->getCellByColumnAndRow(100, $row); $data['cw_liability'] = trim($cw_liability->getValue());



									$cx_prelim = $worksheet->getCellByColumnAndRow(101, $row); $data['cx_prelim'] = trim($cx_prelim->getValue());



									$cy_paydate = $worksheet->getCellByColumnAndRow(102, $row); $data['cy_paydate'] = trim($cy_paydate->getValue());



									$cz_invoice = $worksheet->getCellByColumnAndRow(103, $row); $data['cz_invoice'] = trim($cz_invoice->getValue());



									$da_blank1 = $worksheet->getCellByColumnAndRow(104, $row); $data['da_blank1'] = trim($da_blank1->getValue());



									$db_blank2 = $worksheet->getCellByColumnAndRow(105, $row); $data['db_blank2'] = trim($db_blank2->getValue());



									$dc_blank3 = $worksheet->getCellByColumnAndRow(106, $row); $data['dc_blank3'] = trim($dc_blank3->getValue());



									$statement = $worksheet->getCellByColumnAndRow(107, $row); $data['statement'] = trim($statement->getValue());







									







									$inc_vat_rate = str_replace("%","",$invoice_vatrate);



									$data['vat_rate'] = $inc_vat_rate;



									$vat_percentage = $inc_vat_rate / 100;


									$data['vat_value'] = $data['inv_net'] * $vat_percentage;
									$data['gross'] = $data['inv_net'] + ($data['inv_net'] * $vat_percentage);



									DB::table('invoice_system')->insert($data);



								}



							}



						}



					}



					else{



						return redirect('user/invoice_management')->with('message', 'Import Failed! Invalid Import File');



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



						return redirect('user/invoice_management')->with('success_error', $out);



					}



					else{



						return redirect('user/invoice_management')->with('message', 'Invoice Imported successfully.');



					}



				}



				else{



					return redirect('user/invoice_management?filename='.$name.'&height='.$height.'&round=2&highestrow='.$highestRow.'&import_type_new=1&out='.$out.'');



				}



			}



		}







	}



	public function import_new_invoice_one()



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



				$invoice_no = $worksheet->getCellByColumnAndRow(0, $row); $invoice_no = trim($invoice_no->getValue());



				if($invoice_no == "" )



				{







				}



				else{



					$check_invoice = DB::table('invoice_system')->where('invoice_number',$invoice_no)->first();



					if(count($check_invoice))



					{



						$data['invoice_number'] = $invoice_no;



						$invoice_date = $worksheet->getCellByColumnAndRow(1, $row); $invoice_date = trim($invoice_date->getValue());




						$explode_invoice_date = explode("/",$invoice_date);
						if(count($explode_invoice_date) == 3)
						{
							$inc_date = $explode_invoice_date[0].'-'.$explode_invoice_date[1].'-'.$explode_invoice_date[2];
							$data['invoice_date'] = date('Y-m-d',strtotime($inc_date));

						}
						else{
							$data['invoice_date'] = date('Y-m-d',strtotime($invoice_date));
						}



						$client = $worksheet->getCellByColumnAndRow(2, $row); $data['client_id'] = trim($client->getValue());



						$invoice_net = $worksheet->getCellByColumnAndRow(3, $row); $data['inv_net'] = str_replace(',', '', trim($invoice_net->getValue()));



						$invoice_vatrate = $worksheet->getCellByColumnAndRow(4, $row); $invoice_vatrate = trim($invoice_vatrate->getValue());











						$f_row1 = $worksheet->getCellByColumnAndRow(5, $row); $data['f_row1'] = trim($f_row1->getValue());



						$g_row2 = $worksheet->getCellByColumnAndRow(6, $row); $data['g_row2'] = trim($g_row2->getValue());



						$h_row3 = $worksheet->getCellByColumnAndRow(7, $row); $data['h_row3'] = trim($h_row3->getValue());



						$i_row4 = $worksheet->getCellByColumnAndRow(8, $row); $data['i_row4'] = trim($i_row4->getValue());



						$j_row5 = $worksheet->getCellByColumnAndRow(9, $row); $data['j_row5'] = trim($j_row5->getValue());



						$k_row6 = $worksheet->getCellByColumnAndRow(10, $row); $data['k_row6'] = trim($k_row6->getValue());



						$l_row7 = $worksheet->getCellByColumnAndRow(11, $row); $data['l_row7'] = trim($l_row7->getValue());



						$m_row8 = $worksheet->getCellByColumnAndRow(12, $row); $data['m_row8'] = trim($m_row8->getValue());



						$n_row9 = $worksheet->getCellByColumnAndRow(13, $row); $data['n_row9'] = trim($n_row9->getValue());



						$o_row10 = $worksheet->getCellByColumnAndRow(14, $row); $data['o_row10'] = trim($o_row10->getValue());



						$p_row11 = $worksheet->getCellByColumnAndRow(15, $row); $data['p_row11'] = trim($p_row11->getValue());



						$q_row12 = $worksheet->getCellByColumnAndRow(16, $row); $data['q_row12'] = trim($q_row12->getValue());



						$r_row13 = $worksheet->getCellByColumnAndRow(17, $row); $data['r_row13'] = trim($r_row13->getValue());



						$s_row14 = $worksheet->getCellByColumnAndRow(18, $row); $data['s_row14'] = trim($s_row14->getValue());



						$t_row15 = $worksheet->getCellByColumnAndRow(19, $row); $data['t_row15'] = trim($t_row15->getValue());



						$u_row16 = $worksheet->getCellByColumnAndRow(20, $row); $data['u_row16'] = trim($u_row16->getValue());



						$v_row17 = $worksheet->getCellByColumnAndRow(21, $row); $data['v_row17'] = trim($v_row17->getValue());



						$w_row18 = $worksheet->getCellByColumnAndRow(22, $row); $data['w_row18'] = trim($w_row18->getValue());



						$x_row19 = $worksheet->getCellByColumnAndRow(23, $row); $data['x_row19'] = trim($x_row19->getValue());



						$y_row20 = $worksheet->getCellByColumnAndRow(24, $row); $data['y_row20'] = trim($y_row20->getValue());







						$z_row1 = $worksheet->getCellByColumnAndRow(25, $row); $data['z_row1'] = trim($z_row1->getValue());



						$aa_row2 = $worksheet->getCellByColumnAndRow(26, $row); $data['aa_row2'] = trim($aa_row2->getValue());



						$ab_row3 = $worksheet->getCellByColumnAndRow(27, $row); $data['ab_row3'] = trim($ab_row3->getValue());



						$ac_row4 = $worksheet->getCellByColumnAndRow(28, $row); $data['ac_row4'] = trim($ac_row4->getValue());



						$ad_row5 = $worksheet->getCellByColumnAndRow(29, $row); $data['ad_row5'] = trim($ad_row5->getValue());



						$ae_row6 = $worksheet->getCellByColumnAndRow(30, $row); $data['ae_row6'] = trim($ae_row6->getValue());



						$af_row7 = $worksheet->getCellByColumnAndRow(31, $row); $data['af_row7'] = trim($af_row7->getValue());



						$ag_row8 = $worksheet->getCellByColumnAndRow(32, $row); $data['ag_row8'] = trim($ag_row8->getValue());



						$ah_row9 = $worksheet->getCellByColumnAndRow(33, $row); $data['ah_row9'] = trim($ah_row9->getValue());



						$ai_row10 = $worksheet->getCellByColumnAndRow(34, $row); $data['ai_row10'] = trim($ai_row10->getValue());



						$aj_row11 = $worksheet->getCellByColumnAndRow(35, $row); $data['aj_row11'] = trim($aj_row11->getValue());



						$ak_row12 = $worksheet->getCellByColumnAndRow(36, $row); $data['ak_row12'] = trim($ak_row12->getValue());



						$al_row13 = $worksheet->getCellByColumnAndRow(37, $row); $data['al_row13'] = trim($al_row13->getValue());



						$am_row14 = $worksheet->getCellByColumnAndRow(38, $row); $data['am_row14'] = trim($am_row14->getValue());



						$an_row15 = $worksheet->getCellByColumnAndRow(39, $row); $data['an_row15'] = trim($an_row15->getValue());



						$ao_row16 = $worksheet->getCellByColumnAndRow(40, $row); $data['ao_row16'] = trim($ao_row16->getValue());



						$ap_row17 = $worksheet->getCellByColumnAndRow(41, $row); $data['ap_row17'] = trim($ap_row17->getValue());



						$aq_row18 = $worksheet->getCellByColumnAndRow(42, $row); $data['aq_row18'] = trim($aq_row18->getValue());



						$ar_row19 = $worksheet->getCellByColumnAndRow(43, $row); $data['ar_row19'] = trim($ar_row19->getValue());



						$as_row20 = $worksheet->getCellByColumnAndRow(44, $row); $data['as_row20'] = trim($as_row20->getValue());











						$at_row1 = $worksheet->getCellByColumnAndRow(45, $row); $data['at_row1'] = trim($at_row1->getValue());



						$au_row2 = $worksheet->getCellByColumnAndRow(46, $row); $data['au_row2'] = trim($au_row2->getValue());



						$av_row3 = $worksheet->getCellByColumnAndRow(47, $row); $data['av_row3'] = trim($av_row3->getValue());



						$aw_row4 = $worksheet->getCellByColumnAndRow(48, $row); $data['aw_row4'] = trim($aw_row4->getValue());



						$ax_row5 = $worksheet->getCellByColumnAndRow(49, $row); $data['ax_row5'] = trim($ax_row5->getValue());



						$ay_row6 = $worksheet->getCellByColumnAndRow(50, $row); $data['ay_row6'] = trim($ay_row6->getValue());



						$az_row7 = $worksheet->getCellByColumnAndRow(51, $row); $data['az_row7'] = trim($az_row7->getValue());



						$ba_row8 = $worksheet->getCellByColumnAndRow(52, $row); $data['ba_row8'] = trim($ba_row8->getValue());



						$bb_row9 = $worksheet->getCellByColumnAndRow(53, $row); $data['bb_row9'] = trim($bb_row9->getValue());



						$bc_row10 = $worksheet->getCellByColumnAndRow(54, $row); $data['bc_row10'] = trim($bc_row10->getValue());



						$bd_row11 = $worksheet->getCellByColumnAndRow(55, $row); $data['bd_row11'] = trim($bd_row11->getValue());



						$be_row12 = $worksheet->getCellByColumnAndRow(56, $row); $data['be_row12'] = trim($be_row12->getValue());



						$bf_row13 = $worksheet->getCellByColumnAndRow(57, $row); $data['bf_row13'] = trim($bf_row13->getValue());



						$bg_row14 = $worksheet->getCellByColumnAndRow(58, $row); $data['bg_row14'] = trim($bg_row14->getValue());



						$bh_row15 = $worksheet->getCellByColumnAndRow(59, $row); $data['bh_row15'] = trim($bh_row15->getValue());



						$bi_row16 = $worksheet->getCellByColumnAndRow(60, $row); $data['bi_row16'] = trim($bi_row16->getValue());



						$bj_row17 = $worksheet->getCellByColumnAndRow(61, $row); $data['bj_row17'] = trim($bj_row17->getValue());



						$bk_row18 = $worksheet->getCellByColumnAndRow(62, $row); $data['bk_row18'] = trim($bk_row18->getValue());



						$bl_row19 = $worksheet->getCellByColumnAndRow(63, $row); $data['bl_row19'] = trim($bl_row19->getValue());



						$bm_row20 = $worksheet->getCellByColumnAndRow(64, $row); $data['bm_row20'] = trim($bm_row20->getValue());







						$bn_row1 = $worksheet->getCellByColumnAndRow(65, $row); $data['bn_row1'] = trim($bn_row1->getValue());



						$bo_row2 = $worksheet->getCellByColumnAndRow(66, $row); $data['bo_row2'] = trim($bo_row2->getValue());



						$bp_row3 = $worksheet->getCellByColumnAndRow(67, $row); $data['bp_row3'] = trim($bp_row3->getValue());



						$bq_row4 = $worksheet->getCellByColumnAndRow(68, $row); $data['bq_row4'] = trim($bq_row4->getValue());



						$br_row5 = $worksheet->getCellByColumnAndRow(69, $row); $data['br_row5'] = trim($br_row5->getValue());



						$bs_row6 = $worksheet->getCellByColumnAndRow(70, $row); $data['bs_row6'] = trim($bs_row6->getValue());



						$bt_row7 = $worksheet->getCellByColumnAndRow(71, $row); $data['bt_row7'] = trim($bt_row7->getValue());



						$bu_row8 = $worksheet->getCellByColumnAndRow(72, $row); $data['bu_row8'] = trim($bu_row8->getValue());



						$bv_row9 = $worksheet->getCellByColumnAndRow(73, $row); $data['bv_row9'] = trim($bv_row9->getValue());



						$bw_row10 = $worksheet->getCellByColumnAndRow(74, $row); $data['bw_row10'] = trim($bw_row10->getValue());



						$bx_row11 = $worksheet->getCellByColumnAndRow(75, $row); $data['bx_row11'] = trim($bx_row11->getValue());



						$by_row12 = $worksheet->getCellByColumnAndRow(76, $row); $data['by_row12'] = trim($by_row12->getValue());



						$bz_row13 = $worksheet->getCellByColumnAndRow(77, $row); $data['bz_row13'] = trim($bz_row13->getValue());



						$ca_row14 = $worksheet->getCellByColumnAndRow(78, $row); $data['ca_row14'] = trim($ca_row14->getValue());



						$cb_row15 = $worksheet->getCellByColumnAndRow(79, $row); $data['cb_row15'] = trim($cb_row15->getValue());



						$cc_row16 = $worksheet->getCellByColumnAndRow(80, $row); $data['cc_row16'] = trim($cc_row16->getValue());



						$cd_row17 = $worksheet->getCellByColumnAndRow(81, $row); $data['cd_row17'] = trim($cd_row17->getValue());



						$ce_row18 = $worksheet->getCellByColumnAndRow(82, $row); $data['ce_row18'] = trim($ce_row18->getValue());



						$cf_row19 = $worksheet->getCellByColumnAndRow(83, $row); $data['cf_row19'] = trim($cf_row19->getValue());



						$cg_row20 = $worksheet->getCellByColumnAndRow(84, $row); $data['cg_row20'] = trim($cg_row20->getValue());







						$ch_invoice_number = $worksheet->getCellByColumnAndRow(85, $row); $data['ch_invoice_number'] = trim($ch_invoice_number->getValue());



						$ci_year_end = $worksheet->getCellByColumnAndRow(86, $row); $data['ci_year_end'] = trim($ci_year_end->getValue());



						$cj1 = $worksheet->getCellByColumnAndRow(87, $row); $data['cj1'] = trim($cj1->getValue());



						$ck2 = $worksheet->getCellByColumnAndRow(88, $row); $data['ck2'] = trim($ck2->getValue());



						$cl3 = $worksheet->getCellByColumnAndRow(89, $row); $data['cl3'] = trim($cl3->getValue());



						$cm4 = $worksheet->getCellByColumnAndRow(90, $row); $data['cm4'] = trim($cm4->getValue());



						$cn5 = $worksheet->getCellByColumnAndRow(91, $row); $data['cn5'] = trim($cn5->getValue());



						$co_abridgedinc = $worksheet->getCellByColumnAndRow(92, $row); $data['co_abridgedinc'] = trim($co_abridgedinc->getValue());



						$cp_sor = $worksheet->getCellByColumnAndRow(93, $row); $data['cp_sor'] = trim($cp_sor->getValue());



						$cq_adjnote = $worksheet->getCellByColumnAndRow(94, $row); $data['cq_adjnote'] = trim($cq_adjnote->getValue());







						$cr_position = $worksheet->getCellByColumnAndRow(95, $row); $data['cr_position'] = trim($cr_position->getValue());



						$cs_liability = $worksheet->getCellByColumnAndRow(96, $row); $data['cs_liability'] = trim($cs_liability->getValue());



						$ct_prelim = $worksheet->getCellByColumnAndRow(97, $row); $data['ct_prelim'] = trim($ct_prelim->getValue());



						$cu_paydate = $worksheet->getCellByColumnAndRow(98, $row); $data['cu_paydate'] = trim($cu_paydate->getValue());



						$cv_included = $worksheet->getCellByColumnAndRow(99, $row); $data['cv_included'] = trim($cv_included->getValue());



						$cw_liability = $worksheet->getCellByColumnAndRow(100, $row); $data['cw_liability'] = trim($cw_liability->getValue());



						$cx_prelim = $worksheet->getCellByColumnAndRow(101, $row); $data['cx_prelim'] = trim($cx_prelim->getValue());



						$cy_paydate = $worksheet->getCellByColumnAndRow(102, $row); $data['cy_paydate'] = trim($cy_paydate->getValue());



						$cz_invoice = $worksheet->getCellByColumnAndRow(103, $row); $data['cz_invoice'] = trim($cz_invoice->getValue());



						$da_blank1 = $worksheet->getCellByColumnAndRow(104, $row); $data['da_blank1'] = trim($da_blank1->getValue());



						$db_blank2 = $worksheet->getCellByColumnAndRow(105, $row); $data['db_blank2'] = trim($db_blank2->getValue());



						$dc_blank3 = $worksheet->getCellByColumnAndRow(106, $row); $data['dc_blank3'] = trim($dc_blank3->getValue());



						$statement = $worksheet->getCellByColumnAndRow(107, $row); $data['statement'] = trim($statement->getValue());







						







						$f_row1 = $worksheet->getCellByColumnAndRow(5, $row); $data['f_row1'] = trim($f_row1->getValue());







									$inc_vat_rate = str_replace("%","",$invoice_vatrate);



									$data['vat_rate'] = $inc_vat_rate;



						$vat_percentage = $inc_vat_rate / 100;


						$data['vat_value'] = $data['inv_net'] * $vat_percentage;
						$data['gross'] = $data['inv_net'] + ($data['inv_net'] * $vat_percentage);







						$db_id = $check_invoice->id;



						DB::table('invoice_system')->where('id',$db_id)->update($data);



					}



					else{



						$data['invoice_number'] = $invoice_no;



						$invoice_date = $worksheet->getCellByColumnAndRow(1, $row); $invoice_date = trim($invoice_date->getValue());







						$explode_invoice_date = explode("/",$invoice_date);
						if(count($explode_invoice_date) == 3)
						{
							$inc_date = $explode_invoice_date[0].'-'.$explode_invoice_date[1].'-'.$explode_invoice_date[2];
							$data['invoice_date'] = date('Y-m-d',strtotime($inc_date));

						}
						else{
							$data['invoice_date'] = date('Y-m-d',strtotime($invoice_date));
						}



						$client = $worksheet->getCellByColumnAndRow(2, $row); $data['client_id'] = trim($client->getValue());



						$invoice_net = $worksheet->getCellByColumnAndRow(3, $row); $data['inv_net'] = str_replace(',', '', trim($invoice_net->getValue()));



						$invoice_vatrate = $worksheet->getCellByColumnAndRow(4, $row); $invoice_vatrate = trim($invoice_vatrate->getValue());











						$f_row1 = $worksheet->getCellByColumnAndRow(5, $row); $data['f_row1'] = trim($f_row1->getValue());



						$g_row2 = $worksheet->getCellByColumnAndRow(6, $row); $data['g_row2'] = trim($g_row2->getValue());



						$h_row3 = $worksheet->getCellByColumnAndRow(7, $row); $data['h_row3'] = trim($h_row3->getValue());



						$i_row4 = $worksheet->getCellByColumnAndRow(8, $row); $data['i_row4'] = trim($i_row4->getValue());



						$j_row5 = $worksheet->getCellByColumnAndRow(9, $row); $data['j_row5'] = trim($j_row5->getValue());



						$k_row6 = $worksheet->getCellByColumnAndRow(10, $row); $data['k_row6'] = trim($k_row6->getValue());



						$l_row7 = $worksheet->getCellByColumnAndRow(11, $row); $data['l_row7'] = trim($l_row7->getValue());



						$m_row8 = $worksheet->getCellByColumnAndRow(12, $row); $data['m_row8'] = trim($m_row8->getValue());



						$n_row9 = $worksheet->getCellByColumnAndRow(13, $row); $data['n_row9'] = trim($n_row9->getValue());



						$o_row10 = $worksheet->getCellByColumnAndRow(14, $row); $data['o_row10'] = trim($o_row10->getValue());



						$p_row11 = $worksheet->getCellByColumnAndRow(15, $row); $data['p_row11'] = trim($p_row11->getValue());



						$q_row12 = $worksheet->getCellByColumnAndRow(16, $row); $data['q_row12'] = trim($q_row12->getValue());



						$r_row13 = $worksheet->getCellByColumnAndRow(17, $row); $data['r_row13'] = trim($r_row13->getValue());



						$s_row14 = $worksheet->getCellByColumnAndRow(18, $row); $data['s_row14'] = trim($s_row14->getValue());



						$t_row15 = $worksheet->getCellByColumnAndRow(19, $row); $data['t_row15'] = trim($t_row15->getValue());



						$u_row16 = $worksheet->getCellByColumnAndRow(20, $row); $data['u_row16'] = trim($u_row16->getValue());



						$v_row17 = $worksheet->getCellByColumnAndRow(21, $row); $data['v_row17'] = trim($v_row17->getValue());



						$w_row18 = $worksheet->getCellByColumnAndRow(22, $row); $data['w_row18'] = trim($w_row18->getValue());



						$x_row19 = $worksheet->getCellByColumnAndRow(23, $row); $data['x_row19'] = trim($x_row19->getValue());



						$y_row20 = $worksheet->getCellByColumnAndRow(24, $row); $data['y_row20'] = trim($y_row20->getValue());







						$z_row1 = $worksheet->getCellByColumnAndRow(25, $row); $data['z_row1'] = trim($z_row1->getValue());



						$aa_row2 = $worksheet->getCellByColumnAndRow(26, $row); $data['aa_row2'] = trim($aa_row2->getValue());



						$ab_row3 = $worksheet->getCellByColumnAndRow(27, $row); $data['ab_row3'] = trim($ab_row3->getValue());



						$ac_row4 = $worksheet->getCellByColumnAndRow(28, $row); $data['ac_row4'] = trim($ac_row4->getValue());



						$ad_row5 = $worksheet->getCellByColumnAndRow(29, $row); $data['ad_row5'] = trim($ad_row5->getValue());



						$ae_row6 = $worksheet->getCellByColumnAndRow(30, $row); $data['ae_row6'] = trim($ae_row6->getValue());



						$af_row7 = $worksheet->getCellByColumnAndRow(31, $row); $data['af_row7'] = trim($af_row7->getValue());



						$ag_row8 = $worksheet->getCellByColumnAndRow(32, $row); $data['ag_row8'] = trim($ag_row8->getValue());



						$ah_row9 = $worksheet->getCellByColumnAndRow(33, $row); $data['ah_row9'] = trim($ah_row9->getValue());



						$ai_row10 = $worksheet->getCellByColumnAndRow(34, $row); $data['ai_row10'] = trim($ai_row10->getValue());



						$aj_row11 = $worksheet->getCellByColumnAndRow(35, $row); $data['aj_row11'] = trim($aj_row11->getValue());



						$ak_row12 = $worksheet->getCellByColumnAndRow(36, $row); $data['ak_row12'] = trim($ak_row12->getValue());



						$al_row13 = $worksheet->getCellByColumnAndRow(37, $row); $data['al_row13'] = trim($al_row13->getValue());



						$am_row14 = $worksheet->getCellByColumnAndRow(38, $row); $data['am_row14'] = trim($am_row14->getValue());



						$an_row15 = $worksheet->getCellByColumnAndRow(39, $row); $data['an_row15'] = trim($an_row15->getValue());



						$ao_row16 = $worksheet->getCellByColumnAndRow(40, $row); $data['ao_row16'] = trim($ao_row16->getValue());



						$ap_row17 = $worksheet->getCellByColumnAndRow(41, $row); $data['ap_row17'] = trim($ap_row17->getValue());



						$aq_row18 = $worksheet->getCellByColumnAndRow(42, $row); $data['aq_row18'] = trim($aq_row18->getValue());



						$ar_row19 = $worksheet->getCellByColumnAndRow(43, $row); $data['ar_row19'] = trim($ar_row19->getValue());



						$as_row20 = $worksheet->getCellByColumnAndRow(44, $row); $data['as_row20'] = trim($as_row20->getValue());











						$at_row1 = $worksheet->getCellByColumnAndRow(45, $row); $data['at_row1'] = trim($at_row1->getValue());



						$au_row2 = $worksheet->getCellByColumnAndRow(46, $row); $data['au_row2'] = trim($au_row2->getValue());



						$av_row3 = $worksheet->getCellByColumnAndRow(47, $row); $data['av_row3'] = trim($av_row3->getValue());



						$aw_row4 = $worksheet->getCellByColumnAndRow(48, $row); $data['aw_row4'] = trim($aw_row4->getValue());



						$ax_row5 = $worksheet->getCellByColumnAndRow(49, $row); $data['ax_row5'] = trim($ax_row5->getValue());



						$ay_row6 = $worksheet->getCellByColumnAndRow(50, $row); $data['ay_row6'] = trim($ay_row6->getValue());



						$az_row7 = $worksheet->getCellByColumnAndRow(51, $row); $data['az_row7'] = trim($az_row7->getValue());



						$ba_row8 = $worksheet->getCellByColumnAndRow(52, $row); $data['ba_row8'] = trim($ba_row8->getValue());



						$bb_row9 = $worksheet->getCellByColumnAndRow(53, $row); $data['bb_row9'] = trim($bb_row9->getValue());



						$bc_row10 = $worksheet->getCellByColumnAndRow(54, $row); $data['bc_row10'] = trim($bc_row10->getValue());



						$bd_row11 = $worksheet->getCellByColumnAndRow(55, $row); $data['bd_row11'] = trim($bd_row11->getValue());



						$be_row12 = $worksheet->getCellByColumnAndRow(56, $row); $data['be_row12'] = trim($be_row12->getValue());



						$bf_row13 = $worksheet->getCellByColumnAndRow(57, $row); $data['bf_row13'] = trim($bf_row13->getValue());



						$bg_row14 = $worksheet->getCellByColumnAndRow(58, $row); $data['bg_row14'] = trim($bg_row14->getValue());



						$bh_row15 = $worksheet->getCellByColumnAndRow(59, $row); $data['bh_row15'] = trim($bh_row15->getValue());



						$bi_row16 = $worksheet->getCellByColumnAndRow(60, $row); $data['bi_row16'] = trim($bi_row16->getValue());



						$bj_row17 = $worksheet->getCellByColumnAndRow(61, $row); $data['bj_row17'] = trim($bj_row17->getValue());



						$bk_row18 = $worksheet->getCellByColumnAndRow(62, $row); $data['bk_row18'] = trim($bk_row18->getValue());



						$bl_row19 = $worksheet->getCellByColumnAndRow(63, $row); $data['bl_row19'] = trim($bl_row19->getValue());



						$bm_row20 = $worksheet->getCellByColumnAndRow(64, $row); $data['bm_row20'] = trim($bm_row20->getValue());







						$bn_row1 = $worksheet->getCellByColumnAndRow(65, $row); $data['bn_row1'] = trim($bn_row1->getValue());



						$bo_row2 = $worksheet->getCellByColumnAndRow(66, $row); $data['bo_row2'] = trim($bo_row2->getValue());



						$bp_row3 = $worksheet->getCellByColumnAndRow(67, $row); $data['bp_row3'] = trim($bp_row3->getValue());



						$bq_row4 = $worksheet->getCellByColumnAndRow(68, $row); $data['bq_row4'] = trim($bq_row4->getValue());



						$br_row5 = $worksheet->getCellByColumnAndRow(69, $row); $data['br_row5'] = trim($br_row5->getValue());



						$bs_row6 = $worksheet->getCellByColumnAndRow(70, $row); $data['bs_row6'] = trim($bs_row6->getValue());



						$bt_row7 = $worksheet->getCellByColumnAndRow(71, $row); $data['bt_row7'] = trim($bt_row7->getValue());



						$bu_row8 = $worksheet->getCellByColumnAndRow(72, $row); $data['bu_row8'] = trim($bu_row8->getValue());



						$bv_row9 = $worksheet->getCellByColumnAndRow(73, $row); $data['bv_row9'] = trim($bv_row9->getValue());



						$bw_row10 = $worksheet->getCellByColumnAndRow(74, $row); $data['bw_row10'] = trim($bw_row10->getValue());



						$bx_row11 = $worksheet->getCellByColumnAndRow(75, $row); $data['bx_row11'] = trim($bx_row11->getValue());



						$by_row12 = $worksheet->getCellByColumnAndRow(76, $row); $data['by_row12'] = trim($by_row12->getValue());



						$bz_row13 = $worksheet->getCellByColumnAndRow(77, $row); $data['bz_row13'] = trim($bz_row13->getValue());



						$ca_row14 = $worksheet->getCellByColumnAndRow(78, $row); $data['ca_row14'] = trim($ca_row14->getValue());



						$cb_row15 = $worksheet->getCellByColumnAndRow(79, $row); $data['cb_row15'] = trim($cb_row15->getValue());



						$cc_row16 = $worksheet->getCellByColumnAndRow(80, $row); $data['cc_row16'] = trim($cc_row16->getValue());



						$cd_row17 = $worksheet->getCellByColumnAndRow(81, $row); $data['cd_row17'] = trim($cd_row17->getValue());



						$ce_row18 = $worksheet->getCellByColumnAndRow(82, $row); $data['ce_row18'] = trim($ce_row18->getValue());



						$cf_row19 = $worksheet->getCellByColumnAndRow(83, $row); $data['cf_row19'] = trim($cf_row19->getValue());



						$cg_row20 = $worksheet->getCellByColumnAndRow(84, $row); $data['cg_row20'] = trim($cg_row20->getValue());







						$ch_invoice_number = $worksheet->getCellByColumnAndRow(85, $row); $data['ch_invoice_number'] = trim($ch_invoice_number->getValue());



						$ci_year_end = $worksheet->getCellByColumnAndRow(86, $row); $data['ci_year_end'] = trim($ci_year_end->getValue());



						$cj1 = $worksheet->getCellByColumnAndRow(87, $row); $data['cj1'] = trim($cj1->getValue());



						$ck2 = $worksheet->getCellByColumnAndRow(88, $row); $data['ck2'] = trim($ck2->getValue());



						$cl3 = $worksheet->getCellByColumnAndRow(89, $row); $data['cl3'] = trim($cl3->getValue());



						$cm4 = $worksheet->getCellByColumnAndRow(90, $row); $data['cm4'] = trim($cm4->getValue());



						$cn5 = $worksheet->getCellByColumnAndRow(91, $row); $data['cn5'] = trim($cn5->getValue());



						$co_abridgedinc = $worksheet->getCellByColumnAndRow(92, $row); $data['co_abridgedinc'] = trim($co_abridgedinc->getValue());



						$cp_sor = $worksheet->getCellByColumnAndRow(93, $row); $data['cp_sor'] = trim($cp_sor->getValue());



						$cq_adjnote = $worksheet->getCellByColumnAndRow(94, $row); $data['cq_adjnote'] = trim($cq_adjnote->getValue());







						$cr_position = $worksheet->getCellByColumnAndRow(95, $row); $data['cr_position'] = trim($cr_position->getValue());



						$cs_liability = $worksheet->getCellByColumnAndRow(96, $row); $data['cs_liability'] = trim($cs_liability->getValue());



						$ct_prelim = $worksheet->getCellByColumnAndRow(97, $row); $data['ct_prelim'] = trim($ct_prelim->getValue());



						$cu_paydate = $worksheet->getCellByColumnAndRow(98, $row); $data['cu_paydate'] = trim($cu_paydate->getValue());



						$cv_included = $worksheet->getCellByColumnAndRow(99, $row); $data['cv_included'] = trim($cv_included->getValue());



						$cw_liability = $worksheet->getCellByColumnAndRow(100, $row); $data['cw_liability'] = trim($cw_liability->getValue());



						$cx_prelim = $worksheet->getCellByColumnAndRow(101, $row); $data['cx_prelim'] = trim($cx_prelim->getValue());



						$cy_paydate = $worksheet->getCellByColumnAndRow(102, $row); $data['cy_paydate'] = trim($cy_paydate->getValue());



						$cz_invoice = $worksheet->getCellByColumnAndRow(103, $row); $data['cz_invoice'] = trim($cz_invoice->getValue());



						$da_blank1 = $worksheet->getCellByColumnAndRow(104, $row); $data['da_blank1'] = trim($da_blank1->getValue());



						$db_blank2 = $worksheet->getCellByColumnAndRow(105, $row); $data['db_blank2'] = trim($db_blank2->getValue());



						$dc_blank3 = $worksheet->getCellByColumnAndRow(106, $row); $data['dc_blank3'] = trim($dc_blank3->getValue());



						$statement = $worksheet->getCellByColumnAndRow(107, $row); $data['statement'] = trim($statement->getValue());







						















									$inc_vat_rate = str_replace("%","",$invoice_vatrate);



									$data['vat_rate'] = $inc_vat_rate;



						$vat_percentage = $inc_vat_rate / 100;


									$data['vat_value'] = $data['inv_net'] * $vat_percentage;
									$data['gross'] = $data['inv_net'] + ($data['inv_net'] * $vat_percentage);



						DB::table('invoice_system')->insert($data);



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



				return redirect('user/invoice_management')->with('success_error', $out);



			}



			else{



				return redirect('user/invoice_management')->with('message', 'Invoice Imported successfully.');



			}



		}



		else{



			return redirect('user/invoice_management?filename='.$name.'&height='.$height.'&round='.$nextround.'&highestrow='.$highestRow.'&out='.$out.'&import_type_new=1');



		}



	}







	



}




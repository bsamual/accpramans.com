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



									<td align="left" style="'.$textcolor.'"><a href="javascript:" class="invoice_class" data-element="<?php echo $invoice->invoice_number; ?>" style="<?php echo $textcolor?>">'.$invoice->invoice_number.'</a></td>



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



							<td align="left" style="'.$textcolor.'"><a href="javascript:" class="invoice_class" data-element="<?php echo $invoice->invoice_number; ?>" style="<?php echo $textcolor?>">'.$invoice->invoice_number.'</a></td>



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



                                <td align="left" style="'.$textcolor.'"><a href="javascript:" class="invoice_class" data-element="<?php echo $invoice->invoice_number; ?>" style="<?php echo $textcolor?>">'.$invoice->invoice_number.'</a></td>



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



							<td align="left" style="'.$textcolor.'"><a href="javascript:" class="invoice_class" data-element="<?php echo $invoice->invoice_number; ?>" style="<?php echo $textcolor?>">'.$invoice->invoice_number.'</a></td>



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



							<td align="left" style="'.$textcolor.'"><a href="javascript:" class="invoice_class" data-element="<?php echo $invoice->invoice_number; ?>" style="<?php echo $textcolor?>">'.$invoice->invoice_number.'</a></td>



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
									$explode_hyphen_invoice_date = explode("-",$invoice_date);
									if(count($explode_invoice_date) == 3)
									{
										$inc_date = $explode_invoice_date[0].'-'.$explode_invoice_date[1].'-'.$explode_invoice_date[2];
										$data['invoice_date'] = date('Y-m-d',strtotime($inc_date));

									}
									elseif(count($explode_hyphen_invoice_date) == 3){
										$data['invoice_date'] = date('Y-m-d',strtotime($invoice_date));
									}
									else{
										$unix_date = ($invoice_date - 25569) * 86400;
										$excel_date = 25569 + ($unix_date / 86400);
										$unix_date = ($excel_date - 25569) * 86400;
										$data['invoice_date'] = gmdate("Y-m-d", $unix_date);
									}

									$client = $worksheet->getCellByColumnAndRow(2, $row); $data['client_id'] = trim($client->getValue());



									$invoice_net = $worksheet->getCellByColumnAndRow(3, $row); $data['inv_net'] = str_replace(',', '', trim($invoice_net->getValue()));



									$invoice_vatrate = $worksheet->getCellByColumnAndRow(4, $row); $invoice_vatrate = trim($invoice_vatrate->getValue());







									$f_row1 = $worksheet->getCellByColumnAndRow(5, $row); $data['f_row1'] = (!empty($f_row1->getValue()))?$f_row1->getValue():'&nbsp;';



									$g_row2 = $worksheet->getCellByColumnAndRow(6, $row); $data['g_row2'] = (!empty($g_row2->getValue()))?$g_row2->getValue():'&nbsp;';



									$h_row3 = $worksheet->getCellByColumnAndRow(7, $row); $data['h_row3'] = (!empty($h_row3->getValue()))?$h_row3->getValue():'&nbsp;';



									$i_row4 = $worksheet->getCellByColumnAndRow(8, $row); $data['i_row4'] = (!empty($i_row4->getValue()))?$i_row4->getValue():'&nbsp;';



									$j_row5 = $worksheet->getCellByColumnAndRow(9, $row); $data['j_row5'] = (!empty($j_row5->getValue()))?$j_row5->getValue():'&nbsp;';



									$k_row6 = $worksheet->getCellByColumnAndRow(10, $row); $data['k_row6'] = (!empty($k_row6->getValue()))?$k_row6->getValue():'&nbsp;';



									$l_row7 = $worksheet->getCellByColumnAndRow(11, $row); $data['l_row7'] = (!empty($l_row7->getValue()))?$l_row7->getValue():'&nbsp;';



									$m_row8 = $worksheet->getCellByColumnAndRow(12, $row); $data['m_row8'] = (!empty($m_row8->getValue()))?$m_row8->getValue():'&nbsp;';



									$n_row9 = $worksheet->getCellByColumnAndRow(13, $row); $data['n_row9'] = (!empty($n_row9->getValue()))?$n_row9->getValue():'&nbsp;';



									$o_row10 = $worksheet->getCellByColumnAndRow(14, $row); $data['o_row10'] = (!empty($o_row10->getValue()))?$o_row10->getValue():'&nbsp;';



									$p_row11 = $worksheet->getCellByColumnAndRow(15, $row); $data['p_row11'] = (!empty($p_row11->getValue()))?$p_row11->getValue():'&nbsp;';



									$q_row12 = $worksheet->getCellByColumnAndRow(16, $row); $data['q_row12'] = (!empty($q_row12->getValue()))?$q_row12->getValue():'&nbsp;';



									$r_row13 = $worksheet->getCellByColumnAndRow(17, $row); $data['r_row13'] = (!empty($r_row13->getValue()))?$r_row13->getValue():'&nbsp;';



									$s_row14 = $worksheet->getCellByColumnAndRow(18, $row); $data['s_row14'] = (!empty($s_row14->getValue()))?$s_row14->getValue():'&nbsp;';



									$t_row15 = $worksheet->getCellByColumnAndRow(19, $row); $data['t_row15'] = (!empty($t_row15->getValue()))?$t_row15->getValue():'&nbsp;';



									$u_row16 = $worksheet->getCellByColumnAndRow(20, $row); $data['u_row16'] = (!empty($u_row16->getValue()))?$u_row16->getValue():'&nbsp;';



									$v_row17 = $worksheet->getCellByColumnAndRow(21, $row); $data['v_row17'] = (!empty($v_row17->getValue()))?$v_row17->getValue():'&nbsp;';



									$w_row18 = $worksheet->getCellByColumnAndRow(22, $row); $data['w_row18'] = (!empty($w_row18->getValue()))?$w_row18->getValue():'&nbsp;';



									$x_row19 = $worksheet->getCellByColumnAndRow(23, $row); $data['x_row19'] = (!empty($x_row19->getValue()))?$x_row19->getValue():'&nbsp;';



									$y_row20 = $worksheet->getCellByColumnAndRow(24, $row); $data['y_row20'] = (!empty($y_row20->getValue()))?$y_row20->getValue():'&nbsp;';







									$z_row1 = $worksheet->getCellByColumnAndRow(25, $row); $data['z_row1'] = (!empty($z_row1->getValue()))?$z_row1->getValue():'&nbsp;';



									$aa_row2 = $worksheet->getCellByColumnAndRow(26, $row); $data['aa_row2'] = (!empty($aa_row2->getValue()))?$aa_row2->getValue():'&nbsp;';



									$ab_row3 = $worksheet->getCellByColumnAndRow(27, $row); $data['ab_row3'] = (!empty($ab_row3->getValue()))?$ab_row3->getValue():'&nbsp;';



									$ac_row4 = $worksheet->getCellByColumnAndRow(28, $row); $data['ac_row4'] = (!empty($ac_row4->getValue()))?$ac_row4->getValue():'&nbsp;';



									$ad_row5 = $worksheet->getCellByColumnAndRow(29, $row); $data['ad_row5'] = (!empty($ad_row5->getValue()))?$ad_row5->getValue():'&nbsp;';



									$ae_row6 = $worksheet->getCellByColumnAndRow(30, $row); $data['ae_row6'] = (!empty($ae_row6->getValue()))?$ae_row6->getValue():'&nbsp;';



									$af_row7 = $worksheet->getCellByColumnAndRow(31, $row); $data['af_row7'] = (!empty($af_row7->getValue()))?$af_row7->getValue():'&nbsp;';



									$ag_row8 = $worksheet->getCellByColumnAndRow(32, $row); $data['ag_row8'] = (!empty($ag_row8->getValue()))?$ag_row8->getValue():'&nbsp;';



									$ah_row9 = $worksheet->getCellByColumnAndRow(33, $row); $data['ah_row9'] = (!empty($ah_row9->getValue()))?$ah_row9->getValue():'&nbsp;';



									$ai_row10 = $worksheet->getCellByColumnAndRow(34, $row); $data['ai_row10'] = (!empty($ai_row10->getValue()))?$ai_row10->getValue():'&nbsp;';



									$aj_row11 = $worksheet->getCellByColumnAndRow(35, $row); $data['aj_row11'] = (!empty($aj_row11->getValue()))?$aj_row11->getValue():'&nbsp;';



									$ak_row12 = $worksheet->getCellByColumnAndRow(36, $row); $data['ak_row12'] = (!empty($ak_row12->getValue()))?$ak_row12->getValue():'&nbsp;';



									$al_row13 = $worksheet->getCellByColumnAndRow(37, $row); $data['al_row13'] = (!empty($al_row13->getValue()))?$al_row13->getValue():'&nbsp;';



									$am_row14 = $worksheet->getCellByColumnAndRow(38, $row); $data['am_row14'] = (!empty($am_row14->getValue()))?$am_row14->getValue():'&nbsp;';



									$an_row15 = $worksheet->getCellByColumnAndRow(39, $row); $data['an_row15'] = (!empty($an_row15->getValue()))?$an_row15->getValue():'&nbsp;';



									$ao_row16 = $worksheet->getCellByColumnAndRow(40, $row); $data['ao_row16'] = (!empty($ao_row16->getValue()))?$ao_row16->getValue():'&nbsp;';



									$ap_row17 = $worksheet->getCellByColumnAndRow(41, $row); $data['ap_row17'] = (!empty($ap_row17->getValue()))?$ap_row17->getValue():'&nbsp;';



									$aq_row18 = $worksheet->getCellByColumnAndRow(42, $row); $data['aq_row18'] = (!empty($aq_row18->getValue()))?$aq_row18->getValue():'&nbsp;';



									$ar_row19 = $worksheet->getCellByColumnAndRow(43, $row); $data['ar_row19'] = (!empty($ar_row19->getValue()))?$ar_row19->getValue():'&nbsp;';



									$as_row20 = $worksheet->getCellByColumnAndRow(44, $row); $data['as_row20'] = (!empty($as_row20->getValue()))?$as_row20->getValue():'&nbsp;';











									$at_row1 = $worksheet->getCellByColumnAndRow(45, $row); $data['at_row1'] = (!empty($at_row1->getValue()))?$at_row1->getValue():'&nbsp;';



									$au_row2 = $worksheet->getCellByColumnAndRow(46, $row); $data['au_row2'] = (!empty($au_row2->getValue()))?$au_row2->getValue():'&nbsp;';



									$av_row3 = $worksheet->getCellByColumnAndRow(47, $row); $data['av_row3'] = (!empty($av_row3->getValue()))?$av_row3->getValue():'&nbsp;';



									$aw_row4 = $worksheet->getCellByColumnAndRow(48, $row); $data['aw_row4'] = (!empty($aw_row4->getValue()))?$aw_row4->getValue():'&nbsp;';



									$ax_row5 = $worksheet->getCellByColumnAndRow(49, $row); $data['ax_row5'] = (!empty($ax_row5->getValue()))?$ax_row5->getValue():'&nbsp;';



									$ay_row6 = $worksheet->getCellByColumnAndRow(50, $row); $data['ay_row6'] = (!empty($ay_row6->getValue()))?$ay_row6->getValue():'&nbsp;';



									$az_row7 = $worksheet->getCellByColumnAndRow(51, $row); $data['az_row7'] = (!empty($az_row7->getValue()))?$az_row7->getValue():'&nbsp;';



									$ba_row8 = $worksheet->getCellByColumnAndRow(52, $row); $data['ba_row8'] = (!empty($ba_row8->getValue()))?$ba_row8->getValue():'&nbsp;';



									$bb_row9 = $worksheet->getCellByColumnAndRow(53, $row); $data['bb_row9'] = (!empty($bb_row9->getValue()))?$bb_row9->getValue():'&nbsp;';



									$bc_row10 = $worksheet->getCellByColumnAndRow(54, $row); $data['bc_row10'] = (!empty($bc_row10->getValue()))?$bc_row10->getValue():'&nbsp;';



									$bd_row11 = $worksheet->getCellByColumnAndRow(55, $row); $data['bd_row11'] = (!empty($bd_row11->getValue()))?$bd_row11->getValue():'&nbsp;';



									$be_row12 = $worksheet->getCellByColumnAndRow(56, $row); $data['be_row12'] = (!empty($be_row12->getValue()))?$be_row12->getValue():'&nbsp;';



									$bf_row13 = $worksheet->getCellByColumnAndRow(57, $row); $data['bf_row13'] = (!empty($bf_row13->getValue()))?$bf_row13->getValue():'&nbsp;';



									$bg_row14 = $worksheet->getCellByColumnAndRow(58, $row); $data['bg_row14'] = (!empty($bg_row14->getValue()))?$bg_row14->getValue():'&nbsp;';



									$bh_row15 = $worksheet->getCellByColumnAndRow(59, $row); $data['bh_row15'] = (!empty($bh_row15->getValue()))?$bh_row15->getValue():'&nbsp;';



									$bi_row16 = $worksheet->getCellByColumnAndRow(60, $row); $data['bi_row16'] = (!empty($bi_row16->getValue()))?$bi_row16->getValue():'&nbsp;';



									$bj_row17 = $worksheet->getCellByColumnAndRow(61, $row); $data['bj_row17'] = (!empty($bj_row17->getValue()))?$bj_row17->getValue():'&nbsp;';



									$bk_row18 = $worksheet->getCellByColumnAndRow(62, $row); $data['bk_row18'] = (!empty($bk_row18->getValue()))?$bk_row18->getValue():'&nbsp;';



									$bl_row19 = $worksheet->getCellByColumnAndRow(63, $row); $data['bl_row19'] = (!empty($bl_row19->getValue()))?$bl_row19->getValue():'&nbsp;';



									$bm_row20 = $worksheet->getCellByColumnAndRow(64, $row); $data['bm_row20'] = (!empty($bm_row20->getValue()))?$bm_row20->getValue():'&nbsp;';







									$bn_row1 = $worksheet->getCellByColumnAndRow(65, $row); $data['bn_row1'] = (!empty($bn_row1->getValue()))?$bn_row1->getValue():'&nbsp;';



									$bo_row2 = $worksheet->getCellByColumnAndRow(66, $row); $data['bo_row2'] = (!empty($bo_row2->getValue()))?$bo_row2->getValue():'&nbsp;';



									$bp_row3 = $worksheet->getCellByColumnAndRow(67, $row); $data['bp_row3'] = (!empty($bp_row3->getValue()))?$bp_row3->getValue():'&nbsp;';



									$bq_row4 = $worksheet->getCellByColumnAndRow(68, $row); $data['bq_row4'] = (!empty($bq_row4->getValue()))?$bq_row4->getValue():'&nbsp;';



									$br_row5 = $worksheet->getCellByColumnAndRow(69, $row); $data['br_row5'] = (!empty($br_row5->getValue()))?$br_row5->getValue():'&nbsp;';



									$bs_row6 = $worksheet->getCellByColumnAndRow(70, $row); $data['bs_row6'] = (!empty($bs_row6->getValue()))?$bs_row6->getValue():'&nbsp;';



									$bt_row7 = $worksheet->getCellByColumnAndRow(71, $row); $data['bt_row7'] = (!empty($bt_row7->getValue()))?$bt_row7->getValue():'&nbsp;';



									$bu_row8 = $worksheet->getCellByColumnAndRow(72, $row); $data['bu_row8'] = (!empty($bu_row8->getValue()))?$bu_row8->getValue():'&nbsp;';



									$bv_row9 = $worksheet->getCellByColumnAndRow(73, $row); $data['bv_row9'] = (!empty($bv_row9->getValue()))?$bv_row9->getValue():'&nbsp;';



									$bw_row10 = $worksheet->getCellByColumnAndRow(74, $row); $data['bw_row10'] = (!empty($bw_row10->getValue()))?$bw_row10->getValue():'&nbsp;';



									$bx_row11 = $worksheet->getCellByColumnAndRow(75, $row); $data['bx_row11'] = (!empty($bx_row11->getValue()))?$bx_row11->getValue():'&nbsp;';



									$by_row12 = $worksheet->getCellByColumnAndRow(76, $row); $data['by_row12'] = (!empty($by_row12->getValue()))?$by_row12->getValue():'&nbsp;';



									$bz_row13 = $worksheet->getCellByColumnAndRow(77, $row); $data['bz_row13'] = (!empty($bz_row13->getValue()))?$bz_row13->getValue():'&nbsp;';



									$ca_row14 = $worksheet->getCellByColumnAndRow(78, $row); $data['ca_row14'] = (!empty($ca_row14->getValue()))?$ca_row14->getValue():'&nbsp;';



									$cb_row15 = $worksheet->getCellByColumnAndRow(79, $row); $data['cb_row15'] = (!empty($cb_row15->getValue()))?$cb_row15->getValue():'&nbsp;';



									$cc_row16 = $worksheet->getCellByColumnAndRow(80, $row); $data['cc_row16'] = (!empty($cc_row16->getValue()))?$cc_row16->getValue():'&nbsp;';



									$cd_row17 = $worksheet->getCellByColumnAndRow(81, $row); $data['cd_row17'] = (!empty($cd_row17->getValue()))?$cd_row17->getValue():'&nbsp;';



									$ce_row18 = $worksheet->getCellByColumnAndRow(82, $row); $data['ce_row18'] = (!empty($ce_row18->getValue()))?$ce_row18->getValue():'&nbsp;';



									$cf_row19 = $worksheet->getCellByColumnAndRow(83, $row); $data['cf_row19'] = (!empty($cf_row19->getValue()))?$cf_row19->getValue():'&nbsp;';



									$cg_row20 = $worksheet->getCellByColumnAndRow(84, $row); $data['cg_row20'] = (!empty($cg_row20->getValue()))?$cg_row20->getValue():'&nbsp;';







									$ch_invoice_number = $worksheet->getCellByColumnAndRow(85, $row); $data['ch_invoice_number'] = (!empty($ch_invoice_number->getValue()))?$ch_invoice_number->getValue():'&nbsp;';



									$ci_year_end = $worksheet->getCellByColumnAndRow(86, $row); $data['ci_year_end'] = (!empty($ci_year_end->getValue()))?$ci_year_end->getValue():'&nbsp;';



									$cj1 = $worksheet->getCellByColumnAndRow(87, $row); $data['cj1'] = (!empty($cj1->getValue()))?$cj1->getValue():'&nbsp;';



									$ck2 = $worksheet->getCellByColumnAndRow(88, $row); $data['ck2'] = (!empty($ck2->getValue()))?$ck2->getValue():'&nbsp;';



									$cl3 = $worksheet->getCellByColumnAndRow(89, $row); $data['cl3'] = (!empty($cl3->getValue()))?$cl3->getValue():'&nbsp;';



									$cm4 = $worksheet->getCellByColumnAndRow(90, $row); $data['cm4'] = (!empty($cm4->getValue()))?$cm4->getValue():'&nbsp;';



									$cn5 = $worksheet->getCellByColumnAndRow(91, $row); $data['cn5'] = (!empty($cn5->getValue()))?$cn5->getValue():'&nbsp;';



									$co_abridgedinc = $worksheet->getCellByColumnAndRow(92, $row); $data['co_abridgedinc'] = (!empty($co_abridgedinc->getValue()))?$co_abridgedinc->getValue():'&nbsp;';



									$cp_sor = $worksheet->getCellByColumnAndRow(93, $row); $data['cp_sor'] = (!empty($cp_sor->getValue()))?$cp_sor->getValue():'&nbsp;';



									$cq_adjnote = $worksheet->getCellByColumnAndRow(94, $row); $data['cq_adjnote'] = (!empty($cq_adjnote->getValue()))?$cq_adjnote->getValue():'&nbsp;';







									$cr_position = $worksheet->getCellByColumnAndRow(95, $row); $data['cr_position'] = (!empty($cr_position->getValue()))?$cr_position->getValue():'&nbsp;';



									$cs_liability = $worksheet->getCellByColumnAndRow(96, $row); $data['cs_liability'] = (!empty($cs_liability->getValue()))?$cs_liability->getValue():'&nbsp;';



									$ct_prelim = $worksheet->getCellByColumnAndRow(97, $row); $data['ct_prelim'] = (!empty($ct_prelim->getValue()))?$ct_prelim->getValue():'&nbsp;';



									$cu_paydate = $worksheet->getCellByColumnAndRow(98, $row); $data['cu_paydate'] = (!empty($cu_paydate->getValue()))?$cu_paydate->getValue():'&nbsp;';



									$cv_included = $worksheet->getCellByColumnAndRow(99, $row); $data['cv_included'] = (!empty($cv_included->getValue()))?$cv_included->getValue():'&nbsp;';



									$cw_liability = $worksheet->getCellByColumnAndRow(100, $row); $data['cw_liability'] = (!empty($cw_liability->getValue()))?$cw_liability->getValue():'&nbsp;';



									$cx_prelim = $worksheet->getCellByColumnAndRow(101, $row); $data['cx_prelim'] = (!empty($cx_prelim->getValue()))?$cx_prelim->getValue():'&nbsp;';



									$cy_paydate = $worksheet->getCellByColumnAndRow(102, $row); $data['cy_paydate'] = (!empty($cy_paydate->getValue()))?$cy_paydate->getValue():'&nbsp;';



									$cz_invoice = $worksheet->getCellByColumnAndRow(103, $row); $data['cz_invoice'] = (!empty($cz_invoice->getValue()))?$cz_invoice->getValue():'&nbsp;';



									$da_blank1 = $worksheet->getCellByColumnAndRow(104, $row); $data['da_blank1'] = (!empty($da_blank1->getValue()))?$da_blank1->getValue():'&nbsp;';



									$db_blank2 = $worksheet->getCellByColumnAndRow(105, $row); $data['db_blank2'] = (!empty($db_blank2->getValue()))?$db_blank2->getValue():'&nbsp;';



									$dc_blank3 = $worksheet->getCellByColumnAndRow(106, $row); $data['dc_blank3'] = (!empty($dc_blank3->getValue()))?$dc_blank3->getValue():'&nbsp;';



									$statement = $worksheet->getCellByColumnAndRow(107, $row); $data['statement'] = (!empty($statement->getValue()))?$statement->getValue():'&nbsp;';


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
									$explode_hyphen_invoice_date = explode("-",$invoice_date);
									if(count($explode_invoice_date) == 3)
									{
										$inc_date = $explode_invoice_date[0].'-'.$explode_invoice_date[1].'-'.$explode_invoice_date[2];
										$data['invoice_date'] = date('Y-m-d',strtotime($inc_date));

									}
									elseif(count($explode_hyphen_invoice_date) == 3){
										$data['invoice_date'] = date('Y-m-d',strtotime($invoice_date));
									}
									else{
										$unix_date = ($invoice_date - 25569) * 86400;
										$excel_date = 25569 + ($unix_date / 86400);
										$unix_date = ($excel_date - 25569) * 86400;
										$data['invoice_date'] = gmdate("Y-m-d", $unix_date);
									}



									$client = $worksheet->getCellByColumnAndRow(2, $row); $data['client_id'] = trim($client->getValue());



									$invoice_net = $worksheet->getCellByColumnAndRow(3, $row); $data['inv_net'] = str_replace(',', '', trim($invoice_net->getValue()));



									$invoice_vatrate = $worksheet->getCellByColumnAndRow(4, $row); $invoice_vatrate = trim($invoice_vatrate->getValue());







									$f_row1 = $worksheet->getCellByColumnAndRow(5, $row); $data['f_row1'] = (!empty($f_row1->getValue()))?$f_row1->getValue():'&nbsp;';



									$g_row2 = $worksheet->getCellByColumnAndRow(6, $row); $data['g_row2'] = (!empty($g_row2->getValue()))?$g_row2->getValue():'&nbsp;';



									$h_row3 = $worksheet->getCellByColumnAndRow(7, $row); $data['h_row3'] = (!empty($h_row3->getValue()))?$h_row3->getValue():'&nbsp;';



									$i_row4 = $worksheet->getCellByColumnAndRow(8, $row); $data['i_row4'] = (!empty($i_row4->getValue()))?$i_row4->getValue():'&nbsp;';



									$j_row5 = $worksheet->getCellByColumnAndRow(9, $row); $data['j_row5'] = (!empty($j_row5->getValue()))?$j_row5->getValue():'&nbsp;';



									$k_row6 = $worksheet->getCellByColumnAndRow(10, $row); $data['k_row6'] = (!empty($k_row6->getValue()))?$k_row6->getValue():'&nbsp;';



									$l_row7 = $worksheet->getCellByColumnAndRow(11, $row); $data['l_row7'] = (!empty($l_row7->getValue()))?$l_row7->getValue():'&nbsp;';



									$m_row8 = $worksheet->getCellByColumnAndRow(12, $row); $data['m_row8'] = (!empty($m_row8->getValue()))?$m_row8->getValue():'&nbsp;';



									$n_row9 = $worksheet->getCellByColumnAndRow(13, $row); $data['n_row9'] = (!empty($n_row9->getValue()))?$n_row9->getValue():'&nbsp;';



									$o_row10 = $worksheet->getCellByColumnAndRow(14, $row); $data['o_row10'] = (!empty($o_row10->getValue()))?$o_row10->getValue():'&nbsp;';



									$p_row11 = $worksheet->getCellByColumnAndRow(15, $row); $data['p_row11'] = (!empty($p_row11->getValue()))?$p_row11->getValue():'&nbsp;';



									$q_row12 = $worksheet->getCellByColumnAndRow(16, $row); $data['q_row12'] = (!empty($q_row12->getValue()))?$q_row12->getValue():'&nbsp;';



									$r_row13 = $worksheet->getCellByColumnAndRow(17, $row); $data['r_row13'] = (!empty($r_row13->getValue()))?$r_row13->getValue():'&nbsp;';



									$s_row14 = $worksheet->getCellByColumnAndRow(18, $row); $data['s_row14'] = (!empty($s_row14->getValue()))?$s_row14->getValue():'&nbsp;';



									$t_row15 = $worksheet->getCellByColumnAndRow(19, $row); $data['t_row15'] = (!empty($t_row15->getValue()))?$t_row15->getValue():'&nbsp;';



									$u_row16 = $worksheet->getCellByColumnAndRow(20, $row); $data['u_row16'] = (!empty($u_row16->getValue()))?$u_row16->getValue():'&nbsp;';



									$v_row17 = $worksheet->getCellByColumnAndRow(21, $row); $data['v_row17'] = (!empty($v_row17->getValue()))?$v_row17->getValue():'&nbsp;';



									$w_row18 = $worksheet->getCellByColumnAndRow(22, $row); $data['w_row18'] = (!empty($w_row18->getValue()))?$w_row18->getValue():'&nbsp;';



									$x_row19 = $worksheet->getCellByColumnAndRow(23, $row); $data['x_row19'] = (!empty($x_row19->getValue()))?$x_row19->getValue():'&nbsp;';



									$y_row20 = $worksheet->getCellByColumnAndRow(24, $row); $data['y_row20'] = (!empty($y_row20->getValue()))?$y_row20->getValue():'&nbsp;';







									$z_row1 = $worksheet->getCellByColumnAndRow(25, $row); $data['z_row1'] = (!empty($z_row1->getValue()))?$z_row1->getValue():'&nbsp;';



									$aa_row2 = $worksheet->getCellByColumnAndRow(26, $row); $data['aa_row2'] = (!empty($aa_row2->getValue()))?$aa_row2->getValue():'&nbsp;';



									$ab_row3 = $worksheet->getCellByColumnAndRow(27, $row); $data['ab_row3'] = (!empty($ab_row3->getValue()))?$ab_row3->getValue():'&nbsp;';



									$ac_row4 = $worksheet->getCellByColumnAndRow(28, $row); $data['ac_row4'] = (!empty($ac_row4->getValue()))?$ac_row4->getValue():'&nbsp;';



									$ad_row5 = $worksheet->getCellByColumnAndRow(29, $row); $data['ad_row5'] = (!empty($ad_row5->getValue()))?$ad_row5->getValue():'&nbsp;';



									$ae_row6 = $worksheet->getCellByColumnAndRow(30, $row); $data['ae_row6'] = (!empty($ae_row6->getValue()))?$ae_row6->getValue():'&nbsp;';



									$af_row7 = $worksheet->getCellByColumnAndRow(31, $row); $data['af_row7'] = (!empty($af_row7->getValue()))?$af_row7->getValue():'&nbsp;';



									$ag_row8 = $worksheet->getCellByColumnAndRow(32, $row); $data['ag_row8'] = (!empty($ag_row8->getValue()))?$ag_row8->getValue():'&nbsp;';



									$ah_row9 = $worksheet->getCellByColumnAndRow(33, $row); $data['ah_row9'] = (!empty($ah_row9->getValue()))?$ah_row9->getValue():'&nbsp;';



									$ai_row10 = $worksheet->getCellByColumnAndRow(34, $row); $data['ai_row10'] = (!empty($ai_row10->getValue()))?$ai_row10->getValue():'&nbsp;';



									$aj_row11 = $worksheet->getCellByColumnAndRow(35, $row); $data['aj_row11'] = (!empty($aj_row11->getValue()))?$aj_row11->getValue():'&nbsp;';



									$ak_row12 = $worksheet->getCellByColumnAndRow(36, $row); $data['ak_row12'] = (!empty($ak_row12->getValue()))?$ak_row12->getValue():'&nbsp;';



									$al_row13 = $worksheet->getCellByColumnAndRow(37, $row); $data['al_row13'] = (!empty($al_row13->getValue()))?$al_row13->getValue():'&nbsp;';



									$am_row14 = $worksheet->getCellByColumnAndRow(38, $row); $data['am_row14'] = (!empty($am_row14->getValue()))?$am_row14->getValue():'&nbsp;';



									$an_row15 = $worksheet->getCellByColumnAndRow(39, $row); $data['an_row15'] = (!empty($an_row15->getValue()))?$an_row15->getValue():'&nbsp;';



									$ao_row16 = $worksheet->getCellByColumnAndRow(40, $row); $data['ao_row16'] = (!empty($ao_row16->getValue()))?$ao_row16->getValue():'&nbsp;';



									$ap_row17 = $worksheet->getCellByColumnAndRow(41, $row); $data['ap_row17'] = (!empty($ap_row17->getValue()))?$ap_row17->getValue():'&nbsp;';



									$aq_row18 = $worksheet->getCellByColumnAndRow(42, $row); $data['aq_row18'] = (!empty($aq_row18->getValue()))?$aq_row18->getValue():'&nbsp;';



									$ar_row19 = $worksheet->getCellByColumnAndRow(43, $row); $data['ar_row19'] = (!empty($ar_row19->getValue()))?$ar_row19->getValue():'&nbsp;';



									$as_row20 = $worksheet->getCellByColumnAndRow(44, $row); $data['as_row20'] = (!empty($as_row20->getValue()))?$as_row20->getValue():'&nbsp;';











									$at_row1 = $worksheet->getCellByColumnAndRow(45, $row); $data['at_row1'] = (!empty($at_row1->getValue()))?$at_row1->getValue():'&nbsp;';



									$au_row2 = $worksheet->getCellByColumnAndRow(46, $row); $data['au_row2'] = (!empty($au_row2->getValue()))?$au_row2->getValue():'&nbsp;';



									$av_row3 = $worksheet->getCellByColumnAndRow(47, $row); $data['av_row3'] = (!empty($av_row3->getValue()))?$av_row3->getValue():'&nbsp;';



									$aw_row4 = $worksheet->getCellByColumnAndRow(48, $row); $data['aw_row4'] = (!empty($aw_row4->getValue()))?$aw_row4->getValue():'&nbsp;';



									$ax_row5 = $worksheet->getCellByColumnAndRow(49, $row); $data['ax_row5'] = (!empty($ax_row5->getValue()))?$ax_row5->getValue():'&nbsp;';



									$ay_row6 = $worksheet->getCellByColumnAndRow(50, $row); $data['ay_row6'] = (!empty($ay_row6->getValue()))?$ay_row6->getValue():'&nbsp;';



									$az_row7 = $worksheet->getCellByColumnAndRow(51, $row); $data['az_row7'] = (!empty($az_row7->getValue()))?$az_row7->getValue():'&nbsp;';



									$ba_row8 = $worksheet->getCellByColumnAndRow(52, $row); $data['ba_row8'] = (!empty($ba_row8->getValue()))?$ba_row8->getValue():'&nbsp;';



									$bb_row9 = $worksheet->getCellByColumnAndRow(53, $row); $data['bb_row9'] = (!empty($bb_row9->getValue()))?$bb_row9->getValue():'&nbsp;';



									$bc_row10 = $worksheet->getCellByColumnAndRow(54, $row); $data['bc_row10'] = (!empty($bc_row10->getValue()))?$bc_row10->getValue():'&nbsp;';



									$bd_row11 = $worksheet->getCellByColumnAndRow(55, $row); $data['bd_row11'] = (!empty($bd_row11->getValue()))?$bd_row11->getValue():'&nbsp;';



									$be_row12 = $worksheet->getCellByColumnAndRow(56, $row); $data['be_row12'] = (!empty($be_row12->getValue()))?$be_row12->getValue():'&nbsp;';



									$bf_row13 = $worksheet->getCellByColumnAndRow(57, $row); $data['bf_row13'] = (!empty($bf_row13->getValue()))?$bf_row13->getValue():'&nbsp;';



									$bg_row14 = $worksheet->getCellByColumnAndRow(58, $row); $data['bg_row14'] = (!empty($bg_row14->getValue()))?$bg_row14->getValue():'&nbsp;';



									$bh_row15 = $worksheet->getCellByColumnAndRow(59, $row); $data['bh_row15'] = (!empty($bh_row15->getValue()))?$bh_row15->getValue():'&nbsp;';



									$bi_row16 = $worksheet->getCellByColumnAndRow(60, $row); $data['bi_row16'] = (!empty($bi_row16->getValue()))?$bi_row16->getValue():'&nbsp;';



									$bj_row17 = $worksheet->getCellByColumnAndRow(61, $row); $data['bj_row17'] = (!empty($bj_row17->getValue()))?$bj_row17->getValue():'&nbsp;';



									$bk_row18 = $worksheet->getCellByColumnAndRow(62, $row); $data['bk_row18'] = (!empty($bk_row18->getValue()))?$bk_row18->getValue():'&nbsp;';



									$bl_row19 = $worksheet->getCellByColumnAndRow(63, $row); $data['bl_row19'] = (!empty($bl_row19->getValue()))?$bl_row19->getValue():'&nbsp;';



									$bm_row20 = $worksheet->getCellByColumnAndRow(64, $row); $data['bm_row20'] = (!empty($bm_row20->getValue()))?$bm_row20->getValue():'&nbsp;';







									$bn_row1 = $worksheet->getCellByColumnAndRow(65, $row); $data['bn_row1'] = (!empty($bn_row1->getValue()))?$bn_row1->getValue():'&nbsp;';



									$bo_row2 = $worksheet->getCellByColumnAndRow(66, $row); $data['bo_row2'] = (!empty($bo_row2->getValue()))?$bo_row2->getValue():'&nbsp;';



									$bp_row3 = $worksheet->getCellByColumnAndRow(67, $row); $data['bp_row3'] = (!empty($bp_row3->getValue()))?$bp_row3->getValue():'&nbsp;';



									$bq_row4 = $worksheet->getCellByColumnAndRow(68, $row); $data['bq_row4'] = (!empty($bq_row4->getValue()))?$bq_row4->getValue():'&nbsp;';



									$br_row5 = $worksheet->getCellByColumnAndRow(69, $row); $data['br_row5'] = (!empty($br_row5->getValue()))?$br_row5->getValue():'&nbsp;';



									$bs_row6 = $worksheet->getCellByColumnAndRow(70, $row); $data['bs_row6'] = (!empty($bs_row6->getValue()))?$bs_row6->getValue():'&nbsp;';



									$bt_row7 = $worksheet->getCellByColumnAndRow(71, $row); $data['bt_row7'] = (!empty($bt_row7->getValue()))?$bt_row7->getValue():'&nbsp;';



									$bu_row8 = $worksheet->getCellByColumnAndRow(72, $row); $data['bu_row8'] = (!empty($bu_row8->getValue()))?$bu_row8->getValue():'&nbsp;';



									$bv_row9 = $worksheet->getCellByColumnAndRow(73, $row); $data['bv_row9'] = (!empty($bv_row9->getValue()))?$bv_row9->getValue():'&nbsp;';



									$bw_row10 = $worksheet->getCellByColumnAndRow(74, $row); $data['bw_row10'] = (!empty($bw_row10->getValue()))?$bw_row10->getValue():'&nbsp;';



									$bx_row11 = $worksheet->getCellByColumnAndRow(75, $row); $data['bx_row11'] = (!empty($bx_row11->getValue()))?$bx_row11->getValue():'&nbsp;';



									$by_row12 = $worksheet->getCellByColumnAndRow(76, $row); $data['by_row12'] = (!empty($by_row12->getValue()))?$by_row12->getValue():'&nbsp;';



									$bz_row13 = $worksheet->getCellByColumnAndRow(77, $row); $data['bz_row13'] = (!empty($bz_row13->getValue()))?$bz_row13->getValue():'&nbsp;';



									$ca_row14 = $worksheet->getCellByColumnAndRow(78, $row); $data['ca_row14'] = (!empty($ca_row14->getValue()))?$ca_row14->getValue():'&nbsp;';



									$cb_row15 = $worksheet->getCellByColumnAndRow(79, $row); $data['cb_row15'] = (!empty($cb_row15->getValue()))?$cb_row15->getValue():'&nbsp;';



									$cc_row16 = $worksheet->getCellByColumnAndRow(80, $row); $data['cc_row16'] = (!empty($cc_row16->getValue()))?$cc_row16->getValue():'&nbsp;';



									$cd_row17 = $worksheet->getCellByColumnAndRow(81, $row); $data['cd_row17'] = (!empty($cd_row17->getValue()))?$cd_row17->getValue():'&nbsp;';



									$ce_row18 = $worksheet->getCellByColumnAndRow(82, $row); $data['ce_row18'] = (!empty($ce_row18->getValue()))?$ce_row18->getValue():'&nbsp;';



									$cf_row19 = $worksheet->getCellByColumnAndRow(83, $row); $data['cf_row19'] = (!empty($cf_row19->getValue()))?$cf_row19->getValue():'&nbsp;';



									$cg_row20 = $worksheet->getCellByColumnAndRow(84, $row); $data['cg_row20'] = (!empty($cg_row20->getValue()))?$cg_row20->getValue():'&nbsp;';







									$ch_invoice_number = $worksheet->getCellByColumnAndRow(85, $row); $data['ch_invoice_number'] = (!empty($ch_invoice_number->getValue()))?$ch_invoice_number->getValue():'&nbsp;';



									$ci_year_end = $worksheet->getCellByColumnAndRow(86, $row); $data['ci_year_end'] = (!empty($ci_year_end->getValue()))?$ci_year_end->getValue():'&nbsp;';



									$cj1 = $worksheet->getCellByColumnAndRow(87, $row); $data['cj1'] = (!empty($cj1->getValue()))?$cj1->getValue():'&nbsp;';



									$ck2 = $worksheet->getCellByColumnAndRow(88, $row); $data['ck2'] = (!empty($ck2->getValue()))?$ck2->getValue():'&nbsp;';



									$cl3 = $worksheet->getCellByColumnAndRow(89, $row); $data['cl3'] = (!empty($cl3->getValue()))?$cl3->getValue():'&nbsp;';



									$cm4 = $worksheet->getCellByColumnAndRow(90, $row); $data['cm4'] = (!empty($cm4->getValue()))?$cm4->getValue():'&nbsp;';



									$cn5 = $worksheet->getCellByColumnAndRow(91, $row); $data['cn5'] = (!empty($cn5->getValue()))?$cn5->getValue():'&nbsp;';



									$co_abridgedinc = $worksheet->getCellByColumnAndRow(92, $row); $data['co_abridgedinc'] = (!empty($co_abridgedinc->getValue()))?$co_abridgedinc->getValue():'&nbsp;';



									$cp_sor = $worksheet->getCellByColumnAndRow(93, $row); $data['cp_sor'] = (!empty($cp_sor->getValue()))?$cp_sor->getValue():'&nbsp;';



									$cq_adjnote = $worksheet->getCellByColumnAndRow(94, $row); $data['cq_adjnote'] = (!empty($cq_adjnote->getValue()))?$cq_adjnote->getValue():'&nbsp;';







									$cr_position = $worksheet->getCellByColumnAndRow(95, $row); $data['cr_position'] = (!empty($cr_position->getValue()))?$cr_position->getValue():'&nbsp;';



									$cs_liability = $worksheet->getCellByColumnAndRow(96, $row); $data['cs_liability'] = (!empty($cs_liability->getValue()))?$cs_liability->getValue():'&nbsp;';



									$ct_prelim = $worksheet->getCellByColumnAndRow(97, $row); $data['ct_prelim'] = (!empty($ct_prelim->getValue()))?$ct_prelim->getValue():'&nbsp;';



									$cu_paydate = $worksheet->getCellByColumnAndRow(98, $row); $data['cu_paydate'] = (!empty($cu_paydate->getValue()))?$cu_paydate->getValue():'&nbsp;';



									$cv_included = $worksheet->getCellByColumnAndRow(99, $row); $data['cv_included'] = (!empty($cv_included->getValue()))?$cv_included->getValue():'&nbsp;';



									$cw_liability = $worksheet->getCellByColumnAndRow(100, $row); $data['cw_liability'] = (!empty($cw_liability->getValue()))?$cw_liability->getValue():'&nbsp;';



									$cx_prelim = $worksheet->getCellByColumnAndRow(101, $row); $data['cx_prelim'] = (!empty($cx_prelim->getValue()))?$cx_prelim->getValue():'&nbsp;';



									$cy_paydate = $worksheet->getCellByColumnAndRow(102, $row); $data['cy_paydate'] = (!empty($cy_paydate->getValue()))?$cy_paydate->getValue():'&nbsp;';



									$cz_invoice = $worksheet->getCellByColumnAndRow(103, $row); $data['cz_invoice'] = (!empty($cz_invoice->getValue()))?$cz_invoice->getValue():'&nbsp;';



									$da_blank1 = $worksheet->getCellByColumnAndRow(104, $row); $data['da_blank1'] = (!empty($da_blank1->getValue()))?$da_blank1->getValue():'&nbsp;';



									$db_blank2 = $worksheet->getCellByColumnAndRow(105, $row); $data['db_blank2'] = (!empty($db_blank2->getValue()))?$db_blank2->getValue():'&nbsp;';



									$dc_blank3 = $worksheet->getCellByColumnAndRow(106, $row); $data['dc_blank3'] = (!empty($dc_blank3->getValue()))?$dc_blank3->getValue():'&nbsp;';



									$statement = $worksheet->getCellByColumnAndRow(107, $row); $data['statement'] = (!empty($statement->getValue()))?$statement->getValue():'&nbsp;';







									







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
						$explode_hyphen_invoice_date = explode("-",$invoice_date);
						if(count($explode_invoice_date) == 3)
						{
							$inc_date = $explode_invoice_date[0].'-'.$explode_invoice_date[1].'-'.$explode_invoice_date[2];
							$data['invoice_date'] = date('Y-m-d',strtotime($inc_date));

						}
						elseif(count($explode_hyphen_invoice_date) == 3){
							$data['invoice_date'] = date('Y-m-d',strtotime($invoice_date));
						}
						else{
							$unix_date = ($invoice_date - 25569) * 86400;
							$excel_date = 25569 + ($unix_date / 86400);
							$unix_date = ($excel_date - 25569) * 86400;
							$data['invoice_date'] = gmdate("Y-m-d", $unix_date);
						}



						$client = $worksheet->getCellByColumnAndRow(2, $row); $data['client_id'] = trim($client->getValue());



						$invoice_net = $worksheet->getCellByColumnAndRow(3, $row); $data['inv_net'] = str_replace(',', '', trim($invoice_net->getValue()));



						$invoice_vatrate = $worksheet->getCellByColumnAndRow(4, $row); $invoice_vatrate = trim($invoice_vatrate->getValue());











						$f_row1 = $worksheet->getCellByColumnAndRow(5, $row); $data['f_row1'] = (!empty($f_row1->getValue()))?$f_row1->getValue():'&nbsp;';



						$g_row2 = $worksheet->getCellByColumnAndRow(6, $row); $data['g_row2'] = (!empty($g_row2->getValue()))?$g_row2->getValue():'&nbsp;';



						$h_row3 = $worksheet->getCellByColumnAndRow(7, $row); $data['h_row3'] = (!empty($h_row3->getValue()))?$h_row3->getValue():'&nbsp;';



						$i_row4 = $worksheet->getCellByColumnAndRow(8, $row); $data['i_row4'] = (!empty($i_row4->getValue()))?$i_row4->getValue():'&nbsp;';



						$j_row5 = $worksheet->getCellByColumnAndRow(9, $row); $data['j_row5'] = (!empty($j_row5->getValue()))?$j_row5->getValue():'&nbsp;';



						$k_row6 = $worksheet->getCellByColumnAndRow(10, $row); $data['k_row6'] = (!empty($k_row6->getValue()))?$k_row6->getValue():'&nbsp;';



						$l_row7 = $worksheet->getCellByColumnAndRow(11, $row); $data['l_row7'] = (!empty($l_row7->getValue()))?$l_row7->getValue():'&nbsp;';



						$m_row8 = $worksheet->getCellByColumnAndRow(12, $row); $data['m_row8'] = (!empty($m_row8->getValue()))?$m_row8->getValue():'&nbsp;';



						$n_row9 = $worksheet->getCellByColumnAndRow(13, $row); $data['n_row9'] = (!empty($n_row9->getValue()))?$n_row9->getValue():'&nbsp;';



						$o_row10 = $worksheet->getCellByColumnAndRow(14, $row); $data['o_row10'] = (!empty($o_row10->getValue()))?$o_row10->getValue():'&nbsp;';



						$p_row11 = $worksheet->getCellByColumnAndRow(15, $row); $data['p_row11'] = (!empty($p_row11->getValue()))?$p_row11->getValue():'&nbsp;';



						$q_row12 = $worksheet->getCellByColumnAndRow(16, $row); $data['q_row12'] = (!empty($q_row12->getValue()))?$q_row12->getValue():'&nbsp;';



						$r_row13 = $worksheet->getCellByColumnAndRow(17, $row); $data['r_row13'] = (!empty($r_row13->getValue()))?$r_row13->getValue():'&nbsp;';



						$s_row14 = $worksheet->getCellByColumnAndRow(18, $row); $data['s_row14'] = (!empty($s_row14->getValue()))?$s_row14->getValue():'&nbsp;';



						$t_row15 = $worksheet->getCellByColumnAndRow(19, $row); $data['t_row15'] = (!empty($t_row15->getValue()))?$t_row15->getValue():'&nbsp;';



						$u_row16 = $worksheet->getCellByColumnAndRow(20, $row); $data['u_row16'] = (!empty($u_row16->getValue()))?$u_row16->getValue():'&nbsp;';



						$v_row17 = $worksheet->getCellByColumnAndRow(21, $row); $data['v_row17'] = (!empty($v_row17->getValue()))?$v_row17->getValue():'&nbsp;';



						$w_row18 = $worksheet->getCellByColumnAndRow(22, $row); $data['w_row18'] = (!empty($w_row18->getValue()))?$w_row18->getValue():'&nbsp;';



						$x_row19 = $worksheet->getCellByColumnAndRow(23, $row); $data['x_row19'] = (!empty($x_row19->getValue()))?$x_row19->getValue():'&nbsp;';



						$y_row20 = $worksheet->getCellByColumnAndRow(24, $row); $data['y_row20'] = (!empty($y_row20->getValue()))?$y_row20->getValue():'&nbsp;';







						$z_row1 = $worksheet->getCellByColumnAndRow(25, $row); $data['z_row1'] = (!empty($z_row1->getValue()))?$z_row1->getValue():'&nbsp;';



						$aa_row2 = $worksheet->getCellByColumnAndRow(26, $row); $data['aa_row2'] = (!empty($aa_row2->getValue()))?$aa_row2->getValue():'&nbsp;';



						$ab_row3 = $worksheet->getCellByColumnAndRow(27, $row); $data['ab_row3'] = (!empty($ab_row3->getValue()))?$ab_row3->getValue():'&nbsp;';



						$ac_row4 = $worksheet->getCellByColumnAndRow(28, $row); $data['ac_row4'] = (!empty($ac_row4->getValue()))?$ac_row4->getValue():'&nbsp;';



						$ad_row5 = $worksheet->getCellByColumnAndRow(29, $row); $data['ad_row5'] = (!empty($ad_row5->getValue()))?$ad_row5->getValue():'&nbsp;';



						$ae_row6 = $worksheet->getCellByColumnAndRow(30, $row); $data['ae_row6'] = (!empty($ae_row6->getValue()))?$ae_row6->getValue():'&nbsp;';



						$af_row7 = $worksheet->getCellByColumnAndRow(31, $row); $data['af_row7'] = (!empty($af_row7->getValue()))?$af_row7->getValue():'&nbsp;';



						$ag_row8 = $worksheet->getCellByColumnAndRow(32, $row); $data['ag_row8'] = (!empty($ag_row8->getValue()))?$ag_row8->getValue():'&nbsp;';



						$ah_row9 = $worksheet->getCellByColumnAndRow(33, $row); $data['ah_row9'] = (!empty($ah_row9->getValue()))?$ah_row9->getValue():'&nbsp;';



						$ai_row10 = $worksheet->getCellByColumnAndRow(34, $row); $data['ai_row10'] = (!empty($ai_row10->getValue()))?$ai_row10->getValue():'&nbsp;';



						$aj_row11 = $worksheet->getCellByColumnAndRow(35, $row); $data['aj_row11'] = (!empty($aj_row11->getValue()))?$aj_row11->getValue():'&nbsp;';



						$ak_row12 = $worksheet->getCellByColumnAndRow(36, $row); $data['ak_row12'] = (!empty($ak_row12->getValue()))?$ak_row12->getValue():'&nbsp;';



						$al_row13 = $worksheet->getCellByColumnAndRow(37, $row); $data['al_row13'] = (!empty($al_row13->getValue()))?$al_row13->getValue():'&nbsp;';



						$am_row14 = $worksheet->getCellByColumnAndRow(38, $row); $data['am_row14'] = (!empty($am_row14->getValue()))?$am_row14->getValue():'&nbsp;';



						$an_row15 = $worksheet->getCellByColumnAndRow(39, $row); $data['an_row15'] = (!empty($an_row15->getValue()))?$an_row15->getValue():'&nbsp;';



						$ao_row16 = $worksheet->getCellByColumnAndRow(40, $row); $data['ao_row16'] = (!empty($ao_row16->getValue()))?$ao_row16->getValue():'&nbsp;';



						$ap_row17 = $worksheet->getCellByColumnAndRow(41, $row); $data['ap_row17'] = (!empty($ap_row17->getValue()))?$ap_row17->getValue():'&nbsp;';



						$aq_row18 = $worksheet->getCellByColumnAndRow(42, $row); $data['aq_row18'] = (!empty($aq_row18->getValue()))?$aq_row18->getValue():'&nbsp;';



						$ar_row19 = $worksheet->getCellByColumnAndRow(43, $row); $data['ar_row19'] = (!empty($ar_row19->getValue()))?$ar_row19->getValue():'&nbsp;';



						$as_row20 = $worksheet->getCellByColumnAndRow(44, $row); $data['as_row20'] = (!empty($as_row20->getValue()))?$as_row20->getValue():'&nbsp;';











						$at_row1 = $worksheet->getCellByColumnAndRow(45, $row); $data['at_row1'] = (!empty($at_row1->getValue()))?$at_row1->getValue():'&nbsp;';



						$au_row2 = $worksheet->getCellByColumnAndRow(46, $row); $data['au_row2'] = (!empty($au_row2->getValue()))?$au_row2->getValue():'&nbsp;';



						$av_row3 = $worksheet->getCellByColumnAndRow(47, $row); $data['av_row3'] = (!empty($av_row3->getValue()))?$av_row3->getValue():'&nbsp;';



						$aw_row4 = $worksheet->getCellByColumnAndRow(48, $row); $data['aw_row4'] = (!empty($aw_row4->getValue()))?$aw_row4->getValue():'&nbsp;';



						$ax_row5 = $worksheet->getCellByColumnAndRow(49, $row); $data['ax_row5'] = (!empty($ax_row5->getValue()))?$ax_row5->getValue():'&nbsp;';



						$ay_row6 = $worksheet->getCellByColumnAndRow(50, $row); $data['ay_row6'] = (!empty($ay_row6->getValue()))?$ay_row6->getValue():'&nbsp;';



						$az_row7 = $worksheet->getCellByColumnAndRow(51, $row); $data['az_row7'] = (!empty($az_row7->getValue()))?$az_row7->getValue():'&nbsp;';



						$ba_row8 = $worksheet->getCellByColumnAndRow(52, $row); $data['ba_row8'] = (!empty($ba_row8->getValue()))?$ba_row8->getValue():'&nbsp;';



						$bb_row9 = $worksheet->getCellByColumnAndRow(53, $row); $data['bb_row9'] = (!empty($bb_row9->getValue()))?$bb_row9->getValue():'&nbsp;';



						$bc_row10 = $worksheet->getCellByColumnAndRow(54, $row); $data['bc_row10'] = (!empty($bc_row10->getValue()))?$bc_row10->getValue():'&nbsp;';



						$bd_row11 = $worksheet->getCellByColumnAndRow(55, $row); $data['bd_row11'] = (!empty($bd_row11->getValue()))?$bd_row11->getValue():'&nbsp;';



						$be_row12 = $worksheet->getCellByColumnAndRow(56, $row); $data['be_row12'] = (!empty($be_row12->getValue()))?$be_row12->getValue():'&nbsp;';



						$bf_row13 = $worksheet->getCellByColumnAndRow(57, $row); $data['bf_row13'] = (!empty($bf_row13->getValue()))?$bf_row13->getValue():'&nbsp;';



						$bg_row14 = $worksheet->getCellByColumnAndRow(58, $row); $data['bg_row14'] = (!empty($bg_row14->getValue()))?$bg_row14->getValue():'&nbsp;';



						$bh_row15 = $worksheet->getCellByColumnAndRow(59, $row); $data['bh_row15'] = (!empty($bh_row15->getValue()))?$bh_row15->getValue():'&nbsp;';



						$bi_row16 = $worksheet->getCellByColumnAndRow(60, $row); $data['bi_row16'] = (!empty($bi_row16->getValue()))?$bi_row16->getValue():'&nbsp;';



						$bj_row17 = $worksheet->getCellByColumnAndRow(61, $row); $data['bj_row17'] = (!empty($bj_row17->getValue()))?$bj_row17->getValue():'&nbsp;';



						$bk_row18 = $worksheet->getCellByColumnAndRow(62, $row); $data['bk_row18'] = (!empty($bk_row18->getValue()))?$bk_row18->getValue():'&nbsp;';



						$bl_row19 = $worksheet->getCellByColumnAndRow(63, $row); $data['bl_row19'] = (!empty($bl_row19->getValue()))?$bl_row19->getValue():'&nbsp;';



						$bm_row20 = $worksheet->getCellByColumnAndRow(64, $row); $data['bm_row20'] = (!empty($bm_row20->getValue()))?$bm_row20->getValue():'&nbsp;';







						$bn_row1 = $worksheet->getCellByColumnAndRow(65, $row); $data['bn_row1'] = (!empty($bn_row1->getValue()))?$bn_row1->getValue():'&nbsp;';



						$bo_row2 = $worksheet->getCellByColumnAndRow(66, $row); $data['bo_row2'] = (!empty($bo_row2->getValue()))?$bo_row2->getValue():'&nbsp;';



						$bp_row3 = $worksheet->getCellByColumnAndRow(67, $row); $data['bp_row3'] = (!empty($bp_row3->getValue()))?$bp_row3->getValue():'&nbsp;';



						$bq_row4 = $worksheet->getCellByColumnAndRow(68, $row); $data['bq_row4'] = (!empty($bq_row4->getValue()))?$bq_row4->getValue():'&nbsp;';



						$br_row5 = $worksheet->getCellByColumnAndRow(69, $row); $data['br_row5'] = (!empty($br_row5->getValue()))?$br_row5->getValue():'&nbsp;';



						$bs_row6 = $worksheet->getCellByColumnAndRow(70, $row); $data['bs_row6'] = (!empty($bs_row6->getValue()))?$bs_row6->getValue():'&nbsp;';



						$bt_row7 = $worksheet->getCellByColumnAndRow(71, $row); $data['bt_row7'] = (!empty($bt_row7->getValue()))?$bt_row7->getValue():'&nbsp;';



						$bu_row8 = $worksheet->getCellByColumnAndRow(72, $row); $data['bu_row8'] = (!empty($bu_row8->getValue()))?$bu_row8->getValue():'&nbsp;';



						$bv_row9 = $worksheet->getCellByColumnAndRow(73, $row); $data['bv_row9'] = (!empty($bv_row9->getValue()))?$bv_row9->getValue():'&nbsp;';



						$bw_row10 = $worksheet->getCellByColumnAndRow(74, $row); $data['bw_row10'] = (!empty($bw_row10->getValue()))?$bw_row10->getValue():'&nbsp;';



						$bx_row11 = $worksheet->getCellByColumnAndRow(75, $row); $data['bx_row11'] = (!empty($bx_row11->getValue()))?$bx_row11->getValue():'&nbsp;';



						$by_row12 = $worksheet->getCellByColumnAndRow(76, $row); $data['by_row12'] = (!empty($by_row12->getValue()))?$by_row12->getValue():'&nbsp;';



						$bz_row13 = $worksheet->getCellByColumnAndRow(77, $row); $data['bz_row13'] = (!empty($bz_row13->getValue()))?$bz_row13->getValue():'&nbsp;';



						$ca_row14 = $worksheet->getCellByColumnAndRow(78, $row); $data['ca_row14'] = (!empty($ca_row14->getValue()))?$ca_row14->getValue():'&nbsp;';



						$cb_row15 = $worksheet->getCellByColumnAndRow(79, $row); $data['cb_row15'] = (!empty($cb_row15->getValue()))?$cb_row15->getValue():'&nbsp;';



						$cc_row16 = $worksheet->getCellByColumnAndRow(80, $row); $data['cc_row16'] = (!empty($cc_row16->getValue()))?$cc_row16->getValue():'&nbsp;';



						$cd_row17 = $worksheet->getCellByColumnAndRow(81, $row); $data['cd_row17'] = (!empty($cd_row17->getValue()))?$cd_row17->getValue():'&nbsp;';



						$ce_row18 = $worksheet->getCellByColumnAndRow(82, $row); $data['ce_row18'] = (!empty($ce_row18->getValue()))?$ce_row18->getValue():'&nbsp;';



						$cf_row19 = $worksheet->getCellByColumnAndRow(83, $row); $data['cf_row19'] = (!empty($cf_row19->getValue()))?$cf_row19->getValue():'&nbsp;';



						$cg_row20 = $worksheet->getCellByColumnAndRow(84, $row); $data['cg_row20'] = (!empty($cg_row20->getValue()))?$cg_row20->getValue():'&nbsp;';







						$ch_invoice_number = $worksheet->getCellByColumnAndRow(85, $row); $data['ch_invoice_number'] = (!empty($ch_invoice_number->getValue()))?$ch_invoice_number->getValue():'&nbsp;';



						$ci_year_end = $worksheet->getCellByColumnAndRow(86, $row); $data['ci_year_end'] = (!empty($ci_year_end->getValue()))?$ci_year_end->getValue():'&nbsp;';



						$cj1 = $worksheet->getCellByColumnAndRow(87, $row); $data['cj1'] = (!empty($cj1->getValue()))?$cj1->getValue():'&nbsp;';



						$ck2 = $worksheet->getCellByColumnAndRow(88, $row); $data['ck2'] = (!empty($ck2->getValue()))?$ck2->getValue():'&nbsp;';



						$cl3 = $worksheet->getCellByColumnAndRow(89, $row); $data['cl3'] = (!empty($cl3->getValue()))?$cl3->getValue():'&nbsp;';



						$cm4 = $worksheet->getCellByColumnAndRow(90, $row); $data['cm4'] = (!empty($cm4->getValue()))?$cm4->getValue():'&nbsp;';



						$cn5 = $worksheet->getCellByColumnAndRow(91, $row); $data['cn5'] = (!empty($cn5->getValue()))?$cn5->getValue():'&nbsp;';



						$co_abridgedinc = $worksheet->getCellByColumnAndRow(92, $row); $data['co_abridgedinc'] = (!empty($co_abridgedinc->getValue()))?$co_abridgedinc->getValue():'&nbsp;';



						$cp_sor = $worksheet->getCellByColumnAndRow(93, $row); $data['cp_sor'] = (!empty($cp_sor->getValue()))?$cp_sor->getValue():'&nbsp;';



						$cq_adjnote = $worksheet->getCellByColumnAndRow(94, $row); $data['cq_adjnote'] = (!empty($cq_adjnote->getValue()))?$cq_adjnote->getValue():'&nbsp;';







						$cr_position = $worksheet->getCellByColumnAndRow(95, $row); $data['cr_position'] = (!empty($cr_position->getValue()))?$cr_position->getValue():'&nbsp;';



						$cs_liability = $worksheet->getCellByColumnAndRow(96, $row); $data['cs_liability'] = (!empty($cs_liability->getValue()))?$cs_liability->getValue():'&nbsp;';



						$ct_prelim = $worksheet->getCellByColumnAndRow(97, $row); $data['ct_prelim'] = (!empty($ct_prelim->getValue()))?$ct_prelim->getValue():'&nbsp;';



						$cu_paydate = $worksheet->getCellByColumnAndRow(98, $row); $data['cu_paydate'] = (!empty($cu_paydate->getValue()))?$cu_paydate->getValue():'&nbsp;';



						$cv_included = $worksheet->getCellByColumnAndRow(99, $row); $data['cv_included'] = (!empty($cv_included->getValue()))?$cv_included->getValue():'&nbsp;';



						$cw_liability = $worksheet->getCellByColumnAndRow(100, $row); $data['cw_liability'] = (!empty($cw_liability->getValue()))?$cw_liability->getValue():'&nbsp;';



						$cx_prelim = $worksheet->getCellByColumnAndRow(101, $row); $data['cx_prelim'] = (!empty($cx_prelim->getValue()))?$cx_prelim->getValue():'&nbsp;';



						$cy_paydate = $worksheet->getCellByColumnAndRow(102, $row); $data['cy_paydate'] = (!empty($cy_paydate->getValue()))?$cy_paydate->getValue():'&nbsp;';



						$cz_invoice = $worksheet->getCellByColumnAndRow(103, $row); $data['cz_invoice'] = (!empty($cz_invoice->getValue()))?$cz_invoice->getValue():'&nbsp;';



						$da_blank1 = $worksheet->getCellByColumnAndRow(104, $row); $data['da_blank1'] = (!empty($da_blank1->getValue()))?$da_blank1->getValue():'&nbsp;';



						$db_blank2 = $worksheet->getCellByColumnAndRow(105, $row); $data['db_blank2'] = (!empty($db_blank2->getValue()))?$db_blank2->getValue():'&nbsp;';



						$dc_blank3 = $worksheet->getCellByColumnAndRow(106, $row); $data['dc_blank3'] = (!empty($dc_blank3->getValue()))?$dc_blank3->getValue():'&nbsp;';



						$statement = $worksheet->getCellByColumnAndRow(107, $row); $data['statement'] = (!empty($statement->getValue()))?$statement->getValue():'&nbsp;';







						







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
						$explode_hyphen_invoice_date = explode("-",$invoice_date);
						if(count($explode_invoice_date) == 3)
						{
							$inc_date = $explode_invoice_date[0].'-'.$explode_invoice_date[1].'-'.$explode_invoice_date[2];
							$data['invoice_date'] = date('Y-m-d',strtotime($inc_date));

						}
						elseif(count($explode_hyphen_invoice_date) == 3){
							$data['invoice_date'] = date('Y-m-d',strtotime($invoice_date));
						}
						else{
							$unix_date = ($invoice_date - 25569) * 86400;
							$excel_date = 25569 + ($unix_date / 86400);
							$unix_date = ($excel_date - 25569) * 86400;
							$data['invoice_date'] = gmdate("Y-m-d", $unix_date);
						}



						$client = $worksheet->getCellByColumnAndRow(2, $row); $data['client_id'] = trim($client->getValue());



						$invoice_net = $worksheet->getCellByColumnAndRow(3, $row); $data['inv_net'] = str_replace(',', '', trim($invoice_net->getValue()));



						$invoice_vatrate = $worksheet->getCellByColumnAndRow(4, $row); $invoice_vatrate = trim($invoice_vatrate->getValue());











						$f_row1 = $worksheet->getCellByColumnAndRow(5, $row); $data['f_row1'] = (!empty($f_row1->getValue()))?$f_row1->getValue():'&nbsp;';



						$g_row2 = $worksheet->getCellByColumnAndRow(6, $row); $data['g_row2'] = (!empty($g_row2->getValue()))?$g_row2->getValue():'&nbsp;';



						$h_row3 = $worksheet->getCellByColumnAndRow(7, $row); $data['h_row3'] = (!empty($h_row3->getValue()))?$h_row3->getValue():'&nbsp;';



						$i_row4 = $worksheet->getCellByColumnAndRow(8, $row); $data['i_row4'] = (!empty($i_row4->getValue()))?$i_row4->getValue():'&nbsp;';



						$j_row5 = $worksheet->getCellByColumnAndRow(9, $row); $data['j_row5'] = (!empty($j_row5->getValue()))?$j_row5->getValue():'&nbsp;';



						$k_row6 = $worksheet->getCellByColumnAndRow(10, $row); $data['k_row6'] = (!empty($k_row6->getValue()))?$k_row6->getValue():'&nbsp;';



						$l_row7 = $worksheet->getCellByColumnAndRow(11, $row); $data['l_row7'] = (!empty($l_row7->getValue()))?$l_row7->getValue():'&nbsp;';



						$m_row8 = $worksheet->getCellByColumnAndRow(12, $row); $data['m_row8'] = (!empty($m_row8->getValue()))?$m_row8->getValue():'&nbsp;';



						$n_row9 = $worksheet->getCellByColumnAndRow(13, $row); $data['n_row9'] = (!empty($n_row9->getValue()))?$n_row9->getValue():'&nbsp;';



						$o_row10 = $worksheet->getCellByColumnAndRow(14, $row); $data['o_row10'] = (!empty($o_row10->getValue()))?$o_row10->getValue():'&nbsp;';



						$p_row11 = $worksheet->getCellByColumnAndRow(15, $row); $data['p_row11'] = (!empty($p_row11->getValue()))?$p_row11->getValue():'&nbsp;';



						$q_row12 = $worksheet->getCellByColumnAndRow(16, $row); $data['q_row12'] = (!empty($q_row12->getValue()))?$q_row12->getValue():'&nbsp;';



						$r_row13 = $worksheet->getCellByColumnAndRow(17, $row); $data['r_row13'] = (!empty($r_row13->getValue()))?$r_row13->getValue():'&nbsp;';



						$s_row14 = $worksheet->getCellByColumnAndRow(18, $row); $data['s_row14'] = (!empty($s_row14->getValue()))?$s_row14->getValue():'&nbsp;';



						$t_row15 = $worksheet->getCellByColumnAndRow(19, $row); $data['t_row15'] = (!empty($t_row15->getValue()))?$t_row15->getValue():'&nbsp;';



						$u_row16 = $worksheet->getCellByColumnAndRow(20, $row); $data['u_row16'] = (!empty($u_row16->getValue()))?$u_row16->getValue():'&nbsp;';



						$v_row17 = $worksheet->getCellByColumnAndRow(21, $row); $data['v_row17'] = (!empty($v_row17->getValue()))?$v_row17->getValue():'&nbsp;';



						$w_row18 = $worksheet->getCellByColumnAndRow(22, $row); $data['w_row18'] = (!empty($w_row18->getValue()))?$w_row18->getValue():'&nbsp;';



						$x_row19 = $worksheet->getCellByColumnAndRow(23, $row); $data['x_row19'] = (!empty($x_row19->getValue()))?$x_row19->getValue():'&nbsp;';



						$y_row20 = $worksheet->getCellByColumnAndRow(24, $row); $data['y_row20'] = (!empty($y_row20->getValue()))?$y_row20->getValue():'&nbsp;';







						$z_row1 = $worksheet->getCellByColumnAndRow(25, $row); $data['z_row1'] = (!empty($z_row1->getValue()))?$z_row1->getValue():'&nbsp;';



						$aa_row2 = $worksheet->getCellByColumnAndRow(26, $row); $data['aa_row2'] = (!empty($aa_row2->getValue()))?$aa_row2->getValue():'&nbsp;';



						$ab_row3 = $worksheet->getCellByColumnAndRow(27, $row); $data['ab_row3'] = (!empty($ab_row3->getValue()))?$ab_row3->getValue():'&nbsp;';



						$ac_row4 = $worksheet->getCellByColumnAndRow(28, $row); $data['ac_row4'] = (!empty($ac_row4->getValue()))?$ac_row4->getValue():'&nbsp;';



						$ad_row5 = $worksheet->getCellByColumnAndRow(29, $row); $data['ad_row5'] = (!empty($ad_row5->getValue()))?$ad_row5->getValue():'&nbsp;';



						$ae_row6 = $worksheet->getCellByColumnAndRow(30, $row); $data['ae_row6'] = (!empty($ae_row6->getValue()))?$ae_row6->getValue():'&nbsp;';



						$af_row7 = $worksheet->getCellByColumnAndRow(31, $row); $data['af_row7'] = (!empty($af_row7->getValue()))?$af_row7->getValue():'&nbsp;';



						$ag_row8 = $worksheet->getCellByColumnAndRow(32, $row); $data['ag_row8'] = (!empty($ag_row8->getValue()))?$ag_row8->getValue():'&nbsp;';



						$ah_row9 = $worksheet->getCellByColumnAndRow(33, $row); $data['ah_row9'] = (!empty($ah_row9->getValue()))?$ah_row9->getValue():'&nbsp;';



						$ai_row10 = $worksheet->getCellByColumnAndRow(34, $row); $data['ai_row10'] = (!empty($ai_row10->getValue()))?$ai_row10->getValue():'&nbsp;';



						$aj_row11 = $worksheet->getCellByColumnAndRow(35, $row); $data['aj_row11'] = (!empty($aj_row11->getValue()))?$aj_row11->getValue():'&nbsp;';



						$ak_row12 = $worksheet->getCellByColumnAndRow(36, $row); $data['ak_row12'] = (!empty($ak_row12->getValue()))?$ak_row12->getValue():'&nbsp;';



						$al_row13 = $worksheet->getCellByColumnAndRow(37, $row); $data['al_row13'] = (!empty($al_row13->getValue()))?$al_row13->getValue():'&nbsp;';



						$am_row14 = $worksheet->getCellByColumnAndRow(38, $row); $data['am_row14'] = (!empty($am_row14->getValue()))?$am_row14->getValue():'&nbsp;';



						$an_row15 = $worksheet->getCellByColumnAndRow(39, $row); $data['an_row15'] = (!empty($an_row15->getValue()))?$an_row15->getValue():'&nbsp;';



						$ao_row16 = $worksheet->getCellByColumnAndRow(40, $row); $data['ao_row16'] = (!empty($ao_row16->getValue()))?$ao_row16->getValue():'&nbsp;';



						$ap_row17 = $worksheet->getCellByColumnAndRow(41, $row); $data['ap_row17'] = (!empty($ap_row17->getValue()))?$ap_row17->getValue():'&nbsp;';



						$aq_row18 = $worksheet->getCellByColumnAndRow(42, $row); $data['aq_row18'] = (!empty($aq_row18->getValue()))?$aq_row18->getValue():'&nbsp;';



						$ar_row19 = $worksheet->getCellByColumnAndRow(43, $row); $data['ar_row19'] = (!empty($ar_row19->getValue()))?$ar_row19->getValue():'&nbsp;';



						$as_row20 = $worksheet->getCellByColumnAndRow(44, $row); $data['as_row20'] = (!empty($as_row20->getValue()))?$as_row20->getValue():'&nbsp;';











						$at_row1 = $worksheet->getCellByColumnAndRow(45, $row); $data['at_row1'] = (!empty($at_row1->getValue()))?$at_row1->getValue():'&nbsp;';



						$au_row2 = $worksheet->getCellByColumnAndRow(46, $row); $data['au_row2'] = (!empty($au_row2->getValue()))?$au_row2->getValue():'&nbsp;';



						$av_row3 = $worksheet->getCellByColumnAndRow(47, $row); $data['av_row3'] = (!empty($av_row3->getValue()))?$av_row3->getValue():'&nbsp;';



						$aw_row4 = $worksheet->getCellByColumnAndRow(48, $row); $data['aw_row4'] = (!empty($aw_row4->getValue()))?$aw_row4->getValue():'&nbsp;';



						$ax_row5 = $worksheet->getCellByColumnAndRow(49, $row); $data['ax_row5'] = (!empty($ax_row5->getValue()))?$ax_row5->getValue():'&nbsp;';



						$ay_row6 = $worksheet->getCellByColumnAndRow(50, $row); $data['ay_row6'] = (!empty($ay_row6->getValue()))?$ay_row6->getValue():'&nbsp;';



						$az_row7 = $worksheet->getCellByColumnAndRow(51, $row); $data['az_row7'] = (!empty($az_row7->getValue()))?$az_row7->getValue():'&nbsp;';



						$ba_row8 = $worksheet->getCellByColumnAndRow(52, $row); $data['ba_row8'] = (!empty($ba_row8->getValue()))?$ba_row8->getValue():'&nbsp;';



						$bb_row9 = $worksheet->getCellByColumnAndRow(53, $row); $data['bb_row9'] = (!empty($bb_row9->getValue()))?$bb_row9->getValue():'&nbsp;';



						$bc_row10 = $worksheet->getCellByColumnAndRow(54, $row); $data['bc_row10'] = (!empty($bc_row10->getValue()))?$bc_row10->getValue():'&nbsp;';



						$bd_row11 = $worksheet->getCellByColumnAndRow(55, $row); $data['bd_row11'] = (!empty($bd_row11->getValue()))?$bd_row11->getValue():'&nbsp;';



						$be_row12 = $worksheet->getCellByColumnAndRow(56, $row); $data['be_row12'] = (!empty($be_row12->getValue()))?$be_row12->getValue():'&nbsp;';



						$bf_row13 = $worksheet->getCellByColumnAndRow(57, $row); $data['bf_row13'] = (!empty($bf_row13->getValue()))?$bf_row13->getValue():'&nbsp;';



						$bg_row14 = $worksheet->getCellByColumnAndRow(58, $row); $data['bg_row14'] = (!empty($bg_row14->getValue()))?$bg_row14->getValue():'&nbsp;';



						$bh_row15 = $worksheet->getCellByColumnAndRow(59, $row); $data['bh_row15'] = (!empty($bh_row15->getValue()))?$bh_row15->getValue():'&nbsp;';



						$bi_row16 = $worksheet->getCellByColumnAndRow(60, $row); $data['bi_row16'] = (!empty($bi_row16->getValue()))?$bi_row16->getValue():'&nbsp;';



						$bj_row17 = $worksheet->getCellByColumnAndRow(61, $row); $data['bj_row17'] = (!empty($bj_row17->getValue()))?$bj_row17->getValue():'&nbsp;';



						$bk_row18 = $worksheet->getCellByColumnAndRow(62, $row); $data['bk_row18'] = (!empty($bk_row18->getValue()))?$bk_row18->getValue():'&nbsp;';



						$bl_row19 = $worksheet->getCellByColumnAndRow(63, $row); $data['bl_row19'] = (!empty($bl_row19->getValue()))?$bl_row19->getValue():'&nbsp;';



						$bm_row20 = $worksheet->getCellByColumnAndRow(64, $row); $data['bm_row20'] = (!empty($bm_row20->getValue()))?$bm_row20->getValue():'&nbsp;';







						$bn_row1 = $worksheet->getCellByColumnAndRow(65, $row); $data['bn_row1'] = (!empty($bn_row1->getValue()))?$bn_row1->getValue():'&nbsp;';



						$bo_row2 = $worksheet->getCellByColumnAndRow(66, $row); $data['bo_row2'] = (!empty($bo_row2->getValue()))?$bo_row2->getValue():'&nbsp;';



						$bp_row3 = $worksheet->getCellByColumnAndRow(67, $row); $data['bp_row3'] = (!empty($bp_row3->getValue()))?$bp_row3->getValue():'&nbsp;';



						$bq_row4 = $worksheet->getCellByColumnAndRow(68, $row); $data['bq_row4'] = (!empty($bq_row4->getValue()))?$bq_row4->getValue():'&nbsp;';



						$br_row5 = $worksheet->getCellByColumnAndRow(69, $row); $data['br_row5'] = (!empty($br_row5->getValue()))?$br_row5->getValue():'&nbsp;';



						$bs_row6 = $worksheet->getCellByColumnAndRow(70, $row); $data['bs_row6'] = (!empty($bs_row6->getValue()))?$bs_row6->getValue():'&nbsp;';



						$bt_row7 = $worksheet->getCellByColumnAndRow(71, $row); $data['bt_row7'] = (!empty($bt_row7->getValue()))?$bt_row7->getValue():'&nbsp;';



						$bu_row8 = $worksheet->getCellByColumnAndRow(72, $row); $data['bu_row8'] = (!empty($bu_row8->getValue()))?$bu_row8->getValue():'&nbsp;';



						$bv_row9 = $worksheet->getCellByColumnAndRow(73, $row); $data['bv_row9'] = (!empty($bv_row9->getValue()))?$bv_row9->getValue():'&nbsp;';



						$bw_row10 = $worksheet->getCellByColumnAndRow(74, $row); $data['bw_row10'] = (!empty($bw_row10->getValue()))?$bw_row10->getValue():'&nbsp;';



						$bx_row11 = $worksheet->getCellByColumnAndRow(75, $row); $data['bx_row11'] = (!empty($bx_row11->getValue()))?$bx_row11->getValue():'&nbsp;';



						$by_row12 = $worksheet->getCellByColumnAndRow(76, $row); $data['by_row12'] = (!empty($by_row12->getValue()))?$by_row12->getValue():'&nbsp;';



						$bz_row13 = $worksheet->getCellByColumnAndRow(77, $row); $data['bz_row13'] = (!empty($bz_row13->getValue()))?$bz_row13->getValue():'&nbsp;';



						$ca_row14 = $worksheet->getCellByColumnAndRow(78, $row); $data['ca_row14'] = (!empty($ca_row14->getValue()))?$ca_row14->getValue():'&nbsp;';



						$cb_row15 = $worksheet->getCellByColumnAndRow(79, $row); $data['cb_row15'] = (!empty($cb_row15->getValue()))?$cb_row15->getValue():'&nbsp;';



						$cc_row16 = $worksheet->getCellByColumnAndRow(80, $row); $data['cc_row16'] = (!empty($cc_row16->getValue()))?$cc_row16->getValue():'&nbsp;';



						$cd_row17 = $worksheet->getCellByColumnAndRow(81, $row); $data['cd_row17'] = (!empty($cd_row17->getValue()))?$cd_row17->getValue():'&nbsp;';



						$ce_row18 = $worksheet->getCellByColumnAndRow(82, $row); $data['ce_row18'] = (!empty($ce_row18->getValue()))?$ce_row18->getValue():'&nbsp;';



						$cf_row19 = $worksheet->getCellByColumnAndRow(83, $row); $data['cf_row19'] = (!empty($cf_row19->getValue()))?$cf_row19->getValue():'&nbsp;';



						$cg_row20 = $worksheet->getCellByColumnAndRow(84, $row); $data['cg_row20'] = (!empty($cg_row20->getValue()))?$cg_row20->getValue():'&nbsp;';







						$ch_invoice_number = $worksheet->getCellByColumnAndRow(85, $row); $data['ch_invoice_number'] = (!empty($ch_invoice_number->getValue()))?$ch_invoice_number->getValue():'&nbsp;';



						$ci_year_end = $worksheet->getCellByColumnAndRow(86, $row); $data['ci_year_end'] = (!empty($ci_year_end->getValue()))?$ci_year_end->getValue():'&nbsp;';



						$cj1 = $worksheet->getCellByColumnAndRow(87, $row); $data['cj1'] = (!empty($cj1->getValue()))?$cj1->getValue():'&nbsp;';



						$ck2 = $worksheet->getCellByColumnAndRow(88, $row); $data['ck2'] = (!empty($ck2->getValue()))?$ck2->getValue():'&nbsp;';



						$cl3 = $worksheet->getCellByColumnAndRow(89, $row); $data['cl3'] = (!empty($cl3->getValue()))?$cl3->getValue():'&nbsp;';



						$cm4 = $worksheet->getCellByColumnAndRow(90, $row); $data['cm4'] = (!empty($cm4->getValue()))?$cm4->getValue():'&nbsp;';



						$cn5 = $worksheet->getCellByColumnAndRow(91, $row); $data['cn5'] = (!empty($cn5->getValue()))?$cn5->getValue():'&nbsp;';



						$co_abridgedinc = $worksheet->getCellByColumnAndRow(92, $row); $data['co_abridgedinc'] = (!empty($co_abridgedinc->getValue()))?$co_abridgedinc->getValue():'&nbsp;';



						$cp_sor = $worksheet->getCellByColumnAndRow(93, $row); $data['cp_sor'] = (!empty($cp_sor->getValue()))?$cp_sor->getValue():'&nbsp;';



						$cq_adjnote = $worksheet->getCellByColumnAndRow(94, $row); $data['cq_adjnote'] = (!empty($cq_adjnote->getValue()))?$cq_adjnote->getValue():'&nbsp;';







						$cr_position = $worksheet->getCellByColumnAndRow(95, $row); $data['cr_position'] = (!empty($cr_position->getValue()))?$cr_position->getValue():'&nbsp;';



						$cs_liability = $worksheet->getCellByColumnAndRow(96, $row); $data['cs_liability'] = (!empty($cs_liability->getValue()))?$cs_liability->getValue():'&nbsp;';



						$ct_prelim = $worksheet->getCellByColumnAndRow(97, $row); $data['ct_prelim'] = (!empty($ct_prelim->getValue()))?$ct_prelim->getValue():'&nbsp;';



						$cu_paydate = $worksheet->getCellByColumnAndRow(98, $row); $data['cu_paydate'] = (!empty($cu_paydate->getValue()))?$cu_paydate->getValue():'&nbsp;';



						$cv_included = $worksheet->getCellByColumnAndRow(99, $row); $data['cv_included'] = (!empty($cv_included->getValue()))?$cv_included->getValue():'&nbsp;';



						$cw_liability = $worksheet->getCellByColumnAndRow(100, $row); $data['cw_liability'] = (!empty($cw_liability->getValue()))?$cw_liability->getValue():'&nbsp;';



						$cx_prelim = $worksheet->getCellByColumnAndRow(101, $row); $data['cx_prelim'] = (!empty($cx_prelim->getValue()))?$cx_prelim->getValue():'&nbsp;';



						$cy_paydate = $worksheet->getCellByColumnAndRow(102, $row); $data['cy_paydate'] = (!empty($cy_paydate->getValue()))?$cy_paydate->getValue():'&nbsp;';



						$cz_invoice = $worksheet->getCellByColumnAndRow(103, $row); $data['cz_invoice'] = (!empty($cz_invoice->getValue()))?$cz_invoice->getValue():'&nbsp;';



						$da_blank1 = $worksheet->getCellByColumnAndRow(104, $row); $data['da_blank1'] = (!empty($da_blank1->getValue()))?$da_blank1->getValue():'&nbsp;';



						$db_blank2 = $worksheet->getCellByColumnAndRow(105, $row); $data['db_blank2'] = (!empty($db_blank2->getValue()))?$db_blank2->getValue():'&nbsp;';



						$dc_blank3 = $worksheet->getCellByColumnAndRow(106, $row); $data['dc_blank3'] = (!empty($dc_blank3->getValue()))?$dc_blank3->getValue():'&nbsp;';



						$statement = $worksheet->getCellByColumnAndRow(107, $row); $data['statement'] = (!empty($statement->getValue()))?$statement->getValue():'&nbsp;';







						















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


	public function invoicesprintview(){
		$id = Input::get('id');

		$invoice_details = DB::table('invoice_system')->where('invoice_number', $id)->first();
		$client_details = DB::table('cm_clients')->where('client_id', $invoice_details->client_id)->first();

		if(count($client_details) == ''){

			$companyname = '<div style="width: 100%; height: auto; float: left; margin: 200px 0px 0px 0px; font-size: 15px; font-weight: bold; text-align: center; letter-spacing: 0px;">Company Details not found</div>';
			
			echo json_encode(array('companyname' => $companyname));
		}
		else{
					
			
			$company_firstname='
          <div class="company_details_div">
	              <div class="firstname_div">
	                <b>To:</b><br/>
	                '.$client_details->firstname.' '.$client_details->surname.'<br/>  
	                '.$client_details->company.'<br/>  
	                '.$client_details->address1.'<br/>  
	                '.$client_details->address2.'<br/>
	                '.$client_details->address3.'
	              </div>
	               
	              
	            </div>
	            
	            
	            <div class="account_details_div">
	              <div class="account_details_main_address_div">
	              		<div class="aib_account">
			                AIB Account: 48870061<br/>
			                Sort Code: 93-72-23<br/>
			                VAT Number: 9754009E<br/>
			                Company Number: 485123
			            </div>	                 
	                  <div class="account_details_invoice_div">
	                    <div class="account_table">
	                      <div class="account_row">
	                        <div class="account_row_td left"><b>Account:</b></div>
	                        <div class="account_row_td right">'.$client_details->client_id.'</div>
	                      </div>
	                      <div class="account_row">
	                        <div class="account_row_td left"><b>Invoice:</b></div>
	                        <div class="account_row_td right">'.$invoice_details->invoice_number.'</div>
	                      </div>
	                      <div class="account_row">
	                        <div class="account_row_td left"><b>Date:</b></div>
	                        <div class="account_row_td right">'.date('d-M-Y',strtotime($invoice_details->invoice_date)).'</div>
	                      </div>
	                    </div>
	                  </div>
	              </div>
	            </div>
	            <div class="invoice_label">
              INVOICE
            </div>';

            

            
            	$row1 = '
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->f_row1).'</div>
                  <div class="class_row_td right">'.$invoice_details->bn_row1.'</div>';
           
            
           
            	$row2 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->g_row2).'</div>
                  <div class="class_row_td right">'.$invoice_details->bo_row2.'</div>';
            
            
            	$row3 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->h_row3).'</div>
                  <div class="class_row_td right">'.$invoice_details->bp_row3.'</div>';

            
            	$row4 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->i_row4).'</div>
                  <div class="class_row_td right">'.$invoice_details->bq_row4.'</div>';

           
            	$row5 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->j_row5).'</div>
                  <div class="class_row_td right">'.$invoice_details->br_row5.'</div>';

            
            	$row6 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->k_row6).'</div>
                  <div class="class_row_td right">'.$invoice_details->bs_row6.'</div>';

            
            	$row7 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->l_row7).'</div>
                  <div class="class_row_td right">'.$invoice_details->bt_row7.'</div>';

            
            	$row8 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->m_row8).'</div>
                  <div class="class_row_td right">'.$invoice_details->bu_row8.'</div>';

            
            	$row9 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->n_row9).'</div>
                  <div class="class_row_td right">'.$invoice_details->bv_row9.'</div>';

           
            	$row10 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->o_row10).'</div>
                  <div class="class_row_td right">'.$invoice_details->bw_row10.'</div>';

            
            	$row11 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->p_row11).'</div>
                  <div class="class_row_td right">'.$invoice_details->bx_row11.'</div>';
           

           
            	$row12 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->q_row12).'</div>
                  <div class="class_row_td right">'.$invoice_details->by_row12.'</div>';
            

            
            	$row13 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->r_row13).'</div>
                  <div class="class_row_td right">'.$invoice_details->bz_row13.'</div>';

            
            	$row14 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->s_row14).'</div>
                  <div class="class_row_td right">'.$invoice_details->ca_row14.'</div>';
            
            
            	$row15 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->t_row15).'</div>
                  <div class="class_row_td right">'.$invoice_details->cb_row15.'</div>';
           

           
            	$row16 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->u_row16).'</div>
                  <div class="class_row_td right">'.$invoice_details->cc_row16.'</div>';
            

            
            	$row17 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->v_row17).'</div>
                  <div class="class_row_td right">'.$invoice_details->cd_row17.'</div>';
           

           
            	$row18 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->w_row18).'</div>
                  <div class="class_row_td right">'.$invoice_details->ce_row18.'</div>';
            

            
            	$row19 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->x_row19).'</div>
                  <div class="class_row_td right">'.$invoice_details->cf_row19.'</div>';


            
            	$row20 = '            	
                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->y_row20).'</div>
                  <div class="class_row_td right">'.$invoice_details->cg_row20.'</div>';




	         $tax_details ='

	         <div class="tax_table_div">
	            <div class="tax_table">
	              <div class="tax_row">
	                <div class="tax_row_td left">Total Fees (as agreed)</div>
	                <div class="tax_row_td right" width="13%">'.number_format_invoice($invoice_details->inv_net).'</div>
	              </div>
	              <div class="tax_row">
	                <div class="tax_row_td left">VAT @ 23%</div>
	                <div class="tax_row_td right" style="border-top:0px;">'.number_format_invoice($invoice_details->vat_value).'</div>
	              </div>
	              <div class="tax_row">
	                <div class="tax_row_td left" style="color:#fff">.</div>
	                <div class="tax_row_td right">'.number_format_invoice($invoice_details->gross).'</div>
	              </div>
	              <div class="tax_row">
	                <div class="tax_row_td left">Outlay @ 0%</div>
	                <div class="tax_row_td right" style="border-top:0px;" style="color:#fff">..</div>
	              </div>
	              <div class="tax_row">
	                <div class="tax_row_td left">Total Fees Due</div>
	                <div class="tax_row_td right" style="border-bottom: 2px solid #000">'.number_format_invoice($invoice_details->gross).'</div>
	              </div>
	            </div>
	          </div>

	         ';
	         //echo json_encode(array('companyname' => $company_firstname, 'taxdetails' => $tax_details, 'detailsrow' => $roww_details ));
	         echo json_encode(array('companyname' => $company_firstname, 'taxdetails' => $tax_details, 'row1' => $row1, 'row2' => $row2, 'row3' => $row3, 'row4' => $row4, 'row5' => $row5, 'row6' => $row6, 'row7' => $row7, 'row8' => $row8, 'row10' => $row10, 'row11' => $row11, 'row12' => $row12, 'row13' => $row13, 'row14' => $row14, 'row15' => $row15, 'row16' => $row16, 'row17' => $row17, 'row18' => $row18, 'row19' => $row19, 'row20' => $row20  ));
	     }



	}
	public function invoice_saveas_pdf()
	{
		$html = '

		
		<style>
			@page { margin: 0in; }
		    body {
		        background-image: url('.URL::to('assets/invoice_letterpad_1.jpg').');
		        background-position: top left right bottom;
			    background-repeat: no-repeat;
			    font-family: Verdana,Geneva,sans-serif; 
		    }
		    .tax_table_div{width: 100%; margin-top:-30px}
            .tax_table{margin-left:73%;width: 20%;}

            .details_table .class_row .class_row_td { font-size: 14px; float:left; }
            .details_table .class_row .class_row_td.left{position:absolute; left:80px; width:600px; text-align: left; Verdana,Geneva,sans-serif; font-size:14px; }
            .details_table .class_row .class_row_td.right{margin-left:605px; text-align: right; padding-right: 10px; Verdana,Geneva,sans-serif; font-size:14px;}
            .details_table .class_row{line-height: 30px; clear:both}
            .details_table { height : 420px !important; }

            .class_row{width: 100%; clear:both; height:20px:}

            .tax_table .tax_row .tax_row_td{ font-size: 14px; font-weight: 600;float:left;}
            .tax_table .tax_row .tax_row_td.left{position:absolute; left:80px; width:600px; text-align: left; font-family: Verdana,Geneva,sans-serif; font-size:14px;}
            .tax_table .tax_row .tax_row_td.right{{margin-left:605px;text-align: right; padding-right: 20px;border-top: 2px solid #000;}
            .tax_table .tax_row{line-height: 30px;}

            .company_details_class{width:100%; height:auto; }

            .account_details_main_address_div{width:200px; margin-top:-100px;  float:left; margin-left:550px;}
            .account_details_invoice_div{width:200px; }

            .company_details_div{width: 400px; margin-top: 220px; height:75px; float:left; font-family: Verdana,Geneva,sans-serif; font-size:14px;}
            .firstname_div{position:absolute; width:300px; left:80px; right:80px; margin-top: 90px; font-family: Verdana,Geneva,sans-serif; font-size:14px;}
            .aib_account{ color: #ccc; font-family: Verdana,Geneva,sans-serif; font-size: 12px; width:200px;  }

            .account_details_div{width: 400px; font-family: Verdana,Geneva,sans-serif; font-size:14px; line-height:20px; margin-top:40px;}
            .account_details_address_div{position:absolute; left:80px; width:250px; margin-top:60px; }
            .account_table .account_row .account_row_td.left{margin-left:0px;}
            .account_table .account_row .account_row_td.right{margin-left:100px;padding-top:-18px;}

            .invoice_label{ width: 100%; margin: 20px 0px; font-size: 15px; font-weight: bold; text-align: center; letter-spacing: 10px; }
            .tax_details_class_maindiv{width: 100%; float: left;}
		</style>';

		$html.= Input::get('htmlcontent');
		$pdf = PDF::loadHTML($html);
		$pdf->save('papers/Invoice Report.pdf');
		echo 'Invoice Report.pdf';
	}
	public function invoice_print_pdf()
	{
		$html = '<style>
			@page { margin: 0in; }
		    body {
			    font-family: Questa Sans; 
		    }
		    .tax_table_div{width: 100%; margin-top:-30px}
            .tax_table{margin-left:73%;width: 20%;}

            .details_table .class_row .class_row_td { font-size: 14px; float:left; }
            .details_table .class_row .class_row_td.left{position:absolute; left:80px; width:600px; text-align: left; Verdana,Geneva,sans-serif; font-size:14px; }
            .details_table .class_row .class_row_td.right{margin-left:605px; text-align: right; padding-right: 10px; Verdana,Geneva,sans-serif; font-size:14px;}
            .details_table .class_row{line-height: 30px; clear:both}
            .details_table { height : 420px !important; }

            .class_row{width: 100%; clear:both; height:20px:}

            .tax_table .tax_row .tax_row_td{ font-size: 14px; font-weight: 600;float:left;}
            .tax_table .tax_row .tax_row_td.left{position:absolute; left:80px; width:600px; text-align: left; font-family: Questa Sans; font-size:14px;}
            .tax_table .tax_row .tax_row_td.right{{margin-left:605px;text-align: right; padding-right: 20px;border-top: 2px solid #000;}
            .tax_table .tax_row{line-height: 30px;}

            .company_details_class{width:100%; height:auto; }

            .account_details_main_address_div{width:200px; margin-top:-100px;  float:left; margin-left:550px;}
            .account_details_invoice_div{width:200px; }

            .company_details_div{width: 400px; margin-top: 220px; height:75px; float:left; font-family: Questa Sans; font-size:14px;}
            .firstname_div{position:absolute; width:300px; left:80px; right:80px; margin-top: 90px; font-family: Questa Sans; font-size:14px;}
            .aib_account{ color: #ccc; font-family: Questa Sans; font-size: 12px; width:200px;  }

            .account_details_div{width: 400px; font-family: Questa Sans; font-size:14px; line-height:20px; margin-top:40px;}
            .account_details_address_div{position:absolute; left:80px; width:250px; margin-top:60px; }
            .account_table .account_row .account_row_td.left{margin-left:0px;}
            .account_table .account_row .account_row_td.right{margin-left:100px;padding-top:-18px;}

            .invoice_label{ width: 100%; margin: 20px 0px; font-size: 15px; font-weight: bold; text-align: center; letter-spacing: 10px; }
            .tax_details_class_maindiv{width: 100%; float: left;}
		</style>';

		$html.= Input::get('htmlcontent');
		$pdf = PDF::loadHTML($html);
		$pdf->save('papers/Invoice Report.pdf');
		echo 'Invoice Report.pdf';
	}
}
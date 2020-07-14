@extends('userheader')

@section('content')
<?php require_once(app_path('Http/helpers.php')); ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/jquery.dataTables.min.css'); ?>">

<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/fixedHeader.dataTables.min.css'); ?>">



<script src="<?php echo URL::to('assets/js/jquery.dataTables.min.js'); ?>"></script>

<script src="<?php echo URL::to('assets/js/dataTables.fixedHeader.min.js'); ?>"></script>



<script src="<?php echo URL::to('assets/js/jquery.form.js'); ?>"></script>

<script src="http://html2canvas.hertzen.com/dist/html2canvas.js"></script>



<link rel="stylesheet" href="<?php echo URL::to('assets/js/lightbox/colorbox.css'); ?>">

<script src="<?php echo URL::to('assets/js/lightbox/jquery.colorbox.js'); ?>"></script>

<style>

body{

  background: #f5f5f5 !important;

}



.label_class{

  float:left ;

  margin-top:15px;

  font-weight:700;

}

.upload_img{

  position: absolute;

    top: 0px;

    z-index: 1;

    background: rgb(226, 226, 226);

    padding: 19% 0%;

    text-align: center;

    overflow: hidden;

}

.upload_text{

  font-size: 15px;

    font-weight: 800;

    color: #631500;

}





.form-control[readonly]{

      background-color: #fff !important

}

.formtable tr td{

  padding-left: 15px;

  padding-right: 15px;

}

.fullviewtablelist>tbody>tr>td{

  font-weight:800 !important;

  font-size:15px !important;

}

.fullviewtablelist>tbody>tr>td a{

  font-weight:800 !important;

  font-size:15px !important;

}

.modal { overflow: auto !important;z-index: 999999;}

.pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover

{

  z-index: 0 !important;

}



.label_class{

  float:left ;

  margin-top:15px;

  font-weight:700;

}





.modal_load {

    display:    none;

    position:   fixed;

    z-index:    999999;

    top:        0;

    left:       0;

    height:     100%;

    width:      100%;

    background: rgba( 255, 255, 255, .8 ) 

                url(<?php echo URL::to('assets/images/loading.gif'); ?>) 

                50% 50% 

                no-repeat;

}



.report_div{

    position: absolute;

    top: 55%;

    left:20%;

    padding: 15px;

    background: #ff0;

    z-index: 999999;

    text-align: left;

}

.report_model_selectall{padding:10px 15px; background-image:linear-gradient(to bottom,#f5f5f5 0,#e8e8e8 100%); background: #f5f5f5; border:1px solid #ddd; margin-top: 20px; border-radius: 3px;  }



.report_ok_button{background: #000; text-align: center; padding: 6px 12px; color: #fff; float: left; border: 0px; font-size: 13px; }

.report_ok_button:hover{background: #5f5f5f; text-decoration: none; color: #fff}

.report_ok_button:focus{background: #000; text-decoration: none; color: #fff}



body.loading {

    overflow: hidden;   

}

body.loading .modal_load {

    display: block;

}

    .table thead th:focus{background: #ddd !important;}

    .form-control{border-radius: 0px;}

    .disabled{cursor :auto !important;pointer-events: auto !important}

    body #coupon {

      display: none;

    }

    @media print {

      body * {

        display: none;

      }

      body #coupon {

        display: block;

      }

    }

</style>

<script>

function popitup(url) {

    newwindow=window.open(url,'name','height=600,width=1500');

    if (window.focus) {newwindow.focus()}

    return false;

}



</script>



<style>

.error{color: #f00; font-size: 12px;}

a:hover{text-decoration: underline;}

</style>

<?php

if(!empty($_GET['import_type_new']))

{

  if(!empty($_GET['round']))

  {

    

    $filename = $_GET['filename'];



    

    $height = $_GET['height'];

    $highestrow = $_GET['highestrow'];

    $round = $_GET['round'];

    $out = $_GET['out'];



    ?>

    <div class="upload_img" style="width: 100%;height: 100%;z-index:1"><p class="upload_text">Uploading Please wait...</p><img src="<?php echo URL::to('assets/loading.gif'); ?>" width="100px" height="100px"><p class="upload_text">Finished Uploading <?php echo $height; ?> of <?php echo $highestrow; ?></p></div>



    <script>

      $(document).ready(function() {

        var base_url = "<?php echo URL::to('/'); ?>";

        window.location.replace(base_url+'/user/import_new_invoice_one?filename=<?php echo $filename; ?>&height=<?php echo $height; ?>&round=<?php echo $round; ?>&highestrow=<?php echo $highestrow; ?>&import_type_new=1&out=<?php echo $out; ?>');

      })

    </script>

    <?php



  }

}

?>

<img id="coupon" />



<div class="modal fade report_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">

  <div class="modal-dialog modal-lg" role="document">

      <div class="modal-content">

        <div class="modal-header">

          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            <h4 class="modal-title">Report Clients</h4>



            <div class="col-lg-12 report_model_selectall">

              <div class="col-lg-2" style="padding: 0px;"><input type="checkbox" class="select_all_class" id="select_all_class" value="1" style="padding-top: 20px;"><label for="select_all_class" style="font-size: 14px; font-weight: normal;">Select all</label></div>





              <div class="col-lg-10" id="date_filter_fields">

                <div style="float:left; line-height:30px;">From</div>

                <div class="col-lg-3"><input type="text" class="form-control datepicker" name="fromdate" id="fromdate" /></div>

                <div style="float:left; line-height:30px;">To</div>

                <div class="col-lg-3"><input type="text" class="form-control datepicker" name="todate" id="todate"  /></div>

                <div class="col-lg-3"><a href="javascript:" class="report_ok_button fillter_class" style="line-height:20px;">Submit</a></div>               

              </div>

            </div>





        </div>

        <div class="modal-body" style="height: 400px; overflow-y: scroll;">

            <table class="table">

              <thead>

              <tr style="background: #fff;">

                  <th width="5%" style="text-align: left;">S.No</th>

                  <th width="5%" style="text-align: left;"></th>

                  <th style="text-align: left;">Client ID</th>

                  <th style="text-align: left;">First Name</th>    

                  <th style="text-align: left;">Company Name</th>

                  <th width="15%" style="text-align: left;">Invoice Count</th>

              </tr>

              </thead>                            

              <tbody id="report_tbody">

              



              </tbody>

          </table>

        </div>

        <div class="modal-footer">

            <input type="button" class="common_black_button" id="save_as_csv" value="Save as CSV">

            <input type="button" class="common_black_button" id="save_as_pdf" value="Save as PDF">

        </div>

      </div>

  </div>

</div>


<div class="modal fade invoice_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">

  <div class="modal-dialog modal-lg" role="document">

      <div class="modal-content">

        <div class="modal-header">

          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            <h4 class="modal-title">Invoices</h4>
        </div>

        <div class="modal-body" style="height: 600px; font-size: 14px; overflow-y: scroll;">
          <style type="text/css">
            .account_table .account_row .account_row_td{font-size: 14px; line-height: 20px; float:left;}
            .account_table .account_row .account_row_td.left{width:50%;}
            .account_table .account_row .account_row_td.right{width:50%;}

            .tax_table_div{width: 100%; height: auto; float: left;}
            .tax_table{width: 80%; height: auto; float: left;margin-left: 10%;}
            .tax_table .tax_row .tax_row_td.right{width:30%;text-align: right; padding-right: 10px; border-top:2px solid #000; }

            .details_table .class_row .class_row_td, .tax_table .tax_row .tax_row_td{ font-size: 14px; font-weight: 600;float:left;}
            .details_table .class_row .class_row_td.left, .tax_table .tax_row .tax_row_td.left{width:70%;text-align: left;}
            .details_table .class_row .class_row_td.right, .tax_table .tax_row .tax_row_td.right{width:30%;text-align: right; padding-right: 10px;}
            .details_table .class_row, .tax_table .tax_row{line-height: 30px;}

            .company_details_div{width: 100%; height: auto; float: left; margin-top: 220px;}
            .firstname_div{width: 50%; float: left; padding-left: 10%; margin-top: 25px;}
            .aib_account{ width: 220px; height: auto; float: right; line-height: 20px; color: #ccc; font-size: 12px; }
            .account_details_div{width: 100%; height:auto; float: left;}
            .account_details_main_address_div{width: 80%; height: auto; margin: 0px 10%;}
            .account_details_address_div{width: 75%; height: auto; float: left; margin-top: 20px;}
            .account_details_invoice_div{width: 25%; height: auto; float: left;}
            .invoice_label{width: 100%; height: auto; float: left; margin: 20px 0px; font-size: 15px; font-weight: bold; text-align: center; letter-spacing: 10px;}
          </style>
          <div id="letterpad_modal" style="width: 100%;height:1235px; float: left; background:url('<?php echo URL::to('assets/invoice_letterpad.jpg');?>') no-repeat">


          

            <div class="company_details_class"></div>
            <div style="width: 100%; min-height: 539px; float: left;">
              <div class="details_table" style="width: 80%; height: auto; margin: 0px 10%;">
                <div class="class_row class_row1"></div>
                <div class="class_row class_row2"></div>
                <div class="class_row class_row3"></div>
                <div class="class_row class_row4"></div>
                <div class="class_row class_row5"></div>
                <div class="class_row class_row6"></div>
                <div class="class_row class_row7"></div>
                <div class="class_row class_row8"></div>
                <div class="class_row class_row9"></div>
                <div class="class_row class_row10"></div>
                <div class="class_row class_row11"></div>
                <div class="class_row class_row12"></div>
                <div class="class_row class_row13"></div>
                <div class="class_row class_row14"></div>
                <div class="class_row class_row15"></div>
                <div class="class_row class_row16"></div>
                <div class="class_row class_row17"></div>
                <div class="class_row class_row18"></div>
                <div class="class_row class_row19"></div>
                <div class="class_row class_row20"></div>
              </div>
            </div>
            <div class="tax_details_class"></div> 
          </div>
        </div>

        <div class="modal-footer">

            <input type="button" class="common_black_button saveas_pdf" value="Save as PDF">
            <input type="button" class="common_black_button print_pdf" value="Print">

        </div>

      </div>

  </div>

</div>

<div class="modal fade" id="import_invoice" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">

  <div class="modal-dialog modal-sm" role="document">

      <div class="modal-content">

        <form action="<?php echo URL::to('user/import_new_invoice'); ?>" method="post" autocomplete="off" enctype="multipart/form-data">

          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

              <h4 class="modal-title">Import Invoice</h4>

          </div>

          <div class="modal-body">

              <label>Choose File : </label>

              <input type="file" name="new_file" id="new_file" class="form-control input-sm" accept=".csv" required> 

          </div>

          <div class="modal-footer">

              <input type="submit" class="common_black_button" id="import_new_file" value="Import">

          </div>

        </form>

      </div>

  </div>

</div>









<div class="content_section" style="margin-bottom:200px">

  <div class="page_title">

        <h4 class="col-lg-3" style="padding: 0px;">

                Invoice Management                

            </h4>

            <div class="col-lg-3 text-right" style="padding-right: 0px; line-height: 35px;">

                

            </div>

            <div class="col-lg-1 text-right" style="padding-right: 0px;">

              <input type="text" name="" placeholder="Search" class="form-control search_input_class" >

            </div>

            <div class="col-lg-2 text-right" style="padding-right: 0px;">

              <select class="form-control search_select_class">

                <option value="">Select Type</option>

                <option value="invoice_number">Invoice Number</option>

                <option value="invoice_date">Date</option>

                <option value="client_id">Client ID</option>

                <option value="company_name">Company Name</option>

                <option value="inv_net">Net</option>

                <option value="vat_rate">VAT</option>

                <option value="gross">Gross</option>

              </select>

            </div>

            <div class="col-lg-3 text-right"  style="padding: 0px;" >

              <div class="select_button" style=" margin-left: 10px;">

                <ul>

                <li><a href="javascript:" id="search_button" style="font-size: 13px; font-weight: 500;">Search</a></li>

                <li><a href="" style="font-size: 13px; font-weight: 500;">Reset</a></li>                

                <li><a href="javascript:" style="font-size: 13px; font-weight: 500;" class="invoice_import">Import</a></li>

                <li><a href="javascript:" class="reportclassdiv" style="font-size: 13px; font-weight: 500;">Report</a></li>

                <div class="report_div" style="display: none">

                    <label>Please select following report type</label><br>

                    <input type="radio" name="report_invoice" id="allinvoice" class="class_invoice" value="1"><label for="allinvoice">Client Based Invoice</label>
                    <br/>
                    <input type="radio" name="report_invoice" id="datefilterinvoice" class="class_invoice" value="2"><label for="datefilterinvoice">Date Based Invoice</label>
                    <br/>
                    <input type="submit" name="invoce_report_but" class="report_ok_button ok_button" value="OK">

                </div>

                

              </ul>

            </div>

            <br/>

            <input type="checkbox" name="show_statement" id="show_statement" value="1" checked style="margin-right:10px"><label for="show_statement">Show All Invoices</label>

  </div>

  <div class="table-responsive" style="max-width: 100%; float: left;margin-bottom:30px; margin-top:55px">

  </div>

  <div style="clear: both;">

   <?php

    if(Session::has('message')) { ?>

        <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>



    

    <?php } ?>

    </div> 



<table class="display nowrap fullviewtablelist" id="client_expand" width="100%">

                        <thead>

                        <tr style="background: #fff;">

                            <th width="2%" style="text-align: left;">S.No</th>

                            <th style="text-align: left;">Invoice #</th>

                            <th style="text-align: left;">Date</th>

                            <th style="text-align: left;">Client ID</th>

                            <th style="text-align: left;">Company Name</th>

                            <th style="text-align: right;">Net</th>

                            <th style="text-align: right;">VAT</th>

                            <th style="text-align: right;">Gross</th>

                            <th style="text-align: left;">Statement</th>

                        </tr>

                        </thead>                            

                        <tbody id="invoice_tbody">

                        <?php

                            $i=1;

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

                          ?>

                            <tr>

                                <td><?php echo $i; ?></td>

                                <td align="left"><a href="javascript:" class="invoice_class" data-element="<?php echo $invoice->invoice_number; ?>" style="<?php echo $textcolor?>"><?php echo $invoice->invoice_number; ?></a></td>

                                <td align="left" style="<?php echo $textcolor?>"><spam style="display:none"><?php echo strtotime($invoice->invoice_date); ?></spam><?php echo date('d-M-Y',strtotime($invoice->invoice_date)); ?></td>

                                <td align="left" style="<?php echo $textcolor?>"><?php echo $invoice->client_id; ?></td>

                                <td align="left" style="<?php echo $textcolor?>"><?php echo $company; ?></td>

                                <td align="right" style="<?php echo $textcolor?>"><?php echo number_format_invoice($invoice->inv_net); ?></td>

                                <td align="right" style="<?php echo $textcolor?>"><?php echo number_format_invoice($invoice->vat_value); ?></td>

                                <td align="right" style="<?php echo $textcolor?>"><?php echo number_format_invoice($invoice->gross); ?></td>

                                <td align="left" style="<?php echo $textcolor?>"><?php echo $statementtext; ?></td>

                                

                            </tr>

                            <?php

                              $i++;

                              }              

                            }

                            if($i == 1)

                            {

                              echo'<tr><td colspan="9" align="center">Empty</td></tr>';

                            }

                          ?> 

                        </tbody>

                    </table>

</div>

    <!-- End  -->

<div class="main-backdrop"><!-- --></div>



<div id="report_pdf_type_two" style="display:none">

  <style>

  .table_style {

      width: 100%;

      border-collapse:collapse;

      border:1px solid #c5c5c5;

  }

  </style>



  <h3 id="pdf_title_all_ivoice" style="width: 100%; text-align: center; margin: 15px 0px; float: left;">Report of Client Based Invoices</h3>

  <h3 id="pdf_title_date_filter" style="width: 100%; text-align: center; margin: 15px 0px; float: left;">Report of Invoices From <span id="pdffromdate"></span> to <span id="pdftodate"></span></h3>





  <table class="table_style">

    <thead>

      <tr>

      <th width="2%" style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">S.No</th>

      <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Invoice ID</th>

      <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Date</th>

      <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Client ID</th>

      <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px ">Company Name</th>

      <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px ">Net</th>

      <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">VAT</th>

      <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Gross</th>

      </tr>

    </thead>

    <tbody id="report_pdf_type_two_tbody">



    </tbody>

  </table>

</div>





<div class="modal_load"></div>

<input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">

<input type="hidden" name="show_alert" id="show_alert" value="">

<input type="hidden" name="pagination" id="pagination" value="1">



<script>



$( function() {

  $(".datepicker" ).datepicker({ dateFormat: 'mm-dd-yy' });

  $(".datepicker" ).datepicker();

});


function printPdf(url) {
  var iframe = this._printIframe;
  if (!this._printIframe) {
    iframe = this._printIframe = document.createElement('iframe');
    document.body.appendChild(iframe);

    iframe.style.display = 'none';
    iframe.onload = function() {
      setTimeout(function() {
        iframe.focus();
        iframe.contentWindow.print();
      }, 1);
    };
  }

  iframe.src = url;
}


$(window).click(function(e) {

  if($(e.target).hasClass('saveas_pdf'))
  {
    var htmlcontent = $("#letterpad_modal").html();
    $.ajax({
      url:"<?php echo URL::to('user/invoice_saveas_pdf'); ?>",
      data:{htmlcontent:htmlcontent},
      type:"post",
      success: function(result)
      {
        SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);
      }
    });
  }
  if($(e.target).hasClass('print_pdf'))
  {
    var htmlcontent = $("#letterpad_modal").html();
    $.ajax({
      url:"<?php echo URL::to('user/invoice_print_pdf'); ?>",
      data:{htmlcontent:htmlcontent},
      type:"post",
      success: function(result)
      {
        $("#pdfDocument").attr("src","<?php echo URL::to('papers/Invoice Report.pdf'); ?>");
        printPdf("<?php echo URL::to('papers/Invoice Report.pdf'); ?>");
      }
    });
  }
  if(e.target.id == "select_all_class")

  {

    if($(e.target).is(":checked"))

    {

      $(".select_client").each(function() {

        $(this).prop("checked",true);

      });



    }

    else{

      $(".select_client").each(function() {

        $(this).prop("checked",false);

      });

    }

  }

  if(e.target.id == "search_button")

  {

    var input = $(".search_input_class").val();

    var select = $(".search_select_class").val();

    if(input == "" || select == "")

    {

      alert("Please Type the Input and choose the search type to serch the Invoice.");

    }

    else{

      $.ajax({

        url:"<?php echo URL::to('user/invoice_search'); ?>",

        type:"get",

        data:{input:input,select:select},

        success: function(result) {

          $("#invoice_tbody").html(result);

          $("#client_expand_info").hide();         

          

        }

      });

    }

  }





  if(e.target.id == 'show_statement')

  {

    if($(e.target).is(':checked'))

    {

      $.ajax({

        url:"<?php echo URL::to('user/show_statement'); ?>",

        type:"post",

        data:{value:1},

        success: function(result)

        {

          $("#invoice_tbody").html(result);

        }

      });

    }

    else{

      $.ajax({

        url:"<?php echo URL::to('user/show_statement'); ?>",

        type:"post",

        data:{value:0},

        success: function(result)

        {

          $("#invoice_tbody").html(result);

        }

      });

    }

  }



  if($(e.target).hasClass('reportclassdiv'))

  {

    $(".report_div").toggle();

  }





  /*if($(e.target).hasClass('allinvoice')) {

    $("body").addClass("loading");    

    $.ajax({

        url: "<?php echo URL::to('user/report_client_invoice') ?>",

        data:{id:0},

        type:"post",

        success:function(result){          

           $(".report_modal").modal("toggle");

           $("#report_tbody").html(result);

           $(".report_div").hide();

           $("body").removeClass("loading");



    }

  });

  }*/





  if(e.target.id == "save_as_csv")

  {

    if ($('#date_filter_fields').is(':visible')) {

      var fromdate = $("#fromdate").val();

      var todate = $("#todate").val();

       $("body").addClass("loading");

        if($(".select_client:checked").length)

        {

          var checkedvalue = '';

          $(".select_client:checked").each(function() {

              var value = $(this).val();

              if(checkedvalue == "")

              {

                checkedvalue = value;

              }

              else{

                checkedvalue = checkedvalue+","+value;

              }

          });

          $.ajax({

            url:"<?php echo URL::to('user/invoice_report_csv'); ?>",

            type:"post",

            data:{value:checkedvalue,fromdate:fromdate,todate:todate},

            success: function(result)

            {

              SaveToDisk("<?php echo URL::to('papers'); ?>/CM_Report.csv",'Invoice_Report.csv');

            }

          });

        }

        else{

          $("body").removeClass("loading");

          alert("Please Choose atleast one invoice to continue.");

        }

    }

    else{

       $("body").addClass("loading");

        if($(".select_client:checked").length)

        {

          var checkedvalue = '';

          $(".select_client:checked").each(function() {

              var value = $(this).val();

              if(checkedvalue == "")

              {

                checkedvalue = value;

              }

              else{

                checkedvalue = checkedvalue+","+value;

              }

          });

          $.ajax({

            url:"<?php echo URL::to('user/invoice_report_csv'); ?>",

            type:"post",

            data:{value:checkedvalue},

            success: function(result)

            {

              SaveToDisk("<?php echo URL::to('papers'); ?>/CM_Report.csv",'Invoice_Report.csv');

            }

          });

        }

        else{

          $("body").removeClass("loading");

          alert("Please Choose atleast one invoice to continue.");

        }

    }

  }



  if(e.target.id == "save_as_pdf")

  {

    $("#report_pdf_type_two_tbody").html('');

    if ($('#date_filter_fields').is(':visible')) {

      var fromdate = $("#fromdate").val();

      var todate = $("#todate").val();

        if($(".select_client:checked").length)

        {

          $("body").addClass("loading");

            var checkedvalue = '';

            var size = 100;

            $(".select_client:checked").each(function() {

              var value = $(this).val();

              if(checkedvalue == "")

              {

                checkedvalue = value;

              }

              else{

                checkedvalue = checkedvalue+","+value;

              }

            });

            var exp = checkedvalue.split(',');

            var arrayval = [];

            for (var i=0; i<exp.length; i+=size) {

                var smallarray = exp.slice(i,i+size);

                arrayval.push(smallarray);

            }

            $.each(arrayval, function( index, value ) {

                setTimeout(function(){ 

                  var imp = value.join(',');



                  $.ajax({

                    url:"<?php echo URL::to('user/invoice_report_pdf'); ?>",

                    type:"post",

                    data:{value:imp,fromdate:fromdate,todate:todate},

                    success: function(result)

                    {

                      $("#report_pdf_type_two_tbody").append(result);

                      

                      var last = index + parseInt(1);

                      if(arrayval.length == last)

                      {

                        var pdf_html = $("#report_pdf_type_two").html();

                        $.ajax({

                          url:"<?php echo URL::to('user/invoice_download_report_pdfs'); ?>",

                          type:"post",

                          data:{htmlval:pdf_html},

                          success: function(result)

                          {

                            SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);

                          }

                        });

                      }

                    }

                  });

                }, 3000);

            });

            

        }

        else{

          $("body").removeClass("loading");

          alert("Please Choose atleast one invoice to continue.");

        }

    }

    else{

        if($(".select_client:checked").length)

        {

          $("body").addClass("loading");

            var checkedvalue = '';

            var size = 100;

            $(".select_client:checked").each(function() {

              var value = $(this).val();

              if(checkedvalue == "")

              {

                checkedvalue = value;

              }

              else{

                checkedvalue = checkedvalue+","+value;

              }

            });

            var exp = checkedvalue.split(',');

            var arrayval = [];

            for (var i=0; i<exp.length; i+=size) {

                var smallarray = exp.slice(i,i+size);

                arrayval.push(smallarray);

            }

            $.each(arrayval, function( index, value ) {

                setTimeout(function(){ 

                  var imp = value.join(',');



                  $.ajax({

                    url:"<?php echo URL::to('user/invoice_report_pdf'); ?>",

                    type:"post",

                    data:{value:imp},

                    success: function(result)

                    {

                      $("#report_pdf_type_two_tbody").append(result);

                      

                      var last = index + parseInt(1);

                      if(arrayval.length == last)

                      {

                        var pdf_html = $("#report_pdf_type_two").html();

                        $.ajax({

                          url:"<?php echo URL::to('user/invoice_download_report_pdfs'); ?>",

                          type:"post",

                          data:{htmlval:pdf_html},

                          success: function(result)

                          {

                            SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);

                          }

                        });

                      }

                    }

                  });

                }, 3000);

            });

            

        }

        else{

          $("body").removeClass("loading");

          alert("Please Choose atleast one invoice to continue.");

        }

    }

  }



  if($(e.target).hasClass('ok_button'))

  {

    var check_option = $(".class_invoice:checked").val();

    if(check_option === "" || typeof check_option === "undefined")

    {

      alert("Please select atleast one report type to move forward.");

    }

    else{

      var id = $('input[name="report_invoice"]:checked').val();

      $(".class_invoice").prop("checked", false);

      if(id == 1){

          $("body").addClass("loading");    

          $.ajax({

              url: "<?php echo URL::to('user/report_client_invoice') ?>",

              data:{id:0},

              type:"post",

              success:function(result){      

                 $(".report_modal").modal("show");

                 $("#report_tbody").html(result);

                 $("#report_tbody").show();               

                 $(".report_div").hide();

                 $("body").removeClass("loading");

                 $("#date_filter_fields").hide();

                 $("#pdf_title_all_ivoice").show();

                 $("#pdf_title_date_filter").hide();

                 

          }

        });

      }

      else{

        $("body").addClass("loading");    

          $.ajax({

              url: "<?php echo URL::to('user/report_client_invoice') ?>",

              data:{id:1},

              type:"post",

              success:function(result){      

                 $(".report_modal").modal("show");

                 $("#report_tbody").hide();

                 $(".report_div").hide();

                 $("body").removeClass("loading");

                 $("#date_filter_fields").show();



          }

        });

      }

    }

  }



   if($(e.target).hasClass('fillter_class')){



    var from = $("#fromdate").val();

    var to = $("#todate").val();



    if(from === "" || to === "")

    {

      if(from == "")

      {

        alert("Plaese choose the From Date to view the report");

      }

      else

      {

        alert("Plaese choose the TO Date to view the report");

      }

    } 

      else{
        $("body").addClass("loading");    
          $.ajax({
            url: "<?php echo URL::to('user/report_client_invoice_date_filter') ?>",
            data:{id:0, fdate:from, tdate:to},
            type:"post",
            success:function(result){      
               $(".report_modal").modal("show");
               $("#report_tbody").html(result);
               $("#report_tbody").show();
               $(".report_div").hide();
               $("body").removeClass("loading");
               $("#pdf_title_all_ivoice").hide();
               $("#pdf_title_date_filter").show();
            }
          });
      }
  }
  if($(e.target).hasClass('invoice_import'))
  {    

    $("#import_invoice").modal("show");

  }

  if($(e.target).hasClass('invoice_class')){

    var editid = $(e.target).attr("data-element");
    console.log(editid);
    $.ajax({
          url: "<?php echo URL::to('user/invoices_print_view') ?>",
          data:{id:editid},
          dataType:'json',
          type:"post",
          success:function(result){      
             $(".invoice_modal").modal("show");
             $("body").removeClass("loading");  
             $(".company_details_class").html(result['companyname']);
             $(".tax_details_class").html(result['taxdetails']);
             $(".class_row1").html(result['row1']);
             $(".class_row2").html(result['row2']);
             $(".class_row3").html(result['row3']);
             $(".class_row4").html(result['row4']);
             $(".class_row5").html(result['row5']);
             $(".class_row6").html(result['row6']);
             $(".class_row7").html(result['row7']);
             $(".class_row8").html(result['row8']);
             $(".class_row9").html(result['row9']);
             $(".class_row10").html(result['row10']);
             $(".class_row11").html(result['row11']);
             $(".class_row12").html(result['row12']);
             $(".class_row13").html(result['row13']);
             $(".class_row14").html(result['row14']);
             $(".class_row15").html(result['row15']);
             $(".class_row16").html(result['row16']);
             $(".class_row17").html(result['row17']);
             $(".class_row18").html(result['row18']);
             $(".class_row19").html(result['row19']);
             $(".class_row20").html(result['row20']);
      }

    });


   }



});

</script>





<!-- Page Scripts -->

<script>

$(function(){

    $('#client_expand').DataTable({

        fixedHeader: {

          headerOffset: 75

        },

        autoWidth: true,

        scrollX: false,

        fixedColumns: false,

        searching: false,

        paging: false,

        info: false

    });

});

</script>



<script>

$("#fromdate").change(function(){    

    var value = $(this).val();

    console.log(value);

    $.ajax({

      success:function(result){

        $("#pdffromdate").html(value);



      }



    });

    

});



$("#todate").change(function(){    

    var value = $(this).val();

    console.log(value);

    $.ajax({

      success:function(result){

        $("#pdftodate").html(value);       

      }



    });

    

});

// $(window).scroll(function(e){

//   var len = $(".load_more").length;

//   if(len > 0)

//   {

//     var off = $(".load_more").offset();

//     var scroll = $(window).scrollTop();

//     var h = screen.height - parseInt(220);

//     var screen_height = $(document).height();



//     var final_scroll = parseInt(scroll) + parseInt(h);

//     if(off.top <= final_scroll)

//     {

//       $("body").addClass("loading");

//       doSomething();

//     }

//   }

// });

// function doSomething() { 
    

//     var paginate = $("#pagination").val();

//     var count = parseInt(paginate) + parseInt(1);

//     var base_url = "<?php echo URL::to('user/invoicemanagement_paginate'); ?>";



//     $("#pagination").val(count);

//     $.ajax({

//       url: base_url,

//       data: {page:count},

//       type: "get",

//       success:function(result){

//         $("body").removeClass("loading");

//         $("body").find(".load_more").removeClass("load_more");

//         $("#invoice_tbody").append(result);

//         var table = $('#client_expand').DataTable();
//         table.fixedHeader.adjust();

//       }

//     });

//   }

</script>











@stop
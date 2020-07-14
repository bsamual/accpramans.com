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
.common_black_button:hover{
  background: #5f5f5f;
    color: #fff;
    border: 0px;
    box-shadow: none;
    background-color: #5f5f5f !important;
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
.ui-tooltip{
  margin-top:-50px !important;
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
.field_check
{
  width:24%;
}
.import_div{
    position: absolute;
    top: 55%;
    left:30%;
    padding: 15px;
    background: #ff0;
    z-index: 999999;
}
.selectall_div{
  position: absolute;
    top: 13%;
    left: 5%;
    border: 1px solid #000;
    padding: 12px;
    background: #ff0;
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
.ui-widget{z-index: 999999999}

.dropzone .dz-preview.dz-image-preview {

    background: #949400 !important;

}

.dz-message span{text-transform: capitalize !important; font-weight: bold;}

.trash_imageadd{

  cursor:pointer;

}

.dropzone.dz-clickable .dz-message, .dropzone.dz-clickable .dz-message *{

      margin-top: 40px;

}

.dropzone .dz-preview {

  margin:0px !important;

  min-height:0px !important;

  width:100% !important;

  color:#000 !important;

    float: left;

  clear: both;

}

.dropzone .dz-preview p {

  font-size:12px !important;

}

.remove_dropzone_attach{

  color:#f00 !important;

  margin-left:10px;

}

.img_div_add{

        border: 1px solid #000;

    width: 300px;

    position: absolute !important;

    min-height: 118px;

    background: rgb(255, 255, 0);

    display:none;

}

.dropzone.dz-clickable{margin-bottom: 0px !important;}

.report_model_selectall{padding:10px 15px; background-image:linear-gradient(to bottom,#f5f5f5 0,#e8e8e8 100%); background: #f5f5f5; border:1px solid #ddd; margin-top: 20px; border-radius: 3px;  }


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

<img id="coupon" />



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
            .account_table .account_row .account_row_td.left{width:40%;}
            .account_table .account_row .account_row_td.right{width:60%;}

            .tax_table_div{width: 100%; height: auto; float: left;}
            .tax_table{width: 80%; height: auto; float: left;margin-left: 10%;}
            .tax_table .tax_row .tax_row_td.right{width:30%;text-align: right; padding-right: 10px; border-top:2px solid #000; }

            .class_row{width: 100%; height: 20px;}

            .details_table .class_row .class_row_td, .tax_table .tax_row .tax_row_td{ font-size: 14px; font-weight: 600;float:left;}
            .details_table .class_row .class_row_td.left{width:70%;min-height:10px; text-align: left; float: left; height:20px;}
            .details_table .class_row .class_row_td.left_corner{width:10%;text-align: right; float: left;height:20px;}
            .details_table .class_row .class_row_td.right_start{width:10%;text-align: right; float: left;height:20px;}
            .details_table .class_row .class_row_td.right{width:10%;text-align: right; padding-right: 10px; float: right;height:20px;}

            .tax_table .tax_row .tax_row_td.left{width:70%;text-align: left; float: left;}
            .tax_table .tax_row .tax_row_td.right{width:30%;text-align: right; padding-right: 10px; float: right;}

            .details_table .class_row, .tax_table .tax_row{line-height: 30px; clear: both;}

            .company_details_class{width: 100%; margin: 0px auto; height: auto;}

            .company_details_div{width: 40%; height: auto; float: left; margin-top: 220px; margin-left: 10%}
            .firstname_div{width: 100%; float: left; margin-top: 55px;}
            .aib_account{ width: 200px; height: auto; float: right; line-height: 20px; color: #ccc; font-size: 12px; }
            .account_details_div{width: 50%; height:auto; float: left; margin-top: 220px;}
            .account_details_main_address_div{width: 100%; height: auto; float: right;}
            .account_details_address_div{width: 100%; height: auto; float: left; }
            .account_details_invoice_div{width: 200px; height: auto; float: right; clear: both; margin-top: 20px;}
            .invoice_label{width: 100%; height: auto; float: left; margin: 20px 0px; font-size: 15px; font-weight: bold; text-align: center; letter-spacing: 10px;}
            .tax_details_class_maindiv{width: 100%; min-height: 539px; float: left;}
          </style>
          <div id="letterpad_modal" style="width: 100%;height:1235px; float: left; background:url('<?php echo URL::to('assets/invoice_letterpad.jpg');?>') no-repeat">
            <div class="company_details_class"></div>
            <div class="tax_details_class_maindiv">
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
            <input type="hidden" name="invoice_number_pdf" id="invoice_number_pdf" value="">
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



<div class="content_section" style="margin-bottom:200px">
  <div class="page_title">
    <div style="clear: both;">
   <?php
    if(Session::has('message')) { ?>
        <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
     <?php }   
    ?>
    </div> 
    <div class="col-lg-12">
      <div class="col-lg-4" style="padding: 0px">
        <?php
        if(isset($_GET['client_id']))
        {
          $client_details = DB::table('cm_clients')->where('client_id',$_GET['client_id'])->first();
          if(count($client_details))
          {
            $clientname = $client_details->company.'-'.$_GET['client_id'];
            $client_id = $_GET['client_id'];
          }
          else{
            $clientname = '';
            $client_id = '';
          }
        }
        else{
          $clientname = '';
          $client_id = '';
        }
        ?>
        <input type="text" class="form-control" id="search_clientid" placeholder="Enter Client ID" name="" value="<?php echo $clientname; ?>">
        <input type="hidden" id="client_search" class="client_search" value="<?php echo $client_id; ?>">
      </div>
    </div>  
</div>
<?php
if(isset($_GET['client_id']))
{
  $client_id = $_GET['client_id'];
  $joblist = DB::table('task_job')->where('client_id', $client_id)->where('status', 1)->orderBy('job_date', 'DESC')->get();

  $client_details = DB::table('cm_clients')->where('client_id', $client_id)->first();

  $client_name = $client_details->company.'-'.$client_details->client_id;


  $result_time_job='';
  if(count($joblist)){
    foreach ($joblist as $jobs) {
      $user_details = DB::table('user')->where('user_id', $jobs->user_id)->first();
      $time_task = DB::table('time_task')->where('id', $jobs->task_id)->first();

      $ta_count = DB::table('ta_client_invoice')->where('client_id', $client_id)->first();
      $key='';
      $disable = '';
      $invoice = '';
      $class = 'select_task';
      $allocated_class = 'unallocated_row';

      if(count($ta_count)){
        // $explode_invoice = explode(',', $ta_count->invoice);
        $unserialize = unserialize($ta_count->tasks);

        if($ta_count->tasks != ''){
          foreach ($unserialize as $key => $siglelist) {                   
            if(in_array($jobs->id, $siglelist)){
              $disable = 'disabled';
              $invoice = $key;
              $class = '';

              $allocated_class = 'allocated_row';
            }
          }
        }
      }



      $ratelist = DB::table('user_cost')->where('user_id', $jobs->user_id)->get();


      //-----------Job Time Start----------------
      $explode_job_minutes = explode(":",$jobs->job_time);
      $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);

      //-----------Job Time End----------------


      $rate_result = '0';
      $cost = '0';

      if(count($ratelist)){
        foreach ($ratelist as $rate) {
          $job_date = strtotime($jobs->job_date);
          $from_date = strtotime($rate->from_date);
          $to_date = strtotime($rate->to_date);

          if($rate->to_date != '0000-00-00'){                         
            if($job_date >= $from_date  && $job_date <= $to_date){
              
              $rate_result = $rate->cost;                        
              $cost = ($rate_result/60)*$total_minutes;
            }
          }
          else{
            if($job_date >= $from_date){
              $rate_result = $rate->cost;
              $cost = ($rate_result/60)*$total_minutes;
            }
          }
        }                      
      }
      $result_time_job.='               
              <tr class="'.$allocated_class.'">
                <td><input type="checkbox" name="tasks_job" '.$disable.' class="'.$class.'" data-element="'.$jobs->id.'" value="'.$jobs->id.'"><label>&nbsp;</label></td>
                <td>'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
                <td>'.$time_task->task_name.'</td>
                <td>'.$user_details->lastname.' '.$user_details->firstname.' ('.$rate_result.')</td>
                <td style="text-align:right">'.$jobs->job_time.'</td>
                <td style="text-align:right">'.number_format_invoice_without_decimal($cost).'</td>
                <td style="text-align:right">'.$invoice.'</td>
              </tr>
      ';
    }              
  }
  else{
    $result_time_job.='
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>Empty</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>';
  }

  $invoicelist = DB::table('invoice_system')->where('client_id', $client_id)->orderBy('invoice_date', 'DESC')->orderBy('invoice_number', 'DESC')->get();

  $result_invoice='';
  if(count($invoicelist)){
  foreach ($invoicelist as $invoice) {
    $result_invoice.='
      <tr>
        <td><input type="radio" name="invoice_item" class="invoice_item_class" data-element="'.$invoice->invoice_number.'" name=""><label>&nbsp;</label></td>
        <td><a href="javascript:" class="invoice_class" data-element="'.$invoice->invoice_number.'">'.$invoice->invoice_number.'</td>
        <td>'.date('d-M-Y', strtotime($invoice->invoice_date)).'</td>
        <td  style="text-align:right">'.number_format_invoice_without_decimal($invoice->inv_net).'</td>
        <td  style="text-align:right">'.number_format_invoice_without_decimal($invoice->vat_value).'</td>
        <td  style="text-align:right">'.number_format_invoice_without_decimal($invoice->gross).'</td>                  
      </tr>';
  }
  }
  else{
  $result_invoice.='
      <tr>
        <td></td>
        <td></td>
        <td align="right">Empty</td>
        <td></td>
        <td></td>
        <td></td>                  
      </tr>';
  }
}
else{
  $client_name = '';
  $result_time_job ='';
  $result_invoice='';
}
?>
  <div class="col-lg-12" style="margin-top: 20px;">
        <ul class="nav nav-tabs" role="tablist">
            <li class="active"><a href="<?php echo URL::to('user/ta_allocation?client_id='.$_GET['client_id'])?>">Allocation</a></li>            
            <li><a href="<?php echo URL::to('user/ta_auto_allocation?client_id='.$_GET['client_id'])?>">Auto Alloction</a></li>
            <li><a href="<?php echo URL::to('user/ta_overview?client_id='.$_GET['client_id'])?>">Overview</a></li>
            <li style="float:right"><a href="<?php echo URL::to('user/ta_system')?>" class="common_black_button">Back to TA System</a></li>
      </ul>
    </div>
  <div class="col-lg-12" style="padding-top: 20px;">
    <div class="tab-content">
      <div class="result_div">
        <div class="col-lg-7">

          <div style="width: auto; height: auto; float: left;">
            <input type="checkbox" id="show_unallocated" name="">
            <label for="show_unallocated">Show unallocated time only</label>
          </div>
          <div style="width: auto; height:auto; float: right;">
            <a href="javascript:" class="common_black_button download_csv_allocated_tasks">Export</a>
          </div>
          <div class="col-lg-12 padding_00 text-center" style="font-size: 18px; font-weight: bold;">Time for <span class="client_name_class"><?php echo $client_name; ?></span></div>

          <div class="table-responsive" style="max-width: 100%; float: left;margin-top:25px; max-height: 500px; overflow: hidden; overflow-y: scroll;height:500px;width:100%">
            <table class="display nowrap fullviewtablelist" id="time_expand">
              <thead>
              <tr style="background: #fff;">
                  <th width="2%" style="text-align: left;">
                    <input type="checkbox" class="select_all_class" id="select_all_class" /><label>&nbsp;</label>
                  </th>
                  <th style="text-align: left;">Date</th>
                  <th style="text-align: left;">Task</th>
                  <th style="text-align: left;">User (Rate)</th>
                  <th style="text-align: left;">Job Time</th>
                  <th style="text-align: left;">Cost</th>
                  <th style="text-align: left;">Allocation</th>
              </tr>
              </thead>
              <tbody class="time_table_class">
                <?php echo $result_time_job; ?>
              </tbody>
            </table>
          </div>
          <div style="width: 100%; height:35px; float: right;">
            <a href="javascript:" style="width:100%; float: left; margin:0px;" class="common_black_button allocate_button">Allocate Selected Time to Active Invoice</a>
          </div>          
        </div>
        <div class="col-lg-5">


          <div style="width: auto; height:30px; float: right;">
            <a href="javascript:" class="common_black_button download_csv_allocated_invoices">Export</a>
          </div>
          <div class="col-lg-12 padding_00 text-center" style="font-size: 18px; font-weight: bold;">Invoices for <span class="client_name_class"><?php echo $client_name; ?></span>
            <input type="hidden" value="" class="select_invoice" name="">
            <input type="hidden" value="" class="select_invoice_click" name="">
          </div>

          <div class="table-responsive" style="max-width: 100%; float: left;margin-top:25px; max-height: 500px; overflow: hidden; overflow-y: scroll;width:100%">
            <table class="display nowrap fullviewtablelist" id="invoice_expand">
              <thead>
              <tr style="background: #fff;">
                  <th width="2%" style="text-align: left;">S.No</th>
                  <th style="text-align: left;">Invoice No</th>
                  <th style="text-align: left;">Date</th>
                  <th style="text-align: left;">Net</th>
                  <th style="text-align: left;">Vat</th>
                  <th style="text-align: left;">Gross</th>
              </tr>
              </thead>
              <tbody class="invoice_table_class">  
                <?php echo $result_invoice; ?>       
              </tbody>
            </table>
          </div>

          <div style="width: 100%; height:35px; float: right;">
            <a href="javascript:" style="width:100%; float: left; margin:0px;" class="common_black_button activate_class">Activate Selected Invoice</a>
          </div>          
        </div>
        

        <div class="clearfix"></div>
        <div class="col-lg-2"></div>
        <div class="col-lg-8 ">
          <div class="col-lg-12 padding_00 text-center" style="font-size: 18px; font-weight: bold; margin-top: 70px;"><span class="client_name_class"><?php echo $client_name; ?></span> Active Invoice <span class="invoice_number">item</span></div>

          <div class="table-responsive" style="width: 100%; float: left;margin-top:25px">
            <table class="own_table">
              <thead>
              <tr style="background: #fff;">                  
                  <th style="text-align: left;">Invoice NO</th>
                  <th style="text-align: left;">Date</th>
                  <th style="text-align: left;">VAT</th>
                  <th style="text-align: left;">Net</th>
                  <th style="text-align: left;">Gross</th>
              </tr>
              </thead>
              <tbody class="invoice_item_result">
                <tr><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td></tr>
              </tbody>
            </table>
          </div>
          
        </div>

        <div class="clearfix"></div>
        <div class="col-lg-2"></div>
        <div class="col-lg-8">
          <div class="col-lg-3"></div>
          <div class="col-lg-6 padding_00 text-center" style="font-size: 18px; font-weight: bold; margin-top: 70px;">Time Allocated to <span class="empty_active">Active</span> Invoice <span class="invoice_number"></span></div>
          <div class="col-lg-3">
            <div style="width: auto; height:30px; margin-top: 70px; float: right;">
              <a href="javascript:" class="common_black_button download_csv_active_invoices">Export</a>
            </div>
          </div>

          <div class="table-responsive" style="width: 100%; float: left;margin-top:25px">
            <table class="display nowrap fullviewtablelist" id="time_allocated" style="margin-bottom: 0px;">
              <thead>
              <tr style="background: #fff;">
                  <th width="2%" style="text-align: left;">S.No</th>
                  <th style="text-align: left;">Date</th>
                  <th style="text-align: left;">Task</th>
                  <th style="text-align: left;">User (Rate)</th>
                  <th style="text-align: left; width: 100px;">Job Time</th>
                  <th style="text-align: left; width: 100px;">Cost</th>
                  <th style="text-align: left; width: 100px;">Allocation</th>
              </tr>
              </thead>
              <tbody class="result_invoice_task">
                <tr><td>&nbsp;</td><td></td><td></td><td></td><td></td></td><td></td><td></td></tr>
                
              </tbody>
            </table>
            <table class="own_table" style="margin: 0px; width: 100%;">
                <tr class="result_invoice_total_row"></tr>
            </table>
          </div>
          <div style="width: 100%; height:30px; float: right;">
            <a href="javascript:" style="width:100%; float: left;" class="common_black_button unallocate_button">Unallocate Selected Allocated Time From Active Invoice</a>
          </div>
        </div>


        <div class="clearfix"></div>
        <div class="col-lg-2"></div>        
        <div class="col-lg-8">
          <div class="col-lg-12 padding_00 text-center" style="font-size: 18px; font-weight: bold; margin-top: 70px;">Summary of <span class="empty_active">Active</span> Invoice <span class="invoice_number"></span> to Time allocation            
</div>

          <div class="table-responsive" style="width: 100%; float: left;margin-top:25px">
            <table class="own_table">
              <thead>
              <tr style="background: #fff;">                  
                  <th style="text-align: left;">Invoice</th>
                  <th style="text-align: left;">Net</th>
              </tr>
              </thead>
              <tbody class="time_allocation_class">
                <tr><td>&nbsp;</td><td></td></tr>              
                
              </tbody>
            </table>
          </div>
        

        <div class="table-responsive" style="width: 100%; float: left;margin-top:25px">
            <table class="own_table">
              <thead>
              <tr style="background: #fff;">                  
                  <th style="text-align: left;">Task</th>
                  <th style="text-align: center;">Time</th>
                  <th style="text-align: center;">Cost</th>
                  <th style="text-align: center;"></th>
              </tr>
              </thead>
              <tbody class="summary_class">
                <tr><td>&nbsp;</td><td></td><td></td><td></td></tr>
                
              </tbody>
            </table>
          </div>
        </div>

        </div>



        
      </div>
    </div>
  </div>
</div>

    <!-- End  -->
<div class="main-backdrop"><!-- --></div>



<div class="modal_load"></div>
<input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
<input type="hidden" name="show_alert" id="show_alert" value="">
<input type="hidden" name="pagination" id="pagination" value="1">

<script>
$(window).click(function(e) {

if($(e.target).hasClass('download_csv_allocated_tasks'))
{
  var client_id = $(".client_search").val();
  $.ajax({
    url:"<?php echo URL::to('user/download_csv_allocated_tasks'); ?>",
    type:"post",
    data:{client_id:client_id},
    success:function(result){
      SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);
    }
  })
}
if($(e.target).hasClass('download_csv_allocated_invoices'))
{
  var client_id = $(".client_search").val();
  $.ajax({
    url:"<?php echo URL::to('user/download_csv_allocated_invoices'); ?>",
    type:"post",
    data:{client_id:client_id},
    success:function(result){
      SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);
    }
  })
}
if($(e.target).hasClass('download_csv_active_invoices'))
{
  var client_id = $(".client_search").val();
  var invoice = $(".select_invoice_click").val();
  if(invoice == "")
  {
    alert('Please Select any one invoice as active then select the export option.');
  }
  else{
    $.ajax({
      url:"<?php echo URL::to('user/download_csv_active_invoices'); ?>",
      type:"post",
      data:{client_id:client_id,invoice:invoice},
      success:function(result){
        SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);
      }
    })
  }
}
if($(e.target).hasClass('single_summary'))
{
  var client_id = $(".client_search").val();
  var invoice = $(".select_invoice_click").val();
  var task_type = $(e.target).attr("data-element");

  if(invoice == "")
  {
    alert('Please Select any one invoice as active then select the export option.');
  }
  else{
    $.ajax({
      url:"<?php echo URL::to('user/download_csv_task_summary'); ?>",
      type:"post",
      data:{client_id:client_id,invoice:invoice,task_type:task_type},
      success:function(result){
        SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);
      }
    })
  }
}

if(e.target.id == "select_all_class")
{
  if($(e.target).is(":checked"))
  {
    $(".select_task").each(function() {
      $(this).prop("checked",true);
    });
  }
  else{
    $(".select_task").each(function() {
      $(this).prop("checked",false);
    });
  }
}


if($(e.target).hasClass('invoice_class')){

  var editid = $(e.target).attr("data-element");
  $.ajax({
        url: "<?php echo URL::to('user/invoices_print_view') ?>",
        data:{id:editid},
        dataType:'json',
        type:"post",
        success:function(result){      
           $(".invoice_modal").modal("show");
           $("body").removeClass("loading");  
           $("#invoice_number_pdf").val(editid);
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


if($(e.target).hasClass('saveas_pdf'))
{
  var htmlcontent = $("#letterpad_modal").html();
  var inv_no = $("#invoice_number_pdf").val();
  $.ajax({
    url:"<?php echo URL::to('user/invoice_saveas_pdf'); ?>",
    data:{htmlcontent:htmlcontent,inv_no:inv_no},
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
if($(e.target).hasClass('invoice_item_class')){
  var invoice = $(e.target).attr("data-element");
  $(".select_invoice").val(invoice);
}


if($(e.target).hasClass('activate_class')){
  var select_invoice = $(".select_invoice").val();
  $(".select_invoice_click").val(select_invoice);
  if(select_invoice == "")
  {
    alert("Please select invoice.")
  }
  else{
    $('body').addClass('loading');  
    var client_id = $("#client_search").val();
    var invoice = $(".select_invoice").val();
    $("#time_allocated").dataTable().fnDestroy();
    
    
    $.ajax({
      url:"<?php echo URL::to('user/ta_invoice_update'); ?>",
      type:"post",
      data:{invoice:invoice,client_id:client_id},
      dataType:"json",
      success: function(result)
      {
        $(".invoice_item_result").html(result['output_invoice']);
        $(".result_invoice_task").html(result['result_invoice_task']);
        $(".time_allocation_class").html(result['result_net']);
        $(".summary_class").html(result['result_summary']);
        $(".result_invoice_total_row").html(result['result_invoice_total_row']);

        $(".invoice_number").html('#'+invoice);
        $(".empty_active").html('');
        $('body').removeClass('loading');  
        $('#time_allocated').DataTable({        
            autoWidth: true,
            scrollX: false,
            fixedColumns: false,
            searching: false,
            paging: false,
            info: false
        });
        

        
        
      }
    });
  }
}







if($(e.target).hasClass('allocate_button'))
{
  var select_invoice = $(".select_invoice").val();
  var select_invoice_click = $(".select_invoice_click").val();

  if(select_invoice == "")
  {
    alert("Please select invoice.")
  }
  else if(select_invoice_click == ""){
    alert("Please click Activate Selected Invoice Button.")
  }
  else if($(".select_task:checked").length)
    {
      $("body").addClass("loading");
        var invoice = $(".select_invoice_click").val();
        var client_id = $("#client_search").val();
        var checkedvalue = '';
        var size = 100;
        $(".select_task:checked").each(function() {
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
              $("#time_expand").dataTable().fnDestroy();
              $("#time_allocated").dataTable().fnDestroy();
              $.ajax({
                url:"<?php echo URL::to('user/ta_tasks_update'); ?>",
                type:"post",
                dataType:"json",
                data:{value:imp,invoice:invoice,client_id:client_id},
                success: function(result)
                {
                  $(".result_invoice_task").html(result['result_invoice_task']);
                  $(".time_allocation_class").html(result['result_net']);
                  $(".summary_class").html(result['result_summary']);
                  $(".time_table_class").html(result['result_time_job']);
                  $(".result_invoice_total_row").html(result['result_invoice_total_row']);

                  if($("#show_unallocated").is(":checked"))
                  {
                    $(".allocated_row").hide();                    
                  }
                  else{
                    $(".allocated_row").show();                    
                  } 

                  $("body").removeClass("loading");

                  $('#time_expand').DataTable({        
                      autoWidth: true,
                      scrollX: false,
                      fixedColumns: false,
                      searching: false,
                      paging: false,
                      info: false
                  });

                  $('#time_allocated').DataTable({        
                      autoWidth: true,
                      scrollX: false,
                      fixedColumns: false,
                      searching: false,
                      paging: false,
                      info: false
                  });
                  


                  
                }
              });
            }, 3000);
        });
    }
    else{
      $("body").removeClass("loading");
      alert("Please Choose atleast one Task to continue.");
    }
    
}



if($(e.target).hasClass('unallocate_button'))
{
  var select_invoice = $(".select_invoice").val();
  var select_invoice_click = $(".select_invoice_click").val();

  if(select_invoice == "")
  {
    alert("Please select invoice.")
  }
  else if(select_invoice_click == ""){
    alert("Please click Activate Selected Invoice Button.")
  }
  else if($(".select_task_unallocate:checked").length)
    {
      var invoice_number = $(".select_invoice_click").val();
      var r = confirm("Do you want to Unallocate the selected time from #"+invoice_number);
      if(r == true){
        $("body").addClass("loading");
        

        var invoice = $(".select_invoice_click").val();
        var client_id = $("#client_search").val();
        var checkedvalue = '';
        var size = 100;
        $(".select_task_unallocate:checked").each(function() {
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
              $("#time_expand").dataTable().fnDestroy();
              $("#time_allocated").dataTable().fnDestroy();
              var imp = value.join(',');
              
              $.ajax({
                url:"<?php echo URL::to('user/ta_tasks_update_unallocate'); ?>",
                type:"post",
                dataType:"json",
                data:{value:imp,invoice:invoice,client_id:client_id},
                success: function(result)
                {
                  $(".time_table_class").html(result['result_time_job']);
                  $(".result_invoice_task").html(result['result_invoice_task']);
                  $(".time_allocation_class").html(result['result_net']);
                  $(".summary_class").html(result['result_summary']);
                  $(".result_invoice_total_row").html(result['result_invoice_total_row']);

                  if($("#show_unallocated").is(":checked"))
                  {
                    $(".allocated_row").hide();                    
                  }
                  else{
                    $(".allocated_row").show();                    
                  } 
                  $("body").removeClass("loading");

                  $('#time_expand').DataTable({        
                      autoWidth: true,
                      scrollX: false,
                      fixedColumns: false,
                      searching: false,
                      paging: false,
                      info: false
                  });

                  $('#time_allocated').DataTable({        
                      autoWidth: true,
                      scrollX: false,
                      fixedColumns: false,
                      searching: false,
                      paging: false,
                      info: false
                  });
                  
                  
                }
              });
            }, 3000);
        });
      }
      else{
        return false
      }
    }
    else{
      $("body").removeClass("loading");
      alert("Please Choose atleast one Task to continue.");
    }
    
}

if(e.target.id == 'show_unallocated')
{  
  if($(e.target).is(":checked"))
  {
    $(".allocated_row").hide();    
  }
  else{
    $(".allocated_row").show();    
  }
}




})
</script>

<!-- Page Scripts -->
<script>

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


$(function(){

    $('#time_expand').DataTable({        
        autoWidth: true,
        scrollX: false,
        fixedColumns: false,
        searching: false,
        paging: false,
        info: false
    });
    $('#invoice_expand').DataTable({        
        autoWidth: true,
        scrollX: false,
        fixedColumns: false,
        searching: false,
        paging: false,
        info: false
    });
    $('#time_allocated').DataTable({        
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
$(document).ready(function() {      
   $("#search_clientid").autocomplete({
      source: function(request, response) {
          $.ajax({
              url:"<?php echo URL::to('user/ta_allocation_client_search'); ?>",
              dataType: "json",
              data: {
                  term : request.term
              },
              success: function(data) {
                  response(data);
                 
              }
          });
      },
      minLength: 1,
      select: function( event, ui ) {
        $("body").addClass("loading");
        $("#client_search").val(ui.item.id);    
        $("#time_expand").dataTable().fnDestroy();
        $("#invoice_expand").dataTable().fnDestroy();
        $.ajax({
          dataType: "json",          
          url:"<?php echo URL::to('user/ta_allocation_client_search_result'); ?>",
          data:{value:ui.item.id},
          success: function(result){
            $(".select_invoice_click").val('');
            $(".select_invoice").val('');

            $(".time_table_class").html(result['result_time_job']);
            $(".invoice_table_class").html(result['result_invoice']);
            $(".client_name_class").html(result['client_name']);
            $(".select_invoice").val('');
            $(".invoice_item_result").html('<tr><td>&nbsp;</td><td></td><td></td>td></td><td></td><<td></td></tr>');
            $(".result_invoice_task").html('<tr><td>&nbsp;</td><td></td><td></td><td></td><td></td>td></td><td></td><td></td></tr>');
            $(".time_allocation_class").html('<tr><td>&nbsp;</td><td></td></tr>');
            $(".summary_class").html('<tr><td>&nbsp;</td><td></td><td></td><td></td></tr>');

            if($("#show_unallocated").is(":checked"))
            {
              $(".allocated_row").hide();              
            }
            else{
              $(".allocated_row").show();              
            }           


            $('#time_expand').DataTable({        
                autoWidth: true,
                scrollX: false,
                fixedColumns: false,
                searching: false,
                paging: false,
                info: false
            });

            $('#invoice_expand').DataTable({        
                autoWidth: true,
                scrollX: false,
                fixedColumns: false,
                searching: false,
                paging: false,
                info: false
            });

            var url = '/user/ta_allocation?client_id='+ui.item.id;
            window.location.replace("<?php echo URL::to('/'); ?>"+url)

            $("body").removeClass("loading");
            
          }
        })
      }
  });     
});
</script>
@stop
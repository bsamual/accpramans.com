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
<?php
DB::table('request_others')->where('other_content','')->where('status',0)->delete();
DB::table('request_purchase_attached')->where('attachment','')->where('url','')->where('status',0)->delete();
DB::table('request_purchase_invoice')->where('specific_invoice','')->where('status',0)->delete();
DB::table('request_sales_attached')->where('attachment','')->where('url','')->where('status',0)->delete();
DB::table('request_sales_invoice')->where('specific_invoice','')->where('sales_invoices','')->where('status',0)->delete();
DB::table('request_bank_statement')->where('bank_id','')->where('statment_number','')->where('status',0)->delete();
DB::table('request_cheque')->where('bank_id','')->where('specific_number','')->where('status',0)->delete();
DB::table('request_cheque_attached')->where('attachment','')->where('url','')->where('status',0)->delete();
?>
<style>
.fa-book,.fa-file-text,.fa-reply,.fa-trash,.fa-eye,.fa-binoculars{
  font-size:21px;
}
.thumbnail{
  margin-bottom:-7px;
  pointer-events: none;
}
body{
  background: #f5f5f5 !important;
}
.color_pallete_blue{
  padding:5px 10px;
  background: blue;
      border-radius: 6px;
    margin-left: 10px;
    float: right;
    color:#fff;
}
.color_pallete_green{
  padding:5px 10px;
  background: green;
      border-radius: 6px;
    margin-left: 10px;
    float: right;
    color:#fff;
}
.color_pallete_red{
  padding:5px 10px;
  background: red;
      border-radius: 6px;
    margin-left: 10px;
    float: right;
    color:#fff;
}

.color_pallete_blue:hover{
  padding:5px 10px;
  background: blue;
      border-radius: 6px;
    margin-left: 10px;
    float: right;
    color:#fff;
}
.color_pallete_green:hover{
  padding:5px 10px;
  background: green;
      border-radius: 6px;
    margin-left: 10px;
    float: right;
    color:#fff;
}
.color_pallete_red:hover{
  padding:5px 10px;
  background: red;
      border-radius: 6px;
    margin-left: 10px;
    float: right;
    color:#fff;
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
<div class="modal fade view_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal_view_result"></div>
      </div>
  </div>
</div>
<div class="modal fade delete_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="z-index:999999999">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Delete</h5>
        </div>
        <div class="modal-body">
          <div class="sub_title3 alert_content" style="line-height: 25px;">
            Are you sure you want to delete this Request?
          </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="common_black_button yes_delete">Yes</button>
            <button type="button" class="common_black_button no_delete">No</button>
        </div>
      </div>
    </div>
</div>
<div class="content_section" style="margin-bottom:200px">
  <div class="page_title">
        <h4 class="col-lg-3" style="padding: 0px;">
                Client Request Manager              
            </h4>
            <div class="col-lg-4 text-right" style="padding-right: 0px; line-height: 35px;">
                
            </div>
            <div class="col-lg-5">
              <div class="select_button">
                  <ul style="float: right;">                                    
                  <li><a href="<?php echo URL::to('user/client_request_system/')?>" style="font-size: 13px; font-weight: 500;">Back</a></li>
                </ul>
              </div>
            </div>
  <div class="table-responsive" style="max-width: 100%; float: left;margin-bottom:30px; margin-top:55px">
  </div>
  <div style="clear: both;">
   <?php
    if(Session::has('message')) { ?>
        <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
     <?php }   
    ?>
    </div> 

<style type="text/css">
.refresh_icon{margin-left: 10px;}
.refresh_icon:hover{text-decoration: none;}
.datepicker-only-init_date_received, .auto_save_date{cursor: pointer;}
.bootstrap-datetimepicker-widget{width: 300px !important;}
.image_div_attachments P{width: 100%; height: auto; font-size: 13px; font-weight: normal; color: #000;}
.margin_top_10{margin-top: 10px;}
</style>

<div class="col-lg-12">
  <div class="col-lg-2"><b>Client Id:</b></div>
  <div class="col-lg-4"><span style="font-weight: normal;"><?php echo $client_details->client_id?></span></div>
  <div class="col-lg-6">
      <div>
        <a href="javascript:" class="color_pallete_blue thumbnail">Sent and fully Received</a>
        
      </div>
  </div>
</div>
<div class="col-lg-12 margin_top_10">
  <div class="col-lg-2"><b>Company:</b></div>
  <div class="col-lg-4"><span style="font-weight: normal;"><?php echo $client_details->company?></span></div>
  <div class="col-lg-6">
      <div>
        
        <a href="javascript:" class="color_pallete_green thumbnail">Sent but NOT fully Received</a>
        
      </div>
  </div>
</div>
<div class="col-lg-12 margin_top_10">
  <div class="col-lg-2"><b>Name:</b></div>
  <div class="col-lg-4"><span style="font-weight: normal;"><?php echo $client_details->firstname?> <?php echo $client_details->surname?></span></div>
  <div class="col-lg-6">
      <div>
        
        <a href="javascript:" class="color_pallete_red thumbnail">Not Yet Sent</a>
      </div>
  </div>
</div>
<div class="col-lg-12 margin_top_10">
  <div class="col-lg-2"><b>Primary Email:</b></div>
  <div class="col-lg-4"><span style="font-weight: normal;"><?php echo $client_details->email?></span></div>
  
</div>
<div class="col-lg-12 margin_top_10">
  <div class="col-lg-2"><b>Secondary Email:</b></div>
  <div class="col-lg-4"><span style="font-weight: normal;"><?php echo $client_details->email2?></span></div>
  <div class="col-lg-6">
      <div class="select_button">
          <ul style="float: right;">                                    
          <li><a href="<?php echo URL::to('user/request_new_add/'.base64_encode($client_details->client_id))?>" style="font-size: 13px; font-weight: 500;">New Request</a></li>
        </ul>
      </div>
  </div>
</div>
<div class="col-lg-12 margin_top_10">
</div>





<table class="display nowrap fullviewtablelist" id="crm_expand" width="100%" style="max-width: 100%;">
                        <thead>
                        <tr style="background: #fff;">
                             <th width="2%" style="text-align: left;">S.No</th>
                            <th style="text-align: left;">Request Date</th>
                            <th style="text-align: left;">Request From</th>
                            <th style="text-align: left;">Date Sent</th>
                            <th style="text-align: left;">Subject</th>
                            <th style="text-align: center;">Action</th>
                            
                        </tr>
                        </thead>                            
                        <tbody id="clients_tbody">
                          <?php
                          $i=1;
                          $output='';
                          if(count($crmlist)){
                            foreach ($crmlist as $crm) {
                              $user_details = DB::table('user')->where('user_id', $crm->request_from)->first();
                              $category_details = DB::table('request_category')->where('category_id', $crm->category_id)->first();

                              if($crm->request_date === '0000-00-00'){
                                $request_date = '';
                              }
                              else{
                                $request_date = date('d-M-Y', strtotime($crm->request_date));
                              }

                              if($crm->request_sent === '0000-00-00 00:00:00'){
                                $request_sent = '';
                              }
                              else{
                                $request_sent = date('d-M-Y', strtotime($crm->request_sent));
                              }

                              if($crm->request_from != ''){
                                $request_from = $user_details->lastname.'&nbsp;'.$user_details->firstname;
                              }
                              else{
                                $request_from = '';
                              }

                              if($crm->category_id != ''){
                                $category_name = $category_details->category_name;
                              }
                              else{
                                $category_name = '';
                              }

                              if($crm->status == 0){
                                $received_all = '';

                                $colorval = '#f00';
                                $delete_crm = '<a href="javascript:" class="delete_request" data-element="'.base64_encode($crm->request_id).'" title="Delete" data-toggle="tooltip" style="color:'.$colorval.'"><i class="fa fa-trash delete_request" aria-hidden="true" data-element="'.base64_encode($crm->request_id).'"></i></a>&nbsp;&nbsp;';
                              }
                              else{
                                $delete_crm = '';
                                $check_received_purchase = DB::table('request_purchase_invoice')->where('request_id',$crm->request_id)->where('status',0)->count();
                                $check_received_purchase_attached = DB::table('request_purchase_attached')->where('request_id',$crm->request_id)->where('status',0)->count(); 

                                $check_received_sales = DB::table('request_sales_invoice')->where('request_id',$crm->request_id)->where('status',0)->count();
                                $check_received_sales_attached = DB::table('request_sales_attached')->where('request_id',$crm->request_id)->where('status',0)->count();

                                $check_received_bank = DB::table('request_bank_statement')->where('request_id',$crm->request_id)->where('status',0)->count();

                                $check_received_cheque = DB::table('request_cheque')->where('request_id',$crm->request_id)->where('status',0)->count();
                                $check_received_cheque_attached = DB::table('request_cheque_attached')->where('request_id',$crm->request_id)->where('status',0)->count();

                                $check_received_others = DB::table('request_others')->where('request_id',$crm->request_id)->where('status',0)->count();

                                $check_purchase = DB::table('request_purchase_invoice')->where('request_id',$crm->request_id)->count();
                                $check_purchase_attached = DB::table('request_purchase_attached')->where('request_id',$crm->request_id)->count(); 

                                $check_sales = DB::table('request_sales_invoice')->where('request_id',$crm->request_id)->count();
                                $check_sales_attached = DB::table('request_sales_attached')->where('request_id',$crm->request_id)->count();

                                $check_bank = DB::table('request_bank_statement')->where('request_id',$crm->request_id)->count();

                                $check_cheque = DB::table('request_cheque')->where('request_id',$crm->request_id)->count();
                                $check_cheque_attached = DB::table('request_cheque_attached')->where('request_id',$crm->request_id)->count();

                                $check_others = DB::table('request_others')->where('request_id',$crm->request_id)->count();

                                $countval_not_received = $check_received_purchase + $check_received_purchase_attached + $check_received_sales + $check_received_sales_attached + $check_received_bank + $check_received_cheque + $check_received_cheque_attached + $check_received_others;

                                $countval = $check_purchase + $check_purchase_attached + $check_sales + $check_sales_attached + $check_bank + $check_cheque + $check_cheque_attached + $check_others;

                                if($countval_not_received == 0)
                                {
                                  $colorval = 'blue';
                                }
                                else{
                                  $colorval = 'green';
                                }

                                $received_all = '<a href="'.URL::to('user/request_received_all/'.base64_encode($crm->request_id)).'"php title="Received All" data-toggle="tooltip" style="color:'.$colorval.'"><i class="fa fa-reply" aria-hidden="true"></i></a>';
                              }


                              $output.='
                              <tr>
                                <td style="color:'.$colorval.'">'.$i.'</td>
                                <td><a href="'.URL::to('user/client_request_edit/'.base64_encode($crm->request_id)).'" style="color:'.$colorval.'">'.$request_date.'</a></td>
                                <td><a href="'.URL::to('user/client_request_edit/'.base64_encode($crm->request_id)).'" style="color:'.$colorval.'">'.$request_from.'</a></td>
                                <td style="color:'.$colorval.'">'.$request_sent.'</td>
                                <td style="color:'.$colorval.'">Information Request: '.$crm->year.' &#8212; '.$category_name.'</td>
                                <td align="center">
                                <a href="javascript:" title="Send" data-toggle="tooltip" style="display:none" style="color:'.$colorval.'"><i class="fa fa-envelope" aria-hidden="true"></i></a>&nbsp;&nbsp;
                                <a href="javascript:" title="Notify" data-toggle="tooltip" style="display:none" style="color:'.$colorval.'"><i class="fa fa-bell" aria-hidden="true"></i></a>&nbsp;&nbsp;

                                <a href="'.URL::to('user/client_request_edit/'.base64_encode($crm->request_id)).'" title="Request" data-toggle="tooltip" style="color:'.$colorval.'"><i class="fa fa-book" aria-hidden="true"></i></a>&nbsp;&nbsp;
                                '.$delete_crm.'                                
                                <a href="javascript:" title="View" data-toggle="tooltip" style="color:'.$colorval.'"><i class="fa fa-binoculars view_class" aria-hidden="true" data-element="'.$crm->request_id.'"></i></a>&nbsp;&nbsp;
                                '.$received_all.'
                                </td>
                              </tr>
                              ';
                              $i++;
                            }
                          }                          
                          else{
                            $output='
                            <tr><td></td>
                            <td></td>
                            <td></td>
                            <td align="left">Empty</td>
                            <td></td>
                            <td></td></tr>';
                          }
                          echo $output;
                          ?>
                            
                        </tbody>
                    </table>
</div>
    <!-- End  -->
<div class="main-backdrop"><!-- --></div>
<div id="print_image">
    
</div>
<div id="report_pdf_type_two" style="display:none">
  <style>
  .table_style {
      width: 100%;
      border-collapse:collapse;
      border:1px solid #c5c5c5;
  }
  </style>
  <table class="table_style">
    <thead>
      <tr>
      <td style="text-align: left;border:1px solid #000;">#</td>
      <td style="text-align: left;border:1px solid #000;">Client Id</td>
      <td style="text-align: left;border:1px solid #000;">Company</td>
      <td style="text-align: left;border:1px solid #000;">First Name</td>
      <td style="text-align: left;border:1px solid #000;">Surname</td>
      <td style="text-align: left;border:1px solid #000;">Client Source</td>
      <td style="text-align: left;border:1px solid #000;">Date Client Since</td>
      <td style="text-align: left;border:1px solid #000;">Client Identity</td>      
      <td style="text-align: left;border:1px solid #000;">Bank Account</td>
      <td style="text-align: left;border:1px solid #000;">File Review</td>
      <td style="text-align: left;border:1px solid #000;">Risk Category</td>
      </tr>
    </thead>
    <tbody id="report_pdf_type_two_tbody">

    </tbody>
  </table>
</div>



<div id="report_pdf_type_two_invoice" style="display:none">
  <style>
  .table_style {
      width: 100%;
      border-collapse:collapse;
      border:1px solid #c5c5c5;
  }
  </style>

  <h3 id="pdf_title_inivoice" style="width: 100%; text-align: center; margin: 15px 0px; float: left;">List of Invoices issued to <span class="invoice_filename"></span></h3>  

  <table class="table_style">
    <thead>
      <tr>
      <th width="2%" style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">S.No</th>
      <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Invoice ID</th>
      <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Date</th>
      <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Client ID</th>
      <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px ">Company Name</th>
      <th style="text-align: right; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px ">Net</th>
      <th style="text-align: right; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">VAT</th>
      <th style="text-align: right; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Gross</th>
      </tr>
    </thead>
    <tbody id="report_pdf_type_two_tbody_invoice">

    </tbody>
  </table>
</div>





<div class="modal_load"></div>
<input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
<input type="hidden" name="show_alert" id="show_alert" value="">
<input type="hidden" name="pagination" id="pagination" value="1">
<input type="hidden" name="hidden_delete_req_id" id="hidden_delete_req_id" value="">
<input type="hidden" name="hidden_request_id" id="hidden_request_id" value="">

<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover({
        placement : 'left',
        trigger : 'hover'
    });  
});
$(function(){
    $('#crm_expand').DataTable({
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
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});
</script>



<script>
$(window).click(function(e) {
if($(e.target).hasClass('delete_request'))
{
  var id = $(e.target).attr("data-element");
  $("#hidden_delete_req_id").val(id);
  $(".delete_modal").modal("show");
}
if($(e.target).hasClass('yes_delete'))
{
  var id = $("#hidden_delete_req_id").val();
  window.location.replace("<?php echo URL::to('user/request_delete'); ?>/"+id);
}
if($(e.target).hasClass('no_delete'))
{
  $(".delete_modal").modal("hide");
  $("#hidden_delete_req_id").val("");
}
if($(e.target).hasClass('view_class')){    
  var requestid = $(e.target).attr("data-element");
  $("#hidden_request_id").val(requestid);
  console.log(requestid);
  $.ajax({
      url:'<?php echo URL::to('user/client_request_view'); ?>',
      type:'post',
      data:{requestid:requestid},
      dataType:"json",
      success: function(result){
         $(".view_model").modal('show');  
         $(".modal_view_result").html(result['content']);
      }
    }) 
}
if(e.target.id == "download_view_pdf")
{
  var requestid = $("#hidden_request_id").val();
  $.ajax({
      url:'<?php echo URL::to('user/download_request_view'); ?>',
      type:'post',
      data:{requestid:requestid},
      success: function(result){
        SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);
      }
    }) 
}


})
</script>

@stop
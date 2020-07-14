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
.own_table tr:last-child td{
  border-bottom:0px solid;
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

.at_auto_allocation_yes:hover{text-decoration:none;}

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
        <input type="hidden" class="invoice_select" name="">
        <input type="hidden" class="invoice_select_click" name="">
      </div>
    </div>  
</div>
<?php
if(isset($_GET['client_id']))
{
  $client_id = $_GET['client_id'];
  $client_details = DB::table('cm_clients')->where('client_id', $client_id)->first();

  $invoicelist = DB::table('invoice_system')->where('client_id', $client_id)->orderBy('invoice_date', 'DESC')->orderBy('invoice_number', 'DESC')->get();
  $result_invoice='';
  if(count($invoicelist)){
    foreach ($invoicelist as $invoice) {
      $result_invoice.='
      <tr>
        <td style="width:50px;"><input type="radio" name="invoice_item" class="select_invoice" data-element="'.$invoice->invoice_number.'" /><label>&nbsp;</label></td>
        <td><a href="javascript:" data-element="'.$invoice->invoice_number.'" class="invoice_class">'.$invoice->invoice_number.'</a></td>
        <td>'.date('d-M-Y', strtotime($invoice->invoice_date)).'</td>
        <td style="text-align:right">'.number_format_invoice_without_decimal($invoice->inv_net).'</td>
        <td style="text-align:right">'.number_format_invoice_without_decimal($invoice->vat_value).'</td>            
        <td style="text-align:right">'.number_format_invoice_without_decimal($invoice->gross).'</td>            
      </tr>
      ';
    }
  }
  else{
    $result_invoice='
    <tr>
      <td></td>
      <td></td>
      <td align="right">Empty Invoice</td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    ';
  }

  $company = ' to '. $client_details->company;
}
else{
  $result_invoice = '';
  $company = '';
}
?>
  <div class="col-lg-12" style="margin-top: 20px;">
        <ul class="nav nav-tabs" role="tablist">
            <li><a href="<?php echo URL::to('user/ta_allocation?client_id='.$_GET['client_id'])?>">Allocation</a></li>
            <li class="active"><a href="<?php echo URL::to('user/ta_auto_allocation?client_id='.$_GET['client_id'])?>">Auto Alloction</a></li>
            <li><a href="<?php echo URL::to('user/ta_overview?client_id='.$_GET['client_id'])?>">Overview</a></li>
            <li style="float:right"><a href="<?php echo URL::to('user/ta_system')?>" class="common_black_button">Back to TA System</a></li>
      </ul>
    </div>
  <div class="col-lg-12" style="padding-top: 20px;">
    <div class="tab-content">

      <div class="row">
      <div class="col-lg-5">
        <div class="col-lg-12 padding_00 text-center" style="font-size: 15px; font-weight: bold;">Invoices Issued<span class="company_name"><?php echo $company; ?></span></div>
        
        <div class="table-responsive" style="width: 100%; float: left;margin-top:25px; height: 650px; overflow: hidden; overflow-y: scroll;">
          <table class="display nowrap fullviewtablelist" id="time_expand">
            <thead>
              <tr style="background: #fff;">
                <th></th>
                <th>Invoice No</th>
                <th>Date</th>
                <th>Net</th>
                <th>VAT</th>
                <th>Gross</th>
              </tr>
            </thead>
            <tbody class="result_invoice">
              <?php echo $result_invoice; ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-lg-1 padding_00 text-center" style="padding-top: 110px;">
        <a href="javascript:" class="common_black_button review_select_class" style="width: 100%; float: left;">Review <br/>Selected Invoice</a>
      </div>
      <div class="col-lg-6">
        <div class="col-lg-12 padding_00 text-center" style="font-size: 15px; font-weight: bold;">Selected Invoice <span class="invoice_number"></span></div>
        <div class="table-responsive" style="width: 100%; float: left;margin-top:25px; max-height: 500px; overflow: hidden; overflow-y: scroll;">
          <table class="own_table">
            <thead>
              <tr style="background: #fff;">                
                <th>Invoice No</th>
                <th>Date</th>
                <th>Net</th>
                <th>VAT</th>
                <th>Gross</th>
              </tr>
            </thead>
            <tbody class="result_invoice_single">
              <tr>                
                <td>&nbsp;</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="col-lg-12 padding_00 review_letterpad" style="width: 100%; height: 500px; margin-top: 50px;  overflow:scroll; display: none;  ">
          <div style="width: 900px;height:1235px; float: left; background:url('<?php echo URL::to('assets/invoice_letterpad.jpg');?>') no-repeat">
            <div class="company_details_classreview"></div>
            <div class="tax_details_class_maindiv">
              <div class="details_table" style="width: 80%; height: auto; margin: 0px 10%;">
                <div class="class_row class_row1review"></div>
                <div class="class_row class_row2review"></div>
                <div class="class_row class_row3review"></div>
                <div class="class_row class_row4review"></div>
                <div class="class_row class_row5review"></div>
                <div class="class_row class_row6review"></div>
                <div class="class_row class_row7review"></div>
                <div class="class_row class_row8review"></div>
                <div class="class_row class_row9review"></div>
                <div class="class_row class_row10review"></div>
                <div class="class_row class_row11review"></div>
                <div class="class_row class_row12review"></div>
                <div class="class_row class_row13review"></div>
                <div class="class_row class_row14review"></div>
                <div class="class_row class_row15review"></div>
                <div class="class_row class_row16review"></div>
                <div class="class_row class_row17review"></div>
                <div class="class_row class_row18review"></div>
                <div class="class_row class_row19review"></div>
                <div class="class_row class_row20review"></div>
              </div>
            </div>
            <input type="hidden" name="invoice_number_pdf" id="invoice_number_pdf" value="">
            <div class="tax_details_classreview"></div> 
          </div>
        </div>
        
      </div>
    </div>

    <div class="row" style="margin-top: 100px;">
      <div class="col-lg-5">
        <div class="col-lg-12 padding_00 text-center" style="font-size: 15px; font-weight: bold;">Tasks allocated to this client</div>
        
        <div class="table-responsive" style="width: 100%; float: left;margin-top:25px">
          <table class="own_table">
            <thead>
              <tr style="background: #fff;">
                <th style="width: 50px;"></th>
                <th>Task Name</th>
              </tr>
            </thead>
            <tbody class="result_time_task">
              <tr><td colspan="2">Empty</td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="col-lg-1 text-center padding_00" >
        <div class="col-lg-12 padding_00"  style="padding-top: 110px;">
          <a href="javascript:" class="common_black_button allocate_task_button" style="width: 100%; float: left;">Allocate this task to Selected Invoice</a>
        </div>
        <div class="col-lg-12 padding_00"  style="padding-top: 50px;">
          <a href="javascript:" class="common_black_button unallocate_task_button" style="width: 100%; float: left;">Unallocate this task to Selected Invoice</a>
        </div>
        
      </div>

      <div class="col-lg-6">
        <div class="col-lg-12 padding_00 text-center" style="font-size: 15px; font-weight: bold;">Tasks Allocated To This Invoice</div>
        
        <div class="table-responsive" style="width: 100%; float: left;margin-top:25px">
          <table class="own_table">
            <thead>
              <tr style="background: #fff;">
                <th style="width: 50px;"></th>
                <th>Task Name</th>
                <th align="center" style="width:90px;">Action</th>
              </tr>
            </thead>
            <tbody class="result_allocated_task">
              <tr><td colspan="3">Empty</td></tr>
            </tbody>
          </table>
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
<input type="hidden" name="hidden_at_id" id="hidden_at_id" value="">

<script>
$(window).click(function(e) {
  if($(e.target).hasClass('review_select_class')){
    var invoice = $(".invoice_select").val();    
    $(".invoice_select_click").val(invoice);

    var client_id = $(".client_search").val();
    if(invoice == "")
    {
      alert("Please select invoice.")
    }
    else{
        $("body").addClass("loading");
        $.ajax({
        url:"<?php echo URL::to('user/ta_auto_allocation_invoice'); ?>",
        data:{invoice:invoice,client_id:client_id},
        type:"post",
        dataType:"json",
        success: function(result)
        {
          $(".invoice_number").html('#'+invoice);
          $(".result_invoice_single").html(result['result_invoice_single']);
          $(".result_time_task").html(result['result_time_task']);
          $(".result_allocated_task").html(result['result_allocated_task']);
          

          $(".company_details_classreview").html(result['companyname']);
           $(".tax_details_classreview").html(result['taxdetails']);
           $(".class_row1review").html(result['row1']);
           $(".class_row2review").html(result['row2']);
           $(".class_row3review").html(result['row3']);
           $(".class_row4review").html(result['row4']);
           $(".class_row5review").html(result['row5']);
           $(".class_row6review").html(result['row6']);
           $(".class_row7review").html(result['row7']);
           $(".class_row8review").html(result['row8']);
           $(".class_row9review").html(result['row9']);
           $(".class_row10review").html(result['row10']);
           $(".class_row11review").html(result['row11']);
           $(".class_row12review").html(result['row12']);
           $(".class_row13review").html(result['row13']);
           $(".class_row14review").html(result['row14']);
           $(".class_row15review").html(result['row15']);
           $(".class_row16review").html(result['row16']);
           $(".class_row17review").html(result['row17']);
           $(".class_row18review").html(result['row18']);
           $(".class_row19review").html(result['row19']);
           $(".class_row20review").html(result['row20']);
           $(".review_letterpad").show();
           $("body").removeClass("loading");
          
        }
      });

    }
  }
  if($(e.target).hasClass('select_invoice')){
    var invoice = $(e.target).attr("data-element");
    $(".invoice_select").val(invoice);
  }


  if($(e.target).hasClass('allocate_task_button')){
    if($(".task_item:checked").length)
      {
          var invoice = $(".invoice_select_click").val();
          var checkedvalue = '';
          $(".task_item:checked").each(function() {
            var value = $(this).parents("tr").find("td").first().next().html();
            if(checkedvalue == "")
            {
              checkedvalue = value;
            }
            else{
              checkedvalue = checkedvalue+", "+value;
            }
          });

          $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>Do you want to automatically allocate all Unassigned time from '"+checkedvalue+"' to invoice '#"+invoice+"'?</p> <p style='text-align:center'><a href='javascript:' class='common_black_button yes_hit'>YES</a> <a href='javascript:' class='common_black_button no_hit'>NO</a></p>", fixed:true,width:'100%'});
      }
      else{
        $("body").removeClass("loading");
        alert("Please Choose atleast one Task to continue.");
      }

  }
  if($(e.target).hasClass('at_auto_allocation_yes')){
    var invoice = $(".invoice_select_click").val();
    var checkedvalue = $(e.target).parents("tr").find("td").first().next().html();
    var at_id = $(e.target).attr("data-element");
    $("#hidden_at_id").val(at_id);
    
    $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>Do you want to automatically allocate all Unassignedtime from '"+checkedvalue+"' to invoice '"+invoice+"'</p> <p style='text-align:center'><a href='javascript:' class='common_black_button yes_individual_hit'>YES</a> <a href='javascript:' class='common_black_button no_individual_hit'>NO</a></p>", fixed:true,width:'100%'});
  }
  if($(e.target).hasClass('no_individual_hit')){
    $.colorbox.close();
  }
  if($(e.target).hasClass('yes_individual_hit')){
    $.colorbox.close();
    $("body").addClass("loading");
    var invoice = $(".invoice_select_click").val();
    var client_id = $("#client_search").val();
    var checkedvalue = $("#hidden_at_id").val();
    $.ajax({
      url:"<?php echo URL::to('user/ta_auto_allocation_tasks_yes_individual'); ?>",
      type:"post",
      data:{value:checkedvalue,invoice:invoice,client_id:client_id},
      success: function(result)
      {
        $("body").removeClass("loading");
        $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>Task are allocated to the selected invoice</p>", fixed:true,width:'20%'});
      }
    });  
  }
  if($(e.target).hasClass('no_hit')){
    $.colorbox.close();
    $("body").addClass("loading");
    var invoice = $(".invoice_select_click").val();
    var client_id = $("#client_search").val();
    var checkedvalue = '';
    var size = 100;
    $(".task_item:checked").each(function() {
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
            url:"<?php echo URL::to('user/ta_auto_allocation_tasks'); ?>",
            type:"post",
            dataType:"json",
            data:{value:imp,invoice:invoice,client_id:client_id},
            success: function(result)
            {
              $(".result_allocated_task").html(result['result_allocated_task']);
              $(".result_time_task").html(result['result_time_task']);
              $("body").removeClass("loading");
              alert("You have just auto allocated the selected tasks to this invoice #"+invoice+" but all the existing time were not allocated to this invoice.");
            }
          });
        }, 3000);
    });
  }
  if($(e.target).hasClass('yes_hit')){
    $.colorbox.close();
    $("body").addClass("loading");
    var invoice = $(".invoice_select_click").val();
    var client_id = $("#client_search").val();
    var checkedvalue = '';
    var size = 100;
    $(".task_item:checked").each(function() {
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
            url:"<?php echo URL::to('user/ta_auto_allocation_tasks_yes'); ?>",
            type:"post",
            dataType:"json",
            data:{value:imp,invoice:invoice,client_id:client_id},
            success: function(result)
            {
              $(".result_allocated_task").html(result['result_allocated_task']);
              $(".result_time_task").html(result['result_time_task']);
              $("body").removeClass("loading");
            }
          });
        }, 3000);
    });
  }

  if($(e.target).hasClass('unallocate_task_button')){

    if($(".task_item_unallocated:checked").length)
      {
          $("body").addClass("loading");

          var invoice = $(".invoice_select_click").val();
          var client_id = $("#client_search").val();
          var checkedvalue = '';
          var size = 100;
          $(".task_item_unallocated:checked").each(function() {
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
                  url:"<?php echo URL::to('user/ta_auto_unallocation_tasks'); ?>",
                  type:"post",
                  dataType:"json",
                  data:{value:imp,invoice:invoice,client_id:client_id},
                  success: function(result)
                  {
                    $(".result_allocated_task").html(result['result_allocated_task']);
                    $(".result_time_task").html(result['result_time_task']);
                    $("body").removeClass("loading");
                   


                    
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
})

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
    
    
});
</script>

<script>
$(document).ready(function() {      
   $("#search_clientid").autocomplete({
      source: function(request, response) {
          $.ajax({
              url:"<?php echo URL::to('user/ta_autoalloaction_client_search'); ?>",
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
        $("#client_search").val(ui.item.id); 
        $("body").addClass("loading");         
        $.ajax({          
          url:"<?php echo URL::to('user/ta_autoalloaction_client_search_result'); ?>",
          data:{value:ui.item.id},
           dataType: "json",
          success: function(result){
            $(".result_invoice").html(result['result_invoice']);
            $(".company_name").html(result['company_name']);
            $(".result_time_task").html('<tr><td colspan="2">&nbsp;</td></tr>');
            $(".result_allocated_task").html('<tr><td colspan="3">&nbsp;</td></tr>');   
            $(".review_letterpad").hide();

            var url = '/user/ta_auto_allocation?client_id='+ui.item.id;
            window.location.replace("<?php echo URL::to('/'); ?>"+url)

            $("body").removeClass("loading");         
          }
        })
      }
  });     
});
</script>
@stop
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
  .attach_p_main{
    margin-top: 9px;
    margin-left: 10px;
    clear: both;
  }
  .attach_p{
    pointer-events: none;
  }
  .goback{
    background: #000;
    text-align: center;
    padding: 8px 12px;
    color: #fff;
  }
  .goback:hover{
    background: #5f5f5f;
    text-decoration: none;
    color: #fff;
  }
#colorbox, #cboxOverlay{z-index:9999999;}
body{
  background: #f5f5f5 !important;
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

.client_request_button{width: 100%;}

.request_textarea{height: 80px !important;}
.ui-widget{z-index: 999999999}

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
<div class="modal fade delete_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="z-index:999999999">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Delete</h5>
        </div>
        <div class="modal-body">
          <div class="sub_title3 alert_content" style="line-height: 25px;">
            Are you sure you want to delete this Request Item?
          </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="common_black_button yes_delete">Yes</button>
            <button type="button" class="common_black_button no_delete">No</button>
        </div>
      </div>
    </div>
</div>

<div class="img_div img_div_purchase" style="z-index:9999999; min-height: 275px">
  <div class="image_div_attachments_purchase">
    <p>You can only upload maximum 300 files at a time. If you drop more than 300 files then the files uploading process will be crashed. </p>
    <form action="<?php echo URL::to('user/crm_upload_images_purchase'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
        <input name="_token" type="hidden" value="">
    </form>                
  </div>
</div>

<div class="img_div img_div_sales" style="z-index:9999999; min-height: 275px">
  <div class="image_div_attachments_sales">
  <p>You can only upload maximum 300 files at a time. If you drop more than 300 files then the files uploading process will be crashed. </p>
  <form action="<?php echo URL::to('user/crm_upload_images_sales'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload1" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
      <input name="_token" type="hidden" value="">
  </form>                
  </div>
</div>

<div class="img_div img_div_cheque" style="z-index:9999999; min-height: 275px">
  <div class="image_div_attachments_cheque">
    <p>You can only upload maximum 300 files at a time. If you drop more than 300 files then the files uploading process will be crashed. </p>
    <form action="<?php echo URL::to('user/crm_upload_images_cheque'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload2" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
        <input name="_token" type="hidden" value="">
    </form>                
  </div>
</div>

<?php 
  $admin_details = Db::table('admin')->first();
  $admin_cc = $admin_details->task_cc_email;
?> 
<div class="modal fade sent_for_approval" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog" role="document">
    <form action="<?php echo URL::to('user/email_for_approval'); ?>" method="post" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Sent Request or Approval</h4>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-md-3">
              <label>From:</label>
            </div>
            <div class="col-md-9">
               <select name="from_user" id="from_user" class="form-control input-sm" value="" required>
                  <option value="">Select User</option>
                  <?php
                    $users = DB::table('user')->where('user_status',0)->where('disabled',0)->where('email','!=', '')->orderBy('firstname','asc')->get();
                    if(count($users))
                    {
                      foreach($users as $user)
                      {
                          ?>
                            <option value="<?php echo $user->user_id; ?>"><?php echo $user->lastname.' '.$user->firstname; ?></option>
                          <?php
                      }
                    }
                  ?>
              </select>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-3">
              <label>To:</label>
            </div>
            <div class="col-md-9">
              <select name="to_user" id="to_user" class="form-control input-sm" value="" required>
                  <option value="">Select User</option>
                  <?php
                    $users = DB::table('user')->where('user_status',0)->where('disabled',0)->where('email','!=', '')->orderBy('firstname','asc')->get();
                    if(count($users))
                    {
                      foreach($users as $user)
                      {
                          ?>
                            <option value="<?php echo $user->user_id; ?>"><?php echo $user->lastname.' '.$user->firstname; ?></option>
                          <?php
                      }
                    }
                  ?>
              </select>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-3">
              <label>CC:</label>
            </div>
            <div class="col-md-9">
              <input type="text" name="cc_approval" class="form-control input-sm" value="<?php echo $admin_cc; ?>" readonly>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-3">
              <label>Subject:</label>
            </div>
            <div class="col-md-9">
              <input type="text" name="subject_approval" class="form-control input-sm subject_approval" value="" required>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-12">
              <label>Message:</label>
            </div>
            <div class="col-md-12">
              <textarea name="message_editor" id="editor">
              </textarea>
            </div>
            <div id="approval_attachments">

            </div>
          </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="request_id_email_approval" id="request_id_email_approval" value="<?php echo $request_details->request_id;?>">
        <input type="submit" class="btn btn-primary common_black_button" value="Send request for Approval">
      </div>
    </div>
    </form>
  </div>
</div>


<div class="modal fade sent_to_client" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog" role="document" style="width:50%">
    <form action="<?php echo URL::to('user/email_to_client'); ?>" method="post" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Sent Request to Client</h4>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-md-3">
              <label>From:</label>
            </div>
            <div class="col-md-9">
               <select name="from_user_to_client" id="from_user_to_client" class="form-control input-sm" value="" required>
                  <option value="">Select User</option>
                  <?php
                    $users = DB::table('user')->where('user_status',0)->where('disabled',0)->where('email','!=', '')->orderBy('firstname','asc')->get();
                    if(count($users))
                    {
                      foreach($users as $user)
                      {
                          ?>
                            <option value="<?php echo $user->user_id; ?>"><?php echo $user->lastname.' '.$user->firstname; ?></option>
                          <?php
                      }
                    }
                  ?>
              </select>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-3">
              <label>To:</label>
            </div>
            <div class="col-md-9">
              <?php 
              $get_client_email_address = DB::table('cm_clients')->where('client_id',$request_details->client_id)->first();
              if($get_client_email_address->email2 == "")
              {
                $email = $get_client_email_address->email;  
              }
              else{
                $email = $get_client_email_address->email.','.$get_client_email_address->email2;
              }
              
              ?>
              <input type="text" class="form-control client_search_class" placeholder="Enter Email Address" name="client_search" value="<?php echo $email; ?>" autocomplete="off" required>
              <!-- <input type="hidden" id="client_search" name="client_search" value=""> -->
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-3">
              <label>CC:</label>
            </div>
            <div class="col-md-9">
              <input type="text" name="cc_approval_to_client" class="form-control input-sm" value="<?php echo $admin_cc; ?>" readonly>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-3">
              <label>Subject:</label>
            </div>
            <div class="col-md-9">
              <input type="text" name="subject_to_client" class="form-control input-sm subject_to_client" value="" required>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-12">
              <label>Message:</label>
            </div>
            <div class="col-md-12">
              <textarea name="message_editor_to_client" id="editor_1">
              </textarea>
            </div>
            <div id="client_attachments">

            </div>
          </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="request_id_email_client" id="request_id_email_client" value="<?php echo $request_details->request_id;?>">
        <input type="submit" class="btn btn-primary common_black_button" value="Send Request to Client">
      </div>
    </div>
    </form>
  </div>
</div>
<div class="modal fade item_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal_result"></div>
      </div>
  </div>
</div>
<div class="modal fade view_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal_view_result"></div>
      </div>
  </div>
</div>


<div class="content_section" style="margin-bottom:200px">
  <div class="page_title">
        <h4 class="col-lg-4" style="padding: 0px;">
                Make / View a Client Request              
            </h4>
            <div class="col-lg-4 text-right" style="padding-right: 0px; line-height: 35px;">
                <a class="goback" href="<?php echo URL::to('user/client_request_manager/'.base64_encode($request_details->client_id)); ?>" style="font-size: 13px; font-weight: 500;">Go Back to Client Request Manager</a>
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
.form-control[disabled]{background: #e6e6e6 !important}
.select_button ul li{margin:0px 25px; }
</style>

<?php

  if($request_details->status == 0){
    $disabled_input = '';
    $disabled_div = '';
  }
  else{
    $disabled_input = 'disabled';
    $disabled_div = 'display:none';
  } 
?>




<div class="col-lg-12 margin_top_10">
  <div class="col-lg-9 padding_00">
    <div class="col-lg-2 padding_00">Year</div>
    <div class="col-lg-5">
      <div class="form-group">
        <select class="form-control year_class" required data-element="<?php echo base64_encode($request_details->request_id)?>" <?php echo $disabled_input?> >   
        <?php
        $starting_year  =date('Y', strtotime('-10 year'));
         $ending_year = date('Y', strtotime('+1 year'));
         $current_year = date('Y');


         for($ending_year; $ending_year >= $starting_year; $ending_year--) {
             echo '<option value="'.$ending_year.'"';
             if( $request_details->year ==  $ending_year ) {
                    echo ' selected="selected"';
             }
             elseif($current_year ==  $ending_year){
                echo 'selected="selected"';
             }
             echo ' >'.$ending_year.'</option>';
         }      
        ?>                 
        </select>
        <input type="hidden" class="client_id" value="<?php echo $request_details->client_id;?>" name="">
        <input type="hidden" class="request_id" value="<?php echo $request_details->request_id;?>" name="">
      </div>      
    </div>
    <div class="clearfix"></div>
    <div class="col-lg-2 padding_00">Category:</div>
    <div class="col-lg-5">
      <div class="form-group">
        <select class="form-control accounts_class" required  <?php echo $disabled_input?>>
            <option value="">Select Category</option>
            <?php
            $outputcategory='';
            if(count($categorylist)){
              foreach ($categorylist as $category) {

                if($category->category_id == $request_details->category_id){$category_select = 'Selected';}else{$category_select = '';}

                $outputcategory.='<option value="'.$category->category_id.'" '.$category_select.'>'.$category->category_name.'</option>';
              }
            }
            else{
              $outputcategory.='<option value="">Empty</option>';
            }
            echo $outputcategory;
            ?>
        </select>   
      </div>   
    </div>
    <div class="clearfix"></div>
    <div class="col-lg-2 padding_00">Employee:</div>
    <div class="col-lg-5">
      <div class="form-group">
        <select class="form-control user_class" required  <?php echo $disabled_input?>>
            <option value="">Select Employee</option>
            <?php
            $outputuser='';
            if(count($userlist)){
              foreach ($userlist as $user) {
                if($user->user_id == $request_details->request_from){$user_select = 'Selected';}else{$user_select = '';}
                $outputuser.='<option value="'.$user->user_id.'" '.$user_select.'>'.$user->lastname.' '.$user->firstname.'</option>';
              }
            }
            else{
              $outputuser.='<option value="">Empty</option>';
            }
            echo $outputuser;
            ?>
        </select>
      </div>      
    </div>
    <div class="clearfix"></div>
    <div class="col-lg-2 padding_00">Subject:</div>
    <div class="col-lg-5">
      <div class="form-group">
        <?php
        $category = DB::table('request_category')->where('category_id', $request_details->category_id)->first();
        ?>
        <div class="information_class">
          <?php
          if($request_details->category_id != ''){
            $category_name = $category->category_name;
          }
          else{
            $category_name = '';
          }
          $client_details = DB::table('cm_clients')->where('client_id',$request_details->client_id)->first();
          ?>

          Information Request: <?php echo $request_details->year;?>  <?php echo $category_name ;?> (<?php echo $client_details->company ;?>)
        </div>
        
      </div>      
    </div>
    <div class="col-lg-12 padding_00" style="margin-top: 25px; <?php echo $disabled_div?>">
      <b>Request Item:</b><br/><br/>

      <div class="request_item_class">

      <?php
      $request_item = DB::table('request_sub_category')->where('category_id', $request_details->category_id)->get();
      $outputitem='';
      if(count($request_item)){
        foreach ($request_item as $item) {
          $outputitem.='<a href="javascript:" style="font-weight:normal;" class="item_class" data-element="'.base64_encode($item->sub_category_id).'">'.$item->sub_category_name.'</a><br/>';
        }
      }
      else{
        $outputitem='Item not found';
      }
      echo $outputitem;
      ?>
      </div>

    </div>

    <div class="col-lg-12 padding_00" style="margin-top: 20px;">
      <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th style="text-align: left;width:20%">Items on this request</th>
            <th style="text-align: left;width:30%"></th>
            <th style="text-align: left;width:45%"></th>
            <th style="text-align: left;width:5%">
              <?php
              if($request_details->status == 0){
                $title_table = 'Delete';
              }
              else{
                $title_table = 'Received';
              }
              echo $title_table;
              ?>
             </th>            
          </tr>
          </thead>
        <tbody>    

    <?php

    $output_purchase_invoice='<div class="col-lg-12 padding_00">';
    if(count($purchaseinvoicelist)){
      foreach ($purchaseinvoicelist as $invoice) {

        if($request_details->status == 0){
          $del_rec_pur='<a title="Delete" class="fa fa-trash delete_request_item" href="javascript:" data-element="'.URL::to('user/request_delete_purchase').'/'.base64_encode($invoice->invoice_id).'"></a>';
        }
        else{
          if($invoice->status == 0){
            $del_rec_pur = '<a title="Received" href="'.URL::to('user/request_purchase_received'.'/'.base64_encode($invoice->invoice_id)).'" style="color: #f00"><i class="fa fa-times"></i></a>';
          }
          else{
            $del_rec_pur = '<a  title="Not Received" href="'.URL::to('user/request_purchase_notreceived'.'/'.base64_encode($invoice->invoice_id)).'" style="color: #33CC66"><i class="fa fa fa-check"></i></a>';
          }

          
        }

        

        $purchase_attached_list = DB::table('request_purchase_attached')->where('purchase_id', $invoice->invoice_id)->get();

        $output_purchase_attach='';

        if(count($purchase_attached_list)){
          foreach ($purchase_attached_list as $purchase_attach) {

            if($request_details->status == 0){
              $del_rec_pur_att='<a title="Delete" class="fa fa-trash delete_request_item" href="javascript:" data-element="'.URL::to('user/request_delete_purchase_attach').'/'.base64_encode($purchase_attach->attached_id).'"></a>';
            }
            else{
              if($purchase_attach->status == 0){
                $del_rec_pur_att = '<a title="Received" href="'.URL::to('user/request_purchase_received_attach'.'/'.base64_encode($purchase_attach->attached_id)).'" style="color: #f00"><i class="fa fa-times"></i></a>'; 
              }
              else{
                $del_rec_pur_att = '<a title="Not Received" href="'.URL::to('user/request_purchase_notreceived_attach'.'/'.base64_encode($purchase_attach->attached_id)).'" style="color: #33CC66"><i class="fa fa-check"></i></a>';
              }
            }
           

            $output_purchase_attach.='<tr>
            <td>Purchase Invoices</td>
            <td>Attached List of Purchase Invoices:</td>
            <td><a href="'.URL::to('/').'/'.$purchase_attach->url.'/'.$purchase_attach->attachment.'" download>'.$purchase_attach->attachment.'</a></td>
            <td align="center">'.$del_rec_pur_att.'</td>
            </tr>
            ';
          }
        }
        else{
          $output_purchase_attach='';
        }

        if($invoice->specific_invoice != "")
        {
          $output_purchase_invoice.='
          <tr>
            <td>Purchase Invoices</td>
            <td>Specific Purchase Invoices</td>
            <td>'.$invoice->specific_invoice.'</td>
            <td align="center">'.$del_rec_pur.'</td>          
          </tr>';
        }
        $output_purchase_invoice.=$output_purchase_attach;
      }     
    }
    else{
      $output_purchase_invoice='';
    }

    echo $output_purchase_invoice;




    $output_sales_invoice='<div class="col-lg-12 padding_00">';
    if(count($salesinvoicelist)){
      foreach ($salesinvoicelist as $invoice) {


        if($request_details->status == 0){
          $del_rec_sal='<a title="Delete" class="fa fa-trash delete_request_item" href="javascript:" data-element="'.URL::to('user/request_delete_sales').'/'.base64_encode($invoice->invoice_id).'"></a>';
        }
        else{
          if($invoice->status == 0 ){
            $del_rec_sal = '<a title="Received" href="'.URL::to('user/request_sales_received'.'/'.base64_encode($invoice->invoice_id)).'" style="color: #f00"><i class="fa fa-times"></i></a>';
          }
          else{
            $del_rec_sal = '<a  title="Not Received"  href="'.URL::to('user/request_sales_notreceived'.'/'.base64_encode($invoice->invoice_id)).'" style="color: #33CC66"><i class="fa fa-check"></i></a>';
          }
        }


        $sales_attached_list = DB::table('request_sales_attached')->where('sales_id', $invoice->invoice_id)->get();

        $output_sales_attach='';

        if(count($sales_attached_list)){
          foreach ($sales_attached_list as $sales_attach) {

            if($request_details->status == 0){
              $del_rec_sal_att ='<a title="Delete" class="fa fa-trash delete_request_item" href="javascript:" data-element="'.URL::to('user/request_delete_sales_attach').'/'.base64_encode($sales_attach->attached_id).'"></a>';
            }
            else{
              if($sales_attach->status == 0){
                $del_rec_sal_att ='<a title="Received" href="'.URL::to('user/request_sales_received_attach'.'/'.base64_encode($sales_attach->attached_id)).'" style="color: #f00"><i class="fa fa-times"></i></a>';
              }
              else{
                $del_rec_sal_att = '<a title="Not Received" href="'.URL::to('user/request_sales_notreceived_attach'.'/'.base64_encode($sales_attach->attached_id)).'" style="color: #33CC66"><i class="fa fa-check"></i></a>';
              }

            }

            $output_sales_attach.='<tr>
            <td>Sales Invoices</td>
            <td>Attached List of Sales Invoices:</td>
            <td><a href="'.URL::to('/').'/'.$sales_attach->url.'/'.$sales_attach->attachment.'" download>'.$sales_attach->attachment.'</a></td>
            <td align="center">'.$del_rec_sal_att.'</td>
            </tr>
            ';
          }
        }
        else{
          $output_sales_attach='';
        }


        if($invoice->specific_invoice != "")
        {
          $output_sales_invoice.='
          <tr>
            <td>Sales Invoices</td>
            <td>Specific Sales Invoice</td>
            <td>'.$invoice->specific_invoice.'</td>
            <td align="center">'.$del_rec_sal.'</td>          
          </tr>';
        }
        if($invoice->sales_invoices != "")
        {
          $output_sales_invoice.='
          <tr>
            <td>Sales Invoices</td>
            <td>Sales Invoices to Specific Customer</td>
            <td>'.$invoice->sales_invoices.'</td>
            <td align="center">'.$del_rec_sal.'</td>          
          </tr>';
        }
        $output_sales_invoice.=$output_sales_attach;
      }     
    }
    else{
      $output_sales_invoice='';
    }

    echo $output_sales_invoice;






    $output_statement='<div class="col-lg-12 padding_00">';
    if(count($bankstatementlist)){
      foreach ($bankstatementlist as $statement) {

        if($request_details->status == 0){
          $del_rec_bank ='<a title="Delete" class="fa fa-trash delete_request_item" href="javascript:" data-element="'.URL::to('user/request_delete_statement').'/'.base64_encode($statement->statement_id).'"></a>';
        }
        else{
          if($statement->status == 0){
            $del_rec_bank = '<a title="Received" href="'.URL::to('user/request_bank_statement'.'/'.base64_encode($statement->statement_id)).'" style="color: #f00"><i class="fa fa-times"></i></a>';
          }
          else{
            $del_rec_bank = '<a title="Not Received" href="'.URL::to('user/request_bank_statement_notreceived'.'/'.base64_encode($statement->statement_id)).'" style="color: #33CC66"><i class="fa fa-check"></i></a>';
          }
        }

        $bank_details = DB::table('aml_bank')->where('id', $statement->bank_id)->first();
        if(count($bank_details))
        {
          $bank_name = $bank_details->bank_name.' '.$bank_details->account_number.' ('.$bank_details->account_name.')';
        }
        else{
          $bank_name = '';
        }
        if($statement->statment_number == ''){
          $result_bank = $bank_name.' From '.date('d-M-Y', strtotime($statement->from_date)).' to '.date('d-M-Y', strtotime($statement->to_date));
        }
        elseif($statement->from_date == '0000-00-00'){
          $result_bank = $bank_name.' Statement Numbers '.$statement->statment_number;
        }
        else{
           $result_bank = $bank_name.' Statement Numbers '.$statement->statment_number.' From '.date('d-M-Y', strtotime($statement->from_date)).' to '.date('d-M-Y', strtotime($statement->to_date));
        }


        $output_statement.='
        <tr>
          <td>Bank Statements</td>
          <td>Statements for:</td>
          <td>'.$result_bank.'</td>
          <td align="center">'.$del_rec_bank.'</td>          
        </tr>
        ';
      }     
    }
    else{
      $output_statement='';
    }

    echo $output_statement;

    
        

    $output_cheque='<div class="col-lg-12 padding_00">';
    if(count($chequebooklist)){
      foreach ($chequebooklist as $cheque) {
        $bank_details = DB::table('aml_bank')->where('id', $cheque->bank_id)->first();

        if($request_details->status == 0){
          $del_rec_che='<a title="Delete" class="fa fa-trash delete_request_item" href="javascript:" data-element="'.URL::to('user/request_delete_cheque').'/'.base64_encode($cheque->cheque_id).'"></a>';
        }
        else{
          if($cheque->status == 0){
            $del_rec_che = '<a title="Received" href="'.URL::to('user/request_cheque_received'.'/'.base64_encode($cheque->cheque_id)).'" style="color: #f00"><i class="fa fa-times"></i></a>';          
          }
          else{
            $del_rec_che = '<a title="Not Received" href="'.URL::to('user/request_cheque_notreceived'.'/'.base64_encode($cheque->cheque_id)).'" style="color: #33CC66"><i class="fa fa-check"></i></a>';          
          }
        }


        

        $cheque_attached_list = DB::table('request_cheque_attached')->where('cheque_id', $cheque->cheque_id)->get();

        $output_cheque_attach='';
        if(count($bank_details))
        {
          $bank_name_cheque = $bank_details->bank_name;
        }
        else{
          $bank_name_cheque = '';
        }
        if(count($cheque_attached_list)){
          foreach ($cheque_attached_list as $cheque_attach) {

            if($request_details->status == 0){
              $del_rec_che_att = '<a title="Delete" class="fa fa-trash delete_request_item" href="javascript:" data-element="'.URL::to('user/request_delete_cheque_attach').'/'.base64_encode($cheque_attach->attached_id).'"></a>';
            }
            else{
              if($cheque_attach->status == 0){
                $del_rec_che_att = '<a title="Received" href="'.URL::to('user/request_cheque_received_attach'.'/'.base64_encode($cheque_attach->attached_id)).'" style="color: #f00"><i class="fa fa-times"></i></a>';              
              }
              else{
                $del_rec_che_att = '<a title="Not Received" href="'.URL::to('user/request_cheque_notreceived_attach'.'/'.base64_encode($cheque_attach->attached_id)).'" style="color: #33CC66"><i class="fa fa-check"></i></a>';
              }
            }

            

            $output_cheque_attach.='<tr>
            <td>Cheque Books</td>
            <td>Attached List of Cheque Books</td>
            <td><a href="'.URL::to('/').'/'.$cheque_attach->url.'/'.$cheque_attach->attachment.'" download>'.$cheque_attach->attachment.'</a></td>
            <td align="center">'.$del_rec_che_att.'</td>
            </tr>
            ';
          }
        }
        else{
          $output_cheque_attach='';
        }

        

        if($cheque->specific_number != "")
        {
          if(count($bank_details))
          {
            $bank_name = $bank_details->bank_name.' '.$bank_details->account_number.' ('.$bank_details->account_name.')';
          }
          else{
            $bank_name = '';
          }

          $output_cheque.='
          <tr>
            <td>Cheque Books</td>
            <td>Specific Cheques</td>
            <td>'.$bank_name.' Specific Cheque Numbers: '.$cheque->specific_number.'</td>
            <td align="center">'.$del_rec_che.'</td>
          </tr>';
        }
        $output_cheque.=$output_cheque_attach;
      }     
    }
    else{
      $output_cheque='';
    }

    echo $output_cheque;


    $output_other='<div class="col-lg-12 padding_00">';
    if(count($otherlist)){
      foreach ($otherlist as $other) {       

      if($request_details->status == 0){
        $del_rec_oth='<a title="Delete" class="fa fa-trash delete_request_item" href="javascript:" data-element="'.URL::to('user/request_delete_other').'/'.base64_encode($other->other_id).'"></a>';
      }
      else{
        if($other->status == 0){
          $del_rec_oth = '<a title="Received" href="'.URL::to('user/request_other_received'.'/'.base64_encode($other->other_id)).'" style="color: #f00"><i class="fa fa-times"></i></a>';          
        }
        else{
          $del_rec_oth = '<a title="Not Received" href="'.URL::to('user/request_other_notreceived'.'/'.base64_encode($other->other_id)).'" style="color: #33CC66"><i class="fa fa-check"></i></a>';          
        }
      }

        
        $output_other.='
        <tr>
          <td>Other Information</td>
          <td></td>
          <td>'.$other->other_content.'</td>
          <td align="center">'.$del_rec_oth.'</td>
        </tr>
        ';
      }     
    }
    else{
      $output_other='';
    }

    echo $output_other;
    ?>
      <tr>
        <td colspan="5">
          <div class="select_button" style="<?php if($request_details->status == 0){echo 'display: none;';}else{echo 'display: block';}?>">
              <ul style="float: right;">
              <li><a href="<?php echo URL::to('user/request_received_all/'.base64_encode($request_details->request_id))?>">All received</a></li>
            </ul>
          </div>
        </td>
      </tr>

      <tr>
        <td colspan="5">
          <div class="select_button">
              <ul style="float: right;">
              <li><a href="javascript:" class="view_class">View full Request</a></li>
              <li><a href="javascript:" class="send_request_for_approval">Send Request for Approval</a></li>
              <li><a href="javascript:" class="send_request_to_client">Send Request to Client</a></li>
            </ul>
          </div>
        </td>
      </tr>
      <tr>
        <td colspan="5">
          <div class="select_button">
            <ul style="float: right;">

              <?php
                $last_sent_approval = DB::table('request_client_email_sent')->where('request_id',$request_details->request_id)->orderBy('id', 'ASC')->where('type', 1)->get();
                if(count($last_sent_approval)){$display_approval = 'block';}else{$display_approval = 'none';}
                ?>
             
              <li style="width: 200px; display: <?php echo $display_approval?>">
                <div style="font-weight: normal; text-align: right;">
                <b>Request sent for Approval:</b><br/>
                <?php                  
                if(count($last_sent_approval))
                {
                  foreach($last_sent_approval as $sent)
                  {
                    $last_email_sent = date('d-M-Y H:i', strtotime($sent->email_sent));
                    echo ''.$last_email_sent.'<br/>';
                  }
                }
                
                ?>
                </div>


              </li>
              <li style="width: 150px;">
                <?php
                  $last_sent = DB::table('request_client_email_sent')->where('request_id',$request_details->request_id)->orderBy('id', 'ASC')->where('type', 0)->get();

                  if(count($last_sent)){$display = 'block';}else{$display = 'none';}
                  ?>
                  <div style="font-weight: normal; text-align: right; display: <?php echo $display?>">

                  <b>Date Sent to Client:</b><br/>
                  <?php                  
                  if(count($last_sent))
                  {
                    foreach($last_sent as $sent)
                    {
                      $last_email_sent = date('d-M-Y H:i', strtotime($sent->email_sent));
                      echo ''.$last_email_sent.'<br/>';
                    }
                  }
                  
                  ?>
                  <div class="select_button">
                        <ul style="float: right; margin-top: 10px;">

                        <li style="margin: 0px;"><a href="javascript:" class="send_request_to_client" style="font-size: 13px; font-weight: 500;">Resend Email</a></li>
                      </ul>
                    </div>
                  </div>
                  
                    
                 
                
                
              </li>
            </ul>
          </div>
        </td>
      </tr>
      </tbody>
      </table>
    </div>
    </div>

    


    

  </div>
  

</div>



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

<?php
  $count = DB::table('request_client_email_sent')->where('request_id',$request_details->request_id)->count();
  $last_sent = DB::table('request_client_email_sent')->where('request_id',$request_details->request_id)->orderBy('id', 'DESC')->first();
  if($count > 0)
  {
    ?>
    <input type="hidden" name="email_already_sent" id="email_already_sent" value="1">
    <input type="hidden" name="last_sent_date" id="last_sent_date" value="<?php echo $last_sent->email_sent; ?>">
    <?php
  }
  else {
    ?>
    <input type="hidden" name="email_already_sent" id="email_already_sent" value="0">
    <input type="hidden" name="last_sent_date" id="last_sent_date" value="">
    <?php 
  }

  $check_received_purchase = DB::table('request_purchase_invoice')->where('request_id',$request_details->request_id)->where('status',0)->count();
  $check_received_purchase_attached = DB::table('request_purchase_attached')->where('request_id',$request_details->request_id)->where('status',0)->count(); 

  $check_received_sales = DB::table('request_sales_invoice')->where('request_id',$request_details->request_id)->where('status',0)->count();
  $check_received_sales_attached = DB::table('request_sales_attached')->where('request_id',$request_details->request_id)->where('status',0)->count();

  $check_received_bank = DB::table('request_bank_statement')->where('request_id',$request_details->request_id)->where('status',0)->count();

  $check_received_cheque = DB::table('request_cheque')->where('request_id',$request_details->request_id)->where('status',0)->count();
  $check_received_cheque_attached = DB::table('request_cheque_attached')->where('request_id',$request_details->request_id)->where('status',0)->count();

  $check_received_others = DB::table('request_others')->where('request_id',$request_details->request_id)->where('status',0)->count();

  $check_purchase = DB::table('request_purchase_invoice')->where('request_id',$request_details->request_id)->count();
  $check_purchase_attached = DB::table('request_purchase_attached')->where('request_id',$request_details->request_id)->count(); 

  $check_sales = DB::table('request_sales_invoice')->where('request_id',$request_details->request_id)->count();
  $check_sales_attached = DB::table('request_sales_attached')->where('request_id',$request_details->request_id)->count();

  $check_bank = DB::table('request_bank_statement')->where('request_id',$request_details->request_id)->count();

  $check_cheque = DB::table('request_cheque')->where('request_id',$request_details->request_id)->count();
  $check_cheque_attached = DB::table('request_cheque_attached')->where('request_id',$request_details->request_id)->count();

  $check_others = DB::table('request_others')->where('request_id',$request_details->request_id)->count();

  $countval_not_received = $check_received_purchase + $check_received_purchase_attached + $check_received_sales + $check_received_sales_attached + $check_received_bank + $check_received_cheque + $check_received_cheque_attached + $check_received_others;

  $countval = $check_purchase + $check_purchase_attached + $check_sales + $check_sales_attached + $check_bank + $check_cheque + $check_cheque_attached + $check_others;

  if($countval == 0 && $countval_not_received == 0)
  {
    echo '<input type="hidden" name="email_sent_type" id="email_sent_type" value="0">'; // No Items Created
  }
  elseif($request_details->status == 0)
  {
     echo '<input type="hidden" name="email_sent_type" id="email_sent_type" value="5">'; // all received
  }
  elseif($countval_not_received == 0)
  {
    echo '<input type="hidden" name="email_sent_type" id="email_sent_type" value="1">'; // all received
  }
  elseif($countval == $countval_not_received)
  {
    echo '<input type="hidden" name="email_sent_type" id="email_sent_type" value="2">'; // None received
  }
  else{
    echo '<input type="hidden" name="email_sent_type" id="email_sent_type" value="3">'; // Some Items received
  }
?>



<div class="modal_load"></div>
<input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
<input type="hidden" name="show_alert" id="show_alert" value="">
<input type="hidden" name="pagination" id="pagination" value="1">
<input type="hidden" name="hidden_delete_req_item_url" id="hidden_delete_req_item_url" value="">


<script>
// $(document).ready(function() {    
//   $(".client_search_class").autocomplete({
//         source: function(request, response) {
//             $.ajax({
//                 url:"<?php echo URL::to('user/aml_client_search'); ?>",
//                 dataType: "json",
//                 data: {
//                     term : request.term
//                 },
//                 success: function(data) {
//                     response(data);
//                 }
//             });
//         },
//         minLength: 1,
//         select: function( event, ui ) {
//           $("#client_search").val(ui.item.id);  
//           var email_sent_type = $("#email_sent_type").val();
//           var last_sent_date = $("#last_sent_date").val();
//           if(email_sent_type == 0)
//           {
//             $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>Nothing on the Request to send. Please Create a New Request item.</p>", fixed:true});
//           }
//           else if(email_sent_type == 1)
//           {
//             $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>This request was already sent to the client on “"+last_sent_date+"” and all items have been marked as received.</p>", fixed:true});
//           }
//           else if(email_sent_type == 2)
//           {
//             if (CKEDITOR.instances.editor_1) CKEDITOR.instances.editor_1.destroy();
//             var requestid = $(".request_id").val();        
//             $.ajax({
//               url:"<?php echo URL::to('user/send_request_to_client_edit'); ?>",
//               type:"post",
//               data:{requestid:requestid,to_user:ui.item.id},
//               dataType:"json",
//               success: function(result){
//                 CKEDITOR.replace('editor_1',
//                  {
//                   height: '300px',
//                  }); 
//                  $(".sent_to_client").modal('show');  
//                  $(".subject_to_client").val(result['subject']);
//                  $("#from_user_to_client").val(result['user_id']);
//                  $(".client_search_class").val(result['client_name']+'-'+result['client_id']);
//              $("#client_search").val(result['client_id']);
//              $("#client_attachments").html(result['attachments']);
//                  CKEDITOR.instances['editor_1'].setData(result['content']);
//               }
//             })
//           }
//           else{
//             if (CKEDITOR.instances.editor_1) CKEDITOR.instances.editor_1.destroy();
//             var requestid = $(".request_id").val();
//             var to_user = '';
//             $.ajax({
//                 url:'<?php echo URL::to('user/send_request_to_client_some_not_edit'); ?>',
//                 type:'post',
//                 data:{requestid:requestid,to_user:ui.item.id},
//                 dataType:"json",
//                 success: function(result){
//                   CKEDITOR.replace('editor_1',
//                    {
//                     height: '300px',
//                    }); 
//                    $(".sent_to_client").modal('show');  
//                    $(".subject_to_client").val(result['subject']);
//                    $("#from_user_to_client").val(result['user_id']);
//                    $(".client_search_class").val(result['client_name']+'-'+result['client_id']);
//              $("#client_search").val(result['client_id']);
//              $("#client_attachments").html(result['attachments']);
//                    CKEDITOR.instances['editor_1'].setData(result['content']);
//                 }
//             });
//           }
//         }
//   });
// });
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
fileList = new Array();

Dropzone.options.imageUpload = {
    maxFiles: 2000,
    acceptedFiles: null,
    maxFilesize:500000,
    timeout: 10000000,
    dataType: "HTML",
    parallelUploads: 1,
    maxfilesexceeded: function(file) {
        this.removeAllFiles();
        this.addFile(file);
    },
    init: function() {
        this.on('sending', function(file) {
            $("body").addClass("loading");
            $(".accepted_files_main").hide();
            $(".accepted_files").html(0);
        });
        this.on("drop", function(event) {
            $("body").addClass("loading");        
        });
        this.on("success", function(file, response) {
            var obj = jQuery.parseJSON(response);
            file.serverId = obj.id; // Getting the new upload ID from the server via a JSON response
            $("#add_attachments_purchase_div").append("<p>"+obj.filename+" </p>");
            $(".img_div").hide();
        });
        this.on("complete", function (file) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            var acceptedcount= this.getAcceptedFiles().length;
            var rejectedcount= this.getRejectedFiles().length;
            $(".accepted_files_main").show();

            $(".accepted_files").html(acceptedcount);
            var totalcount = acceptedcount + rejectedcount;
            $("#total_count_files").val(totalcount);
            $("body").removeClass("loading");
          }
         });
        this.on("error", function (file) {
            $("body").removeClass("loading");
      });
        this.on("canceled", function (file) {
            $("body").removeClass("loading");
      });
        this.on("removedfile", function(file) {
            if (!file.serverId) { return; }
            $.get("<?php echo URL::to('user/remove_property_images'); ?>"+"/"+file.serverId);
        });
    },
};

Dropzone.options.imageUpload1 = {

    maxFiles: 2000,

    acceptedFiles: null,

    maxFilesize:500000,

    timeout: 10000000,

    dataType: "HTML",
    
    parallelUploads: 1,

    maxfilesexceeded: function(file) {
        this.removeAllFiles();
        this.addFile(file);
    },

    init: function() {

        this.on('sending', function(file) {

            $("body").addClass("loading");
            $(".accepted_files_main").hide();

            $(".accepted_files").html(0);

        });

        this.on("drop", function(event) {

            $("body").addClass("loading");        

        });

        this.on("success", function(file, response) {

            var obj = jQuery.parseJSON(response);

            file.serverId = obj.id; // Getting the new upload ID from the server via a JSON response

            $("#add_attachments_sales_div").append("<p>"+obj.filename+" </p>");
            $(".img_div").hide();



        });

        this.on("complete", function (file) {

          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            var acceptedcount= this.getAcceptedFiles().length;
            var rejectedcount= this.getRejectedFiles().length;
            $(".accepted_files_main").show();

            $(".accepted_files").html(acceptedcount);
            var totalcount = acceptedcount + rejectedcount;
            $("#total_count_files").val(totalcount);
            $("body").removeClass("loading");
          }

        

         });

        this.on("error", function (file) {

            $("body").removeClass("loading");

      });

        this.on("canceled", function (file) {

            $("body").removeClass("loading");

      });

        

        this.on("removedfile", function(file) {

            if (!file.serverId) { return; }

            $.get("<?php echo URL::to('user/remove_property_images'); ?>"+"/"+file.serverId);

        });

    },

};

Dropzone.options.imageUpload2 = {

    maxFiles: 2000,

    acceptedFiles: null,

    maxFilesize:500000,

    timeout: 10000000,

    dataType: "HTML",
    
    parallelUploads: 1,

    maxfilesexceeded: function(file) {
        this.removeAllFiles();
        this.addFile(file);
    },

    init: function() {

        this.on('sending', function(file) {

            $("body").addClass("loading");
            $(".accepted_files_main").hide();

            $(".accepted_files").html(0);

        });

        this.on("drop", function(event) {

            $("body").addClass("loading");        

        });

        this.on("success", function(file, response) {

            var obj = jQuery.parseJSON(response);

            file.serverId = obj.id; // Getting the new upload ID from the server via a JSON response

            $("#add_attachments_cheque_div").append("<p>"+obj.filename+" </p>");
            $(".img_div").hide();



        });

        this.on("complete", function (file) {

          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            var acceptedcount= this.getAcceptedFiles().length;
            var rejectedcount= this.getRejectedFiles().length;
            $(".accepted_files_main").show();

            $(".accepted_files").html(acceptedcount);
            var totalcount = acceptedcount + rejectedcount;
            $("#total_count_files").val(totalcount);
            $("body").removeClass("loading");
          }

        

         });

        this.on("error", function (file) {

            $("body").removeClass("loading");

      });

        this.on("canceled", function (file) {

            $("body").removeClass("loading");

      });

        

        this.on("removedfile", function(file) {

            if (!file.serverId) { return; }

            $.get("<?php echo URL::to('user/remove_property_images'); ?>"+"/"+file.serverId);

        });

    },

};
$(".image_file_purchase").change(function(){
  var lengthval = $(this.files).length;
  var htmlcontent = '';
  var attachments = $('#add_attachments_purchase_div').html();
  for(var i=0; i<= lengthval - 1; i++)
  {
    var sno = i + 1;
    if(htmlcontent == "")
    {
      htmlcontent = '<p class="attachment_p">'+this.files[i].name+'</p>';
    }
    else{
      htmlcontent = htmlcontent+'<p class="attachment_p">'+this.files[i].name+'</p>';
    }
  }
  $('#add_attachments_purchase_div').html(attachments+' '+htmlcontent);
  $("#attachments_text").show();
  $(".img_div").hide();
});
$("#to_user").change(function() {
  if (CKEDITOR.instances.editor) CKEDITOR.instances.editor.destroy();
  var requestid = $(".request_id").val();
  var to_user = $(this).val();
  $.ajax({
      url:'<?php echo URL::to('user/send_request_for_approval_edit'); ?>',
      type:'post',
      data:{requestid:requestid,to_user:to_user},
      dataType:"json",
      success: function(result){
        CKEDITOR.replace('editor',
         {
          height: '150px',
         }); 
         $(".sent_for_approval").modal('show');  
         $(".subject_approval").val(result['subject']);
         $("#from_user").val(result['user_id']);
         $("#approval_attachments").html(result['attachments']);
         CKEDITOR.instances['editor'].setData(result['content']);
      }
  }) 
});
$(window).click(function(e) {
if($(e.target).hasClass('delete_request_item'))
{
  var url = $(e.target).attr("data-element");
  $("#hidden_delete_req_item_url").val(url);
  $(".delete_modal").modal("show");
}
if($(e.target).hasClass('yes_delete'))
{
  var url = $("#hidden_delete_req_item_url").val();
  window.location.replace(url);
}
if($(e.target).hasClass('no_delete'))
{
  $(".delete_modal").modal("hide");
  $("#hidden_delete_req_item_url").val("");
}
if($(e.target).hasClass('client_purchase_button'))
{
  e.preventDefault();
  var specific_invoice = $(".req_purchase").val();
  var attachments = $("#add_attachments_purchase_div").find("p").length;
  if(attachments > 0 || specific_invoice != "")
  {
    $("#purchase_invoice_form").submit();
  }
  else{
    $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:red>Please Enter the Specific Purchase Invoice or choose Attachments to Proceed.</p>", fixed:true});
  }
}
if($(e.target).hasClass('client_sales_button'))
{
  e.preventDefault();
  var specific_invoice = $(".req_sales").val();
  var attachments = $("#add_attachments_sales_div").find("p").length;
  var sales_specific_invoice = $(".req_specific_sales").val();
  if(attachments > 0 || specific_invoice != "" || sales_specific_invoice != "")
  {
    $("#sales_invoice_form").submit();
  }
  else{
    $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:red>Please Enter the Specific Sales Invoice or choose Attachments or Enter Sales Invoices to Specific Customer to Proceed.</p>", fixed:true});
  }
}
if($(e.target).hasClass('client_cheque_button'))
{
  e.preventDefault();
  var bank_account = $("#cheque_bank_id").val();
  var specific_invoice = $(".req_cheque").val();
  var attachments = $("#add_attachments_cheque_div").find("p").length;
  if(bank_account == "")
  {
    $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:red>Please select the Bank Account to Proceed.</p>", fixed:true});
  }
  else if(attachments == 0 && specific_invoice == "")
  {
    $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:red>Please Enter the Specific Cheque Numbers or choose Attachments to Proceed.</p>", fixed:true});
  }
  else{
    $("#add_cheque_form").submit();
  }
}
if($(e.target).hasClass('send_request_for_approval'))
{
  var employee_select = $(".user_class").val();
  var year_class = $(".year_class").val();
  var category_class = $(".accounts_class").val();

  if(employee_select == "")
  {
    $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:red>Please select the Employee from the dropdown. </p>", fixed:true});
  }
  else if(year_class == "")
  {
    $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:red>Please select the Year from the dropdown. </p>", fixed:true});
  }
  else if(category_class == "")
  {
    $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:red>Please select the Category from the dropdown. </p>", fixed:true});
  }
  else{
    var email_sent_type = $("#email_sent_type").val();
    if(email_sent_type == 0)
    {
      $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>Nothing on the Request to send. Please Create a New Request item.</p>", fixed:true});
    }
    else{
      if (CKEDITOR.instances.editor) CKEDITOR.instances.editor.destroy();
        var requestid = $(".request_id").val();
        var to_user = '';
        $.ajax({
            url:'<?php echo URL::to('user/send_request_for_approval_edit'); ?>',
            type:'post',
            data:{requestid:requestid,to_user:to_user},
            dataType:"json",
            success: function(result){
              CKEDITOR.replace('editor',
               {
                height: '150px',
               }); 
               $(".sent_for_approval").modal('show');  
               $(".subject_approval").val(result['subject']);
               $("#from_user").val(result['user_id']);
               $("#approval_attachments").html(result['attachments']);
               CKEDITOR.instances['editor'].setData(result['content']);
            }
        })
    } 
  }
}
if($(e.target).hasClass('send_request_to_client'))
{
  var employee_select = $(".user_class").val();
  var year_class = $(".year_class").val();
  var category_class = $(".accounts_class").val();

  if(employee_select == "")
  {
    $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:red>Please select the Employee from the dropdown. </p>", fixed:true});
  }
  else if(year_class == "")
  {
    $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:red>Please select the Year from the dropdown. </p>", fixed:true});
  }
  else if(category_class == "")
  {
    $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:red>Please select the Category from the dropdown. </p>", fixed:true});
  }
  else
  {
    var email_sent_type = $("#email_sent_type").val();
    var last_sent_date = $("#last_sent_date").val();
    if(email_sent_type == 0)
    {
      $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>Nothing on the Request to send. Please Create a New Request item.</p>", fixed:true});
    }
    else if(email_sent_type == 1)
    {
      $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>This request was already sent to the client on “"+last_sent_date+"” and all items have been marked as received.</p>", fixed:true});
    }
    else if(email_sent_type == 2)
    {
      if (CKEDITOR.instances.editor_1) CKEDITOR.instances.editor_1.destroy();
      var requestid = $(".request_id").val();
      var to_user = '';
      $.ajax({
          url:'<?php echo URL::to('user/send_request_to_client_edit_none_received'); ?>',
          type:'post',
          data:{requestid:requestid,to_user:to_user},
          dataType:"json",
          success: function(result){
            CKEDITOR.replace('editor_1',
             {
              height: '300px',
             }); 
             $(".sent_to_client").modal('show');  
             $(".subject_to_client").val(result['subject']);
             $("#from_user_to_client").val(result['user_id']);
             //$(".client_search_class").val(result['client_name']+'-'+result['client_id']);
             $("#client_search").val(result['client_id']);
             $("#client_attachments").html(result['attachments']);
             CKEDITOR.instances['editor_1'].setData(result['content']);
          }
      });
    }
    else if(email_sent_type == 5)
    {
      if (CKEDITOR.instances.editor_1) CKEDITOR.instances.editor_1.destroy();
      var requestid = $(".request_id").val();
      var to_user = '';
      $.ajax({
          url:'<?php echo URL::to('user/send_request_to_client_edit'); ?>',
          type:'post',
          data:{requestid:requestid,to_user:to_user},
          dataType:"json",
          success: function(result){
            CKEDITOR.replace('editor_1',
             {
              height: '300px',
             }); 
             $(".sent_to_client").modal('show');  
             $(".subject_to_client").val(result['subject']);
             $("#from_user_to_client").val(result['user_id']);
             //$(".client_search_class").val(result['client_name']+'-'+result['client_id']);
             $("#client_search").val(result['client_id']);
             $("#client_attachments").html(result['attachments']);
             CKEDITOR.instances['editor_1'].setData(result['content']);
          }
      });
    }
    else{
      if (CKEDITOR.instances.editor_1) CKEDITOR.instances.editor_1.destroy();
      var requestid = $(".request_id").val();
      var to_user = '';
      $.ajax({
          url:'<?php echo URL::to('user/send_request_to_client_some_not_edit'); ?>',
          type:'post',
          data:{requestid:requestid,to_user:to_user},
          dataType:"json",
          success: function(result){
            CKEDITOR.replace('editor_1',
             {
              height: '300px',
             }); 
             $(".sent_to_client").modal('show');  
             $(".subject_to_client").val(result['subject']);
             $("#from_user_to_client").val(result['user_id']);
             //$(".client_search_class").val(result['client_name']+'-'+result['client_id']);
             $("#client_search").val(result['client_id']);
             $("#client_attachments").html(result['attachments']);
             CKEDITOR.instances['editor_1'].setData(result['content']);
          }
      });
    }
  }
}
if($(e.target).hasClass('bank_statements_button'))
{
  e.preventDefault();
  var bank = $("#statement_bank_id").val();
  var numbers = $(".statement_number").val();
  var from = $(".from_date").val();
  var to = $(".to_date").val();

  if(bank == "")
  {
    $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:red>Please select the Bank Account to Proceed.</p>", fixed:true});
  }
  else if(numbers == "" && from == "" && to == "")
  {
    $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:red>You should enter Statement Numbers or From and To Date to Proceed with Bank Statement Request.</p>", fixed:true});
  }
  else if(from != "" && to == ""){
    $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:red>You should enter the To Date to Proceed with Bank Statement Request.</p>", fixed:true});
  }
  else if(from == "" && to != ""){
    $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:red>You should enter the From Date to Proceed with Bank Statement Request.</p>", fixed:true});
  }
  else{
    $("#bank_form").submit();
  }
}
if($(e.target).hasClass('image_submit_purchase'))
{
  var files = $(e.target).parent().find('.image_file_purchase').val();
  if(files == '' || typeof files === 'undefines')
  {
    $(e.target).parent().find(".error_files").text("Please Choose the files to proceed");
    return false;
  }
  else{
    $(e.target).parents('.modal-body').find('.img_div').toggle();
  }
}
else{
  $(".img_div").each(function() {
    $(this).hide();
  });
}
if($(e.target).hasClass('image_submit_sales'))
{
  var files = $(e.target).parent().find('.image_file_sales').val();
  if(files == '' || typeof files === 'undefines')
  {
    $(e.target).parent().find(".error_files").text("Please Choose the files to proceed");
    return false;
  }
  else{
    $(e.target).parents('.modal-body').find('.img_div').toggle();
  }
}
else{
  $(".img_div").each(function() {
    $(this).hide();
  });
}
if($(e.target).hasClass('image_submit_cheque'))
{
  var files = $(e.target).parent().find('.image_file_cheque').val();
  if(files == '' || typeof files === 'undefines')
  {
    $(e.target).parent().find(".error_files").text("Please Choose the files to proceed");
    return false;
  }
  else{
    $(e.target).parents('.modal-body').find('.img_div').toggle();
  }
}
else{
  $(".img_div").each(function() {
    $(this).hide();
  });
}
if($(e.target).hasClass('image_file_purchase'))
{
  $(e.target).parents('.modal-body').find('.img_div').toggle();
}
if($(e.target).hasClass('image_file_sales'))
{
  $(e.target).parents('.modal-body').find('.img_div').toggle();
}
if($(e.target).hasClass('image_file_cheque'))
{
  $(e.target).parents('.modal-body').find('.img_div').toggle();
}
if($(e.target).hasClass('trash_image_purchase'))
{
  $("body").addClass("loading");
  $.ajax({
    url:"<?php echo URL::to('user/clear_session_attachments_purchase'); ?>",
    type:"post",
    success: function(result)
    {
      $("#add_attachments_purchase_div").html('');
      $("body").removeClass("loading");
    }
  })
}
if($(e.target).hasClass('trash_image_sales'))
{
  $("body").addClass("loading");
  $.ajax({
    url:"<?php echo URL::to('user/clear_session_attachments_sales'); ?>",
    type:"post",
    success: function(result)
    {
      $("#add_attachments_sales_div").html('');
      $("body").removeClass("loading");
    }
  })
}
if($(e.target).hasClass('trash_image_cheque'))
{
  $("body").addClass("loading");
  $.ajax({
    url:"<?php echo URL::to('user/clear_session_attachments_cheque'); ?>",
    type:"post",
    success: function(result)
    {
      $("#add_attachments_cheque_div").html('');
      $("body").removeClass("loading");
    }
  })
}
if($(e.target).hasClass("dropzone"))
{
  $(e.target).parents('td').find('.img_div').show();    
  $(e.target).parents('.modal-body').find('.img_div').show();    
}
if($(e.target).hasClass("remove_dropzone_attach"))
{
  $(e.target).parents('td').find('.img_div').show();   
  $(e.target).parents('.modal-body').find('.img_div').show(); 
}
if($(e.target).parent().hasClass("dz-message"))
{
  $(e.target).parents('td').find('.img_div').show();
  $(e.target).parents('.modal-body').find('.img_div').show();    
}
if($(e.target).hasClass('fa-plus-purchase'))
{
  var show_html = $('.img_div_purchase').html();
  var windowwidth = $(window).width();
  var windowheigth = $(window).height();
  var windowoffset = $(".footer_row").offset();
  
  var leftpos = e.pageX;
  var toppos = e.pageY;
  var windowtop = $(e.target).offset();
  
  var wwidth = parseInt(windowwidth) - parseInt(400);
  var hheight = parseInt(windowheigth) - parseInt(220);
  if (leftpos > wwidth || hheight > windowtop.top) {
    if (leftpos > wwidth) {
      leftpos = wwidth;
      toppos = hheight;
    }
    else{
      leftpos = e.pageX;
      toppos = windowtop.top;
    }
  }
  else{
    leftpos = e.pageX;
    toppos = hheight;
  }
  $(".img_div_purchase").css({"top":toppos,"left":leftpos,});
  $('.img_div_purchase').toggle();
  Dropzone.forElement("#imageUpload").removeAllFiles(true);
  $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");    
}
if($(e.target).hasClass('fa-plus-sales'))
{
  var show_html = $('.img_div_sales').html();
  var windowwidth = $(window).width();
  var windowheigth = $(window).height();
  var windowoffset = $(".footer_row").offset();
  
  var leftpos = e.pageX;
  var toppos = e.pageY;
  var windowtop = $(e.target).offset();
  
  var wwidth = parseInt(windowwidth) - parseInt(400);
  var hheight = parseInt(windowheigth) - parseInt(220);
  if (leftpos > wwidth || hheight > windowtop.top) {
    if (leftpos > wwidth) {
      leftpos = wwidth;
      toppos = hheight;
    }
    else{
      leftpos = e.pageX;
      toppos = windowtop.top;
    }
  }
  else{
    leftpos = e.pageX;
    toppos = hheight;
  }
  $(".img_div_sales").css({"top":toppos,"left":leftpos,});
  $('.img_div_sales').toggle();
  Dropzone.forElement("#imageUpload1").removeAllFiles(true);
  $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");    
}
if($(e.target).hasClass('fa-plus-cheque'))
{
  var show_html = $('.img_div_cheque').html();
  var windowwidth = $(window).width();
  var windowheigth = $(window).height();
  var windowoffset = $(".footer_row").offset();
  
  var leftpos = e.pageX;
  var toppos = e.pageY;
  var windowtop = $(e.target).offset();
  
  var wwidth = parseInt(windowwidth) - parseInt(400);
  var hheight = parseInt(windowheigth) - parseInt(220);
  if (leftpos > wwidth || hheight > windowtop.top) {
    if (leftpos > wwidth) {
      leftpos = wwidth;
      toppos = hheight;
    }
    else{
      leftpos = e.pageX;
      toppos = windowtop.top;
    }
  }
  else{
    leftpos = e.pageX;
    toppos = hheight;
  }
  $(".img_div_cheque").css({"top":toppos,"left":leftpos,});
  $('.img_div_cheque').toggle();

  $(e.target).parent().find('.img_div_cheque').toggle();
  Dropzone.forElement("#imageUpload2").removeAllFiles(true);
  $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");    
}

if($(e.target).hasClass('item_class')){
  if (CKEDITOR.instances.editor_2) CKEDITOR.instances.editor_2.destroy();
  var id = $(e.target).attr("data-element");
  var clientid = $(".client_id").val();
  var requestid = $(".request_id").val();
  $.ajax({
      url:'<?php echo URL::to('user/client_request_modal'); ?>',
      type:'post',
      data:{id:id, clientid:clientid, requestid:requestid},
      dataType:"json",
      success: function(result){
        $(".modal_result").html(result['modal']);
        if(id == "NQ==")
        {
          CKEDITOR.replace('editor_2',
         {
          height: '150px',
         }); 
        }
        
        $(".item_model").modal('show'); 
        //CKEDITOR.instances['editor_2'].setData(result['content']); 

        //var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});

        $(".from_date").datetimepicker({
           //defaultDate: fullDate,
           format: 'L',
           format: 'DD-MMM-YYYY',
        });
        $(".to_date").datetimepicker({
           //defaultDate: fullDate,
           format: 'L',
           format: 'DD-MMM-YYYY',
        });

        $(".from_date").on("dp.hide", function (e) {

          if( e.date ){
            e.date.add(1, 'day');
          }
          $('.to_date').data("DateTimePicker").minDate(e.date);
          $('.to_date').val("");
        });
      }
    }) 
}

/*
if($(e.target).hasClass('year_class')){  
  var value = $(e.target).val();
  var requestid = $(".request_id").val();
  $.ajax({
      url:'<?php echo URL::to('user/client_request_year_category_user'); ?>',
      type:'post',
      data:{value:value, requestid:requestid, type:1},
      dataType:"json",
      success: function(result){
        $(".information_class").html(result['content']);        
      }
    }) 
}

if($(e.target).hasClass('accounts_class')){  
  var value = $(e.target).val();
  var requestid = $(".request_id").val();
  $.ajax({
      url:'<?php echo URL::to('user/client_request_year_category_user'); ?>',
      type:'post',
      data:{value:value, requestid:requestid, type:2},
      dataType:"json",
      success: function(result){
        $(".information_class").html(result['content']);  
        $(".request_item_class").html(result['outputitem']);
      }
    }) 
}

if($(e.target).hasClass('user_class')){  
  var value = $(e.target).val();
  var requestid = $(".request_id").val();
  $.ajax({
      url:'<?php echo URL::to('user/client_request_year_category_user'); ?>',
      type:'post',
      data:{value:value, requestid:requestid, type:3},
      dataType:"json",
      success: function(result){
        
      }
    }) 
}
*/

if($(e.target).hasClass('view_class')){    
  var requestid = $(".request_id").val();
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
  var requestid = $(".request_id").val();
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
<script>
$(".year_class").change(function(){
  var value = $(this).val();
  var requestid = $(".request_id").val();
  $.ajax({
      url:'<?php echo URL::to('user/client_request_year_category_user'); ?>',
      type:'post',
      data:{value:value, requestid:requestid, type:1},
      dataType:"json",
      success: function(result){
        $(".information_class").html(result['content']);        
      }
    }) 
});
$(".accounts_class").change(function(){
  var value = $(this).val();
  var requestid = $(".request_id").val();
  $.ajax({
      url:'<?php echo URL::to('user/client_request_year_category_user'); ?>',
      type:'post',
      data:{value:value, requestid:requestid, type:2},
      dataType:"json",
      success: function(result){
        $(".information_class").html(result['content']);  
        $(".request_item_class").html(result['outputitem']);
      }
    }) 
});
$(".user_class").change(function(){
  var value = $(this).val();
  var requestid = $(".request_id").val();
  $.ajax({
      url:'<?php echo URL::to('user/client_request_year_category_user'); ?>',
      type:'post',
      data:{value:value, requestid:requestid, type:3},
      dataType:"json",
      success: function(result){
        
      }
    }) 
});
</script>
@stop
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
.dropzone {
min-height: 400px !important;
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
.modal_load_rebuild {
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

      margin-top: 160px;

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

.dropzone.dz-clickable{margin-bottom: 30px !important;}

.report_model_selectall{padding:10px 15px; background-image:linear-gradient(to bottom,#f5f5f5 0,#e8e8e8 100%); background: #f5f5f5; border:1px solid #ddd; margin-top: 20px; border-radius: 3px;  }

.form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control{background: #e4e4e4 !important}
body.loading {
    overflow: hidden;   
}
body.loading .modal_load {
    display: block;
}

body.loading_rebuild {
    overflow: hidden;   
}
body.loading_rebuild .modal_load_rebuild {
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
.error{color: #f00; font-size: 12px;}
a:hover{text-decoration: underline;}

</style>

<img id="coupon" />

<div class="modal fade emailunsent" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="margin-top: 5%;">
  <div class="modal-dialog" role="document">
    <form action="<?php echo URL::to('user/rct_send_bulk_email'); ?>" method="post" id="emailunsent_form">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">RCT SUBMISSION EMAIL</h4>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-md-3">
              <label>From</label>
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
                            <option value="<?php echo trim($user->email); ?>"><?php echo $user->lastname.' '.$user->firstname; ?></option>
                          <?php
                      }
                    }
                  ?>
              </select>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-3">
              <label>To</label>
            </div>
            <div class="col-md-9">
              <?php
              if($client_details->email2 == "")
              {
                ?>
                <input type="text" name="to_user" id="to_user" class="form-control input-sm" value="<?php echo $client_details->email; ?>" required>
                <?php
              }
              else{
                ?>
                <input type="text" name="to_user" id="to_user" class="form-control input-sm" value="<?php echo $client_details->email.','.$client_details->email2; ?>" required>
                <?php
              }
              ?>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-3">
              <label>CC</label>
            </div>
            <div class="col-md-9">
              <?php 
                $admin_details = Db::table('admin')->first();
                $admin_cc = $admin_details->p30_cc_email;
              ?>
              <input type="text" name="cc_unsent" class="form-control input-sm" id="cc_unsent" value="<?php echo $admin_cc; ?>" readonly>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-3">
              <label>Subject</label>
            </div>
            <div class="col-md-9">
              <input type="text" name="subject_unsent" class="form-control input-sm subject_unsent" value="RCT LIABILITY ASSESSMENT" required>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-12">
              <label>Message</label>
            </div>
            <div class="col-md-12">
              <textarea name="message_editor" id="editor_1"></textarea>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-12">
              <label>Attachment</label>
            </div>
            <div class="col-md-12">
              <h5><img src="<?php echo URL::to('assets/images/pdf.jpg'); ?>" style="width:100px;"> RCT_Notification.pdf</h5>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="hidden_keys" id="hidden_keys" value="">
        <input type="hidden" name="hidden_client_id" id="hidden_client_id" value="<?php echo $client_details->client_id; ?>">
        <input type="submit" class="btn btn-primary common_black_button email_unsent_button" value="Send Email">
      </div>
    </div>
    </form>
  </div>
</div>
<div class="modal fade upload_html_modal" id="upload_html_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title">Upload File</h4>
          </div>
          <div class="modal-body">
            <div class="image_div_attachments">
               <form action="<?php echo URL::to('user/upload_rct_html_form'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                   <input name="_token" type="hidden" value="">
                   <input type="hidden" name="hidden_upload_client_id" id="hidden_upload_client_id" value="<?php echo $client_details->client_id; ?>">
               </form>
             </div>
             <p id="attachments_text" style="display:none; font-weight: bold;margin-top:30px">"Files Uploaded and Processed:</p>
            <div id="add_attachments_div">
            </div>          
          </div>
          <div class="modal-footer">
              <input type="hidden" value="<?php echo $client_details->client_id?>" name="hidden_client_id" id="hidden_client_id">
              <a href="<?php echo URL::to('user/rct_client_manager/'.$client_details->client_id.'?active_month='.$_GET['active_month']); ?>" class="common_black_button">Close</a>
          </div>
      </div>
  </div>
</div>
<div class="modal fade tax_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <form method="post" action="<?php echo URL::to('user/rct_add_tax')?>" id="add_tax_form">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">New Tax</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>Enter Tax Name</label>
              <input type="text" class="form-control tax_name_class" required placeholder="Enter Tax Name" name="tax_name">
            </div>
            <div class="form-group">
              <label>Enter Tax Number</label>
              <input type="text" class="form-control tax_number_class" required placeholder="Enter Tax Number" name="tax_number">
            </div>            
          </div>
          <div class="modal-footer">
              <input type="hidden" value="<?php echo $client_details->client_id?>" name="client_id">
              <input type="submit" class="common_black_button print_pdf" value="Add New">
          </div>
        </form>
      </div>
  </div>
</div>

<div class="modal fade submissions_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <form method="post" action="<?php echo URL::to('user/rct_add_submission')?>" id="add_submission">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Make RCT Submissions</h4>
          </div>
          <div class="modal-body">
            <div class="row">
            <div class="col-lg-12">
              <div class="col-lg-6" style="padding:0px">
              <div class="form-group">
                <label>Select Type</label>
                <select class="form-control type_class" name="submission_type" required>
                  <option value="">Select Type</option>
                  <option value="1">Contract</option>
                  <option value="2">Payment</option>
                </select>
                </div>
              </div>
            </div>
          <div class="col-lg-6">
              <div class="form-group principal_group" style="display: none;">
                <label>Principal Name</label>
                <select class="form-control principal_class" required name="principal_name">
                  <option value="">Select Principal Name</option>
                  <?php
                  if(count($taxlist)){
                    $tax_name = explode(',', $taxlist->tax_name);
                    $tax_number = explode(',', $taxlist->tax_number);
                    $outputtax='';
                    if(count($tax_name)){
                      foreach ($tax_name as $key => $name) {  
                        if($name != "" && $tax_number[$key] != "")
                        {
                          $outputtax.='<option value="'.$key.'">'.$name.' - '.$tax_number[$key].'</option>';
                        }   
                      }
                    }
                  }
                  else{
                    $outputtax='
                          <option value="">Empty</option>';
                  }
                  echo $outputtax;
                  ?>
                </select>
                
              </div>
              <div class="form-group start_date_group" style="display: none;">
                <label>Start/Pay Date</label>

                <label class="input-group datepicker-only-init">
                  <input type="text" class="form-control start_date_class" placeholder="Enter Start/Pay Date" name="start_date" required />
                  <span class="input-group-addon">
                      <i class="glyphicon glyphicon-calendar"></i>
                  </span>
              </label>
                
              </div>
              
              
              <div class="form-group sub_contractor_group" style="display: none;">
                <label>Sub Contractor Name</label>
                <input type="text" class="form-control sub_contractor_class" required placeholder="Enter Sub Contractor Name" name="sub_contractor_name">
              </div>
              <div class="form-group sub_contractor_id_group" style="display: none;">
                <label>Sub Contractor ID</label>
                <input type="text" class="form-control sub_contractor_id_class" required placeholder="Enter Sub Contractor ID" name="sub_contractor_id">
              </div>
              <div class="form-group site_group" style="display: none;">
                <label>Site</label>
                <input type="text" class="form-control site_class" required placeholder="Enter Site" name="site">
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group rct_id_group" style="display: none;">
                <label>RCT ID</label>
                <input type="text" class="form-control rct_class" required placeholder="Enter RCT ID" name="rct_id">
              </div>
              <div class="form-group finish_date_group" style="display: none;">
                <label>Finish Date</label>
                <label class="input-group datepicker-only-init">
                  <input type="text" class="form-control finish_date_class" placeholder="Enter Finish Date" name="finish_date" required />
                  <span class="input-group-addon">
                      <i class="glyphicon glyphicon-calendar"></i>
                  </span>
              </label>                
              </div>
              <div class="form-group gross_group" style="display: none;">
                <label>Value Gross</label>
                <input type="number" class="form-control value_gross_class" required placeholder="Enter Value Gross" name="value_gross">
              </div>
              <div class="form-group value_group" style="display: none;">
                <label>Value Net</label>
                <input type="number" class="form-control value_net_class" required placeholder="Enter Value Net" name="value_net">
              </div>
              <div class="form-group deduction_group" style="display: none;">
                <label>Deduction</label>
                <input type="number" class="form-control deduction_class" required placeholder="Enter Deduction" name="deduction">
              </div>
            </div>
          </div>
        </div>
          <div class="modal-footer">
              <input type="hidden" value="<?php echo $client_details->client_id?>" name="client_id">
              <input type="submit" class="common_black_button" value="Add New">
          </div>
        </form>
      </div>
  </div>
</div>


<div class="modal fade edit_submissions_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <form method="post" action="<?php echo URL::to('user/rct_edit_submission_update')?>" id="edit_submission">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Edit RCT Submissions</h4>
          </div>
          <div class="modal-body">
            <div class="row">
            <div class="col-lg-12">
              <div class="col-lg-6" style="padding:0px">
              <div class="form-group">
                <label>Select Type</label>
                <select class="form-control type_class_edit" name="" required>
                  <option value="">Select Type</option>
                  <option value="1">Contract</option>
                  <option value="2">Payment</option>
                </select>
                
              </div>
              </div>
            </div>
          <div class="col-lg-6">
              
              <div class="form-group principal_group_edit" style="display: none;">
                <label>Principal Name</label>
                <select class="form-control principal_class_edit" required name="principal_name">
                  <option value="">Select Principal Name</option>
                  <?php
                  if(count($taxlist)){
                    $tax_name = explode(',', $taxlist->tax_name);
                    $tax_number = explode(',', $taxlist->tax_number);
                    $outputtax='';
                    if(count($tax_name)){
                      foreach ($tax_name as $key => $name) {    
                        if($name != "" && $tax_number[$key] != "")
                        {  
                          $outputtax.='<option value="'.$key.'">'.$name.' - '.$tax_number[$key].'</option>';
                        }
                      }
                    }
                  }
                  else{
                    $outputtax='
                          <option value="">Empty</option>';
                  }
                  echo $outputtax;
                  ?>
                </select>
                
              </div>
              <div class="form-group start_date_group_edit" style="display: none;">
                <label>Start/Pay Date</label>

                <label class="input-group datepicker-only-init">
                  <input type="text" class="form-control start_date_class_edit" placeholder="Enter Start/Pay Date" name="start_date" required />
                  <span class="input-group-addon">
                      <i class="glyphicon glyphicon-calendar"></i>
                  </span>
              </label>
                
              </div>
              <div class="form-group sub_contractor_group_edit" style="display: none;">
                <label>Sub Contractor Name</label>
                <input type="text" class="form-control sub_contractor_class_edit" required placeholder="Enter Sub Contractor Name" name="sub_contractor_name">
              </div>
              <div class="form-group sub_contractor_id_group_edit" style="display: none;">
                <label>Sub Contractor ID</label>
                <input type="text" class="form-control sub_contractor_id_class_edit" required placeholder="Enter Sub Contractor ID" name="sub_contractor_id">
              </div>
              <div class="form-group site_group_edit" style="display: none;">
                <label>Site</label>
                <input type="text" class="form-control site_class_edit" required placeholder="Enter Site" name="site">
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group rct_id_group_edit" style="display: none;">
                <label>RCT ID</label>
                <input type="text" class="form-control rct_class_edit" required placeholder="Enter RCT ID" name="rct_id">
              </div>
              
              <div class="form-group finish_date_group_edit" style="display: none;">
                <label>Finish Date</label>
                <label class="input-group datepicker-only-init">
                  <input type="text" class="form-control finish_date_class_edit" placeholder="Enter Finish Date" name="finish_date" required />
                  <span class="input-group-addon">
                      <i class="glyphicon glyphicon-calendar"></i>
                  </span>
              </label>                
              </div>
              <div class="form-group gross_group_edit" style="display: none;">
                <label>Value Gross</label>
                <input type="number" class="form-control value_gross_class_edit" required placeholder="Enter Value Gross" name="value_gross">
              </div>
              <div class="form-group value_group_edit" style="display: none;">
                <label>Value Net</label>
                <input type="number" class="form-control value_net_class_edit" required placeholder="Enter Value Net" name="value_net">
              </div>
              <div class="form-group deduction_group_edit" style="display: none;">
                <label>Deduction</label>
                <input type="number" class="form-control deduction_class_edit" required placeholder="Enter Deduction" name="deduction">
              </div>
            </div>
          </div>
        </div>
          <div class="modal-footer">
              <input type="hidden" class="type_edit"  name="submission_type">
              <input type="hidden" class="key_edit" name="key">
              <input type="hidden" value="<?php echo $client_details->client_id?>" name="client_id">
              <a href="javascript:" class="common_black_button saveas_pdf" style="float: left;">Save as PDF</a>
              <input type="submit" class="common_black_button" value="Update">
          </div>
        </form>
      </div>
  </div>
</div>


<div class="modal fade saveas_pdf_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-body">
            <div class="row">
              <div class="col-lg-12">
            <?php
            $letter_pad = DB::table('letterpad')->first();
            ?>             
              <div id="pdf_content" style="width: 100%; height: 1235px; float: left; position: absolute; top: 0px; z-index: 999; background: url('<?php echo URL::to('uploads/letterpad/'.$letter_pad->image)?>') no-repeat; -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;">                
              </div>            
            </div>
          </div>
        </div>
      </div>
  </div>
</div>



<div class="modal fade view_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document" style="width: 90%;">
      <div class="modal-content">
        <form method="post" action="<?php echo URL::to('user/rct_add_tax')?>" id="add_tax_form">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">View Submission - <span class="month_year"></span></h4>
          </div>
          <div class="modal-body">
            <table class="own_table" width="100%" style="max-width: 100%;">
              <thead>
                <tr style="background: #fff;">
                  <th>Type</th>
                  <th>RCT ID</th>
                  <th>Principal Name</th>
                  <th>Sub Contractor Name</th>
                  <th>Sub Contractor ID</th>
                  <th>Site</th>
                  <th>Start/Pay Date</th>
                  <th>Finish Date</th>
                  <th>Value Gross</th>
                  <th>Value Net</th>
                  <th>Deduction</th>                  
                </tr>
              </thead>
              <tbody class="view_submission">
              </tbody>
            </table>
          </div>

                     
          </div>
          <div class="modal-footer">
              
          </div>
        </form>
      </div>
  </div>
</div>



<div class="content_section" style="margin-bottom:200px">
  <div class="page_title">
        <h5 class="col-lg-3" style="padding: 0px;font-size:22px;font-weight:600">
                Client RCT Manager      
                <input type="hidden" value="<?php echo $client_details->client_id?>" id="client_id" name="client_id">
                
            </h5>
            <a href="<?php echo URL::to('user/rct_system'); ?>" class="common_black_button" style="float:right">Back to RCT Manager</a>
            <a href="<?php echo URL::to('user/rct_liability_assessment/'.$client_details->client_id.'?active_month='.$_GET['active_month']); ?>" class="common_black_button" style="float:right">Move to RCT Liability Assessment Screen</a>
            
</div>

<div class="col-lg-12 padding_00" style="font-size: 15px !important;">
  
  
  <div style="clear: both;">
   <?php
    if(Session::has('message')) { ?>
        <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
     <?php }   
    ?>
    </div> 

    <div class="col-lg-2" style="line-height: 30px; font-weight: 700">
      Client Id:<br/>
      Company:<br/>
      Name:<br/>
      Primary Email:<br/>
      Secondary Email:
    </div>
    <div class="col-lg-3" style="line-height: 30px;">
      <?php echo $client_details->client_id;?><br/>
      <?php echo $client_details->company;?><br/>
      <?php echo $client_details->firstname;?><br/>
      <?php echo $client_details->email;?><br/>
      <?php echo $client_details->email2;?>
    </div>
    <div class="col-lg-5">
      <b>Tax Number</b><a href="javascript:"><i class="fa fa-plus add_tax_number" style="margin-left: 10px;"></i></a><br/>
      <div style="width:100%; height: auto; float: left; border: 1px solid #000; background: #fff; padding: 5px 10px; margin-top: 10px; height: 100px; overflow: scroll; overflow-x:hidden;">

        <?php
        if(count($taxlist)){
          $tax_name = explode(',', $taxlist->tax_name);
          $tax_number = explode(',', $taxlist->tax_number);
          $outputtax='';
          if(count($tax_name)){
            foreach ($tax_name as $key => $name) {  
              if($name=="" && $tax_number[$key] == "")
              {

              }  
              else{
                $outputtax.='
                <div class="row" style="line-height: 25px;">
                  <div class="col-lg-6">
                    '.$name.'
                  </div>
                  <div class="col-lg-5">
                    '.$tax_number[$key].'
                  </div>
                  <div class="col-lg-1">
                    <a href="'.URL::to('user/delete_tax_number?client_id='.$client_details->client_id.'&key='.$key.'').'" data-element="'.$key.'" class="fa fa-trash"></a>
                  </div>
                </div>';
              }   
            }
          }
        }
        else{
          $outputtax='
                <div class="row" style="line-height: 25px;">
                <div class="col-lg-6">
                  Empty
                </div>
                </div>';
        }
        echo $outputtax;
        ?>       
      </div>

    </div>

</div>

<div class="row">
  <div class="col-lg-12" style="margin: 20px 0px">
    <div class="col-lg-6" style="font-size: 15px; font-weight: bold;">RCT Submissions</div>
    <div class="col-lg-6">
      <a href="javascript:" class="common_black_button add_new_submissions" style="width: auto; float: right;">Add New Submissions</a>
      <a href="javascript:" class="common_black_button upload_html" style="width: auto; float: right;">Upload</a>
      <a href="javascript:" class="common_black_button extract_pdf_class" style="width: auto; float: right;">Extract to PDF</a>
      <a href="javascript:" class="common_black_button extract_csv_class" style="width: auto; float: right;">Extract to CSV</a>
    </div>    
  </div>
  <div class="clearfix"></div>
  <div class="col-lg-12">
    <div class="table-responsive">
      <table class="own_table" id="rct_submission_table" width="100%" style="max-width: 100%;">
        <thead>
          <tr style="background: #fff;">
            <th style="text-align: left">Type</th>
            <th style="text-align: left">RCT ID</th>
            <th style="text-align: left">Principal Name</th>
            <th style="text-align: left">Sub Contractor Name</th>
            <th style="text-align: left">Sub Contractor ID</th>
            <th style="text-align: left">Site</th>
            <th style="text-align: left">Start/Pay Date</th>
            <th style="text-align: left">Finish Date</th>
            <th style="text-align: left">Value Gross</th>
            <th style="text-align: left">Value Net</th>
            <th style="text-align: left">Deduction</th>
            <th style="text-align: left">Action</th>
          </tr>
        </thead>
        <tbody class="result_submission">

          <?php
          $outputsubmission='';
          $site_view = '';
          $sub_cont_name = '';
          $sub_cont_id = '';
          $finish_date_view = '';
          $value_net_view = '';
          $deduction_view = '';
          $type_text='';
          $rct_id='';
          $key='';
          $principal_key='';
          $explode_tax_name='';
          $explode_tax_number='';
          if(count($rctsubmission)){            
            $rct_type = unserialize($rctsubmission->type);
            $rct_id = unserialize($rctsubmission->rct_id);
            $principal_name = unserialize($rctsubmission->principal_name);
            $start_date = unserialize($rctsubmission->start_date);
            $value_gross = unserialize($rctsubmission->value_gross);
            
            if(count($rct_type)){
              foreach ($rct_type as $key => $type) {
                if($type == 1){
                  $type_text = 'CONTRACT';    
                  
                  $site = unserialize($rctsubmission->site);  
                  $finish_date = unserialize($rctsubmission->finish_date); 
                            
                }
                elseif(($type == 2)){
                  $type_text = 'PAYMENT';

                  $sub_contractor = unserialize($rctsubmission->sub_contractor);
                  $sub_contractor_id = unserialize($rctsubmission->sub_contractor_id);

                  $value_net = unserialize($rctsubmission->value_net);
                  $deduction = unserialize($rctsubmission->deduction); 
                }
                else{
                  $type_text='';
                }
                $principal_key = $principal_name[$key];
                $tax_details = DB::table('rct_tax_number')->where('tax_client_id', $client_id)->first();
                if(count($tax_details))
                {
                  $explode_tax_name = explode(',', $tax_details->tax_name);
                  $explode_tax_number = explode(',', $tax_details->tax_number);

                  $tx_name = '';
                  $tx_number = '';
                  if(isset($explode_tax_name[$principal_key]))
                  {
                    $tx_name = $explode_tax_name[$principal_key];
                  }
                  if(isset($explode_tax_name[$principal_key]))
                  {
                    $tx_number = $explode_tax_number[$principal_key];
                  }

                  $prin_name = $tx_name.' - '.$tx_number;
                }
                else{
                  $explode_tax_name = array();
                  $explode_tax_name = array();

                  $prin_name = '';
                }

                

                if(isset($sub_contractor[$key])){
                  $sub_cont_name = $sub_contractor[$key];
                }
                else{
                  $sub_cont_name = '';
                }

                if(isset($sub_contractor_id[$key])){
                  $sub_cont_id = $sub_contractor_id[$key];
                }
                else{
                  $sub_cont_id = '';
                }

                if(isset($site[$key])){                  
                    $site_view = $site[$key];                  
                }
                else{
                  $site_view = '';
                }

                if(isset($finish_date[$key])){
                  $finish_date_view = date('d-M-Y', strtotime($finish_date[$key]));
                  $finish_date_spam = strtotime($finish_date[$key]);
                }
                else{
                  $finish_date_view = '';
                  $finish_date_spam = '';
                }

                if(isset($value_net[$key])){
                  $value_net_view = number_format_invoice_without_decimal($value_net[$key]);
                }
                else{
                  $value_net_view = '';
                }

                if(isset($deduction[$key])){
                  $deduction_view = number_format_invoice_without_decimal($deduction[$key]);
                }
                else{
                  $deduction_view = '';
                }



                $outputsubmission.='
                <tr>
                  <td style="text-align: left">'.$type_text.'</td>
                  <td style="text-align: left">'.$rct_id[$key].'<br/></td>

                  <td style="text-align: left">'.$prin_name.'</td>
                  <td style="text-align: left">'.$sub_cont_name.'</td>
                  <td style="text-align: left">'.$sub_cont_id.'</td>
                  <td style="text-align: left">'.$site_view.'</td>
                  <td style="text-align: left"><spam style="display:none">'.strtotime($start_date[$key]).'</spam>'.date('d-M-Y', strtotime($start_date[$key])).'</td>
                  <td style="text-align: left"><spam style="display:none">'.$finish_date_spam.'</spam>'.$finish_date_view.'</td>
                  <td style="text-align: left">'.number_format_invoice_without_decimal($value_gross[$key]).'</td>
                  <td style="text-align: left">'.$value_net_view.'</td>
                  <td style="text-align: left">'.$deduction_view.'</td>
                  <td style="text-align: left">
                    <a href="javascript:" title="Delete"><i class="fa fa-trash delete_submission" data-element="'.$type.'" id="'.$key.'"></i></a>&nbsp;&nbsp;
                    <a href="javascript:" title="View / Edit"><i class="fa fa-pencil edit_class"  data-element="'.$type.'" id="'.$key.'"></i></a>&nbsp;&nbsp;
                    <a href="javascript:" title="Email"><i class="fa fa-envelope email_class_single"  data-element="'.$type.'" id="'.$key.'"></i></a>&nbsp;&nbsp;
                    <a href="javascript:" title="Save As PDF"><i class="fa fa-download save_as_pdf"  data-element="'.$type.'" id="'.$key.'"></i></a>
                  </td>
                </tr>
                ';
              }
            }  
            else{
              $outputsubmission='
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td align="left">Empty</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              ';
            }          
          }          
          else{
            $outputsubmission='
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td align="left">Empty</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            ';
          }
          echo $outputsubmission;
          ?>





          
        </tbody>
      </table>
    </div>
  </div>
   <div class="col-lg-8" style="font-size: 15px; font-weight: bold; margin-top: 50px;">RCT Liabilities</div>
   <div class="col-lg-4">
    <div class="select_button">
      <ul style="float: right; margin-top: 50px;">                                    
        <li><a href="javascript:" class="load_all_rct" style="font-size: 13px; font-weight: 500;">Load All</a></li>
        <li><a href="javascript:" class="extract_csv_rct" style="font-size: 13px; font-weight: 500;">Extract to CSV</a></li>
        <li><a href="javascript:" class="rebuild_all_rct" style="font-size: 13px; font-weight: 500;">Rebuild ALL</a></li>
        
      </ul>
    </div>
     
   </div>
   <div class="col-lg-12">
     <table class="own_table" id="rct_liabilities_table" width="100%" style="max-width: 100%; margin-top: 15px">
        <thead>
          <tr style="background: #fff;">
            <th style="text-align: left">Month</th>
            <th style="text-align: left">Gross</th>
            <th style="text-align: left">Deduction</th>
            <th style="text-align: left">Net</th>
            <th style="text-align: left">Count</th>
            <th style="text-align: left">Action</th>
            <th style="text-align: left">Email</th>
            
          </tr>
        </thead>
        <tbody id="rct_liabilities_tbody">
          <?php
          $rct_output = '';
          if(count($rctsubmission)){
            $start_date = unserialize($rctsubmission->start_date);
            $grossval = unserialize($rctsubmission->value_gross);
            $netval = unserialize($rctsubmission->value_net);
            $deductionval = unserialize($rctsubmission->deduction);

            $data = array();

            $email_date = Db::table('rct_submission_email')->where('client_id',$client_details->client_id)->groupBy('start_date')->get();
            if(count($email_date))
            {
              foreach($email_date as $email)
              {
                $data[$email->start_date] = array();
              }
            }

            if(count($start_date))
            {
              foreach($start_date as $key => $start)
              {
                $date = substr($start,0,7);
                if(isset($data[$date]))
                {
                  if(count($data[$date]))
                  {
                    $implodeval = implode(",",$data[$date]);                  
                    $combineval = $implodeval.','.$key;
                    $data[$date] = explode(',',$combineval);
                  }
                  else{
                    $data[$date] = array($key);
                  }
                }
                else{
                  $data[$date] = array($key);
                }
              }
            }
            krsort($data);
            if(count($data))
            {
              $keyival = 1;
              foreach($data as $key_date => $dataval)
              {
                if($keyival > 24)
                {                  
                }
                else{
                  $grosssum = 0;
                  $netsum = 0;
                  $deductionsum = 0;
                  $icount = 0;
                  if(count($dataval))
                  {
                    foreach($dataval as $sumvalue)
                    {
                      if(isset($grossval[$sumvalue]))
                      {
                        $grosssum = $grosssum + $grossval[$sumvalue];
                      }
                      else{
                        $grosssum = $grosssum + 0;
                      }

                      if(isset($netval[$sumvalue]))
                      {
                        $netsum = $netsum + $netval[$sumvalue];
                      }
                      else{
                        $netsum = $netsum + 0;
                      }

                      if(isset($deductionval[$sumvalue]))
                      {
                        $deductionsum = $deductionsum + $deductionval[$sumvalue];
                      }
                      else{
                        $deductionsum = $deductionsum + 0;
                      }
                      $icount++;
                    }
                    $actions = '<a href="javascript:" title="Rebuild"><i class="fa fa-refresh rebuild_single" data-element="'.$key_date.'" aria-hidden="true"></i></a>&nbsp;&nbsp;
                      <a href="javascript:" title="View"><i class="fa fa-files-o view_class" data-element="'.$key_date.'" aria-hidden="true"></i></a>&nbsp;&nbsp;';

                    $grosssum = number_format_invoice_without_decimal($grosssum);
                    $netsum = number_format_invoice_without_decimal($netsum);
                    $deductionsum = number_format_invoice_without_decimal($deductionsum);
                    $icount = $icount;
                  }
                  else{
                    $grosssum = '-';
                    $netsum = '-';
                    $deductionsum = '-';
                    $icount = '-';
                    $actions = '';
                  }

                  $email_date = Db::table('rct_submission_email')->where('client_id',$client_details->client_id)->where('start_date',$key_date)->get();
                  $emails_sent = '';
                  if(count($email_date))
                  {
                    foreach($email_date as $email)
                    {
                      $emails_sent.='<p class="p_email_sent">'.date('F d, Y', strtotime($email->email_sent)).'</p>';
                    }
                  }

                  $rct_output.= '<tr>
                    <td style="text-align: left">'.date('M-Y', strtotime($key_date)).'</td>
                    <td style="text-align:left">'.$grosssum.'</td>
                    <td style="text-align:left">'.$deductionsum.'</td>
                    <td style="text-align:left">'.$netsum.'</td>
                    <td style="text-align:left">'.$icount.'</td>
                    <td style="text-align:left">
                      '.$actions.'
                    </td>
                    <td style="text-align:left">
                    '.$emails_sent.'
                    </td>
                  </tr>';
                  
                }
                $keyival++;
              }
            }
          }
          echo $rct_output;
          ?>
        </tbody>
      </table>
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





<div class="modal_load"></div>
<div class="modal_load_rebuild"><h5 class="rebuild_content" style="font-size: 18px;text-align: center;font-weight: 600;margin-top: 28%;">Please Wait while the system recalculate the liabilities for all the months<h5></div>
<input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
<input type="hidden" name="show_alert" id="show_alert" value="">
<input type="hidden" name="pagination" id="pagination" value="1">

<script>
fileList = new Array();

Dropzone.options.imageUpload = {

    acceptedFiles: null,

    maxFilesize:50000,

    timeout: 10000000,

    dataType: "HTML",
    
    parallelUploads: 1,

    init: function() {

        this.on('sending', function(file) {

            $("body").addClass("loading");

        });

        this.on("drop", function(event) {

            $("body").addClass("loading");        

        });

        this.on("success", function(file, response) {
            var obj = jQuery.parseJSON(response);
            file.serverId = obj.id; // Getting the new upload ID from the server via a JSON response
            
            $("#attachments_text").show();
            var countp = $("#add_attachments_div").find(".row").length;
            var pcount = countp + 1;
            if(obj.error_content == "")
            {
              $("#add_attachments_div").append("<div class='row' style='margin-top:15px'> <div class='col-md-7'> "+pcount+". "+obj.filename+"</div><div class='col-md-5'></div></div>");
            }
            else{
              $("#add_attachments_div").append("<div class='row' style='margin-top:15px'> <div class='col-md-7' style='color:#f00'> "+pcount+". "+obj.filename+"</div><div class='col-md-5' style='color:#f00'>"+obj.error_content+"</div></div>");
            }
            $(".img_div").each(function() {
              $(this).hide();
            });
        });

        this.on("complete", function (file) {

          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {

            $("body").removeClass("loading");

          }
          $(".dz-preview").detach();
            $(".dz-default").show();
        

         });

        this.on("error", function (file) {

            console.log(file);

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
$(function(){
    $('#rct_submission_table').DataTable({        
        autoWidth: true,
        scrollX: false,
        fixedColumns: false,
        searching: false,
        paging: false,
        info: false,
        ordering: true,
        aaSorting: [],
    });
    $('#rct_liabilities_table').DataTable({        
        autoWidth: true,
        scrollX: false,
        fixedColumns: false,
        searching: false,
        paging: false,
        info: false,
        ordering:true,
        aaSorting: [],
    });
    
});
$(window).click(function(e) {
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
if($(e.target).hasClass('remove_dropzone_attach'))

{

  var attachment_id = $(e.target).attr("data-element");

  var file_id = $(e.target).attr("data-task");

  $.ajax({

    url:"<?php echo URL::to('user/infile_remove_dropzone_attachment'); ?>",

    type:"post",

    data:{attachment_id:attachment_id,file_id:file_id},

    success: function(result)

    {

      var countval = $(e.target).parents(".dropzone").find(".dz-preview").length;

      if(countval == 1)

      {

        $(e.target).parents(".dropzone").removeClass("dz-started");

      }

      $(e.target).parents(".dz-preview").detach();

      

    }

  })

}
if($(e.target).hasClass('extract_pdf_class'))
{
  var length = $(".save_as_pdf").length;
  if(length > 0)
  {
    $("body").addClass("loading");
    var client_id = $("#client_id").val();
    var keys ='';
    $(".save_as_pdf").each(function() {
      if(keys == "")
      {
        keys = $(this).attr("id");
      }
      else{
        keys = keys+','+$(this).attr("id");
      }
    });
    setTimeout(function() {
      $.ajax({
          url:"<?php echo URL::to('user/rctsaveaspdf_multiple'); ?>",
          type:"post",
          data:{keys:keys,client_id:client_id},
          success: function(result){
            SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);
            $("body").removeClass("loading");
          }
      })
    },2000);
  }
  else{
    alert('There Should be atleast one RCT Submission to Extract to PDF');
  }
}
if($(e.target).hasClass('extract_csv_class'))
{
  var length = $(".save_as_pdf").length;
  if(length > 0)
  {
    $("body").addClass("loading");
    var client_id = $("#client_id").val();
    var keys ='';
    $(".save_as_pdf").each(function() {
      if(keys == "")
      {
        keys = $(this).attr("id");
      }
      else{
        keys = keys+','+$(this).attr("id");
      }
    });
    setTimeout(function() {
      $.ajax({
          url:"<?php echo URL::to('user/rctsaveascsv_multiple'); ?>",
          type:"post",
          data:{keys:keys,client_id:client_id},
          success: function(result){
            SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);
            $("body").removeClass("loading");
          }
      })
    },2000);
  }
  else{
    alert('There Should be atleast one RCT Submission to Extract to CSV');
  }
}
if($(e.target).hasClass('upload_html'))
{
  $("#upload_html_modal").modal("show");
}
if($(e.target).hasClass('email_class_single'))
{
  var client_id = $("#client_id").val();
  var type = $(e.target).attr("data-element");
  var key = $(e.target).attr("id");
  $("#hidden_keys").val(key);

  if (CKEDITOR.instances.editor_1) CKEDITOR.instances.editor_1.destroy();
  CKEDITOR.replace('editor_1',
  {
    height: '150px',
  });
  $.ajax({
    url:"<?php echo URL::to('user/get_ckeditor_content_single'); ?>",
    type:"post",
    data:{client_id:client_id,type:type,key:key},
    success:function(result)
    {
      CKEDITOR.instances['editor_1'].setData(result);
      if(type == "1")
      {
        $(".subject_unsent").val("RCT Contract");
      }
      else{
        $(".subject_unsent").val("RCT Payment");
      }
      $(".emailunsent").modal('show');
    }
  })
}
if($(e.target).hasClass('load_all_rct'))
{
  $("body").addClass("loading");
  setTimeout(function() {
    var client_id = $("#client_id").val();
    $("#rct_liabilities_table").dataTable().fnDestroy();
    $.ajax({
      url:"<?php echo URL::to('user/rct_load_all_liabilities'); ?>",
      type:"post",
      data:{client_id:client_id},
      success:function(result)
      {
        $("body").removeClass("loading");
        $("#rct_liabilities_tbody").html(result);
        $('#rct_liabilities_table').DataTable({        
            autoWidth: true,
            scrollX: false,
            fixedColumns: false,
            searching: false,
            paging: false,
            info: false,
            ordering: true,
            aaSorting: [],
        });
      }
    })
  },2000);
}
if($(e.target).hasClass('extract_csv_rct'))
{
  $("body").addClass("loading");
  setTimeout(function() {
    var client_id = $("#client_id").val();
    $.ajax({
      url:"<?php echo URL::to('user/rct_extract_csv_liabilities'); ?>",
      type:"post",
      data:{client_id:client_id},
      success:function(result)
      {
        SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);
        $("body").removeClass("loading");
      }
    })
  },2000);
}
if($(e.target).hasClass('rebuild_all_rct'))
{
  var r = confirm("Are you sure you want to rebuild all this submissions?");
  if(r)
  {
    $("body").addClass("loading_rebuild");
    $(".rebuild_content").html("Please Wait while the system recalculate the liabilities for all the months");
    setTimeout( function() {
        
        window.location.reload();
    },2000);
  }
}
if($(e.target).hasClass('rebuild_single'))
{
  var r = confirm("Are you sure you want to rebuild this submissions?");
  if(r)
  {
    var date = $(e.target).attr("data-element");
    var month = date.split("-");
    var monthval = parseInt(month[1]);
    if(monthval == 1) { var mon = 'Jan'; }
    else if(monthval == 2) { var mon = 'Feb'; }
    else if(monthval == 3) { var mon = 'Mar'; }
    else if(monthval == 4) { var mon = 'Apr'; }
    else if(monthval == 5) { var mon = 'May'; }
    else if(monthval == 6) { var mon = 'Jun'; }
    else if(monthval == 7) { var mon = 'jul'; }
    else if(monthval == 8) { var mon = 'Aug'; }
    else if(monthval == 9) { var mon = 'Sep'; }
    else if(monthval == 10) { var mon = 'Oct'; }
    else if(monthval == 11) { var mon = 'Nov'; }
    else if(monthval == 12) { var mon = 'Dec'; }

    var active_14 = mon+'-'+month[0];
    $(".rebuild_content").html("Please Wait while the system recalculate the liabilities for "+active_14);
    $("body").addClass("loading_rebuild");
      setTimeout( function() {
          window.location.reload();
      },2000);
  }
}
if($(e.target).hasClass('add_tax_number')){  
  $(".tax_name_class").val('');
  $(".tax_number_class").val('');
  $(".tax_modal").modal('show');
}

if($(e.target).hasClass('add_new_submissions')){

  //var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});
  $(".start_date_class  ").datetimepicker({
     //defaultDate: fullDate,
     format: 'L',
     format: 'DD-MMM-YYYY',
  });
  $(".finish_date_class  ").datetimepicker({
     //defaultDate: fullDate,
     format: 'L',
     format: 'DD-MMM-YYYY',
  });

  $(".start_date_class_edit  ").datetimepicker({
     //defaultDate: fullDate,
     format: 'L',
     format: 'DD-MMM-YYYY',
  });
  $(".finish_date_class_edit  ").datetimepicker({
     //defaultDate: fullDate,
     format: 'L',
     format: 'DD-MMM-YYYY',
  });

  $(".rct_class").val('');
  $(".principal_class").val('');
  $(".sub_contractor_class").val('');
  $(".sub_contractor_id_class").val('');
  $(".site_class").val('');
  $(".start_date_class").val('');
  $(".finish_date_class").val('');
  $(".value_gross_class").val('');
  $(".value_net_class").val('');
  $(".deduction_class").val('');
  $(".type_class ").val('');
  $("#rct_id-error").hide();

  $(".sub_contractor_group").hide();
  $(".sub_contractor_id_group").hide();
  $(".site_group").hide();
  $(".finish_date_group").hide();
  $(".value_group").hide();
  $(".deduction_group").hide();

  $(".rct_id_group").hide();
  $(".principal_group").hide();
  $(".start_date_group").hide();
  $(".gross_group").hide();




  $(".submissions_modal").modal('show');

}

if($(e.target).hasClass('delete_submission')){
  var r = confirm("Are You sure you want to delete this Submission?");
  if (r == true) {
    $("body").addClass("loading");
    var type = $(e.target).attr('data-element');
    var key = $(e.target).attr('id');
    var client_id = $("#client_id").val();

    $.ajax({
        url:"<?php echo URL::to('user/rct_delete_submission'); ?>",
        type:"post",
        data:{type:type,key:key,client_id:client_id},
        dataType:"json",
        success: function(result){
          $(".result_submission").html(result['result_submission']);
          $("body").removeClass("loading");
          window.location.reload();
        }
    })
  }  
}

if($(e.target).hasClass('edit_class')){
  //$("body").addClass("loading");
  var type = $(e.target).attr('data-element');
  var key = $(e.target).attr('id');
  var client_id = $("#client_id").val();

  console.log(client_id);

  $.ajax({
      url:"<?php echo URL::to('user/rct_edit_submission'); ?>",
      type:"post",
      data:{type:type,key:key,client_id:client_id},
      dataType:"json",
      success: function(result){
        $(".type_class_edit").val(result['type']);       
        $(".type_class_edit").attr("disabled", true);
        $(".rct_class_edit").attr("disabled", true);
        $(".rct_class_edit").val(result['rct_id']);
        $(".principal_class_edit").val(result['principal']);
        $(".start_date_class_edit").val(result['start_date']);
        $(".key_edit").val(result['key']);
        $(".type_edit").val(result['type']);

        $(".start_date_class_edit  ").datetimepicker({
           //defaultDate: fullDate,
           format: 'L',
           format: 'DD-MMM-YYYY',
        });

        $(".value_gross_class_edit").val(result['gross']);

        $(".rct_id_group_edit").show();
        $(".principal_group_edit").show();
        $(".start_date_group_edit").show();
        $(".gross_group_edit").show();
        

        if(result['type'] == 1){
          

          $(".site_group_edit").show();
          $(".finish_date_group_edit").show();

          $(".site_class_edit").val(result['site']);
          $(".finish_date_class_edit").val(result['finish']);

          $(".finish_date_class_edit  ").datetimepicker({
             //defaultDate: fullDate,
             format: 'L',
             format: 'DD-MMM-YYYY',
          });

          $(".sub_contractor_group_edit").hide();
          $(".sub_contractor_id_group_edit").hide();
          $(".value_group_edit").hide();
          $(".deduction_group_edit").hide();

        }
        else{
          $(".site_group_edit").hide();
          $(".finish_date_group_edit").hide();

          $(".sub_contractor_group_edit").show();
          $(".sub_contractor_id_group_edit").show();
          $(".value_group_edit").show();
          $(".deduction_group_edit").show();

          $(".sub_contractor_class_edit").val(result['sub_contractor']);
          $(".sub_contractor_id_class_edit").val(result['sub_contractor_id']);
          $(".value_net_class_edit").val(result['value_net']);
          $(".deduction_class_edit").val(result['deduction']);
          
        }


        $(".edit_submissions_modal").modal('show');
        
        //$("body").removeClass("loading");

      }
  })
}


if($(e.target).hasClass('saveas_pdf')){
  //$("body").addClass("loading");
  var type = $(".type_edit").val();
  var key = $(".key_edit").val();
  var client_id = $("#client_id").val();
  $("#pdf_content").html('');
  setTimeout(function() {
    $.ajax({
        url:"<?php echo URL::to('user/rct_saveaspdf'); ?>",
        type:"post",
        data:{type:type,key:key,client_id:client_id},
        success: function(result){
          SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);
          $("body").removeClass("loading");
        }
    })
  },2000);
}
if($(e.target).hasClass('save_as_pdf')){
  $("body").addClass("loading");
  var type = $(e.target).attr("data-element");
  var key = $(e.target).attr("id");
  var client_id = $("#client_id").val();
  $("#pdf_content").html('');
  setTimeout(function() {
    $.ajax({
        url:"<?php echo URL::to('user/rct_saveaspdf'); ?>",
        type:"post",
        data:{type:type,key:key,client_id:client_id},
        success: function(result){
          SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);
          $("body").removeClass("loading");
        }
    });
  },2000);
}
if($(e.target).hasClass('view_class')){
  var date = $(e.target).attr("data-element");
  var client_id = $("#client_id").val();
  $.ajax({
      url:"<?php echo URL::to('user/rct_submission_view'); ?>",
      type:"post",
      data:{date:date,client_id:client_id},
      dataType:"json",
      success: function(result){
        $(".view_submission").html(result['result_view_submission']);
        $(".month_year").html(result['month_year']);
        $(".view_modal").modal('show');
        

      }
  })

}

})

$(window).change(function(e) {
if($(e.target).hasClass('type_class')){
  var type = $(e.target).val();
  if(type == 1){
    $(".sub_contractor_group").hide();
    $(".sub_contractor_id_group").hide();
    $(".site_group").show();
    $(".finish_date_group").show();
    $(".value_group").hide();
    $(".deduction_group").hide();

    $(".rct_id_group").show();
    $(".principal_group").show();
    $(".start_date_group").show();
    $(".gross_group").show();

    $(".rct_class").val('');
    $(".principal_class").val('');
    $(".sub_contractor_class").val('');
    $(".sub_contractor_id_class").val('');
    $(".site_class").val('');
    $(".start_date_class").val('');
    $(".finish_date_class").val('');
    $(".value_gross_class").val('');
    $(".value_net_class").val('');
    $(".deduction_class").val('');
    $("#rct_id-error").hide();
  }
  else{
    $(".sub_contractor_group").show();
    $(".sub_contractor_id_group").show();
    $(".site_group").hide();
    $(".finish_date_group").hide();
    $(".value_group").show();
    $(".deduction_group").show();

    $(".rct_id_group").show();
    $(".principal_group").show();
    $(".start_date_group").show();
    $(".gross_group").show();

    $(".rct_class").val('');
    $(".principal_class").val('');
    $(".sub_contractor_class").val('');
    $(".sub_contractor_id_class").val('');
    $(".site_class").val('');
    $(".start_date_class").val('');
    $(".finish_date_class").val('');
    $(".value_gross_class").val('');
    $(".value_net_class").val('');
    $(".deduction_class").val('');
    $("#rct_id-error").hide();
  }
}

})
</script>

<!-- Page Scripts -->
<script>
$(function(){
    $('#rct_expand').DataTable({
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



$.ajaxSetup({async:false});
$('#add_tax_form').validate({
    rules: {          
      tax_name:{required: true},      
      tax_number: {required: true, remote:{url:"<?php echo URL::to('user/rct_tax_number_check')?>",
                  data: {'client_id':function(){return $('#client_id').val()}},
                                async:false },},
    },
    messages: {              
      tax_name:{required:"Enter Tax Name"},
      tax_number : {
          required : "Enter Tax Number",
          remote : "Tax Number is already exists",
      },      
    },
});




$.ajaxSetup({async:false});
$('#add_submission').validate({
    rules: {          
      submission_type:{required: true},      
      rct_id: {required: true, remote:{url:"<?php echo URL::to('user/rct_submission_check')?>",
                  data: {'client_id':function(){return $('#client_id').val()}, 'submission_type':function(){return $('.type_class').val()}},
                                async:false },},
      principal_name:{required: true},
      sub_contractor_name:{required: true},
      sub_contractor_id:{required: true},
      site:{required: true},
      start_date:{required: true},
      finish_date:{required: true},
      value_gross:{required: true},
      value_net:{required: true},
      deduction:{required: true},
    },
    messages: {              
      submission_type:{required:"Please select type"},
      rct_id : {
          required : "Enter RCT ID",
          remote : "RCT ID is already exists",
      },
      principal_name:{required:"Please select principal name "},
      sub_contractor_name:{required:"Please enter sub contractor name"},
      sub_contractor_id:{required:"Please enter sub contractor id"},
      site:{required:"Please enter site"},
      start_date:{required:"Please select start date"},
      finish_date:{required:"Please select finish date"},
      value_gross:{required:"Please enter value gross"},
      value_net:{required:"Please enter value net"},
      deduction:{required:"Please enter deduction"},      
    },
});

$('#edit_submission').validate({
    rules: {          
      submission_type:{required: true},      
      rct_id: {required: true, remote:{url:"<?php echo URL::to('user/rct_submission_check')?>",
                  data: {'client_id':function(){return $('#client_id').val()}, 'submission_type':function(){return $('.type_class').val()}},
                                async:false },},
      principal_name:{required: true},
      sub_contractor_name:{required: true},
      sub_contractor_id:{required: true},
      site:{required: true},
      start_date:{required: true},
      finish_date:{required: true},
      value_gross:{required: true},
      value_net:{required: true},
      deduction:{required: true},
    },
    messages: {              
      submission_type:{required:"Please select type"},
      rct_id : {
          required : "Enter RCT ID",
          remote : "RCT ID is already exists",
      },
      principal_name:{required:"Please select principal name "},
      sub_contractor_name:{required:"Please enter sub contractor name"},
      sub_contractor_id:{required:"Please enter sub contractor id"},
      site:{required:"Please enter site"},
      start_date:{required:"Please select start date"},
      finish_date:{required:"Please select finish date"},
      value_gross:{required:"Please enter value gross"},
      value_net:{required:"Please enter value net"},
      deduction:{required:"Please enter deduction"},      
    },
});


</script>


@stop
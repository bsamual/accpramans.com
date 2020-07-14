@extends('userheader')
@section('content')
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/jquery.dataTables.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/fixedHeader.dataTables.min.css'); ?>">

<script src="<?php echo URL::to('assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo URL::to('assets/js/dataTables.fixedHeader.min.js'); ?>"></script>

<script src="<?php echo URL::to('assets/js/jquery.form.js'); ?>"></script>
<script src="http://html2canvas.hertzen.com/dist/html2canvas.js"></script>

<link rel="stylesheet" href="<?php echo URL::to('assets/js/lightbox/colorbox.css'); ?>">
<script src="<?php echo URL::to('assets/js/lightbox/jquery.colorbox.js'); ?>"></script>
<style>
.disabled{
	cursor: not-allowed;
	pointer-events: none;
}

.disabled_hidden{
  display:none !important;
}
.dist1_hiddentd{
	display:none;
}
.dist1_showtd{
	display:block;
}

.dist2_hiddentd{
	display:none;
}
.dist2_showtd{
	display:block;
}

.dist3_hiddentd{
	display:none;
}
.dist3_showtd{
	display:block;
}

body{
  background: #f5f5f5 !important;
}
.fa-plus,.fa-minus-square{

  cursor:pointer;

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
.selectall{padding:10px 15px; background-image:linear-gradient(to bottom,#f5f5f5 0,#e8e8e8 100%); background: #f5f5f5; border:1px solid #ddd; border-radius: 3px;  }

.report_ok_button{background: #000; text-align: center; padding: 6px 12px; color: #fff; float: left; border: 0px; font-size: 13px; }
.report_ok_button:hover{background: #5f5f5f; text-decoration: none; color: #fff}
.report_ok_button:focus{background: #000; text-decoration: none; color: #fff}

.report_ok_button_100{background: #000; width:200px; margin-bottom:5px; height: 30px; line-height: 35px;  text-align: center; padding: 6px 12px; color: #fff; border: 0px; font-size: 13px; }
.report_ok_button_100:hover{background: #5f5f5f; text-decoration: none; color: #fff}
.report_ok_button_100:focus{background: #000; text-decoration: none; color: #fff}

.form-title{width: 100%; height: auto; float: left; margin-bottom: 5px;}


.table tr td, tr th{font-size: 15px;}
body{width: 2300px;}

body.loading {
    overflow: hidden;   
}
body.loading .modal_load {
    display: block;
}
    .table thead th:focus{background: #ddd !important;}
    .form-control{border-radius: 0px;}
    
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
.dropzone .dz-preview.dz-image-preview {

    background: #949400 !important;

}

.dropzone.dz-clickable .dz-message, .dropzone.dz-clickable .dz-message *{

      margin-top: 40px;

}

.dropzone .dz-preview {

  margin:0px !important;

  min-height:0px !important;

  width:100% !important;

  color:#000 !important;

}

.dropzone .dz-preview p {

  font-size:12px !important;

}

.remove_dropzone_attach{

  color:#f00 !important;

  margin-left:10px;

}
.liability_style{width: 100%;}
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
.sub_note{
      margin-left: 0px;
}
.main_note{
  font-size:16px;
  font-weight: 800;
  text-decoration: underline;
}
</style>
<?php 
  $admin_details = Db::table('admin')->first();
  $admin_cc = $admin_details->task_cc_email;
?>

<style>
.setting_crypt_modal .modal-dialog{margin-top: 15%;}
</style>
<div class="modal fade setting_crypt_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document" style="width:35%;margin-top:15%">
      <div class="modal-content">
        
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Settings</h4>
          </div>
          <div class="modal-body">
            <div class="crypt_groyp">
              <div class="form-group" style="width:80%">
                  <div class="form-title">Enter CRYPT PIN</div>
                  <input type="password" class="form-control crypt_pin_setting" value="" placeholder="Enter CRYPT Pin" name="crypt_pin" required>
                  <label class="error crypt_error" style="display:none">Hi</label>
              </div>
            </div>            
                         
              <div class="form-group" style="width:80%">
                <div class="setting_drop"></div>
              </div>
            
            
          </div>
          <div class="modal-footer">
              <input type="button" class="common_black_button crypt_button_setting" value="Submit">
              <div class="setting_submit"></div>
          </div>
      </div>
  </div>
</div>
<div class="modal fade emailunsent" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog" role="document">
    <form action="<?php echo URL::to('user/yearend_email_unsent_files'); ?>" method="post" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Email Unsent Files</h4>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-md-3">
              <label>From</label>
            </div>
            <div class="col-md-9">
              <input type="text" name="from_user" id="from_user" class="form-control input-sm" value="" required>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-3">
              <label>To</label>
            </div>
            <div class="col-md-9">
              <input type="text" name="to_user" id="to_user" class="form-control input-sm" value="" required>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-3">
              <label>CC</label>
            </div>
            <div class="col-md-9">
              <input type="text" name="cc_unsent" class="form-control input-sm" value="<?php echo $admin_cc; ?>" readonly>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-3">
              <label>Subject</label>
            </div>
            <div class="col-md-9">
              <input type="text" name="subject_unsent" class="form-control input-sm subject_unsent" value="" required>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-12">
              <label>Message</label>
            </div>
            <div class="col-md-12">
              <textarea name="message_editor" id="editor">
              </textarea>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-12">
              <label>Attachment</label>
            </div>
            <div class="col-md-12" id="email_attachments">
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="email_sent_option" id="email_sent_option" value="0">
        <input type="submit" class="btn btn-primary common_black_button" value="Email Unsent Files">
      </div>
    </div>
    </form>
  </div>
</div>

<div class="modal fade resendemailunsent" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">



  <div class="modal-dialog" role="document">



    <form action="<?php echo URL::to('user/yearend_email_unsent_files'); ?>" method="post" >



    <div class="modal-content">



      <div class="modal-header">



        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>



        <h4 class="modal-title" id="myModalLabel">Resend Email</h4>



      </div>



      <div class="modal-body">



          <div class="row">



            <div class="col-md-3">



              <label>From</label>



            </div>



            <div class="col-md-9">



              <input type="text" name="from_user" id="from_userresend" class="form-control input-sm" value="" required>



            </div>



          </div>



          <div class="row" style="margin-top:10px">



            <div class="col-md-3">



              <label>To</label>



            </div>



            <div class="col-md-9">



              <input type="text" name="to_user" id="to_userresend" class="form-control input-sm" value="" required>



            </div>



          </div>



          <div class="row" style="margin-top:10px">



            <div class="col-md-3">



              <label>CC</label>



            </div>



            <div class="col-md-9">



              <input type="text" name="cc_unsent" class="form-control input-sm" value="<?php echo $admin_cc; ?>" readonly>



            </div>



          </div>



          <div class="row" style="margin-top:10px">



            <div class="col-md-3">



              <label>Subject</label>



            </div>



            <div class="col-md-9">



              <input type="text" name="subject_unsent" class="form-control input-sm subject_resend" value="" required>



            </div>



          </div>



          <div class="row" style="margin-top:10px">



            <div class="col-md-12">



              <label>Message</label>



            </div>



            <div class="col-md-12">



              <textarea name="message_editor" id="editor_9">



                  



              </textarea>



            </div>



          </div>



          <div class="row" style="margin-top:10px">



            <div class="col-md-12">



              <label>Attachment</label>



            </div>



            <div class="col-md-12" id="email_attachmentsresend">



                



            </div>



          </div>



      </div>



      <div class="modal-footer">


      <input type="hidden" name="email_sent_option" id="email_resent_option" value="0">
        <input type="submit" class="btn btn-primary common_black_button" value="Send">



      </div>



    </div>



    </form>



  </div>



</div>

<div class="modal fade" id="supplementary_notes_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document" style="width:35%">
      <div class="modal-content">
        <form> 
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Choose Notes</h4>
          </div>
          <div class="modal-body supplementary_notes_modal_body">
            <label>Select Supplementary Note Template : </label>
            <select name="select_template" class="select_template form-control input-sm">
              <option value="">Select Template</option>
              <?php
                $templates = DB::table('supplementary_formula')->get();
                if(count($templates))
                {
                  foreach($templates as $template)
                  {
                    echo '<option value="'.$template->id.'">'.$template->name.'</option>';
                  }
                }
              ?> 
            </select>

            <div class="template_div" style="margin-top:15px;width:100%;display:none">
              <div class="col-md-4 col-lg-4" style="margin-top:5px">
                <label>Value 1 :</label>
              </div>
              <div class="col-md-8 col-lg-8" style="margin-top:5px">
                <input type="text" class="form-control input-sm value_1_output" id="value_1_output" value=""> 
              </div>

              <div class="col-md-4 col-lg-4" style="margin-top:5px">
                <label>Value 2 :</label>
              </div>
              <div class="col-md-8 col-lg-8" style="margin-top:5px">
                <input type="text" class="form-control input-sm value_2_output" id="value_2_output" value=""> 
              </div>

              <div class="col-md-4 col-lg-4" style="margin-top:5px">
                <label>Value 3 :</label>
              </div>
              <div class="col-md-8 col-lg-8" style="margin-top:5px">
                <input type="text" class="form-control input-sm value_3_output" id="value_3_output" value=""> 
              </div>

              <div class="col-md-4 col-lg-4" style="margin-top:5px">
                <label>Value 4 :</label>
              </div>
              <div class="col-md-8 col-lg-8" style="margin-top:5px">
                <input type="text" class="form-control input-sm value_4_output" id="value_4_output" value=""> 
              </div>

              <div class="col-md-4 col-lg-4" style="margin-top:5px">
                <label>Value 5 :</label>
              </div>
              <div class="col-md-8 col-lg-8" style="margin-top:5px">
                <input type="text" class="form-control input-sm value_5_output" id="value_5_output" value=""> 
              </div>

              <div class="col-md-4 col-lg-4" style="margin-top:5px">
                <label>Value 6 :</label>
              </div>
              <div class="col-md-8 col-lg-8" style="margin-top:5px">
                <input type="text" class="form-control input-sm value_6_output" id="value_6_output" value=""> 
              </div>

              <div class="col-md-4 col-lg-4" style="margin-top:5px;margin-bottom: 20px">
                <label>Invoice No :</label>
              </div>
              <div class="col-md-8 col-lg-8" style="margin-top:5px;margin-bottom: 20px">
                <input type="text" class="form-control input-sm invoice_output" id="invoice_output" value=""> 
              </div>

              <div class="note_text_div">

              </div>
            </div>
          </div>
          <div class="modal-footer">
            <div class="row add_notes_yearend" style="display:none">
                <div class="col-md-9 col-lg-9" style="text-align: left;margin-top: 8px;">
                  <span> Note : You can multiselect Supplementary note</span>
                </div>
                <div class="col-md-3 col-lg-3">
                    <input type="button" class="common_black_button add_notes_yearend_button" value="Insert Note" >
                </div>
            </div>
            <input type="button" class="common_black_button view_template" value="View Template">
          </div>
        </form>
      </div>
  </div>
</div>




<div class="content_section" style="margin-bottom:200px">
  <div class="page_title" style="margin-bottom: 2px;">
          <div class="col-lg-3" style="padding: 0px;">
                Client Year End Manager for the year - <?php echo $year_details->year; ?>                
                <input type="hidden" class="year_id" value="<?php echo $year_details->year;?>" name="">
                <input type="hidden" class="row_id" value="<?php echo $year_details->id;?>" name="">
                <input type="hidden" class="client_id" value="<?php echo $year_details->client_id;?>" name="">
            </div>
            <div class="col-lg-2" style="padding-right: 0px; line-height: 35px;">
                
            </div>
            <div class="col-lg-4 text-right"  style="padding: 0px;" >
            <div class="select_button" style=" margin-left: 10px;">
                <ul style="float: right;">                  
                  <li><input type="checkbox" name="checkbox_disable" class="checkbox_disable" id="checkbox_disable" value="1" <?php if($year_details->disabled == 1) { echo 'checked'; } else { echo ''; } ?>><label for="checkbox_disable" class="checkbox_disable_label" style="margin-top: 4px;margin-right: 26px;">Mark this Complete</label></li>              
                  <li><a href="<?php echo URL::to('user/yeadend_clients/'.base64_encode($year_details->year)); ?>" style="font-size: 13px; font-weight: 500;">Back to Year End Manager</a></li>    
                  <li><a href="javascript:" class="setting_class" style="font-size: 13px; font-weight: 500;">Settings</a></li>                
              </ul>
            </div>
                                    
  </div>

  <div style="clear: both;">
   <?php
    if(Session::has('message')) { ?>
        <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>

    
    <?php } ?>
    </div> 


</div>

<div class="table-responsive">

  <div class="col-lg-12" style="font-size: 15px; text-align: center; margin-bottom: 20px;"><b style="font-size:25px; color:blue"><?php echo $client_details->client_id.'&nbsp;&nbsp;&nbsp;'.$client_details->company?></b></div>
  <input type="hidden" class="id_class" value="<?php echo $year_details->id?>" name="">
  <div class="col-lg-3" style="font-size: 15px; line-height: 25px;">
    <div class="col-lg-3">Address:</div>
    <div class="col-lg-9">
      <?php 
        if($client_details->address1 != ''){
          echo $client_details->address1.'<br/>';
        }
        if($client_details->address2 != ''){
          echo $client_details->address2.'<br/>';
        }
        if($client_details->address3 != ''){
          echo $client_details->address3.'<br/>';
        }
        if($client_details->address4 != ''){
          echo $client_details->address4.'<br/>';
        }
        if($client_details->address5 != ''){
          echo $client_details->address5.'<br/>';
        }
        ?>
    </div> 

    <div class="col-lg-3">Email:</div>
    <div class="col-lg-9"><?php echo $client_details->email?></div>   
  </div>
  

    <div class="col-lg-12">
      <div style="width: 100%; height:auto; margin: 0px auto; margin-top: 20px;">
        <?php 
        $year_setting_details = DB::table('year_setting')->count();
        if($year_setting_details != 0 ){
        if($year_details->setting_id != ''){ ?> 

        <table class="table" style="max-width: 2300px;" >
          <tr class="table_tr" style="font-weight: bold;">
            <td colspan="2" align="center"><span style="float:left;margin-top:3px">Year End Date : </span> <input type="text" class="date_client_year_end form-control input-sm" id="date_client_year_end" value="<?php echo $year_details->year_end_date; ?>" style="width:55%" placeholder="Enter Date As <?php echo date('d-M-Y'); ?>" ></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="2" align="center"></td>
            <td colspan="2" align="center"></td>
          </tr>
          <tr class="table_tr" style="font-weight: bold;">
            <td></td>
            <td></td>
            <td style="background:#e2efd9" align="center">Supplementary</td>
            <td style="background:#deebf6" colspan="3" align="center">Distribution 1</td>
            <td style="background:#fff3cb" colspan="3" align="center">Distribution 2</td>
            <td style="background:#ededed" colspan="3" align="center">Distrbution 3 </td>
          </tr>
          <tr class="table_tr" style="font-weight: bold;">
            <td colspan="2" style="vertical-align: bottom;"> <input type="checkbox" name="hide_na_docs" class="hide_na_docs" id="hide_na_docs" value="1" <?php if($year_details->hide_na == 1) { echo 'checked'; } else { echo ''; } ?>> <label for="hide_na_docs"> Hide N/A Documents </label> </td>
            <td style="background:#e2efd9"></td>
            <td style="background:#deebf6" colspan="3" align="center"><span style="float: left; width: 30%; line-height: 30px;">Dist Email:</span><span style="float: left; width: 70%"><input type="text" placeholder="Enter Email Id" value="<?php echo $year_details->distribution1_email?>" class="form-control dist_email1"></span></td>
            <td style="background:#fff3cb" colspan="3" align="center"><span style="float: left; width: 30%; line-height: 30px;">Dist Email:</span><span style="float: left; width: 70%"><input type="text"  placeholder="Enter Email Id" value="<?php echo $year_details->distribution2_email?>" class="form-control dist_email2"></span></td>
            <td style="background:#ededed" colspan="3" align="center"><span style="float: left; width: 30%; line-height: 30px;">Dist Email:</span><span style="float: left; width: 70%"><input type="text"  placeholder="Enter Email Id" value="<?php echo $year_details->distribution3_email?>" class="form-control dist_email3"></span></td>
          </tr>
          <tr class="table_tr" style="font-weight: bold;">
            <td>N/A</td>
            <td>Document</td>
            <td style="background:#e2efd9">Notes</td>
            <td style="background:#deebf6;" width="250px">Attachments</td>
            <td style="background:#deebf6;" width="100px"></td>
            <td style="background:#deebf6;" width="120px">Future</td>
            <td style="background:#fff3cb;" width="250px">Attachments</td>
            <td style="background:#fff3cb;" width="100px"></td>
            <td style="background:#fff3cb;" width="120px">Future</td>
            <td style="background:#ededed;" width="250px">Attachments</td>
            <td style="background:#ededed;" width="100px"></td>
            <td style="background:#ededed" width="120px">Future</td>
          </tr>

          <?php
          $setting_detail = explode(',',$year_details->setting_id);
          $active_detail = explode(',',$year_details->setting_active);   

          if($year_details->distribution1_future == "")
          {
            $explode = explode(",",$year_details->setting_id);
            $future = '';
            if(count($explode))
            {
                  foreach($explode as $exp)
                  {
                        if($future == "")
                        {
                              $future = '0';
                        }
                        else{
                              $future = $future.',0';
                        }
                  }
            }
            $distribution1_future = explode(',',$future);
          }
          else{
            $distribution1_future = explode(',',$year_details->distribution1_future);
            if(count($distribution1_future) != count($setting_detail))
            {
              $diff = count($setting_detail) - count($distribution1_future);
              $addzero = '';
              for($k = 1; $k <= $diff; $k++)
              {
                if($addzero == "")
                {
                  $addzero = '0';
                }
                else{
                  $addzero = $addzero.',0';
                }
              }
              $total = $year_details->distribution1_future.','.$addzero;
              $distribution1_future = explode(',',$total);
            }
          }
          if($year_details->distribution2_future == "")
          {
            $explode = explode(",",$year_details->setting_id);
            $future = '';
            if(count($explode))
            {
                  foreach($explode as $exp)
                  {
                        if($future == "")
                        {
                              $future = '0';
                        }
                        else{
                              $future = $future.',0';
                        }
                  }
            }
            $distribution2_future = explode(',',$future);
          }
          else{
            $distribution2_future = explode(',',$year_details->distribution2_future);
            if(count($distribution2_future) != count($setting_detail))
            {
              $diff = count($setting_detail) - count($distribution2_future);
              $addzero = '';
              for($k = 1; $k <= $diff; $k++)
              {
                if($addzero == "")
                {
                  $addzero = '0';
                }
                else{
                  $addzero = $addzero.',0';
                }
              }
              $total = $year_details->distribution2_future.','.$addzero;
              $distribution2_future = explode(',',$total);
            }
          }
          if($year_details->distribution3_future == "")
          {
            $explode = explode(",",$year_details->setting_id);
            $future = '';
            if(count($explode))
            {
                  foreach($explode as $exp)
                  {
                        if($future == "")
                        {
                              $future = '0';
                        }
                        else{
                              $future = $future.',0';
                        }
                  }
            }

            
            $distribution3_future = explode(',',$future);
          }
          else{
            $distribution3_future = explode(',',$year_details->distribution3_future);
            if(count($distribution3_future) != count($setting_detail))
            {
              $diff = count($setting_detail) - count($distribution3_future);
              $addzero = '';
              for($k = 1; $k <= $diff; $k++)
              {
                if($addzero == "")
                {
                  $addzero = '0';
                }
                else{
                  $addzero = $addzero.',0';
                }
              }
              $total = $year_details->distribution3_future.','.$addzero;
              $distribution3_future = explode(',',$total);
            }
          }

          $active_merge = array_combine($setting_detail,$active_detail);
          
          $distribution1_merge = array_combine($setting_detail,$distribution1_future);
          $distribution2_merge = array_combine($setting_detail,$distribution2_future);
          $distribution3_merge = array_combine($setting_detail,$distribution3_future);

          if(count($setting_detail)){
            foreach ($setting_detail as $single) {
              $settingname = DB::table('year_setting')->where('id', $single)->first();
              $liability_single = DB::table('year_client_liability')->where('row_id', $year_details->id)->where('setting_id', $single)->first();  



          ?>
          <?php
          
          
          // if(count($active_detail)){
          //   foreach ($active_detail as $single_active) {
          //     if($single_active == 1){
          //       $checkbox_active = 'checked';
          //     }
          //     else{
          //       $checkbox_active = '';
          //     }
          //   }
          // }
          ?>              
            <tr class="table_tr">
              <td><input type="checkbox" value="1" <?php if($active_merge[$single] == 1){echo 'checked';} else{ echo '';}  ?> name="setting_active" class="setting_active"><label>&nbsp;</label></td>
              <td><?php echo $settingname->document?></td>
              <td style="background:#e2efd9">
              	<?php if($active_merge[$single] == 1) { $disabled ='disabled_hidden'; } else { $disabled ='notdisabled_hidden'; }  
                echo '<div>';
                  $attachments = DB::table('yearend_notes_attachments')->where('client_id',$year_details->id)->where('setting_id',$single)->where('attach_type',0)
                  ->where('distribution_type',0)->get();
                  if(count($attachments))
                  {
                        foreach($attachments as $attachment)
                        {
                          echo '<a href="javascript:" class="fileattachment '.$disabled.'" style="" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachments.'">'.$attachment->attachments.'</a><a href="javascript:" class="trash_icon '.$disabled.'"><i class="fa fa-trash trash_notes" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                        }
                  }
                  ?>
                  <i class="fa fa-plus call_notes_modal <?php echo $disabled; ?>" data-element="<?php echo $year_details->id; ?>" data-value="<?php echo $single; ?>" data-distribution="0" data-type="0" style="margin-top:10px; font-weight: 300;" aria-hidden="true" title="Add Attachment"></i>
                  <?php if(count($attachments)) { ?>
                      <i class="fa fa-minus-square fadeleteall_attachments_note <?php echo $disabled; ?>" data-element="<?php echo $year_details->id; ?>" data-value="<?php echo $single; ?>" data-type="0" data-distribution="0" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>
                  <?php } ?>
                </div>
              </td>

              <td style="background:#deebf6">

              	<?php
              	if($distribution1_merge[$single] == 1) { $hide ='dist1_hiddentd'; } else { $hide = 'dist1_showtd'; } 
              	if($active_merge[$single] == 1) { $disabled ='disabled_hidden'; } else { $disabled ='notdisabled_hidden'; } 
              	echo '<div class="'.$hide.'">';
	                $attachments = DB::table('yearend_distribution_attachments')->where('client_id',$year_details->id)->where('setting_id',$single)->where('distribution_type',1)->where('attach_type',0)->get();
	                if(count($attachments))
	                {
	                      foreach($attachments as $attachment)
	                      {
	                          echo '<a href="javascript:" class="fileattachment '.$disabled.'" style="" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachments.'">'.$attachment->attachments.'</a><a href="javascript:" class="trash_icon '.$disabled.'"><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
	                      }
	                }
	                ?>
	              	<i class="fa fa-plus <?php echo $disabled; ?>" style="margin-top:10px; font-weight: 300;" aria-hidden="true" title="Add Attachment"></i>
	              	<?php if(count($attachments)) { ?>
	                    <i class="fa fa-minus-square fadeleteall_attachments <?php echo $disabled; ?>" data-element="<?php echo $year_details->id; ?>" data-value="<?php echo $single; ?>" data-distribution="1" data-type="0" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>
	                <?php } ?>
                </div>
              	<div class="img_div" style="z-index:9999999">
                    <form name="image_form" id="image_form" action="<?php echo URL::to('user/yearend_individual_attachment?distribution_type=1&attach_type=0'); ?>" method="post" enctype="multipart/form-data" style="text-align: left;">
                      <input type="file" name="image_file[]" required class="form-control image_file" value="" multiple>
                      <input type="hidden" name="hidden_client_id" value="<?php echo $year_details->id; ?>">
                      <input type="hidden" name="hidden_setting_id" value="<?php echo $single; ?>">
                      <input type="submit" name="image_submit" class="btn btn-sm btn-primary image_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
                      <spam class="error_files"></spam>
                    </form>
                    <div style="width:100%;text-align:center;margin-top:-10px;margin-bottom:10px;color:#000"><label style="font-weight:800;">OR</label></div>
                    <div class="image_div_attachments">
                      <form action="<?php echo URL::to('user/yearend_attachment_individual?distribution_type=1&attach_type=0'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                      	  <input type="hidden" name="hidden_client_id" value="<?php echo $year_details->id; ?>">
                      	  <input type="hidden" name="hidden_setting_id" value="<?php echo $single; ?>">
                          <input name="_token" type="hidden" value="">
                      </form>
                      <a href="<?php echo URL::to('user/yearend_individualclient/'.base64_encode($year_details->id)); ?>" class="btn btn-sm btn-primary" align="left" style="margin-left:7px; float:left;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
                    </div>
                 </div>
              </td>
              <td style="background:#deebf6">

                <input type="text" data-element="<?php echo base64_encode($single); ?>" class="form-control liability_style liability_class_1 <?php echo $disabled; ?>" value="<?php if(count($liability_single)){ echo number_format_invoice_without_decimal($liability_single->liability1);} ?>" placeholder="Enter Liability"  name="">
              </td>
              <td style="background:#deebf6"><input type="checkbox" id="future1_<?php echo $single?>" name="distribution1_future" class="distribution1_future <?php echo $disabled; ?>" value="1" <?php if($distribution1_merge[$single] == 1){echo 'checked';} else{ echo '';}  ?>><label for="future1_<?php echo $single?>" class="<?php echo $disabled; ?>">Future</label></td>
              <td style="background:#fff3cb">
              	<?php
              	if($distribution2_merge[$single] == 1) { $hide ='dist2_hiddentd'; } else { $hide = 'dist2_showtd'; } 
              	if($active_merge[$single] == 1) { $disabled ='disabled_hidden'; } else { $disabled ='notdisabled_hidden'; } 
                echo '<div class="'.$hide.'">';
	                $attachments = DB::table('yearend_distribution_attachments')->where('client_id',$year_details->id)->where('setting_id',$single)->where('distribution_type',2)->where('attach_type',0)->get();
	                if(count($attachments))
	                {
	                      foreach($attachments as $attachment)
	                      {
	                          echo '<a href="javascript:" class="fileattachment '.$disabled.'" style="" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachments.'">'.$attachment->attachments.'</a><a href="javascript:" class="trash_icon '.$disabled.'"><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
	                      }
	                }
	                ?>
	              	<i class="fa fa-plus <?php echo $disabled; ?>" style="margin-top:10px; font-weight: 300;" aria-hidden="true" title="Add Attachment"></i>
	              	<?php if(count($attachments)) { ?>
	                    <i class="fa fa-minus-square fadeleteall_attachments <?php echo $disabled; ?>" data-element="<?php echo $year_details->id; ?>" data-value="<?php echo $single; ?>" data-distribution="2" data-type="0" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>
	                <?php } ?>
                </div>
              	<div class="img_div" style="z-index:9999999">
                    <form name="image_form" id="image_form" action="<?php echo URL::to('user/yearend_individual_attachment?distribution_type=2&attach_type=0'); ?>" method="post" enctype="multipart/form-data" style="text-align: left;">
                      <input type="file" name="image_file[]" required class="form-control image_file" value="" multiple>
                      <input type="hidden" name="hidden_client_id" value="<?php echo $year_details->id; ?>">
                      <input type="hidden" name="hidden_setting_id" value="<?php echo $single; ?>">
                      <input type="submit" name="image_submit" class="btn btn-sm btn-primary image_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
                      <spam class="error_files"></spam>
                    </form>
                    <div style="width:100%;text-align:center;margin-top:-10px;margin-bottom:10px;color:#000"><label style="font-weight:800;">OR</label></div>
                    <div class="image_div_attachments">
                      <form action="<?php echo URL::to('user/yearend_attachment_individual?distribution_type=2&attach_type=0'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                      	  <input type="hidden" name="hidden_client_id" value="<?php echo $year_details->id; ?>">
                      	  <input type="hidden" name="hidden_setting_id" value="<?php echo $single; ?>">
                          <input name="_token" type="hidden" value="">
                      </form>
                      <a href="<?php echo URL::to('user/yearend_individualclient/'.base64_encode($year_details->id)); ?>" class="btn btn-sm btn-primary" align="left" style="margin-left:7px; float:left;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
                    </div>
                 </div>
              </td>
              <td style="background:#fff3cb">
                <input type="text" data-element="<?php echo base64_encode($single); ?>" class="form-control liability_style liability_class_2 <?php echo $disabled; ?>" value="<?php if(count($liability_single)){ echo number_format_invoice_without_decimal($liability_single->liability2);} ?>" placeholder="Enter Liability" name="">
              </td>
              <td style="background:#fff3cb"><input type="checkbox" id="future2_<?php echo $single?>" name="distribution2_future" class="distribution2_future <?php echo $disabled; ?>" value="1" <?php if($distribution2_merge[$single] == 1){echo 'checked';} else{ echo '';}  ?>><label for="future2_<?php echo $single?>" class="<?php echo $disabled; ?>">Future</label></td>
              <td style="background:#ededed">
              	<?php
              	if($distribution3_merge[$single] == 1) { $hide ='dist3_hiddentd'; } else { $hide = 'dist3_showtd'; } 
              	if($active_merge[$single] == 1) { $disabled ='disabled_hidden'; } else { $disabled ='notdisabled_hidden'; } 
                echo '<div class="'.$hide.'">';
	                $attachments = DB::table('yearend_distribution_attachments')->where('client_id',$year_details->id)->where('setting_id',$single)->where('distribution_type',3)->where('attach_type',0)->get();
	                if(count($attachments))
	                {
	                      foreach($attachments as $attachment)
	                      {
	                          echo '<a href="javascript:" class="fileattachment '.$disabled.'" style="" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachments.'">'.$attachment->attachments.'</a><a href="javascript:" class="trash_icon '.$disabled.'"><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
	                      }
	                }
	                ?>
	              	<i class="fa fa-plus <?php echo $disabled; ?>" style="margin-top:10px; font-weight: 300;" aria-hidden="true" title="Add Attachment"></i>
	              	<?php if(count($attachments)) { ?>
	                    <i class="fa fa-minus-square fadeleteall_attachments <?php echo $disabled; ?>" data-element="<?php echo $year_details->id; ?>" data-value="<?php echo $single; ?>" data-distribution="3" data-type="0" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>
	                <?php } ?>
                </div>
              	<div class="img_div" style="z-index:9999999">
                    <form name="image_form" id="image_form" action="<?php echo URL::to('user/yearend_individual_attachment?distribution_type=3&attach_type=0'); ?>" method="post" enctype="multipart/form-data" style="text-align: left;">
                      <input type="file" name="image_file[]" required class="form-control image_file" value="" multiple>
                      <input type="hidden" name="hidden_client_id" value="<?php echo $year_details->id; ?>">
                      <input type="hidden" name="hidden_setting_id" value="<?php echo $single; ?>">
                      <input type="submit" name="image_submit" class="btn btn-sm btn-primary image_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
                      <spam class="error_files"></spam>
                    </form>
                    <div style="width:100%;text-align:center;margin-top:-10px;margin-bottom:10px;color:#000"><label style="font-weight:800;">OR</label></div>
                    <div class="image_div_attachments">
                      <form action="<?php echo URL::to('user/yearend_attachment_individual?distribution_type=3&attach_type=0'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                      	  <input type="hidden" name="hidden_client_id" value="<?php echo $year_details->id; ?>">
                      	  <input type="hidden" name="hidden_setting_id" value="<?php echo $single; ?>">
                          <input name="_token" type="hidden" value="">
                      </form>
                      <a href="<?php echo URL::to('user/yearend_individualclient/'.base64_encode($year_details->id)); ?>" class="btn btn-sm btn-primary" align="left" style="margin-left:7px; float:left;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
                    </div>
                 </div>
              </td>
              <td style="background:#ededed">
                <input type="text" data-element="<?php echo base64_encode($single); ?>" class="form-control liability_class_3 liability_style <?php echo $disabled; ?>" value="<?php if(count($liability_single)){ echo number_format_invoice_without_decimal($liability_single->liability3);} ?>" placeholder="Enter Liability" name="">
              </td>
              <td style="background:#ededed"><input type="checkbox" id="future3_<?php echo $single?>" name="distribution3_future" class="distribution3_future <?php echo $disabled; ?>" value="1" <?php if($distribution3_merge[$single] == 1){echo 'checked';} else{ echo '';}  ?>><label for="future3_<?php echo $single?>" class="<?php echo $disabled; ?>">Future</label></td>
            </tr>
          <?php
            }
          }
          ?>
          <tr class="table_tr">
            <td></td>
            <td height="30px"></td>
            <td style="background:#e2efd9"></td>
            <td style="background:#deebf6"></td>
            <td style="background:#deebf6"></td>
            <td style="background:#deebf6"></td>
            <td style="background:#fff3cb"></td>
            <td style="background:#fff3cb"></td>
            <td style="background:#fff3cb"></td>
            <td style="background:#ededed"></td>
            <td style="background:#ededed"></td>
            <td style="background:#ededed"></td>
          </tr>
          <tr class="table_tr">
            <td></td>
            <td>Closing Note</td>
            <td style="background:#e2efd9">
              <?php 
                echo '<div>';
                  $attachments = DB::table('yearend_notes_attachments')->where('client_id',$year_details->id)->where('setting_id',0)->where('attach_type',1)->where('distribution_type',0)->get();
                  if(count($attachments))
                  {
                        foreach($attachments as $attachment)
                        {
                          echo '<a href="javascript:" class="fileattachment '.$disabled.'" style="" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachments.'">'.$attachment->attachments.'</a><a href="javascript:" class="trash_icon '.$disabled.'"><i class="fa fa-trash trash_notes" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                        }
                  }
                  ?>
                  <i class="fa fa-plus call_notes_modal" data-element="<?php echo $year_details->id; ?>" data-value="0" data-distribution="0" data-type="1" style="margin-top:10px; font-weight: 300;" aria-hidden="true" title="Add Attachment"></i>
                  <?php if(count($attachments)) { ?>
                      <i class="fa fa-minus-square fadeleteall_attachments_note" data-element="<?php echo $year_details->id; ?>" data-value="0" data-distribution="0" data-type="1" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>
                  <?php } ?>
                </div>
            </td>
            <td style="background:#deebf6">
              <?php 
                echo '<div>';
                  $attachments = DB::table('yearend_notes_attachments')->where('client_id',$year_details->id)->where('setting_id',0)->where('attach_type',1)->where('distribution_type',1)->get();
                  if(count($attachments))
                  {
                        foreach($attachments as $attachment)
                        {
                          echo '<a href="javascript:" class="fileattachment '.$disabled.'" style="" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachments.'">'.$attachment->attachments.'</a><a href="javascript:" class="trash_icon '.$disabled.'"><i class="fa fa-trash trash_notes" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                        }
                  }
                  ?>
                  <i class="fa fa-plus call_notes_modal" data-element="<?php echo $year_details->id; ?>" data-value="0" data-distribution="1" data-type="1" style="margin-top:10px; font-weight: 300;" aria-hidden="true" title="Add Attachment"></i>
                  <?php if(count($attachments)) { ?>
                      <i class="fa fa-minus-square fadeleteall_attachments_note" data-element="<?php echo $year_details->id; ?>" data-value="0" data-distribution="1" data-type="1" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>
                  <?php } ?>
                </div>
            </td>
            <td style="background:#deebf6"></td>
            <td style="background:#deebf6"></td>
            <td style="background:#fff3cb">
              <?php 
                echo '<div>';
                  $attachments = DB::table('yearend_notes_attachments')->where('client_id',$year_details->id)->where('setting_id',0)->where('attach_type',1)->where('distribution_type',2)->get();
                  if(count($attachments))
                  {
                        foreach($attachments as $attachment)
                        {
                          echo '<a href="javascript:" class="fileattachment '.$disabled.'" style="" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachments.'">'.$attachment->attachments.'</a><a href="javascript:" class="trash_icon '.$disabled.'"><i class="fa fa-trash trash_notes" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                        }
                  }
                  ?>
                  <i class="fa fa-plus call_notes_modal" data-element="<?php echo $year_details->id; ?>" data-value="0" data-distribution="2" data-type="1" style="margin-top:10px; font-weight: 300;" aria-hidden="true" title="Add Attachment"></i>
                  <?php if(count($attachments)) { ?>
                      <i class="fa fa-minus-square fadeleteall_attachments_note" data-element="<?php echo $year_details->id; ?>" data-value="0" data-distribution="2" data-type="1" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>
                  <?php } ?>
                </div>
            </td>
            <td style="background:#fff3cb"></td>
            <td style="background:#fff3cb"></td>
            <td style="background:#ededed">
              <?php 
                echo '<div>';
                  $attachments = DB::table('yearend_notes_attachments')->where('client_id',$year_details->id)->where('setting_id',0)->where('attach_type',1)->where('distribution_type',3)->get();
                  if(count($attachments))
                  {
                        foreach($attachments as $attachment)
                        {
                          echo '<a href="javascript:" class="fileattachment '.$disabled.'" style="" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachments.'">'.$attachment->attachments.'</a><a href="javascript:" class="trash_icon '.$disabled.'"><i class="fa fa-trash trash_notes" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                        }
                  }
                  ?>
                  <i class="fa fa-plus call_notes_modal" data-element="<?php echo $year_details->id; ?>" data-value="0" data-distribution="3" data-type="1" style="margin-top:10px; font-weight: 300;" aria-hidden="true" title="Add Attachment"></i>
                  <?php if(count($attachments)) { ?>
                      <i class="fa fa-minus-square fadeleteall_attachments_note" data-element="<?php echo $year_details->id; ?>" data-value="0" data-distribution="3" data-type="1" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>
                  <?php } ?>
                </div>
            </td>
            <td style="background:#ededed"></td>
            <td style="background:#ededed"></td>
          </tr>
          <tr class="table_tr">
            <td></td>
            <td>Fee Note</td>
            <td style="background:#e2efd9">
              <?php 
                echo '<div>';
                  $attachments = DB::table('yearend_notes_attachments')->where('client_id',$year_details->id)->where('setting_id',0)->where('attach_type',2)->where('distribution_type',0)->get();
                  if(count($attachments))
                  {
                        foreach($attachments as $attachment)
                        {
                          echo '<a href="javascript:" class="fileattachment '.$disabled.'" style="" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachments.'">'.$attachment->attachments.'</a><a href="javascript:" class="trash_icon '.$disabled.'"><i class="fa fa-trash trash_notes" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                        }
                  }
                  ?>
                  <i class="fa fa-plus call_notes_modal" data-element="<?php echo $year_details->id; ?>" data-value="0" data-distribution="0" data-type="2" style="margin-top:10px; font-weight: 300;" aria-hidden="true" title="Add Attachment"></i>
                  <?php if(count($attachments)) { ?>
                      <i class="fa fa-minus-square fadeleteall_attachments_note" data-element="<?php echo $year_details->id; ?>" data-value="0" data-distribution="0" data-type="2" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>
                  <?php } ?>
                </div>
            </td>
            <td style="background:#deebf6">
            	<?php 
                echo '<div>';
                  $attachments = DB::table('yearend_notes_attachments')->where('client_id',$year_details->id)->where('setting_id',0)->where('attach_type',2)->where('distribution_type',1)->get();
                  if(count($attachments))
                  {
                        foreach($attachments as $attachment)
                        {
                          echo '<a href="javascript:" class="fileattachment '.$disabled.'" style="" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachments.'">'.$attachment->attachments.'</a><a href="javascript:" class="trash_icon '.$disabled.'"><i class="fa fa-trash trash_notes" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                        }
                  }
                  ?>
                  <i class="fa fa-plus call_notes_modal" data-element="<?php echo $year_details->id; ?>" data-value="0" data-distribution="1" data-type="2" style="margin-top:10px; font-weight: 300;" aria-hidden="true" title="Add Attachment"></i>
                  <?php if(count($attachments)) { ?>
                      <i class="fa fa-minus-square fadeleteall_attachments_note" data-element="<?php echo $year_details->id; ?>" data-value="0" data-distribution="1" data-type="2" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>
                  <?php } ?>
                </div>
            </td>
            <td style="background:#deebf6"></td>
            <td style="background:#deebf6"></td>
            <td style="background:#fff3cb">
            	<?php 
                echo '<div>';
                  $attachments = DB::table('yearend_notes_attachments')->where('client_id',$year_details->id)->where('setting_id',0)->where('attach_type',2)->where('distribution_type',2)->get();
                  if(count($attachments))
                  {
                        foreach($attachments as $attachment)
                        {
                          echo '<a href="javascript:" class="fileattachment '.$disabled.'" style="" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachments.'">'.$attachment->attachments.'</a><a href="javascript:" class="trash_icon '.$disabled.'"><i class="fa fa-trash trash_notes" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                        }
                  }
                  ?>
                  <i class="fa fa-plus call_notes_modal" data-element="<?php echo $year_details->id; ?>" data-value="0" data-distribution="2" data-type="2" style="margin-top:10px; font-weight: 300;" aria-hidden="true" title="Add Attachment"></i>
                  <?php if(count($attachments)) { ?>
                      <i class="fa fa-minus-square fadeleteall_attachments_note" data-element="<?php echo $year_details->id; ?>" data-value="0" data-distribution="2" data-type="2" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>
                  <?php } ?>
                </div>
            </td>
            <td style="background:#fff3cb"></td>
            <td style="background:#fff3cb"></td>
            <td style="background:#ededed">
            	<?php 
                echo '<div>';
                  $attachments = DB::table('yearend_notes_attachments')->where('client_id',$year_details->id)->where('setting_id',0)->where('attach_type',2)->where('distribution_type',3)->get();
                  if(count($attachments))
                  {
                        foreach($attachments as $attachment)
                        {
                          echo '<a href="javascript:" class="fileattachment '.$disabled.'" style="" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachments.'">'.$attachment->attachments.'</a><a href="javascript:" class="trash_icon '.$disabled.'"><i class="fa fa-trash trash_notes" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                        }
                  }
                  ?>
                  <i class="fa fa-plus call_notes_modal" data-element="<?php echo $year_details->id; ?>" data-value="0" data-distribution="3" data-type="2" style="margin-top:10px; font-weight: 300;" aria-hidden="true" title="Add Attachment"></i>
                  <?php if(count($attachments)) { ?>
                      <i class="fa fa-minus-square fadeleteall_attachments_note" data-element="<?php echo $year_details->id; ?>" data-value="0" data-distribution="3" data-type="2" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>
                  <?php } ?>
                </div>
            </td>
            <td style="background:#ededed"></td>
            <td style="background:#ededed"></td>
          </tr>
          <tr class="table_tr">
            <td></td>
            <td>Signature:</td>
            <td>
              <?php 
                echo '<div>';
                  $attachments = DB::table('yearend_notes_attachments')->where('client_id',$year_details->id)->where('setting_id',0)->where('attach_type',3)->where('distribution_type',0)->get();
                  if(count($attachments))
                  {
                        foreach($attachments as $attachment)
                        {
                          echo '<a href="javascript:" class="fileattachment '.$disabled.'" style="" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachments.'">'.$attachment->attachments.'</a><a href="javascript:" class="trash_icon '.$disabled.'"><i class="fa fa-trash trash_notes" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                        }
                  }
                  ?>
                  <i class="fa fa-plus call_notes_modal" data-element="<?php echo $year_details->id; ?>" data-value="0" data-distribution="0" data-type="3" style="margin-top:10px; font-weight: 300;" aria-hidden="true" title="Add Attachment"></i>
                  <?php if(count($attachments)) { ?>
                      <i class="fa fa-minus-square fadeleteall_attachments_note" data-element="<?php echo $year_details->id; ?>" data-value="0" data-distribution="0" data-type="3" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>
                  <?php } ?>
                </div>
            </td>
            <td>
            	<?php 
                echo '<div>';
                  $attachments = DB::table('yearend_notes_attachments')->where('client_id',$year_details->id)->where('setting_id',0)->where('attach_type',3)->where('distribution_type',1)->get();
                  if(count($attachments))
                  {
                        foreach($attachments as $attachment)
                        {
                          echo '<a href="javascript:" class="fileattachment '.$disabled.'" style="" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachments.'">'.$attachment->attachments.'</a><a href="javascript:" class="trash_icon '.$disabled.'"><i class="fa fa-trash trash_notes" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                        }
                  }
                  ?>
                  <i class="fa fa-plus call_notes_modal" data-element="<?php echo $year_details->id; ?>" data-value="0" data-distribution="1" data-type="3" style="margin-top:10px; font-weight: 300;" aria-hidden="true" title="Add Attachment"></i>
                  <?php if(count($attachments)) { ?>
                      <i class="fa fa-minus-square fadeleteall_attachments_note" data-element="<?php echo $year_details->id; ?>" data-value="0" data-distribution="1" data-type="3" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>
                  <?php } ?>
                </div>
            </td>
            <td></td>
            <td></td>
            <td>
            	<?php 
                echo '<div>';
                  $attachments = DB::table('yearend_notes_attachments')->where('client_id',$year_details->id)->where('setting_id',0)->where('attach_type',3)->where('distribution_type',2)->get();
                  if(count($attachments))
                  {
                        foreach($attachments as $attachment)
                        {
                          echo '<a href="javascript:" class="fileattachment '.$disabled.'" style="" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachments.'">'.$attachment->attachments.'</a><a href="javascript:" class="trash_icon '.$disabled.'"><i class="fa fa-trash trash_notes" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                        }
                  }
                  ?>
                  <i class="fa fa-plus call_notes_modal" data-element="<?php echo $year_details->id; ?>" data-value="0" data-distribution="2" data-type="3" style="margin-top:10px; font-weight: 300;" aria-hidden="true" title="Add Attachment"></i>
                  <?php if(count($attachments)) { ?>
                      <i class="fa fa-minus-square fadeleteall_attachments_note" data-element="<?php echo $year_details->id; ?>" data-value="0" data-distribution="2" data-type="3" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>
                  <?php } ?>
                </div>
            </td>
            <td></td>
            <td></td>
            <td>
            	<?php 
                echo '<div>';
                  $attachments = DB::table('yearend_notes_attachments')->where('client_id',$year_details->id)->where('setting_id',0)->where('attach_type',3)->where('distribution_type',3)->get();
                  if(count($attachments))
                  {
                        foreach($attachments as $attachment)
                        {
                          echo '<a href="javascript:" class="fileattachment '.$disabled.'" style="" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachments.'">'.$attachment->attachments.'</a><a href="javascript:" class="trash_icon '.$disabled.'"><i class="fa fa-trash trash_notes" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                        }
                  }
                  ?>
                  <i class="fa fa-plus call_notes_modal" data-element="<?php echo $year_details->id; ?>" data-value="0" data-distribution="3" data-type="3" style="margin-top:10px; font-weight: 300;" aria-hidden="true" title="Add Attachment"></i>
                  <?php if(count($attachments)) { ?>
                      <i class="fa fa-minus-square fadeleteall_attachments_note" data-element="<?php echo $year_details->id; ?>" data-value="0" data-distribution="3" data-type="3" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>
                  <?php } ?>
                </div>
            </td>
            <td></td>
            <td></td>
          </tr>

          <tr style="font-weight: bold;">
            <td></td>
            <td></td>
            <td></td>
            <td colspan="3" align="center">
              <a href="javascript:" class="report_ok_button_100 email_unsent_dist1">Email Distribution</a>
              <?php 
              $check_last_email_sent = DB::table('year_client')->where('id',$year_end_id)->first();
              if($check_last_email_sent->dist1_email_sent_date != "0000-00-00 00:00:00")
              {
                $disabled_resent = '';
                $email_date = date('d F Y @ H : i', strtotime($check_last_email_sent->dist1_email_sent_date));
                $email_date = '<p>'.$email_date.'</p>';
              }
              else{
                $disabled_resent = 'disabled';
                $email_date = '<p>&nbsp;</p>';
              }
              echo $email_date;
              ?>
              <a href="javascript:" class="report_ok_button_100 email_resend_dist1 <?php echo $disabled_resent; ?>" style="clear: both">Resend Email to Client</a>
              <div style="width: 100%; margin: 10px 0px;">Distribution Notes</div>
              <div style="width: 100%">
                <input type="radio" id="downloadpdf1" name="download" class="download" value="1"><label for="downloadpdf1">PDF</label>
                <input type="radio" id="downloadword1" name="download" class="download" value="2"><label for="downloadword1">Word</label>
              </div>
              <div style="width: 100%">
                <input type="checkbox" id="email_checkbox1" name="email_checkbox" class="email_checkbox"><label for="email_checkbox1">Email</label>                                
              </div>
              <div style="width: 100%;">
                <a href="javascript:" class="report_ok_button_100 download_distribution" id="download_distribution1" style="clear: both">Download</a>
              </div>
            </td>
            <td colspan="3" align="center">
              <a href="javascript:" class="report_ok_button_100 email_unsent_dist2">Email Distribution</a>
              <?php 
              $check_last_email_sent = DB::table('year_client')->where('id',$year_end_id)->first();
              if($check_last_email_sent->dist2_email_sent_date != "0000-00-00 00:00:00")
              {
                $disabled_resent = '';
                $email_date = date('d F Y @ H : i', strtotime($check_last_email_sent->dist2_email_sent_date));
                $email_date = '<p>'.$email_date.'</p>';
              }
              else{
                $disabled_resent = 'disabled';
                $email_date = '<p>&nbsp;</p>';
              }
              echo $email_date;
              ?>
              <a href="javascript:" class="report_ok_button_100 email_resend_dist2 <?php echo $disabled_resent; ?>" style="clear: both">Resend Email to Client</a>
              <div style="width: 100%; margin: 10px 0px;">Distribution Notes</div>
              <div style="width: 100%">
                <input type="radio" id="downloadpdf2" name="download2" class="download2" value="1"><label for="downloadpdf2">PDF</label>
                <input type="radio" id="downloadword2" name="download2" class="download2" value="2"><label for="downloadword2">Word</label>
              </div>
              <div style="width: 100%">
                <input type="checkbox" id="email_checkbox2" name="email_checkbox2" class="email_checkbox"><label for="email_checkbox2">Email</label>                                
              </div>
              <div style="width: 100%;">
                <a href="javascript:" class="report_ok_button_100 download_distribution" id="download_distribution2" style="clear: both">Download</a>
              </div>
            </td>
            <td colspan="3" align="center">
              <a href="javascript:" class="report_ok_button_100 email_unsent_dist3">Email Distribution</a>
              <?php 
              $check_last_email_sent = DB::table('year_client')->where('id',$year_end_id)->first();
              if($check_last_email_sent->dist3_email_sent_date != "0000-00-00 00:00:00")
              {
                $disabled_resent = '';
                $email_date = date('d F Y @ H : i', strtotime($check_last_email_sent->dist3_email_sent_date));
                $email_date = '<p>'.$email_date.'</p>';
              }
              else{
                $disabled_resent = 'disabled';
                $email_date = '<p>&nbsp;</p>';
              }
              echo $email_date;
              ?>
              <a href="javascript:" class="report_ok_button_100 email_resend_dist3 <?php echo $disabled_resent; ?>" style="clear: both">Resend Email to Client</a>
              <div style="width: 100%; margin: 10px 0px;">Distribution Notes</div>
              <div style="width: 100%">
                <input type="radio" id="downloadpdf3" name="download3" class="download3" value="1"><label for="downloadpdf3">PDF</label>
                <input type="radio" id="downloadword3" name="download3" class="download3" value="2"><label for="downloadword3">Word</label>
              </div>
              <div style="width: 100%">
                <input type="checkbox" id="email_checkbox3" name="email_checkbox3" class="email_checkbox"><label for="email_checkbox3">Email</label>                                
              </div>
              <div style="width: 100%;">
                <a href="javascript:" class="report_ok_button_100 download_distribution" id="download_distribution3" style="clear: both">Download</a>
              </div>
            </td>
          </tr>

        </table>
          <?php } } ?>
      </div>
    </div>

    <div style="display: none;">
      <?php
      if(count($year_details))
      {
        echo '<pre>';
        print_r(unserialize($year_details->setting_default));
      }
      ?>
    </div>

</div>
    <!-- End  -->

<div class="main-backdrop"><!-- --></div>




<div class="modal_load"></div>
<input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
<input type="hidden" name="show_alert" id="show_alert" value="">
<input type="hidden" name="pagination" id="pagination" value="1">
<input type="hidden" name="hidden_notes_year_id" id="hidden_notes_year_id" value="">
<input type="hidden" name="hidden_notes_setting_id" id="hidden_notes_setting_id" value="">
<input type="hidden" name="hidden_notes_type" id="hidden_notes_type" value="">
<input type="hidden" name="hidden_notes_distribution" id="hidden_notes_distribution" value="">


<script>

fileList = new Array();
Dropzone.options.imageUpload = {
    addRemoveLinks: true,
    maxFilesize:50,
    acceptedFiles: null,
    init: function() {
        this.on("success", function(file, response) {
            var obj = jQuery.parseJSON(response);
            file.serverId = obj.id; // Getting the new upload ID from the server via a JSON response
            file.previewElement.innerHTML = "<p>"+obj.filename+" <a href='javascript:' class='remove_dropzone_attach' data-element='"+obj.id+"'>Remove</a></p>";
        });
        this.on("removedfile", function(file) {
            if (!file.serverId) { return; }
            $.get("<?php echo URL::to('user/remove_property_images'); ?>"+"/"+file.serverId);
        });
    },
};

$(document).ready(function(){
  if($("#hide_na_docs").is(":checked"))
  {
    $(".setting_active:checked").parents("tr").hide();
  }
  $("#date_client_year_end").datetimepicker({
       format: 'L',
       format: 'DD-MMM-YYYY',
       useCurrent: false,
    });
	var setting_active = '';
	var distribution1_future = '';
	var distribution2_future = '';
	var distribution3_future = '';
	var yearend_id = "<?php echo $year_details->id; ?>";
  if($(".checkbox_disable").is(":checked"))
  {
    $('.table_tr').find("input").addClass("disabled");
    $('.table_tr').find("a").addClass("disabled");
    $('.table_tr').find(".fa-plus").addClass("disabled");
    $('.table_tr').find(".fa-minus-square").addClass("disabled");
    $('.table_tr').find(".fa-trash").addClass("disabled");
    $('.table_tr').find("label").addClass("disabled");
    $('.table_tr').find(".checkbox_disable").removeClass("disabled");
    $('.table_tr').find(".checkbox_disable_label").removeClass("disabled");
    $('.table_tr').find(".fileattachment").removeClass("disabled");
    
  }
  else{
    $('.table_tr').find("input").removeClass("disabled");
    $('.table_tr').find("a").removeClass("disabled");
    $('.table_tr').find(".fa-plus").removeClass("disabled");
    $('.table_tr').find(".fa-minus-square").removeClass("disabled");
    $('.table_tr').find(".fa-trash").removeClass("disabled");
    $('.table_tr').find("label").removeClass("disabled");
    $('.table_tr').find(".fileattachment").removeClass("disabled");
  }
	$(".setting_active").each(function(){
		if($(this).is(":checked"))
		{
			if(setting_active == "")
			{
				setting_active = "1";
			}
			else{
				setting_active = setting_active+',1';
			}
		}
		else{
			if(setting_active == "")
			{
				setting_active = "0";
			}
			else{
				setting_active = setting_active+',0';
			}
		}
	});

	$(".distribution1_future").each(function(){
		if($(this).is(":checked"))
		{
			if(distribution1_future == "")
			{
				distribution1_future = "1";
			}
			else{
				distribution1_future = distribution1_future+',1';
			}
		}
		else{
			if(distribution1_future == "")
			{
				distribution1_future = "0";
			}
			else{
				distribution1_future = distribution1_future+',0';
			}
		}
	});
	$(".distribution2_future").each(function(){
		if($(this).is(":checked"))
		{
			if(distribution2_future == "")
			{
				distribution2_future = "1";
			}
			else{
				distribution2_future = distribution2_future+',1';
			}
		}
		else{
			if(distribution2_future == "")
			{
				distribution2_future = "0";
			}
			else{
				distribution2_future = distribution2_future+',0';
			}
		}
	});
	$(".distribution3_future").each(function(){
		if($(this).is(":checked"))
		{
			if(distribution3_future == "")
			{
				distribution3_future = "1";
			}
			else{
				distribution3_future = distribution3_future+',1';
			}
		}
		else{
			if(distribution3_future == "")
			{
				distribution3_future = "0";
			}
			else{
				distribution3_future = distribution3_future+',0';
			}
		}
	});

	$.ajax({
		url:"<?php echo URL::to('user/distribution_future'); ?>",
		type:"post",
		data:{setting_active:setting_active,distribution1_future:distribution1_future,distribution2_future:distribution2_future,distribution3_future:distribution3_future,yearend_id:yearend_id}
	})
});
$(window).change(function(e) {
  if($(e.target).hasClass("select_template"))
  {
    var templateid = $(e.target).val();
    if(templateid != "")
    {
      $.ajax({
        url:"<?php echo URL::to('user/select_template'); ?>",
        type:"post",
        dataType:"json",
        data:{templateid:templateid},
        success: function(result)
        {
          $(".value_1_output").val(result['value_1']);
          $(".value_2_output").val(result['value_2']);
          $(".value_3_output").val(result['value_3']);
          $(".value_4_output").val(result['value_4']);
          $(".value_5_output").val(result['value_5']);
          $(".value_6_output").val(result['value_6']);

          $(".value_1_output").attr("placeholder",result['placeholder1']);
          $(".value_2_output").attr("placeholder",result['placeholder2']);
          $(".value_3_output").attr("placeholder",result['placeholder3']);
          $(".value_4_output").attr("placeholder",result['placeholder4']);
          $(".value_5_output").attr("placeholder",result['placeholder5']);
          $(".value_6_output").attr("placeholder",result['placeholder6']);
          $(".invoice_output").attr("placeholder",result['placeholder7']);

          $(".invoice_output").val(result['invoice']);

          $(".note_text_div").html(result['attachments']);
        }
      });
    }
    else{
      $(".value_1_output").val('');
      $(".value_2_output").val('');
      $(".value_3_output").val('');
      $(".value_4_output").val('');
      $(".value_5_output").val('');
      $(".value_6_output").val('');

      $(".invoice_output").val('');

      $(".note_text_div").html('');
    }
  }
});
$(window).click(function(e) {
if(e.target.id == "hide_na_docs")
{
  var yearend_id = "<?php echo $year_details->id; ?>";
  if($(e.target).is(":checked"))
  {
    var status = 1;
    $(".setting_active:checked").parents("tr").hide();
  }
  else{
    var status = 0;
    $(".setting_active:checked").parents("tr").show();
  }
  $.ajax({
    url:"<?php echo URL::to('user/update_na_status'); ?>",
    type:"post",
    data:{status:status,yearend_id:yearend_id},
    success: function(result)
    {

    }
  })
}
if($(e.target).hasClass('view_template'))
{
  $(".template_div").show();
  $(e.target).hide();
  $(".add_notes_yearend").show();
}
if($(e.target).hasClass('checkbox_disable'))
{
  if($(e.target).is(":checked"))
  {
      $.ajax({
        url:"<?php echo URL::to('user/make_client_disable'); ?>",
        type:"post",
        data:{status:1,year:"<?php echo $year_details->id; ?>"},
        success: function(result)
        {
          $('.table_tr').find("input").addClass("disabled");
          $('.table_tr').find("a").addClass("disabled");
          $('.table_tr').find(".fa-plus").addClass("disabled");
          $('.table_tr').find(".fa-minus-square").addClass("disabled");
          $('.table_tr').find(".fa-trash").addClass("disabled");
          $('.table_tr').find("label").addClass("disabled");
          $('.table_tr').find(".checkbox_disable").removeClass("disabled");
          $('.table_tr').find(".checkbox_disable_label").removeClass("disabled");
          $('.table_tr').find(".fileattachment").removeClass("disabled");
          alert("Yearend Manager for this client is marked complete");
        }
      });
  }
  else{
      $.ajax({
        url:"<?php echo URL::to('user/make_client_disable'); ?>",
        type:"post",
        data:{status:0,year:"<?php echo $year_details->id; ?>"},
        success: function(result)
        {
          $('.table_tr').find("input").removeClass("disabled");
          $('.table_tr').find("a").removeClass("disabled");
          $('.table_tr').find(".fa-plus").removeClass("disabled");
          $('.table_tr').find(".fa-minus-square").removeClass("disabled");
          $('.table_tr').find(".fa-trash").removeClass("disabled");
          $('.table_tr').find("label").removeClass("disabled");
          $('.table_tr').find(".fileattachment").removeClass("disabled");
          alert("Yearend Manager for this client is marked incomplete");
        }
      });
  }
}
if($(e.target).hasClass('setting_class')){
    $(".setting_crypt_modal").modal("show");
    $(".crypt_pin_setting").prop('required', true);
    $(".crypt_pin_setting").prop('disabled', false);
    $(".crypt_pin_setting").val('');
    $(".crypt_error").hide();   
    $(".setting_drop").hide();
    $(".setting_button").hide();
    $(".crypt_button_setting").show();

}
if($(e.target).hasClass('setting_button')){
  var setting_type = $(".setting_type").val();
  if(setting_type == "" || typeof setting_type === "undefined")
  {
    alert("Please select type");
    return false;
  }
}
if($(e.target).hasClass("check_all_setting"))
{
  var id = $(e.target).val();
  if($(e.target).is(":checked"))
  {
    $(".attachments_setting_"+id).prop("checked",true);
  }
  else{
    $(".attachments_setting_"+id).prop("checked",false);
  }
}
if(e.target.id == "check_all_closing_note")
{
  if($(e.target).is(":checked"))
  {
    $(".check_all_closing_note").prop("checked",true);
  }
  else{
    $(".check_all_closing_note").prop("checked",false);
  }
}
if(e.target.id == "check_all_fee_note")
{
  if($(e.target).is(":checked"))
  {
    $(".check_all_fee_note").prop("checked",true);
  }
  else{
    $(".check_all_fee_note").prop("checked",false);
  }
}
if(e.target.id == "check_all_signature")
{
  if($(e.target).is(":checked"))
  {
    $(".check_all_signature").prop("checked",true);
  }
  else{
    $(".check_all_signature").prop("checked",false);
  }
}

if($(e.target).hasClass('email_unsent_dist1'))
{
  var yearid = "<?php echo $year_end_id; ?>";
  var type = "unsent";
  $.ajax({
    url:'<?php echo URL::to('user/edit_yearend_email_unsent_files'); ?>',
    type:'get',
    data:{yearid:yearid,distribution:"1",type:type},
    dataType:"json",
    success: function(result)
    {
      if (CKEDITOR.instances.editor) CKEDITOR.instances.editor.destroy();
      CKEDITOR.replace('editor',
     {
      height: '150px',
     });
        CKEDITOR.instances['editor'].setData(result['html']);
        $("#email_attachments").html(result['files']);
        $(".subject_unsent").val(result['subject']);
        $("#to_user").val(result['to']);
        $("#email_sent_option").val("1");
        $(".emailunsent").modal('show');
    }
  })
}

if($(e.target).hasClass('email_unsent_dist2'))
{
  var yearid = "<?php echo $year_end_id; ?>";
  var type = "unsent";
  $.ajax({
    url:'<?php echo URL::to('user/edit_yearend_email_unsent_files'); ?>',
    type:'get',
    data:{yearid:yearid,distribution:"2",type:type},
    dataType:"json",
    success: function(result)
    {
      if (CKEDITOR.instances.editor) CKEDITOR.instances.editor.destroy();
      CKEDITOR.replace('editor',
     {
      height: '150px',
     });
        CKEDITOR.instances['editor'].setData(result['html']);
        $("#email_attachments").html(result['files']);
        $(".subject_unsent").val(result['subject']);
        $("#to_user").val(result['to']);
        $("#email_sent_option").val("2");
        $(".emailunsent").modal('show');
    }
  })
}

if($(e.target).hasClass('email_unsent_dist3'))
{
  var yearid = "<?php echo $year_end_id; ?>";
  var type = "unsent";
  $.ajax({
    url:'<?php echo URL::to('user/edit_yearend_email_unsent_files'); ?>',
    type:'get',
    data:{yearid:yearid,distribution:"3",type:type},
    dataType:"json",
    success: function(result)
    {
      if (CKEDITOR.instances.editor) CKEDITOR.instances.editor.destroy();
      CKEDITOR.replace('editor',
     {
      height: '150px',
     });
        CKEDITOR.instances['editor'].setData(result['html']);
        $("#email_attachments").html(result['files']);
        $(".subject_unsent").val(result['subject']);
        $("#to_user").val(result['to']);
        $("#email_sent_option").val("3");
        $(".emailunsent").modal('show');
    }
  })
}

if($(e.target).hasClass('email_resend_dist1'))
{
  var yearid = "<?php echo $year_end_id; ?>";
  var type = "resend";
  $.ajax({
    url:'<?php echo URL::to('user/edit_yearend_email_unsent_files'); ?>',
    type:'get',
    data:{yearid:yearid,distribution:"1",type:type},
    dataType:"json",
    success: function(result)
    {
      if (CKEDITOR.instances.editor_9) CKEDITOR.instances.editor_9.destroy();
      CKEDITOR.replace('editor_9',
     {
      height: '150px',
     });
        CKEDITOR.instances['editor_9'].setData(result['html']);
        $("#email_attachmentsresend").html(result['files']);
        $(".subject_resend").val(result['subject']);
        $("#to_userresend").val(result['to']);
        $("#email_resent_option").val("1");
        $(".resendemailunsent").modal('show');
    }
  })
}

if($(e.target).hasClass('email_resend_dist2'))
{
  var yearid = "<?php echo $year_end_id; ?>";
  var type = "resend";
  $.ajax({
    url:'<?php echo URL::to('user/edit_yearend_email_unsent_files'); ?>',
    type:'get',
    data:{yearid:yearid,distribution:"2",type:type},
    dataType:"json",
    success: function(result)
    {
      if (CKEDITOR.instances.editor_9) CKEDITOR.instances.editor_9.destroy();
      CKEDITOR.replace('editor_9',
     {
      height: '150px',
     });
        CKEDITOR.instances['editor_9'].setData(result['html']);
        $("#email_attachmentsresend").html(result['files']);
        $(".subject_resend").val(result['subject']);
        $("#to_userresend").val(result['to']);
        $("#email_resent_option").val("2");
        $(".resendemailunsent").modal('show');
    }
  })
}

if($(e.target).hasClass('email_resend_dist3'))
{
  var yearid = "<?php echo $year_end_id; ?>";
  var type = "resend";
  $.ajax({
    url:'<?php echo URL::to('user/edit_yearend_email_unsent_files'); ?>',
    type:'get',
    data:{yearid:yearid,distribution:"3",type:type},
    dataType:"json",
    success: function(result)
    {
      if (CKEDITOR.instances.editor_9) CKEDITOR.instances.editor_9.destroy();
      CKEDITOR.replace('editor_9',
     {
      height: '150px',
     });
        CKEDITOR.instances['editor_9'].setData(result['html']);
        $("#email_attachmentsresend").html(result['files']);
        $(".subject_resend").val(result['subject']);
        $("#to_userresend").val(result['to']);
        $("#email_resent_option").val("3");
        $(".resendemailunsent").modal('show');
    }
  })
}
if(e.target.id == 'download_distribution1')
{
  var count = $(".download:checked").length;
  if(count > 0)
  {
    var type = $(".download:checked").val();
    var yearselected = "<?php echo $year_end_id; ?>";
    if($("#email_checkbox1").is(":checked"))
    {
      var email = 1;
    }
    else{
      var email = 2;
    }
    $.ajax({
      url:"<?php echo URL::to('user/download_email_format'); ?>",
      type:"post",
      data:{type:type,email:email,distribution:"1",yearselected:yearselected},
      success: function(result)
      {
        if(type == 1)
        {
          SaveToDisk("<?php echo URL::to('job_file/Distribution_Email_Format.pdf'); ?>",result);
        }
        else{
          SaveToDisk("<?php echo URL::to('job_file/Distribution_Email_Format.doc'); ?>",result);
        }
      }
    });
  }
  else{
    alert("Please Select the Download Type either pdf or word document to download");
  }
}
if(e.target.id == 'download_distribution2')
{
  var count = $(".download2:checked").length;
  if(count > 0)
  {
    var type = $(".download2:checked").val();
    var yearselected = "<?php echo $year_end_id; ?>";
    if($("#email_checkbox2").is(":checked"))
    {
      var email = 1;
    }
    else{
      var email = 2;
    }
    $.ajax({
      url:"<?php echo URL::to('user/download_email_format'); ?>",
      type:"post",
      data:{type:type,email:email,distribution:"2",yearselected:yearselected},
      success: function(result)
      {
        if(type == 1)
        {
          SaveToDisk("<?php echo URL::to('job_file/Distribution_Email_Format.pdf'); ?>",result);
        }
        else{
          SaveToDisk("<?php echo URL::to('job_file/Distribution_Email_Format.doc'); ?>",result);
        }
      }
    });
  }
  else{
    alert("Please Select the Download Type either pdf or word document to download");
  }
}
if(e.target.id == 'download_distribution3')
{
  var count = $(".download3:checked").length;
  if(count > 0)
  {
    var type = $(".download3:checked").val();
    var yearselected = "<?php echo $year_end_id; ?>";
    if($("#email_checkbox3").is(":checked"))
    {
      var email = 1;
    }
    else{
      var email = 2;
    }
    $.ajax({
      url:"<?php echo URL::to('user/download_email_format'); ?>",
      type:"post",
      data:{type:type,email:email,distribution:"3",yearselected:yearselected},
      success: function(result)
      {
        if(type == 1)
        {
          SaveToDisk("<?php echo URL::to('job_file/Distribution_Email_Format.pdf'); ?>",result);
        }
        else{
          SaveToDisk("<?php echo URL::to('job_file/Distribution_Email_Format.doc'); ?>",result);
        }
      }
    });
  }
  else{
    alert("Please Select the Download Type either pdf or word document to download");
  }
}
if($(e.target).hasClass("fileattachment_note"))
{
  var element = $(e.target).attr("data-element");
  $.ajax({
    url:"<?php echo URL::to('user/download_supplementary_note'); ?>",
    type:"get",
    data:{id:element},
    success: function(result)
    {
      SaveToDisk("<?php echo URL::to('uploads/supplementary_text_file.txt'); ?>",'supplementary_text_file.txt');
    }
  })
}
if($(e.target).hasClass("add_notes_yearend_button"))
{
  var count = $(".check_note:checked").length;
  if(count > 0)
  {
    var year_id = $("#hidden_notes_year_id").val();
    var setting_id = $("#hidden_notes_setting_id").val();
    var type = $("#hidden_notes_type").val();
    var distribution = $("#hidden_notes_distribution").val();

    var noteidd = '';
    var textvall = '';
    $(".check_note:checked").each(function() {
      var noteid = $(this).val();
      var textval = $(".notetxt_"+noteid).text();

      if(noteidd == "")
      {
        noteidd = noteid;
      }
      else{
        noteidd = noteidd+','+noteid;
      }

      if(textvall == "")
      {
        textvall = textval;
      }
      else{
        textvall = textvall+'||'+textval;
      }
    });

    $.ajax({
      url:"<?php echo URL::to('user/insert_notes_yearend'); ?>",
      type:"post",
      data:{noteid:noteidd,textval:textvall,year_id:year_id,setting_id:setting_id,type:type,distribution:distribution},
      success: function(result)
      {
        window.location.reload();
      }
    });
  }
  else{
    alert("Please select the supplementary note to attach the file.");
  }
}
if($(e.target).hasClass('call_notes_modal'))
{
  $("#supplementary_notes_modal").modal("show");
  $(".value_1_output").val('');
  $(".value_2_output").val('');
  $(".value_3_output").val('');
  $(".value_4_output").val('');
  $(".value_5_output").val('');
  $(".value_6_output").val('');
  $(".invoice_output").val('');
  $(".note_text_div").html('');
  $(".select_template").val('');

  $(".template_div").hide();
  $(".view_template").show();
  $(".add_notes_yearend").hide();


  var year_id = $(e.target).attr("data-element");
  var setting_id = $(e.target).attr("data-value");
  var type = $(e.target).attr("data-type");
  var distribution = $(e.target).attr("data-distribution");

  $("#hidden_notes_year_id").val(year_id);
  $("#hidden_notes_setting_id").val(setting_id);
  $("#hidden_notes_distribution").val(distribution);
  $("#hidden_notes_type").val(type);

  // $.ajax({
  //   url:"<?php echo URL::to('user/check_already_attached'); ?>",
  //   type:"get",
  //   data:{year_id:year_id,setting_id:setting_id,type:type,distribution:distribution},
  //   success: function(result)
  //   {
  //     $("#supplementary_notes_modal").modal("show");
  //     $(".supplementary_notes_modal_body").html(result);

  //     $("#hidden_notes_year_id").val(year_id);
  //     $("#hidden_notes_setting_id").val(setting_id);
  //     $("#hidden_notes_distribution").val(distribution);
  //     $("#hidden_notes_type").val(type);
  //   }
  // })
}
if($(e.target).hasClass('setting_active'))
{
  
    if($(e.target).is(":checked"))
    {
      var findattachments = $(e.target).parents("tr").find(".fileattachment").length;
      if(findattachments > 0)
      {
        var r = confirm("These Documents Will be deleted do you want to continue?");
        if(!r)
        {
          $(e.target).prop("checked",false);
          return false;
        }
        $(e.target).parents("tr").find(".notdisabled_hidden").removeClass("notdisabled_hidden").addClass("disabled_hidden");
      }
      else{
        $(e.target).parents("tr").find(".notdisabled_hidden").removeClass("notdisabled_hidden").addClass("disabled_hidden");
      }
    }
    else{
      $(e.target).parents("tr").find(".disabled_hidden").removeClass("disabled_hidden").addClass("notdisabled_hidden");
    }
    var setting_active = '';
    var yearend_id = "<?php echo $year_details->id; ?>";
    $(".setting_active").each(function(){
      if($(this).is(":checked"))
      {
        if(setting_active == "")
        {
          setting_active = "1";
        }
        else{
          setting_active = setting_active+',1';
        }
      }
      else{
        if(setting_active == "")
        {
          setting_active = "0";
        }
        else{
          setting_active = setting_active+',0';
        }
      }
    });
    $.ajax({
      url:"<?php echo URL::to('user/setting_active_update'); ?>",
      type:"post",
      data:{setting_active:setting_active,yearend_id:yearend_id}
    });
}
if($(e.target).hasClass('distribution1_future'))
{
	if($(e.target).is(":checked"))
	{
		$(e.target).parents("tr").find(".dist1_showtd").removeClass("dist1_showtd").addClass("dist1_hiddentd");
		
	}
	else{
		$(e.target).parents("tr").find(".dist1_hiddentd").removeClass("dist1_hiddentd").addClass("dist1_showtd");
	}
	var distribution1_future = '';
	var yearend_id = "<?php echo $year_details->id; ?>";
	$(".distribution1_future").each(function(){
		if($(this).is(":checked"))
		{
			if(distribution1_future == "")
			{
				distribution1_future = "1";
			}
			else{
				distribution1_future = distribution1_future+',1';
			}
		}
		else{
			if(distribution1_future == "")
			{
				distribution1_future = "0";
			}
			else{
				distribution1_future = distribution1_future+',0';
			}
		}
	});
	$.ajax({
		url:"<?php echo URL::to('user/distribution1_future'); ?>",
		type:"post",
		data:{distribution1_future:distribution1_future,yearend_id:yearend_id}
	});
}
if($(e.target).hasClass('distribution2_future'))
{
	if($(e.target).is(":checked"))
	{
		$(e.target).parents("tr").find(".dist2_showtd").removeClass("dist2_showtd").addClass("dist2_hiddentd");
		
	}
	else{
		$(e.target).parents("tr").find(".dist2_hiddentd").removeClass("dist2_hiddentd").addClass("dist2_showtd");
	}
	var distribution2_future = '';
	var yearend_id = "<?php echo $year_details->id; ?>";
	$(".distribution2_future").each(function(){
		if($(this).is(":checked"))
		{
			if(distribution2_future == "")
			{
				distribution2_future = "1";
			}
			else{
				distribution2_future = distribution2_future+',1';
			}
		}
		else{
			if(distribution2_future == "")
			{
				distribution2_future = "0";
			}
			else{
				distribution2_future = distribution2_future+',0';
			}
		}
	});
	$.ajax({
		url:"<?php echo URL::to('user/distribution2_future'); ?>",
		type:"post",
		data:{distribution2_future:distribution2_future,yearend_id:yearend_id}
	});
}
if($(e.target).hasClass('distribution3_future'))
{
	if($(e.target).is(":checked"))
	{
		$(e.target).parents("tr").find(".dist3_showtd").removeClass("dist3_showtd").addClass("dist3_hiddentd");
		
	}
	else{
		$(e.target).parents("tr").find(".dist3_hiddentd").removeClass("dist3_hiddentd").addClass("dist3_showtd");
	}
	var distribution3_future = '';
	var yearend_id = "<?php echo $year_details->id; ?>";
	$(".distribution3_future").each(function(){
		if($(this).is(":checked"))
		{
			if(distribution3_future == "")
			{
				distribution3_future = "1";
			}
			else{
				distribution3_future = distribution3_future+',1';
			}
		}
		else{
			if(distribution3_future == "")
			{
				distribution3_future = "0";
			}
			else{
				distribution3_future = distribution3_future+',0';
			}
		}
	});
	$.ajax({
		url:"<?php echo URL::to('user/distribution3_future'); ?>",
		type:"post",
		data:{distribution3_future:distribution3_future,yearend_id:yearend_id}
	});
}
if($(e.target).hasClass('image_submit'))
{
	var files = $(e.target).parent().find('.image_file').val();
	if(files == '' || typeof files === 'undefines')
	{
	  $(e.target).parent().find(".error_files").text("Please Choose the files to proceed");
	  return false;
	}
	else{
	  $(e.target).parents('td').find('.img_div').toggle();
	}
}
else{
	$(".img_div").each(function() {
	  $(this).hide();
	});
}
if($(e.target).hasClass('fa-plus'))
{
	var pos = $(e.target).position();
	var leftposi = parseInt(pos.left) - 200;
	$(e.target).parents("td").find('.img_div').css({"position":"absolute","top":pos.top,"left":leftposi}).toggle();
	$(".dz-message").find("span").html("Click here to BROWSE the files <br/>OR just drop files here to upload");
}

if($(e.target).hasClass('image_file'))
{
	$(e.target).parents('td').find('.img_div').toggle();
	$(e.target).parents('.modal-body').find('.img_div').toggle();
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
if($(e.target).hasClass('remove_dropzone_attach'))
{
	var attachment_id = $(e.target).attr("data-element");
	$.ajax({
	  url:"<?php echo URL::to('user/remove_yearend_dropzone_attachment'); ?>",
	  type:"post",
	  data:{attachment_id:attachment_id},
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
if($(e.target).parent().hasClass("dz-message"))
{
	$(e.target).parents('td').find('.img_div').show();
	$(e.target).parents('.modal-body').find('.img_div').show();    
}
if($(e.target).hasClass('add_new')){
    $(".add_modal").modal("show");
    $(".year_input_group").hide();
    $(".year_drop").prop('required', false);
    $(".crypt_pin").prop('required', true);
    $(".crypt_pin").prop('disabled', false);
    $(".crypt_pin").val('');
    $(".crypt_error").hide();
}
if($(e.target).hasClass('crypt_button')){
  var crypt = $(".crypt_pin").val();
  if(crypt == "" || typeof crypt === "undefined")
  {
    alert("Please Enter CRYPT PIN");
    return false;
  }
  else{
    $.ajax({
      url:"<?php echo URL::to('user/yearend_crypt_validdation')?>",
      type:"post",
      dataType:"json",
      data:{crypt:crypt,type:0},
      success:function(result){
        if(result['security'] == true){
          $(".crypt_error_first").css({"display":"block","color":"green"});
          $(".crypt_error_first").html('CRYPT Pin validation success');
          $(".crypt_groyp").show();
          $(".year_input_group").show();
          $(".year_drop").html(result['drop']);
          $(".crypt_button").hide();
          $(".year_button").show();
          $(".year_class").prop('required', true);
          $(".crypt_pin").prop('required', false);
          $(".crypt_pin").prop('disabled', true);
          $(".button_submit").html(result['create_button']);
        }
        else{
          $(".crypt_error_first").css({"display":"block","color":"red"});
          $(".crypt_error_first").html('CRYPT Pin is incorrect');
          $(".crypt_groyp").show();
          $(".year_input_group").hide();
          $(".crypt_button").show();
          $(".year_button").hide();
        }

      }
    })
  } 
}

if($(e.target).hasClass('crypt_button_setting')){
  var crypt = $(".crypt_pin_setting").val();
  if(crypt == "" || typeof crypt === "undefined")
  {
    alert("Please Enter CRYPT PIN");
    return false;
  }
  else{
    $.ajax({
      url:"<?php echo URL::to('user/yearend_crypt_validdation')?>",
      type:"post",
      dataType:"json",
      data:{crypt:crypt,type:1},
      success:function(result){
        if(result['security'] == true){
          $(".crypt_error").css({"display":"block","color":"green"});
          $(".crypt_error").html('CRYPT Pin validation success');          
          $(".setting_drop").show();
          $(".setting_drop").html(result['drop']);          
          $(".crypt_pin_setting").prop('required', false);
          $(".crypt_pin_setting").prop('disabled', true);
          $(".setting_submit").html(result['create_button']);
          $(".crypt_button_setting").hide();
        }
        else{
          $(".crypt_error").css({"display":"block","color":"red"});
          $(".crypt_error").html('CRYPT Pin is incorrect');
          
          $(".year_input_group").hide();
          $(".crypt_button").show();
          $(".year_button").hide();
        }

      }
    })
  } 
}

// if($(e.target).hasClass('setting_class')){
//     $(".setting_crypt_modal").modal("show");
//     $(".crypt_pin_setting").prop('required', true);
//     $(".crypt_pin_setting").prop('disabled', false);
//     $(".crypt_pin_setting").val('');
//     $(".crypt_error").hide();    
// }


// if($(e.target).hasClass('setting_button')){
//   var setting_type = $(".setting_type").val();
//   if(setting_type == "" || typeof setting_type === "undefined")
//   {
//     alert("Please select type");
//     return false;
//   }
// }



if($(e.target).hasClass('year_button')){
  var year_class = $(".year_class").val();

  if(year_class == "" || typeof year_class === "undefined")
  {
    alert("Please select year");
    return false;
  }
  else{
    var r = confirm("Warning, once you create this year no year prior to this can be created.  Do you wish to Proceed with Creating the year?");
    if (r == true) {      

    } else {
        return false;
    }
  }
}

if($(e.target).hasClass('trash_image'))
{
	var r = confirm("Are You sure you want to delete this image");
	if (r == true) {
	  var imgid = $(e.target).attr('data-element');
	  $.ajax({
	      url:"<?php echo URL::to('user/yearend_delete_image'); ?>",
	      type:"post",
	      data:{imgid:imgid},
	      success: function(result) {
	        window.location.reload();
	      }
	  });
	}
}
if($(e.target).hasClass('trash_notes'))
{
  var r = confirm("Are You sure you want to delete this Note");
  if (r == true) {
    var imgid = $(e.target).attr('data-element');
    $.ajax({
        url:"<?php echo URL::to('user/yearend_delete_note'); ?>",
        type:"post",
        data:{imgid:imgid},
        success: function(result) {
          window.location.reload();
        }
    });
  }
}

if($(e.target).hasClass('fadeleteall_attachments'))
{
	var r = confirm("Are You sure you want to delete all the attachments?");
	if (r == true) {
	  var clientid = $(e.target).attr('data-element');
	  var settingid = $(e.target).attr('data-value');
	  var distribution = $(e.target).attr('data-distribution');
	  var type = $(e.target).attr('data-type');
	  $.ajax({
	      url:"<?php echo URL::to('user/yearend_delete_all_image'); ?>",
	      type:"post",
	      data:{clientid:clientid,settingid:settingid,distribution:distribution,type:type},
	      success: function(result) {
	        window.location.reload();
	      }
	  });
	}
}
if($(e.target).hasClass('fadeleteall_attachments_note'))
{
  var r = confirm("Are You sure you want to delete all the attachments?");
  if (r == true) {
    var clientid = $(e.target).attr('data-element');
    var settingid = $(e.target).attr('data-value');
    var type = $(e.target).attr('data-type');
    var distribution = $(e.target).attr('data-distribution');
    $.ajax({
        url:"<?php echo URL::to('user/yearend_delete_all_note'); ?>",
        type:"post",
        data:{clientid:clientid,settingid:settingid,type:type,distribution:distribution},
        success: function(result) {
          window.location.reload();
        }
    });
  }
}
if($(e.target).hasClass('fileattachment'))
{
    e.preventDefault();
    var element = $(e.target).attr('data-element');
    $('body').addClass('loading');
    setTimeout(function(){
      SaveToDisk(element,element.split('/').reverse()[0]);
      $('body').removeClass('loading');
      }, 3000);
    return false; 
}
});
$(window).change(function(e){
  if($(e.target).hasClass('setting_type')){
    var level = $(e.target).val();  
    if (level == 1) {     
      $(".setting_button").attr("href", "<?php echo URL::to('user/supplementary_manager')?>");
    }

    else if (level == 2) {     
      $(".setting_button").attr("href", "<?php echo URL::to('user/yearend_setting')?>");
    }
    else{
      $(".setting_button").attr("href", "#");
    }
  }
});
$(".add_modal").keypress(function(e) {
  if (e.which == 13 && !$(e.target).is("textarea")) {
    return false;
  }
});

$(".setting_crypt_modal").keypress(function(e) {
  if (e.which == 13 && !$(e.target).is("textarea")) {
    return false;
  }
});


var valueTimmer;                //timer identifier
var valueInterval = 500;  //time in ms, 5 second for example
var $valueinput1 = $('.dist_email1');
//on keyup, start the countdown
$valueinput1.on('keyup', function () {
  var input_val = $(this).val();
  var id = $(".id_class").val();
  clearTimeout(valueTimmer);
  valueTimmer = setTimeout(doneTyping, valueInterval,input_val,id);
});
//on keydown, clear the countdown 
$valueinput1.on('keydown', function () {
  clearTimeout(valueTimmer);
});
//user is "finished typing," do something
function doneTyping (valueinput1,id) {
  $.ajax({
        url:"<?php echo URL::to('user/dist_emailupdate')?>",
        type:"post",
        data:{value:valueinput1,id:id,number:1},
        success: function(result) {
          
        }
      });
}
//Distribution 2 Start
var $valueinput2 = $('.dist_email2');
$valueinput2.on('keyup', function () {
  var input_val = $(this).val();
  var id = $(".id_class").val();
  clearTimeout(valueTimmer);
  valueTimmer = setTimeout(doneTyping2, valueInterval,input_val,id);
});
$valueinput2.on('keydown', function () {
  clearTimeout(valueTimmer);
});
function doneTyping2 (valueinput2,id) {
  $.ajax({
        url:"<?php echo URL::to('user/dist_emailupdate')?>",
        type:"post",
        data:{value:valueinput2,id:id,number:2},
        success: function(result) {
          
        }
      });
}
//Distribution 3 Start
var $valueinput3 = $('.dist_email3');
$valueinput3.on('keyup', function () {
  var input_val = $(this).val();
  var id = $(".id_class").val();
  clearTimeout(valueTimmer);
  valueTimmer = setTimeout(doneTyping3, valueInterval,input_val,id);
});
$valueinput3.on('keydown', function () {
  clearTimeout(valueTimmer);
});
function doneTyping3 (valueinput3,id) {
  $.ajax({
        url:"<?php echo URL::to('user/dist_emailupdate')?>",
        type:"post",
        data:{value:valueinput3,id:id,number:3},
        success: function(result) {
          
        }
      });
}


$.ajaxSetup({
    async: true
});
function ajax_response(e){
  var valueinput1 = $(".value_1_output").val();
  var valueinput2 = $(".value_2_output").val();
  var valueinput3 = $(".value_3_output").val();
  var valueinput4 = $(".value_4_output").val();
  var valueinput5 = $(".value_5_output").val();
  var valueinput6 = $(".value_6_output").val();
  var valueinvoice = $(".invoice_output").val();

  $(".classval").each(function() {
    var idval = $(this).attr("id");

    if(idval == "value1") { $(this).html(valueinput1); }
    if(idval == "value2") { $(this).html(valueinput2); }
    if(idval == "value3") { $(this).html(valueinput3); }
    if(idval == "value4") { $(this).html(valueinput4); }
    if(idval == "value5") { $(this).html(valueinput5); }
    if(idval == "value6") { $(this).html(valueinput6); }

    if(idval == "invoice") { $(this).html(valueinvoice); }
  });

  var date_client_year_end = $(".date_client_year_end").val();
    $.ajax({
      url:"<?php echo URL::to('user/set_client_year_end_date'); ?>",
      type:"post",
      data:{yearid:"<?php echo $year_details->id; ?>",date:date_client_year_end},
      success: function(result)
      {

      }
    });
  setTimeout(ajax_response,1000);
}
setTimeout(ajax_response,1000);

</script>


<script>
$(window).keyup(function(e) {
    var valueTimmer;                //timer identifier
    var valueInterval = 500;  //time in ms, 5 second for example
    var $liability1_value = $('.liability_class_1');
    var $liability2_value = $('.liability_class_2');
    var $liability3_value = $('.liability_class_3');
    if($(e.target).hasClass('liability_class_1'))
    {        
        var that = $(e.target);
        var input_val = $(e.target).val();
        var setting_id = $(e.target).attr("data-element");
        var row_id  = $(".row_id").val();
        var client_id  = $(".client_id").val();
        var year_id  = $(".year_id").val();
        clearTimeout(valueTimmer);
        valueTimmer = setTimeout(doneTyping_liability1, valueInterval,input_val, row_id, client_id, year_id, setting_id, that);   
    }

    if($(e.target).hasClass('liability_class_2'))
    {        
        var that = $(e.target);
        var input_val = $(e.target).val();
        var setting_id = $(e.target).attr("data-element");
        var row_id  = $(".row_id").val();
        var client_id  = $(".client_id").val();
        var year_id  = $(".year_id").val();
        clearTimeout(valueTimmer);
        valueTimmer = setTimeout(doneTyping_liability2, valueInterval,input_val, row_id, client_id, year_id, setting_id, that);   
    }

    if($(e.target).hasClass('liability_class_3'))
    {        
        var that = $(e.target);
        var input_val = $(e.target).val();
        var setting_id = $(e.target).attr("data-element");
        var row_id  = $(".row_id").val();
        var client_id  = $(".client_id").val();
        var year_id  = $(".year_id").val();
        clearTimeout(valueTimmer);
        valueTimmer = setTimeout(doneTyping_liability3, valueInterval,input_val, row_id, client_id, year_id, setting_id, that);   
    }
});

function doneTyping_liability1 (liability1_value, row_id, client_id, year_id, setting_id, that) {
  $.ajax({
        url:"<?php echo URL::to('user/yearend_liability_update')?>",
        type:"post",
        data:{setting_id:setting_id, value:liability1_value, year_id:year_id, row_id:row_id, client_id:client_id, type:1},
        success: function(result) { 
          //that.val(result);
        } 
  });            
}

function doneTyping_liability2 (liability2_value, row_id, client_id, year_id, setting_id, that) {
  $.ajax({
        url:"<?php echo URL::to('user/yearend_liability_update')?>",
        type:"post",
        data:{setting_id:setting_id, value:liability2_value, year_id:year_id, row_id:row_id, client_id:client_id, type:2},
        success: function(result) { 
          //that.val(result);
        } 
  });            
}

function doneTyping_liability3 (liability3_value, row_id, client_id, year_id, setting_id, that) {
  $.ajax({
        url:"<?php echo URL::to('user/yearend_liability_update')?>",
        type:"post",
        data:{setting_id:setting_id, value:liability3_value, year_id:year_id, row_id:row_id, client_id:client_id, type:3},
        success: function(result) { 
          //that.val(result);
        } 
  });            
}
</script>




@stop
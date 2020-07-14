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



<div class="modal fade" id="supplementary_notes_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <form> 
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Choose Notes</h4>
          </div>
          <div class="modal-body supplementary_notes_modal_body">

          </div>
          <div class="modal-footer">
              <input type="button" class="common_black_button add_notes_yearend" value="Submit">
          </div>
        </form>
      </div>
  </div>
</div>




<div class="content_section" style="margin-bottom:200px">
  <div class="page_title" style="margin-bottom: 2px;">
          <div class="col-lg-3" style="padding: 0px;">
                Client Year End Manager
            </div>
            <div class="col-lg-5 text-right" style="padding-right: 0px; line-height: 35px;">
                
            </div>
            <div class="col-lg-4 text-right"  style="padding: 0px;" >
                                    
  </div>

  <div style="clear: both;">
   <?php
    if(Session::has('message')) { ?>
        <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>

    
    <?php } ?>
    </div> 


</div>

<div class="table-responsive">

  <div class="col-lg-12" style="font-size: 15px; text-align: center; margin-bottom: 20px;"><b><?php echo $client_details->client_id.'&nbsp;&nbsp;&nbsp;'.$client_details->company?></b></div>
  <input type="hidden" class="id_class" value="<?php echo $year_details->id?>" name="">
  <div class="col-lg-6" style="font-size: 15px; line-height: 25px;">
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
  </div>
  <div class="col-lg-6" style="font-size: 15px; line-height: 25px;">
      <div class="col-lg-3">Email:</div>
      <div class="col-lg-9"><?php echo $client_details->email?></div>
    </div>

    <div class="col-lg-12">
      <div style="width: 100%; height:auto; margin: 0px auto; margin-top: 75px;">
        <?php 
        $year_setting_details = DB::table('year_setting')->count();
        if($year_setting_details != 0 ){
        if($year_details->setting_id != ''){ ?> 
        <table class="table">
          <tr style="font-weight: bold;">
            <td></td>
            <td></td>
            <td style="background:#e2efd9" align="center">Supplementary</td>
            <td style="background:#deebf6" colspan="2" align="center">Distribution 1</td>
            <td style="background:#fff3cb" colspan="2" align="center">Distribution 2</td>
            <td style="background:#ededed" colspan="2" align="center">Distrbution 3 </td>
          </tr>
          <tr style="font-weight: bold;">
            <td></td>
            <td></td>
            <td style="background:#e2efd9"></td>
            <td style="background:#deebf6" colspan="2" align="center"><span style="float: left; width: 30%; line-height: 30px;">Dist Email:</span><span style="float: left; width: 70%"><input type="text" placeholder="Enter Email Id" value="<?php echo $year_details->distribution1_email?>" class="form-control dist_email1"></span></td>
            <td style="background:#fff3cb" colspan="2" align="center"><span style="float: left; width: 30%; line-height: 30px;">Dist Email:</span><span style="float: left; width: 70%"><input type="text"  placeholder="Enter Email Id" value="<?php echo $year_details->distribution2_email?>" class="form-control dist_email2"></span></td>
            <td style="background:#ededed" colspan="2" align="center"><span style="float: left; width: 30%; line-height: 30px;">Dist Email:</span><span style="float: left; width: 70%"><input type="text"  placeholder="Enter Email Id" value="<?php echo $year_details->distribution3_email?>" class="form-control dist_email3"></span></td>
          </tr>
          <tr style="font-weight: bold;">
            <td>N/A</td>
            <td>Document</td>
            <td style="background:#e2efd9">Notes</td>
            <td style="background:#deebf6">Attachments</td>
            <td style="background:#deebf6" width="120px">Future</td>
            <td style="background:#fff3cb">Attachments</td>
            <td style="background:#fff3cb" width="120px">Future</td>
            <td style="background:#ededed">Attachments</td>
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
          }

          $active_merge = array_combine($setting_detail,$active_detail);

          $distribution1_merge = array_combine($setting_detail,$distribution1_future);
          $distribution2_merge = array_combine($setting_detail,$distribution2_future);
          $distribution3_merge = array_combine($setting_detail,$distribution3_future);

          if(count($setting_detail)){
            foreach ($setting_detail as $single) {
              $settingname = DB::table('year_setting')->where('id', $single)->first();

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
            <tr>
              <td><input type="checkbox" value="1" <?php if($active_merge[$single] == 1){echo 'checked';} else{ echo '';}  ?> name="setting_active" class="setting_active"><label>&nbsp;</label></td>
              <td><?php echo $settingname->document?></td>
              <td style="background:#e2efd9">
              	<?php if($active_merge[$single] == 1) { $disabled ='disabled'; } else { $disabled ='notdisabled'; }  
                echo '<div>';
                  $attachments = DB::table('yearend_notes_attachments')->where('client_id',$year_details->id)->where('setting_id',$single)->where('attach_type',0)->get();
                  if(count($attachments))
                  {
                        foreach($attachments as $attachment)
                        {
                          $note_details = DB::table('supplementary_formula_attachments')->where('id',$attachment->note_id)->first();
                            echo '<a href="javascript:" class="fileattachment_note '.$disabled.'" style="" data-element="'.$attachment->note_id.'">'.$note_details->name.'</a><a href="javascript:" class="trash_icon '.$disabled.'"><i class="fa fa-trash trash_notes" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                        }
                  }
                  ?>
                  <i class="fa fa-plus call_notes_modal <?php echo $disabled; ?>" data-element="<?php echo $year_details->id; ?>" data-value="<?php echo $single; ?>" data-type="0" style="margin-top:10px; font-weight: 300;" aria-hidden="true" title="Add Attachment"></i>
                  <?php if(count($attachments)) { ?>
                      <i class="fa fa-minus-square fadeleteall_attachments_note <?php echo $disabled; ?>" data-element="<?php echo $year_details->id; ?>" data-value="<?php echo $single; ?>" data-type="0" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>
                  <?php } ?>
                </div>
              </td>

              <td style="background:#deebf6">

              	<?php
              	if($distribution1_merge[$single] == 1) { $hide ='dist1_hiddentd'; } else { $hide = 'dist1_showtd'; } 
              	if($active_merge[$single] == 1) { $disabled ='disabled'; } else { $disabled =''; } 
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
              <td style="background:#deebf6"><input type="checkbox" id="future1_<?php echo $single?>" name="distribution1_future" class="distribution1_future <?php echo $disabled; ?>" value="1" <?php if($distribution1_merge[$single] == 1){echo 'checked';} else{ echo '';}  ?>><label for="future1_<?php echo $single?>" class="<?php echo $disabled; ?>">Future</label></td>
              <td style="background:#fff3cb">
              	<?php
              	if($distribution2_merge[$single] == 1) { $hide ='dist2_hiddentd'; } else { $hide = 'dist2_showtd'; } 
              	if($active_merge[$single] == 1) { $disabled ='disabled'; } else { $disabled ='notdisabled'; } 
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
	                    <i class="fa fa-minus-square fadeleteall_attachments <?php echo $disabled; ?>" data-element="<?php echo $year_details->id; ?>" data-value="<?php echo $single; ?>" data-distribution="1" data-type="0" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>
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
              <td style="background:#fff3cb"><input type="checkbox" id="future2_<?php echo $single?>" name="distribution2_future" class="distribution2_future <?php echo $disabled; ?>" value="1" <?php if($distribution2_merge[$single] == 1){echo 'checked';} else{ echo '';}  ?>><label for="future2_<?php echo $single?>" class="<?php echo $disabled; ?>">Future</label></td>
              <td style="background:#ededed">
              	<?php
              	if($distribution3_merge[$single] == 1) { $hide ='dist3_hiddentd'; } else { $hide = 'dist3_showtd'; } 
              	if($active_merge[$single] == 1) { $disabled ='disabled'; } else { $disabled ='notdisabled'; } 
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
	                    <i class="fa fa-minus-square fadeleteall_attachments <?php echo $disabled; ?>" data-element="<?php echo $year_details->id; ?>" data-value="<?php echo $single; ?>" data-distribution="1" data-type="0" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>
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
              <td style="background:#ededed"><input type="checkbox" id="future3_<?php echo $single?>" name="distribution3_future" class="distribution3_future <?php echo $disabled; ?>" value="1" <?php if($distribution3_merge[$single] == 1){echo 'checked';} else{ echo '';}  ?>><label for="future3_<?php echo $single?>" class="<?php echo $disabled; ?>">Future</label></td>
            </tr>
          <?php
            }
          }
          ?>
          <tr>
            <td></td>
            <td height="30px"></td>
            <td style="background:#e2efd9"></td>
            <td style="background:#deebf6"></td>
            <td style="background:#deebf6"></td>
            <td style="background:#fff3cb"></td>
            <td style="background:#fff3cb"></td>
            <td style="background:#ededed"></td>
            <td style="background:#ededed"></td>
          </tr>
          <tr>
            <td></td>
            <td>Closing Note</td>
            <td style="background:#e2efd9">
              <?php 
                echo '<div>';
                  $attachments = DB::table('yearend_notes_attachments')->where('client_id',$year_details->id)->where('setting_id',0)->where('attach_type',1)->get();
                  if(count($attachments))
                  {
                        foreach($attachments as $attachment)
                        {
                          $note_details = DB::table('supplementary_formula_attachments')->where('id',$attachment->note_id)->first();
                            echo '<a href="javascript:" class="fileattachment_note" style="" data-element="'.$attachment->note_id.'">'.$note_details->name.'</a><a href="javascript:" class="trash_icon"><i class="fa fa-trash trash_notes" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                        }
                  }
                  ?>
                  <i class="fa fa-plus call_notes_modal" data-element="<?php echo $year_details->id; ?>" data-value="0" data-type="1" style="margin-top:10px; font-weight: 300;" aria-hidden="true" title="Add Attachment"></i>
                  <?php if(count($attachments)) { ?>
                      <i class="fa fa-minus-square fadeleteall_attachments_note" data-element="<?php echo $year_details->id; ?>" data-value="0" data-type="1" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>
                  <?php } ?>
                </div>
            </td>
            <td style="background:#deebf6"></td>
            <td style="background:#deebf6"></td>
            <td style="background:#fff3cb"></td>
            <td style="background:#fff3cb"></td>
            <td style="background:#ededed"></td>
            <td style="background:#ededed"></td>
          </tr>
          <tr>
            <td></td>
            <td>Free Note</td>
            <td style="background:#e2efd9">
              <?php 
                echo '<div>';
                  $attachments = DB::table('yearend_notes_attachments')->where('client_id',$year_details->id)->where('setting_id',0)->where('attach_type',2)->get();
                  if(count($attachments))
                  {
                        foreach($attachments as $attachment)
                        {
                          $note_details = DB::table('supplementary_formula_attachments')->where('id',$attachment->note_id)->first();
                            echo '<a href="javascript:" class="fileattachment_note" style="" data-element="'.$attachment->note_id.'">'.$note_details->name.'</a><a href="javascript:" class="trash_icon"><i class="fa fa-trash trash_notes" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                        }
                  }
                  ?>
                  <i class="fa fa-plus call_notes_modal" data-element="<?php echo $year_details->id; ?>" data-value="0" data-type="2" style="margin-top:10px; font-weight: 300;" aria-hidden="true" title="Add Attachment"></i>
                  <?php if(count($attachments)) { ?>
                      <i class="fa fa-minus-square fadeleteall_attachments_note" data-element="<?php echo $year_details->id; ?>" data-value="0" data-type="2" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>
                  <?php } ?>
                </div>
            </td>
            <td style="background:#deebf6">
            	<?php
                $attachments = DB::table('yearend_distribution_attachments')->where('client_id',$year_details->id)->where('setting_id',0)->where('distribution_type',1)->where('attach_type',1)->get();
                if(count($attachments))
                {
                      foreach($attachments as $attachment)
                      {
                          echo '<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachments.'">'.$attachment->attachments.'</a><a href="javascript:" class="trash_icon"><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                      }
                }
                ?>
            	<i class="fa fa-plus" style="margin-top:10px; font-weight: 300;" aria-hidden="true" title="Add Attachment"></i>
            	<?php if(count($attachments)) { ?>
                    <i class="fa fa-minus-square fadeleteall_attachments" data-element="<?php echo $year_details->id; ?>" data-value="0" data-distribution="1" data-type="1" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>
                <?php } ?>
            	<div class="img_div" style="z-index:9999999">
                    <form name="image_form" id="image_form" action="<?php echo URL::to('user/yearend_individual_attachment?distribution_type=1&attach_type=1'); ?>" method="post" enctype="multipart/form-data" style="text-align: left;">
                      <input type="file" name="image_file[]" required class="form-control image_file" value="" multiple>
                      <input type="hidden" name="hidden_client_id" value="<?php echo $year_details->id; ?>">
                      <input type="hidden" name="hidden_setting_id" value="">
                      <input type="submit" name="image_submit" class="btn btn-sm btn-primary image_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
                      <spam class="error_files"></spam>
                    </form>
                    <div style="width:100%;text-align:center;margin-top:-10px;margin-bottom:10px;color:#000"><label style="font-weight:800;">OR</label></div>
                    <div class="image_div_attachments">
                      <form action="<?php echo URL::to('user/yearend_attachment_individual?distribution_type=1&attach_type=1'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                      	  <input type="hidden" name="hidden_client_id" value="<?php echo $year_details->id; ?>">
                      	  <input type="hidden" name="hidden_setting_id" value="<?php echo $single; ?>">
                          <input name="_token" type="hidden" value="">
                      </form>
                      <a href="<?php echo URL::to('user/yearend_individualclient/'.base64_encode($year_details->id)); ?>" class="btn btn-sm btn-primary" align="left" style="margin-left:7px; float:left;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
                    </div>
                 </div>
            </td>
            <td style="background:#deebf6"></td>
            <td style="background:#fff3cb">
            	<?php
                $attachments = DB::table('yearend_distribution_attachments')->where('client_id',$year_details->id)->where('setting_id',0)->where('distribution_type',2)->where('attach_type',1)->get();
                if(count($attachments))
                {
                      foreach($attachments as $attachment)
                      {
                          echo '<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachments.'">'.$attachment->attachments.'</a><a href="javascript:" class="trash_icon"><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                      }
                }
                ?>
            	<i class="fa fa-plus" style="margin-top:10px; font-weight: 300;" aria-hidden="true" title="Add Attachment"></i>
            	<?php if(count($attachments)) { ?>
                    <i class="fa fa-minus-square fadeleteall_attachments" data-element="<?php echo $year_details->id; ?>" data-value="0" data-distribution="2" data-type="1" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>
                <?php } ?>
            	<div class="img_div" style="z-index:9999999">
                    <form name="image_form" id="image_form" action="<?php echo URL::to('user/yearend_individual_attachment?distribution_type=2&attach_type=1'); ?>" method="post" enctype="multipart/form-data" style="text-align: left;">
                      <input type="file" name="image_file[]" required class="form-control image_file" value="" multiple>
                      <input type="hidden" name="hidden_client_id" value="<?php echo $year_details->id; ?>">
                      <input type="hidden" name="hidden_setting_id" value="<?php echo $single; ?>">
                      <input type="submit" name="image_submit" class="btn btn-sm btn-primary image_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
                      <spam class="error_files"></spam>
                    </form>
                    <div style="width:100%;text-align:center;margin-top:-10px;margin-bottom:10px;color:#000"><label style="font-weight:800;">OR</label></div>
                    <div class="image_div_attachments">
                      <form action="<?php echo URL::to('user/yearend_attachment_individual?distribution_type=2&attach_type=1'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                      	  <input type="hidden" name="hidden_client_id" value="<?php echo $year_details->id; ?>">
                      	  <input type="hidden" name="hidden_setting_id" value="">
                          <input name="_token" type="hidden" value="">
                      </form>
                      <a href="<?php echo URL::to('user/yearend_individualclient/'.base64_encode($year_details->id)); ?>" class="btn btn-sm btn-primary" align="left" style="margin-left:7px; float:left;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
                    </div>
                 </div>
            </td>
            <td style="background:#fff3cb"></td>
            <td style="background:#ededed">
            	<?php
                $attachments = DB::table('yearend_distribution_attachments')->where('client_id',$year_details->id)->where('setting_id',0)->where('distribution_type',3)->where('attach_type',1)->get();
                if(count($attachments))
                {
                      foreach($attachments as $attachment)
                      {
                          echo '<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachments.'">'.$attachment->attachments.'</a><a href="javascript:" class="trash_icon"><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                      }
                }
                ?>
            	<i class="fa fa-plus" style="margin-top:10px; font-weight: 300;" aria-hidden="true" title="Add Attachment"></i>
            	<?php if(count($attachments)) { ?>
                    <i class="fa fa-minus-square fadeleteall_attachments" data-element="<?php echo $year_details->id; ?>" data-value="0" data-distribution="3" data-type="1" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>
                <?php } ?>
            	<div class="img_div" style="z-index:9999999">
                    <form name="image_form" id="image_form" action="<?php echo URL::to('user/yearend_individual_attachment?distribution_type=3&attach_type=1'); ?>" method="post" enctype="multipart/form-data" style="text-align: left;">
                      <input type="file" name="image_file[]" required class="form-control image_file" value="" multiple>
                      <input type="hidden" name="hidden_client_id" value="<?php echo $year_details->id; ?>">
                      <input type="hidden" name="hidden_setting_id" value="">
                      <input type="submit" name="image_submit" class="btn btn-sm btn-primary image_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
                      <spam class="error_files"></spam>
                    </form>
                    <div style="width:100%;text-align:center;margin-top:-10px;margin-bottom:10px;color:#000"><label style="font-weight:800;">OR</label></div>
                    <div class="image_div_attachments">
                      <form action="<?php echo URL::to('user/yearend_attachment_individual?distribution_type=3&attach_type=1'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                      	  <input type="hidden" name="hidden_client_id" value="<?php echo $year_details->id; ?>">
                      	  <input type="hidden" name="hidden_setting_id" value="">
                          <input name="_token" type="hidden" value="">
                      </form>
                      <a href="<?php echo URL::to('user/yearend_individualclient/'.base64_encode($year_details->id)); ?>" class="btn btn-sm btn-primary" align="left" style="margin-left:7px; float:left;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
                    </div>
                 </div>
            </td>
            <td style="background:#ededed"></td>
          </tr>
          <tr>
            <td></td>
            <td>Signature:</td>
            <td></td>
            <td>
            	<?php
                $attachments = DB::table('yearend_distribution_attachments')->where('client_id',$year_details->id)->where('setting_id',0)->where('distribution_type',1)->where('attach_type',2)->get();
                if(count($attachments))
                {
                      foreach($attachments as $attachment)
                      {
                          echo '<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachments.'">'.$attachment->attachments.'</a><a href="javascript:" class="trash_icon"><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                      }
                }
                ?>
            	<i class="fa fa-plus" style="margin-top:10px; font-weight: 300;" aria-hidden="true" title="Add Attachment"></i>
            	<?php if(count($attachments)) { ?>
                    <i class="fa fa-minus-square fadeleteall_attachments" data-element="<?php echo $year_details->id; ?>" data-value="0" data-distribution="1" data-type="2" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>
                <?php } ?>
            	<div class="img_div" style="z-index:9999999">
                    <form name="image_form" id="image_form" action="<?php echo URL::to('user/yearend_individual_attachment?distribution_type=1&attach_type=2'); ?>" method="post" enctype="multipart/form-data" style="text-align: left;">
                      <input type="file" name="image_file[]" required class="form-control image_file" value="" multiple>
                      <input type="hidden" name="hidden_client_id" value="<?php echo $year_details->id; ?>">
                      <input type="hidden" name="hidden_setting_id" value="">
                      <input type="submit" name="image_submit" class="btn btn-sm btn-primary image_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
                      <spam class="error_files"></spam>
                    </form>
                    <div style="width:100%;text-align:center;margin-top:-10px;margin-bottom:10px;color:#000"><label style="font-weight:800;">OR</label></div>
                    <div class="image_div_attachments">
                      <form action="<?php echo URL::to('user/yearend_attachment_individual?distribution_type=1&attach_type=2'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                      	  <input type="hidden" name="hidden_client_id" value="<?php echo $year_details->id; ?>">
                      	  <input type="hidden" name="hidden_setting_id" value="">
                          <input name="_token" type="hidden" value="">
                      </form>
                      <a href="<?php echo URL::to('user/yearend_individualclient/'.base64_encode($year_details->id)); ?>" class="btn btn-sm btn-primary" align="left" style="margin-left:7px; float:left;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
                    </div>
                 </div>
            </td>
            <td></td>
            <td>
            	<?php
                $attachments = DB::table('yearend_distribution_attachments')->where('client_id',$year_details->id)->where('setting_id',0)->where('distribution_type',2)->where('attach_type',2)->get();
                if(count($attachments))
                {
                      foreach($attachments as $attachment)
                      {
                          echo '<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachments.'">'.$attachment->attachments.'</a><a href="javascript:" class="trash_icon"><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                      }
                }
                ?>
            	<i class="fa fa-plus" style="margin-top:10px; font-weight: 300;" aria-hidden="true" title="Add Attachment"></i>
            	<?php if(count($attachments)) { ?>
                    <i class="fa fa-minus-square fadeleteall_attachments" data-element="<?php echo $year_details->id; ?>" data-value="0" data-distribution="2" data-type="2" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>
                <?php } ?>
            	<div class="img_div" style="z-index:9999999">
                    <form name="image_form" id="image_form" action="<?php echo URL::to('user/yearend_individual_attachment?distribution_type=2&attach_type=2'); ?>" method="post" enctype="multipart/form-data" style="text-align: left;">
                      <input type="file" name="image_file[]" required class="form-control image_file" value="" multiple>
                      <input type="hidden" name="hidden_client_id" value="<?php echo $year_details->id; ?>">
                      <input type="hidden" name="hidden_setting_id" value="">
                      <input type="submit" name="image_submit" class="btn btn-sm btn-primary image_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
                      <spam class="error_files"></spam>
                    </form>
                    <div style="width:100%;text-align:center;margin-top:-10px;margin-bottom:10px;color:#000"><label style="font-weight:800;">OR</label></div>
                    <div class="image_div_attachments">
                      <form action="<?php echo URL::to('user/yearend_attachment_individual?distribution_type=2&attach_type=2'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                      	  <input type="hidden" name="hidden_client_id" value="<?php echo $year_details->id; ?>">
                      	  <input type="hidden" name="hidden_setting_id" value="">
                          <input name="_token" type="hidden" value="">
                      </form>
                      <a href="<?php echo URL::to('user/yearend_individualclient/'.base64_encode($year_details->id)); ?>" class="btn btn-sm btn-primary" align="left" style="margin-left:7px; float:left;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
                    </div>
                 </div>
            </td>
            <td></td>
            <td>
            	<?php
                $attachments = DB::table('yearend_distribution_attachments')->where('client_id',$year_details->id)->where('setting_id',0)->where('distribution_type',3)->where('attach_type',2)->get();
                if(count($attachments))
                {
                      foreach($attachments as $attachment)
                      {
                          echo '<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachments.'">'.$attachment->attachments.'</a><a href="javascript:" class="trash_icon"><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                      }
                }
                ?>
            	<i class="fa fa-plus" style="margin-top:10px; font-weight: 300;" aria-hidden="true" title="Add Attachment"></i>
            	<?php if(count($attachments)) { ?>
                    <i class="fa fa-minus-square fadeleteall_attachments" data-element="<?php echo $year_details->id; ?>" data-value="0" data-distribution="3" data-type="2" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>
                <?php } ?>
            	<div class="img_div" style="z-index:9999999">
                    <form name="image_form" id="image_form" action="<?php echo URL::to('user/yearend_individual_attachment?distribution_type=3&attach_type=2'); ?>" method="post" enctype="multipart/form-data" style="text-align: left;">
                      <input type="file" name="image_file[]" required class="form-control image_file" value="" multiple>
                      <input type="hidden" name="hidden_client_id" value="<?php echo $year_details->id; ?>">
                      <input type="hidden" name="hidden_setting_id" value="">
                      <input type="submit" name="image_submit" class="btn btn-sm btn-primary image_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
                      <spam class="error_files"></spam>
                    </form>
                    <div style="width:100%;text-align:center;margin-top:-10px;margin-bottom:10px;color:#000"><label style="font-weight:800;">OR</label></div>
                    <div class="image_div_attachments">
                      <form action="<?php echo URL::to('user/yearend_attachment_individual?distribution_type=3&attach_type=2'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                      	  <input type="hidden" name="hidden_client_id" value="<?php echo $year_details->id; ?>">
                      	  <input type="hidden" name="hidden_setting_id" value="">
                          <input name="_token" type="hidden" value="">
                      </form>
                      <a href="<?php echo URL::to('user/yearend_individualclient/'.base64_encode($year_details->id)); ?>" class="btn btn-sm btn-primary" align="left" style="margin-left:7px; float:left;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
                    </div>
                </div>
            </td>
            <td></td>
          </tr>

          <tr style="font-weight: bold;">
            <td></td>
            <td></td>
            <td></td>
            <td colspan="2" align="center">
              <a href="javascript:" class="report_ok_button_100">Email Distribution</a><br/>
              <a href="javascript:" class="report_ok_button_100" style="clear: both">Resend Email client</a>
              <div style="width: 100%; margin: 10px 0px;">Distribution Notes</div>
              <div style="width: 100%">
                <input type="radio" id="downloadpdf1" name="download"><label for="downloadpdf1">PDF</label>
                <input type="radio" id="downloadword1" name="download"><label for="downloadword1">Word</label>
              </div>
              <div style="width: 100%">
                <input type="checkbox" id="email_checkbox1" name=""><label for="email_checkbox1">Email</label>                                
              </div>
              <div style="width: 100%;">
                <a href="javascript:" class="report_ok_button_100" style="clear: both">Download</a>
              </div>
            </td>
            <td colspan="2" align="center">
              <a href="javascript:" class="report_ok_button_100">Email Distribution</a><br/>
              <a href="javascript:" class="report_ok_button_100" style="clear: both">Resend Email client</a>
              <div style="width: 100%; margin: 10px 0px;">Distribution Notes</div>
              <div style="width: 100%">
                <input type="radio" id="downloadpdf2" name="download2"><label for="downloadpdf2">PDF</label>
                <input type="radio" id="downloadword2" name="download2"><label for="downloadword2">Word</label>
              </div>
              <div style="width: 100%">
                <input type="checkbox" id="email_checkbox2" name=""><label for="email_checkbox2">Email</label>                                
              </div>
              <div style="width: 100%;">
                <a href="javascript:" class="report_ok_button_100" style="clear: both">Download</a>
              </div>
            </td>
            <td colspan="2" align="center">
              <a href="javascript:" class="report_ok_button_100">Email Distribution</a><br/>
              <a href="javascript:" class="report_ok_button_100" style="clear: both">Resend Email client</a>
              <div style="width: 100%; margin: 10px 0px;">Distribution Notes</div>
              <div style="width: 100%">
                <input type="radio" id="downloadpdf3" name="download3"><label for="downloadpdf3">PDF</label>
                <input type="radio" id="downloadword3" name="download3"><label for="downloadword3">Word</label>
              </div>
              <div style="width: 100%">
                <input type="checkbox" id="email_checkbox3" name=""><label for="email_checkbox3">Email</label>                                
              </div>
              <div style="width: 100%;">
                <a href="javascript:" class="report_ok_button_100" style="clear: both">Download</a>
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
	var setting_active = '';
	var distribution1_future = '';
	var distribution2_future = '';
	var distribution3_future = '';
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
$(window).click(function(e) {
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
if($(e.target).hasClass("add_notes_yearend"))
{
  var count = $(".sub_note:checked").length;
  if(count > 0)
  {
    var year_id = $("#hidden_notes_year_id").val();
    var setting_id = $("#hidden_notes_setting_id").val();
    var type = $("#hidden_notes_type").val();
    var values = '';
    $(".sub_note:checked").each(function(){
      if(values == "")
      {
        values = $(this).val();
      }
      else{
        values = values+','+$(this).val();
      }
    });
    $.ajax({
      url:"<?php echo URL::to('user/insert_notes_yearend'); ?>",
      type:"get",
      data:{year_id:year_id,setting_id:setting_id,type:type,values:values},
      success: function(result)
      {
        window.location.reload();
      }
    });
  }
  else{
    alert("you should select atleast one note to proceed");
  }
}
if($(e.target).hasClass('call_notes_modal'))
{
  var year_id = $(e.target).attr("data-element");
  var setting_id = $(e.target).attr("data-value");
  var type = $(e.target).attr("data-type");
  $.ajax({
    url:"<?php echo URL::to('user/check_already_attached'); ?>",
    type:"get",
    data:{year_id:year_id,setting_id:setting_id,type:type},
    success: function(result)
    {
      $("#supplementary_notes_modal").modal("show");
      $(".supplementary_notes_modal_body").html(result);

      $("#hidden_notes_year_id").val(year_id);
      $("#hidden_notes_setting_id").val(setting_id);
      $("#hidden_notes_type").val(type);
    }
  })
}
if($(e.target).hasClass('setting_active'))
{
	if($(e.target).is(":checked"))
	{
		$(e.target).parents("tr").find(".notdisabled").removeClass("notdisabled").addClass("disabled");
		
	}
	else{
		$(e.target).parents("tr").find(".disabled").removeClass("disabled").addClass("notdisabled");
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

if($(e.target).hasClass('setting_class')){
    $(".setting_crypt_modal").modal("show");
    $(".crypt_pin_setting").prop('required', true);
    $(".crypt_pin_setting").prop('disabled', false);
    $(".crypt_pin_setting").val('');
    $(".crypt_error").hide();    
}


if($(e.target).hasClass('setting_button')){
  var setting_type = $(".setting_type").val();
  if(setting_type == "" || typeof setting_type === "undefined")
  {
    alert("Please select type");
    return false;
  }
}



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
if($(e.target).hasClass('setting_type')){
  var level = $(e.target).val();  
  if (level == 1) {     
    $(".setting_button").attr("href", "<?php echo URL::to('user/supplementary_manager')?>");
  }

  else if (level == 2) {     
    $(".setting_button").attr("href", "<?php echo URL::to('user/yearend_setting')?>");
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
    $.ajax({
        url:"<?php echo URL::to('user/yearend_delete_all_note'); ?>",
        type:"post",
        data:{clientid:clientid,settingid:settingid,type:type},
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




</script>







@stop
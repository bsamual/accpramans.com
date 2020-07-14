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
        window.location.replace(base_url+'/user/import_new_clients_one?filename=<?php echo $filename; ?>&height=<?php echo $height; ?>&round=<?php echo $round; ?>&highestrow=<?php echo $highestrow; ?>&import_type_new=1&out=<?php echo $out; ?>');
      })
    </script>
    <?php

  }
}
if(!empty($_GET['import_type_existing']))
{
  if(!empty($_GET['round']))
  {
    $filename = $_GET['filename'];
    $height = $_GET['height'];
    $highestrow = $_GET['highestrow'];
    $round = $_GET['round'];
    $out = $_GET['out'];
    $checkbox = $_GET['checkbox'];
    ?>
    <div class="upload_img" style="width: 100%;height: 100%;z-index:1"><p class="upload_text">Uploading Please wait...</p><img src="<?php echo URL::to('assets/loading.gif'); ?>" width="100px" height="100px"><p class="upload_text">Finished Uploading <?php echo $height; ?> of <?php echo $highestrow; ?></p></div>

    <script>
      $(document).ready(function() {
        var base_url = "<?php echo URL::to('/'); ?>";
        window.location.replace(base_url+'/user/import_existing_clients_one?filename=<?php echo $filename; ?>&height=<?php echo $height; ?>&round=<?php echo $round; ?>&highestrow=<?php echo $highestrow; ?>&import_type_existing=1&out=<?php echo $out; ?>&checkbox=<?php echo $checkbox; ?>');
      })
    </script>
    <?php

  }
}
?>
<img id="coupon" />

<div class="modal fade other_client_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Other Clients</h4>
            <br/>
            
        </div>
        <div class="modal-body" style="height: 400px; overflow-y: scroll;">
          <div class="panel-group">
            <div class="col-lg-9">
              <div class="form-title">Choose Client</div>
              <input type="text" class="form-control client_search_class" placeholder="Enter Client Name" name="">
              <input type="text" id="client_search" name="" />
              <input type="text" id="select_type" name="" />
              <input type="text" id="current_client_id" name="" />
              
            </div>              
            <div class="col-lg-3" style="padding: 15px 0px 0px 0px; ">
              <input type="button" class="common_black_button other_submit"  value="Submit">
            </div>
              
            </div>
            <table class="table">
              <thead>
              <tr style="background: #fff;">
                   <th width="5%" style="text-align: left;">S.No</th>                   
                  <th style="text-align: left;">Client ID</th>
                  <th style="text-align: left;">First Name</th>    
                  <th style="text-align: left;">Company Name</th>                         
              </tr>
              </thead>                            
              <tbody>
                <?php
                $output='';
                $i=1;
                if(count($clientlist)){
                  foreach ($clientlist as $client) {
                    if($client->active == 1){
                      $color = 'style="color:#26BD67"';
                    }
                    elseif($client->active == 2){
                      $color = 'style="color:#FF0000"';
                    }
                    $output.='
                    <tr>
                      <td '.$color.'>'.$i.'</td>
                      <td '.$color.'>'.$client->client_id.'</td>
                      <td '.$color.'>'.$client->firstname.'</td>
                      <td '.$color.'>'.$client->company.'</td>
                    </tr>';
                    $i++;
                  }
                  
                }
                else{
                  $output='<tr><td colspan="4">Empty</td></tr>';
                }
                echo $output;
                ?>
              

              </tbody>
          </table>
        </div>
        <div class="modal-footer">            
            
        </div>
      </div>
  </div>
</div>

<div class="modal fade partner_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Personal Acquaintance of Partner</h4>           
            
        </div>
        <div class="modal-body" >
          <div class="panel-group">
            <div class="form-title">Select Partner</div>
              <select class="form-control" id="user_type">
                <option value="">Select Partner</option>
                <?php


                $resultuser='';
                if(count($userlist)){
                  foreach ($userlist as $user) {
                    $resultuser.='<option value='.$user->user_id.'>'.$user->firstname.' '.$user->lastname.'</option>';
                  }
                }
                echo $resultuser;
                ?>
              </select>
              
              <input type="text" id="select_type2" name="" />
              <input type="text" id="partner_current_client_id" name="" />
            
              
            </div>
        </div>
        <div class="modal-footer">            
            <input type="button" class="common_black_button partner_submit"  value="Submit">
        </div>
      </div>
  </div>
</div>

<div class="modal fade reply_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Reply to Advert / Walk in</h4>
                       
        </div>
        <div class="modal-body" >
          <div class="panel-group">
            <div class="form-title">Enter Notes</div>
              <textarea class="form-control" placeholder="Enter Notes" id="reply_note" style="height: 200px;"></textarea>
              
              <input type="text" id="select_type3" name="" />
              <input type="text" id="note_current_client_id" name="" />
            
              
            </div>
        </div>
        <div class="modal-footer">            
            <input type="button" class="common_black_button note_submit"  value="Submit">
        </div>
      </div>
  </div>
</div>

<div class="modal fade bank_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add Bank</h4>
                        
        </div>
        <div class="modal-body" >
          <div class="form-group">
            BanK Name
              <input type="text" name="" class="form-control"  id="bank_name" placeholder="Bank Name" >
            </div>
            <div class="form-group">Bank Account Name
              <input type="text" name="" class="form-control" id="account_name" placeholder="Bank Account Name">
            </div>
            <div class="form-group">Bank Account Number
              <input type="number" name="" class="form-control" id="account_number" placeholder="Bank Account Number">
            </div>
            
            <input type="text" id="bank_current_client_id" name="" />
        </div>
        <div class="modal-footer">            
            <input type="button" class="common_black_button bank_submit"  value="Add Bank">
        </div>
      </div>
  </div>
</div>

<div class="modal fade bank_detail_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title bank_company_name">Add Bank</h4>           
            
        </div>
        <div class="modal-body">

          <table class="display nowrap fullviewtablelist"  id="bank_expand">
            <thead>
              <th>#</th>
              <th>Bank Name</th>
              <th>Account Name</th>
              <th>Account Number</th>
            </thead>
            <tbody id="bank_detail_body">
              
            </tbody>
          </table>
          
        </div>
        <div class="modal-footer">            
            
        </div>
      </div>
  </div>
</div>


<div class="modal fade review_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title bank_company_name">Review</h4>           
            
        </div>
        <div class="modal-body">
          <div class="form-group">
            Select Date
            <label class="input-group datepicker-only-init_date_received">
                <input type="text" class="form-control review_date" id="review_date" placeholder="Select Date" name="received_date" style="font-weight: 500;" required />
                <span class="input-group-addon">
                    <i class="glyphicon glyphicon-calendar"></i>
                </span>
            </label>
          </div>
          <div class="form-group">
            Review By
            <textarea class="form-control" id="reivew_filed"></textarea>
          </div>
          <input type="text" id="review_current_client_id" name="">
        </div>
        <div class="modal-footer">            
            <input type="button" class="common_black_button review_submit"  value="Review">
        </div>
      </div>
  </div>
</div>




<div class="content_section" style="margin-bottom:200px">
  <div class="page_title">
        <h4 class="col-lg-3" style="padding: 0px;">
                AML System                
            </h4>
            <div class="col-lg-6 text-right" style="padding-right: 0px; line-height: 35px;">
                
            </div>
            
            
            <div class="col-lg-1 text-right"  style="padding: 0px;" >
              <div class="select_button" style=" margin-left: 10px;">
                <ul>
                
                <li><a href="javascript:" style="font-size: 13px; font-weight: 500;">Report</a></li>
                <li><a href="javascript:" style="font-size: 13px; font-weight: 500;">Notify</a></li>
              </ul>
            </div>
          </div>
          <div class="col-lg-2" style="padding-top: 7px">
            
            <?php $check_incomplete = Db::table('user_login')->where('userid',1)->first(); if($check_incomplete->aml_incomplete == 1) { $inc_checked = 'checked'; } else { $inc_checked = ''; } ?>
                <input type="checkbox" name="show_incomplete" id="show_incomplete" value="1" <?php echo $inc_checked?> style="margin-right:10px"><label for="show_incomplete">Hide Deactivated Accounts</label>
              </div>
  <div class="table-responsive" style="max-width: 100%; float: left;margin-bottom:30px; margin-top:55px">
  </div>
  <div style="clear: both;">
   <?php
    if(Session::has('message')) { ?>
        <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>

     
    
    if(isset($_GET['email_sent'])) { DB::table('cm_email_attachment')->delete(); ?>
        <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>Email Sent successfully. </p>
        <script>
            window.history.replaceState(null, null, "<?php echo URL::to('user/client_management'); ?>");
        </script>
    <?php } ?>
    </div> 

<style type="text/css">
.refresh_icon{margin-left: 10px;}
.refresh_icon:hover{text-decoration: none;}
.datepicker-only-init_date_received, .auto_save_date{cursor: pointer;}
.bootstrap-datetimepicker-widget{width: 300px !important;}
</style>

<table class="display nowrap fullviewtablelist" id="aml_expand" width="100%" style="max-width: 100%;">
                        <thead>
                        <tr style="background: #fff;">
                             <th width="2%" style="text-align: left;">S.No</th>
                            <th style="text-align: left;">Client ID</th>
                            <th style="text-align: left;">Company</th>
                            <th style="text-align: left;">First Name</th>
                            <th style="text-align: left;">Surname</th>
                            <th style="text-align: left;">Client Source</th>
                            <th style="text-align: left;">Date Client Since</th>
                            <th style="text-align: left;">Client Identity</th> 
                            <th style="text-align: left;">Bank Accounts</th>                             
                            <th style="text-align: left;">File review</th>
                            <th style="text-align: left;">Risk Category</th>                            
                        </tr>
                        </thead>                            
                        <tbody id="clients_tbody">
                        <?php
                            $i=1;
                            if(count($clientlist)){              
                              foreach($clientlist as $key => $client){

                                
                                
                                  $disabled='';
                                  if($client->active != "")
                                  {
                                    if($client->active == 2)
                                    {
                                      $disabled='disabled';
                                    }
                                    $check_color = DB::table('cm_class')->where('id',$client->active)->first();
                                    $style="color:#".$check_color->classcolor."";
                                  }
                                  else{
                                    $style="color:#000";
                                  }

                                  $aml_system = DB::table('aml_system')->where('client_id', $client->client_id)->first();

                                  if(count($aml_system)){
                                    $risk_select = $aml_system->risk_category;
                                    /*if($aml_system->risk_category == 1){ $risk_select = $aml_system->risk_category;}
                                    elseif($aml_system->risk_category == 2){ $risk_select = $aml_system->risk_category;}
                                    elseif($aml_system->risk_category == 3){ $risk_select = $aml_system->risk_category;}
                                    else{
                                      $risk_select = '';
                                    }*/
                                  }
                                  else{
                                    $risk_select = '';
                                  }   


                                  if(count($aml_system)){
                                    if($aml_system->review == 1){
                                      $output_reveiw = 'Date:'.date('d-M-Y', strtotime($aml_system->review_date)).'</br/>Review By'.$aml_system->file_review;
                                    }
                                    else{
                                      $output_reveiw = '
                                      <div class="select_button" style=" margin-left: 10px;">
                                        <ul>                                    
                                        <li><a href="javascript:" class="review_by" data-element="'.$client->client_id.'" style="font-size: 13px; font-weight: 500;">Review By</a></li>
                                      </ul>
                                    </div>';
                                    }  
                                  }  
                                  else{
                                    $output_reveiw = '
                                    <div class="select_button" style=" margin-left: 10px;">
                                      <ul>                                    
                                      <li><a href="javascript:" class="review_by" data-element="'.$client->client_id.'" style="font-size: 13px; font-weight: 500;">Review By</a></li>
                                    </ul>
                                  </div>';
                                  }                           


                                  if(count($aml_system)){
                                    if($aml_system->client_source == 1){
                                      $client_details = DB::table('cm_clients')->where('client_id', $aml_system->client_source_detail)->first();
                                      $client_source ='Other Client - '.$client_details->firstname.'<a href="javascript:" class="refresh_icon" data-toggle="tooltip" data-original-title="Refresh"><i class="fa fa-refresh refresh_client_source" data-element='.$aml_system->client_id.'></i></a>';
                                    }
                                    elseif($aml_system->client_source == 2){
                                      $user_details = DB::table('user')->where('user_id', $aml_system->client_source_detail)->first();                                      
                                      $client_source ='Partner - '.$user_details->firstname.' '.$user_details->lastname.'<a href="javascript:" class="refresh_icon" data-toggle="tooltip" data-original-title="Refresh"><i class="fa fa-refresh refresh_client_source" data-element='.$aml_system->client_id.'></i></a>';
                                    }
                                    elseif($aml_system->client_source == 3){
                                      
                                      $client_source = 'Note - '.$aml_system->client_source_detail.'<a href="javascript:" class="refresh_icon" data-toggle="tooltip" data-original-title="Refresh"><i class="fa fa-refresh refresh_client_source" data-element='.$aml_system->client_id.'></i></a>';
                                    }
                                    else{
                                      $client_source='<input type="radio" name="client_source" class="other_client" value="1" id="other_client_'.$client->client_id.'" data-element="'.$client->client_id.'" data-toggle="modal" data-target=".other_client_modal" value="1"><label for="other_client_'.$client->client_id.'">Other Client</label><br/>
                                  <input type="radio" name="client_source" class="partner_class" data-toggle="modal" data-target=".partner_modal" value="2"  data-element="'.$client->client_id.'" id="personal_partner_'.$client->client_id.'"><label for="personal_partner_'.$client->client_id.'">Personal Acquaintance of Partner</label><br/>
                                  <input type="radio" name="client_source"  class="reply_class" data-toggle="modal" data-target=".reply_modal"  value="3" data-element="'.$client->client_id.'" id="reply_note_'.$client->client_id.'"><label for="reply_note_'.$client->client_id.'">Reply to Advert / Walk in</label>';
                                    }
                                  }
                                  else{

                                    $client_source='<input type="radio" name="client_source" class="other_client" value="1" id="other_client_'.$client->client_id.'" data-element="'.$client->client_id.'" data-toggle="modal" data-target=".other_client_modal" value="1"><label for="other_client_'.$client->client_id.'">Other Client</label><br/>
                                  <input type="radio" name="client_source" class="partner_class" data-toggle="modal" data-target=".partner_modal" value="2"  data-element="'.$client->client_id.'" id="personal_partner_'.$client->client_id.'"><label for="personal_partner_'.$client->client_id.'">Personal Acquaintance of Partner</label><br/>
                                  <input type="radio" name="client_source"  class="reply_class" data-toggle="modal" data-target=".reply_modal"  value="3" data-element="'.$client->client_id.'" id="reply_note_'.$client->client_id.'"><label for="reply_note_'.$client->client_id.'">Reply to Advert / Walk in</label>';


                                  }


                                  $aml_bank = DB::table('aml_bank')->where('client_id', $client->client_id)->first();
                                  $aml_count = DB::table('aml_bank')->where('client_id', $client->client_id)->count();

                                  if(count($aml_bank)){
                                    $bank_output='<a href="javascript:" class="bank_detail_class" data-element="'.$client->client_id.'">'.$aml_count.'</a>
                                    <a href="javascript:" class="bank_class" data-toggle="modal" data-target=".bank_modal" ><i class="fa fa-plus faplus add_bank" title="Add Bank" data-element="'.$client->client_id.'" style="margin-left:10px;"></i></a>
                                    ';
                                  }
                                  else{
                                    $bank_output='
                                    <a href="javascript:" class="bank_class" data-toggle="modal" data-target=".bank_modal" ><i class="fa fa-plus faplus add_bank" data-toggle="tooltip" data-original-title="Add Bank" data-element="'.$client->client_id.'"></i></a>
                                    ';
                                  }

                                  
                                  
                          ?>
                            <tr class="edit_task <?php echo $disabled; ?>" style="<?php echo $style; ?>"  id="clientidtr_<?php echo $client->id; ?>">
                                <td style="<?php echo $style; ?>"><?php echo $i; ?></td>
                                <td align="left"><a href="javascript:" id="<?php echo base64_encode($client->id); ?>" class="invoice_class" style="<?php echo $style; ?>"><?php echo $client->client_id; ?></a></td>
                                <td align="left"><a href="javascript:" id="<?php echo base64_encode($client->id); ?>" class="invoice_class" style="<?php echo $style; ?>"><?php echo ($client->company == "")?$client->firstname.' & '.$client->surname:$client->company; ?></a></td>
                                <td align="left"><a href="javascript:" id="<?php echo base64_encode($client->id); ?>" class="invoice_class" style="<?php echo $style; ?>"><?php echo $client->firstname; ?></a></td>
                                <td align="left"><a href="javascript:" id="<?php echo base64_encode($client->id); ?>" class="invoice_class" style="<?php echo $style; ?>"><?php echo $client->surname; ?></a></td>
                                
                                <td align="left" style="<?php echo $style; ?>" id="client_source_<?php echo $client->client_id?>">
                                  <?php echo $client_source?>
                                </td>
                                <td align="left" style="<?php echo $style; ?>">
                                  <label class="input-group datepicker-only-init_date_received">
                                    <input type="text" class="form-control client_date_since" placeholder="Select Date" name="received_date" style="font-weight: 500;" required />
                                    <span class="input-group-addon">
                                        <i class="glyphicon glyphicon-calendar"></i>
                                    </span>
                                </label>
                                </td>
                                <td align="left" style="<?php echo $style; ?>"><a href="javascript:" id="'.base64_encode($client->id).'" class="" style="'.$style.'"></a></td>
<!-- 
                                <td align="center"><a href="javascript:" id="<?php echo base64_encode($client->client_id); ?>" class="payroll_class" style="<?php echo $style; ?>">Payroll Tasks</a></td> -->

                                <td style="<?php echo $style; ?>" align="left"  id="client_bank_<?php echo $client->client_id?>">
                                  <?php echo $bank_output ?>
                                </td>
                                <td style="<?php echo $style; ?>" align="left"   id="review_<?php echo $client->client_id?>">
                                  <?php echo $output_reveiw?>
                                  
                                </td>
                                <td style="<?php echo $style; ?>" align="left">
                                  <select class="form-control risk_class" data-element="<?php echo $client->client_id?>" >
                                    <option value="1" <?php if($risk_select == 1){echo 'selected';}else{echo'';}?>>Green</option>
                                    <option value="2" <?php if($risk_select == 2){echo 'selected';}else{echo'';}?>>Yellow</option>
                                    <option value="3" <?php if($risk_select == 3){echo 'selected';}else{echo'';}?>>Red</option>
                                  </select>
                                  
                                </td>
                            </tr>
                            <?php
                              $i++;
                              }              
                            }
                            if($i == 1)
                            {
                              echo'<tr><td colspan="11" align="center">Empty</td></tr>';
                            }
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
      <td style="text-align: left;border:1px solid #000;">Client ID</td>
      <td style="text-align: left;border:1px solid #000;">FIrstName</td>
      <td style="text-align: left;border:1px solid #000;">Surname</td>
      <td style="text-align: left;border:1px solid #000;">Company</td>
      <td style="text-align: left;border:1px solid #000;">Address</td>
      <td style="text-align: left;border:1px solid #000;">EMail ID</td>
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

<script type="text/javascript">
<?php

if(!empty($_GET['divid']))
{
  $divid = $_GET['divid'];
  ?>
  $(function() {
    $("body").addClass("loading");
    setTimeout(function(){ 
      if($("#<?php echo $divid; ?>").length > 0)
      {
        $(document).scrollTop( $("#<?php echo $divid; ?>").offset().top - parseInt(150) ); 
        <?php if(Session::get('edit_message')){ ?>
          $.colorbox({html:"<p style=text-align:center;font-size:18px;font-weight:600;color:green><?php echo Session::get('edit_message'); ?></p>",width:"30%",fixed:true});
        <?php } else if(Session::get('edit_error')) { ?>
          $.colorbox({html:"<p style=text-align:center;font-size:18px;font-weight:600;color:#f00><?php echo Session::get('edit_error'); ?></p>",width:"30%",fixed:true});
        <?php } else if(isset($_GET['activate'])) { ?>
            $.colorbox({html:"<p style=text-align:center;font-size:18px;font-weight:600;color:green>Client Activated Successfully.</p>",width:"30%",fixed:true});
        <?php } else if(isset($_GET['deactivate'])) { ?>
            $.colorbox({html:"<p style=text-align:center;font-size:18px;font-weight:600;color:#f00>Client Deactivated Successfully.</p>",width:"30%",fixed:true});
        <?php } else if(isset($_GET['status_pin_invalid'])) { ?>
            $.colorbox({html:"<p style=text-align:center;font-size:18px;font-weight:600;color:#f00>Crypt Pin You have entered is Incorrect.</p>",width:"30%",fixed:true});
        <?php } ?>
      }
      $("body").removeClass("loading"); 
       window.history.replaceState(null, null, "<?php echo URL::to('user/client_management'); ?>");
    }, 5000); 
  });
  <?php
} ?>
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
//     var base_url = "<?php echo URL::to('user/clientmanagement_paginate'); ?>";

//     $("#pagination").val(count);
//     $.ajax({
//       url: base_url,
//       data: {page:count},
//       type: "get",
//       success:function(result){
//         $("body").removeClass("loading");
//         $("body").find(".load_more").removeClass("load_more");
//         $("#clients_tbody").append(result);
//         var table = $('#client_expand').DataTable();
 
//         table.fixedHeader.adjust();
//       }
//     });
//   }
$(document).ready(function(){
    $('#images').on('change',function(){
      $("body").addClass("loading");
      setTimeout(function(){ 
          $('#multiple_upload_form').ajaxForm({
              //display the uploaded images
              target:'#images_preview',
              beforeSubmit:function(e){
                  $(".attachments").html(e);
                   $("body").removeClass("loading");
              },
              success:function(e){
                  $(".attachments").html(e);
                   $("body").removeClass("loading");
              },
              error:function(e){
                  $(".attachments").html(e);
                   $("body").removeClass("loading");
              }
          }).submit();
      }, 2000);
      
  });
});
$(document).ready(function() {
  $('[data-toggle="tooltip"]').tooltip(); 
  if($("#show_incomplete").is(':checked'))
  {
    $(".edit_task").each(function() {
        if($(this).hasClass('disabled'))
        {
          $(this).hide();
        }
    });
  }
  else{
    $(".edit_task").each(function() {
        if($(this).hasClass('disabled'))
        {
          $(this).show();
        }
    });
  }
});

$(window).click(function(e) {
  if(e.target.id == 'show_incomplete')
  {
    if($(e.target).is(':checked'))
    {
      $(".edit_task").each(function() {
          if($(this).hasClass('disabled'))
          {
            $(this).hide();
          }
      });
      $.ajax({
        url:"<?php echo URL::to('user/update_aml_incomplete_status'); ?>",
        type:"post",
        data:{value:1},
        success: function(result)
        {
        }
      });
    }
    else{
      $(".edit_task").each(function() {
          if($(this).hasClass('disabled'))
          {
            $(this).show();
          }
      });
      $.ajax({
        url:"<?php echo URL::to('user/update_aml_incomplete_status'); ?>",
        type:"post",
        data:{value:0},
        success: function(result)
        {
        }
      });
    }
  }

if($(e.target).hasClass('refresh_client_source')){
  $('body').addClass('loading');
  var client_id = $(e.target).attr('data-element');
  
  $.ajax({
    url:"<?php echo URL::to('user/aml_system_client_source_refresh'); ?>",
    type:"post",
    data:{id:client_id},
    dataType:"json",
    success: function(result)
    {
      $("#client_source_"+result['id']).html(result['output']);
      $('body').removeClass('loading');
    }
  });

}



if($(e.target).hasClass('risk_class')){
  var client_id = $(e.target).attr('data-element');
  var value = $(e.target).val();
  
  $.ajax({
    url:"<?php echo URL::to('user/aml_system_risk_update'); ?>",
    type:"post",
    data:{id:client_id,value:value},
    dataType:"json",
    success: function(result)
    {
      
    }
  });


}

if($(e.target).hasClass('other_client')){
  var type = $(e.target).val();
  var current_client = $(e.target).attr('data-element');

  $("#select_type").val(type);
  $("#current_client_id").val(current_client);  
  $("#client_search").val('');
  $(".client_search_class").val('');
}

if($(e.target).hasClass('other_submit')){
  $('body').addClass('loading');
  var client_id = $("#client_search").val();  
  var type = $("#select_type").val();
  var current_client_id = $("#current_client_id").val();

  $.ajax({
    url:"<?php echo URL::to('user/aml_system_other_client'); ?>",
    type:"post",
    data:{client_id:client_id,type:type,current_client_id:current_client_id},
    dataType:"json",
    success: function(result)
    {
      $(".other_client_modal").modal('hide');
      $("#client_source_"+result['id']).html(result['output']);
      $('body').removeClass('loading');
      $('[data-toggle="tooltip"]').tooltip();
      
    }
  });


}

if($(e.target).hasClass('partner_class')){

  var type = $(e.target).val();
  var current_client = $(e.target).attr('data-element');
  $("#user_type").val('');

  $("#select_type2").val(type);
  $("#partner_current_client_id").val(current_client);  

}


if($(e.target).hasClass('partner_submit')){
  $('body').addClass('loading');  
  var type = $("#select_type2").val();
  var current_client_id = $("#partner_current_client_id").val();
  var user_type = $("#user_type").val();

  $.ajax({
    url:"<?php echo URL::to('user/aml_system_partner'); ?>",
    type:"post",
    data:{type:type,current_client_id:current_client_id,user_type:user_type},
    dataType:"json",
    success: function(result)
    {
      $(".partner_modal").modal('hide');
      $("#client_source_"+result['id']).html(result['output']);
      $('body').removeClass('loading');
      $('[data-toggle="tooltip"]').tooltip();
      
    }
  });


}

if($(e.target).hasClass('reply_class')){

  var type = $(e.target).val();
  var current_client = $(e.target).attr('data-element');
  $("#reply_note").val('');

  $("#select_type3").val(type);
  $("#note_current_client_id").val(current_client);  

}

if($(e.target).hasClass('note_submit')){
  $('body').addClass('loading');  
  var type = $("#select_type3").val();
  var current_client_id = $("#note_current_client_id").val();
  var reply_note = $("#reply_note").val();

  $.ajax({
    url:"<?php echo URL::to('user/aml_system_note'); ?>",
    type:"post",
    data:{type:type,current_client_id:current_client_id,reply_note:reply_note},
    dataType:"json",
    success: function(result)
    {
      $(".reply_modal").modal('hide');
      $("#client_source_"+result['id']).html(result['output']);
      $('body').removeClass('loading');
      $('[data-toggle="tooltip"]').tooltip();
      
    }
  });
}


if($(e.target).hasClass('add_bank')){

  var current_client = $(e.target).attr('data-element');
  $("#bank_name").val('');
  $("#account_name").val('');
  $("#account_number").val('');
  
  $("#bank_current_client_id").val(current_client);  

}


if($(e.target).hasClass('bank_submit')){
  $('body').addClass('loading');    
  var current_client_id = $("#bank_current_client_id").val();
  var bank_name = $("#bank_name").val();
  var account_name = $("#account_name").val();
  var account_number = $("#account_number").val();

  $.ajax({
    url:"<?php echo URL::to('user/aml_system_add_bank'); ?>",
    type:"post",
    data:{current_client_id:current_client_id,bank_name:bank_name,account_name:account_name,account_number:account_number},
    dataType:"json",
    success: function(result)
    {
      $(".bank_modal").modal('hide');
      $("#client_bank_"+result['id']).html(result['output']);
      $('body').removeClass('loading');
      $('[data-toggle="tooltip"]').tooltip();
      
    }
  });


}

if($(e.target).hasClass('bank_detail_class')){
  
  $("#bank_expand").dataTable().fnDestroy();
  $('body').addClass('loading');  
   var client_id = $(e.target).attr('data-element');

  $.ajax({
    url:"<?php echo URL::to('user/aml_system_bank_details'); ?>",
    type:"post",
    data:{client_id:client_id},
    dataType:"json",
    success: function(result)
    {
      $(".bank_detail_model").modal('show');
      $("#bank_detail_body").html(result['output']);
      $(".bank_company_name").html(result['company_name']);
      $('#bank_expand').DataTable({
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
      $('body').removeClass('loading'); 
    }
  });
}

if($(e.target).hasClass('review_by')){

  var current_client = $(e.target).attr('data-element');
  $("#review_current_client_id").val(current_client);
  $(".review_model").modal('show');


}

if($(e.target).hasClass('review_submit')){
  $('body').addClass('loading');    
  var current_client_id = $("#review_current_client_id").val();
  var review_date = $("#review_date").val();
  var reivew_filed = $("#reivew_filed").val();

  $.ajax({
    url:"<?php echo URL::to('user/aml_system_review'); ?>",
    type:"post",
    data:{current_client_id:current_client_id,review_date:review_date,reivew_filed:reivew_filed},
    dataType:"json",
    success: function(result)
    {
      $(".review_model").modal('hide');
      
    }
  });


}





});
$.ajaxSetup({async:false});
$('#form-validation').validate({
    rules: {
        name : {required: true,},
        lname : {required: true,},
        taxnumber : {required: true,remote:"<?php echo URL::to('user/rctclient_checktax'); ?>"},
        email : {required: true,email:true,remote:"<?php echo URL::to('user/rctclient_checkemail'); ?>"},
    },
    messages: {
        name : "Client Name is Required",
        lname : "Salutation is Required",
        taxnumber : {
          required : "Tax Number is Required",
          remote : "Tax Number is already exists",
        },
        email : {
          required : "Email Id is Required",
          email : "Please Enter a valid Email Address",
          remote : "Email Id is already exists",
        },
    },
});

</script>

<!-- Page Scripts -->
<script>
$(function(){
    $('#aml_expand').DataTable({
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

$(".client_date_since").datetimepicker({
   //defaultDate: fullDate,       
   format: 'L',
   format: 'DD-MMM-YYYY',
   //maxDate: fullDate,
});
$(".review_date").datetimepicker({   
   format: 'L',
   format: 'DD-MMM-YYYY'
});



</script>

<script>
$(document).ready(function() {    
  $(".client_search_class").autocomplete({
        source: function(request, response) {
            $.ajax({
                url:"<?php echo URL::to('user/aml_client_search'); ?>",
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
          $.ajax({
            url:"<?php echo URL::to('user/aml_client_search_select'); ?>",
            data:{value:ui.item.id},
            success: function(result){
              
              
            }
          })
        }
  });
});
</script>





@stop
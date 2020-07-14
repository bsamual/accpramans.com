@extends('userheader')
@section('content')

<style>
.download_letterpad{
    position: absolute;
    top: 0px;
    right: 0px;
    z-index: 9999;
    font-size: 24px;
    padding: 2px;
    background: #fff;
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
.fa-sort{
  cursor: pointer;
}
.modal_load {
    display:    none;
    position:   fixed;
    z-index:    99999;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 ) 
                url(<?php echo URL::to('assets/loading.gif'); ?>) 
                50% 50% 
                no-repeat;
}
.error_ref{
  color: #f00;
    font-size: 9px;
    position: absolute;
    left: 55.5%;
}
body.loading {
    overflow: hidden;
}
body.loading .modal_load {
    display: block;
}
</style>
<style>
    .table thead th:focus{background: #333333}

</style>
<style>
.letter_image{width: 100%; height: auto; position: absolute;}
.item_view_all{width: 100%; height: auto; float: left; position: absolute; margin-top: 50px; padding: 20px; }
.iteal_view_title{width: 100%; height: auto; float: left; text-align: center; font-size: 30px; margin-top: 200px; font-weight: bold; }
.iteal_view_sub_title{width: 100%; height: auto; float: left; text-align: center; font-size: 20px; padding:20px 0px; font-weight: bold;}
.table_view{width: 100%; height: auto; float: left; margin-top: 50px;}
.table thead th:focus{background: #ddd !important;}
.form-control{border-radius: 0px;}
</style>
<?php
if(!empty($_GET['import_type']))
{
  if(!empty($_GET['round']))
  {
    
    $filename = $_GET['filename'];

    $client_id = $_GET['client_id'];
    $type = $_GET['type'];
    $height = $_GET['height'];
    $highestrow = $_GET['highestrow'];
    $round = $_GET['round'];
    $out = $_GET['out'];

    ?>
    <div class="upload_img" style="width: 100%;height: 100%;z-index:1"><p class="upload_text">Uploading Please wait...</p><img src="<?php echo URL::to('assets/loading.gif'); ?>" width="100px" height="100px"><p class="upload_text">Finished Uploading <?php echo $height; ?> of <?php echo $highestrow; ?></p></div>

    <script>
      $(document).ready(function() {
        var base_url = "<?php echo URL::to('/'); ?>";
        window.location.replace(base_url+'/user/rctimport_form_one?filename=<?php echo $filename; ?>&client_id=<?php echo $client_id; ?>&type=<?php echo $type; ?>&height=<?php echo $height; ?>&round=<?php echo $round; ?>&highestrow=<?php echo $highestrow; ?>&import_type=1&out=<?php echo $out; ?>');
      })
    </script>
    <?php

  }
}
?>
<div class="modal fade client_email_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg" role="document" style="width:40%">
   <div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Email Item</h4>
    </div>
    <div class="modal-body notify_place_div">
     
          <form action="<?php echo URL::to('user/rctclient_email_form'); ?>" method="post" >
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
              <?php
              $admin_details = DB::table('admin')->where('id',1)->first();
              ?>
                <input type="text" name="cc_user" class="form-control input-sm" value="<?php echo $admin_details->cc_email; ?>" readonly>
              </div>
            </div>
            <div class="row" style="margin-top:10px">
              <div class="col-md-3">
                <label>Subject</label>
              </div>
              <div class="col-md-9">
                <input type="text" name="subject_email" class="form-control input-sm subject_email" value="" required>
              </div>
            </div>
            <div class="row" style="margin-top:10px">
              <div class="col-md-12">
                <label>Message</label>
              </div>
              <div class="col-md-12">
                <textarea name="message_editor" id="editor_1">
                    <?php
                    $item_salutation = DB::table('email_salution')->where('id',4)->first();
                    echo $item_salutation->description;
                    ?>
                </textarea>
              </div>
            </div>
            <div class="row" style="margin-top:10px">
              <div class="col-md-12">
                <label>Attachment</label>
              </div>
              <div class="col-md-12" id="email_attachments">
                  <img src="<?php echo URL::to('assets/pdf.jpg'); ?>" width="100" height="100">
                  <spam class="attachment_pdf"></spam>
                  <input type="hidden" name="hidden_attachment_pdf" id="hidden_attachment_pdf" value="">
                  <input type="hidden" name="hidden_item_id" id="hidden_item_id" value="">
              </div>
            </div>
            <div class="row" style="margin-top:10px">
              <div class="col-md-12">
                <label>Message</label>
              </div>
              <div class="col-md-12">
                <input type="submit" class="common_black_button" value="Send Mail" style="float:right">
              </div>
            </div>
         </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade email_report_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg" role="document" style="width:40%">
   <div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Email Report</h4>
    </div>
    <div class="modal-body notify_place_div">
     
          <form action="<?php echo URL::to('user/rctemail_report_form'); ?>" method="post" >
            <div class="row">
              <div class="col-md-3">
                <label>From</label>
              </div>
              <div class="col-md-9">
                <input type="text" name="from_user_report" id="from_user_report" class="form-control input-sm" value="" required>
              </div>
            </div>
            <div class="row" style="margin-top:10px">
              <div class="col-md-3">
                <label>To</label>
              </div>
              <div class="col-md-9">
                <input type="text" name="to_user_report" id="to_user_report" class="form-control input-sm" value="" required>
              </div>
            </div>
            <div class="row" style="margin-top:10px">
              <div class="col-md-3">
                <label>CC</label>
              </div>
              <div class="col-md-9">
              <?php
              $admin_details = DB::table('admin')->where('id',1)->first();
              ?>
                <input type="text" name="cc_user_report" class="form-control input-sm" value="<?php echo $admin_details->cc_email; ?>" readonly>
              </div>
            </div>
            <div class="row" style="margin-top:10px">
              <div class="col-md-3">
                <label>Subject</label>
              </div>
              <div class="col-md-9">
                <input type="text" name="subject_email_report" class="form-control input-sm subject_email_report" value="" required>
              </div>
            </div>
            <div class="row" style="margin-top:10px">
              <div class="col-md-12">
                <label>Message</label>
              </div>
              <div class="col-md-12">
                <textarea name="message_editor_report" id="editor_2">
                    <?php
                    $report_salutation = DB::table('email_salution')->where('id',3)->first();
                    echo $report_salutation->description;
                    ?>
                </textarea>
              </div>
            </div>
            <div class="row" style="margin-top:10px">
              <div class="col-md-12">
                <label>Attachment</label>
              </div>
              <div class="col-md-12" id="email_attachments">
                  <img src="<?php echo URL::to('assets/pdf.jpg'); ?>" width="100" height="100">
                  <spam class="attachment_pdf_report"></spam>
                  <input type="hidden" name="hidden_attachment_pdf_report" id="hidden_attachment_pdf_report" value="">
              </div>
            </div>
            <div class="row" style="margin-top:10px">
              <div class="col-md-12">
                <label>Message</label>
              </div>
              <div class="col-md-12">
                <input type="submit" class="common_black_button" value="Send Mail" style="float:right">
              </div>
            </div>
         </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade view_item_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <a href="javascript:" class="download_letterpad fa fa-download" title="Download as Pdf"></a>
        <div class="letter_image">
            <img src="<?php echo URL::to('uploads/letterpad/no-image.jpg'); ?>" id="img_id" width="100%">
        </div>
      <div class="item_view_all">
          <div class="iteal_view_title">TaxRelevant Contracts Tax</div>
          <div class="iteal_view_sub_title">Payment Notification Acknowledgement</div>
          <div class="table_view">
              <table width="500px " align="center">
                  <tr>
                      <td height="40px" align="left" valign="top">Payment Notification ID:</td>
                      <td height="40px" align="left" valign="top"><span class="class_reference"></span></td>
                  </tr>
                  <tr>
                      <td height="40px" align="left" valign="top">Sub Tax Ref:</td>
                      <td height="40px" align="left" valign="top"><span class="class_rctno"></span></td>
                  </tr>
                  <tr>
                      <td height="40px" align="left" valign="top">Sub Name:</td>
                      <td height="40px" align="left" valign="top"><span class="class_subcontractor"></span></td>
                  </tr>
                  <tr>
                      <td height="40px" align="left" valign="top">Date:</td>
                      <td height="40px" align="left" valign="top"><span class="class_date"></span></td>
                  </tr>
                  <tr>
                      <td height="40px" align="left" valign="top">Gross Payment:</td>
                      <td height="40px" align="left" valign="top">€ <span class="class_gross"></span></td>
                  </tr>
                  <tr>
                      <td height="40px" align="left" valign="top">Net Payment:</td>
                      <td height="40px" align="left" valign="top">€ <span class="class_net"></span></td>
                  </tr>
                  <tr>
                      <td height="40px" align="left" valign="top">Deduction Amount</td>
                      <td height="40px" align="left" valign="top">€ <span class="class_deduction"></span></td>
                  </tr>
                  <tr>
                      <td colspan="2" height="50"></td>
                  </tr>
                  <tr>
                      <td colspan="2" height="50" align="left" valign="top"><b><span class="class_client"></span>: <span class="class_taxnumber"></span></b></td>                      
                  </tr>
                  <tr>
                      <td colspan="2" align="left" valign="top" height="100px">You have notified the Revenue Commissioners that you are about to make a relevant payment of €<span class="class_gross"></span> to the below subcontractor: 
                        <span style="font-weight: bold;;" class="class_subcontractor"></span>: <span class="class_rctno"></span></td>                      
                  </tr>
                  <tr>
                      <td colspan="2" height="40" align="left" valign="top">SUBMITTED TO REVENUE VIA ROS BY GBS & Co <a href="http://www.gbsco.ie" target="_blank">www.gbsco.ie</a></td>
                  </tr>
              </table>
          </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade modal-size-small importcsv" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
        <form name="import_form" action="<?php echo URL::to('user/rctimport_form'); ?>" method="post" enctype="multipart/form-data">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Import CSV</h4>
            </div>
            <div class="modal-body">
                <table width="100%">
                    <tr>
                        <td height="70" align="top" valign="left">
                            <select class="form-control" name="type_import" required>
                                <option value="">Select Type</option>
                                <option value="1">RCT Contract Notification</option>
                                <option value="2">RCT Payment Notification</option>
                                <option value="3">Home Renovation Incentive</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="file" class="form-control" name="file_import" id="file_import" required>
                            <input type="hidden" name="hidden_client_id" id="hidden_client_id" value="<?php echo $clientdetails->client_id; ?>">
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">                                
                <input type="submit" class="common_black_button" id="import_file_csv" value="Update">
            </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade modal-size-small" id="importerror_modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width:40% !important">
        <div class="modal-content">
        
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">ERROR LIST</h4>
            </div>
            <div class="modal-body">
                <?php if(Session::has('success_error')) { 
                  echo Session::get('success_error');
                }?>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/vendors/datatables/media/css/dataTables.bootstrap4.min.css') ?>">
    <script src="<?php echo URL::to('assets/vendors/datatables/media/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo URL::to('assets/vendors/datatables/media/js/dataTables.bootstrap4.min.js') ?>"></script>
    
    <script src="<?php echo URL::to('assets/vendors/datatables-responsive/js/dataTables.responsive.js') ?>"></script>
<div class="content_section" style="margin-bottom:200px">
  <div class="page_title">
    <h4 class="col-lg-4" style="padding: 0px;">
                GBS & Co RCT Tracker
            </h4>            
            <div class="col-lg-2 text-right" style="padding-right: 0px;">
              <input type="text" name="" placeholder="Search Subcontractor" class="form-control contractor_search_class">
              <input type="hidden" id="contractor_id">
            </div>
            <div class="col-lg-2 text-right" style="padding-right: 0px;"><input type="text" name="" placeholder="Search Sub Rct No" class="form-control sub_rct_class"></div>
            <div class="col-lg-2 text-right" style="padding-right: 0px;"><input type="text" name="" placeholder="Search Reference" class="form-control reference_class_autocomplete"></div>
            <div class="col-lg-2 text-right"  style="padding: 0px;" >

            <div class="modal fade" id="add_item_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                <div class="modal-dialog modal-sm" role="document" style="width:25%">
                 <div class="modal-content">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title label_class" id="myModalLabel">Add Item</h4>
                  </div>
                  <div class="modal-body notify_place_div">
                        <form id="add_item_form" action="<?php echo URL::to('user/rct_add_new_item'); ?>" method="post">
                          <div class="row" style="margin-right: 0px; margin-left: 0px;">
                              <label style="float:left">Choose Item Type : </label>
                              <div class="form-group">
                                <select name="item_type" class="form-control add_item_type" required placeholder="Choose Item Type">
                                  <option value="">Select Type</option>
                                  <option value="1">RCT Contract Notification</option>
                                    <option value="2">RCT Payment Notification</option>
                                    <option value="3">Home Renovation Incentive</option>
                                </select>
                              </div>
                              <label style="float:left">Enter Subcontractor : </label>
                              <div class="form-group">
                                <input type="text" name="add_subcontractor" id="add_subcontractor" class="form-control" value="" required placeholder="Enter Subcontractor">
                              </div>
                              <label style="float:left">Enter RCT No : </label>
                              <div class="form-group">
                                <input type="text" name="add_rct_no" id="add_rct_no" class="form-control" value="" required placeholder="Enter Rct No">
                              </div>
                              <label style="float:left">Enter Reference No : </label>
                              <div class="form-group">
                                <input type="text" name="add_reference" id="add_reference" class="form-control" value="" required placeholder="Enter Reference No">
                                <label id="add_reference_error" style="color:#f00;"></label>
                              </div>
                              <label style="float:left">Enter Date : </label>
                              <div class="form-group">
                                <input type="text" name="add_date" id="add_date" class="form-control datepicker" value="" required placeholder="Enter Date">
                              </div>
                              <label style="float:left">Enter Gross : </label>
                              <div class="form-group">
                                <input type="text" name="add_gross" id="add_gross" class="form-control" value="" required placeholder="Enter Gross">
                              </div>
                              <label style="float:left">Enter Rate : </label>
                              <div class="form-group">
                                <input type="text" name="add_rate" id="add_rate" class="form-control" value="" placeholder="Enter Rate" readonly>
                              </div>
                              <label style="float:left">Enter Deduction : </label>
                              <div class="form-group">
                                <input type="text" name="add_deduction" id="add_deduction" class="form-control" value="" required placeholder="Enter Deduction">
                              </div>
                              <label style="float:left">Enter Net : </label>
                              <div class="form-group">
                                <input type="text" name="add_net" id="add_net" class="form-control" value="" placeholder="Enter Net" readonly>
                              </div>
                              <label style="float:left">Enter Invoice : </label>
                              <div class="form-group">
                                <input type="text" name="add_invoice" id="add_invoice" class="form-control" value="" required placeholder="ENter Invoice">
                              </div>
                              <input type="hidden" name="hidden_client_id_add" id="hidden_client_id_add" value="<?php echo $clientdetails->client_id; ?>">
                              <input type="submit" name="add_item" id="add_item_button" class="common_black_button" style="font-size: 13px; font-weight: normal;;" value="Add Item" style="float:left;margin-top:15px;">
                          </div>  
                        </form>
                    </div>
                  </div>
                </div>
              </div>
              <div class="select_button">
                <ul style="margin-left: 10px;">
                  <li><a href="" class="" style="font-size: 13px; font-weight: normal;">Reset</a></li>
                  <li><a type="button" class="" data-toggle="modal" data-target="#add_item_modal" style="font-size: 13px; font-weight: normal;">Add New Item</a></li>
                </ul>
              </div>
            </div>
            <input type="hidden" value="<?php echo $clientdetails->client_id; ?>" id="client_id">
            <h5 class="col-lg-12" style="padding: 0px;"><?php echo $clientdetails->firstname; ?> - <?php echo $clientdetails->taxnumber; ?> | <span class="h6">Email: <a href="mailto:<?php echo $clientdetails->email; ?>" style="color:#337ab7; text-decoration: underline"><?php echo $clientdetails->email; ?></a></span></h5>
  </div>

  <div class="row">
                <div class="col-lg-12">                    
                    <div>
                     <?php
                     if(Session::has('message')) { ?>
                          <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
                     <?php }
                     if(Session::has('success_error')) { ?>
                          <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a> Please Click on the button to view the error list. <a href="javascript:" class="common_black_button" data-toggle="modal" data-target="#importerror_modal">View Error</a></p>
                     <?php }
                     
                     ?>
                     </div> 
                        <div class="nav-tabs-horizontal">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" href="javascript: void(0);" data-toggle="tab" data-target="#all" role="tab">All</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="javascript: void(0);" data-toggle="tab" data-target="#contract" role="tab">RCT Contract</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="javascript: void(0);" data-toggle="tab" data-target="#payment" role="tab">RCT Payment</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="javascript: void(0);" data-toggle="tab" data-target="#home_renovation" role="tab">Home Renovation Incentive</a>
                                </li>
                            </ul>
                            <div class="tab-content padding-vertical-20">
                                <div class="tab-pane active" id="all" role="tabpanel">
                                   <table class="table table-hover nowrap" id="client_expand" width="100%">
                                        <thead class="table-inverse">
                                        <tr style="background: #fff;">
                                            <th>#</th>
                                            <th>Type</th>
                                            <th>SubContractor</th>
                                            <th>Sub Rct No</th>                                
                                            <th>Reference</th>
                                            <th>Date</th>
                                            <th>Gross</th>
                                            <th>Rate</th>
                                            <th>Deduction</th>
                                            <th>Net</th>
                                            <th>Email</th>
                                            <th>Invoice</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody id="search_result_tbody">
                                        <?php
                                        $i=1;
                                        if(count($trackerlist)){
                                            foreach($trackerlist  as $single){
                                        ?>
                                        <tr>
                                            <td style="line-height: 35px;"><?php echo $i?></td>
                                            <td>
                                                <select class="form-control type_class" data-element="<?php echo $single->id; ?>">
                                                    <option value="">Select Type</option>
                                                    <?php
                                                    if(count($typelist)){
                                                        foreach($typelist as $type){
                                                    ?>
                                                        <option value="<?php echo $type->id?>" 
                                                            <?php echo ($type->id == $single->rct_type)?'selected':''; ?>
                                                            ><?php echo $type->type_name ?></option>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                <?php $name = DB::table('rct_type')->where('id',$single->rct_type)->first(); if(count($name)) { ?>
                                                <spam class="sort_class" style="display:none"><?php echo $name->type_name; ?></spam>
                                                <?php } else { ?>
                                                 <spam class="sort_class" style="display:none"></spam>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <input type="text" value="<?php echo $single->subcontractor?>" class="form-control sub_class" data-element="<?php echo $single->id; ?>" name="" placeholder="SubContractor">
                                                <span class="sort_class" style="display: none;"><?php echo $single->subcontractor?></span>
                                            </td>
                                            <td>
                                                <input type="text" value="<?php echo $single->rctno; ?>" data-element="<?php echo $single->id; ?>" class="form-control subrct_class" name="" placeholder="Sub Rct No">
                                                <spam class="sort_class" style="display:none"><?php echo $single->rctno; ?></spam>
                                            </td>
                                            <td>
                                                <input type="text" value="<?php echo $single->reference?>" data-element="<?php echo $single->id; ?>" class="form-control reference_class" name="" placeholder="RCT Contract/Payment ID">
                                                <span class="error_ref"></span>
                                                <span class="sort_class" style="display: none;"><?php echo $single->reference?></span>
                                            </td>
                                            <td>
                                                <input type="text" value="<?php if($single->date == "0000-00-00") { echo 'MM-DD-YYYY'; } else { echo date('m-d-Y', strtotime($single->date)); } ?>" class="form-control datepicker date_input" data-element="<?php echo $single->id; ?>" placeholder="Select Date" />
                                                <span class="sort_class" style="display: none;"><?php if($single->date == "0000-00-00") { echo 'MM-DD-YYYY'; } else { echo date('m-d-Y', strtotime($single->date)); } ?></span>
                                            </td>
                                            <td>
                                                <div class="form-control">€ :
                                                    <input type="text" style="border: 0px; outline: 0px;" value="<?php echo $single->gross?>" data-element="<?php echo $single->id; ?>" class="gross_class" name="" placeholder="Enter Gross">
                                                    <span class="sort_class" style="display: none"><?php echo $single->gross?></span>
                                                </div>      
                                            </td>
                                            <td>
                                                <div class="form-control" style="background: #f2f4f8; width: 65.5%">
                                                    <span class="rate_class">
                                                        <?php 
                                                            if(!empty($single->rate)) { echo(substr($single->rate,-2) == "00") ? substr($single->rate,0,-3).'%' : $single->rate.'%'; } 
                                                            else{ echo 'N/A'; }

                                                        ?>
                                                    </span>
                                                </div>           
                                            </td>
                                            <td>
                                                <div class="form-control">€ :
                                                    <input type="text" style="border: 0px; outline: 0px;" value="<?php echo $single->deduction?>"  data-element="<?php echo $single->id; ?>" class="deduction_class" name="" placeholder="Enter Deduction" >
                                                    <span class="deduction_class_span" style="display: none;"><?php echo $single->deduction?></span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-control" style="background: #f2f4f8; width: 57.5%">€ :
                                                    <span class="net_class">
                                                        <?php 
                                                            if(!empty($single->net)) { echo(substr($single->net,-2) == "00") ? substr($single->net,0,-3) : $single->net; }
                                                            else{ echo 'N/A'; }
                                                        ?>
                                                    </span>
                                                 </div>
                                            </td>
                                            <td>
                                                <div class="form-control" style="background: #f2f4f8"><?php if($single->email != "0000-00-00 00:00:00") { echo date('F d Y @ H:i',strtotime($single->email)); } else { echo 'MM-DD-YYYY @ HH:MM'; } ?></div>                                                
                                                <span style="display: none;"><?php echo $single->email?></span>
                                            </td>
                                            <td>
                                               <input type="text" class="form-control invoice_class" value="<?php echo $single->invoice?>" data-element="<?php echo $single->id; ?>" name="" placeholder="Enter Invoice">
                                               <span class="sort_class" style="display: none;"><?php echo $single->invoice?></span>
                                            </td>
                                            <td style="line-height: 40px;">
                                                <a href="#" data-toggle="modal" class="itemviewclass" id="<?php echo base64_encode($single->id); ?>" data-target=".view_item_modal" title="View"><i class="fa fa-pencil-square-o itemviewclass" id="<?php echo base64_encode($single->id); ?>" aria-hidden="true"></i></a>&nbsp;&nbsp;
                                                <a href="javascript:" class="itememailclass" id="<?php echo base64_encode($single->id); ?>" title="Send as Email"><i class="fa fa-envelope itememailclass" id="<?php echo base64_encode($single->id); ?>"></i></a>&nbsp;&nbsp;
                                                <a href="<?php echo URL::to('user/rctclient_expad_delete_item/'.base64_encode($single->id))?>" class="delete_item" title="Delete"><i class="fa fa-trash delete_item" aria-hidden="true"></i></a>
                                            </td>
                                        </tr>
                                        <?php
                                            $i++;
                                            }                                            
                                        }
                                        if($i == 1)
                                        {
                                          echo'<tr>
                                          <td align="center"></td>
                                          <td align="center"></td>
                                          <td align="center"></td>
                                          <td align="center"></td>
                                          <td align="center"></td>
                                          <td align="center"></td>
                                          <td align="center">Empty</td>
                                          <td align="center"></td>
                                          <td align="center"></td>
                                          <td align="center"></td>
                                          <td align="center"></td>
                                          <td align="center"></td>
                                          <td align="center"></td>
                                          </tr>';
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane" id="contract" role="tabpanel">
                                    RCT Contract
                                </div>
                                <div class="tab-pane" id="payment" role="tabpanel">
                                    RCT Payment
                                </div>
                                <div class="tab-pane" id="home_renovation" role="tabpanel">
                                    Home Renovation
                                </div>
                            </div>
                        </div>
                    
                </div>
            </div>  
            <div class="col-lg-12" style="padding: 0px; margin-top: 30px;">
              <a href="<?php echo URL::to('user/rctclients'); ?>" class="common_black_button">BACK</a>
              <nav aria-label="Page navigation" style="float:right">
                  <ul class="pagination" id="pagination"></ul>
              </nav>           
            </div>
            <div class="col-lg-12" style="padding: 20px 15px;">
            <div class="row">
                <div class="btn-group dropup">
                    <button type="button" class="common_black_button dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        CSV Report
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="" role="menu">
                        <li><a class="dropdown-item" href="<?php echo URL::to('user/rctexport_all_csv/'.base64_encode($clientdetails->client_id)); ?>">All</a></li>
                        <li><a class="dropdown-item" href="<?php echo URL::to('user/rctexport_csv_rctc/'.base64_encode($clientdetails->client_id)); ?>">RCT Contract</a></li>
                        <li><a class="dropdown-item" href="<?php echo URL::to('user/rctexport_csv_pctc/'.base64_encode($clientdetails->client_id)); ?>">RCT Payment</a></li>
                        <li><a class="dropdown-item" href="<?php echo URL::to('user/rctexport_csv_home/'.base64_encode($clientdetails->client_id)); ?>">Home Renovation Incentive</a></li>
                    </ul>
                </div>
                <div class="btn-group dropup">
                    <button type="button" class="common_black_button dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        PDF Report
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="" role="menu">
                        <li><a class="dropdown-item all_pdf" href="javascript: void(0)" id="<?php echo base64_encode($clientdetails->client_id) ?>">All</a></li>
                        <li><a class="dropdown-item rctc_pdf" id="<?php echo base64_encode($clientdetails->client_id) ?>" href="javascript: void(0)">RCT Contract</a></li>
                        <li><a class="dropdown-item rctp_pdf" id="<?php echo base64_encode($clientdetails->client_id) ?>" href="javascript: void(0)">RCT Payment</a>  </li>                      
                        <li><a class="dropdown-item home_pdf" id="<?php echo base64_encode($clientdetails->client_id) ?>" href="javascript: void(0)">Home Renovation Incentive</a></li>
                    </ul>
                </div>
                <div class="btn-group dropup">
                    <button type="button" class="common_black_button dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        Email Report
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="" role="menu">
                        <li><a class="dropdown-item all_pdf_email" href="javascript: void(0)" id="<?php echo base64_encode($clientdetails->client_id) ?>">All</a></li>
                        <li><a class="dropdown-item rctc_pdf_email" id="<?php echo base64_encode($clientdetails->client_id) ?>" href="javascript: void(0)">RCT Contract</a></li>
                        <li><a class="dropdown-item rctp_pdf_email" id="<?php echo base64_encode($clientdetails->client_id) ?>" href="javascript: void(0)">RCT Payment</a></li>
                        <li><a class="dropdown-item home_pdf_email" id="<?php echo base64_encode($clientdetails->client_id) ?>" href="javascript: void(0)">Home Renovation Incentive</a></li>
                    </ul>
                </div>
                <button type="button" class="common_black_button" data-toggle="modal" data-target=".importcsv">
                        Import
                </button>
            </div>
        </div>
</div>




    
        
    
<div class="modal_load"></div>
<script>
$('#add_item_form').validate({
    rules: {
        item_type : {required: true,},
        add_subcontractor : {required: true,},
        add_rct_no : {required: true,},
        add_reference : {required: true,},
        add_date : {required: true,},
        add_gross : {required: true,},
        add_deduction : {required: true,},
        add_invoice : {required: true,},
    },
    messages: {
        item_type : "Item Type is Required",
        add_subcontractor : "Subcontractor is Required",
        add_rct_no :  "RCT No is Required",
        add_reference :  "Reference is Required",
        add_date :  "Date is Required",
        add_gross :  "GROSS is Required",
        add_deduction :  "Deduction is Required",
        add_invoice :  "Invoice is Required",
    },
});
$(function(){
  $('#client_expand').DataTable({
      autoWidth: true,
      scrollX: true,
      fixedColumns: true,
      searching: false,
      bInfo: false,
      bPaginate: false
  });
  <?php if(count($trackerlist)){ ?>
  var obj = $('#pagination').twbsPagination({
      totalPages: <?php echo ceil($count_tracker/10); ?>,
      visiblePages: 5,
      onPageClick: function (event, page) {
          $.ajax({
            url:"<?php echo URL::to('user/rctpaginate_response'); ?>",
            type:"POST",
            data:{client_id:"<?php echo $clientdetails->client_id; ?>",page:page},
            success: function(result){
              $("#search_result_tbody").html(result);
              $(".datepicker" ).datepicker({ dateFormat: 'mm-dd-yy' });
            }
          });
      }
  });
  <?php } ?>
});
$(window).change(function(e) {
  if($(e.target).hasClass('type_class'))
  {
      var id = $(e.target).attr("data-element");
      var value = $(e.target).val();
      $.ajax({
          url:"<?php echo URL::to('user/rctclient_expand_type_update'); ?>",
          data:{value:value,id:id},
          type:"POST",
          success: function(result)
          {
              $(e.target).parent().find(".sort_class").text(result);
          }
      })
  }   
  if($(e.target).hasClass('date_input'))
  {
      var input_val = $(e.target).val();
      var id = $(e.target).attr('data-element');
      $.ajax({
          url:"<?php echo URL::to('user/rctclient_expand_date_update'); ?>",
          type:"POST",
          data:{date:input_val,id:id},
          success: function(result) {
              $(e.target).parent().find(".sort_class").text(result);
          }
      });
  }
});
$(window).click(function(e) {
  if($(e.target).hasClass('download_letterpad'))
  {
    var id = $(e.target).attr("data-element");
    $("#hidden_item_id").val(id);
    $.ajax({
        url:"<?php echo URL::to('user/rctclient_item_email')?>"+"/"+id,
        data:{id:id},
        type:"post",
        success:function(result){
          SaveToDisk("<?php echo URL::to('export/pdf/'); ?>/"+result,result);
        }

    });
  }
  if(e.target.id == "add_item_button")
  {
    e.preventDefault();
    var error = $("#add_reference_error").text();
    if(error == "")
    {
      $("#add_item_form").submit();
    }
    else{
      return false;
    }
  }
  if(e.target.id == "import_file_csv")
  {
    var file = $("#file_import").val();
    if (file=="") {

    }
    else{
        $(".importcsv").modal("hide");
        $('body').append('<div class="upload_img" style="width: 100%;height: 100%;z-index:1"><p class="upload_text">Uploading Please wait...</p><img src="<?php echo URL::to('assets/loading.gif'); ?>" width="100px" height="100px"><p class="upload_text">It might take upto 5 minutes to initialize...</p></div>');
    }
  }
  $(".datepicker" ).datepicker({ dateFormat: 'mm-dd-yy' });
  var ascending = false;
  if($(e.target).hasClass('delete_item'))
  {
    var r = confirm("Are You Sure want to Delete this Item?");
    if (r == true) {
       
    } else {
        return false;
    }
  }
  var doneTypingInterval = 100;  
  var typingTimer;  
  var $input = $('.sub_class');

  $input.on('keyup', function () {
    var input_val = $(this).val();
    var id = $(this).attr('data-element');
    var element = $(this);

    clearTimeout(typingTimer);
    typingTimer = setTimeout(doneTyping, doneTypingInterval,input_val,id,element);
  });
  $input.on('keydown', function () {
    clearTimeout(typingTimer);
  });
  function doneTyping (input,id,element) {
    $.ajax({
          url:"<?php echo URL::to('user/rctclient_expand_sub_update'); ?>",
          type:"POST",
          data:{value:input,id:id},
          success: function(result) {
              element.parent().find(".sort_class").text(result);
          }
        });
  }

  //subcontractor End
var typingTimerrct; 
  var $inputrct = $('.subrct_class');

  $inputrct.on('keyup', function () {
    var input_val = $(this).val();
    var id = $(this).attr('data-element');
    var element = $(this);

    clearTimeout(typingTimerrct);
    typingTimerrct = setTimeout(doneTypingrct, doneTypingInterval,input_val,id,element);
  });

  $inputrct.on('keydown', function () {
    clearTimeout(typingTimerrct);
  });

  function doneTypingrct (input,id,element) {
    $.ajax({
          url:"<?php echo URL::to('user/rctclient_expand_sub_rctno'); ?>",
          type:"POST",
          data:{value:input,id:id},
          success: function(result) {
              element.parent().find(".sort_class").text(result);
          }
        });
  }

  //subrec No start
  var typingTimerrct; 
  var $inputrct = $('.subrct_class');

  $inputrct.on('keyup', function () {
    var input_val = $(this).val();
    var id = $(this).attr('data-element');
    var element = $(this);

    clearTimeout(typingTimerrct);
    typingTimerrct = setTimeout(doneTypingrct, doneTypingInterval,input_val,id,element);
  });

  $inputrct.on('keydown', function () {
    clearTimeout(typingTimerrct);
  });

  function doneTypingrct (input,id,element) {
    $.ajax({
          url:"<?php echo URL::to('user/rctclient_expand_sub_rctno'); ?>",
          type:"POST",
          data:{value:input,id:id},
          success: function(result) {
              element.parent().find(".sort_class").text(result);
          }
        });
  }
  //subrec No END

  //Reference start
  var typingTimerreference; 
  var $inputreference = $('.reference_class');
  if($(e.target).hasClass('reference_class'))
  {
    var $inputreferenceval = $(e.target).val();
  }
  $inputreference.on('keyup', function () {
    $(this).parent().find(".error_ref").text('');
    var input_val = $(this).val();
    var id = $(this).attr('data-element');
    var element = $(this);

    clearTimeout(typingTimerreference);
    typingTimerreference = setTimeout(doneTypingreference, doneTypingInterval,input_val,id,element,$inputreferenceval);
  });

  $inputreference.on('keydown', function () {
    clearTimeout(typingTimerreference);
  });

  function doneTypingreference (input,id,element,inputreferenceval) {
    $.ajax({
          url:"<?php echo URL::to('user/rctclient_expand_reference'); ?>",
          type:"POST",
          data:{value:input,id:id},
          success: function(result) {
            if(result == "exists")
            {
              element.val('');
              element.parent().find(".sort_class").text('');
              element.parent().find(".error_ref").text('Reference No is already exists in this rct type.');
            }
            else{
              element.parent().find(".sort_class").text(result);
            }
          }
        });
  }

  //Reference start
  var typingTimeraddreference; 
  var $inputaddreference = $('#add_reference');
  $inputaddreference.on('keyup', function () {
    var input_val = $(this).val();
    var element = $(this);

    clearTimeout(typingTimeraddreference);
    typingTimeraddreference = setTimeout(doneTypingaddreference, doneTypingInterval,input_val,element);
  });

  $inputaddreference.on('keydown', function () {
    clearTimeout(typingTimeraddreference);
  });

  function doneTypingaddreference (input,element) {
    var item_type = $(".add_item_type").val();
    $.ajax({
          url:"<?php echo URL::to('user/rctclient_expand_check_reference'); ?>",
          type:"POST",
          data:{value:input,item_type:item_type},
          success: function(result) {
            if(result == 1)
            {
              $("#add_reference_error").text("Reference No already taken by this Rct Type");
            }
            else{
              $("#add_reference_error").text("");
            }
          }
        });
  }
  //Reference END

  //Reference start
  var typingTimergross; 
  var $inputgross = $('.gross_class');

  $inputgross.on('keyup', function (evee) {
    var input_val = $(this).val();
    var id = $(this).attr('data-element');
    var element = $(this);
    if (evee.which != 8 && evee.which != 0 && (evee.keyCode < 48 || evee.keyCode > 57) && (evee.keyCode < 96 || evee.keyCode > 105)) {
               return false;
    }
    clearTimeout(typingTimergross);
    typingTimergross = setTimeout(doneTypinggross, doneTypingInterval,input_val,id,element);
  });

  $inputgross.on('keydown', function (evee) {
    if (evee.which != 8 && evee.which != 0 && (evee.keyCode < 48 || evee.keyCode > 57) && (evee.keyCode < 96 || evee.keyCode > 105)) {
               return false;
    }
    clearTimeout(typingTimergross);
  });

  function doneTypinggross (input,id,element) {
    $.ajax({
      url:"<?php echo URL::to('user/rctclient_expand_gross'); ?>",
      type:"POST",
      dataType:"json", 
      data:{value:input,id:id},
      success: function(result) {
          element.parent().find(".sort_class").text(result['gross']);
          var last_2_char = result['rate'].substr(result['rate'].length - 2);
          if(last_2_char == "00")
          {
              element.parents("tr").find(".rate_class").html(result['rate'].slice(0,-3)+'%');
          }
          else{
              element.parents("tr").find(".rate_class").html(result['rate']+'%');
          }

          var last_2_net = result['net'].substr(result['net'].length - 2);
          if(last_2_net == "00")
          {
              element.parents("tr").find(".net_class").html(result['net'].slice(0,-3));
          }
          else{
              element.parents("tr").find(".net_class").html(result['net']);
          }
      }
    }); 
  }

  //Reference start
  var typingTimeraddgross; 
  var $inputaddgross = $('#add_gross');

  $inputaddgross.on('keyup', function (evee) {
    var input_val = $(this).val();
    var element = $(this);
    if (evee.which != 8 && evee.which != 0 && (evee.keyCode < 48 || evee.keyCode > 57) && (evee.keyCode < 96 || evee.keyCode > 105)) {
               return false;
    }
    clearTimeout(typingTimeraddgross);
    typingTimeraddgross = setTimeout(doneTypingaddgross, doneTypingInterval,input_val,element);
  });

  $inputaddgross.on('keydown', function (evee) {
    if (evee.which != 8 && evee.which != 0 && (evee.keyCode < 48 || evee.keyCode > 57) && (evee.keyCode < 96 || evee.keyCode > 105)) {
               return false;
    }
    clearTimeout(typingTimeraddgross);
  });

  function doneTypingaddgross (input,element) {
    var deduction = $("#add_deduction").val();
    $.ajax({
      url:"<?php echo URL::to('user/rctclient_expand_add_gross'); ?>",
      type:"POST",
      dataType:"json", 
      data:{value:input,deduction:deduction},
      success: function(result) {
          $("#add_rate").val(result['rate']);
          $("#add_net").val(result['net']);
      }
    }); 
  }

  //Reference start
  var typingTimeradddeduction; 
  var $inputadddeduction = $('#add_deduction');

  $inputadddeduction.on('keyup', function (evee) {
    var input_val = $(this).val();
    var element = $(this);
    if (evee.which != 8 && evee.which != 0 && (evee.keyCode < 48 || evee.keyCode > 57) && (evee.keyCode < 96 || evee.keyCode > 105)) {
               return false;
    }
    clearTimeout(typingTimeradddeduction);
    typingTimeradddeduction = setTimeout(doneTypingadddeduction, doneTypingInterval,input_val,element);
  });

  $inputadddeduction.on('keydown', function (evee) {
    if (evee.which != 8 && evee.which != 0 && (evee.keyCode < 48 || evee.keyCode > 57) && (evee.keyCode < 96 || evee.keyCode > 105)) {
               return false;
    }
    clearTimeout(typingTimeradddeduction);
  });

  function doneTypingadddeduction (input,element) {
    var gross = $("#add_gross").val();
    $.ajax({
      url:"<?php echo URL::to('user/rctclient_expand_add_deduction'); ?>",
      type:"POST",
      dataType:"json", 
      data:{value:input,gross:gross},
      success: function(result) {
          $("#add_rate").val(result['rate']);
          $("#add_net").val(result['net']);
      }
    }); 
  }
  //Reference END

  //Deduction start
  var typingTimerdeduction; 
  var $inputdeduction = $('.deduction_class');

  $inputdeduction.on('keyup', function (evee) {
    var input_val = $(this).val();
    var id = $(this).attr('data-element');
    var element = $(this);
    if (evee.which != 8 && evee.which != 0 && (evee.keyCode < 48 || evee.keyCode > 57) && (evee.keyCode < 96 || evee.keyCode > 105)) {
               return false;
    }
    clearTimeout(typingTimerdeduction);
    typingTimerdeduction = setTimeout(doneTypingdeduction, doneTypingInterval,input_val,id,element);
  });

  $inputdeduction.on('keydown', function (evee) {
    if (evee.which != 8 && evee.which != 0 && (evee.keyCode < 48 || evee.keyCode > 57) && (evee.keyCode < 96 || evee.keyCode > 105)) {
               return false;
    }
    clearTimeout(typingTimerdeduction);
  });

  function doneTypingdeduction (input,id,element) {
    $.ajax({
          url:"<?php echo URL::to('user/rctclient_expand_deduction'); ?>",
          type:"POST",
          dataType:"json",
          data:{value:input,id:id},
          success: function(result) {
              element.parent().find(".deduction_class_span").text(result['deduction']);
              var last_2_char = result['rate'].substr(result['rate'].length - 2);
              if(last_2_char == "00")
              {
                  element.parents("tr").find(".rate_class").html(result['rate'].slice(0,-3)+'%');
              }
              else{
                  element.parents("tr").find(".rate_class").html(result['rate']+'%');
              }

              var last_2_net = result['net'].substr(result['net'].length - 2);
              if(last_2_net == "00")
              {
                  element.parents("tr").find(".net_class").html(result['net'].slice(0,-3));
              }
              else{
                  element.parents("tr").find(".net_class").html(result['net']);
              }


          }
        });
  }
  //Deduction END

  //Deduction start
  var typingTimerinvoice; 
  var $inputinvoice = $('.invoice_class');

  $inputinvoice.on('keyup', function () {
    var input_val = $(this).val();
    var id = $(this).attr('data-element');
    var element = $(this);

    clearTimeout(typingTimerinvoice);
    typingTimerinvoice = setTimeout(doneTypinginvoice, doneTypingInterval,input_val,id,element);
  });

  $inputinvoice.on('keydown', function () {
    clearTimeout(typingTimerinvoice);
  });

  function doneTypinginvoice (input,id,element) {
    $.ajax({
          url:"<?php echo URL::to('user/rctclient_expand_invoice'); ?>",
          type:"POST",
          data:{value:input,id:id},
          success: function(result) {
              element.parent().find(".sort_class").text(result);
          }
        });
  }
  //Deduction END
  if($(e.target).hasClass('itemviewclass')){
    var id = $(e.target).attr("id");
    $.ajax({
        url:"<?php echo URL::to('user/rctclient_item_view')?>"+"/"+id,
        dataType:'json',
        data:{id:id},
        type:"post",
        success:function(result){
            $(".view_item_modal").show();
            $(".download_letterpad").attr("data-element",id);
            $('.class_subcontractor').html(result['subcontractor']);
            $('.class_rctno').html(result['rctno']);
            $('.class_date').html(result['date']);
            $('.class_deduction').html(result['deduction']);
            $('.class_gross').html(result['gross']);
            $('.class_invoice').html(result['invoice']);
            $('.class_net').html(result['net']);
            $('.class_rate').html(result['rate']);
            $('.class_reference').html(result['reference']);
            $('.class_client').html(result['client_name']);
            $('.class_taxnumber').html(result['client_taxnumber']);
            $("#img_id").attr("src",result['image']);
        }

    })
  }
  if($(e.target).hasClass('itememailclass')){
    if (CKEDITOR.instances.editor_1) CKEDITOR.instances.editor_1.destroy();
    var id = $(e.target).attr("id");
    $("#hidden_item_id").val(id);
    $.ajax({
        url:"<?php echo URL::to('user/rctclient_item_email')?>"+"/"+id,
        data:{id:id},
        type:"post",
        success:function(result){
          $(".client_email_modal").modal("show");
          $(".attachment_pdf").text(result);
          $("#hidden_attachment_pdf").val(result);
          CKEDITOR.replace('editor_1',
           {
            height: '150px',
           });
        }

    })
  }
  if($(e.target).hasClass('all_pdf')){
      var id = $(e.target).attr("id");    
      $("body").addClass("loading");
      $.ajax({
          url:"<?php echo URL::to('user/rctexport_all_pdf')?>"+"/"+id,
          data:{id:id},
          type:"post",
          success:function(result){

            $("body").removeClass("loading");
            SaveToDisk("<?php echo URL::to('export/pdf/'); ?>/"+result,result);
              return false; //this is critical to stop the click event which will trigger a normal file download
              
          }

      })
  }
  if($(e.target).hasClass('rctc_pdf')){
      var id = $(e.target).attr("id");    
      $("body").addClass("loading");
      $.ajax({
          url:"<?php echo URL::to('user/rctexport_pdf_rctc')?>"+"/"+id,
          data:{id:id},
          type:"post",
          success:function(result){

            $("body").removeClass("loading");
            SaveToDisk("<?php echo URL::to('export/pdf/'); ?>/"+result,result);
              return false; //this is critical to stop the click event which will trigger a normal file download
              
          }

      })
  }  
  if($(e.target).hasClass('rctp_pdf')){
      var id = $(e.target).attr("id");    
      $("body").addClass("loading");
      $.ajax({
          url:"<?php echo URL::to('user/rctexport_pdf_pctc')?>"+"/"+id,
          data:{id:id},
          type:"post",
          success:function(result){

            $("body").removeClass("loading");
            SaveToDisk("<?php echo URL::to('export/pdf/'); ?>/"+result,result);
              return false; //this is critical to stop the click event which will trigger a normal file download
              
          }

      })
  }
  if($(e.target).hasClass('home_pdf')){
      var id = $(e.target).attr("id");    
      $("body").addClass("loading");
      $.ajax({
          url:"<?php echo URL::to('user/rctexport_pdf_home')?>"+"/"+id,
          data:{id:id},
          type:"post",
          success:function(result){

            $("body").removeClass("loading");
            SaveToDisk("<?php echo URL::to('export/pdf/'); ?>/"+result,result);
              return false; //this is critical to stop the click event which will trigger a normal file download
              
          }

      })
  } 
  if($(e.target).hasClass('all_pdf_email')){
    if (CKEDITOR.instances.editor_2) CKEDITOR.instances.editor_2.destroy();
      var id = $(e.target).attr("id");    
      $("body").addClass("loading");
      $.ajax({
          url:"<?php echo URL::to('user/rctexport_all_pdf')?>"+"/"+id,
          data:{id:id},
          type:"post",
          success:function(result){
            $("body").removeClass("loading");
             $(".email_report_modal").modal("show");
             $(".attachment_pdf_report").text(result);
             $("#hidden_attachment_pdf_report").val(result);
             CKEDITOR.replace('editor_2',
             {
              height: '150px',
             });
          }

      })
  }
  if($(e.target).hasClass('rctc_pdf_email')){
    if (CKEDITOR.instances.editor_2) CKEDITOR.instances.editor_2.destroy();
      var id = $(e.target).attr("id");    
      $("body").addClass("loading");
      $.ajax({
          url:"<?php echo URL::to('user/rctexport_pdf_rctc')?>"+"/"+id,
          data:{id:id},
          type:"post",
          success:function(result){
            $("body").removeClass("loading");
            $(".email_report_modal").modal("show");
             $(".attachment_pdf_report").text(result);
             $("#hidden_attachment_pdf_report").val(result);
             CKEDITOR.replace('editor_2',
             {
              height: '150px',
             });
          }

      })
  }  
  if($(e.target).hasClass('rctp_pdf_email')){
    if (CKEDITOR.instances.editor_2) CKEDITOR.instances.editor_2.destroy();
      var id = $(e.target).attr("id");    
      $("body").addClass("loading");
      $.ajax({
          url:"<?php echo URL::to('user/rctexport_pdf_pctc')?>"+"/"+id,
          data:{id:id},
          type:"post",
          success:function(result){
            $("body").removeClass("loading");
            $(".email_report_modal").modal("show");
             $(".attachment_pdf_report").text(result);
             $("#hidden_attachment_pdf_report").val(result);
             CKEDITOR.replace('editor_2',
             {
              height: '150px',
             });
          }

      })
  }
  if($(e.target).hasClass('home_pdf_email')){
    if (CKEDITOR.instances.editor_2) CKEDITOR.instances.editor_2.destroy();
      var id = $(e.target).attr("id");    
      $("body").addClass("loading");
      $.ajax({
          url:"<?php echo URL::to('user/rctexport_pdf_home')?>"+"/"+id,
          data:{id:id},
          type:"post",
          success:function(result){
            $("body").removeClass("loading");
            $(".email_report_modal").modal("show");
             $(".attachment_pdf_report").text(result);
             $("#hidden_attachment_pdf_report").val(result);
             CKEDITOR.replace('editor_2',
             {
              height: '150px',
             });
          }

      })
  } 

  
})

$(document).ready(function() {
  
  $(".datepicker" ).datepicker({ dateFormat: 'mm-dd-yy' });
  var clientid = $("#client_id").val();
     $(".contractor_search_class").autocomplete({
        source: function(request, response) {
            $.ajax({
                url:"<?php echo URL::to('user/rctcontractor_search'); ?>",
                dataType: "json",
                data: {
                    term : request.term, clientid:clientid
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        minLength: 1,
        select: function( event, ui ) {
          $("#contractor_id").val(ui.item.id);
          $.ajax({
            url:"<?php echo URL::to('user/rctcontractor_search_select'); ?>",
            data:{value:ui.item.value, clientid:clientid},
            success: function(result){
              $("#search_result_tbody").html(result);
              $(".datepicker" ).datepicker({ dateFormat: 'mm-dd-yy' });
              $("#pagination").hide();
            }
          })
        }
    }); 
     $(".sub_rct_class").autocomplete({
        source: function(request, response) {
            $.ajax({
                url:"<?php echo URL::to('user/rctsub_rct_search'); ?>",
                dataType: "json",
                data: {
                    term : request.term, clientid:clientid
                },
                success: function(data) {
                    response(data);
                   
                }
            });
        },
        minLength: 1,
        select: function( event, ui ) {
          $("#contractor_id").val(ui.item.id);
          $.ajax({
            url:"<?php echo URL::to('user/rctsub_rct_select'); ?>",
            data:{value:ui.item.value},
            success: function(result){
              $("#search_result_tbody").html(result);
              $(".datepicker" ).datepicker({ dateFormat: 'mm-dd-yy' });
              $("#pagination").hide();
            }
          })
        }
    });
     $(".reference_class_autocomplete").autocomplete({
        source: function(request, response) {
            $.ajax({
                url:"<?php echo URL::to('user/rctreference_search'); ?>",
                dataType: "json",
                data: {
                    term : request.term, clientid:clientid
                },
                success: function(data) {
                    response(data);
                   
                }
            });
        },
        minLength: 1,
        select: function( event, ui ) {
          $("#contractor_id").val(ui.item.id);
          $.ajax({
            url:"<?php echo URL::to('user/rctreference_select'); ?>",
            data:{value:ui.item.value},
            success: function(result){
              $("#search_result_tbody").html(result);
              $(".datepicker" ).datepicker({ dateFormat: 'mm-dd-yy' });
              $("#pagination").hide();
            }
          })
        }
    });
     
});
</script>
@stop
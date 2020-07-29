@extends('userheader')

@section('content')

<script src='<?php echo URL::to('assets/js/table-fixed-header_cm.js'); ?>'></script>

<style>
.img_div{ z-index:9999; }
#task_body_std>tr>td,#task_body_cmp>tr>td,#task_body_enh>tr>td
{
  min-height: 700px !important;
  max-height: 700px !important;
  height: 315px !important;
}
.scroll_attachment_div { 
    max-height: 100px;
    overflow-y: scroll;
    padding-left: 10px;
    line-height: 25px;
    scrollbar-color: #000 #fff;
    scrollbar-width: thin;
}
.disclose_label{ width:300px; }
.option_label{width:100%;}
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

table{

      border-collapse: separate !important;

}

.fa-plus,.fa-pencil-square{

  cursor:pointer;

}

.error_files_notepad

{

  color:#f00;

}

.notepad_div {

    border: 1px solid #000;

    width: 400px;

    position: absolute;

    height: 250px;

    background: #dfdfdf;

    display: none;
    z-index: 999999 !important;
}

.notepad_div textarea{

  height:212px;

}

@-moz-document url-prefix('') {

    .special_td{

        margin-top:-1px !important;

        width: 105px !important;

    }

    .special_div{

      width:630% !important;

    }

}

.fa-minus-square{



  margin-left:15px;



  cursor:pointer;



}



.text_checkbox{



      margin-top: 10px;



    color: #000;



    font-weight: 700;



}



.comments_input{



    margin-top: 10px;



    width: 235%;



    height: 200px !important;



}



.uname_input{



  margin-top: 0px;



}



.task_email_input{



  margin-top: 0px;



}



.date_input{



  margin-top: 0px;



  margin-bottom: 10px



}



.time_input{



  margin-top: 0px;



}



.footer_row{



   display:none !important;



}



.modal{



  z-index:99999 !important;



}



#alert_modal{



  z-index:9999999 !important;



}



#alert_modal_edit{



  z-index:9999999 !important;



}



.attach_align{



  text-align: left !important;



}



.copy_label{



      font-size: 12px;



    color: #000;



    text-align: left;



    padding: 3px 14px;



}



.fileattachment{



  font-weight:800;



  color:#fff !important;



}



.fileattachment:hover{



  font-weight:800;



  color:#fff !important;



}



.fa-sort{



  cursor: pointer;



}



.table_bg tbody tr td{



  padding:8px;



}



.table_bg thead tr th{



  padding:8px;



}



.email_sort_std,.email_sort_enh,.email_sort_cmp{



  width:10% !important;



}



.task_sort_std,.task_sort_enh,.task_sort_cmp{



  text-align: left !important;



}



.task_sort_std_val,.task_sort_enh_val,.task_sort_cmp_val{



  text-align: left !important;



}



.task_tr_std,.task_tr_enh,.task_tr_cmp



{



  vertical-align: top !important;



}



.page_title{



  background: #ffa12d !important;



  margin-bottom: 0px !important;



}



.button_top_right ul{



      margin: 0px 0px 0px 0px !important;



}



.error_files{



  color:#f00;



  font-weight:800;



}



.email_unsent_label{



  width:100%;



}



.download_div



{



    position: absolute;



    top: 34px;



    left:47%;



    width: 28%;



    background: #ff0;



    padding: 9px;



    line-height: 31px;



}



.notify_div



{



    position: absolute;



    top: 34px;



    left:67%;



    width: 28%;



    background: #ff0;



    padding: 9px;



    line-height: 31px;



}



.close_xmark{



       position: absolute;



    right: 0px;



    top: 0;



    font-weight: 800;



    padding: 0px 5px;



    background: #000000;



    color: #ffcd44;



    font-size: 10px;



}



.close_xmark:focus, .close_xmark:hover {



    color: #641500;



    text-decoration: none !important;



}



.download_button



{



      background: #000;



    color: #fff;



    padding: 5px 10px;



    float: left;



    font-size: 13px;



    font-weight: normal;



    text-transform: none;



}



.download_radio{



        width: 85%;



    clear: both;



    float: left;



    padding: 5px;



    margin-left: 15px;



    border-bottom: 1px solid;



}



.download_radio:hover{



            width: 85%;



    clear: both;



    float: left;



    padding: 5px;



    margin-left: 15px;



    border-bottom: 1px solid #000;



    background: #000;



    color: #fff;



}



.notify_radio{



        width: 85%;



    clear: both;



    float: left;



    padding: 5px;



    margin-left: 15px;



    border-bottom: 1px solid;



}



.notify_radio:hover{



            width: 85%;



    clear: both;



    float: left;



    padding: 5px;



    margin-left: 15px;



    border-bottom: 1px solid #000;



    background: #000;



    color: #fff;



}



.modal_load {



    display:    none;



    position:   fixed;



    z-index:    1000;



    top:        0;



    left:       0;



    height:     100%;



    width:      100%;



    background: rgba( 255, 255, 255, .8 ) 



                url(<?php echo URL::to('assets/images/loading.gif'); ?>) 



                50% 50% 



                no-repeat;



}



body.loading {



    overflow: hidden;   



}



body.loading .modal_load {



    display: block;



}



.ui-widget{z-index: 999999999}



.form-control[readonly]{background: #eaeaea !important}



</style>

<script src="<?php echo URL::to('ckeditor/ckeditor.js'); ?>"></script>
<script src="<?php echo URL::to('ckeditor/samples/js/sample.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo URL::to('ckeditor/samples/css/samples.css'); ?>">
<link rel="stylesheet" href="<?php echo URL::to('ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css'); ?> ">



<?php 

  $admin_details = Db::table('admin')->first();

  $admin_cc = $admin_details->task_cc_email;

?>            
<div class="modal fade" id="show_email_sent_popup" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog" role="document" style="z-index: 999999">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Email Sent Options</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <input type="radio" name="email_sent_options" class="email_sent_options" id="option_a" value="a"><label for="option_a" class="option_label">Fix an Error Created In House
</label>
            <input type="radio" name="email_sent_options" class="email_sent_options" id="option_b" value="b"><label for="option_b" class="option_label">Fix an Error by Client or Implement a client Requested Change</label>

            <input type="radio" name="email_sent_options" class="email_sent_options" id="option_c" value="c"><label for="option_c" class="option_label">Combined In House and Client Prompted adjustments</label>
          </div>
        </div>
      </div>
      <div class="modal-footer">
          <input type="hidden" class="btn btn-primary common_black_button" id="hidden_task_id_val" value="">
        <input type="button" class="btn btn-primary common_black_button" id="email_option_submit" value="Submit">
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="alert_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">

  <div class="modal-dialog" role="document" style="z-index: 999999">

    <div class="modal-content">

      <div class="modal-header">

        <h4 class="modal-title" id="myModalLabel">Alert</h4>

      </div>

      <div class="modal-body">

        <div class="row">

          <div class="col-md-8">

            <label>Do you want to update the Company/Task Name?</label>

          </div>

          <div class="col-md-4">

            <input type="radio" name="company_update" class="company_update" id="company_yes" value="1"><label for="company_yes">Yes</label>

            <input type="radio" name="company_update" class="company_update" id="company_no" value="0"><label for="company_no">No</label>

          </div>

        </div>

        <div class="row">

          <div class="col-md-8">

            <label>Do you want to update the Employer Number?</label>

          </div>

          <div class="col-md-4">

            <input type="radio" name="emp_update" class="emp_update" id="emp_yes" value="1"><label for="emp_yes">Yes</label>

            <input type="radio" name="emp_update" class="emp_update" id="emp_no" value="0"><label for="emp_no">No</label>

          </div>

        </div>

        <div class="row">

          <div class="col-md-8">

            <label>Do you want to update the Email Address?</label>

          </div>

          <div class="col-md-4">

            <input type="radio" name="email_update" class="email_update" id="email_yes" value="1"><label for="email_yes">Yes</label>

            <input type="radio" name="email_update" class="email_update" id="email_no" value="0"><label for="email_no">No</label>

          </div>

        </div>

        <div class="row">

          <div class="col-md-8">

            <label>Do you want to update the Salutation?</label>

          </div>

          <div class="col-md-4">

            <input type="radio" name="salutation_update" class="salutation_update" id="salutation_yes" value="1"><label for="salutation_yes">Yes</label>

            <input type="radio" name="salutation_update" class="salutation_update" id="salutation_no" value="0"><label for="salutation_no">No</label>

          </div>

        </div>

      </div>

      <div class="modal-footer">

        <input type="button" class="btn btn-primary common_black_button" id="alert_submit" value="Submit">

      </div>

    </div>

  </div>

</div>

<div class="modal fade" id="alert_modal_edit" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">

  <div class="modal-dialog" role="document" style="z-index: 999999">

    <div class="modal-content">

      <div class="modal-header">

        <h4 class="modal-title" id="myModalLabel">Alert</h4>

      </div>

      <div class="modal-body">

        <div class="row">

          <div class="col-md-8">

            <label>Do you want to update the Company/Task Name?</label>

          </div>

          <div class="col-md-4">

            <input type="radio" name="company_update_edit" class="company_update_edit" id="company_yes_edit" value="1"><label for="company_yes_edit">Yes</label>

            <input type="radio" name="company_update_edit" class="company_update_edit" id="company_no_edit" value="0"><label for="company_no_edit">No</label>

          </div>

        </div>

        <div class="row">

          <div class="col-md-8">

            <label>Do you want to update the Employer Number?</label>

          </div>

          <div class="col-md-4">

            <input type="radio" name="emp_update_edit" class="emp_update_edit" id="emp_yes_edit" value="1"><label for="emp_yes_edit">Yes</label>

            <input type="radio" name="emp_update_edit" class="emp_update_edit" id="emp_no_edit" value="0"><label for="emp_no_edit">No</label>

          </div>

        </div>

        <div class="row">

          <div class="col-md-8">

            <label>Do you want to update the Email Address?</label>

          </div>

          <div class="col-md-4">

            <input type="radio" name="email_update_edit" class="email_update_edit" id="email_yes_edit" value="1"><label for="email_yes_edit">Yes</label>

            <input type="radio" name="email_update_edit" class="email_update_edit" id="email_no_edit" value="0"><label for="email_no_edit">No</label>

          </div>

        </div>

        <div class="row">

          <div class="col-md-8">

            <label>Do you want to update the Salutation?</label>

          </div>

          <div class="col-md-4">

            <input type="radio" name="salutation_update_edit" class="salutation_update_edit" id="salutation_yes_edit" value="1"><label for="salutation_yes_edit">Yes</label>

            <input type="radio" name="salutation_update_edit" class="salutation_update_edit" id="salutation_no_edit" value="0"><label for="salutation_no_edit">No</label>

          </div>

        </div>

      </div>

      <div class="modal-footer">

        <input type="button" class="btn btn-primary common_black_button" id="alert_submit_edit" value="Submit">

      </div>

    </div>

  </div>

</div>

<div class="modal fade notify_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">

  <div class="modal-dialog" role="document">

    

    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        <h4 class="modal-title" id="myModalLabel">Notify All Option</h4>

      </div>

      <div class="modal-body notify_place_div">

          

      </div>

      <div class="modal-footer">

        <input type="hidden" id="notify_type" value="">

        <input type="button" class="btn btn-primary common_black_button" id="email_notify" value="Email Notify Options">

      </div>

    </div>

  </div>

</div>

<div class="modal fade createnewtask" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">

  <div class="modal-dialog modal-lg" role="document" style="width:80%">

    <form action="<?php echo URL::to('user/add_new_task_month'); ?>" method="post" >

    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        <h4 class="modal-title" id="myModalLabel">Create New Task</h4>

      </div>

      <div class="modal-body">

          <table class="table">

            <tr>

              <td>

                <label>Choose Company Name</label>

                <input type="text" class="form-control common_input client_search_class" required placeholder="Choose Company Name">

                <input type="hidden" id="client_search" name="clientid" />

              </td>

              <td>

                <input type="hidden" value="<?php echo $monthid->year ?>" name="year">

                <input type="hidden" value="<?php echo $monthid->month ?>" name="month">

                <input type="hidden" value="<?php echo $monthid->month_id ?>" name="monthid">

                <label>Enter Task Name</label>

                <input type="input" placeholder="Enter Task Name" class="form-control common_input" name="tastname" id="taskname" required>

              </td>

              <td></td>

              <td></td>

            </tr>

            <tr>

              <td>

                <label>Tax Reg1</label>

                <input type="text" class="form-control common_input tax_reg1class" value="" name="" placeholder="Tax Reg1" readonly>

              </td>

              <td>

                <label>Select Category</label>

                <select class="form-control common_input" name="classified" required>

                    <option value="">Select Category</option>

                    <?php

                    if(count($classifiedlist)){

                      foreach ($classifiedlist as $classified) {

                    ?>

                        <option value="<?php echo $classified->classified_id ?>"><?php echo $classified->classified_name?></option>

                    <?php

                      }

                    }

                    ?>

                </select>

              </td>

              <td>

                <label>Employer Number</label>

                <input type="text" name="task_enumber" class="common_input form-control" placeholder="Employer Number" id="task_enumber" required>

              </td>

              <td rowspan="3">

                <table style="margin-top:30px">

                  <tr>

                    <td><input type="radio" name="enterhours" value="0" required> <label>Enter Hours</label></td>

                    <td><input type="radio" name="enterhours" value="2" required> <label>N/A </label></td>

                  </tr>

                  <tr>

                    <td><input type="radio" name="holiday" value="0" required> <label>Holiday Pay</label></td>

                    <td><input type="radio" name="holiday" value="2" required> <label>N/A </label></td>

                  </tr>

                  <tr>

                    <td><input type="radio" name="process" value="0" required> <label>Process Payroll</label></td>

                    <td><input type="radio" name="process" value="2" required> <label>N/A </label></td>

                  </tr>

                  <tr>

                    <td><input type="radio" name="payslips" value="0" required> <label>Upload Payslips</label></td>

                    <td><input type="radio" name="payslips" value="2" required> <label>N/A </label></td>

                  </tr>

                  <tr>

                    <td><input type="radio" name="email" value="0" required> <label>Email Payslip</label></td>

                    <td><input type="radio" name="email" value="2" required> <label>N/A </label></td>

                  </tr>

                  <tr>

                    <td><input type="radio" name="uploadd" value="0" required> <label>Upload Report</label></td>

                    <td><input type="radio" name="uploadd" value="2" required> <label>N/A </label></td>

                  </tr>

                </table>

              </td>

            </tr>

            <tr>

              <td>

                <label>Primary Email</label>

                <input type="text" class="form-control common_input primaryemail_class" name="" value="" placeholder="Primary Email" readonly>

              </td>

              <td>

                <label>Enter Email</label>

                <input type="email" name="task_email" id="task_email_create" class="common_input form-control" placeholder="Enter Email" required>

              </td>

              <td>

                <label>Enter Secondary Email</label>

                <input type="email" name="secondary_email" class="common_input form-control" placeholder="Enter Secondary Email">

              </td>

              

            </tr>

            <tr>

              <td>

                <label>Firstname</label>

                <input type="text" class="form-control common_input firstname_class" name="" value="" placeholder="Firstname" readonly>

              </td>

              <td> <label>Enter Salutation</label><textarea name="salutation" id="salutation_create" class="common_input form-control" placeholder="Enter Salutation" required></textarea></td>

              <td>

                <label>Enter Network Location</label>

                <input type="text" name="location" class="common_input form-control" placeholder="Enter Network Location" required>

              </td>

            </tr>

            <tr>

              <td colspan="3"> <label>P30 Section : </label></td>

            </tr>

            <tr>

              <td>

                <label>Select Task Level</label>

                <?php $levels = DB::table('p30_tasklevel')->where('status',0)->orderBy('name','desc')->get(); ?>

                <select class="form-control tasklevel_input" name="tasklevel" required>

                    <option value="">Select Task Level</option>

                    <?php

                    if(count($levels)){

                      foreach ($levels as $level) {

                    ?>

                        <option value="<?php echo $level->id ?>"><?php echo $level->name; ?></option>

                    <?php

                      }

                    }

                    ?>

                </select>

              </td>

              <td>

                <div style="margin-top:28px">

                  <label>Email : </label>

                  <input type="radio" name="email_p30" value="1" required> <label>Yes</label>

                  <input type="radio" name="email_p30" value="0" required> <label>No</label>

                </div>

              </td>

              <td>

                <div style="margin-top:28px">

                  <label>Pay : </label>

                  <input type="radio" name="pay_p30" value="1" required> <label>Yes</label>

                  <input type="radio" name="pay_p30" value="0" required> <label>No</label>

                </div>

              </td>

            </tr>

          </table>

      </div>

      <div class="modal-footer">

        <input type="hidden" name="hidden_client_id" id="hidden_client_id" value="">

        <input type="hidden" name="hidden_client_emp" id="hidden_client_emp" value="">

        <input type="hidden" name="hidden_client_salutation" id="hidden_client_salutation" value="">

        <input type="submit" class="btn btn-primary common_black_button" value="Create New">

      </div>

    </div>

    </form>

  </div>

</div>

<div class="modal fade edit_task_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">

  <div class="modal-dialog modal-lg" role="document" style="width:80%">

    <form action="<?php echo URL::to('user/edit_task_details'); ?>" method="post" >

    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        <h4 class="modal-title" id="myModalLabel">Edit task name</h4>

      </div>

      <div class="modal-body">

          <table class="table">

            <tr>

              <td>

                <label>Choose Company Name</label>

                <input type="text" class="form-control common_input client_search_class_edit companyclass" required placeholder="Choose Company Name">

                <input type="hidden" id="client_search_edit" name="clientid" />

              </td>

              <td>

                <input type="hidden" class="hidden_taskname_id" id="hidden_taskname_id" name="hidden_taskname_id" value="">

                <label>Enter Task Name</label>

                <input type="text" name="task_name" id="taskname_edit" class="task_name form-control input-sm" value="" required>

              </td>

              <td></td>

              <td></td>

            </tr>

            <tr>

              <td>

                <label>Tax Reg1</label>

                <input type="text" class="form-control common_input tax_reg1class_edit" value="" name="" placeholder="Tax Reg1" readonly>

              </td>

              <td>

                <label>Select Category</label>

                <select name="task_category" class="task_category form-control input-sm" required>

                  <option value="">Select Category</option>

                  <?php

                  if(count($classifiedlist)){

                    foreach ($classifiedlist as $classified) { ?>

                      <option value="<?php echo $classified->classified_id ?>"><?php echo $classified->classified_name?></option>

                    <?php

                    }

                  }

                  ?>

                </select>

              </td>

              <td>

                <label>Employer Number</label>

                <input type="text" name="enumber" id="enumber_edit" class="enumberclass form-control input-sm" value="" required>

              </td>

              <td rowspan="3">

                <table class="table">

                  <tr>

                    <td><input type="radio" name="enterhours_edit" id="hours_enter" value="0" required> <label>Enter Hours</label></td>

                    <td><input type="radio" name="enterhours_edit" id="hours_na" value="2" required> <label>N/A </label></td>

                  </tr>

                  <tr>

                    <td><input type="radio" name="holiday_edit" id="holiday_enter" value="0" required> <label>Holiday Pay</label></td>

                    <td><input type="radio" name="holiday_edit" id="holiday_na" value="2" required> <label>N/A </label></td>

                  </tr>

                  <tr>

                    <td><input type="radio" name="process_edit" id="process_enter" value="0" required> <label>Process Payroll</label></td>

                    <td><input type="radio" name="process_edit" id="process_na" value="2" required> <label>N/A </label></td>

                  </tr>

                  <tr>

                    <td><input type="radio" name="payslips_edit" id="payslips_enter" value="0" required> <label>Upload Payslips</label></td>

                    <td><input type="radio" name="payslips_edit" id="payslips_na" value="2" required> <label>N/A </label></td>

                  </tr>

                  <tr>

                    <td><input type="radio" name="email_edit" id="email_enter" value="0" required> <label>Email Payslip</label></td>

                    <td><input type="radio" name="email_edit" id="email_na" value="2" required> <label>N/A </label></td>

                  </tr>

                  <tr>

                    <td><input type="radio" name="uploadd_edit" id="report_enter" value="0" required> <label>Upload Report</label></td>

                    <td><input type="radio" name="uploadd_edit" id="report_na" value="2" required> <label>N/A </label></td>

                  </tr>

                </table>

              </td>

            </tr>

            <tr>

              <td>

                <label>Primary Email</label>

                <input type="text" class="form-control common_input primaryemail_class_edit" name="" value="" placeholder="Primary Email" readonly>

              </td>

              <td>

                <label>Enter Email</label>

                <input type="text" name="task_email_edit" id="task_email_edit" class="task_email_edit form-control input-sm" value="" required>

              </td>

              <td>

                <label>Enter Secondary Email</label>

                <input type="text" name="secondary_email_edit" class="secondary_email_edit form-control input-sm" value="">

              </td>

              

            </tr>

            <tr>

              <td>

                <label>Firstname</label>

                <input type="text" class="form-control common_input firstname_class_edit" name="" value="" placeholder="Firstname" readonly>

              </td>

              <td> <label>Enter Salutation</label><textarea name="salutation_edit" id="salutation_edit" class="salutation_edit common_input form-control" placeholder="Enter Salutation" required></textarea></td>

              <td>

                <label>Enter Network Location</label>

                <input type="text" name="task_network" class="task_network form-control input-sm" value="" required>

              </td>

            </tr>

            <tr>

              <td colspan="3"> <label>P30 Section : </label></td>

            </tr>

            <tr>

              <td>

                <label>Select Task Level</label>

                <?php $levels = DB::table('p30_tasklevel')->where('status',0)->orderBy('name','desc')->get(); ?>

                <select class="form-control tasklevel_edit" name="tasklevel_edit" required>

                    <option value="">Select Task Level</option>

                    <?php

                    if(count($levels)){

                      foreach ($levels as $level) {

                    ?>

                        <option value="<?php echo $level->id ?>"><?php echo $level->name; ?></option>

                    <?php

                      }

                    }

                    ?>

                </select>

              </td>

              <td>

                <div style="margin-top:28px">

                  <label>Email : </label>

                  <input type="radio" name="email_p30_edit" id="p30_email_yes" value="1" required> <label>Yes</label>

                  <input type="radio" name="email_p30_edit" id="p30_email_no" value="0" required> <label>No</label>

                </div>

              </td>

              <td>

                <div style="margin-top:28px">

                  <label>Pay : </label>

                  <input type="radio" name="pay_p30_edit" id="p30_pay_yes" value="1" required> <label>Yes</label>

                  <input type="radio" name="pay_p30_edit" id="p30_pay_no" value="0" required> <label>No</label>

                </div>

              </td>

            </tr>

          </table>

      </div>

      <div class="modal-footer">

        <input type="hidden" name="hidden_client_id_edit" id="hidden_client_id_edit" value="">

        <input type="hidden" name="hidden_client_emp_edit" id="hidden_client_emp_edit" value="">

        <input type="hidden" name="hidden_client_salutation_edit" id="hidden_client_salutation_edit" value="">



        <input type="submit" class="btn btn-primary" value="Submit" style="background: #000; color:#fff">

      </div>

    </div>

    </form>

  </div>

</div>

<div class="modal fade copy_task" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">

  <div class="modal-dialog modal-sm" role="document">

    <form action="<?php echo URL::to('user/copy_task'); ?>" method="post" >

    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        <h4 class="modal-title" id="myModalLabel">Copy task</h4>

      </div>

      <div class="modal-body">

          <input type="hidden" name="hidden_task_id" id="hidden_task_id" value="">

          <input type="hidden" name="hidden_copy_year" id="hidden_copy_year" value="">

          <input type="hidden" name="hidden_copy_week" id="hidden_copy_week" value="">

          <input type="hidden" name="hidden_copy_month" id="hidden_copy_month" value="">

          <?php

            $years = DB::table('year')->where('delete_status',0)->where('year_status', 0)->orderBy('year_name','dec')->get();

          ?>

          <h5 style="font-weight:800">CHOOSE YEAR : </h5>

          <div class="select_button" style="min-height:48px;float:none">

            <ul>

                <?php

                if(count($years)){

                  foreach($years as $year){

                    if($year->year_status == 0){

                ?>

                  <li><a href="javascript:" class="year_button" data-element="<?php echo $year->year_id; ?>"><?php echo $year->year_name; ?></a></li>

                <?php

                    }

                  }

                }

                ?>            

            </ul>

          </div>

          <h5 style="font-weight:800">CHOOSE TYPE : </h5>

          <select name="select_year_type" class="form-control" id="select_year_type">

            <option value="">Select Type</option>

            <option value="weekly">Weekly Task</option>

            <option value="monthly">Monthly Task</option>

          </select>

          <div class="select_button weekly_select" style="min-height:69px;float:none">

          </div>

          <div class="select_button category_select_copy" style="display:none;min-height:48px;float:none">

            <h5 style="font-weight:800">Choose Category : </h5>

            <select name="category_type_copy" class="form-control" id="category_type_copy" required>

              <option value="">Select Category</option>

              <?php

              if(count($classifiedlist)){

                foreach ($classifiedlist as $classified) { ?>

                  <option value="<?php echo $classified->classified_id ?>"><?php echo $classified->classified_name?></option>

                <?php

                }

              }

              ?>

            </select>

          </div>

      </div>

      <labe>

      <div class="modal-footer">

        <input type="submit" class="btn btn-primary" value="Submit" style="background: #000; color:#fff">

      </div>

    </div>

    </form>

  </div>

</div>

<div class="modal fade emailunsent" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">

  <div class="modal-dialog" role="document">

    <form action="<?php echo URL::to('user/email_unsent_files'); ?>" method="post" >

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

              <select name="select_user" id="select_user" class="form-control input-sm" required>

                <?php echo $unamelist; ?>

              </select>

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

    <form action="<?php echo URL::to('user/email_unsent_files'); ?>" method="post" >

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

              <select name="select_user" id="select_userresend" class="form-control input-sm" required>

                <?php echo $unamelist; ?>

              </select>

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

        <input type="submit" class="btn btn-primary common_black_button" value="Send">

      </div>

    </div>

    </form>

  </div>

</div>

<div class="modal fade" id="email_report_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">

  <div class="modal-dialog" role="document">

    <form action="<?php echo URL::to('user/email_report_send?year='.$yearname->year_name.'&month='.$monthid->month.'&type=month'); ?>" method="post" >

    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        <h4 class="modal-title" id="myModalLabel">Email Task Report</h4>

      </div>

      <div class="modal-body">

          <div class="row">

            <div class="col-md-3">

              <label>From</label>

            </div>

            <div class="col-md-9">

              <select name="select_user_report" class="form-control input-sm" required>

                <?php echo $unamelist; ?>

              </select>

            </div>

          </div>

          <div class="row" style="margin-top:10px">

            <div class="col-md-3">

              <label>To</label>

            </div>

            <div class="col-md-9">

              <input type="text" name="to_user_report" class="form-control input-sm" value="" required>

            </div>

          </div>

          <div class="row" style="margin-top:10px">

            <div class="col-md-3">

              <label>CC</label>

            </div>

            <div class="col-md-9">

              <input type="text" name="cc_report" class="form-control input-sm" value="<?php echo $admin_cc; ?>" readonly>

            </div>

          </div>

          <div class="row" style="margin-top:10px">

            <div class="col-md-3">

              <label>Subject</label>

            </div>

            <div class="col-md-9">

              <input type="text" name="subject_report" class="form-control input-sm subject_report" value="" required>

            </div>

          </div>

          <div class="row" style="margin-top:10px">

            <div class="col-md-12">

              <label>Message</label>

            </div>

            <div class="col-md-12">

              <textarea name="message_report" class="form-control" style="height:150px"></textarea>

            </div>

          </div>

          <div class="row" style="margin-top:10px">

            <div class="col-md-12">

              <label>Attachment</label>

            </div>

            <div class="col-md-2">

                <img src="<?php echo URL::to('assets/images/pdf.jpg'); ?>" width="100px">

            </div>

            <div class="col-md-10">

                <label style="margin-top:30px" id="task_report_label">Task_Report_For_Year-<?php echo $yearname->year_name; ?>_Month-<?php echo $monthid->month; ?>.pdf</label>

                <label style="margin-top:30px" id="notify_report_label">Notify_Report_For_Year-<?php echo $yearname->year_name; ?>_Month-<?php echo $monthid->month; ?>.pdf</label>

            </div>

          </div>

      </div>

      <div class="modal-footer">

        <input type="hidden" id="hidden_report_type" name="hidden_report_type" value="">

        <input type="submit" class="btn btn-primary common_black_button" value="Email Task Report">

      </div>

    </div>

    </form>

  </div>

</div>

<div class="modal fade model_notify" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">

  <div class="modal-dialog" role="document">

    <form action="" method="post" >

    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        <h4 class="modal-title notify_title" id="myModalLabel">Notify</h4>

      </div>

      <div class="modal-body">
          <div class="row">
            <div class="col-lg-12">
              <input type="checkbox" name="" id="notity_selectall"><label for="notity_selectall">Select All</label>
              <table id="dtBasicExample" class="table">
                <thead>
                  <tr>
                    <th scope="col" style="text-align: left">S.No</th>
                    <th scope="col" style="text-align: left">Name</th>
                  </tr>
                </thead>
                <tbody>
                 <?php
                 $i = 1;
                if(count($userlist)){
                  foreach ($userlist as $user) {
                    ?>
                    <tr>
                      <td scope="row"><?php echo $i; ?> <input type="checkbox" class="notify_id_class" name="username" id="user_<?php echo $user->user_id?>" data-element="<?php echo $user->email; ?>" data-value="<?php echo $user->user_id; ?>"><label>&nbsp;</label></td>
                      <td><label for="user_<?php echo $user->user_id?>"><?php echo $user->lastname.' '.$user->firstname; ?></label></td>
                    </tr>
                    <?php
                  $i++;
                  }
                }
                ?>
                </tbody>
              </table>
            </div>
          </div>
      </div>

      <div class="modal-footer">

          <input type="hidden" class="notify_task_id" value="" name="">

          <input type="button" class="btn btn-primary common_black_button notify_all_clients_tasks" value="Send Email">

      </div>

    </div>

    </form>

  </div>

</div>

<div class="modal fade model_don_not_complete" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog" role="document">
    
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title notify_title" id="myModalLabel">Disable:<span class="dont_task_name"></span></h4>
      </div>
      <div class="modal-body">
        <div style="font-size: 15px; font-weight: bold; width: 100%; height: auto; float: left; margin-bottom: 10px;">Do you want to Disable this task for:</div>

        <input type="radio" name="dontvale" value="1" class="dontvale_class" id="donot_period" ><label for="donot_period">This Period Only</label><br/>
        <input type="radio" name="dontvale" value="2" class="dontvale_class" id="donot_future_period" ><label for="donot_future_period">Until Future Notice</label>
      
      </div>
      <div class="modal-footer">
        <input type="hidden" class="dontvale" value="">
        <input type="hidden" class="donot_id_class" name="task_id">
        <input type="submit" class="btn btn-primary common_black_button donot_submit_new" value="Submit">
      </div>
    </div>
    
  </div>
</div>

<div class="content_section">

  <div class="page_title" style="z-index:999">

    <?php

    if($monthid->month_closed == '0000-00-00 00:00:00')

    {

      $now = time();

      $your_date = strtotime($monthid->updatetime);

      $datediff = $now - $your_date;

    }

    else{

      $now = strtotime($monthid->month_closed);

      $your_date = strtotime($monthid->updatetime);

      $datediff = $now - $your_date;

    }

    $days = floor($datediff / (60 * 60 * 24));

    if($days < 0)

    {

      $days = 0;

    }

  ?>

    <div class="col-lg-4 padding_00" style="margin-left: 1%;">Task of <?php echo $yearname->year_name ?> &nbsp;&nbsp;&nbsp;&nbsp; Month : Month <?php echo $monthid->month ?> &nbsp;&nbsp;&nbsp;&nbsp; No of days : <?php echo $days; ?></div>

    <div class="col-lg-7 padding_00 button_top_right">

      <ul style="margin-right: 13%;">

        

        <li><a href="<?php echo URL::to('user/close_create_new_month/'.$monthid->month_id); ?>" id="close_create_new_month">Close and Create New Month</a></li>

        <li><a href="javascript:" id="email_report_button">Email Task Report</a></li>

        <li class="dropdown_download"><a href="javascript:" id="download_reports" class="dropdown_download">DOWNLOAD <i class="fa fa-caret-down dropdown_download"></i></a></li>

        <div class="download_div" style="display:none">

          <a href="javascript:" class="close_xmark">X</a>

          <div class="row">

              <a href="javascript:" class="download_radio" id="all_tasks">All Tasks</a>

              <a href="javascript:" class="download_radio" id="task_completed">Tasks Completed</a>

              <a href="javascript:" class="download_radio" id="task_incomplete">Tasks InComplete</a>

          </div>

        </div>

        <li class="dropdown_notify"><a href="javascript:" id="notify_reports" class="dropdown_notify">NOTIFY ALL <i class="fa fa-caret-down dropdown_notify"></i></a></li>

        <div class="notify_div" style="display:none">

          <a href="javascript:" class="close_xmark">X</a>

          <div class="row">

              <a href="javascript:" class="notify_radio" id="task_standard">Standard</a>

              <a href="javascript:" class="notify_radio" id="task_enhanced">Enhanced</a>

              <a href="javascript:" class="notify_radio" id="task_complex">Complex</a>

          </div>

        </div>

        <li><a href="javascript:"  data-toggle="modal" data-target=".createnewtask">Create a Task</a></li>

        <?php $check_incomplete = Db::table('user_login')->where('userid',1)->first(); if($check_incomplete->month_incomplete == 1) { $inc_checked = 'checked="checked"'; } else { $inc_checked = ''; } ?>

        <br/> <input type="checkbox" name="show_incomplete" id="show_incomplete" value="1" <?php echo $inc_checked; ?>><label for="show_incomplete">Show Incomplete Only</label> 

      </ul>

    </div>



  </div>

 

  

<div style="width:100%;float:left; margin-top: 55px; margin-bottom: -66px;">

<?php

if(Session::has('message')) { ?>

    <p class="alert alert-info"><?php echo Session::get('message'); ?></p>

<?php }

if(Session::has('error')) { ?>

    <p class="alert alert-danger"><?php echo Session::get('error'); ?></p>

<?php }

?>

</div>



    <?php if(count($resultlist_standard)){ ?>

    <div class="table-responsive" style="max-width: 100%; float: left; margin-top:55px">

      <label class="label_task"> Standard Task</label>

      <table class="table_bg table-fixed-header" style="width: 2000px; margin: 0px auto;margin-bottom:30px">

          <thead class="header">

            <tr class="background_bg">

                <th width="80px">S.No <i class="fa fa-sort sno_sort_std" aria-hidden="true"></th>

                <th width="80px"></th>

                <th width="250px">Task Name <i class="fa fa-sort task_sort_std" aria-hidden="true"></th>

                <th width="90px" style="width:90px !important;">Enter<br/>Hours</th>

                <th width="90px" style="width:90px !important;">Holiday<br/>Pay</th>

                <th width="90px" style="width:90px !important;">Process<br/>Payroll</th>

                <th width="90px" style="width:90px !important;">Upload<br/>Payslips</th>

                <th width="90px" style="width:90px !important;">Email<br/>Payslips</th>

                <th width="90px" style="width:90px !important;">Upload<br/>Report</th>

                <th width="200px">Date <i class="fa fa-sort date_sort_std" aria-hidden="true"></th>

                <th width="250px">Username <i class="fa fa-sort user_sort_std" aria-hidden="true"></th>

                <th width="350px">Email <i class="fa fa-sort email_sort_std" aria-hidden="true"></th>                

                <th width="400px">Network Location</th>

                <th width="400px">Email Unsent Files</th>

                <th width="200px">Action</th>

            </tr>

          </thead>

          <tbody id="task_body_std">

            

              <?php 

              

               if(count($resultlist_standard)){

                  $i=1;

                  foreach ($resultlist_standard as $result) {

                    if($i < 10)

                  {

                    $i = '0'.$i;

                  }

              ?>

              <tr class="task_tr_std" id="taskidtr_<?php echo $result->task_id; ?>">

              <?php if($result->task_status == 1) { $disabled='disabled'; } elseif($result->task_complete_period == 1){ $disabled='disabled'; } else{ $disabled=''; } ?>
              <?php if($result->task_status == 1) { $task_label='style="color:#f00;font-weight:800"'; } elseif($result->task_complete_period == 1) { $task_label='style="color:#1b0fd4;font-weight:800"'; }  elseif($result->task_started == 1) { $task_label='style="color:#89ff00;font-weight:800"'; } else{ $task_label='style="color:#fff;font-weight:600"'; }  ?>

                  <td class="sno_sort_std_val"><?php echo $i;?></td>

                  <td>

                    <?php

                      if($result->task_started == 0){

                        echo '<input type="checkbox" name="task_started_checkbox" value="1" class="task_started_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';

                      }

                      else if($result->task_started == 1){

                        echo '<input type="checkbox" name="task_started_checkbox" value="1" class="task_started_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';

                      }

                    ?>

                  </td>

                  <td class="task_sort_std_val" align="left"><label class="task_label <?php echo $disabled; ?>" <?php echo $task_label; ?>><?php echo $result->task_name ?></label>
                    <br/><br/><br/>

                    <b style="color:#000; margin-bottom: 5px; width: 170px; height: auto; float: left;">Default Staff: </b>

                    <select class="form-control default_staff" data-element="<?php echo $result->task_id; ?>" <?php echo $disabled; ?>>                    
                    <?php
                    $userlist = DB::table('user')->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
                    $uname = '<option value="">Select Username</option>';
                      if(count($userlist)){
                        foreach ($userlist as $singleuser) {
                          if($result->default_staff == $singleuser->user_id) { $selected = 'selected'; } else{ $selected = ''; }
                            if($uname == '')
                              {
                                $uname = '<option value="'.$singleuser->user_id.'" '.$selected.'>'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';
                              }
                              else{
                                $uname = $uname.'<option value="'.$singleuser->user_id.'" '.$selected.'>'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';
                              }
                            }
                        }
                        echo $uname;
                    ?>
                  </select>

                  <br/>


                  <?php if($result->task_month > 24) { ?>
                  <b style="color:#000;">Last Period Complete : </b><br/>
                  <?php 
                  if($result->last_email_sent_carry == '0000-00-00 00:00:00') { echo 'Not complete Last period'; }
                  else{
                      $date_carry = date('d F Y', strtotime($result->last_email_sent_carry));

                      $time_carry = date('H : i', strtotime($result->last_email_sent_carry));

                      $last_date_carry = $date_carry.' <br/>@ '.$time_carry;
                      echo $last_date_carry;
                  }
                  }
                  ?>
                  </td>

                  <td align="center" class="special_td" style="width:90px;">

                    <?php

                      

                      if($result->enterhours == 0){

                        echo '<input type="checkbox" name="enterhours_checkbox" value="1" class="enterhours_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';

                      }

                      else if($result->enterhours == 1){

                        echo '<input type="checkbox" name="enterhours_checkbox" value="1" class="enterhours_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';

                      }

                      else{

                        echo 'N/A';

                      }

                    ?>

                    <div class="text_checkbox">ENTER<br/>HOURS</div>
                    <br/>
                    <div class="special_div" style="text-align: left;border-top:1px solid #000;width:550px !important;clear:both;float:left;position: absolute;">
                      
                      <style>
                      .single_notify, .all_notify{font-weight: bold;}
                      </style>

                      <?php 
                      if($result->task_notify == 1){
                        echo '<a href="javascript:" class="single_notify '.$disabled.'" data-element="'.$result->task_id.'">Notify</a> &nbsp &nbsp <a href="javascript:"  class="all_notify '.$disabled.'" data-element="'.$result->task_id.'">Notify all</a>';
                      }
                      ?>
                      <br/>
                      <i class="fa fa-plus faplus <?php echo $disabled; ?>" style="margin-top:10px" aria-hidden="true" title="Add Attachment"></i>

                      <i class="fa fa-pencil-square fanotepad <?php echo $disabled; ?>" style="margin-top:10px;margin-left:10px" aria-hidden="true" title="Add Notepad"></i>
                      <?php
                      
                      $attachments = DB::table('task_attached')->where('task_id',$result->task_id)->where('network_attach',1)->get();

                      if(count($attachments))

                      {
                        echo '<i class="fa fa-minus-square fadeleteall_attachments '.$disabled.'" data-element="'.$result->task_id.'" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>';
                        echo '<h5 style="color:#000; font-weight:600">Files Received :</h5>';
                        echo '<div class="scroll_attachment_div">';
                            foreach($attachments as $attachment)
                            {

                                echo '<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disabled.'"><i class="fa fa-trash trash_image '.$disabled.'" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';

                            }
                        echo '</div>';

                      }

                      ?>

                          <div class="img_div">

                            <form name="image_form" id="image_form" action="<?php echo URL::to('user/task_image_upload?type=2'); ?>" method="post" enctype="multipart/form-data" style="text-align: left;">

                              <input type="file" name="image_file[]" class="form-control image_file" value="" multiple>

                              <input type="hidden" name="hidden_id" value="<?php echo $result->task_id ?>">

                              <input type="submit" name="image_submit" class="btn btn-sm btn-primary image_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">

                              <spam class="error_files"></spam>

                            </form>
                            <div style="width:100%;text-align:center;margin-top:-10px;margin-bottom:10px;color:#000"><label style="font-weight:800;">OR</label></div>
                              <div class="image_div_attachments">
                                <form action="<?php echo URL::to('user/taskmanager_upload_images?task_id='.$result->task_id.'&type=2'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="image-upload" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid;">
                                <input name="_token" type="hidden" value="iceYGYrd7HqKzNlyAzFhbLh4Tu2FMEuijqGj5V3Q">
                                 
                                </form>
                                <a href="<?php echo URL::to('user/select_month/'.base64_encode($monthid->month_id).'?divid=taskidtr_'.$result->task_id); ?>" class="btn btn-sm btn-primary" align="left" style="margin-left:7px;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
                              </div>

                          </div>
                          <div class="notepad_div">

                              <form name="notepad_form" id="notepad_form" action="<?php echo URL::to('user/task_notepad_upload'); ?>" method="post" enctype="multipart/form-data" style="text-align: left;">

                                
                                <textarea name="notepad_contents" class="form-control notepad_contents" placeholder="Enter Contents"></textarea>

                                <input type="hidden" name="hidden_id" value="<?php echo $result->task_id ?>">

                                <input type="submit" name="notepad_submit" class="btn btn-sm btn-primary notepad_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">

                                <spam class="error_files_notepad"></spam>

                              </form>

                            </div>
                    </div>
                  </td>

                  <td align="center">

                    <?php

                      if($result->holiday == 0){

                        echo '<input type="checkbox" name="holiday_checkbox" value="1" class="holiday_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';

                      }

                      else if($result->holiday == 1){

                        echo '<input type="checkbox" name="holiday_checkbox" value="1" class="holiday_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';

                      }

                      else{

                        echo 'N/A';

                      }

                    ?>

                    <div class="text_checkbox">HOLIDAY<br/>PAY</div>

                  </td>

                  <td align="center">

                    <?php

                      if($result->process == 0){

                        echo '<input type="checkbox" name="process_checkbox" value="1" class="process_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';

                      }

                      else if($result->process == 1){

                        echo '<input type="checkbox" name="process_checkbox" value="1" class="process_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';

                      }

                      else{

                        echo 'N/A';

                      }

                    ?>

                    <div class="text_checkbox">PROCESS<br/>PAYROLL</div>

                  </td>

                  <td align="center">

                    <?php

                      if($result->payslips == 0){

                        echo '<input type="checkbox" name="payslips_checkbox" value="1" class="payslips_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';

                      }

                      else if($result->payslips == 1){

                        echo '<input type="checkbox" name="payslips_checkbox" value="1" class="payslips_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';

                      }

                      else{

                        echo 'N/A';

                      }

                    ?>

                    <div class="text_checkbox">UPLOAD<br/>PAYSLIPS</div>

                  </td>

                  <td align="center">

                    <?php

                      if($result->email == 0){

                        echo '<input type="checkbox" name="email_checkbox" value="1" class="email_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';

                      }

                      else if($result->email == 1){

                        echo '<input type="checkbox" name="email_checkbox" value="1" class="email_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';

                      }

                      else{

                        echo 'N/A';

                      }

                    ?>

                    <div class="text_checkbox">EMAIL<br/>PAYSLIPS</div>

                  </td>

                  <td align="center">

                    <?php

                      if($result->upload == 0){

                        echo '<input type="checkbox" name="upload_checkbox" value="1" class="upload_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';

                      }

                      else if($result->upload == 1){

                        echo '<input type="checkbox" name="upload_checkbox" value="1" class="upload_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';

                      }

                      else{

                        echo 'N/A';

                      }

                    ?>

                    <div class="text_checkbox">UPLOAD<br/>REPORT</div>

                  </td>

                  

                  <?php 

                    if($result->task_status == 1)

                    {

                      $seperatedate = explode(' ',$result->updatetime);

                      $explodedate = explode('-',$seperatedate[0]);

                      $explodetime = explode(':',$seperatedate[1]);

                      $date = $explodedate[1].'-'.$explodedate[2].'-'.$explodedate[0];

                      $time = $explodetime[0].':'.$explodetime[1];

                    }

                    else{

                      $date = 'MM-DD-YYYY';

                      $time = 'HH:MM';

                    }

                  ?>

                  <td class="date_sort_cmp_val" align="center">

                    <input type="text" class="form-control common_input datepicker date_input" value="<?php echo $date; ?>" data-element="<?php echo $result->task_id; ?>" style="text-align: center;" disabled/>

                    <input type="text" class="form-control common_input time_input" value="<?php echo $time; ?>" data-element="<?php echo $result->task_id; ?>" style="text-align: center;" disabled/>

                    

                  </td>

                  <td class="user_sort_std_val" align="center">

                    <select class="form-control common_input uname_input" data-element="<?php echo $result->task_id; ?>" <?php echo $disabled; ?>>

                        <?php

                            $userlist = DB::table('user')->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();

                            $uname = '<option value="">Select Username</option>';

                            if(count($userlist)){

                              foreach ($userlist as $singleuser) {

                                if($result->users == $singleuser->user_id) { $selected = 'selected'; } else{ $selected = ''; }

                                if($uname == '')

                                {

    $uname = '<option value="'.$singleuser->user_id.'" '.$selected.'>'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';

                                }

                                else{

    $uname = $uname.'<option value="'.$singleuser->user_id.'" '.$selected.'>'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';

                                }

                              }

                            }

                            echo $uname;

                        ?>

                    </select>

                    <textarea class="form-control common_input commandclass comments_input" id="<?php echo $result->task_id ?>" data-element="<?php echo $result->task_id; ?>" placeholder="Enter Your Comments Here" <?php echo $disabled; ?>><?php echo $result->comments ?></textarea>

                  </td>

                  <td class="email_sort_std_val" align="center">

                    <input type="text" class="form-control common_input task_email_input" value="<?php echo $result->task_email ?>" data-element="<?php echo $result->task_id; ?>"  placeholder="Enter Email" disabled>

                  </td>

                  

                  <td align="center" class="file_received_div" style="text-align: left">

                      <input type="text" class="form-control common_input network_input" name="" value="<?php echo $result->network ?>" disabled><br/>

                      <i class="fa fa-plus faplus <?php echo $disabled; ?>" style="margin-top:10px" aria-hidden="true" ></i>

                      <?php

                      $attachments = DB::table('task_attached')->where('task_id',$result->task_id)->where('network_attach',0)->get();

                      if(count($attachments))

                      {
                          echo '<i class="fa fa-minus-square fadeleteall '.$disabled.'" data-element="'.$result->task_id.'" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>';
                          echo '<h5 style="color:#000; font-weight:600">Attachments :</h5>';
                          echo '<div class="scroll_attachment_div">';

                            foreach($attachments as $attachment)

                            {

                                echo '<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disabled.'"><i class="fa fa-trash trash_image sample_trash '.$disabled.'" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';

                            }
                          echo '</div>';

                      }

                      ?>

                          <label style="width:100%;margin-top:15px">PAYE/PRSI/USC Liability:</label>
                          <?php if(count($attachments)) { ?>
                              <input type="textbox" name="liability_input" class="liability_input form-control input-sm" data-element="<?php echo $result->task_id; ?>" value="<?php echo $result->liability; ?>" <?php echo $disabled; ?>>
                          <?php } else { ?>
                              <input type="textbox" name="liability_input" class="liability_input form-control input-sm" data-element="<?php echo $result->task_id; ?>" value="" disabled>
                          <?php } ?>

                          <div class="img_div">

                            <label class="copy_label">Network location has been copied. Just paste the URL in the "File name" path to go to that folder.</label>

                            <form name="image_form" id="image_form" action="<?php echo URL::to('user/task_image_upload'); ?>" method="post" enctype="multipart/form-data" style="text-align: left;">

                              <input type="file" name="image_file[]" class="form-control image_file" value="" multiple>

                              <input type="hidden" name="hidden_id" value="<?php echo $result->task_id ?>">

                              <input type="submit" name="image_submit" class="btn btn-sm btn-primary image_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">

                              <spam class="error_files"></spam>

                            </form>
                            <div style="width:100%;text-align:center;margin-top:-10px;margin-bottom:10px;color:#000"><label style="font-weight:800;">OR</label></div>
                              <div class="image_div_attachments">
                                <form action="<?php echo URL::to('user/taskmanager_upload_images?task_id='.$result->task_id.'&type=1'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="image-upload" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid;">
                                <input name="_token" type="hidden" value="iceYGYrd7HqKzNlyAzFhbLh4Tu2FMEuijqGj5V3Q">
                                 
                                </form>
                                <a href="<?php echo URL::to('user/select_month/'.base64_encode($monthid->month_id).'?divid=taskidtr_'.$result->task_id); ?>" class="btn btn-sm btn-primary" align="left" style="margin-left:7px;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
                              </div>

                          </div>
                          <?php
                          if($result->disclose_liability == 1) { $check_lia = 'checked'; } else { $check_lia = ''; } ?>
                          <input type="checkbox" name="disclose_liability" id="disclose_liability<?php echo $result->task_id; ?>" class="disclose_liability" value="1" data-element="<?php echo $result->task_id; ?>" <?php echo $check_lia.' '.$disabled; ?>><label class="disclose_label" for="disclose_liability<?php echo $result->task_id; ?>">Disclose Lability on Client Email</label>

                  </td>

                  





                  <td align="center">

                    <a href="javascript:" class="fa fa-envelope <?php echo $disabled; ?> email_unsent <?php if($result->last_email_sent != '0000-00-00 00:00:00') { echo 'show_popup'; } ?>" data-toggle="tooltip" title="Email Unsent FIles" aria-hidden="true" data-element="<?php echo $result->task_id; ?>"></a>

                    <a href="javascript:" class="<?php if($result->last_email_sent == '0000-00-00 00:00:00') { echo 'disabled'; } ?> resendemail_unsent" data-element="<?php echo $result->task_id; ?>">
                      <img src="<?php echo URL::to('assets/email_resend_icon.png')?>" class="resendemail_unsent <?php echo $disabled; ?>" data-toggle="tooltip" title="Resend Email" aria-hidden="true" data-element="<?php echo $result->task_id; ?>" style="margin-top: -3px; height: 12px; width: auto;">  
                      
                    </a>

                    <a href="javascript:" class="fa fa-file-text <?php if($result->last_email_sent == '0000-00-00 00:00:00') { echo 'disabled'; } ?> report_email_unsent" data-toggle="tooltip" title="Download Report as Pdf" aria-hidden="true" data-element="<?php echo $result->task_id; ?>"></a>

                    <?php
                    if($result->last_email_sent != '0000-00-00 00:00:00')
                    {
                      $get_dates = DB::table('task_email_sent')->where('task_id',$result->task_id)->get();
                      $last_date = '';
                      if(count($get_dates))
                      {
                        foreach($get_dates as $dateval)
                        {
                          $date = date('d F Y', strtotime($dateval->email_sent));
                          $time = date('H : i', strtotime($dateval->email_sent));
                          if($dateval->options != '0')
                          {
                            if($dateval->options == 'a') { $text = 'Fix an Error Created In House'; }
                            elseif($dateval->options == 'b') { $text = 'Fix an Error by Client or Implement a client Requested Change'; }
                            elseif($dateval->options == 'c') { $text = 'Combined In House and Client Prompted adjustments'; }
                            else{ $text= ''; }
                            $itag = '<span class="" title="'.$text.'" style="font-weight:800;"> ('.strtoupper($dateval->options).') </span>';
                          }
                          else{
                            $itag = '';
                          }
                          if($last_date == "")
                          {
                            $last_date = '<p>'.$date.' @ '.$time.' '.$itag.'</p>';
                          }
                          else{
                            $last_date = $last_date.'<p>'.$date.' @ '.$time.' '.$itag.'</p>';
                          }
                        }
                      }
                      else{
                        $date = date('d F Y', strtotime($result->last_email_sent));
                        $time = date('H : i', strtotime($result->last_email_sent));
                        $last_date = '<p>'.$date.' @ '.$time.'</p>';
                      }
                    }
                    else{
                      $last_date = '';
                    }
                    ?>

                    <label class="email_unsent_label"><?php echo $last_date; ?></label>

                  </td>

                  <td align="center">

                    <a href="javascript:" class="<?php echo $disabled; ?>" data-toggle="modal" data-target=".copy_task" data-element="<?php echo $result->task_id; ?>"><i class="fa fa-files-o <?php echo $disabled; ?>" data-toggle="tooltip" title="Copy Task" aria-hidden="true" data-element="<?php echo $result->task_id; ?>"></i></a>&nbsp;&nbsp;



                    <a href="javascript:" class="edit_task <?php echo $disabled; ?>" data-element="<?php echo $result->task_id; ?>"><i class="fa fa-pencil edit_task <?php echo $disabled; ?>" data-toggle="tooltip" title="Edit Task Name" aria-hidden="true" data-element="<?php echo $result->task_id; ?>"></i></a>&nbsp;&nbsp;



                    <a href="<?php echo URL::to('user/delete_task/'.base64_encode($result->task_id))?>" class="task_delete <?php echo $disabled; ?>"><i class="fa fa-trash task_delete <?php echo $disabled; ?>" data-toggle="tooltip" title="Delete Task" aria-hidden="true"></i></a>&nbsp;&nbsp;



                    <a href="javascript:" class="<?php if($result->task_complete_period == 1) { echo $disabled; } ?>"><i class="fa <?php if($result->task_status == 0) { echo 'fa-check'; } else{ echo 'fa-times'; } ?>" data-toggle="tooltip" <?php if($result->task_status == 0) { echo 'title="Mark as Completed"'; } else { echo 'title="Mark as Incomplete"'; }?> data-element="<?php echo $result->task_id; ?>" aria-hidden="true"></i></a>

                      <a href="javascript:" class="<?php if($result->task_status == 1) { echo $disabled; } ?>" <?php if($result->task_complete_period == 1) { echo 'style="color:#f00;"'; } ?>><i class="fa <?php if($result->task_complete_period == 0) { echo 'fa-exclamation-triangle donot_complete'; } else{ echo 'fa-ban do_complete'; } ?>" data-toggle="tooltip" <?php if($result->task_complete_period == 0) { echo 'title="Do not complete this Period"'; } else { echo 'title="Disable: Do not complete this Period"'; }?> data-element="<?php echo $result->task_id; ?>" aria-hidden="true"></i></a>
                    <br/><br/>
                    <div style="width: 100%; height: auto; float: left; font-size: 30px; color: <?php if($result->client_id == '') { echo 'red'; } else{ echo 'blue'; } ?>">
                      <i class="fa <?php if($result->client_id == '') { echo 'fa-chain-broken'; } else{ echo 'fa-link'; } ?>" data-toggle="tooltip" <?php if($result->client_id == '') { echo 'title="This task is not linked"'; } else { echo 'title="This task is linked"'; }?>></i>
                    </div>

                  </td>            

              </tr>

              <?php

                  $i++;

                  }

                  

               }

               

               else{

                  echo "<tr>

                        <td colspan='15' align='center'>Task not found</td></tr>";

               }

              ?>

          </tbody>

      </table>

      <p></p>

    </div>

    <?php } ?>

       <?php if(count($resultlist_enhanced)){ ?>

      <div class="table-responsive" style="max-width: 100%; float: left; margin-bottom:30px; margin-top:55px">

       <label class="label_task"> Enhanced Task</label>

      <table class="table_bg table-fixed-header_1" style="width: 2000px; margin: 0px auto;">

          <thead class="header">

            <tr class="background_bg">

                <th width="80px">S.No <i class="fa fa-sort sno_sort_enh" aria-hidden="true"></th>

                <th width="80px"></th>

                <th width="250px">Task Name <i class="fa fa-sort task_sort_enh" aria-hidden="true"></th>

                <th width="90px" style="width:90px !important;">Enter<br/>Hours</th>

                <th width="90px" style="width:90px !important;">Holiday<br/>Pay</th>

                <th width="90px" style="width:90px !important;">Process<br/>Payroll</th>

                <th width="90px" style="width:90px !important;">Upload<br/>Payslips</th>

                <th width="90px" style="width:90px !important;">Email<br/>Payslips</th>

                <th width="90px" style="width:90px !important;">Upload<br/>Report</th>

                <th width="200px">Date <i class="fa fa-sort date_sort_enh" aria-hidden="true"></th>

                <th width="250px">Username <i class="fa fa-sort user_sort_enh" aria-hidden="true"></th>

                <th width="350px">Email <i class="fa fa-sort email_sort_enh" aria-hidden="true"></th>

                <th width="400px">Network Location</th>

                <th width="400px">Email Unsent Files</th>

                <th width="200px">Action</th>

            </tr>

          </thead>

          <tbody id="task_body_enh">

            

              <?php 

              

               if(count($resultlist_enhanced)){

                  $i=1;

                  foreach ($resultlist_enhanced as $result) {

                    if($i < 10)

                  {

                    $i = '0'.$i;

                  }

              ?>

              <tr class="task_tr_enh" id="taskidtr_<?php echo $result->task_id; ?>">

              <?php if($result->task_status == 1) { $disabled='disabled'; } elseif($result->task_complete_period == 1){ $disabled='disabled'; } else{ $disabled=''; } ?>



              <?php if($result->task_status == 1) { $task_label='style="color:#f00;font-weight:800"'; } elseif($result->task_complete_period == 1) { $task_label='style="color:#1b0fd4;font-weight:800"'; }  elseif($result->task_started == 1) { $task_label='style="color:#89ff00;font-weight:800"'; } else{ $task_label='style="color:#fff;font-weight:600"'; } ?>

                  <td class="sno_sort_enh_val"><?php echo $i;?></td>

                  <td>

                    <?php

                      if($result->task_started == 0){

                        echo '<input type="checkbox" name="task_started_checkbox" value="1" class="task_started_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';

                      }

                      else if($result->task_started == 1){

                        echo '<input type="checkbox" name="task_started_checkbox" value="1" class="task_started_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';

                      }

                    ?>

                  </td>

                  <td class="task_sort_enh_val" align="left"><label class="task_label <?php echo $disabled; ?>" <?php echo $task_label; ?>><?php echo $result->task_name ?></label>

                    <br/><br/><br/>

                    <b style="color:#000; margin-bottom: 5px; width: 170px; height: auto; float: left;">Default Staff: </b>

                    <select class="form-control default_staff" data-element="<?php echo $result->task_id; ?>" <?php echo $disabled; ?>>                    
                    <?php
                    $userlist = DB::table('user')->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
                    $uname = '<option value="">Select Username</option>';
                      if(count($userlist)){
                        foreach ($userlist as $singleuser) {
                          if($result->default_staff == $singleuser->user_id) { $selected = 'selected'; } else{ $selected = ''; }
                            if($uname == '')
                              {
                                $uname = '<option value="'.$singleuser->user_id.'" '.$selected.'>'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';
                              }
                              else{
                                $uname = $uname.'<option value="'.$singleuser->user_id.'" '.$selected.'>'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';
                              }
                            }
                        }
                        echo $uname;
                    ?>
                  </select>

                  <br/>


                  <?php if($result->task_month > 24) { ?>
                  <b style="color:#000;">Last Period Complete : </b><br/>
                  <?php 
                  if($result->last_email_sent_carry == '0000-00-00 00:00:00') { echo 'Not complete Last period'; }
                  else{
                      $date_carry = date('d F Y', strtotime($result->last_email_sent_carry));

                      $time_carry = date('H : i', strtotime($result->last_email_sent_carry));

                      $last_date_carry = $date_carry.' <br/>@ '.$time_carry;
                      echo $last_date_carry;
                  }
                  }
                  ?>
                  </td>

                  <td align="center" class="special_td" style="width:90px;">

                    <?php

                      

                      if($result->enterhours == 0){

                        echo '<input type="checkbox" name="enterhours_checkbox" value="1" class="enterhours_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';

                      }

                      else if($result->enterhours == 1){

                        echo '<input type="checkbox" name="enterhours_checkbox" value="1" class="enterhours_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';

                      }

                      else{

                        echo 'N/A';

                      }

                    ?>

                    <div class="text_checkbox">ENTER<br/>HOURS</div>
                    <br/>
                    <div class="special_div" style="text-align: left;border-top:1px solid #000;width:550px !important;clear:both;float:left;position: absolute;">
                      
                      <style>
                      .single_notify, .all_notify{font-weight: bold;}
                      </style>

                      <?php 
                      if($result->task_notify == 1){
                        echo '<a href="javascript:" class="single_notify '.$disabled.'" data-element="'.$result->task_id.'">Notify</a> &nbsp &nbsp <a href="javascript:"  class="all_notify '.$disabled.'" data-element="'.$result->task_id.'">Notify all</a>';
                      }
                      ?>
                      <br/>
                      <i class="fa fa-plus faplus <?php echo $disabled; ?>" style="margin-top:10px" aria-hidden="true" title="Add Attachment"></i>
                      <i class="fa fa-pencil-square fanotepad <?php echo $disabled; ?>" style="margin-top:10px;margin-left:10px" aria-hidden="true" title="Add Notepad"></i>

                      <?php
                      
                      $attachments = DB::table('task_attached')->where('task_id',$result->task_id)->where('network_attach',1)->get();

                      if(count($attachments))

                      {
                        echo '<i class="fa fa-minus-square fadeleteall_attachments '.$disabled.'" data-element="'.$result->task_id.'" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>';
                        echo '<h5 style="color:#000; font-weight:600">Files Received :</h5>';
                        echo '<div class="scroll_attachment_div">';
                            foreach($attachments as $attachment)

                            {

                                echo '<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disabled.'"><i class="fa fa-trash trash_image '.$disabled.'" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';

                            }
                        echo '</div>';

                      }

                      ?>

                          <div class="img_div">

                            <form name="image_form" id="image_form" action="<?php echo URL::to('user/task_image_upload?type=2'); ?>" method="post" enctype="multipart/form-data" style="text-align: left;">

                              <input type="file" name="image_file[]" class="form-control image_file" value="" multiple>

                              <input type="hidden" name="hidden_id" value="<?php echo $result->task_id ?>">

                              <input type="submit" name="image_submit" class="btn btn-sm btn-primary image_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">

                              <spam class="error_files"></spam>

                            </form>
                            <div style="width:100%;text-align:center;margin-top:-10px;margin-bottom:10px;color:#000"><label style="font-weight:800;">OR</label></div>
                              <div class="image_div_attachments">
                                <form action="<?php echo URL::to('user/taskmanager_upload_images?task_id='.$result->task_id.'&type=2'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="image-upload" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid;">
                                <input name="_token" type="hidden" value="iceYGYrd7HqKzNlyAzFhbLh4Tu2FMEuijqGj5V3Q">
                                 
                                </form>
                                <a href="<?php echo URL::to('user/select_month/'.base64_encode($monthid->month_id).'?divid=taskidtr_'.$result->task_id); ?>" class="btn btn-sm btn-primary" align="left" style="margin-left:7px;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
                              </div>
                          </div>
                          <div class="notepad_div">

                              <form name="notepad_form" id="notepad_form" action="<?php echo URL::to('user/task_notepad_upload'); ?>" method="post" enctype="multipart/form-data" style="text-align: left;">
                                <textarea name="notepad_contents" class="form-control notepad_contents" placeholder="Enter Contents"></textarea>

                                <input type="hidden" name="hidden_id" value="<?php echo $result->task_id ?>">

                                <input type="submit" name="notepad_submit" class="btn btn-sm btn-primary notepad_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">

                                <spam class="error_files_notepad"></spam>

                              </form>

                            </div>
                    </div>
                  </td>

                  <td align="center">

                    <?php

                      if($result->holiday == 0){

                        echo '<input type="checkbox" name="holiday_checkbox" value="1" class="holiday_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';

                      }

                      else if($result->holiday == 1){

                        echo '<input type="checkbox" name="holiday_checkbox" value="1" class="holiday_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';

                      }

                      else{

                        echo 'N/A';

                      }

                    ?>

                    <div class="text_checkbox">HOLIDAY<br/>PAY</div>

                  </td>

                  <td align="center">

                    <?php

                      if($result->process == 0){

                        echo '<input type="checkbox" name="process_checkbox" value="1" class="process_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';

                      }

                      else if($result->process == 1){

                        echo '<input type="checkbox" name="process_checkbox" value="1" class="process_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';

                      }

                      else{

                        echo 'N/A';

                      }

                    ?>

                    <div class="text_checkbox">PROCESS<br/>PAYROLL</div>

                  </td>

                  <td align="center">

                    <?php

                      if($result->payslips == 0){

                        echo '<input type="checkbox" name="payslips_checkbox" value="1" class="payslips_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';

                      }

                      else if($result->payslips == 1){

                        echo '<input type="checkbox" name="payslips_checkbox" value="1" class="payslips_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';

                      }

                      else{

                        echo 'N/A';

                      }

                    ?>

                    <div class="text_checkbox">UPLOAD<br/>PAYSLIPS</div>

                  </td>

                  <td align="center">

                    <?php

                      if($result->email == 0){

                        echo '<input type="checkbox" name="email_checkbox" value="1" class="email_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';

                      }

                      else if($result->email == 1){

                        echo '<input type="checkbox" name="email_checkbox" value="1" class="email_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';

                      }

                      else{

                        echo 'N/A';

                      }

                    ?>

                    <div class="text_checkbox">EMAIL<br/>PAYSLIPS</div>

                  </td>

                  <td align="center">

                    <?php

                      if($result->upload == 0){

                        echo '<input type="checkbox" name="upload_checkbox" value="1" class="upload_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';

                      }

                      else if($result->upload == 1){

                        echo '<input type="checkbox" name="upload_checkbox" value="1" class="upload_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';

                      }

                      else{

                        echo 'N/A';

                      }

                    ?>

                    <div class="text_checkbox">UPLOAD<br/>REPORT</div>

                  </td>

                  
                  <?php 

                    if($result->task_status == 1)

                    {

                      $seperatedate = explode(' ',$result->updatetime);

                      $explodedate = explode('-',$seperatedate[0]);

                      $explodetime = explode(':',$seperatedate[1]);

                      $date = $explodedate[1].'-'.$explodedate[2].'-'.$explodedate[0];

                      $time = $explodetime[0].':'.$explodetime[1];

                    }

                    else{

                      $date = 'MM-DD-YYYY';

                      $time = 'HH:MM';

                    }

                  ?>

                  <td class="date_sort_cmp_val" align="center">

                    <input type="text" class="form-control common_input datepicker date_input" value="<?php echo $date; ?>" data-element="<?php echo $result->task_id; ?>" style="text-align: center;" disabled/>

                    <input type="text" class="form-control common_input time_input" value="<?php echo $time; ?>" data-element="<?php echo $result->task_id; ?>" style="text-align: center;" disabled/>

                    

                  </td>

                  <td class="user_sort_enh_val" align="center">

                    <select class="form-control common_input uname_input" data-element="<?php echo $result->task_id; ?>" <?php echo $disabled; ?>>

                        <?php

                            $userlist = DB::table('user')->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();

                            $uname = '<option value="">Select Username</option>';

                            if(count($userlist)){

                              foreach ($userlist as $singleuser) {

                                if($result->users == $singleuser->user_id) { $selected = 'selected'; } else{ $selected = ''; }

                                if($uname == '')

                                {

    $uname = '<option value="'.$singleuser->user_id.'" '.$selected.'>'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';

                                }

                                else{

    $uname = $uname.'<option value="'.$singleuser->user_id.'" '.$selected.'>'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';

                                }

                              }

                            }

                            echo $uname;

                        ?>

                    </select>

                    <textarea class="form-control common_input commandclass comments_input" id="<?php echo $result->task_id ?>" data-element="<?php echo $result->task_id; ?>" placeholder="Enter Your Comments Here" <?php echo $disabled; ?>><?php echo $result->comments ?></textarea>

                  </td>

                  <td class="email_sort_enh_val" align="center">

                  

                    <input type="text" class="form-control common_input task_email_input" value="<?php echo $result->task_email ?>" data-element="<?php echo $result->task_id; ?>"  placeholder="Enter Email" disabled>

                  </td>

                  <td align="center" class="file_received_div" style="text-align: left">

                      <input type="text" class="form-control common_input network_input" name="" value="<?php echo $result->network ?>" disabled><br/>
                      <i class="fa fa-plus faplus <?php echo $disabled; ?>" style="margin-top:10px" aria-hidden="true" ></i>
                      <?php

                      $attachments = DB::table('task_attached')->where('task_id',$result->task_id)->where('network_attach',0)->get();

                      if(count($attachments))

                      {
                        echo '<i class="fa fa-minus-square fadeleteall '.$disabled.'" data-element="'.$result->task_id.'" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>';
                        echo '<h5 style="color:#000; font-weight:600">Attachments :</h5>';
                          echo '<div class="scroll_attachment_div">';

                            foreach($attachments as $attachment)

                            {

                                echo '<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disabled.'"><i class="fa fa-trash trash_image sample_trash '.$disabled.'" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';

                            }
                          echo '</div>';

                      }

                      ?>

                          <label style="width:100%;margin-top:15px">PAYE/PRSI/USC Liability:</label>
                          <?php if(count($attachments)) { ?>
                              <input type="textbox" name="liability_input" class="liability_input form-control input-sm" data-element="<?php echo $result->task_id; ?>" value="<?php echo $result->liability; ?>" <?php echo $disabled; ?>>
                          <?php } else { ?>
                              <input type="textbox" name="liability_input" class="liability_input form-control input-sm" data-element="<?php echo $result->task_id; ?>" value="" disabled>
                          <?php } ?>

                          <div class="img_div">

                            <label class="copy_label">Network location has been copied. Just paste the URL in the "File name" path to go to that folder.</label>

                            <form name="image_form" id="image_form" action="<?php echo URL::to('user/task_image_upload'); ?>" method="post" enctype="multipart/form-data" style="text-align: left;">

                              <input type="file" name="image_file[]" class="form-control image_file" value="" multiple>

                              <input type="hidden" name="hidden_id" value="<?php echo $result->task_id ?>">

                              <input type="submit" name="image_submit" class="btn btn-sm btn-primary image_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">

                              <spam class="error_files"></spam>

                            </form>
                            <div style="width:100%;text-align:center;margin-top:-10px;margin-bottom:10px;color:#000"><label style="font-weight:800;">OR</label></div>
                              <div class="image_div_attachments">
                                <form action="<?php echo URL::to('user/taskmanager_upload_images?task_id='.$result->task_id.'&type=1'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="image-upload" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid;">
                                <input name="_token" type="hidden" value="iceYGYrd7HqKzNlyAzFhbLh4Tu2FMEuijqGj5V3Q">
                                 
                                </form>
                                <a href="<?php echo URL::to('user/select_month/'.base64_encode($monthid->month_id).'?divid=taskidtr_'.$result->task_id); ?>" class="btn btn-sm btn-primary" align="left" style="margin-left:7px;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
                              </div>
                          </div>
                          <?php
                          if($result->disclose_liability == 1) { $check_lia = 'checked'; } else { $check_lia = ''; } ?>
                          <input type="checkbox" name="disclose_liability" id="disclose_liability<?php echo $result->task_id; ?>" class="disclose_liability" value="1" data-element="<?php echo $result->task_id; ?>" <?php echo $check_lia.' '.$disabled; ?>><label class="disclose_label" for="disclose_liability<?php echo $result->task_id; ?>">Disclose Lability on Client Email</label>
                  </td>

                  <td align="center">

                    <a href="javascript:" class="fa fa-envelope <?php echo $disabled; ?> email_unsent <?php if($result->last_email_sent != '0000-00-00 00:00:00') { echo 'show_popup'; } ?>" data-toggle="tooltip" title="Email Unsent FIles" aria-hidden="true" data-element="<?php echo $result->task_id; ?>"></a>

                    <a href="javascript:" class="<?php if($result->last_email_sent == '0000-00-00 00:00:00') { echo 'disabled'; } ?> resendemail_unsent" data-element="<?php echo $result->task_id; ?>">
                      <img src="<?php echo URL::to('assets/email_resend_icon.png')?>" class="resendemail_unsent <?php echo $disabled; ?>" data-toggle="tooltip" title="Resend Email" aria-hidden="true" data-element="<?php echo $result->task_id; ?>" style="margin-top: -3px; height: 12px; width: auto;">  
                      
                    </a>
                     <a href="javascript:" class="fa fa-file-text <?php if($result->last_email_sent == '0000-00-00 00:00:00') { echo 'disabled'; } ?> report_email_unsent" data-toggle="tooltip" title="Download Report as Pdf" aria-hidden="true" data-element="<?php echo $result->task_id; ?>"></a>
                    <?php
                    if($result->last_email_sent != '0000-00-00 00:00:00')
                    {
                      $get_dates = DB::table('task_email_sent')->where('task_id',$result->task_id)->get();
                      $last_date = '';
                      if(count($get_dates))
                      {
                        foreach($get_dates as $dateval)
                        {
                          $date = date('d F Y', strtotime($dateval->email_sent));
                          $time = date('H : i', strtotime($dateval->email_sent));
                          if($dateval->options != '0')
                          {
                            if($dateval->options == 'a') { $text = 'Fix an Error Created In House'; }
                            elseif($dateval->options == 'b') { $text = 'Fix an Error by Client or Implement a client Requested Change'; }
                            elseif($dateval->options == 'c') { $text = 'Combined In House and Client Prompted adjustments'; }
                            else{ $text= ''; }
                            $itag = '<span class="" title="'.$text.'" style="font-weight:800;"> ('.strtoupper($dateval->options).') </span>';
                          }
                          else{
                            $itag = '';
                          }
                          if($last_date == "")
                          {
                            $last_date = '<p>'.$date.' @ '.$time.' '.$itag.'</p>';
                          }
                          else{
                            $last_date = $last_date.'<p>'.$date.' @ '.$time.' '.$itag.'</p>';
                          }
                        }
                      }
                      else{
                        $date = date('d F Y', strtotime($result->last_email_sent));
                        $time = date('H : i', strtotime($result->last_email_sent));
                        $last_date = '<p>'.$date.' @ '.$time.'</p>';
                      }
                    }
                    else{
                      $last_date = '';
                    }
                    ?>

                    <label class="email_unsent_label"><?php echo $last_date; ?></label>

                  </td>

                  <td align="center">

                    <a href="javascript:" class="<?php echo $disabled; ?>" data-toggle="modal" data-target=".copy_task" data-element="<?php echo $result->task_id; ?>"><i class="fa fa-files-o <?php echo $disabled; ?>" data-toggle="tooltip" title="Copy Task" aria-hidden="true" data-element="<?php echo $result->task_id; ?>"></i></a>&nbsp;&nbsp;



                    <a href="javascript:" class="edit_task <?php echo $disabled; ?>" data-element="<?php echo $result->task_id; ?>"><i class="fa fa-pencil edit_task <?php echo $disabled; ?>" data-toggle="tooltip" title="Edit Task Name" aria-hidden="true" data-element="<?php echo $result->task_id; ?>"></i></a>&nbsp;&nbsp;



                    <a href="<?php echo URL::to('user/delete_task/'.base64_encode($result->task_id))?>" class="task_delete <?php echo $disabled; ?>"><i class="fa fa-trash task_delete <?php echo $disabled; ?>" data-toggle="tooltip" title="Delete Task" aria-hidden="true"></i></a>&nbsp;&nbsp;



                    <a href="javascript:" class="<?php if($result->task_complete_period == 1) { echo $disabled; } ?>"><i class="fa <?php if($result->task_status == 0) { echo 'fa-check'; } else{ echo 'fa-times'; } ?>" data-toggle="tooltip" <?php if($result->task_status == 0) { echo 'title="Mark as Completed"'; } else { echo 'title="Mark as Incomplete"'; }?> data-element="<?php echo $result->task_id; ?>" aria-hidden="true"></i></a>

                      <a href="javascript:" class="<?php if($result->task_status == 1) { echo $disabled; } ?>" <?php if($result->task_complete_period == 1) { echo 'style="color:#f00;"'; } ?>><i class="fa <?php if($result->task_complete_period == 0) { echo 'fa-exclamation-triangle donot_complete'; } else{ echo 'fa-ban do_complete'; } ?>" data-toggle="tooltip" <?php if($result->task_complete_period == 0) { echo 'title="Do not complete this Period"'; } else { echo 'title="Disable: Do not complete this Period"'; }?> data-element="<?php echo $result->task_id; ?>" aria-hidden="true"></i></a>
                    <br/><br/>
                     <div style="width: 100%; height: auto; float: left; font-size: 30px; color: <?php if($result->client_id == '') { echo 'red'; } else{ echo 'blue'; } ?>">
                      <i class="fa <?php if($result->client_id == '') { echo 'fa-chain-broken'; } else{ echo 'fa-link'; } ?>" data-toggle="tooltip" <?php if($result->client_id == '') { echo 'title="This task is not linked"'; } else { echo 'title="This task is linked"'; }?>></i>
                    </div>

                  </td>            

              </tr>

              <?php

                  $i++;

                  }

                  

               }

               

               else{

                  echo "<tr>

                        <td colspan='15' align='center'>Task not found</td></tr>";

               }

              ?>

          </tbody>

      </table>

      <p></p>

      </div>

    <?php } ?>

       <?php if(count($resultlist_complex)){ ?>

       <div class="table-responsive" style="max-width: 100%; float: left;  margin-top:55px">

       <label class="label_task"> Complex Task</label>

      <table class="table_bg table-fixed-header_2" style="width: 2000px; margin: 0px auto;margin-bottom:30px">

          <thead class="header">

            <tr class="background_bg">

                <th width="80px">S.No <i class="fa fa-sort sno_sort_cmp" aria-hidden="true"></th>

                <th width="80px"></th>

                <th width="250px">Task Name <i class="fa fa-sort task_sort_cmp" aria-hidden="true"></th>

                <th width="90px" style="width:90px !important;">Enter<br/>Hours</th>

                <th width="90px" style="width:90px !important;">Holiday<br/>Pay</th>

                <th width="90px" style="width:90px !important;">Process<br/>Payroll</th>

                <th width="90px" style="width:90px !important;">Upload<br/>Payslips</th>

                <th width="90px" style="width:90px !important;">Email<br/>Payslips</th>

                <th width="90px" style="width:90px !important;">Upload<br/>Report</th>

                <th width="200px">Date <i class="fa fa-sort date_sort_cmp" aria-hidden="true"></th>

                <th width="250px">Username <i class="fa fa-sort user_sort_cmp" aria-hidden="true"></th>

                <th width="350px">Email <i class="fa fa-sort email_sort_cmp" aria-hidden="true"></th>

                <th width="400px">Network Location</th>

                <th width="400px">Email Unsent Files</th>

                <th width="200px">Action</th>

            </tr>

          </thead>

          <tbody id="task_body_cmp">

            

              <?php 

              

               if(count($resultlist_complex)){

                  $i=1;

                  foreach ($resultlist_complex as $result) {

                    if($i < 10)

                  {

                    $i = '0'.$i;

                  }

              ?>

              <tr class="task_tr_cmp" id="taskidtr_<?php echo $result->task_id; ?>">

              <?php if($result->task_status == 1) { $disabled='disabled'; } elseif($result->task_complete_period == 1){ $disabled='disabled'; } else{ $disabled=''; } ?>
              <?php if($result->task_status == 1) { $task_label='style="color:#f00;font-weight:800"'; } elseif($result->task_complete_period == 1) { $task_label='style="color:#1b0fd4;font-weight:800"'; }  elseif($result->task_started == 1) { $task_label='style="color:#89ff00;font-weight:800"'; } else{ $task_label='style="color:#fff;font-weight:600"'; }  ?>

                  <td class="sno_sort_cmp_val"><?php echo $i;?></td>

                  <td>

                    <?php

                      if($result->task_started == 0){

                        echo '<input type="checkbox" name="task_started_checkbox" value="1" class="task_started_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';

                      }

                      else if($result->task_started == 1){

                        echo '<input type="checkbox" name="task_started_checkbox" value="1" class="task_started_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';

                      }

                    ?>

                  </td>

                  <td class="task_sort_cmp_val" align="left"><label class="task_label <?php echo $disabled; ?>" <?php echo $task_label; ?>><?php echo $result->task_name ?></label>

                    <br/><br/><br/>


                    <b style="color:#000; margin-bottom: 5px; width: 170px; height: auto; float: left;">Default Staff: </b>

                    <select class="form-control default_staff" data-element="<?php echo $result->task_id; ?>" <?php echo $disabled; ?>>                    
                    <?php
                    $userlist = DB::table('user')->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
                    $uname = '<option value="">Select Username</option>';
                      if(count($userlist)){
                        foreach ($userlist as $singleuser) {
                          if($result->default_staff == $singleuser->user_id) { $selected = 'selected'; } else{ $selected = ''; }
                            if($uname == '')
                              {
                                $uname = '<option value="'.$singleuser->user_id.'" '.$selected.'>'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';
                              }
                              else{
                                $uname = $uname.'<option value="'.$singleuser->user_id.'" '.$selected.'>'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';
                              }
                            }
                        }
                        echo $uname;
                    ?>
                  </select>

                  <br/>


                  <?php if($result->task_month > 24) { ?>
                  <b style="color:#000;">Last Period Complete : </b><br/>
                  <?php 
                  if($result->last_email_sent_carry == '0000-00-00 00:00:00') { echo 'Not complete Last period'; }
                  else{
                      $date_carry = date('d F Y', strtotime($result->last_email_sent_carry));

                      $time_carry = date('H : i', strtotime($result->last_email_sent_carry));

                      $last_date_carry = $date_carry.' <br/>@ '.$time_carry;
                      echo $last_date_carry;
                  }
                  }
                  ?>
                  </td>

                  <td align="center" class="special_td" style="width:90px;">

                    <?php

                      

                      if($result->enterhours == 0){

                        echo '<input type="checkbox" name="enterhours_checkbox" value="1" class="enterhours_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';

                      }

                      else if($result->enterhours == 1){

                        echo '<input type="checkbox" name="enterhours_checkbox" value="1" class="enterhours_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';

                      }

                      else{

                        echo 'N/A';

                      }

                    ?>

                    <div class="text_checkbox">ENTER<br/>HOURS</div>
                    <br/>
                    <div class="special_div" style="text-align: left;border-top:1px solid #000;width:550px !important;clear:both;float:left;position: absolute;">
                      

                      <style>
                      .single_notify, .all_notify{font-weight: bold;}
                      </style>

                      <?php 
                      if($result->task_notify == 1){
                        echo '<a href="javascript:" class="single_notify '.$disabled.'" data-element="'.$result->task_id.'">Notify</a> &nbsp &nbsp <a href="javascript:"  class="all_notify '.$disabled.'" data-element="'.$result->task_id.'">Notify all</a>';
                      }
                      ?>
                      <br/>
                      <i class="fa fa-plus faplus <?php echo $disabled; ?>" style="margin-top:10px" aria-hidden="true" title="Add Attachment"></i>

                          <i class="fa fa-pencil-square fanotepad <?php echo $disabled; ?>" style="margin-top:10px;margin-left:10px" aria-hidden="true" title="Add Notepad"></i>

                      <?php
                      
                      $attachments = DB::table('task_attached')->where('task_id',$result->task_id)->where('network_attach',1)->get();

                      if(count($attachments))

                      {
                        echo '<i class="fa fa-minus-square fadeleteall_attachments '.$disabled.'" data-element="'.$result->task_id.'" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>';
                        echo '<h5 style="color:#000; font-weight:600">Files Received :</h5>';
                        echo '<div class="scroll_attachment_div">';
                            foreach($attachments as $attachment)

                            {

                                echo '<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disabled.'"><i class="fa fa-trash trash_image '.$disabled.'" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';

                            }
                        echo '</div>';

                      }

                      ?>

                          <div class="img_div">

                            <form name="image_form" id="image_form" action="<?php echo URL::to('user/task_image_upload?type=2'); ?>" method="post" enctype="multipart/form-data" style="text-align: left;">

                              <input type="file" name="image_file[]" class="form-control image_file" value="" multiple>

                              <input type="hidden" name="hidden_id" value="<?php echo $result->task_id ?>">

                              <input type="submit" name="image_submit" class="btn btn-sm btn-primary image_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">

                              <spam class="error_files"></spam>

                            </form>
                            <div style="width:100%;text-align:center;margin-top:-10px;margin-bottom:10px;color:#000"><label style="font-weight:800;">OR</label></div>
                              <div class="image_div_attachments">
                                <form action="<?php echo URL::to('user/taskmanager_upload_images?task_id='.$result->task_id.'&type=2'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="image-upload" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid;">
                                <input name="_token" type="hidden" value="iceYGYrd7HqKzNlyAzFhbLh4Tu2FMEuijqGj5V3Q">
                                 
                                </form>
                                <a href="<?php echo URL::to('user/select_month/'.base64_encode($monthid->month_id).'?divid=taskidtr_'.$result->task_id); ?>" class="btn btn-sm btn-primary" align="left" style="margin-left:7px;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
                              </div>
                          </div>
                          <div class="notepad_div">

                              <form name="notepad_form" id="notepad_form" action="<?php echo URL::to('user/task_notepad_upload'); ?>" method="post" enctype="multipart/form-data" style="text-align: left;">

                                
                                <textarea name="notepad_contents" class="form-control notepad_contents" placeholder="Enter Contents"></textarea>

                                <input type="hidden" name="hidden_id" value="<?php echo $result->task_id ?>">

                                <input type="submit" name="notepad_submit" class="btn btn-sm btn-primary notepad_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">

                                <spam class="error_files_notepad"></spam>

                              </form>

                            </div>
                    </div>
                  </td>

                  <td align="center">

                    <?php

                      if($result->holiday == 0){

                        echo '<input type="checkbox" name="holiday_checkbox" value="1" class="holiday_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';

                      }

                      else if($result->holiday == 1){

                        echo '<input type="checkbox" name="holiday_checkbox" value="1" class="holiday_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';

                      }

                      else{

                        echo 'N/A';

                      }

                    ?>

                    <div class="text_checkbox">HOLIDAY<br/>PAY</div>

                  </td>

                  <td align="center">

                    <?php

                      if($result->process == 0){

                        echo '<input type="checkbox" name="process_checkbox" value="1" class="process_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';

                      }

                      else if($result->process == 1){

                        echo '<input type="checkbox" name="process_checkbox" value="1" class="process_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';

                      }

                      else{

                        echo 'N/A';

                      }

                    ?>

                    <div class="text_checkbox">PROCESS<br/>PAYROLL</div>

                  </td>

                  <td align="center">

                    <?php

                      if($result->payslips == 0){

                        echo '<input type="checkbox" name="payslips_checkbox" value="1" class="payslips_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';

                      }

                      else if($result->payslips == 1){

                        echo '<input type="checkbox" name="payslips_checkbox" value="1" class="payslips_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';

                      }

                      else{

                        echo 'N/A';

                      }

                    ?>

                    <div class="text_checkbox">UPLOAD<br/>PAYSLIPS</div>

                  </td>

                  <td align="center">

                    <?php

                      if($result->email == 0){

                        echo '<input type="checkbox" name="email_checkbox" value="1" class="email_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';

                      }

                      else if($result->email == 1){

                        echo '<input type="checkbox" name="email_checkbox" value="1" class="email_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';

                      }

                      else{

                        echo 'N/A';

                      }

                    ?>

                    <div class="text_checkbox">EMAIL<br/>PAYSLIPS</div>

                  </td>

                  <td align="center">

                    <?php

                      if($result->upload == 0){

                        echo '<input type="checkbox" name="upload_checkbox" value="1" class="upload_checkbox" data-element="'.$result->task_id.'" '.$disabled.'><label >&nbsp;</label>';

                      }

                      else if($result->upload == 1){

                        echo '<input type="checkbox" name="upload_checkbox" value="1" class="upload_checkbox" data-element="'.$result->task_id.'" checked '.$disabled.'><label >&nbsp;</label>';

                      }

                      else{

                        echo 'N/A';

                      }

                    ?>

                    <div class="text_checkbox">UPLOAD<br/>REPORT</div>

                  </td>

                  

                  <?php 

                    if($result->task_status == 1)

                    {

                      $seperatedate = explode(' ',$result->updatetime);

                      $explodedate = explode('-',$seperatedate[0]);

                      $explodetime = explode(':',$seperatedate[1]);

                      $date = $explodedate[1].'-'.$explodedate[2].'-'.$explodedate[0];

                      $time = $explodetime[0].':'.$explodetime[1];

                    }

                    else{

                      $date = 'MM-DD-YYYY';

                      $time = 'HH:MM';

                    }

                  ?>

                  <td class="date_sort_cmp_val" align="center">

                    <input type="text" class="form-control common_input datepicker date_input" value="<?php echo $date; ?>" data-element="<?php echo $result->task_id; ?>" style="text-align: center;" disabled/>

                    <input type="text" class="form-control common_input time_input" value="<?php echo $time; ?>" data-element="<?php echo $result->task_id; ?>" style="text-align: center;" disabled/>

                    

                  </td>

                  <td class="user_sort_cmp_val" align="center">

                    <select class="form-control common_input uname_input" data-element="<?php echo $result->task_id; ?>" <?php echo $disabled; ?>>

                        <?php
                            $userlist = DB::table('user')->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
                            $uname = '<option value="">Select Username</option>';
                              if(count($userlist)){
                                foreach ($userlist as $singleuser) {
                                  if($result->users == $singleuser->user_id) { $selected = 'selected'; } else{ $selected = ''; }
                                    if($uname == '')
                                      {
                                        $uname = '<option value="'.$singleuser->user_id.'" '.$selected.'>'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';
                                      }
                                      else{
                                        $uname = $uname.'<option value="'.$singleuser->user_id.'" '.$selected.'>'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';
                                      }
                                    }
                                }
                                echo $uname;
                            ?>
                    </select>

                    <textarea class="form-control common_input commandclass comments_input" id="<?php echo $result->task_id ?>" data-element="<?php echo $result->task_id; ?>" placeholder="Enter Your Comments Here" <?php echo $disabled; ?>><?php echo $result->comments ?></textarea>

                  </td>

                  <td class="email_sort_cmp_val" align="center">

                  

                    <input type="text" class="form-control common_input task_email_input" value="<?php echo $result->task_email ?>" data-element="<?php echo $result->task_id; ?>"  placeholder="Enter Email" disabled>

                  </td>

                  <td align="center" class="file_received_div" style="text-align: left">

                      <input type="text" class="form-control common_input network_input" name="" value="<?php echo $result->network ?>" disabled><br/>
                      <i class="fa fa-plus faplus <?php echo $disabled; ?>" style="margin-top:10px" aria-hidden="true" ></i>
                      <?php

                      $attachments = DB::table('task_attached')->where('task_id',$result->task_id)->where('network_attach',0)->get();

                      if(count($attachments))

                      {
                        echo '<i class="fa fa-minus-square fadeleteall '.$disabled.'" data-element="'.$result->task_id.'" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>';
                        echo '<h5 style="color:#000; font-weight:600">Attachments :</h5>';
                          echo '<div class="scroll_attachment_div">';

                            foreach($attachments as $attachment)

                            {

                                echo '<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disabled.'"><i class="fa fa-trash trash_image sample_trash '.$disabled.'" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';

                            }
                        echo '</div>';

                      }

                      ?>

                          <label style="width:100%;margin-top:15px">PAYE/PRSI/USC Liability:</label>
                          <?php if(count($attachments)) { ?>
                              <input type="textbox" name="liability_input" class="liability_input form-control input-sm" data-element="<?php echo $result->task_id; ?>" value="<?php echo $result->liability; ?>" <?php echo $disabled; ?>>
                          <?php } else { ?>
                              <input type="textbox" name="liability_input" class="liability_input form-control input-sm" data-element="<?php echo $result->task_id; ?>" value="" disabled>
                          <?php } ?>

                          <div class="img_div">

                            <label class="copy_label">Network location has been copied. Just paste the URL in the "File name" path to go to that folder.</label>

                            <form name="image_form" id="image_form" action="<?php echo URL::to('user/task_image_upload'); ?>" method="post" enctype="multipart/form-data" style="text-align: left;">

                              <input type="file" name="image_file[]" class="form-control image_file" value="" multiple>

                              <input type="hidden" name="hidden_id" value="<?php echo $result->task_id ?>">

                              <input type="submit" name="image_submit" class="btn btn-sm btn-primary image_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">

                              <spam class="error_files"></spam>

                            </form>
                            <div style="width:100%;text-align:center;margin-top:-10px;margin-bottom:10px;color:#000"><label style="font-weight:800;">OR</label></div>
                              <div class="image_div_attachments">
                                <form action="<?php echo URL::to('user/taskmanager_upload_images?task_id='.$result->task_id.'&type=1'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="image-upload" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid;">
                                <input name="_token" type="hidden" value="iceYGYrd7HqKzNlyAzFhbLh4Tu2FMEuijqGj5V3Q">
                                 
                                </form>
                                <a href="<?php echo URL::to('user/select_month/'.base64_encode($monthid->month_id).'?divid=taskidtr_'.$result->task_id); ?>" class="btn btn-sm btn-primary" align="left" style="margin-left:7px;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
                              </div>
                          </div>
                          <?php
                          if($result->disclose_liability == 1) { $check_lia = 'checked'; } else { $check_lia = ''; } ?>
                          <input type="checkbox" name="disclose_liability" id="disclose_liability<?php echo $result->task_id; ?>" class="disclose_liability" value="1" data-element="<?php echo $result->task_id; ?>" <?php echo $check_lia.' '.$disabled; ?>><label class="disclose_label" for="disclose_liability<?php echo $result->task_id; ?>">Disclose Lability on Client Email</label>
                  </td>

                  <td align="center">

                    <a href="javascript:" class="fa fa-envelope <?php echo $disabled; ?> email_unsent <?php if($result->last_email_sent != '0000-00-00 00:00:00') { echo 'show_popup'; } ?>" data-toggle="tooltip" title="Email Unsent FIles" aria-hidden="true" data-element="<?php echo $result->task_id; ?>"></a>

                    <a href="javascript:" class="<?php if($result->last_email_sent == '0000-00-00 00:00:00') { echo 'disabled'; } ?> resendemail_unsent" data-element="<?php echo $result->task_id; ?>">
                      <img src="<?php echo URL::to('assets/email_resend_icon.png')?>" class="resendemail_unsent <?php echo $disabled; ?>" data-toggle="tooltip" title="Resend Email" aria-hidden="true" data-element="<?php echo $result->task_id; ?>" style="margin-top: -3px; height: 12px; width: auto;">  
                      
                    </a>
                    <a href="javascript:" class="fa fa-file-text <?php if($result->last_email_sent == '0000-00-00 00:00:00') { echo 'disabled'; } ?> report_email_unsent" data-toggle="tooltip" title="Download Report as Pdf" aria-hidden="true" data-element="<?php echo $result->task_id; ?>"></a>

                    <?php
                    if($result->last_email_sent != '0000-00-00 00:00:00')
                    {
                      $get_dates = DB::table('task_email_sent')->where('task_id',$result->task_id)->get();
                      $last_date = '';
                      if(count($get_dates))
                      {
                        foreach($get_dates as $dateval)
                        {
                          $date = date('d F Y', strtotime($dateval->email_sent));
                          $time = date('H : i', strtotime($dateval->email_sent));
                          if($dateval->options != '0')
                          {
                            if($dateval->options == 'a') { $text = 'Fix an Error Created In House'; }
                            elseif($dateval->options == 'b') { $text = 'Fix an Error by Client or Implement a client Requested Change'; }
                            elseif($dateval->options == 'c') { $text = 'Combined In House and Client Prompted adjustments'; }
                            else{ $text= ''; }
                            $itag = '<span class="" title="'.$text.'" style="font-weight:800;"> ('.strtoupper($dateval->options).') </span>';
                          }
                          else{
                            $itag = '';
                          }
                          if($last_date == "")
                          {
                            $last_date = '<p>'.$date.' @ '.$time.' '.$itag.'</p>';
                          }
                          else{
                            $last_date = $last_date.'<p>'.$date.' @ '.$time.' '.$itag.'</p>';
                          }
                        }
                      }
                      else{
                        $date = date('d F Y', strtotime($result->last_email_sent));
                        $time = date('H : i', strtotime($result->last_email_sent));
                        $last_date = '<p>'.$date.' @ '.$time.'</p>';
                      }
                    }
                    else{
                      $last_date = '';
                    }
                    ?>

                    <label class="email_unsent_label"><?php echo $last_date; ?></label>

                  </td>

                  <td align="center">

                    <a href="javascript:" class="<?php echo $disabled; ?>" data-toggle="modal" data-target=".copy_task" data-element="<?php echo $result->task_id; ?>"><i class="fa fa-files-o <?php echo $disabled; ?>" data-toggle="tooltip" title="Copy Task" aria-hidden="true" data-element="<?php echo $result->task_id; ?>"></i></a>&nbsp;&nbsp;



                    <a href="javascript:" class="edit_task <?php echo $disabled; ?>" data-element="<?php echo $result->task_id; ?>"><i class="fa fa-pencil edit_task <?php echo $disabled; ?>" data-toggle="tooltip" title="Edit Task Name" aria-hidden="true" data-element="<?php echo $result->task_id; ?>"></i></a>&nbsp;&nbsp;



                    <a href="<?php echo URL::to('user/delete_task/'.base64_encode($result->task_id))?>" class="task_delete <?php echo $disabled; ?>"><i class="fa fa-trash task_delete <?php echo $disabled; ?>" data-toggle="tooltip" title="Delete Task" aria-hidden="true"></i></a>&nbsp;&nbsp;



                    <a href="javascript:" class="<?php if($result->task_complete_period == 1) { echo $disabled; } ?>"><i class="fa <?php if($result->task_status == 0) { echo 'fa-check'; } else{ echo 'fa-times'; } ?>" data-toggle="tooltip" <?php if($result->task_status == 0) { echo 'title="Mark as Completed"'; } else { echo 'title="Mark as Incomplete"'; }?> data-element="<?php echo $result->task_id; ?>" aria-hidden="true"></i></a>

                      <a href="javascript:" class="<?php if($result->task_status == 1) { echo $disabled; } ?>" <?php if($result->task_complete_period == 1) { echo 'style="color:#f00;"'; } ?>><i class="fa <?php if($result->task_complete_period == 0) { echo 'fa-exclamation-triangle donot_complete'; } else{ echo 'fa-ban do_complete'; } ?>" data-toggle="tooltip" <?php if($result->task_complete_period == 0) { echo 'title="Do not complete this Period"'; } else { echo 'title="Disable: Do not complete this Period"'; }?> data-element="<?php echo $result->task_id; ?>" aria-hidden="true"></i></a>

                    <br/><br/>
                     <div style="width: 100%; height: auto; float: left; font-size: 30px; color: <?php if($result->client_id == '') { echo 'red'; } else{ echo 'blue'; } ?>">
                      <i class="fa <?php if($result->client_id == '') { echo 'fa-chain-broken'; } else{ echo 'fa-link'; } ?>" data-toggle="tooltip" <?php if($result->client_id == '') { echo 'title="This task is not linked"'; } else { echo 'title="This task is linked"'; }?>></i>
                    </div>

                  </td>            

              </tr>

              <?php

                  $i++;

                  }

                  

               }

               

               else{

                  echo "<tr>

                        <td colspan='15' align='center'>Task not found</td></tr>";

               }

              ?>

          </tbody>

      </table>

      <p></p>

      </div>

    <?php } ?>

    

</div>

<input type="hidden" name="sno_sortoptions_std" id="sno_sortoptions_std" value="asc">

<input type="hidden" name="task_sortoptions_std" id="task_sortoptions_std" value="asc">

<input type="hidden" name="date_sortoptions_std" id="date_sortoptions_std" value="asc">

<input type="hidden" name="user_sortoptions_std" id="user_sortoptions_std" value="asc">

<input type="hidden" name="email_sortoptions_std" id="email_sortoptions_std" value="asc">

<input type="hidden" name="initial_sortoptions_std" id="initial_sortoptions_std" value="asc">



<input type="hidden" name="sno_sortoptions_enh" id="sno_sortoptions_enh" value="asc">

<input type="hidden" name="task_sortoptions_enh" id="task_sortoptions_enh" value="asc">

<input type="hidden" name="date_sortoptions_enh" id="date_sortoptions_enh" value="asc">

<input type="hidden" name="user_sortoptions_enh" id="user_sortoptions_enh" value="asc">

<input type="hidden" name="email_sortoptions_enh" id="email_sortoptions_enh" value="asc">

<input type="hidden" name="initial_sortoptions_enh" id="initial_sortoptions_enh" value="asc">



<input type="hidden" name="sno_sortoptions_cmp" id="sno_sortoptions_cmp" value="asc">

<input type="hidden" name="task_sortoptions_cmp" id="task_sortoptions_cmp" value="asc">

<input type="hidden" name="date_sortoptions_cmp" id="date_sortoptions_cmp" value="asc">

<input type="hidden" name="user_sortoptions_cmp" id="user_sortoptions_cmp" value="asc">

<input type="hidden" name="email_sortoptions_cmp" id="email_sortoptions_cmp" value="asc">

<input type="hidden" name="initial_sortoptions_cmp" id="initial_sortoptions_cmp" value="asc">



<div class="modal_load"></div>

<script>
$(document).ready(function () {
  $('#dtBasicExample').DataTable({
        fixedHeader: {
          headerOffset: 75
        },
        autoWidth: true,
        scrollX: false,
        fixedColumns: false,
        searching: false,
        paging: false,
        info: false,
        ordering: true
    });
});
$(document).ready(function() {

  $('.table-fixed-header').fixedHeader();

  $('.table-fixed-header_1').fixedHeader();

  $('.table-fixed-header_2').fixedHeader();



  if($("#show_incomplete").is(':checked'))

  {

    $(".edit_task").each(function() {

        if($(this).hasClass('disabled'))

        {

          $(this).parents('tr').hide();

        }

    });

  }

  else{

    $(".edit_task").each(function() {

        if($(this).hasClass('disabled'))

        {

          $(this).parents('tr').show();

        }

    });

  }

});

</script>

<?php

if(!empty($_GET['divid']))

{

  $divid = $_GET['divid'];

  ?>

  <script>

  $(function() {

    $(document).scrollTop( $("#<?php echo $divid; ?>").offset().top - parseInt(150) );  

  });

  </script>

  <?php

}

?>



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
            file.previewElement.innerHTML = "<p>"+obj.filename+" <a href='javascript:' class='remove_dropzone_attach' data-element='"+obj.id+"' data-task='"+obj.task_id+"'>Remove</a></p>";
        });
        this.on("removedfile", function(file) {
            if (!file.serverId) { return; }
            $.get("<?php echo URL::to('user/remove_property_images'); ?>"+"/"+file.serverId);
        });
    },
};
initSample();

  $( function() {

    $("#datepicker" ).datepicker({ dateFormat: 'mm-dd-yy' });

  } );

  $( function() {

    $(".datepicker" ).datepicker({ dateFormat: 'mm-dd-yy' });

  } );

  </script>

<script>

$(".commandclass").change( function(){

  var editid = $(this).attr("id");

  console.log(editid);

  $.ajax({

      url: "<?php echo URL::to('user/command_stire') ?>"+"/"+editid,

      dataType:"json",

      type:"post",

      success:function(result){

         

    }

  })

});

function SaveToDisk(fileURL, fileName) {

  if (!window.ActiveXObject) {

    var link = document.createElement('a');

    link.download = fileName;

      link.href = fileURL;

      link.click();

  }

  // for IE < 11

  else if ( !! window.ActiveXObject && document.execCommand)     {

    var _window = window.open(fileURL, '_blank');

    if (_window == null || typeof(_window)=='undefined')

    {

      alert('Please uncheck the option "Block Popup windows" to allow the popup window generated from our website. For iPads please go to general "Settings -> Safari -> Block Pop-ups and uncheck the option.');

    }

    _window.document.close();

    _window.document.execCommand('SaveAs', true, fileName || fileURL)

    _window.close();

  }

  $("body").removeClass("loading");

}

function copyToClipboard(element) {

  var $temp = $("<input>");

  $("body").append($temp);

  $temp.val($(element).val()).select();

  document.execCommand("copy");

  $temp.remove();

}



$(window).click(function(e) {
  if($(e.target).hasClass('disclose_liability'))
  {
    if($(e.target).is(":checked"))
    {
      var status = 1;
    }
    else{
      var status = 0;
    }
    var task_id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/save_disclose_liability'); ?>",
      type:"post",
      data:{task_id:task_id,status:status},
      success: function(result)
      {

      }
    });
  }
  if($(e.target).hasClass('report_email_unsent'))
  {
    $("body").addClass("loading");
    var task_id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/email_report_generator'); ?>",
      type:"get",
      data:{task_id:task_id},
      success: function(result)
      {
         $("body").removeClass("loading");
         SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);
      }
    })
  }
  if(e.target.id == "email_option_submit")
  {
      var value = $(".email_sent_options:checked").val();
      if(value == "" || typeof value === "undefined")
      {
          alert("Please select any one of the option to proceed");
      }
      else{
        $("#email_sent_option").val(value);
        $("#show_email_sent_popup").modal("hide");

        var task_id = $("#hidden_task_id_val").val();
        $.ajax({
          url:'<?php echo URL::to('user/edit_email_unsent_files'); ?>',
          type:'get',
          data:{task_id:task_id},
          dataType:"json",
          success: function(result)
          {
              CKEDITOR.instances['editor'].setData(result['html']);
              $("#email_attachments").html(result['files']);
              $(".subject_unsent").val(result['subject']);
              $("#select_user").val(result['from']);
              $("#to_user").val(result['to']);
              $(".emailunsent").modal('show');
          }
        })
      }
  }
  if($(e.target).hasClass('remove_dropzone_attach'))
  {
    var attachment_id = $(e.target).attr("data-element");
    var task_id = $(e.target).attr("data-task");
    $.ajax({
      url:"<?php echo URL::to('user/remove_dropzone_attachment'); ?>",
      type:"post",
      data:{attachment_id:attachment_id,task_id:task_id},
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

  if($(e.target).hasClass('notepad_submit'))

  {

    
    var contents = $(e.target).parent().find('.notepad_contents').val();

    if(contents == '' || typeof contents === 'undefined')

    {

      $(e.target).parent().find(".error_files_notepad").text("Please Enter the contents for the notepad to save.");

      return false;

    }

    else{

      $(e.target).parents('td').find('.notepad_div').toggle();

    }

  }

  else{

    $(".notepad_div").each(function() {

      $(this).hide();

    });

  }

  if(e.target.id == "alert_submit")

  {

    var company = $(".company_update:checked").val();

    var emp = $(".emp_update:checked").val();

    var email = $(".email_update:checked").val();

    var salutation = $(".salutation_update:checked").val();



    if(company == "" || typeof company === "undefined" || emp == "" || typeof emp === "undefined" || email == "" || typeof email === "undefined" || salutation == "" || typeof salutation === "undefined")

    {

      alert("Please select yes/no for all the questions.");

    }

    else{

      var clientid = $("#hidden_client_id").val();

      if(company == 1)

      {

        $.ajax({

          url:"<?php echo URL::to('user/getclientcompanyname'); ?>",

          type:"post",

          data:{clientid:clientid},

          success: function(result)

          {

            $("#taskname").val(result);

          }

        });

      }

      if(email == 1)

      {

        $.ajax({

          url:"<?php echo URL::to('user/getclientemail'); ?>",

          type:"post",

          data:{clientid:clientid},

          success: function(result)

          {

            $("#task_email_create").val(result);

          }

        });

      }

      if(emp == 1)

      {

        $("#hidden_client_emp").val(1);

      }

      else{

        $("#hidden_client_emp").val(0);

      }

      if(salutation == 1)

      {

        $("#hidden_client_salutation").val(1);

      }

      else{

        $("#hidden_client_salutation").val(0);

      }

      $("#alert_modal").modal("hide");

    }

  }

  if(e.target.id == "alert_submit_edit")

  {

    var company = $(".company_update_edit:checked").val();

    var emp = $(".emp_update_edit:checked").val();

    var email = $(".email_update_edit:checked").val();

    var salutation = $(".salutation_update_edit:checked").val();



    if(company == "" || typeof company === "undefined" || emp == "" || typeof emp === "undefined" || email == "" || typeof email === "undefined" || salutation == "" || typeof salutation === "undefined")

    {

      alert("Please select yes/no for all the questions.");

    }

    else{

      var clientid = $("#hidden_client_id_edit").val();

      if(company == 1)

      {

        $.ajax({

          url:"<?php echo URL::to('user/getclientcompanyname'); ?>",

          type:"post",

          data:{clientid:clientid},

          success: function(result)

          {

            $("#taskname_edit").val(result);

          }

        });

      }

      if(email == 1)

      {

        $.ajax({

          url:"<?php echo URL::to('user/getclientemail'); ?>",

          type:"post",

          data:{clientid:clientid},

          success: function(result)

          {

            $("#task_email_edit").val(result);

          }

        });

      }

      if(emp == 1)

      {

        $("#hidden_client_emp_edit").val(1);

      }

      else{

        $("#hidden_client_emp_edit").val(0);

      }

      if(salutation == 1)

      {

        $("#hidden_client_salutation_edit").val(1);

      }

      else{

        $("#hidden_client_salutation_edit").val(0);

      }

      $("#alert_modal_edit").modal("hide");

    }

  }

  if(e.target.id == 'show_incomplete')

  {

    if($(e.target).is(':checked'))

    {

       $(".edit_task").each(function() {

          if($(this).hasClass('disabled'))

          {

            $(this).parents('tr').hide();

          }

      });

      $.ajax({

        url:"<?php echo URL::to('user/update_incomplete_status_month'); ?>",

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

            $(this).parents('tr').show();

          }

      });

      $.ajax({

        url:"<?php echo URL::to('user/update_incomplete_status_month'); ?>",

        type:"post",

        data:{value:0},

        success: function(result)

        {

          

        }

      });

      

    }

  }

  if($(e.target).hasClass('faplus'))

  {

    var temp = $(e.target).parents('tr').find(".network_input");

    copyToClipboard(temp);

  }

  if($(e.target).hasClass('dropdown_download'))

  {

    $(".download_div").each(function() {

      $(this).hide();

    });

    $(e.target).parents('ul').find('.download_div').toggle();

  }

  if($(e.target).hasClass('dropdown_notify'))

  {

    if (CKEDITOR.instances.editor_1) CKEDITOR.instances.editor_1.destroy();

    $(".notify_div").each(function() {

      $(this).hide();

    });

    $(e.target).parents('ul').find('.notify_div').toggle();

  }

  if($(e.target).hasClass('close_xmark'))

  {

    $(e.target).parent().hide();

  }

  var ascending = false;

  

  if($(e.target).hasClass('email_unsent'))

  {
    if($(e.target).hasClass('show_popup'))
    {
      var task_id = $(e.target).attr('data-element');
      $("#hidden_task_id_val").val(task_id);
      $("#show_email_sent_popup").modal("show");
    }
    else{
      $("#email_sent_option").val("0");
      var task_id = $(e.target).attr('data-element');

      $.ajax({

        url:'<?php echo URL::to('user/edit_email_unsent_files'); ?>',

        type:'get',

        data:{task_id:task_id},

        dataType:"json",

        success: function(result)

        {

            CKEDITOR.instances['editor'].setData(result['html']);

            $("#email_attachments").html(result['files']);

            $(".subject_unsent").val(result['subject']);

            $("#select_user").val(result['from']);

            $("#to_user").val(result['to']);

            $(".emailunsent").modal('show');

        }

      })
    }
  }

  if($(e.target).hasClass('resendemail_unsent'))

  {

    var task_id = $(e.target).attr('data-element');

    $.ajax({

      url:'<?php echo URL::to('user/resendedit_email_unsent_files'); ?>',

      type:'get',

      data:{task_id:task_id},

      dataType:"json",

      success: function(result)

      {

          CKEDITOR.instances['editor_9'].setData(result['html']);

          $("#email_attachmentsresend").html(result['files']);

          $(".subject_resend").val(result['subject']);

          $("#select_userresend").val(result['from']);

          $("#to_userresend").val(result['to']);

          $(".resendemailunsent").modal('show');

      }

    })

  }

  if($(e.target).hasClass('sno_sort_std'))

  {

    var sort = $("#sno_sortoptions_std").val();

    if(sort == 'asc')

    {

      $("#sno_sortoptions_std").val('desc');

      var sorted = $('#task_body_std').find('.task_tr_std').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.sno_sort_std_val').html()) <

        convertToNumber($(b).find('.sno_sort_std_val').html()))) ? 1 : -1;

      });

    }

    else{

      $("#sno_sortoptions_std").val('asc');

      var sorted = $('#task_body_std').find('.task_tr_std').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.sno_sort_std_val').html()) <

        convertToNumber($(b).find('.sno_sort_std_val').html()))) ? -1 : 1;

      });

    }

    ascending = ascending ? false : true;

    $('#task_body_std').html(sorted);

  }

  if($(e.target).hasClass('sno_sort_enh'))

  {

    var sort = $("#sno_sortoptions_enh").val();

    if(sort == 'asc')

    {

      $("#sno_sortoptions_enh").val('desc');

      var sorted = $('#task_body_enh').find('.task_tr_enh').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.sno_sort_enh_val').html()) <

        convertToNumber($(b).find('.sno_sort_enh_val').html()))) ? 1 : -1;

      });

    }

    else{

      $("#sno_sortoptions_enh").val('asc');

      var sorted = $('#task_body_enh').find('.task_tr_enh').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.sno_sort_enh_val').html()) <

        convertToNumber($(b).find('.sno_sort_enh_val').html()))) ? -1 : 1;

      });

    }

    ascending = ascending ? false : true;

    $('#task_body_enh').html(sorted);

  }

  if($(e.target).hasClass('sno_sort_cmp'))

  {

    var sort = $("#sno_sortoptions_cmp").val();

    if(sort == 'asc')

    {

      $("#sno_sortoptions_cmp").val('desc');

      var sorted = $('#task_body_cmp').find('.task_tr_cmp').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.sno_sort_cmp_val').html()) <

        convertToNumber($(b).find('.sno_sort_cmp_val').html()))) ? 1 : -1;

      });

    }

    else{

      $("#sno_sortoptions_cmp").val('asc');

      var sorted = $('#task_body_cmp').find('.task_tr_cmp').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.sno_sort_cmp_val').html()) <

        convertToNumber($(b).find('.sno_sort_cmp_val').html()))) ? -1 : 1;

      });

    }

    ascending = ascending ? false : true;

    $('#task_body_cmp').html(sorted);

  }

  if($(e.target).hasClass('task_sort_std'))

  {

    var sort = $("#task_sortoptions_std").val();

    if(sort == 'asc')

    {

      $("#task_sortoptions_std").val('desc');

      var sorted = $('#task_body_std').find('.task_tr_std').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.task_sort_std_val').html()) <

        convertToNumber($(b).find('.task_sort_std_val').html()))) ? 1 : -1;

      });

    }

    else{

      $("#task_sortoptions_std").val('asc');

      var sorted = $('#task_body_std').find('.task_tr_std').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.task_sort_std_val').html()) <

        convertToNumber($(b).find('.task_sort_std_val').html()))) ? -1 : 1;

      });

    }

    ascending = ascending ? false : true;

    $('#task_body_std').html(sorted);

  }

  if($(e.target).hasClass('task_sort_enh'))

  {

    var sort = $("#task_sortoptions_enh").val();

    if(sort == 'asc')

    {

      $("#task_sortoptions_enh").val('desc');

      var sorted = $('#task_body_enh').find('.task_tr_enh').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.task_sort_enh_val').html()) <

        convertToNumber($(b).find('.task_sort_enh_val').html()))) ? 1 : -1;

      });

    }

    else{

      $("#task_sortoptions_enh").val('asc');

      var sorted = $('#task_body_enh').find('.task_tr_enh').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.task_sort_enh_val').html()) <

        convertToNumber($(b).find('.task_sort_enh_val').html()))) ? -1 : 1;

      });

    }

    ascending = ascending ? false : true;

    $('#task_body_enh').html(sorted);

  }

  if($(e.target).hasClass('task_sort_cmp'))

  {

    var sort = $("#task_sortoptions_cmp").val();

    if(sort == 'asc')

    {

      $("#task_sortoptions_cmp").val('desc');

      var sorted = $('#task_body_cmp').find('.task_tr_cmp').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.task_sort_cmp_val').html()) <

        convertToNumber($(b).find('.task_sort_cmp_val').html()))) ? 1 : -1;

      });

    }

    else{

      $("#task_sortoptions_cmp").val('asc');

      var sorted = $('#task_body_cmp').find('.task_tr_cmp').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.task_sort_cmp_val').html()) <

        convertToNumber($(b).find('.task_sort_cmp_val').html()))) ? -1 : 1;

      });

    }

    ascending = ascending ? false : true;

    $('#task_body_cmp').html(sorted);

  }

  if($(e.target).hasClass('date_sort_std'))

  {

    var sort = $("#date_sortoptions_std").val();

    if(sort == 'asc')

    {

      $("#date_sortoptions_std").val('desc');

      var sorted = $('#task_body_std').find('.task_tr_std').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.date_sort_std_val').find('.date_input').val()) <

        convertToNumber($(b).find('.date_sort_std_val').find('.date_input').val()))) ? 1 : -1;

      });

    }

    else{

      $("#date_sortoptions_std").val('asc');

      var sorted = $('#task_body_std').find('.task_tr_std').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.date_sort_std_val').find('.date_input').val()) <

        convertToNumber($(b).find('.date_sort_std_val').find('.date_input').val()))) ? -1 : 1;

      });

    }

    ascending = ascending ? false : true;

    $('#task_body_std').html(sorted);

  }

  if($(e.target).hasClass('date_sort_enh'))

  {

    var sort = $("#date_sortoptions_enh").val();

    if(sort == 'asc')

    {

      $("#date_sortoptions_enh").val('desc');

      var sorted = $('#task_body_enh').find('.task_tr_enh').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.date_sort_enh_val').find('.date_input').val()) <

        convertToNumber($(b).find('.date_sort_enh_val').find('.date_input').val()))) ? 1 : -1;

      });

    }

    else{

      $("#date_sortoptions_enh").val('asc');

      var sorted = $('#task_body_enh').find('.task_tr_enh').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.date_sort_enh_val').find('.date_input').val()) <

        convertToNumber($(b).find('.date_sort_enh_val').find('.date_input').val()))) ? -1 : 1;

      });

    }

    ascending = ascending ? false : true;

    $('#task_body_enh').html(sorted);

  }

  if($(e.target).hasClass('date_sort_cmp'))

  {

    var sort = $("#date_sortoptions_cmp").val();

    if(sort == 'asc')

    {

      $("#date_sortoptions_cmp").val('desc');

      var sorted = $('#task_body_cmp').find('.task_tr_cmp').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.date_sort_cmp_val').find('.date_input').val()) <

        convertToNumber($(b).find('.date_sort_cmp_val').find('.date_input').val()))) ? 1 : -1;

      });

    }

    else{

      $("#date_sortoptions_cmp").val('asc');

      var sorted = $('#task_body_cmp').find('.task_tr_cmp').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.date_sort_cmp_val').find('.date_input').val()) <

        convertToNumber($(b).find('.date_sort_cmp_val').find('.date_input').val()))) ? -1 : 1;

      });

    }

    ascending = ascending ? false : true;

    $('#task_body_cmp').html(sorted);

  }

  if($(e.target).hasClass('user_sort_std'))

  {

    var sort = $("#user_sortoptions_std").val();

    if(sort == 'asc')

    {

      $("#user_sortoptions_std").val('desc');

      var sorted = $('#task_body_std').find('.task_tr_std').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.user_sort_std_val').find('.uname_input').val()) <

        convertToNumber($(b).find('.user_sort_std_val').find('.uname_input').val()))) ? 1 : -1;

      });

    }

    else{

      $("#user_sortoptions_std").val('asc');

      var sorted = $('#task_body_std').find('.task_tr_std').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.user_sort_std_val').find('.uname_input').val()) <

        convertToNumber($(b).find('.user_sort_std_val').find('.uname_input').val()))) ? -1 : 1;

      });

    }

    ascending = ascending ? false : true;

    $('#task_body_std').html(sorted);

  }

  if($(e.target).hasClass('user_sort_enh'))

  {

    var sort = $("#user_sortoptions_enh").val();

    if(sort == 'asc')

    {

      $("#user_sortoptions_enh").val('desc');

      var sorted = $('#task_body_enh').find('.task_tr_enh').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.user_sort_enh_val').find('.uname_input').val()) <

        convertToNumber($(b).find('.user_sort_enh_val').find('.uname_input').val()))) ? 1 : -1;

      });

    }

    else{

      $("#user_sortoptions_enh").val('asc');

      var sorted = $('#task_body_enh').find('.task_tr_enh').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.user_sort_enh_val').find('.uname_input').val()) <

        convertToNumber($(b).find('.user_sort_enh_val').find('.uname_input').val()))) ? -1 : 1;

      });

    }

    ascending = ascending ? false : true;

    $('#task_body_enh').html(sorted);

  }

  if($(e.target).hasClass('user_sort_cmp'))

  {

    var sort = $("#user_sortoptions_cmp").val();

    if(sort == 'asc')

    {

      $("#user_sortoptions_cmp").val('desc');

      var sorted = $('#task_body_cmp').find('.task_tr_cmp').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.user_sort_cmp_val').find('.uname_input').val()) <

        convertToNumber($(b).find('.user_sort_cmp_val').find('.uname_input').val()))) ? 1 : -1;

      });

    }

    else{

      $("#user_sortoptions_cmp").val('asc');

      var sorted = $('#task_body_cmp').find('.task_tr_cmp').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.user_sort_cmp_val').find('.uname_input').val()) <

        convertToNumber($(b).find('.user_sort_cmp_val').find('.uname_input').val()))) ? -1 : 1;

      });

    }

    ascending = ascending ? false : true;

    $('#task_body_cmp').html(sorted);

  }

  if($(e.target).hasClass('email_sort_std'))

  {

    var sort = $("#email_sortoptions_std").val();

    if(sort == 'asc')

    {

      $("#email_sortoptions_std").val('desc');

      var sorted = $('#task_body_std').find('.task_tr_std').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.email_sort_std_val').find('.task_email_input').val()) <

        convertToNumber($(b).find('.email_sort_std_val').find('.task_email_input').val()))) ? 1 : -1;

      });

    }

    else{

      $("#email_sortoptions_std").val('asc');

      var sorted = $('#task_body_std').find('.task_tr_std').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.email_sort_std_val').find('.task_email_input').val()) <

        convertToNumber($(b).find('.email_sort_std_val').find('.task_email_input').val()))) ? -1 : 1;

      });

    }

    ascending = ascending ? false : true;

    $('#task_body_std').html(sorted);

  }

  if($(e.target).hasClass('email_sort_enh'))

  {

    var sort = $("#email_sortoptions_enh").val();

    if(sort == 'asc')

    {

      $("#email_sortoptions_enh").val('desc');

      var sorted = $('#task_body_enh').find('.task_tr_enh').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.email_sort_enh_val').find('.task_email_input').val()) <

        convertToNumber($(b).find('.email_sort_enh_val').find('.task_email_input').val()))) ? 1 : -1;

      });

    }

    else{

      $("#email_sortoptions_enh").val('asc');

      var sorted = $('#task_body_enh').find('.task_tr_enh').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.email_sort_enh_val').find('.task_email_input').val()) <

        convertToNumber($(b).find('.email_sort_enh_val').find('.task_email_input').val()))) ? -1 : 1;

      });

    }

    ascending = ascending ? false : true;

    $('#task_body_enh').html(sorted);

  }

  if($(e.target).hasClass('email_sort_cmp'))

  {

    var sort = $("#email_sortoptions_cmp").val();

    if(sort == 'asc')

    {

      $("#email_sortoptions_cmp").val('desc');

      var sorted = $('#task_body_cmp').find('.task_tr_cmp').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.email_sort_cmp_val').find('.task_email_input').val()) <

        convertToNumber($(b).find('.email_sort_cmp_val').find('.task_email_input').val()))) ? 1 : -1;

      });

    }

    else{

      $("#email_sortoptions_cmp").val('asc');

      var sorted = $('#task_body_cmp').find('.task_tr_cmp').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.email_sort_cmp_val').find('.task_email_input').val()) <

        convertToNumber($(b).find('.email_sort_cmp_val').find('.task_email_input').val()))) ? -1 : 1;

      });

    }

    ascending = ascending ? false : true;

    $('#task_body_cmp').html(sorted);

  }

  if($(e.target).hasClass('initial_sort_std'))

  {

    var sort = $("#initial_sortoptions_std").val();

    if(sort == 'asc')

    {

      $("#initial_sortoptions_std").val('desc');

      var sorted = $('#task_body_std').find('.task_tr_std').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.initial_sort_std_val').find('.initial_input').val()) <

        convertToNumber($(b).find('.initial_sort_std_val').find('.initial_input').val()))) ? 1 : -1;

      });

    }

    else{

      $("#initial_sortoptions_std").val('asc');

      var sorted = $('#task_body_std').find('.task_tr_std').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.initial_sort_std_val').find('.initial_input').val()) <

        convertToNumber($(b).find('.initial_sort_std_val').find('.initial_input').val()))) ? -1 : 1;

      });

    }

    ascending = ascending ? false : true;

    $('#task_body_std').html(sorted);

  }

  if($(e.target).hasClass('initial_sort_enh'))

  {

    var sort = $("#initial_sortoptions_enh").val();

    if(sort == 'asc')

    {

      $("#initial_sortoptions_enh").val('desc');

      var sorted = $('#task_body_enh').find('.task_tr_enh').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.initial_sort_enh_val').find('.initial_input').val()) <

        convertToNumber($(b).find('.initial_sort_enh_val').find('.initial_input').val()))) ? 1 : -1;

      });

    }

    else{

      $("#initial_sortoptions_enh").val('asc');

      var sorted = $('#task_body_enh').find('.task_tr_enh').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.initial_sort_enh_val').find('.initial_input').val()) <

        convertToNumber($(b).find('.initial_sort_enh_val').find('.initial_input').val()))) ? -1 : 1;

      });

    }

    ascending = ascending ? false : true;

    $('#task_body_enh').html(sorted);

  }

  if($(e.target).hasClass('initial_sort_cmp'))

  {

    var sort = $("#initial_sortoptions_cmp").val();

    if(sort == 'asc')

    {

      $("#initial_sortoptions_cmp").val('desc');

      var sorted = $('#task_body_cmp').find('.task_tr_cmp').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.initial_sort_cmp_val').find('.initial_input').val()) <

        convertToNumber($(b).find('.initial_sort_cmp_val').find('.initial_input').val()))) ? 1 : -1;

      });

    }

    else{

      $("#initial_sortoptions_cmp").val('asc');

      var sorted = $('#task_body_cmp').find('.task_tr_cmp').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.initial_sort_cmp_val').find('.initial_input').val()) <

        convertToNumber($(b).find('.initial_sort_cmp_val').find('.initial_input').val()))) ? -1 : 1;

      });

    }

    ascending = ascending ? false : true;

    $('#task_body_cmp').html(sorted);

  }

  if($(e.target).hasClass('edit_task'))

  {

      var task_id = $(e.target).attr('data-element');

      $.ajax({

        url:'<?php echo URL::to('user/edit_task_name'); ?>',

        type:'get',

        data:{task_id:task_id},

        dataType:"json",

        success: function(result)

        {
              $("#hidden_client_id_edit").val(result['client_id']);

            $(".task_name").val(result['task_name']);

            $(".task_email_edit").val(result['task_email']);

            $(".secondary_email_edit").val(result['secondary_email']);

            $(".salutation_edit").val(result['salutation']);

            $(".task_network").val(result['network']);

            $(".task_category").val(result['category']);

            $(".hidden_taskname_id").val(result['task_id']);

            $(".enumberclass").val(result['enumber']);





            $(".companyclass").val(result['companyname']);

            $(".tax_reg1class_edit").val(result['taxreg']);

            $(".primaryemail_class_edit").val(result['primaryemail']);

            $(".firstname_class_edit").val(result['firstname']);





            if(result['enterhours'] == 2)

            {

              $("#hours_na").prop("checked",true);

            }

            else{

              $("#hours_enter").prop("checked",true);

            }



            if(result['holiday'] == 2)

            {

              $("#holiday_na").prop("checked",true);

            }

            else{

              $("#holiday_enter").prop("checked",true);

            }



            if(result['process'] == 2)

            {

              $("#process_na").prop("checked",true);

            }

            else{

              $("#process_enter").prop("checked",true);

            }



            if(result['payslips'] == 2)

            {

              $("#payslips_na").prop("checked",true);

            }

            else{

              $("#payslips_enter").prop("checked",true);

            }



            if(result['email'] == 2)

            {

              $("#email_na").prop("checked",true);

            }

            else{

              $("#email_enter").prop("checked",true);

            }



            if(result['upload'] == 2)

            {

              $("#report_na").prop("checked",true);

            }

            else{

              $("#report_enter").prop("checked",true);

            }



            $(".tasklevel_edit").val(result['tasklevel']);



            if(result['p30_pay'] == 1)

            {

              $("#p30_pay_yes").prop("checked",true);

            }

            else{

              $("#p30_pay_no").prop("checked",true);

            }



            if(result['p30_email'] == 1)

            {

              $("#p30_email_yes").prop("checked",true);

            }

            else{

              $("#p30_email_no").prop("checked",true);

            }



            $(".edit_task_modal").modal('show');

        }

      })

  }

  if($(e.target).hasClass('cancel_month'))

  {

      window.location.reload();

  }

  if(e.target.id == 'close_create_new_month')

  {

    var r = confirm("New Month will be created and all the task in this month will be copied in newly created month. Are you sure you want to continue?");

    if (r == true) {

        

    } else {

        return false;

    }

  }

  if(e.target.id == 'email_report_button')

  {

    var id = '<?php echo $monthid->month_id; ?>';

    $.ajax({

        url:"<?php echo URL::to('user/email_report_pdf_month'); ?>",

        type:"get",

        data:{id:id},

        success: function(result) {

          $(".subject_report").val(result);

          $("#email_report_model").modal('show');

          $("#task_report_label").show();

          $("#notify_report_label").hide();

          $("#hidden_report_type").val('task_report');

        }

    });

  }

  if(e.target.id == 'email_notify')

  {

    $(".notify_modal").modal('hide');

    var message = CKEDITOR.instances['editor_1'].getData();

    $("body").addClass("loading");

    

    var emails = [];

    var toemails = '';
    var timeval = "<?php echo time(); ?>";

    $(".notify_option").each(function(i, el) {

      var id = $(el).attr('data-element');

        if($(el).is(':checked'))

        {

          var user_email = $(el).parents('tr').find(".notify_primary_email").val();

          var secondary_email = $(el).parents('tr').find(".notify_secondary_email").val();

          

          if(user_email != '' && typeof user_email !== 'undefined')

          {

            if($.inArray(user_email, emails) == -1)

            {

              emails.push(user_email);

              if(toemails == '')

              {

                toemails= user_email;

              }

              else{

                toemails = toemails+', '+user_email;

              }

            }

          }

          

          if(secondary_email != '' && typeof secondary_email !== 'undefined')

          {

            if($.inArray(secondary_email, emails) == -1)

            {

              emails.push(secondary_email);

              if(toemails == '')

              {

                toemails= secondary_email;

              }

              else{

                toemails = toemails+', '+secondary_email;

              }

            }

          }

        }

    });

    toemails = toemails+', <?php echo $admin_cc; ?>';

    var option_length = emails.length;

    $.each( emails, function( i, value ) {

        setTimeout(function(){

          $.ajax({

            url:"<?php echo URL::to('user/email_notify_pdf'); ?>",

            type:"get",

            data:{email:value,message:message,toemails:toemails,week:"0",month:"<?php echo $monthid->month_id; ?>",timeval:timeval},

            success: function(result) {

              var keyi = parseInt(i) + parseInt(1);

              if(option_length == keyi)

              {

                $("body").removeClass("loading");

              }

            }

          });

        },2000 + ( i * 2000 ));

    });    

  }



  if(e.target.id == 'task_standard')

  {

    if (CKEDITOR.instances.editor_1) CKEDITOR.instances.editor_1.destroy();

    var id = '<?php echo $monthid->month_id; ?>';

    var value = 1;

    $("#notify_type").val(1);

    $.ajax({

        url:"<?php echo URL::to('user/notify_tasks_month'); ?>",

        type:"get",

        data:{id:id,value:value},

        success: function(result) {

          $(".notify_modal").modal('show');

          $(".notify_place_div").html(result);

          setTimeout(function(){  

             CKEDITOR.replace('editor_1',

             {

              height: '150px',

             }); 

          },1000);

        }

    });

  }

  if(e.target.id == 'task_enhanced')

  {

    if (CKEDITOR.instances.editor_1) CKEDITOR.instances.editor_1.destroy();

    var id = '<?php echo $monthid->month_id; ?>';

    var value = 2;

    $("#notify_type").val(2);

    $.ajax({

        url:"<?php echo URL::to('user/notify_tasks_month'); ?>",

        type:"get",

        data:{id:id,value:value},

        success: function(result) {

          $(".notify_modal").modal('show');

          $(".notify_place_div").html(result);

          setTimeout(function(){  

             CKEDITOR.replace('editor_1',

             {

              height: '150px',

             }); 

          },1000);

        }

    });

  }

  if(e.target.id == 'task_complex')

  {

    if (CKEDITOR.instances.editor_1) CKEDITOR.instances.editor_1.destroy();

    var id = '<?php echo $monthid->month_id; ?>';

    var value = 3;

    $("#notify_type").val(3);

    $.ajax({

        url:"<?php echo URL::to('user/notify_tasks_month'); ?>",

        type:"get",

        data:{id:id,value:value},

        success: function(result) {

          $(".notify_modal").modal('show');

          $(".notify_place_div").html(result);

          setTimeout(function(){  

             CKEDITOR.replace('editor_1',

             {

              height: '150px',

             }); 

          },1000);

        }

    });

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

    return false; //this is critical to stop the click event which will trigger a normal file download

  }

  if(e.target.id == 'all_tasks')

  {

    var id = '<?php echo $monthid->month_id; ?>';

    $("body").addClass("loading");

    $.ajax({

        url:"<?php echo URL::to('user/alltask_report_pdf_month'); ?>",

        type:"get",

        data:{id:id},

        success: function(result) {

          $("body").removeClass("loading");

          SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);

            return false; //this is critical to stop the click event which will trigger a normal file download

        }

    });

  }

  if(e.target.id == 'task_completed')

  {

    var id = '<?php echo $monthid->month_id; ?>';

    $("body").addClass("loading");

    $.ajax({

        url:"<?php echo URL::to('user/task_complete_report_pdf_month'); ?>",

        type:"get",

        data:{id:id},

        success: function(result) {

          $("body").removeClass("loading");

          SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);

            return false; //this is critical to stop the click event which will trigger a normal file download

        }

    });

  }

  if(e.target.id == 'task_incomplete')

  {

    var id = '<?php echo $monthid->month_id; ?>';

    $("body").addClass("loading");

    $.ajax({

        url:"<?php echo URL::to('user/task_incomplete_report_pdf_month'); ?>",

        type:"get",

        data:{id:id},

        success: function(result) {

          $("body").removeClass("loading");

          SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);

            return false; //this is critical to stop the click event which will trigger a normal file download

        }

    });

  }

  if($(e.target).hasClass('year_button'))

  {

    $(".year_button").each(function() {

      $(this).removeClass('highlight_button');

    });

    $(e.target).addClass('highlight_button');

    $("#hidden_copy_year").val($(e.target).attr('data-element'));

    $("#select_year_type").val('');

    $(".weekly_select").html('');

  }

  if($(e.target).hasClass('month_button'))

  {

    $(".month_button").each(function() {

      $(this).removeClass('highlight_button');

    });

    $(e.target).addClass('highlight_button');

    $("#hidden_copy_month").val($(e.target).attr('data-element'));

    $(".category_select_copy").show();

  }

  if($(e.target).hasClass('week_button'))

  {

    $(".week_button").each(function() {

      $(this).removeClass('highlight_button');

    });

    $(e.target).addClass('highlight_button');

    $("#hidden_copy_week").val($(e.target).attr('data-element'));

    $(".category_select_copy").show();

  }

  if($(e.target).hasClass('fa-files-o'))

  {

    var element = $(e.target).attr('data-element');

    $("#hidden_task_id").val(element);

  }

  if($(e.target).hasClass('task_delete'))

  {

    var r = confirm("Are You Sure you want to delete this task");

    if (r == true) {



    }

    else{

      return false

    }

  }

  if($(e.target).hasClass('fa-check'))

  {

    var liability = $(e.target).parents('tr').find('.liability_input').prop('disabled');

    if(liability == 1)
    {
      
    }
    else{
        var liabilityval = $(e.target).parents('tr').find('.liability_input').val();
        if(liabilityval == "")
        {
          alert("You CAN NOT mark the wages as done until the the libility text box is filled in!");
          return false;
        }
    }

    var user = $(e.target).parents('tr').find(".uname_input").val();



    var enterhours = $(e.target).parents('tr').find(".enterhours_checkbox").length;

    var holiday = $(e.target).parents('tr').find(".holiday_checkbox").length;

    var process_check = $(e.target).parents('tr').find(".process_checkbox").length;

    var payslips = $(e.target).parents('tr').find(".payslips_checkbox").length;

    var email_check = $(e.target).parents('tr').find(".email_checkbox").length;

    var upload = $(e.target).parents('tr').find(".upload_checkbox").length;



    var enterhours_val = $(e.target).parents('tr').find(".enterhours_checkbox:checked").val();

    var holiday_val = $(e.target).parents('tr').find(".holiday_checkbox:checked").val();

    var process_check_val = $(e.target).parents('tr').find(".process_checkbox:checked").val();

    var payslips_val = $(e.target).parents('tr').find(".payslips_checkbox:checked").val();

    var email_check_val = $(e.target).parents('tr').find(".email_checkbox:checked").val();

    var upload_val = $(e.target).parents('tr').find(".upload_checkbox:checked").val();



    if(user == '' || typeof user === 'undefined')

    {

      alert('Please Select the username for this task to make Mark as Complete');

    }

    else{

      if((enterhours == 1 && typeof enterhours_val === 'undefined') || (holiday == 1 && typeof holiday_val === 'undefined') || (process_check == 1 && typeof process_check_val === 'undefined') || (payslips == 1 && typeof payslips_val === 'undefined') || (email_check == 1 && typeof email_check_val === 'undefined') || (upload == 1 && typeof upload_val === 'undefined'))

      {

        alert('You must CHECK all the boxes of this task to Mark this task as Complete');

      }

      else{

          var r = confirm("Please note that if you Mark this Task as Complete then all the fields will be disabled and you won't be able to change until you mark this task as incomplete again.");

          if (r == true) {

              var id = $(e.target).attr('data-element');

              $.ajax({

                  url:"<?php echo URL::to('user/task_status_update'); ?>",

                  type:"get",

                  dataType:"json",

                  data:{status:1,id:id},

                  success: function(result) {

                    $(e.target).parents('tr').find(".date_input").val(result['date']);

                    $(e.target).parents('tr').find(".time_input").val(result['time']);

                    $(e.target).parents('tr').find("select").each(function(){

                      $(this).prop('disabled',true);

                    });

                    $(e.target).parents('tr').find("textarea").each(function(){

                      $(this).prop('disabled',true);

                    });

                    $(e.target).parents('tr').find(".liability_input").prop("disabled",true);

                    $(e.target).parents('tr').find('.fa-trash').each(function() {

                      $(this).addClass('disabled');

                    });

                    $(e.target).parents('tr').find('.fa-trash').addClass('disabled');
                    $(e.target).parents('tr').find('.fa-trash').parent().addClass('disabled');



                    $(e.target).parents('tr').find(".task_started_checkbox").addClass('disabled');

                    $(e.target).parents('tr').find(".enterhours_checkbox").addClass('disabled');

                    $(e.target).parents('tr').find(".holiday_checkbox").addClass('disabled');

                    $(e.target).parents('tr').find(".process_checkbox").addClass('disabled');

                    $(e.target).parents('tr').find(".payslips_checkbox").addClass('disabled');

                    $(e.target).parents('tr').find(".email_checkbox").addClass('disabled');

                    $(e.target).parents('tr').find(".upload_checkbox").addClass('disabled');



                    $(e.target).parents('tr').find('.fa-plus').addClass('disabled');
                    $(e.target).parents('tr').find('.fa-pencil-square').addClass('disabled');

                    $(e.target).parents('tr').find('.fa-minus-square').addClass('disabled');

                    $(e.target).parents('tr').find('.task_label').addClass('disabled');

                    $(e.target).parents('tr').find('.edit_task').addClass('disabled');

                    $(e.target).parents('tr').find('.fa-files-o').addClass('disabled');

                    $(e.target).parents('tr').find('.task_delete').addClass('disabled');

                    $(e.target).parents('tr').find('.single_notify').addClass('disabled');
              $(e.target).parents('tr').find('.all_notify').addClass('disabled');
              $(e.target).parents('tr').find('.email_unsent').addClass('disabled');
              $(e.target).parents('tr').find('.resendemail_unsent').addClass('disabled');
              $(e.target).parents('tr').find('.report_email_unsent').addClass('disabled');


              

              $(e.target).parents('tr').find('.donot_complete').addClass('disabled');
              $(e.target).parents('tr').find('.do_complete').addClass('disabled');

              $(e.target).parents('tr').find('.donot_complete').parent().addClass('disabled');
              $(e.target).parents('tr').find('.do_complete').parent().addClass('disabled');

                    $(e.target).parents('tr').find('.task_label').css({'color':'#f00','font-weight':'800'});

                    $(e.target).removeClass('fa-check');

                    $(e.target).addClass('fa-times');

                    $(e.target).attr("data-original-title","Mark as Incomplete");



                    if($("#show_incomplete").is(':checked'))

                    {

                      $(".edit_task").each(function() {

                          if($(this).hasClass('disabled'))

                          {

                            $(this).parents('tr').hide();

                          }

                      });

                    }

                    else{

                      $(".edit_task").each(function() {

                          if($(this).hasClass('disabled'))

                          {

                            $(this).parents('tr').show();

                          }

                      });

                    }

                  }

              });

          }

      }

    }

  }

  if($(e.target).hasClass('fa-times'))

  {

    var r = confirm("Unfreezing will enable all the input fields that you can change all the details");

    if (r == true) {

      var id = $(e.target).attr('data-element');



      $.ajax({

          url:"<?php echo URL::to('user/task_status_update'); ?>",

          type:"get",

          data:{status:0,id:id},

          success: function(result) {

            $(e.target).parents('tr').find(".date_input").val('MM-DD-YYYY');

            $(e.target).parents('tr').find(".time_input").val('HH:MM');

            $(e.target).parents('tr').find("select").each(function(){

              $(this).prop('disabled',false);

            });

            $(e.target).parents('tr').find("textarea").each(function(){

              $(this).prop('disabled',false);

            });

            $(e.target).parents('tr').find(".liability_input").prop("disabled",false);

            $(e.target).parents('tr').find('.fa-trash').each(function() {

              $(this).removeClass('disabled');

            });

            $(e.target).parents('tr').find('.fa-trash').removeClass('disabled');
                    $(e.target).parents('tr').find('.fa-trash').parent().removeClass('disabled');


            $(e.target).parents('tr').find(".task_started_checkbox").removeClass('disabled');

            $(e.target).parents('tr').find(".enterhours_checkbox").removeClass('disabled');

            $(e.target).parents('tr').find(".holiday_checkbox").removeClass('disabled');

            $(e.target).parents('tr').find(".process_checkbox").removeClass('disabled');

            $(e.target).parents('tr').find(".payslips_checkbox").removeClass('disabled');

            $(e.target).parents('tr').find(".email_checkbox").removeClass('disabled');

            $(e.target).parents('tr').find(".upload_checkbox").removeClass('disabled');



            $(e.target).parents('tr').find('.fa-plus').removeClass('disabled');
            $(e.target).parents('tr').find('.fa-pencil-square').removeClass('disabled');

            $(e.target).parents('tr').find('.fa-minus-square').removeClass('disabled');



            $(e.target).parents('tr').find('.task_label').removeClass('disabled');

            $(e.target).parents('tr').find('.edit_task').removeClass('disabled');

            $(e.target).parents('tr').find('.fa-files-o').removeClass('disabled');

            $(e.target).parents('tr').find('.task_delete').removeClass('disabled');

            $(e.target).parents('tr').find('.single_notify').removeClass('disabled');
              $(e.target).parents('tr').find('.all_notify').removeClass('disabled');
              $(e.target).parents('tr').find('.email_unsent').removeClass('disabled');

              if($(e.target).parents('tr').find('.email_unsent_label').html() == "")
              {
                
              }
              else{
                $(e.target).parents('tr').find('.resendemail_unsent').removeClass('disabled');
                $(e.target).parents('tr').find('.report_email_unsent').removeClass('disabled');
              }


              $(e.target).parents('tr').find('.donot_complete').removeClass('disabled');
              $(e.target).parents('tr').find('.do_complete').removeClass('disabled');

              $(e.target).parents('tr').find('.donot_complete').parent().removeClass('disabled');
              $(e.target).parents('tr').find('.do_complete').parent().removeClass('disabled');

            if($(e.target).parents('tr').find(".task_started_checkbox").is(":checked"))
            {
              $(e.target).parents('tr').find('.task_label').css({'color':'#89ff00','font-weight':'800'});
            }
            else{
              $(e.target).parents('tr').find('.task_label').css({'color':'#fff','font-weight':'600'});
            }

            $(e.target).removeClass('fa-times');

            $(e.target).addClass('fa-check');

            $(e.target).attr("data-original-title","Mark as Completed");



            if($("#show_incomplete").is(':checked'))

            {

              $(".edit_task").each(function() {

                  if($(this).hasClass('disabled'))

                  {

                    $(this).parents('tr').hide();

                  }

              });

            }

            else{

              $(".edit_task").each(function() {

                  if($(this).hasClass('disabled'))

                  {

                    $(this).parents('tr').show();

                  }

              });

            }

          }

      });

    }

  }

  if($(e.target).hasClass('trash_image'))

  {

    if($(e.target).hasClass('sample_trash'))
    {
      var attach_count = $(e.target).parents('.file_received_div').find(".fileattachment").length;
      if(attach_count == 1)
      {
          var r = confirm("Are You sure you want to delete this image and Do you want to Remove the Liability Recorded and update it later?");
      }
      else{
            var r = confirm("Are You sure you want to delete this image");
      }
    }
    else{
      var r = confirm("Are You sure you want to delete this image");
    }

    if (r == true) {

      var imgid = $(e.target).attr('data-element');



      $.ajax({

          url:"<?php echo URL::to('user/task_delete_image'); ?>",

          type:"get",

          data:{imgid:imgid},

          success: function(result) {

            window.location.reload();

          }

      });

    }

  }

  if($(e.target).hasClass('fadeleteall'))

  {

    var r = confirm("Are You sure you want to delete all the attachments and Do you want to Remove the Liability Recorded and update it later?");

    if (r == true) {

      var taskid = $(e.target).attr('data-element');



      $.ajax({

          url:"<?php echo URL::to('user/task_delete_all_image'); ?>",

          type:"get",

          data:{taskid:taskid},

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

      var taskid = $(e.target).attr('data-element');



      $.ajax({

          url:"<?php echo URL::to('user/task_delete_all_image_attachments'); ?>",

          type:"get",

          data:{taskid:taskid},

          success: function(result) {

            window.location.reload();

          }

      });

    }

  }

  if($(e.target).hasClass('fa-plus'))

  {

    var pos = $(e.target).position();

    var leftposi = parseInt(pos.left) - 200;

    $(e.target).parent().find('.img_div').css({"position":"absolute","top":pos.top,"left":leftposi}).toggle();

  }

  if($(e.target).hasClass('fa-pencil-square'))

  {

    var pos = $(e.target).position();

    var leftposi = parseInt(pos.left) - 200;

    $(e.target).parent().find('.notepad_div').css({"position":"absolute","top":pos.top,"left":leftposi}).toggle();

  }

  if($(e.target).hasClass('image_file'))

  {

    $(e.target).parents('td').find('.img_div').toggle();

  }

  if($(e.target).hasClass("dropzone"))
  {
    $(e.target).parents('td').find('.img_div').show();    
  }
  if($(e.target).hasClass("remove_dropzone_attach"))
  {
    $(e.target).parents('td').find('.img_div').show();    
  }
  if($(e.target).parent().hasClass("dz-message"))
  {
    $(e.target).parents('td').find('.img_div').show();    
  }

  if($(e.target).hasClass('notepad_contents'))

  {

    $(e.target).parents('td').find('.notepad_div').toggle();

  }

  if($(e.target).hasClass('task_started_checkbox'))

  {

    var id = $(e.target).attr('data-element');

    if($(e.target).is(':checked'))

    {



      $.ajax({

        url:"<?php echo URL::to('user/task_started_checkbox'); ?>",

        type:"get",

        data:{task_started:1,id:id},

        success: function(result) {

          $(e.target).parents('tr').find('.task_label').css({'color':'#89ff00','font-weight':'800'});

        }

      });

    }

    else{

      $.ajax({

        url:"<?php echo URL::to('user/task_started_checkbox'); ?>",

        type:"get",

        data:{task_started:0,id:id},

        success: function(result) {

          $(e.target).parents('tr').find('.task_label').css({'color':'#fff','font-weight':'600'});

        }

      });

    }

  }

  if($(e.target).hasClass('enterhours_checkbox'))

  {

    var id = $(e.target).attr('data-element');

    if($(e.target).is(':checked'))

    {



      $.ajax({

        url:"<?php echo URL::to('user/task_enterhours'); ?>",

        type:"get",

        data:{enterhouse:1,id:id},

        success: function(result) {

          $

        }

      });

    }

    else{

      $.ajax({

        url:"<?php echo URL::to('user/task_enterhours'); ?>",

        type:"get",

        data:{enterhouse:0,id:id},

        success: function(result) {



        }

      });

    }

  }

  if($(e.target).hasClass('holiday_checkbox'))

  {

    var id = $(e.target).attr('data-element');

    if($(e.target).is(':checked'))

    {



      $.ajax({

        url:"<?php echo URL::to('user/task_holiday'); ?>",

        type:"get",

        data:{holiday:1,id:id},

        success: function(result) {



        }

      });

    }

    else{

      $.ajax({

        url:"<?php echo URL::to('user/task_holiday'); ?>",

        type:"get",

        data:{holiday:0,id:id},

        success: function(result) {



        }

      });

    }

  }

  if($(e.target).hasClass('process_checkbox'))

  {

    var id = $(e.target).attr('data-element');

    if($(e.target).is(':checked'))

    {



      $.ajax({

        url:"<?php echo URL::to('user/task_process'); ?>",

        type:"get",

        data:{process:1,id:id},

        success: function(result) {



        }

      });

    }

    else{

      $.ajax({

        url:"<?php echo URL::to('user/task_process'); ?>",

        type:"get",

        data:{process:0,id:id},

        success: function(result) {



        }

      });

    }

  }

  if($(e.target).hasClass('payslips_checkbox'))

  {

    var id = $(e.target).attr('data-element');

    if($(e.target).is(':checked'))

    {



      $.ajax({

        url:"<?php echo URL::to('user/task_payslips'); ?>",

        type:"get",

        data:{payslips:1,id:id},

        success: function(result) {



        }

      });

    }

    else{

      $.ajax({

        url:"<?php echo URL::to('user/task_payslips'); ?>",

        type:"get",

        data:{payslips:0,id:id},

        success: function(result) {



        }

      });

    }

  }

  if($(e.target).hasClass('email_checkbox'))

  {

    var id = $(e.target).attr('data-element');

    if($(e.target).is(':checked'))

    {



      $.ajax({

        url:"<?php echo URL::to('user/task_email'); ?>",

        type:"get",

        data:{email:1,id:id},

        success: function(result) {



        }

      });

    }

    else{

      $.ajax({

        url:"<?php echo URL::to('user/task_email'); ?>",

        type:"get",

        data:{email:0,id:id},

        success: function(result) {



        }

      });

    }

  }

  if($(e.target).hasClass('upload_checkbox'))

  {

    var id = $(e.target).attr('data-element');

    if($(e.target).is(':checked'))

    {



      $.ajax({

        url:"<?php echo URL::to('user/task_upload'); ?>",

        type:"get",

        data:{upload:1,id:id},

        success: function(result) {



        }

      });

    }

    else{

      $.ajax({

        url:"<?php echo URL::to('user/task_upload'); ?>",

        type:"get",

        data:{upload:0,id:id},

        success: function(result) {



        }

      });

    }

  }

  if($(e.target).hasClass('single_notify')){
    var taskid = $(e.target).attr("data-element");
    $(".model_notify").modal("show");
    $(".notify_title").html('Send Notification to Selected Staffs');
    $(".notify_task_id").val(taskid);
    $(".notify_id_class").prop("checked", false);
    $(".notify_id_class").prop("disabled", false);

    $("#notity_selectall").prop("checked", false);
    $("#notity_selectall").prop("disabled", false);

  }

  if($(e.target).hasClass('all_notify')){
    var taskid = $(e.target).attr("data-element");
    $(".model_notify").modal("show");
    $(".notify_title").html('Send Notification to All Staffs');
    $(".notify_task_id").val(taskid);
    $(".notify_id_class").prop("checked", true);
    $(".notify_id_class").attr("readonly", true);

    $("#notity_selectall").prop("checked", true);
    $("#notity_selectall").prop("disabled", true);
  }

  if(e.target.id == "notity_selectall"){
    if($(e.target).is(":checked"))
    {
      $(".notify_id_class").each(function() {
        $(this).prop("checked",true);
      });
    }

    else{
      $(".notify_id_class").each(function() {
        $(this).prop("checked",false);
      });
    }
  }
/*
  if($(e.target).hasClass('donot_complete')) {
    $("body").addClass("loading");
    var taskid = $(e.target).attr("data-element");
    $.ajax({
          url:"<?php echo URL::to('user/task_complete_update'); ?>",
          data:{status:1,id:taskid},
          success: function(result) {
              $(e.target).parents('tr').find("select").each(function(){
                $(this).prop('disabled',true);
              });
              $(e.target).parents('tr').find("textarea").each(function(){
                $(this).prop('disabled',true);
              });
              $(e.target).parents('tr').find("input").each(function(){
                $(this).prop('disabled',true);
              });
              $(e.target).parents('tr').find('.fa-trash').each(function() {
                $(this).addClass('disabled');
              });
              
              $(e.target).parents('tr').find('.fa-plus').addClass('disabled');
              $(e.target).parents('tr').find('.fa-pencil-square').addClass('disabled');
              $(e.target).parents('tr').find('.fa-minus-square').addClass('disabled');
              $(e.target).parents('tr').find('.task_label').addClass('disabled');
              $(e.target).parents('tr').find('.edit_task').addClass('disabled');
              $(e.target).parents('tr').find('.fa-files-o').addClass('disabled');
              $(e.target).parents('tr').find('.task_delete').addClass('disabled');

              $(e.target).parents('tr').find('.single_notify').addClass('disabled');
              $(e.target).parents('tr').find('.all_notify').addClass('disabled');
              

              $(e.target).parents('tr').find('.email_unsent').addClass('disabled');
              $(e.target).parents('tr').find('.fa-files-o').parent().addClass('disabled');
              $(e.target).parents('tr').find('.fa-check').parent().addClass('disabled');
              $(e.target).parents('tr').find('.fa-times').parent().addClass('disabled');


              $(e.target).removeClass('fa-exclamation-triangle');
              $(e.target).removeClass('donot_complete');

              $(e.target).addClass('fa-ban');
              $(e.target).addClass('do_complete');

              $(e.target).parent().css({'color':'#f00'});

              $(e.target).parents('tr').find('.task_label').css({'color':'#1b0fd4','font-weight':'800'});
              $(e.target).parents('tr').find('.task_started_checkbox').prop("checked",false);

              if($("#show_incomplete").is(':checked'))
              {
                $(".edit_task").each(function() {
                    if($(this).hasClass('disabled'))
                    {
                      $(this).parents('tr').hide();
                    }
                });
              }
              else{
                $(".edit_task").each(function() {
                    if($(this).hasClass('disabled'))
                    {
                      $(this).parents('tr').show();
                    }
                });
              }
              $("body").removeClass("loading");
            }
    })
  }*/
if($(e.target).hasClass('donot_complete')) {
  var taskid = $(e.target).attr("data-element");
  $.ajax({
        url:"<?php echo URL::to('user/donot_complete_task_details'); ?>",
        type:"get",
        data:{taskid:taskid},
        success: function(result) {
          $(".dont_task_name").html(result);
          $(".donot_id_class").val(taskid);
          $(".model_don_not_complete").modal("show");
        }
  });
}

if($(e.target).hasClass('dontvale_class')) {
  var value = $(e.target).attr("value");
  $(".dontvale").val(value);
}

if($(e.target).hasClass('donot_submit_new'))
  {
    var check_option = $(".dontvale_class:checked").val();
    if(check_option === "" || typeof check_option === "undefined")
    {
      alert("Please select any one type.");
    }
    else{
      $("body").addClass("loading");
      var taskid = $(".donot_id_class").val();
      var dontvale = $(".dontvale").val();      

      $.ajax({
            url:"<?php echo URL::to('user/task_complete_update_new'); ?>",
            data:{status:1,id:taskid, dontvale:dontvale},
            success: function(result) {
              $(".model_don_not_complete").modal("hide");
                $('#taskidtr_'+taskid).find("select").each(function(){
                  $(this).prop('disabled',true);
                });
                $('#taskidtr_'+taskid).find("textarea").each(function(){
                  $(this).prop('disabled',true);
                });
                $('#taskidtr_'+taskid).find("input").each(function(){
                  $(this).prop('disabled',true);
                });
                $('#taskidtr_'+taskid).find('.fa-trash').each(function() {
                  $(this).addClass('disabled');
                });             
                $('#taskidtr_'+taskid).find('.fa-plus').addClass('disabled');
                $('#taskidtr_'+taskid).find('.fa-pencil-square').addClass('disabled');
                $('#taskidtr_'+taskid).find('.fa-minus-square').addClass('disabled');
                $('#taskidtr_'+taskid).find('.task_label').addClass('disabled');
                $('#taskidtr_'+taskid).find('.edit_task').addClass('disabled');
                $('#taskidtr_'+taskid).find('.fa-files-o').addClass('disabled');
                $('#taskidtr_'+taskid).find('.task_delete').addClass('disabled');
                $('#taskidtr_'+taskid).find('.single_notify').addClass('disabled');
                $('#taskidtr_'+taskid).find('.all_notify').addClass('disabled');            
                $('#taskidtr_'+taskid).find('.email_unsent').addClass('disabled');
                $('#taskidtr_'+taskid).find('.fa-files-o').parent().addClass('disabled');
                $('#taskidtr_'+taskid).find('.fa-check').parent().addClass('disabled');
                $('#taskidtr_'+taskid).find('.fa-times').parent().addClass('disabled');
                $('#taskidtr_'+taskid).find('.fa-exclamation-triangle').removeClass('fa-exclamation-triangle');
                 $('#taskidtr_'+taskid).find('.donot_complete').addClass('do_complete');
                $('#taskidtr_'+taskid).find('.do_complete').removeClass('donot_complete');
                $('#taskidtr_'+taskid).find('.do_complete').addClass('fa-ban');
                $('#taskidtr_'+taskid).find('.do_complete').parent().css({'color':'#f00'});
                $('#taskidtr_'+taskid).find('.task_label').css({'color':'#1b0fd4','font-weight':'800'});
                $('#taskidtr_'+taskid).find('.task_started_checkbox').prop("checked",false);
                if($("#show_incomplete").is(':checked'))
                {
                  $(".edit_task").each(function() {
                      if($(this).hasClass('disabled'))
                      {
                        $(this).parents('tr').hide();
                      }
                  });
                }
                else{
                  $(".edit_task").each(function() {
                      if($(this).hasClass('disabled'))
                      {
                        $(this).parents('tr').show();
                      }
                  });
                }
                $("body").removeClass("loading");
            }
      })
     
    }
}

  if($(e.target).hasClass('do_complete')){
    $("body").addClass("loading");
    var taskid = $(e.target).attr("data-element");
    $.ajax({
          url:"<?php echo URL::to('user/task_complete_update'); ?>",
          data:{status:0,id:taskid},
          success: function(result) {
              $(e.target).parents('tr').find("select").each(function(){
                $(this).prop('disabled',false);
              });
              $(e.target).parents('tr').find("textarea").each(function(){
                $(this).prop('disabled',false);
              });
              $(e.target).parents('tr').find("input").each(function(){
                $(this).prop('disabled',false);
              });
              $(e.target).parents('tr').find('.fa-trash').each(function() {
                $(this).removeClass('disabled');
              });
              
              $(e.target).parents('tr').find('.fa-plus').removeClass('disabled');
              $(e.target).parents('tr').find('.fa-pencil-square').removeClass('disabled');
              $(e.target).parents('tr').find('.fa-minus-square').removeClass('disabled');
              $(e.target).parents('tr').find('.task_label').removeClass('disabled');
              $(e.target).parents('tr').find('.edit_task').removeClass('disabled');
              $(e.target).parents('tr').find('.fa-files-o').removeClass('disabled');
              $(e.target).parents('tr').find('.task_delete').removeClass('disabled');

              $(e.target).parents('tr').find('.single_notify').removeClass('disabled');
              $(e.target).parents('tr').find('.all_notify').removeClass('disabled');
              

              $(e.target).parents('tr').find('.email_unsent').removeClass('disabled');
              $(e.target).parents('tr').find('.fa-files-o').parent().removeClass('disabled');
              $(e.target).parents('tr').find('.fa-check').parent().removeClass('disabled');
              $(e.target).parents('tr').find('.fa-times').parent().removeClass('disabled');

              $(e.target).removeClass('fa-ban');
              $(e.target).removeClass('do_complete');

              $(e.target).addClass('fa-exclamation-triangle');
              $(e.target).addClass('donot_complete');

              $(e.target).parent().css({'color':'#000'});

              $(e.target).parents('tr').find('.task_label').css({'color':'#fff','font-weight':'800'});

              if($("#show_incomplete").is(':checked'))
              {
                $(".edit_task").each(function() {
                    if($(this).hasClass('disabled'))
                    {
                      $(this).parents('tr').hide();
                    }
                });
              }
              else{
                $(".edit_task").each(function() {
                    if($(this).hasClass('disabled'))
                    {
                      $(this).parents('tr').show();
                    }
                });
              }
              $("body").removeClass("loading");
            }
    })
  }
  if($(e.target).hasClass("notify_all_clients_tasks"))
  {
    $("body").addClass("loading");
    $(".model_notify").modal("hide");
    var task_id = $(".notify_task_id").val();
    var emails = [];
    var clientids = [];
    var toemails = '';

    var task_id = $(".notify_task_id").val();
    $(".notify_id_class").each(function(i, el) {
        if($(el).is(':checked'))
        {
          var user_email = $(el).attr('data-element');
          var user_id = $(el).attr('data-value');
          
          if(user_email != '' && typeof user_email !== 'undefined')
          {
            if($.inArray(user_email, emails) == -1)
            {
              emails.push(user_email);
              if(toemails == '')
              {
                toemails= user_email;
              }
              else{
                toemails = toemails+', '+user_email;
              }
            }
          }
          if(user_id != '' && typeof user_id !== 'undefined')
          {
            if($.inArray(user_id, clientids) == -1)
            {
              clientids.push(user_id);
            }
          }
        }
    });
    toemails = toemails+', <?php echo $admin_cc; ?>';
    var option_length = emails.length;
    $.each( emails, function( i, value ) {
        setTimeout(function(){
          $.ajax({
            url:"<?php echo URL::to('user/email_notify_tasks_pdf'); ?>",
            type:"get",
            data:{email:value,clientid:clientids[i],toemails:toemails,task_id:task_id},
            success: function(result) {
              var keyi = parseInt(i) + parseInt(1);
              if(option_length == keyi)
              {
                $("body").removeClass("loading");
                $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>Email sent Successfully</p>", fixed:true});
              }
            }
          });
        },2000 + ( i * 2000 ));
    }); 
  }

});

var convertToNumber = function(value){

       return value.toLowerCase();

}
$(".image_file").change(function(){
  var lengthval = $(this.files).length;
  var htmlcontent = '<label class="attachments_label">Attachments : </label>';
  for(var i=0; i<= lengthval - 1; i++)
  {
    var sno = i + 1;
    if(htmlcontent == "")
    {
      htmlcontent = '<p class="attachment_p">'+sno+'. '+this.files[i].name+'</p>';
    }
    else{
      htmlcontent = htmlcontent+'<p class="attachment_p">'+sno+'. '+this.files[i].name+'</p>';
    }
  }
  $(this).parent().find(".image_div_attachments").html(htmlcontent);
});
$(window).change(function(e) {

  if(e.target.id == 'select_year_type')

  {

    var year = $("#hidden_copy_year").val();

    var id = $("#hidden_task_id").val();

    if($(e.target).val() == 'weekly')

    {

      $.ajax({

          url:"<?php echo URL::to('user/get_week_by_year'); ?>",

          type:"get",

          data:{year:year,id:id},

          success: function(result) {

            $(".weekly_select").html(result);

          }

      });

    }

    else{

      $.ajax({

          url:"<?php echo URL::to('user/get_month_by_year'); ?>",

          type:"get",

          data:{year:year,id:id},

          success: function(result) {

            $(".weekly_select").html(result);

          }

      });

    }

  }

  if($(e.target).hasClass('date_input'))

  {

    var input_val = $(e.target).val();

    var id = $(e.target).attr('data-element');

    $.ajax({

        url:"<?php echo URL::to('user/task_date_update'); ?>",

        type:"get",

        data:{date:input_val,id:id},

        success: function(result) {



        }

      });

  }

  // if($(e.target).hasClass('task_email_input'))

  // {

  //   var input_val = $(e.target).val();

  //   var id = $(e.target).attr('data-element');

  //   $.ajax({

  //       url:"<?php echo URL::to('user/task_email_update'); ?>",

  //       type:"get",

  //       data:{email:input_val,id:id},

  //       success: function(result) {



  //       }

  //     });

  // }

  if($(e.target).hasClass('uname_input'))

  {

    var input_val = $(e.target).val();

    var id = $(e.target).attr('data-element');

    $.ajax({

        url:"<?php echo URL::to('user/task_users_update'); ?>",

        type:"get",

        data:{users:input_val,id:id},

        success: function(result) {

          

          $(e.target).parents("tr").find('.initial_input').val(input_val);

        }

      });

  }

  

  if($(e.target).hasClass('initial_input'))

  {

    var input_val = $(e.target).val();

    var id = $(e.target).attr('data-element');

    $.ajax({

        url:"<?php echo URL::to('user/task_users_update'); ?>",

        type:"get",

        data:{users:input_val,id:id},

        success: function(result) {

          $(e.target).parents("tr").find('.uname_input').val(input_val);

          

        }

      });

  }


if($(e.target).hasClass('default_staff'))
  {
    var input_val = $(e.target).val();
    var id = $(e.target).attr('data-element');
    $.ajax({
        url:"<?php echo URL::to('user/task_default_users_update'); ?>",
        type:"get",
        data:{users:input_val,id:id},
        success: function(result) {
          //$(e.target).parents("tr").find('.initial_input').val(input_val);
        }
      });
  }



  if($(e.target).hasClass('classified_input'))

  {

    var input_val = $(e.target).val();

    var id = $(e.target).attr('data-element');

    $.ajax({

        url:"<?php echo URL::to('user/task_classified_update'); ?>",

        type:"get",

        data:{classified:input_val,id:id},

        success: function(result) {



        }

      });

  }

});



//setup before functions

var typingTimer;                //timer identifier

var doneTypingInterval = 1000;  //time in ms, 5 second for example

var $input = $('.comments_input');



//on keyup, start the countdown

$input.on('keyup', function () {

  var input_val = $(this).val();

  var id = $(this).attr('data-element');



  clearTimeout(typingTimer);

  typingTimer = setTimeout(doneTyping, doneTypingInterval,input_val,id);

});



//on keydown, clear the countdown 

$input.on('keydown', function () {

  clearTimeout(typingTimer);
  var input_val = $(this).val();
  var id = $(this).attr('data-element');
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping, doneTypingInterval,input_val,id);

});



//user is "finished typing," do something

function doneTyping (input,id) {

  $.ajax({

        url:"<?php echo URL::to('user/task_comments_update'); ?>",

        type:"get",

        data:{comments:input,id:id},

        success: function(result) {



        }

      });

}

var $input = $('.liability_input');
//on keyup, start the countdown
$input.on('keyup', function () {
  var input_val = $(this).val();
  var id = $(this).attr('data-element');
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping_liability, doneTypingInterval,input_val,id);
});
//on keydown, clear the countdown 
$input.on('keydown', function () {
  clearTimeout(typingTimer);
  var input_val = $(this).val();
  var id = $(this).attr('data-element');
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping_liability, doneTypingInterval,input_val,id);
});
//user is "finished typing," do something
function doneTyping_liability (input,id) {
  $.ajax({
        url:"<?php echo URL::to('user/task_liability_update'); ?>",
        type:"get",
        data:{liability:input,id:id},
        success: function(result) {
        }
      });
}
$(window).focusout(function(e) {
  if($(e.target).hasClass('liability_input'))
  {
    var input_val = $(e.target).val();
    var id = $(e.target).attr('data-element');
    $.ajax({
        url:"<?php echo URL::to('user/task_liability_update'); ?>",
        type:"get",
        data:{liability:input_val,id:id},
        success: function(result) {
        }
      });
  }
});


</script>



<script>

$(document).ready(function() {    

     $(".client_search_class").autocomplete({

        source: function(request, response) {

            $.ajax({

                url:"<?php echo URL::to('user/task_client_search'); ?>",

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

            dataType: "json",

            url:"<?php echo URL::to('user/task_client_search_select'); ?>",

            data:{value:ui.item.id},

            success: function(result){      

              $("#hidden_client_id").val(ui.item.id);        

              $(".tax_reg1class").val(result['taxreg']);

              $(".primaryemail_class").val(result['primaryemail']);

              $(".firstname_class").val(result['firstname']);

              $('#alert_modal').modal({backdrop: 'static', keyboard: false});

            }

          })

        }

    });

     $(".client_search_class_edit").autocomplete({

        source: function(request, response) {

            $.ajax({

                url:"<?php echo URL::to('user/task_client_search'); ?>",

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

          $("#client_search_edit").val(ui.item.id);          

          $.ajax({

            dataType: "json",

            url:"<?php echo URL::to('user/task_client_search_select'); ?>",

            data:{value:ui.item.id},

            success: function(result){

              /*$("#clients_tbody").html(result);

              $("#client_expand_paginate").hide();

              $(".dataTables_info").hide();*/

              $("#hidden_client_id_edit").val(ui.item.id);

              $(".tax_reg1class_edit").val(result['taxreg']);

              $(".primaryemail_class_edit").val(result['primaryemail']);

              $(".firstname_class_edit").val(result['firstname']);

              $('#alert_modal_edit').modal({backdrop: 'static', keyboard: false});

            }

          })

        }

    });

});

</script>





@stop
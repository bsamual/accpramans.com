@extends('userheader')

@section('content')



<script src='<?php echo URL::to('assets/js/table-fixed-header.js'); ?>'></script>



<style>

/*.winheight tbody {

display:block;

max-height:300px;

overflow-y:scroll;

}

.winheight thead, tbody tr {

display:table;

width:100%;

}*/

.disabled{

  background: #bdbcbc;

}

.task_label{

  background: none !important;

}

.fa-plus{

  background: none !important;

}

.disable_button{

  cursor: not-allowed;

}

.disable_button a{

  pointer-events: none;

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

  border-bottom:1px solid #000;

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

  background: #03d4b7 !important;

  margin-bottom: 0px !important;

  padding-top: 20px !important;

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

</style>

<?php if(Session::has('message_import')) { ?>

  <div class="modal fade p30_review_output_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">

    <div class="modal-dialog modal-lg" role="document" style="width:80%">

      <div class="modal-content">

          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            <h4 class="modal-title">P30 Review Due</h4>

          </div>

          <div class="modal-body" style="height:500px; overflow-y: scroll">

            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">

              

                Fields that did not have a corresponding ER Number :

              

            </div>

            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 col-lg-offset-4 col-md-offset-4 col-sm-offset-4 col-xs-offset-4">

                <a href="javascript:" class="btn btn-primary btn-sm pdf_button" id="pdf_dont_ernumber">Download PDF</a>

            </div>

            <div class="select_button">

              <table class="table">

                <thead>

                  <th>S.no</th>

                  <th>P30 Task</th>

                  <th>Task Level</th>

                  <th>Pay</th>

                  <th>Period</th>

                  <th>Email</th>

                  <th>Liability</th>

                </thead>

                <tbody>

                <?php 

                  $dont_er = DB::table('p30_task')->where('task_month',$monthid->month_id)->where('na',1)->get();

                  $output ='';

                  if(count($dont_er))

                  {

                    $i = 1;

                    foreach($dont_er as $task)

                    {

                      $task_level = DB::table('p30_tasklevel')->where('id',$task->task_level)->first();

                      if(count($task_level))

                      {

                        $task_level_val = $task_level->name;

                      } 

                      else{

                        $task_level_val = '';

                      }

                      $period = DB::table('p30_period')->where('id',$task->task_period)->first();

                      if(count($period))

                      {

                        $period_val = $period->name;

                      } 

                      else{

                        $period_val = '';

                      }

                      if($task->pay == 1) { $pay = 'Yes';} else { $pay = 'No'; }



                      $output.='<tr>

                      <td style="border:1px solid #000; text-align:left">'.$i.'</td>

                      <td style="border:1px solid #000; text-align:left">'.$task->task_name.'</td>

                      <td style="border:1px solid #000; text-align:left">'.$task_level_val.'</td>

                      <td style="border:1px solid #000; text-align:left">'.$pay.'</td>

                      <td style="border:1px solid #000; text-align:left">'.$period_val.'</td>

                      <td style="border:1px solid #000; text-align:left">'.$task->task_email.'</td>

                      <td style="border:1px solid #000; text-align:left">'.$task->liability.'</td>

                      </tr>';

                      $i++;

                    }

                  }

                  else{

                    $output.='<tr><td colspan="7">No Task Found</td></tr>';

                  }

                  echo $output;

                ?>

                </tbody>

              </table>

            </div>



            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">

              

                Fields that have the NA checked :

              

            </div>

            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 col-lg-offset-4 col-md-offset-4 col-sm-offset-4 col-xs-offset-4">

                <a href="javascript:" class="btn btn-primary btn-sm pdf_button" id="pdf_na_checked">Download PDF</a>

            </div>

            <div class="select_button">

              <table class="table">

                <thead>

                  <th>S.no</th>

                  <th>P30 Task</th>

                  <th>Task Level</th>

                  <th>Pay</th>

                  <th>Period</th>

                  <th>Email</th>

                  <th>Liability</th>

                </thead>

                <tbody>

                <?php 

                  $dont_er = DB::table('p30_task')->where('task_month',$monthid->month_id)->where('na',1)->get();

                  $output ='';

                  if(count($dont_er))

                  {

                    $i = 1;

                    foreach($dont_er as $task)

                    {

                      $task_level = DB::table('p30_tasklevel')->where('id',$task->task_level)->first();

                      if(count($task_level))

                      {

                        $task_level_val = $task_level->name;

                      } 

                      else{

                        $task_level_val = '';

                      }

                      $period = DB::table('p30_period')->where('id',$task->task_period)->first();

                      if(count($period))

                      {

                        $period_val = $period->name;

                      } 

                      else{

                        $period_val = '';

                      }

                      if($task->pay == 1) { $pay = 'Yes';} else { $pay = 'No'; }



                      $output.='<tr>

                      <td style="border:1px solid #000; text-align:left">'.$i.'</td>

                      <td style="border:1px solid #000; text-align:left">'.$task->task_name.'</td>

                      <td style="border:1px solid #000; text-align:left">'.$task_level_val.'</td>

                      <td style="border:1px solid #000; text-align:left">'.$pay.'</td>

                      <td style="border:1px solid #000; text-align:left">'.$period_val.'</td>

                      <td style="border:1px solid #000; text-align:left">'.$task->task_email.'</td>

                      <td style="border:1px solid #000; text-align:left">'.$task->liability.'</td>

                      </tr>';

                      $i++;

                    }

                  }

                  else{

                    $output.='<tr><td colspan="7">No Task Found</td></tr>';

                  }

                  echo $output;

                ?>

                </tbody>

              </table>

            </div>





            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">

              

                Fields that have the NA NOT checked! :

              

            </div>

            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 col-lg-offset-4 col-md-offset-4 col-sm-offset-4 col-xs-offset-4">

                <a href="javascript:" class="btn btn-primary btn-sm pdf_button" id="pdf_na_not_checked">Download PDF</a>

            </div>

            <div class="select_button">

              <table class="table">

                <thead>

                  <th>S.no</th>

                  <th>P30 Task</th>

                  <th>Task Level</th>

                  <th>Pay</th>

                  <th>Period</th>

                  <th>Email</th>

                  <th>Liability</th>

                </thead>

                <tbody>

                <?php 

                  $dont_er = DB::table('p30_task')->where('task_month',$monthid->month_id)->where('na',2)->get();

                  $output ='';

                  if(count($dont_er))

                  {

                    $i = 1;

                    foreach($dont_er as $task)

                    {

                      $task_level = DB::table('p30_tasklevel')->where('id',$task->task_level)->first();

                      if(count($task_level))

                      {

                        $task_level_val = $task_level->name;

                      } 

                      else{

                        $task_level_val = '';

                      }

                      $period = DB::table('p30_period')->where('id',$task->task_period)->first();

                      if(count($period))

                      {

                        $period_val = $period->name;

                      } 

                      else{

                        $period_val = '';

                      }

                      if($task->pay == 1) { $pay = 'Yes';} else { $pay = 'No'; }



                      $output.='<tr>

                      <td style="border:1px solid #000; text-align:left">'.$i.'</td>

                      <td style="border:1px solid #000; text-align:left">'.$task->task_name.'</td>

                      <td style="border:1px solid #000; text-align:left">'.$task_level_val.'</td>

                      <td style="border:1px solid #000; text-align:left">'.$pay.'</td>

                      <td style="border:1px solid #000; text-align:left">'.$period_val.'</td>

                      <td style="border:1px solid #000; text-align:left">'.$task->task_email.'</td>

                      <td style="border:1px solid #000; text-align:left">'.$task->liability.'</td>

                      </tr>';

                      $i++;

                    }

                  }

                  else{

                    $output.='<tr><td colspan="7">No Task Found</td></tr>';

                  }

                  echo $output;

                ?>

                </tbody>

              </table>

            </div>

          </div>

      </div>

    </div>

  </div>

  <?php

}

?>

<div class="modal fade report_pdf_div" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">

  <div class="modal-dialog modal-lg" role="document" style="width: 80%;">

    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        <h4 class="modal-title" id="myModalLabel">Report Pdf</h4>

      </div>

      <div class="modal-body" style="height:500px; overflow-y:scroll">

          <table class="table">

            <thead>

              <tr>

                <th style="width:12%"><input type="checkbox" class="select_all_report" id="select_all_report"><label for="select_all_report">Select</label></th>

                <th>P30 Task</th>

                <th>Task Level</th>

                <th>Pay</th>

                <th>Period</th>

                <th>Email</th>

                <th>Liabilitys</th>

              </tr>

            </thead>

            <tbody id="report_pdf_tbody">

            </tbody>

          </table>

      </div>

      <div class="modal-footer">

        <input type="button" class="btn btn-primary common_black_button" id="download_pdf_report" value="Download Report as PDf">

      </div>

    </div>

  </div>

</div>

<div class="modal fade" id="p30_review_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">

  <div class="modal-dialog modal-sm" role="document">

    <div class="modal-content">

      <form id="review_p30_due_form" action="<?php echo URL::to('user/import_p30_review_due'); ?>" method="post" enctype="multipart/form-data">

        <div class="modal-header">

          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

          <h4 class="modal-title" id="myModalLabel">Review P30 Due</h4>

        </div>

        <div class="modal-body">

            <input type="hidden" name="hidden_month_id" value="<?php echo $monthid->month_id; ?>">

            <input type="file" name="import_csv" id="import_csv" class="form-control input-sm" required="required">

        </div>

        <div class="modal-footer">

          <input type="submit" class="btn btn-primary common_black_button" value="Review P30 Due">

        </div>

      </form>

    </div>

  </div>

</div>

<div class="content_section">

  <div class="page_title">

    <div class="col-lg-4 padding_00" style="margin-left: 1%;">Task of <?php echo $yearname->year_name ?> &nbsp;&nbsp;&nbsp;&nbsp; Month : Month <?php echo $monthid->month ?></div>

    <div class="col-lg-7 padding_00 button_top_right">

      <ul style="margin-right: 13%;">

        <?php $check_current_year = DB::table('year')->orderBy('year_id','desc')->first(); ?>

        <li><a href="javascript:" id="review_p30_due" class="" data-toggle="modal" data-target="#p30_review_modal" style="float:right">Review P30's Due</a></li>

        <li><a href="javascript:" id="report_pdf_button" class="" style="float:right">Report Pdf</a></li>

        <li class="<?php if($check_current_year->year_id == $monthid->year) { echo ''; } else { echo 'disable_button'; }?>"><a href="<?php echo URL::to('user/p30_close_create_new_month/'.$monthid->year); ?>" id="review_month" class="" style="float:right">Close and Create New Month</a></li>

        <?php $check_current_month = DB::table('p30_month')->orderBy('month_id','desc')->first(); ?>

        <li class="<?php if($check_current_month->month_id == $monthid->month_id) { echo ''; } else { echo 'disable_button'; }?>"><a href="<?php echo URL::to('user/p30_review_month/'.$monthid->month_id); ?>" id="review_month">Review Month</a></li>

        <?php $check_incomplete = Db::table('user_login')->where('userid',1)->first(); 

        if($check_incomplete->p30_incomplete == 1) { $inc_checked = 'checked="checked"'; } else { $inc_checked = ''; }

        if($check_incomplete->p30_na == 1) { $incna_checked = 'checked="checked"'; } else { $incna_checked = ''; } ?>

        <br/> 

        <input type="checkbox" name="show_incomplete" id="show_incomplete" value="1" <?php echo $inc_checked; ?>><label for="show_incomplete">Show Incomplete Only</label>

        <input type="checkbox" name="show_na" id="show_na" value="1" <?php echo $incna_checked; ?>><label for="show_na">Hide/Show N/A P30's</label>

      </ul>

    </div>

  </div>

<div style="width:100%;float:left; margin-top: 55px; margin-bottom: -66px;">

<?php

if(Session::has('message')) { ?>

    <p class="alert alert-info" style="clear:both; margin-top: 30px;"><?php echo Session::get('message'); ?></p>

<?php }

if(Session::has('error')) { ?>

    <p class="alert alert-danger" style="clear:both; margin-top: 30px;"><?php echo Session::get('error'); ?></p>

<?php }

?>

</div>



<div class="modal fade emailunsent" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">

  <div class="modal-dialog" role="document">

    <form action="<?php echo URL::to('user/p30_email_unsent_files'); ?>" method="post" >

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

              <input type="text" name="to_user" id="to_user" class="form-control input-sm" value="" required>

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

              <textarea name="message_editor" id="editor_1">

                  

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

        <input type="hidden" name="hidden_email_task_id" id="hidden_email_task_id" value="">

        <input type="submit" class="btn btn-primary common_black_button email_unsent_button" value="Email Unsent Files">

      </div>

    </div>

    </form>

  </div>

</div>

  

    <div style="max-width: 100%; float: left; margin-top:67px">

      

      <table class="table_bg table-fixed-header" style="width: 2000px; margin: 0px auto;margin-bottom:30px">

          <thead class='header'>

            <tr class="background_bg">

                <th width="100px">S.No <i class="fa fa-sort sno_sort_std" aria-hidden="true"></th>

                <th width="120px">N/A</th>

                <th width="250px">P30 Task <i class="fa fa-sort task_sort_std" aria-hidden="true"></th>

                <th width="200px">ER Number <i class="fa fa-sort eno_sort_std" aria-hidden="true"></th>

                <th width="450px">Task Level <i class="fa fa-sort level_sort_std" aria-hidden="true"></th>

                                

                <th width="400px">Pay</th>

                <th width="400px">Due</th>

                <th width="400px">Period <i class="fa fa-sort period_sort_std" aria-hidden="true"></th>

                <th width="200px">Email(Y/N)</th>

                <th width="400px">Manual File</th>

                <th width="400px">Automatic File</th>

                <th width="350px">Liability <i class="fa fa-sort liability_sort_std" aria-hidden="true"></th>

                <th width="200px">Email</th>

                <th width="200px">Complete</th>

            </tr>

          </thead>

          <tbody id="task_body_std">

            

              <?php 

              

               if(count($resultlist)){

                  $i=1;

                  foreach ($resultlist as $result) {

                    if($i < 10)

                  {

                    $i = '0'.$i;

                  }

              ?>

               <?php if($result->task_status == 1 || $result->na == 1) { 

                $disabled='disabled';

                  if($result->na == 1) { $action_disabled='disabled'; } 

                  else {  $action_disabled='';  } 

                }

               else{ $disabled=''; $action_disabled=''; }

                  if($result->task_status == 1) { $hide_tr='hide_tr'; } else{ $hide_tr=''; } 

                  if($result->na == 1) { $hidena_tr='hidena_tr'; } else{ $hidena_tr=''; } 

               ?>

                <?php if($result->task_status == 1) { $task_label='style="color:#f00;font-weight:800"'; } elseif($result->na == 1) { $task_label='style="color:#da8e04;font-weight:800"'; }  else{ $task_label='style="color:#fff;font-weight:600"'; } ?>



              <tr class="task_tr_std <?php echo $hide_tr; ?> <?php echo $hidena_tr; ?>" id="taskidtr_<?php echo $result->id; ?>">

                  <td class="sno_sort_std_val"><label class="task_label <?php echo $disabled; ?>" <?php echo $task_label; ?>><?php echo $i;?></label></td>

                  <td>

                    <input type="checkbox" name="na_p30" class="na_p30 <?php if($result->task_status == 1) { echo 'disabled'; } ?>" value="1" data-element="<?php echo $result->id; ?>" <?php if($result->na == 1) { echo 'checked'; } elseif($result->na == 2) { echo 'disabled'; } else { echo ''; } ?>>

                    <label>&nbsp;</label>

                  </td>

                  <td class="task_sort_std_val" align="left"><label class="task_label <?php echo $disabled; ?>" <?php echo $task_label; ?>><?php echo $result->task_name; ?></label></td>

                  <td class="eno_sort_std_val" style="text-align: left"><label class="task_label <?php echo $disabled; ?>" <?php echo $task_label; ?>> <?php echo $result->task_enumber; ?> </label></td>

                  <td class="level_sort_std_val">

                    <?php $tasklevel = DB::table('p30_tasklevel')->where('status',0)->orderBy('name','asc')->get(); ?>

                    <select name="task_level" class="form-control input-sm task_level_class <?php echo $disabled; ?>" data-element="<?php echo $result->id; ?>">

                        <option value="">Select Task Level</option>

                        <?php 

                          if(count($tasklevel))

                          {

                            foreach($tasklevel as $level)

                            {

                              if($result->task_level == $level->id) { $selected = 'selected'; } else { $selected = ''; }

                              echo '<option value="'.$level->id.'" '.$selected.'>'.$level->name.'</option>';

                            }

                          }

                        ?>

                    </select>

                  </td>

                  

                  <td>

                    <input type="checkbox" name="pay_p30" class="pay_p30 <?php echo $disabled; ?>" value="1" data-element="<?php echo $result->id; ?>" <?php if($result->pay == 1) { echo 'checked'; } else { echo ''; } ?>>

                    <label>&nbsp;</label>

                  </td>

                  <td> 



                    <?php 

                      $due_date = DB::table('p30_due_date')->first(); 

                      $month_details = Db::table('p30_month')->where('month_id',$monthid->month_id)->first();

                      $year_details = DB::table('year')->where('year_id',$month_details->year)->first();

                      if($month_details->month == 1) { $month_name = "February"; }

                      if($month_details->month == 2) { $month_name = "March"; }

                      if($month_details->month == 3) { $month_name = "April"; }

                      if($month_details->month == 4) { $month_name = "May"; }

                      if($month_details->month == 5) { $month_name = "June"; }

                      if($month_details->month == 6) { $month_name = "July"; }

                      if($month_details->month == 7) { $month_name = "August"; }

                      if($month_details->month == 8) { $month_name = "September"; }

                      if($month_details->month == 9) { $month_name = "October"; }

                      if($month_details->month == 10) { $month_name = "November"; }

                      if($month_details->month == 11) { $month_name = "December"; }

                      if($month_details->month == 12) { $month_name = "January"; }



                    if($month_name == "January") { $next_year = $year_details->year_name + 1; ?>

                        <input type="text" class="form-control input-sm <?php echo $disabled; ?>" value="<?php echo $due_date->date.' '.$month_name.' '.$next_year; ?>" readonly>

                    <?php } else { ?>

                        <input type="text" class="form-control input-sm <?php echo $disabled; ?>" value="<?php echo $due_date->date.' '.$month_name.' '.$year_details->year_name; ?>" readonly>

                    <?php } ?>

                  </td>

                  <td class="period_sort_std_val">

                    <?php $period = DB::table('p30_period')->where('status',0)->orderBy('sort','asc')->get(); ?>

                    <select name="task_period" class="form-control input-sm period_class <?php echo $disabled; ?>" data-element="<?php echo $result->id; ?>">

                        <option value="">Select Period</option>

                        <?php 

                          if(count($period))

                          {

                            foreach($period as $level)

                            {

                              if($result->task_period == $level->id) { $selected = 'selected'; } else { $selected = ''; }

                              echo '<option value="'.$level->id.'" '.$selected.'>'.$level->name.'</option>';

                            }

                          }

                        ?>

                    </select>

                  </td>

                  <td>

                    <input type="checkbox" name="email_p30" data-element="<?php echo $result->id; ?>" class="email_p30 <?php echo $disabled; ?>" value="1" <?php if($result->email == 1) { echo 'checked'; } else { echo ''; } ?>>

                    <label>&nbsp;</label>

                  </td>

                  <td>

                    <input type="text" class="form-control common_input network_input" name="" value="<?php echo $result->network ?>" disabled><br/>

                      <?php

                      $attachments = DB::table('p30_task_attached')->where('task_id',$result->id)->get();

                      if(count($attachments))

                      {

                            foreach($attachments as $attachment)

                            {

                                echo '<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disabled.'"><i class="fa fa-trash trash_image '.$disabled.'" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';

                            }

                      }

                      ?>

                      <i class="fa fa-plus faplus <?php echo $disabled; ?>" style="margin-top:10px" aria-hidden="true" ></i>

                      <div class="img_div">

                        <label class="copy_label">Network location has been copied. Just paste the URL in the "File name" path to go to that folder.</label>

                        <form name="image_form" id="image_form" action="<?php echo URL::to('user/p30_task_image_upload'); ?>" method="post" enctype="multipart/form-data" style="text-align: left;">

                          <input type="file" name="image_file[]" class="form-control image_file" value="" multiple>

                          <input type="hidden" name="hidden_id" value="<?php echo $result->id ?>">

                          <input type="submit" name="image_submit" class="btn btn-sm btn-primary image_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">

                          <spam class="error_files"></spam>

                        </form>

                      </div>

                  </td>

                  <td>

                    <?php

                      $attachment = DB::table('p30_task_xml')->where('task_id',$result->id)->first();

                      if(count($attachment))

                      {

                        echo '<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disabled.'"><i class="fa fa-trash trash_image_automatic '.$disabled.'" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';

                      }

                      else{

                        echo '<i class="fa fa-plus automatic_file '.$disabled.'" style="margin-top:10px" aria-hidden="true" ></i>';

                      }

                      ?>

                    

                    <div class="img_div_automatic">

                        <label class="copy_label">Please Choose the XML File to Calculate the liability.</label>

                        <form name="image_automatic_form" id="image_automatic_form" action="<?php echo URL::to('user/p30_task_automatic_image_upload'); ?>" method="post" enctype="multipart/form-data" style="text-align: left;">

                          <input type="file" name="image_file" class="form-control automatic_image_file" value="" accept="text/xml">

                          <input type="hidden" name="hidden_id" value="<?php echo $result->id ?>">

                          <input type="submit" name="automatic_image_submit" class="btn btn-sm btn-primary automatic_image_submit" align="left" value="Calculate Liability" style="margin-left:7px;    background: #000;margin-top:4px">

                          <spam class="automatic_error_files"></spam>

                        </form>

                      </div>

                  </td>

                  <td class="liability_sort_std_val">
                    <input type="text" name="liability" class="liability_class form-control input-sm <?php echo $disabled; ?>" value="<?php if($result->liability == "") { echo '0.00'; } else { echo $result->liability; } ?>"> 
                    <input type="text" name="hidden_liability" data-element="<?php echo $result->id; ?>" class="hidden_liability form-control input-sm <?php echo $disabled; ?>" value="<?php if($result->liability == "") { echo '0.00'; } else { echo $result->liability; } ?>" style="display:none"></td>



                  <td>

                      <a href="javascript:" class="email_unsent <?php echo $disabled; ?>" data-element="<?php echo $result->id; ?>"><i class="fa fa-envelope email_unsent" data-toggle="tooltip" title="Email Unsent FIles" aria-hidden="true" data-element="<?php echo $result->id; ?>"></i></a>

                      <?php

                      if($result->last_email_sent != '0000-00-00 00:00:00')

                      {

                        $date = date('d F Y', strtotime($result->last_email_sent));

                        $time = date('H : i', strtotime($result->last_email_sent));

                        $last_date = $date.' <br/>@ '.$time;

                      }

                      else{

                        $last_date = '';

                      }

                      ?>

                      <label class="email_unsent_label"><?php echo $last_date; ?></label>

                  </td>

                  <td>

                    <a href="javascript:" class="<?php echo $action_disabled; ?>"><i class="fa <?php if($result->task_status == 0) { echo 'fa-check'; } else{ echo 'fa-times'; } ?> <?php echo $action_disabled; ?>" data-toggle="tooltip" <?php if($result->task_status == 0) { echo 'title="Mark as Completed"'; } else { echo 'title="Mark as Incomplete"'; }?> data-element="<?php echo $result->id; ?>" aria-hidden="true"></i></a>

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

</div>

<input type="hidden" name="sno_sortoptions_std" id="sno_sortoptions_std" value="asc">

<input type="hidden" name="task_sortoptions_std" id="task_sortoptions_std" value="asc">

<input type="hidden" name="eno_sortoptions_std" id="eno_sortoptions_std" value="asc">

<input type="hidden" name="level_sortoptions_std" id="level_sortoptions_std" value="asc">

<input type="hidden" name="period_sortoptions_std" id="period_sortoptions_std" value="asc">

<input type="hidden" name="liability_sortoptions_std" id="liability_sortoptions_std" value="asc">



<div class="modal_load"></div>

<script>

$(document).ready(function(){

      $('.table-fixed-header').fixedHeader();

      });



$(document).ready(function() {

  if($("#show_incomplete").is(':checked'))

  {

    $(".hide_tr").each(function() {

          $(this).hide();

    });

  }

  else{

    $(".hide_tr").each(function() {

          $(this).show();

    });

  }

  if($("#show_na").is(':checked'))

  {

    $(".hidena_tr").each(function() {

          $(this).hide();

    });

  }

  else{

    $(".hidena_tr").each(function() {

          $(this).show();

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

if(Session::has('message_import')) { ?>

<script>

$(document).ready(function(){

  $(".p30_review_output_modal").modal("show");

});

</script>

<?php } ?>

<script>

$(document).ready(function() {

  var hheight = $(window).height();

  var top = parseInt(80);



  var winheight = hheight - top;

  $(".winheight").css({"max-height": winheight, "overflow-y": "scroll"})



  $(".liability_class").each(function() {

    var value = $(this).val();

    var added = addCommas(value);

    x = added.split('.');

    var sep = x[0].split('');

    var final_value = '';

    if(value == "0.00")

    {

      $(this).val('0.00');

      $(this).parent().find(".hidden_liability").val('0.00');

    }

    else{

      $.each(sep,function( index, val ) { 

        if(val == ",")

        {

          final_value = final_value+",";

        }

        else{

          if(final_value == "")

          {

            final_value = val;

          }

          else{

            final_value = final_value+val;

          }

        }

      });

      $(this).val(final_value+'.'+x[1]);

      $(this).parent().find(".hidden_liability").val(added);

    }

  });

});



function addCommas(nStr) {

    nStr += '';

    x = nStr.split('.');

    x1 = x[0];

    x2 = x.length > 1 ? '.' + x[1] : '';

    var rgx = /(\d+)(\d{3})/;

    while (rgx.test(x1)) {

        x1 = x1.replace(rgx, '$1' + ',' + '$2');

    }

    return x1 + x2;

}





$(window).change(function(e) {

  if($(e.target).hasClass('task_level_class'))

  {

      var id = $(e.target).attr("data-element");

      var value = $(e.target).val();

      $.ajax({

          url:"<?php echo URL::to('user/p30_tasklevel_update'); ?>",

          data:{value:value,id:id},

          type:"POST",

          success: function(result)

          {

              $(e.target).parent().find(".sort_class").text(result);

          }

      })

  }

  if($(e.target).hasClass('period_class'))

  {

      var id = $(e.target).attr("data-element");

      var value = $(e.target).val();

      $.ajax({

          url:"<?php echo URL::to('user/p30_period_update'); ?>",

          data:{value:value,id:id},

          type:"POST",

          success: function(result)

          {

              $(e.target).parent().find(".sort_class").text(result);

          }

      })

  }

  if($(e.target).hasClass('pay_p30'))

  {

    var id = $(e.target).attr('data-element');

    if($(e.target).is(':checked'))

    {



      $.ajax({

        url:"<?php echo URL::to('user/pay_p30'); ?>",

        type:"get",

        data:{pay:1,id:id},

        success: function(result) {



        }

      });

    }

    else{

      $.ajax({

        url:"<?php echo URL::to('user/pay_p30'); ?>",

        type:"get",

        data:{pay:0,id:id},

        success: function(result) {



        }

      });

    }

  }

  if($(e.target).hasClass('na_p30'))

  {

    var id = $(e.target).attr('data-element');

    if($(e.target).is(':checked'))

    {

      $.ajax({

        url:"<?php echo URL::to('user/na_p30'); ?>",

        type:"get",

        data:{na:1,id:id},

        success: function(result) {

          var complete = $(e.target).parents('tr').find('.fa-check').length;

          if(complete > 0)

          {

            $(e.target).parents('tr').find("select").each(function(){

              $(this).addClass('disabled');

            });

            $(e.target).parents('tr').find("textarea").each(function(){

              $(this).addClass('disabled');

            });

            $(e.target).parents('tr').find('.fa-trash').each(function() {

              $(this).addClass('disabled');

            });

            $(e.target).parents('tr').find('.email_unsent').each(function() {

              $(this).addClass('disabled');

            });



            $(e.target).parents('tr').find(".pay_p30").addClass('disabled');

            $(e.target).parents('tr').find(".email_p30").addClass('disabled');

            $(e.target).parents('tr').find(".liability_class").addClass('disabled');

            

            $(e.target).parents('tr').find('.faplus').addClass('disabled');

            $(e.target).parents('tr').find('.fa-check').addClass('disabled');

            $(e.target).parents('tr').find('.fa-check').parent().addClass('disabled');

            $(e.target).parents('tr').find('.automatic_file').addClass('disabled');

            $(e.target).parents('tr').find('.task_label').addClass('disabled');

            $(e.target).parents('tr').find('.fa-files-o').addClass('disabled');



            $(e.target).parents('tr').find('.task_label').css({'color':'#da8e04','font-weight':'800'});

            $(e.target).parents('tr').find('.task_label').css({'color':'#da8e04','font-weight':'800'});

          }

          else{

            $(e.target).parents('tr').find('.fa-times').addClass('disabled');

            $(e.target).parents('tr').find('.fa-times').parent().addClass('disabled');

          }

        }

      });

    }

    else{

      $.ajax({

        url:"<?php echo URL::to('user/na_p30'); ?>",

        type:"get",

        data:{na:0,id:id},

        success: function(result) {

          var complete = $(e.target).parents('tr').find('.fa-check').length;

          if(complete > 0)

          {

              $(e.target).parents('tr').find("select").each(function(){

                $(this).removeClass('disabled');

              });

              $(e.target).parents('tr').find("textarea").each(function(){

                $(this).removeClass('disabled');

              });

              $(e.target).parents('tr').find('.fa-trash').each(function() {

                $(this).removeClass('disabled');

              });

              $(e.target).parents('tr').find('.email_unsent').each(function() {

                $(this).removeClass('disabled');

              });



              $(e.target).parents('tr').find(".pay_p30").removeClass('disabled');

              $(e.target).parents('tr').find(".email_p30").removeClass('disabled');

              $(e.target).parents('tr').find(".liability_class").removeClass('disabled');

              

              $(e.target).parents('tr').find('.faplus').removeClass('disabled');

              $(e.target).parents('tr').find('.fa-check').removeClass('disabled');

              $(e.target).parents('tr').find('.fa-check').parent().removeClass('disabled');

              $(e.target).parents('tr').find('.automatic_file').removeClass('disabled');

              $(e.target).parents('tr').find('.task_label').removeClass('disabled');

              $(e.target).parents('tr').find('.fa-files-o').removeClass('disabled');



              $(e.target).parents('tr').find('.task_label').css({'color':'#fff','font-weight':'600'});

              $(e.target).parents('tr').find('.task_label').css({'color':'#fff','font-weight':'600'});

          }

          else{

            $(e.target).parents('tr').find('.fa-times').removeClass('disabled');

            $(e.target).parents('tr').find('.fa-times').parent().removeClass('disabled');

          }

        }

      });

    }

  }



  if($(e.target).hasClass('email_p30'))

  {

    var id = $(e.target).attr('data-element');

    if($(e.target).is(':checked'))

    {



      $.ajax({

        url:"<?php echo URL::to('user/email_p30'); ?>",

        type:"get",

        data:{email:1,id:id},

        success: function(result) {



        }

      });

    }

    else{

      $.ajax({

        url:"<?php echo URL::to('user/email_p30'); ?>",

        type:"get",

        data:{email:0,id:id},

        success: function(result) {



        }

      });

    }

  }





});

function copyToClipboard(element) {

  var $temp = $("<input>");

  $("body").append($temp);

  $temp.val($(element).val()).select();

  document.execCommand("copy");

  $temp.remove();

}

var convertToNumber = function(value){

       return value.toLowerCase();

}
$(".liability_class").focus(function(){
    $(".hidden_liability").each(function() {
      $(this).hide();
    });
    $(".liability_class").each(function() {
      $(this).show();
    });
    $(this).parent().find(".hidden_liability").show();
    $(this).parent().find(".liability_class").hide();
    $(this).parent().find(".hidden_liability").focus();
  });
$(window).click(function(e) {
  
  if(e.target.id == 'show_incomplete')

  {

    if($(e.target).is(':checked'))

    {

       $(".hide_tr").each(function() {

            $(this).hide();

      });

      $.ajax({

        url:"<?php echo URL::to('user/update_p30_incomplete_status_month'); ?>",

        type:"post",

        data:{value:1},

        success: function(result)

        {

         

        }

      });

    }

    else{

      $(".hide_tr").each(function() {

            $(this).show();

      });

      $.ajax({

        url:"<?php echo URL::to('user/update_p30_incomplete_status_month'); ?>",

        type:"post",

        data:{value:0},

        success: function(result)

        {

          

        }

      });

      

    }

  }

  if(e.target.id == 'show_na')

  {

    if($(e.target).is(':checked'))

    {

       $(".hidena_tr").each(function() {

            $(this).hide();

      });

      $.ajax({

        url:"<?php echo URL::to('user/update_p30_na_status_month'); ?>",

        type:"post",

        data:{value:1},

        success: function(result)

        {

         

        }

      });

    }

    else{

      $(".hidena_tr").each(function() {

            $(this).show();

      });

      $.ajax({

        url:"<?php echo URL::to('user/update_p30_na_status_month'); ?>",

        type:"post",

        data:{value:0},

        success: function(result)

        {

          

        }

      });

      

    }

  }

  if($(e.target).hasClass('email_unsent_button'))
  {
    var attachments = $(".check_attachment_cls:checked").length;
    if(attachments > 0)
    {

    }
    else{
      alert("Please Select atleast one attachment to sent a mail.");
      return false;
    }
  }

  if(e.target.id == "pdf_dont_ernumber")

  {

    $.ajax({

      url:"<?php echo URL::to('user/download_p30_review'); ?>",

      type:"get",

      data:{monthid :"<?php echo $monthid->month_id; ?>",type:1},

      success: function(result)

      {

        SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);

      }

    });

  }

  if(e.target.id == "pdf_na_checked")

  {

    $.ajax({

      url:"<?php echo URL::to('user/download_p30_review'); ?>",

      type:"get",

      data:{monthid :"<?php echo $monthid->month_id; ?>",type:2},

      success: function(result)

      {

        SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);

      }

    });

  }

  if(e.target.id == "pdf_na_not_checked")

  {

    $.ajax({

      url:"<?php echo URL::to('user/download_p30_review'); ?>",

      type:"get",

      data:{monthid :"<?php echo $monthid->month_id; ?>",type:3},

      success: function(result)

      {

        SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);

      }

    });

  }

  if(e.target.id == "select_all_report")

  {

    if($(e.target).is(":checked"))

    {

      $(".report_task").each(function() {

        $(this).prop("checked",true);

      });

    }

    else{

      $(".report_task").each(function() {

        $(this).prop("checked",false);

      });

    }

  }

  if(e.target.id == "report_pdf_button")

  {

    $.ajax({

        url:"<?php echo URL::to('user/p30_report_task'); ?>",

        type:"post",

        data:{month_id: "<?php echo $monthid->month_id; ?>"},

        success: function(result){

          $("#report_pdf_tbody").html(result);

          $(".report_pdf_div").modal('show');

          $("#select_all_report").prop("checked",false);

        }

    });

  }

  if(e.target.id == "download_pdf_report")

  {

    var elementval = '';

    var len = $(".report_task:checked").length;

    if(len > 0)

    {

      $(".report_task:checked").each(function() {

          var element = $(this).attr("data-element");

          if(elementval == "")

          {

            elementval = element;

          }

          else{

            elementval = elementval+','+element;

          }

      });

      $.ajax({

        url:"<?php echo URL::to('user/download_p30_pdf_report'); ?>",

        type:"post",

        data:{task_id:elementval},

        success: function(result){

          SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);

        }

      })

    }

    else{

      alert("Please Select atleast one task to download a report as pdf");

    }

  }

  if($(e.target).hasClass("liability_class"))

  {



      $(".hidden_liability").each(function() {

        $(this).hide();

      });

      $(".liability_class").each(function() {

        $(this).show();

      });

      $(e.target).parent().find(".hidden_liability").show().focus();

      $(e.target).parent().find(".liability_class").hide();


  }

  else if($(e.target).hasClass("hidden_liability"))

  {

      

  }

  else{

      $(".hidden_liability").each(function() {

        $(this).hide();

      });

      $(".liability_class").each(function() {

        $(this).show();

      });

  }

  if($(e.target).hasClass('fa-check'))

  {

    var task_level = $(e.target).parents("tr").find(".task_level_class").val();

    var liability = $(e.target).parents("tr").find(".liability_class").val();

    var pay = $(e.target).parents("tr").find(".pay_p30:checked").length;

    var period = $(e.target).parents("tr").find(".period_class").val();

    var email = $(e.target).parents("tr").find(".email_p30:checked").length;

    var fields = [];

    if(task_level == "") { fields.push("Task Level"); }

    if(liability == "") { fields.push("liability"); }

    if(period == "") { fields.push("Period"); }



    if(task_level == "" || liability == "" || period == "")

    {

      var imp = fields.join(', ')

      alert("Please Fill "+imp+" in order to mark this task as completed.");

    }

    else{

      var r = confirm("Please note that if you Mark this Task as Complete then all the fields will be disabled and you won't be able to change until you mark this task as incomplete again.");

      if (r == true) {

          var id = $(e.target).attr('data-element');

          $.ajax({

              url:"<?php echo URL::to('user/p30_task_status_update'); ?>",

              type:"get",

              dataType:"json",

              data:{status:1,id:id},

              success: function(result) {

                $(e.target).parents('tr').find("select").each(function(){

                  $(this).addClass('disabled');

                });

                $(e.target).parents('tr').find("textarea").each(function(){

                  $(this).addClass('disabled');

                });

                $(e.target).parents('tr').find('.fa-trash').each(function() {

                  $(this).addClass('disabled');

                });

                $(e.target).parents('tr').find('.email_unsent').each(function() {

                  $(this).addClass('disabled');

                });



                $(e.target).parents('tr').find(".pay_p30").addClass('disabled');

                $(e.target).parents('tr').find(".email_p30").addClass('disabled');

                $(e.target).parents('tr').find(".liability_class").addClass('disabled');

                

                $(e.target).parents('tr').find('.faplus').addClass('disabled');

                $(e.target).parents('tr').find('.automatic_file').addClass('disabled');

                $(e.target).parents('tr').find('.task_label').addClass('disabled');

                $(e.target).parents('tr').find('.fa-files-o').addClass('disabled');



                $(e.target).parents('tr').find('.na_p30').addClass('disabled');



                $(e.target).parents('tr').find('.task_label').css({'color':'#f00','font-weight':'800'});

                $(e.target).parents('tr').find('.task_label').css({'color':'#f00','font-weight':'800'});



                $(e.target).parents('tr').addClass("hide_tr");



                $(e.target).removeClass('fa-check');

                $(e.target).addClass('fa-times');

                $(e.target).attr("data-original-title","Mark as Incomplete");



                if($("#show_incomplete").is(':checked'))

                {

                  $(".hide_tr").each(function() {

                        $(this).hide();

                  });

                }

                else{

                  $(".hide_tr").each(function() {

                        $(this).show();

                  });

                }

              }

          });

      }

    }

  }

  if($(e.target).hasClass('fa-times'))

  {

    var r = confirm("Unfreezing will enable all the input fields that you can change all the details");

    if (r == true) {

      var id = $(e.target).attr('data-element');



      $.ajax({

          url:"<?php echo URL::to('user/p30_task_status_update'); ?>",

          type:"get",

          data:{status:0,id:id},

          success: function(result) {

            

            $(e.target).parents('tr').find("select").each(function(){

              $(this).removeClass('disabled');

            });

            $(e.target).parents('tr').find("textarea").each(function(){

              $(this).removeClass('disabled');

            });

            $(e.target).parents('tr').find('.fa-trash').each(function() {

              $(this).removeClass('disabled');

            });



            $(e.target).parents('tr').find('.email_unsent').each(function() {

              $(this).removeClass('disabled');

            });





            $(e.target).parents('tr').find(".pay_p30").removeClass('disabled');

            $(e.target).parents('tr').find(".email_p30").removeClass('disabled');

            $(e.target).parents('tr').find(".liability_class").removeClass('disabled');



            $(e.target).parents('tr').find('.faplus').removeClass('disabled');

            $(e.target).parents('tr').find('.automatic_file').removeClass('disabled');

            $(e.target).parents('tr').find('.task_label').removeClass('disabled');

            $(e.target).parents('tr').find('.fa-files-o').removeClass('disabled');



            $(e.target).parents('tr').find('.na_p30').removeClass('disabled');



            $(e.target).parents('tr').find('.task_label').css({'color':'#fff','font-weight':'600'});



            $(e.target).parents('tr').removeClass("hide_tr");



            $(e.target).removeClass('fa-times');

            $(e.target).addClass('fa-check');

            $(e.target).attr("data-original-title","Mark as Completed");

          }

      });

    }

  }

 

  if($(e.target).hasClass('image_submit'))

  {

    var files = $(e.target).parent().find('.image_file').val();

    if(files == '' || typeof files === 'undefined')

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



  if($(e.target).hasClass('automatic_image_submit'))

  {

    var files = $(e.target).parent().find('.automatic_image_file').val();

    if(files == '' || typeof files === 'undefined')

    {

      $(e.target).parent().find(".automatic_error_files").text("Please Choose the files to proceed");

      return false;

    }

    else{

      $(e.target).parents('td').find('.img_div_automatic').toggle();

    }

  }

  else{

    $(".img_div_automatic").each(function() {

      $(this).hide();

    });

  }

  if($(e.target).hasClass('email_unsent'))

  {
    if($(e.target).hasClass('disable_class'))
    {
      alert("Please Wait for the Liability to update.");
    }
    else{
      $("body").addClass("loading");

      setTimeout(function() {

          var period = $(e.target).parents("tr").find(".period_class").val();

          if(period != "")

          {

              if (CKEDITOR.instances.editor_1) CKEDITOR.instances.editor_1.destroy();



              CKEDITOR.replace('editor_1',

                       {

                        height: '150px',

                       }); 

              var task_id = $(e.target).attr('data-element');

              $.ajax({

                url:'<?php echo URL::to('user/p30_edit_email_unsent_files'); ?>',

                type:'get',

                data:{task_id:task_id},

                dataType:"json",

                success: function(result)

                {

                    CKEDITOR.instances['editor_1'].setData(result['html']);

                    $("#email_attachments").html(result['files']);

                    $(".subject_unsent").val(result['subject']);

                    $("#to_user").val(result['to']);

                    $("#from_user").val(result['from']);

                    $(".emailunsent").modal('show');

                    $("#hidden_email_task_id").val(task_id);

                }

              })

          }

          else{

            alert("Please select the Period and then Proceed with EMail Unsent Files");

          }

          $("body").removeClass("loading");

      },7000);
    }
  }

  if($(e.target).hasClass('faplus'))

  {

    var temp = $(e.target).parents('tr').find(".network_input");

    copyToClipboard(temp);

  }

  if($(e.target).hasClass('faplus'))

  {

    var pos = $(e.target).position();

    var leftposi = parseInt(pos.left) - 400;

    $(e.target).parent().find('.img_div').css({"position":"absolute","top":pos.top,"left":leftposi});

    $(e.target).parent().find('.img_div').show();

  }



  if($(e.target).hasClass('automatic_file'))

  {

    var temp = $(e.target).parents('tr').find(".network_input");

    copyToClipboard(temp);

  }

  if($(e.target).hasClass('automatic_file'))

  {

    var pos = $(e.target).position();

    var leftposi = parseInt(pos.left) - 400;

    $(e.target).parent().find('.img_div_automatic').css({"position":"absolute","top":pos.top,"left":leftposi});

    $(e.target).parent().find('.img_div_automatic').show();

  }



  if($(e.target).hasClass('image_file'))

  {

    $(e.target).parents('td').find('.img_div').toggle();

  }

  if($(e.target).hasClass('automatic_image_file'))

  {

    $(e.target).parents('td').find('.img_div_automatic').toggle();

  }

  if($(e.target).hasClass('trash_image'))

  {

    var r = confirm("Are You sure you want to delete this file");

    if (r == true) {

      var imgid = $(e.target).attr('data-element');



      $.ajax({

          url:"<?php echo URL::to('user/p30_task_delete_image'); ?>",

          type:"get",

          data:{imgid:imgid},

          success: function(result) {

            window.location.reload();

          }

      });

    }

  }

  if($(e.target).hasClass('trash_image_automatic'))

  {

    var r = confirm("Are You sure you want to delete this file");

    if (r == true) {

      var imgid = $(e.target).attr('data-element');



      $.ajax({

          url:"<?php echo URL::to('user/p30_task_delete_xml'); ?>",

          type:"get",

          data:{imgid:imgid},

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

    return false; //this is critical to stop the click event which will trigger a normal file download

  }

  var ascending = false;

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



  if($(e.target).hasClass('eno_sort_std'))

  {

    var sort = $("#eno_sortoptions_std").val();

    if(sort == 'asc')

    {

      $("#eno_sortoptions_std").val('desc');

      var sorted = $('#task_body_std').find('.task_tr_std').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.eno_sort_std_val').html()) <

        convertToNumber($(b).find('.eno_sort_std_val').html()))) ? 1 : -1;

      });

    }

    else{

      $("#eno_sortoptions_std").val('asc');

      var sorted = $('#task_body_std').find('.task_tr_std').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.eno_sort_std_val').html()) <

        convertToNumber($(b).find('.eno_sort_std_val').html()))) ? -1 : 1;

      });

    }

    ascending = ascending ? false : true;

    $('#task_body_std').html(sorted);

  }



  if($(e.target).hasClass('level_sort_std'))

  {

    var sort = $("#level_sortoptions_std").val();

    if(sort == 'asc')

    {

      $("#level_sortoptions_std").val('desc');

      var sorted = $('#task_body_std').find('.task_tr_std').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.level_sort_std_val').find('.task_level_class').val()) <

        convertToNumber($(b).find('.level_sort_std_val').find('.task_level_class').val()))) ? 1 : -1;

      });

    }

    else{

      $("#level_sortoptions_std").val('asc');

      var sorted = $('#task_body_std').find('.task_tr_std').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.level_sort_std_val').find('.task_level_class').val()) <

        convertToNumber($(b).find('.level_sort_std_val').find('.task_level_class').val()))) ? -1 : 1;

      });

    }

    ascending = ascending ? false : true;

    $('#task_body_std').html(sorted);

  }



  if($(e.target).hasClass('period_sort_std'))

  {

    var sort = $("#period_sortoptions_std").val();

    if(sort == 'asc')

    {

      $("#period_sortoptions_std").val('desc');

      var sorted = $('#task_body_std').find('.task_tr_std').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.period_sort_std_val').find('.period_class').val()) <

        convertToNumber($(b).find('.period_sort_std_val').find('.period_class').val()))) ? 1 : -1;

      });

    }

    else{

      $("#period_sortoptions_std").val('asc');

      var sorted = $('#task_body_std').find('.task_tr_std').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.period_sort_std_val').find('.period_class').val()) <

        convertToNumber($(b).find('.period_sort_std_val').find('.period_class').val()))) ? -1 : 1;

      });

    }

    ascending = ascending ? false : true;

    $('#task_body_std').html(sorted);

  }

  if($(e.target).hasClass('liability_sort_std'))

  {

    var sort = $("#liability_sortoptions_std").val();

    if(sort == 'asc')

    {

      $("#liability_sortoptions_std").val('desc');

      var sorted = $('#task_body_std').find('.task_tr_std').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.liability_sort_std_val').find('.hidden_liability').val()) <

        convertToNumber($(b).find('.liability_sort_std_val').find('.hidden_liability').val()))) ? 1 : -1;

      });

    }

    else{

      $("#liability_sortoptions_std").val('asc');

      var sorted = $('#task_body_std').find('.task_tr_std').sort(function(a,b){

        return (ascending ==

             (convertToNumber($(a).find('.liability_sort_std_val').find('.hidden_liability').val()) <

        convertToNumber($(b).find('.liability_sort_std_val').find('.hidden_liability').val()))) ? -1 : 1;

      });

    }

    ascending = ascending ? false : true;

    $('#task_body_std').html(sorted);

  }

});

//setup before functions

var typingTimer;

var doneTypingInterval = 2000;

var $input = $('.hidden_liability');



$input.on('keyup', function (event) {

    $(this).val($(this).val().replace(/[^0-9\.]/g,''));

    if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {

        event.preventDefault();

    }

  var input_val = $(this).val();

  var id = $(this).attr('data-element');

  var element = $(this);

  element.parents("tr").find(".email_unsent").addClass("disable_class");

  clearTimeout(typingTimer);

  typingTimer = setTimeout(doneTyping, doneTypingInterval,input_val,id,element);

});



$input.on('keydown', function () {

  clearTimeout(typingTimer);

});



function doneTyping (input,id,element) {

  $("body").addClass("loading");

  setTimeout(function() {

    var newinput = input.replace(/,/g , "");

      $.ajax({

        url:"<?php echo URL::to('user/p30_task_liability_update'); ?>",

        type:"get",

        data:{liability:newinput,task_id:id},

        success: function(result) {

          var value = newinput;

          var added = addCommas(value);

          x = added.split('.');

          var sep = x[0].split('');

          var final_value = '';

          $.each(sep,function( index, val ) { 

            if(val == ",")

            {

              final_value = final_value+",";

            }

            else{

              if(final_value == "")

              {

                final_value = val;

              }

              else{

                final_value = final_value+val;

              }

            }

          });

          

          if(typeof final_value === "undefined" || final_value == "")

          {

            element.val("0.00");

            element.parent().find(".liability_class").val("0.00");

          }

          else{

             if(typeof x[1] === "undefined")

            {

               var str = final_value+'.'+x[1];

               element.val(added+'.00');

               element.parent().find(".liability_class").val(final_value+'.00');

            }

            else{

              var str = final_value+'.'+x[1];

               element.val(added);

               element.parent().find(".liability_class").val(final_value+'.'+x[1]);

            }

          }

          $("body").removeClass("loading");
          element.parents("tr").find(".email_unsent").removeClass("disable_class");

        }

      })

  },1000);

}

</script>





@stop
@extends('userheader')
@section('content')
<style>
.liability_class{
  cursor:pointer;
}
.update_row_class{
  padding-top:15px;
  padding-bottom:15px;
  border-bottom:1px solid #dfdfdf;
}
.text-design{
  font-size:14px;
}
.load_info{
  background: #000;
    color: #fff;
    padding: 5px 10px;
    font-size: 13px;
    font-weight: normal;
    text-transform: none;
    position: absolute;
    left:50%;
}
.export_csv{
  background: #000;
    color: #fff;
    padding: 5px 10px;
    font-size: 13px;
    font-weight: normal;
    text-transform: none;
    position: absolute;
    left:66%;
}
.update_task{
    background: #000;
    color: #fff;
    padding: 5px 10px;
    font-size: 13px;
    font-weight: normal;
    text-transform: none;
    position: absolute;
    left:58%;
}
.unload_info{
  background: #000;
    color: #fff;
    padding: 5px 10px;
    font-size: 13px;
    font-weight: normal;
    text-transform: none;
    position: absolute;
    left:50%;
}
.load_info:active{
    color: #fff !important;
}
.unload_info:active{
  color: #fff !important;
}
.load_info:hover{
    color: #fff !important;
}
.unload_info:hover{
  color: #fff !important;
}
.load_info:focus{
    color: #fff !important;
}
.unload_info:focus{
  color: #fff !important;
}
.load_info:visited{
    color: #fff !important;
}
.unload_info:visited{
  color: #fff !important;
}
.error{
  color:#f00;
}
.blueinfo{
    color:#240bf7 !important;
    padding:6px;
    margin-left:-3px;
}
.table_bg>thead>tr>th
{
    text-align:left;
}
.select_button table tbody tr td a{
    background: #000;
    text-align: center;
    padding: 8px 12px;
    color: #fff;
    float: left;
    width: 100%;
}
.select_button table tbody tr td a:hover{
    background: #000;
    text-align: center;
    padding: 8px 12px;
    color: #fff;
    float: left;
    width: 100%;
}
.select_button table tbody tr td label{
    color:#000 !important;
    font-weight:800;
    margin-top:6px;
}
.page_title{
  margin-bottom: 0px !important;
  padding: 10px !important;
  background: #fff; z-index: 99
}
.table_bg tbody tr td{
  padding:1px;
  border-bottom:1px solid #000;
  font-weight: 600; font-size: 15px;
  color: #000 !important;
}
.table_bg thead tr th{
  padding:8px;
}
.button_top_right ul li a{padding: 5px 10px; font-size: 16px; font-weight: 600; margin-bottom: 0px;}
.form-control[readonly]{background-color: #e6e6e6 !important}
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
body.loading {
    overflow: hidden;   
}
body.loading .modal_load {
    display: block;
}
.modal_load_content {
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
body.loading_content {
    overflow: hidden;   
}
body.loading_content .modal_load_content {
    display: block;
}
.left_menu{background: #ff0; left: 0px; position: fixed;}
.left_menu li{clear: both; width: 100%;}
.left_menu li a{padding: 10px 15px;}
.left_menu .dropdown-menu{left: 120px; top: 0px;}
.left_menu .dropdown-menu li a{padding: 3px 10px;}

.paye_mars_ul{width: 6800px; height: auto; float: left; border: 1px solid #000;}
.paye_mars_ul ul{margin: 0px; padding: 0px;}
.paye_mars_ul ul li{width: 100%; height: auto; float: left; list-style: none; border-bottom: 1px solid #000; font-size: 18px; font-weight: 700}
.paye_mars_ul ul li .sno{width: 70px; height: auto; float: left; padding: 5px; }
.paye_mars_ul ul li .clientname{width: 90%; height: auto; float: left; padding: 5px; border-left: 1px solid #000;}
</style>
<div class="modal fade emailunsent" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="margin-top: 5%;">
  <div class="modal-dialog" role="document">
    <form action="" method="post" id="emailunsent_form">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Paye MRS Email</h4>
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
              <input type="text" name="cc_unsent" class="form-control input-sm" id="cc_unsent" value="<?php echo $admin_cc; ?>" readonly>
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
              <textarea name="message_editor" id="editor_1"></textarea>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="hidden_email_task_id" id="hidden_email_task_id" value="">
        <input type="button" class="btn btn-primary common_black_button email_unsent_button" value="Send Email">
      </div>
    </div>
    </form>
  </div>
</div>
<div id="alert_modal" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static" style="margin-top:100px;z-index:999999">
  <div class="modal-dialog" style="width:30%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Alert</h4>
      </div>
      <div class="modal-body" id="alert_content">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>

<div id="confirm_modal" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static" style="margin-top:100px">
  <div class="modal-dialog" style="width:30%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Alert</h4>
      </div>
      <div class="modal-body" id="confirm_content">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary yes_hit">Yes</button>
        <button type="button" class="btn btn-primary no_hit">No</button>
      </div>
    </div>
  </div>
</div>
<div id="update_task_model" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static" style="margin-top:100px">
  <div class="modal-dialog" style="width:65%">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Update Task Details</h4>
      </div>
      <div class="modal-body" id="update_task_content">
      </div>
      <div class="modal-footer">
        <input type="hidden" name="hidden_paye_task_id" id="hidden_paye_task_id" value="">
        <button type="button" class="btn btn-default update_paye_task">Update Task Details</button>
      </div>
    </div>
  </div>
</div>
<div class="content_section">
<div class="arrow_right" style="height: auto; padding: 15px; position: fixed; bottom: 10px; background:  #ff0; z-index: 9999999; right: 15px;font-size:34px;display:none">
  <a href="javascript:" class="arrow_right_scroll"><i class="fa fa-arrow-circle-o-up arrow_right_scroll" aria-hidden="true"></i></a>
</div>
  <div class="page_title" style="position:fixed;margin-top: -17px;">
    <div class="col-lg-7 padding_00" style="line-height: 30px; font-size: 20px">
      PAYE M.R.S <?php echo $year->year_name?>  
      <input type="hidden" value="<?php echo $year->year_id?>" class="year_id" name="">
    </div>
    <div class="col-lg-5 padding_00 button_top_right" style="float:left;">
          <ul style="float:none">
            <li class="" style="margin-left: 28px;"><a class="close_create_new_year" href="javascript:" data-element="<?php echo URL::to('user/paye_p30_create_new_year'); ?>" style="float:right">Close and Create New Year</a></li>
          </ul>
    </div>
    <div class="col-lg-12 padding_00">
        <div class="row">
          <div class="col-lg-9" style="padding: 10px; border:5px solid #000">
            <div class="col-lg-12" style="line-height: 30px;">Active Week Periods for all:</div>
              <div class="col-lg-12 padding_00" style="margin-bottom: 0px;">
                  <div class="col-lg-2"></div>
                  <div class="col-lg-3">Week</div>
                  <div class="col-lg-3">Month</div>
                  <div style="clear: both;"></div>
                  <div class="col-lg-2" style="line-height: 30px;">From</div>
                  <div class="col-lg-3">                
                      <select class="form-control week_from" required>
                          <option value="">Select Week From</option>
                          <?php
                          $selected_week_to = '';
                          for($wk=1;$wk<=53;$wk++)
                          {
                            echo '<option value="'.$wk.'"'; if($year->selected_week_from == $wk){ echo 'selected'; } echo '>Week'.$wk.'</option>';
                            $selected_week_to.='<option value="'.$wk.'"'; if($year->selected_week_to == $wk){ $selected_week_to.='selected'; } $selected_week_to.='>Week'.$wk.'</option>';
                          }
                          ?>
                      </select>
                  </div>
                  <div class="col-lg-3 ">    

                      <select class="form-control month_from" required>
                          <option value="">Select Month From</option>
                          <?php
                          $selected_month_to = '';
                          $selected_active_month = '';
                          for($mn=1;$mn<=12;$mn++)
                          {
                            if($mn == "1") { $mon_mon = 'January'; }
                            elseif($mn == "2") { $mon_mon = 'February'; }
                            elseif($mn == "3") { $mon_mon = 'March'; }
                            elseif($mn == "4") { $mon_mon = 'April'; }
                            elseif($mn == "5") { $mon_mon = 'May'; }
                            elseif($mn == "6") { $mon_mon = 'June'; }
                            elseif($mn == "7") { $mon_mon = 'July'; }
                            elseif($mn == "8") { $mon_mon = 'August'; }
                            elseif($mn == "9") { $mon_mon = 'September'; }
                            elseif($mn == "10") { $mon_mon = 'October'; }
                            elseif($mn == "11") { $mon_mon = 'November'; }
                            elseif($mn == "12") { $mon_mon = 'December'; }
                            echo '<option value="'.$mn.'"'; if($year->selected_month_from == $mn){ echo 'selected'; } echo '>'.$mon_mon.'</option>';
                            $selected_month_to.='<option value="'.$mn.'"'; if($year->selected_month_to == $mn){ $selected_month_to.='selected'; } $selected_month_to.='>'.$mon_mon.'</option>';
                            $selected_active_month.='<option value="'.$mn.'"'; if($year->active_month == $mn){ $selected_active_month.='selected'; } $selected_active_month.='>'.$mon_mon.'</option>';
                          }
                          ?>
                      </select>            
                      
                  </div>
                  <div class="col-lg-4 button_top_right" style="float: left;">
                    <ul style="float: left; margin-bottom: 5px;">
                        <li><a href="javascript:" class="apply_class">Apply</a></li>                      
                        <li><a href="javascript:" class="show_all_periods">Show all Periods</a></li>
                        
                      </ul>
                </div>
              </div>
              <div class="col-lg-12 padding_00" style="margin-bottom: 0px;">
                  <div class="col-lg-2" style="line-height: 30px;">To</div>
                  <div class="col-lg-3">   
                    <select class="form-control week_to" required>
                          <option value="">Select Week To</option>
                          <?php echo $selected_week_to; ?>
                      </select> 
                  </div>
                  <div class="col-lg-3 ">                
                      <select class="form-control month_to" required>
                          <option value="">Select Month To</option>
                          <?php echo $selected_month_to; ?>
                      </select>
                  </div>
                  <div class="col-lg-4 button_top_right" style="float: left;">
                    <ul style="float: left; margin-bottom: 5px;">                      
                        <li><a href="javascript:" class="show_active_periods">Show Active Periods Only</a></li>
                      </ul>
                </div>
              </div>
              <div class="col-lg-12 padding_00" >
                <div class="col-lg-2">Active Month:</div>
                <div class="col-lg-3">
                  <select class="form-control active_month">
                      <option value="">Select Period</option>
                      <?php echo $selected_active_month; ?>
                  </select>
                </div>
              </div>
          </div>
          <div class="col-lg-3" style="margin-top:-32px">
            <style type="text/css">
              .one_by_one ul li{margin-bottom: 5px;}
            </style>
            <div class="col-lg-9 button_top_right one_by_one" style="float: left;">
                <ul style="float: left; margin-bottom: 5px;">
                  <li class=""><a href="<?php echo URL::to('user/paye_p30_review_year/'.$year->year_id); ?>">Review Year</a></li>
                  <li><a href="javascript:" class="show_hide_disable" data-element="<?php if($year->disable_clients == 1) { echo 'show'; } else { echo 'hide'; } ?>"><?php if($year->disable_clients == 1) { echo 'Show Disabled Clients'; } else { echo 'Hide Disabled Clients'; } ?></a></li>
                    <li><a href="javascript:" class="show_hide_clients" data-element="<?php if($year->email_clients == 1) { echo 'show'; } else { echo 'hide'; } ?>" title="This will hide/show clients having Email sent on the current active month"><?php if($year->email_clients == 1) { echo 'Show Clients'; } else { echo 'Hide Clients'; } ?></a></li>                      
                    <!-- <li><a href="javascript:" class="show_all_tables">Show All Tables</a></li> -->
                    <li><a href="javascript:" class="refresh_all_clients">Refresh All Tables</a></li>
                    <li><a href="javascript:" class="update_all_tasks">Update All Tasks</a></li>
                  </ul>
            </div>
          </div>
        </div>
    </div>
  </div>
  <div class="col-lg-12" style="clear: both;  margin-top:240px">
    <div style="width:100%;float:left;">
        <?php
        if(isset($_GET['status']) && $_GET['status'] == "apply")
        {
          ?>
          <p class="alert alert-info" style="clear:both; ">Applied Successfully.
              <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
          </p>
          <?php
        }
        if(Session::has('message')) { ?>
            <p class="alert alert-info" style="clear:both; "><?php echo Session::get('message'); ?>
                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            </p>
        <?php }
        if(Session::has('error')) { ?>
            <p class="alert alert-danger" style="clear:both;"><?php echo Session::get('error'); ?>
                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            </p>
        <?php }
        ?>
        </div>
  <?php
  $output='<div class="paye_mars_ul">
        <ul id="task_paye_ul">
        <li>
            <div class="sno" style="background:#000;color:#fff">S.No</div>
            <div class="clientname" style="background:#000;color:#fff">Task Name
                
            </div>
        </li>';
  $iii = 1;
  if(count($payelist)){
    foreach ($payelist as $keytask => $task) {
        $level_name = DB::table('p30_tasklevel')->where('id',$task->task_level)->first();
        if($task->task_level != 0){ $action = $level_name->name; }
        if($task->pay == 0){ $pay = 'No';}else{$pay = 'Yes';}
        if($task->email == 0){ $email = 'No';}else{$email = 'Yes';}
        if($keytask % 2 == 0) { $background = ''; }else{ $background = 'style="background:#e5f7fe"'; }
        if($task->disabled == 1) { $checked = 'checked'; $label_color = 'color:#f00'; $disbledtext = ' (DISABLED)'; } else { $checked = ''; $label_color = 'color:#000'; $disbledtext = ''; }
        $period1 = unserialize($task->month_liabilities_1);
        $period2 = unserialize($task->month_liabilities_2);
        $period3 = unserialize($task->month_liabilities_3);
        $period4 = unserialize($task->month_liabilities_4);
        $period5 = unserialize($task->month_liabilities_5);
        $period6 = unserialize($task->month_liabilities_6);
        $period7 = unserialize($task->month_liabilities_7);
        $period8 = unserialize($task->month_liabilities_8);
        $period9 = unserialize($task->month_liabilities_9);
        $period10 = unserialize($task->month_liabilities_10);
        $period11 = unserialize($task->month_liabilities_11);
        $period12 = unserialize($task->month_liabilities_12);
        $put_output_week = '';
        $put_output_week1 = '';
        $put_output_month = '';
        $put_output_month1 = '';
        for($wk=1; $wk<=53; $wk++)
        {
          $var_week = 'week'.$wk;
          if($task->$var_week == 0){ ${'week'.$wk} = '<div class="payp30_dash">-</div>';} 
          else{
            if($period1[$var_week] != "") { $check_week = $period1[$var_week]; } elseif($period2[$var_week] != "") { $check_week = $period2[$var_week]; } elseif($period3[$var_week] != "") { $check_week = $period3[$var_week]; } elseif($period4[$var_week] != "") { $check_week = $period4[$var_week]; } elseif($period5[$var_week] != "") { $check_week = $period5[$var_week]; } elseif($period6[$var_week] != "") { $check_week = $period6[$var_week]; } elseif($period7[$var_week] != "") { $check_week = $period7[$var_week]; } elseif($period8[$var_week] != "") { $check_week = $period8[$var_week]; } elseif($period9[$var_week] != "") { $check_week = $period9[$var_week]; } elseif($period10[$var_week] != "") { $check_week = $period10[$var_week]; } elseif($period11[$var_week] != "") { $check_week = $period11[$var_week]; } elseif($period12[$var_week] != "") { $check_week = $period12[$var_week]; } else { $check_week = ''; }
            ${'week'.$wk} = '<a href="javascript:" class="';if($check_week == "") {  ${'week'.$wk}.= 'payp30_black task_class_colum'; }elseif($task->$var_week !== $check_week) {  ${'week'.$wk}.= 'payp30_red'; }else{ ${'week'.$wk}.= 'payp30_red'; } ${'week'.$wk}.=' " value="'.$task->id.'" data-element="'.$wk.'">'; if($check_week == "") { ${'week'.$wk}.= number_format_invoice($task->$var_week); } elseif($task->$var_week !== $check_week) { ${'week'.$wk}.= number_format_invoice($check_week).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->$var_week.'" title="Liability Value ('.number_format_invoice($task->$var_week).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { ${'week'.$wk}.= number_format_invoice($task->$var_week); } ${'week'.$wk}.='</a>';}
            $put_output_week.='<th align="right" class="payp30_week_bg week_td_'.$wk.' '; if($year->show_active == 1) { if($year->week_from <=$wk && $year->week_to >=$wk) { $put_output_week.='hide_column'; } else { $put_output_week.='show_column'; } } $put_output_week.='" style="text-align:right;">Week '.$wk.'</th>';
            $put_output_week1.='<td align="left" class="payp30_week_bg week'.$wk.' '; if($year->show_active == 1) { if($year->week_from <=$wk && $year->week_to >=$wk) { $put_output_week1.='hide_column'; } else { $put_output_week1.='show_column'; } } $put_output_week1.='">'.${'week'.$wk}.'</td>';
        }
        for($mn=1; $mn<=12; $mn++)
        {
          $var_month = 'month'.$mn;
          if($task->$var_month == 0){ ${'month'.$mn} = '<div class="payp30_dash">-</div>';} 
          else{
            if($period1[$var_month] != "") { $check_month = $period1[$var_month]; } elseif($period2[$var_month] != "") { $check_month = $period2[$var_month]; } elseif($period3[$var_month] != "") { $check_month = $period3[$var_month]; } elseif($period4[$var_month] != "") { $check_month = $period4[$var_month]; } elseif($period5[$var_month] != "") { $check_month = $period5[$var_month]; } elseif($period6[$var_month] != "") { $check_month = $period6[$var_month]; } elseif($period7[$var_month] != "") { $check_month = $period7[$var_month]; } elseif($period8[$var_month] != "") { $check_month = $period8[$var_month]; } elseif($period9[$var_month] != "") { $check_month = $period9[$var_month]; } elseif($period10[$var_month] != "") { $check_month = $period10[$var_month]; } elseif($period11[$var_month] != "") { $check_month = $period11[$var_month]; } elseif($period12[$var_month] != "") { $check_month = $period12[$var_month]; } else { $check_month = ''; }
            ${'month'.$mn} = '<a href="javascript:" class="';if($check_month == "") {  ${'month'.$mn}.= 'payp30_black task_class_colum_month'; }elseif($task->$var_month !== $check_month) {  ${'month'.$mn}.= 'payp30_red'; }else{ ${'month'.$mn}.= 'payp30_red'; } ${'month'.$mn}.=' " value="'.$task->id.'" data-element="'.$mn.'">'; if($check_month == "") { ${'month'.$mn}.= number_format_invoice($task->$var_month); } elseif($task->$var_month !== $check_month) { ${'month'.$mn}.= number_format_invoice($check_month).'<i class="fa fa-exclamation-triangle blueinfo" data-element="'.$task->$var_month.'" title="Liability Value ('.number_format_invoice($task->$var_month).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { ${'month'.$mn}.= number_format_invoice($task->$var_month); } ${'month'.$mn}.='</a>';}
            if($mn == "1") { $mon_mon = 'Jan'; }
            elseif($mn == "2") { $mon_mon = 'Feb'; }
            elseif($mn == "3") { $mon_mon = 'Mar'; }
            elseif($mn == "4") { $mon_mon = 'Apr'; }
            elseif($mn == "5") { $mon_mon = 'May'; }
            elseif($mn == "6") { $mon_mon = 'Jun'; }
            elseif($mn == "7") { $mon_mon = 'Jul'; }
            elseif($mn == "8") { $mon_mon = 'Aug'; }
            elseif($mn == "9") { $mon_mon = 'Sep'; }
            elseif($mn == "10") { $mon_mon = 'Oct'; }
            elseif($mn == "11") { $mon_mon = 'Nov'; }
            elseif($mn == "12") { $mon_mon = 'Dec'; }
            $put_output_month.='<th align="right" class="payp30_month_bg month_td_'.$mn.' '; if($year->show_active == 1) { if($year->month_from <=$mn && $year->month_to >=$mn) { $put_output_month.='hide_column'; } else { $put_output_month.='show_column'; } } $put_output_month.='" style="text-align:right;">'.$mon_mon.' '.$year->year_name.'</th>';
            $put_output_month1.='<td align="left" class="payp30_month_bg month'.$mn.' '; if($year->show_active == 1) { if($year->month_from <=$mn && $year->month_to >=$mn) { $put_output_month1.='hide_column'; } else { $put_output_month1.='show_column'; } } $put_output_month1.='">'.${'month'.$mn}.'</td>';
        }
        $output.='<li class="main_li" '.$background.'>
                <div class="sno">'.$iii.'</div>
                 <div class="clientname"><input type="checkbox" name="disable_clients" class="disable_clients" id="disable_'.$task->id.'" value="'.$task->id.'" '.$checked.'> <label class="task_name_label task_name_label2" for="disable_'.$task->id.'" style="'.$label_color.'">'.$task->task_name.$disbledtext.'</label> 
                  <a href="javascript:" class="export_csv" data-element="'.$task->id.'"> Export CSV </a>
                  <a href="javascript:" class="update_task" data-element="'.$task->id.'">Update Task</a>
                    <div class="load_info_table">
                      <table class="table_bg table-fixed-header table_paye_p30" id="table_'.$task->id.'" style="margin-bottom:20px; width:6700px; margin-top:40px">
                        <thead class="header">
                          <tr>
                              <th style="border-right: 1px solid #000 !important; border-left: 1px solid #000 !important; border: 1px solid #000;width:50px" valign="top">S.No</th>                    
                              <th colspan="8" style="text-align:left;width:500px">
                                  Clients
                              </th>                    
                              <th style="border-bottom: 0px; text-align:center;width:300px;" width="200px">
                                  Email Sent                        
                              </th>                    
                              <th style="width:50px"></th>
                              '.$put_output_week.'
                              '.$put_output_month.'
                          </tr>
                        </thead>
                        <tbody>
                            <tr class="task_row_'.$task->id.'">
                              <td style="border-right: 1px solid #000 !important; border-left: 1px solid #000 !important; border: 1px solid #fff;" valign="top">1</td>
                              <td colspan="3" style="border-bottom: 0px; text-align: left; height:110px;"> 
                                <div class="update_task_label_sample" style="width:400px; position:absolute; margin-top:-50px;">
                                  <b class="task_name_label" style="font-size:18px;'.$label_color.'">'.$task->task_name.'</b><br>
                                  Emp No. '.$task->task_enumber.'<br>
                                  Action: '.$action.'<br>
                                  PAY: '.$pay.'<br>
                                  Email: '.$email.'                   
                                </div>
                              </td> 
                              <td style="text-align: center;" valign="bottom">ROS Liability</td>
                              <td style="text-align: center;" valign="bottom">Task Liability</td>
                              <td valign="bottom">Diff</td>
                              <td style="text-align: center;" valign="bottom">
                                Payments
                                <a href="javascript:" class="fa fa-plus payments_attachments"></a>
                                <div class="img_div">
                                    <form name="image_form" id="image_form" action="'.URL::to('user/payments_attachment?task_id='.$task->id).'" method="post" enctype="multipart/form-data" style="text-align: left;">
                                      <input type="file" name="image_file" class="form-control image_file" value="" accept=".csv">
                                      <div class="image_div_attachments"></div>
                                      <input type="submit" name="image_submit" class="btn btn-sm btn-primary image_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
                                      <spam class="error_files"></spam>
                                    </form>
                                  </div>
                              </td>
                              <td colspan="2" style="text-align:center; border-right:1px solid #000;">
                                <input type="hidden" class="active_month_class payetask_'.$task->id.'" value="'.$task->active_month.'" />
                              </td>
                              <td style="padding:0px 10px;"><a href="javascript:"><i class="fa fa-refresh refresh_liability" data-element="'.$task->id.'"></i></a></td>
                              '.$put_output_week1.'
                              '.$put_output_month1.'
                              ';
                          $output.='</tr>';
                          $total_ros_value = 0;
                          $total_task_value = 0;
                          $total_diff_value = 0;
                          $total_payment_value = 0;
                        for($i=1; $i<=12;$i++)
                        {
                          if($i == 1) { $month_name = 'Jan'; }
                          elseif($i == 2) { $month_name = 'Feb'; }
                          elseif($i == 3) { $month_name = 'Mar'; }
                          elseif($i == 4) { $month_name = 'Apr'; }
                          elseif($i == 5) { $month_name = 'May'; }
                          elseif($i == 6) { $month_name = 'Jun'; }
                          elseif($i == 7) { $month_name = 'Jul'; }
                          elseif($i == 8) { $month_name = 'Aug'; }
                          elseif($i == 9) { $month_name = 'Sep'; }
                          elseif($i == 10) { $month_name = 'Oct'; }
                          elseif($i == 11) { $month_name = 'Nov'; }
                          elseif($i == 12) { $month_name = 'Dec'; }
                          if($i == $task->active_month) { $checked = "checked"; } else { $checked = ''; }
                          if(${'period'.$i}['last_email_sent'] == "") { $email_sent = ''; }
                          else{ $email_sent = date('d M Y @ H:i', strtotime(${'period'.$i}['last_email_sent'])); }
                          $put_output_week2 = '';
                          $put_output_month2 = '';
                          for($wk=1;$wk<=53;$wk++)
                          {
                            if(${'week'.$wk} == '<div class="payp30_dash">-</div>')
                            {
                              ${'periodweek'.$wk} = '<div class="payp30_dash week'.$wk.'_class week'.$wk.'_class_'.$task->id.'-'.$i.' '; if($year->show_active == 1) { if($year->week_from <=$wk && $year->week_to >=$wk) { ${'periodweek'.$wk}.='hide_column_inner'; } else { ${'periodweek'.$wk}.='show_column_inner'; } } ${'periodweek'.$wk}.='">-</div>';
                            }
                            elseif(${'period'.$i}['week'.$wk] == 0){ 
                              ${'periodweek'.$wk} = '<div class="payp30_dash week'.$wk.'_class week'.$wk.'_class_'.$task->id.'-'.$i.' '; if($year->show_active == 1) { if($year->week_from <=$wk && $year->week_to >=$wk) { ${'periodweek'.$wk}.='hide_column_inner'; } else { ${'periodweek'.$wk}.='show_column_inner'; } } ${'periodweek'.$wk}.='">-</div>';
                            }
                            else{
                                ${'periodweek'.$wk} = '<a href="javascript:" class="payp30_green week'.$wk.'_class week'.$wk.'_class_'.$task->id.'-'.$i.' week_remove '; if($year->show_active == 1) { if($year->week_from <=$wk && $year->week_to >=$wk) { ${'periodweek'.$wk}.='hide_column_inner'; } else { ${'periodweek'.$wk}.='show_column_inner'; } } ${'periodweek'.$wk}.=' " value="'.$i.'" data-value="'.$task->id.'" data-element="'.$wk.'">'.number_format_invoice(${'period'.$i}['week'.$wk]).'</a>';
                            }
                            $put_output_week2.='<td align="left" class="payp30_week_bg">'.${'periodweek'.$wk}.'</td>';
                          }
                          for($mn=1;$mn<=12;$mn++)
                          {
                            if(${'month'.$mn} == '<div class="payp30_dash">-</div>')
                            {
                              ${'periodmonth'.$mn} = '<div class="payp30_dash month'.$mn.'_class month'.$mn.'_class_'.$task->id.'-'.$i.' '; if($year->show_active == 1) { if($year->month_from <=$mn && $year->month_to >=$mn) { ${'periodmonth'.$mn}.='hide_column_inner'; } else { ${'periodmonth'.$mn}.='show_column_inner'; } } ${'periodmonth'.$mn}.='">-</div>';
                            }
                            elseif(${'period'.$i}['month'.$mn] == 0){ 
                                ${'periodmonth'.$mn} = '<div class="payp30_dash month'.$mn.'_class month'.$mn.'_class_'.$task->id.'-'.$i.' '; if($year->show_active == 1) { if($year->month_from <=$mn && $year->month_to >=$mn) { ${'periodmonth'.$mn}.='hide_column_inner'; } else { ${'periodmonth'.$mn}.='show_column_inner'; } } ${'periodmonth'.$mn}.='">-</div>';
                            }
                            else{
                                ${'periodmonth'.$mn} = '<a href="javascript:" class="payp30_green month'.$mn.'_class month'.$mn.'_class_'.$task->id.'-'.$i.' month_remove '; if($year->show_active == 1) { if($year->month_from <=$mn && $year->month_to >=$mn) { ${'periodmonth'.$mn}.='hide_column_inner'; } else { ${'periodmonth'.$mn}.='show_column_inner'; } } ${'periodmonth'.$mn}.=' " value="'.$i.'" data-value="'.$task->id.'" data-element="'.$mn.'">'.number_format_invoice(${'period'.$i}['month'.$mn]).'</a>';
                            }
                            $put_output_month2.='<td align="left" class="payp30_month_bg">'.${'periodmonth'.$mn}.'</td>';
                          }

                          $output.='<tr class="month_row_'.$task->id.'-'.$i.'" data-element="'.$i.'" data-value="'.$task->id.'">
                              <td style="border-right: 1px solid #000 !important; border-left: 1px solid #000 !important; border: 1px solid #fff; border-bottom:1px solid #000"></td>
                              <td colspan="2" style="width: 40px; text-align: right; border-bottom: 0px;">
                                  <input type="radio" name="month_name_'.$task->id.'" class="month_class month_class_'.$i.'" value="'.$i.'" data-element="'.$task->id.'" '.$checked.'><label>&nbsp;</label>
                              </td>
                              <td style="width: 100px; border-bottom: 0px;">'.$month_name.'-'.$year->year_name.'</td>
                              <td style="width: 110px; border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control ros_class ros_class_'.$task->id.'-'.$i.'" data-element="'.$i.'" data-value="'.$task->id.'" value="'.number_format_invoice(${'period'.$i}['ros_liability']).'"></td>
                              <td style="width: 110px; border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control liability_class liability_class_'.$task->id.'-'.$i.'" value="'.number_format_invoice(${'period'.$i}['task_liability']).'" readonly=""></td>
                              <td style="width: 110px; border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control diff_class diff_class_'.$task->id.'-'.$i.'" value="'.number_format_invoice(${'period'.$i}['liability_diff']).'" readonly=""></td>
                              <td style="width: 110px; border-bottom: 0px; border: 1px solid #000; border-top: 0px;">
                                <input class="form-control payment_class payment_class_'.$task->id.'-'.$i.'" style="color:#009800;" data-element="'.$i.'" data-value="'.$task->id.'" value="'.number_format_invoice(${'period'.$i}['payments']).'">
                              </td>
                              <td colspan="3" style="width: 180px; "><a href="javascript:" class="fa fa-envelope email_unsent email_unsent_'.$task->id.'-'.$i.'" data-element="'.$i.'" data-value="'.$task->id.'"></a><br>'.$email_sent.'</td>
                                '.$put_output_week2.'
                                '.$put_output_month2.'';
                          $output.='</tr>';

                          $total_ros_value = $total_ros_value + number_format_invoice_without_comma(${'period'.$i}['ros_liability']);
                          $total_task_value = $total_task_value + number_format_invoice_without_comma(${'period'.$i}['task_liability']);
                          $total_diff_value = $total_diff_value + number_format_invoice_without_comma(${'period'.$i}['liability_diff']);
                          $total_payment_value = $total_payment_value + number_format_invoice_without_comma(${'period'.$i}['payments']);
                        }
                        $output.='<tr class="task_total_row_'.$task->id.'">
                                <td style="border-right: 1px solid #000 !important; border-left: 1px solid #000 !important; border: 1px solid #fff; border-bottom:1px solid #000"></td>
                                
                                <td colspan="2" style="width: 40px; text-align: right; border-bottom: 0px;">
                                    
                                </td>
                                <td style="width: 100px; border-bottom: 0px;">Total </td>
                                <td style="width: 110px; border-bottom: 0px; border: 1px solid #000; border-top: 0px;">
                                  <input class="form-control total_ros_class" value="'.number_format_invoice($total_ros_value).'"></td>
                                <td style="width: 110px; border-bottom: 0px; border: 1px solid #000; border-top: 0px;">
                                  <input class="form-control total_liability_class" value="'.number_format_invoice($total_task_value).'" readonly=""></td>
                                <td style="width: 110px; border-bottom: 0px; border: 1px solid #000; border-top: 0px;">
                                  <input class="form-control total_diff_class" value="'.number_format_invoice($total_diff_value).'" readonly=""></td>
                                <td style="width: 110px; border-bottom: 0px; border: 1px solid #000; border-top: 0px;">
                                  <input class="form-control total_payment_class" style="color:#009800;" value="'.number_format_invoice($total_payment_value).'">
                                </td>
                                <td colspan="3" style="width: 180px;"></td>
                                <td colspan="65"></td>
                            </tr></tbody>
                        </table>
                    </div>
                </div>
            </li>';
            $iii++;
    }
  }
  else{
    $output.='
      <li>
          <div class="sno"></div>
          <div class="clientname"> Empty
             
          </div>
      </li>';
  }
  $output.='</ul>
  </div>';
  echo $output;
  ?>

    </div>
  </div>
</div>
<input type="hidden" name="hidden_loading_status" id="hidden_loading_status" value="">
<div class="modal_load"></div>
<div class="modal_load_content" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until all the tables are loaded.</p>
  <p style="font-size:18px;font-weight: 600;">Loading: <span id="count_first"></span> of <span id="count_last"></span></p>
  <p style="font-size:18px;font-weight: 600;">This may take upto <span id="estimated_time"></span> Minutes</p>
</div>

<script type="text/javascript">
<?php if(!empty($_GET['div_id']))
{ $divid = $_GET['div_id']; ?>
  $(function() {
    setTimeout(function() {
      $(document).scrollTop( $("#table_<?php echo $divid; ?>").offset().top - parseInt(150) );
    },3000);
  });
<?php } ?>
$(window).scroll(function(){
    if($(this).scrollTop()){
      $(".navbar").fadeOut(1000);
      $(".footer_row").fadeOut(1000);
      $(".arrow_right").fadeIn(1000);
      $(".content_section").css("margin-top","10px");
    }
    else{
     $(".navbar").fadeIn(1000);
     $(".footer_row").fadeIn(1000);
     $(".arrow_right").fadeOut(1000);
     $(".content_section").css("margin-top","100px");
    }
});
$("document").ready(function() {
  $("body").addClass("loading");
    <?php
    if($year->show_active == 1)
    { ?>
        $(".hide_column").show();
        $(".hide_column_inner").parents("td").show();
        $(".show_column").hide();
        $(".show_column_inner").parents("td").hide();
    <?php }
    else{ ?>
        $(".hide_column").show();
        $(".hide_column_inner").parents("td").show();
        $(".show_column").show();
        $(".show_column_inner").parents("td").show();
    <?php }
    if($year->disable_clients == 1) { ?> var disablestatus = 'hide'; <?php }
    else{ ?> var disablestatus = 'show'; <?php }
    if($year->email_clients == 1) { ?> var emailstatus = 'hide'; <?php }
    else{ ?> var emailstatus = 'show'; <?php } ?>
    $.ajax({
      url:"<?php echo URL::to('user/update_paye_p30_year_disabled_status'); ?>",
      type:"post",
      data:{year:"<?php echo $year->year_id; ?>",status:disablestatus},
      success: function(result)
      {
        if(disablestatus == "hide") { $(".disable_clients:checked").parents(".main_li").hide(); }
        else{
          $(".disable_clients").parents(".main_li").show();
          var disable_status = $(".show_hide_clients").attr("data-element");
          if(disable_status == "show")
          {
            var explode = result.split(",");
            $.each(explode, function(index,value) {
              $("#disable_"+value).parents(".main_li").hide();
            });
          }
        }
      }
    });

    $.ajax({
        url:"<?php echo URL::to('user/update_paye_p30_year_email_clients_status'); ?>",
        type:"post",
        data:{year:"<?php echo $year->year_id; ?>",status:emailstatus},
        success: function(result)
        {
          if(emailstatus == "hide")
          {
            var explode = result.split(",");
            $.each(explode, function(index,value) {
              $("#disable_"+value).parents(".main_li").hide();
            });
          }
          else{
            $(".disable_clients").parents(".main_li").show();
            var disable_status = $(".show_hide_disable").attr("data-element");
            if(disable_status == "show")
            {
              $(".disable_clients:checked").parents(".main_li").hide();
            }
          }
        }
      });

    setTimeout(function() {
      $("body").removeClass("loading");
    },3000);
    $("#emailunsent_form").validate({
       rules: {
         from_user: "required",
         to_user: "required",
         subject_unsent: "required"
       },
       messages: {
         from_user: "Please Select the User",
         to_user: "Please Select the User",
         subject_unsent: "Please enter the Subject",

       }
    });
});
function ajaxRequest (urls) {
    if (urls.length > 0) {
        $.ajax({
          url: urls.pop(),
          method:"get",
          dataType:"json",
          success: function(result){
            if(result['update'] == 0)
            {

            }
            else{
              for(var $i=1; $i<=53; $i++)
              {
                if(jQuery.inArray($i, result['changed_liability_week']) !== -1) { 
                  if($(".task_row_"+result['payep30_task']).find(".week"+$i).find(".blueinfo").length == 0) { $(".task_row_"+result['payep30_task']).find(".week"+$i).find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week'+$i]+'" title="Liability Value ('+result['week'+$i]+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); 
                  } 
                } 
                else { $(".task_row_"+result['payep30_task']).find(".week"+$i).find("a").html(result['week'+$i]); $(".task_row_"+result['payep30_task']).find(".week"+$i).find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="'+$i+'">'+result['week'+$i]+'</a>'); 
                }
              }
              for(var $mn=1; $mn<=12; $mn++)
              {
                if(jQuery.inArray($mn, result['changed_liability_month']) !== -1) { 
                  if($(".task_row_"+result['payep30_task']).find(".month"+$mn).find(".blueinfo").length == 0) { 
                    $(".task_row_"+result['payep30_task']).find(".month"+$mn).find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['month'+$mn]+'" title="Liability Value ('+result['month'+$mn]+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); 
                  } 
                } 
                else { $(".task_row_"+result['payep30_task']).find(".month"+$mn).find("a").html(result['month'+$mn]); $(".task_row_"+result['payep30_task']).find(".month"+$mn).find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum_month" value="'+result['payep30_task']+'" data-element="'+$mn+'">'+result['month'+$mn]+'</a>'); 
                }
              }
            }
          }
        })
        .done(function (result) {
            ajaxRequest(urls);
        });
    }
    else{
      $("body").removeClass("loading");
      $("#hidden_loading_status").val("");
      $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>Refresh made Successfully for all the clients.</p>", fixed:true});
    }
}

function ajaxRequest_update (urls) {
    if (urls.length > 0) {
        $.ajax({
          url: urls.pop(),
          method:"get",
          dataType:"json",
          success: function(result){
            if(result['type'] == "2")
            {
              if($('#update_task_model').hasClass('in'))
              {

              }
              else{
                $("#update_task_model").modal("show");
              }
              $("#update_task_content").append(result['output']);
            }
            else{
              $(".task_row_"+result['task_id']).find(".update_task_label_sample").html(result['output']);
              $(".task_row_"+result['task_id']).parents(".clientname").find(".task_name_label2").html(result['outputtext']);
            }
          }
        })
        .done(function (result) {
            ajaxRequest_update(urls);
        });
    }
    else{
      $("body").removeClass("loading");
      $("#hidden_loading_status").val("");
    }
}
$(window).dblclick(function(e) {
  if($(e.target).hasClass('liability_class'))
  {
    var value = $(e.target).val();
    var paye_task = $(e.target).parents("tr").find(".ros_class").attr("data-value");
    var paye_month = $(e.target).parents("tr").find(".ros_class").attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/update_ros_liability'); ?>",
      type:"post",
      data:{paye_task:paye_task,paye_month:paye_month,value:value},
      success: function(result)
      {
        $(e.target).parents("tr").find(".ros_class").val(value);
        $(e.target).parents("tr").find(".diff_class").val('0.00');
      }
    });
  }
});

$(window).click(function(e) {
if($(e.target).hasClass("show_active_periods")){
    var week_from = $(".week_from").val(); 
    var week_to = $(".week_to").val();
    var month_from = $(".month_from").val();
    var month_to = $(".month_to").val();
    var year_id = $(".year_id").val();

    if(week_from == "" && week_to == "" && month_from == "" && month_to == "")
    {
        $("#alert_modal").modal("show");
        $("#alert_content").html("Please choose the Week and Month from the dropdown and proceed to apply");
    }
    else if(week_from != "" && week_to == "")
    {
        $("#alert_modal").modal("show");
        $("#alert_content").html("Please choose the Week To option from the dropdown and proceed to apply");
    }
    else if(week_to != "" && week_from == "")
    {
        $("#alert_modal").modal("show");
        $("#alert_content").html("Please choose the Week From option from the dropdown and proceed to apply");
    }
    else if(month_from != "" && month_to == "")
    {
        $("#alert_modal").modal("show");
        $("#alert_content").html("Please choose the Month To option from the dropdown and proceed to apply");
    }
    else if(month_to != "" && month_from == "")
    {
        $("#alert_modal").modal("show");
        $("#alert_content").html("Please choose the Month From option from the dropdown and proceed to apply");
    }
    else{
      if(week_from == "1") { week_from = 1; }
      else if(week_from == "") { week_from = 0; }
      else{ week_from = parseInt(week_from) - 1; }
      if(week_to == "53") { week_to = 53; }
      else if(week_to == "") { week_to = 0; }
      else{ week_to = parseInt(week_to) + 1; }
      if(month_from == "1") { month_from = 1; }
      else if(month_from == "") { month_from = 0; }
      else{ month_from = parseInt(month_from) - 1; }
      if(month_to == "12") { month_to = 12; }
      else if(month_to == "") { month_to = 0; }
      else{ month_to = parseInt(month_to) + 1; }
      var weekcount = parseInt(week_to) - parseInt(week_from);
      var monthcount = parseInt(month_to) - parseInt(month_from);
      var weekcountval = parseInt(weekcount) + 1;
      var monthcountval = parseInt(monthcount) + 1;
      var totalval = weekcountval + monthcountval;
      var pixelval = parseInt(totalval) * 90;
      var totalpixel = 850 + parseInt(pixelval);
      
      $.ajax({
          url:"<?php echo URL::to('user/paye_p30_active_periods'); ?>",
          type:"post",
          dataType:"json",
          data:{week_from:week_from, week_to:week_to, month_from:month_from, month_to:month_to, year_id:year_id  },
          success: function(result){
              $("body").addClass("loading");
              $(".payp30_week_bg").hide();
              $(".payp30_month_bg").hide();
              for(var i=week_from; i<=week_to; i++)
              {
                $(".refresh_liability:visible").parents(".table_paye_p30").css("width",totalpixel+"px");
                  $(".week_td_"+i).show();
                  $(".week"+i).show();
                  $(".week"+i+"_class").parents("td").show();
              }
              for(var i=month_from; i<=month_to; i++)
              {
                  $(".month_td_"+i).show();
                  $(".month"+i).show();
                  $(".month"+i+"_class").parents("td").show();
              }
              $("body").removeClass("loading");
          }
      })
    }
}
if($(e.target).hasClass("show_all_periods")){
    $("body").addClass("loading");
    var week_from = $(".week_from").val(); 
    var week_to = $(".week_to").val();
    var month_from = $(".month_from").val();
    var month_to = $(".month_to").val();
    var year_id = $(".year_id").val();
    $.ajax({
        url:"<?php echo URL::to('user/paye_p30_all_periods'); ?>",
        type:"post",
        data:{week_from:week_from, week_to:week_to, month_from:month_from, month_to:month_to, year_id:year_id  },
        success: function(result){
            $(".refresh_liability:visible").parents(".table_paye_p30").css("width","6700px");
            $(".payp30_week_bg").show();
            $(".payp30_month_bg").show();
            $("body").removeClass("loading");
        }
    })    
}
if($(e.target).hasClass('close_create_new_year'))
{
  var r = confirm("Are you Sure you want to create a new year?");
  if(r)
  {
    var href=$(e.target).attr("data-element")
    window.location.replace(href);
  }
}
if($(e.target).hasClass('payments_attachments'))
{
  $(e.target).parents("td").find(".img_div").show();
}
else if($(e.target).parents('.img_div').length > 0)
{
  $(e.target).parents("td").find(".img_div").show();
}
else{
  $(".img_div").hide();
}
if($(e.target).hasClass('image_submit'))
{
  e.preventDefault();
  var alert = 0;
  $(e.target).parents(".table_paye_p30").find(".payment_class").each(function() {
    var value = $(this).val();
    if(value != '0.00' && value != '0' && value != "")
    {
      alert++;
    }
  });
  if(alert == 0)
  {
    $(e.target).parents("form").submit();
  }
  else{
    var r = confirm("this process will overwrite the payments with valid payments form the uploaded ROS payment extract file.  Do you wish to continue?")
    if(r)
    {
      $(e.target).parents("form").submit();
    }
  }
}
if($(e.target).hasClass('update_paye_task'))
{
  var lengthval = $(".update_row_class").length;
  var update_task = $(".update_task_radio:checked").length;
  if(lengthval == update_task)
  {
    $("body").addClass("loading");
    $(".update_task_radio:checked").each(function(index) {
      var that = this;
      var t = setTimeout(function() {
        var value = $(that).val();
        var paye_task_id = $(that).attr("data-element");
        $.ajax({
          url:"<?php echo URL::to('user/update_paye_task_details'); ?>",
          type:"get",
          dataType:"json",
          data:{value:value,paye_task_id:paye_task_id},
          success: function(result)
          {
            $(".task_row_"+paye_task_id).find(".update_task_label_sample").html(result['output']);
            $("#update_task_model").modal("hide");
            $("#update_task_content").html("");
            $(".task_row_"+paye_task_id).parents(".clientname").find(".task_name_label2").html(result['outputtext']);
            if(update_task == index + 1)
            {
              $("body").removeClass("loading");
            }
          }
        })
      }, 1000 * index);
    });
  }
  else{
    $("#alert_modal").modal("show");
    $("#alert_content").html("Please select any of the option to update for all the client(s).");
  }
}
if($(e.target).hasClass('update_task'))
{
  var loading_status = $("#hidden_loading_status").val();
  if(loading_status == "")
  {
    $("body").addClass("loading");  
  }
  var task_id = $(e.target).attr("data-element");
  $.ajax({
    url:"<?php echo URL::to('user/check_paye_task_details'); ?>",
    type:"get",
    dataType:"json",
    data:{task_id:task_id},
    success: function(result)
    {
      if(result['type'] == "2")
      {
        if($('#update_task_model').hasClass('in'))
        {

        }
        else{
          $("#update_task_model").modal("show");
        }
        $("#update_task_content").append(result['output']);
        if(loading_status == "")
        {
          $("body").removeClass("loading");
        }
      }
      else{
        $(".task_row_"+task_id).find(".update_task_label_sample").html(result['output']);
        $(".task_row_"+task_id).parents(".clientname").find(".task_name_label2").html(result['outputtext']);
        if(loading_status == "")
        {
          $("body").removeClass("loading");
        }
      }
    }
  })
}
if($(e.target).hasClass('show_all_tables'))
{
  $("#hidden_loading_status").val("1");
  $("body").addClass("loading_content");
  var countval = $(".load_info").length;
  var gettime = countval * 5;
  var convert_minutes = Math.round(gettime / 60);
  $("#count_last").html(countval);
  $("#estimated_time").html(convert_minutes);
  $(".load_info").each(function(index) {
    var that = this;
    var t = setTimeout(function() { 
        $("#count_first").html(index+1);
        $(that).trigger('click');
        
    }, 5000 * index);
  });
}
if($(e.target).hasClass('refresh_all_clients'))
{
  $("#hidden_loading_status").val("1");
  $("body").addClass("loading");
  var year_id = $(".year_id").val();
  $.ajax({
    url:"<?php echo URL::to('user/get_employee_numbers'); ?>",
    type:"get",
    data:{year_id:year_id},
    success: function(result)
    {
      if(result == "")
      {
        setTimeout(function() {
          $("body").removeClass("loading");
          $("#hidden_loading_status").val("");
          $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>Refresh made Successfully for all the clients.</p>", fixed:true});
        },5000);
      }
      else{
        var urls= '';
        var ids = result.split(",");
        $.each( ids, function( key, value ) {
          var task_id = value;
          if(urls == "")
          {
            urls = "<?php echo URL::to('user/refresh_paye_p30_liability'); ?>?task_id="+task_id+"&year_id="+year_id;
          }
          else{
            urls = urls+",<?php echo URL::to('user/refresh_paye_p30_liability'); ?>?task_id="+task_id+"&year_id="+year_id;
          }
        });
        var url = urls.split(",");
        ajaxRequest(url);
      }
    }
  });
}
if($(e.target).hasClass('update_all_tasks'))
{
  $("#hidden_loading_status").val("1");
  $("body").addClass("loading");
  var countval = $(".update_task").length;
  var urls= '';
  $(".update_task").each(function(index) {
    var task_id = $(this).attr("data-element");
    if(urls == "")
    {
      urls = "<?php echo URL::to('user/check_paye_task_details'); ?>?task_id="+task_id;
    }
    else{
      urls = urls+",<?php echo URL::to('user/check_paye_task_details'); ?>?task_id="+task_id;
    }
  });

  var url = urls.split(",");
  ajaxRequest_update(url);
}

if($(e.target).hasClass('export_csv'))
{
  var base_url = '<?php echo URL::to('papers'); ?>';
  var task_id = $(e.target).attr("data-element");
  $.ajax({
    url:"<?php echo URL::to('user/paye_p30_create_csv'); ?>",
    type:"post",
    data:{task_id:task_id},
    success: function(result)
    {
      SaveToDisk(base_url+'/'+result,result)
    }
  })
}
if($(e.target).hasClass("disable_clients"))
{
  var task_id = $(e.target).val();
  if($(e.target).is(":checked"))
  {
    var status = 1;
  }
  else{
    var status = 0;
  }
  $.ajax({
    url:"<?php echo URL::to('user/update_paye_p30_clients_status'); ?>",
    type:"post",
    data:{task_id:task_id,status:status},
    success: function(result)
    {
      if($(e.target).is(":checked"))
      {
        var label = $(e.target).parent().find(".task_name_label2").html();
        var content = label+' (DISABLED)';
        $(e.target).parent().find(".task_name_label2").html(content);
        $(e.target).parent().find(".task_name_label").css("color","#f00");
      }
      else{
        var label = $(e.target).parent().find(".task_name_label2").html();
        var content = label.replace(" (DISABLED)","");
        $(e.target).parent().find(".task_name_label2").html(content);
        $(e.target).parent().find(".task_name_label").css("color","#000");
      }
    }
  })
}
if($(e.target).hasClass('show_hide_disable'))
{
  var status = $(e.target).attr("data-element");
  $.ajax({
    url:"<?php echo URL::to('user/update_paye_p30_year_disabled_status'); ?>",
    type:"post",
    data:{year:"<?php echo $year->year_id; ?>",status:status},
    success: function(result)
    {
      if(status == "hide")
      {
        $(e.target).attr("data-element","show");
        $(e.target).html("Show Disabled Clients");
        $(".disable_clients:checked").parents(".main_li").hide();
      }
      else{
        $(e.target).attr("data-element","hide");
        $(e.target).html("Hide Disabled Clients");
        $(".disable_clients").parents(".main_li").show();

        var disable_status = $(".show_hide_clients").attr("data-element");
        if(disable_status == "show")
        {
          var explode = result.split(",");
          $.each(explode, function(index,value) {
            $("#disable_"+value).parents(".main_li").hide();
          });
        }
      }
    }
  })
}
if($(e.target).hasClass('show_hide_clients'))
{
  $("body").addClass("loading");
  var status = $(e.target).attr("data-element");
  $.ajax({
    url:"<?php echo URL::to('user/update_paye_p30_year_email_clients_status'); ?>",
    type:"post",
    data:{year:"<?php echo $year->year_id; ?>",status:status},
    success: function(result)
    {
      if(status == "hide")
      {
        $(e.target).attr("data-element","show");
        $(e.target).html("Show Clients");

        var explode = result.split(",");
        $.each(explode, function(index,value) {
          $("#disable_"+value).parents(".main_li").hide();
        });
      }
      else{
        $(e.target).attr("data-element","hide");
        $(e.target).html("Hide Clients");
        $(".disable_clients").parents(".main_li").show();

        var disable_status = $(".show_hide_disable").attr("data-element");
        if(disable_status == "show")
        {
          $(".disable_clients:checked").parents(".main_li").hide();
        }
      }
      $("body").removeClass("loading");
    }
  })
}
if($(e.target).hasClass("arrow_right_scroll"))
{
  $('body,html').animate({
        scrollTop : 0                       // Scroll to top of body
    }, 500);
}
if($(e.target).hasClass('email_unsent'))
{
  $("body").addClass("loading");
  if (CKEDITOR.instances.editor_1) CKEDITOR.instances.editor_1.destroy();
    CKEDITOR.replace('editor_1',
             {
              height: '150px',
             }); 
  setTimeout(function() {
          var task_id = $(e.target).attr('data-value');
          var month_id = $(e.target).attr('data-element');
          $.ajax({
            url:'<?php echo URL::to('user/paye_p30_edit_email_unsent_files'); ?>',
            type:'get',
            data:{task_id:task_id,month_id:month_id},
            dataType:"json",
            success: function(result)
            {
                $(".subject_unsent").val(result['subject']);
                $("#to_user").val(result['to']);
                $("#from_user").val(result['from']);
                $(".emailunsent").modal('show');
                $("#hidden_email_task_id").val(task_id+'-'+month_id);
                CKEDITOR.instances['editor_1'].setData(result['html']);
            }
          })
      $("body").removeClass("loading");
  },7000);
}
if($(e.target).hasClass('email_unsent_button'))
{
  if($("#emailunsent_form").valid())
  {
    $("body").addClass("loading");
    var content = CKEDITOR.instances['editor_1'].getData();
    var to = $("#to_user").val();
    var from = $("#from_user").val();
    var subject = $(".subject_unsent").val();
    var task_id = $("#hidden_email_task_id").val();
    var cc = $("#cc_unsent").val();

    $.ajax({
      url:"<?php echo URL::to('user/paye_p30_email_unsent_files'); ?>",
      type:"post",
      data:{task_id:task_id,from:from,to:to,subject:subject,content:content,cc:cc},
      success: function(result)
      {
        $(".emailunsent").modal('hide');
        if(result == "0")
        {
          $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>Email Field is empty so email is not sent</p>", fixed:true});
        }
        else{
          var monthtaskid = task_id.split('-');
          var split_task_id = monthtaskid[0];
          var split_month_id = monthtaskid[1];
          $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>Email sent Successfully</p>", fixed:true});
          $(".email_unsent_"+task_id).parents("td").html("<a href='javascript:' class='fa fa-envelope email_unsent email_unsent_"+task_id+"' data-element='"+split_month_id+"' data-value='"+split_task_id+"'></a><br>"+result);
        }
        $("body").removeClass("loading");
      }
    });
  }
}
if($(e.target).hasClass("month_class")){
    $("body").addClass("loading");
    var month_id = $(e.target).val(); 
    var task_id = $(e.target).attr("data-element");
    $(".payetask_"+task_id).val(month_id);

    $.ajax({
        url:"<?php echo URL::to('user/paye_p30_single_month'); ?>",
        type:"post",
        dataType:"json",
        data:{month_id:month_id, task_id:task_id},
        success: function(result){
            $("body").removeClass("loading"); 
        }
    })

}

if($(e.target).hasClass("apply_class")){
    var week_from = $(".week_from").val(); 
    var week_to = $(".week_to").val();
    var month_from = $(".month_from").val();
    var month_to = $(".month_to").val();
    var active_month = $(".active_month").val();
    
    if(week_from == "" && week_to == "" && month_from == "" && month_to == "")
    {
        $("#alert_modal").modal("show");
        $("#alert_content").html("Please choose the Week and Month from the dropdown and proceed to apply");
    }
    else if(week_from != "" && week_to == "")
    {
        $("#alert_modal").modal("show");
        $("#alert_content").html("Please choose the Week To option from the dropdown and proceed to apply");
    }
    else if(week_to != "" && week_from == "")
    {
        $("#alert_modal").modal("show");
        $("#alert_content").html("Please choose the Week From option from the dropdown and proceed to apply");
    }
    else if(month_from != "" && month_to == "")
    {
        $("#alert_modal").modal("show");
        $("#alert_content").html("Please choose the Month To option from the dropdown and proceed to apply");
    }
    else if(month_to != "" && month_from == "")
    {
        $("#alert_modal").modal("show");
        $("#alert_content").html("Please choose the Month From option from the dropdown and proceed to apply");
    }
    else if(active_month == "")
    {
        $("#alert_modal").modal("show");
        $("#alert_content").html("Please choose the Active Month option from the dropdown and proceed to apply");
    }
    else{
        if(month_from == 1) { var month_from_name = 'January'; }
        if(month_from == 2) { var month_from_name = 'February'; }
        if(month_from == 3) { var month_from_name = 'March'; }
        if(month_from == 4) { var month_from_name = 'April'; }
        if(month_from == 5) { var month_from_name = 'May'; }
        if(month_from == 6) { var month_from_name = 'June'; }
        if(month_from == 7) { var month_from_name = 'July'; }
        if(month_from == 8) { var month_from_name = 'August'; }
        if(month_from == 9) { var month_from_name = 'September'; }
        if(month_from == 10) { var month_from_name = 'October'; }
        if(month_from == 11) { var month_from_name = 'November'; }
        if(month_from == 12) { var month_from_name = 'December'; }

        if(month_to == 1) { var month_to_name = 'January'; }
        if(month_to == 2) { var month_to_name = 'February'; }
        if(month_to == 3) { var month_to_name = 'March'; }
        if(month_to == 4) { var month_to_name = 'April'; }
        if(month_to == 5) { var month_to_name = 'May'; }
        if(month_to == 6) { var month_to_name = 'June'; }
        if(month_to == 7) { var month_to_name = 'July'; }
        if(month_to == 8) { var month_to_name = 'August'; }
        if(month_to == 9) { var month_to_name = 'September'; }
        if(month_to == 10) { var month_to_name = 'October'; }
        if(month_to == 11) { var month_to_name = 'November'; }
        if(month_to == 12) { var month_to_name = 'December'; }

        if(active_month == 1) { var active_month_name = 'January'; }
        if(active_month == 2) { var active_month_name = 'February'; }
        if(active_month == 3) { var active_month_name = 'March'; }
        if(active_month == 4) { var active_month_name = 'April'; }
        if(active_month == 5) { var active_month_name = 'May'; }
        if(active_month == 6) { var active_month_name = 'June'; }
        if(active_month == 7) { var active_month_name = 'July'; }
        if(active_month == 8) { var active_month_name = 'August'; }
        if(active_month == 9) { var active_month_name = 'September'; }
        if(active_month == 10) { var active_month_name = 'October'; }
        if(active_month == 11) { var active_month_name = 'November'; }
        if(active_month == 12) { var active_month_name = 'December'; }

        if(week_to != "" && week_from != "" && month_to != "" && month_from != "")
        {
          $("#confirm_modal").modal("show");
          $("#confirm_content").html("You are about to Apply all unallocated PREM charges to weeks "+week_from+" to "+week_to+" and month "+month_from_name+" to "+month_to_name+" to the currently active month which is : "+active_month_name+" Do you want to continue?");
        }
        else if(week_to != "" && week_from != "")
        {
          $("#confirm_modal").modal("show");
          $("#confirm_content").html("You are about to Apply all unallocated PREM charges to weeks "+week_from+" to "+week_to+" to the currently active month which is : "+active_month_name+" Do you want to continue?");
        }
        else if(month_to != "" && month_from != "")
        {
           $("#confirm_modal").modal("show");
          $("#confirm_content").html("You are about to Apply all unallocated PREM charges to month "+month_from_name+" to "+month_to_name+" to the currently active month which is : "+active_month_name+" Do you want to continue?");
        }
    }    
}
if($(e.target).hasClass('yes_hit'))
{
  var week_from = $(".week_from").val(); 
  var week_to = $(".week_to").val();
  var month_from = $(".month_from").val();
  var month_to = $(".month_to").val();
  var active_month = $(".active_month").val();
  var active = $(".active_month").val();
  $(".active_month_class").val(active);
  $(".month_class_"+active).prop("checked", true);
  $("body").addClass("loading");
  var year_id = $(".year_id").val();
  $.ajax({
      url:"<?php echo URL::to('user/paye_p30_apply'); ?>",
      type:"post",
      dataType:"json",
      data:{week_from:week_from, week_to:week_to, month_from:month_from, month_to:month_to, active_month:active_month, year_id:year_id  },
      success: function(result){
          window.location.replace("<?php echo URL::to('user/paye_p30_manage/'.base64_encode($year->year_id)); ?>?status=apply");
      }
  });
}
if($(e.target).hasClass('no_hit'))
{
  $("#confirm_modal").modal("hide");
}
if($(e.target).hasClass("task_class_colum")){
    $("body").addClass("loading");
    var task_id = $(e.target).attr("value"); 
    var week = $(e.target).attr("data-element");
    var month_id = $(".payetask_"+task_id).val();
    var year_id = $(".year_id").val();
    $.ajax({
      url:"<?php echo URL::to('user/paye_p30_periods_update'); ?>",
      type:"post",
      dataType:"json",
      data:{task_id:task_id, week:week, month_id:month_id, year_id:year_id  },
      success: function(result){

         $(".week"+result['week']+"_class_"+task_id+'-'+month_id).html(result['value']);
         $(".week"+result['week']+"_class_"+task_id+'-'+month_id).css({"text-align":"right"});

        $(".month_row_"+task_id+'-'+month_id).find(".liability_class").val(result['task_liability']);
        $(".month_row_"+task_id+'-'+month_id).find(".diff_class").val(result['different']);    
        var ros_total = 0;
        var task_total = 0;
        var diff_total = 0;
        var payment_total = 0;
        $(e.target).parents('.table_paye_p30').find(".ros_class").each(function() {
            var str = $(this).val();
            var value = str.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            var rosvalue = parseFloat(value);
            if (!isNaN(rosvalue)) {
                ros_total += rosvalue;
            }
        });
        $(e.target).parents('.table_paye_p30').find(".liability_class").each(function() {
            var str = $(this).val();
            var value = str.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            var taskvalue = parseFloat(value);
            if (!isNaN(taskvalue)) {
                task_total += taskvalue;
            }
        });
        $(e.target).parents('.table_paye_p30').find(".diff_class").each(function() {
            var str = $(this).val();
            var value = str.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            var diffvalue = parseFloat(value);
            if (!isNaN(diffvalue)) {
                diff_total += diffvalue;
            }
        });
        $(e.target).parents('.table_paye_p30').find(".payment_class").each(function() {
            var str = $(this).val();
            var value = str.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            var paymentvalue = parseFloat(value);
            if (!isNaN(paymentvalue)) {
                payment_total += paymentvalue;
            }
        });
        $(e.target).parents('.table_paye_p30').find(".total_ros_class").val(ros_total.toFixed(2));
        $(e.target).parents('.table_paye_p30').find(".total_liability_class").val(task_total.toFixed(2));
        $(e.target).parents('.table_paye_p30').find(".total_diff_class").val(diff_total.toFixed(2));
        $(e.target).parents('.table_paye_p30').find(".total_payment_class").val(payment_total.toFixed(2));    
        $(e.target).removeClass();        
        $(e.target).addClass("payp30_red");
        $("body").removeClass("loading");                
      }
  })
}
if($(e.target).hasClass("task_class_colum_month")){
    $("body").addClass("loading");
    var task_id = $(e.target).attr("value"); 
    var month = $(e.target).attr("data-element");
    var month_id = $(".payetask_"+task_id).val();
    var year_id = $(".year_id").val();
    $.ajax({
      url:"<?php echo URL::to('user/paye_p30_periods_month_update'); ?>",
      type:"post",
      dataType:"json",
      data:{task_id:task_id, month:month, month_id:month_id, year_id:year_id  },
      success: function(result){
         $(".month"+result['month']+"_class_"+task_id+'-'+month_id).html(result['value']);
         $(".month"+result['month']+"_class_"+task_id+'-'+month_id).css({"text-align":"right"});

        $(".month_row_"+task_id+'-'+month_id).find(".liability_class").val(result['task_liability']);
        $(".month_row_"+task_id+'-'+month_id).find(".diff_class").val(result['different']);   

        var ros_total = 0;
        var task_total = 0;
        var diff_total = 0;
        var payment_total = 0;
        $(e.target).parents('.table_paye_p30').find(".ros_class").each(function() {
            var str = $(this).val();
            var value = str.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            var rosvalue = parseFloat(value);
            if (!isNaN(rosvalue)) {
                ros_total += rosvalue;
            }
        });
        $(e.target).parents('.table_paye_p30').find(".liability_class").each(function() {
            var str = $(this).val();
            var value = str.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            var taskvalue = parseFloat(value);
            if (!isNaN(taskvalue)) {
                task_total += taskvalue;
            }
        });
        $(e.target).parents('.table_paye_p30').find(".diff_class").each(function() {
            var str = $(this).val();
            var value = str.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            var diffvalue = parseFloat(value);
            if (!isNaN(diffvalue)) {
                diff_total += diffvalue;
            }
        });
        $(e.target).parents('.table_paye_p30').find(".payment_class").each(function() {
            var str = $(this).val();
            var value = str.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            var paymentvalue = parseFloat(value);
            if (!isNaN(paymentvalue)) {
                payment_total += paymentvalue;
            }
        });
        $(e.target).parents('.table_paye_p30').find(".total_ros_class").val(ros_total.toFixed(2));
        $(e.target).parents('.table_paye_p30').find(".total_liability_class").val(task_total.toFixed(2));
        $(e.target).parents('.table_paye_p30').find(".total_diff_class").val(diff_total.toFixed(2));
        $(e.target).parents('.table_paye_p30').find(".total_payment_class").val(payment_total.toFixed(2));     

        $(e.target).removeClass();        
        $(e.target).addClass("payp30_red");
        $("body").removeClass("loading");                
      }
  })
}
if($(e.target).hasClass("week_remove")){
    $("body").addClass("loading");
    var task_id = $(e.target).attr("data-value"); 
    var period = $(e.target).attr("value"); 
    var week = $(e.target).attr("data-element");    
    $.ajax({
      url:"<?php echo URL::to('user/paye_p30_periods_remove'); ?>",
      type:"post",
      dataType:"json",
      data:{task_id:task_id,period:period,week:week},
      success: function(result){
        $(e.target).parents("tr").find(".liability_class").val(result['task_liability']);
        $(e.target).parents("tr").find(".diff_class").val(result['different']);
        $(".week"+week+"_class_"+task_id+'-'+period).html(result['value']);
        $(".week"+week+"_class_"+task_id+'-'+period).css({"text-align":"center"});
        $(".task_row_"+task_id).find(".week"+week).find(".payp30_red").addClass("payp30_black task_class_colum ");
        $(".task_row_"+task_id).find(".week"+week).find(".payp30_red").removeClass("payp30_red");
        if($(".task_row_"+task_id).find(".week"+week).find(".blueinfo").length > 0)
        {
            var changed_val = $(".task_row_"+task_id).find(".week"+week).find(".blueinfo").attr("data-element");
            if(changed_val == "-")
            {
              $(".task_row_"+task_id).find(".week"+week).html('<div class="payp30_dash">-</div>');
            }
            else if(changed_val == ""){
              $(".task_row_"+task_id).find(".week"+week).html('<div class="payp30_dash">-</div>');
            }
            else if(changed_val == "0"){
              $(".task_row_"+task_id).find(".week"+week).html('<div class="payp30_dash">-</div>');
            }
            else{
              $(".task_row_"+task_id).find(".week"+week).find("a").html(changed_val);
            }
            
        }
        var ros_total = 0;
        var task_total = 0;
        var diff_total = 0;
        var payment_total = 0;
        $(e.target).parents('.table_paye_p30').find(".ros_class").each(function() {
            var str = $(this).val();
            var value = str.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            var rosvalue = parseFloat(value);
            if (!isNaN(rosvalue)) {
                ros_total += rosvalue;
            }
        });
        $(e.target).parents('.table_paye_p30').find(".liability_class").each(function() {
            var str = $(this).val();
            var value = str.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            var taskvalue = parseFloat(value);
            if (!isNaN(taskvalue)) {
                task_total += taskvalue;
            }
        });
        $(e.target).parents('.table_paye_p30').find(".diff_class").each(function() {
            var str = $(this).val();
            var value = str.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            var diffvalue = parseFloat(value);
            if (!isNaN(diffvalue)) {
                diff_total += diffvalue;
            }
        });
        $(e.target).parents('.table_paye_p30').find(".payment_class").each(function() {
            var str = $(this).val();
            var value = str.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            var paymentvalue = parseFloat(value);
            if (!isNaN(paymentvalue)) {
                payment_total += paymentvalue;
            }
        });
        $(e.target).parents('.table_paye_p30').find(".total_ros_class").val(ros_total.toFixed(2));
        $(e.target).parents('.table_paye_p30').find(".total_liability_class").val(task_total.toFixed(2));
        $(e.target).parents('.table_paye_p30').find(".total_diff_class").val(diff_total.toFixed(2));
        $(e.target).parents('.table_paye_p30').find(".total_payment_class").val(payment_total.toFixed(2));
        $(e.target).removeClass("payp30_green");        
        $(e.target).addClass("payp30_dash");
        $("body").removeClass("loading");                
      }
  })
}
if($(e.target).hasClass("month_remove")){
    $("body").addClass("loading");
    var task_id = $(e.target).attr("data-value"); 
    var period = $(e.target).attr("value");
    var month = $(e.target).attr("data-element");    
    $.ajax({
      url:"<?php echo URL::to('user/paye_p30_periods_month_remove'); ?>",
      type:"post",
      dataType:"json",
      data:{task_id:task_id,period:period,month:month},
      success: function(result){
        $(e.target).parents("tr").find(".liability_class").val(result['task_liability']);
        $(e.target).parents("tr").find(".diff_class").val(result['different']);
        $(".month"+month+"_class_"+task_id+'-'+period).html(result['value']);
        $(".month"+month+"_class_"+task_id+'-'+period).css({"text-align":"center"});
        $(".task_row_"+task_id).find(".month"+month).find(".payp30_red").addClass("payp30_black task_class_colum ");
        $(".task_row_"+task_id).find(".month"+month).find(".payp30_red").removeClass("payp30_red");
        if($(".task_row_"+task_id).find(".month"+month).find(".blueinfo").length > 0)
        {
            var changed_val = $(".task_row_"+task_id).find(".month"+month).find(".blueinfo").attr("data-element");
            if(changed_val == "-")
            {
              $(".task_row_"+task_id).find(".month"+month).html('<div class="payp30_dash">-</div>');
            }
            else if(changed_val == ""){
              $(".task_row_"+task_id).find(".month"+month).html('<div class="payp30_dash">-</div>');
            }
            else if(changed_val == "0"){
              $(".task_row_"+task_id).find(".month"+month).html('<div class="payp30_dash">-</div>');
            }
            else{
              $(".task_row_"+task_id).find(".month"+month).find("a").html(changed_val);
            }
        }
        var ros_total = 0;
        var task_total = 0;
        var diff_total = 0;
        var payment_total = 0;
        $(e.target).parents('.table_paye_p30').find(".ros_class").each(function() {
            var str = $(this).val();
            var value = str.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            var rosvalue = parseFloat(value);
            if (!isNaN(rosvalue)) {
                ros_total += rosvalue;
            }
        });
        $(e.target).parents('.table_paye_p30').find(".liability_class").each(function() {
            var str = $(this).val();
            var value = str.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            var taskvalue = parseFloat(value);
            if (!isNaN(taskvalue)) {
                task_total += taskvalue;
            }
        });
        $(e.target).parents('.table_paye_p30').find(".diff_class").each(function() {
            var str = $(this).val();
            var value = str.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            var diffvalue = parseFloat(value);
            if (!isNaN(diffvalue)) {
                diff_total += diffvalue;
            }
        });
        $(e.target).parents('.table_paye_p30').find(".payment_class").each(function() {
            var str = $(this).val();
            var value = str.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            value = value.replace(",","");
            var paymentvalue = parseFloat(value);
            if (!isNaN(paymentvalue)) {
                payment_total += paymentvalue;
            }
        });
        $(e.target).parents('.table_paye_p30').find(".total_ros_class").val(ros_total.toFixed(2));
        $(e.target).parents('.table_paye_p30').find(".total_liability_class").val(task_total.toFixed(2));
        $(e.target).parents('.table_paye_p30').find(".total_diff_class").val(diff_total.toFixed(2));
        $(e.target).parents('.table_paye_p30').find(".total_payment_class").val(payment_total.toFixed(2));
        $(e.target).removeClass("payp30_green");        
        $(e.target).addClass("payp30_dash");
        $("body").removeClass("loading");                
      }
  })
}
if($(e.target).hasClass('refresh_liability'))
{
  var loading_status = $("#hidden_loading_status").val();
  if(loading_status == "")
  {
    $("body").addClass("loading");  
  }
  var task_id = $(e.target).attr("data-element");
  var year_id = $(".year_id").val();
  $.ajax({
    url:"<?php echo URL::to('user/refresh_paye_p30_liability'); ?>",
    type:"get",
    dataType:"json",
    data:{task_id:task_id, year_id:year_id},
    success: function(result){
      if(result['update'] == 0)
      {

      }
      else{
        for(var $i=1; $i<=53; $i++)
        {
          if(jQuery.inArray($i, result['changed_liability_week']) !== -1) { 
            if($(e.target).parents("tr").find(".week"+$i).find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week"+$i).find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week'+$i]+'" title="Liability Value ('+result['week'+$i]+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); 
            } 
          } 
          else { $(e.target).parents("tr").find(".week"+$i).find("a").html(result['week'+$i]); $(e.target).parents("tr").find(".week"+$i).find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="'+$i+'">'+result['week'+$i]+'</a>'); 
          }
        }
        for(var $mn=1; $mn<=12; $mn++)
        {
          if(jQuery.inArray($mn, result['changed_liability_month']) !== -1) { 
            if($(e.target).parents("tr").find(".month"+$mn).find(".blueinfo").length == 0) { 
              $(e.target).parents("tr").find(".month"+$mn).find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['month'+$mn]+'" title="Liability Value ('+result['month'+$mn]+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); 
            } 
          } 
          else { $(e.target).parents("tr").find(".month"+$mn).find("a").html(result['month'+$mn]); $(e.target).parents("tr").find(".month"+$mn).find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum_month" value="'+result['payep30_task']+'" data-element="'+$mn+'">'+result['month'+$mn]+'</a>'); 
          }
        }
      }
      if(loading_status == "")
      {
        $("body").removeClass("loading");
      }
    }
  });
}
})
$(".active_month").change(function(){
    $("body").addClass("loading");
    var active = $(this).val();
    $(".active_month_class").val(active);
    $(".month_class_"+active).prop("checked", true);
     $.ajax({
        url:"<?php echo URL::to('user/paye_p30_all_month'); ?>",
        type:"post",
        dataType:"json",
        data:{active:active,year:"<?php echo $year->year_id; ?>"},
        success: function(result){
            $("body").removeClass("loading"); 
        }
    })
    
});
$(".week_from").change(function(){
    var from = $(this).val();
     $.ajax({
        url:"<?php echo URL::to('user/paye_p30_week_selected'); ?>",
        type:"post",
        data:{value:from,status:"from",year:"<?php echo $year->year_id; ?>"},
        success: function(result){
        }
    })
    
});
$(".week_to").change(function(){
    var to = $(this).val();
     $.ajax({
        url:"<?php echo URL::to('user/paye_p30_week_selected'); ?>",
        type:"post",
        data:{value:to,status:"to",year:"<?php echo $year->year_id; ?>"},
        success: function(result){
        }
    })
    
});
$(".month_from").change(function(){
    var from = $(this).val();
     $.ajax({
        url:"<?php echo URL::to('user/paye_p30_month_selected'); ?>",
        type:"post",
        data:{value:from,status:"from",year:"<?php echo $year->year_id; ?>"},
        success: function(result){
        }
    })
    
});
$(".month_to").change(function(){
    var to = $(this).val();
     $.ajax({
        url:"<?php echo URL::to('user/paye_p30_month_selected'); ?>",
        type:"post",
        data:{value:to,status:"to",year:"<?php echo $year->year_id; ?>"},
        success: function(result){
        }
    })
    
});
$(window).keyup(function(e) {
    var valueTimmer;                //timer identifier
    var valueInterval = 500;  //time in ms, 5 second for example
    var $ros_value = $('.ros_class');
    var $payment_value = $('.payment_class');
    if($(e.target).hasClass('ros_class'))
    {
        var that = $(e.target);
        var input_val = $(e.target).val();  
        var period_id = $(e.target).attr("data-element");
        var task_id = $(e.target).attr("data-value");
        clearTimeout(valueTimmer);
        valueTimmer = setTimeout(doneTyping, valueInterval,input_val, period_id,task_id,that);   
    }
    if($(e.target).hasClass('payment_class'))
    {
        var that = $(e.target);
        var input_val = $(e.target).val();  
        var period_id = $(e.target).attr("data-element");
        var task_id = $(e.target).attr("data-value");
        clearTimeout(valueTimmer);
        valueTimmer = setTimeout(doneTypingpayment, valueInterval,input_val, period_id,task_id,that);   
    }
});
function doneTyping (ros_value,period_id,task_id,that) {
  $.ajax({
        url:"<?php echo URL::to('user/paye_p30_ros_update')?>",
        type:"post",
        dataType:"json",
        data:{value:ros_value, period_id:period_id,task_id:task_id},
        success: function(result) {            
            $(".month_row_"+task_id+'-'+period_id).find(".diff_class").val(result['different']);

            var ros_total = 0;
            var task_total = 0;
            var diff_total = 0;
            var payment_total = 0;
            that.parents('.table_paye_p30').find(".ros_class").each(function() {
                var str = $(this).val();
                var value = str.replace(",","");
                value = value.replace(",","");
                value = value.replace(",","");
                value = value.replace(",","");
                value = value.replace(",","");
                var rosvalue = parseFloat(value);
                if (!isNaN(rosvalue)) {
                    ros_total += rosvalue;
                }
            });
            that.parents('.table_paye_p30').find(".liability_class").each(function() {
                var str = $(this).val();
                var value = str.replace(",","");
                value = value.replace(",","");
                value = value.replace(",","");
                value = value.replace(",","");
                value = value.replace(",","");
                var taskvalue = parseFloat(value);
                if (!isNaN(taskvalue)) {
                    task_total += taskvalue;
                }
            });
            that.parents('.table_paye_p30').find(".diff_class").each(function() {
                var str = $(this).val();
                var value = str.replace(",","");
                value = value.replace(",","");
                value = value.replace(",","");
                value = value.replace(",","");
                value = value.replace(",","");
                var diffvalue = parseFloat(value);
                if (!isNaN(diffvalue)) {
                    diff_total += diffvalue;
                }
            });
            that.parents('.table_paye_p30').find(".payment_class").each(function() {
                var str = $(this).val();
                var value = str.replace(",","");
                value = value.replace(",","");
                value = value.replace(",","");
                value = value.replace(",","");
                value = value.replace(",","");
                var paymentvalue = parseFloat(value);
                if (!isNaN(paymentvalue)) {
                    payment_total += paymentvalue;
                }
            });
            
            that.parents('.table_paye_p30').find(".total_ros_class").val(ros_total.toFixed(2));
            that.parents('.table_paye_p30').find(".total_liability_class").val(task_total.toFixed(2));
            that.parents('.table_paye_p30').find(".total_diff_class").val(diff_total.toFixed(2));
            that.parents('.table_paye_p30').find(".total_payment_class").val(payment_total.toFixed(2));           
        }
      });
}
function doneTypingpayment (payment_value,period_id,task_id,that) {
  $.ajax({
        url:"<?php echo URL::to('user/paye_p30_payment_update')?>",
        type:"post",
        dataType:"json",
        data:{value:payment_value, period_id:period_id,task_id:task_id},
        success: function(result) {            
            //$(".month_row_"+result['id']).find(".diff_class").val(result['different']);  
            var ros_total = 0;
            var task_total = 0;
            var diff_total = 0;
            var payment_total = 0;
            that.parents('.table_paye_p30').find(".ros_class").each(function() {
                var str = $(this).val();
                var value = str.replace(",","");
                value = value.replace(",","");
                value = value.replace(",","");
                value = value.replace(",","");
                value = value.replace(",","");
                var rosvalue = parseFloat(value);
                if (!isNaN(rosvalue)) {
                    ros_total += rosvalue;
                }
            });
            that.parents('.table_paye_p30').find(".liability_class").each(function() {
                var str = $(this).val();
                var value = str.replace(",","");
                value = value.replace(",","");
                value = value.replace(",","");
                value = value.replace(",","");
                value = value.replace(",","");
                var taskvalue = parseFloat(value);
                if (!isNaN(taskvalue)) {
                    task_total += taskvalue;
                }
            });
            that.parents('.table_paye_p30').find(".diff_class").each(function() {
                var str = $(this).val();
                var value = str.replace(",","");
                value = value.replace(",","");
                value = value.replace(",","");
                value = value.replace(",","");
                value = value.replace(",","");
                var diffvalue = parseFloat(value);
                if (!isNaN(diffvalue)) {
                    diff_total += diffvalue;
                }
            });
            that.parents('.table_paye_p30').find(".payment_class").each(function() {
                var str = $(this).val();
                var value = str.replace(",","");
                value = value.replace(",","");
                value = value.replace(",","");
                value = value.replace(",","");
                value = value.replace(",","");
                var paymentvalue = parseFloat(value);
                if (!isNaN(paymentvalue)) {
                    payment_total += paymentvalue;
                }
            });
            
            that.parents('.table_paye_p30').find(".total_ros_class").val(ros_total.toFixed(2));
            that.parents('.table_paye_p30').find(".total_liability_class").val(task_total.toFixed(2));
            that.parents('.table_paye_p30').find(".total_diff_class").val(diff_total.toFixed(2));
            that.parents('.table_paye_p30').find(".total_payment_class").val(payment_total.toFixed(2));         
        }
      });
}
$(".ros_class").blur(function(){
    setTimeout(function() {
        var ros_value = $(this).val();  
        var period_id = $(this).attr("data-element");

        $.ajax({
            url:"<?php echo URL::to('user/paye_p30_ros_update')?>",
            type:"post",
            dataType:"json",
            data:{value:ros_value, id:period_id},
            success: function(result) {            
                $(".month_row_"+result['id']).find(".diff_class").val(result['different']);           
            }
        });   
    },1000);
});
</script>
@stop


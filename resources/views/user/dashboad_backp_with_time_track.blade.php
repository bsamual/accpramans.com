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

body{

  background: #f5f5f5 !important;

}

.tasks_drop{border-radius: 0px; background:none;}


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

.modal_load {
    display:    none;
    position:   fixed;
    z-index:    999999;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 ) 
                url(<?php echo URL::to('assets/images/loading.gif'); ?>) 50% 50% no-repeat;

}



.ok_button{background: #000; text-align: center; padding: 6px 12px; color: #fff; float: left; border: 0px; font-size: 13px; }
.ok_button:hover{background: #5f5f5f; text-decoration: none; color: #fff}
.ok_button:focus{background: #000; text-decoration: none; color: #fff}


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
.ui-widget{z-index: 999999999}
</style>
<div class="modal fade create_new_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="margin-top: 5%;">
  <div class="modal-dialog modal-sm" role="document">
    <form action="<?php echo URL::to('user/time_job_add')?>" method="post" class="add_new_form" id="create_job_form">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title"></h4>
          </div>
          <div class="modal-body">
            <div class="form-group">                
                <input  type="checkbox" class="mark_internal" name="internal" id="mark_internal"><label for="mark_internal" style="font-size: 14px; font-weight: normal; cursor: pointer;">Internal Job</label>
                <input type="hidden" class="internal_type" value="1" name="internal_type">
                <input type="hidden" class="user_id" value="" name="user_id">
            </div>
            <div class="form-group client_group">                
                <input  type="text" class="form-control client_search_class" name="client_name" placeholder="Enter Client Name / Client ID" required>
                <input type="hidden" id="client_search" name="clientid" />
            </div>

            <div class="form-group internal_tasks_group" style="display: none;">                
                <div class="dropdown" style="width: 100%">
                <a class="btn btn-default dropdown-toggle tasks_drop" data-target="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="width: 100%">
                  <span class="task-choose_internal">Select Task</span>  <span class="caret"></span>                          
                </a>
                
                <ul class="dropdown-menu internal_task_details" role="menu"  aria-labelledby="dropdownMenu" style="width: 100%">                  
                    <?php
                    if(count($taskslist)){
                      foreach ($taskslist as $single_task) {
                        if($single_task->task_type == 0){
                          $icon = '<i class="fa fa-desktop" style="margin-right:10px;"></i>';
                        }
                        else if($single_task->task_type == 1){
                          $icon = '<i class="fa fa-users" style="margin-right:10px;"></i>';
                        }
                        else{
                          $icon = '<i class="fa fa-globe" style="margin-right:10px;"></i>';
                        }
                    ?>
                      <li><a tabindex="-1" href="javascript:" class="tasks_li_internal" data-element="<?php echo $single_task->id?>"><?php echo $icon.$single_task->task_name?></a></li>
                    <?php
                      }
                    }
                    ?>
                </ul>
              </div>
              
            </div>

<input type="hidden" id="idtask" value="" name="task_id">
            <div class="form-group tasks_group" style="display: none;">                
                <div class="dropdown" style="width: 100%">
                <a class="btn btn-default dropdown-toggle tasks_drop" data-target="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="width: 100%">
                  <span class="task-choose">Select Task</span>  <span class="caret"></span>                          
                </a>
                
                <ul class="dropdown-menu task_details" role="menu"  aria-labelledby="dropdownMenu" style="width: 100%">                  
                  
                </ul>
              </div>
             
            </div>

            <div class="form-group date_group" style="display: none;">
                <label class="input-group datepicker-only-init">
                    <input type="text" class="form-control create_dateclass" placeholder="Select Date" name="date" style="font-weight: 500;" required />
                    <span class="input-group-addon">
                        <i class="glyphicon glyphicon-calendar"></i>
                    </span>
                </label>
            </div>

            <div class="form-group start_group" style="display: none;">
                <div class='input-group date' id='start_time'>
                    <input type='text' class="form-control create_startclass" placeholder="Select Start Time" name="starttime" style="font-weight: 500;" required />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                    </span>
                </div>
            </div>

            <div class="form-group stop_group" style="display: none;">
                <div class='input-group date' id='stop_time'>
                    <input type='text' class="form-control stop_time" placeholder="Select Stop Time" name="stoptime" style="font-weight: 500;" required />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                    </span>
                </div>
            </div>



            <input type="hidden" id="quickjob" value="" name="quick_job">
            <input type="hidden" value="" class="currentdate" name="" name="">
          </div>
          <div class="modal-footer">
            
            <input type="button" class="common_black_button start_button_quick" value="Start" style="display: none;">
            <input type="button" class="common_black_button start_button" value="Start" style="display: none;">
            <input type="submit" class="common_black_button job_button_name" value="" style="display: none;">
          </div>
        </div>
    </form>
  </div>
</div>



<div class="modal fade stop_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="margin-top: 5%;">
  <div class="modal-dialog modal-sm" role="document">
    <form action="<?php echo URL::to('user/time_job_stop')?>" method="post" class="add_new_form">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title">Stop Job</h4>
          </div>
          <div class="modal-body">            

            

            <div class="form-group start_group">
                <div class='input-group date' id='start_time'>
                    <b style="padding-bottom: 10px; float: left;">Job Date:</b>
                    <input class="form-control dateclass" value="" readonly>
                </div>
            </div>

            <div class="form-group">
                <div class='input-group date' id='stop_time1'>
                    <input type='text' class="form-control stop_time1" placeholder="Select Stop Time" name="stoptime" style="font-weight: 500;" required />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                    </span>
                </div>
            </div>

            <div class="form-group">
                <textarea class="form-control comments" name="comments" placeholder="Enter Comments"></textarea>
                <input type="hidden" class="idclass" name="id">
            </div>
            
          </div>
          <div class="modal-footer">
            <input type="submit" class="common_black_button" value="Stop Job">
          </div>
        </div>
    </form>
  </div>
</div>


<div class="modal fade take_break_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="margin-top: 5%;">
  <div class="modal-dialog modal-sm" role="document">
    <form action="<?php echo URL::to('user/job_add_break')?>" method="post" class="add_new_form">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title">Take Break</h4>
          </div>
          <div class="modal-body">            

            

            <div class="form-group">
                <select class="form-control select_break_class" name="breaktime" required>
                  <option value="">Select Time</option>
                  <option value="00:15:00">15 Minutes</option>
                  <option value="00:30:00">30 Minutes</option>
                  <option value="00:45:00">45 Minutes</option>
                  <option value="01:00:00">60 Minutes</option>
                </select>
            </div>

            <input type="hidden" class="id_take_break" name="id">            
            
          </div>
          <div class="modal-footer">
            <input type="submit" class="common_black_button" value="Take Break">
          </div>
        </div>
    </form>
  </div>
</div>


<div class="modal fade break_time_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="margin-top: 5%;">
  <div class="modal-dialog modal-sm" role="document">
    <form action="<?php echo URL::to('user/job_add_break')?>" method="post" class="add_new_form">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title">Break Time</h4>
          </div>
          <div class="modal-body">
              <table class="display nowrap fullviewtablelist" id="break_tbody" width="100%">
                <thead>
                  <tr style="background: #fff;">
                    <th width="2%" style="text-align: left;">S.No</th>
                    <th style="text-align: left;">Break Time</th>
                </tr>
                </thead>
                <tbody class="break_time_details">
                </tbody>
              </table>              
          </div>
          <div class="modal-footer">
            
          </div>
        </div>
    </form>
  </div>
</div>





<div class="content_section">
  <div style="clear: both;">
   <?php
    if(Session::has('message')) { ?>
        <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>

    
    <?php } ?>
    <?php
    if(Session::has('error-message')) { ?>
        <p class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('error-message'); ?></p>

    
    <?php } ?>
    </div>
  <div class="page_title">
    Dashboard
  </div>
    <div class="row">      
      <div class="col-lg-3">
        <div class="dashboard">
          <div class="dashboard_signle cmsystem">
            <div class="content">
              <div class="title">Cm System</div>
              <div class="ul_list">
                <ul>
                <?php 
                $total_clients = DB::table('cm_clients')->count();
                $active_cm_clients = DB::table('cm_clients')->where('active',1)->count();
                ?>
                  <li>Total  Clients : <?php echo $total_clients; ?></li>
                  <li>Active  Clients : <?php echo $active_cm_clients; ?></li>
                </ul>
              </div>
            </div>
            <div class="icon"><img src="<?php echo URL::to('assets/images/icon_cm_system.jpg')?>"></div>            
          </div>
          <div class="more morecmsystem">
                <a href="<?php echo URL::to('user/client_management'); ?>"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
            </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="dashboard">
          <div class="dashboard_signle week">
            <div class="content">
              <div class="title">Weekly Payroll</div>
              <div class="ul_list">
              <?php 
              $current_week = DB::table('week')->orderBy('week_id','desc')->first();
              $current_year = DB::table('year')->where('year_id',$current_week->year)->first();
              $no_of_tasks = DB::table('task')->where('task_week',$current_week->week_id)->count();
              $week_completed = DB::table('task')->where('task_week',$current_week->week_id)->where('task_status',1)->count();
              $week_incompleted = DB::table('task')->where('task_week',$current_week->week_id)->where('task_status',0)->count();
              ?>
                <div class="sub-title">Week #<?php echo $current_week->week; ?>, <?php echo $current_year->year_name; ?> - <?php echo $no_of_tasks; ?> Tasks</div>
                <ul>
                  <li>Completed Tasks : <?php echo $week_completed; ?></li>
                  <li>Incomplete Tasks : <?php echo $week_incompleted; ?></li>
                </ul>
              </div>
            </div>
            <div class="icon"><img src="<?php echo URL::to('assets/images/icon_week_task.jpg')?>"></div>            
          </div>
          <div class="more moreweek">
                <a href="<?php echo URL::to('user/manage_week'); ?>"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
            </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="dashboard">
          <div class="dashboard_signle month">
            <div class="content">
              <div class="title">Monthly Payroll</div>
              <?php 
              $current_month = DB::table('month')->orderBy('month_id','desc')->first();
              $current_year = DB::table('year')->where('year_id',$current_month->year)->first();
              $no_of_tasks_month = DB::table('task')->where('task_month',$current_month->month_id)->count();
              $week_completed_month = DB::table('task')->where('task_month',$current_month->month_id)->where('task_status',1)->count();
              $week_incompleted_month = DB::table('task')->where('task_month',$current_month->month_id)->where('task_status',0)->count();
              ?>
              <div class="ul_list">
                <div class="sub-title">Month #<?php echo $current_month->month; ?>, <?php echo $current_year->year_name; ?> - <?php echo $no_of_tasks_month; ?> Tasks</div>
                <ul>
                  <li>Completed Tasks : <?php echo $week_completed_month; ?></li>
                  <li>Incomplete Tasks : <?php echo $week_incompleted_month; ?></li>
                </ul>
              </div>
            </div>
            <div class="icon"><img src="<?php echo URL::to('assets/images/icon_month_task.jpg')?>"></div>            
          </div>
          <div class="more moremonth">
                <a href="<?php echo URL::to('user/manage_month'); ?>"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
            </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="dashboard">
          <div class="dashboard_signle p30">
            <div class="content">
              <div class="title">P30 system</div>
              <div class="ul_list">
              <?php 
              $current_p30_month = DB::table('p30_month')->orderBy('month_id','desc')->first();
              $current_year = DB::table('year')->where('year_id',$current_p30_month->year)->first();
              $no_of_tasks_p30_month = DB::table('p30_task')->where('task_month',$current_p30_month->month_id)->count();
              $week_completed_p30_month = DB::table('p30_task')->where('task_month',$current_p30_month->month_id)->where('task_status',1)->count();
              $week_incompleted_p30_month = DB::table('p30_task')->where('task_month',$current_p30_month->month_id)->where('task_status',0)->count();
              ?>
                <div class="sub-title">Month #<?php echo $current_p30_month->month; ?>, <?php echo $current_year->year_name; ?> - <?php echo $no_of_tasks_p30_month; ?> Tasks</div>
                <ul>
                  <li>Completed Tasks : <?php echo $week_completed_p30_month; ?></li>
                  <li>Incomplete Tasks : <?php echo $week_incompleted_p30_month; ?></li>
                </ul>
              </div>
            </div>
            <div class="icon"><img src="<?php echo URL::to('assets/images/icon_p30.jpg')?>"></div>            
          </div>
          <div class="more morep30">
                <a href="<?php echo URL::to('user/p30'); ?>"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
            </div>
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="col-lg-3">
        <div class="dashboard">
          <div class="dashboard_signle vat">
            <div class="content">
              <div class="title">VAT system</div>
              <?php 
              $disabled_clients = DB::table('vat_clients')->where('status',1)->count();
              $clients_email = DB::table('vat_clients')->where('status',0)->where('pemail','!=', '')->where('self_manage','no')->count();
              $clients_without_email = DB::table('vat_clients')->where('status',0)->where('pemail', '')->where('self_manage','no')->count();
              $self_manage = DB::table('vat_clients')->where('status',0)->where('self_manage','yes')->count();
              ?>
              <div class="ul_list">                
                <ul>
                  <li>Disabled Clients : <?php echo $disabled_clients; ?></li>
                  <li>Clients With Email : <?php echo $clients_email; ?></li>
                  <li>Clients Without Email: <?php echo $clients_without_email; ?></li>
                  <li>Self Managed  : <?php echo $self_manage; ?></li>
                </ul>
              </div>
            </div>
            <div class="icon"><img src="<?php echo URL::to('assets/images/icon_vat.jpg')?>"></div>            
          </div>
          <div class="more morevat">
                <a href="<?php echo URL::to('user/vat_clients'); ?>"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
            </div>
        </div>
      </div>
      <div class="col-lg-12">
        <h3 style="font-weight: 700">Track Time</h3>
        <h4 class="col-lg-2" style="padding: 0px; font-weight: 600">Active Job</h4>

        <div class="col-lg-2">

          <select class="form-control" id="user_select">
            <option value="">Select User</option>        
            <?php
            if(count($userlist)){
              foreach ($userlist as $user) {
            ?>
              <option value="<?php echo $user->user_id ?>"><?php echo $user->firstname?></option>
            <?php
              }
            }
            ?>
          </select>          
        </div>
        <div class="col-lg-3" style="padding: 0px;">
          <a href="javascript:" class="ok_button create_new" style="line-height:20px; margin-right: 5px;">Create an Active Job</a>
          <a href="javascript:" class="ok_button create_new_quick" style="line-height:20px;">Create a Quick Break  </a>
        </div>

        <div class="clearfix" style="margin-bottom: 20px;"></div>

        <table class="display nowrap fullviewtablelist" id="active_job" width="100%">
          <thead>
            <tr style="background: #fff;">
              <th width="2%" style="text-align: left;">S.No</th>
              <th style="text-align: left;">Client Name</th>
              <th style="text-align: left;">Task Name</th>
              <th style="text-align: left;">Task Type</th>
              <th style="text-align: left;">Start Time</th>
              <th style="text-align: left;">Job Time</th>
              
              <th style="text-align: center;">Action</th>
          </tr>
          </thead>
          <tbody id="tbody_active">
            <?php
            $output='';
            $i=1;            
            if(count($joblist)){              
              foreach ($joblist as $jobs) {
                if($jobs->quick_job == 0){
                  if($jobs->status == 0){
                    $client_details = DB::table('cm_clients')->where('client_id', $jobs->client_id)->first();
                    if(count($client_details) != ''){
                      $client_name = $client_details->company;
                    }
                    else{
                      $client_name = 'N/A';
                    }

                    $task_details = DB::table('time_task')->where('id', $jobs->task_id)->first();

                    if(count($task_details) != ''){
                      $task_name = $task_details->task_name;
                      $task_type = $task_details->task_type;

                      if($task_type == 0){
                        $task_type = '<i class="fa fa-desktop" style="margin-right:10px;"></i> Internal Task';
                      }
                      else if($task_type == 1){
                        $task_type = '<i class="fa fa-users" style="margin-right:10px;"></i> Client Task';
                      }
                      else{
                        $task_type = '<i class="fa fa-globe" style="margin-right:10px;"></i> Globel Task';
                      }
                    }
                    else{
                      $task_name = 'N/A';
                      $task_type = 'N/A';
                    }

                    $break_time_count = DB::table('job_break_time')->where('job_id', $jobs->id)->get();
                                        
                    $count_minues = 0;
                    if(count($break_time_count)){
                      foreach ($break_time_count as $break_time1) {
                        if($break_time1->break_time == "01:00:00") { $minval = 60; }
                        elseif($break_time1->break_time == "00:45:00") { $minval = 45; }
                        elseif($break_time1->break_time == "00:30:00") { $minval = 30; }
                        elseif($break_time1->break_time == "00:15:00") { $minval = 15; }
                        if($count_minues == 0)
                        {
                          $count_minues = $minval;
                        }
                        else{
                          $count_minues = $count_minues + $minval;
                        }
                        
                      }
                    }

                    if($count_minues == 0)
                    {
                      $break_hours = '';
                    }
                    elseif($count_minues < 60)
                    {
                      $break_hours = $count_minues.' Minutes';
                    }
                    elseif($count_minues == 60)
                    {
                      $break_hours = '1 Hour';
                    }
                    else{
                      if(floor($count_minues / 60) <= 9)
                      {
                        $h = floor($count_minues / 60);
                      }
                      else{
                        $h = floor($count_minues / 60);
                      }
                      if(($count_minues -   floor($count_minues / 60) * 60) <= 9)
                      {
                        $m = ($count_minues -   floor($count_minues / 60) * 60);
                      }
                      else{
                        $m = ($count_minues -   floor($count_minues / 60) * 60);
                      }
                      if($m == "00")
                      {
                        $break_hours = $h.' Hours';
                      }
                      else{
                        $break_hours = $h.':'.$m.' Hours';
                      }
                      
                    }



                    //-----------Job Time Start----------------
                    $created_date = $jobs->job_created_date;

                    $jobstart = strtotime($created_date.' '.$jobs->start_time);
                    $jobend   = strtotime($created_date.' '.date('H:i:s'));
                    

                    if($jobend < $jobstart)
                    {
                      $todate = date('Y-m-d', strtotime("+1 day", $jobend));
                      $jobend   = strtotime($todate.' '.date('H:i:s'));
                    }

                    $jobdiff  = $jobend - $jobstart;
                    //$todate = date('Y-m-d', strtotime("+1 day", strtotime($jobstart)));



                    $hours = floor($jobdiff / (60 * 60));
                    $minutes = $jobdiff - $hours * (60 * 60);
                    $minutes = floor( $minutes / 60 );
                    $second = round((((($jobdiff % 604800) % 86400) % 3600) % 60));
                    if($hours <= 9)
                    {
                      $hours = '0'.$hours;
                    }
                    else{
                      $hours = $hours;
                    }
                    if($minutes <= 9)
                    {
                      $minutes = '0'.$minutes;
                    }
                    else{
                      $minutes = $minutes;
                    }
                    if($second <= 9)
                    {
                      $second = '0'.$second;
                    }
                    else{
                      $second = $second;
                    }

                    $jobtime =   $hours.':'.$minutes.':'.$second;

                    //-----------Job Time End----------------


                    

            $output.='
            <tr>
              <td align="left">'.$i.'</td>
              <td align="left">'.$client_name.'</td>
              <td align="left">'.$task_name.'</td>
              <td align="left">'.$task_type.'</td>
              <td align="left">'.$data['start_time'] = date('h:i A', strtotime($jobs->start_time)).'</td>
              <td align="left">
              <span id="job_time_refresh_'.$jobs->id.'">'.$jobtime.'</span> &nbsp;&nbsp;<a href="javascript:"><i class="fa fa-refresh job_time_refresh" aria-hidden="true" data-element="'.$jobs->id.'"></i></a>

              </td>
              
              <td align="center"><a href="javascript:" class="stop_class" data-element="'.$jobs->id.'">Stop</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript:" class="take_break_class" data-element="'.$jobs->id.'">Take Break</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript:" class="break_time_class" data-element="'.$jobs->id.'">Break Time</a> '.$break_hours.'</td>
            </tr>';
              $i++;
                  }
                }
              }              
            }
            if($i == 1){
              $output.= '<tr>
                        <td align="left"></td>
                        <td align="left"></td>
                        <td align="left"></td>
                        <td align="center">Empty</td>
                        <td align="left"></td>
                        <td align="left"></td>
                        <td align="left"></td>                        
                        </tr>';
            }
            echo $output;           
            ?>
            
          </tbody>
        </table>
      </div>

      <div class="col-lg-12" style="margin-top: 50px;">
        
         <h4 class="col-lg-2" style="padding: 0px; font-weight: 600">Job of the Day / Closed Job</h4>

        <table class="display nowrap fullviewtablelist" id="closed_job" width="100%">
          <thead>
            <tr style="background: #fff;">
              <th width="2%" style="text-align: left;">S.No</th>
              <th style="text-align: left;">Client Name</th>
              <th style="text-align: left;">Task Name</th>
              <th style="text-align: left;">Task Type</th>
              <th style="text-align: left;">Quick Break</th>
              <th style="text-align: left;">Start Time</th>   
              <th style="text-align: left;">Job Time</th>
              <th style="text-align: left;">Stop Time</th>              
          </tr>
          </thead>
          <tbody id="tbody_jobclosed">
            <?php
            $output='';
            $i=1;            
            if(count($joblist)){              
              foreach ($joblist as $jobs) {
                if($jobs->quick_job == 1 || $jobs->status == 1 ){
                $client_details = DB::table('cm_clients')->where('client_id', $jobs->client_id)->first();

                if(count($client_details) != ''){
                  $client_name = $client_details->company;
                }
                else{
                  $client_name = 'N/A';
                }
                $task_details = DB::table('time_task')->where('id', $jobs->task_id)->first();

                if(count($task_details) != ''){
                  $task_name = $task_details->task_name;
                  $task_type = $task_details->task_type;

                  if($task_type == 0){
                    $task_type = '<i class="fa fa-desktop" style="margin-right:10px;"></i> Internal Task';
                  }
                  else if($task_type == 1){
                    $task_type = '<i class="fa fa-users" style="margin-right:10px;"></i> Client Task';
                  }
                  else{
                    $task_type = '<i class="fa fa-globe" style="margin-right:10px;"></i> Globel Task';
                  }
                }
                else{
                  $task_name = 'N/A';
                  $task_type = 'N/A';
                }

                if($jobs->quick_job == 0){
                  $quick_job = 'No';
                  $job_time = $jobs->job_time;
                }
                else{
                  $quick_job = 'Yes'; 
                  $job_time = 'N/A';
                }


            $output.='
            <tr>
              <td align="left">'.$i.'</td>
              <td align="left">'.$client_name.'</td>
              <td align="left">'.$task_name.'</td>
              <td align="left">'.$task_type.'</td>
              <td align="left">'.$quick_job.'</td>
              <td align="left">'.date('h:i A', strtotime($jobs->start_time)).'</td>
              <td align="left">'.$job_time.'</td>
              <td align="left">'.date('h:i A', strtotime($jobs->stop_time)).'</td>
            </tr>';
              $i++;
                  
                }
              }              
            }
            if($i == 1){
              $output.= '<tr>
                        <td align="left"></td>
                        <td align="left"></td>
                        <td align="left"></td>
                        <td align="right">Empty</td>
                        <td align="left"></td>
                        <td align="left"></td>
                        <td align="left"></td>
                        <td align="left"></td>
                        </tr>';
            }
            echo $output;
            ?>
          </tbody>
        </table>

      </div>

      <div class="col-lg-12 user_details_div" style="margin-top: 30px;display:none">        
         <h5 style="padding: 0px; font-weight: 600">Total number of minutes worked for today by <span class="job_username">Ciaran</span> : <span id="job_username_minutes">120</span> Minutes</h5>
      </div>
      <div class="col-lg-12 user_details_div" style="margin-top: 0px;display:none">        
         <h5 style="padding: 0px; font-weight: 600">Total number of hours worked for today by <span class="job_username">Ciaran</span> : <span id="job_username_hours">02</span> Hours</h5>
      </div>




    </div>
</div>
<script>
$("#user_select").change(function(){

  var id = $(this).val();
  $.ajax({
    url:"<?php echo URL::to('user/job_user_filter')?>",
    data:{userid:id},
    dataType:'json',
    success:function(result){
        $("#tbody_active").html(result['activejob']);
        $("#tbody_jobclosed").html(result['closejob']);
        $("#user_details_div").show();
        $(".job_username").html(result['username']);
        $("#job_username_minutes").html(result['spendminutes']);
        $("#job_username_hours").html(result['spendhours']);
    }
  })
});
</script>


<script>
$(function(){
    $('#active_job').DataTable({
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
    $('#closed_job').DataTable({
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
$(window).click(function(e) {
  var userval = $("#user_select").val();
  if(userval != "")
  {
    $(".user_details_div").show();
  }
  else{
    $(".user_details_div").hide(); 
  }
  if($(e.target).hasClass('create_new')) {
    var userid = $("#user_select").val();
    if(userid == "" || typeof userid === "undefined")
    {
      alert("Please select the Users");
      return false;
    }
    $(".mark_internal").prop("checked",false);
    $(".client_search_class").val("");
    $(".client_group").show();
    $(".internal_tasks_group").hide();
    $(".user_id").val(userid);
    $(".create_new_model").modal("show");
    $("#quickjob").val('0');
    $(".job_title").html('Create an Active Job');
    $(".job_button_name").val('Create an Active Job');
    $(".stop_group").hide();
    $(".stop_time").prop("required",false);
    
    $(".stop_time").val('');
    $(".start_button").show();
    $(".start_button_quick").hide();
    $(".job_button_name").hide();
    $(".date_group").hide();
    $(".start_group").hide();
    $(".client_search_class").val('');
    $(".tasks_group").hide();
    
    
       
  }
  if($(e.target).hasClass('create_new_quick')) {
    var userid = $("#user_select").val();
    if(userid == "" || typeof userid === "undefined")
    {
      alert("Please select the Users");
      return false;
    }

    $(".mark_internal").prop("checked",false);
    $(".client_search_class").val("");
    $(".client_group").show();
    $(".internal_tasks_group").hide();

    $(".user_id").val(userid);
    $(".create_new_model").modal("show");
    $("#quickjob").val('1');
    $(".job_title").html('Create a Quick Break');
    $(".job_button_name").val('Create a Quick Break');    
    $(".create_dateclass").prop("required",false);
    $(".stop_time").prop("required",true);
    
    
    $(".stop_time").val('');
    $(".date_group").hide();
    $(".start_button_quick").show();
    $(".start_button").hide();
    $(".job_button_name").hide();
    $(".start_group").hide();
    $(".stop_group").hide();
    $(".client_search_class").val('');
    $(".tasks_group").hide();
  }
  if($(e.target).hasClass('mark_internal'))
  {
    if($(e.target).is(":checked"))
    {      
      $(".internal_type").val('0');
      $(".client_group").hide();
      $(".internal_tasks_group").show();
      $(".tasks_group").hide();
      $(".client_search_class").val('');
      $(".client_search_class").prop("required",false);
      $("#client_search").val('');
      $(".task_details").html('');
      $("#idtask").val('');
    }
    else{
      $(".internal_type").val('1');
      $(".client_group").show();
      $(".internal_tasks_group").hide();
      $(".tasks_group").hide();
      $(".client_search_class").prop("required",true);
    }
  }
  if($(e.target).hasClass('tasks_li'))
  {
    var taskid = $(e.target).attr('data-element');
    $("#idtask").val(taskid);
    $(".task-choose:first-child").text($(e.target).text());
  }
  if($(e.target).hasClass('tasks_li_internal'))
  {
    var taskid = $(e.target).attr('data-element');
    $("#idtask").val(taskid);
    $(".task-choose_internal:first-child").text($(e.target).text());
  }

  if($(e.target).hasClass('stop_class')) {
    var id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/stop_job_details')?>",
      dataType: "json",
      data:{jobid:id},
      success:function(result){
        $(".idclass").val(result['id']);
        $(".dateclass").val(result['date']);
        $(".stop_model").modal("show");
        $(".comments").val('');
        $('#stop_time1').data("DateTimePicker").minDate(moment().startOf('day').hour(result['start_hour']).minute(result['start_min']));
        $('#stop_time1').data("DateTimePicker").maxDate(moment().startOf('day').hour(23).minute(59));

      }
    })
  }

  if($(e.target).hasClass('take_break_class')) {

    var id = $(e.target).attr("data-element"); 
    $(".id_take_break").val(id);    
    $(".take_break_model").modal("show");
    $(".select_break_class").val('');
  }

  if($(e.target).hasClass('break_time_class')) {
    $("#break_tbody").dataTable().fnDestroy();
    var id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/break_time_details')?>",      
      data:{jobid:id},
      success:function(result){
        $(".break_time_details").html(result);
        $(".break_time_model").modal("show");
        $('#break_tbody').DataTable({            
            fixedHeader: {
              headerOffset: 75
            },
            autoWidth: true,
            scrollX: false,
            fixedColumns: false,
            searching: false,
            paging: false,
            info: false,            
        });
      }
    })
  }

  if($(e.target).hasClass('start_button'))
  {
    var internal_job = $(".mark_internal:checked").length;
    var idtask = $("#idtask").val();
    if(internal_job > 0)
    {
     
      if(idtask == "" || typeof idtask === "undefined")
      {
        alert("Please Select any of the internal task from the dropdown.");
        return false;
      }
      else{
          var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});
          
          $(".create_dateclass").datetimepicker({
             defaultDate: fullDate,
             format: 'LL',
          })
          $(".create_startclass").datetimepicker({
             defaultDate: fullDate,
             format: 'LT',
          }) 

          $(".create_dateclass").prop("readonly", false);
          $(".date_group").show();
          $(".start_group").show();
          $(".start_button").hide();
          $(".job_button_name").show();
          
      }
    }
    else{
       $("#create_job_form").valid();
       var client_search_class = $(".client_search_class").val();
       if(client_search_class != "")
       {
          if(idtask == "" || typeof idtask === "undefined")
          {
            alert("Please Select any of the task from the dropdown.");
            return false;
          }
          else{

            var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});
            
            $(".create_dateclass").datetimepicker({
               defaultDate: fullDate,
               format: 'LL',
            })
            $(".create_startclass").datetimepicker({
               defaultDate: fullDate,
               format: 'LT',
            }) 


            $(".create_dateclass").prop("readonly", false);
            $(".date_group").show();
            $(".start_group").show();
            $(".start_button").hide();
            $(".job_button_name").show();
            
          }
       } 
    }
  }
  if($(e.target).hasClass('start_button_quick'))
  {    
    var internal_job = $(".mark_internal:checked").length;
    var idtask = $("#idtask").val();
    if(internal_job > 0)
    {
      if(idtask == "" || typeof idtask === "undefined")
      {
        alert("Please Select any of the internal task from the dropdown.");
        return false;
      }
      else{
        var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});

        $(".create_dateclass").datetimepicker({
           defaultDate: fullDate,
           format: 'LL',
        })
        
        $(".create_startclass").datetimepicker({
           defaultDate: fullDate,
           format: 'LT',
        })      

        $(".start_group").show();
        $(".stop_group").show();    
        $(".start_button_quick").hide();
        $(".job_button_name").show(); 
        $(".date_group").show();
        $(".create_dateclass").prop("readonly", true);
      }
    }
    else{
      $("#create_job_form").valid();
       var client_search_class = $(".client_search_class").val();
       if(client_search_class != "")
       {
          if(idtask == "" || typeof idtask === "undefined")
          {
            alert("Please Select any of the task from the dropdown.");
            return false;
          }
          else{
            var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});

            $(".create_dateclass").datetimepicker({
               defaultDate: fullDate,
               format: 'LL',
            })
            
            $(".create_startclass").datetimepicker({
               defaultDate: fullDate,
               format: 'LT',
            })      

            $(".start_group").show();
            $(".stop_group").show();    
            $(".start_button_quick").hide();
            $(".job_button_name").show(); 
            $(".date_group").show();
            $(".create_dateclass").prop("readonly", true);
          }
        }
    }
    
  }

  if($(e.target).hasClass('job_time_refresh')){
    
    var editid = $(e.target).attr("data-element");
    
    $.ajax({
      url: "<?php echo URL::to('user/job_time_count_refresh') ?>",
      data:{id:editid},
      type:"post",
      dataType:"json",
      success:function(result){
         
         $("#job_time_refresh_"+result['id']).html(result['refreshcount']);
       }

    });
  }




});
</script>

<script type="text/javascript">
    $(function () {
        $('#start_time').datetimepicker({
            format: 'LT',
        });
        $('#stop_time').datetimepicker({
            format: 'LT'
        });
        $('#stop_time1').datetimepicker({
            format: 'LT'
        });
        $("#start_time").on("dp.change", function (e) {
            $('#stop_time').data("DateTimePicker").minDate(e.date);
            $('#stop_time').data("DateTimePicker").maxDate(moment().startOf('day').hour(23).minute(59));
            
        });
        
        $('.datepicker-only-init').datetimepicker({
            widgetPositioning: {
                horizontal: 'left'
            },
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down"
            },
            format: 'LL'
        });
    });
</script>
<script>
$(document).ready(function() {    
     $(".client_search_class").autocomplete({
        source: function(request, response) {
            $.ajax({
                url:"<?php echo URL::to('user/timesystem_client_search'); ?>",
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
            
            url:"<?php echo URL::to('user/timesystem_client_search_select'); ?>",
            data:{value:ui.item.id},
            success: function(result){
              if(ui.item.active_status == 2)
              {
                var r = confirm("This is a Deactivated Client. Are you sure you want to continue with this client?");
                if(r)
                {
                  $(".task_details").html(result);
                  $(".task-choose:first-child").text("Select Tasks");
                  $("#idtask").val('');
                  $(".tasks_group").show();
                }
                else{
                  $(".client_search_class").val('');
                  $(".task_details").html('');
                  $(".task-choose:first-child").text("Select Tasks");
                  $("#idtask").val('');
                  $(".tasks_group").hide();
                }
              }
              else{
                $(".task_details").html(result);
                $(".task-choose:first-child").text("Select Tasks")
                $("#idtask").val('');
                $(".tasks_group").show();
              }
            }
          })
        }
    });     
});
</script>

<script type="text/javascript">
/*var fullDate = new Date()
var twoDigitMonth = ((fullDate.getMonth().length+1) === 1)? (fullDate.getMonth()+1) :(fullDate.getMonth()+1);
var currentDate = fullDate.getDate() + "-" + twoDigitMonth + "-" + fullDate.getFullYear();
console.log(currentDate);
$(".currentdate").val(currentDate);*/
</script>

<!-- 


  job_type = Internal Job =0
  job_type = client Job = 1

  quick_job = Big Job =0
  quick_job = Quick Job =1


 -->

@stop
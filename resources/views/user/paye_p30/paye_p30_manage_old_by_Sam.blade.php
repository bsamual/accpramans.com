@extends('userheader')
@section('content')
<script src='<?php echo URL::to('assets/js/table-fixed-header_cm.js'); ?>'></script>
<style>
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
.error{
  color:#f00;
}
.pagination {
  display: inline-block;
}

.pagination a {
  color: black;
  float: left;
  padding: 8px 16px;
  text-decoration: none;
  transition: background-color .3s;
  border: 1px solid #ddd;
}

.pagination a.active {
  background-color: #4CAF50;
  color: white;
  border: 1px solid #4CAF50;
}

.pagination a:hover:not(.active) {background-color: #ddd;}

.blueinfo{
    color:#240bf7 !important;
    padding:6px;
    border:1px solid;
    border-radius:10px;
    margin-left:10px;
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

.modal_load_content {
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
body.loading_content {
    overflow: hidden;   
}
body.loading .modal_load_content {
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
<!-- <div class="left_menu_dropdown" style="width: 150px; height: auto; position: fixed; z-index: 999; top:300px; left: 0px;display:none">
    <?php $segment1 =  Request::segment(2);  ?>
    <ul class="nav navbar-nav navbar-right menu left_menu" style="margin-right: 25.5%;">
            <li class="dropdown <?php if($segment1 == "client_management" || $segment1 == "invoice_management" || $segment1 == "client_statements" || $segment1 == "receipt_management" || $segment1 == "time_management") { echo 'active'; } ?>"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">GBS-EP Central</a>
                <ul class="dropdown-menu">
                    <li class="<?php if(($segment1 == "client_management")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/client_management'); ?>">Client Mangement</a></li>
                    <li class="<?php if(($segment1 == "invoice_management")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/invoice_management'); ?>">Invoice Management</a></li>
                    <li class="<?php if(($segment1 == "client_statements")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/client_statements'); ?>">Client Statements</a></li>
                    <li class="<?php if(($segment1 == "receipt_management")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/receipt_management'); ?>">Receipt Management</a></li>
                    <li class="<?php if(($segment1 == "time_management")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/time_management'); ?>">Time Management</a></li>
                </ul>
            </li>
            <li class="dropdown <?php if($segment1 == "manage_week" || $segment1 == "week_manage" || $segment1 == "select_week" || $segment1 == "manage_month" || $segment1 == "month_manage" || $segment1 == "select_month" || $segment1 == "p30" || $segment1 == "p30month_manage" || $segment1 == "p30_select_month" || $segment1 == "paye_p30month_manage" || $segment1 == "paye_p30_select_month") { echo 'active'; } ?>"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">EasyPayroll</a>
                <ul class="dropdown-menu">
                    <li class="<?php if(($segment1 == "manage_week") || ($segment1 == "week_manage") || ($segment1 == "select_week")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/manage_week')?>">Weekly Payroll</a></li>
                    <li class="<?php if(($segment1 == "manage_month") || ($segment1 == "month_manage") || ($segment1 == "select_month")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/manage_month')?>">Monthly Payroll</a></li>
                    <li class="<?php if(($segment1 == "p30") || ($segment1 == "p30month_manage" || $segment1 == "paye_p30month_manage" || $segment1 == "paye_p30_select_month") || ($segment1 == "p30_select_month")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/p30'); ?>">P30 System</a></li>
                </ul>
            </li>

            <li class="dropdown <?php if($segment1 == "vat_clients" || $segment1 == "rctclients" || $segment1 == "expand_rctclient" || $segment1 == "gbs_p30" || $segment1 == "gbs_p30month_manage" || $segment1 == "gbs_p30_select_month" || $segment1 == "year_end_manager" || $segment1 == "yearend_setting" || $segment1 == "supplementary_manager" || $segment1 == "yeadend_clients" || $segment1 == "yearend_individualclient" || $segment1 == "supplementary_note_create" || $segment1 == "gbs_paye_p30month_manage" || $segment1 == "gbs_paye_p30_select_month") { echo 'active'; } ?>"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">GBS & Co</a>
                <ul class="dropdown-menu">
                    <li class="<?php if(($segment1 == "vat_clients")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/vat_clients')?>">VAT Management</a></li>
                    <li class="<?php if(($segment1 == "rctclients") || ($segment1 == "expand_rctclient")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/rctclients')?>">RCT System</a></li>
                    <li class="<?php if(($segment1 == "gbs_p30") || ($segment1 == "gbs_p30month_manage") || ($segment1 == "gbs_p30_select_month") || ($segment1 == "gbs_paye_p30month_manage") || ($segment1 == "gbs_paye_p30_select_month")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/gbs_p30'); ?>">P30 System</a></li>
                    <li class="<?php if(($segment1 == "year_end_manager")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/year_end_manager'); ?>">Year End Manager</a></li>
                </ul>
            </li>
            <li class="<?php if(($segment1 == "time_me" || $segment1 == "time_task" || $segment1 == "time_me_overview" || $segment1 == "time_me_joboftheday" || $segment1 == "time_me_client_review" || $segment1 == "time_me_all_job" || $segment1 == "time_track")) { echo "active"; } else { echo ""; } ?>"><a href="javascript:" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Time Me</a>
              <ul class="dropdown-menu">
                <li class="<?php if(($segment1 == "time_track")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/time_track')?>">TimeMe Manager</a></li>
                <li class="<?php if(($segment1 == "time_me_overview" || $segment1 == "time_me_joboftheday" || $segment1 == "time_me_client_review" || $segment1 == "time_me_all_job")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/time_me_overview')?>">TimeMe Overview</a></li>
                  <li class="<?php if(($segment1 == "time_task")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/time_task')?>">Tasks</a></li>
              </ul>
            </li>

            <li class="<?php if(($segment1 == "in_file" || $segment1 == "in_file_advance" || $segment1 == "infile_search")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/in_file_advance')?>">InFiles</a>


            
              
            </li>

            

            <li><a href="<?php echo URL::to('user/logout')?>">Logout</a></li>
          </ul>
</div> -->
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
                    $users = DB::table('user')->where('user_status',0)->where('email','!=', '')->get();
                    if(count($users))
                    {
                      foreach($users as $user)
                      {
                          ?>
                            <option value="<?php echo trim($user->email); ?>"><?php echo $user->firstname.' '.$user->lastname; ?></option>
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
<div id="alert_modal" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
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

<div class="content_section">
<!-- 
<div class="header_logo" style="width: 130px; height: auto; padding: 15px; position: fixed; top: 10px; background:  #ff0; z-index: 9999999; left: 0px;display:none">
  <img src="<?php echo URL::to('assets/images/easy_payroll_logo.png')?>" style="width: 100%" />
</div> -->
<div class="arrow_right" style="height: auto; padding: 15px; position: fixed; bottom: 10px; background:  #ff0; z-index: 9999999; right: 15px;font-size:34px;display:none">
  <a href="javascript:" class="arrow_right_scroll"><i class="fa fa-arrow-circle-o-up arrow_right_scroll" aria-hidden="true"></i></a>
</div>

  <div class="page_title" style="position:fixed;margin-top: -17px;">
    <div class="col-lg-2 padding_00" style="line-height: 30px; font-size: 20px">
      PAYE M.R.S <?php echo $year->year_name?>  
      <input type="hidden" value="<?php echo $year->year_id?>" class="year_id" name="">          
    </div>

    <div class="col-lg-10 padding_00 button_top_right">
          <ul>
            <li class="">
                <div class="col-lg-12" style="line-height: 30px;">Active Month:</div>
            </li>
            <li class="">
                <div class="col-lg-12">
                    <select class="form-control active_month">
                        <option value="">Select Period</option>
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                </div>
            </li>
            <li class=""><a href="<?php echo URL::to('user/paye_p30_create_new_year'); ?>" style="float:right">Close and Create New Year</a></li>
            <li class=""><a href="<?php echo URL::to('user/paye_p30_review_year/'.$year->year_id); ?>">Review Year</a></li>           
          </ul>
    </div>

    <div class="col-lg-12 padding_00">
        <div class="row">
            <div class="col-lg-3 padding_00">
                <div class="col-lg-12" style="line-height: 30px;">Active Week Periods for all:</div>
                <div class="col-lg-6">                
                    <select class="form-control week_from" required>
                        <option>Select From</option>
                        <option value="1">Week1</option>
                        <option value="2" selected>Week2</option>
                        <option value="3">Week3</option>
                        <option value="4">Week4</option>
                        <option value="5">Week5</option>
                        <option value="6">Week6</option>
                        <option value="7">Week7</option>
                        <option value="8">Week8</option>
                        <option value="9">Week9</option>
                        <option value="10">Week10</option>
                        <option value="11">Week11</option>
                        <option value="12">Week12</option>
                        <option value="13">Week13</option>
                        <option value="14">Week14</option>
                        <option value="15">Week15</option>
                        <option value="16">Week16</option>
                        <option value="17">Week17</option>
                        <option value="18">Week18</option>
                        <option value="19">Week19</option>
                        <option value="20">Week20</option>
                        <option value="21">Week21</option>
                        <option value="22">Week22</option>
                        <option value="23">Week23</option>
                        <option value="24">Week24</option>
                        <option value="25">Week25</option>
                        <option value="26">Week26</option>
                        <option value="27">Week27</option>
                        <option value="28">Week28</option>
                        <option value="29">Week29</option>
                        <option value="30">Week30</option>
                        <option value="31">Week31</option>
                        <option value="32">Week32</option>
                        <option value="33">Week33</option>
                        <option value="34">Week34</option>
                        <option value="35">Week35</option>
                        <option value="36">Week36</option>
                        <option value="37">Week37</option>
                        <option value="38">Week38</option>
                        <option value="39">Week39</option>
                        <option value="40">Week40</option>
                        <option value="41">Week41</option>
                        <option value="42">Week42</option>
                        <option value="43">Week43</option>
                        <option value="44">Week44</option>
                        <option value="45">Week45</option>
                        <option value="46">Week46</option>
                        <option value="47">Week47</option>
                        <option value="48">Week48</option>
                        <option value="49">Week49</option>
                        <option value="50">Week50</option>
                        <option value="51">Week51</option>
                        <option value="52">Week52</option>
                        <option value="53">Week53</option>
                    </select>
                </div>
                <div class="col-lg-6 ">                
                    <select class="form-control week_to" required>
                        <option>Select To</option>
                        <option value="1">Week1</option>
                        <option value="2">Week2</option>
                        <option value="3">Week3</option>
                        <option value="4">Week4</option>
                        <option value="5">Week5</option>
                        <option value="6">Week6</option>
                        <option value="7">Week7</option>
                        <option value="8">Week8</option>
                        <option value="9">Week9</option>
                        <option value="10" selected >Week10</option>
                        <option value="11">Week11</option>
                        <option value="12">Week12</option>
                        <option value="13">Week13</option>
                        <option value="14">Week14</option>
                        <option value="15">Week15</option>
                        <option value="16">Week16</option>
                        <option value="17">Week17</option>
                        <option value="18">Week18</option>
                        <option value="19">Week19</option>
                        <option value="20">Week20</option>
                        <option value="21">Week21</option>
                        <option value="22">Week22</option>
                        <option value="23">Week23</option>
                        <option value="24">Week24</option>
                        <option value="25">Week25</option>
                        <option value="26">Week26</option>
                        <option value="27">Week27</option>
                        <option value="28">Week28</option>
                        <option value="29">Week29</option>
                        <option value="30">Week30</option>
                        <option value="31">Week31</option>
                        <option value="32">Week32</option>
                        <option value="33">Week33</option>
                        <option value="34">Week34</option>
                        <option value="35">Week35</option>
                        <option value="36">Week36</option>
                        <option value="37">Week37</option>
                        <option value="38">Week38</option>
                        <option value="39">Week39</option>
                        <option value="40">Week40</option>
                        <option value="41">Week41</option>
                        <option value="42">Week42</option>
                        <option value="43">Week43</option>
                        <option value="44">Week44</option>
                        <option value="45">Week45</option>
                        <option value="46">Week46</option>
                        <option value="47">Week47</option>
                        <option value="48">Week48</option>
                        <option value="49">Week49</option>
                        <option value="50">Week50</option>
                        <option value="51">Week51</option>
                        <option value="52">Week52</option>
                        <option value="53">Week53</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-3 padding_00">
                <div class="col-lg-12" style="line-height: 30px;">Active Month Periods for all:</div>
                <div class="col-lg-6">                
                    <select class="form-control month_from" required>
                        <option>Select From</option>
                        <option value="1" selected>January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                </div>
                <div class="col-lg-6 ">                
                    <select class="form-control month_to" required>
                        <option>Select To</option>
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5" selected>May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-4 button_top_right" style="float: left;">
                <ul style="float: left; margin-top: 28px;">
                    <li><a href="javascript:" class="apply_class">Apply</a></li>
                    <li><a href="javascript:" class="show_active_periods">Show Active Periods Only</a></li>
                    <li><a href="javascript:" class="show_all_periods">Show all Periods</a></li>
                  </ul>
            </div>
            <div class="col-lg-2" >
              
            </div>
        </div>
        
    </div>
  </div>

  <div class="col-lg-12" style="clear: both;  margin-top:130px">

    <div style="width:100%;float:left;">

        <?php

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
        <ul>
        <li>
            <div class="sno" style="background:#000;color:#fff">S.No</div>
            <div class="clientname" style="background:#000;color:#fff">Task Name
                
            </div>
        </li>';
  $i = 1;
  if(count($payelist)){
    foreach ($payelist as $keytask => $task) {
        
        $level_name = DB::table('p30_tasklevel')->where('id',$task->task_level)->first();

        if($task->task_level != 0){ $action = $level_name->name; }
        if($task->pay == 0){ $pay = 'No';}else{$pay = 'Yes';}
        if($task->email == 0){ $email = 'No';}else{$email = 'Yes';}
        if($keytask % 2 == 0) { $background = ''; }else{ $background = 'style="background:#e5f7fe"'; }
        $output.='<li '.$background.'>
                <div class="sno">'.$i.'</div>
                <div class="clientname">'.$task->task_name.' <a href="javascript:" class="load_info" data-element="'.$task->id.'"> Load Table </a>
                    <div class="load_info_table" style="display:none">

                    </div>
                </div>
            </li>';
            $i++;
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
<div class="modal_load"></div>
<div class="modal_load_content"></div>
<script type="text/javascript">
$(window).scroll(function(){
    if($(this).scrollTop()){
      $(".navbar").fadeOut(1000);
      $(".footer_row").fadeOut(1000);
      $(".arrow_right").fadeIn(1000);
      // $(".header_logo").fadeIn(1000);

      $(".content_section").css("margin-top","10px");
    }
    else{
     $(".navbar").fadeIn(1000);
     $(".footer_row").fadeIn(1000);
     $(".arrow_right").fadeOut(1000);
     // $(".header_logo").fadeOut(1000);
     
     $(".content_section").css("margin-top","100px");
    }
 }); 
$("document").ready(function() {
    <?php
    if($year->show_active == 1)
    {
        ?>
        $(".hide_column").hide();
        $(".hide_column_inner").parents("td").hide();
        <?php
    }
    else{
        ?>
        $(".hide_column").show();
        $(".hide_column_inner").parents("td").show();
        <?php
    }
    ?>

    // $('.table-fixed-header').fixedHeader();

    $(".pagination").find("a").hide();
    $(".pagination").find(".active").show();
    $(".pagination").find(".active").prev().show();
    $(".pagination").find(".active").prev().prev().show();

    $(".pagination").find(".active").next().show();
    $(".pagination").find(".active").next().next().show();
    $(".pagination").find(".active").next().next().next().show();
    $(".pagination").find(".active").next().next().next().next().show();


    $(".pagination").find(".next_page").show();
    $(".pagination").find(".prev_page").show();

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
$(window).click(function(e) {
if($(e.target).hasClass('load_info'))
{
  $("body").addClass("loading");
  var task_id = $(e.target).attr("data-element");
  $.ajax({
    url:"<?php echo URL::to('user/load_table_info'); ?>",
    type:"post",
    data:{task_id:task_id,year_id:"<?php echo $year->year_id; ?>"},
    dataType:"json",
    success: function(result)
    {
      $(e.target).parents(".clientname").find(".load_info_table").html(result['output']);
      if(result['show_active'] == 1)
      {
        $(".hide_column").hide();
        $(".hide_column_inner").parents("td").hide();
      }
      else{
        $(".hide_column").show();
        $(".hide_column_inner").parents("td").show();
      }
      $(".load_info_table").slideDown(2000);
      $("body").removeClass("loading");
    }
  });
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
          
          var period_id = $(e.target).attr('data-element');
          $.ajax({
            url:'<?php echo URL::to('user/paye_p30_edit_email_unsent_files'); ?>',
            type:'get',
            data:{period_id:period_id},
            dataType:"json",
            success: function(result)
            {
                $(".subject_unsent").val(result['subject']);
                $("#to_user").val(result['to']);
                $("#from_user").val(result['from']);
                $(".emailunsent").modal('show');
                $("#hidden_email_task_id").val(period_id);
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
          $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>Email sent Successfully</p>", fixed:true});
          $(".email_unsent_"+task_id).parents("td").html("<a href='javascript:' class='fa fa-envelope email_unsent email_unsent_"+task_id+"' data-element='"+task_id+"'></a><br>"+result);
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
    $("body").addClass("loading");
    var week_from = $(".week_from").val(); 
    var week_to = $(".week_to").val();
    var month_from = $(".month_from").val();
    var month_to = $(".month_to").val();
    var active_month = $(".active_month_class").val();

    if(week_from == "")
    {
        $("#alert_modal").modal("show");
        $("#alert_content").html("Please choose the Week From option from the dropdown and prroceed to apply");
    }
    else if(week_to == "")
    {
        $("#alert_modal").modal("show");
        $("#alert_content").html("Please choose the Week To option from the dropdown and prroceed to apply");
    }
    else if(month_from == "")
    {
        $("#alert_modal").modal("show");
        $("#alert_content").html("Please choose the Month From option from the dropdown and prroceed to apply");
    }
    else if(month_to == "")
    {
        $("#alert_modal").modal("show");
        $("#alert_content").html("Please choose the Month To option from the dropdown and prroceed to apply");
    }
    else if(active_month == "")
    {
        $("#alert_modal").modal("show");
        $("#alert_content").html("Please choose the Active Month option from the dropdown and prroceed to apply");
    }
    else{
        var year_id = $(".year_id").val();
    
        $.ajax({
            url:"<?php echo URL::to('user/paye_p30_apply'); ?>",
            type:"post",
            dataType:"json",
            data:{week_from:week_from, week_to:week_to, month_from:month_from, month_to:month_to, active_month:active_month, year_id:year_id  },
            success: function(result){
                window.location.reload();
            }
        })
    }    
}

if($(e.target).hasClass("show_active_periods")){
    $("body").addClass("loading");
    var week_from = $(".week_from").val(); 
    var week_to = $(".week_to").val();
    var month_from = $(".month_from").val();
    var month_to = $(".month_to").val();

    var year_id = $(".year_id").val();

    if(week_from == "")
    {
        $("#alert_modal").modal("show");
        $("#alert_content").html("Please choose the Week From option from the dropdown and prroceed to show active periods");
    }
    else if(week_to == "")
    {
        $("#alert_modal").modal("show");
        $("#alert_content").html("Please choose the Week To option from the dropdown and prroceed to show active periods");
    }
    else if(month_from == "")
    {
        $("#alert_modal").modal("show");
        $("#alert_content").html("Please choose the Month From option from the dropdown and prroceed to show active periods");
    }
    else if(month_to == "")
    {
        $("#alert_modal").modal("show");
        $("#alert_content").html("Please choose the Month To option from the dropdown and prroceed to show active periods");
    }
    else{
    
    $.ajax({
        url:"<?php echo URL::to('user/paye_p30_active_periods'); ?>",
        type:"post",
        dataType:"json",
        data:{week_from:week_from, week_to:week_to, month_from:month_from, month_to:month_to, year_id:year_id  },
        success: function(result){
            for(var i=result['week_from']; i<=result['week_to']; i++)
            {
                $(".week_td_"+i).hide();
                $(".week"+i).hide();
                $(".week"+i+"_class_1").parents("td").hide();
                $(".week"+i+"_class_2").parents("td").hide();
                $(".week"+i+"_class_3").parents("td").hide();
                $(".week"+i+"_class_4").parents("td").hide();
                $(".week"+i+"_class_5").parents("td").hide();
                $(".week"+i+"_class_6").parents("td").hide();
                $(".week"+i+"_class_7").parents("td").hide();
                $(".week"+i+"_class_8").parents("td").hide();
                $(".week"+i+"_class_9").parents("td").hide();
                $(".week"+i+"_class_10").parents("td").hide();
                $(".week"+i+"_class_11").parents("td").hide();
                $(".week"+i+"_class_12").parents("td").hide();

                $("body").removeClass("loading");
            }

            for(var i=result['month_from']; i<=result['month_to']; i++)
            {
                $(".month_td_"+i).hide();
                $(".month"+i).hide();
                $(".month"+i+"_class_1").parents("td").hide();
                $(".month"+i+"_class_2").parents("td").hide();
                $(".month"+i+"_class_3").parents("td").hide();
                $(".month"+i+"_class_4").parents("td").hide();
                $(".month"+i+"_class_5").parents("td").hide();
                $(".month"+i+"_class_6").parents("td").hide();
                $(".month"+i+"_class_7").parents("td").hide();
                $(".month"+i+"_class_8").parents("td").hide();
                $(".month"+i+"_class_9").parents("td").hide();
                $(".month"+i+"_class_10").parents("td").hide();
                $(".month"+i+"_class_11").parents("td").hide();
                $(".month"+i+"_class_12").parents("td").hide();

                $("body").removeClass("loading");
            }
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
            $(".payp30_week_bg").show();
            $("body").removeClass("loading");
        }
    })    
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

         $(".week"+result['week']+"_class_"+result['id']).html(result['value']);
         $(".week"+result['week']+"_class_"+result['id']).css({"text-align":"right"});

        $(".month_row_"+result['id']).find(".liability_class").val(result['task_liability']);
        $(".month_row_"+result['id']).find(".diff_class").val(result['different']);        

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
         $(".month"+result['month']+"_class_"+result['id']).html(result['value']);
         $(".month"+result['month']+"_class_"+result['id']).css({"text-align":"right"});

        $(".month_row_"+result['id']).find(".liability_class").val(result['task_liability']);
        $(".month_row_"+result['id']).find(".diff_class").val(result['different']);        

        $(e.target).removeClass();        
        $(e.target).addClass("payp30_red");
        $("body").removeClass("loading");                
      }
  })
}

if($(e.target).hasClass("week_remove")){
    $("body").addClass("loading");
    var task_id = $(e.target).attr("value"); 
    var week = $(e.target).attr("data-element");    
    $.ajax({
      url:"<?php echo URL::to('user/paye_p30_periods_remove'); ?>",
      type:"post",
      dataType:"json",
      data:{task_id:task_id, week:week},
      success: function(result){

        $(e.target).parents("tr").find(".liability_class").val(result['task_liability']);
        $(e.target).parents("tr").find(".diff_class").val(result['different']);

        $(".week"+result['week']+"_class_"+result['id']).html(result['value']);
        $(".week"+result['week']+"_class_"+result['id']).css({"text-align":"center"});

        // if(result['week'] == 1){
        //     $(".week1_class_"+result['id']).html(result['value']);
        //     $(".week1_class_"+result['id']).css({"text-align":"center"});
        // }

        // if(result['week'] == 2){
        //     $(".week2_class_"+result['id']).html(result['value']);
        //     $(".week2_class_"+result['id']).css({"text-align":"center"});
        // }

        // if(result['week'] == 3){
        //     $(".week3_class_"+result['id']).html(result['value']);
        //     $(".week3_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 4){
        //     $(".week4_class_"+result['id']).html(result['value']);
        //     $(".week4_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 5){
        //     $(".week5_class_"+result['id']).html(result['value']);
        //     $(".week5_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 6){
        //     $(".week6_class_"+result['id']).html(result['value']);
        //     $(".week6_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 7){
        //     $(".week7_class_"+result['id']).html(result['value']);
        //     $(".week7_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 8){
        //     $(".week8_class_"+result['id']).html(result['value']);
        //     $(".week8_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 9){
        //     $(".week9_class_"+result['id']).html(result['value']);
        //     $(".week9_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 10){
        //     $(".week10_class_"+result['id']).html(result['value']);
        //     $(".week10_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 11){
        //     $(".week11_class_"+result['id']).html(result['value']);
        //     $(".week11_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 12){
        //     $(".week12_class_"+result['id']).html(result['value']);
        //     $(".week12_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 13){
        //     $(".week13_class_"+result['id']).html(result['value']);
        //     $(".week13_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 14){
        //     $(".week14_class_"+result['id']).html(result['value']);
        //     $(".week14_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 15){
        //     $(".week15_class_"+result['id']).html(result['value']);
        //     $(".week15_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 16){
        //     $(".week16_class_"+result['id']).html(result['value']);
        //     $(".week16_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 17){
        //     $(".week17_class_"+result['id']).html(result['value']);
        //     $(".week17_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 18){
        //     $(".week18_class_"+result['id']).html(result['value']);
        //     $(".week18_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 19){
        //     $(".week19_class_"+result['id']).html(result['value']);
        //     $(".week19_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 20){
        //     $(".week20_class_"+result['id']).html(result['value']);
        //     $(".week20_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 21){
        //     $(".week21_class_"+result['id']).html(result['value']);
        //     $(".week21_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 22){
        //     $(".week22_class_"+result['id']).html(result['value']);
        //     $(".week22_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 23){
        //     $(".week23_class_"+result['id']).html(result['value']);
        //     $(".week23_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 24){
        //     $(".week24_class_"+result['id']).html(result['value']);
        //     $(".week24_class_"+result['id']).css({"text-align":"center"});                       
        // }
        // if(result['week'] == 25){
        //     $(".week25_class_"+result['id']).html(result['value']);
        //     $(".week25_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 26){
        //     $(".week26_class_"+result['id']).html(result['value']);
        //     $(".week26_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 27){
        //     $(".week27_class_"+result['id']).html(result['value']);
        //     $(".week27_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 28){
        //     $(".week28_class_"+result['id']).html(result['value']);
        //     $(".week28_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 29){
        //     $(".week29_class_"+result['id']).html(result['value']);
        //     $(".week29_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 30){
        //     $(".week30_class_"+result['id']).html(result['value']);
        //     $(".week30_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 31){
        //     $(".week31_class_"+result['id']).html(result['value']);
        //     $(".week31_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 32){
        //     $(".week32_class_"+result['id']).html(result['value']);
        //     $(".week32_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 33){
        //     $(".week33_class_"+result['id']).html(result['value']);
        //     $(".week33_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 34){
        //     $(".week34_class_"+result['id']).html(result['value']);
        //     $(".week34_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 35){
        //     $(".week35_class_"+result['id']).html(result['value']);
        //     $(".week35_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 36){
        //     $(".week36_class_"+result['id']).html(result['value']);
        //     $(".week36_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 37){
        //     $(".week37_class_"+result['id']).html(result['value']);
        //     $(".week37_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 38){
        //     $(".week38_class_"+result['id']).html(result['value']);
        //     $(".week38_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 39){
        //     $(".week39_class_"+result['id']).html(result['value']);
        //     $(".week39_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 40){
        //     $(".week40_class_"+result['id']).html(result['value']);
        //     $(".week40_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 41){
        //     $(".week41_class_"+result['id']).html(result['value']);
        //     $(".week41_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 42){
        //     $(".week42_class_"+result['id']).html(result['value']);
        //     $(".week42_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 43){
        //     $(".week43_class_"+result['id']).html(result['value']);
        //     $(".week43_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 44){
        //     $(".week44_class_"+result['id']).html(result['value']);
        //     $(".week44_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 45){
        //     $(".week45_class_"+result['id']).html(result['value']);
        //     $(".week45_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 46){
        //     $(".week46_class_"+result['id']).html(result['value']);
        //     $(".week46_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 47){
        //     $(".week47_class_"+result['id']).html(result['value']);
        //     $(".week47_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 48){
        //     $(".week48_class_"+result['id']).html(result['value']);
        //     $(".week48_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 49){
        //     $(".week49_class_"+result['id']).html(result['value']);
        //     $(".week49_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 50){
        //     $(".week50_class_"+result['id']).html(result['value']);
        //     $(".week50_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 51){
        //     $(".week51_class_"+result['id']).html(result['value']);
        //     $(".week51_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 52){
        //     $(".week52_class_"+result['id']).html(result['value']);
        //     $(".week52_class_"+result['id']).css({"text-align":"center"});                       
        // }

        // if(result['week'] == 53){
        //     $(".week53_class_"+result['id']).html(result['value']);
        //     $(".week53_class_"+result['id']).css({"text-align":"center"});                       
        // }



        $(".task_row_"+result['paye_task']).find(".week"+result['week']).find(".payp30_red").addClass("payp30_black task_class_colum ");

        $(".task_row_"+result['paye_task']).find(".week"+result['week']).find(".payp30_red").removeClass("payp30_red");

        if($(".task_row_"+result['paye_task']).find(".week"+result['week']).find(".blueinfo").length > 0)
        {
            var changed_val = $(".task_row_"+result['paye_task']).find(".week"+result['week']).find(".blueinfo").attr("data-element");
            $(".task_row_"+result['paye_task']).find(".week"+result['week']).find("a").html(changed_val);
        }

        $(e.target).removeClass("payp30_green");        
        $(e.target).addClass("payp30_dash");
        $("body").removeClass("loading");                
      }
  })
}


if($(e.target).hasClass("month_remove")){
    $("body").addClass("loading");
    var task_id = $(e.target).attr("value"); 
    var month = $(e.target).attr("data-element");    
    $.ajax({
      url:"<?php echo URL::to('user/paye_p30_periods_month_remove'); ?>",
      type:"post",
      dataType:"json",
      data:{task_id:task_id, month:month},
      success: function(result){

        $(e.target).parents("tr").find(".liability_class").val(result['task_liability']);
        $(e.target).parents("tr").find(".diff_class").val(result['different']);

        $(".month"+result['month']+"_class_"+result['id']).html(result['value']);
        $(".month"+result['month']+"_class_"+result['id']).css({"text-align":"center"});


        $(".task_row_"+result['paye_task']).find(".month"+result['month']).find(".payp30_red").addClass("payp30_black task_class_colum_month");

        $(".task_row_"+result['paye_task']).find(".month"+result['month']).find(".payp30_red").removeClass("payp30_red");

        if($(".task_row_"+result['paye_task']).find(".month"+result['month']).find(".blueinfo").length > 0)
        {
            var changed_val = $(".task_row_"+result['paye_task']).find(".month"+result['month']).find(".blueinfo").attr("data-element");
            $(".task_row_"+result['paye_task']).find(".month"+result['month']).find("a").html(changed_val);
        }

        $(e.target).removeClass("payp30_green");        
        $(e.target).addClass("payp30_dash");
        $("body").removeClass("loading");                
      }
  })
}



if($(e.target).hasClass('refresh_liability'))
  {
    $("body").addClass("loading");
    var task_id = $(e.target).attr("data-element");
    var year_id = $(".year_id").val();
    $.ajax({
      url:"<?php echo URL::to('user/refresh_paye_p30_liability'); ?>",
      type:"get",
      dataType:"json",
      data:{task_id:task_id, year_id:year_id},
      success: function(result){
        


        if(jQuery.inArray("1", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week1").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week1").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week1']+'" title="Liability Value ('+result['week1']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week1").find("a").html(result['week1']); $(e.target).parents("tr").find(".week1").find(".payp30_dash").html(result['week1']); }
        if(jQuery.inArray("2", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week2").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week2").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week2']+'" title="Liability Value ('+result['week2']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week2").find("a").html(result['week2']); $(e.target).parents("tr").find(".week2").find(".payp30_dash").html(result['week2']); }
        if(jQuery.inArray("3", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week3").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week3").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week3']+'" title="Liability Value ('+result['week3']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week3").find("a").html(result['week3']); $(e.target).parents("tr").find(".week3").find(".payp30_dash").html(result['week3']); }
        if(jQuery.inArray("4", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week4").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week4").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week4']+'" title="Liability Value ('+result['week4']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week4").find("a").html(result['week4']); $(e.target).parents("tr").find(".week4").find(".payp30_dash").html(result['week4']); }
        if(jQuery.inArray("5", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week5").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week5").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week5']+'" title="Liability Value ('+result['week5']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week5").find("a").html(result['week5']); $(e.target).parents("tr").find(".week5").find(".payp30_dash").html(result['week5']); }
        if(jQuery.inArray("6", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week6").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week6").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week6']+'" title="Liability Value ('+result['week6']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week6").find("a").html(result['week6']); $(e.target).parents("tr").find(".week6").find(".payp30_dash").html(result['week6']); }
        if(jQuery.inArray("7", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week7").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week7").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week7']+'" title="Liability Value ('+result['week7']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week7").find("a").html(result['week7']); $(e.target).parents("tr").find(".week7").find(".payp30_dash").html(result['week7']); }
        if(jQuery.inArray("8", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week8").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week8").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week8']+'" title="Liability Value ('+result['week8']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week8").find("a").html(result['week8']); $(e.target).parents("tr").find(".week8").find(".payp30_dash").html(result['week8']); }
        if(jQuery.inArray("9", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week9").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week9").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week9']+'" title="Liability Value ('+result['week9']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week9").find("a").html(result['week9']); $(e.target).parents("tr").find(".week9").find(".payp30_dash").html(result['week9']); }
        if(jQuery.inArray("10", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week10").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week10").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week10']+'" title="Liability Value ('+result['week10']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week10").find("a").html(result['week10']); $(e.target).parents("tr").find(".week10").find(".payp30_dash").html(result['week10']); }
        if(jQuery.inArray("11", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week11").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week11").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week11']+'" title="Liability Value ('+result['week11']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week11").find("a").html(result['week11']); $(e.target).parents("tr").find(".week11").find(".payp30_dash").html(result['week11']); }
        if(jQuery.inArray("12", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week12").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week12").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week12']+'" title="Liability Value ('+result['week12']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week12").find("a").html(result['week12']); $(e.target).parents("tr").find(".week12").find(".payp30_dash").html(result['week12']); }
        if(jQuery.inArray("13", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week13").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week13").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week13']+'" title="Liability Value ('+result['week13']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week13").find("a").html(result['week13']); $(e.target).parents("tr").find(".week13").find(".payp30_dash").html(result['week13']); }
        if(jQuery.inArray("14", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week14").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week14").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week14']+'" title="Liability Value ('+result['week14']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week14").find("a").html(result['week14']); $(e.target).parents("tr").find(".week14").find(".payp30_dash").html(result['week14']); }
        if(jQuery.inArray("15", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week15").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week15").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week15']+'" title="Liability Value ('+result['week15']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week15").find("a").html(result['week15']); $(e.target).parents("tr").find(".week15").find(".payp30_dash").html(result['week15']); }
        if(jQuery.inArray("16", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week16").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week16").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week16']+'" title="Liability Value ('+result['week16']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week16").find("a").html(result['week16']); $(e.target).parents("tr").find(".week16").find(".payp30_dash").html(result['week16']); }
        if(jQuery.inArray("17", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week17").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week17").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week17']+'" title="Liability Value ('+result['week17']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week17").find("a").html(result['week17']); $(e.target).parents("tr").find(".week17").find(".payp30_dash").html(result['week17']); }
        if(jQuery.inArray("18", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week18").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week18").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week18']+'" title="Liability Value ('+result['week18']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week18").find("a").html(result['week18']); $(e.target).parents("tr").find(".week18").find(".payp30_dash").html(result['week18']); }
        if(jQuery.inArray("19", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week19").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week19").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week19']+'" title="Liability Value ('+result['week19']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week19").find("a").html(result['week19']); $(e.target).parents("tr").find(".week19").find(".payp30_dash").html(result['week19']); }
        if(jQuery.inArray("20", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week20").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week20").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week20']+'" title="Liability Value ('+result['week20']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week20").find("a").html(result['week20']); $(e.target).parents("tr").find(".week20").find(".payp30_dash").html(result['week20']); }
        if(jQuery.inArray("21", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week21").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week21").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week21']+'" title="Liability Value ('+result['week21']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week21").find("a").html(result['week21']); $(e.target).parents("tr").find(".week21").find(".payp30_dash").html(result['week21']); }
        if(jQuery.inArray("22", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week22").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week22").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week22']+'" title="Liability Value ('+result['week22']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week22").find("a").html(result['week22']); $(e.target).parents("tr").find(".week22").find(".payp30_dash").html(result['week22']); }
        if(jQuery.inArray("23", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week23").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week23").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week23']+'" title="Liability Value ('+result['week23']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>');  }} else { $(e.target).parents("tr").find(".week23").find("a").html(result['week23']); $(e.target).parents("tr").find(".week23").find(".payp30_dash").html(result['week23']); }
        if(jQuery.inArray("24", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week24").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week24").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week24']+'" title="Liability Value ('+result['week24']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week24").find("a").html(result['week24']); $(e.target).parents("tr").find(".week24").find(".payp30_dash").html(result['week24']); }
        if(jQuery.inArray("25", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week25").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week25").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week25']+'" title="Liability Value ('+result['week25']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week25").find("a").html(result['week25']); $(e.target).parents("tr").find(".week25").find(".payp30_dash").html(result['week25']); }
        if(jQuery.inArray("26", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week26").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week26").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week26']+'" title="Liability Value ('+result['week26']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week26").find("a").html(result['week26']); $(e.target).parents("tr").find(".week26").find(".payp30_dash").html(result['week26']); }
        if(jQuery.inArray("27", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week27").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week27").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week27']+'" title="Liability Value ('+result['week27']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week27").find("a").html(result['week27']); $(e.target).parents("tr").find(".week27").find(".payp30_dash").html(result['week27']); }
        if(jQuery.inArray("28", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week28").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week28").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week28']+'" title="Liability Value ('+result['week28']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week28").find("a").html(result['week28']); $(e.target).parents("tr").find(".week28").find(".payp30_dash").html(result['week28']); }
        if(jQuery.inArray("29", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week29").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week29").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week29']+'" title="Liability Value ('+result['week29']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week29").find("a").html(result['week29']); $(e.target).parents("tr").find(".week29").find(".payp30_dash").html(result['week29']); }
        if(jQuery.inArray("30", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week30").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week30").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week30']+'" title="Liability Value ('+result['week30']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week30").find("a").html(result['week30']); $(e.target).parents("tr").find(".week30").find(".payp30_dash").html(result['week30']); }
        if(jQuery.inArray("31", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week31").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week31").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week31']+'" title="Liability Value ('+result['week31']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week31").find("a").html(result['week31']); $(e.target).parents("tr").find(".week31").find(".payp30_dash").html(result['week31']); }
        if(jQuery.inArray("32", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week32").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week32").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week32']+'" title="Liability Value ('+result['week32']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week32").find("a").html(result['week32']); $(e.target).parents("tr").find(".week32").find(".payp30_dash").html(result['week32']); }
        if(jQuery.inArray("33", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week33").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week33").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week33']+'" title="Liability Value ('+result['week33']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week33").find("a").html(result['week33']); $(e.target).parents("tr").find(".week33").find(".payp30_dash").html(result['week33']); }
        if(jQuery.inArray("34", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week34").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week34").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week34']+'" title="Liability Value ('+result['week34']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week34").find("a").html(result['week34']); $(e.target).parents("tr").find(".week34").find(".payp30_dash").html(result['week34']); }
        if(jQuery.inArray("35", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week35").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week35").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week35']+'" title="Liability Value ('+result['week35']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week35").find("a").html(result['week35']); $(e.target).parents("tr").find(".week35").find(".payp30_dash").html(result['week35']); }
        if(jQuery.inArray("36", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week36").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week36").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week36']+'" title="Liability Value ('+result['week36']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week36").find("a").html(result['week36']); $(e.target).parents("tr").find(".week36").find(".payp30_dash").html(result['week36']); }
        if(jQuery.inArray("37", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week37").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week37").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week37']+'" title="Liability Value ('+result['week37']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week37").find("a").html(result['week37']); $(e.target).parents("tr").find(".week37").find(".payp30_dash").html(result['week37']); }
        if(jQuery.inArray("38", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week38").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week38").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week38']+'" title="Liability Value ('+result['week38']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week38").find("a").html(result['week38']); $(e.target).parents("tr").find(".week38").find(".payp30_dash").html(result['week38']); }
        if(jQuery.inArray("39", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week39").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week39").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week39']+'" title="Liability Value ('+result['week39']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week39").find("a").html(result['week39']); $(e.target).parents("tr").find(".week39").find(".payp30_dash").html(result['week39']); }
        if(jQuery.inArray("40", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week40").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week40").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week40']+'" title="Liability Value ('+result['week40']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week40").find("a").html(result['week40']); $(e.target).parents("tr").find(".week40").find(".payp30_dash").html(result['week40']); }
        if(jQuery.inArray("41", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week41").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week41").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week41']+'" title="Liability Value ('+result['week41']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week41").find("a").html(result['week41']); $(e.target).parents("tr").find(".week41").find(".payp30_dash").html(result['week41']); }
        if(jQuery.inArray("42", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week42").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week42").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week42']+'" title="Liability Value ('+result['week42']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week42").find("a").html(result['week42']); $(e.target).parents("tr").find(".week42").find(".payp30_dash").html(result['week42']); }
        if(jQuery.inArray("43", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week43").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week43").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week43']+'" title="Liability Value ('+result['week43']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week43").find("a").html(result['week43']); $(e.target).parents("tr").find(".week43").find(".payp30_dash").html(result['week43']); }
        if(jQuery.inArray("44", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week44").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week44").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week44']+'" title="Liability Value ('+result['week44']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week44").find("a").html(result['week44']); $(e.target).parents("tr").find(".week44").find(".payp30_dash").html(result['week44']); }
        if(jQuery.inArray("45", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week45").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week45").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week45']+'" title="Liability Value ('+result['week45']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week45").find("a").html(result['week45']); $(e.target).parents("tr").find(".week45").find(".payp30_dash").html(result['week45']); }
        if(jQuery.inArray("46", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week46").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week46").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week46']+'" title="Liability Value ('+result['week46']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week46").find("a").html(result['week46']); $(e.target).parents("tr").find(".week46").find(".payp30_dash").html(result['week46']); }
        if(jQuery.inArray("47", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week47").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week47").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week47']+'" title="Liability Value ('+result['week47']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week47").find("a").html(result['week47']); $(e.target).parents("tr").find(".week47").find(".payp30_dash").html(result['week47']); }
        if(jQuery.inArray("48", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week48").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week48").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week48']+'" title="Liability Value ('+result['week48']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week48").find("a").html(result['week48']); $(e.target).parents("tr").find(".week48").find(".payp30_dash").html(result['week48']); }
        if(jQuery.inArray("49", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week49").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week49").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week49']+'" title="Liability Value ('+result['week49']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week49").find("a").html(result['week49']); $(e.target).parents("tr").find(".week49").find(".payp30_dash").html(result['week49']); }
        if(jQuery.inArray("50", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week50").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week50").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week50']+'" title="Liability Value ('+result['week50']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week50").find("a").html(result['week50']); $(e.target).parents("tr").find(".week50").find(".payp30_dash").html(result['week50']); }
        if(jQuery.inArray("51", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week51").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week51").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week51']+'" title="Liability Value ('+result['week51']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week51").find("a").html(result['week51']); $(e.target).parents("tr").find(".week51").find(".payp30_dash").html(result['week51']); }
        if(jQuery.inArray("52", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week52").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week52").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week52']+'" title="Liability Value ('+result['week52']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week52").find("a").html(result['week52']); $(e.target).parents("tr").find(".week52").find(".payp30_dash").html(result['week52']); }
        if(jQuery.inArray("53", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week53").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week53").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week53']+'" title="Liability Value ('+result['week53']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week53").find("a").html(result['week53']); $(e.target).parents("tr").find(".week53").find(".payp30_dash").html(result['week53']); }

        if(jQuery.inArray("1", result['changed_liability_month']) !== -1) { if($(e.target).parents("tr").find(".month1").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".month1").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['month1']+'" title="Liability Value ('+result['month1']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".month1").find("a").html(result['month1']); $(e.target).parents("tr").find(".month1").find(".payp30_dash").html(result['month1']); }
        if(jQuery.inArray("2", result['changed_liability_month']) !== -1) { if($(e.target).parents("tr").find(".month2").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".month2").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['month2']+'" title="Liability Value ('+result['month2']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".month2").find("a").html(result['month2']); $(e.target).parents("tr").find(".month2").find(".payp30_dash").html(result['month2']); }
        if(jQuery.inArray("3", result['changed_liability_month']) !== -1) { if($(e.target).parents("tr").find(".month3").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".month3").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['month3']+'" title="Liability Value ('+result['month3']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".month3").find("a").html(result['month3']); $(e.target).parents("tr").find(".month3").find(".payp30_dash").html(result['month3']); }
        if(jQuery.inArray("4", result['changed_liability_month']) !== -1) { if($(e.target).parents("tr").find(".month4").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".month4").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['month4']+'" title="Liability Value ('+result['month4']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".month4").find("a").html(result['month4']); $(e.target).parents("tr").find(".month4").find(".payp30_dash").html(result['month4']); }
        if(jQuery.inArray("5", result['changed_liability_month']) !== -1) { if($(e.target).parents("tr").find(".month5").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".month5").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['month5']+'" title="Liability Value ('+result['month5']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".month5").find("a").html(result['month5']); $(e.target).parents("tr").find(".month5").find(".payp30_dash").html(result['month5']); }
        if(jQuery.inArray("6", result['changed_liability_month']) !== -1) { if($(e.target).parents("tr").find(".month6").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".month6").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['month6']+'" title="Liability Value ('+result['month6']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".month6").find("a").html(result['month6']); $(e.target).parents("tr").find(".month6").find(".payp30_dash").html(result['month6']); }
        if(jQuery.inArray("7", result['changed_liability_month']) !== -1) { if($(e.target).parents("tr").find(".month7").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".month7").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['month7']+'" title="Liability Value ('+result['month7']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".month7").find("a").html(result['month7']); $(e.target).parents("tr").find(".month7").find(".payp30_dash").html(result['month7']); }
        if(jQuery.inArray("8", result['changed_liability_month']) !== -1) { if($(e.target).parents("tr").find(".month8").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".month8").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['month8']+'" title="Liability Value ('+result['month8']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".month8").find("a").html(result['month8']); $(e.target).parents("tr").find(".month8").find(".payp30_dash").html(result['month8']); }
        if(jQuery.inArray("9", result['changed_liability_month']) !== -1) { if($(e.target).parents("tr").find(".month9").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".month9").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['month9']+'" title="Liability Value ('+result['month9']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".month9").find("a").html(result['month9']); $(e.target).parents("tr").find(".month9").find(".payp30_dash").html(result['month9']); }
        if(jQuery.inArray("10", result['changed_liability_month']) !== -1) { if($(e.target).parents("tr").find(".month10").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".month10").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['month10']+'" title="Liability Value ('+result['month10']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".month10").find("a").html(result['month10']); $(e.target).parents("tr").find(".month10").find(".payp30_dash").html(result['month10']); }
        if(jQuery.inArray("11", result['changed_liability_month']) !== -1) { if($(e.target).parents("tr").find(".month11").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".month11").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['month11']+'" title="Liability Value ('+result['month11']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".month11").find("a").html(result['month11']); $(e.target).parents("tr").find(".month11").find(".payp30_dash").html(result['month11']); }
        if(jQuery.inArray("12", result['changed_liability_month']) !== -1) { if($(e.target).parents("tr").find(".month12").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".month12").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['month12']+'" title="Liability Value ('+result['month12']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".month12").find("a").html(result['month12']); $(e.target).parents("tr").find(".month12").find(".payp30_dash").html(result['month12']); }

        $("body").removeClass("loading");

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
        data:{active:active},
        success: function(result){
            $("body").removeClass("loading"); 
        }
    })
    
});


</script>
<script type="text/javascript">
function doneTyping (ros_value,period_id) {
  $.ajax({
        url:"<?php echo URL::to('user/paye_p30_ros_update')?>",
        type:"post",
        dataType:"json",
        data:{value:ros_value, id:period_id},
        success: function(result) {            
            $(".month_row_"+result['id']).find(".diff_class").val(result['different']);           
        }
      });
}
$(window).keyup(function(e) {
    var valueTimmer;                //timer identifier
    var valueInterval = 500;  //time in ms, 5 second for example
    var $ros_value = $('.ros_class');
    if($(e.target).hasClass('ros_class'))
    {
        var input_val = $(e.target).val();  
        var period_id = $(e.target).attr("data-element");
        clearTimeout(valueTimmer);
        valueTimmer = setTimeout(doneTyping, valueInterval,input_val, period_id);   
    }
});

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
// //setup before functions
// var valueTimmer;                //timer identifier
// var valueInterval = 500;  //time in ms, 5 second for example
// var $ros_value = $('.ros_class');
// //on keyup, start the countdown
// $ros_value.on('keyup', function () {

//   var input_val = $(this).val();  
//   var period_id = $(this).attr("data-element");

//   console.log(e.keyCode);
//   clearTimeout(valueTimmer);
//   valueTimmer = setTimeout(doneTyping, valueInterval,input_val, period_id);
// });
// //on keydown, clear the countdown 
// $ros_value.on('keydown', function () {
//   clearTimeout(valueTimmer);
// });
// //user is "finished typing," do something

</script>
@stop


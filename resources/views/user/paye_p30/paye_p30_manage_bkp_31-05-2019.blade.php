@extends('userheader')
@section('content')
<script src='<?php echo URL::to('assets/js/table-fixed-header_cm.js'); ?>'></script>
<style>
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
</style>
<style type="text/css">
.left_menu{background: #ff0; left: 0px; position: fixed;}
.left_menu li{clear: both; width: 100%;}
.left_menu li a{padding: 10px 15px;}
.left_menu .dropdown-menu{left: 120px; top: 0px;}
.left_menu .dropdown-menu li a{padding: 3px 10px;}
</style>
<div class="left_menu_dropdown" style="width: 150px; height: auto; position: fixed; z-index: 999; top:300px; left: 0px;display:none">
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

            


            <!-- <li class="<?php //if(($segment1 == "p30") || ($segment1 == "p30month_manage")) { echo "active"; } else { echo ""; } ?>"><a href="<?php //echo URL::to('user/p30')?>">P30 Task</a></li> -->
            <!-- <li><a href="<?php echo URL::to('admin')?>">Admin Login</a></li> -->
            <li><a href="<?php echo URL::to('user/logout')?>">Logout</a></li>
          </ul>
</div>
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
            <li class=""><a href="javascript:" style="float:right">Close and Create New Year</a></li>
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
                <div class="pagination" style="float: right;margin-top: 30px; margin-bottom: 0px;">
                  <?php
                  $counts = DB::table('paye_p30_task')->where('paye_year',$year->year_id)->count();
                  $get_pages = $counts / 50;
                  $page_counts = ceil($get_pages);
                  if(isset($_GET['page'])) { $page = $_GET['page']; } else { $page = 1; }
                  if($page_counts == 1) { $nextbutton = "javascript:"; $prevbutton = 'javascript:'; }
                  else { 
                    if($page_counts == $page) { 
                        $prevpage  = $page - 1; 
                        $nextbutton = 'javascript:'; 
                        $prevbutton = URL::to('user/paye_p30_manage/'.base64_encode($year->year_id).'?page=1'); 
                    }
                    elseif($page ==  1){
                        $nextpage  = $page + 1; 
                        $prevpage  = $page - 1; 
                        $nextbutton = URL::to('user/paye_p30_manage/'.base64_encode($year->year_id).'?page='.$page_counts); 
                        $prevbutton = 'javascript:'; 
                    }
                    else{
                        $nextpage  = $page + 1; 
                        $prevpage  = $page - 1; 
                        $nextbutton = URL::to('user/paye_p30_manage/'.base64_encode($year->year_id).'?page='.$page_counts); 
                        $prevbutton = URL::to('user/paye_p30_manage/'.base64_encode($year->year_id).'?page=1'); 
                    }
                  }
                  ?>
                  <a class="prev_page" href="<?php echo $prevbutton; ?>">&laquo;</a>
                  <?php
                  for($i = 1; $i <= $page_counts; $i++) {

                  ?>
                        <a class="<?php if($page == $i) { echo 'active'; } ?>" href="<?php echo URL::to('user/paye_p30_manage/'.base64_encode($year->year_id).'?page='.$i); ?>"><?php echo $i; ?></a>
                  <?php
                  }
                  ?>
                  <a class="next_page" href="<?php echo $nextbutton; ?>">&raquo;</a>
                </div>
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
  $output='';
  if(isset($_GET['page'])) { $page = $_GET['page']; } else { $page = 1; }
  $pageval = $page - 1;
  $i = $pageval * 50;
  $i = $i + 1;
  if(count($payelist)){
    foreach ($payelist as $keytask => $task) {
        
        $level_name = DB::table('p30_tasklevel')->where('id',$task->task_level)->first();

        $output_row='';
        
        $periodlist = DB::table('paye_p30_periods')->where('paye_task', $task->id)->get();
        if(count($periodlist)){
            foreach ($periodlist as $period) { 
                

                    if($task->active_month == $period->month_id){$month_active = 'checked';}else{$month_active = 'false';}

                    if($period->month_id == 1) { $month_name = "Jan"; }
                    if($period->month_id == 2) { $month_name = "Feb"; }
                    if($period->month_id == 3) { $month_name = "Mar"; }
                    if($period->month_id == 4) { $month_name = "Apr"; }
                    if($period->month_id == 5) { $month_name = "May"; }
                    if($period->month_id == 6) { $month_name = "Jun"; }
                    if($period->month_id == 7) { $month_name = "Jul"; }
                    if($period->month_id == 8) { $month_name = "Aug"; }
                    if($period->month_id == 9) { $month_name = "Sep"; }
                    if($period->month_id == 10) { $month_name = "Oct"; }
                    if($period->month_id == 11) { $month_name = "Nov"; }
                    if($period->month_id == 12) { $month_name = "Dec"; }                    

                    if($period->week1 == 0){ 
                        $periodweek1 = '<div class="payp30_dash week1_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=1 && $year->week_to >=1) { $periodweek1.='hide_column_inner'; } else { $periodweek1.=''; } } $periodweek1.='">-</div>';
                    }
                    else{
                        $periodweek1 = '<a href="javascript:" class="payp30_green week1_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=1 && $year->week_to >=1) { $periodweek1.='hide_column_inner'; } else { $periodweek1.=''; } } $periodweek1.=' " value="'.$period->period_id.'" data-element="1">'.number_format_invoice($period->week1).'</a>';
                                           
                    }

                    if($period->week2 == 0){ 
                        $periodweek2 = '<div class="payp30_dash week2_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=2 && $year->week_to >=2) { $periodweek2.='hide_column_inner'; } else { $periodweek2.=''; } } $periodweek2.='">-</div>';
                        
                    }
                    else{
                        $periodweek2 = '<a href="javascript:" class="payp30_green week2_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=2 && $year->week_to >=2) { $periodweek2.='hide_column_inner'; } else { $periodweek2.=''; } } $periodweek2.='" value="'.$period->period_id.'" data-element="2">'.number_format_invoice($period->week2).'</a>';
                    }

                    if($period->week3 == 0){ 
                        $periodweek3 = '<div class="payp30_dash week3_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=3 && $year->week_to >=3) { $periodweek3.='hide_column_inner'; } else { $periodweek3.=''; } } $periodweek3.='">-</div>';
                    }
                    else{
                        $periodweek3 = '<a href="javascript:" class="payp30_green week3_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=3 && $year->week_to >=3) { $periodweek3.='hide_column_inner'; } else { $periodweek3.=''; } } $periodweek3.='"  value="'.$period->period_id.'" data-element="3">'.number_format_invoice($period->week3).'</a>';
                    }

                    if($period->week4 == 0){ 
                        $periodweek4 = '<div class="payp30_dash week4_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=4 && $year->week_to >=4) { $periodweek4.='hide_column_inner'; } else { $periodweek4.=''; } } $periodweek4.='">-</div>';
                    }
                    else{
                        $periodweek4 = '<a href="javascript:" class="payp30_green week4_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=4 && $year->week_to >=4) { $periodweek4.='hide_column_inner'; } else { $periodweek4.=''; } } $periodweek4.='"  value="'.$period->period_id.'" data-element="4">'.number_format_invoice($period->week4).'</a>';
                    }

                    if($period->week5 == 0){ 
                        $periodweek5 = '<div class="payp30_dash week5_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=5 && $year->week_to >=5) { $periodweek5.='hide_column_inner'; } else { $periodweek5.=''; } } $periodweek5.='">-</div>';
                    }
                    else{
                        $periodweek5 = '<a href="javascript:" class="payp30_green week5_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=5 && $year->week_to >=5) { $periodweek5.='hide_column_inner'; } else { $periodweek5.=''; } } $periodweek5.='"  value="'.$period->period_id.'" data-element="5">'.number_format_invoice($period->week5).'</a>';
                    }

                    if($period->week6 == 0){ 
                        $periodweek6 = '<div class="payp30_dash week6_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=6 && $year->week_to >=6) { $periodweek6.='hide_column_inner'; } else { $periodweek6.=''; } } $periodweek6.='">-</div>';
                    }
                    else{
                        $periodweek6 = '<a href="javascript:" class="payp30_green week6_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=6 && $year->week_to >=6) { $periodweek6.='hide_column_inner'; } else { $periodweek6.=''; } } $periodweek6.='"  value="'.$period->period_id.'" data-element="6">'.number_format_invoice($period->week6).'</a>';
                    }

                    if($period->week7 == 0){ 
                        $periodweek7 = '<div class="payp30_dash week7_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=7 && $year->week_to >=7) { $periodweek7.='hide_column_inner'; } else { $periodweek7.=''; } } $periodweek7.='">-</div>';
                    }
                    else{
                        $periodweek7 = '<a href="javascript:" class="payp30_green week7_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=7 && $year->week_to >=7) { $periodweek7.='hide_column_inner'; } else { $periodweek7.=''; } } $periodweek7.='"  value="'.$period->period_id.'" data-element="7">'.number_format_invoice($period->week7).'</a>';
                    }

                    if($period->week8 == 0){ 
                        $periodweek8 = '<div class="payp30_dash week8_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=8 && $year->week_to >=8) { $periodweek8.='hide_column_inner'; } else { $periodweek8.=''; } } $periodweek8.='">-</div>';
                    }
                    else{
                        $periodweek8 = '<a href="javascript:" class="payp30_green week8_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=8 && $year->week_to >=8) { $periodweek8.='hide_column_inner'; } else { $periodweek8.=''; } } $periodweek8.='"  value="'.$period->period_id.'" data-element="8">'.number_format_invoice($period->week8).'</a>';
                    }

                    if($period->week9 == 0){ 
                        $periodweek9 = '<div class="payp30_dash week9_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=9 && $year->week_to >=9) { $periodweek9.='hide_column_inner'; } else { $periodweek9.=''; } } $periodweek9.='">-</div>';
                    }
                    else{
                        $periodweek9 = '<a href="javascript:" class="payp30_green week9_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=9 && $year->week_to >=9) { $periodweek9.='hide_column_inner'; } else { $periodweek9.=''; } } $periodweek9.='"  value="'.$period->period_id.'" data-element="9">'.number_format_invoice($period->week9).'</a>';
                    }

                    if($period->week10 == 0){ 
                        $periodweek10 = '<div class="payp30_dash week10_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=10 && $year->week_to >=10) { $periodweek10.='hide_column_inner'; } else { $periodweek10.=''; } } $periodweek10.='">-</div>';
                    }
                    else{
                        $periodweek10 = '<a href="javascript:" class="payp30_green week10_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=10 && $year->week_to >=10) { $periodweek10.='hide_column_inner'; } else { $periodweek10.=''; } } $periodweek10.='"  value="'.$period->period_id.'" data-element="10">'.number_format_invoice($period->week10).'</a>';
                    }

                    if($period->week11 == 0){ 
                        $periodweek11 = '<div class="payp30_dash week11_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=11 && $year->week_to >=11) { $periodweek11.='hide_column_inner'; } else { $periodweek11.=''; } } $periodweek11.='">-</div>';
                    }
                    else{
                        $periodweek11 = '<a href="javascript:" class="payp30_green week11_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=11 && $year->week_to >=11) { $periodweek11.='hide_column_inner'; } else { $periodweek11.=''; } } $periodweek11.='"  value="'.$period->period_id.'" data-element="11">'.number_format_invoice($period->week11).'</a>';
                    }

                    if($period->week12 == 0){ 
                        $periodweek12 = '<div class="payp30_dash week12_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=12 && $year->week_to >=12) { $periodweek12.='hide_column_inner'; } else { $periodweek12.=''; } } $periodweek12.='">-</div>';
                    }
                    else{
                        $periodweek12 = '<a href="javascript:" class="payp30_green week12_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=12 && $year->week_to >=12) { $periodweek12.='hide_column_inner'; } else { $periodweek12.=''; } } $periodweek12.='"  value="'.$period->period_id.'" data-element="12">'.number_format_invoice($period->week12).'</a>';
                    }

                    if($period->week13 == 0){ 
                        $periodweek13 = '<div class="payp30_dash week13_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=13 && $year->week_to >=13) { $periodweek13.='hide_column_inner'; } else { $periodweek13.=''; } } $periodweek13.='">-</div>';
                    }
                    else{
                        $periodweek13 = '<a href="javascript:" class="payp30_green week13_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=13 && $year->week_to >=13) { $periodweek13.='hide_column_inner'; } else { $periodweek13.=''; } } $periodweek13.='"  value="'.$period->period_id.'" data-element="13">'.number_format_invoice($period->week13).'</a>';
                    }

                    if($period->week14 == 0){ 
                        $periodweek14 = '<div class="payp30_dash week14_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=14 && $year->week_to >=14) { $periodweek14.='hide_column_inner'; } else { $periodweek14.=''; } } $periodweek14.='">-</div>';
                    }
                    else{
                        $periodweek14 = '<a href="javascript:" class="payp30_green week14_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=14 && $year->week_to >=14) { $periodweek14.='hide_column_inner'; } else { $periodweek14.=''; } } $periodweek14.='"  value="'.$period->period_id.'" data-element="14">'.number_format_invoice($period->week14).'</a>';
                    }

                    if($period->week15 == 0){ 
                        $periodweek15 = '<div class="payp30_dash week15_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=15 && $year->week_to >=15) { $periodweek15.='hide_column_inner'; } else { $periodweek15.=''; } } $periodweek15.='">-</div>';
                    }
                    else{
                        $periodweek15 = '<a href="javascript:" class="payp30_green week15_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=15 && $year->week_to >=15) { $periodweek15.='hide_column_inner'; } else { $periodweek15.=''; } } $periodweek15.='"  value="'.$period->period_id.'" data-element="15">'.number_format_invoice($period->week15).'</a>';
                    }

                    if($period->week16 == 0){ 
                        $periodweek16 = '<div class="payp30_dash week16_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=16 && $year->week_to >=16) { $periodweek16.='hide_column_inner'; } else { $periodweek16.=''; } } $periodweek16.='">-</div>';
                    }
                    else{
                        $periodweek16 = '<a href="javascript:" class="payp30_green week16_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=16 && $year->week_to >=16) { $periodweek16.='hide_column_inner'; } else { $periodweek16.=''; } } $periodweek16.='"  value="'.$period->period_id.'" data-element="16">'.number_format_invoice($period->week16).'</a>';
                    }

                    if($period->week17 == 0){ 
                        $periodweek17 = '<div class="payp30_dash week17_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=17 && $year->week_to >=17) { $periodweek17.='hide_column_inner'; } else { $periodweek17.=''; } } $periodweek17.='">-</div>';
                    }
                    else{
                        $periodweek17 = '<a href="javascript:" class="payp30_green week17_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=17 && $year->week_to >=17) { $periodweek17.='hide_column_inner'; } else { $periodweek17.=''; } } $periodweek17.='"  value="'.$period->period_id.'" data-element="17">'.number_format_invoice($period->week17).'</a>';
                    }

                    if($period->week18 == 0){ 
                        $periodweek18 = '<div class="payp30_dash week18_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=18 && $year->week_to >=18) { $periodweek18.='hide_column_inner'; } else { $periodweek18.=''; } } $periodweek18.='">-</div>';
                    }
                    else{
                        $periodweek18 = '<a href="javascript:" class="payp30_green week18_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=18 && $year->week_to >=18) { $periodweek18.='hide_column_inner'; } else { $periodweek18.=''; } } $periodweek18.='"  value="'.$period->period_id.'" data-element="18">'.number_format_invoice($period->week18).'</a>';
                    }

                    if($period->week19 == 0){ 
                        $periodweek19 = '<div class="payp30_dash week19_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=19 && $year->week_to >=19) { $periodweek19.='hide_column_inner'; } else { $periodweek19.=''; } } $periodweek19.='">-</div>';
                    }
                    else{
                        $periodweek19 = '<a href="javascript:" class="payp30_green week19_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=19 && $year->week_to >=19) { $periodweek19.='hide_column_inner'; } else { $periodweek19.=''; } } $periodweek19.='"  value="'.$period->period_id.'" data-element="19">'.number_format_invoice($period->week19).'</a>';
                    }

                    if($period->week20 == 0){ 
                        $periodweek20 = '<div class="payp30_dash week20_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=20 && $year->week_to >=20) { $periodweek20.='hide_column_inner'; } else { $periodweek20.=''; } } $periodweek20.='">-</div>';
                    }
                    else{
                        $periodweek20 = '<a href="javascript:" class="payp30_green week20_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=20 && $year->week_to >=20) { $periodweek20.='hide_column_inner'; } else { $periodweek20.=''; } } $periodweek20.='"  value="'.$period->period_id.'" data-element="20">'.number_format_invoice($period->week20).'</a>';
                    }

                    if($period->week21 == 0){ 
                        $periodweek21 = '<div class="payp30_dash week21_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=21 && $year->week_to >=21) { $periodweek21.='hide_column_inner'; } else { $periodweek21.=''; } } $periodweek21.='">-</div>';
                    }
                    else{
                        $periodweek21 = '<a href="javascript:" class="payp30_green week21_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=21 && $year->week_to >=21) { $periodweek21.='hide_column_inner'; } else { $periodweek21.=''; } } $periodweek21.='"  value="'.$period->period_id.'" data-element="21">'.number_format_invoice($period->week21).'</a>';
                    }

                    if($period->week22 == 0){ 
                        $periodweek22 = '<div class="payp30_dash week22_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=22 && $year->week_to >=22) { $periodweek22.='hide_column_inner'; } else { $periodweek22.=''; } } $periodweek22.='">-</div>';
                    }
                    else{
                        $periodweek22 = '<a href="javascript:" class="payp30_green week22_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=22 && $year->week_to >=22) { $periodweek22.='hide_column_inner'; } else { $periodweek22.=''; } } $periodweek22.='"  value="'.$period->period_id.'" data-element="22">'.number_format_invoice($period->week22).'</a>';
                    }

                    if($period->week23 == 0){ 
                        $periodweek23 = '<div class="payp30_dash week23_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=23 && $year->week_to >=23) { $periodweek23.='hide_column_inner'; } else { $periodweek23.=''; } } $periodweek23.='">-</div>';
                    }
                    else{
                        $periodweek23 = '<a href="javascript:" class="payp30_green week23_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=23 && $year->week_to >=23) { $periodweek23.='hide_column_inner'; } else { $periodweek23.=''; } } $periodweek23.='"  value="'.$period->period_id.'" data-element="23">'.number_format_invoice($period->week23).'</a>';
                    }

                    if($period->week24 == 0){ 
                        $periodweek24 = '<div class="payp30_dash week24_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=24 && $year->week_to >=24) { $periodweek24.='hide_column_inner'; } else { $periodweek24.=''; } } $periodweek24.='">-</div>';
                    }
                    else{
                        $periodweek24 = '<a href="javascript:" class="payp30_green week24_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=24 && $year->week_to >=24) { $periodweek24.='hide_column_inner'; } else { $periodweek24.=''; } } $periodweek24.='"  value="'.$period->period_id.'" data-element="24">'.number_format_invoice($period->week24).'</a>';
                    }

                    if($period->week25 == 0){ 
                        $periodweek25 = '<div class="payp30_dash week25_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=25 && $year->week_to >=25) { $periodweek25.='hide_column_inner'; } else { $periodweek25.=''; } } $periodweek25.='">-</div>';
                    }
                    else{
                        $periodweek25 = '<a href="javascript:" class="payp30_green week25_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=25 && $year->week_to >=25) { $periodweek25.='hide_column_inner'; } else { $periodweek25.=''; } } $periodweek25.='"  value="'.$period->period_id.'" data-element="25">'.number_format_invoice($period->week25).'</a>';
                    }

                    if($period->week26 == 0){ 
                        $periodweek26 = '<div class="payp30_dash week26_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=26 && $year->week_to >=26) { $periodweek26.='hide_column_inner'; } else { $periodweek26.=''; } } $periodweek26.='">-</div>';
                    }
                    else{
                        $periodweek26 = '<a href="javascript:" class="payp30_green week26_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=26 && $year->week_to >=26) { $periodweek26.='hide_column_inner'; } else { $periodweek26.=''; } } $periodweek26.='"  value="'.$period->period_id.'" data-element="26">'.number_format_invoice($period->week26).'</a>';
                    }

                    if($period->week27 == 0){ 
                        $periodweek27 = '<div class="payp30_dash week27_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=27 && $year->week_to >=27) { $periodweek27.='hide_column_inner'; } else { $periodweek27.=''; } } $periodweek27.='">-</div>';
                    }
                    else{
                        $periodweek27 = '<a href="javascript:" class="payp30_green week27_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=27 && $year->week_to >=27) { $periodweek27.='hide_column_inner'; } else { $periodweek27.=''; } } $periodweek27.='"  value="'.$period->period_id.'" data-element="27">'.number_format_invoice($period->week27).'</a>';
                    }

                    if($period->week28 == 0){ 
                        $periodweek28 = '<div class="payp30_dash week28_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=28 && $year->week_to >=28) { $periodweek28.='hide_column_inner'; } else { $periodweek28.=''; } } $periodweek28.='">-</div>';
                    }
                    else{
                        $periodweek28 = '<a href="javascript:" class="payp30_green week28_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=28 && $year->week_to >=28) { $periodweek28.='hide_column_inner'; } else { $periodweek28.=''; } } $periodweek28.='"  value="'.$period->period_id.'" data-element="28">'.number_format_invoice($period->week28).'</a>';
                    }

                    if($period->week29 == 0){ 
                        $periodweek29 = '<div class="payp30_dash week29_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=29 && $year->week_to >=29) { $periodweek29.='hide_column_inner'; } else { $periodweek29.=''; } } $periodweek29.='">-</div>';
                    }
                    else{
                        $periodweek29 = '<a href="javascript:" class="payp30_green week29_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=29 && $year->week_to >=29) { $periodweek29.='hide_column_inner'; } else { $periodweek29.=''; } } $periodweek29.='"  value="'.$period->period_id.'" data-element="29">'.number_format_invoice($period->week29).'</a>';
                    }

                    if($period->week30 == 0){ 
                        $periodweek30 = '<div class="payp30_dash week30_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=30 && $year->week_to >=30) { $periodweek30.='hide_column_inner'; } else { $periodweek30.=''; } } $periodweek30.='">-</div>';
                    }
                    else{
                        $periodweek30 = '<a href="javascript:" class="payp30_green week30_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=30 && $year->week_to >=30) { $periodweek30.='hide_column_inner'; } else { $periodweek30.=''; } } $periodweek30.='"  value="'.$period->period_id.'" data-element="30">'.number_format_invoice($period->week30).'</a>';
                    }

                    if($period->week31 == 0){ 
                        $periodweek31 = '<div class="payp30_dash week31_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=31 && $year->week_to >=31) { $periodweek31.='hide_column_inner'; } else { $periodweek31.=''; } } $periodweek31.='">-</div>';
                    }
                    else{
                        $periodweek31 = '<a href="javascript:" class="payp30_green week31_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=31 && $year->week_to >=31) { $periodweek31.='hide_column_inner'; } else { $periodweek31.=''; } } $periodweek31.='"  value="'.$period->period_id.'" data-element="31">'.number_format_invoice($period->week31).'</a>';
                    }

                    if($period->week32 == 0){ 
                        $periodweek32 = '<div class="payp30_dash week32_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=32 && $year->week_to >=32) { $periodweek32.='hide_column_inner'; } else { $periodweek32.=''; } } $periodweek32.='">-</div>';
                    }
                    else{
                        $periodweek32 = '<a href="javascript:" class="payp30_green week32_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=32 && $year->week_to >=32) { $periodweek32.='hide_column_inner'; } else { $periodweek32.=''; } } $periodweek32.='"  value="'.$period->period_id.'" data-element="32">'.number_format_invoice($period->week32).'</a>';
                    }

                    if($period->week33 == 0){ 
                        $periodweek33 = '<div class="payp30_dash week33_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=33 && $year->week_to >=33) { $periodweek33.='hide_column_inner'; } else { $periodweek33.=''; } } $periodweek33.='">-</div>';
                    }
                    else{
                        $periodweek33 = '<a href="javascript:" class="payp30_green week33_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=33 && $year->week_to >=33) { $periodweek33.='hide_column_inner'; } else { $periodweek33.=''; } } $periodweek33.='"  value="'.$period->period_id.'" data-element="33">'.number_format_invoice($period->week33).'</a>';
                    }

                    if($period->week34 == 0){ 
                        $periodweek34 = '<div class="payp30_dash week34_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=34 && $year->week_to >=34) { $periodweek34.='hide_column_inner'; } else { $periodweek34.=''; } } $periodweek34.='">-</div>';
                    }
                    else{
                        $periodweek34 = '<a href="javascript:" class="payp30_green week34_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=34 && $year->week_to >=34) { $periodweek34.='hide_column_inner'; } else { $periodweek34.=''; } } $periodweek34.='"  value="'.$period->period_id.'" data-element="34">'.number_format_invoice($period->week34).'</a>';
                    }

                    if($period->week35 == 0){ 
                        $periodweek35 = '<div class="payp30_dash week35_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=35 && $year->week_to >=35) { $periodweek35.='hide_column_inner'; } else { $periodweek35.=''; } } $periodweek35.='">-</div>';
                    }
                    else{
                        $periodweek35 = '<a href="javascript:" class="payp30_green week35_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=35 && $year->week_to >=35) { $periodweek35.='hide_column_inner'; } else { $periodweek35.=''; } } $periodweek35.='"  value="'.$period->period_id.'" data-element="35">'.number_format_invoice($period->week35).'</a>';
                    }

                    if($period->week36 == 0){ 
                        $periodweek36 = '<div class="payp30_dash week36_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=36 && $year->week_to >=36) { $periodweek36.='hide_column_inner'; } else { $periodweek36.=''; } } $periodweek36.='">-</div>';
                    }
                    else{
                        $periodweek36 = '<a href="javascript:" class="payp30_green week36_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=36 && $year->week_to >=36) { $periodweek36.='hide_column_inner'; } else { $periodweek36.=''; } } $periodweek36.='"  value="'.$period->period_id.'" data-element="36">'.number_format_invoice($period->week36).'</a>';
                    }

                    if($period->week37 == 0){ 
                        $periodweek37 = '<div class="payp30_dash week37_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=37 && $year->week_to >=37) { $periodweek37.='hide_column_inner'; } else { $periodweek37.=''; } } $periodweek37.='">-</div>';
                    }
                    else{
                        $periodweek37 = '<a href="javascript:" class="payp30_green week37_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=37 && $year->week_to >=37) { $periodweek37.='hide_column_inner'; } else { $periodweek37.=''; } } $periodweek37.='"  value="'.$period->period_id.'" data-element="37">'.number_format_invoice($period->week37).'</a>';
                    }

                    if($period->week38 == 0){ 
                        $periodweek38 = '<div class="payp30_dash week38_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=38 && $year->week_to >=38) { $periodweek38.='hide_column_inner'; } else { $periodweek38.=''; } } $periodweek38.='">-</div>';
                    }
                    else{
                        $periodweek38 = '<a href="javascript:" class="payp30_green week38_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=38 && $year->week_to >=38) { $periodweek38.='hide_column_inner'; } else { $periodweek38.=''; } } $periodweek38.='"  value="'.$period->period_id.'" data-element="38">'.number_format_invoice($period->week38).'</a>';
                    }

                    if($period->week39 == 0){ 
                        $periodweek39 = '<div class="payp30_dash week39_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=39 && $year->week_to >=39) { $periodweek39.='hide_column_inner'; } else { $periodweek39.=''; } } $periodweek39.='">-</div>';
                    }
                    else{
                        $periodweek39 = '<a href="javascript:" class="payp30_green week39_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=39 && $year->week_to >=39) { $periodweek39.='hide_column_inner'; } else { $periodweek39.=''; } } $periodweek39.='"  value="'.$period->period_id.'" data-element="39">'.number_format_invoice($period->week39).'</a>';
                    }

                    if($period->week40 == 0){ 
                        $periodweek40 = '<div class="payp30_dash week40_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=40 && $year->week_to >=40) { $periodweek40.='hide_column_inner'; } else { $periodweek40.=''; } } $periodweek40.='">-</div>';
                    }
                    else{
                        $periodweek40 = '<a href="javascript:" class="payp30_green week40_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=40 && $year->week_to >=40) { $periodweek40.='hide_column_inner'; } else { $periodweek40.=''; } } $periodweek40.='"  value="'.$period->period_id.'" data-element="40">'.number_format_invoice($period->week40).'</a>';
                    }

                    if($period->week41 == 0){ 
                        $periodweek41 = '<div class="payp30_dash week41_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=41 && $year->week_to >=41) { $periodweek41.='hide_column_inner'; } else { $periodweek41.=''; } } $periodweek41.='">-</div>';
                    }
                    else{
                        $periodweek41 = '<a href="javascript:" class="payp30_green week41_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=41 && $year->week_to >=41) { $periodweek41.='hide_column_inner'; } else { $periodweek41.=''; } } $periodweek41.='"  value="'.$period->period_id.'" data-element="41">'.number_format_invoice($period->week41).'</a>';
                    }

                    if($period->week42 == 0){ 
                        $periodweek42 = '<div class="payp30_dash week42_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=42 && $year->week_to >=42) { $periodweek42.='hide_column_inner'; } else { $periodweek42.=''; } } $periodweek42.='">-</div>';
                    }
                    else{
                        $periodweek42 = '<a href="javascript:" class="payp30_green week42_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=42 && $year->week_to >=42) { $periodweek42.='hide_column_inner'; } else { $periodweek42.=''; } } $periodweek42.='"  value="'.$period->period_id.'" data-element="42">'.number_format_invoice($period->week42).'</a>';
                    }

                    if($period->week43 == 0){ 
                        $periodweek43 = '<div class="payp30_dash week43_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=43 && $year->week_to >=43) { $periodweek43.='hide_column_inner'; } else { $periodweek43.=''; } } $periodweek43.='">-</div>';
                    }
                    else{
                        $periodweek43 = '<a href="javascript:" class="payp30_green week43_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=43 && $year->week_to >=43) { $periodweek43.='hide_column_inner'; } else { $periodweek43.=''; } } $periodweek43.='"  value="'.$period->period_id.'" data-element="43">'.number_format_invoice($period->week43).'</a>';
                    }

                    if($period->week44 == 0){ 
                        $periodweek44 = '<div class="payp30_dash week44_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=44 && $year->week_to >=44) { $periodweek44.='hide_column_inner'; } else { $periodweek44.=''; } } $periodweek44.='">-</div>';
                    }
                    else{
                        $periodweek44 = '<a href="javascript:" class="payp30_green week44_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=44 && $year->week_to >=44) { $periodweek44.='hide_column_inner'; } else { $periodweek44.=''; } } $periodweek44.='"  value="'.$period->period_id.'" data-element="44">'.number_format_invoice($period->week44).'</a>';
                    }

                    if($period->week45 == 0){ 
                        $periodweek45 = '<div class="payp30_dash week45_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=45 && $year->week_to >=45) { $periodweek45.='hide_column_inner'; } else { $periodweek45.=''; } } $periodweek45.='">-</div>';
                    }
                    else{
                        $periodweek45 = '<a href="javascript:" class="payp30_green week45_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=45 && $year->week_to >=45) { $periodweek45.='hide_column_inner'; } else { $periodweek45.=''; } } $periodweek45.='"  value="'.$period->period_id.'" data-element="45">'.number_format_invoice($period->week45).'</a>';
                    }

                    if($period->week46 == 0){ 
                        $periodweek46 = '<div class="payp30_dash week46_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=46 && $year->week_to >=46) { $periodweek46.='hide_column_inner'; } else { $periodweek46.=''; } } $periodweek46.='">-</div>';
                    }
                    else{
                        $periodweek46 = '<a href="javascript:" class="payp30_green week46_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=46 && $year->week_to >=46) { $periodweek46.='hide_column_inner'; } else { $periodweek46.=''; } } $periodweek46.='"  value="'.$period->period_id.'" data-element="46">'.number_format_invoice($period->week46).'</a>';
                    }

                    if($period->week47 == 0){ 
                        $periodweek47 = '<div class="payp30_dash week47_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=47 && $year->week_to >=47) { $periodweek47.='hide_column_inner'; } else { $periodweek47.=''; } } $periodweek47.='">-</div>';
                    }
                    else{
                        $periodweek47 = '<a href="javascript:" class="payp30_green week47_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=47 && $year->week_to >=47) { $periodweek47.='hide_column_inner'; } else { $periodweek47.=''; } } $periodweek47.='"  value="'.$period->period_id.'" data-element="47">'.number_format_invoice($period->week47).'</a>';
                    }

                    if($period->week48 == 0){ 
                        $periodweek48 = '<div class="payp30_dash week48_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=48 && $year->week_to >=48) { $periodweek48.='hide_column_inner'; } else { $periodweek48.=''; } } $periodweek48.='">-</div>';
                    }
                    else{
                        $periodweek48 = '<a href="javascript:" class="payp30_green week48_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=48 && $year->week_to >=48) { $periodweek48.='hide_column_inner'; } else { $periodweek48.=''; } } $periodweek48.='"  value="'.$period->period_id.'" data-element="48">'.number_format_invoice($period->week48).'</a>';
                    }

                    if($period->week49 == 0){ 
                        $periodweek49 = '<div class="payp30_dash week49_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=49 && $year->week_to >=49) { $periodweek49.='hide_column_inner'; } else { $periodweek49.=''; } } $periodweek49.='">-</div>';
                    }
                    else{
                        $periodweek49 = '<a href="javascript:" class="payp30_green week49_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=49 && $year->week_to >=49) { $periodweek49.='hide_column_inner'; } else { $periodweek49.=''; } } $periodweek49.='"  value="'.$period->period_id.'" data-element="49">'.number_format_invoice($period->week49).'</a>';
                    }

                    if($period->week50 == 0){ 
                        $periodweek50 = '<div class="payp30_dash week50_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=50 && $year->week_to >=50) { $periodweek50.='hide_column_inner'; } else { $periodweek50.=''; } } $periodweek50.='">-</div>';
                    }
                    else{
                        $periodweek50 = '<a href="javascript:" class="payp30_green week50_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=50 && $year->week_to >=50) { $periodweek50.='hide_column_inner'; } else { $periodweek50.=''; } } $periodweek50.='"  value="'.$period->period_id.'" data-element="50">'.number_format_invoice($period->week50).'</a>';
                    }

                    if($period->week51 == 0){ 
                        $periodweek51 = '<div class="payp30_dash week51_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=51 && $year->week_to >=51) { $periodweek51.='hide_column_inner'; } else { $periodweek51.=''; } } $periodweek51.='">-</div>';
                    }
                    else{
                        $periodweek51 = '<a href="javascript:" class="payp30_green week51_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=51 && $year->week_to >=51) { $periodweek51.='hide_column_inner'; } else { $periodweek51.=''; } } $periodweek51.='"  value="'.$period->period_id.'" data-element="51">'.number_format_invoice($period->week51).'</a>';
                    }

                    if($period->week52 == 0){ 
                        $periodweek52 = '<div class="payp30_dash week52_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=52 && $year->week_to >=52) { $periodweek52.='hide_column_inner'; } else { $periodweek52.=''; } } $periodweek52.='">-</div>';
                    }
                    else{
                        $periodweek52 = '<a href="javascript:" class="payp30_green week52_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=52 && $year->week_to >=52) { $periodweek52.='hide_column_inner'; } else { $periodweek52.=''; } } $periodweek52.='"  value="'.$period->period_id.'" data-element="52">'.number_format_invoice($period->week52).'</a>';
                    }

                    if($period->week53 == 0){ 
                        $periodweek53 = '<div class="payp30_dash week53_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->week_from <=53 && $year->week_to >=53) { $periodweek53.='hide_column_inner'; } else { $periodweek53.=''; } } $periodweek53.='">-</div>';
                    }
                    else{
                        $periodweek53 = '<a href="javascript:" class="payp30_green week53_class_'.$period->period_id.' week_remove '; if($year->show_active == 1) { if($year->week_from <=53 && $year->week_to >=53) { $periodweek53.='hide_column_inner'; } else { $periodweek53.=''; } } $periodweek53.='"  value="'.$period->period_id.'" data-element="53">'.number_format_invoice($period->week53).'</a>';
                    }



                    if($period->month1 == 0){ 
                        $periodmonth1 = '<div class="payp30_dash month1_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->month_from <=1 && $year->month_to >=1) { $periodmonth1.='hide_column_inner'; } else { $periodmonth1.=''; } } $periodmonth1.='">-</div>';
                    }
                    else{
                        $periodmonth1 = '<a href="javascript:" class="payp30_green month1_class_'.$period->period_id.' month_remove '; if($year->show_active == 1) { if($year->month_from <=1 && $year->month_to >=1) { $periodmonth1.='hide_column_inner'; } else { $periodmonth1.=''; } } $periodmonth1.='"  value="'.$period->period_id.'" data-element="1">'.number_format_invoice($period->month1).'</a>';
                    }

                    if($period->month2 == 0){ 
                        $periodmonth2 = '<div class="payp30_dash month2_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->month_from <=2 && $year->month_to >=2) { $periodmonth2.='hide_column_inner'; } else { $periodmonth2.=''; } } $periodmonth2.='">-</div>';
                    }
                    else{
                        $periodmonth2 = '<a href="javascript:" class="payp30_green month2_class_'.$period->period_id.' month_remove '; if($year->show_active == 1) { if($year->month_from <=2 && $year->month_to >=2) { $periodmonth2.='hide_column_inner'; } else { $periodmonth2.=''; } } $periodmonth2.='"  value="'.$period->period_id.'" data-element="2">'.number_format_invoice($period->month2).'</a>';
                    }

                    if($period->month3 == 0){ 
                        $periodmonth3 = '<div class="payp30_dash month3_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->month_from <=3 && $year->month_to >=3) { $periodmonth3.='hide_column_inner'; } else { $periodmonth3.=''; } } $periodmonth3.='">-</div>';
                    }
                    else{
                        $periodmonth3 = '<a href="javascript:" class="payp30_green month3_class_'.$period->period_id.' month_remove '; if($year->show_active == 1) { if($year->month_from <=3 && $year->month_to >=3) { $periodmonth3.='hide_column_inner'; } else { $periodmonth3.=''; } } $periodmonth3.='"  value="'.$period->period_id.'" data-element="3">'.number_format_invoice($period->month3).'</a>';
                    }

                    if($period->month4 == 0){ 
                        $periodmonth4 = '<div class="payp30_dash month4_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->month_from <=4 && $year->month_to >=4) { $periodmonth4.='hide_column_inner'; } else { $periodmonth4.=''; } } $periodmonth4.='">-</div>';
                    }
                    else{
                        $periodmonth4 = '<a href="javascript:" class="payp30_green month4_class_'.$period->period_id.' month_remove '; if($year->show_active == 1) { if($year->month_from <=4 && $year->month_to >=4) { $periodmonth4.='hide_column_inner'; } else { $periodmonth4.=''; } } $periodmonth4.='"  value="'.$period->period_id.'" data-element="4">'.number_format_invoice($period->month4).'</a>';
                    }

                    if($period->month5 == 0){ 
                        $periodmonth5 = '<div class="payp30_dash month5_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->month_from <=5 && $year->month_to >=5) { $periodmonth5.='hide_column_inner'; } else { $periodmonth5.=''; } } $periodmonth5.='">-</div>';
                    }
                    else{
                        $periodmonth5 = '<a href="javascript:" class="payp30_green month5_class_'.$period->period_id.' month_remove '; if($year->show_active == 1) { if($year->month_from <=5 && $year->month_to >=5) { $periodmonth5.='hide_column_inner'; } else { $periodmonth5.=''; } } $periodmonth5.='"  value="'.$period->period_id.'" data-element="5">'.number_format_invoice($period->month5).'</a>';
                    }

                    if($period->month6 == 0){ 
                        $periodmonth6 = '<div class="payp30_dash month6_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->month_from <=6 && $year->month_to >=6) { $periodmonth6.='hide_column_inner'; } else { $periodmonth6.=''; } } $periodmonth6.='">-</div>';
                    }
                    else{
                        $periodmonth6 = '<a href="javascript:" class="payp30_green month6_class_'.$period->period_id.' month_remove '; if($year->show_active == 1) { if($year->month_from <=6 && $year->month_to >=6) { $periodmonth6.='hide_column_inner'; } else { $periodmonth6.=''; } } $periodmonth6.='"  value="'.$period->period_id.'" data-element="6">'.number_format_invoice($period->month6).'</a>';
                    }

                    if($period->month7 == 0){ 
                        $periodmonth7 = '<div class="payp30_dash month7_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->month_from <=7 && $year->month_to >=7) { $periodmonth7.='hide_column_inner'; } else { $periodmonth7.=''; } } $periodmonth7.='">-</div>';
                    }
                    else{
                        $periodmonth7 = '<a href="javascript:" class="payp30_green month7_class_'.$period->period_id.' month_remove '; if($year->show_active == 1) { if($year->month_from <=7 && $year->month_to >=7) { $periodmonth7.='hide_column_inner'; } else { $periodmonth7.=''; } } $periodmonth7.='"  value="'.$period->period_id.'" data-element="7">'.number_format_invoice($period->month7).'</a>';
                    }

                    if($period->month8 == 0){ 
                        $periodmonth8 = '<div class="payp30_dash month8_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->month_from <=8 && $year->month_to >=8) { $periodmonth8.='hide_column_inner'; } else { $periodmonth8.=''; } } $periodmonth8.='">-</div>';
                    }
                    else{
                        $periodmonth8 = '<a href="javascript:" class="payp30_green month8_class_'.$period->period_id.' month_remove '; if($year->show_active == 1) { if($year->month_from <=8 && $year->month_to >=8) { $periodmonth8.='hide_column_inner'; } else { $periodmonth8.=''; } } $periodmonth8.='"  value="'.$period->period_id.'" data-element="8">'.number_format_invoice($period->month8).'</a>';
                    }

                    if($period->month9 == 0){ 
                        $periodmonth9 = '<div class="payp30_dash month9_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->month_from <=9 && $year->month_to >=9) { $periodmonth9.='hide_column_inner'; } else { $periodmonth9.=''; } } $periodmonth9.='">-</div>';
                    }
                    else{
                        $periodmonth9 = '<a href="javascript:" class="payp30_green month9_class_'.$period->period_id.' month_remove '; if($year->show_active == 1) { if($year->month_from <=9 && $year->month_to >=9) { $periodmonth9.='hide_column_inner'; } else { $periodmonth9.=''; } } $periodmonth9.='"  value="'.$period->period_id.'" data-element="9">'.number_format_invoice($period->month9).'</a>';
                    }

                    if($period->month10 == 0){ 
                        $periodmonth10 = '<div class="payp30_dash month10_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->month_from <=10 && $year->month_to >=10) { $periodmonth10.='hide_column_inner'; } else { $periodmonth10.=''; } } $periodmonth10.='">-</div>';
                    }
                    else{
                        $periodmonth10 = '<a href="javascript:" class="payp30_green month10_class_'.$period->period_id.' month_remove '; if($year->show_active == 1) { if($year->month_from <=10 && $year->month_to >=10) { $periodmonth10.='hide_column_inner'; } else { $periodmonth10.=''; } } $periodmonth10.='"  value="'.$period->period_id.'" data-element="10">'.number_format_invoice($period->month10).'</a>';
                    }

                    if($period->month11 == 0){ 
                        $periodmonth11 = '<div class="payp30_dash month11_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->month_from <=11 && $year->month_to >=11) { $periodmonth11.='hide_column_inner'; } else { $periodmonth11.=''; } } $periodmonth11.='">-</div>';
                    }
                    else{
                        $periodmonth11 = '<a href="javascript:" class="payp30_green month11_class_'.$period->period_id.' month_remove '; if($year->show_active == 1) { if($year->month_from <=11 && $year->month_to >=11) { $periodmonth11.='hide_column_inner'; } else { $periodmonth11.=''; } } $periodmonth11.='"  value="'.$period->period_id.'" data-element="11">'.number_format_invoice($period->month11).'</a>';
                    }

                    if($period->month12 == 0){ 
                        $periodmonth12 = '<div class="payp30_dash month12_class_'.$period->period_id.' '; if($year->show_active == 1) { if($year->month_from <=12 && $year->month_to >=12) { $periodmonth12.='hide_column_inner'; } else { $periodmonth12.=''; } } $periodmonth12.='">-</div>';
                    }
                    else{
                        $periodmonth12 = '<a href="javascript:" class="payp30_green month12_class_'.$period->period_id.' month_remove '; if($year->show_active == 1) { if($year->month_from <=12 && $year->month_to >=12) { $periodmonth12.='hide_column_inner'; } else { $periodmonth12.=''; } } $periodmonth12.='"  value="'.$period->period_id.'" data-element="12">'.number_format_invoice($period->month12).'</a>';
                    }


                $output_row.='
                <tr class="month_row_'.$period->period_id.'">
                    <td style="border-right: 1px solid #000 !important; border-left: 1px solid #000 !important; border: 1px solid #fff; border-bottom:1px solid #000"></td>
                    <td style="width: 100px; border-bottom: 0px; text-align: left;">
                    
                    </td>
                    
                    <td style="width: 40px; text-align: right; border-bottom: 0px;">
                        <input type="radio" name="month_name_'.$task->id.'" class="month_class month_class_'.$period->month_id.'" value="'.$period->month_id.'" data-element="'.$period->paye_task.'" '.$month_active.' name="'.$period->paye_task.'"><label>&nbsp;</label>
                    </td>
                    <td style="width: 100px; border-bottom: 0px;">'.$month_name.'-'.$year->year_name.'</td>
                    <td style="width: 150px; border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control ros_class" data-element="'.$period->period_id.'" value="'.number_format_invoice($period->ros_liability).'"></td>
                    <td style="width: 150px; border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control liability_class" value="'.number_format_invoice($period->task_liability).'" readonly></td>
                    <td style="width: 150px; border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control diff_class" value="'.number_format_invoice($period->liability_diff).'" readonly></td>
                    <td colspan="3" style="width: 250px; "><a href="javascript:" class="fa fa-envelope email_unsent email_unsent_'.$period->period_id.'" data-element="'.$period->period_id.'"></a><br/>';
                    if($period->last_email_sent != '0000-00-00 00:00:00') { $email_sent_date = date('d M Y @ H:m', strtotime($period->last_email_sent)); } else { $email_sent_date = ''; }
                    $output_row.=''.$email_sent_date.'<br/></td>
                                        
                    <td align="left" class="payp30_week_bg">'.$periodweek1.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek2.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek3.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek4.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek5.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek6.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek7.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek8.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek9.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek10.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek11.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek12.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek13.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek14.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek15.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek16.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek17.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek18.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek19.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek20.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek21.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek22.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek23.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek24.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek25.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek26.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek27.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek28.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek29.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek30.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek31.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek32.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek33.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek34.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek35.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek36.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek37.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek38.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek39.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek40.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek41.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek42.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek43.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek44.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek45.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek46.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek47.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek48.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek49.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek50.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek51.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek52.'</td>
                    <td align="left" class="payp30_week_bg">'.$periodweek53.'</td>
                    <td align="left" class="payp30_month_bg">'.$periodmonth1.'</td>
                    <td align="left" class="payp30_month_bg">'.$periodmonth2.'</td>
                    <td align="left" class="payp30_month_bg">'.$periodmonth3.'</td>
                    <td align="left" class="payp30_month_bg">'.$periodmonth4.'</td>
                    <td align="left" class="payp30_month_bg">'.$periodmonth5.'</td>
                    <td align="left" class="payp30_month_bg">'.$periodmonth6.'</td>
                    <td align="left" class="payp30_month_bg">'.$periodmonth7.'</td>
                    <td align="left" class="payp30_month_bg">'.$periodmonth8.'</td>
                    <td align="left" class="payp30_month_bg">'.$periodmonth9.'</td>
                    <td align="left" class="payp30_month_bg">'.$periodmonth10.'</td>
                    <td align="left" class="payp30_month_bg">'.$periodmonth11.'</td>
                    <td align="left" class="payp30_month_bg">'.$periodmonth12.'</td>
                </tr>
                ';

                }                
            
        }

        



        if($task->task_level != 0){ $action = $level_name->name; }
        if($task->pay == 0){ $pay = 'No';}else{$pay = 'Yes';}
        if($task->email == 0){ $email = 'No';}else{$email = 'Yes';}       
        

        
        $check_week1 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week1','!=','')->first();
        if($task->week1 == 0){ $week1 = '<div class="payp30_dash">-</div>';}else{$week1 = '<a href="javascript:" 
            class="';if(!count($check_week1)) {  $week1.= 'payp30_black task_class_colum'; }elseif($task->week1 !== $check_week1->week1) {  $week1.= 'payp30_red'; }else{ $week1.= 'payp30_red'; } $week1.=' " value="'.$task->id.'" data-element="1">'; if(!count($check_week1)) { $week1.= number_format_invoice($task->week1); } elseif($task->week1 !== $check_week1->week1) { $week1.= number_format_invoice($check_week1->week1).'<i class="fa fa-info blueinfo" data-element="'.$task->week1.'" title="Liability Value ('.number_format_invoice($task->week1).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week1.= number_format_invoice($task->week1); } $week1.='</a>';}

        $check_week2 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week2','!=','')->first();
        if($task->week2 == 0){ $week2 = '<div class="payp30_dash">-</div>';}else{$week2 = '<a href="javascript:" 
            class="';if(!count($check_week2)) {  $week2.= 'payp30_black task_class_colum'; }elseif($task->week2 !== $check_week2->week2) {  $week2.= 'payp30_red'; }else{ $week2.= 'payp30_red'; } $week2.=' " value="'.$task->id.'" data-element="2">'; if(!count($check_week2)) { $week2.= number_format_invoice($task->week2); } elseif($task->week2 !== $check_week2->week2) { $week2.= number_format_invoice($check_week2->week2).'<i class="fa fa-info blueinfo" data-element="'.$task->week2.'" title="Liability Value ('.number_format_invoice($task->week2).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week2.= number_format_invoice($task->week2); } $week2.='</a>';}

        $check_week3 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week3','!=','')->first();
        if($task->week3 == 0){ $week3 = '<div class="payp30_dash">-</div>';}else{$week3 = '<a href="javascript:" 
            class="';if(!count($check_week3)) {  $week3.= 'payp30_black task_class_colum'; }elseif($task->week3 !== $check_week3->week3) {  $week3.= 'payp30_red'; }else{ $week3.= 'payp30_red'; } $week3.=' " value="'.$task->id.'" data-element="3">'; if(!count($check_week3)) { $week3.= number_format_invoice($task->week3); } elseif($task->week3 !== $check_week3->week3) { $week3.= number_format_invoice($check_week3->week3).'<i class="fa fa-info blueinfo" data-element="'.$task->week2.'" title="Liability Value ('.number_format_invoice($task->week3).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week3.= number_format_invoice($task->week3); } $week3.='</a>';}

        $check_week4 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week4','!=','')->first();
        if($task->week4 == 0){ $week4 = '<div class="payp30_dash">-</div>';}else{$week4 = '<a href="javascript:" 
            class="';if(!count($check_week4)) {  $week4.= 'payp30_black task_class_colum'; }elseif($task->week4 !== $check_week4->week4) {  $week4.= 'payp30_red'; }else{ $week4.= 'payp30_red'; } $week4.=' " value="'.$task->id.'" data-element="4">'; if(!count($check_week4)) { $week4.= number_format_invoice($task->week4); } elseif($task->week4 !== $check_week4->week4) { $week4.= number_format_invoice($check_week4->week4).'<i class="fa fa-info blueinfo" data-element="'.$task->week4.'" title="Liability Value ('.number_format_invoice($task->week4).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week4.= number_format_invoice($task->week4); } $week4.='</a>';}

        $check_week5 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week5','!=','')->first();
        if($task->week5 == 0){ $week5 = '<div class="payp30_dash">-</div>';}else{$week5 = '<a href="javascript:" 
            class="';if(!count($check_week5)) {  $week5.= 'payp30_black task_class_colum'; }elseif($task->week5 !== $check_week5->week5) {  $week5.= 'payp30_red'; }else{ $week5.= 'payp30_red'; } $week5.=' " value="'.$task->id.'" data-element="5">'; if(!count($check_week5)) { $week5.= number_format_invoice($task->week5); } elseif($task->week5 !== $check_week5->week5) { $week5.= number_format_invoice($check_week5->week5).'<i class="fa fa-info blueinfo" data-element="'.$task->week5.'" title="Liability Value ('.number_format_invoice($task->week5).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week5.= number_format_invoice($task->week5); } $week5.='</a>';}

        $check_week6 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week6','!=','')->first();
        if($task->week6 == 0){ $week6 = '<div class="payp30_dash">-</div>';}else{$week6 = '<a href="javascript:" 
            class="';if(!count($check_week6)) {  $week6.= 'payp30_black task_class_colum'; }elseif($task->week6 !== $check_week6->week6) {  $week6.= 'payp30_red'; }else{ $week6.= 'payp30_red'; } $week6.=' " value="'.$task->id.'" data-element="6">'; if(!count($check_week6)) { $week6.= number_format_invoice($task->week6); } elseif($task->week6 !== $check_week6->week6) { $week6.= number_format_invoice($check_week6->week6).'<i class="fa fa-info blueinfo" data-element="'.$task->week6.'" title="Liability Value ('.number_format_invoice($task->week6).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week6.= number_format_invoice($task->week6); } $week6.='</a>';}

        $check_week7 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week7','!=','')->first();
        if($task->week7 == 0){ $week7 = '<div class="payp30_dash">-</div>';}else{$week7 = '<a href="javascript:" 
            class="';if(!count($check_week7)) {  $week7.= 'payp30_black task_class_colum'; }elseif($task->week7 !== $check_week7->week7) {  $week7.= 'payp30_red'; }else{ $week7.= 'payp30_red'; } $week7.=' " value="'.$task->id.'" data-element="7">'; if(!count($check_week7)) { $week7.= number_format_invoice($task->week7); } elseif($task->week7 !== $check_week7->week7) { $week7.= number_format_invoice($check_week7->week7).'<i class="fa fa-info blueinfo" data-element="'.$task->week7.'" title="Liability Value ('.number_format_invoice($task->week7).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week7.= number_format_invoice($task->week7); } $week7.='</a>';}

        $check_week8 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week8','!=','')->first();
        if($task->week8 == 0){ $week8 = '<div class="payp30_dash">-</div>';}else{$week8 = '<a href="javascript:" 
            class="';if(!count($check_week8)) {  $week8.= 'payp30_black task_class_colum'; }elseif($task->week8 !== $check_week8->week8) {  $week8.= 'payp30_red'; }else{ $week8.= 'payp30_red'; } $week8.=' " value="'.$task->id.'" data-element="8">'; if(!count($check_week8)) { $week8.= number_format_invoice($task->week8); } elseif($task->week8 !== $check_week8->week8) { $week8.= number_format_invoice($check_week8->week8).'<i class="fa fa-info blueinfo" data-element="'.$task->week8.'" title="Liability Value ('.number_format_invoice($task->week8).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week8.= number_format_invoice($task->week8); } $week8.='</a>';}

        $check_week9 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week9','!=','')->first();
        if($task->week9 == 0){ $week9 = '<div class="payp30_dash">-</div>';}else{$week9 = '<a href="javascript:" 
            class="';if(!count($check_week9)) {  $week9.= 'payp30_black task_class_colum'; }elseif($task->week9 !== $check_week9->week9) {  $week9.= 'payp30_red'; }else{ $week9.= 'payp30_red'; } $week9.=' " value="'.$task->id.'" data-element="9">'; if(!count($check_week9)) { $week9.= number_format_invoice($task->week9); } elseif($task->week9 !== $check_week9->week9) { $week9.= number_format_invoice($check_week9->week9).'<i class="fa fa-info blueinfo" data-element="'.$task->week9.'" title="Liability Value ('.number_format_invoice($task->week9).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week9.= number_format_invoice($task->week9); } $week9.='</a>';}

        $check_week10 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week10','!=','')->first();
        if($task->week10 == 0){ $week10 = '<div class="payp30_dash">-</div>';}else{$week10 = '<a href="javascript:" 
            class="';if(!count($check_week10)) {  $week10.= 'payp30_black task_class_colum'; }elseif($task->week10 !== $check_week10->week10) {  $week10.= 'payp30_red'; }else{ $week10.= 'payp30_red'; } $week10.=' " value="'.$task->id.'" data-element="10">'; if(!count($check_week10)) { $week10.= number_format_invoice($task->week10); } elseif($task->week10 !== $check_week10->week10) { $week10.= number_format_invoice($check_week10->week10).'<i class="fa fa-info blueinfo" data-element="'.$task->week10.'" title="Liability Value ('.number_format_invoice($task->week10).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week10.= number_format_invoice($task->week10); } $week10.='</a>';}

        $check_week11 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week11','!=','')->first();
        if($task->week11 == 0){ $week11 = '<div class="payp30_dash">-</div>';}else{$week11 = '<a href="javascript:" 
            class="';if(!count($check_week11)) {  $week11.= 'payp30_black task_class_colum'; }elseif($task->week11 !== $check_week11->week11) {  $week11.= 'payp30_red'; }else{ $week11.= 'payp30_red'; } $week11.=' " value="'.$task->id.'" data-element="11">'; if(!count($check_week11)) { $week11.= number_format_invoice($task->week11); } elseif($task->week11 !== $check_week11->week11) { $week11.= number_format_invoice($check_week11->week11).'<i class="fa fa-info blueinfo" data-element="'.$task->week11.'" title="Liability Value ('.number_format_invoice($task->week11).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week11.= number_format_invoice($task->week11); } $week11.='</a>';}

        $check_week12 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week12','!=','')->first();
        if($task->week12 == 0){ $week12 = '<div class="payp30_dash">-</div>';}else{$week12 = '<a href="javascript:" 
            class="';if(!count($check_week12)) {  $week12.= 'payp30_black task_class_colum'; }elseif($task->week12 !== $check_week12->week12) {  $week12.= 'payp30_red'; }else{ $week12.= 'payp30_red'; } $week12.=' " value="'.$task->id.'" data-element="12">'; if(!count($check_week12)) { $week12.= number_format_invoice($task->week12); } elseif($task->week12 !== $check_week12->week12) { $week12.= number_format_invoice($check_week12->week12).'<i class="fa fa-info blueinfo" data-element="'.$task->week12.'" title="Liability Value ('.number_format_invoice($task->week12).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week12.= number_format_invoice($task->week12); } $week12.='</a>';}

        $check_week13 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week13','!=','')->first();
        if($task->week13 == 0){ $week13 = '<div class="payp30_dash">-</div>';}else{$week13 = '<a href="javascript:" 
            class="';if(!count($check_week13)) {  $week13.= 'payp30_black task_class_colum'; }elseif($task->week13 !== $check_week13->week13) {  $week13.= 'payp30_red'; }else{ $week13.= 'payp30_red'; } $week13.=' " value="'.$task->id.'" data-element="13">'; if(!count($check_week13)) { $week13.= number_format_invoice($task->week13); } elseif($task->week13 !== $check_week13->week13) { $week13.= number_format_invoice($check_week13->week13).'<i class="fa fa-info blueinfo" data-element="'.$task->week13.'" title="Liability Value ('.number_format_invoice($task->week13).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week13.= number_format_invoice($task->week13); } $week13.='</a>';}

        $check_week14 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week14','!=','')->first();
        if($task->week14 == 0){ $week14 = '<div class="payp30_dash">-</div>';}else{$week14 = '<a href="javascript:" 
            class="';if(!count($check_week14)) {  $week14.= 'payp30_black task_class_colum'; }elseif($task->week14 !== $check_week14->week14) {  $week14.= 'payp30_red'; }else{ $week14.= 'payp30_red'; } $week14.=' " value="'.$task->id.'" data-element="14">'; if(!count($check_week14)) { $week14.= number_format_invoice($task->week14); } elseif($task->week14 !== $check_week14->week14) { $week14.= number_format_invoice($check_week14->week14).'<i class="fa fa-info blueinfo" data-element="'.$task->week14.'" title="Liability Value ('.number_format_invoice($task->week14).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week14.= number_format_invoice($task->week14); } $week14.='</a>';}

        $check_week15 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week15','!=','')->first();
        if($task->week15 == 0){ $week15 = '<div class="payp30_dash">-</div>';}else{$week15 = '<a href="javascript:" 
            class="';if(!count($check_week15)) {  $week15.= 'payp30_black task_class_colum'; }elseif($task->week15 !== $check_week15->week15) {  $week15.= 'payp30_red'; }else{ $week15.= 'payp30_red'; } $week15.=' " value="'.$task->id.'" data-element="15">'; if(!count($check_week15)) { $week15.= number_format_invoice($task->week15); } elseif($task->week15 !== $check_week15->week15) { $week15.= number_format_invoice($check_week15->week15).'<i class="fa fa-info blueinfo" data-element="'.$task->week15.'" title="Liability Value ('.number_format_invoice($task->week15).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week15.= number_format_invoice($task->week15); } $week15.='</a>';}

        $check_week16 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week16','!=','')->first();
        if($task->week16 == 0){ $week16 = '<div class="payp30_dash">-</div>';}else{$week16 = '<a href="javascript:" 
            class="';if(!count($check_week16)) {  $week16.= 'payp30_black task_class_colum'; }elseif($task->week16 !== $check_week16->week16) {  $week16.= 'payp30_red'; }else{ $week16.= 'payp30_red'; } $week16.=' " value="'.$task->id.'" data-element="16">'; if(!count($check_week16)) { $week16.= number_format_invoice($task->week16); } elseif($task->week16 !== $check_week16->week16) { $week16.= number_format_invoice($check_week16->week16).'<i class="fa fa-info blueinfo" data-element="'.$task->week16.'" title="Liability Value ('.number_format_invoice($task->week16).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week16.= number_format_invoice($task->week16); } $week16.='</a>';}

        $check_week17 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week17','!=','')->first();
        if($task->week17 == 0){ $week17 = '<div class="payp30_dash">-</div>';}else{$week17 = '<a href="javascript:" 
            class="';if(!count($check_week17)) {  $week17.= 'payp30_black task_class_colum'; }elseif($task->week17 !== $check_week17->week17) {  $week17.= 'payp30_red'; }else{ $week17.= 'payp30_red'; } $week17.=' " value="'.$task->id.'" data-element="17">'; if(!count($check_week17)) { $week17.= number_format_invoice($task->week17); } elseif($task->week17 !== $check_week17->week17) { $week17.= number_format_invoice($check_week17->week17).'<i class="fa fa-info blueinfo" data-element="'.$task->week17.'" title="Liability Value ('.number_format_invoice($task->week17).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week17.= number_format_invoice($task->week17); } $week17.='</a>';}

        $check_week18 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week18','!=','')->first();
        if($task->week18 == 0){ $week18 = '<div class="payp30_dash">-</div>';}else{$week18 = '<a href="javascript:" 
            class="';if(!count($check_week18)) {  $week18.= 'payp30_black task_class_colum'; }elseif($task->week18 !== $check_week18->week18) {  $week18.= 'payp30_red'; }else{ $week18.= 'payp30_red'; } $week18.=' " value="'.$task->id.'" data-element="18">'; if(!count($check_week18)) { $week18.= number_format_invoice($task->week18); } elseif($task->week18 !== $check_week18->week18) { $week18.= number_format_invoice($check_week18->week18).'<i class="fa fa-info blueinfo" data-element="'.$task->week18.'" title="Liability Value ('.number_format_invoice($task->week18).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week18.= number_format_invoice($task->week18); } $week18.='</a>';}


        $check_week19 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week19','!=','')->first();
        if($task->week19 == 0){ $week19 = '<div class="payp30_dash">-</div>';}else{$week19 = '<a href="javascript:" 
            class="';if(!count($check_week19)) {  $week19.= 'payp30_black task_class_colum'; }elseif($task->week19 !== $check_week19->week19) {  $week19.= 'payp30_red'; }else{ $week19.= 'payp30_red'; } $week19.=' " value="'.$task->id.'" data-element="19">'; if(!count($check_week19)) { $week19.= number_format_invoice($task->week19); } elseif($task->week19 !== $check_week19->week19) { $week19.= number_format_invoice($check_week19->week19).'<i class="fa fa-info blueinfo" data-element="'.$task->week19.'" title="Liability Value ('.number_format_invoice($task->week19).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week19.= number_format_invoice($task->week19); } $week19.='</a>';}

        $check_week20 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week20','!=','')->first();
        if($task->week20 == 0){ $week20 = '<div class="payp30_dash">-</div>';}else{$week20 = '<a href="javascript:" 
            class="';if(!count($check_week20)) {  $week20.= 'payp30_black task_class_colum'; }elseif($task->week20 !== $check_week20->week20) {  $week20.= 'payp30_red'; }else{ $week20.= 'payp30_red'; } $week20.=' " value="'.$task->id.'" data-element="20">'; if(!count($check_week20)) { $week20.= number_format_invoice($task->week20); } elseif($task->week20 !== $check_week20->week20) { $week20.= number_format_invoice($check_week20->week20).'<i class="fa fa-info blueinfo" data-element="'.$task->week20.'" title="Liability Value ('.number_format_invoice($task->week20).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week20.= number_format_invoice($task->week20); } $week20.='</a>';}

        $check_week21 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week21','!=','')->first();
        if($task->week21 == 0){ $week21 = '<div class="payp30_dash">-</div>';}else{$week21 = '<a href="javascript:" 
            class="';if(!count($check_week21)) {  $week21.= 'payp30_black task_class_colum'; }elseif($task->week21 !== $check_week21->week21) {  $week21.= 'payp30_red'; }else{ $week21.= 'payp30_red'; } $week21.=' " value="'.$task->id.'" data-element="21">'; if(!count($check_week21)) { $week21.= number_format_invoice($task->week21); } elseif($task->week21 !== $check_week21->week21) { $week21.= number_format_invoice($check_week21->week21).'<i class="fa fa-info blueinfo" data-element="'.$task->week21.'" title="Liability Value ('.number_format_invoice($task->week21).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week21.= number_format_invoice($task->week21); } $week21.='</a>';}

        $check_week22 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week22','!=','')->first();
        if($task->week22 == 0){ $week22 = '<div class="payp30_dash">-</div>';}else{$week22 = '<a href="javascript:" 
            class="';if(!count($check_week22)) {  $week22.= 'payp30_black task_class_colum'; }elseif($task->week22 !== $check_week22->week22) {  $week22.= 'payp30_red'; }else{ $week22.= 'payp30_red'; } $week22.=' " value="'.$task->id.'" data-element="22">'; if(!count($check_week22)) { $week22.= number_format_invoice($task->week22); } elseif($task->week22 !== $check_week22->week22) { $week22.= number_format_invoice($check_week22->week22).'<i class="fa fa-info blueinfo" data-element="'.$task->week22.'" title="Liability Value ('.number_format_invoice($task->week22).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week22.= number_format_invoice($task->week22); } $week22.='</a>';}

        $check_week23 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week23','!=','')->first();
        if($task->week23 == 0){ $week23 = '<div class="payp30_dash">-</div>';}else{$week23 = '<a href="javascript:" 
            class="';if(!count($check_week23)) {  $week23.= 'payp30_black task_class_colum'; }elseif($task->week23 !== $check_week23->week23) {  $week23.= 'payp30_red'; }else{ $week23.= 'payp30_red'; } $week23.=' " value="'.$task->id.'" data-element="23">'; if(!count($check_week23)) { $week23.= number_format_invoice($task->week23); } elseif($task->week23 !== $check_week23->week23) { $week23.= number_format_invoice($check_week23->week23).'<i class="fa fa-info blueinfo" data-element="'.$task->week23.'" title="Liability Value ('.number_format_invoice($task->week23).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week23.= number_format_invoice($task->week23); } $week23.='</a>';}

        $check_week24 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week24','!=','')->first();
        if($task->week24 == 0){ $week24 = '<div class="payp30_dash">-</div>';}else{$week24 = '<a href="javascript:" 
            class="';if(!count($check_week24)) {  $week24.= 'payp30_black task_class_colum'; }elseif($task->week24 !== $check_week24->week24) {  $week24.= 'payp30_red'; }else{ $week24.= 'payp30_red'; } $week24.=' " value="'.$task->id.'" data-element="24">'; if(!count($check_week24)) { $week24.= number_format_invoice($task->week24); } elseif($task->week24 !== $check_week24->week24) { $week24.= number_format_invoice($check_week24->week24).'<i class="fa fa-info blueinfo" data-element="'.$task->week24.'" title="Liability Value ('.number_format_invoice($task->week24).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week24.= number_format_invoice($task->week24); } $week24.='</a>';}

        $check_week25 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week25','!=','')->first();
        if($task->week25 == 0){ $week25 = '<div class="payp30_dash">-</div>';}else{$week25 = '<a href="javascript:" 
            class="';if(!count($check_week25)) {  $week25.= 'payp30_black task_class_colum'; }elseif($task->week25 !== $check_week25->week25) {  $week25.= 'payp30_red'; }else{ $week25.= 'payp30_red'; } $week25.=' " value="'.$task->id.'" data-element="25">'; if(!count($check_week25)) { $week25.= number_format_invoice($task->week25); } elseif($task->week25 !== $check_week25->week25) { $week25.= number_format_invoice($check_week25->week25).'<i class="fa fa-info blueinfo" data-element="'.$task->week25.'" title="Liability Value ('.number_format_invoice($task->week25).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week25.= number_format_invoice($task->week25); } $week25.='</a>';}

        $check_week26 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week26','!=','')->first();
        if($task->week26 == 0){ $week26 = '<div class="payp30_dash">-</div>';}else{$week26 = '<a href="javascript:" 
            class="';if(!count($check_week26)) {  $week26.= 'payp30_black task_class_colum'; }elseif($task->week26 !== $check_week26->week26) {  $week26.= 'payp30_red'; }else{ $week26.= 'payp30_red'; } $week26.=' " value="'.$task->id.'" data-element="26">'; if(!count($check_week26)) { $week26.= number_format_invoice($task->week26); } elseif($task->week26 !== $check_week26->week26) { $week26.= number_format_invoice($check_week26->week26).'<i class="fa fa-info blueinfo" data-element="'.$task->week26.'" title="Liability Value ('.number_format_invoice($task->week26).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week26.= number_format_invoice($task->week26); } $week26.='</a>';}

        $check_week27 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week27','!=','')->first();
        if($task->week27 == 0){ $week27 = '<div class="payp30_dash">-</div>';}else{$week27 = '<a href="javascript:" 
            class="';if(!count($check_week27)) {  $week27.= 'payp30_black task_class_colum'; }elseif($task->week27 !== $check_week27->week27) {  $week27.= 'payp30_red'; }else{ $week27.= 'payp30_red'; } $week27.=' " value="'.$task->id.'" data-element="27">'; if(!count($check_week27)) { $week27.= number_format_invoice($task->week27); } elseif($task->week27 !== $check_week27->week27) { $week27.= number_format_invoice($check_week27->week27).'<i class="fa fa-info blueinfo" data-element="'.$task->week27.'" title="Liability Value ('.number_format_invoice($task->week27).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week27.= number_format_invoice($task->week27); } $week27.='</a>';}

        $check_week28 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week28','!=','')->first();
        if($task->week28 == 0){ $week28 = '<div class="payp30_dash">-</div>';}else{$week28 = '<a href="javascript:" 
            class="';if(!count($check_week28)) {  $week28.= 'payp30_black task_class_colum'; }elseif($task->week28 !== $check_week28->week28) {  $week28.= 'payp30_red'; }else{ $week28.= 'payp30_red'; } $week28.=' " value="'.$task->id.'" data-element="28">'; if(!count($check_week28)) { $week28.= number_format_invoice($task->week28); } elseif($task->week28 !== $check_week28->week28) { $week28.= number_format_invoice($check_week28->week28).'<i class="fa fa-info blueinfo" data-element="'.$task->week28.'" title="Liability Value ('.number_format_invoice($task->week28).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week28.= number_format_invoice($task->week28); } $week28.='</a>';}

        $check_week29 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week29','!=','')->first();
        if($task->week29 == 0){ $week29 = '<div class="payp30_dash">-</div>';}else{$week29 = '<a href="javascript:" 
            class="';if(!count($check_week29)) {  $week29.= 'payp30_black task_class_colum'; }elseif($task->week29 !== $check_week29->week29) {  $week29.= 'payp30_red'; }else{ $week29.= 'payp30_red'; } $week29.=' " value="'.$task->id.'" data-element="29">'; if(!count($check_week29)) { $week29.= number_format_invoice($task->week29); } elseif($task->week29 !== $check_week29->week29) { $week29.= number_format_invoice($check_week29->week29).'<i class="fa fa-info blueinfo" data-element="'.$task->week29.'" title="Liability Value ('.number_format_invoice($task->week29).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week29.= number_format_invoice($task->week29); } $week29.='</a>';}

        $check_week30 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week30','!=','')->first();
        if($task->week30 == 0){ $week30 = '<div class="payp30_dash">-</div>';}else{$week30 = '<a href="javascript:" 
            class="';if(!count($check_week30)) {  $week30.= 'payp30_black task_class_colum'; }elseif($task->week30 !== $check_week30->week30) {  $week30.= 'payp30_red'; }else{ $week30.= 'payp30_red'; } $week30.=' " value="'.$task->id.'" data-element="30">'; if(!count($check_week30)) { $week30.= number_format_invoice($task->week30); } elseif($task->week30 !== $check_week30->week30) { $week30.= number_format_invoice($check_week30->week30).'<i class="fa fa-info blueinfo" data-element="'.$task->week30.'" title="Liability Value ('.number_format_invoice($task->week30).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week30.= number_format_invoice($task->week30); } $week30.='</a>';}

        $check_week31 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week31','!=','')->first();
        if($task->week31 == 0){ $week31 = '<div class="payp30_dash">-</div>';}else{$week31 = '<a href="javascript:" 
            class="';if(!count($check_week31)) {  $week31.= 'payp30_black task_class_colum'; }elseif($task->week31 !== $check_week31->week31) {  $week31.= 'payp30_red'; }else{ $week31.= 'payp30_red'; } $week31.=' " value="'.$task->id.'" data-element="31">'; if(!count($check_week31)) { $week31.= number_format_invoice($task->week31); } elseif($task->week31 !== $check_week31->week31) { $week31.= number_format_invoice($check_week31->week31).'<i class="fa fa-info blueinfo" data-element="'.$task->week31.'" title="Liability Value ('.number_format_invoice($task->week31).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week31.= number_format_invoice($task->week31); } $week31.='</a>';}

        $check_week32 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week32','!=','')->first();
        if($task->week32 == 0){ $week32 = '<div class="payp30_dash">-</div>';}else{$week32 = '<a href="javascript:" 
            class="';if(!count($check_week32)) {  $week32.= 'payp30_black task_class_colum'; }elseif($task->week32 !== $check_week32->week32) {  $week32.= 'payp30_red'; }else{ $week32.= 'payp30_red'; } $week32.=' " value="'.$task->id.'" data-element="32">'; if(!count($check_week32)) { $week32.= number_format_invoice($task->week32); } elseif($task->week32 !== $check_week32->week32) { $week32.= number_format_invoice($check_week32->week32).'<i class="fa fa-info blueinfo" data-element="'.$task->week32.'" title="Liability Value ('.number_format_invoice($task->week32).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week32.= number_format_invoice($task->week32); } $week32.='</a>';}

        $check_week33 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week33','!=','')->first();
        if($task->week33 == 0){ $week33 = '<div class="payp30_dash">-</div>';}else{$week33 = '<a href="javascript:" 
            class="';if(!count($check_week33)) {  $week33.= 'payp30_black task_class_colum'; }elseif($task->week33 !== $check_week33->week33) {  $week33.= 'payp30_red'; }else{ $week33.= 'payp30_red'; } $week33.=' " value="'.$task->id.'" data-element="33">'; if(!count($check_week33)) { $week33.= number_format_invoice($task->week33); } elseif($task->week33 !== $check_week33->week33) { $week33.= number_format_invoice($check_week33->week33).'<i class="fa fa-info blueinfo" data-element="'.$task->week33.'" title="Liability Value ('.number_format_invoice($task->week33).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week33.= number_format_invoice($task->week33); } $week33.='</a>';}

        $check_week34 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week34','!=','')->first();
        if($task->week34 == 0){ $week34 = '<div class="payp30_dash">-</div>';}else{$week34 = '<a href="javascript:" 
            class="';if(!count($check_week34)) {  $week34.= 'payp30_black task_class_colum'; }elseif($task->week34 !== $check_week34->week34) {  $week34.= 'payp30_red'; }else{ $week34.= 'payp30_red'; } $week34.=' " value="'.$task->id.'" data-element="34">'; if(!count($check_week34)) { $week34.= number_format_invoice($task->week34); } elseif($task->week34 !== $check_week34->week34) { $week34.= number_format_invoice($check_week34->week34).'<i class="fa fa-info blueinfo" data-element="'.$task->week34.'" title="Liability Value ('.number_format_invoice($task->week34).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week34.= number_format_invoice($task->week34); } $week34.='</a>';}

        $check_week35 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week35','!=','')->first();
        if($task->week35 == 0){ $week35 = '<div class="payp30_dash">-</div>';}else{$week35 = '<a href="javascript:" 
            class="';if(!count($check_week35)) {  $week35.= 'payp30_black task_class_colum'; }elseif($task->week35 !== $check_week35->week35) {  $week35.= 'payp30_red'; }else{ $week35.= 'payp30_red'; } $week35.=' " value="'.$task->id.'" data-element="35">'; if(!count($check_week35)) { $week35.= number_format_invoice($task->week35); } elseif($task->week35 !== $check_week35->week35) { $week35.= number_format_invoice($check_week35->week35).'<i class="fa fa-info blueinfo" data-element="'.$task->week35.'" title="Liability Value ('.number_format_invoice($task->week35).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week35.= number_format_invoice($task->week35); } $week35.='</a>';}

        $check_week36 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week36','!=','')->first();
        if($task->week36 == 0){ $week36 = '<div class="payp30_dash">-</div>';}else{$week36 = '<a href="javascript:" 
            class="';if(!count($check_week36)) {  $week36.= 'payp30_black task_class_colum'; }elseif($task->week36 !== $check_week36->week36) {  $week36.= 'payp30_red'; }else{ $week36.= 'payp30_red'; } $week36.=' " value="'.$task->id.'" data-element="36">'; if(!count($check_week36)) { $week36.= number_format_invoice($task->week36); } elseif($task->week36 !== $check_week36->week36) { $week36.= number_format_invoice($check_week36->week36).'<i class="fa fa-info blueinfo" data-element="'.$task->week36.'" title="Liability Value ('.number_format_invoice($task->week36).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week36.= number_format_invoice($task->week36); } $week36.='</a>';}

        $check_week37 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week37','!=','')->first();
        if($task->week37 == 0){ $week37 = '<div class="payp30_dash">-</div>';}else{$week37 = '<a href="javascript:" 
            class="';if(!count($check_week37)) {  $week37.= 'payp30_black task_class_colum'; }elseif($task->week37 !== $check_week37->week37) {  $week37.= 'payp30_red'; }else{ $week37.= 'payp30_red'; } $week37.=' " value="'.$task->id.'" data-element="37">'; if(!count($check_week37)) { $week37.= number_format_invoice($task->week37); } elseif($task->week37 !== $check_week37->week37) { $week37.= number_format_invoice($check_week37->week37).'<i class="fa fa-info blueinfo" data-element="'.$task->week37.'" title="Liability Value ('.number_format_invoice($task->week37).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week37.= number_format_invoice($task->week37); } $week37.='</a>';}

        $check_week38 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week38','!=','')->first();
        if($task->week38 == 0){ $week38 = '<div class="payp30_dash">-</div>';}else{$week38 = '<a href="javascript:" 
            class="';if(!count($check_week38)) {  $week38.= 'payp30_black task_class_colum'; }elseif($task->week38 !== $check_week38->week38) {  $week38.= 'payp30_red'; }else{ $week38.= 'payp30_red'; } $week38.=' " value="'.$task->id.'" data-element="38">'; if(!count($check_week38)) { $week38.= number_format_invoice($task->week38); } elseif($task->week38 !== $check_week38->week38) { $week38.= number_format_invoice($check_week38->week38).'<i class="fa fa-info blueinfo" data-element="'.$task->week38.'" title="Liability Value ('.number_format_invoice($task->week38).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week38.= number_format_invoice($task->week38); } $week38.='</a>';}

        $check_week39 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week39','!=','')->first();
        if($task->week39 == 0){ $week39 = '<div class="payp30_dash">-</div>';}else{$week39 = '<a href="javascript:" 
            class="';if(!count($check_week39)) {  $week39.= 'payp30_black task_class_colum'; }elseif($task->week39 !== $check_week39->week39) {  $week39.= 'payp30_red'; }else{ $week39.= 'payp30_red'; } $week39.=' " value="'.$task->id.'" data-element="39">'; if(!count($check_week39)) { $week39.= number_format_invoice($task->week39); } elseif($task->week39 !== $check_week39->week39) { $week39.= number_format_invoice($check_week39->week39).'<i class="fa fa-info blueinfo" data-element="'.$task->week39.'" title="Liability Value ('.number_format_invoice($task->week39).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week39.= number_format_invoice($task->week39); } $week39.='</a>';}

        $check_week40 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week40','!=','')->first();
        if($task->week40 == 0){ $week40 = '<div class="payp30_dash">-</div>';}else{$week40 = '<a href="javascript:" 
            class="';if(!count($check_week40)) {  $week40.= 'payp30_black task_class_colum'; }elseif($task->week40 !== $check_week40->week40) {  $week40.= 'payp30_red'; }else{ $week40.= 'payp30_red'; } $week40.=' " value="'.$task->id.'" data-element="40">'; if(!count($check_week40)) { $week40.= number_format_invoice($task->week40); } elseif($task->week40 !== $check_week40->week40) { $week40.= number_format_invoice($check_week40->week40).'<i class="fa fa-info blueinfo" data-element="'.$task->week40.'" title="Liability Value ('.number_format_invoice($task->week40).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week40.= number_format_invoice($task->week40); } $week40.='</a>';}

        $check_week41 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week41','!=','')->first();
        if($task->week41 == 0){ $week41 = '<div class="payp30_dash">-</div>';}else{$week41 = '<a href="javascript:" 
            class="';if(!count($check_week41)) {  $week41.= 'payp30_black task_class_colum'; }elseif($task->week41 !== $check_week41->week41) {  $week41.= 'payp30_red'; }else{ $week41.= 'payp30_red'; } $week41.=' " value="'.$task->id.'" data-element="41">'; if(!count($check_week41)) { $week41.= number_format_invoice($task->week41); } elseif($task->week41 !== $check_week41->week41) { $week41.= number_format_invoice($check_week41->week41).'<i class="fa fa-info blueinfo" data-element="'.$task->week41.'" title="Liability Value ('.number_format_invoice($task->week41).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week41.= number_format_invoice($task->week41); } $week41.='</a>';}

        $check_week42 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week42','!=','')->first();
        if($task->week42 == 0){ $week42 = '<div class="payp30_dash">-</div>';}else{$week42 = '<a href="javascript:" 
            class="';if(!count($check_week42)) {  $week42.= 'payp30_black task_class_colum'; }elseif($task->week42 !== $check_week42->week42) {  $week42.= 'payp30_red'; }else{ $week42.= 'payp30_red'; } $week42.=' " value="'.$task->id.'" data-element="42">'; if(!count($check_week42)) { $week42.= number_format_invoice($task->week42); } elseif($task->week42 !== $check_week42->week42) { $week42.= number_format_invoice($check_week42->week42).'<i class="fa fa-info blueinfo" data-element="'.$task->week42.'" title="Liability Value ('.number_format_invoice($task->week42).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week42.= number_format_invoice($task->week42); } $week42.='</a>';}

        $check_week43 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week43','!=','')->first();
        if($task->week43 == 0){ $week43 = '<div class="payp30_dash">-</div>';}else{$week43 = '<a href="javascript:" 
            class="';if(!count($check_week43)) {  $week43.= 'payp30_black task_class_colum'; }elseif($task->week43 !== $check_week43->week43) {  $week43.= 'payp30_red'; }else{ $week43.= 'payp30_red'; } $week43.=' " value="'.$task->id.'" data-element="43">'; if(!count($check_week43)) { $week43.= number_format_invoice($task->week43); } elseif($task->week43 !== $check_week43->week43) { $week43.= number_format_invoice($check_week43->week43).'<i class="fa fa-info blueinfo" data-element="'.$task->week43.'" title="Liability Value ('.number_format_invoice($task->week43).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week43.= number_format_invoice($task->week43); } $week43.='</a>';}

        $check_week44 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week44','!=','')->first();
        if($task->week44 == 0){ $week44 = '<div class="payp30_dash">-</div>';}else{$week44 = '<a href="javascript:" 
            class="';if(!count($check_week44)) {  $week44.= 'payp30_black task_class_colum'; }elseif($task->week44 !== $check_week44->week44) {  $week44.= 'payp30_red'; }else{ $week44.= 'payp30_red'; } $week44.=' " value="'.$task->id.'" data-element="44">'; if(!count($check_week44)) { $week44.= number_format_invoice($task->week44); } elseif($task->week44 !== $check_week44->week44) { $week44.= number_format_invoice($check_week44->week44).'<i class="fa fa-info blueinfo" data-element="'.$task->week44.'" title="Liability Value ('.number_format_invoice($task->week44).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week44.= number_format_invoice($task->week44); } $week44.='</a>';}

        $check_week45 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week45','!=','')->first();
        if($task->week45 == 0){ $week45 = '<div class="payp30_dash">-</div>';}else{$week45 = '<a href="javascript:" 
            class="';if(!count($check_week45)) {  $week45.= 'payp30_black task_class_colum'; }elseif($task->week45 !== $check_week45->week45) {  $week45.= 'payp30_red'; }else{ $week45.= 'payp30_red'; } $week45.=' " value="'.$task->id.'" data-element="45">'; if(!count($check_week45)) { $week45.= number_format_invoice($task->week45); } elseif($task->week45 !== $check_week45->week45) { $week45.= number_format_invoice($check_week45->week45).'<i class="fa fa-info blueinfo" data-element="'.$task->week45.'" title="Liability Value ('.number_format_invoice($task->week45).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week45.= number_format_invoice($task->week45); } $week45.='</a>';}

        $check_week46 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week46','!=','')->first();
        if($task->week46 == 0){ $week46 = '<div class="payp30_dash">-</div>';}else{$week46 = '<a href="javascript:" 
            class="';if(!count($check_week46)) {  $week46.= 'payp30_black task_class_colum'; }elseif($task->week46 !== $check_week46->week46) {  $week46.= 'payp30_red'; }else{ $week46.= 'payp30_red'; } $week46.=' " value="'.$task->id.'" data-element="46">'; if(!count($check_week46)) { $week46.= number_format_invoice($task->week46); } elseif($task->week46 !== $check_week46->week46) { $week46.= number_format_invoice($check_week46->week46).'<i class="fa fa-info blueinfo" data-element="'.$task->week46.'" title="Liability Value ('.number_format_invoice($task->week46).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week46.= number_format_invoice($task->week46); } $week46.='</a>';}

        $check_week47 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week47','!=','')->first();
        if($task->week47 == 0){ $week47 = '<div class="payp30_dash">-</div>';}else{$week47 = '<a href="javascript:" 
            class="';if(!count($check_week47)) {  $week47.= 'payp30_black task_class_colum'; }elseif($task->week47 !== $check_week47->week47) {  $week47.= 'payp30_red'; }else{ $week47.= 'payp30_red'; } $week47.=' " value="'.$task->id.'" data-element="47">'; if(!count($check_week47)) { $week47.= number_format_invoice($task->week47); } elseif($task->week47 !== $check_week47->week47) { $week47.= number_format_invoice($check_week47->week47).'<i class="fa fa-info blueinfo" data-element="'.$task->week47.'" title="Liability Value ('.number_format_invoice($task->week47).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week47.= number_format_invoice($task->week47); } $week47.='</a>';}

        $check_week48 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week48','!=','')->first();
        if($task->week48 == 0){ $week48 = '<div class="payp30_dash">-</div>';}else{$week48 = '<a href="javascript:" 
            class="';if(!count($check_week48)) {  $week48.= 'payp30_black task_class_colum'; }elseif($task->week48 !== $check_week48->week48) {  $week48.= 'payp30_red'; }else{ $week48.= 'payp30_red'; } $week48.=' " value="'.$task->id.'" data-element="48">'; if(!count($check_week48)) { $week48.= number_format_invoice($task->week48); } elseif($task->week48 !== $check_week48->week48) { $week48.= number_format_invoice($check_week48->week48).'<i class="fa fa-info blueinfo" data-element="'.$task->week48.'" title="Liability Value ('.number_format_invoice($task->week48).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week48.= number_format_invoice($task->week48); } $week48.='</a>';}

        $check_week49 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week49','!=','')->first();
        if($task->week49 == 0){ $week49 = '<div class="payp30_dash">-</div>';}else{$week49 = '<a href="javascript:" 
            class="';if(!count($check_week49)) {  $week49.= 'payp30_black task_class_colum'; }elseif($task->week49 !== $check_week49->week49) {  $week49.= 'payp30_red'; }else{ $week49.= 'payp30_red'; } $week49.=' " value="'.$task->id.'" data-element="49">'; if(!count($check_week49)) { $week49.= number_format_invoice($task->week49); } elseif($task->week49 !== $check_week49->week49) { $week49.= number_format_invoice($check_week49->week49).'<i class="fa fa-info blueinfo" data-element="'.$task->week49.'" title="Liability Value ('.number_format_invoice($task->week49).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week49.= number_format_invoice($task->week49); } $week49.='</a>';}

        $check_week50 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week50','!=','')->first();
        if($task->week50 == 0){ $week50 = '<div class="payp30_dash">-</div>';}else{$week50 = '<a href="javascript:" 
            class="';if(!count($check_week50)) {  $week50.= 'payp30_black task_class_colum'; }elseif($task->week50 !== $check_week50->week50) {  $week50.= 'payp30_red'; }else{ $week50.= 'payp30_red'; } $week50.=' " value="'.$task->id.'" data-element="50">'; if(!count($check_week50)) { $week50.= number_format_invoice($task->week50); } elseif($task->week50 !== $check_week50->week50) { $week50.= number_format_invoice($check_week50->week50).'<i class="fa fa-info blueinfo" data-element="'.$task->week50.'" title="Liability Value ('.number_format_invoice($task->week50).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week50.= number_format_invoice($task->week50); } $week50.='</a>';}

        $check_week51 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week51','!=','')->first();
        if($task->week51 == 0){ $week51 = '<div class="payp30_dash">-</div>';}else{$week51 = '<a href="javascript:" 
            class="';if(!count($check_week51)) {  $week51.= 'payp30_black task_class_colum'; }elseif($task->week51 !== $check_week51->week51) {  $week51.= 'payp30_red'; }else{ $week51.= 'payp30_red'; } $week51.=' " value="'.$task->id.'" data-element="51">'; if(!count($check_week51)) { $week51.= number_format_invoice($task->week51); } elseif($task->week51 !== $check_week51->week51) { $week51.= number_format_invoice($check_week51->week51).'<i class="fa fa-info blueinfo" data-element="'.$task->week51.'" title="Liability Value ('.number_format_invoice($task->week51).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week51.= number_format_invoice($task->week51); } $week51.='</a>';}

        $check_week52 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week52','!=','')->first();
        if($task->week52 == 0){ $week52 = '<div class="payp30_dash">-</div>';}else{$week52 = '<a href="javascript:" 
            class="';if(!count($check_week52)) {  $week52.= 'payp30_black task_class_colum'; }elseif($task->week52 !== $check_week52->week52) {  $week52.= 'payp30_red'; }else{ $week52.= 'payp30_red'; } $week52.=' " value="'.$task->id.'" data-element="52">'; if(!count($check_week52)) { $week52.= number_format_invoice($task->week52); } elseif($task->week52 !== $check_week52->week52) { $week52.= number_format_invoice($check_week52->week52).'<i class="fa fa-info blueinfo" data-element="'.$task->week52.'" title="Liability Value ('.number_format_invoice($task->week52).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week52.= number_format_invoice($task->week52); } $week52.='</a>';}

        $check_week53 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('week53','!=','')->first();
        if($task->week53 == 0){ $week53 = '<div class="payp30_dash">-</div>';}else{$week53 = '<a href="javascript:" 
            class="';if(!count($check_week53)) {  $week53.= 'payp30_black task_class_colum'; }elseif($task->week53 !== $check_week53->week53) {  $week53.= 'payp30_red'; }else{ $week53.= 'payp30_red'; } $week53.=' " value="'.$task->id.'" data-element="53">'; if(!count($check_week53)) { $week53.= number_format_invoice($task->week53); } elseif($task->week53 !== $check_week53->week53) { $week53.= number_format_invoice($check_week53->week53).'<i class="fa fa-info blueinfo" data-element="'.$task->week53.'" title="Liability Value ('.number_format_invoice($task->week53).') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'; } else { $week53.= number_format_invoice($task->week53); } $week53.='</a>';}




        $check_month1 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('month1','!=','')->first();
        if($task->month1 == 0){ $month1 = '<div class="payp30_dash">-</div>';}else{$month1 = '<a href="javascript:" 
            class="';if(!count($check_month1)) {  $month1.= 'payp30_black task_class_colum_month'; }elseif($task->month1 !== $check_month1->month1) {  $month1.= 'payp30_red'; }else{ $month1.= 'payp30_red'; } $month1.=' " value="'.$task->id.'" data-element="1">'; if(!count($check_month1)) { $month1.= number_format_invoice($task->month1); } elseif($task->month1 !== $check_month1->month1) { $month1.= number_format_invoice($check_month1->month1).'<i class="fa fa-info blueinfo" data-element="'.$task->month1.'" title="Liability Value ('.number_format_invoice($task->month1).') has been changed in this month so if you want to update the value please remove the value which is alloted below"></i>'; } else { $month1.= number_format_invoice($task->month1); } $month1.='</a>';}

        $check_month2 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('month2','!=','')->first();
        if($task->month2 == 0){ $month2 = '<div class="payp30_dash">-</div>';}else{$month2 = '<a href="javascript:" 
            class="';if(!count($check_month2)) {  $month2.= 'payp30_black task_class_colum_month'; }elseif($task->month2 !== $check_month2->month2) {  $month2.= 'payp30_red'; }else{ $month2.= 'payp30_red'; } $month2.=' " value="'.$task->id.'" data-element="2">'; if(!count($check_month2)) { $month2.= number_format_invoice($task->month2); } elseif($task->month2 !== $check_month2->month2) { $month2.= number_format_invoice($check_month2->month2).'<i class="fa fa-info blueinfo" data-element="'.$task->month2.'" title="Liability Value ('.number_format_invoice($task->month2).') has been changed in this month so if you want to update the value please remove the value which is alloted below"></i>'; } else { $month2.= number_format_invoice($task->month2); } $month2.='</a>';}

        $check_month3 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('month3','!=','')->first();
        if($task->month3 == 0){ $month3 = '<div class="payp30_dash">-</div>';}else{$month3 = '<a href="javascript:" 
            class="';if(!count($check_month3)) {  $month3.= 'payp30_black task_class_colum_month'; }elseif($task->month3 !== $check_month3->month3) {  $month3.= 'payp30_red'; }else{ $month3.= 'payp30_red'; } $month3.=' " value="'.$task->id.'" data-element="3">'; if(!count($check_month3)) { $month3.= number_format_invoice($task->month3); } elseif($task->month3 !== $check_month3->month3) { $month3.= number_format_invoice($check_month3->month3).'<i class="fa fa-info blueinfo" data-element="'.$task->month3.'" title="Liability Value ('.number_format_invoice($task->month3).') has been changed in this month so if you want to update the value please remove the value which is alloted below"></i>'; } else { $month3.= number_format_invoice($task->month3); } $month3.='</a>';}

        $check_month4 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('month4','!=','')->first();
        if($task->month4 == 0){ $month4 = '<div class="payp30_dash">-</div>';}else{$month4 = '<a href="javascript:" 
            class="';if(!count($check_month4)) {  $month4.= 'payp30_black task_class_colum_month'; }elseif($task->month4 !== $check_month4->month4) {  $month4.= 'payp30_red'; }else{ $month4.= 'payp30_red'; } $month4.=' " value="'.$task->id.'" data-element="4">'; if(!count($check_month4)) { $month4.= number_format_invoice($task->month4); } elseif($task->month4 !== $check_month4->month4) { $month4.= number_format_invoice($check_month4->month4).'<i class="fa fa-info blueinfo" data-element="'.$task->month4.'" title="Liability Value ('.number_format_invoice($task->month4).') has been changed in this month so if you want to update the value please remove the value which is alloted below"></i>'; } else { $month4.= number_format_invoice($task->month4); } $month4.='</a>';}

        $check_month5 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('month5','!=','')->first();
        if($task->month5 == 0){ $month5 = '<div class="payp30_dash">-</div>';}else{$month5 = '<a href="javascript:" 
            class="';if(!count($check_month5)) {  $month5.= 'payp30_black task_class_colum_month'; }elseif($task->month5 !== $check_month5->month5) {  $month5.= 'payp30_red'; }else{ $month5.= 'payp30_red'; } $month5.=' " value="'.$task->id.'" data-element="5">'; if(!count($check_month5)) { $month5.= number_format_invoice($task->month5); } elseif($task->month5 !== $check_month5->month5) { $month5.= number_format_invoice($check_month5->month5).'<i class="fa fa-info blueinfo" data-element="'.$task->month5.'" title="Liability Value ('.number_format_invoice($task->month5).') has been changed in this month so if you want to update the value please remove the value which is alloted below"></i>'; } else { $month5.= number_format_invoice($task->month5); } $month5.='</a>';}

        $check_month6 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('month6','!=','')->first();
        if($task->month6 == 0){ $month6 = '<div class="payp30_dash">-</div>';}else{$month6 = '<a href="javascript:" 
            class="';if(!count($check_month6)) {  $month6.= 'payp30_black task_class_colum_month'; }elseif($task->month6 !== $check_month6->month6) {  $month6.= 'payp30_red'; }else{ $month6.= 'payp30_red'; } $month6.=' " value="'.$task->id.'" data-element="6">'; if(!count($check_month6)) { $month6.= number_format_invoice($task->month6); } elseif($task->month6 !== $check_month6->month6) { $month6.= number_format_invoice($check_month6->month6).'<i class="fa fa-info blueinfo" data-element="'.$task->month6.'" title="Liability Value ('.number_format_invoice($task->month6).') has been changed in this month so if you want to update the value please remove the value which is alloted below"></i>'; } else { $month6.= number_format_invoice($task->month6); } $month6.='</a>';}

        $check_month7 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('month7','!=','')->first();
        if($task->month7 == 0){ $month7 = '<div class="payp30_dash">-</div>';}else{$month7 = '<a href="javascript:" 
            class="';if(!count($check_month7)) {  $month7.= 'payp30_black task_class_colum_month'; }elseif($task->month7 !== $check_month7->month7) {  $month7.= 'payp30_red'; }else{ $month7.= 'payp30_red'; } $month7.=' " value="'.$task->id.'" data-element="7">'; if(!count($check_month7)) { $month7.= number_format_invoice($task->month7); } elseif($task->month7 !== $check_month7->month7) { $month7.= number_format_invoice($check_month7->month7).'<i class="fa fa-info blueinfo" data-element="'.$task->month7.'" title="Liability Value ('.number_format_invoice($task->month7).') has been changed in this month so if you want to update the value please remove the value which is alloted below"></i>'; } else { $month7.= number_format_invoice($task->month7); } $month7.='</a>';}

        $check_month8 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('month8','!=','')->first();
        if($task->month8 == 0){ $month8 = '<div class="payp30_dash">-</div>';}else{$month8 = '<a href="javascript:" 
            class="';if(!count($check_month8)) {  $month8.= 'payp30_black task_class_colum_month'; }elseif($task->month8 !== $check_month8->month8) {  $month8.= 'payp30_red'; }else{ $month8.= 'payp30_red'; } $month8.=' " value="'.$task->id.'" data-element="8">'; if(!count($check_month8)) { $month8.= number_format_invoice($task->month8); } elseif($task->month8 !== $check_month8->month8) { $month8.= number_format_invoice($check_month8->month8).'<i class="fa fa-info blueinfo" data-element="'.$task->month8.'" title="Liability Value ('.number_format_invoice($task->month8).') has been changed in this month so if you want to update the value please remove the value which is alloted below"></i>'; } else { $month8.= number_format_invoice($task->month8); } $month8.='</a>';}

        $check_month9 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('month9','!=','')->first();
        if($task->month9 == 0){ $month9 = '<div class="payp30_dash">-</div>';}else{$month9 = '<a href="javascript:" 
            class="';if(!count($check_month9)) {  $month9.= 'payp30_black task_class_colum_month'; }elseif($task->month9 !== $check_month9->month9) {  $month9.= 'payp30_red'; }else{ $month9.= 'payp30_red'; } $month9.=' " value="'.$task->id.'" data-element="9">'; if(!count($check_month9)) { $month9.= number_format_invoice($task->month9); } elseif($task->month9 !== $check_month9->month9) { $month9.= number_format_invoice($check_month9->month9).'<i class="fa fa-info blueinfo" data-element="'.$task->month9.'" title="Liability Value ('.number_format_invoice($task->month9).') has been changed in this month so if you want to update the value please remove the value which is alloted below"></i>'; } else { $month9.= number_format_invoice($task->month9); } $month9.='</a>';}

        $check_month10 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('month10','!=','')->first();
        if($task->month10 == 0){ $month10 = '<div class="payp30_dash">-</div>';}else{$month10 = '<a href="javascript:" 
            class="';if(!count($check_month10)) {  $month10.= 'payp30_black task_class_colum_month'; }elseif($task->month10 !== $check_month10->month10) {  $month10.= 'payp30_red'; }else{ $month10.= 'payp30_red'; } $month10.=' " value="'.$task->id.'" data-element="10">'; if(!count($check_month10)) { $month10.= number_format_invoice($task->month10); } elseif($task->month10 !== $check_month10->month10) { $month10.= number_format_invoice($check_month10->month10).'<i class="fa fa-info blueinfo" data-element="'.$task->month10.'" title="Liability Value ('.number_format_invoice($task->month10).') has been changed in this month so if you want to update the value please remove the value which is alloted below"></i>'; } else { $month10.= number_format_invoice($task->month10); } $month10.='</a>';}

        $check_month11 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('month11','!=','')->first();
        if($task->month11 == 0){ $month11 = '<div class="payp30_dash">-</div>';}else{$month11 = '<a href="javascript:" 
            class="';if(!count($check_month11)) {  $month11.= 'payp30_black task_class_colum_month'; }elseif($task->month11 !== $check_month11->month11) {  $month11.= 'payp30_red'; }else{ $month11.= 'payp30_red'; } $month11.=' " value="'.$task->id.'" data-element="11">'; if(!count($check_month11)) { $month11.= number_format_invoice($task->month11); } elseif($task->month11 !== $check_month11->month11) { $month11.= number_format_invoice($check_month11->month11).'<i class="fa fa-info blueinfo" data-element="'.$task->month11.'" title="Liability Value ('.number_format_invoice($task->month11).') has been changed in this month so if you want to update the value please remove the value which is alloted below"></i>'; } else { $month11.= number_format_invoice($task->month11); } $month11.='</a>';}

        $check_month12 = DB::table('paye_p30_periods')->where('paye_task',$task->id)->where('month12','!=','')->first();
        if($task->month12 == 0){ $month12 = '<div class="payp30_dash">-</div>';}else{$month12 = '<a href="javascript:" 
            class="';if(!count($check_month12)) {  $month12.= 'payp30_black task_class_colum_month'; }elseif($task->month12 !== $check_month12->month12) {  $month12.= 'payp30_red'; }else{ $month12.= 'payp30_red'; } $month12.=' " value="'.$task->id.'" data-element="12">'; if(!count($check_month12)) { $month12.= number_format_invoice($task->month12); } elseif($task->month12 !== $check_month12->month12) { $month12.= number_format_invoice($check_month12->month12).'<i class="fa fa-info blueinfo" data-element="'.$task->month12.'" title="Liability Value ('.number_format_invoice($task->month12).') has been changed in this month so if you want to update the value please remove the value which is alloted below"></i>'; } else { $month12.= number_format_invoice($task->month12); } $month12.='</a>';}


    $output.='
      <div class="table-responsive" style="float: left;width:7000px">
        <table class="table_bg table-fixed-header table_paye_p30" style="margin-bottom:20px;width:6700px;margin-top:40px">
              <thead class="header">
                <tr>
                    <th style="border-right: 1px solid #000 !important; border-left: 1px solid #000 !important; border: 1px solid #000;" valign="top">S.No</th>                    
                    <th colspan="7" style="text-align:left">
                        Clients
                    </th>                    
                    <th style="border-bottom: 0px; text-align:center;width:300px;" width="200px">
                        Email Sent                        
                    </th>                    
                    <th style=""></th>
                    <th align="right" class="payp30_week_bg week_td_1 '; if($year->show_active == 1) { if($year->week_from <=1 && $year->week_to >=1) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 1</th>
                    <th align="right" class="payp30_week_bg week_td_2 '; if($year->show_active == 1) { if($year->week_from <=2 && $year->week_to >=2) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 2</th>
                    <th align="right" class="payp30_week_bg week_td_3 '; if($year->show_active == 1) { if($year->week_from <=3 && $year->week_to >=3) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 3</th>
                    <th align="right" class="payp30_week_bg week_td_4 '; if($year->show_active == 1) { if($year->week_from <=4 && $year->week_to >=4) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 4</th>
                    <th align="right" class="payp30_week_bg week_td_5 '; if($year->show_active == 1) { if($year->week_from <=5 && $year->week_to >=5) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 5</th>
                    <th align="right" class="payp30_week_bg week_td_6 '; if($year->show_active == 1) { if($year->week_from <=6 && $year->week_to >=6) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 6</th>
                    <th align="right" class="payp30_week_bg week_td_7 '; if($year->show_active == 1) { if($year->week_from <=7 && $year->week_to >=7) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 7</th>
                    <th align="right" class="payp30_week_bg week_td_8 '; if($year->show_active == 1) { if($year->week_from <=8 && $year->week_to >=8) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 8</th>
                    <th align="right" class="payp30_week_bg week_td_9 '; if($year->show_active == 1) { if($year->week_from <=9 && $year->week_to >=9) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 9</th>
                    <th align="right" class="payp30_week_bg week_td_10 '; if($year->show_active == 1) { if($year->week_from <=10 && $year->week_to >=10) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 10</th>
                    <th align="right" class="payp30_week_bg week_td_11 '; if($year->show_active == 1) { if($year->week_from <=11 && $year->week_to >=11) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 11</th>
                    <th align="right" class="payp30_week_bg week_td_12 '; if($year->show_active == 1) { if($year->week_from <=12 && $year->week_to >=12) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 12</th>
                    <th align="right" class="payp30_week_bg week_td_13 '; if($year->show_active == 1) { if($year->week_from <=13 && $year->week_to >=13) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 13</th>
                    <th align="right" class="payp30_week_bg week_td_14 '; if($year->show_active == 1) { if($year->week_from <=14 && $year->week_to >=14) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 14</th>
                    <th align="right" class="payp30_week_bg week_td_15 '; if($year->show_active == 1) { if($year->week_from <=15 && $year->week_to >=15) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 15</th>
                    <th align="right" class="payp30_week_bg week_td_16 '; if($year->show_active == 1) { if($year->week_from <=16 && $year->week_to >=16) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 16</th>
                    <th align="right" class="payp30_week_bg week_td_17 '; if($year->show_active == 1) { if($year->week_from <=17 && $year->week_to >=17) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 17</th>
                    <th align="right" class="payp30_week_bg week_td_18 '; if($year->show_active == 1) { if($year->week_from <=18 && $year->week_to >=18) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 18</th>
                    <th align="right" class="payp30_week_bg week_td_19 '; if($year->show_active == 1) { if($year->week_from <=19 && $year->week_to >=19) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 19</th>
                    <th align="right" class="payp30_week_bg week_td_20 '; if($year->show_active == 1) { if($year->week_from <=20 && $year->week_to >=20) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 20</th>
                    <th align="right" class="payp30_week_bg week_td_21 '; if($year->show_active == 1) { if($year->week_from <=21 && $year->week_to >=21) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 21</th>
                    <th align="right" class="payp30_week_bg week_td_22 '; if($year->show_active == 1) { if($year->week_from <=22 && $year->week_to >=22) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 22</th>
                    <th align="right" class="payp30_week_bg week_td_23 '; if($year->show_active == 1) { if($year->week_from <=23 && $year->week_to >=23) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 23</th>
                    <th align="right" class="payp30_week_bg week_td_24 '; if($year->show_active == 1) { if($year->week_from <=24 && $year->week_to >=24) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 24</th>
                    <th align="right" class="payp30_week_bg week_td_25 '; if($year->show_active == 1) { if($year->week_from <=25 && $year->week_to >=25) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 25</th>
                    <th align="right" class="payp30_week_bg week_td_26 '; if($year->show_active == 1) { if($year->week_from <=26 && $year->week_to >=26) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 26</th>
                    <th align="right" class="payp30_week_bg week_td_27 '; if($year->show_active == 1) { if($year->week_from <=27 && $year->week_to >=27) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 27</th>
                    <th align="right" class="payp30_week_bg week_td_28 '; if($year->show_active == 1) { if($year->week_from <=28 && $year->week_to >=28) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 28</th>
                    <th align="right" class="payp30_week_bg week_td_29 '; if($year->show_active == 1) { if($year->week_from <=29 && $year->week_to >=29) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 29</th>
                    <th align="right" class="payp30_week_bg week_td_30 '; if($year->show_active == 1) { if($year->week_from <=30 && $year->week_to >=30) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 30</th>
                    <th align="right" class="payp30_week_bg week_td_31 '; if($year->show_active == 1) { if($year->week_from <=31 && $year->week_to >=31) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 31</th>
                    <th align="right" class="payp30_week_bg week_td_32 '; if($year->show_active == 1) { if($year->week_from <=32 && $year->week_to >=32) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 32</th>
                    <th align="right" class="payp30_week_bg week_td_33 '; if($year->show_active == 1) { if($year->week_from <=33 && $year->week_to >=33) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 33</th>
                    <th align="right" class="payp30_week_bg week_td_34 '; if($year->show_active == 1) { if($year->week_from <=34 && $year->week_to >=34) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 34</th>
                    <th align="right" class="payp30_week_bg week_td_35 '; if($year->show_active == 1) { if($year->week_from <=35 && $year->week_to >=35) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 35</th>
                    <th align="right" class="payp30_week_bg week_td_36 '; if($year->show_active == 1) { if($year->week_from <=36 && $year->week_to >=36) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 36</th>
                    <th align="right" class="payp30_week_bg week_td_37 '; if($year->show_active == 1) { if($year->week_from <=37 && $year->week_to >=37) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 37</th>
                    <th align="right" class="payp30_week_bg week_td_38 '; if($year->show_active == 1) { if($year->week_from <=38 && $year->week_to >=38) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 38</th>
                    <th align="right" class="payp30_week_bg week_td_39 '; if($year->show_active == 1) { if($year->week_from <=39 && $year->week_to >=39) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 39</th>
                    <th align="right" class="payp30_week_bg week_td_40 '; if($year->show_active == 1) { if($year->week_from <=40 && $year->week_to >=40) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 40</th>
                    <th align="right" class="payp30_week_bg week_td_41 '; if($year->show_active == 1) { if($year->week_from <=41 && $year->week_to >=41) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 41</th>
                    <th align="right" class="payp30_week_bg week_td_42 '; if($year->show_active == 1) { if($year->week_from <=42 && $year->week_to >=42) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 42</th>
                    <th align="right" class="payp30_week_bg week_td_43 '; if($year->show_active == 1) { if($year->week_from <=43 && $year->week_to >=43) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 43</th>
                    <th align="right" class="payp30_week_bg week_td_44 '; if($year->show_active == 1) { if($year->week_from <=44 && $year->week_to >=44) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 44</th>
                    <th align="right" class="payp30_week_bg week_td_45 '; if($year->show_active == 1) { if($year->week_from <=45 && $year->week_to >=45) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 45</th>
                    <th align="right" class="payp30_week_bg week_td_46 '; if($year->show_active == 1) { if($year->week_from <=46 && $year->week_to >=46) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 46</th>
                    <th align="right" class="payp30_week_bg week_td_47 '; if($year->show_active == 1) { if($year->week_from <=47 && $year->week_to >=47) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 47</th>
                    <th align="right" class="payp30_week_bg week_td_48 '; if($year->show_active == 1) { if($year->week_from <=48 && $year->week_to >=48) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 48</th>
                    <th align="right" class="payp30_week_bg week_td_49 '; if($year->show_active == 1) { if($year->week_from <=49 && $year->week_to >=49) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 49</th>
                    <th align="right" class="payp30_week_bg week_td_50 '; if($year->show_active == 1) { if($year->week_from <=50 && $year->week_to >=50) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 50</th>
                    <th align="right" class="payp30_week_bg week_td_51 '; if($year->show_active == 1) { if($year->week_from <=51 && $year->week_to >=51) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 51</th>
                    <th align="right" class="payp30_week_bg week_td_52 '; if($year->show_active == 1) { if($year->week_from <=52 && $year->week_to >=52) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 52</th>
                    <th align="right" class="payp30_week_bg week_td_53 '; if($year->show_active == 1) { if($year->week_from <=53 && $year->week_to >=53) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Week 53</th>
                    <th align="right" class="payp30_month_bg month_td_1 '; if($year->show_active == 1) { if($year->month_from <=1 && $year->month_to >=1) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Jan '.$year->year_name.'</th>
                    <th align="right" class="payp30_month_bg month_td_2 '; if($year->show_active == 1) { if($year->month_from <=2 && $year->month_to >=2) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Feb '.$year->year_name.'</th>
                    <th align="right" class="payp30_month_bg month_td_3 '; if($year->show_active == 1) { if($year->month_from <=3 && $year->month_to >=3) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Mar '.$year->year_name.'</th>
                    <th align="right" class="payp30_month_bg month_td_4 '; if($year->show_active == 1) { if($year->month_from <=4 && $year->month_to >=4) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Apr '.$year->year_name.'</th>
                    <th align="right" class="payp30_month_bg month_td_5 '; if($year->show_active == 1) { if($year->month_from <=5 && $year->month_to >=5) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">May '.$year->year_name.'</th>
                    <th align="right" class="payp30_month_bg month_td_6 '; if($year->show_active == 1) { if($year->month_from <=6 && $year->month_to >=6) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Jun '.$year->year_name.'</th>
                    <th align="right" class="payp30_month_bg month_td_7 '; if($year->show_active == 1) { if($year->month_from <=7 && $year->month_to >=7) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Jul '.$year->year_name.'</th>
                    <th align="right" class="payp30_month_bg month_td_8 '; if($year->show_active == 1) { if($year->month_from <=8 && $year->month_to >=8) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Aug '.$year->year_name.'</th>
                    <th align="right" class="payp30_month_bg month_td_9 '; if($year->show_active == 1) { if($year->month_from <=9 && $year->month_to >=9) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Sep '.$year->year_name.'</th>
                    <th align="right" class="payp30_month_bg month_td_10 '; if($year->show_active == 1) { if($year->month_from <=10 && $year->month_to >=10) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Oct '.$year->year_name.'</th>
                    <th align="right" class="payp30_month_bg month_td_11 '; if($year->show_active == 1) { if($year->month_from <=11 && $year->month_to >=11) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Nov '.$year->year_name.'</th>
                    <th align="right" class="payp30_month_bg month_td_12 '; if($year->show_active == 1) { if($year->month_from <=12 && $year->month_to >=12) { $output.='hide_column'; } else { $output.=''; } } $output.='" style="text-align:right;">Dec '.$year->year_name.'</th>
                </tr>
              </thead>
              <tbody>
                <tr class="task_row_'.$task->id.'">
                    <td style="border-right: 1px solid #000 !important; border-left: 1px solid #000 !important; border: 1px solid #fff;" valign="top">'.$i.'</td>
                    <td colspan="3"  style="border-bottom: 0px; text-align: left; height:110px;"> 
                      <div style="width:400px; position:absolute; margin-top:-50px;">
                        <b style="font-size:18px;">'.$task->task_name.'</b><br/>
                        Emp No. '.$task->task_enumber.'<br/>
                        Action: '.$action.'<br/>
                        PAY: '.$pay.'<br/>
                        Email: '.$email.'                   
                      </div>
                    </td> 
                    <td style="text-align: center;" valign="bottom">ROS Liability</td>
                    <td style="text-align: center;" valign="bottom">Task Liability</td>
                    <td valign="bottom">Diff</td>
                    
                    <td colspan="2" style="text-align:center; border-right:1px solid #000;"">
                    
                    <input type="hidden" class="active_month_class payetask_'.$task->id.'" value="'.$task->active_month.'" />
                    </td>
                    <td style="padding:0px 10px;"><a href="javascript:"><i class="fa fa-refresh refresh_liability" data-element="'.$task->id.'"></i></a></td>
                    <td align="left" class="payp30_week_bg week1 '; if($year->show_active == 1) { if($year->week_from <=1 && $year->week_to >=1) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week1.'</td>
                    <td align="left" class="payp30_week_bg week2 '; if($year->show_active == 1) { if($year->week_from <=2 && $year->week_to >=2) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week2.'</td>
                    <td align="left" class="payp30_week_bg week3 '; if($year->show_active == 1) { if($year->week_from <=3 && $year->week_to >=3) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week3.'</td>
                    <td align="left" class="payp30_week_bg week4 '; if($year->show_active == 1) { if($year->week_from <=4 && $year->week_to >=4) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week4.'</td>
                    <td align="left" class="payp30_week_bg week5 '; if($year->show_active == 1) { if($year->week_from <=5 && $year->week_to >=5) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week5.'</td>
                    <td align="left" class="payp30_week_bg week6 '; if($year->show_active == 1) { if($year->week_from <=6 && $year->week_to >=6) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week6.'</td>
                    <td align="left" class="payp30_week_bg week7 '; if($year->show_active == 1) { if($year->week_from <=7 && $year->week_to >=7) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week7.'</td>
                    <td align="left" class="payp30_week_bg week8 '; if($year->show_active == 1) { if($year->week_from <=8 && $year->week_to >=8) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week8.'</td>
                    <td align="left" class="payp30_week_bg week9 '; if($year->show_active == 1) { if($year->week_from <=9 && $year->week_to >=9) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week9.'</td>
                    <td align="left" class="payp30_week_bg week10 '; if($year->show_active == 1) { if($year->week_from <=10 && $year->week_to >=10) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week10.'</td>
                    <td align="left" class="payp30_week_bg week11 '; if($year->show_active == 1) { if($year->week_from <=11 && $year->week_to >=11) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week11.'</td>
                    <td align="left" class="payp30_week_bg week12 '; if($year->show_active == 1) { if($year->week_from <=12 && $year->week_to >=12) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week12.'</td>
                    <td align="left" class="payp30_week_bg week13 '; if($year->show_active == 1) { if($year->week_from <=13 && $year->week_to >=13) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week13.'</td>
                    <td align="left" class="payp30_week_bg week14 '; if($year->show_active == 1) { if($year->week_from <=14 && $year->week_to >=14) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week14.'</td>
                    <td align="left" class="payp30_week_bg week15 '; if($year->show_active == 1) { if($year->week_from <=15 && $year->week_to >=15) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week15.'</td>
                    <td align="left" class="payp30_week_bg week16 '; if($year->show_active == 1) { if($year->week_from <=16 && $year->week_to >=16) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week16.'</td>
                    <td align="left" class="payp30_week_bg week17 '; if($year->show_active == 1) { if($year->week_from <=17 && $year->week_to >=17) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week17.'</td>
                    <td align="left" class="payp30_week_bg week18 '; if($year->show_active == 1) { if($year->week_from <=18 && $year->week_to >=18) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week18.'</td>
                    <td align="left" class="payp30_week_bg week19 '; if($year->show_active == 1) { if($year->week_from <=19 && $year->week_to >=19) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week19.'</td>
                    <td align="left" class="payp30_week_bg week20 '; if($year->show_active == 1) { if($year->week_from <=20 && $year->week_to >=20) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week20.'</td>
                    <td align="left" class="payp30_week_bg week21 '; if($year->show_active == 1) { if($year->week_from <=21 && $year->week_to >=21) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week21.'</td>
                    <td align="left" class="payp30_week_bg week22 '; if($year->show_active == 1) { if($year->week_from <=22 && $year->week_to >=22) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week22.'</td>
                    <td align="left" class="payp30_week_bg week23 '; if($year->show_active == 1) { if($year->week_from <=23 && $year->week_to >=23) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week23.'</td>
                    <td align="left" class="payp30_week_bg week24 '; if($year->show_active == 1) { if($year->week_from <=24 && $year->week_to >=24) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week24.'</td>
                    <td align="left" class="payp30_week_bg week25 '; if($year->show_active == 1) { if($year->week_from <=25 && $year->week_to >=25) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week25.'</td>
                    <td align="left" class="payp30_week_bg week26 '; if($year->show_active == 1) { if($year->week_from <=26 && $year->week_to >=26) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week26.'</td>
                    <td align="left" class="payp30_week_bg week27 '; if($year->show_active == 1) { if($year->week_from <=27 && $year->week_to >=27) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week27.'</td>
                    <td align="left" class="payp30_week_bg week28 '; if($year->show_active == 1) { if($year->week_from <=28 && $year->week_to >=28) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week28.'</td>
                    <td align="left" class="payp30_week_bg week29 '; if($year->show_active == 1) { if($year->week_from <=29 && $year->week_to >=29) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week29.'</td>
                    <td align="left" class="payp30_week_bg week30 '; if($year->show_active == 1) { if($year->week_from <=30 && $year->week_to >=30) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week30.'</td>
                    <td align="left" class="payp30_week_bg week31 '; if($year->show_active == 1) { if($year->week_from <=31 && $year->week_to >=31) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week31.'</td>
                    <td align="left" class="payp30_week_bg week32 '; if($year->show_active == 1) { if($year->week_from <=32 && $year->week_to >=32) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week32.'</td>
                    <td align="left" class="payp30_week_bg week33 '; if($year->show_active == 1) { if($year->week_from <=33 && $year->week_to >=33) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week33.'</td>
                    <td align="left" class="payp30_week_bg week34 '; if($year->show_active == 1) { if($year->week_from <=34 && $year->week_to >=34) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week34.'</td>
                    <td align="left" class="payp30_week_bg week35 '; if($year->show_active == 1) { if($year->week_from <=35 && $year->week_to >=35) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week35.'</td>
                    <td align="left" class="payp30_week_bg week36 '; if($year->show_active == 1) { if($year->week_from <=36 && $year->week_to >=36) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week36.'</td>
                    <td align="left" class="payp30_week_bg week37 '; if($year->show_active == 1) { if($year->week_from <=37 && $year->week_to >=37) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week37.'</td>
                    <td align="left" class="payp30_week_bg week38 '; if($year->show_active == 1) { if($year->week_from <=38 && $year->week_to >=38) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week38.'</td>
                    <td align="left" class="payp30_week_bg week39 '; if($year->show_active == 1) { if($year->week_from <=39 && $year->week_to >=39) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week39.'</td>
                    <td align="left" class="payp30_week_bg week40 '; if($year->show_active == 1) { if($year->week_from <=40 && $year->week_to >=40) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week40.'</td>
                    <td align="left" class="payp30_week_bg week41 '; if($year->show_active == 1) { if($year->week_from <=41 && $year->week_to >=41) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week41.'</td>
                    <td align="left" class="payp30_week_bg week42 '; if($year->show_active == 1) { if($year->week_from <=42 && $year->week_to >=42) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week42.'</td>
                    <td align="left" class="payp30_week_bg week43 '; if($year->show_active == 1) { if($year->week_from <=43 && $year->week_to >=43) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week43.'</td>
                    <td align="left" class="payp30_week_bg week44 '; if($year->show_active == 1) { if($year->week_from <=44 && $year->week_to >=44) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week44.'</td>
                    <td align="left" class="payp30_week_bg week45 '; if($year->show_active == 1) { if($year->week_from <=45 && $year->week_to >=45) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week45.'</td>
                    <td align="left" class="payp30_week_bg week46 '; if($year->show_active == 1) { if($year->week_from <=46 && $year->week_to >=46) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week46.'</td>
                    <td align="left" class="payp30_week_bg week47 '; if($year->show_active == 1) { if($year->week_from <=47 && $year->week_to >=47) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week47.'</td>
                    <td align="left" class="payp30_week_bg week48 '; if($year->show_active == 1) { if($year->week_from <=48 && $year->week_to >=48) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week48.'</td>
                    <td align="left" class="payp30_week_bg week49 '; if($year->show_active == 1) { if($year->week_from <=49 && $year->week_to >=49) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week49.'</td>
                    <td align="left" class="payp30_week_bg week50 '; if($year->show_active == 1) { if($year->week_from <=50 && $year->week_to >=50) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week50.'</td>
                    <td align="left" class="payp30_week_bg week51 '; if($year->show_active == 1) { if($year->week_from <=51 && $year->week_to >=51) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week51.'</td>
                    <td align="left" class="payp30_week_bg week52 '; if($year->show_active == 1) { if($year->week_from <=52 && $year->week_to >=52) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week52.'</td>
                    <td align="left" class="payp30_week_bg week53 '; if($year->show_active == 1) { if($year->week_from <=53 && $year->week_to >=53) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$week53.'</td>
                    <td align="left" class="payp30_month_bg month1 '; if($year->show_active == 1) { if($year->month_from <=1 && $year->month_to >=1) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$month1.'</td>
                    <td align="left" class="payp30_month_bg month2 '; if($year->show_active == 1) { if($year->month_from <=2 && $year->month_to >=2) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$month2.'</td>
                    <td align="left" class="payp30_month_bg month3 '; if($year->show_active == 1) { if($year->month_from <=3 && $year->month_to >=3) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$month3.'</td>
                    <td align="left" class="payp30_month_bg month4 '; if($year->show_active == 1) { if($year->month_from <=4 && $year->month_to >=4) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$month4.'</td>
                    <td align="left" class="payp30_month_bg month5 '; if($year->show_active == 1) { if($year->month_from <=5 && $year->month_to >=5) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$month5.'</td>
                    <td align="left" class="payp30_month_bg month6 '; if($year->show_active == 1) { if($year->month_from <=6 && $year->month_to >=6) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$month6.'</td>
                    <td align="left" class="payp30_month_bg month7 '; if($year->show_active == 1) { if($year->month_from <=7 && $year->month_to >=7) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$month7.'</td>
                    <td align="left" class="payp30_month_bg month8 '; if($year->show_active == 1) { if($year->month_from <=8 && $year->month_to >=8) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$month8.'</td>
                    <td align="left" class="payp30_month_bg month9 '; if($year->show_active == 1) { if($year->month_from <=9 && $year->month_to >=9) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$month9.'</td>
                    <td align="left" class="payp30_month_bg month10 '; if($year->show_active == 1) { if($year->month_from <=10 && $year->month_to >=10) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$month10.'</td>
                    <td align="left" class="payp30_month_bg month11 '; if($year->show_active == 1) { if($year->month_from <=11 && $year->month_to >=11) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$month11.'</td>
                    <td align="left" class="payp30_month_bg month12 '; if($year->show_active == 1) { if($year->month_from <=12 && $year->month_to >=12) { $output.='hide_column'; } else { $output.=''; } } $output.='">'.$month12.'</td>                    
                </tr>
              </tbody>
                '.$output_row.'
            </table> 
      </div>  
            ';
            $i++;
    }
  }
  else{
    $output='
    <div class="table-responsive" style="float: left; width:7000px;">    
        <table class="table_bg table-fixed-header table_paye_p30" style="width: 6700px; margin-bottom:30px">
          <thead class="header">
            <tr >
                <td style="border-right: 1px solid #000 !important; border-left: 1px solid #000 !important; border: 1px solid #000;" valign="top">S.No</td>                    
                <td colspan="7" style="text-align:left">
                    Clients
                </td>                    
                <td style="border-bottom: 0px; text-align:center; width:300px;" width="200px">
                    Email Sent                        
                </td>                    
                <td></td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 1</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 2</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 3</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 4</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 5</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 6</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 7</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 8</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 9</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 10</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 11</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 12</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 13</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 14</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 15</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 16</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 17</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 18</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 19</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 20</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 21</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 22</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 23</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 24</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 25</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 26</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 27</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 28</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 29</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 30</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 31</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 32</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 33</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 34</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 35</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 36</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 37</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 38</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 39</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 40</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 41</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 42</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 43</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 44</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 45</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 46</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 47</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 48</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 49</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 50</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 51</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 52</td>
                <td align="right" class="payp30_week_bg" style="text-align:right;">Week 53</td>
                <td align="right" class="payp30_month_bg" style="text-align:right;">Jan '.$year->year_name.'</td>
                <td align="right" class="payp30_month_bg" style="text-align:right;">Feb '.$year->year_name.'</td>
                <td align="right" class="payp30_month_bg" style="text-align:right;">Mar '.$year->year_name.'</td>
                <td align="right" class="payp30_month_bg" style="text-align:right;">Apr '.$year->year_name.'</td>
                <td align="right" class="payp30_month_bg" style="text-align:right;">May '.$year->year_name.'</td>
                <td align="right" class="payp30_month_bg" style="text-align:right;">Jun '.$year->year_name.'</td>
                <td align="right" class="payp30_month_bg" style="text-align:right;">Jul '.$year->year_name.'</td>
                <td align="right" class="payp30_month_bg" style="text-align:right;">Aug '.$year->year_name.'</td>
                <td align="right" class="payp30_month_bg" style="text-align:right;">Sep '.$year->year_name.'</td>
                <td align="right" class="payp30_month_bg" style="text-align:right;">Oct '.$year->year_name.'</td>
                <td align="right" class="payp30_month_bg" style="text-align:right;">Nov '.$year->year_name.'</td>
                <td align="right" class="payp30_month_bg" style="text-align:right;">Dec '.$year->year_name.'</td>
            </tr>
          </thead>
          <tbody>
            <tr>
                <td colspan="30">Empty</td>
                <td colspan="30">Empty</td>
                <td colspan="18">Empty</td>
            </tr>
          </tbody>
        </table>
    <div>
            ';
  }
  echo $output;
  ?>

    </div>
  </div>
</div>
<div class="modal_load"></div>
<div class="modal_load_content">Please Wait While the Page loads all the datas. It tooks some time to load all the datas of this year</div>
<script type="text/javascript">
$(window).scroll(function(){
    if($(this).scrollTop()){
      $(".navbar").fadeOut(1000);
      $(".footer_row").fadeOut(1000);
      $(".arrow_right").fadeIn(1000);
      $(".left_menu_dropdown").fadeIn(1000);
      // $(".header_logo").fadeIn(1000);

      $(".content_section").css("margin-top","10px");
    }
    else{
     $(".navbar").fadeIn(1000);
     $(".footer_row").fadeIn(1000);
     $(".arrow_right").fadeOut(1000);
     $(".left_menu_dropdown").fadeOut(1000);
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
        


        if(jQuery.inArray("1", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week1").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week1").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week1']+'" title="Liability Value ('+result['week1']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week1").find("a").html(result['week1']); }
        if(jQuery.inArray("2", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week2").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week2").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week2']+'" title="Liability Value ('+result['week2']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week2").find("a").html(result['week2']); }
        if(jQuery.inArray("3", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week3").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week3").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week3']+'" title="Liability Value ('+result['week3']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week3").find("a").html(result['week3']); }
        if(jQuery.inArray("4", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week4").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week4").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week4']+'" title="Liability Value ('+result['week4']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week4").find("a").html(result['week4']); }
        if(jQuery.inArray("5", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week5").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week5").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week5']+'" title="Liability Value ('+result['week5']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week5").find("a").html(result['week5']); }
        if(jQuery.inArray("6", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week6").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week6").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week6']+'" title="Liability Value ('+result['week6']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week6").find("a").html(result['week6']); }
        if(jQuery.inArray("7", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week7").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week7").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week7']+'" title="Liability Value ('+result['week7']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week7").find("a").html(result['week7']); }
        if(jQuery.inArray("8", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week8").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week8").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week8']+'" title="Liability Value ('+result['week8']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week8").find("a").html(result['week8']); }
        if(jQuery.inArray("9", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week9").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week9").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week9']+'" title="Liability Value ('+result['week9']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week9").find("a").html(result['week9']); }
        if(jQuery.inArray("10", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week10").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week10").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week10']+'" title="Liability Value ('+result['week10']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week10").find("a").html(result['week10']); }
        if(jQuery.inArray("11", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week11").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week11").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week11']+'" title="Liability Value ('+result['week11']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week11").find("a").html(result['week11']); }
        if(jQuery.inArray("12", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week12").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week12").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week12']+'" title="Liability Value ('+result['week12']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week12").find("a").html(result['week12']); }
        if(jQuery.inArray("13", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week13").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week13").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week13']+'" title="Liability Value ('+result['week13']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week13").find("a").html(result['week13']); }
        if(jQuery.inArray("14", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week14").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week14").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week14']+'" title="Liability Value ('+result['week14']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week14").find("a").html(result['week14']); }
        if(jQuery.inArray("15", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week15").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week15").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week15']+'" title="Liability Value ('+result['week15']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week15").find("a").html(result['week15']); }
        if(jQuery.inArray("16", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week16").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week16").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week16']+'" title="Liability Value ('+result['week16']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week16").find("a").html(result['week16']); }
        if(jQuery.inArray("17", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week17").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week17").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week17']+'" title="Liability Value ('+result['week17']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week17").find("a").html(result['week17']); }
        if(jQuery.inArray("18", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week18").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week18").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week18']+'" title="Liability Value ('+result['week18']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week18").find("a").html(result['week18']); }
        if(jQuery.inArray("19", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week19").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week19").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week19']+'" title="Liability Value ('+result['week19']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week19").find("a").html(result['week19']); }
        if(jQuery.inArray("20", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week20").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week20").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week20']+'" title="Liability Value ('+result['week20']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week20").find("a").html(result['week20']); }
        if(jQuery.inArray("21", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week21").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week21").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week21']+'" title="Liability Value ('+result['week21']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week21").find("a").html(result['week21']); }
        if(jQuery.inArray("22", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week22").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week22").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week22']+'" title="Liability Value ('+result['week22']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week22").find("a").html(result['week22']); }
        if(jQuery.inArray("23", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week23").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week23").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week23']+'" title="Liability Value ('+result['week23']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>');  }} else { $(e.target).parents("tr").find(".week23").find("a").html(result['week23']); }
        if(jQuery.inArray("24", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week24").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week24").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week24']+'" title="Liability Value ('+result['week24']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week24").find("a").html(result['week24']); }
        if(jQuery.inArray("25", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week25").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week25").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week25']+'" title="Liability Value ('+result['week25']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week25").find("a").html(result['week25']); }
        if(jQuery.inArray("26", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week26").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week26").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week26']+'" title="Liability Value ('+result['week26']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week26").find("a").html(result['week26']); }
        if(jQuery.inArray("27", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week27").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week27").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week27']+'" title="Liability Value ('+result['week27']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week27").find("a").html(result['week27']); }
        if(jQuery.inArray("28", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week28").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week28").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week28']+'" title="Liability Value ('+result['week28']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week28").find("a").html(result['week28']); }
        if(jQuery.inArray("29", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week29").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week29").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week29']+'" title="Liability Value ('+result['week29']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week29").find("a").html(result['week29']); }
        if(jQuery.inArray("30", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week30").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week30").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week30']+'" title="Liability Value ('+result['week30']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week30").find("a").html(result['week30']); }
        if(jQuery.inArray("31", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week31").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week31").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week31']+'" title="Liability Value ('+result['week31']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week31").find("a").html(result['week31']); }
        if(jQuery.inArray("32", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week32").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week32").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week32']+'" title="Liability Value ('+result['week32']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week32").find("a").html(result['week32']); }
        if(jQuery.inArray("33", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week33").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week33").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week33']+'" title="Liability Value ('+result['week33']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week33").find("a").html(result['week33']); }
        if(jQuery.inArray("34", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week34").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week34").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week34']+'" title="Liability Value ('+result['week34']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week34").find("a").html(result['week34']); }
        if(jQuery.inArray("35", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week35").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week35").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week35']+'" title="Liability Value ('+result['week35']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week35").find("a").html(result['week35']); }
        if(jQuery.inArray("36", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week36").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week36").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week36']+'" title="Liability Value ('+result['week36']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week36").find("a").html(result['week36']); }
        if(jQuery.inArray("37", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week37").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week37").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week37']+'" title="Liability Value ('+result['week37']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week37").find("a").html(result['week37']); }
        if(jQuery.inArray("38", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week38").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week38").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week38']+'" title="Liability Value ('+result['week38']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week38").find("a").html(result['week38']); }
        if(jQuery.inArray("39", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week39").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week39").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week39']+'" title="Liability Value ('+result['week39']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week39").find("a").html(result['week39']); }
        if(jQuery.inArray("40", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week40").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week40").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week40']+'" title="Liability Value ('+result['week40']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week40").find("a").html(result['week40']); }
        if(jQuery.inArray("41", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week41").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week41").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week41']+'" title="Liability Value ('+result['week41']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week41").find("a").html(result['week41']); }
        if(jQuery.inArray("42", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week42").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week42").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week42']+'" title="Liability Value ('+result['week42']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week42").find("a").html(result['week42']); }
        if(jQuery.inArray("43", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week43").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week43").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week43']+'" title="Liability Value ('+result['week43']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week43").find("a").html(result['week43']); }
        if(jQuery.inArray("44", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week44").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week44").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week44']+'" title="Liability Value ('+result['week44']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week44").find("a").html(result['week44']); }
        if(jQuery.inArray("45", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week45").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week45").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week45']+'" title="Liability Value ('+result['week45']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week45").find("a").html(result['week45']); }
        if(jQuery.inArray("46", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week46").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week46").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week46']+'" title="Liability Value ('+result['week46']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week46").find("a").html(result['week46']); }
        if(jQuery.inArray("47", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week47").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week47").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week47']+'" title="Liability Value ('+result['week47']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week47").find("a").html(result['week47']); }
        if(jQuery.inArray("48", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week48").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week48").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week48']+'" title="Liability Value ('+result['week48']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week48").find("a").html(result['week48']); }
        if(jQuery.inArray("49", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week49").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week49").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week49']+'" title="Liability Value ('+result['week49']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week49").find("a").html(result['week49']); }
        if(jQuery.inArray("50", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week50").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week50").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week50']+'" title="Liability Value ('+result['week50']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week50").find("a").html(result['week50']); }
        if(jQuery.inArray("51", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week51").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week51").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week51']+'" title="Liability Value ('+result['week51']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week51").find("a").html(result['week51']); }
        if(jQuery.inArray("52", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week52").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week52").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week52']+'" title="Liability Value ('+result['week52']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week52").find("a").html(result['week52']); }
        if(jQuery.inArray("53", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week53").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week53").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['week53']+'" title="Liability Value ('+result['week53']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week53").find("a").html(result['week53']); }

        if(jQuery.inArray("1", result['changed_liability_month']) !== -1) { if($(e.target).parents("tr").find(".month1").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".month1").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['month1']+'" title="Liability Value ('+result['month1']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".month1").find("a").html(result['month1']); }
        if(jQuery.inArray("2", result['changed_liability_month']) !== -1) { if($(e.target).parents("tr").find(".month2").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".month2").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['month2']+'" title="Liability Value ('+result['month2']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".month2").find("a").html(result['month2']); }
        if(jQuery.inArray("3", result['changed_liability_month']) !== -1) { if($(e.target).parents("tr").find(".month3").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".month3").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['month3']+'" title="Liability Value ('+result['month3']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".month3").find("a").html(result['month3']); }
        if(jQuery.inArray("4", result['changed_liability_month']) !== -1) { if($(e.target).parents("tr").find(".month4").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".month4").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['month4']+'" title="Liability Value ('+result['month4']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".month4").find("a").html(result['month4']); }
        if(jQuery.inArray("5", result['changed_liability_month']) !== -1) { if($(e.target).parents("tr").find(".month5").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".month5").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['month5']+'" title="Liability Value ('+result['month5']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".month5").find("a").html(result['month5']); }
        if(jQuery.inArray("6", result['changed_liability_month']) !== -1) { if($(e.target).parents("tr").find(".month6").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".month6").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['month6']+'" title="Liability Value ('+result['month6']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".month6").find("a").html(result['month6']); }
        if(jQuery.inArray("7", result['changed_liability_month']) !== -1) { if($(e.target).parents("tr").find(".month7").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".month7").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['month7']+'" title="Liability Value ('+result['month7']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".month7").find("a").html(result['month7']); }
        if(jQuery.inArray("8", result['changed_liability_month']) !== -1) { if($(e.target).parents("tr").find(".month8").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".month8").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['month8']+'" title="Liability Value ('+result['month8']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".month8").find("a").html(result['month8']); }
        if(jQuery.inArray("9", result['changed_liability_month']) !== -1) { if($(e.target).parents("tr").find(".month9").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".month9").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['month9']+'" title="Liability Value ('+result['month9']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".month9").find("a").html(result['month9']); }
        if(jQuery.inArray("10", result['changed_liability_month']) !== -1) { if($(e.target).parents("tr").find(".month10").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".month10").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['month10']+'" title="Liability Value ('+result['month10']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".month10").find("a").html(result['month10']); }
        if(jQuery.inArray("11", result['changed_liability_month']) !== -1) { if($(e.target).parents("tr").find(".month11").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".month11").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['month11']+'" title="Liability Value ('+result['month11']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".month11").find("a").html(result['month11']); }
        if(jQuery.inArray("12", result['changed_liability_month']) !== -1) { if($(e.target).parents("tr").find(".month12").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".month12").find("a").append('<i class="fa fa-info blueinfo" data-element="'+result['month12']+'" title="Liability Value ('+result['month12']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".month12").find("a").html(result['month12']); }

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


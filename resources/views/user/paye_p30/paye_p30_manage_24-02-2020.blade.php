@extends('userheader')
@section('content')
<script src='<?php echo URL::to('assets/js/table-fixed-header_cm.js'); ?>'></script>
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
<!-- 
<div class="header_logo" style="width: 130px; height: auto; padding: 15px; position: fixed; top: 10px; background:  #ff0; z-index: 9999999; left: 0px;display:none">
  <img src="<?php echo URL::to('assets/images/easy_payroll_logo.png')?>" style="width: 100%" />
</div> -->
<div class="arrow_right" style="height: auto; padding: 15px; position: fixed; bottom: 10px; background:  #ff0; z-index: 9999999; right: 15px;font-size:34px;display:none">
  <a href="javascript:" class="arrow_right_scroll"><i class="fa fa-arrow-circle-o-up arrow_right_scroll" aria-hidden="true"></i></a>
</div>

  <div class="page_title" style="position:fixed;margin-top: -17px;">
    <div class="col-lg-7 padding_00" style="line-height: 30px; font-size: 20px">
      PAYE M.R.S <?php echo $year->year_name?>  
      <input type="hidden" value="<?php echo $year->year_id?>" class="year_id" name="">    

      <!-- <input type="button" class="common_black_button show_hide_clients" data-element="<?php if($year->email_clients == 1) { echo 'show'; } else { echo 'hide'; } ?>" value="<?php if($year->email_clients == 1) { echo 'Show Clients'; } else { echo 'Hide Clients'; } ?>" title="This will hide/show clients having Email sent on the current active month" style="float:right;font-size: 15px;font-weight: 600;padding: 5px 7px;">

      <input type="button" class="common_black_button show_hide_disable" data-element="<?php if($year->disable_clients == 1) { echo 'show'; } else { echo 'hide'; } ?>" value="<?php if($year->disable_clients == 1) { echo 'Show Disabled Clients'; } else { echo 'Hide Disabled Clients'; } ?>" style="float:right;font-size: 15px;font-weight: 600;padding: 5px 7px;">

      <input type="button" class="common_black_button refresh_all_clients" value="Refresh All" style="float:right;font-size: 15px;font-weight: 600;padding: 5px 7px;">

      <input type="button" class="common_black_button show_all_tables" value="Show All Tables" style="float:right;font-size: 15px;font-weight: 600;padding: 5px 7px;"> -->
            
    </div>

    <div class="col-lg-5 padding_00 button_top_right" style="float:left;">
          <ul style="float:none">
            <li class="" style="display: none;">
                <div class="col-lg-12" style="line-height: 30px;">Active Month:</div>
            </li>
            <li class="" style="display: none;">
                <div class="col-lg-12">
                    <!-- <select class="form-control active_month">
                        <option value="">Select Period</option>
                        <option value="1" <?php if($year->active_month == 1){ echo 'selected'; } ?>>January</option>
                        <option value="2" <?php if($year->active_month == 2){ echo 'selected'; } ?>>February</option>
                        <option value="3" <?php if($year->active_month == 3){ echo 'selected'; } ?>>March</option>
                        <option value="4" <?php if($year->active_month == 4){ echo 'selected'; } ?>>April</option>
                        <option value="5" <?php if($year->active_month == 5){ echo 'selected'; } ?>>May</option>
                        <option value="6" <?php if($year->active_month == 6){ echo 'selected'; } ?>>June</option>
                        <option value="7" <?php if($year->active_month == 7){ echo 'selected'; } ?>>July</option>
                        <option value="8" <?php if($year->active_month == 8){ echo 'selected'; } ?>>August</option>
                        <option value="9" <?php if($year->active_month == 9){ echo 'selected'; } ?>>September</option>
                        <option value="10" <?php if($year->active_month == 10){ echo 'selected'; } ?>>October</option>
                        <option value="11" <?php if($year->active_month == 11){ echo 'selected'; } ?>>November</option>
                        <option value="12" <?php if($year->active_month == 12){ echo 'selected'; } ?>>December</option>
                    </select> -->
                </div>
            </li>
            <li class="" style="margin-left: 28px;"><a href="<?php echo URL::to('user/paye_p30_create_new_year'); ?>" style="float:right">Close and Create New Year</a></li>
                       
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
                          <option value="1" <?php if($year->selected_week_from == 1){ echo 'selected'; } ?>>Week1</option>
                          <option value="2" <?php if($year->selected_week_from == 2){ echo 'selected'; } ?>>Week2</option>
                          <option value="3" <?php if($year->selected_week_from == 3){ echo 'selected'; } ?>>Week3</option>
                          <option value="4" <?php if($year->selected_week_from == 4){ echo 'selected'; } ?>>Week4</option>
                          <option value="5" <?php if($year->selected_week_from == 5){ echo 'selected'; } ?>>Week5</option>
                          <option value="6" <?php if($year->selected_week_from == 6){ echo 'selected'; } ?>>Week6</option>
                          <option value="7" <?php if($year->selected_week_from == 7){ echo 'selected'; } ?>>Week7</option>
                          <option value="8" <?php if($year->selected_week_from == 8){ echo 'selected'; } ?>>Week8</option>
                          <option value="9" <?php if($year->selected_week_from == 9){ echo 'selected'; } ?>>Week9</option>
                          <option value="10" <?php if($year->selected_week_from == 10){ echo 'selected'; } ?>>Week10</option>
                          <option value="11" <?php if($year->selected_week_from == 11){ echo 'selected'; } ?>>Week11</option>
                          <option value="12" <?php if($year->selected_week_from == 12){ echo 'selected'; } ?>>Week12</option>
                          <option value="13" <?php if($year->selected_week_from == 13){ echo 'selected'; } ?>>Week13</option>
                          <option value="14" <?php if($year->selected_week_from == 14){ echo 'selected'; } ?>>Week14</option>
                          <option value="15" <?php if($year->selected_week_from == 15){ echo 'selected'; } ?>>Week15</option>
                          <option value="16" <?php if($year->selected_week_from == 16){ echo 'selected'; } ?>>Week16</option>
                          <option value="17" <?php if($year->selected_week_from == 17){ echo 'selected'; } ?>>Week17</option>
                          <option value="18" <?php if($year->selected_week_from == 18){ echo 'selected'; } ?>>Week18</option>
                          <option value="19" <?php if($year->selected_week_from == 19){ echo 'selected'; } ?>>Week19</option>
                          <option value="20" <?php if($year->selected_week_from == 20){ echo 'selected'; } ?>>Week20</option>
                          <option value="21" <?php if($year->selected_week_from == 21){ echo 'selected'; } ?>>Week21</option>
                          <option value="22" <?php if($year->selected_week_from == 22){ echo 'selected'; } ?>>Week22</option>
                          <option value="23" <?php if($year->selected_week_from == 23){ echo 'selected'; } ?>>Week23</option>
                          <option value="24" <?php if($year->selected_week_from == 24){ echo 'selected'; } ?>>Week24</option>
                          <option value="25" <?php if($year->selected_week_from == 25){ echo 'selected'; } ?>>Week25</option>
                          <option value="26" <?php if($year->selected_week_from == 26){ echo 'selected'; } ?>>Week26</option>
                          <option value="27" <?php if($year->selected_week_from == 27){ echo 'selected'; } ?>>Week27</option>
                          <option value="28" <?php if($year->selected_week_from == 28){ echo 'selected'; } ?>>Week28</option>
                          <option value="29" <?php if($year->selected_week_from == 29){ echo 'selected'; } ?>>Week29</option>
                          <option value="30" <?php if($year->selected_week_from == 30){ echo 'selected'; } ?>>Week30</option>
                          <option value="31" <?php if($year->selected_week_from == 31){ echo 'selected'; } ?>>Week31</option>
                          <option value="32" <?php if($year->selected_week_from == 32){ echo 'selected'; } ?>>Week32</option>
                          <option value="33" <?php if($year->selected_week_from == 33){ echo 'selected'; } ?>>Week33</option>
                          <option value="34" <?php if($year->selected_week_from == 34){ echo 'selected'; } ?>>Week34</option>
                          <option value="35" <?php if($year->selected_week_from == 35){ echo 'selected'; } ?>>Week35</option>
                          <option value="36" <?php if($year->selected_week_from == 36){ echo 'selected'; } ?>>Week36</option>
                          <option value="37" <?php if($year->selected_week_from == 37){ echo 'selected'; } ?>>Week37</option>
                          <option value="38" <?php if($year->selected_week_from == 38){ echo 'selected'; } ?>>Week38</option>
                          <option value="39" <?php if($year->selected_week_from == 39){ echo 'selected'; } ?>>Week39</option>
                          <option value="40" <?php if($year->selected_week_from == 40){ echo 'selected'; } ?>>Week40</option>
                          <option value="41" <?php if($year->selected_week_from == 41){ echo 'selected'; } ?>>Week41</option>
                          <option value="42" <?php if($year->selected_week_from == 42){ echo 'selected'; } ?>>Week42</option>
                          <option value="43" <?php if($year->selected_week_from == 43){ echo 'selected'; } ?>>Week43</option>
                          <option value="44" <?php if($year->selected_week_from == 44){ echo 'selected'; } ?>>Week44</option>
                          <option value="45" <?php if($year->selected_week_from == 45){ echo 'selected'; } ?>>Week45</option>
                          <option value="46" <?php if($year->selected_week_from == 46){ echo 'selected'; } ?>>Week46</option>
                          <option value="47" <?php if($year->selected_week_from == 47){ echo 'selected'; } ?>>Week47</option>
                          <option value="48" <?php if($year->selected_week_from == 48){ echo 'selected'; } ?>>Week48</option>
                          <option value="49" <?php if($year->selected_week_from == 49){ echo 'selected'; } ?>>Week49</option>
                          <option value="50" <?php if($year->selected_week_from == 50){ echo 'selected'; } ?>>Week50</option>
                          <option value="51" <?php if($year->selected_week_from == 51){ echo 'selected'; } ?>>Week51</option>
                          <option value="52" <?php if($year->selected_week_from == 52){ echo 'selected'; } ?>>Week52</option>
                          <option value="53" <?php if($year->selected_week_from == 53){ echo 'selected'; } ?>>Week53</option>
                      </select>
                  </div>
                  <div class="col-lg-3 ">    

                      <select class="form-control month_from" required>
                          <option value="">Select Month From</option>
                          <option value="1" <?php if($year->selected_month_from == 1){ echo 'selected'; } ?>>January</option>
                          <option value="2" <?php if($year->selected_month_from == 2){ echo 'selected'; } ?>>February</option>
                          <option value="3" <?php if($year->selected_month_from == 3){ echo 'selected'; } ?>>March</option>
                          <option value="4" <?php if($year->selected_month_from == 4){ echo 'selected'; } ?>>April</option>
                          <option value="5" <?php if($year->selected_month_from == 5){ echo 'selected'; } ?>>May</option>
                          <option value="6" <?php if($year->selected_month_from == 6){ echo 'selected'; } ?>>June</option>
                          <option value="7" <?php if($year->selected_month_from == 7){ echo 'selected'; } ?>>July</option>
                          <option value="8" <?php if($year->selected_month_from == 8){ echo 'selected'; } ?>>August</option>
                          <option value="9" <?php if($year->selected_month_from == 9){ echo 'selected'; } ?>>September</option>
                          <option value="10" <?php if($year->selected_month_from == 10){ echo 'selected'; } ?>>October</option>
                          <option value="11" <?php if($year->selected_month_from == 11){ echo 'selected'; } ?>>November</option>
                          <option value="12" <?php if($year->selected_month_from == 12){ echo 'selected'; } ?>>December</option>
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
                          <option value="1" <?php if($year->selected_week_to == 1){ echo 'selected'; } ?>>Week1</option>
                          <option value="2" <?php if($year->selected_week_to == 2){ echo 'selected'; } ?>>Week2</option>
                          <option value="3" <?php if($year->selected_week_to == 3){ echo 'selected'; } ?>>Week3</option>
                          <option value="4" <?php if($year->selected_week_to == 4){ echo 'selected'; } ?>>Week4</option>
                          <option value="5" <?php if($year->selected_week_to == 5){ echo 'selected'; } ?>>Week5</option>
                          <option value="6" <?php if($year->selected_week_to == 6){ echo 'selected'; } ?>>Week6</option>
                          <option value="7" <?php if($year->selected_week_to == 7){ echo 'selected'; } ?>>Week7</option>
                          <option value="8" <?php if($year->selected_week_to == 8){ echo 'selected'; } ?>>Week8</option>
                          <option value="9" <?php if($year->selected_week_to == 9){ echo 'selected'; } ?>>Week9</option>
                          <option value="10" <?php if($year->selected_week_to == 10){ echo 'selected'; } ?>>Week10</option>
                          <option value="11" <?php if($year->selected_week_to == 11){ echo 'selected'; } ?>>Week11</option>
                          <option value="12" <?php if($year->selected_week_to == 12){ echo 'selected'; } ?>>Week12</option>
                          <option value="13" <?php if($year->selected_week_to == 13){ echo 'selected'; } ?>>Week13</option>
                          <option value="14" <?php if($year->selected_week_to == 14){ echo 'selected'; } ?>>Week14</option>
                          <option value="15" <?php if($year->selected_week_to == 15){ echo 'selected'; } ?>>Week15</option>
                          <option value="16" <?php if($year->selected_week_to == 16){ echo 'selected'; } ?>>Week16</option>
                          <option value="17" <?php if($year->selected_week_to == 17){ echo 'selected'; } ?>>Week17</option>
                          <option value="18" <?php if($year->selected_week_to == 18){ echo 'selected'; } ?>>Week18</option>
                          <option value="19" <?php if($year->selected_week_to == 19){ echo 'selected'; } ?>>Week19</option>
                          <option value="20" <?php if($year->selected_week_to == 20){ echo 'selected'; } ?>>Week20</option>
                          <option value="21" <?php if($year->selected_week_to == 21){ echo 'selected'; } ?>>Week21</option>
                          <option value="22" <?php if($year->selected_week_to == 22){ echo 'selected'; } ?>>Week22</option>
                          <option value="23" <?php if($year->selected_week_to == 23){ echo 'selected'; } ?>>Week23</option>
                          <option value="24" <?php if($year->selected_week_to == 24){ echo 'selected'; } ?>>Week24</option>
                          <option value="25" <?php if($year->selected_week_to == 25){ echo 'selected'; } ?>>Week25</option>
                          <option value="26" <?php if($year->selected_week_to == 26){ echo 'selected'; } ?>>Week26</option>
                          <option value="27" <?php if($year->selected_week_to == 27){ echo 'selected'; } ?>>Week27</option>
                          <option value="28" <?php if($year->selected_week_to == 28){ echo 'selected'; } ?>>Week28</option>
                          <option value="29" <?php if($year->selected_week_to == 29){ echo 'selected'; } ?>>Week29</option>
                          <option value="30" <?php if($year->selected_week_to == 30){ echo 'selected'; } ?>>Week30</option>
                          <option value="31" <?php if($year->selected_week_to == 31){ echo 'selected'; } ?>>Week31</option>
                          <option value="32" <?php if($year->selected_week_to == 32){ echo 'selected'; } ?>>Week32</option>
                          <option value="33" <?php if($year->selected_week_to == 33){ echo 'selected'; } ?>>Week33</option>
                          <option value="34" <?php if($year->selected_week_to == 34){ echo 'selected'; } ?>>Week34</option>
                          <option value="35" <?php if($year->selected_week_to == 35){ echo 'selected'; } ?>>Week35</option>
                          <option value="36" <?php if($year->selected_week_to == 36){ echo 'selected'; } ?>>Week36</option>
                          <option value="37" <?php if($year->selected_week_to == 37){ echo 'selected'; } ?>>Week37</option>
                          <option value="38" <?php if($year->selected_week_to == 38){ echo 'selected'; } ?>>Week38</option>
                          <option value="39" <?php if($year->selected_week_to == 39){ echo 'selected'; } ?>>Week39</option>
                          <option value="40" <?php if($year->selected_week_to == 40){ echo 'selected'; } ?>>Week40</option>
                          <option value="41" <?php if($year->selected_week_to == 41){ echo 'selected'; } ?>>Week41</option>
                          <option value="42" <?php if($year->selected_week_to == 42){ echo 'selected'; } ?>>Week42</option>
                          <option value="43" <?php if($year->selected_week_to == 43){ echo 'selected'; } ?>>Week43</option>
                          <option value="44" <?php if($year->selected_week_to == 44){ echo 'selected'; } ?>>Week44</option>
                          <option value="45" <?php if($year->selected_week_to == 45){ echo 'selected'; } ?>>Week45</option>
                          <option value="46" <?php if($year->selected_week_to == 46){ echo 'selected'; } ?>>Week46</option>
                          <option value="47" <?php if($year->selected_week_to == 47){ echo 'selected'; } ?>>Week47</option>
                          <option value="48" <?php if($year->selected_week_to == 48){ echo 'selected'; } ?>>Week48</option>
                          <option value="49" <?php if($year->selected_week_to == 49){ echo 'selected'; } ?>>Week49</option>
                          <option value="50" <?php if($year->selected_week_to == 50){ echo 'selected'; } ?>>Week50</option>
                          <option value="51" <?php if($year->selected_week_to == 51){ echo 'selected'; } ?>>Week51</option>
                          <option value="52" <?php if($year->selected_week_to == 52){ echo 'selected'; } ?>>Week52</option>
                          <option value="53" <?php if($year->selected_week_to == 53){ echo 'selected'; } ?>>Week53</option>
                      </select> 
                  </div>
                  <div class="col-lg-3 ">                
                      <select class="form-control month_to" required>
                          <option value="">Select Month To</option>
                          <option value="1" <?php if($year->selected_month_to == 1){ echo 'selected'; } ?>>January</option>
                          <option value="2" <?php if($year->selected_month_to == 2){ echo 'selected'; } ?>>February</option>
                          <option value="3" <?php if($year->selected_month_to == 3){ echo 'selected'; } ?>>March</option>
                          <option value="4" <?php if($year->selected_month_to == 4){ echo 'selected'; } ?>>April</option>
                          <option value="5" <?php if($year->selected_month_to == 5){ echo 'selected'; } ?>>May</option>
                          <option value="6" <?php if($year->selected_month_to == 6){ echo 'selected'; } ?>>June</option>
                          <option value="7" <?php if($year->selected_month_to == 7){ echo 'selected'; } ?>>July</option>
                          <option value="8" <?php if($year->selected_month_to == 8){ echo 'selected'; } ?>>August</option>
                          <option value="9" <?php if($year->selected_month_to == 9){ echo 'selected'; } ?>>September</option>
                          <option value="10" <?php if($year->selected_month_to == 10){ echo 'selected'; } ?>>October</option>
                          <option value="11" <?php if($year->selected_month_to == 11){ echo 'selected'; } ?>>November</option>
                          <option value="12" <?php if($year->selected_month_to == 12){ echo 'selected'; } ?>>December</option>
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
                      <option value="1" <?php if($year->active_month == 1){ echo 'selected'; } ?>>January</option>
                      <option value="2" <?php if($year->active_month == 2){ echo 'selected'; } ?>>February</option>
                      <option value="3" <?php if($year->active_month == 3){ echo 'selected'; } ?>>March</option>
                      <option value="4" <?php if($year->active_month == 4){ echo 'selected'; } ?>>April</option>
                      <option value="5" <?php if($year->active_month == 5){ echo 'selected'; } ?>>May</option>
                      <option value="6" <?php if($year->active_month == 6){ echo 'selected'; } ?>>June</option>
                      <option value="7" <?php if($year->active_month == 7){ echo 'selected'; } ?>>July</option>
                      <option value="8" <?php if($year->active_month == 8){ echo 'selected'; } ?>>August</option>
                      <option value="9" <?php if($year->active_month == 9){ echo 'selected'; } ?>>September</option>
                      <option value="10" <?php if($year->active_month == 10){ echo 'selected'; } ?>>October</option>
                      <option value="11" <?php if($year->active_month == 11){ echo 'selected'; } ?>>November</option>
                      <option value="12" <?php if($year->active_month == 12){ echo 'selected'; } ?>>December</option>
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
                    
                    <li><a href="javascript:" class="show_all_tables">Show All Tables</a></li>
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
        $get_period_datas = DB::table('paye_p30_periods')->select('period_id','ros_liability', 'task_liability','liability_diff','last_email_sent','month_id','payments')->where('paye_task',$task->id)->get();
        $level_name = DB::table('p30_tasklevel')->where('id',$task->task_level)->first();

        if($task->task_level != 0){ $action = $level_name->name; }
        if($task->pay == 0){ $pay = 'No';}else{$pay = 'Yes';}
        if($task->email == 0){ $email = 'No';}else{$email = 'Yes';}
        if($keytask % 2 == 0) { $background = ''; }else{ $background = 'style="background:#e5f7fe"'; }
        if($task->disabled == 1) { $checked = 'checked'; $label_color = 'color:#f00'; $disbledtext = ' (DISABLED)'; } else { $checked = ''; $label_color = 'color:#000'; $disbledtext = ''; }
        $output.='<li class="main_li" '.$background.'>
                <div class="sno">'.$i.'</div>
                <div class="clientname"><input type="checkbox" name="disable_clients" class="disable_clients" id="disable_'.$task->id.'" value="'.$task->id.'" '.$checked.'> <label class="task_name_label task_name_label2" for="disable_'.$task->id.'" style="'.$label_color.'">'.$task->task_name.$disbledtext.'</label> <a href="javascript:" class="load_info" data-element="'.$task->id.'"> Show Table </a>
                  <a href="javascript:" class="export_csv" data-element="'.$task->id.'"> Export CSV </a>

                  <a href="javascript:" class="update_task" data-element="'.$task->id.'">Update Task</a>
                    <div class="load_info_table">
                      <table class="table_bg table-fixed-header table_paye_p30" id="table_'.$task->id.'" style="margin-bottom:20px; width:900px; margin-top:40px">
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
                              
                              <td colspan="2" style="text-align:center;" "="">
                              
                              <input type="hidden" class="active_month_class payetask_1" value="5">
                              </td>
                              
                              <td style="padding:0px 10px;"><a href="javascript:" style="display:none"><i class="fa fa-refresh refresh_liability" data-element="'.$task->id.'"></i></a></td>
                          </tr>';
                          $total_ros_value = 0;
                          $total_task_value = 0;
                          $total_diff_value = 0;
                          $total_payment_value = 0;
                        if(count($get_period_datas))
                        {
                          foreach($get_period_datas as $period)
                          {
                            if($period->month_id == 1) { $month_name = 'Jan'; }
                            elseif($period->month_id == 2) { $month_name = 'Feb'; }
                            elseif($period->month_id == 3) { $month_name = 'Mar'; }
                            elseif($period->month_id == 4) { $month_name = 'Apr'; }
                            elseif($period->month_id == 5) { $month_name = 'May'; }
                            elseif($period->month_id == 6) { $month_name = 'Jun'; }
                            elseif($period->month_id == 7) { $month_name = 'Jul'; }
                            elseif($period->month_id == 8) { $month_name = 'Aug'; }
                            elseif($period->month_id == 9) { $month_name = 'Sep'; }
                            elseif($period->month_id == 10) { $month_name = 'Oct'; }
                            elseif($period->month_id == 11) { $month_name = 'Nov'; }
                            elseif($period->month_id == 12) { $month_name = 'Dec'; }
                            if($period->month_id == $task->active_month) { $checked = "checked"; } else { $checked = ''; }

                            if($period->last_email_sent == "0000-00-00 00:00:00") { $email_sent = ''; }
                            else{ $email_sent = date('d M Y @ H:i', strtotime($period->last_email_sent)); }
                            $output.='<tr class="month_row_'.$period->period_id.'">
                                <td style="border-right: 1px solid #000 !important; border-left: 1px solid #000 !important; border: 1px solid #fff; border-bottom:1px solid #000"></td>
                                
                                <td colspan="2" style="width: 40px; text-align: right; border-bottom: 0px;">
                                    <input type="radio" name="month_name_'.$task->id.'" class="month_class month_class_'.$period->month_id.'" value="'.$period->month_id.'" data-element="'.$task->id.'" '.$checked.'><label>&nbsp;</label>
                                </td>
                                <td style="width: 100px; border-bottom: 0px;">'.$month_name.'-'.$year->year_name.'</td>
                                <td style="width: 110px; border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control ros_class" data-element="'.$period->period_id.'" value="'.number_format_invoice($period->ros_liability).'"></td>
                                <td style="width: 110px; border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control liability_class" value="'.number_format_invoice($period->task_liability).'" readonly=""></td>
                                <td style="width: 110px; border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control diff_class" value="'.number_format_invoice($period->liability_diff).'" readonly=""></td>
                                <td style="width: 110px; border-bottom: 0px; border: 1px solid #000; border-top: 0px;">
                                  <input class="form-control payment_class" style="color:#009800;" data-element="'.$period->period_id.'" value="'.number_format_invoice($period->payments).'">
                                </td>
                                <td colspan="3" style="width: 180px; "><a href="javascript:" class="fa fa-envelope email_unsent email_unsent_'.$period->period_id.'" data-element="'.$period->period_id.'"></a><br>'.$email_sent.'<br></td>
                            </tr>';

                            $total_ros_value = $total_ros_value + number_format_invoice_without_comma($period->ros_liability);
                            $total_task_value = $total_task_value + number_format_invoice_without_comma($period->task_liability);
                            $total_diff_value = $total_diff_value + number_format_invoice_without_comma($period->liability_diff);
                            $total_payment_value = $total_payment_value + number_format_invoice_without_comma($period->payments);
                          }
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
                            </tr></tbody>
                        </table>
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
<input type="hidden" name="hidden_loading_status" id="hidden_loading_status" value="">
<div class="modal_load"></div>
<div class="modal_load_content" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until all the tables are loaded.</p>
  <p style="font-size:18px;font-weight: 600;">Loading: <span id="count_first"></span> of <span id="count_last"></span></p>
  <p style="font-size:18px;font-weight: 600;">This may take upto <span id="estimated_time"></span> Minutes</p>
</div>

<script type="text/javascript">
<?php
if(!empty($_GET['div_id']))
{
  $divid = $_GET['div_id'];
  ?>
  $(function() {
    setTimeout(function() {
      $(document).scrollTop( $("#table_<?php echo $divid; ?>").offset().top - parseInt(150) );
    },3000);
  });
  <?php
}
?>
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
  setTimeout(function() {
    $("body").addClass("loading");
  },500);
    <?php
    if($year->show_active == 1)
    {
        ?>
        $(".hide_column").show();
        $(".hide_column_inner").parents("td").show();

        $(".show_column").hide();
        $(".show_column_inner").parents("td").hide();
        <?php
    }
    else{
        ?>
        $(".hide_column").show();
        $(".hide_column_inner").parents("td").show();

        $(".show_column").show();
        $(".show_column_inner").parents("td").show();
        <?php
    }
    if($year->disable_clients == 1)
    {
      ?>
        var disablestatus = 'hide';
      <?php
    }
    else{
      ?>
        var disablestatus = 'show';
      <?php
    }
    if($year->email_clients == 1)
    {
      ?>
        var emailstatus = 'hide';
        
      <?php
    }
    else{
      ?>
        var emailstatus = 'show';
      <?php
    }
    ?>

    $.ajax({
      url:"<?php echo URL::to('user/update_paye_p30_year_disabled_status'); ?>",
      type:"post",
      data:{year:"<?php echo $year->year_id; ?>",status:disablestatus},
      success: function(result)
      {
        if(disablestatus == "hide")
        {
          
          $(".disable_clients:checked").parents(".main_li").hide();
        }
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
$(window).dblclick(function(e) {
  if($(e.target).hasClass('liability_class'))
  {
    var value = $(e.target).val();
    var paye_task = $(e.target).parents("tr").find(".ros_class").attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/update_ros_liability'); ?>",
      type:"post",
      data:{paye_task:paye_task,value:value},
      success: function(result)
      {
        $(e.target).parents("tr").find(".ros_class").val(value);
      }
    });
  }
});
$(window).click(function(e) {
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
	var countval = $(".refresh_liability").length;
	$(".refresh_liability").each(function(index) {
		var that = this;
        var t = setTimeout(function() { 
            $(that).trigger('click');
            if(countval == index + 1)
            {
            	$("body").removeClass("loading");
            	$("#hidden_loading_status").val("");
            	$.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>Refresh made Successfully for all the clients.</p>", fixed:true});
            }
        }, 2000 * index);
	});
}
if($(e.target).hasClass('update_all_tasks'))
{
  $("#hidden_loading_status").val("1");
  $("body").addClass("loading");
  var countval = $(".update_task").length;
  $(".update_task").each(function(index) {
    var that = this;
        var t = setTimeout(function() { 
            $(that).trigger('click');
            if(countval == index + 1)
            {
              $("body").removeClass("loading");
              $("#hidden_loading_status").val("");
              $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>Updated successfully for all the clients.</p>", fixed:true});
            }
        }, 2000 * index);
  });
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
if($(e.target).hasClass('load_info'))
{
  var loading_status = $("#hidden_loading_status").val();
  if(loading_status == "")
  {
  	$("body").addClass("loading");	
  }
  
  var task_id = $(e.target).attr("data-element");
  $.ajax({
    url:"<?php echo URL::to('user/load_table_info'); ?>",
    type:"post",
    data:{task_id:task_id,year_id:"<?php echo $year->year_id; ?>"},
    dataType:"json",
    success: function(result)
    {

      $(e.target).text("Hide Table");
      $(e.target).addClass("unload_info").removeClass("load_info");

      $(e.target).parents(".clientname").find(".load_info_table").html(result['output']);
      if(result['show_active'] == 1)
      {
        var week_from = result['week_from'];
        var week_to = result['week_to'];
        var month_from = result['month_from'];
        var month_to = result['month_to'];

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

        $(".refresh_liability:visible").parents(".table_paye_p30").css("width",totalpixel+"px");
        
        $(e.target).parents(".clientname").find(".load_info_table").find(".hide_column").show();
        $(e.target).parents(".clientname").find(".load_info_table").find(".hide_column_inner").parents("td").show();

        $(e.target).parents(".clientname").find(".load_info_table").find(".show_column").hide();
        $(e.target).parents(".clientname").find(".load_info_table").find(".show_column_inner").parents("td").hide();
      }
      else{
        $(e.target).parents(".clientname").find(".load_info_table").find(".hide_column").show();
        $(e.target).parents(".clientname").find(".load_info_table").find(".hide_column_inner").parents("td").show();

        $(e.target).parents(".clientname").find(".load_info_table").find(".show_column").show();
        $(e.target).parents(".clientname").find(".load_info_table").find(".show_column_inner").parents("td").show();
      }

      $(e.target).parents(".clientname").find(".refresh_liability").trigger("click");
      $(".load_info_table").slideDown(2000);

      var countval = $(".load_info").length;
      var gettime = countval * 5;
      var convert_minutes = Math.round(gettime / 60);
      $("#estimated_time").html(convert_minutes);
      if(countval == 1)
      {
        $("body").removeClass("loading_content");
        $("#hidden_loading_status").val("");
      }

      setTimeout(function() {
      	if(loading_status == "")
    		{
    			$("body").removeClass("loading");
    		}
      },4000);
    }
  });
}
if($(e.target).hasClass('unload_info'))
{
  $("body").addClass("loading");
    $(e.target).text("Show Table");
    $(e.target).addClass("load_info").removeClass("unload_info");

    $(e.target).parents(".clientname").find(".payp30_week_bg").detach();
    $(e.target).parents(".clientname").find(".payp30_month_bg").detach();
    $(e.target).parents(".clientname").find(".table_paye_p30").css("width","900px");
    $(e.target).parents(".clientname").find(".refresh_liability").detach();

    $("body").removeClass("loading");
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
          $("#confirm_content").html("You are about to Apply all unallocated PREM charges to weeks "+week_from+" to "+week_to+" and month "+month_from_name+" to "+month_to_name+" to the currently active month which is : "+active_month_name+" Do you want to continue Yes or No");
        }
        else if(week_to != "" && week_from != "")
        {
          $("#confirm_modal").modal("show");
          $("#confirm_content").html("You are about to Apply all unallocated PREM charges to weeks "+week_from+" to "+week_to+" to the currently active month which is : "+active_month_name+" Do you want to continue Yes or No");
        }
        else if(month_to != "" && month_from != "")
        {
           $("#confirm_modal").modal("show");
          $("#confirm_content").html("You are about to Apply all unallocated PREM charges to month "+month_from_name+" to "+month_to_name+" to the currently active month which is : "+active_month_name+" Do you want to continue Yes or No");
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
          window.location.reload();
      }
  });
}
if($(e.target).hasClass('no_hit'))
{
  $("#confirm_modal").modal("hide");
}
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

      $("body").addClass("loading");
      $.ajax({
          url:"<?php echo URL::to('user/paye_p30_active_periods'); ?>",
          type:"post",
          dataType:"json",
          data:{week_from:week_from, week_to:week_to, month_from:month_from, month_to:month_to, year_id:year_id  },
          success: function(result){
              $(".payp30_week_bg").hide();
              $(".payp30_month_bg").hide();
              for(var i=result['week_from']; i<=result['week_to']; i++)
              {
                $(".refresh_liability:visible").parents(".table_paye_p30").css("width",totalpixel+"px");

                  $(".week_td_"+i).show();
                  $(".week"+i).show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();

                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();
                  $(".week"+i+"_class").parents("td").show();


                  $("body").removeClass("loading");
              }

              for(var i=result['month_from']; i<=result['month_to']; i++)
              {
                  $(".month_td_"+i).show();
                  $(".month"+i).show();
                  $(".month"+i+"_class").parents("td").show();
                  $(".month"+i+"_class").parents("td").show();
                  $(".month"+i+"_class").parents("td").show();
                  $(".month"+i+"_class").parents("td").show();
                  $(".month"+i+"_class").parents("td").show();
                  $(".month"+i+"_class").parents("td").show();
                  $(".month"+i+"_class").parents("td").show();
                  $(".month"+i+"_class").parents("td").show();
                  $(".month"+i+"_class").parents("td").show();
                  $(".month"+i+"_class").parents("td").show();
                  $(".month"+i+"_class").parents("td").show();
                  $(".month"+i+"_class").parents("td").show();

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
            $(".refresh_liability:visible").parents(".table_paye_p30").css("width","6700px");
            $(".payp30_week_bg").show();
            $(".payp30_month_bg").show();
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
         $(".month"+result['month']+"_class_"+result['id']).html(result['value']);
         $(".month"+result['month']+"_class_"+result['id']).css({"text-align":"right"});

        $(".month_row_"+result['id']).find(".liability_class").val(result['task_liability']);
        $(".month_row_"+result['id']).find(".diff_class").val(result['different']);   

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
        


        if(jQuery.inArray("1", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week1").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week1").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week1']+'" title="Liability Value ('+result['week1']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week1").find("a").html(result['week1']); $(e.target).parents("tr").find(".week1").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="1">'+result['week1']+'</a>'); }
        if(jQuery.inArray("2", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week2").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week2").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week2']+'" title="Liability Value ('+result['week2']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week2").find("a").html(result['week2']); $(e.target).parents("tr").find(".week2").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="2">'+result['week2']+'</a>'); }
        if(jQuery.inArray("3", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week3").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week3").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week3']+'" title="Liability Value ('+result['week3']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week3").find("a").html(result['week3']); $(e.target).parents("tr").find(".week3").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="3">'+result['week3']+'</a>'); }
        if(jQuery.inArray("4", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week4").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week4").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week4']+'" title="Liability Value ('+result['week4']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week4").find("a").html(result['week4']); $(e.target).parents("tr").find(".week4").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="4">'+result['week4']+'</a>'); }
        if(jQuery.inArray("5", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week5").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week5").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week5']+'" title="Liability Value ('+result['week5']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week5").find("a").html(result['week5']); $(e.target).parents("tr").find(".week5").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="5">'+result['week5']+'</a>'); }
        if(jQuery.inArray("6", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week6").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week6").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week6']+'" title="Liability Value ('+result['week6']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week6").find("a").html(result['week6']); $(e.target).parents("tr").find(".week6").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="6">'+result['week6']+'</a>'); }
        if(jQuery.inArray("7", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week7").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week7").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week7']+'" title="Liability Value ('+result['week7']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week7").find("a").html(result['week7']); $(e.target).parents("tr").find(".week7").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="7">'+result['week7']+'</a>'); }
        if(jQuery.inArray("8", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week8").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week8").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week8']+'" title="Liability Value ('+result['week8']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week8").find("a").html(result['week8']); $(e.target).parents("tr").find(".week8").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="8">'+result['week8']+'</a>'); }
        if(jQuery.inArray("9", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week9").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week9").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week9']+'" title="Liability Value ('+result['week9']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week9").find("a").html(result['week9']); $(e.target).parents("tr").find(".week9").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="9">'+result['week9']+'</a>'); }
        if(jQuery.inArray("10", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week10").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week10").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week10']+'" title="Liability Value ('+result['week10']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week10").find("a").html(result['week10']); $(e.target).parents("tr").find(".week10").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="10">'+result['week10']+'</a>'); }
        if(jQuery.inArray("11", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week11").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week11").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week11']+'" title="Liability Value ('+result['week11']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week11").find("a").html(result['week11']); $(e.target).parents("tr").find(".week11").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="11">'+result['week11']+'</a>'); }
        if(jQuery.inArray("12", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week12").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week12").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week12']+'" title="Liability Value ('+result['week12']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week12").find("a").html(result['week12']); $(e.target).parents("tr").find(".week12").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="12">'+result['week12']+'</a>'); }
        if(jQuery.inArray("13", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week13").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week13").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week13']+'" title="Liability Value ('+result['week13']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week13").find("a").html(result['week13']); $(e.target).parents("tr").find(".week13").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="13">'+result['week13']+'</a>'); }
        if(jQuery.inArray("14", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week14").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week14").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week14']+'" title="Liability Value ('+result['week14']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week14").find("a").html(result['week14']); $(e.target).parents("tr").find(".week14").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="14">'+result['week14']+'</a>'); }
        if(jQuery.inArray("15", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week15").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week15").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week15']+'" title="Liability Value ('+result['week15']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week15").find("a").html(result['week15']); $(e.target).parents("tr").find(".week15").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="15">'+result['week15']+'</a>'); }
        if(jQuery.inArray("16", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week16").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week16").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week16']+'" title="Liability Value ('+result['week16']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week16").find("a").html(result['week16']); $(e.target).parents("tr").find(".week16").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="16">'+result['week16']+'</a>'); }
        if(jQuery.inArray("17", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week17").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week17").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week17']+'" title="Liability Value ('+result['week17']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week17").find("a").html(result['week17']); $(e.target).parents("tr").find(".week17").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="17">'+result['week17']+'</a>'); }
        if(jQuery.inArray("18", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week18").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week18").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week18']+'" title="Liability Value ('+result['week18']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week18").find("a").html(result['week18']); $(e.target).parents("tr").find(".week18").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="18">'+result['week18']+'</a>'); }
        if(jQuery.inArray("19", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week19").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week19").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week19']+'" title="Liability Value ('+result['week19']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week19").find("a").html(result['week19']); $(e.target).parents("tr").find(".week19").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="19">'+result['week19']+'</a>'); }
        if(jQuery.inArray("20", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week20").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week20").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week20']+'" title="Liability Value ('+result['week20']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week20").find("a").html(result['week20']); $(e.target).parents("tr").find(".week20").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="20">'+result['week20']+'</a>'); }
        if(jQuery.inArray("21", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week21").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week21").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week21']+'" title="Liability Value ('+result['week21']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week21").find("a").html(result['week21']); $(e.target).parents("tr").find(".week21").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="21">'+result['week21']+'</a>'); }
        if(jQuery.inArray("22", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week22").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week22").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week22']+'" title="Liability Value ('+result['week22']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week22").find("a").html(result['week22']); $(e.target).parents("tr").find(".week22").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="22">'+result['week22']+'</a>'); }
        if(jQuery.inArray("23", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week23").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week23").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week23']+'" title="Liability Value ('+result['week23']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>');  }} else { $(e.target).parents("tr").find(".week23").find("a").html(result['week23']); $(e.target).parents("tr").find(".week23").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="23">'+result['week23']+'</a>'); }
        if(jQuery.inArray("24", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week24").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week24").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week24']+'" title="Liability Value ('+result['week24']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week24").find("a").html(result['week24']); $(e.target).parents("tr").find(".week24").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="24">'+result['week24']+'</a>'); }
        if(jQuery.inArray("25", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week25").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week25").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week25']+'" title="Liability Value ('+result['week25']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week25").find("a").html(result['week25']); $(e.target).parents("tr").find(".week25").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="25">'+result['week25']+'</a>'); }
        if(jQuery.inArray("26", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week26").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week26").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week26']+'" title="Liability Value ('+result['week26']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week26").find("a").html(result['week26']); $(e.target).parents("tr").find(".week26").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="26">'+result['week26']+'</a>'); }
        if(jQuery.inArray("27", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week27").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week27").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week27']+'" title="Liability Value ('+result['week27']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week27").find("a").html(result['week27']); $(e.target).parents("tr").find(".week27").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="27">'+result['week27']+'</a>'); }
        if(jQuery.inArray("28", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week28").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week28").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week28']+'" title="Liability Value ('+result['week28']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week28").find("a").html(result['week28']); $(e.target).parents("tr").find(".week28").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="28">'+result['week28']+'</a>'); }
        if(jQuery.inArray("29", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week29").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week29").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week29']+'" title="Liability Value ('+result['week29']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week29").find("a").html(result['week29']); $(e.target).parents("tr").find(".week29").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="29">'+result['week29']+'</a>'); }
        if(jQuery.inArray("30", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week30").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week30").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week30']+'" title="Liability Value ('+result['week30']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week30").find("a").html(result['week30']); $(e.target).parents("tr").find(".week30").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="30">'+result['week30']+'</a>'); }
        if(jQuery.inArray("31", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week31").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week31").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week31']+'" title="Liability Value ('+result['week31']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week31").find("a").html(result['week31']); $(e.target).parents("tr").find(".week31").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="31">'+result['week31']+'</a>'); }
        if(jQuery.inArray("32", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week32").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week32").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week32']+'" title="Liability Value ('+result['week32']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week32").find("a").html(result['week32']); $(e.target).parents("tr").find(".week32").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="32">'+result['week32']+'</a>'); }
        if(jQuery.inArray("33", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week33").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week33").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week33']+'" title="Liability Value ('+result['week33']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week33").find("a").html(result['week33']); $(e.target).parents("tr").find(".week33").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="33">'+result['week33']+'</a>'); }
        if(jQuery.inArray("34", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week34").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week34").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week34']+'" title="Liability Value ('+result['week34']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week34").find("a").html(result['week34']); $(e.target).parents("tr").find(".week34").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="34">'+result['week34']+'</a>'); }
        if(jQuery.inArray("35", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week35").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week35").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week35']+'" title="Liability Value ('+result['week35']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week35").find("a").html(result['week35']); $(e.target).parents("tr").find(".week35").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="35">'+result['week35']+'</a>'); }
        if(jQuery.inArray("36", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week36").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week36").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week36']+'" title="Liability Value ('+result['week36']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week36").find("a").html(result['week36']); $(e.target).parents("tr").find(".week36").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="36">'+result['week36']+'</a>'); }
        if(jQuery.inArray("37", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week37").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week37").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week37']+'" title="Liability Value ('+result['week37']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week37").find("a").html(result['week37']); $(e.target).parents("tr").find(".week37").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="37">'+result['week37']+'</a>'); }
        if(jQuery.inArray("38", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week38").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week38").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week38']+'" title="Liability Value ('+result['week38']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week38").find("a").html(result['week38']); $(e.target).parents("tr").find(".week38").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="38">'+result['week38']+'</a>'); }
        if(jQuery.inArray("39", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week39").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week39").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week39']+'" title="Liability Value ('+result['week39']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week39").find("a").html(result['week39']); $(e.target).parents("tr").find(".week39").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="39">'+result['week39']+'</a>'); }
        if(jQuery.inArray("40", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week40").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week40").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week40']+'" title="Liability Value ('+result['week40']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week40").find("a").html(result['week40']); $(e.target).parents("tr").find(".week40").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="40">'+result['week40']+'</a>'); }
        if(jQuery.inArray("41", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week41").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week41").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week41']+'" title="Liability Value ('+result['week41']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week41").find("a").html(result['week41']); $(e.target).parents("tr").find(".week41").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="41">'+result['week41']+'</a>'); }
        if(jQuery.inArray("42", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week42").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week42").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week42']+'" title="Liability Value ('+result['week42']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week42").find("a").html(result['week42']); $(e.target).parents("tr").find(".week42").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="42">'+result['week42']+'</a>'); }
        if(jQuery.inArray("43", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week43").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week43").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week43']+'" title="Liability Value ('+result['week43']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week43").find("a").html(result['week43']); $(e.target).parents("tr").find(".week43").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="43">'+result['week43']+'</a>'); }
        if(jQuery.inArray("44", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week44").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week44").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week44']+'" title="Liability Value ('+result['week44']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week44").find("a").html(result['week44']); $(e.target).parents("tr").find(".week44").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="44">'+result['week44']+'</a>'); }
        if(jQuery.inArray("45", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week45").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week45").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week45']+'" title="Liability Value ('+result['week45']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week45").find("a").html(result['week45']); $(e.target).parents("tr").find(".week45").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="45">'+result['week45']+'</a>'); }
        if(jQuery.inArray("46", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week46").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week46").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week46']+'" title="Liability Value ('+result['week46']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week46").find("a").html(result['week46']); $(e.target).parents("tr").find(".week46").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="46">'+result['week46']+'</a>'); }
        if(jQuery.inArray("47", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week47").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week47").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week47']+'" title="Liability Value ('+result['week47']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week47").find("a").html(result['week47']); $(e.target).parents("tr").find(".week47").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="47">'+result['week47']+'</a>'); }
        if(jQuery.inArray("48", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week48").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week48").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week48']+'" title="Liability Value ('+result['week48']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week48").find("a").html(result['week48']); $(e.target).parents("tr").find(".week48").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="48">'+result['week48']+'</a>'); }
        if(jQuery.inArray("49", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week49").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week49").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week49']+'" title="Liability Value ('+result['week49']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week49").find("a").html(result['week49']); $(e.target).parents("tr").find(".week49").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="49">'+result['week49']+'</a>'); }
        if(jQuery.inArray("50", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week50").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week50").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week50']+'" title="Liability Value ('+result['week50']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week50").find("a").html(result['week50']); $(e.target).parents("tr").find(".week50").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="50">'+result['week50']+'</a>'); }
        if(jQuery.inArray("51", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week51").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week51").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week51']+'" title="Liability Value ('+result['week51']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week51").find("a").html(result['week51']); $(e.target).parents("tr").find(".week51").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="51">'+result['week51']+'</a>'); }
        if(jQuery.inArray("52", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week52").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week52").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week52']+'" title="Liability Value ('+result['week52']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week52").find("a").html(result['week52']); $(e.target).parents("tr").find(".week52").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="52">'+result['week52']+'</a>'); }
        if(jQuery.inArray("53", result['changed_liability_week']) !== -1) { if($(e.target).parents("tr").find(".week53").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".week53").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['week53']+'" title="Liability Value ('+result['week53']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".week53").find("a").html(result['week53']); $(e.target).parents("tr").find(".week53").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum " value="'+result['payep30_task']+'" data-element="53">'+result['week53']+'</a>'); }

        if(jQuery.inArray("1", result['changed_liability_month']) !== -1) { if($(e.target).parents("tr").find(".month1").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".month1").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['month1']+'" title="Liability Value ('+result['month1']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".month1").find("a").html(result['month1']); $(e.target).parents("tr").find(".month1").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum_month" value="'+result['payep30_task']+'" data-element="1">'+result['month1']+'</a>'); }
        if(jQuery.inArray("2", result['changed_liability_month']) !== -1) { if($(e.target).parents("tr").find(".month2").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".month2").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['month2']+'" title="Liability Value ('+result['month2']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".month2").find("a").html(result['month2']); $(e.target).parents("tr").find(".month2").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum_month" value="'+result['payep30_task']+'" data-element="2">'+result['month2']+'</a>'); }
        if(jQuery.inArray("3", result['changed_liability_month']) !== -1) { if($(e.target).parents("tr").find(".month3").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".month3").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['month3']+'" title="Liability Value ('+result['month3']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".month3").find("a").html(result['month3']); $(e.target).parents("tr").find(".month3").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum_month" value="'+result['payep30_task']+'" data-element="3">'+result['month3']+'</a>'); }
        if(jQuery.inArray("4", result['changed_liability_month']) !== -1) { if($(e.target).parents("tr").find(".month4").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".month4").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['month4']+'" title="Liability Value ('+result['month4']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".month4").find("a").html(result['month4']); $(e.target).parents("tr").find(".month4").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum_month" value="'+result['payep30_task']+'" data-element="4">'+result['month4']+'</a>'); }
        if(jQuery.inArray("5", result['changed_liability_month']) !== -1) { if($(e.target).parents("tr").find(".month5").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".month5").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['month5']+'" title="Liability Value ('+result['month5']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".month5").find("a").html(result['month5']); $(e.target).parents("tr").find(".month5").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum_month" value="'+result['payep30_task']+'" data-element="5">'+result['month5']+'</a>'); }
        if(jQuery.inArray("6", result['changed_liability_month']) !== -1) { if($(e.target).parents("tr").find(".month6").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".month6").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['month6']+'" title="Liability Value ('+result['month6']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".month6").find("a").html(result['month6']); $(e.target).parents("tr").find(".month6").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum_month" value="'+result['payep30_task']+'" data-element="6">'+result['month6']+'</a>'); }
        if(jQuery.inArray("7", result['changed_liability_month']) !== -1) { if($(e.target).parents("tr").find(".month7").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".month7").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['month7']+'" title="Liability Value ('+result['month7']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".month7").find("a").html(result['month7']); $(e.target).parents("tr").find(".month7").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum_month" value="'+result['payep30_task']+'" data-element="7">'+result['month7']+'</a>'); }
        if(jQuery.inArray("8", result['changed_liability_month']) !== -1) { if($(e.target).parents("tr").find(".month8").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".month8").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['month8']+'" title="Liability Value ('+result['month8']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".month8").find("a").html(result['month8']); $(e.target).parents("tr").find(".month8").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum_month" value="'+result['payep30_task']+'" data-element="8">'+result['month8']+'</a>'); }
        if(jQuery.inArray("9", result['changed_liability_month']) !== -1) { if($(e.target).parents("tr").find(".month9").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".month9").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['month9']+'" title="Liability Value ('+result['month9']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".month9").find("a").html(result['month9']); $(e.target).parents("tr").find(".month9").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum_month" value="'+result['payep30_task']+'" data-element="9">'+result['month9']+'</a>'); }
        if(jQuery.inArray("10", result['changed_liability_month']) !== -1) { if($(e.target).parents("tr").find(".month10").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".month10").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['month10']+'" title="Liability Value ('+result['month10']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".month10").find("a").html(result['month10']); $(e.target).parents("tr").find(".month10").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum_month" value="'+result['payep30_task']+'" data-element="10">'+result['month10']+'</a>'); }
        if(jQuery.inArray("11", result['changed_liability_month']) !== -1) { if($(e.target).parents("tr").find(".month11").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".month11").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['month11']+'" title="Liability Value ('+result['month11']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".month11").find("a").html(result['month11']); $(e.target).parents("tr").find(".month11").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum_month" value="'+result['payep30_task']+'" data-element="11">'+result['month11']+'</a>'); }
        if(jQuery.inArray("12", result['changed_liability_month']) !== -1) { if($(e.target).parents("tr").find(".month12").find(".blueinfo").length == 0) { $(e.target).parents("tr").find(".month12").find("a").append('<i class="fa fa-exclamation-triangle blueinfo" data-element="'+result['month12']+'" title="Liability Value ('+result['month12']+') has been changed in this week so if you want to update the value please remove the value which is alloted below"></i>'); } } else { $(e.target).parents("tr").find(".month12").find("a").html(result['month12']); $(e.target).parents("tr").find(".month12").find(".payp30_dash").html('<a href="javascript:" class="payp30_black task_class_colum_month" value="'+result['payep30_task']+'" data-element="12">'+result['month12']+'</a>'); }
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





</script>
<script type="text/javascript">

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
        clearTimeout(valueTimmer);
        valueTimmer = setTimeout(doneTyping, valueInterval,input_val, period_id,that);   
    }
    if($(e.target).hasClass('payment_class'))
    {
        var that = $(e.target);
        var input_val = $(e.target).val();  
        var period_id = $(e.target).attr("data-element");
        clearTimeout(valueTimmer);
        valueTimmer = setTimeout(doneTypingpayment, valueInterval,input_val, period_id,that);   
    }
});


function doneTyping (ros_value,period_id,that) {
  $.ajax({
        url:"<?php echo URL::to('user/paye_p30_ros_update')?>",
        type:"post",
        dataType:"json",
        data:{value:ros_value, id:period_id},
        success: function(result) {            
            $(".month_row_"+result['id']).find(".diff_class").val(result['different']);

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
function doneTypingpayment (payment_value,period_id,that) {
  $.ajax({
        url:"<?php echo URL::to('user/paye_p30_payment_update')?>",
        type:"post",
        dataType:"json",
        data:{value:payment_value, id:period_id},
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


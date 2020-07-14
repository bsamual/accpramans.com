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
      





    </div>
</div>


@stop
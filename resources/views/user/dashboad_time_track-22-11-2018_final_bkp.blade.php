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



  background: #2fd9ff !important;



}

#reset_breaktime{

  background: #000;

    color: #fff;

        margin-left: -17px;

}

.add_minutes_div{    

    border: 1px solid #dfdfdf;

}

.add_minutes_div:hover{    

    border: 1px solid #dfdfdf;

    background: #000;

    color:#fff;

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



.form-title{width: 100%; height: auto; float: left; margin-bottom: 5px;}



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

                <input type="hidden" class="hidden_activejob_starttime" id="hidden_activejob_starttime" value="" name="hidden_activejob_starttime">

            </div>

            <div class="form-group client_group">

                <div class="form-title">Choose Client</div>

                <input  type="text" class="form-control client_search_class" name="client_name" placeholder="Enter Client Name / Client ID" required>

                <input type="hidden" id="client_search" name="clientid" />

            </div>



            <div class="form-group internal_tasks_group" style="display: none;">

                <div class="form-title">Select Task</div>

                <div class="dropdown" style="width: 100%">

                <a class="btn btn-default dropdown-toggle tasks_drop" data-target="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="width: 100%">

                  <span class="task-choose_internal">Select Task</span>  <span class="caret"></span>                          

                </a>

                

                <ul class="dropdown-menu internal_task_details" role="menu"  aria-labelledby="dropdownMenu" style="width: 100%">

                  <li><a tabindex="-1" href="javascript:" class="tasks_li_internal">Select Task</a></li>

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

                <div class="form-title">Select Task</div>



                <div class="dropdown" style="width: 100%">

                <a class="btn btn-default dropdown-toggle tasks_drop" data-target="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="width: 100%">

                  <span class="task-choose">Select Task</span>  <span class="caret"></span>                          

                </a>

                

                <ul class="dropdown-menu task_details" role="menu"  aria-labelledby="dropdownMenu" style="width: 100%">                  

                  

                </ul>

              </div>

             

            </div>



            <div class="form-group date_group" style="display: none;">

                <div class="form-title">Choose Date</div>

                <label class="input-group datepicker-only-init">

                    <input type="text" class="form-control create_dateclass" placeholder="Select Date" name="date" style="font-weight: 500;" required />

                    <span class="input-group-addon">

                        <i class="glyphicon glyphicon-calendar"></i>

                    </span>

                </label>

            </div>



            <div class="form-group start_group" style="display: none;">

                <div class="form-title">Choose Start Time</div>

                <div class='input-group date' id='start_time'>

                    <input type='text' class="form-control create_startclass" placeholder="Select Start Time" name="starttime" style="font-weight: 500;" required />

                    <span class="input-group-addon">

                        <span class="glyphicon glyphicon-time"></span>

                    </span>

                </div>

            </div>



            <div class="form-group stop_group" style="display: none;">

                <div class="form-title">Choose Stop Time</div>

                <div class='input-group date' id='stop_time'>

                    <input type='text' class="form-control stop_time" placeholder="Select Stop Time" name="stoptime" style="font-weight: 500;" required />

                    <span class="input-group-addon">

                        <span class="glyphicon glyphicon-time"></span>

                    </span>

                </div>

            </div>







            <input type="hidden" id="quickjob" value="" name="quick_job">

            <input type="hidden" class="acive_id" value="" name="acive_id">

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

  <div class="modal-dialog modal-sm" role="document" style="width:30%">

    <form action="<?php echo URL::to('user/time_job_stop')?>" method="post" class="add_new_form">

        <div class="modal-content">

          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            <h4 class="modal-title">Stop Active Job</h4>

          </div>

          <div class="modal-body">

            <div class="form-group start_group">

                <div class="row">

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class="form-title">Job Date :</div>

                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class='input-group date'>                    

                        <input class="form-control dateclass" name="date" value="" readonly autocomplete="off">

                    </div>

                  </div>

                </div>

            </div>



            <div class="form-group">

                <div class="row">

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class="form-title">Job Started Time :</div>

                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class='input-group date'>

                        <input type='text' class="form-control stop_start_time" readonly placeholder="Select Stop Time" name="stoptime" style="font-weight: 500;background-color: #eceaea !important" required autocomplete="off"/>

                        <span class="input-group-addon">

                            <span class="glyphicon glyphicon-time"></span>

                        </span>

                    </div>

                  </div>

                </div>

            </div>

            <div class="form-group">

                <div class="row">

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class="form-title">Choose Stop Time :</div>

                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class='input-group date' id='stop_time1'>

                        <input type='text' class="form-control stop_time1" placeholder="Select Stop Time" name="stoptime" style="font-weight: 500;" required autocomplete="off"/>

                        <span class="input-group-addon">

                            <span class="glyphicon glyphicon-time"></span>

                        </span>

                    </div>

                  </div>

                </div>

            </div>

            <div class="form-group">

                <div class="row">

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class="form-title">Job Time :</div>

                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class='input-group date stop_jom_time'>

                        <input type='text' class="form-control calculate_job_time" name="stoptime" style="font-weight: 500;background-color: #eceaea !important" disabled/>

                        <span class="input-group-addon">

                            <span class="glyphicon glyphicon-time"></span>

                        </span>

                    </div>

                  </div>

                </div>

            </div>

            <div class="form-group">

                <textarea class="form-control comments" name="comments" placeholder="Enter Comments"></textarea>

                <input type="hidden" class="idclass" name="id">

            </div>

            <div class="form-group breaktime_div" style="display:none">

                <div class="row">

                  <div class="col-lg-4 col-md-2 col-sm-6 col-xs-6">

                    <div class="form-title" style="margin-top:10px; font-weight:800;">Break Time : </div>

                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">

                    <div class='input-group date'>

                        <input type='text' class="form-control" id="break_time" name="breaktime" style="font-weight: 500;" readonly/>

                        <input type="hidden" id="break_time_val" name="break_time_val" value="0">

                    </div>

                  </div>

                  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">

                    <a href="javascript:" class="btn btn-sm btn-primary" id="reset_breaktime"> Reset </a>

                  </div>

                </div>

            </div>

            <div class="form-group breaktime_div" style="display:none">

                <div class="row">

                  <a href="javascript:" class="col-lg-3 col-md-3 col-sm-6 col-xs-6 add_minutes_div add_minutes" data-element="15"> <i class="fa fa-plus"></i> 15 Minutes</a>

                  <a href="javascript:" class="col-lg-3 col-md-3 col-sm-6 col-xs-6 add_minutes_div add_minutes" data-element="30"> <i class="fa fa-plus"></i> 30 Minutes</a>

                  <a href="javascript:" class="col-lg-3 col-md-3 col-sm-6 col-xs-6 add_minutes_div add_minutes" data-element="45"> <i class="fa fa-plus"></i> 45 Minutes</a>

                  <a href="javascript:" class="col-lg-3 col-md-3 col-sm-6 col-xs-6 add_minutes_div add_minutes" data-element="60"> <i class="fa fa-plus"></i> 60 Minutes</a>

                </div>

                <div class="form-group" style="margin-top:20px;">

                <div class="row">

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class="form-title">Job Time :</div>

                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class='input-group date stop_jom_time'>

                        <input type='text' class="form-control calculate_job_time" name="stoptime" style="font-weight: 500;background-color: #eceaea !important" disabled/>

                        <span class="input-group-addon">

                            <span class="glyphicon glyphicon-time"></span>

                        </span>

                    </div>

                  </div>

                </div>

            </div>

            <div class="form-group start_group">

                <div class="row">

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class="form-title">Total Quick Jobs :</div>

                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class='input-group date'>                    

                        <input class="form-control" id="total_quick_jobs" value="" autocomplete="off" disabled>

                    </div>

                  </div>

                </div>

            </div>

            <div class="form-group start_group">

                <div class="row">

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class="form-title">Total Breaks :</div>

                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class='input-group date'>                    

                        <input class="form-control" id="total_breaks" value="" autocomplete="off" disabled>

                    </div>

                  </div>

                </div>

            </div>


            <div class="form-group">

                <div class="row">

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class="form-title">Actual Time on Job:</div>

                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class='input-group date'>

                        <input class="form-control" id="total_time_minutes_format" value="" autocomplete="off" disabled>

                    </div>

                  </div>

                </div>

            </div>

            </div>

            
          </div>

          <div class="modal-footer">

            <input type="button" class="common_black_button" id="stop_job" value="Stop Job">

            <input type="submit" class="common_black_button" id="stop_active_job" value="Stop Active Job" style="display:none">

          </div>

        </div>

    </form>

  </div>

</div>

<div class="modal fade stop_quick_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="margin-top: 5%;">

  <div class="modal-dialog modal-sm" role="document" style="width:30%">

    <form action="<?php echo URL::to('user/time_job_stop_quick')?>" method="post" class="add_new_form">

        <div class="modal-content">

          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            <h4 class="modal-title">Stop Quick Job</h4>

          </div>

          <div class="modal-body">

            <div class="form-group start_group">

                <div class="row">

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class="form-title">Job Date :</div>

                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class='input-group date'>                    

                        <input class="form-control dateclass" name="date" value="" readonly autocomplete="off">

                    </div>

                  </div>

                </div>

            </div>



            <div class="form-group">

                <div class="row">

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class="form-title">Job Started Time :</div>

                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class='input-group date'>

                        <input type='text' class="form-control stop_start_time" readonly placeholder="Select Stop Time" name="stoptime" style="font-weight: 500;background-color: #eceaea !important" required autocomplete="off"/>

                        <span class="input-group-addon">

                            <span class="glyphicon glyphicon-time"></span>

                        </span>

                    </div>

                  </div>

                </div>

            </div>

            <div class="form-group">

                <div class="row">

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class="form-title">Choose Stop Time :</div>

                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class='input-group date' id='stop_time2'>

                        <input type='text' class="form-control stop_time2" placeholder="Select Stop Time" name="stoptime" style="font-weight: 500;" required autocomplete="off"/>

                        <span class="input-group-addon">

                            <span class="glyphicon glyphicon-time"></span>

                        </span>

                    </div>

                  </div>

                </div>

            </div>

            <div class="form-group">

                <div class="row">

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class="form-title">Job Time :</div>

                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class='input-group date stop_jom_time'>

                        <input type='text' class="form-control" id="calculate_job_time_quick" name="stoptime" style="font-weight: 500;background-color: #eceaea !important" disabled/>

                        <span class="input-group-addon">

                            <span class="glyphicon glyphicon-time"></span>

                        </span>

                    </div>

                  </div>

                </div>

            </div>

            <div class="form-group">

                <textarea class="form-control comments" name="comments" placeholder="Enter Comments"></textarea>

                <input type="hidden" class="idclass" name="id">

            </div>

            <div class="form-group breaktime_div" style="display:none">

                <div class="row">

                  <div class="col-lg-4 col-md-2 col-sm-6 col-xs-6">

                    <div class="form-title" style="margin-top:10px; font-weight:800;">Break Time : </div>

                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">

                    <div class='input-group date'>

                        <input type='text' class="form-control" id="break_time" name="breaktime" style="font-weight: 500;" readonly/>

                        <input type="hidden" id="break_time_val" name="break_time_val" value="0">

                    </div>

                  </div>

                  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">

                    <a href="javascript:" class="btn btn-sm btn-primary" id="reset_breaktime"> Reset </a>

                  </div>

                </div>

            </div>

            <div class="form-group breaktime_div" style="display:none">

                <div class="row">

                  <a href="javascript:" class="col-lg-3 col-md-3 col-sm-6 col-xs-6 add_minutes_div add_minutes" data-element="15"> <i class="fa fa-plus"></i> 15 Minutes</a>

                  <a href="javascript:" class="col-lg-3 col-md-3 col-sm-6 col-xs-6 add_minutes_div add_minutes" data-element="30"> <i class="fa fa-plus"></i> 30 Minutes</a>

                  <a href="javascript:" class="col-lg-3 col-md-3 col-sm-6 col-xs-6 add_minutes_div add_minutes" data-element="45"> <i class="fa fa-plus"></i> 45 Minutes</a>

                  <a href="javascript:" class="col-lg-3 col-md-3 col-sm-6 col-xs-6 add_minutes_div add_minutes" data-element="60"> <i class="fa fa-plus"></i> 60 Minutes</a>

                </div>

            </div>

          </div>

          <div class="modal-footer">

            <input type="submit" class="common_black_button" id="stop_job_quick" value="Stop Quick Job">           

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

    <h4 class="col-lg-12 text-center" style="padding: 0px; font-size: 25px; font-weight: bold; margin-bottom: 20px;">

                TimeMe Manager

                <a href="<?php echo URL::to('user/time_me_overview'); ?>" class="ok_button" style="float:right;font-size:14px;padding:10px">TimeMe Overview</a>

            </h4>

  </div>

    <div class="row">      

      

      

      

      

      

      

      <div class="col-lg-12" style="background: #fff; padding:25px 15px !important; ">        

        <h4 class="col-lg-2" style="padding: 0px; font-weight: 600">Active Job</h4>

        <div class="col-lg-6"></div>

        <div class="col-lg-2">



          <select class="form-control" id="user_select">

            <option value="">Select User</option>        

            <?php

            $selected = '';

            if(count($userlist)){

              foreach ($userlist as $user) {

                if(Session::has('task_job_user'))

                {

                  if($user->user_id == Session::get('task_job_user')) { $selected = 'selected'; }

                  else{ $selected = ''; }

                }

            ?>

              <option value="<?php echo $user->user_id ?>" <?php echo $selected; ?>><?php echo $user->firstname.'&nbsp;'.$user->lastname?></option>

            <?php

              }

            }

            ?>

          </select>          

        </div>

        <div class="col-lg-2" style="padding: 0px;">

        <?php

        if(Session::has('task_job_user') && Session::get('task_job_user') != "")

        {

          $check_date_available = DB::table('task_job')->where('user_id',Session::get('task_job_user'))->where('quick_job',0)->where('status',0)->first();

          if(count($check_date_available))

          {

            $job_available = 'not_create';

          }

          else{

            $job_available = '';

          }  

        }

        else{

          $job_available = '';

        }

             

        ?>

          <a href="javascript:" class="ok_button create_new <?php echo $job_available; ?>" style="line-height:20px; margin-right: 5px;">Create an Active Job</a>          

        </div>



        <div class="clearfix" style="margin-bottom: 20px;"></div>



        <table class="display nowrap fullviewtablelist" id="active_job" width="100%">

          <thead>

            <tr style="background: #fff;">

              <th width="2%" style="text-align: left;">S.No</th>

              <th style="text-align: left;">Client Name</th>

              <th style="text-align: left;">Task Name</th>

              <th style="text-align: left;">Task Type</th>

              <th style="text-align: left;">Quick Break</th>

              <th style="text-align: left;">Date</th>

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
                if($jobs->quick_job == 0 || $jobs->quick_job == 1){
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
                      elseif($task_type == 1){
                        $task_type = '<i class="fa fa-users" style="margin-right:10px;"></i> Client Task';
                      }
                      else{
                        $task_type = '<i class="fa fa-globe" style="margin-right:10px;"></i> Global Task';
                      }
                    }
                    else{
                      $task_name = 'N/A';
                      $task_type = 'N/A';
                    }
                    // $break_time_count = DB::table('job_break_time')->where('job_id', $jobs->id)->get();
                    // $count_minues = 0;
                    // if(count($break_time_count)){
                    //   foreach ($break_time_count as $break_time1) {
                    //     if($break_time1->break_time == "01:00:00") { $minval = 60; }
                    //     elseif($break_time1->break_time == "00:45:00") { $minval = 45; }
                    //     elseif($break_time1->break_time == "00:30:00") { $minval = 30; }
                    //     elseif($break_time1->break_time == "00:15:00") { $minval = 15; }
                    //     if($count_minues == 0)
                    //     {
                    //       $count_minues = $minval;
                    //     }
                    //     else{
                    //       $count_minues = $count_minues + $minval;
                    //     }
                    //   }
                    // }
                    // if($count_minues == 0)
                    // {
                    //   $break_hours = '';
                    // }
                    // elseif($count_minues < 60)
                    // {
                    //   $break_hours = $count_minues.' Minutes';
                    // }
                    // elseif($count_minues == 60)
                    // {
                    //   $break_hours = '1 Hour';
                    // }
                    // else{
                    //   if(floor($count_minues / 60) <= 9)
                    //   {
                    //     $h = floor($count_minues / 60);
                    //   }
                    //   else{
                    //     $h = floor($count_minues / 60);
                    //   }
                    //   if(($count_minues -   floor($count_minues / 60) * 60) <= 9)
                    //   {
                    //     $m = ($count_minues -   floor($count_minues / 60) * 60);
                    //   }
                    //   else{
                    //     $m = ($count_minues -   floor($count_minues / 60) * 60);
                    //   }
                    //   if($m == "00")
                    //   {
                    //     $break_hours = $h.' Hours';
                    //   }
                    //   else{
                    //     $break_hours = $h.':'.$m.' Hours';
                    //   }
                    // }
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
                    $current_date = date('Y-m-d');
                    if($current_date != $jobs->job_date)
                    {
                      $redcolor = 'color:#f00;';
                    }
                    elseif($jobs->color == 1){
                     $redcolor = 'color:#0f9600';
                    }
                    elseif($jobs->color == 0){
                      $redcolor = 'color:#666';
                    }
                    else{
                      $redcolor = '';
                    }
                    if($jobs->quick_job == 0){
                      $quick_job = 'No';                      
                      if($jobs->color == '1'){
                        $buttons = '<a style="'.$redcolor.'" href="javascript:" class="stop_class" data-element="'.$jobs->id.'" style="'.$redcolor.'">Stop</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a style="'.$redcolor.'" href="javascript:" class="create_new_quick" data-element="'.$jobs->id.'">Quick Job</a>';
                      }
                      else{
                        $buttons = '<a style="'.$redcolor.'; cursor:not-allowed" href="javascript:" data-element="'.$jobs->id.'" style="'.$redcolor.'">Stop</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a style="'.$redcolor.'; cursor:not-allowed" href="javascript:" data-element="'.$jobs->id.'">Quick Job</a>';
                      }
                    }
                    elseif($jobs->stop_time == '00:00:00'){
                      $quick_job = 'Yes'; 
                      $buttons = '<a style="'.$redcolor.'" href="javascript:" class="stop_class_quick" data-element="'.$jobs->id.'" style="'.$redcolor.'">Stop</a>';
                    }
                    else{
                      $quick_job = 'Yes'; 
                      $buttons = '';
                    }
            $output.='
            <tr>
              <td align="left" style="'.$redcolor.'">'.$i.'</td>
              <td align="left" style="'.$redcolor.'">'.$client_name.'</td>
              <td align="left" style="'.$redcolor.'">'.$task_name.'</td>
              <td align="left" style="'.$redcolor.'">'.$task_type.'</td>
              <td align="left" style="'.$redcolor.'">'.$quick_job.'</td>
              <td align="left" style="'.$redcolor.'">'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
              <td align="left" style="'.$redcolor.'">'.$data['start_time'] = date('H:i:s', strtotime($jobs->start_time)).'</td>
              <td align="left" style="'.$redcolor.'">
              <span id="job_time_refresh_'.$jobs->id.'" style="'.$redcolor.'">'.$jobtime.'</span> &nbsp;&nbsp;<a href="javascript:"><i class="fa fa-refresh job_time_refresh" aria-hidden="true" data-element="'.$jobs->id.'"></i></a>
              </td>
              <td align="center" style="'.$redcolor.'">'.$buttons.'</td>
            </tr>';
              
              $userid = Session::get('task_job_user');
            $joblist_child = DB::table('task_job')->where('user_id',$userid)->where('active_id',$jobs->id)->get();
                      $childi = 1;
                      if(count($joblist_child)){              
                        foreach ($joblist_child as $child) {
                          if($child->quick_job == 0 || $child->quick_job == 1){
                            if($child->status == 0){
                              $client_details = DB::table('cm_clients')->where('client_id', $child->client_id)->first();
                              if(count($client_details) != ''){
                                $client_name = $client_details->company;
                              }
                              else{
                                $client_name = 'N/A';
                              }
                              $task_details = DB::table('time_task')->where('id', $child->task_id)->first();
                              if(count($task_details) != ''){
                                $task_name = $task_details->task_name;
                                $task_type = $task_details->task_type;
                                if($task_type == 0){
                                  $task_type = '<i class="fa fa-desktop" style="margin-right:10px;"></i> Internal Task';
                                }
                                elseif($task_type == 1){
                                  $task_type = '<i class="fa fa-users" style="margin-right:10px;"></i> Client Task';
                                }
                                else{
                                  $task_type = '<i class="fa fa-globe" style="margin-right:10px;"></i> Global Task';
                                }
                              }
                              else{
                                $task_name = 'N/A';
                                $task_type = 'N/A';
                              }
                              
                              $created_date = $child->job_created_date;
                              $jobstart = strtotime($created_date.' '.$child->start_time);
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
                              $current_date = date('Y-m-d');
                              if($current_date != $child->job_date)
                              {
                                $redcolor = 'color:#f00;';
                              }
                              elseif($child->color == 1){
                               $redcolor = 'color:#0f9600';
                              }
                              elseif($child->color == 0){
                                $redcolor = 'color:#666';
                              }
                              else{
                                $redcolor = '';
                              }
                              if($child->quick_job == 0){
                                $quick_job = 'No';                      
                                if($child->color == '1'){
                                  $buttons = '<a style="'.$redcolor.'" href="javascript:" class="stop_class" data-element="'.$child->id.'" style="'.$redcolor.'">Stop</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a style="'.$redcolor.'" href="javascript:" class="create_new_quick" data-element="'.$child->id.'">Quick Job</a>';
                                }
                                else{
                                  $buttons = '<a style="'.$redcolor.'; cursor:not-allowed" href="javascript:" data-element="'.$child->id.'" style="'.$redcolor.'">Stop</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a style="'.$redcolor.'; cursor:not-allowed" href="javascript:" data-element="'.$child->id.'">Quick Job</a>';
                                }
                              }
                              elseif($child->stop_time == '00:00:00'){
                                $quick_job = 'Yes'; 
                                $buttons = '<a style="'.$redcolor.'" href="javascript:" class="stop_class_quick" data-element="'.$child->id.'" style="'.$redcolor.'">Stop</a>';
                              }
                              else{
                                $quick_job = 'Yes'; 
                                $buttons = '';
                              }
                          $output.='
                          <tr>
                            <td align="left" style="'.$redcolor.'">'.$i.'.'.$childi.'</td>
                            <td align="left" style="'.$redcolor.'">'.$client_name.'</td>
                            <td align="left" style="'.$redcolor.'">'.$task_name.'</td>
                            <td align="left" style="'.$redcolor.'">'.$task_type.'</td>
                            <td align="left" style="'.$redcolor.'">'.$quick_job.'</td>
                            <td align="left" style="'.$redcolor.'">'.date('d-M-Y', strtotime($child->job_date)).'</td>
                            <td align="left" style="'.$redcolor.'">'.$data['start_time'] = date('H:i:s', strtotime($child->start_time)).'</td>
                            <td align="left" style="'.$redcolor.'">';
                            if($child->job_time != "00:00:00")
                            {
                              $output.='<span style="'.$redcolor.'">'.$child->job_time.'</span>';
                            }
                            else{
                              $output.='<span id="job_time_refresh_'.$child->id.'" style="'.$redcolor.'">'.$jobtime.'</span> &nbsp;&nbsp;<a href="javascript:"><i class="fa fa-refresh job_time_refresh" aria-hidden="true" data-element="'.$child->id.'"></i></a>';
                            }
                            $output.='</td>
                            <td align="center" style="'.$redcolor.'">'.$buttons.'</td>
                          </tr>';
                            $childi++;
                          }
                        }
                      }
                    }
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
                        <td align="left"></td>
                        <td align="center">Empty</td>
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



      <div class="col-lg-12" style="margin-top: 50px; background: #fff; padding: 25px 15px;">

        

         <h4 class="col-lg-12" style="padding: 0px; font-weight: 600">Job of the Day / Closed Job</h4>



        <table class="display nowrap fullviewtablelist" id="closed_job" width="100%">

          <thead>

            <tr style="background: #fff;">

              <th width="2%" style="text-align: left;">S.No</th>

              <th style="text-align: left;">Client Name</th>

              <th style="text-align: left;">Task Name</th>

              <th style="text-align: left;">Task Type</th>

              <th style="text-align: left;">Quick Break</th>

              <th style="text-align: left;">Date</th>

              <th style="text-align: left;">Start Time</th>   

              <th style="text-align: left;">Job Time</th>

              <th style="text-align: left;">Stop Time</th>

              <th style="text-align: center;">Action</th>

          </tr>

          </thead>

          <tbody id="tbody_jobclosed">

            <?php

            $output='';
            $i=1;            
            if(count($joblist)){
              foreach ($joblist as $jobs) {
                $current_date = date('Y-m-d');
                if($current_date == $jobs->job_date)
                {
                    if($jobs->status == 1 ){
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
                        $task_type = '<i class="fa fa-globe" style="margin-right:10px;"></i> Global Task';
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

                      $job_time = $jobs->job_time;

                    }



                    if($jobs->comments != "") { $comments = $jobs->comments; } else { $comments = 'No Comments Found'; }

                    $output.='

                    <tr>

                      <td align="left">'.$i.'</td>

                      <td align="left">'.$client_name.'</td>

                      <td align="left">'.$task_name.'</td>

                      <td align="left">'.$task_type.'</td>

                      <td align="left">'.$quick_job.'</td>

                      <td align="left">'.date('d-M-Y', strtotime($jobs->job_date)).'</td>

                      <td align="left">'.date('H:i:s', strtotime($jobs->start_time)).'</td>

                      <td align="left">'.$job_time.'</td>

                      <td align="left">'.date('H:i:s', strtotime($jobs->stop_time)).'</td>

                      <td align="center">
                      <a href="javascript:" class="fa fa-comment" data-toggle="modal" data-target="#comments_'.$jobs->id.'" title="View Comments"></a>

                        <div id="comments_'.$jobs->id.'" class="modal fade" role="dialog" >

                            <div class="modal-dialog" style="width:20%">

                              <div class="modal-content">

                                <div class="modal-header">

                                  <button type="button" class="close" data-dismiss="modal">&times;</button>

                                  <h4 class="modal-title">Comments</h4>

                                </div>

                                <div class="modal-body">

                                  '.$comments.'

                                </div>

                                <div class="modal-footer">

                                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                </div>

                              </div>

                            </div>

                          </div>

                      </td>

                    </tr>';
                    $userid = Session::get('task_job_user');
                    $joblist_child = DB::table('task_job')->where('user_id',$userid)->where('active_id',$jobs->id)->get();
                    $childi=1;            
                      if(count($joblist_child)){
                        foreach ($joblist_child as $child) {
                          $current_date = date('Y-m-d');
                          if($current_date == $child->job_date)
                          {
                              if($child->status == 1 ){
                              $client_details = DB::table('cm_clients')->where('client_id', $child->client_id)->first();
                              if(count($client_details) != ''){
                                $client_name = $client_details->company;
                              }
                              else{
                                $client_name = 'N/A';
                              }
                              $task_details = DB::table('time_task')->where('id', $child->task_id)->first();
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
                                  $task_type = '<i class="fa fa-globe" style="margin-right:10px;"></i> Global Task';
                                }
                              }
                              else{
                                $task_name = 'N/A';
                                $task_type = 'N/A';
                              }
                              if($child->quick_job == 0){

                                $quick_job = 'No';

                                $job_time = $child->job_time;

                              }

                              else{

                                $quick_job = 'Yes'; 

                                $job_time = $child->job_time;

                              }



                              if($child->comments != "") { $comments = $child->comments; } else { $comments = 'No Comments Found'; }

                              $output.='

                              <tr>

                                <td align="left">'.$i.'.'.$childi.'</td>

                                <td align="left">'.$client_name.'</td>

                                <td align="left">'.$task_name.'</td>

                                <td align="left">'.$task_type.'</td>

                                <td align="left">'.$quick_job.'</td>

                                <td align="left">'.date('d-M-Y', strtotime($child->job_date)).'</td>

                                <td align="left">'.date('H:i:s', strtotime($child->start_time)).'</td>

                                <td align="left">'.$job_time.'</td>

                                <td align="left">'.date('H:i:s', strtotime($child->stop_time)).'</td>

                                <td align="center">
                                <a href="javascript:" class="fa fa-comment" data-toggle="modal" data-target="#comments_'.$child->id.'" title="View Comments"></a>

                                  <div id="comments_'.$child->id.'" class="modal fade" role="dialog" >

                                      <div class="modal-dialog" style="width:20%">

                                        <div class="modal-content">

                                          <div class="modal-header">

                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                            <h4 class="modal-title">Comments</h4>

                                          </div>

                                          <div class="modal-body">

                                            '.$comments.'

                                          </div>

                                          <div class="modal-footer">

                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                          </div>

                                        </div>

                                      </div>

                                    </div>

                                </td>

                              </tr>';
                              $childi++;
                              }
                          }
                        }
                      }
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

                        <td align="left"></td>

                        <td align="right">Empty</td>

                        <td align="left"></td>

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

      <div class="col-lg-12 summary_div" style="margin-top: 50px; background: #fff; padding: 25px 15px;<?php if(Session::has('task_job_user') && Session::get('task_job_user') != "") { echo 'display:block'; } else{ echo 'display:none'; } ?>">

        

         <h4 class="col-lg-12" style="padding: 0px; font-weight: 600">Summary</h4>



        <?php

      if(Session::has('task_job_user') && Session::get('task_job_user') != "")
      {
        $currentdate = date('Y-m-d');
        $user_details = DB::table('user')->where('user_id',Session::get('task_job_user'))->first();
        $getdetails = DB::table('task_job')->where('user_id',Session::get('task_job_user'))->where('job_date',$currentdate)->get();
        $currentdatetime = date('Y-m-d H:i:s');
        $spendminutes = 0;
        $spendhours = 0;
        if(count($getdetails))
        {
          foreach($getdetails as $details) {
            if($details->quick_job == 1 || $details->status == 1){

              $todaystarttime = strtotime($currentdate.' '.$details->start_time);
              $currenttime = strtotime($currentdate.' '.$details->stop_time);
                 $diff = $currenttime - $todaystarttime;
                if($spendminutes == 0)
                {
                  $spendminutes = round(abs($diff) / 60,2);
                }
                else
                {
                  $spendminutes = $spendminutes + round(abs($diff) / 60,2);
                }
                if($spendhours == 0)
                {
                  $spendhours = round(abs($diff)/3600, 1);
                }
                else
                {
                  $spendhours = $spendhours + round(abs($diff)/3600, 1);
                }
            }
          }
        }
        else{
          $spendminutes = 0;
          $spendhours = 0;
        }    
        echo '<div class="col-lg-12 user_details_div" style="margin-top: 30px;">        
          <div class="col-lg-5 col-md-5 col-sm-5" style="border:1px solid #000;border-bottom:0px solid;">
            <h5 style="padding: 0px; font-weight: 600">Total number of minutes worked for today by <span class="job_username">'.$user_details->firstname.'</span> : </h5>
          </div>
          <div class="col-lg-2 col-md-2 col-sm-2" style="border:1px solid #000;border-bottom:0px solid;border-left:0px solid">
              <h5 style="padding: 0px; font-weight: 600"><span id="job_username_minutes">'.$spendminutes.'</span> Minutes</h5>
          </div>
        </div>

        <div class="col-lg-12 user_details_div" style="margin-top: 0px;">        
          <div class="col-lg-5 col-md-5 col-sm-5" style="border:1px solid #000;border-top:0px solid;">
            <h5 style="padding: 0px; font-weight: 600">Total number of hours worked for today by <span class="job_username">'.$user_details->firstname.'</span> : </h5>
          </div>
          <div class="col-lg-2 col-md-2 col-sm-2" style="border:1px solid #000;border-top:0px solid;border-left:0px solid">
              <h5 style="padding: 0px; font-weight: 600"><span id="job_username_hours">'.$spendhours.'</span> Hours</h5>
          </div>
        </div>';

      }

      else{

        echo '<div class="col-lg-12 user_details_div" style="margin-top: 30px;">        
          <div class="col-lg-5 col-md-5 col-sm-5" style="border:1px solid #000;border-bottom:0px solid;">
            <h5 style="padding: 0px; font-weight: 600">Total number of minutes worked for today by <span class="job_username">Ciaran</span> : </h5>
          </div>
          <div class="col-lg-2 col-md-2 col-sm-2" style="border:1px solid #000;border-bottom:0px solid;border-left:0px solid">
              <h5 style="padding: 0px; font-weight: 600"><span id="job_username_minutes">120</span> Minutes</h5>
          </div>
        </div>

        <div class="col-lg-12 user_details_div" style="margin-top: 0px;">        
          <div class="col-lg-5 col-md-5 col-sm-5" style="border:1px solid #000;border-top:0px solid;">
            <h5 style="padding: 0px; font-weight: 600">Total number of hours worked for today by <span class="job_username">Ciaran</span> : </h5>
          </div>
          <div class="col-lg-2 col-md-2 col-sm-2" style="border:1px solid #000;border-top:0px solid;border-left:0px solid">
              <h5 style="padding: 0px; font-weight: 600"><span id="job_username_hours">2</span> Hours</h5>
          </div>
        </div>';
      }

      ?>



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

      if(result['job_available'] == 1)

      {

        $(".create_new").addClass("not_create");

      }

      else{

        $(".create_new").removeClass("not_create");

      }

        $("#tbody_active").html(result['activejob']);

        $("#tbody_jobclosed").html(result['closejob']);

        $("#user_details_div").show();

        $(".summary_div").show();

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

  if($(e.target).hasClass('not_create'))

  {

    alert("You can only have 1 active job! You must stop the current active job before you create a New Active Job");

  }

  else{

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

      $(".acive_id").val('');

    }

  }

  

  if($(e.target).hasClass('create_new_quick')) {

    $("body").addClass('loading');

    var element = $(e.target).attr("data-element");

    $(".acive_id").val(element);

    $.ajax({

      url:"<?php echo URL::to('user/get_quick_break_details'); ?>",

      type:"get",

      dataType:"json",

      data:{jobid:element},

      success: function(result)

      {

        $(".mark_internal").prop("checked",false);
        $(".client_search_class").val("");
        $(".client_group").show();
        $(".internal_tasks_group").hide();
        $(".user_id").val(result['userid']);
        $(".hidden_activejob_starttime").val(result['start_time']);
        $(".create_new_model").modal("show");
        $("#quickjob").val('1');
        $(".job_title").html('Create a Quick Job');
        $(".job_button_name").val('Create a Quick Job');
        $(".create_dateclass").prop("required",false);

        //$(".stop_time").prop("required",true);

        $(".stop_time").val('');
        $(".date_group").hide();
        $(".start_button_quick").show();
        $(".start_button").hide();
        $(".job_button_name").hide();
        $(".start_group").hide();
        $(".stop_group").hide();
        $(".client_search_class").val('');
        $(".tasks_group").hide();
        $("body").removeClass('loading');
      }

    });

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

      var child_value = $(".tasks_li_internal:first").text();

      $(".task-choose_internal:first-child").text(child_value);

    }

    else{

      $(".internal_type").val('1');

      $(".client_group").show();

      $(".internal_tasks_group").hide();

      $(".tasks_group").hide();

      $(".client_search_class").prop("required",true);

      $("#idtask").val('');

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

  if(e.target.id == "stop_job")

  {

    var job_time = $(".calculate_job_time").val();

    if(job_time == "" || typeof job_time === "undefined")

    {

      alert("Please Choose the Stop time to calculate the job time and then proceed with stop button.");

    }

    else{

      $(".breaktime_div").show();

      $(e.target).hide();

      $("#stop_active_job").show();

    }

  }


  if(e.target.id == "stop_job_quick")
  {

    var job_time = $("#calculate_job_time_quick").val();
    if(job_time == "" || typeof job_time === "undefined")
    {
      alert("Please Choose the Stop time to calculate the job time and then proceed with stop button.");
    }   

  }




  if($(e.target).hasClass('add_minutes'))

  {

    $("body").addClass("loading");

    var element = $(e.target).attr("data-element");

    var break_time = $("#break_time_val").val();

    var jobtime = $(".calculate_job_time").val();

    var total_quick_jobs = $("#total_quick_jobs").val();

    $.ajax({

      url:"<?php echo URL::to('user/calculate_break_time'); ?>",

      type:"get",

      dataType:"json",

      data:{element:element,break_time:break_time,jobtime:jobtime,total_quick_jobs:total_quick_jobs},

      success: function(result)

      {

        if(result['alert'] == 1)

        {

          alert("Break Time exceeds the job time. Please make sure you choose the correct break time.");

        }

        else{

          $("#break_time").val(result['break_hours']);
          $("#total_breaks").val(result['break_hours_another']);
          $("#break_time_val").val(result['count']);
          $("#total_time_minutes_format").val(result['total_time_minutes_format']);

        }

      }

    })

  }

  if(e.target.id == "reset_breaktime")

  {

    $("#break_time").val('');
    $("#total_time_minutes_format").val('');
    $("#total_breaks").val('');
    $("#break_time_val").val(0);

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
        $("#total_quick_jobs").val(result['quick_job_times']);
        $(".stop_start_time").val(result['start_time']);

        $(".stop_model").modal("show");

        $(".comments").val('');



        $('#stop_time1').data("DateTimePicker").minDate(moment().startOf('day').hour(result['start_hour']).minute(result['start_min']));

        $('#stop_time1').data("DateTimePicker").maxDate(moment().startOf('day').hour(23).minute(59));

        $('.stop_time1').data("DateTimePicker").minDate(moment().startOf('day').hour(result['start_hour']).minute(result['start_min']));

        $('.stop_time1').data("DateTimePicker").maxDate(moment().startOf('day').hour(23).minute(59));

        $(".stop_time1").val('');



        $("#break_time").val('');

        $("#break_time_val").val(0);



        $(".breaktime_div").hide();

        $("#stop_job").show();

        $("#stop_active_job").hide();

        $(".calculate_job_time").val('');

        $(".stop_class").val('Stop Active Job')

      }

    })

  }


  if($(e.target).hasClass('stop_class_quick')) {

    var id = $(e.target).attr("data-element");

    $.ajax({

      url:"<?php echo URL::to('user/stop_job_details')?>",

      dataType: "json",

      data:{jobid:id},

      success:function(result){

        $(".idclass").val(result['id']);

        $(".dateclass").val(result['date']);

        $(".stop_start_time").val(result['start_time']);

        $(".stop_quick_model").modal("show");

        $(".comments").val('');



        $('#stop_time2').data("DateTimePicker").minDate(moment().startOf('day').hour(result['start_hour']).minute(result['start_min']));

        $('#stop_time2').data("DateTimePicker").maxDate(moment().startOf('day').hour(23).minute(59));

        $('.stop_time2').data("DateTimePicker").minDate(moment().startOf('day').hour(result['start_hour']).minute(result['start_min']));

        $('.stop_time2').data("DateTimePicker").maxDate(moment().startOf('day').hour(23).minute(59));

        $(".stop_time2").val('');

        

        $("#stop_job").show();

        $("#stop_active_job").hide();

        $("#calculate_job_time_quick").val('');

        $(".stop_class").val('Stop Quick Job')

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

  if($(e.target).hasClass('job_button_name'))

  {

    var idtask = $("#idtask").val();

    if($('.tasks_group').is(":visible"))

    {

      if(idtask == "" || typeof idtask === "undefined")

      {

        alert("Please Select any of the task from the dropdown.");

        return false;

      }

    }

    if($('.internal_tasks_group').is(":visible"))

    {

      if(idtask == "" || typeof idtask === "undefined")

      {

        alert("Please Select any of the internal task from the dropdown.");

        return false;

      }

    }

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

             format: 'L',

             format: 'DD-MMM-YYYY',

          })

          $(".create_startclass").datetimepicker({

             defaultDate: fullDate,

             format: 'HH:mm',

          });

          $("#start_time").datetimepicker({

             defaultDate: fullDate,

             format: 'HH:mm',

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

               format: 'L',

               format: 'DD-MMM-YYYY',

            })

            $(".create_startclass").datetimepicker({

               defaultDate: fullDate,

               format: 'LT',

               format: 'HH:mm',

            });

            $("#start_time").datetimepicker({

               defaultDate: fullDate,

               format: 'LT',

               format: 'HH:mm',

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

    if($('.create_startclass').data("DateTimePicker"))

    {

      $('.create_startclass').data("DateTimePicker").destroy();  

    }

    if($('#start_time').data("DateTimePicker"))

    {

      $('#start_time').data("DateTimePicker").destroy();

    }



    var active_starttime = $(".hidden_activejob_starttime").val();

    var splittime = active_starttime.split(":");



   

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

           format: 'L',

           format: 'DD-MMM-YYYY',

        })



        $(".create_startclass").datetimepicker({

           format: 'LT',

           format: 'HH:mm',

           minDate: moment().startOf('day').hour(splittime[0]).minute(splittime[1]),

           maxDate: moment().startOf('day').hour(23).minute(59),

        })



        $('#start_time').datetimepicker({

            format: 'LT',

            format: 'HH:mm',

            minDate: moment().startOf('day').hour(splittime[0]).minute(splittime[1]),

            maxDate: moment().startOf('day').hour(23).minute(59),

        })

        $('#stop_time').datetimepicker({

            defaultDate: fullDate,

            format: 'LT',

            format: 'HH:mm',

        })



        $(".start_group").show();

        $(".stop_group").hide();    

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

               format: 'L',

               format: 'DD-MMM-YYYY',

            })           

            $(".create_startclass").datetimepicker({

               format: 'LT',

               format: 'HH:mm',

               minDate: moment().startOf('day').hour(splittime[0]).minute(splittime[1]),

               maxDate: moment().startOf('day').hour(23).minute(59),

            })



            $('#start_time').datetimepicker({

               format: 'LT',

               format: 'HH:mm',

               minDate: moment().startOf('day').hour(splittime[0]).minute(splittime[1]),

               maxDate: moment().startOf('day').hour(23).minute(59),

            })

            $('#stop_time').datetimepicker({

                format: 'LT',

                format: 'HH:mm',

            }) 



            $(".start_group").show();

            $(".stop_group").hide();    

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

if($(e.target).hasClass('create_new_quick_altert')){
    alert("Please Stop Quick Job.");
    return false;
}
if($(e.target).hasClass('stop_class_altert')){
    alert("Please Stop Quick Job.");
    return false;
}









});

</script>



<script type="text/javascript">

    $(function () {

        $('#stop_time').datetimepicker({

            format: 'LT',

            format: 'HH:mm',

        });

        $('#stop_time1').datetimepicker({

            format: 'LT',

            format: 'HH:mm',

        });

        $('.stop_time1').datetimepicker({

            format: 'LT',

            format: 'HH:mm',

        });

        $('#stop_time2').datetimepicker({

            format: 'LT',

            format: 'HH:mm',

        });

        $('.stop_time2').datetimepicker({

            format: 'LT',

            format: 'HH:mm',

        });

        $("#start_time").on("dp.change", function (e) {

            $('#stop_time').data("DateTimePicker").minDate(e.date);

            $('#stop_time').data("DateTimePicker").maxDate(moment().startOf('day').hour(23).minute(59));

        });



        $("#stop_time1").on("dp.hide", function (e) {

            var start_time = $(".stop_start_time").val();

            var stop_time = $(".stop_time1").val();
            if(stop_time == "")
            {

            }
            else{
                $.ajax({
                  url:"<?php echo URL::to('user/calculate_job_time'); ?>",
                  type:"get",
                  data:{start_time:start_time,stop_time:stop_time},
                  success: function(result)
                  {
                    $(".calculate_job_time").val(result)
                  }
                });
            }
        });


        $("#stop_time2").on("dp.hide", function (e) {

            var start_time = $(".stop_start_time").val();

            var stop_time = $(".stop_time2").val();
            if(stop_time == "")
            {

            }
            else{
                $.ajax({
                  url:"<?php echo URL::to('user/calculate_job_time'); ?>",
                  type:"get",
                  data:{start_time:start_time,stop_time:stop_time},
                  success: function(result)
                  {
                    $("#calculate_job_time_quick").val(result)
                  }
                });
            }
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

            format: 'L',

            format: 'DD-MMM-YYYY',

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
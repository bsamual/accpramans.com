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

#total_time_minutes_format{
  color:#0f9600;
}
#reset_breaktime{

  background: #000;

    color: #fff;

        margin-left: -17px;

}
#edit_reset_breaktime{

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

.edit_add_minutes_div{    

    border: 1px solid #dfdfdf;

}

.edit_add_minutes_div:hover{    

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

                <input type="hidden" id="hidden_job_id" value="" name="hidden_job_id">

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

                <div class="form-title">Current Date</div>

                <label class="input-group datepicker-only-init">

                    <input type="text" class="form-control create_dateclass" placeholder="Select Date" name="date" style="font-weight: 500;" required readonly/>

                    <span class="input-group-addon">

                        <i class="glyphicon glyphicon-calendar"></i>

                    </span>

                </label>

            </div>



            <div class="form-group start_group">

                <div class="form-title">Choose Start Time</div>

                <div class='input-group date' id='start_time'>

                    <input type='text' class="form-control create_startclass" placeholder="Select Start Time" name="starttime" style="font-weight: 500;" required autocomplete="off" />

                    <span class="input-group-addon">

                        <span class="glyphicon glyphicon-time"></span>

                    </span>

                </div>

            </div>



            <div class="form-group stop_group">

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
            <input type="hidden" class="taskjob_id" value="" name="taskjob_id">

            <input type="hidden" value="" class="currentdate" name="" >
            <input type="hidden" value="" class="add_edit_job" name="add_edit_job" >


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

                        <input type='text' class="form-control stop_start_time" readonly placeholder="Select Start Time" name="stoptime" style="font-weight: 500;background-color: #eceaea !important" required autocomplete="off"/>

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

                        <input type='text' class="form-control stop_start_time" readonly placeholder="Select Start Time" name="stoptime" style="font-weight: 500;background-color: #eceaea !important" required autocomplete="off"/>

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



<div class="modal fade edit_stop_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="margin-top: 5%;">

  <div class="modal-dialog modal-sm" role="document" style="width:30%">

    <form action="<?php echo URL::to('user/edit_time_job_update')?>" method="post">

        <div class="modal-content">

          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            <h4 class="modal-title stop_title">Edit Job</h4>

          </div>

          <div class="modal-body">           

            <div class="form-group">
              <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                  <input type="checkbox" class="edit_mark_internal" name="internal" id="edit_mark_internal">
                  <label for="edit_mark_internal" style="font-size: 14px; font-weight: normal; cursor: pointer;">Internal Job</label>
                  <input type="hidden" class="internal_type" value="">
                </div>
              </div>
            </div>
            <div class="form-group edit_client_group">
              <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                  <div class="form-title">
                    Choose Client
                  </div>                  
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                  <input type="input" class="form-control edit_client_name" required value="">
                  <input type="hidden" class="edit_client_class" name="clientid" value="">
                </div>
              </div>
            </div>

            <div class="form-group edit_task_group">
              <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                  <div class="form-title">
                    Choose Task
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                  <div class="dropdown" style="width: 100%">
                    <a class="btn btn-default dropdown-toggle tasks_drop" data-target="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="width: 100%">
                      <span class="task-choose">Select Task</span>  <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu task_details" role="menu"  aria-labelledby="dropdownMenu" style="width: 100%">                  

                    </ul>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group edit_internal_task_group">
              <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                  <div class="form-title">
                    Choose Task
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
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
              </div>
            </div>

            <input type="hidden" id="edit_idtask" value="" name="task_id">


            <div class="form-group start_group">

                <div class="row">

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class="form-title">Job Date :</div>

                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class='input-group date'>                    

                        <input class="form-control edit_dateclass" name="date" value="" readonly autocomplete="off">

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

                    <div class='input-group edit_date'>

                        <input type='text' class="form-control edit_start_time" readonly placeholder="Select Start Time" name="stoptime" style="font-weight: 500;background-color: #eceaea !important" required autocomplete="off"/>

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

                    <div class='input-group date' id='edit_stop_time1'>

                        <input type='text' class="form-control edit_stop_time1" placeholder="Select Stop Time" name="stoptime" style="font-weight: 500;" required autocomplete="off"/>

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

                        <input type='text' class="form-control edit_calculate_job_time" name="edit_calculate_job_time" style="font-weight: 500;background-color: #eceaea !important" disabled/>

                        <span class="input-group-addon">

                            <span class="glyphicon glyphicon-time"></span>

                        </span>

                    </div>

                  </div>

                </div>

            </div>

            <div class="form-group">

                <textarea class="form-control edit_comments" name="comments" placeholder="Enter Comments"></textarea>

                <input type="hidden" class="idclass" name="id">

            </div>

            <div class="form-group breaktime_div_edit_close" style="display:none">

                <div class="row">

                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">

                    <div class="form-title" style="margin-top:10px; font-weight:800;">Actual Job Time for Primary Active Job : </div>

                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class='input-group date'>
                        <input type='text' class="form-control" id="primary_job_time" name="primary_job_time" style="font-weight: 500;" readonly/>
                    </div>

                  </div>

                </div>

            </div>

            <div class="form-group breaktime_div_edit" style="display:none">

                <div class="row">

                  <div class="col-lg-4 col-md-2 col-sm-6 col-xs-6">

                    <div class="form-title" style="margin-top:10px; font-weight:800;">Break Time : </div>

                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">

                    <div class='input-group date'>

                        <input type='text' class="form-control" id="edit_break_time" name="breaktime" style="font-weight: 500;" readonly/>

                        <input type="hidden" id="edit_break_time_val" name="edit_break_time_val" value="0">

                    </div>

                  </div>

                  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">

                    <a href="javascript:" class="btn btn-sm btn-primary" id="edit_reset_breaktime"> Reset </a>

                  </div>

                </div>

            </div>

            <div class="form-group breaktime_div_edit" style="display:none">

                <div class="row">

                  <a href="javascript:" class="col-lg-3 col-md-3 col-sm-6 col-xs-6 edit_add_minutes_div edit_add_minutes" data-element="15"> <i class="fa fa-plus"></i> 15 Minutes</a>

                  <a href="javascript:" class="col-lg-3 col-md-3 col-sm-6 col-xs-6 edit_add_minutes_div edit_add_minutes" data-element="30"> <i class="fa fa-plus"></i> 30 Minutes</a>

                  <a href="javascript:" class="col-lg-3 col-md-3 col-sm-6 col-xs-6 edit_add_minutes_div edit_add_minutes" data-element="45"> <i class="fa fa-plus"></i> 45 Minutes</a>

                  <a href="javascript:" class="col-lg-3 col-md-3 col-sm-6 col-xs-6 edit_add_minutes_div edit_add_minutes" data-element="60"> <i class="fa fa-plus"></i> 60 Minutes</a>

                </div>

                <div class="form-group" style="margin-top:20px;">

                <div class="row">

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class="form-title">Job Time :</div>

                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class='input-group date stop_jom_time'>

                        <input type='text' class="form-control edit_calculate_job_time" name="edit_calculate_job_time" style="font-weight: 500;background-color: #eceaea !important" disabled/>

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

                        <input class="form-control" id="edit_total_quick_jobs" value="" autocomplete="off" disabled>

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

                        <input class="form-control" id="edit_total_breaks" value="" autocomplete="off" disabled>

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

                        <input class="form-control" id="edit_total_time_minutes_format" value="" autocomplete="off" disabled>

                    </div>

                  </div>

                </div>

            </div>

            </div>

            
          </div>

          <div class="modal-footer">
            <input type="hidden" id="hidden_edit_job_id" value="" name="hidden_edit_job_id">
            <input type="hidden" id="edit_quickjob" value="" name="quick_job">
            <input type="submit" class="common_black_button" id="edit_stop_active_job" value="Update">        

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

              <th style="text-align: left;">Stop Time</th>

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
                      $client_name = $client_details->company.' ('.$jobs->client_id.')';
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
                      if($created_date == date('Y-m-d'))
                      {
                          $negative = '-';
                          $jobdiff  = $jobstart - $jobend;
                      }
                      else{
                        $negative = '';
                        $todate = date('Y-m-d', strtotime("+1 day", $jobend));
                        $jobend   = strtotime($todate.' '.date('H:i:s'));
                        $jobdiff  = $jobend - $jobstart;
                      }
                    }
                    else{
                      $negative = '';
                      $jobdiff  = $jobend - $jobstart;
                    }

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

                    $explode_job_minutes = explode(":",$jobtime);
                    $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
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
                        $buttons = '<a style="'.$redcolor.'" href="javascript:" class="stop_class" data-element="'.$jobs->id.'" style="'.$redcolor.'">Stop</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a style="'.$redcolor.'" href="javascript:" class="create_new_quick" data-element="'.$jobs->id.'">Quick Job</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript:" class="edit_active_job" data-element="'.$jobs->id.'" style="'.$redcolor.'">Edit Job</a>';
                      }
                      else{
                        $buttons = '<a style="'.$redcolor.'; cursor:not-allowed" href="javascript:" data-element="'.$jobs->id.'" style="'.$redcolor.'">Stop</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a style="'.$redcolor.'; cursor:not-allowed" href="javascript:" data-element="'.$jobs->id.'">Quick Job</a>';
                      }
                    }
                    elseif($jobs->stop_time == '00:00:00'){
                      $quick_job = 'Yes'; 
                      $buttons = '<a style="'.$redcolor.'" href="javascript:" class="stop_class_quick" data-element="'.$jobs->id.'" style="'.$redcolor.'">Stop</a>|&nbsp;&nbsp;<a href="javascript:" class="edit_quick_job" data-element="'.$jobs->id.'" style="'.$redcolor.'">Edit Job</a>';
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
              <td align="left" style="'.$redcolor.'">N/A</td>
              <td align="left" style="'.$redcolor.'">
              <span id="job_time_refresh_'.$jobs->id.'" style="'.$redcolor.'">'.$jobtime.' ('.$total_minutes.')</span> &nbsp;&nbsp;<a href="javascript:"><i class="fa fa-refresh job_time_refresh" aria-hidden="true" data-element="'.$jobs->id.'"></i></a>
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
                                $client_name = $client_details->company.' ('.$child->client_id.')';
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
                                if($created_date == date('Y-m-d'))
                                {
                                    $childnegative = '-';
                                    $jobdiff  = $jobstart - $jobend;
                                }
                                else{
                                  $childnegative = '';
                                  $todate = date('Y-m-d', strtotime("+1 day", $jobend));
                                  $jobend   = strtotime($todate.' '.date('H:i:s'));
                                  $jobdiff  = $jobend - $jobstart;
                                }
                              }
                              else{
                                $childnegative = '';
                                $jobdiff  = $jobend - $jobstart;
                              }

                             
                              
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
                              $explode_job_minutes = explode(":",$jobtime);
                              $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);
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
                                $buttons = '<a style="'.$redcolor.'" href="javascript:" class="stop_class_quick" data-element="'.$child->id.'" style="'.$redcolor.'">Stop</a>|&nbsp;&nbsp;<a href="javascript:" class="edit_quick_job" data-element="'.$child->id.'" style="'.$redcolor.'">Edit Job</a>';
                              }
                              else{
                                $quick_job = 'Yes'; 
                                $buttons = '<a href="javascript:" class="edit_quick_job" data-element="'.$child->id.'" style="'.$redcolor.'">Edit Job</a>';
                              }
                          $output.='
                          <tr>
                            <td align="left" style="'.$redcolor.'">'.$i.'.'.$childi.'</td>
                            <td align="left" style="'.$redcolor.'">'.$client_name.'</td>
                            <td align="left" style="'.$redcolor.'">'.$task_name.'</td>
                            <td align="left" style="'.$redcolor.'">'.$task_type.'</td>
                            <td align="left" style="'.$redcolor.'">'.$quick_job.'</td>
                            <td align="left" style="'.$redcolor.'">'.date('d-M-Y', strtotime($child->job_date)).'</td>
                            <td align="left" style="'.$redcolor.'">'.date('H:i:s', strtotime($child->start_time)).'</td>';
                            if($child->stop_time != "00:00:00")
                            {
                              $output.='<td align="left" style="'.$redcolor.'">'.date('H:i:s', strtotime($child->stop_time)).'</td>';
                            }
                            else{
                              $output.='<td align="left" style="'.$redcolor.'">N/A</td>';
                            }

                            $output.='<td align="left" style="'.$redcolor.'">';
                            if($child->stop_time != "00:00:00")
                            {
                              $output.='<span style="'.$redcolor.'">'.$child->job_time.'</span>';
                            }
                            else{
                              $output.='<span id="job_time_refresh_'.$child->id.'" style="'.$redcolor.'">'.$childnegative.' '.$jobtime.' ('.$total_minutes.')</span> &nbsp;&nbsp;<a href="javascript:"><i class="fa fa-refresh job_time_refresh" aria-hidden="true" data-element="'.$child->id.'"></i></a>';
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
                      $client_name = $client_details->company.' ('.$jobs->client_id.')';
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

                      $get_quick_jobs = DB::table('task_job')->where('active_id',$jobs->id)->get();
                      $quick_minutes = 0;
                      if(count($get_quick_jobs))
                      {
                        foreach($get_quick_jobs as $quickjobs_single)
                        {
                          $total_quick_jobs_1 = explode(':', $quickjobs_single->job_time);
                          $minutes = ($total_quick_jobs_1[0]*60) + ($total_quick_jobs_1[1]) + ($total_quick_jobs_1[2]/60);
                          if($quick_minutes == 0)
                          {
                            $quick_minutes = $minutes;
                          }
                          else{
                            $quick_minutes = $quick_minutes + $minutes;
                          }
                        }
                      }

                      $break_time_min = DB::table('job_break_time')->where('job_id',$jobs->id)->where('break_time','!=','00:00:00')->first();
                      $break_timee_minutes = 0;
                      if(count($break_time_min))
                      {
                        $break_timee = explode(':', $break_time_min->break_time);
                        $break_timee_minutes = ($break_timee[0]*60) + ($break_timee[1]) + ($break_timee[2]/60);
                      }


                      $quick_minutes = $quick_minutes + $break_timee_minutes;

                      $job_timee = explode(':', $jobs->job_time);
                      $job_timee_minutes = ($job_timee[0]*60) + ($job_timee[1]) + ($job_timee[2]/60);

                      $job_time_min = $job_timee_minutes - $quick_minutes;

                      if(floor($job_time_min / 60) <= 9)
                      {
                        $h = '0'.floor($job_time_min / 60);
                      }
                      else{
                        $h = floor($job_time_min / 60);
                      }
                      if(($job_time_min -   floor($job_time_min / 60) * 60) <= 9)
                      {
                        $m = '0'.($job_time_min -   floor($job_time_min / 60) * 60);
                      }
                      else{
                        $m = ($job_time_min -   floor($job_time_min / 60) * 60);
                      }
                      $job_time = $h.':'.$m.':00';
                    }
                    else{
                      $quick_job = 'Yes'; 
                      $job_time = $jobs->job_time;
                    }
                    $explode_job_minutes = explode(":",$job_time);
                    $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);




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

                      <td align="left">'.$job_time.' ('.$total_minutes.')</td>

                      <td align="left">'.date('H:i:s', strtotime($jobs->stop_time)).'</td>

                      <td align="center">
                      <a href="javascript:" class="fa fa-comment" data-toggle="modal" data-target="#comments_'.$jobs->id.'" title="View Comments"></a>
                      &nbsp;&nbsp;|&nbsp;&nbsp;
                      <a href="javascript:" class="edit_close_job" data-element="'.$jobs->id.'">Edit Job</a>

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
                                $client_name = $client_details->company.' ('.$child->client_id.')';
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

                              $explode_job_minutes = explode(":",$job_time);
                              $total_minutes = ($explode_job_minutes[0]*60) + ($explode_job_minutes[1]);



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

                                <td align="left">'.$job_time.' ('.$total_minutes.')</td>

                                <td align="left">'.date('H:i:s', strtotime($child->stop_time)).'</td>

                                <td align="center">
                                <a href="javascript:" class="fa fa-comment" data-toggle="modal" data-target="#comments_'.$child->id.'" title="View Comments"></a>

                                &nbsp;&nbsp;|&nbsp;&nbsp;

                                <a href="javascript:" class="edit_close_job" data-element="'.$child->id.'">Edit Job</a>

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
            $getdetails_active_jobs = DB::table('task_job')->where('user_id',Session::get('task_job_user'))->where('job_date',$currentdate)->where('quick_job',0)->where('stop_time','!=','00:00:00')->get();
            $getdetails_active_jobs_num = DB::table('task_job')->where('user_id',Session::get('task_job_user'))->where('job_date',$currentdate)->where('stop_time','00:00:00')->count();


            $quick_jobs_count = DB::table('task_job')->where('user_id',Session::get('task_job_user'))->where('job_date',$currentdate)->where('quick_job',1)->count();

            $getdetails_quick_jobs = DB::table('task_job')->where('user_id',Session::get('task_job_user'))->where('job_date',$currentdate)->where('quick_job',1)->get();

            $currentdatetime = date('Y-m-d H:i:s');
            $spendminutes = 0;
            $spendquickminutes = 0;
            $primary_active_job_text = '';

            if($getdetails_active_jobs_num > 0)
            {
              $primary_active_job_text = 'Not Available as you have '.$getdetails_active_jobs_num.' active job(s)';
            }
            else{
              if(count($getdetails_active_jobs))
              {
                foreach($getdetails_active_jobs as $activejobs)
                {
                  $todaystarttime = strtotime($currentdate.' '.$activejobs->start_time);
                  $currenttime = strtotime($currentdate.' '.$activejobs->stop_time);
                  $diff = $currenttime - $todaystarttime;
                  if($spendminutes == 0) {
                    $spendminutes = round(abs($diff) / 60);
                  }
                  else {
                    $spendminutes = $spendminutes + round(abs($diff) / 60);
                  }
                }
              }
            }

            if(count($getdetails_quick_jobs))
            {
              foreach($getdetails_quick_jobs as $quickjobs)
              {
                if($quickjobs->stop_time == "00:00:00")
                {
                  $todaystarttime = strtotime($currentdate.' '.$quickjobs->start_time);
                  $currenttime = strtotime($currentdatetime);

                  if($currenttime < $todaystarttime)
                  {
                    $diff = 0;
                  }
                  else{
                    $diff = $currenttime - $todaystarttime;
                  }

                  if($spendquickminutes == 0) {
                    $spendquickminutes = round(abs($diff) / 60);
                  }
                  else {
                    $spendquickminutes = $spendquickminutes + round(abs($diff) / 60);
                  }
                }
                else{
                  $todaystarttime = strtotime($currentdate.' '.$quickjobs->start_time);
                  $currenttime = strtotime($currentdate.' '.$quickjobs->stop_time);
                  $diff = $currenttime - $todaystarttime;

                  if($spendquickminutes == 0) {
                    $spendquickminutes = round(abs($diff) / 60);
                  }
                  else {
                    $spendquickminutes = $spendquickminutes + round(abs($diff) / 60);
                  }
                }
              }
            }

            $actual_primary_job_time = $spendminutes - $spendquickminutes;

            if(floor($actual_primary_job_time / 60) <= 9)
            {
              $h = '0'.floor($actual_primary_job_time / 60);
            }
            else{
              $h = floor($actual_primary_job_time / 60);
            }
            if(($actual_primary_job_time -   floor($actual_primary_job_time / 60) * 60) <= 9)
            {
              $m = '0'.($actual_primary_job_time -   floor($actual_primary_job_time / 60) * 60);
            }
            else{
              $m = ($actual_primary_job_time -   floor($actual_primary_job_time / 60) * 60);
            }

            if($primary_active_job_text == "")
            {
              if($actual_primary_job_time < 60)
              {
                $summary_total_time = $m.' Minutes';
              }
              else{
                $summary_total_time = $h.':'.$m.' Hours';
              }
            }
            else{
              $summary_total_time = $primary_active_job_text;
            }

            if(floor($spendquickminutes / 60) <= 9)
            {
              $h = '0'.floor($spendquickminutes / 60);
            }
            else{
              $h = floor($spendquickminutes / 60);
            }
            if(($spendquickminutes -   floor($spendquickminutes / 60) * 60) <= 9)
            {
              $m = '0'.($spendquickminutes -   floor($spendquickminutes / 60) * 60);
            }
            else{
              $m = ($spendquickminutes -   floor($spendquickminutes / 60) * 60);
            }

            if($spendquickminutes < 60)
            {
              $summary_quick_jobs_time = $m.' Minutes';
            }
            else{
              $summary_quick_jobs_time = $h.':'.$m.' Hours';
            }

            echo '<div class="col-lg-12 user_details_div" style="margin-top: 30px;">        
              <div class="col-lg-5 col-md-5 col-sm-5" style="border:1px solid #000;">
                <h5 style="padding: 0px; font-weight: 600">Total Time on the Primary Active Job : </h5>
              </div>
              <div class="col-lg-3 col-md-3 col-sm-3" style="border:1px solid #000;">
                  <h5 style="padding: 0px; font-weight: 600"><span id="active_job_hours">'.$summary_total_time.'</span></h5>
              </div>
            </div>

            <div class="col-lg-12 user_details_div">        
              <div class="col-lg-5 col-md-5 col-sm-5" style="border:1px solid #000;">
                <h5 style="padding: 0px; font-weight: 600">Total Quick Jobs : </h5>
              </div>
              <div class="col-lg-3 col-md-3 col-sm-3" style="border:1px solid #000;">
                  <h5 style="padding: 0px; font-weight: 600"><span id="total_quick_jobs_html">'.$quick_jobs_count.'</span></h5>
              </div>
            </div>

            <div class="col-lg-12 user_details_div">        
              <div class="col-lg-5 col-md-5 col-sm-5" style="border:1px solid #000;">
                <h5 style="padding: 0px; font-weight: 600">Total Time on all Quick Jobs : </h5>
              </div>
              <div class="col-lg-3 col-md-3 col-sm-3" style="border:1px solid #000;">
                  <h5 style="padding: 0px; font-weight: 600"><span id="total_time_quick_jobs">'.$summary_quick_jobs_time.'</span></h5>
              </div>
            </div>';

          }

          else{

            echo '<div class="col-lg-12 user_details_div" style="margin-top: 30px;">        
              <div class="col-lg-5 col-md-5 col-sm-5" style="border:1px solid #000;">
                <h5 style="padding: 0px; font-weight: 600">Total Time on the Primary Active Job : </h5>
              </div>
              <div class="col-lg-2 col-md-2 col-sm-2" style="border:1px solid #000;">
                  <h5 style="padding: 0px; font-weight: 600"><span id="active_job_hours">0</span> Hours</h5>
              </div>
            </div>

            <div class="col-lg-12 user_details_div">        
              <div class="col-lg-5 col-md-5 col-sm-5" style="border:1px solid #000;">
                <h5 style="padding: 0px; font-weight: 600">Total Quick Jobs : </h5>
              </div>
              <div class="col-lg-2 col-md-2 col-sm-2" style="border:1px solid #000;">
                  <h5 style="padding: 0px; font-weight: 600"><span id="total_quick_jobs_html">0</span></h5>
              </div>
            </div>

            <div class="col-lg-12 user_details_div">        
              <div class="col-lg-5 col-md-5 col-sm-5" style="border:1px solid #000;">
                <h5 style="padding: 0px; font-weight: 600">Total Time on all Quick Jobs : </h5>
              </div>
              <div class="col-lg-2 col-md-2 col-sm-2" style="border:1px solid #000;">
                  <h5 style="padding: 0px; font-weight: 600"><span id="total_time_quick_jobs">0</span> Hours</h5>
              </div>
            </div>';
          }

          ?>
      </div>

      <!-- <div class="col-lg-12 summary_div" style="margin-top: 50px; background: #fff; padding: 25px 15px;<?php if(Session::has('task_job_user') && Session::get('task_job_user') != "") { echo 'display:block'; } else{ echo 'display:none'; } ?>">

        

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



      </div> -->



      

    </div>

</div>

<script>

$("#user_select").change(function(){

  $('#active_job').DataTable().destroy();

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

        $("#active_job_hours").html(result['summary_total_time']);
        $("#total_quick_jobs_html").html(result['quick_jobs']);
        $("#total_time_quick_jobs").html(result['summary_quick_jobs_time']);

        
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
    $.ajax({
      url:"<?php echo URL::to('user/check_time_me_user_active_job'); ?>",
      type:"post",
      data:{userid:userid},
      success: function(result)
      {
        if(result > 0)
        {
          alert('You can only have 1 active job! You must stop the current active job before you create a New Active Job') ? "" : location.reload();
        }
        else{
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
          $(".taskjob_id").val('');
          $(".add_edit_job").val(0);
        }
      }
    });
  }
  if($(e.target).hasClass('create_new_quick')) {

    $("body").addClass('loading');

    var element = $(e.target).attr("data-element");

    $(".acive_id").val(element);
    $(".taskjob_id").val('');
    $(".add_edit_job").val(0);

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
    $("#edit_idtask").val(taskid);

    $(".task-choose:first-child").text($(e.target).text());

  }

  if($(e.target).hasClass('tasks_li_internal'))

  {

    var taskid = $(e.target).attr('data-element');

    $("#idtask").val(taskid);
    $("#edit_idtask").val(taskid);

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
          $("#total_time_minutes_format").css({"color":"#f00"});
          $("#stop_active_job").prop("disabled",true);
        }
        else{
          $("#total_time_minutes_format").css({"color":"#0f9600"});
          $("#stop_active_job").prop("disabled",false);
        }

          $("#break_time").val(result['break_hours']);
          $("#total_breaks").val(result['break_hours_another']);
          $("#break_time_val").val(result['count']);
          $("#total_time_minutes_format").val(result['total_time_minutes_format']);
      }

    })

  }

  if($(e.target).hasClass('edit_add_minutes'))

  {

    $("body").addClass("loading");

    var element = $(e.target).attr("data-element");

    var break_time = $("#edit_break_time_val").val();

    var jobtime = $(".edit_calculate_job_time").val();

    var total_quick_jobs = $("#edit_total_quick_jobs").val();

    $.ajax({

      url:"<?php echo URL::to('user/calculate_break_time'); ?>",

      type:"get",

      dataType:"json",

      data:{element:element,break_time:break_time,jobtime:jobtime,total_quick_jobs:total_quick_jobs},

      success: function(result)

      {

        if(result['alert'] == 1)
        {
          $("#edit_total_time_minutes_format").css({"color":"#f00"});
          $("#edit_stop_active_job").prop("disabled",true);
        }
        else{
          $("#edit_total_time_minutes_format").css({"color":"#0f9600"});
          $("#edit_stop_active_job").prop("disabled",false);
        }

          $("#edit_break_time").val(result['break_hours']);
          $("#edit_total_breaks").val(result['break_hours_another']);
          $("#edit_break_time_val").val(result['count']);
          $("#edit_total_time_minutes_format").val(result['total_time_minutes_format']);
      }

    })

  }

  if(e.target.id == "reset_breaktime")

  {
    var element = 0;
    var break_time = 0;

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
          $("#total_time_minutes_format").css({"color":"#f00"});
          $("#stop_active_job").prop("disabled",true);
        }
        else{
          $("#total_time_minutes_format").css({"color":"#0f9600"});
          $("#stop_active_job").prop("disabled",false);
        }

          $("#break_time").val('');
          $("#total_breaks").val('');
          $("#break_time_val").val(0);
          $("#total_time_minutes_format").val(result['total_time_minutes_format']);
      }

    })
  }

  if(e.target.id == "edit_reset_breaktime")

  {
    var element = 0;
    var break_time = 0;

    var jobtime = $(".edit_calculate_job_time").val();

    var total_quick_jobs = $("#edit_total_quick_jobs").val();

    $.ajax({

      url:"<?php echo URL::to('user/calculate_break_time'); ?>",

      type:"get",

      dataType:"json",

      data:{element:element,break_time:break_time,jobtime:jobtime,total_quick_jobs:total_quick_jobs},

      success: function(result)

      {

        if(result['alert'] == 1)
        {
          $("#edit_total_time_minutes_format").css({"color":"#f00"});
          $("#edit_stop_active_job").prop("disabled",true);
        }
        else{
          $("#edit_total_time_minutes_format").css({"color":"#0f9600"});
          $("#edit_stop_active_job").prop("disabled",false);
        }

          $("#edit_break_time").val('');
          $("#edit_total_breaks").val('');
          $("#edit_break_time_val").val(0);
          $("#edit_total_time_minutes_format").val(result['total_time_minutes_format']);
      }

    })
  }

  if($(e.target).hasClass('stop_class')) {

    var id = $(e.target).attr("data-element");

    $.ajax({

      url:"<?php echo URL::to('user/stop_job_details')?>",

      dataType: "json",

      data:{jobid:id},

      success:function(result){

        $(".stop_title").html('Stop Active Job');
        $("#stop_job").val('Stop Active Job');

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

        $(".stop_time1").val(result['stop_time']);

        if(result['alert'] == 1)
        {
          $("#total_time_minutes_format").css({"color":"#f00"});
          $("#stop_active_job").prop("disabled",true);
        }
        else{
          $("#total_time_minutes_format").css({"color":"#0f9600"});
          $("#stop_active_job").prop("disabled",false);
        }
        $(".calculate_job_time").val(result['jobtime']);
        $("#total_time_minutes_format").val(result['total_time_minutes_format']);



        $("#break_time").val('');

        $("#total_breaks").val('');

        $("#break_time_val").val(0);



        $(".breaktime_div").hide();

        $("#stop_job").show();

        $("#stop_active_job").hide();

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

        $(".stop_time2").val(result['stop_time']);

        $("#calculate_job_time_quick").val(result['jobtime']);

        

        $("#stop_job").show();

        $("#stop_active_job").hide();

        $(".stop_class").val('Stop Quick Job')
        $(".stop_quick_title").html('Stop Quick Break');
        $("#stop_job_quick").val('Stop Quick Break');

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
        var getTime = new Date().toLocaleString("en-GB", {timeZone: "Europe/Dublin"}).split(" ");
        var time = getTime[1];

        time = time.split(':');

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

        });

        $('#stop_time').datetimepicker({

            defaultDate: fullDate,

            format: 'LT',

            format: 'HH:mm',

        });

        if(time[0] < 9)
        {
          var timee = time[0];
        }
        else{
          var timee = time[0];
        }

        if(time[0] < splittime[0])
        {
          $(".create_startclass").val(splittime[0]+':'+splittime[1]);
        }
        else if(time[0] == splittime[0])
        {
          if(time[1] <= splittime[1])
          {
            $(".create_startclass").val(splittime[0]+':'+splittime[1]);
          }
          else{
            $(".create_startclass").val(timee+':'+time[1]);
          }
        }
        else{
          $(".create_startclass").val(timee+':'+time[1]);
        }

        $(".start_group").show();

        $(".stop_group").hide();    

        $(".start_button_quick").hide();

        $(".job_button_name").show(); 

        $(".date_group").show();

        

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

            var getTime = new Date().toLocaleString("en-GB", {timeZone: "Europe/Dublin"}).split(" ");
            var time = getTime[1];
            time = time.split(':');

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

            if(time[0] < 9)
            {
              var timee = time[0];
            }
            else{
              var timee = time[0];
            }

            $(".create_startclass").val(timee+':'+time[1]);

            $(".start_group").show();

            $(".stop_group").hide();    

            $(".start_button_quick").hide();

            $(".job_button_name").show(); 

            $(".date_group").show();

            

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

if($(e.target).hasClass('edit_quick_job')){  
  $(".stop_group").hide();
  var id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/edit_time_job')?>",      
      data:{jobid:id},
       dataType:"json",
      success:function(result){  
        $(".breaktime_div_edit_close").hide();      
        if($('.create_startclass').data("DateTimePicker"))
        {
          $('.create_startclass').data("DateTimePicker").destroy();  
        }
        if($('#start_time').data("DateTimePicker"))
        {
          $('#start_time').data("DateTimePicker").destroy();
        }
        $("#hidden_job_id").val(result['id']);
        $(".create_new_model").modal("show");        
        $("#hidden_job_id").val(result['id']);

        if(result['job_type'] == 0) { 
          $(".internal_type").val('0');
          $(".mark_internal").prop("checked",true);
          $(".client_search_class").val('');
          $(".client_search_class").prop("required",false);
          $("#client_search").val('');
          $(".client_group").hide();
          $(".internal_tasks_group").show();
          $(".tasks_group").hide();
          $(".task_details").html('');

         var taskid = result['task_id'];
         $("#idtask").val(taskid);
         $(".task-choose_internal:first-child").text(result['task_name']);
        }
        else{
          $(".internal_type").val('1');
          $(".mark_internal").prop("checked",false);
          $(".client_search_class").val(result['client_name']);
          $(".client_search_class").prop("required",true);
          $("#client_search").val(result['client_id']);
          $(".client_group").show();
          $(".internal_tasks_group").hide();
          $(".tasks_group").show();
          $(".task_details").html(result['tasks_group']);

         var taskid = result['task_id'];
         $("#idtask").val(taskid);
         $(".task-choose:first-child").text(result['task_name']);
      }
      $("#idtask").val(result['task_id']);
      if(result['quick_job'] == 1) {
          $(".job_title").html('Edit Quick Break');
          $(".job_button_name").val('Edit Quick Break');   
        }
      else{
        $(".job_title").html('Edit Active Job');
        $(".job_button_name").val('Edit Active Job');
        }
      $(".user_id").val(result['user_id']);
      
      $("#quickjob").val(result['quick_job']);

      $(".create_dateclass").prop("required",true);      
      $(".start_group").show();
      var splittime = result['active_start_time'].split(":");

      if(result['stop_time'] == "00:00")
      {
        $('#start_time').datetimepicker({
              format: 'LT',
              format: 'HH:mm',
              minDate: moment().startOf('day').hour(splittime[0]).minute(splittime[1]),
              maxDate: moment().startOf('day').hour(23).minute(59),
          });
        $('.create_startclass').datetimepicker({
            format: 'LT',
            format: 'HH:mm',
            minDate: moment().startOf('day').hour(splittime[0]).minute(splittime[1]),
            maxDate: moment().startOf('day').hour(23).minute(59),
        });
      }
      else{
        var splitstoptime = result['stop_time'].split(":");
        $('#start_time').datetimepicker({
              format: 'LT',
              format: 'HH:mm',
              minDate: moment().startOf('day').hour(splittime[0]).minute(splittime[1]),
              maxDate: moment().startOf('day').hour(splitstoptime[0]).minute(splitstoptime[1]),
          });
        $('.create_startclass').datetimepicker({
            format: 'LT',
            format: 'HH:mm',
            minDate: moment().startOf('day').hour(splittime[0]).minute(splittime[1]),
            maxDate: moment().startOf('day').hour(splitstoptime[0]).minute(splitstoptime[1]),
        });
      }
      $(".start_time").prop("required",true);
      $(".create_startclass").val(result['start_time']);
      $(".start_button").hide();
      $(".create_dateclass").val(result['job_date']);

      $(".date_group").show();
      $(".start_button_quick").hide();
      
      $(".job_button_name").show();
      

      $(".add_edit_job").val(1);
      $(".acive_id").val(result['active_id']);
      $(".taskjob_id").val(result['id']);
      }


    })
}
if($(e.target).hasClass('edit_active_job')){  
  $(".stop_group").hide();
  var id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/edit_time_job')?>",      
      data:{jobid:id},
       dataType:"json",
      success:function(result){    
        $(".breaktime_div_edit_close").hide();    
        if($('.create_startclass').data("DateTimePicker"))
        {
          $('.create_startclass').data("DateTimePicker").destroy();  
        }
        if($('#start_time').data("DateTimePicker"))
        {
          $('#start_time').data("DateTimePicker").destroy();
        }
        $("#hidden_job_id").val(result['id']);
        $(".create_new_model").modal("show");        
        $("#hidden_job_id").val(result['id']);

        if(result['job_type'] == 0) { 
          $(".internal_type").val('0');
          $(".mark_internal").prop("checked",true);
          $(".client_search_class").val('');
          $(".client_search_class").prop("required",false);
          $("#client_search").val('');
          $(".client_group").hide();
          $(".internal_tasks_group").show();
          $(".tasks_group").hide();
          $(".task_details").html('');

         var taskid = result['task_id'];
         $("#idtask").val(taskid);
         $(".task-choose_internal:first-child").text(result['task_name']);
        }
        else{
          $(".internal_type").val('1');
          $(".mark_internal").prop("checked",false);
          $(".client_search_class").val(result['client_name']);
          $(".client_search_class").prop("required",true);
          $("#client_search").val(result['client_id']);
          $(".client_group").show();
          $(".internal_tasks_group").hide();
          $(".tasks_group").show();
          $(".task_details").html(result['tasks_group']);

         var taskid = result['task_id'];
         $("#idtask").val(taskid);
         $(".task-choose:first-child").text(result['task_name']);
      }
      $("#idtask").val(result['task_id']);
      if(result['quick_job'] == 1) {
          $(".job_title").html('Edit Quick Break');
          $(".job_button_name").val('Edit Quick Break');   
        }
      else{
        $(".job_title").html('Edit Active Job');
        $(".job_button_name").val('Edit Active Job');
        }
      $(".user_id").val(result['user_id']);
      
      $("#quickjob").val(result['quick_job']);

      $(".create_dateclass").prop("required",true);      
      $(".start_group").show();
      $(".start_time").prop("required",true);

      var splitquicktime = result['quick_start_time'].split(":");
      if(splitquicktime == "")
      {
        $('#start_time').datetimepicker({
              format: 'LT',
              format: 'HH:mm',
              maxDate: moment().startOf('day').hour(23).minute(59),
          });
        $('.create_startclass').datetimepicker({
            format: 'LT',
            format: 'HH:mm',
            maxDate: moment().startOf('day').hour(23).minute(59),
        });
      }
      else{
        $('#start_time').datetimepicker({
              format: 'LT',
              format: 'HH:mm',
              maxDate: moment().startOf('day').hour(splitquicktime[0]).minute(splitquicktime[1]),
          });
        $('.create_startclass').datetimepicker({
            format: 'LT',
            format: 'HH:mm',
            maxDate: moment().startOf('day').hour(splitquicktime[0]).minute(splitquicktime[1]),
        });
      }



      $(".create_startclass").val(result['start_time']);
      $(".start_button").hide();
      $(".create_dateclass").val(result['job_date']);

      $(".date_group").show();
      $(".start_button_quick").hide();
      
      $(".job_button_name").show();

      $('#start_time').datetimepicker({
          format: 'LT',
          format: 'HH:mm',
      });
      $('.create_startclass').datetimepicker({
          format: 'LT',
          format: 'HH:mm',
      });

      $(".add_edit_job").val(1);
      $(".acive_id").val(result['active_id']);
      $(".taskjob_id").val(result['id']);
      }


    })
}


if($(e.target).hasClass('edit_mark_internal'))
  {
    if($(e.target).is(":checked"))
    {    
      $(".internal_type").val('0');
      $(".edit_client_group").hide();
      $(".edit_internal_task_group").show();
      $(".edit_task_group").hide();
      $(".edit_client_name").val('');
      $(".edit_client_name").prop("required",false);
      $(".edit_client_class").val('');
      $(".task_details").html('');
      $("#edit_idtask").val('');
      var child_value = $(".tasks_li_internal:first").text();
      $(".task-choose_internal:first-child").text(child_value);
    }
    else{
      $(".internal_type").val('1');
      $(".edit_client_group").show();
      $(".edit_internal_task_group").hide();
      $(".edit_task_group").hide();
      $(".edit_client_name").prop("required",true);
      $("#edit_idtask").val('');
    }

  }


if($(e.target).hasClass('edit_close_job')){  
  var id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/edit_time_job')?>",      
      data:{jobid:id},
      dataType:"json",
      success:function(result){        
      $(".edit_stop_model").modal("show");
      if($('.edit_stop_time1').data("DateTimePicker"))
      {
        $('.edit_stop_time1').data("DateTimePicker").destroy();  
      }
      if($('#edit_stop_time1').data("DateTimePicker"))
      {
        $('#edit_stop_time1').data("DateTimePicker").destroy();
      }
      if(result['alert'] == 1)
      {
        $("#edit_total_time_minutes_format").css({"color":"#f00"});
        $("#edit_stop_active_job").prop("disabled",true);
      }
      else{
        $("#edit_total_time_minutes_format").css({"color":"#0f9600"});
        $("#edit_stop_active_job").prop("disabled",false);
      }

      if(result['quick_job'] == 0){
        $(".breaktime_div_edit_close").hide();
        $(".breaktime_div_edit").show();
        $("#edit_total_quick_jobs").val(result['quick_job_times']);
        $("#edit_break_time_val").val(result['total_breaks_minutes']);
        $("#edit_total_breaks").val(result['breaktime']);

        var splitstarttime = result['start_time'].split(":");  
        $('#edit_stop_time1').datetimepicker({
              format: 'LT',
              format: 'HH:mm',
              minDate: moment().startOf('day').hour(splitstarttime[0]).minute(splitstarttime[1]),
              maxDate: moment().startOf('day').hour(23).minute(59),
          });
        $('.edit_stop_time1').datetimepicker({
            format: 'LT',
            format: 'HH:mm',
            minDate: moment().startOf('day').hour(splitstarttime[0]).minute(splitstarttime[1]),
            maxDate: moment().startOf('day').hour(23).minute(59),
        });
      }
      else{
        $(".breaktime_div_edit_close").show();
        $("#primary_job_time").val(result['total_time_minutes_format']);
        $(".breaktime_div_edit").hide();

        var splitstarttime = result['start_time'].split(":");  
        var splitstoptime = result['stoptime_till_val'].split(":");  
            
        $('#edit_stop_time1').datetimepicker({
              format: 'LT',
              format: 'HH:mm',
              minDate: moment().startOf('day').hour(splitstarttime[0]).minute(splitstarttime[1]),
              maxDate: moment().startOf('day').hour(splitstoptime[0]).minute(splitstoptime[1]),
          });
        $('.edit_stop_time1').datetimepicker({
            format: 'LT',
            format: 'HH:mm',
            minDate: moment().startOf('day').hour(splitstarttime[0]).minute(splitstarttime[1]),
            maxDate: moment().startOf('day').hour(splitstoptime[0]).minute(splitstoptime[1]),
        });
      }
      if(result['job_type'] == 0){
        $("#edit_mark_internal").prop("checked",true);
        $(".edit_client_group").hide();
        $(".edit_client_name").prop("required",false);

        var taskid = result['task_id'];
        $("#edit_idtask").val(taskid);
        $(".task-choose_internal:first-child").text(result['task_name']);
        $(".edit_internal_task_group").show();
        $(".edit_task_group").hide();

      }
      else{
        $("#edit_mark_internal").prop("checked",false);
        $(".edit_client_group").show();
        $(".edit_client_name").val(result['client_name']);
        $(".edit_client_class").val(result['client_id']);
        $(".edit_client_name").prop("required",true);

        var taskid = result['task_id'];
        $("#edit_idtask").val(taskid);
        $(".task-choose:first-child").text(result['task_name']);
        $(".edit_internal_task_group").hide();
        $(".edit_task_group").show();
        $(".task_details").html(result['tasks_group']);
      }

      $(".edit_dateclass").val(result['job_date']);
      $(".edit_start_time").val(result['start_time']);
      $(".edit_stop_time1").val(result['stop_time']);
      $(".edit_calculate_job_time").val(result['job_time']);
      $(".edit_comments").val(result['comments']);
      
      $("#edit_break_time").val(result['breaktime']);
      
      $("#edit_quickjob").val(result['quick_job']);
      
      $("#edit_total_time_minutes_format").val(result['total_time_minutes_format']);

      $("#hidden_edit_job_id").val(result['id']);
    }
  })
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

        $('#edit_stop_time1').datetimepicker({

            format: 'LT',

            format: 'HH:mm',

        });

        $('.edit_stop_time1').datetimepicker({

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
              var total_quick_jobs = $("#total_quick_jobs").val();
              var total_breaks = $("#total_breaks").val();
                $.ajax({
                  url:"<?php echo URL::to('user/calculate_job_time'); ?>",
                  type:"get",
                  dataType:"json",
                  data:{start_time:start_time,stop_time:stop_time,total_quick_jobs:total_quick_jobs,total_breaks:total_breaks},
                  success: function(result)
                  {
                    if(result['alert'] == 1)
                    {
                      $("#total_time_minutes_format").css({"color":"#f00"});
                      $("#stop_active_job").prop("disabled",true);
                    }
                    else{
                      $("#total_time_minutes_format").css({"color":"#0f9600"});
                      $("#stop_active_job").prop("disabled",false);
                    }
                    $(".calculate_job_time").val(result['jobtime']);
                    $("#total_time_minutes_format").val(result['total_time_minutes_format']);

                  }
                });
            }
        });

        $("#edit_stop_time1").on("dp.hide", function (e) {

            var start_time = $(".edit_start_time").val();

            var stop_time = $(".edit_stop_time1").val();
            if(stop_time == "")
            {

            }
            else{
              var total_quick_jobs = $("#edit_total_quick_jobs").val();
              var total_breaks = $("#edit_total_breaks").val();
                $.ajax({
                  url:"<?php echo URL::to('user/calculate_job_time'); ?>",
                  type:"get",
                  dataType:"json",
                  data:{start_time:start_time,stop_time:stop_time,total_quick_jobs:total_quick_jobs,total_breaks:total_breaks},
                  success: function(result)
                  {
                    if(result['alert'] == 1)
                    {
                      $("#edit_total_time_minutes_format").css({"color":"#f00"});
                      $("#edit_stop_active_job").prop("disabled",true);
                    }
                    else{
                      $("#edit_total_time_minutes_format").css({"color":"#0f9600"});
                      $("#edit_stop_active_job").prop("disabled",false);
                    }
                    $(".edit_calculate_job_time").val(result['jobtime']);
                    $("#edit_total_time_minutes_format").val(result['total_time_minutes_format']);

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
                var total_quick_jobs = $("#total_quick_jobs").val();
              var total_breaks = $("#total_breaks").val();
                $.ajax({
                  url:"<?php echo URL::to('user/calculate_job_time'); ?>",
                  type:"get",
                  dataType:"json",
                  data:{start_time:start_time,stop_time:stop_time,total_quick_jobs:total_quick_jobs,total_breaks:total_breaks},
                  success: function(result)
                  {
                    $("#calculate_job_time_quick").val(result['jobtime'])
                  }
                });
            }
        });



        

        // $('.datepicker-only-init').datetimepicker({

        //     widgetPositioning: {

        //         horizontal: 'left'

        //     },

        //     icons: {

        //         time: "fa fa-clock-o",

        //         date: "fa fa-calendar",

        //         up: "fa fa-arrow-up",

        //         down: "fa fa-arrow-down"

        //     },

        //     format: 'L',

        //     format: 'DD-MMM-YYYY',

        // });

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

  $(".edit_client_name").autocomplete({
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
          $(".edit_client_class").val(ui.item.id);          
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
                  $("#edit_idtask").val('');
                  $(".edit_task_group").show();
                }
                else{
                  $(".edit_client_name").val('');
                  $(".task_details").html('');
                  $(".task-choose:first-child").text("Select Tasks");
                  $("#edit_idtask").val('');
                  $(".edit_task_group").hide();
                }
              }
              else{
                $(".task_details").html(result);
                $(".task-choose:first-child").text("Select Tasks")
                $("#edit_idtask").val('');
                $(".edit_task_group").show();
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
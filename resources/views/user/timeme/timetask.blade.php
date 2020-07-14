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

.label_class{
  float:left ;
  margin-top:15px;
  font-weight:700;
}
.upload_img{
  position: absolute;
    top: 0px;
    z-index: 1;
    background: rgb(226, 226, 226);
    padding: 19% 0%;
    text-align: center;
    overflow: hidden;
}
.upload_text{
  font-size: 15px;
    font-weight: 800;
    color: #631500;
}


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

.label_class{
  float:left ;
  margin-top:15px;
  font-weight:700;
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
                url(<?php echo URL::to('assets/images/loading.gif'); ?>) 
                50% 50% 
                no-repeat;
}

.report_div{
    position: absolute;
    top: 55%;
    left:20%;
    padding: 15px;
    background: #ff0;
    z-index: 999999;
    text-align: left;
}
.selectall{padding:10px 15px; background-image:linear-gradient(to bottom,#f5f5f5 0,#e8e8e8 100%); background: #f5f5f5; border:1px solid #ddd; border-radius: 3px;  }

.report_ok_button{background: #000; text-align: center; padding: 6px 12px; color: #fff; float: left; border: 0px; font-size: 13px; }
.report_ok_button:hover{background: #5f5f5f; text-decoration: none; color: #fff}
.report_ok_button:focus{background: #000; text-decoration: none; color: #fff}

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
</style>
<script>
function popitup(url) {
    newwindow=window.open(url,'name','height=600,width=1500');
    if (window.focus) {newwindow.focus()}
    return false;
}

</script>

<style>
.error{color: #f00; font-size: 12px;}
a:hover{text-decoration: underline;}
</style>

<div class="modal fade add_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <form action="<?php echo URL::to('user/time_task_add')?>" method="post" class="add_new_form"> 
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Add Task</h4>
          </div>
          <div class="modal-body" style="height: 400px; overflow-y: scroll;">

            <input type="hidden" class="task_type" value="1" name="task_type">

              <div class="col-lg-12 selectall">
                <div class="col-lg-4" style="padding: 0px;"><input type="text" class="form-control" name="task_name" placeholder="Enter Task Name" required /></div>
                <div class="col-lg-6" style="padding-top: 5px;"><input type="checkbox" class="mark_internal" id="mark_internal" value="1"><label for="mark_internal" style="font-size: 14px; font-weight: normal;">Make The Task Internal </label></div>
              </div>

              <div class="check_mark" style="width: 100%; height: auto; text-align: left; padding: 15px; margin-top: 20px; float: left; font-size: 15px; font-weight: bold; display: none;"><i class="fa fa-exclamation-triangle" style="margin-right: 15px;"></i>Internal Tasks Can Not Be Assigned to Clients</div>

              <div class="col-lg-12 selectall class_selectall" style="margin:15px 0px;">
                <div class="col-lg-2" style="padding: 5px 0px 0px 0px;">
                  <input type="checkbox" class="select_all_class" id="select_all_class" style="padding-top: 20px;" name="">
                  <label for="select_all_class" style="font-size: 14px; font-weight: normal;">Select all</label>
                </div>
              </div>


              <table class="table client_list">
                <thead>
                <tr style="background: #fff;">
                    <th width="5%" style="text-align: left;">S.No</th>
                    <th width="5%" style="text-align: left;"></th>
                    <th style="text-align: left;">Client ID</th>
                    <th style="text-align: left;">First Name</th>    
                    <th style="text-align: left;">Surname</th>
                    <th width="15%" style="text-align: left;">Company Name</th>
                </tr>
                </thead>                            
                <tbody id="timetask_tbody">
                <?php
                  

                  
                  $i=1;
                  if(count($clientlist)){                     
                    foreach ($clientlist as $key => $client) {
                      

                  ?>
                    <tr>
                        <td><?php echo $i ?></td>
                        <td><input type="checkbox" name="select_client[]" class="select_client add_select_client" data-element="<?php echo $client->client_id?>" value="<?php echo $client->client_id?>"><label>&nbsp</label></td>
                        <td><?php echo $client->client_id?></td>
                        <td><?php echo $client->firstname?></td>
                        <td><?php echo $client->surname?></td>
                        <td><?php echo $client->company?></td>
                    </tr>
                  <?php
                    $i++;                    
                    }
                  }
                  ?>
                </tbody>
            </table>
          </div>
          <div class="modal-footer">
              <input type="submit" class="common_black_button" id="add_task" value="Add New Task">            
          </div>
        </form>
        <form action="<?php echo URL::to('user/time_task_update')?>" method="post" class="edit_task"> 
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Edit Time Task</h4>
          </div>
          <div class="modal-body" style="height: 400px; overflow-y: scroll;">

            <input type="hidden" class="task_type edit_type" value="1" name="task_type">

              <div class="col-lg-12 selectall">
                <div class="col-lg-4" style="padding: 0px;"><input type="text" class="form-control task_class" name="task_name" placeholder="Enter Task Name" required /></div>
                <div class="col-lg-6" style="padding-top: 5px;"><input type="checkbox" class="mark_internal" id="mark_internal_edit" value="1"><label for="mark_internal_edit" style="font-size: 14px; font-weight: normal;">Make The Task Internal </label></div>
              </div>

              <div class="check_mark" style="width: 100%; height: auto; text-align: left; padding: 15px; margin-top: 20px; float: left; font-size: 15px; font-weight: bold; display: none;"><i class="fa fa-exclamation-triangle" style="margin-right: 15px;"></i>Internal Tasks Can Not Be Assigned to Clients</div>

              <div class="col-lg-12 selectall class_selectall" style="margin:15px 0px;">
                <div class="col-lg-2" style="padding: 5px 0px 0px 0px;">
                  <input type="checkbox" class="select_all_class" id="select_all_class_edit" style="padding-top: 20px;">
                  <label for="select_all_class_edit" style="font-size: 14px; font-weight: normal;">Select all</label>
                </div>
              </div>


              <table class="table client_list">
                <thead>
                <tr style="background: #fff;">
                    <th width="5%" style="text-align: left;">S.No</th>
                    <th width="5%" style="text-align: left;"></th>
                    <th style="text-align: left;">Client ID</th>
                    <th style="text-align: left;">First Name</th>    
                    <th style="text-align: left;">Surname</th>
                    <th width="15%" style="text-align: left;">Company Name</th>
                </tr>
                </thead>                            
                <tbody id="timetask_tbody">
                <?php
                  

                  
                  $i=1;
                  if(count($clientlist)){                     
                    foreach ($clientlist as $key => $client) {
                      

                  ?>
                    <tr>
                        <td><?php echo $i ?></td>
                        <td><input type="checkbox" name="select_client[]" class="select_client edit_select_client" id="client_<?php echo $client->client_id; ?>" data-element="<?php echo $client->client_id?>" value="<?php echo $client->client_id?>"><label>&nbsp</label></td>
                        <td><?php echo $client->client_id?></td>
                        <td><?php echo $client->firstname?></td>
                        <td><?php echo $client->surname?></td>
                        <td><?php echo $client->company?></td>

                    </tr>
                  <?php
                    $i++;                    
                    }
                  }
                  ?>
                </tbody>
            </table>
          </div>
          <div class="modal-footer">
              <input type="hidden" class="task_id" value="" name="taskid">
              <input type="submit" class="common_black_button" id="update_task" value="Update Task">
          </div>
        </form>
      </div>
  </div>
</div>

<div class="modal fade count_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">List of Clients this task is assigned to</h4>
          </div>
          <div class="modal-body" style="height: 400px; overflow-y: scroll;">           

              <table class="table">
                <thead>
                <tr style="background: #fff;">
                    <th width="5%" style="text-align: left;">S.No</th>                    
                    <th style="text-align: left;">Client ID</th>
                    <th style="text-align: left;">First Name</th>    
                    <th style="text-align: left;">Surname</th>
                    <th width="15%" style="text-align: left;">Company Name</th>
                </tr>
                </thead>                            
                <tbody id="timetask_count_tbody">
                

                </tbody>
            </table>
          </div>
          <div class="modal-footer">
              
          </div>        
      </div>
  </div>
</div>

<div class="modal fade local_unlocal_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <form action="<?php echo URL::to('user/timetasklock_unlock')?>" method="post"> 
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Confirm to <span class="lock_unlock_text"></span> this Task</h4>
        </div>
        <div class="modal-body">
          <div class="crypt_question_class">
            <div class="form-group">
            Are you sure you want to <span class="lock_unlock_text"></span> this Task?<br/><br/>

              <div class="select_button">
                  <ul>
                  <li><a href="javascript:" style="font-size: 13px; font-weight: 500;" class="crypt_yes">Yes</a></li>               
                  <li><a href="javascript:" style="font-size: 13px; font-weight: 500;" class="crypt_no">No</a></li>               
                </ul>
              </div>
              <div class="modal-footer" style="border-top: 0px;">
              </div>
            </div>
          </div>
          <div class="crypt_class">
            <label>Enter Crypt Pin : </label>
            <div class="form-group">            
                <input class="form-control" name="crypt" id="crypt_pin_input" placeholder="Enter Crypt Pin" type="password" required>
                <input type="hidden" value="" class="lock_status" name="status">
                <input type="hidden" value="" class="lock_id" name="id">
            </div>
            <div class="modal-footer">
              <input type="submit" value="Submit" class="common_black_button">
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>



<div class="content_section" style="margin-bottom:200px">
  <div class="page_title">
        <h4 class="col-lg-3" style="padding: 0px;">
                Time Task                
            </h4>
            <div class="col-lg-6 text-right" style="padding-right: 0px; line-height: 35px;">
                
            </div>
            <div class="col-lg-3 text-right"  style="padding: 0px;" >
              <div class="select_button" style=" margin-left: 10px;">
                <ul>
                <li><a href="javascript:" style="font-size: 13px; font-weight: 500;" class="review_all">Review All Global Tasks</a></li>               
                <li><a href="javascript:" style="font-size: 13px; font-weight: 500;" class="add_new">Add a Task</a></li>               
              </ul>
            </div>                        
  </div>
  <div class="table-responsive" style="max-width: 100%; float: left;margin-bottom:30px; margin-top:55px">
  </div>
  <div style="clear: both;">
   <?php
    if(Session::has('message')) { ?>
        <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>

    
    <?php } ?>
    </div> 

<table class="display nowrap fullviewtablelist" id="time_task_expand" width="100%">
                        <thead>
                        <tr style="background: #fff;">
                            <th width="2%" style="text-align: left;">S.No</th>
                            <th style="text-align: left;">Task Name</th>
                            <th style="text-align: left;">Task Type</th>
                            <th width="10%" style="text-align: left;"># of Clients in <br/>Client Manager</th>
                            <th width="10%" style="text-align: left;"># of Clients this task <br/>is assigned to</th>
                            <th style="text-align: left;"># of Clients this task <br/>is not assigned to</th>
                            <th width="20%" style="text-align: center;">Action</th>                           
                        </tr>
                        </thead>                            
                        <tbody id="invoice_tbody">
                            <?php
                             $i=1;
                              if(count($tasklist)){                                
                                foreach ($tasklist as $key => $task) {
                                  if($task->task_type == 0){
                                    $style = 'color:#2fa500';
                                  }
                                  else{
                                    $style = 'color:#000';
                                  }
                            ?>
                              <tr style="<?php echo $style;?>">
                                <td><?php echo $i;?></td>
                                <td><?php echo $task->task_name?></td>
                                <td>
                                  <?php 
                                  if($task->task_type == 0) {
                                    echo '<i class="fa fa-desktop" style="margin-right:10px;"></i> Internal Task';
                                  }
                                  else if ($task->task_type == 1){
                                    echo '<i class="fa fa-users" style="margin-right:10px;"></i> Client Task';
                                  }
                                  else{
                                    echo '<i class="fa fa-globe" style="margin-right:10px;"></i> Global Task';
                                  }

                                  ?>
                                  
                                </td>
                                <td>
                                    <?php
                                    if($task->task_type == 0){
                                      echo 'N/A';
                                    }
                                    else{
                                      echo $client_id_count;
                                    }
                                    ?>
                                </td>
                                <td>
                                  
                                  <?php
                                  $task_id = $task->id;
                                    if($task->task_type == 1){
                                      $count = count(explode(',',$task->clients));
                                      
                                      echo '<a href="javascript:" class="countclients" id="countclients_'.$task_id.'" data-element="'.base64_encode($task_id).'" >'.$count.'</a>';
                                    }
                                    else if($task->task_type == 2){
                                      $count = count(explode(',',$task->clients));
                                      echo '<a href="javascript:" class="countclients" id="countclients_'.$task_id.'" data-element="'.base64_encode($task_id).'">'.$count.'</a>';                                      
                                    }
                                    else{
                                      $count = 'N/A';
                                      echo $count;

                                    }
                                  ?>
                                    
                                  </td>

                                  <td id="review_<?php echo $task_id?>">
                                  <?php 
                                  //echo $client_id_count

                                  $task_clients = explode(",",$task->clients);
                                  $task_client_count = count($task_clients);                                

                                  if($task->task_type == 2){
                                    if($client_id_count ==$task_client_count){
                                      echo '0';
                                    }
                                    else{
                                      $not_assign = $client_id_count-$task_client_count;

                                      echo '<div style="float:left; line-height:30px; margin-right:15px; min-width:30px; max-width:auto;">'.$not_assign.'</div>';

                                      echo '<a href="javascript:" data-element='.base64_encode($task->id).'  class="review_class report_ok_button">Review</a>';
                                    }
                                  }
                                  else{
                                    echo 'N/A';
                                  }
                                  ?>

                                </td>
                                <td align="center">
                                  <?php
                                  if($task->status == 0){
                                    echo '<a href="javascript:"><i class="fa fa-unlock" data-element='.base64_encode($task->id).' data-toggle="tooltip" data-placement="top" title="Lock this Task"></i></a>'.'&nbsp &nbsp <a href="javascript:"><i class="fa fa-pencil" data-element='.base64_encode($task->id).' data-toggle="tooltip" data-placement="top" title="Edit this Task"></i></a>';
                                  }
                                  else{
                                    echo '<a href="javascript:"><i class="fa fa-lock" data-element='.base64_encode($task->id).' data-toggle="tooltip" data-placement="top" title="Unlock this Task"></i></a>';
                                  }

                                  ?>                                  
                                  
                                </td>
                                
                              </tr>
                              
                            <?php
                               $i++;

                                }                                
                              }
                              if($i == 1)
                                {
                                  echo'<tr><td colspan="6" align="center">Empty</td></tr>';
                                }                             
                            ?>
                        </tbody>
                    </table>
</div>
    <!-- End  -->
<div class="main-backdrop"><!-- --></div>




<div class="modal_load"></div>
<input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
<input type="hidden" name="show_alert" id="show_alert" value="">
<input type="hidden" name="pagination" id="pagination" value="1">

<script>

$( function() {
  $(".datepicker" ).datepicker({ dateFormat: 'mm-dd-yy' });
  $(".datepicker" ).datepicker();
});

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})


$(window).click(function(e) {
  if($(e.target).hasClass("add_select_client"))
  {
    var checked_count = $(".add_select_client:checked").length;
    var total_count = $(".add_select_client").length;
    if(total_count == checked_count)
    {
      $("#select_all_class").prop("checked",true);
      $(".task_type").val("2");
    }
    else{
      $("#select_all_class").prop("checked",false);
      $(".task_type").val("1");
    }
  }
  if($(e.target).hasClass("edit_select_client"))
  {
    var checked_count = $(".edit_select_client:checked").length;
    var total_count = $(".edit_select_client").length;
    if(total_count == checked_count)
    {
      $("#select_all_class_edit").prop("checked",true);
      $(".task_type").val("2");
    }
    else{
      $("#select_all_class_edit").prop("checked",false);
      $(".task_type").val("1");
    }
  }
  if($(e.target).hasClass('add_new')){
    $(".add_modal").modal("show");
    $(".edit_task").hide();
    $(".add_new_form").show();
    $(".add_select_client").prop("checked",false);
    $(".mark_internal").attr("disabled", false);
    $(".mark_internal").attr("checked", false);
    $(".class_selectall").show();
    $(".client_list").show();
    $(".check_mark").hide();
    /*$("body").addClass("loading");
    $.ajax({
      url: "<?php echo URL::to('user/time_task_client_details') ?>",
      type:"post",
      success:function(result){      
         $(".add_modal").modal("show");
         $("#timetask_tbody").html(result);
         $("body").removeClass("loading");
       }

    });*/
  } 

  if($(e.target).hasClass('select_all_class'))
  {
    if($(e.target).is(":checked"))
    {
      $(".select_client").each(function() {
        $(this).prop("checked",true);        
        
        $(".mark_internal").attr("disabled", true);
      });
      $(".task_type").val('2');

    }
    else{
      $(".select_client").each(function() {
        $(this).prop("checked",false);
        
        $(".mark_internal").attr("disabled", false);
      });
      $(".task_type").val('1');
    }
  }

  if($(e.target).hasClass('mark_internal'))
  {
    if($(e.target).is(":checked"))
    {
      $(".class_selectall").hide();
      $(".client_list").hide();
      $(".task_type").val('0');
      $(".check_mark").show();
    }
    else{
      $(".class_selectall").show();
      $(".client_list").show(); 
      $(".task_type").val('1');
      $(".check_mark").hide();
    }
  }

  if($(e.target).hasClass('countclients')){
    $("body").addClass("loading");
    var editid = $(e.target).attr("data-element");
    console.log(editid);
    $.ajax({
      url: "<?php echo URL::to('user/time_task_client_counts') ?>",
      data:{id:editid},
      type:"post",
      success:function(result){      
         $(".count_modal").modal("show");
         $("#timetask_count_tbody").html(result);
         $("body").removeClass("loading");
       }

    });
  }

  if($(e.target).hasClass('fa-unlock')){
    $(".local_unlocal_modal").modal("show");    
    $(".lock_status").val(1);
    var editid = $(e.target).attr("data-element");
    $(".lock_id").val(editid);
    $(".crypt_question_class").show();
    $(".crypt_class").hide();
    $(".lock_unlock_text").text('Lock')
  }
  if($(e.target).hasClass('fa-lock')){
    $(".local_unlocal_modal").modal("show");    
    $(".lock_status").val(0);
    var editid = $(e.target).attr("data-element");
    $(".lock_id").val(editid);
    $(".crypt_question_class").show();
    $(".crypt_class").hide();
    $(".lock_unlock_text").text('Unlock')
  }
  if($(e.target).hasClass('fa-pencil')){ 
    $(".edit_select_client").prop("checked",false);  
    var editid = $(e.target).attr("data-element");
    $(".edit_task").show();
    $(".add_new_form").hide();
    console.log(editid)
    $.ajax({
      url:"<?php echo URL::to('user/timetask_edit')?>",
      type:"post",
      dataType:'json',
      data:{id:editid},
      success:function(result){
        $(".add_modal").modal("show");
        $(".task_class").val(result['taskname']);

        
        var clients = result['clients'].split(",");        

        $.each( clients, function( key, value ) {
          $("#client_"+value).prop("checked",true);
        });

        var checked_count = $(".edit_select_client:checked").length;
        var total_count = $(".edit_select_client").length;
        if(total_count == checked_count)
        {
          $("#select_all_class_edit").prop("checked",true);          
        }
        else{
          $("#select_all_class_edit").prop("checked",false);
        }

        $(".edit_type").val(result['tasktype']);
        $(".task_id").val(result['taskid']);

        var task_type = result['tasktype'];
        if(task_type == 0){
          $("#mark_internal_edit").prop("checked",true);
          $(".class_selectall").hide();
          $(".client_list").hide();
          $(".check_mark").show();
        }
        else{
          $("#mark_internal_edit").prop("checked",false);
          $(".class_selectall").show();
          $(".client_list").show();
          $(".check_mark").hide();
        }
      }
    });
  }

  if($(e.target).hasClass('review_class')){
    $("body").addClass("loading");
    var editid = $(e.target).attr("data-element");
    
    $.ajax({
      url: "<?php echo URL::to('user/time_task_review') ?>",
      data:{id:editid},
      type:"post",
      dataType:"json",
      success:function(result){
         $("body").removeClass("loading");
         $("#countclients_"+result['taskid']).html(result['reviewcount']);
         $("#review_"+result['taskid']).text('0');
       }

    });
  }

  if($(e.target).hasClass('review_all')){
    $("body").addClass("loading");    
    $.ajax({
      url: "<?php echo URL::to('user/time_task_review_all') ?>",
      data:{id:editid},
      type:"post",
      dataType:"json",
      success:function(result){
         $("body").removeClass("loading");
         $.each( result, function( key, value ) {
          $("#countclients_"+value['taskid']).html(value['reviewcount']);
          $("#review_"+value['taskid']).text('0');
        });
         
       }

    });
  }

  if(e.target.id == "add_task")
  {
    if($("#mark_internal").is(":checked"))
    {

    }
    else{
      var checkedcount = $(".add_select_client:checked").length;
      if(checkedcount <= 0)
      {
        
        alert("Please Choose any one client to move on.");
        return false;
      }
    }
  }
  if(e.target.id == "update_task")
  {
    if($("#mark_internal_edit").is(":checked"))
    {

    }
    else{
      var checkedcount = $(".edit_select_client:checked").length;
      if(checkedcount <= 0)
      {
        alert("Please Choose any one client to move on.");
        return false;
      }
    }
  }

  if($(e.target).hasClass('crypt_no')){
    $(".local_unlocal_modal").modal("hide");
  }

  if($(e.target).hasClass('crypt_yes')){
    $(".crypt_question_class").hide();
    $(".crypt_class").show();    
  }
  

  

  


  






});




</script>

<script>
$(function(){
    $('#time_task_expand').DataTable({
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





@stop
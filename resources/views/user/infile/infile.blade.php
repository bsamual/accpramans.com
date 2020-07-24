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
.tasks_drop {text-align: left !important; }
.error_files_notepad_add{color:#f00;}
.download_b_all_image{ cursor : pointer; }
.download_p_all_image{ cursor : pointer; }
.download_s_all_image{ cursor : pointer; }
.download_o_all_image{ cursor : pointer; }
.file_attachment_div{width:100%;}
.add_text{width:95px;}
.user_td_class{
  word-wrap: break-word; white-space:normal; min-width:150px; max-width: 150px;
}
.datepicker-only-init table tr th{border-top: 0px !important;}
.datepicker-only-init table tr td{border-top: 0px !important;}
.auto_save_date table tr th{border-top: 0px !important;}
.auto_save_date table tr td{border-top: 0px !important;}
.form-control[disabled]{background-color: #ccc !important;}
.fa-plus,.fa-plus-task, .fa-pencil-square{cursor: pointer;}
.dropzone .dz-preview.dz-image-preview {
    background: #949400 !important;
}
.remove_dropzone_attach_task{
  color:#f00 !important;
  margin-left:10px;
}
.remove_notepad_attach_task{
  color:#f00 !important;
  margin-left:10px;
}
.dz-message span{text-transform: capitalize !important; font-weight: bold;}

.trash_imageadd{

  cursor:pointer;

}

.dropzone.dz-clickable .dz-message, .dropzone.dz-clickable .dz-message *{

      margin-top: 40px;

}

.dropzone .dz-preview {

  margin:0px !important;

  min-height:0px !important;

  width:100% !important;

  color:#000 !important;

    float: left;

  clear: both;

}

.dropzone .dz-preview p {

  font-size:12px !important;

}

.remove_dropzone_attach{

  color:#f00 !important;

  margin-left:10px;

}

.delete_all_image, .delete_all_notes_only, .delete_all_notes, .download_all_image, .download_rename_all_image, .download_all_notes_only, .download_all_notes{cursor: pointer;}





.notepad_div {

    border: 1px solid #000;

    width: 400px;

    position: absolute;

    height: 250px;

    background: #dfdfdf;

    display: none;

}

.notepad_div textarea{

  height:212px;

}



.notepad_div_notes {

    border: 1px solid #000;

    width: 400px;

    position: absolute;

    height: 250px;

    background: #dfdfdf;

    display: none;

}

.notepad_div_notes textarea{

  height:212px;

}

.img_div_add{

        border: 1px solid #000;

    width: 280px;

    position: inherit !important;

    min-height: 118px;

    background: rgb(255, 255, 0);

    display:none;

}





.notepad_div_notes_add,.notepad_div_notes_task {

    border: 1px solid #000;

    width: 280px;

    position: absolute;

    height: 250px;

    background: #dfdfdf;

    display: none;

}

.notepad_div_notes_add textarea,.notepad_div_notes_task textarea{

  height:212px;

}

body{

  background: #03d4b7 !important;

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

    z-index:    999999999;

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

    top: 91%; left: 25%;

    padding: 15px;

    background: #ff0;

    z-index: 999999;

    text-align: left;

}

.selectall{padding:10px 15px; background-image:linear-gradient(to bottom,#f5f5f5 0,#e8e8e8 100%); background: #f5f5f5; border:1px solid #ddd; border-radius: 3px;  }



.ok_button{background: #000; text-align: center; padding: 6px 12px; color: #fff; float: left; border: 0px; font-size: 13px; line-height: 20px; font-weight: 500 }

.ok_button:hover{background: #5f5f5f; text-decoration: none; color: #fff}

.ok_button:focus{background: #000; text-decoration: none; color: #fff}

.report_csv, .report_pdf{opacity: 1 !important}



.datepicker-only-init_date_received, .auto_save_date{cursor: pointer;}



.ui-widget{z-index: 999999999}

.ui-widget .ui-menu-item-wrapper{font-size: 14px; font-weight: bold;}

.ui-widget .ui-menu-item-wrapper:hover{font-size: 14px; font-weight: bold}



body.loading {

    overflow: hidden;   

}

body.loading .modal_load {

    display: block;

}

    .table thead th:focus{background: #ddd !important;}

    .form-control{border-radius: 0px;}

    .disabled{cursor :auto !important;pointer-events: auto !important}

    .disable_class{cursor :auto !important;pointer-events: none !important}

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

.form-title{width: 100%; height: auto; float: left; margin-bottom: 5px;}



.submit_button{background: #000; text-align: center; padding: 8px 12px; color: #fff; float: left; border: none; font-size: 13px; font-weight: normal;}

.submit_button:hover{background: #5f5f5f; text-decoration: none;}

</style>
<?php 
  $admin_details = Db::table('admin')->first();
  $admin_cc = $admin_details->task_cc_email;
if(Session::has('countupdated')) 
{
  $countupdated = Session::get('countupdated');
  $total_count = Session::get('total_count');
  $message = Session::get('message');

  ?>
  <script>
    $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green"><?php echo $message; ?> Files Uploaded <?php echo $countupdated; ?> of <?php echo $total_count; ?> Files successfully</p>'});
  </script>
  <?php
}
elseif(Session::has('countupdated'))
{
  $message = Session::get('message');
  ?>
  <script>
    $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green"><?php echo $message; ?></p>'});
  </script>
  <?php
}
?>
<!--*************************************************************************-->
<div class="modal fade infiles_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:45%;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title">Link Infiles</h4>
          </div>
          <div class="modal-body" id="infiles_body">  

          </div>
          <div class="modal-footer">  
            <input type="button" class="common_black_button" id="link_infile_button" value="Submit">
          </div>
        </div>
  </div>
</div>
<div class="modal fade create_new_task_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="margin-top: 5%;overflow-y: scroll">
  <div class="modal-dialog modal-sm" role="document" style="width:45%">
    <form action="<?php echo URL::to('user/create_new_taskmanager_task')?>" method="post" class="add_new_form" id="create_task_form">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title">New Task Creator</h4>
          </div>
          <div class="modal-body">            
            <div class="row"> 
                <div class="col-md-3">
                	<label style="margin-top:5px">Author:</label>
                </div>
                <div class="col-md-9">
                	<select name="select_user" class="form-control select_user_author" required>
	                  <option value="">Select User</option>        
	                    <?php
	                    $userlist = DB::table('user')->where('user_status', 0)->where('disabled',0)->orderBy('firstname','asc')->get();
	                    $selected = '';
	                    if(count($userlist)){
	                      foreach ($userlist as $user) {
	                    ?>
	                      <option value="<?php echo $user->user_id ?>"><?php echo $user->lastname.'&nbsp;'.$user->firstname; ?></option>
	                    <?php
	                      }
	                    }
	                    ?>
	                </select>
                </div>
            </div>
            <div class="row" style="margin-top:10px">
            	<div class="col-md-3">
                	<label style="margin-top:5px">Creation Date:</label>
                </div>
                <div class="col-md-9">
                	<label class="input-group datepicker-only-init_date_received">
	                    <input type="text" class="form-control created_date" placeholder="Select Creation Date" name="created_date" style="font-weight: 500;" required />
	                    <span class="input-group-addon">
	                        <i class="glyphicon glyphicon-calendar"></i>
	                    </span>
	                </label>
                </div>
            </div>
            <div class="row" style="margin-top:7px">
            	<div class="col-md-3">
                	<label style="margin-top:5px">Allocate To:</label>
                </div>
                <div class="col-md-7">
                	<select name="allocate_user" class="form-control allocate_user_add">
	                  <option value="">Select User</option>        
	                    <?php
	                    $selected = '';
	                    if(count($userlist)){
	                      foreach ($userlist as $user) {
	                    ?>
	                      <option value="<?php echo $user->user_id ?>"><?php echo $user->lastname.'&nbsp;'.$user->firstname; ?></option>
	                    <?php
	                      }
	                    }
	                    ?>
	                </select>
                </div>
                <div class="col-md-2" style="padding:0px">
                  <div style="margin-top:5px">
                  	<input type='checkbox' name="open_task" id="open_task" value="1"/>
                  	<label for="open_task">OpenTask</label>
                  </div>
                </div>
            </div>
            <div class="row" style="margin-top:14px">
            	<div class="col-md-3 client_group">
                	<label style="margin-top:5px">Client:</label>
                </div>
                <?php
                if(isset($_GET['client_id']))
                {
                	$client_id = $_GET['client_id'];
                	$client_details = DB::table('cm_clients')->where('client_id',$client_id)->first();
                	$company = $client_details->company.'-'.$client_id;
                }
                else{
                	$client_id = '';
                	$company = '';
                }
                ?>
                <div class="col-md-7 client_group">
                	<input  type="text" class="form-control client_search_class_task" name="client_name" placeholder="Enter Client Name / Client ID" value="<?php echo $company; ?>" required disabled>
                	<input type="hidden" id="client_search_task" name="clientid" value="<?php echo $client_id; ?>"/>
                </div>

                <div class="col-md-3 internal_tasks_group" style="display: none;">
                	<label style="margin-top:5px">Select Task:</label>
                </div>
                <div class="col-md-7 internal_tasks_group" style="display: none;">
                	<div class="dropdown" style="width: 100%">
		                <a class="btn btn-default dropdown-toggle tasks_drop" data-target="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="width: 100%">
		                  <span class="task-choose_internal">Select Task</span>  <span class="caret"></span>                          
		                </a>
		                <ul class="dropdown-menu internal_task_details" role="menu"  aria-labelledby="dropdownMenu" style="width: 100%">
		                  <li><a tabindex="-1" href="javascript:" class="tasks_li_internal">Select Task</a></li>
		                    <?php
		                    $taskslist = DB::table('time_task')->where('task_type', 0)->orderBy('task_name', 'asc')->get();
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
		                <input type="hidden" name="idtask" id="idtask" value="">
		              </div>
                </div>
                <div class="col-md-2" style="padding:0px">
                  <div style="margin-top:5px">
                	  <!-- <input type='checkbox' name="internal_checkbox" id="internal_checkbox" value="1" disabled />
                  	<label for="internal_checkbox">Internal</label> -->
                  </div>
                </div>
            </div>
            <div class="form-group start_group" style="margin-top:10px">
                <div class="form-title"><label style="margin-top:5px">Subject:</label></div>
                <input  type="text" class="form-control subject_class" name="subject_class" placeholder="Enter Subject">
            </div>
            <div class="form-group start_group task_specifics_add">
                <div class="form-title" style="float:none"><label style="margin-top:5px">Task Specifics:</label></div>
                <textarea class="form-control task_specifics" id="editor_2" name="task_specifics" placeholder="Enter Task Specifics" style="height:400px"></textarea>
            </div>
            <div class="form-group date_group">
                <div class="col-md-2" style="padding:0px">
                	<label style="margin-top:5px">DueDate:</label>
                </div>
                <div class="col-md-10">
                	<label class="input-group datepicker-only-init_date_received">
	                    <input type="text" class="form-control due_date" placeholder="Select Due Date" name="due_date" style="font-weight: 500;" required />
	                    <span class="input-group-addon">
	                        <i class="glyphicon glyphicon-calendar"></i>
	                    </span>
	                </label>
                </div>
            </div>
            <div class="form-group start_group retreived_files_div">

            </div>
            <div class="form-group start_group">
              <label>Task Files: </label>
              <a href="javascript:" class="fa fa-plus fa-plus-task" style="margin-top:10px; margin-left: 10px;" aria-hidden="true" title="Add Attachment"></a> 
              <a href="javascript:" class="fa fa-pencil-square fanotepadtask" style="margin-top:10px; margin-left: 10px;" aria-hidden="true" title="Add Completion Notes"></a>
              <a href="javascript:" class="infiles_link" style="margin-top:10px; margin-left: 10px;">Infiles</a>
              <input type="hidden" name="hidden_infiles_id" id="hidden_infiles_id" value="">
              <div class="img_div img_div_task" style="z-index:9999999; min-height: 275px">
                <form name="image_form" id="image_form" action="" method="post" enctype="multipart/form-data" style="text-align: left;">
                </form>
                <div class="image_div_attachments">
                  <p>You can only upload maximum 300 files at a time. If you drop more than 300 files then the files uploading process will be crashed. </p>
                  <form action="<?php echo URL::to('user/infile_upload_images_taskmanager_add'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload5" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                      <input name="_token" type="hidden" value="">
                  </form>              
                </div>
               </div>
               <div class="notepad_div_notes_task" style="z-index:9999; position:absolute;display:none">
                  <textarea name="notepad_contents_task" class="form-control notepad_contents_task" placeholder="Enter Contents"></textarea>
                  <input type="button" name="notepad_submit_task" class="btn btn-sm btn-primary notepad_submit_task" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
                  <spam class="error_files_notepad_add"></spam>
              </div>
            </div>
            
            <p id="attachments_text_task" style="display:none; font-weight: bold;">Files Attached:</p>
            <div id="add_attachments_div_task">
            </div>
            <div id="add_notepad_attachments_div_task">
            </div>
            <p id="attachments_infiles" style="display:none; font-weight: bold;">Linked Infiles:</p>
            <div id="add_infiles_attachments_div">
            </div>
            <div class="form-group date_group">
                <div class="form-title" style="font-weight:600;margin-left:-10px;float:none"><input type='checkbox' name="accept_recurring" class="accept_recurring" id="recurring_checkbox0" value="1" checked/> <label for="recurring_checkbox0">Recurring Task</label></div>
                <div class="accept_recurring_div">
                  <p>This Task is repeated:</p>
                  <div class="form-title" style="float:none">
                    <input type='radio' name="recurring_checkbox" class="recurring_checkbox" id="recurring_checkbox1" value="1" checked/>
                    <label for="recurring_checkbox1">Monthly</label>
                  </div>
                  <div class="form-title" style="float:none">
                    <input type='radio' name="recurring_checkbox" class="recurring_checkbox" id="recurring_checkbox2" value="2"/>
                    <label for="recurring_checkbox2">Weekly</label>
                  </div>
                  <div class="form-title" style="float:none">
                    <input type='radio' name="recurring_checkbox" class="recurring_checkbox" id="recurring_checkbox3" value="3"/>
                    <label for="recurring_checkbox3">Daily</label>
                  </div>
                  <div class="form-title" style="float:none">
                    <input type='radio' name="recurring_checkbox" class="recurring_checkbox" id="recurring_checkbox4" value="4"/>
                    <label for="recurring_checkbox4">Specific Number of Days</label>
                    <input type="number" name="specific_recurring" class="specific_recurring" value="" style="width: 29%;height: 25px;">
                  </div>
                </div>
            </div>
          </div>
          <div class="modal-footer">     
            <input type="hidden" name="action_type" id="action_type" value="">
            <input type="hidden" name="hidden_specific_type" id="hidden_specific_type" value="">
            <input type="hidden" name="hidden_attachment_type" id="hidden_attachment_type" value="">
            <input type="hidden" name="hidden_task_id_copy_task" class="hidden_task_id_copy_task" value="">
            <input type="hidden" name="total_count_files" id="total_count_files" value="">
            <input type="submit" class="common_black_button make_task_live" value="Make Task Live" style="width: 100%;">
          </div>
        </div>
    </form>
  </div>
</div>
<div class="modal fade model_notify" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog" role="document" style="width:25%">
    <form action="" method="post" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title notify_title" id="myModalLabel">Notify</h4>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-lg-12">
            </div>
              <input type="checkbox" name="" id="notity_selectall"><label for="notity_selectall">Select All</label>
              <table id="dtBasicExample" class="table">
                <thead>
                  <tr>
                    <th scope="col" style="text-align: left">S.No</th>
                    <th scope="col" style="text-align: left">Name</th>
                  </tr>
                </thead>
                <tbody>
              <?php
              $userlist_notify = DB::table('user')->where('user_status', 0)->where('disabled',0)->orderBy('firstname','asc')->get();
              $i = 1;
              if(count($userlist_notify)){
                foreach ($userlist_notify as $user) {
                ?>
                  <tr>
                    <td scope="row"><?php echo $i; ?> <input type="checkbox" class="notify_id_class" name="username" id="user_<?php echo $user->user_id?>" data-element="<?php echo $user->email; ?>" data-value="<?php echo $user->user_id; ?>"><label>&nbsp;</label></td>
                    <td><label for="user_<?php echo $user->user_id?>"><?php echo $user->lastname.' '.$user->firstname; ?></label></td>
                  </tr>
                <?php
                $i++;
                }
              }
              ?>
              </tbody>
              </table>
          </div>
      </div>
      <div class="modal-footer">
          <input type="hidden" class="notify_file_id" value="" name="">
          <input type="button" class="btn btn-primary common_black_button notify_all_clients_tasks" value="Send Email">
      </div>
    </div>
    </form>
  </div>
</div>


<div class="modal fade report_infile_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Infile Report</h4>
        </div>

        <div class="modal-body" style="height: 400px; overflow-y: scroll;">

          <div class="col-md-2 select_all" style="padding: 0px;"><input type="checkbox" class="select_all_class" id="select_all_class" value="1" style="padding-top: 20px;"><label for="select_all_class" style="font-size: 14px; font-weight: normal;">Select all</label>
          </div>
          <div class="col-md-6">
            <input type="checkbox" id="show_incomplete_report" checked=""><label for="show_incomplete_report" style="font-size: 14px; font-weight: normal;">Show Incomplete Files</label>

            <input type="hidden" class="report_show_incomplete">

          </div>


            <table class="table">
              <thead>
              <tr style="background: #fff;">
                  <th width="5%" style="text-align: left;">S.No</th>
                  <th width="5%" style="text-align: left;"></th>
                  <th style="text-align: left;">Client ID</th>
                  <th style="text-align: left;">First Name</th>    
                  <th style="text-align: left;">Company Name</th>                  
              </tr>
              </thead>
              <tbody id="report_tbody">

              </tbody>
          </table>
        </div>
        <div class="modal-footer">
            <div class="single_client_button" style="display: none">
              <input type="button" class="common_black_button" id="single_save_as_csv" value="Save as CSV">
              <input type="button" class="common_black_button" id="single_save_as_pdf" value="Save as PDF">
            </div>
            <div class="all_client_button" style="display: none">
              <input type="button" class="common_black_button" id="save_as_csv" value="Save as CSV">
              <input type="button" class="common_black_button" id="save_as_pdf" value="Save as PDF">
            </div>
        </div>

      </div>

  </div>

</div>







<div class="modal fade create_new_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="margin-top: 5%;">

  <div class="modal-dialog modal-sm" role="document">

    <form action="<?php echo URL::to('user/create_new_file')?>" method="post" class="add_new_form" id="create_job_form">

        <div class="modal-content">

          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            <h4 class="modal-title job_title">File Received Manager</h4>

          </div>

          <div class="modal-body">            

            <div class="form-group client_group"> 

                <div class="form-title">Select a Client <span><i class="fa fa-info-circle" style="font-size: 13px; cursor: pointer;" data-toggle="tooltip" title="Please make sure that you select a client from the auto-complete result shown below as you type, only then Create New button will be enabled.

"></i></span></div>

                <input  type="text" class="form-control client_search_class" name="client_name" placeholder="Enter Client Name / Client ID" required>

                <input type="hidden" id="client_search" name="clientid" />

            </div>



            <div class="form-group date_group">

                <div class="form-title">Recevied Date</div>

                <label class="input-group datepicker-only-init_date_received">

                    <input type="text" class="form-control date_received" placeholder="Select Received Date" name="received_date" style="font-weight: 500;" required />

                    <span class="input-group-addon">

                        <i class="glyphicon glyphicon-calendar"></i>

                    </span>

                </label>

            </div>



            <div class="form-group start_group">

                <div class="form-title">Added Date</div>

                <div class='input-group datepicker-only-init'>

                    <input type='text' class="form-control date_added" placeholder="Select Added Date" name="added_date" style="font-weight: 500;" required />

                    <span class="input-group-addon">

                        <i class="glyphicon glyphicon-calendar"></i>

                    </span>

                </div>

            </div>



            <div class="form-group start_group">

                <div class="form-title">Description</div>

                <textarea type='text' class="form-control" placeholder="Enter Description" name="description" style="font-weight: 500; height: 100px" required /></textarea>

            </div>



            <div class="form-group start_group">

                <div class='input-group'>

                    <input type='checkbox' name="hard_files_checkbox" id="hard_files_checkbox" value="1"/>

                    <label for="hard_files_checkbox">Hard Files</label>

                </div>

            </div>



            <p id="attachments_text" style="display:none; font-weight: bold;">"Files Attached:</p>

            <div id="add_attachments_div">

            

            </div>

            <div id="add_notepad_attachments_div">



            </div>

            

            <div class="form-group start_group">

              <i class="fa fa-plus fa-plus-add" style="margin-top:10px;" aria-hidden="true" title="Add Attachment"></i> 

              <i class="fa fa-pencil-square fanotepadadd" style="margin-top:10px; margin-left: 10px;" aria-hidden="true" title="Add Completion Notes"></i>

              <i class="fa fa-trash trash_imageadd" data-element="" style="margin-left: 10px;" aria-hidden="true"></i>



              <div class="img_div img_div_add" style="z-index:9999999; min-height: 275px">

                <form name="image_form" id="image_form" action="" method="post" enctype="multipart/form-data" style="text-align: left;">

                  

                </form>

                

                <div class="image_div_attachments">
                  <p>You can only upload maximum 300 files at a time. If you drop more than 300 files then the files uploading process will be crashed. </p>
                  <form action="<?php echo URL::to('user/infile_upload_images_add'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload1" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">

                      <input name="_token" type="hidden" value="'.$file->id.'">

                   

                  </form>                

                </div>

               </div>



               <div class="notepad_div_notes_add" style="z-index:9999; position:absolute">

                  <textarea name="notepad_contents_add" class="form-control notepad_contents_add" placeholder="Enter Contents"></textarea>

                  <input type="button" name="notepad_submit_add" class="btn btn-sm btn-primary notepad_submit_add" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">

                  <spam class="error_files_notepad_add"></spam>

              </div>

            </div>

          </div>

          <div class="modal-footer">           
            <p class="accepted_files_main" style="display:none"><strong> </strong><span class="accepted_files">0</span> Files are Ready to Upload.</p> 
            <input type="hidden" name="total_count_files" id="total_count_files" value="">
            <input type="submit" class="common_black_button job_button_name create_new_class" style="display: none" value="Create New">

          </div>

        </div>

    </form>

  </div>

</div>

<!--*************************************************************************-->

<div class="content_section" style="margin-bottom:200px">

  <div class="page_title">

          <h4 class="col-lg-4" style="padding: 0px;">

                In Files System: Incoming File Management

            </h4>

            <div class="col-lg-1 text-right"></div>

            <div class="col-lg-3 text-right" style="padding-right: 0px;">

                <form action="<?php echo URL::to('user/infile_search')?>" method="get">

                  <div class="col-lg-6" style="padding: 0px;">

                    <div class="form-group">
                        <?php
                        if(isset($_GET['client_id']))
                        {
                          $client_id = $_GET['client_id'];
                          $companydetails_val = DB::table('cm_clients')->where('client_id',$client_id)->first();
                          $companyname_val = $companydetails_val->company.'-'.$client_id;
                          $hiddenval = $_GET['client_id'];
                        }
                        else{
                          $companyname_val = '';
                          $hiddenval = '';
                        }
                        ?>
                        <input type="text" class="form-control client_common_search" placeholder="Enter Client Name" style="font-weight: 500;" value="<?php echo $companyname_val; ?>" required />                      

                      <input type="hidden" class="client_search_common" id="client_search_hidden_infile" value="<?php echo $hiddenval; ?>" name="client_id">                                          

                    </div>                  

                  </div>

                  <div class="col-lg-6" style="padding: 0px;">

                    <div class="select_button" style=" margin-left: 10px; width: 150px;">

                        <ul>

                        <li><input type="submit" value="Submit" class="submit_button" id="client_search_infile"  name=""></li>

                        <li><a href="<?php echo URL::to('user/in_file')?>" style="font-size: 13px; font-weight: 500;">Reset</a></li>

                      </ul>

                    </div>

                  </div>

                </form>

              

            </div>

            <div class="col-lg-4" style="padding:5px 0px 0px 0px; ">

              <div style="float: left;">

                <?php
                  $user_details = DB::table('user_login')->where('id',1)->first();
                  ?>

                <input type="checkbox" id="show_incomplete" <?php if($user_details->infile_incomplete == 1) { echo 'checked'; } else{echo '';}?> ><label for="show_incomplete">Show Incomplete Files</label>

              </div>

              <div class="select_button" style=" margin-left: 10px; width: 400px;">

                <ul>

                <li><a href="javascript:" class="create_new" style="font-size: 13px; font-weight: 500;">Add New File Batch</a></li>
                <li><a href="javascript:" class="reportclassdiv" style="font-size: 13px; font-weight: 500;">Report</a></li>
                <li><a href="<?php echo URL::to('user/in_file_advance'); ?>" style="font-size: 13px; font-weight: 500;">Advance View</a></li>

                <div class="report_div" style="display: none">

                    <label>Please select following report type</label><br>

                    <input type="radio" name="report_infile" id="singleclient" class="class_invoice" value="1"><label for="singleclient">Individual Client</label>
                    <br/>
                    <input type="radio" name="report_infile" id="allclient" class="class_invoice" value="2"><label for="allclient">All Clients</label>
                    <br/>

                    <input type="hidden" class="report_type">
                    <input type="submit" name="invoce_report_but" class="report_ok_button ok_button" value="OK">

                </div>

              </ul>

            </div>

            </div>

              

  <div style="clear: both;">

   <?php

    if(Session::has('message')) { ?>

        <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>



    

    <?php } ?>

    </div> 





</div>

<div class="row">

  <div class="col-lg-12">      

      <table class="display nowrap fullviewtablelist" id="in_file" width="100%">

        <thead>

          <tr style="background: #fff;">

            

            <th width="2%" style="text-align: left;">S.No</th>

            <th style="text-align: left;">Client Name</th>

            <th style="text-align: left;">Date Received / Added</th>

            <th style="text-align: left;" width="20%">Files & Notes </th>

            <th style="text-align: left;width:150px; max-width: 150px;">Complete by</th>

            <th style="text-align: left;">Complete Date</th>

            <th style="text-align: left;">Completion Notes</th>

            <th style="text-align: left;">Hard Files</th>

            <th style="text-align: left;">Action</th>

        </tr>

        </thead>

        <tbody id="in_file_tbody">
          <?php
          $i=1;
          $output='';
          if(count($infiles)){
            foreach ($infiles as $file) {
              if($file->status == 0){
                $staus = 'fa-check edit_status'; 
                $statustooltip = 'Complete Infile';
                $disable = '';
                $disable_class = '';
                $color='';
              }
              else{
                $staus = 'fa-times edit_status incomplete_status';
                $statustooltip = 'InComplete Infile';
                $disable = 'disabled';
                $disable_class = 'disable_class';
                $color = 'style="color:#f00;"';
              }
              $companydetails = DB::table('cm_clients')->where('client_id', $file->client_id)->first();
              if(count($companydetails) != ''){
                $companyname = $companydetails->company;
              }
              else{
                $companyname = 'N/A';
              }
              $attachments = DB::table('in_file_attachment')->where('file_id',$file->id)->where('status',0)->where('notes_type', 0)->get();
              $downloadfile='';
              if(count($attachments)){  
              $downloadfile.='<table id="bspo_id_'.$file->id.'">
              <style>
                .bpso_all_check{font-size:20px; font-weight:700; margin-left:10px;}
                .bpso_all_check:hover{text-decoration:none}
              </style>
                <tr>
                  <td></td>
                  <td>
                    <div style="width:100%; text-align:center">
                      <a href="javascript:" class="bpso_all_check" data-toggle="tooltip" title="Select Missed Items in B Category" id="'.$file->id.'" data-element="1">@</a>
                    </div>
                    <div style="width:100%; text-align:center">
                    <i class="fa fa-cloud-download download_b_all_image" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments in B Category"></i>
                    </div>
                  </td>
                  <td>
                    <div style="width:100%; text-align:center">
                      <a href="javascript:" class="bpso_all_check" data-toggle="tooltip" title="Select Missed Items in P Category" id="'.$file->id.'" data-element="2">@</a>
                    </div>
                    <div style="width:100%; text-align:center">
                    <i class="fa fa-cloud-download download_p_all_image" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments in P Category"></i>
                    </div>
                  </td>
                  <td>
                    <div style="width:100%; text-align:center">
                      <a href="javascript:" class="bpso_all_check" data-toggle="tooltip" title="Select Missed Items in S Category" id="'.$file->id.'" data-element="3">@</a>
                    </div>
                    <div style="width:100%; text-align:center">
                    <i class="fa fa-cloud-download download_s_all_image" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments in S Category"></i>
                    </div>
                  </td>
                  <td>
                    <div style="width:100%; text-align:center">
                      <a href="javascript:" class="bpso_all_check" data-toggle="tooltip" title="Select Missed Items in O Category" id="'.$file->id.'" data-element="4">@</a>
                    </div>
                    <div style="width:100%; text-align:center">
                    <i class="fa fa-cloud-download download_o_all_image" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments in O Category"></i>
                    </div>
                  </td>
                </tr>';                   
                foreach($attachments as $attachment){
                	if($attachment->textstatus == 1) { $texticon="display:none"; $hide = 'display:initial'; } else { $texticon="display:initial"; $hide = 'display:none'; }
                  if($attachment->check_file == 1) { $textdisabled ='disabled'; $checked = 'checked'; } else { $textdisabled =''; $checked = ''; }

                  if($attachment->b == 1) {  $bchecked = 'checked'; } else { $bchecked = ''; }
                  if($attachment->p == 1) {  $pchecked = 'checked'; } else { $pchecked = ''; }
                  if($attachment->s == 1) {  $schecked = 'checked'; } else { $schecked = ''; }
                  if($attachment->o == 1) {  $ochecked = 'checked'; } else { $ochecked = ''; }

                    $downloadfile.= '
                    <tr>
                      <td>
                        <div class="file_attachment_div">
                          <input type="checkbox" name="fileattachment_checkbox" class="fileattachment_checkbox '.$disable_class.'" id="fileattach_'.$attachment->id.'" value="'.$attachment->id.'" '.$checked.' '.$disable.'><label for="fileattach_'.$attachment->id.'">&nbsp;</label> 
                        	<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'" '.$color.'>'.$attachment->attachment.'</a>
                        	<a href="javascript:" class="trash_icon '.$disable_class.'"><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a>
                        	<a href="javascript:" class="fa fa-text-width add_text_image '.$disable_class.'" data-element="'.$attachment->id.'" title="Add Text" style="'.$texticon.'"></a>
                        	<input type="text" name="add_text" class="add_text '.$disable_class.'" data-element="'.$attachment->id.'" value="'.$attachment->textval.'" placeholder="Add Text" '.$textdisabled.' style="'.$hide.'">
                        	<a href="javascript:" class="fa fa-minus-square remove_text_image '.$disable_class.'" data-element="'.$attachment->id.'" title="Remove Text" style="'.$hide.'"></a>
                        	<a href="javascript:" class="fa fa-download download_rename" data-src="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'" data-element="'.$attachment->id.'" title="Download & Rename" style="'.$hide.'"></a>
                        </div>
                      </td>
                      <td>
                        <input type="radio" name="check_'.$attachment->id.'" class="b_check" id="b_check_'.$attachment->id.'" value="'.$attachment->id.'" '.$bchecked.' title="Bank"><label for="b_check_'.$attachment->id.'" title="Bank">B</label> 
                      </td>
                      <td>
                        <input type="radio" name="check_'.$attachment->id.'" class="p_check" id="p_check_'.$attachment->id.'" value="'.$attachment->id.'" '.$pchecked.' title="Purchases"><label for="p_check_'.$attachment->id.'" title="Purchases">P</label> 
                      </td>
                      <td>
                        <input type="radio" name="check_'.$attachment->id.'" class="s_check" id="s_check_'.$attachment->id.'" value="'.$attachment->id.'" '.$schecked.' title="Sales"><label for="s_check_'.$attachment->id.'" title="Sales">S</label> 
                      </td>
                      <td>
                        <input type="radio" name="check_'.$attachment->id.'" class="o_check" id="o_check_'.$attachment->id.'" value="'.$attachment->id.'" '.$ochecked.' title="Other Sundry"><label for="o_check_'.$attachment->id.'" title="Other Sundry">O</label> 
                      </td>
                      </tr>
                      ';
                }
              $downloadfile.='</table>';
              }
              else{
                $downloadfile ='';
              }
              /*<i class="fa fa-download download_all_image" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments"></i>*/
              if(count($attachments)){
                $deleteall = '<i class="fa fa-minus-square delete_all_image '.$disable_class.'" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Delete All Attachments"></i>
                
                <i class="fa fa-cloud-download download_rename_all_image" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download & Rename All Attachments"></i>';
              }

              else{

                $deleteall = '';

              }
              if(count($attachments))
              {
                $span = '<span style="color:#000">There are '.count($attachments).' file(s)</span>';
              }
              else{
                $span = '';
              }







              $notes_attachments = DB::table('in_file_attachment')->where('file_id',$file->id)->where('status',0)->where('notes_type', 1)->get();



              $download_notes='';



              if(count($notes_attachments)){                        

                foreach($notes_attachments as $attachment){
                   if($attachment->check_file == 1) { $checked = 'checked'; } else { $checked = ''; }
                    $download_notes.= '<input type="checkbox" name="fileattachment_checkbox" class="fileattachment_checkbox '.$disable_class.'" id="fileattach_'.$attachment->id.'" value="'.$attachment->id.'" '.$checked.' '.$disable.'><label for="fileattach_'.$attachment->id.'">&nbsp;</label><a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'" '.$color.'>'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disable_class.'"><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';

                }

              }

              else{

                $download_notes ='';

              }



              if(count($notes_attachments)){

                $delete_notes_all = '<i class="fa fa-minus-square delete_all_notes '.$disable_class.'" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Delete All Attachments"></i>

                <i class="fa fa-download download_all_notes" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments"></i>';

              }

              else{

                $delete_notes_all = '';

              }





              $attach_notes_only = DB::table('in_file_attachment')->where('file_id',$file->id)->where('status',1)->get();



              $notes_only='';



              if(count($attach_notes_only)){                        

                foreach($attach_notes_only as $attachment){



                    $notes_only.= '<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disable_class.'" '.$color.'><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';

                }

              }

              else{

                $notes_only ='';

              }



              if(count($attach_notes_only)){

                $delete_notes_only = '<i class="fa fa-minus-square delete_all_notes_only '.$disable_class.'" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Delete All Attachments"></i>

                <i class="fa fa-download download_all_notes_only" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments"></i>';

              }

              else{

                $delete_notes_only = '';

              }









              $userdrop='';



              if(count($userlist)){

                foreach ($userlist as $user) {

                  if($user->user_id == $file->complete_by ){ $selected = 'selected';} else{$selected = '';}

                  $userdrop.='<option value="'.$user->user_id.'" '.$selected.'>'.$user->lastname.' '.$user->firstname.'</option>';

                }

              }



              $complete_date = ($file->complete_date != '0000-00-00')?date('d-M-Y', strtotime($file->complete_date)):'';



              $hard_files = ($file->hard_files != 0)?'YES':'NO';



              if($file->internal == 1){

                $internal_checkbox = 'checked';

              }

              else{

                $internal_checkbox = '';

              }

              if(isset($_GET['client_id']))
              {
                $url_upload = URL::to('user/infile_search?client_id=').$_GET['client_id'];
              }
              else{
                $url_upload = URL::to('user/in_file');
              }

          $output.='

            <tr id="infile_'.$file->id.'">

              <td '.$color.' valign="top" >'.$i.'</td>

              <td '.$color.' valign="top">'.$companyname.'</td>

              <td '.$color.' valign="top">Received:'.date('d-M-Y', strtotime($file->data_received)).'<br/>Added: '.date('d-M-Y', strtotime($file->date_added)).'</td>

              <td '.$color.'>

              <span style="color: #0300c1;">Description:</span>

              <div style="clear:both"></div>              

              <span style="color: #0300c1;">'.$file->description.'</span>

              <div style="clear:both"></div>              

              '.$downloadfile.'

              <i class="fa fa-plus faplus '.$disable_class.'" style="margin-top:10px" aria-hidden="true" title="Add Attachment"></i>              

              '.$deleteall.'<br/><br/>



              <div style="width:100%; height:auto; float:left; padding-bottom:10px; border-bottom:1px solid #000;color: #0300c1;">Notes:
                  <br/> '.$span.'
              </div>



              <div class="clearfix"></div>



              '.$download_notes.'

              <i class="fa fa-pencil-square fanotepad '.$disable_class.'" style="margin-top:10px;" aria-hidden="true" title="Add Completion Notes"></i>

              '.$delete_notes_all.'';

              if($file->task_notify == 1){

                $output.='<br/><a href="javascript:" class="single_notify '.$disable_class.'" data-element="'.$file->id.'" '.$color.'>Notify</a> &nbsp &nbsp <a href="javascript:"  class="all_notify '.$disable_class.'" data-element="'.$file->id.'" '.$color.'>Notify all</a> &nbsp &nbsp <a href="javascript:"  class="create_task_manager" data-element="'.$file->id.'" '.$color.'>Create Task</a>';

              }
               

                 $output.='<div class="img_div" style="z-index:9999999">
                      <form name="image_form" id="image_form" action="'.URL::to('user/infile_image_upload').'" method="post" enctype="multipart/form-data" style="text-align: left;">

                        <input type="file" name="image_file[]" required class="form-control image_file" value="" multiple>

                        <input type="hidden" name="hidden_id" value="'.$file->id.'">

                        <input type="submit" name="image_submit" class="btn btn-sm btn-primary image_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">

                        <spam class="error_files"></spam>
                      </form>
                     <div style="width:100%;text-align:center;margin-top:-10px;margin-bottom:10px;color:#000"><label style="font-weight:800;">OR</label></div>
                     <div class="image_div_attachments">
                       <form action="'.URL::to('user/infile_upload_images?file_id='.$file->id).'" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                           <input name="_token" type="hidden" value="'.$file->id.'">
                       </form>
                       <a href="'.$url_upload.'" class="btn btn-sm btn-primary" align="left" style="margin-left:7px; float:left;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
                     </div>
                  </div>
                  <div class="notepad_div" style="z-index:9999; position:absolute">



                    <form name="notepad_form" id="notepad_form" action="'.URL::to('user/infile_notepad_upload').'" method="post" enctype="multipart/form-data" style="text-align: left;">

                      

                      <textarea name="notepad_contents" class="form-control notepad_contents" placeholder="Enter Contents"></textarea>

                      <input type="hidden" name="hidden_id" value="'.$file->id.'">

                      <input type="submit" name="notepad_submit" class="btn btn-sm btn-primary notepad_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">



                      <spam class="error_files_notepad"></spam>



                    </form>
                  </div>
              </td>

              <td class="user_td_class" '.$color.' valign="top">

              <select class="form-control user_select '.$disable_class.'" data-element="'.$file->id.'" '.$disable.'>

              <option value="">Select User</option>'.$userdrop.'</select>

              </td>

              <td '.$color.' valign="top">

              

                  <label class="input-group auto_save_date">

                      <input type="text" class="form-control complete_date '.$disable_class.'" '.$disable.' value="'.$complete_date.'" data-element="'.$file->id.'" placeholder="Select Date" name="date" style="font-weight: 500; z-index:0" required="" autocomplete="off">

                      <span class="input-group-addon">

                          <i class="glyphicon glyphicon-calendar"></i>

                      </span>

                  </label>

              

              </td>

              <td '.$color.' valign="top">



              '.$notes_only.'

              <i class="fa fa-pencil-square fanotepad_notes '.$disable_class.'" style="margin-top:10px;" aria-hidden="true" title="Add Completion Notes"></i>

              '.$delete_notes_only.'



              <div class="notepad_div_notes" style="z-index:9999; position:absolute">



                <form name="notepad_form_notes" id="notepad_form_notes" action="'.URL::to('user/infile_notepad_upload_notes').'" method="post" enctype="multipart/form-data" style="text-align: left;">

                  

                  <textarea name="notepad_contents" class="form-control notepad_contents" placeholder="Enter Contents"></textarea>

                  <input type="hidden" name="hidden_id" value="'.$file->id.'">

                  <input type="submit" name="notepad_submit" class="btn btn-sm btn-primary notepad_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">



                  <spam class="error_files_notepad"></spam>



                </form>



              </div>



              </td>

              <td '.$color.' align="center" valign="top">'.$hard_files.'</td>

              <td '.$color.' align="center" valign="top"><a href="javascript:"><i class="fa '.$staus.'" data-element="'.$file->id.'" title="'.$statustooltip.'"></i></a></td>

            </tr>';          

            $i++;

            }

          }

          else{

            $output.='<tr>

                    <td>1</td>

                    <td></td>

                    <td></td>

                    <td></td>

                    <td align="center">Empty</td>

                    <td></td>

                    <td></td>

                    <td></td>

                    <td></td>

                    </tr>';

          }

          echo $output;

          

          ?>

          

        </tbody>

      </table>





  </div>

</div>

</div>



    <!-- End  -->

<div class="main-backdrop"><!-- --></div>

<div id="report_pdf_type_two" style="display:none">
  <style>
  .table_style {
      width: 100%;
      border-collapse:collapse;
      border:1px solid #c5c5c5;
  }
  </style>
  <table class="table_style">
    <thead>
      <tr>
      <td style="text-align: left;border:1px solid #000;">S.No</td>
      <td style="text-align: left;border:1px solid #000;">Client Name</td>
      <td style="text-align: left;border:1px solid #000;">Date Received</td>
      <td style="text-align: left;border:1px solid #000;">Date Added</td>
      <td style="text-align: left;border:1px solid #000;">No. of Files</td>
      </tr>
    </thead>
    <tbody id="report_pdf_type_two_tbody">

    </tbody>
  </table>
</div>


<div id="report_pdf_type_two_single" style="display:none">

  <div id="report_pdf_type_two_tbody_single">
  </div>
</div>





<div class="modal_load"></div>

<input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">

<input type="hidden" name="show_alert" id="show_alert" value="">

<input type="hidden" name="pagination" id="pagination" value="1">

<input type="hidden" name="hidden_file_id" id="hidden_file_id" value="">







<script>
// Basic example
$(document).ready(function () {
  $('#dtBasicExample').DataTable({
        fixedHeader: {
          headerOffset: 75
        },
        autoWidth: true,
        scrollX: false,
        fixedColumns: false,
        searching: false,
        paging: false,
        info: false,
        ordering: true
    });

  if($("#show_incomplete").is(':checked'))
  {
    $(".user_select").each(function() {
        if($(this).hasClass('disable_class'))
        {
          $(this).parents('tr').hide();
        }
    });
  }
  else{
    $(".user_select").each(function() {
        if($(this).hasClass('disable_class'))
        {
          $(this).parents('tr').show();
        }
    });
  }
});

$(function(){

    $('#in_file').DataTable({

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



<script type="text/javascript">

  $(function () {        

  	var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});

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

      $('.datepicker-only-init_date_received').datetimepicker({

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

          maxDate: fullDate,

          format: 'DD-MMM-YYYY',

      });

      $('.auto_save_date').datetimepicker({

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

      $('.complete_date').datetimepicker({

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

$(".image_file").change(function(){

  var lengthval = $(this.files).length;

  var htmlcontent = '<label class="attachments_label">Attachments : </label>';

  for(var i=0; i<= lengthval - 1; i++)

  {

    var sno = i + 1;

    if(htmlcontent == "")

    {

      htmlcontent = '<p class="attachment_p">'+sno+'. '+this.files[i].name+'</p>';

    }

    else{

      htmlcontent = htmlcontent+'<p class="attachment_p">'+sno+'. '+this.files[i].name+'</p>';

    }

  }

  $(this).parent().find(".image_div_attachments").html(htmlcontent);

});

$(".image_file_add").change(function(){

  var lengthval = $(this.files).length;

  var htmlcontent = '';

  var attachments = $('#add_attachments_div').html();

  for(var i=0; i<= lengthval - 1; i++)

  {

    var sno = i + 1;

    if(htmlcontent == "")

    {

      htmlcontent = '<p class="attachment_p">'+this.files[i].name+'</p>';

    }

    else{

      htmlcontent = htmlcontent+'<p class="attachment_p">'+this.files[i].name+'</p>';

    }

  }

  $('#add_attachments_div').html(attachments+' '+htmlcontent);

  $("#attachments_text").show();

  $(".img_div").hide();

});

$(window).change(function(e) {



  if($(e.target).hasClass('user_select'))

  {

    var input_val = $(e.target).val();

    var id = $(e.target).attr('data-element');

    $.ajax({

        url:"<?php echo URL::to('user/infile_user_update'); ?>",

        type:"get",

        data:{users:input_val,id:id},

        success: function(result) {

          

        }

      });

  }  

  // if($(e.target).hasClass('complete_date'))

  // {

  //   var input_val = $(e.target).val();

  //   var id = $(e.target).attr('data-element');

  //   $.ajax({

  //       url:"<?php echo URL::to('user/infile_complete_date'); ?>",

  //       type:"get",

  //       data:{date:input_val,id:id},

  //       success: function(result) {

          

  //       }

  //     });

  // }

});


jQuery(document).ready(function($) {
  $('[data-toggle="tooltip"]').tooltip();
    var max = 10;
    $('.add_text').keypress(function(e) {
    	
        if (e.which < 0x20) {
            // e.which < 0x20, then it's not a printable character
            // e.which === 0 - Not a character
            return;     // Do nothing
        }
        if (this.value.length == max) {
        	$(this).val($.trim(this.value));
            e.preventDefault();
        } else if (this.value.length > max) {
        	$(this).val($.trim(this.value));
            // Maximum exceeded
            this.value = this.value.substring(0, max);
        }
    });
}); //end if ready(fn)
$(".client_search_class_task").autocomplete({
      source: function(request, response) {
          $.ajax({
              url:"<?php echo URL::to('user/task_client_search'); ?>",
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
        $("#client_search_task").val(ui.item.id);
        $.ajax({
          dataType: "json",
          url:"<?php echo URL::to('user/task_client_search_select'); ?>",
          data:{value:ui.item.id},
          success: function(result){         
            $("#client_search_task").val(ui.item.id);
          }
        })
      }
  });
$(window).click(function(e) {
  if($(e.target).hasClass('notepad_contents_task'))
  {
    $(e.target).parents('.modal-body').find('.notepad_div_notes_task').show();
  }
  if($(e.target).hasClass('make_task_live'))
  {
    e.preventDefault();
    if($("#internal_checkbox").is(":checked"))
    {
        var taskvalue = $("#idtask").val();
        if(taskvalue == "")
        {
          alert("Please select the Task Name and then make the task as live");
          return false;
        }
    }
    else{
      var clientid = $("#client_search_task").val();
      if(clientid == "")
      {
        alert("Please select the Client and then make the task as live");
        return false;
      }
    }
    if (CKEDITOR.instances.editor_2)
    {
      var comments = CKEDITOR.instances['editor_2'].getData();
      if(comments == "")
      {
        alert("Please Enter Task Specifics and then make the task as Live.");
        return false;
      }
      else{
        $( "#create_task_form" ).valid();
        $("#create_task_form").submit();
      }
    }
    else{
      $("#create_task_form").valid();
      $("#create_task_form").submit();
    }
  }
  if($(e.target).hasClass('accept_recurring'))
  {
    if($(e.target).is(":checked"))
    {
      $(".accept_recurring_div").show();
      $("#recurring_checkbox1").prop("checked",true);
    }
    else{
      $(".accept_recurring_div").hide();
      $(".recurring_checkbox").prop("checked",false);
    }
  }
  if($(e.target).hasClass('remove_infile_link_add'))
  {
    var file_id = $(e.target).attr("data-element");
    var ids = $("#hidden_infiles_id").val();
    var idval = ids.split(",");
    var nextids = '';
    $.each(idval, function( index, value ) {
      if(value != file_id)
      {
        if(nextids == "")
        {
          nextids = value;
        }
        else{
          nextids = nextids+','+value;
        }
      }
    });
    $("#hidden_infiles_id").val(nextids);
    $(e.target).parents("tr").detach();
  }
  if(e.target.id == "link_infile_button")
  {
    var checkcount = $(".infile_check:checked").length;
    if(checkcount > 0)
    {
      var ids = '';
      $(".infile_check:checked").each(function() {
        if(ids == "")
        {
          ids = $(this).val();
        }
        else{
          ids = ids+','+$(this).val();
        }
      });

      $("#hidden_infiles_id").val(ids);
      $(".infiles_modal").modal("hide");
      $.ajax({
        url:"<?php echo URL::to('user/show_linked_infiles'); ?>",
        type:"post",
        data:{ids:ids},
        success:function(result)
        {
          $("#attachments_infiles").show();
          $("#add_infiles_attachments_div").show();
          $("#add_infiles_attachments_div").html(result);
        }
      })
    }
  }
 //  if($(e.target).hasClass('link_infile'))
 //  {
 //  	var href = $(e.target).attr("data-element");
	// var printWin = window.open(href,'_blank','location=no,height=570,width=650,top=80, left=250,leftscrollbars=yes,status=yes');
	// if (printWin == null || typeof(printWin)=='undefined')
	// {
	// 	alert('Please uncheck the option "Block Popup windows" to allow the popup window generated from our website.');
	// }
 //  }
  if($(e.target).hasClass('infiles_link'))
  {
  	var client_id = $("#client_search_task").val();
    var ids = $("#hidden_infiles_id").val();

  	if(client_id == "")
  	{
  		alert("Please select the client and then choose infiles");
  	}
  	else{
  		$.ajax({
  			url:"<?php echo URL::to('user/show_infiles'); ?>",
  			type:"post",
  			data:{client_id:client_id,ids:ids},
  			success: function(result)
  			{
  				$(".infiles_modal").modal("show");
  				$("#infiles_body").html(result);
  			}
  		})
  	}
  }
  if($(e.target).hasClass('notepad_submit_task'))
  { 
    var contents = $(e.target).parent().find('.notepad_contents_task').val();
    if(contents == '' || typeof contents === 'undefined')
    {
      $(e.target).parent().find(".error_files_notepad_add").text("Please Enter the contents for the notepad to save.");
      return false;
    }
    else{
      $(e.target).parents('td').find('.notepad_div_notes_task').toggle();
    }
  }
  else{
    $(".notepad_div_notes_task").each(function() {
      $(this).hide();
    });
  }
  if($(e.target).hasClass('notepad_contents_task'))
  {
    $(e.target).parents('.modal-body').find('.notepad_div_notes_task').show();
  }
  if($(e.target).hasClass('notepad_submit_task'))
  {
    var contents = $(".notepad_contents_task").val();
    $.ajax({
      url:"<?php echo URL::to('user/add_taskmanager_notepad_contents'); ?>",
      type:"post",
      data:{contents:contents},
      dataType:"json",
      success: function(result)
      {
        $("#attachments_text").show();
        $("#add_notepad_attachments_div_task").append("<p>"+result['filename']+" <a href='javascript:' class='remove_notepad_attach_task' data-task='"+result['file_id']+"'>Remove</a></p>");
        $(".notepad_div_notes_task").hide();
      }
    });
  }
  if($(e.target).hasClass("create_task_manager"))
  {
  	var fileid = $(e.target).attr("data-element");
  	$("#hidden_infiles_id").val(fileid);
  	
    $(".create_new_task_model").find(".job_title").html("New Task Creator");
    var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});
    var user_id = $(".select_user_home").val();
    $(".select_user_author").val(user_id);
    $(".create_new_task_model").modal("show");
    if (CKEDITOR.instances.editor_2) CKEDITOR.instances.editor_2.destroy();
    $(".created_date").datetimepicker({
       defaultDate: fullDate,       
       format: 'L',
       format: 'DD-MMM-YYYY',
       maxDate: fullDate,
    });
    $(".due_date").datetimepicker({
       defaultDate: fullDate,
       format: 'L',
       format: 'DD-MMM-YYYY',
       minDate: fullDate,
    });
    CKEDITOR.replace('editor_2',
    {
    	height: '150px',
    	enterMode: CKEDITOR.ENTER_BR,
        shiftEnterMode: CKEDITOR.ENTER_P,
        autoParagraph: false,
        entities: false,
   });

    $("#action_type").val("1");
    $(".allocate_user_add").val("");
    $(".task-choose_internal").html("Select Task");
    $(".subject_class").val("");
    $(".task_specifics_add").show();
    CKEDITOR.instances['editor_2'].setData("");
    
    $(".retreived_files_div").hide();
    $(".retreived_files_div").html("");
    $(".recurring_checkbox").prop("checked", false);
    $(".specific_recurring").val("");
    $(".task_specifics_copy_val").html("");
    $("#hidden_task_specifics").val("");

    $("#hidden_specific_type").val("");
    $("#hidden_attachment_type").val("");

    $(".created_date").prop("readonly", true);
    $(".client_group").show();
    $(".client_search_class").prop("required",true);
    $(".internal_tasks_group").hide();
    $("#internal_checkbox").prop("checked",false);
    $(".infiles_link").show();
    $("#attachments_text").hide();
    
    $("#attachments_infiles").hide();
    $("#idtask").val("");

    $("#hidden_copied_files").val("");
    $("#hidden_copied_notes").val("");
    $("#hidden_copied_infiles").val("");

    $(".accept_recurring").prop("checked",false);
    $(".accept_recurring_div").hide();
    $("#recurring_checkbox1").prop("checked",false);

    $("#open_task").prop("checked",false);
    $(".allocate_user_add").removeClass("disable_user");
    $.ajax({
      url:"<?php echo URL::to('user/clear_session_task_attachments'); ?>",
      type:"post",
      data:{fileid:fileid},
      success: function(result)
      {
        $("#add_notepad_attachments_div").html('');
        $("#add_attachments_div").html('');
        $("body").removeClass("loading");
        $("#attachments_infiles").show();
        $("#add_infiles_attachments_div").html(result);
      }
    })
  }
  
  if($(e.target).hasClass('fanotepadtask')){
    var clientid = $("#client_search_task").val();
    var pos = $(e.target).position();
    var leftposi = parseInt(pos.left) - 20;
    $(e.target).parent().find('.notepad_div_notes_task').css({"position":"absolute","top":pos.top,"left":leftposi}).toggle();
  }
  if($(e.target).hasClass('remove_dropzone_attach_task'))
  {
    var file_id = $(e.target).attr("data-task");
    $.ajax({
      url:"<?php echo URL::to('user/tasks_remove_dropzone_attachment'); ?>",
      type:"post",
      data:{file_id:file_id},
      success: function(result)
      {
        $(e.target).parents("p").detach();
      }
    })
  }
  if($(e.target).hasClass('remove_notepad_attach_task'))
  {
    var file_id = $(e.target).attr("data-task");
    $.ajax({
      url:"<?php echo URL::to('user/tasks_remove_notepad_attachment'); ?>",
      type:"post",
      data:{file_id:file_id},
      success: function(result)
      {
        $(e.target).parents("p").detach();
      }
    })
  }
  if($(e.target).hasClass('b_check'))
  {
    var attachment_id = $(e.target).val();
    if($(e.target).is(":checked"))
    {
      var b_status = 1;
    }
    else{
      var b_status = 0;
    }
    $.ajax({
      url:"<?php echo URL::to('user/change_attachment_bpso_status'); ?>",
      type:"post",
      data:{type:"1",status:b_status,attachment_id:attachment_id},
      success: function(result)
      {

      }
    });
  }
  if($(e.target).hasClass('p_check'))
  {
    var attachment_id = $(e.target).val();
    if($(e.target).is(":checked"))
    {
      var p_status = 1;
    }
    else{
      var p_status = 0;
    }
    $.ajax({
      url:"<?php echo URL::to('user/change_attachment_bpso_status'); ?>",
      type:"post",
      data:{type:"2",status:p_status,attachment_id:attachment_id},
      success: function(result)
      {

      }
    });
  }
  if($(e.target).hasClass('s_check'))
  {
    var attachment_id = $(e.target).val();
    if($(e.target).is(":checked"))
    {
      var s_status = 1;
    }
    else{
      var s_status = 0;
    }
    $.ajax({
      url:"<?php echo URL::to('user/change_attachment_bpso_status'); ?>",
      type:"post",
      data:{type:"3",status:s_status,attachment_id:attachment_id},
      success: function(result)
      {

      }
    });
  }
  if($(e.target).hasClass('o_check'))
  {
    var attachment_id = $(e.target).val();
    if($(e.target).is(":checked"))
    {
      var o_status = 1;
    }
    else{
      var o_status = 0;
    }
    $.ajax({
      url:"<?php echo URL::to('user/change_attachment_bpso_status'); ?>",
      type:"post",
      data:{type:"4",status:o_status,attachment_id:attachment_id},
      success: function(result)
      {

      }
    });
  }
	if($(e.target).hasClass('download_rename'))
  	{
	    e.preventDefault();
	    var element = $(e.target).attr('data-src');
	    var id = $(e.target).attr('data-element');

	    $('body').addClass('loading');

	    $.ajax({
	    	url:"<?php echo URL::to('user/get_attachment_details'); ?>",
	    	type:"get",
	    	data:{id:id,element:element},
	    	success: function(result)
	    	{
	    		if(result == "")
	    		{
	    			SaveToDisk(element,element.split('/').reverse()[0]);
	    		}
	    		else{
	    			SaveToDisk(element,result);
	    		}
	    		$('body').removeClass('loading');
	    	}
	    });
  	}
	if($(e.target).hasClass('add_text_image'))
	{
		$("body").addClass("loading");
		var id = $(e.target).attr("data-element");
		$.ajax({
			url:"<?php echo URL::to('user/change_attachment_text_status'); ?>",
			type:"get",
			data:{id:id},
			success: function(result)
			{
				if($(e.target).parent().find(".fileattachment_checkbox").is(":checked"))
				{
					$(e.target).parent().find(".add_text").prop("disabled",true);
				}
				else{
					$(e.target).parent().find(".add_text").prop("disabled",false);
				}
				$(e.target).parent().find(".add_text").show();
				$(e.target).parent().find(".add_text").val("");
				$(e.target).parent().find(".remove_text_image").show();
				$(e.target).parent().find(".download_rename").show();
				$(e.target).hide();
				$("body").removeClass("loading");
			}
		})
	}
	if($(e.target).hasClass('remove_text_image'))
	{
		$("body").addClass("loading");
		var id = $(e.target).attr("data-element");
		$.ajax({
			url:"<?php echo URL::to('user/remove_attachment_text_status'); ?>",
			type:"get",
			data:{id:id},
			success: function(result)
			{
				$(e.target).parent().find(".add_text_image").show();
				$(e.target).parent().find(".add_text").hide();
				$(e.target).parent().find(".download_rename").hide();
				$(e.target).hide();
				$("body").removeClass("loading");
			}
		})
	}
  if($(e.target).hasClass('single_notify')){

    var taskid = $(e.target).attr("data-element");

    $(".model_notify").modal("show");

    $(".notify_title").html('Send Notification to Selected Staffs');

    $(".notify_file_id").val(taskid);

    $(".notify_id_class").prop("checked", false);

    $(".notify_id_class").prop("disabled", false);



    $("#notity_selectall").prop("checked", false);

    $("#notity_selectall").prop("disabled", false);



  }



  if($(e.target).hasClass('all_notify')){

    var taskid = $(e.target).attr("data-element");

    $(".model_notify").modal("show");

    $(".notify_title").html('Send Notification to All Staffs');

    $(".notify_file_id").val(taskid);

    $(".notify_id_class").prop("checked", true);

    $(".notify_id_class").attr("disabled", true);



    $("#notity_selectall").prop("checked", true);

    $("#notity_selectall").prop("disabled", true);



    //$("#notity_selectall").parent().hide();

  }



  if(e.target.id == "notity_selectall"){

    if($(e.target).is(":checked"))

    {

      $(".notify_id_class").each(function() {

        $(this).prop("checked",true);

      });

    }



    else{

      $(".notify_id_class").each(function() {

        $(this).prop("checked",false);

      });

    }

  }
  if($(e.target).hasClass("notify_all_clients_tasks"))

  {

    $("body").addClass("loading");

    $(".model_notify").modal("hide");

    var emails = [];

    var clientids = [];

    var toemails = '';

    var timeval = '<?php echo time(); ?>';


    var file_id = $(".notify_file_id").val();

    $(".notify_id_class").each(function(i, el) {

        if($(el).is(':checked'))

        {

          var user_email = $(el).attr('data-element');

          var user_id = $(el).attr('data-value');

          

          if(user_email != '' && typeof user_email !== 'undefined')

          {

            if($.inArray(user_email, emails) == -1)

            {

              emails.push(user_email);

              if(toemails == '')

              {

                toemails= user_email;

              }

              else{

                toemails = toemails+', '+user_email;

              }

            }

          }

          if(user_id != '' && typeof user_id !== 'undefined')

          {

            if($.inArray(user_id, clientids) == -1)

            {

              clientids.push(user_id);

            }

          }

        }

    });

    toemails = toemails+', <?php echo $admin_cc; ?>';

    var option_length = emails.length;

    $.each( emails, function( i, value ) {

        setTimeout(function(){

          $.ajax({

            url:"<?php echo URL::to('user/infile_email_notify_tasks_pdf'); ?>",

            type:"get",

            data:{email:value,clientid:clientids[i],toemails:toemails,file_id:file_id,timeval:timeval},

            success: function(result) {

              var keyi = parseInt(i) + parseInt(1);

              if(option_length == keyi)

              {

                $("body").removeClass("loading");

                $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>Email sent Successfully</p>", fixed:true});

              }

            }

          });

        },2000 + ( i * 2000 ));

    }); 

  }
  if(e.target.id == "client_search_infile")
  {
    var clientid = $("#client_search_hidden_infile").val();
    if(clientid == "" || typeof clientid === "undefined")
    {
      alert("Please select a client from the autocomplete list and then click submit.");
      return false;
    }
  }
  if($(e.target).hasClass('fileattachment_checkbox'))
  {
    var value = $(e.target).val();
    $("body").addClass("loading");
    if($(e.target).is(":checked"))
    {
      $.ajax({
        url:"<?php echo URL::to('user/fileattachment_status'); ?>",
        type:"post",
        data:{id:value,status:1},
        success: function(result)
        {
        	$(e.target).parent().find(".add_text").prop("disabled",true);
        	$("body").removeClass("loading");
        }
      });
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/fileattachment_status'); ?>",
        type:"post",
        data:{id:value,status:0},
        success: function(result)
        {
        	$(e.target).parent().find(".add_text").prop("disabled",false);
        	$("body").removeClass("loading");
        }
      });
    }
  }
  if($(e.target).parents(".auto_save_date").length > 0)

  {

    var file_id = $(e.target).parents(".auto_save_date").find(".complete_date").attr("data-element");

    $("#hidden_file_id").val(file_id);

  }

  if($(e.target).hasClass('image_submit'))

  {

    var files = $(e.target).parent().find('.image_file').val();

    if(files == '' || typeof files === 'undefines')

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

  if($(e.target).hasClass('image_submit_add'))

  {

    var files = $(e.target).parent().find('.image_file_add').val();

    if(files == '' || typeof files === 'undefines')

    {

      $(e.target).parent().find(".error_files").text("Please Choose the files to proceed");

      return false;

    }

    else{

      $(e.target).parents('.modal-body').find('.img_div').toggle();

    }

  }

  else{

    $(".img_div").each(function() {

      $(this).hide();

    });

  }

  if($(e.target).hasClass('notepad_submit'))

  { 

    var contents = $(e.target).parent().find('.notepad_contents').val();

    if(contents == '' || typeof contents === 'undefined')

    {

      $(e.target).parent().find(".error_files_notepad").text("Please Enter the contents for the notepad to save.");

      return false;

    }

    else{

      $(e.target).parents('td').find('.notepad_div').toggle();

      $(e.target).parents('td').find('.notepad_div_notes').toggle();

    }

  }

  else{

    $(".notepad_div").each(function() {

      $(this).hide();

    });

    $(".notepad_div_notes").each(function() {

      $(this).hide();

    });

  }

  if($(e.target).hasClass('notepad_submit_add'))

  { 

    var contents = $(e.target).parent().find('.notepad_contents_add').val();

    if(contents == '' || typeof contents === 'undefined')

    {

      $(e.target).parent().find(".error_files_notepad_add").text("Please Enter the contents for the notepad to save.");

      return false;

    }

    else{

      $(e.target).parents('td').find('.notepad_div_notes_add').toggle();

    }

  }

  else{

    $(".notepad_div_notes_add").each(function() {

      $(this).hide();

    });

  }

  if($(e.target).hasClass('image_file'))

  {

    $(e.target).parents('td').find('.img_div').toggle();

    $(e.target).parents('.modal-body').find('.img_div').toggle();

  }

  if($(e.target).hasClass('image_file_add'))

  {

    $(e.target).parents('.modal-body').find('.img_div').toggle();

  }

  if($(e.target).hasClass("dropzone"))

  {

    $(e.target).parents('td').find('.img_div').show();    

    $(e.target).parents('.modal-body').find('.img_div').show();    

  }

  if($(e.target).hasClass("remove_dropzone_attach"))

  {

    $(e.target).parents('td').find('.img_div').show();   

    $(e.target).parents('.modal-body').find('.img_div').show(); 

  }

  if($(e.target).parent().hasClass("dz-message"))

  {

    $(e.target).parents('td').find('.img_div').show();

    $(e.target).parents('.modal-body').find('.img_div').show();    

  }

  if($(e.target).hasClass('notepad_contents'))

  {

    $(e.target).parents('td').find('.notepad_div').toggle();

    $(e.target).parents('td').find('.notepad_div_notes').toggle();

  }

  if($(e.target).hasClass('notepad_contents_add'))

  {

    $(e.target).parents('.modal-body').find('.notepad_div_notes_add').toggle();

  }

  if($(e.target).hasClass('notepad_submit_add'))

  {

    var contents = $(".notepad_contents_add").val();

    var clientid = $("#client_search").val();

    $.ajax({

      url:"<?php echo URL::to('user/add_notepad_contents'); ?>",

      type:"post",

      data:{contents:contents,clientid:clientid},

      success: function(result)

      {

        $("#attachments_text").show();

        $("#add_notepad_attachments_div").append('<p>'+result+'</p>');

        $(".notepad_div_notes_add").hide();

      }

    });

  }

  if($(e.target).hasClass('trash_imageadd'))

  {

    $("body").addClass("loading");

    $.ajax({

      url:"<?php echo URL::to('user/clear_session_attachments'); ?>",

      type:"post",

      success: function(result)

      {

        $("#add_notepad_attachments_div").html('');

        $("#add_attachments_div").html('');

        $("body").removeClass("loading");

      }

    })

  }

  if($(e.target).hasClass('edit_status')){
    if($(e.target).hasClass('incomplete_status'))
    {
      var r = confirm("Are you sure you want to mark this Infile as Incomplete?");
      if (r == true) {
          $("body").addClass("loading");
          var id = $(e.target).attr("data-element");
          $.ajax({
                url:"<?php echo URL::to('user/in_file_status_update'); ?>",
                data:{status:0,id:id},
                success: function(result) {
                  $("body").removeClass("loading");
                  $(e.target).parents("tr").find("td").css({"color" : "#000"});
                  $(e.target).parents("tr").find("a").css({"color" : "#000"});
                  $(e.target).parents("tr").find("i").css({"color" : "#000"});
                  $(e.target).parents("tr").find(".fa-plus").removeClass("disable_class");
                  $(e.target).parents("tr").find(".fa-minus-square").removeClass("disable_class");
                  $(e.target).parents("tr").find(".fa-pencil-square").removeClass("disable_class");
                  $(e.target).parents("tr").find(".user_select").removeClass("disable_class");
                  $(e.target).parents("tr").find(".complete_date").removeClass("disable_class");
                  $(e.target).parents("tr").find("select").prop("disabled",false);
                  $(e.target).parents("tr").find("input").prop("disabled",false);
                  $(e.target).parents("tr").find(".fa-times").addClass('fa-check');
                  $(e.target).parents("tr").find(".fa-times").removeClass('incomplete_status');
                  $(e.target).parents("tr").find(".fa-times").removeClass('fa-times');
                  $(e.target).parents("tr").find(".fileattachment_checkbox").each(function() {
                    if($(this).is(":checked"))
                    {
                      $(this).parent().find(".add_text").prop("disabled",true);
                    }
                  });
                  if($("#show_incomplete").is(':checked'))
                    {
                    $(".user_select").each(function() {
                        if($(this).hasClass('disable_class'))
                        {
                          $(this).parents('tr').hide();
                        }
                    });
                  }
                  else{
                    $(".user_select").each(function() {
                        if($(this).hasClass('disable_class'))
                        {
                          $(this).parents('tr').show();
                        }
                    });
                  }           
                }
          });
      }
      else{
        return false
      }
    }
    else{
      var username = $(e.target).parents("tr").find(".user_select").val();
      var complete_date = $(e.target).parents("tr").find(".complete_date").val();
      if(username == "" || typeof username === "undefined")
      {
        alert("Please choose a Username to mark this file as completed");
      }
      else if(complete_date == "" || typeof complete_date === "undefined")
      {
        alert("Please select the Complete Date to mark this file as completed");
      }
      else{
          var r = confirm("Are you sure you want to Complete this file?");
          if (r == true) {
              $("body").addClass("loading");
              var id = $(e.target).attr("data-element");
              $.ajax({
                    url:"<?php echo URL::to('user/in_file_status_update'); ?>",
                    data:{status:1,id:id},
                    success: function(result) {
                      $("body").removeClass("loading");
                      $(e.target).parents("tr").find("td").css({"color" : "#f00"});
                      $(e.target).parents("tr").find("a").css({"color" : "#f00"});
                      $(e.target).parents("tr").find("i").css({"color" : "#f00"});
                      $(e.target).parents("tr").find(".fa-plus").addClass("disable_class");
                      $(e.target).parents("tr").find(".fa-minus-square").addClass("disable_class");
                      $(e.target).parents("tr").find(".fa-pencil-square").addClass("disable_class");
                      $(e.target).parents("tr").find(".fa-check").css({"color" : "#000"});
                      $(e.target).parents("tr").find(".user_select").addClass("disable_class");
                      $(e.target).parents("tr").find(".complete_date").addClass("disable_class");
                      $(e.target).parents("tr").find("select").prop("disabled",true);
                      $(e.target).parents("tr").find("input").prop("disabled",true);
                      $(e.target).parents("tr").find(".fa-check").addClass('fa-times');
                      $(e.target).parents("tr").find(".fa-check").addClass('incomplete_status');
                      $(e.target).parents("tr").find(".fa-check").removeClass('fa-check');
                      if($("#show_incomplete").is(':checked'))
                        {
                        $(".user_select").each(function() {
                            if($(this).hasClass('disable_class'))
                            {
                              $(this).parents('tr').hide();
                            }
                        });
                      }
                      else{
                        $(".user_select").each(function() {
                            if($(this).hasClass('disable_class'))
                            {
                              $(this).parents('tr').show();
                            }
                        });
                      }
                    }
              });
          }
          else{
            return false
          }
      }
    }
  }


  if($(e.target).hasClass('fa-times')){

    

  }



  /*

  if(e.target.id == 'show_incomplete'){

    Dropzone.autoDiscover = false;

    $("body").addClass("loading");   

    $("#in_file").dataTable().fnDestroy();

    if($(e.target).is(':checked'))

    {

       

      $.ajax({

        url:"<?php echo URL::to('user/in_file_show_incomplete'); ?>",

        type:"post",

        data:{value:1},

        success: function(result)

        {

          $("body").removeClass("loading");

          $("#in_file_tbody").html(result);

          $('#in_file').DataTable({            

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

      });

    }

    else{

      $.ajax({

        url:"<?php echo URL::to('user/in_file_show_incomplete'); ?>",

        type:"post",

        data:{value:0},

        success: function(result)

        {

          $("body").removeClass("loading");

          $("#in_file_tbody").html(result);

          $('#in_file').DataTable({            

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

      });

    }

  }*/



  if(e.target.id == 'show_incomplete'){



    if($("#show_incomplete").is(':checked'))

                {

      $(".user_select").each(function() {

          if($(this).hasClass('disable_class'))

          {

            $(this).parents('tr').hide();

          }

      });
      var status = 1;
    }

    else{

      $(".user_select").each(function() {

          if($(this).hasClass('disable_class'))

          {

            $(this).parents('tr').show();

          }

      });
      var status = 0;

    }

    $.ajax({
      url:"<?php echo URL::to('user/infile_incomplete_status'); ?>",
      type:"post",
      data:{status:status},
      success: function(result)
      {

      }
    });

  }





 





  if($(e.target).hasClass('create_new')) {

    var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});

    $(".create_new_model").modal("show");

    $(".date_received").datetimepicker({

       defaultDate: fullDate,       

       format: 'L',

       format: 'DD-MMM-YYYY',

       maxDate: fullDate,

    });

    $(".date_added").datetimepicker({

       defaultDate: fullDate,

       format: 'L',

       format: 'DD-MMM-YYYY',

    });

    $(".date_added").prop("readonly", true);

    $("#attachments_text").hide();

  }

  

  if($(e.target).hasClass('fa-plus-add'))
  {
    var pos = $(e.target).position();
    var leftposi = parseInt(pos.left);
    $(e.target).parent().find('.img_div_add').toggle();
    Dropzone.forElement("#imageUpload1").removeAllFiles(true);
    $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");    
  }
  else if($(e.target).hasClass('fa-plus-task'))
  {
    var pos = $(e.target).position();
    var leftposi = parseInt(pos.left);
    $(e.target).parent().find('.img_div_task').toggle();
    Dropzone.forElement("#imageUpload5").removeAllFiles(true);
    $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");    
  }
  else if($(e.target).hasClass('fa-plus'))
  {
    var pos = $(e.target).position();
    var leftposi = parseInt(pos.left) - 200;
    $(e.target).parent().find('.img_div').css({"position":"absolute","top":pos.top,"left":leftposi}).toggle();
    $(".dz-message").find("span").html("Click here to BROWSE the files <br/>OR just drop files here to upload");
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

    return false; 

  }

  if($(e.target).hasClass('remove_dropzone_attach'))

  {

    var attachment_id = $(e.target).attr("data-element");

    var file_id = $(e.target).attr("data-task");

    $.ajax({

      url:"<?php echo URL::to('user/infile_remove_dropzone_attachment'); ?>",

      type:"post",

      data:{attachment_id:attachment_id,file_id:file_id},

      success: function(result)

      {

        var countval = $(e.target).parents(".dropzone").find(".dz-preview").length;

        if(countval == 1)

        {

          $(e.target).parents(".dropzone").removeClass("dz-started");

        }

        $(e.target).parents(".dz-preview").detach();

        

      }

    })

  }



  if($(e.target).hasClass('trash_image'))

  {



    var r = confirm("Are You sure you want to delete");

    if (r == true) {

      var imgid = $(e.target).attr('data-element');

      $.ajax({

          url:"<?php echo URL::to('user/infile_delete_image'); ?>",

          type:"get",

          data:{imgid:imgid},

          success: function(result) {

            window.location.reload();

          }

      });

    }

  }



  if($(e.target).hasClass('delete_all_image')){



    var r = confirm("Are You sure you want to delete all the attachments?");

    if (r == true) {

      var id = $(e.target).attr('data-element');

      $.ajax({

          url:"<?php echo URL::to('user/infile_delete_all_image'); ?>",

          type:"get",

          data:{id:id},

          success: function(result) {

            window.location.reload();

          }

      });

    }

  }

  if($(e.target).hasClass('download_all_image')){

      $("body").addClass("loading");

      var id = $(e.target).attr('data-element');

      $.ajax({

          url:"<?php echo URL::to('user/infile_download_all_image'); ?>",

          type:"get",

          data:{id:id},

          success: function(result) {

              SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);

              setTimeout(function() {

                $.ajax({

                  url:"<?php echo URL::to('user/delete_file_link'); ?>",

                  type:"post",

                  data:{result:result},

                  success: function(result)

                  {

                    $("body").removeClass("loading");

                  }

                });

              },3000);

          }

      });

  }

  if($(e.target).hasClass('download_rename_all_image')){

      $("body").addClass("loading");

      var id = $(e.target).attr('data-element');

      $.ajax({

          url:"<?php echo URL::to('user/infile_download_rename_all_image'); ?>",

          type:"get",

          data:{id:id},

          success: function(result) {

              SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);

              setTimeout(function() {

                $.ajax({

                  url:"<?php echo URL::to('user/delete_file_link'); ?>",

                  type:"post",

                  data:{result:result},

                  success: function(result)

                  {

                    $("body").removeClass("loading");

                  }

                });

              },3000);

          }

      });

  }
  if($(e.target).hasClass('download_b_all_image')){

      var lenval = $(e.target).parents("table").find(".b_check:checked").length;
      if(lenval > 0)
      {
          $("body").addClass("loading");

          var id = $(e.target).attr('data-element');

          $.ajax({

              url:"<?php echo URL::to('user/infile_download_bpso_all_image'); ?>",

              type:"get",

              data:{type:"b",id:id},

              success: function(result) {

                  SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);

                  setTimeout(function() {

                    $.ajax({

                      url:"<?php echo URL::to('user/delete_file_link'); ?>",

                      type:"post",

                      data:{result:result},

                      success: function(result)

                      {

                        $("body").removeClass("loading");

                      }

                    });

                  },3000);

              }

          });
      }
      else{
        alert("None of the checkbox is checked to download the files");
      }
  }
  if($(e.target).hasClass('download_p_all_image')){
      var lenval = $(e.target).parents("table").find(".p_check:checked").length;
      if(lenval > 0)
      {
        $("body").addClass("loading");

        var id = $(e.target).attr('data-element');

        $.ajax({

            url:"<?php echo URL::to('user/infile_download_bpso_all_image'); ?>",

            type:"get",

            data:{type:"p",id:id},

            success: function(result) {

                SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);

                setTimeout(function() {

                  $.ajax({

                    url:"<?php echo URL::to('user/delete_file_link'); ?>",

                    type:"post",

                    data:{result:result},

                    success: function(result)

                    {

                      $("body").removeClass("loading");

                    }

                  });

                },3000);

            }

        });
      }
      else{
        alert("None of the checkbox is checked to download the files");
      }
  }
  if($(e.target).hasClass('download_s_all_image')){
      var lenval = $(e.target).parents("table").find(".s_check:checked").length;
      if(lenval > 0)
      {
        $("body").addClass("loading");

        var id = $(e.target).attr('data-element');

        $.ajax({

            url:"<?php echo URL::to('user/infile_download_bpso_all_image'); ?>",

            type:"get",

            data:{type:"s",id:id},

            success: function(result) {

                SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);

                setTimeout(function() {

                  $.ajax({

                    url:"<?php echo URL::to('user/delete_file_link'); ?>",

                    type:"post",

                    data:{result:result},

                    success: function(result)

                    {

                      $("body").removeClass("loading");

                    }

                  });

                },3000);

            }

        });
      }
      else{
        alert("None of the checkbox is checked to download the files");
      }
  }
  if($(e.target).hasClass('download_o_all_image')){
      var lenval = $(e.target).parents("table").find(".o_check:checked").length;
      if(lenval > 0)
      {
        $("body").addClass("loading");

        var id = $(e.target).attr('data-element');

        $.ajax({

            url:"<?php echo URL::to('user/infile_download_bpso_all_image'); ?>",

            type:"get",

            data:{type:"o",id:id},

            success: function(result) {

                SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);

                setTimeout(function() {

                  $.ajax({

                    url:"<?php echo URL::to('user/delete_file_link'); ?>",

                    type:"post",

                    data:{result:result},

                    success: function(result)

                    {

                      $("body").removeClass("loading");

                    }

                  });

                },3000);

            }

        });
      }
      else{
        alert("None of the checkbox is checked to download the files");
      }
  }

  if($(e.target).hasClass('delete_all_notes_only')){



    var r = confirm("Are You sure you want to delete all the attachments?");

    if (r == true) {

      var id = $(e.target).attr('data-element');

      $.ajax({

          url:"<?php echo URL::to('user/infile_delete_all_notes_only'); ?>",

          type:"get",

          data:{id:id},

          success: function(result) {

            window.location.reload();

          }

      });

    }

  }



  if($(e.target).hasClass('download_all_notes_only')){

    $("body").addClass("loading");

      var id = $(e.target).attr('data-element');

      $.ajax({

          url:"<?php echo URL::to('user/infile_download_all_notes_only'); ?>",

          type:"get",

          data:{id:id},

          success: function(result) {

            SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);

              

              setTimeout(function() {

                $.ajax({

                  url:"<?php echo URL::to('user/delete_file_link'); ?>",

                  type:"post",

                  data:{result:result},

                  success: function(result)

                  {

                    $("body").removeClass("loading");

                  }

                });

              },3000);

          }

      });

  }  
if($(e.target).hasClass('bpso_all_check')){
  $("body").addClass("loading");
  var id = $(e.target).attr('id');
  var type = $(e.target).attr('data-element');
  $.ajax({
        url: "<?php echo URL::to('user/bpso_all_check') ?>",
        type:"post",        
        data:{id:id, type:type},
        dataType: "json",       
        success:function(result){
          $("#bspo_id_"+result['id']).html(result['table_content']);
          $("body").removeClass("loading");
          $('[data-toggle="tooltip"]').tooltip();
                         
    }
  });

}




  if($(e.target).hasClass('delete_all_notes')){



    var r = confirm("Are You sure you want to delete all the attachments?");

    if (r == true) {

      var id = $(e.target).attr('data-element');

      $.ajax({

          url:"<?php echo URL::to('user/infile_delete_all_notes'); ?>",

          type:"get",

          data:{id:id},

          success: function(result) {

            window.location.reload();

          }

      });

    }

  }

  if($(e.target).hasClass('download_all_notes')){

    $("body").addClass("loading");

      var id = $(e.target).attr('data-element');

      $.ajax({

          url:"<?php echo URL::to('user/infile_download_all_notes'); ?>",

          type:"get",

          data:{id:id},

          success: function(result) {

            SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);

              

              setTimeout(function() {

                $.ajax({

                  url:"<?php echo URL::to('user/delete_file_link'); ?>",

                  type:"post",

                  data:{result:result},

                  success: function(result)

                  {

                    $("body").removeClass("loading");

                  }

                });

              },3000);

          }

      });

  }



  if($(e.target).hasClass('fa-pencil-square')){



    var pos = $(e.target).position();

    var leftposi = parseInt(pos.left) - 200;

    $(e.target).parent().find('.notepad_div').css({"position":"absolute","top":pos.top,"left":leftposi}).toggle();



  }



  if($(e.target).hasClass('fanotepad_notes')){



    var pos = $(e.target).position();

    var leftposi = parseInt(pos.left) - 200;

    $(e.target).parent().find('.notepad_div_notes').css({"position":"absolute","top":pos.top,"left":leftposi}).toggle();



  }



   if($(e.target).hasClass('fanotepadadd')){

    var clientid = $("#client_search").val();

    if(clientid == "")

    {

      alert("Please Choose the client id to create the attachments");

    }

    else{

      var pos = $(e.target).position();

      var leftposi = parseInt(pos.left) - 20;

      $(e.target).parent().find('.notepad_div_notes_add').css({"position":"absolute","top":pos.top,"left":leftposi}).toggle();

    }

  }





  if($(e.target).hasClass('internal_checkbox')){

    var id = $(e.target).attr('data-element');

    if($(e.target).is(':checked')){

      $.ajax({

        url:"<?php echo URL::to('user/infile_internal'); ?>",

        type:"get",

        data:{internal:1,id:id},

        success: function(result) {

          //$(e.target).parents('tr').find('.task_label').css({'color':'#89ff00','font-weight':'800'});

        }

      });

    }

    else{

      $.ajax({

        url:"<?php echo URL::to('user/infile_internal'); ?>",

        type:"get",

        data:{internal:0,id:id},

        success: function(result) {

          //$(e.target).parents('tr').find('.task_label').css({'color':'#fff','font-weight':'600'});

        }



      });

    }

  }

  if($(e.target).hasClass('reportclassdiv'))
  {
    $(".report_div").toggle();
  }

  if($(e.target).hasClass('ok_button'))
  {
    var check_option = $(".class_invoice:checked").val();
    $("#show_incomplete_report").prop("checked", true);
    $(".report_show_incomplete").val(1);

    if(check_option === "" || typeof check_option === "undefined")
    {
      alert("Please select atleast one report type to move forward.");
    }
    else{
      $(".report_type").val(1);
      var id = $('input[name="report_infile"]:checked').val();
      $(".class_invoice").prop("checked", false);
      if(id == 1){
          $("#report_tbody").html('');
          $("body").addClass("loading");
          $.ajax({
              url: "<?php echo URL::to('user/report_infile') ?>",
              data:{id:0},
              type:"post",
              success:function(result){
                 $(".report_infile_model").modal("show");                 
                 $(".report_div").hide();
                 $("body").removeClass("loading");
                 $("#report_tbody").html(result);
                 $(".select_all").hide();
                 $(".single_client_button").show();
                 $(".all_client_button").hide();                 
          }
        });
      }
      else{
        $(".report_type").val(2);
        $("#report_tbody").html('');
        $("body").addClass("loading");
          $.ajax({
              url: "<?php echo URL::to('user/report_infile') ?>",
              data:{id:1},
              type:"post",
              success:function(result){  
                $(".report_infile_model").modal("show");
                $(".report_div").hide();
                $("body").removeClass("loading");      
                $("#report_tbody").html(result);
                $(".select_all").show(); 
                $(".single_client_button").hide();
                $(".all_client_button").show();
          }
        });
      }
    }
  }


  if(e.target.id == "select_all_class") {
    if($(e.target).is(":checked")){
      $(".select_client").each(function() {
        $(this).prop("checked",true);
      });
    }
    else{
      $(".select_client").each(function() {
        $(this).prop("checked",false);
      });
    }
  }


  if(e.target.id == "save_as_pdf")
  {
    $("#report_pdf_type_two_tbody").html('');
    var status = $(".report_show_incomplete").val();
    if($(".select_client:checked").length)
    {
      $("body").addClass("loading");
        var checkedvalue = '';
        var size = 100;
        $(".select_client:checked").each(function() {
          var value = $(this).val();
          if(checkedvalue == "")
          {
            checkedvalue = value;
          }
          else{
            checkedvalue = checkedvalue+","+value;
          }
        });
        var exp = checkedvalue.split(',');
        var arrayval = [];
        for (var i=0; i<exp.length; i+=size) {
            var smallarray = exp.slice(i,i+size);
            arrayval.push(smallarray);
        }
        $.each(arrayval, function( index, value ) {
            setTimeout(function(){ 
              var imp = value.join(',');
              $.ajax({
                url:"<?php echo URL::to('user/infile_report_pdf'); ?>",
                type:"post",
                data:{value:imp, status:status},
                success: function(result)
                {
                  $("#report_pdf_type_two_tbody").append(result);
                  
                  var last = index + parseInt(1);
                  if(arrayval.length == last)
                  {
                    var pdf_html = $("#report_pdf_type_two").html();
                    $.ajax({
                      url:"<?php echo URL::to('user/download_infile_report_pdf'); ?>",
                      type:"post",
                      data:{htmlval:pdf_html},
                      success: function(result)
                      {
                        SaveToDisk("<?php echo URL::to('infile_report'); ?>/"+result,result);
                      }
                    });
                  }
                }
              });
            }, 3000);
        });
        
    }
    else{
      $("body").removeClass("loading");
      alert("Please Choose atleast one client to continue.");
    }
  }
  if(e.target.id == "save_as_csv")
  {
    $("body").addClass("loading");
    var status = $(".report_show_incomplete").val();
    if($(".select_client:checked").length)
    {
      var checkedvalue = '';
      $(".select_client:checked").each(function() {
          var value = $(this).val();
          if(checkedvalue == "")
          {
            checkedvalue = value;
          }
          else{
            checkedvalue = checkedvalue+","+value;
          }
      });
      $.ajax({
        url:"<?php echo URL::to('user/infile_report_csv'); ?>",
        type:"post",
        data:{value:checkedvalue, status:status},
        success: function(result)
        {
          SaveToDisk("<?php echo URL::to('infile_report'); ?>/Infile_Report.csv",'Infile_Report.csv');
        }
      });
    }
    else{
      $("body").removeClass("loading");
      alert("Please Choose atleast one client to continue.");
    }
  }

  if(e.target.id == "single_save_as_csv")
  {
    $("body").addClass("loading");
    var status = $(".report_show_incomplete").val();
    if($(".select_client:checked").length)
    {
      var checkedvalue = '';
      $(".select_client:checked").each(function() {
          var value = $(this).val();
          if(checkedvalue == "")
          {
            checkedvalue = value;
          }
          else{
            checkedvalue = checkedvalue+","+value;
          }
      });
      $.ajax({
        url:"<?php echo URL::to('user/infile_report_csv_single'); ?>",
        type:"post",
        data:{value:checkedvalue, status:status},
        success: function(result)
        {
          SaveToDisk("<?php echo URL::to('infile_report'); ?>/Infile_Report.csv",'Infile_Report.csv');
        }
      });
    }
    else{
      $("body").removeClass("loading");
      alert("Please Choose atleast one client to continue.");
    }
  }


  if(e.target.id == "single_save_as_pdf")
  {
    $("#report_pdf_type_two_tbody_single").html('');
    var status = $(".report_show_incomplete").val();
    console.log(status);
    if($(".select_client:checked").length)
    {
      $("body").addClass("loading");
        var checkedvalue = '';
        var size = 100;
        $(".select_client:checked").each(function() {
          var value = $(this).val();
          if(checkedvalue == "")
          {
            checkedvalue = value;
          }
          else{
            checkedvalue = checkedvalue+","+value;
          }
        });
        var exp = checkedvalue.split(',');
        var arrayval = [];
        for (var i=0; i<exp.length; i+=size) {
            var smallarray = exp.slice(i,i+size);
            arrayval.push(smallarray);
        }
        $.each(arrayval, function( index, value ) {
            setTimeout(function(){ 
              var imp = value.join(',');
              $.ajax({
                url:"<?php echo URL::to('user/infile_report_pdf_single'); ?>",
                type:"post",
                data:{value:imp, status:status},
                success: function(result)
                {
                  $("#report_pdf_type_two_tbody_single").append(result);
                  
                  var last = index + parseInt(1);
                  if(arrayval.length == last)
                  {
                    var pdf_html = $("#report_pdf_type_two_single").html();
                    $.ajax({
                      url:"<?php echo URL::to('user/download_infile_report_pdf_single'); ?>",
                      type:"post",
                      data:{htmlval:pdf_html},
                      success: function(result)
                      {
                        SaveToDisk("<?php echo URL::to('infile_report'); ?>/"+result,result);
                      }
                    });
                  }
                }
              });
            }, 3000);
        });
        
    }
    else{
      $("body").removeClass("loading");
      alert("Please Choose atleast one client to continue.");
    }
  }

  if(e.target.id == 'show_incomplete_report'){
    var type = $(".report_type").val();
    $("body").addClass("loading");
    if($(e.target).is(':checked'))
    {
      $.ajax({
        url:"<?php echo URL::to('user/infile_report_incomplete'); ?>",
        type:"post",
        data:{id:0, type:type},
        success: function(result)
        {
          $("#report_tbody").html(result);
          $(".report_show_incomplete").val(1);
          $("body").removeClass("loading");
        }
      });
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/infile_report_incomplete'); ?>",
        type:"post",
        data:{id:1, type:type},
        success: function(result)
        {
          $("#report_tbody").html(result);
          $(".report_show_incomplete").val(2);
          $("body").removeClass("loading");
        }
      });
    }

  }









});



$(function () {

  $(".auto_save_date").on("dp.change", function (e) {

      var file_id = $("#hidden_file_id").val();

      var input_val = $("#infile_"+file_id).find(".complete_date").val();

      

      $.ajax({

        url:"<?php echo URL::to('user/infile_complete_date'); ?>",

        type:"get",

        data:{date:input_val,id:file_id},

        success: function(result) {

          

        }

      });

  });
  $(".auto_save_date").on("dp.show", function (e) {
    $(this).find(".complete_date").val("");
  });
  $(".complete_date").on("dp.show", function (e) {
    $(this).val("");
  });

});

$(document).ready(function() {

if($("#show_incomplete").is(':checked'))

                {

  $(".user_select").each(function() {

      if($(this).hasClass('disable_class'))

      {

        $(this).parents('tr').hide();

      }

  });

}

else{

  $(".user_select").each(function() {

      if($(this).hasClass('disable_class'))

      {

        $(this).parents('tr').show();

      }

  });

}



 $('[data-toggle="tooltip"]').tooltip(); 







  $(".client_search_class").autocomplete({

      source: function(request, response) {

          $.ajax({

              url:"<?php echo URL::to('user/task_client_search'); ?>",

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

          dataType: "json",

          url:"<?php echo URL::to('user/task_client_search_select'); ?>",

          data:{value:ui.item.id},

          success: function(result){         

            $("#client_search").val(ui.item.id);     

            $(".create_new_class").show();

          }

        })

      }

  });

  $(".client_common_search").autocomplete({

      source: function(request, response) {        

          $.ajax({

              url:"<?php echo URL::to('user/task_client_common_search'); ?>",

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

        $("body").addClass("loading");

        $("#client_search").val(ui.item.id);

        //$("#in_file").dataTable().fnDestroy();

        $.ajax({

          dataType: "json",

          url:"<?php echo URL::to('user/task_client_common_search_select'); ?>",

          data:{value:ui.item.id},

          success: function(result){   

            $("body").removeClass("loading");

            $(".client_search_common").val(ui.item.id);          





            /*



            $("#in_file_tbody").html(result['result_row']);   

            $('#in_file').DataTable({            

              fixedHeader: {

                headerOffset: 75

              },

              autoWidth: true,

              scrollX: false,

              fixedColumns: false,

              searching: false,

              paging: false,

              info: false,            

          });*/

          }

        })

      }

  });

});





</script>



<script>

fileList = new Array();

Dropzone.options.imageUpload = {

    acceptedFiles: null,

    maxFilesize:50000,

    timeout: 10000000,

    dataType: "HTML",
    
    parallelUploads: 1,

    init: function() {

        this.on('sending', function(file) {

            $("body").addClass("loading");

        });

        this.on("drop", function(event) {

            $("body").addClass("loading");        

        });

        this.on("success", function(file, response) {

            var obj = jQuery.parseJSON(response);

            file.serverId = obj.id; // Getting the new upload ID from the server via a JSON response

            if(obj.id != 0)

            {

              file.previewElement.innerHTML = "<p>"+obj.filename+" <a href='javascript:' class='remove_dropzone_attach' data-element='"+obj.id+"' data-task='"+obj.task_id+"'>Remove</a></p>";

            }

            else{

              $("#attachments_text").show();

              $("#add_attachments_div").append("<p>"+obj.filename+" </p>");

              $(".img_div").each(function() {

                $(this).hide();

              });

            }



        });

        this.on("complete", function (file) {

        	if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {

        		$("body").removeClass("loading");

        	}

	      

	       });

        this.on("error", function (file) {

            console.log(file);

            $("body").removeClass("loading");

      });

        this.on("canceled", function (file) {

            $("body").removeClass("loading");

      });

        

        this.on("removedfile", function(file) {

            if (!file.serverId) { return; }

            $.get("<?php echo URL::to('user/remove_property_images'); ?>"+"/"+file.serverId);

        });

    },

};

Dropzone.options.imageUpload1 = {

    maxFiles: 2000,

    acceptedFiles: null,

    maxFilesize:500000,

    timeout: 10000000,

    dataType: "HTML",
    
    parallelUploads: 1,

    maxfilesexceeded: function(file) {
        this.removeAllFiles();
        this.addFile(file);
    },

    init: function() {

        this.on('sending', function(file) {

            $("body").addClass("loading");
            $(".accepted_files_main").hide();

            $(".accepted_files").html(0);

        });

        this.on("drop", function(event) {

            $("body").addClass("loading");        

        });

        this.on("success", function(file, response) {

            var obj = jQuery.parseJSON(response);

            file.serverId = obj.id; // Getting the new upload ID from the server via a JSON response

            if(obj.id != 0)

            {

              file.previewElement.innerHTML = "<p>"+obj.filename+" <a href='javascript:' class='remove_dropzone_attach' data-element='"+obj.id+"' data-task='"+obj.task_id+"'>Remove</a></p>";

            }

            else{

              $("#attachments_text").show();

              $("#add_attachments_div").append("<p>"+obj.filename+" </p>");

              $(".img_div").each(function() {

                $(this).hide();

              });

            }



        });

        this.on("complete", function (file) {

          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            var acceptedcount= this.getAcceptedFiles().length;
            var rejectedcount= this.getRejectedFiles().length;
            $(".accepted_files_main").show();

            $(".accepted_files").html(acceptedcount);
            var totalcount = acceptedcount + rejectedcount;
            $("#total_count_files").val(totalcount);
            $("body").removeClass("loading");

          }

        

         });

        this.on("error", function (file) {

            $("body").removeClass("loading");

      });

        this.on("canceled", function (file) {

            $("body").removeClass("loading");

      });

        

        this.on("removedfile", function(file) {

            if (!file.serverId) { return; }

            $.get("<?php echo URL::to('user/remove_property_images'); ?>"+"/"+file.serverId);

        });

    },

};
Dropzone.options.imageUpload5 = {
    maxFiles: 2000,
    acceptedFiles: null,
    maxFilesize:500000,
    timeout: 10000000,
    dataType: "HTML",
    parallelUploads: 1,
    maxfilesexceeded: function(file) {
        this.removeAllFiles();
        this.addFile(file);
    },
    init: function() {
        this.on('sending', function(file) {
            $("body").addClass("loading");
        });
        this.on("drop", function(event) {
            $("body").addClass("loading");        
        });
        this.on("success", function(file, response) {
            var obj = jQuery.parseJSON(response);
            file.serverId = obj.id; // Getting the new upload ID from the server via a JSON response
            if(obj.id != 0)
            {
              file.previewElement.innerHTML = "<p>"+obj.filename+" <a href='javascript:' class='remove_dropzone_attach_task' data-task='"+obj.task_id+"'>Remove</a></p>";
            }
            else{
              $("#attachments_text").show();
              $("#add_attachments_div_task").append("<p>"+obj.filename+" <a href='javascript:' class='remove_dropzone_attach_task' data-task='"+obj.file_id+"'>Remove</a></p>");
              $(".img_div").each(function() {
                $(this).hide();
              });
            }
        });
        this.on("complete", function (file) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            var acceptedcount= this.getAcceptedFiles().length;
            var rejectedcount= this.getRejectedFiles().length;
            var totalcount = acceptedcount + rejectedcount;
            $("#total_count_files").val(totalcount);
            $("body").removeClass("loading");
          }
        });
        this.on("error", function (file) {
            $("body").removeClass("loading");
        });
        this.on("canceled", function (file) {
            $("body").removeClass("loading");
        });
        this.on("removedfile", function(file) {
            if (!file.serverId) { return; }
            $.get("<?php echo URL::to('user/remove_property_images'); ?>"+"/"+file.serverId);
        });
    },
};
//on keyup, start the countdown


var typingTimer;                //timer identifier
var doneTypingInterval = 1000;  //time in ms, 5 second for example
var $input = $('.add_text');

$input.on('keyup', function () {
  var input_val = $(this).val();
  var id = $(this).attr('data-element');
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping, doneTypingInterval,input_val,id);
});
//on keydown, clear the countdown 
$input.on('keydown', function () {
  clearTimeout(typingTimer);
});
//user is "finished typing," do something
function doneTyping (input,id) {
  $.ajax({
        url:"<?php echo URL::to('user/update_fileattachment_textval'); ?>",
        type:"get",
        data:{input:input,id:id},
        success: function(result) {
        }
      });
}

$.ajaxSetup({async:false});

$( "#create_task_form" ).validate({

    rules: {
        select_user : {required: true},
        created_date : { required: true},   
        client_name : { required: true},   
        due_date : { required: true},   
    },
    messages: {
        select_user : {
          required : "Please select the Author",
        },
        created_date : {
            required : "Creation Date is required",
        },
        client_name : {
            required : "Client Name is required",
        },
        due_date : {
            required : "Due Date is required",
        },
    },
});
</script>



@stop
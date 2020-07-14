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



<div class="content_section" style="margin-bottom:200px">
  <div class="page_title">
        <h4 class="col-lg-3" style="padding: 0px;">
                Active Job                
            </h4>
            <div class="col-lg-7 text-right" style="padding-right: 0px; line-height: 35px;">
                
            </div>
            <div class="col-lg-3 text-right" style="padding: 0px;float:right">
              <div class="select_button" style=" margin-left: 10px;">
                <ul>
                <li><a href="javascript:" style="font-size: 13px; font-weight: 500;" class="report_csv">Report CSV</a></li>               
                <li><a href="javascript:" style="font-size: 13px; font-weight: 500;" class="report_pdf">Report PDF</a></li>   
                <li><a href="<?php echo URL::to('user/time_track'); ?>" style="font-size: 13px; font-weight: 500;">TimeMe Manager</a></li>               
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
     <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="<?php echo URL::to('user/time_me_overview')?>">Active Job</a></li>
        <li role="presentation"><a href="<?php echo URL::to('user/time_me_joboftheday')?>">Job of the day / Close Job</a></li>
        <li role="presentation"><a href="<?php echo URL::to('user/time_me_client_review')?>" >Client Review </a></li>
        <li role="presentation"><a href="<?php echo URL::to('user/time_me_all_job')?>">All Jobs </a></li>
      </ul>

       <div class="tab-content" style="background: #fff; padding-top: 25px; padding-bottom:15px;">
        <div class="col-lg-2" style="padding: 0px;"><input type="checkbox" class="select_all_class" id="select_all_class" value="1" style="padding-top: 20px;"><label for="select_all_class" style="font-size: 14px; font-weight: normal;">Select all</label></div>
        <table class="display nowrap fullviewtablelist" id="active_job" width="100%">
          <thead>
            <tr style="background: #fff;">
              <th></th>
              <th width="2%" style="text-align: left;">S.No</th>
              <th style="text-align: left;">User Name</th>
              <th style="text-align: left;">Client Name</th>
              <th style="text-align: left;">Task Name</th>
              <th style="text-align: left;">Task Type</th>
              <th style="text-align: left;">Date</th>
              <th style="text-align: left;">Start Time</th>
              <th style="text-align: left;">Job Time</th>             
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
                        $task_type = '<i class="fa fa-globe" style="margin-right:10px;"></i> Global Task';
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

                    $user_details = DB::table('user')->where('user_id', $jobs->user_id)->first();

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
              <td>
              <input type="checkbox" name="select_job" class="select_job class_'.$jobs->id.'" data-element="'.$jobs->id.'" value="'.$jobs->id.'"><label>&nbsp</label>
              </td>
              <td align="left">'.$i.'</td>
              <td align="left">'.$user_details->firstname.'</td>
              <td align="left">'.$client_name.'</td>
              <td align="left">'.$task_name.'</td>
              <td align="left">'.$task_type.'</td>
              <td align="left">'.date('d-M-Y', strtotime($jobs->job_date)).'</td>
              <td align="left">'.$data['start_time'] = date('h:i A', strtotime($jobs->start_time)).'</td>
              <td align="left">

              <span id="job_time_refresh_'.$jobs->id.'">'.$jobtime.'</span> &nbsp;&nbsp;<a href="javascript:"><i class="fa fa-refresh job_time_refresh" aria-hidden="true" data-element="'.$jobs->id.'"></i></a>

              </td>
              
              
            </tr>';
            
            $joblist_child = DB::table('task_job')->where('active_id',$jobs->id)->get();
            $ichild=1;            
            if(count($joblist_child)){              
              foreach ($joblist_child as $child) {
                
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

                    $break_time_count = DB::table('job_break_time')->where('job_id', $child->id)->get();
                                        
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

                    $user_details = DB::table('user')->where('user_id', $child->user_id)->first();

                    //-----------Job Time Start----------------

                    $created_date = $child->job_created_date;

                    $jobstart = strtotime($created_date.' '.$child->start_time);
                    $jobend   = strtotime($created_date.' '.date('H:i:s'));
                    

                    if($jobend < $jobstart)
                    {
                      $todate = date('Y-m-d', strtotime("+1 day", $jobend));
                      $jobend   = strtotime($todate.' '.date('H:i:s'));
                    }

                    $jobdiff  = $jobend - $jobstart;


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
              <td>
              <input type="checkbox" name="select_jobasasasa" class="select_jobassasa classasas_'.$child->id.'" data-element="'.$child->id.'" value="'.$child->id.'" style="display:none"><label style="display:none">&nbsp</label>
              </td>
              <td align="left">'.$i.'.'.$ichild.'</td>
              <td align="left">'.$user_details->firstname.'</td>
              <td align="left">'.$client_name.'</td>
              <td align="left">'.$task_name.'</td>
              <td align="left">'.$task_type.'</td>
              <td align="left">'.date('d-M-Y', strtotime($child->job_date)).'</td>
              <td align="left">'.$data['start_time'] = date('h:i A', strtotime($child->start_time)).'</td>
              <td align="left">

              <span id="job_time_refresh_'.$child->id.'">'.$jobtime.'</span> &nbsp;&nbsp;<a href="javascript:"><i class="fa fa-refresh job_time_refresh" aria-hidden="true" data-element="'.$child->id.'"></i></a>

              </td>
              
              
            </tr>';
            $ichild++;
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

<h3 id="pdf_title_all_ivoice" style="width: 100%; text-align: center; margin: 15px 0px; float: left;">Active Job Report</h3>

  <table class="table_style">
    <thead>
      <tr style="background: #fff;">        
        <th width="2%" style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">S.No</th>
        <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">User Name</th>
        <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Client Name</th>
        <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Task Name</th>
        <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Task Type</th>
        <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Date</th>
        <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Start Time</th>
        <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Job Time</th>             
    </tr>
    </thead>
    <tbody id="report_pdf_type_two_tbody">
    </tbody>
</table>
</div>

<div class="modal_load"></div>
<input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
<input type="hidden" name="show_alert" id="show_alert" value="">
<input type="hidden" name="pagination" id="pagination" value="1">
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

});
</script>

<script>
$(window).click(function(e) {
  /*if($(e.target).hasClass('export_csv')) {
    $("body").addClass("loading");
    $.ajax({
        url:"<?php echo URL::to('user/active_job_report_csv'); ?>",
        type:"post",        
        success: function(result)
        {
          SaveToDisk("<?php echo URL::to('job_file'); ?>/active_job_Report.csv",'active_job_Report.csv');
        }
      });
  }*/


  if($(e.target).hasClass('report_csv')) {


    if($(".select_job:checked").length)
      {
        var checkedvalue = '';
        $(".select_job:checked").each(function() {
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
          url:"<?php echo URL::to('user/active_job_report_csv'); ?>",
          type:"post",
          data:{value:checkedvalue},
          success: function(result)
          {
            SaveToDisk("<?php echo URL::to('job_file'); ?>/active_job_Report.csv",'active_job_Report.csv');
          }
        });
      }

      else{
        $("body").removeClass("loading");
        alert("Please Choose atleast one Active Job.");
      }
  }

  if($(e.target).hasClass('report_pdf')) {
     $("#report_pdf_type_two_tbody").html('');
     if($(".select_job:checked").length){
          $("body").addClass("loading");
            var checkedvalue = '';
            var size = 100;
            $(".select_job:checked").each(function() {
              var value = $(this).val();
              if(checkedvalue == "")
              {
                checkedvalue = value;
              }
              else{
                checkedvalue = checkedvalue+","+value;
              }
            });
            
            var imp = checkedvalue;
            $.ajax({
              url:"<?php echo URL::to('user/active_job_report_pdf'); ?>",
              type:"post",
              data:{value:imp},
              success: function(result)
              {
                SaveToDisk("<?php echo URL::to('job_file'); ?>/"+result,result);
              }
            });
                
        }

      else{
        $("body").removeClass("loading");
        alert("Please Choose atleast one Active Job.");
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


  if(e.target.id == "select_all_class"){
    if($(e.target).is(":checked"))
    {
      $(".select_job").each(function() {
        $(this).prop("checked",true);
      });
    }

    else{
      $(".select_job").each(function() {
        $(this).prop("checked",false);
      });
    }
  }


});
</script>

@stop
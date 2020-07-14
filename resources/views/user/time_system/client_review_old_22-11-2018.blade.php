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

<script type="text/javascript" src="<?php echo URL::to('assets/js/bootstrap-multiselect.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo URL::to('assets/css/bootstrap-multiselect.css'); ?>" type="text/css"/>

<style>
.multiselect-container > li > a > label.checkbox
    {
        min-width: 180px;
    }
    .btn-group > .btn:first-child
    {
        min-width: 180px;
    }
body{
  background: #2fd9ff !important;
}
.col-md-2{
      padding-right: 5px;
    padding-left: 5px;
}
.search_button{
    background: #000;
    text-align: center;
    padding: 7px 12px;
    color: #fff;
    float: left;
}
.search_button:hover{
    background: #5f5f5f;
    text-align: center;
    padding: 7px 12px;
    color: #fff;
    float: left;
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
        <h4 class="col-lg-2" style="padding: 0px;">
                Client Review
            </h4>
            <div class="col-lg-10 text-right" style="padding-right: 0px; line-height: 35px;">
                <form action="post">
                  <div class="col-lg-12" style="padding: 0px;">
                    <div class="col-md-2">
                      <div class="form-group">
                         
                              <input type="text" class="form-control" id="search_clientid" placeholder="Client Id" name="clientid" style="font-weight: 500;" required />
                          
                      </div>    
                    </div> 
                    <div class="col-md-2">
                      <div class="form-group">
                              <select id="select_tasks" class="form-control select_tasks" multiple="multiple">
                                  
                              </select>
                      </div>    
                    </div>
                    <div class="col-md-3">
                      <div class="form-group date_group">
                          <label class="input-group datepicker-only-init">
                              <input type="text" class="form-control" id="start_date" placeholder="Select Start Date" name="start_date" style="font-weight: 500;" required />
                              <span class="input-group-addon">
                                  <i class="glyphicon glyphicon-calendar"></i>
                              </span>
                          </label>
                      </div>    
                    </div> 
                    <div class="col-md-2">
                      <div class="form-group date_group">
                          <label class="input-group datepicker-stoponly-init">
                              <input type="text" class="form-control" id="stop_date" placeholder="Select End Date" name="end_date" style="font-weight: 500;" required />
                              <span class="input-group-addon">
                                  <i class="glyphicon glyphicon-calendar"></i>
                              </span>
                          </label>
                      </div>     
                    </div>  
                    <div class="col-md-2">
                      <div class="form-group">
                              <select id="select_users" class="form-control select_users" multiple="multiple">
                                  
                                  <?php
                                  if(count($userlist))
                                  {
                                    foreach($userlist as $user)
                                    {
                                      echo '<option value="'.$user->user_id.'">'.$user->firstname.' '.$user->lastname.'</option>';
                                    }
                                  }
                                  ?>
                              </select>
                      </div>    
                    </div>
                    <div class="col-lg-1">
                      <input type="hidden" id="client_search" class="client_search" value="">
                      <input type="button" class="btn btn-primary btn-sm search_button" value="Search"  name="">
                      
                    </div>            
                  </div>
                </form>
            </div>
            <div class="col-lg-3 text-right" style="padding: 0px;float:right">
              <div class="select_button" style=" margin-left: 10px;">
                <ul>
                  <li><a href="javascript:" style="font-size: 13px; font-weight: 500;" class="review_all report_csv">Report CSV</a></li>               
                  <li><a href="javascript:" style="font-size: 13px; font-weight: 500;" class="add_new report_pdf">Report PDF</a></li>  
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
        <li role="presentation"><a href="<?php echo URL::to('user/time_me_overview')?>">Active Job</a></li>
        <li role="presentation"><a href="<?php echo URL::to('user/time_me_joboftheday')?>" >Job of the day / Close Job</a></li>
        <li role="presentation" class="active"><a href="<?php echo URL::to('user/time_me_client_review')?>">Client Review </a></li>
        <li role="presentation"><a href="<?php echo URL::to('user/time_me_all_job')?>">All Jobs </a></li>
      </ul>

       <div class="tab-content" style="background: #fff; padding-top: 25px; padding-bottom: 15px;">
          <div class="filter_text" style="width: 100%; text-align: center; font-size: 15px; font-weight: bold;">Use the filter option to get the desired search results to Export as CSV / PDF</div>
          <div class="table_selectall" style="display: none;">
            <div class="col-lg-2" style="padding: 0px;"><input type="checkbox" class="select_all_class" id="select_all_class" value="1" style="padding-top: 20px;"><label for="select_all_class" style="font-size: 14px; font-weight: normal;">Select all</label></div>
            <table class="display nowrap fullviewtablelist" id="job_oftheday" width="100%">
              <thead>
                <tr style="background: #fff;">
                  <th width="2%"></th>
                  <th width="2%" style="text-align: left;">S.No</th>
                  <th style="text-align: left;">User Name</th>
                  <th style="text-align: left;">Client Name</th>
                  <th style="text-align: left;">Task Name</th>
                  <th style="text-align: left;">Task Type</th>
                  <th style="text-align: left;">Date</th>
                  <th style="text-align: left;">Start Time</th>
                  <th style="text-align: left;">Stop Time</th>
                  <th style="text-align: left;">Job Time</th> 
                  <th style="text-align: left;">Action</th>             
                  
              </tr>
              </thead>
              <tbody id="tbody_job_oftheday">
                
                
              </tbody>
            </table>
          </div>


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
        <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Stop Time</th>
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
<script type="text/javascript">
    $(function () {       
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
        $('.datepicker-stoponly-init').datetimepicker({
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
  $('#select_tasks').multiselect({includeSelectAllOption: true,nonSelectedText: 'Select Tasks'});
  $('#select_users').multiselect({includeSelectAllOption: true,nonSelectedText: 'Select Users'});
     $("#search_clientid").autocomplete({
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
            
            url:"<?php echo URL::to('user/timesystem_client_search_select_tasks'); ?>",
            data:{value:ui.item.id},
            success: function(result){
              $("#select_tasks").html(result);
              $('#select_tasks').multiselect('rebuild');
            }
          })
        }
    });     
});
$(window).click(function(e) {
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
          url:"<?php echo URL::to('user/clientreview_report_csv'); ?>",
          type:"post",
          data:{value:checkedvalue},
          success: function(result)
          {
            SaveToDisk("<?php echo URL::to('job_file'); ?>/clientreview_Report.csv",'clientreview_Report.csv');
          }
        });
      }

      else{
        $("body").removeClass("loading");
        alert("Please Choose atleast one Job.");
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
                    url:"<?php echo URL::to('user/clientreview_report_pdf'); ?>",
                    type:"post",
                    data:{value:imp},
                    success: function(result)
                    {
                      $("#report_pdf_type_two_tbody").append(result);
                      var last = index + parseInt(1);
                      if(arrayval.length == last)
                      {
                        var pdf_html = $("#report_pdf_type_two").html();
                        $.ajax({
                          url:"<?php echo URL::to('user/clientreview_report_pdf_download'); ?>",
                          type:"post",
                          data:{htmlval:pdf_html},
                          success: function(result)
                          {
                            SaveToDisk("<?php echo URL::to('job_file'); ?>/"+result,result);
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
        alert("Please Choose atleast one Job.");
      }
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
  if($(e.target).hasClass('search_button')) {
    $("#job_oftheday").dataTable().fnDestroy();

    var client_id = $("#client_search").val();
    var tasks = $("#select_tasks").val();
    var tasksval = tasks.join(',');

    var users = $("#select_users").val();
    var usersval = users.join(',');
    var start_date = $("#start_date").val();
    var stop_date = $("#stop_date").val();

    if(client_id == "" || typeof client_id === "undefined" || tasks == "" || typeof tasks === "undefined" || users == "" || typeof users === "undefined" || start_date == "" || typeof start_date === "undefined" || stop_date == "" || typeof stop_date === "undefined")
    {
      alert("Please Enter all the mandatory fields to search.")
    }
    else{
        $("body").addClass("loading");
        $.ajax({
          url:"<?php echo URL::to('user/search_client_review')?>",
          data:{client_id:client_id,tasks:tasksval,users:usersval,start_date:start_date,stop_date:stop_date},
          success:function (result){
            
            $("#tbody_job_oftheday").html(result);
            $(".table_selectall").show();
            $(".filter_text").hide();
            $("#job_oftheday").removeClass("no-footer");
            $(".class_report_csv").addClass("report_csv");
            $(".class_report_pdf").addClass("report_pdf");
            $()
            $('#job_oftheday').DataTable({
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
    }
    $("body").removeClass("loading");
  }
});
</script>

@stop
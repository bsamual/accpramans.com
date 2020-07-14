@extends('userheader')
@section('content')
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/jquery.dataTables.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/fixedHeader.dataTables.min.css'); ?>">

<script src="<?php echo URL::to('assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo URL::to('assets/js/dataTables.fixedHeader.min.js'); ?>"></script>

<script src="<?php echo URL::to('assets/js/jquery.form.js'); ?>"></script>


<link rel="stylesheet" href="<?php echo URL::to('assets/js/lightbox/colorbox.css'); ?>">
<script src="<?php echo URL::to('assets/js/lightbox/jquery.colorbox.js'); ?>"></script>

<script src='<?php echo URL::to('assets/js/table-fixed-header_cm.js')?>'></script>
<style>

body{
  background: #f5f5f5 !important;
}
.fa-sort{
  cursor:pointer;
}
.show_incomplete_label{
  cursor:pointer;
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

.form-title{width: 100%; height: auto; float: left; margin-bottom: 5px;}


.table tr td, tr th{font-size: 15px;}


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

.blinking{
    animation:blinkingText 0.8s infinite;
}
@keyframes blinkingText{
    0%{     background: #f00;    }
    49%{    background: #000; }
    50%{    background: #f00; }
    99%{    background:#000;  }
    100%{   background: #f00;    }
}
</style>

<div class="modal fade crypt_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document" style="width:35%;margin-top:15%">
      <div class="modal-content">
        <form method="post" action="<?php echo URL::to('user/year_setting_copy_to_year')?>"> 
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">CRYPT PIN</h4>
          </div>
          <div class="modal-body">
            <div class="crypt_groyp">
              <div class="form-group" style="width:80%">
                  <div class="form-title">Enter CRYPT PIN</div>
                  <input type="password" class="form-control crypt_pin_setting" value="" placeholder="Enter CRYPT Pin" name="crypt" required>
                  <label class="error crypt_error" style="display:none"></label>
              </div>
            </div>
            <input type="hidden" value="<?php echo base64_encode($yearid)?>" name="yearid">            
            
          </div>
          <div class="modal-footer">
              <input type="submit" class="common_black_button" value="Submit">              
          </div>
        </form>
      </div>
  </div>
</div>

<div class="modal fade" id="review_clients_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <form method="post" action="<?php echo URL::to('user/review_clients_update')?>"> 
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Review Clients</h4>
          </div>
          <div class="modal-body review_clients_tbody" id="review_clients_tbody">

          </div>
        </form>
      </div>
  </div>
</div>







<div class="content_section" style="margin-bottom:200px">
  <div class="page_title" style="margin-bottom: 2px;">
          <div class="col-lg-3" style="padding: 0px;">
              <?php echo $yearid; ?> YEAR END MANAGER
            </div>
            <div class="col-lg-9 text-right"  style="padding: 0px;" >
              <div class="select_button" style=" margin-left: 10px;">
                <ul style="float: right;">            
                         
                  <li><input type="checkbox" name="show_incomplete" id="show_incomplete" value="1" class="show_incomplete_label"><label for="show_incomplete" class="show_incomplete_label">Hide / Unhide Completed Clients</label> </li>     
                  <li><a href="<?php echo URL::to('user/year_end_manager'); ?>" style="font-size: 13px; font-weight: 500;">Back to Select Year Screen</a></li>
                  <li><a href="javascript:" class="export_to_csv common_black_button" style="font-size: 13px; font-weight: 500;">Export to CSV</a></li>
                  <?php
                  $count_setting = DB::table('year_setting')->count();
                  $year = DB::table('year_end_year')->where('year', $yearid)->first();

                  $count_year_setting = count(explode(',',$year->setting_id));
                  if($count_setting != $count_year_setting){
                    $reviewbutton = '<li><a href="javascript:" class="update_documents" style="font-size: 13px; font-weight: 500;">Review Clients</a></li>';
                  }
                  elseif($year->setting_id == ''){
                    $reviewbutton = '<li><a href="javascript:" class="update_documents" style="font-size: 13px; font-weight: 500;">New Year End Docs</a></li>';
                  }
                  else{
                    $reviewbutton = '<li><a href="javascript:" class="review_clients" style="font-size: 13px; font-weight: 500;">Review Clients</a></li>';
                  }
                  echo $reviewbutton;
                  ?>
                  
                  <li><a href="javascript:" class="create_new_year" style="font-size: 13px; font-weight: 500;">Create New Year</a></li>

                  <?php
                  if($count_setting == ''){
                    $button = '<li><a class="no_setting" href="javascript:" style="font-size: 13px; font-weight: 500;">New Year End Docs</a></li>';
                  }
                  elseif($count_setting != $count_year_setting){
                    $button = '<li><a href="javascript:" class="blinking new_year_end_class" style="font-size: 13px; font-weight: 500;">New Year End Docs</a></li>';
                  }
                  elseif($year->setting_id == ''){
                    $button = '<li><a href="javascript:" class="blinking new_year_end_class" style="font-size: 13px; font-weight: 500;">New Year End Docs</a></li>';
                  }
                  else{
                    $button = '<li><a class="setting_same" href="javascript:" style="font-size: 13px; font-weight: 500;">New Year End Docs</a></li>';
                  }
                  echo $button;



                  ?>


                  
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

<div class="table-responsive">
  <table class="table table_bg table-fixed-header">
    <thead>
      <tr class="background_bg">
        <th width="100px" style="text-align:left"><i class="fa fa-sort sort_sno"></i> S.No</th>
        <th width="150px" style="text-align:left"><i class="fa fa-sort sort_clientid"></i>Client Id</th>
        <th style="text-align:left"><i class="fa fa-sort sort_firstname"></i> First Name</th>
        <th style="text-align:left"><i class="fa fa-sort sort_lastname"></i> Last Name</th>
        <th style="text-align:left"><i class="fa fa-sort sort_company"></i> Company</th>
        <th style="text-align:left"><i class="fa fa-sort sort_status"></i> Status </th>
      </tr>   
    </thead>
    <tbody id="task_body">    
          <?php
          $output='';
          $i=1;
          if(count($clientslist)){
            foreach ($clientslist as $client) {
              $client_details = DB::table('cm_clients')->where('client_id', $client->client_id)->first();
              if(count($client_details)){
                $clientid = $client_details->client_id;
                $firstname = $client_details->firstname;
                $lastname = $client_details->surname;
                $company = $client_details->company;
              }
              else{
                $clientid = '';
                $firstname = '';
                $lastname = '';
                $company = '';
              }
              if($client->status == 0)
              {
                $color = 'color:#f00 !important;';
              }
              elseif($client->status == 1)
              {
                $color = 'color:#f7a001 !important;';
              }
              elseif($client->status == 2)
              {
                $color = 'color:#0000fb !important;';
              }
              else{
                $color = 'color:#f00 !important;';
              }
              if($i < 10)
              {
                $i = '0'.$i;
              }
              
              if($client->status == 0) { if($client_details->active == "2") { $stausval = 'Inactive & Not Started'; } else { $stausval = 'Not Started'; } }
              elseif($client->status == 1) { $stausval = 'Inprogress'; }
              elseif($client->status == 2) { $stausval = 'Completed'; }
              $output.='
              <tr class="task_tr client_'.$client->status.'">
                <td class="sno_sort_val" style="'.$color.'text-align:left;font-weight:600">'.$i.'</td>
                <td class="clientid_sort_val" style="'.$color.'text-align:left;font-weight:600"><a style="'.$color.'" href="'.URL::to('user/yearend_individualclient/'.base64_encode($client->id)).'">'.$clientid.'</a></td>
                <td class="firstname_sort_val" style="'.$color.'text-align:left;font-weight:600"><a style="'.$color.'" href="'.URL::to('user/yearend_individualclient/'.base64_encode($client->id)).'">'.$firstname.'</a></td>
                <td class="lastname_sort_val" style="'.$color.'text-align:left;font-weight:600"><a style="'.$color.'" href="'.URL::to('user/yearend_individualclient/'.base64_encode($client->id)).'">'.$lastname.'</a></td>
                <td class="company_sort_val" style="'.$color.'text-align:left;font-weight:600"><a style="'.$color.'" href="'.URL::to('user/yearend_individualclient/'.base64_encode($client->id)).'">'.$company.'</a></td>
                <td class="status_sort_val" style="'.$color.'text-align:left;font-weight:600">'.$stausval.'</td>
              </tr>';
              $i++;
            }
          }
          else{
            $output.='
              <tr>
                <td colspan="3" align="center">Empty</td>
              </tr>
            ';
          }
          echo $output;
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

<input type="hidden" name="sno_sortoptions" id="sno_sortoptions" value="asc">
<input type="hidden" name="clientid_sortoptions" id="clientid_sortoptions" value="asc">
<input type="hidden" name="firstname_sortoptions" id="firstname_sortoptions" value="asc">
<input type="hidden" name="lastname_sortoptions" id="lastname_sortoptions" value="asc">
<input type="hidden" name="company_sortoptions" id="company_sortoptions" value="asc">
<input type="hidden" name="status_sortoptions" id="status_sortoptions" value="asc">

<input type="hidden" name="review_clientid_sortoptions" id="review_clientid_sortoptions" value="asc">
<input type="hidden" name="review_company_sortoptions" id="review_company_sortoptions" value="asc">


<script>
var convertToNumber = function(value){
       return value.toLowerCase();
}
var convertToNumber_int = function(value){
       return parseInt(value);
}
$(window).click(function(e) {
var ascending = false;
if($(e.target).hasClass('sort_sno'))
{
  var sort = $("#sno_sortoptions").val();
  if(sort == 'asc')
  {
    $("#sno_sortoptions").val('desc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber_int($(a).find('.sno_sort_val').html()) <
      convertToNumber_int($(b).find('.sno_sort_val').html()))) ? 1 : -1;
    });
  }
  else{
    $("#sno_sortoptions").val('asc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber_int($(a).find('.sno_sort_val').html()) <
      convertToNumber_int($(b).find('.sno_sort_val').html()))) ? -1 : 1;
    });
  }
  ascending = ascending ? false : true;
  $('#task_body').html(sorted);
}
if($(e.target).hasClass('sort_clientid'))
{
  var sort = $("#clientid_sortoptions").val();
  if(sort == 'asc')
  {
    $("#clientid_sortoptions").val('desc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.clientid_sort_val').find('a').html()) <
      convertToNumber($(b).find('.clientid_sort_val').find('a').html()))) ? 1 : -1;
    });
  }
  else{
    $("#clientid_sortoptions").val('asc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.clientid_sort_val').find('a').html()) <
      convertToNumber($(b).find('.clientid_sort_val').find('a').html()))) ? -1 : 1;
    });
  }
  ascending = ascending ? false : true;
  $('#task_body').html(sorted);
}

if($(e.target).hasClass('review_sort_clientid'))
{
  var sort = $("#review_clientid_sortoptions").val();
  if(sort == 'asc')
  {
    $("#review_clientid_sortoptions").val('desc');
    var sorted = $('#review_task_body').find('.review_task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.review_clientid_sort_val').find('a').html()) <
      convertToNumber($(b).find('.review_clientid_sort_val').find('a').html()))) ? 1 : -1;
    });
  }
  else{
    $("#review_clientid_sortoptions").val('asc');
    var sorted = $('#review_task_body').find('.review_task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.review_clientid_sort_val').find('a').html()) <
      convertToNumber($(b).find('.review_clientid_sort_val').find('a').html()))) ? -1 : 1;
    });
  }
  ascending = ascending ? false : true;
  $('#review_task_body').html(sorted);
}

if($(e.target).hasClass('sort_firstname'))
{
  var sort = $("#firstname_sortoptions").val();
  if(sort == 'asc')
  {
    $("#firstname_sortoptions").val('desc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.firstname_sort_val').find('a').html()) <
      convertToNumber($(b).find('.firstname_sort_val').find('a').html()))) ? 1 : -1;
    });
  }
  else{
    $("#firstname_sortoptions").val('asc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.firstname_sort_val').find('a').html()) <
      convertToNumber($(b).find('.firstname_sort_val').find('a').html()))) ? -1 : 1;
    });
  }
  ascending = ascending ? false : true;
  $('#task_body').html(sorted);
}
if($(e.target).hasClass('sort_lastname'))
{
  var sort = $("#lastname_sortoptions").val();
  if(sort == 'asc')
  {
    $("#lastname_sortoptions").val('desc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.lastname_sort_val').find('a').html()) <
      convertToNumber($(b).find('.lastname_sort_val').find('a').html()))) ? 1 : -1;
    });
  }
  else{
    $("#lastname_sortoptions").val('asc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.lastname_sort_val').find('a').html()) <
      convertToNumber($(b).find('.lastname_sort_val').find('a').html()))) ? -1 : 1;
    });
  }
  ascending = ascending ? false : true;
  $('#task_body').html(sorted);
}
if($(e.target).hasClass('sort_company'))
{
  var sort = $("#company_sortoptions").val();
  if(sort == 'asc')
  {
    $("#company_sortoptions").val('desc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.company_sort_val').find('a').html()) <
      convertToNumber($(b).find('.company_sort_val').find('a').html()))) ? 1 : -1;
    });
  }
  else{
    $("#company_sortoptions").val('asc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.company_sort_val').find('a').html()) <
      convertToNumber($(b).find('.company_sort_val').find('a').html()))) ? -1 : 1;
    });
  }
  ascending = ascending ? false : true;
  $('#task_body').html(sorted);
}

if($(e.target).hasClass('review_sort_company'))
{
  var sort = $("#review_company_sortoptions").val();
  if(sort == 'asc')
  {
    $("#review_company_sortoptions").val('desc');
    var sorted = $('#review_task_body').find('.review_task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.review_company_sort_val').find('a').html()) <
      convertToNumber($(b).find('.review_company_sort_val').find('a').html()))) ? 1 : -1;
    });
  }
  else{
    $("#review_company_sortoptions").val('asc');
    var sorted = $('#review_task_body').find('.review_task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.review_company_sort_val').find('a').html()) <
      convertToNumber($(b).find('.review_company_sort_val').find('a').html()))) ? -1 : 1;
    });
  }
  ascending = ascending ? false : true;
  $('#review_task_body').html(sorted);
}

if($(e.target).hasClass('sort_status'))
{
  var sort = $("#status_sortoptions").val();
  if(sort == 'asc')
  {
    $("#status_sortoptions").val('desc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.status_sort_val').html()) <
      convertToNumber($(b).find('.status_sort_val').html()))) ? 1 : -1;
    });
  }
  else{
    $("#status_sortoptions").val('asc');
    var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.status_sort_val').html()) <
      convertToNumber($(b).find('.status_sort_val').html()))) ? -1 : 1;
    });
  }
  ascending = ascending ? false : true;
  $('#task_body').html(sorted);
}
if($(e.target).hasClass('export_to_csv'))
{
  $("body").addClass("loading");
  setTimeout(function() {
    $.ajax({
      url:"<?php echo URL::to('user/yearend_export_to_csv'); ?>",
      type:"post",
      data:{year:"<?php echo $yearid; ?>"},
      success:function(result)
      {
        SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);
        $("body").removeClass("loading");
      }
    })
  },1000);
}
if($(e.target).hasClass('update_documents'))
{
  alert('New Clients cannot be added when "New Year End Docs" is blinking. Apply any new docs to the existing clients first and then add new clients');
}
if(e.target.id == 'show_incomplete')
{
  if($(e.target).is(":checked"))
  {
    $(".client_2").hide();
  }
  else{
    $(".client_2").show();
  }
}
if($(e.target).hasClass("submit_review_clients"))
{
  var count = $(".review_clients_checkbox:checked").length;
  if(count < 1)
  {
    alert("You Should select atleast one client to proceed with Review Clients.");
    return false;
  }
}
if(e.target.id =="hide_deactivate_clients")
{
  if($(e.target).is(":checked"))
  {
    $(".hidden_tr").hide();
  }
  else{
    $(".hidden_tr").show();
  }
}
if($(e.target).hasClass("select_all_clients"))
{
  if($(e.target).is(":checked"))
  {
    $(".review_clients_checkbox").prop("checked",true);
  }
  else{
    $(".review_clients_checkbox").prop("checked",false);
  }
}
if($(e.target).hasClass("hide_clients"))
{
  if($(e.target).is(":checked"))
  {
    $(".review_clients_checkbox").prop("checked",true);
  }
  else{
    $(".review_clients_checkbox").prop("checked",false);
  }
}

if($(e.target).hasClass('create_new_year'))
{
  var r=confirm("Are you sure you want to create a new year?");
  if(r)
  {
    window.location.replace("<?php echo URL::to('user/yearend_create_new_year'); ?>");
  }
}
if($(e.target).hasClass('review_clients'))
{
  var yearid = "<?php echo $yearid; ?>";
  $.ajax({
    url:"<?php echo URL::to('user/review_get_clients'); ?>",
    type:"get",
    data:{yearid:yearid},
    success:function(result)
    {
      $("#review_clients_modal").modal("show");
      $("#review_clients_tbody").html(result);
    }
  })
}

if($(e.target).hasClass('add_new')){
    $(".add_modal").modal("show");
    $(".year_input_group").hide();
    $(".year_drop").prop('required', false);
    $(".crypt_pin").prop('required', true);
    $(".crypt_pin").prop('disabled', false);
    $(".crypt_pin").val('');
    $(".crypt_error").hide();
}
if($(e.target).hasClass('crypt_button')){
  var crypt = $(".crypt_pin").val();
  if(crypt == "" || typeof crypt === "undefined")
  {
    alert("Please Enter CRYPT PIN");
    return false;
  }
  else{
    $.ajax({
      url:"<?php echo URL::to('user/yearend_crypt_validdation')?>",
      type:"post",
      dataType:"json",
      data:{crypt:crypt,type:0},
      success:function(result){
        if(result['security'] == true){
          $(".crypt_error_first").css({"display":"block","color":"green"});
          $(".crypt_error_first").html('CRYPT Pin validation success');
          $(".crypt_groyp").show();
          $(".year_input_group").show();
          $(".year_drop").html(result['drop']);
          $(".crypt_button").hide();
          $(".year_button").show();
          $(".year_class").prop('required', true);
          $(".crypt_pin").prop('required', false);
          $(".crypt_pin").prop('disabled', true);
          $(".button_submit").html(result['create_button']);
        }
        else{
          $(".crypt_error_first").css({"display":"block","color":"red"});
          $(".crypt_error_first").html('CRYPT Pin is incorrect');
          $(".crypt_groyp").show();
          $(".year_input_group").hide();
          $(".crypt_button").show();
          $(".year_button").hide();
        }

      }
    })
  } 
}



if($(e.target).hasClass('setting_class')){
    $(".setting_crypt_modal").modal("show");
    $(".crypt_pin_setting").prop('required', true);
    $(".crypt_pin_setting").prop('disabled', false);
    $(".crypt_pin_setting").val('');
    $(".crypt_error").hide();    
}


if($(e.target).hasClass('setting_button')){
  var setting_type = $(".setting_type").val();
  if(setting_type == "" || typeof setting_type === "undefined")
  {
    alert("Please select type");
    return false;
  }
}



if($(e.target).hasClass('year_button')){
  var year_class = $(".year_class").val();

  if(year_class == "" || typeof year_class === "undefined")
  {
    alert("Please select year");
    return false;
  }
  else{
    var r = confirm("Warning, once you create this year no year prior to this can be created.  Do you wish to Proceed with Creating the year?");
    if (r == true) {      

    } else {
        return false;
    }
  }
}


if($(e.target).hasClass('setting_type')){
  var level = $(e.target).val();  
  if (level == 1) {     
    $(".setting_button").attr("href", "<?php echo URL::to('user/supplementary_manager')?>");
  }

  else if (level == 2) {     
    $(".setting_button").attr("href", "<?php echo URL::to('user/yearend_setting')?>");
  }
}

if($(e.target).hasClass('no_setting')){
  alert("It looks like there are NO Documents created yet in the settings screen. Please create atleast one document type and then run this New Year End doc.");
  return false;
}

if($(e.target).hasClass('setting_same')){
  alert("It looks like NO new Documents were created in the settings screen since this Year has been created. Please create a new document type and then run this New Year End doc.");
  return false;
}

if($(e.target).hasClass('new_year_end_class')){
    $(".crypt_modal").modal("show");    
    $(".crypt_pin_setting").val('');
}




});

$(".add_modal").keypress(function(e) {
  if (e.which == 13 && !$(e.target).is("textarea")) {
    return false;
  }
});

$(".setting_crypt_modal").keypress(function(e) {
  if (e.which == 13 && !$(e.target).is("textarea")) {
    return false;
  }
});







</script>

<script>

$(document).ready(function() {

  $('.table-fixed-header').fixedHeader();  

});

</script>






@stop
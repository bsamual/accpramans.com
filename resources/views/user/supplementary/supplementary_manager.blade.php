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

.form-title{width: 100%; height: auto; float: left; margin-bottom: 5px;}

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
  <div class="modal-dialog modal-sm" style="width: 30%" role="document">
      <div class="modal-content">
        <form action="<?php echo URL::to('user/supplementary_add')?>" method="post" class="add_new_form" id="form-validation"> 
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Create Supplementary Note</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
                <div class="form-title">Number:</div>
                <input type="number" class="form-control number_class" value="" placeholder="Enter Number" name="number" required>
            </div>
            <div class="form-group">
                <div class="form-title">Enter Supplementary Note Name:</div>
                <input type="text" class="form-control name_class" value="" placeholder="Enter Name" name="name" required>
            </div>
            <div class="form-group">
                <div class="form-title">Enter Description for Supplementary Note:</div>
                <textarea class="form-control description_class" placeholder="Enter Description" name="description" style="height: 250px" required></textarea>
            </div>
          </div>
          <div class="modal-footer">
              <input type="submit" class="common_black_button" id="add_task" value="Create Supplementary Note">            
          </div>
        </form>        
      </div>
  </div>
</div>





<div class="content_section" style="margin-bottom:200px">
  <div class="page_title">
        <h4 class="col-lg-3" style="padding: 0px;">
                Supplementary Note Manager              
            </h4>
            <div class="col-lg-6 text-right" style="padding-right: 0px; line-height: 35px;">
                
            </div>
            <div class="col-lg-3 text-right"  style="padding: 0px;" >
              <div class="select_button" style=" margin-left: 10px;">
                <ul style="float: right">                
                <li><a href="javascript:" style="font-size: 13px; font-weight: 500;" class="add_new">Create New</a></li>               
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

<table class="display nowrap fullviewtablelist" id="supply_expand" width="100%">
                        <thead>
                        <tr style="background: #fff;">
                            <th width="2%" style="text-align: left;">S.No</th>
                            <th style="text-align: left;">Name</th>
                            <th style="text-align: left;">Description</th>                            
                            <th width="20%" style="text-align: center;">Action</th>                           
                        </tr>
                        </thead>                            
                        <tbody>
                          <?php
                          $ouput='';
                          if(count($supplelist)){
                            foreach ($supplelist as $supple) {
                              $ouput.='
                              <tr>
                                <td>'.$supple->number.'</td>
                                <td>'.$supple->name.'</td>
                                <td>'.str_limit($supple->description, 100).'</td>
                                <td align="center"><a href="'.URL::to('user/supplementary_note_create/'.base64_encode($supple->id)).'">Edit</a></td>
                              </tr>
                              ';
                            }
                          }
                          echo $ouput;
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

$(window).click(function(e) {

if($(e.target).hasClass('add_new')){
    $(".add_modal").modal("show");
    $(".number_class").val('');
    $(".name_class").val('');
    $(".description_class").val('');
}





});

$.ajaxSetup({async:false});
$('#form-validation').validate({
    rules: {
        name : {required: true,},
        description : {required: true,},
        number : {required: true,remote:"<?php echo URL::to('user/supple_number_check')?>"},
        
    },
    messages: {
        name : "Client Name is Required",
        description : "Description is Required",
        number : {
          required : "Number is Required",
          remote : "Number is already exists",
        },
    },
});



</script>

<script>
$(function(){
    $('#supply_expand').DataTable({
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
@extends('userheader')
@section('content')
<style>
    
    .table thead th:focus{background: #ddd !important;}
    .form-control{border-radius: 0px;}
</style>
<script>
function popitup(url) {
    newwindow=window.open(url,'name','height=600,width=1500');
    if (window.focus) {newwindow.focus()}
    return false;
}

</script>

    

<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="z-index: 99999">
  <div class="modal-dialog modal-sm" role="document">
    <form id="form-validation" action="<?php echo URL::to('user/add_rctclients'); ?>" method="post" class="addsp">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add Clients</h4>
          </div>
          <div class="modal-body">
          <label>Enter Client Name : </label>
            <div class="form-group">                
                <input class="form-control"
                       name="name"
                       placeholder="Enter Client Name"
                       type="text"
                       required>
            </div>
            <label>Enter Salutation : </label>
            <div class="form-group">                
                <input class="form-control"
                       name="lname"
                       placeholder="Enter Salutation"
                       type="text"
                       required>
            </div>
            <label>Enter Tax Number : </label>
            <div class="form-group">                
                <input class="form-control"
                       name="taxnumber"
                       id="idtax"
                       placeholder="Enter Tax Number"
                       type="text"
                       required>
            </div>
            <label>Enter Email Id : </label>
            <div class="form-group email_group">                
                <input class="form-control"
                       name="email"
                       id="idemail" 
                       placeholder="Enter Email ID"
                       type="text"
                       required>
            </div>
            <label>Enter Secondary Email Id : </label>
            <div class="form-group">                
                <input class="form-control"
                       name="secondaryemail"
                       type="email"
                       placeholder="Enter Secondary Email">
            </div>
            
          </div>
          <div class="modal-footer">
            <input type="submit" id="formvalid_id" value="Submit" class="common_black_button">
          </div>
        </div>
    </form>

    <form id="form-validation-edit" action="<?php echo URL::to('user/update_rctclients'); ?>" method="post" class="editsp">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Edit Clients</h4>
          </div>
          <div class="modal-body">
            <label>Enter Client Name : </label>
            <div class="form-group">                
                <input class="form-control name_class"
                       name="name"
                       placeholder="Enter Client Name"
                       type="text"
                       required>
            </div>
            <label>Enter Salutation : </label>
            <div class="form-group">                
                <input class="form-control lname_class"
                       name="lname"
                       placeholder="Enter Salutation"
                       type="text"
                       required>
            </div>
            <label>Enter Tax Number : </label>
            <div class="form-group">                
                <input class="form-control taxnumber_class"
                       name="taxnumber"
                       placeholder="Enter Tax Number"
                       id="idtax_edit"
                       type="text"
                       required>
            </div>
            <label>Enter Email Id : </label>
            <div class="form-group">                
                <input class="form-control email_class"
                       name="email"
                       placeholder="Enter Email ID"
                       type="text"
                       required>
            </div>
            <label>Enter Secondary Email Id : </label>
            <div class="form-group">                
                <input class="form-control second_class"
                       name="secondaryemail"
                       placeholder="Enter Secondary Email"
                       type="email">
                <input type="hidden" name="id" class="form-control name_id">
            </div>
          </div>
          <div class="modal-footer">
            <input type="submit" value="Update" class="common_black_button">
          </div>
        </div>
    </form>

  </div>
</div>

<div class="content_section" style="margin-bottom:200px">
  <div class="page_title">
    
        <h4 class="col-lg-4" style="padding: 0px;">
                GBS & Co RCT Tracker                
            </h4>
            <div class="col-lg-2 text-right" style="padding-right: 0px;">
                <input type="text" name="" placeholder="Search Client Name" class="form-control client_search_class">
                <input type="hidden" id="client_search" />
            </div>
            <div class="col-lg-2 text-right" style="padding-right: 0px;">
              <input type="text" name="" placeholder="Search Tax Number" class="form-control tax_search_class" >
              <input type="hidden" id="tax_search" />
            </div>
            <div class="col-lg-2 text-right" style="padding-right: 0px;">
              <input type="text" name="" placeholder="Search Email ID" class="form-control email_search_class">
              <input type="hidden" id="email_search" />
            </div>
            <div class="col-lg-2 text-right"  style="padding: 0px;" >
              <div class="select_button" style=" margin-left: 10px;">
                <ul>
                <li><a href="" style="font-size: 13px; font-weight: 500;">Reset</a></li>
                <li><a href="" class="addclientbutton" style="font-size: 13px; font-weight: 500;" data-toggle="modal" data-target=".bs-example-modal-sm">Add Client</a></li>
              </ul>
            </div>
  </div>
  <div class="table-responsive" style="max-width: 100%; float: left;margin-bottom:30px; margin-top:55px">
  </div>
  <div style="clear: both;">
   <?php
    if(Session::has('message')) { ?>
        <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
    <?php }
    ?>
    </div> 
<table class="table table-hover nowrap" id="client_expand" width="100%">
                        <thead class="table-inverse">
                        <tr style="background: #fff;">
                             <th width="5%" style="text-align: center;">S.No</th>
                            <th style="text-align: center;">Client Name</th>
                            <th style="text-align: center;">Tax Number</th>
                            <th style="text-align: center;">Email</th>
                                <th style="text-align: center;">Secondary Email</th>
                            <th style="text-align: center;">RCT Count</th>
                            <th style="text-align: center;">Invoiced</th>
                            <th style="text-align: center;">Emailed</th>
                            <th style="text-align: center;">Action</th>
                        </tr>
                        </thead>                            
                        <tbody id="clients_tbody">
                        <?php
                            $i=1;
                            if(count($userlist)){              
                              foreach($userlist as $user){
                                if($user->status == 0){
                          ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td align="left"><?php echo $user->firstname; ?></td>
                            <td align="left"><?php echo $user->taxnumber; ?></td>
                            <td align="left"><?php echo $user->email; ?></td>
                            <td align="left"><?php echo $user->secondary_email; ?></td>
                            <td align="center">
                              <?php
                              $count = DB::table('rct_tracker')->where('client_id', $user->client_id)->count();
                              echo $count;
                              ?>
                            </td>
                            <td align="center">
                              <?php
                              $countivoice = DB::table('rct_tracker')->where('client_id', $user->client_id)->where('invoice','!=', '')->count();
                              echo $countivoice;
                              ?>
                            </td>
                            <td align="center">
                              <?php
                              $countemailed = DB::table('rct_tracker')->where('client_id', $user->client_id)->where('email','!=', '0000-00-00 00:00:00')->count();
                              echo $countemailed;
                              ?>
                            </td>
                            <td align="center">
                                <a href="<?php echo URL::to('user/expand_rctclient/'.base64_encode($user->client_id))?>" title="View RCT Items"><i class="fa fa fa-plus" aria-hidden="true"></i></a>&nbsp;&nbsp;
                                <a href="#" id="<?php echo base64_encode($user->client_id); ?>" class="editclass" title="Edit Client"><i class="fa fa-pencil-square editclass" id="<?php echo base64_encode($user->client_id); ?>" aria-hidden="true"></i></a>&nbsp; &nbsp;
                                <a href="<?php echo URL::to('user/rctclient_hidden/'.base64_encode($user->client_id))?>" title="Hide Client"><i class="fa fa-eye-slash hidden_user" aria-hidden="true"></i></a>                                    
                            </td>
                        </tr>
                        <?php
                              $i++;
                                }
                              }              
                            }
                            if($i == 1)
                            {
                              echo'<tr><td align="center"></td>
                              <td align="center"></td>
                              <td align="center"></td>
                              <td align="center"></td>
                              <td align="center">Empty</td>
                              <td align="center"></td>
                              <td align="center"></td>
                              <td align="center"></td>
                              <td align="center"></td>
                              </tr>';
                            }
                          ?> 

                        </tbody>
                    </table>

</div>
    <section class="panel">
        <div class="panel-heading">
            


        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">  
                                  
                    
                    
                </div>
            </div>            
        </div>
    </section>
    <!-- End  -->
<div class="main-backdrop"><!-- --></div>

<script>
$(window).click(function(e) {
  if($(e.target).hasClass('addclientbutton')) {
    $(".editsp").hide();
    $(".addsp").show();
  }
  if($(e.target).hasClass('editclass')) {
    var editid = $(e.target).attr("id");
    $('#form-validation-edit').validate({
        rules: {
            name : {required: true,},
            lname : {required: true,},
            taxnumber : {required: true,
                          remote: { 
                            url : "<?php echo URL::to('user/rctclient_checktax'); ?>",
                            type:"get",
                            data : { id: atob(editid)},
                          }, },
            email : {required: true,email:true,
                          remote: { 
                            url : "<?php echo URL::to('user/rctclient_checkemail'); ?>",
                            type:"get",
                            data : { id: atob(editid)},
                          }, },

        },
        messages: {
            name : "Client Name is Required",
            lname : "Salutation is Required",
            taxnumber : {
              required : "Tax Number is Required",
              remote : "Tax Number is already exists",
            },
            email : {
              required : "Email Id is Required",
              email : "Please Enter a valid Email Address",
              remote : "Email Id is already exists",
            },
        },
    });
    
    $.ajax({
        url: "<?php echo URL::to('user/edit_rctclients') ?>"+"/"+editid,
        dataType:"json",
        type:"post",
        success:function(result){
           $(".bs-example-modal-sm").modal("toggle");
           $(".editsp").show();
           $(".addsp").hide();
           $(".name_class").val(result['name']);
           $(".lname_class").val(result['lname']);
           $(".taxnumber_class").val(result['taxnumber']);
           $(".email_class").val(result['email']);
           $(".second_class").val(result['secondaryemail']);
           $(".name_id").val(result['id']);
      }
    });
  }
});
$.ajaxSetup({async:false});
$('#form-validation').validate({
    rules: {
        name : {required: true,},
        lname : {required: true,},
        taxnumber : {required: true,remote:"<?php echo URL::to('user/rctclient_checktax'); ?>"},
        email : {required: true,email:true,remote:"<?php echo URL::to('user/rctclient_checkemail'); ?>"},
    },
    messages: {
        name : "Client Name is Required",
        lname : "Salutation is Required",
        taxnumber : {
          required : "Tax Number is Required",
          remote : "Tax Number is already exists",
        },
        email : {
          required : "Email Id is Required",
          email : "Please Enter a valid Email Address",
          remote : "Email Id is already exists",
        },
    },
});

$('#idtax').keypress(function (e) {
    var value = $(this).val();
    if ($.trim(value).length > 0) {
      $(this).val($.trim(value));
    }
});
$('#idtax').change(function (e) {
    var value = $(this).val();
    if ($.trim(value).length > 0) {
      $(this).val($.trim(value));
    }
});
$('#idtax').keyup(function (e) {
    var value = $(this).val();
    if ($.trim(value).length > 0) {
      $(this).val($.trim(value));
    }
});
$('#idtax').keydown(function (e) {
    var value = $(this).val();
    if ($.trim(value).length > 0) {
      $(this).val($.trim(value));
    }
});

$('#idtax_edit').keypress(function (e) {
    var value = $(this).val();
    if ($.trim(value).length > 0) {
      $(this).val($.trim(value));
    }
});
$('#idtax_edit').change(function (e) {
    var value = $(this).val();
    if ($.trim(value).length > 0) {
      $(this).val($.trim(value));
    }
});
$('#idtax_edit').keyup(function (e) {
    var value = $(this).val();
    if ($.trim(value).length > 0) {
      $(this).val($.trim(value));
    }
});
$('#idtax_edit').keydown(function (e) {
    var value = $(this).val();
    if ($.trim(value).length > 0) {
      $(this).val($.trim(value));
    }
});
</script>

<!-- Page Scripts -->
<script>
$(function(){
    $('#client_expand').DataTable({
        autoWidth: true,
        scrollX: true,
        fixedColumns: true,
        searching: false
    });
});
</script>



<script>
$(window).click(function(e) {
  var ascending = false;
  
  if($(e.target).hasClass('hidden_user'))
  {
    var r = confirm("Are You Sure want to Hide this Clients?");
    if (r == true) {
       
    } else {
        return false;
    }
  }
});
</script>


<script>
$(document).ready(function() {    
     $(".client_search_class").autocomplete({
        source: function(request, response) {
            $.ajax({
                url:"<?php echo URL::to('user/rctclient_search'); ?>",
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
            url:"<?php echo URL::to('user/rctclient_search_select'); ?>",
            data:{value:ui.item.value},
            success: function(result){
              $("#clients_tbody").html(result);
              $("#client_expand_paginate").hide();
              $(".dataTables_info").hide();
            }
          })
        }
    });
    $(".tax_search_class").autocomplete({
        source: function(request, response) {
            $.ajax({
                url:"<?php echo URL::to('user/rctclient_tax_search'); ?>",
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
          $("#tax_search").val(ui.item.id);
          $.ajax({
            url:"<?php echo URL::to('user/rctclient_tax_search_select'); ?>",
            data:{value:ui.item.value},
            success: function(result){
              $("#clients_tbody").html(result);
              $("#client_expand_paginate").hide();
              $(".dataTables_info").hide();
            }
          })
        }
    });
    $(".email_search_class").autocomplete({
        source: function(request, response) {
            $.ajax({
                url:"<?php echo URL::to('user/rctclient_email_search'); ?>",
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
          $("#email_search").val(ui.item.id);
          $.ajax({
            url:"<?php echo URL::to('user/rctclient_email_search_select'); ?>",
            data:{value:ui.item.value},
            success: function(result){
              $("#clients_tbody").html(result);
              $("#client_expand_paginate").hide();
              $(".dataTables_info").hide();
            }
          })
        }
    });
});
</script>
@stop
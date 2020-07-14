@extends('adminheader')
@section('content')
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <form id="form-validation" action="<?php echo URL::to('admin/add_rctclients'); ?>" method="post" class="addsp">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add Clients</h4>
          </div>
          <div class="modal-body">

            <div class="form-group">                
                <input class="form-control"
                       name="name"
                       placeholder="Enter Client Name"
                       type="text"
                       required>
            </div>
            <div class="form-group">                
                <input class="form-control"
                       name="lname"
                       placeholder="Enter Salutation"
                       type="text"
                       required>
            </div>
            <div class="form-group">                
                <input class="form-control"
                       name="taxnumber"
                       id="idtax"
                       placeholder="Enter Tax Number"
                       type="text"
                       required>
            </div>
            <div class="form-group email_group">                
                <input class="form-control"
                       name="email"
                       id="idemail" 
                       placeholder="Enter Email ID"
                       type="text"
                       required>
            </div>
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

    <form id="form-validation-edit" action="<?php echo URL::to('admin/update_rctclients'); ?>" method="post" class="editsp">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Edit Clients</h4>
          </div>
          <div class="modal-body">

            <div class="form-group">                
                <input class="form-control name_class"
                       name="name"
                       placeholder="Enter Client Name"
                       type="text"
                       required>
            </div>
            <div class="form-group">                
                <input class="form-control lname_class"
                       name="lname"
                       placeholder="Enter Salutation"
                       type="text"
                       required>
            </div>
            <div class="form-group">                
                <input class="form-control taxnumber_class"
                       name="taxnumber"
                       placeholder="Enter Tax Number"
                       id="idtax_edit"
                       type="text"
                       required>
            </div>
            <div class="form-group">                
                <input class="form-control email_class"
                       name="email"
                       placeholder="Enter Email ID"
                       type="text"
                       required>
            </div>
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
<style>
.cursor_sort{cursor: pointer;}
.table thead th:focus{background: #333333}
</style>
<!-- Content Header (Page header) -->
<div class="admin_content_section">
        <div class="col-lg-12 padding_00">
            <div class="col-lg-4 text-left padding_00">
              <div class="sub_title" style="padding: 0px;">
                  Manage Clients                
              </div>
            </div>
            <div class="col-lg-2 text-right" style="padding-right: 0px;">
              <input type="text" name="" placeholder="Search Client Name" class="form-control client_search_class">
              <input type="hidden" id="client_search" />
            </div>
            <div class="col-lg-2 text-right" style="padding-right: 0px;">
              <input type="text" name="" placeholder="Search Tax Number" class="form-control tax_search_class">
              <input type="hidden" id="tax_search" />
            </div>
            <div class="col-lg-2 text-right" style="padding-right: 0px;">
              <input type="text" name="" placeholder="Search Email ID" class="form-control email_search_class">
              <input type="hidden" id="email_search" />
            </div>
            <div class="col-lg-2 text-right"  style="padding: 0px;" >
                <a href="" class="common_black_button    margin-inline">Reset</a>
                <button type="button" class="common_black_button addclass" style="margin-right: 0px;" data-toggle="modal" data-target=".bs-example-modal-sm">Add New</button>                
            </div>                                    
        </div>
        
            <div class="row">
                <div class="col-lg-12">                                      
                    <div class="col-lg-12 padding_00" style="margin-top: 20px;">
                      <div>
                     <?php
                      if(Session::has('message')) { ?>
                          <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
                      <?php }
                      ?>
                      </div>
                        <table class="table table-hover nowrap" id="clients_sort" width="100%">
                            <thead class="table-inverse">
                            <tr style="background: #fff;">
                                <th width="5%" style="text-align: left;">S.No</th>
                                <th style="text-align: center;">First Name</th>
                                <th style="text-align: center;">Last Name</th>
                                <th style="text-align: center;">Tax Number</th>
                                <th style="text-align: center;">Email</th>
                                <th style="text-align: center;">Secondary Email</th>
                                <th style="text-align: center;" width="15%">Action</th>
                            </tr>
                            </thead>                            
                            <tbody id="clients_tbody">
                              <?php
                                $i=1;
                                if(count($userlist)){              
                                  foreach($userlist as $user){
                              ?>
                              <tr class="sort_tr">            
                                <td class="sno_td" ><?php echo $i;?></td>            
                                <td align="left" class="first_td"><?php echo $user->firstname; ?></td>
                                <td align="left" class="last_td"><?php echo $user->lastname; ?></td>
                                <td align="left" class="tax_td"><?php echo $user->taxnumber; ?></td>
                                <td align="left" class="email_td"><?php echo $user->email; ?></td>
                                <td align="left" class="seconday_td"><?php echo $user->secondary_email; ?></td>
                                <td align="center">
                                    <?php
                                    if($user->status ==0){
                                      echo'<a href="'.URL::to('admin/deactive_rctclients',base64_encode($user->client_id)).'" title="Hide Clients"><i style="color:#00b348;" class="fa fa-check" aria-hidden="true"></i></a>';
                                    }
                                    else{
                                      echo'<a href="'.URL::to('admin/active_rctclients',base64_encode($user->client_id)).'" title="Unhide Clients"><i style="color:#f00;" class="fa fa-times" aria-hidden="true"></i></a>';
                                    }
                                    ?>

                                    &nbsp; &nbsp;

                                    <a href="#" id="<?php echo base64_encode($user->client_id); ?>" class="editclass" title="Edit Clients"><i class="fa fa-pencil-square editclass" id="<?php echo base64_encode($user->client_id); ?>" aria-hidden="true"></i></a>&nbsp; &nbsp;
                                    <a href="<?php echo URL::to('admin/delete_rctclients', base64_encode($user->client_id)) ?>" title="Delete Clients" class="delete_user"><i class="fa fa fa-trash delete_user" aria-hidden="true"></i></a>
                                </td>
                              </tr>
                              <?php
                                  $i++;
                                  }              
                                }
                                else{
                                  echo'<tr><td colspan="5" align="center">Empty</td></tr>';
                                }
                              ?>                                                       
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>            
        
    </div>



<script>
$(function(){
    $('#clients_sort').DataTable({
        autoWidth: true,
        scrollX: true,
        fixedColumns: true,
        searching: false
    });
});
</script>

<script>

$(window).click(function(e) {
  if($(e.target).hasClass('addclass')) {
    $(".addsp").show();
    $(".editsp").hide();
  }
  if($(e.target).hasClass('editclass')) {
    var editid = $(e.target).attr("id");
    $('#form-validation-edit').validate({
        rules: {
            name : {required: true,},
            lname : {required: true,},
            taxnumber : {required: true,
                          remote: { 
                            url : "<?php echo URL::to('admin/rctclient_checktax'); ?>",
                            type:"get",
                            data : { id: atob(editid)},
                          }, },
            email : {required: true,email:true,
                          remote: { 
                            url : "<?php echo URL::to('admin/rctclient_checkemail'); ?>",
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
        url: "<?php echo URL::to('admin/edit_rctclients') ?>"+"/"+editid,
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


  var ascending = false;
  
  if($(e.target).hasClass('delete_user'))
  {
    var r = confirm("Are You Sure want to delete this Clients?");
    if (r == true) {
       
    } else {
        return false;
    }
  }
});
$.ajaxSetup({async:false});
$('#form-validation').validate({
    rules: {
        name : {required: true,},
        lname : {required: true,},
        taxnumber : {required: true,remote:"<?php echo URL::to('admin/rctclient_checktax'); ?>"},
        email : {required: true,email:true,remote:"<?php echo URL::to('admin/rctclient_checkemail'); ?>"},
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

<script>
$(document).ready(function() {    
     $(".client_search_class").autocomplete({
        source: function(request, response) {
            $.ajax({
                url:"<?php echo URL::to('admin/rctclient_search'); ?>",
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
            url:"<?php echo URL::to('admin/rctclient_search_select'); ?>",
            data:{value:ui.item.value},
            success: function(result){
              $("#clients_tbody").html(result);
              $("#clients_sort_paginate").hide();
              $(".dataTables_info").hide();
            }
          })
        }
    });
    $(".tax_search_class").autocomplete({
        source: function(request, response) {
            $.ajax({
                url:"<?php echo URL::to('admin/rctclient_tax_search'); ?>",
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
            url:"<?php echo URL::to('admin/rctclient_tax_search_select'); ?>",
            data:{value:ui.item.value},
            success: function(result){
              $("#clients_tbody").html(result);
              $("#clients_sort_paginate").hide();
              $(".dataTables_info").hide();
            }
          })
        }
    });

    $(".email_search_class").autocomplete({
        source: function(request, response) {
            $.ajax({
                url:"<?php echo URL::to('admin/rctclient_email_search'); ?>",
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
            url:"<?php echo URL::to('admin/rctclient_email_search_select'); ?>",
            data:{value:ui.item.value},
            success: function(result){
              $("#clients_tbody").html(result);
              $("#clients_sort_paginate").hide();
              $(".dataTables_info").hide();
            }
          })
        }
    });


    
});
</script>
@stop
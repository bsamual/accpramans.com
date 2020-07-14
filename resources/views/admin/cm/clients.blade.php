@extends('adminheader')
@section('content')

<style>
.modal { overflow: auto !important; }
.pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover
{
  z-index: 0 !important;
}
.ui-tooltip{
  margin-top:-50px !important;
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
.field_check
{
  width:24%;
}
.import_div{
    position: absolute;
    top: 55%;
    left:30%;
    padding: 15px;
    background: #ff0;
}
.selectall_div{
  position: absolute;
    top: 13%;
    left: 5%;
    border: 1px solid #000;
    padding: 12px;
    background: #ff0;
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
                Client Management System                
            </h4>
            <div class="col-lg-1 text-right" style="padding-right: 0px; line-height: 35px;">
                
            </div>
            <div class="col-lg-1 text-right" style="padding-right: 0px;">
              <input type="text" name="" placeholder="Search" class="form-control search_input_class" >
            </div>
            <div class="col-lg-2 text-right" style="padding-right: 0px;">
              <select class="form-control search_select_class">
                <option value="">Select Type</option>
                <option value="client_id">Client Id</option>
                <option value="firstname">First Name</option>
                <option value="surname">Surname</option>
                <option value="address1">Address</option>
                <option value="company">Company Name</option>
                <option value="email">Email Address</option>
                <option value="phone">Phone</option>
              </select>
            </div>
            <div class="col-lg-5 text-right"  style="padding: 0px;" >
              <div class="select_button" style=" margin-left: 10px;">
                <ul>
                <li><a href="javascript:" id="search_button" style="font-size: 13px; font-weight: 500;">Search</a></li>
                <li><a href="" style="font-size: 13px; font-weight: 500;">Reset</a></li>
              </ul>
            </div>
            <br/>
            <?php $check_incomplete = Db::table('user_login')->where('userid',1)->first(); if($check_incomplete->cm_incomplete == 1) { $inc_checked = 'checked'; } else { $inc_checked = ''; } ?>
                <input type="checkbox" name="show_incomplete" id="show_incomplete" value="1" <?php echo $inc_checked; ?> style="margin-right:10px"><label for="show_incomplete">Hide Deactivated Accounts</label>
  </div>
  <div class="table-responsive" style="max-width: 100%; float: left;margin-bottom:30px; margin-top:55px">
  </div>
<table class="table table-hover nowrap" id="client_expand" width="100%">
                        <thead class="table-inverse">
                        <tr style="background: #fff;">
                             <th width="2%" style="text-align: left;">S.No</th>
                            <th style="text-align: left;">Client ID</th>
                            <th style="text-align: left;">First Name</th>
                            <th style="text-align: left;">Surname</th>
                            <th style="text-align: left;">Company Name</th>
                            <th style="text-align: left;">Class</th>
                            <th style="text-align: left; width:300px; max-width: 300px;">Address</th>
                            <th style="text-align: left;">Primary Email</th> 
                            <th style="text-align: left;">Telephone</th>                            
                        </tr>
                        </thead>                            
                        <tbody id="clients_tbody">
                        <?php
                            $i=1;
                            if(count($clientlist)){              
                              foreach($clientlist as $client){
                                $address = $client->address1.' '.$client->address2.' '.$client->address3.' '.$client->address4.' '.$client->address5;
                                  if($client->status == 1) { $disabled='disabled'; $style="color:red"; } 
                                  else{ 
                                    $disabled='';
                                    if($client->active != "")
                                    {
                                      $check_color = DB::table('cm_class')->where('id',$client->active)->first();
                                      $style="color:#".$check_color->classcolor."";
                                    }
                                    else{
                                      $style="color:#000";
                                    }
                                  } 
                          ?>
                        <tr class="edit_task <?php echo $disabled; ?>">
                            <td style="<?php echo $style; ?>"><?php echo $i; ?></td>
                            <td align="left" style="<?php echo $style; ?>"><?php echo $client->client_id; ?></td>
                            <td align="left" style="<?php echo $style; ?>"><?php echo $client->firstname; ?></td>
                            <td style="<?php echo $style; ?>" align="left"><?php echo $client->surname; ?></td>
                            <td style="<?php echo $style; ?>" align="left"><?php echo ($client->company == "")?$client->firstname.' '.$client->surname:$client->company; ?></td>
                            <td align="left">
                                <select name="class_select" class="form-control class_select" data-element="<?php echo $client->id; ?>">
                                    
                                    <?php
                                      $class = DB::table('cm_class')->where('status', 0)->get();
                                      if(count($class))
                                      {
                                        foreach($class as $cls)
                                        {
                                          if($cls->id == $client->active) { $selected = 'selected'; } else { $selected = ''; }
                                          echo '<option value="'.$cls->id.'" '.$selected.'>'.$cls->classname.'</option>';
                                        }
                                      }
                                    ?>
                                </select>
                            </td>
                            <td style="word-wrap: break-word; white-space:normal; min-width:300px; max-width: 300px;<?php echo $style; ?>" align="left"><?php echo $address; ?></td>
                            <td align="left"><a style="<?php echo $style; ?>" href="mailto:<?php echo $client->email; ?>"><?php echo $client->email; ?></a></td>
                            <td style="<?php echo $style; ?>" align="left"><?php echo $client->phone; ?></td>
                        </tr>
                        <?php
                              $i++;
                              }              
                            }
                            if($i == 1)
                            {
                              echo'<tr><td colspan="9" align="center">Empty</td></tr>';
                            }
                          ?> 

                        </tbody>
                    </table>

</div>
    <!-- End  -->
<div class="main-backdrop"><!-- --></div>
<div id="print_image">
    
</div>
<div class="modal_load"></div>
<input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
<input type="hidden" name="show_alert" id="show_alert" value="">

<script type="text/javascript">

$(document).ready(function() {
  $('[data-toggle="tooltip"]').tooltip(); 
  if($("#show_incomplete").is(':checked'))
  {
    $(".edit_task").each(function() {
        if($(this).hasClass('disabled'))
        {
          $(this).hide();
        }
    });
  }
  else{
    $(".edit_task").each(function() {
        if($(this).hasClass('disabled'))
        {
          $(this).show();
        }
    });
  }
});
$(window).change(function(e) {
    if($(e.target).hasClass('class_select'))
    {
      var val = $(e.target).val();
      var id = $(e.target).attr('data-element');
      $.ajax({
        url:"<?php echo URL::to('admin/change_cm_client_class'); ?>",
        type:"post",
        data:{val:val,id:id},
        success: function(result)
        {
          $(e.target).parents("tr").find("td").css({"color":"#"+result});
          $(e.target).parents("tr").find("a").css({"color":"#"+result});
        }
      })
    }
});
$(window).click(function(e) {
 
  if(e.target.id == 'show_incomplete')
  {
    if($(e.target).is(':checked'))
    {
      $(".edit_task").each(function() {
          if($(this).hasClass('disabled'))
          {
            $(this).hide();
          }
      });
      $.ajax({
        url:"<?php echo URL::to('admin/update_cm_incomplete_status'); ?>",
        type:"post",
        data:{value:1},
        success: function(result)
        {
        }
      });
    }
    else{
      $(".edit_task").each(function() {
          if($(this).hasClass('disabled'))
          {
            $(this).show();
          }
      });
      $.ajax({
        url:"<?php echo URL::to('admin/update_cm_incomplete_status'); ?>",
        type:"post",
        data:{value:0},
        success: function(result)
        {
        }
      });
    }
  }
  if(e.target.id == "search_button")
  {
    var input = $(".search_input_class").val();
    var select = $(".search_select_class").val();
    if(input == "" || select == "")
    {
      alert("Please Type the Input and choose the search type to serch the clients.");
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('admin/cm_search_clients'); ?>",
        type:"get",
        data:{input:input,select:select},
        success: function(result) {
          $("#clients_tbody").html(result);
          $("#client_expand_info").hide();
          $(".dataTables_paginate").hide();
          if($("#show_incomplete").is(':checked'))
          {
            $(".edit_task").each(function() {
                if($(this).hasClass('disabled'))
                {
                  $(this).hide();
                }
            });
          }
          else{
            $(".edit_task").each(function() {
                if($(this).hasClass('disabled'))
                {
                  $(this).show();
                }
            });
          }
        }
      });
    }
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





@stop
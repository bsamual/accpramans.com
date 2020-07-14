@extends('adminheader')
@section('content')
<style>
.error{
  color:#f00;
}
</style>
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <form id="add_field_form" action="<?php echo URL::to('admin/add_cm_field'); ?>" method="post" class="addsp">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add Field</h4>
          </div>
          <div class="modal-body">
            <table class="table">
                <tr>
                    <td><label>Enter Field Name</label></td>
                </tr>
                <tr>
                    <td><input type="text" name="name_add" id="name_add" class="form-control" placeholder="Enter Field Name" required>
                    <strong class="fieldnameerror" style="color:#f00"></strong>
                    </td>
                </tr>
                <tr>
                    <td><label>Choose Field Type</label></td>
                </tr>
                <tr>
                    <td>
                        <select class="form-control" name="field" id="field_type" required>
                            <option value="">Select Field Type</option>
                            <option value="1">Input</option>
                            <option value="2">Yer or No</option>
                            <option value="3">Message Box</option>
                            <option value="4">File Attached</option>
                            <option value="5">Email</option>
                            <option value="6">Drop Down</option>
                        </select>
                    </td>
                </tr>          
            </table>

            <table class="table" id="dropdown_table" style="display:none">
                <tr>
                    <td><input type="text" class="form-control input-sm" name="options[]"></td>
                    <td><input type="text" class="form-control input-sm" name="value[]"></td>
                    <td class="action_icon"><a href="javascript:" class="fa fa-plus add_option"></a></td>
                </tr>
            </table>
          </div>
          <div class="modal-footer">
            <input type="submit" value="Submit" class="common_black_button float_right">
          </div>
        </div>
    </form>

    <form id="edit_field_form" action="<?php echo URL::to('admin/update_cm_field'); ?>" method="post" class="editsp">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Edit Field</h4>
          </div>
          <div class="modal-body">
            <table class="table">
                <tr>
                    <td><label>Enter Field Name</label></td>
                </tr>
                <tr>
                    <td><input type="text" name="name" class="form-control name_class" placeholder="Enter Task Level" required></td>
                </tr>
                <tr>
                    <td><label>Choose Field Type</label></td>
                </tr>
                <tr>
                    <td>
                      <select class="form-control field_class" name="field" id="field_type_edit" required>
                            <option value="">Select Field Type</option>
                            <option value="1">Input</option>
                            <option value="2">Yer or No</option>
                            <option value="3">Message Box</option>
                            <option value="4">File Attached</option>
                            <option value="5">Email</option>
                            <option value="6">Drop Down</option>
                        </select>
                    </td>
                </tr>
                
            </table>
            <table class="table" id="dropdown_table_edit" style="display:none">
                
            </table>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="id" class="form-control name_id">
            <input type="submit" value="Update" class="btn common_black_button">
          </div>
        </div>
    </form>
  </div>
</div>
<!-- Content Header (Page header) -->
<div class="admin_content_section">  
  <div>
  <div class="table-responsive">
    <div>
      <?php
      if(Session::has('message')) { ?>
          <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
      <?php }
      ?>
    </div>
    <div class="col-lg-12 padding_00">
      <div class="col-lg-6 text-left padding_00">
        <div class="sub_title">Manage CM Field</div>
      </div>      
      <div class="col-lg-6 text-right">
        <a href="javascript:" class="addclass common_black_button float_right" data-toggle="modal" data-target=".bs-example-modal-sm">Add New Field</a>
      </div>
    </div>
    <table class="table">
        <thead>
          <tr>
              <th width="5%" style="text-align: left;">S.No</th>
              <th>Field Name</th>   
              <th>Field Type</th>              
              <th width="15%">Action</th>
              
          </tr>
        </thead>
        <tbody>
          <?php
            $i=1;
            if(count($cmfieldlist)){              
              foreach($cmfieldlist as $cmfield){
          ?>
          <tr>            
            <td><?php echo $i;?></td>            
            <td align="center"><?php echo $cmfield->name; ?></td>
            <?php
              if($cmfield->field == 1) { $type = 'Input'; }
              if($cmfield->field == 2) { $type = 'Yes Or No'; }
              if($cmfield->field == 3) { $type = 'Message Box'; }
              if($cmfield->field == 4) { $type = 'File Attached'; }
              if($cmfield->field == 5) { $type = 'Email'; }
              if($cmfield->field == 6) { $type = 'Dropdown'; }
            ?>
            <td align="center"><?php echo $type; ?></td>
            <td align="center">
                <?php
                if($cmfield->status ==0){
                  echo'<a href="'.URL::to('admin/deactive_cm_field',base64_encode($cmfield->id)).'"><i style="color:#00b348;" class="fa fa-check" aria-hidden="true"></i></a>';
                }
                else{
                  echo'<a href="'.URL::to('admin/active_cm_field',base64_encode($cmfield->id)).'"><i style="color:#f00;" class="fa fa-times" aria-hidden="true"></i></a>';
                }
                ?>

                &nbsp; &nbsp;

                <!-- <a href="javascript:" id="<?php //echo base64_encode($cmfield->id); ?>" class="editclass"><i class="fa fa-pencil-square" aria-hidden="true"></i></a> -->
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
<script>
$('#name_add').keypress(function (e) {
    var regex = new RegExp("^[a-zA-Z0-9_\s]+$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(str)) {
        return true;
    }
    else if(e.keyCode == 8) {
      return true;
    }
    $(".fieldnameerror").show();
    $(".fieldnameerror").text("This ("+str+") is a Restricted special character so it has been removed.");
    setTimeout(check_error,3000);
    
    e.preventDefault();
    return false;
});
function check_error()
{
  if($(".fieldnameerror").css('display') != 'none')
  {
    $(".fieldnameerror").text("");
    $(".fieldnameerror").hide();
  }
}

$(".addclass").click( function(){
  $(".addsp").show();
  $(".editsp").hide();
});
$(".editclass").click( function(){
  var editid = $(this).attr("id");
  console.log(editid);
  $.ajax({
      url: "<?php echo URL::to('admin/edit_cm_field') ?>"+"/"+editid,
      dataType:"json",
      type:"post",
      success:function(result){
         $(".bs-example-modal-sm").modal("toggle");
         $(".editsp").show();
         $(".addsp").hide();
         $(".name_class").val(result['name']);
         $(".field_class").val(result['field']);  
         if(result['field'] == "6")
         {
          $("#dropdown_table_edit").html(result['options']);
          $("#dropdown_table_edit").show();
         }       
         $(".name_id").val(result['id']);
    }
  })
});
$(window).click(function(e) {
  var ascending = false;
  if($(e.target).hasClass('delete_user'))
  {
    var r = confirm("Deleting this User will leads to remove from task alloted to this user. Are You Sure want to delete this user?");
    if (r == true) {
       
    } else {
        return false;
    }
  }
  if($(e.target).hasClass('add_option'))
  {
    var html = '<tr><td><input type="text" class="form-control input-sm" name="options[]" required></td><td><input type="text" class="form-control input-sm" name="value[]" required></td><td class="action_icon"><a href="javascript:" class="fa fa-plus add_option"></a><a href="javascript:" class="fa fa-minus delete_option"></a></td></tr>';
    
    $(".action_icon").each(function() {
      $(this).html('<a href="javascript:" class="fa fa-minus delete_option"></a>');
    });
    $("#dropdown_table").append(html);
  }
  if($(e.target).hasClass('delete_option'))
  {
    $(e.target).parents("tr").detach();
    if($("#dropdown_table").find("tr").length == 1)
    {
      $(".action_icon").each(function() {
        $(this).html('<a href="javascript:" class="fa fa-plus add_option"></a>');
      });
    }
    else{
      $(".action_icon:last").html('<a href="javascript:" class="fa fa-plus add_option"></a><a href="javascript:" class="fa fa-minus delete_option"></a>');
    }
  }

  if($(e.target).hasClass('add_option_edit'))
  {
    var html = '<tr><td><input type="text" class="form-control input-sm" name="options[]" required></td><td><input type="text" class="form-control input-sm" name="value[]" required></td><td class="action_icon_edit"><a href="javascript:" class="fa fa-plus add_option_edit"></a><a href="javascript:" class="fa fa-minus delete_option_edit"></a></td></tr>';
    
    $(".action_icon_edit").each(function() {
      $(this).html('<a href="javascript:" class="fa fa-minus delete_option_edit"></a>');
    });
    $("#dropdown_table_edit").append(html);
  }
  if($(e.target).hasClass('delete_option_edit'))
  {
    $(e.target).parents("tr").detach();
    if($("#dropdown_table_edit").find("tr").length == 1)
    {
      $(".action_icon_edit").each(function() {
        $(this).html('<a href="javascript:" class="fa fa-plus add_option_edit"></a>');
      });
    }
    else{
      $(".action_icon_edit:last").html('<a href="javascript:" class="fa fa-plus add_option_edit"></a><a href="javascript:" class="fa fa-minus delete_option_edit"></a>');
    }
  }
});
$(window).change(function(e) {
  if(e.target.id == "field_type")
  {
    var value = $(e.target).val();
    if(value == 6)
    {
      $("#dropdown_table").show();
      $("#dropdown_table").find("input").each(function() {
        $(this).prop("required",true);
      });
    }
    else{
      $("#dropdown_table").hide();
      $("#dropdown_table").find("input").each(function() {
        $(this).prop("required",false);
      });
    }
  }
  if(e.target.id == "field_type_edit")
  {
    var value = $(e.target).val();
    if(value == 6)
    {
      if($("#dropdown_table_edit").find(".action_icon_edit").length)
      {

      }
      else{
        var html = '<tr><td><input type="text" class="form-control input-sm" name="options[]" required></td><td><input type="text" class="form-control input-sm" name="value[]" required></td><td class="action_icon_edit"><a href="javascript:" class="fa fa-plus add_option_edit"></a></td></tr>';
    
        $("#dropdown_table_edit").append(html);
      }
      $("#dropdown_table_edit").show();
      $("#dropdown_table_edit").find("input").each(function() {
        $(this).prop("required",true);
      });
    }
    else{
      $("#dropdown_table_edit").hide();
      $("#dropdown_table_edit").find("input").each(function() {
        $(this).prop("required",false);
      });
    }
  }
});
$('#add_field_form').validate({
    rules: {
        name_add : {required: true,remote:"<?php echo URL::to('admin/cm_client_checkfield'); ?>"},
    },
    messages: {
        name_add : {
          required : "Field Name is Required",
          remote : "Field Name is Already created",
        },
    },
});
</script>
@stop
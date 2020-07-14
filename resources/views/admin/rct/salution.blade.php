@extends('adminheader')
@section('content')
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <form id="form-validation-edit" action="<?php echo URL::to('admin/update_rctsalution'); ?>" method="post" class="editsp">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Edit Salution</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
                <input type="text"
                  class="form-control name_class" readonly>
            </div>

            <div class="form-group">
                <textarea class="form-control desc_class"
                       name="description"
                       placeholder="Enter Salution"
                       type="text"
                       data-validation="[NOTEMPTY]"
                       data-validation-message="Enter Salution" style="height: 150px;"></textarea>
                <input type="hidden" class="name_id" name="id">
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
</style>
<!-- Content Header (Page header) -->
<div class="admin_content_section">
        <div class="col-lg-12 padding_00">
            <div class="col-lg-4 text-left padding_00">
              <div class="sub_title" style="padding: 0px;">
                Manage Email Salution             
              </div>
            </div>
            
        </div>
        
            <div class="col-lg-12 padding_00" style="margin-top: 20px;">
                <div class="col-lg-12 padding_00">                    
                  <div>
                 <?php
                  if(Session::has('message')) { ?>
                      <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
                  <?php }
                  ?>
                  </div>
                    <div class="margin-bottom-50">
                        <table class="table">
                            <thead class="table-inverse">
                            <tr style="background: #fff;">
                                <th width="5%" style="text-align: left;">S.No</th>
                                <th>Name</th>
                                <th style="text-align: center;">Description</th>
                                <th style="text-align: center;" width="15%">Action</th>
                            </tr>
                            </thead>                            
                            <tbody>
                              <?php
                                $i=1;
                                if(count($userlist)){              
                                  foreach($userlist as $user){
                              ?>
                              <tr>            
                                <td><?php echo $i;?></td>            
                                <td align="left"><?php echo $user->name; ?></td>
                                <td align="left"><?php echo $user->description; ?></td>
                                <td align="center">
                                    <a href="#" id="<?php echo base64_encode($user->id); ?>" class="editclass"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
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

<input type="hidden" name="sno_sortoptions" id="sno_sortoptions" value="asc">
<input type="hidden" name="first_sortoptions" id="first_sortoptions" value="asc">
<input type="hidden" name="last_sortoptions" id="last_sortoptions" value="asc">
<input type="hidden" name="tax_sortoptions" id="tax_sortoptions" value="asc">
<input type="hidden" name="email_sortoptions" id="email_sortoptions" value="asc">
<input type="hidden" name="seconday_sortoptions" id="seconday_sortoptions" value="asc">


<script>
$(".addclass").click( function(){
  $(".addsp").show();
  $(".editsp").hide();
});
$(".editclass").click( function(){
  var editid = $(this).attr("id");
  console.log(editid);
  $.ajax({
      url: "<?php echo URL::to('admin/edit_rctsalution') ?>"+"/"+editid,
      dataType:"json",
      type:"post",
      success:function(result){
         $(".bs-example-modal-sm").modal("toggle");
         $(".editsp").show();
         $(".addsp").hide();
         $(".name_class").val(result['name']);
         $(".desc_class").val(result['description']);         
         $(".name_id").val(result['id']);
    }
  })
});
$(window).click(function(e) {
  var ascending = false;
  if($(e.target).hasClass('delete_user'))
  {
    var r = confirm("Are You Sure want to delete this Subcontractor?");
    if (r == true) {
       
    } else {
        return false;
    }
  }
});
$('#form-validation').validate({
    submit: {
        settings: {
            inputContainer: '.form-group',
            errorListClass: 'form-control-error',
            errorClass: 'has-danger'
        }
    }
});
$('#form-validation-edit').validate({
    submit: {
        settings: {
            inputContainer: '.form-group',
            errorListClass: 'form-control-error',
            errorClass: 'has-danger'
        }
    }
});
</script>
@stop
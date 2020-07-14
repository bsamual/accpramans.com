@extends('adminheader')
@section('content')
<style>
.label_class{
  width:20%;
  float: left;
}
</style>
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <form action="<?php echo URL::to('admin/add_cm_paper'); ?>" method="post" class="addsp">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add Print Size</h4>
          </div>
          <div class="modal-body">
            <table class="table">
                <tr>
                    <td><label>Enter Print Name</label></td>
                    <td><input type="text" name="name" class="form-control" placeholder="Enter Print Name" required></td>
                    <td></td>
                </tr>
                <tr>
                    <td><label>Enter Width (PX)</label></td>
                    <td><input type="number" min="1" max="2000" name="width" class="form-control" placeholder="Enter Width (PX)" required></td>
                    <td></td>
                </tr>
                <tr>
                    <td><label>Enter Height (PX)</label></td>
                    <td><input type="number" min="1" max="2000" name="height" class="form-control" placeholder="Enter Height (PX) " required></td>
                    <td></td>
                </tr>
                <tr>
                  <td colspan="3">
                      <label>CHOOSE LABELS</label><br/><br/>
                      <div class="label_class"><input type="checkbox" name="fields[]" value="client_id" id="field_1"><label for="field_1">Client Id</label></div>
                      <div class="label_class"><input type="checkbox" name="fields[]" value="firstname" id="field_2"><label for="field_2">Firstname</label></div>
                      <div class="label_class"><input type="checkbox" name="fields[]" value="surname" id="field_3"><label for="field_3">Surname</label></div>
                      <div class="label_class"><input type="checkbox" name="fields[]" value="company" id="field_4"><label for="field_4">Company</label></div>
                      <div class="label_class"><input type="checkbox" name="fields[]" value="address" id="field_5"><label for="field_5">Address</label></div>
                      <div class="label_class"><input type="checkbox" name="fields[]" value="email" id="field_6"><label for="field_6">Primar Email</label></div>
                      <div class="label_class"><input type="checkbox" name="fields[]" value="tye" id="field_7"><label for="field_7">Type</label></div>
                      <div class="label_class"><input type="checkbox" name="fields[]" value="active" id="field_8"><label for="field_8">Class</label></div>
                      <div class="label_class"><input type="checkbox" name="fields[]" value="tax_reg1" id="field_9"><label for="field_9">Tax Reg1</label></div>
                      <div class="label_class"><input type="checkbox" name="fields[]" value="tax_reg2" id="field_10"><label for="field_10">Tax Reg2</label></div>
                      <div class="label_class"><input type="checkbox" name="fields[]" value="tax_reg3" id="field_11"><label for="field_11">Tax Reg3</label></div>
                      <div class="label_class"><input type="checkbox" name="fields[]" value="email2" id="field_12"><label for="field_12">Secondary Email</label></div>
                      <div class="label_class"><input type="checkbox" name="fields[]" value="phone" id="field_13"><label for="field_13">Phone</label></div>
                      <div class="label_class"><input type="checkbox" name="fields[]" value="linkcode" id="field_14"><label for="field_14">Link Code</label></div>
                      <div class="label_class"><input type="checkbox" name="fields[]" value="cro" id="field_15"><label for="field_15">Cro</label></div>
                      <div class="label_class"><input type="checkbox" name="fields[]" value="trade_status" id="field_16"><label for="field_16">Trade Status</label></div>
                      <div class="label_class"><input type="checkbox" name="fields[]" value="directory" id="field_17"><label for="field_17">Directory</label></div>
                  </td>
                </tr>
                <tr>
                    <td>
                    <label>SET AS DEFAULT</label><br/><br/>
                    <input type="checkbox" name="set_as_default" id="set_as_default" value="1"><label for="set_as_default">Set As Default</label>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
          </div>
          <div class="modal-footer">
            <input type="submit" value="Submit" class="common_black_button float_right">
          </div>
        </div>
    </form>

    <form action="<?php echo URL::to('admin/update_cm_paper'); ?>" method="post" class="editsp">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Edit Print Size</h4>
          </div>
          <div class="modal-body">
            <table class="table">
                <tr>
                    <td><label>Enter Print Name</label></td>
                    <td><input type="text" name="name" class="form-control name_class" placeholder="Enter Task Level" required></td>
                    <td></td>
                </tr>
                <tr>
                    <td><label>Enter Width (PX)</label></td>
                    <td><input type="number" min="1" max="2000" name="width" class="form-control width_class" placeholder="Enter color Code" required></td>
                    <td></td>
                </tr>
                <tr>
                    <td><label>Enter Height (PX)</label></td>
                    <td><input type="number" min="1" max="2000" name="height" class="form-control height_class" placeholder="Enter color Code" required></td>
                    <td></td>
                </tr>
                <tr>
                  <td colspan="3">
                      <label>CHOOSE LABELS</label><br/><br/>
                      <div class="label_class"><input type="checkbox" name="fields[]" value="client_id" id="client_id_edit"><label for="client_id_edit">Client Id</label_edit></div>
                      <div class="label_class"><input type="checkbox" name="fields[]" value="firstname" id="firstname_edit"><label for="firstname_edit">Firstname</label></div>
                      <div class="label_class"><input type="checkbox" name="fields[]" value="surname" id="surname_edit"><label for="surname_edit">Surname</label></div>
                      <div class="label_class"><input type="checkbox" name="fields[]" value="company" id="company_edit"><label for="company_edit">Company</label></div>
                      <div class="label_class"><input type="checkbox" name="fields[]" value="address" id="address_edit"><label for="address_edit">Address</label></div>
                      <div class="label_class"><input type="checkbox" name="fields[]" value="email" id="email_edit"><label for="email_edit">Primar Email</label></div>
                      <div class="label_class"><input type="checkbox" name="fields[]" value="tye" id="tye_edit"><label for="tye_edit">Type</label></div>
                      <div class="label_class"><input type="checkbox" name="fields[]" value="active" id="active_edit"><label for="active_edit">Class</label></div>
                      <div class="label_class"><input type="checkbox" name="fields[]" value="tax_reg1" id="tax_reg1_edit"><label for="tax_reg1_edit">Tax Reg1</label></div>
                      <div class="label_class"><input type="checkbox" name="fields[]" value="tax_reg2" id="tax_reg2_edit"><label for="tax_reg2_edit">Tax Reg2</label></div>
                      <div class="label_class"><input type="checkbox" name="fields[]" value="tax_reg3" id="tax_reg3_edit"><label for="tax_reg3_edit">Tax Reg3</label></div>
                      <div class="label_class"><input type="checkbox" name="fields[]" value="email2" id="email2_edit"><label for="email2_edit">Secondary Email</label></div>
                      <div class="label_class"><input type="checkbox" name="fields[]" value="phone" id="phone_edit"><label for="phone_edit">Phone</label></div>
                      <div class="label_class"><input type="checkbox" name="fields[]" value="linkcode" id="linkcode_edit"><label for="linkcode_edit">Link Code</label></div>
                      <div class="label_class"><input type="checkbox" name="fields[]" value="cro" id="cro_edit"><label for="cro_edit">Cro</label></div>
                      <div class="label_class"><input type="checkbox" name="fields[]" value="trade_status" id="trade_status_edit"><label for="trade_status_edit">Trade Status</label></div>
                      <div class="label_class"><input type="checkbox" name="fields[]" value="directory" id="directory_edit"><label for="directory_edit">Directory</label></div>
                  </td>
                </tr>
                <tr>
                    <td>
                    <label>SET AS DEFAULT</label><br/><br/>
                    <input type="checkbox" name="set_as_default" id="set_as_default_edit" value="1"><label for="set_as_default_edit">Set As Default</label>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
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
        <div class="sub_title">Manage CM Print</div>
      </div>      
      <div class="col-lg-6 text-right">
        <a href="javascript:" class="addclass common_black_button float_right" data-toggle="modal" data-target=".bs-example-modal-sm">Add New Print</a>
      </div>
    </div>
    <table class="table">
        <thead>
          <tr>
              <th width="5%" style="text-align: left;">S.No</th>
              <th>Name</th>
              <th>Size</th>
              <th width="15%">Action</th>
              
          </tr>
        </thead>
        <tbody>
          <?php
            $i=1;
            if(count($cmprintlist)){              
              foreach($cmprintlist as $cmprint){
          ?>
          <tr>            
            <td><?php echo $i;?></td>            
            <td align="center"><?php echo $cmprint->papername; ?></td>
            <td align="center">Width: <?php echo $cmprint->width; ?> X Height:<?php echo $cmprint->height; ?></td>
            <td align="center">
                <?php
                if($cmprint->status ==0){
                  echo'<a href="'.URL::to('admin/deactive_cm_paper',base64_encode($cmprint->id)).'"><i style="color:#f00;" class="fa fa-times" aria-hidden="true"></i></a>';
                }
                else{
                  echo'<a href="javascript:"><i style="color:#00b348;" class="fa fa-check" aria-hidden="true"></i></a>';
                }
                ?>

                &nbsp; &nbsp;

                <a href="javascript:" id="<?php echo base64_encode($cmprint->id); ?>" class="editclass"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
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
$(".addclass").click( function(){
  $(".addsp").show();
  $(".editsp").hide();
});
$(".editclass").click( function(){
  var editid = $(this).attr("id");
  console.log(editid);
  $.ajax({
      url: "<?php echo URL::to('admin/edit_cm_paper') ?>"+"/"+editid,
      dataType:"json",
      type:"post",
      success:function(result){
         $(".bs-example-modal-sm").modal("toggle");
         $(".editsp").show();
         $(".addsp").hide();
         $(".name_class").val(result['name']);
         $(".width_class").val(result['width']);
         $(".height_class").val(result['height']);         
         $(".name_id").val(result['id']);
         var rr = result['fields'].split(',');
         $.each(rr, function( index, value ) {
          $("#"+value+"_edit").prop("checked",true);
        });
         if(result['status'] == 1)
         {
          $("#set_as_default_edit").prop("checked",true);
         }
         else{
          $("#set_as_default_edit").prop("checked",false);
         }

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
});
</script>
@stop
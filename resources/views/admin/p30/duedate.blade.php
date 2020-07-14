@extends('adminheader')
@section('content')
<style>
.sub_title{
    font-size: 18px;
    margin-bottom: 20px;

}
.border{
    padding: 10px;
    line-height: 3;
}
.error{
      color: #f00;
    line-height: 1;
}
.top_row{
  z-index:99999;
}
</style>
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

    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col-lg-offset-2 col-md-offset-2 padding_00 border">
        <div class="col-lg-12 text-left padding_00">
          <div class="sub_title" style="text-align:center">Due Date</div>
        </div>
        <div class="col-lg-12 text-left padding_00">
          <form action="<?php echo URL::to('admin/update_p30_duedate'); ?>" method="post" id="update_admin_form">
            <div class="row">
                <div class="col-md-6">
                    <label>Due Date</label>
                </div>
                <div class="col-md-6">
                    <input type="number" min="1" max="31" name="date" class="form-control input-sm" value="<?php echo $duedate->date; ?>" required>
                </div>
            </div>
            
            
            
            <div class="row">
              <div class="col-md-12" style="text-align:center">
                  <input type="submit" name="admin_submit" id="admin_submit" class="btn common_black_button" value="Change Settings">
              </div>
            </div>
          </form>
        </div>
      </div>
    
        
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
      url: "<?php echo URL::to('admin/edit_p30_period') ?>"+"/"+editid,
      dataType:"json",
      type:"post",
      success:function(result){
         $(".bs-example-modal-sm").modal("toggle");
         $(".editsp").show();
         $(".addsp").hide();
         $(".name_class").val(result['name']);         
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
});
</script>
@stop
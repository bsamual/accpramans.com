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

          <p class="alert alert-info">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            <?php echo Session::get('message'); ?></p>
      <?php }
      ?>
    </div>
      <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col-lg-offset-2 col-md-offset-2 padding_00 border">
        <div class="col-lg-12 text-left padding_00">
          <div class="sub_title" style="text-align:center">CRYPT-PIN Password </div>
        </div>
        <div class="col-lg-12 text-left padding_00">
          <form action="<?php echo URL::to('admin/update_cm_crypt'); ?>" method="post" id="update_admin_form">            
            <div class="row">
                <div class="col-md-6">
                    <label>Current Password</label>
                </div>
                <div class="col-md-6">
                    <?php $details = DB::table('admin')->first(); ?>
                    <input type="text" name="oldcryptpassword" class="form-control input-sm" value="<?php echo $details->view_pass; ?>" readonly required>
                </div>

            </div>
            <div class="row">
                <div class="col-md-6">
                    <label>New Password</label>
                </div>
                <div class="col-md-6">
                    <input type="password" name="cryptpassword" id="cryptpassword" class="form-control input-sm" value="" required>
                </div>

            </div>
            <div class="row">
                <div class="col-md-6">
                    <label>Confirm Password</label>
                </div>
                <div class="col-md-6">
                    <input type="password" name="confirmcryptpassword" id="confirmcryptpassword" class="form-control input-sm" value="" required>
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
 
  $.ajaxSetup({async:false});
$("#update_admin_form" ).validate({

    rules: {        

        cryptpassword : {required: true,},

        confirmcryptpassword : {required: true, equalTo: cryptpassword},    

    },

    messages: {        

        cryptpassword : "New Password is required",

        confirmcryptpassword : {

            required: "Confirm Password is required",

            equalTo : "Does not Match the password",

        },

    },

});

</script>
@stop
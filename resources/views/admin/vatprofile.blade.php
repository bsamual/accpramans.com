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
          <p class="alert alert-info"><?php echo Session::get('message'); ?></p>
      <?php }
      ?>
    </div>      
      <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 col-lg-offset-2 col-md-offset-2 padding_00 border">
        <div class="col-lg-12 text-left padding_00">
          <div class="sub_title" style="margin-bottom: 0px; ">VAT EMail Signature :</div>
        </div>
        <div class="col-lg-12 text-left padding_00">
          <form action="<?php echo URL::to('admin/update_user_signature'); ?>" method="post" id="update_user_signature_form">
            <textarea class="form-control input-sm" id="editor_1"  name="user_signature" style="height:100px"><?php echo $admin_details->signature; ?></textarea>
            <div class="row">
              <div class="col-md-12" style="text-align:center; margin-top:20px">
                  <input type="submit" name="notify_submit" id="notify_submit" class="btn common_black_button" value="Update">
              </div>
            </div>
          </form>
        </div>
      </div>
  </div>
</div>
</div>
<script>
 initSample(); 
$("#update_admin_form" ).validate({

    rules: {
        admin_username : {required:true,},

        newadmin_password : {required: true,},

        confirmadmin_password : {required: true, equalTo: newadmin_password},    

    },

    messages: {

        admin_username : "Username is required",

        newadmin_password : "New Password is required",

        confirmadmin_password : {

            required: "Confirm Password is required",

            equalTo : "Does not Match the password",

        },

    },

});
$("#update_user_form" ).validate({

    rules: {
        user_username : {required:true,},

        newuser_password : {required: true,},

        confirmuser_password : {required: true, equalTo: newuser_password},    

    },

    messages: {

        user_username : "Username is required",

        newuser_password : "New Password is required",

        confirmuser_password : {

            required: "Confirm Password is required",

            equalTo : "Does not Match the password",

        },

    },

});
</script>
@stop
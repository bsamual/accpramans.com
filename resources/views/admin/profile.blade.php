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
      <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col-lg-offset-2 col-md-offset-2 padding_00 border">
        <div class="col-lg-12 text-left padding_00">
          <div class="sub_title" style="text-align:center">ADMIN SETTINGS</div>
        </div>
        <div class="col-lg-12 text-left padding_00">
          <form action="<?php echo URL::to('admin/update_admin_setting'); ?>" method="post" id="update_admin_form">
            <div class="row">
                <div class="col-md-6">
                    <label>Username</label>
                </div>
                <div class="col-md-6">
                    <input type="text" name="admin_username" id="admin_username" class="form-control input-sm" value="<?php echo $admin_details->username; ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label>Current Password</label>
                </div>
                <div class="col-md-6">
                    <input type="text" name="admin_password" id="admin_password" class="form-control input-sm" value="" required>
                </div>

            </div>
            <div class="row">
                <div class="col-md-6">
                    <label>New Password</label>
                </div>
                <div class="col-md-6">
                    <input type="password" name="newadmin_password" id="newadmin_password" class="form-control input-sm" value="" required>
                </div>

            </div>
            <div class="row">
                <div class="col-md-6">
                    <label>Confirm Password</label>
                </div>
                <div class="col-md-6">
                    <input type="password" name="confirmadmin_password" id="confirmadmin_password" class="form-control input-sm" value="" required>
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
      <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col-lg-offset-2 col-md-offset-2 padding_00 border">
        <div class="col-lg-12 text-left padding_00">
          <div class="sub_title" style="text-align:center">USER SETTINGS</div>
        </div>
        <div class="col-lg-12 text-left padding_00">
          <form action="<?php echo URL::to('admin/update_user_setting'); ?>" method="post" id="update_user_form">
            <div class="row">
                <div class="col-md-6">
                    <label>Username</label>
                </div>
                <div class="col-md-6">
                    <input type="text" name="user_username" id="user_username" class="form-control input-sm" value="<?php echo $user_details->username; ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label>Current Password</label>
                </div>
                <div class="col-md-6">
                    <input type="text" name="user_password" id="user_password" class="form-control input-sm" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label>New Password</label>
                </div>
                <div class="col-md-6">
                    <input type="password" name="newuser_password" id="newuser_password" class="form-control input-sm" value="" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label>Confirm Password</label>
                </div>
                <div class="col-md-6">
                    <input type="password" name="confirmuser_password" id="confirmuser_password" class="form-control input-sm" value="" required>
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
      <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 col-lg-offset-2 col-md-offset-2 padding_00 border">
        <div class="col-lg-12 text-left padding_00">
          <div class="sub_title" style="margin-bottom: 0px; ">Easypayroll Notification Message :</div>
        </div>
        <div class="col-lg-12 text-left padding_00">
          <form action="<?php echo URL::to('admin/update_user_notification'); ?>" method="post" id="update_user_form">
            <textarea class="form-control input-sm" id="editor_1"  name="user_notification" style="height:100px"><?php echo $admin_details->notify_message; ?></textarea>
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
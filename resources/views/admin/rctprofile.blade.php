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
      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-lg-offset-4 col-md-offset-4 padding_00 border">
        <div class="col-lg-12 text-left padding_00">
          <div class="sub_title" style="margin-bottom: 0px; ">RCT Email Signature :</div>
        </div>
        <div class="col-lg-6 text-left padding_00">
          <form id="form-validation-email" name="form-validation" method="POST" action="<?php echo URL::to('admin/update_email_setting'); ?>">
                        <div class="col-lg-12" style="padding: 25px;">
                          <div>
                            <?php
                            if(Session::has('emailmessage')) { ?>
                                <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('emailmessage'); ?></p>
                            <?php }
                            ?>
                          </div>
                            <div class="form-group">
                                <label>Admin Email ID</label>
                                 <input id="validation-email"
                                       class="form-control"
                                       placeholder="Enter Email"
                                       value="<?php echo $admin_details->email; ?>"
                                       name="email"
                                       type="text"
                                       required >                                
                            </div> 
                            <div class="form-group">
                                <label>User CC Email ID</label>
                                <input id="validation-cc-email"
                                       class="form-control"
                                       placeholder="Enter CC Email ID"
                                       value="<?php echo $admin_details->cc_email; ?>"
                                       name="ccemail"
                                       type="text"
                                       required>                                 
                            </div>  
                            <div class="form-group">
                                <label>Item Delete Email ID</label>
                                <input id="validation-cc-email"
                                       class="form-control"
                                       placeholder="Enter Delete Email ID"
                                       value="<?php echo $admin_details->delete_email; ?>"
                                       name="deleteemail"
                                       type="text"
                                       required>                                
                            </div>                          
                            <div class="form-group">
                                <input type="submit" class="common_black_button" value="Update">
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
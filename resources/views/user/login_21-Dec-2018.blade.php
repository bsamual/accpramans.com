<html>
<head>
<title>Easypayroll - Tasks</title>
<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
<script type="text/javascript" src="assets/js/jquery-1.11.2.min.js"></script>
<link href="<?php echo URL::to('assets/css/bootstrap-theme.min.css') ?>" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/font-awesome-4.2.0/css/font-awesome.css') ?>">

<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/style.css') ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/style-responsive.css') ?>">

<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
<!-- <style>
.login_box{
  width:50% !important;
}
.login_box .box{
  height:273px;
}
</style> -->
</head>
<body style="background: #eb9605;">
<div style="width: 100%; height: auto; text-align: center; float: left; margin-bottom: 20px; margin-top: 10%;"><img src="<?php echo URL::to('assets/images/easy_payroll_logo.png')?>" ></div>
<div class="login_box">
  <!-- <div class="row">
      <div class="col-md-6 col-lg-6 col-sm-12"> -->
        
        <div class="box">
          <form action="<?php echo URL::to('user/login'); ?>" method="post">
            <div class="title">User Login</div>
            <div style="clear: both">
            <?php
            if(Session::has('message')) { ?>
                <p class="alert alert-info"><?php echo Session::get('message'); ?></p>
            <?php }
            ?>
            </div> 
            <input type="text" name="userid"  class="linput" placeholder="Enter User Name" required>
            <input type="Password" name="password" class="linput" placeholder="Enter Password" required>    
            <button type="submit" class="lbutton">Submit</button>
          </form>
        </div>
      <!-- </div> -->

      <!-- <div class="col-md-6 col-lg-6 col-sm-12">
        <div class="box">
          <form action="<?php //echo URL::to('admin/login'); ?>" method="post">
            <div class="title">Admin Login</div>
            <div style="clear: both">
            <?php
            //if(Session::has('admin_message')) { ?>
                <p class="alert alert-info"><?php //echo Session::get('admin_message'); ?></p>
            <?php //}
            ?>
            </div> 
            <input type="Password" name="password" class="linput" placeholder="Enter Crypt Pin" required>    
            <button type="submit" class="lbutton">Submit</button>
          </form>
        </div>
      </div> -->
  <!-- </div> -->
  
  
</div>


<script src="<?php echo URL::to('assets/js/jquery.validate.js') ?>"></script>
<script type="text/javascript" src="<?php echo URL::to('assets/js/bootstrap.min.js') ?>"></script>
</body>
</html>




<html>
<head>
<meta charset="UTF-8">
<?php
    //header('Set-Cookie: fileDownload=true; path=/');
    header('Cache-Control: max-age=60, must-revalidate');
 ?>
<title>Easypayroll - Tasks</title>
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/bootstrap.min.css')?>">
<script type="text/javascript" src="<?php echo URL::to('assets/js/jquery-1.11.2.min.js')?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/bootstrap-theme.min.css')?>" />
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/font-awesome-4.2.0/css/font-awesome.css')?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/style.css')?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/style-responsive.css')?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/stylesheet-image-based.css')?>">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
<link rel="stylesheet" href="<?php echo URL::to('assets/css/datepicker/jquery-ui.css')?>">
<link rel="stylesheet" href="<?php echo URL::to('assets/lightbox/colorbox.css')?>">
<script src="<?php echo URL::to('assets/js/datepicker/jquery-1.12.4.js')?>"></script>
<script src="<?php echo URL::to('assets/js/jquery.validate.js')?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/dropzone/dist/dropzone.css'); ?>" />
<script type="text/javascript" src="<?php echo URL::to('assets/dropzone/dist/dropzone.js'); ?>"></script>
<script src="<?php echo URL::to('assets/js/datepicker/jquery-ui.js')?>"></script>
<script type="text/javascript" src="<?php echo URL::to('assets/js/bootstrap.min.js')?>"></script>

<script src="<?php echo URL::to('assets/lightbox/jquery.colorbox.js'); ?>"></script>

<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/vendors/jscrollpane/style/jquery.jscrollpane.css') ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/vendors/ladda/dist/ladda-themeless.min.css') ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/vendors/select2/dist/css/select2.min.css') ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/vendors/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/vendors/summernote/dist/summernote.css') ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/vendors/datatables/media/css/dataTables.bootstrap4.min.css') ?>">


<!-- Vendors Scripts -->
<!-- v1.0.0 -->
<script src="<?php echo URL::to('assets/pagination/jquery.twbsPagination.js'); ?>" type="text/javascript"></script>
<script src="<?php echo URL::to('assets/vendors/jquery-mousewheel/jquery.mousewheel.min.js') ?>"></script>
<script src="<?php echo URL::to('assets/vendors/jscrollpane/script/jquery.jscrollpane.min.js') ?>"></script>
<script src="<?php echo URL::to('assets/vendors/select2/dist/js/select2.full.min.js') ?>"></script>
<!-- <script src="<?php //echo URL::to('assets/vendors/html5-form-validation/dist/jquery.validation.min.js') ?>"></script> -->
<script src="<?php echo URL::to('assets/vendors/moment/min/moment.min.js') ?>"></script>
<script src="<?php echo URL::to('assets/vendors/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') ?>"></script>
<script src="<?php echo URL::to('assets/vendors/summernote/dist/summernote.min.js') ?>"></script>
<script src="<?php echo URL::to('assets/vendors/datatables/media/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo URL::to('assets/vendors/datatables/media/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?php echo URL::to('assets/vendors/datatables-responsive/js/dataTables.responsive.js') ?>"></script>
<script src="<?php echo URL::to('assets/vendors/editable-table/mindmup-editabletable.js') ?>"></script>
<link href="<?php echo URL::to('assets/common/css/jquery-ui.css')?>" rel="stylesheet">
<!-- <script src="<?php //echo URL::to('assets/common/js/jquery-ui.js')?>"></script> -->
<link href="<?php echo URL::to('assets/common/css/jquery.ui.autocomplete.css')?>" rel="stylesheet">
<script src="<?php echo URL::to('assets/ckeditor/ckeditor.js'); ?>"></script>
<script src="<?php echo URL::to('assets/ckeditor/src/js/main.js'); ?>"></script>



<style>
<?php
 $segment1 =  Request::segment(2);  
  if($segment1 == 'manage_week' || $segment1 == 'week_manage' || $segment1 == 'select_week') { 
    if($segment1 == 'select_week') { ?>
      .body_bg{
        background: #7bab15;
        width:2030px !important;
      }
      .page_title{
        width: 100% !important;
          height: auto;
          float: left !important;
          position: fixed !important;
          top: 84px !important;
          font-weight: bold;
          text-align: left;
          padding: 5px 0px;
          margin-bottom: 20px;
          color: #000;
          font-size: 15px;
          text-transform: uppercase;
          left: 0px;
      }
      <?php } 
    else{ ?>
      .body_bg{
        background: #7bab15;
      }
      <?php
      } 
  } 
  elseif($segment1 == 'manage_month' || $segment1 == 'month_manage' || $segment1 == 'select_month') { 
    if($segment1 == 'select_month') { ?>
      .body_bg{
        background: #ffa12d;
        width:2030px !important;
      }
      .page_title{
        width: 100% !important;
          height: auto;
          float: left !important;
          position: fixed !important;
          top: 84px !important;
        font-weight: bold;
        text-align: left;
        padding: 5px 0px;
        margin-bottom: 20px;
        color: #000;
        font-size: 15px;
        text-transform: uppercase;
        left: 0px;
    }
      <?php } 
    else{ ?>
      .body_bg{
        background: #ffa12d;
      }
      <?php
    }
  } 
  elseif($segment1 == 'p30' || $segment1 == 'p30month_manage' || $segment1 == 'p30_select_month') { 
    if($segment1 == 'p30_select_month') { ?>
    .body_bg{
        background: #03d4b7;
        width:2030px !important;
      }
      .page_title{
        width: 100% !important;
          height: auto;
          float: left !important;
          position: fixed !important;
          top: 84px !important;
        font-weight: bold;
        text-align: left;
        padding: 5px 0px;
        margin-bottom: 20px;
        color: #000;
        font-size: 15px;
        text-transform: uppercase;
        left: 0px;
    }
      <?php } 
    else{ ?>
      .body_bg{
        background: #03d4b7;
        
      }
      <?php
    }
  }
  elseif($segment1 == 'gbs_p30' || $segment1 == 'gbs_p30month_manage' || $segment1 == 'gbs_p30_select_month') { 
    if($segment1 == 'gbs_p30_select_month') { ?>
    .body_bg{
        background: #03d4b7;
        width:2030px !important;
      }
      .page_title{
        width: 100% !important;
          height: auto;
          float: left !important;
          position: fixed !important;
          top: 84px !important;
        font-weight: bold;
        text-align: left;
        padding: 5px 0px;
        margin-bottom: 20px;
        color: #000;
        font-size: 15px;
        text-transform: uppercase;
        left: 0px;
    }
      <?php } 
    else{ ?>
      .body_bg{
        background: #03d4b7;
        
      }
      <?php
    }
  } 
  ?>
.dropdown-menu>.active>a, .dropdown-menu>.active>a:focus, .dropdown-menu>.active>a:hover
{
      background-color: #000 !important;
      background-image:none !important;
      color:#fff !important;
}
</style>
</head>
<body class="body_bg loading">

<div class="top_row" style="z-index: 99999">
  <div class="col-lg-12 padding_00">
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo URL::to('user/dashboard')?>"><img src="<?php echo URL::to('assets/images/easy_payroll_logo.png')?>" class="img-responsive" /></a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav navbar-right menu" style="margin-right: 25.5%;">
            <li class="dropdown <?php if($segment1 == "client_management" || $segment1 == "invoice_management" || $segment1 == "client_statements" || $segment1 == "receipt_management" || $segment1 == "time_management") { echo 'active'; } ?>"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">GBS-EP Central</a>
                <ul class="dropdown-menu">
                    <li class="<?php if(($segment1 == "client_management")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/client_management'); ?>">Client Mangement</a></li>
                    <li class="<?php if(($segment1 == "invoice_management")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/invoice_management'); ?>">Invoice Management</a></li>
                    <li class="<?php if(($segment1 == "client_statements")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/client_statements'); ?>">Client Statements</a></li>
                    <li class="<?php if(($segment1 == "receipt_management")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/receipt_management'); ?>">Receipt Management</a></li>
                    <li class="<?php if(($segment1 == "time_management")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/time_management'); ?>">Time Management</a></li>
                </ul>
            </li>
            <li class="dropdown <?php if($segment1 == "manage_week" || $segment1 == "week_manage" || $segment1 == "select_week" || $segment1 == "manage_month" || $segment1 == "month_manage" || $segment1 == "select_month" || $segment1 == "p30" || $segment1 == "p30month_manage" || $segment1 == "p30_select_month") { echo 'active'; } ?>"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">EasyPayroll</a>
                <ul class="dropdown-menu">
                    <li class="<?php if(($segment1 == "manage_week") || ($segment1 == "week_manage") || ($segment1 == "select_week")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/manage_week')?>">Weekly Payroll</a></li>
                    <li class="<?php if(($segment1 == "manage_month") || ($segment1 == "month_manage") || ($segment1 == "select_month")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/manage_month')?>">Monthly Payroll</a></li>
                    <li class="<?php if(($segment1 == "p30") || ($segment1 == "p30month_manage") || ($segment1 == "p30_select_month")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/p30'); ?>">P30 System</a></li>
                </ul>
            </li>

            <li class="dropdown <?php if($segment1 == "vat_clients" || $segment1 == "rctclients" || $segment1 == "expand_rctclient" || $segment1 == "gbs_p30" || $segment1 == "gbs_p30month_manage" || $segment1 == "gbs_p30_select_month" || $segment1 == "year_end_manager") { echo 'active'; } ?>"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">GBS & Co</a>
                <ul class="dropdown-menu">
                    <li class="<?php if(($segment1 == "vat_clients")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/vat_clients')?>">VAT Management</a></li>
                    <li class="<?php if(($segment1 == "rctclients") || ($segment1 == "expand_rctclient")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/rctclients')?>">RCT System</a></li>
                    <li class="<?php if(($segment1 == "gbs_p30") || ($segment1 == "gbs_p30month_manage") || ($segment1 == "gbs_p30_select_month")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/gbs_p30'); ?>">P30 System</a></li>
                    <li class="<?php if(($segment1 == "year_end_manager")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/year_end_manager'); ?>">Year End Manager</a></li>
                </ul>
            </li>
            <li class="<?php if(($segment1 == "time_me" || $segment1 == "time_task" || $segment1 == "time_me_overview" || $segment1 == "time_me_joboftheday" || $segment1 == "time_me_client_review" || $segment1 == "time_me_all_job" || $segment1 == "time_track")) { echo "active"; } else { echo ""; } ?>"><a href="javascript:" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Time Me</a>
              <ul class="dropdown-menu">
                <li class="<?php if(($segment1 == "time_track")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/time_track')?>">Dashboard</a></li>
                  <li class="<?php if(($segment1 == "time_task")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/time_task')?>">Tasks</a></li>
                  <li class="<?php if(($segment1 == "time_me_overview" || $segment1 == "time_me_joboftheday" || $segment1 == "time_me_client_review" || $segment1 == "time_me_all_job")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/time_me_overview')?>">Time me Overview</a></li>
              </ul>
            </li>

            <li class="<?php if(($segment1 == "in_file")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/in_file')?>">InFiles</a>


            
              
            </li>

            


            <!-- <li class="<?php //if(($segment1 == "p30") || ($segment1 == "p30month_manage")) { echo "active"; } else { echo ""; } ?>"><a href="<?php //echo URL::to('user/p30')?>">P30 Task</a></li> -->
            <!-- <li><a href="<?php echo URL::to('admin')?>">Admin Login</a></li> -->
            <li><a href="<?php echo URL::to('user/logout')?>">Logout</a></li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
  </nav>
  </div>
</div>
@yield('content')
<script>
$(document).ready(function() {
  $("body").removeClass("loading");
})
function detectPopupBlocker() {
  var myTest = window.open("about:blank","","directories=no,height=100,width=100,menubar=no,resizable=no,scrollbars=no,status=no,titlebar=no,top=0,location=no");
  if (!myTest) {
    return 1;
  } else {
    myTest.close();
    return 0;
  }
}

function SaveToDisk(fileURL, fileName) {
	var idval = detectPopupBlocker();
	if(idval == 1)
	{
		alert("A popup blocker was detected. Please Allow the popups to download the file.");
	}
	else{
		// for non-IE
		if (!window.ActiveXObject) {
		  var save = document.createElement('a');
		  save.href = fileURL;
		  save.target = '_blank';
		  save.download = fileName || 'unknown';
		  var evt = new MouseEvent('click', {
		    'view': window,
		    'bubbles': true,
		    'cancelable': false
		  });
		  save.dispatchEvent(evt);
		  (window.URL || window.webkitURL).revokeObjectURL(save.href);
		}
		// for IE < 11
		else if ( !! window.ActiveXObject && document.execCommand)     {
		  var _window = window.open(fileURL, '_blank');
		  _window.document.close();
		  _window.document.execCommand('SaveAs', true, fileName || fileURL)
		  _window.close();
		}
	}
	$("body").removeClass("loading");
}
</script>
<div class="footer_row">© Copyright <?php echo date('Y'); ?> All Rights Reserved EasyPayroll</div>

</body>
</html>
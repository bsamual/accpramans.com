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
<script src="<?php echo URL::to('assets/js/datepicker/jquery-ui.js')?>"></script>
<script src="<?php echo URL::to('assets/ckeditor/ckeditor.js'); ?>"></script>
  <script src="<?php echo URL::to('assets/ckeditor/src/js/main.js'); ?>"></script>
  <script src="<?php echo URL::to('assets/lightbox/jquery.colorbox.js'); ?>"></script>
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
  } ?>
</style>
</head>
<body class="body_bg">
<style>
.pdf_button{

}
.upload_img{
  position: absolute;
    top: 0px;
    z-index: 1;
    background: rgb(226, 226, 226);
    padding: 19% 0%;
    text-align: center;
    overflow: hidden;
}
.upload_text{
  font-size: 15px;
    font-weight: 800;
    color: #631500;
}
.fa-sort{
  cursor: pointer;
}
.modal_load {
    display:    none;
    position:   fixed;
    z-index:    99999;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 ) 
                url(<?php echo URL::to('assets/loading.gif'); ?>) 
                50% 50% 
                no-repeat;
}
.error_ref{
  color: #f00;
    font-size: 9px;
    position: absolute;
    left: 55.5%;
}
body.loading {
    overflow: hidden;
}
body.loading .modal_load {
    display: block;
}
.select_button table tbody tr td a{
    background: #000;
    text-align: center;
    padding: 8px 12px;
    color: #fff;
    float: left;
    width: 100%;
}
.select_button table tbody tr td a:hover{
    background: #000;
    text-align: center;
    padding: 8px 12px;
    color: #fff;
    float: left;
    width: 100%;
}
.select_button table tbody tr td label{
    color:#000 !important;
    font-weight:800;
    margin-top:6px;
}

.btn{
    background: #000;
    text-align: center;
    padding: 8px 12px;
    color: #fff;
    float: left;
    width: 100%;
}
.btn:hover{
    background: #000;
    text-align: center;
    padding: 8px 12px;
    color: #fff;
    float: left;
    width: 100%;
}
.drop_down{
  width: 100%;
margin-top: 2px;
background: none !important;
color: #000 !important;
border-bottom: 1px solid #dedada;
}
.dropdown-menu{
  right: 0px;
left: 79%;
top: 85%;
}
.finishmsg{
  color: #427405;
font-size: 17px;
font-weight: 800;
}
.page_title{
  font-size:18px !important;
  margin-bottom:8px !important;
}
</style>

<style>
.body_bg{
    background: #fff;
}
</style>


<div class="content_section" style="margin-top:0px">
  <p class="finishmsg"></p>
  <?php
    if(Session::has('message')) { ?>
           <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
  <?php } ?>
  <?php
  $taxnumber_compared = Session::get('alreadyinsertedrows');
  ?>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    <div class="page_title">
      Clients Without Email Address:
      
    </div>
  </div>
  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 col-lg-offset-4 col-md-offset-4 col-sm-offset-4 col-xs-offset-4">
      <a href="javascript:" class="btn btn-primary btn-sm pdf_button" id="pdf_without_email">Download PDF</a>
  </div>
  <div class="select_button">
        <table class="table table_bg">
          <thead>
            <tr class="background_bg">
                <th width="5%" style="text-align: left;">#</th>
                <th style="text-align: left; border:1px solid #000;">Client Name</th>
                <th style="text-align: left; border:1px solid #000">Tax Regn./Trader No</th>

            </tr>
          </thead>
          <tbody>
            <?php
                $i=1;
                if(count($without_emailed)){              
                  foreach($without_emailed as $without){       
                  if(in_array($without->taxnumber,$taxnumber_compared)) {                    
              ?>
            <tr style="text-align:left">
                <td width="5%" style="text-align: left;"><label><?php echo $i; ?></label></th>
                <td style="text-align: left; border:1px solid #000""><label><?php echo $without->name; ?></label></td>
                <td style="text-align: left; border:1px solid #000""><label><?php echo $without->taxnumber; ?></label></td>
            </tr>
            <?php
                $i++;      
                }                        
                }              
              }
              if($i == 1)
              {
                echo'<tr><td colspan="9" align="left">Empty</td></tr>';
              }
            ?>
            
          </tbody>            
        </table>
        
  </div>

  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    <div class="page_title">
      Disabled Clients
      
    </div>
  </div>
  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 col-lg-offset-4 col-md-offset-4 col-sm-offset-4 col-xs-offset-4">
      <a href="javascript:" class="btn btn-primary btn-sm pdf_button" id="pdf_disabled">Download PDF</a>
  </div>
  <div class="select_button">
    <table class="table table_bg">
      <thead>
        <tr class="background_bg">
            <th width="5%" style="text-align: left;">#</th>
            <th style="text-align: left; border:1px solid #000;">Client Name</th>
            <th style="text-align: left; border:1px solid #000">Tax Regn./Trader No</th>

        </tr>
      </thead>
      <tbody>
        <?php
            $i=1;
            if(count($disabled)){              
              foreach($disabled as $disable){    
              if(in_array($disable->taxnumber,$taxnumber_compared)) {                            
          ?>
        <tr style="text-align:left">
            <td width="5%" style="text-align: left;"><label><?php echo $i; ?></label></th>
            <td style="text-align: left; border:1px solid #000""><label><?php echo $disable->name; ?></label></td>
            <td style="text-align: left; border:1px solid #000""><label><?php echo $disable->taxnumber; ?></label></td>
        </tr>
        <?php
            $i++;  
            }                            
            }              
          }
          if($i == 1)
          {
            echo'<tr><td colspan="9" align="left">Empty</td></tr>';
          }
        ?>
        
      </tbody>            
    </table>
  </div>

  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    <div class="page_title">
      Clients that we Can Notify <br/>
      <input type="checkbox" name="select_all" id="select_all" value="1"> <label for="select_all">Select All</label><br/>
      <input type="checkbox" name="always_nill" id="always_nill" value="1"> <label for="select_all">Send Email to Always Nil Clients</label>
    </div>
  </div>
  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 col-lg-offset-4 col-md-offset-4 col-sm-offset-4 col-xs-offset-4">
      <a href="javascript:" class="btn btn-primary btn-sm pdf_button" id="pdf_with_email">Download PDF</a>
  </div>
  <div class="select_button">
    <table class="table table_bg">
      <thead>
        <tr class="background_bg">
            <th width="5%" style="text-align: left;">#</th>
            <th style="text-align: left; border:1px solid #000;"> OK to Send </th>
            <th style="text-align: left; border:1px solid #000;">Client Name</th>
            <th style="text-align: left; border:1px solid #000">Tax Regn./Trader No</th>
            <th style="text-align: left; border:1px solid #000">Email</th>
            <th style="text-align: left; border:1px solid #000">Secondary Email</th> 
            <th style="text-align: left; border:1px solid #000">Last Email Sent</th> 
        </tr>
      </thead>
      <tbody>
        <?php
            $i=1;
            if(count($with_emailed)){              
              foreach($with_emailed as $with){    
              if(in_array($with->taxnumber,$taxnumber_compared)) {                             
                $check_compare = DB::table('vat_clients_compare')->where('taxnumber',$with->taxnumber)->get();
                if(count($check_compare)) {
                  foreach($check_compare as $key => $compare)
                  {
                    ?>
                    <tr style="text-align:left; <?php if($key != 0) { echo 'display:none'; } ?>">
                        <td width="5%" style="text-align: left;"><label><?php echo $i; ?></label></th>
                        <td style="text-align: left; border:1px solid #000"">
                        <?php
                          if($with->always_nil == "yes") { $always_nil_check = 'always_nil_check'; } else { $always_nil_check = '';  }
                        ?>
                            <input type="checkbox" class="select_functioning <?php echo $always_nil_check; ?> functioningall functioning_<?php echo $with->client_id; ?>" name="select_functioning" id="<?php echo $with->client_id; ?>" value="1" style="opacity:1"> 
                            <input type="hidden" class="hidden_pemail" id="<?php echo $with->client_id; ?>" value="<?php echo $with->pemail; ?>">
                            <input type="hidden" class="hidden_semail" id="<?php echo $with->client_id; ?>" value="<?php echo $with->semail; ?>">
                            <input type="hidden" class="hidden_salutation" id="<?php echo $with->client_id; ?>" value="<?php echo $with->salutation; ?>">
                            <input type="hidden" class="hidden_self" id="<?php echo $with->client_id; ?>" value="<?php echo $with->self_manage; ?>">

                            <input type="hidden" class="hidden_period" id="<?php echo $with->client_id; ?>" value="<?php echo $compare->period; ?>">
                            <input type="hidden" class="hidden_duedate" id="<?php echo $with->client_id; ?>" value="<?php echo $compare->due_date; ?>">
                        </td>
                        <td style="text-align: left; border:1px solid #000""><label><?php echo $with->name; ?></label></td>
                        <td style="text-align: left; border:1px solid #000""><label><?php echo $with->taxnumber; ?></label></td>
                        <td style="text-align: left; border:1px solid #000""><label><?php echo $with->pemail; ?></label></td>
                        <td style="text-align: left; border:1px solid #000""><label><?php echo $with->semail; ?></label></td>
                        <td style="text-align: left; border:1px solid #000""><label><?php echo ($with->last_email_sent == "0000-00-00 00:00:00")?'-':date('d F Y', strtotime($with->last_email_sent)); ?></label></td>
                    </tr>
                  <?php
                     
                  }                        
                }   
                 $i++;             
              }
            }
          }
          if($i == 1)
          {
            echo'<tr><td colspan="9" align="left">Empty</td></tr>';
          }
          ?>
      </tbody>            
    </table>
    <br/>
    <input type="button" name="with_email_submit" id="with_email_submit" class="btn btn-primary" value="Send VAT Notifications" style="width:20%; margin-top:-20px !important">
  </div>
</div>
<div class="modal_load"></div>
<?php
if(Session::has('message_import')) { ?>
<script>
$(document).ready(function(){
  $(".import_excel_modal").modal("show");
});
</script>
<?php } ?>
<script>
function SaveToDisk_vat(fileURL, fileName) {
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
$(window).click(function(e) {  
  if(e.target.id == "select_all")
  {
    if($(e.target).is(":checked"))
    {
      $("#always_nill").prop("checked",false);
      $(".select_functioning").each(function() {
        $(this).prop("checked",true);
      });
    }
    else{
      $(".select_functioning").each(function() {
        $(this).prop("checked",false);
      });
    }
  }
  if(e.target.id == "always_nill")
  {
    if($(e.target).is(":checked"))
    {
      $("#select_all").prop("checked",false);

      $(".select_functioning").each(function() {
        $(this).prop("checked",false);
      });
      $(".always_nil_check").each(function() {
        $(this).prop("checked",true);
      });
    }
    else{
      $(".select_functioning").each(function() {
        $(this).prop("checked",false);
      });
      $(".always_nil_check").each(function() {
        $(this).prop("checked",false);
      });
    }
  }
  if(e.target.id == "pdf_without_email")
  {
    $("body").addClass("loading");
      $.ajax({
          url:"<?php echo URL::to('user/pdf_without_email'); ?>",
          type:"get",
          success: function(result)
          {
              $("body").removeClass("loading");
              SaveToDisk_vat("<?php echo URL::to('papers'); ?>/"+result,result);
          }
      });
  }
  if(e.target.id == "pdf_with_email")
  {
    $("body").addClass("loading");
    $.ajax({
          url:"<?php echo URL::to('user/pdf_with_email'); ?>",
          type:"get",
          success: function(result)
          {
                $("body").removeClass("loading");
              SaveToDisk_vat("<?php echo URL::to('papers'); ?>/"+result,result);
          }
      });
  }
  if(e.target.id == "pdf_disabled")
  {
    $("body").addClass("loading");
    $.ajax({
          url:"<?php echo URL::to('user/pdf_disabled'); ?>",
          type:"get",
          success: function(result)
          {
                $("body").removeClass("loading");
              SaveToDisk_vat("<?php echo URL::to('papers'); ?>/"+result,result);
          }
      });
  }
  if($(e.target).hasClass('functioningall'))
  {
    var id = $(e.target).attr("id");
    if($(e.target).is(":checked"))
    {
      $(".functioning_"+id).each(function() {
        $(this).prop("checked",true);
      });
    }
    else{
      $(".functioning_"+id).each(function() {
        $(this).prop("checked",false);
      });
    }
  }
  if($(e.target).hasClass('select_functioning'))
  {
    if($(e.target).is(":checked"))
    {
      
    }
    else{
      var lnth = $(".select_functioning:checked").length;
      if(lnth == 0)
      {
        $("#select_all").prop("checked",false);
      }
    }
  }
  if(e.target.id == "with_email_submit")
  {
    $("body").addClass("loading");
    var option_length = $(".select_functioning:checked").length;
    var emails = '';
    var timeval = "<?php echo time(); ?>";
    $(".select_functioning").each(function() {
      if($(this).is(":checked"))
      {
        var pemail = $(this).parent().find(".hidden_pemail").val();
        var semail = $(this).parent().find(".hidden_semail").val();
        if(emails == "")
        {
          emails = pemail+','+semail;
        }
        else{
          emails = emails+','+pemail+','+semail;
        }
      }
    });
    if(emails == "")
    {
      $("body").removeClass("loading");
      alert('Please select atleast one client to send the VAT Notification.');
    }
    $(".select_functioning").each(function(i, value) {
      if($(this).is(":checked"))
      {
          var pemail = $(this).parent().find(".hidden_pemail").val();
          var semail = $(this).parent().find(".hidden_semail").val();
          var salutation = $(this).parent().find(".hidden_salutation").val();
          var self_manage = $(this).parent().find(".hidden_self").val();
          var period = $(this).parent().find(".hidden_period").val();
          var due_date = $(this).parent().find(".hidden_duedate").val();
          var client_id = $(this).attr("id");
          setTimeout(function(){
              $.ajax({
                url:"<?php echo URL::to('user/email_vatnotifications'); ?>",
                type:"get",
                data:{pemail:pemail,semail:semail,salutation:salutation,self_manage:self_manage,period:period,due_date:due_date,emails:emails,client_id:client_id,timeval:timeval},
                success: function(result) {
                  var keyi = parseInt(i) + parseInt(1);
                  if(option_length <= keyi)
                  {
                    $("body").removeClass("loading");
                    $(document).scrollTop(0);  
                    $(".finishmsg").text("Email Sent Successfully!");
                  }
                }
              });
            },2000 + ( i * 2000 ));
      }
    });
  }
  if(e.target.id == "edit_client_details")
  {
    e.preventDefault();
    var pemail = $(".error_pemail").text();
    var semail = $(".error_semail").text();

    if(pemail!= "" || semail != "")
    {
      return false;
    }
    else{
      $("#form-validation-edit").submit();
    }
  }
  if($(e.target).hasClass('import_button')) {
    $(".import_modal").modal("show");
  }
  if($(e.target).hasClass('compare_button')) {
    $(".compare_modal").modal("show");
  }
  
  if($(e.target).hasClass('editclass')) {
    var editid = $(e.target).attr("id");
    console.log(editid);
    $.ajax({
        url: "<?php echo URL::to('user/edit_vat_clients') ?>"+"/"+editid,
        dataType:"json",
        type:"post",
        success:function(result){
           $(".bs-example-modal-sm").modal("toggle");
           $(".modal-content").css({"top":"90px"});
           $(".editsp").show();           
           $(".name_class").val(result['name']);           
           $(".taxnumber_class").val(result['taxnumber']);
           $(".pemail_class").val(result['pemail']);
           $(".semail_class").val(result['semail']);
           $(".salutation_class").val(result['salutation']);
           $(".name_id").val(result['id']);

            if(result['self_manage'] == 'yes')
            {
              $("#self_manage_class_yes").prop("checked",true);
            }
            else if(result['self_manage'] == 'no')
            {
              $("#self_manage_class_no").prop("checked",true);
            }
            else
            {
              $("#self_manage_class_yes").prop("checked",false);
              $("#self_manage_class_no").prop("checked",false);
            }
      }
    });
  }
});

//setup before functions
var typingTimer;                //timer identifier
var doneTypingInterval = 1000;  //time in ms, 5 second for example
var $input = $('.pemail_class');

//on keyup, start the countdown
$input.on('keyup', function () {
  var input_val = $(this).val();

  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping, doneTypingInterval,input_val);
});

//on keydown, clear the countdown 
$input.on('keydown', function () {
  clearTimeout(typingTimer);
});

//user is "finished typing," do something
function doneTyping (input) {
  var client_id = $(".name_id").val();
  var semail = $(".semail_class").val();
  $.ajax({
        url:"<?php echo URL::to('user/check_client_email'); ?>",
        type:"get",
        data:{email:input,client_id:client_id,type:"primary"},
        success: function(result) {
          if(result == 1)
          {
            $(".error_pemail").text('Email Already Exists');
          }
          else{
            $(".error_pemail").text('');
          }
          if(semail == input)
          {
            $(".error_pemail").text('Primary and Secondary Email Should Differ');
          }
        }
      });
}

//setup before functions
var stypingTimer;                //timer identifier
var sdoneTypingInterval = 1000;  //time in ms, 5 second for example
var $sinput = $('.semail_class');

//on keyup, start the countdown
$sinput.on('keyup', function () {
  var sinput_val = $(this).val();

  clearTimeout(stypingTimer);
  stypingTimer = setTimeout(sdoneTyping, sdoneTypingInterval,sinput_val);
});

//on keydown, clear the countdown 
$sinput.on('keydown', function () {
  clearTimeout(stypingTimer);
});

//user is "finished typing," do something
function sdoneTyping (input) {
  var client_id = $(".name_id").val();
  var pemail = $(".pemail_class").val();
  $.ajax({
        url:"<?php echo URL::to('user/check_client_email'); ?>",
        type:"get",
        data:{email:input,client_id:client_id,type:"secondary"},
        success: function(result) {
          if(result == 1)
          {
            $(".error_semail").text('Email Already Exists');
          }
          else{
            $(".error_semail").text('');
          }
          if(pemail == input)
          {
            $(".error_semail").text('Primary and Secondary Email Should Differ');
          }
        }
      });
}
</script>
</body>
</html>
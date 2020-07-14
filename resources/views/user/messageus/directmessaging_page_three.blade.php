@extends('userheader')
@section('content')
<style>
#client_table td, #client_table th{ text-align:left; background: #fff }
.inactive_client td { color:#f00; }
.messageus_attachment_a, #messageus_attachment_p{
  font-weight:700;
  text-decoration: underline;
  color:blue;
}
.inactive_client td { color:#f00; }
.table td, .table th{ text-align:left; background: #fff }
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
.fa-sort{ cursor:pointer; }
.error{
  color:#f00;
  float:right;
}
/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
.file_attachments{
	color: #002bff;
    text-decoration: underline;
    font-weight: 700;
}
.modal_load {
    display:    none;
    position:   fixed;
    z-index:    9999999999;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 ) 
                url(<?php echo URL::to('assets/images/loading.gif'); ?>) 
                50% 50% 
                no-repeat;
}
body.loading {
    overflow: hidden;   
}
body.loading .modal_load {
    display: block;
}
.file_attachment_div{width:100%;}
.dropzone .dz-preview.dz-image-preview {
    background: #949400 !important;
}
.dz-message span{text-transform: capitalize !important; font-weight: bold;}
.trash_imageadd{
  cursor:pointer;
}
.dropzone.dz-clickable .dz-message, .dropzone.dz-clickable .dz-message *{
      margin-top: 40px;
}
.dropzone .dz-preview {
  margin:0px !important;
  min-height:0px !important;
  width:100% !important;
  color:#000 !important;
  float: left;
  clear: both;
}
.dropzone .dz-preview p {
  font-size:12px !important;
}
.remove_dropzone_attach{
  color:#f00 !important;
  margin-left:10px;
}
.remove_dropzone_attach_add{
  color:#f00 !important;
  margin-left:10px;
}
.remove_notepad_attach_add{
  color:#f00 !important;
  margin-left:10px;
}
.remove__attach_add{
  color:#f00 !important;
  margin-left:10px;
}
.delete_all_image, .delete_all_notes_only, .delete_all_notes, .download_all_image, .download_rename_all_image, .download_all_notes_only, .download_all_notes{cursor: pointer;}
.img_div_add{
    border: 1px solid #000;
    width: 280px;
    position: absolute !important;
    min-height: 118px;
    background: rgb(255, 255, 0);
    display:none;
}
.readonly .slider{
  background: #dfdfdf !important;
}
.readonly .slider:before{
  background: #000 !important;
}
input:checked + .slider{
      background-color: #2196F3 !important;
}
.switch {
  background: #fff !important;
  position: relative;
  display: inline-block;
  width: 47px;
  height: 24px;
  float:left !important;
  margin-top: 4px;
}
.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 20px;
  left: 0px;
  bottom: 3px;
  background-color: red;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
<script src="<?php echo URL::to('ckeditor/ckeditor.js'); ?>"></script>
<script src="<?php echo URL::to('ckeditor/samples/js/sample.js'); ?>"></script>
<script src="<?php echo URL::to('assets/ckeditor/src/js/main1.js'); ?>"></script>

<link rel="stylesheet" href="<?php echo URL::to('ckeditor/samples/css/samples.css'); ?>">
<link rel="stylesheet" href="<?php echo URL::to('ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css'); ?> ">
<div class="modal fade send_message_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title">Select Username</h4>
          </div>
          <div class="modal-body" style="min-height: 100px;">  
            <div class="col-md-12">
              <h5>Select From Username:</h5>
            </div>
            <div class="col-md-12">
              <select name="select_from" class="form-control select_from">
                <option value="">Select User</option>        
                  <?php
                  $userlist = DB::table('user')->where('user_status', 0)->where('disabled',0)->orderBy('firstname','asc')->get();
                  if(count($userlist)){
                    foreach ($userlist as $user) {
                  ?>
                    <option value="<?php echo $user->user_id ?>"><?php echo $user->lastname.'&nbsp;'.$user->firstname; ?></option>
                  <?php
                    }
                  }
                  ?>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <input type="button" class="common_black_button send_message_button" id="send_message_button" value="Submit">
          </div>
        </div>
  </div>
</div>
<div class="content_section">
	<div id="fixed-header" style="width:100%;background: #fff;margin-top: -16px;">
	  <div class="page_title" style="z-index:999;margin-top:20px">
	  	<div class="row">
		    <div class="col-md-12 padding_00" style="text-align: center">
		      <label style="margin-left: 1%; text-align:center;font-size:20px">MessageUs - Central Client Messaging System</label>
		    </div>
		  </div>
	  </div>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item waves-effect waves-light active" style="width:20%;text-align: center">
        <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="false">
          <spam id="open_task_count">Direct Messaging</spam>
        </a>
      </li>
      <li class="nav-item waves-effect waves-light" style="width:20%;text-align: center">
        <a href="javascript:" class="nav-link" id="profile-tab">Invoice Distribution</a>
      </li>
      <li class="nav-item waves-effect waves-light" style="width:20%;text-align: center">
        <a href="<?php echo URL::to('user/messageus_groups'); ?>" class="nav-link" id="profile-tab">Groups</a>
      </li>
      <li class="nav-item waves-effect waves-light" style="width:20%;text-align: center">
        <a href="<?php echo URL::to('user/messageus_saved_messages'); ?>" class="nav-link" id="profile-tab">Saved Messages</a>
      </li>
    </ul>
  </div>
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane active in" id="home" role="tabpanel" aria-labelledby="home-tab">
      <div class="row" style="background: #c7c7c7;">
        <div class="col-md-6" style="padding:20px">
          <div class="col-md-12">
            <h5 style="font-weight:800">Message Summary:</h5>
          </div>
          <div class="col-md-12" style="margin-top:10px">
            <h5 style="font-weight:800">Message Subject: </h5>
            <input type="text" name="message_subject" class="form-control message_subject" value="<?php echo $message_details->subject; ?>" disabled style="background: #fff !important">
          </div>
          <div class="col-md-12" style="margin-top:20px">
            <h5 style="font-weight:800">Message Body: </h5>
            <div style="width:100%;background: #fff;min-height:500px;padding:10px">
              <?php echo $message_details->message; ?>
            </div>
          </div>
        </div>
        <div class="col-md-6" style="padding:20px">
          <div class="col-md-12" style="margin-top:20px">
            <h5 style="font-weight:800">Attached Files: </h5>
            <?php
            $fileoutput = '';
            $files = DB::table('messageus_files')->where('message_id',$_GET['message_id'])->get();
            if(count($files))
            {
              foreach($files as $file)
              {
                $fileoutput.="<div class='messageus_attachment' style='width:100%'><a href='".URL::to('/')."/".$file->url."/".$file->filename."' class='messageus_attachment_a' download>".$file->filename."</a></div>";
              }
            }
            echo $fileoutput;
            ?>
          </div>
          <div class="col-md-12" style="margin-top:20px">
            <h5 style="font-weight:800">Selected Clients: </h5>
            <div class="table-responsive" id="table_responsive_div" style="max-height: 500px;">
              <table class="table" id="client_table">
                <thead>
                  <tr>
                    <th>Code</th>
                    <th>Details</th>
                    <th>Primary Email</th>
                    <th>Secondary Email</th>
                  </tr>
                </thead>
                <tbody id="clients_tbody">
                  <?php
                  $clientoutput = '';
                  $clients = explode(",",$message_details->client_ids);
                  $primary = explode(",",$message_details->primary_emails);
                  $secondary = explode(",",$message_details->secondary_emails);
                  if(count($clients))
                  {
                    foreach($clients as $client)
                    {
                      $client_details = DB::table('cm_clients')->where('client_id',$client)->first();
                      if($client_details->active == "2")
                      {
                        $cls = 'inactive_client';
                      }
                      else{
                        $cls = 'active_client';
                      }
                      if(in_array($client_details->email,$primary))
                      {
                        $pri_email = $client_details->email;
                      }
                      else{
                        $pri_email = '';
                      }
                      if(in_array($client_details->email2,$secondary))
                      {
                        $sec_email = $client_details->email2;
                      }
                      else{
                        $sec_email = '';
                      }
                      $clientoutput.='<tr class="'.$cls.'">
                        <td>'.$client_details->client_id.'</td>
                        <td>'.$client_details->company.'</td>
                        <td>'.$pri_email.'</td>
                        <td>'.$sec_email.'</td>
                      </tr>';

                    }
                  }
                  echo $clientoutput;
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-md-12" style="padding:20px">
          <div class="col-md-12" style="margin-top:20px">
            <a href="<?php echo URL::to('user/directmessaging?message_id='.$_GET['message_id']); ?>" class="common_black_button" style="font-size:20px;float:right;width:20%"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back to Message Screen</a><br/><br/><br/>
            <a href="javascript:" class="common_black_button send_message_later" style="font-size:20px;float:right;width:20%">Send Message Later <i class="fa fa-arrow-right" aria-hidden="true"></i></a><br/><br/><br/>
            <a href="javascript:" class="common_black_button send_message_now" style="font-size:20px;float:right;width:20%">Send Message Now</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal_load"></div>
<?php
  $message_details = DB::table('messageus')->where('id',$_GET['message_id'])->first();
?>
<script>
var table_heigth = $("#client_table").height();
if(table_heigth > 500)
{
  $("#table_responsive_div").css("overflow-y","scroll");
}
$(function(){
  $.ajaxSetup({async:false});
    $('#client_table').DataTable({
        fixedHeader: {
          headerOffset: 75
        },
        autoWidth: true,
        scrollX: false,
        fixedColumns: false,
        searching: false,
        paging: false,
        info: false
    });
});
$('body').on('hidden.bs.popover', function (e) {
    $(e.target).data("bs.popover").inState = { click: false, hover: false, focus: false }
});
$('html').on('click', function(e) {
  if (typeof $(e.target).data('original-title') == 'undefined' && !$(e.target).parents().is('.popover.in')) {
    $('[data-original-title]').popover('hide');
  }
});
$(window).change(function(e){
  if($(e.target).hasClass('select_from'))
  {
    var from = $(e.target).val();
    $.ajax({
      url:"<?php echo URL::to('user/choose_messageus_from'); ?>",
      type:"post",
      data:{from:from,message_id:"<?php echo $_GET['message_id']; ?>"},
      success:function(result)
      {
        
      }
    })
  }
})
function sendotheremails(message_id,from,emailval)
{
  var emails = emailval.split(",");
  if(emailval != "")
  {
    var firstemail = emails[0];
    $.ajax({
      url:"<?php echo URL::to('user/send_message_now'); ?>",
      type:"post",
      data:{message_id:message_id,from:from,email:firstemail},
      success: function(result) {
        emails.splice(0, 1);
        var otheremails = emails.join(',');

        if(otheremails == "")
        {
          $("body").removeClass("loading");
          window.location.replace("<?php echo URL::to('user/directmessaging?mail_send=1'); ?>");
        }
        else{
          sendotheremails(message_id,from,otheremails);
        }
      }
    });
  }
}
$(window).click(function(e) {
  if($(e.target).hasClass('send_message_later'))
  {
      $("body").addClass("loading");
      setTimeout(function() {
        $.ajax({
          url:"<?php echo URL::to('user/send_message_later'); ?>",
          type:"post",
          data:{message_id:"<?php echo $_GET['message_id']; ?>"},
          success:function(result)
          {
            window.location.replace("<?php echo URL::to('user/directmessaging?mail_saved=1'); ?>");
          }
        })
      },1000);
  }
  if($(e.target).hasClass('send_message_now'))
  {
      $(".send_message_modal").modal("show");
  }
  if($(e.target).hasClass('send_message_button'))
  {
    var message_id = "<?php echo $_GET['message_id']; ?>";
    var primary_emails = "<?php echo $message_details->primary_emails; ?>";
    var secondary_emails = "<?php echo $message_details->secondary_emails; ?>";
    var emailval = '';
    var from = $(".select_from").val();
    if(from == "")
    {
      alert("Please select the user from dropdown");
    }
    else{
      $("body").addClass("loading");
      setTimeout(function() {
        if(primary_emails != "")
        {
          if(emailval == "")
          {
            emailval = primary_emails;
          }
          else{
            emailval = emailval+','+primary_emails;
          }
        }

        if(secondary_emails != "")
        {
          if(emailval == "")
          {
            emailval = secondary_emails;
          }
          else{
            emailval = emailval+','+secondary_emails;
          }
        }

        var emails = emailval.split(",");
        if(emailval != "")
        {
          var firstemail = emails[0];
          $.ajax({
            url:"<?php echo URL::to('user/send_message_now'); ?>",
            type:"post",
            data:{message_id:message_id,from:from,email:firstemail},
            success: function(result) {
              emails.splice(0, 1);
              var otheremails = emails.join(',');

              if(otheremails == "")
              {
                $("body").removeClass("loading");
                window.location.replace("<?php echo URL::to('user/directmessaging?mail_send=1'); ?>");
              }
              else{
                sendotheremails(message_id,from,otheremails);
              }
            }
          });
        }
      },1000);
    }
  }
  if($(e.target).hasClass('add_notes_attachment'))
  {
    $("body").addClass("loading");
    setTimeout(function() {
      if (CKEDITOR.instances.editor_1) CKEDITOR.instances.editor_1.destroy();
      var fileid = $(e.target).attr("data-task");
      var filename = $(e.target).parents(".messageus_attachment").find(".messageus_attachment_a").html();
      $("#attachment_content_div").show();
      $("#attachment_content_title").html(filename+" COMMENT:");
      $(".attachment_content").attr("data-element",fileid);

      CKEDITOR.replace('editor_1',
       {
        height: '100px',
        enterMode: CKEDITOR.ENTER_BR,
        shiftEnterMode: CKEDITOR.ENTER_P,
        autoParagraph: false,
       });

      $.ajax({
        url:"<?php echo URL::to('user/get_attachment_notes'); ?>",
        type:"post",
        data:{fileid:fileid,type:"0"},
        success:function(result)
        {
          CKEDITOR.instances['editor_1'].setData(result);
          $("body").removeClass("loading");
        }
      })
    },1000);
  }
  if($(e.target).hasClass('notes_attachment'))
  {
    $("body").addClass("loading");
    setTimeout(function() {
      if (CKEDITOR.instances.editor_1) CKEDITOR.instances.editor_1.destroy();
      var fileid = $(e.target).attr("data-task");
      var filename = $(e.target).parents(".messageus_attachment").find(".messageus_attachment_a").html();
      $("#attachment_content_div").show();
      $("#attachment_content_title").html(filename+" COMMENT:");
      $(".attachment_content").attr("data-element",fileid);

      CKEDITOR.replace('editor_1',
       {
        height: '100px',
        enterMode: CKEDITOR.ENTER_BR,
        shiftEnterMode: CKEDITOR.ENTER_P,
        autoParagraph: false,
       });

      $.ajax({
        url:"<?php echo URL::to('user/get_attachment_notes'); ?>",
        type:"post",
        data:{fileid:fileid,type:"1"},
        success:function(result)
        {
          CKEDITOR.instances['editor_1'].setData(result);
          $("body").removeClass("loading");
        }
      })
    },1000);
  }
  if($(e.target).hasClass('remove_dropzone_attach'))
  {
    var file_id = $(e.target).attr("data-task");
    $.ajax({
      url:"<?php echo URL::to('user/message_remove_dropzone_attachment'); ?>",
      type:"post",
      data:{file_id:file_id},
      success: function(result)
      {
        $(e.target).parents(".messageus_attachment").detach();
      }
    })
  }

  if($(e.target).hasClass('remove_dropzone_attach_add'))
  {
    var file_id = $(e.target).attr("data-task");
    $.ajax({
      url:"<?php echo URL::to('user/messageus_remove_dropzone_attachment'); ?>",
      type:"post",
      data:{file_id:file_id},
      success: function(result)
      {
        $("#add_attachments_div").html(result);
      }
    })
  }
});

var valueTimmer;                //timer identifier
var valueInterval = 1000;  //time in ms, 5 second for example
CKEDITOR.on('instanceCreated', function (e) {
  e.editor.on('contentDom', function() {
      e.editor.document.on('keyup', function(event) {
          clearTimeout(valueTimmer);
          var value = CKEDITOR.instances['editor_1'].getData();
          valueTimmer = setTimeout(doneTyping, valueInterval,value);
      });
  });
});
function doneTyping(textarea) {
    var fileid = $(".attachment_content").attr("data-element");
    <?php
    if(isset($_GET['message_id']))
    {
      $type = "1";
    }
    else{
      $type = "0";
    }
    ?>
    $.ajax({
      url:"<?php echo URL::to('user/messageus_add_comment_to_attachment'); ?>",
      type:"post",
      data:{fileid:fileid,textarea:textarea,type:"<?php echo $type; ?>"},
      success:function(result)
      {

      }
    })
}

fileList = new Array();
Dropzone.options.imageUpload1 = {
    maxFiles: 2000,
    acceptedFiles: null,
    maxFilesize:500000,
    timeout: 10000000,
    dataType: "HTML",
    parallelUploads: 1,
    maxfilesexceeded: function(file) {
        this.removeAllFiles();
        this.addFile(file);
    },
    init: function() {
        this.on('sending', function(file) {
            $("body").addClass("loading");
        });
        this.on("drop", function(event) {
            $("body").addClass("loading");        
        });
        this.on("success", function(file, response) {
            var obj = jQuery.parseJSON(response);
            file.serverId = obj.id; // Getting the new upload ID from the server via a JSON response
            if(obj.id != 0)
            {
              file.previewElement.innerHTML = "<div class='messageus_attachment' style='width:100%'><a href='<?php echo URL::to('/'); ?>/"+obj.attachment+"' class='messageus_attachment_a' download>"+obj.filename+"</a> <a href='javascript:' class='fa fa-trash remove_dropzone_attach' data-task='"+obj.file_id+"'></a> <a href='javascript:' class='fa fa-text-width notes_attachment' data-task='"+obj.file_id+"'></a></div>";
            }
            else{
              $("#attachments_text").show();
              $("#add_attachments_div").append("<div class='messageus_attachment' style='width:100%'><a href='<?php echo URL::to('/'); ?>/"+obj.attachment+"' class='messageus_attachment_a' download>"+obj.filename+"</a> <a href='javascript:' class='fa fa-trash remove_dropzone_attach_add' data-task='"+obj.file_id+"'></a> <a href='javascript:' class='fa fa-text-width add_notes_attachment' data-task='"+obj.file_id+"'></a></div>");
            }
        });
        this.on("complete", function (file) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            var acceptedcount= this.getAcceptedFiles().length;
            var rejectedcount= this.getRejectedFiles().length;
            var totalcount = acceptedcount + rejectedcount;
            $("#total_count_files").val(totalcount);
            $("body").removeClass("loading");
            Dropzone.forElement("#imageUpload1").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
          }
          var pcount = $(".messageus_attachment").length;
          if(pcount > 8)
          {
            $(".view_attachments").show();

            $("[data-toggle=popover]").popover({
                html : true,
                content: function() {
                  var content = $(this).attr("data-popover-content");
                  return $(content).children(".popover-body").html();
                },
                title: function() {
                  var title = $(this).attr("data-popover-content");
                  return $(title).children(".popover-heading").html();
                }
            });
          }
          else{
            $(".view_attachments").hide();
          }
        });
        this.on("error", function (file) {
            $("body").removeClass("loading");
        });
        this.on("canceled", function (file) {
            $("body").removeClass("loading");
        });
        this.on("removedfile", function(file) {
            if (!file.serverId) { return; }
            $.get("<?php echo URL::to('user/remove_property_images'); ?>"+"/"+file.serverId);
        });
    },
};
</script>
@stop

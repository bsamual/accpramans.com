@extends('userheader')
@section('content')
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/jquery.dataTables.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/fixedHeader.dataTables.min.css'); ?>">

<script src="<?php echo URL::to('assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo URL::to('assets/js/dataTables.fixedHeader.min.js'); ?>"></script>

<script src="<?php echo URL::to('assets/js/jquery.form.js'); ?>"></script>
<script src="http://html2canvas.hertzen.com/dist/html2canvas.js"></script>

<link rel="stylesheet" href="<?php echo URL::to('assets/js/lightbox/colorbox.css'); ?>">
<script src="<?php echo URL::to('assets/js/lightbox/jquery.colorbox.js'); ?>"></script>
<style>
body{
  background: #f5f5f5 !important;
}

.label_class{
  float:left ;
  margin-top:15px;
  font-weight:700;
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


.form-control[readonly]{
      background-color: #fff !important
}
.formtable tr td{
  padding-left: 15px;
  padding-right: 15px;
}
.fullviewtablelist>tbody>tr>td{
  font-weight:800 !important;
  font-size:15px !important;
}
.fullviewtablelist>tbody>tr>td a{
  font-weight:800 !important;
  font-size:15px !important;
}
.modal { overflow: auto !important;z-index: 999999;}
.pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover
{
  z-index: 0 !important;
}

.label_class{
  float:left ;
  margin-top:15px;
  font-weight:700;
}


.modal_load {
    display:    none;
    position:   fixed;
    z-index:    999999;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 ) 
                url(<?php echo URL::to('assets/images/loading.gif'); ?>) 
                50% 50% 
                no-repeat;
}

.report_div{
    position: absolute;
    top: 55%;
    left:20%;
    padding: 15px;
    background: #ff0;
    z-index: 999999;
    text-align: left;
}
.selectall{padding:10px 15px; background-image:linear-gradient(to bottom,#f5f5f5 0,#e8e8e8 100%); background: #f5f5f5; border:1px solid #ddd; border-radius: 3px;  }

.report_ok_button{background: #000; text-align: center; padding: 6px 12px; color: #fff; float: left; border: 0px; font-size: 13px; }
.report_ok_button:hover{background: #5f5f5f; text-decoration: none; color: #fff}
.report_ok_button:focus{background: #000; text-decoration: none; color: #fff}

.form-title{width: 100%; height: auto; float: left; margin-bottom: 5px;}

.fa-plus-add, .trash_imageadd, .fa-plus-edit, .trash_imageedit{cursor: pointer;}
.img_div_add, .img_div_edit{margin-left: 11px;}

.add_padding{padding-bottom: 270px;}

.dz-default{margin-top: 50px !important;}

.img_div_add{

        border: 1px solid #000;

    width: 270px;

    position: inherit !important;

    min-height: 100px;

    background: rgb(255, 255, 0);

    display:block;
    float: left

}

.img_div_edit{

        border: 1px solid #000;

    width: 270px;

    position: inherit !important;

    min-height: 100px;

    background: rgb(255, 255, 0);

    display:block;
    float: left

}

body.loading {
    overflow: hidden;   
}
body.loading .modal_load {
    display: block;
}
    .table thead th:focus{background: #ddd !important;}
    .form-control{border-radius: 0px;}
    .disabled{cursor :auto !important;pointer-events: auto !important}
    body #coupon {
      display: none;
    }
    @media print {
      body * {
        display: none;
      }
      body #coupon {
        display: block;
      }
    }
</style>
<script>
function popitup(url) {
    newwindow=window.open(url,'name','height=600,width=1500');
    if (window.focus) {newwindow.focus()}
    return false;
}

</script>

<style>
.error{color: #f00; font-size: 12px;}
a:hover{text-decoration: underline;}
</style>
<div class="modal fade add_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <form action="<?php echo URL::to('user/year_setting_create')?>" method="post" class="add_new_form"> 
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Create Year End Document Type</h4>
          </div>
          <div class="modal-body add_new_body">

              <!-- <div class="form-group" style="width:80%">
                  <div class="form-title">Enter CRYPT PIN:</div>
                  <input type="password" class="form-control crypt_pin" value="" placeholder="Enter CRYPT Pin" name="crypt_pin" required>
                  <label class="error crypt_error" style="display:none"></label>
              </div> -->


              <div class="form-group-add" style="width:80%">
                
              </div>
              
              <p id="attachments_text" style="font-weight: bold;">Default Attachment:</p>

              <div id="add_attachments_div">
              </div>
              <div class="form-group start_group">
                <i class="fa fa-plus fa-plus-add" style="margin-top:10px;" aria-hidden="true" title="Add Attachment"></i> 
                <i class="fa fa-trash trash_imageadd" data-element="" style="margin-left: 10px;" aria-hidden="true"></i>
              </div>
              
          </div>

          <div class="modal-footer">
              <!-- <input type="button" class="common_black_button crypt_button_add" value="Submit" style="margin-top: 15px;"> -->
              <input type="submit" class="common_black_button create_settin" value="Create Document Type">    
          </div>
        </form>   
        
          <div class="img_div img_div_add" style="z-index:9999999; min-height: 214px;display:none; float: left;">
            <form name="image_form" id="image_form" action="" method="post" enctype="multipart/form-data" style="text-align: left;">
            </form>
            <div class="image_div_attachments">
              <form action="<?php echo URL::to('user/yearend_upload_images_add'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                  <input name="_token" type="hidden" value="">
              </form>                
            </div>
           </div> 
        
      </div>
  </div>
</div>
<div class="modal fade edit_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <form action="<?php echo URL::to('user/year_setting_update')?>" method="post"> 
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Edit Document type</h4>
          </div>
          <div class="modal-body add_edit_body">

              <!-- <div class="form-group">
                  <div class="form-title">Enter CRYPT PIN:</div>
                  <input type="password" class="form-control crypt_pin_edit" value="" placeholder="Enter CRYPT Pin" name="crypt_pin" required>
                  <label class="error crypt_error" style="display:none"></label>   
              </div> -->

              <div class="form-group-edit">
                
              </div>              
              

             <p id="attachments_text_edit" style="font-weight: bold;">Default Attached:</p>

              <div id="add_attachments_div_edit">
              </div>
              <div class="form-group start_group_edit">
                <i class="fa fa-plus fa-plus-edit" style="margin-top:10px;" aria-hidden="true" title="Add Attachment"></i> 
                <i class="fa fa-trash trash_imageedit" data-element="" style="margin-left: 10px;" aria-hidden="true"></i>
                
              </div>     
          </div>
          <div class="modal-footer">
              <input type="hidden" value="" class="id_class" name="id">
              <!-- <input type="button" class="common_black_button crypt_button_edit" value="Submit" style="margin-top: 15px;"> -->
              <input type="submit" class="common_black_button create_settin_edit" value="Update Document Type">    
          </div>
        </form>
        <div class="img_div img_div_edit" style="z-index:9999999; min-height: 214px;display:none">
          <form name="image_form" id="image_form_edit" action="" method="post" enctype="multipart/form-data" style="text-align: left;">
          </form>
          <div class="image_div_attachments_edit">
            <form action="<?php echo URL::to('user/yearend_upload_images_edit'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload1" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                <input name="_token" type="hidden" value="">
                <input type="hidden" name="hidden_year_setting_id" id="hidden_year_setting_id" value="">
            </form>                
          </div>
         </div>
      </div>
  </div>
</div>




<div class="content_section" style="margin-bottom:200px">
  <div class="page_title" style="margin-bottom: 2px;">
            <div class="col-lg-12 col-md-12 col-sm-12 select_button" style="padding-right: 0px;text-align: center">
                Year End Document Type Management
                <ul style="float: right;">                                
                  <li><a href="javascript:" class="add_new" style="font-size: 13px; font-weight: 500;">Create Year End Document Type</a></li>                
              </ul>
            </div>

  <div style="clear: both;">
   <?php
    if(Session::has('message')) { ?>
        <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>

    
    <?php } ?>
    </div> 


</div>
<style type="text/css">
.table tr td, tr th{font-size: 15px;}
</style>
<div class="col-lg-12">
  <div class="table-responsive">
    <table class="table" style="font-size: 15px;">
      <thead>
        <tr>
          <th width="50px" style="text-align: left">S.No</th>
          <th width="400px" style="text-align: left">Name</th>
          <th style="text-align: left">Introduction</th>
          <th style="text-align: left">File</th>
          <th style="text-align: left">Check to disable</th>
          <th width="200px" style="text-align: left">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $output='';
        $i=1;
        if(count($setting_list)){
          foreach ($setting_list as $setting) {
            if($setting->active == 1){
              $checked = 'checked';
            }
            else{
              $checked ='';
            }

            $attachments = DB::table('year_setting_attachment')->where('year_setting_id', $setting->id)->get();

            $attachmentsname='';

            if(count($attachments)){
              foreach ($attachments as $single) {
                $file_ext = explode('.',$single->attachment);
                $file_ext_count=count($file_ext);
                $cnt=$file_ext_count-1;
                $ext= $file_ext[$cnt];

                $icon = '';
                if($ext == "pdf") { $icon = '<img src="'.URL::to('assets/images/pdf.png').'" width="24px" height="24px" data-element='.URL::to($single->url.'/'.$single->attachment).' class="attachmentsdownload"> '; }
                elseif($ext == "doc") { $icon = '<img src="'.URL::to('assets/images/word.png').'" width="24px" height="24px" data-element='.URL::to($single->url.'/'.$single->attachment).' class="attachmentsdownload"> '; }
                elseif($ext == "docx") { $icon = '<img src="'.URL::to('assets/images/word.png').'" width="24px" height="24px" data-element='.URL::to($single->url.'/'.$single->attachment).' class="attachmentsdownload"> '; }
                elseif($ext == "xls") { $icon = '<img src="'.URL::to('assets/images/excel.png').'" width="24px" height="24px" data-element='.URL::to($single->url.'/'.$single->attachment).' class="attachmentsdownload"> '; }
                elseif($ext == "xlsx") { $icon = '<img src="'.URL::to('assets/images/excel.png').'" width="24px" height="24px" data-element='.URL::to($single->url.'/'.$single->attachment).' class="attachmentsdownload"> '; }
                elseif($ext == "csv") { $icon = '<img src="'.URL::to('assets/images/excel.png').'" width="24px" height="24px" data-element='.URL::to($single->url.'/'.$single->attachment).' class="attachmentsdownload"> '; }
                elseif($ext == "jpeg") { $icon = '<img src="'.URL::to('assets/images/image.png').'" width="24px" height="24px" data-element='.URL::to($single->url.'/'.$single->attachment).' class="attachmentsdownload"> '; }
                elseif($ext == "png") { $icon = '<img src="'.URL::to('assets/images/image.png').'" width="24px" height="24px" data-element='.URL::to($single->url.'/'.$single->attachment).' class="attachmentsdownload"> '; }
                elseif($ext == "jpg") { $icon = '<img src="'.URL::to('assets/images/image.png').'" width="24px" height="24px" data-element='.URL::to($single->url.'/'.$single->attachment).' class="attachmentsdownload"> '; }
                elseif($ext == "gif") { $icon = '<img src="'.URL::to('assets/images/image.png').'" width="24px" height="24px" data-element='.URL::to($single->url.'/'.$single->attachment).' class="attachmentsdownload"> '; }
                elseif($ext == "bmp") { $icon = '<img src="'.URL::to('assets/images/image.png').'" width="24px" height="24px" data-element='.URL::to($single->url.'/'.$single->attachment).' class="attachmentsdownload"> '; }
                elseif($ext == "tiff") { $icon = '<img src="'.URL::to('assets/images/image.png').'" width="24px" height="24px" data-element='.URL::to($single->url.'/'.$single->attachment).' class="attachmentsdownload"> '; }
                elseif($ext == "txt") { $icon = '<img src="'.URL::to('assets/images/text.png').'" width="24px" height="24px" data-element='.URL::to($single->url.'/'.$single->attachment).' class="attachmentsdownload"> '; }
                else{ $icon = '<img src="'.URL::to('assets/images/file.png').'" width="24px" height="24px" data-element='.URL::to($single->url.'/'.$single->attachment).' class="attachmentsdownload"> '; }

                $attachmentsname.='<a href="javascript:" title="'.$single->attachment.'" data-element='.URL::to($single->url.'/'.$single->attachment).' class="attachmentsdownload">'.$icon.'</a>';
              }
            }
            else{
              $attachmentsname.='N/A';
            }



            $output.='            
            <tr>
              <td>'.$i.'</td>
              <td>'.$setting->document.'</td>
              <td>'.$setting->introduction.'</td>
              <td>'.$attachmentsname.'</td>
              <td><input type="checkbox" class="active_checkbox" data-element="'.$setting->id.'" '.$checked.'  /><label>&nbsp;</label></td>
              <td align="center" style="text-align: left"><a href="javascript:" class="edit_setting" data-element="'.$setting->id.'">Edit</a></td>
            </tr>';
            $i++;
          }
        }
        else{
          $output.='<tr><td align="center" colspan="4">Empty</td></tr>';
        }
        echo $output;
        ?>        
      </tbody>
    </table>
  </div>
</div>

    <!-- End  -->

<div class="main-backdrop"><!-- --></div>




<div class="modal_load"></div>
<input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
<input type="hidden" name="show_alert" id="show_alert" value="">
<input type="hidden" name="pagination" id="pagination" value="1">





<script>
$(".image_file_add").change(function(){
  var lengthval = $(this.files).length;
  var htmlcontent = '';
  var attachments = $('#add_attachments_div').html();
  for(var i=0; i<= lengthval - 1; i++)
  {
    var sno = i + 1;
    if(htmlcontent == "")
    {
      htmlcontent = '<p class="attachment_p">'+this.files[i].name+'</p>';
    }
    else{
      htmlcontent = htmlcontent+'<p class="attachment_p">'+this.files[i].name+'</p>';
    }
  }
  $('#add_attachments_div').html(attachments+' '+htmlcontent);
  $("#attachments_text").show();
  $(".img_div").hide();
});

$(".image_file_edit").change(function(){
  var lengthval = $(this.files).length;
  var htmlcontent = '';
  var attachments = $('#add_attachments_div_edit').html();
  for(var i=0; i<= lengthval - 1; i++)
  {
    var sno = i + 1;
    if(htmlcontent == "")
    {
      htmlcontent = '<p class="attachment_p">'+this.files[i].name+'</p>';
    }
    else{
      htmlcontent = htmlcontent+'<p class="attachment_p">'+this.files[i].name+'</p>';
    }
  }
  $('#add_attachments_div_edit').html(attachments+' '+htmlcontent);
  $("#attachments_text_edit").show();
  $(".img_div_edit").hide();
});
$(window).click(function(e) {
  if($(e.target).hasClass("delete_file"))
  {
    var r = confirm("Are you sure you want to delete this file? ");
    if(r)
    {
      var value= $(e.target).attr("data-element");
      $.ajax({
        url:"<?php echo URL::to('user/remove_year_setting_attachment'); ?>",
        data:{id:value},
        type:"post",
        success: function(result)
        {
          $(e.target).parent().detach();
        }
      })
    }
  }
  if($(e.target).hasClass('fa-plus-add'))
  {
    var leftpos = e.pageX;
    var toppos = e.pageY;

    var posX = $(e.target).offset().left,
    posY = $(e.target).offset().top;

    leftpos = leftpos - posX;
    toppos = toppos - (posY + 360);

    

    $('.img_div_add').toggle();
    $('.add_new_body').toggleClass('add_padding');
    $(".img_div_add").css({"position":"absolute","top":toppos,"left":leftpos});
    Dropzone.forElement("#imageUpload").removeAllFiles(true);
    $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
  }



  if($(e.target).hasClass('fa-plus-edit'))

  {

    var leftpos = e.pageX;
    var toppos = e.pageY;

    var posX = $(e.target).offset().left,
    posY = $(e.target).offset().top;

    leftpos = leftpos - posX;
    toppos = toppos - (posY + 360);

    $('.img_div_edit').toggle();
    $('.add_edit_body').toggleClass('add_padding');
    $(".img_div_edit").css({"position":"absolute","top":toppos,"left":leftpos});
    Dropzone.forElement("#imageUpload1").removeAllFiles(true);
    $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");    

  }

if($(e.target).hasClass('add_new')){
    $(".add_modal").modal("show"); 
    
    $(".img_div_add").hide();
    $(".add_new_body").removeClass('add_padding');

    $.ajax({
      url:"<?php echo URL::to('user/yearend_crypt_setting_add')?>",
      type:"post",
      dataType:"json",
      data:{type:0},
      success:function(result){
          $(".form-group-add").show();
          $(".form-group-add").html(result['form_details']);
          $(".button_submit").html(result['create_button']);
          $(".crypt_button_add").hide();
          $(".button_submit").show();

          $("#attachments_text").show();
          $("#add_attachments_div").show();
          $(".start_group").show();
          $(".create_settin").show();
      }
    })

}


if($(e.target).hasClass('active_checkbox'))
  {
  var id = $(e.target).attr('data-element');
  if($(e.target).is(':checked'))
  {
    $.ajax({
      url:"<?php echo URL::to('user/active_checkbox')?>",
      type:"post",
      data:{active:1,id:id},
      success: function(result) {
      }
    });
  }
  else{
    $.ajax({
      url:"<?php echo URL::to('user/active_checkbox')?>",
      type:"post",
      data:{active:2,id:id},
      success: function(result) {
      }
    });
  }
}

if($(e.target).hasClass('edit_setting')){
  var id = $(e.target).attr('data-element');  
  $(".add_edit_body").removeClass('add_padding');
  $.ajax({
    url:"<?php echo URL::to('user/yearend_crypt_setting_add')?>",
    type:"post",
    dataType:"json",
    data:{type:1,id:id},
    success:function(result){
        $(".edit_modal").modal("show");
        $(".id_class").val(id);
        $(".form-group-edit").show();
        $(".form-group-edit").html(result['form_details']);
        $(".button_submit_edit").html(result['create_button']);
        $("#add_attachments_div_edit").html(result['attachments']);
        $(".button_submit_edit").show();
        $(".img_div_edit").hide();
        $("#hidden_year_setting_id").val(id);

        $("#attachments_text_edit").show();
        $("#add_attachments_div_edit").show();
        $(".start_group_edit").show();
        $(".create_settin_edit").show();
    }
  });
}


if($(e.target).hasClass('crypt_button_add')){
  var crypt = $(".crypt_pin").val();
  if(crypt == "" || typeof crypt === "undefined")
  {
    alert("Please Enter CRYPT PIN");
    return false;
  }
  else{
    
  } 
}


if($(e.target).hasClass('attachmentsdownload')){
    e.preventDefault();
    var element = $(e.target).attr('data-element');
    $('body').addClass('loading');
    setTimeout(function(){
      SaveToDisk(element,element.split('/').reverse()[0]);
      $('body').removeClass('loading');
      }, 3000);
    return false; 
}
if($(e.target).hasClass('image_file'))

  {

    $(e.target).parents('td').find('.img_div').toggle();

    $(e.target).parents('.modal-body').find('.img_div').toggle();

  }

  if($(e.target).hasClass('image_file_add'))

  {

    $(e.target).parents('.modal-body').find('.img_div').toggle();

  }

  if($(e.target).hasClass("dropzone"))

  {

    $(e.target).parents('td').find('.img_div').show();    

    $(e.target).parents('.modal-body').find('.img_div').show();    

  }

  if($(e.target).hasClass("remove_dropzone_attach"))

  {

    $(e.target).parents('td').find('.img_div').show();   

    $(e.target).parents('.modal-body').find('.img_div').show(); 

  }

  if($(e.target).parent().hasClass("dz-message"))

  {

    $(e.target).parents('td').find('.img_div').show();

    $(e.target).parents('.modal-body').find('.img_div').show();    

  }
  if($(e.target).hasClass('trash_imageadd'))

  {
    var r = confirm("Are you sure tou want to clear all the attachments?");
    if(r){

      $("body").addClass("loading");

      $.ajax({

        url:"<?php echo URL::to('user/clear_session_attachments'); ?>",

        type:"post",

        success: function(result)

        {

          $("#add_notepad_attachments_div").html('');

          $("#add_attachments_div").html('');

          $("body").removeClass("loading");

        }

      })
    }

  }

  if($(e.target).hasClass('trash_imageedit'))

  {
    var r = confirm("Are you sure tou want to delete all the attachments?");
    if(r){
      var year_setting_id = $(".id_class").val();
      $("body").addClass("loading");

      $.ajax({

        url:"<?php echo URL::to('user/remove_all_attachments'); ?>",

        data:{year_setting_id:year_setting_id},

        type:"post",

        success: function(result)

        {

          $("#add_attachments_div_edit").html('');

          $("body").removeClass("loading");

        }

      });
    }
  }

  if($(e.target).hasClass('fileattachment'))

  {

    e.preventDefault();

    var element = $(e.target).attr('data-element');

    $('body').addClass('loading');

    setTimeout(function(){

      SaveToDisk(element,element.split('/').reverse()[0]);

      $('body').removeClass('loading');

      }, 3000);

    return false; 

  }

  if($(e.target).hasClass('remove_dropzone_attach'))

  {

    var attachment_id = $(e.target).attr("data-element");

    var file_id = $(e.target).attr("data-task");

    $.ajax({

      url:"<?php echo URL::to('user/infile_remove_dropzone_attachment'); ?>",

      type:"post",

      data:{attachment_id:attachment_id,file_id:file_id},

      success: function(result)

      {

        var countval = $(e.target).parents(".dropzone").find(".dz-preview").length;

        if(countval == 1)

        {

          $(e.target).parents(".dropzone").removeClass("dz-started");

        }

        $(e.target).parents(".dz-preview").detach();

        

      }

    })

  }



});

$(".add_modal").keypress(function(e) {
  if (e.which == 13 && !$(e.target).is("textarea")) {
    return false;
  }
});
</script>

<script>
fileList = new Array();
Dropzone.options.imageUpload = {
    addRemoveLinks: true,
    maxFilesize:50,
    acceptedFiles: null,
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
              file.previewElement.innerHTML = "<p>"+obj.filename+" <a href='javascript:' class='remove_dropzone_attach' data-element='"+obj.id+"' data-task='"+objtask_id+"'>Remove</a></p>";
            }
            else{
              $("#attachments_text").show();
              $("#add_attachments_div").append("<p>"+obj.filename+" </p>");
              $(".img_div").each(function() {
                $(this).hide();
              });
              $(".add_new_body").removeClass('add_padding');

              
            }
        });
        this.on("complete", function (file) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            $("body").removeClass("loading");
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

fileList = new Array();
Dropzone.options.imageUpload1 = {
    addRemoveLinks: true,
    maxFilesize:50,
    acceptedFiles: null,
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
              file.previewElement.innerHTML = "<p>"+obj.filename+" <a href='javascript:' class='remove_dropzone_attach' data-element='"+obj.id+"' data-task='"+objtask_id+"'>Remove</a></p>";
            }
            else{
              $("#attachments_text_edit").show();
              $("#add_attachments_div_edit").append("<p>"+obj.filename+" <a href='javascript:'' class='delete_file fa fa-trash' data-element='"+obj.file_id+"'></a></p>");
              $(".img_div_edit").each(function() {
                $(this).hide();
              });
              $(".add_edit_body").removeClass('add_padding');
            }
        });
        this.on("complete", function (file) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            $("body").removeClass("loading");
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
//on keyup, start the countdown
var typingTimer;                //timer identifier
var doneTypingInterval = 1000;  //time in ms, 5 second for example
var $input = $('.add_text');

$input.on('keyup', function () {
  var input_val = $(this).val();
  var id = $(this).attr('data-element');
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping, doneTypingInterval,input_val,id);
});
//on keydown, clear the countdown 
$input.on('keydown', function () {
  clearTimeout(typingTimer);
});
//user is "finished typing," do something
function doneTyping (input,id) {
  $.ajax({
        url:"<?php echo URL::to('user/update_fileattachment_textval'); ?>",
        type:"get",
        data:{input:input,id:id},
        success: function(result) {
        }
      });
}
</script>






@stop
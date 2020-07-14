@extends('adminheader')
@section('content')
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <form action="<?php echo URL::to('admin/add_year'); ?>" method="post" class="addsp">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add Year</h4>
          </div>
          <div class="modal-body">
            <table class="table">
                <tr>
                    <td>
                    <select name="name" class="form-control input-sm name_class2" required>
                      <option value="">Enter Year</option>
                      <?php 
                        for($i=2000;$i<=2050;$i++)
                        {
                          $check_year = DB::table('year')->where('year_name',$i)->first();
                          if(!count($check_year)){
                      ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                      <?php } } ?>
                    </select>
                      <label class="error_year"></label>
                    </td>
                </tr>
                <tr>
                    <td>
                      <input type="text" name="end_date" class="form-control datepicker" placeholder="End Date for Week Number 1" required>
                    </td>
                </tr>
            </table>
          </div>
          <div class="modal-footer">
            <input type="submit" value="Submit" class="common_black_button float_right submitclass">
          </div>
        </div>
    </form>

    <form action="<?php echo URL::to('admin/update_year'); ?>" method="post" class="editsp">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Edit Year</h4>
          </div>
          <div class="modal-body">
            <table class="table">
                <tr>
                    <td><input type="text" name="name" class="form-control name_class name_class3" placeholder="Enter year" required>
                      <label class="error_year3"></label>
                    <input type="hidden" name="id" class="form-control name_id"></td>
                </tr>
            </table>
          </div>
          <div class="modal-footer">
            <input type="submit" value="Update" class="btn common_black_button updateclass">
          </div>
        </div>
    </form>
  </div>
</div>
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
    <div class="col-lg-12 padding_00">
      <div class="col-lg-6 text-left padding_00">
        <div class="sub_title">Manage Year</div>
      </div>
      <div class="col-lg-6 text-right">
        <a href="javascript:" class="addclass common_black_button float_right" data-toggle="modal" data-target=".bs-example-modal-sm">Add New Year</a>
      </div>
    </div>
    <table class="table">
        <thead>
          <tr>
              <th width="5%" style="text-align: left;">S.No</th>
              <th>Year</th>
              <th>End Date For Week Number 1</th>
              <th width="15%">Action</th>
              
          </tr>
        </thead>
        <tbody>
          <?php
            $i=1;
            if(count($yearlist)){              
              foreach($yearlist as $year){
          ?>
          <tr>            
            <td><?php echo $i; ?></td>
            <td align="center"><?php echo $year->year_name; ?></td>
            <td align="center"><?php echo $year->end_date; ?></td>
            <td align="center">
                <?php
                  if($year->year_status == 0){
                    echo'<a href="'.URL::to('admin/deactive_year',base64_encode($year->year_id)).'"><i style="color:#00b348;" class="fa fa-check" aria-hidden="true"></i></a>';
                  }
                  else{
                    echo'<a href="'.URL::to('admin/active_year',base64_encode($year->year_id)).'"><i style="color:#f00;" class="fa fa-times" aria-hidden="true"></i></a>';
                  }
                ?>
                &nbsp; &nbsp;
               <!--  <a href="#" id="<?php //echo base64_encode($year->year_id); ?>" class="editclass"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>&nbsp; &nbsp; -->
                <a href="<?php echo URL::to('admin/delete_year', base64_encode($year->year_id)) ?>"><i class="fa fa fa-trash" aria-hidden="true"></i></a>
            </td>
          </tr>
          <?php
              $i++;
              }              
            }
            else{
              echo'<tr><td colspan="5" align="center">Empty</td></tr>';
            }
          ?>
          
        </tbody>
    </table>
  </div>
</div>
</div>
<script>
$( function() {
  $(".datepicker" ).datepicker({ dateFormat: 'mm-dd-yy' });
} );
</script>
<script>
$(".addclass").click( function(){
  $(".addsp").show();
  $(".editsp").hide();
});
$(".editclass").click( function(){
  var editid = $(this).attr("id");
  console.log(editid);
  $.ajax({
      url: "<?php echo URL::to('admin/edit_year') ?>"+"/"+editid,
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
</script>

<script>
var typingTimer;               
var doneTypingInterval = 250;  
var $input = $('.name_class2');

$input.on('keyup', function () {
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping, doneTypingInterval);
});

$input.on('keydown', function () {
  clearTimeout(typingTimer);
});

function doneTyping () {  
  var ytext = $(".name_class2").val();
  $.ajax({
    url:"<?php echo URL::to('admin/check_year')?>",
    dataType:"json",
    type:"post",
    data:{ytext:ytext},
    success:function(result){
      if(result == 1)
      {
        $(".error_year").text("Year is already exist");
      }
      else{
        $(".error_year").text("");
      }
    }
  })
}
$(".submitclass").click(function(e){
  var errormsg = $(".error_year").text();
  if(errormsg != "")
  {
    e.preventDefault();
  }
});




var typingTimer;               
var doneTypingInterval = 250;  
var $input3 = $('.name_class3');

$input3.on('keyup', function () {
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping3, doneTypingInterval);
});

$input3.on('keydown', function () {
  clearTimeout(typingTimer);
});

function doneTyping3 () {  
  var ytext = $(".name_class3").val();
  $.ajax({
    url:"<?php echo URL::to('admin/check_year')?>",
    dataType:"json",
    type:"post",
    data:{ytext:ytext},
    success:function(result){
      if(result == 1)
      {
        $(".error_year3").text("Year is already exist");
      }
      else{
        $(".error_year3").text("");
      }
    }
  })
}
$(".updateclass").click(function(e){
  var errormsg = $(".error_year3").text();
  if(errormsg != "")
  {
    e.preventDefault();
  }
});

</script>


@stop
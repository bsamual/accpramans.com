@extends('adminheader')
@section('content')
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <form action="<?php echo URL::to('admin/add_p30_period'); ?>" method="post" class="addsp">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add Period</h4>
          </div>
          <div class="modal-body">
            <table class="table">
                <tr>
                    <td><input type="text" name="name" class="form-control" placeholder="Enter Period" required></td>
                </tr>
            </table>
          </div>
          <div class="modal-footer">
            <input type="submit" value="Submit" class="common_black_button float_right">
          </div>
        </div>
    </form>

    <form action="<?php echo URL::to('admin/update_p30_period'); ?>" method="post" class="editsp">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Edit Period</h4>
          </div>
          <div class="modal-body">
            <table class="table">
                <tr>
                    <td><input type="text" name="name" class="form-control name_class" placeholder="Enter Period" required></td>
                </tr>                                
            </table>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="id" class="form-control name_id">
            <input type="submit" value="Update" class="btn common_black_button">
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
          <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
      <?php }
      ?>
    </div>
    <div class="col-lg-12 padding_00">
      <div class="col-lg-6 text-left padding_00">
        <div class="sub_title">Manage Period</div>
      </div>
      <div class="col-lg-6 text-right">
        <a href="javascript:" class="addclass common_black_button float_right" data-toggle="modal" data-target=".bs-example-modal-sm">Add Period</a>
      </div>
    </div>
    <table class="table">
        <thead>
          <tr>
              <th width="5%" style="text-align: left;">S.No</th>
              <th style="text-align: left;">Name</th>
              <th width="10%" style="text-align: left;">Sort</th>          
              <th width="15%" style="text-align: left;">Action</th>
              
          </tr>
        </thead>
        <tbody>
          <?php
            $i=1;
            if(count($periodlist)){              
              foreach($periodlist as $period){
          ?>
          <tr>            
            <td><?php echo $i;?></td>            
            <td><?php echo $period->name; ?></td>  
            <td>
              <select name="sort_period" id="sort_<?php echo $period->id; ?>" data-element="<?php echo $period->id; ?>" class="form-control sort_period">
                <?php 
                $count = DB::table('p30_period')->count();
                for($ival=1;$ival<=$count;$ival++)
                {
                  if($ival == $period->sort) { $selected = 'selected'; } else { $selected = ''; }
                  echo '<option value="'.$ival.'" '.$selected.'>'.$ival.'</option>';
                }
                ?>
              </select>
            </td>          
            <td>
                <?php
                if($period->status ==0){
                  echo'<a href="'.URL::to('admin/deactive_p30_period',base64_encode($period->id)).'"><i style="color:#00b348;" class="fa fa-check" aria-hidden="true"></i></a>';
                }
                else{
                  echo'<a href="'.URL::to('admin/active_p30_period',base64_encode($period->id)).'"><i style="color:#f00;" class="fa fa-times" aria-hidden="true"></i></a>';
                }
                ?>

                &nbsp; &nbsp;

                <a href="#" id="<?php echo base64_encode($period->id); ?>" class="editclass"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
            </td>
          </tr>
          <?php
              $i++;
              }              
            }
            else{
              echo'<tr><td colspan="5">Empty</td></tr>';
            }
          ?>
          
        </tbody>
    </table>
  </div>
</div>
</div>
<input type="hidden" id="hidden_sort" value="">
<script>
$(".addclass").click( function(){
  $(".addsp").show();
  $(".editsp").hide();
});
$(".editclass").click( function(){
  var editid = $(this).attr("id");
  console.log(editid);
  $.ajax({
      url: "<?php echo URL::to('admin/edit_p30_period') ?>"+"/"+editid,
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


$(window).click(function(e) {
  var ascending = false;
  if($(e.target).hasClass('delete_user'))
  {
    var r = confirm("Deleting this User will leads to remove from task alloted to this user. Are You Sure want to delete this user?");
    if (r == true) {
       
    } else {
        return false;
    }
  }
});

$( ".sort_period" ).focus(function() {
    var prevval = $(this).val();
    $("#hidden_sort").val(prevval);
});
$(window).change(function(e){
  if($(e.target).hasClass('sort_period'))
  {
      var currentid = $(e.target).attr("id");
      var currentelement = $(e.target).attr("data-element");
      var previous = $("#hidden_sort").val();
      var value = $(e.target).val();
      var periodid = [currentelement];
      var sortid = [value];
      $(".sort_period").each(function() {
        var id = $(this).attr("id");
        var sortvalue = $(this).val();
        var chengeelement = $(this).attr("data-element");

        if(id == currentid)
        {

        }
        else{
          if(sortvalue == value)
          {
            periodid.push(chengeelement);
            sortid.push(previous);
            $(this).val(previous);
          }
        }
      });
      $("#hidden_sort").val('');
      $(e.target).blur();

    $.ajax({
      url:"<?php echo URL::to('admin/period_sort_order'); ?>",
      type:"get",
      data:{currentid:periodid[0],currentval:sortid[0],previd:periodid[1],prevval:sortid[1]},
      success: function(result){

      }
    });
  }
});
</script>
@stop
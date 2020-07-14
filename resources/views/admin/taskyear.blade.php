@extends('adminheader')
@section('content')
<style>
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
</style>
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <form action="<?php echo URL::to('admin/add_taskyear'); ?>" method="post" class="addsp">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add Year</h4>
          </div>
          <div class="modal-body">
            <table class="table">
                <tr>
                  <td colspan="2">
                    <select class="form-control" name="year" id="year_add" required>
                      <option value="">Select Year</option>
                      <?php 
                      $last_year = DB::table('year')->orderBy('year_id','desc')->first();
                      ?>
                        <option value="<?php echo $last_year->year_name + 1; ?>"><?php echo $last_year->year_name + 1; ?></option>
                    </select>
                  </td>
                </tr>
                <tr>
                    <td>
                      <input type="text" name="end_date" class="form-control datepicker" id="endweek_add" placeholder="End Date for Week Number 1" required>
                    </td>
                </tr>           
            </table>
          </div>
          <div class="modal-footer">
            <input type="submit" value="Submit" class="common_black_button submit_addyear float_right">
          </div>
        </div>
    </form>

    <form action="<?php echo URL::to('admin/update_taskyear'); ?>" method="post" class="editsp">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Edit Year</h4>
          </div>
          <div class="modal-body">
            <table class="table">
                <tr>
                  <td colspan="2">
                    <select class="form-control yearclass" name="year" required disabled>
                      <?php 
                        for($i=2000;$i<=2050;$i++)
                        {
                      ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                      <?php } ?>
                    </select>
                  </td>
                </tr>
                <tr>
                    <td>
                      <input type="text" name="end_date" class="form-control datepicker enddateclass" placeholder="End Date for Week Number 1" disabled required>
                    </td>
                </tr>        
            </table>
            <input type="hidden" name="taskyear_id" id="taskyear_id" value="">
          </div>
          <div class="modal-footer">
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
          <p class="alert alert-info"><?php echo Session::get('message'); ?></p>
      <?php }
      ?>
    </div>
    <div class="col-lg-12 padding_00">
      <div class="col-lg-6 text-left padding_00">
        <div class="sub_title">Manage Task</div>
      </div>
      <div class="col-lg-6 text-right">
        <a href="javascript:" class="addclass common_black_button float_right" data-toggle="modal" data-target=".bs-example-modal-sm">Add New Year</a>
      </div>
    </div>
    <table class="table">
        <thead>
          <tr>
              <th width="5%" style="text-align: left;">S.No <i class="fa fa-sort sno_sort" aria-hidden="true"></i></th>
              <th width="10%">Year <i class="fa fa-sort year_sort" aria-hidden="true"></i></th>
              <th width="15%">End Date for Week 1 <i class="fa fa-sort date_sort" aria-hidden="true"></i></th>
              <th width="15%">Action</th>
              
          </tr>
        </thead>
        <tbody id="sort_maintable">
          <?php
            $i=1;
            if(count($tasklist)){              
              foreach($tasklist as $task){
          ?>
          <tr class="sno_tr">            
            <td class="sno_td"><?php echo $i;?></td>            
            <td class="year_td" align="center"><?php echo $task->year_name; ?></td>
            <?php 
              $exp = explode('-',$task->end_date);
              $date = $exp[1].'-'.$exp[2].'-'.$exp[0];
            ?>
            <td class="date_td" align="center"><?php echo $date; ?></td>
            
            <td align="center">
                <?php
                if($task->taskyear_status ==0){
                  echo'<a href="'.URL::to('admin/deactive_taskyear',base64_encode($task->taskyear_id)).'"><i style="color:#00b348;" class="fa fa-check" aria-hidden="true"></i></a>';
                }
                else{
                  echo'<a href="'.URL::to('admin/active_taskyear',base64_encode($task->taskyear_id)).'"><i style="color:#f00;" class="fa fa-times" aria-hidden="true"></i></a>';
                }
                ?>

                &nbsp; &nbsp;

                <a href="#" id="<?php echo base64_encode($task->taskyear_id); ?>" class="editclass"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>&nbsp; &nbsp;
                
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
<div class="modal_load"></div>
<input type="hidden" name="sno_sortoptions" id="sno_sortoptions" value="asc">
<input type="hidden" name="year_sortoptions" id="year_sortoptions" value="asc">
<input type="hidden" name="date_sortoptions" id="date_sortoptions" value="asc">
<script>
$( function() {
  $(".datepicker" ).datepicker({ dateFormat: 'mm-dd-yy' });
} );
</script>
<script>
$(window).click(function(e) {
  var ascending = false;
  if($(e.target).hasClass('submit_addyear'))
  {
    var year = $("#year_add").val();
    var end_week = $("#endweek_add").val();
    if(year != '' && end_week != '')
    {
      $("body").addClass("loading");
    }
  }
  if($(e.target).hasClass('delete_taskyear'))
  {
    var r = confirm("Deleting this Year will leads to erase all the related Weeks,Months and Tasks belongs to this year. Are You Sure want to delete this year?");
    if (r == true) {
       
    } else {
        return false;
    }
  }
  if($(e.target).hasClass('sno_sort'))
  {
    var sort = $("#sno_sortoptions").val();
    if(sort == 'asc')
    {
      $("#sno_sortoptions").val('desc');
      var sorted = $('#sort_maintable').find('.sno_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.sno_td').html()) <
        convertToNumber($(b).find('.sno_td').html()))) ? 1 : -1;
      });
    }
    else{
      $("#sno_sortoptions").val('asc');
      var sorted = $('#sort_maintable').find('.sno_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.sno_td').html()) <
        convertToNumber($(b).find('.sno_td').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#sort_maintable').html(sorted);
  }
  if($(e.target).hasClass('year_sort'))
  {
    var sort = $("#year_sortoptions").val();
    if(sort == 'asc')
    {
      $("#year_sortoptions").val('desc');
      var sorted = $('#sort_maintable').find('.sno_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.year_td').html()) <
        convertToNumber($(b).find('.year_td').html()))) ? 1 : -1;
      });
    }
    else{
      $("#year_sortoptions").val('asc');
      var sorted = $('#sort_maintable').find('.sno_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.year_td').html()) <
        convertToNumber($(b).find('.year_td').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#sort_maintable').html(sorted);
  }
  if($(e.target).hasClass('date_sort'))
  {
    var sort = $("#date_sortoptions").val();
    if(sort == 'asc')
    {
      $("#date_sortoptions").val('desc');
      var sorted = $('#sort_maintable').find('.sno_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.date_td').html()) <
        convertToNumber($(b).find('.date_td').html()))) ? 1 : -1;
      });
    }
    else{
      $("#date_sortoptions").val('asc');
      var sorted = $('#sort_maintable').find('.sno_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.date_td').html()) <
        convertToNumber($(b).find('.date_td').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#sort_maintable').html(sorted);
  }
});
var convertToNumber = function(value){
       return value.toLowerCase();
}
$(".addclass").click( function(){
  $(".addsp").show();
  $(".editsp").hide();
});
$(".editclass").click( function(){
  var editid = $(this).attr("id");
  console.log(editid);
  $.ajax({
      url: "<?php echo URL::to('admin/edit_taskyear') ?>"+"/"+editid,
      dataType:"json",
      type:"get",
      success:function(result){
         $(".bs-example-modal-sm").modal("toggle");
         $(".editsp").show();
         $(".addsp").hide();
         $(".yearclass").val(result['year']);
         $(".enddateclass").val(result['end_date']);
         $("#taskyear_id").val(result['id']);   
         $(".check_user").each(function(){
          $(this).prop("checked",false);
         });
         var res = result['user'].split(",");
         $.each( res, function( key, value ) {
          $(".user_"+value).prop("checked",true);
        });      
    }
  })
});
</script>
@stop
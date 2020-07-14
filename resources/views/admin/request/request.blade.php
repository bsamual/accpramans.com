@extends('adminheader')
@section('content')
<style>
.label_class{
  width:20%;
  float: left;
}
.plus_add{ padding: 3px ;background: #000; color: #fff; width: 30px; text-align: center; margin-top: 23px; font-size: 20px; float: right; }
.plus_add:hover{background: #5f5f5f; color: #fff}
.minus_remove{ padding: 3px ;background: #000; color: #fff; width: 30px; text-align: center; margin-top: 23px; font-size: 20px; float: right;margin-left: 4px; }
.minus_remove:hover{background: #5f5f5f; color: #fff}
table tr td{font-size: 15px !important;}
</style>
<div class="modal fade add_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-md" role="document" style="z-index: 999999">
    <div class="modal-content">
      <form method="post" action="<?php echo URL::to('admin/request_add')?>">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">Add Request Category</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Enter Category Name</label>
            <input type="text" required class="form-control" name="category" placeholder="Enter Category" name="">
          </div> 
          <div class="col-lg-12 padding_00" style="height: 1px; background: #ccc;"></div>
          <div class="col-lg-12 padding_00" style="padding-top: 10px; margin-bottom: 8px;" >
            <b style="font-size: 15px;">Request Item</b>
          </div>
          <div id="add_items_div">
          	<div class="row single_item_div">
	            <div class="col-lg-10">
	              <div class="form-group">
	                <label>Enter Item Name</label>
	                <input type="text" required class="form-control" name="request_item[]" placeholder="Enter Item Name">
	              </div>      
	            </div>
	            <div class="col-lg-2">
	              <a href="javascript:" class="plus_add">+</a>
	            </div>
	        </div>
          </div>
          
          

          
        </div>
        <div class="modal-footer">
          <input type="submit" class="btn btn-primary common_black_button" value="Add New Category">
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade edit_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-md" role="document" style="z-index: 999999">
    <div class="modal-content">
      <form method="post" action="<?php echo URL::to('admin/request_edit_form')?>">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">Edit Request Category</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Enter Category Name</label>
            <input type="text" required class="form-control category_edit" name="category_edit" placeholder="Enter Category" name="">
          </div> 
          <div class="col-lg-12 padding_00" style="height: 1px; background: #ccc;"></div>
          <div class="col-lg-12 padding_00" style="padding-top: 10px; margin-bottom: 8px;" >
            <b style="font-size: 15px;">Request Item</b>
          </div>
          <div id="edit_items_div">
          	
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="category_id_edit" id="category_id_edit" value="">
          <input type="submit" class="btn btn-primary common_black_button" value="Update Category">
        </div>
      </form>
    </div>
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
        <div class="sub_title">Setup Request Categories</div>
      </div>      
      <div class="col-lg-6 text-right">
        <a href="javascript:" class="addclass common_black_button float_right" data-toggle="modal" data-target=".add_model">Add Setup Category</a>
      </div>
    </div>
    <table class="table">
        <thead>
          <tr>
              <th width="5%" style="text-align: left;">S.No</th>
              <th style="text-align: left;">Category Name</th>
              <th>Signature</th>
              <th width="15%">Action</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $output='';
        $i=1;
        if(count($requestlist)){
          foreach ($requestlist as $request) {
            $sub_category=DB::table('request_sub_category')->where('category_id', $request->category_id)->get();
            if(count($sub_category)){
              $outputsub='';
              foreach ($sub_category as $sub) {
                $outputsub.=$sub->sub_category_name.'<br/>';
              }              
            }
            else{
              $outputsub='';
            }
            if($request->status == 0){
              $status = '<a href="'.URL::to('admin/deactive_request/'.base64_encode($request->category_id)).'"><i class="fa fa-check deactive_class" style="color:#00b348;"></i></a>';
            }
            else{
              $status = '<a href="'.URL::to('admin/active_request/'.base64_encode($request->category_id)).'"><i class="fa fa-times active_class" style="color:#f00;"></i></a>';
            }
            $output.='<tr>
                        <td>'.$i.'</td>
                        <td><b>'.$request->category_name.'</b><br/>'.$outputsub.'</td>
                        <td width="300px;">
                        <textarea placeholder="Enter Signature" data-element="'.base64_encode($request->category_id).'" class="form-control class_signature" style="height:100px;">'.$request->signature.'</textarea>
                        </td>
                        <td style="font-size:15px; text-align:center">
                        '.$status.'&nbsp;&nbsp;
                        <a href="javascript:"><i class="fa fa-pencil-square edit_icon" data-element="'.base64_encode($request->category_id).'"></i></a>&nbsp;&nbsp;
                        <a href="'.URL::to('admin/delete_request/'.base64_encode($request->category_id)).'"><i class="fa fa-trash"></i></a>
                        
                        </td>
                      </tr>';
                      $i++;
          }
        }
        else{
          $output='<tr><td colspan="4" align="center">Empty</td></tr>';
        }
        echo $output;
        ?>
        </tbody>
    </table>
  </div>
</div>
</div>
<script>
$(window).click(function(e) { 
if($(e.target).hasClass('plus_add'))
{
	$("#add_items_div").append('<div class="row single_item_div"><div class="col-lg-10"><div class="form-group"><label>Enter Item Name</label><input type="text" required class="form-control" placeholder="Enter Item Name" name="request_item[]"></div> </div><div class="col-lg-2"><a href="javascript:" class="plus_add">+</a></div></div>');

	$(".plus_add").each(function(){
		$(this).parent().html('<a href="javascript:" class="minus_remove">-</a>');
	});
	$(".minus_remove:last").parent().html('<a href="javascript:" class="minus_remove">-</a><a href="javascript:" class="plus_add">+</a>');
}
if($(e.target).hasClass('minus_remove'))
{
	$(e.target).parents(".single_item_div").detach();
	$(".plus_add").each(function(){
		$(this).parent().html('<a href="javascript:" class="minus_remove">-</a>');
	});
	$(".minus_remove:last").parent().html('<a href="javascript:" class="minus_remove">-</a><a href="javascript:" class="plus_add">+</a>');
}
if($(e.target).hasClass('edit_icon'))
  {
    var id = $(e.target).attr("data-element");
    $("#category_id_edit").val(id);
    $.ajax({
      url:"<?php echo URL::to('admin/request_edit_category'); ?>",
      type:"get",
      data:{id:id},
      dataType:"json",
      success: function(result) {
        $(".edit_model").modal("show");
        $(".category_edit").val(result['category_name']);
        var items = result['sub_category_name'];
        var items_sep = items.split("||");
        $.each(items_sep, function(index,value)
        {
        	$("#edit_items_div").append('<div class="row single_item_div_edit"><div class="col-lg-10"><div class="form-group"><label>Enter Item Name</label><input type="text" required class="form-control" placeholder="Enter Item Name" name="request_item_edit[]" value="'+value+'"></div> </div><div class="col-lg-2"></div></div>')
        });
      }
    })
  }
})
$(window).keyup(function(e) {
    var valueTimmer;                //timer identifier
    var valueInterval = 500;  //time in ms, 5 second for example
    var $signature_value = $('.class_signature');    
    if($(e.target).hasClass('class_signature'))
    {        
        var input_val = $(e.target).val();  
        var id = $(e.target).attr("data-element");
        clearTimeout(valueTimmer);
        valueTimmer = setTimeout(doneTyping, valueInterval,input_val, id);   
    }    
});
function doneTyping (signature_value,id) {
  $.ajax({
        url:"<?php echo URL::to('admin/request_signature')?>",
        type:"post",
        dataType:"json",
        data:{value:signature_value, id:id},
        success: function(result) {            
            
        }
      });
}
</script>
@stop
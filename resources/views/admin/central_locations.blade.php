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
textarea{
  width:100%;
  height:200px !important;
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
    <form name="update_form" id="update_form" action="<?php echo URL::to('admin/update_central_locations_form'); ?>" method="post">     
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding_00 border">
        <div class="col-lg-11 col-lg-offset-1 text-left padding_00">
          <div class="sub_title" style="margin-bottom: 0px; ">
            <?php 
            $use = DB::table('admin')->first(); 
            if($use->central_locations == 1) { $disabled = ''; } else { $disabled = 'readonly'; } ?>
            <input type="checkbox" name="use_central_locations" id="use_central_locations" value="1" <?php echo($use->central_locations == 1)?"checked":""; ?>>
            <label for="use_central_locations">Use Central Locations</label>
          </div>
        </div>
        <div class="col-lg-4 text-left padding_00">
            <div class="col-lg-12" style="padding: 25px;">
              <div>
                <?php
                if(Session::has('emailmessage')) { ?>
                    <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('emailmessage'); ?></p>
                <?php }
                ?>
              </div>
                <div class="form-group">
                    <label>Client Management System</label>
                    <textarea placeholder="Enter Client Management System" class="form-control edit_central_class" name="client_management" <?php echo $disabled; ?>><?php echo $selectval->client_management; ?></textarea>                          
                </div> 
            </div>
        </div>

        <div class="col-lg-4 text-left padding_00">
            <div class="col-lg-12" style="padding: 25px;">
                <div class="form-group">
                    <label>Invoice Management</label>
                    <textarea placeholder="Enter Invoice Management" class="form-control edit_central_class" name="invoice_management" <?php echo $disabled; ?>><?php echo $selectval->invoice_management; ?></textarea>                          
                </div> 
            </div>
        </div>

        <div class="col-lg-4 text-left padding_00">
            <div class="col-lg-12" style="padding: 25px;">
                <div class="form-group">
                    <label>Client Statements</label>
                    <textarea placeholder="Enter Client Statements" class="form-control edit_central_class" name="client_statements" <?php echo $disabled; ?>><?php echo $selectval->client_statements; ?></textarea>                          
                </div> 
            </div>
        </div>

        <div class="col-lg-4 text-left padding_00">
            <div class="col-lg-12" style="padding: 25px;">
                <div class="form-group">
                    <label>Weekly / Monthly Tasks</label>
                    <textarea placeholder="Enter Weekly / Monthly Tasks" class="form-control edit_central_class" name="weekly_monthly" <?php echo $disabled; ?>><?php echo $selectval->weekly_monthly; ?></textarea>                          
                </div> 
            </div>
        </div>

        <div class="col-lg-4 text-left padding_00">
            <div class="col-lg-12" style="padding: 25px;">
                <div class="form-group">
                    <label>P30 Output</label>
                    <textarea placeholder="Enter P30 Output" class="form-control edit_central_class" name="p30" <?php echo $disabled; ?>><?php echo $selectval->p30; ?></textarea>                          
                </div> 
            </div>
        </div>

        <div class="col-lg-4 text-left padding_00">
            <div class="col-lg-12" style="padding: 25px;">
                <div class="form-group">
                    <label>VAT System</label>
                    <textarea placeholder="Enter VAT System" class="form-control edit_central_class" name="vat" <?php echo $disabled; ?>><?php echo $selectval->vat; ?></textarea>                          
                </div> 
            </div>
        </div>

        <div class="col-lg-4 text-left padding_00">
            <div class="col-lg-12" style="padding: 25px;">
                <div class="form-group">
                    <label>RCT System</label>
                    <textarea placeholder="Enter RCT System" class="form-control edit_central_class" name="rct" <?php echo $disabled; ?>><?php echo $selectval->rct; ?></textarea>                          
                </div> 
            </div>
        </div>

        <div class="col-lg-4 text-left padding_00">
            <div class="col-lg-12" style="padding: 25px;">
                <div class="form-group">
                    <label>Year End Data</label>
                    <textarea placeholder="Enter Year End Data" class="form-control edit_central_class" name="year_end" <?php echo $disabled; ?>><?php echo $selectval->year_end; ?></textarea>                          
                </div> 
            </div>
        </div>

        <div class="col-lg-4 text-left padding_00">
            <div class="col-lg-12" style="padding: 25px;">
                <div class="form-group">
                    <label>Time Location</label>
                    <textarea placeholder="Enter Time Location" class="form-control edit_central_class" name="time_location" <?php echo $disabled; ?>><?php echo $selectval->time_location; ?></textarea>                          
                </div> 
            </div>
        </div>
      </div>
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding_00 border" style="text-align: center">
        <input type="submit" name="update_central_locations" class="common_black_button" value="Update Central Locations">
      </div>
    </form>
  </div>
</div>
</div>
<script>
$(window).click(function(e) {
  if(e.target.id == "use_central_locations")
  {
    if($(e.target).is(":checked"))
    {
      $.ajax({
        url:"<?php echo URL::to('admin/update_central_locations'); ?>",
        type:"post",
        data:{id:1},
        success: function(result)
        {
          $(".edit_central_class").each(function () {
            $(this).prop("readonly",false);
          });
        }
      });
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('admin/update_central_locations'); ?>",
        type:"post",
        data:{id:0},
        success: function(result)
        {
          $(".edit_central_class").each(function () {
            $(this).prop("readonly",true);
          });
        }
      });
    }
  }
 });
</script>
@stop
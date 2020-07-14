@extends('userheader')
@section('content')

<style>
body{background:url('<?php echo URL::to('assets/images/p30_bg.jpg')?>') no-repeat center top !important; 
  -webkit-background-size: cover !important;
  -moz-background-size: cover !important;
  -o-background-size: cover !important;
  background-size: cover !important;}
  .paye_p30_year_div{
    width: 30%;
    background: rgba(255, 255, 255,0.5);
    padding: 10px;

  }
</style>
<style>
.page_title{color:#fff; text-shadow: 0px 1px 2px #000}
</style>
<div class="content_section">
  <div class="page_title">
    Pre 2019 P30 System
  </div>
  <div class="select_button" style="margin-bottom:20px">
        <ul>
            <?php
            if(count($yearlist)){
              foreach($yearlist as $year){
                if($year->year_status == 0){
            ?>
              <li><a href="<?php echo URL::to('user/gbs_p30month_manage/'.base64_encode($year->year_id))?>"><?php echo $year->year_name?></a></li>
            <?php
                }
              }
            }
            ?>            
        </ul>
        <p style="clear: both;font-size: 18px;font-weight: 800;color: #fff; text-shadow: 0px 1px 2px #000; position: absolute;bottom:8%;text-align: center;
    width: 98%;">You Are In The P30 System</p>
  </div>
  <div class="page_title" >
    PAYE M.R.S 2019+ 
  </div>
  <div class="select_button">
      <?php
        if(count($paye_p30_year))
        {
          echo '<ul>';
            foreach($paye_p30_year as $year){
              if($year->year_status == 0){
                ?>  
                   <li><a href="<?php echo URL::to('user/gbs_paye_p30month_manage/'.base64_encode($year->year_id))?>"><?php echo $year->year_name; ?></a></li>
                <?php
              }
            }
          echo '</ul>';
        }
        else{
          ?>
          <div class="paye_p30_year_div">
            <p>Note: Select your First year.</p>
            <h6 style="font-weight:800">SELECT YEAR: </h6>
            <select name="select_paye_p30_year" id="select_paye_p30_year" class="form-control input-sm">
                <option value="">Select Year</option>
                <?php
                $starting_year = 2019;
                for($i = $starting_year; $i<=2040; $i++)
                {
                  echo '<option value="'.$i.'">'.$i.'</option>';
                }
                ?> 
            </select>
            <input type="button" name="update_paye_p30_year" id="update_paye_p30_year" class="common_black_button" value="Submit" style="margin-top:10px">
          </div>
          <?php
        }
      ?>
  </div>
</div>
<script>
$(window).click(function(e){
  if(e.target.id == "update_paye_p30_year")
  {
    var year = $("#select_paye_p30_year").val();
    if(year != "")
    {
      $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:15px;font-weight:600;color:red>About to create your First year, once you create this year you CAN NOT create an older year, <br/>Are you sure you wish to continue creating this year?</p><div style='text-align:center;margin-top: 33px;'><a href='javascript:' class='common_black_button ok_hit' style='margin-left:10px'>Yes</a><a href='javascript:' class='common_black_button cancel_hit' style='margin-left:10px'>No</a></div>", fixed:true,height:"200px"});
    }
    else{
      alert("Please Select the Year from dropdown.")
    }
  }
  if($(e.target).hasClass('ok_hit'))
  {
      var year = $("#select_paye_p30_year").val();
      if(year != "")
      {
        $.ajax({
          url:"<?php echo URL::to('user/gbs_update_paye_p30_first_year'); ?>",
          type:"post",
          data:{year:year},
          success: function(result)
          {
            window.location.reload();
          }
        })
      }
      else{
        $.colorbox.close();
      }
  }
  if($(e.target).hasClass('cancel_hit'))
  {
    $.colorbox.close();
  }
});
</script>
@stop
@extends('userheader')
@section('content')
<style>
body{background:url('<?php echo URL::to('assets/images/paye_p30_month.jpg')?>') no-repeat center top !important; 
  -webkit-background-size: cover !important;
  -moz-background-size: cover !important;
  -o-background-size: cover !important;
  background-size: cover !important;}
  .paye_p30_year_div{
    width: 30%;
    background: rgba(255, 255, 255,0.5);
    padding: 10px;

  }

.table_bg>thead>tr>th
{
    text-align:left;
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
</style>
<div class="content_section">
  <div class="page_title">
    SELECT MONTH
    
  </div>
    <div class="select_button">
        <table class="table table_bg" style="width:70%">
          <thead>
            <tr class="background_bg">
                <th style="width:30%">Month</th>
                <th>NO OF TASKS</th>
            </tr>
          </thead>
          <tbody>
            <?php
            
            if(count($monthlist)){
              foreach($monthlist as $month){
                $task_count = DB::table('paye_p30_task')->where('task_month',$month->month_id)->count();
                $year = DB::table('paye_p30_year')->where('year_id',$month->year)->first();
                if($month->month == 1) { $month_name = "January"; }
                if($month->month == 2) { $month_name = "February"; }
                if($month->month == 3) { $month_name = "March"; }
                if($month->month == 4) { $month_name = "April"; }
                if($month->month == 5) { $month_name = "May"; }
                if($month->month == 6) { $month_name = "June"; }
                if($month->month == 7) { $month_name = "July"; }
                if($month->month == 8) { $month_name = "August"; }
                if($month->month == 9) { $month_name = "September"; }
                if($month->month == 10) { $month_name = "October"; }
                if($month->month == 11) { $month_name = "November"; }
                if($month->month == 12) { $month_name = "December"; }
            ?>
            <tr>
                <td style="text-align:left; border:1px solid #000"><a href="<?php echo URL::to('user/paye_p30_select_month/'.base64_encode($month->month_id))?>" class="btn" style="text-align:left;"><?php echo $month_name.' '.$year->year_name.' PAYE M.R.S'; ?></a></td>
                <td style="text-align:center; border:1px solid #000"><label><?php echo $task_count; ?></label></td>
            </tr>
            <?php 
            } } 
            else{
                echo "Month Not Found";
            }?>
          </tbody>            
        </table>
        
    </div>
</div>
@stop
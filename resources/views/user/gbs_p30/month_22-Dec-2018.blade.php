@extends('userheader')
@section('content')
<div class="content_section">
  <div class="page_title">
    SELECT YEAR
  </div>
    <div class="select_button">
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
        <p style="clear: both;font-size: 18px;font-weight: 800;color: #000;position: absolute;bottom:8%;text-align: center;
    width: 98%;">You Are In The P30 System</p>
    </div>
</div>
@stop
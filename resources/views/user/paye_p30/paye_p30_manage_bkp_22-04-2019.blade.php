@extends('userheader')
@section('content')
<style>


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

.page_title{

  margin-bottom: 0px !important;

  padding: 20px !important;

  background: #fff; z-index: 99
     

}


.table_bg tbody tr td{

  padding:8px;

  border-bottom:1px solid #000;
  font-weight: 600; font-size: 15px;
  color: #000 !important;

}

.table_bg thead tr th{

  padding:8px;

}
.button_top_right ul li a{padding: 5px 10px; font-size: 16px; font-weight: 600}
.form-control[readonly]{background-color: #e6e6e6 !important}

</style>
<div class="content_section">
  <div class="page_title" style="position:fixed;margin-top: -17px;">
    <div class="col-lg-2 padding_00" style="line-height: 30px; font-size: 20px">
      PAYE M.R.S <?php echo $year->year_name?>           
    </div>

    <div class="col-lg-10 padding_00 button_top_right">
          <ul>
            <li class=""><a href="javascript:" style="float:right">Close and Create New Year</a></li>
            <li class=""><a href="<?php echo URL::to('user/paye_p30_review_year/'.$year->year_id); ?>">Review Year</a></li>           
          </ul>
    </div>

    <div class="col-lg-12 padding_00">
        <div class="row">
            <div class="col-lg-3 padding_00">
                <div class="col-lg-12" style="line-height: 30px;">Active Week Periods for all:</div>
                <div class="col-lg-6">                
                    <select class="form-control">
                        <option>Select From</option>
                    </select>
                </div>
                <div class="col-lg-6 ">                
                    <select class="form-control">
                        <option>Select To</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-3 padding_00">
                <div class="col-lg-12" style="line-height: 30px;">Active Month Periods for all:</div>
                <div class="col-lg-6">                
                    <select class="form-control">
                        <option>Select From</option>
                    </select>
                </div>
                <div class="col-lg-6 ">                
                    <select class="form-control">
                        <option>Select To</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-4 button_top_right" style="float: left;">
                <ul style="float: left; margin-top: 28px;">
                    <li class=""><a href="javascript:" >Apply</a></li>
                    <li class=""><a href="javascript:">Show Active Periods Only</a></li>
                    <li class=""><a href="javascript:">Show all Periods</a></li>
                  </ul>
            </div>
            <div class="col-lg-2" >
                <div class="col-lg-12" style="line-height: 30px;">Active Month:</div>
                <div class="col-lg-12">
                    <select class="form-control">
                        <option value="">Select Period</option>
                    </select>
                </div>
            </div>
        </div>
        
    </div>
  </div>

  <div class="col-lg-12" style="clear: both;  margin-top:180px">

    <div style="width:100%;float:left;">

        <?php

        if(Session::has('message')) { ?>

            <p class="alert alert-info" style="clear:both; "><?php echo Session::get('message'); ?>
                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            </p>



        <?php }

        if(Session::has('error')) { ?>

            <p class="alert alert-danger" style="clear:both;"><?php echo Session::get('error'); ?>
                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            </p>

        <?php }

        ?>

        </div>

  <?php
  $output='';
  $i=1;
  if(count($payelist)){
    foreach ($payelist as $task) {
        $level_name = DB::table('p30_tasklevel')->where('id',$task->task_level)->first();

        $output_month='';

        if(count($periodlist)){
            foreach ($periodlist as $period) { 

                if($period->month_id == 1) { $month_name = "January"; }
                if($period->month_id == 2) { $month_name = "February"; }
                if($period->month_id == 3) { $month_name = "March"; }
                if($period->month_id == 4) { $month_name = "April"; }
                if($period->month_id == 5) { $month_name = "May"; }
                if($period->month_id == 6) { $month_name = "June"; }
                if($period->month_id == 7) { $month_name = "July"; }
                if($period->month_id == 8) { $month_name = "August"; }
                if($period->month_id == 9) { $month_name = "September"; }
                if($period->month_id == 10) { $month_name = "October"; }
                if($period->month_id == 11) { $month_name = "November"; }
                if($period->month_id == 12) { $month_name = "December"; }

                $output_month.='
                

                ';
            }
        }



        if($task->task_level != 0){ $action = $level_name->name; }
        if($task->pay == 0){ $pay = 'No';}else{$pay = 'Yes';}
        if($task->email == 0){ $email = 'No';}else{$email = 'Yes';}

        if($task->week1 == 0){ $week1 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week1 = '<a href="javascript:" class="payp30_black">'.$task->week1.'</a>';}

        if($task->week2 == 0){ $week2 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week2 = '<a href="javascript:" class="payp30_black">'.$task->week2.'</a>';}

        if($task->week3 == 0){ $week3 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week3 = '<a href="javascript:" class="payp30_black">'.$task->week3.'</a>';}

        if($task->week4 == 0){ $week4 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week4 = '<a href="javascript:" class="payp30_black">'.$task->week4.'</a>';}

        if($task->week5 == 0){ $week5 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week5 = '<a href="javascript:" class="payp30_black">'.$task->week5.'</a>';}

        if($task->week6 == 0){ $week6 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week6 = '<a href="javascript:" class="payp30_black">'.$task->week6.'</a>';}

        if($task->week7 == 0){ $week7 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week7 = '<a href="javascript:" class="payp30_black">'.$task->week7.'</a>';}

        if($task->week8 == 0){ $week8 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week8 = '<a href="javascript:" class="payp30_black">'.$task->week8.'</a>';}

        if($task->week9 == 0){ $week9 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week9 = '<a href="javascript:" class="payp30_black">'.$task->week9.'</a>';}

        if($task->week10 == 0){ $week10 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week10 = '<a href="javascript:" class="payp30_black">'.$task->week10.'</a>';}

        if($task->week11 == 0){ $week11 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week11 = '<a href="javascript:" class="payp30_black">'.$task->week11.'</a>';}

        if($task->week12 == 0){ $week12 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week12 = '<a href="javascript:" class="payp30_black">'.$task->week12.'</a>';}

        if($task->week13 == 0){ $week13 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week13 = '<a href="javascript:" class="payp30_black">'.$task->week13.'</a>';}

        if($task->week14 == 0){ $week14 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week14 = '<a href="javascript:" class="payp30_black">'.$task->week14.'</a>';}

        if($task->week15 == 0){ $week15 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week15 = '<a href="javascript:" class="payp30_black">'.$task->week15.'</a>';}

        if($task->week16 == 0){ $week16 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week16 = '<a href="javascript:" class="payp30_black">'.$task->week16.'</a>';}

        if($task->week17 == 0){ $week17 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week17 = '<a href="javascript:" class="payp30_black">'.$task->week17.'</a>';}

        if($task->week18 == 0){ $week18 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week18 = '<a href="javascript:" class="payp30_black">'.$task->week18.'</a>';}

        if($task->week19 == 0){ $week19 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week19 = '<a href="javascript:" class="payp30_black">'.$task->week19.'</a>';}

        if($task->week20 == 0){ $week20 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week20 = '<a href="javascript:" class="payp30_black">'.$task->week20.'</a>';}

        if($task->week21 == 0){ $week21 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week21 = '<a href="javascript:" class="payp30_black">'.$task->week21.'</a>';}

        if($task->week22 == 0){ $week22 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week22 = '<a href="javascript:" class="payp30_black">'.$task->week22.'</a>';}

        if($task->week23 == 0){ $week23 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week23 = '<a href="javascript:" class="payp30_black">'.$task->week23.'</a>';}

        if($task->week24 == 0){ $week24 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week24 = '<a href="javascript:" class="payp30_black">'.$task->week24.'</a>';}

        if($task->week25 == 0){ $week25 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week25 = '<a href="javascript:" class="payp30_black">'.$task->week25.'</a>';}

        if($task->week26 == 0){ $week26 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week26 = '<a href="javascript:" class="payp30_black">'.$task->week26.'</a>';}

        if($task->week27 == 0){ $week27 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week27 = '<a href="javascript:" class="payp30_black">'.$task->week27.'</a>';}

        if($task->week28 == 0){ $week28 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week28 = '<a href="javascript:" class="payp30_black">'.$task->week28.'</a>';}

        if($task->week29 == 0){ $week29 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week29 = '<a href="javascript:" class="payp30_black">'.$task->week29.'</a>';}

        if($task->week30 == 0){ $week30 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week30 = '<a href="javascript:" class="payp30_black">'.$task->week30.'</a>';}

        if($task->week31 == 0){ $week31 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week31 = '<a href="javascript:" class="payp30_black">'.$task->week31.'</a>';}

        if($task->week32 == 0){ $week32 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week32 = '<a href="javascript:" class="payp30_black">'.$task->week32.'</a>';}

        if($task->week33 == 0){ $week33 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week33 = '<a href="javascript:" class="payp30_black">'.$task->week33.'</a>';}

        if($task->week34 == 0){ $week34 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week34 = '<a href="javascript:" class="payp30_black">'.$task->week34.'</a>';}

        if($task->week35 == 0){ $week35 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week35 = '<a href="javascript:" class="payp30_black">'.$task->week35.'</a>';}

        if($task->week36 == 0){ $week36 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week36 = '<a href="javascript:" class="payp30_black">'.$task->week36.'</a>';}

        if($task->week37 == 0){ $week37 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week37 = '<a href="javascript:" class="payp30_black">'.$task->week37.'</a>';}

        if($task->week38 == 0){ $week38 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week38 = '<a href="javascript:" class="payp30_black">'.$task->week38.'</a>';}

        if($task->week39 == 0){ $week39 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week39 = '<a href="javascript:" class="payp30_black">'.$task->week39.'</a>';}

        if($task->week40 == 0){ $week40 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week40 = '<a href="javascript:" class="payp30_black">'.$task->week40.'</a>';}

        if($task->week41 == 0){ $week41 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week41 = '<a href="javascript:" class="payp30_black">'.$task->week41.'</a>';}

        if($task->week42 == 0){ $week42 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week42 = '<a href="javascript:" class="payp30_black">'.$task->week42.'</a>';}

        if($task->week43 == 0){ $week43 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week43 = '<a href="javascript:" class="payp30_black">'.$task->week43.'</a>';}

        if($task->week44 == 0){ $week44 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week44 = '<a href="javascript:" class="payp30_black">'.$task->week44.'</a>';}

        if($task->week45 == 0){ $week45 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week45 = '<a href="javascript:" class="payp30_black">'.$task->week45.'</a>';}

        if($task->week46 == 0){ $week46 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week46 = '<a href="javascript:" class="payp30_black">'.$task->week46.'</a>';}

        if($task->week47 == 0){ $week47 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week47 = '<a href="javascript:" class="payp30_black">'.$task->week47.'</a>';}

        if($task->week48 == 0){ $week48 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week48 = '<a href="javascript:" class="payp30_black">'.$task->week48.'</a>';}

        if($task->week49 == 0){ $week49 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week49 = '<a href="javascript:" class="payp30_black">'.$task->week49.'</a>';}

        if($task->week50 == 0){ $week50 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week50 = '<a href="javascript:" class="payp30_black">'.$task->week50.'</a>';}

        if($task->week51 == 0){ $week51 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week51 = '<a href="javascript:" class="payp30_black">'.$task->week51.'</a>';}

        if($task->week52 == 0){ $week52 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week52 = '<a href="javascript:" class="payp30_black">'.$task->week52.'</a>';}

        if($task->week53 == 0){ $week53 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$week53 = '<a href="javascript:" class="payp30_black">'.$task->week53.'</a>';}

        if($task->month1 == 0){ $month1 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$month1 = '<a href="javascript:" class="payp30_black">'.$task->month1.'</a>';}

        if($task->month2 == 0){ $month2 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$month2 = '<a href="javascript:" class="payp30_black">'.$task->month2.'</a>';}

        if($task->month3 == 0){ $month3 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$month3 = '<a href="javascript:" class="payp30_black">'.$task->month3.'</a>';}

        if($task->month4 == 0){ $month4 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$month4 = '<a href="javascript:" class="payp30_black">'.$task->month4.'</a>';}

        if($task->month5 == 0){ $month5 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$month5 = '<a href="javascript:" class="payp30_black">'.$task->month5.'</a>';}

        if($task->month6 == 0){ $month6 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$month6 = '<a href="javascript:" class="payp30_black">'.$task->month6.'</a>';}

        if($task->month7 == 0){ $month7 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$month7 = '<a href="javascript:" class="payp30_black">'.$task->month7.'</a>';}

        if($task->month8 == 0){ $month8 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$month8 = '<a href="javascript:" class="payp30_black">'.$task->month8.'</a>';}

        if($task->month9 == 0){ $month9 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$month9 = '<a href="javascript:" class="payp30_black">'.$task->month9.'</a>';}

        if($task->month10 == 0){ $month10 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$month10 = '<a href="javascript:" class="payp30_black">'.$task->month10.'</a>';}

        if($task->month11 == 0){ $month11 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$month11 = '<a href="javascript:" class="payp30_black">'.$task->month11.'</a>';}

        if($task->month12 == 0){ $month12 = '<div style="float:left;width:100%;text-align:center">-</div>';}else{$month12 = '<a href="javascript:" class="payp30_black">'.$task->month12.'</a>';}








    $output.='
      <div class="table-responsive" style="float: left; width:5000px;">               
            <table class="table_bg" style="width: 4700px; margin-bottom:30px">
                <tr style="width: 4000px;">
                    <td style="border-right: 1px solid #000">'.$i.'</td>                    
                    <td colspan="4"></td>
                    <td style="border-bottom: 0px; text-align: center;">ROS Liability</td>
                    <td style="border-bottom: 0px; text-align: center;">Task Liability</td>
                    <td style="border-bottom: 0px;">Diff</td>
                    <td style="border-bottom: 0px; text-align: center;">Email</td>
                    <td style="border-bottom: 0px;"></td>                    
                    <td align="left" class="payp30_week_bg">Week<br/>1</td>
                    <td align="left" class="payp30_week_bg">Week<br/>2</td>
                    <td align="left" class="payp30_week_bg">Week<br/>3</td>
                    <td align="left" class="payp30_week_bg">Week<br/>4</td>
                    <td align="left" class="payp30_week_bg">Week<br/>5</td>
                    <td align="left" class="payp30_week_bg">Week<br/>6</td>
                    <td align="left" class="payp30_week_bg">Week<br/>7</td>
                    <td align="left" class="payp30_week_bg">Week<br/>8</td>
                    <td align="left" class="payp30_week_bg">Week<br/>9</td>
                    <td align="left" class="payp30_week_bg">Week<br/>10</td>
                    <td align="left" class="payp30_week_bg">Week<br/>11</td>
                    <td align="left" class="payp30_week_bg">Week<br/>12</td>
                    <td align="left" class="payp30_week_bg">Week<br/>13</td>
                    <td align="left" class="payp30_week_bg">Week<br/>14</td>
                    <td align="left" class="payp30_week_bg">Week<br/>15</td>
                    <td align="left" class="payp30_week_bg">Week<br/>16</td>
                    <td align="left" class="payp30_week_bg">Week<br/>17</td>
                    <td align="left" class="payp30_week_bg">Week<br/>18</td>
                    <td align="left" class="payp30_week_bg">Week<br/>19</td>
                    <td align="left" class="payp30_week_bg">Week<br/>20</td>
                    <td align="left" class="payp30_week_bg">Week<br/>21</td>
                    <td align="left" class="payp30_week_bg">Week<br/>22</td>
                    <td align="left" class="payp30_week_bg">Week<br/>23</td>
                    <td align="left" class="payp30_week_bg">Week<br/>24</td>
                    <td align="left" class="payp30_week_bg">Week<br/>24</td>
                    <td align="left" class="payp30_week_bg">Week<br/>26</td>
                    <td align="left" class="payp30_week_bg">Week<br/>27</td>
                    <td align="left" class="payp30_week_bg">Week<br/>28</td>
                    <td align="left" class="payp30_week_bg">Week<br/>29</td>
                    <td align="left" class="payp30_week_bg">Week<br/>30</td>
                    <td align="left" class="payp30_week_bg">Week<br/>31</td>
                    <td align="left" class="payp30_week_bg">Week<br/>32</td>
                    <td align="left" class="payp30_week_bg">Week<br/>33</td>
                    <td align="left" class="payp30_week_bg">Week<br/>34</td>
                    <td align="left" class="payp30_week_bg">Week<br/>35</td>
                    <td align="left" class="payp30_week_bg">Week<br/>36</td>
                    <td align="left" class="payp30_week_bg">Week<br/>37</td>
                    <td align="left" class="payp30_week_bg">Week<br/>38</td>
                    <td align="left" class="payp30_week_bg">Week<br/>39</td>
                    <td align="left" class="payp30_week_bg">Week<br/>40</td>
                    <td align="left" class="payp30_week_bg">Week<br/>41</td>
                    <td align="left" class="payp30_week_bg">Week<br/>42</td>
                    <td align="left" class="payp30_week_bg">Week<br/>43</td>
                    <td align="left" class="payp30_week_bg">Week<br/>44</td>
                    <td align="left" class="payp30_week_bg">Week<br/>45</td>
                    <td align="left" class="payp30_week_bg">Week<br/>46</td>
                    <td align="left" class="payp30_week_bg">Week<br/>47</td>
                    <td align="left" class="payp30_week_bg">Week<br/>48</td>
                    <td align="left" class="payp30_week_bg">Week<br/>49</td>
                    <td align="left" class="payp30_week_bg">Week<br/>50</td>
                    <td align="left" class="payp30_week_bg">Week<br/>51</td>
                    <td align="left" class="payp30_week_bg">Week<br/>52</td>
                    <td align="left" class="payp30_week_bg">Week<br/>53</td>
                    <td align="left" class="payp30_month_bg">Jan<br/>'.$year->year_name.'</td>
                    <td align="left" class="payp30_month_bg">Feb<br/>'.$year->year_name.'</td>
                    <td align="left" class="payp30_month_bg">Mar<br/>'.$year->year_name.'</td>
                    <td align="left" class="payp30_month_bg">Apr<br/>'.$year->year_name.'</td>
                    <td align="left" class="payp30_month_bg">May<br/>'.$year->year_name.'</td>
                    <td align="left" class="payp30_month_bg">Jun<br/>'.$year->year_name.'</td>
                    <td align="left" class="payp30_month_bg">Jul<br/>'.$year->year_name.'</td>
                    <td align="left" class="payp30_month_bg">Aug<br/>'.$year->year_name.'</td>
                    <td align="left" class="payp30_month_bg">Sep<br/>'.$year->year_name.'</td>
                    <td align="left" class="payp30_month_bg">Oct<br/>'.$year->year_name.'</td>
                    <td align="left" class="payp30_month_bg">Nov<br/>'.$year->year_name.'</td>
                    <td align="left" class="payp30_month_bg">Dec<br/>'.$year->year_name.'</td>
                </tr>

                <tr>
                    <td style="border-right: 1px solid #000 !important; border-left: 1px solid #000 !important; border: 1px solid #fff;"></td>
                    <td colspan="9"  style="width: 100px; border-bottom: 0px; text-align: left;">'.$task->task_name.'</td>   

                    <td align="left" class="payp30_week_bg">'.$week1.'</td>
                    <td align="left" class="payp30_week_bg">'.$week2.'</td>
                    <td align="left" class="payp30_week_bg">'.$week3.'</td>
                    <td align="left" class="payp30_week_bg">'.$week4.'</td>
                    <td align="left" class="payp30_week_bg">'.$week5.'</td>
                    <td align="left" class="payp30_week_bg">'.$week6.'</td>
                    <td align="left" class="payp30_week_bg">'.$week7.'</td>
                    <td align="left" class="payp30_week_bg">'.$week8.'</td>
                    <td align="left" class="payp30_week_bg">'.$week9.'</td>
                    <td align="left" class="payp30_week_bg">'.$week10.'</td>
                    <td align="left" class="payp30_week_bg">'.$week11.'</td>
                    <td align="left" class="payp30_week_bg">'.$week12.'</td>
                    <td align="left" class="payp30_week_bg">'.$week13.'</td>
                    <td align="left" class="payp30_week_bg">'.$week14.'</td>
                    <td align="left" class="payp30_week_bg">'.$week15.'</td>
                    <td align="left" class="payp30_week_bg">'.$week16.'</td>
                    <td align="left" class="payp30_week_bg">'.$week17.'</td>
                    <td align="left" class="payp30_week_bg">'.$week18.'</td>
                    <td align="left" class="payp30_week_bg">'.$week19.'</td>
                    <td align="left" class="payp30_week_bg">'.$week20.'</td>
                    <td align="left" class="payp30_week_bg">'.$week21.'</td>
                    <td align="left" class="payp30_week_bg">'.$week22.'</td>
                    <td align="left" class="payp30_week_bg">'.$week23.'</td>
                    <td align="left" class="payp30_week_bg">'.$week24.'</td>
                    <td align="left" class="payp30_week_bg">'.$week25.'</td>
                    <td align="left" class="payp30_week_bg">'.$week26.'</td>
                    <td align="left" class="payp30_week_bg">'.$week27.'</td>
                    <td align="left" class="payp30_week_bg">'.$week28.'</td>
                    <td align="left" class="payp30_week_bg">'.$week29.'</td>
                    <td align="left" class="payp30_week_bg">'.$week30.'</td>
                    <td align="left" class="payp30_week_bg">'.$week31.'</td>
                    <td align="left" class="payp30_week_bg">'.$week32.'</td>
                    <td align="left" class="payp30_week_bg">'.$week33.'</td>
                    <td align="left" class="payp30_week_bg">'.$week34.'</td>
                    <td align="left" class="payp30_week_bg">'.$week35.'</td>
                    <td align="left" class="payp30_week_bg">'.$week36.'</td>
                    <td align="left" class="payp30_week_bg">'.$week37.'</td>
                    <td align="left" class="payp30_week_bg">'.$week38.'</td>
                    <td align="left" class="payp30_week_bg">'.$week39.'</td>
                    <td align="left" class="payp30_week_bg">'.$week40.'</td>
                    <td align="left" class="payp30_week_bg">'.$week41.'</td>
                    <td align="left" class="payp30_week_bg">'.$week42.'</td>
                    <td align="left" class="payp30_week_bg">'.$week43.'</td>
                    <td align="left" class="payp30_week_bg">'.$week44.'</td>
                    <td align="left" class="payp30_week_bg">'.$week45.'</td>
                    <td align="left" class="payp30_week_bg">'.$week46.'</td>
                    <td align="left" class="payp30_week_bg">'.$week47.'</td>
                    <td align="left" class="payp30_week_bg">'.$week48.'</td>
                    <td align="left" class="payp30_week_bg">'.$week49.'</td>
                    <td align="left" class="payp30_week_bg">'.$week50.'</td>
                    <td align="left" class="payp30_week_bg">'.$week51.'</td>
                    <td align="left" class="payp30_week_bg">'.$week52.'</td>
                    <td align="left" class="payp30_week_bg">'.$week53.'</td>
                    <td align="left" class="payp30_month_bg">'.$month1.'</td>
                    <td align="left" class="payp30_month_bg">'.$month2.'</td>
                    <td align="left" class="payp30_month_bg">'.$month3.'</td>
                    <td align="left" class="payp30_month_bg">'.$month4.'</td>
                    <td align="left" class="payp30_month_bg">'.$month5.'</td>
                    <td align="left" class="payp30_month_bg">'.$month6.'</td>
                    <td align="left" class="payp30_month_bg">'.$month7.'</td>
                    <td align="left" class="payp30_month_bg">'.$month8.'</td>
                    <td align="left" class="payp30_month_bg">'.$month9.'</td>
                    <td align="left" class="payp30_month_bg">'.$month10.'</td>
                    <td align="left" class="payp30_month_bg">'.$month11.'</td>
                    <td align="left" class="payp30_month_bg">'.$month12.'</td>                    
                </tr>

                <tr>
                    <td style="border-right: 1px solid #000 !important; border-left: 1px solid #000 !important; border: 1px solid #fff;"></td>
                    <td style="width: 100px; border-bottom: 0px; text-align: left;">Emp No.</td>
                    <td style="width: 130px; border-bottom: 0px; text-align: left;">'.$task->task_enumber.'</td>                                
                    <td style="width: 40px; text-align: right; border-bottom: 0px;"><input type="checkbox" name=""><label>&nbsp;</label></td>
                    <td style="width: 70px; border-bottom: 0px;">Jan-19</td>
                    <td style="width: 100px; border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control" value="156.19"></td>
                    <td style="width: 100px; border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control" value="208.26" readonly></td>
                    <td style="width: 120px; border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control" value="-52.07" readonly></td>
                    <td style="width: 200px; ">05 Feb 2019 @ 12.40</td>
                    <td style="width: 50px; "><a href="javascript:"><i class="fa fa-refresh"></i></a></td>                    
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg"><a href="javascript:" class="payp30_green">152.06</a></td>
                    <td align="left" class="payp30_week_bg"><a href="javascript:" class="payp30_green">52.06</a></td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_month_bg"><a href="javascript:" class="payp30_green">52.06</a></td>
                    <td align="left" class="payp30_month_bg"><a href="javascript:" class="payp30_green">52.06</a></td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                </tr>
                <tr>
                    <td style="border-right: 1px solid #000 !important; border-left: 1px solid #000 !important; border: 1px solid #fff;"></td>
                    <td style="border-bottom: 0px; text-align: left;">Action</td>
                    <td style="border-bottom: 0px; text-align: left;">'.$action.'</td>                                
                    <td style="border-bottom: 0px; text-align: right;"><input type="checkbox" name=""><label>&nbsp;</label></td>
                    <td style="border-bottom: 0px;">Feb-19</td>
                    <td style="border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control" value="156.19" ></td>
                    <td style="border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control" value="208.26" readonly></td>
                    <td style="border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control" value="-52.07" readonly></td>
                    <td></td>
                    <td></td>                    
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg"><a href="javascript:" class="payp30_green">10</a></td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg"><a href="javascript:" class="payp30_green">52.06</a></td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg"><a href="javascript:" class="payp30_green">52.06</a></td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                </tr>
                <tr>
                    <td style="border-right: 1px solid #000 !important; border-left: 1px solid #000 !important; border: 1px solid #fff;"></td>
                    <td style="border-bottom: 0px; text-align: left;">PAY</td>
                    <td style="border-bottom: 0px; text-align: left;">'.$pay.'</td>                                
                    <td style="border-bottom: 0px; text-align: right;"><input type="checkbox" name=""><label>&nbsp;</label></td>
                    <td style="border-bottom: 0px;">Mar-19</td>
                    <td style="border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control" value="156.19" ></td>
                    <td style="border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control" value="208.26" readonly></td>
                    <td style="border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control" value="-52.07" readonly></td>
                    <td></td>
                    <td></td>                    
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                </tr>
                <tr>
                    <td style="border-right: 1px solid #000 !important; border-left: 1px solid #000 !important; border: 1px solid #fff;"></td>
                    <td style="border-bottom: 0px; text-align: left;">Email</td>
                    <td style="border-bottom: 0px; text-align: left;">'.$email.'</td>                                
                    <td style="border-bottom: 0px; text-align: right;"><input type="checkbox" name=""><label>&nbsp;</label></td>
                    <td style="border-bottom: 0px;">Apr-19</td>
                    <td style="border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control" value="156.19" ></td>
                    <td style="border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control" value="208.26" readonly></td>
                    <td style="border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control" value="-52.07" readonly></td>
                    <td></td>
                    <td></td>                    
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg"><a href="javascript:" class="payp30_green">52.06</a></td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                </tr>
                <tr>
                    <td style="border-right: 1px solid #000 !important; border-left: 1px solid #000 !important; border: 1px solid #fff;"></td>
                    <td style="width: 100px; border-bottom: 0px;"></td>
                    <td style="width: 100px; border-bottom: 0px;"></td>                                
                    <td style="width: 70px; border-bottom: 0px; text-align: right;"><input type="checkbox" name=""><label>&nbsp;</label></td>
                    <td style="width: 70px; border-bottom: 0px;">May-19</td>
                    <td style="border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control" value="156.19" ></td>
                    <td style="border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control" value="208.26" readonly></td>
                    <td style="border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control" value="-52.07" readonly></td>
                    <td></td>
                    <td></td>                    
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                </tr>
                <tr>
                    <td style="border-right: 1px solid #000 !important; border-left: 1px solid #000 !important; border: 1px solid #fff;"></td>
                    <td style="width: 100px; border-bottom: 0px;"></td>
                    <td style="width: 100px; border-bottom: 0px;"></td>                                
                    <td style="width: 70px; border-bottom: 0px; text-align: right;"><input type="checkbox" name=""><label>&nbsp;</label></td>
                    <td style="width: 70px; border-bottom: 0px;">Jun-19</td>
                    <td style="border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control" value="156.19" ></td>
                    <td style="border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control" value="208.26" readonly></td>
                    <td style="border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control" value="-52.07" readonly></td>
                    <td></td>
                    <td></td>                    
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                </tr>
                <tr>
                    <td style="border-right: 1px solid #000 !important; border-left: 1px solid #000 !important; border: 1px solid #fff;"></td>
                    <td style="width: 100px; border-bottom: 0px;"></td>
                    <td style="width: 100px; border-bottom: 0px;"></td>                                
                    <td style="width: 70px; border-bottom: 0px; text-align: right;"><input type="checkbox" name=""><label>&nbsp;</label></td>
                    <td style="width: 70px; border-bottom: 0px;">Jul-19</td>
                    <td style="border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control" value="156.19" ></td>
                    <td style="border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control" value="208.26" readonly></td>
                    <td style="border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control" value="-52.07" readonly></td>
                    <td></td>
                    <td></td>                    
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                </tr>
                <tr>
                    <td style="border-right: 1px solid #000 !important; border-left: 1px solid #000 !important; border: 1px solid #fff;"></td>
                    <td style="width: 100px; border-bottom: 0px;"></td>
                    <td style="width: 100px; border-bottom: 0px;"></td>                                
                    <td style="width: 70px; border-bottom: 0px; text-align: right;"><input type="checkbox" name=""><label>&nbsp;</label></td>
                    <td style="width: 70px; border-bottom: 0px;">Aug-19</td>
                    <td style="border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control" value="156.19" ></td>
                    <td style="border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control" value="208.26" readonly></td>
                    <td style="border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control" value="-52.07" readonly></td>
                    <td></td>
                    <td></td>                    
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                </tr>
                <tr>
                    <td style="border-right: 1px solid #000 !important; border-left: 1px solid #000 !important; border: 1px solid #fff;"></td>
                    <td style="width: 100px; border-bottom: 0px;"></td>
                    <td style="width: 100px; border-bottom: 0px;"></td>                                
                    <td style="width: 70px; border-bottom: 0px; text-align: right;"><input type="checkbox" name=""><label>&nbsp;</label></td>
                    <td style="width: 70px; border-bottom: 0px;">Sep-19</td>
                    <td style="border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control" value="156.19" ></td>
                    <td style="border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control" value="208.26" readonly></td>
                    <td style="border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control" value="-52.07" readonly></td>
                    <td></td>
                    <td></td>                    
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                </tr>
                <tr>
                    <td style="border-right: 1px solid #000 !important; border-left: 1px solid #000 !important; border: 1px solid #fff;"></td>
                    <td style="width: 100px; border-bottom: 0px;"></td>
                    <td style="width: 100px; border-bottom: 0px;"></td>                                
                    <td style="width: 70px; border-bottom: 0px; text-align: right;"><input type="checkbox" name=""><label>&nbsp;</label></td>
                    <td style="width: 70px; border-bottom: 0px;">Oct-19</td>
                    <td style="border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control" value="156.19" ></td>
                    <td style="border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control" value="208.26" readonly></td>
                    <td style="border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control" value="-52.07" readonly></td>
                    <td></td>
                    <td></td>                    
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                </tr>
                <tr>
                    <td style="border-right: 1px solid #000 !important; border-left: 1px solid #000 !important; border: 1px solid #fff;"></td>
                    <td style="width: 100px; border-bottom: 0px;"></td>
                    <td style="width: 100px; border-bottom: 0px;"></td>                                
                    <td style="width: 70px; border-bottom: 0px; text-align: right;"><input type="checkbox" name=""><label>&nbsp;</label></td>
                    <td style="width: 70px; border-bottom: 0px;">Nov-19</td>
                    <td style="border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control" value="156.19" ></td>
                    <td style="border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control" value="208.26" readonly></td>
                    <td style="border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control" value="-52.07" readonly></td>
                    <td></td>
                    <td></td>                    
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                </tr>
                <tr>
                    <td style="border-right: 1px solid #000 !important; border-left: 1px solid #000 !important; border-bottom: 1px solid #000 !important; border: 1px solid #fff;"></td>
                    <td style="width: 100px; border-bottom: 0px;"></td>
                    <td style="width: 100px; border-bottom: 0px;"></td>                                
                    <td style="width: 70px; border-bottom: 0px; text-align: right;"><input type="checkbox" name=""><label>&nbsp;</label></td>
                    <td style="width: 70px; border-bottom: 0px;">Dec-19</td>
                    <td style="border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control" value="156.19" ></td>
                    <td style="border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control" value="208.26" readonly></td>
                    <td style="border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control" value="-52.07" readonly></td>
                    <td></td>
                    <td></td>                    
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_week_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                    <td align="left" class="payp30_month_bg">-</td>
                </tr>
            </table>   
            ';
            $i++;
    }
  }
  else{
    $output='
    <div class="table-responsive" style="float: left; width:5000px;">               
        <table class="table_bg" style="width: 4700px; margin-bottom:30px">
            <tr style="width: 4000px;">
                <td style="border-right: 1px solid #000">1</td>                    
                <td colspan="4"></td>
                <td style="border-bottom: 0px; text-align: center;">ROS Liability</td>
                <td style="border-bottom: 0px; text-align: center;">Task Liability</td>
                <td style="border-bottom: 0px;">Diff</td>
                <td style="border-bottom: 0px; text-align: center;">Email</td>
                <td style="border-bottom: 0px;"></td>                    
                <td align="left" class="payp30_week_bg">Week<br/>1</td>
                <td align="left" class="payp30_week_bg">Week<br/>2</td>
                <td align="left" class="payp30_week_bg">Week<br/>3</td>
                <td align="left" class="payp30_week_bg">Week<br/>4</td>
                <td align="left" class="payp30_week_bg">Week<br/>5</td>
                <td align="left" class="payp30_week_bg">Week<br/>6</td>
                <td align="left" class="payp30_week_bg">Week<br/>7</td>
                <td align="left" class="payp30_week_bg">Week<br/>8</td>
                <td align="left" class="payp30_week_bg">Week<br/>9</td>
                <td align="left" class="payp30_week_bg">Week<br/>10</td>
                <td align="left" class="payp30_week_bg">Week<br/>11</td>
                <td align="left" class="payp30_week_bg">Week<br/>12</td>
                <td align="left" class="payp30_week_bg">Week<br/>13</td>
                <td align="left" class="payp30_week_bg">Week<br/>14</td>
                <td align="left" class="payp30_week_bg">Week<br/>15</td>
                <td align="left" class="payp30_week_bg">Week<br/>16</td>
                <td align="left" class="payp30_week_bg">Week<br/>17</td>
                <td align="left" class="payp30_week_bg">Week<br/>18</td>
                <td align="left" class="payp30_week_bg">Week<br/>19</td>
                <td align="left" class="payp30_week_bg">Week<br/>20</td>
                <td align="left" class="payp30_week_bg">Week<br/>21</td>
                <td align="left" class="payp30_week_bg">Week<br/>22</td>
                <td align="left" class="payp30_week_bg">Week<br/>23</td>
                <td align="left" class="payp30_week_bg">Week<br/>24</td>
                <td align="left" class="payp30_week_bg">Week<br/>24</td>
                <td align="left" class="payp30_week_bg">Week<br/>26</td>
                <td align="left" class="payp30_week_bg">Week<br/>27</td>
                <td align="left" class="payp30_week_bg">Week<br/>28</td>
                <td align="left" class="payp30_week_bg">Week<br/>29</td>
                <td align="left" class="payp30_week_bg">Week<br/>30</td>
                <td align="left" class="payp30_week_bg">Week<br/>31</td>
                <td align="left" class="payp30_week_bg">Week<br/>32</td>
                <td align="left" class="payp30_week_bg">Week<br/>33</td>
                <td align="left" class="payp30_week_bg">Week<br/>34</td>
                <td align="left" class="payp30_week_bg">Week<br/>35</td>
                <td align="left" class="payp30_week_bg">Week<br/>36</td>
                <td align="left" class="payp30_week_bg">Week<br/>37</td>
                <td align="left" class="payp30_week_bg">Week<br/>38</td>
                <td align="left" class="payp30_week_bg">Week<br/>39</td>
                <td align="left" class="payp30_week_bg">Week<br/>40</td>
                <td align="left" class="payp30_week_bg">Week<br/>41</td>
                <td align="left" class="payp30_week_bg">Week<br/>42</td>
                <td align="left" class="payp30_week_bg">Week<br/>43</td>
                <td align="left" class="payp30_week_bg">Week<br/>44</td>
                <td align="left" class="payp30_week_bg">Week<br/>45</td>
                <td align="left" class="payp30_week_bg">Week<br/>46</td>
                <td align="left" class="payp30_week_bg">Week<br/>47</td>
                <td align="left" class="payp30_week_bg">Week<br/>48</td>
                <td align="left" class="payp30_week_bg">Week<br/>49</td>
                <td align="left" class="payp30_week_bg">Week<br/>50</td>
                <td align="left" class="payp30_week_bg">Week<br/>51</td>
                <td align="left" class="payp30_week_bg">Week<br/>52</td>
                <td align="left" class="payp30_week_bg">Week<br/>53</td>
                <td align="left" class="payp30_month_bg">Jan<br/>'.$year->year_name.'</td>
                <td align="left" class="payp30_month_bg">Feb<br/>'.$year->year_name.'</td>
                <td align="left" class="payp30_month_bg">Mar<br/>'.$year->year_name.'</td>
                <td align="left" class="payp30_month_bg">Apr<br/>'.$year->year_name.'</td>
                <td align="left" class="payp30_month_bg">May<br/>'.$year->year_name.'</td>
                <td align="left" class="payp30_month_bg">Jun<br/>'.$year->year_name.'</td>
                <td align="left" class="payp30_month_bg">Jul<br/>'.$year->year_name.'</td>
                <td align="left" class="payp30_month_bg">Aug<br/>'.$year->year_name.'</td>
                <td align="left" class="payp30_month_bg">Sep<br/>'.$year->year_name.'</td>
                <td align="left" class="payp30_month_bg">Oct<br/>'.$year->year_name.'</td>
                <td align="left" class="payp30_month_bg">Nov<br/>'.$year->year_name.'</td>
                <td align="left" class="payp30_month_bg">Dec<br/>'.$year->year_name.'</td>
            </tr>
            <tr>
                <td colspan="30">Empty</td>
                <td colspan="30">Empty</td>
                <td colspan="18">Empty</td>
            </tr>
        </table>
    <div>
            ';
  }
  echo $output;
  ?>

    </div>
  </div>
</div>
@stop


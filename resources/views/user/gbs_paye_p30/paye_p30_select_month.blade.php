@extends('userheader')
@section('content')
<script src='<?php echo URL::to('assets/js/table-fixed-header_cm.js'); ?>'></script>
<style>
#review_month{
  font-size: 16px;
    font-weight: 600;
    padding: 7px;
}
.selectall_task_label{
  font-weight:800;
}
.disabled{
  background: #bdbcbc;
}
.task_label{
  background: none !important;
}
.body_bg{
  background: #03d4b7 !important;
}
.fa-plus{

  background: none !important;

}

.disable_button{

  cursor: not-allowed;

}

.disable_button a{

  pointer-events: none;

}

.text_checkbox{

      margin-top: 10px;

    color: #000;

    font-weight: 700;

}

.comments_input{

    margin-top: 10px;

    width: 235%;

    height: 200px !important;

}

.uname_input{

  margin-top: 0px;

}

.task_email_input{

  margin-top: 0px;

}

.date_input{

  margin-top: 0px;

  margin-bottom: 10px

}

.time_input{

  margin-top: 0px;

}

.footer_row{

   display:none !important;

}

.modal{

  z-index:99999 !important;

}

.attach_align{

  text-align: left !important;

}

.copy_label{

      font-size: 12px;

    color: #000;

    text-align: left;

    padding: 3px 14px;

}

.fileattachment{

  font-weight:800;

  color:#fff !important;

}

.fileattachment:hover{

  font-weight:800;

  color:#fff !important;

}

.fa-sort{

  cursor: pointer;

}

.table_bg tbody tr td{

  padding:8px;

  border-bottom:1px solid #000;

  text-align: left;

}

.table_bg thead tr th{

  padding:8px;

  text-align: left;

}

.email_sort_std,.email_sort_enh,.email_sort_cmp{

  width:10% !important;

}

.task_sort_std,.task_sort_enh,.task_sort_cmp{

  text-align: left !important;

}

.task_sort_std_val,.task_sort_enh_val,.task_sort_cmp_val{

  text-align: left !important;

}

.task_tr_std,.task_tr_enh,.task_tr_cmp

{

  vertical-align: top !important;

}

.page_title{

  margin-bottom: 0px !important;

  padding: 20px !important;

      background: #03d4b7;

}

.button_top_right ul{

      margin: 0px 0px 0px 0px !important;

}

.error_files{

  color:#f00;

  font-weight:800;

}

.email_unsent_label{

  width:100%;

}

.download_div

{

    position: absolute;

    top: 34px;

    left:47%;

    width: 28%;

    background: #ff0;

    padding: 9px;

    line-height: 31px;

}

.notify_div

{

    position: absolute;

    top: 34px;

    left:67%;

    width: 28%;

    background: #ff0;

    padding: 9px;

    line-height: 31px;

}

.close_xmark{

       position: absolute;

    right: 0px;

    top: 0;

    font-weight: 800;

    padding: 0px 5px;

    background: #000000;

    color: #ffcd44;

    font-size: 10px;

}

.close_xmark:focus, .close_xmark:hover {

    color: #641500;

    text-decoration: none !important;

}

.download_button

{

      background: #000;

    color: #fff;

    padding: 5px 10px;

    float: left;

    font-size: 13px;

    font-weight: normal;

    text-transform: none;

}

.download_radio{

        width: 85%;

    clear: both;

    float: left;

    padding: 5px;

    margin-left: 15px;

    border-bottom: 1px solid;

}

.download_radio:hover{

            width: 85%;

    clear: both;

    float: left;

    padding: 5px;

    margin-left: 15px;

    border-bottom: 1px solid #000;

    background: #000;

    color: #fff;

}

.notify_radio{

        width: 85%;

    clear: both;

    float: left;

    padding: 5px;

    margin-left: 15px;

    border-bottom: 1px solid;

}

.notify_radio:hover{

            width: 85%;

    clear: both;

    float: left;

    padding: 5px;

    margin-left: 15px;

    border-bottom: 1px solid #000;

    background: #000;

    color: #fff;

}

.modal_load {

    display:    none;

    position:   fixed;

    z-index:    1000;

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

<?php if(Session::has('message_import')) { ?>

  <div class="modal fade p30_review_output_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">

    <div class="modal-dialog modal-lg" role="document" style="width:80%">

      <div class="modal-content">

          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            <h4 class="modal-title">P30 Review Due</h4>

          </div>

          <div class="modal-body" style="height:500px; overflow-y: scroll">

            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">

              

                Fields that did not have a corresponding ER Number :

              

            </div>

            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 col-lg-offset-4 col-md-offset-4 col-sm-offset-4 col-xs-offset-4">

                <a href="javascript:" class="btn btn-primary btn-sm pdf_button" id="pdf_dont_ernumber">Download PDF</a>

            </div>

            <div class="select_button">

              <table class="table">

                <thead>

                  <th>S.no</th>

                  <th>P30 Task</th>

                  <th>Task Level</th>

                  <th>Pay</th>

                  <th>Period</th>

                  <th>Email</th>

                  <th>Liability</th>

                </thead>

                <tbody>

                <?php 

                  $dont_er = DB::table('p30_task')->where('task_month',$monthid->month_id)->where('na',1)->get();

                  $output ='';

                  if(count($dont_er))

                  {

                    $i = 1;

                    foreach($dont_er as $task)

                    {

                      $task_level = DB::table('p30_tasklevel')->where('id',$task->task_level)->first();

                      if(count($task_level))

                      {

                        $task_level_val = $task_level->name;

                      } 

                      else{

                        $task_level_val = '';

                      }

                      $period = DB::table('p30_period')->where('id',$task->task_period)->first();

                      if(count($period))

                      {

                        $period_val = $period->name;

                      } 

                      else{

                        $period_val = '';

                      }

                      if($task->pay == 1) { $pay = 'Yes';} else { $pay = 'No'; }



                      $output.='<tr>

                      <td style="border:1px solid #000; text-align:left">'.$i.'</td>

                      <td style="border:1px solid #000; text-align:left">'.$task->task_name.'</td>

                      <td style="border:1px solid #000; text-align:left">'.$task_level_val.'</td>

                      <td style="border:1px solid #000; text-align:left">'.$pay.'</td>

                      <td style="border:1px solid #000; text-align:left">'.$period_val.'</td>

                      <td style="border:1px solid #000; text-align:left">'.$task->task_email.'</td>

                      <td style="border:1px solid #000; text-align:left">'.$task->liability.'</td>

                      </tr>';

                      $i++;

                    }

                  }

                  else{

                    $output.='<tr><td colspan="7">No Task Found</td></tr>';

                  }

                  echo $output;

                ?>

                </tbody>

              </table>

            </div>



            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">

              

                Fields that have the NA checked :

              

            </div>

            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 col-lg-offset-4 col-md-offset-4 col-sm-offset-4 col-xs-offset-4">

                <a href="javascript:" class="btn btn-primary btn-sm pdf_button" id="pdf_na_checked">Download PDF</a>

            </div>

            <div class="select_button">

              <table class="table">

                <thead>

                  <th>S.no</th>

                  <th>P30 Task</th>

                  <th>Task Level</th>

                  <th>Pay</th>

                  <th>Period</th>

                  <th>Email</th>

                  <th>Liability</th>

                </thead>

                <tbody>

                <?php 

                  $dont_er = DB::table('p30_task')->where('task_month',$monthid->month_id)->where('na',1)->get();

                  $output ='';

                  if(count($dont_er))

                  {

                    $i = 1;

                    foreach($dont_er as $task)

                    {

                      $task_level = DB::table('p30_tasklevel')->where('id',$task->task_level)->first();

                      if(count($task_level))

                      {

                        $task_level_val = $task_level->name;

                      } 

                      else{

                        $task_level_val = '';

                      }

                      $period = DB::table('p30_period')->where('id',$task->task_period)->first();

                      if(count($period))

                      {

                        $period_val = $period->name;

                      } 

                      else{

                        $period_val = '';

                      }

                      if($task->pay == 1) { $pay = 'Yes';} else { $pay = 'No'; }



                      $output.='<tr>

                      <td style="border:1px solid #000; text-align:left">'.$i.'</td>

                      <td style="border:1px solid #000; text-align:left">'.$task->task_name.'</td>

                      <td style="border:1px solid #000; text-align:left">'.$task_level_val.'</td>

                      <td style="border:1px solid #000; text-align:left">'.$pay.'</td>

                      <td style="border:1px solid #000; text-align:left">'.$period_val.'</td>

                      <td style="border:1px solid #000; text-align:left">'.$task->task_email.'</td>

                      <td style="border:1px solid #000; text-align:left">'.$task->liability.'</td>

                      </tr>';

                      $i++;

                    }

                  }

                  else{

                    $output.='<tr><td colspan="7">No Task Found</td></tr>';

                  }

                  echo $output;

                ?>

                </tbody>

              </table>

            </div>





            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">

              

                Fields that have the NA NOT checked! :

              

            </div>

            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 col-lg-offset-4 col-md-offset-4 col-sm-offset-4 col-xs-offset-4">

                <a href="javascript:" class="btn btn-primary btn-sm pdf_button" id="pdf_na_not_checked">Download PDF</a>

            </div>

            <div class="select_button">

              <table class="table">

                <thead>

                  <th>S.no</th>

                  <th>P30 Task</th>

                  <th>Task Level</th>

                  <th>Pay</th>

                  <th>Period</th>

                  <th>Email</th>

                  <th>Liability</th>

                </thead>

                <tbody>

                <?php 

                  $dont_er = DB::table('p30_task')->where('task_month',$monthid->month_id)->where('na',2)->get();

                  $output ='';

                  if(count($dont_er))

                  {

                    $i = 1;

                    foreach($dont_er as $task)

                    {

                      $task_level = DB::table('p30_tasklevel')->where('id',$task->task_level)->first();

                      if(count($task_level))

                      {

                        $task_level_val = $task_level->name;

                      } 

                      else{

                        $task_level_val = '';

                      }

                      $period = DB::table('p30_period')->where('id',$task->task_period)->first();

                      if(count($period))

                      {

                        $period_val = $period->name;

                      } 

                      else{

                        $period_val = '';

                      }

                      if($task->pay == 1) { $pay = 'Yes';} else { $pay = 'No'; }



                      $output.='<tr>

                      <td style="border:1px solid #000; text-align:left">'.$i.'</td>

                      <td style="border:1px solid #000; text-align:left">'.$task->task_name.'</td>

                      <td style="border:1px solid #000; text-align:left">'.$task_level_val.'</td>

                      <td style="border:1px solid #000; text-align:left">'.$pay.'</td>

                      <td style="border:1px solid #000; text-align:left">'.$period_val.'</td>

                      <td style="border:1px solid #000; text-align:left">'.$task->task_email.'</td>

                      <td style="border:1px solid #000; text-align:left">'.$task->liability.'</td>

                      </tr>';

                      $i++;

                    }

                  }

                  else{

                    $output.='<tr><td colspan="7">No Task Found</td></tr>';

                  }

                  echo $output;

                ?>

                </tbody>

              </table>

            </div>

          </div>

      </div>

    </div>

  </div>

  <?php

}

?>

<div class="content_section">

  <div class="page_title" style="position:fixed;margin-top: -17px;">

    <div class="col-lg-6 padding_00">
      Task of <?php echo $yearname->year_name ?> &nbsp;&nbsp;&nbsp;&nbsp; Month : Month <?php echo $monthid->month ?>

      
        <?php $user_login_details = DB::table('user_login')->where('id',1)->first(); ?>
        <input type="button" name="show_hide_task" id="show_hide_task" class="common_black_button show_hide_task <?php if($user_login_details->paye_hide_task == 1) { echo 'show_task'; } else { echo 'hide_task'; } ?>" Value="<?php if($user_login_details->paye_hide_task == 1) { echo 'Show Tasks'; } else { echo 'Hide Tasks'; } ?>">
      
    </div>

    <div class="col-lg-6 padding_00 button_top_right">

      <ul style="margin-right: 13%;">

        <?php $check_current_year = DB::table('paye_p30_year')->orderBy('year_id','desc')->first(); ?>

        

        <?php $check_current_month = DB::table('paye_p30_month')->orderBy('month_id','desc')->first(); ?>

        <li class="<?php if($check_current_month->month_id == $monthid->month_id) { echo ''; } else { echo 'disable_button'; }?>"><a href="<?php echo URL::to('user/gbs_paye_p30_review_month/'.$monthid->month_id); ?>" id="review_month">Review Month</a></li>

        <li> <input type="button" name="show_hide_columns" id="show_hide_columns" class="common_black_button show_hide_columns <?php if($user_login_details->paye_hide_task == 1) { echo 'show_column'; } else { echo 'hide_column'; } ?>" Value="<?php if($user_login_details->paye_hide_task == 1) { echo 'Show Columns'; } else { echo 'Hide Columns'; } ?>"></li>

        <!-- <?php $check_incomplete = Db::table('user_login')->where('userid',1)->first(); 

        if($check_incomplete->p30_incomplete == 1) { $inc_checked = 'checked="checked"'; } else { $inc_checked = ''; }

        if($check_incomplete->p30_na == 1) { $incna_checked = 'checked="checked"'; } else { $incna_checked = ''; } ?>

        <br/> 

        <input type="checkbox" name="show_incomplete" id="show_incomplete" value="1" <?php echo $inc_checked; ?>><label for="show_incomplete">Show Incomplete Only</label>

        <input type="checkbox" name="show_na" id="show_na" value="1" <?php echo $incna_checked; ?>><label for="show_na">Hide/Show N/A P30's</label> -->

      </ul>

    </div>

  </div>

<div style="width:100%;float:left; margin-top: 55px; margin-bottom: -66px;">

<?php

if(Session::has('message')) { ?>

    <p class="alert alert-info" style="clear:both; margin-top: 30px;"><?php echo Session::get('message'); ?></p>

<?php }

if(Session::has('error')) { ?>

    <p class="alert alert-danger" style="clear:both; margin-top: 30px;"><?php echo Session::get('error'); ?></p>

<?php }

?>

</div>



<div class="modal fade emailunsent" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">

  <div class="modal-dialog" role="document">

    <form action="<?php echo URL::to('user/gbs_paye_p30_email_unsent_files'); ?>" method="post" >

    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        <h4 class="modal-title" id="myModalLabel">Paye MRS Email</h4>

      </div>

      <div class="modal-body">

          <div class="row">

            <div class="col-md-3">

              <label>From</label>

            </div>

            <div class="col-md-9">

              <select name="from_user" id="from_user" class="form-control input-sm" value="" required>

                  <option value="">Select User</option>

                  <?php

                    $users = DB::table('user')->where('user_status',0)->where('disabled',0)->where('email','!=', '')->orderBy('firstname','asc')->get();

                    if(count($users))

                    {

                      foreach($users as $user)

                      {

                          ?>

                            <option value="<?php echo trim($user->email); ?>"><?php echo $user->lastname.' '.$user->firstname; ?></option>

                          <?php

                      }

                    }

                  ?>

              </select>

            </div>

          </div>

          <div class="row" style="margin-top:10px">

            <div class="col-md-3">

              <label>To</label>

            </div>

            <div class="col-md-9">

              <input type="text" name="to_user" id="to_user" class="form-control input-sm" value="" required>

            </div>

          </div>

          <div class="row" style="margin-top:10px">

            <div class="col-md-3">

              <label>CC</label>

            </div>

            <div class="col-md-9">

              <?php 

                $admin_details = Db::table('admin')->first();

                $admin_cc = $admin_details->p30_cc_email;

              ?>

              <input type="text" name="cc_unsent" class="form-control input-sm" value="<?php echo $admin_cc; ?>" readonly>

            </div>

          </div>

          <div class="row" style="margin-top:10px">

            <div class="col-md-3">

              <label>Subject</label>

            </div>

            <div class="col-md-9">

              <input type="text" name="subject_unsent" class="form-control input-sm subject_unsent" value="" required>

            </div>

          </div>

          <div class="row" style="margin-top:10px">

            <div class="col-md-12">

              <label>Message</label>

            </div>

            <div class="col-md-12">

              <textarea name="message_editor" id="editor_1">

                  

              </textarea>

            </div>

          </div>
      </div>

      <div class="modal-footer">

        <input type="hidden" name="hidden_email_task_id" id="hidden_email_task_id" value="">

        <input type="submit" class="btn btn-primary common_black_button email_unsent_button" value="Send Email">

      </div>

    </div>

    </form>

  </div>

</div>

  

    <div class="table-responsive" style="width:6000px; margin-top:55px">
      <table class="table_bg table-fixed-header" style="width: 100%; margin: 0px auto;">
          <thead class='header'>
            <tr class="background_bg">
                <th>S.No</th>
                <th>Hide / Show</th>
                <th style="width:400px">PAYE MRS Task</th>
                <th>ROS Liability</th>
                <th>Task Liability</th>     
                <th>Diff</th>
                <th class="show_columns_td week1">Week 1</th>
                <th class="show_columns_td week2">Week 2</th>
                <th class="show_columns_td week3">Week 3</th>
                <th class="show_columns_td week4">Week 4</th>
                <th class="show_columns_td week5">Week 5</th>
                <th class="show_columns_td week6">Week 6</th>
                <th class="show_columns_td week7">Week 7</th>
                <th class="show_columns_td week8">Week 8</th>
                <th class="show_columns_td week9">Week 9</th>
                <th class="show_columns_td week10">Week 10</th>
                <th class="show_columns_td week11">Week 11</th>
                <th class="show_columns_td week12">Week 12</th>
                <th class="show_columns_td week13">Week 13</th>
                <th class="show_columns_td week14">Week 14</th>
                <th class="show_columns_td week15">Week 15</th>
                <th class="show_columns_td week16">Week 16</th>
                <th class="show_columns_td week17">Week 17</th>
                <th class="show_columns_td week18">Week 18</th>
                <th class="show_columns_td week19">Week 19</th>
                <th class="show_columns_td week20">Week 20</th>
                <th class="show_columns_td week21">Week 21</th>
                <th class="show_columns_td week22">Week 22</th>
                <th class="show_columns_td week23">Week 23</th>
                <th class="show_columns_td week24">Week 24</th>
                <th class="show_columns_td week25">Week 25</th>
                <th class="show_columns_td week26">Week 26</th>
                <th class="show_columns_td week27">Week 27</th>
                <th class="show_columns_td week28">Week 28</th>
                <th class="show_columns_td week29">Week 29</th>
                <th class="show_columns_td week30">Week 30</th>
                <th class="show_columns_td week31">Week 31</th>
                <th class="show_columns_td week32">Week 32</th>
                <th class="show_columns_td week33">Week 33</th>
                <th class="show_columns_td week34">Week 34</th>
                <th class="show_columns_td week35">Week 35</th>
                <th class="show_columns_td week36">Week 36</th>
                <th class="show_columns_td week37">Week 37</th>
                <th class="show_columns_td week38">Week 38</th>
                <th class="show_columns_td week39">Week 39</th>
                <th class="show_columns_td week40">Week 40</th>
                <th class="show_columns_td week41">Week 41</th>
                <th class="show_columns_td week42">Week 42</th>
                <th class="show_columns_td week43">Week 43</th>
                <th class="show_columns_td week44">Week 44</th>
                <th class="show_columns_td week45">Week 45</th>
                <th class="show_columns_td week46">Week 46</th>
                <th class="show_columns_td week47">Week 47</th>
                <th class="show_columns_td week48">Week 48</th>
                <th class="show_columns_td week49">Week 49</th>
                <th class="show_columns_td week50">Week 50</th>
                <th class="show_columns_td week51">Week 51</th>
                <th class="show_columns_td week52">Week 52</th>
                <th class="show_columns_td week53">Week 53</th>
                <th class="show_columns_td month1">Jan-<?php echo $yearname->year_name ?></th>
                <th class="show_columns_td month2">Feb-<?php echo $yearname->year_name ?></th>
                <th class="show_columns_td month3">Mar-<?php echo $yearname->year_name ?></th>
                <th class="show_columns_td month4">Apr-<?php echo $yearname->year_name ?></th>
                <th class="show_columns_td month5">May-<?php echo $yearname->year_name ?></th>
                <th class="show_columns_td month6">Jun-<?php echo $yearname->year_name ?></th>
                <th class="show_columns_td month7">Jul-<?php echo $yearname->year_name ?></th>
                <th class="show_columns_td month8">Aug-<?php echo $yearname->year_name ?></th>
                <th class="show_columns_td month9">Sep-<?php echo $yearname->year_name ?></th>
                <th class="show_columns_td month10">Oct-<?php echo $yearname->year_name ?></th>
                <th class="show_columns_td month11">Nov-<?php echo $yearname->year_name ?></th>
                <th class="show_columns_td month12">Dec-<?php echo $yearname->year_name ?></th>
                <th>Email</th>                
            </tr>
            <tr style="background: #03d4b7;">
                <th style="border-left:1px solid #000"></th>
                <th><input type="checkbox" name="selectall_task" class="selectall_task" id="selectall_task"><label for="selectall_task" class="selectall_task_label">Select All </label></th>
                <th></th>
                <th></th>
                <th></th>     
                <th><input type="checkbox" name="selectall_columns" class="selectall_columns" id="selectall_columns"><label for="selectall_columns" class="selectall_columns_label">Select All </label> </th>
                <th class="show_columns_td week1"><input type="checkbox" name="week1" class="week1_show show_colnmns" data-element="week1" id="week1_show" <?php if($user_login_details->week1_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week1_show">&nbsp;</label></th>
                <th class="show_columns_td week2"><input type="checkbox" name="week2" class="week2_show show_colnmns" data-element="week2" id="week2_show" <?php if($user_login_details->week2_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week2_show">&nbsp;</label></th>
                <th class="show_columns_td week3"><input type="checkbox" name="week3" class="week3_show show_colnmns" data-element="week3" id="week3_show" <?php if($user_login_details->week3_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week3_show">&nbsp;</label></th>
                <th class="show_columns_td week4"><input type="checkbox" name="week4" class="week4_show show_colnmns" data-element="week4" id="week4_show" <?php if($user_login_details->week4_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week4_show">&nbsp;</label></th>
                <th class="show_columns_td week5"><input type="checkbox" name="week5" class="week5_show show_colnmns" data-element="week5" id="week5_show" <?php if($user_login_details->week5_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week5_show">&nbsp;</label></th>
                <th class="show_columns_td week6"><input type="checkbox" name="week6" class="week6_show show_colnmns" data-element="week6" id="week6_show" <?php if($user_login_details->week6_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week6_show">&nbsp;</label></th>
                <th class="show_columns_td week7"><input type="checkbox" name="week7" class="week7_show show_colnmns" data-element="week7" id="week7_show" <?php if($user_login_details->week7_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week7_show">&nbsp;</label></th>
                <th class="show_columns_td week8"><input type="checkbox" name="week8" class="week8_show show_colnmns" data-element="week8" id="week8_show" <?php if($user_login_details->week8_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week8_show">&nbsp;</label></th>
                <th class="show_columns_td week9"><input type="checkbox" name="week9" class="week9_show show_colnmns" data-element="week9" id="week9_show" <?php if($user_login_details->week9_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week9_show">&nbsp;</label></th>
                <th class="show_columns_td week10"><input type="checkbox" name="week10" class="week10_show show_colnmns" data-element="week10" id="week10_show" <?php if($user_login_details->week10_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week10_show">&nbsp;</label></th>
                <th class="show_columns_td week11"><input type="checkbox" name="week11" class="week11_show show_colnmns" data-element="week11" id="week11_show" <?php if($user_login_details->week11_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week11_show">&nbsp;</label></th>
                <th class="show_columns_td week12"><input type="checkbox" name="week12" class="week12_show show_colnmns" data-element="week12" id="week12_show" <?php if($user_login_details->week12_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week12_show">&nbsp;</label></th>
                <th class="show_columns_td week13"><input type="checkbox" name="week13" class="week13_show show_colnmns" data-element="week13" id="week13_show" <?php if($user_login_details->week13_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week13_show">&nbsp;</label></th>
                <th class="show_columns_td week14"><input type="checkbox" name="week14" class="week14_show show_colnmns" data-element="week14" id="week14_show" <?php if($user_login_details->week14_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week14_show">&nbsp;</label></th>
                <th class="show_columns_td week15"><input type="checkbox" name="week15" class="week15_show show_colnmns" data-element="week15" id="week15_show" <?php if($user_login_details->week15_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week15_show">&nbsp;</label></th>
                <th class="show_columns_td week16"><input type="checkbox" name="week16" class="week16_show show_colnmns" data-element="week16" id="week16_show" <?php if($user_login_details->week16_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week16_show">&nbsp;</label></th>
                <th class="show_columns_td week17"><input type="checkbox" name="week17" class="week17_show show_colnmns" data-element="week17" id="week17_show" <?php if($user_login_details->week17_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week17_show">&nbsp;</label></th>
                <th class="show_columns_td week18"><input type="checkbox" name="week18" class="week18_show show_colnmns" data-element="week18" id="week18_show" <?php if($user_login_details->week18_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week18_show">&nbsp;</label></th>
                <th class="show_columns_td week19"><input type="checkbox" name="week19" class="week19_show show_colnmns" data-element="week19" id="week19_show" <?php if($user_login_details->week19_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week19_show">&nbsp;</label></th>
                <th class="show_columns_td week20"><input type="checkbox" name="week20" class="week20_show show_colnmns" data-element="week20" id="week20_show" <?php if($user_login_details->week20_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week20_show">&nbsp;</label></th>
                <th class="show_columns_td week21"><input type="checkbox" name="week21" class="week21_show show_colnmns" data-element="week21" id="week21_show" <?php if($user_login_details->week21_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week21_show">&nbsp;</label></th>
                <th class="show_columns_td week22"><input type="checkbox" name="week22" class="week22_show show_colnmns" data-element="week22" id="week22_show" <?php if($user_login_details->week22_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week22_show">&nbsp;</label></th>
                <th class="show_columns_td week23"><input type="checkbox" name="week23" class="week23_show show_colnmns" data-element="week23" id="week23_show" <?php if($user_login_details->week23_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week23_show">&nbsp;</label></th>
                <th class="show_columns_td week24"><input type="checkbox" name="week24" class="week24_show show_colnmns" data-element="week24" id="week24_show" <?php if($user_login_details->week24_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week24_show">&nbsp;</label></th>
                <th class="show_columns_td week25"><input type="checkbox" name="week25" class="week25_show show_colnmns" data-element="week25" id="week25_show" <?php if($user_login_details->week25_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week25_show">&nbsp;</label></th>
                <th class="show_columns_td week26"><input type="checkbox" name="week26" class="week26_show show_colnmns" data-element="week26" id="week26_show" <?php if($user_login_details->week26_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week26_show">&nbsp;</label></th>
                <th class="show_columns_td week27"><input type="checkbox" name="week27" class="week27_show show_colnmns" data-element="week27" id="week27_show" <?php if($user_login_details->week27_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week27_show">&nbsp;</label></th>
                <th class="show_columns_td week28"><input type="checkbox" name="week28" class="week28_show show_colnmns" data-element="week28" id="week28_show" <?php if($user_login_details->week28_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week28_show">&nbsp;</label></th>
                <th class="show_columns_td week29"><input type="checkbox" name="week29" class="week29_show show_colnmns" data-element="week29" id="week29_show" <?php if($user_login_details->week29_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week29_show">&nbsp;</label></th>
                <th class="show_columns_td week30"><input type="checkbox" name="week30" class="week30_show show_colnmns" data-element="week30" id="week30_show" <?php if($user_login_details->week30_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week30_show">&nbsp;</label></th>
                <th class="show_columns_td week31"><input type="checkbox" name="week31" class="week31_show show_colnmns" data-element="week31" id="week31_show" <?php if($user_login_details->week31_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week31_show">&nbsp;</label></th>
                <th class="show_columns_td week32"><input type="checkbox" name="week32" class="week32_show show_colnmns" data-element="week32" id="week32_show" <?php if($user_login_details->week32_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week32_show">&nbsp;</label></th>
                <th class="show_columns_td week33"><input type="checkbox" name="week33" class="week33_show show_colnmns" data-element="week33" id="week33_show" <?php if($user_login_details->week33_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week33_show">&nbsp;</label></th>
                <th class="show_columns_td week34"><input type="checkbox" name="week34" class="week34_show show_colnmns" data-element="week34" id="week34_show" <?php if($user_login_details->week34_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week34_show">&nbsp;</label></th>
                <th class="show_columns_td week35"><input type="checkbox" name="week35" class="week35_show show_colnmns" data-element="week35" id="week35_show" <?php if($user_login_details->week35_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week35_show">&nbsp;</label></th>
                <th class="show_columns_td week36"><input type="checkbox" name="week36" class="week36_show show_colnmns" data-element="week36" id="week36_show" <?php if($user_login_details->week36_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week36_show">&nbsp;</label></th>
                <th class="show_columns_td week37"><input type="checkbox" name="week37" class="week37_show show_colnmns" data-element="week37" id="week37_show" <?php if($user_login_details->week37_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week37_show">&nbsp;</label></th>
                <th class="show_columns_td week38"><input type="checkbox" name="week38" class="week38_show show_colnmns" data-element="week38" id="week38_show" <?php if($user_login_details->week38_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week38_show">&nbsp;</label></th>
                <th class="show_columns_td week39"><input type="checkbox" name="week39" class="week39_show show_colnmns" data-element="week39" id="week39_show" <?php if($user_login_details->week39_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week39_show">&nbsp;</label></th>
                <th class="show_columns_td week40"><input type="checkbox" name="week40" class="week40_show show_colnmns" data-element="week40" id="week40_show" <?php if($user_login_details->week40_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week40_show">&nbsp;</label></th>
                <th class="show_columns_td week41"><input type="checkbox" name="week41" class="week41_show show_colnmns" data-element="week41" id="week41_show" <?php if($user_login_details->week41_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week41_show">&nbsp;</label></th>
                <th class="show_columns_td week42"><input type="checkbox" name="week42" class="week42_show show_colnmns" data-element="week42" id="week42_show" <?php if($user_login_details->week42_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week42_show">&nbsp;</label></th>
                <th class="show_columns_td week43"><input type="checkbox" name="week43" class="week43_show show_colnmns" data-element="week43" id="week43_show" <?php if($user_login_details->week43_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week43_show">&nbsp;</label></th>
                <th class="show_columns_td week44"><input type="checkbox" name="week44" class="week44_show show_colnmns" data-element="week44" id="week44_show" <?php if($user_login_details->week44_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week44_show">&nbsp;</label></th>
                <th class="show_columns_td week45"><input type="checkbox" name="week45" class="week45_show show_colnmns" data-element="week45" id="week45_show" <?php if($user_login_details->week45_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week45_show">&nbsp;</label></th>
                <th class="show_columns_td week46"><input type="checkbox" name="week46" class="week46_show show_colnmns" data-element="week46" id="week46_show" <?php if($user_login_details->week46_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week46_show">&nbsp;</label></th>
                <th class="show_columns_td week47"><input type="checkbox" name="week47" class="week47_show show_colnmns" data-element="week47" id="week47_show" <?php if($user_login_details->week47_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week47_show">&nbsp;</label></th>
                <th class="show_columns_td week48"><input type="checkbox" name="week48" class="week48_show show_colnmns" data-element="week48" id="week48_show" <?php if($user_login_details->week48_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week48_show">&nbsp;</label></th>
                <th class="show_columns_td week49"><input type="checkbox" name="week49" class="week49_show show_colnmns" data-element="week49" id="week49_show" <?php if($user_login_details->week49_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week49_show">&nbsp;</label></th>
                <th class="show_columns_td week50"><input type="checkbox" name="week50" class="week50_show show_colnmns" data-element="week50" id="week50_show" <?php if($user_login_details->week50_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week50_show">&nbsp;</label></th>
                <th class="show_columns_td week51"><input type="checkbox" name="week51" class="week51_show show_colnmns" data-element="week51" id="week51_show" <?php if($user_login_details->week51_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week51_show">&nbsp;</label></th>
                <th class="show_columns_td week52"><input type="checkbox" name="week52" class="week52_show show_colnmns" data-element="week52" id="week52_show" <?php if($user_login_details->week52_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week52_show">&nbsp;</label></th>
                <th class="show_columns_td week53"><input type="checkbox" name="week53" class="week53_show show_colnmns" data-element="week53" id="week53_show" <?php if($user_login_details->week53_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="week53_show">&nbsp;</label></th>


                <th class="show_columns_td month1"><input type="checkbox" name="month1" class="month1_show show_colnmns" data-element="month1" id="month1_show" <?php if($user_login_details->month1_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="month1_show">&nbsp;</label></th>
                <th class="show_columns_td month2"><input type="checkbox" name="month2" class="month2_show show_colnmns" data-element="month2" id="month2_show" <?php if($user_login_details->month2_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="month2_show">&nbsp;</label></th>
                <th class="show_columns_td month3"><input type="checkbox" name="month3" class="month3_show show_colnmns" data-element="month3" id="month3_show" <?php if($user_login_details->month3_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="month3_show">&nbsp;</label></th>
                <th class="show_columns_td month4"><input type="checkbox" name="month4" class="month4_show show_colnmns" data-element="month4" id="month4_show" <?php if($user_login_details->month4_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="month4_show">&nbsp;</label></th>
                <th class="show_columns_td month5"><input type="checkbox" name="month5" class="month5_show show_colnmns" data-element="month5" id="month5_show" <?php if($user_login_details->month5_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="month5_show">&nbsp;</label></th>
                <th class="show_columns_td month6"><input type="checkbox" name="month6" class="month6_show show_colnmns" data-element="month6" id="month6_show" <?php if($user_login_details->month6_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="month6_show">&nbsp;</label></th>
                <th class="show_columns_td month7"><input type="checkbox" name="month7" class="month7_show show_colnmns" data-element="month7" id="month7_show" <?php if($user_login_details->month7_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="month7_show">&nbsp;</label></th>
                <th class="show_columns_td month8"><input type="checkbox" name="month8" class="month8_show show_colnmns" data-element="month8" id="month8_show" <?php if($user_login_details->month8_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="month8_show">&nbsp;</label></th>
                <th class="show_columns_td month9"><input type="checkbox" name="month9" class="month9_show show_colnmns" data-element="month9" id="month9_show" <?php if($user_login_details->month9_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="month9_show">&nbsp;</label></th>
                <th class="show_columns_td month10"><input type="checkbox" name="month10" class="month10_show show_colnmns" data-element="month10" id="month10_show" <?php if($user_login_details->month10_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="month10_show">&nbsp;</label></th>
                <th class="show_columns_td month11"><input type="checkbox" name="month11" class="month11_show show_colnmns" data-element="month11" id="month11_show" <?php if($user_login_details->month11_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="month11_show">&nbsp;</label></th>
                <th class="show_columns_td month12"><input type="checkbox" name="month12" class="month12_show show_colnmns" data-element="month12" id="month12_show" <?php if($user_login_details->month12_hide == 1){ echo 'checked'; } else { echo ''; } ?>><label for="month12_show">&nbsp;</label></th>  
                <th style="border-right:1px solid #000"></th>             
              </tr>
          </thead>
          <tbody id="task_body_std">
              <?php
              if(count($resultlist))
              {
                $sno = 1;
                foreach($resultlist as $list)
                {
                  ?>
                  <tr>
                      <td><?php echo $sno; ?></td>
                      <td>
                        <input type="checkbox" name="show_td" class="show_td" id="show_td_<?php echo $list->id; ?>" data-element="<?php echo $list->id; ?>" <?php if($list->task_status == 1) { echo 'checked'; } else { echo ''; } ?>><label for="show_td_<?php echo $list->id; ?>"> &nbsp; </label>
                      </td>
                      <td>
                          <strong style="color:#000;font-size:17px"><?php echo $list->task_name; ?></strong><br/>
                          Employer Number: <strong><?php echo $list->task_enumber; ?></strong><br/>
                          <?php
                          $level_name = DB::table('p30_tasklevel')->where('id',$list->task_level)->first(); 

                          if($monthid->month == 1) { $month_name = "January"; }
                          if($monthid->month == 2) { $month_name = "February"; }
                          if($monthid->month == 3) { $month_name = "March"; }
                          if($monthid->month == 4) { $month_name = "April"; }
                          if($monthid->month == 5) { $month_name = "May"; }
                          if($monthid->month == 6) { $month_name = "June"; }
                          if($monthid->month == 7) { $month_name = "July"; }
                          if($monthid->month == 8) { $month_name = "August"; }
                          if($monthid->month == 9) { $month_name = "September"; }
                          if($monthid->month == 10) { $month_name = "October"; }
                          if($monthid->month == 11) { $month_name = "November"; }
                          if($monthid->month == 12) { $month_name = "December"; }
                          ?>
                          Action: <?php if($list->task_level != 0){ echo $level_name->name; } ?><br/>
                          Pay: <strong><?php echo ($list->pay == 1)?'Yes':'No'; ?></strong><br/>
                          Email: <strong><?php echo ($list->email == 1)?'Yes':'No'; ?></strong><br/>
                          Period: <strong><?php echo $month_name; ?></strong>
                      </td>
                      <td><input type="text" name="ros_liability" class="ros_liability form-control input-sm" id="ros_liability_<?php echo $list->id; ?>" data-element="<?php echo $list->id; ?>" value="<?php echo (number_format_invoice($list->ros_liability) == 0.00)?"":number_format_invoice($list->ros_liability); ?>"> </td>
                      <td>
                        <input type="text" name="task_liability" class="task_liability form-control input-sm" id="task_liability_<?php echo $list->id; ?>" data-element="<?php echo $list->id; ?>" value="<?php echo (number_format_invoice($list->task_liability) == 0.00)?"":number_format_invoice($list->task_liability); ?>" disabled style="width:70%;float:left"> 
                        <a href="javascript:" class="fa fa-refresh refresh_liability" data-element="<?php echo $list->id; ?>" style="margin-left:10px;font-size:20px;margin-top:5px"></a>
                      </td>
                      <td>
                        <?php if($list->ros_liability == "") { $diff = ''; } else { $diff = $list->ros_liability - $list->task_liability; } ?>
                        <input type="text" name="diff_liability" class="diff_liability form-control input-sm" id="diff_liability_<?php echo $list->id; ?>" data-element="<?php echo $list->id; ?>" value="<?php echo (number_format_invoice($diff) == 0.00)?"":number_format_invoice($diff); ?>" disabled>
                      </td>

                      <td class="show_columns_td week1" style="font-weight:800"><?php echo ($list->week1 == "0")?'-':number_format_invoice($list->week1); ?></td>
                      <td class="show_columns_td week2" style="font-weight:800"><?php echo ($list->week2 == "0")?'-':number_format_invoice($list->week2); ?></td>
                      <td class="show_columns_td week3" style="font-weight:800"><?php echo ($list->week3 == "0")?'-':number_format_invoice($list->week3); ?></td>
                      <td class="show_columns_td week4" style="font-weight:800"><?php echo ($list->week4 == "0")?'-':number_format_invoice($list->week4); ?></td>
                      <td class="show_columns_td week5" style="font-weight:800"><?php echo ($list->week5 == "0")?'-':number_format_invoice($list->week5); ?></td>
                      <td class="show_columns_td week6" style="font-weight:800"><?php echo ($list->week6 == "0")?'-':number_format_invoice($list->week6); ?></td>
                      <td class="show_columns_td week7" style="font-weight:800"><?php echo ($list->week7 == "0")?'-':number_format_invoice($list->week7); ?></td>
                      <td class="show_columns_td week8" style="font-weight:800"><?php echo ($list->week8 == "0")?'-':number_format_invoice($list->week8); ?></td>
                      <td class="show_columns_td week9" style="font-weight:800"><?php echo ($list->week9 == "0")?'-':number_format_invoice($list->week9); ?></td>
                      <td class="show_columns_td week10" style="font-weight:800"><?php echo ($list->week10 == "0")?'-':number_format_invoice($list->week10); ?></td>
                      <td class="show_columns_td week11" style="font-weight:800"><?php echo ($list->week11 == "0")?'-':number_format_invoice($list->week11); ?></td>
                      <td class="show_columns_td week12" style="font-weight:800"><?php echo ($list->week12 == "0")?'-':number_format_invoice($list->week12); ?></td>
                      <td class="show_columns_td week13" style="font-weight:800"><?php echo ($list->week13 == "0")?'-':number_format_invoice($list->week13); ?></td>
                      <td class="show_columns_td week14" style="font-weight:800"><?php echo ($list->week14 == "0")?'-':number_format_invoice($list->week14); ?></td>
                      <td class="show_columns_td week15" style="font-weight:800"><?php echo ($list->week15 == "0")?'-':number_format_invoice($list->week15); ?></td>
                      <td class="show_columns_td week16" style="font-weight:800"><?php echo ($list->week16 == "0")?'-':number_format_invoice($list->week16); ?></td>
                      <td class="show_columns_td week17" style="font-weight:800"><?php echo ($list->week17 == "0")?'-':number_format_invoice($list->week17); ?></td>
                      <td class="show_columns_td week18" style="font-weight:800"><?php echo ($list->week18 == "0")?'-':number_format_invoice($list->week18); ?></td>
                      <td class="show_columns_td week19" style="font-weight:800"><?php echo ($list->week19 == "0")?'-':number_format_invoice($list->week19); ?></td>
                      <td class="show_columns_td week20" style="font-weight:800"><?php echo ($list->week20 == "0")?'-':number_format_invoice($list->week20); ?></td>
                      <td class="show_columns_td week21" style="font-weight:800"><?php echo ($list->week21 == "0")?'-':number_format_invoice($list->week21); ?></td>
                      <td class="show_columns_td week22" style="font-weight:800"><?php echo ($list->week22 == "0")?'-':number_format_invoice($list->week22); ?></td>
                      <td class="show_columns_td week23" style="font-weight:800"><?php echo ($list->week23 == "0")?'-':number_format_invoice($list->week23); ?></td>
                      <td class="show_columns_td week24" style="font-weight:800"><?php echo ($list->week24 == "0")?'-':number_format_invoice($list->week24); ?></td>
                      <td class="show_columns_td week25" style="font-weight:800"><?php echo ($list->week25 == "0")?'-':number_format_invoice($list->week25); ?></td>
                      <td class="show_columns_td week26" style="font-weight:800"><?php echo ($list->week26 == "0")?'-':number_format_invoice($list->week26); ?></td>
                      <td class="show_columns_td week27" style="font-weight:800"><?php echo ($list->week27 == "0")?'-':number_format_invoice($list->week27); ?></td>
                      <td class="show_columns_td week28" style="font-weight:800"><?php echo ($list->week28 == "0")?'-':number_format_invoice($list->week28); ?></td>
                      <td class="show_columns_td week29" style="font-weight:800"><?php echo ($list->week29 == "0")?'-':number_format_invoice($list->week29); ?></td>
                      <td class="show_columns_td week30" style="font-weight:800"><?php echo ($list->week30 == "0")?'-':number_format_invoice($list->week30); ?></td>
                      <td class="show_columns_td week31" style="font-weight:800"><?php echo ($list->week31 == "0")?'-':number_format_invoice($list->week31); ?></td>
                      <td class="show_columns_td week32" style="font-weight:800"><?php echo ($list->week32 == "0")?'-':number_format_invoice($list->week32); ?></td>
                      <td class="show_columns_td week33" style="font-weight:800"><?php echo ($list->week33 == "0")?'-':number_format_invoice($list->week33); ?></td>
                      <td class="show_columns_td week34" style="font-weight:800"><?php echo ($list->week34 == "0")?'-':number_format_invoice($list->week34); ?></td>
                      <td class="show_columns_td week35" style="font-weight:800"><?php echo ($list->week35 == "0")?'-':number_format_invoice($list->week35); ?></td>
                      <td class="show_columns_td week36" style="font-weight:800"><?php echo ($list->week36 == "0")?'-':number_format_invoice($list->week36); ?></td>
                      <td class="show_columns_td week37" style="font-weight:800"><?php echo ($list->week37 == "0")?'-':number_format_invoice($list->week37); ?></td>
                      <td class="show_columns_td week38" style="font-weight:800"><?php echo ($list->week38 == "0")?'-':number_format_invoice($list->week38); ?></td>
                      <td class="show_columns_td week39" style="font-weight:800"><?php echo ($list->week39 == "0")?'-':number_format_invoice($list->week39); ?></td>
                      <td class="show_columns_td week40" style="font-weight:800"><?php echo ($list->week40 == "0")?'-':number_format_invoice($list->week40); ?></td>
                      <td class="show_columns_td week41" style="font-weight:800"><?php echo ($list->week41 == "0")?'-':number_format_invoice($list->week41); ?></td>
                      <td class="show_columns_td week42" style="font-weight:800"><?php echo ($list->week42 == "0")?'-':number_format_invoice($list->week42); ?></td>
                      <td class="show_columns_td week43" style="font-weight:800"><?php echo ($list->week43 == "0")?'-':number_format_invoice($list->week43); ?></td>
                      <td class="show_columns_td week44" style="font-weight:800"><?php echo ($list->week44 == "0")?'-':number_format_invoice($list->week44); ?></td>
                      <td class="show_columns_td week45" style="font-weight:800"><?php echo ($list->week45 == "0")?'-':number_format_invoice($list->week45); ?></td>
                      <td class="show_columns_td week46" style="font-weight:800"><?php echo ($list->week46 == "0")?'-':number_format_invoice($list->week46); ?></td>
                      <td class="show_columns_td week47" style="font-weight:800"><?php echo ($list->week47 == "0")?'-':number_format_invoice($list->week47); ?></td>
                      <td class="show_columns_td week48" style="font-weight:800"><?php echo ($list->week48 == "0")?'-':number_format_invoice($list->week48); ?></td>
                      <td class="show_columns_td week49" style="font-weight:800"><?php echo ($list->week49 == "0")?'-':number_format_invoice($list->week49); ?></td>
                      <td class="show_columns_td week50" style="font-weight:800"><?php echo ($list->week50 == "0")?'-':number_format_invoice($list->week50); ?></td>
                      <td class="show_columns_td week51" style="font-weight:800"><?php echo ($list->week51 == "0")?'-':number_format_invoice($list->week51); ?></td>
                      <td class="show_columns_td week52" style="font-weight:800"><?php echo ($list->week52 == "0")?'-':number_format_invoice($list->week52); ?></td>
                      <td class="show_columns_td week53" style="font-weight:800"><?php echo ($list->week53 == "0")?'-':number_format_invoice($list->week53); ?></td>

                      <td class="show_columns_td month1" style="font-weight:800"><?php echo ($list->month1 == "0")?'-':number_format_invoice($list->month1); ?></td>
                      <td class="show_columns_td month2" style="font-weight:800"><?php echo ($list->month2 == "0")?'-':number_format_invoice($list->month2); ?></td>
                      <td class="show_columns_td month3" style="font-weight:800"><?php echo ($list->month3 == "0")?'-':number_format_invoice($list->month3); ?></td>
                      <td class="show_columns_td month4" style="font-weight:800"><?php echo ($list->month4 == "0")?'-':number_format_invoice($list->month4); ?></td>
                      <td class="show_columns_td month5" style="font-weight:800"><?php echo ($list->month5 == "0")?'-':number_format_invoice($list->month5); ?></td>
                      <td class="show_columns_td month6" style="font-weight:800"><?php echo ($list->month6 == "0")?'-':number_format_invoice($list->month6); ?></td>
                      <td class="show_columns_td month7" style="font-weight:800"><?php echo ($list->month7 == "0")?'-':number_format_invoice($list->month7); ?></td>
                      <td class="show_columns_td month8" style="font-weight:800"><?php echo ($list->month8 == "0")?'-':number_format_invoice($list->month8); ?></td>
                      <td class="show_columns_td month9" style="font-weight:800"><?php echo ($list->month9 == "0")?'-':number_format_invoice($list->month9); ?></td>
                      <td class="show_columns_td month10" style="font-weight:800"><?php echo ($list->month10 == "0")?'-':number_format_invoice($list->month10); ?></td>
                      <td class="show_columns_td month11" style="font-weight:800"><?php echo ($list->month11 == "0")?'-':number_format_invoice($list->month11); ?></td>
                      <td class="show_columns_td month12" style="font-weight:800"><?php echo ($list->month12 == "0")?'-':number_format_invoice($list->month12); ?></td>

                      <td><a href="javascript:" class="fa fa-envelope email_unsent" data-element="<?php echo $list->id; ?>"></a></td>
                  </tr>
                  <?php
                  $sno++;
                }
              }
              ?>
          </tbody>
      </table>
      <p></p>
    </div>
</div>
<input type="hidden" name="sno_sortoptions_std" id="sno_sortoptions_std" value="asc">
<input type="hidden" name="task_sortoptions_std" id="task_sortoptions_std" value="asc">
<input type="hidden" name="eno_sortoptions_std" id="eno_sortoptions_std" value="asc">
<input type="hidden" name="level_sortoptions_std" id="level_sortoptions_std" value="asc">
<input type="hidden" name="period_sortoptions_std" id="period_sortoptions_std" value="asc">
<input type="hidden" name="liability_sortoptions_std" id="liability_sortoptions_std" value="asc">
<div class="modal_load"></div>


<?php

if(!empty($_GET['divid']))

{

  $divid = $_GET['divid'];

  ?>

  <script>

  $(function() {

    $(document).scrollTop( $("#<?php echo $divid; ?>").offset().top - parseInt(150) );  

  });

  </script>

  <?php

}

?>

<script>

$(document).ready(function() {
  $("body").addClass("loading");
  var show_td = $(".show_td").length;
  var show_td_checked = $(".show_td:checked").length;
  if(show_td == show_td_checked)
  {
    $("#selectall_task").prop("checked",true);
  }
  else{
    $("#selectall_task").prop("checked",false); 
  }

  var show_cols = $(".show_colnmns").length;
  var show_cols_checked = $(".show_colnmns:checked").length;
  if(show_cols == show_cols_checked)
  {
    $("#selectall_columns").prop("checked",true);
  }
  else{
    $("#selectall_columns").prop("checked",false); 
  }

  if(show_cols_checked == 1 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"5940px"});
  }
  else if(show_cols_checked == 2 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"5880px"});
  }
  else if(show_cols_checked == 3 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"5820px"});
  }
  else if(show_cols_checked == 4 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"5760px"});
  }
  else if(show_cols_checked == 5 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"5700px"});
  }
  else if(show_cols_checked == 6 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"5640px"});
  }
  else if(show_cols_checked == 7 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"5580px"});
  }
  else if(show_cols_checked == 8 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"5520px"});
  }
  else if(show_cols_checked == 9 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"5460px"});
  }
  else if(show_cols_checked == 10 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"5400px"});
  }
  else if(show_cols_checked == 11 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"5340px"});
  }
  else if(show_cols_checked == 12 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"5280px"});
  }
  else if(show_cols_checked == 13 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"5220px"});
  }
  else if(show_cols_checked == 14 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"5160px"});
  }
  else if(show_cols_checked == 15 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"5100px"});
  }
  else if(show_cols_checked == 16 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"5040px"});
  }
  else if(show_cols_checked == 17 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"4980px"});
  }
  else if(show_cols_checked == 18 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"4920px"});
  }
  else if(show_cols_checked == 19 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"4860px"});
  }
  else if(show_cols_checked == 20 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"4800px"});
  }
  else if(show_cols_checked == 21 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"4740px"});
  }
  else if(show_cols_checked == 22 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"4680px"});
  }
  else if(show_cols_checked == 23 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"4620px"});
  }
  else if(show_cols_checked == 24 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"4560px"});
  }
  else if(show_cols_checked == 25 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"4500px"});
  }
  else if(show_cols_checked == 26 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"4440px"});
  }
  else if(show_cols_checked == 27 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"4380px"});
  }
  else if(show_cols_checked == 28 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"4320px"});
  }
  else if(show_cols_checked == 29 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"4260px"});
  }
  else if(show_cols_checked == 30 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"4200px"});
  }
  else if(show_cols_checked == 31 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"4140px"});
  }
  else if(show_cols_checked == 32 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"4080px"});
  }
  else if(show_cols_checked == 33 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"4020px"});
  }
  else if(show_cols_checked == 34 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"3960px"});
  }
  else if(show_cols_checked == 35 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"3900px"});
  }
  else if(show_cols_checked == 36 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"3840px"});
  }
  else if(show_cols_checked == 37 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"3780px"});
  }
  else if(show_cols_checked == 38 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"3720px"});
  }
  else if(show_cols_checked == 39 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"3660px"});
  }
  else if(show_cols_checked == 40 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"3600px"});
  }
  else if(show_cols_checked == 41 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"3540px"});
  }
  else if(show_cols_checked == 42 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"3480px"});
  }
  else if(show_cols_checked == 43 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"3420px"});
  }
  else if(show_cols_checked == 44 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"3360px"});
  }
  else if(show_cols_checked == 45 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"3300px"});
  }
  else if(show_cols_checked == 46 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"3240px"});
  }
  else if(show_cols_checked == 47 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"3180px"});
  }
  else if(show_cols_checked == 48 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"3120px"});
  }
  else if(show_cols_checked == 49 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"3060px"});
  }
  else if(show_cols_checked == 50 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"3000px"});
  }
  else if(show_cols_checked == 51 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"2940px"});
  }
  else if(show_cols_checked == 52 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"2880px"});
  }
  else if(show_cols_checked == 53 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"2820px"});
  }





  else if(show_cols_checked == 54 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"2760px"});
  }
  else if(show_cols_checked == 55 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"2700px"});
  }
  else if(show_cols_checked == 56 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"2640px"});
  }
  else if(show_cols_checked == 57 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"2520px"});
  }
  else if(show_cols_checked == 58 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"2460px"});
  }
  else if(show_cols_checked == 59 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"2400px"});
  }
  else if(show_cols_checked == 60 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"2440px"});
  }
  else if(show_cols_checked == 61 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"2380px"});
  }
  else if(show_cols_checked == 62 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"2320px"});
  }
  else if(show_cols_checked == 63 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"2260px"});
  }
  else if(show_cols_checked == 64 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"2200px"});
  }
  else if(show_cols_checked == 65 && $("#show_hide_columns").hasClass('show_column'))
  {
    $(".table_bg").parent().css({"width":"1200px"});
  }
  

  

  if($("#show_hide_task").hasClass('show_task'))
  {
    $(".show_td").parents("tr").show();
    $(".show_td:checked").parents("tr").hide();
  }
  else{
    $(".show_td").parents("tr").show();
  }

  if($("#show_hide_columns").hasClass('show_column'))
  {
    $(".show_columns_td").show();
    $(".show_colnmns:checked").each(function() {
      var element = $(this).attr("data-element");
      $("."+element).hide();
    });
  }
  else{
    $(".show_columns_td").show();
  }

  $("body").removeClass("loading");
});


$(window).click(function(e) {
  if($(e.target).hasClass("ros_liability"))
  {
    var rosvalue = $(e.target).val();
    rosvalue = rosvalue.replace(",", "");
    rosvalue = rosvalue.replace(",", "");
    rosvalue = rosvalue.replace(",", "");
    rosvalue = rosvalue.replace(",", "");
    rosvalue = rosvalue.replace(",", "");
    $(e.target).val(rosvalue);
    $(e.target).focus();
  }
  if($(e.target).hasClass('refresh_liability'))
  {
    $("body").addClass("loading");
    var task_id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/gbs_refresh_paye_p30_liability'); ?>",
      type:"get",
      dataType:"json",
      data:{task_id:task_id},
      success: function(result){
        $("#task_liability_"+task_id).val(result['task_liability']);
        $(e.target).parents("tr").find(".week1").val(result['week1']);
        $(e.target).parents("tr").find(".week2").val(result['week2']);
        $(e.target).parents("tr").find(".week3").val(result['week3']);
        $(e.target).parents("tr").find(".week4").val(result['week4']);
        $(e.target).parents("tr").find(".week5").val(result['week5']);
        $(e.target).parents("tr").find(".week6").val(result['week6']);
        $(e.target).parents("tr").find(".week7").val(result['week7']);
        $(e.target).parents("tr").find(".week8").val(result['week8']);
        $(e.target).parents("tr").find(".week9").val(result['week9']);
        $(e.target).parents("tr").find(".week10").val(result['week10']);
        $(e.target).parents("tr").find(".week11").val(result['week11']);
        $(e.target).parents("tr").find(".week12").val(result['week12']);
        $(e.target).parents("tr").find(".week13").val(result['week13']);
        $(e.target).parents("tr").find(".week14").val(result['week14']);
        $(e.target).parents("tr").find(".week15").val(result['week15']);
        $(e.target).parents("tr").find(".week16").val(result['week16']);
        $(e.target).parents("tr").find(".week17").val(result['week17']);
        $(e.target).parents("tr").find(".week18").val(result['week18']);
        $(e.target).parents("tr").find(".week19").val(result['week19']);
        $(e.target).parents("tr").find(".week20").val(result['week20']);
        $(e.target).parents("tr").find(".week21").val(result['week21']);
        $(e.target).parents("tr").find(".week22").val(result['week22']);
        $(e.target).parents("tr").find(".week23").val(result['week23']);
        $(e.target).parents("tr").find(".week24").val(result['week24']);
        $(e.target).parents("tr").find(".week25").val(result['week25']);
        $(e.target).parents("tr").find(".week26").val(result['week26']);
        $(e.target).parents("tr").find(".week27").val(result['week27']);
        $(e.target).parents("tr").find(".week28").val(result['week28']);
        $(e.target).parents("tr").find(".week29").val(result['week29']);
        $(e.target).parents("tr").find(".week30").val(result['week30']);
        $(e.target).parents("tr").find(".week31").val(result['week31']);
        $(e.target).parents("tr").find(".week32").val(result['week32']);
        $(e.target).parents("tr").find(".week33").val(result['week33']);
        $(e.target).parents("tr").find(".week34").val(result['week34']);
        $(e.target).parents("tr").find(".week35").val(result['week35']);
        $(e.target).parents("tr").find(".week36").val(result['week36']);
        $(e.target).parents("tr").find(".week37").val(result['week37']);
        $(e.target).parents("tr").find(".week38").val(result['week38']);
        $(e.target).parents("tr").find(".week39").val(result['week39']);
        $(e.target).parents("tr").find(".week40").val(result['week40']);
        $(e.target).parents("tr").find(".week41").val(result['week41']);
        $(e.target).parents("tr").find(".week42").val(result['week42']);
        $(e.target).parents("tr").find(".week43").val(result['week43']);
        $(e.target).parents("tr").find(".week44").val(result['week44']);
        $(e.target).parents("tr").find(".week45").val(result['week45']);
        $(e.target).parents("tr").find(".week46").val(result['week46']);
        $(e.target).parents("tr").find(".week47").val(result['week47']);
        $(e.target).parents("tr").find(".week48").val(result['week48']);
        $(e.target).parents("tr").find(".week49").val(result['week49']);
        $(e.target).parents("tr").find(".week50").val(result['week50']);
        $(e.target).parents("tr").find(".week51").val(result['week51']);
        $(e.target).parents("tr").find(".week52").val(result['week52']);
        $(e.target).parents("tr").find(".week53").val(result['week53']);

        $(e.target).parents("tr").find(".month1").val(result['month1']);
        $(e.target).parents("tr").find(".month2").val(result['month2']);
        $(e.target).parents("tr").find(".month3").val(result['month3']);
        $(e.target).parents("tr").find(".month4").val(result['month4']);
        $(e.target).parents("tr").find(".month5").val(result['month5']);
        $(e.target).parents("tr").find(".month6").val(result['month6']);
        $(e.target).parents("tr").find(".month7").val(result['month7']);
        $(e.target).parents("tr").find(".month8").val(result['month8']);
        $(e.target).parents("tr").find(".month9").val(result['month9']);
        $(e.target).parents("tr").find(".month10").val(result['month10']);
        $(e.target).parents("tr").find(".month11").val(result['month11']);
        $(e.target).parents("tr").find(".month12").val(result['month12']);

        var ros_liability = $("#ros_liability_"+task_id).val();

        if(ros_liability == "")
        {
          $("#diff_liability_"+task_id).val("");
        }
        else{
          var diff = parseInt(ros_liability) - parseInt(result['task_liability']);
          $("#diff_liability_"+task_id).val(diff);
        }
        

        $("body").removeClass("loading");

      }
    });
  }
  if($(e.target).hasClass("selectall_task"))
  {
    $("body").addClass("loading");
    if($(e.target).is(":checked"))
    {
      $(".selectall_task").prop("checked",true);
      $(".show_td").prop("checked",true);
      var task_id = '';
      $(".show_td").each(function() {
        if(task_id == "")
        {
          task_id = $(this).attr("data-element");
        }
        else{
          task_id = task_id+','+$(this).attr("data-element");
        }
      });
      $.ajax({
        url:"<?php echo URL::to('user/gbs_update_paye_p30_task_status'); ?>",
        type:"post",
        data:{task_id:task_id,status:1},
        success: function(result)
        {
          if($("#show_hide_task").hasClass('show_task'))
          {
            $(".show_td").parents("tr").show();
            $(".show_td:checked").parents("tr").hide();
          }
          else{
            $(".show_td").parents("tr").show();
          }
          $("body").removeClass("loading");
        }
      })
    }
    else{
      $(".selectall_task").prop("checked",false);
      $(".show_td").prop("checked",false);
      var task_id = '';
      $(".show_td").each(function() {
        if(task_id == "")
        {
          task_id = $(this).attr("data-element");
        }
        else{
          task_id = task_id+','+$(this).attr("data-element");
        }
      });
      $.ajax({
        url:"<?php echo URL::to('user/gbs_update_paye_p30_task_status'); ?>",
        type:"post",
        data:{task_id:task_id,status:0},
        success: function(result)
        {
          if($("#show_hide_task").hasClass('show_task'))
          {
            $(".show_td").parents("tr").show();
            $(".show_td:checked").parents("tr").hide();
          }
          else{
            $(".show_td").parents("tr").show();
          }
          $("body").removeClass("loading");
        }
      });
    }
  }
  if(e.target.id == "show_hide_task")
  {
    $("body").addClass("loading");
    if($(e.target).hasClass("hide_task"))
    {
      $.ajax({
        url:"<?php echo URL::to('user/gbs_update_paye_p30_hide_task_status'); ?>",
        type:"post",
        data:{status:1},
        success: function(result)
        {
          $(".show_td").parents("tr").show();
          $(".show_td:checked").parents("tr").hide();
          $(e.target).addClass("show_task");
          $(e.target).removeClass("hide_task");
          $(e.target).val("Show Task");
          $("body").removeClass("loading");
        }
      })
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/gbs_update_paye_p30_hide_task_status'); ?>",
        type:"post",
        data:{status:0},
        success: function(result)
        {
          $(".show_td").parents("tr").show();
          $(e.target).addClass("hide_class");
          $(e.target).addClass("hide_task");
          $(e.target).removeClass("show_task");
          $(e.target).val("Hide Task");
          $("body").removeClass("loading");
        }
      })
    }
    
  }
  if($(e.target).hasClass('show_td'))
  {
    $("body").addClass("loading");
    if($(e.target).is(":checked"))
    {
      var task_id = $(e.target).attr("data-element");
      var status = 1;
      $.ajax({
        url:"<?php echo URL::to('user/gbs_update_paye_p30_task_status'); ?>",
        type:"post",
        data:{task_id:task_id,status:status},
        success: function(result)
        {
          if($("#show_hide_task").hasClass('show_task'))
          {
            $(".show_td").parents("tr").show();
            $(".show_td:checked").parents("tr").hide();
          }
          else{
            $(".show_td").parents("tr").show();
          }
          $("body").removeClass("loading");
        }
      })
    }
    else{
      var task_id = $(e.target).attr("data-element");
      var status = 0;
      $.ajax({
        url:"<?php echo URL::to('user/gbs_update_paye_p30_task_status'); ?>",
        type:"post",
        data:{task_id:task_id,status:status},
        success: function(result)
        {
          if($("#show_hide_task").hasClass('show_task'))
          {
            $(".show_td").parents("tr").show();
            $(".show_td:checked").parents("tr").hide();
          }
          else{
            $(".show_td").parents("tr").show();
          }
          $("body").removeClass("loading");
        }
      })
    }
  }

  if(e.target.id == "selectall_columns")
  {
    $("body").addClass("loading");
    if($(e.target).is(":checked"))
    {
      $(".show_colnmns").prop("checked",true);
      var col_id = '';
      $(".show_colnmns").each(function() {
        if(col_id == "")
        {
          col_id = $(this).attr("data-element");
        }
        else{
          col_id = col_id+','+$(this).attr("data-element");
        }
      });
      $.ajax({
        url:"<?php echo URL::to('user/gbs_update_paye_p30_columns_status_selectall'); ?>",
        type:"post",
        data:{col_id:col_id,status:1},
        success: function(result)
        {
          if($("#show_hide_columns").hasClass('show_column'))
          {
            $(".show_columns_td").show();
            $(".show_colnmns:checked").each(function() {
              var element = $(this).attr("data-element");
              $("."+element).hide();
            });
          }
          else{
            $(".show_columns_td").show();
          }
          if($("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"1500px"});
          }
          
          
          $("body").removeClass("loading");
        }
      })
    }
    else{
      $(".show_colnmns").prop("checked",false);
      var col_id = '';
      $(".show_colnmns").each(function() {
        if(col_id == "")
        {
          col_id = $(this).attr("data-element");
        }
        else{
          col_id = col_id+','+$(this).attr("data-element");
        }
      });
      $.ajax({
        url:"<?php echo URL::to('user/gbs_update_paye_p30_columns_status_selectall'); ?>",
        type:"post",
        data:{col_id:col_id,status:0},
        success: function(result)
        {
          if($("#show_hide_columns").hasClass('show_column'))
          {
            $(".show_columns_td").show();
            $(".show_colnmns:checked").each(function() {
              var element = $(this).attr("data-element");
              $("."+element).hide();
            });
          }
          else{
            $(".show_columns_td").show();
          }

          if($("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"6000px"});
          }
          $("body").removeClass("loading");
        }
      });
    }
  }

  if(e.target.id == "show_hide_columns")
  {
    $("body").addClass("loading");
    if($(e.target).hasClass('hide_column'))
    {
      $.ajax({
        url:"<?php echo URL::to('user/gbs_update_paye_p30_hide_columns_status'); ?>",
        type:"post",
        data:{status:1},
        success: function(result)
        {
          $(".show_columns_td").show();
          $(".show_colnmns:checked").each(function() {
            var element = $(this).attr("data-element");
            $("."+element).hide();
          });
          $(e.target).addClass("show_column");
          $(e.target).removeClass("hide_column");
          $(e.target).val("Show Columns");
          var show_cols_checked = $(".show_colnmns:checked").length;

          if(show_cols_checked == 1)
          {
            $(".table_bg").parent().css({"width":"5940px"});
          }
          else if(show_cols_checked == 2)
          {
            $(".table_bg").parent().css({"width":"5880px"});
          }
          else if(show_cols_checked == 3)
          {
            $(".table_bg").parent().css({"width":"5820px"});
          }
          else if(show_cols_checked == 4)
          {
            $(".table_bg").parent().css({"width":"5760px"});
          }
          else if(show_cols_checked == 5)
          {
            $(".table_bg").parent().css({"width":"5700px"});
          }
          else if(show_cols_checked == 6)
          {
            $(".table_bg").parent().css({"width":"5640px"});
          }
          else if(show_cols_checked == 7)
          {
            $(".table_bg").parent().css({"width":"5580px"});
          }
          else if(show_cols_checked == 8)
          {
            $(".table_bg").parent().css({"width":"5520px"});
          }
          else if(show_cols_checked == 9)
          {
            $(".table_bg").parent().css({"width":"5460px"});
          }
          else if(show_cols_checked == 10)
          {
            $(".table_bg").parent().css({"width":"5400px"});
          }
          else if(show_cols_checked == 11)
          {
            $(".table_bg").parent().css({"width":"5340px"});
          }
          else if(show_cols_checked == 12)
          {
            $(".table_bg").parent().css({"width":"5280px"});
          }
          else if(show_cols_checked == 13)
          {
            $(".table_bg").parent().css({"width":"5220px"});
          }
          else if(show_cols_checked == 14)
          {
            $(".table_bg").parent().css({"width":"5160px"});
          }
          else if(show_cols_checked == 15)
          {
            $(".table_bg").parent().css({"width":"5100px"});
          }
          else if(show_cols_checked == 16)
          {
            $(".table_bg").parent().css({"width":"5040px"});
          }
          else if(show_cols_checked == 17)
          {
            $(".table_bg").parent().css({"width":"4980px"});
          }
          else if(show_cols_checked == 18)
          {
            $(".table_bg").parent().css({"width":"4920px"});
          }
          else if(show_cols_checked == 19)
          {
            $(".table_bg").parent().css({"width":"4860px"});
          }
          else if(show_cols_checked == 20)
          {
            $(".table_bg").parent().css({"width":"4800px"});
          }
          else if(show_cols_checked == 21)
          {
            $(".table_bg").parent().css({"width":"4740px"});
          }
          else if(show_cols_checked == 22)
          {
            $(".table_bg").parent().css({"width":"4680px"});
          }
          else if(show_cols_checked == 23)
          {
            $(".table_bg").parent().css({"width":"4620px"});
          }
          else if(show_cols_checked == 24)
          {
            $(".table_bg").parent().css({"width":"4560px"});
          }
          else if(show_cols_checked == 25)
          {
            $(".table_bg").parent().css({"width":"4500px"});
          }
          else if(show_cols_checked == 26)
          {
            $(".table_bg").parent().css({"width":"4440px"});
          }
          else if(show_cols_checked == 27)
          {
            $(".table_bg").parent().css({"width":"4380px"});
          }
          else if(show_cols_checked == 28)
          {
            $(".table_bg").parent().css({"width":"4320px"});
          }
          else if(show_cols_checked == 29)
          {
            $(".table_bg").parent().css({"width":"4260px"});
          }
          else if(show_cols_checked == 30)
          {
            $(".table_bg").parent().css({"width":"4200px"});
          }
          else if(show_cols_checked == 31)
          {
            $(".table_bg").parent().css({"width":"4140px"});
          }
          else if(show_cols_checked == 32)
          {
            $(".table_bg").parent().css({"width":"4080px"});
          }
          else if(show_cols_checked == 33)
          {
            $(".table_bg").parent().css({"width":"4020px"});
          }
          else if(show_cols_checked == 34)
          {
            $(".table_bg").parent().css({"width":"3960px"});
          }
          else if(show_cols_checked == 35)
          {
            $(".table_bg").parent().css({"width":"3900px"});
          }
          else if(show_cols_checked == 36)
          {
            $(".table_bg").parent().css({"width":"3840px"});
          }
          else if(show_cols_checked == 37)
          {
            $(".table_bg").parent().css({"width":"3780px"});
          }
          else if(show_cols_checked == 38)
          {
            $(".table_bg").parent().css({"width":"3720px"});
          }
          else if(show_cols_checked == 39)
          {
            $(".table_bg").parent().css({"width":"3660px"});
          }
          else if(show_cols_checked == 40)
          {
            $(".table_bg").parent().css({"width":"3600px"});
          }
          else if(show_cols_checked == 41)
          {
            $(".table_bg").parent().css({"width":"3540px"});
          }
          else if(show_cols_checked == 42)
          {
            $(".table_bg").parent().css({"width":"3480px"});
          }
          else if(show_cols_checked == 43)
          {
            $(".table_bg").parent().css({"width":"3420px"});
          }
          else if(show_cols_checked == 44)
          {
            $(".table_bg").parent().css({"width":"3360px"});
          }
          else if(show_cols_checked == 45)
          {
            $(".table_bg").parent().css({"width":"3300px"});
          }
          else if(show_cols_checked == 46)
          {
            $(".table_bg").parent().css({"width":"3240px"});
          }
          else if(show_cols_checked == 47)
          {
            $(".table_bg").parent().css({"width":"3180px"});
          }
          else if(show_cols_checked == 48)
          {
            $(".table_bg").parent().css({"width":"3120px"});
          }
          else if(show_cols_checked == 49)
          {
            $(".table_bg").parent().css({"width":"3060px"});
          }
          else if(show_cols_checked == 50)
          {
            $(".table_bg").parent().css({"width":"3000px"});
          }
          else if(show_cols_checked == 51)
          {
            $(".table_bg").parent().css({"width":"2940px"});
          }
          else if(show_cols_checked == 52)
          {
            $(".table_bg").parent().css({"width":"2880px"});
          }
          else if(show_cols_checked == 53)
          {
            $(".table_bg").parent().css({"width":"2820px"});
          }





          else if(show_cols_checked == 54)
          {
            $(".table_bg").parent().css({"width":"2760px"});
          }
          else if(show_cols_checked == 55)
          {
            $(".table_bg").parent().css({"width":"2700px"});
          }
          else if(show_cols_checked == 56)
          {
            $(".table_bg").parent().css({"width":"2640px"});
          }
          else if(show_cols_checked == 57)
          {
            $(".table_bg").parent().css({"width":"2520px"});
          }
          else if(show_cols_checked == 58)
          {
            $(".table_bg").parent().css({"width":"2460px"});
          }
          else if(show_cols_checked == 59)
          {
            $(".table_bg").parent().css({"width":"2400px"});
          }
          else if(show_cols_checked == 60)
          {
            $(".table_bg").parent().css({"width":"2440px"});
          }
          else if(show_cols_checked == 61)
          {
            $(".table_bg").parent().css({"width":"2380px"});
          }
          else if(show_cols_checked == 62)
          {
            $(".table_bg").parent().css({"width":"2320px"});
          }
          else if(show_cols_checked == 63)
          {
            $(".table_bg").parent().css({"width":"2260px"});
          }
          else if(show_cols_checked == 64)
          {
            $(".table_bg").parent().css({"width":"2200px"});
          }
          else if(show_cols_checked == 65)
          {
            $(".table_bg").parent().css({"width":"1200px"});
          }
          $("body").removeClass("loading");
        }
      })
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/gbs_update_paye_p30_hide_columns_status'); ?>",
        type:"post",
        data:{status:0},
        success: function(result)
        {
          $(".table_bg").parent().css({"width":"6000px"});
          $(".show_columns_td").show();

          $(e.target).addClass("hide_column");
          $(e.target).removeClass("show_column");
          $(e.target).val("Hide Columns");

          $("body").removeClass("loading");
        }
      })
    }
    
  }
  if($(e.target).hasClass('show_colnmns'))
  {
    $("body").addClass("loading");
    if($(e.target).is(":checked"))
    {
      var col_id = $(e.target).attr("data-element");
      var status = 1;
      $.ajax({
        url:"<?php echo URL::to('user/gbs_update_paye_p30_columns_status'); ?>",
        type:"post",
        data:{col_id:col_id,status:status},
        success: function(result)
        {
          if($("#show_hide_columns").hasClass('show_column'))
          {
            $(".show_columns_td").show();
            $(".show_colnmns:checked").each(function() {
              var element = $(this).attr("data-element");
              $("."+element).hide();
            });
          }
          else{
            $(".show_columns_td").show();
          }

          var show_cols_checked = $(".show_colnmns:checked").length;

          if(show_cols_checked == 1 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"5940px"});
          }
          else if(show_cols_checked == 2 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"5880px"});
          }
          else if(show_cols_checked == 3 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"5820px"});
          }
          else if(show_cols_checked == 4 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"5760px"});
          }
          else if(show_cols_checked == 5 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"5700px"});
          }
          else if(show_cols_checked == 6 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"5640px"});
          }
          else if(show_cols_checked == 7 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"5580px"});
          }
          else if(show_cols_checked == 8 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"5520px"});
          }
          else if(show_cols_checked == 9 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"5460px"});
          }
          else if(show_cols_checked == 10 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"5400px"});
          }
          else if(show_cols_checked == 11 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"5340px"});
          }
          else if(show_cols_checked == 12 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"5280px"});
          }
          else if(show_cols_checked == 13 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"5220px"});
          }
          else if(show_cols_checked == 14 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"5160px"});
          }
          else if(show_cols_checked == 15 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"5100px"});
          }
          else if(show_cols_checked == 16 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"5040px"});
          }
          else if(show_cols_checked == 17 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"4980px"});
          }
          else if(show_cols_checked == 18 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"4920px"});
          }
          else if(show_cols_checked == 19 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"4860px"});
          }
          else if(show_cols_checked == 20 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"4800px"});
          }
          else if(show_cols_checked == 21 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"4740px"});
          }
          else if(show_cols_checked == 22 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"4680px"});
          }
          else if(show_cols_checked == 23 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"4620px"});
          }
          else if(show_cols_checked == 24 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"4560px"});
          }
          else if(show_cols_checked == 25 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"4500px"});
          }
          else if(show_cols_checked == 26 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"4440px"});
          }
          else if(show_cols_checked == 27 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"4380px"});
          }
          else if(show_cols_checked == 28 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"4320px"});
          }
          else if(show_cols_checked == 29 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"4260px"});
          }
          else if(show_cols_checked == 30 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"4200px"});
          }
          else if(show_cols_checked == 31 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"4140px"});
          }
          else if(show_cols_checked == 32 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"4080px"});
          }
          else if(show_cols_checked == 33 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"4020px"});
          }
          else if(show_cols_checked == 34 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"3960px"});
          }
          else if(show_cols_checked == 35 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"3900px"});
          }
          else if(show_cols_checked == 36 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"3840px"});
          }
          else if(show_cols_checked == 37 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"3780px"});
          }
          else if(show_cols_checked == 38 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"3720px"});
          }
          else if(show_cols_checked == 39 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"3660px"});
          }
          else if(show_cols_checked == 40 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"3600px"});
          }
          else if(show_cols_checked == 41 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"3540px"});
          }
          else if(show_cols_checked == 42 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"3480px"});
          }
          else if(show_cols_checked == 43 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"3420px"});
          }
          else if(show_cols_checked == 44 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"3360px"});
          }
          else if(show_cols_checked == 45 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"3300px"});
          }
          else if(show_cols_checked == 46 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"3240px"});
          }
          else if(show_cols_checked == 47 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"3180px"});
          }
          else if(show_cols_checked == 48 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"3120px"});
          }
          else if(show_cols_checked == 49 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"3060px"});
          }
          else if(show_cols_checked == 50 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"3000px"});
          }
          else if(show_cols_checked == 51 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"2940px"});
          }
          else if(show_cols_checked == 52 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"2880px"});
          }
          else if(show_cols_checked == 53 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"2820px"});
          }





          else if(show_cols_checked == 54 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"2760px"});
          }
          else if(show_cols_checked == 55 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"2700px"});
          }
          else if(show_cols_checked == 56 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"2640px"});
          }
          else if(show_cols_checked == 57 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"2520px"});
          }
          else if(show_cols_checked == 58 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"2460px"});
          }
          else if(show_cols_checked == 59 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"2400px"});
          }
          else if(show_cols_checked == 60 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"2440px"});
          }
          else if(show_cols_checked == 61 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"2380px"});
          }
          else if(show_cols_checked == 62 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"2320px"});
          }
          else if(show_cols_checked == 63 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"2260px"});
          }
          else if(show_cols_checked == 64 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"2200px"});
          }
          else if(show_cols_checked == 65 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"1200px"});
          }

          $("body").removeClass("loading");
        }
      })
    }
    else{
      var col_id = $(e.target).attr("data-element");
      var status = 0;
      $.ajax({
        url:"<?php echo URL::to('user/gbs_update_paye_p30_columns_status'); ?>",
        type:"post",
        data:{col_id:col_id,status:status},
        success: function(result)
        {
          if($("#show_hide_task").hasClass('show_task'))
          {
            $(".show_columns_td").show();
            $(".show_colnmns:checked").each(function() {
              var element = $(this).attr("data-element");
              $("."+element).hide();
            });
          }
          else{
            $(".show_columns_td").show();
          }

          var show_cols_checked = $(".show_colnmns:checked").length;

          if(show_cols_checked == 1 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"5940px"});
          }
          else if(show_cols_checked == 2 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"5880px"});
          }
          else if(show_cols_checked == 3 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"5820px"});
          }
          else if(show_cols_checked == 4 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"5760px"});
          }
          else if(show_cols_checked == 5 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"5700px"});
          }
          else if(show_cols_checked == 6 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"5640px"});
          }
          else if(show_cols_checked == 7 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"5580px"});
          }
          else if(show_cols_checked == 8 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"5520px"});
          }
          else if(show_cols_checked == 9 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"5460px"});
          }
          else if(show_cols_checked == 10 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"5400px"});
          }
          else if(show_cols_checked == 11 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"5340px"});
          }
          else if(show_cols_checked == 12 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"5280px"});
          }
          else if(show_cols_checked == 13 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"5220px"});
          }
          else if(show_cols_checked == 14 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"5160px"});
          }
          else if(show_cols_checked == 15 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"5100px"});
          }
          else if(show_cols_checked == 16 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"5040px"});
          }
          else if(show_cols_checked == 17 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"4980px"});
          }
          else if(show_cols_checked == 18 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"4920px"});
          }
          else if(show_cols_checked == 19 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"4860px"});
          }
          else if(show_cols_checked == 20 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"4800px"});
          }
          else if(show_cols_checked == 21 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"4740px"});
          }
          else if(show_cols_checked == 22 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"4680px"});
          }
          else if(show_cols_checked == 23 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"4620px"});
          }
          else if(show_cols_checked == 24 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"4560px"});
          }
          else if(show_cols_checked == 25 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"4500px"});
          }
          else if(show_cols_checked == 26 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"4440px"});
          }
          else if(show_cols_checked == 27 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"4380px"});
          }
          else if(show_cols_checked == 28 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"4320px"});
          }
          else if(show_cols_checked == 29 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"4260px"});
          }
          else if(show_cols_checked == 30 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"4200px"});
          }
          else if(show_cols_checked == 31 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"4140px"});
          }
          else if(show_cols_checked == 32 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"4080px"});
          }
          else if(show_cols_checked == 33 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"4020px"});
          }
          else if(show_cols_checked == 34 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"3960px"});
          }
          else if(show_cols_checked == 35 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"3900px"});
          }
          else if(show_cols_checked == 36 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"3840px"});
          }
          else if(show_cols_checked == 37 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"3780px"});
          }
          else if(show_cols_checked == 38 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"3720px"});
          }
          else if(show_cols_checked == 39 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"3660px"});
          }
          else if(show_cols_checked == 40 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"3600px"});
          }
          else if(show_cols_checked == 41 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"3540px"});
          }
          else if(show_cols_checked == 42 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"3480px"});
          }
          else if(show_cols_checked == 43 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"3420px"});
          }
          else if(show_cols_checked == 44 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"3360px"});
          }
          else if(show_cols_checked == 45 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"3300px"});
          }
          else if(show_cols_checked == 46 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"3240px"});
          }
          else if(show_cols_checked == 47 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"3180px"});
          }
          else if(show_cols_checked == 48 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"3120px"});
          }
          else if(show_cols_checked == 49 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"3060px"});
          }
          else if(show_cols_checked == 50 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"3000px"});
          }
          else if(show_cols_checked == 51 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"2940px"});
          }
          else if(show_cols_checked == 52 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"2880px"});
          }
          else if(show_cols_checked == 53 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"2820px"});
          }





          else if(show_cols_checked == 54 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"2760px"});
          }
          else if(show_cols_checked == 55 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"2700px"});
          }
          else if(show_cols_checked == 56 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"2640px"});
          }
          else if(show_cols_checked == 57 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"2520px"});
          }
          else if(show_cols_checked == 58 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"2460px"});
          }
          else if(show_cols_checked == 59 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"2400px"});
          }
          else if(show_cols_checked == 60 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"2440px"});
          }
          else if(show_cols_checked == 61 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"2380px"});
          }
          else if(show_cols_checked == 62 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"2320px"});
          }
          else if(show_cols_checked == 63 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"2260px"});
          }
          else if(show_cols_checked == 64 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"2200px"});
          }
          else if(show_cols_checked == 65 && $("#show_hide_columns").hasClass('show_column'))
          {
            $(".table_bg").parent().css({"width":"1200px"});
          }

          $("body").removeClass("loading");
        }
      })
    }
  }


  if($(e.target).hasClass('email_unsent'))
  {
      $("body").addClass("loading");
      setTimeout(function() {
              if (CKEDITOR.instances.editor_1) CKEDITOR.instances.editor_1.destroy();
              CKEDITOR.replace('editor_1',
                       {
                        height: '150px',
                       }); 
              var task_id = $(e.target).attr('data-element');
              $.ajax({
                url:'<?php echo URL::to('user/gbs_paye_p30_edit_email_unsent_files'); ?>',

                type:'get',

                data:{task_id:task_id},

                dataType:"json",

                success: function(result)

                {

                    CKEDITOR.instances['editor_1'].setData(result['html']);

                    $(".subject_unsent").val(result['subject']);

                    $("#to_user").val(result['to']);

                    $("#from_user").val(result['from']);

                    $(".emailunsent").modal('show');

                    $("#hidden_email_task_id").val(task_id);

                }

              })


          $("body").removeClass("loading");

      },7000);
    
  }

  
});

//setup before functions

var typingTimer;

var doneTypingInterval = 500;

var $input = $('.ros_liability');



$input.on('keyup', function (event) {

  $(this).val($(this).val().replace(/[^0-9\.]/g,''));

    if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {

        event.preventDefault();

    }

  var input_val = $(this).val();

  var id = $(this).attr('data-element');

  clearTimeout(typingTimer);

  typingTimer = setTimeout(doneTyping, doneTypingInterval,input_val,id);

});



$input.on('keydown', function () {

  clearTimeout(typingTimer);

});



function doneTyping (input,id) {

  $("body").addClass("loading");

  setTimeout(function() {

      $.ajax({

        url:"<?php echo URL::to('user/gbs_paye_p30_ros_liability_update'); ?>",

        type:"get",

        dataType:"json",

        data:{liability:input,task_id:id},

        success: function(result) {
          $("#diff_liability_"+id).val(result['diff']);
          $("#ros_liability_"+id).val(result['ros_liability']);
          $("body").removeClass("loading");

        }

      })

  },1000);

}

</script>





@stop
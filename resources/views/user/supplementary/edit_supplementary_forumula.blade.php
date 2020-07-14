@extends('userheader')
@section('content')
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/jquery.dataTables.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/fixedHeader.dataTables.min.css'); ?>">

<script src="<?php echo URL::to('assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo URL::to('assets/js/dataTables.fixedHeader.min.js'); ?>"></script>

<script src="<?php echo URL::to('assets/js/jquery.form.js'); ?>"></script>
<script src="http://html2canvas.hertzen.com/dist/html2canvas.js"></script>

<link rel="stylesheet" href="<?php echo URL::to('assets/js/lightbox/colorbox.css'); ?>">
<script src="<?php echo URL::to('assets/js/lightbox/jquery.colorbox.js'); ?>"></script>
<style>
body{
  background: #f5f5f5 !important;
}

.label_class{
  float:left ;
  margin-top:15px;
  font-weight:700;
}
.upload_img{
  position: absolute;
    top: 0px;
    z-index: 1;
    background: rgb(226, 226, 226);
    padding: 19% 0%;
    text-align: center;
    overflow: hidden;
}
.upload_text{
  font-size: 15px;
    font-weight: 800;
    color: #631500;
}


.form-control[readonly]{
      background-color: #fff !important
}
.formtable tr td{
  padding-left: 15px;
  padding-right: 15px;
}
.fullviewtablelist>tbody>tr>td{
  font-weight:800 !important;
  font-size:15px !important;
}
.fullviewtablelist>tbody>tr>td a{
  font-weight:800 !important;
  font-size:15px !important;
}
.modal { overflow: auto !important;z-index: 999999;}
.pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover
{
  z-index: 0 !important;
}

.label_class{
  float:left ;
  margin-top:15px;
  font-weight:700;
}


.modal_load {
    display:    none;
    position:   fixed;
    z-index:    999999;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 ) 
                url(<?php echo URL::to('assets/images/loading.gif'); ?>) 
                50% 50% 
                no-repeat;
}

.report_div{
    position: absolute;
    top: 55%;
    left:20%;
    padding: 15px;
    background: #ff0;
    z-index: 999999;
    text-align: left;
}
.selectall{padding:10px 15px; background-image:linear-gradient(to bottom,#f5f5f5 0,#e8e8e8 100%); background: #f5f5f5; border:1px solid #ddd; border-radius: 3px;  }

.report_ok_button{background: #000; text-align: center; padding: 6px 12px; color: #fff; float: left; border: 0px; font-size: 13px; }
.report_ok_button:hover{background: #5f5f5f; text-decoration: none; color: #fff}
.report_ok_button:focus{background: #000; text-decoration: none; color: #fff}

.form-title{width: 100%; height: auto; float: left; margin-bottom: 5px;}

.form-control[disabled]{background: #d8d8d8 !important}

.value_1_output_class, .value_2_output_class, .value_3_output_class, .value_4_output_class, .value_5_output_class, .value_6_output_class{cursor: not-allowed;}

body.loading {
    overflow: hidden;   
}
body.loading .modal_load {
    display: block;
}
    .table thead th:focus{background: #ddd !important;}
    .form-control{border-radius: 0px;}
    .disabled{cursor :auto !important;pointer-events: auto !important}
    body #coupon {
      display: none;
    }
    @media print {
      body * {
        display: none;
      }
      body #coupon {
        display: block;
      }
    }
</style>
<script>
function popitup(url) {
    newwindow=window.open(url,'name','height=600,width=1500');
    if (window.focus) {newwindow.focus()}
    return false;
}
</script>
<style>
.error{color: #f00; font-size: 12px;}
a:hover{text-decoration: underline;}
</style>
<div class="content_section" style="margin-bottom:100px">
  <div class="page_title">
        <h4 class="col-lg-12" style="padding: 0px;font-size:15px">
        <?php $main = DB::table('supplementary_formula')->where('id',$notelist->formula_id)->first(); ?>
                P.S. You are now editing "<?php echo $notelist->name; ?>" note of "<?php echo $main->name; ?>" Supplementary note.
            </h4>

  <div style="clear: both;">
   <?php
    if(Session::has('message')) { ?>
        <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
    <?php } ?>
    </div> 


</div>

<div class="row">
  <div class="col-lg-12">
    <div class="col-lg-6">
      <!-- <label style="float:left;margin-right:15px;font-size:16px;margin-top:5px">Supplementary Note Name: <?php echo $notelist->name; ?></label> -->
      <input type="hidden" class="form-control name_class" value="<?php echo $notelist->name; ?>" placeholder="Enter Supplementary Note Name" name="" disabled>
    </div>
    <div class="col-lg-3"></div>
    <div class="col-lg-3 text-right">
      <a href="<?php echo URL::to('user/supplementary_note_create').'/'.base64_encode($main->id); ?>" class="common_black_button">Go Back to "<?php echo $main->name; ?>"</a>
    </div>
    <div class="col-lg-12">
    <div class="table-responsive">
    <input type="hidden" id="id_supply" value="<?php echo $id_supple_main ?>" name="">
<style type="text/css">
.formula_table{width: 70%;}
.formula_table tr td{line-height: 35px !important; font-size: 11px; font-weight: 500;}
.formula_table tr th{line-height: 16px !important; font-size: 11px; font-weight: 700;}
.formula_table tr td select,.formula_table tr td input,.formula_table tr td textarea{font-size:11px;}

.formula_table tr td select,.formula_table tr td input{height: 27px;margin-top:5px;}
.formula_table .add_variable_value{padding-top: 3px;}
    
.description_class_1, .description_class_2, .description_class_3, .description_class_4, .description_class_5, .description_class_6{    height: 45px !important;
    width: 150%;}
</style>
    <table class="table formula_table" align="center">
      <thead>
        <tr>
          <th width="70px"></th>        
          <th>Input Value</th>
          <th>Input type</th>
          <th width="110px">Select <br/> Value</th>
          <th width="110px" align="center">Select <br/>Commutation</th>
          <th width="110px">Select <br/> Value</th>
          <th >Output Value</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="td_title">Value 1</td>
          <td><input type="text" class="form-control value_1_class" value="<?php echo $notelist->value_1; ?>" name="" placeholder="Select Input Type"></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td><input type="text" class="form-control value_1_output_class" readonly value="<?php echo $notelist->value_1; ?>" name="" placeholder="Output Value"></td>
          <td><input type="button" class="common_black_button add_variable_value" data-element="value1" value="Add Value" name="" title="Add Value1 Variable value"></td>
          <td><textarea class="form-control description_class_1" placeholder="Enter Description"><?php echo $notelist->value_1_description; ?></textarea></td>
        </tr>
        <tr>
          <td class="td_title">Value 2</td>
          <td><input type="text" class="form-control value_2_class" value="<?php echo $notelist->value_2; ?>" name="" placeholder="Select Input Type"></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td><input type="text" class="form-control value_2_output_class" readonly name="" value="<?php echo $notelist->value_2; ?>" placeholder="Output Value"></td>
          <td><input type="button" class="common_black_button add_variable_value" data-element="value2" value="Add Value" name="" title="Add Value2 Variable value"></td>
          <td><textarea class="form-control description_class_2" placeholder="Enter Description"><?php echo $notelist->value_2_description; ?></textarea></td>
        </tr>
        <tr>
          <td class="td_title">Value 3</td>
          <td><input type="text" class="form-control value_3_class"<?php if($notelist->value_3 == 2){echo '';}else{echo'disabled';}?> placeholder="Select Input Type" value="<?php if($notelist->value_3 == 2){echo $notelist->value_3_number;}else{echo'';}?>" name=""></td>
          <td>
            <select class="form-control value_3_type">
              <option value="">Select Type</option>
              <option value="1" <?php if($notelist->value_3 == 1){echo 'selected';}else{ echo '';}?>>Combo</option>
              <option value="2" <?php if($notelist->value_3 == 2){echo 'selected';}else{ echo '';}?>>Alpha Numberic</option>
            </select>
          </td>
          <td>
            <select class="form-control value_3_one" <?php if($notelist->value_3 == 2){echo 'disabled';}elseif($notelist->value_3 == ''){echo 'disabled';}else{ echo '';}?>>
              <option value="">Select</option>
              <option value="1" <?php if($notelist->value_3 == 2){echo '';} elseif($notelist->value_3_combo1 == 1){echo 'selected';}else{ echo '';}?>>Value 1</option>
              <option value="2" <?php if($notelist->value_3 == 2){echo '';} elseif($notelist->value_3_combo1 == 2){echo 'selected';}else{ echo '';}?>>Value 2</option>
            </select>  
            <div class="error error_3_ouput" style="width: 30%; font-size: 14px; font-weight: bold; text-align: center; position: absolute;"></div>          
          </td>
          <td>
            <select class="form-control value_3_formula" <?php if($notelist->value_3 == 2){echo 'disabled';}elseif($notelist->value_3 == ''){echo 'disabled';}else{ echo '';}?>>
              <option value="">Select</option>
              <option value="1" <?php if($notelist->value_3 == 2){echo '';} elseif($notelist->value_3_formula == 1){echo 'selected';}else{ echo '';}?>>+</option>
              <option value="2" <?php if($notelist->value_3 == 2){echo '';} elseif($notelist->value_3_formula == 2){echo 'selected';}else{ echo '';}?>>-</option>
              <option value="3" <?php if($notelist->value_3 == 2){echo '';} elseif($notelist->value_3_formula == 3){echo 'selected';}else{ echo '';}?>>*</option>
              <option value="4" <?php if($notelist->value_3 == 2){echo '';} elseif($notelist->value_3_formula == 4){echo 'selected';}else{ echo '';}?>>/</option>
            </select>            
          </td>
          <td>
             <select class="form-control value_3_two" <?php if($notelist->value_3 == 2){echo 'disabled';}elseif($notelist->value_3 == ''){echo 'disabled';}else{ echo '';}?>>
              <option value="">Select</option>
              <option value="1" <?php if($notelist->value_3 == 2){echo '';} elseif($notelist->value_3_combo2 == 1){echo 'selected';}else{ echo '';}?>>Value 1</option>
              <option value="2" <?php if($notelist->value_3 == 2){echo '';} elseif($notelist->value_3_combo2 == 2){echo 'selected';}else{ echo '';}?>>Value 2</option>
            </select>
          </td>
          <td><input type="text" class="form-control value_3_output_class" readonly value="<?php echo $notelist->value_3_output; ?>" name="" placeholder="Output Value">            
          </td>
          <td>
            <input type="button" class="common_black_button add_variable_value" data-element="value3" value="Add Value" name="" title="Add Value3 Variable value">
          </td>
          <td>
            <textarea class="form-control description_class_3" placeholder="Enter Description"><?php echo $notelist->value_3_description; ?></textarea>
          </td>        
        </tr>
        <tr>
          <td class="td_title">Value 4</td>
          <td><input type="text" class="form-control value_4_class" placeholder="Select Input Type" name="" <?php if($notelist->value_4 == 2){echo '';}else{echo'disabled';}?> value="<?php if($notelist->value_4 == 2){echo $notelist->value_4_number;}else{echo'';}?>"></td>
          <td>
            <select class="form-control value_4_type">
              <option value="">Select Type</option>
              <option value="1" <?php if($notelist->value_4 == 1){echo 'selected';}else{ echo '';}?>>Combo</option>
              <option value="2" <?php if($notelist->value_4 == 2){echo 'selected';}else{ echo '';}?>>Alpha Numberic</option>
            </select>
          </td>
          <td>
            <select class="form-control value_4_one" <?php if($notelist->value_4 == 2){echo 'disabled';}elseif($notelist->value_4 == ''){echo 'disabled';}else{ echo '';}?>>
              <option value="">Select</option>
              <option value="1" <?php if($notelist->value_4 == 2){echo '';} elseif($notelist->value_4_combo1 == 1){echo 'selected';}else{ echo '';}?>>Value 1</option>
              <option value="2"<?php if($notelist->value_4 == 2){echo '';} elseif($notelist->value_4_combo1 == 2){echo 'selected';}else{ echo '';}?>>Value 2</option>
              <option value="3" <?php if($notelist->value_4 == 2){echo '';} elseif($notelist->value_4_combo1 == 3){echo 'selected';}else{ echo '';}?>>Value 3</option>
            </select>
            <div class="error error_4_ouput" style="width: 30%; font-size: 14px; font-weight: bold; text-align: center; position: absolute;"></div>
          </td>
          <td>
            <select class="form-control value_4_formula" <?php if($notelist->value_4 == 2){echo 'disabled';}elseif($notelist->value_4 == ''){echo 'disabled';}else{ echo '';}?>>
              <option value="">Select</option>
              <option value="1" <?php if($notelist->value_4 == 2){echo '';} elseif($notelist->value_4_formula == 1){echo 'selected';}else{ echo '';}?>>+</option>
              <option value="2" <?php if($notelist->value_4 == 2){echo '';} elseif($notelist->value_4_formula == 2){echo 'selected';}else{ echo '';}?>>-</option>
              <option value="3" <?php if($notelist->value_4 == 2){echo '';} elseif($notelist->value_4_formula == 3){echo 'selected';}else{ echo '';}?>>*</option>
              <option value="4" <?php if($notelist->value_4 == 2){echo '';} elseif($notelist->value_4_formula == 4){echo 'selected';}else{ echo '';}?>>/</option>
            </select>            
          </td>
          <td>
             <select class="form-control value_4_two" <?php if($notelist->value_4 == 2){echo 'disabled';}elseif($notelist->value_4 == ''){echo 'disabled';}else{ echo '';}?>>
              <option value="">Select</option>
              <option value="1" <?php if($notelist->value_4 == 2){echo '';} elseif($notelist->value_4_combo2 == 1){echo 'selected';}else{ echo '';}?>>Value 1</option>
              <option value="2" <?php if($notelist->value_4 == 2){echo '';} elseif($notelist->value_4_combo2 == 2){echo 'selected';}else{ echo '';}?>>Value 2</option>
              <option value="3" <?php if($notelist->value_4 == 2){echo '';} elseif($notelist->value_4_combo2 == 3){echo 'selected';}else{ echo '';}?>>Value 3</option>
            </select>
          </td>
          <td><input type="text" class="form-control value_4_output_class" readonly name="" value="<?php echo $notelist->value_4_output; ?>" placeholder="Output Value">            
          </td>
          <td>
            <input type="button" class="common_black_button add_variable_value" data-element="value4" value="Add Value" name="" title="Add Value4 Variable value">
          </td>
          <td>
            <textarea class="form-control description_class_4" placeholder="Enter Description"><?php echo $notelist->value_4_description; ?></textarea>
          </td>        
        </tr>
        <tr>
          <td class="td_title">Value 5</td>
          <td><input type="text" class="form-control value_5_class" placeholder="Select Input Type" name="" <?php if($notelist->value_5 == 2){echo '';}else{echo'disabled';}?> value="<?php if($notelist->value_5 == 2){echo $notelist->value_5_number;}else{echo'';}?>"></td>
          <td>
            <select class="form-control value_5_type">
              <option value="">Select Type</option>
              <option value="1" <?php if($notelist->value_5 == 1){echo 'selected';}else{ echo '';}?>>Combo</option>
              <option value="2" <?php if($notelist->value_5 == 2){echo 'selected';}else{ echo '';}?>>Alpha Numberic</option>
            </select>
          </td>
          <td>
            <select class="form-control value_5_one" <?php if($notelist->value_5 == 2){echo 'disabled';}elseif($notelist->value_5 == ''){echo 'disabled';}else{ echo '';}?>>
              <option value="">Select</option>
              <option value="1" <?php if($notelist->value_5 == 2){echo '';} elseif($notelist->value_5_combo1 == 1){echo 'selected';}else{ echo '';}?>>Value 1</option>
              <option value="2" <?php if($notelist->value_5 == 2){echo '';} elseif($notelist->value_5_combo1 == 2){echo 'selected';}else{ echo '';}?>>Value 2</option>
              <option value="3" <?php if($notelist->value_5 == 2){echo '';} elseif($notelist->value_5_combo1 == 3){echo 'selected';}else{ echo '';}?>>Value 3</option>
              <option value="4" <?php if($notelist->value_5 == 2){echo '';} elseif($notelist->value_5_combo1 == 4){echo 'selected';}else{ echo '';}?>>Value 4</option>
            </select>
            <div class="error error_5_ouput" style="width: 30%; font-size: 14px; font-weight: bold; text-align: center; position: absolute;"></div>
          </td>
          <td>
            <select class="form-control value_5_formula" <?php if($notelist->value_5 == 2){echo 'disabled';}elseif($notelist->value_5 == ''){echo 'disabled';}else{ echo '';}?>>
              <option value="">Select</option>
              <option value="1" <?php if($notelist->value_5 == 2){echo '';} elseif($notelist->value_5_formula == 1){echo 'selected';}else{ echo '';}?>>+</option>
              <option value="2" <?php if($notelist->value_5 == 2){echo '';} elseif($notelist->value_5_formula == 2){echo 'selected';}else{ echo '';}?>>-</option>
              <option value="3" <?php if($notelist->value_5 == 2){echo '';} elseif($notelist->value_5_formula == 3){echo 'selected';}else{ echo '';}?>>*</option>
              <option value="4" <?php if($notelist->value_5 == 2){echo '';} elseif($notelist->value_5_formula == 4){echo 'selected';}else{ echo '';}?>>/</option>
            </select>
          </td>
          <td>
             <select class="form-control value_5_two" <?php if($notelist->value_5 == 2){echo 'disabled';}elseif($notelist->value_5 == ''){echo 'disabled';}else{ echo '';}?>>
              <option value="">Select</option>
              <option value="1" <?php if($notelist->value_5 == 2){echo '';} elseif($notelist->value_5_combo2 == 1){echo 'selected';}else{ echo '';}?>>Value 1</option>
              <option value="2" <?php if($notelist->value_5 == 2){echo '';} elseif($notelist->value_5_combo2 == 2){echo 'selected';}else{ echo '';}?>>Value 2</option>
              <option value="3" <?php if($notelist->value_5 == 2){echo '';} elseif($notelist->value_5_combo2 == 3){echo 'selected';}else{ echo '';}?>>Value 3</option>
              <option value="4" <?php if($notelist->value_5 == 2){echo '';} elseif($notelist->value_5_combo2 == 4){echo 'selected';}else{ echo '';}?>>Value 4</option>
            </select>
          </td>
          <td><input type="text" class="form-control value_5_output_class" readonly name="" value="<?php echo $notelist->value_5_output; ?>" placeholder="Output Value">
            
          </td>
          <td>
            <input type="button" class="common_black_button add_variable_value" data-element="value5" value="Add Value" name="" title="Add Value5 Variable value">
          </td>
          <td>
            <textarea class="form-control description_class_5" placeholder="Enter Description"><?php echo $notelist->value_5_description; ?></textarea>
          </td>        
        </tr>
        <tr>
          <td class="td_title">Value 6</td>
          <td><input type="text" class="form-control value_6_class" placeholder="Select Input Type" name="" <?php if($notelist->value_6 == 2){echo '';}else{echo'disabled';}?> value="<?php if($notelist->value_6 == 2){echo $notelist->value_6_number;}else{echo'';}?>"></td>
          <td>
            <select class="form-control value_6_type">
              <option value="">Select Type</option>
              <option value="1" <?php if($notelist->value_6 == 1){echo 'selected';}else{ echo '';}?>>Combo</option>
              <option value="2" <?php if($notelist->value_6 == 2){echo 'selected';}else{ echo '';}?>>Alpha Numberic</option>
            </select>
          </td>
          <td>
            <select class="form-control value_6_one" <?php if($notelist->value_6 == 2){echo 'disabled';}elseif($notelist->value_6 == ''){echo 'disabled';}else{ echo '';}?>>
              <option value="">Select</option>
              <option value="1" <?php if($notelist->value_6 == 2){echo '';} elseif($notelist->value_6_combo1 == 1){echo 'selected';}else{ echo '';}?>>Value 1</option>
              <option value="2" <?php if($notelist->value_6 == 2){echo '';} elseif($notelist->value_6_combo1 == 2){echo 'selected';}else{ echo '';}?>>Value 2</option>
              <option value="3" <?php if($notelist->value_6 == 2){echo '';} elseif($notelist->value_6_combo1 == 3){echo 'selected';}else{ echo '';}?>>Value 3</option>
              <option value="4" <?php if($notelist->value_6 == 2){echo '';} elseif($notelist->value_6_combo1 == 4){echo 'selected';}else{ echo '';}?>>Value 4</option>
              <option value="5" <?php if($notelist->value_6 == 2){echo '';} elseif($notelist->value_6_combo1 == 5){echo 'selected';}else{ echo '';}?>>Value 5</option>              
            </select>
            <div class="error error_6_ouput" style="width: 30%; font-size: 14px; font-weight: bold; text-align: center; position: absolute;"></div>            
          </td>
          <td>
            <select class="form-control value_6_formula" <?php if($notelist->value_6 == 2){echo 'disabled';}elseif($notelist->value_6 == ''){echo 'disabled';}else{ echo '';}?>>
              <option value="">Select</option>
              <option value="1" <?php if($notelist->value_6 == 2){echo '';} elseif($notelist->value_6_formula == 1){echo 'selected';}else{ echo '';}?>>+</option>
              <option value="2" <?php if($notelist->value_6 == 2){echo '';} elseif($notelist->value_6_formula == 2){echo 'selected';}else{ echo '';}?>>-</option>
              <option value="3" <?php if($notelist->value_6 == 2){echo '';} elseif($notelist->value_6_formula == 3){echo 'selected';}else{ echo '';}?>>*</option>
              <option value="4" <?php if($notelist->value_6 == 2){echo '';} elseif($notelist->value_6_formula == 4){echo 'selected';}else{ echo '';}?>>/</option>
            </select>

          </td>
          <td>
             <select class="form-control value_6_two" <?php if($notelist->value_6 == 2){echo 'disabled';}elseif($notelist->value_6 == ''){echo 'disabled';}else{ echo '';}?>>
              <option value="">Select</option>
              <option value="1" <?php if($notelist->value_6 == 2){echo '';} elseif($notelist->value_6_combo2 == 1){echo 'selected';}else{ echo '';}?>>Value 1</option>
              <option value="2" <?php if($notelist->value_6 == 2){echo '';} elseif($notelist->value_6_combo2 == 2){echo 'selected';}else{ echo '';}?>>Value 2</option>
              <option value="3" <?php if($notelist->value_6 == 2){echo '';} elseif($notelist->value_6_combo2 == 3){echo 'selected';}else{ echo '';}?>>Value 3</option>
              <option value="4" <?php if($notelist->value_6 == 2){echo '';} elseif($notelist->value_6_combo2 == 4){echo 'selected';}else{ echo '';}?>>Value 4</option>
              <option value="5" <?php if($notelist->value_6 == 2){echo '';} elseif($notelist->value_6_combo2 == 5){echo 'selected';}else{ echo '';}?>>Value 5</option>
            </select>
          </td>
          <td><input type="text" class="form-control value_6_output_class" readonly name="" value="<?php echo $notelist->value_6_output; ?>" placeholder="Output Value">            
          </td>
          <td>
            <input type="button" class="common_black_button add_variable_value" data-element="value6" value="Add Value" name="" title="Add Value6 Variable value">
          </td>
          <td>
            <textarea class="form-control description_class_6" placeholder="Enter Description"><?php echo $notelist->value_6_description; ?></textarea>
          </td>        
        </tr>
        </tbody>
        </table>
        <table class="table" align="center">
        <tbody>
        <tr>
          <td colspan="9">
            <div style="float: left;margin-top: 9px;">Invoice Number:</div>
            <div style="float: left; margin: 0px 15px"><input type="text" class="form-control invoice_class" value="<?php echo $notelist->invoice_number; ?>" placeholder="Enter Invoice Number" name=""></div>
            <div style="float: left"><input type="button" class="common_black_button add_variable_value" data-element="invoice" value="Add Value" name="" title="Add Invoice Variable value"></div>
          </td>          
        </tr>
        <tr>
          <td colspan="7">
            <div style="float: left;margin-top: 9px;">Magic Fixed Text:</div>
            <div style="float: left;" class="col-lg-5"><input type="text" class="form-control magic_text" placeholder="Enter Magic Fixed Text" name="magic_text"></div>
            <div style="float: left"><input type="button" class="common_black_button add_fixed_text" value="Add Fixed Text" name="" title="Add Fixed Text"></div>
          </td>
          <td colspan="2" align="right">
            <div class="col-lg-12" style="margin-top:9px">
                <a href="javascript:" class="common_black_button undo" title="Undo Process"><i class="fa fa-undo"></i> Undo</a>
                <a href="javascript:" class="common_black_button redo" title="Redo Process"><i class="fa fa-repeat"></i>  Redo</a>
                <a href="javascript:" class="common_black_button clear" title="Clear Process">Clear</a>
            </div>
          </td>
        </tr>
        <tr>
          <td colspan="9">
            <div class="col-lg-12" style="padding: 0px;">
              <label>HOLDING TEXT: </label>
              <textarea class="form-control magic_text_output" style="height: 110px;"><?php echo $notelist->fixed_text; ?></textarea>
            </div>
            
            <div class="clearfix"></div>
            <div class="col-lg-12 text-right" style="padding: 10px 0px 0px 5px">
              <input type="button" class="common_black_button build_note_text" value="BUILD DISPLAY NOTE TEXT" name="" title="BUILD DISPLAY NOTE TEXT">
            </div>
          </td>
        </tr>
        <tr>
          <td colspan="9">
            <div class="col-lg-12" style="padding: 0px;">
              <label>DEMONSTRATION DISPLAY TEXT: </label>
              <textarea class="form-control build_note_output" style="height: 110px;"><?php echo $notelist->supplementary_text; ?></textarea>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-12 text-right" style="padding: 10px 0px 0px 5px">
              <input type="button" class="common_black_button save_supplementary_note" value="UPDATE SUPPLEMENTARY NOTE" name="" title="SAVE THIS SUPPLEMENTARY NOTE">
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  </div>
</div>
</div>
    <!-- End  -->

<div class="main-backdrop"><!-- --></div>

<div class="hidden_undo_elements"></div>
<div class="hidden_redo_elements"></div>


<div class="modal_load"></div>
<input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
<input type="hidden" name="show_alert" id="show_alert" value="">
<input type="hidden" name="pagination" id="pagination" value="1">





<script>
$.ajaxSetup({
    async: true
});
$(window).click(function(e) {
if($(e.target).hasClass('save_supplementary_note'))
{
  var build_note_output = $(".build_note_output").val();
  if(build_note_output == "")
  {
    alert("Display Text Note is Empty. Please add a text and add a variable value to build a display note text");
  }
  else{
    var id = "<?php echo $id_supple_main; ?>";
    var name = $(".name_class").val();
    var value_1 = $(".value_1_class").val();
    var value_1_description = $(".description_class_1").val();
    var value_2 = $(".value_2_class").val();
    var value_2_description = $(".description_class_2").val();

    var value_3 = $(".value_3_class").val();
    var value_3_number = $(".value_3_type").val();
    var value_3_combo1 = $(".value_3_one").val();
    var value_3_combo2 = $(".value_3_two").val();
    var value_3_formula = $(".value_3_formula").val();
    var value_3_output = $(".value_3_output_class").val();
    var value_3_description = $(".description_class_3").val();

    var value_4 = $(".value_4_class").val();
    var value_4_number = $(".value_4_type").val();
    var value_4_combo1 = $(".value_4_one").val();
    var value_4_combo2 = $(".value_4_two").val();
    var value_4_formula = $(".value_4_formula").val();
    var value_4_output = $(".value_4_output_class").val();
    var value_4_description = $(".description_class_4").val();

    var value_5 = $(".value_5_class").val();
    var value_5_number = $(".value_5_type").val();
    var value_5_combo1 = $(".value_5_one").val();
    var value_5_combo2 = $(".value_5_two").val();
    var value_5_formula = $(".value_5_formula").val();
    var value_5_output = $(".value_5_output_class").val();
    var value_5_description = $(".description_class_5").val();

    var value_6 = $(".value_6_class").val();
    var value_6_number = $(".value_6_type").val();
    var value_6_combo1 = $(".value_6_one").val();
    var value_6_combo2 = $(".value_6_two").val();
    var value_6_formula = $(".value_6_formula").val();
    var value_6_output = $(".value_6_output_class").val();
    var value_6_description = $(".description_class_6").val();

    var invoice_number = $(".invoice_class").val();

    var fixed_text = $(".magic_text_output").val();
    var supplementary_text = $(".build_note_output").val();

    $(".build_note_output").val("");

    $.ajax({
      url:"<?php echo URL::to("user/update_supplementary_note"); ?>",
      type:"post",
      data:{id:id,name:name,value_1:value_1,value_1_description:value_1_description,value_2:value_2,value_2_description:value_2_description,value_3:value_3,value_3_number:value_3_number,value_3_combo1:value_3_combo1,value_3_combo2:value_3_combo2,value_3_formula:value_3_formula,value_3_output:value_3_output,value_3_description:value_3_description,value_4:value_4,value_4_number:value_4_number,value_4_combo1:value_4_combo1,value_4_combo2:value_4_combo2,value_4_formula:value_4_formula,value_4_output:value_4_output,value_4_description:value_4_description,value_5:value_5,value_5_number:value_5_number,value_5_combo1:value_5_combo1,value_5_combo2:value_5_combo2,value_5_formula:value_5_formula,value_5_output:value_5_output,value_5_description:value_5_description,value_6:value_6,value_6_number:value_6_number,value_6_combo1:value_6_combo1,value_6_combo2:value_6_combo2,value_6_formula:value_6_formula,value_6_output:value_6_output,value_6_description:value_6_description,invoice_number:invoice_number,fixed_text:fixed_text,supplementary_text:supplementary_text},
      success: function(result)
      {
        window.location.reload();
      }
    });
  }
}
if($(e.target).hasClass("build_note_text"))
{
  var magic_text = $(".magic_text_output").val();
  if(magic_text != "")
  {
    var value1 = $(".value_1_output_class").val();
    var value2 = $(".value_2_output_class").val();
    var value3 = $(".value_3_output_class").val();
    var value4 = $(".value_4_output_class").val();
    var value5 = $(".value_5_output_class").val();
    var value6 = $(".value_6_output_class").val();
    var invoice = $(".invoice_class").val();

    var res = magic_text.replace("<<value1>>",value1);
    res = res.replace("<<value2>>",value2);
    res = res.replace("<<value3>>",value3);
    res = res.replace("<<value4>>",value4);
    res = res.replace("<<value5>>",value5);
    res = res.replace("<<value6>>",value6);
    res = res.replace("<<invoice>>",invoice);

    $(".build_note_output").val(res);
    $(".magic_text").val("");
    $(".hidden_undo_elements").html("");
    $(".hidden_undo_elements").html("");

    var magic = $(".magic_text_output").val();
    var supple_text = $(".build_note_output").val();
    var id = "<?php echo $id_supple_main; ?>";
    $.ajax({
      url:"<?php echo URL::to('user/update_fixed_text_edit'); ?>",
      type:"post",
      data:{magic:magic,magic_text:magic_text,supple_text:supple_text,id:id},
      success: function(result)
      {

      }
    });
  }
  else{
    alert("Fixed Text Note is Empty. Please add a text and add a variable value to build a display note text");
  }
}
if($(e.target).hasClass("undo"))
{
  var count = $(".hidden_undo_text_element").length;
  if(count < 1)
  {
    alert("There is no undo operations are done");
  }
  else{
    var get_value = $.trim($(".hidden_undo_text_element").last().val());
    var textarea_val = $.trim($(".magic_text_output").val());
    var res = textarea_val.replace(get_value, "");
    $(".magic_text_output").val(res);
    $(".hidden_undo_text_element").last().detach();
    var elements = $(".hidden_redo_elements").html();
    $(".hidden_redo_elements").html(elements+'<input type="hidden" class="hidden_redo_text_element" value="'+get_value+'">');
  }
  var magic = $(".magic_text_output").val();
  var supple_text = $(".build_note_output").val();
  var id = "<?php echo $id_supple_main; ?>";
  $.ajax({
    url:"<?php echo URL::to('user/update_fixed_text_edit'); ?>",
    type:"post",
    data:{magic:magic,magic_text:magic,supple_text:supple_text,id:id},
    success: function(result)
    {

    }
  });
}
if($(e.target).hasClass("redo"))
{
  var count = $(".hidden_redo_text_element").length;
  if(count < 1)
  {
    alert("There is no redo operations are done");
  }
  else{
    var get_value = $.trim($(".hidden_redo_text_element").last().val());
    var textarea_val = $.trim($(".magic_text_output").val());
    if(textarea_val == "")
    {
      var output = get_value;
    }
    else{
      var output = textarea_val+' '+get_value;
    }
    $(".magic_text_output").val(output);

    $(".hidden_redo_text_element").last().detach();
    var elements = $(".hidden_undo_elements").html();
    $(".hidden_undo_elements").html(elements+'<input type="hidden" class="hidden_undo_text_element" value="'+get_value+'">');
  }
  var magic = $(".magic_text_output").val();
  var supple_text = $(".build_note_output").val();
  var id = "<?php echo $id_supple_main; ?>";
  $.ajax({
    url:"<?php echo URL::to('user/update_fixed_text_edit'); ?>",
    type:"post",
    data:{magic:magic,magic_text:magic,supple_text:supple_text,id:id},
    success: function(result)
    {

    }
  });
}
if($(e.target).hasClass("clear"))
{
  var r = confirm("Are you sure you want to clear the fixed text?");
  if(r)
  {
    $(".magic_text_output").val("");
    $(".hidden_undo_elements").html("");
    $(".hidden_redo_elements").html("");
  }
  else{
    return false;
  }
  var magic = $(".magic_text_output").val();
  var supple_text = $(".build_note_output").val();
  var id = "<?php echo $id_supple_main; ?>";
  $.ajax({
    url:"<?php echo URL::to('user/update_fixed_text_edit'); ?>",
    type:"post",
    data:{magic:magic,magic_text:magic,supple_text:supple_text,id:id},
    success: function(result)
    {

    }
  });
}
if($(e.target).hasClass('add_variable_value'))
{
  var element = $(e.target).attr("data-element");
  var magic_text_output = $.trim($(".magic_text_output").val());
  if(magic_text_output == "")
  {
    var output = '<<'+element+'>>';
  }
  else{
    var output = magic_text_output+' <<'+element+'>>';
  }
  $(".magic_text_output").val(output);
  var elements = $(".hidden_undo_elements").html();
  $(".hidden_undo_elements").html(elements+'<input type="hidden" class="hidden_undo_text_element" value="<<'+element+'>>">');

  var magic = $(".magic_text_output").val();
  var supple_text = $(".build_note_output").val();
  var id = "<?php echo $id_supple_main; ?>";
  $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>Variable Added to Note</p>", fixed:true});
  $.ajax({
    url:"<?php echo URL::to('user/update_fixed_text_edit'); ?>",
    type:"post",
    data:{magic:magic,magic_text:magic,supple_text:supple_text,id:id},
    success: function(result)
    {

    }
  });
}
if($(e.target).hasClass('add_fixed_text'))
{
  var magic_text = $(".magic_text").val();
  var magic_text_output = $.trim($(".magic_text_output").val());
  if(magic_text_output == "")
  {
    var output = magic_text;
  }
  else{
    var output = magic_text_output+' '+magic_text;
  }
  $(".magic_text_output").val(output);
  $(".magic_text").val("");
  var elements = $(".hidden_undo_elements").html();
  $(".hidden_undo_elements").html(elements+'<input type="hidden" class="hidden_undo_text_element" value="'+magic_text+'">');

  var magic = $(".magic_text_output").val();
  var supple_text = $(".build_note_output").val();
  var id = "<?php echo $id_supple_main; ?>";
  $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>Fixed Text Added to Note</p>", fixed:true});
  $.ajax({
    url:"<?php echo URL::to('user/update_fixed_text_edit'); ?>",
    type:"post",
    data:{magic:magic,magic_text:magic,supple_text:supple_text,id:id},
    success: function(result)
    {

    }
  });
}
if($(e.target).hasClass('add_new')){
    $(".add_modal").modal("show");
}

if($(e.target).hasClass('value_3_type')){
  var value = $(e.target).val();
  var id = $("#id_supply").val();
  if (value == 1) {     
      $.ajax({
        url:"<?php echo URL::to('user/supple_type_update_edit')?>",
        type:"post",
        dataType:"json",
        data:{value:value,id:id,type:3},
        success: function(result) {
          $(".value_3_class").val('');
          $(".value_3_class").prop("disabled", true);
          $(".value_3_one").prop("disabled", false);
          $(".value_3_formula").prop("disabled", false);
          $(".value_3_two").prop("disabled", false);
          $(".value_3_output_class").val('');
          $(".value_3_one").val(result['combo1']);
          $(".value_3_two").val(result['combo2']);
          $(".value_3_formula").val(result['formula']);
          $(".value_3_output_class").val(result['output']);
        }
      });
  }

  else if (value == 2) {     
    $.ajax({
        url:"<?php echo URL::to('user/supple_type_update_edit')?>",
        type:"post",
        dataType:"json",
        data:{value:value,id:id,type:3},
        success: function(result) {
          $(".value_3_output_class").val(result['output']);
          $(".value_3_class").prop("disabled", false);
          $(".value_3_one").prop("disabled", true);
          $(".value_3_one").val('');
          $(".value_3_formula").prop("disabled", true);
          $(".value_3_formula").val('');
          $(".value_3_two").prop("disabled", true);
          $(".value_3_two").val('');
          
        }
      });
  }
}



if($(e.target).hasClass('value_4_type')){
  var value = $(e.target).val();
  var id = $("#id_supply").val();
  if (value == 1) {     
      $.ajax({
        url:"<?php echo URL::to('user/supple_type_update_edit')?>",
        type:"post",
        dataType:"json",
        data:{value:value,id:id,type:4},
        success: function(result) {
          $(".value_4_class").val('');
          $(".value_4_class").prop("disabled", true);
          $(".value_4_one").prop("disabled", false);
          $(".value_4_formula").prop("disabled", false);
          $(".value_4_two").prop("disabled", false);
          $(".value_4_output_class").val('');
          $(".value_4_one").val(result['combo1']);
          $(".value_4_two").val(result['combo2']);
          $(".value_4_formula").val(result['formula']);
          $(".value_4_output_class").val(result['output']);
        }
      });
  }

  else if (value == 2) {     
    $.ajax({
        url:"<?php echo URL::to('user/supple_type_update_edit')?>",
        type:"post",
        dataType:"json",
        data:{value:value,id:id,type:4},
        success: function(result) {
          $(".value_4_class").val(result['type_4']);
          $(".value_4_class").prop("disabled", false);
          $(".value_4_one").prop("disabled", true);
          $(".value_4_one").val('');
          $(".value_4_formula").prop("disabled", true);
          $(".value_4_formula").val('');
          $(".value_4_two").prop("disabled", true);
          $(".value_4_two").val('');
          
        }
      });
  }
}


if($(e.target).hasClass('value_5_type')){
  var value = $(e.target).val();
  var id = $("#id_supply").val();
  if (value == 1) {     
      $.ajax({
        url:"<?php echo URL::to('user/supple_type_update_edit')?>",
        type:"post",
        dataType:"json",
        data:{value:value,id:id,type:5},
        success: function(result) {
          $(".value_5_class").val('');
          $(".value_5_class").prop("disabled", true);
          $(".value_5_one").prop("disabled", false);
          $(".value_5_formula").prop("disabled", false);
          $(".value_5_two").prop("disabled", false);
          $(".value_5_output_class").val('');
          $(".value_5_one").val(result['combo1']);
          $(".value_5_two").val(result['combo2']);
          $(".value_5_formula").val(result['formula']);
          $(".value_5_output_class").val(result['output']);
        }
      });
  }

  else if (value == 2) {     
    $.ajax({
        url:"<?php echo URL::to('user/supple_type_update_edit')?>",
        type:"post",
        dataType:"json",
        data:{value:value,id:id,type:5},
        success: function(result) {
          $(".value_5_class").val(result['type_5']);
          $(".value_5_class").prop("disabled", false);
          $(".value_5_one").prop("disabled", true);
          $(".value_5_one").val('');
          $(".value_5_formula").prop("disabled", true);
          $(".value_5_formula").val('');
          $(".value_5_two").prop("disabled", true);
          $(".value_5_two").val('');
          
        }
      });
  }
}

if($(e.target).hasClass('value_6_type')){
  var value = $(e.target).val();
  var id = $("#id_supply").val();
  if (value == 1) {     
      $.ajax({
        url:"<?php echo URL::to('user/supple_type_update_edit')?>",
        type:"post",
        dataType:"json",
        data:{value:value,id:id,type:6},
        success: function(result) {
          $(".value_6_class").val('');
          $(".value_6_class").prop("disabled", true);
          $(".value_6_one").prop("disabled", false);
          $(".value_6_formula").prop("disabled", false);
          $(".value_6_two").prop("disabled", false);
          $(".value_6_output_class").val('');
          $(".value_6_one").val(result['combo1']);
          $(".value_6_two").val(result['combo2']);
          $(".value_6_formula").val(result['formula']);
          $(".value_6_output_class").val(result['output']);
        }
      });
  }

  else if (value == 2) {     
    $.ajax({
        url:"<?php echo URL::to('user/supple_type_update_edit')?>",
        type:"post",
        dataType:"json",
        data:{value:value,id:id,type:6},
        success: function(result) {
          $(".value_6_class").val(result['type_6']);
          $(".value_6_class").prop("disabled", false);
          $(".value_6_one").prop("disabled", true);
          $(".value_6_one").val('');
          $(".value_6_formula").prop("disabled", true);
          $(".value_6_formula").val('');
          $(".value_6_two").prop("disabled", true);
          $(".value_6_two").val('');
          
        }
      });
  }
}



if($(e.target).hasClass('value_3_one')){
  var value = $(e.target).val();
  var id = $("#id_supply").val();
  $.ajax({
    url:"<?php echo URL::to('user/supple_comboone_update_edit')?>",
    type:"post",
    dataType:"json",
    data:{value:value,id:id,type:3},
    success: function(result) {
      if(result['value'] == 1){
        $(".value_3_output_class").val(result['output']);
        $(".error_3_ouput").hide();
      }
      else{
        $(".error_3_ouput").html(result['message']);
        $(".error_3_ouput").show();
        $(".value_3_output_class").val(result['output']);
      }
      
    }
  });
}



if($(e.target).hasClass('value_3_formula')){
  var value = $(e.target).val();
  var id = $("#id_supply").val();
  $.ajax({
    url:"<?php echo URL::to('user/supple_formula_update_edit')?>",
    type:"post",
    dataType:"json",
    data:{value:value,id:id,type:3},
    success: function(result) {
      if(result['value'] == 1){
        $(".value_3_output_class").val(result['output']);
        $(".error_3_ouput").hide();
      }
      else{
        $(".error_3_ouput").html(result['message']);
        $(".error_3_ouput").show();
        $(".value_3_output_class").val(result['output']);
      }
      
    }
  });
}

if($(e.target).hasClass('value_3_two')){
  var value = $(e.target).val();
  var id = $("#id_supply").val();
  $.ajax({
    url:"<?php echo URL::to('user/supple_combotwo_update_edit')?>",
    type:"post",
    dataType:"json",
    data:{value:value,id:id,type:3},
    success: function(result) {
      if(result['value'] == 1){
        $(".value_3_output_class").val(result['output']);
        $(".error_3_ouput").hide();
      }
      else{
        $(".error_3_ouput").html(result['message']);
        $(".error_3_ouput").show();
        $(".value_3_output_class").val(result['output']);
      }
      
    }
  });
}

if($(e.target).hasClass('value_4_one')){
  var value = $(e.target).val();
  var id = $("#id_supply").val();
  $.ajax({
    url:"<?php echo URL::to('user/supple_combotwo_update_edit')?>",
    type:"post",
    dataType:"json",
    data:{value:value,id:id,type:4},
    success: function(result) {
      if(result['value'] == 1){
        $(".value_4_output_class").val(result['output']);
        $(".error_4_ouput").hide();
      }
      else{
        $(".error_4_ouput").html(result['message']);
        $(".error_4_ouput").show();
        $(".value_4_output_class").val(result['output']);
      }
      
    }
  });
}

if($(e.target).hasClass('value_4_formula')){
  var value = $(e.target).val();
  var id = $("#id_supply").val();
  $.ajax({
    url:"<?php echo URL::to('user/supple_formula_update_edit')?>",
    type:"post",
    dataType:"json",
    data:{value:value,id:id,type:4},
    success: function(result) {
      if(result['value'] == 1){
        $(".value_4_output_class").val(result['output']);
        $(".error_4_ouput").hide();
      }
      else{
        $(".error_4_ouput").html(result['message']);
        $(".error_4_ouput").show();
        $(".value_4_output_class").val(result['output']);
      }
      
    }
  });
}

if($(e.target).hasClass('value_4_two')){
  var value = $(e.target).val();
  var id = $("#id_supply").val();
  $.ajax({
    url:"<?php echo URL::to('user/supple_combotwo_update_edit')?>",
    type:"post",
    dataType:"json",
    data:{value:value,id:id,type:4},
    success: function(result) {
      if(result['value'] == 1){
        $(".value_4_output_class").val(result['output']);
        $(".error_4_ouput").hide();
      }
      else{
        $(".error_4_ouput").html(result['message']);
        $(".error_4_ouput").show();
        $(".value_4_output_class").val(result['output']);
      }
      
    }
  });
}

if($(e.target).hasClass('value_5_one')){
  var value = $(e.target).val();
  var id = $("#id_supply").val();
  $.ajax({
    url:"<?php echo URL::to('user/supple_comboone_update_edit')?>",
    type:"post",
    dataType:"json",
    data:{value:value,id:id,type:5},
    success: function(result) {
      if(result['value'] == 1){
        $(".value_5_output_class").val(result['output']);
        $(".error_5_ouput").hide();
      }
      else{
        $(".error_5_ouput").html(result['message']);
        $(".error_5_ouput").show();
        $(".value_5_output_class").val(result['output']);
      }
      
    }
  });
}

if($(e.target).hasClass('value_5_formula')){
  var value = $(e.target).val();
  var id = $("#id_supply").val();
  $.ajax({
    url:"<?php echo URL::to('user/supple_formula_update_edit')?>",
    type:"post",
    dataType:"json",
    data:{value:value,id:id,type:5},
    success: function(result) {
      if(result['value'] == 1){
        $(".value_5_output_class").val(result['output']);
        $(".error_5_ouput").hide();
      }
      else{
        $(".error_5_ouput").html(result['message']);
        $(".error_5_ouput").show();
        $(".value_5_output_class").val(result['output']);
      }
      
    }
  });
}

if($(e.target).hasClass('value_5_two')){
  var value = $(e.target).val();
  var id = $("#id_supply").val();
  $.ajax({
    url:"<?php echo URL::to('user/supple_combotwo_update_edit')?>",
    type:"post",
    dataType:"json",
    data:{value:value,id:id,type:5},
    success: function(result) {
      if(result['value'] == 1){
        $(".value_5_output_class").val(result['output']);
        $(".error_5_ouput").hide();
      }
      else{
        $(".error_5_ouput").html(result['message']);
        $(".error_5_ouput").show();
        $(".value_5_output_class").val(result['output']);
      }
      
    }
  });
}


if($(e.target).hasClass('value_6_one')){
  var value = $(e.target).val();
  var id = $("#id_supply").val();
  $.ajax({
    url:"<?php echo URL::to('user/supple_comboone_update_edit')?>",
    type:"post",
    dataType:"json",
    data:{value:value,id:id,type:6},
    success: function(result) {
      if(result['value'] == 1){
        $(".value_6_output_class").val(result['output']);
        $(".error_6_ouput").hide();
      }
      else{
        $(".error_6_ouput").html(result['message']);
        $(".error_6_ouput").show();
        $(".value_6_output_class").val(result['output']);
      }
      
    }
  });
}

if($(e.target).hasClass('value_6_formula')){
  var value = $(e.target).val();
  var id = $("#id_supply").val();
  $.ajax({
    url:"<?php echo URL::to('user/supple_formula_update_edit')?>",
    type:"post",
    dataType:"json",
    data:{value:value,id:id,type:6},
    success: function(result) {
      if(result['value'] == 1){
        $(".value_6_output_class").val(result['output']);
        $(".error_6_ouput").hide();
      }
      else{
        $(".error_6_ouput").html(result['message']);
        $(".error_6_ouput").show();
        $(".value_6_output_class").val(result['output']);
      }
      
    }
  });
}

if($(e.target).hasClass('value_6_two')){
  var value = $(e.target).val();
  var id = $("#id_supply").val();
  $.ajax({
    url:"<?php echo URL::to('user/supple_combotwo_update_edit')?>",
    type:"post",
    dataType:"json",
    data:{value:value,id:id,type:6},
    success: function(result) {
      if(result['value'] == 1){
        $(".value_6_output_class").val(result['output']);
        $(".error_6_ouput").hide();
      }
      else{
        $(".error_6_ouput").html(result['message']);
        $(".error_6_ouput").show();
        $(".value_6_output_class").val(result['output']);
      }
      
    }
  });
}

});

$.ajaxSetup({async:false});
$('#form-validation').validate({
    rules: {
        name : {required: true,},
        description : {required: true,},
        number : {required: true,remote:"<?php echo URL::to('user/supple_number_check')?>"},
        
    },
    messages: {
        name : "Client Name is Required",
        description : "Description is Required",
        number : {
          required : "Number is Required",
          remote : "Number is already exists",
        },
    },
});

$.ajaxSetup({
    async: true
});
function ajax_response(e){
  var valueinput1 = $(".value_1_class").val();
  $(".value_1_output_class").val(valueinput1);

  var valueinput2 = $(".value_2_class").val();
  $(".value_2_output_class").val(valueinput2);

  var value_3_type = $(".value_3_type").val();
  var value_3_one = $(".value_3_one").val();
  var value_3_formula = $(".value_3_formula").val();
  var value_3_two = $(".value_3_two").val();
  if(value_3_type == 2)
  {
    var valueinput3 = $(".value_3_class").val();
    $(".value_3_output_class").val(valueinput3);
  }
  else{
    if(value_3_type == 1)
    {
      if(value_3_one != "" && value_3_two !="" && value_3_formula != "")
      {
        var val_3_one ='';
        var val_3_two ='';
        var ans_3 ='';
        if(value_3_one == 1) { val_3_one = valueinput1; }
        else if(value_3_one == 2) { val_3_one = valueinput2; }

        if(value_3_two == 1) { val_3_two = valueinput1; }
        else if(value_3_two == 2) { val_3_two = valueinput2; }

        if(value_3_formula == 1) { ans_3 = parseInt(val_3_one) + parseInt(val_3_two); }
        else if(value_3_formula == 2) { ans_3 = parseInt(val_3_one) - parseInt(val_3_two); }
        else if(value_3_formula == 3) { ans_3 = parseInt(val_3_one) * parseInt(val_3_two); }
        else if(value_3_formula == 4) { ans_3 = parseInt(val_3_one) / parseInt(val_3_two); }

        $(".value_3_output_class").val(ans_3);
      }
    }

    var value_3_output = $(".value_3_output_class").val();
    if(value_3_output == "")
    {
      valueinput3 = '';
    }
    else{
      valueinput3 = value_3_output;
    }
  }

  var value_4_type = $(".value_4_type").val();
  var value_4_one = $(".value_4_one").val();
  var value_4_formula = $(".value_4_formula").val();
  var value_4_two = $(".value_4_two").val();
  if(value_4_type == 2)
  {
    var valueinput4 = $(".value_4_class").val();
    $(".value_4_output_class").val(valueinput4);
  }
  else{
    if(value_4_type == 1)
    {
      if(value_4_one != "" && value_4_two !="" && value_4_formula != "")
      {
        var val_4_one ='';
        var val_4_two ='';
        var ans_4 ='';
        if(value_4_one == 1) { val_4_one = valueinput1; }
        else if(value_4_one == 2) { val_4_one = valueinput2; }
        else if(value_4_one == 3) { val_4_one = valueinput3; }

        if(value_4_two == 1) { val_4_two = valueinput1; }
        else if(value_4_two == 2) { val_4_two = valueinput2; }
        else if(value_4_two == 3) { val_4_two = valueinput3; }

        if(value_4_formula == 1) { ans_4 = parseInt(val_4_one) + parseInt(val_4_two); }
        else if(value_4_formula == 2) { ans_4 = parseInt(val_4_one) - parseInt(val_4_two); }
        else if(value_4_formula == 3) { ans_4 = parseInt(val_4_one) * parseInt(val_4_two); }
        else if(value_4_formula == 4) { ans_4 = parseInt(val_4_one) / parseInt(val_4_two); }

        $(".value_4_output_class").val(ans_4);
      }
    }
    var value_4_output = $(".value_4_output_class").val();
    if(value_4_output == "")
    {
      valueinput4 = '';
    }
    else{
      valueinput4 = value_4_output;
    }
  }

  var value_5_type = $(".value_5_type").val();
  var value_5_one = $(".value_5_one").val();
  var value_5_formula = $(".value_5_formula").val();
  var value_5_two = $(".value_5_two").val();
  if(value_5_type == 2)
  {
    var valueinput5 = $(".value_5_class").val();
    $(".value_5_output_class").val(valueinput5);
  }
  else{
    if(value_5_type == 1)
    {
      if(value_5_one != "" && value_5_two !="" && value_5_formula != "")
      {
        var val_5_one ='';
        var val_5_two ='';
        var ans_5 ='';
        if(value_5_one == 1) { val_5_one = valueinput1; }
        else if(value_5_one == 2) { val_5_one = valueinput2; }
        else if(value_5_one == 3) { val_5_one = valueinput3; }
        else if(value_5_one == 4) { val_5_one = valueinput4; }

        if(value_5_two == 1) { val_5_two = valueinput1; }
        else if(value_5_two == 2) { val_5_two = valueinput2; }
        else if(value_5_two == 3) { val_5_two = valueinput3; }
        else if(value_5_two == 4) { val_5_two = valueinput4; }

        if(value_5_formula == 1) { ans_5 = parseInt(val_5_one) + parseInt(val_5_two); }
        else if(value_5_formula == 2) { ans_5 = parseInt(val_5_one) - parseInt(val_5_two); }
        else if(value_5_formula == 3) { ans_5 = parseInt(val_5_one) * parseInt(val_5_two); }
        else if(value_5_formula == 4) { ans_5 = parseInt(val_5_one) / parseInt(val_5_two); }

        $(".value_5_output_class").val(ans_5);
      }
    }
    var value_5_output = $(".value_5_output_class").val();
    if(value_5_output == "")
    {
      valueinput5 = '';
    }
    else{
      valueinput5 = value_5_output;
    }
  }

  var value_6_type = $(".value_6_type").val();
  var value_6_one = $(".value_6_one").val();
  var value_6_formula = $(".value_6_formula").val();
  var value_6_two = $(".value_6_two").val();
  if(value_6_type == 2)
  {
    var valueinput6 = $(".value_6_class").val();
    $(".value_6_output_class").val(valueinput6);
  }
  else{
    if(value_6_type == 1)
    {
      if(value_6_one != "" && value_6_two !="" && value_6_formula != "")
      {
        var val_6_one ='';
        var val_6_two ='';
        var ans_6 ='';
        if(value_6_one == 1) { val_6_one = valueinput1; }
        else if(value_6_one == 2) { val_6_one = valueinput2; }
        else if(value_6_one == 3) { val_6_one = valueinput3; }
        else if(value_6_one == 4) { val_6_one = valueinput4; }
        else if(value_6_one == 5) { val_6_one = valueinput5; }

        if(value_6_two == 1) { val_6_two = valueinput1; }
        else if(value_6_two == 2) { val_6_two = valueinput2; }
        else if(value_6_two == 3) { val_6_two = valueinput3; }
        else if(value_6_two == 4) { val_6_two = valueinput4; }
        else if(value_6_two == 5) { val_6_two = valueinput5; }

        if(value_6_formula == 1) { ans_6 = parseInt(val_6_one) + parseInt(val_6_two); }
        else if(value_6_formula == 2) { ans_6 = parseInt(val_6_one) - parseInt(val_6_two); }
        else if(value_6_formula == 3) { ans_6 = parseInt(val_6_one) * parseInt(val_6_two); }
        else if(value_6_formula == 4) { ans_6 = parseInt(val_6_one) / parseInt(val_6_two); }

        $(".value_6_output_class").val(ans_6);
      }
    }
    var value_6_output = $(".value_6_output_class").val();
    if(value_6_output == "")
    {
      valueinput6 = '';
    }
    else{
      valueinput6 = value_6_output;
    }
  }

  var valueinvoice = $(".invoice_class").val();

  var valuedes1 = $(".description_class_1").val();
  var valuedes2 = $(".description_class_2").val();
  var valuedes3 = $(".description_class_3").val();
  var valuedes4 = $(".description_class_4").val();
  var valuedes5 = $(".description_class_5").val();
  var valuedes6 = $(".description_class_6").val();
  var valuetitle = $(".name_class").val();

  var id = $("#id_supply").val();

  $.ajax({
    url:"<?php echo URL::to('user/supple_value_update_edit')?>",
    type:"post",
    data:{valueinput1:valueinput1,valueinput2:valueinput2,valueinput3:valueinput3,valueinput4:valueinput4,valueinput5:valueinput5,valueinput6:valueinput6,valueinvoice:valueinvoice,valuedes1:valuedes1,valuedes2:valuedes2,valuedes3:valuedes3,valuedes4:valuedes4,valuedes5:valuedes5,valuedes6:valuedes6,valuetitle:valuetitle,id:id,value_3_type:value_3_type,value_3_one:value_3_one,value_3_two:value_3_two,value_3_formula:value_3_formula,value_4_type:value_4_type,value_4_one:value_4_one,value_4_two:value_4_two,value_4_formula:value_4_formula,value_5_type:value_5_type,value_5_one:value_5_one,value_5_two:value_5_two,value_5_formula:value_5_formula,value_6_type:value_6_type,value_6_one:value_6_one,value_6_two:value_6_two,value_6_formula:value_6_formula},
    success: function(result) {
    }
  });
  

  setTimeout(ajax_response,1000);
}
setTimeout(ajax_response,1000);
</script>







@stop
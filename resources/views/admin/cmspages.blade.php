@extends('adminheader')
@section('content')
<!-- Content Header (Page header) -->

<section class="content-header">
      <h1>
        Manage CMS
        
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
<!-- Default box -->
      <div class="box">
        
        <div class="box-body">
          <div class="col-lg-1"></div>
                <div class="col-lg-10 pull-center">
                <?php
                if(Session::has('message')){
                ?>
                  <P class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
                <?php }
                ?> 
                    

                    <div class="">
                        <table class="table">
                            <thead>
                                <tr>
                                    <td width="4%">S.No</td>
                                    <td>Page Name</td>
                                    <td width="12%" align="center">Action</td>
                                </tr>
                            </thead>
                            <tbody>
                            <tr>
                              <td>1</td>
                              <td><?php echo $cmsall[0]->page_title; ?></td>
                              <td align="center">
                                  <a href="<?php echo URL::to('admin/about')?>"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>                                  
                              </td>
                            </tr>
                            <tr>
                              <td>2</td>
                              <td><?php echo $cmsall[1]->page_title; ?></td>
                              <td align="center">
                                  <a href="<?php echo URL::to('admin/contact')?>"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>                                  
                              </td>
                            </tr>
                            <tr>
                              <td>3</td>
                              <td><?php echo $cmsall[2]->page_title; ?></td>
                              <td align="center">
                                  <a href="<?php echo URL::to('admin/terms')?>"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>                                  
                              </td>
                            </tr>
                            <tr>
                              <td>4</td>
                              <td><?php echo $cmsall[3]->page_title; ?></td>
                              <td align="center">
                                  <a href="<?php echo URL::to('admin/privacy')?>"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>                                  
                              </td>
                            </tr>
                            </tbody>                            
                        </table>
                    </div>
                </div>

        </div>
        <!-- /.box-body -->
        
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->


<!-- /.content -->
</section>

@stop
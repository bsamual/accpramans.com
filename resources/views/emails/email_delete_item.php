
<div style="float:left;width:100%;margin:0;padding:0;min-height:100%;line-height:150%;font-size:14px;color:#565a5c;background-color:#f7f7f7;">
    <div style="background:#fff; width:85%; overflow:hidden;text-align:left;padding:15px;max-width:600px;margin:30px auto 30px;display:block;">
        <div style="width:94%; height:auto; padding:20px; text-align:center; float:left;">
            <a href="#" style="width:100%; float:left;"><img src="<?php echo $logo; ?>" width="270" height="62" style="width:225px; margin:0px auto;" /></a>
        </div>
        <div style="width:100%; float:left; height:auto; padding:20px;">
                    <div style="width:100%;margin-bottom:15px"> <b><?php echo $salutation; ?></b><br> Item has been deleted successfully. Please see the below details of the item is been deleted. </div>
                    <div>
                        <div style="width:50%;height:auto;float:left">Payment Notification ID:</div>
                        <div style="width:50%;height:auto;float:left">: <?php echo $item_details->reference; ?></div>
                    </div>
                    <div>
                        <div style="width:50%;height:auto;float:left">Sub Tax Ref:</div>
                        <div style="width:50%;height:auto;float:left">: <?php echo $item_details->rctno; ?></div>
                    </div>
                    <div>
                        <div style="width:50%;height:auto;float:left">Sub Name:</div>
                        <div style="width:50%;height:auto;float:left">: <?php echo $item_details->subcontractor; ?></div>
                    </div>
                    <div>
                        <div style="width:50%;height:auto;float:left">Date:</div>
                        <div style="width:50%;height:auto;float:left">: <?php echo $item_details->date; ?></div>
                    </div>
                    <div>
                        <div style="width:50%;height:auto;float:left">Gross Payment:</div>
                        <div style="width:50%;height:auto;float:left">: <?php echo $item_details->gross; ?></div>
                    </div>
                    <div>
                        <div style="width:50%;height:auto;float:left">Net Payment:</div>
                        <div style="width:50%;height:auto;float:left">: <?php echo $item_details->net; ?></div>
                    </div>
                    <div>
                        <div style="width:50%;height:auto;float:left">Deduction Amount</div>
                        <div style="width:50%;height:auto;float:left">: <?php echo $item_details->deduction; ?></div>
                    </div>
                    <div>
                        <div style="width:50%;height:auto;float:left">Client Details:</div>
                        <div style="width:50%;height:auto;float:left">: <?php echo $client_details->firstname.': '.$client_details->taxnumber; ?></div>
                    </div>
                    <div>
                        <div style="width:50%;height:auto;float:left">This request was made on <?php echo date('F d Y h:i A'); ?></div>
                    </div>
                    <div style="width:95%;height:auto;float:left;margin-top:20px">
                        <b>Regards,</b><br>
                        GBSCO & CO Team<br><div class="yj6qo"></div><div class="adL"><br><br>
                        </div>
                    </div>
                    <div class="adL">
                    </div>
           
            
            <div style="width:100%; height:auto; float:left;">
            <b>Regards,</b><br>
            GBSCO & CO Team<br/>
            </div>  
        </div>
    </div>
</div>
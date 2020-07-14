
<div style="float:left;width:100%;margin:0;padding:0;min-height:100%;line-height:150%;font-size:14px;color:#565a5c;background-color:#f7f7f7;">
    <div style="background:#fff; width:85%; overflow:hidden;text-align:left;padding:15px;max-width:600px;margin:30px auto 30px;display:block;">
        <div style="width:94%; height:auto; padding:20px; text-align:center; float:left;">
            <a href="#" style="width:100%; float:left;"><img src="<?php echo $logo; ?>" width="270" height="62" style="width:225px; margin:0px auto;" /></a>
        </div>
        <div style="width:100%; float:left; height:auto; padding:20px;">
            <div style="width:100%; margin-bottom:15px;">
                <?php echo $message; ?><br/><br/>
                <b>This Email has been sent to : </b> <?php echo $sentmails; ?><br/><br/>
                <spam style="color:#f00">Note: Please find the Payroll documents attached (For better view, please download the document and open it).</spam>
            </div>
            
            <div style="width:100%; height:auto; float:left;">
            <b>Regards,</b><br>
            EasyPayroll Team<br/>
            </div>  
        </div>
    </div>
</div>
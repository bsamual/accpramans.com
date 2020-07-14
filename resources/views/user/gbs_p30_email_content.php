<div style="width:100%; float:left; height:auto; padding:20px;">
    <div style="width:100%; margin-bottom:10px;">
        <?php echo $salutation; ?>, <br/>
        <p>Your PAYE/PRSI/USC liability for the period <?php echo $period; ?> is €<?php echo ($liability == "")?'N/A':$liability; ?> 
        <?php 
            if($pay == 1)
            {
                echo 'and will be called from your bank account within 10 days of '.$due_date.'.';
            }else{
                echo 'and is due for submission &amp; payment by '.$due_date.'.';
            }
        ?>
        </p>
        
        <spam style="color:#f00">Note: Please find the payroll documents attached. For better view, please download the document and open it.</spam>
    </div>
    
    <div style="width:100%; height:auto; float:left;">
    <b>Regards,</b><br>
    EasyPayroll Team<br/><br/>
    <b>This Email has been sent to : </b> <?php echo $sentmails; ?>
    </div>  
</div>
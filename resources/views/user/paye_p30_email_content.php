<div style="width:100%; float:left; height:auto; padding:20px;">
    <div style="width:100%; margin-bottom:10px;">
        <?php echo $salutation; ?>, <br/>
        <p> We have processed <u><em>your PAYE return</em></u> for the month of <?php echo $period; ?></p>
        <p> Your employee/directors PAYE liability for <?php echo $period; ?> is €<?php echo $ros_liability; ?> </p>
        <?php 
        if($task_level_id == "7") {
            echo '<p> Your Monthly Direct Debit Will be Called as Normal with any under or over payment being reconciled at a later date.</p>';
        }
        else{
            if($pay == "Yes")
            {
                echo '<p> This liability will be called from your account on 20th of '.$next_period.'.</p>';
            }
            else
            {
                echo '<p> This liability is due for payment by 20th of '.$next_period.'.</p>';
            }
        }
        ?>
        <p>Finally, please give us a call if you are unsure about the figures, dates or any other aspect of your PAYE liability.</p>        
    </div>
    
    <div style="width:100%; height:auto; float:left;">
    <b>Regards,</b><br>
    EasyPayroll Team<br/><br/>
    <b>This Email has been sent to : </b> <?php echo $sentmails; ?>
    </div>  
</div>  



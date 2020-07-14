
<div style="float:left;width:100%;margin:0;padding:0;min-height:100%;line-height:150%;font-size:14px;color:#565a5c;background-color:#f7f7f7;">
    <div style="background:#fff; width:85%; overflow:hidden;text-align:left;padding:15px;max-width:600px;margin:30px auto 30px;display:block;">
        <div style="width:94%; height:auto; padding:20px; text-align:center; float:left;">
            <a href="#" style="width:100%; float:left;"><img src="<?php echo $logo; ?>" style="width:178px; margin:0px auto;" /></a>
        </div>
        <div style="width:100%; float:left; height:auto; padding:20px;">
            <div style="width:94%; margin-bottom:15px;text-align: justify;">
                <?php 
                    if(trim($salutation) == "")
                    {
                        echo 'Hi';
                    } 
                    else{
                        echo $salutation;
                    }
                ?>,<br/><br/>
                Your VAT returns for <b><?php echo $period; ?></b> is due on <b><?php echo date('d/m/Y', strtotime($due_date)); ?></b> <br/>
                <?php 
                if($self_manage == "yes")
                {
                    ?>
                    Please keep in mind your due date and try to get your VAT return submitted on time.  If you are unsure what to do, please give us a call and we will help you out. <br/>
                    <?php
                }
                elseif($self_manage == "no")
                {
                    ?>
                    Please can we get your purchase and sale invoices, bank account statements/extracts, cheque books and other relevant information. <br/> <br/> Please also can you get us this information in good time to meet the date your return is due. <br/>
                    <?php
                }
                ?>
                <br/>
                
            </div>
            <div style="width:94%; height:auto; float:left;">
                <?php
                echo $signature;
                ?>
            </div>
            <div style="width:100%; height:auto; float:left;">
				<b>This Email has been sent to : </b> <?php echo $sentmails; ?><br/>
            </div> 
        </div>
    </div>
</div>
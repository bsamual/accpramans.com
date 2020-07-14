
<div style="float:left;width:100%;margin:0;padding:0;min-height:100%;line-height:150%;font-size:14px;color:#565a5c;background-color:#f7f7f7;">
    <div style="background:#fff; width:85%; overflow:hidden;text-align:left;padding:15px;max-width:600px;margin:30px auto 30px;display:block;">
        <div style="width:94%; height:auto; padding:20px; text-align:center; float:left;">
            <a href="#" style="width:100%; float:left;"><img src="<?php echo $logo; ?>" width="270" height="62" style="width:225px; margin:0px auto;" /></a>
        </div>
        <div style="width:100%; float:left; height:auto; padding:20px;">
            <div style="width:94%; margin-bottom:15px;text-align: justify;">
                Hi <?php echo $username; ?>,<br/> <br/>
                This Task is now Available.<br/><br/>

                <b>Client Name : </b> <?php echo $client_name; ?>,<br/>
                <b>Received Date : </b> <?php echo date('d-M-Y', strtotime($file_details->data_received)); ?>,<br/>
                <b>Added Date : </b> <?php echo date('d-M-Y', strtotime($file_details->date_added)); ?>,<br/>
                <b>Description : </b> <?php echo $file_details->description; ?>,<br/>
                <b>Hard Files : </b> <?php echo($file_details->hard_files == 1)?'YES':'NO'; ?>,<br/>

            </div>
            <div style="width:100%; height:auto; float:left;">
				<b>This Email has been sent to : </b> <?php echo $sentmails; ?><br/><br/>
            </div>
            <div style="width:100%; height:auto; float:left;">
            	<b>Regards,</b><br>
            	EasyPayroll Team<br/>
            </div>  
        </div>
    </div>
</div>
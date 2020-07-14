<?php
    /*
        Do some parameters checks, database data collection, etc. etc. here
    */
    // Force a download dialog on the user's browser:
    //$filepath = 'SCJP Sun Certified Programmer for Java 6 Exam 310-065SCJP Sun Certified Programmer for Java 6 Exam 310-065.pdf';
    $filepath = $_GET['filename'];

//header("Content-Type: application/pdf");


    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
    header('Content-Transfer-Encoding: binary');
    header('Cache-Control: must-revalidate');
    header('Content-Length: '.filesize($filepath));
    //ob_clean();
    //flush();
	try{
		$page = file_get_contents($filepath);
		echo $page;
		header('Set-Cookie: fileDownload=true; path=/');
	}catch(Exception $e){
		header('Set-Cookie: fileDownload=false; path=/');	
	}
    
    exit();
?>


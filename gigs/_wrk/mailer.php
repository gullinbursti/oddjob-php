<?php
// start the output buffer
ob_start();

function sendMail($to_addr, $to_name, $sub, $msg) {
    $headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	//$headers .= 'To: '. $to_name .' <'. $to_addr .'>' . "\r\n";
	$headers .= 'From: Odd Job <redeem@oddjob.com>' . "\r\n";
	//$headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
	//$headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";  
	
	mail($to_addr, $sub, $msg, $headers);	
}


// clear the output buffer
ob_flush();
?>
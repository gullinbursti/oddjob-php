<?php 

function sendPush($d_id, $msg) {
    
	$ch = curl_init();
    
	curl_setopt($ch, CURLOPT_URL, 'https://go.urbanairship.com/api/push/');
	curl_setopt($ch, CURLOPT_USERPWD, "XTOkC5ndSsKDO3Noi6_vOQ:cghD7ulYQPCdYzOlZC1MVQ");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, '{"device_tokens": ["'. $d_id .'"], "aps": {"alert": "'. $msg .'"}}');

	$res = curl_exec($ch);
	$err_no = curl_errno($ch);
	$err_msg = curl_error($ch);
	$header = curl_getinfo($ch);
	curl_close($ch);
	
	//echo ("res:[". $res ."] err_no:[". $err_no ."] err_msg:[". $err_msg ."] header:[". $header ."]");
}

?>
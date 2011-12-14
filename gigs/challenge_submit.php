<?php
// start the output buffer
ob_start();


require "./_consts/db_consts.php";
require "./_consts/fb_consts.php";

require './_inc/fb-sdk/facebook.php';

include "./_inc/db_open.php";

$form_bit = 0x000;

$job_id = 0;
$fb_id = 0;
$type_id = $_POST['selChallengeType'];
$user_arr = array();

if (isset($_POST['hidJobID'])) {
	$job_id = $_POST['hidJobID'];
	$form_bit = $form_bit | 0x001;
	
	if (isset($_POST['hidFBID'])) {
		$fb_id = $_POST['hidFBID'];
		$form_bit = $form_bit | 0x010;
	
		if (isset($_POST['hidUsers'])) {
			$user_arr = explode("|", $_POST['hidUsers']);
			$form_bit = $form_bit | 0x100; 

		}
	}
}

if ($form_bit = 0x111) {
	
	$query = 'INSERT INTO `tblChallenges` (';
	$query .= '`id`, `type_id`, `job_id`, `fb_id`, `added`) ';
	$query .= 'VALUES (NULL, "'. $type_id .'", "'. $job_id .'", "'. $fb_id .'", CURRENT_TIMESTAMP);';
	$result = mysql_query($query);
	$challenge_id = mysql_insert_id();
	
	if ($challenge_id) {
		foreach ($user_arr as $key=>$val) {
			echo ("\n [".$key."]".$val);
			
			$query = 'INSERT INTO `tblJobChallengers` (';
			$query .= '`challenge_id`, `fb_id`) ';
			$query .= 'VALUES ("'. $challenge_id .'", "'. $val .'");';
			$result = mysql_query($query);
		}
	}
	
	header('Location: challenge.php?jID='. $job_id .'&cID='. $challenge_id .'&a=true');
	
} else
	header('Location: challenge.php?jID='. $job_id);
 

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta http-equiv="Content-language" value="en" />
		
		<title>Submit Job</title>
		<style type="text/css" rel="stylesheet" media="screen">
			html, body {margin:0px; padding:0px; font-family: 'Open Sans', sans-serif; background-color:#ffffff;}
			#divSubmitting {font-size:12px; font-weight:600;}
		</style>		
	</head>
	
	<body><div id="divSubmitting">Submitting…</div></body>
</html>

<?php

require "./_inc/db_close.php";

// clear the output buffer
ob_flush();
?>
<?php
// start the output buffer
ob_start();


require "./_consts/db_consts.php";
require "./_consts/fb_consts.php";

require './_inc/fb-sdk/facebook.php';

include "./_inc/db_open.php";

$form_bit = 0x0000;

$job_id = 0;
$fb_id = 0;
$job_rate = -1;
$job_txt = "";

if (isset($_POST['hidJobID'])) {
	$job_id = $_POST['hidJobID'];
	$form_bit = $form_bit | 0x0001;
	
	if (isset($_POST['hidFBID'])) {
		$fb_id = $_POST['hidFBID'];
		$form_bit = $form_bit | 0x0010;
	
		if (isset($_POST['hidJobRating']) && $_POST['hidJobRating'] != -1) {
			$job_rate = $_POST['hidJobRating'];
			$form_bit = $form_bit | 0x0100;
		
			if (isset($_POST['txtComment'])) {
				$job_txt = $_POST['txtComment'];
				$form_bit = $form_bit | 0x1000;
			}
		}
	}
}

if ($form_bit = 0x1111) {
	
	$query = 'INSERT INTO `tblJobRatings` (';
	$query .= '`id`, `job_id`, `fb_id`, `score`, `comment`, `added`) ';
	$query .= 'VALUES (NULL, "'. $job_id .'", "'. $fb_id .'", "'. $job_rate .'", "'. $job_txt .'", CURRENT_TIMESTAMP);';
	$result = mysql_query($query);
	$rate_id = mysql_insert_id();
	
	
	header('Location: recommend.php?jID='. $job_id .'&a=true');
	
} else
	header('Location: recommend.php?jID='. $job_id);
 

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
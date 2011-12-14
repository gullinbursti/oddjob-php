<?php

// start the output buffer
ob_start();


include "./db_open.php";

$user_id = $_POST['userID'];
$job_id = $_POST['jobID'];
$status_id = $_POST['statusID'];

$query = 'INSERT INTO `tblUsersJobs` (';
$query .= '`id`, `user_id`, `job_id`, `status_id`, `added`) ';
$query .= 'VALUES (NULL, "'. $user_id .'", "'. $job_id .'", "'. $status_id .'", CURRENT_TIMESTAMP);';
$result = mysql_query($query);


$query = 'SELECT `slots` FROM `tblJobs` WHERE `id` = "'. $job_id .'"';
$result = mysql_query($query);

if ($result) {
	
	$row = mysql_fetch_row(mysql_query($query));
	$slots_tot = $row[0];
	
	if ($status_id == "4" || $status_id == "6") {
		$slots_tot--;
	
	} else if ($status_id == "5") {
		$slots_tot++;
	}
	
	$query = 'UPDATE `tblJobs` SET `slots` ='. $slots_tot .' WHERE `id` ='. $job_id .';';
	$result = mysql_query($query);
}
	

include "./db_close.php";

echo "['". $user_id ."']['". $job_id ."']['". $status_id ."']";
header('Location: confirm.php');

// clear the output buffer
ob_flush();

?>
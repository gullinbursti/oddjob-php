<?php
// start the output buffer
ob_start();


//http://dev.gullinbursti.cc/projs/oddjob/posts/budweiser.htm
//me/oddjobb:install 


define(APP_ID, "139514356142393");
define(APP_SECRET, "b5c9eb235ba09cd7ad58ca99770dca55");


$NA_DATE = '0000-00-00 00:00:00';
$fb_id = '660042243';
$app_id = '139514356142393';
$app_secret = 'b5c9eb235ba09cd7ad58ca99770dca55';
$canvas_url = "http://dev.gullinbursti.cc/projs/oddjob/harness";
//$auth_url = "https://www.facebook.com/dialog/oauth?client_id=". $app_id ."&redirect_uri=". urlencode($canvas_url) ."&scope=read_stream,publish_stream,publish_actions,read_friendlists,share_item,user_location,user_work_history";
$auth_url = "https://www.facebook.com/dialog/oauth?client_id=". $app_id ."&redirect_uri=". urlencode($canvas_url) ."&scope=read_stream,publish_stream,publish_actions,read_friendlists,share_item,user_location,user_work_history";
$app_url = "http://apps.facebook.com/oddjobb/";

$fb_id = 0;
$locality_id = 0;
$job_id = 0;
$job_long = 0;
$job_lat = 0; 

include "./db_open.php";

function sqlTime() {
	$ts_result = mysql_query("SELECT NOW();") or die("Couldn't get the date from MySQL");
	$row = mysql_fetch_row($ts_result);
	return($row[0]); 
}

$jobID_arr = explode("|", $_POST['hidIDs']);

for ($i=0; $i<count($jobID_arr); $i++) {
	$job_id = $jobID_arr[$i];
	
	$query = 'SELECT `image_id` FROM `tblJobsImages` WHERE `job_id` = "'. $job_id .'";';
	$imgID_arr = mysql_query($query);
	
	while ($img_row = mysql_fetch_array($imgID_arr, MYSQL_BOTH)) {
		$img_id = $img_row['image_id'];
		
		$query = 'DELETE FROM `tblImages` WHERE `id` = "'. $img_id .'";';
		$result = mysql_query($query);
		
		echo ("job_id:[". $job_id ."] img_id:[". $img_id ."]");
	}
	
	$query = 'DELETE FROM `tblJobsImages` WHERE `job_id` = "'. $job_id .'";';
	$result = mysql_query($query);
	
	$query = 'SELECT `object_id`, `app_id` FROM `tblJobs` WHERE `id` = "'. $job_id .'";';
	$job_row = mysql_fetch_row(mysql_query($query));
	
	$query = 'DELETE FROM `tblObjects` WHERE `id` = "'. $job_row[0] .'";';
	$result = mysql_query($query);

	$query = 'DELETE FROM `tblApps` WHERE `id` = "'. $job_row[1] .';';
	$app_row = mysql_fetch_row(mysql_query($query));
    
	$query = 'DELETE FROM `tblJobs` WHERE `id` = "'. $job_id .'";';
	$result = mysql_query($query);
	
	header('Location: index.php');
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" />

<?php

// clear the output buffer
ob_flush();
?>
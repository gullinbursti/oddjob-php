<?php

// start the output buffer
ob_start();


include "./db_open.php";

$job_id = $_POST['hidID'];
$type_id = $_POST['selType'];
$title_str = $_POST['txtTitle'];
$info_str = $_POST['txtInfo'];
$terms_str = $_POST['txtTerms'];
$longitude = $_POST['txtLongitude'];
$latitude = $_POST['txtLatitude'];
$age_min = $_POST['txtAgeMin'];
$age_max = $_POST['txtAgeMax'];
$friends_req = $_POST['txtFriends'];
$sex_str = $_POST['radSex'];
$edu_id = $_POST['radEdu'];
$app_id = $_POST['hidAppID'];
$app_name = $_POST['txtAppName'];
$obj_id = $_POST['hidObjID'];
$obj_disp = $_POST['txtObjDisp'];
$obj_name = strtolower(str_replace(" ", "_", $obj_disp));
$slots_tot = $_POST['txtSlots'];
$expire_date = $_POST['txtExpires'];
$isActive = "N";

if ($_POST['chkActive'] == "Y")
	$isActive = "Y";

$query = 'UPDATE `tblObjects` SET ';
$query .= '`name` = "'. $obj_name .'", ';
$query .= '`display` = "'. $obj_disp .'", ';
$query .= '`active` = "'. $isActive .'" ';
$query .= 'WHERE `id` = "'. $obj_id .'";';
$obj_res = mysql_query($query);

$query = 'UPDATE `tblApps` SET ';
$query .= '`name` = "'. $app_name .'" ';
$query .= 'WHERE `id` = "'. $app_id .'";';
$app_res = mysql_query($query);

$query = 'UPDATE `tblJobs` SET ';
$query .= '`type_id` = "'. $type_id .'", ';
$query .= '`title` = "'. $title_str .'", ';
$query .= '`info` = "'. $info_str .'", ';
$query .= '`terms` = "'. $terms_str .'", ';
$query .= '`object_id` = "'. $obj_id .'", ';
$query .= '`app_id` = "'. $app_id .'", ';
$query .= '`longitude` = "'. $longitude .'", ';
$query .= '`latitude` = "'. $latitude .'", ';
$query .= '`age_min` = "'. $age_min .'", ';
$query .= '`age_max` = "'. $age_max .'", ';
$query .= '`friends` = "'. $friends_req .'", ';
$query .= '`sex` = "'. $sex_str .'", ';
$query .= '`edu_id` = "'. $edu_id .'", ';
$query .= '`slots` = "'. $slots_tot .'", ';
$query .= '`expires` = "'. $expire_date .'", ';
$query .= '`isActive` = "'. $isActive .'" ';  
$query .= 'WHERE `id` = "'. $job_id .'";';
$job_res = mysql_query($query);
	 
include "./db_close.php";

//echo "['". $user_id ."']['". $job_id ."']['". $status_id ."']";
header('Location: index.php');

// clear the output buffer
ob_flush();

?>               
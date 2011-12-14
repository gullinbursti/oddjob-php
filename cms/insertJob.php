<?php

// start the output buffer
ob_start();


include "./db_open.php";

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
$app_name = $_POST['txtAppName'];
$obj_disp = $_POST['txtObjDisp'];
$obj_name = strtolower(str_replace(" ", "_", $obj_disp));
$slots_tot = $_POST['txtSlots'];
$expire_date = $_POST['txtExpires'];
$isActive = "N";

if ($_POST['chkActive'] == "Y")
	$isActive = "Y";

//$query = 'SELECT `id` FROM `tblObjects` WHERE `name` = "'. $obj_name .'";';
//$obj_row = mysql_fetch_row(mysql_query($query));

//if (!$obj_row) {	
	$query = 'INSERT INTO `tblObjects` (';
	$query .= '`id`, `name`, `display`, `info`, `url`, `active`, `added`, `modified`) ';
	$query .= 'VALUES (NULL, "'. $obj_name .'", "'. $obj_disp .'", "", "", "'. $isActive .'", NOW(), CURRENT_TIMESTAMP);';
	$result = mysql_query($query);
	$obj_id = mysql_insert_id();

//} else
//	$obj_id = $obj_row[0];	

//$query = 'SELECT `id` FROM `tblApps` WHERE `name` = "'. $app_name .'";';
//$app_row = mysql_fetch_row(mysql_query($query));

//if (!$app_row) {
	$query = 'INSERT INTO `tblApps` (';
	$query .= '`id`, `name`, `info`, `itunes_id`, `itunes_name`, `added`, `modified`) ';
	$query .= 'VALUES (NULL, "'. $app_name .'", "", "0", "", NOW(), CURRENT_TIMESTAMP);';
	$result = mysql_query($query);
	$app_id = mysql_insert_id();
	
//} else
//	$app_id = $app_row[0];	


$query = 'INSERT INTO `tblJobs` (';
$query .= '`id`, `type_id`, `title`, `info`, `terms`, `supplier_id`, `object_id`, `app_id`, `amount`, `longitude`, `latitude`, `age_min`, `age_max`, `sex`, `friends`, `pts`, `pts_req`, `slots`, `edu_id`, `isActive`, `added`, `expires`, `modified`) ';
$query .= 'VALUES (NULL, "'. $type_id .'", "'. $title_str .'", "'. $info_str .'", "'. $terms_str .'", "0", "'. $obj_id .'", "'. $app_id .'", "0", "'. $longitude .'", "'. $latitude .'", "'. $age_min .'", "'. $age_max .'", "'. $sex_str .'", "'. $friends_req .'",  "0", "0", "'. $slots_tot .'", "'. $edu_id .'", "'. $isActive .'", NOW(), "'. $expire_date .'", CURRENT_TIMESTAMP);';
$result = mysql_query($query);
  
include "./db_close.php";

//echo "['". $user_id ."']['". $job_id ."']['". $status_id ."']";
header('Location: index.php');

// clear the output buffer
ob_flush();

?>               
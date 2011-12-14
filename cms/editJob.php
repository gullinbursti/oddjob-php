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

$job_id = $_GET['jID'];

$query = 'SELECT `id`, `name` FROM `tblJobTypes`;';
$type_arr = mysql_query($query);

$query = 'SELECT * FROM `tblJobs` WHERE `id` = '. $job_id .';';
$job_row = mysql_fetch_row(mysql_query($query));

$query = 'SELECT `name`, `display` FROM `tblObjects` WHERE `id` = '. $job_row[6] .';';
$obj_row = mysql_fetch_row(mysql_query($query));

$query = 'SELECT `name` FROM `tblApps` WHERE `id` = '. $job_row[7] .';';
$app_row = mysql_fetch_row(mysql_query($query));

$query = 'SELECT `tblImages`.`id`, `tblImages`.`type_id`, `tblImages`.`url` FROM `tblImages` INNER JOIN `tblJobsImages` ON `tblImages`.`id` = `tblJobsImages`.`image_id` WHERE `tblJobsImages`.`job_id` = "'. $job_row[0] .'" ORDER BY `tblJobsImages`.`sort`;';
$img_arr = mysql_query($query);

while ($img_row = mysql_fetch_array($img_arr, MYSQL_BOTH)) {
	switch ($img_row['type_id']) {
		case "1":
			$appIco_url = $img_row['url'];
			break;
			
		case "2":
			$appImg_url = $img_row['url'];
			break;
			
	    case "4":
		  	$fbIco_url = $img_row['url'];
		    break;
	}
	
	/*array_push($img_arr, array(
		"id" => $img_row['id'], 
		"url" => $img_row['url']
	));*/
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta http-equiv="Content-language" value="en" />
		
		<title>Add Job</title>
		<style>
			html, body {margin:4px; padding:4px; font-family:'Open Sans', sans-serif; background-color:#ffffff;}
			#divWrapper {background-image:url('./images/background.jpg'); background-repeat:no-repeat; background-attachment:fixed; background-position:bottom right;}
			#tblMainWrapper {width:100%; height:100%;}
			
			#tdHeaderTout {width:100%; height:13px; width:100%; text-align:right; padding-top:8px; padding-right:11px; color:#0176ec; font-size:12px; font-weight:600;}
			#tdHeaderTout a, #tdHeaderTout a:active, #tdHeaderTout a:visited {color:#a4a4a4; text-decoration:none;} 
			#tdHeaderTout a:hover {color:#a4a4a4; text-decoration:underline;} 
			
			#tdJobList {width:620px; height:100%; vertical-align:top; padding-left:18px;}
			#divJobsList {width:100%; height:100%;}
			.tblJobItem_Open {width:610px; height:130px; background-image:url('./images/bg_jobRow.png'); background-repeat:no-repeat;}/* padding-right:20px;} */
			.tblJobItem_Taken {width:610px; height:130px; background-image:url('./images/bg_jobRow.png'); background-repeat:no-repeat;}/* padding-right:20px;} */
			
			.imgIco {position:relative; left:60px; bottom:60px;}
			.imgLocationPin {padding-left:15px;}
			.tdJobTitle {font-size:24px; font-weight:600; color:#8c8c8c; vertical-align:middle; padding-top:10px;}
			.tdJobRankAmt {font-size:14px; font-weight:600; color:#0176ec; text-align:center; padding-top:4px; vertical-align:top;}
			.btnTakeJob {width:158px; height:54px; border:none; background-image:url('./images/btn_takeJob.png'); background-repeat:no-repeat;}
			
			#tdJobImg {text-align:middle; vertical-align:middle; padding-left:20px;}
			#tdJobDetails {width:605px; height:570px; vertical-align:top; background-image:url('./images/backplateBg.png'); background-repeat:no-repeat;}
			#tdJobMap {padding-left:20px; padding-top:20px;}
			#tdJobInfo {color:#6e6e6e; vertical-align:top; padding-top:40px; padding-left:30px;}
			#tblJobStats {width:605px; height:130px; vertical-align:top;}/* padding-right:20px;} */
			#divRulesHeader {font-size:26px; font-weight:600; color:#0176ec; padding-bottom:10px;}
			#divJobLocation {color:#666666; font-weight:600; font-size:12px; padding-bottom:25px;}
			.tdJobBtn {text-align:center; vertical-align:middle; padding:30px;}
			.tdJobBtns {padding-top:30px; padding-bottom:30px;}
			/*.btnInstallLarge {font-size:20px; color:#ffffff; width:449px; height:62px; border:none; background-image:url('./images/btnLargeInstall_normal.png'); background-repeat:no-repeat;}*/
			.btnInstallLarge {font-size:20px; color:#ffffff; width:449px; height:69px; border:none; background-image:url('./images/btnInstallLG_normal.jpg'); background-repeat:no-repeat;}
			.btnInstallSmall {width:269px; height:62px; border:none; background-image:url('./images/btnSmallInstall_normal.png'); background-repeat:no-repeat;}
			.btnSendLarge {width:449px; height:62px; border:none; background-image:url('./images/btnLargeSend_normal.png'); background-repeat:no-repeat;}
			.btnSendSmall {width:269px; height:62px; border:none; background-image:url('./images/btnSmallSend_normal.png'); background-repeat:no-repeat;}
			#tdJobTerms {font-size:10px; font-weight:600; color:#b3b3b3; text-align:justify; padding:20px; padding-top:0px;}
			#divExpires {font-size:11px; font-weight:700; color:#f9101e; padding-top:15px;}
			#tdUsersLbl {font-size:14px; font-weight:600; color:#2277e7; padding-top:10px; padding-left:20px;}
			#tdAvatars {padding-top:15px; padding-left:20px; padding-bottom:28px;}
			.imgAvatar {padding-right:16px;}
			#tdDesignElement {width:414px; vertical-align:top; color:#666666; padding-left:20px;}
			#tdLogo {width:100%; padding-top:75px;}
			#tdTagline {width:100%; padding-top:20px;}
			#tdCTA {width:100%; padding-top:20px;}
		</style>
		
		<script type="text/javascript">
		<!--
			function updateDetails() {
				document.frmJobDetails.submit();
			}
			
			function cancel() {
				location.href = "index.php";
			}
		-->
		</script>
	</head>
	
	<body>
    	<form id="frmJobDetails" name="frmJobDetails" action="updateJob.php" method="post"><div id="divWrapper" height="100%">
		<table cellspacing="0" cellpadding="0" border="0">
			<tr><td>ID:</td><td><input type="text" id="txtID" name="txtID" value="<?php echo ($job_row[0]) ?>" size="4" disabled /></td></tr>
			<tr><td>Title*:</td><td><input type="text" id="txtTitle" name="txtTitle" value="<?php echo ($job_row[2]) ?>" size="64" /></td></tr>
			<tr><td colspan="2"><hr /></td></tr>
			<tr><td>Type:</td><td>
				<select id="selType" name="selType"><option>Select…</option>
				<?php while ($type_row = mysql_fetch_array($type_arr, MYSQL_BOTH)) {
					if ($job_row[1] == $type_row['id'])
						$option_html = "<option value=\"". $type_row['id'] ."\" selected>". $type_row['name'] ."</option>";
					else
						$option_html = "<option value=\"". $type_row['id'] ."\">". $type_row['name'] ."</option>";
					
				  echo ($option_html);  
				} ?>
				</select>
			</td></tr>
			<tr><td colspan="2"><hr /></td></tr>
			<tr><td>Description:</td><td><textarea id="txtInfo" name="txtInfo" width="96" rows="5"><?php echo ($job_row[3]) ?></textarea></td></tr>
			<tr><td colspan="2"><hr /></td></tr>
			<tr><td>Terms &amp; Conditions:</td><td><textarea id="txtTerms" name="txtTerms" width="96" rows="5"><?php echo ($job_row[4]) ?></textarea></td></tr>
			<tr><td colspan="2"><hr /></td></tr>
			<tr><td>Longitude &amp; Latitude:</td><td><input type="text" id="txtLongitude" name="txtLongitude" value="<?php echo ($job_row[9]) ?>" size="8" /><input type="text" id="txtLatitude" name="txtLatitude" value="<?php echo ($job_row[10]) ?>" size="8" /></td></tr>
			<tr><td colspan="2"><hr /></td></tr>
			<tr><td>Age Min / Max:</td><td><input type="text" id="txtAgeMin" name="txtAgeMin" value="<?php echo ($job_row[11]) ?>" size="3" /><input type="text" id="txtAgeMax" name="txtAgeMax" value="<?php echo ($job_row[12]) ?>" size="3" /></td></tr>
			<tr><td colspan="2"><hr /></td></tr>
		   	<tr><td>Friends Min:</td><td><input type="text" id="txtFriends" name="txtFriends" value="<?php echo ($job_row[14]) ?>" size="4" /></td></tr>
			<tr><td colspan="2"><hr /></td></tr>
			<tr><td>App Name*:</td><td><input type="text" id="txtAppName" name="txtAppName" value="<?php echo ($app_row[0]) ?>" size="32" /></td></tr>
			<tr><td colspan="2"><hr /></td></tr>
			<tr><td>Sex:</td><td><?php
				$sex_arr = array(
					"N" => "Any", 
					"M" => "Male",
					"F" => "Female");
					
				foreach ($sex_arr as $key => $val) {
					echo ("<input type=\"radio\" id=\"radSex\" name=\"radSex\" value=\"". $key ."\" ");					
					
					if ($key == $job_row[13])
						echo ("checked />". $val ."<br />");
					else
					    echo ("/>". $val ."<br />");
				}
			?></td></tr>
			<tr><td colspan="2"><hr /></td></tr>
			<tr><td>Education Level:</td><td><?php 
				$edu_arr = array(
					"0" => "Any", 
					"1" => "High School", 
					"2" => "College");
				
				foreach ($edu_arr as $key => $val) {
					echo ("<input type=\"radio\" id=\"radEdu\" name=\"radEdu\" value=\"0\" ");
					
					if ($key == $job_row[13])
						echo ("checked />". $val ."<br />");
					else
					    echo ("/>". $val ."<br />");
				}
			?></td></tr>
			<tr><td colspan="2"><hr /></td></tr>
			<tr><td>Slots:</td><td><input type="text" id="txtSlots" name="txtSlots" value="<?php echo ($job_row[17]) ?>" size="4" /></td></tr>
			<tr><td colspan="2"><hr /></td></tr>
			<tr><td>Object Display*:</td><td><input type="text" id="txtObjDisp" name="txtObjDisp" value="<?php echo ($obj_row[1]) ?>" size="16" /><input type="text" id="txtObjName" name="txtObjName" value="<?php echo ($obj_row[0]) ?>" size="16" disabled /></td></tr>
			<tr><td colspan="2"><hr /></td></tr>
			<tr><td>Expires:</td><td><input type="text" id="txtExpires" name="txtExpires" value="<?php echo ($job_row[21]); ?>" size="32" /></td></tr>
			<tr><td colspan="2"><hr /></td></tr>
			<tr><td>Active:</td><td><?php
				if ($job_row[19] == "Y")
					echo ("<input type=\"checkbox\" id=\"chkActive\" name=\"chkActive\" value=\"Y\" checked />");
				else
				    echo ("<input type=\"checkbox\" id=\"chkActive\" name=\"chkActive\" value=\"Y\" />");
			?></td></tr>
			<tr><td colspan="2"><hr /></td></tr>
			<tr><td colspan="2"><input type="button" id="btnSubmit" name="btnSubmit" value="Submit" onclick="updateDetails();" /><input type="button" id="btnCancel" name="btnCancel" value="Cancel" onclick="cancel();" /></td></tr>
		</table>
		<input type="hidden" id="hidID" name="hidID" value="<?php echo ($job_id); ?>">
		<input type="hidden" id="hidObjID" name="hidObjID" value="<?php echo ($job_row[6]); ?>">
		<input type="hidden" id="hidAppID" name="hidAppID" value="<?php echo ($job_row[7]); ?>">
	</form>
	<hr />
	<a href="http://apps.facebook.com/oddjobb/?jID=<?php echo ($job_id); ?>" target="_blank">http://apps.facebook.com/oddjobb/?jID=<?php echo ($job_id); ?></a>
	<hr />

	<form name="frmAppHeader" action="uploadImage.php?tID=2" enctype="multipart/form-data" method="post">
		<table cellspacing="0" cellspadding="0" border="0">
			<tr><td colspan="2">App Header</td></tr>
			<tr><td><img src="<?php echo ($appImg_url); ?>" width="600" height="140" /></td></tr>
			<tr><td><input type="file" id="filImage" name="filImage" /></td></tr>
			<tr><td><input type="submit" id="btnSubmit" name="btnSubmit" value="Upload" /></td></tr>
			<tr><td><hr /></td></tr>
		</table>
		<input type="hidden" id="hidID" name="hidID" value="<?php echo ($job_id); ?>">
	</form> 
	
	<form name="frmAppIcon" action="uploadImage.php?tID=1" enctype="multipart/form-data" method="post">
		<table cellspacing="0" cellspadding="0" border="0">
			<tr><td>App Icon</td></tr>
			<tr><td><img src="<?php echo ($appIco_url); ?>" width="88" height="88" /></td></tr>
			<tr><td><input type="file" id="filImage" name="filImage" /></td></tr>
			<tr><td><input type="submit" id="btnSubmit" name="btnSubmit" value="Upload" /></td></tr>
			<tr><td><hr /></td></tr>
		</table>
		<input type="hidden" id="hidID" name="hidID" value="<?php echo ($job_id); ?>">
	</form>
	
	<form name="frmFacebookIcon" action="uploadImage.php?tID=4" enctype="multipart/form-data" method="post">
		<table cellspacing="0" cellspadding="0" border="0">
			<tr><td colspan="2">Facebook Image</td></tr>
			<tr><td><img src="<?php echo ($fbIco_url); ?>" width="88" height="88" /></td></tr>
			<tr><td><input type="file" id="filImage" name="filImage" /></td></tr>
			<tr><td><input type="submit" id="btnSubmit" name="btnSubmit" value="Upload" /></td></tr>
			<tr><td><hr /></td></tr>
		</table>
		<input type="hidden" id="hidID" name="hidID" value="<?php echo ($job_id); ?>">
	</form>
	</div>
	</body>
</html>

<?php

// clear the output buffer
ob_flush();
?>
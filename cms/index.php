<?php

// start the session engine
session_start(); 


// start the output buffer
ob_start();


//http://dev.gullinbursti.cc/projs/oddjob/posts/budweiser.htm
//me/oddjobb:install 

// login isn't set, redirect
if (!isset($_SESSION['login']))
	header('Location: ./login.php');
	

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


include './Users.php';
include './Jobs.php';

require './php-sdk/src/facebook.php';

function sqlTime() {
	$ts_result = mysql_query("SELECT NOW();") or die("Couldn't get the date from MySQL");
	$row = mysql_fetch_row($ts_result);
	return($row[0]); 
}


			
$jobs = new Jobs;
$users = new Users; 


$job_id = 0;

$job_arr = $jobs->allJobs();
$job_tot = 0;
while ($job_row = mysql_fetch_array($job_arr, MYSQL_BOTH))
	$job_tot++;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta http-equiv="Content-language" value="en" />
		
		<title>Harness</title>
		
		<style>
			html, body {margin:4px; padding:4px; font-family: 'Open Sans', sans-serif; background-color:#ffffff;}
			#divWrapper {background-image:url('./images/background.jpg'); background-repeat:no-repeat; background-attachment:fixed; background-position:bottom right;}
			#tblMainWrapper {width:100%; height:100%;}
			
			.spnActive {color:#000000;}
			.spnNonactive {color:#cccccc;}
			
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
			function removeJobs() {
				var tot = <?php echo ($job_tot); ?>;
				var jobIDs = "";
				var cnt = 0;
				
				for (var i=0; i<tot; i++) {
					var chkbox = document.getElementById('chkDeleteJob_'+i);
					
					if (chkbox.checked) {
						var job_id = chkbox.name.substring(13);
						
						if (cnt > 0)
							jobIDs += "|";
						
						jobIDs += job_id;
						cnt++;
					}
				}
				
				if (cnt > 0) {
					if (cnt == 1)
						var prompt = confirm("Delete this 1 job?");
					
					else
					    var prompt = confirm("Delete these "+cnt+" jobs?");
					
					if (prompt) {
						document.frmOddjob.action = "./deleteJobs.php";
						document.frmOddjob.hidIDs.value = jobIDs;
						document.frmOddjob.submit();
					}
				}
			}
			
			function newJob() {
				location.href = "addJob.php";
			}
			
			function editJob(jID) {
				location.href = "editJob.php?jID=" + jID;
			}
			
			function openGraph(jID) {
				paramWindow = window.open("openGraph.php?jID="+jID, "paramWindow", "location=0,status=0,toolbar=0,scrollbars=0,resizable=0,directories=0,width=640,height=200");
				testwindow.moveTo(0, 0);

			}
			
			function logout() {
				location.href = "logout.php";
			}  
		-->
		</script>
	</head>
	
	<body onload="initialize()" onunload="GUnload()">
	<form id="frmOddjob" name="frmOddjob" action="./" method="post"><div id="divWrapper" height="100%">
		<table id="tblMainWrapper" cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr><td colspan="4" align="right"><a href="./logout.php">Logout</a></td></tr>
			<tr><td colspan="4"><hr /></td></tr>
			<tr><td>Remove</td><td>Job Title</td><td>Facebook Page</td><td>Modify</td></tr>
			<tr><td colspan="4"><hr /></td></tr>
			<tr><td colspan="4"><hr /></td></tr>
			<?php
			
			$cnt = 0;
			$job_arr = $jobs->allJobs(); 
			while ($job_row = mysql_fetch_array($job_arr, MYSQL_BOTH)) { 
				$query = 'SELECT `tblImages`.`id`, `tblImages`.`type_id`, `tblImages`.`url` FROM `tblImages` INNER JOIN `tblJobsImages` ON `tblImages`.`id` = `tblJobsImages`.`image_id` WHERE `tblJobsImages`.`job_id` = "'. $job_row['id'] .'" ORDER BY `tblJobsImages`.`sort`;';
				$img_res = mysql_query($query);

				while ($img_row = mysql_fetch_array($img_res, MYSQL_BOTH)) {

					switch ($img_row['type_id']) {
						case "4":
							$img_url = $img_row['url'];
							break;

						case "5":
							$ico_url = $img_row['url'];
							break;
					}
				}
				
				if ($job_row['isActive'] == "Y")
					$txtColor_html = "spnActive";
				else
					$txtColor_html = "spnNonactive";
				
				echo ("<tr><td class=\"\"><input type=\"checkbox\" id=\"chkDeleteJob_". $cnt ."\" name=\"chkDeleteJob_". $job_row['id'] ."\" /></td><td class=\"". $txtColor_html ."\">". $job_row['title'] ."</td><td class=\"\"><input type=\"button\" id=\"btnOpenGraph_". $job_row['id'] ."\" name=\"btnOpenGraph_". $job_row['id'] ."\" value=\"OpenGraph Params\" onclick=\"openGraph(". $job_row['id'] .")\" />&nbsp;<a href=\"http://apps.facebook.com/oddjobb/?jID=". $job_row['id'] ."\" target=\"_blank.\">App Page</a></td><td class=\"\"><input type=\"button\" id=\"btnEditJob_". $job_row['id'] ."\" name==\"btnEditJob_". $job_row['id'] ."\" value=\"Edit\" onclick=\"editJob(". $job_row['id'] .")\" /></td></tr>");
				echo ("<tr><td colspan=\"4\"><hr /></td></tr>");
				
				$cnt++;
			}
			
			?>
			<tr><td class=""><input type="button" id="btnRemoveJobs" name="btnRemoveJobs" value="Delete" onclick="removeJobs();" /></td><td class="" colspan="3"><input type="button" id="btnNewJob" name="btnNewJob" value="Add New Job" onclick="newJob();" /></td></tr>
		</table>
		<input type="hidden" id="hidIDs" name="hidIDs" value="" />
	</div></form>
	</body>
</html>

<?php

// clear the output buffer
ob_flush();
?>
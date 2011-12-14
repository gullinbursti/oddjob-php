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


include './Users.php';
include './Jobs.php';

require './php-sdk/src/facebook.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => APP_ID,
  'secret' => APP_SECRET,
));

// user id
$user = $facebook->getUser();


if ($user) {
	try {
		$user_profile = $facebook->api('/me');
		//echo ("user_profile:[". print_r($user_profile) ."]");
	
	} catch (FacebookApiException $e) {
		error_log($e);
		$user = null;
	}
}

// login / logout url will be needed depending on current user state.
if ($user) {
	$logoutUrl = $facebook->getLogoutUrl();

} else {
	$loginUrl = $facebook->getLoginUrl();
	header("Location: ". $loginUrl);
}
			
$jobs = new Jobs;
$users = new Users;

function apiResult($res) {
	
	echo ($res);
	/*if (!$res || $res.error)
		alert('Error occurred ['+response.error.message+']');
	  else
		alert('Post was successful! Action ID: ' + response.id);*/
}  

function sqlTime() {
	$ts_result = mysql_query("SELECT NOW();") or die("Couldn't get the date from MySQL");
	$row = mysql_fetch_row($ts_result);
	return($row[0]); 
}

function initUser() {
	$signed_req = $_REQUEST['signed_request'];
	list($encoded_sig, $payload) = explode('.', $signed_req, 2);  
	$fb_res = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
    
    //if (empty($fb_res['user_id']))
	 //   echo("<script>top.location.href='". $loginUrl ."'</script>");

	//else
	//	echo ("Welcome User: ". $fb_res['user_id']);
    
	return ($fb_res);   
}  

function feedPost($id, $msg) {
	//$ogurl = "http://dev.gullinbursti.cc/projs/oddjob/posts/budweiser.htm";
	$token_url = "https://graph.facebook.com/oauth/access_token"; 
	$token_params = "grant_type=client_credentials&client_id=". APP_ID ."&client_secret=". APP_SECRET;
	$access_token = file_get_contents($token_url ."?". $token_params);

	$fbog_host = "https://graph.facebook.com/feed";
	$fbog_params = "?". $access_token ."&message=". urlencode($msg) ."&id=". $id ."&method=post";
	$fbog_url = $fbog_host . $fbog_params;
	
	$post_id = file_get_contents($fbog_url);

	// output the post id
	//echo ("post_id:[". $post_id ."]");
}

function actionJobPost($fb, $j_id, $action, $object) {
	
	//echo ("j_id:[". $j_id ."] action:[". $action ."] object:[". $object ."]");
	
	$ret_obj = $fb->api('/me/'. 'oddjobb' .':'. $action, 'post', array(
			$object => 'http://apps.facebook.com/oddjobb/?jID='. $j_id,));
	 
	//echo ("post_id:[".$ret_obj['id'] ."]");
	
	//$ret_obj = $facebook->api('/me/'. 'oddjobb' .':complete', 'post', array(
		//    'fab' => 'http://apps.facebook.com/oddjobb/?jID=6',));
		//echo $ret_obj['id']; 
}

$job_id = 0;

if (!isset($_SESSION['login']['id'])) {
	$user_arr = initUser();
	$fb_id = $user_arr['user_id'];
	$_SESSION['login']['id'] = $fb_id;

} else 
	$fb_id = $_SESSION['login']['id'];
	
//$oath_token = $user_arr['oauth_token'];

//feedPost($fb_id, "DERP! @ .[". sqlTime() ."] _(http://www.derp.com)_");
if (isset($_GET['jID'])) {
	$job_id = $_GET['jID'];
	
	$query = 'SELECT `title`, `info`, `slots`, `object_id`, `longitude`, `latitude`, `app_id`, `type_id` FROM `tblJobs` WHERE `id` ='. $job_id .';';
	$job_row = mysql_fetch_row(mysql_query($query));
	$job_name = $job_row[0];
	$job_info = $job_row[1];
	$slots_tot = $job_row[2];
	$job_long = $job_row[4];
	$job_lat = $job_row[5];
	
	//echo ("job_id:[". $job_id ."] job_name:[". $job_name ."] job_info:[". $job_info0 ."] slots_tot:[". $slots_tot ."]");
    $query = 'SELECT `name` FROM `tblJobTypes` WHERE `id` ='. $job_row[7] .';';
	$type_row = mysql_fetch_row(mysql_query($query));
	$type_name = $type_row[0];							
								
	$query = 'SELECT `name` FROM `tblObjects` WHERE `id` ='. $job_row[3] .';';
	$obj_row = mysql_fetch_row(mysql_query($query));
	$obj_name = $obj_row[0]; 
	
	$query = 'SELECT `name` FROM `tblApps` WHERE `id` ='. $job_row[6] .';';
	$app_row = mysql_fetch_row(mysql_query($query));
	$app_name = $app_row[0]; 
    
	$query = 'SELECT `tblImages`.`id`, `tblImages`.`url` FROM `tblImages` INNER JOIN `tblJobsImages` ON `tblImages`.`id` = `tblJobsImages`.`image_id` WHERE `tblJobsImages`.`job_id` = "'. $job_id .'" AND type_id = "4";';
	$img_row = mysql_fetch_row(mysql_query($query));
    $img_url = $img_row[1];
}



if (isset($_POST['actionID'])) {
 	$job_id = $_POST['jobID'];
	$user_id = $_POST['userID'];   
	$status_id = $_POST['statusID'];
	
	//echo ("job_id:[". $job_id ."]");
	
	if ($_POST['actionID'] == "1") {
		$query = 'INSERT INTO `tblUsersJobs` (';
		$query .= '`id`, `user_id`, `job_id`, `status_id`, `added`) ';
		$query .= 'VALUES (NULL, "'. $user_id .'", "'. $job_id .'", "'. $status_id .'", CURRENT_TIMESTAMP);';
		$result = mysql_query($query);


		$query = 'SELECT `title`, `info`, `slots`, `object_id`, `longitude`, `latitude`, `type_id` FROM `tblJobs` WHERE `id` = "'. $job_id .'"';
		$result = mysql_query($query);

		if ($result) {

			$job_row = mysql_fetch_row(mysql_query($query));
			$job_name = $job_row[0];
			$job_info = $job_row[1];
			$slots_tot = $job_row[2];
			$job_long = $job_row[4];
			$job_lat = $job_row[5];

			if ($status_id == "4" || $status_id == "6")
				$slots_tot++;//--;

			else if ($status_id == "5")
				$slots_tot++;

			$query = 'UPDATE `tblJobs` SET `slots` ='. $slots_tot .' WHERE `id` ='. $job_id .';';
			$result = mysql_query($query);
		}
	
		$query = 'SELECT `name` FROM `tblObjects` WHERE `id` ='. $job_row[3] .';';
		$obj_row = mysql_fetch_row(mysql_query($query));
		$obj_name = $obj_row[0];
    
		$query = 'SELECT `tblImages`.`id`, `tblImages`.`url` FROM `tblImages` INNER JOIN `tblJobsImages` ON `tblImages`.`id` = `tblJobsImages`.`image_id` WHERE `tblJobsImages`.`job_id` = "'. $job_id .'" AND `tblImages`.`type_id` = "4";';
		$img_row = mysql_fetch_row(mysql_query($query));
    	$img_url = $img_row[1];

		$query = 'SELECT `name` FROM `tblJobTypes` WHERE `id` = "'. $job_row[6] .'";';
		$type_row = mysql_fetch_row(mysql_query($query));
		
		actionJobPost($facebook, $job_id, strtolower($type_row[0]), $obj_name);
	
	} else if ($_POST['actionID'] == "2" || $_POST['actionID'] == "3") {
		$query = 'SELECT `title`, `info`, `slots`, `object_id`, `longitude`, `latitude` FROM `tblJobs` WHERE `id` = "'. $job_id .'"';
		$result = mysql_query($query);
		$job_row = mysql_fetch_row(mysql_query($query));
		$job_name = $job_row[0];
		$job_info = $job_row[1];
		$slots_tot = $job_row[2];
		$job_long = $job_row[4];
	  	$job_lat = $job_row[5];
			
		$query = 'SELECT `name` FROM `tblObjects` WHERE `id` ='. $job_row[3] .';';
		$obj_row = mysql_fetch_row(mysql_query($query));
		$obj_name = $obj_row[0];
    
		$query = 'SELECT `tblImages`.`id`, `tblImages`.`url` FROM `tblImages` INNER JOIN `tblJobsImages` ON `tblImages`.`id` = `tblJobsImages`.`image_id` WHERE `tblJobsImages`.`job_id` = "'. $job_id .'" AND `tblImages`.`type_id` = "4";';
		$img_row = mysql_fetch_row(mysql_query($query));
    	$img_url = $img_row[1];

		$query = 'SELECT `name` FROM `tblJobTypes` WHERE `id` = "'. $job_row[6] .'";';
		$type_row = mysql_fetch_row(mysql_query($query));
	}
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# oddjobb: http://ogp.me/ns/fb/oddjobb#">
	    <meta property="fb:app_id"      content="139514356142393"> 
	    <meta property="og:type"        content="oddjobb:<?php echo ($obj_name)?>"> 
	    <meta property="og:url"         content="http://apps.facebook.com/oddjobb/?jID=<?php echo ($job_id)?>"> 
	    <meta property="og:title"       content="<?php echo ($job_name)?>"> 
	    <meta property="og:description" content="<?php echo ($job_info)?>"> 
	    <meta property="og:image"       content="<?php echo ($img_url)?>">
	    <meta property="og:locale"      content="en_us">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta http-equiv="Content-language" value="en" />
		
		<title>Harness</title>
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>
		
		<style>
			html, body {margin:0px; padding:0px; font-family: 'Open Sans', sans-serif; background-color:#ffffff;}
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
			
			#map_canvas {border:1px solid #b6b6b6;}
			
		</style>
		<!-- // Note: you will need to replace the sensor parameter below with either an explicit true or false value. -->
  		<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=true&amp;key=ABQIAAAAkqf4B_FJfAqQS595FGmCCBTLLWk40wlS7BEwh-ZVRoA3zpfA0RSKwQZEohuUndCyUuC02jd4QGUFTg" type="text/javascript"></script>
		
		<script type="text/javascript">
		<!--
		    
		function performJob(jobID) {
			var userID = <?php echo ($fb_id); ?>; 
			var statusID = 6;
				
			//alert (userID + "][" + statusID);
				
			document.frmOddjob.userID.value = userID;
			document.frmOddjob.jobID.value = jobID;
			document.frmOddjob.statusID.value = statusID;
			document.frmOddjob.actionID.value = "1";
				
			document.frmOddjob.submit();
		}
		
		function infoJob(jobID) {
			var userID = <?php echo ($fb_id); ?>; 
			var statusID = 6;
				
			//alert (userID + "][" + statusID);
				
			document.frmOddjob.userID.value = userID;
			document.frmOddjob.jobID.value = jobID;
			document.frmOddjob.statusID.value = statusID;
			document.frmOddjob.actionID.value = "2";
			document.frmOddjob.submit();
		}
		
		function installJob(jobID) {
			var userID = <?php echo ($fb_id); ?>; 
			var statusID = 6;
				
			//alert (userID + "][" + statusID);
				
			document.frmOddjob.userID.value = userID;
			document.frmOddjob.jobID.value = jobID;
			document.frmOddjob.statusID.value = statusID;
			document.frmOddjob.actionID.value = "3";
				
			document.frmOddjob.submit();
		} 
		
		
		 function sendInstruct(jobID) {
			var userID = <?php echo ($fb_id); ?>; 
			alert ("This feature is coming soon");
			//location.href = "mailto:Odd Job - Instructions";
		}	
		
				
		-->
		</script>
	</head>
	
	<body onload="initialize()" onunload="GUnload()">
	<form id="frmOddjob" name="frmOddjob" action="./" method="post"><div id="divWrapper" height="100%">
		<table id="tblMainWrapper" cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr><td id="tdHeaderTout" colspan="2">Palo Alto, CA</td></tr>
			<tr>
				<td id="tdJobList">
					<div id="divJobsList">
						<table cellspacing="0" cellpadding="0" border="0" width="100%"><?php
							
							if ($job_id == 0) {
							
								$job_arr = $jobs->allJobs();
							
								while ($job_row = mysql_fetch_array($job_arr, MYSQL_BOTH)) {
									$score = 0;
			
									if ($fb_id > 0) {
										$query = 'SELECT `locality_id`, `age`, `sex`, `friends`, `edu_id` FROM `tblUsers` WHERE `fb_id` = "'. $fb_id .'";';
										$user_row = mysql_fetch_row(mysql_query($query));
				
										if ($user_row[1] >= $job_row['age_min'])
											$score++;
					
										else
											$score = -666;
					
										if ($job_row['sex'] == "N" || $job_row['sex'] == $user_row[2])
											$score++;
									}
			
									$query = 'SELECT * FROM `tblJobsLocalities` WHERE `job_id` ='. $job_row['id'] .';';
									$result = mysql_query($query);
									$isFound = false;
			
									while ($locality_row = mysql_fetch_array($result, MYSQL_BOTH)) {
				
										if ($fb_id > 0) {
											if ($locality_row['locality_id'] == $user_row[0])
												$isFound = true;//$score++;
										}
									}
			
									if ($isFound)
										$score++;
				
									else
										$score = -666;
				
				
									if ($user_row[3] >= $job_row['friends'])
										$score++;

									else
										$score = -666;  
				
				
									if ($user_row[4] >= $job_row['edu_id'])
										$score++;

									else
										$score = -666;
			                    
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
				
									$query = 'SELECT `name` FROM `tblObjects` WHERE `id` ='. $job_row['object_id'] .';';
								    $obj_row = mysql_fetch_row(mysql_query($query));
									$obj_name = $obj_row[0];  
				
				                   
									if ($score > 0) {
										$pre_html = "<td width='100%'><table cellspacing='0' cellspacing='0' border='0' width='100%' class='tblJobItem_Open'><tr>";
										$img_html = "<td rowspan='2' width='167'><img src='". $img_url ."' width='150' height='125' alt='". $obj_name ."' /></td>";
										$title_html = "<td class='tdJobTitle'>". $job_row['title'] ."</td></tr>";
										$score_html = "<tr><td><table cellspacing='0' cellspacing='0' border='0' width='100%'><tr><td class='tdJobRankAmt' width='69'>". (($score / 5) * 100) ."%</td><td width='13' /><td class='tdJobRankAmt' width='69'>.8</td><td width='13' /><td class='tdJobRankAmt' width='69'>". $job_row['slots'] ."</td><td width='12' />";
										$btn_html = "<td><input type='button' class='btnTakeJob' id='btnJob_". $job_row['id'] ."' name='btnJob_". $job_row['id'] ."' value='' onclick='infoJob(". $job_row['id'] .");' /></td></tr></table></td></tr>";
										$post_html = "</td></tr></table></td>";
										echo ("<tr>". $pre_html . $img_html . $title_html . $score_html . $btn_html . $post_html ."</tr>");
									}
								
									echo ("<tr><td height='21' /></tr>");
								}
							} else {
								
								$fbID_arr = array(
									"660042243", 
									"100001070614958", 
									"1554917948", 
									"100000936690098", 
									"1390251585", 
									"1234648027", 
									"684145355",
									"777590228",
								);
								
								//.echo ("actionID:[". $_POST['actionID'] ."]"); 
								
								
								$score = 5;
								$pre_html = "<tr><td id='tdJobDetails'><table cellspacing='0' cellspacing='0' border='0' width='100%'>";
								
								$stats1_html = "<tr><td><table cellspacing='0' cellspacing='0' border='0' width='100%' id='tblJobStats'><tr>";
								$img_html = "<td id='tdJobImg' width='105'><img style='background:url(". $img_url .");' src='./images/appCover.png' width='87' height='85' alt='". $obj_name ."' /></td>";
								$title_html = "<td class='tdJobTitle'>". $job_name ."</td></tr>";
								$score_html = "";//"<tr><td><table cellspacing='0' cellspacing='0' border='0' width='100%'><tr><td class='tdJobRankAmt' width='69'>". (($score / 5) * 100) ."%</td><td width='13' /><td class='tdJobRankAmt' width='69'>.8</td><td width='13' /><td class='tdJobRankAmt' width='69'>". $slots_tot ."</td><td width='12' />";
								//$stats2_html = "</td></tr></table>";
								$stats2_html = "</table>";
								
								$details1_html = "<tr><td><table cellspacing='0' cellspacing='0' border='0' width='585'><tr>";
								$map_html = "<td id='tdJobMap'><div id='map_canvas' style='width:215px; height:204px'></div></td>";
								
								$name_arr = explode(" ", $user_profile['name']);
								
								switch ($_POST['actionID']) {
									case "1":
										$btn_html = "<tr><td class='tdJobBtns' colspan='2'><table width='100%' cellspacing='0' cellpadding='0' border='0'><tr><td width='20' /><td width='269'><input type='button' class='btnInstallSmall' id='btnInstall_". $job_id ."' name='btnInstall_". $job_id ."' value='' onclick='installJob(". $job_id .");' /></td><td width='269'><input type='button' class='btnSendSmall' id='btnSendJob_". $job_id ."' name='btnSendJob_". $job_id ."' value='' onclick='sendInstruct(". $job_id .");' /></td></tr></table></td></tr>";//"<td height='54' /></tr></table>"; 
										$summary_html = "<td id='tdJobInfo'><div id='divRulesHeader'>Almost there, ". $name_arr[0] ."!</div><div id='divJobLocation'><img src='./images/grayLocation.png' width='9' height='15' alt='Location Pin' style='padding-right:8px;' />Palo Alto, CA</div>". $job_info ."</td></tr>";
										break;
									
									case "3":
										$btn_html = "<tr><td class='tdJobBtn' colspan='2'><input type='button' class='btnSendLarge' id='btnSend_". $job_id ."' name='btnSend_". $job_id ."' value='' onclick='sendInstruct(". $job_id .");' /></td></tr>";//"<td height='54' /></tr></table>"; 
										$summary_html = "<td id='tdJobInfo'><div id='divRulesHeader'>Congrats, ". $name_arr[0] ."!</div><div id='divJobLocation'><img src='./images/grayLocation.png' width='9' height='15' alt='Location Pin' style='padding-right:8px;' />Palo Alto, CA</div>". $job_info ."</td></tr>";
										break;
										
									default:
										$btn_html = "<tr><td class=\"tdJobBtn\" colspan=\"2\"><input type=\"button\" id=\"btnInstall_\"". $job_id ."\" name=\"btnInstall_\"". $job_id ."\" value=\"Install ". $app_name ."\" onclick=\"performJob(". $job_id .");\" /></td></tr>";//"<td><input type='button' class='btnTakeJob' id='btnJob_". $job_id ."' name='btnJob_". $job_id ."' value='' onclick='performJob(". $job_id .");' /></td></tr></table>";
										$summary_html = "<td id='tdJobInfo'><div id='divRulesHeader'>". $type_name ." ". $app_name ."</div><div id='divJobLocation'><img src='./images/grayLocation.png' width='9' height='15' alt='Location Pin' style='padding-right:8px;' />Palo Alto, CA</div>". $job_info ."</td></tr>";
										break;
								}
								
								
								
									
								$terms_html = "<tr><td id='tdJobTerms' colspan='2'>Consectetuer adipiscing elit sed diam nonummy nibh euismod tincidunt ut laoreet; dolore magna aliquam. Et iusto odio dignissim qui, blandit praesent luptatum zzril delenit augue duis dolore. Quod ii legunt saepius claritas est etiam processus dynamicus qui.<div id='divExpires'>DEAL EXPIRES 11/05/2011</div></td></tr></table>";
								$details2_html = "</td></tr>";
								
								$users1_html = "<tr><td><table cellspacing='0' cellspacing='0' border='0' width='100%'>";
								$peeps_html = "<tr><td id='tdUsersLbl'>". count($fbID_arr) ." friends have taken this odd job should consider…</td></tr>";
								$avatars_html = "<tr><td id='tdAvatars'>";
								
								for ($i=0; $i<8; $i++)
									$avatars_html .= "<a href='http://facebook.com/profile.php?id=". $fbID_arr[$i] ."' target='_blank'><img style='padding-right:18px; background:url(http://graph.facebook.com/". $fbID_arr[$i] ."/picture); background-repeat:no-repeat;' src='./images/avatarCover.png' width='53' height='53' border='0' alt='USER ".$i."' />";
								
								$avatars_html .= "</td></tr>";
								$users2_html = "</table></td></tr>";
								
								$post_html = "</table></td></tr>";  
								
								echo ($pre_html);
								echo ($stats1_html . $img_html . $title_html . $score_html . $stats2_html);
							   	echo ($details1_html . $map_html . $summary_html . $btn_html . $terms_html . $details2_html);
								echo ($users1_html . $peeps_html . $avatars_html . $users2_html);
								echo ($post_html);
								
							}
							//<tr><td class='tdJobItem' width='100%'><img width='100%' height='64' src='#' alt="{JOB_ITEM}" /></td></tr>
							
						?></table>
					</div>
				</td>
				<td id="tdDesignElement"><table cellspacing="0" cellpadding="0" border="0" width="100%">
					<tr><td id="tdLogo"><img src="./images/logo.png" width="173" height="46" alt="Odd Job" /></td></tr>
					<tr><td id="tdTagline">Earn free goods, services, cash, and coupons for taking digital jobs :)</td></tr>
					<tr><td id="tdCTA"><a href="#" target="_blank"><img src="./images/appStore.png" width="129" height="43" alt="Odd Job on the App Store" border="0" /></a></td></tr>
				</table></td>
			</tr>
		</table>
		<input type="hidden" id="userID" name="userID" value="0" />
		<input type="hidden" id="jobID" name="jobID" value="0" />
		<input type="hidden" id="statusID" name="statusID" value="0" />
		<input type="hidden" id="actionID" name="actionID" value="0" />
	</div></form>
	<script type="text/javascript">
		
		var point = new GLatLng(<?php echo ($job_lat); ?>, <?php echo ($job_long); ?>)
	
		var map = new GMap2(document.getElementById("map_canvas"));
			//map.setCenter(new GLatLng(37.4419, -122.1419), 13);
			map.setCenter(point, 13);
  			map.setUIToDefault();
		
    		map.addOverlay(new GMarker(point));
            //map.openInfoWindow(point, document.createTextNode(<?php echo($job_name); ?>));


		window.fbAsyncInit = function() {
			FB.Canvas.setAutoGrow();
		}
	</script>	
	</body>
</html>

<?php

// clear the output buffer
ob_flush();



/*
$pre_html = "<td width='100%'><table cellspacing='0' cellspacing='0' border='0' width='100%' class='tblJobItem_Taken'><tr>";
$img_html = "<td><img src='". $img_url ."' width='137' height='124' alt='". $obj_name ."' /><span class='imgIco'><img src='". $ico_url ."' width='0' height='0' alt='". $obj_name ."' /></span></td>";
$title_html = "<td width='28' /><td valign='top'><table cellspacing='0' cellpadding='0' border='1'><tr><td colspan='3' height='20' /></tr><tr><td colspan='3'><span class='spnJobTitle'>". $job_row['title'] ."</span></td></tr>";
$score_html = "<tr><td colspan='3' height='10' /></tr><tr><td>". (($score / 5) * 100) ."%</td><td>.8</td><td>". $job_row['slots'] ."</td></tr></table></td>";
$btn_html = "<td width='6' /><td width='158' />";
									$post_html = "<td width='20' /></tr></table></td>";
									echo ("<tr>". $pre_html . $img_html . $title_html . $score_html . $btn_html . $post_html ."</tr>"); 
*/
?>
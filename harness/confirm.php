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
$auth_url = "https://www.facebook.com/dialog/oauth?client_id=". $app_id ."&redirect_uri=". urlencode($canvas_url) ."&scope=read_stream,publish_stream,publish_actions,read_friendlists,share_item,user_location,user_work_history";

$fb_id = 0;
$locality_id = 0;
$job_id = 0; 


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
if ($user)
	$logoutUrl = $facebook->getLogoutUrl();

else
	$loginUrl = $facebook->getLoginUrl();


//$facebook->api('/me/oddjobb:complete&heineken_app=http://dev.gullinbursti.cc/projs/oddjob/posts/budweiser.htm', 'post', apiResult($res));
			
$jobs = new Jobs;
$users = new Users;

function apiResult($res) {
	
	echo ($res);
	/*if (!$res || $res.error)
		alert('Error occurred ['+response.error.message+']');
	  else
		alert('Post was successful! Action ID: ' + response.id);*/
}  

if (isset($_GET['fbID']))
	$fb_id = $_GET['fbID'];

if (isset($_GET['lID']))
	$locality_id = $_GET['lID'];
	
if (isset($_GET['jID']))
	$job_id = $_GET['jID'];


function sqlTime() {
	 $ts_result = mysql_query("SELECT NOW();") or die("Couldn't get the date from MySQL");
	$row = mysql_fetch_row($ts_result);
	return($row[0]); 
}


function initUser() {
	$signed_req = $_REQUEST['signed_request'];
	list($encoded_sig, $payload) = explode('.', $signed_req, 2);  
	$fb_res = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);

	if (empty($fb_res['user_id']))
		echo("<script>top.location.href='". $auth_url ."'</script>");

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


//$user_arr = initUser();
//$fb_id = $user_arr['user_id'];
//$oath_token = $user_arr['oauth_token'];

//feedPost($fb_id, "DERP! @ .[". sqlTime() ."] _(http://www.derp.com)_");


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta http-equiv="Content-language" value="en" />
		
		<title>Harness</title>
		
		<style>
			html, body {margin:0px; padding:0px;}
			.tdJobID {width:32px; border:1px solid #666666;}
			.tdJobName {width:320px; border:1px solid #666666;}
			.tdJobScore {width:32px; border:1px solid #666666;}
			
			.tdUserAge {width:48px; border:1px solid #666666;}
			.tdUserSex {width:48px; border:1px solid #666666;}
			.tdUserFriends {width:48px; border:1px solid #666666;}
			.tdUserLocality {width:128px; border:1px solid #666666;}
			
			.spanJobActive {background-color:#666666;}
			
			#tblMainWrapper {width:100%; height:100%;}
			#tdAdTout {width:192px; height:100%; background-color:#999999;}
			#tdHeaderTout {width:100%; height:96px; background-color:#666666;}
			#tdJobList {width:100%; height:100%; background-color:#cccccc;}
			#divJobsList {width:100%; height:100%; overflow:auto;}
			.tblJobItem {width:100%; height:64px; border-bottom:1px solid #999999; padding:4px;}
			
		</style>
		
		<script type="text/javascript">
		<!--
		
			function performJob(jobID) {
				var userID = <?php echo ($fb_id); ?>; 
				var statusID = 6;
				
				alert (userID + "][" + statusID);
				
				document.frmOddjob.userID.value = userID;
				document.frmOddjob.jobID.value = jobID;
				document.frmOddjob.statusID.value = statusID;
				
				document.frmOddjob.submit();
			}
				
		-->
		</script>
	</head>
	
	<body><form id="frmOddjob" name="frmOddjob" action="update.php" method="post">
		<table id="tblMainWrapper" cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td id="tdAdTout" rowspan="2" width="192">
					<img width="192" height="600" src="#" alt="{AD_SPACE}" />
				</td>
				<td id="tdHeaderTout" width="100%">
					<img width="100%" height="96" src="#" alt="{HEADER}" />
				</td>
			</tr>
			<tr>
				<td id="tdJobList" valign="top" width="100%">
					JOB CONFIRMED!
				</td>
			</tr>
		</table>
		<input type="hidden" id="userID" name="userID" value="0" />
		<input type="hidden" id="jobID" name="jobID" value="0" />
		<input type="hidden" id="statusID" name="statusID" value="0" />
	</form></body>
</html>

<?php

// clear the output buffer
ob_flush();

?>
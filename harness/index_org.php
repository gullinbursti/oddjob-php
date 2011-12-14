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


$user_arr = initUser();
$fb_id = $user_arr['user_id'];
//$oath_token = $user_arr['oauth_token'];

feedPost($fb_id, "DERP! @ .[". sqlTime() ."] _(http://www.derp.com)_");


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta http-equiv="Content-language" value="en" />
		
		<title>Harness</title>
		
		<style>
			.tdJobID {width:32px; border:1px solid #666666;}
			.tdJobName {width:320px; border:1px solid #666666;}
			.tdJobScore {width:32px; border:1px solid #666666;}
			
			.tdUserAge {width:48px; border:1px solid #666666;}
			.tdUserSex {width:48px; border:1px solid #666666;}
			.tdUserFriends {width:48px; border:1px solid #666666;}
			.tdUserLocality {width:128px; border:1px solid #666666;}
			
			.spanJobActive {background-color:#666666;}
		</style>
		
		<script type="text/javascript">
		<!--
			
			function changeLocality() {
				var localityID = document.getElementById('selLocalities').value;
				
				//alert ("localityID:["+localityID+"]");
				location.href = "index.php?lID="+localityID;
			}
			
			
			function changeUser() {
				var fbID = document.getElementById('selUsers').value;
				
				//alert(userID);
				location.href = "index.php?fbID="+fbID;
			}
			
			
			function updateJob() {
				
				var indUser = document.getElementById('selUsers').selectedIndex;
				var indJob = document.getElementById('selJobs').selectedIndex;
				
				var userID = document.getElementById('selUsers').value;
				var jobID = document.getElementById('selJobs').value;
				var statusID;
				
				
				if (document.frmOddjob.radStatus[0].checked)
					statusID = document.frmOddjob.radStatus[0].value;
					
				if (document.frmOddjob.radStatus[1].checked)
					statusID = document.frmOddjob.radStatus[1].value;
					
				if (document.frmOddjob.radStatus[2].checked)
					statusID = document.frmOddjob.radStatus[2].value;
					
				if (document.frmOddjob.radStatus[3].checked)
					statusID = document.frmOddjob.radStatus[3].value;
					
				if (document.frmOddjob.radStatus[4].checked)
					statusID = document.frmOddjob.radStatus[4].value;

				document.frmOddjob.userID.value = userID;
				document.frmOddjob.jobID.value = jobID;
				document.frmOddjob.statusID.value = statusID;
				
				//alert ("statusID:["+statusID+"]");
				
				document.frmOddjob.submit();
			}
			
			function performJob(jobID) {
				var userID = document.getElementById('selUsers').value; 
				var statusID = 6;
				
				document.frmOddjob.userID.value = userID;
				document.frmOddjob.jobID.value = jobID;
				document.frmOddjob.statusID.value = statusID;
				
				document.frmOddjob.submit();
			}
				
		-->
		</script>
	</head>
	
	<body><form id="frmOddjob" name="frmOddjob" action="update.php" method="post">
		
		<div id="divLocalities">
			Localities:
			<select id="selLocalities" onchange="changeLocality();">
				<option value='0'>ALL</option><?php
				$query = 'SELECT `id`, `name` FROM `tblLocalities`;';
				$result = mysql_query($query);
				$cnt = 0;
				
				while ($row = mysql_fetch_array($result, MYSQL_BOTH)) {
					$opt_html = "<option value='". $row['id'] ."'";
					
					if ($row['id'] == $locality_id)
						$opt_html .= " selected";
					
					$opt_html .= ">". $row['name'] ."</option>";
					echo ($opt_html);	
					$cnt++;
				}
			?></select>
		</div>
		<hr />
		<div id="divUsers">
			Users: 
			<select id="selUsers" onchange="changeUser();"><option value="0">SELECT</option><?php
				
				if ($locality_id == 0)
					$user_arr = $users->allUsers();
				
				else
					$user_arr = $users->usersByLocalityID($locality_id);
					
				
				while ($row = mysql_fetch_array($user_arr, MYSQL_BOTH)) {
					$opt_html = "<option value='". $row['fbid'] ."'";
					
					if ($row['fbid'] == $fb_id)
						$opt_html .= " selected";
						
					$opt_html .= ">". $row['fName'] ." ". $row['lName'] ."</option>";	
					echo ($opt_html);		
				}
			?></select>
			<?php 
				if ($fb_id > 0) {
					echo ("<table><tr><td rowspan='2'><img src='". "http://graph.facebook.com/". $fb_id ."/picture" ."' /></td>");
					
					
					/*$ch = curl_init("http://graph.facebook.com/". $fb_id);

					curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_exec($ch);
					curl_close($ch);
					
					echo $ch;
					*/
					$query = 'SELECT `tblUsers`.`age`, `tblUsers`.`sex`, `tblUsers`.`friends`, `tblLocalities`.`name` FROM `tblUsers` INNER JOIN `tblLocalities` ON `tblUsers`.`locality_id` = `tblLocalities`.`id` WHERE `fbid` = "'. $fb_id .'";';
					$user_row = mysql_fetch_row(mysql_query($query));
						
					echo ("<td class='tdUserAge'>AGE</td><td class='tdUserSex'>SEX</td><td class='tdUserSex'>FRIENDS</td><td class='tdUserLocality'>LOCALITY</td>");
					echo ("<tr><td class='tdUserAge'>". $user_row[0] ."</td><td class='tdUserSex'>". $user_row[1] ."</td><td class='tdUserFriends'>". $user_row[2] ."</td><td class='tdUserLocality'>". $user_row[3] ."</td></tr>");
					echo ("</table>");
				}
			?>
		</div>
		<hr />
		<!-- ><div id="divJobs">
			Jobs:
			<select id="selJobs"><?php /*
			
				if ($locality_id == 0)
					$arr = $jobs->allJobs();
					
				else
					$arr = $jobs->jobsByLocalityID($locality_id);
					
				
				while ($row = mysql_fetch_array($arr, MYSQL_BOTH)) {
					echo "<option value='". $row['id'] ."'>". "[". $row['id'] ."] ". $row['title'] ." - "."</option>";	
				}
				
				unset($arr);
			*/?></select>
		</div>
		
		<div id="divStatus">
			
			Status:
			<?php /*
				$query = 'SELECT `id`, `name` FROM `tblJobStatusTypes`;';
				$result = mysql_query($query);
				$cnt = 0;
				
				while ($row = mysql_fetch_array($result, MYSQL_BOTH)) {
					
					if ($cnt > 2)
						echo "  <input type='radio' name='radStatus' value='". $row['id'] ."' />". $row['name'];
					
					$cnt++;
				}
			*/?>
		</div>
		<hr />
		<input type="button" id="btnUpdate" name="btnUpdate" value="UPDATE" onclick="updateJob();">
	<hr /><hr />-->
	
	<div id="divJobsList">
		<table><?php
		$job_arr = $jobs->allJobs();
		
		while ($job_row = mysql_fetch_array($job_arr, MYSQL_BOTH)) {
			$score = 0;
			
			if ($fb_id > 0) {
				$query = 'SELECT `locality_id`, `age`, `sex`, `friends`, `edu_id` FROM `tblUsers` WHERE `fbid` = "'. $fb_id .'";';
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
			
			if ($score > 0)
				echo ("<tr><td class='tdJobID'>". $job_row['id'] ."</td><td class='tdJobName'>". $job_row['title'] ."</td><td class='tdJobScore'>". $score ."</td><td class='tdPerformJob'><input type='button' id='btnJob_". $job_row['id'] ."' name='btnJob_". $job_row['id'] ."' value='Perform' onclick='performJob(". $job_row['id'] .");' /></td></tr>\n");
		}
		
		unset($job_arr);
		?></table>
		<input type="hidden" id="userID" name="userID" value="0" />
		<input type="hidden" id="jobID" name="jobID" value="0" />
		<input type="hidden" id="statusID" name="statusID" value="0" />
		</form>
	</div>
	</body>
</html>

<?php

// clear the output buffer
ob_flush();

?>
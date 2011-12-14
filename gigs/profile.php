<?php ob_start();

require "./_consts/db_consts.php";
require "./_consts/fb_consts.php";

require './_inc/fb-sdk/facebook.php';
require './_wrk/mailer.php';

require "./_inc/db_open.php";


// init job id
$job_id = 0;

//$auth_url = "https://graph.facebook.com/oauth/authorize?client_id=". $APP_ID ."&redirect_uri=http://dev.gullinbursti.cc/projs/oddjob/gigs&scope=email,read_stream,publish_stream,offline_access,user_relationships,user_birthday,user_work_history,user_education_history,user_location";
//header("Location: ". $auth_url);
	
// fb app init
$facebook = new Facebook(array(
  'appId'  => $APP_ID,
  'secret' => $APP_SECRET,
));

// user data
$user = $facebook->getUser(); 

if ($user) {
	try {
		$user_profile = $facebook->api('/me');
		$fb_id = $user_profile['id'];
		$fb_name = $user_profile['name'];
		$fb_location = $user_profile['location']['name'];
		$fb_email = $user_profile['email'];
		
		$friend_arr = array();
		foreach ($facebook->api('/me/friends') as $data) {
			foreach ($data as $key=>$item_arr)
				$friend_arr[$item_arr['id']] = $item_arr['name'];
		}
	    array_pop($friend_arr);
	
		$like_arr = array();
	    foreach ($facebook->api('/me/likes') as $data) {
			foreach ($data as $key=>$item_arr)
				$like_arr[$item_arr['id']] = $item_arr['name'];
		}
		array_pop($like_arr);
		
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
	$auth_url = "https://graph.facebook.com/oauth/authorize?client_id=". $APP_ID ."&redirect_uri=http://dev.gullinbursti.cc/projs/oddjob/gigs/&scope=email,read_stream,publish_stream,offline_access,user_relationships,user_birthday,user_work_history,user_education_history,user_location";
	header("Location: ". $auth_url);
}  

$query = 'SELECT * FROM `tblJobs` WHERE `isActive` ="Y";';
$job_arr = mysql_query($query);


$query ='SELECT `device_id` FROM `tblUsers` WHERE `fb_id` = "'. $fb_id .'"';
$user_row = mysql_fetch_row(mysql_query($query));
$device_id = $user_row[0];
			
// 


//foreach ($friend_arr as $key => $val)
//	echo ("[". $key ."]". $val ."<br />");

//sendMail($fb_email, $fb_name, "Odd Job Redeem", "<html><head><title>Birthday Reminders for August</title></head><body><p>Here are the birthdays upcoming in August!</p><table><tr><th>Person</th><th>Day</th><th>Month</th><th>Year</th></tr><tr><td>Joe</td><td>3rd</td><td>August</td><td>1970</td></tr><tr><td>Sally</td><td>17th</td><td>August</td><td>1973</td></tr></table></body></html>");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<script type="text/javascript">
			var _kmq = _kmq || [];
		
			function _kms(u) {
				setTimeout(function() {
	      			var s = document.createElement('script'); 
					var f = document.getElementsByTagName('script')[0]; 
					s.type = 'text/javascript'; 
					s.async = true;
	      			s.src = u; 
					f.parentNode.insertBefore(s, f);
	    		}, 1);
	  		}

	  		_kms('//i.kissmetrics.com/i.js');
			_kms('//doug1izaerwt3.cloudfront.net/8afc90ad40b3e6b403aaec5e35d8b1343a9822da.1.js');
			_kmq.push(['record', 'View Account']);
		</script>
		
	    <title>:: Odd Job ::</title>
		<link href="./css/screen.css" rel="stylesheet" type="text/css" media="screen">
		
		<script type="text/javascript">
		<!--
			function showActivity () {
				location.href = './profile.php?a=1';
				_kmq.push(['record', 'View Activity']);
			}
			
			function showInvites() {
				location.href = './profile.php?a=2';
				_kmq.push(['record', 'View Invites']);
			}
			
			function showAccount() {
				location.href = './profile.php?a=3';
				_kmq.push(['record', 'View Settings']);
			}
			
			function inviteFriend(fID) {
				alert(fID);
			}
		-->
		</script>
	</head>
	
	<body><div id="divMainWrapper">
		<?php include './_inc/header.php'; ?>
		<div align="center">			
			<?php include './_inc/notifications.php'; ?>
			<div id="divAccountBtns">
				<input type="button" id="btnActivity" name="btnActivity" value="Activity" onclick="showActivity();" />
				<input type="button" id="btnInvites" name="btnInvites" value="Invites" onclick="showInvites();" />
				<input type="button" id="btnAccount" name="btnAccount" value="Account" onclick="showAccount();" />
			</div>
			<div id="divProfileContent"><?php
				switch ($_GET['a']) {
					
					// summary
					case 1:
						$summary_arr = array();
						
						$query = 'SELECT `job_id` FROM `tblJobRatings` WHERE `fb_id` ='. $fb_id .' ORDER BY `added`;';
						$review_res = mysql_query($query);
						
						$review_arr = array();
						while ($review_row = mysql_fetch_array($review_res, MYSQL_NUM))
							array_push($review_arr, $review_row[0]);
						       
						
						array_push($summary_arr, $review_arr);
						$query = 'SELECT `job_id` FROM `tblChallenges` WHERE `fb_id` ='. $fb_id .' ORDER BY `added`;';
						$challenge_res = mysql_query($query);
						
						$challenge_arr = array();
						while ($challenge_row = mysql_fetch_array($challenge_res, MYSQL_NUM))
							array_push($challenge_arr, $challenge_row[0]);
						       
						
						array_push($summary_arr, $challenge_arr);
						$query = 'SELECT `job_id` FROM `tblJobWatches` WHERE `fb_id` ='. $fb_id .' ORDER BY `added`;';
						$watch_res = mysql_query($query);
						
						$watch_arr = array();
						while ($watch_row = mysql_fetch_array($watch_res, MYSQL_NUM))
							array_push($watch_arr, $watch_row[0]);
						
                        array_push($summary_arr, $watch_arr);
                        
						$job_cnt = 0;
						for ($i=0; $i<3; $i++) {
							foreach ($summary_arr[$i] as $key=>$val) {
								$job_cnt++;
								
								$query ='SELECT `title`, `info`, `app_id` FROM `tblJobs` WHERE `id` = "'. $val .'"';
								$job_row = mysql_fetch_row(mysql_query($query));
								
								// retrieve app info						
								$query = 'SELECT `name`, `itunes_id`, `youtube_id` FROM `tblApps` WHERE `id` ='. $job_row[2] .';';
								$app_row = mysql_fetch_row(mysql_query($query));
	
								if ($app_row) {
									$appStore_json = json_decode(file_get_contents("http://itunes.apple.com/lookup?id=". $app_row[1] .""));
									$screenshot_arr = $appStore_json->results[0]->screenshotUrls;
								}
								
								$type_str = "";
								switch ($i) {
									case 0:
										$type_str = "Reviewed ";
										break;
										
									case 1:
										$type_str = "Challenged ";
										break;
										
									case 2:
										$type_str = "Watched ";
										break;
								}
								
								echo("<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\">\n");
								echo("<tr><td rowspan=\"2\" width=\"70\"><img src=\"". $appStore_json->results[0]->artworkUrl60 ."\" width=\"64\" height=\"64\" /></td><td>". $type_str . $app_row[0] ."</td><td rowspan=\"2\"><img src=\"\" width=\"32\" height=\"32\" /></td></tr>");
								echo("<tr><td>". $job_row[0] ."! ". $job_row[1] ."</td></tr>");
								echo("<tr><td colspan=\"3\"><hr /></td></tr>");
								echo("</table>");
								//echo ("summary_arr[". $i ."][". $key ."]= (". $val ."): '". $job_title ."'<br />");
								
							}
						}
						break;
					
					// invites
					case 2:
						$cnt = 0;
						$rand_tot = rand(4, 16);
						$friendID_arr = array_rand($friend_arr, $rand_tot);
						for ($i=0; $i<$rand_tot; $i++) {
							echo("<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\">\n");
							echo("<tr><td><a href=\"http://facebook.com/profile.php?id=". $friendID_arr[$i] ."\" target=\"_blank\"><img id=\"imgFBAvatar\" src=\"http://graph.facebook.com/". $friendID_arr[$i] ."/picture\" width=\"53\" height=\"53\" border=\"0\" title=\"". $friend_arr[$friendID_arr[$i]] ."\" alt=\"". $friend_arr[$friendID_arr[$i]] ."\" /></a></td><td><a href=\"http://facebook.com/profile.php?id=". $friendID_arr[$i] ."\" target=\"_blank\">". $friend_arr[$friendID_arr[$i]] ."</a></td><td align=\"right\"><input type=\"button\" id=\"btnInvite_". $friendID_arr[$i] ."\" name=\"btnInvite_". $friendID_arr[$i] ."\" value=\"Invite\" onclick=\"inviteFriend(". $friendID_arr[$i] .");\" /></td></tr>\n");
							echo("<tr><td colspan=\"3\"><hr /></td></tr>\n");
							echo("</table>\n");
						}
						break;
					
					// account	
					case 3:
						echo("<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\">\n");
						echo("<tr><td align=\"left\"><img src=\"http://graph.facebook.com/". $fb_id ."/picture\" width=\"64\" height=\"64\" title=\"". $fb_name ."\" alt=\"". $fb_name ."\" /></td><td align=\"right\"><input type=\"button\" id=\"btnDeactivate\" name=\"btnDeactivate\" value=\"Deactivate Account\" onclick=\"deactivate(<?php echo($fb_id); ?>);\" /></td></tr>\n");
						echo("<tr><td colspan=\"2\"><hr /></td></tr>\n");
						echo("<tr><td colspan=\"2\">");
						echo("<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\">");
						echo("<tr><td width=\"192\"><span class=\"spnProfileInfo\">Location:</span></td><td><span class=\"spnProfileInfo\">". $fb_location ."</span></td></tr>\n");
						echo("<tr><td><span class=\"spnProfileInfo\">Email:</span></td><td><span class=\"spnProfileInfo\">". $fb_email ."</span></td></tr>\n");
						echo("<tr><td><span class=\"spnProfileInfo\">Device ID:</span></td><td><span class=\"spnProfileInfo\">". $device_id ."</span></td></tr>\n");
						echo("</table>");
						echo("</td></tr></table>\n");
						break;
				}
			?></div>
		</div>
	</div></body>
</html>

<?php 

require "./_inc/db_close.php";
ob_flush(); 
?>
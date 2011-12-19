<?php ob_start();

require "./_consts/db_consts.php";
//require "./_consts/fb_consts.php";

$APP_ID = '139514356142393';
$APP_SECRET = 'b5c9eb235ba09cd7ad58ca99770dca55'; 
$app_url = 'http://apps.facebook.com/oddjobb/';
$FB_ID = '660042243';

require './_inc/fb-sdk/facebook.php';
require './_wrk/mailer.php';

require "./_inc/db_open.php";



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
		
	} catch (FacebookApiException $e) {
		error_log($e);
		$user = null;
	}
}

// login / logout url will be needed depending on current user state.
if ($user) {
	$logoutUrl = $facebook->getLogoutUrl();
	//echo($logoutUrl);

} else { 
	$loginUrl = $facebook->getLoginUrl();
	$auth_url = "https://graph.facebook.com/oauth/authorize?client_id=". $APP_ID ."&redirect_uri=". implode("/", explode('/', $_SERVER['SCRIPT_URI'], -1)) ."&scope=email,read_stream,publish_stream,offline_access,user_relationships,user_birthday,user_work_history,user_education_history,user_location";
	header("Location: ". $auth_url);
}  

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
			//_kmq.push(['identify', '<?php echo($fb_id); ?>']);
			_kmq.push(['record', 'Sign Up']);
		</script>
		
	    <title>:: Odd Job ::</title>
		<link href="./css/signin.css" rel="stylesheet" type="text/css" media="screen" />
		<link href="./css/friendFinder.css" rel="stylesheet" type="text/css" media="screen" />
		
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script type="text/javascript" src="./js/friendFinder.js"></script>
		<script type="text/javascript">
		  $(function() {
		      var friends = <?php echo json_encode($facebook->api('/me/friends')); ?>;
		      $('#friend-finder').friendFinder(friends);
		  });
		</script>
		<script type="text/javascript">
		<!--
			function locationChanged() {
				document.frmSignin.action = "./signin.php?l=1";
				document.frmSignin.submit();
			}
			
			function fbSignin() {
				document.frmSignin.action = "./signin.php?l=2";
				document.frmSignin.submit();
			}
			
			function shareFriends() {
				document.frmSignin.action = "./signin.php?l=3";
				document.frmSignin.submit();
			}
		-->
		</script>
	</head>
	
	<body><div id="divMainWrapper" align="center">
		<div id="divContainer"><div id="divSignup"><form id="frmSignin" name="frmSignin" method="post" action="">
			<img src="./img/signin_logo.jpg" width="159" height="42" title="" alt="" />
			<p align="left">Earn rewards and local deals by reviewing and sharing applications and games you already love…</p>
			<?php switch ($_GET['l']) { 
				default: ?>
					<p><select id="selLocation" name="selLocation" onchange="locationChanged();">
						<option value="0">Select Location…</option>
						<option value="1">Palo Alto</option>
					</select></p>
			<?php break;
			 	case "1": ?>
					<p><a href="#" onclick="fbSignin();"><img src="./img/signin_btn.jpg" width="219" height="47" border="0" /></a></p>
			<?php break; 
				case "2": ?>
					<p><a href="./index.php"><img src="./img/signin_nothanks.jpg" width="110" height="47" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="shareFriends()"><img src="./img/signin_shareBtn.jpg" width="181" height="47" /></a></p>
			<?php break;
				case "3": ?>
					<p />
					<div id="friend-finder">
					    <div id="friend-finder-selected"></div>
					    <input type="text" class="friend-finder-input" placeholder="Start typing a friend's name..." autocomplete="off" autocorrect="off" />
					    <input type="hidden" name="friends" value="" />
					    <ul id="friend-finder-dropdown"></ul>
					</div><p /><br /><?php
						$friendID_arr = array_rand($friend_arr, 10);
							for ($i=0; $i<10; $i++) {
								if ($i == 5)
									echo ("<br /><p />");
							
								echo ("<a href=\"http://facebook.com/profile.php?id=". $friendID_arr[$i] ."\" target=\"_blank\"><img class=\"imgFBAvatar\" src=\"http://graph.facebook.com/". $friendID_arr[$i] ."/picture\" width=\"32\" height=\"32\" border=\"0\" title=\"". $friend_arr[$friendID_arr[$i]] ."\" alt=\"". $friend_arr[$friendID_arr[$i]] ."\" /></a><input type=\"checkbox\" class=\"chkFBAvatar\" />");
							} ?>
					<p />
					<p><br /><a href="./index.php"><img src="./img/signin_nothanks.jpg" width="110" height="47" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick=""><img src="./img/signin_shareBtn.jpg" width="181" height="47" /></a></p>
				<?php break;
			} ?>
		</form></div></div>
		<div id="divFooter">
			<a href="./about.php">How it works?</a> | 
			<a href="./merchants.php">Merchants</a> | 
			<a href="./developers.php">Developers</a> | 
			<a href="./privacy.php">Privacy</a> | 
			<a href="./terms.php?jID=<?php echo($job_id); ?>">Terms &amp; Conditions</a>
			<div id="divAppBtn"><a href="http://itunes.apple.com/us/app/id000000000?mt=8" target="_blank"><img src="./img/badgeAppStore.png" width="97" height="33" title="View Odd Job on the iTunes Store" alt="View Odd Job on the iTunes Store" border="0" /></a></div>
		</div>
	</div></body>
</html>

<?php 

require "./_inc/db_close.php";
ob_flush(); 
?>
<?php ob_start();

require "./_consts/db_consts.php";
require "./_consts/fb_consts.php";

require './_wrk/mailer.php';
require './_inc/fb-sdk/facebook.php';
require "./_inc/db_open.php";


//if ($_GET['fb'] == "true") {
	$facebook = new Facebook(array(
	  'appId'  => $APP_ID,
	  'secret' => $APP_SECRET,
	));


	$user = $facebook->getUser(); 

	if ($user) {
		try {
			$user_profile = $facebook->api('/me');
			$fb_id = $user_profile['id'];
		
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

	} else { 
		$loginUrl = $facebook->getLoginUrl();
		$auth_url = "https://graph.facebook.com/oauth/authorize?client_id=". $APP_ID ."&redirect_uri=http://dev.gullinbursti.cc/projs/oddjob/signup/user.php&scope=user_location";
		header("Location: ". $auth_url);
	}
//}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	    <title>Odd Job :: User Signup</title>
		<link href="./css/screen.css" rel="stylesheet" type="text/css" media="screen">
		
		<script type="text/javascript">
		<!--
			
			function fbConnect() {
				location.href = "user.php?fb=true";
			}
		    
			function friendSelector() {
				FB.ui({method: 'apprequests',
					message: 'Odd Job Early Access'
				}, requestCallback);
			}
			
		
			function signup() {
				var hidFBID = document.getElementById('hdFBID');
				var hidFriends = document.getElementById('hidFriends');
				
				alert ("txtName:["+txtName+"] txtCompName:["+txtCompName+"] txtEmail:["+txtEmail+"]txtPass:["+txtPass+"]");
				document.frmSignup.submit();
			}
			
			function requestCallback() {
				
			}
			
		-->
		</script>
		
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
		</script> 
	</head>
	
	<body><div id="divMainWrapper" align="center">
		
		<script src="http://connect.facebook.net/en_US/all.js"></script>
        <script>
			FB.init({
				appId  : '139514356142393', 
				frictionlessRequests: true, 
				status : true,
				cookie : true,
				oauth: true
			});
        </script>
        
		<form id="frmSignup" name="frmSignup" method="post" action="./signup"><table id="tblFormTout" cellpadding="0" cellspacing="0" border="0">
			
			<tr><td colspan="2" id="tdFormHeader">Want early access to Odd Job?</td></tr>
			<tr><td colspan="2"><hr noshade="noshade" size="1" /></td></tr>
			<tr><td colspan="2" id="tdFormInfo">Odd Job is a social platform for you to earn rewards for installing games and applications.</td></tr>
			<tr><td colspan="2" align="center">
				<input type="hidden" id="hidType" name="hidType" value="user" />
				<input type="hidden" id="hidFBID" name="hidFBID" value="<?php echo($fb_id); ?>" />
				<input type="hidden" id="hidFriends" name="hidFriends" value="" />
				<input type="button" id="btnConnect" name="btnConnect" value="Connect to Facebook" onclick="friendSelector();" />
			</td></tr>
			<tr><td colspan="2" align="left"><span id="spnDisclaim">By signing up, you are also accepting our <a href="./terms.php">Terms and Conditions.</a></span></td></tr>
			
		</table></form>
	</div></body>
</html>

<?php 

require "./_inc/db_close.php";
ob_flush(); 
?> 
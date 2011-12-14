<?php

// start the session engine
session_start(); 

// start the output buffer
ob_start();


// feedback message to user
$err_msg = '';

// landed w/ a msg
if (isset($_GET['err']))
	$err_msg = $_GET['err']; 

// reset the user / pass vars
$user_str = '';
$pass_str = '';

if (isset($_SESSION['login']))
	header('Location: ./index.php');
 
// easter eggs
if (isset($_GET['egg'])) {
	$user_str = 'admin';
	$pass_str = 'dope911t';
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta http-equiv="Content-language" value="en" />
		
		<title>Odd Job CMS Login</title>
		<script type="text/javascript">
		<!--
				
			function attemptLogin() {
				
				var strUser = document.getElementById('txtUser').value;
				var strPass = document.getElementById('txtPass').value;
				 
				if (strUser != "" && strPass != "")
					document.frmLogin.submit();
						
				else
					alert("Fields cannot be blank!");
			}
				
		-->
		</script>
	</head>
	
	<body>
		<div id="siteTout">
			<div id="pageTout">
				Odd Job CMS:
				<hr />
	   			<form name="frmLogin" action="./login_validate.php" method="post"><table id="tblMainWrapper" cellspacing="0" cellpadding="0" border="0">
	   				<tr><td>Username:&nbsp;&nbsp;</td><td><input type="text" id="txtUser" name="txtUser" value="<?php echo $user_str; ?>" /></td></tr>
	   				<tr><td>Password:&nbsp;&nbsp;</td><td><input type="password" id="txtPass" name="txtPass" value="<?php echo $pass_str; ?>" /></td></tr>
	   				<tr><td colspan="2"><input type="submit" value="Submit" onclick="attemptLogin();" /></td></tr>
	   			</table></form>
	   			<?php echo $err_msg; ?>
			</div>
			<div id="footerTout">
			</div>
		</div>
	</body>
</html>
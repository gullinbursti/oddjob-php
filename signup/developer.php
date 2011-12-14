<?php ob_start();

require "./_consts/db_consts.php";
require './_wrk/mailer.php';
require "./_inc/db_open.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	    <title>Odd Job :: Developer Signup</title>
		<link href="./css/screen.css" rel="stylesheet" type="text/css" media="screen">
		
		<script type="text/javascript">
		<!--
			function signup() {
				
				var isComplete = true;
				var err_str = "";
				
				var txtName = document.getElementById('txtName');
				var txtCompName = document.getElementById('txtCompName');
				var txtEmail = document.getElementById('txtEmail');
				var txtPass = document.getElementById('txtPass');
				var txtPass2 = document.getElementById('txtPass2');
				var txtPhone1 = document.getElementById('txtPhone1');
				var txtPhone2 = document.getElementById('txtPhone2');
				var txtPhone3 = document.getElementById('txtPhone3');
				
				if (txtName.value == "") {
					isComplete = false;
					err_str += "Name is required\n";
				}
				
				if (txtCompName.value == "") {
					isComplete = false;
					err_str += "Company name is required\n";
				}
					
				if (txtEmail.value == "") {
					isComplete = false;
					err_str += "Email address is required\n";
				}
					
				if (txtPass.value == "") {
					isComplete = false;
					err_str += "Password is required\n";
				}
					
				if (txtPass.value != txtPass2.value) {
					isComplete = false;
					err_str += "Passwords don't match\n";
				}
				
				if (txtPhone1.value == "" || txtPhone2.value == "" || txtPhone3.value == "") {
					isComplete = false;
					err_str += "Phone number is required";
				
				} else
					document.frmSignup.hidPhone.value = txtPhone1.value + "." + txtPhone2.value + "." + txtPhone3.value;
				
				
				if (!isComplete)
					alert (err_str);
				
				else
					document.frmSignup.submit();
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
		<div id="divLogin"><a href="login.php?t=developer">Already have an account?</a></div>
		<form id="frmSignup" name="frmSignup" method="post" action="./signup.php"><table id="tblFormTout" cellpadding="0" cellspacing="0" border="0">
			<tr><td colspan="2" id="tdFormHeader">Developer Signup</td></tr>
			<tr><td colspan="2"><hr noshade="noshade" size="1" /></td></tr>
			<tr><td colspan="2" id="tdFormInfo">Odd Job is a social platform for you to earn rewards for installing games and applications.</td></tr>
			<tr><td class="tdLabel"><label for="txtName">Name:</label></td><td class="tdField"><input type="text" id="txtName" name="txtName" size="32" value="" /></td></tr>
			<tr><td class="tdLabel"><label for="txtCompName">Company Name:</label></td><td class="tdField"><input type="text" id="txtCompName" name="txtCompName" size="32" value="" /></td></tr>
			<tr><td class="tdLabel"><label for="txtPhone">Phone #:</label></td><td class="tdField">(<input type="text" id="txtPhone1" name="txtPhone1" size="3" value="" />)<input type="text" id="txtPhone2" name="txtPhone2" size="3" value="" />-<input type="text" id="txtPhone3" name="txtPhone3" size="4" value="" /></td></tr>
			<tr><td class="tdLabel"><label for="txtEmail">Email:</label></td><td class="tdField"><input type="text" id="txtEmail" name="txtEmail" size="32" value="" /></td></tr>
			<tr><td class="tdLabel"><label for="txtPass">Password:</label></td><td class="tdField"><input type="password" id="txtPass" name="txtPass" size="8" value="" /></td></tr>
			<tr><td class="tdLabel"><label for="txtPass2">Confirm Password:</label></td><td class="tdField"><input type="password" id="txtPass2" name="txtPass2" size="8" value="" /></td></tr>
			<tr><td colspan="2" align="center"><input type="hidden" id="hidPhone" name="hidPhone" value="" /><input type="hidden" id="hidType" name="hidType" value="developer" /><input type="button" id="btnSubmit" name="btnSubmit" value="Submit" onclick="signup();"></td></tr>
			<tr><td colspan="2" align="left"><span id="spnDisclaim">By signing up, you are also accepting our <a href="./terms.php">Terms and Conditions.</a></span></td></tr>
		</table></form>
	</div></body>
</html>

<?php 

require "./_inc/db_close.php";
ob_flush(); 
?> 
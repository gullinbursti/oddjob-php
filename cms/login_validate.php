<?php

session_start();

// start the output buffer
ob_start();

// form fields
$user_str = $_POST['txtUser'];
$pass_str = $_POST['txtPass'];

// bitwise boolean
$login_bit = 0x00;

// messaging
$err_msg = '';


if ($user_str != "admin") {
	$login_bit = $login_bit | 0x01;
	$err_msg = 'Username invalid';
}
	
if ($pass_str != "dope911t") {
	$login_bit = $login_bit | 0x10;
	$err_msg = 'Password invalid';
}

if ($login_bit == 0x00) {
	$_SESSION['login'] = "admin";
	header('Location: ./index.php');

} else
	header('Location: ./login.php?err='. urlencode($err_msg));

// clear the output buffer
ob_flush();


?>
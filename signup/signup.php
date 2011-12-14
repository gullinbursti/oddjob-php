<?php ob_start();

require "./_consts/db_consts.php";
require './_wrk/mailer.php';
require "./_inc/db_open.php";


if ($_POST['hidType']) {
	switch ($_POST['hidType']) {
		case "merchant":
			$query = 'INSERT INTO `tblSignupMerchants` (';
			$query .= '`id`, `name`, `comp_name`, `email`, `phone`, `pass`, `added`) ';
			$query .= 'VALUES (NULL, "'. $_POST['txtName'] .'", "'. $_POST['txtCompName'] .'", "'. $_POST['txtEmail'] .'", "'. $_POST['hidPhone'] .'", "'. $_POST['txtPass'] .'", CURRENT_TIMESTAMP);';
			$result = mysql_query($query);
			$merchant_id = mysql_insert_id(); 
			break;
			
		case "developer":
			$query = 'INSERT INTO `tblSignupDevelopers` (';
			$query .= '`id`, `name`, `comp_name`, `email`, `phone`, `pass`, `added`) ';
			$query .= 'VALUES (NULL, "'. $_POST['txtName'] .'", "'. $_POST['txtCompName'] .'", "'. $_POST['txtEmail'] .'", "'. $_POST['hidPhone'] .'", "'. $_POST['txtPass'] .'", CURRENT_TIMESTAMP);';
			$result = mysql_query($query);
			$developer_id = mysql_insert_id();
			break;
			
		case "user":
			$fb_id = $_POST['txtFBID'];
			$query = 'INSERT INTO `tblSignupUsers` (';
			$query .= '`id`, `fb_id`, `invites`, `added`) ';
			$query .= 'VALUES (NULL, "'. $_POST['hidFBID'] .'", "'. $_POST['hidFriends'] .'", CURRENT_TIMESTAMP);';
			$result = mysql_query($query);
			$user_id = mysql_insert_id();
			break;
	}
	
	require "./_inc/db_close.php";
	header('Location: '. $_POST['hidType'] .'.php?a=true');

} else {
	require "./_inc/db_close.php";
	header('Location: index.php');
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	    <title>:: Odd Job ::</title>
		<link href="./css/screen.css" rel="stylesheet" type="text/css" media="screen" />
	</head>
	<body />
</html>

<?php ob_flush(); ?> 
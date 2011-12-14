<?php

if (isset($_POST["fbid"]))
	$fb_id = $_POST["fbid"];
	
else
	$fb_id = -666;
?>

<html><body><?php echo "fbid=[". $fb_id ."]";?></body></html>

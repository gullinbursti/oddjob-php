<?php

require "./_consts/db_consts.php";
require "./_consts/fb_consts.php";

$db_conn = mysql_connect('internal-db.s41232.gridserver.com', 'db41232_oj_usr', 'dope911t');// or die("Could not connect to database.");
mysql_select_db('db41232_ojdev');// or die("Could not select database."); 

// init job id
$job_id = 0;
$job_name = "";
$job_info = "";
$type_id = 0;
$type_name = "Review";
$app_id = 0;
$app_name = "";
$obj_id = 0;
$obj_name = "";
$img_url= "";

// check for querystr
if (isset($_GET['jID'])) {
	$job_id = $_GET['jID'];
	
	// retrieve job info
	$query = 'SELECT `action_id`, `app_id`, `offer_id` FROM `tblJobs` WHERE `id` ='. $job_id .';';
	$job_row = mysql_fetch_row(mysql_query($query));

	// has result
	if ($job_row) {
		
		 // retrieve app info						
		$query = 'SELECT `name`, `ico_url`, `fb_object`, `info` FROM `tblApps` WHERE `id` ='. $job_row[1] .';';
		$app_row = mysql_fetch_row(mysql_query($query));
		
		if ($app_row) {
			$app_name = $app_row[0];
			$img_url = $app_row[1];
			$obj_name = $app_row[2];  
			$job_info = $app_row[3];
		}
		
		// retrieve job type
    	$query = 'SELECT `fb_name` FROM `tblActionTypes` WHERE `id` ='. $job_row[0] .';';
		$type_row = mysql_fetch_row(mysql_query($query));
		
		if ($type_row)
			$type_name = $type_row[0];
			
			
		// retrieve job type
    	$query = 'SELECT `name` FROM `tblOffers` WHERE `id` ='. $job_row[2] .';';
		$offer_row = mysql_fetch_row(mysql_query($query));
		
		if ($offer_row)
			$offer_name = $offer_row[0];
			
			
	   // retrieve merchant
		$query = 'SELECT `tblMerchants`.`name` FROM `tblMerchants` INNER JOIN `tblOffers` ON `tblMerchants`.`id` = `tblOffers`.`merchant_id` WHERE `tblOffers`.`id` ='. $job_row[2] .';';
		$merchant_row = mysql_fetch_row(mysql_query($query));

		if ($merchant_row)
			$merchant_name = $merchant_row[0];
	}
}

echo ("<html>\n");
echo ("  <head prefix=\"og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# oddjobb: http://ogp.me/ns/fb/oddjobb#\">\n");
echo ("    <meta property=\"fb:app_id\"      content=\"139514356142393\" />\n");
echo ("    <meta property=\"og:type\"        content=\"oddjobb:". $obj_name ."\" />\n");
echo ("    <meta property=\"og:url\"         content=\"". $_SERVER['SCRIPT_URI'] ."?". $_SERVER['QUERY_STRING'] ."\">\n");
echo ("    <meta property=\"og:title\"       content=\"". $app_name ."\" />\n");
echo ("    <meta property=\"og:description\" content=\"". $offer_name ." from ". $merchant_name ."!\" />\n");
echo ("    <meta property=\"og:image\"       content=\"". $img_url ."\" />\n");
//echo ("    <meta http-equiv=\"refresh\"      content=\"0;url=". implode("/", explode('/', $_SERVER['SCRIPT_URI'], -1)) ."/". strtolower($type_name) .".php?jID=". $job_id ."\" />");
echo ("  </head>\n");
echo ("</html>\n");

//echo ("    <meta http-equiv=\"refresh\"      content=\"0;url=http://dev.gullinbursti.cc/projs/oddjob/gigs/". strtolower($type_name) .".php?jID=". $job_id ."\" />");
//echo ("    <meta property=\"og:url\"         content=\"http://dev.gullinbursti.cc/projs/oddjob/gigs/opengraph.php?jID=". $job_id ."\" />\n");
require "./_inc/db_close.php";

?>
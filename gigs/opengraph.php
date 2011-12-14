<?php

require "./_consts/db_consts.php";
require "./_consts/fb_consts.php";

$db_conn = mysql_connect('internal-db.s4086.gridserver.com', 'db4086_ojusr', 'dope911t');// or die("Could not connect to database.");
mysql_select_db('db4086_oddjob');// or die("Could not select database."); 

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
	$query = 'SELECT `title`, `info`, `type_id`, `app_id`, `object_id` FROM `tblJobs` WHERE `id` ='. $job_id .';';
	$job_row = mysql_fetch_row(mysql_query($query));

	// has result
	if ($job_row) {
		$job_name = $job_row[0];
		$job_info = $job_row[1];
		$type_id = $job_row[2];
		$app_id = $job_row[3];
		$obj_id = $job_row[4];
		
		// retrieve job type
    	$query = 'SELECT `name` FROM `tblJobTypes` WHERE `id` ='. $type_id .';';
		$type_row = mysql_fetch_row(mysql_query($query));
		
		if ($type_row)
			$type_name = $type_row[0];
			
		// retrieve app info						
		$query = 'SELECT `name`, `itunes_id`, `youtube_id` FROM `tblApps` WHERE `id` ='. $app_id .';';
		$app_row = mysql_fetch_row(mysql_query($query));
		
		if ($app_row) {
			$app_name = $app_row[0];
			$app_id = $app_row[1];
			$youtube_id = $app_row[2];
			$appStore_json = json_decode(file_get_contents("http://itunes.apple.com/lookup?id=". $app_id .""));
			$img_url = $appStore_json->results[0]->artworkUrl60;
		}
    	
		$query = 'SELECT `name` FROM `tblObjects` WHERE `id` ='. $obj_id .';';
		$obj_row = mysql_fetch_row(mysql_query($query));
		$obj_name = $obj_row[0];
	}
}

echo ("<html>\n");
echo ("  <head prefix=\"og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# oddjobb: http://ogp.me/ns/fb/oddjobb#\">\n");
echo ("    <meta property=\"fb:app_id\"      content=\"139514356142393\" />\n");
echo ("    <meta property=\"og:type\"        content=\"oddjobb:". $obj_name ."\" />\n");
echo ("    <meta property=\"og:url\"         content=\"". $_SERVER['SCRIPT_URI'] ."?". $_SERVER['QUERY_STRING'] ."\">");
echo ("    <meta property=\"og:title\"       content=\"". $app_name ."\" />\n");
echo ("    <meta property=\"og:description\" content=\"". $job_name ."! ". $job_info ."\" />\n");
echo ("    <meta property=\"og:image\"       content=\"". $img_url ."\" />\n");
echo ("    <meta http-equiv=\"refresh\"      content=\"0;url=". implode("/", explode('/', $_SERVER['SCRIPT_URI'], -1)) ."/". strtolower($type_name) .".php?jID=". $job_id ."\" />");
echo ("  </head>\n");
echo ("</html>\n");

//echo ("    <meta http-equiv=\"refresh\"      content=\"0;url=http://dev.gullinbursti.cc/projs/oddjob/gigs/". strtolower($type_name) .".php?jID=". $job_id ."\" />");
//echo ("    <meta property=\"og:url\"         content=\"http://dev.gullinbursti.cc/projs/oddjob/gigs/opengraph.php?jID=". $job_id ."\" />\n");
require "./_inc/db_close.php";

?>
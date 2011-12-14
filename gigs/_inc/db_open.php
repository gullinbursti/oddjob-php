<?php

// include the db attribs
require './_consts/db_consts.php';

// make the connection
$db_conn = mysql_connect($DB_HOST, $DB_USER, $DB_PASS) or die("Could not connect to database.");

// select the proper db
mysql_select_db($DB_NAME) or die("Could not select database.");

// get the current date / time from mysql
$ts_result = mysql_query("SELECT NOW();") or die("Couldn't get the date from MySQL");
$row = mysql_fetch_row($ts_result);
$sql_time = $row[0];

?>
<?php

class JobSearch {
	
	private $db_conn;
	
	
	function __construct() {
		$this->db_conn = mysql_connect('127.0.0.1', 'oj_usr', 'dope911t') or die("Could not connect to database.");
		mysql_select_db('oddjob') or die("Could not select database.");
	}
	
	
	function __destruct() {
		$this->db_conn->close();
	}
	
	
	function userInfo($fb_id) {
		$query = 'SELECT `id`, `fName`, `lName` FROM `tblUsers` WHERE `fbid` = "'. $fb_id .'";';
		$row = mysql_fetch_row(mysql_query($query));

		echo "id: [". $row[0] ."] Name:[". $row[1] ." ". $row[2] ."]";
	}
}


	
//$fb_id='660042243';
	
$jobSearch = new JobSerach;
$jobSearch->userInfo('660042243');
	
//$db_conn = mysql_connect('127.0.0.1', 'oj_usr', 'dope911t') or die("Could not connect to database.");
//mysql_select_db('oddjob') or die("Could not select database.");
	
//$query = 'SELECT `id`, `fName`, `lName` FROM `tblUsers` WHERE `fbid` = "'. $fb_id .'";';
//$row = mysql_fetch_row(mysql_query($query));
	
//echo "id: [". $row[0] ."] Name:[". $row[1] ." ". $row[2] ."]";	
	
?>
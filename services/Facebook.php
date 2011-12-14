<?php

class Facebook {
	
	private $db_conn;
	
	
	function __construct() {
		
		// make the connection
		$this->db_conn = mysql_connect('internal-db.s41232.gridserver.com', 'db41232_oj_usr', 'dope911t') or die("Could not connect to database.");
		
		// select the proper db
		mysql_select_db('db41232_oddjob') or die("Could not select database.");
	}
	
	
	function __destruct() { 
		
		if ($this->db_conn) {
			mysql_close($this->db_conn);
			$this->db_conn = null;
		}
	}
	
	
	function toActivity($fb_id, $action_id, $object_id, $token) {
		$query = 'SELECT `name` FROM `tblActions` WHERE `id` = "'. $action_id .'";';
		$row = mysql_fetch_row(mysql_query($query));
		
		$action_name =$row[0];
		
		$query = 'SELECT `name`, `url` FROM `tblObjects` WHERE `id` = "'. $object_id .'";';
		$row = mysql_fetch_row(mysql_query($query));
		
		$object_name =$row[0];
		$object_url =$row[1];
		
		echo "action_id: [". $action_id ."] action_name:[". $action_name ."] object_id:[". $object_id ."] object_name:[". $object_name ."]";
		
		
		$this->curl("https://graph.facebook.com/". $fb_id ."/oddjobb:". $action_name ."?". $object_name ."=". $object_url .".htm&access_token=". $token);
	}
	
	
	function toTicker($fb_id, $action_id, $object_id, $token) {
		$query = 'SELECT `name` FROM `tblActions` WHERE `id` = "'. $action_id .'";';
		$row = mysql_fetch_row(mysql_query($query));
		
		$action_name =$row[0];
		
		$query = 'SELECT `name`, `url` FROM `tblObjects` WHERE `id` = "'. $object_id .'";';
		$row = mysql_fetch_row(mysql_query($query));
		
		$object_name =$row[0];
		$object_url =$row[1];
		
		echo "action_id: [". $action_id ."] action_name:[". $action_name ."] object_id:[". $object_id ."] object_name:[". $object_name ."]";
		
		
		$this->curl("https://graph.facebook.com/". $fb_id ."/oddjobb:". $action_name ."?". $object_name ."=". $object_url .".htm&access_token=". $token);             
	}
	
	
	function toTimeline($fb_id, $action_id, $object_id, $token) {
		$query = 'SELECT `name` FROM `tblActions` WHERE `id` = "'. $action_id .'";';
		$row = mysql_fetch_row(mysql_query($query));
		
		$action_name =$row[0];
		
		$query = 'SELECT `name`, `url` FROM `tblObjects` WHERE `id` = "'. $object_id .'";';
		$row = mysql_fetch_row(mysql_query($query));
		
		$object_name =$row[0];
		$object_url =$row[1];
		
		echo "action_id: [". $action_id ."] action_name:[". $action_name ."] object_id:[". $object_id ."] object_name:[". $object_name ."]";
		
		
		$this->curl("https://graph.facebook.com/". $fb_id ."/oddjobb:". $action_name ."?". $object_name ."=". $object_url .".htm&access_token=". $token);
	}
	
	
	function curl($url) {
		
		$ch = curl_init($url);

	    curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_exec($ch);
		curl_close($ch);
		
		
		//http://dev.gullinbursti.cc/projs/oddjob/posts/
	}
}

	
$facebook = new Facebook;
$facebook->toActivity('660042243');
	
?>
<?php

class App {
	
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
	
	/**
	 * Helper method to get a string description for an HTTP status code
	 * http://www.gen-x-design.com/archives/create-a-rest-api-with-php/ 
	 * @returns status
	 */
	function getStatusCodeMessage($status) {
		
		$codes = Array(
			100 => 'Continue',
			101 => 'Switching Protocols',
			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',
			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Found',
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			306 => '(Unused)',
			307 => 'Temporary Redirect',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentication Required',
			408 => 'Request Timeout',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition Failed',
			413 => 'Request Entity Too Large',
			414 => 'Request-URI Too Long',
			415 => 'Unsupported Media Type',
			416 => 'Requested Range Not Satisfiable',
			417 => 'Expectation Failed',
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unavailable',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported');

		return (isset($codes[$status])) ? $codes[$status] : '';
	}
	
	
	/**
	 * Helper method to send a HTTP response code/message
	 * @returns body
	 */
	function sendResponse($status=200, $body='', $content_type='text/html') {
		
		$status_header = "HTTP/1.1 ". $status ." ". $this->getStatusCodeMessage($status);
		header($status_header);
		header("Content-type: ". $content_type);
		echo $body;
	}
		
	function boot($fb_id, $f_name, $l_name, $age, $hometown, $edu_id, $profession, $friends_tot) {   
		
		$query = 'SELECT `id` FROM `tblUsers` WHERE `fb_id` = "'. $fb_id .'";';
		$row = mysql_fetch_row(mysql_query($query));
		
		
		// update w/ app runtime
		if ($row) {
			$user_id = $row[0];
			
		// insert new install	
		} else {
			$query = 'INSERT INTO `tblUsers` (';
			$query .= '`id`, `fbid`, `fName`, `lName`, `age`, `hometown`, `edu_id`, `profession`, `friends`, `added`) ';
			$query .= 'VALUES (NULL, "'. $fb_id .'", "'. $f_name .'", "'. $l_name .'", "'. $age .'", "'. $hometown .'", "'. $edu_id .'", "'. $profession .'", "'. $friends .'", NOW());';
			$result = mysql_query($query);
			
			
			$query = 'SELECT LAST_INSERT_ID();';
			$row = mysql_fetch_row(mysql_query($query));
		}
		 
		$user_id = $row[0];
	
		// Return data, as JSON
		$result = array("id" => $row[0]);
		$this->sendResponse(200, json_encode($result));
		return (true);
	}
}

	
$app = new App;
//$app->boot('660042243');

if (isset($_POST["fbid"])) {
	$fb_id = $_POST["fbid"];
	$name = explode(" ", $_POST["name"]);
	$f_name = $name[0];
	$l_name = $name[1];
	$age = $_POST["age"];
	$hometown = $_POST["hometown"];
	$edu_id = $_POST["edu"];
	$profession = $_POST["profession"];
	$friends_tot = $_POST["friends"];
	
	$userInfo_arr = $app->boot($fb_id, $f_name, $l_name, $age, $hometown, $edu_id, $profession, $friends_tot);
}
	
?>
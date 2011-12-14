<?php

	class Users {
	
		private $db_conn;
		
		private $degrees2Miles;
	
		function __construct() {
			
			$this->degrees2Miles = 69;
			
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
	
		/**
		 * This service returns all users available
		 * @returns recordset
		 */
		function allUsers() {

			$query = 'SELECT * FROM `tblUsers`;';
			$res = mysql_query($query);

			// error performing query
			if (mysql_num_rows($res) > 0) {

				// Return data, as JSON
				$result = array();

				while ($row = mysql_fetch_array($res, MYSQL_BOTH)) {
					array_push($result, array(
						"id" => $row['id'], 
						"fb_id" => $row['fb_id'], 
						"fName" => $row['fName'], 
						"lName" => $row['lName'], 
						"locality_id" => $row['locality_id'], 
						"sex" => $row['sex'], 
						"age" => $row['age'], 
						"edu_id" => $row['edu_id']
						
					));
				}

				$this->sendResponse(200, json_encode($result));
				return (true);

			} else
				return (false);
		}


		/**
		 * This service returns user data from a Facebook id
		 * @returns recordset
		 */
		function userInfoByFacebookID($id) {
			
			$query = 'SELECT `id`, `fName`, `lName` FROM `tblUsers` WHERE `fb_id` = "'. $id .'";';
			$row = mysql_fetch_row(mysql_query($query));
			
			if ($row) {
				// Return data, as JSON
				$result = array(
					"id" => $row[0], 
					"fName" => $row[1], 
					"lName" => $row[2]);
			
				$this->sendResponse(200, json_encode($result));
				return (true);
			
				//return ($row);
				//$result = mysql_query($query);
			}
			
			return (false);
		}
		
		
		/**
		 * This service returns id with age
		 * @returns recordset
		 */
		function userIDByAgeRange($l, $u) {

			$query = 'SELECT `id`, FROM `tblUsers` WHERE `age` >= "'. $l .'" AND `age` <= "'. $u .'";';
			$row = mysql_fetch_row(mysql_query($query));

			if ($row) {
				// Return data, as JSON
				$result = array(
					"id" => $row[0]);

				$this->sendResponse(200, json_encode($result));
				return (true);
			}
			
			return (false);

			//return ($row);
			//$result = mysql_query($query);
		}
		
		/**
		 * This service returns id with gender
		 * @returns recordset
		 */
		function userIDBySex($sex) {

			$query = 'SELECT `id` FROM `tblUsers` WHERE `sex` = "'. $sex .'";';
			$row = mysql_fetch_row(mysql_query($query));

			if ($row) {
				// Return data, as JSON
				$result = array(
					"id" => $row[0]);

				$this->sendResponse(200, json_encode($result));
				return (true);			
			}
			
			return (false);
		}
		
		
		
	
		/**
		 * This service returns id w/in radius
		 * @returns recordset
		 */
		function userIDByRadius($long, $lat, $r) {
			
			$degees = $r / 69;
			return (true);
		}
		
		
		function updateUserLocation($fb_id, $long, $lat) {
			
			$query = 'UPDATE `tblUsers` SET `longitude` ='. $long .' WHERE `fb_id` ='. $fb_id .';';
			$result = mysql_query($query);
			
			$query = 'UPDATE `tblUsers` SET `latitude` ='. $lat .' WHERE `fb_id` ='. $fb_id .';';
			$result = mysql_query($query);
			
			return (true);
		}
		
		
		
		function userIDByEducationID($edu_id) {
			$query = 'SELECT `id` FROM `tblUsers` WHERE `edu_id` = "'. $edu_id .'";';
			$row = mysql_fetch_row(mysql_query($query));
			
			if ($row) {

				// Return data, as JSON
				$result = array(
					"id" => $row[0]);

				$this->sendResponse(200, json_encode($result));
			
				return (true);
			}
			
			return (false);
		}
		
		
		function updateFavorite($fb_id, $job_id, $isFav) {
			
			if ($isFav) {
				echo ("addFavorite (fbid:[". $fb_id ."] jobID:[". $job_id ."] isFav:[". $isFav ."])");
				
		   		$query = 'INSERT INTO `tblUsersFavJobs` (';
				$query .= '`id`, `fb_id`, `job_id`, `added`) ';
				$query .= 'VALUES (NULL, "'. $fb_id .'", "'. $job_id .'", CURRENT_TIMESTAMP);';
				$result = mysql_query($query);
			
				return (true);
				
			} else {
				echo ("delFavorite (fbid:[". $fb_id ."] jobID:[". $job_id ."] isFav:[". $isFav ."])");
				
				$query = 'DELETE FROM `tblUsersFavJobs` WHERE `fb_id` = "'. $fb_id .'" AND `job_id` = "'. $job_id .'";';
				$result = mysql_query($query);
				
				return (true);
			}
	    	
		}
	}
	
	//$fb_id = '660042243';
	
	$users = new Users;
	
	if (isset($_POST['action'])) {
		switch ($_POST['action']) {
			case "0":
				$users->updateUserLocation($_POST['fbid'], $_POST['long'], $_POST['lat']);
				break;
				
			case "1":
				if (isset($_POST["fbid"]) && isset($_POST["jobID"])) {
					$users->updateFavorite($_POST["fbid"], $_POST["jobID"], false);
				}
				break;
				
			case "2":
				if (isset($_POST["fbid"])) {
					$users->updateFavorite($_POST["fbid"], $_POST["jobID"], true);
				}
				break; 
		}
	}
	//$userInfo_arr = $users->userInfoByFacebookID($fb_id);
?>
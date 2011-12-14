<?php

	class Jobs {
	
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
		 * This service returns all jobs available
		 * @returns recordset
		 */
		function allJobs() {

			$query = 'SELECT * FROM `tblJobs`;';
			$res = mysql_query($query);
			
			// error performing query
			if (mysql_num_rows($res) > 0) {
					
				// Return data, as JSON
				$result = array();
			
				while ($row = mysql_fetch_array($res, MYSQL_BOTH)) {
					array_push($result, array(
						"id" => $row['id'], 
						"type_id" => $row['type_id'], 
						"title" => $row['title'], 
						//"info" => $row['info'], 
						"locality_id" => $row['locality_id'], 
						"points" => $row['points']
					));
				}
			
				$this->sendResponse(200, json_encode($result));
				return (true);
			
			} else
				return (false);
		}
		
		
		/**
		 * This service returns jobs available per locality
		 * @returns recordset
		 */
		function jobsByLocalityID($l_id) {

			$query = 'SELECT * FROM `tblJobs` WHERE `locality_id` ="'. $l_id .'";';
			$res = mysql_query($query);
			
			// error performing query
			if (mysql_num_rows($res) > 0) {
					
				// Return data, as JSON
				$result = array();
			
				while ($row = mysql_fetch_array($res, MYSQL_BOTH)) {
					array_push($result, array(
						"id" => $row['id'], 
						"type_id" => $row['type_id'], 
						"title" => $row['title'], 
						//"info" => $row['info'], 
						"locality_id" => $row['locality_id'], 
						"points" => $row['points']
					));
				}
			
				$this->sendResponse(200, json_encode($result));
				return (true);
			
			} else
				return (false);
		}
		
		
		function jobSearch($fb_id) {
			
			// Return data, as JSON
			$result = array();   
			
			$query= 'SELECT * FROM `tblAntedJobs` WHERE `isActive` = "Y"';
			$ante_res = mysql_query($query);
			
			while ($ante_row = mysql_fetch_array($ante_res, MYSQL_BOTH)) { 
				
				$query = 'SELECT `name` FROM `tblApps` WHERE `id` ='. $ante_row['app_id'] .';';
			    $app_row = mysql_fetch_row(mysql_query($query));
			    $app_name = $app_row[0];
				
				if (!$app_name)
					$app_name = "";
					
					
				$query = 'SELECT `tblImages`.`id`, `tblImages`.`type_id`, `tblImages`.`url` FROM `tblImages` INNER JOIN `tblJobsImages` ON `tblImages`.`id` = `tblJobsImages`.`image_id` WHERE `tblJobsImages`.`job_id` = "'. $ante_row[0] .'" ORDER BY `tblJobsImages`.`sort`;';
				$img_res = mysql_query($query);
				
				$img_arr = array();
				while ($img_row = mysql_fetch_array($img_res, MYSQL_BOTH)) {
				   
					switch ($img_row['type_id']) {
						case "6":
							$img_url = $img_row['url'];
							break; 
							
						case "7":
							$nfo_url = $img_row['url'];
							break;
							
						case "4":
							$ico_url = $img_row['url'];
							break;
					}
				}
				
				$query = 'SELECT `name`, `display` FROM `tblObjects` WHERE `id` ='. $ante_row['object_id'] .';';
			    $obj_row = mysql_fetch_row(mysql_query($query));
				$obj_name = $obj_row[0]; 
				$obj_disp = $obj_row[1]; 
				
				
				$query = 'SELECT * FROM `tblJobMapPts` WHERE `id` ='. $ante_row['id'] .';';
				$coord_row = mysql_fetch_object(mysql_query($query));
				$coord_lat = $coord_row->latitude;
				$coord_long = $coord_row->longitude;   
					
	  	  		array_push($result, array(
		  		   "id" => $ante_row['id'], 
					"type_id" => $ante_row['type_id'], 
					"type_name" => $ante_row[0], 
					"object_name" => $obj_name, 
					"object_disp" => $obj_disp, 
					"title" => $ante_row['title'], 
					"info" => $ante_row['info'], 
					"score" => 5, 
					"slots" => $ante_row['slots'], 
					"points" => $ante_row['points'], 
					"image" => $img_url, 
					"ico" => $ico_url,
					"nfo" => $nfo_url,   
					"app" => $app_name, 
					"long" => $ante_row['longitude'], 
 					"lat" => $ante_row['latitude'] 
				));
			}
			
			unset($app_name);
		           
			$query = 'SELECT * FROM `tblJobs` WHERE `isActive` = "Y" AND `slots` > 0;';
			$job_arr = mysql_query($query);
			
			
			
			while ($job_row = mysql_fetch_array($job_arr, MYSQL_BOTH)) {
				$score = 0;
				
				$query = 'SELECT `name` FROM `tblApps` WHERE `id` ='. $job_row['app_id'] .';';
			    $app_row = mysql_fetch_row(mysql_query($query));
			    $app_name = $app_row[0];
				
				if (!$app_name)
					$app_name = "";
				
				$query = 'SELECT `tblImages`.`id`, `tblImages`.`type_id`, `tblImages`.`url` FROM `tblImages` INNER JOIN `tblJobsImages` ON `tblImages`.`id` = `tblJobsImages`.`image_id` WHERE `tblJobsImages`.`job_id` = "'. $job_row[0] .'" ORDER BY `tblJobsImages`.`sort`;';
				$img_res = mysql_query($query);
				
				$img_arr = array();
				while ($img_row = mysql_fetch_array($img_res, MYSQL_BOTH)) {
					
				   
					switch ($img_row['type_id']) {
						case "1":
							$ico_url = $img_row['url'];
							break;
							
						case "2":
							$img_url = $img_row['url'];
							break;
							
					    case "3":
						  	$nfo_url = $img_row['url'];
						    break;
					}
					
					/*array_push($img_arr, array(
						"id" => $img_row['id'], 
						"url" => $img_row['url']
					));*/
				}
				
				$query = 'SELECT `name`, `display` FROM `tblObjects` WHERE `id` ='. $job_row['object_id'] .';';
			    $obj_row = mysql_fetch_row(mysql_query($query));
				$obj_name = $obj_row[0];
				$obj_disp = $obj_row[1];
				
				$query = 'SELECT * FROM `tblJobMapPts` WHERE `id` ='. $job_row['id'] .';';
				$coord_row = mysql_fetch_object(mysql_query($query));
				$coord_lat = $coord_row->latitude;
				$coord_long = $coord_row->longitude;
				
				$query = 'SELECT `locality_id`, `age`, `sex`, `friends`, `edu_id` FROM `tblUsers` WHERE `fb_id` = "'. $fb_id .'";';
				$user_row = mysql_fetch_row(mysql_query($query));
				
				
				$query = 'SELECT `name` FROM `tblJobTypes` WHERE `id` = "'. $job_row['type_id'] .'";';
				$type_row = mysql_fetch_row(mysql_query($query));
				
				if ($user_row[1] >= $job_row['age_min'])
					$score++;

				else
					$score = -666;
					
				
				if ($user_row[3] >= $job_row['friends'])
					$score++;

				else
					$score--;// = -666;
					
				
                if ($user_row[4] >= $job_row['edu_id'])
					$score++;

				else
					$score--;// = -666;
					
					
				if ($job_row['sex'] == "N" || $job_row['sex'] == $user_row[2])
						$score++;

				$query = 'SELECT * FROM `tblJobsLocalities` WHERE `job_id` ='. $job_row['id'] .';';
				$locality_res = mysql_query($query);
				$isFound = false;

				while ($locality_row = mysql_fetch_array($locality_res, MYSQL_BOTH)) {
					if ($locality_row['locality_id'] == $user_row[0])
						$isFound = true;
				}

				if ($isFound)
					$score++;

				else
					$score--;// = -666;
					      
				if ($score > 0) {
					
					if ($score == 5)
						$score--;
						
					$score += (rand(0, 100) / 100);
					
					array_push($result, array(
						"id" => $job_row['id'], 
						"type_id" => $job_row['type_id'], 
						"type_name" => $type_row[0], 
						"object_name" => $obj_name, 
						"object_disp" => $obj_disp, 
						"title" => $job_row['title'], 
						"info" => $job_row['info'], 
						"score" => $score, 
						"distance" => rand(11, 59), 
						"slots" => $job_row['slots'], 
						"points" => $job_row['points'], 
						"image" => $img_url, 
						"ico" => $ico_url,
						"nfo" => $nfo_url,   
						"app" => $app_name, 
						"long" => $job_row['longitude'], 
						"lat" => $job_row['latitude']
					));  
				}
			}	
			
			$this->sendResponse(200, json_encode($result));
			return (true);	
		}
		
		
		
		function updateState($user_id, $job_id, $status_id) {

			$query = 'INSERT INTO `tblUsersJobs` (';
			$query .= '`id`, `user_id`, `job_id`, `status_id`, `added`) ';
			$query .= 'VALUES (NULL, "'. $user_id .'", "'. $job_id .'", "'. $status_id .'", CURRENT_TIMESTAMP);';
			$res = mysql_query($query);
			
			$query = 'SELECT `slots` FROM `tblJobs` WHERE `id` = "'. $job_id .'"';
			$res = mysql_query($query);

			if ($res) {

				$row = mysql_fetch_row(mysql_query($query));
				$slots_tot = $row[0];

				if ($status_id == 6)
					$slots_tot--;
                                
				else if ($status_id == 8)
					$slots_tot++;


				$query = 'UPDATE `tblJobs` SET `slots` ='. $slots_tot .' WHERE `id` ='. $job_id .';';
				$res = mysql_query($query);
			}
			
	   
 			// return data, as JSON
			$result = array("slots" => $slots_tot);
            $this->sendResponse(200, json_encode($result));
			return (true);
		}
	}
	
	$jobs = new Jobs;
	
	
	if (isset($_POST["action"])) {
		
		switch ($_POST["action"]) {
			case "0": 
				if (isset($_POST["fbid"])) {
					 $fb_id = $_POST["fbid"];
					 $userInfo_arr = $jobs->jobSearch($fb_id);
				}
				break;
				
				
			case "1": 
				if (isset($_POST["fbid"]) && isset($_POST["job_id"])) {
				 	$userInfo_arr = $jobs->updateState($_POST["fbid"], $_POST["job_id"], 6);
				}
				break;
				
			case "2":
			    if (isset($_POST["fbid"]) && isset($_POST["job_id"])) {
				 	$userInfo_arr = $jobs->updateState($_POST["fbid"], $_POST["job_id"], 7);
				}
				break;
		}
	}   
	
	//if (isset($_POST["fbid"])) {
	//    $fb_id = $_POST["fbid"];
	//	$userInfo_arr = $jobs->jobSearch($fb_id);
	//}
?>
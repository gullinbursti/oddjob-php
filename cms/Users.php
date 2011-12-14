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
		
			//if ($this->db_conn) {
			//	mysql_close($this->db_conn);
			//	$this->db_conn = null;
			//}
		}
	
		/**
		 * This service returns all users available
		 * @returns recordset
		 */
		function allUsers() {

			$query = 'SELECT * FROM `tblUsers`;';
			$res = mysql_query($query);

			// error performing query
			if (mysql_num_rows($res) > 0)
				return ($res);

			else
				return (null);
		}
		
		
		/**
		 * This service returns all users w/in a locality
		 * @returns recordset
		 */
		function usersByLocalityID($id) {

			$query = 'SELECT * FROM `tblUsers` WHERE `locality_id` = "'. $id .'";';
			$res = mysql_query($query);

			// error performing query
			if (mysql_num_rows($res) > 0)
				return ($res);

			else
				return (null);
		}
		
		


		/**
		 * This service returns user data from a Facebook id
		 * @returns recordset
		 */
		function userInfoByFacebookID($id) {
			
			$query = 'SELECT `id`, `fName`, `lName` FROM `tblUsers` WHERE `fbid` = "'. $id .'";';
			$row = mysql_fetch_row(mysql_query($query));
			
			if ($row) {
				// Return data, as JSON
				$result = array(
					"id" => $row[0], 
					"fName" => $row[1], 
					"lName" => $row[2]);
			
				return ($result);
			}
			
			return (null);
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

				return ($result);
			}
			
			return (null);

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

				return ($result);			
			}
			
			return (null);
		}
		
		
		
	
		/**
		 * This service returns id w/in radius
		 * @returns recordset
		 */
		function userIDByRadius($long, $lat, $r) {
			
			$degees = $r / 69;
			
			
			
			
			return (true);
		}
		
		
		
		function userIDByEducationID($edu_id) {
			$query = 'SELECT `id` FROM `tblUsers` WHERE `edu_id` = "'. $edu_id .'";';
			$row = mysql_fetch_row(mysql_query($query));
			
			if ($row) {

				// Return data, as JSON
				$result = array(
					"id" => $row[0]);
			
				return ($result);
			}
			
			return (null);
		}
	}
	
?>
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
		
			//if ($this->db_conn) {
			//	mysql_close($this->db_conn);
			//	$this->db_conn = null;
			//}
		}
		
	
		
		/**
		 * This service returns all jobs available
		 * @returns recordset
		 */
		function allJobs() {

			$query = 'SELECT * FROM `tblJobs` WHERE `isActive` ="Y";';
			$res = mysql_query($query);
			
			// error performing query
			if (mysql_num_rows($res) > 0)
				return ($res);
			
			else
				return (null);
		}
		
		
		/**
		 * This service returns jobs available per locality
		 * @returns recordset
		 */
		function jobsByLocalityID($l_id) {

			$query = 'SELECT * FROM `tblJobs` WHERE `locality_id` ="'. $l_id .'" AND `isActive` ="Y";';
			$res = mysql_query($query);
			
			// error performing query
			if (mysql_num_rows($res) > 0)
				return ($res);
			
			else
				return (null);
		}
	}
?>
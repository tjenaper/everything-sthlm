<?php

	class DB {

		private static $instance = null, $prev_results = [], $mysqli;
		public static $con;
		private $db = ['url' => 'localhost', 'user' => 'root', 'password' => '', 'database' => 'phlogger'];

		private function __construct() {

			self::$mysqli = new mysqli($this->db['url'], $this->db['user'], $this->db['password'], $this->db['database']);
			self::$con = self::con();

		}

		private static function getInstance() {
		
			if (self::$instance === null) {
				
				self::$instance = new DB();

			}
 
			return self::$instance;

		}

		private static function con() {
				
			return self::$mysqli;
		
		}

		// funktion för att tvätta det som skickas från ett formulär med POST, används inte utanför klassen
		public function clean($input) {

			self::getInstance();

			if(is_array($input)) {
			
				// en array med tvättade värden som matas ut
				$clean_data = [];

				// loopa igenom $_POST
				foreach($input as $key => $value) {
					$clean_data[$key] = self::$mysqli->real_escape_string($value);
				}

			}

			else {

				$clean_data = self::$mysqli->real_escape_string($input);

			}
		
			return $clean_data;

		}

		public static function query($query, $single = false) {

			self::getInstance();

			$output = [];
			$hash_query = hash('sha1', $query);

			if(array_key_exists($hash_query, self::$prev_results)) {
				$output = self::$prev_results[$hash_query];
			}

			else {

				if($res = self::$mysqli->query($query)) {

					if($single) {

						$data = $res->fetch_assoc();

						$output = $data;

					}

					else {

						$output = [];

						while($data = $res->fetch_assoc()) {
							$output[] = $data;
						}

					}

					self::$prev_results[$hash_query] = $output;

				}

				if(!$res) {

					echo self::$mysqli->error; die();
				
				}

			}

			return $output;

		}

	}


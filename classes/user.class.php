<?php

class User {

	private $id, $firstName, $lastName, $email, $phone, $address_street, $address_zip, $address_city;
	private static $user;

	function __construct($id){
		$cleanId = DB::clean($id);
		$sql = "SELECT firstname, lastname, email, phone, address_street, address_zip, address_city
				FROM user
				WHERE id = ".$cleanId;
				
		$data = DB::query($sql, TRUE);
		$this->id 				= $cleanId;
		$this->firstName 		= $data["firstname"];
		$this->lastName 		= $data["lastname"];
		$this->email 			= $data["email"];
		$this->phone 			= $data["phone"];
		$this->address_street 	= $data["address_street"];
		$this->address_zip 		= $data["address_zip"];
		$this->address_city 	= $data["address_city"];

	}

	function __get($var) {
		if ($this->$var) {
			return $this->$var;
		}
	}

	function __isset($var) { //behövs för att Twig ska kunna använda magisk get.
		if ($this->$var) {
			return TRUE; 
		}
		return FALSE; 
	}


	function login($input){
		$cleanInput = DB::clean($input);
		$scrambledPassword = hash_hmac("sha1", $cleanInput["password"], "dont put baby in the corner");
		$sql = "SELECT id
				FROM user
				WHERE email = '".$cleanInput["email"]."'
				AND password = '".$scrambledPassword."'
				";
		$data = DB::query($sql, TRUE); //TRUE gör att man bara får tillbaka en rad

		if($data){
			$_SESSION["everythingSthlm"]["userId"] = $data["id"];
			self::$user = new User($data["id"]);

		}
	}







}


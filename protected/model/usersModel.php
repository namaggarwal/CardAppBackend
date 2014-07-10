<?php

class usersModel{

	
	private $dbConn;


	function usersModel($dbConn){

		$this->dbConn = $dbConn;		
	}

	public function insertNewUser($phone,$password){

		$query = "Insert into ".config::USER_TABLE. " (phone,password) VALUES ( '".$phone."',password('".$password."') );";		


		$res = $this->dbConn->query($query);

		$output = array();

		if(!$res){
			$output["REQUEST_STATUS"] = 2;
			$output["REQUEST_MESSAGE"] = "This number is already registered";
		}else{

			$output["REQUEST_STATUS"] = 1;
			$output["id"] = $this->dbConn->insert_id;
		}

		return $output;

	}


	public function validateUser($phone,$password){

		$query = "Select * from ".config::USER_TABLE. " where phone='".$this->dbConn->escape_string($phone)."' and password=password('".$this->dbConn->escape_string($password)."') ;";
		
		$res = $this->dbConn->query($query);

		$output = array();
		if(!$res){

			$output["REQUEST_STATUS"] = 2;
			$output["REQUEST_MESSAGE"] = "There is some issue with server";
		}else if($res->num_rows == 1){

			$user = $res->fetch_object();
			$output["REQUEST_STATUS"] = 1;
			$output["id"] = $user->id;
			

		}else{

			$output["REQUEST_STATUS"] = 2;
			$output["REQUEST_MESSAGE"] = "Phone number and password does not match";

		}

		return $output;		

	}

}
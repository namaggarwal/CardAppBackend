<?php

class usersModel{

	
	private $dbConn;


	function usersModel($dbConn){

		$this->dbConn = $dbConn;		
	}

	public function insertNewUser($phone,$password){

		$query = "Insert into ".config::USER_TABLE. " (phone,password) VALUES ( '".$phone."',password('".$password."') );";		

		$this->dbConn->query($query);

	}

}
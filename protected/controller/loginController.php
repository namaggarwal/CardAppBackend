<?php

require_once(dirname(dirname(__FILE__))."/model/usersModel.php");

//Controller class for the Register page requests
class loginController extends baseController{

	private $data;


	public function init($data){

		$this->data = $data;
		$this->data["CountryCode"] = $_REQUEST["cc"];
		$this->data["PhoneNumber"] = $_REQUEST["pn"];
		$this->data["Password"] = $_REQUEST["pwd"];		
		$this->login();

	}


	private function login(){


		//$this->prettyPrint($this->data);

		/// Verify that parameters are right

		$output=array();
		$err = FALSE;
		
		if(!(strlen($this->data["CountryCode"]) > 2 && $this->data["CountryCode"][0] == "+")){

			$output["REQUEST_STATUS"] = 2;
			$output["REQUEST_MESSAGE"] = "Country Code is not correct";
			$err = TRUE;
			
		}else if(!is_numeric($this->data["PhoneNumber"])){

			$output["REQUEST_STATUS"] = 2;
			$output["REQUEST_MESSAGE"] = "Phone number can only contain numbers";
			$err = TRUE;

		}else if(strlen($this->data["Password"]) <6 ) {

			$output["REQUEST_STATUS"] = 2;
			$output["REQUEST_MESSAGE"] = "Password should be atleast 6 digits";
			$err = TRUE;

		}


		if($err){

			$this->sendResponse($output);
		}else{

			$phone  = substr($this->data["CountryCode"], 1);
			$phone .= $this->data["PhoneNumber"];

			$this->dbConn = $this->acquireDbConn();
			$usersModelObj = new usersModel($this->dbConn);

			$output = $usersModelObj->validateUser($phone,$this->data["Password"]);
			if($output["REQUEST_STATUS"] == 1){
				$output["cc"] = $this->data["CountryCode"];
				$output["pn"] = $this->data["PhoneNumber"];			
				$output["cpn"] = $phone;
			}


			$this->sendResponse($output);	
		}

	}
}
<?php

require_once(dirname(dirname(__FILE__))."/model/transModel.php");

//Controller class for the Register page requests
class transController extends baseController{

	private $data;


	public function init($data){

		$this->data = $data;		
		$this->data["id"] = isset($_REQUEST["id"])?$_REQUEST["id"]:null;
		$this->data["amt"] = isset($_REQUEST["amt"])?$_REQUEST["amt"]:null;
		$this->data["recid"] = isset($_REQUEST["recid"])?$_REQUEST["recid"]:null;
		$this->data["sendid"] = isset($_REQUEST["sendid"])?$_REQUEST["sendid"]:null;
		$this->data["sendcardid"] = isset($_REQUEST["sendcardid"])?$_REQUEST["sendcardid"]:null;
		$this->data["reccardid"] = isset($_REQUEST["reccardid"])?$_REQUEST["reccardid"]:null;
		$this->data["till"] = isset($_REQUEST["till"])?$_REQUEST["till"]:null;		
		$this->data["act"] = isset($_REQUEST["act"])?$_REQUEST["act"]:null;
		

		switch($this->data["act"]){


			case "add":
					$this->addNewTransaction();
				break;
			case "list":
					$this->getAllTransactions();

			default:
				$output = array();
				$output["REQUEST_STATUS"] = 2;
				$output["REQUEST_MESSAGE"] = "No such action";
				$this->sendResponse($output);
				break;

		}
		

	}


	private function addNewTransaction(){


		/// Verify that parameters are right

		$output=array();
		$err = FALSE;
		

		if(!is_numeric($this->data["amt"])){

			$output["REQUEST_STATUS"] = 2;
			$output["REQUEST_MESSAGE"] = "Incorrect amount value";
			$err = TRUE;

		}else if(!is_numeric($this->data["recid"])){

			$output["REQUEST_STATUS"] = 2;
			$output["REQUEST_MESSAGE"] = "Receiver id not found";
			$err = TRUE;

		}else if(!is_numeric($this->data["sendid"])){

			$output["REQUEST_STATUS"] = 2;
			$output["REQUEST_MESSAGE"] = "Sender id not found";
			$err = TRUE;

		}else if(!is_numeric($this->data["sendcardid"])){

			$output["REQUEST_STATUS"] = 2;
			$output["REQUEST_MESSAGE"] = "Sender card id not found";
			$err = TRUE;

		}else if(!is_numeric($this->data["reccardid"])){

			$output["REQUEST_STATUS"] = 2;
			$output["REQUEST_MESSAGE"] = "Receiver card id not found";
			$err = TRUE;

		}



		if($err){

			$this->sendResponse($output);
		}else{
			
			$this->dbConn = $this->acquireDbConn();
			$transModelObj = new transModel($this->dbConn);	

			$output = $transModelObj->addNewTransaction($this->data["amt"],$this->data["recid"],$this->data["sendid"],$this->data["sendcardid"],$this->data["reccardid"]);

			$this->sendResponse($output);	
		}

	}



	private function getAllTransactions(){


		$err = FALSE;
		$output = array();

		if(!is_numeric($this->data["recid"])){

			$output["REQUEST_STATUS"] = 2;
			$output["REQUEST_MESSAGE"] = "Receiver id not found";
			$err = TRUE;

		}else if(!is_numeric($this->data["till"])){

			$output["REQUEST_STATUS"] = 2;
			$output["REQUEST_MESSAGE"] = "Invalid request";
			$err = TRUE;

		}


		if($err){

			$this->sendResponse($output);
		}else{
			
			$this->dbConn = $this->acquireDbConn();
			$transModelObj = new transModel($this->dbConn);	

			$output = $transModelObj->getTransactionForUser($this->data["recid"],$this->data["till"]);

			$this->sendResponse($output);	
		}



	}
}

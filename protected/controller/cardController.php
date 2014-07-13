<?php

require_once(dirname(dirname(__FILE__))."/model/usersModel.php");
require_once(dirname(dirname(__FILE__))."/model/cardsModel.php");

//Controller class for the Register page requests
class cardController extends baseController{

	private $data;


	public function init($data){

		$this->data = $data;		
		$this->data["id"] = isset($_REQUEST["id"])?$_REQUEST["id"]:null;
		$this->data["userid"] = isset($_REQUEST["userid"])?$_REQUEST["userid"]:null;
		$this->data["name"] = isset($_REQUEST["name"])?$_REQUEST["name"]:null;
		$this->data["num"] = isset($_REQUEST["num"])?$_REQUEST["num"]:null;
		$this->data["cvv"] = isset($_REQUEST["cvv"])?$_REQUEST["cvv"]:null;
		$this->data["valid"] = isset($_REQUEST["valid"])?$_REQUEST["valid"]:null;
		$this->data["act"] = isset($_REQUEST["act"])?$_REQUEST["act"]:null;
		$this->data["isdefault"] = isset($_REQUEST["isdefault"])?$_REQUEST["isdefault"]:null;
	

		switch($this->data["act"]){


			case "add":
					$this->addNewCard();
				break;

			default:
				$output = array();
				$output["REQUEST_STATUS"] = 2;
				$output["REQUEST_MESSAGE"] = "No such action";
				$this->sendResponse($output);
				break;

		}
		

	}


	private function addNewCard(){


		//$this->prettyPrint($this->data);

		/// Verify that parameters are right

		$output=array();
		$err = FALSE;
		
		if(!(is_numeric($this->data["num"]) && strlen($this->data["num"]) == 16)){

			$output["REQUEST_STATUS"] = 2;
			$output["REQUEST_MESSAGE"] = "Invalid card number";
			$err = TRUE;

		}else if($this->data["name"] == ""){

			$output["REQUEST_STATUS"] = 2;
			$output["REQUEST_MESSAGE"] = "Please specify name on the card";
			$err = TRUE;

		}else if($this->data["valid"] == ""){

			$output["REQUEST_STATUS"] = 2;
			$output["REQUEST_MESSAGE"] = "Please specify valid thru";
			$err = TRUE;

		}
		else if(!(is_numeric($this->data["cvv"]) && strlen($this->data["cvv"]) == 3)){

			$output["REQUEST_STATUS"] = 2;
			$output["REQUEST_MESSAGE"] = "Invalid cvv code";
			$err = TRUE;

		}else if(!is_numeric($this->data["userid"])){

			$output["REQUEST_STATUS"] = 2;
			$output["REQUEST_MESSAGE"] = "Invalid user id";
			$err = TRUE;

		}



		if($err){

			$this->sendResponse($output);
		}else{
			
			$this->dbConn = $this->acquireDbConn();
			$cardsModelObj = new cardsModel($this->dbConn);	

			$output = $cardsModelObj->addNewCard($this->data["userid"],$this->data["name"],$this->data["num"],$this->data["cvv"],$this->data["isdefault"],$this->data["valid"]);
			if($output["REQUEST_STATUS"] == 1){			   			   
			   $output["CARD"]["userid"] = $this->data["userid"];
			   $output["CARD"]["name"] = $this->data["name"];
			   $output["CARD"]["num"] = $this->data["num"];
			   $output["CARD"]["cvv"] = $this->data["cvv"];
			   $output["CARD"]["valid"] = $this->data["valid"];			   
			}


			$this->sendResponse($output);	
		}

	}
}

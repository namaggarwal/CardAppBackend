<?php

class cardsModel{

	
	private $dbConn;


	function cardsModel($dbConn){

		$this->dbConn = $dbConn;		
	}


	public function getAllCardsForUser($userid){


		$query = "Select * from ".config::CARD_TABLE. " where userid =".$this->dbConn->escape_string($userid).";";

		$res = $this->dbConn->query($query);

		$output = array();

		if(!$res){
			$output["REQUEST_STATUS"] = 2;
			$output["REQUEST_MESSAGE"] = "There is some error while fetching the current cards";
			return $output;
		}else{

			$output["REQUEST_STATUS"] = 1;			
		}

		$output["CARDS"] = array();

		while ($card = $res->fetch_object()) {

			$output["CARDS"][$card->id]              = array();
			$output["CARDS"][$card->id]["id"]        = $card->id;
			$output["CARDS"][$card->id]["userid"]    = $card->userid;
			$output["CARDS"][$card->id]["name"]      = $card->name;
			$output["CARDS"][$card->id]["num"]       = $card->num;
			$output["CARDS"][$card->id]["cvv"]       = $card->cvv;
			$output["CARDS"][$card->id]["isdefault"] = $card->isdefault;
			$output["CARDS"][$card->id]["valid"]     = $card->valid;
			
		}

		return $output;
	}

	public function addNewCard($userid,$name,$num,$cvv,$isdefault,$valid){


	    $cards = $this->getAllCardsForUser($userid);

	    if($cards["REQUEST_STATUS"] != 1){

			return $cards;	    	
	    }

	    if(count($cards["CARDS"]) == 0){
	    	$isdefault = 1;
	    }else{
    		$isdefault = 0;
	    }

		$query = "Insert into ".config::CARD_TABLE. " (name,num,cvv,valid,userid,isdefault) VALUES
				 ( '".$this->dbConn->escape_string($name)."',
				 	".$this->dbConn->escape_string($num).",
				 	".$this->dbConn->escape_string($cvv).",
				 	'".$this->dbConn->escape_string($valid)."',
				 	".$this->dbConn->escape_string($userid).",
				 	".$this->dbConn->escape_string($isdefault).");";

	
		$res = $this->dbConn->query($query);

		$output = array();

		if(!$res){
			$output["REQUEST_STATUS"] = 2;
			$output["REQUEST_MESSAGE"] = "Some error occured while adding the card.";
		}else{

			$output["REQUEST_STATUS"] = 1;
			$output["CARD"] = array();
			$output["CARD"]["id"] = $this->dbConn->insert_id;			
			$output["CARD"]["isdefault"] = $isdefault;
		}

		return $output;

	}

}


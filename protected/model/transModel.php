<?php

class transModel{

	
	private $dbConn;


	function transModel($dbConn){

		$this->dbConn = $dbConn;		
	}


	public function getTransactionForUser($userid,$till){	

		$query = "Select * from ".config::TRANS_TABLE. " where recid=".$userid." OR sendid = ".$userid." ORDER BY trantime DESC";


/*		SELECT 
IF(t.recid='14',1,2) as transtype
,t.amount
,c.num
,t.transtime
FROM 
carddb.trans t
inner join
carddb.cards c
on t.reccardid = c.id
WHERE (recid = 14
OR sendid =14)
AND t.transtime > '2014-07-13 00:00:00'
ORDER BY transtime DESC
;
*/
		$res = $this->dbConn->query($query);

		$output = array();

		

		return $output;
	}

	public function addNewTransaction($amount,$recid,$sendid,$sendcardid,$reccardid){


	    $query = "Insert into ".config::TRANS_TABLE. " (recid,sendid,sendcardid,reccardid,amount) VALUES
				 (  ".$this->dbConn->escape_string($recid).",
				 	".$this->dbConn->escape_string($sendid).",
				 	".$this->dbConn->escape_string($sendcardid).",
				 	".$this->dbConn->escape_string($reccardid).",
				 	".$this->dbConn->escape_string($amount).");";
				 	

	
		$res = $this->dbConn->query($query);

		$output = array();

		if(!$res){
			$output["REQUEST_STATUS"] = 2;
			$output["REQUEST_MESSAGE"] = "Some error occured while transacting.";
		}else{

			$output["REQUEST_STATUS"] = 1;
			$output["TRANSID"] = $this->dbConn->insert_id;
			
		}

		return $output;

	}

}


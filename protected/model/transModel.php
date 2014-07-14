<?php

class transModel{

	
	private $dbConn;


	function transModel($dbConn){

		$this->dbConn = $dbConn;		
	}


	public function getTransactionForUser($userid,$till){	
		
		$output = array();
		switch ($till) {

			//Today
			case 1:
				$startTime = date("Y-m-d 00:00:00");
				$endTime = date("Y-m-d 23:59:59");
				break;
			//Yesterday
			case 2:
				$startTime = date("Y-m-d 00:00:00",strtotime("-1 days"));
				$endTime = date("Y-m-d 23:59:59",strtotime("-1 days"));
				break;
			//This week
			case 3:
				//If today is monday
				if(date("w") == "1"){
					$startTime = date("Y-m-d 00:00:00");
					$endTime = date("Y-m-d 23:59:59");
				}else{
					$startTime = date("Y-m-d 00:00:00",strtotime("previous monday"));
					$endTime = date("Y-m-d 23:59:59",strtotime("previous monday"));
				}
				break;
			//This month
			case 4:
				$startTime = date("Y-m-01 00:00:00");
				$endTime = date("Y-m-t 23:59:59");
				break;
			//Last month
			case 5:
				$startTime = date("Y-m-d 00:00:00",strtotime("first day of last month"));
				$endTime = date("Y-m-d 23:59:59",strtotime("last day of last month"));
				break;
			default:				
				$output["REQUEST_STATUS"] = 2;
				$output["REQUEST_MESSAGE"] = "Invalid data provided.";
				return $output;
				break;
		}

		$query =    "SELECT 
					t.id as id
					,IF(t.recid='".$userid."',1,2) as transtype
					,t.amount as amount
					,c.num as cardnum
					,c.name as cardname
					,t.transtime as transtime
					FROM 
					carddb.trans t
					inner join
					carddb.cards c
					on IF(t.recid=".$userid.",t.sendcardid,t.reccardid) = c.id
					WHERE (recid = ".$userid."
					OR sendid =14)
					AND t.transtime >= '".$startTime."'
					AND t.transtime <= '".$endTime."'
					ORDER BY transtime DESC
					;";
		
		$res = $this->dbConn->query($query);
		
		if(!$res){

			$output["REQUEST_STATUS"] = 2;
			$output["REQUEST_MESSAGE"] = "Some error occured while fetching transactions.";

		}else{

			$output["REQUEST_STATUS"] = 1;

			$output["TRANSSENT"] = array();
			$output["TRANSREC"] = array();
			$output["TOTALREC"] = 0;
			$output["TOTALSENT"] = 0;
			$output["TOTAL"] = 0;
			
			while($trans = $res->fetch_object()){

				$key = ($trans->transtype == 1)?"TRANSREC":"TRANSSENT";
				$output[$key][$trans->id]               = array();
				$output[$key][$trans->id]["id"]         = $trans->id;
				$output[$key][$trans->id]["transtype"]  = $trans->transtype;
				$output[$key][$trans->id]["amount"]     = $trans->amount;
				$output[$key][$trans->id]["cardnum"]    = $trans->cardnum;
				$output[$key][$trans->id]["cardname"]   = $trans->cardname;
				$output[$key][$trans->id]["transtime"]  = $trans->transtime;

				if($key == "TRANSREC"){
					$output["TOTALREC"] +=  $trans->amount;
				}else{
					$output["TOTALSENT"] +=  $trans->amount;
				}	


			}

			$output["TOTAL"] = $output["TOTALREC"] - $output["TOTALSENT"];

			
		}		

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


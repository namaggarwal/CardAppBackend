<?php
require_once(dirname(__FILE__)."\\Config.php");
require_once(dirname(__FILE__)."\\router.php");
require_once(dirname(__FILE__)."\\protected/controller/baseController.php");

class scraperApp{

	private $req_method;
	private $req_url;
	private $cont;
	private $contData = array();



	private function setRequestParams(){

		$this->req_method = strtoupper($_SERVER["REQUEST_METHOD"]);
		$this->req_url = str_replace(config::BASE_URL,"",$_SERVER['REQUEST_URI']);
		$index = strpos($this->req_url,"?");
		if($index){
			$this->req_url = substr($this->req_url, 0,$index);
		}

		$this->contData["REQUEST_METHOD"] = $this->req_method;
		$this->contData["REQUEST_URL"] = $this->req_url;


	}

	public function init(){

		$this->setRequestParams();
		$rout = new router($this->contData);
		$contName = $rout->getController();			
		require_once("./protected/controller/".$contName.".php");
		$this->cont = new $contName();
		$this->cont->init($this->contData);
	
	}


}


$app = new scraperApp();

try{
	$app->init();
}catch(Exception $exp){	
	$exp_code = $exp->getCode();
	if($exp_code == 404){		
		header("HTTP/1.0 404 Not Found");
	}
	print($exp->getMessage());
}

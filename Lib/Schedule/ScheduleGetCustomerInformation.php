<?php

class ScheduleGetCustomerInformation{

		private static $uniqueInstance;
		public $useModels=array("TblMstepCustomer");
		public $findValues=array();
		public $models;

		public static function getInstance(Controller &$controller){

        		if(!isset(static::$uniqueInstance[$controller->name])) static::$uniqueInstance[$controller->name]=new ScheduleGetCustomerInformation($controller);
        		return static::$uniqueInstance[$controller->name];
    	}

		function __construct(Controller &$controller){

				$this->models=new SetModel($this,$controller);
		}

		function isOnceGetValue(){

				return isset($this->findValues["data"]);
		}

		function findAllByClientId($client_id,$del_flg=0){

				if($this->isOnceGetValue()) return $this->findValues;

				$model=$this->models->getSettedModels()["TblMstepCustomer"];
				if(!$customers=$model->findAllByClientIdAndDelFlg($client_id,$del_flg)){
				
						$val=array();
						$this->findValues["data"]=$val;
						return $val;
				}

				$this->findValues["data"]=$customers;
				return $customers;
		}
}


?>

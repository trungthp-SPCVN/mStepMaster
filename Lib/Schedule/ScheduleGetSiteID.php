<?php

class ScheduleGetSiteID{

		public $controller;
		public $useModels=array("TblMstepSiteDetail");
		public $findValues=array();
		public $models;
		private $last_data_key;

		function __construct(Controller &$controller){

				$this->models=new SetModel($this,$controller);
				$this->controller=$controller;
		}

		function isOnceGetValue($data_key){

				return isset($this->findValues["data"][$data_key]);
		}

		private function __setDataKey($data_key){
		
				$this->last_data_key=$data_key;
		}

		function getLastFind(){

				return $this->findSiteDetailByKey($this->last_data_key);
		}

		function findSiteDetailByKey($data_key){

				if($data_key==$this->last_data_key) return $this->findValues["data"][$data_key];
				return false;
		}

		function findAllByCustomerId($customer_ids,$del_flg=0){

				$data_key=implode("",$customer_ids);
				if($this->isOnceGetValue($data_key)) return $this->findValues[$data_key];

				$this->__setDataKey($data_key);
				$model=$this->models->getSettedModels()["TblMstepSiteDetail"];
				if(!$site_details=$model->findAllByCustomerIdAndDelFlg($customer_ids,$del_flg)){
				
						$val=array();
						$this->findValues["data"][$data_key]=$val;
						return $val;
				}

				$this->findValues["data"][$data_key]=$site_details;
				return $site_details;
		}

		function getSiteID($client_id){

				$instance=ScheduleGetCustomerInformation::getInstance($this->controller);
				$customers=$instance->findAllByClientId($client_id);
				$customer_ids=Set::extract($customers,"{}.TblMstepCustomer.id");
				$site_details=$this->findAllByCustomerId($customer_ids,0);
				return $site_details;
		}
}


?>

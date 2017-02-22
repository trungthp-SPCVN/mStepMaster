<?php

class ScheduleLog{

		private static $uniqueInstance;
		public $controller;
		public $useModels=array("TblMstepScheduleLog");
		public $findValue=array();
		public $models;

		public static function getInstance(Controller &$controller){

        		if(!isset(static::$uniqueInstance[$controller->name])) static::$uniqueInstance[$controller->name]=new ScheduleLog($controller);
        		return static::$uniqueInstance[$controller->name];
    	}

		function __construct(Controller &$controller){

				$this->models=new SetModel($this,$controller);
				$this->controller=$controller;
		}

		public function getLastEditTime(){

				if(!empty($this->findValue)) return strtotime($this->findValue["TblMstepScheduleLog"]["edit_time"]);
				$this->findValue=$this->models->getSettedModels()["TblMstepScheduleLog"]->findOne();
				if(!$this->findValue) return false;
				return strtotime($this->findValue["TblMstepScheduleLog"]["edit_time"]);
		}

		public function timeInitialize($user_id,$last_edit_time=""){

				$edit_time="";
				if(!empty($last_edit_time)) $edit_time=date("Y/m/d H:i:s",$last_edit_time);
				return $this->models->getSettedModels()["TblMstepScheduleLog"]->timeInitialize(array(
				
						"id"       =>1,
						"user_id"  =>$user_id,
						"edit_time"=>$edit_time
				));
		}

		public function editTime($user_id,$current_time_ms,$time_key=""){

				if(empty($time_key)) $time_key=time();
				$bin_key=TimeoutInvestigationKeys::makeBinKey($user_id,$time_key);
				if(!$save=$this->models->getSettedModels()["TblMstepScheduleLog"]->editTime(array(
				
						"bin_key"          =>$bin_key,
						"user_id"          =>$user_id,
						"edit_time_expired_ms"=>$current_time_ms, //今の時間
						"id"               =>1
				))){

						return false;
				};

				return $save;
		}
}

?>

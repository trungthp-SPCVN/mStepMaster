<?php

class TimeoutInvestigationExec{

		public $controller;
		public $useModels=array("TblMstepScheduleLog");
		public $findValue=array();
		public $models;
		public $session;

		function __construct(Controller &$controller,Component &$session){

				$this->models=new SetModel($this,$controller);
				$this->controller=&$controller;
				$this->session=&$session;
		}

		public function checkLastEditTimeSesKey($unique_key,$user_id) {

				$ses_key =TimeoutInvestigationKeys::makeTimeSesKey($unique_key);
				$time_key=$this->session->read($ses_key);
				$res=$this->checkLastEditTime($user_id,$time_key);
				return $res;
		}

		static function checkSessionKey($user_id,$time_key,$bin_key){

				if(empty($time_key)) return false;

				if(!$dec=TimeoutInvestigationKeys::decBinKey($bin_key,$time_key)) return false;
				if($dec["user_id"]!=$user_id)  return false;
				if($dec["time_key"]!=$time_key) return false;
				return true;
		}

		public function checkLastEditTime($user_id,$time_key){

				if(!$log=$this->models->getSettedModels()["TblMstepScheduleLog"]->findOne()) return false;
				if($log["TblMstepScheduleLog"]["start_user_id"]!=$user_id) return false;
				$bin_key=TimeoutInvestigationKeys::makeBinKey($user_id,$time_key);
				if(!self::checkSessionKey($user_id,$time_key,$bin_key)) return false;
				return true;	
		}

		private function __getValue(){

				if(!empty($this->findValue)) return $this->findValue;
				$this->findValue=$this->models->getSettedModels()["TblMstepScheduleLog"]->findOne();
				return $this->findValue;
		}

		public function checkEffectiveTime(){

				$current_time_ms=TimeoutInvestigation::currentMsTime();
				$last_modified=$this->__getValue();
				return ($current_time_ms>($last_modified["TblMstepScheduleLog"]["edit_time_expired_ms"]+TimeoutInvestigation::effectiveTime()));
		}
}

?>

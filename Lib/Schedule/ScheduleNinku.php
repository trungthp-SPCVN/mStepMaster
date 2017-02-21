<?php

class ScheduleNinku{

		public $controller;
		public $useModels=array("TblMstepSiteWorker","TblMstepSiteSchedule");
		public $findValues=array();
		public $models;

		function __construct(Controller &$controller){

				$this->models=new SetModel($this,$controller);
				$this->controller=$controller;
		}

		static public function getNinkuInsertMakeAry($site_workers,$schedule_days=array()){

				$date_workers=array();
				foreach($site_workers as $k=>$v){

						$id         =$v["TblMstepSiteWorker"]["id"];
						$schedule_id=$v["TblMstepSiteWorker"]["schedule_id"];
						$worker_id  =$v["TblMstepSiteWorker"]["worker_id"];
						$date       =date("Ymd",strtotime($schedule_days[$schedule_id]));
						if(!isset($date_workers[$date])) $date_workers[$date]=array();
						if(!isset($date_workers[$date][$worker_id])){ 

								$date_workers[$date][$worker_id]["count"]=0;
								$date_workers[$date][$worker_id]["id"]   =array();
						}

						$date_workers[$date][$worker_id]["count"]++;
						$date_workers[$date][$worker_id]["id"][]=$id;
				}

				$updates=array();
				foreach($date_workers as $date=>$v){
				
						foreach($v as $user_id=>$_v){
						
								$ninku=1;
								$ids=$_v["id"];
								$base_par_ninku=round(1/$_v["count"],1);
								for($i=0;$i<$_v["count"];$i++){

										$is_last_roop=($i==($_v["count"]-1));
										$insert_ninku=($is_last_roop?$ninku:$base_par_ninku);
										$ninku-=$base_par_ninku;
										$id=array_shift($ids);
										$count=count($updates);
										$updates[$count]["id"]      =$id;
										$updates[$count]["man_hour"]=$insert_ninku;
								}
						}
				}

				return $updates;
		}

		function getNinkInsertAry($dates=array()){

				$model=$this->models->getSettedModels()["TblMstepSiteSchedule"];
				$schedules=$model->getSiteScheduleByDate($dates);
				if(empty($schedules)) return array();
	
				$schedule_days=Set::combine($schedules,"{n}.{$model->name}.id","{n}.{$model->name}.start_date");
				$schedule_ids =Set::extract($schedules,"{}.{$model->name}.id");
	
				$model=$this->models->getSettedModels()["TblMstepSiteWorker"];
				$model->unbindFully();
				if(!$site_workers =$model->findAllByScheduleIdAndDelFlg($schedule_ids,0)) return array();
				return self::getNinkuInsertMakeAry($site_workers,$schedule_days);
		}
}

?>

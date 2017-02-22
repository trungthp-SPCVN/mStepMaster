<?php

require_once "Error/ScheduleError.php";

class Output{

		public function __outputStatus($status){

				$error=Schedule\Errors::$errorStatus[$status];
				$output["errorNo"]=$status;
				$output["title"]  =$error["title"];
				$output["message"]=$error["message"];
				self::__outputNo($output);
		}

		static public function __outputNo($values = array()) {

				$values["status"]="NO";
				self::__output($values);
		}

		static public function __outputYes($values = array()) {

				$values["status"]="YES";
				self::__output($values);
		}

		static public function __output($res=array()){

				Configure::write("debug", 0);
				header("Access-Control-Allow-Origin: *");
				header("Content-type:application/json;charset=utf8");
				echo json_encode($res);
				exit;
		}
}

?>

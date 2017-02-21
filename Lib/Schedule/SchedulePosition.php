<?php

class SchedulePosition{

		static public function checkIFSamePosition($targets=array(),$position){

				$range=range(0,(SCHEDULE_BLOCK_NUM-1));
				if(!in_array($position,$targets) AND SCHEDULE_BLOCK_NUM>$position) return $position;

				$allow_positions=array_diff($range,$targets);
				if(1>count($allow_positions)) return false;
				return self::checkIFSamePositionNearValue($position,$allow_positions);
		}

		static public function checkIFSamePositionNearValue($position,$allow_positions,$base_position=""){

				if(empty($allow_positions)) return false;

				$next_position=($position+1);
				if(!is_numeric($base_position)) $base_position=$position;
				if(!in_array($next_position,$allow_positions)){

						if($next_position>SCHEDULE_BLOCK_NUM) $next_position=(0-1);
						return self::checkIFSamePositionNearValue($next_position,$allow_positions,$base_position);
				}

				return $next_position;
		}
}

?>

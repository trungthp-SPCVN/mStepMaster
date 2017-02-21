<?php

/**
 * Created by PhpStorm.
 * User: Edward
 * Date: 2/15/17
 * Time: 11:02 AM
 */

class TblMstepClientRequest extends AppModel {
	var $name='TblMstepClientRequest';
	
	var $belongsTo=[
		'Requester'=>[
			'className'=>'TblMstepMasterUser',
			'foreignKey'=>'requester'
		],
		'Creator'=>[
			'className'=>'TblMstepMasterUser',
			'foreignKey'=>'creator'
		]
	];
	
	public function getRequestByID($id){
		if(!$id) {return false;}
		
		$this->id=$id;
		return $this->read();
	}
}
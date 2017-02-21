<?php

/**
 * Created by PhpStorm.
 * User: Edward
 * Date: 2/15/17
 * Time: 11:00 AM
 */
class ClientRequestController extends AppController {
	var $name = "ClientRequest";
	var $uses = array('TblMstepClientRequest');
	
	public function beforeFilter(){}
	
	public function index(){
		$this->paginate=[
			'limit'=>10,
			'conditions'=>[
				'TblMstepClientRequest.del_flg'=>0
			]
		];
		
		$request=$this->paginate('TblMstepClientRequest');
//		v($request);
		$this->set(compact('request'));
	}
	
	public function add(){
		$this->render('edit');
	}
	
	public function edit($id){
		if(!$id or !is_numeric($id)) { return false; }
		
		$request=$this->TblMstepClientRequest->getRequestById($id);
		
		$this->set(compact('request'));
	}
	
	public function detail($id){
		if(!$id or !is_numeric($id)) { return false; }
		
		$request=$this->TblMstepClientRequest->getRequestById($id);
		
		$this->set(compact('request'));
	}
	
	public function save_process(){
		if(!$this->data) {return false;}
		$data=$this->data;
		if(!empty($data['id'])){
			$data['modified_date']=date('Y-m-d H:i:s');
		} else {
			$data['requester']=$this->Auth->user('id');
			$data['created_date']=date('Y-m-d H:i:s');
		}
		
		$status=$this->__save_request($this->data);
		Output::__outputYes();
	}
	
	public function __save_request($data){
		if(!$data) { return false; }
		if(!empty($data['id'])) {
			$this->TblMstepClientRequest->id=$data['id'];
			unset($data['id']);
		}
		
		return $this->TblMstepClientRequest->save($data);
	}
	
	public function __update_status(){
		
	}
}
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
	
	public function beforeFilter(){
		parent::beforeFilter();
		
		$this->page_title=__('Client Request', true);
	}
	
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
		if(!$this->request->is('ajax')){return false;}
		if(!$this->data) {return false;}
		$data=$this->data;
		$res['status']=__('');
		
		if(!empty($data['id'])){
			$data['modified_date']=date('Y-m-d H:i:s');
			$this->TblMstepClientRequest->id=$data['id'];
			unset($data['id']);
		} else {
			$data['requester']=$this->Auth->user('id');
			$data['created_date']=date('Y-m-d H:i:s');
			$this->TblMstepClientRequest->create();
		}
		
		$res=$this->TblMstepClientRequest->save($data);
		Output::__outputYes($res);
	}
	
	public function update_status() {
		if (!$this->request->is('ajax')) {
			return false;
		}
		if (!$this->data) {
			return false;
		}
		$res['message']=__('Status has beed updated success to '.$this->data['value'],true);
		
		Output::__outputYes($res);
	}
	public function delete() {
		if (!$this->request->is('ajax')) {
			return false;
		}
		if (!$this->data) {
			return false;
		}
		
		$res['message']=__('Request has been deleted success',true);
		
		Output::__outputYes($res);
	}
}
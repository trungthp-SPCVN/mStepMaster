<?php

/**
 * Created by PhpStorm.
 * User: Edward
 * Date: 2/15/17
 * Time: 11:00 AM
 *
 * @property TblMstepClientRequest $TblMstepClientRequest
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
	
	/**
	 * Only for add and edit
	 * @return bool
	 *
	 */
	public function save_process(){
		if(!$this->request->is('ajax')){return false;}
		if(!$this->data) {return false;}
		$data=$this->data;
		
		if(!empty($data['id'])){
			$data['modified_date']=date('Y-m-d H:i:s');
			$this->TblMstepClientRequest->id=$data['id'];
			unset($data['id']);
		} else {
			$data['requester']=$this->Auth->user('id');
			$data['created_date']=date('Y-m-d H:i:s');
			$this->TblMstepClientRequest->create();
		}
		
		// alway set status is Requested
		$data['status']='requested';
		
		$this->TblMstepClientRequest->set($data);
		
		if(!$this->TblMstepClientRequest->save()) {
			$res['message']=__('Cannot not save Client request, please try again',true);
			Output::__outputNo($res);
		}
		
		// save success
		$res['message']=__('Your request has been saved success');
		Output::__outputYes($res);
	}
	
	/**
	 * Update status
	 *
	 * @return bool
	 */
	public function update_status() {
		if (!$this->request->is('ajax')) {
			return false;
		}
		if (!isset($this->data['id']) or (int)$this->data['id']<=0) {
			return false;
		}
		
		// update request status
		$data=$this->data;
		
		
		// get reason to update
		$reason='';
		if(isset($data['reason'])) {
			$reason = $data['reason'];
			unset($data['reason']);
		}
		
		// get request id
		$request_id=$data['id'];
		unset($data['id']);
		
		// set time and editor information
		$data['modified_date']=date('Y-m-d H:i:s');
		
		// update request
		$this->TblMstepClientRequest->id=$request_id;
		$this->TblMstepClientRequest->set($data);
		
		if(!$this->TblMstepClientRequest->save($data)) {
			$res['message']=__('Cannot save Client Request, please try again',true);
			Output::__outputNo($res);
		}
		// save success
		$res['message']=__('Status has beed updated success to '.$data['status'],true);
		Output::__outputYes($res);
	}
	
	/**
	 * Change delete status to yes
	 *
	 * @return bool
	 */
	public function delete() {
		if (!$this->request->is('ajax')) {
			return false;
		}
		if (!isset($this->data['id']) or (int)$this->data['id']<=0) {
			return false;
		}
		
		// find client request
		$this->TblMstepClientRequest->id=$this->data['id'];
		$this->TblMstepClientRequest->set(array(
			'del_flg'=>1,
			'modified_date'=>date('Y-m-d H:i:s')
		));
		
		// delete client request
		if(!$this->TblMstepClientRequest->save()){
			$res['message']=__('Cannot delete this Client request, please try again', true);
			Output::__outputNo($res);
		}
		
		// delete success
		$res['message']=__('Request has been deleted success',true);
		Output::__outputYes($res);
	}
}
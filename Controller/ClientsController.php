<?php

/**
 * Created by PhpStorm.
 * User: Edward
 * Date: 1/9/17
 * Time: 5:06 PM
 */
class ClientsController extends AppController {
    var $uses = [
            'ClientProfile',
            'Clients'
    ];

	public function index(){
	    $this->ClientProfile->bindModel(array(
            'hasMany' => array(
                    'Clients' => array(
                            'className' => 'Clients',
                            'foreignKey' => 'id'
                    )
	            )
            )
        );
	    $order[] = "ClientProfile.created_date DESC";
	    $clients = $this->ClientProfile->findAll(null, null, $order);
	    
	    $old_config_debug = Configure::read('debug');
	    Configure::write('debug', 0);
// 	    foreach($clients as &$client){
//             if($conn = mysqli_connect($client['Clients'][0]['db_host'], $client['Clients'][0]['db_user'], $client['Clients'][0]['db_password'], $client['Clients'][0]['db_name'])){
//                 $client['Clients'][0]['db_status'] = "OK";
//             }else{
//                 $client['Clients'][0]['db_status'] = "Fail";
//             }
// 	    }
	    Configure::write('debug', $old_config_debug);
	    $this->set(compact('clients'));
	}
	
	public function detail($id = null){
	    if (!is_numeric($id)) {
	        throw new NotFoundException();
	    }
	    $this->ClientProfile->bindModel(array(
	            'hasMany' => array(
	                    'Clients' => array(
	                            'className' => 'Clients',
	                            'foreignKey' => 'id'
	                    )
	            )
           )
        );
	    
	    $client = $this->ClientProfile->findById($id);
	    $this->set(compact('client'));
	}
	
	function updateStatus(){
	    if($this->request->is("post")){
	        $post = $_POST;
	        if(isset($post['client_id']) && isset($post['status'])){
	            $this->Clients->updateAll(array("del_flg" =>$post['status']), array("id"=>$post['client_id']));
	            $res['status'] = "YES";
	            Output::__output($res);
	        }else{
	            $res['status'] = "NO";
	            Output::__output($res);
	        }
	    }
	}
	
	public function add($client_id = null){
	    $client = [];
	    
	    if(!empty($client_id)){
	        $this->ClientProfile->bindModel(array(
	                'hasMany' => array(
	                        'Clients' => array(
	                                'className' => 'Clients',
	                                'foreignKey' => 'id'
	                        )
	                )
	           )
            );
	        $client = $this->ClientProfile->findById($client_id);
	        
	        if(!$client || !is_numeric($client_id)){
	            throw new NotFoundException();
	        }
	    }
	    $this->set(compact('client'));
	}
	
	public function saveData(){
	    if($this->request->is("post")){
	        $post = $_POST;
	        
	        $datasource = $this->Clients->getDataSource();
	        $datasource->begin();
	        
	        if(!empty($post['client_id'])){
	            $this->ClientProfile->id = $post['client_id'];
	            $this->Clients->id = $post['client_id'];
	            
	            if(!$this->Clients->save($post)){
	                $res['status'] = "NO";
	                Output::__output($res);
	            }
	            
	            if(!$this->ClientProfile->save($post)){
	                $res['status'] = "NO";
	                Output::__output($res);
	            }
	        }else{
	            $this->Clients->create();
	            $this->ClientProfile->create();
	            
	            if(!$this->Clients->save($post)){
	                $res['status'] = "NO";
	                Output::__output($res);
	            }
	            
	            $client_id = $this->Clients->getLastInsertID();
	            $post['id'] = $client_id;
	             
	            if(!$this->ClientProfile->save($post)){
	                $res['status'] = "NO";
	                Output::__output($res);
	            }
	        }
	        
	        $datasource->commit();
	        
	        $res['status'] = "YES";
	        Output::__output($res);
	    }
	}
	
	public function checkConnectDB(){
	    if($this->request->is("post")){
	        $post = $_POST;
	        
	        $old_config_debug = Configure::read('debug');
	        Configure::write('debug', 0);
            if($conn = mysqli_connect($post['host'], $post['user'], $post['pass'], $post['name'], $post['port'])){
                $res['status'] = "OK";
            }else{
                $res['status'] = "Fail";
            }
            Output::__output($res);
	        Configure::write('debug', $old_config_debug);
	    }
	}
}
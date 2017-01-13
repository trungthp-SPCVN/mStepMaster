<?php

require_once "tsv.php";
require_once "output.php";
require_once "SetModel.php";

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package     app.Controller
 * @link        http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	var $ext = ".html";
	var $client_id;
	var $check_authentication;

	public $components = array(
		'Session',
		'Auth' => array(
			'loginRedirect' => array('controller' => 'client', 'action' => 'index'),
			'logoutRedirect' => array('controller' => 'users', 'action' => 'login'),
			'authenticate' => array(
				'Form' => array(
					'fields' => array('username' => 'login_id',
						'password' => 'login_pass'),
				),
				'all'=>['userModel'=>'TblMstepMasterUser'],
			),
			'authorize' => array('Controller'),
		),
		'Acl',
	);

	function __getLogPath(){

			$controller=$this->params["controller"];
			$action    =$this->params["action"];
			$log_path=LOGS."post".DS."{$controller}_{$action}.txt";
			return $log_path;
	}

	function beforeFilter() {

			parent::beforeFilter();

			if($this->data){

					$post=$this->data;
					$log_path=$this->__getLogPath();
					@file_put_contents($log_path,serialize($post));
			}

			if(!defined("UNIQUE_KEY")) define("UNIQUE_KEY",'sample_unique_key');

			$this->check_authentication = $this->Auth->user("authority") === "master";
			$this->set('role', $this->check_authentication);
	}

	function __output($res = array()) {

		Configure::write("debug", 0);
		Output::__output($res);
	}

	/**
	 * Determines if authorized.
	 *
	 * @author Nguyen Chat Hien
	 */
	public function isAuthorized($user) {

		// Admin can access every action
		if (isset($user['authority']) && $user['authority'] === 'master') {
			return true;
		}

		// Default deny
		throw new unAuthorizedException();
	}

	#
	# @author Kiyosawa
	# @date 2011/05/07 14:44:59
	function beforeRender() {

		parent::beforeRender();
	}

	function __arrangeAry($data = array(), $key) {

		$res = array();
		foreach ($data as $k => $v) {

			$__data = current($v);
			$res[$__data[$key]] = $__data;
			unset($res[$__data[$key]][$key]);
		}

		return $res;
	}

	function isPrimaryKey($data = array(), $key) {

		$res = array();
		foreach ($data as $k => $v) {

			$__data = current($v);
			$res[$__data[$key]] = $__data;
		}

		return $res;
	}

		function __multiInsert(Model $model,$inserts=array()){

				try{

						$model->multiInsert($inserts);

				}catch(Exception $e){

						$res["status"]=false;
						$res["message"]=$e->getMessage();
						return $res;
				}

				$res["status"]=true;
				return $res;
		}

		function unbindFully(){

				if(empty($this->uses)) return;
				foreach($this->uses as $k=>$model) $this->$model->recursive=-1;
		}

}

?>


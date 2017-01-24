<?php

/**
 * Created by PhpStorm.
 * User: Edward
 * Date: 1/9/17
 * Time: 5:06 PM
 */
class ClientController extends AppController {
//	var $name='Client';

	public function index(){
		$this->page_title=__('Client management');
		$this->page_desc=__('Manage all data about Client');
	}
}
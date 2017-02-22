<?php
/*
 * Copyright 2015 SPC Viet Nam Co., Ltd.
 * All right reserved.
 */

/**
 * @Author: Nguyen Chat Hien
 * @Date:   2016-08-17 14:28:44
 * @Last Modified by:   Nguyen Chat Hien
 * @Last Modified time: 2016-11-30 09:23:26
 */
class ErrorsController extends AppController {

	var $name = 'Errors';

	/**
	 * Determines if authorized.
	 *
	 * @param      <type>   $user   The user
	 *
	 * @return     boolean  True if authorized, False otherwise.
	 */
	public function isAuthorized($user) {

		// All registered users obligatory check access denied function
		if ($this->action === 'accessdenied') {
			return true;
		}

		return parent::isAuthorized($user);
	}

	public function beforeFilter() {

		parent::beforeFilter();
		$this->layout='error';
		$this->Auth->allow('error404', 'unAuthorizedException');
	}

	/**
	 * NotFoundException()
	 */
	public function error404() {}

	/**
	 * UnauthorizedException
	 */
	public function accessdenied() {}

}

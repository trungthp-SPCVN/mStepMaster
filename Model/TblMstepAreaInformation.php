<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
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
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppModel', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class TblMstepAreaInformation extends AppModel {

	var $name = "TblMstepAreaInformation";
	var $useTable = "tbl_mstep_area_informations";
	var $primaryKey = "id";

	public $hasMany = array(
		'TblMstepSiteDetail' => array(
			'className' => 'TblMstepSiteDetail',
			'foreignKey' => 'area_id',
			'conditions' => array('TblMstepSiteDetail.del_flg' => '0'),
			'order' => 'TblMstepSiteDetail.id DESC',
			'dependent' => true,
		),
	);

	/**
	 * Gets the preference.
	 *
	 * @return     array  The preference.
	 */
	public function getPref() {

		$options = array(
			'fields' => array('`TblMstepAreaInformation`.`pref_id`, `TblMstepAreaInformation`.`pref`'),
			'group' => '`TblMstepAreaInformation`.`pref_id`',
		);

		if (!$data = $this->find('all', $options)) {
			return array();
		}

		$res = array();
		foreach ($data as $k => $v) {

			$_v = $v["TblMstepAreaInformation"];
			$res[$_v["pref_id"]] = $_v["pref"];
		}

		return $res;
	}

	/**
	 * Gets the address.
	 *
	 * @return     array  The address.
	 */
	public function getAddress() {

		if (!$data = $this->findAll()) {
			return array();
		}

		$res = array();
		foreach ($data as $k => $v) {

			$_v = $v["TblMstepAreaInformation"];
			$res[$_v["id"]] = $_v["address1"];
		}
		return $res;
	}

}

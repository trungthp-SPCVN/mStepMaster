<?php
/*
 * Copyright 2015 SPCVN Co., Ltd.
 * All right reserved.
*/

/**
 * @Author: Nguyen Minh Hung
 * @Date:   2017-02-15 10:11:33
 * @Last Modified by:   Nguyen Minh Hung
 * @Last Modified time: 2017-02-15 10:11:33
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
class Clients extends AppModel{

    var $name = "Clients";
    var $useTable = "clients";
    var $primaryKey = "id";
}

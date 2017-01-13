<?php
/*
 * Copyright 2015 SPCVN Co., Ltd.
 * All right reserved.
*/

/**
 * @Author: Nguyen Chat Hien
 * @Date:   2016-09-15 10:11:33
 * @Last Modified by:   Nguyen Chat Hien
 * @Last Modified time: 2016-09-16 11:23:33
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
class TblMstepGroupsUser extends AppModel{

    var $name = "TblMstepGroupsUser";
    var $useTable = "tbl_mstep_group_users";
    var $primaryKey = "id";

}

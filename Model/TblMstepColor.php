    <?php
/*
 * Copyright 2015 SPCVN Co., Ltd.
 * All right reserved.
*/

/**
 * @Author: Nguyen Chat Hien
 * @Date:   2016-09-08 17:38:35
 * @Last Modified by:   Nguyen Chat Hien
 * @Last Modified time: 2016-10-05 00:55:25
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
class TblMstepColor extends AppModel{

        var $name = "TblMstepColor";
        var $useTable = "tbl_mstep_colors";
        var $primaryKey = "id";
}


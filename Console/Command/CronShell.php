<?php
/**
 * AppShell file
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
 * @since         CakePHP(tm) v 2.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * Application Shell
 *
 * Add your application-wide methods in the class below, your shells
 * will inherit them.
 *
 * @package       app.Console.Command
 */

App::uses('AppController','Controller');
App::uses('WeatherImportController','Controller');

class CronShell extends AppShell {

        #
        # @author Kiyosawa
        # @date
        public function startup(){

                parent::startup();
        }

    /**
     * Cron import Yahoo weather to database
     * @date 2016-8-26
     * @author Edward <duc.nguyen@spc-vn.com>
     */
        public function importWeatherYahoo() {

            $this->Controller=new WeatherImportController();
            $res=$this->Controller->beforeFilter();
            $res=$this->Controller->importWeatherYahoo();
            exit;
        }


}


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

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {

        function startTransaction(){

                $dataSource=$this->getDataSource();
                $dataSource->begin($this);
        }

        # @author Kiyosawa
        # @date
        function commitTransaction(){

                $dataSource=$this->getDataSource();
                $dataSource->commit($this);
        }

		public function unbindModelAll($reset=true){

				foreach(array("hasOne","hasMany","belongsTo","hasAndBelongsToMany") as $relation){

						$this->unbindModel(array($relation => array_keys($this->$relation)),$reset);
				}
		} 

        #
        # @author Kiyosawa
        # @date
        function rollbackTransaction(){

                $dataSource=$this->getDataSource();
                $dataSource->rollback($this);
        }

        function findAllFlat($w="",$f="",$o="",$l=""){

                $tmp = null;
                $data = $this->findAll($w,$f,$o,$l);
                foreach ($data as $k=>$v) {
                    $id = $v[$this->name][$this->primaryKey];
                    $tmp[$id] = array_shift($v);
                }//foreach
                return $tmp;
        }

        # 1.2->1.3
        # 2010/12/31 14:53:38 Akabane
        function findCount($conditions = null, $recursive = 0) {
            return $this->find('count', compact('conditions','recursive'));
        }

        function findOne($conditions=null,$fields=array(),$orders=null){

                return parent::find('first',array("conditions"=>$conditions,"fields"=>$fields,"order"=>$orders));
        }

        # 1.2->1.3
        # 2010/12/31 14:53:38 Akabane
        function findAll($w="",$f="",$o="",$l=""){
            return parent::find("all" , array("conditions"=>$w, "fields"=>$f, "order"=>$o, "limit"=>$l));
        }

        # 1.2->1.3
        # 2010/12/31 14:53:38 Akabane
        function del($id = null, $cascade = true) {
            //$oldDb = $this->useDbConfig;
            //$this->setDataSource('master');
            $return = parent::delete($id, $cascade);
            //$this->setDataSource($oldDb);
            return $return;

        }

        # 1.2->1.3
        # 2011/01/05 08:02:50
        function deleteAll($conditions, $cascade = true, $callbacks = false) {
            //$oldDb = $this->useDbConfig;
            //$this->setDataSource('master');
            $return = parent::deleteAll($conditions , $cascade , $callbacks);
            //$this->setDataSource($oldDb);
            //return $return;
        }

        # Akabane
        # 2010/12/31 05:55:58
        function save($data = null, $validate = true, $fieldList = array()){
            //$oldDb = $this->useDbConfig;
            //$this->setDataSource('master');
            return $return = parent::save($data, $validate, $fieldList);
            //$this->setDataSource($oldDb);
            //return $return;
        }

        # Akabane
        # 2010/12/31 05:55:58
        function updateAll($fields, $conditions = true){
            //$oldDb = $this->useDbConfig;
            //$this->setDataSource('master');
            $return = parent::updateAll($fields, $conditions);
            //$this->setDataSource($oldDb);
            //return $return;
        }

        /**
         * マルチインサート＆アップデート自動生成
         * $limit件毎に再帰的にインサート処理を行う
         *
         * @param Array $data       インサート用配列
         * @param Multi $primary    プライマリーキー(配列でも文字列でも可)
         * @param Int   $limit      何件毎に再帰的にインサートをするか
         * @param Array $update     アップデート用のデータ(自動取得するので基本的にいらない。再帰用)
         * @param Array $clm        保存用のカラム(自動取得するので基本的にいらない。再帰用)
         *
         * @author kumakura
         * @date 2008/12/26 16:00:00
         */


        function multiInsert($data = array() , $primary = "id" , $limit = 1000 , $update = array() , $clm = array()){
            //$oldDb = $this->useDbConfig;
            //$this->setDataSource('master');
            $return = $this->_multiInsert($data , $primary , $limit , $update , $clm);
            //$this->setDataSource($oldDb);
            return $return;
        }
        function _multiInsert($data = array() , $primary = "id" , $limit = 1000 , $update = array() , $clm = array()){


            $sql = "INSERT INTO {$this->useTable} (";
            # カラム取得
            if(!$clm){
                $map_data = current($data);
                ksort($map_data); # 2010/12/19 21:15:05 位置ぞろえ
                foreach($map_data as $k=>$v)    $clm[$k] = "`{$k}`";
                if($this->hasField(array("created")))   $clm["created"]     = "`created`";
                if($this->hasField(array("modified")))  $clm["modified"]    = "`modified`";
            }
            $sql.= implode(",",$clm).") VALUES ";
            # 実データをインサート用文字列に変換
            $i = 1;
            foreach($data as $k=>$v){
                # 2010/12/19 21:15:18 位置ぞろえ
                ksort($v);
                foreach($v as $_v)  $in[] = "'$_v'";
                $str = "(".implode(",",$in);
                $str.= (!isset($v["created"]) AND $this->hasField(array("created"))) ? ",NOW()" : "";
                $str.= $this->hasField(array("modified")) ? ",NOW()" : "";
                $str.= ")";
                $insert[] = $str;
                array_shift($data);
                unset($in);
                if($i == $limit)    break;
                $i++;
            }
            $sql.= implode(",",$insert)."\n";
            # アップデート用文字列生成
            if(!$update){
                foreach($clm as $k=>$v){
                    //プライマリーキーやユニークキーはアップデート対象にしない。文字列でも、配列でも指定可
                    if(is_string($primary)){
                        if($primary == $k)  continue;
                    } elseif (is_array($primary)){
                        if(in_array($k,$primary)) continue;
                    }
                    //追加日は飛ばす。
                    if ($k == "created" or $k == "modified")    continue;
                    $update[] = "{$k} = VALUES({$k})";
                }
            }
            if($update){
                $sql.= " ON DUPLICATE KEY UPDATE ";
                $sql.= implode(" , ",$update);
                $sql.= $this->hasField(array("modified")) ? ",modified = NOW()" : "";
            }
            # インサート＆アップデート
            //v($sql);

            $this->query($sql);
            unset($insert,$sql);
            # 再帰的に
            if($data){
                $this->_multiInsert($data , $primary , $limit , $update , $clm);
            }
        }

    # 汎用的なメソッド
    # ------------------------------------------------------------------------------------------------------

        /**
         * データベースの設定を変更
         * 2009/01/03 20:45:31
         */
        function __construct($id = false, $table = null, $ds = null){
            parent::__construct($id, $table, $ds);
        }

        /**
         * リレーションを
         *
         * var $hasMany_full
         * var $belongsTo_full
         * var $hasAndBelongsToMany_full
         *
         * にて指定したものに変更される
         * $hasMany と $hasMany_full　などは当然共存可能。切り替えができるという事
         */
        function bindFully() {
            $this->bindModel(array('hasMany' => $this->hasMany_full,
                                     'hasAndBelongsToMany' => $this->hasAndBelongsToMany_full,
                                     'belongsTo' => $this->belongsTo_full));
        }

        /**
         * 全てのリレーションを解除
         *
         */
        function unbindFully() {
            $unbind = array();
            foreach ($this->belongsTo as $model=>$info) {
                $unbind['belongsTo'][] = $model;
            }
            foreach ($this->hasOne as $model=>$info) {
                $unbind['hasOne'][] = $model;
            }
            foreach ($this->hasMany as $model=>$info) {
                $unbind['hasMany'][] = $model;
            }
            foreach ($this->hasAndBelongsToMany as $model=>$info) {
                $unbind['hasAndBelongsToMany'][] = $model;
            }
            $this->unbindModel($unbind);
        }

        # tbl_userで始まるテーブル一覧を取得する
        # @author Akabane
        # @date 2011/09/10 18:38:27
        function getTableList_TblUser(){

            $r = $this->query("show tables");
            foreach($r as $k=>$v){
                $table_name = ( array_shift($v["TABLE_NAMES"]) );

                if(preg_match("#^tbl_user#" , $table_name )){
                    if($table_name != "tbl_users"){
                        $res[] = $table_name;
                    }
                }
            }//foreach
            return ($res);
        }

        #■検索条件の設定
        #■各フィールドの設定は各メソッドに任せる
        function addOptionSearch(&$w,$option){

                foreach($option as $k=>$v){

                        $k=ucfirst($k);
                        if(!method_exists($this,"set{$k}")) continue;

                        $method="set{$k}";
                        $this->{$method}($v,$w);
                }
        }

        #■データ取得
        #■findAllよりこっち使う
        function getDataOne($condition=array(),$option=array()){

                $w=array();
                $this->addOptionSearch($w,$condition);

                $options["conditions"]=$w;
                $options["fields"]=(isset($option["fields"]))?$option["fields"]:"";
                $options["limit"] =(isset($option["limit"])) ?$option["limit"]:"";
                $options["order"] =(isset($option["order"])) ?$option["order"]:"";
                $options["offset"]=(isset($option["offset"]))?$option["offset"]:"";
                $args=array_slice(func_get_args(),2);
                array_unshift($args,$options);
                array_unshift($args,"first");
                return call_user_func_array(array($this,'find'),$args);
        }

        #■データ取得
        #■findAllよりこっち使う
        function getDataAll($condition=array(),$option=array()){

                $options=$w=array();
                $this->addOptionSearch($w,$condition);

                $options["conditions"]=$w;
                $options["fields"]=(isset($option["fields"]))?$option["fields"]:"";
                $options["limit"] =(isset($option["limit"])) ?$option["limit"]:"";
                $options["order"] =(isset($option["order"])) ?$option["order"]:"";
                $options["offset"]=(isset($option["offset"]))?$option["offset"]:"";

                $args=array_slice(func_get_args(),2);
                array_unshift($args,$options);
                array_unshift($args,"all");
                return call_user_func_array(array($this,"find"),$args);
        }

        #■グループの設定
        function setGroup($field,&$w=array()){

                if(is_array($field)) $field=implode(",",$field);
                $w[]="1=1 group by {$field}";
        }

        #■DBバックアップ
        function mysqlDump($backup_dir){

                $max_backup_file_num=BACKUP_MAX_FILE_SAVE_NUM;
                $current_files=glob($backup_dir."*");
                $current_file_count=count($current_files);
                $remove_backup_file="";
                if($current_file_count>=$max_backup_file_num){

                        $time=min(array_map(function($a){

                                $pathinfo=pathinfo($a);
                                $filename=$pathinfo["filename"];
                                $e=explode("_",$filename);
                                return $e[count($e)-1];

                        },$current_files));

                        $remove_backup_file=$backup_dir."mysqldump_{$time}.sql";
                        unlink($remove_backup_file);
                }

                $save_file="mysqldump_".date("YmdHis").".sql";
                $backup_file_path=$backup_dir.$save_file;

                $mysqldump=system("which mysqldump");
                if(DEVELOP_MODE=="local" AND empty($mysqldump)) $mysqldump="/Applications/XAMPP/xamppfiles/bin/mysqldump";

                $table=$this->useTable;
                $user  =MYSQL_LOCAL_LOGIN;
                $passwd=MYSQL_LOCAL_PASS;
                $host  =MYSQL_LOCAL_HOST;
                $db    =MYSQL_LOCAL_DB;
                $psfile=MYSQL_LOCAL_PS_FILE;
                //$command="{$mysqldump} -u {$user} -p{$passwd} -t {$db} {$table} > {$backup_file_path}";
                $command="{$mysqldump} --defaults-extra-file={$psfile} -u {$user} -t {$db} {$table} > {$backup_file_path}";
                ob_start();
                system($command,$num);
                $res=ob_get_contents();
                ob_end_clean();

                $res["status"]=empty($num)?true:false;
                $res["backup_file"]=$backup_file_path;
                $res["remove_file"]=$remove_backup_file;
                return $res;
        }


}


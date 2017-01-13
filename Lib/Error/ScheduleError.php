<?php

namespace Schedule;

class Errors {

		static $errorStatus = array(

				"0" => array("title" => "実行エラー", "message" => "他の方が編集中です"),
				"1" => array("title" => "実行エラーです", "message" => "サーバエラーです"),
				"2" => array("title" => "既に編集されています", "message" => "ページをリロード致します"),
				"3" => array("title" => "実行エラー", "message" => "編集権限が他の方に移っています"),
		);
}

?>

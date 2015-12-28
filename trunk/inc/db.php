<?php
date_default_timezone_set ('Asia/Shanghai');
require_once('ez_sql_mysql.php');
function db() {
	$db = new  ezSQL_mysql('root', '163888', 'phoenix', '127.0.0.1'); 
	$db->query("SET CHARACTER SET `utf8`");
	return $db;
}
?>

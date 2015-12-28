<?
require_once('ez_sql_mysql.php');

function db() {
    return new ezSQL_mysql('root', '163888', 'phoenix', 'localhost');
}
?>

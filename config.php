<?php
define('HBDX',true);
$servername = "localhost";
$dbusername = "root";
$dbpassword = "123456";
$dbname = 'down';
$conn = mysql_connect($servername,$dbusername,$dbpassword);
mysql_select_db($dbname);
mysql_query("set names utf8");
$sitehost = $_SERVER['HTTP_HOST'];
?>

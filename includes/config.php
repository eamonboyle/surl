<?php 
session_start();
ob_start();
date_default_timezone_set('Europe/London');
$dbhost = "eamonsystems.db.11889039.hostedresource.com";
$dbname = "eamonsystems";
$dbuser = "eamonsystems";
$dbpass = "Eamon1992!";
mysql_connect($dbhost, $dbuser, $dbpass) or die("MySQL Error: " . mysql_error());
mysql_select_db($dbname) or die("MySQL Error: " . mysql_error());
?>
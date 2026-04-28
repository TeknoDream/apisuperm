<?php


$mysql_host = "localhost";
$mysql_database = "si11e_admin";
$mysql_user = "root";
$mysql_password = 'fdgfdgfdgfdgygHFEnLtDB';

try{
    $dbMat = new PDO('mysql:host=' . $mysql_host . ';dbname=' . $mysql_database.';charset=utf8', 
	$mysql_user,
	$mysql_password);
}
catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
?>

<?php
session_start();

$mysql_host = "motumdata.cyyjw19hq4ed.us-east-1.rds.amazonaws.com";
$mysql_database = "siie_admin";
$mysql_user = "motum";
$mysql_password = 'A*1k1sL*6$LRDS'; //AuvxV%KsL*V%K@S root//

try{
    $dbMat = new PDO('mysql:host=' . $mysql_host . ';dbname=' . $mysql_database.';charset=utf8', 
	$mysql_user,
	$mysql_password);
}
catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

$s="INSERT INTO logs (FECHA_LOG,IP_LOG,AGENT,DONDE,PETICION)
	VALUES (UTC_TIMESTAMP(),:ip,:donde,:agente,:peticion)";
$reqA = $dbMat->prepare($s);
$reqA->bindParam(':ip', $_SERVER["REMOTE_ADDR"]);
$reqA->bindParam(':donde', $_SERVER["HTTP_HOST"]);
$reqA->bindParam(':agente', $_SERVER["HTTP_USER_AGENT"]);
$reqA->bindParam(':peticion', $_GET["args"]);
$reqA->execute();

header('Location: http://'.$_SERVER["REMOTE_ADDR"]."/");

?>
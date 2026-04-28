<?php
session_start();
$id=$_POST["id"];
$acc=$_POST["acc"];

if($acc==1){
	if($id==1){
		$salidas=$_SESSION["_FACEBOOK"];
	}
	elseif($id==2){
		$salidas=$_SESSION["_TWITTER"];
	}
}
echo json_encode($salidas);
?>
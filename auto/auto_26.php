 t<?php
//ini_set('display_errors', 'On');
//ini_set('display_startup_errors', 'Off');

error_reporting(E_ERROR | E_WARNING | E_PARSE);

$_PROYECTO=26;
$_EMPRESA=22;
include "auto_00.php";

$_sysvars=array('project'=>$_PROYECTO,'company'=>$_EMPRESA);
$_sysvars_r=$_sysvars;
$state=ConectarseAUTO($dbEmpresa,$_sysvars);
if(!$state){
	echo 'Sin Conexión';
	exit(0);
}
include 		"/var/www/siie/public_html/phplib/variables_se.php";
Consultas($sqlCons,$sqlOrder,$_PROYECTO,$_CLIENTE,$_IDIOMA,$_TZ,$_GCLIENTE,$_USUARIO,$_GRUPO,$_PARAMETROS);
$_PARAMETROS=ParametrosAPI($dbEmpresa,$_sysvars_r,$_sysvars);
CuerpoMail($Email,$_PROYECTO,$_EMPRESA,$_CLIENTE,$_IDIOMA,$_TZ,$_GCLIENTE,$_USUARIO,$_GRUPO,$_PARAMETROS);

/******************************/
/******************************/
/******************************/
/******************************/

foreach ($argv as $key => $value) {
	$val_s=explode("=", $value);
	$results[$val_s[0]]=$val_s[1];
}
$tp=$results["tp"];

if($tp==1){  // AJUSTAR APERTURAS

	$ESTTZ = new DateTimeZone('UTC');
	$hoyOBJ = new DateTime(date(DATE_ATOM),$ESTTZ); 
	$hoySTR=$hoyOBJ->format('Y-m-d H:i');
	
	$item_ids=array();
	$s=$sqlCons[3][501];
	$req = $dbEmpresa->prepare($s);			
	$req->bindParam(':DateTime', $hoySTR);
	$req->execute();

	while($regItem = $req->fetch()){
		$item_ids[]=$regItem["ID_RESP"];
	}


	if(count($item_ids)){
		$id_items=implode(",",$item_ids);
		$s="INSERT INTO s_cresp_status
			(	ID_RESP
			,	OPEN_RESP)
			(SELECT ID_RESP,1 FROM s_cresp WHERE ID_RESP IN ($id_items))
			ON DUPLICATE KEY UPDATE OPEN_RESP=1";
		$req = $dbEmpresa->prepare($s);			
		$req->execute();
		
		$s="UPDATE s_cresp_status
			SET OPEN_RESP=0
			WHERE ID_RESP NOT IN ($id_items)";
		$req = $dbEmpresa->prepare($s);			
		$req->execute();
	}
	else{
		$s="UPDATE s_cresp_status
			SET OPEN_RESP=0)";
		$req = $dbEmpresa->prepare($s);			
		$req->execute();
	}

	$dbEmpresa->exec($s);	
}
?>
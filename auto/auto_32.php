 t<?php
//ini_set('display_errors', 'On');
//ini_set('display_startup_errors', 'Off');

error_reporting(E_ERROR | E_WARNING | E_PARSE);

$_PROYECTO=32;
$_EMPRESA=28;
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

	$user_calc=array();
	$VAL_PROY=0.05;
	$VAL_DESC=0.01;
	$VAL_CALF=0.01;
	$VAL_FOTO=0.02;
	$MAX_FOTO=3;

	//PROYECTOS
	$s='SELECT 
			SUM(IF(y_proyectos.DESC_PROY="",0,1)) AS X_PDESC
	,		COUNT(y_proyectos.ID_PROY) AS X_PROYECTOS
	,		y_proyectos.ID_USUARIO
	FROM y_proyectos
	LEFT JOIN adm_usuarios ON adm_usuarios.ID_USUARIO=y_proyectos.ID_USUARIO
	WHERE y_proyectos.HAB_PROY=0 AND adm_usuarios.HAB_U=0 
	GROUP BY y_proyectos.ID_USUARIO';
	$req = $dbEmpresa->prepare($s);			
	$req->execute();
	while($regItem = $req->fetch()){
		$id_usuario=$regItem['ID_USUARIO'];
		if(!isset($user_calc[$id_usuario])) $user_calc[$id_usuario]=0;
		$user_calc[$id_usuario]=$user_calc[$id_usuario]+($regItem['X_PROYECTOS']*$VAL_PROY)+($regItem['X_PDESC']*$VAL_DESC);
	}
	// FOTOS
	$s='SELECT 
		COUNT(y_proyectos_fotos.ID_FOTO) AS X_FOTOS
	,	y_proyectos.ID_USUARIO
	,	y_proyectos.ID_PROY
	FROM y_proyectos_fotos
	LEFT JOIN y_proyectos ON y_proyectos.ID_PROY=y_proyectos_fotos.ID_PROY
	LEFT JOIN adm_usuarios ON adm_usuarios.ID_USUARIO=y_proyectos.ID_USUARIO
	WHERE y_proyectos.HAB_PROY=0 AND adm_usuarios.HAB_U=0
	GROUP BY y_proyectos.ID_PROY';
	$req = $dbEmpresa->prepare($s);			
	$req->execute();
	while($regItem = $req->fetch()){
		$id_usuario=$regItem['ID_USUARIO'];
		if(!isset($user_calc[$id_usuario])) $user_calc[$id_usuario]=0;
		$user_calc[$id_usuario]=$user_calc[$id_usuario]+(($regItem['X_FOTOS']>=$MAX_FOTO?$MAX_FOTO:$regItem['X_FOTOS'])*$VAL_FOTO);
	}
	// XCALIF
	$s='SELECT 
			SUM(y_proyectos_calif.VAL_CALIF) AS X_CALIF
	,		y_proyectos.ID_USUARIO
	FROM y_proyectos_calif
	LEFT JOIN y_proyectos ON y_proyectos.ID_PROY=y_proyectos_calif.ID_PROY
	LEFT JOIN adm_usuarios ON adm_usuarios.ID_USUARIO=y_proyectos.ID_USUARIO
	WHERE y_proyectos.HAB_PROY=0 AND adm_usuarios.HAB_U=0 AND y_proyectos.FECHAS_PROY> DATE_SUB(UTC_TIMESTAMP(), INTERVAL 3 MONTH)
	GROUP BY y_proyectos.ID_USUARIO';
	$req = $dbEmpresa->prepare($s);			
	$req->execute();
	while($regItem = $req->fetch()){
		$id_usuario=$regItem['ID_USUARIO'];
		if(!isset($user_calc[$id_usuario])) $user_calc[$id_usuario]=0;
		$user_calc[$id_usuario]=$user_calc[$id_usuario]+($regItem['X_CALIF']*$VAL_CALF);
	}
	// ACTUALIZAR
	$s='UPDATE x_usuario
		SET DEST_USUARIO=:calif
		WHERE ID_USUARIO=:id_usuario LIMIT 1';
	$req = $dbEmpresa->prepare($s);	
	foreach ($user_calc as $id_usuario => $calif) {
		$req->bindValue(':id_usuario',$id_usuario);
		$req->bindValue(':calif',$calif);
		$req->execute();
	}
}
?>
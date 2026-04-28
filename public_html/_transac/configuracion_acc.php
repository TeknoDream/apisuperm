<?php

$datos_adicionales=array();
$output=array();

$c_campo=encrip_mysql("adm_ventanas_cont.ID_VENTANA",2);
$sTitulos=$sqlCons[1][1]." WHERE $c_campo=:c_sha AND adm_ventanas_cont.HAB_VENTANA=0 ".$sqlOrder[1][1];
$reqTitulos = $dbEmpresa->prepare($sTitulos);
$reqTitulos->bindParam(':c_sha', $c_sha);
$reqTitulos->execute();	
CreaConsulta($c_sha,$reqTitulos,$output,$sArmado);
$cnf=$output["scons"]["cnf"];
$tabla=$output["scons"]["tabla"];

/*PARA ARMAR LA CONSULTA SI ES UPDATE U OTRA */
$permiso=$PermisosA[$cnf]["P"]==1;
if(!$permiso) PrintErr(array('txt-MSJ16-0'));

unset($result['_AJAX']);
unset($result['md']);
unset($result['__route__']);
try{  				
	$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);			
	$dbEmpresa->beginTransaction();	
	if(!$nuevo){
		$sPre=array();
		foreach ($result as $i => $mPre){ $sPre[]=$output["cols"]["Cols"][mb_substr($i,1)]."=:$i"; }
		$sPreIN=implode(",",$sPre);
		$id_campo=encrip_mysql($output["scons"]["id_campo"]);
		if($output["scons"]["mempresa"]==1)
			$s=sprintf("UPDATE %s SET %s WHERE %s=:id AND ID_MEMPRESA=$_CLIENTE",$tabla,$sPreIN,$id_campo);
		else
			$s=sprintf("UPDATE %s SET %s WHERE %s=:id",$tabla,$sPreIN,$id_campo);
		$req=$dbEmpresa->prepare($s);
		$req->bindParam(':id', $id_sha);
		foreach ($result as $i => $mPre){
			$req ->bindParam(":$i", $result[$i]);
		}
		$req->execute();		
	}
	else{
		$sPre=array();
		foreach ($result as $i => $mPre){ $sPre[]=":$i"; }
		$sPreIN=implode(",",$sPre);
		$sCol=implode(",",$output["cols"]["Cols"]);
		if($output["scons"]["mempresa"]==1)
			$s=sprintf("INSERT INTO %s (%s,ID_MEMPRESA) VALUES(%s,$_CLIENTE)",$tabla,$sCol,$sPreIN);
		else
			$s=sprintf("INSERT INTO %s (%s) VALUES(%s)",$tabla,$sCol,$sPreIN);
		$req=$dbEmpresa->prepare($s);
		foreach ($result as $i => $mPre){ 
			$req ->bindParam(":$i", $result[$i]);
		}
		$req->execute();
		$id=$dbEmpresa->lastInsertId();
		$id_sha=encrip($id);
	}
	if($_PROYECTO==16)			include("configuracion_acc_016.php");
	elseif($_PROYECTO==29)		include("configuracion_acc_029.php"); 
	elseif($_PROYECTO==32)		include("configuracion_acc_032.php"); 
	$dbEmpresa->commit();		
}
catch (Exception $e){
	$dbEmpresa->rollBack();
}		
?>
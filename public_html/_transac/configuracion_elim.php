<?php
$output=array();
$c_campo=encrip_mysql("adm_ventanas_cont.ID_VENTANA",2);
$sTitulos=$sqlCons[1][3]."WHERE $c_campo=:c_sha AND adm_ventanas_cont.HAB_VENTANA=0 ";
$reqTitulos = $dbEmpresa->prepare($sTitulos);
$reqTitulos->bindParam(':c_sha', $c_sha);
$reqTitulos->execute();
CreaConsulta($c_sha,$reqTitulos,$output,$sArmado);
$cnf=$output["scons"]["cnf"];
$tabla=$output["scons"]["tabla"];
$hab_campo=$output["scons"]["hab_campo"];
/*TABLA*/

$permiso=$PermisosA[$cnf]["P"]==1;
if(!$permiso) PrintErr(array('txt-MSJ16-0'));

$estado=$accion==$acc02?0:1;
$id_campo=encrip_mysql($output["scons"]["id_campo"]);
$reload=2;

try{  				
	$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);			
	$dbEmpresa->beginTransaction();	
	if($output["scons"]["mempresa"]==1){	
		if($estado==0)	$s=sprintf("UPDATE %s SET %s=%d WHERE %s=:id AND ID_MEMPRESA=$_CLIENTE ",$tabla,$hab_campo,$estado,$id_campo);
		else			$s=sprintf("DELETE FROM %s WHERE %s=:id AND ID_MEMPRESA=$_CLIENTE ",$tabla,$id_campo);
	}
	else{
		if($estado==0)	$s=sprintf("UPDATE %s SET %s=%d WHERE %s=:id ",$tabla,$hab_campo,$estado,$id_campo);
		else			$s=sprintf("DELETE FROM %s WHERE %s=:id ",$tabla,$id_campo);
	}
	$req = $dbEmpresa->prepare($s);
	$req->bindParam(':id', $id_sha);
	$req->execute();
	$dbEmpresa->commit();
	$reload=false;
}
catch (Exception $e){
	$dbEmpresa->rollBack();
	$err_str=$e->getMessage();
	try{ 
		if($output["scons"]["mempresa"]==1)
			$s=sprintf("UPDATE %s SET %s=%d WHERE %s=:id AND ID_MEMPRESA=$_CLIENTE ",$tabla,$hab_campo,$estado,$id_campo);
		else
			$s=sprintf("UPDATE %s SET %s=%d WHERE %s=:id ",$tabla,$hab_campo,$estado,$id_campo);
		$req = $dbEmpresa->prepare($s);
		$req->bindParam(':id', $id_sha);
		$req->execute();
		$err_str='';
	}
	catch (Exception $e){
		$err_str=$e->getMessage();
	}
}
if($_PROYECTO==16)			include("configuracion_acc_016.php");
elseif($_PROYECTO==29)		include("configuracion_acc_029.php"); 
elseif($_PROYECTO==32)		include("configuracion_acc_032.php"); 
?>
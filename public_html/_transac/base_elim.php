<?php
require 		"phplib/s3/aws.phar";
use 			Aws\Common\Aws;	

if($cnf==36) 	$permiso=$PermisosA[8]["P"]==1;
else 			$permiso=$PermisosA[$cnf]["P"]==1;
if(!$permiso) PrintErr(array('txt-MSJ16-0'));

$datos_adicionales=array();
$EjecuteDelete=false;
$pag_prop=false;
$sID='';

if($cnf==19){			
	$sWhere=encrip_mysql('s_cresp.ID_RESP');
	$sID=$sqlCons[1][101]." WHERE $sWhere=:id LIMIT 1";
	$Campo="HAB_RESP";
	$Campo_ID="ID_RESP";
	$Tabla="s_cresp";	
	$pag_prop=true;
}
elseif($cnf==36){
	if(($accion==$acc01)||($accion==$acc02)){
		$sWhere=encrip_mysql('adm_usuarios.ID_USUARIO');
		$sID=$sqlCons[1][0]." WHERE $sWhere=:id LIMIT 1";	
		$Campo="HAB_U";
		$Campo_ID="ID_USUARIO";
		$Tabla="adm_usuarios";
		$pag_prop=true;
	}
	elseif($accion==$acc03){
		$sWhere=encrip_mysql('adm_empresas_url.ID_URLS');
		$Campo_ID="ID_URLS";
		$Tabla="adm_empresas_url";
	}
}
elseif($cnf==8){
	if(($accion==$acc01)||($accion==$acc02)){
		$sWhere=encrip_mysql('adm_grupos.ID_GRUPO');
		$sID=$sqlCons[1][64]." WHERE $sWhere=:id LIMIT 1";
		$Campo="HAB_GRUPO";
		$Campo_ID="ID_GRUPO";
		$Tabla="adm_grupos";
		$pag_prop=true;
	}
}
elseif($cnf==2){
	if($accion==$acc03){
		$sWhere=encrip_mysql('adm_empresas_url.ID_URLS');
		$Campo_ID="ID_URLS";
		$Tabla="adm_empresas_url";
	}

}
else{
	if($_PROYECTO==1)			include("base_elim_001.php"); //ROCKETMP
	elseif($_PROYECTO==8)		include("base_elim_008.php"); //FALCONCRM
	elseif($_PROYECTO==10)		include("base_elim_010.php"); //TUPYME	
	elseif($_PROYECTO==13)		include("base_elim_013.php"); //CIUDAD TRAVEL
	elseif($_PROYECTO==14)		include("base_elim_014.php"); //EVENTOS CCB
	elseif($_PROYECTO==16)		include("base_elim_016.php"); //DISPONIBLES
	elseif($_PROYECTO==18)		include("base_elim_018.php"); //MENSAJERO
	elseif($_PROYECTO==19)		include("base_elim_019.php"); //SCA
	elseif($_PROYECTO==20)		include("base_elim_020.php"); //Appetitos
	elseif($_PROYECTO==21)		include("base_elim_021.php"); //Innova
	elseif($_PROYECTO==22)		include("base_elim_022.php"); //Rocket
	elseif($_PROYECTO==23)		include("base_elim_023.php"); //VIGA
	elseif($_PROYECTO==24)		include("base_elim_024.php"); //MarcaGPS
	elseif($_PROYECTO==25)		include("base_elim_025.php"); //Esteba Rios
	elseif($_PROYECTO==26)		include("base_elim_026.php"); //Mis Veterinarias
	elseif($_PROYECTO==27)		include("base_elim_027.php"); //Cancheros
	elseif($_PROYECTO==28)		include("base_elim_028.php"); //Petrozones
	elseif($_PROYECTO==29)		include("base_elim_029.php"); //Asking Room
	elseif($_PROYECTO==31)		include("base_elim_031.php"); //Super Maestros
	elseif($_PROYECTO==32)		include("base_elim_032.php"); //Super Maestros
}
//IMAGENES

$bandera=false;
$estado=$accion==$acc02?0:1;
$id_campo=encrip_mysql($id_campo);
//INTENTA BORRARLO DEL TODO
if($sID!=''){
	$req = $dbEmpresa->prepare($sID); 
	$req->bindParam(':id', $id_sha);
	if($idioma) $req->bindParam(':_IDIOMA', $_IDIOMA);
	$req->execute();	
	$reg = $req->fetch();
	$id=$reg[$Campo_ID];
	$EjecuteDelete=true;
}
if($accion==$acc02){
	$s=sprintf("UPDATE %s SET %s=%d WHERE %s='%s'",$Tabla,$Campo,$estado,$sWhere,$id_sha);
	$dbEmpresa->exec($s);
}
else{
	try{  				
		$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);			
		$dbEmpresa->beginTransaction();	
		if($cnf==36&&$accion==$acc01){
			$sWhereV=encrip_mysql('adm_usuarios_empresa.ID_USUARIO');
			$s="DELETE FROM adm_usuarios_empresa 
				WHERE ID_MEMPRESA=$_CLIENTE AND $sWhereV=:id";
			$reqV = $dbEmpresa->prepare($s);
			$reqV->bindParam(':id', $id_sha);
			$reqV->execute();

			$s="SELECT COUNT(*) AS EMPRESAS FROM adm_usuarios_empresa WHERE $sWhereV=:id";
			$reqV = $dbEmpresa->prepare($s);
			$reqV->bindParam(':id', $id_sha);
			$reqV->execute();
			$regV = $reqV->fetch();
			$PElim=($regV["EMPRESAS"]==0);
		}
		elseif($cnf==36&&$accion==$acc03){
			$sWhereV=encrip_mysql('adm_usuarios_empresa.ID_USUARIO');
			$s="DELETE FROM adm_usuarios_empresa 
				WHERE ID_MEMPRESA=$_CLIENTE AND $sWhereV=:id";
			$reqV = $dbEmpresa->prepare($s);
			$reqV->bindParam(':id', $id_sha);
			$reqV->execute();
		}		
		else $PElim=true;
		
		if($PElim){			
			$s=sprintf("DELETE FROM %s WHERE %s=:id",$Tabla,$sWhere);
			$req = $dbEmpresa->prepare($s);
			$req->bindParam(':id', $id_sha);						
			$req->execute();			
			$reload=false;

			$UploadDeleteArgs=array(
						'S3_BUCKET'=>$_PARAMETROS["S3_BUCKET"]
					,	'PROYECTO'=>$_PROYECTO
					,	'EMPRESA'=>$_EMPRESA
					,	'MODULE'=>$cnf
					,	'OBJECT'=>$id
					,	'TP_FILE'=>'img');

			if($_PROYECTO==1)			include("base_elim_sp_001.php"); //ROCKETMP		
			elseif($_PROYECTO==8)		include("base_elim_sp_008.php"); //FALCONCRM
			elseif($_PROYECTO==10)		include("base_elim_sp_010.php"); //TUPYME
			elseif($_PROYECTO==13)		include("base_elim_sp_013.php"); //CIUDAD TRAVEL
			elseif($_PROYECTO==14)		include("base_elim_sp_014.php"); //EVENTOS CCB
			elseif($_PROYECTO==16)		include("base_elim_sp_016.php"); //DISPONIBLES
			elseif($_PROYECTO==18)		include("base_elim_sp_018.php"); //Mensajero
			elseif($_PROYECTO==19)		include("base_elim_sp_019.php"); //SCA
			elseif($_PROYECTO==20)		include("base_elim_sp_020.php"); //Appetitos
			elseif($_PROYECTO==23)		include("base_elim_sp_023.php"); //VIGA
			elseif($_PROYECTO==24)		include("base_elim_sp_024.php"); //Marca GPS
			elseif($_PROYECTO==29)		include("base_elim_sp_029.php"); //Asking Room

			//ARREGLOS
			if($_PROYECTO==1)		include("base_elima_001.php"); //ROCKETMP
			elseif($_PROYECTO==8)	include("base_elima_008.php"); //FALCONCRM
		}
		$dbEmpresa->commit();
		$reload=true;
		
		
	}
	catch (Exception $e1){
		$dbEmpresa->rollBack();
		$err_str1=$e1->getMessage();
		try{ 
			$s=sprintf("UPDATE %s SET %s=%d WHERE %s=:id",$Tabla,$Campo,$estado,$sWhere);
			$req = $dbEmpresa->prepare($s);
			$req->bindParam(':id', $id_sha);
			$req->execute();
			$err_str='';
		}
		catch (Exception $e2){
			$err_str2=$e2->getMessage();
		}
	}
	if($EjecuteDelete){
		//BORRA ARCHIVO//
		$AwsS3 = Aws::factory(array('key' => $_PARAMETROS['S3_KEY'],'secret' => $_PARAMETROS['S3_SECRET']));
		DeleteFiles($AwsS3,$dbEmpresa,$UploadDeleteArgs);
		$salidas["hide"]=$UploadDeleteArgs;
	}
}
?>
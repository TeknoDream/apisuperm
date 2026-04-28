<?php 
/*
---------------------------------------------------------------------------------
                        SUPERMAESTROS BACKOFFICE
                             Proyecto N° 32
                        Archivo: op_acc_032.php
    Descripción: archivo de configuración opciones adicionales de los objetos JSON
--------------------------------------------------------------------------------

Este archivo configura opciones adicionales del menú de los objetos, como por 
ejemplo el botón de autorizar o desautorizar a un instalador, agregar fotos 
a las remodelaciones, ocultar o hacer visible una remodelación.

Módulos de SuperMaestros
------------------------
N° 500 Usuarios
N° 501 Remodelaciones (inicialmente llamado Proyectos)
N° 502 Proyectos (inicialmente denominado Ofertas)
N° 503 Facturas
N° 504 Cotizaciones
N° 505 Estado de cuenta del instalador
N° 506 Noticias
N° 507 Publicidad
N° 508 Mensajes
N° 509 Especialidades
*/

use 			Aws\Common\Aws;	
/******************************/
/******************************/
/*** Autorizar Instalador *****/
/******************************/

/*$det_plus==1: acción de autorizar
$det_plus==2: acción de desautorizar 

VERIF_USUARIO=0 instalador no autorizado
VERIF_USUARIO=1 instalador autorizado */ 

if($cnf==500 &&($det_plus==1||$det_plus==2)){	
	$sWhere=encrip_mysql("adm_usuarios.ID_USUARIO");
    $s=$sqlCons[0][500]." WHERE $sWhere=:idt LIMIT 1";    
	$req = $dbEmpresa->prepare($s); 
	$req->bindParam(':idt', $id_sha_t);
	$req->execute();	
	if($reg = $req->fetch()) $id=$reg["ID_USUARIO"];
	else {$mensaje[0]='txt-MSJ9-0';$error=true;}

    if(!$error){		
    	try{  		
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();				
		
	    	$Activo=$det_plus==1?1:0;
			$s="UPDATE x_usuario
				SET VERIF_USUARIO=:Activo
				WHERE ID_USUARIO=:id LIMIT 1";

			$reqC = $dbEmpresa->prepare($s);
			$reqC ->bindParam(":Activo", $Activo);
			$reqC ->bindParam(":id", $id);
			$reqC->execute();
			$dbEmpresa->commit();		
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}
	}
} 
/******************************/
/******************************/
/*** Agregar Fotos a una ******/
/****    Remodelación   *******/
/******************************/
/*:main variable que idenfitica
si la foto ha sido elegida como
principal de la remodelación
:main=0 >no es principal
:main=1 > si es principal*/

elseif($cnf==501&&$det_plus==1){  
	$nuevo=(nuevo_item()==$id_sha);
	if(($result["imagen"]==1)){		
		$tamano=$_FILES[$control_img]["size"];
		$ubicacion=$_FILES[$control_img]["tmp_name"];
		$finfo = finfo_open(FILEINFO_MIME_TYPE); 	
		$tipo=finfo_file($finfo, $ubicacion);	
		finfo_close($finfo);	
		$nomb_f=$_FILES[$control_img]["name"];	
		if(($tamano<=$fmin) || ($tamano>$fmax))		{$mensaje[0]='txt-MSJ2-0';$error=true;}
		if(!fValid($tipo,$_files_clase[0]))			{$mensaje[0]='txt-MSJ3-0';$error=true;}
	}

	
	if(!$error){
		if($nuevo){
			$sWhere_t=encrip_mysql('y_proyectos.ID_PROY');
			$s=$sqlCons[0][501]." WHERE  $sWhere_t=:idt LIMIT 1";		
			$req = $dbEmpresa->prepare($s); 
			$req->bindParam(':idt', $id_sha_t);
			$req->execute();	
			if(!$reg = $req->fetch()) {$mensaje[0]='txt-MSJ9-0';$error=true;}	
			$id_print='NULL';
			$id_proy=$reg["ID_PROY"];
		}
		else{
			$sWhere=encrip_mysql('y_proyectos_fotos.ID_FOTO');
			$s=$sqlCons[1][501]." WHERE $sWhere=:id LIMIT 1";		
			$req = $dbEmpresa->prepare($s); 
			$req->bindParam(':id', $id_sha);
			$req->execute();	
			if(!$reg = $req->fetch()) {$mensaje[0]='txt-MSJ9-0';$error=true;}	
			$id=$reg["ID_FOTO"];
			$id_print=$reg["ID_FOTO"];
			$id_proy=$reg["ID_PROY"];
		}
	}
	
	if(!$error){		
		try{  	
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();
			//campo MAIN_FOTO=0 :no es principal
			// MAIN_FOTO=1 : foto principal
			$main=$result['main']==1?1:0;
			if($main){
				$s='UPDATE y_proyectos_fotos
					SET MAIN_FOTO=0
					WHERE ID_PROY=:id_proy AND MAIN_FOTO=1';
				$req = $dbEmpresa->prepare($s); 
				$req->bindParam(':id_proy', $id_proy);
				$req->execute();	
			}
			
			$s="INSERT INTO y_proyectos_fotos
					(ID_FOTO
				,	ID_PROY
				,	FECHAS_FOTO
				,	TITLE_FOTO
				,	MAIN_FOTO)
				VALUES
					($id_print
				,	$id_proy
				,	UTC_TIMESTAMP()
				,	:title
				,	:main)
			ON DUPLICATE KEY UPDATE 
				TITLE_FOTO=:title
			, 	FECHAS_FOTO=UTC_TIMESTAMP()
			,	MAIN_FOTO=:main";					
			$req = $dbEmpresa->prepare($s); 
			$req->bindParam(':title', $result["title"]);	
			$req->bindParam(':main', $result["main"]);	
			$req ->execute();	
			if($nuevo)	$id=$dbEmpresa->lastInsertId();		
			//Se configuran los parámetros para la imagen y almacenarla en el servidor S3
			if(($result["imagen"]==1)){			
				require 		"phplib/s3/aws.phar";
				$UploadDeleteArgs=array(
						'S3_BUCKET'=>$_PARAMETROS["S3_BUCKET"]
					,	'PROYECTO'=>$_PROYECTO
					,	'EMPRESA'=>$_EMPRESA
					,	'MODULE'=>$cnf
					,	'OBJECT'=>$id
					,	'TP_FILE'=>'img');

				$AwsS3 = Aws::factory(array('key' => $_PARAMETROS['S3_KEY'],'secret' => $_PARAMETROS['S3_SECRET']));
				UploadFiles($AwsS3,$control_img,$dbEmpresa,$UploadDeleteArgs);		

				$s="UPDATE y_proyectos_fotos 
					SET ID_FILE=IFNULL((SELECT adm_files.ID_FILE FROM adm_files WHERE adm_files.F_MODULE=$cnf AND adm_files.ID_OBJECT=$id AND adm_files.F_SUB_EXT='org' AND adm_files.F_TP_FILE='img' LIMIT 1),0)
					WHERE ID_FOTO=$id";
				$dbEmpresa->exec($s);
			}
			$dbEmpresa->commit();			  
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}	
	}
}
/*******************************/
/*******************************/
/*** Hacer visible/ocultar *****/
/***    Remodelación   *********/
/******************************/

/* Por defecto todo proyecto creado
es visible
$det_plus=2 : ocultar proyecto
$det_plus=3 : hacer visible */
elseif($cnf==501 &&($det_plus==2||$det_plus==3)){	
	$sWhere=encrip_mysql("y_proyectos.ID_PROY");
    $s=$sqlCons[0][501]." WHERE $sWhere=:idt LIMIT 1";    
	$req = $dbEmpresa->prepare($s); 
	$req->bindParam(':idt', $id_sha_t);
	$req->execute();	
	if($reg = $req->fetch()) $id=$reg["ID_PROY"];
	else {$mensaje[0]='txt-MSJ9-0';$error=true;}

    if(!$error){		
    	try{  		
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();				
		
	    	$Activo=$det_plus==2?1:0;
			$s="UPDATE y_proyectos
				SET STATUS_PROY=:Activo
				WHERE ID_PROY=:id LIMIT 1";

			$reqC = $dbEmpresa->prepare($s);
			$reqC ->bindParam(":Activo", $Activo);
			$reqC ->bindParam(":id", $id);
			$reqC->execute();
			$dbEmpresa->commit();		
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}
	}
} 
/*******************************/
/*******************************/
/*** Validar/Invalidar**********/
/*****	Factura ***************/
/******************************/

/* Las facturas subidas por un 
instalador por defecto son invalidas

$det_plus==1 : validar factura
$det_plus==2 : invalidar factura  */

if($cnf==503 &&($det_plus==1||$det_plus==2)){	
	$sWhere=encrip_mysql("y_facturas.ID_FACT");
    $s=$sqlCons[0][503]." WHERE $sWhere=:idt LIMIT 1";    
	$req = $dbEmpresa->prepare($s); 
	$req->bindParam(':idt', $id_sha_t);
	$req->execute();	
	if($reg = $req->fetch()) $id=$reg["ID_FACT"];
	else {$mensaje[0]='txt-MSJ9-0';$error=true;}

    if(!$error){		
    	try{  		
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();				
			/* Campo VALIDAT_FACT por defecto siempre en 0
			VALIDAT_FACT=0 : factura invalida
			VALIDAT_FACT=1 : factura valida */
	    	$Activo=$det_plus==1?1:0;
			$s="UPDATE y_facturas
				SET VALIDAT_FACT=:Activo
				WHERE ID_FACT=:id LIMIT 1";

			$reqC = $dbEmpresa->prepare($s);
			$reqC ->bindParam(":Activo", $Activo);
			$reqC ->bindParam(":id", $id);
			$reqC->execute();
			$dbEmpresa->commit();		
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}
	}
} 
?>
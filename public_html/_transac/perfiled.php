<?php
require 		"phplib/s3/aws.phar";
use 			Aws\Common\Aws;	
$cnf=36;
$permiso=$PermisosA[$cnf]["P"]==1;
if(!$permiso) PrintErr(array('txt-MSJ16-0'));

if($m==1){	
	$user=$result["user"];
	$nombres=$result["nombres"];
	$apellidos=$result["apellidos"];
	$tdoc=$result["tdoc"];
	$doc=$result["doc"];
	$ciuddoc=$result["ciuddoc"];
	$genero=$result["genero"];
	$tel=$result["tel"];
	$tel2=$result["tel2"];
	$dir=$result["dir"];
	
	$tz=$result["tz"];
	$idioma=$result["idioma"];
	$moneda=$result["moneda"];
	
	if(($_PROYECTO==13)||($_PROYECTO==8)){
		if(($nombres=='')||($tdoc==0)||($genero==0)||($tz==0)||($moneda==0)) {$mensaje[0]='txt-MSJ1-0';$error=true;}			
	}
	else{
		if(($nombres=='')||($tdoc==0)||($genero==0)||($tz==0)) {$mensaje[0]='txt-MSJ1-0';$error=true;}			
	}

	if(!comprobtexto($user)) 	{$mensaje[0]='txt-MSJ11-0';$error=true;}
	else{
		$s="SELECT ID_USUARIO
				FROM adm_usuarios
			WHERE ALIAS=:user AND ID_USUARIO<>'$_USUARIO'";
		$req = $dbEmpresa->prepare($s);	 
		$req->bindParam(':user', $user);	
		$req->execute();
		if($reg = $req->fetch())  {$mensaje[0]='txt-MSJ12-0';$error=true;}
	}

	if(!$error){
		try{  				
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();
			if(($_PROYECTO==13)||($_PROYECTO==8)){
				$s="UPDATE adm_usuarios
					SET
					ALIAS=:user,				
					NOMBRE_U=:nombres,
					APELLIDO_U=:apellidos,
					ID_IDIOMA=:idioma,
					ID_MONEDA=:moneda
					WHERE ID_USUARIO='$_USUARIO'";
				$req = $dbEmpresa->prepare($s);	 
				$req->bindParam(':user', $user);	
				$req->bindParam(':nombres', $nombres);
				$req->bindParam(':apellidos', $apellidos);
				$req->bindParam(':idioma', $idioma);
				$req->bindParam(':moneda', $moneda);
			}
			else{
				$s="UPDATE adm_usuarios
					SET
					ALIAS=:user,				
					NOMBRE_U=:nombres,
					APELLIDO_U=:apellidos,
					ID_IDIOMA=:idioma
					WHERE ID_USUARIO='$_USUARIO'";
				$req = $dbEmpresa->prepare($s);	 
				$req->bindParam(':user', $user);	
				$req->bindParam(':nombres', $nombres);
				$req->bindParam(':apellidos', $apellidos);
				$req->bindParam(':idioma', $idioma);
			}
			
			$req->execute();			
			$s="INSERT INTO adm_usuarios_datos (ID_USUARIO,ID_DOCUMENTO,ID_TZ,DOCUMENTO,ID_GENERO,TELEFONO_U,TELEFONO2_U,DIRECCION_U)
				VALUES('$_USUARIO',:tdoc,:tz,:doc,:genero,:tel,:tel2,:dir) 
				ON DUPLICATE KEY UPDATE				
				ID_DOCUMENTO=:tdoc,
				ID_TZ=:tz,
				DOCUMENTO=:doc,
				ID_GENERO=:genero,
				TELEFONO_U=:tel,
				TELEFONO2_U=:tel2,
				DIRECCION_U=:dir";
			$req = $dbEmpresa->prepare($s);	 
			$req->bindParam(':tdoc', $tdoc);	
			$req->bindParam(':tz', $tz);
			$req->bindParam(':doc', $doc);
			$req->bindParam(':genero', $genero);
			$req->bindParam(':tel', $tel);
			$req->bindParam(':tel2', $tel2);
			$req->bindParam(':dir', $dir);
			$req->execute();		

			//URLS			
			$conteov=count($result["EDRS"]);						
			if($conteov>0){	
				/***********/
				foreach ($result["EDRS"] as $k => $GastoG){									
					if($result["EDRS"][$k]!=0){
						$UsrRs=$result["UsrRs"][$k];						
						$s="REPLACE INTO adm_usuarios_datosrs (ID_USUARIO,ID_ORIGEN,RS_VALOR)
									VALUES($_USUARIO,:IdOrigen,:UsrRs)";									
						$req = $dbEmpresa->prepare($s); 
						$req->bindParam(':IdOrigen',$result["IdOrigen"][$k]);	
						$req->bindParam(':UsrRs',$UsrRs);			
						$req->execute(); 					
					}
				}
				/***********/				
			}
			$dbEmpresa->commit();		
			$reload=true;
			$words=true;
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}
	}
}
elseif($m==2){
	$email=$result["correo"];

	if(!checkmail($email)) 	{$mensaje[0]='txt-MSJ10-0';$error=true;}	
	
	if(!$error){			
		$s=$sqlCons[1][0]." WHERE adm_usuarios.CORREO_U=:email AND adm_usuarios.ID_USUARIO<>:id LIMIT 1";
		$reqV = $dbEmpresa->prepare($s); 
		$reqV ->bindParam(':email', $email);
		$reqV ->bindParam(':id', $_USUARIO);
		$reqV ->execute();
		if($regV = $reqV->fetch()) {$mensaje[0]='txt-MSJ20-0';$error=true;}
	}
	if(!$error){	
		try{  				
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();	
			$s="UPDATE adm_usuarios
				SET				
				CORREO_U=:email,
				VERIF_U=0
				WHERE ID_USUARIO=:id";
			$req = $dbEmpresa->prepare($s);	 	
			$req ->bindParam(':email', $email);
			$req ->bindParam(':id', $_USUARIO);
			$req->execute();

			/****** CREACION DE NUEVA SESION *****/
			$s=$sqlCons[1][0]." WHERE adm_usuarios.ID_USUARIO=:id LIMIT 1";
			$reqV = $dbEmpresa->prepare($s);
			$reqV ->bindParam(':id', $_USUARIO);
			$reqV ->execute();
			$regV = $reqV->fetch();

			//validacion($dbEmpresa,$regV["CORREO_U"],$regV["PASSWORD_U"],$_SESSION["ses_type"],$_SESSION["ses"]);			
			$dbEmpresa->commit();
			try{
				/*******SEND EMAIL***********/			
				CuerpoMail($Email,$_PROYECTO,$_EMPRESA,$_CLIENTE,$_IDIOMA,$_TZ,$_GCLIENTE,$_USUARIO,$_GRUPO,$_PARAMETROS);				
				$code_01=sha1($_USUARIO.$email).md5($_USUARIO.$email).sha1($_USUARIO);
				$to=array();
				$to[0]["mail"]=$email;
				$to[0]["name"]=$regV["USUARIO_COMP"];

				$Asunto=$Email[1][8]['title'];
				$URL_Verif=$_SERVER["HTTP_HOST"]."/verification/?code=".$code_01;
				$html_cont=sprintf($Email[1][8]['body'],$email,$URL_Verif);
				$rtamail=send_email_srv($_PARAMETROS,$Asunto,$html_cont,$to,array(),array(),true,$Email[1][8]['alt']);
				
				$salidas["rta_mail"]=$rtamail;				
				/*******SEND EMAIL***********/
			}
			catch (Exception $e){
			}
			$reload=true;
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}	

	}

}
elseif($m==3){
	$pass01=sha1($result["pass01"]);
	$pass02=$result["pass02"];
	$pass03=$result["pass03"];
	
	
	$s="SELECT ID_USUARIO
			FROM adm_usuarios
		WHERE PASSWORD_U=:pass01 AND ID_USUARIO='$_USUARIO'";
	$req = $dbEmpresa->prepare($s);	 
	$req->bindParam(':pass01', $pass01);	
	$req->execute();
	if(!($reg = $req->fetch()))  {$mensaje[0]='txt-MSJ13-0';$error=true;}
	
	elseif(($pass02!=$pass03)) {$mensaje[0]='txt-MSJ14-0';$error=true;}
	elseif((mb_strlen($pass02)<$minimo_pass)) {$mensaje[0]='txt-MSJ15-0';$error=true;}
	
	if(!$error){
		try{  				
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();
			$pass02=sha1($pass02);
			$s="UPDATE adm_usuarios
				SET
				PASSWORD_U=:pass02
				WHERE ID_USUARIO='$_USUARIO'";
			$req = $dbEmpresa->prepare($s);	 
			$req->bindParam(':pass02', $pass02);	
			$req->execute();

			/****** CREACION DE NUEVA SESION *****/
			$s=$sqlCons[1][0]." WHERE adm_usuarios.ID_USUARIO=:id LIMIT 1";
			$reqV = $dbEmpresa->prepare($s);
			$reqV ->bindParam(':id', $_USUARIO);
			$reqV ->execute();
			$regV = $reqV->fetch();
			//REVALIDA LA SESION
			//validacion($dbEmpresa,$regV["CORREO_U"],$regV["PASSWORD_U"],$_SESSION["ses_type"],$_SESSION["ses"]);
			$dbEmpresa->commit();
			$reload=true;
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}
	}
	
}
elseif($m==4){
	$tamano=$_FILES[$control_img]["size"];
	$ubicacion=$_FILES[$control_img]["tmp_name"];
	$finfo = finfo_open(FILEINFO_MIME_TYPE); 	
	$tipo=finfo_file($finfo, $ubicacion);	
	finfo_close($finfo);	
	$nombre=$_FILES[$control_img]["name"];
	if(($tamano<=$fmin) || ($tamano>$fmax))	{$mensaje[0]='txt-MSJ2-0';$error=true;}
	if(tipo_archivo($tipo)!=1)	{$mensaje[0]='txt-MSJ3-0';$error=true;}
	if(!$error){	
		try{  				
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();	

			$AwsS3 = Aws::factory(array('key' => $_PARAMETROS['S3_KEY'],'secret' => $_PARAMETROS['S3_SECRET']));
			$UploadDeleteArgs=array(
						'S3_BUCKET'=>$_PARAMETROS["S3_BUCKET"]
					,	'PROYECTO'=>$_PROYECTO
					,	'EMPRESA'=>$_EMPRESA
					,	'MODULE'=>36
					,	'OBJECT'=>$_USUARIO
					,	'TP_FILE'=>'img');

			UploadFiles($AwsS3,$control_img,$dbEmpresa,$UploadDeleteArgs,$Info);	

			$s="UPDATE adm_usuarios 
				SET ID_FILE=IFNULL((SELECT adm_files.ID_FILE FROM adm_files WHERE adm_files.F_MODULE=36 AND adm_files.ID_OBJECT=$_USUARIO AND adm_files.F_SUB_EXT='org' AND adm_files.F_TP_FILE='img'),0)
				WHERE ID_USUARIO=$_USUARIO";
			$dbEmpresa->exec($s);		


			$dbEmpresa->commit();
			$reload=true;		  
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}			
			
	}
}
elseif($m==5){
	$idrs=$result["id"];
	$acc=$result["acc"];	
	if($acc==10){	
		try{  				
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();		
			if($idrs==1){
				$s="DELETE FROM adm_usuarios_facebook WHERE ID_USUARIO='$_USUARIO'";
			}
			elseif($idrs==2){
				$s="DELETE FROM adm_usuarios_twitter WHERE ID_USUARIO='$_USUARIO'";
			}
			$req = $dbEmpresa->prepare($s);	
			$req->execute();			
			$dbEmpresa->commit();												  
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}
	}
}
elseif($m==6){		
	if(!$error){
		try{  				
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();
			//URLS			
			$conteov=count($result["EDRS"]);						
			if($conteov>0){	
				/***********/
				foreach ($result["EDRS"] as $k => $GastoG){									
					if($result["EDRS"][$k]!=0){
						$UsrRs=$result["UsrRs"][$k];						
						$s="REPLACE INTO adm_usuarios_datosrs (ID_USUARIO,ID_ORIGEN,RS_VALOR)
									VALUES($_USUARIO,:IdOrigen,:UsrRs)";									
						$req = $dbEmpresa->prepare($s); 
						$req->bindParam(':IdOrigen',$result["IdOrigen"][$k]);	
						$req->bindParam(':UsrRs',$UsrRs);			
						$req->execute(); 					
					}
				}
				/***********/				
			}
			$dbEmpresa->commit();					  
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}	
	}
}
?>
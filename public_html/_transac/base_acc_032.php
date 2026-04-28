<?php
use 			Aws\Common\Aws;	
/*
---------------------------------------------------------------------------------
                        SUPERMAESTROS BACKOFFICE
                             Proyecto N° 32
                        Archivo: base_acc_032.php
 Descripción: archivo de configuración de inserción de datos en la base de datos
--------------------------------------------------------------------------------
Este es el archivo que toma los datos recogidos de los fomularios, los valida
y los ingresa en la base de datos, en la tabla que corresponda de acuerdo
al número de módulo que corresponda con el formulario.

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

*************************/
/*************************/
/*****   Usuarios *********/
/*************************/ 
if($cnf==500){ 
	if($result["nomb"]==''
	||	$result["apel"]==''
	||	$result["email"]==''
	||	$result["tel1"]=='')
	{$mensaje[0]='txt-MSJ1-0';$error=true;} //Validar campos vaciós y arrojar error si faltan datos
 
	if(!$nuevo){
		$sWhere=encrip_mysql('adm_usuarios.ID_USUARIO');
		$s="SELECT ID_USUARIO AS ID,CORREO_U,VERIF_U FROM adm_usuarios WHERE $sWhere=:id LIMIT 1 ";
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		$req->execute();	
		if($reg = $req->fetch()){
			$id=$reg["ID"];
			$correo_old=$reg['CORREO_U'];
			$verify=$reg['VERIF_U'];
		}
		else {$mensaje[0]='txt-MSJ9-0';$error=true;}		
		//if($id==$_USUARIO) {$mensaje[0]='txt-MSJ9-0';$error=true;}		
		$id_print=$id;
	}
	else $id_print="NULL";	

	//
	$correo=$result["email"];
	if(!$error){
		if(!checkmail($correo)) {$mensaje[0]='txt-MSJ10-0';$error=true;}
		else{		
			$s="SELECT ID_USUARIO
					FROM adm_usuarios
				WHERE CORREO_U=:correo ".(!$nuevo?" AND ID_USUARIO<>$id_print":"");		
			$req = $dbEmpresa->prepare($s);	 
			$req->bindParam(':correo', $correo);	
			$req->execute();
			if($reg = $req->fetch())  {$mensaje[0]='txt-MSJ20-0';$error=true;}
		}
	}

	//////////////LINK//////////////
	if($nuevo){
		$link=cambiar_url($result["nomb"],2);		
		$link_busc=$link."%";		
		$s="SELECT adm_usuarios.ALIAS  AS LINK_PAGINA
			FROM adm_usuarios 
			WHERE (adm_usuarios.ALIAS LIKE :link OR adm_usuarios.ALIAS=:linkcompleto) ".(!$nuevo?" AND adm_usuarios.ID_USUARIO<>:id":""); 
		$ReqB = $dbEmpresa->prepare($s); 
		$ReqB->bindParam(':link',$link_busc, PDO::PARAM_STR);
		$ReqB->bindParam(':linkcompleto',$link);
		if(!$nuevo)	$ReqB->bindParam(':id', $id_print);
		$ReqB->execute();	
		$links=array();
		while($RegB = $ReqB->fetch()){				
			$caracteres=strlen($link)-strlen($RegB["LINK_PAGINA"]);
			$num_link=mb_substr($RegB["LINK_PAGINA"],$caracteres);
			if(is_numeric($num_link)) $links[]=$num_link;
			elseif($num_link==$RegB["LINK_PAGINA"]) $links[]=0;
		}		
		if(count($links)>0) $link.=max($links)+1;	
	}
	if(!$error){	
		try{ 
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();

			$type=$result["type"];
			$verify=$correo_old==$correo?$verify:0;
			$passw=substr(uniqid(),0,6);
			$link=0;
			
			$s="INSERT INTO adm_usuarios
				(ID_USUARIO
			,	ALIAS
			,	NOMBRE_U
			,	APELLIDO_U
			,	PASSWORD_U
			,	FECHA_U
			,	CORREO_U)
			VALUES
				(:id
			,	:link
			,	:nomb
			,	:apel
			,	SHA1(:passw)
			,	UTC_TIMESTAMP()
			,	:email)
			ON DUPLICATE KEY UPDATE
					NOMBRE_U=:nomb
				,	APELLIDO_U=:apel
				,	CORREO_U=:email
				,	FECHA_U=UTC_TIMESTAMP()
				,	VERIF_U=:verify";
			$req = $dbEmpresa->prepare($s);	
			$req->bindParam(':id', $id);		
			$req->bindParam(':link', $link);
			$req->bindParam(':nomb', $result["nomb"]);
			$req->bindParam(':apel', $result["apel"]);
			$req->bindParam(':passw', $passw);
			$req->bindParam(':email', $result["email"]);
			$req->bindParam(':verify', $verify);	
			$req->execute();
			if($nuevo) $id=$dbEmpresa->lastInsertId();

			$s='INSERT INTO x_usuario
					(ID_USUARIO
				,	TEL1_USUARIO
				,	TEL2_USUARIO
				,	TYPE_USUARIO
				,	BIO_USUARIO)
				VALUES
					(:id
				,	:tel1
				,	:tel2
				,	:type
				,	:bio)
				ON DUPLICATE KEY UPDATE
					TEL1_USUARIO=:tel1
				,	TEL2_USUARIO=:tel2
				,	TYPE_USUARIO=:type
				,	BIO_USUARIO=:bio';
			$req = $dbEmpresa->prepare($s);	
			$req->bindParam(':id', $id);
			$req->bindParam(':tel1', $result["tel1"]);
			$req->bindParam(':tel2', $result["tel2"]);
			$req->bindParam(':type', $result["type"]);
			$req->bindParam(':bio', $result["bio"]);
			$req->execute();

			///////////////////////////////////
			////////////////URLS///////////////
			///////////////////////////////////				
			$conteov=count($result["EDUrls"]);						
			if($conteov>0){	
				/***********/
				foreach ($result["EDUrls"] as $k => $X){									
					if($result["EDUrls"][$k]!=0){
						$EmpURL=$result["EmpURL"][$k];						
						$s="REPLACE INTO x_usuario_rs (ID_USUARIO,ID_URLS,URLS)
									VALUES($id,:IdURL,:EmpURL)";									
						$req = $dbEmpresa->prepare($s); 
						$req->bindParam(':IdURL',$result["IdURL"][$k]);	
						$req->bindParam(':EmpURL',$EmpURL);			
						$req->execute(); 					
					}
				}
				/***********/				
			}
			///////////////////////////////////
			//////////ESPECIALIDADES///////////
			///////////////////////////////////	
			$s="DELETE FROM x_usuario_espec WHERE ID_USUARIO=$id";
			$req = $dbEmpresa->prepare($s); 	
			$req->execute(); 	

			$conteov=count($result["nEspec"]);						
			if($conteov>0){	
				/***********/
				foreach ($result["nEspec"] as $k => $X){									
					if($result["nEspec"][$k]!=0){					
						$s="INSERT INTO x_usuario_espec (ID_USUARIO,ID_ESPEC)
									VALUES($id,:IdEspec)";									
						$req = $dbEmpresa->prepare($s); 
						$req->bindParam(':IdEspec',$result["IdEspec"][$k]);			
						$req->execute(); 					
					}
				}
				/***********/				
			}

			if($nuevo){
				try{
					/*******SEND EMAIL***********/						
					CuerpoMail($Email,$_PROYECTO,$_EMPRESA,$_CLIENTE,$_IDIOMA,$_TZ,$_GCLIENTE,$_USUARIO,$_GRUPO,$_PARAMETROS);											
					$code_01=sha1($id.$correo).md5($id.$correo).sha1($id);
					$to=array();
					$to[0]["mail"]=$correo;
					$to[0]["name"]=$result["nomb"].' '.$result["apel"];

					$Asunto=$Email[1][0]['title'];
					$URL_Verif='http://'.$_SERVER["HTTP_HOST"]."/verification/?code=".$code_01;
					$html_cont=sprintf($Email[1][0]['body'],$correo,$passw,$URL_Verif);
					$rtamail=send_email_srv($_PARAMETROS,$Asunto,$html_cont,$to,array(),array(),true,$Email[1][0]['alt']);
					$salidas["rta_mail"]=$rtamail;
					/*******SEND EMAIL***********/
				}
				catch (Exception $e){			
				}
			}

			$cnf=36;
			$UploadDeleteArgs=array(
						'S3_BUCKET'=>$_PARAMETROS["S3_BUCKET"]
					,	'PROYECTO'=>$_PROYECTO
					,	'EMPRESA'=>$_EMPRESA
					,	'MODULE'=>$cnf
					,	'OBJECT'=>$id
					,	'TP_FILE'=>'img');
			
			if($result["imagen"]==1){
				$tamano=$_FILES[$control_img]["size"];
				$ubicacion=$_FILES[$control_img]["tmp_name"];
				$finfo = finfo_open(FILEINFO_MIME_TYPE); 	
				$tipo=finfo_file($finfo, $ubicacion);	
				finfo_close($finfo);	
				if(($tamano>=$fmin) && ($tamano<=$fmax)){
					if(fValid($tipo,$_files_clase[0])){
						$AwsS3 = Aws::factory(array('key' => $_PARAMETROS['S3_KEY'],'secret' => $_PARAMETROS['S3_SECRET']));
						UploadFiles($AwsS3,$control_img,$dbEmpresa,$UploadDeleteArgs);
					}
				}
			}
			elseif($result["imagen"]==3){
				$AwsS3 = Aws::factory(array('key' => $_PARAMETROS['S3_KEY'],'secret' => $_PARAMETROS['S3_SECRET']));
				DeleteFiles($AwsS3,$dbEmpresa,$UploadDeleteArgs);			
			}
			if($result["imagen"]==1||$result["imagen"]==3){
				$s="UPDATE adm_usuarios 
					SET ID_FILE=IFNULL((SELECT adm_files.ID_FILE FROM adm_files WHERE adm_files.F_MODULE=$cnf AND adm_files.ID_OBJECT=$id AND adm_files.F_SUB_EXT='org' AND adm_files.F_TP_FILE='img' LIMIT 1),0)
					WHERE ID_USUARIO=$id";
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
/*************************/
/*************************/
/**   Remodelaciones *****/
/*************************/ 
elseif($cnf==501){ 
	if($result["nom"]=='' 
	||	$result["descp"]=='')
	{
		$mensaje[0]='txt-MSJ1-0';$error=true;
	}

	if(!$nuevo){
		$sWhere=encrip_mysql('y_proyectos.ID_PROY');
		$s="SELECT ID_PROY AS ID FROM y_proyectos WHERE $sWhere=:id LIMIT 1 ";
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		$req->execute();	
		if($reg = $req->fetch())	$id=$reg["ID"];
		else {$mensaje[0]='txt-MSJ9-0';$error=true;}				
		$id_print=$id;
	}
	else $id_print="NULL";	

	if(!$error){	
		try{ 
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();

			$s="INSERT INTO y_proyectos
				(ID_PROY
			,	ID_USUARIO
			,	FECHAS_PROY
			,	NOMB_PROY
			,	DESC_PROY)
			VALUES
				($id_print
			,	:id_user
			,	UTC_TIMESTAMP()
			,	:nom
			,	:descp)
			ON DUPLICATE KEY UPDATE
				FECHAS_PROY=UTC_TIMESTAMP()
			,	ID_USUARIO=:id_user
			,	NOMB_PROY=:nom
			,	DESC_PROY=:descp";
			$req = $dbEmpresa->prepare($s);		
			$req->bindParam(':nom', $result["nom"]);
			$req->bindParam(':descp', $result["descp"]);
			$req->bindParam(':id_user', $result["id_user"]);
			$req->execute();
			if($nuevo) $id=$dbEmpresa->lastInsertId();

			$dbEmpresa->commit();
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}
	}	
}
/*************************/
/*************************/
/***** Proyectos *********/
/*************************/ 
elseif($cnf==502){ 
	if($result["tit"]=='' 
	||	$result["fechaI"]==''
	||	$result["fechaF"]==''
	||	$result["comen"]=='')
	{
		$mensaje[0]='txt-MSJ1-0';$error=true;
	}

	if(!$nuevo){
		$sWhere=encrip_mysql('x_ofertas.ID_OFERTA');
		$s="SELECT ID_OFERTA AS ID FROM x_ofertas WHERE $sWhere=:id LIMIT 1 ";
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		$req->execute();	
		if($reg = $req->fetch())	$id=$reg["ID"];
		else {$mensaje[0]='txt-MSJ9-0';$error=true;}				
		$id_print=$id;
	}
	if(!$error){	
		try{ 
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();

			$s="INSERT INTO x_ofertas
				(ID_OFERTA
			,	TITLE_OFERTA
			,	ID_USUARIO
			,	ID_CIUDAD
			,	ID_ESPEC
			,	FECHAS_OFERTA
			,	FECHAI_OFERTA
			,	FECHAF_OFERTA
			,	COMENT_OFERT
			,	CONTACT_OFERTA)
			VALUES
				($id_print
			,	:tit
			,	$_USUARIO
			,	:ciudad_ori
			,	:espec
			,	UTC_TIMESTAMP()
			,	STR_TO_DATE(:fechaI,'%d/%m/%Y')
			,	STR_TO_DATE(:fechaF,'%d/%m/%Y')
			,	:comen
			,	:contact)
			ON DUPLICATE KEY UPDATE
				TITLE_OFERTA=:tit			
			,	ID_CIUDAD=:ciudad_ori
			,	ID_ESPEC=:espec
			,	FECHAS_OFERTA=UTC_TIMESTAMP()
			,	FECHAI_OFERTA=STR_TO_DATE(:fechaI,'%d/%m/%Y')
			,	FECHAF_OFERTA=STR_TO_DATE(:fechaF,'%d/%m/%Y')
			,	COMENT_OFERT=:comen
			,	CONTACT_OFERTA=:contact";
			$req = $dbEmpresa->prepare($s);		
			$req->bindParam(':tit', $result["tit"]);
			$req->bindParam(':ciudad_ori', $result["ciudad_ori"]);
			$req->bindParam(':espec', $result["espec"]);
			$req->bindParam(':fechaI', $result["fechaI"]);
			$req->bindParam(':fechaF', $result["fechaF"]);
			$req->bindParam(':comen', $result["comen"]);
			$req->bindParam(':contact', $result["contact"]);
			$req->execute();

			if($nuevo) 
				$id=$dbEmpresa->lastInsertId();

			$dbEmpresa->commit();
		}
			catch (Exception $e){
				$dbEmpresa->rollBack();
				$err_str=$e->getMessage();
		}
	}	
}
/*************************/
/*************************/
/*** Estado de Cuenta ****/
/*************************/ 
elseif($cnf==505){ 
	if($result["id_isntl"]=='' 
	||	$result["punt"]==''
	||	$result["comen"]=='')
	{
		$mensaje[0]='txt-MSJ1-0';$error=true;
	}

	if(!$nuevo){
		$sWhere=encrip_mysql('y_ecuenta.ID_ECUENTA');
		$s="SELECT ID_ECUENTA AS ID FROM y_ecuenta WHERE $sWhere=:id LIMIT 1 ";
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		$req->execute();	
		if($reg = $req->fetch())	$id=$reg["ID"];
		else {$mensaje[0]='txt-MSJ9-0';$error=true;}				
		$id_print=$id;
	}
	else $id_print="NULL";	
	if($result["accion"]==1){
		$inipCuenta=$result["punt"];
		$outpCuenta=0;
	}
	elseif($result["accion"]==2){
		$inipCuenta=0;
		$outpCuenta=$result["punt"];

		$puntos=0;
		$s='SELECT POINTS_USUARIO FROM x_usuario WHERE ID_USUARIO=:id_isntl LIMIT 1';
		$reqP = $dbEmpresa->prepare($s); 
		$reqP->bindParam(':id', $id_sha);
		$reqP->execute();
		if($regP = $reqP->fetch())	$puntos=$regP['POINTS_USUARIO'];
		
		if($result["punt"]>$puntos){
			$mensaje[0]='txt-238-1';$error=true;
		}
		

	}




	if(!$error){	
		try{ 
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();

			$s="INSERT INTO y_ecuenta
				(ID_ECUENTA
			,	ID_USUARIO_M
			,	ID_USUARIO_A
			,	FECHAS_ECUENTA
			,	COMMEN_ECUENTA
			,	INIP_ECUENTA
			,	OUTP_ECUENTA)
			VALUES
				($id_print
			,	:id_isntl
			,	$_USUARIO
			,	UTC_TIMESTAMP()
			,	:comen
			,	$inipCuenta
			,	$outpCuenta)";
			$req = $dbEmpresa->prepare($s);		
			$req->bindParam(':id_isntl', $result["id_isntl"]);
			$req->bindParam(':comen', $result["comen"]);
			$req->execute();
			if($nuevo) 	$id=$dbEmpresa->lastInsertId();

			$s='UPDATE x_usuario
				SET POINTS_USUARIO=(SELECT SUM(INIP_ECUENTA)-SUM(OUTP_ECUENTA) FROM y_ecuenta WHERE ID_USUARIO_M=:id_isntl)
				WHERE ID_USUARIO=:id_isntl';
			$req = $dbEmpresa->prepare($s);		
			$req->bindParam(':id_isntl', $result["id_isntl"]);
			$req->execute();


			$dbEmpresa->commit();
		}
			catch (Exception $e){
				$dbEmpresa->rollBack();
				$err_str=$e->getMessage();
		}
	}	
}
/*************************/
/*************************/
/*****  Noticias *********/
/*************************/ 
elseif($cnf==506){
	if($result["title"]==''||$result["meta"]=='')
		{$mensaje[0]='txt-MSJ1-0';$error=true;} 
	//	
	if(!$nuevo){
		$sWhere=encrip_mysql('y_noti.ID_NOTI');
		$s=" SELECT y_noti.ID_NOTI AS ID FROM y_noti WHERE $sWhere=:id LIMIT 1";	
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		$req->execute();	
		if($reg = $req->fetch()) $id=$reg["ID"];
		else {$mensaje[0]='txt-MSJ9-0';$error=true;}
		$id_print=$id;
	}
	else $id_print="NULL";

	//////////////LINK//////////////
	$link_emp=cambiar_url($result["title"],2);			
	$link = rawurlencode($link_emp);
	$link_busc=$link."%";
	$s="SELECT y_noti.SLUG_NOTI  AS LINK_PAGINA
		FROM y_noti 				
		WHERE (y_noti.SLUG_NOTI LIKE :link OR y_noti.SLUG_NOTI=:linkcompleto)".(!$nuevo?" AND y_noti.ID_NOTI<>$id_print":""); 
	$ReqB = $dbEmpresa->prepare($s); 
	$ReqB->bindParam(':link',$link_busc, PDO::PARAM_STR);
	$ReqB->bindParam(':linkcompleto',$link);
	$ReqB->execute();	
	$links=array();
	while($RegB = $ReqB->fetch()){				
		$caracteres=strlen($link)-strlen($RegB["LINK_PAGINA"]);
		$num_link=mb_substr($RegB["LINK_PAGINA"],$caracteres);
		if(is_numeric($num_link)) $links[]=$num_link;
		elseif($num_link==$RegB["LINK_PAGINA"]) $links[]=0;
	}		
	if(count($links)>0) $link.=max($links)+1;
	//////////////LINK//////////////

	if(!$error){	
		try{ 
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();

			$s="INSERT INTO y_noti
					(ID_NOTI
				,	ID_USUARIO
				,	TYPE_NOTI
				,	TITLE_NOTI
				,	SLUG_NOTI
				,	MTITLE_NOTI
				,	MDESC_NOTI
				,	FECHAS_NOTI
				,	ACTIV_NOTI
				,	CONT_NOTI)
				VALUES
					($id_print
				,	$_USUARIO
				,	:tipo
				,	:title
				,	:link
				,	:meta
				,	:desc
				,	UTC_TIMESTAMP()
				,	:activa
				,	:content)
				ON DUPLICATE KEY UPDATE
						TYPE_NOTI=:tipo
					,	TITLE_NOTI=:title
					,	SLUG_NOTI=:link
					,	MTITLE_NOTI=:meta
					,	MDESC_NOTI=:desc
					,	ACTIV_NOTI=:activa
					,	CONT_NOTI=:content";
			$req = $dbEmpresa->prepare($s);			
			$req->bindParam(':tipo', $result["tipo"]);
			$req->bindParam(':title', $result["title"]);
			$req->bindParam(':link', $link);
			$req->bindParam(':meta', $result["meta"]);
			$req->bindParam(':desc', $result["desc"]);
			$req->bindParam(':activa', $result["activa"]);
			$req->bindParam(':content', $result["content"]);
			$req->execute();
			if($nuevo) $id=$dbEmpresa->lastInsertId();	

			$UploadDeleteArgs=array(
						'S3_BUCKET'=>$_PARAMETROS["S3_BUCKET"]
					,	'PROYECTO'=>$_PROYECTO
					,	'EMPRESA'=>$_EMPRESA
					,	'MODULE'=>$cnf
					,	'OBJECT'=>$id
					,	'TP_FILE'=>'img');

			if(($result["imagen"]==1)){				
				$tamano=$_FILES[$control_img]["size"];
				$ubicacion=$_FILES[$control_img]["tmp_name"];
				$finfo = finfo_open(FILEINFO_MIME_TYPE); 	
				$tipo=finfo_file($finfo, $ubicacion);	
				finfo_close($finfo);	
				if(($tamano>=$fmin) && ($tamano<=$fmax)){
					if(fValid($tipo,$_files_clase[0])){
						$AwsS3 = Aws::factory(array('key' => $_PARAMETROS['S3_KEY'],'secret' => $_PARAMETROS['S3_SECRET']));
						UploadFiles($AwsS3,$control_img,$dbEmpresa,$UploadDeleteArgs);
					}
				}
			}
			elseif(($result["imagen"]==3)){
				$AwsS3 = Aws::factory(array('key' => $_PARAMETROS['S3_KEY'],'secret' => $_PARAMETROS['S3_SECRET']));
				DeleteFiles($AwsS3,$dbEmpresa,$UploadDeleteArgs);
			}
			if($result["imagen"]==1||$result["imagen"]==3){
				$s="UPDATE y_noti 
					SET ID_FILE=IFNULL((SELECT adm_files.ID_FILE FROM adm_files WHERE adm_files.F_MODULE=$cnf AND adm_files.ID_OBJECT=$id AND adm_files.F_SUB_EXT='org' AND adm_files.F_TP_FILE='img' LIMIT 1),0)
					WHERE ID_NOTI=$id";
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
/*************************/
/*************************/
/**** Publicidad *********/
/*************************/ 
elseif($cnf==507){

	if($result["nombre"]==''||$result["fechaPI"]==''||$result["fechaPF"]=='')
		{$mensaje[0]='txt-MSJ1-0';$error=true;}
	//	
	if(!$nuevo){
		$sWhere=encrip_mysql('y_publicidad.ID_PUBL');
		$s=" SELECT y_publicidad.ID_PUBL AS ID FROM y_publicidad WHERE $sWhere=:id LIMIT 1";	
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		$req->execute();	
		if($reg = $req->fetch()) $id=$reg["ID"];
		else {$mensaje[0]='txt-MSJ9-0';$error=true;}
		$id_print=$id;
	}
	else $id_print="NULL";

	if(!$error){	
		try{ 
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();

			$s="INSERT INTO y_publicidad
					(ID_PUBL
				,	ID_USUARIO
				,	NAME_PUBL
				,	TITLE_PUBL
				,	FECHAS_PUBL
				,	FECHAI_PUBL
				,	FECHAF_PUBL
				,	ACTI_PUBL
				,	MOVIL_PUBL)
				VALUES
					($id_print
				,	$_USUARIO
				,	:nombre
				,	:title
				,	UTC_TIMESTAMP()
				,	STR_TO_DATE(:fechaPI,'%d/%m/%Y')
				,	STR_TO_DATE(:fechaPF,'%d/%m/%Y')
				,	:activa
				,	:movil)
				ON DUPLICATE KEY UPDATE
						NAME_PUBL=:nombre
					,	TITLE_PUBL=:title
					,	FECHAI_PUBL=STR_TO_DATE(:fechaPI,'%d/%m/%Y')
					,	FECHAF_PUBL=STR_TO_DATE(:fechaPF,'%d/%m/%Y')
					,	ACTI_PUBL=:activa
					,	MOVIL_PUBL=:movil";
			$req = $dbEmpresa->prepare($s);			
			$req->bindParam(':nombre', $result["nombre"]);
			$req->bindParam(':title', $result["title"]);
			$req->bindParam(':fechaPI', $result["fechaPI"]);
			$req->bindParam(':fechaPF', $result["fechaPF"]);
			$req->bindParam(':activa', $result["activa"]);
			$req->bindParam(':movil', $result["movil"]);
			$req->execute();
			if($nuevo) $id=$dbEmpresa->lastInsertId();	

			$UploadDeleteArgs=array(
						'S3_BUCKET'=>$_PARAMETROS["S3_BUCKET"]
					,	'PROYECTO'=>$_PROYECTO
					,	'EMPRESA'=>$_EMPRESA
					,	'MODULE'=>$cnf
					,	'OBJECT'=>$id
					,	'TP_FILE'=>'img');

			if(($result["imagen"]==1)){				
				$tamano=$_FILES[$control_img]["size"];
				$ubicacion=$_FILES[$control_img]["tmp_name"];
				$finfo = finfo_open(FILEINFO_MIME_TYPE); 	
				$tipo=finfo_file($finfo, $ubicacion);	
				finfo_close($finfo);	
				if(($tamano>=$fmin) && ($tamano<=$fmax)){
					if(fValid($tipo,$_files_clase[0])){
						$AwsS3 = Aws::factory(array('key' => $_PARAMETROS['S3_KEY'],'secret' => $_PARAMETROS['S3_SECRET']));
						UploadFiles($AwsS3,$control_img,$dbEmpresa,$UploadDeleteArgs);
					}
				}
			}
			elseif(($result["imagen"]==3)){
				$AwsS3 = Aws::factory(array('key' => $_PARAMETROS['S3_KEY'],'secret' => $_PARAMETROS['S3_SECRET']));
				DeleteFiles($AwsS3,$dbEmpresa,$UploadDeleteArgs);
			}
			if($result["imagen"]==1||$result["imagen"]==3){
				$s="UPDATE y_publicidad 
					SET ID_FILE=IFNULL((SELECT adm_files.ID_FILE FROM adm_files WHERE adm_files.F_MODULE=$cnf AND adm_files.ID_OBJECT=$id AND adm_files.F_SUB_EXT='org' AND adm_files.F_TP_FILE='img' LIMIT 1),0)
					WHERE ID_PUBL=$id";
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
/*************************/
/*************************/
/****Especialidades*******/
/*************************/ 
elseif($cnf==509){ 

	if($result["espec"]=='' )
	{
		$mensaje[0]='txt-MSJ1-0';$error=true;
	}

	if(!$nuevo){
		$sWhere=encrip_mysql('z_espec.ID_ESPEC');
		$s="SELECT ID_ESPEC AS ID FROM z_espec WHERE $sWhere=:id LIMIT 1 ";
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		$req->execute();	
		if($reg = $req->fetch())	$id=$reg["ID"];
		else {$mensaje[0]='txt-MSJ9-0';$error=true;}				
		$id_print=$id;
	}
	else $id_print="NULL";	

	if(!$error){	
		try{ 
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();

			$s="INSERT INTO z_espec
				(ID_ESPEC
			,	NAME_ESPEC)
			VALUES
				($id_print
			,	:espec)
			ON DUPLICATE KEY UPDATE
					NAME_ESPEC=:espec";
			$req = $dbEmpresa->prepare($s);		
			$req->bindParam(':espec', $result["espec"]);
			$req->execute();
			if($nuevo) 
				$id=$dbEmpresa->lastInsertId();

			$dbEmpresa->commit();
		}
			catch (Exception $e){
				$dbEmpresa->rollBack();
				$err_str=$e->getMessage();
		}
	}	
}

?>

	
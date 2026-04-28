<?php
require 		"phplib/s3/aws.phar";
use 			Aws\Common\Aws;	

if($cnf==36) 	$permiso=$PermisosA[8]["P"]==1;
else 			$permiso=$PermisosA[$cnf]["P"]==1;
if(!$permiso)PrintErr(array('txt-MSJ16-0'));

if($cnf==19){
	//
	if(($result["nombre"]=='')||($result["ciudad"]==0)||($_PROYECTO==8 && $result["moneda"]==0))
		{$mensaje[0]='txt-MSJ1-0';$error=true;}
	//
	
	if(!$nuevo){
		$sWhere=encrip_mysql('s_cresp.ID_RESP');
		$s="SELECT ID_RESP FROM s_cresp WHERE $sWhere=:id AND ID_MEMPRESA=$_CLIENTE LIMIT 1 ";
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		$req->execute();	
		if($reg = $req->fetch()) $id=$reg["ID_RESP"];
		else {$mensaje[0]='txt-MSJ9-0';$error=true;}
		$id_print=$id;
	}
	else $id_print="NULL";	
	
	if(!$error){
		//////////////LINK//////////////
		$link=cambiar_url($result["nombre"],2);			
		
		$link_busc=$link."%";
		$sWhere=encrip_mysql('s_cresp.ID_RESP');	
		$s="SELECT s_cresp.SLUG_RESP  AS LINK_PAGINA
			FROM s_cresp 
			WHERE (s_cresp.SLUG_RESP LIKE :link OR s_cresp.SLUG_RESP=:linkcompleto) AND s_cresp.ID_MEMPRESA=$_CLIENTE ".(!$nuevo?" AND $sWhere<>:id":""); 
		$ReqB = $dbEmpresa->prepare($s); 
		$ReqB->bindParam(':link',$link_busc, PDO::PARAM_STR);
		$ReqB->bindParam(':linkcompleto',$link);
		if(!$nuevo)	$ReqB->bindParam(':id', $id_sha);
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

		try{  				
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);			
			$dbEmpresa->beginTransaction();
			$ppal=($result["ppal"]==1||!isset($result["ppal"]))?1:0;
			if($ppal==1){
				$s="UPDATE s_cresp SET PPAL=0 WHERE ID_MEMPRESA=$_CLIENTE AND PPAL=1";
				$req = $dbEmpresa->prepare($s);
				$req->execute();
			}
			$geo='POINT('.(float)$result["latu"].' '.(float)$result["lonu"].')';			
			$s="INSERT INTO s_cresp
				(ID_RESP,NOMB_RESP,ABR_RESP,SLUG_RESP,COMENT_RESP,DIRECCION,TELEFONO,ID_CIUDAD,LOCATION,ZOOM,PPAL,ID_MEMPRESA)
			VALUES($id_print,:nombre,:abr,:link,:desc,:direc,:tel,:ciudad,GeomFromText(:geo),:zoom,:ppal,$_CLIENTE)
			ON DUPLICATE KEY UPDATE
				NOMB_RESP=:nombre,
				ABR_RESP=:abr,
				SLUG_RESP=:link,
				COMENT_RESP=:desc,
				DIRECCION=:direc,
				TELEFONO=:tel,
				ID_CIUDAD=:ciudad,
				LOCATION=GeomFromText(:geo),
				ZOOM=:zoom,
				PPAL=:ppal";
			$Eqreq = $dbEmpresa->prepare($s); 
			$Eqreq->bindParam(':nombre', $result["nombre"]);
			$Eqreq->bindParam(':abr', $result["abr"]);
			$Eqreq->bindParam(':link', $link);
			$Eqreq->bindParam(':desc', $result["desc"]);
			$Eqreq->bindParam(':direc', $result["direc"]);
			$Eqreq->bindParam(':tel', $result["tel"]);
			$Eqreq->bindParam(':ciudad', $result["ciudad"]);
			$Eqreq->bindParam(':geo', $geo);
			$Eqreq->bindParam(':zoom', $result["zoom"]);
			$Eqreq->bindParam(':ppal', $ppal);
			$Eqreq->execute();
			
			if($nuevo) $id=$dbEmpresa->lastInsertId();		
			$id_sha=encrip($id);

			if($_PROYECTO==8){
				$s="REPLACE INTO s_cresp_moneda (ID_RESP,ID_MONEDA)
						VALUES($id,:moneda)";
				$req = $dbEmpresa->prepare($s); 
				$req->bindParam(':moneda', $result["moneda"]);
				$req->execute();
			}
				
			if($nuevo){	
				$s="SELECT * FROM adm_grupos WHERE ID_MEMPRESA=$_CLIENTE AND ADM_GRUPO IN (1,2)";	
				$req = $dbEmpresa->prepare($s); 
				$req->execute();	
				while($reg = $req->fetch()){
					if($reg["ID_GRUPO"]!=$_GRUPO){ //INSERTA EN GRUPO ADMINISTRADOR
						$s="REPLACE INTO s_cresp_grupo
							(ID_GRUPO,ID_RESP)
						VALUES(:idGrupo,:id)";
						$Gqreq = $dbEmpresa->prepare($s); 
						$Gqreq->bindParam(':idGrupo', $reg["ID_GRUPO"]);
						$Gqreq->bindParam(':id', $id);
						$Gqreq->execute();
					}
				}
				$s="REPLACE INTO s_cresp_grupo
					(ID_GRUPO,ID_RESP)
				VALUES(:idGrupo,:id)";
				$Gqreq = $dbEmpresa->prepare($s); 
				$Gqreq->bindParam(':idGrupo', $_GRUPO);
				$Gqreq->bindParam(':id', $id);
				$Gqreq->execute();
			}
			//INSERTA AREAS
			if($PermisosA[8]["P"]==1){				
				if(count($result["IdGrupo"])>0){	
					$BloqueId=$result["IdGrupo"];
					$Chequeados=$result["Grupos"];
					$insert=array();
					$delete=array();
					$IdInsert=array();
					$IdDelete=array();
					foreach ($BloqueId as $k => $Bloque){ 
						if(isset($Chequeados[$k])){
							$IdInsert[$k]=$BloqueId[$k];
							$insert[$k]="(:$k,$id)";
						}
						else{
							$IdDelete[$k]=$BloqueId[$k];
							$delete[$k]=":$k";
						}
					}		
					if(count($IdInsert)>0){
						$insert_str=implode(",",$insert);
						$s="REPLACE INTO s_cresp_grupo (ID_GRUPO,ID_RESP)
							VALUES $insert_str";
						$req = $dbEmpresa->prepare($s);			
						foreach ($IdInsert as $k => $iID){
							$req ->bindParam(":$k", $IdInsert[$k]);
						}
						$req ->execute();
					}
					if(count($IdDelete)>0){
						$delete_str=implode(",",$delete);
						$s="DELETE FROM s_cresp_grupo WHERE ID_RESP=$id AND 
							ID_GRUPO IN ($delete_str)";
						$req = $dbEmpresa->prepare($s);			
						foreach ($IdDelete as $k => $iID){
							$req ->bindParam(":$k", $IdDelete[$k]);
						}
						$req ->execute();
					}
					
				}
			}
			//CIUDADES			
			$conteov=count($result["EDCiudad"]);						
			if($conteov>0){	
				/***********/
				foreach ($result["EDCiudad"] as $k => $GastoG){		
					if($result["EDCiudad"][$k]==10){
						$s="DELETE FROM s_cresp_ciudades WHERE ID_RESP=$id AND ID_CIUDAD=:IdCiudad";
						$req = $dbEmpresa->prepare($s); 
						$req->bindParam(':IdCiudad', $result["IdCiudad"][$k]);
						$req->execute();						
					}					
					if($result["EDCiudad"][$k]==20){
						$s="REPLACE INTO s_cresp_ciudades (ID_RESP,ID_CIUDAD)
									VALUES($id,:IdCiudad)";									
						$req = $dbEmpresa->prepare($s); 
						$req->bindParam(':IdCiudad',$result["IdCiudad"][$k]);			
						$req->execute(); 	
									
					}

				}
				/***********/				
			}
			//**//

			//CARACTERISTICAS					
			$conteov=count($result["EDCar"]);						
			if($conteov>0){	
				/***********/
				foreach ($result["EDCar"] as $k => $GastoG){	
					
					if($result["EDCar"][$k]==20){

						$s="INSERT INTO s_cresp_caract (ID_RESP,ID_CAR,DATO_CAREQ)
									VALUES($id,:IdCar,:dato)";									
						$req = $dbEmpresa->prepare($s);	
						$req->bindParam(':IdCar', $result["IdCar"][$k]);	
						$req->bindParam(':dato', $result["ValoC"][$k]);		
						$req->execute(); 					
					}
					if($result["EDCar"][$k]==1){
						$s="UPDATE s_cresp_caract 
							SET 
							ID_CAR=:IdCar,
							DATO_CAREQ=:dato
							WHERE ID_CARITEM=:IdCarData";
						$req = $dbEmpresa->prepare($s); 
						$req->bindParam(':IdCar', $result["IdCar"][$k]);	
						$req->bindParam(':dato', $result["ValoC"][$k]);	
						$req->bindParam(':IdCarData', $result["IdCarData"][$k]);
						$req->execute();		
					}
				}
				/***********/				
			}
			$dbEmpresa->commit();


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
				$s="UPDATE s_cresp 
					SET ID_FILE=IFNULL((SELECT adm_files.ID_FILE FROM adm_files WHERE adm_files.F_MODULE=$cnf AND adm_files.ID_OBJECT=$id AND adm_files.F_SUB_EXT='org' AND adm_files.F_TP_FILE='img' LIMIT 1),0)
					WHERE ID_RESP=$id";
				$dbEmpresa->exec($s);
			}
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}		
	}
}
//GRUPOS
elseif($cnf==8){	
	//
	if(($result["nombre"]==''))
		{$mensaje[0]='txt-MSJ1-0';$error=true;}
	//
	$flag=1;
	if(!$nuevo){
		$sWhere=encrip_mysql('adm_grupos.ID_GRUPO');
		$s="SELECT ID_GRUPO,ADM_GRUPO FROM adm_grupos WHERE $sWhere=:id AND ID_MEMPRESA=$_CLIENTE LIMIT 1 ";
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		$req->execute();	
		if($reg = $req->fetch()){
			$flag=$reg["ADM_GRUPO"]!=0?1:0;
			$id=$reg["ID_GRUPO"];
		}
		else {$mensaje[0]='txt-MSJ9-0';$error=true;}
		if(!((($PermisosA[10000]["P"]==1)||($flag!=1))&&($id!=$_GRUPO))) {$mensaje[0]='txt-MSJ9-0';$error=true;}		
		$id_print=$id;
	}
	else $id_print="NULL";	
	
	if(!$error){		
		try{  				
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);			
			$dbEmpresa->beginTransaction();			
			$s="INSERT INTO adm_grupos
				(ID_GRUPO,DESC_GRUPO,COMEN_GRUPO,ID_MEMPRESA)
			VALUES($id_print,:nombre,:desc,$_CLIENTE)
			ON DUPLICATE KEY UPDATE
				DESC_GRUPO=:nombre,
				COMEN_GRUPO=:desc";
			$Repreq = $dbEmpresa->prepare($s); 
			$Repreq->bindParam(':nombre', $result["nombre"]);
			$Repreq->bindParam(':desc', $result["desc"]);			
			$Repreq->execute();
						
			if($nuevo) $id=$dbEmpresa->lastInsertId();		
			$id_sha=encrip($id);	
			
			if((($PermisosA[10000]["P"]==1)||($flag!=1))&&($reg["ID_GRUPO"]!=$_GRUPO)){
				$conteov=count($result["IdVentana"]);						
				if($conteov>0){	
					$ventanas=$result["IdVentana"];
					$ven_imp_id=array();
					foreach ($ventanas as $k => $ven){ 
						$Aplica=isset($result["Ventanas"][$k])?1:0;
						$ven_imp_id[]="(:$k,$id,$Aplica)";
					}
					$ven_imp=implode(",",$ven_imp_id);

					$s="REPLACE INTO adm_grupos_ven (ID_VENTANA,ID_GRUPO,PERMISO_GRUPOVEN)
						VALUES $ven_imp";
					$req = $dbEmpresa->prepare($s);			
					foreach ($ventanas as $k => $ven){
						$req ->bindParam(":$k", $ventanas[$k]);
					}
					$req ->execute();
				}
			}

			//INSERTA AREAS
			if(($PermisosA[10000]["P"]==1)||($flag!=1)){				
				if(count($result["IdArea"])>0){	
					$BloqueId=$result["IdArea"];
					$Chequeados=$result["Areas"];
					$insert=array();
					$delete=array();
					$IdInsert=array();
					$IdDelete=array();
					foreach ($BloqueId as $k => $Bloque){ 
						if(isset($Chequeados[$k])){
							$IdInsert[$k]=$BloqueId[$k];
							$insert[$k]="($id,:$k)";
						}
						else{
							$IdDelete[$k]=$BloqueId[$k];
							$delete[$k]=":$k";
						}
					}		
					if(count($IdInsert)>0){
						$insert_str=implode(",",$insert);
						$s="REPLACE INTO s_cresp_grupo (ID_GRUPO,ID_RESP)
							VALUES $insert_str";
						$req = $dbEmpresa->prepare($s);			
						foreach ($IdInsert as $k => $iID){
							$req ->bindParam(":$k", $IdInsert[$k]);
						}
						$req ->execute();
					}
					if(count($IdDelete)>0){
						$delete_str=implode(",",$delete);
						$s="DELETE FROM s_cresp_grupo WHERE ID_GRUPO=$id AND 
							ID_RESP IN ($delete_str)";
						$req = $dbEmpresa->prepare($s);			
						foreach ($IdDelete as $k => $iID){
							$req ->bindParam(":$k", $IdDelete[$k]);
						}
						$req ->execute();
					}
				}
			}
			$dbEmpresa->commit();	
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}		
	}
}
elseif($cnf==36){	
	if($result["usexist"]==2){
		$correo=$result["correo"];
		
		//
		if($result["correo"]==''||$result["nombre"]=='')
			{$mensaje[0]='txt-MSJ1-0';$error=true;}
		//
		if($nuevo){
			$pass02=$result["password"];
			if((mb_strlen($pass02)<$minimo_pass))	{$mensaje[0]='txt-MSJ15-0';$error=true;}
		}
		if(!$nuevo){
			$sWhere=encrip_mysql('adm_usuarios.ID_USUARIO');
			$s="SELECT ID_USUARIO FROM adm_usuarios WHERE $sWhere=:id LIMIT 1 ";
			$req = $dbEmpresa->prepare($s); 
			$req->bindParam(':id', $id_sha);
			$req->execute();	
			if($reg = $req->fetch()) $id=$reg["ID_USUARIO"];
			else {$mensaje[0]='txt-MSJ9-0';$error=true;}		
			if($id==$_USUARIO) {$mensaje[0]='txt-MSJ9-0';$error=true;}		
			$id_print=$id;
		}
		else $id_print="NULL";	
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

		if(!$error){	
			try{  				
				$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);			
				$dbEmpresa->beginTransaction();			
				if($nuevo){
					$rnd=uniqid();
					$s="INSERT INTO adm_usuarios
						(ALIAS,NOMBRE_U,APELLIDO_U,CORREO_U,PASSWORD_U,FECHA_U,ID_IDIOMA)
					VALUES(:rnd,:nombre,:apellido,:correo,:password,UTC_TIMESTAMP(),1)";
					$Repreq = $dbEmpresa->prepare($s); 
					$Repreq->bindParam(':password', sha1($pass02));
					$Repreq->bindParam(':rnd', $rnd);
				}
				else{
					$s="UPDATE adm_usuarios
						SET
							CORREO_U=:correo,
							NOMBRE_U=:nombre,
							APELLIDO_U=:apellido
						WHERE ID_USUARIO=$id_print";
					$Repreq = $dbEmpresa->prepare($s);
				}
				$Repreq->bindParam(':correo', $correo);
				$Repreq->bindParam(':nombre', $result["nombre"]);
				$Repreq->bindParam(':apellido', $result["apellido"]);
				
				$Repreq->execute();									
				if($nuevo) $id=$dbEmpresa->lastInsertId();	
				
				if($PermisosA[10000]["P"]==1){
					$grupos=$result['IdGroup'];					
					foreach ($grupos as $key => $IdGroup) {
						$IdMEmpresa=$key;
						$GrLast=isset($result['GrLast'][$key])?1:0;
						if($IdGroup==0){
							$s='DELETE FROM adm_usuarios_empresa
								WHERE ID_USUARIO=:id AND ID_MEMPRESA=:IdMEmpresa LIMIT 1';
							$req = $dbEmpresa->prepare($s);
							$req->bindValue(':id', $id);
							$req->bindValue(':IdMEmpresa', $IdMEmpresa);
							$req->execute();	
						}
						else{
							$s='INSERT INTO adm_usuarios_empresa
								(ID_USUARIO
								,ID_MEMPRESA
								,ID_GRUPO
								,LAST)
							VALUES(
								:id
								,:IdMEmpresa
								,:IdGroup
								,:GrLast)
							ON DUPLICATE KEY UPDATE
								LAST=:GrLast
							,	ID_GRUPO=:IdGroup';
							$req = $dbEmpresa->prepare($s);
							$req->bindValue(':id', $id);
							$req->bindValue(':IdMEmpresa', $IdMEmpresa);
							$req->bindValue(':IdGroup', $IdGroup);
							$req->bindValue(':GrLast', $GrLast);
							$req->execute();	
						}
					}
					$s='UPDATE adm_usuarios
						SET FLAG_U=:flagus
						WHERE ID_USUARIO=:id LIMIT 1';
					$req = $dbEmpresa->prepare($s);
					$req->bindValue(':id', $id);
					$req->bindValue(':flagus', $result['flagus']);
					$req->execute();
				}
				else{
					$sGrupo="SELECT adm_grupos.ID_GRUPO FROM adm_grupos 
						WHERE adm_grupos.ID_GRUPO=:grupo AND adm_grupos.ID_MEMPRESA=$_CLIENTE AND adm_grupos.ADM_GRUPO<>2 ";
					$s="REPLACE INTO adm_usuarios_empresa (ID_MEMPRESA,ID_USUARIO,ID_GRUPO,LAST)
						VALUES($_CLIENTE,$id,($sGrupo),1)";
					$Repreq = $dbEmpresa->prepare($s);
					$Repreq->bindParam(':grupo', $result["grupo"]);
					$Repreq->execute();	
				}

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
					$nombre=$_FILES[$control_img]["name"];	
					if(($tamano>=$fmin) && ($tamano<=$fmax)){
						if(fValid($tipo,$_files_clase[0])){
							$AwsS3 = Aws::factory(array('key' => $_PARAMETROS['S3_KEY'],'secret' => $_PARAMETROS['S3_SECRET']));
							UploadFiles($AwsS3,$control_img,$dbEmpresa,$UploadDeleteArgs,$Info);			
						}
					}
				}
				elseif($result["imagen"]==3){
					$AwsS3 = Aws::factory(array('key' => $_PARAMETROS['S3_KEY'],'secret' => $_PARAMETROS['S3_SECRET']));
					DeleteFiles($AwsS3,$dbEmpresa,$UploadDeleteArgs);
				} 

				if($result["imagen"]==1||$result["imagen"]==3){
					$s="UPDATE adm_usuarios 
						SET ID_FILE=IFNULL((SELECT adm_files.ID_FILE FROM adm_files WHERE adm_files.F_MODULE=36 AND adm_files.ID_OBJECT=$id_print AND adm_files.F_SUB_EXT='org' AND adm_files.F_TP_FILE='img' LIMIT 1),0)
						WHERE ID_USUARIO=$id_print";
					$dbEmpresa->exec($s);
				}

				$id_sha=encrip($id);	
				$c_sha=36;
				$dbEmpresa->commit();	
				if($nuevo){
					try{
						/*******SEND EMAIL***********/						
						$code_01=sha1($id.$correo).md5($id.$correo).sha1($id);
						$to=array();
						$to[0]["mail"]=$correo;
						$to[0]["name"]=$result["nombre"].' '.$result["apellido"];

						$Asunto=$Email[1][1]['title'];
						$URL_Verif='http://'.$_SERVER["HTTP_HOST"]."/verification/?code=".$code_01;
						$URL_OP='http://'.$_SERVER["HTTP_HOST"];

						$html_cont=sprintf($Email[1][1]['body'],$_sysvars["name"],$correo,$pass02,$URL_OP,$URL_OP,$URL_Verif);
						$rtamail=send_email_srv($_PARAMETROS,$Asunto,$html_cont,$to,array(),array(),true,$Email[1][1]['alt']);
						/*******SEND EMAIL***********/

					}
					catch (Exception $e){			
					}
				}
			}
			catch (Exception $e){
				$dbEmpresa->rollBack();
				$err_str=$e->getMessage();
			}				
		}
	}
	elseif($result["usexist"]==1){
		try{  				
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);			
			$dbEmpresa->beginTransaction();	
			$correo=$result["correo_exist"];
			if(!checkmail($correo)) {$mensaje[0]='txt-MSJ10-0';$error=true;}
			else{		

				$s="SELECT ID_USUARIO,
						NOMBRE_U,
						APELLIDO_U,
						CORREO_U
						FROM adm_usuarios
					WHERE CORREO_U=:correo AND ID_USUARIO<>$_USUARIO";		
				$req = $dbEmpresa->prepare($s);	 
				$req->bindParam(':correo', $correo);	
				$req->execute();
				if($reg = $req->fetch()){ //CORREO ENCONTRADO
					$s="REPLACE INTO adm_usuarios_empresa (ID_MEMPRESA,ID_USUARIO,ID_GRUPO,LAST)
									VALUES($_CLIENTE,:id_usuario,:grupo,0)";
					$Repreq = $dbEmpresa->prepare($s);
					$Repreq->bindParam(':grupo', $result["grupo_exist"]);
					$Repreq->bindParam(':id_usuario', $reg["ID_USUARIO"]);
					$Repreq->execute();

					$id_sha=encrip($reg["ID_USUARIO"]);	
					$c_sha=36;
					$dbEmpresa->commit();

					try{
						/*******SEND EMAIL***********/			
						$to=array();
						$to[0]["mail"]=$reg["CORREO_U"];
						$to[0]["name"]=$reg["NOMBRE_U"].' '.$reg["APELLIDO_U"];
						$URL_OP='http://'.$_SERVER["HTTP_HOST"];
						$Asunto=$Email[1][2]['title'];
						$html_cont=sprintf($Email[1][2]['body'],$reg["NOMBRE_U"],$_PARAMETROS["RAZON_SOCIAL"],$_sysvars["NOMBRE_U"],$_PARAMETROS["RAZON_SOCIAL"],$URL_OP,$URL_OP);
						$rtamail=send_email_srv($_PARAMETROS,$Asunto,$html_cont,$to,array(),array(),true,$Email[1][2]['alt']);
						/*******SEND EMAIL***********/

					}
					catch (Exception $e){			
					}	
				}
				else {$mensaje[0]='txt-MSJ514-0';$error=true;}
			}
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}		

	}
}
//CLASES DE NEGOCIO
elseif($cnf==10003){	

	if(!$nuevo){
		$sWhere=encrip_mysql('adm_empresas_btipo.TIPO_GRUPOPAL');
		$s="SELECT TIPO_GRUPOPAL FROM adm_empresas_btipo WHERE $sWhere=:id LIMIT 1 ";
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		$req->execute();
		if($reg = $req->fetch()) $id=$reg["TIPO_GRUPOPAL"];
		else {$mensaje[0]='txt-MSJ9-0';$error=true;}
		$id_print=$id;
	}
	else $id_print="NULL";	
	
	//////////////LINK//////////////
	$link=cambiar_url($result["nomb"][1],2);			
	
	$link_busc=$link."%";
	$sWhere=encrip_mysql('adm_empresas_btipo.TIPO_GRUPOPAL');	
	$s="SELECT adm_empresas_btipo.LINK_GRUPOPAL  AS LINK_PAGINA
		FROM adm_empresas_btipo		
		WHERE (adm_empresas_btipo.LINK_GRUPOPAL LIKE :link OR adm_empresas_btipo.LINK_GRUPOPAL=:linkcompleto)".(!$nuevo?" AND $sWhere<>:id":""); 
	$ReqB = $dbEmpresa->prepare($s); 
	$ReqB->bindParam(':link',$link_busc, PDO::PARAM_STR);
	$ReqB->bindParam(':linkcompleto',$link);
	if(!$nuevo)	$ReqB->bindParam(':id', $id_sha);
	$ReqB->execute();	
	$links=array();
	while($RegB = $ReqB->fetch()){				
		$caracteres=strlen($link)-strlen($RegB["LINK_PAGINA"]);
		$num_link=mb_substr($RegB["LINK_PAGINA"],$caracteres);
		if(is_numeric($num_link)) $links[]=$num_link;
		elseif($num_link==$RegB["LINK_PAGINA"]) $links[]=0;
	}
	
	if(count($links)>0) $link.=max($links)+1;
	
	if(!$error){
		try{  				
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);			
			$dbEmpresa->beginTransaction();
			
			$s="INSERT INTO adm_empresas_btipo
				(TIPO_GRUPOPAL,FECHA_GRUPOPAL,ID_USUARIO,LINK_GRUPOPAL)
			VALUES($id_print,UTC_TIMESTAMP(),$_USUARIO,:link)
			ON DUPLICATE KEY UPDATE
			LINK_GRUPOPAL=:link";
			$req = $dbEmpresa->prepare($s);
			$req ->bindParam(':link', $link);
			$req ->execute(); 
			if($nuevo) $id=$dbEmpresa->lastInsertId();	
			
			$id_sha=encrip($id);	
			
			$conteov=count($result["idioma"]);						
			if($conteov>0){	
				$s="REPLACE INTO adm_empresas_btipo_desc (TIPO_GRUPOPAL,ID_IDIOMA,NOMB_GRUPOPAL,DESC_GRUPOPAL)
					VALUES (:id,:idioma,:nomb,:desc)";
				$req = $dbEmpresa->prepare($s);	
				$req ->bindParam(':id', $id);
				foreach ($result["idioma"] as $k => $Idioma){		
					$req ->bindParam(':idioma', $result["idioma"][$k]);
					$req ->bindParam(':nomb', $result["nomb"][$k]);	 
					$req ->bindParam(':desc', $result["desc"][$k]);	
					$req ->execute(); 		
				}
			}

			$conteov=count($result["IdVentana"]);						
			if($conteov>0){	
				$ventanas=$result["IdVentana"];
				$ven_imp_id=array();
				foreach ($ventanas as $k => $ven){ 
					$Aplica=isset($result["Ventanas"][$k])?1:0;
					$ven_imp_id[$k]="(:$k,$id,$Aplica)";
				}
				$ven_imp=implode(",",$ven_imp_id);

				$s="REPLACE INTO adm_ventanas_etipo (ID_VENTANA,TIPO_GRUPOPAL,PERMISO)
					VALUES $ven_imp";
				$req = $dbEmpresa->prepare($s);			
				foreach ($ventanas as $k => $ven){
					$req ->bindParam(":$k", $ventanas[$k]);
				}
				$req ->execute();
			}

			if(($nuevo)&&($_PROYECTO==1)){
				$sL=array();
				$sL[]="INSERT INTO adm_empresas_imp_oficios (ID_OFICIO,ID_IDIOMA,TIPO_GRUPOPAL,NOMB_OFICIO,DESC_OFICIO)
						(SELECT ID_OFICIO,ID_IDIOMA,:id,NOMB_OFICIO,DESC_OFICIO FROM adm_empresas_imp_oficios WHERE TIPO_GRUPOPAL=:cnegocio)";				
				
				$sL[]="INSERT INTO adm_empresas_imp_tfalla (ID_FALLA,ID_IDIOMA,TIPO_GRUPOPAL,NOMB_FALLA,COMEN_FALLA)
						(SELECT ID_FALLA,ID_IDIOMA,:id,NOMB_FALLA,COMEN_FALLA FROM adm_empresas_imp_tfalla WHERE TIPO_GRUPOPAL=:cnegocio)";

				$sL[]="INSERT INTO adm_empresas_imp_ttrabajo (ID_TTRABAJO,ID_IDIOMA,TIPO_GRUPOPAL,DESC_TTRABAJO,COMENT_TTRABAJO)
						(SELECT ID_TTRABAJO,ID_IDIOMA,:id,DESC_TTRABAJO,COMENT_TTRABAJO FROM adm_empresas_imp_ttrabajo WHERE TIPO_GRUPOPAL=:cnegocio)";
				
				$sL[]="INSERT INTO fac_estados_eq (ID_ESTADO,ID_IDIOMA,TIPO_GRUPOPAL,ESTADO,OPERATIVO,CALCULO)
						(SELECT ID_ESTADO,ID_IDIOMA,:id,ESTADO,OPERATIVO,CALCULO FROM fac_estados_eq WHERE TIPO_GRUPOPAL=:cnegocio)";

				$sL[]="INSERT INTO fac_estados_ot (ID_ESTADO,ID_IDIOMA,TIPO_GRUPOPAL,ESTADO,ABR_EDO,TIPO,PUNTAJE,CONDICION)
						(SELECT ID_ESTADO,ID_IDIOMA,:id,ESTADO,ABR_EDO,TIPO,PUNTAJE,CONDICION FROM fac_estados_ot WHERE TIPO_GRUPOPAL=:cnegocio)";

				$sL[]="INSERT INTO fac_tmmto_desc (ID_TMMTO,ID_IDIOMA,TIPO_GRUPOPAL,DESC_TMMTO,COMENT_TMMTO,CONDICION,TIPO)
						(SELECT ID_TMMTO,ID_IDIOMA,:id,DESC_TMMTO,COMENT_TMMTO,CONDICION,TIPO FROM fac_tmmto_desc WHERE TIPO_GRUPOPAL=:cnegocio)";
			}	
			elseif(($nuevo)&&($_PROYECTO==8)){
				$sL=array();
				$sL[]="INSERT INTO adm_empresas_imp_tinmueble (ID_TINMUEBLE,ID_IDIOMA,TIPO_GRUPOPAL,TINMUEBLE)
						(SELECT ID_TINMUEBLE,ID_IDIOMA,:id,TINMUEBLE FROM adm_empresas_imp_tinmueble WHERE TIPO_GRUPOPAL=:cnegocio)";				
				
				$sL[]="INSERT INTO adm_empresas_imp_tsegmento (ID_TSEGMENTO,ID_IDIOMA,TIPO_GRUPOPAL,TSEGMENTO)
						(SELECT ID_TSEGMENTO,ID_IDIOMA,:id,TSEGMENTO FROM adm_empresas_imp_tsegmento WHERE TIPO_GRUPOPAL=:cnegocio)";

				$sL[]="INSERT INTO adm_empresas_imp_tvalor (ID_TVALOR,ID_IDIOMA,TIPO_GRUPOPAL,TVALOR,DESC_TVALOR)
						(SELECT ID_TVALOR,ID_IDIOMA,:id,TVALOR,DESC_TVALOR FROM adm_empresas_imp_tvalor WHERE TIPO_GRUPOPAL=:cnegocio)";				

				$sL[]="INSERT INTO fac_enegocio_name (ID_ENEGOCIO,ID_IDIOMA,TIPO_GRUPOPAL,NOMB_ENEGOCIO)
						(SELECT ID_ENEGOCIO,ID_IDIOMA,:id,NOMB_ENEGOCIO FROM fac_enegocio_name WHERE TIPO_GRUPOPAL=:cnegocio)";

				$sL[]="INSERT INTO fac_teventos_name (ID_TEVENTO,ID_IDIOMA,TIPO_GRUPOPAL,NOMB_EVENTO)
						(SELECT ID_TEVENTO,ID_IDIOMA,:id,NOMB_EVENTO FROM fac_teventos_name WHERE TIPO_GRUPOPAL=:cnegocio)";

				$sL[]="INSERT INTO fac_tnegocio_name (ID_TNEGOCIO,ID_IDIOMA,TIPO_GRUPOPAL,NOMB_TNEGOCIO)
						(SELECT ID_TNEGOCIO,ID_IDIOMA,:id,NOMB_TNEGOCIO FROM fac_tnegocio_name WHERE TIPO_GRUPOPAL=:cnegocio)";

				$sL[]="INSERT INTO fac_tref_name (ID_TREF,ID_IDIOMA,TIPO_GRUPOPAL,NOMB_REF)
						(SELECT ID_TREF,ID_IDIOMA,:id,NOMB_REF FROM fac_tref_name WHERE TIPO_GRUPOPAL=:cnegocio)";
			}	


			if($nuevo){
				$sL[]="INSERT INTO adm_empresas_imp_textos (ID_IDIOMA,ID_PALABRA,TIPO_GRUPOPAL,PALABRA,TOOLTIP)
							(SELECT ID_IDIOMA,ID_PALABRA,:id,PALABRA,TOOLTIP FROM adm_empresas_imp_textos WHERE TIPO_GRUPOPAL=:cnegocio)";
				
				$sL[]="INSERT INTO adm_empresas_v_names (ID_VENTANA,ID_IDIOMA,VENTANA_NOMBRE,SCVENTANA,TIPO_GRUPOPAL)
							(SELECT ID_VENTANA,ID_IDIOMA,VENTANA_NOMBRE,SCVENTANA,:id FROM adm_empresas_v_names WHERE TIPO_GRUPOPAL=:cnegocio)";
				
				$sL[]="INSERT INTO adm_empresas_v_grupo_name (ID_GVENTANA,ID_IDIOMA,DESC_GVENTANA,TIPO_GRUPOPAL)
						(SELECT ID_GVENTANA,ID_IDIOMA,DESC_GVENTANA,:id FROM adm_empresas_v_grupo_name WHERE TIPO_GRUPOPAL=:cnegocio)";

				$sL[]="INSERT INTO adm_empresas_v_cont_names (ID_VENTANA,ID_IDIOMA,TITULO_VENTANA,STITULO_VENTANA,TIPO_GRUPOPAL)
						(SELECT ID_VENTANA,ID_IDIOMA,TITULO_VENTANA,STITULO_VENTANA,:id FROM adm_empresas_v_cont_names WHERE TIPO_GRUPOPAL=:cnegocio)";

				$sL[]="INSERT INTO adm_empresas_v_cont_campo_names (ID_CAMPO,ID_IDIOMA,TITULO_CAMPO,TOOLTIP_CAMPO,TIPO_GRUPOPAL)
						(SELECT ID_CAMPO,ID_IDIOMA,TITULO_CAMPO,TOOLTIP_CAMPO,:id FROM adm_empresas_v_cont_campo_names WHERE TIPO_GRUPOPAL=:cnegocio)";

				$sL[]="INSERT INTO adm_empresas_v_informes_desc (ID_INFORME,ID_IDIOMA,TIPO_GRUPOPAL,NOMB_INFORME,DESC_INFORME,REF_INFORME,FECHA_INFORME,REV_INFORME)
							(SELECT ID_INFORME,ID_IDIOMA,:id,NOMB_INFORME,DESC_INFORME,REF_INFORME,FECHA_INFORME,REV_INFORME FROM adm_empresas_v_informes_desc WHERE TIPO_GRUPOPAL=:cnegocio)";
				
				$sL[]="INSERT INTO adm_empresas_v_informes_grupo_desc (ID_GINFORME,ID_IDIOMA,TIPO_GRUPOPAL,NOM_GINFORME,DESC_GINFORME)
							(SELECT ID_GINFORME,ID_IDIOMA,:id,NOM_GINFORME,DESC_GINFORME FROM adm_empresas_v_informes_grupo_desc WHERE TIPO_GRUPOPAL=:cnegocio)";
				foreach($sL as $k => $s){
					$req = $dbEmpresa->prepare($sL[$k]);
					$req ->bindParam(':cnegocio', $result["cnegocio"]);
					$req ->bindParam(':id', $id);
					$req ->execute(); 
				}
			}
			$dbEmpresa->commit();
			Grupos($dbEmpresa,true);			//CARGA DATOS DE GRUPO
			
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
				$s="UPDATE adm_empresas_btipo 
					SET ID_FILE=IFNULL((SELECT adm_files.ID_FILE FROM adm_files WHERE adm_files.F_MODULE=$cnf AND adm_files.ID_OBJECT=$id AND adm_files.F_SUB_EXT='org' AND adm_files.F_TP_FILE='img' LIMIT 1),0)
					WHERE TIPO_GRUPOPAL=$id";
				$dbEmpresa->exec($s);
			}
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}					
	}
}
//TIPO DE EMPRESA
elseif($cnf==10002){
	if(!$nuevo){
		$sWhere=encrip_mysql('adm_empresas_tipo.ID_TIPOE');
		$s="SELECT ID_TIPOE FROM adm_empresas_tipo WHERE $sWhere=:id LIMIT 1 ";
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		$req->execute();	
		if($reg = $req->fetch()) $id=$reg["ID_TIPOE"];
		else {$mensaje[0]='txt-MSJ9-0';$error=true;}
		$id_print=$id;
	}
	else $id_print="NULL";	
	
	//////////////LINK//////////////
	$link=cambiar_url($result["nomb"][1],2);			
	
	$link_busc=$link."%";
	$sWhere=encrip_mysql('adm_empresas_tipo.ID_TIPOE');	
	$s="SELECT adm_empresas_tipo.LINK_TIPOE  AS LINK_PAGINA
		FROM adm_empresas_tipo		
		WHERE (adm_empresas_tipo.LINK_TIPOE LIKE :link OR adm_empresas_tipo.LINK_TIPOE=:linkcompleto)".(!$nuevo?" AND $sWhere<>:id":""); 
	$ReqB = $dbEmpresa->prepare($s); 
	$ReqB->bindParam(':link',$link_busc, PDO::PARAM_STR);
	$ReqB->bindParam(':linkcompleto',$link);
	if(!$nuevo)	$ReqB->bindParam(':id', $id_sha);
	$ReqB->execute();	
	$links=array();
	while($RegB = $ReqB->fetch()){				
		$caracteres=strlen($link)-strlen($RegB["LINK_PAGINA"]);
		$num_link=mb_substr($RegB["LINK_PAGINA"],$caracteres);
		if(is_numeric($num_link)) $links[]=$num_link;
		elseif($num_link==$RegB["LINK_PAGINA"]) $links[]=0;
	}
	
	if(count($links)>0) $link.=max($links)+1;
	
	if(!$error){
		try{  				
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);			
			$dbEmpresa->beginTransaction();
			
			$s="INSERT INTO adm_empresas_tipo
				(ID_TIPOE,TIPO_GRUPOPAL,FECHA_TIPOE,ID_USUARIO,LINK_TIPOE)
			VALUES($id_print,:cnegocio,UTC_TIMESTAMP(),$_USUARIO,:link)
			ON DUPLICATE KEY UPDATE
			LINK_TIPOE=:link,
			TIPO_GRUPOPAL=:cnegocio";
			$req = $dbEmpresa->prepare($s);
			$req ->bindParam(':link', $link);
			$req ->bindParam(':cnegocio', $result["cnegocio"]);		
			$req ->execute(); 
			if($nuevo) $id=$dbEmpresa->lastInsertId();	
			
			$id_sha=encrip($id);	
			
			$conteov=count($result["idioma"]);						
			if($conteov>0){	
				$s="REPLACE INTO adm_empresas_tipo_desc (ID_TIPOE,ID_IDIOMA,NOMB_TIPOE,DESC_TIPOE)
					VALUES (:id,:idioma,:nomb,:desc)";
				$req = $dbEmpresa->prepare($s);	
				$req ->bindParam(':id', $id);
				foreach ($result["idioma"] as $k => $Idioma){		
					$req ->bindParam(':idioma', $result["idioma"][$k]);
					$req ->bindParam(':nomb', $result["nomb"][$k]);	 
					$req ->bindParam(':desc', $result["desc"][$k]);	
					$req ->execute(); 		
				}
			}			
			$dbEmpresa->commit();

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
				$s="UPDATE adm_empresas_tipo 
					SET ID_FILE=IFNULL((SELECT adm_files.ID_FILE FROM adm_files WHERE adm_files.F_MODULE=$cnf AND adm_files.ID_OBJECT=$id AND adm_files.F_SUB_EXT='org' AND adm_files.F_TP_FILE='img' LIMIT 1),0)
					WHERE ID_TIPOE=$id";
				$dbEmpresa->exec($s);
			}		  
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}					
	}
}
//EMPRESAS
elseif($cnf==10004){
	if(!$nuevo){
		$sWhere=encrip_mysql('adm_empresas.ID_MEMPRESA');
		$s="SELECT ID_MEMPRESA FROM adm_empresas WHERE $sWhere=:id LIMIT 1 ";
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		$req->execute();
		if($reg = $req->fetch()) $id=$reg["ID_MEMPRESA"];
		else {$mensaje[0]='txt-MSJ9-0';$error=true;}
		$id_print=$id;
	}
	else {$mensaje[0]='txt-MSJ9-0';$error=true;}
	
	
	if(!$error){
		try{  				
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);			
			$dbEmpresa->beginTransaction();
			
			$s="UPDATE adm_empresas
				SET	ID_TIPOE=:tempresa,
				NOMB_MEMPRESA=:nombre
				WHERE ID_MEMPRESA=:id";
			$req = $dbEmpresa->prepare($s);
			$req ->bindParam(':id', $id);
			$req ->bindParam(':tempresa', $result["tempresa"]);		
			$req ->bindParam(':nombre', $result["nombre"]);		
			$req ->execute(); 
			if($nuevo) $id=$dbEmpresa->lastInsertId();	
			
			$id_sha=encrip($id);	
			
			$conteov=count($result["idioma"]);						
			if($conteov>0){	
				$s="REPLACE INTO adm_empresas_desc (ID_MEMPRESA,ID_IDIOMA,LEMA_EMPRESA,DESC_EMPRESA)
					VALUES (:id,:idioma,:lema,:desc)";
				$req = $dbEmpresa->prepare($s);	
				$req ->bindParam(':id', $id);
				foreach ($result["idioma"] as $k => $Idioma){		
					$req ->bindParam(':idioma', $result["idioma"][$k]);
					$req ->bindParam(':lema', $result["lema"][$k]);	 
					$req ->bindParam(':desc', $result["desc"][$k]);	
					$req ->execute(); 		
				}
			}	



			if($PermisosA[10000]["P"]==1){
				$grupos=$result['IdGroup'];					
				foreach ($grupos as $key => $IdGroup) {
					$IdUsuario=$key;
					$GrLast=isset($result['GrLast'][$key])?1:0;
					if($IdGroup==0){
						$s='DELETE FROM adm_usuarios_empresa
							WHERE ID_USUARIO=:IdUsuario AND ID_MEMPRESA=:id LIMIT 1';
						$req = $dbEmpresa->prepare($s);
						$req->bindValue(':IdUsuario', $IdUsuario);
						$req->bindValue(':id', $id);
						$req->execute();	
					}
					else{
						$s='INSERT INTO adm_usuarios_empresa
							(ID_USUARIO
							,ID_MEMPRESA
							,ID_GRUPO
							,LAST)
						VALUES(
							:IdUsuario
							,:id
							,:IdGroup
							,:GrLast)
						ON DUPLICATE KEY UPDATE
							LAST=:GrLast
						,	ID_GRUPO=:IdGroup';
						$req = $dbEmpresa->prepare($s);
						$req->bindValue(':IdUsuario', $IdUsuario);
						$req->bindValue(':id', $id);
						$req->bindValue(':IdGroup', $IdGroup);
						$req->bindValue(':GrLast', $GrLast);
						$req->execute();	
					}
				}				
			}



			$dbEmpresa->commit();

			$UploadDeleteArgs=array(
						'S3_BUCKET'=>$_PARAMETROS["S3_BUCKET"]
					,	'PROYECTO'=>$_PROYECTO
					,	'EMPRESA'=>$_EMPRESA
					,	'MODULE'=>0
					,	'OBJECT'=>$id
					,	'TP_FILE'=>'LogoClient');

			if(($result["imagen"]==1)){			
				$tamano=$_FILES[$control_img]["size"];
				$ubicacion=$_FILES[$control_img]["tmp_name"];
				$finfo = finfo_open(FILEINFO_MIME_TYPE); 	
				$tipo=finfo_file($finfo, $ubicacion);	
				finfo_close($finfo);	
				$nombre=$_FILES[$control_img]["name"];
				if(($tamano<=$fmin) || ($tamano>$fmax))	{$mensaje[0]='txt-MSJ2-0';$error=true;}
				if(tipo_archivo($tipo)!=1)	{$mensaje[0]='txt-MSJ3-0';$error=true;}		
				if(!$error){			
					$AwsS3 = Aws::factory(array('key' => $_PARAMETROS['S3_KEY'],'secret' => $_PARAMETROS['S3_SECRET']));
					UploadFiles($AwsS3,$control_img,$dbEmpresa,$UploadDeleteArgs);		
				}
			}
			elseif(($result["imagen"]==3)){
				$AwsS3 = Aws::factory(array('key' => $_PARAMETROS['S3_KEY'],'secret' => $_PARAMETROS['S3_SECRET']));
				DeleteFiles($AwsS3,$dbEmpresa,$UploadDeleteArgs);
			}
			if($result["imagen"]==1||$result["imagen"]==3){
				$s="UPDATE adm_empresas 
					SET ID_FILE=IFNULL((SELECT adm_files.ID_FILE FROM adm_files WHERE adm_files.F_MODULE=0 AND adm_files.ID_OBJECT=$id AND adm_files.F_SUB_EXT='org' AND adm_files.F_TP_FILE='LogoClient' LIMIT 1),0)
					WHERE ID_MEMPRESA=$id";
				$dbEmpresa->exec($s);
			}	
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}					
	}
}
else{
	if($_PROYECTO==1)			include("base_acc_001.php"); //ROCKETMP
	elseif($_PROYECTO==8)		include("base_acc_008.php"); //FALCONCRM
	elseif($_PROYECTO==10)		include("base_acc_010.php"); //TUPYME
	elseif($_PROYECTO==13)		include("base_acc_013.php"); //CIUDAD TRAVEL
	elseif($_PROYECTO==14)		include("base_acc_014.php"); //EVENTOS CCB
	elseif($_PROYECTO==16)		include("base_acc_016.php"); //Disponibles
	elseif($_PROYECTO==18)		include("base_acc_018.php"); //Mensajero
	elseif($_PROYECTO==19)		include("base_acc_019.php"); //SCA
	elseif($_PROYECTO==20)		include("base_acc_020.php"); //Appetitos
	elseif($_PROYECTO==21)		include("base_acc_021.php"); //Innova
	elseif($_PROYECTO==22)		include("base_acc_022.php"); //New Rocket
	elseif($_PROYECTO==23)		include("base_acc_023.php"); //VIGA
	elseif($_PROYECTO==24)		include("base_acc_024.php"); //Marca GPS
	elseif($_PROYECTO==25)		include("base_acc_025.php"); //Esteban Rios
	elseif($_PROYECTO==26)		include("base_acc_026.php"); //Mis Veterinarias
	elseif($_PROYECTO==27)		include("base_acc_027.php"); //Cancheros
	elseif($_PROYECTO==28)		include("base_acc_028.php"); //Petrozones
	elseif($_PROYECTO==29)		include("base_acc_029.php"); //Asking Room
	elseif($_PROYECTO==31)		include("base_acc_031.php"); //Checkin
	elseif($_PROYECTO==32)		include("base_acc_032.php"); //Super Maestros
	elseif($_PROYECTO==36)		include("base_acc_036.php"); //Gappsolina 
	elseif($_PROYECTO==37)		include("base_acc_037.php"); //Eureka Movil 
	elseif($_PROYECTO==38)		include("base_acc_038.php"); //Aservisto 
	elseif($_PROYECTO==39)		include("base_acc_039.php"); //Cajasan 
	elseif($_PROYECTO==40)		include("base_acc_040.php"); //Alkilautos
	elseif($_PROYECTO==41)		include("base_acc_041.php"); //7Points
	elseif($_PROYECTO==42)		include("base_acc_042.php"); //INFOEVENTOS
	elseif($_PROYECTO==43)		include("base_acc_043.php"); //TeloEntrego
	elseif($_PROYECTO==44)		include("base_acc_044.php"); //Tienditapp
	elseif($_PROYECTO==45)		include("base_acc_045.php"); //LicorTap

}
?>
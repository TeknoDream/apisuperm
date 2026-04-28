<?php
//DATOS DE TODAS LAS EMPRESAS
require 		"../phplib/s3/aws.phar";
use 			Aws\Common\Aws;	
$ArrayImg=array(
            'PROYECTO'=>$_PROYECTO
        ,   'EMPRESA'  =>$_EMPRESA
        ,   'MODULO'    =>0
        ,   'OBJETO'    =>0
        ,   'TP'        =>'img'
        ,   'EXT'       =>$reg["F_EXT"]
        ,   'All'       =>true);


/**********************************/
/**********************************/
/************LANDING**************/
/**********************************/
/**********************************/
if($tp==10001){
	$txt=isset($_REQUEST["txt"])?$_REQUEST["txt"]:0;
	$_APISEND=isset($_REQUEST["tosend"])?$_REQUEST["tosend"]:0;

	if($_sysvars["_host"]!=''){
		$s="SELECT ID_MEMPRESA FROM adm_empresas_url WHERE URL=:_host LIMIT 1";
		$reqEmpresa=$dbEmpresa->prepare($s);
		$reqEmpresa->bindParam(':_host', $_sysvars["_host"]);
		$reqEmpresa->execute();		
		if($regEmpresa = $reqEmpresa->fetch())	$id_mempresa=$regEmpresa["ID_MEMPRESA"];
		else 									$id_mempresa=0;
	}
	elseif($_CLIENTE!='' && $_CLIENTE!=0) 	$id_mempresa=$_CLIENTE;
	else									$id_mempresa=0;



	/////////DATA DE CONFIGURACION/////////
	if($id_mempresa!=0){
		if($_APISEND==0)
			$s=$sqlCons[2][9]." WHERE adm_configuracion.APISEND>=1 ";
		else
			$s=$sqlCons[2][9]." WHERE adm_configuracion.APISEND=:_APISEND ";
		$reqConfig = $dbEmpresa->prepare($s);
		$reqConfig->bindParam(':idioma', $Idioma);
		$reqConfig->bindParam(':empresa', $id_mempresa);
		if($_APISEND!=0) $reqConfig->bindParam(':_APISEND', $_APISEND);
		$reqConfig->execute();
		while($regConfig = $reqConfig->fetch()){
			$config[$regConfig["CONFIG_NOMBRE"]]=$regConfig["CONFIG_VALOR"];
		}
	}	
	if($_APISEND==0)
		$s=$sqlCons[3][9]." WHERE adm_configuracion_gral.APISEND>=1 ";
	else
		$s=$sqlCons[3][9]." WHERE adm_configuracion_gral.APISEND=:_APISEND ";
	$reqConfig = $dbEmpresa->prepare($s);
	$reqConfig->bindParam(':idioma', $Idioma);	
	if($_APISEND!=0) $reqConfig->bindParam(':_APISEND', $_APISEND);
	$reqConfig->execute(); 
	while($regConfig = $reqConfig->fetch()){
		if(!isset($config[$regConfig["CONFIG_NOMBRE"]])|| $config[$regConfig["CONFIG_NOMBRE"]]=="")
			$config[$regConfig["CONFIG_NOMBRE"]]=$regConfig["CONFIG_VALOR"];
	}		
	/////////////////////////////////////
	if($txt==1){
		//TEXTOS
		$s=$sqlCons[1][68]." WHERE ID_IDIOMA=:idioma";	
		$reqG = $dbMat->prepare($s);	 
		$reqG->bindParam(':idioma', $Idioma);
		$reqG->execute();
		$Textos=array();
		while($regG = $reqG->fetch()){
			$Textos[$regG["ID_PALABRA"]][0]=$regG["PALABRA"];
			$Textos[$regG["ID_PALABRA"]][1]=$regG["TOOLTIP"];
		}
		$s=$sqlCons[2][77];		
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':idioma', $Idioma);
		$req->bindParam(':grupo', $_GCLIENTE);
		$req->bindParam(':empresa', $id_mempresa);
		$req->execute();
		while($reg = $req->fetch()){
			$Textos[$reg["ID_PALABRA"]][0]=$reg["PALABRA"];
			$Textos[$reg["ID_PALABRA"]][1]=$reg["TOOLTIP"];
		}
	}

	$config["FAVICON"]=ImgName($_PROYECTO,$_EMPRESA,0,$id_mempresa,'favico','ico',false,'big');  
	$config["LOGO"]=ImgName($_PROYECTO,$_EMPRESA,0,$id_mempresa,'LogoClient','png',false,'big');   
	$config["LOGO_APP"]=ImgName($_PROYECTO,$_EMPRESA,0,0,'LogoApp','png',false,'big');
	$config["LOGO_STORE"]=ImgName($_PROYECTO,$_EMPRESA,0,0,'LogoStorep','png',false,'big');    
	$salidas = array('config' => $config,'text' => $Textos);
}
/**********************************/
/**********************************/
/************CONTACTENOS***********/
/**********************************/
/**********************************/
elseif($tp==10002){
	$email=isset($_REQUEST["email"])?$_REQUEST["email"]:'';
	$name=isset($_REQUEST["name"])?$_REQUEST["name"]:'';
	$company=isset($_REQUEST["company"])?$_REQUEST["company"]:'';
	$comment= isset($_REQUEST["comment"])?$_REQUEST["comment"]:'';
	$tel= isset($_REQUEST["tel"])?$_REQUEST["tel"]:'';
	//
	if($email=='')
		$error=1;
	//
	if(!checkmail($email)) $error=10;

	if($error==0){
		//INFO EMPRESA
		try{ 
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();

			if($error==0){
				if($tel=='')
					$Mensaje=$comment;
				else
					$Mensaje=$comment."\n Teléfono: $tel";
				$s="INSERT INTO adm_contactenos (
						NOMBRE
					,	CORREO
					,	FECHA
					,	ASUNTO
					,	MENSAJE)
				VALUES(
					:nombre
					,:email
					,UTC_TIMESTAMP()
					,:company
					,:mensaje)";
				$req = $dbEmpresa->prepare($s); 
				$req->bindParam(':nombre', $name);
				$req->bindParam(':email', $email);
				$req->bindParam(':company', $company);
				$req->bindParam(':mensaje', $Mensaje);
				$req->execute();	
				$idREC=$dbEmpresa->lastInsertId();	
				$dbEmpresa->commit();
							
				try{
					//EMAIL 1
					$mensaje_asunto=sprintf(
						"<div>".
							"<p>Correo: %s</p>".
							"<p>Asunto: %s</p>".
							"<p>Mensaje: %s</p>".
						"</div>",$email,$company,nl2br($comment));

					/*******SEND EMAIL***********/
					$_PARAMETROS=ParametrosAPI($dbEmpresa,$_sysvars_r,$_sysvars);
					CuerpoMail($Email,$_PROYECTO,$_EMPRESA,$_CLIENTE,$_IDIOMA,$_TZ,$_GCLIENTE,$_USUARIO,$_GRUPO,$_PARAMETROS);


					$to=array();
					$to[0]["mail"]=$_PARAMETROS["M_TOMAIL"];
					$to[0]["name"]=$_PARAMETROS["M_TONAME"];

					$Asunto=sprintf($Email[1][6]['title'],$idREC);
					$html_cont=sprintf($Email[1][6]['body'],$name,$mensaje_asunto);
					$rtamail=send_email_srv($_PARAMETROS,$Asunto,$html_cont,$to,array(),array(),true,$Email[1][6]['alt']);
					$salidas["rta_mail_01"]=$rtamail;
					/*******SEND EMAIL***********/

					//EMAIL 2
					/*******SEND EMAIL***********/													
					$to=array();
					$to[0]["mail"]=$email;
					$to[0]["name"]=$company;

					$Asunto=$Email[1][7]['title'];
					$html_cont=sprintf($Email[1][7]['body'],$idREC);
					$rtamail=send_email_srv($_PARAMETROS,$Asunto,$html_cont,$to,array(),array(),true,$Email[1][7]['alt']);
					$salidas["rta_mail_02"]=$rtamail;
					/*******SEND EMAIL***********/
					$salidas["transaction"]='OK';
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
/**********************************/
/**********************************/
/**************TXTOS LITE**********/
/**********************************/
/**********************************/
elseif($tp==10005){

	if($_sysvars["_host"]!=''){
		$s="SELECT adm_empresas_url.ID_MEMPRESA,adm_empresas_tipo.TIPO_GRUPOPAL
		FROM adm_empresas_url
		LEFT JOIN adm_empresas ON adm_empresas.ID_MEMPRESA=adm_empresas_url.ID_MEMPRESA
		LEFT JOIN adm_empresas_tipo ON adm_empresas_tipo.ID_TIPOE=adm_empresas.ID_TIPOE
		WHERE adm_empresas_url.URL=:_host LIMIT 1";
		$reqEmpresa=$dbEmpresa->prepare($s);
		$reqEmpresa->bindParam(':_host', $_sysvars["_host"]);
		$reqEmpresa->execute();		
		if($regEmpresa = $reqEmpresa->fetch()){	
			$id_mempresa=$regEmpresa["ID_MEMPRESA"];
			$id_gempresa=$regEmpresa["TIPO_GRUPOPAL"];
		}
		else 	$id_mempresa=0;
	}
	elseif($_CLIENTE!='' && $_CLIENTE!=0){
		$id_mempresa=$_CLIENTE;
		$id_gempresa=$_GCLIENTE;
	}
	else 	$id_mempresa=0;

	$movil=$_REQUEST['movil']!='';
	$_TEXTOS=!$movil;
	$version_old=JSON_PARSE($_REQUEST["versions"]);	
	$version_new=array();
	$nueva=$_REQUEST["nueva"]==1;
	$logged=$verificar?1:0;
	$logemp=$id_mempresa==0?0:1;
	$send=array();
	//LANDING PAGES
	$s=$sqlCons[1][99].
			" WHERE adm_api_tablas.HAB=0 ".
			" AND adm_api_tablas.MEMPRESA IN (2,:logemp)".
			" AND adm_api_tablas.TEMPRESA IN (2,:logemp)".
			" AND adm_api_tablas.LOGGED IN (2,:logged)";	
	$req = $dbEmpresa->prepare($s);
	$req->bindParam(':id_mempresa', $id_mempresa);	
	$req->bindParam(':logged', $logged);
	$req->bindParam(':logemp', $logemp);
	$req->execute();
	$regSal=array();

	while($reg = $req->fetch()){

		$name=$reg["NAME_TABLA"];
		$version_new[$name]=$reg['VERSION'];
		$version_old[$name]=is_numeric($version_old[$name])?$version_old[$name]:-1;
		$c_mode=$reg["MODO"];

		if($c_mode==2)
			$regSal[]=$reg;
		else{
			if($version_new[$name]>$version_old[$name]){			
				if(!isset($send[$name])) 	$send[$name]=array();				
				if($name=='x_textos'){		
					if($_TEXTOS){	
						//TEXTOS GENERICOS DE PROYECTO			
						$s=$sqlCons[2][77];					
						$reqPtabla = $dbEmpresa->prepare($s);
						$reqPtabla->bindParam(':empresa', $id_mempresa);
						$reqPtabla->bindParam(':grupo', $id_gempresa);						
						$reqPtabla->bindParam(':idioma', $Idioma);
						$reqPtabla->execute();					
						while($regPtabla = $reqPtabla->fetch()){
							$kk=$regPtabla["ID_PALABRA"];
							if(!is_null($regPtabla["PALABRA"])) $send[$name]["txt-$kk-0"]=$regPtabla["PALABRA"];
							if(!is_null($regPtabla["TOOLTIP"])) $send[$name]["txt-$kk-1"]=$regPtabla["TOOLTIP"];
						}			

						//TEXTOS SIIE
						include "../phplib/mysql_valores.php";	
						$s=$sqlCons[4][77];
						$reqPtabla = $dbMat->prepare($s);					
						$reqPtabla->bindParam(':idioma', $Idioma);
						$reqPtabla->execute();	
						while($regPtabla = $reqPtabla->fetch()){
							$kk=$regPtabla["ID_PALABRA"];
							if(!is_null($regPtabla["PALABRA"])) $send[$name]["txt-$kk-0"]=$regPtabla["PALABRA"];
							if(!is_null($regPtabla["TOOLTIP"])) $send[$name]["txt-$kk-1"]=$regPtabla["TOOLTIP"];
						}
						
						//DE LA EMPRESA CLIENTE
						$s=$sqlCons[3][77];
						$reqPtabla = $dbEmpresa->prepare($s);
						$reqPtabla->bindParam(':empresa', $id_mempresa);						
						$reqPtabla->bindParam(':idioma', $Idioma);
						$reqPtabla->execute();	
						while($regPtabla = $reqPtabla->fetch()){
							$kk=$regPtabla["ID_PALABRA"];
							if(!is_null($regPtabla["PALABRA"])) $send[$name]["txt-$kk-0"]=$regPtabla["PALABRA"];
							if(!is_null($regPtabla["TOOLTIP"])) $send[$name]["txt-$kk-1"]=$regPtabla["TOOLTIP"];
						}			

						//LANDING PAGES
						$s=$sqlCons[1][75]." WHERE adm_landing.ID_IDIOMA=:idioma";
						$reqLanding = $dbEmpresa->prepare($s);
						$reqLanding->bindParam(':idioma', $Idioma);
						$reqLanding->execute();
						$landing=array();
						while($regLanding = $reqLanding->fetch()){
							$kk=$regLanding ["ETI_DIV"].$regLanding ["ID_LAND"];
							$send[$name]["txt-$kk-0"]=$regLanding ["TITULO"];
							$send[$name]["txt-$kk-1"]=$regLanding ["TEXTO"];
						}
						//LANDING PAGES SOLO COMPAÑIAS
						$s=$sqlCons[2][75]." WHERE adm_empresas_landing.ID_IDIOMA=:idioma AND adm_empresas_landing.ID_MEMPRESA=:empresa ";
						$reqLanding = $dbEmpresa->prepare($s);
						$reqLanding->bindParam(':idioma', $Idioma);
						$reqLanding->bindParam(':empresa', $id_mempresa);
						$reqLanding->execute();
						$landing=array();
						while($regLanding = $reqLanding->fetch()){
							$kk=$regLanding ["ETI_DIV"].$regLanding ["ID_LAND"];
							$send[$name]["txt-$kk-0"]=$regLanding ["TITULO"];
							$send[$name]["txt-$kk-1"]=$regLanding ["TEXTO"];
						}

						//MENSAJES	
						$s="SELECT
						fac_mensajes.ID_MENSAJE,
						fac_mensajes.ID_IDIOMA,
						fac_mensajes.MENSAJE,
						fac_mensajes.DIV_MENSAJE,
						fac_mensajes.DIV_ICONO
						FROM fac_mensajes
						WHERE ID_IDIOMA=:idioma";	
						$reqMsg = $dbMat->prepare($s);	 
						$reqMsg->bindParam(':idioma', $Idioma);		
						$reqMsg->execute();
						while($regMsg = $reqMsg->fetch()){
							$kk='MSJ'.$regMsg ["ID_MENSAJE"];	
							$send[$name]["txt-$kk-0"]=$regMsg ["MENSAJE"];
						}	
					}
				}	
				else{
					$FFlag=true;

					
					$_x1=$reg["CONSULTA_X1"];
					$_x2=$reg["CONSULTA_X2"];
					$c_empresa=$reg["MEMPRESA"];
					$c_tempresa=$reg["TEMPRESA"];
					$c_idioma=$reg["IDIOMA"];	
					$c_user=$reg["USUARIO"];
					$c_module=$reg["VENTANA"];
					
					if($c_module!=0)
						$FFlag=GruposAPI($dbEmpresa,array($c_module),$_sysvars_r,$_sysvars);
					
					if($FFlag){
						$s=$sqlCons[$_x1][$_x2].' '.$sqlOrder[$_x1][$_x2];
						$reqPtabla = $dbEmpresa->prepare($s);
						if($c_empresa==1)	$reqPtabla->bindParam(':empresa', $id_mempresa);
						if($c_tempresa==1)	$reqPtabla->bindParam(':grupo', $id_gempresa);
						if($c_idioma==1)	$reqPtabla->bindParam(':idioma', $Idioma);
						if($c_user==1)		$reqPtabla->bindParam(':usuario', $_USUARIO);
						if($c_mode==1)		$reqPtabla->bindParam(':user_group', $_GRUPO);
						$reqPtabla->execute();

						$kk=0;
						while($regPtabla = $reqPtabla->fetch()){					
							foreach($regPtabla as $name_data => $valor_data){
								if(!is_numeric($name_data)) $send[$name][$kk][$name_data]=$valor_data;
							}
							$kk++;
						}
					}		
				}	
			}	
		}
	}
	$salidas = array(
			'tables' => $send
		,	'versions_new' => $version_new
		,	'group' => $_GRUPO
	);
	
}
elseif($tp==10013){ //Verificar Correo
	//1 Campo vacio
	//10 Email no valido
	//20 Email en Uso
	$email=isset($_REQUEST["email"])?$_REQUEST["email"]:'';
	if($email=='')		$error=1;

	if($error==0){
		if(!checkmail($email)) 
			$error=10;
		else{		
			$s="SELECT ID_USUARIO
					FROM adm_usuarios
				WHERE CORREO_U=:email";		
			$req = $dbEmpresa->prepare($s);	 
			$req->bindParam(':email', $email);	
			$req->execute();
			if($reg = $req->fetch())  
				$error=20;
		}
	}
	if($error==0) $salidas["transaction"]="OK";
}
/**********************************/
/**********************************/
/***********CREAR CUENTA **********/
/**********************************/
/**********************************/
elseif($tp==10100){ //CREAR CUENTA
	$email=isset($_REQUEST["email"])?$_REQUEST["email"]:'';
	$name=isset($_REQUEST["name"])?$_REQUEST["name"]:'';
	$lastname=isset($_REQUEST["lastname"])?$_REQUEST["lastname"]:'';
	$password= isset($_REQUEST["password"])?$_REQUEST["password"]:'';
	$_id_fb= isset($_REQUEST["_id_fb"])?$_REQUEST["_id_fb"]:'';
	$_token_fb= isset($_REQUEST["_token_fb"])?$_REQUEST["_token_fb"]:'';
	$moneda=isset($_REQUEST["moneda"])?$_REQUEST["moneda"]:'COP';

	//
	if($email=='')
		$error=1;
	//
	if(strlen($password)<6)		$error=9;
	if($error==0){
		if(!checkmail($email)) $error=10;
		else{		
			$s="SELECT ID_USUARIO
					FROM adm_usuarios
				WHERE CORREO_U=:email";		
			$req = $dbEmpresa->prepare($s);	 
			$req->bindParam(':email', $email);	
			$req->execute();
			if($reg = $req->fetch())  $error=20;
		}
	}
	if($error==0){
		//INFO EMPRESA
		try{ 
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();

			//////////////LINK//////////////
			$link=cambiar_url($_REQUEST["name"],2);		
			$link_busc=$link."%";		
			$s="SELECT adm_usuarios.ALIAS  AS LINK_PAGINA
				FROM adm_usuarios 
				WHERE (adm_usuarios.ALIAS LIKE :link OR adm_usuarios.ALIAS=:linkcompleto)"; 
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

			//USUARIO!		
			$s="INSERT INTO adm_usuarios
				(ALIAS
				,NOMBRE_U
				,APELLIDO_U
				,CORREO_U
				,PASSWORD_U
				,FECHA_U
				,ID_IDIOMA)
			VALUES(
				:links
				,:nombre
				,:apellido
				,:correo
				,:password
				,UTC_TIMESTAMP()
				,:idioma)";
			$Repreq = $dbEmpresa->prepare($s);
			$Repreq->bindParam(':links', $link); 
			$Repreq->bindParam(':nombre', $name);
			$Repreq->bindParam(':apellido', $lastname);
			$Repreq->bindParam(':correo', $email);
			$Repreq->bindParam(':password', sha1($password));
			$Repreq->bindParam(':idioma', $Idioma);
			$Repreq->execute();				
			$_USUARIO=$dbEmpresa->lastInsertId();
			$verificar=true;


			$salidas["upload"]='NO';
			// SOLO SE EDITA LA IMAGEN SI SE ENVIA setImage en 1
			$_PARAMETROS=ParametrosAPI($dbEmpresa,$_sysvars_r,$_sysvars);
			$control_img='imagen';
			if($_REQUEST["setImage"]==1&&isset($_FILES[$control_img])){				
				$cnf=36;
				$IdItem=$_USUARIO;
				require 		"../phplib/s3/aws.phar";
				$UploadDeleteArgs=array(
							'S3_BUCKET'=>$_PARAMETROS["S3_BUCKET"]
						,	'PROYECTO'=>$_PROYECTO
						,	'EMPRESA'=>$_EMPRESA
						,	'MODULE'=>$cnf
						,	'OBJECT'=>$IdItem
						,	'TP_FILE'=>'img');

				$tamano=$_FILES[$control_img]["size"];
				$ubicacion=$_FILES[$control_img]["tmp_name"];
				$finfo = finfo_open(FILEINFO_MIME_TYPE); 	
				$tipo=finfo_file($finfo, $ubicacion);	
				finfo_close($finfo);
				$nombre=$_FILES[$control_img]["name"];	
				if($tamano<=$fmax){
					if(fValid($tipo,$_files_clase[0])){
						$AwsS3 = Aws::factory(array('key' => $_PARAMETROS['S3_KEY'],'secret' => $_PARAMETROS['S3_SECRET']));
						UploadFiles($AwsS3,$control_img,$dbEmpresa,$UploadDeleteArgs,$Info);
						$s="UPDATE adm_usuarios 
							SET ID_FILE=IFNULL((SELECT adm_files.ID_FILE FROM adm_files WHERE adm_files.F_MODULE=$cnf AND adm_files.ID_OBJECT=$IdItem AND adm_files.F_SUB_EXT='org' AND adm_files.F_TP_FILE='img' LIMIT 1),0)
							WHERE ID_USUARIO=$IdItem";
						$dbEmpresa->exec($s);
						$salidas["upload"]='OK';
					}
					else $error=200021;
				}
				else $error=200022;
			}		


			if($_PARAMETROS["ID_GRUPO"]!='' && $_PARAMETROS["ID_GRUPO"]!=0){
				$s="INSERT INTO adm_usuarios_empresa (ID_MEMPRESA,ID_USUARIO,ID_GRUPO,LAST)
					VALUES($_CLIENTE,$_USUARIO,:grupo,1)";
				$Repreq = $dbEmpresa->prepare($s);
				$Repreq->bindParam(':grupo', $_PARAMETROS["ID_GRUPO"]);
				$Repreq->execute();
			}
						
			//USUARIO VERIFICADO
			$_sysvars["_ualias"]=$email;
			$_sysvars["_upassw"]=sha1($password);
			$_sysvars_r=verif_sp($_sysvars,$dbEmpresa);

			if($_REQUEST["ios"]==1)
				$salidas["_user"][]=$_sysvars;
			else
				$salidas["_user"]=$_sysvars;	

			$dbEmpresa->commit();
			try{
				/*******SEND EMAIL***********/						
				CuerpoMail($Email,$_PROYECTO,$_EMPRESA,$_CLIENTE,$_IDIOMA,$_TZ,$_GCLIENTE,$_USUARIO,$_GRUPO,$_PARAMETROS);											
				$code_01=sha1($_USUARIO.$email).md5($_USUARIO.$email).sha1($_USUARIO);
				$to=array();
				$to[0]["mail"]=$email;
				$to[0]["name"]=$name.' '.$lastname;

				$Asunto=$Email[1][0]['title'];
				$URL_Verif='http://'.$_SERVER["HTTP_HOST"]."/verification/?code=".$code_01;
				$html_cont=sprintf($Email[1][0]['body'],$email,$password,$URL_Verif);
				$rtamail=send_email_srv($_PARAMETROS,$Asunto,$html_cont,$to,array(),array(),true,$Email[1][0]['alt']);
				$salidas["rta_mail"]=$rtamail;
				/*******SEND EMAIL***********/
				$salidas["transaction"]='OK';
				$salidas["transac_id"]=$code_01;
			}
			catch (Exception $e){			
			}
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();		
		}
	}	
}
/**********************************/
/**********************************/
/***********VERIFICACION**********/
/**********************************/
/**********************************/
elseif($tp==10101){ //INF DE USUARIO
	if($verificar)	$salidas["_user"]=$_sysvars;
	elseif(!$verificar&&($_REQUEST["_ualias"]!=''||$_REQUEST["_upassw"]!='')) $error=7;
	else $error=9;
}
/**********************************/
/**********************************/
/*****EDITAR INFO DE USUARIO*******/
/**********************************/
/**********************************/
elseif($tp==10102){
	$name=isset($_REQUEST["name"])?$_REQUEST["name"]:'';
	$lastname=isset($_REQUEST["lastname"])?$_REQUEST["lastname"]:'';
	$moneda= isset($_REQUEST["moneda"])?$_REQUEST["moneda"]:'COP';	
					//
	if($name=='')
		$error=1;
	//			
	if($error==0){
		try{ 
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();
			$s="UPDATE adm_usuarios
				SET
				NOMBRE_U=:nombre,
				APELLIDO_U=:apellido,
				ID_IDIOMA=:idioma,
				ID_MONEDA=IFNULL((SELECT ID_MONEDA FROM fac_moneda WHERE COD01_MONEDA=:moneda AND ID_IDIOMA=1 LIMIT 1),1)
			WHERE ID_USUARIO=$_USUARIO";
			$Repreq = $dbEmpresa->prepare($s); 
			$Repreq->bindParam(':nombre', $name);
			$Repreq->bindParam(':apellido', $lastname);
			$Repreq->bindParam(':idioma', $Idioma);
			$Repreq->bindParam(':moneda', $moneda);
			$Repreq->execute();
			$dbEmpresa->commit();
			$salidas["transaction"]='OK';
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();			
		}
	}
	
}
elseif($tp==10103){ //CONTRASEÑA			
	
	$passwordo= isset($_REQUEST["passwordo"])?$_REQUEST["passwordo"]:'';
	$password= isset($_REQUEST["password"])?$_REQUEST["password"]:'';
	
	if(strlen($password)<4)
		$error=15;	

	if($error==0){
		$s="SELECT ID_USUARIO,CORREO_U
				FROM adm_usuarios
			WHERE PASSWORD_U=:passwordo AND ID_USUARIO=$_USUARIO";
		$reqV = $dbEmpresa->prepare($s);	 
		$reqV->bindParam(':passwordo', sha1($passwordo));	
		$reqV->execute();
		if(!($regV = $reqV->fetch()))  $error=13;
	}
		

	if($error==0){
		try{ 
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();

			$password=sha1($password);
			$s="UPDATE adm_usuarios
				SET
				PASSWORD_U=:password								
			WHERE ID_USUARIO=$_USUARIO";
			$req = $dbEmpresa->prepare($s); 
			$req->bindParam(':password', $password);
			$req->execute();

			/****** CREACION DE NUEVA SESION *****/
			$s=$sqlCons[1][0]." WHERE adm_usuarios.ID_USUARIO=:id LIMIT 1";
			$reqV = $dbEmpresa->prepare($s);
			$reqV ->bindParam(':id', $_USUARIO);
			$reqV ->execute();
			$regV = $reqV->fetch();
	
			$_sysvars["last"]=true;
			$_sysvars["_ualias"]=$regV["CORREO_U"];
			$_sysvars["_upassw"]=$regV["PASSWORD_U"];
			
			$_sysvars_r=verif_sp($_sysvars,$dbEmpresa);	
			$salidas["_user"]=$_sysvars;

			$dbEmpresa->commit();
			$salidas["transaction"]='OK';
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();			
		}
	}
}
elseif($tp==10104){ //RECUPERACION DE CONTRASEÑA
	$email=isset($_REQUEST["email"])?$_REQUEST["email"]:'';
	if(!checkmail($email)) $error=10;	

	if($error==0){
		$s=$sqlCons[1][0]." WHERE adm_usuarios.CORREO_U=:email LIMIT 1";
		$reqV = $dbEmpresa->prepare($s); 
		$reqV ->bindParam(':email', $email);
		$reqV ->execute();
		if(!$regV = $reqV->fetch()) $error=514;
	}
	if($error==0){
		try{ 
			$_USUARIO=$regV["ID_USUARIO"];
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();

			$s="SELECT * 
			    FROM adm_usuarios_rec
				WHERE ID_USUARIO=$_USUARIO AND USO=0 AND UTC_TIMESTAMP()<=DATE_ADD(FECHA,INTERVAL 3 DAY)"; 
			$reqR = $dbEmpresa->prepare($s); 
			$reqR ->execute();
			if($regR = $reqR->fetch()) 	$idREC=$regR["ID_REC"];
			else 						$idREC="NULL";
				
			$s="INSERT INTO adm_usuarios_rec (ID_REC,ID_USUARIO,FECHA)
					VALUES($idREC,$_USUARIO,UTC_TIMESTAMP())
				ON DUPLICATE KEY UPDATE FECHA=UTC_TIMESTAMP";
			$reqR = $dbEmpresa->prepare($s); 
			$reqR ->execute();	
			if($idREC=="NULL") $idREC=$dbEmpresa->lastInsertId();
			$dbEmpresa->commit();
			try{
				$_PARAMETROS=ParametrosAPI($dbEmpresa,$_sysvars_r,$_sysvars);
				CuerpoMail($Email,$_PROYECTO,$_EMPRESA,$_CLIENTE,$_IDIOMA,$_TZ,$_GCLIENTE,$_USUARIO,$_GRUPO,$_PARAMETROS);	
				/*******SEND EMAIL***********/
				$code_01=sha1($regV["ID_USUARIO"].$regV["PASSWORD_U"]).md5($regV["CORREO_U"].$regV["FECHA_U"]).sha1($idREC);
				$recuperacion=sprintf("http://%s/recovery/?code=%s",$_SERVER["SERVER_NAME"],$code_01);

				$to=array();
				$to[0]["mail"]=$regV["CORREO_U"];
				$to[0]["name"]=$regV["USUARIO_COMP"];

				$Asunto=$Email[1][3]['title'];							
				$html_cont=sprintf($Email[1][3]['body'],$recuperacion,$code_01);
				$rtamail=send_email_srv($_PARAMETROS,$Asunto,$html_cont,$to,array(),array(),true,$Email[1][3]['alt']);
				$salidas["rta_mail"]=$rtamail;
				/*******SEND EMAIL***********/
				$salidas["transaction"]='OK';
				$salidas["transac_id"]=$code_01;
			}
			catch (Exception $e){
			}

		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();			
		}
	}
	$salidas["required_fields"]=array();
	$salidas["required_fields"]["email"]="Email del usuario | tipo email";
	
				
}
elseif($tp==10105){ //EDITAR INFO DE USUARIO
	$document=isset($_REQUEST["document"])?$_REQUEST["document"]:'';
	$tdocument=isset($_REQUEST["tdocument"])?$_REQUEST["tdocument"]:'';
	$tz= isset($_REQUEST["tz"])?$_REQUEST["tz"]:116;
	$gender=isset($_REQUEST["gender"])?$_REQUEST["gender"]:1;
	$tel=isset($_REQUEST["tel"])?$_REQUEST["tel"]:'';
	$movil=isset($_REQUEST["movil"])?$_REQUEST["movil"]:'';
	$address=isset($_REQUEST["address"])?$_REQUEST["address"]:'';

	if(($document=='')||($tdocument=='')||($tdocument==0)
			||($tz=='')||($tz==0)
			||($gender=='')||($gender==0))
		$error=1;
	//			
	if($error==0){
		try{ 
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();
			$s="REPLACE INTO adm_usuarios_datos (ID_USUARIO,ID_DOCUMENTO,ID_TZ,DOCUMENTO,ID_GENERO,TELEFONO_U,TELEFONO2_U,DIRECCION_U)
					($_USUARIO,:tdocument,:tz,:document,:gender,:tel,:movil,:address)";
				$Repreq = $dbEmpresa->prepare($s); 
				$Repreq->bindParam(':tdocument', $tdocument);
				$Repreq->bindParam(':tz', $tz);
				$Repreq->bindParam(':document', $Idioma);
				$Repreq->bindParam(':gender', $gender);
				$Repreq->bindParam(':tel', $tel);
				$Repreq->bindParam(':movil', $movil);
				$Repreq->bindParam(':address', $address);
				$Repreq->execute();
				$dbEmpresa->commit();
				$salidas["transaction"]='OK';
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();			
		}
	}		
	$salidas["required_fields"]=array();
	$salidas["required_fields"]["tdocument"]="Tipo de documento de usuario | tipo enum (505)";
	$salidas["required_fields"]["tz"]="Zona horaria de usuario | tipo enum (506)";
	$salidas["required_fields"]["gender"]="Género de usuario | tipo enum (504)";
	$salidas["required_fields"]["document"]="Documento de Identidad | tipo text";
	$salidas["optional_fields"]=array();						
	$salidas["optional_fields"]["tel"]="Teléfono de usuario | tipo text";
	$salidas["required_fields"]["movil"]="Teléfono movil de usuario  | tipo text";
	$salidas["required_fields"]["address"]="Dirección de usuario | tipo text";	
}
elseif($tp==10106){ //MODIFICACIÓN DE CORREO
	$_sysvars["_token_a"]='';
	$_sysvars["_token_b"]='';		
	$_sysvars["client"]=$_REQUEST["client"];
	$_sysvars["_ip"]=$_REQUEST["_ip"];

	$email=isset($_REQUEST["email"])?$_REQUEST["email"]:'';
	
	if(!checkmail($email)) $error=10;	
	if($error==0){
		$s=$sqlCons[1][0]." WHERE adm_usuarios.CORREO_U=:email AND adm_usuarios.ID_USUARIO<>:id LIMIT 1";
		$reqV = $dbEmpresa->prepare($s); 
		$reqV ->bindParam(':email', $email);
		$reqV ->bindParam(':id', $_USUARIO);
		$reqV ->execute();
		if($regV = $reqV->fetch()) $error=20;
	}

	if($error==0){
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

			$_sysvars["last"]=true;
			$_sysvars["_ualias"]=$regV["CORREO_U"];
			$_sysvars["_upassw"]=$regV["PASSWORD_U"];
			$_sysvars_r=verif_sp($_sysvars,$dbEmpresa);	
			$salidas["_user"]=$_sysvars;

			$dbEmpresa->commit();
			try{
				/*******SEND EMAIL***********/	
				$_PARAMETROS=ParametrosAPI($dbEmpresa,$_sysvars_r,$_sysvars);			
				CuerpoMail($Email,$_PROYECTO,$_EMPRESA,$_CLIENTE,$_IDIOMA,$_TZ,$_GCLIENTE,$_USUARIO,$_GRUPO,$_PARAMETROS);				
				$code_01=sha1($_USUARIO.$email).md5($_USUARIO.$email).sha1($_USUARIO);
				$to=array();
				$to[0]["mail"]=$email;
				$to[0]["name"]=$regV["USUARIO_COMP"];

				$Asunto=$Email[1][8]['title'];
				$URL_Verif='http://'.$_SERVER["HTTP_HOST"]."/verification/?code=".$code_01;
				$html_cont=sprintf($Email[1][8]['body'],$email,$URL_Verif);
				$rtamail=send_email_srv($_PARAMETROS,$Asunto,$html_cont,$to,array(),array(),true,$Email[1][8]['alt']);
				
				$salidas["rta_mail"]=$rtamail;				
				/*******SEND EMAIL***********/
				$salidas["transaction"]='OK';
			}
			catch (Exception $e){
			}

		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();	
		}
	}
}
/**********************************/
/**********************************/
/************Editar Usuario********/
/**********************************/
/**********************************/
elseif($tp==20002){
	$name=isset($_REQUEST["name"])?$_REQUEST["name"]:'';
	$lastname=isset($_REQUEST["lastname"])?$_REQUEST["lastname"]:'';
	$email=isset($_REQUEST["email"])?$_REQUEST["email"]:'';
	$password=isset($_REQUEST["password"])?$_REQUEST["password"]:'';
	$passwordo=isset($_REQUEST["passwordo"])?$_REQUEST["passwordo"]:'';

	$_ALTPASSW=$password!='';

	$C_EMAIL=$email!=$_sysvars_r['email'];
	if($_ALTPASSW){
		$C_PASSW=$password!=$_sysvars_r['password']||$passwordo!=$_sysvars_r['password'];		
		$error=$_sysvars_r['password']!=$passwordo?13:0;
		$NSESSION=$C_EMAIL||$C_PASSW;
	}
	else
		$NSESSION=$C_EMAIL;

	if($name=='')
		$error=1;

	// CORREO EXISTENTE
	if($error==0&&$C_EMAIL){
		if(!checkmail($email)) $error=10;	
		if($error==0){
			$s=$sqlCons[1][0]." WHERE adm_usuarios.CORREO_U=:email AND adm_usuarios.ID_USUARIO<>:_USUARIO LIMIT 1";
			$reqV = $dbEmpresa->prepare($s); 
			$reqV ->bindParam(':email', $email);
			$reqV ->bindParam(':_USUARIO', $_USUARIO);
			$reqV ->execute();
			if($regV = $reqV->fetch())
				$error=20;
		}
	}

	//			
	if($error==0){
		try{ 
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();

			$VERIF_U=$C_EMAIL?1:0;
			if($_ALTPASSW){
				$s="UPDATE adm_usuarios
					SET
					NOMBRE_U=:nombre
				,	APELLIDO_U=:apellido
				,	PASSWORD_U=:password
				,	CORREO_U=:email
				,	VERIF_U=:VERIF_U
				WHERE ID_USUARIO=:_USUARIO";
				$Repreq = $dbEmpresa->prepare($s); 
				$Repreq->bindParam(':nombre', $name);
				$Repreq->bindParam(':apellido', $lastname);
				$Repreq->bindParam(':password', $password);
				$Repreq->bindParam(':email', $email);
				$Repreq->bindParam(':VERIF_U', $VERIF_U);
				$Repreq->bindParam(':_USUARIO', $_USUARIO);
				$Repreq->execute();
			}
			else{
				$s="UPDATE adm_usuarios
					SET
					NOMBRE_U=:nombre
				,	APELLIDO_U=:apellido
				,	CORREO_U=:email
				,	VERIF_U=:VERIF_U
				WHERE ID_USUARIO=:_USUARIO";
				$Repreq = $dbEmpresa->prepare($s); 
				$Repreq->bindParam(':nombre', $name);
				$Repreq->bindParam(':apellido', $lastname);
				$Repreq->bindParam(':email', $email);
				$Repreq->bindParam(':VERIF_U', $VERIF_U);
				$Repreq->bindParam(':_USUARIO', $_USUARIO);
				$Repreq->execute();
			}
			$salidas["upload"]='NO';
			// SOLO SE EDITA LA IMAGEN SI SE ENVIA setImage en 1
			$_PARAMETROS=ParametrosAPI($dbEmpresa,$_sysvars_r,$_sysvars);
			$control_img='imagen';
			if($_REQUEST["setImage"]==1&&isset($_FILES[$control_img])){				
				$cnf=36;
				$IdItem=$_USUARIO;
				require 		"../phplib/s3/aws.phar";
				$UploadDeleteArgs=array(
							'S3_BUCKET'=>$_PARAMETROS["S3_BUCKET"]
						,	'PROYECTO'=>$_PROYECTO
						,	'EMPRESA'=>$_EMPRESA
						,	'MODULE'=>$cnf
						,	'OBJECT'=>$IdItem
						,	'TP_FILE'=>'img');

				$tamano=$_FILES[$control_img]["size"];
				$ubicacion=$_FILES[$control_img]["tmp_name"];
				$finfo = finfo_open(FILEINFO_MIME_TYPE); 	
				$tipo=finfo_file($finfo, $ubicacion);	
				finfo_close($finfo);
				$nombre=$_FILES[$control_img]["name"];	
				if($tamano<=$fmax){
					if(fValid($tipo,$_files_clase[0])){
						$AwsS3 = Aws::factory(array('key' => $_PARAMETROS['S3_KEY'],'secret' => $_PARAMETROS['S3_SECRET']));
						UploadFiles($AwsS3,$control_img,$dbEmpresa,$UploadDeleteArgs,$Info);
						$s="UPDATE adm_usuarios 
							SET ID_FILE=IFNULL((SELECT adm_files.ID_FILE FROM adm_files WHERE adm_files.F_MODULE=$cnf AND adm_files.ID_OBJECT=$IdItem AND adm_files.F_SUB_EXT='org' AND adm_files.F_TP_FILE='img' LIMIT 1),0)
							WHERE ID_USUARIO=$IdItem";
						$dbEmpresa->exec($s);
						$salidas["upload"]='OK';
					}
					else $error=200021;
				}
				else $error=200022;
			}		


			$dbEmpresa->commit();



			if($NSESSION){
				/****** CREACION DE NUEVA SESION *****/
				$s=$sqlCons[1][0]." WHERE adm_usuarios.ID_USUARIO=:_USUARIO LIMIT 1";
				$reqV = $dbEmpresa->prepare($s);
				$reqV ->bindParam(':_USUARIO', $_USUARIO);
				$reqV ->execute();
				$regV = $reqV->fetch();
		
				$_sysvars["last"]=true;
				$_sysvars["_ualias"]=$regV["CORREO_U"];
				$_sysvars["_upassw"]=$regV["PASSWORD_U"];
				
				$_sysvars_r=verif_sp($_sysvars,$dbEmpresa);	
				$salidas["_user"]=$_sysvars;
				if($C_EMAIL){
					try{
						/*******SEND EMAIL***********/							
						CuerpoMail($Email,$_PROYECTO,$_EMPRESA,$_CLIENTE,$_IDIOMA,$_TZ,$_GCLIENTE,$_USUARIO,$_GRUPO,$_PARAMETROS);				
						$code_01=sha1($_USUARIO.$email).md5($_USUARIO.$email).sha1($_USUARIO);
						$to=array();
						$to[0]["mail"]=$regV["CORREO_U"];
						$to[0]["name"]=$regV["USUARIO_COMP"];

						$Asunto=$Email[1][8]['title'];
						$URL_Verif='http://'.$_SERVER["HTTP_HOST"]."/verification/?code=".$code_01;
						$html_cont=sprintf($Email[1][8]['body'],$email,$URL_Verif);
						$rtamail=send_email_srv($_PARAMETROS,$Asunto,$html_cont,$to,array(),array(),true,$Email[1][8]['alt']);					
						$salidas["rta_mail"]=$rtamail;				
					}
					catch (Exception $e){
					}
				}
			}
			$salidas["change"]=$NSESSION;
			$salidas["transaction"]='OK';
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();			
		}
	}
}
/**********************************/
/**********************************/
/****************FACEBOOK**********/
/**********************************/
/**********************************/
elseif($tp==10107 || $tp==10108){
	/* 53: No tiene correo
	54: Error de Facebook Token
	1:	token en blanco*/
	$_sysvars["_token_a"]=$_REQUEST["_token_a"];
	$_sysvars["_token_b"]=$_REQUEST["_token_b"];
	$_sysvars["_ualias"]=$_REQUEST["_ualias"];
	$_sysvars["_upassw"]=$_REQUEST["_upassw"];
	$_sysvars["client"]=$_REQUEST["client"];
	$_sysvars["_ip"]=$_REQUEST["_ip"];
	$token=isset($_REQUEST["token"])?$_REQUEST["token"]:'';
	$moneda=isset($_REQUEST["moneda"])?$_REQUEST["moneda"]:'COP';
	//
	if($token=='')	$error=1;
	//	

	if($error==0){		
		$fb_post_url='https://graph.facebook.com/v2.1/me?access_token='.$token.'&fields=id,first_name,email,last_name';
		$response=cURLdata($fb_post_url);
		if($response["id"]!=''){
			$_FBID=$response["id"];
			$_FBMAIL=$response["email"];

			$s = "SELECT 	
				adm_usuarios.ID_USUARIO,					
				adm_usuarios.CORREO_U,
				adm_usuarios.PASSWORD_U
			FROM adm_usuarios
			LEFT JOIN adm_usuarios_facebook ON adm_usuarios_facebook.ID_USUARIO=adm_usuarios.ID_USUARIO					
			WHERE adm_usuarios_facebook.ID_FACEBOOK=:_FBID AND adm_usuarios.HAB_U=0 LIMIT 1";	
			$reqVU = $dbEmpresa->prepare($s);	
			$reqVU->bindParam(':_FBID', $_FBID);				
			$reqVU->execute();

			if($regVU = $reqVU->fetch()){
				//USUARIO VERIFICADO	
				$_sysvars["_ualias"]=$regVU["CORREO_U"];
				$_sysvars["_upassw"]=$regVU["PASSWORD_U"];
				$_sysvars_r=verif_sp($_sysvars,$dbEmpresa);	
				$salidas["_user"]=$_sysvars;

				$_USUARIO=$regVU["ID_USUARIO"];
				$verificar=true;
			}
			else{
				if($_FBMAIL!=''){
					$s="SELECT 
						adm_usuarios.ID_USUARIO,
						adm_usuarios.CORREO_U,
						adm_usuarios.PASSWORD_U
					FROM adm_usuarios WHERE CORREO_U=:_FBMAIL LIMIT 1";		
					$reqE = $dbEmpresa->prepare($s);	 
					$reqE->bindParam(':_FBMAIL', $_FBMAIL);	
					$reqE->execute();
					$existe=$regE=$reqE->fetch();

				}
				else $existe=false;

				if($existe && $response["verified"]){
					//USUARIO VERIFICADO	
					$_sysvars["_ualias"]=$regE["CORREO_U"];
					$_sysvars["_upassw"]=$regE["PASSWORD_U"];
					$_sysvars_r=verif_sp($_sysvars,$dbEmpresa);	
					$salidas["_user"]=$_sysvars;

					$_USUARIO=$regE["ID_USUARIO"];
					$verificar=true;
					//RELACIONA SU CUENT CON EL USUARIO DE FACEBOOK
					$s="REPLACE INTO adm_usuarios_facebook
						(	ID_USUARIO
						,	ID_FACEBOOK
						,	TOKEN_FB
						,	NAME_FB
						,	LASTNAME_FB)
					VALUES(	:_USUARIO
						,	:_FBID
						,	:token
						,	:fbname
						,	:fblastname)";
					$req = $dbEmpresa->prepare($s);	
					$req->bindParam(':_USUARIO',	$_USUARIO); 
					$req->bindParam(':_FBID', 		$_FBID);
					$req->bindParam(':token', 		$token);
					$req->bindParam(':fbname', 		$response["first_name"]);
					$req->bindParam(':fblastname', 	$response["last_name"]);
					$req->execute();
				}
				elseif(!$existe){					
					try{ 
						$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
						$dbEmpresa->beginTransaction();

						$userName=uniqid();						
						$password=substr(clave($userName),0,6);

						$_FBMAIL_T=$_FBMAIL==''?$_FBID.'@facebook.com':$_FBMAIL;
						$s="INSERT INTO adm_usuarios
							(ALIAS
						,	NOMBRE_U
						,	APELLIDO_U
						,	CORREO_U
						,	PASSWORD_U
						,	FECHA_U
						,	ID_IDIOMA
						,	ID_MONEDA)
						VALUES(
							:userName
						,	:nombre
						,	:apellido
						,	:correo
						,	:password
						,	UTC_TIMESTAMP()
						,	:idioma
						,	IFNULL((SELECT ID_MONEDA FROM fac_moneda WHERE COD01_MONEDA=:moneda AND ID_IDIOMA=1 LIMIT 1),1))";
						$reqUsr = $dbEmpresa->prepare($s);
						$reqUsr->bindParam(':userName', 	$userName); 
						$reqUsr->bindParam(':nombre', 		$response["first_name"]);
						$reqUsr->bindParam(':apellido', 	$response["last_name"]);
						$reqUsr->bindParam(':correo', 		$_FBMAIL_T);
						$reqUsr->bindParam(':password', 	sha1($password));
						$reqUsr->bindParam(':idioma', 		$Idioma);
						$reqUsr->bindParam(':moneda', 		$moneda);
						$reqUsr->execute();	
						$_USUARIO=$dbEmpresa->lastInsertId();	
						$verificar=true;
						
						$fb_post_url='https://graph.facebook.com/v2.1/me/picture?redirect=0&height=200&type=normal&width=200&access_token='.$token;
						$foto=cURLdata($fb_post_url);	
						$regInfo=$foto;
						$foto_fb=$regInfo["data"]["url"];								
						$dir_download=$dir_rel[3]."/36-".$_PROYECTO.'-'.$_EMPRESA.'-'.$_USUARIO.'.jpg';							
						rec_img($foto_fb,$dir_download);


						//RELACIONA SU CUENTA CON EL USUARIO DE FACEBOOK
						$s="INSERT INTO adm_usuarios_facebook
							(	ID_USUARIO
							,	ID_FACEBOOK
							,	TOKEN_FB
							,	NAME_FB
							,	LASTNAME_FB)
						VALUES(:_USUARIO
							,	:_FBID
							,	:token
							,	:fbname
							,	:fblastname)";
						$req = $dbEmpresa->prepare($s);	
						$req->bindParam(':_USUARIO', 	$_USUARIO); 
						$req->bindParam(':_FBID', 		$_FBID);
						$req->bindParam(':token', 		$token);
						$req->bindParam(':fbname', 		$response["first_name"]);
						$req->bindParam(':fblastname', 	$response["last_name"]);
						$req->execute();

											
						$_PARAMETROS=ParametrosAPI($dbEmpresa,$_sysvars_r,$_sysvars);	

						//LO RELACIONA CON UNA EMPRESA 
						if($_PARAMETROS["ID_GRUPO"]!='' && $_PARAMETROS["ID_GRUPO"]!=0){
							$s="INSERT INTO adm_usuarios_empresa 
									(ID_MEMPRESA
									,ID_USUARIO
									,ID_GRUPO
									,LAST)
								VALUES($_CLIENTE
									,	:iduser
									,	:grupo
									,	1)";
							$Repreq = $dbEmpresa->prepare($s);
							$Repreq->bindParam(':iduser', 	$_USUARIO); 
							$Repreq->bindParam(':grupo', 	$_PARAMETROS["ID_GRUPO"]);
							$Repreq->execute();
						}			
												
						$AwsS3 = Aws::factory(array('key' => $_PARAMETROS['S3_KEY'],'secret' => $_PARAMETROS['S3_SECRET']));
						$UploadArgs=array('S3_BUCKET'=>$_PARAMETROS["S3_BUCKET"]
										,	'PROYECTO'=>$_PROYECTO
										,	'EMPRESA'=>$_EMPRESA
										,	'MODULE'=>36
										,	'OBJECT'=>$_USUARIO
										,	'TP_FILE'=>'img'
										,	'control_type'=>1);
						UploadFiles($AwsS3,$dir_download,$dbEmpresa,$UploadArgs,$Info);
						

						$s="UPDATE adm_usuarios 
							SET ID_FILE=IFNULL((SELECT adm_files.ID_FILE FROM adm_files WHERE adm_files.F_MODULE=36 AND adm_files.ID_OBJECT=$_USUARIO AND adm_files.F_SUB_EXT='org' AND adm_files.F_TP_FILE='img' LIMIT 1),0)
							WHERE ID_USUARIO=$_USUARIO";
						$dbEmpresa->exec($s);
						

						if($_FBMAIL!=''){
							try{							
								/*******SEND EMAIL***********/			
								$email=$_FBMAIL;	
								$name=$response["first_name"];	
								$lastname=$response["last_name"];
								CuerpoMail($Email,$_PROYECTO,$_EMPRESA,$_CLIENTE,$_IDIOMA,$_TZ,$_GCLIENTE,$_USUARIO,$_GRUPO,$_PARAMETROS);											
								$code_01=sha1($_USUARIO.$email).md5($_USUARIO.$email).sha1($_USUARIO);
								$to=array();
								$to[0]["mail"]=$email;
								$to[0]["name"]=$name.' '.$lastname;
								$Asunto=$Email[1][0]['title'];
								$URL_Verif='http://'.$_SERVER["HTTP_HOST"]."/verification/?code=".$code_01;
								$html_cont=sprintf($Email[1][0]['body'],$email,$password,$URL_Verif);
								$rtamail=send_email_srv($_PARAMETROS,$Asunto,$html_cont,$to,array(),array(),true,$Email[1][0]['alt']);
								$salidas["rta_mail"]=$rtamail;
								/*******SEND EMAIL***********/
								$salidas["transaction"]='OK';
								$salidas["transac_id"]=$code_01;
							}
							catch (Exception $e){
							}
						}
					}
					catch (Exception $e){						
						$dbEmpresa->rollBack();	
					}
					
					/*************************/
					/*************************/					
					
					//USUARIO VERIFICADO
					$_sysvars["_ualias"]=$_FBMAIL_T;
					$_sysvars["_upassw"]=sha1($password);
					$_sysvars_r=verif_sp($_sysvars,$dbEmpresa);
					$salidas["_user"]=$_sysvars;
					$dbEmpresa->commit();
				}
				else $error=53;
			}					
		}
		else $error=54;
	}	
	/*$salidas["required_fields"]=array();
	$salidas["required_fields"]["token"]="Token valido de usuario de Facebook | tipo string";	
	$salidas["optional_fields"]=array();						
	$salidas["required_fields"]["moneda"]="Moneda usada por defecto por el usuario | tipo date enum";*/
}
//UNE CUENTA CON FACEBOOK
elseif($tp==10109){
	$token=isset($_REQUEST["token"])?$_REQUEST["token"]:'';
	//
	if($token=='')	$error=1;
	//	

	if($error==0){
		try{ 
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();
			$fb_post_url='https://graph.facebook.com/me?access_token='.$token;
			$response=cURLdata($fb_post_url);
			if($response["id"]!=''){
				//ELIMINA NEXOS ACTUALES
				$s="DELETE FROM adm_usuarios_facebook WHERE ID_FACEBOOK=:id";
				$req = $dbEmpresa->prepare($s);	
				$req->bindParam(':id', 		$response["id"]); 
				$req->execute();
				
				//RELACIONA SU CUENT CON EL USUARIO DE FACEBOOK
				$s="INSERT INTO adm_usuarios_facebook
					(ID_USUARIO,ID_FACEBOOK,TOKEN_FB,NAME_FB,LASTNAME_FB)
				VALUES(:iduser,:fbid,:token,:fbname,:fblastname)";
				$req = $dbEmpresa->prepare($s);	
				$req->bindParam(':iduser', 		$_sysvars_r["id"]); 
				$req->bindParam(':fbid', 		$response["id"]);
				$req->bindParam(':token', 		$token);
				$req->bindParam(':fbname', 		$response["first_name"]);
				$req->bindParam(':fblastname', 	$response["last_name"]);
				$req->execute();
				$dbEmpresa->commit();
				$salidas["_user"]=$_sysvars;	
			}
			else $error=53;
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();			
		}
	}	
	$salidas["required_fields"]=array();
	$salidas["required_fields"]["token"]="Token valido de usuario de Facebook | tipo string";							
}
elseif($tp==10110){ //CAMBIAR FOTO	

	$control_img='profile';
	$tamano=$_FILES[$control_img]["size"];
	$ubicacion=$_FILES[$control_img]["tmp_name"];
	$nombre=$_FILES[$control_img]["name"];
	$finfo = finfo_open(FILEINFO_MIME_TYPE); 	
	$tipo=finfo_file($finfo, $ubicacion);	
	finfo_close($finfo);	

	if(($tamano<=$fmin) || ($tamano>$fmax))	$error=2;
	if(tipo_archivo($tipo)!=1)				$error=3;

	if($error==0){	
		try{  				
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();		

			/*************************/
			/*************************/
	
			$_PARAMETROS=ParametrosAPI($dbEmpresa,$_sysvars_r,$_sysvars);
			$AwsS3 = Aws::factory(array('key' => $_PARAMETROS['S3_KEY'],'secret' => $_PARAMETROS['S3_SECRET']));	

			$UploadArgs=array('S3_BUCKET'=>$_PARAMETROS["S3_BUCKET"]
							,	'PROYECTO'=>$_PROYECTO
							,	'EMPRESA'=>$_EMPRESA
							,	'MODULE'=>36
							,	'OBJECT'=>$_USUARIO
							,	'TP_FILE'=>'img');
			UploadFiles($AwsS3,$control_img,$dbEmpresa,$UploadArgs);
			/*************************/
			/*************************/
			$s="UPDATE adm_usuarios 
				SET ID_FILE=IFNULL((SELECT adm_files.ID_FILE FROM adm_files WHERE adm_files.F_MODULE=36 AND adm_files.ID_OBJECT=$_USUARIO AND adm_files.F_SUB_EXT='org' AND adm_files.F_TP_FILE='img' LIMIT 1),0)
				WHERE ID_USUARIO=$_USUARIO";
			$dbEmpresa->exec($s);
				

			$dbEmpresa->commit();
			
			$salidas["transaction"]='OK';
	 	}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}			
	}
}
//CERRAR SESSION
elseif($tp==10111){
	$s="UPDATE adm_usuarios_reg
		SET CERRADO_U=1
	WHERE ID_SES=:ses";
	$reqSes = $dbEmpresa->prepare($s);	
	$reqSes->bindParam(':ses',$_sysvars_r["ses"]);
	$reqSes->execute();
	$salidas["transaction"]='OK';

	$s="UPDATE sys_notification 
	SET ACTIVE_NOT=0 
	WHERE 	(ID_PHONE IN (SELECT ID_PHONE FROM sys_phones WHERE KEY_PHONE=:_key AND TYPE_PHONE=:_phone) OR ID_PCSER=:_session)
		AND ACTIVE_NOT=1";
	$reqPhone = $dbEmpresa->prepare($s);
	$reqPhone->bindParam(':_key', $_sysvars["_key"]);
	$reqPhone->bindParam(':_phone', $_sysvars["_phone"]);
	$reqPhone->bindParam(':_session', $_sysvars["_session"]);
	$reqPhone->execute();
}
elseif($tp==10112){ //CAMBIAR LOGO	
	$client=$_sysvars["client"];

	$control='profile';
	$tamano=$_FILES[$control]["size"];
	$ubicacion=$_FILES[$control]["tmp_name"];
	$nombre=$_FILES[$control]["name"];
	$finfo = finfo_open(FILEINFO_MIME_TYPE); 	
	$tipo=finfo_file($finfo, $ubicacion);	
	finfo_close($finfo);	

	if(($tamano<=$fmin) || ($tamano>$fmax))	$error=2;
	if(tipo_archivo($tipo)!=1)				$error=3;

	if($client==0)							$error=9;

	if($error==0){	
		try{  				
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();		

			/*************************/
			/*************************/
			$_PARAMETROS=ParametrosAPI($dbEmpresa,$_sysvars_r,$_sysvars);

			$AwsS3 = Aws::factory(array('key' => $_PARAMETROS['S3_KEY'],'secret' => $_PARAMETROS['S3_SECRET']));	

			$UploadArgs=array('S3_BUCKET'=>$_PARAMETROS["S3_BUCKET"]
							,	'PROYECTO'=>$_PROYECTO
							,	'EMPRESA'=>$_EMPRESA
							,	'MODULE'=>0
							,	'OBJECT'=>$client
							,	'TP_FILE'=>'LogoClient');
			UploadFiles($AwsS3,$control_img,$dbEmpresa,$UploadArgs,$Info);
			/*************************/
			/*************************/
			$s="UPDATE adm_empresas 
				SET ID_FILE=IFNULL((SELECT adm_files.ID_FILE FROM adm_files WHERE adm_files.F_MODULE=0 AND adm_files.ID_OBJECT=$client AND adm_files.F_SUB_EXT='org' AND adm_files.F_TP_FILE='LogoClient' LIMIT 1),0)
				WHERE ID_MEMPRESA=$client";
			$dbEmpresa->exec($s);

			$dbEmpresa->commit();
			$salidas["transaction"]='OK';
	 	}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}			
	}
}
elseif($tp==10113){ //COMPANY LIST

	$s="SELECT 
		adm_empresas.ID_MEMPRESA,
	    adm_empresas.NOMB_MEMPRESA,
	    adm_empresas.URL,
		adm_usuarios_empresa.ID_GRUPO,
		/*IMAGEN*/
		IFNULL(adm_files.ID_FILE,0) AS M_IMG,
		adm_files.F_EXT
	FROM adm_usuarios_empresa 
	LEFT JOIN adm_empresas ON adm_empresas.ID_MEMPRESA=adm_usuarios_empresa.ID_MEMPRESA
	LEFT JOIN adm_files ON adm_files.F_MODULE=0 AND adm_files.ID_OBJECT=adm_empresas.ID_MEMPRESA AND adm_files.F_SUB_EXT='org' AND adm_files.F_TP_FILE='LogoClient'
	WHERE adm_usuarios_empresa.ID_USUARIO=$_USUARIO";
	$req = $dbEmpresa->prepare($s);	 
	$req->execute();
	while($regItem = $req->fetch()){

		$idItem=$regItem["ID_MEMPRESA"];		
				
		$item[$idItem]=array();
		$item[$idItem]["id"]=$idItem;
		$item[$idItem]["company"]=$regItem["NOMB_MEMPRESA"];
		$item[$idItem]["slug"]=$regItem["URL"];
		$item[$idItem]["group"]=$regItem["ID_GRUPO"];

		$ArrayImg["OBJETO"]=$regItem["ID_MEMPRESA"];
		$ArrayImg["MODULO"]=0;
		$ArrayImg["EXT"]=$regItem["F_EXT"];
		$ArrayImg["TP"]='LogoClient';
		$item[$idItem]["imagen"]=ImgBlanc($regItem["M_IMG"],$ArrayImg);

	}
	$salidas = OrderPrint($item);
}
elseif($tp==10114){ //SAVE MAIL CLIC
	$id_send_sha=$_REQUEST["id_send_sha"];

	$s="UPDATE log_mail_send 
		SET VERIFICADO=1, FECHAV_SEND=UTC_TIMESTAMP()
		WHERE MD5(CONCAT(log_mail_send.ID_SEND,SHA1(log_mail_send.ID_SEND)))=:id_send_sha";
	$reqSend = $dbEmpresa->prepare($s);
	$reqSend->bindParam(':id_send_sha', $id_send_sha);
	$reqSend->execute();

}
?>
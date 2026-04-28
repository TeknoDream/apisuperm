<?php
//ini_set('display_errors', 'On');
//ini_set('display_startup_errors', 'Off');

error_reporting(E_ERROR | E_WARNING | E_PARSE);

$_PROYECTO=16;
$_EMPRESA=12;
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

foreach ($argv as $key => $value) {
	$val_s=explode("=", $value);
	$results[$val_s[0]]=$val_s[1];
}
$tp=$results["tp"];


//Enviar Notificación a Interesados
if($tp==4){
	try{ 
		$salidas=array();
		$item_ids_sent=array();
		$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
		$dbEmpresa->beginTransaction();	

		
		$OfertaCandidatos=array();
		$s=$sqlCons[5][3503]." WHERE x_ofertas_usuario.SEND_USUOF=0 ORDER BY x_ofertas_usuario.FECHAS_USUOF ";
		$reqItem = $dbEmpresa->prepare($s); 
		$reqItem->execute();	
		while($regItem = $reqItem->fetch()){
			$item_ids_sent[]=$regItem["ID_USUOF"];
			

			// Al disponible sobre la oferta que aplicó
			$name=$regItem["NOMBRE_U_E"].' '.$regItem["APELLIDO_U_E"];
			$mail=$regItem["CORREO_U_E"];	

			$to=array();
			$to[0]["mail"]=$mail;
			$to[0]["name"]=$name;				

			$Asunto=$Email[1][554]['title'];
			$Alt=$Email[1][554]['alt'];
			$html_cont=sprintf($Email[1][554]['body']
					,	$name
					,	$regItem["NOMB_OCUP"]
					,	$regItem["FECHAI_OFERTA"]
					,	$regItem["NCOM_EMPRESA"]);

			$rtamail=send_email_srv($_PARAMETROS,$Asunto,$html_cont,$to,array(),array(),true,$Alt);
			$salidas["us_mail"][$regItem["CORREO_U_E"]]=$rtamail;


			if(!isset($OfertaCandidatos[$regItem['ID_OFERTA']])){
				$OfertaCandidatos[$regItem['ID_OFERTA']]=array('company'=>$regItem["NCOM_EMPRESA"]
															,	'email'=>$regItem["EMAIL_EMPRESA"]
															,	'offer'	=>$regItem["NOMB_OCUP"]
															,	'candidatos'=>array());
			}		
			$OfertaCandidatos[$regItem['ID_OFERTA']]['candidatos'][$regItem["ID_USUARIO_U_E"]]=array('name'	=>$regItem["NOMBRE_U_E"].' '.$regItem["APELLIDO_U_E"]
																		,	'slug'	=>$regItem["ALIAS_E"]);
		}

		
		foreach ($OfertaCandidatos as $IdOf => $Data) {
			$CPost=0;
			$TablaSend='<span style="margin:5px auto; font-size:1.2em" >';
			foreach ($Data['candidatos'] as $candidato) {
				$href=sprintf('%s/empleado/%s',$_PARAMETROS["LWSERVICE"],$candidato['slug']);
				$TablaSend.=sprintf('<span>&nbsp; &nbsp; &nbsp;<a href="%s" style="color:#f08424;" title="%s">%s</a></span><br />',$href,$candidato['name'],$candidato['name']);
				$CPost++;
			}
			$TablaSend.=sprintf('</span>');

			// A la empresa interesada
			$to=array();
			$to[0]["mail"]=$Data['email'];
			$to[0]["name"]=$Data['company'];				

			$Asunto=$Email[1][555]['title'];
			$Alt=$Email[1][555]['alt'];
			$html_cont=sprintf($Email[1][555]['body']
					,	$Data['company']
					,	$CPost
					,	$Data['offer']
					,	$TablaSend);

			$rtamail=send_email_srv($_PARAMETROS,$Asunto,$html_cont,$to,array(),array(),true,$Alt);
			$salidas["company"][$Data["email"]]=$rtamail;
		}
		$REST_COUNT=count($item_ids_sent);
		$REST_RESULTS=$REST_COUNT>0;

		if($REST_RESULTS)
			$id_items=implode(",",$item_ids_sent);

		if($REST_RESULTS){
			$s="UPDATE x_ofertas_usuario
				SET SEND_USUOF=1
				WHERE ID_USUOF IN ($id_items)";
			$reqItem = $dbEmpresa->prepare($s); 
			$reqItem->execute();	
		}	
		//print_r($salidas);
		$dbEmpresa->commit();	
	}
	catch (Exception $e){
		$dbEmpresa->rollBack();
	}
}
//Notificar nuevas ofertas
elseif($tp==5){
	try{ 
		$salidas=array();
		$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
		$dbEmpresa->beginTransaction();	

		$LimitePE=40;
		$DifDias=3;

		$ESTTZ = new DateTimeZone('UTC');
		$hoyOBJ = new DateTime(date(DATE_ATOM),$ESTTZ); 
		$hoySTR=$hoyOBJ->format('Y-m-d H:i');

		$ofertas=0;
		$s="SELECT COUNT(x_ofertas.ID_OFERTA) AS OFERTAS
		FROM x_ofertas
		WHERE HAB_OFERTA=0 AND DATE('$hoySTR') BETWEEN FECHAI_OFERTA AND FECHAF_OFERTA";
		$reqC = $dbEmpresa->prepare($s); 
		$reqC->execute();	
		if($regC = $reqC->fetch())	$ofertas=$regC['OFERTAS'];

		$item_ids_sent=array();
		if($ofertas>=5){
			$s="SELECT 
				adm_usuarios.ID_USUARIO,
			    adm_usuarios.ALIAS,
			    adm_usuarios.NOMBRE_U,
			    adm_usuarios.APELLIDO_U,
			    adm_usuarios.FECHA_U,
			    adm_usuarios.CORREO_U
			FROM adm_usuarios
			LEFT JOIN x_usuario_extra ON x_usuario_extra.ID_USUARIO=adm_usuarios.ID_USUARIO
			LEFT JOIN x_usuario_mails ON x_usuario_mails.ID_USUARIO=adm_usuarios.ID_USUARIO AND x_usuario_mails.LAST_SENT=1
			WHERE x_usuario_extra.TYPE_USUARIO=0 AND adm_usuarios.ID_USUARIO=1281
				AND (ISNULL(x_usuario_mails.ID_SENT) OR DATEDIFF('$hoySTR',x_usuario_mails.FECHAS_SENT)>=$DifDias)
			LIMIT $LimitePE";
			$reqItem = $dbEmpresa->prepare($s); 
			$reqItem->execute();	
			while($regItem = $reqItem->fetch()){
				$item_ids_sent[]=$regItem["ID_USUARIO"];
				
				$name=$regItem["NOMBRE_U"].' '.$regItem["APELLIDO_U"];
				$mail=$regItem["CORREO_U"];	

				$to=array();
				$to[0]["mail"]=$mail;
				$to[0]["name"]=$name;				

				$Asunto=$Email[1][556]['title'];
				$Alt=$Email[1][556]['alt'];
				$html_cont=sprintf($Email[1][556]['body']
						,	$name
						,	$ofertas
						,	$regItem["FECHAI_OFERTA"]
						,	$regItem["NCOM_EMPRESA"]);

				$rtamail=send_email_srv($_PARAMETROS,$Asunto,$html_cont,$to,array(),array(),true,$Alt);
				$salidas["us_mail"][$mail]=$rtamail;
			}

			$REST_COUNT=count($item_ids_sent);
			$REST_RESULTS=$REST_COUNT>0;

			if($REST_RESULTS)
				$id_items=implode(",",$item_ids_sent);

			if($REST_RESULTS){
				$s="UPDATE x_usuario_mails SET LAST_SENT=0 WHERE ID_SENT IN ($id_items)";
				$reqItem = $dbEmpresa->prepare($s); 
				$reqItem->execute();

				$s="INSERT INTO x_usuario_mails
						(ID_USUARIO
					,	FECHAS_SENT
					,	LAST_SENT)
					(SELECT ID_USUARIO,UTC_TIMESTAMP(),1 FROM adm_usuarios WHERE ID_USUARIO IN ($id_items))";
				$reqItem = $dbEmpresa->prepare($s); 
				$reqItem->execute();
			}
		}
		//print_r($salidas);
		$dbEmpresa->commit();	
	}
	catch (Exception $e){
		$dbEmpresa->rollBack();
	}
}
//Calificar Disponible
elseif($tp==6){
	try{ 
		$salidas=array();
		$item_ids_sent=array();
		$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
		$dbEmpresa->beginTransaction();	

		$DifDias=2;
		$LimitePE=40;

		$ESTTZ = new DateTimeZone('UTC');
		$hoyOBJ = new DateTime(date(DATE_ATOM),$ESTTZ); 
		$hoySTR=$hoyOBJ->format('Y-m-d H:i');

		$s=$sqlCons[4][3500]." WHERE x_empresas_contacto.SEND_CONT=0 
			AND DATEDIFF('$hoySTR',x_empresas_contacto.FECHAS_CONT)>=$DifDias	LIMIT $LimitePE";
		$reqItem = $dbEmpresa->prepare($s); 
		$reqItem->execute();	
		while($regItem = $reqItem->fetch()){
			$item_ids_sent[]=$regItem["ID_CONT"];


			$user=$regItem["NOMBRE_U_D"].' '.$regItem["APELLIDO_U_D"];
			$href=sprintf('%s/empleado/%s#calificar',$_PARAMETROS["LWSERVICE"],$regItem['ALIAS_D']);

			$name=$regItem["NCOM_EMPRESA"];
			$mail=$regItem["EMAIL_EMPRESA"];	

			$to=array();
			$to[0]["mail"]=$mail;
			$to[0]["name"]=$name;				

			$Asunto=$Email[1][558]['title'];
			$Alt=$Email[1][558]['alt'];
			$html_cont=sprintf($Email[1][558]['body']
					,	$name
					,	$user
					,	$href);
			$rtamail=send_email_srv($_PARAMETROS,$Asunto,$html_cont,$to,array(),array(),true,$Alt);
			$salidas["us_mail"][$mail]=$rtamail;
		}
		$REST_COUNT=count($item_ids_sent);
		$REST_RESULTS=$REST_COUNT>0;

		if($REST_RESULTS)
			$id_items=implode(",",$item_ids_sent);

		if($REST_RESULTS){
			$s="UPDATE x_empresas_contacto
				SET SEND_CONT=1
				WHERE ID_CONT IN ($id_items)";
			$reqItem = $dbEmpresa->prepare($s); 
			$reqItem->execute();	
		}	
		//print_r($salidas);
		$dbEmpresa->commit();	
	}
	catch (Exception $e){
		$dbEmpresa->rollBack();
	}
}
//Calificar Disponible
elseif($tp==7){
	try{ 
		$salidas=array();
		$item_ids_sent=array();
		$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
		$dbEmpresa->beginTransaction();	

		$LimitePE=40;

	
		$s="SELECT 
			x_export.ID_EXP,
		    x_export.EMAIL_EXP,
		    x_export.NAME_EXP,
		    x_export.LASTNAME_EXP,
		    x_export.NICK_EXP,
		    x_export.CONV_EXP
		FROM x_export
		LEFT JOIN adm_usuarios ON adm_usuarios.CORREO_U=x_export.EMAIL_EXP
		WHERE x_export.CONV_EXP=0 AND ISNULL(adm_usuarios.ID_USUARIO) LIMIT $LimitePE";		
		$reqItem = $dbEmpresa->prepare($s); 
		$reqItem->execute();	
		while($regItem = $reqItem->fetch()){
			$item_ids_sent[]=$regItem["ID_EXP"];

			$Email=$regItem['EMAIL_EXP'];
			$Name=$regItem['NAME_EXP'];
			$Lastname==$regItem['LASTNAME_EXP'];
			$Nick==$regItem['NICK_EXP'];
			$PassWord=substr(sha1($Email),0,5);

			$ShowName=$regItem['NAME_EXP']==''?$Nick:$Name.' '.$Lastname;

			//////////////LINK//////////////
			$link=cambiar_url($Nick,2);		
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
				,1)";
			$Repreq = $dbEmpresa->prepare($s);
			$Repreq->bindParam(':links', $links); 
			$Repreq->bindParam(':nombre', $Name);
			$Repreq->bindParam(':apellido', $Lastname);
			$Repreq->bindParam(':correo', $Email);
			$Repreq->bindParam(':password', sha1($PassWord));
			$Repreq->execute();		

			$to=array();
			$to[0]["mail"]=$Email;
			$to[0]["name"]=$ShowName;				

			$Asunto=$Email[1][560]['title'];
			$Alt=$Email[1][560]['alt'];
			$html_cont=sprintf($Email[1][558]['body']
					,	$ShowName
					,	$Email
					,	$ShowName);
			$rtamail=send_email_srv($_PARAMETROS,$Asunto,$html_cont,$to,array(),array(),true,$Alt);
			$salidas["us_mail"][$mail]=$rtamail;
		}

		$REST_COUNT=count($item_ids_sent);
		$REST_RESULTS=$REST_COUNT>0;

		if($REST_RESULTS)
			$id_items=implode(",",$item_ids_sent);

		if($REST_RESULTS){
			$s="UPDATE x_export
				SET CONV_EXP=1
				WHERE ID_EXP IN ($id_items)";
			$reqItem = $dbEmpresa->prepare($s); 
			$reqItem->execute();	
		}	
		print_r($salidas);
		$dbEmpresa->commit();	
	}
	catch (Exception $e){
		$dbEmpresa->rollBack();
	}
}
?>
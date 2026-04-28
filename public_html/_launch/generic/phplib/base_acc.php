<?php
session_start();
require_once "../../../phplib/funciones.php";
require_once "../../../phplib/consultas.php";
require_once "../../../phplib/plantilla/cuerpomail.php";
require_once "../../../phplib/mail/PHPMailer/class.phpmailer.php";
require_once "../../../phplib/mail/sendmail_new.php";

$state=ConectarseAPI($dbEmpresa,$subdominio,$_sysvars);
//CARGA DE NUEVO CONSULTAS
$_sysvars_r=$_sysvars;
include 		"../../../phplib/variables_se.php";
Consultas($sqlCons,$sqlOrder,$_PROYECTO,$_CLIENTE,$_IDIOMA,$_TZ,$_GCLIENTE,$_USUARIO,$_GRUPO,$_PARAMETROS);
$_PARAMETROS=ParametrosAPI($dbEmpresa,$_sysvars_r,$_sysvars);



Consultas($sqlCons,$sqlOrder,$_PROYECTO,$_CLIENTE,$_IDIOMA,$_TZ,$_GCLIENTE,$_USUARIO,$_GRUPO,$_PARAMETROS);
CuerpoMail($Email,$_PROYECTO,$_EMPRESA,$_CLIENTE,$_IDIOMA,$_TZ,$_GCLIENTE,$_USUARIO,$_GRUPO,$_PARAMETROS);

if(isset($_REQUEST['data'])){
	parse_str($_REQUEST["data"], $resDec);
	$result=$resDec;
}
else $result=$_REQUEST;
$tp=isset($result["tp"])?$result["tp"]:1;
$sh=isset($result["sh"])?$result["sh"]:0;


$salidas=array();


$error=false;

$badfields=array();
$remove=array();
$auto=array();
$node=array();
$localstorage=array();
$message_esp=array();
$mensaje=array();
$hide=array();
$reload=false;
$close=false;
$words=false;
$rewrite=false;
$go=false;


if($tp==500){		
	/*VERIFICACION DE DOMINIO*/
	$_sysvars["_ip"]=$_SERVER["REMOTE_ADDR"]; //IP DEL CLIENTE

	/*SE USA PARA LA SESION PREVIAMENTE INICIADA*/
	$_sysvars["_token_a"]='';
	$_sysvars["_token_b"]='';

	/*SE USA PARA INICIAR SESION DE FORMA AUTOMATICA*/
	$_sysvars["_ualias"]=$result["usuario"]; //USUARIO
	$_sysvars["_upassw"]=sha1($result["passw"]); //PASSWORD
	$_sysvars["_memory"]=isset($result["recordar"])?1:0; //RECORDAR SESSION	
	$_sysvars["_session"]=session_id(); //SESION ID DEL CLIENTE

	$_sysvars_r=verif_sp($_sysvars,$dbEmpresa);
	$verificar=$_sysvars_r["return"];
	if($verificar){
		$tiempo=$_sysvars["_ttend"];	
		$mensaje[0]='txt-MSJ172-0';
		$mensaje[1]=$result["recordar"]==1?'txt-MSJ175-0':'txt-MSJ176-0';
		setcookie("_token_a",$_sysvars["_token_a"],$tiempo,'/',$dominio_activo,false,true);
		setcookie("_token_b",$_sysvars["_token_b"],$tiempo,'/',$dominio_activo,false,true);
		$reload=true;
		$close=true;
		$words=true;	
	}
	else{
		$mensaje[0]='txt-MSJ7-0';
		$error=true;
	}
}
elseif($tp==502){	
	//
	if(($result["empresa"]=='')){
		$mensaje[0]='txt-MSJ1-0';
		$error=true;
	}

	if(!checkmail($result["email"])){
		$mensaje[0]='txt-MSJ10-0';
		$error=true;
	}
	
	if(!$error){
		try{  				
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();
			//SEND MAIL			
			$s="INSERT INTO adm_contactenos (NOMBRE,CORREO,FECHA,ASUNTO,MENSAJE)
					VALUES(:empresa,:email,UTC_TIMESTAMP(),'CÓDIGO',:mensaje)";
			$req = $dbEmpresa->prepare($s); 
			$req->bindParam(':empresa', $result["empresa"]);
			$req->bindParam(':email', $result["email"]);
			$req->bindParam(':mensaje', $result["mensaje"]);
			$req->execute();	
			$idREC=$dbEmpresa->lastInsertId();	
			$dbEmpresa->commit();

			try{
				//EMAIL 1
				$mensaje_asunto=sprintf(
					"<ul>".
						"<li>Correo: %s</li>".
						"<li>Empresa: %s</li>".
						"<li>Mensaje: %s</li>".
					"</ul>",$result["email"],$result["empresa"],nl2br($result["mensaje"]));

				/*******SEND EMAIL***********/
				$to=array();
				$to[0]["mail"]=$_PARAMETROS["M_TOMAIL"];
				$to[0]["name"]=$_PARAMETROS["M_TONAME"];

				$Asunto=sprintf($Email[1][6]['title'],$idREC);
				$html_cont=sprintf($Email[1][6]['body'],$result["empresa"],$mensaje_asunto);
				$rtamail=send_email_srv($_PARAMETROS,$Asunto,$html_cont,$to,array(),array(),true,$Email[1][6]['alt']);
				/*******SEND EMAIL***********/

				//EMAIL 2		

				/*******SEND EMAIL***********/			
				$to=array();
				$to[0]["mail"]=$result["email"];
				$to[0]["name"]=$result["empresa"];

				$Asunto=$Email[1][7]['title'];
				$html_cont=sprintf($Email[1][7]['body'],$idREC);
				$rtamail=send_email_srv($_PARAMETROS,$Asunto,$html_cont,$to,array(),array(),true,$Email[1][7]['alt']);
				/*******SEND EMAIL***********/

			}
			catch (Exception $e){			
			}

			$mensaje[0]='txt-MSJ119-0';
			$reload=true;
			$close=true;
			
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}
	}
}

//RECUPERAR CONTRASEÑA
elseif($tp==503){	
	//
	if(($result["codrec"]=='')){
		$mensaje[0]='txt-MSJ1-0';
		$error=true;
	}

	
	if(mb_strlen($result["password"])<$minimo_pass){
		$mensaje[0]='txt-MSJ15-0';
		$error=true;
	}
	if($result["password"]!=$result["vpassword"]){
		$mensaje[0]='txt-MSJ14-0';
		$error=true;
	}
	
	if(!$error){
		try{  				
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();

			//SEND MAIL			
			$s="SELECT adm_usuarios.ID_USUARIO,
			adm_usuarios.NOMBRE_U,
			adm_usuarios.CORREO_U
			FROM adm_usuarios
			JOIN adm_usuarios_rec ON adm_usuarios_rec.ID_USUARIO=adm_usuarios.ID_USUARIO AND adm_usuarios_rec.USO=0 AND UTC_TIMESTAMP()<=DATE_ADD(FECHA,INTERVAL 2 DAY)
			WHERE CONCAT(SHA1(CONCAT(adm_usuarios.ID_USUARIO,adm_usuarios.PASSWORD_U)),MD5(CONCAT(adm_usuarios.CORREO_U,adm_usuarios.FECHA_U)),SHA1(adm_usuarios_rec.ID_REC))=:codrec";
			$reqPASS = $dbEmpresa->prepare($s); 
			$reqPASS->bindParam(':codrec', $result["codrec"]);
			$reqPASS->execute();
			if($regPASS = $reqPASS->fetch()){
				$s="UPDATE adm_usuarios
						SET PASSWORD_U=SHA1(:password)
					WHERE adm_usuarios.ID_USUARIO=:id_usuario";			
				$req = $dbEmpresa->prepare($s);
				$req ->bindParam(':id_usuario', $regPASS["ID_USUARIO"]);
				$req ->bindParam(':password', $result["password"]);
				$req ->execute();				
				$dbEmpresa->commit();

				try{
					//EMAIL 1					
					/*******SEND EMAIL***********/
					$to=array();
					$to[0]["mail"]=$regPASS["CORREO_U"];
					$to[0]["name"]=$regPASS["NOMBRE_U"];

					$Asunto=$Email[1][4]['title'];
					$html_cont=sprintf($Email[1][4]['body'],$regPASS["NOMBRE_U"]);
					$rtamail=send_email_srv($_PARAMETROS,$Asunto,$html_cont,$to,array(),array(),true,$Email[1][4]['alt']);
					/*******SEND EMAIL***********/
				}
				catch (Exception $e){			
				}
				$mensaje[0]='txt-MSJ114-0';
				$reload=true;
				$close=true;
			}
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}
	}
}
//OLVIDO SU CONTRASEÑA
elseif($tp==504){

	$captcha_verif=sprintf('https://www.google.com/recaptcha/api/siteverify?secret=%s&response=%s&remoteip=%s'
		,	$_PARAMETROS["G_SECRKEY"]
		,	$result['g-recaptcha-response']
		,	$_SERVER['REMOTE_ADDR']);
	$response=cURLdata($captcha_verif);
	if(!$response['success']){
		$error=true;
		$mensaje[0]='txt-MSJ121-0';
		$captcha=true;
	}

	if(!$error){
		$email=isset($result["email"])?$result["email"]:'';

		if(!checkmail($email)){
			$mensaje[0]='txt-MSJ10-0';
			$error=true;
		}
		if(!$error){
			$s=$sqlCons[1][0]." WHERE adm_usuarios.CORREO_U=:email LIMIT 1";
			$req = $dbEmpresa->prepare($s); 
			$req ->bindParam(':email', $email);
			$req ->execute();
			if(!$reg = $req->fetch()){
				$mensaje[0]='txt-MSJ514-0';
				$error=true;
			}
		}
	}	

	if(!$error){
		try{ 
			$_USUARIO=$reg["ID_USUARIO"];
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
				ON DUPLICATE KEY UPDATE FECHA=UTC_TIMESTAMP()";
			$reqR = $dbEmpresa->prepare($s); 
			$reqR ->execute();	
			if($idREC=="NULL") $idREC=$dbEmpresa->lastInsertId();	
			$dbEmpresa->commit();
			try{
				/*******SEND EMAIL***********/
				$code_01=sha1($reg["ID_USUARIO"].$reg["PASSWORD_U"]).md5($reg["CORREO_U"].$reg["FECHA_U"]).sha1($idREC);
				$recuperacion=sprintf("http://%s/recovery/?code=%s",$_SERVER["SERVER_NAME"],$code_01);

				$to=array();
				$to[0]["mail"]=$reg["CORREO_U"];
				$to[0]["name"]=$reg["USUARIO_COMP"];

				$Asunto=$Email[1][3]['title'];							
				$html_cont=sprintf($Email[1][3]['body'],$recuperacion);
				$rtamail=send_email_srv($_PARAMETROS,$Asunto,$html_cont,$to,array(),array(),true,$Email[1][3]['alt']);
				

				$mensaje[0]='txt-MSJ115-0';
				$reload=true;
				$close=true;
				/*******SEND EMAIL***********/
			}
			catch (Exception $e){
				$mensaje[0]='txt-MSJ9-0';
				$error=true;				
			}
			

		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();			
		}
	}
	if($error) $captcha=true;
}
elseif($tp==530){

	if(($result["email"]=='')||(($result["datos"]==1)&&($result["nombre"]==''))){
		$mensaje[0]='txt-MSJ1-0';
		$error=true;
	}
	$email=isset($result["email"])?$result["email"]:'';
	if(!checkmail($email)){
		$mensaje[0]='txt-MSJ10-0';
		$error=true;
	}

	//IDENTIFICA EL EVENTO
	if(!$error){
		$s=$sqlCons[1][2000]." WHERE s_eventos.URL_EVENTO=:evento LIMIT 1";
        $reqEv = $dbEmpresa->prepare($s);       
        $reqEv->bindParam(':evento', $result["evento"]);
        $reqEv->execute();
        if($regEv = $reqEv->fetch()){
            $evento=$regEv["ID_EVENTO"];
            $mempresa=$regEv["ID_MEMPRESA"];
            $mempresa_name=$regEv["NOMB_MEMPRESA"];
        }
		else{
			$mensaje[0]='txt-MSJ9-0';
			$error=true;

		}
	}	

	//IDENTIFICA SI ES NUEVO
	if(!$error){
		$s=$sqlCons[1][2001]." WHERE s_invitados.EMAIL_INVITADO=:email LIMIT 1";
		$reqInv = $dbEmpresa->prepare($s); 
		$reqInv ->bindParam(':email', $email);
		$reqInv ->execute();
		if($regInv = $reqInv->fetch()){
			$id=$regInv["ID_INVITADO"];
			$id_print=$regInv["ID_INVITADO"];
		}	
		else 							$id_print="NULL";
		$nuevo=$id_print=="NULL";
		if($result["datos"]==1){
			$nombre=$result["nombre"];
			$empresa=$result["empresa"];
		}
		else{
			$nombre=$regInv["NOMBRES_INVITADO"];
			$empresa=$regInv["COMPANIA_INVITADO"];
		}
	}

	if(!$error){
		try{ 
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();			
				
			if($nuevo) 	$rnd=uniqid();
			else 		$rnd='';
			$s="INSERT INTO s_invitados
				(ID_INVITADO,
					NOMBRES_INVITADO,RNDKEY_INVITADO,
					EMAIL_INVITADO,COMPANIA_INVITADO,FECHAS_INVITADO,ID_MEMPRESA,ID_USUARIO)
			VALUES($id_print,:nombre,SHA1(:rnd),:email,:empresa,UTC_TIMESTAMP(),:mempresa,1)
			ON DUPLICATE KEY UPDATE
				NOMBRES_INVITADO=:nombre,				
				EMAIL_INVITADO=:email,
				COMPANIA_INVITADO=:empresa,
				DATOS_INVITADO=0
			";

			$Eqreq = $dbEmpresa->prepare($s); 			
			$Eqreq->bindParam(':nombre', $nombre);
			$Eqreq->bindParam(':rnd', $rnd);
			$Eqreq->bindParam(':email', $result["email"]);
			$Eqreq->bindParam(':empresa', $empresa);
			$Eqreq->bindParam(':mempresa', $mempresa);
			$Eqreq->execute();
			if($nuevo) $id=$dbEmpresa->lastInsertId();	
			$id_sha=encrip($id);			

			//VERIFICA el EVENTO
			if(!$nuevo){
				$s="SELECT ID_INVITACION 
				FROM s_invitados_invitacion
				WHERE ID_INVITADO=:id AND ID_EVENTO=:evento LIMIT 1";
				$req = $dbEmpresa->prepare($s); 			
				$req->bindParam(':id', $id);
				$req->bindParam(':evento', $evento);
				$req->execute();
				if($reg = $req->fetch()) 	$id_invitacion=$reg["ID_INVITACION"];
				else 						$id_invitacion=0;
			}
			else $id_invitacion=0;

			if($id_invitacion==0){
				$unique=uniqid();
				$s="INSERT INTO s_invitados_invitacion (ID_INVITADO,ID_EVENTO,ID_USUARIO,INVITACION_UNIQ)
					VALUES(:id,:evento,1,:unique)";
				$req = $dbEmpresa->prepare($s); 			
				$req->bindParam(':id', $id);
				$req->bindParam(':evento', $evento);
				$req->bindParam(':unique', $unique);
				$req->execute();
				$id_invitacion=$dbEmpresa->lastInsertId();
			}
			else{
				$s="UPDATE s_invitados_invitacion_estado
					SET LAST_ESTADOINV=0
					WHERE LAST_ESTADOINV=1 AND ID_INVITACION=:id_invitacion";
				$req = $dbEmpresa->prepare($s); 			
				$req->bindParam(':id_invitacion', $id_invitacion);
				$req->execute();
			}
			$s="INSERT INTO s_invitados_invitacion_estado (ID_INVITACION,ID_INVESTADO,FECHA_ESTADOINV,LAST_ESTADOINV)
					VALUES(:id_invitacion,:estado,UTC_TIMESTAMP(),1)";
			$req = $dbEmpresa->prepare($s); 			
			$req->bindParam(':id_invitacion', $id_invitacion);
			$req->bindParam(':estado', $result["btn"]);
			$req->execute();
			//ASIGNA EL ESTADO				
			$dbEmpresa->commit();
			try{
	
				$s=$sqlCons[1][2008]." WHERE s_invitados_invitacion.ID_INVITACION=:id_invitacion LIMIT 1";
				$reqE = $dbEmpresa->prepare($s);
				$reqE->bindParam(':id_invitacion', $id_invitacion);
				$reqE->bindParam(':_IDIOMA', $_IDIOMA);
				$reqE->execute();	
				$regE = $reqE->fetch();
				if($regE["TIPO_INVESTADO"]==1){
					$fechai=$regE["FECHAI_EVENTONF"];
					$fechaf=$regE["FECHAF_EVENTONF"];

					$iCal.="BEGIN:VCALENDAR\n";
					$iCal.="PRODID:-//Microsoft Corporation//Outlook 12.0 MIMEDIR//EN\n";
					$iCal.="VERSION:2.0\n";
					$iCal.="METHOD:PUBLISH\n";
					$iCal.="X-MS-OLK-FORCEINSPECTOROPEN:TRUE\n";
					$iCal.="BEGIN:VEVENT\n";
					$iCal.="CLASS:PUBLIC\n";
					$iCal.="CREATED:".date('Ymd\THis\Z', time())."\n";
					$iCal.="DESCRIPTION:".escapeString(strip_tags(html_entity_decode($regE["CONT_EVENTO"])))."\n";
					$iCal.="DTEND:".dateToCal($fechaf,$_TZ)."\n";
					$iCal.="DTSTAMP:".dateToCal($fechai,$_TZ)."\n";
					$iCal.="DTSTART:".dateToCal($fechai,$_TZ)."\n";
					$iCal.="LAST-MODIFIED:".date('Ymd\THis\Z', time())."\n";
					$iCal.="LOCATION:".escapeString($regE["UBICAC_EVENTO"])."\n";
					$iCal.="PRIORITY:5\n";
					$iCal.="SEQUENCE:0\n";
					$iCal.="SUMMARY;LANGUAGE=en-us\n";
					$iCal.="TRANSP:OPAQUE\n";
					$iCal.="UID:".$regE["EVENTO_UNQ"]."\n";
					$iCal.="URL;VALUE=URI:".escapeString('http://'.$_SERVER["HTTP_HOST"])."\n";
					$iCal.="SUMMARY:".escapeString($regE["TITULO_EVENTO"])."\n";
					$iCal.="X-MICROSOFT-CDO-BUSYSTATUS:BUSY\n";
					$iCal.="X-MICROSOFT-CDO-IMPORTANCE:1\n";
					$iCal.="X-MICROSOFT-DISALLOW-COUNTER:FALSE\n";
					$iCal.="X-MS-OLK-ALLOWEXTERNCHECK:TRUE\n";
					$iCal.="X-MS-OLK-AUTOFILLLOCATION:FALSE\n";
					$iCal.="X-MS-OLK-CONFTYPE:0\n";
					//Here is to set the reminder for the event.
					
					$iCal.="BEGIN:VALARM\n";
					$iCal.="TRIGGER:-PT120M\n";
					$iCal.="ACTION:DISPLAY\n";
					$iCal.="DESCRIPTION:Reminder\n";
					$iCal.="END:VALARM\n";
					$iCal.="END:VEVENT\n";
					$iCal.="END:VCALENDAR\n";
					
					$archivo=sprintf("/%s/%s-%s-%s.ics",$dir_rel[3],$mempresa,$_PROYECTO,$id_invitacion);
					$fp = fopen($archivo, 'w');
					fwrite($fp, $iCal);
					fclose($fp);
					unset($fp);
				}
				$texto_add=$regE["TIPO_INVESTADO"]==3?sprintf('<strong>%s:</strong> %s',$_textos[1359][0],$_textos[1359][1]):'';
				if($result["NuevaAsistencia"]==1){
					$AltBody=$Email[1][451]['alt'];
					$Asunto=sprintf($Email[1][451]['title'],$regE["NOMB_MEMPRESA"]);
					$html_cont=sprintf($Email[1][451]['body'],$regE["NOMBRES_INVITADO"],$regE["TITULO_EVENTO"],$texto_add);	
					
				}
				else{	
					$AltBody=$Email[1][450]['alt'];
					$Asunto=sprintf($Email[1][450]['title'],$regE["NOMB_MEMPRESA"]);					
					$html_cont=sprintf($Email[1][450]['body'],$regE["NOMBRES_INVITADO"],$regE["TITULO_EVENTO"],$regE["ESTADO_INVITACION"],$texto_add);
				}


				/*******SEND EMAIL***********/
				$to=array();
				$to[0]["mail"]=$regE["EMAIL_INVITADO"];
				$to[0]["name"]=$regE["NOMBRES_INVITADO"];	

				if($regE["TIPO_INVESTADO"]==1){
					$attachments[0][0]=$archivo;
					$attachments[0][1]=$regE["URL_EVENTO"].".ics";

					$rtamail=send_email_srv($_PARAMETROS,$Asunto,$html_cont,$to,$cc,$cco,true,$AltBody,$attachments);
					//$rtamail=send_email_srv($_PARAMETROS,$Asunto,$html_cont,$to,$cc,$cco,true,'',$attachments);
				}
				else{
					$rtamail=send_email_srv($_PARAMETROS,$Asunto,$html_cont,$to,$cc,$cco,true,$AltBody);
				}
				//$salidas["parAd"]["md_go"]=sprintf("http://app.%s",$_PARAMETROS["MT_DOMAIN"]);

				/*******SEND EMAIL***********/
			}
			catch (Exception $e){
			}

			$mensaje[0]='txt-MSJ171-0';
			$close=true;
			$reload=true;

		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();			
		}
	}
}
$salidas["status"]["captcha"]=$captcha;
$salidas["status"]["error"]=$error;
$salidas["status"]["badfields"]=$badfields;
$salidas["status"]["reload"]=$reload;
$salidas["status"]["close"]=$close;
$salidas["status"]["words"]=$words;
$salidas["status"]["rewrite"]=$rewrite;
$salidas["status"]["go"]=$go;
$salidas["node"]=$node;
$salidas["hide"]=$hide;
$salidas["localstorage"]=$localstorage;
$salidas["remove"]=$remove;
$salidas["mensaje"]=$mensaje;
$salidas["message_esp"]=$message_esp;
$salidas["_user"]=$_sysvars;
$salidas["auto"]=$auto;
$salidas["request"]=$request;
echo json_encode($salidas);
?>
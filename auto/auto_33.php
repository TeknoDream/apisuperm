<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE);

$_PROYECTO=33;
$_EMPRESA=29;
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

/******************************/
/******************************/
/******************************/
/******************************/

foreach ($argv as $key => $value) {
	$val_s=explode("=", $value);
	$results[$val_s[0]]=$val_s[1];
}
$tp=$results["tp"];
$salidas["rta_mail"]=[];
$salidas["return"]=[];
if($tp==1){  // AJUSTAR APERTURAS


	$s="https://graph.facebook.com/oauth/access_token?client_id=".$_PARAMETROS["FB_APPID"]."&client_secret=".$_PARAMETROS["FB_APPSECRET"]."&grant_type=client_credentials";
	$access_token = file_get_contents($s);
	$token=substr($access_token,strlen("access_token="),strlen($access_token)-strlen("access_token="));


	$s="SELECT 
	    z_mensajes.ID_MSG,
	    z_mensajes.ID_USUARIO,
	    z_mensajes.PUB_MSG,
	    z_mensajes.URLV_MSG,
	    z_mensajes.TXT_MSG,
	    DATE_FORMAT(CONVERT_TZ(z_mensajes.FECHAS_MSG,'+00:00','$_TZ'),'%d/%m/%Y') AS FECHAS_MSG,
	    DATE_FORMAT(z_mensajes.FECHAR_MSG,'%d/%m/%Y') AS FECHAR_MSG,
	    DATEDIFF(CONVERT_TZ(UTC_TIMESTAMP(),'+00:00','$_TZ'),z_mensajes.FECHAS_MSG) AS DIFETIMER,

	    adm_usuarios_facebook_r.ID_FACEBOOK AS ID_FB_R,
	    adm_usuarios_facebook_s.ID_FACEBOOK AS ID_FB_S,

	    z_mensajes.TPRIV_MSG,
	    z_mensajes.HAB_MSG,

		adm_usuarios_s.ID_USUARIO AS ID_USUARIO_S,
		adm_usuarios_s.NOMBRE_U AS NOMBRE_U_S,
		adm_usuarios_s.APELLIDO_U AS APELLIDO_U_S,
	    
		adm_usuarios_r.ID_USUARIO AS ID_USUARIO_R,
		adm_usuarios_r.NOMBRE_U AS NOMBRE_U_R,
		adm_usuarios_r.APELLIDO_U AS APELLIDO_U_R,
		adm_usuarios_r.CORREO_U AS CORREO_U_R


	    
	FROM z_mensajes_fbusers
	LEFT JOIN adm_usuarios adm_usuarios_r ON adm_usuarios_r.ID_USUARIO=z_mensajes_fbusers.ID_USUARIO
	LEFT JOIN adm_usuarios_facebook adm_usuarios_facebook_r ON adm_usuarios_facebook_r.ID_USUARIO=adm_usuarios_r.ID_USUARIO
	LEFT JOIN z_mensajes ON z_mensajes.ID_MSG=z_mensajes_fbusers.ID_MSG
	LEFT JOIN adm_usuarios adm_usuarios_s ON adm_usuarios_s.ID_USUARIO=z_mensajes.ID_USUARIO
	LEFT JOIN adm_usuarios_facebook adm_usuarios_facebook_s ON adm_usuarios_facebook_s.ID_USUARIO=adm_usuarios_s.ID_USUARIO
	WHERE DATE(CONVERT_TZ(UTC_TIMESTAMP(),'+00:00','$_TZ'))=z_mensajes.FECHAR_MSG AND NOT ISNULL(adm_usuarios_r.ID_USUARIO)";
	$req = $dbEmpresa->prepare($s); 
	$req->execute();    
	while($reg = $req->fetch()){
		if($reg["CORREO_U_R"]!=''){
			$to[0]["name"]=$reg["NOMBRE_U_R"].' '.$reg["APELLIDO_U_R"];	
			$to[0]["mail"]=$reg["CORREO_U_R"];

			$Alt=$Email[1][550]['alt'];
			$Asunto=$Email[1][550]['title'];

			if($reg["ID_USUARIO_S"]==$reg["ID_USUARIO_R"]){
				$html_cont=sprintf($Email[2][550]['body']
						,	$reg["NOMBRE_U_R"]
						,	$reg["DIFETIMER"]
						,	$reg["FECHAS_MSG"]
						,	$reg["ID_MSG"]);
				$notif=sprintf('Hola @[%s] tienes una Time Kapsul lista para ser abierta',$reg["ID_FB_R"]);	
			}
			else{
				$html_cont=sprintf($Email[1][550]['body']
						,	$reg["NOMBRE_U_R"]
						,	$reg["DIFETIMER"]
						,	$reg["FECHAS_MSG"]
						,	$reg["NOMBRE_U_S"]
						,	$reg["APELLIDO_U_S"]
						,	$reg["ID_MSG"]);	
				$notif=sprintf('Hola @[%s] tienes una Time Kapsul enviada por @[%s] lista para ser abierta',$reg["ID_FB_R"],$reg["ID_FB_S"]);
			}		
			$rtamail=send_email_srv($_PARAMETROS,$Asunto,$html_cont,$to,array(),array(),true,$Alt);
			$salidas["rta_mail"][]=$rtamail.' '.$to[0]["mail"];
		}


		$url="https://graph.facebook.com/v2.5/".$reg["ID_FB_R"]."/notifications";
		$opt=array('access_token'	=>	$token
			,	'href'				=>	'mensajes-recibidos/'.$reg["ID_MSG"]
			,	'template'			=>	$notif);
		$data_string=http_build_query($opt);

		ob_start();
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                           
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');                                                               
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
		curl_setopt($ch, CURLOPT_REFERER, $_SERVER["HTTP_HOST"]);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		$rest = curl_exec($ch);
		curl_close($ch);
		ob_end_clean();
		$salidas["return"][]=json_decode($rest);
	}
}
//print_r($salidas);
?>
<?php
require_once 	"../phplib/funciones.php";
require_once 	"../phplib/consultas.php";
require_once 	"../phplib/plantilla/cuerpomail.php";
require_once 	"../phplib/mail/PHPMailer/class.phpmailer.php";
require_once 	"../phplib/mail/sendmail_new.php";


/*
UPDATE bd.adm_api
SET KEY_API=SHA1(MD5(MD5(SHA1(UTC_TIMESTAMP())))),
DATE_API=UTC_TIMESTAMP();

SELECT CONCAT(adm_api.KEY_API,'-',TIME_TO_SEC(adm_api.DATE_API)) AS KEY_A FROM adm_api;

REPLACE INTO bd.adm_api_approve(ID_API,ID_CONSULTA,ACCESS)(SELECT adm_api.ID_API,adm_api_consultas.ID_CONSULTA,1 FROM adm_api,adm_api_consultas);

SELECT adm_api_consultas.ID_CONSULTA
,	adm_api_consultas.NOMB_CONSULTA
,	adm_api_consultas.TIPO
FROM adm_api_consultas;

*/

//PARAMETROS OBLIGATORIOS
$headers[200]=$_SERVER["SERVER_PROTOCOL"]." 200 OK";
$headers[201]=$_SERVER["SERVER_PROTOCOL"]." 201 Created";
$headers[304]=$_SERVER["SERVER_PROTOCOL"]." 304 Not Modified";
$headers[400]=$_SERVER["SERVER_PROTOCOL"]." 400 Bad Request";
$headers[401]=$_SERVER["SERVER_PROTOCOL"]." 401 Unauthorized";
$headers[403]=$_SERVER["SERVER_PROTOCOL"]." 403 Forbidden";
$headers[404]=$_SERVER["SERVER_PROTOCOL"]." 404 Not Found";
$headers[422]=$_SERVER["SERVER_PROTOCOL"]." 422 Unprocessable Entity";
$headers[500]=$_SERVER["SERVER_PROTOCOL"]." 500 Internal Server Error";
$header=200;
$error=0;
$API_CONF=false;
$_sysvars=array();
$salidas=array();



$state=ConectarseAPI($dbEmpresa,$subdominio,$_sysvars);
if(!$state){
	$header=500;
	header($headers[$header], true, $header);
	exit(0);
}
$redirect_uri=isset($_REQUEST["redirect_uri"])?$_REQUEST["redirect_uri"]:'';
$test=isset($_REQUEST["test"])?$_REQUEST["test"]:0;
$tp=isset($_REQUEST["tp"])?$_REQUEST["tp"]:0;

if($_REQUEST["apikey"]=='')
	$_REQUEST["apikey"]=$_REQUEST["api_key"];

$API_Key=isset($_REQUEST["apikey"])?$_REQUEST["apikey"]:'';
$lang=isset($_REQUEST["lang"])?$_REQUEST["lang"]:$_sysvars["lang"];
$_user=isset($_REQUEST["_user"])?$_REQUEST["_user"]:0;
if($API_Key=="") 	$header=401;
if($tp=="") 		$header=400;
$lang=strtolower($lang);
function RealDom(){
	$dominio=$_SERVER["HTTP_REFERER"];
	$ipremote=$_SERVER["REMOTE_ADDR"];
	if(isset($_SERVER["HTTP_REFERER"])){
		preg_match('@^(?:(http|https)://)?([^/]+)@i', $dominio, $rta);
		$host = $rta[2];
		preg_match('/[^.]+\.[^.]+$/', $host, $rta);
		$realdom=$rta[0];
	}
	else{
		$realdom=$ipremote;
	}
	return $realdom;
	
}

if($header==200){
	////////////////////////////////
	///////VARIABLES DE SESION//////
	////////////////////////////////

	/*VERIFICACION DE DOMINIO*/
	$_sysvars["client"]=isset($_REQUEST["client"])?$_REQUEST["client"]:""; //ID DE ID_MEMPRESA
	$_sysvars["_ip"]=isset($_REQUEST["_ip"])?$_REQUEST["_ip"]:$_SERVER["REMOTE_ADDR"]; //IP DEL CLIENTE
	$_sysvars["_host"]=isset($_REQUEST["_host"])?$_REQUEST["_host"]:''; //IP DEL CLIENTE

	/*SE USA PARA LA SESION PREVIAMENTE INICIADA*/
	$_sysvars["_token_a"]=$_REQUEST["_token_a"];
	$_sysvars["_token_b"]=$_REQUEST["_token_b"];

	/*SE USA PARA LA SESION PREVIAMENTE INICIADA*/
	$_sysvars["_link_a"]=$_REQUEST["_link_a"];
	$_sysvars["_link_b"]=$_REQUEST["_link_b"];

	/*PHONE KEY*/
	$_sysvars["_key"]=$_REQUEST["_key"];
	$_sysvars["_phone"]=$_REQUEST["_phone"];
	$_sysvars["tp"]=$tp;
	
	/*SE USA PARA INICIAR SESION DE FORMA AUTOMATICA*/
	$_sysvars["_ualias"]=$_REQUEST["_ualias"]; //USUARIO
	$_sysvars["_upassw"]=$_REQUEST["_upassw"]; //PASSWORD
	$_sysvars["_memory"]=$_REQUEST["_memory"]; //RECORDAR SESSION	
	$_sysvars["_session"]=$_REQUEST["_session"]; //SESION ID DEL CLIENTE
	/*print_r($_sysvars);*/
	////////////////////////////////
	////////////////////////////////
	////////////////////////////////
	$_sysvars_r=verif_sp($_sysvars,$dbEmpresa);

	
	/*************************/
	$verificar=$_sysvars_r["return"];
	$_VCarga=false;	
	include 		"../phplib/variables_se.php";
	Consultas($sqlCons,$sqlOrder,$_PROYECTO,$_CLIENTE,$_IDIOMA,$_TZ,$_GCLIENTE,$_USUARIO,$_GRUPO,$_PARAMETROS);
	$MaxItems=$NMaxItems[4];

	
	/**********************/
	/**********************/
	/*********LOGS*********/
	/**********************/
	/**********************/
	
	try{  	
		require "../phplib/mysql_valores.php";			
		$dbMat->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
		$dbMat->beginTransaction();

		$TO_SEND=array('req'=>$_REQUEST, 'files'=>$_FILES);

		$s="INSERT INTO log_api (
				FECHA_LOG
			,	HOST_LOG
			,	REMOTE_LOG
			,	API_KEY
			,	REAL_DOM
			,	REQUEST_METHOD
			,	REQUEST
			,	REQUEST_JSON
			,	TP) 
		VALUE(
				UTC_TIMESTAMP()
			,	:host
			,	:remote
			,	:API_Key
			,	:realdom
			,	:REQUEST_METHOD
			,	:REQUEST
			,	:REQUEST_JSON
			,	:tp)";
		$req = $dbMat->prepare($s);
		$req->bindValue(":host", $_SERVER["HTTP_HOST"]);
		$req->bindValue(":remote", $_SERVER["REMOTE_ADDR"]);
		$req->bindValue(":API_Key", $API_Key);
		$req->bindValue(":realdom",$_SERVER["HTTP_REFERER"]);
		$req->bindValue(":REQUEST_METHOD",$_SERVER['REQUEST_METHOD']);
		$req->bindValue(":REQUEST",http_build_query($TO_SEND));
		$req->bindValue(":REQUEST_JSON",json_encode($TO_SEND));
		$req->bindValue(":tp",$_REQUEST['tp']);
		$req->execute();
		$idlog=$dbMat->lastInsertId();


		$dbMat->commit();
	}
	catch (Exception $e){
		$dbMat->rollBack();
	}	
	
	/***************************/
	/***************************/
	/**********SUB-DOM**********/
	/***************************/
	/***************************/	
	$cPrint["referer"]=$_SERVER['HTTP_REFERER'];
	$cPrint["request_method"]=$_SERVER['REQUEST_METHOD'];
	$cPrint["user_agent"]=$_SERVER['HTTP_USER_AGENT'];
	$cPrint["request"]["get"]=$_GET;
	$cPrint["request"]["post"]=$_POST;
	$cPrint["header"]=$headers[$header];
	$cPrint["project"]=$_sysvars_r["project"];	
	$cPrint["company"]=$_sysvars_r["company"];	
	$cPrint["_sysvars"]=$_sysvars;
	$cPrint["log_user"]=$verificar?"Y":"N";	
	;
	/*CONEXION*/
	$dominio=$_SERVER["HTTP_REFERER"];
	/**/
	$cPrint["realdom"]=RealDom();
	//VERIFICACIÓN DE SEGURIDAD DE ACCESO DE API
	$s=$sqlCons[1][74]." WHERE CONCAT(adm_api.KEY_API,'-',TIME_TO_SEC(adm_api.DATE_API))=:key ".
			" AND adm_api.DOMAIN_API IN(:domain,'*') ".
			" LIMIT 1";
	$req = $dbEmpresa->prepare($s);
	$req->bindParam(':key', $API_Key);
	$req->bindParam(':domain', $realdom);
	$req->execute();
	$API_CONF=($reg=$req->fetch());
}

//EL KEY FUE VALIDADO
if($API_CONF){
	$apiid=$reg["ID_API"];
	$empresa_api=$reg["ID_MEMPRESA"];
	$s=$sqlCons[1][80].
	" JOIN adm_api_approve ON adm_api_approve.ID_CONSULTA=adm_api_consultas.ID_CONSULTA AND adm_api_approve.ID_API=:apiid ".
	" WHERE adm_api_consultas.ID_CONSULTA=:tp AND adm_api_approve.ACCESS<>0 AND adm_api_consultas.TIPO=:tipo LIMIT 1";
	$reqTP = $dbEmpresa->prepare($s);
	$reqTP->bindParam(':apiid', $apiid);
	$reqTP->bindParam(':tipo', $_SERVER['REQUEST_METHOD']);
	$reqTP->bindParam(':tp', $tp);
	$reqTP->execute();
	
	$TP_OK=($regTP=$reqTP->fetch());
	if($TP_OK){

		if($regTP["VERIF_CONSULTA"]==1&&!$verificar) 					$header=401;
		elseif($regTP["VERIF_CONSULTA"]==0&&$verificar) 				$header=400;
		elseif($regTP["EMPRESA_CONSULTA"]==1&&$empresa_api!=$_CLIENTE) 	$header=401;
		elseif($regTP["GRUPO_CONSULTA"]==1){
			$s="SELECT adm_grupos_ven.ID_VENTANA
				FROM adm_grupos_ven
				INNER JOIN adm_grupos ON adm_grupos.ID_MEMPRESA=:_CLIENTE AND adm_grupos.ID_GRUPO=adm_grupos_ven.ID_GRUPO
				INNER JOIN adm_usuarios_empresa ON adm_usuarios_empresa.ID_USUARIO=:_USUARIO AND adm_usuarios_empresa.ID_MEMPRESA=:_CLIENTE AND adm_usuarios_empresa.ID_GRUPO=adm_grupos.ID_GRUPO
				WHERE adm_grupos_ven.ID_VENTANA IN (SELECT adm_api_consultas_ven.ID_VENTANA FROM adm_api_consultas_ven WHERE adm_api_consultas_ven.ID_CONSULTA=:tp)
					AND adm_grupos_ven.PERMISO_GRUPOVEN=1";

			$reqTPG = $dbEmpresa->prepare($s);
			$reqTPG->bindParam(':tp', $tp);
			$reqTPG->bindParam(':_USUARIO', $_USUARIO);
			$reqTPG->bindParam(':_CLIENTE', $_CLIENTE);
			$reqTPG->execute();
			$header=($regTPG=$reqTPG->fetch())?$header:400;		
		}
	}
	else
		$header=400;
}	
else
	$header=400;

if($lang!=""){
	if($lang!=""){
		$lang_explode=explode("-",$lang);
		if(count($lang_explode)>0) 	$slang=$lang_explode[0];
		else 						$slang=$lang;
	}
	else $slang=$lang;
	//DEFINIR IDIOMA
	$s=$sqlCons[1][76]." ORDER BY (fac_idioma.NAV01=:slang OR fac_idioma.NAV01=:lang) DESC,fac_idioma.DEFAULT DESC LIMIT 1";
	$reqLang = $dbEmpresa->prepare($s);
	$reqLang->bindParam(':slang', $slang);
	$reqLang->bindParam(':lang', $lang);
	$reqLang->execute();
	$regLang = $reqLang->fetch();
	$Idioma=$regLang["ID_IDIOMA"];
	$_IDIOMA=$Idioma;
}
elseif($verificar && $_sysvars["langn"]!=''){
	$Idioma=$_sysvars["langn"];
}
else $Idioma=1;
$cPrint["language"]=$Idioma;
//INICIO DE API

if($header==200){
	
}

if($header==200){

	include("api_000.php");
	if($_PROYECTO==1)		include("api_001.php");
	elseif($_PROYECTO==10)	include("api_010.php");
	elseif($_PROYECTO==13)	include("api_013.php");
	elseif($_PROYECTO==14)	include("api_014.php");	
	elseif($_PROYECTO==15)	include("api_015.php");	
	elseif($_PROYECTO==16)	include("api_016.php");	
	elseif($_PROYECTO==18)	include("api_018.php");
	elseif($_PROYECTO==19)	include("api_019.php");		
	elseif($_PROYECTO==20)	include("api_020.php");
	elseif($_PROYECTO==21)	include("api_021.php");
	elseif($_PROYECTO==23)	include("api_023.php");
	elseif($_PROYECTO==24)	include("api_024.php");
	elseif($_PROYECTO==25)	include("api_025.php");
	elseif($_PROYECTO==26)	include("api_026.php");
	elseif($_PROYECTO==27)	include("api_027.php");
	elseif($_PROYECTO==28)	include("api_028.php");
	elseif($_PROYECTO==29)	include("api_029.php");
	elseif($_PROYECTO==30)	include("api_030.php");
	elseif($_PROYECTO==31)	include("api_031.php");
	elseif($_PROYECTO==32)	include("api_032.php");// CISA
	elseif($_PROYECTO==33)	include("api_033.php");
	elseif($_PROYECTO==36)	include("api_036.php");

}

if(isset($e))	$error=ErrMSG($e,$_REQUEST);

if($error!=0){
	if(isset($e)) $error='LOG'.$error;
	$s="INSERT INTO log_api_err	(ID_LOG,DATA_ERR)
		VALUES (:idlog,:error)";
	$req = $dbMat->prepare($s);
	$req->bindValue(":idlog", $idlog);
	$req->bindValue(":error", $error);
	$req->execute();
	$salidas["err"]=$error;	
}

/*
$salidas["status"] = $error!=0?0:1;
$salidas["message"] = "Error haciendo mla bla bla"
$salidas["error"] = $error;
*/

unset($salidas["required_fields"]);
unset($salidas["optional_fields"]);


if($_user==1) 	$salidas["_user"]=$_sysvars;		

if($_REQUEST['__rlog']==1) 		$salidas["__log"]=$idlog;
if($_REQUEST['__rrequest']==1) 	$salidas["__request"]=$TO_SEND;



header('Access-Control-Allow-Origin: *');  
header('Access-Control-Allow-Methods: sGET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');
/*
header("Access-Control-Allow-Orgin: *");
header("Access-Control-Allow-Methods: *");
*/
header('Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' ); 
header('Cache-Control: post-check=0, pre-check=0', false ); 
header('Accept-Charset: utf-8');
header('Expires: Mon, 17 May 1984 02:00:00 GMT');
header('Content-type: application/json');

if(isset($_GET['callback']))	header($headers[200], true, 200);
else 							header($headers[$header], true, $header);
 							

if($redirect_uri!='') header("Location: ".$redirect_uri);
if(isset($_GET['callback']))	echo $_GET['callback'].'('.json_encode($salidas).')';
else 							echo json_encode($salidas);
?>

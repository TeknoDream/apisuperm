<?php 
require_once("json_Item.php");
require_once("class.upload.php");
require_once("swhere.php");

//
$acc01=encrip('1',2);
$acc02=encrip('2',2);
$acc03=encrip('3',2);
$acc04=encrip('4',2);
$acc05=encrip('5',2);
$acc06=encrip('6',2);
$acc07=encrip('7',2);
$acc08=encrip('8',2);
$acc09=encrip('9',2);
//

/********************************************/
/********************************************/
/********************************************/
/********************************************/
function ArbolRecursive($idPadre,$idTreePadre,$Matris,$Habilitador,&$Variable){
	foreach ($Matris as $i => $val){
		if($Matris[$i]==$idPadre){
			if($Habilitador[$i]!=1){
				$Variable[$idTreePadre][]=$i;
				ArbolRecursive($i,$idTreePadre,$Matris,$Habilitador,$Variable);
			}
		}
	}
}




function fValid($tipo,$_files_clase){	
	if(in_array($tipo,$_files_clase))			$m=true;
	else										$m=false;
	return $m;
}

/**/
function encrip_mysql($palabra,$tipo=1){
	if($tipo==1)	return ("SHA1(CONCAT('".$_SESSION["TIEMPO"]."',$palabra))");
	else 			return ("MD5(CONCAT('".$_SESSION["TIEMPO"]."',$palabra))");
}
function encrip($palabra,$tipo=1){
	if($tipo==1)	return sha1($_SESSION["TIEMPO"].$palabra); //40
	else 			return md5($_SESSION["TIEMPO"].$palabra); //32
}
function nuevo_item($tipo=1){
	if($tipo==1)	return(sha1($_SESSION["TIEMPO"].'Nuevo'));
	else 			return(md5($_SESSION["TIEMPO"].'Nuevo'));
}
/******//******//******/
function clave($seed){	
	mt_srand(mk_seed());
	$randval = mt_rand();
	return(sha1($seed*$seed));
}
function mk_seed(){
  list($usec, $sec) = explode(' ', microtime());
  return (float) $sec + ((float) $usec * 100000);
}
/******//******//******/
function preparar($s){
	return($s);
}
function imprimir($strText,$opt=1){
	if($opt==1){
		$strText=nl2br(htmlentities(stripslashes($strText),ENT_COMPAT,'UTF-8'));
	}
	elseif($opt==2){
	}
	elseif($opt==3){
		$strText=htmlentities($strText,ENT_COMPAT,'UTF-8');
	}	
	return($strText);	
}

function GenralLog($log){
	$dbEmpresa=$GLOBALS['dbEmpresa'];
	$_USUARIO=$GLOBALS['_USUARIO'];
	$cnf=$GLOBALS['cnf'];
	$det_plus=$GLOBALS['det_plus'];
	$id=$GLOBALS['id'];
	$log_str=json_encode($log);

	$s='INSERT INTO adm_general_log
			(ID_USUARIO
		,	FECHAS_LOG
		,	R_ID_LOG
		,	R_MOD_LOG
		,	R_DET_LOG
		,	DET_LOG)
		VALUES
			(:_USUARIO
		,	UTC_TIMESTAMP()
		,	:id
		,	:cnf
		,	:det_plus
		,	:log_str)';
	$reqSD = $dbEmpresa->prepare($s);
	$reqSD->bindParam(':_USUARIO', $_USUARIO); 
	$reqSD->bindParam(':id', $id); 
	$reqSD->bindParam(':cnf', $cnf); 
	$reqSD->bindParam(':det_plus', $det_plus); 
	$reqSD->bindParam(':log_str', $log_str); 	
	$reqSD->execute();
}


function ConectarseAPI(&$dbEmpresa,&$subdominio='',&$_sysvars=array()){
	include "mysql_valores.php";
	$subdominio=SubDominio();
	$s = "SELECT a_empresa_srv.SRVN_EMPRESA,
	a_empresa_srv.USN_EMPRESA,
	a_empresa_srv.BD_EMPRESA,
	a_empresa_srv.PSW_EMPRESA,
	a_empresa_srv.ID_EMPRESA,
	a_empresa_srv.ID_PRODUCTO,
	a_producto.NOMB_PRODUCTO,
	a_empresas.USN_EMPRESA AS NAME_EMPRESA,
	a_empresa_srv_url.URL
	FROM a_empresa_srv_url 
	LEFT JOIN a_empresa_srv ON a_empresa_srv.ACTIVA=1 AND a_empresa_srv.ID_EMPRESA=a_empresa_srv_url.ID_EMPRESA AND a_empresa_srv.ID_PRODUCTO=a_empresa_srv_url.ID_PRODUCTO
	LEFT JOIN a_empresas ON a_empresas.HAB_EMPRESA=0 AND a_empresas.ID_EMPRESA=a_empresa_srv.ID_EMPRESA  
	LEFT JOIN a_producto ON a_producto.HAB_PRODUCTO=0 AND a_producto.ID_PRODUCTO=a_empresa_srv.ID_PRODUCTO 
	WHERE  a_empresas.USN_EMPRESA=:subdom OR a_empresa_srv_url.URL=:subdom LIMIT 1";

	
	$reqSD = $dbMat->prepare($s);
	$reqSD->bindParam(':subdom', $subdominio); 
	$reqSD->execute();
	if($regSD = $reqSD->fetch()){
		try{
			$_sysvars["project"]=$regSD["ID_PRODUCTO"];
			$_sysvars["company"]=$regSD["ID_EMPRESA"];

			$dbEmpresa = new PDO('mysql:host='.$regSD["SRVN_EMPRESA"].';dbname='.$regSD["BD_EMPRESA"],$regSD["USN_EMPRESA"],$regSD["PSW_EMPRESA"]);	
			
			return true;
		}			
		catch (PDOException $e){	
			print_r($e);
			return false;
		}
	}
	else return false;
}
function ConectarseAUTO(&$dbEmpresa,&$_sysvars=array()){
	include "mysql_valores.php";

	$s = "SELECT 
	a_empresa_srv.SRVN_EMPRESA,
	a_empresa_srv.USN_EMPRESA,
	a_empresa_srv.BD_EMPRESA,
	a_empresa_srv.PSW_EMPRESA,
	a_empresa_srv.ID_EMPRESA,
	a_empresa_srv.ID_PRODUCTO
	FROM a_empresa_srv 
	LEFT JOIN a_empresas ON a_empresas.HAB_EMPRESA=0 AND a_empresas.ID_EMPRESA=a_empresa_srv.ID_EMPRESA  
	LEFT JOIN a_producto ON a_producto.HAB_PRODUCTO=0 AND a_producto.ID_PRODUCTO=a_empresa_srv.ID_PRODUCTO 
	WHERE  a_empresa_srv.ID_EMPRESA=:company OR a_empresa_srv.ID_PRODUCTO=:project LIMIT 1";	
	$reqSD = $dbMat->prepare($s);
	$reqSD->bindParam(':project', $_sysvars["project"]); 
	$reqSD->bindParam(':company', $_sysvars["company"]); 
	$reqSD->execute();
	if($regSD = $reqSD->fetch()){
		try{
			$dbEmpresa = new PDO('mysql:host='.$regSD["SRVN_EMPRESA"].';dbname='.$regSD["BD_EMPRESA"],$regSD["USN_EMPRESA"],$regSD["PSW_EMPRESA"]);	
			return true;
		}			
		catch (PDOException $e){	
			return false;
		}
	}
	else return false;
}
function DescCookie($tp=1){
	try{	
		$dominio_activo=$_SERVER['HTTP_HOST'];	
		if($tp==1){					
			setcookie("_token_a",false,time() - 3600,'/',$dominio_activo,false,true);
			setcookie("_token_b",false,time() - 3600,'/',$dominio_activo,false,true);		
			setcookie("txttime",false,time() - 3600,'/',$dominio_activo,false,false);		
			session_regenerate_id(true);
			session_unset();
			session_destroy();		
			//INICIA UNA NUEVA SESION
			session_start();	
		}
		elseif($tp==2){	
			setcookie("_token_a",false,time() - 3600,'/',$dominio_activo,false,true);
			setcookie("_token_b",false,time() - 3600,'/',$dominio_activo,false,true);		
			setcookie("txttime",false,time() - 3600,'/',$dominio_activo,false,false);
			unset($_SESSION["_USER"]);	
			unset($_SESSION["INFO_USUARIO"]);
			unset($_SESSION["INFO_GRUPOS"]);
			unset($_SESSION["ses"]);
			unset($_SESSION["ses_type"]);
			unset($_SESSION["_CLIENTE"]);
			unset($_SESSION["EMPRESA"]);
		}

	}
	catch (Exception $e){
		$err_str=$e->getMessage();
	}	
}
/**********************/
/**********************/
/**********************/
/***** VALIDATION *****/
/**********************/
/**********************/
/**********************/
function verif_sp(&$_sysvars,$dbEmpresa){
	$ESTTZ = new DateTimeZone('UTC');
	$hoyOBJ = new DateTime(date(DATE_ATOM),$ESTTZ); 
	$hoySTR=$hoyOBJ->format('Y-m-d H:i');

	$_ip=$_sysvars["_ip"];
	$_memory=$_sysvars["_memory"]==1?1:0;
	$_session=$_sysvars["_session"];

	$last=$_sysvars["last"]==true;

	$_token_a=$_sysvars["_token_a"];
	$_token_b=$_sysvars["_token_b"];

	$_link_a=$_sysvars["_link_a"];
	$_link_b=$_sysvars["_link_b"];

	$_ualias=$_sysvars["_ualias"];
	$_upassw=$_sysvars["_upassw"];

	$client=$_sysvars["client"];
	$_PROYECTO=$_sysvars["project"];
	$_EMPRESA=$_sysvars["company"];

	$_PHONE_KEY=$_sysvars["_key"];
	$_PHONE_TYPE=$_sysvars["_phone"];
	$tp=$_sysvars["tp"];
	$_sysvars_r=array();

	$rs_comprob='';

	$new_session=false;
	$return=false;	

	//SESION EXISTENTE
	if($_token_a!="" && $_token_b!='' && !$last){
        $s='SELECT 
				adm_usuarios.ID_USUARIO,
				adm_usuarios.ALIAS,
				adm_usuarios.NOMBRE_U,
				adm_usuarios.APELLIDO_U, 
				adm_usuarios.PASSWORD_U,
				adm_usuarios.CORREO_U,
				fac_idioma.ID_IDIOMA,
				fac_idioma.NAV01,
				fac_idioma.IDIOMA,
				fac_tz.TZ_DIFE,

				adm_usuarios_reg.ID_SES,
				UNIX_TIMESTAMP(adm_usuarios_reg.FECHAF_U) AS LAST_DATE,
				adm_usuarios_reg.FECHAF_U,
				adm_usuarios_reg.TYPE_U,
				adm_usuarios_reg.TOKENA_SES,
				adm_usuarios_reg.TOKENB_SES,
				adm_usuarios_reg_phone.TYPEP_SES,
				adm_usuarios_reg_phone.KEYP_SES,

				adm_usuarios_facebook.ID_FACEBOOK,
				adm_usuarios_facebook.NAME_FB,				
				adm_usuarios_facebook.LASTNAME_FB,
				IFNULL(adm_files.ID_FILE,0) AS M_IMG,
				adm_files.F_EXT AS F_EXT
			FROM adm_usuarios
			LEFT JOIN adm_usuarios_facebook ON adm_usuarios_facebook.ID_USUARIO=adm_usuarios.ID_USUARIO
			LEFT JOIN adm_usuarios_reg ON adm_usuarios_reg.ID_USUARIO=adm_usuarios.ID_USUARIO
			LEFT JOIN adm_usuarios_reg_phone ON adm_usuarios_reg_phone.ID_SES=adm_usuarios_reg.ID_SES
			LEFT JOIN fac_idioma ON fac_idioma.ID_IDIOMA=adm_usuarios.ID_IDIOMA
			LEFT JOIN fac_tz ON fac_tz.ID_TZ=116
			LEFT JOIN adm_files ON adm_files.ID_FILE=adm_usuarios.ID_FILE
			WHERE adm_usuarios_reg.TOKENA_SES=:_token_a
			AND adm_usuarios_reg.TOKENB_SES=:_token_b
			AND adm_usuarios.HAB_U=0 
			AND adm_usuarios_reg.CERRADO_U=0 
			AND IF(adm_usuarios_reg.TYPE_U=1,
					UTC_TIMESTAMP() BETWEEN adm_usuarios_reg.FECHA_U AND adm_usuarios_reg.FECHAF_U,
					adm_usuarios_reg.SESSION_U=:_session) LIMIT 1';
		$req = $dbEmpresa->prepare($s);	
		$req->bindParam(':_token_a', $_token_a);
		$req->bindParam(':_token_b', $_token_b);
		$req->bindParam(':_session', $_session);
		$req->execute();
		$return=($reg = $req->fetch());
	}
	//SI ES POR UN LINK
	elseif($_link_a!="" && $_link_b!='' && !$last){
        $s='SELECT 
				adm_usuarios.ID_USUARIO,
				adm_usuarios.ALIAS,
				adm_usuarios.NOMBRE_U,
				adm_usuarios.APELLIDO_U, 
				adm_usuarios.PASSWORD_U,
				adm_usuarios.CORREO_U,
				fac_idioma.ID_IDIOMA,
				fac_idioma.NAV01,
				fac_idioma.IDIOMA,
				fac_tz.TZ_DIFE,				
				adm_usuarios_reg.TYPE_U,
				adm_usuarios_facebook.ID_FACEBOOK,
				adm_usuarios_facebook.NAME_FB,				
				adm_usuarios_facebook.LASTNAME_FB,
				IFNULL(adm_files.ID_FILE,0) AS M_IMG,
				adm_files.F_EXT AS F_EXT
			FROM adm_usuarios
			LEFT JOIN adm_usuarios_facebook ON adm_usuarios_facebook.ID_USUARIO=adm_usuarios.ID_USUARIO			
			LEFT JOIN fac_idioma ON fac_idioma.ID_IDIOMA=adm_usuarios.ID_IDIOMA
			LEFT JOIN fac_tz ON fac_tz.ID_TZ=116
			LEFT JOIN adm_files ON adm_files.ID_FILE=adm_usuarios.ID_FILE
			WHERE adm_usuarios.ID_USUARIO IN (
					SELECT sys_links.ID_USUARIO 
					FROM sys_links
					WHERE SHA1(ID_LINK)=:_link_a AND RND_LINK=:_link_b AND VAL_LINK=1 AND FECHAE_LINK>=UTC_TIMESTAMP()) 
				AND adm_usuarios.HAB_U=0 LIMIT 1';
		$req = $dbEmpresa->prepare($s);	
		$req->bindParam(':_link_a', $_link_a);
		$req->bindParam(':_link_b', $_link_b);
		$req->execute();
		$return=($reg = $req->fetch());
		$link=true;
		$new_session=true;
	}
	//INICIO DE SESION CON USUARIO Y CONTRASEÑA
	elseif($_ualias!="" && $_upassw!=''){
		$s = "SELECT 
				adm_usuarios.ID_USUARIO,
				adm_usuarios.ALIAS,
				adm_usuarios.NOMBRE_U,
				adm_usuarios.APELLIDO_U, 
				adm_usuarios.PASSWORD_U,
				adm_usuarios.CORREO_U,
				fac_idioma.ID_IDIOMA,
				fac_idioma.NAV01,
				fac_idioma.IDIOMA,
				fac_tz.TZ_DIFE,
				adm_usuarios_facebook.ID_FACEBOOK,
				adm_usuarios_facebook.NAME_FB,				
				adm_usuarios_facebook.LASTNAME_FB,
				IFNULL(adm_files.ID_FILE,0) AS M_IMG,
				adm_files.F_EXT AS F_EXT
			FROM adm_usuarios
			LEFT JOIN adm_usuarios_facebook ON adm_usuarios_facebook.ID_USUARIO=adm_usuarios.ID_USUARIO			
			LEFT JOIN fac_idioma ON fac_idioma.ID_IDIOMA=adm_usuarios.ID_IDIOMA
			LEFT JOIN fac_tz ON fac_tz.ID_TZ=116
			LEFT JOIN adm_files ON adm_files.ID_FILE=adm_usuarios.ID_FILE
			WHERE (adm_usuarios.CORREO_U=:_ualias OR adm_usuarios.ALIAS=:_ualias) AND adm_usuarios.PASSWORD_U=:_upassw AND adm_usuarios.HAB_U=0";	
		$req = $dbEmpresa->prepare($s);	
		$req->bindParam(':_ualias', $_ualias);
		$req->bindParam(':_upassw', $_upassw);				
		$req->execute();
		$return=($reg = $req->fetch());
		$new_session=true;
	}
	if($return){	
		$id=$reg["ID_USUARIO"];		
		$password=$reg["PASSWORD_U"];
		$email=$reg["CORREO_U"];
		$_sysvars["id"]=$reg["ID_USUARIO"];
		$_sysvars["alias"]=$reg["ALIAS"];
		$_sysvars["email"]=$reg["CORREO_U"];
		$_sysvars["name"]=$reg["NOMBRE_U"];
		$_sysvars["lastname"]=$reg["APELLIDO_U"];		

		$_sysvars["lang"]=$reg["NAV01"];	
		$_sysvars["lang_name"]=$reg["IDIOMA"];
		$_sysvars["lang_id"]=$reg["ID_IDIOMA"];
		if($reg["ID_FACEBOOK"]!=''){
			$_sysvars["fb"]=$reg["ID_FACEBOOK"];
			$_sysvars["fb_name"]=$reg["NAME_FB"];
			$_sysvars["fb_lastname"]=$reg["LASTNAME_FB"];
		}

		$_sysvars["tz"]=$reg["TZ_DIFE"];

		if(!$last){
			$_sysvars["tiempo"]=$reg["TYPE_U"]==1?$reg["LAST_DATE"]:0;
			$_sysvars["endses"]=$reg["FECHAF_U"];
			$_sysvars["memory"]=$reg["TYPE_U"];
		}

		$_PREFIX=GetPrefixURL($dbEmpresa);
		$ArrayImg=array(
            'PROYECTO'	=>$_PROYECTO
        ,   'EMPRESA'  	=>$_EMPRESA
        ,   'MODULO'    =>36
        ,   'OBJETO'    =>$id
        ,   'TP'        =>'img'
        ,   'EXT'       =>$reg["F_EXT"]
        ,   'All'       =>true);				
		$_sysvars["display"]=ImgBlanc($reg["M_IMG"],$ArrayImg);
		$_sysvars["display"]['img']=$reg["M_IMG"];
		$_sysvars["display"]["prefix"]=$_PREFIX;

		if($new_session){			
			$PC_U='RestV1'; 
			if($last){
				$tiempo=$_sysvars["tiempo"];
				$finOBJ=$_sysvars["endses"];
				$_memory=$_sysvars["memory"];
			}
			else{
				$tiempo=$_memory==1?(time()+(60*60*24*15)):0;			
				if($_memory==1)	$finOBJ=$hoyOBJ->add(new DateInterval('P15D'));
				else 			$finOBJ=$hoySTR;
				$finSTR=$hoyOBJ->format('Y-m-d H:i');
			}
			$_ttend=$tiempo;
			$_sysvars["_ttend"]=$_ttend;

			$s="UPDATE adm_usuarios_reg
					SET ACTIVA_U=11
				WHERE ID_USUARIO=:id AND ACTIVA_U=10 AND CERRADO_U=0";
			$reqSes = $dbEmpresa->prepare($s);	 
			$reqSes->bindParam(':id', $id);
			$reqSes->execute();		

	
			$_preses_a=uniqid($id.'x'.$email);
			$_preses_b=uniqid($password.'x'.$email);
			$_token_a=sha1($_preses_a);
			$_token_b=sha1($_preses_b);
			$s="INSERT INTO adm_usuarios_reg 
					(ID_USUARIO
					,FECHA_U
					,FECHAF_U
					,PC_U
					,IP_U
					,ACTIVA_U
					,TYPE_U
					,SESSION_U
					,TOKENA_SES
					,TOKENB_SES) 
				VALUES(
					:user
					,UTC_TIMESTAMP()
					,'$finSTR'
					,:PC_U
					,:_ip
					,10
					,:type
					,:_session
					,:_token_a
					,:_token_b)";
			$reqSes = $dbEmpresa->prepare($s);	
			$reqSes->bindParam(':user', $id);
			$reqSes->bindParam(':PC_U', $PC_U);			
			$reqSes->bindParam(':_ip',$_ip);	
			$reqSes->bindParam(':type',$_memory);	
			$reqSes->bindParam(':_session',$_session);	
			$reqSes->bindParam(':_token_a',$_token_a);
			$reqSes->bindParam(':_token_b',$_token_b);
			$reqSes->execute();		
			$_ses=$dbEmpresa->lastInsertId();

			if($_PHONE_KEY!='' && $_PHONE_TYPE!=""){
				$s='INSERT INTO adm_usuarios_reg_phone
						(ID_SES
					,	TYPEP_SES
					,	KEYP_SES)
					VALUES
						(:_ses
					,	:_PHONE_TYPE
					,	:_PHONE_KEY)';
				$reqPhone = $dbEmpresa->prepare($s);
				$reqPhone->bindParam(':_ses', $_ses);
				$reqPhone->bindParam(':_PHONE_TYPE', $_PHONE_TYPE);
				$reqPhone->bindParam(':_PHONE_KEY', $_PHONE_KEY);	
				$reqPhone ->execute();
			}
		}
		else{
			$_ses=$reg["ID_SES"];
			$_ttend=$reg["TYPE_U"]==1?$reg["LAST_DATE"]:0;
			$_token_a=$reg['TOKENA_SES'];
			$_token_b=$reg['TOKENB_SES'];
		}
		$_sysvars["_token_a"]=$_token_a;
		$_sysvars["_token_b"]=$_token_b;
	
		if($client!=""){
			$s="SELECT adm_empresas.ID_MEMPRESA,
				adm_empresas_tipo.TIPO_GRUPOPAL,
				adm_usuarios_empresa.ID_GRUPO
				FROM adm_usuarios_empresa
				JOIN adm_empresas ON adm_empresas.ID_MEMPRESA=adm_usuarios_empresa.ID_MEMPRESA
				JOIN adm_empresas_tipo ON adm_empresas_tipo.ID_TIPOE=adm_empresas.ID_TIPOE
			WHERE adm_usuarios_empresa.ID_USUARIO=:id AND adm_usuarios_empresa.ID_MEMPRESA=:client LIMIT 1";
			$reqEmp = $dbEmpresa->prepare($s);	
			$reqEmp->bindParam(':id', $id);
			$reqEmp->bindParam(':client', $client);				
			$reqEmp->execute();	
			if($regEmp = $reqEmp->fetch()){
				$_sysvars["client"]=$regEmp["ID_MEMPRESA"];
				$_sysvars["gclient"]=$regEmp["TIPO_GRUPOPAL"];		
				$_sysvars_r["admin_group"]=$regEmp["ID_GRUPO"];				
			}
		}
		else{
			$s="SELECT adm_empresas.ID_MEMPRESA,
				adm_empresas_tipo.TIPO_GRUPOPAL,
				adm_usuarios_empresa.ID_GRUPO
				FROM adm_usuarios_empresa
				JOIN adm_empresas ON adm_empresas.ID_MEMPRESA=adm_usuarios_empresa.ID_MEMPRESA
				JOIN adm_empresas_tipo ON adm_empresas_tipo.ID_TIPOE=adm_empresas.ID_TIPOE
			WHERE adm_usuarios_empresa.ID_USUARIO=:id ORDER BY adm_usuarios_empresa.LAST DESC LIMIT 1";
			$reqEmp = $dbEmpresa->prepare($s);	
			$reqEmp->bindParam(':id', $id);			
			$reqEmp->execute();	
			if($regEmp = $reqEmp->fetch()){
				$_sysvars["client"]=$regEmp["ID_MEMPRESA"];
				$_sysvars["gclient"]=$regEmp["TIPO_GRUPOPAL"];	
				$_sysvars_r["admin_group"]=$regEmp["ID_GRUPO"];				
			}			
		}		
		$_sysvars_r["id"]=$id;
		$_sysvars_r["password"]=$password;
		$_sysvars_r["ses"]=$_ses;		
	}
	unset($_sysvars["_ualias"]);
	unset($_sysvars["_upassw"]);
	unset($_sysvars["_link_a"]);
	unset($_sysvars["_link_b"]);
	unset($_sysvars["_key"]);
	unset($_sysvars["_phone"]);

	$_sysvars_r["project"]=$_PROYECTO;
	$_sysvars_r["company"]=$_EMPRESA;
	$_sysvars_r["return"]=$return;
	$_sysvars_r=array_merge($_sysvars_r,$_sysvars);
	return $_sysvars_r;
}
function verif_notif($id_usuario,$opts,$dbEmpresa){
	$result=array();
	
	if($opts["type"]==1){
		define( 'API_ACCESS_KEY', $opts["android"]["GOOGLE_KEY"]);
		$registrationIds = array($opts["key"]);

		$fields = array	(
			    'registration_ids'  => $registrationIds,
			    'data'              => $opts["android"]["msg"]
			);

		$headers = array(
		    'Authorization: key=' . API_ACCESS_KEY,
		    'Content-Type: application/json'
		);

		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result[] = curl_exec($ch );
		curl_close( $ch );



	}
	elseif($opts["type"]==2){
		$deviceToken=$opts["key"];
		$passphrase = $opts["ios"]["CERT_PHRASE"];
		$message = $opts["ios"]["msg"];

		////////////////////////////////////////////////////////////////////////////////

		$ctx = stream_context_create();
		$filename = $opts["ios"]["CERT_FILE"];
		stream_context_set_option($ctx, 'ssl', 'local_cert', $filename);
		stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

		// Open a connection to the APNS server
		$fp = stream_socket_client(
			'ssl://gateway.sandbox.push.apple.com:2195'
			, 	$err
			,	$errstr
			, 	60
			, 	STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT
			, 	$ctx);

		if (!$fp)
		$result[]="Failed to connect: $err $errstr" . PHP_EOL;

		//echo 'Connected to APNS' . PHP_EOL;

		// Create the payload body
		$body['aps'] = array(
				'alert' => $message
			,	'sound' => 'default'
		);

		$payload = json_encode($body);
		$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)).$payload;
		$result[]=fwrite($fp, $msg, strlen($msg));

		if (!$result)
		    $result[]='Message not delivered' . PHP_EOL;
		else
		    $result[]='Message successfully delivered'.PHP_EOL;
		fclose($fp);

	}
	
	return $result;
}

function SendGCM($fields,$KEY){
    $url='https://gcm-http.googleapis.com/gcm/send';
    $headers = array(
    	'Authorization: key=' . $KEY
    ,	'Content-Type: application/json'
    );

    $ch = curl_init();
    curl_setopt( $ch,CURLOPT_URL,$url);
    curl_setopt( $ch,CURLOPT_POST, true );
    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
    $res = curl_exec($ch );
    curl_close( $ch );

    return $res;
}
function SubDominio(){
	include "variables.php";	
	$dom_fuera=($_SERVER["HTTP_HOST"]!=$dominio[1]);
	if($dom_fuera){	
		$host=$_SERVER["HTTP_HOST"];		
		$subdominio=$_SERVER["HTTP_HOST"];
	}
	return mb_strtolower($subdominio);
}
function Dominio(){
	include "variables.php";
	return $dominio[1];
}
function EmpresaComprov($dbEmpresa,$Forzar=false){
	if ((count($_SESSION["_CLIENTE"])<=1)||($Forzar)){
		$usuario=$_SESSION["_USER"];				
		$s="SELECT adm_usuarios_empresa.ID_MEMPRESA,
			adm_empresas_tipo.TIPO_GRUPOPAL,
			adm_empresas.URL
			FROM adm_usuarios_empresa
			JOIN adm_empresas ON adm_empresas.ID_MEMPRESA=adm_usuarios_empresa.ID_MEMPRESA AND adm_empresas.HAB_MEMPRESA=0
			LEFT JOIN adm_empresas_tipo ON adm_empresas_tipo.ID_TIPOE=adm_empresas.ID_TIPOE
			WHERE adm_usuarios_empresa.ID_USUARIO=:idUsuario
			ORDER BY adm_usuarios_empresa.LAST DESC LIMIT 1";
		$req = $dbEmpresa->prepare($s);	 
		$req->bindParam(':idUsuario',$usuario);
		$req->execute();	
		if($reg = $req->fetch()){
			$_SESSION["_CLIENTE"]=array();
			$_SESSION["_CLIENTE"]["id"]=$reg["ID_MEMPRESA"];
			$_SESSION["_CLIENTE"]["group"]=$reg["TIPO_GRUPOPAL"];
			$_SESSION["_CLIENTE"]["url"]=$reg["URL"];
		}
		return true;		
	}
	else return true;
}
function Parametros($dbEmpresa,$Forzar=false){	

	if ((!isset($_SESSION["EMPRESA"]["PUBL"]))||(!isset($_SESSION["EMPRESA"]["PRIV"]))||($Forzar)){
		require_once "consultas.php";		
		include "variables_se.php";
		Consultas($sqlCons,$sqlOrder,$_PROYECTO,$_CLIENTE,$_IDIOMA,$_TZ,$_GCLIENTE,$_USUARIO,$_GRUPO,$_PARAMETROS);
		
		$_SESSION["EMPRESA"]=array();
		$K1=false;$K2=false;
		$s=$sqlCons[1][78];	 
		foreach ($dbEmpresa->query($s) as $reg) {
			if(!$K1){
				$_SESSION["EMPRESA"]["PUBL"]=1;
				$K1=true;
			}
			$_SESSION["EMPRESA"][$reg["CONFIG_NOMBRE"]]=$reg["CONFIG_VALOR"];
		}

		if($_CLIENTE!=0){
			$s=$sqlCons[2][9];	
			$req = $dbEmpresa->prepare($s);	
			$req->bindParam(':idioma', $_IDIOMA);
			$req->bindParam(':empresa', $_CLIENTE);
			$req->execute();
			while ($reg = $req->fetch()) {
				if(!$K2){
					$_SESSION["EMPRESA"]["PRIV"]=1;
					$K2=true;
				}
				if($reg["CONFIG_VALOR"]!='')
					$_SESSION["EMPRESA"][$reg["CONFIG_NOMBRE"]]=$reg["CONFIG_VALOR"];
			}
		}
	}
	return $_SESSION["EMPRESA"];
}
function ParametrosAPI($dbEmpresa,$_sysvars_r,$_sysvars){	
	$_VCarga=false;
	require_once "consultas.php";		
	include "variables_se.php";
	Consultas($sqlCons,$sqlOrder,$_PROYECTO,$_CLIENTE,$_IDIOMA,$_TZ,$_GCLIENTE,$_USUARIO,$_GRUPO,$_PARAMETROS);
	$return=array();	

	$s=$sqlCons[1][78];	 
	foreach ($dbEmpresa->query($s) as $reg) {
		$return[$reg["CONFIG_NOMBRE"]]=$reg["CONFIG_VALOR"];
	}
	if($_CLIENTE!=0){
		$s=$sqlCons[2][9];	
		$req = $dbEmpresa->prepare($s);	
		$req->bindParam(':idioma', $_IDIOMA);
		$req->bindParam(':empresa', $_CLIENTE);
		$req->execute();
		while ($reg = $req->fetch()) {
			if($reg["CONFIG_VALOR"]!='')
				$return[$reg["CONFIG_NOMBRE"]]=$reg["CONFIG_VALOR"];
		}
	}		
	return $return;
}
function UsuarioInfo($dbEmpresa,$Forzar=false){	
	//CARGA DATOS DE USUARIO
	require_once "consultas.php";		
	include "variables_se.php";
	Consultas($sqlCons,$sqlOrder,$_PROYECTO,$_CLIENTE,$_IDIOMA,$_TZ,$_GCLIENTE,$_USUARIO,$_GRUPO,$_PARAMETROS);
	if (($_SESSION["INFO_USUARIO"]["ID_USUARIO"]=="")||($_SESSION["INFO_USUARIO"]["ID_GRUPO"]=="")||($Forzar)){
		$_SESSION["INFO_USUARIO"]=array();		
		$s=$sqlCons[1][0]." WHERE adm_usuarios.ID_USUARIO=:idUsuario LIMIT 1";
		$req = $dbEmpresa->prepare($s);	 
		$req->bindParam(':idUsuario', $_USUARIO);	
		$req->execute();		
		if($reg = $req->fetch()){
			foreach($reg as $name => $valor){
				if(!is_numeric($name)) $_SESSION["INFO_USUARIO"][$name]=$valor;
			}
		}
	}
}
function CargarCiudades($dbEmpresa,$Forzar=false){
	if ((!isset($_SESSION["CIUDADES_AUTOR"]))||($Forzar)){
		$_SESSION["CIUDADES_AUTOR"]=array();
		$s="SELECT s_cresp_ciudades.ID_CIUDAD,
			s_cresp_ciudades.ID_RESP
			FROM s_cresp_ciudades
			WHERE s_cresp_ciudades.ID_RESP IN (SELECT s_cresp_grupo.ID_RESP FROM s_cresp_grupo WHERE s_cresp_grupo.ID_GRUPO=:grupo)";
		$req = $dbEmpresa->prepare($s);	 
		$req->bindParam(':grupo', $_GRUPO);	
		$req->execute();		
		while($reg = $req->fetch()){
			$_SESSION["CIUDADES_AUTOR"][]=$reg["ID_CIUDAD"];
		}
	}
}
function Grupos($dbEmpresa,$_GRUPO,$_GCLIENTE){
	$s="SELECT 
		IFNULL(adm_grupos_ven.PERMISO_GRUPOVEN,0) AS PERMISO_GRUPOVEN,
		IFNULL(adm_ventanas_etipo.PERMISO,0) AS POSIBLE_ACCESO,
		adm_ventanas.ID_VENTANA
		FROM adm_ventanas
		LEFT JOIN adm_grupos_ven ON adm_grupos_ven.ID_VENTANA=adm_ventanas.ID_VENTANA AND adm_grupos_ven.ID_GRUPO=:_GRUPO
		LEFT JOIN adm_ventanas_etipo ON adm_ventanas_etipo.ID_VENTANA=adm_ventanas.ID_VENTANA AND adm_ventanas_etipo.TIPO_GRUPOPAL=:_GCLIENTE";
	$req = $dbEmpresa->prepare($s);	
	$req->bindParam(':_GRUPO',$_GRUPO);
	$req->bindParam(':_GCLIENTE',$_GCLIENTE);
	$req->execute();
	while($reg = $req->fetch()){
		$id_ventana=$reg["ID_VENTANA"];
		$return[$id_ventana]=array(		"P"=>$reg["POSIBLE_ACCESO"]==0?0:$reg["PERMISO_GRUPOVEN"]
									,	"A"=>$reg["POSIBLE_ACCESO"]);
	}
	return $return;	
}
function GruposAPI($dbEmpresa,$Modules,$_sysvars_r,$_sysvars){	

	$_VCarga=false;
	require_once "consultas.php";		
	include "variables_se.php";
	Consultas($sqlCons,$sqlOrder,$_PROYECTO,$_CLIENTE,$_IDIOMA,$_TZ,$_GCLIENTE,$_USUARIO,$_GRUPO,$_PARAMETROS);
	
	$return=array();
	$s="SELECT 
		IFNULL(adm_grupos_ven.PERMISO_GRUPOVEN,0) AS PERMISO_GRUPOVEN,
		IFNULL(adm_ventanas_etipo.PERMISO,0) AS POSIBLE_ACCESO,
		adm_ventanas.ID_VENTANA
		FROM adm_ventanas
		LEFT JOIN adm_grupos_ven ON adm_grupos_ven.ID_VENTANA=adm_ventanas.ID_VENTANA AND adm_grupos_ven.ID_GRUPO=:_GRUPO
		LEFT JOIN adm_ventanas_etipo ON adm_ventanas_etipo.ID_VENTANA=adm_ventanas.ID_VENTANA AND adm_ventanas_etipo.TIPO_GRUPOPAL=:_GCLIENTE
		WHERE adm_ventanas.ID_VENTANA IN (".implode(",",$Modules).")";
	
	$req = $dbEmpresa->prepare($s);	
	$req->bindParam(':_GRUPO',$_GRUPO);
	$req->bindParam(':_GCLIENTE',$_GCLIENTE);
	$req->execute();
	while ($reg = $req->fetch()) {
		$return["MOD"][$reg["ID_VENTANA"]]["P"]=$reg["POSIBLE_ACCESO"]==0?0:$reg["PERMISO_GRUPOVEN"];
		$return["MOD"][$reg["ID_VENTANA"]]["A"]=$reg["POSIBLE_ACCESO"];
	}
	return $return;
}
function CargarMensajes($Forzar=false){
	if(isset($_SESSION["INFO_USUARIO"]["ID_IDIOMA"])) 
				$_IDIOMA=$_SESSION["INFO_USUARIO"]["ID_IDIOMA"];
	else 		$_IDIOMA=1;		
	
	if((count($_SESSION["MENSAJES"])==0)||($Forzar)){
		include "mysql_valores.php";
		$_SESSION["MENSAJES"]=array();		
		$s="SELECT
		fac_mensajes.ID_MENSAJE,
		fac_mensajes.ID_IDIOMA,
		fac_mensajes.MENSAJE,
		fac_mensajes.DIV_MENSAJE,
		fac_mensajes.DIV_ICONO
		FROM fac_mensajes
		WHERE ID_IDIOMA=$_IDIOMA";	
		$req = $dbMat->prepare($s);	 		
		$req->execute();
		while($reg = $req->fetch()){			
			$_SESSION["MENSAJES"][$reg["ID_MENSAJE"]][0]=$reg["MENSAJE"];
			$_SESSION["MENSAJES"][$reg["ID_MENSAJE"]][1]=$reg["DIV_MENSAJE"];
			$_SESSION["MENSAJES"][$reg["ID_MENSAJE"]][2]=$reg["DIV_ICONO"];
		}
	}
}
function CargarTextos($dbEmpresa,$_PROYECTO,$_CLIENTE,$_IDIOMA,$_TZ,$_GCLIENTE,$_USUARIO,$_GRUPO,$_PARAMETROS){
	
	include "mysql_valores.php";
	require_once "consultas.php";		
	Consultas($sqlCons,$sqlOrder,$_PROYECTO,$_CLIENTE,$_IDIOMA,$_TZ,$_GCLIENTE,$_USUARIO,$_GRUPO,$_PARAMETROS);
	

	$s=$sqlCons[1][68]." WHERE ID_IDIOMA=:id_idioma";	
	$reqG = $dbMat->prepare($s);	 
	$reqG->bindParam(':id_idioma', $_IDIOMA);
	$reqG->execute();
	while($regG = $reqG->fetch()){
		$_TEXTOS[$regG["ID_PALABRA"]][0]=$regG["PALABRA"];
		$_TEXTOS[$regG["ID_PALABRA"]][1]=$regG["TOOLTIP"];
	}			
	$s=$sqlCons[2][77].' UNION '.$sqlCons[3][77];		
	$req = $dbEmpresa->prepare($s); 
	$req->bindParam(':idioma', $_IDIOMA);
	$req->bindParam(':grupo', $_GCLIENTE);
	$req->bindParam(':empresa', $_CLIENTE);
	$req->execute();		
	while($reg = $req->fetch()){
		$_TEXTOS[$reg["ID_PALABRA"]][0]=$reg["PALABRA"];
		$_TEXTOS[$reg["ID_PALABRA"]][1]=$reg["TOOLTIP"];
	}
	return $_TEXTOS;
}
function cURLdata($url){
	ob_start();					                                                                
	$ch = curl_init($url);                                                               
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($ch, CURLOPT_VERBOSE, 0);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
	curl_setopt($ch, CURLOPT_REFERER, $_SERVER["HTTP_HOST"]);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	$result = curl_exec($ch);
	$resOBJ=json_decode($result);
	$response=objectToArray($resOBJ);
	curl_close($ch);
	ob_end_clean();
	return $response;
}
//0 OK
//1 SESION NO EXISE

function mobile(){
    $hua=$_SERVER['HTTP_USER_AGENT'];
    if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone)/i',mb_strtolower($hua)))$m=true;
    if(strpos(mb_strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml')>0||
    ((isset($_SERVER['HTTP_X_WAP_PROFILE'])||isset($_SERVER['HTTP_PROFILE']))))$m=true;
 
    $mua=mb_strtolower(mb_substr($hua,0,4));
    $ma = array('w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird',
		'blac','blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
    	'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-','maui',
		'maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-','newt','noki',
    	'oper','palm','pana','pant','phil','play','port','prox','qwap','sage','sams',
		'sany','sch-','sec-','send','seri','sgh-','shar','sie-','siem','smal','smar',
		'sony','sph-','symb','t-mo','teli','tim-','tosh','tsm-','upg1','upsi','vk-v',
		'voda','wap-','wapa','wapi','wapp','wapr','webc','winw','xda','xda-');
    if(in_array($mua,$ma))$m=true;
    if(strpos(mb_strtolower(@$_SERVER['ALL_HTTP']),'OperaMini')>0)$m=true;
    if(strpos(mb_strtolower($hua),'windows')>0&&strpos(mb_strtolower($hua),'IEMobile')<=0)$m=false;
    return $m;
	//return 1;
}
function imagen_valid($tipo){
	$tipos=array('image/jpeg','image/png','image/bmp','image/gif','image/tif');
	if(in_array($tipo,$tipos))$m=true;
	return $m;
}

function checkmail($email=null){ 
	return (filter_var($email, FILTER_VALIDATE_EMAIL));
} 
function comprobtexto($texto){ 
	if (ereg("^[a-zA-Z0-9\-_]{3,20}$", $texto))
		return true; 
	else 
		return false; 
} 

function tipo_archivo($tipo,$clase=1){
	if($clase==1){
		$entidad=array(
			'image/jpeg' => 1,
			'image/png' => 1,
			'image/bmp' => 1,
			'image/gif' => 1,
			'image/x-icon' => 1,
			'image/tif' => 1,
			'image/x-ico' => 1);
	}
	elseif($clase==2){
		$entidad=array(
			'image/x-icon' => 1,
			'image/vnd.microsoft.icon' => 1,
			'image/x-ico' => 1);
	}
	return($entidad[$tipo]);
	
}

/**/
function crear_select($req,$id,$contenido,$init=0,$iniciar=1,$texto_def='Seleccione una opción...',$encrip=0,$val_ini=0,$tipo=0,$init_array=array(),$name="",$SelAll=0){
	if(substr($texto_def,0,4)=='txt-'&&$iniciar==1){
		$rta='<option value="'.($encrip==0?$val_ini:encrip(0).$encrip).'" data-txtid="'.$texto_def.'"></option>';
	}
	elseif($iniciar==1){
		$rta='<option value="'.($encrip==0?$val_ini:encrip(0).$encrip).'">'.$texto_def.'</option>';
	}
	else $rta='';
	if($tipo==0){
	    while($reg = $req->fetch()){
			if($reg[$id]==$init) $selec_in='selected="selected"';
			else $selec_in='';
	        $rta.=sprintf('<option value="%s" %s>%s</option>',($encrip==0?$reg[$id]:encrip($reg[$id]).$encrip),$selec_in,imprimir($reg[$contenido]));                        
	    }
	}
	elseif($tipo==1){
		while($reg = $req->fetch()){
			if((in_array($reg[$id], $init_array))||($SelAll==1)) 	$selec_in='checked';
			else 													$selec_in='';
			$rta.=sprintf('<label><input type="checkbox" name="%s[]" %s value="%s">%s</label><br />',
					$name,$selec_in,$reg[$id],imprimir($reg[$contenido]));                       
	    }
	}
	return ($rta);
}

function SubirImagen($control,$dir_dest,$nomb_f,$tipo='png',&$tamX=0,&$tamY=0,$control_type=0){
	$cli = (isset($argc) && $argc > 1);
	if ($cli) {
		if (isset($argv[1])) $_GET['file'] = $argv[1];
		if (isset($argv[2])) $_GET['dir'] = $argv[2];
		if (isset($argv[3])) $_GET['pics'] = $argv[3];
	}
	
	
	ini_set("max_execution_time",0);
	if($control_type==0) 		$handle = new Upload($_FILES[$control]);
	elseif($control_type==1) 	$handle = new Upload($control);
	if ($handle->uploaded) {
		//Imagen Completa
		$nombre=$nomb_f;
		$filedirec=sprintf('%s/%s.%s',$dir_dest,$nombre,$tipo);

		if($tipo=='png'){			
			$handle->file_new_name_body=$nombre;
			$handle->image_convert			=$tipo;
			$handle->file_overwrite			=true;
			$handle->Process($dir_dest);
			
			//Imagen Thumnail
			$nombre=$nomb_f."_tn1";
			$filedirec=sprintf('%s/%s.%s',$dir_dest,$nombre,"jpg");

			$handle->file_new_name_body=$nombre;
			$handle->image_resize          	=true;
			$handle->image_ratio_y         	=true;
			$handle->image_x               	=100;
			$handle->file_overwrite			=true;
			$handle->image_convert         	='jpg';
			$handle->Process($dir_dest);
			   
			
			//Imagen Thumnail
			$nombre=$nomb_f."_tn2";
			$filedirec=sprintf('%s/%s.%s',$dir_dest,$nombre,"jpg");

			$handle->file_new_name_body=$nombre;
			$handle->image_resize          	= true;
			$handle->image_ratio_y         	= true;
			$handle->image_x               	= 200;
			$handle->file_overwrite			=true;
			$handle->image_convert         	='jpg';
			$handle->Process($dir_dest);
			
			//Imagen Thumnail2
			$nombre=$nomb_f."_tn3";
			$filedirec=sprintf('%s/%s.%s',$dir_dest,$nombre,"jpg");
	
			$handle->file_new_name_body=$nombre;
			$handle->image_resize          	= true;
			$handle->image_ratio_y         	= true;
			$handle->image_x               	= 400;
			$handle->file_overwrite			=true;
			$handle->image_convert         	='jpg';
			$handle->Process($dir_dest);
		}
		else{		
			$handle->file_new_name_body=$nombre;
			$handle->file_overwrite			=true;
			$handle->Process($dir_dest);
		}
	}
	$tamX=$handle->image_src_x;
	$tamY=$handle->image_src_y;
	@unlink($_FILES[$control]);
}

function UploadFiles(&$AwsS3,$control,$dbEmpresa,$_ARGS,&$Info=array()){
	/*
	S3_BUCKET,
	PROYECTO,EMPRESA,
	MODULE,OBJECT,TP_FILE
	
	control_type
	tipo
	*/
	///////////////////////////////////
	///////////////////////////////////
	$cli = (isset($argc) && $argc > 1);
	if ($cli) {
		if (isset($argv[1])) $_GET['file'] = $argv[1];
		if (isset($argv[2])) $_GET['dir'] = $argv[2];
		if (isset($argv[3])) $_GET['pics'] = $argv[3];
	}
	///////////////////////////////////
	///////////////////////////////////
	$control_type=isset($_ARGS["control_type"])?$_ARGS["control_type"]:0;
	$tipo=isset($_ARGS["tipo"])?$_ARGS["tipo"]:'img';
	$_ARGS["TP_FILE"]=isset($_ARGS["TP_FILE"])?$_ARGS["TP_FILE"]:'img';

	ini_set("max_execution_time",0);
	if($control_type==0) 		$handle = new Upload($_FILES[$control]);
	elseif($control_type==1) 	$handle = new Upload($control);
	if ($handle->uploaded) {

		$names=array();
		$dir_dest='/var/www/siie/temp';
		if($tipo=='img'){

			$nombre=sprintf("%s-%s-%s-%s",$_ARGS["MODULE"],$_ARGS["OBJECT"],'org',$_ARGS["TP_FILE"]);
			//IMAGEN ORIGINAL
			if($handle->file_src_name_ext=='png'){
				$handle->file_new_name_body		=$nombre;
				$handle->file_overwrite			=true;
				$handle->Process($dir_dest);
				$mime='image/png';
			}
			elseif($handle->file_src_name_ext=='jpg'){
				$handle->file_new_name_body		=$nombre;
				$handle->file_overwrite			=true;
				$handle->Process($dir_dest);
				$mime='image/jpeg';
			}
			else{
				$handle->file_new_name_body		=$nombre;
				$handle->file_overwrite			=true;
				$handle->Process($dir_dest);

				$finfo = finfo_open(FILEINFO_MIME_TYPE); 	
				$mime=finfo_file($finfo, $handle->file_dst_pathname);
				finfo_close($finfo);
			}
			$names['org']["name"]=$handle->file_dst_name;
			$names['org']["ext"]=$handle->file_dst_name_ext;
			$names['org']["isImg"]=1;
			$names['org']["mime"]=$mime;
			$names['org']["width"]=$handle->image_dst_x;
			$names['org']["height"]=$handle->image_dst_y;
			$names['org']["size"]=filesize($handle->file_dst_pathname);
			$names['org']["source"]=$handle->file_dst_pathname;
			


			
			//Imagen Thumnail 1
			$nombre=sprintf("%s-%s-%s-%s",$_ARGS["MODULE"],$_ARGS["OBJECT"],'tn1',$_ARGS["TP_FILE"]);
			$handle->file_new_name_body		=$nombre;
			$handle->image_resize          	=true;
			$handle->image_ratio_y         	=true;
			$handle->image_x               	=120;
			$handle->file_overwrite			=true;
			$handle->image_convert         	='jpg';
			$handle->jpeg_quality			=85;
			$handle->Process($dir_dest);
			$mime='image/jpeg';
			$names['tn1']["name"]=$handle->file_dst_name;
			$names['tn1']["ext"]=$handle->file_dst_name_ext;
			$names['tn1']["isImg"]=1;
			$names['tn1']["mime"]=$mime;
			$names['tn1']["width"]=$handle->image_dst_x;
			$names['tn1']["height"]=$handle->image_dst_y;
			$names['tn1']["size"]=filesize($handle->file_dst_pathname);
			$names['tn1']["source"]=$handle->file_dst_pathname;

			
			//Imagen Thumnail 2
			$nombre=sprintf("%s-%s-%s-%s",$_ARGS["MODULE"],$_ARGS["OBJECT"],'tn2',$_ARGS["TP_FILE"]);
			$handle->file_new_name_body		=$nombre;
			$handle->image_resize          	=true;
			$handle->image_ratio_y         	=true;
			$handle->image_x               	=260;
			$handle->file_overwrite			=true;
			$handle->image_convert         	='jpg';
			$handle->jpeg_quality			=85;
			$handle->Process($dir_dest);
			$mime='image/jpeg';
			$names['tn2']["name"]=$handle->file_dst_name;
			$names['tn2']["ext"]=$handle->file_dst_name_ext;
			$names['tn2']["isImg"]=1;
			$names['tn2']["mime"]=$mime;
			$names['tn2']["width"]=$handle->image_dst_x;
			$names['tn2']["height"]=$handle->image_dst_y;
			$names['tn2']["size"]=filesize($handle->file_dst_pathname);
			$names['tn2']["source"]=$handle->file_dst_pathname;


			//Imagen Thumnail 3
			$nombre=sprintf("%s-%s-%s-%s",$_ARGS["MODULE"],$_ARGS["OBJECT"],'tn3',$_ARGS["TP_FILE"]);
			$handle->file_new_name_body		=$nombre;			
			$handle->file_overwrite			=true;
			$handle->image_convert         	='jpg';
			$handle->jpeg_quality			=80;
			$handle->Process($dir_dest);
			$mime='image/jpeg';
			$names['tn3']["name"]=$handle->file_dst_name;
			$names['tn3']["ext"]=$handle->file_dst_name_ext;
			$names['tn3']["isImg"]=1;
			$names['tn3']["mime"]=$mime;
			$names['tn3']["width"]=$handle->image_dst_x;
			$names['tn3']["height"]=$handle->image_dst_y;
			$names['tn3']["size"]=filesize($handle->file_dst_pathname);
			$names['tn3']["source"]=$handle->file_dst_pathname;
		}
		else{
			$nombre=sprintf("%s-%s-%s-%s",$_ARGS["MODULE"],$_ARGS["OBJECT"],'org',$_ARGS["TP_FILE"]);
			$handle->file_new_name_body		=$nombre;
			$handle->file_overwrite			=true;
			$handle->Process($dir_dest);

			$finfo = finfo_open(FILEINFO_MIME_TYPE); 	
			$mime=finfo_file($finfo, $handle->file_dst_pathname);
			finfo_close($finfo);
			$names['org']["name"]=$handle->file_dst_name;
			$names['org']["ext"]=$handle->file_dst_name_ext;
			$names['org']["isImg"]=$handle->file_is_image!=1?0:1;
			$names['org']["mime"]=$mime;
			$names['org']["width"]=0;
			$names['org']["height"]=0;
			$names['org']["size"]=filesize($handle->file_dst_pathname);
			$names['org']["source"]=$handle->file_dst_pathname;

		}
	}
	DeleteFiles($AwsS3,$dbEmpresa,$_ARGS);
	try{
		$client = $AwsS3->get('s3');
		$s="INSERT INTO adm_files
				(F_MODULE
				,ID_OBJECT
				,F_SUB_EXT
				,F_EXT
				,F_TP_FILE
				,F_ISIMG
				,F_NAME
				,F_SIZE
				,F_MIME
				,F_WIDTH
				,F_HEIGHT
				,F_HASH
				,F_DATE)
		VALUES(:F_MODULE
				,:ID_OBJECT
				,:F_SUB_EXT
				,:F_EXT
				,:F_TP_FILE
				,:F_ISIMG
				,:F_NAME
				,:F_SIZE
				,:F_MIME
				,:F_WIDTH
				,:F_HEIGHT
				,:F_HASH
				,UTC_TIMESTAMP())";
		$req = $dbEmpresa->prepare($s); 

		foreach ($names as $key => $value) {
			$F_HASH=sha1_file($value['source']);
			$req->bindValue(":F_MODULE",$_ARGS["MODULE"]);
			$req->bindValue(":ID_OBJECT",$_ARGS["OBJECT"]);
			$req->bindValue(":F_SUB_EXT",$key);
			$req->bindValue(":F_EXT",$value['ext']);
			$req->bindValue(":F_TP_FILE",$_ARGS["TP_FILE"]);
			$req->bindValue(":F_ISIMG",$value['isImg']);
			$req->bindValue(":F_NAME",$value['name']);
			$req->bindValue(":F_SIZE",$value['size']);
			$req->bindValue(":F_MIME",$value['mime']);
			$req->bindValue(":F_WIDTH",$value["width"]);
			$req->bindValue(":F_HEIGHT",$value["height"]);
			$req->bindValue(":F_HASH",$F_HASH);
			$req->execute();

			$results = $client->putObject(array(
			    'Bucket'     => $_ARGS['S3_BUCKET'],
			    'Key'        => sprintf("/%s-%s/%s",$_ARGS["PROYECTO"],$_ARGS["EMPRESA"],$value['name']),
			    'SourceFile' => $value['source'],
			    'Metadata'   => array(
			        'isImg' => $value['isImg'],
			        'module' => $_ARGS["MODULE"],
			        'sub_ext' => $key,
			        'tpfile' => $_ARGS["TP_FILE"]
			    )
			));
			//@unlink($value['source']);
		}
	}
	catch (Exception $e){
		ErrMSG($e,$_ARGS);
	}
	$Info=$names;
	@unlink($_FILES[$control]);
}
function DeleteFiles(&$AwsS3,$dbEmpresa,$_ARGS){
	$names=array();

	$_ARGS["TP_FILE"]=isset($_ARGS["TP_FILE"])?$_ARGS["TP_FILE"]:'img';
	$s="SELECT * FROM adm_files 
		WHERE F_MODULE=:F_MODULE AND ID_OBJECT=:ID_OBJECT AND F_TP_FILE=:F_TP_FILE";
	$req = $dbEmpresa->prepare($s); 
	$req->bindValue(":F_MODULE",$_ARGS["MODULE"]);
	$req->bindValue(":ID_OBJECT",$_ARGS["OBJECT"]);
	$req->bindValue(":F_TP_FILE",$_ARGS["TP_FILE"]);
	$req->execute();
	while($reg = $req->fetch()){
		$name=sprintf("%s-%s-%s-%s.%s",$reg["F_MODULE"],$reg["ID_OBJECT"],$reg["F_SUB_EXT"],$reg["F_TP_FILE"],$reg["F_EXT"]);
		$names[]=sprintf("/%s-%s/%s",$_ARGS["PROYECTO"],$_ARGS["EMPRESA"],$name);
	}
	if(count($names)>0){
		$client = $AwsS3->get('s3');		
		foreach ($names as $key => $value) {
			$results = $client->deleteObject(array(
			    'Bucket'  => $_ARGS['S3_BUCKET'],
			    'Key' => $value
			));
		}
		$s="DELETE FROM adm_files WHERE F_MODULE=:F_MODULE AND ID_OBJECT=:ID_OBJECT AND F_TP_FILE=:F_TP_FILE";
		$req = $dbEmpresa->prepare($s); 
		$req->bindValue(":F_MODULE",$_ARGS["MODULE"]);
		$req->bindValue(":ID_OBJECT",$_ARGS["OBJECT"]);
		$req->bindValue(":F_TP_FILE",$_ARGS["TP_FILE"]);
		$req->execute();
	}
}
function Fecha_MySQL($fecha,$formato='/',$delim='-'){
	$fecha_hora=explode(' ',$fecha);
	$fecha_array=explode($formato,$fecha_hora[0]);
	if(count($fecha_hora)==1)
		return(sprintf("%s%s%s%s%s",$fecha_array[2],$delim,$fecha_array[1],$delim,$fecha_array[0]));
	else 
		return(sprintf("%s%s%s%s%s %s",$fecha_array[2],$delim,$fecha_array[1],$delim,$fecha_array[0],$fecha_hora[1]));
}

function ArreglarHTML($texto='',$tipo=1){
	$ent[0] = array( 
		" " => "&nbsp;",
		"¡" => "&iexcl;",
		"¢" => "&cent;",
		"£" => "&pound;",
		"¤" => "&curren;",
		"¥" => "&yen;",
		"¦" => "&brvbar;",
		"§" => "&sect;",
		"¨" => "&uml;",
		"©" => "&copy;",
		"ª" => "&ordf;",
		"«" => "&laquo;",
		"¬" => "&not;",
		"­" => "&shy;",
		"®" => "&reg;",
		"¯" => "&macr;",
		"°" => "&deg;",
		"±" => "&plusmn;",
		"²" => "&sup2;",
		"³" => "&sup3;",
		"´" => "&acute;",
		"µ" => "&micro;",
		"¶" => "&para;",
		"·" => "&middot;",
		"¸" => "&cedil;",
		"¹" => "&sup1;",
		"º" => "&ordm;",
		"»" => "&raquo;",
		"¼" => "&frac14;",
		"½" => "&frac12;",
		"¾" => "&frac34;",
		"¿" => "&iquest;",
		"À" => "&Agrave;",
		"Á" => "&Aacute;",
		"Â" => "&Acirc;",
		"Ã" => "&Atilde;",
		"Ä" => "&Auml;",
		"Å" => "&Aring;",
		"Æ" => "&AElig;",
		"Ç" => "&Ccedil;",
		"È" => "&Egrave;",
		"É" => "&Eacute;",
		"Ê" => "&Ecirc;",
		"Ë" => "&Euml;",
		"Ì" => "&Igrave;",
		"Í" => "&Iacute;",
		"Î" => "&Icirc;",
		"Ï" => "&Iuml;",
		"Ð" => "&ETH;",
		"Ñ" => "&Ntilde;",
		"Ò" => "&Ograve;",
		"Ó" => "&Oacute;",
		"Ô" => "&Ocirc;",
		"Õ" => "&Otilde;",
		"Ö" => "&Ouml;",
		"×" => "&times;",
		"Ø" => "&Oslash;",
		"Ù" => "&Ugrave;",
		"Ú" => "&Uacute;",
		"Û" => "&Ucirc;",
		"Ü" => "&Uuml;",
		"Ý" => "&Yacute;",
		"Þ" => "&THORN;",
		"ß" => "&szlig;",
		"à" => "&agrave;",
		"á" => "&aacute;",
		"â" => "&acirc;",
		"ã" => "&atilde;",
		"ä" => "&auml;",
		"å" => "&aring;",
		"æ" => "&aelig;",
		"ç" => "&ccedil;",
		"è" => "&egrave;",
		"é" => "&eacute;",
		"ê" => "&ecirc;",
		"ë" => "&euml;",
		"ì" => "&igrave;",
		"í" => "&iacute;",
		"î" => "&icirc;",
		"ï" => "&iuml;",
		"ð" => "&eth;",
		"ñ" => "&ntilde;",
		"ò" => "&ograve;",
		"ó" => "&oacute;",
		"ô" => "&ocirc;",
		"õ" => "&otilde;",
		"ö" => "&ouml;",
		"÷" => "&divide;",
		"ø" => "&oslash;",
		"ù" => "&ugrave;",
		"ú" => "&uacute;",
		"û" => "&ucirc;",
		"ü" => "&uuml;",
		"ý" => "&yacute;",
		"þ" => "&thorn;",
		"ÿ" => "&yuml;",
		'"' => "&quot;",
		"'" => "&#39;",
		"<" => "&lt;",
		">" => "&gt;",
		"&" => "&amp;",
	); 
	
	$ent[1] = array( 
		"¨" => "&uml;",
		"©" => "&copy;",
		"ª" => "&ordf;",
		"«" => "&laquo;",
		"¬" => "&not;",
		"­" => "&shy;",
		"®" => "&reg;",
		"¯" => "&macr;",
		"°" => "&deg;",
		"±" => "&plusmn;",
		"²" => "&sup2;",
		"³" => "&sup3;",
		"´" => "&acute;",
		"µ" => "&micro;",
		"¶" => "&para;",
		"·" => "&middot;",
		"¸" => "&cedil;",
		"¹" => "&sup1;",
		"º" => "&ordm;",
		"»" => "&raquo;",
		"¼" => "&frac14;",
		"½" => "&frac12;",
		"¾" => "&frac34;",
		"¿" => "&iquest;",
		"À" => "&Agrave;",
		"Á" => "&Aacute;",
		"Â" => "&Acirc;",
		"Ã" => "&Atilde;",
		"Ä" => "&Auml;",
		"Å" => "&Aring;",
		"Æ" => "&AElig;",
		"Ç" => "&Ccedil;",
		"È" => "&Egrave;",
		"É" => "&Eacute;",
		"Ê" => "&Ecirc;",
		"Ë" => "&Euml;",
		"Ì" => "&Igrave;",
		"Í" => "&Iacute;",
		"Î" => "&Icirc;",
		"Ï" => "&Iuml;",
		"Ð" => "&ETH;",
		"Ñ" => "&Ntilde;",
		"Ò" => "&Ograve;",
		"Ó" => "&Oacute;",
		"Ô" => "&Ocirc;",
		"Õ" => "&Otilde;",
		"Ö" => "&Ouml;",
		"×" => "&times;",
		"Ø" => "&Oslash;",
		"Ù" => "&Ugrave;",
		"Ú" => "&Uacute;",
		"Û" => "&Ucirc;",
		"Ü" => "&Uuml;",
		"Ý" => "&Yacute;",
		"Þ" => "&THORN;",
		"ß" => "&szlig;",
		"à" => "&agrave;",
		"á" => "&aacute;",
		"â" => "&acirc;",
		"ã" => "&atilde;",
		"ä" => "&auml;",
		"å" => "&aring;",
		"æ" => "&aelig;",
		"ç" => "&ccedil;",
		"è" => "&egrave;",
		"é" => "&eacute;",
		"ê" => "&ecirc;",
		"ë" => "&euml;",
		"ì" => "&igrave;",
		"í" => "&iacute;",
		"î" => "&icirc;",
		"ï" => "&iuml;",
		"ð" => "&eth;",
		"ñ" => "&ntilde;",
		"ò" => "&ograve;",
		"ó" => "&oacute;",
		"ô" => "&ocirc;",
		"õ" => "&otilde;",
		"ö" => "&ouml;",
		"÷" => "&divide;",
		"ø" => "&oslash;",
		"ù" => "&ugrave;",
		"ú" => "&uacute;",
		"û" => "&ucirc;",
		"ü" => "&uuml;",
		"ý" => "&yacute;",
		"þ" => "&thorn;",
		"ÿ" => "&yuml;",
	); 
	
	return(strtr($texto, $ent[$tipo]));
}
function ApiConsole($opt=array(),$type,$url,$oargs=''){
	$return=array(); 
	$data_string=http_build_query($opt);
	if($oargs!='') $data_string.=$oargs;
	ob_start();

	if($type!='POST'){
		$data_string=http_build_query($opt);
		if($oargs!='') $data_string.=$oargs;                                 
		$ch = curl_init($url."?".$data_string); 
	}
	else{
		$ch = curl_init($url);
		if(count($_FILES)>0)
			curl_setopt($ch, CURLOPT_POSTFIELDS, $opt);  
		else
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);  
	}                                                    
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);                                                               
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($ch, CURLOPT_VERBOSE, 0);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
	curl_setopt($ch, CURLOPT_REFERER, $_SERVER["HTTP_HOST"]);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	
	$result = curl_exec($ch);
	
	curl_close($ch);
	ob_end_clean();
	$return=objectToArray(json_decode($result));

	return $return;
} 

function objectToArray($d) {
	if (is_object($d))
		$d = get_object_vars($d);
	if (is_array($d))
		return array_map(__FUNCTION__, $d);
	else
		return $d;
}
function ErrMSG($e=array(),$args=array()){
	include "mysql_valores.php";
	$_PROYECTO=$GLOBALS['_PROYECTO'];
	/*LOGS PROVISIONALES*/
	try{  





		$dbMat->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
		$dbMat->beginTransaction();		
		$line=$e->getLine();
		$file=$e->getFile();
		$err_str=$e->getMessage();
		$s="INSERT INTO log_error (ID_USUARIO,ALIAS,ID_PRODUCTO,ID_EMPRESA,FECHA_ERROR,LINE_PROTECTED,FILE_ERR,ERROR_DESC,ARGS) ".
					" VALUE('',:alias,'','',UTC_TIMESTAMP(),:line,:file,:err_str,:args)";
		$req = $dbMat->prepare($s);
		$req->bindParam(':alias', $alias);	
		$req->bindParam(':err_str', $err_str);
		$req->bindParam(':line', $line);	
		$req->bindParam(':file', $file);
		$req->bindParam(':args', json_encode($args)	);		
		$req->execute();		
		$id=$dbMat->lastInsertId();		
		$dbMat->commit();

		$ERR_URL='http://'.$_SERVER['HTTP_HOST'].'/_debugger/?id_err_log='.$id;
		
		$attachments=array(		'fallback'	=>"Error plataforma SIIE: <$ERR_URL| Abrir Debbuger>"
							,	'pretext'	=>"Error plataforma SIIE: <$ERR_URL| Abrir Debbuger>"
							,	'color'		=>"#FF0000"
							,	'fields'	=>array(
													array("title" 	=> "Descripción:"
										               ,"value"	 	=>$err_str
										               ,"short"      =>false)

													,array("title" 	=> "Proyecto:"
										               ,"value"	 	=>$_PROYECTO
										               ,"short"      =>false)
													,array("title" 	=> "Archivo:"
										               ,"value"	 	=>$file
										               ,"short"      =>false)
													,array("title" 	=> "Línea:"
										               ,"value"	 	=>$line
										               ,"short"      =>false)
													,array("title" 	=> "Argumentos:"
										               ,"value"	 	=>json_encode($args)
										               ,"short"      =>false))
							);

		$opt=array(		'channel'		=>	"#bugs"
					,	'username'		=>	"SIIE"
					,	'text'			=>	"Error en Proyecto #$_PROYECTO (".$_SERVER['HTTP_HOST'].")"
					,	'attachments'	=>	array($attachments)
					,	'icon_url'		=>	"http://siie.co/img/slack/siie_err.png");
		$url='https://hooks.slack.com/services/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';

		SlackMSG($opt,$url);


		return $id;
	}
	catch (Exception $e){				
		$dbMat->rollBack();
	}	
}
function CreaConsulta($c_sha,$reqTitulos,&$output,&$sArmado){	
	try{
		$output["scons"]=array();
		$output["cols"]=array();
		$output["scons"]["total_esp"]=0;
		$i=0;
		while($regTitulos = $reqTitulos->fetch()){
			if($i==0){
				$output["scons"]["cnf"]=$regTitulos["ID_VENTANA"];
				$output["scons"]["tabla"]=$regTitulos["TABLA_VENTANA"];
				$output["scons"]["hab_campo"]=$regTitulos["TABLA_HAB_VENTANA"];
				$output["scons"]["ord_campo"]=$regTitulos["TABLA_ORDER_VENTANA"];
				$output["scons"]["tit_campo"]=$regTitulos["SNAME_VENTANA"];
				$output["scons"]["id_campo"]=$regTitulos["TABLA_ID_VENTANA"];
				$output["scons"]["idioma"]=$regTitulos["IDI_VENTANA"];
				$output["scons"]["mempresa"]=$regTitulos["MEMP_VENTANA"];
				$output["scons"]["tabla_titulo"]=$regTitulos["TITULO_VENTANA"];
				$output["scons"]["tabla_stitulo"]=$regTitulos["STITULO_VENTANA"];
				$output["scons"]["resumen"]=$regTitulos["RESU_VENTANA"];
			}	
			$output["scons"]["total_esp"]+=$regTitulos["TAMANO_CAMPO"];//DETERMINA EL TAMAÑO TOTAL
				
			$output["cols"]["Titulos"][]=$regTitulos["TITULO_CAMPO"];
			$output["cols"]["Cols"][]=$regTitulos["NOMBRE_CAMPO"];
			$output["cols"]["ToolTip"][]=$regTitulos["TOOLTIP_CAMPO"];
			$output["cols"]["Tamano"][]=$regTitulos["TAMANO_CAMPO"];
			$output["cols"]["option"][]=array(
												'tipo'=>$regTitulos["TIPO_CAMPO"]
											,	'tabla'=>$regTitulos["TABLA_CAMPO"]
											,	'idioma'=>$regTitulos["TIDI_CAMPO"]==1
											,	'mempresa'=>$regTitulos["TMEM_CAMPO"]==1
											,	'gempresa'=>$regTitulos["TGEMP_CAMPO"]==1
											,	'req'=>$regTitulos["REQ_CAMPO"]==1
											);
			$i++;
		}
		$sCol=implode(",",$output["cols"]["Cols"]);
		$sArmado=sprintf("SELECT %s,%s,%s FROM %s",$output["scons"]["id_campo"],$output["scons"]["hab_campo"],$sCol,$output["scons"]["tabla"]);
	}
	catch (Exception $e){
	}
}
function __distruct(){
  $this->childObject=null;
}
function cambiar_url($url,$tipo=1) {
 	if($tipo==1){
		$url = strtolower($url);
	    $buscar = array('%','-', '+',' ');
	    $url = str_replace ($buscar, '_', $url);
	    $buscar = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
	    $remplzr = array('a', 'e', 'i', 'o', 'u', 'n');
	    $url = str_replace ($buscar, $remplzr, $url);
	  	$buscar = array('/[^a-z0-9-<>]/', '/[-]+/', '/<[^>]*>/');
	    $remplzr = array('', '_', '');
	    $url = preg_replace ($buscar, $remplzr, $url);
	}
	else{
		$url = strtolower($url);
	    $buscar = array('%','_', '+',' ');
	    $url = str_replace ($buscar, '-', $url);
	    $buscar = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
	    $remplzr = array('a', 'e', 'i', 'o', 'u', 'n');
	    $url = str_replace ($buscar, $remplzr, $url);
	    $buscar = array('/[^a-z0-9-<>]/', '/[-]+/', '/<[^>]*>/');
	    $remplzr = array('', '-', '');
	    $url = preg_replace ($buscar, $remplzr, $url);
	}
    return $url;
}
function FracesArray($frase){
	$analisis=array();
	$palabras=explode(" ",$frase);
	$cont=sizeof($palabras);
	for($l=0;$l<$cont;$l++){
		for($i=$l;$i<$cont;$i++){
			$p=array();
			for($k=$l;$k<=$i;$k++){
				$p[]=$palabras[$k];
			}
			$analisis[]=implode(" ",$p);
		}
	}
	return($analisis);
}
function CambioDivisas($mon1,$mon2,$cant){
	$GOOGLE_URL = "http://finance.google.com/finance/converter?a=%d&from=%s&to=%s";
	$ch = curl_init ();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, sprintf($GOOGLE_URL,$cant,$mon1,$mon2));
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	$response = curl_exec($ch);
	curl_close($ch);
	
	$domaine = strstr($response, '&nbsp;');
	$domaine = strstr($domaine, $mon1);
	$domaines=explode("= <span class=bld>",$domaine);
	$total=explode("$mon2</span>",$domaines[1]);
	if (isset($total[0])){
		if (trim($total[0])==''){
			return 0;
		}
		else{
			return $total[0];
		}
	}
	else{
		return 0;
	}
}
function DelDir($carpeta){
	try{
		foreach(glob($carpeta . "/*") as $archivos){
			if (is_dir($archivos)) 	DelDir($archivos);
			else 					unlink($archivos);
		}
		rmdir($carpeta);
	}
	catch (Exception $e){		
	}
}

function InterpolacionLineal($Xa,$Xb,$Ya,$Yb,$X){
	if($Xb!=$Xa)	$Y=$Ya+(($X-$Xa)*(($Yb-$Ya)/($Xb-$Xa)));
	else 			$Y=0;
	return $Y;
}
function escapeJsonString($value) {
    # list from www.json.org: (\b backspace, \f formfeed)    
    $escapers =     array("\\",     "/",   "\"",  "\n",  "\r",  "\t", "\x08", "\x0c");
    $replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t",  "\\f",  "\\b");
    $result = str_replace($escapers, $replacements, $value);
    return $result;
 }


//iCal
function dateToCal($fecha,$TZ,$formato="-") {
	$tz_array=explode(':',$TZ);
	$fecha_hora=explode(' ',$fecha);
	$fecha_hora[1]=$fecha_hora[1]==""?'00:00:00':$fecha_hora[1];
	$hora_array=explode(":",$fecha_hora[1]);
	$fecha_array=explode($formato,$fecha_hora[0]);
	return(sprintf("%s%s%sT%s%s%sZ",
						$fecha_array[0],$fecha_array[1],$fecha_array[2],
						$hora_array[0],$hora_array[1],$hora_array[2]==""?'00':$hora_array[2]));

}
function escapeString($string) {
  return preg_replace('/([\,;])/','\\\$1', $string);
}
function rec_img ($url_origen,$archivo_destino){  
	$mi_curl = curl_init ($url_origen);  
	$fs_archivo = fopen ($archivo_destino, "w");  
	curl_setopt ($mi_curl, CURLOPT_FILE, $fs_archivo);  
	curl_setopt ($mi_curl, CURLOPT_HEADER, 0);  
	curl_exec ($mi_curl);  
	curl_close ($mi_curl);  
	fclose ($fs_archivo);  
}
function ExtraerURL($_URL){
	/*************************/
	/*************************/
	///////SI ES UN EMBEBIDO///////
	$patron = '@src="(.*?)"@';
	$match=preg_match($patron,$_URL,$matches);
	if($match>=1) 	$_URL=$matches[1];
	if(substr($_URL,0,2)!='//' && substr($_URL,0,4)!='http') $_URL='//'.$_URL;
	///////OLD EMBEBIDO///////
	$patron = '@value="(.*?)"@';
	$match=preg_match($patron,$_URL,$matches);
	if($match>=1) 	$_URL=$matches[1];
	if(substr($_URL,0,2)!='//' && substr($_URL,0,4)!='http') $_URL='//'.$_URL;
	///////SI ES DE YOUTUBE///////
	$patron = '@v=([^"]+)@';
	$match=preg_match($patron,$_URL,$matches);
	if($match>=1)  $_URL=sprintf("//www.youtube-nocookie.com/embed/%s",$matches[1]);	
	///////SI ES VIMEO///////
	$patron = '@vimeo.com/([^"]+)@';
	$match=preg_match($patron,$_URL,$matches);
	if($match>=1)  $_URL=sprintf("//player.vimeo.com/%s",$matches[1]);
	/*************************/
	/*************************/
	return($_URL);
}

function ImgName($PROYECTO,$EMPRESA,$MODULO,$OBJETO,$TP='img',$EXT='png',$All=true,$Cual=''){
	$item=array();
	$item["big"]=sprintf("/%s-%s/%s-%s-org-%s.%s",$PROYECTO,$EMPRESA,$MODULO,$OBJETO,$TP,$EXT); 
	$item["t01"]=sprintf("/%s-%s/%s-%s-tn1-%s.jpg",$PROYECTO,$EMPRESA,$MODULO,$OBJETO,$TP); 
	$item["t02"]=sprintf("/%s-%s/%s-%s-tn2-%s.jpg",$PROYECTO,$EMPRESA,$MODULO,$OBJETO,$TP); 
	$item["t03"]=sprintf("/%s-%s/%s-%s-tn3-%s.jpg",$PROYECTO,$EMPRESA,$MODULO,$OBJETO,$TP); 
	if($All) 	return $item;
	else 	 	return $item[$Cual];
}
function ImgBlanc($M_IMG,$ImgParam){
	$PROYECTO=$ImgParam['PROYECTO'];
	$EMPRESA=$ImgParam['EMPRESA'];
	$MODULO=$ImgParam['MODULO'];
	$OBJETO=$ImgParam['OBJETO'];
	$TP=$ImgParam['TP'];
	$EXT=$ImgParam['EXT'];
	$All=$ImgParam['All'];
	$Cual=$ImgParam['Cual'];
	if($M_IMG==0){		
		$return=ImgName($PROYECTO,$EMPRESA,0,0,'NoImageApp','png',$All,$Cual);
	}
	else{
		$return=ImgName($PROYECTO,$EMPRESA,$MODULO,$OBJETO,$TP,$EXT,$All,$Cual);
	}
	if($All)	$return['id']=$M_IMG;
	return $return;
}
function GetPrefixURL($dbEmpresa,$_VALUE='S3_URL1'){
	$s='SELECT CONFIG_VALOR FROM adm_configuracion_gral WHERE CONFIG_NOMBRE=:_VALUE LIMIT 1';
	$reqT = $dbEmpresa->prepare($s);	
	$reqT->bindParam(':_VALUE', $_VALUE);
	$reqT->execute();
	if($regT = $reqT->fetch())
		return $regT['CONFIG_VALOR'];
	else
		return '';
}
function ActVersions(&$dbEmpresa,$sqlCons,$_CLIENTE,$tabla_name){

	//LANDING PAGES
	$s=$sqlCons[1][99]." WHERE adm_api_tablas.NAME_TABLA=:tabla_name LIMIT 1";

	$reqT = $dbEmpresa->prepare($s);	
	$reqT->bindParam(':id_mempresa', $_CLIENTE);
	$reqT->bindParam(':tabla_name', $tabla_name);
	$reqT->execute();
	while($regT = $reqT->fetch()){
		$tabla=$regT["ID_TABLA"];
		if($regT["MEMPRESA"]==1){
			$s="INSERT INTO adm_api_tablas_versiones_empresa (ID_TABLA,ID_MEMPRESA,FECHA,VERSION) 
				VALUES (:tabla,:_CLIENTE,UTC_TIMESTAMP(),1)
				ON DUPLICATE KEY UPDATE VERSION=VERSION+1";
			$req = $dbEmpresa->prepare($s);
			$req->bindParam(':_CLIENTE', $_CLIENTE);
			$req->bindParam(':tabla', $tabla);
			$req->execute();
		}
		else{
			$s="INSERT INTO adm_api_tablas_versiones (ID_TABLA,FECHA,VERSION) 
				VALUES (:tabla,UTC_TIMESTAMP(),1)
				ON DUPLICATE KEY UPDATE VERSION=VERSION+1";
			$req = $dbEmpresa->prepare($s);
			$req->bindParam(':tabla', $tabla);
			$req->execute();
		}
	}
}
function PrintInArray($reqPtabla){
	$send=array();
	$kk=0;
	while($regPtabla = $reqPtabla->fetch()){					
		foreach($regPtabla as $name_data => $valor_data){
			if(!is_numeric($name_data)) $send[$kk][$name_data]=$valor_data;
		}
		$kk++;
	}
	return ($send);
}
/* BORRAR */
function PIAImagesOLD(&$datas,$has,$NewVar,$_PROYECTO,$_EMPRESA,$IdMod,$id,$type,$ext,$hash){
	if(count($datas)>0){
		foreach ($datas as $key => &$data) {
			$data[$NewVar]=ImgBlancTEMP($data[$has],array(
                                    'PROYECTO'  =>$_PROYECTO
                                ,   'EMPRESA'   =>$_EMPRESA
                                ,   'MODULO'    =>$IdMod
                                ,   'OBJETO'    =>$data[$id]
                                ,   'TP'        =>$type
                                ,   'EXT'       =>$data[$ext]
                                ,   'All'       =>true));
		}
	}
}
function ImgBlancTEMP($M_IMG,$ImgParam){
	$PROYECTO=$ImgParam['PROYECTO'];
	$EMPRESA=$ImgParam['EMPRESA'];
	$MODULO=$ImgParam['MODULO'];
	$OBJETO=$ImgParam['OBJETO'];
	$TP=$ImgParam['TP'];
	$EXT=$ImgParam['EXT'];
	$All=$ImgParam['All'];
	$Cual=$ImgParam['Cual'];
	if($M_IMG==0){		
		$return=ImgName($PROYECTO,$EMPRESA,0,0,'NoImageApp','png',$All,$Cual);
	}
	else{
		$return=ImgName($PROYECTO,$EMPRESA,$MODULO,$OBJETO,$TP,$EXT,$All,$Cual);
	}
	return $return;
}
/**/
function PIAImages(&$datas,$has,$NewVar,$_PROYECTO,$_EMPRESA,$IdMod,$id,$type,$ext,$hash){
	if(count($datas)>0){
		foreach ($datas as $key => &$data) {
			$data[$NewVar]=ImgBlanc($data[$has],array(
                                    'PROYECTO'  =>$_PROYECTO
                                ,   'EMPRESA'   =>$_EMPRESA
                                ,   'MODULO'    =>$IdMod
                                ,   'OBJETO'    =>$data[$id]
                                ,   'TP'        =>$type
                                ,   'EXT'       =>$data[$ext]
                                ,   'All'       =>true));
			$data[$NewVar]['hash']=$data[$hash];
		}
	}
}
function OrderPrint($item){
	$return_array=array();
	foreach ($item as $key => $value) {
		$return_array[]=$value;
	}
	return $return_array;
}
function identical_values( $arrayA , $arrayB ) { 
    sort( $arrayA ); 
    sort( $arrayB ); 
    return $arrayA == $arrayB; 
} 
function PrintErr($ERR,$type=1){
	if($type==1){
		$salidas["status"]["error"]=true;	
		$salidas["status"]["reload"]=false;
		$salidas["status"]["close"]=false;
		$salidas["status"]["words"]=false;	
		$salidas["mensaje"]=$ERR;
		echo json_encode($salidas);
		exit(0);
	}
	elseif($type==2){
		echo 	'<div class="error">';
		foreach ($ERR as $key => $value) {
			echo '	<div data-txtid="'.$value.'"></div>';
		}
		echo 	'</div>';
	}
}
function print_paginacion(&$salidas,$Total,$PagActual,$idMaxItem=1,$idMaxPags=1){
	include "variables.php";
	$Paginas=intval($Total/$NMaxItems[$idMaxItem])+(($Total%$NMaxItems[$idMaxItem])!=0?1:0); // CANTIDAD DE PAGINAS
	$Iin=($PagActual-intval($NMaxPags[$idMaxPags]/2))<=0?1:$PagActual-intval($NMaxPags[$idMaxPags]/2);
	$Ifin=($Iin+($NMaxPags[$idMaxPags]-1))>$Paginas?$Paginas:($Iin+($NMaxPags[$idMaxPags]-1));
	//AJUSTE
	$Ajuste=($NMaxPags[$idMaxPags]-1)-($Ifin-$Iin);
	$Iin=($Iin-$Ajuste)<=0?1:($Iin-$Ajuste);

	if($Total>$NMaxItems[$idMaxPags]){  	
		for($i=$Iin;$i<=$Ifin;$i++){
			$sInd=($i-$Iin);
			$salidas["paginacion"]["paginas"][$sInd]["name"]=$i;
			$salidas["paginacion"]["paginas"][$sInd]["pagina"]=$i;
			
			if($i==$PagActual) 	$salidas["paginacion"]["paginas"][$sInd]["tipo"]=6;
			else				$salidas["paginacion"]["paginas"][$sInd]["tipo"]=5;
		}
		
		if ($PagActual > 1){
			$salidas["paginacion"]["first"][0]["name"]="Primeras";
			$salidas["paginacion"]["first"][0]["pagina"]=1;		
			$salidas["paginacion"]["first"][0]["tipo"]=5;	
			
			$salidas["paginacion"]["prev"][0]["name"]="&laquo;";
			$salidas["paginacion"]["prev"][0]["pagina"]=$PagActual-1;
			$salidas["paginacion"]["prev"][0]["tipo"]=5;
			
		}		
		
		if ($PagActual < $Paginas) {
			$salidas["paginacion"]["next"][0]["name"]="&raquo;";
			$salidas["paginacion"]["next"][0]["pagina"]=$PagActual+1;
			$salidas["paginacion"]["next"][0]["tipo"]=5;
	
			$salidas["paginacion"]["last"][0]["name"]="Ultimas";
			$salidas["paginacion"]["last"][0]["pagina"]=$Paginas;
			$salidas["paginacion"]["last"][0]["tipo"]=5;
			
		}
	}
	return "";
}
function print_pag(&$salidas,$PagActual,$CountItems,$MaxItemsNew){
	if ($PagActual > 1){		
		$salidas["paginacion"]["prev"][0]["name"]="&laquo;";
		$salidas["paginacion"]["prev"][0]["pagina"]=$PagActual-1;
		$salidas["paginacion"]["prev"][0]["tipo"]=5;
		
	}
	if ($CountItems>$MaxItemsNew) {
		$salidas["paginacion"]["next"][0]["name"]="&raquo;";
		$salidas["paginacion"]["next"][0]["pagina"]=$PagActual+1;
		$salidas["paginacion"]["next"][0]["tipo"]=5;		
	}
}
function isJson($string) {
	json_decode($string);
	return (json_last_error() == JSON_ERROR_NONE);
}
function JSON_PARSE($var){
	$is_array=is_array($var);
	if($is_array)
		$var_obj=$var;
	else{
		$var_dec=json_decode($var,true);
	 	if(json_last_error()==JSON_ERROR_NONE)
	 		$var_obj=$var_dec;
 		else
 			$var_obj=null;
 	}
 	return $var_obj;
}
class Helper_DateTimeZone extends DateTimeZone{

    final public static function tzOffsetToName($offset, $isDst = null)
    {
        if ($isDst === null)
        {
            $isDst = date('I');
        }

        $offset *= 3600;
        $zone    = timezone_name_from_abbr('', $offset, $isDst);

        if ($zone === false)
        {
            foreach (timezone_abbreviations_list() as $abbr)
            {
                foreach ($abbr as $city)
                {
                    if ((bool)$city['dst'] === (bool)$isDst &&
                        strlen($city['timezone_id']) > 0    &&
                        $city['offset'] == $offset)
                    {
                        $zone = $city['timezone_id'];
                        break;
                    }
                }

                if ($zone !== false)
                {
                    break;
                }
            }
        }    
        return $zone;
    }
}

function PrintTablas($ops,$titulos=array(),$body=array(),$footer=array()){
	
	$salidas=array();
	$salidas["tipo"]=isset($ops['tipo'])?$ops['tipo']:'tabla';
	$salidas["titulo"]=isset($ops['titulo'])?$ops['titulo']:'';
	if(isset($ops['id']))			$salidas["id"]=$ops['id'];
	if(isset($ops['display']))		$salidas["display"]=$ops['display'];		
	if(isset($ops['attr'])) 		$salidas["attr"]=$ops['attr'];
	if(isset($ops['css'])) 			$salidas["css"]=$ops['css'];
	if(isset($ops['css_tabla'])) 	$salidas["css_tabla"]=$ops['css_tabla'];


	$salidas["titulos"]=$titulos;
	$salidas["nItem"]=$body;
	$salidas["footer"]=$footer;
	return $salidas;
}

function RS_Class($rs){
	$RSArray=array();
	$RSArray[4]=array('class'=>'facebook','cont'=>'\f09a');
	$RSArray[5]=array('class'=>'twitter','cont'=>'\f099');
	$RSArray[6]=array('class'=>'instagram','cont'=>'\f16d');
	$RSArray[7]=array('class'=>'flickr','cont'=>'\f16e');
	$RSArray[8]=array('class'=>'foursquare','cont'=>'\f180');
	$RSArray[9]=array('class'=>'linkedin','cont'=>'\f0e1');
	$RSArray[10]=array('class'=>'behance','cont'=>'\f1b4');
	$RSArray[12]=array('class'=>'youtube','cont'=>'\f167');
	$RSArray[13]=array('class'=>'vimeo-square','cont'=>'\f194');
	$RSArray[14]=array('class'=>'tumblr','cont'=>'\f173');
	$RSArray[15]=array('class'=>'rss','cont'=>'\f09e');
	$RSArray[16]=array('class'=>'google-plus','cont'=>'\f0d5');	
	$RSArray[17]=array('class'=>'external-link','cont'=>'\f08e');
	return $RSArray[$rs];
}
function IdCitySel($stdop='op',$dominio){
	$dbEmpresa=$GLOBALS['dbEmpresa'];
	if($dominio==null)	$dominio=$_SERVER["HTTP_HOST"];
	$dominio_array=explode(".", $dominio);
	$subDominio=$dominio_array[0];

	$id_area=0;
	if($subDominio!=$stdop){
		$s='SELECT ID_AREA AS ID
			FROM t_area
			WHERE SLUG_AREA=:subDominio LIMIT 1';

		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':subDominio', $subDominio);	
		$req->execute();
		if($reg = $req->fetch()) $id_area=$reg["ID"];
	}
	return($id_area);
}
function SlackMSG($opt,$url){
	$type="POST";
	$payload=json_encode($opt);
	$ch = curl_init( $url );		
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));		
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	$result = curl_exec($ch);
	curl_close($ch);
}
function CalcRoute($dir1,$dir2,$KEY){

	$distance=0;
	$duration=0;


	$DIR1=rawurlencode($dir1);
	$DIR2=rawurlencode($dir2);
	$href="https://maps.googleapis.com/maps/api/directions/json?"
			."origin=$DIR1"
			."&destination=$DIR2"
			."&units=metric"
			."&key=$KEY";			
	$response=cURLdata($href);
	$routes=$response['routes'];
	$status=$response['status']=='OK';
	if(count($routes)&&$status){
		$leg=$routes[0]['legs'][0];
		
		$distance=$leg['distance']['value'];
		$duration=$leg['duration']['value'];

		$start_address=$leg['start_address'];
		$start_location=$leg['start_location'];	

		$end_address=$leg['end_address'];
		$end_location=$leg['end_location'];	

		//steps
		$steps=$leg['steps'];
		$step_send=array();
		foreach ($steps as $ord => $step) {
			$step_send[]=array(	'distance'=>$step['distance']['value']
							,	'duration'=>$step['duration']['value']
							,	'start_location'=>$step['start_location']
							,	'end_location'=>$step['end_location']
							,	'html_instructions'=>$step['html_instructions']
							,	'ord'=>$ord);
		}
		$bounds=$routes[0]['bounds'];
		$northeast=$bounds['northeast'];
		$southwest=$bounds['southwest'];


	}

	return(array(
			'distance'=>$distance
		,	'duration'=>$duration
		,	'status'=>$status
		,	'start_address'=>$start_address
		,	'start_location'=>$start_location
		,	'end_address'=>$end_address
		,	'end_location'=>$end_location
		,	'bounds'=>array('northeast'=>$northeast,'southwest'=>$southwest) 
		,	'steps'=>$step_send
		));
}

function GeoDecode($dir,$KEY){

	$DIR=rawurlencode($dir);
	$href="https://maps.googleapis.com/maps/api/geocode/json?"
			."address=$DIR"
			."&key=$KEY";			
	$response=cURLdata($href);

	$geometry=$response['results']['location']['geometry']['location'];
	$status=$response['status']=='OK';	
	$location=current($response['results'])['geometry']['location'];

	return(array(
			'location'=>$location
		,	'status'=>$status		
		));
}

function GetYouTube($input_line){
	preg_match("/(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=[0-9]\/)[^&\n]+|(?<=v=)[^&\n]+/", $input_line, $output_array);
	return current($output_array);
}

function set_email_adr($to,$email,&$varEmail){
	$emails_attr=explode(',',$email);	

	if(count($emails_attr)){
		$varEmail=array();
		$to_array=explode(',',$to);	
		foreach ($emails_attr as $key => $str_email) {
			$varEmail[]["name"]=$to_array[$key];	
			$varEmail[]["mail"]=$str_email;	
		}
		
	}
	else{
		$varEmail[0]["name"]=$to;	
		$varEmail[0]["mail"]=$email;
	}
}
function AddUnique(&$array,$value){
	$toReturn=in_array($value, $array);
	if (!$toReturn)
	{
	    $array[] = $value; 
	}
	return $toReturn;
}
?>
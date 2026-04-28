<?php
if($_main=='mail'){
	//PARAMETROS OBLIGATORIOS
	$s="UPDATE log_mail_send 
	SET VERIFICADO=1, FECHAV_SEND=UTC_TIMESTAMP() 
	WHERE SHA1(CONCAT(ID_SEND,ID_USUARIO))=:id";
	$req = $dbEmpresa->prepare($s);
	$req->bindParam(':id', $_GET["cnf"]);
	$req->execute();	
	header('Location: http://'.$_SERVER["HTTP_HOST"]."/");
	exit(0);
}
elseif($_main=='ipago'){
	include "pagos.php";
	exit(0);
}
elseif($_main=='logout'){
	$s="UPDATE adm_usuarios_reg
		SET CERRADO_U=1
	WHERE ID_SES=:ses";
	$reqSes = $dbEmpresa->prepare($s);	
	$reqSes->bindParam(':ses', $_SESSION["ses"]);
	$reqSes->execute();
	DescCookie();
	exit(0);
}
elseif($_main=='external'){
	DescCookie(2);
	$_token_a=$_REQUEST["_token_a"];
	$_token_b=$_REQUEST["_token_b"];
	$_session=$_REQUEST["_session"];
	$_ip=$_REQUEST["_ip"];

	if($_token_a!="" && $_token_b!=''){
		$hoyOBJ = new DateTime();
		$hoySTR=$hoyOBJ->format('Y-m-d H:i');

		$_sql_token_a="SHA1(CONCAT(adm_usuarios.ID_USUARIO,'x',adm_usuarios.PASSWORD_U,'x',adm_usuarios_reg.ID_SES))";
		$_sql_token_b="SHA1(CONCAT(adm_usuarios.CORREO_U,'x',adm_usuarios.ID_USUARIO,'x',adm_usuarios.PASSWORD_U,'x',adm_usuarios_reg.ID_SES))";
		$s = "SELECT				 
				adm_usuarios.PASSWORD_U,
				adm_usuarios.CORREO_U
			FROM adm_usuarios
			LEFT JOIN adm_usuarios_reg ON 
						adm_usuarios_reg.ACTIVA_U=10 AND adm_usuarios_reg.CERRADO_U=0 AND
						adm_usuarios_reg.ID_USUARIO=adm_usuarios.ID_USUARIO AND
						adm_usuarios_reg.IP_U=:_ip AND
						IF(adm_usuarios_reg.TYPE_U=1,
							'$hoySTR' BETWEEN adm_usuarios_reg.FECHA_U AND adm_usuarios_reg.FECHAF_U,
							adm_usuarios_reg.SESSION_U=:_session)

			WHERE $_sql_token_a=:_token_a AND $_sql_token_b=:_token_b AND adm_usuarios.HAB_U=0 LIMIT 1";	
		$req = $dbEmpresa->prepare($s);	
		$req->bindParam(':_token_a', $_token_a);
		$req->bindParam(':_token_b', $_token_b);	
		$req->bindParam(':_ip', $_ip);	
		$req->bindParam(':_session', $_session);			
		$req->execute();
		if($reg = $req->fetch()){	
			$usuario = $reg['CORREO_U'];
			$passw = $reg['PASSWORD_U'];	
			$validar=validacion($dbEmpresa,$usuario,$passw,0);
			$error=$validar;	

		}
	}
	header("Location: /");
}
elseif(($_main=='twitter')||($_main=='facebook')||($_main=='linkedin')){
	include "rs.php";
	exit(0);
}
elseif($_main=="verification"){//VERIFICAR
	if($_GET["code"]!=""){	
		$s="SELECT adm_usuarios.ID_USUARIO,
					adm_usuarios.CORREO_U,
					adm_usuarios.NOMBRE_U
			FROM adm_usuarios
			WHERE CONCAT(SHA1(CONCAT(ID_USUARIO,CORREO_U)),MD5(CONCAT(ID_USUARIO,CORREO_U)),SHA1(ID_USUARIO))=:code AND VERIF_U=0";		
		$req = $dbEmpresa->prepare($s);
		$req->bindParam(':code', $_GET["code"]);
		$req->execute();
		if($reg = $req->fetch()){		
			$s="UPDATE adm_usuarios
				SET VERIF_U=1 
				WHERE adm_usuarios.ID_USUARIO=:id";
			$reqA = $dbEmpresa->prepare($s);
			$reqA->bindParam(':id', $reg["ID_USUARIO"]);
			$reqA->execute();
			try{
				/*******SEND EMAIL***********/
				$to=array();
				$to[0]["mail"]=$reg["CORREO_U"];
				$to[0]["name"]=$reg["NOMBRE_U"];
				$Asunto=$Email[1][5]['title'];
				$html_cont=sprintf($Email[1][5]['body'],$reg["NOMBRE_U"]);
				$rtamail=send_email_srv($_SESSION["EMPRESA"],$Asunto,$html_cont,$to,array(),array(),true,$Email[1][5]['alt']);
				/*******SEND EMAIL***********/

			}
			catch (Exception $e){			
			}			
		}
	
	}
	if($_PARAMETROS["LWSERVICE"]!="")	header('Location: '.$_PARAMETROS["LWSERVICE"]);
	else 								header('Location: /');
	exit(0);
}
else{
	
}
?>
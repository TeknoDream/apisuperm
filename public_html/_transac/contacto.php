<?php 
session_start();

include "../phplib/consultas.php";
include "../phplib/funciones.php";
include "../phplib/mysql_valores.php";

include "../phplib/mail/sendmail.php";
include "../phplib/plantilla/cuerpomail.php";

$result=$_POST;
$key=$_SESSION[$result['key']];

if($key!=$_SERVER["REMOTE_ADDR"]) exit(0);

$result=array_map('trim', $result);
$nombres = $result['nombres'];
$correo = $result['correo'];
$telefono = $result['telefono'];
$mensaje = $result['mensaje'];

$mail_valido=checkmail($correo);
$error=0;
if(($nombres=='')||(!$mail_valido)) $error=502;

if($error==0){
	/*MENSAJE A CONTACTO*/
	try{  				
		$dbMat->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
		$dbMat->beginTransaction();
		$Asunto=utf8_decode("Mensaje de Contacto desde SIIE");
			
		$MensajeE=sprintf($mail[0],
		$nombres,$nombres,$correo,$telefono,nl2br(htmlentities(utf8_decode($_POST['mensaje']))));
		
		$to="contacto@motumdata.com";
		$to_nombre="Contacto SIIE Online";
		
		enviar_correo($Asunto,'',$MensajeE,true,$to,$to_nombre);	
		/*MENSAJE A PERSONA*/
		$Asunto=utf8_decode("Mensaje de Contacto desde SIIE");
			
		$MensajeE=sprintf($mail[1],
		$nombres,$nombres,$correo,$telefono,nl2br(htmlentities(utf8_decode($_POST['mensaje']))));
		
		$to=$correo;
		$to_nombre=$nombres;
		
		enviar_correo($Asunto,'',$MensajeE,true,$to,$to_nombre);	
		
		
		$s="INSERT INTO adm_contacto
		(NOMBRE_C,EMAIL_C,TELEFONO_C,MENSAJE_C,FECHA)
		VALUE(:nombres,:correo,:telefono,:mensaje,UTC_TIMESTAMP())";
		
		$reg = $dbMat->prepare($s); 
		$reg->bindParam(':nombres', $nombres);
		$reg->bindParam(':correo', $correo);
		$reg->bindParam(':telefono', $telefono);
		$reg->bindParam(':mensaje', $mensaje);
		$reg->execute();
	}
	catch (Exception $e){
		$dbMat->rollBack();
		$err_str=$e->getMessage();
	}

}

if($error!=0){
	$mensajes=$_SESSION["MENSAJES"];
	$mensaje_mos=$mensajes[$error][0];
	$salidas["msg"]=$mensaje_mos;
	$salidas["div"]=$mensajes[$error][1];
	$salidas["icon"]=$mensajes[$error][2];
	$salidas["edo"]=$error;

	echo json_encode($salidas);
	
}
else{
	$salidas["edo"]=0;
	echo json_encode($salidas);
}






?>
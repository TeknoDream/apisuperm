<?php
function writeArchivo($Data, $NomArchivo='___Mail_E_D__.log')
{
	$NomArchivo = '/var/www/siie/logs/'.$NomArchivo;
	$LogFile        = fopen($NomArchivo, 'a')        or die("Error creando archivo");  
	fwrite($LogFile, json_encode($Data,true). PHP_EOL) or die("Error en escritura de archivo");
	fclose($LogFile);
}


function send_email_srv($_PARAMETROS,$asunto,$cuerpo,$to=array(),$cc=array(),$bcc=array(),$esHTML=true,$cuerpoALT="",$attachments=array()){
	$mail = new PHPMailer;
	$mail->IsSMTP();                                    
	$mail->Host = $_PARAMETROS["M_HOST"];                 
	$mail->Port = $_PARAMETROS["M_PORT"];                                    
	$mail->SMTPAuth = true;                               
	$mail->Username = $_PARAMETROS["M_USERNAME"];
	$mail->Password = $_PARAMETROS["M_PASSWORD"];
	$mail->SMTPSecure = 'tls';

	$mail->From = $_PARAMETROS["M_FROMMAIL"];
	$mail->FromName = $_PARAMETROS["M_FROMNAME"];

	$mail->WordWrap = 50; 
	$mail->CharSet="UTF-8";

	$mail->IsHTML(true);

	$mail->Subject = $asunto;
	$mail->Body = ArreglarHTML($cuerpo);
	if($cuerpoALT!="")
		$mail->AltBody = $cuerpoALT;
	
	writeArchivo($asunto, '___Mail_Asunto__.log');
	writeArchivo($cuerpo, '___Mail_Body__.log');
	writeArchivo($to, '___Mail_TO__.log');
	writeArchivo($_PARAMETROS, '___Mail_Param__.log');

	if(count($to)>0){		
		for($i=0;$i<count($to);$i++){			
			if(isset($to[$i]["name"]))	$mail->AddAddress($to[$i]["mail"], $to[$i]["name"]);
			else 						$mail->AddAddress($to[$i]["mail"]);
		}
		if(count($cc)>0){			
			for($i=0;$i<count($cc);$i++){
				
				if(isset($cc[$i]["name"]))	$mail->AddCC($cc[$i]["mail"], $cc[$i]["name"]);
				else 						$mail->AddCC($cc[$i]["mail"]);
			}
		}
		if(count($bcc)>0){			
			for($i=0;$i<count($bcc);$i++){				
				if(isset($bcc[$i]["name"]))	$mail->AddBCC($bcc[$i]["mail"], $bcc[$i]["name"]);
				else 						$mail->AddBCC($bcc[$i]["mail"]);
			}
		}
	}	
	if(count($attachments)>0){
		foreach ($attachments as $key => $value) {
			if($value[1]=='')
				$mail->addAttachment($value[0]);
			else
				$mail->addAttachment($value[0],$value[1]);
		}
	}
	
	if(!$mail->Send()){
		$exito = 'Error: ' . $mail->ErrorInfo;
	}else{
		$exito =  true;
	
	}	
	return $exito;
}
?>
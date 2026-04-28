<?php
include("class.phpmailer.php");
function enviar_correo($asunto,$cuerpo,$cuerpoHTML,$esHTML=false,$to,$to_name,$to_cc='',$to_cc_name=''){
	$mail = new phpmailer();
	$mail->IsSMTP();
	$mail->Mailer = "smtp";
	$mail->SMTPSecure = 'ssl';
	$mail->Host = "vps.motumdata.com";//sco11.hostdime.com.co";
	$mail->Port = 465;
	$mail->SMTPAuth = true;
	$mail->WordWrap = 50; 
	$mail->CharSet="UTF-8";
	$mail->Username = "noreply+siieonline.com"; 
	$mail->Password = "EZ-acvO^l]wo";
	$mail->From = 'noreply@siieonline.com'; 
	$mail->FromName = "SIIE";
	$mail->IsHTML(true);
	$mail->Timeout=10;
	$mail->Subject = $asunto;
	$mail->Body = ArreglarHTML($cuerpoHTML);
	
	
	$to_array=explode(";",$to);
	$to_name_array=explode(";",$to_name);
	for($i=0;$i<count($to_array);$i++){
		$mail->AddAddress($to_array[$i], $to_name_array[$i]);
	}
	if($to_cc!=''){
		$to_cc_array=explode(";",$to_cc);
		$to_cc_name_array=explode(";",$to_cc_name);
		for($i=0;$i<count($to_cc_array);$i++){
			$mail->AddCC($to_cc_array[$i], $to_cc_name_array[$i]);
		}
	}
	if(!$mail->Send()) {
		$exito = 'Error: ' . $mail->ErrorInfo;
	}else{
		$exito =  'Mail enviado!';
	
	}	
	return $exito;
}
?>
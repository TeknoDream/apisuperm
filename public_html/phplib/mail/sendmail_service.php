<?php
function send_service($asunto,$cuerpoHTML,$to="",$to_name="",$to_cc="",$to_cc_name="",$to_bcc="",$to_bcc_name=""){
	include "sendgrid-php-master/SendGrid_loader.php";
	$to_array=explode(";",$to);
	$to_name_array=explode(";",$to_name);
	
	$_PARAMETROS=$_SESSION["EMPRESA"];
		
	$sendgrid = new SendGrid($_PARAMETROS["SG_USR"], $_PARAMETROS["SG_PASW"]);
	$mail = new SendGrid\Mail();
		
	for($i=0;$i<count($to_array);$i++)		$mail->addTo($to_array[$i],$to_name_array[$i]);
	
	//**************************//
	if($to_cc!=""){
		$tocc_array=explode(";",$to_cc);
		$tocc_name_array=explode(";",$to_cc_name);	
		for($i=0;$i<count($tocc_array);$i++)	$mail->addCc($tocc_array[$i],$tocc_name_array[$i]);
	}
	
	//**************************//
	if($to_bcc!=""){
		$tobcc_array=explode(";",$to_bcc);
		$tobcc_name_array=explode(";",$to_bcc_name);	
		for($i=0;$i<count($tobcc_array);$i++)	$mail->addBcc($tobcc_array[$i],$tobcc_name_array[$i]);
	}
	
	$mail->
		setFrom($_PARAMETROS["NR_MAIL"])->
		setFromName($_PARAMETROS["NR_MAIL_NAME"])->
		setReplyTo($_PARAMETROS["M_MAIL"])->
		addCategory($_PARAMETROS["M_CAT"])->
		setSubject($asunto)->
		setHtml(ArreglarHTML($cuerpoHTML));
		$sendgrid->smtp-> send($mail);
}
?>
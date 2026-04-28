<?php
$server_cn=$_PARAMETROS["LWSERVICE"];
$TEmail=$_PARAMETROS["S_NOMBCORTO"];
$FUrl=$_PARAMETROS["S_URLCORTA"];
$Slogan=$_PARAMETROS["S_SLOGAN"];
$OPUrl=$_PARAMETROS["WP_OPPAGE"];

$src_Imagen='http:'.$_PARAMETROS["S3_URL4"].ImgName($_PROYECTO,$_EMPRESA,0,0,'LogoApp','png',false,'big');
$css_Tabla_Imagen='background-color: #D6013E;padding:5px 0;color:#FFF';	

$css_Tabla='background-color: #F0F0F0;padding:0px;font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; border:1px solid #D6013E;';	
$css_Titulo='color:#545152;font-size:20px; padding:5px';
$css_Titulo2='color:#666;font-size:20px;margin:3px auto';
$css_Titulo3='color:#666;font-size:17px;margin:5px auto 1px auto';
$css_Titulo4='color:#666;font-size:16px;margin:5px auto 1px auto';

$css_Field='border:1px #CCC solid; padding:10px; margin:5px auto;';
$css_Legen='font-size:16px; color:#333; margin:2px 10px; padding:5px; font-weight:bold';
$css_Ol='padding:5px 10px;';

$css_Cuerpo='font-size:14px;padding:10px;margin:10px 10px 5px 10px;color:#333; text-align:center';	
$css_Cuerpo2='font-size:14px;padding:10px;margin:10px 10px 5px 10px;color:#333;';	
$css_Pie='background-color: #D6013E;margin:5px;color:#FFFFFF;font-size:12px; padding:5px; text-align:center';

$css_Letrap="font-size:10px;";
$css_a='color:#f41a21;';
$css_a2='color:#FFF;';


/***************************************/
/***************************************/
/***************************************/
/***************************************/
/***************************************/
/***************************************/
/***************************************/
$Email[1][1050]['alt']='Invitación Marca GPS';
$Email[1][1050]['title']="Tenemos un bono para ti";
$Email[1][1050]['body']='
<table cellspacing="0" cellpadding="0" border="0" width="100%%" style="'.$css_Tabla.'">
<tbody>
	<tr>
		<td style="'.$css_Tabla_Imagen.'" align="center">
			<a style="'.$css_a.'" href="'.$server_cn.'" title="'.$Slogan.'" style="border:none" target="_blank"><img style="'.$css_LOGO.'" src="'.$src_Imagen.'" /></a>
		</td>
	</tr>
	
	<tr>
		<td align="center">
			<h1 style="'.$css_Titulo.'">Bienvenido</h1>
		</td>
	</tr>
	
	<tr>
		<td>
			<p style="'.$css_Cuerpo.'">
			 	Tenemos un Bono de <strong>%s</strong> en %s para ti
			 	<br/>
			 	<img src="%s" alt="%s" height="120" width="120"/>
			</p>
			<p style="'.$css_Cuerpo2.'">
			 	Para hacerlo efectivo, debes descargar nuestra aplicación móvil para tu 
				<a style="'.$css_a.'" href="https://itunes.apple.com/es/app/%s/%s" title="Descargar">móvil iPhone</a> o 
				la aplicación para tu 
				<a style="'.$css_a.'" href="https://play.google.com/store/apps/details?id=%s" title="Descargar">móvil Android</a><br />
				<br />
				Ingresa con tu correo electrónico %s y la contraseña <strong>%s</strong>
			</p>
		</td>
	</tr>
	<tr><td style="'.$css_Pie.'">
	Mensaje enviado automaticamente desde <a style="'.$css_a2.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
	</tr></td>
</tbody>
</table>';
/***************************************/
/***************************************/
/***************************************/
/***************************************/
/***************************************/
/***************************************/
/***************************************/
$Email[1][1051]['alt']='Bono en Marca GPS';
$Email[1][1051]['title']="Tenemos un bono para ti";
$Email[1][1051]['body']='
<table cellspacing="0" cellpadding="0" border="0" width="100%%" style="'.$css_Tabla.'">
<tbody>
	<tr>
		<td style="'.$css_Tabla_Imagen.'" align="center">
			<a style="'.$css_a.'" href="'.$server_cn.'" title="'.$Slogan.'" style="border:none" target="_blank"><img style="'.$css_LOGO.'" src="'.$src_Imagen.'" /></a>
		</td>
	</tr>
	
	<tr>
		<td align="center">
			<h1 style="'.$css_Titulo.'">Hola %s</h1>
		</td>
	</tr>
	
	<tr>
		<td>
			<p style="'.$css_Cuerpo.'">
			 	Tenemos un Bono de <strong>%s</strong> en %s para ti
			 	<br/>
			 	<img src="%s" alt="%s" height="120" width="120"/>
			</p>
			<p style="'.$css_Cuerpo2.'">
			 	Para hacerlo efectivo, debes descargar nuestra aplicación móvil para tu 
				<a style="'.$css_a.'" href="https://itunes.apple.com/es/app/%s/%s" title="Descargar">móvil iPhone</a> o 
				la aplicación para tu 
				<a style="'.$css_a.'" href="https://play.google.com/store/apps/details?id=%s" title="Descargar">móvil Android</a><br />
			</p>
		</td>
	</tr>
	<tr><td style="'.$css_Pie.'">
	Mensaje enviado automaticamente desde <a style="'.$css_a2.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
	</tr></td>
</tbody>
</table>';

/***************************************/
/***************************************/
/***************************************/
/***************************************/
/***************************************/
/***************************************/
/***************************************/
$Email[1][1052]['alt']='Bono en Marca GPS';
$Email[1][1052]['title']="Bono aplicado";
$Email[1][1052]['body']='
<table cellspacing="0" cellpadding="0" border="0" width="100%%" style="'.$css_Tabla.'">
<tbody>
	<tr>
		<td style="'.$css_Tabla_Imagen.'" align="center">
			<a style="'.$css_a.'" href="'.$server_cn.'" title="'.$Slogan.'" style="border:none" target="_blank"><img style="'.$css_LOGO.'" src="'.$src_Imagen.'" /></a>
		</td>
	</tr>
	
	<tr>
		<td align="center">
			<h1 style="'.$css_Titulo.'">Hola %s</h1>
		</td>
	</tr>
	
	<tr>
		<td>
			<p style="'.$css_Cuerpo.'">
			 	Registramos el uso de tu bono de %s por %s
			</p>			
		</td>
	</tr>
	<tr><td style="'.$css_Pie.'">
	Mensaje enviado automaticamente desde <a style="'.$css_a2.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
	</tr></td>
</tbody>
</table>';
?>
<?php
$css_LOGO='max-height:8em';

$server_cn=$_PARAMETROS["LWSERVICE"];
$TEmail=$_PARAMETROS["S_NOMBCORTO"];
$FUrl=$_PARAMETROS["S_URLCORTA"];
$Slogan=$_PARAMETROS["S_SLOGAN"];
$OPUrl=$_PARAMETROS["WP_OPPAGE"];

$src_Imagen='http:'.$_PARAMETROS["S3_URL4"].ImgName($_PROYECTO,$_EMPRESA,0,0,'LogoApp','png',false,'big');
$css_Tabla_Imagen='padding:0.5em;color:#FFFFFF; background-color:#FFFFFF';	

$css_Tabla='background-color: #FFFFFF;padding:0px;font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; border:1px solid #2190D0;';	
$css_Titulo='color:#333333;font-size:20px; padding:0px';
$css_Titulo2='color:#333333;font-size:20px;margin:3px auto';
$css_Titulo3='color:#333333;font-size:17px;margin:5px auto 1px auto';
$css_Titulo4='color:#333333;font-size:16px;margin:5px auto 1px auto';

$css_Field='border:none padding:10px; margin:5px auto;background-color:#FFFFFF;';
$css_Legen='font-size:16px; color:#333; margin:2px 10px; padding:5px; font-weight:bold';
$css_Ol='padding:5px 10px;';

$css_Cuerpo='font-size:1em;padding:0.5em;margin:10px 0 5px 0;color:#333333;';	
$css_Cuerpo2='font-size:1em;padding:0.5em;margin:10px 10px 5px 10px;color:#333333;';	
$css_Pie='background-color: #2190D0;margin:5px;color:#FFFFFF;font-size:12px; padding:5px; text-align:center';

$css_Letrap="font-size:10px;";
$css_a='color:#2190D0;';
$css_a2='color:#FFF;';

// A USUARIO
$Email[1][550]['alt']='Cápsula del Tiempo';
$Email[1][550]['title']="Tienes una cápsula del tiempo lista para ser abierta";
$Email[1][550]['body']='
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
				Hace %s días el %s, %s %s te envío una capsula del tiempo. Ya es el momento que conozcas que hay adentro.<br />
				Ingresa <a style="'.$css_a.'"href="https://futuroscopio.com/mensajes-recibidos/%s" title="Time Kapsul Facebook">aquí</a> para consultarla.
			</p>
		</td>
	</tr>	
	<tr><td style="'.$css_Pie.'">
		Time Kapsul es un producto de <a style="'.$css_a2.'" href="http://futuremissions.org/home/Home/inicio" title="'.$Slogan.'">Future Missions</a>
	</tr></td>
</tbody>
</table>';
// A USUARIO
$Email[2][550]['alt']='Cápsula del Tiempo';
$Email[2][550]['title']="Tienes una cápsula del tiempo lista para ser abierta";
$Email[2][550]['body']='
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
				Hace %s días el %s, te enviaste una capsula del tiempo. Ya es el momento que conozcas que hay adentro.<br />v
				Ingresa <a style="'.$css_a.'"href="https://futuroscopio.com/mensajes-recibidos/%s" title="Time Kapsul Facebook">aquí</a> para consultarla.
			</p>
		</td>
	</tr>	
	<tr><td style="'.$css_Pie.'">
		Time Kapsul es un producto de <a style="'.$css_a2.'" href="http://futuremissions.org/home/Home/inicio" title="'.$Slogan.'">Future Missions</a>
	</tr></td>
</tbody>
</table>';
// A USUARIO
$Email[3][550]['alt']='Time Kapsul';
$Email[3][550]['title']="Bienvenid@ a la comunidad de Time Kapsul";
$Email[3][550]['body']='
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
				Tú ya haces parte de la comunidad que se imagina el futuro y le gusta compartir momentos que aún no han sucedido,
				Facebook ya no solo hablara del pasado, ya podrás enviar videos, fotos y mensajes de voz para que sean abiertos en una fecha del futuro que tu decidas.<br /><br />
				Podrás acceder a Time Kapsul por medio de tu cuenta de Facebook o haciendo click aquí : <a style="'.$css_a.'"href="https://apps.facebook.com/timekapsule" title="Time Kapsul Facebook">fb.com/timekapsule</a><br /><br />
				Cuando tengas capsulas del tiempo listas para ser abiertas, te enviaremos una alerta a este correo electrónico.
				<br />
				<br />
				<br />
				Gracias
			</p>
		</td>
	</tr>	
	<tr><td style="'.$css_Pie.'">
		Time Kapsul es un producto de <a style="'.$css_a2.'" href="http://futuremissions.org/home/Home/inicio" title="'.$Slogan.'">Future Missions</a>
	</tr></td>
</tbody>
</table>';
?>
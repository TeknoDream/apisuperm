<?php
$css_LOGO='max-height:12em';

$server_cn=$_PARAMETROS["LWSERVICE"];
$TEmail=$_PARAMETROS["S_NOMBCORTO"];
$FUrl=$_PARAMETROS["S_URLCORTA"];
$Slogan=$_PARAMETROS["S_SLOGAN"];
$OPUrl=$_PARAMETROS["WP_OPPAGE"];

$src_Imagen='http:'.$_PARAMETROS["S3_URL4"].ImgName($_PROYECTO,$_EMPRESA,0,0,'LogoApp','png',false,'big');
$css_Tabla_Imagen='padding:0;color:#333333';	

$css_Tabla='background-color: #FFFFFF;padding:0px;font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; border:1px solid #F0F0F0;';	
$css_Titulo='color:#055B31;font-size:20px; padding:0px';
$css_Titulo2='color:#231F20;font-size:20px;margin:3px auto';
$css_Titulo3='color:#231F20;font-size:17px;margin:5px auto 1px auto';
$css_Titulo4='color:#231F20;font-size:16px;margin:5px auto 1px auto';

$css_Field='border:none padding:10px; margin:5px auto;background-color:#FFFFFF;';
$css_Legen='font-size:16px; color:#333; margin:2px 10px; padding:5px; font-weight:bold';
$css_Ol='padding:5px 10px;';

$css_Cuerpo='font-size:14px;padding:0px;margin:10px 0 5px 0;color:#231F20;';	
$css_Cuerpo2='font-size:14px;padding:10px;margin:10px 10px 5px 10px;color:#231F20;';	
$css_Pie='background-color: #D5D5D5;margin:5px;color:#333333;font-size:12px; padding:5px; text-align:center';

$css_Letrap="font-size:10px;";
$css_a='color:#df5a0c;';
$css_a2='color:#000000;';


/***************************************/
/***************************************/
/***************************************/
/***************************************/
/***************************************/
/***************************************/
/***************************************/
$Email[1][1050]['alt']='Radicado de Cita %s';
$Email[1][1050]['title']="Radicado de Cita %s";
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
			<h1 style="'.$css_Titulo.'">Cita</h1>
		</td>
	</tr>
	
	<tr>
		<td>
			<p style="'.$css_Cuerpo.'">
			 	Se radicó su cita con el número <strong>%s</strong><br />
			 	Estaremos en comunicación con usted al número de teléfono %s para confirmar la disponibilidad de nuestros agentes comerciales.
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
$Email[1][1051]['alt']='Radicado de Cita %s';
$Email[1][1051]['title']="Radicado de Cita %s";
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
			<h1 style="'.$css_Titulo.'">Cita</h1>
		</td>
	</tr>
	
	<tr>
		<td>
			<p style="'.$css_Cuerpo.'">
			 	Se radicó su cita con el número <strong>%s</strong><br />
			 	Estaremos en comunicación con usted al número de teléfono %s para confirmar la disponibilidad de nuestros agentes comerciales.
			</p>
			<p style="'.$css_Cuerpo.'">
			 	<strong>El código del inmueble es el %s</strong>	 	
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
$Email[1][1052]['alt']='Solicitud de Cita %s';
$Email[1][1052]['title']="Solicitud de Cita %s";
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
			<h1 style="'.$css_Titulo.'">Solicitud de Cita %s</h1>
		</td>
	</tr>
	
	<tr>
		<td>
			<p style="'.$css_Cuerpo.'">
			 	Se realizó una solicitud de cita a travez de nuestra plataforma web.<br/>
			</p>
			<p style="'.$css_Cuerpo.'">
			 	Los datos de la cita son:<br />
			 	Nombre:	%s<br />
			 	Teléfono: %s<br />
			 	Email: %s<br />
			 	Fecha: <strong>%s</strong><br />
			 	Hora: <strong>%s</strong><br />
			 	<br />
			 	<strong>Inmueble: %s</strong> 	
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
$Email[1][1053]['alt']='Pago de Factura %s';
$Email[1][1053]['title']="Pago en Línea - Factura %s";
$Email[1][1053]['body']='
<table cellspacing="0" cellpadding="0" border="0" width="100%%" style="'.$css_Tabla.'">
<tbody>
	<tr>
		<td style="'.$css_Tabla_Imagen.'" align="center">
			<a style="'.$css_a.'" href="'.$server_cn.'" title="'.$Slogan.'" style="border:none" target="_blank"><img style="'.$css_LOGO.'" src="'.$src_Imagen.'" /></a>
		</td>
	</tr>
	
	<tr>
		<td align="center">
			<h1 style="'.$css_Titulo.'">Pago en Línea</h1>
		</td>
	</tr>
	
	<tr>
		<td>
			<p style="'.$css_Cuerpo.'">
			 	Se realizó exitosamente el pago de su factura %s
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
$Email[1][1054]['alt']='Mensaje de Contácto - No%s';
$Email[1][1054]['title']="Contáctenos";
$Email[1][1054]['body']='
<table cellspacing="0" cellpadding="0" border="0" width="100%%" style="'.$css_Tabla.'">
<tbody>
	<tr>
		<td style="'.$css_Tabla_Imagen.'" align="center">
			<a style="'.$css_a.'" href="'.$server_cn.'" title="'.$Slogan.'" style="border:none" target="_blank"><img style="'.$css_LOGO.'" src="'.$src_Imagen.'" /></a>
		</td>
	</tr>
	
	<tr>
		<td align="center">
			<h1 style="'.$css_Titulo.'">Nuevo Mensaje de Contáctenos</h1>
		</td>
	</tr>
	
	<tr>
		<td>
			<p style="'.$css_Cuerpo.'">
			 	Se recibió un nuevo mensaje de contacto desde el sitio web.<br />
			 	A continuación un resumen del la información recibida:<br />
			</p>
			<p style="'.$css_Cuerpo2.'">%s</p>						
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
$Email[1][1055]['alt']='Publicación de Inmueble';
$Email[1][1055]['title']="Solicitud de Publicación";
$Email[1][1055]['body']='
<table cellspacing="0" cellpadding="0" border="0" width="100%%" style="'.$css_Tabla.'">
<tbody>
	<tr>
		<td style="'.$css_Tabla_Imagen.'" align="center">
			<a style="'.$css_a.'" href="'.$server_cn.'" title="'.$Slogan.'" style="border:none" target="_blank"><img style="'.$css_LOGO.'" src="'.$src_Imagen.'" /></a>
		</td>
	</tr>
	
	<tr>
		<td align="center">
			<h1 style="'.$css_Titulo.'">Solicitud de Publicación</h1>
		</td>
	</tr>
	
	<tr>
		<td>
			<p style="'.$css_Cuerpo.'">
			 	Se recibió una solicitud para publicación de inmueble<br />
			 	<strong>Estos son los datos del usuario:</strong><br />
			 	%s
			 	<br />
			 	<br />
			 	<strong>Los datos del inmueble:</strong><br />
			 	%s
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
$Email[1][1056]['alt']='Inmueble Código %s';
$Email[1][1056]['title']="Recomiendo este Inmueble";
$Email[1][1056]['body']='
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
				%s le comparte este inmueble que puede ser de su interés.
				<br />
				Puede ver información del inmueble código %s <a style="'.$css_a.'" href="%s">dando clic aquí</a> o también puede ver mas opciones ingresando a <a style="'.$css_a.'" href="%s">%s</a>.
				<br/>
				<br/>
			</p>				
			<p style="'.$css_Field.'">
				Tambíen le deja este mensaje:<br/>		
				<span style="font-style: italic; margin-left:0.2em">%s</span>
			</p>		
		</td>
	</tr>
	<tr><td style="'.$css_Pie.'">
	Mensaje enviado automaticamente desde <a style="'.$css_a2.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
	</tr></td>
</tbody>
</table>';

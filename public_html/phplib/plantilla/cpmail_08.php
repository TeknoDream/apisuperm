<?php
$server_cn=$_PARAMETROS["LWSERVICE"];
$TEmail=$_PARAMETROS["S_NOMBCORTO"];
$FUrl=$_PARAMETROS["S_URLCORTA"];
$Slogan=$_PARAMETROS["S_SLOGAN"];
$OPUrl=$_PARAMETROS["WP_OPPAGE"];

$src_Imagen='http:'.$_PARAMETROS["S3_URL4"].ImgName($_PROYECTO,$_EMPRESA,0,0,'LogoApp','jpg',false,'t02');
$css_Tabla_Imagen='background-color: #F2F2F2;padding:5px 0';	

$css_Tabla='background-color: #F0F0F0;padding:0px;font-family: \'Trebuchet MS\', Arial, sans-serif, Helvetica;';	
$css_Titulo='color:#666;font-size:24px';
$css_Titulo2='color:#666;font-size:20px;margin:3px auto';
$css_Titulo3='color:#666;font-size:17px;margin:5px auto 1px auto';
$css_Titulo4='color:#666;font-size:16px;margin:5px auto 1px auto';

$css_Field='border:1px #CCC solid; padding:10px; margin:5px auto;';
$css_Legen='font-size:16px; color:#333; margin:2px 10px; padding:5px; font-weight:bold';
$css_Ol='padding:5px 10px;';

$css_Cuerpo='font-size:14px;padding:10px;margin:10px;color:#333;background-color: #FFF;border:1px solid #d6d6d6;';	
$css_Pie='background-color: #333;margin:5px;color:#FCFCFC;font-size:12px; padding:5px;';

$css_Letrap="font-size:10px;";
$css_a='color:#333;';
$css_a2='color:#FFF;';

$Email[1][150]['alt']='Notificación de Eventos';
$Email[1][150]['title']="Notificación de Evento";
$Email[1][150]['body']='
<table cellspacing="0" cellpadding="0" border="0" width="100%%" style="'.$css_Tabla.'">
<tbody>
	<tr>
		<td style="'.$css_Tabla_Imagen.'" align="center">
			<a style="'.$css_a.'" href="'.$server_cn.'" title="'.$Slogan.'" style="border:none" target="_blank"><img style="'.$css_LOGO.'" src="'.$src_Imagen.'" /></a>
		</td>
	</tr>
	
	<tr>
		<td align="center">
		<h1 style="'.$css_Titulo.'">%s lo invitó al evento "%s"</h1>
		</td>
	</tr>
	
	<tr>
		<td>
			<div style="'.$css_Cuerpo.'">
				Evento: <b>%s</b> <br />
				Lugar: %s <br />
				Fecha de Evento: %s	<br />
				<br />	
				<br />
				<br />
				Por favor, confirme el recibido de este correo haciendo click en el siguiente enlace:<br />
				<a style="'.$css_a.'" href="%s" title="Confirmar Recibido">%s</a>				
			</div>
		</tr>
	</td>
	
	<tr><td style="'.$css_Pie.'">
	Mensaje enviado automaticamente desde <a style="'.$css_a2.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
	</tr></td>
</tbody>
</table>';
$Email[1][151]['alt']='Notificación de Seguimiento';
$Email[1][151]['title']="Nota de Seguimiento";
$Email[1][151]['body']='
<table cellspacing="0" cellpadding="0" border="0" width="100%%" style="'.$css_Tabla.'">
<tbody>
	<tr>
		<td style="'.$css_Tabla_Imagen.'" align="center">
			<a style="'.$css_a.'" href="'.$server_cn.'" title="'.$Slogan.'" style="border:none" target="_blank"><img style="'.$css_LOGO.'" src="'.$src_Imagen.'" /></a>
		</td>
	</tr>
	
	<tr>
		<td align="center">
			<h1 style="'.$css_Titulo.'">%s le envió la siguiente anotación de seguimiento"</h1>
		</td>
	</tr>
	
	<tr>
		<td>
			<div style="'.$css_Cuerpo.'">
				Anotación de Seguimiento: <b>%s</b><br />
				Fecha: %s<br />
				Nota: %s<br />
				<br />	
				<br />
				<br />

				Por favor, confirme el recibido de este correo haciendo click en el siguiente enlace:<br />
				<a style="'.$css_a.'" href="%s" title="Confirmar Recibido">%s</a>				
			</div>
		</tr>
	</td>
	
	<tr><td style="'.$css_Pie.'">
	Mensaje enviado automaticamente desde <a style="'.$css_a2.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
	</tr></td>
</tbody>
</table>';
$Email[1][152]['alt']='Notificación de Facturación';
$Email[1][152]['title']="Factura %s";
$Email[1][152]['body']='
<table cellspacing="0" cellpadding="0" border="0" width="100%%" style="'.$css_Tabla.'">
<tbody>
	<tr>
		<td style="'.$css_Tabla_Imagen.'" align="center">
			<a style="'.$css_a.'" href="'.$server_cn.'" title="'.$Slogan.'" style="border:none" target="_blank"><img style="'.$css_LOGO.'" src="'.$src_Imagen.'" /></a>
		</td>
	</tr>
	
	<tr>
		<td align="center">
			<h1 style="'.$css_Titulo.'">%s de %s le envió la factura adjunta</h1>
		</td>
	</tr>
	
	<tr>
		<td>
			<p style="'.$css_Cuerpo.'">
				Por favor, confirme el recibido de este correo haciendo click en el siguiente enlace:<br />
				<a style="'.$css_a.'" href="%s" title="Confirmar Recibido">%s</a>			
			</p>
		</td>
	</tr>
	
	<tr><td style="'.$css_Pie.'">
	Mensaje enviado automaticamente desde <a style="'.$css_a2.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
	</tr></td>
</tbody>
</table>';
?>
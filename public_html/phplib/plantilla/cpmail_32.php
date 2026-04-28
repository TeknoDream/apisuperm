<?php
$css_LOGO='max-height:8em';

$server_cn=$_PARAMETROS["LWSERVICE"];
$TEmail=$_PARAMETROS["S_NOMBCORTO"];
$FUrl=$_PARAMETROS["S_URLCORTA"];
$Slogan=$_PARAMETROS["S_SLOGAN"];
$OPUrl=$_PARAMETROS["WP_OPPAGE"];

$src_Imagen='http:'.$_PARAMETROS["S3_URL4"].ImgName($_PROYECTO,$_EMPRESA,0,0,'LogoApp','png',false,'big');
$css_Tabla_Imagen='padding:0.5em;color:#FFFFFF; background-color:#F2F2F2';	

$css_Tabla='background-color: #FFFFFF;padding:0px;font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; border:1px solid #F0F0F0;';	
$css_Titulo='color:#333333;font-size:20px; padding:0px';
$css_Titulo2='color:#333333;font-size:20px;margin:3px auto';
$css_Titulo3='color:#333333;font-size:17px;margin:5px auto 1px auto';
$css_Titulo4='color:#333333;font-size:16px;margin:5px auto 1px auto';

$css_Field='border:none padding:10px; margin:5px auto;background-color:#FFFFFF;';
$css_Legen='font-size:16px; color:#333; margin:2px 10px; padding:5px; font-weight:bold';
$css_Ol='padding:5px 10px;';

$css_Cuerpo='font-size:1em;padding:0.5em;margin:10px 0 5px 0;color:#333333;';	
$css_Cuerpo2='font-size:1em;padding:0.5em;margin:10px 10px 5px 10px;color:#333333;';	
$css_Pie='background-color: #BF0411;margin:5px;color:#FFFFFF;font-size:12px; padding:5px; text-align:center';

$css_Letrap="font-size:10px;";
$css_a='color:#df5a0c;';
$css_a2='color:#FFF;';

// A USUARIO
$Email[1][550]['alt']='Cotización Super Maestros';
$Email[1][550]['title']="Tienes una cotización";
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
				%s te envía una cotización en <a style="'.$css_a.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a> con código %s<br />
			</p>
		</td>
	</tr>	
	<tr><td style="'.$css_Pie.'">
	Mensaje enviado automaticamente desde <a style="'.$css_a2.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
	</tr></td>
</tbody>
</table>';
// A USUARIO
$Email[1][551]['alt']='Cotización Super Maestros';
$Email[1][551]['title']="Tienes una cotización";
$Email[1][551]['body']='
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
				%s te envía una cotización en <a style="'.$css_a.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a> con código %s<br />
				Ingresa usando tu correo <strong>%s</strong> y la contraseña <strong>%s</strong>
			</p>
		</td>
	</tr>	
	<tr><td style="'.$css_Pie.'">
	Mensaje enviado automaticamente desde <a style="'.$css_a2.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
	</tr></td>
</tbody>
</table>';

// A USUARIO
$Email[1][552]['alt']='Nuevo Registro de Maestro';
$Email[1][552]['title']="Nuevo Maestro";
$Email[1][552]['body']='
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
				Un nuevo usuario se a registrado en nuestra plataforma<br />
				Estos son sus datos:<br />
				Nombre: %s<br />
				Apellido: %s<br />
				Correo: %s
			</p>
		</td>
	</tr>	
	<tr><td style="'.$css_Pie.'">
	Mensaje enviado automaticamente desde <a style="'.$css_a2.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
	</tr></td>
</tbody>
</table>';
?>
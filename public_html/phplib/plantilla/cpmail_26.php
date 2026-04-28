<?php
$css_LOGO='max-height:12em';

$server_cn=$_PARAMETROS["LWSERVICE"];
$TEmail=$_PARAMETROS["S_NOMBCORTO"];
$FUrl=$_PARAMETROS["S_URLCORTA"];
$Slogan=$_PARAMETROS["S_SLOGAN"];
$OPUrl=$_PARAMETROS["WP_OPPAGE"];

$src_Imagen='http:'.$_PARAMETROS["S3_URL4"].ImgName($_PROYECTO,$_EMPRESA,0,0,'LogoApp','png',false,'big');
$css_Tabla_Imagen='padding:0;color:#FFFFFF; background-color:#00add8';	

$css_Tabla='background-color: #FFFFFF;padding:0px;font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; border:1px solid #F0F0F0;';	
$css_Titulo='color:#333333;font-size:20px; padding:0px';
$css_Titulo2='color:#333333;font-size:20px;margin:3px auto';
$css_Titulo3='color:#333333;font-size:17px;margin:5px auto 1px auto';
$css_Titulo4='color:#333333;font-size:16px;margin:5px auto 1px auto';

$css_Field='border:none padding:10px; margin:5px auto;background-color:#FFFFFF;';
$css_Legen='font-size:16px; color:#333; margin:2px 10px; padding:5px; font-weight:bold';
$css_Ol='padding:5px 10px;';

$css_Cuerpo='font-size:1m;padding:0.5em;margin:10px 0 5px 0;color:#231F20;';	
$css_Cuerpo2='font-size:1m;padding:0.5em;margin:10px 10px 5px 10px;color:#231F20;';	
$css_Pie='background-color: #D5D5D5;margin:5px;color:#333333;font-size:12px; padding:5px; text-align:center';

$css_Letrap="font-size:10px;";
$css_a='color:#df5a0c;';
$css_a2='color:#000000;';

// A USUARIO
$Email[1][600]['alt']='Nueva Promoción';
$Email[1][600]['title']="Tienes una solicitud de Promoción";
$Email[1][600]['body']='
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
				El usuario %s de la veterinaria %s solicita aprobación para la publicación de una promoción
			</p>
		</td>
	</tr>	
	<tr><td style="'.$css_Pie.'">
	Mensaje enviado automaticamente desde <a style="'.$css_a2.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
	</tr></td>
</tbody>
</table>';

?>
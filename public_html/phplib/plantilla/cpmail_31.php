<?php
$server_cn=$_PARAMETROS["LWSERVICE"];
$TEmail=$_PARAMETROS["S_NOMBCORTO"];
$FUrl=$_PARAMETROS["S_URLCORTA"];
$Slogan=$_PARAMETROS["S_SLOGAN"];
$OPUrl=$_PARAMETROS["WP_OPPAGE"];

$src_Imagen='http:'.$_PARAMETROS["S3_URL4"].ImgName($_PROYECTO,$_EMPRESA,0,0,'LogoApp','png',false,'big');
$css_Imagen='height:32px';	

$css_Tabla_Imagen='background-color: #383b49;padding:10px;';	

$css_Tabla='background-color: #FAFAFA;padding:2px;font-family: \'Trebuchet MS\', Arial, sans-serif, Helvetica;border:1px solid #CCCCCC';	
$css_Titulo='color:#333333;font-size:22px';
$css_Titulo2='color:#666;font-size:18px;margin:3px auto';
$css_Titulo3='color:#666;font-size:16px;margin:5px auto 1px auto';
$css_Titulo4='color:#666;font-size:14px;margin:5px auto 1px auto';

$css_Field='border:1px #CCC solid; padding:10px; margin:5px auto;';
$css_Legen='font-size:16px; color:#333; margin:2px 10px; padding:5px; font-weight:bold';
$css_Ol='padding:5px 10px;';

$css_Cuerpo='font-size:14px;padding:10px;margin:5px;color:#333;background-color: #FFFFFF;border:1px solid #DDDDDD;';	
$css_Cuerpo2='font-size:14px;padding:10px;margin:10px;color:#333;text-align:center';	
$css_Pie='background-color: #383b49;margin:5px;color:#FFF;font-size:12px; padding:5px;';

$css_Letrap="font-size:10px;";
$css_a='color:#f08424;';
$css_a1='color:#f08424;';
$css_a2='color:#FFF;';

/**********************************/
/********** RECORDATORIOS**********/
/**********************************/
// A USUARIO
$Email[1][550]['alt']='Registro en checkin';
$Email[1][550]['title']="Registro en checkin";
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
				Se creó el registro al evento %s.<br /> 
				Puede imprimir este correo y llevar este código QR para agilizar la impresión de su identificación:<br />
			</p>
			<p style="'.$css_Cuerpo2.'">
				<img src="%s" alt="%s" height="120" width="120"/>
			</p>
		</td>
	</tr>	
	<tr><td style="'.$css_Pie.'">
	Mensaje enviado automaticamente desde <a style="'.$css_a2.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
	</tr></td>
</tbody>
</table>';
?>
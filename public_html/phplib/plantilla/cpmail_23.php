<?php
$server_cn=$_PARAMETROS["LWSERVICE"];
$TEmail=$_PARAMETROS["S_NOMBCORTO"];
$FUrl=$_PARAMETROS["S_URLCORTA"];
$Slogan=$_PARAMETROS["S_SLOGAN"];
$OPUrl=$_PARAMETROS["WP_OPPAGE"];

$src_Imagen='http:'.$_PARAMETROS["S3_URL4"].ImgName($_PROYECTO,$_EMPRESA,0,0,'LogoApp','png',false,'big');
$css_Tabla_Imagen='background-color: #545152;padding:5px 0;color:#FFF';	

$css_Tabla='background-color: #FFFFFF;padding:0px;font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; border:1px solid #666666;';	
$css_Titulo='color:#545152;font-size:20px; padding:5px';
$css_Titulo2='color:#666;font-size:20px;margin:3px auto';
$css_Titulo3='color:#666;font-size:17px;margin:5px auto 1px auto';
$css_Titulo4='color:#666;font-size:16px;margin:5px auto 1px auto';

$css_Field='border:1px #CCC solid; padding:10px; margin:5px auto;';
$css_Legen='font-size:16px; color:#333; margin:2px 10px; padding:5px; font-weight:bold';
$css_Ol='padding:5px 10px;';

$css_Cuerpo='font-size:14px;padding:10px;margin:10px 10px 5px 10px;color:#333;background-color: #FFF;border:1px solid #d6d6d6;';	
$css_Cuerpo2='font-size:14px;padding:10px;margin:10px 10px 5px 10px;color:#333;';	
$css_Pie='background-color: #545152;margin:5px;color:#FFFFFF;font-size:12px; padding:5px; text-align:center';

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
$Email[1][950]['alt']='Notificación de cotización';
$Email[1][950]['title']="Cotización VG-%s";
$Email[1][950]['body']='
<table cellspacing="0" cellpadding="0" border="0" width="100%%" style="'.$css_Tabla.'">
<tbody>
	<tr>
		<td style="'.$css_Tabla_Imagen.'" align="center">
			<a style="'.$css_a.'" href="'.$server_cn.'" title="'.$Slogan.'" style="border:none" target="_blank"><img style="'.$css_LOGO.'" src="'.$src_Imagen.'" /></a>
		</td>
	</tr>
	
	<tr>
		<td align="center">
			<h1 style="'.$css_Titulo.'">Buen día %s</h1>
		</td>
	</tr>
	
	<tr>
		<td>
			<p style="'.$css_Cuerpo2.'">
			 	Su solicitud de cotización <strong>VG-%s</strong> tiene un valor estimado de <strong>$%s</strong>
			</p>
		</td>
	</tr>
	<tr>
		<td>%s</td>
	</tr>	
	<tr>
		<td><p style="'.$css_Cuerpo2.'">Observaciones: %s</p></td>
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
$Email[1][951]['alt']='Notificaciones VIGA';
$Email[1][951]['title']="Cotización VG-%s";
$Email[1][951]['body']='
<table cellspacing="0" cellpadding="0" border="0" width="100%%" style="'.$css_Tabla.'">
<tbody>
	<tr>
		<td style="'.$css_Tabla_Imagen.'" align="center">
			<a style="'.$css_a.'" href="'.$server_cn.'" title="'.$Slogan.'" style="border:none" target="_blank"><img style="'.$css_LOGO.'" src="'.$src_Imagen.'" /></a>
		</td>
	</tr>
	
	<tr>
		<td align="center">
			<h1 style="'.$css_Titulo.'">Buen día</h1>
		</td>
	</tr>
	
	<tr>
		<td>
			<p style="'.$css_Cuerpo2.'">
			 	Se generó una nueva solicitud de cotización con identificación <strong>VG-%s</strong> y un valor estimado de <strong>$%s</strong>
			</p>
		</td>
	</tr>
	<tr>
		<td>%s</td>
	</tr>
	<tr>
		<td><p style="'.$css_Cuerpo2.'">Observaciones: %s</p></td>
	</tr>	
	<tr><td style="'.$css_Pie.'">
	Mensaje enviado automaticamente desde <a style="'.$css_a2.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
	</tr></td>
</tbody>
</table>';
?>
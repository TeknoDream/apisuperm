<?php
$server_cn=$_PARAMETROS["LWSERVICE"];
$TEmail=$_PARAMETROS["S_NOMBCORTO"];
$FUrl=$_PARAMETROS["S_URLCORTA"];
$Slogan=$_PARAMETROS["S_SLOGAN"];
$OPUrl=$_PARAMETROS["WP_OPPAGE"];

$src_Imagen='http:'.$_PARAMETROS["S3_URL4"].ImgName($_PROYECTO,$_EMPRESA,0,0,'LogoApp','png',false,'big');
$css_Tabla_Imagen='background-color: #f41a21;padding:5px 0;color:#FFF';	

$css_Tabla='background-color: #FFFFFF;padding:0px;font-family: \'Trebuchet MS\', Arial, sans-serif, Helvetica; border:1px solid #f41a21;';	
$css_Titulo='color:#ae0000;font-size:20px; padding:10px 5px 5px 5px';
$css_Titulo2='color:#666;font-size:20px;margin:3px auto';
$css_Titulo3='color:#666;font-size:17px;margin:5px auto 1px auto';
$css_Titulo4='color:#666;font-size:16px;margin:5px auto 1px auto';

$css_Field='border:1px #CCC solid; padding:10px; margin:5px auto;';
$css_Legen='font-size:16px; color:#333; margin:2px 10px; padding:5px; font-weight:bold';
$css_Ol='padding:5px 10px;';

$css_Cuerpo='font-size:14px;padding:10px;margin:10px 10px 5px 10px;color:#333;background-color: #FFF;border:1px solid #d6d6d6;';	
$css_Cuerpo2='font-size:14px;padding:10px;margin:10px 10px 5px 10px;color:#333;';	
$css_Pie='background-color: #ae0000;margin:5px;color:#FFFFFF;font-size:12px; padding:5px; text-align:center';

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
$Email[1][750]['alt']='Notificación confirmación de Pedido';
$Email[1][750]['title']="Pedido %s";
$Email[1][750]['body']='
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
			<div style="'.$css_Cuerpo.'">
			 	Su número de pedido es %s por valor de $%s
			</div>
		</td>
	</tr>
	
	<tr><td style="'.$css_Pie.'">
	Mensaje enviado automaticamente desde <a style="'.$css_a2.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
	</tr></td>
</tbody>
</table>';
$Email[1][751]['alt']='Notificación confirmación de Pedido';
$Email[1][751]['title']="Pedido No. %s";
$Email[1][751]['body']='
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
			<p style="'.$css_Cuerpo2.'">
			 	Su pedido <strong>No.%s</strong> por valor de <strong>$%s</strong> fue confirmado por <a style="'.$css_a.'" href="%s">%s (%s)</a> y llegará en <strong>%smin aprox</strong>
			</p>
		</td>
	</tr>
	<tr>
		<td>%s</td>
	</tr>	
	<tr><td style="'.$css_Pie.'">
	Mensaje enviado automaticamente desde <a style="'.$css_a2.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
	</tr></td>
</tbody>
</table>';
$Email[1][752]['alt']='Alerta tiempo';
$Email[1][752]['title']="Alerta Pedido No. %s";
$Email[1][752]['body']='
<table cellspacing="0" cellpadding="0" border="0" width="100%%" style="'.$css_Tabla.'">
<tbody>
	<tr>
		<td style="'.$css_Tabla_Imagen.'" align="center">
			<a style="'.$css_a.'" href="'.$server_cn.'" title="'.$Slogan.'" style="border:none" target="_blank"><img style="'.$css_LOGO.'" src="'.$src_Imagen.'" /></a>
		</td>
	</tr>
	
	<tr>
		<td align="center">
			<h1 style="'.$css_Titulo.'">Alerta de Tiempo</h1>
		</td>
	</tr>
	
	<tr>
		<td>
			<p style="'.$css_Cuerpo2.'">
			 	El pedido <strong>No.%s</strong> no ha sido confirmado desde <strong>%s</strong><br /><br />

			 	<strong>Información del Usuario</strong><br />
			 	Nombre: %s<br/>
			 	Teléfono: %s<br/>
			 	Móvil: %s<br/>
			 	Dirección: %s<br/>
			 	Referencia: %s<br/>
			 	Barrio: %s<br/><br/>

			 	<strong>Información del Pedido</strong><br />
			 	Valor: $%s<br/>
			 	Plataforma: %s<br/>
			 	Fecha: %s<br/>
			 	Restaurante: %s<br/>
			 	Sucursal: %s<br/>
			</p>
		</td>
	</tr>
	<tr><td style="'.$css_Pie.'">
	Mensaje enviado automaticamente desde <a style="'.$css_a2.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
	</tr></td>
</tbody>
</table>';
?>
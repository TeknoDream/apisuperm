<?php
$server_cn=$_PARAMETROS["LWSERVICE"];
$TEmail=$_PARAMETROS["S_NOMBCORTO"];
$FUrl=$_PARAMETROS["S_URLCORTA"];
$Slogan=$_PARAMETROS["S_SLOGAN"];
$OPUrl=$_PARAMETROS["WP_OPPAGE"];

$src_Imagen='http:'.$_PARAMETROS["S3_URL4"].ImgName($_PROYECTO,$_EMPRESA,0,0,'LogoApp','png',false,'big');
$css_Tabla_Imagen='background-color: #333;padding:5px 0';	

$css_Tabla='background-color: #FCFCFC;padding:0px;font-family: \'Trebuchet MS\', Arial, sans-serif, Helvetica;';	
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

$Email[1][50]['alt']='Notificación de Solicitud de Mantenimiento';
$Email[1][50]['title']="Solicitud de Mantenimiento %s";
$Email[1][50]['body']='
<table cellspacing="0" cellpadding="0" border="0" width="100%%" style="'.$css_Tabla.'">
<tbody>
	<tr>
		<td style="'.$css_Tabla_Imagen.'" align="center">
			<a style="'.$css_a.'" href="'.$server_cn.'" title="'.$Slogan.'" style="border:none" target="_blank"><img style="'.$css_LOGO.'" src="'.$src_Imagen.'" /></a>
		</td>
	</tr>
	
	<tr>
		<td align="center">
		<h1 style="'.$css_Titulo.'">Solicitud de Mantenimiento %s</h1>
		</td>
	</tr>
	
	<tr>
		<td>
			<div style="'.$css_Cuerpo.'">
				<strong>%s</strong> generó la solicitud de mantenimiento con la siguiente identificación <b>%s</b>.
				A continuación los detalles de esta solicitud de mantenimiento:<br /><br /><br />
				<h3 style="'.$css_Titulo3.'">%s</h3>
				%s
				<h3 style="'.$css_Titulo3.'">%s</h3>
				%s
				<br />
				<fieldset style="'.$css_Field.'">        
            		<legend style="'.$css_Legen.'">%s</legend>
            		<h4 style="'.$css_Titulo4.'">%s</h4>
            		<ol style="'.$css_Ol.'">%s</ol>
            	</fieldset>
			</div>
		</tr>
	</td>
	
	<tr><td style="'.$css_Pie.'">
	Mensaje enviado automaticamente desde <a style="'.$css_a2.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
	</tr></td>
</tbody>
</table>';
$Email[1][51]['alt']='Respuesta a Solicitud de Mantenimiento';
$Email[1][51]['title']="Respuesta a Solicitud de Mantenimiento %s";
$Email[1][51]['body']='
<table cellspacing="0" cellpadding="0" border="0" width="100%%" style="'.$css_Tabla.'">
<tbody>
	<tr>
		<td style="'.$css_Tabla_Imagen.'" align="center">
			<a style="'.$css_a.'" href="'.$server_cn.'" title="'.$Slogan.'" style="border:none" target="_blank"><img style="'.$css_LOGO.'" src="'.$src_Imagen.'" /></a>
		</td>
	</tr>
	
	<tr>
		<td align="center">
		<h1 style="'.$css_Titulo.'">Respuesta a Solicitud de Mantenimiento %s</h1>
		</td>
	</tr>
	
	<tr>
		<td>
			<div style="'.$css_Cuerpo.'">
				<strong>%s</strong> respondió su solicitud de mantenimiento con identificación <b>%s</b>.
				A continuación los detalles de esta respuesta:<br /><br /><br />
				<h3 style="'.$css_Titulo3.'">%s</h3>
				%s
				
				<h3 style="'.$css_Titulo3.'">%s</h3>
				%s
				
				<br />
				<fieldset style="'.$css_Field.'">        
            		<legend style="'.$css_Legen.'">%s</legend>
            		%s
            	</fieldset>
			</div>
		</tr>
	</td>	
	<tr><td style="'.$css_Pie.'">
	Mensaje enviado automaticamente desde <a style="'.$css_a2.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
	</tr></td>
</tbody>
</table>';
?>
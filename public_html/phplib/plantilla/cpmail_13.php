<?php
$server_cn=$_PARAMETROS["LWSERVICE"];
$TEmail=$_PARAMETROS["S_NOMBCORTO"];
$FUrl=$_PARAMETROS["S_URLCORTA"];
$Slogan=$_PARAMETROS["S_SLOGAN"];
$ResEmail=$_PARAMETROS["M_FROMMAIL"];

$src_Imagen='http:'.$_PARAMETROS["S3_URL4"].ImgName($_PROYECTO,$_EMPRESA,0,0,'LogoApp','png',false,'big');
$css_Tabla='background-color: #FFF;padding:10px;font-family: \'Trebuchet MS\', Arial, sans-serif, Helvetica;';	
$css_Titulo='color:#666;font-size:2em;font-family: \'HelveticaNeueBold\', \'HelveticaNeue-Bold\', \'Helvetica Neue Bold\', \'HelveticaNeue\', \'Helvetica Neue\', \'TeXGyreHerosBold\', \'Helvetica\', \'Tahoma\', \'Geneva\', \'Arial\', sans-serif; font-weight:600; font-stretch:normal;';
$css_Titulo2='color:#666;font-size:18px;font-family: \'HelveticaNeueBold\', \'HelveticaNeue-Bold\', \'Helvetica Neue Bold\', \'HelveticaNeue\', \'Helvetica Neue\', \'TeXGyreHerosBold\', \'Helvetica\', \'Tahoma\', \'Geneva\', \'Arial\', sans-serif; font-weight:600; font-stretch:normal;';

$css_Cuerpo='font-size:14px;width:99%%;padding:5px;margin:10px auto;color:#333;background-color: #FFF;';	
$css_Pie='width:100%%;margin:5px auto;color:#666;font-size:12px; padding:5px;';
$css_Letrap="font-size:10px;";
$css_a='color:#333;';

$Email[1][350]['alt']='Confirmación de su Compra';
$Email[1][350]['title']="Confirmación de Compra";
$Email[1][350]['body']='
<table cellspacing="0" cellpadding="0" border="0" width="100%%" style="'.$css_Tabla.'">
<tbody>
	<tr>
		<td style="'.$css_Tabla_Imagen.'" align="center">
			<a style="'.$css_a.'" href="'.$server_cn.'" title="'.$Slogan.'" style="border:none" target="_blank"><img style="'.$css_LOGO.'" src="'.$src_Imagen.'" /></a>
		</td>
	</tr>
	
	<tr>
		<td align="center">
			<h1 style="'.$css_Titulo.'">Gracias por tu Compra</h1>
		</td>
	</tr>
	
	<tr>
		<td>
			<div style="'.$css_Cuerpo.'">					
				Buen dia %s,
				<p>El pago de tu compra con referencia %s fue concluido exitósamente.</p>
				<p>A continuación tu listado de compras:
				%s
				</p>		
			</div>
		</tr>
	</td>
	
	<tr><td style="'.$css_Pie.'">
	Mensaje enviado automaticamente desde <a style="'.$css_a.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a> <br />
	Para  cualquier  cambio o solicitud informar a este correo <a style="'.$css_a.'" href="mailto:'.$ResEmail.'" title="'.$ResEmail.'">'.$ResEmail.'</a>
	</tr></td>
</tbody>
</table>';
?>
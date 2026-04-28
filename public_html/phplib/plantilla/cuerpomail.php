<?php
function CuerpoMail(&$Email,$_PROYECTO=0,$_EMPRESA=0,$_CLIENTE=0,$_IDIOMA=0,$_TZ='',$_GCLIENTE=0,$_USUARIO=0,$_GRUPO=0,$_PARAMETROS=array()){
	$css_LOGO='max-height:4em';
	if($_PROYECTO==1) include("cpmail_01.php");
	elseif($_PROYECTO==8) include("cpmail_08.php");
	elseif($_PROYECTO==10) include("cpmail_10.php");
	elseif($_PROYECTO==13) include("cpmail_13.php");
	elseif($_PROYECTO==14) include("cpmail_14.php");
	elseif($_PROYECTO==16) include("cpmail_16.php");
	elseif($_PROYECTO==19) include("cpmail_19.php");
	elseif($_PROYECTO==20) include("cpmail_20.php");
	elseif($_PROYECTO==21) include("cpmail_21.php");
	elseif($_PROYECTO==22) include("cpmail_22.php");
	elseif($_PROYECTO==23) include("cpmail_23.php");	
	elseif($_PROYECTO==24) include("cpmail_24.php");	
	elseif($_PROYECTO==25) include("cpmail_25.php");
	elseif($_PROYECTO==26) include("cpmail_26.php");
	elseif($_PROYECTO==29) include("cpmail_29.php");
	elseif($_PROYECTO==31) include("cpmail_31.php");
	elseif($_PROYECTO==32) include("cpmail_32.php");
	elseif($_PROYECTO==33) include("cpmail_33.php");
	/*
	$Email[ IDIOMA ] [ CONSECUTIVO ] [alt, titulo, body ]
	*/
	
	/***************************************/
	/***************************************/
	/***************************************/
	/***************************************/
	/***************************************/
	/***************************************/
	/***************************************/
	$Email[1][0]['alt']='¡Su cuenta fue creada satisfactóriamente!';
	$Email[1][0]['title']="Creación de Cuenta";
	$Email[1][0]['body']='
	<table cellspacing="0" cellpadding="0" border="0" width="100%%" style="'.$css_Tabla.'">
	<tbody>
		<tr style="'.$css_Tabla_Imagen.'">
			<td style="'.$css_Tabla_Imagen.'" align="center">
				<a style="'.$css_a.'" href="'.$server_cn.'" title="'.$Slogan.'" style="border:none" target="_blank"><img style="'.$css_LOGO.'" src="'.$src_Imagen.'" /></a>
			</td>
		</tr>
		
		<tr>
			<td align="center">
				<h1 style="'.$css_Titulo.'">Bienvenido a '.$TEmail.'</h1>
			</td>
		</tr>
		
		<tr>
			<td>
				<p style="'.$css_Cuerpo.'">
					Su cuenta fue creada satisfactóriamente, estos son los datos de tu cuenta:<br />
					Correo: <b>%s</b> <br />
					Contraseña: <b>%s</b>
					<br />	
					Recuerde que desde este momento usted puede ingresar a <a style="'.$css_a.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
					<br />	
					Recuerde verificar su cuenta haciendo clic <a style="'.$css_a.'" href="%s" title="verificación de cuenta">aquí</a>
				</p>
			</td>
		</tr>
		
		<tr><td style="'.$css_Pie.'">
		Mensaje enviado automaticamente desde <a style="'.$css_a2.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
		</tr></td>
	</tbody>
	</table>';

	$Email[1][1]['alt']='¡Su cuenta fue creada satisfactóriamente!';
	$Email[1][1]['title']="Creación de Cuenta";
	$Email[1][1]['body']='
	<table cellspacing="0" cellpadding="0" border="0" width="100%%" style="'.$css_Tabla.'">
	<tbody>
		<tr>
			<td style="'.$css_Tabla_Imagen.'" align="center">
				<a style="'.$css_a.'" href="'.$server_cn.'" title="'.$Slogan.'" style="border:none" target="_blank"><img style="'.$css_LOGO.'" src="'.$src_Imagen.'" /></a>
			</td>
		</tr>
		
		<tr>
			<td align="center">
			<h1 style="'.$css_Titulo.'">Bienvenido a '.$TEmail.'</h1>
			</td>
		</tr>
		
		<tr>
			<td>
				<p style="'.$css_Cuerpo.'">
					%s creó una cuenta de usuario para usted y estos son datos para el acceso:<br />
					Correo: <b>%s</b> <br />
					Contraseña: <b>%s</b><br />
					Puede ingresar a travez de este enlace: <a style="'.$css_a.'" href="%s" title="Operaciones Especiales">%s</a>
					<br />	
					Recuerde que desde este momento usted ingresar a <a style="'.$css_a.'" href="'.$OPUrl.'" title="'.$Slogan.'">'.$OPUrl.'</a>			
					<br />	
					Para verificar su cuenta, haga clic <a style="'.$css_a.'" href="%s" title="verificación de cuenta">aquí</a>
				</p>
			</td>
		</tr>
		
		<tr><td style="'.$css_Pie.'">
		Mensaje enviado automaticamente desde <a style="'.$css_a2.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
		</tr></td>
	</tbody>
	</table>';

	$Email[1][2]['alt']='¡Su cuenta fue creada satisfactóriamente!';
	$Email[1][2]['title']="Creación de Cuenta";
	$Email[1][2]['body']='
	<table cellspacing="0" cellpadding="0" border="0" width="100%%" style="'.$css_Tabla.'">
	<tbody>
		<tr>
			<td style="'.$css_Tabla_Imagen.'" align="center">
				<a style="'.$css_a.'" href="'.$server_cn.'" title="'.$Slogan.'" style="border:none" target="_blank"><img style="'.$css_LOGO.'" src="'.$src_Imagen.'" /></a>
			</td>
		</tr>
		
		<tr>
			<td align="center">
			<h1 style="'.$css_Titulo.'">%s bienvenido a %s</h1>
			</td>
		</tr>
		
		<tr>
			<td>
				<div style="'.$css_Cuerpo.'">
					%s lo vinculo a la empresa <strong>%s</strong><br />
					Puede ingresar a travez de este enlace: <a style="'.$css_a.'" href="%s" title="Operaciones Especiales">%s</a>
				</div>
			</td>
		</tr>
		
		<tr><td style="'.$css_Pie.'">
		Mensaje enviado automaticamente desde <a style="'.$css_a.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
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
	$Email[1][3]['alt']='¿Olvidó su conraseña? Recuperela aquí';
	$Email[1][3]['title']="Recuperación de Contraseña";
	$Email[1][3]['body']='
	<table cellspacing="0" cellpadding="0" border="0" width="100%%" style="'.$css_Tabla.'">
	<tbody>
		<tr>
			<td style="'.$css_Tabla_Imagen.'" align="center">
				<a style="'.$css_a.'" href="'.$server_cn.'" title="'.$Slogan.'" style="border:none" target="_blank"><img style="'.$css_LOGO.'" src="'.$src_Imagen.'" /></a>
			</td>
		</tr>
		
		<tr>
			<td align="center">
				<h1 style="'.$css_Titulo.'">¿Olvidaste tu contraseña?</h1>
			</td>
		</tr>
		
		<tr>
			<td>
				<p style="'.$css_Cuerpo.'">					
					Da clic <a style="'.$css_a.'" href="%s" title="Recuperación de Contraseña">AQUÍ</a> para iniciar el proceso de recuperación
				</p>
			</td>
		</tr>
		
		<tr><td style="'.$css_Pie.'">
		Mensaje enviado automaticamente desde <a style="'.$css_a2.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
		</tr></td>
	</tbody>
	</table>';

	$Email[1][4]['alt']='¡Contraseña Recuperada satisfactóriamente!';
	$Email[1][4]['title']="Recuperación de Contraseña";
	$Email[1][4]['body']='
	<table cellspacing="0" cellpadding="0" border="0" width="100%%" style="'.$css_Tabla.'">
	<tbody>
		<tr>
			<td style="'.$css_Tabla_Imagen.'" align="center">
				<a style="'.$css_a.'" href="'.$server_cn.'" title="'.$Slogan.'" style="border:none" target="_blank"><img style="'.$css_LOGO.'" src="'.$src_Imagen.'" /></a>
			</td>
		</tr>
		
		<tr>
			<td align="center">
				<h1 style="'.$css_Titulo.'">¡Contraseña Recuperada!</h1>
			</td>
		</tr>
		
		<tr>
			<td>
				<p style="'.$css_Cuerpo.'">					
					Muy bien %s ya cuentas de nuevo con acceso a '.$TEmail.'
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
	$Email[1][5]['alt']='Gracias por verificar su cuenta';
	$Email[1][5]['title']="Verificación de Cuenta";
	$Email[1][5]['body']='
	<table cellspacing="0" cellpadding="0" border="0" width="100%%" style="'.$css_Tabla.'">
	<tbody>
		<tr>
			<td style="'.$css_Tabla_Imagen.'" align="center">
				<a style="'.$css_a.'" href="'.$server_cn.'" title="'.$Slogan.'" style="border:none" target="_blank"><img style="'.$css_LOGO.'" src="'.$src_Imagen.'" /></a>
			</td>
		</tr>
		
		<tr>
			<td align="center">
			<h1 style="'.$css_Titulo.'">Gracias</h1>
			</td>
		</tr>
		
		<tr>
			<td>
				<p style="'.$css_Cuerpo.'">					
					%s su cuenta a sido verificada exitósamente.
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
	$Email[1][6]['alt']='Mensaje de "Contáctenos';
	$Email[1][6]['title']="Contáctenos REF-%s";
	$Email[1][6]['body']='
	<table cellspacing="0" cellpadding="0" border="0" width="100%%" style="'.$css_Tabla.'">
	<tbody>
		<tr>
			<td style="'.$css_Tabla_Imagen.'" align="center">
				<a style="'.$css_a.'" href="'.$server_cn.'" title="'.$Slogan.'" style="border:none" target="_blank"><img style="'.$css_LOGO.'" src="'.$src_Imagen.'" /></a>
			</td>
		</tr>
		
		<tr>
			<td align="center">
			<h1 style="'.$css_Titulo.'">Contáctenos</h1>
			</td>
		</tr>
		
		<tr>
			<td>
				<div style="'.$css_Cuerpo.'">					
					Buen dia,
					<p>El usuario "%s" quiere contactarnos.</p>
					<p>El mensaje enviado es:
						<p>%s</p>
					</p>											
				</div>
			</td>
		</tr>
		
		<tr><td style="'.$css_Pie.'">
		Mensaje enviado automaticamente desde <a style="'.$css_a2.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
		</tr></td>
	</tbody>
	</table>';

	$Email[1][7]['alt']='Mensaje de "Contáctenos';
	$Email[1][7]['title']="Mensaje de Contacto";
	$Email[1][7]['body']='
	<table cellspacing="0" cellpadding="0" border="0" width="100%%" style="'.$css_Tabla.'">
	<tbody>
		<tr>
			<td style="'.$css_Tabla_Imagen.'" align="center">
				<a style="'.$css_a.'" href="'.$server_cn.'" title="'.$Slogan.'" style="border:none" target="_blank"><img style="'.$css_LOGO.'" src="'.$src_Imagen.'" /></a>
			</td>
		</tr>
		
		<tr>
			<td align="center">
				<h1 style="'.$css_Titulo.'">Contáctenos</h1>
			</td>
		</tr>
		
		<tr>
			<td>
				<div style="'.$css_Cuerpo.'">					
					Buen dia,
					<p>Se le asignó el numero %s a su mensaje. Estaremos dandole una pronta respuesta a su inquietud</p>					
				</div>
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
	$Email[1][8]['alt']='Notificación de cambio de correo electrónico';
	$Email[1][8]['title']="Cambio de Correo";
	$Email[1][8]['body']='
	<table cellspacing="0" cellpadding="0" border="0" width="100%%" style="'.$css_Tabla.'">
	<tbody>
		<tr>
			<td style="'.$css_Tabla_Imagen.'" align="center">
				<a style="'.$css_a.'" href="'.$server_cn.'" title="'.$Slogan.'" style="border:none" target="_blank"><img style="'.$css_LOGO.'" src="'.$src_Imagen.'" /></a>
			</td>
		</tr>
		
		<tr>
			<td align="center">
			<h1 style="'.$css_Titulo.'">Modificación de Correo Eletrónico</h1>
			</td>
		</tr>
		
		<tr>
			<td>
				<p style="'.$css_Cuerpo.'">
					Se modificó su dirección de correo. Su nueva dirección ahora es: <b>%s</b><br />
					<br />	
					<br />
					<br />	
					Debe verificar su cuenta haciendo clic <a style="'.$css_a.'" href="%s" title="Verificación de cuenta">aqui</a>
				</p>
			</td>
		</tr>
		
		<tr><td style="'.$css_Pie.'">
		Mensaje enviado automaticamente desde <a style="'.$css_a2.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
		</tr></td>
	</tbody>
	</table>';
}
?>
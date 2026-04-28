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
$css_Cuerpo2='font-size:14px;padding:10px;margin:10px;color:#333;';	
$css_Pie='background-color: #383b49;margin:5px;color:#FFF;font-size:12px; padding:5px;';

$css_Letrap="font-size:10px;";
$css_a='color:#f08424;';
$css_a1='color:#f08424;';
$css_a2='color:#FFF;';

/**********************************/
/********** RECORDATORIOS**********/
/**********************************/
// A USUARIO
$Email[1][550]['alt']='Recordatorio de Disponibles.co';
$Email[1][550]['title']="¿Estas disponible?";
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
				Vemos que no estas disponible. 
				Recuerda que puedes ponerte disponible en 
				<a style="'.$css_a.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
				o descargar la aplicación móvil para tu 
				<a style="'.$css_a.'" href="https://itunes.apple.com/es/app/%s/%s" title="Descargar">iPhone</a> o 
				la aplicación para tu móvil
				<a style="'.$css_a.'" href="https://play.google.com/store/apps/details?id=%s" title="Descargar">Android</a>
			</p>
		</td>
	</tr>	
	<tr><td style="'.$css_Pie.'">
	Mensaje enviado automaticamente desde <a style="'.$css_a2.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
	</tr></td>
</tbody>
</table>';
// A OTROS
$Email[1][551]['alt']='Notificaciones Disponibles';
$Email[1][551]['title']="Aviso Disponibles";
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
				En estos momentos tenemos a %s usuarios disponibles
			</p>
		</td>
	</tr>	
	<tr><td style="'.$css_Pie.'">
	Mensaje enviado automaticamente desde <a style="'.$css_a2.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
	</tr></td>
</tbody>
</table>';
// A OTROS
$Email[1][552]['alt']='Notificaciones Disponibles';
$Email[1][552]['title']="Notificación Disponibles";
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
				Te informamos que el usuario <a style="'.$css_a1.'" href="%s">%s</a> solicita que califiques su trajo en tu empresa <strong>%s</strong><br />
				Agradecemos te tomes unos minutos para calificar su trabajo haciendo click <a style="'.$css_a1.'" href="%s">aquí</a>
			</p>
		</td>
	</tr>	
	<tr><td style="'.$css_Pie.'">
	Mensaje enviado automaticamente desde <a style="'.$css_a2.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
	</tr></td>
</tbody>
</table>';
// A OTROS
$Email[1][553]['alt']='Descuentos en Disponibles.co';
$Email[1][553]['title']="Notificación Disponibles";
$Email[1][553]['body']='
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
				¿Pudiste contactar a alguno de nuestros Disponibles?<br />
				Te invitamos a ingresar a disponibles.co y contarnos cómo te fue con el candidato que elegiste para trabajar en tu empresa.<br />
				Evalúa su desempeño y recibe <strong>hasta el 10%% de descuento en la tarifa que te cobremos en tu próxima solicitud.</strong><br />
				<strong>Entra ya a <a style="'.$css_a1.'" href="%s">'.$FUrl.'</a></strong><br />

				<q>Porque conseguir personal nunca fue tan fácil.</q>
			</p>
		</td>
	</tr>	
	<tr><td style="'.$css_Pie.'">
	Mensaje enviado automaticamente desde <a style="'.$css_a2.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
	</tr></td>
</tbody>
</table>';

// SE POSTULA
$Email[1][554]['alt']='Aplicación de oferta en Disponibles.co';
$Email[1][554]['title']="Oferta en Disponibles.co";
$Email[1][554]['body']='
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
				En <a style="'.$css_a1.'" href="'.$server_cn.'" title="'.$Slogan.'">disponibles.co</a> ya estamos enterados de que te interesa la oferta de
				trabajo de <strong>%s</strong>, para el día %s.<br />
				Ahora la decisión está en manos de la empresa <strong>%s</strong>. Te sugerimos estar atento a nuestra comunicación en caso de que hayas sido seleccionado, para 
				continuar con el proceso.<br />
				En <a style="'.$css_a1.'" href="'.$server_cn.'" title="'.$Slogan.'">disponibles.co</a> queremos ayudarte.
			</p>
		</td>
	</tr>	
	<tr><td style="'.$css_Pie.'">
	Mensaje enviado automaticamente desde <a style="'.$css_a2.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
	</tr></td>
</tbody>
</table>';

// A EMPRESAS <- Automático
$Email[1][555]['alt']='Postulados a tu oferta en Disponibles.co';
$Email[1][555]['title']="Postulados en Disponibles.co";
$Email[1][555]['body']='
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
				Tenemos <strong>%s</strong> candidatos que quieren trabajar contigo para la postulación de tu oferta de <strong>%s</strong>, estos son los candidatos:<br />
				%s
				<br/>
				Elige el perfil que más se acomode a lo que necesitas en tu negocio.<br >
				En <a style="'.$css_a1.'" href="'.$server_cn.'" title="'.$Slogan.'">disponibles.co</a> queremos ayudarte.
			</p>
		</td>
	</tr>	
	<tr><td style="'.$css_Pie.'">
	Mensaje enviado automaticamente desde <a style="'.$css_a2.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
	</tr></td>
</tbody>
</table>';

// A USUARIOS (OFERTAS) <- Automático
$Email[1][556]['alt']='Ofertas en Disponibles.co';
$Email[1][556]['title']="Ofertas en Disponibles.co";
$Email[1][556]['body']='
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
				En este momento hay <strong>%s</strong> ofertas de trabajo para ti, entra ya y elige la que más se acomode a tus
				necesidades.<br />
				En <a style="'.$css_a1.'" href="'.$server_cn.'" title="'.$Slogan.'">disponibles.co</a> nos interesa ayudarte.
			</p>
		</td>
	</tr>	
	<tr><td style="'.$css_Pie.'">
	Mensaje enviado automaticamente desde <a style="'.$css_a2.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
	</tr></td>
</tbody>
</table>';

// A USUARIOS (GUARDADO) <- Automático
$Email[1][557]['alt']='Felicidades tu perfil fue seleccionado';
$Email[1][557]['title']="Felicidades tu perfil fue seleccionado";
$Email[1][557]['body']='
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
				Felicitaciones tu perfil le ha gustado a la empresa %s<br />
				¡Has sido seleccionado, ellos quieren trabajar contigo!<br />
				Datos importantes: <br />
				Lugar: 	%s<br />
				Día: 	%s<br />
				Hora: 	%s<br />
				Vestuario: 	%s<br />
				Recuerda estar puntual, esto demostrará tu interés desde el inicio y dará una imagen de respeto.
			</p>
		</td>
	</tr>	
	<tr><td style="'.$css_Pie.'">
	Mensaje enviado automaticamente desde <a style="'.$css_a2.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
	</tr></td>
</tbody>
</table>';

// A USUARIOS (GUARDADO) <- Automático
$Email[1][558]['alt']='Perfiles en Disponibles.co';
$Email[1][558]['title']="Perfiles en Disponibles.co";
$Email[1][558]['body']='
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
				Si ya te has contactado con <strong>%s</strong>, recuerda hacerle una buena inducción y entrenamiento de las 
				actividades particulares o aspectos que creas importantes ya que de esto depende su buen 
				desempeño, y no olvides un pago justo y un reconocimiento  a sus labores. Un personal motivado 
				y capacitado rinde entre un 20 y 30%% más en su productividad.
				<br />
				Puedes calificar su desempeño dando clic aquí: <a href="%s" style="'.$css_a1.'" title="Calificar">Calificar</a><br >
				En <a style="'.$css_a1.'" href="'.$server_cn.'" title="'.$Slogan.'">disponibles.co</a> queremos ayudarte.
			</p>
		</td>
	</tr>	
	<tr><td style="'.$css_Pie.'">
	Mensaje enviado automaticamente desde <a style="'.$css_a2.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
	</tr></td>
</tbody>
</table>';

// A USUARIOS (GUARDADO) <- Automático
$Email[1][559]['alt']='Felicidades desde Disponibles.co';
$Email[1][559]['title']="Felicidades desde Disponibles.co";
$Email[1][559]['body']='
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
				Ya fuiste seleccionado te felicitamos y esperamos que tu desempeño sea el mejor. En 
				disponibes.co confiamos en tus grandes habilidades y buena actitud. Ánimo, esperamos te vaya 
				muy bien.
			</p>
		</td>
	</tr>	
	<tr><td style="'.$css_Pie.'">
	Mensaje enviado automaticamente desde <a style="'.$css_a2.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
	</tr></td>
</tbody>
</table>';

// A USUARIOS WORDPRESS <- Automático
$Email[1][560]['alt']='Nueva Plataforma Disponibles.co';
$Email[1][560]['title']="Nueva plataforma de Disponibles.co";
$Email[1][560]['body']='
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
				Te invitamos a que conozcas nuestra nueva plataforma de <a style="'.$css_a1.'" href="'.$server_cn.'" title="'.$Slogan.'">disponibles.co</a><br />
				Estuvimos haciendo algunos cambios y tuvimos que modificar tus datos de acceso. Para ingresar y mantenerte informado podrás ingresar con el correo
				<strong>%s</strong> y la contraseña <strong>%s</strong>
			</p>
		</td>
	</tr>	
	<tr><td style="'.$css_Pie.'">
	Mensaje enviado automaticamente desde <a style="'.$css_a2.'" href="'.$server_cn.'" title="'.$Slogan.'">'.$FUrl.'</a>
	</tr></td>
</tbody>
</table>';
?>
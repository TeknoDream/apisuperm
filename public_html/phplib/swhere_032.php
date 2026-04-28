<?php 
/*
---------------------------------------------------------------------------------
                        SUPERMAESTROS BACKOFFICE
                             Proyecto N° 32
                        Archivo: swhere_032.php
       Descripción: archivo de configuración de barras de búsquedas
---------------------------------------------------------------------------------

Este es el archivo que configura la opción de buscar en las barras de búsqueda en
los módulos del backoffice. De igual manera que en las consultas se corresponde
con la numeración de los módulos.

Módulos de SuperMaestros
------------------------
N° 500 Usuarios
N° 501 Remodelaciones (inicialmente llamado Proyectos)
N° 502 Proyectos (inicialmente denominado Ofertas)
N° 503 Facturas
N° 504 Cotizaciones
N° 505 Estado de cuenta del instalador
N° 506 Noticias
N° 507 Publicidad
N° 508 Mensajes
N° 509 Especialidades

Para el caso de las búsquedas a la numeración natural de los módulos
se le agrega un 0, de esta forma el módulo 500 por ejemplo, sería
5000
*/
//Usuarios
if($tipo==5000){
	$sWhere=" AND (
				adm_usuarios.NOMBRE_U  LIKE :Buscar
			OR 	adm_usuarios.APELLIDO_U  LIKE :Buscar
			OR 	CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) LIKE :Buscar
			OR 	adm_usuarios.CORREO_U LIKE :Buscar) ";
}
// Remodelaciones
elseif($tipo==5010){
	$sWhere=" AND (	
			 	y_proyectos.NOMB_PROY  LIKE :Buscar
				 )";
}
//Proyectos
elseif($tipo==5020){
	$sWhere=" AND (
				x_ofertas.TITLE_OFERTA LIKE :Buscar
			OR	adm_usuarios.NOMBRE_U  LIKE :Buscar
			OR 	adm_usuarios.APELLIDO_U  LIKE :Buscar
			OR 	CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) LIKE :Buscar
			OR 	adm_usuarios.CORREO_U LIKE :Buscar) ";
}
//Facturas
elseif($tipo==5030){
	$sWhere=" (
				y_facturas.ID_FACT LIKE :Buscar
			OR	adm_usuarios.NOMBRE_U  LIKE :Buscar
			OR 	adm_usuarios.APELLIDO_U  LIKE :Buscar
			OR 	CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) LIKE :Buscar
				)";
}
//Cotizacion
elseif($tipo==5040){
	$sWhere=" (
				 y_cotizacion.ID_COTIZ LIKE :Buscar
			OR	 adm_usuarios_m.NOMBRE_M  LIKE :Buscar
			OR 	 adm_usuarios_m.APELLIDO_M  LIKE :Buscar
			OR 	CONCAT( adm_usuarios_m.NOMBRE_M,' ', adm_usuarios_m.APELLIDO_M) LIKE :Buscar
			OR  adm_usuarios_u.NOMBRE_U LIKE :Buscar
			OR 	adm_usuarios_u.APELLIDO_U  LIKE :Buscar
			OR 	CONCAT(  adm_usuarios_u.NOMBRE_U,' ',  adm_usuarios_u.APELLIDO_U) LIKE :Buscar
				)";

}
//Estado de Cuenta
elseif($tipo==5050){
	$sWhere=" (
				y_ecuenta.ID_ECUENTA LIKE :Buscar
			OR	adm_usuarios_m.NOMBRE_U  LIKE :Buscar
			OR 	adm_usuarios_m.APELLIDO_U  LIKE :Buscar
			OR 	CONCAT(adm_usuarios_m.NOMBRE_U,' ',adm_usuarios_m.APELLIDO_U) LIKE :Buscar
				)";
}
//Noticias
elseif($tipo==5060){
	$sWhere=" AND (
				y_noti.TITLE_NOTI LIKE :Buscar
			OR	adm_usuarios_op. NOMBRE_U_OP  LIKE :Buscar
			OR 	adm_usuarios_op.APELLIDO_U_OP  LIKE :Buscar
			OR 	CONCAT(adm_usuarios_op.NOMBRE_U_OP,' ',adm_usuarios.APELLIDO_U_OP) LIKE :Buscar
				)";
}
//Publicidad
elseif($tipo==5070){
	$sWhere=" AND (
				y_publicidad.NAME_PUBL LIKE :Buscar
			OR	y_publicidad.TITLE_PUBL LIKE :Buscar
			OR	adm_usuarios_op.NOMBRE_U_OP LIKE :Buscar
			OR 	adm_usuarios_op.,APELLIDO_U_OP  LIKE :Buscar
			OR 	CONCAT(adm_usuarios_op.NOMBRE_U_OP,' ',adm_usuarios_op.,APELLIDO_U_OP) LIKE :Buscar
				)";
}
//MSJ
elseif($tipo==5080){
	$sWhere="  (
				adm_usuarios_e.NOMBRE_U LIKE :Buscar
			OR 	adm_usuarios_e.APELLIDO_U  LIKE :Buscar
			OR 	CONCAT(adm_usuarios_e.NOMBRE_U ,' ',adm_usuarios_e.APELLIDO_U) LIKE :Buscar
			OR adm_usuarios_u.NOMBRE_U  LIKE :Buscar
			OR adm_usuarios_u.APELLIDO_U  LIKE :Buscar
			OR 	CONCAT(adm_usuarios_u.NOMBRE_U  ,' ',adm_usuarios_u.,APELLIDO_U) LIKE :Buscar
				)";
}
//ESPECIALIDAD
elseif($tipo==5090){
	$sWhere=" AND (
				z_espec.NAME_ESPEC LIKE :Buscar
				)";
}
 ?>
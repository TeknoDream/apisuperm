<?php
/*
---------------------------------------------------------------------------------
                        SUPERMAESTROS BACKOFFICE
                             Proyecto N° 32
                        Archivo: consultas_032.php
             Descripción: archivo de configuración de consultas
--------------------------------------------------------------------------------

Este es el archivo que configura todas las consultas que se hacen a la base de datos
de SuperMaestros, cada consulta se corresponde con un módulo o sección del backoffice
Cada proyecto tiene una numeración dentro del backend de SIIE, el número de proyecto
de SuperMaestros es el N° 32, por esta razón todos los archivos relacionados con 
SuperMaestros llevan la extensión _32.php

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

Funcionamiento de las Consultas 
-------------------------------

Hay módulos que tienen interacción y requieren información de diferentes tablas,
para esto se generan subconsultas que son indentificadas por el subindice en
la numeración de la variable tipo array $sqlCons, siendo el indice [0] la 
consulta principal del módulo y de [1] en adelante las subconsultas correspondientes
*/

/*************************/
/*************************/
/*****   Usuarios *********/
/*************************/ 
/*************************/
/*** SMUSU ***/
/*(SM de SuperMaestros y USU de usuario) */
/*SMUSU  es la nomeclantura interna para denominar cada uno de los objetos tipo JSON 
que son utilizados en el backoffice para mostrar la información, son los recuadros
que en el backoffice muestran la información con el título y datos del usuario o
del módulo que se este consultando, para el caso de usuarios es SMUSU pero cada módulo
tiene su objeto correspondiente en el caso de que sea mostrado como un objeto, en caso
contrario se trabaja en forma de tablas (consultar archivo base_inc_32.php)
*/ 

$sqlCons[0][500]="
SELECT 
	adm_usuarios.ID_USUARIO,
    adm_usuarios.ALIAS,
    adm_usuarios.NOMBRE_U,
    adm_usuarios.APELLIDO_U,
    DATE_FORMAT(CONVERT_TZ(adm_usuarios.FECHA_U,'+00:00','$_TZ'),'%d/%m/%Y %H:%i') AS FECHA_U,
    adm_usuarios.CORREO_U,
    adm_usuarios.ID_FILE,
    adm_usuarios.HAB_U,

    x_usuario.TEL1_USUARIO,
    x_usuario.TEL2_USUARIO,
    x_usuario.TYPE_USUARIO,
    x_usuario.VERIF_USUARIO,
    x_usuario.GCALIF_USUARIO,
    x_usuario.CCALIF_USUARIO,
    x_usuario.VCALIF_USUARIO,
    x_usuario.POINTS_USUARIO,
    x_usuario.DEST_USUARIO,
    x_usuario.BIO_USUARIO,
    x_usuario.PROYS_USAURIO,
    x_usuario.CC_USUARIO,
    fac_ciudades.ID_CIUDAD,
    fac_ciudades.NOMB_CIUDAD,

	/*IMAGEN*/
	IFNULL(adm_files.ID_FILE,0) AS M_IMG,
	adm_files.F_EXT,
	adm_files.F_HASH
FROM adm_usuarios
LEFT JOIN x_usuario ON x_usuario.ID_USUARIO=adm_usuarios.ID_USUARIO
LEFT JOIN adm_files ON adm_files.ID_FILE=adm_usuarios.ID_FILE

LEFT JOIN fac_ciudades ON fac_ciudades.ID_CIUDAD=x_usuario.ID_CIUDAD";
$sqlOrder[0][500]="ORDER BY adm_usuarios.FECHA_U DESC";

/*************************/
/**** Usuarios CHAR ******/
/*************************/ 
$sqlCons[1][500]="
SELECT 
	x_usuario_char.ID_CHAR,
    x_usuario_char.ID_USUARIO,
    x_usuario_char.KEY_CHAR
FROM x_usuario_char";
$sqlOrder[1][500]="ORDER BY x_usuario_char.KEY_CHAR";

/*************************/
/**** Usuarios URLS ******/
/*************************/ 
/*** :id_usuario **/
/*:id_usuario, es una variable utilizada en la consulta, que está asociada a la ID del usuario
que se está editando en el formulario, se usa para traer de la base de datos la información de
las redes sociales asociadas a la ID contenida en la variable.*/
$sqlCons[2][500]="
SELECT 
	fac_turls.ID_URLS,
    fac_turls.TIPO_URLS,
	fac_turls.DESC_URLS,
	fac_turls.EXAMPLE_URLS,

	IFNULL(x_usuario_rs.ID_USUARIO,:id_usuario) AS ID_USUARIO,
	IFNULL(x_usuario_rs.URLS,'') AS URLS
FROM fac_turls
LEFT JOIN x_usuario_rs ON x_usuario_rs.ID_USUARIO=:id_usuario AND x_usuario_rs.ID_URLS=fac_turls.ID_URLS";
$sqlOrder[2][500]="ORDER BY x_usuario_rs.URLS";

/*************************/
/**** Usuarios URLS ******/
/*************************/  
$sqlCons[3][500]="
SELECT 
    x_usuario_rs.ID_USUARIO,
	fac_turls.ID_URLS,
    fac_turls.TIPO_URLS,
	fac_turls.DESC_URLS,
	x_usuario_rs.URLS
FROM x_usuario_rs
LEFT JOIN fac_turls ON fac_turls.ID_URLS=x_usuario_rs.ID_URLS";
$sqlOrder[3][500]="ORDER BY fac_turls.DESC_URLS";

/*************************/
/**** Usuarios ESPEC *****/
/*************************/  
$sqlCons[4][500]="
SELECT 
    x_usuario_espec.ID_USUARIO,
    z_espec.ID_ESPEC,
    z_espec.NAME_ESPEC
FROM x_usuario_espec
INNER JOIN z_espec ON z_espec.ID_ESPEC=x_usuario_espec.ID_ESPEC";
$sqlOrder[4][500]="ORDER BY z_espec.NAME_ESPEC";
/*************************/
/**** Usuarios EXPEC *****/
/*************************/  
/** :id_usuario **/
/*:id_usuario, es una variable utilizada en la consulta, que está asociada a la ID del usuario
que se está editando en el formulario, se usa para traer de la base de datos la información de
las especialidades*/
$sqlCons[5][500]="
SELECT 
    z_espec.ID_ESPEC,
    z_espec.NAME_ESPEC,
    IFNULL(x_usuario_espec.ID_USUARIO,0) AS SELECTED
FROM z_espec
LEFT JOIN x_usuario_espec ON x_usuario_espec.ID_USUARIO=:id_usuario AND x_usuario_espec.ID_ESPEC=z_espec.ID_ESPEC";
$sqlOrder[5][500]="ORDER BY z_espec.NAME_ESPEC";

/*************************/
/*************************/
/**** Remodelaciones *****/
/*************************/ 
/*************************/
/*** SMPRO ***/
$sqlCons[0][501]="
SELECT 
	y_proyectos.ID_PROY,
    y_proyectos.NOMB_PROY,
    y_proyectos.DESC_PROY,
    y_proyectos.GCALIF_PROY,
    y_proyectos.CCALIF_PROY,
    y_proyectos.VCALIF_PROY,
    DATE_FORMAT(CONVERT_TZ(y_proyectos.FECHAS_PROY,'+00:00','$_TZ'),'%d/%m/%Y %H:%i') AS FECHAS_PROY,
    y_proyectos.COMMENTS_PROY,
    y_proyectos.HAB_PROY,
    y_proyectos.STATUS_PROY,
	adm_usuarios.ID_USUARIO,
    adm_usuarios.ALIAS,
	adm_usuarios.NOMBRE_U,
	adm_usuarios.APELLIDO_U,
	adm_usuarios.CORREO_U,

    y_proyectos_fotos.ID_FOTO,
    
    /*IMAGEN*/
    IFNULL(adm_files.ID_FILE,0) AS M_IMG,
    adm_files.F_EXT,
    adm_files.F_HASH
FROM y_proyectos
LEFT JOIN adm_usuarios ON adm_usuarios.ID_USUARIO=y_proyectos.ID_USUARIO
LEFT JOIN x_usuario ON x_usuario.ID_USUARIO=adm_usuarios.ID_USUARIO
LEFT JOIN y_proyectos_fotos ON y_proyectos_fotos.ID_PROY=y_proyectos.ID_PROY AND y_proyectos_fotos.MAIN_FOTO=1
LEFT JOIN adm_files ON adm_files.ID_FILE=adm_usuarios.ID_FILE";
$sqlOrder[0][501]="ORDER BY y_proyectos.FECHAS_PROY DESC";

/*************************/
/**** Proyectos FOTOS ****/
/*************************/ 
/*** SMPROFT ***/
$sqlCons[1][501]="
SELECT 
	y_proyectos_fotos.ID_FOTO,
    y_proyectos_fotos.ID_PROY,
    y_proyectos_fotos.FECHAS_FOTO,
    y_proyectos_fotos.TITLE_FOTO,
    y_proyectos_fotos.ORD_FOTO,
    y_proyectos_fotos.MAIN_FOTO,
    /*IMAGEN*/
	IFNULL(adm_files.ID_FILE,0) AS M_IMG,
	adm_files.F_EXT,
	adm_files.F_HASH
FROM y_proyectos_fotos
LEFT JOIN adm_files ON adm_files.ID_FILE=y_proyectos_fotos.ID_FILE";
$sqlOrder[1][501]="ORDER BY y_proyectos_fotos.MAIN_FOTO DESC, y_proyectos_fotos.ORD_FOTO";

/*************************/
/**** Proyectos CALIF ****/
/*************************/ 
$sqlCons[2][501]="
SELECT 
    y_proyectos_calif.ID_PROY,
    y_proyectos_calif.VAL_CALIF,
    DATE_FORMAT(CONVERT_TZ(y_proyectos_calif.FECHAS_CALIF,'+00:00','$_TZ'),'%d/%m/%Y %H:%i') AS FECHAS_CALIF,
    adm_usuarios.ID_USUARIO,
    adm_usuarios.NOMBRE_U,
    adm_usuarios.APELLIDO_U,
    adm_usuarios.CORREO_U
FROM y_proyectos_calif
LEFT JOIN adm_usuarios ON adm_usuarios.ID_USUARIO=y_proyectos_calif.ID_USAURIO";
$sqlOrder[2][501]="ORDER BY y_proyectos_calif.FECHAS_CALIF DESC";

/*************************/
/* Proyectos CALIF LITE **/
/*************************/ 
$sqlCons[3][501]="
SELECT 
    y_proyectos_calif.ID_PROY,
    y_proyectos_calif.VAL_CALIF,
    CONVERT_TZ(y_proyectos_calif.FECHAS_CALIF,'+00:00','$_TZ') AS FECHAS_CALIF,
    y_proyectos_calif.ID_USUARIO
FROM y_proyectos_calif";

/*************************/
/* Proyectos COMMENT **/
/*************************/ 
$sqlCons[4][501]="
SELECT y_proyectos_comment.ID_COMMENT,
    y_proyectos_comment.ID_PROY,
    DATE_FORMAT(CONVERT_TZ(y_proyectos_comment.FECHAS_COMMENT,'+00:00','$_TZ'),'%d/%m/%Y %H:%i') AS FECHAS_COMMENT,
    y_proyectos_comment.TEXT_COMMENT,

    adm_usuarios.ID_USUARIO,
    adm_usuarios.NOMBRE_U,
    adm_usuarios.APELLIDO_U,
    adm_usuarios.CORREO_U,
    
    /*IMAGEN*/
    IFNULL(adm_files.ID_FILE,0) AS M_IMG,
    adm_files.F_EXT,
    adm_files.F_HASH

FROM y_proyectos_comment
LEFT JOIN adm_usuarios ON adm_usuarios.ID_USUARIO=y_proyectos_comment.ID_USUARIO
LEFT JOIN adm_files ON adm_files.ID_FILE=adm_usuarios.ID_FILE";
$sqlOrder[4][501]="ORDER BY y_proyectos_comment.FECHAS_COMMENT";

/*** SOLO PARA GENERACION DE INFORMES **/
$sqlCons[5][501]="
SELECT 
    y_proyectos.ID_PROY,
    y_proyectos.NOMB_PROY,
    y_proyectos.DESC_PROY,
    y_proyectos.GCALIF_PROY,
    y_proyectos.CCALIF_PROY,
    y_proyectos.VCALIF_PROY,
    DATE_FORMAT(CONVERT_TZ(y_proyectos.FECHAS_PROY,'+00:00','$_TZ'),'%d/%m/%Y %H:%i') AS FECHAS_PROY,
    y_proyectos.COMMENTS_PROY,
    y_proyectos.HAB_PROY,
    y_proyectos.STATUS_PROY,

    adm_usuarios.ID_USUARIO,
    adm_usuarios.ALIAS,
    adm_usuarios.NOMBRE_U,
    adm_usuarios.APELLIDO_U,
    adm_usuarios.CORREO_U,

    y_proyectos_fotos.ID_FOTO,
    
    /*IMAGEN*/
    IFNULL(adm_files.ID_FILE,0) AS M_IMG,
    adm_files.F_EXT,
    adm_files.F_HASH,
    IFNULL(
        (SELECT COUNT(*)
        FROM y_proyectos_fotos
        WHERE y_proyectos_fotos.ID_PROY=y_proyectos.ID_PROY),0) AS C_FOTOS

FROM y_proyectos
LEFT JOIN adm_usuarios ON adm_usuarios.ID_USUARIO=y_proyectos.ID_USUARIO
LEFT JOIN x_usuario ON x_usuario.ID_USUARIO=adm_usuarios.ID_USUARIO
LEFT JOIN y_proyectos_fotos ON y_proyectos_fotos.ID_PROY=y_proyectos.ID_PROY AND y_proyectos_fotos.MAIN_FOTO=1
LEFT JOIN adm_files ON adm_files.ID_FILE=y_proyectos_fotos.ID_FILE";
$sqlOrder[5][501]="ORDER BY y_proyectos.FECHAS_PROY DESC";
/*************************/
/*************************/
/******** OFERTAS ********/
/*************************/ 
/*************************/
/*** SMOFER ***/
$sqlCons[0][502]="
SELECT 
    x_ofertas.ID_OFERTA,
    x_ofertas.TITLE_OFERTA,
    DATE_FORMAT(CONVERT_TZ(x_ofertas.FECHAS_OFERTA,'+00:00','$_TZ'),'%d/%m/%Y %H:%i') AS FECHAS_OFERTA,
    DATE_FORMAT(x_ofertas.FECHAI_OFERTA,'%d/%m/%Y') AS FECHAI_OFERTA,
    DATE_FORMAT(x_ofertas.FECHAF_OFERTA,'%d/%m/%Y') AS FECHAF_OFERTA,

    x_ofertas.FECHAI_OFERTA AS FECHAI_OFERTAF,
    x_ofertas.FECHAF_OFERTA AS FECHAF_OFERTAF,

    x_ofertas.COMENT_OFERT,
    x_ofertas.CONTACT_OFERTA,
    x_ofertas.HAB_OFERTA,

    z_espec.ID_ESPEC,
    z_espec.NAME_ESPEC,

    fac_ciudades.ID_CIUDAD,
    fac_ciudades.NOMB_CIUDAD,

    adm_usuarios.ID_USUARIO,
    adm_usuarios.NOMBRE_U AS NOMBRE_U_OP,    
    adm_usuarios.APELLIDO_U AS APELLIDO_U_OP,
    adm_usuarios.ALIAS AS ALIAS_U_OP,

    /*IMAGEN*/
    IFNULL(adm_files.ID_FILE,0) AS M_IMG,
    adm_files.F_EXT,
    adm_files.F_HASH

FROM x_ofertas
LEFT JOIN adm_usuarios ON adm_usuarios.ID_USUARIO=x_ofertas.ID_USUARIO
LEFT JOIN adm_files ON adm_files.ID_FILE=adm_usuarios.ID_FILE
LEFT JOIN fac_ciudades ON fac_ciudades.ID_CIUDAD=x_ofertas.ID_CIUDAD
LEFT JOIN z_espec ON z_espec.ID_ESPEC=x_ofertas.ID_ESPEC";
$sqlOrder[0][502]="ORDER BY x_ofertas.FECHAS_OFERTA DESC";
/***************************/
/**OFERTAS ESPECIALIDADES**/
/*************************/ 
$sqlCons[1][502]="
SELECT 
    z_espec.ID_ESPEC,
    z_espec.NAME_ESPEC,
    IFNULL(x_ofertas.ID_ESPEC,0) AS SELECTED
FROM z_espec
LEFT JOIN x_ofertas ON x_ofertas.ID_OFERTA=:id AND x_ofertas.ID_ESPEC=z_espec.ID_ESPEC";
$sqlOrder[1][503]="ORDER BY z_espec.ID_ESPEC ASC";

/*************************/
/*************************/
/******* FACTURAS ********/
/*************************/ 
/*************************/
$sqlCons[0][503]="
SELECT 
    y_facturas.ID_FACT,
    y_facturas.VPOINT_FACT,
    y_facturas.VALIDAT_FACT,
    y_facturas.ID_FILE,
    DATE_FORMAT(y_facturas.FECHAF_FACT,'%d/%m/%Y') AS FECHAF_FACT,
    DATE_FORMAT(CONVERT_TZ(y_facturas.FECHAS_FACT,'+00:00','$_TZ'),'%d/%m/%Y %H:%i') AS FECHAS_FACT,
    y_facturas.OBS_FACT,

    adm_usuarios.ID_USUARIO,
    adm_usuarios.NOMBRE_U AS NOMBRE_U_OP,
    adm_usuarios.APELLIDO_U AS APELLIDO_U_OP,

    /*IMAGEN*/
    IFNULL(adm_files.ID_FILE,0) AS M_IMG,
    adm_files.F_EXT,
    adm_files.F_HASH
FROM y_facturas
LEFT JOIN adm_usuarios ON adm_usuarios.ID_USUARIO=y_facturas.ID_USUARIO
LEFT JOIN adm_files ON adm_files.ID_FILE=y_facturas.ID_FILE";
$sqlOrder[0][503]="ORDER BY y_facturas.FECHAS_FACT DESC";

/*************************/
/*************************/
/****** COTIZACION *******/
/*************************/ 
/*************************/
/*** SMCOTI ***/
$sqlCons[0][504]="
SELECT 
    y_cotizacion.ID_COTIZ,
    y_cotizacion.STATUS_COTIZ,
    DATE_FORMAT(CONVERT_TZ(y_cotizacion.FECHAS_COTIZ,'+00:00','$_TZ'),'%d/%m/%Y %H:%i') AS FECHAS_COTIZ,
    y_cotizacion.VTOT_COTIZ,

    adm_usuarios_m.ID_USUARIO AS ID_USUARIO_M,
    adm_usuarios_m.NOMBRE_U AS NOMBRE_M,
    adm_usuarios_m.APELLIDO_U AS APELLIDO_M,
    IFNULL(adm_files_m.ID_FILE,0) AS M_IMG_M,
    adm_files_m.F_EXT AS F_EXT_M,
    adm_files_m.F_HASH AS F_HASH_M,

    adm_usuarios_u.ID_USUARIO AS ID_USUARIO_U,
    adm_usuarios_u.NOMBRE_U AS NOMBRE_U,
    adm_usuarios_u.APELLIDO_U AS APELLIDO_U,
    adm_usuarios_u.CORREO_U AS CORREO_U,
    IFNULL(adm_files_u.ID_FILE,0) AS M_IMG_U,
    adm_files_u.F_EXT AS F_EXT_U,
    adm_files_u.F_HASH AS F_HASH_U

FROM y_cotizacion
LEFT JOIN adm_usuarios adm_usuarios_m ON adm_usuarios_m.ID_USUARIO=y_cotizacion.ID_USUARIO_M
LEFT JOIN adm_usuarios adm_usuarios_u ON adm_usuarios_u.ID_USUARIO=y_cotizacion.ID_USUARIO_U
LEFT JOIN adm_files adm_files_m ON adm_files_m.ID_FILE=adm_usuarios_m.ID_FILE
LEFT JOIN adm_files adm_files_u ON adm_files_u.ID_FILE=adm_usuarios_u.ID_FILE";
$sqlOrder[0][504]="ORDER BY y_cotizacion.FECHAS_COTIZ DESC";


/*************************/
/*** COTIZACION ITEMS ****/
/*************************/ 
$sqlCons[1][504]="
SELECT 
    y_cotizacion_items.ID_ITEM,
    y_cotizacion_items.ID_COTIZ,
    y_cotizacion_items.NAME_ITEM,
    y_cotizacion_items.CANT_ITEM,
    y_cotizacion_items.PREC_ITEM,
    y_cotizacion_items.ORD_ITEM
FROM y_cotizacion_items";
$sqlOrder[1][504]="ORDER BY y_cotizacion_items.ORD_ITEM ASC";

/*************************/
/*************************/
/******** ECUENTA ********/
/*************************/ 
/*************************/
$sqlCons[0][505]="
SELECT 
    y_ecuenta.ID_ECUENTA,
    y_ecuenta.COMMEN_ECUENTA,
    adm_usuarios_m.ID_USUARIO AS ID_USUARIO_M,
    adm_usuarios_m.NOMBRE_U AS NOMBRE_U_M,
    adm_usuarios_m.APELLIDO_U AS APELLIDO_U_M,

    adm_usuarios_a.ID_USUARIO AS ID_USUARIO_A,
    adm_usuarios_a.NOMBRE_U AS NOMBRE_A,
    adm_usuarios_a.APELLIDO_U AS APELLIDO_A,

    y_ecuenta.ID_FACT,
    DATE_FORMAT(CONVERT_TZ(y_ecuenta.FECHAS_ECUENTA,'+00:00','$_TZ'),'%d/%m/%Y %H:%i') AS FECHAS_ECUENTA,
    y_ecuenta.INIP_ECUENTA,
    y_ecuenta.OUTP_ECUENTA
FROM y_ecuenta
LEFT JOIN adm_usuarios adm_usuarios_m ON adm_usuarios_m.ID_USUARIO=y_ecuenta.ID_USUARIO_M
LEFT JOIN adm_usuarios adm_usuarios_a ON adm_usuarios_a.ID_USUARIO=y_ecuenta.ID_USUARIO_A";
$sqlOrder[0][505]="ORDER BY y_ecuenta.FECHAS_ECUENTA DESC";
/*************************/
/*************************/
/******** NOTICIAS *******/
/*************************/ 
/*************************/
/***  SMNOTI ***/
$sqlCons[0][506]="
SELECT 
    y_noti.ID_NOTI,
    y_noti.TYPE_NOTI,
    y_noti.TITLE_NOTI,
    y_noti.SLUG_NOTI,
    y_noti.MTITLE_NOTI,
    y_noti.MDESC_NOTI,
    y_noti.ACTIV_NOTI,
    DATE_FORMAT(CONVERT_TZ(y_noti.FECHAS_NOTI,'+00:00','$_TZ'),'%d/%m/%Y %H:%i') AS FECHAS_NOTI,

    y_noti.HAB_NOTI,    
    adm_usuarios.ID_USUARIO,
    adm_usuarios.NOMBRE_U,
    adm_usuarios.APELLIDO_U,

    IFNULL(adm_files.ID_FILE,0) AS M_IMG,
    adm_files.F_EXT AS F_EXT,
    adm_files.F_HASH AS F_HASH,

    y_noti.CONT_NOTI

FROM y_noti
LEFT JOIN adm_usuarios ON adm_usuarios.ID_USUARIO=y_noti.ID_USUARIO
LEFT JOIN adm_files ON adm_files.ID_FILE=y_noti.ID_FILE";
$sqlOrder[0][506]="ORDER BY y_noti.FECHAS_NOTI DESC";

/*************************/
/*************************/
/******* PUBLICIDAD ******/
/*************************/ 
/*************************/
/***  SMPUBL ***/
$sqlCons[0][507]="
SELECT 
    y_publicidad.ID_PUBL,
    y_publicidad.NAME_PUBL,
    y_publicidad.TITLE_PUBL,

    DATE_FORMAT(CONVERT_TZ(y_publicidad.FECHAS_PUBL,'+00:00','$_TZ'),'%d/%m/%Y %H:%i')  AS FECHAS_PUBL,
    DATE_FORMAT(CONVERT_TZ(y_publicidad.FECHAI_PUBL,'+00:00','$_TZ'),'%d/%m/%Y %H:%i')  AS FECHAI_PUBL,
    DATE_FORMAT(CONVERT_TZ(y_publicidad.FECHAF_PUBL,'+00:00','$_TZ'),'%d/%m/%Y %H:%i')  AS FECHAF_PUBL,

    y_publicidad.ACTI_PUBL,
    y_publicidad.TYP_PUBL,
    y_publicidad.MOVIL_PUBL,
    y_publicidad.HAB_PUBL,

    adm_usuarios_op.ID_USUARIO AS ID_USUARIO_OP,
    adm_usuarios_op.NOMBRE_U AS NOMBRE_U_OP,
    adm_usuarios_op.APELLIDO_U AS APELLIDO_U_OP,

    IFNULL(adm_files.ID_FILE,0) AS M_IMG,
    adm_files.F_EXT,
    adm_files.F_HASH

FROM y_publicidad
LEFT JOIN adm_usuarios adm_usuarios_op ON adm_usuarios_op.ID_USUARIO=y_publicidad.ID_USUARIO
LEFT JOIN adm_files ON adm_files.ID_FILE=y_publicidad.ID_FILE";
$sqlOrder[0][507]="ORDER BY y_publicidad.NAME_PUBL";

/*************************/
/*************************/
/******* MENSAJES ********/
/*************************/ 
/*************************/
$sqlCons[0][508]="
SELECT 
    y_message.ID_MSG,
    DATE_FORMAT(CONVERT_TZ(y_message.FECHAS_MSG,'+00:00','$_TZ'),'%d/%m/%Y %H:%i') AS FECHAS_MSG,

    adm_usuarios_e.ID_USUARIO AS ID_USUARIO_E,
    adm_usuarios_e.NOMBRE_U AS NOMBRE_E,
    adm_usuarios_e.APELLIDO_U AS APELLIDO_E,
    IFNULL(adm_files_e.ID_FILE,0) AS M_IMG_E,
    adm_files_e.F_EXT AS F_EXT_E,
    adm_files_e.F_HASH AS F_HASH_E,

    adm_usuarios_u.ID_USUARIO AS ID_USUARIO_U,
    adm_usuarios_u.NOMBRE_U AS NOMBRE_U,
    adm_usuarios_u.APELLIDO_U AS APELLIDO_U,
    IFNULL(adm_files_u.ID_FILE,0) AS M_IMG_U,
    adm_files_u.F_EXT AS F_EXT_U,
    adm_files_u.F_HASH AS F_HASH_U,

    y_message.MSG_TXT
FROM y_message
LEFT JOIN adm_usuarios adm_usuarios_e ON adm_usuarios_e.ID_USUARIO=y_message.ID_USUARIO_E
LEFT JOIN adm_usuarios adm_usuarios_u ON adm_usuarios_u.ID_USUARIO=y_message.ID_USUARIO_R

LEFT JOIN adm_files adm_files_e ON adm_files_e.ID_FILE=adm_usuarios_e.ID_FILE
LEFT JOIN adm_files adm_files_u ON adm_files_u.ID_FILE=adm_usuarios_u.ID_FILE";
$sqlOrder[0][508]="ORDER BY y_message.FECHAS_MSG DESC";

/*************************/
/*************************/
/****** MSJ LISTA ********/
/*************************/ 
/*************************/
$sqlCons[1][508]="
SELECT 
    adm_usuarios_c.ID_USUARIO,
    adm_usuarios_c.NOMBRE_U,
    adm_usuarios_c.APELLIDO_U,
    DATE_FORMAT(CONVERT_TZ(y_message_lst.FECHAS_MSGLST,'+00:00','$_TZ'),'%d/%m/%Y %H:%i') AS FECHAS_MSGLST,
    IFNULL(adm_files_c.ID_FILE,0) AS M_IMG,
    adm_files_c.F_EXT AS F_EXT,
    adm_files_c.F_HASH AS F_HASH
    
FROM y_message_lst
LEFT JOIN adm_usuarios adm_usuarios_c ON adm_usuarios_c.ID_USUARIO=y_message_lst.ID_USUARIO_CNT
LEFT JOIN adm_files adm_files_c ON adm_files_c.ID_FILE=adm_usuarios_c.ID_FILE";
$sqlOrder[1][508]="ORDER BY y_message_lst.FECHAS_MSGLST DESC";


/*************************/
/*************************/
/**** ESPECIALIDAD *******/
/*************************/ 
/*************************/
$sqlCons[0][509]="
SELECT 
    z_espec.ID_ESPEC,
    z_espec.NAME_ESPEC,
    z_espec.HAB_ESPEC
FROM z_espec";
$sqlOrder[0][509]="ORDER BY z_espec.NAME_ESPEC";


/*** TO SEND */

$sqlCons[10][45]="
SELECT fac_ciudades.ID_CIUDAD as id,
fac_ciudades.NOMB_CIUDAD as name,
fac_ciudades.URL_CIUDAD AS slug
FROM fac_ciudades
WHERE fac_ciudades.ID_PAIS=48";
$sqlOrder[10][45]="ORDER BY fac_ciudades.NOMB_CIUDAD";

$sqlCons[10][509]="
SELECT 
    z_espec.ID_ESPEC as id,
    z_espec.NAME_ESPEC as name
FROM z_espec
WHERE z_espec.HAB_ESPEC=0";
$sqlOrder[10][509]="ORDER BY z_espec.NAME_ESPEC";

?>
<?php

function Consultas(&$sqlCons,&$sqlOrder,$_PROYECTO=0,$_CLIENTE=0,$_IDIOMA=0,$_TZ='',$_GCLIENTE=0,$_USUARIO=0,$_GRUPO=0,$_PARAMETROS=array()){
	/*ADMIN*/
	$sqlCons[0][0]="
	SELECT 
	a_empresas.ID_EMPRESA,
	a_empresas.USN_EMPRESA,
	a_empresas.RS_EMPRESA,
	a_empresas.FECHA_EMPRESA,
	a_empresas.HAB_EMPRESA,

	a_empresa_srv.ID_PRODUCTO,
	a_empresa_srv.SRVN_EMPRESA,
	a_empresa_srv.USN_EMPRESA AS USRNAME_DB,
	a_empresa_srv.BD_EMPRESA AS NOMOB_DB,
	a_empresa_srv.PSW_EMPRESA,

	IFNULL(a_empresa_srv.ID_PRODUCTO,0) AS PRODUCTO,
	a_producto.NOMB_PRODUCTO,
	a_producto.SUB_CARPETA,
	a_empresa_srv_url.URL,
	a_empresa_srv_url.WEB_NEW

	FROM a_empresas
	LEFT JOIN a_empresa_srv ON a_empresa_srv.ID_EMPRESA=a_empresas.ID_EMPRESA
	LEFT JOIN a_empresa_srv_url ON a_empresa_srv_url.ID_EMPRESA=a_empresa_srv.ID_EMPRESA AND a_empresa_srv_url.ID_PRODUCTO=a_empresa_srv.ID_PRODUCTO
	LEFT JOIN a_producto ON a_producto.ID_PRODUCTO=a_empresa_srv.ID_PRODUCTO AND a_producto.HAB_PRODUCTO=0";
	$sqlOrder[0][0]="ORDER BY a_empresas.USN_EMPRESA,a_producto.NOMB_PRODUCTO";

	//USUARIOS
	
	$sqlCons[1][0]="
	SELECT 
	'USU' AS OPCION,
	adm_usuarios.ID_USUARIO,
	adm_usuarios.ALIAS,
	adm_usuarios.NOMBRE_U,
	adm_usuarios.APELLIDO_U,
	CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) AS USUARIO_COMP,
	adm_usuarios_empresa.ID_GRUPO,
	adm_usuarios.PASSWORD_U,
	adm_usuarios.ID_IDIOMA,

	fac_idioma.NAV01,
	fac_idioma.NAV03,

	DATE_FORMAT(CONVERT_TZ(adm_usuarios.FECHA_U,'+00:00','$_TZ'),'%d/%m/%Y') AS FECHA_UF,
	adm_usuarios.CORREO_U,
	adm_usuarios.HAB_U,
	adm_usuarios.FECHA_U,
	
	IF(ISNULL(adm_usuarios_datos.ID_USUARIO),0,1) AS DATOS_US,
	adm_usuarios_datos.ID_DOCUMENTO,	
	adm_usuarios_datos.DOCUMENTO,
	/*IFNULL(adm_usuarios_datos.ID_CIUDAD,0) AS ID_CIUDAD,*/
	IFNULL(adm_usuarios_datos.ID_GENERO,1) AS ID_GENERO,
	adm_usuarios_datos.TELEFONO_U,
	adm_usuarios_datos.TELEFONO2_U,
	adm_usuarios_datos.DIRECCION_U,
	
	fac_tdocumento.ABR_DOCUMENTO,
	fac_tdocumento.DOCUMENTO AS DOCUMENTO_TIPO,

	fac_tz.ID_TZ,
	fac_tz.TZ_DIFE,

	fac_genero.ABR_GENERO,
	fac_genero.GENERO,
	adm_grupos.DESC_GRUPO,
	adm_grupos.APP_GRUPO,
	IFNULL(adm_usuarios_facebook.ID_USUARIO,0) AS FACEBOOK,
	IFNULL(adm_usuarios_twitter.ID_USUARIO,0) AS TWITTER,
	adm_usuarios_twitter.TOKEN_TW,
	adm_usuarios_twitter.STOKEN_TW,
	adm_usuarios_twitter.ID_TW,
	adm_usuarios_facebook.ID_FACEBOOK,
	adm_usuarios_facebook.TOKEN_FB,
	(SELECT COUNT(*) FROM adm_usuarios_empresa WHERE adm_usuarios_empresa.ID_USUARIO=adm_usuarios.ID_USUARIO) AS EMPRESAS

	FROM adm_usuarios
	LEFT JOIN adm_usuarios_empresa ON adm_usuarios_empresa.ID_USUARIO=adm_usuarios.ID_USUARIO AND adm_usuarios_empresa.ID_MEMPRESA=$_CLIENTE
	LEFT JOIN adm_grupos ON adm_grupos.ID_GRUPO=adm_usuarios_empresa.ID_GRUPO
	LEFT JOIN adm_usuarios_datos ON adm_usuarios_datos.ID_USUARIO=adm_usuarios.ID_USUARIO

	LEFT JOIN fac_idioma ON fac_idioma.ID_IDIOMA=adm_usuarios.ID_IDIOMA
	LEFT JOIN fac_tdocumento ON fac_tdocumento.ID_DOCUMENTO=adm_usuarios_datos.ID_DOCUMENTO AND fac_tdocumento.ID_IDIOMA=$_IDIOMA
	LEFT JOIN fac_tz ON fac_tz.ID_TZ=IFNULL(adm_usuarios_datos.ID_TZ,116)
	LEFT JOIN fac_genero ON fac_genero.ID_GENERO=adm_usuarios_datos.ID_GENERO AND fac_genero.ID_IDIOMA=$_IDIOMA
	
	LEFT JOIN adm_usuarios_facebook ON adm_usuarios_facebook.ID_USUARIO=adm_usuarios.ID_USUARIO
	LEFT JOIN adm_usuarios_twitter ON adm_usuarios_twitter.ID_USUARIO=adm_usuarios.ID_USUARIO";
	
	if(($_PROYECTO==13)||($_PROYECTO==8)){
		$sqlCons[1][0]="
		SELECT 
		'USU' AS OPCION,
		adm_usuarios.ID_USUARIO,
		adm_usuarios.ALIAS,
		adm_usuarios.NOMBRE_U,
		adm_usuarios.APELLIDO_U,
		CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) AS USUARIO_COMP,
		adm_usuarios_empresa.ID_GRUPO,
		adm_usuarios.PASSWORD_U,
		adm_usuarios.ID_IDIOMA,

		fac_idioma.NAV01,
		fac_idioma.NAV03,
		
		DATE_FORMAT(CONVERT_TZ(adm_usuarios.FECHA_U,'+00:00','$_TZ'),'%d/%m/%Y') AS FECHA_UF,
		adm_usuarios.CORREO_U,
		adm_usuarios.HAB_U,
		adm_usuarios.FECHA_U,
		
		IF(ISNULL(adm_usuarios_datos.ID_USUARIO),0,1) AS DATOS_US,
		adm_usuarios_datos.ID_DOCUMENTO,
		adm_usuarios_datos.DOCUMENTO,
		/*IFNULL(adm_usuarios_datos.ID_CIUDAD,0) AS ID_CIUDAD,*/
		IFNULL(adm_usuarios_datos.ID_GENERO,1) AS ID_GENERO,
		adm_usuarios_datos.TELEFONO_U,
		adm_usuarios_datos.TELEFONO2_U,
		adm_usuarios_datos.DIRECCION_U,
		
		fac_tdocumento.ABR_DOCUMENTO,
		fac_tdocumento.DOCUMENTO AS DOCUMENTO_TIPO,
		fac_tz.ID_TZ,
		fac_tz.TZ_DIFE,
		fac_genero.ABR_GENERO,
		fac_genero.GENERO,
		adm_grupos.DESC_GRUPO,
		adm_grupos.APP_GRUPO,
		IFNULL(adm_usuarios_facebook.ID_USUARIO,0) AS FACEBOOK,
		IFNULL(adm_usuarios_twitter.ID_USUARIO,0) AS TWITTER,
		adm_usuarios_twitter.TOKEN_TW,
		adm_usuarios_twitter.STOKEN_TW,
		adm_usuarios_twitter.ID_TW,
		adm_usuarios_facebook.ID_FACEBOOK,
		adm_usuarios_facebook.TOKEN_FB,
		fac_moneda.ID_MONEDA,
		fac_moneda.MONEDA,
		fac_moneda.COD01_MONEDA,
		fac_moneda.COD02_MONEDA,
		(SELECT COUNT(*) FROM adm_usuarios_empresa WHERE adm_usuarios_empresa.ID_USUARIO=adm_usuarios.ID_USUARIO) AS EMPRESAS

		FROM adm_usuarios
		LEFT JOIN adm_usuarios_empresa ON adm_usuarios_empresa.ID_USUARIO=adm_usuarios.ID_USUARIO AND adm_usuarios_empresa.ID_MEMPRESA=$_CLIENTE
		LEFT JOIN adm_grupos ON adm_grupos.ID_GRUPO=adm_usuarios_empresa.ID_GRUPO
		LEFT JOIN adm_usuarios_datos ON adm_usuarios_datos.ID_USUARIO=adm_usuarios.ID_USUARIO

		LEFT JOIN fac_idioma ON fac_idioma.ID_IDIOMA=adm_usuarios.ID_IDIOMA

		LEFT JOIN fac_tdocumento ON fac_tdocumento.ID_DOCUMENTO=adm_usuarios_datos.ID_DOCUMENTO AND fac_tdocumento.ID_IDIOMA=$_IDIOMA
		LEFT JOIN fac_tz ON fac_tz.ID_TZ=IFNULL(adm_usuarios_datos.ID_TZ,116)
		LEFT JOIN fac_genero ON fac_genero.ID_GENERO=adm_usuarios_datos.ID_GENERO AND fac_genero.ID_IDIOMA=$_IDIOMA

		LEFT JOIN fac_moneda ON fac_moneda.ID_MONEDA=adm_usuarios.ID_MONEDA AND fac_moneda.ID_IDIOMA=$_IDIOMA
		
		LEFT JOIN adm_usuarios_facebook ON adm_usuarios_facebook.ID_USUARIO=adm_usuarios.ID_USUARIO
		LEFT JOIN adm_usuarios_twitter ON adm_usuarios_twitter.ID_USUARIO=adm_usuarios.ID_USUARIO															
		";
	}
	$sqlOrder[1][0]="ORDER BY adm_usuarios.ALIAS";
	/*VENTANAS CONTENIDOS*/
		
	$sqlCons[1][1]="
	SELECT adm_ventanas_cont.ID_VENTANA,
	IFNULL(adm_ventanas_cont_names.TITULO_VENTANA,adm_empresas_v_cont_names.TITULO_VENTANA) AS TITULO_VENTANA,
	IFNULL(adm_ventanas_cont_names.STITULO_VENTANA,adm_empresas_v_cont_names.STITULO_VENTANA) AS STITULO_VENTANA,
	adm_ventanas_cont.TABLA_VENTANA,
	adm_ventanas_cont.TABLA_ID_VENTANA,
	adm_ventanas_cont.TABLA_ORDER_VENTANA,
	adm_ventanas_cont.TABLA_HAB_VENTANA,
	adm_ventanas_cont.SNAME_VENTANA,
	adm_ventanas_cont.RESU_VENTANA,
	adm_ventanas_cont.IDIOMA AS IDI_VENTANA,
	adm_ventanas_cont.MEMPRESA AS MEMP_VENTANA,
	adm_ventanas_cont.HAB_VENTANA,

	adm_ventanas_cont_campo.NOMBRE_CAMPO,
	adm_ventanas_cont_campo.ORDEN_CAMPO,
	adm_ventanas_cont_campo.TAMANO_CAMPO,

	adm_ventanas_cont_campo.TIPO_CAMPO,
	adm_ventanas_cont_campo.TABLA_CAMPO,
	adm_ventanas_cont_campo.TIDI_CAMPO,
	adm_ventanas_cont_campo.TMEM_CAMPO,
	adm_ventanas_cont_campo.TGEMP_CAMPO,
	adm_ventanas_cont_campo.REQ_CAMPO,


	IFNULL(adm_ventanas_cont_campo_names.TITULO_CAMPO,adm_empresas_v_cont_campo_names.TITULO_CAMPO) AS TITULO_CAMPO,
	IFNULL(adm_ventanas_cont_campo_names.TOOLTIP_CAMPO,adm_empresas_v_cont_campo_names.TOOLTIP_CAMPO) AS TOOLTIP_CAMPO
	FROM adm_ventanas_cont
	LEFT JOIN adm_ventanas_cont_names ON adm_ventanas_cont_names.ID_VENTANA=adm_ventanas_cont.ID_VENTANA AND adm_ventanas_cont_names.ID_IDIOMA=$_IDIOMA AND adm_ventanas_cont_names.ID_MEMPRESA=$_CLIENTE
	LEFT JOIN adm_empresas_v_cont_names ON adm_empresas_v_cont_names.ID_VENTANA=adm_ventanas_cont.ID_VENTANA AND adm_empresas_v_cont_names.ID_IDIOMA=$_IDIOMA AND adm_empresas_v_cont_names.TIPO_GRUPOPAL=$_GCLIENTE


	LEFT JOIN adm_ventanas_cont_campo ON adm_ventanas_cont_campo.ID_VENTANA=adm_ventanas_cont.ID_VENTANA
	LEFT JOIN adm_ventanas_cont_campo_names ON adm_ventanas_cont_campo_names.ID_CAMPO=adm_ventanas_cont_campo.ID_CAMPO AND adm_ventanas_cont_campo_names.ID_IDIOMA=$_IDIOMA AND adm_ventanas_cont_campo_names.ID_MEMPRESA=$_CLIENTE
	LEFT JOIN adm_empresas_v_cont_campo_names ON adm_empresas_v_cont_campo_names.ID_CAMPO=adm_ventanas_cont_campo.ID_CAMPO AND adm_empresas_v_cont_campo_names.ID_IDIOMA=$_IDIOMA AND adm_empresas_v_cont_campo_names.TIPO_GRUPOPAL=$_GCLIENTE";
	$sqlOrder[1][1]="ORDER BY adm_ventanas_cont_campo.ORDEN_CAMPO";

	$sqlCons[1][2]="
	SELECT adm_ventanas.ID_VENTANA,
	IFNULL(adm_ventanas_names.VENTANA_NOMBRE,adm_empresas_v_names.VENTANA_NOMBRE) AS VENTANA_NOMBRE,
	adm_ventanas.VENTANA_AGRUPA,
	IFNULL(adm_ventanas_names.SCVENTANA,adm_empresas_v_names.SCVENTANA) AS SCVENTANA,
	adm_ventanas_grupo.ID_GVENTANA,
	IFNULL(adm_ventanas_grupo_name.DESC_GVENTANA,adm_empresas_v_grupo_name.DESC_GVENTANA) AS DESC_GVENTANA
	FROM adm_ventanas
	LEFT JOIN adm_ventanas_names ON adm_ventanas_names.ID_VENTANA=adm_ventanas.ID_VENTANA AND adm_ventanas_names.ID_IDIOMA=$_IDIOMA AND adm_ventanas_names.ID_MEMPRESA=$_CLIENTE
	LEFT JOIN adm_empresas_v_names ON adm_empresas_v_names.ID_VENTANA=adm_ventanas.ID_VENTANA AND adm_empresas_v_names.ID_IDIOMA=$_IDIOMA AND adm_empresas_v_names.TIPO_GRUPOPAL=$_GCLIENTE

	LEFT JOIN adm_ventanas_grupo ON adm_ventanas_grupo.ID_GVENTANA=adm_ventanas.ID_GVENTANA
	LEFT JOIN adm_ventanas_grupo_name ON adm_ventanas_grupo_name.ID_GVENTANA=adm_ventanas_grupo.ID_GVENTANA AND adm_ventanas_grupo_name.ID_IDIOMA=$_IDIOMA AND adm_ventanas_grupo_name.ID_MEMPRESA=$_CLIENTE
	LEFT JOIN adm_empresas_v_grupo_name ON adm_empresas_v_grupo_name.ID_GVENTANA=adm_ventanas_grupo.ID_GVENTANA AND adm_empresas_v_grupo_name.ID_IDIOMA=$_IDIOMA AND adm_empresas_v_grupo_name.TIPO_GRUPOPAL=$_GCLIENTE
	WHERE adm_ventanas.ID_VENTANA IN (
			SELECT adm_grupos_ven.ID_VENTANA
			FROM adm_usuarios
			LEFT JOIN adm_usuarios_empresa ON adm_usuarios_empresa.ID_USUARIO=adm_usuarios.ID_USUARIO AND adm_usuarios_empresa.ID_MEMPRESA=$_CLIENTE
			JOIN adm_grupos_ven ON (adm_grupos_ven.ID_GRUPO=adm_usuarios_empresa.ID_GRUPO)
			WHERE adm_usuarios.ID_USUARIO=%s
			)
		AND adm_ventanas.VENTANA_AGRUPA=%s";
	$sqlOrder[1][2]="ORDER BY IFNULL(adm_ventanas_names.VENTANA_NOMBRE,adm_empresas_v_names.VENTANA_NOMBRE)";

	/*VENTANAS TITULOS*/

		$sqlCons[1][3]="
		SELECT adm_ventanas_cont.ID_VENTANA,
		IFNULL(adm_ventanas_cont_names.TITULO_VENTANA,adm_empresas_v_cont_names.TITULO_VENTANA) AS TITULO_VENTANA,
		IFNULL(adm_ventanas_cont_names.STITULO_VENTANA,adm_empresas_v_cont_names.STITULO_VENTANA) AS STITULO_VENTANA,
		adm_ventanas_cont.TABLA_VENTANA,
		adm_ventanas_cont.TABLA_ID_VENTANA,
		adm_ventanas_cont.TABLA_ORDER_VENTANA,
		adm_ventanas_cont.TABLA_HAB_VENTANA,
		adm_ventanas_cont.RESU_VENTANA,
		adm_ventanas_cont.HAB_VENTANA
		FROM adm_ventanas_cont
		LEFT JOIN adm_ventanas_cont_names ON adm_ventanas_cont_names.ID_VENTANA=adm_ventanas_cont.ID_VENTANA AND adm_ventanas_cont_names.ID_IDIOMA=$_IDIOMA AND adm_ventanas_cont_names.ID_MEMPRESA=$_CLIENTE
		LEFT JOIN adm_empresas_v_cont_names ON adm_empresas_v_cont_names.ID_VENTANA=adm_ventanas_cont.ID_VENTANA AND adm_empresas_v_cont_names.ID_IDIOMA=$_IDIOMA AND adm_empresas_v_cont_names.TIPO_GRUPOPAL=$_GCLIENTE ";
		$sqlOrder[1][3]="ORDER BY IFNULL(adm_ventanas_cont_names.TITULO_VENTANA,adm_empresas_v_cont_names.TITULO_VENTANA)";

	//PAISES
	$sqlCons[1][5]="SELECT *  FROM fac_pais";
	$sqlOrder[1][5]="ORDER BY NOMB_PAIS";
	//CIUDAD
	$sqlCons[1][6]="SELECT * FROM fac_ciudades";
	$sqlOrder[1][6]="ORDER BY NOMB_CIUDAD";
	//GENERO
	$sqlCons[1][7]="SELECT
	fac_genero.ID_GENERO,
	fac_genero.ID_IDIOMA,
	fac_genero.GENERO,
	fac_genero.ABR_GENERO
	FROM fac_genero";
	$sqlOrder[1][7]="ORDER BY GENERO";
	/*API*/
	$sqlCons[2][7]="
	SELECT
	fac_genero.ID_GENERO as id,
	fac_genero.GENERO as name,
	fac_genero.ABR_GENERO as abr
	FROM fac_genero
	JOIN  fac_idioma ON fac_idioma.ID_IDIOMA=fac_genero.ID_IDIOMA AND fac_idioma.ID_IDIOMA=:idioma";
	$sqlOrder[2][7]="ORDER BY GENERO";
	//TZ
	$sqlCons[1][8]="SELECT *,
	CONCAT(TZ_DIFE,' ',TZ_DESC) AS TZ_DIFEARM
	FROM fac_tz";
	$sqlOrder[1][8]="ORDER BY TZ_DIFE";
	//CONFIGURACION GENERAL

	/*API*/
	$sqlCons[2][9]="
	SELECT 
		adm_configuracion.ID_CONFIG,
	    adm_configuracion.CONFIG_NOMBRE,
	    IFNULL(adm_empresas_configuracion.CONFIG_VALOR,adm_configuracion.CONFIG_VALOR) AS CONFIG_VALOR,
	    adm_configuracion.TIPO,
	    adm_configuracion.TABLA,
	    adm_configuracion.ORDEN,
	    adm_configuracion.ORDEN_GRUPO,
	    adm_configuracion.ORDEN_LISTA,
	    adm_configuracion.REQ_CONFIG,
	    adm_configuracion.IDIOMA,
	    adm_configuracion.MEMPRESA,
	    adm_configuracion.APISEND,
		adm_configuracion_name.DESC_CONFIG,
		adm_configuracion_name.GRUPO,
	    adm_empresas_configuracion.ID_MEMPRESA
	FROM adm_configuracion
	INNER JOIN adm_configuracion_name ON adm_configuracion_name.ID_CONFIG=adm_configuracion.ID_CONFIG AND adm_configuracion_name.ID_IDIOMA=:idioma
	LEFT JOIN adm_empresas_configuracion ON adm_empresas_configuracion.ID_CONFIG=adm_configuracion.ID_CONFIG AND adm_empresas_configuracion.ID_MEMPRESA=:empresa";
	$sqlOrder[2][9]="ORDER BY adm_configuracion.ORDEN_GRUPO,adm_configuracion.ORDEN_LISTA";
	$sqlCons[3][9]="
	SELECT adm_configuracion_gral.ID_CONFIG,
		adm_configuracion_gral_name.DESC_CONFIG,
		adm_configuracion_gral_name.GRUPO,
	    adm_configuracion_gral.CONFIG_NOMBRE,
	    adm_configuracion_gral.CONFIG_VALOR,
	    adm_configuracion_gral.TIPO,
	    adm_configuracion_gral.TABLA,
	    adm_configuracion_gral.ORDEN,
	    adm_configuracion_gral.ORDEN_GRUPO,
	    adm_configuracion_gral.ORDEN_LISTA,
	    adm_configuracion_gral.REQ_CONFIG,
	    adm_configuracion_gral.IDIOMA,
	    0 AS ID_MEMPRESA
	FROM adm_configuracion_gral
	LEFT JOIN adm_configuracion_gral_name ON adm_configuracion_gral_name.ID_CONFIG=adm_configuracion_gral.ID_CONFIG AND adm_configuracion_gral_name.ID_IDIOMA=:idioma";
	$sqlOrder[3][9]="ORDER BY adm_configuracion_gral.ORDEN_GRUPO,adm_configuracion_gral.ORDEN_LISTA";
	//MENSAJES
	$sqlCons[1][44]="
	SELECT
	fac_mensajes.ID_MENSAJE,
	fac_mensajes.ID_IDIOMA,
	fac_mensajes.MENSAJE,
	fac_mensajes.DIV_MENSAJE,
	fac_mensajes.DIV_ICONO
	FROM fac_mensajes";
	/*API*/
	$sqlCons[2][44]="
	SELECT
	fac_mensajes.ID_MENSAJE,
	fac_mensajes.ID_IDIOMA,
	fac_mensajes.MENSAJE,
	fac_mensajes.DIV_MENSAJE,
	fac_mensajes.DIV_ICONO
	FROM fac_mensajes
	JOIN  fac_idioma ON fac_idioma.ID_IDIOMA=fac_mensajes.ID_IDIOMA AND fac_idioma.ID_IDIOMA=:idioma";
	$sqlOrder[1][44]="ORDER BY fac_mensajes.ID_MENSAJE";
	//CIUDADES
	$sqlCons[1][45]="
	SELECT fac_ciudades.ID_CIUDAD,
	fac_ciudades.ID_PAIS,
	fac_ciudades.NOMB_CIUDAD,
	fac_ciudades.DISTRITO_CIUDAD,
	fac_ciudades.POBLACION_CIUDAD,
	fac_ciudades.URL_CIUDAD,
	fac_pais.COD_PAIS,
	fac_pais.NOMB_PAIS,
	fac_pais.URL_PAIS
	FROM fac_ciudades
	JOIN fac_pais ON fac_pais.ID_PAIS=fac_ciudades.ID_PAIS";
	$sqlOrder[1][45]="ORDER BY fac_ciudades.NOMB_CIUDAD";
	//CIUDADES
	$sqlCons[2][45]="
	SELECT fac_ciudades.ID_CIUDAD as id,
	fac_ciudades.NOMB_CIUDAD as name,
	fac_ciudades.URL_CIUDAD AS slug
	FROM fac_ciudades
	JOIN fac_pais ON (fac_pais.ID_PAIS=fac_ciudades.ID_PAIS)
	WHERE fac_ciudades.ID_CIUDAD IN (SELECT ID_CIUDAD FROM adm_ciudad) ";
	$sqlOrder[2][45]="ORDER BY fac_ciudades.NOMB_CIUDAD";
	//DOCUMENTOS
	$sqlCons[1][46]="SELECT fac_tdocumento.ID_DOCUMENTO,
	fac_tdocumento.ABR_DOCUMENTO,
	fac_tdocumento.DOCUMENTO,
	CONCAT(fac_tdocumento.DOCUMENTO,' [',fac_tdocumento.ABR_DOCUMENTO,']') AS DOCU_FORMATO,
	fac_tdocumento.HAB_TDOCUMENTO
	FROM fac_tdocumento";
	$sqlOrder[1][46]="ORDER BY fac_tdocumento.ABR_DOCUMENTO";
	/*API*/
	$sqlCons[2][46]="
	SELECT fac_tdocumento.ID_DOCUMENTO as id,
	fac_tdocumento.ABR_DOCUMENTO as abr,
	fac_tdocumento.DOCUMENTO as name
	FROM fac_tdocumento
	JOIN  fac_idioma ON fac_idioma.ID_IDIOMA=fac_tdocumento.ID_IDIOMA AND fac_idioma.ID_IDIOMA=:idioma ";
	$sqlOrder[2][46]="ORDER BY fac_tdocumento.ABR_DOCUMENTO";
	//DIA
	$sqlCons[1][61]="SELECT
	fac_dias.ID_DIA,
	fac_dias.ID_IDIOMA,
	fac_dias.DIA_CONTRAC,
	fac_dias.DIA,
	fac_dias.DIA_ORDEN
	FROM fac_dias";
	$sqlOrder[1][61]="ORDER BY DIA_ORDEN,ID_DIA";
	//GRUPOS

	$sqlCons[1][64]="
	SELECT
	'GRP' AS OPCION, 
	adm_grupos.ID_GRUPO,
	adm_grupos.DESC_GRUPO,
	adm_grupos.COMEN_GRUPO, 
	adm_grupos.HAB_GRUPO,
	IF(adm_grupos.ADM_GRUPO<>0,1,0) AS ADMFLAG,
	(
		SELECT COUNT(*)
		FROM adm_usuarios
		JOIN adm_usuarios_empresa ON adm_usuarios_empresa.ID_USUARIO=adm_usuarios.ID_USUARIO AND adm_usuarios_empresa.ID_MEMPRESA=$_CLIENTE
		WHERE adm_usuarios_empresa.ID_GRUPO=adm_grupos.ID_GRUPO
	) as USUARIOS_G,
	(
		SELECT COUNT(*)
		FROM adm_grupos_ven
		WHERE adm_grupos_ven.ID_GRUPO=adm_grupos.ID_GRUPO AND adm_grupos_ven.PERMISO_GRUPOVEN=1
	) as VENTANAS_G,
	(
		SELECT COUNT(*)
		FROM s_cresp_grupo
		WHERE s_cresp_grupo.ID_GRUPO=adm_grupos.ID_GRUPO
	) as CRESP_G,
	(
		SELECT adm_usuarios.ID_USUARIO
		FROM adm_usuarios
		LEFT JOIN adm_usuarios_empresa ON adm_usuarios_empresa.ID_USUARIO=adm_usuarios.ID_USUARIO AND adm_usuarios_empresa.ID_MEMPRESA=$_CLIENTE
		WHERE adm_usuarios.ID_USUARIO=$_USUARIO AND adm_usuarios_empresa.ID_GRUPO=adm_grupos.ID_GRUPO LIMIT 1
	) AS MI_GRUPO     
	FROM adm_grupos
	JOIN adm_empresas ON adm_empresas.ID_MEMPRESA=adm_grupos.ID_MEMPRESA AND adm_empresas.ID_MEMPRESA=$_CLIENTE ";
	/*API*/
	$sqlCons[2][64]="
	SELECT
	'GRP' AS OPCION, 
	adm_grupos.ID_GRUPO,
	adm_grupos.DESC_GRUPO,
	adm_grupos.COMEN_GRUPO, 
	adm_grupos.HAB_GRUPO,
	IF(adm_grupos.ADM_GRUPO<>0,1,0) AS ADMFLAG,
	(
		SELECT adm_usuarios.ID_USUARIO
		FROM adm_usuarios
		LEFT JOIN adm_usuarios_empresa ON adm_usuarios_empresa.ID_USUARIO=adm_usuarios.ID_USUARIO AND adm_usuarios_empresa.ID_MEMPRESA=:empresa
		WHERE adm_usuarios.ID_USUARIO=$_USUARIO AND adm_usuarios_empresa.ID_GRUPO=adm_grupos.ID_GRUPO LIMIT 1
	) AS MI_GRUPO     
	FROM adm_grupos
	JOIN adm_empresas ON adm_empresas.ID_MEMPRESA=adm_grupos.ID_MEMPRESA AND adm_empresas.ID_MEMPRESA=:empresa ";
	$sqlOrder[1][64]="ORDER BY adm_grupos.ADM_GRUPO DESC,adm_grupos.DESC_GRUPO";


	//VENTNAS	
	$sqlCons[1][66]="
	SELECT 
	'VEN' AS OPCION,
	adm_ventanas.ID_VENTANA,
	IFNULL(adm_ventanas_names.VENTANA_NOMBRE,adm_empresas_v_names.VENTANA_NOMBRE) AS VENTANA_NOMBRE,
	adm_ventanas.ID_GVENTANA,
	adm_ventanas.VENTANA_AGRUPA,
	IFNULL(adm_ventanas_names.SCVENTANA,adm_empresas_v_names.SCVENTANA) AS SCVENTANA,
	adm_ventanas.ORDEN,
	adm_ventanas.ACCIONES,
	IFNULL(adm_ventanas_grupo_name.DESC_GVENTANA,adm_empresas_v_grupo_name.DESC_GVENTANA) AS DESC_GVENTANA,
	adm_grupos_ven.ID_GRUPO,
	IFNULL(adm_grupos_ven.PERMISO_GRUPOVEN,0) AS PERMISO_GRUPOVEN
	FROM adm_ventanas
	LEFT JOIN adm_ventanas_names ON adm_ventanas_names.ID_VENTANA=adm_ventanas.ID_VENTANA AND adm_ventanas_names.ID_IDIOMA=$_IDIOMA AND adm_ventanas_names.ID_MEMPRESA=$_CLIENTE
	LEFT JOIN adm_empresas_v_names ON adm_empresas_v_names.ID_VENTANA=adm_ventanas.ID_VENTANA AND adm_empresas_v_names.ID_IDIOMA=$_IDIOMA AND adm_empresas_v_names.TIPO_GRUPOPAL=$_GCLIENTE

	LEFT JOIN adm_ventanas_grupo ON adm_ventanas_grupo.ID_GVENTANA=adm_ventanas.ID_GVENTANA
	LEFT JOIN adm_ventanas_grupo_name ON adm_ventanas_grupo_name.ID_GVENTANA=adm_ventanas_grupo.ID_GVENTANA AND adm_ventanas_grupo_name.ID_IDIOMA=$_IDIOMA AND adm_ventanas_grupo_name.ID_MEMPRESA=$_CLIENTE
	LEFT JOIN adm_empresas_v_grupo_name ON adm_empresas_v_grupo_name.ID_GVENTANA=adm_ventanas_grupo.ID_GVENTANA AND adm_empresas_v_grupo_name.ID_IDIOMA=$_IDIOMA AND adm_empresas_v_grupo_name.TIPO_GRUPOPAL=$_GCLIENTE

	LEFT JOIN adm_grupos_ven ON adm_grupos_ven.ID_VENTANA=adm_ventanas.ID_VENTANA";

	$sqlOrder[1][66]="ORDER BY adm_ventanas.ID_GVENTANA,IFNULL(adm_ventanas_names.VENTANA_NOMBRE,adm_empresas_v_names.VENTANA_NOMBRE) ";

	//GRUPOS ENLACES
	$sqlCons[1][67]="
	SELECT
	'GRP' AS OPCION, 
	adm_grupos.ID_GRUPO,
	adm_grupos.DESC_GRUPO,
	adm_grupos.COMEN_GRUPO, 
	adm_grupos.HAB_GRUPO,
	s_cresp_grupo.ID_RESP     
	FROM adm_grupos
	LEFT JOIN s_cresp_grupo ON s_cresp_grupo.ID_GRUPO=adm_grupos.ID_GRUPO";
	$sqlOrder[1][67]="ORDER BY adm_grupos.DESC_GRUPO";
	//TEXTOS PRIV
	$sqlCons[1][68]="
	SELECT
	adm_textos.ID_IDIOMA,
	adm_textos.ID_PALABRA,
	adm_textos.PALABRA, 
	adm_textos.TOOLTIP   
	FROM adm_textos";
	$sqlOrder[1][68]="ORDER BY adm_textos.ID_PALABRA";

	//INDEX VENTANA

	$sqlCons[1][69]="
	SELECT 
	adm_ventanas_menu.ID_VENTANA,
	adm_ventanas_menu.DIR_VENTANA,
	adm_ventanas_menu.ACR_VENTANA,
	adm_ventanas_menu.ORDEN_VENTANA,
	adm_ventanas_menu.UBICACION_MENU,
	IFNULL(adm_ventanas_names.VENTANA_NOMBRE,adm_empresas_v_names.VENTANA_NOMBRE) AS VENTANA_NOMBRE,
	IFNULL(adm_ventanas_names.SCVENTANA,adm_empresas_v_names.SCVENTANA) AS SCVENTANA,
	adm_ventanas_menu.TIPO_INFO,
	adm_ventanas_grupo.ID_GVENTANA,		
	IFNULL(adm_ventanas_grupo_name.DESC_GVENTANA,adm_empresas_v_grupo_name.DESC_GVENTANA) AS DESC_GVENTANA
	FROM adm_ventanas_menu

	JOIN adm_ventanas ON adm_ventanas.ID_VENTANA=adm_ventanas_menu.ID_VENTANA    

	JOIN adm_ventanas_etipo ON adm_ventanas_etipo.ID_VENTANA=adm_ventanas.ID_VENTANA AND adm_ventanas_etipo.TIPO_GRUPOPAL=$_GCLIENTE AND adm_ventanas_etipo.PERMISO=1
	JOIN adm_grupos_ven ON adm_grupos_ven.ID_VENTANA=adm_ventanas.ID_VENTANA AND adm_grupos_ven.ID_GRUPO=$_GRUPO AND adm_grupos_ven.PERMISO_GRUPOVEN=1



	LEFT JOIN adm_ventanas_names ON adm_ventanas_names.ID_VENTANA=adm_ventanas.ID_VENTANA AND adm_ventanas_names.ID_IDIOMA=$_IDIOMA AND adm_ventanas_names.ID_MEMPRESA=$_CLIENTE 
	LEFT JOIN adm_empresas_v_names ON adm_empresas_v_names.ID_VENTANA=adm_ventanas.ID_VENTANA AND adm_empresas_v_names.ID_IDIOMA=$_IDIOMA AND adm_empresas_v_names.TIPO_GRUPOPAL=$_GCLIENTE

	LEFT JOIN adm_ventanas_grupo ON adm_ventanas_grupo.ID_GVENTANA=adm_ventanas.ID_GVENTANA
	LEFT JOIN adm_ventanas_grupo_name ON adm_ventanas_grupo_name.ID_GVENTANA=adm_ventanas_grupo.ID_GVENTANA AND adm_ventanas_grupo_name.ID_IDIOMA=$_IDIOMA AND adm_ventanas_grupo_name.ID_MEMPRESA=$_CLIENTE
	LEFT JOIN adm_empresas_v_grupo_name ON adm_empresas_v_grupo_name.ID_GVENTANA=adm_ventanas_grupo.ID_GVENTANA AND adm_empresas_v_grupo_name.ID_IDIOMA=$_IDIOMA AND adm_empresas_v_grupo_name.TIPO_GRUPOPAL=$_GCLIENTE ";

	//LOAD

	$sqlCons[1][70]="
	SELECT 
	adm_ventanas.ID_VENTANA,
	IFNULL(adm_ventanas_names.VENTANA_NOMBRE,adm_empresas_v_names.VENTANA_NOMBRE) AS VENTANA_NOMBRE,
	adm_ventanas.ORDEN,
	adm_ventanas_grupo.ID_GVENTANA,		
	IFNULL(adm_ventanas_grupo_name.DESC_GVENTANA,adm_empresas_v_grupo_name.DESC_GVENTANA) AS DESC_GVENTANA
	FROM adm_ventanas
	LEFT JOIN adm_ventanas_names ON adm_ventanas_names.ID_VENTANA=adm_ventanas.ID_VENTANA AND adm_ventanas_names.ID_IDIOMA=$_IDIOMA AND adm_ventanas_names.ID_MEMPRESA=$_CLIENTE
	LEFT JOIN adm_empresas_v_names ON adm_empresas_v_names.ID_VENTANA=adm_ventanas.ID_VENTANA AND adm_empresas_v_names.ID_IDIOMA=$_IDIOMA AND adm_empresas_v_names.TIPO_GRUPOPAL=$_GCLIENTE

	LEFT JOIN adm_ventanas_grupo ON adm_ventanas_grupo.ID_GVENTANA=adm_ventanas.ID_GVENTANA
	LEFT JOIN adm_ventanas_grupo_name ON adm_ventanas_grupo_name.ID_GVENTANA=adm_ventanas_grupo.ID_GVENTANA AND adm_ventanas_grupo_name.ID_IDIOMA=$_IDIOMA AND adm_ventanas_grupo_name.ID_MEMPRESA=$_CLIENTE
	LEFT JOIN adm_empresas_v_grupo_name ON adm_empresas_v_grupo_name.ID_GVENTANA=adm_ventanas_grupo.ID_GVENTANA AND adm_empresas_v_grupo_name.ID_IDIOMA=$_IDIOMA AND adm_empresas_v_grupo_name.TIPO_GRUPOPAL=$_GCLIENTE

	WHERE adm_ventanas.ACCIONES=0
	AND adm_ventanas.ID_VENTANA IN (
			SELECT adm_grupos_ven.ID_VENTANA
			FROM adm_usuarios
			LEFT JOIN adm_usuarios_empresa ON adm_usuarios_empresa.ID_USUARIO=adm_usuarios.ID_USUARIO AND adm_usuarios_empresa.ID_MEMPRESA=$_CLIENTE
			LEFT JOIN adm_grupos_ven ON (adm_grupos_ven.ID_GRUPO=adm_usuarios_empresa.ID_GRUPO)
			WHERE adm_usuarios.ID_USUARIO='$_USUARIO'
			)
	ORDER BY adm_ventanas_grupo.ORD_GVENTANA,adm_ventanas.ORDEN ";

	$sqlCons[2][70]="
	SELECT 
	adm_ventanas.ID_VENTANA,
	IFNULL(adm_ventanas_names.VENTANA_NOMBRE,adm_empresas_v_names.VENTANA_NOMBRE) AS VENTANA_NOMBRE,
	adm_ventanas.ORDEN,
	adm_ventanas_grupo.ID_GVENTANA,		
	IFNULL(adm_ventanas_grupo_name.DESC_GVENTANA,adm_empresas_v_grupo_name.DESC_GVENTANA) AS DESC_GVENTANA
	FROM adm_ventanas
	LEFT JOIN adm_ventanas_names ON adm_ventanas_names.ID_VENTANA=adm_ventanas.ID_VENTANA AND adm_ventanas_names.ID_IDIOMA=:idioma AND adm_ventanas_names.ID_MEMPRESA=:empresa
	LEFT JOIN adm_empresas_v_names ON adm_empresas_v_names.ID_VENTANA=adm_ventanas.ID_VENTANA AND adm_empresas_v_names.ID_IDIOMA=:idioma AND adm_empresas_v_names.TIPO_GRUPOPAL=:grupo

	LEFT JOIN adm_ventanas_grupo ON adm_ventanas_grupo.ID_GVENTANA=adm_ventanas.ID_GVENTANA
	LEFT JOIN adm_ventanas_grupo_name ON adm_ventanas_grupo_name.ID_GVENTANA=adm_ventanas_grupo.ID_GVENTANA AND adm_ventanas_grupo_name.ID_IDIOMA=:idioma AND adm_ventanas_grupo_name.ID_MEMPRESA=:empresa
	LEFT JOIN adm_empresas_v_grupo_name ON adm_empresas_v_grupo_name.ID_GVENTANA=adm_ventanas_grupo.ID_GVENTANA AND adm_empresas_v_grupo_name.ID_IDIOMA=:idioma AND adm_empresas_v_grupo_name.TIPO_GRUPOPAL=:grupo

	WHERE adm_ventanas.ACCIONES=0
	AND adm_ventanas.ID_VENTANA IN (
			SELECT adm_grupos_ven.ID_VENTANA
			FROM adm_usuarios
			LEFT JOIN adm_usuarios_empresa ON adm_usuarios_empresa.ID_USUARIO=adm_usuarios.ID_USUARIO AND adm_usuarios_empresa.ID_MEMPRESA=:empresa
			LEFT JOIN adm_grupos_ven ON (adm_grupos_ven.ID_GRUPO=adm_usuarios_empresa.ID_GRUPO)
			WHERE adm_usuarios.ID_USUARIO='$_USUARIO'
			)
	ORDER BY adm_ventanas_grupo.ORD_GVENTANA,adm_ventanas.ORDEN ";


	//**//

	$sqlCons[1][71]="
	SELECT 
	IFNULL(adm_ventanas_names.VENTANA_NOMBRE,adm_empresas_v_names.VENTANA_NOMBRE) AS VENTANA_NOMBRE,
	adm_ventanas.ID_VENTANA
	FROM adm_ventanas
	LEFT JOIN adm_ventanas_names ON adm_ventanas_names.ID_VENTANA=adm_ventanas.ID_VENTANA AND adm_ventanas_names.ID_IDIOMA=$_IDIOMA AND adm_ventanas_names.ID_MEMPRESA=$_CLIENTE
	LEFT JOIN adm_empresas_v_names ON adm_empresas_v_names.ID_VENTANA=adm_ventanas.ID_VENTANA AND adm_empresas_v_names.ID_IDIOMA=$_IDIOMA AND adm_empresas_v_names.TIPO_GRUPOPAL=$_GCLIENTE ";


	$sqlCons[1][72]="
	SELECT
	'EMPTIPO' AS OPCION,
	adm_empresas_tipo.ID_TIPOE,
	adm_empresas_tipo.LINK_TIPOE,

	adm_empresas_tipo_desc.ID_IDIOMA,
	adm_empresas_tipo_desc.NOMB_TIPOE,
	adm_empresas_tipo_desc.DESC_TIPOE,

	adm_empresas_tipo.TIPO_GRUPOPAL,
	adm_empresas_tipo.HAB_TIPOE,

	adm_empresas_btipo.LINK_GRUPOPAL,
	adm_empresas_btipo_desc.NOMB_GRUPOPAL,

	CONVERT_TZ(adm_empresas_tipo.FECHA_TIPOE,'+00:00','$_TZ') AS FECHA_F,
	DATE_FORMAT(CONVERT_TZ(adm_empresas_tipo.FECHA_TIPOE,'+00:00','$_TZ'),'%d')  AS DIA_M,   
	DATE_FORMAT(CONVERT_TZ(adm_empresas_tipo.FECHA_TIPOE,'+00:00','$_TZ'),'%m')  AS MES_M,   
	DATE_FORMAT(CONVERT_TZ(adm_empresas_tipo.FECHA_TIPOE,'+00:00','$_TZ'),'%Y')  AS ANN_M, 

	adm_usuarios.ID_USUARIO,
	adm_usuarios.NOMBRE_U AS NOMBRE_U_OP,
	adm_usuarios.APELLIDO_U AS APELLIDO_U_OP

	FROM adm_empresas_tipo
	LEFT JOIN adm_empresas_tipo_desc ON adm_empresas_tipo_desc.ID_TIPOE=adm_empresas_tipo.ID_TIPOE AND adm_empresas_tipo_desc.ID_IDIOMA=$_IDIOMA
	LEFT JOIN adm_empresas_btipo ON adm_empresas_btipo.TIPO_GRUPOPAL=adm_empresas_tipo.TIPO_GRUPOPAL
	LEFT JOIN adm_empresas_btipo_desc ON adm_empresas_btipo_desc.TIPO_GRUPOPAL=adm_empresas_btipo.TIPO_GRUPOPAL AND adm_empresas_btipo_desc.ID_IDIOMA=$_IDIOMA
	LEFT JOIN adm_usuarios ON adm_usuarios.ID_USUARIO=adm_empresas_tipo.ID_USUARIO ";
	$sqlOrder[1][72]="ORDER BY adm_empresas_tipo_desc.NOMB_TIPOE";

	//INFORMES
	$sqlCons[1][73]="
	SELECT
	'INFALL' AS OPCION,
	adm_informes_detalle.ID_INFORME,
	adm_informes_detalle.ID_VENTANA,
	adm_informes_detalle.ORDER_INFORME,
	adm_informes_detalle.FILERES_INFORME,
	adm_informes_detalle.FILEREV_INFORME,
	adm_informes_detalle.TIPO_INFORME,
	adm_informes_detalle.HAB_INFORME,

	IFNULL(adm_informes_desc.NOMB_INFORME,adm_empresas_v_informes_desc.NOMB_INFORME) AS NOMB_INFORME,
	IFNULL(adm_informes_desc.DESC_INFORME,adm_empresas_v_informes_desc.DESC_INFORME) AS DESC_INFORME,
	IFNULL(adm_informes_desc.REF_INFORME,adm_empresas_v_informes_desc.REF_INFORME) AS REF_INFORME,
	DATE_FORMAT(IFNULL(adm_informes_desc.FECHA_INFORME,adm_empresas_v_informes_desc.FECHA_INFORME),'%d/%m/%Y') AS FECHA_INFORMEF,
	IFNULL(adm_informes_desc.REV_INFORME,adm_empresas_v_informes_desc.REV_INFORME) AS REV_INFORME,

	adm_informes_grupo.ID_GINFORME,
	adm_informes_grupo.ORDER_GINFORME,
	IFNULL(adm_informes_grupo_desc.NOM_GINFORME,adm_empresas_v_informes_grupo_desc.NOM_GINFORME) AS NOM_GINFORME,
	IFNULL(adm_informes_grupo_desc.DESC_GINFORME,adm_empresas_v_informes_grupo_desc.DESC_GINFORME) AS DESC_GINFORME


	FROM adm_informes_detalle
	LEFT JOIN adm_informes_desc ON adm_informes_desc.ID_INFORME=adm_informes_detalle.ID_INFORME AND
												adm_informes_desc.ID_MEMPRESA=$_CLIENTE AND adm_informes_desc.ID_IDIOMA=$_IDIOMA
	LEFT JOIN adm_empresas_v_informes_desc ON adm_empresas_v_informes_desc.ID_INFORME=adm_informes_detalle.ID_INFORME AND
												adm_empresas_v_informes_desc.TIPO_GRUPOPAL=$_GCLIENTE AND adm_empresas_v_informes_desc.ID_IDIOMA=$_IDIOMA


	LEFT JOIN adm_informes_grupo ON adm_informes_grupo.ID_GINFORME=adm_informes_detalle.ID_GINFORME
	LEFT JOIN adm_informes_grupo_desc ON adm_informes_grupo_desc.ID_GINFORME=adm_informes_grupo.ID_GINFORME AND
												adm_informes_grupo_desc.ID_MEMPRESA=$_CLIENTE AND adm_informes_grupo_desc.ID_IDIOMA=$_IDIOMA
	LEFT JOIN adm_empresas_v_informes_grupo_desc ON adm_empresas_v_informes_grupo_desc.ID_GINFORME=adm_informes_grupo.ID_GINFORME AND
												adm_empresas_v_informes_grupo_desc.TIPO_GRUPOPAL=$_GCLIENTE AND adm_empresas_v_informes_grupo_desc.ID_IDIOMA=$_IDIOMA

	WHERE adm_informes_detalle.ID_VENTANA IN (
					SELECT adm_grupos_ven.ID_VENTANA
					FROM adm_usuarios
					LEFT JOIN adm_usuarios_empresa ON adm_usuarios_empresa.ID_USUARIO=adm_usuarios.ID_USUARIO AND adm_usuarios_empresa.ID_MEMPRESA=$_CLIENTE
					LEFT JOIN adm_grupos_ven ON adm_grupos_ven.ID_GRUPO=adm_usuarios_empresa.ID_GRUPO
					WHERE adm_usuarios.ID_USUARIO='$_USUARIO'
	        )";

	$sqlOrder[1][73]="ORDER BY adm_informes_grupo.ORDER_GINFORME,adm_informes_detalle.ORDER_INFORME";
	/*API*/
	$sqlCons[1][74]="SELECT
	adm_api.ID_API,
	adm_api.KEY_API,
	adm_api.DATE_API,
	adm_api.DOMAIN_API,
	adm_api.HAB_API,
	adm_api_empresa.ID_MEMPRESA
	FROM adm_api
	LEFT JOIN adm_api_empresa ON adm_api_empresa.ID_API=adm_api.ID_API";
	//LANDING INFO
	$sqlCons[1][75]="
	SELECT
		adm_landing.ID_LAND,
		adm_landing.ID_IDIOMA,
		adm_landing.FECHA_INI,
		adm_landing.FECHA_FIN,
		adm_landing.TITULO,
		adm_landing.ETI_DIV,
		adm_landing.TEXTO
	FROM adm_landing";
	$sqlOrder[1][75]="ORDER BY adm_landing.ID_LAND";

	$sqlCons[2][75]="
	SELECT adm_empresas_landing.ID_LAND,
	    adm_empresas_landing.ID_IDIOMA,
	    adm_empresas_landing.ID_MEMPRESA,
	    adm_empresas_landing.FECHA_INI,
	    adm_empresas_landing.FECHA_FIN,
	    adm_empresas_landing.TITULO,
	    adm_empresas_landing.ETI_DIV,
	    adm_empresas_landing.TEXTO
	FROM adm_empresas_landing";
	$sqlOrder[2][75]="ORDER BY adm_empresas_landing.ID_LAND";
	//IDIOMAS
	$sqlCons[1][76]="SELECT
	fac_idioma.ID_IDIOMA,
	fac_idioma.IDIOMA,
	fac_idioma.NAV01,
	fac_idioma.NAV02,
	fac_idioma.DEFAULT,
	fac_idioma.HAB_IDIOMA
	FROM fac_idioma";
	$sqlOrder[1][76]="ORDER BY fac_idioma.DEFAULT DESC,fac_idioma.IDIOMA";

	//TEXTOS GLOB
	$sqlCons[1][77]="
	SELECT
	adm_empresas_imp_textos.ID_PALABRA,
	adm_empresas_imp_textos.ID_IDIOMA,
	adm_empresas_imp_textos.PALABRA,
	adm_empresas_imp_textos.TOOLTIP,
	adm_empresas_imp_textos.TIPO_GRUPOPAL
	FROM adm_empresas_imp_textos";
	$sqlOrder[1][77]="ORDER BY adm_empresas_imp_textos.ID_PALABRA";
	/****/
	$sqlCons[2][77]="
	SELECT
	adm_empresas_imp_textos.ID_PALABRA,
	adm_empresas_imp_textos.ID_IDIOMA,
	adm_empresas_imp_textos.PALABRA,
	adm_empresas_imp_textos.TOOLTIP
	FROM adm_empresas_imp_textos
	WHERE adm_empresas_imp_textos.ID_IDIOMA=:idioma AND adm_empresas_imp_textos.TIPO_GRUPOPAL=:grupo
		AND adm_empresas_imp_textos.ID_PALABRA NOT IN(SELECT adm_textos.ID_PALABRA FROM adm_textos WHERE adm_textos.ID_IDIOMA=:idioma AND adm_textos.ID_MEMPRESA=:empresa)";
	$sqlCons[3][77]="
	SELECT
	adm_textos.ID_PALABRA,
	adm_textos.ID_IDIOMA,
	adm_textos.PALABRA,
	adm_textos.TOOLTIP
	FROM adm_textos
	WHERE adm_textos.ID_IDIOMA=:idioma AND adm_textos.ID_MEMPRESA=:empresa";
	$sqlCons[4][77]="
	SELECT
	adm_textos.ID_PALABRA,
	adm_textos.ID_IDIOMA,
	adm_textos.PALABRA, 
	adm_textos.TOOLTIP   
	FROM adm_textos
	WHERE adm_textos.ID_IDIOMA=:idioma";
	/***/
	$sqlOrder[1][68]="ORDER BY adm_textos.ID_PALABRA";
	//CONFIG GENERAL
	$sqlCons[1][78]="SELECT *
	FROM adm_configuracion_gral";
	$sqlOrder[1][78]="ORDER BY ID_CONFIG";
	//MONEDA
	$sqlCons[1][79]="
	SELECT
	fac_moneda.ID_MONEDA,
	fac_moneda.MONEDA,
	fac_moneda.COD01_MONEDA,
	fac_moneda.COD02_MONEDA,
	fac_moneda.COD03_MONEDA,
	fac_moneda.REF_MONEDA,
	fac_moneda.HAB_MONEDA,
	fac_moneda_valor.FACCONV_MONEDA,
	fac_moneda_valor.FECHAACT_MON

	FROM fac_moneda
	JOIN fac_idioma ON fac_idioma.ID_IDIOMA=fac_moneda.ID_IDIOMA AND fac_idioma.ID_IDIOMA=:idioma
	LEFT JOIN fac_moneda_valor ON fac_moneda_valor.ID_MONEDA=fac_moneda.ID_MONEDA AND fac_moneda_valor.ID_MONHIST=(
	                                                                    SELECT fac_moneda_valor_pq.ID_MONHIST
	                                                                    FROM fac_moneda_valor fac_moneda_valor_pq
	                                                                    WHERE fac_moneda_valor_pq.ID_MONEDA=fac_moneda.ID_MONEDA AND fac_moneda_valor_pq.FECHAACT_MON<=UTC_TIMESTAMP()
	                                                                    ORDER BY fac_moneda_valor_pq.FECHAACT_MON DESC LIMIT 1)";
	$sqlOrder[1][79]="ORDER BY fac_moneda.REF_MONEDA DESC,fac_moneda.COD01_MONEDA";

	$sqlCons[1][80]="
	SELECT
	adm_api_consultas.ID_CONSULTA,
	adm_api_consultas.NOMB_CONSULTA,
	adm_api_consultas.DESC_CONSULTA,
	adm_api_consultas.VERIF_CONSULTA,
	adm_api_consultas.EMPRESA_CONSULTA,
	adm_api_consultas.GRUPO_CONSULTA,
	adm_api_consultas.TIPO,
	adm_api_consultas.HAB_CONSULTA
	FROM adm_api_consultas";
	$sqlOrder[1][80]="";

	//EMPRESAS
	$sqlCons[1][81]="
	SELECT
	'CLIEMPRESA' AS OPCION,
	adm_empresas.ID_MEMPRESA,
	adm_empresas.NOMB_MEMPRESA,
	adm_empresas_desc.LEMA_EMPRESA,
	adm_empresas_desc.DESC_EMPRESA,
	adm_empresas.START,
	adm_empresas.URL,
	adm_empresas.HAB_MEMPRESA,

	adm_empresas_tipo.ID_TIPOE,
	adm_empresas_tipo.TIPO_GRUPOPAL,

	adm_empresas_tipo_desc.ID_IDIOMA,
	adm_empresas_tipo_desc.NOMB_TIPOE,
	adm_empresas_tipo_desc.DESC_TIPOE,

	adm_empresas_btipo_desc.NOMB_GRUPOPAL,

	DATE_FORMAT(CONVERT_TZ(adm_empresas.START,'+00:00','$_TZ'),'%d')  AS DIA_M,   
	DATE_FORMAT(CONVERT_TZ(adm_empresas.START,'+00:00','$_TZ'),'%m')  AS MES_M,   
	DATE_FORMAT(CONVERT_TZ(adm_empresas.START,'+00:00','$_TZ'),'%Y')  AS ANN_M, 

	adm_usuarios.ID_USUARIO,
	adm_usuarios.NOMBRE_U AS NOMBRE_U_OP,
	adm_usuarios.APELLIDO_U AS APELLIDO_U_OP,

	/*IMAGEN*/
	IFNULL(adm_files.ID_FILE,0) AS M_IMG,
	adm_files.F_EXT

	FROM adm_empresas
	LEFT JOIN adm_usuarios ON adm_usuarios.ID_USUARIO=adm_empresas.ID_USUARIO
	LEFT JOIN adm_empresas_tipo ON adm_empresas_tipo.ID_TIPOE=adm_empresas.ID_TIPOE
	LEFT JOIN adm_empresas_tipo_desc ON adm_empresas_tipo_desc.ID_TIPOE=adm_empresas_tipo.ID_TIPOE AND adm_empresas_tipo_desc.ID_IDIOMA=:idioma
	LEFT JOIN adm_empresas_desc ON adm_empresas_desc.ID_MEMPRESA=adm_empresas.ID_MEMPRESA AND adm_empresas_desc.ID_IDIOMA=:idioma

	LEFT JOIN adm_empresas_btipo ON adm_empresas_btipo.TIPO_GRUPOPAL=adm_empresas_tipo.TIPO_GRUPOPAL
	LEFT JOIN adm_empresas_btipo_desc ON adm_empresas_btipo_desc.TIPO_GRUPOPAL=adm_empresas_btipo.TIPO_GRUPOPAL AND adm_empresas_btipo_desc.ID_IDIOMA=:idioma
	LEFT JOIN adm_files ON adm_files.ID_FILE=adm_empresas.ID_FILE";
	$sqlOrder[1][81]="ORDER BY adm_empresas.NOMB_MEMPRESA";

	$sqlCons[2][81]="
	SELECT
	'CLIEMPRESA' AS OPCION,
	adm_empresas.ID_MEMPRESA,
	adm_empresas.NOMB_MEMPRESA,
	adm_empresas_desc.LEMA_EMPRESA,
	adm_empresas_desc.DESC_EMPRESA,
	adm_empresas.START,
	adm_empresas.URL,
	adm_empresas.HAB_MEMPRESA,

	adm_empresas_tipo.ID_TIPOE,
	adm_empresas_tipo.TIPO_GRUPOPAL,

	adm_empresas_tipo_desc.ID_IDIOMA,
	adm_empresas_tipo_desc.NOMB_TIPOE,
	adm_empresas_tipo_desc.DESC_TIPOE,

	adm_empresas_btipo_desc.NOMB_GRUPOPAL

	FROM adm_empresas
	INNER JOIN adm_usuarios_empresa ON adm_usuarios_empresa.ID_USUARIO=:usuario AND adm_usuarios_empresa.ID_MEMPRESA=adm_empresas.ID_MEMPRESA
	LEFT JOIN adm_usuarios ON adm_usuarios.ID_USUARIO=adm_empresas.ID_USUARIO
	LEFT JOIN adm_empresas_tipo ON adm_empresas_tipo.ID_TIPOE=adm_empresas.ID_TIPOE
	LEFT JOIN adm_empresas_tipo_desc ON adm_empresas_tipo_desc.ID_TIPOE=adm_empresas_tipo.ID_TIPOE AND adm_empresas_tipo_desc.ID_IDIOMA=:idioma
	LEFT JOIN adm_empresas_desc ON adm_empresas_desc.ID_MEMPRESA=adm_empresas.ID_MEMPRESA AND adm_empresas_desc.ID_IDIOMA=:idioma

	LEFT JOIN adm_empresas_btipo ON adm_empresas_btipo.TIPO_GRUPOPAL=adm_empresas_tipo.TIPO_GRUPOPAL
	LEFT JOIN adm_empresas_btipo_desc ON adm_empresas_btipo_desc.TIPO_GRUPOPAL=adm_empresas_btipo.TIPO_GRUPOPAL AND adm_empresas_btipo_desc.ID_IDIOMA=:idioma";

	$sqlCons[3][81]="
	SELECT
	adm_empresas.ID_MEMPRESA,
	adm_empresas.NOMB_MEMPRESA,
	IFNULL(adm_files.ID_FILE,0) AS M_IMG,
	adm_files.F_EXT,
	adm_files.F_HASH
	FROM adm_empresas	
	LEFT JOIN adm_files ON adm_files.ID_FILE=adm_empresas.ID_FILE";



	$sqlCons[1][82]="SELECT *
	FROM adm_empresas_imagenes
	LEFT JOIN adm_empresas_imagenes_names ON adm_empresas_imagenes_names.ID_IMAGEN=adm_empresas_imagenes.ID_IMAGEN AND adm_empresas_imagenes_names.ID_IDIOMA=$_IDIOMA";
	$sqlOrder[1][82]="ORDER BY adm_empresas_imagenes_names.ORD,adm_empresas_imagenes_names.ID_IMAGEN";
	//DATOS DE EMPRESAS
	$sqlCons[1][83]="
	SELECT
	adm_empresas_desc.ID_MEMPRESA,
	adm_empresas_desc.ID_IDIOMA,
	adm_empresas_desc.LEMA_EMPRESA,
	adm_empresas_desc.DESC_EMPRESA
	FROM adm_empresas_desc";

	//CLASE DE EMPRESAS
	$sqlCons[1][85]="
	SELECT
	'GRUPOSEMP' AS OPCION,
	adm_empresas_btipo.TIPO_GRUPOPAL,
	CONVERT_TZ(adm_empresas_btipo.FECHA_GRUPOPAL,'+00:00','$_TZ') AS FECHA_F,
	DATE_FORMAT(CONVERT_TZ(adm_empresas_btipo.FECHA_GRUPOPAL,'+00:00','$_TZ'),'%d')  AS DIA_M,   
	DATE_FORMAT(CONVERT_TZ(adm_empresas_btipo.FECHA_GRUPOPAL,'+00:00','$_TZ'),'%m')  AS MES_M,   
	DATE_FORMAT(CONVERT_TZ(adm_empresas_btipo.FECHA_GRUPOPAL,'+00:00','$_TZ'),'%Y')  AS ANN_M, 
	adm_empresas_btipo.LINK_GRUPOPAL,
	adm_empresas_btipo.HAB_GRUPOPAL,

	adm_empresas_btipo_desc.ID_IDIOMA,
	adm_empresas_btipo_desc.NOMB_GRUPOPAL,
	adm_empresas_btipo_desc.DESC_GRUPOPAL,

	adm_usuarios.ID_USUARIO,
	adm_usuarios.NOMBRE_U AS NOMBRE_U_OP,
	adm_usuarios.APELLIDO_U AS APELLIDO_U_OP

	FROM adm_empresas_btipo
	LEFT JOIN adm_empresas_btipo_desc ON adm_empresas_btipo_desc.TIPO_GRUPOPAL=adm_empresas_btipo.TIPO_GRUPOPAL AND adm_empresas_btipo_desc.ID_IDIOMA=$_IDIOMA
	LEFT JOIN adm_usuarios ON adm_usuarios.ID_USUARIO=adm_empresas_btipo.ID_USUARIO";
	$sqlOrder[1][85]="ORDER BY adm_empresas_btipo_desc.NOMB_GRUPOPAL";
	//CLASE DE EMPRESAS - NOMBRE
	$sqlCons[1][86]="
	SELECT
	adm_empresas_btipo_desc.TIPO_GRUPOPAL,
	adm_empresas_btipo_desc.ID_IDIOMA,
	adm_empresas_btipo_desc.NOMB_GRUPOPAL,
	adm_empresas_btipo_desc.DESC_GRUPOPAL
	FROM adm_empresas_btipo_desc";
	$sqlOrder[1][86]="ORDER BY adm_empresas_btipo.ID_IDIOMA";
	//TIPO DE EMPRESAS - NOMBRE
	$sqlCons[1][87]="
	SELECT
	adm_empresas_tipo_desc.ID_TIPOE,
	adm_empresas_tipo_desc.ID_IDIOMA,
	adm_empresas_tipo_desc.NOMB_TIPOE,
	adm_empresas_tipo_desc.DESC_TIPOE
	FROM adm_empresas_tipo_desc";
	$sqlOrder[1][87]="ORDER BY adm_empresas_tipo_desc.ID_IDIOMA";
	
	//PRECARGA DE VENTANA
	$sqlCons[1][91]="
	SELECT
	adm_empresas_v_names.ID_VENTANA,
	adm_empresas_v_names.ID_IDIOMA,
	adm_empresas_v_names.TIPO_GRUPOPAL,
	adm_empresas_v_names.VENTANA_NOMBRE,
	adm_empresas_v_names.SCVENTANA
	FROM adm_empresas_v_names";
	$sqlOrder[1][91]="ORDER BY adm_empresas_v_names.ID_VENTANA";
	$sqlCons[2][91]="
	SELECT
	adm_empresas_v_names.ID_VENTANA,
	adm_empresas_v_names.ID_IDIOMA,
	adm_empresas_v_names.TIPO_GRUPOPAL,
	IFNULL(adm_ventanas_names.VENTANA_NOMBRE,adm_empresas_v_names.VENTANA_NOMBRE) AS VENTANA_NOMBRE,
	IFNULL(adm_ventanas_names.SCVENTANA,adm_empresas_v_names.SCVENTANA) AS SCVENTANA
	FROM adm_empresas_v_names
	LEFT JOIN adm_ventanas_names ON adm_ventanas_names.ID_VENTANA=adm_empresas_v_names.ID_VENTANA 
						AND adm_ventanas_names.ID_IDIOMA=adm_empresas_v_names.ID_IDIOMA
						AND adm_ventanas_names.ID_MEMPRESA=$_CLIENTE";

	//PRECARGA DE GRUPO DE VENTANA
	$sqlCons[1][92]="
	SELECT
	adm_empresas_v_grupo_name.ID_GVENTANA,
	adm_empresas_v_grupo_name.ID_IDIOMA,
	adm_empresas_v_grupo_name.TIPO_GRUPOPAL,
	adm_empresas_v_grupo_name.DESC_GVENTANA
	FROM adm_empresas_v_grupo_name";
	$sqlOrder[1][92]="ORDER BY adm_empresas_v_grupo_name.ID_GVENTANA";
	$sqlCons[2][92]="
	SELECT
	adm_empresas_v_grupo_name.ID_GVENTANA,
	adm_empresas_v_grupo_name.ID_IDIOMA,
	adm_empresas_v_grupo_name.TIPO_GRUPOPAL,
	IFNULL(adm_ventanas_grupo_name.DESC_GVENTANA,adm_empresas_v_grupo_name.DESC_GVENTANA) AS DESC_GVENTANA
	FROM adm_empresas_v_grupo_name
	LEFT JOIN adm_ventanas_grupo_name ON adm_ventanas_grupo_name.ID_GVENTANA=adm_empresas_v_grupo_name.ID_GVENTANA 
						AND adm_ventanas_grupo_name.ID_IDIOMA=adm_empresas_v_grupo_name.ID_IDIOMA
						AND adm_ventanas_grupo_name.ID_MEMPRESA=$_CLIENTE";

	//PRECARGA DE TITULO VENTANA EN CONFIGURACIÓN
	$sqlCons[1][93]="
	SELECT
	adm_empresas_v_cont_names.ID_VENTANA,
	adm_empresas_v_cont_names.ID_IDIOMA,
	adm_empresas_v_cont_names.TIPO_GRUPOPAL,
	adm_empresas_v_cont_names.TITULO_VENTANA,
	adm_empresas_v_cont_names.STITULO_VENTANA
	FROM adm_empresas_v_cont_names";
	$sqlOrder[1][93]="ORDER BY adm_empresas_v_cont_names.ID_VENTANA";
	$sqlCons[2][93]="
	SELECT
	adm_empresas_v_cont_names.ID_VENTANA,
	adm_empresas_v_cont_names.ID_IDIOMA,
	adm_empresas_v_cont_names.TIPO_GRUPOPAL,
	IFNULL(adm_ventanas_cont_names.TITULO_VENTANA,adm_empresas_v_cont_names.TITULO_VENTANA) AS TITULO_VENTANA,
	IFNULL(adm_ventanas_cont_names.STITULO_VENTANA,adm_empresas_v_cont_names.STITULO_VENTANA) AS STITULO_VENTANA
	FROM adm_empresas_v_cont_names
	LEFT JOIN adm_ventanas_cont_names ON adm_ventanas_cont_names.ID_VENTANA=adm_empresas_v_cont_names.ID_VENTANA 
						AND adm_ventanas_cont_names.ID_IDIOMA=adm_empresas_v_cont_names.ID_IDIOMA
						AND adm_ventanas_cont_names.ID_MEMPRESA=$_CLIENTE";
	//PRECARGA NOMBRE DE CAMPO EN CONFIGURACION
	$sqlCons[1][94]="
	SELECT
	adm_empresas_v_cont_campo_names.ID_CAMPO,
	adm_empresas_v_cont_campo_names.ID_IDIOMA,
	adm_empresas_v_cont_campo_names.TIPO_GRUPOPAL,
	adm_empresas_v_cont_campo_names.TITULO_CAMPO,
	adm_empresas_v_cont_campo_names.TOOLTIP_CAMPO
	FROM adm_empresas_v_cont_campo_names";
	$sqlOrder[1][94]="ORDER BY adm_empresas_v_cont_campo_names.ID_CAMPO";
	$sqlCons[2][94]="
	SELECT
	adm_empresas_v_cont_campo_names.ID_CAMPO,
	adm_empresas_v_cont_campo_names.ID_IDIOMA,
	adm_empresas_v_cont_campo_names.TIPO_GRUPOPAL,
	IFNULL(adm_ventanas_cont_campo_names.TITULO_CAMPO,adm_empresas_v_cont_campo_names.TITULO_CAMPO) AS TITULO_CAMPO,
	IFNULL(adm_ventanas_cont_campo_names.TOOLTIP_CAMPO,adm_empresas_v_cont_campo_names.TOOLTIP_CAMPO) AS TOOLTIP_CAMPO
	FROM adm_empresas_v_cont_campo_names
	LEFT JOIN adm_ventanas_cont_campo_names ON adm_ventanas_cont_campo_names.ID_CAMPO=adm_empresas_v_cont_campo_names.ID_CAMPO 
						AND adm_ventanas_cont_campo_names.ID_IDIOMA=adm_empresas_v_cont_campo_names.ID_IDIOMA
						AND adm_ventanas_cont_campo_names.ID_MEMPRESA=$_CLIENTE";

	

	//COLORES
	$sqlCons[1][98]="
	SELECT
	fac_cnfcolor.ID_CNFCOLOR,
	fac_cnfcolor.ID_IDIOMA,
	fac_cnfcolor.DESC_CNFCOLOR
	FROM fac_cnfcolor
	JOIN fac_idioma ON fac_idioma.ID_IDIOMA=fac_cnfcolor.ID_IDIOMA AND fac_idioma.ID_IDIOMA=:idioma";
	$sqlOrder[1][98]="ORDER BY fac_cnfcolor.DESC_CNFCOLOR";

	//VERSIONES DE TABLAS
	$sqlCons[1][99]="
	SELECT
	adm_api_tablas.ID_TABLA,
	adm_api_tablas.NAME_TABLA,
	adm_api_tablas.CONSULTA_X1,
	adm_api_tablas.CONSULTA_X2,
	adm_api_tablas.LOGGED,
	adm_api_tablas.MEMPRESA,
	adm_api_tablas.TEMPRESA,
	adm_api_tablas.IDIOMA,
	adm_api_tablas.USUARIO,
	adm_api_tablas.VENTANA,
	adm_api_tablas.MODO,
	if(adm_api_tablas.MEMPRESA=1,
		IFNULL(adm_api_tablas_versiones_empresa.VERSION,0),
		IFNULL(adm_api_tablas_versiones.VERSION,0)) AS VERSION
	FROM adm_api_tablas
	LEFT JOIN adm_api_tablas_versiones ON adm_api_tablas_versiones.ID_TABLA=adm_api_tablas.ID_TABLA
	LEFT JOIN adm_api_tablas_versiones_empresa ON adm_api_tablas_versiones_empresa.ID_MEMPRESA=:id_mempresa AND adm_api_tablas_versiones_empresa.ID_TABLA=adm_api_tablas.ID_TABLA";
	//VENTANAS POR CLASE 
	$sqlCons[1][100]="
	SELECT 
	'VEN' AS OPCION,
	adm_ventanas.ID_VENTANA,
	adm_empresas_v_names.VENTANA_NOMBRE AS VENTANA_NOMBRE,
	adm_ventanas.ID_GVENTANA,
	adm_ventanas.VENTANA_AGRUPA,
	adm_empresas_v_names.SCVENTANA AS SCVENTANA,
	adm_ventanas.ORDEN,
	adm_ventanas.ACCIONES,
	adm_empresas_v_grupo_name.DESC_GVENTANA AS DESC_GVENTANA,
	adm_ventanas_etipo.TIPO_GRUPOPAL,
	IFNULL(adm_ventanas_etipo.PERMISO,0) AS PERMISO_GRUPOVEN
	FROM adm_ventanas
	LEFT JOIN adm_empresas_v_names ON adm_empresas_v_names.ID_VENTANA=adm_ventanas.ID_VENTANA AND adm_empresas_v_names.ID_IDIOMA=$_IDIOMA AND adm_empresas_v_names.TIPO_GRUPOPAL=$_GCLIENTE

	LEFT JOIN adm_ventanas_grupo ON adm_ventanas_grupo.ID_GVENTANA=adm_ventanas.ID_GVENTANA
	LEFT JOIN adm_empresas_v_grupo_name ON adm_empresas_v_grupo_name.ID_GVENTANA=adm_ventanas_grupo.ID_GVENTANA AND adm_empresas_v_grupo_name.ID_IDIOMA=$_IDIOMA AND adm_empresas_v_grupo_name.TIPO_GRUPOPAL=$_GCLIENTE

	LEFT JOIN adm_ventanas_etipo ON adm_ventanas_etipo.ID_VENTANA=adm_ventanas.ID_VENTANA ";
	$sqlOrder[1][100]="ORDER BY adm_ventanas.ID_GVENTANA, adm_empresas_v_names.VENTANA_NOMBRE ";

	/*Areas CAMBIA EN _0**/
	$sqlCons[1][101]="
	SELECT
	'ARE' AS OPCION,
	s_cresp.ID_RESP,
	s_cresp.NOMB_RESP,
	s_cresp.ABR_RESP,
	s_cresp.SLUG_RESP,
	s_cresp.COMENT_RESP,
	s_cresp.DIRECCION,
	s_cresp.TELEFONO,
	s_cresp.HAB_RESP,
	s_cresp.ZOOM AS ZOOM_MAP,
	s_cresp.PPAL,
	fac_ciudades.ID_CIUDAD,
	fac_ciudades.NOMB_CIUDAD,
	fac_pais.NOMB_PAIS,
	fac_pais.COD_PAIS,
	fac_ciudades.DISTRITO_CIUDAD,
	X(s_cresp.LOCATION) AS REF_LAT,
	Y(s_cresp.LOCATION) AS REF_LON
	FROM s_cresp
	JOIN s_cresp_grupo ON s_cresp_grupo.ID_RESP=s_cresp.ID_RESP AND s_cresp_grupo.ID_GRUPO=$_GRUPO
	JOIN adm_empresas ON adm_empresas.ID_MEMPRESA=s_cresp.ID_MEMPRESA AND adm_empresas.ID_MEMPRESA=$_CLIENTE
	LEFT JOIN fac_ciudades ON fac_ciudades.ID_CIUDAD=s_cresp.ID_CIUDAD
	LEFT JOIN fac_pais ON fac_pais.ID_PAIS=fac_ciudades.ID_PAIS";
	$sqlOrder[1][101]="ORDER BY s_cresp.PPAL DESC,s_cresp.NOMB_RESP";

	/*Areas APICALL*/
	$sqlCons[3][101]="
	SELECT
	'ARE' AS OPCION,
	s_cresp.ID_RESP,
	s_cresp.NOMB_RESP,
	s_cresp.SLUG_RESP,
	s_cresp.DIRECCION,
	s_cresp.TELEFONO,
	s_cresp.HAB_RESP,       
	s_cresp.ZOOM AS ZOOM_MAP,
	s_cresp.PPAL,
	fac_ciudades.ID_CIUDAD,
	fac_ciudades.NOMB_CIUDAD,
	fac_pais.NOMB_PAIS,
	fac_pais.COD_PAIS,
	fac_ciudades.DISTRITO_CIUDAD,

	X(s_cresp.LOCATION) AS REF_LAT,
	Y(s_cresp.LOCATION) AS REF_LON,

	adm_empresas_tipo.TIPO_GRUPOPAL

	FROM s_cresp
	JOIN adm_empresas ON adm_empresas.ID_MEMPRESA=s_cresp.ID_MEMPRESA
	LEFT JOIN fac_ciudades ON fac_ciudades.ID_CIUDAD=s_cresp.ID_CIUDAD
	LEFT JOIN fac_pais ON fac_pais.ID_PAIS=fac_ciudades.ID_PAIS
	LEFT JOIN adm_empresas_tipo ON adm_empresas_tipo.ID_TIPOE=adm_empresas.ID_TIPOE";
	/*Areas*/
	$sqlCons[5][101]="
	SELECT
	s_cresp.ID_RESP,
	s_cresp.NOMB_RESP,
	s_cresp.ABR_RESP,
	s_cresp.SLUG_RESP,
	s_cresp.COMENT_RESP,
	s_cresp.DIRECCION,
	s_cresp.TELEFONO,
	s_cresp.HAB_RESP,
	s_cresp.ZOOM AS ZOOM_MAP,
	s_cresp.PPAL,
	s_cresp_grupo.ID_GRUPO
	FROM s_cresp
	JOIN adm_empresas ON adm_empresas.ID_MEMPRESA=s_cresp.ID_MEMPRESA AND adm_empresas.ID_MEMPRESA=$_CLIENTE
	LEFT JOIN s_cresp_grupo ON s_cresp_grupo.ID_RESP=s_cresp.ID_RESP ";

	/*Areas*/
	$sqlCons[4][102]="
	SELECT
	'ARE' AS OPCION,
	s_cresp.ID_RESP,
	s_cresp.NOMB_RESP,
	s_cresp.ABR_RESP,
	s_cresp.SLUG_RESP,
	s_cresp.COMENT_RESP,
	s_cresp.DIRECCION,
	s_cresp.TELEFONO,
	s_cresp.HAB_RESP,
	s_cresp.ZOOM AS ZOOM_MAP,
	s_cresp.PPAL,
	fac_ciudades.ID_CIUDAD,
	fac_ciudades.NOMB_CIUDAD,
	fac_pais.NOMB_PAIS,
	fac_pais.COD_PAIS,
	fac_ciudades.DISTRITO_CIUDAD,
	X(s_cresp.LOCATION) AS REF_LAT,
	Y(s_cresp.LOCATION) AS REF_LON
	FROM s_cresp
	JOIN s_cresp_grupo ON s_cresp_grupo.ID_RESP=s_cresp.ID_RESP AND s_cresp_grupo.ID_GRUPO=$_GRUPO
	JOIN adm_empresas ON adm_empresas.ID_MEMPRESA=s_cresp.ID_MEMPRESA AND adm_empresas.ID_MEMPRESA=:empresa
	LEFT JOIN fac_ciudades ON fac_ciudades.ID_CIUDAD=s_cresp.ID_CIUDAD
	LEFT JOIN fac_pais ON fac_pais.ID_PAIS=fac_ciudades.ID_PAIS";

	//CIUDADES y AREAS
	$sqlCons[1][102]="
	SELECT s_cresp_ciudades.ID_CIUDAD,
	s_cresp_ciudades.ID_RESP,
	s_cresp.NOMB_RESP,
	s_cresp.SLUG_RESP,
	fac_ciudades.NOMB_CIUDAD,
	fac_pais.COD_PAIS,
	fac_pais.NOMB_PAIS
	FROM s_cresp_ciudades
	LEFT JOIN s_cresp ON s_cresp.ID_RESP=s_cresp_ciudades.ID_RESP
	LEFT JOIN fac_ciudades ON fac_ciudades.ID_CIUDAD=s_cresp_ciudades.ID_CIUDAD
	LEFT JOIN fac_pais ON fac_pais.ID_PAIS=fac_ciudades.ID_PAIS";
	$sqlOrder[1][102]="ORDER BY s_cresp_ciudades.ID_RESP";

	$sqlCons[1][103]="SELECT
	fac_und_tiempo.ID_UNITIME,
	fac_und_tiempo.ID_IDIOMA,
	fac_und_tiempo.UNIDAD,
	fac_und_tiempo.UNIDAD_PLUR,
	fac_und_tiempo.ABR_UND,
	fac_und_tiempo.MYSQL_UND,
	fac_und_tiempo.TIPO_M,
	fac_und_tiempo.EQUIVALENCIAS,
	fac_und_tiempo.PT
	FROM fac_und_tiempo";
	$sqlOrder[1][103]="ORDER BY fac_und_tiempo.ID_UNITIME";

	$sqlCons[2][103]="
	SELECT
	fac_und_tiempo.ID_UNITIME as id,
	fac_und_tiempo.UNIDAD as unid,
	fac_und_tiempo.ABR_UND as abr
	FROM fac_und_tiempo
	JOIN fac_idioma ON fac_idioma.ID_IDIOMA=fac_und_tiempo.ID_IDIOMA AND fac_idioma.ID_IDIOMA=:idioma";
	$sqlOrder[2][103]="ORDER BY fac_und_tiempo.ID_UNITIME";

	$sqlCons[1][104]="
	SELECT 
		adm_empresas_url.ID_URLS,
	    adm_empresas_url.ID_MEMPRESA,
	    adm_empresas_url.URL,
	    adm_empresas_url.VERIFICADA_URL
	FROM adm_empresas_url";
	$sqlOrder[1][104]="ORDER BY adm_empresas_url.ID_URLS";

	$sqlCons[1][105]="
	SELECT 
		'REGUS' AS OPCION,
		adm_usuarios_reg.ID_SES,
		DATE_FORMAT(CONVERT_TZ(adm_usuarios_reg.FECHA_U,'+00:00','$_TZ'),'%d/%m/%Y %H:%i')  AS FECHA_U, 
	    adm_usuarios_reg.FECHAF_U,
	    adm_usuarios_reg.CERRADO_U,
	    adm_usuarios_reg.PC_U,
	    adm_usuarios_reg.IP_U,
	    adm_usuarios_reg.ACTIVA_U,

		adm_usuarios.ID_USUARIO,
		adm_usuarios.NOMBRE_U,
		adm_usuarios.APELLIDO_U,
		adm_usuarios.CORREO_U

	FROM adm_usuarios_reg
	INNER JOIN adm_usuarios_empresa ON adm_usuarios_empresa.ID_MEMPRESA=$_CLIENTE AND adm_usuarios_empresa.ID_USUARIO=adm_usuarios_reg.ID_USUARIO
	LEFT JOIN adm_usuarios ON adm_usuarios.ID_USUARIO=adm_usuarios_reg.ID_USUARIO
	LEFT JOIN adm_usuarios_datos ON adm_usuarios_datos.ID_USUARIO=adm_usuarios.ID_USUARIO";
	$sqlOrder[1][105]="ORDER BY adm_usuarios_reg.FECHA_U DESC";

	/**********************/
	/****** INGRESOS ******/
	/**** EN EL TIEMPO ****/
	/**********************/
	$sqlCons[2][105]="
	SELECT 
	STRAIGHT_JOIN
	%s,
	COUNT(temp_usuarios.ID_SES) AS C_SES
	FROM t_fechas
	
	LEFT JOIN (
		SELECT 	adm_usuarios_reg.ID_SES 
			,	DATE(CONVERT_TZ(adm_usuarios_reg.FECHA_U,'+00:00','$_TZ')) AS FECHA_U
		FROM adm_usuarios_reg
		LEFT JOIN adm_usuarios ON adm_usuarios.ID_USUARIO=adm_usuarios_reg.ID_USUARIO
		LEFT JOIN adm_usuarios_empresa ON adm_usuarios_empresa.ID_USUARIO=adm_usuarios.ID_USUARIO AND adm_usuarios_empresa.LAST=1
		%s
	) AS temp_usuarios ON temp_usuarios.FECHA_U=t_fechas.FECHA
	%s";
	$sqlOrder[2][105]="ORDER BY t_fechas.FECHA";

	$sqlCons[3][105]="
	SELECT 
	STRAIGHT_JOIN
	%s,
	COUNT(temp_usuarios.ID_USUARIO) AS C_SES
	FROM t_fechas
	LEFT JOIN (
		SELECT 	adm_usuarios.ID_USUARIO
			,	DATE(CONVERT_TZ(adm_usuarios.FECHA_U,'+00:00','$_TZ')) AS FECHA_U
		FROM adm_usuarios
		LEFT JOIN adm_usuarios_empresa ON adm_usuarios_empresa.ID_USUARIO=adm_usuarios.ID_USUARIO AND adm_usuarios_empresa.LAST=1
		%s
	) AS temp_usuarios ON temp_usuarios.FECHA_U=t_fechas.FECHA
	%s";
	$sqlOrder[3][105]="ORDER BY t_fechas.FECHA";

	/***********************/
	//REDES SOCIALES
	/***********************/
	$sqlCons[1][106]="
	SELECT 
		fac_rs_origen.ID_ORIGEN,
	    fac_rs_origen.DESC_ORIGEN,
	    fac_rs_origen.PERFIL,
	    fac_rs_origen.CONEX,
	    fac_rs_origen.HAB_ORIGEN
	FROM fac_rs_origen";
	$sqlOrder[1][106]="ORDER BY fac_rs_origen.DESC_ORIGEN";
	/***********************/
	//REDES SOCIALES USUARIO
	/***********************/
	$sqlCons[1][107]="
	SELECT 
	fac_rs_origen.ID_ORIGEN,
	fac_rs_origen.DESC_ORIGEN,
	fac_rs_origen.PERFIL,
	fac_rs_origen.CONEX,
	fac_rs_origen.HAB_ORIGEN,
	IFNULL(adm_usuarios_datosrs.ID_USUARIO,:user) AS ID_USUARIO,
	IF(ISNULL(adm_usuarios_datosrs.ID_USUARIO),0,1) AS EXISTE,
	IFNULL(RS_VALOR,'') AS RS_VALOR
	FROM fac_rs_origen
	LEFT JOIN adm_usuarios_datosrs ON adm_usuarios_datosrs.ID_USUARIO=:user AND adm_usuarios_datosrs.ID_ORIGEN=fac_rs_origen.ID_ORIGEN";
	$sqlOrder[1][107]="ORDER BY fac_rs_origen.DESC_ORIGEN";
	$sqlCons[2][107]="
	SELECT 
	fac_rs_origen.ID_ORIGEN,
	fac_rs_origen.DESC_ORIGEN,
	fac_rs_origen.PERFIL,
	fac_rs_origen.CONEX,
	adm_usuarios_datosrs.ID_USUARIO,
	RS_VALOR
	FROM adm_usuarios_datosrs
	LEFT JOIN fac_rs_origen ON fac_rs_origen.ID_ORIGEN=adm_usuarios_datosrs.ID_ORIGEN";



	if($_PROYECTO==1) 		include "consultas_01.php";
	elseif($_PROYECTO==8)	include "consultas_08.php";
	elseif($_PROYECTO==10)	include "consultas_10.php";
	elseif($_PROYECTO==11)	include "consultas_11.php";
	elseif($_PROYECTO==13)	include "consultas_13.php";
	elseif($_PROYECTO==14)	include "consultas_14.php";
	elseif($_PROYECTO==15)	include "consultas_15.php";
	elseif($_PROYECTO==16)	include "consultas_16.php";
	elseif($_PROYECTO==18)	include "consultas_18.php";
	elseif($_PROYECTO==19)	include "consultas_19.php";
	elseif($_PROYECTO==20)	include "consultas_20.php";
	elseif($_PROYECTO==21)	include "consultas_21.php";
	elseif($_PROYECTO==22)	include "consultas_22.php";
	elseif($_PROYECTO==23)	include "consultas_23.php";
	elseif($_PROYECTO==24)	include "consultas_24.php";
	elseif($_PROYECTO==25)	include "consultas_25.php";
	elseif($_PROYECTO==26)	include "consultas_26.php";
	elseif($_PROYECTO==27)	include "consultas_27.php";
	elseif($_PROYECTO==28)	include "consultas_28.php";
	elseif($_PROYECTO==29)	include "consultas_29.php";
	elseif($_PROYECTO==31)	include "consultas_31.php";
	elseif($_PROYECTO==32)	include "consultas_32.php";
	elseif($_PROYECTO==33)	include "consultas_33.php";
	elseif($_PROYECTO==34)	include "consultas_34.php";
	elseif($_PROYECTO==35)	include "consultas_35.php";
	elseif($_PROYECTO==36)	include "consultas_36.php";

}
?>
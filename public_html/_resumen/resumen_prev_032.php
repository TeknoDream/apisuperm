<?php
/*
---------------------------------------------------------------------------------
                        SUPERMAESTROS BACKOFFICE
                             Proyecto N° 32
                        Archivo: resumen_prev_032.php
  Descripción: archivo de configuración resúmenes de la información de los objetos JSON
--------------------------------------------------------------------------------

Este es el archivo que configura los resúmenes de la información de un objeto e información
relevante y relacionada con el objeto que se está mostrando, como por ejemplo al objeto del 
usuario al hacer click en el título mostrará el objeto hacia la izquierda y en el panel 
derecho mostrará: Proyectos, cotizaciones recibidas y los mensajes relacioanados con ese usuario.

Cada resumen se configura de acuerdo al número de módulo que le corresponda, siempre desde la
perspectiva del módulo principal.

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

/*************************/
/*************************/
/*****   Usuarios *********/
/*************************/

if($cnf==500){
	$sWhere=encrip_mysql('adm_usuarios.ID_USUARIO');
	$s=$sqlCons[0][500]." WHERE $sWhere=:id LIMIT 1";		
	$req = $dbEmpresa->prepare($s); 
	$req->bindParam(':id', $id_sha);
	$req->execute();
	if(!$reg = $req->fetch()){
		$salidas["ERR"]=true;
		echo json_encode($salidas);
		exit(0);
	}

	$k=0;
	// Lista que se despliega en la zona derecha
	if ($reg['TYPE_USUARIO']!=1){ //Si es un usuario estandar mostramos determinadas opciones

		$salidas["menu"][$k]["label"]="txt-163-1"; //Proyectos (ofertas)
		$salidas["menu"][$k]["tinfo"]=0; 
		$salidas["menu"][$k]["default"]=1; //el default=1 de  indica que esa pestaña cargará abierta por defecto

		$k++;
		$salidas["menu"][$k]["label"]="txt-191-0"; //Cotizaciones Recibidas
		$salidas["menu"][$k]["tinfo"]=2;

		$k++;
		$salidas["menu"][$k]["label"]="txt-161-1"; //Mensajes
		$salidas["menu"][$k]["tinfo"]=5;
	}
	else //Si el usuario es instalador se muestran opciones adicionales
	{
		$salidas["menu"][$k]["label"]="txt-163-1"; //Proyectos (ofertas)
		$salidas["menu"][$k]["tinfo"]=0;
		$salidas["menu"][$k]["default"]=1;

		$k++;
		$salidas["menu"][$k]["label"]="txt-160-0"; //Estado de Cuenta
		$salidas["menu"][$k]["tinfo"]=6;

		$k++;
		$salidas["menu"][$k]["label"]="txt-164-1"; //Remodelaciones (proyectos)
		$salidas["menu"][$k]["tinfo"]=1;
	
		$k++;
		$salidas["menu"][$k]["label"]="txt-158-1"; //Facturas
		$salidas["menu"][$k]["tinfo"]=4;

		$k++;
		$salidas["menu"][$k]["label"]="txt-190-0"; //Cotizaciones Enviadas
		$salidas["menu"][$k]["tinfo"]=3;
	
		$k++;
		$salidas["menu"][$k]["label"]="txt-161-1"; //Mensajes
		$salidas["menu"][$k]["tinfo"]=5;
	}	

	$reg['OPCION']="SMUSU";
	json_Item($reg,$salidaUNO,$cnf,$md,$_sysvars_r);	
	$salidas=array_merge($salidas,$salidaUNO);
}
/****************************/
/****************************/
/***** Remodelaciones *******/
/****************************/
elseif($cnf==501){
	$sWhere=encrip_mysql('y_proyectos.ID_PROY');
	$s=$sqlCons[0][501]." WHERE $sWhere=:id LIMIT 1";		
	$req = $dbEmpresa->prepare($s); 
	$req->bindParam(':id', $id_sha);
	$req->execute();
	if(!$reg = $req->fetch()){
		$salidas["ERR"]=true;
		echo json_encode($salidas);
		exit(0);
	}

	$k=0;
	$salidas["menu"][$k]["label"]="txt-105-1"; //Calificaciones
	$salidas["menu"][$k]["default"]=1;
	$salidas["menu"][$k]["tinfo"]=0;

	$k++;
	$salidas["menu"][$k]["label"]="txt-214-0"; //Imágenes
	$salidas["menu"][$k]["tinfo"]=1;
	
	$reg['OPCION']="SMPRO";
	json_Item($reg,$salidaUNO,$cnf,$md,$_sysvars_r);	
	$salidas=array_merge($salidas,$salidaUNO);
}
/****************************/
/****************************/
/******** Cotización* *******/
/****************************/
elseif($cnf==504){
	$sWhere=encrip_mysql('y_cotizacion.ID_COTIZ');
	$s=$sqlCons[0][504]." WHERE $sWhere=:id LIMIT 1";		
	$req = $dbEmpresa->prepare($s); 
	$req->bindParam(':id', $id_sha);
	$req->execute();
	if(!$reg = $req->fetch()){
		$salidas["ERR"]=true;
		echo json_encode($salidas);
		exit(0);
	}

	$k=0;
	$salidas["menu"][$k]["label"]="txt-204-0"; //Items Cotizados
	$salidas["menu"][$k]["default"]=1;
	$salidas["menu"][$k]["tinfo"]=0;

	
	$reg['OPCION']="SMCOTI";
	json_Item($reg,$salidaUNO,$cnf,$md,$_sysvars_r);	
	$salidas=array_merge($salidas,$salidaUNO);
}
?>
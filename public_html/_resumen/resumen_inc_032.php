<?php
/*
----------------------------------------------------------------------------------------
                        SUPERMAESTROS BACKOFFICE
                             Proyecto N° 32
                        Archivo: resumen_inc_032.php
  Descripción: archivo de configuración resúmenes de la información de los objetos JSON
---------------------------------------------------------------------------------------

Este es el archivo que configura los resúmenes de la información de un objeto e información
relevante y relacionada con el objeto que se está mostrando, este archivo llena las tablas
que se muestran en cada una de las opciones que se configuran el archivo resumen_prev_032.php

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
/*****   Usuarios *********/
/*************************/ 
/* Desde las perspectiva del usuario como módulo principal
se llenan cada una de las pestañas, haciendo las consultas
en base al usuario que se esta mostrando */
if($cnf==500){

	/**Pestaña de Proyectos***/
	/*************************/ 
	if($tinfo==0){ 
		$sWhere_id=encrip_mysql('adm_usuarios.ID_USUARIO');	
		$sWhere=" WHERE $sWhere_id=:id ".sWhere(5020,$busc,$_PROYECTO);
		$s=$sqlCons[0][502].$sWhere; 
		$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute(); 
		$Total = $req->fetchColumn();
		$IniDato=($PagActual-1)*$MaxItems;
		/*TABLA*/
		$s=$sqlCons[0][502].$sWhere.$sqlOrder[0][502]." LIMIT $IniDato,$MaxItems";
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute(); 
		//se lista la información en la tabla que se muestra al abrir la pestaña
		$ops=array('tipo'=>'tabla'
				,	'attr'=>array('width'=>'100%'));
		
		$k=0;
		$titulos[$k]["cont"][]=array(	"label"=>"txt-170-0"
									,	"width"=>10
									,	"css"=>array("text-align"=>"center"));//ID oferta
		
		$titulos[$k]["cont"][]=array(	"label"=>"txt-103-0"
									,	"width"=>20
									,	"css"=>array("text-align"=>"center"));//Descripción
		
		$titulos[$k]["cont"][]=array(	"label"=>"txt-165-0"
									,	"width"=>20
									,	"css"=>array("text-align"=>"center"));//Ciudad
		
		$titulos[$k]["cont"][]=array(	"label"=>"txt-126-1"
									,	"width"=>15
									,	"css"=>array("text-align"=>"center"));//Fecha Inicio
		
		$titulos[$k]["cont"][]=array(	"label"=>"txt-127-1"
									,	"width"=>10
									,	"css"=>array("text-align"=>"center"));//Fecha Fin 

		$titulos[$k]["cont"][]=array(	"label"=>"txt-179-0"
									,	"width"=>15
									,	"css"=>array("text-align"=>"center"));//Fecha de Creación
		$titulos[$k]["cont"][]=array(	"label"=>"txt-171-1"
									,	"width"=>10
									,	"css"=>array("text-align"=>"center"));//Observaciones

		$k=0;
		$body=array();
		while($reg = $req->fetch()){			

			$i=0;
			$body[$k]["cont"][$i]=array("label"=>$reg['ID_OFERTA']); //ID oferta	

			$i++;
			$body[$k]["cont"][$i]=array("label"=>imprimir($reg['TITLE_OFERTA']));//Descripción
			if($PermisosA[500]["P"]==1){			
				$body[$k]["cont"][$i]["link"]=2;
				$body[$k]["cont"][$i]["cod"]="md=".encrip($reg["ID_OFERTA"]).encrip(500,2);
				$body[$k]["cont"][$i]["pagina"]='/abstract';
			}			
			
		
			$i++;			
			$body[$k]["cont"][$i]=array("label"=>$reg['NOMB_CIUDAD']
									,	"css"=>array("text-align"=>"center"));	//Ciudad

			$i++;			
			$body[$k]["cont"][$i]=array("label"=>$reg['FECHAI_OFERTA']
									,	"css"=>array("text-align"=>"center"));	//Fecha Inicio
		
			
			$i++;			
			$body[$k]["cont"][$i]=array("label"=>$reg['FECHAF_OFERTA']
									,	"css"=>array("text-align"=>"center"));	//Fecha Fin 

			$i++;			
			$body[$k]["cont"][$i]=array("label"=>$reg['FECHAS_OFERTA']
									,	"css"=>array("text-align"=>"center"));	//Fecha de Creación

			$i++;
			$body[$k]["cont"][$i]=array("label"=>$reg['COMENT_OFERT']
									,	"css"=>array("text-align"=>"center"));	//Observacione

			$k++;
		}
		$tabla=PrintTablas($ops,$titulos,$body);
		$salidas=array_merge($salidas,$tabla);
		print_paginacion($salidas,$Total,$PagActual,$idMaxItem);	
	}
	elseif($tinfo==6){

		$sWhere_id=encrip_mysql('adm_usuarios_m.ID_USUARIO');	
		$sWhere=" WHERE $sWhere_id=:id ".($busc!=''?' AND ':'').sWhere(5050,$busc,$_PROYECTO);

		$s=$sqlCons[0][505].$sWhere; 
		$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute(); 
		$Total = $req->fetchColumn();
		$IniDato=($PagActual-1)*$MaxItems;
		
		/*TABLA*/
		$s=$sqlCons[0][505].$sWhere.$sqlOrder[0][505]." LIMIT $IniDato,$MaxItems";
		
		$i=0;	
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute();
		//tengo dudas aca como mostrar la relación de instalador con el ID de factura 

		$ops=array('tipo'=>'tabla'
				,	'attr'=>array('width'=>'100%'));
		$k=0;
		$titulos[$k]["cont"][]=array(	"label"=>"txt-170-0"
									,	"width"=>5
									,	"css"=>array("text-align"=>"center"));// ID
		$titulos[$k]["cont"][]=array(	"label"=>"txt-107-0"
									,	"width"=>15
									,	"css"=>array("text-align"=>"center"));//Fecha
		$titulos[$k]["cont"][]=array(	"label"=>"txt-201-0"
									,	"width"=>25
									,	"css"=>array("text-align"=>"center"));//Comentario
		$titulos[$k]["cont"][]=array(	"label"=>"txt-222-0"
									,	"width"=>15
									,	"css"=>array("text-align"=>"center"));//Ingreso
		$titulos[$k]["cont"][]=array(	"label"=>"txt-223-0"
									,	"width"=>10
									,	"css"=>array("text-align"=>"center"));//Salida		
		$titulos[$k]["cont"][]=array(	"label"=>"txt-173-0"
									,	"width"=>10
									,	"css"=>array("text-align"=>"center"));//Eliminar
		$k=0;
		$body=array();
		while($reg = $req->fetch()){			
			$id_sha=encrip($reg["ID_ECUENTA"]);
			$eliminar_a=$id_sha.$c_sha.$acc01;
			$i=0;			
			$body[$k]["cont"][$i]=array("label"=>$reg['ID_ECUENTA']
									,	"css"=>array("text-align"=>"center"));//ID

			$i++;			
			$body[$k]["cont"][$i]=array("label"=>$reg['FECHAS_ECUENTA']
									,	"css"=>array("text-align"=>"center"));//Fecha
		
			$i++;			
			$body[$k]["cont"][$i]=array("label"=>$reg['COMMEN_ECUENTA']
									,	"css"=>array("text-align"=>"center"));//Comentario
			$i++;			
			$body[$k]["cont"][$i]=array("label"=>$reg['INIP_ECUENTA']
									,	"css"=>array("text-align"=>"center"));//Ingreso Puntos
			$i++;			
			$body[$k]["cont"][$i]=array("label"=>$reg['OUTP_ECUENTA']
									,	"css"=>array("text-align"=>"center"));//Salida Puntos							
			
			$i++;
			$body[$k]["cont"][$i]=array("link"	=>	1
												,	"label"	=>	'txt-173-0'
												,	"cod"	=>	"md=".$eliminar_a //boton eliminar
												,	"pagina"=>	'/delete'
												,	"tipo"	=>	'button'
												,	"icon"	=>	"fa-trash");
			$k++;
		}
		$tabla=PrintTablas($ops,$titulos,$body);
		$salidas=array_merge($salidas,$tabla);
		
		$cargado=true;
		$salidas["barra"]=array();
			
		
		$k=0;
		$salidas["barra"][$k]=array();
		$salidas["barra"][$k]["agrupar"]=0;
		$salidas["barra"][$k]["contenido"]=array();
		
		$i=0;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_nuevo;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$nuevo_tag;
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/edit';
		
		$k++;
		$salidas["barra"][$k]=array();
		$salidas["barra"][$k]["agrupar"]=1;
		$salidas["barra"][$k]["contenido"]=array();
		
		$i=0;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_actualizar;
		print_paginacion($salidas,$Total,$PagActual,$idMaxItem);		
	}
	/**Pestaña de Remodelaciones***/
	/******************************/ 
	elseif($tinfo==1){
		$sWhere_id=encrip_mysql('adm_usuarios.ID_USUARIO');	
		$sWhere=" WHERE $sWhere_id=:id ".sWhere(5010,$busc,$_PROYECTO);
		$s=$sqlCons[0][501].$sWhere; 
		$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute(); 
		$Total = $req->fetchColumn();
		$IniDato=($PagActual-1)*$MaxItems;
		/*TABLA*/
		$s=$sqlCons[0][501].$sWhere.$sqlOrder[0][501]." LIMIT $IniDato,$MaxItems";
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute(); 

		$ops=array('tipo'=>'tabla'
				,	'attr'=>array('width'=>'100%'));
		
		$k=0;
		$titulos[$k]["cont"][]=array(	"label"=>"txt-170-0"
									,	"width"=>10
									,	"css"=>array("text-align"=>"center"));//ID proyecto
		
		$titulos[$k]["cont"][]=array(	"label"=>"txt-101-0"
									,	"width"=>20
									,	"css"=>array("text-align"=>"center"));//Nombre Proyecto
		
		
		$titulos[$k]["cont"][]=array(	"label"=>"txt-179-0"
									,	"width"=>20
									,	"css"=>array("text-align"=>"center"));//Fecha de Creación
		$titulos[$k]["cont"][]=array(	"label"=>"txt-105-0"
									,	"width"=>15
									,	"css"=>array("text-align"=>"center"));//Calificación
		

		$k=0;
		$body=array();
		while($reg = $req->fetch()){			

			$i=0;
			$body[$k]["cont"][$i]=array("label"=>$reg['ID_PROY']); //ID proyecto

			$i++;
			$body[$k]["cont"][$i]=array("label"=>imprimir($reg['NOMB_PROY']));//Nombre Proyecto
		
			if($PermisosA[501]["P"]==1){			
				$body[$k]["cont"][$i]["link"]=2;
				$body[$k]["cont"][$i]["cod"]="md=".encrip($reg["ID_PROY"]).encrip(501,2);
				$body[$k]["cont"][$i]["pagina"]='/abstract';
			}			
			
			
			$i++;			
			$body[$k]["cont"][$i]=array("label"=>$reg['FECHAS_PROY']
									,	"css"=>array("text-align"=>"center"));	//Fecha de Creación

			$i++;			
			$body[$k]["cont"][$i]=array("label"=>$reg['VCALIF_PROY']
									,	"css"=>array("text-align"=>"center"));	//Calificación
			
			$k++;
		}
		$tabla=PrintTablas($ops,$titulos,$body);
		$salidas=array_merge($salidas,$tabla);
		print_paginacion($salidas,$Total,$PagActual,$idMaxItem);	
	}
	/**Pestaña de Cotizaciones recibidas (usuario estandar)***/
	/*********************************************************/ 
	elseif($tinfo==2){
		$sWhere_id=encrip_mysql('y_cotizacion.ID_USUARIO_U');	
		$sWhere=" WHERE $sWhere_id=:id ".sWhere(5040,$busc,$_PROYECTO);
		$s=$sqlCons[0][504].$sWhere; 
		$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute(); 
		$Total = $req->fetchColumn();
		$IniDato=($PagActual-1)*$MaxItems;
		/*TABLA*/
		$s=$sqlCons[0][504].$sWhere.$sqlOrder[0][504]." LIMIT $IniDato,$MaxItems";
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute(); 

		$ops=array('tipo'=>'tabla'
				,	'attr'=>array('width'=>'100%'));
		
		$k=0;
		$titulos[$k]["cont"][]=array(	"label"=>"txt-170-0"
									,	"width"=>10
									,	"css"=>array("text-align"=>"center"));//ID Cotizacion
		
		$titulos[$k]["cont"][]=array(	"label"=>"txt-100-0"
									,	"width"=>20
									,	"css"=>array("text-align"=>"center"));//Instalador 
		
		
		$titulos[$k]["cont"][]=array(	"label"=>"txt-107-0"
									,	"width"=>20
									,	"css"=>array("text-align"=>"center"));//Fecha cotizacion
		$titulos[$k]["cont"][]=array(	"label"=>"txt-152-0"
									,	"width"=>15
									,	"css"=>array("text-align"=>"center"));//Valor cotizacion
		$
		

		$k=0;
		$body=array();
		while($reg = $req->fetch()){			

			$i=0;
			$body[$k]["cont"][$i]=array("label"=>$reg['ID_COTIZ']); //ID cotizacion

			$i++;
			$body[$k]["cont"][$i]=array("label"=>imprimir($reg['NOMBRE_M']." ".$reg['APELLIDO_M']));//Instalador
			if($PermisosA[500]["P"]==1){			
				$body[$k]["cont"][$i]["link"]=2;
				$body[$k]["cont"][$i]["cod"]="md=".encrip($reg["ID_USUARIO_M"]).encrip(500,2);
				$body[$k]["cont"][$i]["pagina"]='/abstract';
			}
						
			
			$i++;			
			$body[$k]["cont"][$i]=array("label"=>$reg['FECHAS_COTIZ']
									,	"css"=>array("text-align"=>"center"));	//Fecha cotizacion

			$i++;			
			$body[$k]["cont"][$i]=array("label"=>$reg['VTOT_COTIZ']
									,	"css"=>array("text-align"=>"center"));	//Valor cotizacion
			$k++;
		}
		$tabla=PrintTablas($ops,$titulos,$body);
		$salidas=array_merge($salidas,$tabla);
		print_paginacion($salidas,$Total,$PagActual,$idMaxItem);	
	}
	/**Pestaña de Cotizaciones enviadas (instalador)***/
	/**************************************************/ 
	elseif($tinfo==3){
		$sWhere_id=encrip_mysql('y_cotizacion.ID_USUARIO_M');	
		$sWhere=" WHERE $sWhere_id=:id ".sWhere(5040,$busc,$_PROYECTO);
		$s=$sqlCons[0][504].$sWhere; 
		$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute(); 
		$Total = $req->fetchColumn();
		$IniDato=($PagActual-1)*$MaxItems;
		/*TABLA*/
		$s=$sqlCons[0][504].$sWhere.$sqlOrder[0][504]." LIMIT $IniDato,$MaxItems";
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute(); 

		$ops=array('tipo'=>'tabla'
				,	'attr'=>array('width'=>'100%'));
		
		$k=0;
		$titulos[$k]["cont"][]=array(	"label"=>"txt-170-0"
									,	"width"=>10
									,	"css"=>array("text-align"=>"center"));//ID Cotizacion
		
		$titulos[$k]["cont"][]=array(	"label"=>"txt-187-0"
									,	"width"=>20
									,	"css"=>array("text-align"=>"center"));//Cliente
		
		
		$titulos[$k]["cont"][]=array(	"label"=>"txt-107-0"
									,	"width"=>20
									,	"css"=>array("text-align"=>"center"));//Fecha cotizacion
		$titulos[$k]["cont"][]=array(	"label"=>"txt-152-0"
									,	"width"=>15
									,	"css"=>array("text-align"=>"center"));//Valor cotizacion
		
		

		$k=0;
		$body=array();
		while($reg = $req->fetch()){			

			$i=0;
			$body[$k]["cont"][$i]=array("label"=>$reg['ID_COTIZ']); //ID cotizacion

			$i++;
			$body[$k]["cont"][$i]=array("label"=>imprimir($reg['NOMBRE_U']." ".$reg['APELLIDO_U']));//Instalador
			if($PermisosA[500]["P"]==1){			
				$body[$k]["cont"][$i]["link"]=2;
				$body[$k]["cont"][$i]["cod"]="md=".encrip($reg["ID_USUARIO_U"]).encrip(500,2);
				$body[$k]["cont"][$i]["pagina"]='/abstract';
			}
						
			
			$i++;			
			$body[$k]["cont"][$i]=array("label"=>$reg['FECHAS_COTIZ']
									,	"css"=>array("text-align"=>"center"));	//Fecha cotizacion

			$i++;			
			$body[$k]["cont"][$i]=array("label"=>$reg['VTOT_COTIZ']
									,	"css"=>array("text-align"=>"center"));	//Valor cotizacion
			$k++;
		}
		$tabla=PrintTablas($ops,$titulos,$body);
		$salidas=array_merge($salidas,$tabla);
		print_paginacion($salidas,$Total,$PagActual,$idMaxItem);	
	}
	/**Pestaña de Facturas***/
	/*************************/ 
	elseif($tinfo==4){
		$sWhere_id=encrip_mysql('adm_usuarios.ID_USUARIO');	
		$sWhere=" WHERE $sWhere_id=:id ".sWhere(5030,$busc,$_PROYECTO);
		$s=$sqlCons[0][503].$sWhere; 
		$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute(); 
		$Total = $req->fetchColumn();
		$IniDato=($PagActual-1)*$MaxItems;
		/*TABLA*/
		$s=$sqlCons[0][503].$sWhere.$sqlOrder[0][503]." LIMIT $IniDato,$MaxItems";
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute(); 

		$ops=array('tipo'=>'tabla'
				,	'attr'=>array('width'=>'100%'));
		
		$k=0;
		$titulos[$k]["cont"][]=array(	"label"=>"txt-170-0"
									,	"width"=>10
									,	"css"=>array("text-align"=>"center"));//ID factura
		$titulos[$k]["cont"][]=array(	"label"=>"txt-171-1"
									,	"width"=>20
									,	"css"=>array("text-align"=>"center"));//Observaciones
		$titulos[$k]["cont"][]=array(	"label"=>"txt-107-0"
									,	"width"=>20
									,	"css"=>array("text-align"=>"center"));//Fecha factura
		$titulos[$k]["cont"][]=array(	"label"=>"txt-152-0"
									,	"width"=>15
									,	"css"=>array("text-align"=>"center"));//Valor factura
		$titulos[$k]["cont"][]=array(	"label"=>"txt-208-0"
									,	"width"=>15
									,	"css"=>array("text-align"=>"center"));//Estado
		
		

		$k=0;
		$body=array();
		while($reg = $req->fetch()){			

			$i=0;
			$body[$k]["cont"][$i]=array("label"=>$reg['ID_FACT']
									,	"css"=>array("text-align"=>"center")); //ID cotizacion
			$i++;			
			$body[$k]["cont"][$i]=array("label"=>$reg['OBS_FACT']
									,	"css"=>array("text-align"=>"center"));	//Observaciones
						
			$validar=$reg['VALIDAT_FACT']==0?"txt-220-0":"txt-219-0"; 
			$i++;			
			$body[$k]["cont"][$i]=array("label"=>$reg['FECHAF_FACT']
									,	"css"=>array("text-align"=>"center"));	//Fecha factura

			$i++;			
			$body[$k]["cont"][$i]=array("label"=>$reg['VPOINT_FACT']
									,	"css"=>array("text-align"=>"center"));	//Valor factura

			$i++;			
			$body[$k]["cont"][$i]=array("label"=>$validar
									,	"css"=>array("text-align"=>"center"));	//Estado
			$k++;
		}
		$tabla=PrintTablas($ops,$titulos,$body);
		$salidas=array_merge($salidas,$tabla);
		print_paginacion($salidas,$Total,$PagActual,$idMaxItem);	
	}
	/**Pestaña de Mensajes****/
	/*************************/ 	
	elseif($tinfo==5){
		$sWhere_id=$cnf==500?encrip_mysql('y_message.ID_USUARIO_E'):encrip_mysql('y_message.ID_USUARIO_R');	
		$sWhere=" WHERE $sWhere_id=:id ".sWhere(5080,$busc,$_PROYECTO);
		$s=$sqlCons[0][508].$sWhere; 
		$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute(); 
		$Total = $req->fetchColumn();
		$IniDato=($PagActual-1)*$MaxItems;
		/*TABLA*/
		$s=$sqlCons[0][508].$sWhere.$sqlOrder[0][508]." LIMIT $IniDato,$MaxItems";
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute(); 

		$ops=array('tipo'=>'tabla'
				,	'attr'=>array('width'=>'100%'));
		
		$k=0;
		$titulos[$k]["cont"][]=array(	"label"=>"txt-170-0"
									,	"width"=>10
									,	"css"=>array("text-align"=>"center"));//ID MSJ
		
		$titulos[$k]["cont"][]=array(	"label"=>"txt-188-0"
									,	"width"=>20
									,	"css"=>array("text-align"=>"center"));//Usuario envía 
		
		$titulos[$k]["cont"][]=array(	"label"=>"txt-189-0"
									,	"width"=>20
									,	"css"=>array("text-align"=>"center"));//Usuario recibe

		$titulos[$k]["cont"][]=array(	"label"=>"txt-107-0"
									,	"width"=>15
									,	"css"=>array("text-align"=>"center"));//Fecha del MSJ

		$titulos[$k]["cont"][]=array(	"label"=>"txt-115-0"
									,	"width"=>15
									,	"css"=>array("text-align"=>"center"));//Mensaje 
		
		

		$k=0;
		$body=array();
		while($reg = $req->fetch()){			

			$i=0;
			$body[$k]["cont"][$i]=array("label"=>$reg['ID_MSG']); //ID proyecto

			$i++;
			$body[$k]["cont"][$i]=array("label"=>imprimir($reg['NOMBRE_E']." ".$reg['APELLIDO_E']));//Usuario ENvía
			if($PermisosA[500]["P"]==1){			
				$body[$k]["cont"][$i]["link"]=2;
				$body[$k]["cont"][$i]["cod"]="md=".encrip($reg["ID_USUARIO_E"]).encrip(500,2);
				$body[$k]["cont"][$i]["pagina"]='/abstract';
			}
			$i++;
			$body[$k]["cont"][$i]=array("label"=>imprimir($reg['NOMBRE_U']." ".$reg['APELLIDO_U']));//Usuario Recibe
			
			if($PermisosA[500]["P"]==1){			
				$body[$k]["cont"][$i]["link"]=2;
				$body[$k]["cont"][$i]["cod"]="md=".encrip($reg["ID_USUARIO_U"]).encrip(500,2);
				$body[$k]["cont"][$i]["pagina"]='/abstract';
			}			
			
			
			$i++;			
			$body[$k]["cont"][$i]=array("label"=>$reg['FECHAS_MSG']
									,	"css"=>array("text-align"=>"center"));	//Fecha del MSJ

			$i++;			
			$body[$k]["cont"][$i]=array("label"=>$reg['MSG_TXT']
									,	"css"=>array("text-align"=>"center"));	//Mensaje
			
			$k++;
		}
		$tabla=PrintTablas($ops,$titulos,$body);
		$salidas=array_merge($salidas,$tabla);
		print_paginacion($salidas,$Total,$PagActual,$idMaxItem);	
	}
}
/****************************/
/****************************/
/********  Proyectos *******/
/****************************/
elseif($cnf==501){
	//Calificaciones 
	if($tinfo==0){
		$sWhere_id=encrip_mysql(' y_proyectos_calif.ID_PROY');	
		$sWhere=" WHERE $sWhere_id=:id ".sWhere(5010,$busc,$_PROYECTO);
		$s=$sqlCons[2][501].$sWhere; 
		$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute(); 
		$Total = $req->fetchColumn();
		$IniDato=($PagActual-1)*$MaxItems;
		/*TABLA*/
		$s=$sqlCons[2][501].$sWhere.$sqlOrder[2][501]." LIMIT $IniDato,$MaxItems";
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute(); 

		$ops=array('tipo'=>'tabla'
				,	'attr'=>array('width'=>'100%'));
		
		$k=0;

		$titulos[$k]["cont"][]=array(	"label"=>"txt-108-0"
									,	"width"=>20
									,	"css"=>array("text-align"=>"center"));//Usuario que califica
		$titulos[$k]["cont"][]=array(	"label"=>"txt-107-0"
									,	"width"=>20
									,	"css"=>array("text-align"=>"center"));//Fecha
		
		$titulos[$k]["cont"][]=array(	"label"=>"txt-152-0"
									,	"width"=>15
									,	"css"=>array("text-align"=>"center"));//Calificación 
		
		
		$k=0;
		$body=array();
		while($reg = $req->fetch()){			

			$i=0;
		
			$body[$k]["cont"][$i]=array("label"=>imprimir($reg['NOMBRE_U']." ".$reg['APELLIDO_U']));//Descripción
			if($PermisosA[500]["P"]==1){			
				$body[$k]["cont"][$i]["link"]=2;
				$body[$k]["cont"][$i]["cod"]="md=".encrip($reg["ID_USUARIO"]).encrip(500,2);
				$body[$k]["cont"][$i]["pagina"]='/abstract';
			}			
				
			$i++;			
			$body[$k]["cont"][$i]=array("label"=>$reg['FECHAS_CALIF']
									,	"css"=>array("text-align"=>"center"));	//Fecha

			$i++;			
			$body[$k]["cont"][$i]=array("label"=>$reg['VAL_CALIF']
									,	"css"=>array("text-align"=>"center"));	//Ciudad

			$k++;
		}
		$tabla=PrintTablas($ops,$titulos,$body);
		$salidas=array_merge($salidas,$tabla);
		print_paginacion($salidas,$Total,$PagActual,$idMaxItem);	
	}
	//Imagénes de la Proyecto
	elseif($tinfo==1){
		$sWhere_id=encrip_mysql('y_proyectos_fotos.ID_PROY');	
		$sWhere=" WHERE $sWhere_id=:id ".sWhere(5010,$busc,$_PROYECTO);	
		$s=$sqlCons[1][501].$sWhere; 
		$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";
		$req = $dbEmpresa->prepare($s); 
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->bindParam(':id', $id_sha);
		$req->execute(); 
		$Total = $req->fetchColumn();
		$IniDato=($PagActual-1)*$MaxItems;
		
		/*TABLA*/
		$s=$sqlCons[1][501].$sWhere.$sqlOrder[1][501]." LIMIT $IniDato,$MaxItems";
		
		$i=0;	
		$req = $dbEmpresa->prepare($s); 
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->bindParam(':id', $id_sha);
		$req->execute();
			
		while($reg = $req->fetch()){ 
			$salidas["nItem"][$i]=array();	
			$reg['OPCION']="SMPROF";
			json_Item($reg,$salidas["nItem"][$i],$cnf,$md,$_sysvars_r);
			$i++;	
		}

		$cargado=true;
		$salidas["barra"]=array();
			
		$k=0;
		$salidas["barra"][$k]=array();
		$salidas["barra"][$k]["agrupar"]=0;
		$salidas["barra"][$k]["contenido"]=array();
		
		$i=0;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_nuevo;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$nuevo_tag;
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/edit';
		
		$k++;
		$salidas["barra"][$k]=array();
		$salidas["barra"][$k]["agrupar"]=1;
		$salidas["barra"][$k]["contenido"]=array();
		
		$i=0;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_actualizar;
		print_paginacion($salidas,$Total,$PagActual,$idMaxItem);		
		
	}
}
/******************************/
/******************************/
/******** COTIZACIONES *******/
/******************************/
elseif($cnf==504){
	//Calificaciones 
	if($tinfo==0){
		$sWhere_id=encrip_mysql('y_cotizacion_items.ID_COTIZ');	
		$sWhere=" WHERE $sWhere_id=:id ".sWhere(5040,$busc,$_PROYECTO);
		$s=$sqlCons[1][504].$sWhere; 
		$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute(); 
		$Total = $req->fetchColumn();
		$IniDato=($PagActual-1)*$MaxItems;
		/*TABLA*/
		$s=$sqlCons[1][504].$sWhere.$sqlOrder[1][504]." LIMIT $IniDato,$MaxItems";
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute(); 

		$ops=array('tipo'=>'tabla'
				,	'attr'=>array('width'=>'100%'));
		
		$k=0;

		$titulos[$k]["cont"][]=array(	"label"=>"txt-170-0"
									,	"width"=>20
									,	"css"=>array("text-align"=>"center"));//ID_Item

		$titulos[$k]["cont"][]=array(	"label"=>"txt-101-0"
									,	"width"=>20
									,	"css"=>array("text-align"=>"center"));//Nombre_ITEM
		
		$titulos[$k]["cont"][]=array(	"label"=>"txt-202-0"
									,	"width"=>15
									,	"css"=>array("text-align"=>"center"));//Cantidad 

		$titulos[$k]["cont"][]=array(	"label"=>"txt-203-0"
									,	"width"=>15
									,	"css"=>array("text-align"=>"center"));//Precio
		
		$k=0;
		$body=array();
		while($reg = $req->fetch()){			

			$i=0;
					
			$body[$k]["cont"][$i]=array("label"=>$reg['ID_ITEM']
									,	"css"=>array("text-align"=>"center"));	//ID_Item

			$i++;			
			$body[$k]["cont"][$i]=array("label"=>$reg['NAME_ITEM']
									,	"css"=>array("text-align"=>"center"));	//Nombre_ITEM
			$i++;			
			$body[$k]["cont"][$i]=array("label"=>$reg['CANT_ITEM']
									,	"css"=>array("text-align"=>"center"));	//Cantidad 

			$i++;			
			$body[$k]["cont"][$i]=array("label"=>$reg['PREC_ITEM']
									,	"css"=>array("text-align"=>"center"));	//Precio

			$k++;
		}
		$tabla=PrintTablas($ops,$titulos,$body);
		$salidas=array_merge($salidas,$tabla);
		print_paginacion($salidas,$Total,$PagActual,$idMaxItem);	
	}	
}
?>
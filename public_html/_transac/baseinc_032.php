<?php
/*
---------------------------------------------------------------------------------
                        SUPERMAESTROS BACKOFFICE
                             Proyecto N° 32
                         Archivo: base_inc032.php
  Descripción: archivo de configuración de carga de objetos JSON y tablas de datos
--------------------------------------------------------------------------------
Este archivo configura la carga y llama a los objetos JSON, que muestran  al hacer click en cada uno de los módulos en el 
backofice la carga de la información se hace con este archivo.

La variable $cnf contiene el identificador del módulo que varía dependiendo del
módulo en el cual se está haciendo click en el backoffice, de este modo se 
reconoce que módulo se necesita cargar.

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

Funcionamiento del base_inc
-----------------------------

El archivo base_inc llama a los objetos JSON para aquellos módulos que se 
declaran como objetos, y para los que son tablas (véase la sección de facturas como ejemplo ) 
se listan directamente en el archivo. Cada objeto se llena de información con el archivo
json_item_32.php
/***************************************/
/*********** Usuarios ******************/
/***************************************/
/* Ejemplo de un módulo tipo objeto JSON */
if($cnf==500){
	if($scnf==1){
		$filusu=$_GET['fl-filusu']==''?0:$_GET['fl-filusu'];

		$sWhere=" WHERE ";
		if($filusu==1)		$sWhere.=' x_usuario.TYPE_USUARIO!=1 AND ';
		elseif($filusu==2)	$sWhere.=' x_usuario.TYPE_USUARIO=1 AND '; 

		if($t==2)		$sWhere.=" adm_usuarios.HAB_U=0 "; // Aca usamos la propiedad del campo habilitador, para
		elseif($t==3)	$sWhere.=" adm_usuarios.HAB_U=1 "; // filtrar en el botón de ver todos, o los eliminados
														   //HAB=0 (habilitado) HAB=1 (eliminado)	

		$sWhere.=sWhere(5000,$busc,$_PROYECTO);	
		$s=$sqlCons[0][500].$sWhere; //llamamos la consulta del módulo 500 (usuarios)
		$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";
		$req = $dbEmpresa->prepare($s); 
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute(); 
		$Total = $req->fetchColumn();
		$IniDato=($PagActual-1)*$MaxItems;
		
		/*TABLA*/
		$s=$sqlCons[0][500].$sWhere.$sqlOrder[0][500]." LIMIT $IniDato,$MaxItems";
		
		$i=0;	
		$req = $dbEmpresa->prepare($s); 
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute();
			
		while($reg = $req->fetch()){
			$salidas["nItem"][$i]=array();	
			$reg['OPCION']="SMUSU"; //Se llama al objeto JSON, la nomeclantura para cada objeto
									//se especifica en el archivo de consultas_032.php
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
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3; //botón de editar el objeto
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_nuevo;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$nuevo_tag;
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/edit';
		
		$k++;
		$salidas["barra"][$k]=array();
		$salidas["barra"][$k]["agrupar"]=1;
		$salidas["barra"][$k]["id"]="sBarra-".$k."-".$cnf;
		$salidas["barra"][$k]["contenido"]=array();
		
		$i=0;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=2;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_habdes_activos;
		
		$i++;	
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_habdes_inactivos;	

		$k++;
		$salidas["barra"][$k]=array();
		$salidas["barra"][$k]["agrupar"]=0;
		$salidas["barra"][$k]["contenido"]=array();
		$salidas["barra"][$k]["class"]='combo';


		$opciones=array(array(	"value"	=>0
        				,	"label"	=>'txt-234-1'
        				,	'fl'	=>array('filusu'=>0))
					,	array(	"value"	=>1
        				,	"label"	=>'txt-235-1'
        				,	'fl'	=>array('filusu'=>1))
					,	array(	"value"	=>2
        				,	"label"	=>'txt-236-1'
        				,	'fl'	=>array('filusu'=>2)));

		$i=0;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;		
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['tpb']=11;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['value']=$filusu;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['options']=$opciones;	


		print_paginacion($salidas,$Total,$PagActual,$idMaxItem);	
	}	
}
/***************************************/
/******** Remodelaciones ***************/
/***************************************/
elseif($cnf==501){
	if($scnf==1){
		$sWhere=" WHERE ";
		if($t==2)		$sWhere.=" y_proyectos.HAB_PROY=0 ";
		elseif($t==3)	$sWhere.=" y_proyectos.HAB_PROY=1 ";	
		$sWhere.=sWhere(5010,$busc,$_PROYECTO);	
		$s=$sqlCons[0][501].$sWhere; 
		$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";
		$req = $dbEmpresa->prepare($s); 
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute(); 
		$Total = $req->fetchColumn();
		$IniDato=($PagActual-1)*$MaxItems;
		
		/*TABLA*/
		$s=$sqlCons[0][501].$sWhere.$sqlOrder[0][501]." LIMIT $IniDato,$MaxItems";
		
		$i=0;	
		$req = $dbEmpresa->prepare($s); 
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute();
			
		while($reg = $req->fetch()){
			$salidas["nItem"][$i]=array();	
			$reg['OPCION']="SMPRO";
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
/***************************************/
/*********** Proyectos *****************/
/***************************************/
elseif($cnf==502){
	if($scnf==1){
		$sWhere=" WHERE ";
		if($t==2)		$sWhere.=" x_ofertas.HAB_OFERTA=0 ";
		elseif($t==3)	$sWhere.=" x_ofertas.HAB_OFERTA=1 ";	
		$sWhere.=sWhere(5020,$busc,$_PROYECTO);	
		$s=$sqlCons[0][502].$sWhere; 
		$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";
		$req = $dbEmpresa->prepare($s); 
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute(); 
		$Total = $req->fetchColumn();
		$IniDato=($PagActual-1)*$MaxItems;
		
		/*TABLA*/
		$s=$sqlCons[0][502].$sWhere.$sqlOrder[0][502]." LIMIT $IniDato,$MaxItems";
		
		$i=0;	
		$req = $dbEmpresa->prepare($s); 
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute();
			
		while($reg = $req->fetch()){
			$salidas["nItem"][$i]=array();	
			$reg['OPCION']="SMOFER";
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
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_actualizar;
		print_paginacion($salidas,$Total,$PagActual,$idMaxItem);	
	}	
}
/***************************************/
/*********** Facturas ******************/
/***************************************/
/* Este es el ejemplo de un módulo que no es definido como 
objeto sino como una tabla, la distribución del código varía */
elseif($cnf==503){
	if($scnf==1){
		$sWhere=' ';
		if($busc!=''){
			$sWhere=" WHERE ";
			$sWhere.=sWhere(5030,$busc,$_PROYECTO);	
		}
		$s=$sqlCons[0][503].$sWhere; 
		$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";
		$req = $dbEmpresa->prepare($s); 
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute(); 
		$Total = $req->fetchColumn();
		$IniDato=($PagActual-1)*$MaxItems;
		
		/*TABLA*/
		$s=$sqlCons[0][503].$sWhere.$sqlOrder[0][503]." LIMIT $IniDato,$MaxItems";
		
		$i=0;	
		$req = $dbEmpresa->prepare($s); 
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute();

		// Encabezados y títulos de los campos de la tabla
		$ops=array('tipo'=>'tabla'
				,	'attr'=>array('width'=>'100%'));
		$k=0;
		$titulos[$k]["cont"][]=array(	"label"=>"txt-170-0"
									,	"width"=>5
									,	"css"=>array("text-align"=>"center"));// ID

		$titulos[$k]["cont"][]=array(	"label"=>"txt-100-0"
									,	"width"=>20
									,	"css"=>array("text-align"=>"center"));//Instalador

		$titulos[$k]["cont"][]=array(	"label"=>"txt-107-0"
									,	"width"=>5
									,	"css"=>array("text-align"=>"center"));//Fecha

		$titulos[$k]["cont"][]=array(	"label"=>"txt-171-1"
									,	"width"=>20
									,	"css"=>array("text-align"=>"center"));//Observaciones
		$titulos[$k]["cont"][]=array(	"label"=>"txt-152-0"
									,	"width"=>5
									,	"css"=>array("text-align"=>"center"));//Valor

		$titulos[$k]["cont"][]=array(	"label"=>"txt-208-0"
									,	"width"=>5
									,	"css"=>array("text-align"=>"center"));//status de validacion 

		$titulos[$k]["cont"][]=array(	"label"=>"txt-229-0"
									,	"width"=>10
									,	"css"=>array("text-align"=>"center"));//Ver

		$titulos[$k]["cont"][]=array(	"label"=>"txt-217-0"
									,	"width"=>10
									,	"css"=>array("text-align"=>"center"));//Validar

		$titulos[$k]["cont"][]=array(	"label"=>"txt-218-0"
									,	"width"=>10
									,	"css"=>array("text-align"=>"center"));//Invalidar

		$titulos[$k]["cont"][]=array(	"label"=>"txt-173-0"
									,	"width"=>10
									,	"css"=>array("text-align"=>"center"));//Eliminar

		$k=0;	
		$body=array();
		//Si la consulta es exitosa listamos los resultados en cada celda de acuerdo 
		//a los campos
		while($reg = $req->fetch()){			
			$i=0;		
			$body[$k]["cont"][$i]=array("label"=>$reg['ID_FACT']
									,	"css"=>array("text-align"=>"center"));//ID
			$i++;
			
			$body[$k]["cont"][$i]=array("label"=>imprimir($reg['NOMBRE_U_OP']).' '.imprimir($reg['APELLIDO_U_OP']));//Usuario
			if($PermisosA[500]["P"]==1){			
				$body[$k]["cont"][$i]["link"]=2;
				$body[$k]["cont"][$i]["cod"]="md=".encrip($reg["ID_USUARIO"]).encrip(500,2);
				$body[$k]["cont"][$i]["pagina"]='/abstract';
			}			
			$id_sha=encrip($reg["ID_FACT"]);
			$id_sha2=encrip($reg["ID_FILE"]);
			$eliminar_a=$id_sha.$c_sha.$acc01;
			$eliminar_c='txt-1005-0';
			$md_n=nuevo_item().$c_sha;
			$md=$id_sha.$c_sha;		
			$validar=$reg['VALIDAT_FACT']==0?"txt-220-0":"txt-219-0"; 

			$i++;			
			$body[$k]["cont"][$i]=array("label"=>$reg['FECHAF_FACT']
									,	"css"=>array("text-align"=>"center"));//Fecha
			
			$i++;			
			$body[$k]["cont"][$i]=array("label"=>$reg['OBS_FACT']
									,	"css"=>array("text-align"=>"center"));//Observaciones
			$i++;
			$body[$k]["cont"][$i]=array("label"=>$reg['VPOINT_FACT']
									,	"css"=>array("text-align"=>"center"));//valor

			$i++;
			$body[$k]["cont"][$i]=array("label"=>$validar
									,	"css"=>array("text-align"=>"center"));//estado de la factura

			$i++;
			$body[$k]["cont"][$i]=array("link"	=>	3
												,	"label"	=>	'txt-229-0'
												,	"pagina"=>	'http:'.$_PARAMETROS["S3_URL4"].ImgName($_PROYECTO,$_EMPRESA,503,$reg["ID_FACT"],'img',$reg["F_EXT"],false,'big')
												,	"tipo"	=>	'button'
												,	"data"	=>	array("dinamic"=>"true","target"=>"_blank")
												,	"icon"	=>	"fa-file-picture-o");
			
			$i++;
			$body[$k]["cont"][$i]=array("link"	=>	1
												,	"label"	=>	'txt-217-0'
												,	"cod"	=>	"md=".$md.$id_sha.'001' //boton validar
												,	"pagina"=>	'/operation'
												,	"tipo"	=>	'button'
												,	"icon"	=>	"fa-check-circle");

			$i++;
			$body[$k]["cont"][$i]=array("link"	=>	1
												,	"label"	=>	'txt-218-0'
												,	"cod"	=>	"md=".$md.$id_sha.'002' //boton invalidar
												,	"pagina"=>	'/operation'
												,	"tipo"	=>	'button'
												,	"icon"	=>	"fa-minus-circle");
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
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_actualizar;
		print_paginacion($salidas,$Total,$PagActual,$idMaxItem);	
	}	
}
/********************************************/
/*********** Cotizaciones *******************/
/********************************************/
elseif($cnf==504){
	if($scnf==1){
		$sWhere=" WHERE ";
		if($t==2)		$sWhere.=" y_cotizacion.STATUS_COTIZ=0 ";
		elseif($t==3)	$sWhere.=" y_cotizacion.STATUS_COTIZ=1 ";	

		$sWhere.=sWhere(5040,$busc,$_PROYECTO);	
		$s=$sqlCons[0][504].$sWhere; 
		$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";
		$req = $dbEmpresa->prepare($s); 
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute(); 
		$Total = $req->fetchColumn();
		$IniDato=($PagActual-1)*$MaxItems;
		
		/*TABLA*/
		$s=$sqlCons[0][504].$sWhere.$sqlOrder[0][504]." LIMIT $IniDato,$MaxItems";
		
		$i=0;	
		$req = $dbEmpresa->prepare($s); 
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute();
			
		while($reg = $req->fetch()){
			$salidas["nItem"][$i]=array();	
			$reg['OPCION']="SMCOTI";
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
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_actualizar;
		print_paginacion($salidas,$Total,$PagActual,$idMaxItem);	
	}	
}
/***************************************/
/*********** ECUENTA *******************/
/***************************************/
elseif($cnf==505){
	if($scnf==1){
		$sWhere=' ';

		if($busc!=''){
			$sWhere=" WHERE ";
			$sWhere.=sWhere(5050,$busc,$_PROYECTO);	
		}
		$s=$sqlCons[0][505].$sWhere; 
		$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";
		$req = $dbEmpresa->prepare($s); 
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute(); 
		$Total = $req->fetchColumn();
		$IniDato=($PagActual-1)*$MaxItems;
		
		/*TABLA*/
		$s=$sqlCons[0][505].$sWhere.$sqlOrder[0][505]." LIMIT $IniDato,$MaxItems";
		
		$i=0;	
		$req = $dbEmpresa->prepare($s); 
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
									,	"width"=>10
									,	"css"=>array("text-align"=>"center"));//Fecha
		$titulos[$k]["cont"][]=array(	"label"=>"txt-100-0"
									,	"width"=>15
									,	"css"=>array("text-align"=>"center"));//Instalador
		$titulos[$k]["cont"][]=array(	"label"=>"txt-201-0"
									,	"width"=>20
									,	"css"=>array("text-align"=>"center"));//Comentario
		$titulos[$k]["cont"][]=array(	"label"=>"txt-222-0"
									,	"width"=>10
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
			$body[$k]["cont"][$i]=array("label"=>$reg['NOMBRE_U_M'].' '.$reg['APELLIDO_U_M'] 
									,	"css"=>array("text-align"=>"center"));  //Instalador
			if($PermisosA[500]["P"]==1){			
				$body[$k]["cont"][$i]["link"]=2;
				$body[$k]["cont"][$i]["cod"]="md=".encrip($reg["ID_USUARIO_M"]).encrip(500,2);
				$body[$k]["cont"][$i]["pagina"]='/abstract';
			}			

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
}
/********************************************/
/*********** Noticias ***********************/
/********************************************/
elseif($cnf==506){
	if($scnf==1){
		$sWhere=" WHERE ";
		if($t==2)		$sWhere.=" y_noti.HAB_NOTI=0 ";
		elseif($t==3)	$sWhere.=" y_noti.HAB_NOTI=1 ";	

		$sWhere.=sWhere(5060,$busc,$_PROYECTO);	
		$s=$sqlCons[0][506].$sWhere; 
		$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";
		$req = $dbEmpresa->prepare($s); 
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute(); 
		$Total = $req->fetchColumn();
		$IniDato=($PagActual-1)*$MaxItems;
		
		/*TABLA*/
		$s=$sqlCons[0][506].$sWhere.$sqlOrder[0][506]." LIMIT $IniDato,$MaxItems";
		
		$i=0;	
		$req = $dbEmpresa->prepare($s); 
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute();
			
		while($reg = $req->fetch()){
			$salidas["nItem"][$i]=array();	
			$reg['OPCION']="SMNOTI";
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
/********************************************/
/*********** Publicidad *********************/
/********************************************/
elseif($cnf==507){
	if($scnf==1){
		$sWhere=" WHERE ";
		if($t==2)		$sWhere.=" y_publicidad.HAB_PUBL=0 ";
		elseif($t==3)	$sWhere.=" y_publicidad.HAB_PUBL=1 ";
		$sWhere.=sWhere(5070,$busc,$_PROYECTO);	
		$s=$sqlCons[0][507].$sWhere; 
		$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";
		$req = $dbEmpresa->prepare($s); 
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute(); 
		$Total = $req->fetchColumn();
		$IniDato=($PagActual-1)*$MaxItems;
		
		/*TABLA*/
		$s=$sqlCons[0][507].$sWhere.$sqlOrder[0][507]." LIMIT $IniDato,$MaxItems";
		
		$i=0;	
		$req = $dbEmpresa->prepare($s); 
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute();
			
		while($reg = $req->fetch()){
			$salidas["nItem"][$i]=array();	
			$reg['OPCION']="SMPUBL";
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
/***************************************/
/*********** MSJ **********************/
/***************************************/
elseif($cnf==508){
	if($scnf==1){
		$sWhere=' ';

		if($busc!=''){
			$sWhere=" WHERE ";
			$sWhere.=sWhere(5080,$busc,$_PROYECTO);	
		}
		$s=$sqlCons[0][508].$sWhere; 
		$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";
		$req = $dbEmpresa->prepare($s); 
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute(); 
		$Total = $req->fetchColumn();
		$IniDato=($PagActual-1)*$MaxItems;
		
		/*TABLA*/
		$s=$sqlCons[0][508].$sWhere.$sqlOrder[0][508]." LIMIT $IniDato,$MaxItems";
		
		$i=0;	
		$req = $dbEmpresa->prepare($s); 
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute();


		$ops=array('tipo'=>'tabla'
				,	'attr'=>array('width'=>'100%'));
		$k=0;
		$titulos[$k]["cont"][]=array(	"label"=>"txt-170-0"
									,	"width"=>10
									,	"css"=>array("text-align"=>"center"));// ID
		$titulos[$k]["cont"][]=array(	"label"=>"txt-185-0"
									,	"width"=>15
									,	"css"=>array("text-align"=>"center"));//Envía
		$titulos[$k]["cont"][]=array(	"label"=>"txt-186-0"
									,	"width"=>15
									,	"css"=>array("text-align"=>"center"));//Recibe

		$titulos[$k]["cont"][]=array(	"label"=>"txt-115-0"
									,	"width"=>45
									,	"css"=>array("text-align"=>"center"));//Mensaje

		$titulos[$k]["cont"][]=array(	"label"=>"txt-107-0"
									,	"width"=>15
									,	"css"=>array("text-align"=>"center"));//Fecha 

		$k=0;	
		$body=array();
		while($reg = $req->fetch()){			

			$i=0;		
			$body[$k]["cont"][$i]=array("label"=>$reg['ID_MSG']
									,	"css"=>array("text-align"=>"center"));//ID
			$i++;
			
			$body[$k]["cont"][$i]=array("label"=>imprimir($reg['NOMBRE_E']).' '.imprimir($reg['APELLIDO_E']));//Usuario Envía 
			if($PermisosA[500]["P"]==1){			
				$body[$k]["cont"][$i]["link"]=2;
				$body[$k]["cont"][$i]["cod"]="md=".encrip($reg["ID_USUARIO_E"]).encrip(500,2);
				$body[$k]["cont"][$i]["pagina"]='/abstract';
			}			
			
			$i++;	
			$body[$k]["cont"][$i]=array("label"=>imprimir($reg['NOMBRE_U']).' '.imprimir($reg['APELLIDO_U']));//Usuario Recibe 
			if($PermisosA[500]["P"]==1){			
				$body[$k]["cont"][$i]["link"]=2;
				$body[$k]["cont"][$i]["cod"]="md=".encrip($reg["ID_USUARIO_U"]).encrip(500,2);
				$body[$k]["cont"][$i]["pagina"]='/abstract';
			}			
			$i++;
			$body[$k]["cont"][$i]=array("label"=>$reg['MSG_TXT']
									,	"css"=>array("text-align"=>"center"));//Mensaje
			$i++;
			$body[$k]["cont"][$i]=array("label"=>$reg['FECHAS_MSG']
									,	"css"=>array("text-align"=>"center"));//Fecha			
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
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_actualizar;
		print_paginacion($salidas,$Total,$PagActual,$idMaxItem);	
	}	
}
/***************************************/
/*********** ESPECIALIDADES ************/
/***************************************/
elseif($cnf==509){
	if($scnf==1){
		$sWhere=" WHERE ";
		if($t==2)		$sWhere.=" z_espec.HAB_ESPEC=0 ";
		elseif($t==3)	$sWhere.=" z_espec.HAB_ESPEC=1 ";
		$s=$sqlCons[0][509].$sWhere; 
		$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";
		$req = $dbEmpresa->prepare($s); 
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute(); 
		$Total = $req->fetchColumn();
		$IniDato=($PagActual-1)*$MaxItems;
		
		/*TABLA*/
		$s=$sqlCons[0][509].$sWhere.$sqlOrder[0][509]." LIMIT $IniDato,$MaxItems";
		
		$i=0;	
		$req = $dbEmpresa->prepare($s); 
		if($_GET["busc"]!='') 	$req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->execute();


		$ops=array('tipo'=>'tabla'
				,	'attr'=>array('width'=>'100%'));
		$k=0;
		$titulos[$k]["cont"][]=array(	"label"=>"txt-162-0"
									,	"width"=>40
									,	"css"=>array("text-align"=>"center"));// Especialidad
		$titulos[$k]["cont"][]=array(	"label"=>"txt-169-0"
									,	"width"=>25
									,	"css"=>array("text-align"=>"center"));//Editar
		$titulos[$k]["cont"][]=array(	"label"=>"txt-173-0"
									,	"width"=>25
									,	"css"=>array("text-align"=>"center"));//Eliminar
		$k=0;	
		$body=array();
		while($reg = $req->fetch()){
			$id_sha=encrip($reg["ID_ESPEC"]);
			if($reg["HAB_ESPEC"]==0){
				$eliminar_a=$id_sha.$c_sha.$acc01;
				$eliminar_c='txt-1005-0';
			}
			else{
				$eliminar_a=$id_sha.$c_sha.$acc02;
				$eliminar_c='txt-1010-0';
			}			
			$md_forze=$id_sha.$c_sha;
			$i=0;		
			$body[$k]["cont"][$i]=array("label"=>$reg['NAME_ESPEC']
									,	"css"=>array("text-align"=>"center"));//Especialidad
			$i++;
			
			$body[$k]["cont"][$i]=array("link"	=>	1
												,	"label"	=>	'txt-169-0'
												,	"cod"	=>	"md=".$md_forze//boton editar
												,	"pagina"=>	'/edit'
												,	"tipo"	=>	'button'
												,	"icon"	=>	"fa-edit");
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
}
?>
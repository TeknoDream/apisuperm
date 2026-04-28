<?php  
/*
---------------------------------------------------------------------------------
                        SUPERMAESTROS BACKOFFICE
                             Proyecto N° 32
                        Archivo: json_item_032.php
             Descripción: archivo de configuración de objetos JSON
--------------------------------------------------------------------------------
Este archivo lista la información que llena el objeto JSON que es llamado
en el archivo base_inc_032.php, cada objeto se identifica con la nomeclantura
que previamente se declaró en el archivos de consultas_32.php.
De igual manera cada objeto JSON se corresponde con el número de módulo.

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


/***************************************/
/******** Usuario ********************/
/***************************************/
if($reg["OPCION"]=="SMUSU"){ //Identificamos si el objeto que se está llamando en el base_inc_32 coincide
	$id_sha=encrip($reg["ID_USUARIO"]);
	$c_sha=encrip(500,2);
	$md_n=nuevo_item().$c_sha;
	$md=$id_sha.$c_sha;
	
	if($reg["HAB_U"]==0){ // Si el objeto está habilitado, se muestra la opción de eliminar, de lo contrario
						 //el botón de recuperar
		$eliminar_a=$id_sha.$c_sha.$acc01;
		$eliminar_c=$btn_borrar;
	}
	else{
		$eliminar_a=$id_sha.$c_sha.$acc02;
		$eliminar_c=$btn_recuperar;
	}

	$salidas["idsha"]=$id_sha;
	$salidas["tipobox"]=3;
	$salidas["deshab"]=$reg["HAB_U"];	

	//Agregamos la imagen para el objeto
	$permiso=$PermisosA[500]["P"];		

	$ArrayImg["OBJETO"]=$reg["ID_USUARIO"];
	$ArrayImg["MODULO"]=36;
	$salidas["imagen"]=ImgBlanc($reg["M_IMG"],$ArrayImg);
	
	$salidas["titulo"]["data"]=imprimir($reg["NOMBRE_U"]).' '.imprimir($reg["APELLIDO_U"]);
	if($permiso==1){
		$salidas["titulo"]["link"]=2;				//Creamos un enlace que permita ir directamente
		$salidas["titulo"]["cod"]="md=".$md;		//a la información del usuario que se está listando
		$salidas["titulo"]["pagina"]='/abstract';	//en el objeto
	}
	$salidas["subtitulo"]=imprimir($reg["CORREO_U"]);
	
	if($permiso==1){
		$salidas["barra"]=array();		
		$k=0;
		$salidas["barra"][$k]=array();
		$salidas["barra"][$k]["contenido"]=array();
		
		$i=0;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3; //´botón de editar
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_editar;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$md;
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/edit';
		
		$i++;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3; //botón de eliminar
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$eliminar_c;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$eliminar_a;
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/delete';

		/*Aca clasificamos al usuario que es instalador, entre AUTORIZADO
		Y NO AUTORIZADO, VERIF_USUARIO 0=no autorizado, 1=autorizado */
		if($reg['TYPE_USUARIO']==1&&$reg['VERIF_USUARIO']==0){
			$k++;
			$salidas["barra"][$k]=array();
			$salidas["barra"][$k]["contenido"]=array();
			// Si no está autorizado se muestra la opción de darle autorización
			$i=0;
			$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
			$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['clase'][0]="botones_def";
			$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['label']='txt-210-0';//autorizar
			$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['title']='txt-148-1';
			$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['icons']="fa-check-circle ";
			$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['tpb']=6;
			$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['transictp']=1;
			$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$md.$id_sha.'001';
			$salidas["barra"][$k]["contenido"][$i]["pagina"]='/operation';
		}
		elseif($reg['TYPE_USUARIO']==1&&$reg['VERIF_USUARIO']==1){
			$k++;
			$salidas["barra"][$k]=array();
			$salidas["barra"][$k]["contenido"]=array();
			// Si está autorizado se muestra la opción para quitarle la autorización
			$i=0;
			$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
			$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['clase'][0]="botones_def";
			$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['label']='txt-211-0';//desautorizar
			$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['title']='txt-151-1';
			$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['icons']="fa-minus-circle ";
			$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['tpb']=6;
			$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['transictp']=1;
			$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$md.$id_sha.'002';
			$salidas["barra"][$k]["contenido"][$i]["pagina"]='/operation';
		}

		$i++;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['clase'][0]="botones_def";
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['label']='txt-233-0'; 	//FOrzar cambio de Contraseña
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['title']='txt-233-1';
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['text']=false;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['icons']="fa-key";
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['tpb']=6;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['transictp']=1;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$id_sha.encrip(10000,2).$id_sha.'001';
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/operation';

	}	
	/*DATOS LATERAL*/
	$salidas["info"]=array();
	$i=0;
	$salidas["info"][$i]["desc"]='txt-174-0';
	$salidas["info"][$i]["data"]=imprimir($reg["TEL1_USUARIO"]);
	
	$i++;
	$salidas["info"][$i]["desc"]='txt-175-0';
	$salidas["info"][$i]["data"]=imprimir($reg["TEL2_USUARIO"]);
	
		
	$k=0;
	$salidas["contadores"]=array();			
	$salidas["contadores"][$k]["contenido"]=array();
	
	$i=0;
	if($reg["TYPE_USUARIO"]==1)	{		
		$salidas["contadores"][$k]["contenido"][$i]["desc"]='txt-104-0';
		$salidas["contadores"][$k]["contenido"][$i]["data"]=$reg["POINTS_USUARIO"];
		$salidas["contadores"][$k]["contenido"][$i]["tipo"]=2;
		$i++;
		$salidas["contadores"][$k]["contenido"][$i]["desc"]='txt-105-0';
		$salidas["contadores"][$k]["contenido"][$i]["data"]=$reg["VCALIF_USUARIO"];
		$salidas["contadores"][$k]["contenido"][$i]["tipo"]=2;
		$i++;
		$salidas["info"][$i]["desc"]='txt-178-0';
		$salidas["info"][$i]["data"]=imprimir($reg["BIO_USUARIO"]);

		$i++;		
		$salidas["contadores"][$k]["contenido"][$i]["desc"]='txt-208-0';
		$salidas["contadores"][$k]["contenido"][$i]["data"]=$reg["VERIF_USUARIO"]==1?'txt-208-1':'txt-209-1';
		$salidas["contadores"][$k]["contenido"][$i]["tipo"]=2;

	}
	$i++;
	$salidas["contadores"][$k]["contenido"][$i]["desc"]='txt-177-0';
	if($reg["TYPE_USUARIO"]==0)		
	$salidas["contadores"][$k]["contenido"][$i]["data"]='txt-176-0';
	elseif($reg["TYPE_USUARIO"]==1)	$salidas["contadores"][$k]["contenido"][$i]["data"]='txt-100-0';
	$salidas["contadores"][$k]["contenido"][$i]["tipo"]=2;

	$i++;
	$salidas["info"][$i]["desc"]='txt-144-0';
	$salidas["info"][$i]["data"]=imprimir($reg["FECHA_U"]);
	
}
/***************************************/
/******** Remodelaciones****************/
/***************************************/
elseif($reg["OPCION"]=="SMPRO"){
	$id_sha=encrip($reg["ID_PROY"]);
	
	$c_sha=encrip(501,2);
	$md_n=nuevo_item().$c_sha;
	$md=$id_sha.$c_sha;
	
	if($reg["HAB_PROY"]==0){
		$eliminar_a=$id_sha.$c_sha.$acc01;
		$eliminar_c=$btn_borrar;
	}
	else{
		$eliminar_a=$id_sha.$c_sha.$acc02;
		$eliminar_c=$btn_recuperar;
	}

	$salidas["idsha"]=$id_sha;
	$salidas["tipobox"]=3;
	$salidas["deshab"]=$reg["HAB_PROY"];	

	$permiso=$PermisosA[501]["P"];		

	$ArrayImg["OBJETO"]=$reg["ID_FOTO"];
	$ArrayImg["MODULO"]=501;
	$salidas["imagen"]=ImgBlanc($reg["M_IMG"],$ArrayImg);
	
	$salidas["titulo"]["data"]=imprimir($reg["NOMB_PROY"]);
	if($permiso==1){
		$salidas["titulo"]["link"]=2;
		$salidas["titulo"]["cod"]="md=".$md;
		$salidas["titulo"]["pagina"]='/abstract';
	}
	
	if($permiso==1){
		$salidas["barra"]=array();		
		$k=0;
		$salidas["barra"][$k]=array();
		$salidas["barra"][$k]["contenido"]=array();
		
		$i=0;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_editar;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$md;
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/edit';
		
		$i++;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$eliminar_c;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$eliminar_a;
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/delete';
		/* Habilitamos la opción de hacer visible u ocultar el proyecto
		Para eso se modifica la propiedad del campo STATUS_PROY 
		STATUS_PROY=0 : el proyecto no está visible
		STATUS_PROY=1 : el proyecto esta visible
		*/ 
		if($reg['STATUS_PROY']==0){
			$k++;
			$salidas["barra"][$k]=array();
			$salidas["barra"][$k]["contenido"]=array();
			//Si el proyecto no está visible se habilita el botón para cambiar su estado a visible
			$i=0;
			$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
			$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['clase'][0]="botones_def";
			$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['label']='txt-226-0';//mostrar proyecto
			$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['title']='txt-148-1';
			$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['icons']="fa-check-circle ";
			$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['tpb']=6;
			$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['transictp']=1;
			$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$md.$id_sha.'002';
			$salidas["barra"][$k]["contenido"][$i]["pagina"]='/operation';
		}
		//Si el proyecto está visible se habilita el botón para cambiar su estado a oculto
		elseif($reg['STATUS_PROY']==1){
			$k++;
			$salidas["barra"][$k]=array();
			$salidas["barra"][$k]["contenido"]=array();

			$i=0;
			$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
			$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['clase'][0]="botones_def";
			$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['label']='txt-226-1';//ocultar proyecto
			$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['title']='txt-151-1';
			$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['icons']="fa-minus-circle ";
			$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['tpb']=6;
			$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['transictp']=1;
			$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$md.$id_sha.'003';
			$salidas["barra"][$k]["contenido"][$i]["pagina"]='/operation';
		}
		$k++;
		$salidas["barra"][$k]=array();
		$salidas["barra"][$k]["contenido"]=array();

		$i=0;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['label']='txt-153-0';
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['title']='txt-153-1';
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['icons']="fa-camera-retro";
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['transictp']=1;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".nuevo_item().encrip(501,2).$id_sha.'001'; // Fotos
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/operation';
	}	
	/*DATOS LATERAL*/
	$salidas["info"]=array();	
	$i=0;
	$salidas["info"][$i]["desc"]='txt-103-0';
	$salidas["info"][$i]["data"]=imprimir($reg["DESC_PROY"]);
	
	$i++;
	$salidas["info"][$i]["desc"]='txt-179-0';
	$salidas["info"][$i]["data"]=imprimir($reg["FECHAS_PROY"]);
		
	$k=0;
	$salidas["contadores"]=array();			
	$salidas["contadores"][$k]["contenido"]=array();
	
	$i=0;		
	$salidas["contadores"][$k]["contenido"][$i]["desc"]='txt-105-0';
	$salidas["contadores"][$k]["contenido"][$i]["data"]=$reg["VCALIF_PROY"];
	$salidas["contadores"][$k]["contenido"][$i]["tipo"]=2;
	$i++;
	$salidas["contadores"][$k]["contenido"][$i]["desc"]='txt-100-0';
	$salidas["contadores"][$k]["contenido"][$i]["data"]=$reg["NOMBRE_U"]." ".$reg["APELLIDO_U"];
	$salidas["contadores"][$k]["contenido"][$i]["tipo"]=2;
	$i++;		
	$salidas["contadores"][$k]["contenido"][$i]["desc"]='txt-208-0';
	$salidas["contadores"][$k]["contenido"][$i]["data"]=$reg["STATUS_PROY"]==1?'txt-229-0':'txt-230-0';
	$salidas["contadores"][$k]["contenido"][$i]["tipo"]=2;
		
}
/****************************************/
/***** Remodelación Imagenes*************/
/****************************************/
elseif($reg["OPCION"]=="SMPROF"){
	$id_sha=encrip($reg["ID_FOTO"]);

	$eliminar_a=$id_sha.$c_sha.$acc03;
	$eliminar_c=$btn_borrar;
	
	$c_sha=encrip(501,2);
	$md_n=nuevo_item().$c_sha;
	$md=$id_sha.$c_sha;

	$permiso=$PermisosA[501]["P"];		


	$salidas["idsha"]=$id_sha;
	$salidas["tipobox"]=3;
	$salidas["deshab"]=0;	

	$ArrayImg["OBJETO"]=$reg["ID_FOTO"];
	$ArrayImg["MODULO"]=501;
	$salidas["imagen"]=ImgBlanc($reg["M_IMG"],$ArrayImg);
	

	$salidas["titulo"]=imprimir($reg["TITLE_FOTO"]);
		
	if($permiso==1){
		$salidas["barra"]=array();		
		$k=0;
		$salidas["barra"][$k]=array();
		$salidas["barra"][$k]["contenido"]=array();
		
		$i=0;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_editar;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$md.nuevo_item().'001';
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/operation';
		
		$i++;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$eliminar_c;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$eliminar_a;
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/delete';

	}
	/*DATOS LATERAL*/
	$salidas["info"]=array();	
	$i=0;
	$salidas["info"][$i]["desc"]='txt-179-0';
	$salidas["info"][$i]["data"]=imprimir($reg["FECHAS_FOTO"]);				
}
/***************************************/
/******** Proyectos ********************/
/***************************************/
elseif($reg["OPCION"]=="SMOFER"){
	$id_sha=encrip($reg["ID_OFERTA"]);
	$c_sha=encrip(502,2);
	$md_n=nuevo_item().$c_sha;
	$md=$id_sha.$c_sha;
	
	if($reg["HAB_OFERTA"]==0){
		$eliminar_a=$id_sha.$c_sha.$acc01;
		$eliminar_c=$btn_borrar;
	}
	else{
		$eliminar_a=$id_sha.$c_sha.$acc02;
		$eliminar_c=$btn_recuperar;
	}

	$permiso=$PermisosA[502]["P"];		

	$salidas["idsha"]=$id_sha;
	$salidas["tipobox"]=3;
	$salidas["deshab"]=$reg["HAB_OFERTA"];		
	
	$salidas["titulo"]=imprimir($reg["TITLE_OFERTA"]);

	if($permiso==1){
		$salidas["barra"]=array();		
		$k=0;
		$salidas["barra"][$k]=array();
		$salidas["barra"][$k]["agrupar"]=1;
		$salidas["barra"][$k]["id"]="sBarra".$k;
		$salidas["barra"][$k]["contenido"]=array();
		
		$i=0;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_editar;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$id_sha.$c_sha;
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/edit';
		
		$i++;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$eliminar_c;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$eliminar_a;
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/delete';
	}
	/*DATOS LATERAL*/
	$salidas["info"]=array();	
	$i=0;
	$salidas["info"][$i]["desc"]='txt-126-1';
	$salidas["info"][$i]["data"]=imprimir($reg["FECHAI_OFERTA"]);
	
	$i++;
	$salidas["info"][$i]["desc"]='txt-127-1';
	$salidas["info"][$i]["data"]=imprimir($reg["FECHAF_OFERTA"]);

	$i++;
	$salidas["info"][$i]["desc"]='txt-165-0';
	$salidas["info"][$i]["data"]=imprimir($reg["NOMB_CIUDAD"]);

	$i++;
	$salidas["info"][$i]["desc"]='txt-162-0';
	$salidas["info"][$i]["data"]=imprimir($reg["NAME_ESPEC"]);

	$i++;
	$salidas["info"][$i]["desc"]='txt-222-0';
	$salidas["info"][$i]["data"]=imprimir($reg["CONTACT_OFERTA"]);

	$i++;
	$salidas["info"][$i]["desc"]='txt-201-0';
	$salidas["info"][$i]["data"]=imprimir($reg["COMENT_OFERT"]);

	$i++;
	$salidas["info"][$i]["desc"]='txt-179-0';
	$salidas["info"][$i]["data"]=imprimir($reg["FECHAS_OFERTA"]);
		
	$k=0;
	$salidas["contadores"]=array();			
	$salidas["contadores"][$k]["contenido"]=array();
	
	$i=0;		
	$salidas["contadores"][$k]["contenido"][$i]["desc"]='txt-180-0';
	$salidas["contadores"][$k]["contenido"][$i]["data"]=$reg["NOMBRE_U_OP"].' '.$reg["APELLIDO_U_OP"];
	$salidas["contadores"][$k]["contenido"][$i]["tipo"]=2;		
}
/***************************************/
/******** Cotizacion ********************/
/***************************************/
elseif($reg["OPCION"]=="SMCOTI"){
	$id_sha=encrip($reg["ID_COTIZ"]);
	
	$c_sha=encrip(504,2);
	$md_n=nuevo_item().$c_sha;
	$md=$id_sha.$c_sha;
	
	if($reg["STATUS_COTIZ"]==0){
		$eliminar_a=$id_sha.$c_sha.$acc01;
		$eliminar_c=$btn_borrar;
	}
	else{
		$eliminar_a=$id_sha.$c_sha.$acc02;
		$eliminar_c=$btn_recuperar;
	}

	$salidas["idsha"]=$id_sha;
	$salidas["tipobox"]=3;
	$salidas["deshab"]=$reg["STATUS_COTIZ"];	

	$permiso=$PermisosA[504]["P"];		

	$salidas["titulo"]["data"]=imprimir('Cotización de '.$reg["NOMBRE_M"].' '.$reg["APELLIDO_M"]);
	if($permiso==1){
		$salidas["titulo"]["link"]=2;
		$salidas["titulo"]["cod"]="md=".$md;
		$salidas["titulo"]["pagina"]='/abstract';
	}


	if($permiso==1){
		$salidas["barra"]=array();		
		$k=0;
		$salidas["barra"][$k]=array();
		$salidas["barra"][$k]["contenido"]=array();
		
		$i++;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$eliminar_c;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$eliminar_a;
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/delete';

	}
	$salidas["subtitulo"]=imprimir($reg["NOMBRE_M"].' '.$reg["APELLIDO_M"]);
	
	/*DATOS LATERAL*/
	$salidas["info"]=array();	
	$i=0;
	$salidas["info"][$i]["desc"]='txt-181-0';
	$salidas["info"][$i]["data"]=imprimir($reg["NOMBRE_U"].' '.$reg["APELLIDO_U"]);
	
	$i++;
	$salidas["info"][$i]["desc"]='txt-179-0';
	$salidas["info"][$i]["data"]=imprimir($reg["FECHAS_COTIZ"]);
		
	$k=0;
	$salidas["contadores"]=array();			
	$salidas["contadores"][$k]["contenido"]=array();
	
	$i=0;		
	$salidas["contadores"][$k]["contenido"][$i]["desc"]='txt-152-0';
	$salidas["contadores"][$k]["contenido"][$i]["data"]=$reg["VTOT_COTIZ"];
	$salidas["contadores"][$k]["contenido"][$i]["tipo"]=2;		
}
/***************************************/
/*************Noticias ****************/
/***************************************/
elseif($reg["OPCION"]=="SMNOTI"){
	$id_sha=encrip($reg["ID_NOTI"]);
	
	$c_sha=encrip(506,2);
	$md_n=$_NuevoI.$c_sha;
	$md=$id_sha.$c_sha;
	
	if($reg["HAB_NOTI"]==0){
		$eliminar_a=$id_sha.$c_sha.$acc01;
		$eliminar_c=$btn_borrar;
	}
	else{
		$eliminar_a=$id_sha.$c_sha.$acc02;
		$eliminar_c=$btn_recuperar;
	}
	
	$salidas["idsha"]=$id_sha;
	$salidas["tipobox"]=1;
	$salidas["deshab"]=$reg["HAB_NOTI"];

	$permiso=$PermisosA[506]["P"];

	/*LINKS*/

	$ArrayImg["OBJETO"]=$reg["ID_NOTI"];
	$ArrayImg["MODULO"]=506;
	$salidas["imagen"]=ImgBlanc($reg["M_IMG"],$ArrayImg);

	$salidas["titulo"]=imprimir($reg["TITLE_NOTI"]);	
	if($permiso==1){
		$salidas["barra"]=array();		
		$k=0;
		$salidas["barra"][$k]=array();
		$salidas["barra"][$k]["agrupar"]=1;
		$salidas["barra"][$k]["id"]="sBarra".$k;
		$salidas["barra"][$k]["contenido"]=array();
	}
	if($permiso==1){
		$i=0;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_editar;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$id_sha.$c_sha;
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/edit';
		
		$i++;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$eliminar_c;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$eliminar_a;
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/delete';
	}
	
	/*DATOS LATERAL*/
	$salidas["info"]=array();
	$i=0;
	$salidas["info"][$i]["desc"]='txt-206-0';
	$salidas["info"][$i]["data"]=$reg["SLUG_NOTI"];
	
	$i++;
	$salidas["info"][$i]["desc"]='txt-207-0';
	$salidas["info"][$i]["data"]=imprimir($reg["MTITLE_NOTI"]);
	
	$i++;
	$salidas["info"][$i]["desc"]='txt-121-0';
	$salidas["info"][$i]["data"]=imprimir($reg["MDESC_NOTI"]);

	$i++;
	$salidas["info"][$i]["desc"]='txt-145-0';
	$salidas["info"][$i]["data"]=imprimir($reg["NOMBRE_U"]).' '.imprimir($reg["APELLIDO_U"]);

	$i++;
	$salidas["info"][$i]["desc"]='txt-144-0';
	$salidas["info"][$i]["data"]=imprimir($reg["FECHAS_NOTI"]);


	$i=0;
	$salidas["contadores"][$k]["contenido"][$i]["desc"]='txt-133-0';
	$salidas["contadores"][$k]["contenido"][$i]["data"]=$reg["ACTIV_NOTI"]==1?'txt-1002-0':'txt-1003-0';
	$salidas["contadores"][$k]["contenido"][$i]["tipo"]=2;
	

	/*COMPLEMENTOS*/
	$i=0;
	$salidas["cargaex"][$i]["nombre"]='txt-1019-0';
	$salidas["cargaex"][$i]["cnf"]=$cnf;
	$salidas["cargaex"][$i]["scnf"]=1;
	$salidas["cargaex"][$i]["id"]=$id_sha;
	$salidas["cargaex"][$i]["tp"]=1;
}
/***************************************/
/******** Publicidad ********************/
/***************************************/
elseif($reg["OPCION"]=="SMPUBL"){
	$id_sha=encrip($reg["ID_PUBL"]);
	
	$c_sha=encrip(507,2);
	$md_n=$_NuevoI.$c_sha;
	$md=$id_sha.$c_sha;
	
	if($reg["HAB_PUBL"]==0){
		$eliminar_a=$id_sha.$c_sha.$acc01;
		$eliminar_c=$btn_borrar;
	}
	else{
		$eliminar_a=$id_sha.$c_sha.$acc02;
		$eliminar_c=$btn_recuperar;
	}
	
	$salidas["idsha"]=$id_sha;
	$salidas["tipobox"]=1;
	$salidas["deshab"]=$reg["HAB_PUBL"];

	$permiso=$PermisosA[507]["P"];

	/*LINKS*/

	$ArrayImg["OBJETO"]=$reg["ID_PUBL"];
	$ArrayImg["MODULO"]=507;
	$salidas["imagen"]=ImgBlanc($reg["M_IMG"],$ArrayImg);

	$salidas["titulo"]=imprimir($reg["NAME_PUBL"]);
	$salidas["subtitulo"]=imprimir($reg["TITLE_PUBL"]);
	
	if($permiso==1){
		$salidas["barra"]=array();		
		$k=0;
		$salidas["barra"][$k]=array();
		$salidas["barra"][$k]["agrupar"]=1;
		$salidas["barra"][$k]["id"]="sBarra".$k;
		$salidas["barra"][$k]["contenido"]=array();
		
		$i=0;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_editar;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$id_sha.$c_sha;
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/edit';
		
		$i++;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$eliminar_c;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$eliminar_a;
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/delete';
	}
	
	/*DATOS LATERAL*/
	$salidas["info"]=array();
	$i=0;
	$salidas["info"][$i]["desc"]='txt-126-1';
	$salidas["info"][$i]["data"]=$reg["FECHAI_PUBL"];
	
	$i++;
	$salidas["info"][$i]["desc"]='txt-127-1';
	$salidas["info"][$i]["data"]=$reg["FECHAF_PUBL"];

	$i++;
	$salidas["info"][$i]["desc"]='txt-145-0';
	$salidas["info"][$i]["data"]=imprimir($reg["NOMBRE_U_OP"]).' '.imprimir($reg["APELLIDO_U_OP"]);

	$i++;
	$salidas["info"][$i]["desc"]='txt-144-0';
	$salidas["info"][$i]["data"]=$reg["FECHAS_PUBL"];

	$i=0;
	$salidas["contadores"][$k]["contenido"][$i]["desc"]='txt-128-0';
	$salidas["contadores"][$k]["contenido"][$i]["data"]=$reg["ACTI_PUBL"]==1?'txt-1002-0':'txt-1003-0';
	$salidas["contadores"][$k]["contenido"][$i]["tipo"]=2;
	

	/*COMPLEMENTOS*/
	$i=0;
	$salidas["cargaex"][$i]["nombre"]='txt-1019-0';
	$salidas["cargaex"][$i]["cnf"]=$cnf;
	$salidas["cargaex"][$i]["scnf"]=1;
	$salidas["cargaex"][$i]["id"]=$id_sha;
	$salidas["cargaex"][$i]["tp"]=1;
}
?>
<?php
/*COMUN*/
if($reg["OPCION"]=="GRP"){
	$id_sha=encrip($reg["ID_GRUPO"]);
	
	$c_sha=encrip(8,2);
	$md_n=nuevo_item().$c_sha;
	$md=$id_sha.$c_sha;
	
	if($reg["HAB_GRUPO"]==0){
		$eliminar_a=$id_sha.$c_sha.$acc01;
		$eliminar_c=$btn_borrar;
	}
	else{
		$eliminar_a=$id_sha.$c_sha.$acc02;
		$eliminar_c=$btn_recuperar;
	}
	$salidas["idsha"]=$id_sha;
	$salidas["tipobox"]=3;
	$salidas["deshab"]=$reg["HAB_GRUPO"];

	$flag=$reg["ADMFLAG"];
	$permiso=$PermisosA[8]["P"];
	$permiso_real=(($PermisosA[10000]["P"]==1)||($flag!=1))&&($reg["ID_GRUPO"]!=$_GRUPO)?$permiso:0;
	
	/*Barra de Herramientas*/
	$md_ventana=$id_sha.$c_sha.nuevo_item().'001';
	$md_cresp=$id_sha.$c_sha.nuevo_item().'002';
	$md_usuario=nuevo_item().encrip(36,2).$id_sha;
	
	/*LINKS*/

	
	$salidas["titulo"]["data"]=imprimir($reg["DESC_GRUPO"]);
	if($permiso){
		$salidas["titulo"]["link"]=2;
		$salidas["titulo"]["cod"]="md=".$md;
		$salidas["titulo"]["pagina"]='/abstract';
	}
	$salidas["subtitulo"]=$reg["ID_GRUPO"];
	
	if($permiso==1){
		$salidas["barra"]=array();
		
		$k=0;
		$salidas["barra"][$k]=array();
		$salidas["barra"][$k]["agrupar"]=1;
		$salidas["barra"][$k]["id"]="sBarra".$k;
		$salidas["barra"][$k]["contenido"]=array();
		
		$i=0;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=2;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_info;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$md;
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/abstract';			
		
		if($permiso_real==1){
			$i++;
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
	}	
	if($permiso==1){	
		/*BARRA DE OPERACIONES*/
		$k++;
		$salidas["barra"][$k]["contenido"]=array();
		$salidas["barra"][$k]["agrupar"]=1;
		$salidas["barra"][$k]["id"]="scBarra".$k;
		$salidas["barra"][$k]["contenido"]=array();
		
		$i=0;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_vusuario;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$md_usuario;
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/edit';
	}
	
	
		
	/*DATOS LATERAL*/
	$salidas["info"]=array();	
	$i=0;
	$salidas["info"][$i]["desc"]='txt-1053-0';
	$salidas["info"][$i]["data"]=$reg["ID_GRUPO"];
	
	$i++;
	$salidas["info"][$i]["desc"]='txt-1009-0';
	$salidas["info"][$i]["data"]=imprimir($reg["DESC_GRUPO"]);
	
	$i++;
	$salidas["info"][$i]["desc"]='txt-1041-0';
	$salidas["info"][$i]["data"]=imprimir($reg["COMEN_GRUPO"]);		

	/**/
	
	$k=0;
	$salidas["contadores"]=array();			
	$salidas["contadores"][$k]["contenido"]=array();
	$salidas["contadores"][$k]["id"]="sContador".$k;
	
	$i=0;
	$salidas["contadores"][$k]["contenido"][$i]["desc"]='txt-1112-0';
	$salidas["contadores"][$k]["contenido"][$i]["data"]=$reg["VENTANAS_G"];
	$salidas["contadores"][$k]["contenido"][$i]["tipo"]=2;
	
	$k++;		
	$salidas["contadores"][$k]["contenido"]=array();
	$salidas["contadores"][$k]["id"]="sContador".$k;
	
	$i=0;
	
	if(!is_null($reg["USUARIOS_G"])){
		$salidas["contadores"][$k]["contenido"][$i]["desc"]='txt-1110-0';
		$salidas["contadores"][$k]["contenido"][$i]["data"]=$reg["USUARIOS_G"];
		$salidas["contadores"][$k]["contenido"][$i]["tipo"]=2;		
		$i++;
	}
	if(!is_null($reg["CRESP_G"])){
		$salidas["contadores"][$k]["contenido"][$i]["desc"]='txt-1113-0';
		$salidas["contadores"][$k]["contenido"][$i]["data"]=$reg["CRESP_G"];
		$salidas["contadores"][$k]["contenido"][$i]["tipo"]=2;		
		$i++;
	}

		
	/*COMPLEMENTOS*/	
	$i=0;
	$salidas["cargaex"][$i]["nombre"]='txt-1112-0';
	$salidas["cargaex"][$i]["cnf"]=$cnf;
	$salidas["cargaex"][$i]["scnf"]=2;
	$salidas["cargaex"][$i]["id"]=$id_sha;
	$salidas["cargaex"][$i]["tp"]=1;
		
	$i++;
	$salidas["cargaex"][$i]["nombre"]='txt-1019-0';
	$salidas["cargaex"][$i]["cnf"]=$cnf;
	$salidas["cargaex"][$i]["scnf"]=1;
	$salidas["cargaex"][$i]["id"]=$id_sha;
	$salidas["cargaex"][$i]["tp"]=1;	
}
elseif($reg["OPCION"]=="VEN"){
	$id_sha_n=md5($reg["ID_GRUPO"]);
	$id_sha=encrip($reg["ID_VENTANA"]);
	
	$c_sha=encrip(8,2);
	$md_n=encrip($reg["ID_GRUPO"]).$c_sha.nuevo_item().'002';
	$md=encrip($reg["ID_GRUPO"]).$c_sha.$id_sha.'001';
	
			
	$eliminar_a=$id_sha.$c_sha.$acc03;
	$eliminar_c=$btn_borrar;

	$salidas["idsha"]=$id_sha;
	$salidas["tipobox"]=4;
	$salidas["titulo"]=imprimir($reg["VENTANA_NOMBRE"]);
	$salidas["subtitulo"]=imprimir($reg["DESC_GVENTANA"]);
}
elseif($reg["OPCION"]=="USU"){
	$id_sha_n=md5($reg["ID_USUARIO"]);	
	$id_sha=encrip($reg["ID_USUARIO"]);
	
	$c_sha=encrip(36,2);
	$md_n=nuevo_item().$c_sha;
	$md=$id_sha.$c_sha;
	/*Barra de Herramientas*/
	$permiso=$PermisosA[8]["P"];
	$permiso_real=($reg["ID_USUARIO"]==$_USUARIO)?0:$permiso;
	
	/*LINKS*/
	$md_grupo=encrip($reg["ID_GRUPO"]).encrip(8,2);
	
	if($reg["HAB_U"]==0){
		$eliminar_a=$id_sha.$c_sha.$acc01;
		$eliminar_c=$btn_borrar;
	}
	else{
		$eliminar_a=$id_sha.$c_sha.$acc02;
		$eliminar_c=$btn_recuperar;
	}
	$eliminar_g=$id_sha.$c_sha.$acc03;

	$salidas["idsha"]=$id_sha;
	$salidas["tipobox"]=3;
	$salidas["deshab"]=$reg["HAB_U"];
	

	$ArrayImg["OBJETO"]=$reg["ID_USUARIO"];
	$ArrayImg["MODULO"]=36;
	$salidas["imagen"]=ImgBlanc($reg["M_IMG"],$ArrayImg);
	
	
	if($reg["NOMBRE_U"]!=""){
		if($permiso){
			$salidas["titulo"]["data"]=sprintf("%s %s",imprimir($reg["NOMBRE_U"]),imprimir($reg["APELLIDO_U"]));
			$salidas["titulo"]["link"]=2;
			$salidas["titulo"]["cod"]="md=".$md;
			$salidas["titulo"]["pagina"]='/abstract';
		}
		else
			$salidas["titulo"]=sprintf("%s %s",imprimir($reg["NOMBRE_U"]),imprimir($reg["APELLIDO_U"]));
		$salidas["subtitulo"]=sprintf("%s",imprimir($reg["CORREO_U"]));
	}
	else{
		if($permiso){
			$salidas["titulo"]["data"]=sprintf("%s",imprimir($reg["CORREO_U"]));
			$salidas["titulo"]["link"]=2;
			$salidas["titulo"]["cod"]="md=".$md;
			$salidas["titulo"]["pagina"]='/abstract';
		}
		else
			$salidas["titulo"]=sprintf("%s",imprimir($reg["CORREO_U"]));
	}
	
	
	
	$salidas["barra"]=array();
	
	$k=0;
	$salidas["barra"][$k]=array();
	$salidas["barra"][$k]["agrupar"]=1;
	$salidas["barra"][$k]["id"]="sBarra".$k;
	$salidas["barra"][$k]["contenido"]=array();		
				
	
	$i=0;
	if($permiso==1){				
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_nuevo;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$md_n;
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/edit';	
		$i++;
	}
		
	$salidas["barra"][$k]["contenido"][$i]["tipo"]=2;
	$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_info;
	$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$md;
	$salidas["barra"][$k]["contenido"][$i]["pagina"]='/abstract';		
				
	if($permiso_real==1){	
		$i++;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_editar;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$id_sha.$c_sha;
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/edit';

		$i++;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['label']='txt-1432-0';
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['title']='txt-1432-1';
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['icons']="fa-remove";
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['tpb']=4;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]['transictp']=1;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$eliminar_g;
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/delete';
		
		$i++;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$eliminar_c;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$eliminar_a;
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/delete';
	}
	
		
	$salidas["info"]=array();
	$i=0;
	if($reg["NOMBRE_U"]!=''&&$reg["APELLIDO_U"]!=''){
		$salidas["info"][$i]["desc"]='txt-1069-0';
		$salidas["info"][$i]["data"]=sprintf("%s %s",imprimir($reg["NOMBRE_U"]),imprimir($reg["APELLIDO_U"]));	
	}
	
	$i++;
	$salidas["info"][$i]["desc"]='txt-1094-0';
	$salidas["info"][$i]["data"]=imprimir($reg["ALIAS"]);
	
	$i++;
	$salidas["info"][$i]["desc"]='txt-1070-0';
	$salidas["info"][$i]["data"]=imprimir($reg["GENERO"]);
	
	$i++;
	$salidas["info"][$i]["desc"]='txt-1057-0';
	$salidas["info"][$i]["data"]=$reg["FECHA_UF"];	
	
	$i++;
	$salidas["info"][$i]["desc"]=$reg["ABR_DOCUMENTO"];
	$salidas["info"][$i]["data"]=imprimir($reg["DOCUMENTO"]);	

	if(!is_null($reg["TZ_DIFE"])){	
		$i++;
		$salidas["info"][$i]["desc"]='txt-1102-0';
		$salidas["info"][$i]["data"]=$reg["TZ_DIFE"];	
	}

	$i++;
	$salidas["info"][$i]["desc"]='txt-1072-0';
	$salidas["info"][$i]["data"]=imprimir($reg["CORREO_U"]);	
	
	$i++;
	$salidas["info"][$i]["desc"]='txt-1065-0';
	$salidas["info"][$i]["data"]=imprimir($reg["DIRECCION_U"]);	
	
	$i++;
	$salidas["info"][$i]["desc"]='txt-1058-0';
	$salidas["info"][$i]["data"]=imprimir($reg["NOMB_CIUDAD"]);	
	
	$i++;
	$salidas["info"][$i]["desc"]='txt-1059-0';
	$salidas["info"][$i]["data"]=imprimir($reg["TELEFONO_U"]);	
	
	$i++;
	$salidas["info"][$i]["desc"]='txt-1132-0';
	$salidas["info"][$i]["data"]=imprimir($reg["TELEFONO2_U"]);	
	
	$i++;
	$salidas["info"][$i]["desc"]='txt-1083-0';
	$salidas["info"][$i]["data"]=imprimir($reg["DESC_GRUPO"]);	
	if($permiso){
		$salidas["info"][$i]["link"]=2;
		$salidas["info"][$i]["cod"]="md=".$md_grupo;
		$salidas["info"][$i]["pagina"]='/abstract';
	}

	if($reg["LOCATION_US"]==1){
		$direccion=urlencode(sprintf("%s,%s",$reg["REF_LAT"],$reg["REF_LON"]));
		$zoom=$reg["ZOOM_MAP"];
		$icono="http://siie.co/imagenes/geo.png";
		$mapa=sprintf('https://maps.google.com/maps/api/staticmap?center=%s&zoom=%d&size=500x500&maptype=roadmap&markers=icon:%s|%s&sensor=false',$direccion,$zoom,$icono,$direccion);
	
	
		$i=0;
		$salidas["imgap"][$i]["desc"]='txt-1064-0';
		$salidas["imgap"][$i]["tipo"]='map';
		$salidas["imgap"][$i]["url"]=$mapa;
			$k=0;
			$salidas["imgap"][$i]["info"][$k]["desc"]='txt-1058-0';
			$salidas["imgap"][$i]["info"][$k]["data"]=imprimir($reg["NOMB_CIUDAD_LOC"]);
	}
	
	/*INFO RESALTE*/
	if($reg["DESC_GRUPO"]!=''){
		$i=0;
		$salidas["resinfo"][$i]["desc"]='txt-1083-0';
		$salidas["resinfo"][$i]["data"]=imprimir($reg["DESC_GRUPO"]);	
		$salidas["resinfo"][$i]["link"]=2;
		$salidas["resinfo"][$i]["cod"]="md=".$md_grupo;
		$salidas["resinfo"][$i]["pagina"]='/abstract';
	}

	
	/*COMPLEMENTOS*/
	$i=0;
	$salidas["cargaex"][$i]["nombre"]='txt-1019-0';
	$salidas["cargaex"][$i]["cnf"]=$cnf;
	$salidas["cargaex"][$i]["scnf"]=1;
	$salidas["cargaex"][$i]["id"]=$id_sha;
	$salidas["cargaex"][$i]["tp"]=1;	
}
elseif($reg["OPCION"]=="INFALL"){
	$id_sha=encrip($reg["ID_INFORME"]);
	
	$c_sha=encrip($reg["ID_VENTANA"],2);	
	$md=$id_sha.$c_sha;	
	
	$salidas["idsha"]=$id_sha;
	$salidas["tipobox"]=3;
	$salidas["deshab"]=$reg["HAB_INFORME"];
	
	$permiso=isset($PermisosA[$reg["ID_VENTANA"]]["P"])?$PermisosA[$reg["ID_VENTANA"]]["P"]:0;
	

	$salidas["titulo"]["data"]=imprimir($reg["NOMB_INFORME"]);
	if($permiso){
		$salidas["titulo"]["link"]=1;
		$salidas["titulo"]["cod"]="md=".$md;
		$salidas["titulo"]["pagina"]='/sreport';
	}

	$salidas["subtitulo"]=imprimir($reg["REF_INFORME"]);
	
	/*COMPLEMENTOS*/	
	$i=0;
	$salidas["cargaex"][$i]["nombre"]='txt-1112-0';
	$salidas["cargaex"][$i]["cnf"]=$cnf;
	$salidas["cargaex"][$i]["scnf"]=$reg["ID_INFORME"];
	$salidas["cargaex"][$i]["id"]=$id_sha;
	$salidas["cargaex"][$i]["tp"]=1;
}
/***************************************/
/****************CLASE****************/
/***************************************/
elseif($reg["OPCION"]=="GRUPOSEMP"){
	$id_sha=encrip($reg["TIPO_GRUPOPAL"]);
	
	$c_sha=encrip(10003,2);
	$md_n=nuevo_item().$c_sha;
	$md=$id_sha.$c_sha;
	
	if($reg["HAB_GRUPOPAL"]==0){
		$eliminar_a=$id_sha.$c_sha.$acc01;
		$eliminar_c=$btn_borrar;
	}
	else{
		$eliminar_a=$id_sha.$c_sha.$acc02;
		$eliminar_c=$btn_recuperar;
	}
	$salidas["idsha"]=$id_sha;
	$salidas["tipobox"]=3;
	$salidas["deshab"]=$reg["HAB_GRUPOPAL"];
	
	$permiso=$PermisosA[10003]["P"];
	$permiso_real=$PermisosA[10000]["P"];
	
	/*Barra de Herramientas*/
	$md_usuario=encrip($reg["ID_USUARIO"]).encrip(36,2);
	
	$salidas["imagen"]=ImgName($_PROYECTO,$_EMPRESA,10003,$reg["TIPO_GRUPOPAL"]);
	
	$salidas["titulo"]=imprimir($reg["NOMB_GRUPOPAL"]);
	$salidas["subtitulo"]=imprimir($reg["DESC_GRUPOPAL"]);
	
	if($permiso==1){
		$salidas["barra"]=array();
		
		$k=0;
		$salidas["barra"][$k]=array();
		$salidas["barra"][$k]["agrupar"]=1;
		$salidas["barra"][$k]["id"]="sBarra".$k;
		$salidas["barra"][$k]["contenido"]=array();
		
		$i=0;	
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_nuevo;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$md_n;
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/edit';	
		
		$i++;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_editar;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$md;
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/edit';
		
		$i++;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$eliminar_c;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$eliminar_a;
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/delete';

		$k++;
		$salidas["barra"][$k]=array();
		$salidas["barra"][$k]["agrupar"]=1;
		$salidas["barra"][$k]["id"]="sBarra".$k;
		$salidas["barra"][$k]["contenido"]=array();

		$i++;
		if($PermisosA[10001]["P"]==1){
			
			$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
			$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_edit_txt_cli;
			$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$md.nuevo_item().'001';
			$salidas["barra"][$k]["contenido"][$i]["pagina"]='/operation';
			$i++;
		}
	}	
	
	/*DATOS LATERAL*/
	$salidas["info"]=array();	
	$i=0;
	$salidas["info"][$i]["desc"]='txt-1053-0';
	$salidas["info"][$i]["data"]=$reg["TIPO_GRUPOPAL"];

	$i++;
	$salidas["info"][$i]["desc"]='txt-1130-0';
	$salidas["info"][$i]["data"]=imprimir($reg["NOMB_GRUPOPAL"]);

	$i++;
	$salidas["info"][$i]["desc"]='txt-1091-0';
	$salidas["info"][$i]["data"]=imprimir($reg["DESC_GRUPOPAL"]);

	$i++;
	$salidas["info"][$i]["desc"]='txt-3009-0';
	$salidas["info"][$i]["data"]=sprintf("%s/class/%s",$_PARAMETROS["LWSERVICE"],$reg["LINK_TIPOE"]);
	$salidas["info"][$i]["link"]=3;
	$salidas["info"][$i]["cod"]="";
	$salidas["info"][$i]["pagina"]=sprintf("%s/class/%s",$_PARAMETROS["LWSERVICE"],$reg["LINK_TIPOE"]);

	/*INFO AL PIE*/
	$i=0;
	$salidas["pie"][$i]["desc"]='txt-1031-0';
	$salidas["pie"][$i]["data"]=imprimir($reg["NOMBRE_U_OP"]).' '.imprimir($reg["APELLIDO_U_OP"]);
	$salidas["pie"][$i]["link"]=2;
	$salidas["pie"][$i]["cod"]="md=".$md_usuario;
	$salidas["pie"][$i]["pagina"]='/abstract';
	$salidas["pie"][$i]["fecha"]=sprintf("%s/%s/%s",$reg["DIA_M"],$reg["MES_M"],$reg["ANN_M"]);

	/*COMPLEMENTOS*/	
	$i=0;
	$salidas["cargaex"][$i]["nombre"]='txt-1019-0';
	$salidas["cargaex"][$i]["cnf"]=$cnf;
	$salidas["cargaex"][$i]["scnf"]=1;
	$salidas["cargaex"][$i]["id"]=$id_sha;
	$salidas["cargaex"][$i]["tp"]=1;	
}
/***************************************/
/****************TIPO*******************/
/***************************************/
elseif($reg["OPCION"]=="EMPTIPO"){
	$id_sha=encrip($reg["ID_TIPOE"]);
	
	$c_sha=encrip(10002,2);
	$md_n=nuevo_item().$c_sha;
	$md=$id_sha.$c_sha;
	
	if($reg["HAB_TIPOE"]==0){
		$eliminar_a=$id_sha.$c_sha.$acc01;
		$eliminar_c=$btn_borrar;
	}
	else{
		$eliminar_a=$id_sha.$c_sha.$acc02;
		$eliminar_c=$btn_recuperar;
	}
	$salidas["idsha"]=$id_sha;
	$salidas["tipobox"]=3;
	$salidas["deshab"]=$reg["HAB_TIPOE"];
	
	$permiso=$PermisosA[10002]["P"];
	$permiso_real=$PermisosA[10000]["P"];
	
	/*Barra de Herramientas*/
	$md_usuario=encrip($reg["ID_USUARIO"]).encrip(36,2);
	
	
	$salidas["imagen"]=ImgName($_PROYECTO,$_EMPRESA,10002,$reg["ID_TIPOE"]);
	
	$salidas["titulo"]=imprimir($reg["NOMB_TIPOE"]);
	$salidas["subtitulo"]=imprimir($reg["DESC_TIPOE"]);
	
	if($permiso==1){
		$salidas["barra"]=array();
		
		$k=0;
		$salidas["barra"][$k]=array();
		$salidas["barra"][$k]["agrupar"]=1;
		$salidas["barra"][$k]["id"]="sBarra".$k;
		$salidas["barra"][$k]["contenido"]=array();
		
		$i=0;	
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_nuevo;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$md_n;
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/edit';	
		
		$i++;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_editar;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$md;
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
	$salidas["info"][$i]["desc"]='txt-1053-0';
	$salidas["info"][$i]["data"]=$reg["ID_TIPOE"];

	$i++;
	$salidas["info"][$i]["desc"]='txt-1130-0';
	$salidas["info"][$i]["data"]=imprimir($reg["NOMB_TIPOE"]);

	$i++;
	$salidas["info"][$i]["desc"]='txt-1091-0';
	$salidas["info"][$i]["data"]=imprimir($reg["DESC_TIPOE"]);

	$i++;
	$salidas["info"][$i]["desc"]='txt-1219-0';
	$salidas["info"][$i]["data"]=imprimir($reg["NOMB_GRUPOPAL"]);

	$i++;
	$salidas["info"][$i]["desc"]='txt-3009-0';
	$salidas["info"][$i]["data"]=sprintf("%s/type/%s",$_PARAMETROS["LWSERVICE"],$reg["LINK_TIPOE"]);
	$salidas["info"][$i]["link"]=3;
	$salidas["info"][$i]["cod"]="";
	$salidas["info"][$i]["pagina"]=sprintf("%s/type/%s",$_PARAMETROS["LWSERVICE"],$reg["LINK_TIPOE"]);

	/*INFO AL PIE*/
	$i=0;
	$salidas["pie"][$i]["desc"]='txt-1031-0';
	$salidas["pie"][$i]["data"]=imprimir($reg["NOMBRE_U_OP"]).' '.imprimir($reg["APELLIDO_U_OP"]);
	$salidas["pie"][$i]["link"]=2;
	$salidas["pie"][$i]["cod"]="md=".$md_usuario;
	$salidas["pie"][$i]["pagina"]='/abstract';
	$salidas["pie"][$i]["fecha"]=sprintf("%s/%s/%s",$reg["DIA_M"],$reg["MES_M"],$reg["ANN_M"]);
		
		
	/*COMPLEMENTOS*/	
	$i=0;
	$salidas["cargaex"][$i]["nombre"]='txt-1019-0';
	$salidas["cargaex"][$i]["cnf"]=$cnf;
	$salidas["cargaex"][$i]["scnf"]=1;
	$salidas["cargaex"][$i]["id"]=$id_sha;
	$salidas["cargaex"][$i]["tp"]=1;	
}
/***************************************/
/****************CLIENTES***************/
/***************************************/
elseif($reg["OPCION"]=="CLIEMPRESA"){
	$id_sha=encrip($reg["ID_MEMPRESA"]);
	
	$c_sha=encrip(10004,2);
	$md_n=nuevo_item().$c_sha;
	$md=$id_sha.$c_sha;
	
	if($reg["HAB_MEMPRESA"]==0){
		$eliminar_a=$id_sha.$c_sha.$acc01;
		$eliminar_c=$btn_borrar;
	}
	else{
		$eliminar_a=$id_sha.$c_sha.$acc02;
		$eliminar_c=$btn_recuperar;
	}
	$salidas["idsha"]=$id_sha;
	$salidas["tipobox"]=3;
	$salidas["deshab"]=$reg["HAB_MEMPRESA"];
	
	$permiso=$PermisosA[10004]["P"];
	$permiso_real=$PermisosA[10000]["P"];
	
	/*Barra de Herramientas*/
	$md_usuario=encrip($reg["ID_USUARIO"]).encrip(36,2);
	
	/*LINKS*/
	/**/
	$salidas["imagen"]=ImgName($_PROYECTO,$_EMPRESA,0,$reg["ID_MEMPRESA"],'LogoClient');
	
	$salidas["titulo"]=imprimir($reg["NOMB_MEMPRESA"]);
	$salidas["subtitulo"]=imprimir($reg["LEMA_EMPRESA"]);
	
	if($permiso==1){
		$salidas["barra"]=array();
		
		$k=0;
		$salidas["barra"][$k]=array();
		$salidas["barra"][$k]["agrupar"]=1;
		$salidas["barra"][$k]["id"]="sBarra".$k;
		$salidas["barra"][$k]["contenido"]=array();
		
		$i=0;	
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_nuevo;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$md_n;
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/edit';	
		
		$i++;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_editar;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$md;
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/edit';
		
		$i++;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$eliminar_c;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$eliminar_a;
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/delete';

		$k++;
		$salidas["barra"][$k]=array();
		$salidas["barra"][$k]["agrupar"]=1;
		$salidas["barra"][$k]["id"]="sBarra".$k;
		$salidas["barra"][$k]["contenido"]=array();

		$i++;
		if($PermisosA[10001]["P"]==1){			
			$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
			$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_edit_txt;
			$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$md.nuevo_item().'001';
			$salidas["barra"][$k]["contenido"][$i]["pagina"]='/operation';
		}
		
	}	
	/*DATOS LATERAL*/
	$salidas["info"]=array();	
	$i=0;
	$salidas["info"][$i]["desc"]='txt-1053-0';
	$salidas["info"][$i]["data"]=sprintf("%s-%06s",$_textos[1292][0],$reg["ID_MEMPRESA"]);

	$i++;
	$salidas["info"][$i]["desc"]='txt-1130-0';
	$salidas["info"][$i]["data"]=imprimir($reg["NOMB_MEMPRESA"]);

	$i++;
	$salidas["info"][$i]["desc"]='txt-1091-0';
	$salidas["info"][$i]["data"]=imprimir($reg["DESC_EMPRESA"]);

	$i++;
	$salidas["info"][$i]["desc"]='txt-1293-0';
	$salidas["info"][$i]["data"]=imprimir($reg["LEMA_EMPRESA"]);

	$i++;
	$salidas["info"][$i]["desc"]='txt-1293-0';
	$salidas["info"][$i]["data"]=imprimir($reg["LEMA_EMPRESA"]);

	$i++;
	$salidas["info"][$i]["desc"]='txt-1161-0';
	$salidas["info"][$i]["data"]=imprimir($reg["NOMB_TIPOE"]);

	$i++;
	$salidas["info"][$i]["desc"]='txt-1219-0';
	$salidas["info"][$i]["data"]=imprimir($reg["NOMB_GRUPOPAL"]);

	$i++;
	$salidas["info"][$i]["desc"]='txt-3009-0';
	$salidas["info"][$i]["data"]=sprintf("%s/emp/%s",$_PARAMETROS["LWSERVICE"],$reg["URL"]);
	$salidas["info"][$i]["link"]=3;
	$salidas["info"][$i]["cod"]="";
	$salidas["info"][$i]["pagina"]=sprintf("%s/emp/%s",$_PARAMETROS["LWSERVICE"],$reg["URL"]);

	/*INFO AL PIE*/
	$i=0;
	$salidas["pie"][$i]["desc"]='txt-1031-0';
	$salidas["pie"][$i]["data"]=imprimir($reg["NOMBRE_U_OP"]).' '.imprimir($reg["APELLIDO_U_OP"]);
	$salidas["pie"][$i]["link"]=2;
	$salidas["pie"][$i]["cod"]="md=".$md_usuario;
	$salidas["pie"][$i]["pagina"]='/abstract';
	$salidas["pie"][$i]["fecha"]=sprintf("%s/%s/%s",$reg["DIA_M"],$reg["MES_M"],$reg["ANN_M"]);
		
		
	/*COMPLEMENTOS*/	
	$i=0;
	$salidas["cargaex"][$i]["nombre"]='txt-1019-0';
	$salidas["cargaex"][$i]["cnf"]=$cnf;
	$salidas["cargaex"][$i]["scnf"]=1;
	$salidas["cargaex"][$i]["id"]=$id_sha;
	$salidas["cargaex"][$i]["tp"]=1;	
}
/***************************************/
/*************AREAS OPERACIONALES*******/
/***************************************/
elseif(($reg["OPCION"]=="ARE")||($reg["OPCION"]=="ARE2")){
	$id_sha=encrip($reg["ID_RESP"]);
	
	$c_sha=encrip(19,2);
	$md_n=$_NuevoI.$c_sha;
	$md=$id_sha.$c_sha;
	
	if($reg["HAB_RESP"]==0){
		$eliminar_a=$id_sha.$c_sha.$acc01;
		$eliminar_c=$btn_borrar;
	}
	else{
		$eliminar_a=$id_sha.$c_sha.$acc02;
		$eliminar_c=$btn_recuperar;
	}
	
	$salidas["idsha"]=$id_sha;
	$salidas["tipobox"]=1;
	$salidas["deshab"]=$reg["HAB_RESP"];
	$permiso=$PermisosA[19]["P"];
	$permiso_adm=$PermisosA[8]["P"];
	/*Barra de Herramientas*/
	$md_adm=$id_sha.encrip(8,2).$_NuevoI.'003';
	$md_ciudad=$id_sha.encrip(8,2).$_NuevoI.'004';
	/*LINKS*/
	$md_ub=encrip($reg["ID_UBICACION"]).encrip(24,2);


	$ArrayImg["OBJETO"]=$reg["ID_RESP"];
	$ArrayImg["MODULO"]=19;
	$salidas["imagen"]=ImgBlanc($reg["M_IMG"],$ArrayImg);

	$salidas["titulo"]["data"]=imprimir($reg["NOMB_RESP"]);
	if($permiso){
		$salidas["titulo"]["link"]=2;
		$salidas["titulo"]["cod"]="md=".$md;
		$salidas["titulo"]["pagina"]='/abstract';
	}
	$salidas["subtitulo"]=imprimir($reg["DIRECCION"]);
	
	if($permiso==1){
		$salidas["barra"]=array();		
		$k=0;
		$salidas["barra"][$k]=array();
		$salidas["barra"][$k]["agrupar"]=1;
		$salidas["barra"][$k]["id"]="sBarra".$k;
		$salidas["barra"][$k]["contenido"]=array();
		
		$i=0;	
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_nuevo;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$md_n;
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/edit';	
		
		$i++;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_info;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$md;
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/abstract';			
		
		$i++;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_editar;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$id_sha.$c_sha;
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/edit';
		

		if($reg["PPAL"]!=1){
			$i++;
			$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
			$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$eliminar_c;
			$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$eliminar_a;
			$salidas["barra"][$k]["contenido"][$i]["pagina"]='/delete';
		}
		
		
	}
	
	/*DATOS LATERAL*/
	$salidas["info"]=array();
	$i=0;
	$salidas["info"][$i]["desc"]='txt-1130-0';
	$salidas["info"][$i]["data"]=imprimir($reg["NOMB_RESP"]);
	
	$i++;
	$salidas["info"][$i]["desc"]='txt-1077-0';
	$salidas["info"][$i]["data"]=imprimir($reg["ABR_RESP"]);
	
	$i++;
	$salidas["info"][$i]["desc"]='txt-1041-0';
	$salidas["info"][$i]["data"]=imprimir($reg["COMENT_RESP"]);
		
	$i++;
	$salidas["info"][$i]["desc"]='txt-1059-0';
	$salidas["info"][$i]["data"]=imprimir($reg["TELEFONO"]);
	
	$i++;
	$salidas["info"][$i]["desc"]='txt-1065-0';
	$salidas["info"][$i]["data"]=imprimir($reg["DIRECCION"]);

	$i++;
	$salidas["info"][$i]["desc"]='txt-3009-0';
	$salidas["info"][$i]["data"]=$reg["SLUG_RESP"];
	
	/*INFO RESALTE*/
	$i=0;
	$salidas["resinfo"][$i]["desc"]='txt-1058-0';
	$salidas["resinfo"][$i]["data"]=imprimir($reg["NOMB_CIUDAD"]);	
		
	/*MAPA*/	
	if(!is_null($reg["REF_LON"])){
		$direccion=urlencode(sprintf("%s,%s",$reg["REF_LAT"],$reg["REF_LON"]));
		$zoom=$reg["ZOOM_MAP"];
		$icono="http://siie.co/imagenes/geo.png";
		$mapa=sprintf('https://maps.google.com/maps/api/staticmap?center=%s&zoom=%d&size=600x300&maptype=roadmap&markers=icon:%s|%s&sensor=false',$direccion,$zoom,$icono,$direccion);
		$i=0;
		$salidas["imgap"][$i]["desc"]='txt-1064-0';
		$salidas["imgap"][$i]["tipo"]='map';
		$salidas["imgap"][$i]["url"]=$mapa;
			$k=0;
			$salidas["imgap"][$i]["info"][$k]["desc"]='txt-1058-0';
			$salidas["imgap"][$i]["info"][$k]["data"]=imprimir($reg["NOMB_CIUDAD"]);
	}

	
	/*COMPLEMENTOS*/
	$i=0;
	$salidas["cargaex"][$i]["nombre"]='txt-1019-0';
	$salidas["cargaex"][$i]["cnf"]=$cnf;
	$salidas["cargaex"][$i]["scnf"]=1;
	$salidas["cargaex"][$i]["id"]=$id_sha;
	$salidas["cargaex"][$i]["tp"]=1;
}
/***************************************/
/*********REGISTRO DE USUARIO***********/
/***************************************/
elseif($reg["OPCION"]=="REGUS"){

	$salidas["idsha"]=$id_sha;
	$salidas["tipobox"]=3;
	$salidas["deshab"]=0;
	
	/*Barra de Herramientas*/
	$md=encrip($reg["ID_USUARIO"]).encrip(36,2);
	
	/*LINKS*/
	$salidas["imagen"]=ImgName($_PROYECTO,$_EMPRESA,36,$reg["ID_USUARIO"]);
	
	if($reg["NOMBRE_U"]!=""){
		$salidas["titulo"]["data"]=sprintf("%s %s",imprimir($reg["NOMBRE_U"]),imprimir($reg["APELLIDO_U"]));
		$salidas["subtitulo"]=sprintf("%s",imprimir($reg["CORREO_U"]));
	}
	else $salidas["titulo"]["data"]=sprintf("%s",imprimir($reg["CORREO_U"]));
	$salidas["titulo"]["link"]=2;
	$salidas["titulo"]["cod"]="md=".$md;
	$salidas["titulo"]["pagina"]='/abstract';
	

	$k=0;
	$salidas["contadores"]=array();			
	$salidas["contadores"][$k]["contenido"]=array();
	$salidas["contadores"][$k]["id"]="sContador".$k;
	
	$i=0;
	$salidas["contadores"][$k]["contenido"][$i]["desc"]='txt-1057-0';
	$salidas["contadores"][$k]["contenido"][$i]["data"]=$reg["FECHA_U"];
	$salidas["contadores"][$k]["contenido"][$i]["tipo"]=2;	
}
?>
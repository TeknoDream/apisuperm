<?php
/*
---------------------------------------------------------------------------------
                        SUPERMAESTROS BACKOFFICE
                             Proyecto N° 32
                        Archivo: autocomplete_032.php
           Descripción: archivo de configuración de campos con autocompletacion
--------------------------------------------------------------------------------
Este archivo permite hacer completacion y campos de tipo busqueda en tiempo 
real 
********************************/
/*******************************/
/*** Buscar instalador en el ***/ 
/**Fomulario de Asignar Puntos */
/******************************/
if($tp==5000){

	$sWhere=" WHERE adm_usuarios.HAB_U=0 AND TYPE_USUARIO=1 ";
	$sWhere.=sWhere(5000,$busc,$_PROYECTO);	
	$s=$sqlCons[0][500].$sWhere;
    $reqOP = $dbEmpresa->prepare($s);
	if($busc!='') 	$reqOP->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
    $reqOP->execute();
	$autocomplete=array();
	while($regOP = $reqOP->fetch()){
		$autocomplete[]=array(	"id"	=>$regOP["ID_USUARIO"]
							,	"label"	=>imprimir($regOP["NOMBRE_U"],2).' '.imprimir($regOP["APELLIDO_U"],2)
							,	"value"	=>imprimir($regOP["NOMBRE_U"],2).' '.imprimir($regOP["APELLIDO_U"],2)
							,	"cont"=>array("id_isntl"=>$regOP["ID_USUARIO"]));	
	}
	echo json_encode($autocomplete);
}

?>
<?php
/*
---------------------------------------------------------------------------------
                        SUPERMAESTROS BACKOFFICE
                             Proyecto N° 32
                        Archivo: base_elim_032.php
             Descripción: archivo de configuración de eliminación 
--------------------------------------------------------------------------------

Este archivo configura la eliminación de datos de cada uno de los diferente
módulos del backoffice, de acuerdo al número de módulo que se esté cargando,
se utiliza el campo llave de la tabla para identificar el registro, y se usa
el campo habilitador que cambia de valor para indicar que ese registro ya no 
debe listarse, por integridad y seguridad, nunca se borra totalmente los registros
solo se cambia el estatus del campo habilitador para que no sea mostrado

Campo habilitador=0 : registro activo y habilitado
Campo habilitador=1 : registro inactivo no habilitado

/*************************/
/*************************/
/*****   Usuarios *********/
/*************************/
if($cnf==500){ 
	if(($accion==$acc01)||($accion==$acc02)){
		$sWhere=encrip_mysql('adm_usuarios.ID_USUARIO');
		$sID=$sqlCons[0][500]." WHERE $sWhere=:id LIMIT 1";
		$Campo_ID="ID_USUARIO";
		$Campo="HAB_U";		
		$Tabla="adm_usuarios";	
		$pag_prop=true;	
		$idioma=false;	
	}
}
/*************************/
/*************************/
/*****Remodelaciones *****/
/*************************/
elseif($cnf==501){ 
	if(($accion==$acc01)||($accion==$acc02)){
		$sWhere=encrip_mysql('y_proyectos.ID_PROY');
		$sID=$sqlCons[0][501]." WHERE $sWhere=:id LIMIT 1";
		$Campo_ID="ID_PROY";
		$Campo="HAB_PROY";		
		$Tabla="y_proyectos";	
		$pag_prop=true;	
		$idioma=false;	
	}
	//Eliminar una foto seleccionada de la remodelación
	elseif($accion==$acc03){
		$sWhere=encrip_mysql('y_proyectos_fotos.ID_FOTO');
		$sID=$sqlCons[1][501]." WHERE $sWhere=:id LIMIT 1";
		$Campo_ID="ID_FOTO";	
		$Tabla="y_proyectos_fotos";	
	}
}
/*************************/
/*************************/
/***** Proyectos *********/
/*************************/
elseif($cnf==502){ 
	if(($accion==$acc01)||($accion==$acc02)){
		$sWhere=encrip_mysql('x_ofertas.ID_OFERTA');
		$sID=$sqlCons[0][502]." WHERE $sWhere=:id LIMIT 1";
		$Campo_ID="ID_OFERTA";
		$Campo="HAB_OFERTA";		
		$Tabla="x_ofertas";	
		$pag_prop=true;	
		$idioma=false;	
	}
}
/*************************/
/*************************/
/*****Facturas ***********/
/*************************/
elseif($cnf==503){
	if(($accion==$acc01)||($accion==$acc02)){
		$sWhere=encrip_mysql('y_facturas.ID_FACT');
		$sID=$sqlCons[0][503]." WHERE $sWhere=:id LIMIT 1";
		$Campo_ID="ID_FACT";
		$Campo="VALIDAT_FACT";		
		$Tabla="y_facturas";	
		$pag_prop=true;	
		$idioma=false;	
	}
}
/*************************/
/*************************/
/***** Cotizaciones*******/
/*************************/
elseif($cnf==504){ 
	if(($accion==$acc01)||($accion==$acc02)){
		$sWhere=encrip_mysql('y_cotizacion.ID_COTIZ');
		$sID=$sqlCons[0][504]." WHERE $sWhere=:id LIMIT 1";
		$Campo_ID="ID_COTIZ";
		$Campo="STATUS_COTIZ";		
		$Tabla="y_cotizacion";	
		$pag_prop=true;	
		$idioma=false;	
	}
}
/*************************/
/*************************/
/*** Estado de cuenta*****/
/*************************/
elseif($cnf==505){ 
	if(($accion==$acc01)||($accion==$acc02)){
		$sWhere=encrip_mysql('y_ecuenta.ID_ECUENTA');
		$sID=$sqlCons[0][505]." WHERE $sWhere=:id LIMIT 1";
		$Campo_ID="ID_ECUENTA";
		$Campo="ID_ECUENTA";		
		$Tabla="y_ecuenta";	
		$pag_prop=true;	
		$idioma=false;	
	}
}
/*************************/
/*************************/
/***** Noticias **********/
/*************************/
elseif($cnf==506){ 
	if(($accion==$acc01)||($accion==$acc02)){
		$sWhere=encrip_mysql('y_noti.ID_NOTI');
		$sID=$sqlCons[0][506]." WHERE $sWhere=:id LIMIT 1";
		$Campo_ID="ID_NOTI";
		$Campo="HAB_NOTI";		
		$Tabla="y_noti";	
		$pag_prop=true;	
		$idioma=false;	
	}
}
/*************************/
/*************************/
/***  Publicidad *********/
/*************************/
elseif($cnf==507){ //
	if(($accion==$acc01)||($accion==$acc02)){
		$sWhere=encrip_mysql('y_publicidad.ID_PUBL');
		$sID=$sqlCons[0][507]." WHERE $sWhere=:id LIMIT 1";
		$Campo_ID="ID_PUBL";
		$Campo="HAB_PUBL";		
		$Tabla="y_publicidad";	
		$pag_prop=true;	
		$idioma=false;	
	}
}
/*************************/
/*************************/
/**  Especialidades *****/
/*************************/
elseif($cnf==509){  
	if(($accion==$acc01)||($accion==$acc02)){
		$sWhere=encrip_mysql('z_espec.ID_ESPEC');
		$sID=$sqlCons[0][509]." WHERE $sWhere=:id LIMIT 1";
		$Campo_ID="ID_ESPEC";
		$Campo="HAB_ESPEC";		
		$Tabla="z_espec";	
		$pag_prop=true;	
		$idioma=false;	
	}
}
?>
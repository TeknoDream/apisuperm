<?php
/*
---------------------------------------------------------------------------------
                        SUPERMAESTROS BACKOFFICE
                             Proyecto N° 32
                        Archivo: base_msg_032.php
   Descripción: archivo de configuración de mensajes y eliminación de información
--------------------------------------------------------------------------------
*/
if($cnf==500){ // Usuarios
	if(($accion==$acc01)||($accion==$acc02)){
		$sWhere=encrip_mysql('adm_usuarios.ID_USUARIO');
		$s=$sqlCons[0][501]." WHERE $sWhere=:id LIMIT 1";
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		$req->execute();	
		$reg = $req->fetch();
		$Campo=$reg["NOMBRE_U"].' '.$reg["APELLIDO_U"];
	}
}
elseif($cnf==501){ // Remodelaciones
	if(($accion==$acc01)||($accion==$acc02)){
		$sWhere=encrip_mysql('y_proyectos.ID_PROY');
		$s=$sqlCons[0][501]." WHERE $sWhere=:id LIMIT 1";
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		$req->execute();	
		$reg = $req->fetch();
		$Campo=$reg["ID_PROY"];
	}
	elseif($accion==$acc03){
		$sWhere=encrip_mysql("y_proyectos_fotos.ID_FOTO");
		$s=$sqlCons[1][501]." WHERE $sWhere=:id LIMIT 1";
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		$req->execute();	
		$reg = $req->fetch();
		$Campo=$reg["ID_FOTO"];
		$texto='<div data-txtid="txt-1029-0"></div><div data-txtid="txt-1025-0"></div>';
	}
}
elseif($cnf==503){ // Facturas
	if(($accion==$acc01)||($accion==$acc02)){
		$sWhere=encrip_mysql('y_facturas.ID_FACT');
		$s=$sqlCons[0][503]." WHERE $sWhere=:id LIMIT 1";
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		$req->execute();	
		$reg = $req->fetch();
		$Campo=$reg["ID_FACT"];
	}
}
?>

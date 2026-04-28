<?php
//Usuarios
if($output&&$regInf["ID_INFORME"]==2){    
    fputcsv($output, array(
                utf8_decode('id')
            ,   utf8_decode('alias')
            ,   utf8_decode('nombre')
            ,   utf8_decode('apellido')
            ,   utf8_decode('email')
            ,   utf8_decode('fecha_registro')
            ,   utf8_decode('estatus_usuario') 
            ,   utf8_decode('telefono_1') 
            ,   utf8_decode('telefono_2') 
            ,   utf8_decode('tipo_usuario') 
            ,   utf8_decode('verificar_instalador') 
            ,   utf8_decode('gcalif_usuario') 
            ,   utf8_decode('ccalif_usuario') 
            ,   utf8_decode('vcalif_usuario') 
            ,   utf8_decode('calc_destacado')
            ,   utf8_decode('puntos_instalador') 
            ,   utf8_decode('biografia')
            ,   utf8_decode('nro_de_proyectos') 
            ),';', ' ');
    
    $s=$sqlCons[0][500]." WHERE adm_usuarios.FECHA_U BETWEEN STR_TO_DATE(:fechai,'%d/%m/%Y') AND STR_TO_DATE(:fechaf,'%d/%m/%Y')";       
    $req = $dbEmpresa->prepare($s); 
    $req->bindParam(':fechai', $_GET["fechai"]);
    $req->bindParam(':fechaf', $_GET["fechaf"]);          
    $req->execute();

    while($reg = $req->fetch()){
        $row=array(
                $reg["ID_USUARIO"]
            ,   utf8_decode($reg["ALIAS"])
            ,   utf8_decode($reg["NOMBRE_U"])
            ,   utf8_decode($reg["APELLIDO_U"])
            ,   utf8_decode($reg["CORREO_U"])
            ,   $reg["FECHA_U"]
            ,   $reg["HAB_U"]
            ,   utf8_decode($reg["TEL1_USUARIO"])            
            ,   utf8_decode($reg["TEL2_USUARIO"])
            ,   $reg["TYPE_USUARIO"]
            ,   $reg["VERIF_USUARIO"]
            ,   $reg["GCALIF_USUARIO"]
            ,   $reg["CCALIF_USUARIO"]
            ,   $reg["VCALIF_USUARI"] 
            ,   $reg["DEST_USUARIO"] 
            ,   $reg["POINTS_USUARIO"] 
            ,   utf8_decode($reg["BIO_USUARIO"])
            ,   $reg["PROYS_USAURIO"]
            );
        fputcsv($output, $row,';');
    }
}
// Generación de Archivo de Remodelaciones
elseif($output&&$regInf["ID_INFORME"]==3){     
    fputcsv($output, array(
                utf8_decode('id')
            ,   utf8_decode('titulo')
            ,   utf8_decode('descripcion')
            ,   utf8_decode('gcalif')
            ,   utf8_decode('ccalif')
            ,   utf8_decode('vcalif')
            ,   utf8_decode('fecha') 
            ,   utf8_decode('cant_comments') 
            ,   utf8_decode('cant_fotos') 
            ,   utf8_decode('id_usuario') 
            ,   utf8_decode('nombre_usuario') 
            ,   utf8_decode('apellido_usuario')
            ),';', ' ');
    
    $s=$sqlCons[5][501]." WHERE y_proyectos.FECHAS_PROY BETWEEN STR_TO_DATE(:fechai,'%d/%m/%Y') AND STR_TO_DATE(:fechaf,'%d/%m/%Y')";       
    $req = $dbEmpresa->prepare($s); 
    $req->bindParam(':fechai', $_GET["fechai"]);
    $req->bindParam(':fechaf', $_GET["fechaf"]);          
    $req->execute();

    while($reg = $req->fetch()){
        $row=array(
                $reg["ID_PROY"]
            ,   utf8_decode($reg["NOMB_PROY"])
            ,   utf8_decode($reg["DESC_PROY"])
            ,   $reg["GCALIF_PROY"]
            ,   $reg["CCALIF_PROY"]
            ,   $reg["VCALIF_PROY"]
            ,   $reg["FECHAS_PROY"]
            ,   $reg["COMMENTS_PROY"]         
            ,   $reg["C_FOTOS"]
            ,   $reg["ID_USUARIO"]
            ,   utf8_decode($reg["NOMBRE_U"])
            ,   utf8_decode($reg["APELLIDO_U"])
            );
        fputcsv($output, $row,';');
    }
}
// Generación de Archivo de Proyectos
elseif($output&&$regInf["ID_INFORME"]==4){     
    fputcsv($output, array(
                utf8_decode('id')
            ,   utf8_decode('titulo')
            ,   utf8_decode('fecha_publicacion')
            ,   utf8_decode('fecha_apertura')
            ,   utf8_decode('fecha_cierre')
            ,   utf8_decode('comentario')
            ,   utf8_decode('contacto') 
            ,   utf8_decode('especializacion') 
            ,   utf8_decode('ciudad') 
            ,   utf8_decode('id_usuario') 
            ,   utf8_decode('nombre_usuario') 
            ,   utf8_decode('apellido_usuario')
            ),';', ' ');
    
    $s=$sqlCons[0][502]." WHERE x_ofertas.FECHAS_OFERTA BETWEEN STR_TO_DATE(:fechai,'%d/%m/%Y') AND STR_TO_DATE(:fechaf,'%d/%m/%Y')";       
    $req = $dbEmpresa->prepare($s); 
    $req->bindParam(':fechai', $_GET["fechai"]);
    $req->bindParam(':fechaf', $_GET["fechaf"]);          
    $req->execute();

    while($reg = $req->fetch()){
        $row=array(
                $reg["ID_OFERTA"]
            ,   utf8_decode($reg["TITLE_OFERTA"])
            ,   $reg["FECHAS_OFERTA"]
            ,   $reg["FECHAI_OFERTA"]
            ,   $reg["FECHAF_OFERTA"]
            ,   utf8_decode($reg["COMENT_OFERT"])
            ,   utf8_decode($reg["CONTACT_OFERTA"])
            ,   utf8_decode($reg["NAME_ESPEC"])       
            ,   utf8_decode($reg["NOMB_CIUDAD"])
            ,   $reg["ID_USUARIO"]
            ,   utf8_decode($reg["NOMBRE_U_OP"])
            ,   utf8_decode($reg["APELLIDO_U_OP"])
            );
        fputcsv($output, $row,';');
    }
}
// Generación de Archivo de Facturas
elseif($output&&$regInf["ID_INFORME"]==5){     
    fputcsv($output, array(
                utf8_decode('id')
            ,   utf8_decode('puntos')
            ,   utf8_decode('validada')
            ,   utf8_decode('fecha_factura')
            ,   utf8_decode('fecha_publicacion')           
            ,   utf8_decode('id_usuario') 
            ,   utf8_decode('nombre_usuario') 
            ,   utf8_decode('apellido_usuario')
            ),';', ' ');
    
    $s=$sqlCons[0][503]." WHERE y_facturas.FECHAS_FACT BETWEEN STR_TO_DATE(:fechai,'%d/%m/%Y') AND STR_TO_DATE(:fechaf,'%d/%m/%Y')";       
    $req = $dbEmpresa->prepare($s); 
    $req->bindParam(':fechai', $_GET["fechai"]);
    $req->bindParam(':fechaf', $_GET["fechaf"]);          
    $req->execute();

    while($reg = $req->fetch()){
        $row=array(
                $reg["ID_FACT"]
            ,   utf8_decode($reg["VPOINT_FACT"])
            ,   utf8_decode($reg["VALIDAT_FACT"])
            ,   $reg["FECHAF_FACT"]
            ,   $reg["FECHAS_FACT"]
            ,   utf8_decode($reg["ID_USUARIO"])
            ,   utf8_decode($reg["NOMBRE_U_OP"])
            ,   utf8_decode($reg["APELLIDO_U_OP"])      
            
            );
        fputcsv($output, $row,';');
    }
}
//Cotizaciones enviadas
elseif($output&&$regInf["ID_INFORME"]==6){    
    fputcsv($output, array(
                utf8_decode('id')
            ,   utf8_decode('status')
            ,   utf8_decode('fecha')
            ,   utf8_decode('valor')
            ,   utf8_decode('id_maestro')
            ,   utf8_decode('nombre_maestro')
            ,   utf8_decode('apellido_maestro') 
            ,   utf8_decode('id_usuario')
            ,   utf8_decode('nombre_usuario')
            ,   utf8_decode('apellido_usuario') 
            ),';', ' ');
    
    $s=$sqlCons[0][504]." WHERE y_cotizacion.FECHAS_COTIZ BETWEEN STR_TO_DATE(:fechai,'%d/%m/%Y') AND STR_TO_DATE(:fechaf,'%d/%m/%Y')";       
    $req = $dbEmpresa->prepare($s); 
    $req->bindParam(':fechai', $_GET["fechai"]);
    $req->bindParam(':fechaf', $_GET["fechaf"]);          
    $req->execute();

    while($reg = $req->fetch()){
        $row=array(
                $reg["ID_COTIZ"]
            ,   $reg["STATUS_COTIZ"]
            ,   $reg["FECHAS_COTIZ"]
            ,   $reg["VTOT_COTIZ"]
            ,   $reg["ID_USUARIO_M"]
            ,   utf8_decode($reg["NOMBRE_M"])
            ,   utf8_decode($reg["APELLIDO_M"])
            ,   $reg["ID_USUARIO_U"]          
            ,   utf8_decode($reg["NOMBRE_U"])
            ,   utf8_decode($reg["APELLIDO_U"])
            );
        fputcsv($output, $row,';');
    }
}
?>
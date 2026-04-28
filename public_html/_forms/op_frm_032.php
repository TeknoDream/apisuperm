<?php
/*
---------------------------------------------------------------------------------
                        SUPERMAESTROS BACKOFFICE
                             Proyecto N° 32
                        Archivo: op_frm_032.php
             Descripción: archivo de configuración para formularios relacionados
             con las operaciones del archivo op_acc_032.php
--------------------------------------------------------------------------------
Este archivo permite configura los formulario exclusivamente para las operaciones
descritas en el archivo op_acc_032.php como autorizar o desautorizar un instalador, 
validar o invalidar factura.

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
*/
/******************************/
/******************************/
/*** Autorizar Instalador *****/
/******************************/
if($cnf==500&&($det_plus==1||$det_plus==2)){  
    $sWhere=encrip_mysql("adm_usuarios.ID_USUARIO");
    $s=$sqlCons[0][500]." WHERE $sWhere=:idt LIMIT 1";    
    $req = $dbEmpresa->prepare($s); 
    $req->bindParam(':idt', $id_sha_t);
    $req->execute();    
    echo $s;
    if(!$reg = $req->fetch()) exit(0);

    $sub_titulo=$reg["NOMBRE_U"].' '.$reg["APELLIDO_U"];  
    if ($det_plus==1)
        $idText=215;
    else
        $idText=216;
    $titulo="txt-$idText-0";
    $Content="txt-$idText-1";
?>
    <form class="iform col_bg03" name="frm-subir" method="post" action="/toperation">
        <header class="frm-h">
            <h2 class="frm-tit col_titles" data-txtid="<?php echo $titulo?>"></h2>
            <h3 class="frm-stit col_titles2"><?php echo $sub_titulo?></h3>
            <div class="x bt_col_close" data-close="form"><div class="cx"><i class="fa fa-close"></i></div></div>
        </header>
        <div class="frm-body">           
            <div class="p">
                <strong data-txtid="<?php echo $Content?>"></strong><br /><span data-txtid="txt-221-0"></span>
            </div>           
            <input type="hidden" name="md" value="<?php echo $md?>" />        
        </div>
        <div class="message"></div>
        <div class="botones">
            <button class="button bt_col1 light" data-txtid="txt-1006-0"></button>
        </div>
    </form>
<?php
}
elseif($cnf==501&&($det_plus==2||$det_plus==3)){  
    $sWhere=encrip_mysql("y_proyectos.ID_PROY");
    $s=$sqlCons[0][501]." WHERE $sWhere=:idt LIMIT 1";    
    $req = $dbEmpresa->prepare($s); 
    $req->bindParam(':idt', $id_sha_t);
    $req->execute();    
    echo $s;
    if(!$reg = $req->fetch()) exit(0);

    $sub_titulo=$reg["NOMB_PROY"];  
    if ($det_plus==2)
        $idText=227;
    else
        $idText=228;
    $titulo="txt-$idText-0";
    $Content="txt-$idText-1";
?>
    <form class="iform col_bg03" name="frm-subir" method="post" action="/toperation">
        <header class="frm-h">
            <h2 class="frm-tit col_titles" data-txtid="<?php echo $titulo?>"></h2>
            <h3 class="frm-stit col_titles2"><?php echo $sub_titulo?></h3>
            <div class="x bt_col_close" data-close="form"><div class="cx"><i class="fa fa-close"></i></div></div>
        </header>
        <div class="frm-body">           
            <div class="p">
                <strong data-txtid="<?php echo $Content?>"></strong><br /><span data-txtid="txt-221-0"></span>
            </div>           
            <input type="hidden" name="md" value="<?php echo $md?>" />        
        </div>
        <div class="message"></div>
        <div class="botones">
            <button class="button bt_col1 light" data-txtid="txt-1006-0"></button>
        </div>
    </form>
<?php
}
/******************************/
/******************************/
/*** Agregar Fotos a una ******/
/****    Remodelación   *******/
/******************************/
elseif($cnf==501&&$det_plus==1){    
    if(!$nuevo){
        $sWhere=encrip_mysql("y_proyectos_fotos.ID_FOTO");
        $s=$sqlCons[1][501]." WHERE $sWhere=:id LIMIT 1";    
        $req = $dbEmpresa->prepare($s); 
        $req->bindParam(':id', $id_sha);
        $req->execute();    
        if(!$reg = $req->fetch()) exit(0);

        $picname=$_PARAMETROS["S3_URL4"].ImgBlanc($reg["M_IMG"],array(
                                            'PROYECTO'=>$_PROYECTO
                                        ,   'EMPRESA'  =>$_EMPRESA
                                        ,   'MODULO'    =>$cnf
                                        ,   'OBJETO'    =>$reg["ID_FOTO"]
                                        ,   'TP'        =>'img'
                                        ,   'EXT'       =>$reg["F_EXT"]
                                        ,   'All'       =>false
                                        ,   'Cual'      =>'t02'));
        
    }   
    $main=$reg['MAIN_FOTO'];
    $titulo="txt-168-0";
    $sub_titulo="txt-212-0";
    //TITULO Y SUB TITULO

?>
    <form class="iform col_bg03" name="frm-subir" method="post" action="/toperation">
        <header class="frm-h">
            <h2 class="frm-tit col_titles" data-txtid="<?php echo $titulo ?>"></h2>
            <h3 class="frm-stit col_titles2" data-txtid="<?php echo $sub_titulo?>"></h3>
            <div class="x bt_col_close" data-close="form"><div class="cx"><i class="fa fa-close"></i></div></div>
        </header>   
        <div class="frm-body">   

            <label class="frm-label req" for="title" data-txtid="txt-119-0"></label>
            <input class="input" type="text" name="title" id="title" maxlength="50" value="<?php echo $reg["TITLE_FOTO"]?>" data-required="true"/>

            <label class="frm-label" for="main" data-txtid="txt-213-0"></label>    
            <div id="main" data-buttonset="true">       
                <input type="radio" id="main1" name="main" <?php echo $main?'checked="checked"':''?> value="1" /><label for="main1" data-txtid="txt-194-0"></label>
                <input type="radio" id="main2" name="main" <?php echo $main?'checked="checked"':''?> value="2" /><label for="main2" data-txtid="txt-194-1"></label>
            </div>

            <!-- Imagenes -->
            <fieldset class="fieldset">
                <legend class="legend medium" data-txtid="txt-1004-0"></legend>
                <div class="wrapimg display col_bg03">
                    <div class="_fix"></div>
                    <div class="stdImg _zfix_00" style="background-image: url(<?php echo $picname; ?>)"></div>
                </div>
                <label class="frm-label" for="frm-cimg"><span data-txtid="txt-1000-0"></span></label>
                <div class="options" id="frm-cimg"> 
                    <input type="radio" id="frm-cimg1" name="imagen" value="1" data-expand="true"                     /><label for="frm-cimg1" data-txtid="txt-1002-0"></label>
                    <input type="radio" id="frm-cimg2" name="imagen" value="2" data-expand="true" checked="checked"   /><label for="frm-cimg2" data-txtid="txt-1003-0"></label>
                    <input type="radio" id="frm-cimg3" name="imagen" value="3" data-expand="true"                     /><label for="frm-cimg3" data-txtid="txt-1005-0"></label>
                </div>
                <div class="hide">
                    <input class="input" type="file" name="imagen" id="frm-file" />
                </div>
            </fieldset>
            <!-- end -->   

            <input type="hidden" name="md" value="<?php echo $md?>" />
        </div>
        <div class="message"></div>
        <div class="botones">
            <button class="button bt_col1 light" data-txtid="txt-1006-0"></button>
        </div>
    </form>
<?php
}
/*******************************/
/*******************************/
/*** Validar/Invalidar**********/
/*****  Factura ***************/
/******************************/

if($cnf==503&&($det_plus==1||$det_plus==2)){  
    $sWhere=encrip_mysql("y_facturas.ID_FACT");
    $s=$sqlCons[0][503]." WHERE $sWhere=:idt LIMIT 1";    
    $req = $dbEmpresa->prepare($s); 
    $req->bindParam(':idt', $id_sha_t);
    $req->execute();    
    echo $s;
    if(!$reg = $req->fetch()) exit(0);

    $sub_titulo="Factura Nº ".$reg["ID_FACT"];  
    
    if ($det_plus==1)
        $idText=217; //autorizar
    else
        $idText=218;//desautorizar
    $titulo="txt-$idText-0";
    $Content="txt-$idText-1";
?>
    <form class="iform col_bg03" name="frm-subir" method="post" action="/toperation">
        <header class="frm-h">
            <h2 class="frm-tit col_titles" data-txtid="<?php echo $titulo?>"></h2>
            <h3 class="frm-stit col_titles2"><?php echo $sub_titulo?></h3>
            <div class="x bt_col_close" data-close="form"><div class="cx"><i class="fa fa-close"></i></div></div>
        </header>
        <div class="frm-body">           
            <div class="p">
                <strong data-txtid="<?php echo $Content?>"></strong><br /><span data-txtid="txt-221-0"></span>
            </div>           
            <input type="hidden" name="md" value="<?php echo $md?>" />        
        </div>
        <div class="message"></div>
        <div class="botones">
            <button class="button bt_col1 light" data-txtid="txt-1006-0"></button>
        </div>
    </form>
<?php
}
?>
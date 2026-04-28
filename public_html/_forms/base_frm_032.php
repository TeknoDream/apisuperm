<?php
/*
--------------------------------------------------------------------------------------
                        SUPERMAESTROS BACKOFFICE
                             Proyecto N° 32
                          Archivo: base_frm_032.php
             Descripción: archivo de configuración de formularios
--------------------------------------------------------------------------------------
Este archivo configura y muestra todos los formularios del backoffice para la inserción
y edición de los datos
Se identifica los formularios de acuerdo al número de módulo.

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
/*************************/
/*************************/
/**** Usuarios ***********/
/*************************/ 
if($cnf==500){ 
    if(!$nuevo){        
        $sWhere=encrip_mysql("adm_usuarios.ID_USUARIO");
        $s=$sqlCons[0][500]." WHERE $sWhere=:id LIMIT 1";        
        $req = $dbEmpresa->prepare($s); 
        $req->bindParam(':id', $id_sha);

        $req->execute();    
        if(!$reg = $req->fetch()) exit(0);  
        $sub_titulo=$reg["NOMBRE_U"].' '.$reg["APELLIDO_U"];

        // Se configuran los parámetros para subir la imagen del objeto
        // al servidor S3
        $idImg=$reg["ID_USUARIO"];
        $picname=$_PARAMETROS["S3_URL4"].ImgBlanc($reg["M_IMG"],array(
                                            'PROYECTO'=>$_PROYECTO
                                        ,   'EMPRESA'  =>$_EMPRESA
                                        ,   'MODULO'    =>36
                                        ,   'OBJETO'    =>$idImg
                                        ,   'TP'        =>'img'
                                        ,   'EXT'       =>$reg["F_EXT"]
                                        ,   'All'       =>false
                                        ,   'Cual'      =>'t02'));
    }

   $type=($reg["TYPE_USUARIO"]==''?0:$reg["TYPE_USUARIO"]);  //Tipo de usuario: 0=estandar 1=instalador
?>
    <form class="iform big col_bg03" name="frm-subir" method="post" action="/tedit">
        <header class="frm-h">
            <h2 class="frm-tit col_titles" data-txtid="<?php echo $nuevo?'txt-1009-0':'txt-1017-0'?>"></h2>
            <h3 class="frm-stit col_titles2"><?php echo $sub_titulo?></h3>
            <div class="x bt_col_close" data-close="form"><div class="cx"><i class="fa fa-close"></i></div></div>
        </header>
        <div class="frm-body">
            <div class="col50">
                
                <label class="frm-label req" for="nomb" data-txtid="txt-101-1"></label>
                <input class="input" type="text" name="nomb" id="nomb" maxlength="35" value="<?php echo $reg["NOMBRE_U"]?>" data-required="true"/>

                <label class="frm-label req" for="apel" data-txtid="txt-102-1"></label>
                <input class="input" type="text" name="apel" id="apel" maxlength="35" value="<?php echo $reg["APELLIDO_U"]?>" data-required="true"/>

                <label class="frm-label req" for="email" data-txtid="txt-192-0"></label>
                <input class="input" type="email" name="email" id="email" maxlength="100" value="<?php echo $reg["CORREO_U"]?>" data-required="true"/>

                <label class="frm-label req" for="tel1" data-txtid="txt-174-1"></label>
                <input class="input" type="tel" name="tel1" id="tel1" maxlength="20" value="<?php echo $reg["TEL1_USUARIO"]?>" data-required="true"/>

                <label class="frm-label req" for="tel2" data-txtid="txt-175-0"></label>
                <input class="input" type="tel" name="tel2" id="tel2" maxlength="20" value="<?php echo $reg["TEL2_USUARIO"]?>" />

                <!-- Imagenes -->
                <fieldset class="fieldset">
                    <legend class="legend medium" data-txtid="txt-1004-0"></legend>
                    <div class="wrapimg display col_bg03">
                        <div class="_fix"></div>
                        <div class="stdImg _zfix_00" style="background-image: url(<?php echo $picname; ?>)"></div>
                    </div>
                    <label class="frm-label" for="frm-cimg"><span data-txtid="txt-1000-0"></span></label>
                    <!-- Opcion 1= modificar la imagen, Opcion 2= mantener la imagen actual, Opcion 3=Eliminar la imagen -->
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
            </div><!--
         --><div class="col50">
                <label class="frm-label" for="type" data-txtid="txt-177-0"></label>    
                <div id="type" data-buttonset="true">       
                    <input type="radio" id="type1" name="type" data-expid="type1" data-group="type" value="0" <?php echo $type==0?'checked="checked"':'' ?>/><label for="type1" data-txtid="txt-176-0"></label>
                    <input type="radio" id="type2" name="type" data-expid="type2" data-group="type" value="1" <?php echo $type==1?'checked="checked"':'' ?>/><label for="type2" data-txtid="txt-100-0"></label>  
                    </div>


                <fieldset class="fieldset" data-id="type2" data-grupo="type" <?php echo $type!=1?'style="display:none"':'' ?>>
                  
                    <label class="frm-label req" for="bio" data-txtid="txt-178-0"></label>
                    <textarea class="input" name="bio" id="bio" maxlength="500"  cols="" rows=""><?php echo $reg["BIO_USUARIO"]?></textarea>
            

                    <!--Especialidades-->
                    <fieldset class="fieldset">
                        <legend class="legend" data-txtid="txt-128-0"></legend>
                        <!--  data-tp hace referencia al archivo soloinfo_032.php
                        que permite configurar listas y formularios dinamicos dentro
                        del formulari principal -->
                        <div data-tp="1" data-carga="true" data-md="<?php echo $md?>"></div>
                    </fieldset> 
                       <!--URLS-->
                    <fieldset class="fieldset">
                        <legend class="legend" data-txtid="txt-196-0"></legend>
                        <div data-tp="2" data-carga="true" data-md="<?php echo $md?>"></div>
                    </fieldset>
                </fieldset>
                   
            </div>         
            <input type="hidden" name="md" id="md" value="<?php echo $md?>" />
        </div>
        <div class="message"></div>
        <div class="botones">
            <button class="button bt_col1 light" data-txtid="txt-1006-0"></button>
        </div>
    </form>
<?php 
}
/*************************/
/**** Remodelaciones *****/
/*************************/ 
elseif($cnf==501){
     if(!$nuevo){    
        $sWhere=encrip_mysql("y_proyectos.ID_PROY");
        $s=$sqlCons[0][501]." WHERE $sWhere=:id LIMIT 1";        
        $req = $dbEmpresa->prepare($s); 
        $req->bindParam(':id', $id_sha);
        $req->execute();    
        if(!$reg = $req->fetch()) exit(0);  
        $sub_titulo=$reg["NOMB_PROY"];
        $id_user=$reg["ID_USUARIO"];
        $name_user=$reg["NOMBRE_U"].' '.$reg["APELLIDO_U"];
        
    }
?>
        
        <form class="iform col_bg03" name="frm-subir" method="post" action="/tedit">
            <header class="frm-h">
                <h2 class="frm-tit col_titles" data-txtid="<?php echo $nuevo?'txt-1009-0':'txt-1017-0'?>"></h2>
                <h3 class="frm-stit col_titles2"><?php echo $sub_titulo?></h3>
                <div class="x bt_col_close" data-close="form"><div class="cx"><i class="fa fa-close"></i></div></div>
             </header> 
         <div class="frm-body"> 
            <!-- Existente -->                
            <label class="frm-label" for="usuario" data-txtid="txt-100-1"></label>
            <input class="input asearch" type="text" name="usuario" id="usuario" value="<?php echo  $name_user ?>" data-autocomplete="true" data-tp="5000"/>
            <input type="hidden" name="id_user" data-name="id_isntl"  value="<?php echo $id_user?>"/>


            <label class="frm-label req" for="nom" data-txtid="txt-101-0"></label>
            <input class="input" type="text" name="nom" id="nom" maxlength="70" value="<?php echo $reg["NOM_COMB"]?>" data-required="true"/>
            <label class="frm-label" for="descp" data-txtid="txt-103-0"></label>
            <textarea class="input" name="descp" id="descp" maxlength="200"  cols="" rows=""><?php echo $reg["DESC_PROY"];?></textarea>
        </div>
        <input type="hidden" name="md" id="md" value="<?php echo $md?>" />
    
        <div class="message"></div>
        <div class="botones">
            <button class="button bt_col1 light" data-txtid="txt-1006-0"></button>
        </div>
    </form>
<?php
}
/*************************/
/**** Proyectos **********/
/*************************/ 
elseif($cnf==502){ 
    if(!$nuevo){                                            
        $sWhere=encrip_mysql(" x_ofertas.ID_OFERTA");
        $s=$sqlCons[0][502]." WHERE $sWhere=:id LIMIT 1";        
        $req = $dbEmpresa->prepare($s); 
        $req->bindParam(':id', $id_sha);

        $req->execute();    
        if(!$reg = $req->fetch()) exit(0);  
        $sub_titulo=$reg["TITLE_OFERTA"];

    
        $fechaI=$reg["FECHAI_OFERTA"];
        $fechaF=$reg["FECHAF_OFERTA"];
        $ciudad=$reg["ID_CIUDAD"];
    }
    else{           
        $fechaOBJ = new Date(); 
        $fechaI=$fechaOBJ->format('d/m/Y');
        $fechaOBJ1A=$fechaOBJ->add(new DateInterval('P1Y'));
        $fechaF=$fechaOBJ1A->format('d/m/Y');
       
    }    
?>
    <form class="iform col_bg03" name="frm-subir" method="post" action="/tedit">
        <header class="frm-h">
            <h2 class="frm-tit col_titles" data-txtid="<?php echo $nuevo?'txt-1009-0':'txt-1017-0'?>"></h2>
            <h3 class="frm-stit col_titles2"><?php echo $sub_titulo?></h3>
            <div class="x bt_col_close" data-close="form"><div class="cx"><i class="fa fa-close"></i></div></div>
        </header>
        <div class="frm-body">
                
            <label class="frm-label req" for="tit" data-txtid="txt-119-0"></label>
            <input class="input" type="text" name="tit" id="tit" maxlength="70" value="<?php echo $reg["TITLE_OFERTA"]?>" data-required="true"/>

            <label class="frm-label req" for="contact" data-txtid="txt-230-0"></label>
            <input class="input" type="text" name="contact" id="contact" maxlength="100" value="<?php echo $reg["CONTACT_OFERTA"]?>"/>
            
            <label class="frm-label req" for="fechaI" data-txtid="txt-126-1"></label>
            <input class="input" type="text" name="fechaI" id="fechaI" data-tipo="date" value="<?php echo $fechaI ?>" data-required="true"/>
            
            <label class="frm-label req" for="fechaF" data-txtid="txt-127-1"></label>
            <input class="input" type="text" name="fechaF" id="fechaF" data-tipo="date" value="<?php echo $fechaF ?>" data-required="true"/>
            <!--Ciudades-->
            <label class="frm-label req" for="ciudad_ori" data-txtid="txt-165-0"></label>
                <select class="input" name="ciudad_ori" id="ciudad_ori" data-required="true">
                    <?php 
                        $s=$sqlCons[1][45].' WHERE fac_ciudades.ID_CIUDAD IN (SELECT ID_CIUDAD FROM adm_ciudad) '.$sqlOrder[1][45];
                        $req0 = $dbEmpresa->prepare($s);
                        $req0->execute();    
                        echo crear_select($req0,'ID_CIUDAD','NOMB_CIUDAD',$reg["ID_CIUDAD"],2,'txt-140-1');
                    ?>
                </select>

            <label class="frm-label" for="comen" data-txtid="txt-201-0"></label>
            <textarea class="input" name="comen" id="comen" maxlength="150"  cols="" rows=""><?php echo $reg["COMENT_OFERT"]?></textarea>
                             
            <!--Especialidades-->
            <label class="frm-label req" for="espec" data-txtid="txt-162-0"></label>
                <select class="input" name="espec" id="espec" data-required="true">
                    <?php 
                        $s=$sqlCons[0][509].' WHERE HAB_ESPEC=0 '.$sqlOrder[0][509];
                        $req0 = $dbEmpresa->prepare($s);
                        $req0->execute();    
                        echo crear_select($req0,'ID_ESPEC','NAME_ESPEC',$reg["ID_ESPEC"],2,'txt-140-1');
                    ?>
                </select>
                             
            <input type="hidden" name="md" id="md" value="<?php echo $md?>" />
        </div>
        <div class="message"></div>
        <div class="botones">
            <button class="button bt_col1 light" data-txtid="txt-1006-0"></button>
        </div>
    </form>
<?php 
}
/*************************/
/**** NOTICIAS***********/
/*************************/ 
elseif($cnf==506){ 
    if(!$nuevo){        
        $sWhere=encrip_mysql("y_noti.ID_NOTI");
        $s=$sqlCons[0][506]." WHERE $sWhere=:id LIMIT 1";        
        $req = $dbEmpresa->prepare($s); 
        $req->bindParam(':id', $id_sha);
        $req->execute();    
        if(!$reg = $req->fetch()) exit(0);  

        $idImg=$reg["ID_NOTI"];
        $picname=$_PARAMETROS["S3_URL4"].ImgBlanc($reg["M_IMG"],array(
                                            'PROYECTO'=>$_PROYECTO
                                        ,   'EMPRESA'  =>$_EMPRESA
                                        ,   'MODULO'    =>$cnf
                                        ,   'OBJETO'    =>$idImg
                                        ,   'TP'        =>'img'
                                        ,   'EXT'       =>$reg["F_EXT"]
                                        ,   'All'       =>false
                                        ,   'Cual'      =>'t02'));


   
    }
    $activa=$reg['ACTIV_NOTI']==1;
    $tipo=$reg['TYPE_NOTI']==''?1:$reg['TYPE_NOTI'];
?>
    <form class="iform col_bg03" name="frm-subir" method="post" action="/tedit">
        <header class="frm-h">
            <h2 class="frm-tit col_titles" data-txtid="<?php echo $nuevo?'txt-1009-0':'txt-1017-0'?>"></h2>
            <h3 class="frm-stit col_titles2"><?php echo $sub_titulo?></h3>
            <div class="x bt_col_close" data-close="form"><div class="cx"><i class="fa fa-close"></i></div></div>
        </header>
        <div class="frm-body">          
            <label class="frm-label req" for="title" data-txtid="txt-119-0"></label>
            <input class="input" type="text" name="title" id="title" maxlength="70" value="<?php echo $reg["TITLE_NOTI"]?>" data-required="true"/>

            <label class="frm-label req" for="meta" data-txtid="txt-120-0"></label>
            <input class="input" type="text" name="meta" id="meta" maxlength="100" value="<?php echo $reg["MTITLE_NOTI"]?>" data-required="true"/>

            <label class="frm-label req" for="desc" data-txtid="txt-121-0"></label>
            <textarea class="input" name="desc" id="desc"  cols="" rows=""><?php echo imprimir($reg["MDESC_NOTI"],2) ?></textarea> 

            <label class="frm-label" for="tipo" data-txtid="txt-138-0"></label>    
            <div id="tipo" data-buttonset="true">       
                <input type="radio" id="tipo1" name="tipo" value="1" <?php echo $tipo==1?'checked="checked"':'' ?>/><label for="tipo1" data-txtid="txt-138-1"></label>
                <input type="radio" id="tipo2" name="tipo" value="2" <?php echo $tipo==2?'checked="checked"':'' ?>/><label for="tipo2" data-txtid="txt-139-1"></label>                
            </div>

            <label class="frm-label" for="activa" data-txtid="txt-133-0"></label>    
            <div id="activa" data-buttonset="true">       
                <input type="radio" id="activa1" name="activa" value="1" <?php echo $activa?'checked="checked"':'' ?>/><label for="activa1" data-txtid="txt-1002-0"></label>
                <input type="radio" id="activa2" name="activa" value="2" <?php echo !$activa?'checked="checked"':'' ?>/><label for="activa2" data-txtid="txt-1003-0"></label>                
            </div>

            <label class="frm-label" for="content" data-txtid="txt-122-0"></label>
            <textarea class="input" data-tipo="richtext" name="content" id="content"  cols="" rows=""><?php echo imprimir($reg["CONT_NOTI"],2) ?></textarea> 

            <br />

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

                
            <input type="hidden" name="md" id="md" value="<?php echo $md?>" />
       </div>
        <div class="message"></div>
        <div class="botones">
            <button class="button bt_col1 light" data-txtid="txt-1006-0"></button>
        </div>
    </form>
<?php
}
/***************************/
/**** Estado de Cuenta *****/
/***************************/ 
elseif($cnf==505){ 
    if(!$nuevo){        
        $sWhere=encrip_mysql("y_ecuenta.ID_ECUENTA");
        $s=$sqlCons[0][505]." WHERE $sWhere=:id LIMIT 1";        
        $req = $dbEmpresa->prepare($s); 
        $req->bindParam(':id', $id_sha);
        $req->execute();    
        if(!$reg = $req->fetch()) exit(0);  
        $sub_titulo='Estado de Cuenta de '.$reg["NOMBRE_U_M"].' '.$reg["APELLIDO_U_M"];
    }

    
?>
    <form class="iform col_bg03" name="frm-subir" method="post" action="/tedit">
        <header class="frm-h">
            <h2 class="frm-tit col_titles" data-txtid="<?php echo $nuevo?'txt-1009-0':'txt-1017-0'?>"></h2>
            <h3 class="frm-stit col_titles2"><?php echo $sub_titulo?></h3>
            <div class="x bt_col_close" data-close="form"><div class="cx"><i class="fa fa-close"></i></div></div>
        </header>
        <div class="frm-body">    
            <!-- Buscar instalador
            La opcion de busqueda y autocompletacion se configura en el archivo autocomplete_032.php-->
            <label class="frm-label" for="desc_padre" data-txtid="txt-100-0"></label>
            <input class="input asearch" type="text" name="busc_instal" id="busc_instal" data-autocomplete="true" data-tp="5000"/>
            <input type="hidden" name="id_isntl" data-name="id_isntl"   data-required="true"/>
            <label class="frm-label req" for="comen" data-txtid="txt-201-0"></label>
            <input class="input" type="text" name="comen" id="comen" maxlength="70"  data-required="true"/>
          
           <!-- Agregar/Quitar Puntos-->
            <label class="frm-label" for="accPunt"><span data-txtid="txt-225-0"></span></label>
                <div class="options" id="accPunt"> 
                    <input type="radio" id="accPunt1" name="accion" value="1" data-expand="true" checked="checked" /><label for="accPunt1" data-txtid="txt-231-0"></label>
                    <input type="radio" id="accPunt2" name="accion" value="2" /><label for="accPunt2" data-txtid="txt-232-0"></label>
                </div>   
                
            <label class="frm-label req" for="punt" data-txtid="txt-104-0"></label>
            <input class="input" type="number" name="punt" id="punt" maxlength="35" data-required="true"/>
               
        </div>         
            <input type="hidden" name="md" id="md" value="<?php echo $md?>" />
        </div>
        <div class="message"></div>
        <div class="botones">
            <button class="button bt_col1 light" data-txtid="txt-1006-0"></button>
        </div>
    </form>
<?php
}
/***************************/
/******* Publicidad ********/
/***************************/
elseif($cnf==507){ 
    if(!$nuevo){        
        $sWhere=encrip_mysql("y_publicidad.ID_PUBL");
        $s=$sqlCons[0][507]." WHERE $sWhere=:id LIMIT 1";        
        $req = $dbEmpresa->prepare($s); 
        $req->bindParam(':id', $id_sha);
        $req->execute();    
        if(!$reg = $req->fetch()) exit(0);  

        $sub_titulo=$reg["NAME_PUBL"];
        $idImg=$reg["ID_PUBL"];
        $picname=$_PARAMETROS["S3_URL4"].ImgBlanc($reg["M_IMG"],array(
                                            'PROYECTO'  =>$_PROYECTO
                                        ,   'EMPRESA'   =>$_EMPRESA
                                        ,   'MODULO'    =>$cnf
                                        ,   'OBJETO'    =>$reg["ID_PUBL"]
                                        ,   'TP'        =>'img'
                                        ,   'EXT'       =>$idImg
                                        ,   'All'       =>false
                                        ,   'Cual'      =>'t02'));


        $fechaPI=$reg["FECHAI_PUBL"];
        $fechaPF=$reg["FECHAF_PUBL"];
        $id_publ=$reg["ID_PUBL"];
    }
    else{           
        $fechaOBJ = new DateTime(); 
        $fechaPI=$fechaOBJ->format('d/m/Y H:i');
        $fechaOBJ1A=$fechaOBJ->add(new DateInterval('P1Y'));
        $fechaPF=$fechaOBJ1A->format('d/m/Y H:i');
        $id_publ=0;
    }    


    $tipop=($reg["TYP_PUBL"]==''?1:$reg["TYP_PUBL"]);  
    $activa=($reg["ACTI_PUBL"]==''?1:$reg["ACTI_PUBL"]);
    $movil=($reg["MOVIL_PUBL"]==''||$reg["MOVIL_PUBL"]==0)?2:$reg["MOVIL_PUBL"];  
?>
    <form class="iform col_bg03" name="frm-subir" method="post" action="/tedit">
        <header class="frm-h">
            <h2 class="frm-tit col_titles" data-txtid="<?php echo $nuevo?'txt-1009-0':'txt-1017-0'?>"></h2>
            <h3 class="frm-stit col_titles2"><?php echo $sub_titulo?></h3>
            <div class="x bt_col_close" data-close="form"><div class="cx"><i class="fa fa-close"></i></div></div>
        </header>
        <div class="frm-body">

            <label class="frm-label req" for="nombre" data-txtid="txt-125-0"></label>
            <input class="input" type="text" name="nombre" id="nombre" maxlength="40" value="<?php echo $reg["NAME_PUBL"]?>"/>

            <label class="frm-label req" for="title" data-txtid="txt-119-0"></label>
            <input class="input" type="text" name="title" id="title" maxlength="60" value="<?php echo $reg["TITLE_PUBL"]?>"/>
            
            <fieldset class="fieldset">        
                <legend class="legend" data-txtid="txt-126-0"></legend>  

                <label class="frm-label req" for="fechaPI" data-txtid="txt-126-1"></label>
                <input class="input" type="text" name="fechaPI" id="fechaPI" data-tipo="datetime" value="<?php echo $fechaPI ?>" data-required="true"/>
                
                <label class="frm-label req" for="fechaPF" data-txtid="txt-127-1"></label>
                <input class="input" type="text" name="fechaPF" id="fechaPF" data-tipo="datetime" value="<?php echo $fechaPF ?>" data-required="true"/>

            </fieldset>

            <label class="frm-label" for="activa" data-txtid="txt-128-0"></label>    
            <div id="activa" data-buttonset="true">       
                <input type="radio" id="activa1" name="activa" value="1" <?php echo $activa==1?'checked="checked"':'' ?>/><label for="activa1" data-txtid="txt-1002-0"></label>
                <input type="radio" id="activa2" name="activa" value="2" <?php echo $activa==2?'checked="checked"':'' ?>/><label for="activa2" data-txtid="txt-1003-0"></label>                
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

            <input type="hidden" name="md" id="md" value="<?php echo $md?>" />
        </div>
        <div class="message"></div>
        <div class="botones">
            <button class="button bt_col1 light" data-txtid="txt-1006-0"></button>
        </div>
    </form>
<?php
}
//***ESPECIALIDADES **
elseif($cnf==509){ 
    if(!$nuevo){        
        $sWhere=encrip_mysql("z_espec.ID_ESPEC");
        $s=$sqlCons[0][509]." WHERE $sWhere=:id LIMIT 1";        
        $req = $dbEmpresa->prepare($s); 
        $req->bindParam(':id', $id_sha);
        $req->execute();    
        if(!$reg = $req->fetch()) exit(0);  
        $sub_titulo=$reg["NAME_ESPEC"];
      
    }
   
?>
    <form class="iform min col_bg03" name="frm-subir" method="post" action="/tedit">
        <header class="frm-h">
            <h2 class="frm-tit col_titles" data-txtid="<?php echo $nuevo?'txt-1009-0':'txt-1017-0'?>"></h2>
            <h3 class="frm-stit col_titles2"><?php echo $sub_titulo?></h3>
            <div class="x bt_col_close" data-close="form"><div class="cx"><i class="fa fa-close"></i></div></div>
        </header>
        <div class="frm-body">
                
            <label class="frm-label req" for="espec" data-txtid="txt-101-0"></label>
            <input class="input" type="text" name="espec" id="espec" maxlength="35" value="<?php echo $reg["NAME_ESPEC"]?>" data-required="true"/>
        
              
        </div>         
            <input type="hidden" name="md" id="md" value="<?php echo $md?>" />
        </div>
        <div class="message"></div>
        <div class="botones">
            <button class="button bt_col1 light" data-txtid="txt-1006-0"></button>
        </div>
    </form>
<?php
}

 ?>

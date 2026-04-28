<?php
$id_sha=encrip($_USUARIO);
$m_md=$id_sha.encrip(36,2);

$s=$sqlCons[1][0]." WHERE adm_usuarios.ID_USUARIO=:id LIMIT 1";
$req = $dbEmpresa->prepare($s); 
$req->bindParam(':id', $_sysvars_r["id"]);
$req->execute();    
$usuario_actual = $req->fetch();


?>
<div class="md_carga col_bg03" data-tpage="3" data-cnf="<?php echo $cnf?>">
    <!--LATERAL-->
    <nav class="cnf-nav col_bg01">
        <ul class="ul light">
            <li class="li bt_col2 _selected" data-idtab="1"><span data-txtid="txt-1082-0"></span><i class="fa fa-caret-up"></i></li><!--
         --><li class="li bt_col2" data-idtab="2"><span data-txtid="txt-1104-0"></span><i class="fa fa-caret-up"></i></li><!--
         --><li class="li bt_col2" data-idtab="3"><span data-txtid="txt-1072-0"></span><i class="fa fa-caret-up"></i></li><!--
         --><li class="li bt_col2" data-idtab="4"><span data-txtid="txt-1103-0"></span><i class="fa fa-caret-up"></i></li><!--
         --><li class="li bt_col2" data-idtab="5"><span data-txtid="txt-1105-0"></span><i class="fa fa-caret-up"></i></li>
        </ul>
    </nav>
    <!--FIN DE LATERAL-->

    <!--CONTENIDO DE LOGIN-->
    <div class="cnf-lat col_bg02">        
        <h2 class="cnf-tit" data-idtab="1"><span data-txtid="txt-1082-0"></span></h2>
        <section class="cnf-cont" data-tab="1">
            <form class="mform" name="frm-basisc" method="post" action="/tperfil"> 
                <fieldset class="fieldset">
                    <legend class="legend medium" data-txtid="txt-1164-0"></legend>  
                    <input type="hidden" value="1" name="m" />
                    <label class="frm-label req" for="user" data-txtid="txt-1094-0"></label>
                    <input class="input" type="text" name="user" id="user" maxlength="15" value="<?php echo imprimir($usuario_actual["ALIAS"])?>" data-required="true"/>
                    
                    <label class="frm-label req" for="nombres" data-txtid="txt-1069-0"></label>
                    <input class="input" type="text" name="nombres" id="nombres" maxlength="35" value="<?php echo imprimir($usuario_actual["NOMBRE_U"])?>" data-required="true"/>        
                   
                    <label class="frm-label req" for="apellidos" data-txtid="txt-1068-0"></label>
                    <input class="input" type="text" name="apellidos" id="apellidos" maxlength="35" value="<?php echo imprimir($usuario_actual["APELLIDO_U"])?>" data-required="true"/>
                                                        
                    <label class="frm-label req" for="idioma" data-txtid="txt-1133-0"></label>
                    <select class="input" name="idioma" id="idioma" data-required="true">
                    <?php 
                        $s=$sqlCons[1][76]." WHERE fac_idioma.HAB_IDIOMA=0 ".$sqlOrder[1][76];
                        $req = $dbEmpresa->prepare($s);
                        $req->execute();
                        echo crear_select($req,'ID_IDIOMA','IDIOMA',$usuario_actual["ID_IDIOMA"],0);
                    ?>
                    </select>
                                
                    <label class="frm-label req" for="moneda" data-txtid="txt-1156-0"></label>
                    <select class="input" name="moneda" id="moneda">
                    <?php 
                        $s=$sqlCons[1][79]." WHERE fac_moneda.HAB_MONEDA=0 ".$sqlOrder[1][79];
                        $req = $dbEmpresa->prepare($s);
                        $req->bindParam(':idioma', $usuario_actual["ID_IDIOMA"]);    //ESTA EN LA CONSULTA
                        $req->execute();
                        echo crear_select($req,'ID_MONEDA','MONEDA',$usuario_actual["ID_MONEDA"],0);
                    ?>
                    </select>
                                  
                    <label class="frm-label req" for="tdoc" data-txtid="txt-1100-0"></label>
                    <select class="input" name="tdoc" id="tdoc" data-required="true">
                    <?php 
                        $s=$sqlCons[1][46]." WHERE ID_IDIOMA=$_IDIOMA AND HAB_TDOCUMENTO=0 ".$sqlOrder[1][46];
                        $req = $dbEmpresa->prepare($s);
                        $req->execute();
                        echo crear_select($req,'ID_DOCUMENTO','DOCUMENTO',$usuario_actual["ID_DOCUMENTO"],0);
                    ?>
                    </select>           
                    
                    <label class="frm-label" for="doc" data-txtid="txt-1101-0"></label>
                    <input class="input" type="text" name="doc" id="doc" maxlength="15" value="<?php echo imprimir($usuario_actual["DOCUMENTO"])?>"/>
                    
                    
                    <label class="frm-label req" for="genero" data-txtid="txt-1070-0"></label>
                    <select class="input" name="genero" id="genero" data-required="true">
                        <?php
                        $s=$sqlCons[1][7]." WHERE ID_IDIOMA=$_IDIOMA  ".$sqlOrder[1][7];
                        $req = $dbEmpresa->prepare($s);
                        $req->execute();
                        echo crear_select($req,'ID_GENERO','GENERO',$usuario_actual["ID_GENERO"],0);
                        ?>
                    </select>
                    
                    <label class="frm-label" for="tel" data-txtid="txt-1059-0"></label>
                    <input class="input" type="tel" name="tel" id="tel" value="<?php echo imprimir($usuario_actual["TELEFONO_U"])?>"/>  
                     
                    <label class="frm-label req" for="tel2"><span data-txtid="txt-1132-0"></span> (<span data-txtid="txt-1132-1"></span>)</label>
                    <input class="input" type="tel2" name="tel2" id="tel2" value="<?php echo imprimir($usuario_actual["TELEFONO2_U"])?>" data-required="true"/>      
                  
                    <label class="frm-label" for="dir" data-txtid="txt-1065-0"></label>
                    <input class="input" type="text" name="dir" id="dir" value="<?php echo imprimir($usuario_actual["DIRECCION_U"])?>"/>
                    
                    <label class="frm-label req" for="tz" data-txtid="txt-1102-0"></label>
                    <select class="input" name="tz" id="tz" data-required="true">
                        <?php
                        $s=$sqlCons[1][8]."  ".$sqlOrder[1][8];
                        $req = $dbEmpresa->prepare($s);
                        $req->execute();
                        echo crear_select($req,'ID_TZ','TZ_DIFEARM',$usuario_actual["ID_TZ"],0);
                        ?>
                    </select> 
                </fieldset>
                <div class="message"></div>
                <div class="botones">
                    <button class="button bt_col1 light" data-txtid="txt-1085-0"></button>
                </div>                 
            </form> 
        </section>  

        <h2 class="cnf-tit" data-idtab="2"><span data-txtid="txt-1104-0"></span></h2>
        <section class="cnf-cont" data-tab="2">

            <form class="mform" name="frm-rs" method="post" action="/tperfil">
                <input type="hidden" value="6" name="m" />
                <div data-tp="1" data-carga="true" data-md="<?php echo $m_md?>"></div> 
                <div class="botones">
                    <button class="button bt_col1 light" data-txtid="txt-1085-0"></button>
                </div>
            </form> 
            
        </section> 

        <h2 class="cnf-tit" data-idtab="3"><span data-txtid="txt-1072-0"></span></h2>
        <section class="cnf-cont" data-tab="3">
            <form class="mform" name="frm-mail" method="post" action="/tperfil"> 
                <input type="hidden" value="2" name="m" />
                <label class="frm-label req" for="correo" data-txtid="txt-1072-0"></label>
                <input class="input" type="email" name="correo" id="correo" value="<?php echo imprimir($usuario_actual["CORREO_U"])?>" data-required="true"/>
                
                <div class="message"></div>

                <div class="botones">
                    <button class="button bt_col1 light" data-txtid="txt-1006-0"></button>
                </div>

            </form>
        </section> 

        <h2 class="cnf-tit" data-idtab="4"><span data-txtid="txt-1103-0"></span></h2>
        <section class="cnf-cont" data-tab="4">
            <form class="mform" name="frm-seg"  method="post" action="/tperfil"> 
                <input type="hidden" value="3" name="m" />
                <label class="frm-label req" for="pass01"><span data-txtid="txt-1097-0"></span> <span data-txtid="txt-1109-0"></span></label>
                <input class="input" type="password" name="pass01" id="pass01" value="" data-required="true"/>
                <p>&shy;</p>
                <label class="frm-label req" for="pass02" data-txtid="txt-1097-0"></label>
                <input class="input" type="password" name="pass02" id="pass02" value="" data-required="true"/>
                
                <label class="frm-label req" for="pass03"><span data-txtid="txt-1099-0"></span> <span data-txtid="txt-1097-0"></span> </label>
                <input class="input" type="password" name="pass03" id="pass03" value="" data-required="true"/>
                
                <div class="message"></div>

                <div class="botones">
                    <button class="button bt_col1 light" data-txtid="txt-1006-0"></button>
                </div>

            </form>
        </section> 

        <h2 class="cnf-tit" data-idtab="5"><span data-txtid="txt-1105-0"></span></h2>
        <section class="cnf-cont" data-tab="5">
            <form class="mform" name="frm-udisplay" method="post" action="/tperfil">  
                <input type="hidden" value="4" name="m" />
                <input type="hidden" value="1" name="tppres" />

                <fieldset class="fieldset">
                    <legend class="legend medium" data-txtid="txt-1086-0"></legend>
                    <div class="wrapimg display col_bg03">
                        <div class="_fix"></div>
                        <div class="stdImg _zfix_00" style="background-image: url(<?php echo $_PARAMETROS["S3_URL4"].ImgName($_PROYECTO,$_EMPRESA,36,$_USUARIO,'img','png',false,'t03')?>)"></div>
                    </div>

                    <label class="frm-label" for="i-udisplay"><span data-txtid="txt-1000-0"></span></label>
                    <div class="options" id="i-udisplay">
                        <input type="radio" id="i-udisplay1" name="imagen" value="1" data-expand="true"                     /><label for="i-udisplay1" data-txtid="txt-1002-0"></label>
                        <input type="radio" id="i-udisplay2" name="imagen" value="2" data-expand="true" checked="checked"   /><label for="i-udisplay2" data-txtid="txt-1003-0"></label>
                        <input type="radio" id="i-udisplay3" name="imagen" value="3" data-expand="true"                     /><label for="i-udisplay3" data-txtid="txt-1005-0"></label>
                    </div>
                    <div class="hide">
                        <input class="input" type="file" name="imagen" id="frm-udisplay-file" />
                        <div class="message"></div>
                        <div class="botones">
                            <button class="button bt_col1 light" data-txtid="txt-1006-0"></button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </section>
    </div>
</div>
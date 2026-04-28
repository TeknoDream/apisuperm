<?php
$cnf=8;
$permiso=$PermisosA[$cnf]["P"];
$usuario_actual=$_AUSER;
$id_sha=encrip($_GCLIENTE);
$m_md=$id_sha.encrip(10001,2);
?>
<div class="md_carga col_bg03" data-tpage="3" data-cnf="<?php echo $cnf?>">
    <!--LATERAL-->
    <nav class="cnf-nav col_bg01">
        <ul class="ul light">
            <li class="li bt_col2 _selected" data-idtab="1"><span data-txtid="txt-1082-0"></span><i class="fa fa-caret-up"></i></li><!--
         --><?php if($permiso==1){?><li class="li bt_col2" data-idtab="2"><span data-txtid="txt-1083-0"></span><i class="fa fa-caret-up"></i></li><?php } ?><!--
         --><?php if($PermisosA[10001]["P"]==1){ ?><li class="li bt_col2" data-idtab="3"><span data-txtid="txt-1216-0"></span><i class="fa fa-caret-up"></i></li><?php } ?><!--
         --><li class="li bt_col2" data-idtab="4"><span data-txtid="txt-1084-0"></span><i class="fa fa-caret-up"></i></li>
        </ul>
    </nav>
    <!--FIN DE LATERAL-->

    <div class="cnf-lat col_bg02">
        <h2 class="cnf-tit" data-idtab="1"><span data-txtid="txt-1082-0"></span></h2>
        <section class="cnf-cont" data-tab="1">
            <form class="mform" name="frm-basic" method="post" action="/tgeneral">
                <input type="hidden" value="1" name="m" /> 
                <?php
                /***GENERAL***/
                $s=$sqlCons[2][9].' '.$sqlOrder[2][9];
                $reqConfig = $dbEmpresa->prepare($s);
                $reqConfig->bindParam(':idioma', $_IDIOMA);
                $reqConfig->bindParam(':empresa', $_CLIENTE);
                $reqConfig->execute();
                $grupo=0;
                $name_g="";
                $i=0;
                while($regConfig = $reqConfig->fetch()){
                    if($grupo!=$regConfig["ORDEN_GRUPO"]||$regConfig["GRUPO"]!=$name_g){
                        $grupo=$regConfig["ORDEN_GRUPO"];
                        $name_g=$regConfig["GRUPO"];
                        echo '<h3 class="cnf-t1-sup">'.imprimir($regConfig["GRUPO"]).'</h3>';
                    }
                    $clase_label=$regConfig["REQ_CONFIG"]==1?'data-required="true"':"";
                ?>                
                <label class="frm-label" for="c<?php echo $i?>"><?php echo $regConfig["DESC_CONFIG"] ?></label>     
                <input name="n<?php echo $i?>" type="hidden" id="n<?php echo $i?>" value="<?php echo $regConfig["ID_CONFIG"]?>"  />
                <input name="r<?php echo $i?>" type="hidden" id="r<?php echo $i?>" value="<?php echo $regConfig["REQ_CONFIG"]?>"  /> 
                <?php
                
                if(($regConfig["TIPO"]==1)||($regConfig["TIPO"]==3)||($regConfig["TIPO"]==6)||($regConfig["TIPO"]==7)){
                    if($regConfig["TIPO"]==6) $addData='data-tipo="time"';  
                    elseif($regConfig["TIPO"]==7) $addData='data-tipo="money"';  
                    else  $addData='';       
                ?>
                    <input name="c<?php echo $i?>" type="text" <?php echo $addData?> id="c<?php echo $i?>" class="input" value="<?php echo imprimir($regConfig["CONFIG_VALOR"])?>"   <?php echo $clase_label?>/>
                <?php
                }
                elseif($regConfig["TIPO"]==8){                                     
                ?>
                    <input name="c<?php echo $i?>" type="password" id="c<?php echo $i?>" class="input" value="<?php echo imprimir($regConfig["CONFIG_VALOR"])?>"   <?php echo $clase_label?>/>
                <?php
                }   
                elseif($regConfig["TIPO"]==9){  //CHECKBOX                                      
                ?>
                    <div id="c<?php echo $i?>" class="options" <?php echo $clase_label?>>       
                        <input type="radio" id="c<?php echo $i?>1" name="c<?php echo $i?>" value="1" <?php echo $regConfig["CONFIG_VALOR"]==1?'checked="checked"':''?> /><label for="c<?php echo $i?>1" data-txtid="txt-1002-0"></label>
                        <input type="radio" id="c<?php echo $i?>2" name="c<?php echo $i?>" value="2" <?php echo $regConfig["CONFIG_VALOR"]==2?'checked="checked"':''?> /><label for="c<?php echo $i?>2" data-txtid="txt-1003-0"></label>
                     </div>  
                <?php
                }   
                elseif(($regConfig["TIPO"]==2)){
                ?>
                <textarea name="c<?php echo $i?>" id="c<?php echo $i?>" class="input" <?php echo $clase_label?>><?php echo imprimir($regConfig["CONFIG_VALOR"],2)?></textarea>
                <?php
                }
                elseif($regConfig["TIPO"]==5){
                ?>
                <select name="c<?php echo $i?>"  class="input" <?php echo $clase_label?>>
                   <?php    
                                
                        $s = "SELECT *,".$regConfig["ORDEN"]." AS MD_OTRO FROM ".$regConfig["TABLA"];
                        if($regConfig["IDIOMA"]==1) $s.=" WHERE ID_IDIOMA=$_IDIOMA ";
                        if($regConfig["MEMPRESA"]==1) $s.=" WHERE ID_MEMPRESA=$_CLIENTE ";
                        $s.=' ORDER BY '.$regConfig["ORDEN"];
                        
                        $reqInt = $dbEmpresa->prepare($s);
                        $reqInt->execute();
                        while($regInt = $reqInt->fetch()){
                            $var1="";
                            if ($regConfig["CONFIG_VALOR"]==$regInt[$regConfig["CONFIG_NOMBRE"]]) $var1='selected="selected"';
                            echo sprintf('<option value="%s" %s>%s</option>',
                            $regInt[$regConfig["CONFIG_NOMBRE"]],$var1,imprimir($regInt["MD_OTRO"]));
                        }           
                    ?>
                </select>
                <?php
                    }
                    $i++;
                }
                ?>                
                <div class="message"></div>  
                
                <div class="botones">
                    <button class="button bt_col1 light" data-txtid="txt-1085-0"></button>
                </div>         
            </form>
            
        </section>

        <?php if($permiso==1){?>
        <h2 class="cnf-tit" data-idtab="2"><span data-txtid="txt-1083-0"></span></h2>
        <section class="cnf-cont" data-tab="2">             
            <div class="lists" data-cnf="8" data-tipo="auto-list">
                <div class="w-results">
                    <section class="nav-bar col_bg02"><nav class="bars dsinline"></nav><section class="search dsinline"></section></section>
                    <section class="resuls col_bg02" data-tipo="2"></section>
                    <section class="pages col_bg02"></section>
                </div>
            </div>        
        </section>
        <?php } ?>

        <?php if($PermisosA[10001]["P"]==1){ ?>
        <h2 class="cnf-tit" data-idtab="3"><span data-txtid="txt-1216-0"></span></h2>
        <section class="cnf-cont" data-tab="3"> 
            <div class="mform">
                <label class="etiq" for="idioma_cli" data-txtid="txt-332-0"></label>
                <select class="input" data-over="<?php echo $data_over; ?>" name="idioma" id="idioma_cli" data-autocombo="true" data-content="txtcli" data-total="false">
                <?php 
                    $s=$sqlCons[1][76]." WHERE fac_idioma.HAB_IDIOMA=0 ".$sqlOrder[1][76];
                    $req = $dbEmpresa->prepare($s);
                    $req->execute();
                    echo crear_select($req,'ID_IDIOMA','IDIOMA',0,1,'txt-1134-0');
                ?>
                </select>
               
                <fieldset class="fieldset">
                    <legend class="legend medium" data-txtid="txt-1215-0"></legend>
                    <div data-id="txtcli" data-carga="true" data-tp="13" data-md="<?php echo $m_md?>"></div>
                </fieldset>
                <fieldset class="fieldset">
                    <legend class="legend medium" data-txtid="txt-1112-0"></legend>
                    <div data-id="txtcli" data-carga="true" data-tp="14" data-md="<?php echo $m_md?>"></div>
                </fieldset>
                <fieldset class="fieldset">
                    <legend class="legend medium"><span data-txtid="txt-1083-0"></span> (<span data-txtid="txt-1112-0"></span>)</legend>
                    <div data-id="txtcli" data-carga="true" data-tp="15" data-md="<?php echo $m_md?>"></div>
                </fieldset>
                <fieldset class="fieldset">
                    <legend class="legend medium"><span data-txtid="txt-1112-0"></span> (<span data-txtid="txt-1226-0"></span>)</legend>
                    <div data-id="txtcli" data-carga="true" data-tp="16" data-md="<?php echo $m_md?>"></div>
                </fieldset>
                <fieldset class="fieldset">
                    <legend class="legend medium"><span data-txtid="txt-1223-0"></span> (<span data-txtid="txt-1226-0"></span>)</legend>
                    <div data-id="txtcli" data-carga="true" data-tp="17" data-md="<?php echo $m_md?>"></div>
                </fieldset>
            </div>
        </section>
        <?php } ?>

        <h2 class="cnf-tit" data-idtab="4"><span data-txtid="txt-1084-0"></span></h2>
        <section class="cnf-cont" data-tab="4">
            <form class="mform" name="frm-logo" method="post" action="/tgeneral">  
                <input type="hidden" value="3" name="m" />
                <input type="hidden" value="1" name="tppres" />


                <fieldset class="fieldset">
                    <legend class="legend medium" data-txtid="txt-1086-0"></legend>
                    <div class="wrapimg logo col_bg03">
                        <div class="_fix"></div>
                        <div class="stdImg _zfix_00" style="background-image: url(<?php echo $_PARAMETROS["S3_URL4"].ImgName($_PROYECTO,$_EMPRESA,0,$_CLIENTE,'LogoClient','png',false,'big');  ?>)"></div>
                    </div>
                    <label class="frm-label" for="i-clogo"><span data-txtid="txt-1000-0"></span></label>
                    <div class="options" id="i-clogo">
                        <input type="radio" id="i-clogo1" name="imagen" value="1" data-expand="true"                     /><label for="i-clogo1" data-txtid="txt-1002-0"></label>
                        <input type="radio" id="i-clogo2" name="imagen" value="2" data-expand="true" checked="checked"   /><label for="i-clogo2" data-txtid="txt-1003-0"></label>
                        <input type="radio" id="i-clogo3" name="imagen" value="3" data-expand="true"                     /><label for="i-clogo3" data-txtid="txt-1005-0"></label>
                    </div>
                    <div class="hide">
                        <input class="input" type="file" name="imagen" id="frm-logo-file" />

                        <div class="message"></div>

                        <div class="botones">
                            <button class="button bt_col1 light" data-txtid="txt-1006-0"></button>
                        </div>
                    </div>
                </fieldset>
            </form>

            <form class="mform" name="frm-bg" method="post" action="/tgeneral">  
                <input type="hidden" value="3" name="m" />
                <input type="hidden" value="3" name="tppres" />


                <fieldset class="fieldset">
                    <legend class="legend medium" data-txtid="txt-1087-0"></legend>
                    <div class="wrapimg pat col_bg03">
                        <div class="_fix_30"></div>
                        <div class="stdImg _zfix_00" style="background-image: url(<?php echo $_PARAMETROS["S3_URL4"].ImgName($_PROYECTO,$_EMPRESA,0,$_CLIENTE,'BGClient','png',false,'big');  ?>)"></div>
                    </div>
                    <label class="frm-label" for="i-cbg"><span data-txtid="txt-1000-0"></span></label>
                    <div class="options" id="i-cbg">
                        <input type="radio" id="i-cbg1" name="imagen" value="1" data-expand="true"                     /><label for="i-cbg1" data-txtid="txt-1002-0"></label>
                        <input type="radio" id="i-cbg2" name="imagen" value="2" data-expand="true" checked="checked"   /><label for="i-cbg2" data-txtid="txt-1003-0"></label>
                        <input type="radio" id="i-cbg3" name="imagen" value="3" data-expand="true"                     /><label for="i-cbg3" data-txtid="txt-1005-0"></label>
                    </div>
                    <div class="hide">
                        <input class="input" type="file" name="imagen" id="frm-bg-file" />

                        <div class="message"></div>

                        <div class="botones">
                            <button class="button bt_col1 light" data-txtid="txt-1006-0"></button>
                        </div>
                    </div>
                </fieldset>
            </form>
                
            <form class="mform" name="frm-pat" method="post" action="/tgeneral">  
                <input type="hidden" value="3" name="m" />
                <input type="hidden" value="2" name="tppres" />


                <fieldset class="fieldset">
                    <legend class="legend medium" data-txtid="txt-1088-0"></legend>
                    <div class="wrapimg pat col_bg03">
                        <div class="_fix_30"></div>
                        <div class="stdImg _zfix_00" style="background-image: url(<?php echo $_PARAMETROS["S3_URL4"].ImgName($_PROYECTO,$_EMPRESA,0,$_CLIENTE,'PTClient','png',false,'big');  ?>)"></div>
                    </div>
                    <label class="frm-label" for="i-cpat"><span data-txtid="txt-1000-0"></span></label>
                    <div class="options" id="i-cpat">
                        <input type="radio" id="i-cpat1" name="imagen" value="1" data-expand="true"                     /><label for="i-cpat1" data-txtid="txt-1002-0"></label>
                        <input type="radio" id="i-cpat2" name="imagen" value="2" data-expand="true" checked="checked"   /><label for="i-cpat2" data-txtid="txt-1003-0"></label>
                        <input type="radio" id="i-cpat3" name="imagen" value="3" data-expand="true"                     /><label for="i-cpat3" data-txtid="txt-1005-0"></label>
                    </div>
                    <div class="hide">
                        <input class="input" type="file" name="imagen" id="frm-pat-file" />

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
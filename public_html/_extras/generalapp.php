<?php
$cnf=4;
$permiso=$PermisosA[$cnf]["P"];
$usuario_actual=$_AUSER;
$id_sha=encrip($_GCLIENTE);
$m_md=nuevo_item().encrip(4,2);
?>
<div class="md_carga col_bg03" data-tpage="3" data-cnf="<?php echo $cnf?>">
    <!--LATERAL-->
    <nav class="cnf-nav col_bg01">
        <ul class="ul light">
            <li class="li bt_col2 _selected" data-idtab="1"><span data-txtid="txt-1082-0"></span><i class="fa fa-caret-up"></i></li><!--
         --><li class="li bt_col2" data-idtab="2"><span data-txtid="txt-1189-0"></span><i class="fa fa-caret-up"></i></li><!--
         --><li class="li bt_col2" data-idtab="3"><span data-txtid="txt-1301-0"></span><i class="fa fa-caret-up"></i></li><?php
            if($_PROYECTO==25) include('generalapp_menu_025.php');
         ?></ul>
    </nav>
    <!--FIN DE LATERAL-->

    <!--CONTENIDO DE LOGIN-->
    <div class="cnf-lat col_bg02">
        <h2 class="cnf-tit" data-idtab="1"><span data-txtid="txt-1082-0"></span></h2>
        <section class="cnf-cont" data-tab="1">
            <form class="mform" name="frm-basiscapp" method="post" action="/tgeneralapp">
                <input type="hidden" value="1" name="m" /> 
                <?php
                $s=$sqlCons[3][9].' '.$sqlOrder[3][9];
                $reqConfig = $dbEmpresa->prepare($s);
                $reqConfig->bindParam(':idioma', $_IDIOMA);
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
                    $clase=$input_ext;
                    $clase_label=($regConfig["REQ_CONFIG"]==1?'data-required="true"':"");
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
                elseif($regConfig["TIPO"]==2||$regConfig["TIPO"]==10){
                ?>
                    <textarea name="c<?php echo $i?>" <?php echo $regConfig["TIPO"]==10?'data-tipo="richtext"':''?> id="c<?php echo $i?>" class="input" <?php echo $clase_label?>><?php echo imprimir($regConfig["CONFIG_VALOR"],2)?></textarea>
                <?php
                }
                elseif($regConfig["TIPO"]==5){
                ?>
                <select name="c<?php echo $i?>"  class="input" <?php echo $clase_label?>>
                   <?php    
                                
                        $s = "SELECT *,".$regConfig["ORDEN"]." AS MD_OTRO FROM ".$regConfig["TABLA"];
                        if($regConfig["IDIOMA"]==1) $s.=" WHERE ID_IDIOMA=$_IDIOMA ";
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

        <h2 class="cnf-tit" data-idtab="2"><span data-txtid="txt-1189-0"></span></h2>
        <section class="cnf-cont" data-tab="2">
            <form class="mform"  name="frm-alogo" method="post" action="/tgeneralapp">  
                <input type="hidden" value="2" name="m" />
                <input type="hidden" value="1" name="tppres" />


                <fieldset class="fieldset">
                    <legend class="legend medium" data-txtid="txt-1086-0"></legend>
                    <div class="wrapimg logo col_bg03">
                        <div class="_fix"></div>
                        <div class="stdImg _zfix_00" style="background-image: url(<?php echo $_PARAMETROS["S3_URL4"].ImgName($_PROYECTO,$_EMPRESA,0,0,'LogoApp','png',false,'big')?>)"></div>
                    </div>
                    <div class="options">       
                        <input type="radio" id="i-alogo1" name="imagen" value="1" data-expand="true"                     /><label for="i-alogo1" data-txtid="txt-1002-0"></label>
                        <input type="radio" id="i-alogo2" name="imagen" value="2" data-expand="true" checked="checked"   /><label for="i-alogo2" data-txtid="txt-1003-0"></label>
                        <input type="radio" id="i-alogo3" name="imagen" value="3" data-expand="true"                     /><label for="i-alogo3" data-txtid="txt-1005-0"></label>
                    </div>
                    <div class="hide">
                        <input class="input" type="file" name="imagen" id="frm-alogo-file" />

                        <div class="message"></div>

                        <div class="botones">
                            <button class="button bt_col1 light" data-txtid="txt-1006-0"></button>
                        </div>
                    </div>
                </fieldset>
            </form>

            <form class="mform" name="frm-anoimg" method="post" action="/tgeneralapp">  
                <input type="hidden" value="2" name="m" />
                <input type="hidden" value="2" name="tppres" />


                <fieldset class="fieldset">
                    <legend class="legend medium" data-txtid="txt-1252-0"></legend>
                    <div class="wrapimg logo col_bg03">
                        <div class="_fix"></div>
                        <div class="stdImg _zfix_00" style="background-image: url(<?php echo $_PARAMETROS["S3_URL4"].ImgName($_PROYECTO,$_EMPRESA,0,0,'NoImageApp','png',false,'big')?>)"></div>
                    </div>
                    <div class="options">       
                        <input type="radio" id="i-anoimg1" name="imagen" value="1" data-expand="true"                     /><label for="i-anoimg1" data-txtid="txt-1002-0"></label>
                        <input type="radio" id="i-anoimg2" name="imagen" value="2" data-expand="true" checked="checked"   /><label for="i-anoimg2" data-txtid="txt-1003-0"></label>
                        <input type="radio" id="i-anoimg3" name="imagen" value="3" data-expand="true"                     /><label for="i-anoimg3" data-txtid="txt-1005-0"></label>
                    </div>
                    <div class="hide">
                        <input class="input" type="file" name="imagen" id="frm-anoimg-file" />

                        <div class="message"></div>

                        <div class="botones">
                            <button class="button bt_col1 light" data-txtid="txt-1006-0"></button>
                        </div>
                    </div>
                </fieldset>
            </form>
           
            <form class="mform"  name="frm-afavicon" method="post" action="/tgeneralapp">  
                <input type="hidden" value="2" name="m" />
                <input type="hidden" value="3" name="tppres" />


                <fieldset class="fieldset">
                    <legend class="legend medium" data-txtid="txt-1393-0"></legend>
                    <div class="wrapimg icon col_bg03">
                        <div class="_fix"></div>
                        <div class="stdImg _zfix_00" style="background-image: url(<?php echo $_PARAMETROS["S3_URL4"].ImgName($_PROYECTO,$_EMPRESA,0,0,'favico','ico',false,'big')?>)"></div>
                    </div>
                    <div class="options">       
                        <input type="radio" id="i-afavicon1" name="imagen" value="1" data-expand="true"                     /><label for="i-afavicon1" data-txtid="txt-1002-0"></label>
                        <input type="radio" id="i-afavicon2" name="imagen" value="2" data-expand="true" checked="checked"   /><label for="i-afavicon2" data-txtid="txt-1003-0"></label>
                        <input type="radio" id="i-afavicon3" name="imagen" value="3" data-expand="true"                     /><label for="i-afavicon3" data-txtid="txt-1005-0"></label>
                    </div>
                    <div class="hide">
                        <input class="input" type="file" name="imagen" id="frm-afavicon-file" />

                        <div class="message"></div>

                        <div class="botones">
                            <button class="button bt_col1 light" data-txtid="txt-1006-0"></button>
                        </div>
                    </div>
                </fieldset>
            </form>     
        </section>

        <h2 class="cnf-tit" data-idtab="3"><span data-txtid="txt-1301-0"></span></h2>
        <section class="cnf-cont" data-tab="3">
            <div class="mform">
                <label class="frm-label" for="idioma-lnd" data-txtid="txt-3004-0"></label>
                <select class="input" data-over="<?php echo $data_over; ?>" name="idioma" id="idioma-lnd" data-autocombo="true" data-content="txtlnd" data-total="false">
                <?php 
                    $s=$sqlCons[1][76]." WHERE fac_idioma.HAB_IDIOMA=0 ".$sqlOrder[1][76];
                    $req = $dbEmpresa->prepare($s);
                    $req->execute();
                    echo crear_select($req,'ID_IDIOMA','IDIOMA',0,1,'txt-1134-0');
                ?>
                </select>
                <div id="txtlnd" data-id="txtlnd" data-tp="1" data-md="<?php echo $m_md?>"></div>
                <div class="botones">
                    <button class="button bt_col1 light" data-autocomplete="button" data-tp="74" data-table="txtlnd" data-autodata="idioma" data-txtid="txt-1008-0"></button>
                </div>
            </div>
        </section>
        <?php
        if($_PROYECTO==25) include('generalapp_cnt_025.php');
        ?>
    </div>
</div>
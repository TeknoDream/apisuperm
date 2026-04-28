<?php
$cnf=2;
$permiso=$PermisosA[$cnf]["P"];
if(!$permiso) PrintErr(array('txt-MSJ16-0'),2);

$m_md=nuevo_item().encrip($cnf,2);
?>
<div class="md_carga col_bg03" data-tpage="3" data-cnf="<?php echo $cnf?>">
    <!--LATERAL-->
    <nav class="cnf-nav col_bg01">
        <ul class="ul light">
            <li class="li bt_col2 _selected" data-idtab="1"><span data-txtid="txt-1164-0"></span><i class="fa fa-caret-up"></i></li><!--
         --><li class="li bt_col2" data-idtab="2"><span data-txtid="txt-3009-0"></span><i class="fa fa-caret-up"></i></li><!--
         --><li class="li bt_col2" data-idtab="3"><span data-txtid="txt-1393-0"></span><i class="fa fa-caret-up"></i></li><!--
         --><li class="li bt_col2" data-idtab="4"><span data-txtid="txt-1301-0"></span><i class="fa fa-caret-up"></i></li>
        </ul>
    </nav>
	<!--FIN DE LATERAL-->

    <!--CONTENIDO DE LOGIN-->
    <div class="cnf-lat col_bg02">
        <h2 class="cnf-tit" data-idtab="1"><span data-txtid="txt-1164-0"></span></h2>
        <section class="cnf-cont" data-tab="1">            
            <form class="mform" name="frm-basisc" method="post" action="/tcompany/"> 
                <input type="hidden" value="1" name="m" />
                
                <?php
                $s=$sqlCons[1][81]." WHERE adm_empresas.ID_MEMPRESA=$_CLIENTE LIMIT 1";
                $reqEmp = $dbEmpresa->prepare($s);
                $reqEmp->bindParam(':idioma', $_IDIOMA);
                $reqEmp->execute();  
                $regEmp = $reqEmp->fetch();
                ?>
                <label class="frm-label req" for="nomb" data-txtid="txt-1130-0"></label>
                <input class="input" type="text" name="nomb" id="nomb" maxlength="50" value="<?php echo imprimir($regEmp["NOMB_MEMPRESA"])?>" data-required="true"/>
                
                <?php
                $s=$sqlCons[1][76]." WHERE fac_idioma.HAB_IDIOMA=0 ".$sqlOrder[1][76];
                $req = $dbEmpresa->prepare($s);
                $req->execute();    
                while($reg = $req->fetch()){?>
                    <fieldset class="fieldset">
                        <legend class="legend medium"><?php echo $reg["IDIOMA"]?></legend>
                        <?php
                        $s=$sqlCons[1][83]." WHERE adm_empresas_desc.ID_MEMPRESA=$_CLIENTE AND adm_empresas_desc.ID_IDIOMA=:idioma LIMIT 1";
                        $reqId = $dbEmpresa->prepare($s);
                        $reqId->bindParam(':idioma', $reg["ID_IDIOMA"]);
                        $reqId->execute();  
                        $regId = $reqId->fetch();
                        ?>                        
                    <label class="frm-label " for="lema_<?php echo $reg["ID_IDIOMA"]?>" data-txtid="txt-3035-0"></label>
                    <input class="input" type="text" name="lema[<?php echo $reg["ID_IDIOMA"]?>]" id="lema_<?php echo $reg["ID_IDIOMA"]?>" maxlength="120" value="<?php echo imprimir($regId["LEMA_EMPRESA"])?>"/>
                    
                    <label class="frm-label " for="desc_<?php echo $reg["ID_IDIOMA"]?>" data-txtid="txt-1091-0"></label>
                    <textarea class="input" name="desc[<?php echo $reg["ID_IDIOMA"]?>]" id="desc_<?php echo $reg["ID_IDIOMA"]?>"><?php echo imprimir($regId["DESC_EMPRESA"],2)?></textarea>
                    <input type="hidden" name="idioma[<?php echo $reg["ID_IDIOMA"]?>]" id="idioma_<?php echo $reg["ID_IDIOMA"]?>"  value="<?php echo $reg["ID_IDIOMA"] ?>"/>  
                    </fieldset>                
                <?php
                }
                ?>                
                <div class="message"></div>

                <div class="botones">
                    <button class="button bt_col1 light" data-txtid="txt-1085-0"></button>
                </div>
            </form>            
        </section>


          
        <h2 class="cnf-tit" data-idtab="2"><span data-txtid="txt-3009-0"></span></h2>
        <section class="cnf-cont" data-tab="2">   
            <div class="mform">
                <div id="urls" data-id="urls" data-carga="true" data-tp="1" data-md="<?php echo $m_md?>"></div>
                <div class="botones">
                    <button class="button bt_col1 light" data-autocomplete="button" data-tp="82" data-table="urls" data-txtid="txt-1008-0"></button>
                </div>
            </div>
        </section>

        <h2 class="cnf-tit" data-idtab="3"><span data-txtid="txt-1189-0"></span></h2>
        <section class="cnf-cont" data-tab="3">         
            <form class="mform" name="ci-efavicon" method="post" action="/tcompany">
                <input type="hidden" value="3" name="m" />
                <input type="hidden" value="1" name="tppres" />    

                <fieldset class="fieldset">
                    <legend class="legend medium" data-txtid="txt-1393-0"></legend>
                    <div class="wrapimg icon col_bg03">
                        <div class="_fix"></div>
                        <div class="stdImg _zfix_00" style="background-image: url(<?php echo $_PARAMETROS["S3_URL4"].ImgName($_PROYECTO,$_EMPRESA,0,$_CLIENTE,'favico','ico',false,'big');  ?>)"></div>
                    </div>

                    <label class="frm-label" for="i-efavicon"><span data-txtid="txt-1000-0"></span></label>
                    <div class="options" id="i-efavicon">
                        <input type="radio" id="i-efavicon1" name="imagen" value="1" data-expand="true"                     /><label for="i-efavicon1" data-txtid="txt-1002-0"></label>
                        <input type="radio" id="i-efavicon2" name="imagen" value="2" data-expand="true" checked="checked"   /><label for="i-efavicon2" data-txtid="txt-1003-0"></label>
                        <input type="radio" id="i-efavicon3" name="imagen" value="3" data-expand="true"                     /><label for="i-efavicon3" data-txtid="txt-1005-0"></label>
                    </div>
                    <div class="hide">
                        <input class="input" type="file" name="imagen" id="frm-efavicon-file" />

                        <div class="message"></div>

                        <div class="botones">
                            <button class="button bt_col1 light" data-txtid="txt-1006-0"></button>
                        </div>
                    </div>
                </fieldset>
            </form>           
        </section>
            
        <h2 class="cnf-tit" data-idtab="4"><span data-txtid="txt-1301-0"></span></h2>
        <section class="cnf-cont" data-tab="4"> 
            <div class="mform">
                <label class="frm-label " for="idioma_land" data-txtid="txt-3004-0"></label>
                <select class="input" data-over="<?php echo $data_over; ?>" name="idioma" id="idioma_land" data-autocombo="true" data-content="txtlnd" data-total="false">
                <?php 
                    $s=$sqlCons[1][76]." WHERE fac_idioma.HAB_IDIOMA=0 ".$sqlOrder[1][76];
                    $req = $dbEmpresa->prepare($s);
                    $req->execute();
                    echo crear_select($req,'ID_IDIOMA','IDIOMA',0,1,'txt-1134-0');
                ?>
                </select>
                <div id="txtlnd" data-id="txtlnd" data-tp="2" data-md="<?php echo $m_md?>"></div>
                
                <div class="botones">
                    <button class="button bt_col1 light" data-autocomplete="button" data-tp="74" data-table="txtlnd" data-autodata="idioma" data-txtid="txt-1008-0"></button>
                </div>
            </div>
        </section>
    </div>
</div>
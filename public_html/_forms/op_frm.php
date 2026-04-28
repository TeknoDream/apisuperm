<?php

$md=isset($_REQUEST["md"])?$_REQUEST["md"]:'';
$id_sha=substr($md,0,40);
$c_sha=substr($md,40,32);
$id_sha_t=substr($md,72,40);
$det_plus=intval(substr($md,112,3));




if(nuevo_item()==$id_sha) $nuevo=true;
else $nuevo=false;


$sWhere=encrip_mysql('adm_ventanas.ID_VENTANA',2);
$s=$sqlCons[1][71]." WHERE $sWhere=:c_sha LIMIT 1";
$req = $dbEmpresa->prepare($s); 
$req->bindParam(':c_sha', $c_sha);
$req->execute();	
if($reg = $req->fetch()){
	$cnf=$reg["ID_VENTANA"];
}

if(($cnf==8)&&($id_sha==encrip($_GRUPO))&&($det_plus==1)) exit(0);

$picname=$_PARAMETROS["S3_URL4"].ImgBlanc(0
                            ,   array(
                                    'PROYECTO'=>$_PROYECTO
                                ,   'EMPRESA'  =>$_EMPRESA
                                ,   'MODULO'    =>0
                                ,   'OBJETO'    =>0
                                ,   'TP'        =>'img'
                                ,   'EXT'       =>'jpg'
                                ,   'All'       =>false
                                ,   'Cual'      =>'t02'));
 
?>
<script id="_JS_ADD" type="text/javascript">
    
    (function(){
        var nuevo=<?php echo $nuevo?"true":"false"; ?>
        ,   input_ext='input';   
        <?php   
        if((($cnf==26)&&($det_plus==2))||(($cnf==25)&&($det_plus==2))){ ?>            
            $("#u_caract").delegate('[id^=DescCar_],[id^=UndCar_],[id^=ValorCar_]',"change",function(e){            
                e.preventDefault();             
                var este=$(this);
                var fila=$(this).parents("tr");         
                fila.find('[id^=IdCar_]').remove()              
            });                     
        <?php 
        }

        if((($cnf==39)&&($det_plus==1))||($cnf==41)&&($det_plus==1)){   ?>
            $("#fecha,#item").on("change",$.debounce( 250, function(){                    
                DataCarga($("#u_frm02"),$(this).parents('form'));                   
            }));
        <?php
        }
        if((($cnf==39)&&($det_plus==3))||($cnf==41)&&($det_plus==3)){   ?>
            $("#u_frm02").delegate('[id^=Del_]',"click",function(e){
                e.preventDefault();                 
                _TotalTabla("CTotal_");
            });
        <?php
        }
        //RM <- Consumible
        if($cnf==31){ ?>
            
            $("#repuesto").on("change",function(){
                if($(this).val()=="NU"){    
                    if($("#compreti").is(":visible")) $("#compreti").slideUp(100);  
                    $('#ccompra2').attr("checked","false");
                    $('#ccompra1').attr("checked","true").click();
                }
                else{
                    if(!$("#compreti").is(":visible")) $("#compreti").slideDown(100);                       
                }
                
                
                var config={dir:'/autocomplete'};                   
                var option=$(this).parents('form').serializeObject();
                option.tp=33;
                DinamicCombo(config,option,$("#preprecio"),
                    function(){ $("#preprecio").change()    }
                )
                
            });
            $("#repuesto_alm").on("change",function(){
                if($(this).val()==1){                   
                    if($("#repuesto").val()!="NU"){
                        $('#ccompra1').attr("checked",false);
                        $('#ccompra2').attr("checked",true).click();
                    }
                }
                else{
                    if($("#repuesto").val()!="NU"){
                        $('#ccompra2').attr("checked",false);
                        $('#ccompra1').attr("checked",true).click();
                    }
                }

                if($(this).val()==1)    $("#u_frm_u").slideDown(100); 
                else                    $("#u_frm_u").slideUp(100); 
    
            });
            $("#preprecio").on("change",function(){
                var Valor=$(this).find('option[value="'+$(this).val()+'"]').attr("data-valor");
                $("#valpre").val(Valor);
                _SumaP($("#valpre"));
            });
            
            $("#almrep1,#almrep2").on("click",function(){ 
                $("#repuesto_alm").val($("#almrep1").prop("checked")?1:0)
                                    .change();
            });             
            
            $("#val,#cant,#ctotal,#valpre,#cantpre").on("keyup",$.debounce( 250, function(){ _SumaP($(this)) }));
            
            function _SumaP(esto){
                var Padre=esto.parents('[data-tipo="valores"]').first();
                var Cant=IsNumeric(Padre.find('[data-name="cant"]').val())?parseInt(Padre.find('[data-name="cant"]').val()):0;
                var vUnid=IsNumeric(Padre.find('[data-name="val"]').val())?parseInt(Padre.find('[data-name="val"]').val()):0;
                var cTotal=IsNumeric(Padre.find('[data-name="ctotal"]').val())?parseInt(Padre.find('[data-name="ctotal"]').val()):0;
                if(esto.attr("data-name")=="ctotal")    var vUnid=(Cant==0)?0:(cTotal/Cant);                        
                else                                    var cTotal=vUnid*Cant;
                if(esto.attr("data-name")=="ctotal")    Padre.find('[data-name="val"]').val(vUnid);
                else                                    Padre.find('[data-name="ctotal"]').val(cTotal);
            }
    
        <?php
        }
        if(($cnf==107)){
        ?>
            $("#busccit").on("autocompleteclose", function( event, ui ) {
                DataCarga($("#u_eqrel"),$(this).parents('form')); 
            });
        <?php
        }
        //CONSUMIBLE, ACT CONTADOR, MOVIMIENTO, CAMBIO ESTADO RM
        if(($cnf==31)||($cnf==6)||($cnf==29)||($cnf==9)){
        ?>
            $("#fecha").on("change",$.debounce( 250, function(){
                if($(this).attr("data-adate")!=$(this).val()){
                    $(this).attr("data-adate",$(this).val());
                    DataCarga($("#u_frm02"),$(this).parents('form'));
                }
            }));
            $("#busccit").on("autocompleteclose", function( event, ui ) {
                DataCarga($("#u_frm02"),$(this).parents('form')); 
            });
        <?php
        }
        if($cnf==31){
        ?>              
            $("#busccit").on("autocompleteclose", function( event, ui ) {
                DataCarga($("#reg_contador"),$(this).parents('form')); 
            });
        <?php
        }
        if(($cnf==1)&&($det_plus==2)){
        ?>
            /////////////////
            
            /////////////////
            $("#repuesto").on("change",function(){
                if($(this).val()=="NU"){                            
                    if($("#compreti").is(":visible")) $("#compreti").slideUp(100);  
                    $('#ccompra2').attr("checked",false);
                    $('#ccompra1').attr("checked",true).click();                      
                }
                else{
                    if(!$("#compreti").is(":visible")) $("#compreti").slideDown(100);                       
                }
                                    
                var config={dir:'/autocomplete'};                   
                var option=$(this).parents('form').serializeObject();
                option.tp=33;
                DinamicCombo(config,option,$("#prepreciorep"),
                    function(){ $("#prepreciorep").change() }
                )
                
                
                
            });
            $("#repuesto_alm").on("change",function(){
                if($(this).val()==1)    $("#u_frm_u").slideDown(100); 
                else                    $("#u_frm_u").slideUp(100); 
            });
            $("#prepreciorep").on("change",function(){
                var Valor=$(this).find('option[value="'+$(this).val()+'"]').attr("data-valor");
                $("#valpre").val(Valor);
                _SumaP($("#valpre"));
            });
            
            $("#almrep1,#almrep2").on("click",function(){ 
                $("#repuesto_alm").val($("#almrep1").prop("checked")?1:0)
                                    .change();
            });

            $('[data-addfnuc="ctotal"],[data-addfnuc="cant"],[data-addfnuc="val"]')
                                            .on("keyup",$.debounce( 250, function(){ _SumaP($(this)) }))
                                            .on("change",$.debounce( 250, function(){ _SumaP($(this)) }));
            
            function _SumaP(esto){
                var Padre=esto.parents('[data-tipo="valores"]').first();
                var Cant=IsNumeric(Padre.find('[data-addfnuc="cant"]').val())?parseInt(Padre.find('[data-addfnuc="cant"]').val()):0;
                var vUnid=IsNumeric(Padre.find('[data-addfnuc="val"]').val())?parseInt(Padre.find('[data-addfnuc="val"]').val()):0;
                var cTotal=IsNumeric(Padre.find('[data-addfnuc="ctotal"]').val())?parseInt(Padre.find('[data-addfnuc="ctotal"]').val()):0;
                if(esto.attr("data-addfnuc")=="ctotal")     var vUnid=(Cant==0)?0:Math.round((cTotal/Cant),2);                      
                else                                        var cTotal=Math.round(vUnid*Cant,2);
                if(esto.attr("data-addfnuc")=="ctotal")     Padre.find('[data-addfnuc="val"]').val(vUnid);
                else                                        Padre.find('[data-addfnuc="ctotal"]').val(cTotal);
            }
        
        <?php 
        }       
        //ALMACEN CBONITA
        if(($cnf==1502)&&(($det_plus==1))&&($_PROYECTO==13)){?>
            $("#fecha").on("change",$.debounce( 250, function(){
                DataCarga($("#u_frm05"),$(this).parents('form'));   
            }));
            $("#item").on("change",$.debounce( 250, function(){
                DataCarga($("#u_frm05"),$(this).parents('form'));   
            }));
        <?php
        }


        if($_PROYECTO==40){
            if($cnf==512&&$det_plus==1){?>
                $("#gama").on('change',function(){
                    $("[data-tp='1']").attr('data-gama',$(this).val())
                    DataCarga($("[data-tp='1']"),$(this).parents('form')); 
                });
            <?php
            }
        }

        
        ?>
    })();
</script>
<?php
if(($cnf==36)&&($det_plus==1)){	
	$sWhere=encrip_mysql('adm_empresas.ID_MEMPRESA');
	$s=$sqlCons[1][81]." WHERE $sWhere=:id LIMIT 1";		
	$req = $dbEmpresa->prepare($s); 
	$req->bindParam(':id', $id_sha);
	$req->bindParam(':idioma', $_IDIOMA);
	$req->execute();	
	$reg = $req->fetch();

	
	$sub_titulo=$reg["NOMB_MEMPRESA"];	
	$titulo="txt-1163-0";
	$id_sha_n=md5($_USUARIO);

?>
	<form class="iform min col_bg03" name="frm-subir" method="post" action="/toperation">
        <header class="frm-h">
            <h2 class="frm-tit col_titles" data-txtid="<?php echo $titulo ?>"></h2>
            <h3 class="frm-stit col_titles2"><?php echo $sub_titulo?></h3>
            <div class="x bt_col_close" data-close="form"><div class="cx"><i class="fa fa-close"></i></div></div>
        </header>
        <div class="frm-body">
        	<div class="p" data-txtid="txt-1163-1"></div>
			<input type="hidden" name="md" id="md" value="<?php echo $md?>" />
		</div>
        <div class="message"></div>
        <div class="botones">
            <button class="button bt_col1 light" data-txtid="txt-1006-0"></button>
        </div>
    </form>
<?php
}
//CREAR NUEVA EMPRESA
elseif(($cnf==4)&&($det_plus==1)){	

	$n_ciudad=$_SNombCiudadPpal;
	$n_depto=$_SNombDptoPpal;
	$n_pais=$_SNombPaisPpal;

	$latitude_def=$latitude_def;
	$longitude_def=$longitude_def;
	$zoom_def=$zoom_def;
	$id_ciudad=$_IdCiudadPpal;
	$ciudad=$_NombCiudadPpal;

	$titulo="txt-1159-0";
?>
	<form class="iform col_bg03" name="frm-subir" method="post" action="/toperation">
        <header class="frm-h">
            <h2 class="frm-tit col_titles" data-txtid="<?php echo $titulo ?>"></h2>
            <h3 class="frm-stit col_titles2"><?php echo $sub_titulo?></h3>
            <div class="x bt_col_close" data-close="form"><div class="cx"><i class="fa fa-close"></i></div></div>
        </header>
        <div class="frm-body"> 
            <label class="frm-label req" for="nmempresa" data-txtid="txt-1130-0"></label>
     		<input class="input" type="text" name="nmempresa" id="nmempresa" maxlength="35" value="" data-required="true"/>
    		<?php
            $s="SELECT COUNT(adm_empresas_tipo.ID_TIPOE) AS CONTEO,
            adm_empresas_tipo.ID_TIPOE
            FROM adm_empresas_tipo
            WHERE adm_empresas_tipo.HAB_TIPOE=0";
            $reqFac = $dbEmpresa->prepare($s);
            $reqFac->execute();
            $regFac = $reqFac->fetch();
            if($regFac["CONTEO"]==1){
            ?>
                <input type="hidden" name="tempresa" id="tempresa" value="<?php echo $regFac["ID_TIPOE"]?>" />
            <?php
            }
            else{
            ?>                    
                <label class="frm-label req" for="tempresa" data-txtid="txt-1161-0"></label>
                <select class="input" name="tempresa" id="tempresa" data-required="true">
                <?php 
                    $s=$sqlCons[1][72]." WHERE adm_empresas_tipo.HAB_TIPOE=0 AND adm_empresas_tipo_desc.ID_IDIOMA=$_IDIOMA ".$sqlOrder[1][72];
                    $req = $dbEmpresa->prepare($s);
                    $req->execute();
                    echo crear_select($req,'ID_TIPOE','NOMB_TIPOE',$id_tipoe,1,'txt-1162-0');
                ?>
                </select>  
            <?php
            }?>


    		<fieldset class="fieldset">
                <legend class="legend"><span data-txtid="txt-1396-0"></span></legend>

                <label class="frm-label" for="sucursal" data-txtid="txt-1396-0"></label>
                <input class="input" type="text" name="sucursal" id="sucursal" maxlength="50" value=""/>

                <div data-maps="true" class="mapa" data-setMarker="true" data-setInverse="true" data-setDecode="true">  
                    <label class="frm-label req" for="ciudad" data-txtid="txt-1058-0"></label>
                    <select class="input" name="ciudad" id="ciudad" data-name="city" data-required="true">
                        <option value="0" data-txtid="txt-1128-0"></option>
                        <?php 
                            $s=$sqlCons[1][45].' WHERE fac_ciudades.ID_CIUDAD IN (SELECT ID_CIUDAD FROM adm_ciudad) '.$sqlOrder[1][45];
                            $reqC = $dbEmpresa->prepare($s);
                            $reqC->execute();    
                            while($regC = $reqC->fetch()){
                                $selec_in='';
                                echo sprintf('<option value="%s" %s data-city="%s" data-district="%s" data-country="%s">%s</option>'
                                    ,   $regC["ID_CIUDAD"]
                                    ,   $selec_in
                                    ,   $regC["NOMB_CIUDAD"]
                                    ,   $regC["DISTRITO_CIUDAD"]
                                    ,   $regC["NOMB_PAIS"]
                                    ,   $regC["NOMB_CIUDAD"]);
                            }
                        ?>
                    </select>   
                    <label class="frm-label" for="direc" data-txtid="txt-1065-0"></label>
                    <input class="input" type="text" name="direc" id="direc" maxlength="100" value="" data-name="direction"/>

                    <label class="frm-label" for="tel" data-txtid="txt-1059-0"></label>
                    <input class="input" type="tel" name="tel" id="tel" maxlength="15" value=""/>

                    <input type="hidden" data-name="lat" name="lat_u" value="<?php echo $latitude_def ?>"/>
                    <input type="hidden" data-name="lon" name="lon_u" value="<?php echo $longitude_def ?>"/>
                    <input type="hidden" data-name="zoom" name="zoom" value="<?php echo $zoom_def ?>"/>                             
                    <div class="m02" data-name="map"></div>
                    <div class="botones">
                        <button class="button bt_col1" data-name="decode" data-txtid="txt-1429-0"></button>
                    </div>
                </div>
            </fieldset>

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
//TEXTOS DE CLASE
elseif(($cnf==10003)&&($det_plus==1)){	
	$sWhere=encrip_mysql("adm_empresas_btipo.TIPO_GRUPOPAL");
	$s=$sqlCons[1][85]." WHERE $sWhere=:id LIMIT 1";
	
	$req = $dbEmpresa->prepare($s); 
	$req->bindParam(':id', $id_sha);
	$req->execute();	
	if(!$reg = $req->fetch()) exit(0);	
			
	$id_sha_n=md5($reg["TIPO_GRUPOPAL"]);
	$titulo="txt-1295-0";
	$sub_titulo=$reg["NOMB_GRUPOPAL"];
	
	$m_md=$id_sha.encrip(10001,2);

?>
	<form class="iform big col_bg03" name="frm-subir" method="post" action="/toperation">
        <header class="frm-h">
            <h2 class="frm-tit col_titles" data-txtid="<?php echo $titulo ?>"></h2>
            <h3 class="frm-stit col_titles2"><?php echo $sub_titulo?></h3>
            <div class="x bt_col_close" data-close="form"><div class="cx"><i class="fa fa-close"></i></div></div>
        </header>
        <div class="frm-body">
            <div>
                <div class="col50">
    		        <label class="frm-label" for="idioma" data-txtid="txt-3004-0"></label>
    		        <select class="input" name="idioma" id="idioma" data-autocombo="true" data-content="lenguas" data-total="false">
    		        <?php 
    		            $s=$sqlCons[1][76]." WHERE fac_idioma.HAB_IDIOMA=0 ".$sqlOrder[1][76];
    		            $req = $dbEmpresa->prepare($s);
    		            $req->execute();
    		            echo crear_select($req,'ID_IDIOMA','IDIOMA',0,1,'txt-1134-0');
    		        ?>
    		        </select>
    		    </div><!--
             --><div class="col50"></div>
                <div class="final"></div>
            </div>
            <!-- PARAMETROS -->			        
	        <fieldset class="fieldset">
	            <legend class="legend"><span data-txtid="txt-1112-0"></span> (<span data-txtid="txt-1226-0"></span>)</legend>
	            <div data-id="lenguas" data-carga="true" data-tp="8" data-md="<?php echo $m_md?>"></div>
	        </fieldset>

	        <fieldset class="fieldset">
	            <legend class="legend"><span data-txtid="txt-1223-0"></span> (<span data-txtid="txt-1226-0"></span>)</legend>
	            <div data-id="lenguas" data-carga="true" data-tp="9" data-md="<?php echo $m_md?>"></div>
	        </fieldset>

	        <?php
	        if($_SPROYECTO==1){
	        ?>
	        <!--OPERACION DE MANTENIMIENTO-->
        	<fieldset class="fieldset">
	            <legend class="legend"><span data-txtid="txt-1140-0"></span> <span data-txtid="txt-370-0"></span> (<span data-txtid="txt-1187-0"></span>)</legend>
	            <div data-id="lenguas" data-carga="true" data-tp="10" data-md="<?php echo $m_md?>"></div>
	        </fieldset>
	        <fieldset class="fieldset">
	            <legend class="legend"><span data-txtid="txt-1140-0"></span> <span data-txtid="txt-358-0"></span> (<span data-txtid="txt-1187-0"></span>)</legend>
	            <div data-id="lenguas" data-carga="true" data-tp="11" data-md="<?php echo $m_md?>"></div>
	        </fieldset>
	        <fieldset class="fieldset">
	            <legend class="legend"><span data-txtid="txt-408-0"></span> (<span data-txtid="txt-1187-0"></span>)</legend>
	            <div data-id="lenguas" data-carga="true" data-tp="12" data-md="<?php echo $m_md?>"></div>
	        </fieldset>
	        <!-- DE PRECARGA -->
        	<fieldset class="fieldset">
	            <legend class="legend" data-txtid="txt-416-0"></legend>
	            <div data-id="lenguas" data-carga="true" data-tp="3" data-md="<?php echo $m_md?>"></div>
	        </fieldset>
	        <fieldset class="fieldset">
	            <legend class="legend" data-txtid="txt-406-0"></legend>
	            <div data-id="lenguas" data-carga="true" data-tp="4" data-md="<?php echo $m_md?>"></div>
	        </fieldset>
	        <fieldset class="fieldset">
	            <legend class="legend" data-txtid="txt-407-0"></legend>
	            <div data-id="lenguas" data-carga="true" data-tp="5" data-md="<?php echo $m_md?>"></div>
	        </fieldset>
	        <?php
	        } ?>

	        <!-- VENTANAS -->
	        <fieldset class="fieldset">
	            <legend class="legend" data-txtid="txt-1112-0"></legend>
	            <div data-id="lenguas" data-carga="true" data-tp="6" data-md="<?php echo $m_md?>"></div>
	        </fieldset>
	        <fieldset class="fieldset">
	            <legend class="legend"><span data-txtid="txt-1083-0"></span> (<span data-txtid="txt-1112-0"></span>)</legend>
	            <div data-id="lenguas" data-carga="true" data-tp="7" data-md="<?php echo $m_md?>"></div>
	        </fieldset>	        
	        <!--TEXTO DE APLICACION-->
	        <fieldset class="fieldset">
	            <legend class="legend" data-txtid="txt-1215-0"></legend>
	            <div data-id="lenguas" data-carga="true" data-tp="2" data-md="<?php echo $m_md?>"></div>
	        </fieldset>        
        </div>
    </form>
<?php
}
//TEXTOS DE EMPRESA
elseif(($cnf==10004)&&($det_plus==1)){	
	$sWhere=encrip_mysql("adm_empresas.ID_MEMPRESA");
	$s=$sqlCons[1][81]." WHERE $sWhere=:id LIMIT 1";		
	$req = $dbEmpresa->prepare($s); 
	$req->bindParam(':idioma', $_IDIOMA);
	$req->bindParam(':id', $id_sha);
	$req->execute();	
	if(!$reg = $req->fetch()) exit(0);	
			

	$titulo="txt-1216-0";
	$sub_titulo=$reg["NOMB_GRUPOPAL"];
	
	$m_md=$id_sha.encrip(10001,2);

?>
	<form class="iform big col_bg03" name="frm-subir" method="post" action="/toperation">
        <header class="frm-h">
            <h2 class="frm-tit col_titles" data-txtid="<?php echo $titulo ?>"></h2>
            <h3 class="frm-stit col_titles2"><?php echo $sub_titulo?></h3>
            <div class="x bt_col_close" data-close="form"><div class="cx"><i class="fa fa-close"></i></div></div>
        </header>
        <div class="frm-body">
            <div>
                <div class="col50">
			        <label class="frm-label" for="idioma_cli" data-txtid="txt-3004-0"></label>
		            <select class="input" name="idioma" id="idioma_cli" data-autocombo="true" data-content="txtcli" data-total="false">
		            <?php 
		                $s=$sqlCons[1][76]." WHERE fac_idioma.HAB_IDIOMA=0 ".$sqlOrder[1][76];
		                $req = $dbEmpresa->prepare($s);
		                $req->execute();
		                echo crear_select($req,'ID_IDIOMA','IDIOMA',0,1,'txt-1134-0');
		            ?>
		            </select>
			    </div><!--
             --><div class="col50"></div>
                <div class="final"></div>
            </div>
            
            <fieldset class="fieldset">
                <legend class="legend" data-txtid="txt-1215-0"></legend>
                <div data-id="txtcli" data-carga="true" data-tp="13" data-md="<?php echo $m_md?>"></div>
            </fieldset>
            <fieldset class="fieldset">
                <legend class="legend" data-txtid="txt-1112-0"></legend>
                <div data-id="txtcli" data-carga="true" data-tp="14" data-md="<?php echo $m_md?>"></div>
            </fieldset>
            <fieldset class="fieldset">
                <legend class="legend"><span data-txtid="txt-1083-0"></span> (<span data-txtid="txt-1112-0"></span>)</legend>
                <div data-id="txtcli" data-carga="true" data-tp="15" data-md="<?php echo $m_md?>"></div>
            </fieldset>
            <fieldset class="fieldset">
                <legend class="legend"><span data-txtid="txt-1112-0"></span> (<span data-txtid="txt-1226-0"></span>)</legend>
                <div data-id="txtcli" data-carga="true" data-tp="16" data-md="<?php echo $m_md?>"></div>
            </fieldset>
            <fieldset class="fieldset">
                <legend class="legend"><span data-txtid="txt-1223-0"></span> (<span data-txtid="txt-1226-0"></span>)</legend>
                <div data-id="txtcli" data-carga="true" data-tp="17" data-md="<?php echo $m_md?>"></div>
            </fieldset>
        </div>
    </form>
<?php
}
//Asignación de Contraseña

//TEXTOS DE EMPRESA
elseif($cnf==10000&&$det_plus==1){  
    $sWhere=encrip_mysql('adm_usuarios.ID_USUARIO');
    $s=$sqlCons[1][0]." WHERE $sWhere=:id LIMIT 1";
    
    $req = $dbEmpresa->prepare($s); 
    $req->bindParam(':id', $id_sha);
    $req->execute();    
    if(!$reg = $req->fetch()) exit(0);  

    $titulo="txt-1439-0";
    $sub_titulo=$reg["NOMBRE_U"].' '.$reg["APELLIDO_U"];
    ?>
    <form class="iform mid col_bg03" name="frm-subir" method="post" action="/toperation">
        <header class="frm-h">
            <h2 class="frm-tit col_titles" data-txtid="<?php echo $titulo ?>"></h2>
            <h3 class="frm-stit col_titles2"><?php echo $sub_titulo?></h3>
            <div class="x bt_col_close" data-close="form"><div class="cx"><i class="fa fa-close"></i></div></div>
        </header>
        <div class="frm-body">
            <label class="frm-label" for="password" data-txtid="txt-1439-0"></label>
            <input class="input" type="password" name="password" id="password" value=""/>

            <label class="frm-label" for="notificar"><span data-txtid="txt-1441-0"></span></label>
            <div class="options" id="notificar"> 
                <input type="radio" id="notificar1" name="notificar" value="1"                     /><label for="notificar1" data-txtid="txt-1002-0"></label>
                <input type="radio" id="notificar2" name="notificar" value="2" checked="checked"   /><label for="notificar2" data-txtid="txt-1003-0"></label>
            </div>

            <input type="hidden" name="md" id="md" value="<?php echo $md?>" />
            </div>
            <div class="message"></div>
            <div class="botones">
                <button class="button bt_col1 light" data-txtid="txt-1006-0"></button>
            </div>
        </div>
    </form>
<?php
}
else{
	if($_PROYECTO==1) include("op_frm_001.php");
	elseif($_PROYECTO==5) include("op_frm_005.php");
	elseif($_PROYECTO==7) include("op_frm_007.php");
	elseif($_PROYECTO==8) include("op_frm_008.php");
	elseif($_PROYECTO==10) include("op_frm_010.php");
	elseif($_PROYECTO==13) include("op_frm_013.php");
	elseif($_PROYECTO==14) include("op_frm_014.php");
	elseif($_PROYECTO==15) include("op_frm_015.php");
	elseif($_PROYECTO==16) include("op_frm_016.php");
	elseif($_PROYECTO==19) include("op_frm_019.php");
	elseif($_PROYECTO==20) include("op_frm_020.php");
	elseif($_PROYECTO==21) include("op_frm_021.php");
    elseif($_PROYECTO==22) include("op_frm_022.php");
    elseif($_PROYECTO==23) include("op_frm_023.php");
    elseif($_PROYECTO==24) include("op_frm_024.php");
    elseif($_PROYECTO==25) include("op_frm_025.php");
    elseif($_PROYECTO==26) include("op_frm_026.php");
    elseif($_PROYECTO==27) include("op_frm_027.php");
    elseif($_PROYECTO==28) include("op_frm_028.php");
    elseif($_PROYECTO==29) include("op_frm_029.php");
    elseif($_PROYECTO==31) include("op_frm_031.php");
    elseif($_PROYECTO==32) include("op_frm_032.php");
    elseif($_PROYECTO==38) include("op_frm_038.php");
    elseif($_PROYECTO==39) include("op_frm_039.php");
    elseif($_PROYECTO==40) include("op_frm_040.php");
    elseif($_PROYECTO==41) include("op_frm_041.php");
	elseif($_PROYECTO==42) include("op_frm_042.php");
    elseif($_PROYECTO==43) include("op_frm_043.php");
    elseif($_PROYECTO==45) include("op_frm_045.php");
}
?>

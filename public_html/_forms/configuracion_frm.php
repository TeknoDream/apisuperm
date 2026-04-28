<?php

$loadcont=(isset($_POST["loadcont"])?$_POST["loadcont"]:0);
$md=(isset($_GET["md"])?$_GET["md"]:'');
$c_sha=mb_substr($md,40,32);
$id_sha=mb_substr($md,0,40);
$t=mb_substr($md,72,1);


$c_sha=mb_substr($md,40,32);
$output=array();
$c_campo=encrip_mysql("adm_ventanas_cont.ID_VENTANA",2);
$sTitulos=$sqlCons[1][1]." WHERE $c_campo=:c_sha AND adm_ventanas_cont.HAB_VENTANA=0 ".$sqlOrder[1][1];

$reqTitulos = $dbEmpresa->prepare($sTitulos);
$reqTitulos->bindParam(':c_sha', $c_sha);
$reqTitulos->execute();	
CreaConsulta($c_sha,$reqTitulos,$output,$sArmado);
$cnf=$output["scons"]["cnf"];
$Titulos=$output["cols"]["Titulos"];
$ToolTip=$output["cols"]["ToolTip"];
$Tamano=$output["cols"]["Tamano"];
/*TABLA*/
if(nuevo_item()!=$id_sha){
	$id_campo=encrip_mysql($output["scons"]["id_campo"]);
    if($output["scons"]["mempresa"]==1)
	   $sArmado=$sArmado." WHERE $id_campo=:id AND ID_MEMPRESA=$_CLIENTE LIMIT 1";	
    else
        $sArmado=$sArmado." WHERE $id_campo=:id LIMIT 1"; 	
	$reqConsulta = $dbEmpresa->prepare($sArmado);
	$reqConsulta->bindParam(':id', $id_sha);	
	$reqConsulta->execute();
	$regConsulta=$reqConsulta->fetch();
}
else{
	$com_tit=$_textos[1009][0];
	$contConsulta=0;
	$regConsulta=array();
}
$sub_titulo=$regConsulta[$output["scons"]["tit_campo"]];
$verificar=$PermisosA[$cnf]["P"];
?>
    <form class="iform min col_bg03" name="frm-subir" method="post" action="/tsetconfig">
        <header class="frm-h">
            <h2 class="frm-tit col_titles" data-txtid="<?php echo $nuevo?'txt-1009-0':'txt-1017-0'?>"></h2>
            <h3 class="frm-stit col_titles2"><?php echo $sub_titulo?></h3>
            <div class="x bt_col_close" data-close="form"><div class="cx"><i class="fa fa-close"></i></div></div>
        </header>
        <div class="frm-body">
            <?php

            foreach ($Titulos as $i => $titulo){
                $option=$output["cols"]["option"][$i];
                $require=$option["req"];
                ?>
                <label class="frm-label req" for="N<?php echo $i?>"><?php echo $Titulos[$i]?></label>
                <?php
                if($option["tipo"]==0){
                ?>
                    
                    <input class="input" type="text" name="N<?php echo $i?>" id="N<?php echo $i?>" maxlength="<?php echo $Tamano[$i]?>" title="<?php echo imprimir($ToolTip[$i])?>" value="<?php echo imprimir($regConsulta[$i+2])?>" <?php echo $require?'data-required="true"':''?> />
                <?php 
                }
                elseif($option["tipo"]==1){
                ?>
                    
                    <input class="input" type="text" data-tipo="date" name="N<?php echo $i?>" id="N<?php echo $i?>" maxlength="<?php echo $Tamano[$i]?>" title="<?php echo imprimir($ToolTip[$i])?>" value="<?php echo imprimir($regConsulta[$i+2])?>" <?php echo $require?'data-required="true"':''?> />
                <?php 
                }
                elseif($option["tipo"]==2){
                ?>
                    
                    <input class="input" type="text" data-tipo="datetime" name="N<?php echo $i?>" id="N<?php echo $i?>" maxlength="<?php echo $Tamano[$i]?>" title="<?php echo imprimir($ToolTip[$i])?>" value="<?php echo imprimir($regConsulta[$i+2])?>" <?php echo $require?'data-required="true"':''?> />
                <?php 
                }
                elseif($option["tipo"]==3){
                ?>
                    
                    <input class="input" type="time" name="N<?php echo $i?>" id="N<?php echo $i?>" maxlength="<?php echo $Tamano[$i]?>" title="<?php echo imprimir($ToolTip[$i])?>" value="<?php echo imprimir($regConsulta[$i+2])?>" <?php echo $require?'data-required="true"':''?> />
                <?php 
                }
                elseif($option["tipo"]==4){
                ?>
                    
                    <input class="input" type="number" name="N<?php echo $i?>" id="N<?php echo $i?>" maxlength="<?php echo $Tamano[$i]?>" title="<?php echo imprimir($ToolTip[$i])?>" value="<?php echo imprimir($regConsulta[$i+2])?>" <?php echo $require?'data-required="true"':''?> />
                <?php 
                }
                elseif($option["tipo"]==5){
                ?>
                    <select class="input" name="N<?php echo $i?>" id="N<?php echo $i?>" <?php echo $require?'data-required="true"':''?>>
                    <?php 
                        $s=$sqlCons[0][$option["tabla"]].' '.$sqlOrder[0][$option["tabla"]];
                        $req0 = $dbEmpresa->prepare($s);
                        if($option["idioma"])    $req0->bindParam(':_IDIOMA', $_IDIOMA);
                        if($option["mempresa"])  $req0->bindParam(':_CLIENTE', $_CLIENTE);
                        if($option["gempresa"])  $req0->bindParam(':_GCLIENTE', $_GCLIENTE);
                        $req0->execute();
                        while($reg0 = $req0->fetch())  {
                        ?>
                            <option value="<?php echo $reg0[0]?>" <?php echo $reg0[0]==$regConsulta[$i+2]?'selected="selected"':''; ?>><?php echo $reg0[1] ?></option>
                        <?php
                        } 
                    ?>
                    </select>
                <?php
                }
            } ?>
                    
            <input  name="md" type="hidden" id="md" value="<?php echo $md?>"/>
        </div>
        <div class="message"></div>
        <div class="botones">
            <button class="button bt_col1 light" data-txtid="txt-1006-0"></button>
        </div>
    </form>

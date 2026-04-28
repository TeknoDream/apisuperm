<?php

$loadcont=isset($_POST["loadcont"])?$_POST["loadcont"]:0;
$md=isset($_GET["md"])?$_GET["md"]:'';
$c_sha=mb_substr($md,40,32);
$id_sha=mb_substr($md,0,40);
$t=mb_substr($md,104,1);

$accion=mb_substr($md,72,32);
$continuar=true;
$output=array();
$c_campo=encrip_mysql("adm_ventanas_cont.ID_VENTANA",2);
$sTitulos=$sqlCons[1][1]." WHERE $c_campo=:c_sha AND adm_ventanas_cont.HAB_VENTANA=0 ".$sqlOrder[1][1];
$reqTitulos = $dbEmpresa->prepare($sTitulos);
$reqTitulos->bindParam(':c_sha', $c_sha);
$reqTitulos->execute();	
CreaConsulta($c_sha,$reqTitulos,$output,$sArmado);
$cnf=$output["scons"]["cnf"];

$id_campo=encrip_mysql($output["scons"]["id_campo"]);
if($output["scons"]["mempresa"]==1)
	$sArmado=$sArmado." WHERE $id_campo=:id AND ID_MEMPRESA=$_CLIENTE LIMIT 1";	
else
	$sArmado=$sArmado." WHERE $id_campo=:id LIMIT 1";	
$reqConsulta = $dbEmpresa->prepare($sArmado);
$reqConsulta->bindParam(':id', $id_sha);	
$reqConsulta->execute();
$regConsulta=$reqConsulta->fetch();

$sub_titulo=$regConsulta[$output["scons"]["tit_campo"]];

if($accion==$acc01)
	$texto='<div data-txtid="txt-1026-0"></div><div class="_t1 medium" data-txtid="txt-1025-0"></div>';
elseif($accion==$acc02)
	$texto='<div data-txtid="txt-1027-0"></div><div class="_01 medium" data-txtid="txt-1025-0"></div>';

?>
	<form class="iform min col_bg03" name="frm-subir" method="post" action="/tdelconfig">
		<header class="frm-h">
			<h2 class="frm-tit col_titles" data-txtid="txt-1005-0"></h2>
			<h3 class="frm-stit col_titles2"><?php echo $sub_titulo?></h3>
			<div class="x bt_col_close" data-close="form"><div class="cx"><i class="fa fa-close"></i></div></div>
		</header>
		<div class="frm-body">
			<div class="p"><?php echo $texto; ?></div>
	        <input type="hidden" name="md" id="md" value="<?php echo $md?>" />
        </div>
		<input type="hidden" name="md" value="<?php echo $md?>" />

		<div class="message"></div>
		<?php if($continuar){ ?>
		<div class="botones">
			<button class="button bt_col1 light" data-txtid="txt-1006-0"></button>
		</div>
		<?php } ?>
	</form>
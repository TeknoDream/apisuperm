<?php
$loadcont=isset($_POST["loadcont"])?$_POST["loadcont"]:0;
$md=isset($_GET["md"])?$_GET["md"]:"";
$c_sha=mb_substr($md,40,32);
$id_sha=mb_substr($md,0,40);
$accion=mb_substr($md,72,32);
$id_sha_t=mb_substr($md,104,40);

$continuar=true;

/*ESTO ES IGUAL*/

$sWhere=encrip_mysql('adm_ventanas.ID_VENTANA',2);
$s=$sqlCons[1][71]." WHERE $sWhere=:c_sha LIMIT 1";
$req = $dbEmpresa->prepare($s); 
$req->bindParam(':c_sha', $c_sha);
$req->execute();	
if($reg = $req->fetch()){
	$cnf=$reg["ID_VENTANA"];
}

if($cnf==36) 	$permiso=$PermisosA[8]["P"];
else 			$permiso=$PermisosA[$cnf]["P"];


if($cnf==19){		
	$sWhere=encrip_mysql('s_cresp.ID_RESP');
	$s=$sqlCons[1][101]." WHERE $sWhere=:id LIMIT 1";
	$req = $dbEmpresa->prepare($s); 
	$req->bindParam(':id', $id_sha);
	$req->execute();	
	$reg = $req->fetch();
	$Campo=$reg["NOMB_RESP"];
}
elseif($cnf==36){
	if(($accion==$acc01)||($accion==$acc02)){
		$sWhere=encrip_mysql('adm_usuarios.ID_USUARIO');
		$s=$sqlCons[1][0]." WHERE $sWhere=:id LIMIT 1";	
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		$req->execute();	
		$reg = $req->fetch();
		$Campo=sprintf("%s %s",$reg["NOMBRE_U"],$reg["APELLIDO_U"]);
	}
	elseif($accion==$acc03){
		$texto='<div class="p" data-txtid="txt-1433-1"></div> <div class="p _01 medium" data-txtid="txt-1025-0"></div>';
	}
}
elseif($cnf==8){
	if(($accion==$acc01)||($accion==$acc02)){
		$sWhere=encrip_mysql('adm_grupos.ID_GRUPO');
		$s=$sqlCons[1][64]." WHERE $sWhere=:id LIMIT 1";
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		$req->execute();	
		$reg = $req->fetch();
		$Campo=$reg["DESC_GRUPO"];
	}
}
else{
	if($_PROYECTO==1) 		include("base_msg_001.php");
	elseif($_PROYECTO==5) 	include("base_msg_005.php");
	elseif($_PROYECTO==7) 	include("base_msg_007.php");
	elseif($_PROYECTO==8) 	include("base_msg_008.php");
	elseif($_PROYECTO==10) 	include("base_msg_010.php");
	elseif($_PROYECTO==13) 	include("base_msg_013.php");
	elseif($_PROYECTO==14) 	include("base_msg_014.php");
	elseif($_PROYECTO==15) 	include("base_msg_015.php");
	elseif($_PROYECTO==16) 	include("base_msg_016.php");
	elseif($_PROYECTO==18) 	include("base_msg_018.php");
	elseif($_PROYECTO==19) 	include("base_msg_019.php");
	elseif($_PROYECTO==20) 	include("base_msg_020.php");
	elseif($_PROYECTO==21) 	include("base_msg_021.php");
	elseif($_PROYECTO==22) 	include("base_msg_022.php");
	elseif($_PROYECTO==23) 	include("base_msg_023.php");
	elseif($_PROYECTO==24) 	include("base_msg_024.php");
	elseif($_PROYECTO==25) 	include("base_msg_025.php");
	elseif($_PROYECTO==26) 	include("base_msg_026.php");
	elseif($_PROYECTO==27) 	include("base_msg_027.php");
	elseif($_PROYECTO==29) 	include("base_msg_029.php");
	elseif($_PROYECTO==32) 	include("base_msg_032.php");
	elseif($_PROYECTO==43) 	include("base_msg_043.php");
}
$s=$sqlCons[1][71]." WHERE adm_ventanas.ID_VENTANA='$cnf'";
$req = $dbEmpresa->prepare($s); 
$req->execute();	
$reg = $req->fetch();
$tabla_titulo=$reg["VENTANA_NOMBRE"];
if($accion==$acc01)
	$texto='<div data-txtid="txt-1026-0"></div><div class="_t1 medium" data-txtid="txt-1025-0"></div>';
elseif($accion==$acc02)
	$texto='<div data-txtid="txt-1027-0"></div><div class="_01 medium" data-txtid="txt-1025-0"></div>';

if($texto=='')
	$texto='<div data-txtid="txt-1026-0"></div><div class="_t1 medium" data-txtid="txt-1025-0"></div>';
?>
	<form class="iform min col_bg03" name="frm-subir" method="post" action="/tdelete">
		<header class="frm-h">
			<h2 class="frm-tit col_titles" data-txtid="txt-1005-0"></h2>
			<h3 class="frm-stit col_titles2"><?php echo $Campo?></h3>
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




<?php
$loadcont=isset($_POST["loadcont"])?$_POST["loadcont"]:0;
$md=isset($_GET["md"])?$_GET["md"]:'';
$id_sha=mb_substr($md,0,40);
$c_sha=mb_substr($md,40,32);
$id_sha_t=mb_substr($md,72,40);
$det_plus=intval(mb_substr($md,112,3));

$c_shaf=encrip(80,2);
?>
<div class="siie_carga" data-pagina="4" data-bodyclass="" data-href="<?php echo mb_substr($_SERVER["REQUEST_URI"],1)?>">
<?php
	$sWhere=encrip_mysql('adm_informes_detalle.ID_INFORME');
	$s=$sqlCons[1][73]." AND $sWhere=:id LIMIT 1";
	$req = $dbEmpresa->prepare($s); 
	$req->bindParam(':id', $id_sha);
	$req->execute();
	if(!$reg = $req->fetch()) exit(0);


	if($reg["TIPO_INFORME"]==0){ //1 es HV
	    $c_sha=encrip($reg["ID_VENTANA"],2); 
	    $md=$id_sha.$c_sha;
	}

	$infId=$reg["ID_INFORME"];
	$id_sha_n=md5($reg["ID_INFORME"]);
	$titulo=$reg["NOMB_INFORME"];
	$sub_titulo=$reg["REF_INFORME"];
	$link=cambiar_url($titulo);			
	
	
	$background_img='style="background-image:url(/picint/'.$picname.')"';



	if($_PROYECTO==1) include("reports_frm_001.php");
	elseif($_PROYECTO==8) include("reports_frm_008.php");
	elseif($_PROYECTO==11) include("reports_frm_011.php");
	elseif($_PROYECTO==13) include("reports_frm_013.php");
	elseif($_PROYECTO==15) include("reports_frm_015.php");
	elseif($_PROYECTO==16) include("reports_frm_016.php");
	elseif($_PROYECTO==19) include("reports_frm_019.php");
	elseif($_PROYECTO==20) include("reports_frm_020.php");
	elseif($_PROYECTO==22) include("reports_frm_022.php");
	elseif($_PROYECTO==25) include("reports_frm_025.php");
	elseif($_PROYECTO==31) include("reports_frm_031.php");
	elseif($_PROYECTO==32) include("reports_frm_032.php");
?>


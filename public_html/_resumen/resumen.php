<?php
//
$md=isset($_REQUEST["md"])?$_REQUEST["md"]:'';
$c_sha=mb_substr($md,40);
$id_sha=mb_substr($md,0,-32);
//

$sWhere=encrip_mysql('adm_ventanas.ID_VENTANA',2);
$s=$sqlCons[1][71]." WHERE $sWhere=:c_sha LIMIT 1";
$req = $dbEmpresa->prepare($s); 
$req->bindParam(':c_sha', $c_sha);
$req->execute();	
if($reg = $req->fetch()){
	$id_ventana=$reg["ID_VENTANA"];
}
$permiso=$PermisosA[$cnf]["P"];
?>
<div class="md_carga resumen" data-tpage="1" data-cnf="<?php echo $id_ventana?>" data-md="<?php echo $md?>"></div>
<?php

$cnf=isset($_REQUEST["cnf"])?intval($_REQUEST["cnf"]):1;
$PagActual=isset($_REQUEST["p"])?$_REQUEST["p"]:1;
$t=isset($_REQUEST["fl-t"])?$_REQUEST["fl-t"]:1;
$tp=isset($_REQUEST["tp"])?$_REQUEST["tp"]:0;
///////////////////////////////////////////////
$busc=isset($_REQUEST["busc"])?imprimir($_REQUEST["busc"]):'';
$busc_query='%'.$_REQUEST["busc"].'%';
$busc_send=isset($_REQUEST["busc"])?$_REQUEST["busc"]:'';
///////////////////////////////////////////////

$permiso=$PermisosA[$cnf]["P"];

$c_sha=encrip($cnf,2);
$nuevo_tag=nuevo_item().$c_sha;
$_NuevoI=nuevo_item();

///////////////////////////////////////////////
//LEE TODO EL GET
$salidas=array();
foreach($_REQUEST as $key => $val){
	if($key!='__route__'&&$key!='_AJAX')
		$salidas["parAd"][$key]=$val; 
	
} 
$salidas["parAd"]['fl-t']=$t;
////////////UBICACIONES////////////////////////


if($tp==1) 	$idMaxItem=2; 
else 		$idMaxItem=1;
$MaxItems=$NMaxItems[$idMaxItem];



if($_PROYECTO==1)			include("oprealtime_001.php"); //ROCKETMP
elseif($_PROYECTO==8)		include("oprealtime_008.php"); //FALCONCRM
elseif($_PROYECTO==10)		include("oprealtime_010.php"); //TUPYME
elseif($_PROYECTO==13)		include("oprealtime_013.php"); //CIUDAD TRAVEL
elseif($_PROYECTO==14)		include("oprealtime_014.php"); //EVENTOS CCB
elseif($_PROYECTO==22)		include("oprealtime_022.php"); //R
echo json_encode($salidas);
?>
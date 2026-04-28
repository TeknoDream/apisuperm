<?php


//////////////////////////////////////////////
$result=$_GET;
$md=isset($_GET["md"])?$_GET["md"]:'';
$c_sha=mb_substr($md,40);
$id_sha=mb_substr($md,0,-32);
/*
$md_object=isset($_GET["md"])?$_GET["md"]:'';
$c_sha_def=mb_substr($md_object,40);
$id_sha_def=mb_substr($md_object,0,-32);
*/


///////////////////////////////////////////////
$fil=isset($_GET["fil"])?$_GET["fil"]:0;
$tinfo=isset($_GET["tinfo"])?$_GET["tinfo"]:0;
$loginf=isset($_GET["loginf"])?$_GET["loginf"]:0;

/**/

$salidas=array();

$sWhere=encrip_mysql('adm_informes_detalle.ID_INFORME');
$s=$sqlCons[1][73]." AND $sWhere=:id LIMIT 1";
$req = $dbEmpresa->prepare($s); 
$req->bindParam(':id', $id_sha);
$req->execute();
if(!$reg = $req->fetch()) exit(0);
$infId=$reg["ID_INFORME"];

if($_PROYECTO==8)		include("reports_inc_008.php"); //FALCONCRM
elseif($_PROYECTO==16)		include("reports_inc_016.php"); //DISPONIBLES
elseif($_PROYECTO==19)		include("reports_inc_019.php"); //SCA
elseif($_PROYECTO==20)		include("reports_inc_020.php"); //Appetitos
elseif($_PROYECTO==22)		include("reports_inc_022.php"); //Rocket
elseif($_PROYECTO==25)		include("reports_inc_025.php"); //IER
elseif($_PROYECTO==31)		include("reports_inc_031.php"); //CheckIn
elseif($_PROYECTO==32)		include("reports_inc_032.php"); //Super Maestro
echo json_encode($salidas);
?>
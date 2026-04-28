<?php

$result=$_GET;
$md=isset($_GET["md"])?$_GET["md"]:'';
$c_sha=mb_substr($md,40);
$id_sha=mb_substr($md,0,-32);
$salidas=array();

$sWhere=encrip_mysql('adm_informes_detalle.ID_INFORME');
$s=$sqlCons[1][73]." AND $sWhere=:id LIMIT 1";
$req = $dbEmpresa->prepare($s); 
$req->bindParam(':id', $id_sha);
$req->execute();
if(!$reg = $req->fetch()) exit(0);
$infId=$reg["ID_INFORME"];

/***************/
/***************/
/*ESCRIBIR LOG*/
/***************/
/***************/
try{  				
	$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
	$dbEmpresa->beginTransaction();
	
	$s="INSERT INTO log_reports
			(ID_MEMPRESA,ID_USUARIO,ID_INFORME,FECHA_LOGREP)
	VALUES($_CLIENTE,$_USUARIO,$infId,UTC_TIMESTAMP())";
	$reqInf = $dbEmpresa->prepare($s); 
	$reqInf->execute();						
	$idLog=$dbEmpresa->lastInsertId();
	
	
	foreach($result as $key => $val){
		$s="INSERT INTO log_reports_attr
				(ID_LOGREP,ATR_REPCOND,VAL_REPCOND)
		VALUES($idLog,:key,:val)";
		$reqAtr = $dbEmpresa->prepare($s); 
		$reqAtr->bindParam(':key', $key);
		$reqAtr->bindParam(':val', $val);
		$reqAtr->execute();
	}  
	$dbEmpresa->commit();
}
catch (Exception $e){
	$dbEmpresa->rollBack();
	$err_str=$e->getMessage();			
}
/***************/
/***************/
/***************/
if($_PROYECTO==8)			include("reports_prev_008.php"); //FALCONCRM
elseif($_PROYECTO==16)		include("reports_prev_016.php"); //DISPONIBLES
elseif($_PROYECTO==19)		include("reports_prev_019.php"); //SCA
elseif($_PROYECTO==20)		include("reports_prev_020.php"); //APPETITOS
elseif($_PROYECTO==22)		include("reports_prev_022.php"); //ROCKET
elseif($_PROYECTO==25)		include("reports_prev_025.php"); //IER
elseif($_PROYECTO==31)		include("reports_prev_031.php"); //CheckIn
elseif($_PROYECTO==32)		include("reports_prev_032.php"); //Super Maestro
echo json_encode($salidas);	
?>
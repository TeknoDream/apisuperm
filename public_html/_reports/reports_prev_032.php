<?php
//SPM-P01 SPM-P02 SPM-P03 SPM-P04 SPM-P05
if($infId==7||$infId==8||$infId==9||$infId==10||$infId==11){
	
	$k=0;
	$salidas["menu"][$k]["label"]='txt-1193-0'; //INFORME
	$salidas["menu"][$k]["tinfo"]=1;
	$salidas["menu"][$k]["loginf"]=$idLog;	
	/**********************************/
	/**********************************/
	/**********************************/

	$salidas["idsha"]=$id_sha;
	$salidas["tipobox"]=2;
	
	$salidas["titulo"]=imprimir($reg["NOMB_INFORME"]);
	
	/*DATOS LATERAL*/
	$salidas["info"]=array();	
	$i=0;
	$salidas["info"][$i]["desc"]='txt-1061-0'; //NOTA
	$salidas["info"][$i]["data"]=sprintf($reg["DESC_INFORME"],$result['evento']);
}
?>
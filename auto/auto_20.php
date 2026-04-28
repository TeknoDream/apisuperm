<?php
//ini_set('display_errors', 'On');
//ini_set('display_startup_errors', 'Off');

error_reporting(E_ERROR | E_WARNING | E_PARSE);

$_PROYECTO=20;
$_EMPRESA=16;
include "auto_00.php";

$_sysvars=array('project'=>$_PROYECTO,'company'=>$_EMPRESA);
$_sysvars_r=$_sysvars;
$state=ConectarseAUTO($dbEmpresa,$_sysvars);
if(!$state){
	echo 'Sin Conexión';
	exit(0);
}
include 		"/var/www/siie/public_html/phplib/variables_se.php";
Consultas($sqlCons,$sqlOrder,$_PROYECTO,$_CLIENTE,$_IDIOMA,$_TZ,$_GCLIENTE,$_USUARIO,$_GRUPO,$_PARAMETROS);
$_PARAMETROS=ParametrosAPI($dbEmpresa,$_sysvars_r,$_sysvars);
CuerpoMail($Email,$_PROYECTO,$_EMPRESA,$_CLIENTE,$_IDIOMA,$_TZ,$_GCLIENTE,$_USUARIO,$_GRUPO,$_PARAMETROS);

/******************************/
/******************************/
/******************************/
/******************************/

foreach ($argv as $key => $value) {
	$val_s=explode("=", $value);
	$results[$val_s[0]]=$val_s[1];
}
$tp=$results["tp"];

if($tp==1){  // RECORDATORIOS
	if($_PARAMETROS["M_COPY"]==1){
		$s=$sqlCons[0][905].' WHERE ISNULL(p_scart_status.STATUS) AND TIME_TO_SEC(TIMEDIFF(UTC_TIMESTAMP(),p_scart.FECHAS_SCART))>480 AND adm_empresas.ID_MEMPRESA<>1 ';
		$reqItem = $dbEmpresa->prepare($s); 
		$reqItem->execute();
		if($regItem = $reqItem->fetch()){			
			
			$to[0]["name"]=$_PARAMETROS["M_TONAME"];	
			$to[0]["mail"]=$_PARAMETROS["M_TOMAIL"];

			$to[1]["name"]='Contact Center';	
			$to[1]["mail"]='contactcenter@motumdata.com';

			$Asunto=sprintf($Email[1][752]['title'],$regItem["ID_SCART"]);
			$html_cont=sprintf($Email[1][752]['body']
					,	$regItem["ID_SCART"]
					,	$regItem["T_DIF"]
					,	$regItem["NOMB_LOCAT"].' '.$regItem["APEL_LOCAT"]
					,	$regItem["TEL1_LOCAT"]
					,	$regItem["TEL2_LOCAT"]
					,	$regItem["DIREC_LOCAT"]					
					,	$regItem["REFE_LOCAT"]
					,	$regItem["BARRIO_LOCAT"]
					,	number_format($regItem["COST_SCART"],0)
					,	$regItem["TYPE_PHONE"]==0?'Web':($regItem["TYPE_PHONE"]==1?'Android':'iOS')
					,	$regItem["FECHAS_SCART"]					
					,	$regItem["NCOM_MEMPRESA"]
					,	$regItem["NOMB_RESP"]);
					
			$rtamail=send_email_srv($_PARAMETROS,$Asunto,$html_cont,$to,$cc,array(),true,$Email[1][551]['alt']);
			$salidas["rta_mail"][$regItem["ID_USUARIO"]]=$rtamail;
		}		
	}
}
elseif($tp==2){  // AJUSTAR APERTURAS

	$ESTTZ = new DateTimeZone('UTC');
	$hoyOBJ = new DateTime(date(DATE_ATOM),$ESTTZ); 
	$hoySTR=$hoyOBJ->format('Y-m-d H:i');
	
	$s="CREATE TEMPORARY TABLE IF NOT EXISTS t_open("
		.	$sqlCons[12][901].' WHERE s_cresp.HAB_RESP=0 AND adm_empresas.HAB_MEMPRESA=0 '
		.	')';

	$req = $dbEmpresa->prepare($s);			
	$req->bindParam(':DateTime', $hoySTR);
	$req->execute();

	$s='INSERT INTO s_cresp_status
		(ID_RESP,
		ID_MEMPRESA,
		GSTATUS_RESP,
		TSTATUS_RESP)
		(
			SELECT ID_RESP
			,	ID_MEMPRESA
			,	1
			,	1	
			FROM t_open WHERE  TFALTAA<=0 AND TFALTAC>=0
		)
		ON DUPLICATE KEY UPDATE
			TSTATUS_RESP=1
		,	GSTATUS_RESP=IF(FSTATUS_RESP=2,0,1)';
	$dbEmpresa->exec($s);	

	$s='UPDATE s_cresp_status
	SET TSTATUS_RESP=0
	,	GSTATUS_RESP=IF(FSTATUS_RESP=1,1,0)
	WHERE ID_RESP NOT IN (SELECT t_open.ID_RESP FROM t_open WHERE TFALTAA<=0 AND TFALTAC>=0)';
	$dbEmpresa->exec($s);

	$s='INSERT INTO t_restaurant_sstatus
	(ID_MEMPRESA,STATUS_MEMPRESA)
	(SELECT ID_MEMPRESA,GSTATUS_RESP FROM s_cresp_status WHERE GSTATUS_RESP=1 GROUP BY ID_MEMPRESA)
	ON DUPLICATE KEY UPDATE STATUS_MEMPRESA=1';
	$dbEmpresa->exec($s);

	$s='UPDATE t_restaurant_sstatus
	SET STATUS_MEMPRESA=0
	WHERE ID_MEMPRESA NOT IN (SELECT s_cresp_status.ID_MEMPRESA FROM s_cresp_status WHERE s_cresp_status.GSTATUS_RESP=1)';
	$dbEmpresa->exec($s);
}
elseif($tp==3){
	$_URLS=array();
	$_CATS=array();

	$s=$sqlCons[0][904].' WHERE t_categoryr.TYP_CATR IN (0,1) AND t_categoryr.HAB_CATR=0';
	$reqCAT=$dbEmpresa->prepare($s); 
	$reqCAT->execute();
	while($regCAT = $reqCAT->fetch()){
		$link=$regCAT['SLUG_CATR'];			
		$_CATS[]=$link;
		$_URLS[]='/categoria/'.$link;		
	}

	$s=$sqlCons[10][909];
	$reqArea = $dbEmpresa->prepare($s); 
	$reqArea->execute();
	while($regArea = $reqArea->fetch()){
		$link=$regArea['SLUG_AREA'];
		$idArea=$regArea['ID_AREA'];
		$prom=$regArea['PROM_AREA'];		
		$_URLS[]='/'.$link;		
		if($prom==0){	
			$_URLS[]='/'.$link.'/todos';		
			$s=$sqlCons[1][901].
			' WHERE s_cresp.HAB_RESP=0 
						AND adm_empresas.HAB_MEMPRESA=0 
						AND t_restaurant.PUBLIC_MEMPRESA=1 
						AND s_cresp.ID_CIUDAD IN (
								SELECT t_area_city.ID_CIUDAD 
								FROM t_area_city
								WHERE t_area_city.ID_AREA=:idArea)';
			$reqItem = $dbEmpresa->prepare($s); 
			$reqItem->bindParam(':idArea', $idArea);
			$reqItem->execute();
			while($regItem = $reqItem->fetch()){
				$_URLS[]='/'.$link.'/'.$regItem['SLUG_MEMPRESA'].'/'.$regItem['SLUG_RESP'];
			}
			foreach ($_CATS as $CAT) {
				$_URLS[]='/'.$link.'/categoria/'.$CAT;
			}
		}
	}

	$sitemap=new DomDocument("1.0","UTF-8");

	// create root element
	$root = $sitemap->createElement("urlset");
	$sitemap->appendChild($root);

	$root_attr = $sitemap->createAttribute('xmlns'); 
	$root->appendChild($root_attr); 

	$root_attr_text = $sitemap->createTextNode('http://www.sitemaps.org/schemas/sitemap/0.9'); 
	$root_attr->appendChild($root_attr_text); 

	foreach($_URLS as $http_url){

	        // create child element
	        $url = $sitemap->createElement("url");
	        $root->appendChild($url);

	        $loc = $sitemap->createElement("loc");
	        /*$lastmod = $sitemap->createElement("lastmod");
	        $changefreq = $sitemap->createElement("changefreq");*/

	        $url->appendChild($loc);
	        $url_text = $sitemap->createTextNode($_PARAMETROS['LWSERVICE'].$http_url);
	        $loc->appendChild($url_text);

	        /*$url->appendChild($lastmod);
	        $lastmod_text = $sitemap->createTextNode(date("Y-m-d"));
	        $lastmod->appendChild($lastmod_text);

	        $url->appendChild($changefreq);
	        $changefreq_text = $sitemap->createTextNode("weekly");
	        $changefreq->appendChild($changefreq_text);*/

	}
	$file = "/var/www/appetitos/public_html/sitemap.xml";
	$fh = fopen($file, 'w') or die("Can't open the sitemap file.");
	fwrite($fh, $sitemap->saveXML());
	fclose($fh);
}
?>
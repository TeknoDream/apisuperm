<?php
//ini_set('display_errors', 'On');
//ini_set('display_startup_errors', 'Off');

error_reporting(E_ERROR | E_WARNING | E_PARSE);

$_PROYECTO=23;
$_EMPRESA=19;
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

if($tp==3){
	$_URLS=array();
	$_CATS=array();

	$s=$sqlCons[0][904].' WHERE t_categoryr.HAB_CATR=0';
	$reqCAT=$dbEmpresa->prepare($s); 
	$reqCAT->execute();
	while($regCAT = $reqCAT->fetch()){
		$link=$regCAT['SLUG_CATR'];			
		$_CATS[]=$link;
		$_URLS[]='/'.$link;		
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
				$_URLS[]='/'.$link.'/'.$CAT;
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
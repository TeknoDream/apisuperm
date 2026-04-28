<?php
//ini_set('display_errors', 'On');
//ini_set('display_startup_errors', 'Off');
use 			Aws\Common\Aws;	

error_reporting(E_ERROR | E_WARNING | E_PARSE);

$_PROYECTO=25;
$_EMPRESA=21;
include "auto_00.php";
require 		"/var/www/siie/public_html/phplib/s3/aws.phar";


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
$Folder='/var/www/siie/sync/';

$VALID_FOTOS=false;

if($tp==1){  // RECORDATORIOS
	
	/* INSERTA/ACTUALIZA  BORRA*/

	$files=array(
		'municipio'=> 'municipio.csv'
	,	'zonas'=> 'zonas.csv'
	,	'barrios'=> 'barrios.csv'
	,	'conceptos'=> 'conceptos.csv'
	,	'pot'=> 'pot.csv'
	,	'tipo_in'=> 'tipo_in.csv'
	,	'clientes'=> 'clientes.csv'
	,	'clientes_ventas'=> 'clientes_ventas.csv'
	,	'inmueble'=> 'inmueble.csv'
	,	'inmueble_ventas'=>'inmueble_ventas.csv'
	,	'det_inmueble'=> 'det_inmueble.csv'
	,	'det_inmueble_ventas'=> 'det_inmueble_ventas.csv'
	,	'deudas'=> 'deudas.csv'
	,	'nov_pro'=> 'nov_pro.csv');

	
	$VALID_FOTOS=file_exists($Folder.$files['inmueble'])&&file_exists($Folder.$files['det_inmueble_ventas']);

	$idIndexInmuebles=array();
	$idIndexClientes=array();

	$TradTab=array(
			'municipio'				=>'x_municipio'
		,	'zonas'					=>'x_zonas'
		,	'barrios'				=>'x_barrios'
		,	'tipo_in'				=>'y_tinm'
		,	'det_inmueble'			=>'y_caract'
		,	'det_inmueble_ventas'	=>'y_caract');
	$Affected=array();


	foreach ($files as $key => $UFile) {
		$kFile=0;
		$DataTotal=array();
		$UDFIle=$Folder.$UFile;
		if(file_exists($UDFIle)){
			if (($file_csv = fopen($UDFIle, "r")) !== FALSE) {
			    while (($data_line = fgetcsv($file_csv, 2000, ";")) !== FALSE) {
			    	if($kFile==0) $TitLinea=$data_line;
			    	else{
			    		foreach($data_line as $iL => $DLine){
			    			$DBefore=utf8_encode($data_line[$iL]);
			    			if(substr($DBefore, -1)==';')	$DBefore=substr($DBefore, 0, -1);
			    			$DataTotal[$kFile-1][$TitLinea[$iL]]=trim($DBefore);
			    		}
			    	}						
					$kFile++;
			    }
			    fclose($file_csv);
			}

			//////////////////////////
			//////////////////////////
			//////////////////////////
			//////////////////////////
			if(!isset($Affected[$TradTab[$key]]))
				$Affected[$TradTab[$key]]=0;
			if($key=='municipio'&&count($DataTotal)>0){
				$idIndex=array();
				$s='INSERT INTO x_municipio
					(ID_MUNIC
				,	NOMB_MUNIC)
				VALUES
					(:cod_mun
				,	:nom_mun)
				ON DUPLICATE KEY UPDATE
					NOMB_MUNIC=:nom_mun
				,	HAB_MUNIC=0';
				$req = $dbEmpresa->prepare($s);	
				foreach ($DataTotal as $kData => $vData) {		
					$req->bindParam(':cod_mun', $vData['cod_mun']); 
					$req->bindParam(':nom_mun', $vData['nom_mun']); 
					$req->execute();
					$idIndex[]=$vData['cod_mun'];
					$Affected[$TradTab[$key]]+=$req->rowCount();
				}

				if(count($idIndex)){
					$s='UPDATE x_municipio SET HAB_MUNIC=1 WHERE ID_MUNIC NOT IN ("'.implode('","',$idIndex).'")';
					$req = $dbEmpresa->prepare($s);
					$req->execute();
					$Affected[$TradTab[$key]]+=$req->rowCount();
				}
			}
			//////////////////////////
			//////////////////////////
			//////////////////////////
			//////////////////////////
			elseif($key=='zonas'&&count($DataTotal)>0){
				$idIndex=array();
				$s='INSERT INTO x_zonas
					(ID_ZONA
				,	NOMB_ZONA)
				VALUES
					(:cod_zon
				,	:nom_zon)
				ON DUPLICATE KEY UPDATE
					NOMB_ZONA=:nom_zon
				,	HAB_ZONA=0';
				$req = $dbEmpresa->prepare($s);	
				foreach ($DataTotal as $kData => $vData) {
					$req->bindParam(':cod_zon', $vData['cod_zon']); 
					$req->bindParam(':nom_zon', $vData['nom_zon']); 
					$req->execute();
					$idIndex[]=$vData['cod_zon'];
					$Affected[$TradTab[$key]]+=$req->rowCount();
				}
				if(count($idIndex)){
					$s='UPDATE x_zonas SET HAB_ZONA=1 WHERE ID_ZONA NOT IN ("'.implode('","',$idIndex).'")';
					$req = $dbEmpresa->prepare($s);
					$req->execute();
					$Affected[$TradTab[$key]]+=$req->rowCount();
				}
			}
			//////////////////////////
			//////////////////////////
			//////////////////////////
			//////////////////////////
			elseif($key=='barrios'&&count($DataTotal)>0){			
				$idIndex=array();
				$s='INSERT INTO x_barrios
					(ID_BARR
				,	NOMB_BARR
				,	ID_ZONA
				,	ID_MUNIC)
				VALUES
					(:cod_bar
				,	:nom_bar
				,	:cod_zon
				,	:cod_mun)
				ON DUPLICATE KEY UPDATE
					NOMB_BARR=:nom_bar
				,	ID_ZONA=:cod_zon
				,	ID_MUNIC=:cod_mun
				,	HAB_BARR=0';
				$req = $dbEmpresa->prepare($s);	
				foreach ($DataTotal as $kData => $vData) {		
					$req->bindParam(':cod_bar', $vData['cod_bar']); 
					$req->bindParam(':nom_bar', $vData['nom_bar']); 
					$req->bindParam(':cod_zon', $vData['cod_zon']); 
					$req->bindParam(':cod_mun', $vData['cod_mun']); 
					$req->execute();
					$idIndex[]=$vData['cod_bar'];
					$Affected[$TradTab[$key]]+=$req->rowCount();
				}
				if(count($idIndex)){
					$s='UPDATE x_barrios SET HAB_BARR=1 WHERE ID_BARR NOT IN ("'.implode('","',$idIndex).'")';
					$req = $dbEmpresa->prepare($s);
					$req->execute();
					$Affected[$TradTab[$key]]+=$req->rowCount();
				}
			}
			//////////////////////////
			//////////////////////////
			//////////////////////////
			//////////////////////////
			elseif($key=='conceptos'&&count($DataTotal)>0){
				$idIndex=array();
				$s='INSERT INTO x_concept
					(ID_CONCEPT
				,	DET_CONCEPT)
				VALUES
					(:cod_con
				,	:conc)
				ON DUPLICATE KEY UPDATE
					DET_CONCEPT=:conc
				,	HAB_CONCEPT=0';
				$req = $dbEmpresa->prepare($s);	
				foreach ($DataTotal as $kData => $vData) {		
					$req->bindParam(':cod_con', $vData['cod_con']); 
					$req->bindParam(':conc', $vData['conc']); 
					$req->execute();
					$idIndex[]=$vData['cod_con'];
				}
				if(count($idIndex)){
					$s='UPDATE x_concept SET HAB_CONCEPT=1 WHERE ID_CONCEPT NOT IN ("'.implode('","',$idIndex).'")';
					$req = $dbEmpresa->prepare($s);
					$req->execute();
				}
			}
			//////////////////////////
			//////////////////////////
			//////////////////////////
			//////////////////////////
			elseif($key=='pot'&&count($DataTotal)>0){
				$idIndex=array();
				$s='INSERT INTO y_pot
					(ID_POT
				,	DESC_POT)
				VALUES
					(:cod_pot
				,	:des_pot)
				ON DUPLICATE KEY UPDATE
					DESC_POT=:des_pot
				,	HAB_POT=0';
				$req = $dbEmpresa->prepare($s);	
				foreach ($DataTotal as $kData => $vData) {		
					$req->bindParam(':cod_pot', $vData['cod_pot']); 
					$req->bindParam(':des_pot', $vData['des_pot']); 
					$req->execute();
					$idIndex[]=$vData['cod_pot'];
				}
				if(count($idIndex)){
					$s='UPDATE y_pot SET HAB_POT=1 WHERE ID_POT NOT IN ("'.implode('","',$idIndex).'")';
					$req = $dbEmpresa->prepare($s);
					$req->execute();
				}
			}
			//////////////////////////
			//////////////////////////
			//////////////////////////
			//////////////////////////
			elseif($key=='t_cons'&&count($DataTotal)>0){ // OBSOLETO
				$idIndex=array();
				$s='INSERT INTO y_tneg
					(ID_TNEG
				,	NOMB_TNEG)
				VALUES
					(:tip_cons
				,	:des_cons)
				ON DUPLICATE KEY UPDATE
					NOMB_TNEG=:des_cons
				,	HAB_TNEG=0';
				$req = $dbEmpresa->prepare($s);	
				foreach ($DataTotal as $kData => $vData) {		
					$req->bindParam(':tip_cons', $vData['tip_cons']); 
					$req->bindParam(':des_cons', $vData['des_cons']); 
					$req->execute();
					$idIndex[]=$vData['tip_cons'];
					$Affected[$TradTab[$key]]+=$req->rowCount();
				}
				if(count($idIndex)){
					$s='UPDATE y_tneg SET HAB_TNEG=1 WHERE ID_TNEG NOT IN ("'.implode('","',$idIndex).'")';
					$req = $dbEmpresa->prepare($s);
					$req->execute();
					$Affected[$TradTab[$key]]+=$req->rowCount();
				}
			}
			//////////////////////////
			//////////////////////////
			//////////////////////////
			//////////////////////////
			elseif($key=='tipo_in'&&count($DataTotal)>0){
				$idIndex=array();
				$s='INSERT INTO y_tinm
					(ID_TINM
				,	NOMB_TINM)
				VALUES
					(:tip_inm
				,	:des_tip)
				ON DUPLICATE KEY UPDATE
					NOMB_TINM=:des_tip
				,	HAB_TINM=0';
				$req = $dbEmpresa->prepare($s);	
				foreach ($DataTotal as $kData => $vData) {		
					$req->bindParam(':tip_inm', $vData['tip_inm']); 
					$req->bindParam(':des_tip', $vData['des_tip']); 
					$req->execute();
					$idIndex[]=$vData['tip_inm'];
					$Affected[$TradTab[$key]]+=$req->rowCount();
				}
				if(count($idIndex)){
					$s='UPDATE y_tinm SET HAB_TINM=1 WHERE ID_TINM NOT IN ("'.implode('","',$idIndex).'")';
					$req = $dbEmpresa->prepare($s);
					$req->execute();
					$Affected[$TradTab[$key]]+=$req->rowCount();
				}
			}
			//////////////////////////
			//////////////////////////
			//////////////////////////
			//////////////////////////
			elseif(($key=='clientes'||$key=='clientes_ventas')&&count($DataTotal)>0){
				$s='INSERT INTO u_cliente
					(ID_CLIENTE
				,	NOMB_CLIENTE)
				VALUES
					(:cedula
				,	:nombre)
				ON DUPLICATE KEY UPDATE
					NOMB_CLIENTE=:nombre
				,	HAB_CLIENTE=0';
				$req = $dbEmpresa->prepare($s);	
				foreach ($DataTotal as $kData => $vData) {		
					$req->bindParam(':cedula', $vData['cedula']); 
					$req->bindParam(':nombre', $vData['nombre']); 
					$req->execute();
					$idIndexClientes[]=$vData['cedula'];
				}		
			}
			//////////////////////////
			//////////////////////////
			//////////////////////////
			//////////////////////////
			elseif(($key=='inmueble'||$key=='inmueble_ventas')&&count($DataTotal)>0){
				$idIndex=array();			
				$s="INSERT INTO x_inmueb
						(ID_INMUEB
					,	ID_BARR
					,	DEST_INMUEB
					,	FECHAS_INMUEB
					,	FECHAP_INMUEB
					,	NOMB_INMUEB
					,	ID_CLIENTE
					,	ID_TINM
					,	ID_TNEG
					,	ID_POT
					,	DIR_INMUEB
					,	UBIC_INMUEB
					,	VAL1_INMUEB
					,	VAL2_INMUEB
					,	VAL3_INMUEB
					,	VAL4_INMUEB
					,	M2_INMUEB
					,	EST_INMUEB
					,	CBAN_INMUEB
					,	CHAB_INMUEB
					,	NUS_INMUEB
					,	VID_INMUEB)
					VALUES
						(:cod_inm
					,	:cod_bar
					,	:destacado
					,	UTC_TIMESTAMP()
					,	UTC_TIMESTAMP()
					,	:cod_inm
					,	:cedula
					,	:tip_inm
					,	:tipo_cons
					,	:cod_pot
					,	:dir_inm
					,	GeomFromText(:geo)
					,	:canon
					,	:precio_v
					,	:admon
					,	:cuota_aseo
					,	:area_cons
					,	:estrato
					,	:banos
					,	:alcobas
					,	:estrenar
					,	:video)
				ON DUPLICATE KEY UPDATE
						ID_BARR=:cod_bar
					,	DEST_INMUEB=:destacado
					,	FECHAP_INMUEB=UTC_TIMESTAMP()
					,	NOMB_INMUEB=:cod_inm
					,	ID_CLIENTE=:cedula
					,	ID_TINM=:tip_inm
					,	ID_TNEG=:tipo_cons
					,	ID_POT=:cod_pot
					,	DIR_INMUEB=:dir_inm
					,	UBIC_INMUEB=GeomFromText(:geo)
					,	VAL1_INMUEB=:canon
					,	VAL2_INMUEB=:precio_v
					,	VAL3_INMUEB=:admon
					,	VAL4_INMUEB=:cuota_aseo
					,	M2_INMUEB=:area_cons
					,	EST_INMUEB=:estrato
					,	CBAN_INMUEB=:banos
					,	CHAB_INMUEB=:alcobas
					,	NUS_INMUEB=:estrenar
					,	VID_INMUEB=:video
					,	HAB_INMUEB=0";
				$req = $dbEmpresa->prepare($s);	
				foreach ($DataTotal as $kData => $vData) {	
					$lat = str_replace(",", ".", $vData["lat"]);
					$lon = str_replace(",", ".", $vData["lon"]);

					$geo='POINT('.(float)$lat.' '.(float)$lon.')';
					

					$estrenar=substr($vData["estrenar"],0,1)=='N'?0:1;
					$destacado=substr($vData["destacado"],0,1)=='N'?0:1;
					$tip_inm=$vData['tip_inm'];
					$tipo_cons=$vData['tipo_cons']==1?1:2;


					$req->bindParam(':cod_inm', $vData['cod_inm']); 
					$req->bindParam(':cod_bar', $vData['cod_bar']); 
					$req->bindParam(':destacado', $destacado); 
					$req->bindParam(':cedula', $vData['cedula']); 
					$req->bindParam(':tip_inm', $tip_inm); 
					$req->bindParam(':tipo_cons', $tipo_cons); 
					$req->bindParam(':cod_pot', $vData['cod_pot']); 
					$req->bindParam(':dir_inm', $vData['dir_inm']); 
					$req->bindParam(':geo', $geo); 
					$req->bindParam(':canon', $vData['canon']); 
					$req->bindParam(':precio_v', $vData['precio_v']); 
					$req->bindParam(':admon', $vData['admon']); 
					$req->bindParam(':cuota_aseo', $vData['cuota_aseo']); 
					$req->bindParam(':area_cons', $vData['area_cons']); 
					$req->bindParam(':estrato', $vData['estrato']); 
					$req->bindParam(':banos', $vData['banos']); 
					$req->bindParam(':alcobas', $vData['alcobas']); 
					$req->bindParam(':estrenar', $estrenar); 
					$req->bindParam(':video', $vData['video']);  
					$req->execute();
					$idIndexInmuebles[]=$vData['cod_inm'];
					$idIndex[]=$vData['cod_inm'];
				}
					
				if(count($idIndex)){
					$s='DELETE FROM x_inmueb_caract WHERE ID_INMUEB IN ("'.implode('","',$idIndex).'")';
					$req = $dbEmpresa->prepare($s);
					$req->execute();
				}
			}
			//////////////////////////
			//////////////////////////
			//////////////////////////
			//////////////////////////
			elseif(($key=='det_inmueble'||$key=='det_inmueble_ventas')&&count($DataTotal)>0){
				$ResDet=array();
				foreach ($DataTotal as $vData) {
					if (!in_array($vData['nombre'], $ResDet))
						$ResDet[]=$vData['nombre'];
				}
				$ResDetIndex=array();
				$s='SELECT ID_CARACT
					,	TRIM(DIMP_CARACT) AS DIMP_CARACT 
					FROM y_caract 
					WHERE TRIM(DIMP_CARACT) IN ("'.implode('","',$ResDet).'")';

				$req = $dbEmpresa->prepare($s);
				$req->execute();
				while($reg = $req->fetch()){
					$ResDetIndex[$reg['ID_CARACT']]=$reg['DIMP_CARACT'];
				}
				foreach ($ResDet as $vData) {
					if (!in_array($vData, $ResDetIndex)){
						$noshow=$vData==''?1:0;
						$s='INSERT INTO y_caract
								(ORD_CARACT
							,	NOMB_CARACT
							,	DIMP_CARACT
							,	SHW_CARACT
							,	NSHOW_CARACT)
							VALUES
								(100
							,	:vData
							,	:vData
							,	3
							,	:noshow)';
						$req = $dbEmpresa->prepare($s);	
						$req->bindParam(':vData', $vData); 
						$req->bindParam(':noshow', $noshow); 
						$req->execute();
						$idChar=$dbEmpresa->lastInsertId();
						$ResDetIndex[$idChar]=$vData;
						$Affected[$TradTab[$key]]+=$req->rowCount();
					}
				}
				$idIndex=array();
				$s='INSERT INTO x_inmueb_caract
						(ID_INMUEB
					,	ID_CARACT
					,	VALOR_CARACT)
					VALUES
						(:cod_inm
					,	:idChar
					,	:detalle)';
				$req = $dbEmpresa->prepare($s);	
				foreach ($DataTotal as $kData => $vData) {	
					$idChar=array_search($vData['nombre'], $ResDetIndex);	
					$req->bindParam(':cod_inm', $vData['cod_inm']); 
					$req->bindParam(':idChar', $idChar); 
					$req->bindParam(':detalle', $vData['detalle']); 
					$req->execute();
				}		
			}
			//////////////////////////
			//////////////////////////
			//////////////////////////
			//////////////////////////
			elseif($key=='nov_pro'&&count($DataTotal)>0){
				
				$s='DELETE FROM u_user_cuenta';
				$req = $dbEmpresa->prepare($s);	
				$req->execute();

				$s='INSERT INTO u_user_cuenta
						(ID_INMUEB
					,	ID_CONCEPT
					,	ID_CLIENTE
					,	DET_CUENTA
					,	DEBE_CUENTA
					,	HABER_CUENTA
					,	FECHA_CUENTA)
					VALUES
						(:cod_inm
					,	:cod_con
					,	:cedula
					,	:detalle
					,	:debe
					,	:haber
					,	STR_TO_DATE(:date,"%Y-%m-%d"))';
				$req = $dbEmpresa->prepare($s);	
				foreach ($DataTotal as $kData => $vData) {	
					
					$debe = str_replace(",", ".", $vData["debe"]);
					$haber = str_replace(",", ".", $vData["haber"]);

	
					$date=$vData['ano'].'-'.$vData['mes'].'-1';
					$req->bindParam(':cod_inm', $vData['cod_inm']); 
					$req->bindParam(':cod_con', $vData['cod_con']); 
					$req->bindParam(':cedula', $vData['cedula']); 
					$req->bindParam(':detalle', $vData['detalle']); 
					$req->bindParam(':debe', $debe); 
					$req->bindParam(':haber', $haber); 
					$req->bindParam(':date', $date); 
					$req->execute();
				}
			}
			//////////////////////////
			//////////////////////////
			//////////////////////////
			//////////////////////////
			elseif($key=='deudas'&&count($DataTotal)>0){
				$idIndex=array();			
				$s='DELETE FROM u_user_cuenta';
				$req = $dbEmpresa->prepare($s);	
				$req->execute();
				$uniq=uniqid();
				$s='INSERT INTO x_facturas
						(ID_FACTURA
					,	ID_INMUEB
					,	ID_CLIENTE
					,	NOMB_CLIENTE
					,	HASH_FACTURA
					,	FECHAS_FACTURA
					,	DET_FACTURA
					,	FV1_FACTURA
					,	VAL1_FACTURA
					,	IVA1_FACTURA
					,	FV2_FACTURA
					,	VAL2_FACTURA
					,	IVA2_FACTURA
					,	FV3_FACTURA
					,	VAL3_FACTURA
					,	IVA3_FACTURA
					,	ACT_FACTURA)
					VALUES
						(:factura
					,	:cod_inm
					,	:cedula
					,	:nombre
					,	SHA1(CONCAT(:cedula,:cod_inm,:factura,:uniq,UTC_TIMESTAMP()))
					,	UTC_TIMESTAMP()
					,	:detalle
					,	STR_TO_DATE(:fecha1,"%Y-%m-%d")
					,	:valor1
					,	:iva1
					,	STR_TO_DATE(:fecha2,"%Y-%m-%d")
					,	:valor2
					,	:iva2
					,	STR_TO_DATE(:fecha3,"%Y-%m-%d")
					,	:valor3
					,	:iva3
					,	:activo)
					ON DUPLICATE KEY UPDATE
						ID_INMUEB=:cod_inm
					,	ID_CLIENTE=:cedula
					,	NOMB_CLIENTE=:nombre
					,	DET_FACTURA=:detalle
					,	FV1_FACTURA=STR_TO_DATE(:fecha1,"%Y-%m-%d")
					,	VAL1_FACTURA=:valor1
					,	IVA1_FACTURA=:iva1
					,	FV2_FACTURA=STR_TO_DATE(:fecha2,"%Y-%m-%d")
					,	VAL2_FACTURA=:valor2
					,	IVA2_FACTURA=:iva2
					,	FV3_FACTURA=STR_TO_DATE(:fecha3,"%Y-%m-%d")
					,	VAL3_FACTURA=:valor3
					,	IVA3_FACTURA=:iva3
					,	ACT_FACTURA=:activo
					,	HAB_FACTURA=0';
				$req = $dbEmpresa->prepare($s);	
				foreach ($DataTotal as $kData => $vData) {	
					$valor1 = str_replace(",", ".", $vData["valor1"]);
					$iva1 = str_replace(",", ".", $vData["iva1"]);
					$valor2 = str_replace(",", ".", $vData["valor2"]);
					$iva2 = str_replace(",", ".", $vData["iva2"]);
					$valor3 = str_replace(",", ".", $vData["valor3"]);
					$iva3 = str_replace(",", ".", $vData["iva3"]);

					$req->bindParam(':factura', $vData['factura']); 
					$req->bindParam(':cod_inm', $vData['cod_inm']); 
					$req->bindParam(':cedula', $vData['cedula']); 
					$req->bindParam(':nombre', $vData['nombre']); 
					$req->bindParam(':detalle', $vData['detalle']); 
					$req->bindParam(':fecha1', $vData['fecha1']); 
					$req->bindParam(':valor1', $valor1); 
					$req->bindParam(':iva1', $iva1); 
					$req->bindParam(':fecha2', $vData['fecha2']); 
					$req->bindParam(':valor2', $valor2); 
					$req->bindParam(':iva2', $iva2); 
					$req->bindParam(':fecha3', $vData['fecha3']); 
					$req->bindParam(':valor3', $valor3); 
					$req->bindParam(':iva3', $iva3); 
					$req->bindParam(':activo', $vData['activo']); 
					$req->bindParam(':uniq', $uniq); 
					$req->execute();
					$idIndex[]=$vData['factura'];
				}
				if(count($idIndex)){
					$s='UPDATE x_facturas SET HAB_FACTURA=1 WHERE ID_FACTURA NOT IN ("'.implode('","',$idIndex).'")';
					$req = $dbEmpresa->prepare($s);
					$req->execute();			
				}
			}

			
			/*echo '<h1>'.$key.'</h1>';
			echo '<pre>';
			print_r($DataTotal);
			echo '</pre>';*/
		}
	}

	//AFINA CLIENTES E INMUEBLES
	if(count($idIndexInmuebles)){
		$s='UPDATE x_inmueb SET HAB_INMUEB=1 WHERE ID_INMUEB NOT IN ("'.implode('","',$idIndexInmuebles).'")';
		$req = $dbEmpresa->prepare($s);
		$req->execute();

		// CANNON
		$s='REPLACE INTO  x_inmueb_caract (ID_INMUEB,ID_CARACT,VALOR_CARACT)
		(SELECT x_inmueb.ID_INMUEB,12,x_inmueb.VAL1_INMUEB FROM x_inmueb WHERE ID_TNEG=1 AND x_inmueb.ID_INMUEB IN ("'.implode('","',$idIndexInmuebles).'"))';
		$req = $dbEmpresa->prepare($s);	
		$req->execute();
		
		//Admin
		$s='REPLACE INTO  x_inmueb_caract (ID_INMUEB,ID_CARACT,VALOR_CARACT)
		(SELECT x_inmueb.ID_INMUEB,13,x_inmueb.VAL3_INMUEB FROM x_inmueb WHERE ID_TNEG=1 AND x_inmueb.ID_INMUEB IN ("'.implode('","',$idIndexInmuebles).'"))';
		$req = $dbEmpresa->prepare($s);	
		$req->execute();
		//Aseo
		$s='REPLACE INTO  x_inmueb_caract (ID_INMUEB,ID_CARACT,VALOR_CARACT)
		(SELECT x_inmueb.ID_INMUEB,159,x_inmueb.VAL4_INMUEB FROM x_inmueb WHERE ID_TNEG=1 AND x_inmueb.ID_INMUEB IN ("'.implode('","',$idIndexInmuebles).'"))';
		$req = $dbEmpresa->prepare($s);	
		$req->execute();

		// PRECIO VENTA
		$s='REPLACE INTO  x_inmueb_caract (ID_INMUEB,ID_CARACT,VALOR_CARACT)
		(SELECT x_inmueb.ID_INMUEB,158,x_inmueb.VAL2_INMUEB FROM x_inmueb WHERE ID_TNEG=2 AND x_inmueb.ID_INMUEB IN ("'.implode('","',$idIndexInmuebles).'"))';
		$req = $dbEmpresa->prepare($s);	
		$req->execute();


		//Dirección
		$s='REPLACE INTO  x_inmueb_caract (ID_INMUEB,ID_CARACT,VALOR_CARACT)
		(SELECT x_inmueb.ID_INMUEB,3,x_inmueb.DIR_INMUEB FROM x_inmueb WHERE x_inmueb.ID_INMUEB IN ("'.implode('","',$idIndexInmuebles).'"))';
		$req = $dbEmpresa->prepare($s);	
		$req->execute();
		//Barrio
		$s='REPLACE INTO  x_inmueb_caract (ID_INMUEB,ID_CARACT,VALOR_CARACT)
		(SELECT x_inmueb.ID_INMUEB,5,x_barrios.NOMB_BARR FROM x_inmueb NATURAL JOIN x_barrios WHERE x_inmueb.ID_INMUEB IN ("'.implode('","',$idIndexInmuebles).'"))';
		$req = $dbEmpresa->prepare($s);	
		$req->execute();
		//Municipio
		$s='REPLACE INTO  x_inmueb_caract (ID_INMUEB,ID_CARACT,VALOR_CARACT)
		(SELECT x_inmueb.ID_INMUEB,4,x_municipio.NOMB_MUNIC FROM x_inmueb NATURAL JOIN x_barrios LEFT JOIN x_municipio ON x_municipio.ID_MUNIC=x_barrios.ID_MUNIC WHERE x_inmueb.ID_INMUEB IN ("'.implode('","',$idIndexInmuebles).'"))';
		$req = $dbEmpresa->prepare($s);	
		$req->execute();

		// TIPO DE INMUEBLE
		$s='REPLACE INTO  x_inmueb_caract (ID_INMUEB,ID_CARACT,VALOR_CARACT)
		(SELECT x_inmueb.ID_INMUEB,2,y_tinm.NOMB_TINM FROM x_inmueb NATURAL JOIN y_tinm WHERE x_inmueb.ID_INMUEB IN ("'.implode('","',$idIndexInmuebles).'"))';
		$req = $dbEmpresa->prepare($s);	
		$req->execute();

		// TIPO DE NEGOCIO
		$s='REPLACE INTO  x_inmueb_caract (ID_INMUEB,ID_CARACT,VALOR_CARACT)
		(SELECT x_inmueb.ID_INMUEB,1,y_tneg.NOMB_TNEG FROM x_inmueb NATURAL JOIN y_tneg WHERE x_inmueb.ID_INMUEB IN ("'.implode('","',$idIndexInmuebles).'"))';
		$req = $dbEmpresa->prepare($s);	
		$req->execute();


		//AREA ARRIENDO
		$s='REPLACE INTO  x_inmueb_caract (ID_INMUEB,ID_CARACT,VALOR_CARACT)
		(SELECT x_inmueb.ID_INMUEB,8,x_inmueb.M2_INMUEB FROM x_inmueb WHERE x_inmueb.ID_INMUEB IN ("'.implode('","',$idIndexInmuebles).'"))';
		$req = $dbEmpresa->prepare($s);	
		$req->execute();

		//BAÑOS
		$s='REPLACE INTO  x_inmueb_caract (ID_INMUEB,ID_CARACT,VALOR_CARACT)
		(SELECT x_inmueb.ID_INMUEB,6,x_inmueb.CHAB_INMUEB FROM x_inmueb WHERE x_inmueb.ID_INMUEB IN ("'.implode('","',$idIndexInmuebles).'"))';
		$req = $dbEmpresa->prepare($s);	
		$req->execute();
		//HABITACIONES
		$s='REPLACE INTO  x_inmueb_caract (ID_INMUEB,ID_CARACT,VALOR_CARACT)
		(SELECT x_inmueb.ID_INMUEB,7,x_inmueb.CBAN_INMUEB FROM x_inmueb WHERE x_inmueb.ID_INMUEB IN ("'.implode('","',$idIndexInmuebles).'"))';
		$req = $dbEmpresa->prepare($s);	
		$req->execute();

		//PARA ESTRENAR
		$s='REPLACE INTO  x_inmueb_caract (ID_INMUEB,ID_CARACT,VALOR_CARACT)
		(SELECT x_inmueb.ID_INMUEB,170,"SI" FROM x_inmueb WHERE x_inmueb.NUS_INMUEB=1 AND x_inmueb.ID_INMUEB IN ("'.implode('","',$idIndexInmuebles).'"))';
		$req = $dbEmpresa->prepare($s);	
		$req->execute();
	}

	echo "AQUI1\n";

	if($VALID_FOTOS){
		//IMAGENES//
		$idIndexTotalInm=array();
		$idIndexTotalDel=array();
		$s="SELECT x_inmueb.ID_INMUEB, x_inmueb.HAB_INMUEB FROM x_inmueb";
		$req = $dbEmpresa->prepare($s);
		$req->execute();
		while($reg = $req->fetch()){
			if($reg['HAB_INMUEB']==0)
				$idIndexTotalInm[]=$reg['ID_INMUEB'];
			elseif($reg['HAB_INMUEB']==1)
				$idIndexTotalDel[]=$reg['ID_INMUEB'];
		}
		$idIndexSubPicture=array();

		$ARFiles=array();
		ExploreFiles($Folder,$ARFiles);
		foreach (glob($Folder.'/*',GLOB_ONLYDIR) as $filename) {
			ExploreFiles($filename,$ARFiles);
		}
		echo "AQUI2\n";
		////////////////////////////////////////
		////////////////////////////////////////
		////////////////////////////////////////
		$AwsS3 = Aws::factory(array('key' => $_PARAMETROS['S3_KEY'],'secret' => $_PARAMETROS['S3_SECRET']));	
		////////////////////////////////////////
		////SE BORRAN LOS QUE SE DEBAN BORRAR///
		////////////////////////////////////////
		$UploadDeleteArgs=array(
				'S3_BUCKET'=>$_PARAMETROS["S3_BUCKET"]
			,	'PROYECTO'=>$_PROYECTO
			,	'EMPRESA'=>$_EMPRESA
			,	'MODULE'=>404
			,	'TP_FILE'=>'I');


		$IndexExist=array_keys($ARFiles);
		$idIndexTotalDel=array_merge($idIndexTotalDel,$IndexExist);
		if(count($idIndexTotalDel)>0){
			$s='SELECT x_inmueb_foto.ID_FOTO
				FROM x_inmueb_foto
				WHERE x_inmueb_foto.ID_INMUEB IN ("'.implode('","',$idIndexTotalDel).'")';
			$req = $dbEmpresa->prepare($s);
			$req->execute();
			while($reg = $req->fetch()){
				$UploadDeleteArgs['OBJECT']=$reg['ID_FOTO'];	
				echo 'Deleting: '.$reg['ID_FOTO']." ... ";
				DeleteFiles($AwsS3,$dbEmpresa,$UploadDeleteArgs);
				echo " complete \n";
			};
			$s='DELETE FROM x_inmueb_foto
				WHERE x_inmueb_foto.ID_INMUEB IN ("'.implode('","',$idIndexTotalDel).'")';
			$req = $dbEmpresa->prepare($s);
			$req->execute();
		}
		////////////////////////////////////////
		////////////////////////////////////////
		////////////////////////////////////////
		echo "AQUI3\n";
		////////////////////////////////////////
		////SE CARGAN LOS QUE SE DEBAN AGREGAR//
		////////////////////////////////////////
		$UploadArgs=array('S3_BUCKET'=>$_PARAMETROS["S3_BUCKET"]
					,	'PROYECTO'=>$_PROYECTO
					,	'EMPRESA'=>$_EMPRESA
					,	'MODULE'=>404
					,	'TP_FILE'=>'I'
					,	'control_type'=>1);

		foreach ($idIndexTotalInm as $Inm) {	
			if(count($ARFiles[$Inm])>0){
				foreach ($ARFiles[$Inm] as $Photo) {
					$s='INSERT INTO x_inmueb_foto
							(ID_INMUEB
						,	ORD_FOTO
						,	TIPO_FOTO)
						VALUES
							(:Inm
						,	:Ord
						,	:Ord)';
					$req = $dbEmpresa->prepare($s); 
					$req->bindParam(':Inm', $Inm);
					$req->bindParam(':Ord', $Photo['index']);
					$req->execute();	
					$id_photo=$dbEmpresa->lastInsertId();
					$UploadArgs['OBJECT']=$id_photo;	

					echo 'Uploading... '.$Photo['file']." ... ";			
					UploadFiles($AwsS3,$Photo['file'],$dbEmpresa,$UploadArgs,$Info);
					echo " complete \n";
					
					$s="UPDATE x_inmueb_foto 
						SET ID_FILE=IFNULL((SELECT adm_files.ID_FILE FROM adm_files WHERE adm_files.F_MODULE=404 AND adm_files.ID_OBJECT=$id_photo AND adm_files.F_SUB_EXT='org' AND adm_files.F_TP_FILE='I' LIMIT 1),0)
						WHERE ID_FOTO=$id_photo";
					$dbEmpresa->exec($s);

					if($Photo['index']==0){
						$s="UPDATE x_inmueb 
						SET ID_FILE=IFNULL((SELECT adm_files.ID_FILE FROM adm_files WHERE adm_files.F_MODULE=404 AND adm_files.ID_OBJECT=$id_photo AND adm_files.F_SUB_EXT='org' AND adm_files.F_TP_FILE='I' LIMIT 1),0)
						WHERE ID_INMUEB=$Inm";
						$dbEmpresa->exec($s);
					}
				}
			}
		}
	}

	echo "AQUI4\n";
	////////////////////////////////////////
	////////////////////////////////////////
	////////////////////////////////////////
	if(count($idIndexClientes)){
		$s='UPDATE u_cliente SET HAB_CLIENTE=1 WHERE ID_CLIENTE NOT IN ("'.implode('","',$idIndexClientes).'")';
		$req = $dbEmpresa->prepare($s);
		$req->execute();
	}

	///////// ACTUALIZACION DE TABLAS ///////////
	foreach ($Affected as $key => $value) {
		if($value!=0) ActVersions($dbEmpresa,$sqlCons,$_CLIENTE,$key);
	}

	$s='INSERT INTO log_sync (FECHA_SYNC) VALUES (UTC_TIMESTAMP())';
	$req = $dbEmpresa->prepare($s);
	$req->execute();
}
elseif($tp==3){

	$idIndexTotalInm=array();
	$idIndexTotalDel=array();
	$s="SELECT x_inmueb.ID_INMUEB, x_inmueb.HAB_INMUEB FROM x_inmueb";
	$req = $dbEmpresa->prepare($s);
	$req->execute();
	while($reg = $req->fetch()){
		if($reg['HAB_INMUEB']==0)
			$idIndexTotalInm[]=$reg['ID_INMUEB'];
		elseif($reg['HAB_INMUEB']==1)
			$idIndexTotalDel[]=$reg['ID_INMUEB'];
	}
	$idIndexSubPicture=array();

	$ARFiles=array();
	ExploreFiles($Folder,$ARFiles);
	foreach (glob($Folder.'/*',GLOB_ONLYDIR) as $filename) {
		ExploreFiles($filename,$ARFiles);
	}
	$IndexExist=array_keys($ARFiles);
	$idIndexTotalDel=array_merge($idIndexTotalDel,$IndexExist);
	
	if(count($idIndexTotalDel)>0){
		$s='SELECT x_inmueb_foto.ID_FOTO
			FROM x_inmueb_foto
			WHERE x_inmueb_foto.ID_INMUEB IN ("'.implode('","',$idIndexTotalDel).'")';
		
		$req = $dbEmpresa->prepare($s);
		$req->execute();
		while($reg = $req->fetch()){			
			echo 'Deleting: '.$reg['ID_FOTO']."\n";	
		};		
	}	
	foreach ($idIndexTotalInm as $Inm) {	
		if(count($ARFiles[$Inm])>0){
			foreach ($ARFiles[$Inm] as $Photo) {
				echo $Photo['file'].' - '.sha1_file($Photo['file'])."\n";
			}
		}
	}

	/**/
}
elseif($tp==4){  // Site Map
	$_URLS=array();

	$_SURLS=array('landing'		=>''
		,	'search'		=>'search'
		,	'code'			=>'code'
		,	'servicios'		=>'servicios'
		,	'compania'		=>'compania'
		,	'resena'		=>'resena'
		,	'faq'			=>'faq'
		,	'politicas'		=>'politicas'
		,	'consignar'		=>'consignar'
		,	'tomar'			=>'tomar'	
		,	'manuales'		=>'manuales'
		,	'pdatos'		=>'proteccion-de-datos'
		,	'noticias'		=>'noticias'
		,	'contacto'		=>'contacto'
		,	'perfil'		=>'perfil'
		,	'mis-inmuebles'	=>'mis-inmuebles'	
		,	'favoritos'		=>'favoritos'
		,	'factura'		=>'pagos.php'		
		,	'subir'			=>'subir'			
		,	'cuenta'		=>'cuenta'		
		,	'publicar'		=>'publicar'
		,	'ingreso'		=>'ingreso'	
		,	'fingreso'		=>'fingreso'	
		,	'registro'		=>'registro'	
		,	'rec'			=>'rec'	
		,	'cita'			=>'cita'	
		,	'vanguardia'	=>'vanguardia'	
		,	'recomendar'	=>'recomendar'
	);

$_TURL=array($_SURLS['landing']			=>	array('val'=>2,'id'=>1,'tipo'=>1)
		,	$_SURLS['search']			=>	array('val'=>2,'id'=>2,'tipo'=>3,'title'=>'Arriendos, Ventas en Bucaramanga')
		,	$_SURLS['code']				=>	array('val'=>2,'id'=>3,'tipo'=>2)
		,	$_SURLS['servicios']		=>	array('val'=>2,'id'=>4,'tipo'=>5,'title'=>'Sevicios')
		,	$_SURLS['compania']			=>	array('val'=>2,'id'=>5,'tipo'=>5,'title'=>'La Compañia')
		,	$_SURLS['resena']			=>	array('val'=>2,'id'=>6,'tipo'=>5,'title'=>'Reseña Histórica')
		,	$_SURLS['faq']				=>	array('val'=>2,'id'=>30,'tipo'=>5,'title'=>'Preguntas frecuentes')
		,	$_SURLS['politicas']		=>	array('val'=>2,'id'=>31,'tipo'=>5,'title'=>'Políticas')
		,	$_SURLS['consignar']		=>	array('val'=>2,'id'=>7,'tipo'=>5,'title'=>'Consignar Inmueble')
		,	$_SURLS['tomar']			=>	array('val'=>2,'id'=>8,'tipo'=>5,'title'=>'Tomar un Inmueble')
		,	$_SURLS['manuales']			=>	array('val'=>2,'id'=>9,'tipo'=>5,'title'=>'Manuales')
		,	$_SURLS['pdatos']			=>	array('val'=>2,'id'=>10,'tipo'=>5,'title'=>'Politicas de Protección de Datos')
		,	$_SURLS['noticias']			=>	array('val'=>2,'id'=>11,'tipo'=>5,'title'=>'Noticias')
		,	$_SURLS['contacto']			=>	array('val'=>2,'id'=>12,'tipo'=>5,'title'=>'Contáctenos')
		,	$_SURLS['perfil']			=>	array('val'=>1,'id'=>13,'tipo'=>5,'title'=>'Perfil')
		,	$_SURLS['mis-inmuebles']	=>	array('val'=>1,'id'=>14,'tipo'=>5,'title'=>'Mis Inmuebles')		
		,	$_SURLS['favoritos']		=>	array('val'=>1,'id'=>14,'tipo'=>5,'title'=>'Favoritos')
		,	$_SURLS['factura']			=>	array('val'=>1,'id'=>16,'tipo'=>5,'title'=>'Pagar Factura')	
		,	$_SURLS['cuenta']			=>	array('val'=>1,'id'=>18,'tipo'=>5,'title'=>'Estado de Cuenta')
		,	$_SURLS['vanguardia']		=>	array('val'=>2,'id'=>21,'tipo'=>6,'title'=>'Widget Vanguardia')

		,	$_SURLS['subir'	]			=>	array('val'=>1,'id'=>17,'tipo'=>1,'title'=>'Subir un Inmueble')		
		
		,	$_SURLS['publicar']			=>	array('val'=>1,'id'=>20,'tipo'=>5,'title'=>'Publicar un Inmueble')
		,	$_SURLS['ingreso']			=>	array('val'=>0,'id'=>25,'tipo'=>10)
		,	$_SURLS['fingreso']			=>	array('val'=>0,'id'=>25,'tipo'=>10)
		,	$_SURLS['registro']			=>	array('val'=>0,'id'=>26,'tipo'=>10)
		,	$_SURLS['rec']				=>	array('val'=>0,'id'=>27,'tipo'=>10)
		,	$_SURLS['cita']				=>	array('val'=>1,'id'=>28,'tipo'=>10)
		,	$_SURLS['recomendar']		=>	array('val'=>0,'id'=>29,'tipo'=>10)
		
	);


	foreach ($_TURL as $key => $value) {
		if(($value['tipo']==3||$value['tipo']==5)&&$value['val']!=1)
			$_URLS[]='/'.$key;	
	}
	$s=$sqlCons[0][404].' WHERE x_inmueb.HAB_INMUEB=0';
	$reqINM=$dbEmpresa->prepare($s); 
	$reqINM->execute();
	while($regINM = $reqINM->fetch()){
		$link=$regINM['ID_INMUEB'];			
		$_CATS[]=$link;
		$_URLS[]='/code/'.$link;		
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
	$file = "/var/www/erios/public_html/sitemap.xml";
	$fh = fopen($file, 'w') or die("Can't open the sitemap file.");
	fwrite($fh, $sitemap->saveXML());
	fclose($fh);
}
function ExploreFiles($folder,&$ARRAY){
	foreach (glob("$folder/{*.[pP][nN][gG],*.[jJ][pP][gG],*.[gG][iI][fF]}",GLOB_BRACE) as $filename) {
		
		$FileNameC=basename($filename);
	    $NameArray=NameExt($FileNameC);
	    $InmubId=explode('F', $NameArray[0]);

	    $MainName=intval($InmubId[0]);
	    $AddImg=isset($InmubId[1]);
	    $InxName=isset($InmubId[1])?$InmubId[1]:0;

	    if(!isset($ARRAY[$MainName]))
	    	$ARRAY[$MainName]=array();

	    $ARRAY[$MainName][]=array('index'=>$InxName,'file'=>$filename);

	}
}

function NameExt($name){
	$NameArray=explode('.', $name);
	$extIndex=count($NameArray)-1;
	$ext=$NameArray[$extIndex];
	unset($NameArray[$extIndex]);
	$base=implode('.',$NameArray);
	return(array($base,$ext));
}
?>
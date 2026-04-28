<?php

$md=isset($_REQUEST["md"])?$_REQUEST["md"]:'';
$id_sha=mb_substr($md,0,40);
$c_sha=mb_substr($md,40,32);
$id_sha_t=mb_substr($md,72,40);

$det_plus=mb_substr($md,112,3);
$det_plus=$det_plus==''?0:$det_plus;

$tp=isset($_REQUEST["tp"])?$_REQUEST["tp"]:0;


$sWhere=encrip_mysql('adm_ventanas.ID_VENTANA',2);
$s=$sqlCons[1][71]." WHERE $sWhere=:c_sha LIMIT 1";
$req = $dbEmpresa->prepare($s); 
$req->bindParam(':c_sha', $c_sha);
$req->execute();	
if($reg = $req->fetch()){
	$cnf=$reg["ID_VENTANA"];
}


///////////////////////////////////////////////
$busc=isset($_REQUEST["busc"])?imprimir($_REQUEST["busc"]):"";
$busc_query='%'.$_REQUEST["busc"].'%';
$busc_send=isset($_REQUEST["busc"])?$_REQUEST["busc"]:"";
///////////////////////////////////////////////
$PagActual=isset($_REQUEST["p"])?$_REQUEST["p"]:1;
$MaxItems=$NMaxItems[1];
$nuevo=(nuevo_item()==$id_sha);


if($cnf==8){
	//RELACIONAR MODULOS
	if($det_plus==1){
		if(($PermisosA[10000]["P"]==1)) $sWhereP="  ";
		else 		
			$sWhereP=
				" WHERE adm_ventanas.ACCIONES!=4 
				AND adm_ventanas.ID_VENTANA IN (SELECT adm_ventanas_etipo.ID_VENTANA FROM adm_ventanas_etipo WHERE adm_ventanas_etipo.TIPO_GRUPOPAL=$_GCLIENTE)";

		$sWhere=encrip_mysql('adm_grupos_ven.ID_GRUPO');
		$s=$sqlCons[1][66]." AND $sWhere=:id ".$sWhereP.$sqlOrder[1][66];		
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		$req->execute();
		
		
		$salida=array();
		$salidas["id"]="tblVentanasRel";
		$salidas["display"]="block";
		$salidas["titulo"]='';
		$salidas["titulos"]=array();
		$salidas["nItem"]=array();
		
		$i=0;
		$k=0;
		$salidas["titulos"][$k]["cont"][$i]["label"]='txt-1117-0';
		$salidas["titulos"][$k]["cont"][$i]["width"]=10;
		
		$i++;
		$salidas["titulos"][$k]["cont"][$i]["label"]='txt-1112-0';
		$salidas["titulos"][$k]["cont"][$i]["width"]=35;
		
		$i++;
		$salidas["titulos"][$k]["cont"][$i]["label"]='txt-1083-0';
		$salidas["titulos"][$k]["cont"][$i]["width"]=55;
		
		$k=0;
		while($reg = $req->fetch()){
			$salidas["nItem"][$k]=array();			
			$i=0;			
			$salidas["nItem"][$k]["cont"][$i]["label"]='txt-1117-0';
			$salidas["nItem"][$k]["cont"][$i]["tipo"]='checkbox';
			$salidas["nItem"][$k]["cont"][$i]["checked"]=$reg["PERMISO_GRUPOVEN"]==1;
			$salidas["nItem"][$k]["cont"][$i]["value"]=$reg["ID_VENTANA"];
			$salidas["nItem"][$k]["cont"][$i]["tipobtn"]=$btn_vicular_ven;
			$salidas["nItem"][$k]["cont"][$i]["name"]="Ventanas[".$reg["ID_VENTANA"]."]";
			$salidas["nItem"][$k]["cont"][$i]["id"]="Ventanas_".$reg["ID_VENTANA"];
			
			$i++;
			$salidas["nItem"][$k]["cont"][$i]["label"]=$reg["VENTANA_NOMBRE"];
			
			$i++;
			$salidas["nItem"][$k]["cont"][$i]["label"]=$reg["DESC_GVENTANA"];

			$i++;
			$salidas["nItem"][$k]["cont"][$i]["tipo"]='hidden';
			$salidas["nItem"][$k]["cont"][$i]["value"]=$reg["ID_VENTANA"];
			$salidas["nItem"][$k]["cont"][$i]["name"]="IdVentana[".$reg["ID_VENTANA"]."]";						
			$k++;
		}	
		if($k==0) $salidas["titulo"]='txt-1118-0';
		echo json_encode($salidas);
	}
	//RELACIONAR AREAS A GRUPOS
	elseif($det_plus==2){	
		
		
		$sWhere=encrip_mysql('s_cresp_grupo.ID_GRUPO');
		$s=$sqlCons[5][101]." AND $sWhere=:id ".$sqlOrder[1][101];
		
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		$req->execute();
		
		
		$salida=array();
		$salidas["id"]="tblCrespRel";
		$salidas["display"]="block";
		$salidas["titulo"]='';
		$salidas["titulos"]=array();
		$salidas["nItem"]=array();
		
		$i=0;
		$k=0;
		$salidas["titulos"][$k]["cont"][$i]["label"]='txt-1117-0';
		$salidas["titulos"][$k]["cont"][$i]["width"]=10;
		$i++;
		$salidas["titulos"][$k]["cont"][$i]["width"]=90;
		$salidas["titulos"][$k]["cont"][$i]["label"]='txt-1113-0';	
		
		
		$k=0;
		while($reg = $req->fetch()){
			$salidas["nItem"][$k]=array();		
			$i=0;			
			$salidas["nItem"][$k]["cont"][$i]["label"]='txt-1117-0';
			$salidas["nItem"][$k]["cont"][$i]["tipo"]='checkbox';
			$salidas["nItem"][$k]["cont"][$i]["checked"]=(!is_null($reg["ID_GRUPO"]));
			$salidas["nItem"][$k]["cont"][$i]["value"]=$reg["ID_RESP"];
			$salidas["nItem"][$k]["cont"][$i]["tipobtn"]=$btn_vicular_ven;
			$salidas["nItem"][$k]["cont"][$i]["name"]="Areas[".$reg["ID_RESP"]."]";
			$salidas["nItem"][$k]["cont"][$i]["id"]="Areas_".$reg["ID_RESP"];

			$i++;
			$salidas["nItem"][$k]["cont"][$i]["label"]=$reg["NOMB_RESP"];
			
			$i++;
			$salidas["nItem"][$k]["cont"][$i]["tipo"]='hidden';
			$salidas["nItem"][$k]["cont"][$i]["value"]=$reg["ID_RESP"];
			$salidas["nItem"][$k]["cont"][$i]["name"]="IdArea[".$reg["ID_RESP"]."]";	

			$k++;
		}	
		if($k==0) $salidas["titulo"]='txt-1120-0';	;
		echo json_encode($salidas);
	}
	//RELACIONAR GRUPOS A AREAS
	elseif($det_plus==3){	
		$sWhere=encrip_mysql('s_cresp_grupo.ID_RESP');

		$s=$sqlCons[1][67]." AND $sWhere=:id WHERE ID_MEMPRESA=$_CLIENTE ";
		if($PermisosA[10000]["P"]!=1) $s.=" AND adm_grupos.ADM_GRUPO=0 ";
		$s.=$sqlOrder[1][67];


		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		$req->execute();
				
		$salida=array();
		$salidas["id"]="tblCrespRel";
		$salidas["display"]="block";
		$salidas["titulo"]='';
		$salidas["titulos"]=array();
		$salidas["nItem"]=array();
		
		$i=0;
		$k=0;
		$salidas["titulos"][$k]["cont"][$i]["label"]='txt-1117-0';
		$salidas["titulos"][$k]["cont"][$i]["width"]=10;
		
		$i++;
		$salidas["titulos"][$k]["cont"][$i]["label"]='txt-1083-0';
		$salidas["titulos"][$k]["cont"][$i]["width"]=90;
		
		$k=0;
		while($reg = $req->fetch()){
			$salidas["nItem"][$k]=array();
			
			$i=0;			
			$salidas["nItem"][$k]["cont"][$i]["label"]='txt-1117-0';
			$salidas["nItem"][$k]["cont"][$i]["tipo"]='checkbox';
			$salidas["nItem"][$k]["cont"][$i]["checked"]=(!is_null($reg["ID_RESP"]));
			$salidas["nItem"][$k]["cont"][$i]["value"]=$reg["ID_GRUPO"];
			$salidas["nItem"][$k]["cont"][$i]["tipobtn"]=$btn_vicular_ven;
			$salidas["nItem"][$k]["cont"][$i]["name"]="Grupos[".$reg["ID_GRUPO"]."]";
			$salidas["nItem"][$k]["cont"][$i]["id"]="Grupos_".$reg["ID_GRUPO"];

			$i++;
			$salidas["nItem"][$k]["cont"][$i]["label"]=$reg["DESC_GRUPO"];		
			

			$i++;
			$salidas["nItem"][$k]["cont"][$i]["tipo"]='hidden';
			$salidas["nItem"][$k]["cont"][$i]["value"]=$reg["ID_GRUPO"];
			$salidas["nItem"][$k]["cont"][$i]["name"]="IdGrupo[".$reg["ID_GRUPO"]."]";	
						
			$k++;			
		}	
		if($k==0) $salidas["titulo"]='txt-1121-0';
		echo json_encode($salidas);
	}
}
elseif($cnf==36){
	$sWhere=encrip_mysql('adm_usuarios.ID_USUARIO');
	$s="SELECT adm_usuarios.ID_USUARIO FROM adm_usuarios WHERE $sWhere=:id LIMIT 1";	
	$req = $dbEmpresa->prepare($s); 
	$req->bindParam(':id', $id_sha);
	$req->execute();	
	if($reg = $req->fetch()) $user=$reg["ID_USUARIO"];
	//REDES SOCIALES
	if($tp==1){	
					
		$s=$sqlCons[1][107].' WHERE fac_rs_origen.PERFIL=1 '.$sqlOrder[1][107];		 					
		$req = $dbEmpresa->prepare($s); 		
		$req->bindParam(':user', $user);
		$req->execute(); 				

		$salida=array();
		$salidas["display"]="block";
		$salidas["titulo"]='';
		$salidas["titulos"]=array();
		$salidas["nItem"]=array();
		$salidas["data"]["edcontrol"]=true;
		$salidas["data"]["edid"]='EDRS';
		$salidas["attr"]["width"]='100%';
		$i=0;
		$k=0;
		$salidas["titulos"][$k]["cont"][$i]["label"]='txt-1104-0';
		$salidas["titulos"][$k]["cont"][$i]["width"]=30;			
				
		$i++;
		$salidas["titulos"][$k]["cont"][$i]["label"]='';
		$salidas["titulos"][$k]["cont"][$i]["width"]=70;	
				
		$k=0;
		while($reg = $req->fetch()){
			$salidas["nItem"][$k]=array();			
			$i=0;
			$salidas["nItem"][$k]["cont"][$i]["label"]=imprimir($reg["DESC_ORIGEN"]);

			$i++;
			$salidas["nItem"][$k]["cont"][$i]["title"]='';
			$salidas["nItem"][$k]["cont"][$i]["tipo"]='text';
			$salidas["nItem"][$k]["cont"][$i]["value"]=imprimir($reg["RS_VALOR"]);
			$salidas["nItem"][$k]["cont"][$i]["name"]="UsrRs[".$reg["ID_ORIGEN"]."]";

			$i++;
			$salidas["nItem"][$k]["cont"][$i]["tipo"]='hidden';
			$salidas["nItem"][$k]["cont"][$i]["value"]=$reg["ID_ORIGEN"];
			$salidas["nItem"][$k]["cont"][$i]["name"]="IdOrigen[".$reg["ID_ORIGEN"]."]";

			$i++;
			$salidas["nItem"][$k]["cont"][$i]["tipo"]='hidden';
			$salidas["nItem"][$k]["cont"][$i]["value"]=0;
			$salidas["nItem"][$k]["cont"][$i]["name"]="EDRS[".$reg["ID_ORIGEN"]."]";				
			$k++;			
		}	
		
		echo json_encode($salidas);
	}
	//GRUPOS
	elseif($tp==2){	

		$s=$sqlCons[1][108]." WHERE adm_grupos.HAB_GRUPO=0 ".$sqlOrder[1][108];
	    $reqOP = $dbEmpresa->prepare($s);
	    $reqOP->execute();
		$grupos=array();
		while($regOP = $reqOP->fetch()){
			$id_mempresa=$regOP['ID_MEMPRESA'];
			$grupos[$id_mempresa][]=array("value"	=>$regOP["ID_GRUPO"]
									,		"label"	=>imprimir($regOP["DESC_GRUPO"],2)
									,		"cont"	=>array());

		}
		$s=$sqlCons[0][108].' '.$sqlOrder[0][108];		 					
		$req = $dbEmpresa->prepare($s); 		
		$req->bindParam(':user', $user);
		$req->execute(); 				


		$salida=array();
		$salidas["display"]="block";
		$salidas["titulo"]='';
		$salidas["titulos"]=array();
		$salidas["nItem"]=array();
		
		$i=0;
		$k=0;
		$salidas["titulos"][$k]["cont"][$i]["label"]='txt-1181-0'; // Empresa
		$salidas["titulos"][$k]["cont"][$i]["width"]=40;
		
		$i++;
		$salidas["titulos"][$k]["cont"][$i]["width"]=45;
		$salidas["titulos"][$k]["cont"][$i]["label"]='txt-1139-0'; // Grupo

		$i++;
		$salidas["titulos"][$k]["cont"][$i]["width"]=15;
		$salidas["titulos"][$k]["cont"][$i]["label"]='txt-1145-0'; // Actual
		
		
		$k=0;

		while($reg = $req->fetch()){
			$id_mempresa=$reg['ID_MEMPRESA'];
			$opciones=array();
			$opciones[]=array("value"	=>0
						,		"label"	=>'txt-1435-1'
						,		"cont"	=>array());
			$opciones=array_merge($opciones,$grupos[$id_mempresa]);

			$salidas["nItem"][$k]=array();		
			$i=0;
			$salidas["nItem"][$k]["cont"][$i]["label"]=$reg["NOMB_MEMPRESA"];
			
			$i++;
			$salidas["nItem"][$k]["cont"][$i]["tipo"]='combobox';
			$salidas["nItem"][$k]["cont"][$i]["value"]=$reg['ID_GRUPO'];
			$salidas["nItem"][$k]["cont"][$i]["options"]=$opciones;
			$salidas["nItem"][$k]["cont"][$i]["name"]="IdGroup[".$id_mempresa."]";

			$i++;
			$salidas["nItem"][$k]["cont"][$i]["label"]='txt-1145-0';
			$salidas["nItem"][$k]["cont"][$i]["tipo"]='checkbox';
			$salidas["nItem"][$k]["cont"][$i]["checked"]=$reg["LAST"]==1;
			$salidas["nItem"][$k]["cont"][$i]["value"]=1;
			$salidas["nItem"][$k]["cont"][$i]["name"]="GrLast[".$id_mempresa."]";
			$salidas["nItem"][$k]["cont"][$i]["id"]="GrLast_".$id_mempresa;


			$k++;
		}		
		echo json_encode($salidas);
	}
}
elseif($cnf==19){
	//CIUDADES Y GRUPOS
	if($tp==1){	
		$sWhere=encrip_mysql('s_cresp_ciudades.ID_RESP');
		$s=$sqlCons[1][45].
			" WHERE fac_ciudades.ID_CIUDAD IN (SELECT s_cresp_ciudades.ID_CIUDAD FROM s_cresp_ciudades WHERE $sWhere=:id) ".$sqlOrder[1][45];

		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		$req->execute();
		
		
		$salida=array();
		$salidas["id"]="tblCrespRel";
		$salidas["display"]="block";
		$salidas["titulo"]='';
		$salidas["data"]["edcontrol"]=true;
		$salidas["data"]["edid"]='EDCiudad';
		$salidas["attr"]["width"]='100%';
		$salidas["titulos"]=array();
		$salidas["nItem"]=array();
		
		$i=0;
		$k=0;
		$salidas["titulos"][$k]["cont"][$i]["label"]='txt-1058-0'; //CIUDAD
		$salidas["titulos"][$k]["cont"][$i]["width"]=70;
		
		$i++;
		$salidas["titulos"][$k]["cont"][$i]["label"]=''; //ELIMINAR
		$salidas["titulos"][$k]["cont"][$i]["width"]=30;
		
		$k=0;
		while($reg = $req->fetch()){
			$i=0;		
			$salidas["nItem"][$k]["cont"][$i]["label"]=sprintf("%s, %s",imprimir($reg["NOMB_CIUDAD"]),imprimir($reg["COD_PAIS"]));			
			
			$i++;
			$salidas["nItem"][$k]["cont"][$i]["label"]='txt-1005-0';
			$salidas["nItem"][$k]["cont"][$i]["tipo"]='button';
			$salidas["nItem"][$k]["cont"][$i]["value"]=$reg["ID_CIUDAD"];
			$salidas["nItem"][$k]["cont"][$i]["icon"]="fa-trash";
			$salidas["nItem"][$k]["cont"][$i]["name"]="DelCiudad[".$reg["ID_CIUDAD"]."]";
			$salidas["nItem"][$k]["cont"][$i]["data"]["altern"]='txt-1010-0'; 
			$salidas["nItem"][$k]["cont"][$i]["data"]["delform"]=true;	
			$salidas["nItem"][$k]["cont"][$i]["data"]["delverif"]='EDCiudad';
			
			$i++;
			$salidas["nItem"][$k]["cont"][$i]["tipo"]='hidden';
			$salidas["nItem"][$k]["cont"][$i]["value"]=$reg["ID_CIUDAD"];
			$salidas["nItem"][$k]["cont"][$i]["name"]="IdCiudad[".$reg["ID_CIUDAD"]."]";

			$i++;
			$salidas["nItem"][$k]["cont"][$i]["tipo"]='hidden';
			$salidas["nItem"][$k]["cont"][$i]["value"]=0;
			$salidas["nItem"][$k]["cont"][$i]["name"]="EDCiudad[".$reg["ID_CIUDAD"]."]";

			$k++;
		}	
		if($k==0) $salidas["titulo"]=='txt-1123-0';
		echo json_encode($salidas);
	}
}
/*************************/
/*************************/
/*************************/
/*******PALABRAS SIIE*****/
/*************************/
/*************************/
/*************************/
elseif($cnf==10001){
	$c_sha=encrip($cnf,2);
	$det_plus=sprintf("%03s",($tp+1));
	if($tp==1){
		include "phplib/mysql_valores.php";

		$salida=array();
		$salidas["id"]="list_".$cnf."_".$tp;
		$salidas["display"]="block";
		$salidas["titulo"]='';
		$salidas["attr"]["width"]='100%';
		$salidas["nItem"]=array();	

		$colC=0;	
		$i=0;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1053-1';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=5;

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1217-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=35;			

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1217-1';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=52;

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1085-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=8;

		$s=$sqlCons[1][68]." WHERE adm_textos.ID_IDIOMA=:idioma ".$sqlOrder[1][68];
		$req = $dbMat->prepare($s); 
		$req->bindParam(':idioma', $_REQUEST["idioma"]);
		$req->execute();		

		while($reg = $req->fetch()){
			$filC=$reg["ID_PALABRA"];
			$id_sha_t=encrip($filC);
			$md=$id_sha.$c_sha.$id_sha_t.$det_plus;


			$i=0;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["label"]=$filC;

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]="";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["PALABRA"])?"":imprimir($reg["PALABRA"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="palabra[".$reg["ID_PALABRA"]."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='palabra';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]="";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["TOOLTIP"])?"":imprimir($reg["TOOLTIP"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="tooltip[".$reg["ID_PALABRA"]."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='tooltip';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='button';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=$reg["ID_PALABRA"];
			$salidas["nItem"][$filC]["cont"][$colC.$i]["icon"]="fa-save";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="Save[".$reg["ID_PALABRA"]."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["md"]=$md;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["idioma"]=$_REQUEST["idioma"];
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["enable"]='false';
		}
		echo json_encode($salidas);
	}
	elseif($tp==2){
		//PALABRAS DE APLICACION
		$salida=array();
		$salidas["id"]="list_".$cnf."_".$tp;
		$salidas["display"]="block";
		$salidas["titulo"]='';
		$salidas["attr"]["width"]='100%';
		$salidas["nItem"]=array();	

		$colC=0;	
		$i=0;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1053-1';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=5;		

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1217-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=35;			

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1217-1';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=52;

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1085-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=8;

		$sWhere=encrip_mysql('adm_empresas_imp_textos.TIPO_GRUPOPAL');
		$s=$sqlCons[1][77]." WHERE adm_empresas_imp_textos.ID_IDIOMA=:idioma AND $sWhere=:id  ".$sqlOrder[1][77];
		
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':idioma', $_REQUEST["idioma"]);
		$req->bindParam(':id', $id_sha);
		$req->execute();		

		while($reg = $req->fetch()){
			$filC=$reg["ID_PALABRA"];
			$id_sha_t=encrip($filC);
			$md=$id_sha.$c_sha.$id_sha_t.$det_plus;

			$i=0;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["label"]=$filC;

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]='';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["PALABRA"])?"":imprimir($reg["PALABRA"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="palabra[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='palabra';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]="";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["TOOLTIP"])?"":imprimir($reg["TOOLTIP"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="tooltip[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='tooltip';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='button';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=$filC;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["icon"]="fa-save";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="Save[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["md"]=$md;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["idioma"]=$_REQUEST["idioma"];
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["enable"]='false';
		}
		echo json_encode($salidas);
	}
	elseif($tp==3){
		//PALABRAS DE APLICACION - OFICIO
		$salida=array();
		$salidas["id"]="list_".$cnf."_".$tp;
		$salidas["display"]="block";
		$salidas["titulo"]='';
		$salidas["attr"]["width"]='100%';
		$salidas["nItem"]=array();	

		$colC=0;

		$i=0;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1053-1';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=5;

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1130-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=35;			

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1091-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=52;

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1085-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=8;

		$sWhere=encrip_mysql('adm_empresas_imp_oficios.TIPO_GRUPOPAL');
		$s=$sqlCons[1][88]." WHERE adm_empresas_imp_oficios.ID_IDIOMA=:idioma AND $sWhere=:id  ".$sqlOrder[1][88];
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':idioma', $_REQUEST["idioma"]);
		$req->bindParam(':id', $id_sha);
		$req->execute();		

		while($reg = $req->fetch()){
			$filC=$reg["ID_OFICIO"];
			$id_sha_t=encrip($filC);
			$md=$id_sha.$c_sha.$id_sha_t.$det_plus;

			
			$i=0;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["label"]=$filC;

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]='';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["NOMB_OFICIO"])?"":imprimir($reg["NOMB_OFICIO"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="nomb[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='nomb';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]="";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["DESC_OFICIO"])?"":imprimir($reg["DESC_OFICIO"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="desc[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='desc';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='button';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=$filC;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["icon"]="fa-save";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="Save[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["md"]=$md;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["idioma"]=$_REQUEST["idioma"];
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["enable"]='false';
		}
		echo json_encode($salidas);
	}
	elseif($tp==4){
		//PALABRAS DE APLICACION - TFALLA
		$salida=array();
		$salidas["id"]="list_".$cnf."_".$tp;
		$salidas["display"]="block";
		$salidas["titulo"]='';
		$salidas["attr"]["width"]='100%';
		$salidas["nItem"]=array();	

		$colC=0;

		$i=0;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1053-1';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=5;

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1130-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=35;			

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1091-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=52;

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1085-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=8;

		$sWhere=encrip_mysql('adm_empresas_imp_tfalla.TIPO_GRUPOPAL');
		$s=$sqlCons[1][89]." WHERE adm_empresas_imp_tfalla.ID_IDIOMA=:idioma AND $sWhere=:id  ".$sqlOrder[1][89];
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':idioma', $_REQUEST["idioma"]);
		$req->bindParam(':id', $id_sha);
		$req->execute();		

		while($reg = $req->fetch()){
			$filC=$reg["ID_FALLA"];
			$id_sha_t=encrip($filC);
			$md=$id_sha.$c_sha.$id_sha_t.$det_plus;

			$i=0;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["label"]=$filC;

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]='';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["NOMB_FALLA"])?"":imprimir($reg["NOMB_FALLA"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="nomb[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='nomb';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]="";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["COMEN_FALLA"])?"":imprimir($reg["COMEN_FALLA"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="desc[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='desc';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='button';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=$filC;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["icon"]="fa-save";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="Save[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["md"]=$md;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["idioma"]=$_REQUEST["idioma"];
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["enable"]='false';
		}
		echo json_encode($salidas);
	}
	elseif($tp==5){
		//PALABRAS DE APLICACION - TRABAJO
		$salida=array();
		$salidas["id"]="list_".$cnf."_".$tp;
		$salidas["display"]="block";
		$salidas["titulo"]='';
		$salidas["attr"]["width"]='100%';
		$salidas["nItem"]=array();	

		$colC=0;

		$i=0;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1053-1';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=5;

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1130-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=35;			

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1091-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=52;

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1085-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=8;

		$sWhere=encrip_mysql('adm_empresas_imp_ttrabajo.TIPO_GRUPOPAL');
		$s=$sqlCons[1][90]." WHERE adm_empresas_imp_ttrabajo.ID_IDIOMA=:idioma AND $sWhere=:id  ".$sqlOrder[1][90];
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':idioma', $_REQUEST["idioma"]);
		$req->bindParam(':id', $id_sha);
		$req->execute();		

		while($reg = $req->fetch()){
			$filC=$reg["ID_TTRABAJO"];
			$id_sha_t=encrip($filC);
			$md=$id_sha.$c_sha.$id_sha_t.$det_plus;

			$i=0;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["label"]=$filC;

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]='';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["DESC_TTRABAJO"])?"":imprimir($reg["DESC_TTRABAJO"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="nomb[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='nomb';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]="";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["COMENT_TTRABAJO"])?"":imprimir($reg["COMENT_TTRABAJO"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="desc[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='desc';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='button';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=$filC;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["icon"]="fa-save";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="Save[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["md"]=$md;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["idioma"]=$_REQUEST["idioma"];
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["enable"]='false';
		}
		echo json_encode($salidas);
	}
	elseif($tp==6){
		//PALABRAS DE APLICACION - VENTANAS
		$salida=array();
		$salidas["id"]="list_".$cnf."_".$tp;
		$salidas["display"]="block";
		$salidas["titulo"]='';
		$salidas["attr"]["width"]='100%';
		$salidas["nItem"]=array();	

		$colC=0;

		$i=0;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1053-1';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=5;

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1130-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=52;			

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1221-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=35;

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1085-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=8;

		$sWhere=encrip_mysql('adm_empresas_v_names.TIPO_GRUPOPAL');
		$s=$sqlCons[1][91]." WHERE adm_empresas_v_names.ID_IDIOMA=:idioma AND $sWhere=:id  ".$sqlOrder[1][91];

		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':idioma', $_REQUEST["idioma"]);
		$req->bindParam(':id', $id_sha);
		$req->execute();		

		while($reg = $req->fetch()){
			$filC=$reg["ID_VENTANA"];
			$id_sha_t=encrip($filC);
			$md=$id_sha.$c_sha.$id_sha_t.$det_plus;

			$i=0;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["label"]=$filC;

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]='';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["VENTANA_NOMBRE"])?"":imprimir($reg["VENTANA_NOMBRE"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="nomb[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='nomb';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]="";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["SCVENTANA"])?"":imprimir($reg["SCVENTANA"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="abr[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='abr';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='button';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=$filC;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["icon"]="fa-save";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="Save[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["md"]=$md;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["idioma"]=$_REQUEST["idioma"];
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["enable"]='false';
		}
		echo json_encode($salidas);
	}
	elseif($tp==7){
		//PALABRAS DE APLICACION - GRUPOS
		$salida=array();
		$salidas["id"]="list_".$cnf."_".$tp;
		$salidas["display"]="block";
		$salidas["titulo"]='';
		$salidas["attr"]["width"]='100%';
		$salidas["nItem"]=array();	

		$colC=0;

		$i=0;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1053-1';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=5;

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1091-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=87;			

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1085-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=8;

		$sWhere=encrip_mysql('adm_empresas_v_grupo_name.TIPO_GRUPOPAL');
		$s=$sqlCons[1][92]." WHERE adm_empresas_v_grupo_name.ID_IDIOMA=:idioma AND $sWhere=:id  ".$sqlOrder[1][92];
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':idioma', $_REQUEST["idioma"]);
		$req->bindParam(':id', $id_sha);
		$req->execute();		

		while($reg = $req->fetch()){
			$filC=$reg["ID_GVENTANA"];
			$id_sha_t=encrip($filC);
			$md=$id_sha.$c_sha.$id_sha_t.$det_plus;

			$i=0;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["label"]=$filC;

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]="";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["DESC_GVENTANA"])?"":imprimir($reg["DESC_GVENTANA"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="desc[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='desc';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='button';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=$filC;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["icon"]="fa-save";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="Save[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["md"]=$md;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["idioma"]=$_REQUEST["idioma"];
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["enable"]='false';
		}
		echo json_encode($salidas);
	}
	elseif($tp==8){
		//PALABRAS DE APLICACION - VENTANA EN CONFIGURACIÓN
		$salida=array();
		$salidas["id"]="list_".$cnf."_".$tp;
		$salidas["display"]="block";
		$salidas["titulo"]='';
		$salidas["attr"]["width"]='100%';
		$salidas["nItem"]=array();	

		$colC=0;

		$i=0;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1053-1';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=5;

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1222-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=47;

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1222-1';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=40;			

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1085-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=8;

		$sWhere=encrip_mysql('adm_empresas_v_cont_names.TIPO_GRUPOPAL');
		$s=$sqlCons[1][93]." WHERE adm_empresas_v_cont_names.ID_IDIOMA=:idioma AND $sWhere=:id  ".$sqlOrder[1][93];
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':idioma', $_REQUEST["idioma"]);
		$req->bindParam(':id', $id_sha);
		$req->execute();		

		while($reg = $req->fetch()){
			$filC=$reg["ID_VENTANA"];
			$id_sha_t=encrip($filC);
			$md=$id_sha.$c_sha.$id_sha_t.$det_plus;

			$i=0;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["label"]=$filC;

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]="";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["TITULO_VENTANA"])?"":imprimir($reg["TITULO_VENTANA"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="titulo[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='titulo';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]="";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["STITULO_VENTANA"])?"":imprimir($reg["STITULO_VENTANA"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="stitulo[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='stitulo';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='button';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=$filC;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["icon"]="fa-save";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="Save[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["md"]=$md;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["idioma"]=$_REQUEST["idioma"];
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["enable"]='false';
		}
		echo json_encode($salidas);
	}
	elseif($tp==9){
		//PALABRAS DE APLICACION - CAMPOS DE VENTANA
		$salida=array();
		$salidas["id"]="list_".$cnf."_".$tp;
		$salidas["display"]="block";
		$salidas["titulo"]='';
		$salidas["attr"]["width"]='100%';
		$salidas["nItem"]=array();	

		$colC=0;

		$i=0;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1053-1';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=5;

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1130-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=37;

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1217-1';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=50;			

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1085-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=8;

		$sWhere=encrip_mysql('adm_empresas_v_cont_campo_names.TIPO_GRUPOPAL');
		$s=$sqlCons[1][94]." WHERE adm_empresas_v_cont_campo_names.ID_IDIOMA=:idioma AND $sWhere=:id  ".$sqlOrder[1][94];
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':idioma', $_REQUEST["idioma"]);
		$req->bindParam(':id', $id_sha);
		$req->execute();		

		while($reg = $req->fetch()){
			$filC=$reg["ID_CAMPO"];
			$id_sha_t=encrip($filC);
			$md=$id_sha.$c_sha.$id_sha_t.$det_plus;

			$i=0;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["label"]=$filC;

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]="";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["TITULO_CAMPO"])?"":imprimir($reg["TITULO_CAMPO"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="nomb[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='nomb';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]="";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["TOOLTIP_CAMPO"])?"":imprimir($reg["TOOLTIP_CAMPO"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="tooltip[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='tooltip';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='button';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=$filC;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["icon"]="fa-save";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="Save[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["md"]=$md;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["idioma"]=$_REQUEST["idioma"];
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["enable"]='false';
		}
		echo json_encode($salidas);
	}
	elseif($tp==10){
		//PALABRAS DE APLICACION - ESTADO DE EQUIPO
		$salida=array();
		$salidas["id"]="list_".$cnf."_".$tp;
		$salidas["display"]="block";
		$salidas["titulo"]='';
		$salidas["attr"]["width"]='100%';
		$salidas["nItem"]=array();	

		$colC=0;

		$i=0;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1053-1';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=5;

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1140-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=87;			

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1085-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=8;

		$sWhere=encrip_mysql('fac_estados_eq_desc.TIPO_GRUPOPAL');
		$s=$sqlCons[1][229]." WHERE fac_estados_eq_desc.ID_IDIOMA=:idioma AND $sWhere=:id  ".$sqlOrder[1][229];
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':idioma', $_REQUEST["idioma"]);
		$req->bindParam(':id', $id_sha);
		$req->execute();		

		while($reg = $req->fetch()){
			$filC=$reg["ID_ESTADO"];
			$id_sha_t=encrip($filC);
			$md=$id_sha.$c_sha.$id_sha_t.$det_plus;

			$i=0;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["label"]=$filC;

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]="";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["ESTADO"])?"":imprimir($reg["ESTADO"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="estado[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='estado';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='button';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=$filC;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["icon"]="fa-save";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="Save[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["md"]=$md;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["idioma"]=$_REQUEST["idioma"];
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["enable"]='false';
		}
		echo json_encode($salidas);
	}
	elseif($tp==11){
		//PALABRAS DE APLICACION - ESTADO DE OT
		$salida=array();
		$salidas["id"]="list_".$cnf."_".$tp;
		$salidas["display"]="block";
		$salidas["titulo"]='';
		$salidas["attr"]["width"]='100%';
		$salidas["nItem"]=array();	

		$colC=0;

		$i=0;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1053-1';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=5;

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1140-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=67;			

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1077-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=20;

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1085-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=8;

		$sWhere=encrip_mysql('fac_estados_ot_desc.TIPO_GRUPOPAL');
		$s=$sqlCons[1][237]." WHERE fac_estados_ot_desc.ID_IDIOMA=:idioma AND $sWhere=:id  ".$sqlOrder[1][237];
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':idioma', $_REQUEST["idioma"]);
		$req->bindParam(':id', $id_sha);
		$req->execute();		

		while($reg = $req->fetch()){
			$filC=$reg["ID_ESTADO"];
			$id_sha_t=encrip($filC);
			$md=$id_sha.$c_sha.$id_sha_t.$det_plus;

			$i=0;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["label"]=$filC;

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]="";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["ESTADO"])?"":imprimir($reg["ESTADO"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="estado[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='estado';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]="";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["ABR_EDO"])?"":imprimir($reg["ABR_EDO"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="abr[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='abr';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='button';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=$filC;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["icon"]="fa-save";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="Save[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["md"]=$md;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["idioma"]=$_REQUEST["idioma"];
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["enable"]='false';
		}
		echo json_encode($salidas);
	}
	elseif($tp==12){
		//PALABRAS DE APLICACION - TMMTO
		$salida=array();
		$salidas["id"]="list_".$cnf."_".$tp;
		$salidas["display"]="block";
		$salidas["titulo"]='';
		$salidas["attr"]["width"]='100%';
		$salidas["nItem"]=array();	

		$colC=0;

		$i=0;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1053-1';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=5;

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1091-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=27;	

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1183-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=30;	

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1224-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=15;			

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1225-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=15;

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1085-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=8;

		$sWhere=encrip_mysql('fac_tmmto_desc.TIPO_GRUPOPAL');
		$s=$sqlCons[1][235]." WHERE fac_tmmto_desc.ID_IDIOMA=:idioma AND $sWhere=:id  ".$sqlOrder[1][235];
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':idioma', $_REQUEST["idioma"]);
		$req->bindParam(':id', $id_sha);
		$req->execute();		

		while($reg = $req->fetch()){
			$filC=$reg["ID_TMMTO"];
			$id_sha_t=encrip($filC);
			$md=$id_sha.$c_sha.$id_sha_t.$det_plus;

			$i=0;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["label"]=$filC;

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]="";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["DESC_TMMTO"])?"":imprimir($reg["DESC_TMMTO"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="desc[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='desc';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]="";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["COMENT_TMMTO"])?"":imprimir($reg["COMENT_TMMTO"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="coment[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='coment';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]="";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["CONDICION"])?"":imprimir($reg["CONDICION"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="cond[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='cond';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]="";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["TIPO"])?"":imprimir($reg["TIPO"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="tipo[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='tipo';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='button';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=$filC;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["icon"]="fa-save";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="Save[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["md"]=$md;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["idioma"]=$_REQUEST["idioma"];
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["enable"]='false';
		}
		echo json_encode($salidas);
	}
	/*PERSONALIZADOS*/
	elseif($tp==13){
		//PALABRAS DE APLICACION
		$salida=array();
		$salidas["id"]="list_".$cnf."_".$tp;
		$salidas["display"]="block";
		$salidas["titulo"]='';
		$salidas["attr"]["width"]='100%';
		$salidas["nItem"]=array();	

		$colC=0;	
		$i=0;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1053-1';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=5;		

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1217-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=35;			

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1217-1';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=52;

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1085-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=8;
		
		/****/
		include "phplib/mysql_valores.php";
		$s=$sqlCons[1][68]." WHERE adm_textos.ID_IDIOMA=:idioma ".$sqlOrder[1][68];
		$req = $dbMat->prepare($s); 
		$req->bindParam(':idioma', $_REQUEST["idioma"]);
		$req->execute();
		while($reg = $req->fetch()){
			$filC=$reg["ID_PALABRA"];
			$id_sha_t=encrip($filC);
			$md=$id_sha.$c_sha.$id_sha_t.$det_plus;

			$i=0;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["label"]=$filC;

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]='';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["PALABRA"])?"":imprimir($reg["PALABRA"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="palabra[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='palabra';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]="";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["TOOLTIP"])?"":imprimir($reg["TOOLTIP"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="tooltip[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='tooltip';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='button';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=$filC;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["icon"]="fa-save";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="Save[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["md"]=$md;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["idioma"]=$_REQUEST["idioma"];
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["enable"]='false';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["new"]=false;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["global"]=true;
		}
		/*PERSONALIZADAS*/
		$s=$sqlCons[2][77].' UNION '.$sqlCons[3][77];		
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':idioma', $_REQUEST["idioma"]);
		$req->bindParam(':grupo', $_GCLIENTE);
		$req->bindParam(':empresa', $_CLIENTE);
		$req->bindParam(':id', $id_sha);
		$req->execute();
		while($reg = $req->fetch()){
			$filC=$reg["ID_PALABRA"];
			$id_sha_t=encrip($filC);
			$md=$id_sha.$c_sha.$id_sha_t.$det_plus;

			$i=0;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["label"]=$filC;

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]='';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["PALABRA"])?"":imprimir($reg["PALABRA"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="palabra[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='palabra';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]="";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["TOOLTIP"])?"":imprimir($reg["TOOLTIP"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="tooltip[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='tooltip';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='button';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=$filC;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["icon"]="fa-save";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="Save[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["md"]=$md;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["idioma"]=$_REQUEST["idioma"];
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["enable"]='false';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["new"]=is_null($reg["TIPO_GRUPOPAL"])?true:false;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["global"]=false;
		}
		
		echo json_encode($salidas);
	}

	elseif($tp==14){
		//PALABRAS DE APLICACION - VENTANAS
		$salida=array();
		$salidas["id"]="list_".$cnf."_".$tp;
		$salidas["display"]="block";
		$salidas["titulo"]='';
		$salidas["attr"]["width"]='100%';
		$salidas["nItem"]=array();	

		$colC=0;

		$i=0;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1053-1';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=5;

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1130-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=52;			

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1221-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=35;

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1085-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=8;

		$sWhere=encrip_mysql('adm_empresas_v_names.TIPO_GRUPOPAL');
		$s=$sqlCons[2][91]." WHERE adm_empresas_v_names.ID_IDIOMA=:idioma AND $sWhere=:id  ".$sqlOrder[1][91];		
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':idioma', $_REQUEST["idioma"]);
		$req->bindParam(':id', $id_sha);
		$req->execute();		

		while($reg = $req->fetch()){
			$filC=$reg["ID_VENTANA"];
			$id_sha_t=encrip($filC);
			$md=$id_sha.$c_sha.$id_sha_t.$det_plus;

			$i=0;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["label"]=$filC;

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]='';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["VENTANA_NOMBRE"])?"":imprimir($reg["VENTANA_NOMBRE"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="nomb[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='nomb';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]="";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["SCVENTANA"])?"":imprimir($reg["SCVENTANA"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="abr[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='abr';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='button';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=$filC;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["icon"]="fa-save";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="Save[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["md"]=$md;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["idioma"]=$_REQUEST["idioma"];
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["enable"]='false';
		}
		echo json_encode($salidas);
	}
	elseif($tp==15){
		//PALABRAS DE APLICACION - GRUPOS
		$salida=array();
		$salidas["id"]="list_".$cnf."_".$tp;
		$salidas["display"]="block";
		$salidas["titulo"]='';
		$salidas["attr"]["width"]='100%';
		$salidas["nItem"]=array();	

		$colC=0;

		$i=0;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1053-1';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=5;

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1091-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=87;			

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1085-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=8;

		$sWhere=encrip_mysql('adm_empresas_v_grupo_name.TIPO_GRUPOPAL');
		$s=$sqlCons[2][92]." WHERE adm_empresas_v_grupo_name.ID_IDIOMA=:idioma AND $sWhere=:id  ".$sqlOrder[1][92];
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':idioma', $_REQUEST["idioma"]);
		$req->bindParam(':id', $id_sha);
		$req->execute();		

		while($reg = $req->fetch()){
			$filC=$reg["ID_GVENTANA"];
			$id_sha_t=encrip($filC);
			$md=$id_sha.$c_sha.$id_sha_t.$det_plus;

			$i=0;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["label"]=$filC;

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]="";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["DESC_GVENTANA"])?"":imprimir($reg["DESC_GVENTANA"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="desc[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='desc';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='button';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=$filC;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["icon"]="fa-save";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="Save[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["md"]=$md;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["idioma"]=$_REQUEST["idioma"];
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["enable"]='false';
		}
		echo json_encode($salidas);
	}
	elseif($tp==16){
		//PALABRAS DE APLICACION - VENTANA EN CONFIGURACIÓN
		$salida=array();
		$salidas["id"]="list_".$cnf."_".$tp;
		$salidas["display"]="block";
		$salidas["titulo"]='';
		$salidas["attr"]["width"]='100%';
		$salidas["nItem"]=array();	

		$colC=0;

		$i=0;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1053-1';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=5;

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1222-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=47;

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1222-1';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=40;			

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1085-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=8;

		$sWhere=encrip_mysql('adm_empresas_v_cont_names.TIPO_GRUPOPAL');
		$s=$sqlCons[2][93]." WHERE adm_empresas_v_cont_names.ID_IDIOMA=:idioma AND $sWhere=:id  ".$sqlOrder[1][93];
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':idioma', $_REQUEST["idioma"]);
		$req->bindParam(':id', $id_sha);
		$req->execute();		

		while($reg = $req->fetch()){
			$filC=$reg["ID_VENTANA"];
			$id_sha_t=encrip($filC);
			$md=$id_sha.$c_sha.$id_sha_t.$det_plus;

			$i=0;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["label"]=$filC;

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]="";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["TITULO_VENTANA"])?"":imprimir($reg["TITULO_VENTANA"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="titulo[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='titulo';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]="";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["STITULO_VENTANA"])?"":imprimir($reg["STITULO_VENTANA"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="stitulo[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='stitulo';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='button';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=$filC;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["icon"]="fa-save";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="Save[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["md"]=$md;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["idioma"]=$_REQUEST["idioma"];
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["enable"]='false';
		}
		echo json_encode($salidas);
	}
	elseif($tp==17){
		//PALABRAS DE APLICACION - CAMPOS DE VENTANA
		$salida=array();
		$salidas["id"]="list_".$cnf."_".$tp;
		$salidas["display"]="block";
		$salidas["titulo"]='';
		$salidas["attr"]["width"]='100%';
		$salidas["nItem"]=array();	

		$colC=0;

		$i=0;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1053-1';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=5;

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1130-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=37;

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1217-1';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=50;			

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1085-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=8;

		$sWhere=encrip_mysql('adm_empresas_v_cont_campo_names.TIPO_GRUPOPAL');
		$s=$sqlCons[2][94]." WHERE adm_empresas_v_cont_campo_names.ID_IDIOMA=:idioma AND $sWhere=:id  ".$sqlOrder[1][94];
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':idioma', $_REQUEST["idioma"]);
		$req->bindParam(':id', $id_sha);
		$req->execute();		

		while($reg = $req->fetch()){
			$filC=$reg["ID_CAMPO"];
			$id_sha_t=encrip($filC);
			$md=$id_sha.$c_sha.$id_sha_t.$det_plus;

			$i=0;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["label"]=$filC;



			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]="";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["TITULO_CAMPO"])?"":imprimir($reg["TITULO_CAMPO"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="nomb[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='nomb';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]="";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["TOOLTIP_CAMPO"])?"":imprimir($reg["TOOLTIP_CAMPO"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="tooltip[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='tooltip';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='button';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=$filC;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["icon"]="fa-save";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="Save[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["md"]=$md;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["idioma"]=$_REQUEST["idioma"];
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["enable"]='false';
		}
		echo json_encode($salidas);
	}
}
/*************************/
/*************************/
/*************************/
/*******LANDING PAGE*****/
/*************************/
/*************************/
/*************************/
elseif($cnf==4){
	$c_sha=encrip($cnf,2);
	$det_plus=sprintf("%03s",($tp+1));
	if($tp==1){

		$salida=array();
		$salidas["display"]="block";
		$salidas["titulo"]='';
		$salidas["id"]='landingtable';
		$salidas["attr"]["width"]='100%';
		$salidas["nItem"]=array();	

		$colC=0;	
		$i=0;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1053-1'; //ID
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=5;

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1138-0'; //Etiqueta
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=15;

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1222-0'; //TITULO
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=32;

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1217-0'; // TEXTO
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=40;			

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1085-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=8;

		$s=$sqlCons[1][75]." WHERE adm_landing.ID_IDIOMA=:idioma ".$sqlOrder[1][75];
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':idioma', $_REQUEST["idioma"]);
		$req->execute();		

		while($reg = $req->fetch()){
			$filC=$reg["ID_LAND"];
			$id_sha=encrip($filC);
			$md=$id_sha.$c_sha.$id_sha.$det_plus;


			$i=0;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["label"]=$filC;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["idlbl"]=$filC;

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]="";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["ETI_DIV"])?"":imprimir($reg["ETI_DIV"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="palabra[".$reg["ID_LAND"]."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='etiqueta';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]="";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["TITULO"])?"":imprimir($reg["TITULO"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="tooltip[".$reg["ID_LAND"]."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='titulo';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]="";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=is_null($reg["TEXTO"])?"":imprimir($reg["TEXTO"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="tooltip[".$reg["ID_LAND"]."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='texto';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='button';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=$reg["ID_LAND"];
			$salidas["nItem"][$filC]["cont"][$colC.$i]["icon"]="fa-save";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="Save[".$reg["ID_LAND"]."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["md"]=$md;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["idioma"]=$_REQUEST["idioma"];
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["enable"]='false';
		}
		echo json_encode($salidas);
	}
}
/*************************/
/*************************/
/*************************/
/*********FRONTEND********/
/*************************/
/*************************/
/*************************/
elseif($cnf==2){
	//URLS
	if($tp==1){
		$c_sha=encrip($cnf,2);
		$det_plus='001';

		$salida=array();
		$salidas["id"]="FRONTUrls";
		$salidas["display"]="block";
		$salidas["titulo"]='';
		$salidas["titulos"]=array();
		$salidas["nItem"]=array();
		$salidas["attr"]["width"]='100%';
		$colC=0;	
		
		$i=0;
		$k=0;
		$salidas["titulos"][$k]["cont"][$i]["label"]='txt-3009-0'; //URL
		$salidas["titulos"][$k]["cont"][$i]["width"]=60;
		$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";	

		$i++;
		$salidas["titulos"][$k]["cont"][$i]["label"]='txt-1085-0'; //Guardar
		$salidas["titulos"][$k]["cont"][$i]["width"]=20;
		$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";	

		$i++;
		$salidas["titulos"][$k]["cont"][$i]["label"]='txt-1005-0'; //Eliminar
		$salidas["titulos"][$k]["cont"][$i]["width"]=20;
		$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";	
	
		
		$s=$sqlCons[1][104]." WHERE adm_empresas_url.ID_MEMPRESA=$_CLIENTE ".$sqlOrder[1][104];	
		$req = $dbEmpresa->prepare($s); 
		$req->execute();
		$k=0;
		while($reg = $req->fetch()){
			$filC=$reg["ID_URLS"];
			$id_sha=encrip($filC);
			$md=$id_sha.$c_sha.$id_sha.$det_plus;	

			$salidas["nItem"][$filC]=array();
				
			$i=0;
			$salidas["nItem"][$filC]["cont"][$i]["placeholder"]="";
			$salidas["nItem"][$filC]["cont"][$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$i]["value"]=$reg["URL"];
			$salidas["nItem"][$filC]["cont"][$i]["name"]="url[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$i]["data"]["name"]='url';
			$salidas["nItem"][$filC]["cont"][$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$i]["label"]='';
			$salidas["nItem"][$filC]["cont"][$i]["tipo"]='button';
			$salidas["nItem"][$filC]["cont"][$i]["value"]=$filC;
			$salidas["nItem"][$filC]["cont"][$i]["icon"]="fa-save";
			$salidas["nItem"][$filC]["cont"][$i]["name"]="Save[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$i]["data"]["md"]=$md;
			$salidas["nItem"][$filC]["cont"][$i]["data"]["enable"]='false';

			$i++;
			$salidas["nItem"][$filC]["cont"][$i]["label"]='';
			$salidas["nItem"][$filC]["cont"][$i]["tipo"]='button';
			$salidas["nItem"][$filC]["cont"][$i]["value"]=$filC;
			$salidas["nItem"][$filC]["cont"][$i]["icon"]="fa-trash";
			$salidas["nItem"][$filC]["cont"][$i]["name"]="Delete[".$filC."]";
			$salidas["nItem"][$filC]["cont"][$i]["data"]["md"]=$id_sha.$c_sha.$acc03;

			$salidas["nItem"][$filC]["cont"][$i]["data"]["title"]='txt-1376-0'; 
			$salidas["nItem"][$filC]["cont"][$i]["data"]["msg"]='txt-1376-1'; 
			$salidas["nItem"][$filC]["cont"][$i]["data"]["confirm"]='txt-1025-0'; 
			$salidas["nItem"][$filC]["cont"][$i]["data"]["deltable"]=true;	
			$salidas["nItem"][$filC]["cont"][$i]["data"]["label"]='txt-1005-0';	
			$salidas["nItem"][$filC]["cont"][$i]["data"]["cancel"]='txt-1028-0';	
		}				
		/***************************************/
		/***************************************/		
		$k++;
		echo json_encode($salidas);
	}	
	elseif($tp==2){
		$c_sha=encrip($cnf,2);
		$det_plus=sprintf("%03s",2);

		$salida=array();
		$salidas["display"]="block";
		$salidas["titulo"]='';
		$salidas["attr"]["width"]='100%';
		$salidas["nItem"]=array();	

		$colC=0;	
		$i=0;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1053-1'; //ID
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=5;

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1138-0'; //Etiqueta
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=15;

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1222-0'; //TITULO
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=32;

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1217-0'; // TEXTO
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=40;			

		$i++;
		$salidas["titulos"][1]["cont"][$colC.$i]["label"]='txt-1085-0';
		$salidas["titulos"][1]["cont"][$colC.$i]["width"]=8;
		
		$sReg=array();
		$s=$sqlCons[1][75]." WHERE adm_landing.ID_IDIOMA=:idioma ".$sqlOrder[1][75];
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':idioma', $_REQUEST["idioma"]);
		$req->execute();
		while($reg = $req->fetch()){
			$sReg[$reg["ID_LAND"]]["ID_LAND"]=$reg["ID_LAND"];
			$sReg[$reg["ID_LAND"]]["ETI_DIV"]=$reg["ETI_DIV"];
			$sReg[$reg["ID_LAND"]]["ID_LAND"]=$reg["ID_LAND"];
			
			$sReg[$reg["ID_LAND"]]["TITULO"]="";
			$sReg[$reg["ID_LAND"]]["TEXTO"]="";
			$sReg[$reg["ID_LAND"]]["ETI_DIV"]="";

			$sReg[$reg["ID_LAND"]]["TITULO_ORI"]=$reg["TITULO"];
			$sReg[$reg["ID_LAND"]]["TEXTO_ORI"]=$reg["TEXTO"];
			$sReg[$reg["ID_LAND"]]["ETI_DIV_ORI"]=$reg["ETI_DIV"];
		}
		$s=$sqlCons[2][75]." WHERE adm_empresas_landing.ID_IDIOMA=:idioma AND adm_empresas_landing.ID_MEMPRESA=$_CLIENTE ".$sqlOrder[2][75];
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':idioma', $_REQUEST["idioma"]);
		$req->execute();
		while($reg = $req->fetch()){
			$sReg[$reg["ID_LAND"]]["ID_LAND"]=$reg["ID_LAND"];
			$sReg[$reg["ID_LAND"]]["ETI_DIV"]=$reg["ETI_DIV"];
			$sReg[$reg["ID_LAND"]]["ID_LAND"]=$reg["ID_LAND"];

			$sReg[$reg["ID_LAND"]]["TITULO"]=$reg["TITULO"];
			$sReg[$reg["ID_LAND"]]["TEXTO"]=$reg["TEXTO"];

			$sReg[$reg["ID_LAND"]]["TITULO_ORI"]=$reg["TITULO"];
			$sReg[$reg["ID_LAND"]]["TEXTO_ORI"]=$reg["TEXTO"];
			$sReg[$reg["ID_LAND"]]["ETI_DIV_ORI"]=$reg["ETI_DIV"];
		}

		foreach ($sReg as $key => $reg) {
			$filC=$reg["ID_LAND"];
			$id_sha=encrip($filC);
			$md=$id_sha.$c_sha.$id_sha.$det_plus;


			$i=0;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["label"]=$filC;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["idlbl"]=$filC;

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]=$reg["ETI_DIV_ORI"];
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=imprimir($reg["ETI_DIV"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="palabra[".$reg["ID_LAND"]."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='etiqueta';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]=$reg["TITULO_ORI"];
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=imprimir($reg["TITULO"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="tooltip[".$reg["ID_LAND"]."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='titulo';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["placeholder"]=$reg["TEXTO_ORI"];
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='text';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=imprimir($reg["TEXTO"]);
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="tooltip[".$reg["ID_LAND"]."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["name"]='texto';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["change"]='true';

			$i++;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["tipo"]='button';
			$salidas["nItem"][$filC]["cont"][$colC.$i]["value"]=$reg["ID_LAND"];
			$salidas["nItem"][$filC]["cont"][$colC.$i]["icon"]="fa-save";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["name"]="Save[".$reg["ID_LAND"]."]";
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["md"]=$md;
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["idioma"]=$_REQUEST["idioma"];
			$salidas["nItem"][$filC]["cont"][$colC.$i]["data"]["enable"]='false';
		}
		echo json_encode($salidas);
	}

}
//GRUPOS CLASE
elseif($cnf==10003){
	if($tp==1){	
		$sWhere=encrip_mysql('adm_ventanas_etipo.TIPO_GRUPOPAL');
		$s=$sqlCons[1][100]." AND $sWhere=:id ".$sqlOrder[1][100];		
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		$req->execute();
		
		
		$salida=array();
		$salidas["id"]="tblVentanasRel";
		$salidas["display"]="block";
		$salidas["titulo"]='';
		$salidas["titulos"]=array();
		$salidas["nItem"]=array();
		
		$i=0;
		$k=0;
		$salidas["titulos"][$k]["cont"][$i]["label"]='txt-1117-0';
		$salidas["titulos"][$k]["cont"][$i]["width"]=5;
		
		$i++;
		$salidas["titulos"][$k]["cont"][$i]["label"]='txt-1112-0';
		$salidas["titulos"][$k]["cont"][$i]["width"]=40;
		
		$i++;
		$salidas["titulos"][$k]["cont"][$i]["label"]='txt-1083-0';
		$salidas["titulos"][$k]["cont"][$i]["width"]=55;
		
		$k=0;
		while($reg = $req->fetch()){
			$salidas["nItem"][$k]=array();			
			$i=0;			
			$salidas["nItem"][$k]["cont"][$i]["label"]='txt-1117-0';
			$salidas["nItem"][$k]["cont"][$i]["tipo"]='checkbox';
			$salidas["nItem"][$k]["cont"][$i]["checked"]=$reg["PERMISO_GRUPOVEN"]==1;
			$salidas["nItem"][$k]["cont"][$i]["value"]=$reg["ID_VENTANA"];
			$salidas["nItem"][$k]["cont"][$i]["tipobtn"]=$btn_vicular_ven;
			$salidas["nItem"][$k]["cont"][$i]["name"]="Ventanas[".$reg["ID_VENTANA"]."]";
			$salidas["nItem"][$k]["cont"][$i]["id"]="Ventanas_".$reg["ID_VENTANA"];
			$i++;
			$salidas["nItem"][$k]["cont"][$i]["label"]=$reg["VENTANA_NOMBRE"];
			$i++;
			$salidas["nItem"][$k]["cont"][$i]["label"]=$reg["DESC_GVENTANA"];

			$i++;
			$salidas["nItem"][$k]["cont"][$i]["tipo"]='hidden';
			$salidas["nItem"][$k]["cont"][$i]["value"]=$reg["ID_VENTANA"];
			$salidas["nItem"][$k]["cont"][$i]["name"]="IdVentana[".$reg["ID_VENTANA"]."]";
			$salidas["nItem"][$k]["cont"][$i]["id"]="IdVentana_".$reg["ID_VENTANA"];						
			$k++;			
		}	
		if($k==0) $salidas["titulo"]='txt-1118-0';
		echo json_encode($salidas);
	}
}
elseif($cnf==10004){
	$sWhere=encrip_mysql("adm_empresas.ID_MEMPRESA");	
	$s="SELECT adm_empresas.ID_MEMPRESA FROM adm_empresas WHERE $sWhere=:id LIMIT 1";	
	$req = $dbEmpresa->prepare($s); 
	$req->bindParam(':id', $id_sha);
	$req->execute();	
	if($reg = $req->fetch()) $id_mempresa=$reg["ID_MEMPRESA"];
	
	//GRUPOS
	if($tp==1){	

		$s=$sqlCons[1][108]." WHERE adm_grupos.HAB_GRUPO=0 AND adm_grupos.ID_MEMPRESA=:id_mempresa ".$sqlOrder[1][108];
	    $reqOP = $dbEmpresa->prepare($s);
	    $reqOP->bindParam(':id_mempresa', $id_mempresa);
	    $reqOP->execute();

		$grupos=array();
		$grupos[]=array("value"	=>0
					,		"label"	=>'txt-1435-1'
					,		"cont"	=>array());
		while($regOP = $reqOP->fetch()){
			$grupos[]=array("value"	=>$regOP["ID_GRUPO"]
					,		"label"	=>imprimir($regOP["DESC_GRUPO"],2)
					,		"cont"	=>array());

		}

		$s=$sqlCons[2][108].' WHERE adm_usuarios_empresa.ID_MEMPRESA=:id_mempresa '.$sqlOrder[2][108];		 					
		$req = $dbEmpresa->prepare($s); 		
		$req->bindParam(':id_mempresa', $id_mempresa);
		$req->execute(); 			


		$salida=array();
		$salidas["display"]="block";
		$salidas["titulo"]='';
		$salidas["titulos"]=array();
		$salidas["nItem"]=array();
		
		$i=0;
		$k=0;
		$salidas["titulos"][$k]["cont"][$i]["label"]='txt-1110-0'; // Usuario
		$salidas["titulos"][$k]["cont"][$i]["width"]=45;
		
		$i++;
		$salidas["titulos"][$k]["cont"][$i]["width"]=55;
		$salidas["titulos"][$k]["cont"][$i]["label"]='txt-1139-0'; // Grupo

		$k=0;
		while($reg = $req->fetch()){
			$user=$reg['ID_USUARIO'];	
			$name=$reg["NOMBRE_U"]!=''?imprimir($reg["NOMBRE_U"]).' '.imprimir($reg["APELLIDO_U"]):$reg["CORREO_U"];

			$salidas["nItem"][$k]=array();		
			$i=0;
			$salidas["nItem"][$k]["cont"][$i]["label"]=$name;
			
			$i++;
			$salidas["nItem"][$k]["cont"][$i]["tipo"]='combobox';
			$salidas["nItem"][$k]["cont"][$i]["value"]=$reg['ID_GRUPO'];
			$salidas["nItem"][$k]["cont"][$i]["options"]=$grupos;
			$salidas["nItem"][$k]["cont"][$i]["name"]="IdGroup[".$user."]";
			$k++;
		}		
		echo json_encode($salidas);
	}
}
else{
	if($_PROYECTO==1) 		include "solinfo_001.php";
	elseif($_PROYECTO==8)	include "solinfo_008.php";
	elseif($_PROYECTO==10)	include "solinfo_010.php";
	elseif($_PROYECTO==11)	include "solinfo_011.php";
	elseif($_PROYECTO==13)	include "solinfo_013.php";
	elseif($_PROYECTO==14)	include "solinfo_014.php";
	elseif($_PROYECTO==15)	include "solinfo_015.php";
	elseif($_PROYECTO==16)	include "solinfo_016.php";
	elseif($_PROYECTO==18)	include "solinfo_018.php";
	elseif($_PROYECTO==20)	include "solinfo_020.php";
	elseif($_PROYECTO==22)	include "solinfo_022.php";
	elseif($_PROYECTO==23)	include "solinfo_023.php";
	elseif($_PROYECTO==24)	include "solinfo_024.php";
	elseif($_PROYECTO==25)	include "solinfo_025.php";
	elseif($_PROYECTO==26)	include "solinfo_026.php";
	elseif($_PROYECTO==27)	include "solinfo_027.php";
	elseif($_PROYECTO==28)	include "solinfo_028.php";
	elseif($_PROYECTO==29)	include "solinfo_029.php";
	elseif($_PROYECTO==32)	include "solinfo_032.php";
	elseif($_PROYECTO==36)	include "solinfo_036.php";
}
?>
<?php
$tp=isset($_REQUEST["tp"])?$_REQUEST["tp"]:1;
$busc=isset($_REQUEST["term"])?$_REQUEST["term"]:'';
$busc_query='%'.$busc.'%';
$cmp=isset($_REQUEST["cmp"])?$_REQUEST["cmp"]:'';


$sh=isset($_REQUEST["sh"])?$_REQUEST["sh"]:1;

if(isset($_REQUEST["md"])){
	$md=isset($_REQUEST["md"])?$_REQUEST["md"]:'';
	$id_sha=mb_substr($md,0,40);
	$c_sha=mb_substr($md,40,32);
}
if($tp==1){
	
	/*EXISTENTES*/
	$existente=count($_REQUEST["IdCiudad"])!=0;
	if($existente){
		$aplica=$_REQUEST["IdCiudad"];			
		$aplica_id=array();
		foreach ($aplica as $k => $apli){ $aplica_id[]=":$k";}
		$aplica_id_imp=implode(",",$aplica_id);
		
	}
	/**/
	$sWhere_idresp=encrip_mysql('s_cresp_ciudades.ID_RESP');
	$sWhere_cons=sWhere_cons(20,$busc);
	
	if($existente)
		$s=$sqlCons[1][45]." WHERE fac_ciudades.ID_CIUDAD NOT IN ($aplica_id_imp) $sWhere_cons LIMIT 20";
	else
		$s=$sqlCons[1][45]." WHERE fac_ciudades.ID_CIUDAD<>0 $sWhere_cons LIMIT 20";		
	$req = $dbEmpresa->prepare($s);	
	if($busc!='') $req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
	if($existente){
		foreach ($aplica as $k => $apli){ 
			$req ->bindParam(":$k", $aplica[$k]);
		}
	}	
	$req->execute();
	
	$i=0;
	$autocomplete=array();
	while($reg = $req->fetch()){		
		$autocomplete[$k]["id"]=$reg["ID_CIUDAD"];
		$autocomplete[$k]["value"]=sprintf("%s, %s",imprimir($reg["NOMB_CIUDAD"],2),imprimir($reg["COD_PAIS"],2));
		$autocomplete[$k]["ciudad"]=imprimir($reg["NOMB_CIUDAD"],2);
		$autocomplete[$k]["pais"]=imprimir($reg["NOMB_PAIS"],2);
		$autocomplete[$k]["dir"]=sprintf("%s, %s",($reg["NOMB_PAIS"]),($reg["NOMB_CIUDAD"]));
		$autocomplete[$k]["cont"]=array();
	
		$i=0;		
		$autocomplete[$k]["cont"][$i]["label"]=sprintf("%s, %s",imprimir($reg["NOMB_CIUDAD"]),imprimir($reg["COD_PAIS"]));			
		
		$i++;
		$autocomplete[$k]["cont"][$i]["label"]='txt-1005-0';
		$autocomplete[$k]["cont"][$i]["tipo"]='button';
		$autocomplete[$k]["cont"][$i]["value"]=$reg["ID_CIUDAD"];
		$autocomplete[$k]["cont"][$i]["icon"]="fa-trash";
		$autocomplete[$k]["cont"][$i]["name"]="DelCiudad[".$reg["ID_CIUDAD"]."]";
		$autocomplete[$k]["cont"][$i]["data"]["altern"]='txt-1010-0'; 
		$autocomplete[$k]["cont"][$i]["data"]["delform"]=true;	
		$autocomplete[$k]["cont"][$i]["data"]["delverif"]='EDCiudad';
		
		$i++;
		$autocomplete[$k]["cont"][$i]["tipo"]='hidden';
		$autocomplete[$k]["cont"][$i]["value"]=$reg["ID_CIUDAD"];
		$autocomplete[$k]["cont"][$i]["name"]="IdCiudad[".$reg["ID_CIUDAD"]."]";

		$i++;
		$autocomplete[$k]["cont"][$i]["tipo"]='hidden';
		$autocomplete[$k]["cont"][$i]["value"]=20;
		$autocomplete[$k]["cont"][$i]["name"]="EDCiudad[".$reg["ID_CIUDAD"]."]";
		$k++;
	}
	echo json_encode($autocomplete);	
}
elseif($tp==2){
	$autocomplete=array();
	$sWhere_cons=sWhere_cons(20,$busc);
	$i=0;
	
	if($_PROYECTO==5) 		$restric="fac_ciudades.ID_CIUDAD IN ($sCiudades) ";
	elseif($_PROYECTO!=5) 	$restric="fac_ciudades.ID_CIUDAD <> 0";

	$s=$sqlCons[1][45]." WHERE $restric $sWhere_cons LIMIT 20";
	$req = $dbEmpresa->prepare($s); 		
	if($busc!='') $req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);	
	$req->execute();	
	while($reg = $req->fetch()){
		$autocomplete[$i]["id"]=$reg["ID_CIUDAD"];
		$autocomplete[$i]["value"]=sprintf("%s -%s",imprimir($reg["NOMB_CIUDAD"],2),imprimir($reg["COD_PAIS"],2));
		$autocomplete[$i]["cont"]=array();	
		
		$autocomplete[$i]["cont"]["id_ciudad"]=$reg["ID_CIUDAD"];
		$autocomplete[$i]["cont"]["desc_ciudad"]=imprimir($reg["NOMB_CIUDAD"],2);
		$autocomplete[$i]["cont"]["desc_pais"]=imprimir($reg["NOMB_PAIS"],2);
		$autocomplete[$i]["cont"]["desc_depto"]=imprimir($reg["DISTRITO_CIUDAD"],2);
		$autocomplete[$i]["cont"]["ciudaddesc"]=imprimir($reg["NOMB_CIUDAD"],2).' - '.imprimir($reg["COD_PAIS"],2);
		$i++;
	}
	echo json_encode($autocomplete);	
}
elseif($tp==7){
	/*EXISTENTES*/
	$existente=isset($_REQUEST["IdUs"])?true:false;
	if($existente){
		$aplica=$_REQUEST["IdUs"];			
		$aplica_id=array();
		foreach ($aplica as $k => $apli){ $aplica_id[]=":$k";}
		$aplica_id_imp=implode(",",$aplica_id);
		
	}
	/**/
	$sWhere_idresp=encrip_mysql('adm_usuarios.ID_USUARIO');
	
	if($_PROYECTO==1) $sWhere_cons=sWhere_cons(11,$busc);
	elseif($_PROYECTO==5) $sWhere_cons=sWhere_cons(54,$busc);
	elseif($_PROYECTO==7) $sWhere_cons=sWhere_cons(54,$busc);
	elseif($_PROYECTO==8) $sWhere_cons=sWhere_cons(11,$busc);
	
	$s=$sqlCons[1][0]." WHERE $sWhere_idresp<>:id ".
					($existente?" AND adm_usuarios.ID_USUARIO NOT IN ($aplica_id_imp) ":"").$sWhere_cons;				
	$req = $dbEmpresa->prepare($s); 
	$req->bindParam(':id', $cmp);
		
	if($busc!='') $req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
	if($existente){
		foreach ($aplica as $k => $apli){ $req ->bindParam(":$k", $aplica[$k]);}
	}
	$req->execute();
	
	$k=0;	
	$autocomplete=array();
	while($reg = $req->fetch()){
		
		$autocomplete[$k]["id"]=$reg["ID_USUARIO"];
		if($reg["NOMBRE_U"]!="") 		$autocomplete[$k]["value"]=$reg["NOMBRE_U"].' '.$reg["APELLIDO_U"];
		elseif($reg["CORREO_U"]!="") 	$autocomplete[$k]["value"]=$reg["CORREO_U"];
		else 							$autocomplete[$k]["value"]=$reg["ALIAS"];
			
		$autocomplete[$k]["cont"]=array();
		if($sh==2){
			$i=0;
			if($reg["NOMBRE_U"]!="") 		$autocomplete[$k]["cont"][$i]["label"]=$reg["NOMBRE_U"].' '.$reg["APELLIDO_U"];
			elseif($reg["CORREO_U"]!="") 	$autocomplete[$k]["cont"][$i]["label"]=$reg["CORREO_U"];
			else 							$autocomplete[$k]["cont"][$i]["label"]=$reg["ALIAS"];
			
			$i++;
			$autocomplete[$k]["cont"][$i]["label"]=$reg["ABR_DOCUMENTO"].' '.$reg["DOCUMENTO"];
		
			$i++;
			$autocomplete[$k]["cont"][$i]["label"]='txt-1005-0'; 
			$autocomplete[$k]["cont"][$i]["tipo"]='button';
			$autocomplete[$k]["cont"][$i]["value"]=time();
			$autocomplete[$k]["cont"][$i]["icon"]="fa-trash";
			$autocomplete[$k]["cont"][$i]["name"]="Del[".time()."]";
			$autocomplete[$k]["cont"][$i]["id"]="Del_".time();
			$autocomplete[$k]["cont"][$i]["data"]["delform"]="true";
			$autocomplete[$k]["cont"][$i]["data"]["delvalue"]=20;
			$autocomplete[$k]["cont"][$i]["data"]["altern"]='txt-1010-0'; 

			$i++;
			$autocomplete[$k]["cont"][$i]["tipo"]='hidden';
			$autocomplete[$k]["cont"][$i]["value"]=$reg["ID_USUARIO"];
			$autocomplete[$k]["cont"][$i]["name"]="IdUs[".time()."]";
			$autocomplete[$k]["cont"][$i]["id"]="IdUs".time();

			$i++;
			$autocomplete[$k]["cont"][$i]["tipo"]='hidden';
			$autocomplete[$k]["cont"][$i]["value"]=20;
			$autocomplete[$k]["cont"][$i]["name"]="EDUs[".time()."]";
			$autocomplete[$k]["cont"][$i]["id"]="EDUs_".time();
		}
		$k++;
	}
	echo json_encode($autocomplete);	
}
elseif($tp==13){
	/*EXISTENTES*/
	if($_PROYECTO==1) $sWhere_cons=sWhere_cons(11,$busc);
	elseif($_PROYECTO==5) $sWhere_cons=sWhere_cons(54,$busc);
	elseif($_PROYECTO==7) $sWhere_cons=sWhere_cons(54,$busc);
	elseif($_PROYECTO==8) $sWhere_cons=sWhere_cons(11,$busc);
	
	$s=$sqlCons[1][0]." WHERE adm_usuarios.HAB_U=0 $sWhere_cons LIMIT 20";	
	
					
	$req = $dbEmpresa->prepare($s); 
	$req->bindParam(':id', $cmp);
		
	if($busc!='') $req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
	if($existente){
		foreach ($aplica as $k => $apli){ $req ->bindParam(":$k", $aplica[$k]);}
	}
	$req->execute();
	
	$k=0;	
	$autocomplete=array();
	while($reg = $req->fetch()){
		
		$autocomplete[$k]["id"]=$reg["ID_USUARIO"];
		if($reg["NOMBRE_U"]!="") 		$autocomplete[$k]["value"]=sprintf("%s %s (%s)",$reg["NOMBRE_U"],$reg["APELLIDO_U"],$reg["CORREO_U"]);
		elseif($reg["CORREO_U"]!="") 	$autocomplete[$k]["value"]=$reg["CORREO_U"];
		else 							$autocomplete[$k]["value"]=$reg["ALIAS"];
		
		
		$autocomplete[$k]["cont"]=array();
		
		$autocomplete[$k]["cont"]["iduser"]=$reg["ID_USUARIO"];
		$autocomplete[$k]["cont"]["nombreu"]=($reg["NOMBRE_U"]);
		$autocomplete[$k]["cont"]["apellidou"]=($reg["APELLIDO_U"]);
		$autocomplete[$k]["cont"]["tdocumento"]=$reg["ID_DOCUMENTO"];
		$autocomplete[$k]["cont"]["documento"]=$reg["DOCUMENTO_TIPO"];
		$autocomplete[$k]["cont"]["nombrecomp"]=sprintf("%s %s (%s)",$reg["NOMBRE_U"],$reg["APELLIDO_U"],$reg["CORREO_U"]);
		
		
		$autocomplete[$k]["cont"]["id_ciudaddoc"]=$reg["ID_CIUDAD"];
		$autocomplete[$k]["cont"]["ciudaddoc"]=sprintf("%s [%s]",$reg["NOMB_CIUDAD"],$reg["COD_PAIS"]);
		
		$autocomplete[$k]["cont"]["genero"]=$reg["ID_GENERO"];
		
		$autocomplete[$k]["cont"]["telefono"]=($reg["TELEFONO_U"]);		
		$autocomplete[$k]["cont"]["correou"]=($reg["CORREO_U"]);
		
		$autocomplete[$k]["cont"]["id_location"]=$reg["ID_LOCATION"];
		$autocomplete[$k]["cont"]["datoscomp"]=$reg["DATOS_US"];
		
		$autocomplete[$k]["cont"]["idtcliente"]=$reg["ID_TCLIENTE"];
		$autocomplete[$k]["cont"]["tcliente"]=$reg["TCLIENTE"];
		$autocomplete[$k]["cont"]["desctcliente"]=$reg["DESCU_TCLIENTE"];
		$k++;
	}	
	
	$autocomplete[$k]["id"]=0;
	$autocomplete[$k]["value"]='txt-1092-0';
	$autocomplete[$k]["cont"]=array();	
	
	$autocomplete[$k]["cont"]["iduser"]=0;
	$autocomplete[$k]["cont"]["nombreu"]="";
	$autocomplete[$k]["cont"]["apellidou"]="";
	$autocomplete[$k]["cont"]["tdocumento"]="";
	$autocomplete[$k]["cont"]["iduser"]=0;
	
	$autocomplete[$k]["cont"]["id_ciudaddoc"]=0;
	$autocomplete[$k]["cont"]["ciudaddoc"]="";
	
	$autocomplete[$k]["cont"]["telefono"]="";		
	$autocomplete[$k]["cont"]["direccionu"]="";
	$autocomplete[$k]["cont"]["correou"]="";
	
	$autocomplete[$k]["cont"]["id_location"]=0;
	$autocomplete[$k]["cont"]["datoscomp"]=0;
	
	$autocomplete[$k]["cont"]["idtcliente"]="";
	$autocomplete[$k]["cont"]["tcliente"]="";
	$autocomplete[$k]["cont"]["desctcliente"]="";
	echo json_encode($autocomplete);	
}
elseif($tp==35){
	$area=isset($_REQUEST["area"])?$_REQUEST["area"]:0;
	$s=$sqlCons[1][101]." WHERE s_cresp.ID_RESP=:area LIMIT 1";
	$req = $dbEmpresa->prepare($s); 
	$req->bindParam(':area', $area);
	$req->execute();
	$k=0;
	$autocomplete=array();
	if($reg = $req->fetch()){
		$autocomplete["id"]=$reg["ID_RESP"];
		$autocomplete["value"]=imprimir($reg["NOMB_RESP"],2);
		$autocomplete["cont"]=array();	
		
		
		$autocomplete["cont"]["ciudaddesc"]=$reg["NOMB_CIUDAD"].' - '.$reg["COD_PAIS"];
		$autocomplete["cont"]["id_ciudad"]=$reg["ID_CIUDAD"];
		$autocomplete["cont"]["desc_ciudad"]=$reg["NOMB_CIUDAD"];
		$autocomplete["cont"]["desc_pais"]=$reg["NOMB_PAIS"];
		$autocomplete["cont"]["desc_depto"]=$reg["DISTRITO_CIUDAD"];
		$autocomplete["cont"]["lat_u"]=$reg["REF_LAT"];
		$autocomplete["cont"]["lon_u"]=$reg["REF_LON"];
		$autocomplete["cont"]["zoom"]=$reg["ZOOM_MAP"];
		$autocomplete["cont"]["abr"]=$reg["ABR_RESP"];
		$k++;		
	}
	echo json_encode($autocomplete);
}
elseif($tp==74||$tp==79){	
	$id_sha=nuevo_item();
	$c_sha=$tp==74?encrip(4,2):encrip(2,2);
	$md=$id_sha.$c_sha.$id_sha.'002';

    /*CREAR AUTO*/
    $k=0;
	$autocomplete=array();
	$autocomplete[$k]["id"]=0;
	$autocomplete[$k]["value"]='';	
	$autocomplete[$k]["cont"]=array();

	$i=0;	
	$autocomplete[$k]["cont"][$i]["label"]='-';
	$autocomplete[$k]["cont"][$i]["data"]["idlbl"]=0;


	$i++;
	$autocomplete[$k]["cont"][$i]["placeholder"]="";
	$autocomplete[$k]["cont"][$i]["tipo"]='text';
	$autocomplete[$k]["cont"][$i]["value"]='';
	$autocomplete[$k]["cont"][$i]["name"]="palabra[]";
	$autocomplete[$k]["cont"][$i]["data"]["name"]='etiqueta';
	$autocomplete[$k]["cont"][$i]["data"]["change"]='true';

	$i++;
	$autocomplete[$k]["cont"][$i]["placeholder"]="";
	$autocomplete[$k]["cont"][$i]["tipo"]='text';
	$autocomplete[$k]["cont"][$i]["value"]='';
	$autocomplete[$k]["cont"][$i]["name"]="tooltip[]";
	$autocomplete[$k]["cont"][$i]["data"]["name"]='titulo';
	$autocomplete[$k]["cont"][$i]["data"]["change"]='true';

	$i++;
	$autocomplete[$k]["cont"][$i]["placeholder"]="";
	$autocomplete[$k]["cont"][$i]["tipo"]='text';
	$autocomplete[$k]["cont"][$i]["value"]='';
	$autocomplete[$k]["cont"][$i]["name"]="tooltip[]";
	$autocomplete[$k]["cont"][$i]["data"]["name"]='texto';
	$autocomplete[$k]["cont"][$i]["data"]["change"]='true';

	$i++;
	$autocomplete[$k]["cont"][$i]["tipo"]='button';
	$autocomplete[$k]["cont"][$i]["value"]=0;
	$autocomplete[$k]["cont"][$i]["icon"]="fa-save";
	$autocomplete[$k]["cont"][$i]["name"]="Save[]";
	$autocomplete[$k]["cont"][$i]["data"]["md"]=$md;
	$autocomplete[$k]["cont"][$i]["data"]["idioma"]=$_REQUEST["idioma"];
	$autocomplete[$k]["cont"][$i]["data"]["enable"]='false';
	echo json_encode($autocomplete);
}
//NUEVA URL
elseif($tp==82){	
	$id_sha=nuevo_item();
	$c_sha=encrip(2,2);
	$md=$id_sha.$c_sha.$id_sha.'001';

    /*CREAR AUTO*/
    $k=0;
	$autocomplete=array();
	$autocomplete[$k]["id"]=0;
	$autocomplete[$k]["value"]='';	
	$autocomplete[$k]["cont"]=array();

	$i=0;	
	$autocomplete[$k]["cont"][$i]["placeholder"]="";
	$autocomplete[$k]["cont"][$i]["tipo"]='text';
	$autocomplete[$k]["cont"][$i]["value"]='';
	$autocomplete[$k]["cont"][$i]["name"]="url[]";
	$autocomplete[$k]["cont"][$i]["data"]["name"]='url';
	$autocomplete[$k]["cont"][$i]["data"]["change"]='true';

	$i++;
	$autocomplete[$k]["cont"][$i]["label"]='';
	$autocomplete[$k]["cont"][$i]["tipo"]='button';
	$autocomplete[$k]["cont"][$i]["value"]=0;
	$autocomplete[$k]["cont"][$i]["icon"]="fa-save";
	$autocomplete[$k]["cont"][$i]["name"]="Save[]";
	$autocomplete[$k]["cont"][$i]["data"]["md"]=$md;
	$autocomplete[$k]["cont"][$i]["data"]["enable"]='false';

	$i++;
	$autocomplete[$k]["cont"][$i]["label"]='';
	$autocomplete[$k]["cont"][$i]["tipo"]='button';
	$autocomplete[$k]["cont"][$i]["value"]=0;
	$autocomplete[$k]["cont"][$i]["icon"]="fa-trash";
	$autocomplete[$k]["cont"][$i]["name"]="Delete[]";
	$autocomplete[$k]["cont"][$i]["data"]["deltable"]=true;	

	$autocomplete[$k]["cont"][$i]["data"]["title"]='txt-1376-0'; 
	$autocomplete[$k]["cont"][$i]["data"]["msg"]='txt-1376-1'; 
	$autocomplete[$k]["cont"][$i]["data"]["confirm"]='txt-1025-0'; 
	$autocomplete[$k]["cont"][$i]["data"]["deltable"]=true;	
	$autocomplete[$k]["cont"][$i]["data"]["label"]='txt-1005-0';	
	$autocomplete[$k]["cont"][$i]["data"]["cancel"]='txt-1028-0';		
	echo json_encode($autocomplete);
}
elseif($tp==10001){
	


	$version_old=$_REQUEST["versions"];
	$version_new=array();
	$nueva=$_REQUEST["nueva"]==1;
	$send=array();
	$logged=$verificar?1:0;
	$logemp=$_CLIENTE==0?0:1;
	$s=$sqlCons[1][99].
			" WHERE adm_api_tablas.HAB=0 ".
			" AND adm_api_tablas.LOGGED IN (2,:logged)";
	$req = $dbEmpresa->prepare($s);
	$req->bindParam(':id_mempresa', $_CLIENTE);	
	$req->bindParam(':logged', $logged);

	$req->execute();
	while($reg = $req->fetch()){	

		$name=$reg["NAME_TABLA"];
		$version_new[$name]=$reg['VERSION'];
		$version_old[$name]=is_numeric($version_old[$name])?$version_old[$name]:-1;
		if($version_new[$name]>$version_old[$name]){			
			if(!isset($send[$name])) 	$send[$name]=array();				
			if($name=='x_textos'){			
				//TEXTOS GENERICOS DE PROYECTO			
				$s=$sqlCons[2][77];
				$reqPtabla = $dbEmpresa->prepare($s);
				$reqPtabla->bindParam(':empresa', $_CLIENTE);
				$reqPtabla->bindParam(':grupo', $_GCLIENTE);						
				$reqPtabla->bindParam(':idioma', $_IDIOMA);
				$reqPtabla->execute();					
				while($regPtabla = $reqPtabla->fetch()){
					$kk=$regPtabla["ID_PALABRA"];
					if(!is_null($regPtabla["PALABRA"])) $send[$name]["txt-$kk-0"]=$regPtabla["PALABRA"];
					if(!is_null($regPtabla["TOOLTIP"])) $send[$name]["txt-$kk-1"]=$regPtabla["TOOLTIP"];
				}			

				//TEXTOS SIIE
				include "phplib/mysql_valores.php";	
				$s=$sqlCons[4][77];
				$reqPtabla = $dbMat->prepare($s);					
				$reqPtabla->bindParam(':idioma', $_IDIOMA);
				$reqPtabla->execute();	
				while($regPtabla = $reqPtabla->fetch()){
					$kk=$regPtabla["ID_PALABRA"];
					if(!is_null($regPtabla["PALABRA"])) $send[$name]["txt-$kk-0"]=$regPtabla["PALABRA"];
					if(!is_null($regPtabla["TOOLTIP"])) $send[$name]["txt-$kk-1"]=$regPtabla["TOOLTIP"];
				}
				
				//DE LA EMPRESA CLIENTE
				$s=$sqlCons[3][77];
				$reqPtabla = $dbEmpresa->prepare($s);
				$reqPtabla->bindParam(':empresa', $_CLIENTE);						
				$reqPtabla->bindParam(':idioma', $_IDIOMA);
				$reqPtabla->execute();	
				while($regPtabla = $reqPtabla->fetch()){
					$kk=$regPtabla["ID_PALABRA"];
					if(!is_null($regPtabla["PALABRA"])) $send[$name]["txt-$kk-0"]=$regPtabla["PALABRA"];
					if(!is_null($regPtabla["TOOLTIP"])) $send[$name]["txt-$kk-1"]=$regPtabla["TOOLTIP"];
				}			

				//LANDING PAGES
				$s=$sqlCons[1][75]." WHERE adm_landing.ID_IDIOMA=:idioma";
				$reqLanding = $dbEmpresa->prepare($s);
				$reqLanding->bindParam(':idioma', $_IDIOMA);
				$reqLanding->execute();
				$landing=array();
				while($regLanding = $reqLanding->fetch()){
					$kk=$regLanding ["ETI_DIV"].$regLanding ["ID_LAND"];
					$send[$name]["txt-$kk-0"]=$regLanding ["TITULO"];
					$send[$name]["txt-$kk-1"]=$regLanding ["TEXTO"];
				}
				//LANDING PAGES SOLO COMPAÑIAS
				$s=$sqlCons[2][75]." WHERE adm_empresas_landing.ID_IDIOMA=:idioma AND adm_empresas_landing.ID_MEMPRESA=:empresa ";
				$reqLanding = $dbEmpresa->prepare($s);
				$reqLanding->bindParam(':idioma', $_IDIOMA);
				$reqLanding->bindParam(':empresa', $_CLIENTE);
				$reqLanding->execute();
				$landing=array();
				while($regLanding = $reqLanding->fetch()){
					$kk=$regLanding ["ETI_DIV"].$regLanding ["ID_LAND"];
					$send[$name]["txt-$kk-0"]=$regLanding ["TITULO"];
					$send[$name]["txt-$kk-1"]=$regLanding ["TEXTO"];
				}

				//MENSAJES					
				$s="SELECT
				fac_mensajes.ID_MENSAJE,
				fac_mensajes.ID_IDIOMA,
				fac_mensajes.MENSAJE,
				fac_mensajes.DIV_MENSAJE,
				fac_mensajes.DIV_ICONO
				FROM fac_mensajes
				WHERE ID_IDIOMA=:idioma";	
				$reqMsg = $dbMat->prepare($s);	 
				$reqMsg->bindParam(':idioma', $_IDIOMA);		
				$reqMsg->execute();
				while($regMsg = $reqMsg->fetch()){
					$kk='MSJ'.$regMsg ["ID_MENSAJE"];	
					$send[$name]["txt-$kk-0"]=$regMsg ["MENSAJE"];
				}	
			}	
			else{
				$FFlag=true;

				
				$_x1=$reg["CONSULTA_X1"];
				$_x2=$reg["CONSULTA_X2"];
				$c_empresa=$reg["MEMPRESA"];
				$c_tempresa=$reg["TEMPRESA"];
				$c_idioma=$reg["IDIOMA"];	
				$c_user=$reg["USUARIO"];
				$c_module=$reg["VENTANA"];
				$c_mode=$reg["MODO"];
				if($c_module!=0)
					$FFlag=GruposAPI($dbEmpresa,array($c_module),$_sysvars_r,$_sysvars);
				
				if($FFlag){
					$s=$sqlCons[$_x1][$_x2].' '.$sqlOrder[$_x1][$_x2];
					$reqPtabla = $dbEmpresa->prepare($s);
					if($c_empresa==1)	$reqPtabla->bindParam(':empresa', $id_mempresa);
					if($c_tempresa==1)	$reqPtabla->bindParam(':grupo', $id_gempresa);
					if($c_idioma==1)	$reqPtabla->bindParam(':idioma', $Idioma);
					if($c_user==1)		$reqPtabla->bindParam(':usuario', $_USUARIO);
					if($c_mode==1)		$reqPtabla->bindParam(':user_group', $_GRUPO);
					$reqPtabla->execute();

					$kk=0;
					while($regPtabla = $reqPtabla->fetch()){					
						foreach($regPtabla as $name_data => $valor_data){
							if(!is_numeric($name_data)) $send[$name][$kk][$name_data]=$valor_data;
						}
						$kk++;
					}
				}		
			}	
		}	
	}
	$salidas = array(
			'tables' => $send
		,	'versions_new' => $version_new
	);
	echo json_encode($salidas);	
}
else{
	if($_PROYECTO==1)			include("autocomplete_001.php");
	elseif($_PROYECTO==8)		include("autocomplete_008.php");
	elseif($_PROYECTO==10)		include("autocomplete_010.php");
	elseif($_PROYECTO==11)		include("autocomplete_011.php");
	elseif($_PROYECTO==13)		include("autocomplete_013.php");
	elseif($_PROYECTO==14)		include("autocomplete_014.php");
	elseif($_PROYECTO==15)		include("autocomplete_015.php");
	elseif($_PROYECTO==16)		include("autocomplete_016.php");
	elseif($_PROYECTO==20)		include("autocomplete_020.php");
	elseif($_PROYECTO==22)		include("autocomplete_022.php");
	elseif($_PROYECTO==23)		include("autocomplete_023.php");
	elseif($_PROYECTO==24)		include("autocomplete_024.php");
	elseif($_PROYECTO==26)		include("autocomplete_026.php");
	elseif($_PROYECTO==27)		include("autocomplete_027.php");
	elseif($_PROYECTO==28)		include("autocomplete_028.php");
	elseif($_PROYECTO==29)		include("autocomplete_029.php");
	elseif($_PROYECTO==32)		include("autocomplete_032.php");
}
?>
<?php

$cnf=isset($_REQUEST["cnf"])?intval($_REQUEST["cnf"]):1;
$t=isset($_REQUEST["fl-t"])?$_REQUEST["fl-t"]:2;
$PagActual=isset($_REQUEST["p"])?$_REQUEST["p"]:1;

//DATOS ESPEFICICOS
$scnf=isset($_REQUEST["scnf"])?$_REQUEST["scnf"]:1;
$id_sha=isset($_REQUEST["id"])?$_REQUEST["id"]:0;
$tp=isset($_REQUEST["tp"])?$_REQUEST["tp"]:0;
///////////////////////////////////////////////
$busc=isset($_REQUEST["busc"])?imprimir($_REQUEST["busc"]):"";
$busc_query='%'.$_REQUEST["busc"].'%';
$busc_send=isset($_REQUEST["busc"])?$_REQUEST["busc"]:"";
///////////////////////////////////////////////

$permiso=$PermisosA[$cnf]["P"]==1;

//
$c_sha=encrip($cnf,2);
$nuevo_tag=nuevo_item().$c_sha;
$nuevo_tag2=nuevo_item().$c_sha.nuevo_item();
///////////////////////////////////////////////

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
$MaxItemsNew=$NewMaxItems[$idMaxItem];
$cargado=false;

if($cnf==19){
	if($t==2)		$sWhere=" WHERE s_cresp.HAB_RESP=0 ";
	elseif($t==3)	$sWhere=" WHERE s_cresp.HAB_RESP=1 ";
	
	if($id_sha!=""){
		$sWhere_Este=encrip_mysql('s_cresp.ID_RESP');
		$sWhere.=" AND $sWhere_Este<>:id ";
	}	
	$sWhere.=sWhere_cons(8,$busc); 		
	if($PermisosA[8]["P"]!=1) 	$sWhere.=" AND s_cresp.ID_RESP IN (SELECT s_cresp_grupo.ID_RESP FROM s_cresp_grupo WHERE s_cresp_grupo.ID_GRUPO=$_GRUPO) ";
	

	$s=$sqlCons[1][101].$sWhere;

	$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";
	$req = $dbEmpresa->prepare($s); 
	if($_REQUEST["busc"]!='') $req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
	if($id_sha!='') $req->bindParam(':id', $id_sha);
	$req->execute(); 
	$Total = $req->fetchColumn();
	$IniDato=($PagActual-1)*$MaxItems;
		
	/*TABLA*/
	$s=$sqlCons[1][101].$sWhere.$sqlOrder[1][101]." LIMIT $IniDato,$MaxItems";
	$i=0;	
	$req = $dbEmpresa->prepare($s); 
	if($_REQUEST["busc"]!='') $req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
	if($id_sha!='') $req->bindParam(':id', $id_sha);
	$req->execute();
	while($reg = $req->fetch()){	
		$salidas["nItem"][$i]=array();	
		json_Item($reg,$salidas["nItem"][$i],$cnf,$md,$_sysvars_r);		$i++;	
	}
	$cargado=true;
	$salidas["barra"]=array();
		
	$k=0;
	$salidas["barra"][$k]=array();
	$salidas["barra"][$k]["agrupar"]=0;
	$salidas["barra"][$k]["id"]="sBarra-".$k."-".$cnf;
	$salidas["barra"][$k]["contenido"]=array();
	
	$i=0;
	$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
	$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_nuevo;
	$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$nuevo_tag;
	$salidas["barra"][$k]["contenido"][$i]["pagina"]='/edit';
	
	$k++;
	$salidas["barra"][$k]=array();
	$salidas["barra"][$k]["agrupar"]=1;
	$salidas["barra"][$k]["id"]="sBarra-".$k."-".$cnf;
	$salidas["barra"][$k]["contenido"]=array();
	
	$i=0;
	$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
	$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_habdes_activos;	
	
	$i++;	
	$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
	$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_habdes_inactivos;
      
	print_paginacion($salidas,$Total,$PagActual,$idMaxItem);	
}
elseif($cnf==36){
	$permiso_amd=$PermisosA[8]["P"];
	$permiso_ven=$PermisosA[45]["P"];
	
	/*REPUESTOS*/
	$sWhere=" WHERE ";
	if($t==2)		$sWhere.=" adm_usuarios.HAB_U=0 ";
	elseif($t==3)	$sWhere.=" adm_usuarios.HAB_U=1 ";
	elseif($t==4){	$sWhere.=" (SELECT adm_grupos_ven_pq.PERMISO_GRUPOVEN 
								FROM adm_grupos_ven adm_grupos_ven_pq
								WHERE adm_grupos_ven_pq.ID_GRUPO=adm_usuarios.ID_GRUPO AND adm_grupos_ven_pq.ID_VENTANA=45)=1 AND adm_usuarios.HAB_U=0  ";
					$btn_habdes_vendedor['chk']=true;
					}
	elseif($t==5){	$sWhere.=" adm_usuarios.ID_USUARIO IN (SELECT adm_usuarios_clientes.ID_USUARIO_CLI 
								FROM adm_usuarios_clientes 
								WHERE adm_usuarios_clientes.ID_USUARIO=".$_USUARIO.") AND adm_usuarios.HAB_U=0  ";
					$btn_habdes_mclientes['chk']=true;
					}
	if($id_sha!=""){
		$sWhere_Este=encrip_mysql('adm_usuarios.ID_USUARIO');
		$sWhere.=" AND $sWhere_Este<>:id ";
	}
		
	$sWhere.=sWhere_cons(11,$busc);

	$s=$sqlCons[1][0].$sWhere;
	$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";;
	$req = $dbEmpresa->prepare($s); 
	if($_REQUEST["busc"]!='') $req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
	if($id_sha!='') $req->bindParam(':id', $id_sha);
	$req->execute(); 
	$Total = $req->fetchColumn();	
	$IniDato=($PagActual-1)*$MaxItems;
	
	/*TABLA*/
	$s=$sqlCons[1][0].$sWhere.$sqlOrder[1][0]." LIMIT $IniDato,$MaxItems";


	$i=0;
	$req = $dbEmpresa->prepare($s); 
	if($_REQUEST["busc"]!='') $req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
	if($id_sha!='') $req->bindParam(':id', $id_sha);
	$req->execute();   
	while($reg = $req->fetch()){	
		$salidas["nItem"][$i]=array();	
		json_Item($reg,$salidas["nItem"][$i],$cnf,$md,$_sysvars_r);		$i++;	
	}
	$cargado=true;
	$salidas["barra"]=array();
		
	$k=0;
	$salidas["barra"][$k]=array();
	$salidas["barra"][$k]["agrupar"]=0;
	$salidas["barra"][$k]["id"]="sBarra-".$k."-".$cnf;
	$salidas["barra"][$k]["contenido"]=array();

	$i=0;	
	$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
	$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_nuevo;
	$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".nuevo_item().encrip(36,2);
	$salidas["barra"][$k]["contenido"][$i]["pagina"]='/edit';
	
	$k++;
	$salidas["barra"][$k]=array();
	$salidas["barra"][$k]["agrupar"]=1;
	$salidas["barra"][$k]["id"]="sBarra-".$k."-".$cnf;
	$salidas["barra"][$k]["contenido"]=array();
	
	$i=0;
	$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
	$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_habdes_activos;	
	
	$i++;	
	$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
	$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_habdes_inactivos;
	
	print_paginacion($salidas,$Total,$PagActual,$idMaxItem);	
}

elseif($cnf==8){
	if($scnf==1){
		
		if(($PermisosA[10000]["P"]==1)) 
					$sWhere=" WHERE adm_grupos.ADM_GRUPO IN (0,1,2,3) ";
		else 		$sWhere=" WHERE adm_grupos.ADM_GRUPO IN (0,1) ";
		if($t==2)		$sWhere.=" AND adm_grupos.HAB_GRUPO=0 ";
		elseif($t==3)	$sWhere.=" AND adm_grupos.HAB_GRUPO=1 ";
		
		$sWhere.=sWhere_cons(74,$busc);
		if($id_sha!=""){
			$sWhere_Este=encrip_mysql('adm_grupos.ID_GRUPO');
			$sWhere.=" AND $sWhere_Este<>:id ";
		}
					
		$s=$sqlCons[1][64]." $sWhere ".$sqlOrder[1][64];
		$req = $dbEmpresa->prepare($s); 
		if($_REQUEST["busc"]!='') $req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		if($id_sha!='') $req->bindParam(':id', $id_sha);
		$req->execute();		
		$i=0;	
		while($reg = $req->fetch()){		
			$salidas["nItem"][$i]=array();	
			json_Item($reg,$salidas["nItem"][$i],$cnf,$md,$_sysvars_r);			$i++;	
		}
		$cargado=true;
		$salidas["barra"]=array();
				
		$k=0;
		$salidas["barra"][$k]=array();
		$salidas["barra"][$k]["agrupar"]=0;
		$salidas["barra"][$k]["id"]="sBarra-".$k."-".$cnf;
		$salidas["barra"][$k]["contenido"]=array();
		
		$i=0;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_nuevo;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$nuevo_tag;
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/edit';

		$i++;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;		
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_vusuario;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".nuevo_item().encrip(36,2);
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/edit';
		
		$k++;
		$salidas["barra"][$k]=array();
		$salidas["barra"][$k]["agrupar"]=1;
		$salidas["barra"][$k]["id"]="sBarra-".$k."-".$cnf;
		$salidas["barra"][$k]["contenido"]=array();
		
		$i=0;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_habdes_activos;
		
		$i++;	
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_habdes_inactivos;
		
		
		print_paginacion($salidas,$Total,$PagActual,$idMaxItem);
	}
	elseif($scnf==2){
		$i=0;	
		$sWhereD=encrip_mysql('adm_grupos_ven.ID_GRUPO');
		$sWhere=" WHERE $sWhereD=:id ";
		if($_PROYECTO==1)	$sWhere.=sWhere_cons(75,$busc);
		else					$sWhere.=sWhere_cons(76,$busc);
	
		$s=$sqlCons[1][66]." WHERE $sWhere ";	
		$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";		
		$req = $dbEmpresa->prepare($s); 
		if($_REQUEST["busc"]!='') $req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->bindParam(':id', $id_sha);
		$req->execute();
		$Total = $req->fetchColumn();
		$IniDato=($PagActual-1)*$MaxItems;
			
		$s=$sqlCons[1][66]." $sWhere ".$sqlOrder[1][66]." LIMIT $IniDato,$MaxItems";
		$req = $dbEmpresa->prepare($s); 
		if($_REQUEST["busc"]!='') $req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->bindParam(':id', $id_sha);
		$req->execute();		
		$i=0;	
		while($reg = $req->fetch()){
			$salidas["nItem"][$i]=array();	
			json_Item($reg,$salidas["nItem"][$i],$cnf,$md,$_sysvars_r);			$i++;	
		}
		print_paginacion($salidas,$Total,$PagActual,$idMaxItem);
		$cargado=true;
		
	}
}	
elseif($cnf==80){	
	$sWhere=" AND adm_informes_detalle.HAB_INFORME=0 AND ".
		"adm_informes_detalle.TIPO_INFORME<>1 AND ".
		"adm_informes_grupo.HAB_GINFORME=0 AND ".
		"adm_informes_detalle.ID_GINFORME=:idg AND ".
		"NOT (ISNULL(adm_informes_desc.NOMB_INFORME) AND ISNULL(adm_empresas_v_informes_desc.NOMB_INFORME)) ";
	if($id_sha!=""){
		$sWhere_Este=encrip_mysql('adm_informes_detalle.ID_INFORME');
		$sWhere.=" AND $sWhere_Este<>:id ";
	}		
	$s=$sqlCons[1][73].$sWhere; 
	$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";	
	$req = $dbEmpresa->prepare($s); 
	if($id_sha!='') $req->bindParam(':id', $id_sha);
	$req->bindParam(':idg', $scnf);
	$req->execute(); 
	$Total = $req->fetchColumn();
	$IniDato=($PagActual-1)*$MaxItems;
	
	/*TABLA*/
	$s=$sqlCons[1][73].$sWhere.$sqlOrder[1][73]." LIMIT $IniDato,$MaxItems";
	
	$i=0;	
	$req = $dbEmpresa->prepare($s); 
	if($id_sha!='') $req->bindParam(':id', $id_sha);
	$req->bindParam(':idg', $scnf);
	$req->execute();
		
	while($reg = $req->fetch()){
		$salidas["nItem"][$i]=array();	
		json_Item($reg,$salidas["nItem"][$i],$cnf,$md,$_sysvars_r);		$i++;	
	} 
	print_paginacion($salidas,$Total,$PagActual,$idMaxItem);
	$cargado=true;
}
elseif($cnf==10003){ //CLASES
	if($scnf==1){	
		if($t==2)		$sWhere=" WHERE adm_empresas_btipo.HAB_GRUPOPAL=0 ";
		elseif($t==3)	$sWhere=" WHERE adm_empresas_btipo.HAB_GRUPOPAL=1 ";
		
				
		if($id_sha!=""){
			$sWhere_Este=encrip_mysql('adm_empresas_btipo.TIPO_GRUPOPAL');
			$sWhere.=" AND $sWhere_Este<>:id ";
		}
		$sWhere.=sWhere_cons(100,$busc);	
		$s=$sqlCons[1][85].$sWhere; 
		$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";
		$req = $dbEmpresa->prepare($s); 
		if($_REQUEST["busc"]!='') $req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		if($id_sha!='') $req->bindParam(':id', $id_sha);
		$req->execute(); 
		$Total = $req->fetchColumn();
		$IniDato=($PagActual-1)*$MaxItems;
		
		/*TABLA*/
		$s=$sqlCons[1][85].$sWhere.$sqlOrder[1][85]." LIMIT $IniDato,$MaxItems";
		
		$i=0;	
		$req = $dbEmpresa->prepare($s); 
		if($_REQUEST["busc"]!='') $req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		if($id_sha!='') $req->bindParam(':id', $id_sha);
		$req->execute();
			
		while($reg = $req->fetch()){
			$salidas["nItem"][$i]=array();	
			json_Item($reg,$salidas["nItem"][$i],$cnf,$md,$_sysvars_r);			$i++;	
		}
	
		$cargado=true;
		$salidas["barra"]=array();
			
		$k=0;
		$salidas["barra"][$k]=array();
		$salidas["barra"][$k]["agrupar"]=0;
		$salidas["barra"][$k]["id"]="sBarra-".$k."-".$cnf;
		$salidas["barra"][$k]["contenido"]=array();
		
		$i=0;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_nuevo;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$nuevo_tag;
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/edit';
		
		$k++;
		$salidas["barra"][$k]=array();
		$salidas["barra"][$k]["agrupar"]=1;
		$salidas["barra"][$k]["id"]="sBarra-".$k."-".$cnf;
		$salidas["barra"][$k]["contenido"]=array();
		
		$i=0;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_habdes_activos;
		
		$i++;	
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_habdes_inactivos;				
		  
		print_paginacion($salidas,$Total,$PagActual,$idMaxItem);	
	}
}
elseif($cnf==10002){ //TIPOS
	if($scnf==1){
		if($t==2)		$sWhere=" WHERE adm_empresas_tipo.HAB_TIPOE=0 ";
		elseif($t==3)	$sWhere=" WHERE adm_empresas_tipo.HAB_TIPOE=1 ";
		
				
		if($id_sha!=""){
			$sWhere_Este=encrip_mysql('adm_empresas_tipo.ID_TIPOE');
			$sWhere.=" AND $sWhere_Este<>:id ";
		}
		$sWhere.=sWhere_cons(101,$busc);	
		$s=$sqlCons[1][72].$sWhere; 
		$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";
		$req = $dbEmpresa->prepare($s); 
		if($_REQUEST["busc"]!='') $req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		if($id_sha!='') $req->bindParam(':id', $id_sha);
		$req->execute(); 
		$Total = $req->fetchColumn();
		$IniDato=($PagActual-1)*$MaxItems;
		
		/*TABLA*/
		$s=$sqlCons[1][72].$sWhere.$sqlOrder[1][72]." LIMIT $IniDato,$MaxItems";
		
		$i=0;	
		$req = $dbEmpresa->prepare($s); 
		if($_REQUEST["busc"]!='') $req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		if($id_sha!='') $req->bindParam(':id', $id_sha);
		$req->execute();
			
		while($reg = $req->fetch()){
			$salidas["nItem"][$i]=array();	
			json_Item($reg,$salidas["nItem"][$i],$cnf,$md,$_sysvars_r);			$i++;	
		}
	
		$cargado=true;
		$salidas["barra"]=array();
			
		$k=0;
		$salidas["barra"][$k]=array();
		$salidas["barra"][$k]["agrupar"]=0;
		$salidas["barra"][$k]["id"]="sBarra-".$k."-".$cnf;
		$salidas["barra"][$k]["contenido"]=array();
		
		$i=0;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_nuevo;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$nuevo_tag;
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/edit';
		
		$k++;
		$salidas["barra"][$k]=array();
		$salidas["barra"][$k]["agrupar"]=1;
		$salidas["barra"][$k]["id"]="sBarra-".$k."-".$cnf;
		$salidas["barra"][$k]["contenido"]=array();
		
		$i=0;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_habdes_activos;
		
		$i++;	
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_habdes_inactivos;				
		  
		print_paginacion($salidas,$Total,$PagActual,$idMaxItem);	
	}
}
elseif($cnf==10004){ //EMPRESAS CLIENTES
	if($scnf==1){	
		if($t==2)		$sWhere=" WHERE adm_empresas.HAB_MEMPRESA=0 ";
		elseif($t==3)	$sWhere=" WHERE adm_empresas.HAB_MEMPRESA=1 ";
		
				
		if($id_sha!=""){
			$sWhere_Este=encrip_mysql('adm_empresas.ID_MEMPRESA');
			$sWhere.=" AND $sWhere_Este<>:id ";
		}
		$sWhere.=sWhere_cons(100,$busc);	
		$s=$sqlCons[1][81].$sWhere; 
		$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";
		$req = $dbEmpresa->prepare($s); 
		if($_REQUEST["busc"]!='') $req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->bindParam(':idioma', $_IDIOMA);
		if($id_sha!='') $req->bindParam(':id', $id_sha);
		$req->execute(); 
		$Total = $req->fetchColumn();
		$IniDato=($PagActual-1)*$MaxItems;
		
		/*TABLA*/
		$s=$sqlCons[1][81].$sWhere.$sqlOrder[1][81]." LIMIT $IniDato,$MaxItems";
		
		$i=0;	
		$req = $dbEmpresa->prepare($s); 
		if($_REQUEST["busc"]!='') $req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		$req->bindParam(':idioma', $_IDIOMA);
		if($id_sha!='') $req->bindParam(':id', $id_sha);
		$req->execute();
			
		while($reg = $req->fetch()){
			$salidas["nItem"][$i]=array();	
			json_Item($reg,$salidas["nItem"][$i],$cnf,$md,$_sysvars_r);			$i++;	
		}
	
		$cargado=true;
		$salidas["barra"]=array();
			
		$k=0;
		$salidas["barra"][$k]=array();
		$salidas["barra"][$k]["agrupar"]=0;
		$salidas["barra"][$k]["id"]="sBarra-".$k."-".$cnf;
		$salidas["barra"][$k]["contenido"]=array();
		
		$i=0;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_nuevo;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$nuevo_tag;
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/edit';
		
		$k++;
		$salidas["barra"][$k]=array();
		$salidas["barra"][$k]["agrupar"]=1;
		$salidas["barra"][$k]["id"]="sBarra-".$k."-".$cnf;
		$salidas["barra"][$k]["contenido"]=array();
		
		$i=0;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_habdes_activos;
		
		$i++;	
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_habdes_inactivos;				
		  
		print_paginacion($salidas,$Total,$PagActual,$idMaxItem);	
	}
}	
elseif($cnf==10000){ //USUARIOS ADMINS
	if($scnf==1){	
		$sWhere=" WHERE adm_usuarios.ID_USUARIO IN (SELECT adm_usuarios_empresa.ID_USUARIO FROM adm_usuarios_empresa) ";
		if($t==2)		$sWhere.=" AND adm_usuarios.HAB_U=0 ";
		elseif($t==3)	$sWhere.=" AND adm_usuarios.HAB_U=1 ";
	
		$sWhere.=sWhere_cons(11,$busc);	
		$s=$sqlCons[1][0].$sWhere; 
		$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";
		$req = $dbEmpresa->prepare($s); 
		if($_REQUEST["busc"]!='') $req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		if($id_sha!='') $req->bindParam(':id', $id_sha);
		$req->execute(); 
		$Total = $req->fetchColumn();
		$IniDato=($PagActual-1)*$MaxItems;
		
		/*TABLA*/
		$s=$sqlCons[1][0].$sWhere.$sqlOrder[1][0]." LIMIT $IniDato,$MaxItems";
		
		$i=0;	
		$req = $dbEmpresa->prepare($s); 
		if($_REQUEST["busc"]!='') $req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
		if($id_sha!='') $req->bindParam(':id', $id_sha);
		$req->execute();
			
		while($reg = $req->fetch()){
			$salidas["nItem"][$i]=array();	
			json_Item($reg,$salidas["nItem"][$i],$cnf,$md,$_sysvars_r);			$i++;	
		}
	
		$cargado=true;
		$salidas["barra"]=array();
			
		$k=0;
		$salidas["barra"][$k]=array();
		$salidas["barra"][$k]["agrupar"]=0;
		$salidas["barra"][$k]["id"]="sBarra-".$k."-".$cnf;
		$salidas["barra"][$k]["contenido"]=array();
		
		$i=0;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_nuevo;
		$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".nuevo_item().encrip(36,2);
		$salidas["barra"][$k]["contenido"][$i]["pagina"]='/edit';
		
		$k++;
		$salidas["barra"][$k]=array();
		$salidas["barra"][$k]["agrupar"]=1;
		$salidas["barra"][$k]["id"]="sBarra-".$k."-".$cnf;
		$salidas["barra"][$k]["contenido"]=array();
		
		$i=0;
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_habdes_activos;
		
		$i++;	
		$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
		$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_habdes_inactivos;				
		  
		print_paginacion($salidas,$Total,$PagActual,$idMaxItem);	
	}
}
else{
	if($_PROYECTO==1)			include("baseinc_001.php"); //ROCKETMP	
	elseif($_PROYECTO==8)		include("baseinc_008.php"); //FALCONCRM
	elseif($_PROYECTO==10)		include("baseinc_010.php"); //TUPYME
	elseif($_PROYECTO==11)		include("baseinc_011.php"); //ALESTRA GEO
	elseif($_PROYECTO==13)		include("baseinc_013.php"); //CIUDAD TRAVEL
	elseif($_PROYECTO==14)		include("baseinc_014.php"); //EVENTOS CCB
	elseif($_PROYECTO==15)		include("baseinc_015.php"); //PONTIFICIA
	elseif($_PROYECTO==16)		include("baseinc_016.php"); //Disponibles
	elseif($_PROYECTO==18)		include("baseinc_018.php"); //Mensajero
	elseif($_PROYECTO==19)		include("baseinc_019.php"); //SCA
	elseif($_PROYECTO==20)		include("baseinc_020.php"); //APPETITOS
	elseif($_PROYECTO==21)		include("baseinc_021.php"); //INNOVACION
	elseif($_PROYECTO==22)		include("baseinc_022.php"); //ROCKETMP
	elseif($_PROYECTO==23)		include("baseinc_023.php"); //VIGA
	elseif($_PROYECTO==24)		include("baseinc_024.php"); //Marca GPS
	elseif($_PROYECTO==25)		include("baseinc_025.php"); //Esteban Rios
	elseif($_PROYECTO==26)		include("baseinc_026.php"); //Mis Veterinarias
	elseif($_PROYECTO==27)		include("baseinc_027.php"); //Cancheros
	elseif($_PROYECTO==28)		include("baseinc_028.php"); //PetroZones
	elseif($_PROYECTO==29)		include("baseinc_029.php"); //Asking Room
	elseif($_PROYECTO==31)		include("baseinc_031.php"); //Qr Print
	elseif($_PROYECTO==32)		include("baseinc_032.php"); //Super Maestros
	elseif($_PROYECTO==33)		include("baseinc_033.php"); //Futuroscopio
	elseif($_PROYECTO==34)		include("baseinc_034.php"); //Eureka
	elseif($_PROYECTO==35)		include("baseinc_035.php"); //Créditos
	elseif($_PROYECTO==36)		include("baseinc_036.php"); //Gappsolina
	elseif($_PROYECTO==37)		include("baseinc_037.php"); //Eureka Movil
	elseif($_PROYECTO==38)		include("baseinc_038.php"); //A ser visto
	elseif($_PROYECTO==39)		include("baseinc_039.php"); //Cajasan
	elseif($_PROYECTO==40)		include("baseinc_040.php"); //Alkilautos
	elseif($_PROYECTO==41)		include("baseinc_041.php"); //7points
	elseif($_PROYECTO==42)		include("baseinc_042.php"); //Infoeventos
	elseif($_PROYECTO==43)		include("baseinc_043.php"); //TeloEntrego
	elseif($_PROYECTO==44)		include("baseinc_044.php"); //La TienditApp
	elseif($_PROYECTO==45)		include("baseinc_045.php"); //LicorTap
}


if(!$cargado){	
	$output=array();
	$c_campo=encrip_mysql("adm_ventanas_cont.ID_VENTANA",2);
	$sTitulos=$sqlCons[1][1]." WHERE $c_campo=:c_sha AND adm_ventanas_cont.HAB_VENTANA=0 ".$sqlOrder[1][1];
	$reqTitulos = $dbEmpresa->prepare($sTitulos);
	$reqTitulos->bindParam(':c_sha', $c_sha);
	$reqTitulos->execute();	
	CreaConsulta($c_sha,$reqTitulos,$output,$sArmado);
	$cnf=$output["scons"]["cnf"];
	$hab_campo=$output["scons"]["hab_campo"];
	$ord_campo=$output["scons"]["ord_campo"];	
	
	if($output["scons"]["mempresa"]==1){
		if($t==2)		$sWhere=" WHERE $hab_campo=0 AND ID_MEMPRESA=$_CLIENTE ";
		elseif($t==3)	$sWhere=" WHERE $hab_campo=1 AND ID_MEMPRESA=$_CLIENTE ";
	}
	else{
		if($t==2)		$sWhere=" WHERE $hab_campo=0 ";
		elseif($t==3)	$sWhere=" WHERE $hab_campo=1 ";
	}
	
	if($_REQUEST["busc"]!=''){
		$sPre=array();
		foreach ($output["cols"]["Cols"] as $i => $nCol){ $sPre[]=$nCol." LIKE :Buscar"; }
		$sPreIN=implode(" OR ",$sPre);		
		$sWhere.="  AND ($sPreIN) ";
	}
	
	$s=$sArmado.' '.$sWhere;	
	$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";		
	$req = $dbEmpresa->prepare($s); 
	if($_REQUEST["busc"]!='') $req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
	$req->execute();
	$Total = $req->fetchColumn();
	$IniDato=($PagActual-1)*$MaxItems;
		
	$s=$sArmado." $sWhere ORDER BY ".$ord_campo." LIMIT $IniDato,$MaxItems";
	$req = $dbEmpresa->prepare($s); 
	if($_REQUEST["busc"]!='') $req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
	$req->execute();		
	$i=0;	
	while($reg = $req->fetch()){		
		$salidas["nItem"][$i]=array();	
		json_Item($reg,$salidas["nItem"][$i],$cnf,$md,$_sysvars_r,$output);
		$i++;	
	}
	$cargado=true;
	$salidas["barra"]=array();
			
	$k=0;
	$salidas["barra"][$k]=array();
	$salidas["barra"][$k]["agrupar"]=0;
	$salidas["barra"][$k]["id"]="sBarra-".$k."-".$cnf;
	$salidas["barra"][$k]["contenido"]=array();
	
	$i=0;
	$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
	$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_nuevo;
	$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$nuevo_tag;
	$salidas["barra"][$k]["contenido"][$i]["pagina"]='/setconfig';
	
	$k++;
	$salidas["barra"][$k]=array();
	$salidas["barra"][$k]["agrupar"]=1;
	$salidas["barra"][$k]["contenido"]=array();
	
	$i=0;
	$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
	$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_habdes_activos;
	
	$i++;	
	$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
	$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_habdes_inactivos;
	print_paginacion($salidas,$Total,$PagActual,$idMaxItem);
	
}
echo json_encode($salidas);
?>
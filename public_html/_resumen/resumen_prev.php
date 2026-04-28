<?php

$md=isset($_REQUEST["md"])?$_REQUEST["md"]:'';
$c_sha=mb_substr($md,40);
$id_sha=mb_substr($md,0,-32);
$cnf=isset($_REQUEST["cnf"])?$_REQUEST["cnf"]:0;

$permiso=$PermisosA[$cnf]["P"]==1;

if(!$permiso){
	$salidas["NPERM"]=true;
	echo json_encode($salidas);	
	exit(0);
}
$salidas=array();
if(($cnf==36)||($cnf==8)||($cnf==19)){
	if($cnf==36){
		$sWhere=encrip_mysql('adm_usuarios.ID_USUARIO');
		$s=$sqlCons[1][0]." WHERE $sWhere=:id LIMIT 1";
	}
	elseif($cnf==8){
		$sWhere=encrip_mysql('adm_grupos.ID_GRUPO');
		$s=$sqlCons[1][64]." WHERE $sWhere=:id LIMIT 1";
	}
	elseif($cnf==19){
		$sWhere=encrip_mysql('s_cresp.ID_RESP');
		$s=$sqlCons[1][101]." WHERE $sWhere=:id LIMIT 1 ";		
	}

	$req = $dbEmpresa->prepare($s); 
	$req->bindParam(':id', $id_sha);
	$req->execute();	
	if(!$reg = $req->fetch()){
		$salidas["ERR"]=true;
		echo json_encode($salidas);
		exit(0);
	}

	$k=0;
	if($cnf==8){		
		$salidas["menu"][$k]["label"]="txt-1110-0"; //USUARIOS
		$salidas["menu"][$k]["tinfo"]=51;	
		$salidas["menu"][$k]["default"]=1;	
		$k++;

		$salidas["menu"][$k]["label"]="txt-1113-0"; //AREA OPERACIONAL
		$salidas["menu"][$k]["tinfo"]=52;
		$k++;
	}

	if(($cnf==8)||($cnf==36)){
		$salidas["menu"][$k]["label"]="txt-1377-0"; //REGISTRO DE INGRESOS
		$salidas["menu"][$k]["tinfo"]=50;		
		$k++;
	}

	


	if($_PROYECTO==1){	
		$salidas["menu"][$k]["label"]="txt-496-0"; //REGISTROS
		$salidas["menu"][$k]["defsubmenu"]=0;
		$salidas["menu"][$k]["tinfo"]=0;			
		$salidas["menu"][$k]["submenu"]=array();		
			$ti=0;		
			$salidas["menu"][$k]["submenu"][$ti]["label"]="txt-358-0"; //OT
			$salidas["menu"][$k]["submenu"][$ti]["fil"]=0;
			$salidas["menu"][$k]["submenu"][$ti]["tpfil"]=0;		
			$ti++;		
			$salidas["menu"][$k]["submenu"][$ti]["label"]="txt-427-0";//MV
			$salidas["menu"][$k]["submenu"][$ti]["fil"]=1;
			$salidas["menu"][$k]["submenu"][$ti]["tpfil"]=0;
			$ti++;		
			$salidas["menu"][$k]["submenu"][$ti]["label"]="txt-424-0";//CE
			$salidas["menu"][$k]["submenu"][$ti]["fil"]=2;
			$salidas["menu"][$k]["submenu"][$ti]["tpfil"]=0;
			$ti++;		
			$salidas["menu"][$k]["submenu"][$ti]["label"]="txt-386-0";//CN
			$salidas["menu"][$k]["submenu"][$ti]["fil"]=3;
			$salidas["menu"][$k]["submenu"][$ti]["tpfil"]=0;
			$ti++;		
			$salidas["menu"][$k]["submenu"][$ti]["label"]="txt-421-0";//AT
			$salidas["menu"][$k]["submenu"][$ti]["fil"]=4;
			$salidas["menu"][$k]["submenu"][$ti]["tpfil"]=0;
		
		$k++;
		$salidas["menu"][$k]["label"]="txt-495-0"; //ALMACEN
		$salidas["menu"][$k]["defsubmenu"]=0;
		$salidas["menu"][$k]["tinfo"]=1;
		$salidas["menu"][$k]["submenu"]=array();		
			$ti=0;	
			$salidas["menu"][$k]["submenu"][$ti]["label"]="txt-492-0";//ING/EG
			$salidas["menu"][$k]["submenu"][$ti]["fil"]=0;
			$salidas["menu"][$k]["submenu"][$ti]["tpfil"]=1;
			
			$ti++;		
			$salidas["menu"][$k]["submenu"][$ti]["label"]="txt-437-0";//GE
			$salidas["menu"][$k]["submenu"][$ti]["fil"]=1;
			$salidas["menu"][$k]["submenu"][$ti]["tpfil"]=1;

		if($cnf==19){
			$k++;		
			$salidas["menu"][$k]["label"]="txt-497-0"; //EMPLEADOS
			$salidas["menu"][$k]["tinfo"]=5;
		}
	}
	elseif($_PROYECTO==8){	
		$salidas["menu"][$k]["label"]="txt-9050-0"; //Recaudos
		$salidas["menu"][$k]["tinfo"]=0;	
		$salidas["menu"][$k]["defsubmenu"]=0;
		$salidas["menu"][$k]["submenu"]=array();					
			$ti=0;
			$salidas["menu"][$k]["submenu"][$ti]["label"]="txt-9027-0"; //ACUERDO DE PAGO
			$salidas["menu"][$k]["submenu"][$ti]["fil"]=0;
			$salidas["menu"][$k]["submenu"][$ti]["tpfil"]=0;

			$ti++;
			$salidas["menu"][$k]["submenu"][$ti]["label"]="txt-9057-0"; //Pagos Adicionales
			$salidas["menu"][$k]["submenu"][$ti]["fil"]=1;
			$salidas["menu"][$k]["submenu"][$ti]["tpfil"]=0;

			$ti++;
			$salidas["menu"][$k]["submenu"][$ti]["label"]="txt-9066-0"; //Cuota de Separacion
			$salidas["menu"][$k]["submenu"][$ti]["fil"]=2;
			$salidas["menu"][$k]["submenu"][$ti]["tpfil"]=0;

			$ti++;
			$salidas["menu"][$k]["submenu"][$ti]["label"]="txt-9069-0"; //Subsidios
			$salidas["menu"][$k]["submenu"][$ti]["fil"]=3;
			$salidas["menu"][$k]["submenu"][$ti]["tpfil"]=0;

		if($cnf==19){			
			$k++;
			$salidas["menu"][$k]["label"]="txt-9006-0"; //Proyectos
			$salidas["menu"][$k]["tinfo"]=1;

			$k++;
			$salidas["menu"][$k]["label"]="txt-9074-0"; //Clientes
			$salidas["menu"][$k]["tinfo"]=2;
				
		}			
		if($cnf==8){
			$k++;			
			$salidas["menu"][$k]["label"]="txt-1110-0"; //USUARIOS
			$salidas["menu"][$k]["tinfo"]=3;
			$k++;
		}
	}
	elseif($_PROYECTO==13){	
		$salidas["menu"][$k]["label"]="txt-3021-0"; //VENDIDOS
		$salidas["menu"][$k]["tinfo"]=0;	

		if($cnf==8){
			$k++;
			$salidas["menu"][$k]["label"]="txt-1110-0"; //USUARIOS
			$salidas["menu"][$k]["tinfo"]=1;
		}
		if($cnf==19){
			$k++;
			$salidas["menu"][$k]["label"]="txt-3008-0"; //VITRINA
			$salidas["menu"][$k]["tinfo"]=2;
		}
	}
	json_Item($reg,$salidaUNO,$cnf,$md,$_sysvars_r);	$salidas=array_merge($salidas,$salidaUNO);	
}
else{
	if($_PROYECTO==1)			include("resumen_prev_001.php"); //ROCKETMP
	elseif($_PROYECTO==8)		include("resumen_prev_008.php"); //FALCONCRM
	elseif($_PROYECTO==10)		include("resumen_prev_010.php"); //TUPYME
	elseif($_PROYECTO==11)		include("resumen_prev_011.php"); //ALESTRA GEO
	elseif($_PROYECTO==13)		include("resumen_prev_013.php"); //CIUDAD TRAVEL
	elseif($_PROYECTO==14)		include("resumen_prev_014.php"); //EVENTOS CCB
	elseif($_PROYECTO==15)		include("resumen_prev_015.php"); //PONTIFICIA
	elseif($_PROYECTO==16)		include("resumen_prev_016.php"); //DISPONIBLES
	elseif($_PROYECTO==18)		include("resumen_prev_018.php"); //Mensajero
	elseif($_PROYECTO==19)		include("resumen_prev_019.php"); //SCA
	elseif($_PROYECTO==20)		include("resumen_prev_020.php"); //APPETITOS
	elseif($_PROYECTO==21)		include("resumen_prev_021.php"); //INNOVA
	elseif($_PROYECTO==22)		include("resumen_prev_022.php"); //ROCKETMP
	elseif($_PROYECTO==23)		include("resumen_prev_023.php"); //VIGA
	elseif($_PROYECTO==24)		include("resumen_prev_024.php"); //MARCA GPS
	elseif($_PROYECTO==25)		include("resumen_prev_025.php"); //Esteban Rios
	elseif($_PROYECTO==26)		include("resumen_prev_026.php"); //Mis Veterinarias
	elseif($_PROYECTO==27)		include("resumen_prev_027.php"); //Cancheros
	elseif($_PROYECTO==28)		include("resumen_prev_028.php"); //Acipet -Petrozones
	elseif($_PROYECTO==29)		include("resumen_prev_029.php"); //Asking Room
	elseif($_PROYECTO==31)		include("resumen_prev_031.php"); //CheckIN
	elseif($_PROYECTO==32)		include("resumen_prev_032.php"); //SuperMaestros
}
echo json_encode($salidas);
?>
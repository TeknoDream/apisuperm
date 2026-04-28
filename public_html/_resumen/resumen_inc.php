<?php

///////////////////////////////////////////////
$busc=isset($_REQUEST["busc"])?imprimir($_REQUEST["busc"]):'';
$busc_query='%'.$_REQUEST["busc"].'%';
$busc_send=isset($_REQUEST["busc"])?$_REQUEST["busc"]:'';
///////////////////////////////////////////////

$fil=isset($_REQUEST["fl-fil"])?$_REQUEST["fl-fil"]:0;
$tinfo=isset($_REQUEST["tinfo"])?$_REQUEST["tinfo"]:0;

$t_pag=isset($_REQUEST["tp"])?$_REQUEST["tp"]:1;
$PagActual=isset($_REQUEST["p"])?$_REQUEST["p"]:1;
///////////////////////////////////////////////
$md=isset($_REQUEST["md"])?$_REQUEST["md"]:'';
$c_sha=mb_substr($md,40);
$id_sha=mb_substr($md,0,-32);
$cnf=isset($_REQUEST["cnf"])?$_REQUEST["cnf"]:0;

if($tp==1) 	$idMaxItem=2; 
else 		$idMaxItem=1;
$MaxItems=$NMaxItems[$idMaxItem];


///////////////////////////////////////////////
//LEE TODO EL GET
$salidas=array();
foreach($_REQUEST as $key => $val){
	if($key!='__route__'&&$key!='_AJAX')
		$salidas["parAd"][$key]=$val; 
	
} 
$salidas["parAd"]['fl-t']=$t;
////////////UBICACIONES////////////////////////


//MENU SUPERIOR
//BUSCADOR
//MENU INFERIOR
//CONTENIDOS =>CAJAS,TEXTOS

/**/
$acc01=encrip('1',2);
$acc02=encrip('2',2);
$acc03=encrip('3',2);
$acc04=encrip('4',2);
$acc05=encrip('5',2);
$acc06=encrip('6',2);
$acc07=encrip('7',2);
$acc08=encrip('8',2);
$acc09=encrip('9',2);
/**/

if(($cnf==36)||($cnf==8)||($cnf==19)){
	try{
		if($tinfo==50 || $tinfo==51 || $tinfo==52){
			if($cnf==36) 		$sWhere=encrip_mysql('adm_usuarios.ID_USUARIO');
			elseif($cnf==8) 	$sWhere=encrip_mysql('adm_usuarios_empresa.ID_GRUPO');	
			elseif($cnf==19) 	$sWhere=encrip_mysql('s_cresp.ID_RESP');		

			if($tinfo==50){
				$sWhere_q=sWhere_cons(11,$busc);
				$s=$sqlCons[1][105]." WHERE $sWhere=:id $sWhere_q ";
				$Order=$sqlOrder[1][105];			
			}
			elseif($tinfo==51){
				$sWhere_q=sWhere_cons(11,$busc);
				$s=$sqlCons[1][0]." WHERE $sWhere=:id $sWhere_q ";
				$Order=$sqlOrder[1][0];			
			}
			elseif($tinfo==52){
				$sWhere_cresp=encrip_mysql('s_cresp_grupo.ID_GRUPO');
				$sWhere_q=sWhere_cons(8,$busc);
				$s=$sqlCons[1][101]." WHERE $sWhere_cresp=:id $sWhere_q ";
				$Order=$sqlOrder[1][101];			
			}
			$sCont="SELECT COUNT(*) FROM (".$s.") AS CONTEO";
			$reqCont = $dbEmpresa->prepare($sCont); 
			$reqCont->bindParam(':id', $id_sha);		
			if($_REQUEST["busc"]!='') $reqCont->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
			$reqCont->execute(); 
			$Total = $reqCont->fetchColumn();		
			$IniDato=($PagActual-1)*$NMaxItems[1];				
			/**/
			$i=0;
			/**/		
			$s=$s.' '.$Order." LIMIT $IniDato,$NMaxItems[1]";
			$req = $dbEmpresa->prepare($s); 
			$req->bindParam(':id', $id_sha);
			if($_REQUEST["busc"]!='') $req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
			$req->execute(); 				
			while($reg = $req->fetch()){
				$salidas["nItem"][$i]=array();
				json_Item($reg,$salidas["nItem"][$i],$cnf,$md,$_sysvars_r);				$i++;				
			}
		}

		if($_PROYECTO==8){	
							$index_Wh[19]=encrip_mysql('s_cresp.ID_RESP');
							$index_Wh[36]=encrip_mysql('adm_usuarios_op.ID_USUARIO');
			if($tinfo==3) 	$index_Wh[8]=encrip_mysql('adm_usuarios_empresa.ID_GRUPO');
			else 			$index_Wh[8]=encrip_mysql('s_cresp_grupo.ID_GRUPO');
			//ACUERDOS DE PAGO
			$index_t[0][0]=829;
			$index_Q[0][0]=33;			

			//ACUERDOS DE PAGO 'OTROS'
			$index_t[1][0]=830;
			$index_Q[1][0]=46;			

			//ACUERDO DE SEPARACION
			$index_t[2][0]=827;
			$index_Q[2][0]=50;	

			//ACUERDO DE SUBSIDIOS
			$index_t[3][0]=831;
			$index_Q[3][0]=47;	


			//PROYECTOS
			$index_t[0][1]=805;
			$index_Q[0][1]=29;	

			//CLIENTES
			$index_t[0][2]=808;
			$index_Q[0][2]=31;	

			//USUARIOS
			$index_t[0][3]=0;
			$index_Q[0][3]=11;	
			
			
			$sWhere=" WHERE ".$index_Wh[$cnf]."=:id ";	
			$sWhere.=sWhere_cons($index_Q[$fil][$tinfo],$busc);
		

			/*TABLA*/
			$s=$sqlCons[1][$index_t[$fil][$tinfo]].$sWhere;
			$sCont="SELECT COUNT(*) FROM (".$s.") AS CONTEO";
			$reqCont = $dbEmpresa->prepare($sCont);		
			$reqCont->bindParam(':id', $id_sha);
			if($_REQUEST["busc"]!='') $reqCont->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
			$reqCont->execute(); 
			$Total = $reqCont->fetchColumn();		
			$IniDato=($PagActual-1)*$NMaxItems[1];	

			$s=$s.' '.$sqlOrder[$fil][1][$index_t[$fil][$tinfo]]." LIMIT $IniDato,$NMaxItems[1]";
			$req = $dbEmpresa->prepare($s); 
			$req->bindParam(':id', $id_sha);
			if($_REQUEST["busc"]!='') $req->bindParam(':Buscar', $busc_query, PDO::PARAM_STR);
			$req->execute(); 

			if(($tinfo==1)||($tinfo==2)||($tinfo==3)){						
				while($reg = $req->fetch()){
					$salidas["nItem"][$i]=array();
					json_Item($reg,$salidas["nItem"][$i],$cnf,$md,$_sysvars_r);					$i++;				
				}
			}
			else{				
				$salida=array();
				$salidas["tipo"]="tabla";
				$salidas["id"]="INFO_".$fil."_".$tinfo;
				$salidas["display"]="block";
				$salidas["titulo"]='';
				$salidas["titulos"]=array();
				$salidas["nItem"]=array();
				if($tinfo==0){				
					$i=0;
					$k=0;					
					$salidas["titulos"][$k]["cont"][$i]["label"]="txt-9041-0"; //Fecha de Pago
					$salidas["titulos"][$k]["cont"][$i]["width"]=13;
					$salidas["titulos"][$k]["cont"][$i]["rowspan"]=2;
					$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";

					$i++;
					$salidas["titulos"][$k]["cont"][$i]["label"]="txt-9016-0"; //Entidad Financiera
					$salidas["titulos"][$k]["cont"][$i]["width"]=18;
					$salidas["titulos"][$k]["cont"][$i]["rowspan"]=2;
					$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";

					$i++;
					$salidas["titulos"][$k]["cont"][$i]["label"]="txt-9074-0"; //CLIENTE
					$salidas["titulos"][$k]["cont"][$i]["width"]=25;
					$salidas["titulos"][$k]["cont"][$i]["rowspan"]=2;
					$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";

					$i++;
					$salidas["titulos"][$k]["cont"][$i]["label"]="txt-9005-0"; //Proyecto
					$salidas["titulos"][$k]["cont"][$i]["width"]=21;
					$salidas["titulos"][$k]["cont"][$i]["colspan"]=2;
					$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";

					$i++;
					$salidas["titulos"][$k]["cont"][$i]["label"]="txt-9019-0"; //SEPARACION ID
					$salidas["titulos"][$k]["cont"][$i]["width"]=15;
					$salidas["titulos"][$k]["cont"][$i]["rowspan"]=2;
					$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";

					$i++;
					$salidas["titulos"][$k]["cont"][$i]["label"]="txt-368-0"; //VALOR
					$salidas["titulos"][$k]["cont"][$i]["width"]=8;
					$salidas["titulos"][$k]["cont"][$i]["rowspan"]=2;
					$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";

					$i=0;
					$k++;			
					$salidas["titulos"][$k]["cont"][$i]["label"]="txt-9003-0"; //SEGMENTO
					$salidas["titulos"][$k]["cont"][$i]["width"]=15;
					$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";

					$i++;
					$salidas["titulos"][$k]["cont"][$i]["label"]="txt-9017-0"; //Inmueble,
					$salidas["titulos"][$k]["cont"][$i]["width"]=6;
					$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";	
						
					/**/
					$k=0;
					$line=0;
					/**/			
					while($reg = $req->fetch()){
						
						$salidas["nItem"][$k]=array();
						$Colores=($line%2==0)?"colf01":"colf02";

						/***/
						$i=0;
						$salidas["nItem"][$k]["cont"][$i]["label"]=$reg["FECHAF"];
						$salidas["nItem"][$k]["cont"][$i]["rowspan"]=2;		
						$salidas["nItem"][$k]["cont"][$i]["class"][0]=$Colores;

						$md_tmp=encrip($reg["ID_ENTIDAD"]).encrip(60,2);
						$i++;
						$salidas["nItem"][$k]["cont"][$i]["label"]=imprimir($reg["NOMB_ENTIDAD"]);
						$salidas["nItem"][$k]["cont"][$i]["data"]["md"]=$md_tmp;
						$salidas["nItem"][$k]["cont"][$i]["rowspan"]=2;		
						$salidas["nItem"][$k]["cont"][$i]["class"][0]=$Colores;
						if($PermisosA[60]["P"]==1){								
							$salidas["nItem"][$k]["cont"][$i]["link"]=2;
							$salidas["nItem"][$k]["cont"][$i]["cod"]="md=".$md_tmp;
							$salidas["nItem"][$k]["cont"][$i]["pagina"]='/abstract';
						}			

						$md_tmp=encrip($reg["ID_CLIENTE"]).encrip(59,2);
						$i++;
						$salidas["nItem"][$k]["cont"][$i]["label"]=sprintf("%s %s",imprimir($reg["NOMB_CLIENTE"]),imprimir($reg["APELLIDO_CLIENTE"]));
						$salidas["nItem"][$k]["cont"][$i]["data"]["md"]=$md_tmp;
						$salidas["nItem"][$k]["cont"][$i]["rowspan"]=2;	
						$salidas["nItem"][$k]["cont"][$i]["class"][0]=$Colores;	
						if($PermisosA[59]["P"]==1){								
							$salidas["nItem"][$k]["cont"][$i]["link"]=2;
							$salidas["nItem"][$k]["cont"][$i]["cod"]="md=".$md_tmp;
							$salidas["nItem"][$k]["cont"][$i]["pagina"]='/abstract';
						}

						$md_tmp=encrip($reg["ID_PROYECTO"]).encrip(55,2);	
						$i++;
						$salidas["nItem"][$k]["cont"][$i]["label"]=imprimir($reg["NOMB_PROYECTO"]);
						$salidas["nItem"][$k]["cont"][$i]["data"]["md"]=$md_tmp;
						$salidas["nItem"][$k]["cont"][$i]["colspan"]=2;
						$salidas["nItem"][$k]["cont"][$i]["class"][0]=$Colores;
						$salidas["nItem"][$k]["cont"][$i]["css"]["text-align"]="center";
						if($PermisosA[55]["P"]==1){							
							$salidas["nItem"][$k]["cont"][$i]["link"]=2;
							$salidas["nItem"][$k]["cont"][$i]["cod"]="md=".$md_tmp;
							$salidas["nItem"][$k]["cont"][$i]["pagina"]='/abstract';
						}

						$md_tmp=encrip($reg["ID_SEPARACION"]).encrip(57,2);	
						$i++;
						$salidas["nItem"][$k]["cont"][$i]["label"]=sprintf("%s-%s",$_textos[9049][0],$reg["ID_SEPARACION"]);
						$salidas["nItem"][$k]["cont"][$i]["data"]["md"]=$md_tmp;
						$salidas["nItem"][$k]["cont"][$i]["rowspan"]=2;	
						$salidas["nItem"][$k]["cont"][$i]["class"][0]=$Colores;	
						$salidas["nItem"][$k]["cont"][$i]["css"]["text-align"]="center";
						if($PermisosA[57]["P"]==1){							
							$salidas["nItem"][$k]["cont"][$i]["link"]=2;
							$salidas["nItem"][$k]["cont"][$i]["cod"]="md=".$md_tmp;
							$salidas["nItem"][$k]["cont"][$i]["pagina"]='/abstract';
						}
						/***/

						
						$i++;
						$salidas["nItem"][$k]["cont"][$i]["label"]="$".number_format($reg["VALOR_PAGADO"]);
						$salidas["nItem"][$k]["cont"][$i]["rowspan"]=2;
						$salidas["nItem"][$k]["cont"][$i]["class"][0]=$Colores;

						$k++;
						/***************************************/
						/***************************************/
						$salidas["nItem"][$k]=array();		

						/***/
						
						$md_tmp=encrip($reg["ID_SEGMENTO"]).encrip(56,2);	
						$i=0;
						$salidas["nItem"][$k]["cont"][$i]["label"]=imprimir($reg["NOMB_SEGMENTO"]);
						$salidas["nItem"][$k]["cont"][$i]["data"]["md"]=$md_tmp;
						$salidas["nItem"][$k]["cont"][$i]["class"][0]=$Colores;
						$salidas["nItem"][$k]["cont"][$i]["css"]["text-align"]="center";
						if($PermisosA[56]["P"]==1){							
							$salidas["nItem"][$k]["cont"][$i]["link"]=2;
							$salidas["nItem"][$k]["cont"][$i]["cod"]="md=".$md_tmp;
							$salidas["nItem"][$k]["cont"][$i]["pagina"]='/abstract';
						}

						$md_tmp=encrip($reg["ID_INMUEBLE"]).encrip(62,2);	
						$i++;
						$salidas["nItem"][$k]["cont"][$i]["label"]=imprimir($reg["NOM_INMUEBLE"]);
						$salidas["nItem"][$k]["cont"][$i]["data"]["md"]=$md_tmp;
						$salidas["nItem"][$k]["cont"][$i]["class"][0]=$Colores;
						$salidas["nItem"][$k]["cont"][$i]["css"]["text-align"]="center";
						if($PermisosA[62]["P"]==1){							
							$salidas["nItem"][$k]["cont"][$i]["link"]=2;
							$salidas["nItem"][$k]["cont"][$i]["cod"]="md=".$md_tmp;
							$salidas["nItem"][$k]["cont"][$i]["pagina"]='/abstract';
						}
						$k++;				
							
						
						$line++;
					}		
					print_paginacion($salidas,$Total,$PagActual,$idMaxItem);
				}
			}
		}	
	}
	catch (Exception $e){
		$err_str=$e->getMessage();
	}
}
else{
	if($_PROYECTO==1)			include("resumen_inc_001.php"); //ROCKETMP	
	elseif($_PROYECTO==8)		include("resumen_inc_008.php"); //FALCONCRM
	elseif($_PROYECTO==10)		include("resumen_inc_010.php"); //TUPYME
	elseif($_PROYECTO==11)		include("resumen_inc_011.php"); //ALESTRA GEO
	elseif($_PROYECTO==13)		include("resumen_inc_013.php"); //CIUDAD TRAVEL
	elseif($_PROYECTO==14)		include("resumen_inc_014.php"); //EVENTOS CCB
	elseif($_PROYECTO==15)		include("resumen_inc_015.php"); //PONTIFICIA
	elseif($_PROYECTO==16)		include("resumen_inc_016.php"); //DISPONIBLES
	elseif($_PROYECTO==18)		include("resumen_inc_018.php"); //MENSAJERO
	elseif($_PROYECTO==19)		include("resumen_inc_019.php"); //SCA
	elseif($_PROYECTO==20)		include("resumen_inc_020.php"); //APPETITOS
	elseif($_PROYECTO==21)		include("resumen_inc_021.php"); //INNOVA
	elseif($_PROYECTO==22)		include("resumen_inc_022.php"); //ROCKETMP	
	elseif($_PROYECTO==23)		include("resumen_inc_023.php"); //VIGA	
	elseif($_PROYECTO==24)		include("resumen_inc_024.php"); //MARCA GPS	
	elseif($_PROYECTO==25)		include("resumen_inc_025.php"); //Esteban Rios
	elseif($_PROYECTO==26)		include("resumen_inc_026.php"); //Mis Veterinarias
	elseif($_PROYECTO==27)		include("resumen_inc_027.php"); //Cancheros
	elseif($_PROYECTO==28)		include("resumen_inc_028.php"); //Petrozones
	elseif($_PROYECTO==29)		include("resumen_inc_029.php"); //Asking Room
	elseif($_PROYECTO==31)		include("resumen_inc_031.php"); //CheckIN
	elseif($_PROYECTO==32)		include("resumen_inc_032.php"); //SuperMaestros
}

/*
$_SESSION["PP"]=$salidas;
$_SESSION["DD"]=$_REQUEST;*/
$_SESSION["e_res"]=$e;
/*
$_SESSION["s1"]=htmlentities($s);
/*
$_SESSION["total"]=$Total;
*/
echo json_encode($salidas);
$salidas=null;unset($salidas);
$i=null;unset($i);
$req->__distruct;
$reg=null;unset($reg);

?>
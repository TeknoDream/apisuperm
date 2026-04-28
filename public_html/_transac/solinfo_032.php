<?php 
/*
---------------------------------------------------------------------------------
                        SUPERMAESTROS BACKOFFICE
                             Proyecto N° 32
                        Archivo: solinfo_032.php
     Descripción: archivo de configuración de formularios dinámicos y tablas dentro de los
     					formularios principales
--------------------------------------------------------------------------------
Este archivo configura los sub-formularios, tablas y listas que se muestran dentro de los
formularios principales, como por ejemplo el de usuarios, que muestra internamente en el
formulario principal la lista de las especialidades el instalador, de las redes sociales,
etc. 
Se configura de acuerdo al número de módulo que se esté cargando en el formulario

Módulos de SuperMaestros
------------------------
N° 500 Usuarios
N° 501 Remodelaciones (inicialmente llamado Proyectos)
N° 502 Proyectos (inicialmente denominado Ofertas)
N° 503 Facturas
N° 504 Cotizaciones
N° 505 Estado de cuenta del instalador
N° 506 Noticias
N° 507 Publicidad
N° 508 Mensajes
N° 509 Especialidades
*/
//Usuarios
if($cnf==500){
	$sWhere=encrip_mysql('adm_usuarios.ID_USUARIO');
	$s="SELECT adm_usuarios.ID_USUARIO AS ID FROM adm_usuarios WHERE $sWhere=:id LIMIT 1";	
	$req = $dbEmpresa->prepare($s); 
	$req->bindParam(':id', $id_sha);
	$req->execute();	
	if($reg = $req->fetch()) $id_item=$reg["ID"];
//Especialidades
	if($tp==1){	
			$s=$sqlCons[5][500]." WHERE z_espec.HAB_ESPEC=0 ".$sqlOrder[5][500];
		    $req = $dbEmpresa->prepare($s);
		    $req->bindParam(':id_usuario', $id_item);
		    $req->execute();
			
			$salida=array();
			$salidas["display"]="block";
			$salidas["titulo"]='';
			$salidas["titulos"]=array();
			$salidas["nItem"]=array();
			
			$i=0;
			$k=0;
			$salidas["titulos"][$k]["cont"][$i]["label"]='txt-195-0';
			$salidas["titulos"][$k]["cont"][$i]["width"]=10;
			
			$i++;
			$salidas["titulos"][$k]["cont"][$i]["width"]=45;
			$salidas["titulos"][$k]["cont"][$i]["label"]='txt-162-0';	

		
			//Si al consulta es exitosa mostramos la tabla dentro del formulario del usuario
			$k=0;
			while($reg = $req->fetch()){
				$salidas["nItem"][$k]=array();		
				$i=0;			
				$salidas["nItem"][$k]["cont"][$i]["label"]='txt-162-1';
				$salidas["nItem"][$k]["cont"][$i]["tipo"]='checkbox';
				$salidas["nItem"][$k]["cont"][$i]["checked"]=$reg["SELECTED"]!=0;
				$salidas["nItem"][$k]["cont"][$i]["value"]=$reg["ID_ESPEC"];
				$salidas["nItem"][$k]["cont"][$i]["name"]="nEspec[".$reg["ID_ESPEC"]."]";
				$salidas["nItem"][$k]["cont"][$i]["id"]="IdNsEspec".$reg["ID_ESPEC"];
				
				$i++;
				$salidas["nItem"][$k]["cont"][$i]["label"]=$reg["NAME_ESPEC"];

				
				$i++;
				$salidas["nItem"][$k]["cont"][$i]["tipo"]='hidden';
				$salidas["nItem"][$k]["cont"][$i]["value"]=$reg["ID_ESPEC"];
				$salidas["nItem"][$k]["cont"][$i]["name"]="IdEspec[".$reg["ID_ESPEC"]."]";	

				$k++;
			}	
			echo json_encode($salidas);
	}
	//URLS Redes sociales
	elseif($tp==2){	
		$s=$sqlCons[2][500];
		$Order=$sqlOrder[2][500];	
		/**/
		/**/		
		$s=$s.' WHERE fac_turls.HAB_URLS=0 '.$Order;
		 					
		$req = $dbEmpresa->prepare($s); 		
		$req->bindParam(':id_usuario', $id_item);
		$req->execute(); 			
		$salida=array();
		$salidas["display"]="block";
		$salidas["titulo"]='';
		$salidas["titulos"]=array();
		$salidas["nItem"]=array();
		$salidas["data"]["edcontrol"]=true;
		$salidas["data"]["edid"]='EDUrls';
		$salidas["attr"]["width"]='100%';
		$i=0;
		$k=0;
		$salidas["titulos"][$k]["cont"][$i]["label"]='txt-1369-1';
		$salidas["titulos"][$k]["cont"][$i]["width"]=30;			
				
		$i++;
		$salidas["titulos"][$k]["cont"][$i]["label"]='txt-1369-0';
		$salidas["titulos"][$k]["cont"][$i]["width"]=70;	
		
		$k=0;
		while($reg = $req->fetch()){
			$salidas["nItem"][$k]=array();			
			$i=0;
			$salidas["nItem"][$k]["cont"][$i]["label"]=imprimir($reg["DESC_URLS"]);

			$i++;
			$salidas["nItem"][$k]["cont"][$i]["title"]='';
			$salidas["nItem"][$k]["cont"][$i]["placeholder"]=$reg["EXAMPLE_URLS"];
			$salidas["nItem"][$k]["cont"][$i]["tipo"]='text';
			$salidas["nItem"][$k]["cont"][$i]["value"]=imprimir($reg["URLS"]);
			$salidas["nItem"][$k]["cont"][$i]["name"]="EmpURL[".$reg["ID_URLS"]."]";

			$i++;
			$salidas["nItem"][$k]["cont"][$i]["tipo"]='hidden';
			$salidas["nItem"][$k]["cont"][$i]["value"]=$reg["ID_URLS"];
			$salidas["nItem"][$k]["cont"][$i]["name"]="IdURL[".$reg["ID_URLS"]."]";

			$i++;
			$salidas["nItem"][$k]["cont"][$i]["tipo"]='hidden';
			$salidas["nItem"][$k]["cont"][$i]["value"]=$reg["TIPO_URLS"];
			$salidas["nItem"][$k]["cont"][$i]["name"]="TpURL[".$reg["ID_URLS"]."]";

			$i++;
			$salidas["nItem"][$k]["cont"][$i]["tipo"]='hidden';
			$salidas["nItem"][$k]["cont"][$i]["value"]=0;
			$salidas["nItem"][$k]["cont"][$i]["name"]="EDUrls[".$reg["ID_URLS"]."]";				
			$k++;			
		}	
		
		echo json_encode($salidas);
	}
}
// OFERTAS
elseif($cnf==502){
	$sWhere=encrip_mysql('x_ofertas.ID_OFERTA');
	$s="SELECT x_ofertas.ID_OFERTA AS ID FROM x_ofertas WHERE $sWhere=:id LIMIT 1";	
	$req = $dbEmpresa->prepare($s); 
	$req->bindParam(':id', $id_sha);
	$req->execute();	
	if($reg = $req->fetch()) $id_item=$reg["ID"];
	//Especialidades
	if($tp==1){	
			$s=$sqlCons[1][502]." WHERE z_espec.HAB_ESPEC=0 ".$sqlOrder[1][502];
		    $req = $dbEmpresa->prepare($s);
		    $req->bindParam(':id_oferta', $id_item);
		    $req->execute();
			
			$salida=array();
			$salidas["display"]="block";
			$salidas["titulo"]='';
			$salidas["titulos"]=array();
			$salidas["nItem"]=array();
			
			$i=0;
			$k=0;
			$salidas["titulos"][$k]["cont"][$i]["label"]='txt-195-0';
			$salidas["titulos"][$k]["cont"][$i]["width"]=10;
			
			$i++;
			$salidas["titulos"][$k]["cont"][$i]["width"]=45;
			$salidas["titulos"][$k]["cont"][$i]["label"]='txt-162-0';	

		
			
			$k=0;
			while($reg = $req->fetch()){
				$salidas["nItem"][$k]=array();		
				$i=0;			
				$salidas["nItem"][$k]["cont"][$i]["label"]='txt-162-1';
				$salidas["nItem"][$k]["cont"][$i]["tipo"]='radio';
				$salidas["nItem"][$k]["cont"][$i]["checked"]=$reg["SELECTED"]!=0;
				$salidas["nItem"][$k]["cont"][$i]["value"]=$reg["ID_ESPEC"];;
				$salidas["nItem"][$k]["cont"][$i]["name"]="NomEspc[".$reg["NAME_ESPEC"]."]";
				$salidas["nItem"][$k]["cont"][$i]["id"]="IdMat".$reg["ID_ESPEC"];
				
				$i++;
				$salidas["nItem"][$k]["cont"][$i]["label"]=$reg["NAME_ESPEC"];

				
				$i++;
				$salidas["nItem"][$k]["cont"][$i]["tipo"]='hidden';
				$salidas["nItem"][$k]["cont"][$i]["value"]=$reg["ID_ESPEC"];
				$salidas["nItem"][$k]["cont"][$i]["name"]="IdMat[".$reg["ID_ESPEC"]."]";	

				$k++;
			}	
			echo json_encode($salidas);
	}
}
?>
<?php
//SPM-P01
if($infId==7){
	//ORDER//		
	$s=$sqlCons[0][500]." WHERE adm_usuarios.FECHA_U BETWEEN STR_TO_DATE(:fechai,'%d/%m/%Y') AND STR_TO_DATE(:fechaf,'%d/%m/%Y') ".$sqlOrder[0][500];   
	$req = $dbEmpresa->prepare($s);
	$req->bindParam(':fechai', $_GET["fechai"]);
    $req->bindParam(':fechaf', $_GET["fechaf"]); 
	$req->execute();
	$salida=array();
	$salidas["tipo"]="tabla";
	$salidas["display"]="block";
	$salidas["titulo"]='';
	$salidas["titulos"]=array();
	$salidas["nItem"]=array();	
	
	$k=0;

	$i=0;
	$salidas["titulos"][$k]["cont"][$i]["label"]='txt-100-0'; //Instalador
	$salidas["titulos"][$k]["cont"][$i]["width"]=10;
	$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";
	$i++;
	$salidas["titulos"][$k]["cont"][$i]["label"]='txt-101-0'; //Nombre
	$salidas["titulos"][$k]["cont"][$i]["width"]=20;
	$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";
	$i++;
	$salidas["titulos"][$k]["cont"][$i]["label"]='txt-102-0'; //Apellido
	$salidas["titulos"][$k]["cont"][$i]["width"]=20;
	$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";		
	$i++;
	$salidas["titulos"][$k]["cont"][$i]["label"]='txt-192-0'; //Correo
	$salidas["titulos"][$k]["cont"][$i]["width"]=15;
	$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";
	$i++;
	$salidas["titulos"][$k]["cont"][$i]["label"]='txt-105-0'; //Calificacion
	$salidas["titulos"][$k]["cont"][$i]["width"]=10;
	$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";
	$i++;
	$salidas["titulos"][$k]["cont"][$i]["label"]='txt-104-0'; //Puntaje
	$salidas["titulos"][$k]["cont"][$i]["width"]=10;
	$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";
	$i++;
	$salidas["titulos"][$k]["cont"][$i]["label"]='txt-107-0'; //Fecha
	$salidas["titulos"][$k]["cont"][$i]["width"]=15;
	$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";			
		
	while($reg = $req->fetch()){
		$i=0;
		$salidas["nItem"][$k]["cont"][$i]["label"]=imprimir($reg["ID_USUARIO"]);	

		$i++;
		$salidas["nItem"][$k]["cont"][$i]["label"]=imprimir($reg["NOMBRE_U"]);	

		$i++;
		$salidas["nItem"][$k]["cont"][$i]["label"]=imprimir($reg["APELLIDO_U"]);	

		$i++;
		$salidas["nItem"][$k]["cont"][$i]["label"]=imprimir($reg["CORREO_U"]);	

		$i++;
		$salidas["nItem"][$k]["cont"][$i]["label"]=number_format($reg["VCALIF_USUARIO"],2);	

		$i++;
		$salidas["nItem"][$k]["cont"][$i]["label"]=number_format($reg["DEST_USUARIO"],2);	

		$i++;
		$salidas["nItem"][$k]["cont"][$i]["label"]=imprimir($reg["FECHA_U"]);	
		
		$k++;
		
	}		
}
//SPM-P02
elseif($infId==8){
	//ORDER//		
	 $s=$sqlCons[5][501]." WHERE y_proyectos.FECHAS_PROY BETWEEN STR_TO_DATE(:fechai,'%d/%m/%Y') AND STR_TO_DATE(:fechaf,'%d/%m/%Y') ".$sqlOrder[5][501]; 
	$req = $dbEmpresa->prepare($s);
	$req->bindParam(':fechai', $_GET["fechai"]);
    $req->bindParam(':fechaf', $_GET["fechaf"]); 
	$req->execute();

	$salida=array();
	$salidas["tipo"]="tabla";
	$salidas["display"]="block";
	$salidas["titulo"]='';
	$salidas["titulos"]=array();
	$salidas["nItem"]=array();	
	
	$k=0;

	$i=0;
	$salidas["titulos"][$k]["cont"][$i]["label"]='txt-170-0'; //Id
	$salidas["titulos"][$k]["cont"][$i]["width"]=10;
	$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";
	$i++;
	$salidas["titulos"][$k]["cont"][$i]["label"]='txt-119-0'; //Titulo
	$salidas["titulos"][$k]["cont"][$i]["width"]=20;
	$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";
	$i++;
	$salidas["titulos"][$k]["cont"][$i]["label"]='txt-105-0'; //Calificacion
	$salidas["titulos"][$k]["cont"][$i]["width"]=15;
	$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";		
	$i++;
	$salidas["titulos"][$k]["cont"][$i]["label"]='txt-107-0'; //Fecha
	$salidas["titulos"][$k]["cont"][$i]["width"]=15;
	$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";
	$i++;
	$salidas["titulos"][$k]["cont"][$i]["label"]='txt-201-0'; //Comentarios
	$salidas["titulos"][$k]["cont"][$i]["width"]=10;
	$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";
	$i++;
	$salidas["titulos"][$k]["cont"][$i]["label"]='txt-109-1'; //Fotos
	$salidas["titulos"][$k]["cont"][$i]["width"]=10;
	$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";
	$i++;
	$salidas["titulos"][$k]["cont"][$i]["label"]='txt-101-0'; //Nombre
	$salidas["titulos"][$k]["cont"][$i]["width"]=15;
	$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";	
	$i++;
	$salidas["titulos"][$k]["cont"][$i]["label"]='txt-102-0'; //Apellidos
	$salidas["titulos"][$k]["cont"][$i]["width"]=15;
	$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";			
		
	while($reg = $req->fetch()){
		$i=0;
		$salidas["nItem"][$k]["cont"][$i]["label"]=imprimir($reg["ID_PROY"]);	

		$i++;
		$salidas["nItem"][$k]["cont"][$i]["label"]=imprimir($reg["NOMB_PROY"]);	

		$i++;
		$salidas["nItem"][$k]["cont"][$i]["label"]=imprimir($reg["VCALIF_PROY"]);	

		$i++;
		$salidas["nItem"][$k]["cont"][$i]["label"]=imprimir($reg["FECHAS_PROY"]);	

		$i++;
		$salidas["nItem"][$k]["cont"][$i]["label"]=imprimir($reg["COMMENTS_PROY"]);	

		$i++;
		$salidas["nItem"][$k]["cont"][$i]["label"]=imprimir($reg["C_FOTOS"]);	

		$i++;
		$salidas["nItem"][$k]["cont"][$i]["label"]=imprimir($reg["NOMBRE_U"]);	

		$i++;
		$salidas["nItem"][$k]["cont"][$i]["label"]=imprimir($reg["APELLIDO_U"]);	
		
		$k++;
		
	}		
}
//SPM-P03
elseif($infId==9){
	//ORDER//		
	$s=$sqlCons[0][502]." WHERE x_ofertas.FECHAS_OFERTA BETWEEN STR_TO_DATE(:fechai,'%d/%m/%Y') AND STR_TO_DATE(:fechaf,'%d/%m/%Y') ".$sqlOrder[0][502];       
    $req = $dbEmpresa->prepare($s); 
    $req->bindParam(':fechai', $_GET["fechai"]);
    $req->bindParam(':fechaf', $_GET["fechaf"]);          
    $req->execute();

	$salida=array();
	$salidas["tipo"]="tabla";
	$salidas["display"]="block";
	$salidas["titulo"]='';
	$salidas["titulos"]=array();
	$salidas["nItem"]=array();	
	
	$k=0;

	$i=0;
	$salidas["titulos"][$k]["cont"][$i]["label"]='txt-170-0'; //Id
	$salidas["titulos"][$k]["cont"][$i]["width"]=10;
	$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";
	$i++;
	$salidas["titulos"][$k]["cont"][$i]["label"]='txt-119-0'; //Titulo
	$salidas["titulos"][$k]["cont"][$i]["width"]=20;
	$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";	
	$i++;
	$salidas["titulos"][$k]["cont"][$i]["label"]='txt-107-0'; //Fecha
	$salidas["titulos"][$k]["cont"][$i]["width"]=15;
	$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";	
	$i++;
	$salidas["titulos"][$k]["cont"][$i]["label"]='txt-101-0'; //Nombre
	$salidas["titulos"][$k]["cont"][$i]["width"]=15;
	$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";	
	$i++;
	$salidas["titulos"][$k]["cont"][$i]["label"]='txt-102-0'; //Apellidos
	$salidas["titulos"][$k]["cont"][$i]["width"]=15;
	$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";			
		
	while($reg = $req->fetch()){
		$i=0;
		$salidas["nItem"][$k]["cont"][$i]["label"]=imprimir($reg["ID_OFERTA"]);	

		$i++;
		$salidas["nItem"][$k]["cont"][$i]["label"]=imprimir($reg["TITLE_OFERTA"]);	

		$i++;
		$salidas["nItem"][$k]["cont"][$i]["label"]=imprimir($reg["FECHAS_OFERTA"]);	

		$i++;
		$salidas["nItem"][$k]["cont"][$i]["label"]=imprimir($reg["NOMBRE_U_OP"]);	

		$i++;
		$salidas["nItem"][$k]["cont"][$i]["label"]=imprimir($reg["APELLIDO_U_OP"]);	
		
		$k++;
		
	}		
}
//SPM-P04
elseif($infId==10){
	//ORDER//		
	$s=$sqlCons[0][503]." WHERE y_facturas.FECHAS_FACT BETWEEN STR_TO_DATE(:fechai,'%d/%m/%Y') AND STR_TO_DATE(:fechaf,'%d/%m/%Y') ".$sqlOrder[0][503];       
    $req = $dbEmpresa->prepare($s); 
    $req->bindParam(':fechai', $_GET["fechai"]);
    $req->bindParam(':fechaf', $_GET["fechaf"]);          
    $req->execute();

	$salida=array();
	$salidas["tipo"]="tabla";
	$salidas["display"]="block";
	$salidas["titulo"]='';
	$salidas["titulos"]=array();
	$salidas["nItem"]=array();	
	
	$k=0;

	$i=0;
	$salidas["titulos"][$k]["cont"][$i]["label"]='txt-170-0'; //Id
	$salidas["titulos"][$k]["cont"][$i]["width"]=10;
	$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";
	$i++;
	$salidas["titulos"][$k]["cont"][$i]["label"]='txt-152-0'; //Valor
	$salidas["titulos"][$k]["cont"][$i]["width"]=15;
	$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";	
	$i++;
	$salidas["titulos"][$k]["cont"][$i]["label"]='txt-219-0'; //Valida
	$salidas["titulos"][$k]["cont"][$i]["width"]=15;
	$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";	
	$i++;
	$salidas["titulos"][$k]["cont"][$i]["label"]='txt-107-0'; //Fecha
	$salidas["titulos"][$k]["cont"][$i]["width"]=20;
	$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";	
	$i++;
	$salidas["titulos"][$k]["cont"][$i]["label"]='txt-101-0'; //Nombre
	$salidas["titulos"][$k]["cont"][$i]["width"]=20;
	$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";	
	$i++;
	$salidas["titulos"][$k]["cont"][$i]["label"]='txt-102-0'; //Apellidos
	$salidas["titulos"][$k]["cont"][$i]["width"]=20;
	$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";			
		
	while($reg = $req->fetch()){
		$i=0;
		$salidas["nItem"][$k]["cont"][$i]["label"]=imprimir($reg["ID_FACT"]);	

		$i++;
		$salidas["nItem"][$k]["cont"][$i]["label"]='$'.number_format($reg["VPOINT_FACT"],2);	

		$i++;
		$salidas["nItem"][$k]["cont"][$i]["label"]=imprimir($reg["VALIDAT_FACT"]);	

		$i++;
		$salidas["nItem"][$k]["cont"][$i]["label"]=imprimir($reg["FECHAS_FACT"]);	

		$i++;
		$salidas["nItem"][$k]["cont"][$i]["label"]=imprimir($reg["NOMBRE_U_OP"]);	

		$i++;
		$salidas["nItem"][$k]["cont"][$i]["label"]=imprimir($reg["APELLIDO_U_OP"]);
		
		$k++;
		
	}		
}
//SPM-P05
elseif($infId==11){
	//ORDER//		
	$s=$sqlCons[0][504]." WHERE y_cotizacion.FECHAS_COTIZ BETWEEN STR_TO_DATE(:fechai,'%d/%m/%Y') AND STR_TO_DATE(:fechaf,'%d/%m/%Y') ".$sqlOrder[0][504];       
    $req = $dbEmpresa->prepare($s); 
    $req->bindParam(':fechai', $_GET["fechai"]);
    $req->bindParam(':fechaf', $_GET["fechaf"]);          
    $req->execute();

	$salida=array();
	$salidas["tipo"]="tabla";
	$salidas["display"]="block";
	$salidas["titulo"]='';
	$salidas["titulos"]=array();
	$salidas["nItem"]=array();	
	
	$k=0;

	$i=0;
	$salidas["titulos"][$k]["cont"][$i]["label"]='txt-170-0'; //Id
	$salidas["titulos"][$k]["cont"][$i]["width"]=10;
	$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";
	$i++;
	$salidas["titulos"][$k]["cont"][$i]["label"]='txt-208-0'; //Estado
	$salidas["titulos"][$k]["cont"][$i]["width"]=10;
	$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";	
	$i++;
	$salidas["titulos"][$k]["cont"][$i]["label"]='txt-107-0'; //Fecha
	$salidas["titulos"][$k]["cont"][$i]["width"]=15;
	$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";	
	$i++;
	$salidas["titulos"][$k]["cont"][$i]["label"]='txt-152-0'; //Valor
	$salidas["titulos"][$k]["cont"][$i]["width"]=15;
	$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";	
	$i++;
	$salidas["titulos"][$k]["cont"][$i]["label"]='txt-100-0'; //Insalador
	$salidas["titulos"][$k]["cont"][$i]["width"]=25;
	$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";	
	$i++;
	$salidas["titulos"][$k]["cont"][$i]["label"]='txt-108-0'; //Usuario
	$salidas["titulos"][$k]["cont"][$i]["width"]=25;
	$salidas["titulos"][$k]["cont"][$i]["css"]["text-align"]="center";			
		
	while($reg = $req->fetch()){
		$i=0;
		$salidas["nItem"][$k]["cont"][$i]["label"]=imprimir($reg["ID_COTIZ"]);	

		$i++;
		$salidas["nItem"][$k]["cont"][$i]["label"]=imprimir($reg["STATUS_COTIZ"]);	

		$i++;
		$salidas["nItem"][$k]["cont"][$i]["label"]=imprimir($reg["FECHAS_COTIZ"]);	

		$i++;
		$salidas["nItem"][$k]["cont"][$i]["label"]='$'.number_format($reg["VTOT_COTIZ"]);	

		$i++;
		$salidas["nItem"][$k]["cont"][$i]["label"]=imprimir($reg["NOMBRE_M"]).' '.imprimir($reg["APELLIDO_M"]);

		$i++;
		$salidas["nItem"][$k]["cont"][$i]["label"]=imprimir($reg["NOMBRE_U"]).' '.imprimir($reg["APELLIDO_U"]);
		
		$k++;
		
	}		
}
?>
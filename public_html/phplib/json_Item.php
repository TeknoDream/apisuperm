<?php
function json_Item($reg,&$salidas,$cnf,$md,$_sysvars_r=array(),$opciones=array()){
	include "variables_se.php";	
	$c_sha=mb_substr($md,40);
	$id_sha=mb_substr($md,0,-32);
	

	$permiso_informe=$PermisosA[80]["P"];
	$md_informe=encrip(1).encrip(80,2);
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
	//TIPO DE OBJETO
	//1=> OBJETO
	//2=> OPERACION
	//3=> ADMINISTRACION
	//4=> CONFIGURACION
	//5=> PROGRAMACION
	
	//TIPO DE BOTON
	//1 NUEVO
	//2 VER
	//3 EDITAR	
	//4 ELIMINAR
	//5 OPERACIONES
	//6 ADDS
	
	//7 VER 01
	//8 VER 01
	//9 VER 01
	
	/*INICIO DE SALIDA*/
	$_NuevoI=nuevo_item();
	$salidas=array();


	$ArrayTempl=array(
            'PROYECTO'=>$_PROYECTO
        ,   'EMPRESA'  =>$_EMPRESA
        ,   'MODULO'    =>0
        ,   'OBJETO'    =>0
        ,   'TP'        =>'img'
        ,   'EXT'       =>$reg["F_EXT"]
        ,   'All'       =>true);

	$ArrayImg=$ArrayTempl;

	include("json_Item_000.php");
	if(!isset($salidas["idsha"])){
		if($_PROYECTO==1)			include("json_Item_001.php");
		elseif($_PROYECTO==5)		include("json_Item_005.php");
		elseif($_PROYECTO==8)		include("json_Item_008.php");
		elseif($_PROYECTO==10)		include("json_Item_010.php");
		elseif($_PROYECTO==11)		include("json_Item_011.php");
		elseif($_PROYECTO==13)		include("json_Item_013.php");
		elseif($_PROYECTO==14)		include("json_Item_014.php");
		elseif($_PROYECTO==15)		include("json_Item_015.php");
		elseif($_PROYECTO==16)		include("json_Item_016.php");
		elseif($_PROYECTO==18)		include("json_Item_018.php");
		elseif($_PROYECTO==19)		include("json_Item_019.php");
		elseif($_PROYECTO==20)		include("json_Item_020.php");
		elseif($_PROYECTO==21)		include("json_Item_021.php");
		elseif($_PROYECTO==22)		include("json_Item_022.php");
		elseif($_PROYECTO==23)		include("json_Item_023.php");
		elseif($_PROYECTO==24)		include("json_Item_024.php");
		elseif($_PROYECTO==25)		include("json_Item_025.php");
		elseif($_PROYECTO==26)		include("json_Item_026.php");
		elseif($_PROYECTO==27)		include("json_Item_027.php");
		elseif($_PROYECTO==28)		include("json_Item_028.php");
		elseif($_PROYECTO==29)		include("json_Item_029.php");
		elseif($_PROYECTO==31)		include("json_Item_031.php");
		elseif($_PROYECTO==32)		include("json_Item_032.php");
		elseif($_PROYECTO==33)		include("json_Item_033.php");
		elseif($_PROYECTO==34)		include("json_Item_034.php");
		elseif($_PROYECTO==35)		include("json_Item_035.php");
		elseif($_PROYECTO==36)		include("json_Item_036.php");
	}
	

	if(!isset($salidas["idsha"])){
		/*DE CONFIGURACION*/
		$cnf=$opciones["scons"]["cnf"];
		$hab_campo=$opciones["scons"]["hab_campo"];
		$ord_campo=$opciones["scons"]["ord_campo"];	
		$tit_campo=$opciones["scons"]["tit_campo"];	
		$id_campo=$opciones["scons"]["id_campo"];	
	
		$id_sha_n=md5($reg[$id_campo]);	
		$id_sha=encrip($reg[$id_campo]);
		
		$c_sha=encrip($cnf,2);
		$md_n=nuevo_item().$c_sha;
		$md=$id_sha.$c_sha;
		
		if($reg[$hab_campo]==0){
			$eliminar_a=$id_sha.$c_sha.$acc01;
			$eliminar_c=$btn_borrar;
		}
		else{
			$eliminar_a=$id_sha.$c_sha.$acc02;
			$eliminar_c=$btn_recuperar;
		}
		$salidas["idsha"]=$id_sha;
		$salidas["tipobox"]=4;
		$salidas["deshab"]=$reg[$hab_campo];
		
		$permiso=($PermisosA[$cnf]["P"]);
		/*Barra de Herramientas*/
		
		/*LINKS*/
		$salidas["titulo"]=imprimir($reg[$tit_campo]);
		$salidas["subtitulo"]="";
		if($permiso==1){
			$salidas["barra"]=array();
			
			$k=0;
			$salidas["barra"][$k]=array();
			$salidas["barra"][$k]["agrupar"]=1;
			$salidas["barra"][$k]["id"]="sBarra".$k;
			$salidas["barra"][$k]["contenido"]=array();
			
			$i=0;			
			
			if($opciones["scons"]["resumen"]==1){			
				$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
				$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_info;
				$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$md;
				$salidas["barra"][$k]["contenido"][$i]["pagina"]='/abstract';	
				$i++;
			}			
			
			$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
			$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$btn_editar;
			$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$id_sha.$c_sha;
			$salidas["barra"][$k]["contenido"][$i]["pagina"]='/setconfig';
			
			$i++;
			$salidas["barra"][$k]["contenido"][$i]["tipo"]=3;
			$salidas["barra"][$k]["contenido"][$i]["tipobtn"]=$eliminar_c;
			$salidas["barra"][$k]["contenido"][$i]["cod"]="md=".$eliminar_a;
			$salidas["barra"][$k]["contenido"][$i]["pagina"]='/delconfig';
		}
		
		/*DATOS LATERAL*/
			
		//$salidas["info"]=array();
		foreach ($opciones["cols"]["Cols"] as $i => $nCol){
			$salidas["info"][$i]["desc"]=$opciones["cols"]["Titulos"][$i];
			$salidas["info"][$i]["data"]=imprimir($reg[$nCol]);
		}
		
		/*COMPLEMENTOS*/
		$i=0;
		$salidas["cargaex"][$i]["nombre"]='txt-1113-0';
		$salidas["cargaex"][$i]["cnf"]=$cnf;
		$salidas["cargaex"][$i]["scnf"]=1;
		$salidas["cargaex"][$i]["id"]=$id_sha;
		$salidas["cargaex"][$i]["tp"]=1;	

	}
	return $salidas;	
}
?>
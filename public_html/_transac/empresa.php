<?php
require 		"phplib/s3/aws.phar";
use 			Aws\Common\Aws;	

$cnf=2;
$permiso=$PermisosA[$cnf]["P"]==1;
if(!$permiso) PrintErr(array('txt-MSJ16-0'));

$m=$result["m"];
if($m==1){	
	if(($result["nomb"]=="")) {$mensaje[0]='txt-MSJ1-0';$error=true;}
	if(!$error){
		try{  				
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();	
			
			//////////////LINK//////////////
			$link=cambiar_url($result["nomb"]);			
			
			$link_busc=$link."%";
			$s="SELECT adm_empresas.URL  AS LINK_PAGINA
				FROM adm_empresas 				
				WHERE (adm_empresas.URL LIKE :link OR adm_empresas.URL=:linkcompleto) AND adm_empresas.ID_MEMPRESA<>$_CLIENTE "; 
			$ReqB = $dbEmpresa->prepare($s); 
			$ReqB->bindParam(':link',$link_busc, PDO::PARAM_STR);
			$ReqB->bindParam(':linkcompleto',$link);
			$ReqB->execute();	
			$links=array();
			while($RegB = $ReqB->fetch()){				
				$caracteres=strlen($link)-strlen($RegB["LINK_PAGINA"]);
				$num_link=mb_substr($RegB["LINK_PAGINA"],$caracteres);
				if(is_numeric($num_link)) $links[]=$num_link;
				elseif($num_link==$RegB["LINK_PAGINA"]) $links[]=0;
			}
			
			if(count($links)>0) $link.=max($links)+1;
			//////////////////////////////////////////
				
			$s="UPDATE adm_empresas
				SET NOMB_MEMPRESA=:nomb,
				URL=:link
				WHERE ID_MEMPRESA=$_CLIENTE";			
			$req = $dbEmpresa->prepare($s);	 
			$req->bindParam(':nomb', $result["nomb"]);
			$req->bindParam(':link', $link);	
			$req->execute();


			$conteov=count($result["idioma"]);						
			if($conteov>0){	
				$s="REPLACE INTO adm_empresas_desc (ID_MEMPRESA,ID_IDIOMA,LEMA_EMPRESA,DESC_EMPRESA)
					VALUES ($_CLIENTE,:idioma,:lema,:desc)";
				$req = $dbEmpresa->prepare($s);	
				foreach ($result["idioma"] as $k => $Idioma){		
					$req ->bindParam(':idioma', $result["idioma"][$k]);
					$req ->bindParam(':lema', $result["lema"][$k]);	 
					$req ->bindParam(':desc', $result["desc"][$k]);	
					$req ->execute(); 		
				}
			}
			$dbEmpresa->commit();												  
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
		}
	}

}
elseif($m==2){	

	$UploadDeleteArgs=array(
		'S3_BUCKET'=>$_PARAMETROS["S3_BUCKET"]
	,	'PROYECTO'=>$_PROYECTO
	,	'EMPRESA'=>$_EMPRESA
	,	'MODULE'=>$cnf
	,	'OBJECT'=>$_CLIENTE.'_'.$result["imgtipo"]
	,	'TP_FILE'=>'img');

	if(($result["imagen"]==1)){		
		$tamano=$_FILES[$control_img]["size"];
		$ubicacion=$_FILES[$control_img]["tmp_name"];
		$finfo = finfo_open(FILEINFO_MIME_TYPE); 	
		$tipo=finfo_file($finfo, $ubicacion);	
		finfo_close($finfo);	
		$nombre=$_FILES[$control_img]["name"];
		if(($tamano<=$fmin) || ($tamano>$fmax))	{$mensaje[0]='txt-MSJ2-0';$error=true;}
		if(tipo_archivo($tipo)!=1)	{$mensaje[0]='txt-MSJ3-0';$error=true;}
		try{  				
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();	
			if(!$error){				
				$AwsS3 = Aws::factory(array('key' => $_PARAMETROS['S3_KEY'],'secret' => $_PARAMETROS['S3_SECRET']));
				UploadFiles($AwsS3,$control_img,$dbEmpresa,$UploadDeleteArgs,$Info);			
				$tamX=$Info['org']["width"];
				$tamY=$Info['org']["height"];

				$s="UPDATE adm_empresas_imagenes
					SET 
					F_NAME=:nombre,
					F_TYPE=:tipo,
					F_SIZE=:tamano,
					F_DIMX=:tamX,
					F_DIMY=:tamY
					WHERE ID_IMAGEN=:imgtipo AND ID_MEMPRESA=$_CLIENTE";				
				$req = $dbEmpresa->prepare($s);	 
				$req->bindParam(':nombre', $nombre);	
				$req->bindParam(':tipo', $tipo);	
				$req->bindParam(':tamano', $tamano);	
				$req->bindParam(':tamX', $tamX);	
				$req->bindParam(':tamY', $tamY);	
				$req->bindParam(':imgtipo',  $result["imgtipo"]);	
				$req->execute();
			}
		
			$dbEmpresa->commit();											  
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}
	}
	elseif(($result["imagen"]==3)){		
		$AwsS3 = Aws::factory(array('key' => $_PARAMETROS['S3_KEY'],'secret' => $_PARAMETROS['S3_SECRET']));
		DeleteFiles($AwsS3,$dbEmpresa,$UploadDeleteArgs);
		try{  				
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();	
			$s="UPDATE adm_empresas_imagenes
				SET 
				F_NAME=NULL,
				F_TYPE=NULL,
				F_SIZE=NULL,
				F_DIMX=NULL,
				F_DIMY=NULL
				WHERE ID_IMAGEN=:imgtipo AND ID_MEMPRESA=$_CLIENTE";				
			$req = $dbEmpresa->prepare($s);	
			$req->bindParam(':imgtipo',  $result["imgtipo"]);	
			$req->execute();		
			$dbEmpresa->commit();											  
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
		}
	}
}
elseif($m==3){	
	$tpPresenta=$result["tppres"];	
				
	$tamano=$_FILES[$control_img]["size"];
	$ubicacion=$_FILES[$control_img]["tmp_name"];

	$UploadDeleteArgs=array(
			'S3_BUCKET'=>$_PARAMETROS["S3_BUCKET"]
		,	'PROYECTO'=>$_PROYECTO
		,	'EMPRESA'=>$_EMPRESA
		,	'MODULE'=>0
		,	'OBJECT'=>$_CLIENTE
		,	'TP_FILE'=>'favico'
		,	'tipo'=>'ico');

	if(file_exists($ubicacion)&&$result["imagen"]==1){
		$finfo = finfo_open(FILEINFO_MIME_TYPE); 	
		$tipo=finfo_file($finfo, $ubicacion);	
		finfo_close($finfo);	
		$nombre=$_FILES[$control_img]["name"];
		//NOMBRE DE ARCHIVO
	
		if(($tamano<=$fmin) || ($tamano>$fmax))	{$mensaje[0]='txt-MSJ2-0';$error=true;}
		if(tipo_archivo($tipo,2)!=1)	{$mensaje[0]='txt-MSJ3-0';$error=true;}

		$AwsS3 = Aws::factory(array('key' => $_PARAMETROS['S3_KEY'],'secret' => $_PARAMETROS['S3_SECRET']));
		UploadFiles($AwsS3,$control_img,$dbEmpresa,$UploadDeleteArgs);			

	}
	elseif($result["imagen"]==3){
		$AwsS3 = Aws::factory(array('key' => $_PARAMETROS['S3_KEY'],'secret' => $_PARAMETROS['S3_SECRET']));
		DeleteFiles($AwsS3,$dbEmpresa,$UploadDeleteArgs);
	}
	else {$mensaje[0]='txt-MSJ9-0';$error=true;}
}
?>
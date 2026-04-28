<?php
require 		"phplib/s3/aws.phar";
use 			Aws\Common\Aws;	

$cnf=4;
$permiso=$PermisosA[$cnf]["P"]==1;
if(!$permiso) PrintErr(array('txt-MSJ16-0'));


if($m==1){	
	$i=0;
	while(isset($result["r$i"])&&(!$error)){
		if(($result["r$i"]==1)&&($result["c$i"]=="")) {$mensaje[0]='txt-MSJ1-0';$error=true;}
		$i++;
	}
	if(!$error){
		try{  				
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();	
			$i=0;	
			while(isset($result["n$i"])){	
				$id=isset($result["n$i"])?$result["n$i"]:0;			
				$s="UPDATE adm_configuracion_gral
					SET CONFIG_VALOR=:valor 
					WHERE ID_CONFIG=:id";				
				$req = $dbEmpresa->prepare($s);	 
				$req->bindParam(':valor', $result["c$i"]);	
				$req->bindParam(':id',$id);
				$req->execute();
				$i++;
			}			
			$dbEmpresa->commit();	
			$reload=true;										  
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}
	}

}
elseif($m==2){	
	$tpPresenta=$result["tppres"];	
				
	$tamano=$_FILES[$control_img]["size"];
	$ubicacion=$_FILES[$control_img]["tmp_name"];

	$UploadDeleteArgs=array(
				'S3_BUCKET'=>$_PARAMETROS["S3_BUCKET"]
			,	'PROYECTO'=>$_PROYECTO
			,	'EMPRESA'=>$_EMPRESA
			,	'MODULE'=>0
			,	'OBJECT'=>0);


	//NOMBRE DE ARCHIVO	
	if($tpPresenta==1){
		$UploadDeleteArgs["TP_FILE"]="LogoApp";
	}
	elseif($tpPresenta==2){
		$UploadDeleteArgs["TP_FILE"]="NoImageApp";
	}
	elseif($tpPresenta==3){
		$UploadDeleteArgs["TP_FILE"]="favico";
		$UploadDeleteArgs["tipo"]='icon';
	}

	$filedirec=$direccion."/".$nomb_file;


	if(file_exists($ubicacion)&&$result["imagen"]==1){
		$finfo = finfo_open(FILEINFO_MIME_TYPE); 	
		$tipo=finfo_file($finfo, $ubicacion);
		finfo_close($finfo);	
		$nombre=$_FILES[$control_img]["name"];		
		if($result["imagen"]==1&&($tpPresenta==1||$tpPresenta==2)){			
			if(($tamano<=$fmin) || ($tamano>$fmax))	{$mensaje[0]='txt-MSJ2-0';$error=true;}
			if(tipo_archivo($tipo)!=1)				{$mensaje[0]='txt-MSJ3-0';$error=true;}		
			if(!$error){			
				$AwsS3 = Aws::factory(array('key' => $_PARAMETROS['S3_KEY'],'secret' => $_PARAMETROS['S3_SECRET']));
				UploadFiles($AwsS3,$control_img,$dbEmpresa,$UploadDeleteArgs);		
			}
		}
		if($result["imagen"]==1&&($tpPresenta==3)){	
			
			if(($tamano<=$fmin) || ($tamano>$fmax))	{$mensaje[0]='txt-MSJ2-0';$error=true;}
			if(tipo_archivo($tipo,2)!=1)	{$mensaje[0]='txt-MSJ3-0';$error=true;}	
			$AwsS3 = Aws::factory(array('key' => $_PARAMETROS['S3_KEY'],'secret' => $_PARAMETROS['S3_SECRET']));
			UploadFiles($AwsS3,$control_img,$dbEmpresa,$UploadDeleteArgs);		
			
		}		
	}
	elseif($result["imagen"]==3&&($tpPresenta==1||$tpPresenta==2)){
		$AwsS3 = Aws::factory(array('key' => $_PARAMETROS['S3_KEY'],'secret' => $_PARAMETROS['S3_SECRET']));
		DeleteFiles($AwsS3,$dbEmpresa,$UploadDeleteArgs);
	}
	elseif($result["imagen"]==3&&($tpPresenta==3)){
		$AwsS3 = Aws::factory(array('key' => $_PARAMETROS['S3_KEY'],'secret' => $_PARAMETROS['S3_SECRET']));
		DeleteFiles($AwsS3,$dbEmpresa,$UploadDeleteArgs);
	}
	else {$mensaje[0]='txt-MSJ9-0';$error=true;}
}
?>
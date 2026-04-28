<?php
require 		"phplib/s3/aws.phar";
use 			Aws\Common\Aws;	
$cnf=10;
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
				$s="UPDATE adm_empresas_configuracion
					SET CONFIG_VALOR=:valor 
					WHERE ID_CONFIG=:id AND ID_MEMPRESA=$_CLIENTE";				
				$req = $dbEmpresa->prepare($s);	 
				$req->bindParam(':valor', $result["c$i"]);	
				$req->bindParam(':id',$id);
				$req->execute();
				$i++;
			}	

			$LemaCompany=$result["c0"];
			$DescCompany=$result["c11"];
			$s="UPDATE adm_empresas_desc
				SET				
					LEMA_EMPRESA=:LemaCompany
				,	DESC_EMPRESA=:DescCompany
				WHERE ID_MEMPRESA=$_CLIENTE";
			$req = $dbEmpresa->prepare($s);	 
			$req->bindParam(':LemaCompany', $LemaCompany);	
			$req->bindParam(':DescCompany', $DescCompany);	
			$req->execute();

			$s="UPDATE adm_empresas
				SET NOMB_MEMPRESA=:LemaCompany
				WHERE ID_MEMPRESA=$_CLIENTE";
			$req = $dbEmpresa->prepare($s);	 
			$req->bindParam(':LemaCompany', $LemaCompany);	
			$req->execute();

			$dbEmpresa->commit();	
			$reload=true;										  
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}
	}

}
elseif($m==3){	
	$tpPresenta=$result["tppres"];	
	//NOMBRE DE ARCHIVO

	$UploadDeleteArgs=array(
				'S3_BUCKET'=>$_PARAMETROS["S3_BUCKET"]
			,	'PROYECTO'=>$_PROYECTO
			,	'EMPRESA'=>$_EMPRESA
			,	'MODULE'=>0
			,	'OBJECT'=>$_CLIENTE);
	
	if($tpPresenta==1){
		$UploadDeleteArgs["TP_FILE"]="LogoClient";
	}
	elseif($tpPresenta==2){
		$UploadDeleteArgs["TP_FILE"]="BGClient";
	}
	elseif($tpPresenta==3){
		$UploadDeleteArgs["TP_FILE"]="PTClient";
	}

	if(($result["imagen"]==1)){		
		$tamano=$_FILES[$control_img]["size"];
		$ubicacion=$_FILES[$control_img]["tmp_name"];
		$finfo = finfo_open(FILEINFO_MIME_TYPE); 	
		$tipo=finfo_file($finfo, $ubicacion);	
		finfo_close($finfo);	
		$nombre=$_FILES[$control_img]["name"];
		if(($tamano<=$fmin) || ($tamano>$fmax))	{$mensaje[0]='txt-MSJ2-0';$error=true;}
		if(tipo_archivo($tipo)!=1)	{$mensaje[0]='txt-MSJ3-0';$error=true;}		
		if(!$error){			
			$AwsS3 = Aws::factory(array('key' => $_PARAMETROS['S3_KEY'],'secret' => $_PARAMETROS['S3_SECRET']));
			UploadFiles($AwsS3,$control_img,$dbEmpresa,$UploadDeleteArgs);		
		}
	}
	elseif(($result["imagen"]==3)){
		$AwsS3 = Aws::factory(array('key' => $_PARAMETROS['S3_KEY'],'secret' => $_PARAMETROS['S3_SECRET']));
		DeleteFiles($AwsS3,$dbEmpresa,$UploadDeleteArgs);
	}
	if($tpPresenta==1){
		if($result["imagen"]==1||$result["imagen"]==3){
			$s="UPDATE adm_empresas 
				SET ID_FILE=IFNULL((SELECT adm_files.ID_FILE FROM adm_files WHERE adm_files.F_MODULE=0 AND adm_files.ID_OBJECT=$_CLIENTE AND adm_files.F_SUB_EXT='org' AND adm_files.F_TP_FILE='LogoClient' LIMIT 1),0)
				WHERE ID_MEMPRESA=$_CLIENTE";
			$dbEmpresa->exec($s);
		}
	}
}
?>
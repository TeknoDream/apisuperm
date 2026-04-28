<?php
 $iresult=$result;
$cnf=10000;
$permiso=$PermisosA[$cnf]["P"]==1;
if(!$permiso) PrintErr(array('txt-MSJ16-0'));

$m=$result["m"];

$control=$control_img;
$tamano=$_FILES[$control]["size"];
$nombre=$_FILES[$control]["name"];
$ubicacion=$_FILES[$control]["tmp_name"];
$finfo = finfo_open(FILEINFO_MIME_TYPE); 	
$tipo=finfo_file($finfo, $ubicacion);	
finfo_close($finfo);
$dir_dest='/var/www/siie/temp/';
if(fValid($tipo,$_files_clase[2])){
	try{  				
		$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);			
		$dbEmpresa->beginTransaction();

		$s="INSERT INTO adm_implement (ID_USUARIO,ID_MEMPRESA,NOMBRE_ARCHIVO,FECHA,TIPO)
			VALUES($_USUARIO,$_CLIENTE,:nombre,UTC_TIMESTAMP(),:tipo)";
		$req = $dbEmpresa->prepare($s);		
		$req ->bindParam(':nombre', $nombre);
		$req ->bindParam(':tipo', $tipo);	 	
		$req ->execute();
		$id=$dbEmpresa->lastInsertId();
		$dbEmpresa->commit();
		$FileName=$_PROYECTO.'-'.$id;
		$folder=$dir_dest.'/'.$FileName;
		if(mkdir($folder, 0755)){
			$zip = new ZipArchive;
			$res = $zip->open($ubicacion);
			if ($res === TRUE) {
				$zip->extractTo($folder);
				$zip->close();
				$error=0;
			}
			else $error=9;
		}
	}
	catch (Exception $e){
		$dbEmpresa->rollBack();
		$err_str=$e->getMessage();
	}	
	@unlink($_FILES[$control]);
}
else $error=3;
$ControlFile=$folder."/comando.txt";
if (!file_exists($ControlFile)) $error=9;

if($error==0){
	$file = fopen($ControlFile, "r");
	$Comando=array();
	while(!feof($file)){
		$TextoCons=fgets($file);
		$PreComando=explode("-",$TextoCons);
		$PreSubComando=explode(":",$PreComando[1]);
		$Comando[$PreComando[0]][$PreSubComando[0]]=$PreSubComando[1];
	}
	fclose($file);

	if($_PROYECTO==1)		include 'implement_001.php';
	elseif($_PROYECTO==22)	include 'implement_022.php';
}
?>
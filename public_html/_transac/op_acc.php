<?php
require 		"phplib/s3/aws.phar";
use 			Aws\Common\Aws;	

$permiso=$PermisosA[$cnf]["P"]==1;
if(!$permiso) PrintErr(array('txt-MSJ16-0'));

if(($cnf==36)&&($det_plus==1)){
	try{  		
		$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
		$dbEmpresa->beginTransaction();	
		
		
		$sWhere=encrip_mysql('adm_empresas.ID_MEMPRESA');
		$s=$sqlCons[1][81]." WHERE $sWhere=:id LIMIT 1";		
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		$req->bindParam(':idioma', $_IDIOMA);
		$req->execute();	
		if($reg = $req->fetch()){
			$id=$reg["ID_MEMPRESA"];
			$s="UPDATE adm_usuarios_empresa
				SET LAST=0
				WHERE ID_USUARIO=$_USUARIO AND ID_MEMPRESA<>$id";									
			$req = $dbEmpresa->prepare($s);	
			$req->execute(); 

			$s="UPDATE adm_usuarios_empresa
				SET LAST=1
				WHERE ID_USUARIO=$_USUARIO AND ID_MEMPRESA=$id";									
			$req = $dbEmpresa->prepare($s);	
			$req->execute(); 
			$reload=true;
			$words=true;
	
		}				
		$dbEmpresa->commit();		
			
	}
	catch (Exception $e){
		$dbEmpresa->rollBack();
		$err_str=$e->getMessage();
	}	
}
elseif(($cnf==4)&&($det_plus==1)){ //CREACIÓN DE EMPRESA

	if($result["nmempresa"]==''
	||	$result["tempresa"]==0
	||	$result["sucursal"]==''
	||	$result["ciudad"]==0
	||	$result["direc"]=='')
		{$mensaje[0]='txt-MSJ1-0';$error=true;}
	//	


	include("phplib/appfunc.php");


	if(!$nuevo){
		$sWhere=encrip_mysql('adm_empresas.ID_MEMPRESA');
		$s=" SELECT adm_empresas.ID_MEMPRESA FROM adm_empresas  WHERE $sWhere=:id LIMIT 1";	
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		$req->execute();	
		if($reg = $req->fetch()) $id=$reg["ID_MEMPRESA"];
		else {$mensaje[0]='txt-MSJ9-0';$error=true;}
		$id_print=$id;
	}
	else $id_print="NULL";

	if($error==0){	
		$presult["id_print"]=$id_print;
		$presult["auto_change"]=1;

	

		$presult["imagen"]=$result["imagen"];
		$presult["imagen_f_name"]=$control_img;
		$presult["tempresa"]=$result["tempresa"];
		$presult["nmempresa"]=$result["nmempresa"];

		$presult["lat_u"]=$result["latu"];
		$presult["lon_u"]=$result["lonu"];
		$presult["desc"]='';
		$presult["scnomb"]=$result["sucursal"];
		$presult["direc"]=$result["direc"];
		$presult["tel"]=$result["tel"];
		$presult["id_ciudad"]=$result["ciudad"];
		$presult["zoom"]=$result["zoom"];

		$AwsS3 = Aws::factory(array('key' => $_PARAMETROS['S3_KEY'],'secret' => $_PARAMETROS['S3_SECRET']));	
		$id_empresa=CrearEmpresas($presult,$dbEmpresa,$AwsS3,$_USUARIO,$_IDIOMA,$_PARAMETROS,$_PROYECTO,$_EMPRESA);

		$reload=true;
		$words=true;
	}
	
}
elseif(($cnf==10001)&&(($det_plus>=2)&&($det_plus<=18))){
	if($det_plus==2){	
		try{  	
			include "phplib/mysql_valores.php";
			$dbMat->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbMat->beginTransaction();			
			
			$sWhere=encrip_mysql('adm_textos.ID_PALABRA');
			$sId="SELECT ID_PALABRA FROM adm_textos WHERE $sWhere=:idt AND ID_IDIOMA=:idioma  LIMIT 1";
			$reqId = $dbMat->prepare($sId);	
			$reqId ->bindParam(':idt', $id_sha_t);			
			$reqId ->bindParam(':idioma', $result["idioma"]);
			$reqId ->execute();
			if(!$regId = $reqId->fetch()) exit(0);
			
			$s="REPLACE INTO adm_textos (ID_PALABRA,ID_IDIOMA,PALABRA,TOOLTIP)
				VALUES (:id,:idioma,:palabra,:tooltip)";
			$req = $dbMat->prepare($s);	
			$req ->bindParam(':id', $regId["ID_PALABRA"]);			
			$req ->bindParam(':idioma', $result["idioma"]);
			$req ->bindParam(':palabra', $result["palabra"]);	 
			$req ->bindParam(':tooltip', $result["tooltip"]);			
			$req ->execute();				
			$dbMat->commit();	
			$id_sha=encrip($regId["ID_PALABRA"]);

		}
		catch (Exception $e){
			$dbMat->rollBack();
			$err_str=$e->getMessage();
		}		
	}
	elseif($det_plus==3){
		try{  	
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();			
			
			$sWhere=encrip_mysql('adm_empresas_imp_textos.TIPO_GRUPOPAL');
			$sWhere_t=encrip_mysql('adm_empresas_imp_textos.ID_PALABRA');			
			$sId="SELECT ID_PALABRA,TIPO_GRUPOPAL FROM adm_empresas_imp_textos WHERE $sWhere=:id AND $sWhere_t=:idt AND ID_IDIOMA=:idioma  LIMIT 1";
			$reqId = $dbEmpresa->prepare($sId);	
			$reqId ->bindParam(':id', $id_sha);		
			$reqId ->bindParam(':idt', $id_sha_t);		
			$reqId ->bindParam(':idioma', $result["idioma"]);
			$reqId ->execute();
			if(!$regId = $reqId->fetch()) exit(0);
			
			$s="REPLACE INTO adm_empresas_imp_textos (ID_PALABRA,ID_IDIOMA,TIPO_GRUPOPAL,PALABRA,TOOLTIP)
				VALUES (:idt,:idioma,:id,:palabra,:tooltip)";
			$req = $dbEmpresa->prepare($s);	
			$req ->bindParam(':idt', $regId["ID_PALABRA"]);	
			$req ->bindParam(':id', $regId["TIPO_GRUPOPAL"]);		
			$req ->bindParam(':idioma', $result["idioma"]);
			$req ->bindParam(':palabra', $result["palabra"]);	 
			$req ->bindParam(':tooltip', $result["tooltip"]);			
			$req ->execute();				
			$dbEmpresa->commit();	
			$id_sha=encrip($regId["ID_PALABRA"]);

		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}	
	}
	elseif($det_plus==4){
		try{  	
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();			
			
			$sWhere=encrip_mysql('adm_empresas_imp_oficios.TIPO_GRUPOPAL');
			$sWhere_t=encrip_mysql('adm_empresas_imp_oficios.ID_OFICIO');			
			$sId="SELECT ID_OFICIO,TIPO_GRUPOPAL FROM adm_empresas_imp_oficios WHERE $sWhere=:id AND $sWhere_t=:idt AND ID_IDIOMA=:idioma  LIMIT 1";
			$reqId = $dbEmpresa->prepare($sId);	
			$reqId ->bindParam(':id', $id_sha);		
			$reqId ->bindParam(':idt', $id_sha_t);		
			$reqId ->bindParam(':idioma', $result["idioma"]);
			$reqId ->execute();
			if(!$regId = $reqId->fetch()) exit(0);
			
			$s="REPLACE INTO adm_empresas_imp_oficios (ID_OFICIO,ID_IDIOMA,TIPO_GRUPOPAL,NOMB_OFICIO,DESC_OFICIO)
				VALUES (:idt,:idioma,:id,:nomb,:desc)";
			$req = $dbEmpresa->prepare($s);	
			$req ->bindParam(':idt', $regId["ID_OFICIO"]);	
			$req ->bindParam(':id', $regId["TIPO_GRUPOPAL"]);		
			$req ->bindParam(':idioma', $result["idioma"]);
			$req ->bindParam(':nomb', $result["nomb"]);	 
			$req ->bindParam(':desc', $result["desc"]);			
			$req ->execute();				
			$dbEmpresa->commit();	
			$id_sha=encrip($regId["ID_OFICIO"]);
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}	
	}
	elseif($det_plus==5){
		try{  	
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();			
			
			$sWhere=encrip_mysql('adm_empresas_imp_tfalla.TIPO_GRUPOPAL');
			$sWhere_t=encrip_mysql('adm_empresas_imp_tfalla.ID_FALLA');			
			$sId="SELECT ID_FALLA,TIPO_GRUPOPAL FROM adm_empresas_imp_tfalla WHERE $sWhere=:id AND $sWhere_t=:idt AND ID_IDIOMA=:idioma  LIMIT 1";
			$reqId = $dbEmpresa->prepare($sId);	
			$reqId ->bindParam(':id', $id_sha);		
			$reqId ->bindParam(':idt', $id_sha_t);		
			$reqId ->bindParam(':idioma', $result["idioma"]);
			$reqId ->execute();
			if(!$regId = $reqId->fetch()) exit(0);
			
			$s="REPLACE INTO adm_empresas_imp_tfalla (ID_FALLA,ID_IDIOMA,TIPO_GRUPOPAL,NOMB_FALLA,COMEN_FALLA)
				VALUES (:idt,:idioma,:id,:nomb,:desc)";
			$req = $dbEmpresa->prepare($s);	
			$req ->bindParam(':idt', $regId["ID_FALLA"]);	
			$req ->bindParam(':id', $regId["TIPO_GRUPOPAL"]);		
			$req ->bindParam(':idioma', $result["idioma"]);
			$req ->bindParam(':nomb', $result["nomb"]);	 
			$req ->bindParam(':desc', $result["desc"]);			
			$req ->execute();				
			$dbEmpresa->commit();	
			$id_sha=encrip($regId["ID_FALLA"]);
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}	
	}
	elseif($det_plus==6){
		try{  	
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();			
			
			$sWhere=encrip_mysql('adm_empresas_imp_ttrabajo.TIPO_GRUPOPAL');
			$sWhere_t=encrip_mysql('adm_empresas_imp_ttrabajo.ID_TTRABAJO');			
			$sId="SELECT ID_TTRABAJO,TIPO_GRUPOPAL FROM adm_empresas_imp_ttrabajo WHERE $sWhere=:id AND $sWhere_t=:idt AND ID_IDIOMA=:idioma  LIMIT 1";
			$reqId = $dbEmpresa->prepare($sId);	
			$reqId ->bindParam(':id', $id_sha);		
			$reqId ->bindParam(':idt', $id_sha_t);		
			$reqId ->bindParam(':idioma', $result["idioma"]);
			$reqId ->execute();
			if(!$regId = $reqId->fetch()) exit(0);
			
			$s="REPLACE INTO adm_empresas_imp_ttrabajo (ID_TTRABAJO,ID_IDIOMA,TIPO_GRUPOPAL,DESC_TTRABAJO,COMENT_TTRABAJO)
				VALUES (:idt,:idioma,:id,:nomb,:desc)";
			$req = $dbEmpresa->prepare($s);	
			$req ->bindParam(':idt', $regId["ID_TTRABAJO"]);	
			$req ->bindParam(':id', $regId["TIPO_GRUPOPAL"]);		
			$req ->bindParam(':idioma', $result["idioma"]);
			$req ->bindParam(':nomb', $result["nomb"]);	 
			$req ->bindParam(':desc', $result["desc"]);			
			$req ->execute();				
			$dbEmpresa->commit();	
			$id_sha=encrip($regId["ID_TTRABAJO"]);
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}	
	}
	elseif($det_plus==7){
		try{  	
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();			
			
			$sWhere=encrip_mysql('adm_empresas_v_names.TIPO_GRUPOPAL');
			$sWhere_t=encrip_mysql('adm_empresas_v_names.ID_VENTANA');			
			$sId="SELECT ID_VENTANA,TIPO_GRUPOPAL FROM adm_empresas_v_names WHERE $sWhere=:id AND $sWhere_t=:idt AND ID_IDIOMA=:idioma  LIMIT 1";
			$reqId = $dbEmpresa->prepare($sId);	
			$reqId ->bindParam(':id', $id_sha);		
			$reqId ->bindParam(':idt', $id_sha_t);		
			$reqId ->bindParam(':idioma', $result["idioma"]);
			$reqId ->execute();
			if(!$regId = $reqId->fetch()) exit(0);
			
			$s="REPLACE INTO adm_empresas_v_names (ID_VENTANA,ID_IDIOMA,TIPO_GRUPOPAL,VENTANA_NOMBRE,SCVENTANA)
				VALUES (:idt,:idioma,:id,:nomb,:abr)";
			$req = $dbEmpresa->prepare($s);	
			$req ->bindParam(':idt', $regId["ID_VENTANA"]);	
			$req ->bindParam(':id', $regId["TIPO_GRUPOPAL"]);		
			$req ->bindParam(':idioma', $result["idioma"]);
			$req ->bindParam(':nomb', $result["nomb"]);	 
			$req ->bindParam(':abr', $result["abr"]);			
			$req ->execute();				
			$dbEmpresa->commit();	
			$id_sha=encrip($regId["ID_VENTANA"]);
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}	
	}
	elseif($det_plus==8){
		try{  	
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();			
			
			$sWhere=encrip_mysql('adm_empresas_v_grupo_name.TIPO_GRUPOPAL');
			$sWhere_t=encrip_mysql('adm_empresas_v_grupo_name.ID_GVENTANA');			
			$sId="SELECT ID_GVENTANA,TIPO_GRUPOPAL FROM adm_empresas_v_grupo_name WHERE $sWhere=:id AND $sWhere_t=:idt AND ID_IDIOMA=:idioma  LIMIT 1";
			$reqId = $dbEmpresa->prepare($sId);	
			$reqId ->bindParam(':id', $id_sha);		
			$reqId ->bindParam(':idt', $id_sha_t);		
			$reqId ->bindParam(':idioma', $result["idioma"]);
			$reqId ->execute();
			if(!$regId = $reqId->fetch()) exit(0);
			
			$s="REPLACE INTO adm_empresas_v_grupo_name (ID_GVENTANA,ID_IDIOMA,TIPO_GRUPOPAL,DESC_GVENTANA)
				VALUES (:idt,:idioma,:id,:desc)";
			$req = $dbEmpresa->prepare($s);	
			$req ->bindParam(':idt', $regId["ID_GVENTANA"]);	
			$req ->bindParam(':id', $regId["TIPO_GRUPOPAL"]);		
			$req ->bindParam(':idioma', $result["idioma"]);
			$req ->bindParam(':desc', $result["desc"]);				
			$req ->execute();				
			$dbEmpresa->commit();	
			$id_sha=encrip($regId["ID_GVENTANA"]);
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}	
	}
	elseif($det_plus==9){
		try{  	
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();			
			
			$sWhere=encrip_mysql('adm_empresas_v_cont_names.TIPO_GRUPOPAL');
			$sWhere_t=encrip_mysql('adm_empresas_v_cont_names.ID_VENTANA');			
			$sId="SELECT ID_VENTANA,TIPO_GRUPOPAL FROM adm_empresas_v_cont_names WHERE $sWhere=:id AND $sWhere_t=:idt AND ID_IDIOMA=:idioma  LIMIT 1";
			$reqId = $dbEmpresa->prepare($sId);	
			$reqId ->bindParam(':id', $id_sha);		
			$reqId ->bindParam(':idt', $id_sha_t);		
			$reqId ->bindParam(':idioma', $result["idioma"]);
			$reqId ->execute();
			if(!$regId = $reqId->fetch()) exit(0);
			
			$s="REPLACE INTO adm_empresas_v_cont_names (ID_VENTANA,ID_IDIOMA,TIPO_GRUPOPAL,TITULO_VENTANA,STITULO_VENTANA)
				VALUES (:idt,:idioma,:id,:titulo,:stitulo)";
			$req = $dbEmpresa->prepare($s);	
			$req ->bindParam(':idt', $regId["ID_VENTANA"]);	
			$req ->bindParam(':id', $regId["TIPO_GRUPOPAL"]);		
			$req ->bindParam(':idioma', $result["idioma"]);
			$req ->bindParam(':titulo', $result["titulo"]);	 
			$req ->bindParam(':stitulo', $result["stitulo"]);			
			$req ->execute();				
			$dbEmpresa->commit();	
			$id_sha=encrip($regId["ID_VENTANA"]);
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}	
	}
	elseif($det_plus==10){
		try{  	
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();			
			
			$sWhere=encrip_mysql('adm_empresas_v_cont_campo_names.TIPO_GRUPOPAL');
			$sWhere_t=encrip_mysql('adm_empresas_v_cont_campo_names.ID_CAMPO');			
			$sId="SELECT ID_CAMPO,TIPO_GRUPOPAL FROM adm_empresas_v_cont_campo_names WHERE $sWhere=:id AND $sWhere_t=:idt AND ID_IDIOMA=:idioma  LIMIT 1";
			$reqId = $dbEmpresa->prepare($sId);	
			$reqId ->bindParam(':id', $id_sha);		
			$reqId ->bindParam(':idt', $id_sha_t);		
			$reqId ->bindParam(':idioma', $result["idioma"]);
			$reqId ->execute();
			if(!$regId = $reqId->fetch()) exit(0);
			
			$s="REPLACE INTO adm_empresas_v_cont_campo_names (ID_CAMPO,ID_IDIOMA,TIPO_GRUPOPAL,TITULO_CAMPO,TOOLTIP_CAMPO)
				VALUES (:idt,:idioma,:id,:nomb,:tooltip)";
			$req = $dbEmpresa->prepare($s);	
			$req ->bindParam(':idt', $regId["ID_CAMPO"]);	
			$req ->bindParam(':id', $regId["TIPO_GRUPOPAL"]);		
			$req ->bindParam(':idioma', $result["idioma"]);
			$req ->bindParam(':nomb', $result["nomb"]);	 
			$req ->bindParam(':tooltip', $result["tooltip"]);			
			$req ->execute();				
			$dbEmpresa->commit();	
			$id_sha=encrip($regId["ID_CAMPO"]);
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}	
	}
	elseif($det_plus==11){
		try{  	
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();			
			
			$sWhere=encrip_mysql('fac_estados_eq_desc.TIPO_GRUPOPAL');
			$sWhere_t=encrip_mysql('fac_estados_eq_desc.ID_ESTADO');			
			$sId="SELECT ID_ESTADO,TIPO_GRUPOPAL FROM fac_estados_eq_desc WHERE $sWhere=:id AND $sWhere_t=:idt AND ID_IDIOMA=:idioma  LIMIT 1";
			$reqId = $dbEmpresa->prepare($sId);	
			$reqId ->bindParam(':id', $id_sha);		
			$reqId ->bindParam(':idt', $id_sha_t);		
			$reqId ->bindParam(':idioma', $result["idioma"]);
			$reqId ->execute();
			if(!$regId = $reqId->fetch()) exit(0);
			
			$s="REPLACE INTO fac_estados_eq_desc (ID_ESTADO,ID_IDIOMA,TIPO_GRUPOPAL,ESTADO)
				VALUES (:idt,:idioma,:id,:estado)";
			$req = $dbEmpresa->prepare($s);	
			$req ->bindParam(':idt', $regId["ID_ESTADO"]);	
			$req ->bindParam(':id', $regId["TIPO_GRUPOPAL"]);		
			$req ->bindParam(':idioma', $result["idioma"]);
			$req ->bindParam(':estado', $result["estado"]);			
			$req ->execute();				
			$dbEmpresa->commit();	
			$id_sha=encrip($regId["ID_ESTADO"]);
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}	
	}
	elseif($det_plus==12){
		try{  	
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();			
			
			$sWhere=encrip_mysql('fac_estados_ot_desc.TIPO_GRUPOPAL');
			$sWhere_t=encrip_mysql('fac_estados_ot_desc.ID_ESTADO');			
			$sId="SELECT ID_ESTADO,TIPO_GRUPOPAL FROM fac_estados_ot_desc WHERE $sWhere=:id AND $sWhere_t=:idt AND ID_IDIOMA=:idioma  LIMIT 1";
			$reqId = $dbEmpresa->prepare($sId);	
			$reqId ->bindParam(':id', $id_sha);		
			$reqId ->bindParam(':idt', $id_sha_t);		
			$reqId ->bindParam(':idioma', $result["idioma"]);
			$reqId ->execute();
			if(!$regId = $reqId->fetch()) exit(0);
			
			$s="REPLACE INTO fac_estados_ot_desc (ID_ESTADO,ID_IDIOMA,TIPO_GRUPOPAL,ESTADO,ABR_EDO)
				VALUES (:idt,:idioma,:id,:estado,:abr)";
			$req = $dbEmpresa->prepare($s);	
			$req ->bindParam(':idt', $regId["ID_ESTADO"]);	
			$req ->bindParam(':id', $regId["TIPO_GRUPOPAL"]);		
			$req ->bindParam(':idioma', $result["idioma"]);
			$req ->bindParam(':estado', $result["estado"]);	
			$req ->bindParam(':abr', $result["abr"]);			
			$req ->execute();				
			$dbEmpresa->commit();	
			$id_sha=encrip($regId["ID_ESTADO"]);
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}	
	}
	elseif($det_plus==13){
		try{  	
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();			
			
			$sWhere=encrip_mysql('fac_tmmto_desc.TIPO_GRUPOPAL');
			$sWhere_t=encrip_mysql('fac_tmmto_desc.ID_TMMTO');			
			$sId="SELECT ID_TMMTO,TIPO_GRUPOPAL FROM fac_tmmto_desc WHERE $sWhere=:id AND $sWhere_t=:idt AND ID_IDIOMA=:idioma  LIMIT 1";
			$reqId = $dbEmpresa->prepare($sId);	
			$reqId ->bindParam(':id', $id_sha);		
			$reqId ->bindParam(':idt', $id_sha_t);		
			$reqId ->bindParam(':idioma', $result["idioma"]);
			$reqId ->execute();
			if(!$regId = $reqId->fetch()) exit(0);
			
			$s="REPLACE INTO fac_tmmto_desc (ID_TMMTO,ID_IDIOMA,TIPO_GRUPOPAL,DESC_TMMTO,COMENT_TMMTO,CONDICION,TIPO)
				VALUES (:idt,:idioma,:id,:desc,:coment,:cond,:tipo)";
			$req = $dbEmpresa->prepare($s);	
			$req ->bindParam(':idt', $regId["ID_TMMTO"]);	
			$req ->bindParam(':id', $regId["TIPO_GRUPOPAL"]);		
			$req ->bindParam(':idioma', $result["idioma"]);
			$req ->bindParam(':desc', $result["desc"]);	
			$req ->bindParam(':coment', $result["coment"]);
			$req ->bindParam(':cond', $result["cond"]);	
			$req ->bindParam(':tipo', $result["tipo"]);					
			$req ->execute();				
			$dbEmpresa->commit();	
			$id_sha=encrip($regId["ID_TMMTO"]);
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}	
	}
	/**/
	elseif($det_plus==14){
		try{  	
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();			
			
			if($result["global"]=='true'){
				include "phplib/mysql_valores.php";
				$sWhere=encrip_mysql('adm_textos.ID_PALABRA');
				$sId="SELECT ID_PALABRA FROM adm_textos WHERE $sWhere=:idt AND ID_IDIOMA=:idioma  LIMIT 1";
				$reqId = $dbMat->prepare($sId);	
				$reqId ->bindParam(':idt', $id_sha_t);			
				$reqId ->bindParam(':idioma', $result["idioma"]);
				$reqId ->execute();
				if(!$regId = $reqId->fetch()) exit(0);
			}
			else{
				$sWhere='adm_empresas_imp_textos.TIPO_GRUPOPAL';
				$sWhere_t=encrip_mysql('adm_empresas_imp_textos.ID_PALABRA');			
				$sId="SELECT ID_PALABRA,TIPO_GRUPOPAL FROM adm_empresas_imp_textos WHERE $sWhere=:id AND $sWhere_t=:idt AND ID_IDIOMA=:idioma  LIMIT 1";
				$reqId = $dbEmpresa->prepare($sId);	
				$reqId ->bindParam(':id', $_GCLIENTE);		
				$reqId ->bindParam(':idt', $id_sha_t);		
				$reqId ->bindParam(':idioma', $result["idioma"]);
				$reqId ->execute();
				if(!$regId = $reqId->fetch()) exit(0);				
			}
			$s="REPLACE INTO adm_textos (ID_PALABRA,ID_IDIOMA,ID_MEMPRESA,PALABRA,TOOLTIP)
				VALUES (:idt,:idioma,:id,:palabra,:tooltip)";
			$req = $dbEmpresa->prepare($s);	
			$req ->bindParam(':idt', $regId["ID_PALABRA"]);	
			$req ->bindParam(':id', $_CLIENTE);		
			$req ->bindParam(':idioma', $result["idioma"]);
			$req ->bindParam(':palabra', $result["palabra"]);	 
			$req ->bindParam(':tooltip', $result["tooltip"]);			
			$req ->execute();				
			$dbEmpresa->commit();	
			$id_sha=encrip($regId["ID_PALABRA"]);


		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}	
	}
	elseif($det_plus==15){
		try{  	
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();			
			
			$sWhere='adm_empresas_v_names.TIPO_GRUPOPAL';
			$sWhere_t=encrip_mysql('adm_empresas_v_names.ID_VENTANA');			
			$sId="SELECT ID_VENTANA,TIPO_GRUPOPAL FROM adm_empresas_v_names WHERE $sWhere=:id AND $sWhere_t=:idt AND ID_IDIOMA=:idioma  LIMIT 1";
			$reqId = $dbEmpresa->prepare($sId);	
			$reqId ->bindParam(':id',$_GCLIENTE);
			$reqId ->bindParam(':idt', $id_sha_t);		
			$reqId ->bindParam(':idioma', $result["idioma"]);
			$reqId ->execute();
			if(!$regId = $reqId->fetch()) exit(0);
			
			$s="REPLACE INTO adm_ventanas_names (ID_VENTANA,ID_IDIOMA,ID_MEMPRESA,VENTANA_NOMBRE,SCVENTANA)
				VALUES (:idt,:idioma,:id,:nomb,:abr)";
			$req = $dbEmpresa->prepare($s);	
			$req ->bindParam(':idt', $regId["ID_VENTANA"]);	
			$req ->bindParam(':id', $_CLIENTE);
			$req ->bindParam(':idioma', $result["idioma"]);
			$req ->bindParam(':nomb', $result["nomb"]);	 
			$req ->bindParam(':abr', $result["abr"]);			
			$req ->execute();				
			$dbEmpresa->commit();	
			$id_sha=encrip($regId["ID_VENTANA"]);
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}	
	}
	elseif($det_plus==16){
		try{  	
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();			
			
			$sWhere='adm_empresas_v_grupo_name.TIPO_GRUPOPAL';
			$sWhere_t=encrip_mysql('adm_empresas_v_grupo_name.ID_GVENTANA');			
			$sId="SELECT ID_GVENTANA,TIPO_GRUPOPAL FROM adm_empresas_v_grupo_name WHERE $sWhere=:id AND $sWhere_t=:idt AND ID_IDIOMA=:idioma  LIMIT 1";
			$reqId = $dbEmpresa->prepare($sId);	
			$reqId ->bindParam(':id', $_GCLIENTE);
			$reqId ->bindParam(':idt', $id_sha_t);		
			$reqId ->bindParam(':idioma', $result["idioma"]); 
			$reqId ->execute();
			if(!$regId = $reqId->fetch()) exit(0);
			
			$s="REPLACE INTO adm_ventanas_grupo_name (ID_GVENTANA,ID_IDIOMA,ID_MEMPRESA,DESC_GVENTANA)
				VALUES (:idt,:idioma,:id,:desc)";
			$req = $dbEmpresa->prepare($s);	
			$req ->bindParam(':idt', $regId["ID_GVENTANA"]);	
			$req ->bindParam(':id', $_CLIENTE);
			$req ->bindParam(':idioma', $result["idioma"]);
			$req ->bindParam(':desc', $result["desc"]);				
			$req ->execute();				
			$dbEmpresa->commit();	
			$id_sha=encrip($regId["ID_GVENTANA"]);
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}	
	}
	elseif($det_plus==17){
		try{  	
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();			
			
			$sWhere='adm_empresas_v_cont_names.TIPO_GRUPOPAL';
			$sWhere_t=encrip_mysql('adm_empresas_v_cont_names.ID_VENTANA');			
			$sId="SELECT ID_VENTANA,TIPO_GRUPOPAL FROM adm_empresas_v_cont_names WHERE $sWhere=:id AND $sWhere_t=:idt AND ID_IDIOMA=:idioma  LIMIT 1";
			$reqId = $dbEmpresa->prepare($sId);	
			$reqId ->bindParam(':id', $_GCLIENTE);	
			$reqId ->bindParam(':idt', $id_sha_t);		
			$reqId ->bindParam(':idioma', $result["idioma"]);
			$reqId ->execute();
			if(!$regId = $reqId->fetch()) exit(0);
			
			$s="REPLACE INTO adm_ventanas_cont_names (ID_VENTANA,ID_IDIOMA,ID_MEMPRESA,TITULO_VENTANA,STITULO_VENTANA)
				VALUES (:idt,:idioma,:id,:titulo,:stitulo)";
			$req = $dbEmpresa->prepare($s);	
			$req ->bindParam(':idt', $regId["ID_VENTANA"]);	
			$req ->bindParam(':id', $_CLIENTE);
			$req ->bindParam(':idioma', $result["idioma"]);
			$req ->bindParam(':titulo', $result["titulo"]);	 
			$req ->bindParam(':stitulo', $result["stitulo"]);			
			$req ->execute();				
			$dbEmpresa->commit();	
			$id_sha=encrip($regId["ID_VENTANA"]);
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}	
	}
	elseif($det_plus==18){
		try{  	
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();		
			
			$sWhere='adm_empresas_v_cont_campo_names.TIPO_GRUPOPAL';
			$sWhere_t=encrip_mysql('adm_empresas_v_cont_campo_names.ID_CAMPO');			
			$sId="SELECT ID_CAMPO,TIPO_GRUPOPAL FROM adm_empresas_v_cont_campo_names WHERE $sWhere=:id AND $sWhere_t=:idt AND ID_IDIOMA=:idioma  LIMIT 1";
			$reqId = $dbEmpresa->prepare($sId);	
			$reqId ->bindParam(':id', $_GCLIENTE);	
			$reqId ->bindParam(':idt', $id_sha_t);		
			$reqId ->bindParam(':idioma', $result["idioma"]);
			$reqId ->execute();
			if(!$regId = $reqId->fetch()) exit(0);
			
			$s="REPLACE INTO adm_ventanas_cont_campo_names (ID_CAMPO,ID_IDIOMA,ID_MEMPRESA,TITULO_CAMPO,TOOLTIP_CAMPO)
				VALUES (:idt,:idioma,:id,:nomb,:tooltip)";
			$req = $dbEmpresa->prepare($s);	
			$req ->bindParam(':idt', $regId["ID_CAMPO"]);	
			$req ->bindParam(':id', $_CLIENTE);
			$req ->bindParam(':idioma', $result["idioma"]);
			$req ->bindParam(':nomb', $result["nomb"]);	 
			$req ->bindParam(':tooltip', $result["tooltip"]);			
			$req ->execute();				
			$dbEmpresa->commit();	
			$id_sha=encrip($regId["ID_CAMPO"]);
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}	
	}
	ActVersions($dbEmpresa,$sqlCons,$_CLIENTE,'x_textos');
}
elseif(($cnf==4)&&($det_plus==2)){
	if($det_plus==2){	
		if(!$nuevo){
			$sWhere=encrip_mysql('adm_landing.ID_LAND');
			$s="SELECT ID_LAND AS ID FROM adm_landing WHERE $sWhere=:id LIMIT 1";
			$req = $dbEmpresa->prepare($s); 
			$req->bindParam(':id', $id_sha);
			$req->execute();	
			if($reg = $req->fetch()) $id=$reg["ID"];
			else {$mensaje[0]='txt-MSJ9-0';$error=true;}		
			$id_print=$id;
		}
		else{
			$s="SELECT ID_LAND AS ID FROM adm_landing ORDER BY ID_LAND DESC LIMIT 1";
			$req = $dbEmpresa->prepare($s); 
			$req->execute();	
			if($reg = $req->fetch()) $id=$reg["ID"]+1;
			else 					 $id=1;
			$id_print=$id;
		}

		try{  	
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();			
			
			if(!$nuevo){
				$s="REPLACE INTO adm_landing (ID_LAND,ID_IDIOMA,TITULO,ETI_DIV,TEXTO)
					VALUES ($id_print,:idioma,:titulo,:etiqueta,:texto)";
				$req = $dbEmpresa->prepare($s);			
				$req ->bindParam(':idioma', $result["idioma"]);
			}
			else{
				$s="INSERT INTO adm_landing (ID_LAND,ID_IDIOMA,TITULO,ETI_DIV,TEXTO)
					 (
					 	SELECT $id_print,ID_IDIOMA,:titulo,:etiqueta,:texto
					 	FROM fac_idioma
					 )";
				$req = $dbEmpresa->prepare($s);	
			}
			$req ->bindParam(':titulo', $result["titulo"]);	 
			$req ->bindParam(':etiqueta', $result["etiqueta"]);	
			$req ->bindParam(':texto', $result["texto"]);		
			$req ->execute();				
			$dbEmpresa->commit();	
			
			$id_sha=encrip($id);

			$salidas["parAd"]["md"]=$id_sha.encrip($cnf,2).$id_sha.'002';
			$salidas["parAd"]["idlbl"]=$id_print;
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}		
	}
	ActVersions($dbEmpresa,$sqlCons,$_CLIENTE,'x_textos');
}
elseif($cnf==2){
	if($det_plus==1){	
		if(!$nuevo){
			$sWhere=encrip_mysql('adm_empresas_url.ID_URLS');
			$s="SELECT ID_URLS AS ID FROM adm_empresas_url WHERE $sWhere=:id LIMIT 1";
			$req = $dbEmpresa->prepare($s); 
			$req->bindParam(':id', $id_sha);
			$req->execute();	
			if($reg = $req->fetch()) $id=$reg["ID"];
			else {$mensaje[0]='txt-MSJ9-0';$error=true;}		
			$id_print=$id;
		}
		else $id_print="NULL";

		if(!$error){
			$s="SELECT ID_URLS FROM adm_empresas_url WHERE adm_empresas_url.URL=:url";
			if(!$nuevo) $s.=" AND adm_empresas_url.ID_URLS<>$id_print ";
			$req = $dbEmpresa->prepare($s); 
			$req ->bindParam(':url', $result["url"]);
			$req->execute();	
			if($reg = $req->fetch()) {$mensaje[0]='txt-MSJ21-0';$error=true;}
		}
		if(!$error){
			try{  	
				$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
				$dbEmpresa->beginTransaction();			
			
				$s="INSERT INTO adm_empresas_url (ID_URLS,URL,ID_MEMPRESA)
					VALUES ($id_print,:url,$_CLIENTE)
					ON DUPLICATE KEY UPDATE
					URL=:url";
				$req = $dbEmpresa->prepare($s);			
				$req ->bindParam(':url', $result["url"]);	
				$req ->execute();					
				if($nuevo) $id=$dbEmpresa->lastInsertId();
				$id_sha=encrip($id);
				$dbEmpresa->commit();

				
				$salidas["parAd"]["md"]=$id_sha.encrip($cnf,2).$id_sha.'001';
				$salidas["parAd"]["md_del"]=$id_sha.encrip($cnf,2).$acc03;
				$salidas["parAd"]["idlbl"]=$id_print;
			}
			catch (Exception $e){
				$dbEmpresa->rollBack();
				$err_str=$e->getMessage();
			}
		}		
	}
	elseif($det_plus==2){	
		if(!$nuevo){
			$sWhere_1=encrip_mysql('adm_empresas_landing.ID_LAND');
			$sWhere_2=encrip_mysql('adm_landing.ID_LAND');
			$s="(SELECT ID_LAND AS ID FROM adm_empresas_landing 
							WHERE $sWhere_1=:id AND ID_MEMPRESA=$_CLIENTE LIMIT 1)
				UNION
				(SELECT ID_LAND AS ID FROM adm_landing 
								WHERE $sWhere_2=:id LIMIT 1)";
			$req = $dbEmpresa->prepare($s); 
			$req->bindParam(':id', $id_sha);
			$req->execute();	
			if($reg = $req->fetch()) $id=$reg["ID"];
			else {$mensaje[0]='txt-MSJ9-0';$error=true;}		
			$id_print=$id;
		}
		else{
			$s="SELECT ID_LAND AS ID FROM adm_empresas_landing WHERE ID_MEMPRESA=$_CLIENTE ORDER BY ID_LAND DESC LIMIT 1";
			$req = $dbEmpresa->prepare($s); 
			$req->execute();	
			if($reg = $req->fetch()) $id=$reg["ID"]+1;
			else 					 $id=1;
			$id_print=encrip($id);
		}

		try{  	
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();			
			
			if(!$nuevo){
				$s="REPLACE INTO adm_empresas_landing (ID_LAND,ID_IDIOMA,TITULO,ETI_DIV,TEXTO,ID_MEMPRESA,FECHA_INI)
					VALUES ($id_print,:idioma,:titulo,:etiqueta,:texto,$_CLIENTE,UTC_TIMESTAMP())";
				$req = $dbEmpresa->prepare($s);			
				$req ->bindParam(':idioma', $result["idioma"]);
			}
			else{
				$s="INSERT INTO adm_landing (ID_LAND,ID_IDIOMA,TITULO,ETI_DIV,TEXTO,ID_MEMPRESA,FECHA_INI)
					 (
					 	SELECT $id_print,ID_IDIOMA,:titulo,:etiqueta,:texto,$_CLIENTE,UTC_TIMESTAMP()
					 	FROM fac_idioma
					 )";
				$req = $dbEmpresa->prepare($s);	
			}
			$req ->bindParam(':titulo', $result["titulo"]);	 
			$req ->bindParam(':etiqueta', $result["etiqueta"]);	
			$req ->bindParam(':texto', $result["texto"]);		
			$req ->execute();				
			$dbEmpresa->commit();	
			
			$id_sha=encrip($id);

			$salidas["parAd"]["md"]=$id_sha.encrip($cnf,2).$id_sha.'002';
			$salidas["parAd"]["idlbl"]=$id_print;
			CargarTextos($dbEmpresa,true);
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}		
		ActVersions($dbEmpresa,$sqlCons,$_CLIENTE,'x_textos');
	}
}
elseif($cnf==10000&&$det_plus==1){
	try{  		
		$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
		$dbEmpresa->beginTransaction();	
		
		$sWhere=encrip_mysql('adm_usuarios.ID_USUARIO');
    	$s=$sqlCons[1][0]." WHERE $sWhere=:id LIMIT 1";	
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		$req->execute();	
		if($reg = $req->fetch()){

			$id=$reg["ID_USUARIO"];
			$correo=$reg['CORREO_U'];
			$name=$reg["NOMBRE_U"].' '.$reg["APELLIDO_U"];
			$password=$result['password'];

			$s="UPDATE adm_usuarios
				SET PASSWORD_U=SHA1(:password)
				WHERE ID_USUARIO=:id LIMIT 1";									
			$req = $dbEmpresa->prepare($s);	
			$req->bindParam(':password', $password);
			$req->bindParam(':id', $id);
			$req->execute(); 

			$hide['result']=$result;

			if($result['notificar']==1){
				try{
					CuerpoMail($Email,$_PROYECTO,$_EMPRESA,$_CLIENTE,$_IDIOMA,$_TZ,$_GCLIENTE,$_USUARIO,$_GRUPO,$_PARAMETROS);	
					/*******SEND EMAIL***********/						
					$to=array();
					$to[0]["mail"]=$correo;
					$to[0]["name"]=$name;

					$Asunto=$Email[1][9]['title'];
					$Alt=$Email[1][9]['alt'];

					$html_cont=sprintf($Email[1][9]['body']
						,$name
						,$password);
					$rtamail=send_email_srv($_PARAMETROS,$Asunto,$html_cont,$to,array(),array(),true,$Alt);
					/*******SEND EMAIL***********/

					$hide['rta']=$rtamail;

				}
				catch (Exception $e){			
				}
			}	
		}				
		$dbEmpresa->commit();		
			
	}
	catch (Exception $e){
		$dbEmpresa->rollBack();
		$err_str=$e->getMessage();
	}
	
}
else{
	if($_PROYECTO==1)			include("op_acc_001.php"); //ROCKETMP	
	elseif($_PROYECTO==8)		include("op_acc_008.php"); //FALCONCRM
	elseif($_PROYECTO==10)		include("op_acc_010.php"); //TUPYME
	elseif($_PROYECTO==13)		include("op_acc_013.php"); //CIUDAD TRAVEL
	elseif($_PROYECTO==14)		include("op_acc_014.php"); //EVENTOS CCB
	elseif($_PROYECTO==16)		include("op_acc_016.php"); //DISPONIBLES
	elseif($_PROYECTO==19)		include("op_acc_019.php"); //SCA
	elseif($_PROYECTO==20)		include("op_acc_020.php"); //Appetitos
	elseif($_PROYECTO==21)		include("op_acc_021.php"); //Innova
	elseif($_PROYECTO==22)		include("op_acc_022.php"); //RK
	elseif($_PROYECTO==23)		include("op_acc_023.php"); //VIGA
	elseif($_PROYECTO==24)		include("op_acc_024.php"); //MARCA GPS
	elseif($_PROYECTO==25)		include("op_acc_025.php"); //ER
	elseif($_PROYECTO==26)		include("op_acc_026.php"); //Mis Veterinarias
	elseif($_PROYECTO==27)		include("op_acc_027.php"); //Cancheros
	elseif($_PROYECTO==28)		include("op_acc_028.php"); //Petrozones
	elseif($_PROYECTO==29)		include("op_acc_029.php"); //Asking Room
	elseif($_PROYECTO==31)		include("op_acc_031.php"); //QR
	elseif($_PROYECTO==32)		include("op_acc_032.php"); //Super Maestros
	elseif($_PROYECTO==38)		include("op_acc_038.php"); //Super Maestros
	elseif($_PROYECTO==39)		include("op_acc_039.php"); //cajasan
	elseif($_PROYECTO==40)		include("op_acc_040.php"); //alkilautos
	elseif($_PROYECTO==41)		include("op_acc_041.php"); //7points
	elseif($_PROYECTO==42)		include("op_acc_042.php"); //InfoEventos
	elseif($_PROYECTO==43)		include("op_acc_043.php"); //TeloEntrego
	elseif($_PROYECTO==45)		include("op_acc_045.php"); //LicorTap
}
?>
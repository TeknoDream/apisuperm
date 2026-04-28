<?php
function MailConfirm($dbEmpresa,$to=array(),$cc=array(),$bcc=array()){
	include("variables_se.php");
	/*REGISTRO DE ENVÍO*/
	$sMail="INSERT INTO log_mail_send (ID_USUARIO,FECHA_SEND,MAIL_SEND,NAME_SEND)
						VALUES($_USUARIO,UTC_TIMESTAMP(),:mail,:send)";
	$reqMail = $dbEmpresa->prepare($sMail);
	$reqMail->bindParam(':mail', $_PARAMETROS["M_FROMMAIL"]);
	$reqMail->bindParam(':send', $_PARAMETROS["M_FROMNAME"]);
	$reqMail->execute();	
	$idMail=$dbEmpresa->lastInsertId();


	if(count($to)>0){
		$sMail="INSERT INTO log_mail_send_list (ID_SEND,TIPO_SEND,MAIL_SEND,NAME_SEND)
							VALUES($idMail,'TO',:mail,:send)";
		$reqMail = $dbEmpresa->prepare($sMail);	
		for($i=0;$i<count($to);$i++){			
			$reqMail->bindParam(':mail', $to[$i]["mail"]);
			$reqMail->bindParam(':send', $to[$i]["name"]);
			$reqMail->execute();
		}
		if(count($cc)>0){
			$sMail="INSERT INTO log_mail_send_list (ID_SEND,TIPO_SEND,MAIL_SEND,NAME_SEND)
								VALUES($idMail,'CC',:mail,:send)";
			$reqMail = $dbEmpresa->prepare($sMail);	
			for($i=0;$i<count($cc);$i++){			
				$reqMail->bindParam(':mail', $cc[$i]["mail"]);
				$reqMail->bindParam(':send', $cc[$i]["name"]);
				$reqMail->execute();
			}
		}
		if(count($bcc)>0){
			$sMail="INSERT INTO log_mail_send_list (ID_SEND,TIPO_SEND,MAIL_SEND,NAME_SEND)
								VALUES($idMail,'BCC',:mail,:send)";
			$reqMail = $dbEmpresa->prepare($sMail);	
			for($i=0;$i<count($bcc);$i++){			
				$reqMail->bindParam(':mail', $bcc[$i]["mail"]);
				$reqMail->bindParam(':send', $bcc[$i]["name"]);
				$reqMail->execute();
			}
		}
	}
	return($idMail);
}
function ActContador($dbEmpresa,$idEquipo){
	

}
function CrearEmpresas(&$result,$dbEmpresa,&$AwsS3,$_USUARIO,$_IDIOMA,$_PARAMETROS,$_PROYECTO,$_EMPRESA){

	include "variables.php";
	//include "variables_se.php";




	$auto_change=$result["auto_change"];

	$id_print=$result["id_print"];	
	$nuevo=$id_print=="NULL";


	//////////////LINK//////////////
	$link_emp=cambiar_url($result["nmempresa"],2);			
	$link = rawurlencode($link_emp);
	$link_busc=$link."%";
	$s="SELECT adm_empresas.URL  AS LINK_PAGINA
		FROM adm_empresas 				
		WHERE (adm_empresas.URL LIKE :link OR adm_empresas.URL=:linkcompleto)".(!$nuevo?" AND adm_empresas.ID_MEMPRESA<>$id_print":""); 
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
	//////////////LINK//////////////

	//CREACION DE EMPRESA//
	$s="INSERT INTO adm_empresas (ID_MEMPRESA,ID_TIPOE,NOMB_MEMPRESA,URL,ID_USUARIO,START)
			VALUES($id_print,:tempresa,:nmempresa,:link,$_USUARIO,UTC_TIMESTAMP())
		ON DUPLICATE KEY UPDATE
			ID_TIPOE=:tempresa,
			NOMB_MEMPRESA=:nmempresa,
			URL=:link";
	$EReq = $dbEmpresa->prepare($s); 
	$EReq->bindParam(':tempresa', $result["tempresa"]);
	$EReq->bindParam(':nmempresa', $result["nmempresa"]);
	$EReq->bindParam(':link', $link);
	$EReq->execute();
	if($nuevo) 	$id_empresa=$dbEmpresa->lastInsertId();
	else 		$id_empresa=$id_print;

	if($nuevo){
		/*CREA LOS GRUPOS*/		
		$s="SELECT ID_GRUPO,DESC_GRUPO,COMEN_GRUPO,ADM_GRUPO
			FROM adm_empresas_g_grupos 
			WHERE ID_IDIOMA=:id_idioma";
		$reqG = $dbEmpresa->prepare($s);	 
		$reqG->bindParam(':id_idioma', $_IDIOMA);
		$reqG->execute();
		while($regG = $reqG->fetch()){			
			$s="INSERT INTO adm_grupos
					(DESC_GRUPO,COMEN_GRUPO,ADM_GRUPO,ID_MEMPRESA)
				VALUES (:DESC_GRUPO,:COMEN_GRUPO,:ADM_GRUPO,$id_empresa)";
			$req = $dbEmpresa->prepare($s);
			$req->bindParam(':DESC_GRUPO', $regG["DESC_GRUPO"]);
			$req->bindParam(':COMEN_GRUPO', $regG["COMEN_GRUPO"]);
			$req->bindParam(':ADM_GRUPO', $regG["ADM_GRUPO"]);
			$req->execute();
			$id_grupo=$dbEmpresa->lastInsertId();
			if($regG["ADM_GRUPO"]==1) $id_grupoU=$id_grupo;
			if($regG["ADM_GRUPO"]==2) $id_grupoSU=$id_grupo;	
			if($regG["ADM_GRUPO"]==3) $id_grupoPROD=$id_grupo;	
			$s="INSERT INTO adm_grupos_ven
					(ID_GRUPO,ID_VENTANA,PERMISO_GRUPOVEN)
				(SELECT $id_grupo,ID_VENTANA,PERMISO_GRUPOVEN FROM adm_empresas_g_grupos_ven WHERE ID_GRUPO=".$regG["ID_GRUPO"].")";
			$dbEmpresa->exec($s);
		}	
		
		//CREA CONFIGURACION
		$s="INSERT INTO adm_empresas_configuracion
			(ID_CONFIG,ID_MEMPRESA,CONFIG_VALOR)
		(SELECT ID_CONFIG,$id_empresa,CONFIG_VALOR FROM adm_configuracion)";
		$dbEmpresa->exec($s);

		//TIPO DE EMPRESA
		$s="UPDATE adm_empresas_configuracion
				SET CONFIG_VALOR=(SELECT adm_empresas_tipo_desc.NOMB_TIPOE FROM adm_empresas_tipo_desc WHERE adm_empresas_tipo_desc.ID_TIPOE=:tempresa AND adm_empresas_tipo_desc.ID_IDIOMA=$_IDIOMA)
			WHERE (ID_CONFIG=27 OR ID_CONFIG=1002) AND ID_MEMPRESA=$id_empresa";
		$req = $dbEmpresa->prepare($s);
		$req->bindParam(':tempresa', $result["tempresa"]);
		$req->execute();

		
		//AREAS
		$s="SELECT NOMB_RESP,PPAL
			FROM adm_empresas_imp_cresp 
			WHERE ID_IDIOMA=:id_idioma";
		$reqG = $dbEmpresa->prepare($s);	 
		$reqG->bindParam(':id_idioma', $_IDIOMA);
		$reqG->execute();
		while($regG = $reqG->fetch()){			
			//VINCULAR CON GRUPO ADMINISTRADOR
			if($regG["PPAL"]==1)
				$scnomb=$result["scnomb"]==""?$regG["NOMB_RESP"]:$result["scnomb"];
			else
				$scnomb=$regG["NOMB_RESP"];
			//////////////LINK//////////////
			$link=cambiar_url($scnomb,2);			
			
			$link_busc=$link."%";
			$sWhere=encrip_mysql('s_cresp.ID_ITEM');	
			$s="SELECT s_cresp.SLUG_RESP  AS LINK_PAGINA
				FROM s_cresp 
				WHERE (s_cresp.SLUG_RESP LIKE :link OR s_cresp.SLUG_RESP=:linkcompleto) AND s_cresp.ID_MEMPRESA=$id_empresa"; 
			$ReqB = $dbEmpresa->prepare($s); 
			$ReqB->bindParam(':link',$link_busc, PDO::PARAM_STR);
			$ReqB->bindParam(':linkcompleto',$link);
			$ReqB->bindParam(':id', $id_sha);
			$ReqB->execute();	
			$links=array();
			while($RegB = $ReqB->fetch()){				
				$caracteres=strlen($link)-strlen($RegB["LINK_PAGINA"]);
				$num_link=mb_substr($RegB["LINK_PAGINA"],$caracteres);
				if(is_numeric($num_link)) $links[]=$num_link;
				elseif($num_link==$RegB["LINK_PAGINA"]) $links[]=0;
			}		
			if(count($links)>0) $link.=max($links)+1;
			//////////////LINK//////////////	


			if($regG["PPAL"]==1){
				$geo='POINT('.(float)$result["lat_u"].' '.(float)$result["lon_u"].')';	
				$scnomb=$result["scnomb"]==""?$regG["NOMB_RESP"]:$result["scnomb"];
				$abr=mb_strtoupper(mb_substr($scnomb,0,3));	

				$s="INSERT INTO s_cresp
					(NOMB_RESP,SLUG_RESP,ABR_RESP,COMENT_RESP,DIRECCION,TELEFONO,ID_CIUDAD,LOCATION,ZOOM,PPAL,ID_MEMPRESA)
				VALUES(:nombre,:link,:abr,:desc,:direc,:tel,:id_ciudad,GeomFromText(:geo),:zoom,:ppal,$id_empresa)";
				$req = $dbEmpresa->prepare($s); 
				$req->bindParam(':nombre', $scnomb);
				$req->bindParam(':link', $link);
				$req->bindParam(':abr', $abr);
				$req->bindParam(':desc', $result["desc"]);
				$req->bindParam(':direc', $result["direc"]);
				$req->bindParam(':tel', $result["tel"]);
				$req->bindParam(':id_ciudad', $result["id_ciudad"]);
				$req->bindParam(':geo', $geo);
				$req->bindParam(':zoom', $result["zoom"]);
				$req->bindParam(':ppal', $regG["PPAL"]);
				$req->execute();
			
				$id_cresp=$dbEmpresa->lastInsertId();	
				$result["id_resp"]=$id_cresp;

				$s="INSERT INTO s_cresp_grupo
						(ID_GRUPO,ID_RESP)
					VALUES ($id_grupoU,$id_cresp)";
				$dbEmpresa->exec($s);
				if($id_grupoSU!=""){	
					//SUPER USUARIO!
					$s="INSERT INTO s_cresp_grupo
							(ID_GRUPO,ID_RESP)
						VALUES ($id_grupoSU,$id_cresp)";
					$dbEmpresa->exec($s);	
				}
				if($id_grupoPROD!=""){	
					//SUPER USUARIO!
					$s="INSERT INTO s_cresp_grupo
							(ID_GRUPO,ID_RESP)
						VALUES ($id_grupoPROD,$id_cresp)";
					$dbEmpresa->exec($s);		
				}
			}
			else{
				$s="INSERT INTO s_cresp
						(NOMB_RESP,SLUG_RESP,PPAL,ID_MEMPRESA,ID_CIUDAD)
					VALUES (:NOMB_RESP,:link,:PPAL,$id_empresa,:id_ciudad)";
				$req = $dbEmpresa->prepare($s);
				$req->bindParam(':NOMB_RESP', $regG["NOMB_RESP"]);
				$req->bindParam(':link', $link);
				$req->bindParam(':PPAL', $regG["PPAL"]);
				$req->bindParam(':id_ciudad', $result["id_ciudad"]);
				$req->execute();

			}
		}

		//AGREGA MI USUARIO
		$s="SELECT ID_USUARIO,FLAG_U
			FROM adm_usuarios 
			WHERE FLAG_U IN (1,2)";
		$reqUSFlag = $dbEmpresa->prepare($s);	 
		$reqUSFlag->execute();
		$UsPpal=false;
		while($regUSFlag = $reqUSFlag->fetch()){			
			if(($regUSFlag["FLAG_U"]==2)&&($id_grupoSU!="")){
				$UsPpal=$UsPpal?$UsPpal:($_USUARIO==$regUSFlag["ID_USUARIO"]);
				//$Last=($_USUARIO==$regUSFlag["ID_USUARIO"])?1:0;
				$s="INSERT INTO adm_usuarios_empresa
					(ID_USUARIO,ID_MEMPRESA,ID_GRUPO,LAST)
				VALUES(".$regUSFlag["ID_USUARIO"].",$id_empresa,$id_grupoSU,0)";
				$dbEmpresa->exec($s);
			}
			elseif(($regUSFlag["FLAG_U"]==1)&&($id_grupoPROD!="")){
				$UsPpal=$UsPpal?$UsPpal:($_USUARIO==$regUSFlag["ID_USUARIO"]);
				//$Last=($_USUARIO==$regUSFlag["ID_USUARIO"])?1:0;
				$s="INSERT INTO adm_usuarios_empresa
					(ID_USUARIO,ID_MEMPRESA,ID_GRUPO,LAST)
				VALUES(".$regUSFlag["ID_USUARIO"].",$id_empresa,$id_grupoPROD,0)";
				$dbEmpresa->exec($s);
			}
			
		}
		if(!$UsPpal){
			//RELACIONA NUEVA EMORESA
			$s="INSERT INTO adm_usuarios_empresa
				(ID_USUARIO,ID_MEMPRESA,ID_GRUPO,LAST)
			VALUES($_USUARIO,$id_empresa,$id_grupoU,0)";
			$dbEmpresa->exec($s); 
		}

		if($_PROYECTO==1){
			$TGrupo="(SELECT adm_empresas_tipo.TIPO_GRUPOPAL FROM adm_empresas_tipo WHERE adm_empresas_tipo.ID_TIPOE=:tempresa)";

			$s="INSERT INTO m_oficios
				(NOMB_OFICIO,DESC_OFICIO,ID_MEMPRESA,ID_USUARIO)
			(SELECT NOMB_OFICIO,DESC_OFICIO,$id_empresa,$_USUARIO FROM adm_empresas_imp_oficios WHERE ID_IDIOMA=$_IDIOMA AND TIPO_GRUPOPAL=$TGrupo)";
			$req = $dbEmpresa->prepare($s);
			$req->bindParam(':tempresa', $result["tempresa"]);
			$req->execute();


			$s="INSERT INTO m_tfalla
				(NOMB_FALLA,COMEN_FALLA,ID_MEMPRESA)
			(SELECT NOMB_FALLA,COMEN_FALLA,$id_empresa FROM adm_empresas_imp_tfalla WHERE ID_IDIOMA=$_IDIOMA AND TIPO_GRUPOPAL=$TGrupo)";
			$req = $dbEmpresa->prepare($s);
			$req->bindParam(':tempresa', $result["tempresa"]);
			$req->execute();		
			
			$s="INSERT INTO m_ttrabajo
				(DESC_TTRABAJO,COMENT_TTRABAJO,ID_MEMPRESA)
			(SELECT DESC_TTRABAJO,COMENT_TTRABAJO,$id_empresa FROM adm_empresas_imp_ttrabajo WHERE ID_IDIOMA=$_IDIOMA AND TIPO_GRUPOPAL=$TGrupo)";
			$req = $dbEmpresa->prepare($s);
			$req->bindParam(':tempresa', $result["tempresa"]);
			$req->execute();
			
			$s="INSERT INTO m_concepto
				(DESC_CONCEPTO,ID_MEMPRESA)
			(SELECT DESC_CONCEPTO,$id_empresa FROM adm_empresas_imp_concepto WHERE ID_IDIOMA=$_IDIOMA AND TIPO_GRUPOPAL=$TGrupo)";
			$req = $dbEmpresa->prepare($s);
			$req->bindParam(':tempresa', $result["tempresa"]);
			$req->execute();
		}
		elseif($_PROYECTO==13){	
			//OTROS
			$s="SELECT adm_empresas_tipo.TIPO_GRUPOPBL FROM adm_empresas_tipo WHERE adm_empresas_tipo.ID_TIPOE=:tempresa";
			$reqG = $dbEmpresa->prepare($s);	 
			$reqG->bindParam(':tempresa', $result["tempresa"]);
			$reqG->execute();
			$regG = $reqG->fetch();
			if($regG["TIPO_GRUPOPBL"]==0){
				$s="INSERT INTO fac_tipos_empresa
					(ID_MEMPRESA,ID_TITEM)
				VALUES ($id_empresa,1)";
				$dbEmpresa->exec($s);
			}
			elseif($regG["TIPO_GRUPOPBL"]==1){
				$s="INSERT INTO fac_tipos_empresa
					(ID_MEMPRESA,ID_TITEM)
				VALUES ($id_empresa,2)";
				$dbEmpresa->exec($s);
			}
			elseif($regG["TIPO_GRUPOPBL"]==2){
				$s="INSERT INTO fac_tipos_empresa
					(ID_MEMPRESA,ID_TITEM)
				VALUES ($id_empresa,1),($id_empresa,2)";
				$dbEmpresa->exec($s);

			}
			//IMAGENES
			$s="INSERT INTO adm_empresas_imagenes(ID_IMAGEN,ID_MEMPRESA,IMAGEN_NOMBRE,MINS_IMAGEN,MAXS_IMAGEN,DIMX,DIMY)
				(
					SELECT 
					adm_empresas_c_imagenes.ID_IMAGEN,
					$id_empresa,
					adm_empresas_c_imagenes.IMAGEN_NOMBRE,
					adm_empresas_c_imagenes.MINS_IMAGEN,
					adm_empresas_c_imagenes.MAXS_IMAGEN,
					adm_empresas_c_imagenes.DIMX,
					adm_empresas_c_imagenes.DIMY
					FROM adm_empresas_c_imagenes
				)";
			$dbEmpresa->exec($s);
		}
	}

	//NOMBRE DE EMPRESA
	$s="UPDATE adm_empresas_configuracion
			SET CONFIG_VALOR=:nmempresa
		WHERE (ID_CONFIG=1 OR ID_CONFIG=1000) AND ID_MEMPRESA=$id_empresa";
	$req = $dbEmpresa->prepare($s);
	$req->bindParam(':nmempresa', $result["nmempresa"]);
	$req->execute();	

	/**********************************/
	/******** AGREGA LA IMAGEN ********/
	/**********************************/
	$control_img=$result["imagen_f_name"];
	if(($result["imagen"]==1)){
		$tamano=$_FILES[$control_img]["size"];
		$ubicacion=$_FILES[$control_img]["tmp_name"];
		$finfo = finfo_open(FILEINFO_MIME_TYPE); 	
		$tipo=finfo_file($finfo, $ubicacion);	
		finfo_close($finfo);		
		if(($tamano>=$fmin) && ($tamano<=$fmax)){	
			if(fValid($tipo,$_files_clase[0])){
				$UploadArgs=array('S3_BUCKET'=>$_PARAMETROS["S3_BUCKET"]
							,	'PROYECTO'=>$_PROYECTO
								,	'EMPRESA'=>$_EMPRESA
								,	'MODULE'=>0
								,	'OBJECT'=>$id_empresa
								,	'TP_FILE'=>'LogoClient');
				UploadFiles($AwsS3,$control_img,$dbEmpresa,$UploadArgs);	
			}
		}
	}
	elseif(($result["imagen"]==3)){
		$DeleteArgs=array('S3_BUCKET'=>$_PARAMETROS["S3_BUCKET"]
							,	'PROYECTO'=>$_PROYECTO
							,	'EMPRESA'=>$_EMPRESA
							,	'MODULE'=>0
							,	'OBJECT'=>$id_empresa
							,	'TP_FILE'=>'LogoClient');
		DeleteFiles($AwsS3,$dbEmpresa,$DeleteArgs);
	}
	if($result["imagen"]==1||$result["imagen"]==3){
		$s="UPDATE adm_empresas 
			SET ID_FILE=IFNULL((SELECT adm_files.ID_FILE FROM adm_files WHERE adm_files.F_MODULE=0 AND adm_files.ID_OBJECT=$id_empresa AND adm_files.F_SUB_EXT='org' AND adm_files.F_TP_FILE='LogoClient' LIMIT 1),0)
			WHERE ID_MEMPRESA=$id_empresa";
		$dbEmpresa->exec($s);
	}	


	if($auto_change==1||$auto_change==2){
		//SUELTA LA EMPRESA ACTUAL
		$s="UPDATE adm_usuarios_empresa
			SET LAST=IF(ID_MEMPRESA=$id_empresa,1,0)
			WHERE ID_USUARIO=$_USUARIO";									
		$req = $dbEmpresa->prepare($s);	
		$req->execute(); 		
	}

	if($auto_change==1){
		EmpresaComprov($dbEmpresa,true);
		//UsuarioInfo($dbEmpresa,true);
		//Grupos($dbEmpresa,true);
		//Parametros($dbEmpresa,true);
		//CargarTextos($dbEmpresa,true);
	}
	
	if($_CLIENTE==$id_empresa){
		//Parametros($dbEmpresa,true);
	}
		
	return $id_empresa;
}
function SendInvoce($req,$id,$options,$dbEmpresa,$_PROYECTO,$_EMPRESA,$_CLIENTE,$_textos,$_PARAMETROS){

	require_once('pdf/tcpdf.php');	
	
	/*DEFINIR UBICACION DE LA IMAGEN*/
	include("variables.php");
	$dir_dest=$dir_rel[3];
	$nomb_file=sprintf("/%s-%s-%s.pdf",$_PROYECTO,$_EMPRESA,$id);	
	$archivo=$dir_dest.$nomb_file;

	/**/
	class MYPDF extends TCPDF {
	  public function Header() {
	    include("variables.php");
	    include("variables_se.php");
	    $WTotal=$this->getPageWidth();
	    $Margins=$this->getMargins();
	    $WUsable=$WTotal-($Margins["left"]+$Margins["right"]);

	    $_textos=$GLOBALS['_textos'];
	    $options=$GLOBALS['options'];
	    $_PARAMETROS=$GLOBALS['_PARAMETROS'];
	    $_PROYECTO=$GLOBALS['_PROYECTO'];
	    $_EMPRESA=$GLOBALS['_EMPRESA'];
	    $_CLIENTE=$GLOBALS['_CLIENTE'];

	    $ext=".png";
	    $dir_ima='http:'.$_PARAMETROS["S3_URL4"].ImgName($_PROYECTO,$_EMPRESA,0,$_CLIENTE,'LogoClient','png',false,'big');

	    $ImgH=25;
	    $ImgW=($WUsable*.5);

	    $ImgX=($WUsable*.5)+$Margins["left"];
	    $ImgY=$Margins["top"];   
	  
	    $this->Image($dir_ima, $ImgX, $ImgY, $ImgW,$ImgH, 'PNG', '', 'M', false, 300, 'LTR', false, false, 0, 'CM', false, false);
	    $MarcosY=$Margins["top"]+26;
	    $Marco1W=$WUsable*.5;
	    $Marco1X=$Margins["left"];
	    $Marco1Y=$MarcosY;
	    $this->setCellPaddings(1, 1, 1, 1);
	    $this->SetFillColor(222, 222, 222);
	    $this->SetDrawColor(204, 204, 204);
	    $this->SetLineWidth(0.1);
	    $this->SetFont('helvetica', 'R', 12);    
	    $this->MultiCell($Marco1W, 8, $options["name"], 0, 'L', 0, 0, $Marco1X, $Marco1Y, true);
	    $this->SetFont('helvetica', 'R', 10);
	    $this->MultiCell($Marco1W, 8, sprintf("%s: %s",$_textos[1065][0],$options["direccion"]), 0, 'L', 0, 0, $Marco1X, $Marco1Y+13, true,0,false,true,0,'M');
	    $this->MultiCell($Marco1W, 8, sprintf("%s: %s",$_textos[1059][0],$options["telefono"]), 0, 'L', 0, 0, $Marco1X, $Marco1Y+20, true,0,false,true,0,'M');
	    $this->MultiCell($Marco1W, 8, sprintf("%s: %s",$options["abrdoc"],$options["doc"]), 0, 'L', 0, 0, $Marco1X, $Marco1Y+27, true,0,false,true,0,'M');



	    $Marco2W=$WUsable*.5;
	    $Marco2X=$Margins["left"]+($WUsable*.5);
	    $Marco2Y=$MarcosY;
	    $this->SetFont('helvetica', 'B', 13);
	    $this->MultiCell($Marco2W, 8, $_textos[9122][0].' '.$options["fac_numbr"], 'B', 'C', true, 0, $Marco2X, $Marco2Y, true,0,false,true,0,'M');

	    $this->SetFont('helvetica', 'B', 10);
	    $this->MultiCell($Marco2W*.5, 8, $_textos[9125][0], 0, 'L', 0, 0, $Marco2X, $Marco2Y+8, true,0,false,true,0,'M');
	    $this->MultiCell($Marco2W*.5, 8, $_textos[9126][0], 0, 'L', 0, 0, $Marco2X, $Marco2Y+16, true,0,false,true,0,'M');
	    $this->SetFont('helvetica', 'R', 10);
	    $this->MultiCell($Marco2W*.5, 8, $options["fechae"], 0, 'C', 0, 0, $Marco2X+($Marco2W*.5), $Marco2Y+8, true,0,false,true,0,'M');
	    $this->MultiCell($Marco2W*.5, 8, $options["fechav"], 0, 'C', 0, 0, $Marco2X+($Marco2W*.5), $Marco2Y+16, true,0,false,true,0,'M');
	    
	    $this->SetFont('helvetica', 'B', 12);
	    $this->MultiCell($Marco2W*.5, 8, $_textos[1254][0], 'B', 'L', 1, 0, $Marco2X, $Marco2Y+24, true,0,false,true,0,'M');
	    $this->SetFont('helvetica', 'R', 12);
	    $this->MultiCell($Marco2W*.5, 8, "$".number_format($options["vtotal"],2), 'B', 'C', 1, 0, $Marco2X+($Marco2W*.5), $Marco2Y+24, true,0,false,true,0,'M');

	  }
	  public function Footer() {  
	    include("variables.php");
	    $_textos=$GLOBALS['_textos'];
	    $options=$GLOBALS['options'];
	    $_PARAMETROS=$GLOBALS['_PARAMETROS'];
	    $_PROYECTO=$GLOBALS['_PROYECTO'];
	    $_EMPRESA=$GLOBALS['_EMPRESA'];
	    $_CLIENTE=$GLOBALS['_CLIENTE'];

	    $this->SetY(-20);
	    $this->SetFont('helvetica', 'I', 8);
	    $TxtLine=array();
	    $TxtLine[0]=sprintf("%s | %s | %s:%s | %s:%s",$_PARAMETROS["RAZON_SOCIAL"]
	    									,$_PARAMETROS["DIRECCION"]
	                                      	, $_textos[1307][0],$_PARAMETROS["E_MAIL_R"]
	                                      	, $_textos[1059][0],$_PARAMETROS["TELEFONO"]);
	    $TxtLine[1]=sprintf("%s:%s | %s",$options["abrown"],$_PARAMETROS["DOCUMENTO"],$_PARAMETROS["BILL_TREG"]);
	    $TxtLine[2]=sprintf("%s | %s",$_PARAMETROS["BILL_FOOT01"],$_PARAMETROS["BILL_FOOT02"]);
	    foreach ($TxtLine as $key => $value) {
	      $this->Cell(0, 2, $value, 0, 1, 'C', 0, '', 0, false, 'T', 'M');
	    }
	  }
	  public function ColoredTable($headers,$data,$options=array()) {
	    /**/
	    $WTotal=$this->getPageWidth();
	    $Margins=$this->getMargins();
	    $WUsable=$WTotal-($Margins["left"]+$Margins["right"]);
	    /**/

	    function PrintRow($data,$where,$WUsable,$options){
	      if(isset($options["background-color"]))
	        $where->SetFillColor($options["background-color"][0],$options["background-color"][1],$options["background-color"][2]);
	    
	      if(isset($options["color"]))
	        $where->SetTextColor($options["color"][0],$options["color"][1],$options["color"][2]);

	      if(isset($options["border-color"]))
	        $where->SetDrawColor($options["border-color"][0],$options["border-color"][1],$options["border-color"][2]);

	      if(isset($options["border-width"]))
	        $where->SetLineWidth($options["border-width"]);

	      if(isset($options["font"]))
	        $where->SetFont($options["font"][0],$options["font"][1],$options["font"][2]);
	     
	    if(!isset($options["background-alt"])) $options["background-alt"]=false;
	    if(!isset($options["background"])) $options["background"]=false;
	     /*IMPRIME*/
	      if(count($data)>0){
	      	$fill=$options["background"];
	        foreach ($data as $hd => $subheaders){
				foreach ($subheaders as $h => $header) {
					$WCell=($header["width"]/100)*$WUsable;					
					$where->Cell($WCell, $header["height"], $header['content'], $header['border'], 0, $header['align'], $fill);
				}
				$fill=(!$fill)&&($options["background-alt"]);
				$fill=$fill||$options["background"];
	          $where->Ln();
	        }
	      }

	    }
	    PrintRow($headers,$this,$WUsable,$options["header"]);
	    PrintRow($data,$this,$WUsable,$options["content"]);
	  }
	}
	include("variables.php");

	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor($_AUSER["USUARIO_COMP"]);
	$pdf->SetTitle($options["fac_numbr"]);
	$pdf->SetSubject($options["name"]);
	// set default header data
	$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	// set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	// set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	// set some language-dependent strings (optional)
	if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	  require_once(dirname(__FILE__).'/lang/eng.php');
	  $pdf->setLanguageArray($l);
	}

	$pdf->AddPage();
	/****/
	$WTotal=$pdf->getPageWidth();
	$HTotal=$pdf->getPageHeight();
	$Margins=$pdf->getMargins();
	$WUsable=$WTotal-($Margins["left"]+$Margins["right"]);
	/****/
	/*MARGENES*/
	$TIni=100;
	$TFin=30;
	$pdf->SetDrawColor(204, 204, 204);
	$pdf->SetLineWidth(0.1);
	$HMargen=$HTotal-($Margins["top"]+$TIni+$TFin);
	$pdf->SetY($TIni);
	$pdf->Cell($WUsable*.41, $HMargen, '', 1, 0, '', 0);
	$pdf->Cell($WUsable*.13, $HMargen, '', 1, 0, '', 0);
	$pdf->Cell($WUsable*.10, $HMargen, '', 1, 0, '', 0);
	$pdf->Cell($WUsable*.18, $HMargen, '', 1, 0, '', 0);
	$pdf->Cell($WUsable*.18, $HMargen, '', 1, 0, '', 0);

	$pdf->SetY($TIni);
	$W=array(49,17,17,17);
	$opciones["header"]["background-color"]=array(222,222,222);
	$opciones["header"]["color"]=array(6,6,6);
	$opciones["header"]["border-color"]=array(204,204,204);
	$opciones["header"]["border-width"]='0.2';
	$opciones["header"]["font"]=array('helvetica', 'N', 11);
	$opciones["header"]["background"]=true;

	$opciones["content"]["background-color"]=array(242,242,242);
	$opciones["content"]["color"]=array(6,6,6);
	$opciones["content"]["font"]=array('helvetica', 'R', 11);
	$opciones["content"]["border-color"]=array(204,204,204);
	$opciones["content"]["border-width"]='0.2';
	$opciones["content"]["background-alt"]=true;
	$i=0;
	$k=0;
	$headers[$i][$k]["content"]=$_textos[1091][0]; //DESC
	$headers[$i][$k]["align"]='L';
	$headers[$i][$k]["width"]=41;
	$headers[$i][$k]["height"]=7;
	$headers[$i][$k]["border"]=1;
	$k++;
	$headers[$i][$k]["content"]=$_textos[1148][0]; //CANT
	$headers[$i][$k]["align"]='C';
	$headers[$i][$k]["width"]=13;
	$headers[$i][$k]["height"]=7;
	$headers[$i][$k]["border"]=1;
	$k++;
	$headers[$i][$k]["content"]=$_textos[369][0]; //UNI
	$headers[$i][$k]["align"]='C';
	$headers[$i][$k]["width"]=10;
	$headers[$i][$k]["height"]=7;
	$headers[$i][$k]["border"]=1;
	$k++;
	$headers[$i][$k]["content"]=$_textos[440][0]; //PRECIO
	$headers[$i][$k]["align"]='C';
	$headers[$i][$k]["width"]=18;
	$headers[$i][$k]["height"]=7;
	$headers[$i][$k]["border"]=1;
	$k++;
	$headers[$i][$k]["content"]=$_textos[1169][0]; //TOTAL
	$headers[$i][$k]["align"]='C';
	$headers[$i][$k]["width"]=18;
	$headers[$i][$k]["height"]=7;
	$headers[$i][$k]["border"]=1;

	$i=0;
	while($reg = $req->fetch()){
		$cantidad=$reg["CANTIDAD"];
		$precio=$reg["PRECIO"];
		$descuento=($reg["DESCUENTO"]/100);

		$precio_dcto=$precio*(1-$descuento); 	//PRIMERO DESCUENTO
		$precio_tax=$precio_dcto*(1+$iva);		//LUEGO APLICA IVA
		$total=$precio_tax*$cantidad;			//SUMA EN FILA

		$k=0;
		$data[$i][$k]["content"]=$reg["NOMB_PORTAFOLIO"]; //DESC
		$data[$i][$k]["align"]='L';
		$data[$i][$k]["width"]=41;
		$data[$i][$k]["height"]=7;
		$data[$i][$k]["border"]='LR';
		$k++;
		$data[$i][$k]["content"]=number_format($cantidad); //CANT
		$data[$i][$k]["align"]='C';
		$data[$i][$k]["width"]=13;
		$data[$i][$k]["height"]=7;
		$data[$i][$k]["border"]='LR';
		$k++;
		$data[$i][$k]["content"]=$reg["ABR_UNIDAD"]; //UNI
		$data[$i][$k]["align"]='C';
		$data[$i][$k]["width"]=10;
		$data[$i][$k]["height"]=7;
		$data[$i][$k]["border"]='LR';
		$k++;
		$data[$i][$k]["content"]=sprintf('$%s',number_format($precio_dcto)); //PRECIO
		$data[$i][$k]["align"]='C';
		$data[$i][$k]["width"]=18;
		$data[$i][$k]["height"]=7;
		$data[$i][$k]["border"]='LR';
		$k++;
		$data[$i][$k]["content"]=sprintf('$%s',number_format($total)); //TOTAL
		$data[$i][$k]["align"]='C';
		$data[$i][$k]["width"]=18;
		$data[$i][$k]["height"]=7;
		$data[$i][$k]["border"]='LR';

		$i++;
	}
	$pdf->ColoredTable($headers, $data,$opciones);

	$pdf->SetFont('helvetica', 'R', 11);
	$STotal="$".number_format($options["stotal"]-$options["sdesc"],2);
	$Tax="$".number_format($options["tax"],2);
	$Total="$".number_format($options["vtotal"],2);
	$Taman=$HTotal-($Margins["top"]+$TFin);
	$pdf->MultiCell($WUsable, 12, sprintf("%s: %s",$_textos[1061][0],$options["info_nota"]), 'LTR', 'L', 0, 0, $Margins["left"], $Taman, true);

	$NTam=$Taman+12;
	$pdf->MultiCell($WUsable*.64, 18, sprintf("%s: %s",$_textos[1325][0],$options["vletras"]), 'LTB', 'L', 0, 0, $Margins["left"], $NTam, true);

	$pdf->MultiCell($WUsable*.18, 6, $_textos[1171][0].":", 'LTB', 'L', 0, 0, $Margins["left"]+$WUsable*.64,  $NTam, true);
	$pdf->MultiCell($WUsable*.18, 6, $_textos[1170][0].":", 'LB', 'L', 0, 0,    $Margins["left"]+$WUsable*.64,  $NTam+6, true);
	$pdf->MultiCell($WUsable*.18, 6, $_textos[1169][0].":", 'LB', 'L', 0, 0,    $Margins["left"]+$WUsable*.64,  $NTam+12, true);

	$pdf->MultiCell($WUsable*.18, 6, $STotal,  'RTB', 'C', 0, 0,$Margins["left"]+$WUsable*.82, $NTam, true);
	$pdf->MultiCell($WUsable*.18, 6, $Tax,  'RB', 'C', 0, 0,    $Margins["left"]+$WUsable*.82, $NTam+6, true);
	$pdf->MultiCell($WUsable*.18, 6, $Total,  'RB', 'C', 0, 0,  $Margins["left"]+$WUsable*.82, $NTam+12, true);
	
	$pdf->Output($archivo, 'F');

	
	return($archivo);

	//============================================================+
	// END OF FILE
	//============================================================+
}

function ActRocketMP($dbEmpresa,$type,$c,$where='',$_args=array()){
	if($where!='') $where='WHERE '.$where;
	if($type=="equipo"){		
		$s="SELECT ID_EQUIPO FROM m_equipo $where";
		$reqE = $dbEmpresa->prepare($s); 
		foreach ($_args as $key => $value) {
			$reqE ->bindValue(":$key", $value);
		}
		$reqE ->execute();
		while($regE = $reqE->fetch()){
			if($c=='estado'){
				$s="UPDATE m_equipo_estado SET LAST=0 WHERE ID_EQUIPO=:idequipo";
				$req = $dbEmpresa->prepare($s); 
				$req->bindParam(':idequipo', $regE["ID_EQUIPO"]);	
				$req->execute();

				$s="UPDATE m_equipo_estado SET LAST=1 WHERE ID_EQUIPO=:idequipo ".
					"ORDER BY FECHA_EQ DESC,FECHAS_EQ DESC,ID_EQESTADO DESC LIMIT 1";
				$req = $dbEmpresa->prepare($s); 
				$req->bindParam(':idequipo', $regE["ID_EQUIPO"]);	
				$req->execute();
			}
			elseif($c=='contador'){

				$s="UPDATE m_equipo_contador_actual SET LAST=0 WHERE ID_EQUIPO=:idequipo";
				$req = $dbEmpresa->prepare($s); 
				$req->bindParam(':idequipo', $regE["ID_EQUIPO"]);	
				$req->execute();

				$s="UPDATE m_equipo_contador_actual SET LAST=1 WHERE ID_EQUIPO=:idequipo ".
					"ORDER BY FECHA_CONTADOR DESC, FECHAS_CONTADOR DESC, ID_CONTADORREG DESC LIMIT 1";
				$req = $dbEmpresa->prepare($s); 
				$req->bindParam(':idequipo', $regE["ID_EQUIPO"]);	
				$req->execute();
			}
			elseif($c=='location'){
				$s="UPDATE m_equipo_location SET LAST=0 WHERE ID_EQUIPO=:idequipo";
				$req = $dbEmpresa->prepare($s); 
				$req->bindParam(':idequipo', $regE["ID_EQUIPO"]);	
				$req->execute();

				$s="UPDATE m_equipo_location SET LAST=1 WHERE ID_EQUIPO=:idequipo ".
					"ORDER BY FECHA DESC LIMIT 1";
				$req = $dbEmpresa->prepare($s); 
				$req->bindParam(':idequipo', $regE["ID_EQUIPO"]);	
				$req->execute();
			}
			elseif($c=='ubicacion'){
				$s="UPDATE m_equipo_mover SET LAST=0 WHERE ID_EQUIPO=:idequipo";
				$req = $dbEmpresa->prepare($s); 
				$req->bindParam(':idequipo', $regE["ID_EQUIPO"]);	
				$req->execute();

				$s="UPDATE m_equipo_mover SET LAST=1 WHERE ID_EQUIPO=:idequipo ".
					"ORDER BY FECHA_MOVER DESC, FECHAS_MOVER DESC, ID_MOVER DESC LIMIT 1";
				$req = $dbEmpresa->prepare($s); 
				$req->bindParam(':idequipo', $regE["ID_EQUIPO"]);	
				$req->execute();
			}
		}
	}
	elseif($type=="ot"){
		$s="SELECT ID_OT FROM m_ot $where";

		$reqE = $dbEmpresa->prepare($s); 
		foreach ($_args as $key => $value) {
			$reqE ->bindValue(":$key", $value);
		}
		$reqE ->execute();
		while($regE = $reqE->fetch()){

			if($c=='estado'){
				$s="UPDATE m_ot_seguimiento SET LAST=0 WHERE ID_OT=:idot";
				$req = $dbEmpresa->prepare($s);
				$req->bindParam(':idot', $regE["ID_OT"]);	
				$req->execute();

				$s="UPDATE m_ot_seguimiento SET LAST=1 WHERE ID_OT=:idot ".
					"ORDER BY FECHA_OTSEG DESC, FECHAS_OTSEG DESC, ID_OTSEG DESC LIMIT 1";
				$req = $dbEmpresa->prepare($s);
				$req->bindParam(':idot', $regE["ID_OT"]);	
				$req->execute();
			}			
		}
	}

}
?>
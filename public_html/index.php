<?php
session_start();
require_once 	"phplib/funciones.php";
require_once 	"phplib/consultas.php";
require_once 	"phplib/plantilla/cuerpomail.php";
require_once 	"phplib/mail/PHPMailer/class.phpmailer.php";
require_once 	"phplib/mail/sendmail_new.php";
require_once   	"phplib/moldes.php";
if(!isset($_SESSION["TIEMPO"])) $_SESSION["TIEMPO"]=clave(uniqid());
$__route__=$_REQUEST["__route__"];
$route=explode('/', $__route__);
$_main=isset($route[1])?$route[1]:'';	
$_args=isset($route[2])?$route[2]:'';

// echo '======== $__route__ ======' . $__route__;
$result=$_REQUEST;

$_AJAX=isset($_REQUEST["_AJAX"])?$_REQUEST["_AJAX"]:0;
$_SH=isset($_REQUEST["_SH"])?$_REQUEST["_SH"]:1;

$dominio='siie.co';
$header=200;
$error=0;
$_sysvars=array();
$dom_fuera=($_SERVER["HTTP_HOST"]!=$dominio);
if($dom_fuera){
	$state=ConectarseAPI($dbEmpresa,$subdominio,$_sysvars);
	if(!$state){
		$header=500;
		header($headers[$header], true, $header);
		exit(0);
	}


	/*VERIFICACION DE DOMINIO*/
	$_sysvars["_ip"]=$_SERVER["REMOTE_ADDR"]; //IP DEL CLIENTE

	/*SE USA PARA LA SESION PREVIAMENTE INICIADA*/
	$_sysvars["_token_a"]=$_COOKIE["_token_a"];
	$_sysvars["_token_b"]=$_COOKIE["_token_b"];

	/*SE USA PARA INICIAR SESION DE FORMA AUTOMATICA*/
	$_sysvars["_ualias"]=$_REQUEST["_ualias"]; //USUARIO
	$_sysvars["_upassw"]=$_REQUEST["_upassw"]; //PASSWORD
	$_sysvars["_memory"]=$_REQUEST["_memory"]; //RECORDAR SESSION	
	$_sysvars["_session"]=session_id(); //SESION ID DEL CLIENTE


	////////////////////////////////
	////////////////////////////////
	////////////////////////////////
	$_sysvars_r=verif_sp($_sysvars,$dbEmpresa);
	$verificar=$_sysvars_r["return"];
	include 		"phplib/variables_se.php";
	Consultas($sqlCons,$sqlOrder,$_PROYECTO,$_CLIENTE,$_IDIOMA,$_TZ,$_GCLIENTE,$_USUARIO,$_GRUPO,$_PARAMETROS);
	$_PARAMETROS=ParametrosAPI($dbEmpresa,$_sysvars_r,$_sysvars);
	$PermisosA=Grupos($dbEmpresa,$_GRUPO,$_GCLIENTE);
	$_sysvars_r["PermisosA"]=$PermisosA;
	$_sysvars_r["_PARAMETROS"]=$_PARAMETROS;

	// INICIA CONFIGURACION //

	$_OP_URL=$_PARAMETROS["S3_URL4"];
	$_LW_URL=$_PARAMETROS["WP_OPPAGE"];

	$_LOGO=$_OP_URL.ImgName($_PROYECTO,$_EMPRESA,0,0,'LogoApp','png',false,'big'); 
	$_LOGO_CLIENT=$_OP_URL.ImgName($_PROYECTO,$_EMPRESA,0,$_CLIENTE,'LogoClient','png',false,'big'); 
	if($_sysvars_r["display"]['img']!=0)
		$_USR_IMG=$_OP_URL.$_sysvars_r["display"]['t01']; 
	else
		$_USR_IMG='/img/avatar.png';

	$favicon=$_OP_URL.ImgName($_PROYECTO,$_EMPRESA,0,0,'favico','ico',false,'big');

	$_og_fb_id=$_PARAMETROS["FB_APPID"];
	$_mt_title=$_PARAMETROS["S_NOMBCORTO"];
	$_mt_url=$_LW_URL;
	$_mt_image=$_LOGO;
	$_mt_description=$_PARAMETROS["S_SLOGAN"];
	$_og_description=$_PARAMETROS["S_SLOGAN"];
	$_og_tw_stwitter=$_PARAMETROS["TW_TWITTER"];
	$_og_tw_ctwitter=$_PARAMETROS["TW_TWITTER"];
	$_og_fb_autor="";
	$http_title=sprintf("%s - %s",$_PARAMETROS["S_NOMBCORTO"],$_PARAMETROS["S_SLOGAN"]);

	$_mt_gplus='';
	$forms=false;
	$page=false;
	$set_report=true;
	/*BUSCAR*/
	$opt=$_OPT;	
	$error=0;
	$_og_type='website';
	$tp_include=$_AJAX==1?'code':'header-footer';
	$_def_head="_inc/header.php";
	$_def_footer="_inc/footer.php";
	$def_class="";
	$ShowNotify=true;
	if($_main=='mail'||$_main=='ipago'
		||$_main=='logout'||$_main=='external'
		||$_main=='l'||$_main=='twitter'
		||$_main=='facebook'||$_main=='linkedin'
		||$_main=="plain"||$_main=="verification") {
		$include_file="_extras/extras.php";
		$tp_include='code';
	}
	else{
		if($_main=='autocomplete'){
			$include_file="_transac/autocomplete.php";
			$tp_include='code';
		}
		elseif($verificar){
			if($_main=='edit')				$include_file="_forms/base_frm.php";
			elseif($_main=='setconfig')		$include_file="_forms/configuracion_frm.php";
			elseif($_main=='delconfig')		$include_file="_forms/configuracion_msg.php";
			elseif($_main=='delete')		$include_file="_forms/base_msg.php"; 
			elseif($_main=='operation')		$include_file="_forms/op_frm.php";
			elseif($_main=='sreport')		$include_file="_forms/reports_frm.php";
			elseif($_main=='cabstracth')	$include_file="_resumen/resumen_prev.php";
			elseif($_main=='cabstractb')	$include_file="_resumen/resumen_inc.php";
			elseif($_main=='abstract')		$include_file="_resumen/resumen.php";	
		
			

			elseif($_main=='basert')		$include_file="_transac/oprealtime.php";
			elseif($_main=='baseglob')		$include_file="_transac/baseglob.php";
			elseif($_main=='base')			$include_file="_transac/baseinc.php";	
			elseif($_main=='socialnet')		$include_file="_transac/socialnet.php";
			elseif($_main=='sinfo')			$include_file="_transac/solinfo.php";	


			elseif($_main=='tedit')			{$OResponse=true; $include_file="_transac/base_acc.php";$det_cnf=true; $std_res=true;}
			elseif($_main=='tdelete')		{$OResponse=true; $include_file="_transac/base_elim.php";$det_cnf=true; $elim_res=true;}
			elseif($_main=='toperation')	{$OResponse=true; $include_file="_transac/op_acc.php";$det_cnf=true; $std_res=true;}
			elseif($_main=='tsetconfig')	{$OResponse=true; $include_file="_transac/configuracion_acc.php";$conf_res=true;}
			elseif($_main=='tdelconfig')	{$OResponse=true; $include_file="_transac/configuracion_elim.php";$conf_res=true;$elim_res=true;}
			elseif($_main=='tcompany')		{$OResponse=true; $include_file="_transac/empresa.php";	$MField=true;}
			elseif($_main=='tgeneralapp')	{$OResponse=true; $include_file="_transac/generalapp.php";	$MField=true;}
			elseif($_main=='tgeneral')		{$OResponse=true; $include_file="_transac/general.php";	$MField=true;}		
			elseif($_main=='tperfil')		{$OResponse=true; $include_file="_transac/perfiled.php";	$MField=true;}
			elseif($_main=='implement')		{$OResponse=true; $include_file="_implement/implement.php";$MField=true;}
			elseif($_main=='informe'){
				$ShowNotify=false;
				$md=$result["md"];
				$id_sha=substr($md,0,40);
				$c_sha=substr($md,40,32);
				$sWhere=encrip_mysql('adm_informes_detalle.ID_INFORME');
				$s=$sqlCons[1][73]." AND $sWhere=:id LIMIT 1";
				$reqInf = $dbEmpresa->prepare($s); 
				$reqInf->bindParam(':id', $id_sha);
				$reqInf->execute();
				if($regInf = $reqInf->fetch()){
					$cnf=$regInf["ID_VENTANA"];
					if($regInf["TIPO_INFORME"]==10)		
						$tp_include='code';
					else{

						$infId=$regInf["ID_INFORME"];
						$titulo=$regInf["NOMB_INFORME"];
						$sub_titulo=$regInf["REF_INFORME"];
						$fecharev=$regInf["FECHA_INFORMEF"];
						$rev=$regInf["REV_INFORME"];

						$_def_head="_informes/header.php";
						$_def_footer="_informes/footer.php";

						$def_class="repots";
					}
					
					
					$dir_inf=sprintf("informes/%s/%s/index.php",$_PROYECTO,$_EMPRESA);
					if(file_exists($dir_inf))	
						$include_file=$dir_inf;	
					else
						$include_file='_informes/index.php';	

				}
				else{
					$include_file='_extras/error.php';
				}
				
			}
			elseif($_main=='creporth')		$include_file="_reports/reports_prev.php";
			elseif($_main=='creportb')		$include_file="_reports/reports_inc.php";
			
			else{
				$s=$sqlCons[1][69]." WHERE adm_ventanas_menu.ACR_VENTANA=:_main LIMIT 1";

				$req = $dbEmpresa->prepare($s);	
				$req->bindParam(':_main', $_main);
				$req->execute();		
				if($reg = $req->fetch()){
					$id_ventana=$reg["ID_VENTANA"];
					$titulo_modulo=$reg["VENTANA_NOMBRE"];
					$tipo_modulo=$reg["TIPO_INFO"];				
					$include_file='_extras/'.$reg["DIR_VENTANA"];
				}
				else $include_file='_extras/error.php';
			}	
		}
		else{
			$ShowNotify=false;
			
			
			$_LOGO_SET=$_COOKIE['company_logo']!=''?$_OP_URL.$_COOKIE['company_logo']:$_LOGO;

			if($_main!=""&&$_main!="register"&&$_main!="code"&&$_main!="recovery"&&$_main!="forgot"){
				$s=$sqlCons[3][81].' WHERE adm_empresas.URL=:slug LIMIT 1';
				$reqLOGO = $dbEmpresa->prepare($s); 
				$reqLOGO->bindParam(':slug', $_main);
				$reqLOGO->execute();				
				if($regLOGO = $reqLOGO->fetch()){
					$ArrayImg=array(
				            'PROYECTO'=>$_PROYECTO
				        ,   'EMPRESA'  =>$_EMPRESA
				        ,   'MODULO'    =>0
				        ,   'OBJETO'    =>$regLOGO["ID_MEMPRESA"]
				        ,   'TP'        =>'LogoClient'
				        ,   'EXT'       =>$regLOGO["F_EXT"]
				        ,   'All'       =>true);				
					$_ALLLOGO=ImgBlanc($regLOGO["M_IMG"],$ArrayImg);
					$_LOGO_SET=$_ALLLOGO['t03'];

					$tiempo=time()+(60*60*24*15);
					$dominio_activo=$_SERVER['HTTP_HOST'];	
					setcookie("company_logo",$_ALLLOGO['big'],$tiempo,'/',$dominio_activo,false,true);
				}
				header('Location: http://'.$_SERVER["HTTP_HOST"]); 
				exit();
			}

			if($_main=="recovery"){
			    $s="SELECT adm_usuarios.ID_USUARIO,
			    adm_usuarios.NOMBRE_U,
			    adm_usuarios.CORREO_U
			    FROM adm_usuarios
			    JOIN adm_usuarios_rec ON adm_usuarios_rec.ID_USUARIO=adm_usuarios.ID_USUARIO AND adm_usuarios_rec.USO=0 AND UTC_TIMESTAMP()<=DATE_ADD(FECHA,INTERVAL 2 DAY)
			    WHERE CONCAT(SHA1(CONCAT(adm_usuarios.ID_USUARIO,adm_usuarios.PASSWORD_U)),MD5(CONCAT(adm_usuarios.CORREO_U,adm_usuarios.FECHA_U)),SHA1(adm_usuarios_rec.ID_REC))=:codrec";
			    $reqPASS = $dbEmpresa->prepare($s); 
			    $reqPASS->bindParam(':codrec', $_GET["code"]);
			    $reqPASS->execute();
			    if(! $regPASS = $reqPASS->fetch()) header('Location: //'.$_SERVER["HTTP_HOST"]); 
			}

			if($dom_fuera){			
			    $Launch=sprintf("_launch/%s-%s/index.php",$_PROYECTO,$_EMPRESA);
			    $Launch_CSS=sprintf("_launch/%s-%s/css/css.css",$_PROYECTO,$_EMPRESA);
			    $PasteCSS=file_exists($Launch_CSS);
			    
			    if(file_exists($Launch)){
			    	$include_file=$Launch;
			    	$Launch_Dir=sprintf("_launch/%s-%s",$_PROYECTO,$_EMPRESA);
			    }
			    else{	        
			    	$include_file="_launch/generic/index.php";
			    	$Launch_Dir='_launch/generic';
			    }


			}
			$tp_include='';
		}
	}
}
else{
	$ShowNotify=false;
	$StandardPage=true;
	$include_file="_extras/nologin.php";
	$Launch_Dir='_launch/generic';
}
if($tp_include=='code'){
	if($OResponse){
		//VERIFICACION DE PERMISOS
		$md=$result["md"];
		$id_sha=substr($md,0,40);
		$c_sha=substr($md,40,32);


		if($MField)	$m=$result["m"];

		if($std_res){
			$id_sha_t=substr($md,72,40);
			$det_plus=intval(substr($md,112,3));
		}
		if($elim_res){
			$id_sha_t=substr($md,104,40);
			$accion=substr($md,72,32);
		}
		if($conf_res){
			$t=substr($md,72,1);	
		}

		//Determina CNF
		if($det_cnf){
			$sWhere=encrip_mysql('adm_ventanas.ID_VENTANA',2);
			$s=$sqlCons[1][71]." WHERE $sWhere=:c_sha LIMIT 1";
			$req = $dbEmpresa->prepare($s); 
			$req->bindParam(':c_sha', $c_sha);
			$req->execute();	
			if($reg = $req->fetch())	$cnf=$reg["ID_VENTANA"];
		}
		$nuevo=(nuevo_item()==$id_sha);
		$control_img=$result["control_img"];

		/********************************************/
		/********************************************/
		/********************************************/
		/********************************************/
		$error=false;
		$badfields=array();
		$remove=array();
		$auto=array();
		$node=array();
		$localstorage=array();
		$message_esp=array();
		$mensaje=array();
		$reload=false;
		$close=true;
		$words=false;
		$rewrite=false;
		$go=false;
		$captcha=false;
		/********************************************/
		/********************************************/
		include $include_file;
		if(isset($acnf))	$auto=array('cnf'=>$acnf);
		else 				$auto=array('cnf'=>$cnf);
		if(isset($e)){
			$hide['msg']=$e->getMessage();
			$hide['line']=$e->getLine();
			$hide['file']=$e->getFile();
			$hide['include']=$include_file;
			$hide['result']=$result;
	
			ErrMSG($e,$_REQUEST);
			$error=true;
			$mensaje[]='txt-MSJ9-0';
		}
		$salidas["status"]["key"]=$_key;
		$salidas["status"]["error"]=$error;
		$salidas["status"]["captcha"]=$captcha;
		$salidas["status"]["badfields"]=$badfields;
		$salidas["status"]["reload"]=$error?false:$reload;
		$salidas["status"]["close"]=$error?false:$close;
		$salidas["status"]["words"]=$words;
		$salidas["status"]["rewrite"]=$rewrite;
		$salidas["status"]["go"]=$go;
		$salidas["hide"]=$hide;
		$salidas["node"]=$node;
		$salidas["localstorage"]=$localstorage;
		$salidas["remove"]=$remove;
		if(!$error&&count($mensaje)==0)
			$salidas["mensaje"][]='txt-MSJ100-0';
		else
			$salidas["mensaje"]=$mensaje;
		$salidas["message_esp"]=$message_esp;
		$salidas["request"]=$request;
		$salidas["auto"]=$auto;
		
		echo json_encode($salidas);
	}
	else 	include $include_file;
}
else{
?><!doctype html>
	<html lang="<?php echo $_LANG_DEF?>" data-_token_a="<?php echo $_sysvars["_token_a"]?>" data-_token_b="<?php echo $_sysvars["_token_b"]?>" data-_session="<?php echo $_sysvars["_session"]?>">
		<?php include("_inc/inchead.php");?>		

		<body class="_<?php echo $colors?>">
			<?php 
			include "_inc/body_before.php";
			?><div class="bwrap"><?php
			if($tp_include=='header-footer')	include $_def_head;
			?><div id="wrap" class="<?php echo $def_class?>"><?php include $include_file;?></div><?php if($ShowNotify){?><aside class="notify col_bg01" data-id="notify"></aside><?php }
			if($tp_include=='header-footer')	include $_def_footer;
			?></div><?php
			include "_inc/body_after.php";
			?>
		</body>
</html><?php 
}?>
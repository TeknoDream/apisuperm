<?php
/* SUPER MAESTROS */
use Aws\Common\Aws;		
use Parse\ParseClient;
use Parse\ParseQuery;
use Parse\ParseObject;
use Parse\ParsePush;
use Parse\ParseInstallation;


use ElephantIO\Client,
    ElephantIO\Engine\SocketIO\Version0X;

/*17886da91cfc4e657f64df6ad12ae46d0cd2f77c-83533*/
/*11b0157e11e26c6bbdb61e741db444c9cc056065-83536*/

/*
5000	Instaladores	GET
5010	Remodelacion	GET
5015	Remodelacion Nuevo/Editar/Eliminar	POST
5016	Foto Remodelacion Nuevo/Editar/Eliminar	POST
5017	Calificar Remodelacion	POST
5018	Comentario Remodelacion	POST
5020	Proyecto	GET
5025	Proyecto Nuevo/Editar/Eliminar	POST
5030	Facturas	GET
5035	Facturas Nuevo/Editar/Eliminar	POST
5040	Cotizaciones	GET
5045	Cotizaciones Nuevo/Editar/Eliminar	POST
5050	Estado de Cuenta	GET
5060	Noticias	GET
5070	Publicidad	GET
5080	Mensajes	GET
5085	Mensajes Nuevo	POST
10000	Actualización de Tablas	GET
10001	Info General	GET
10002	Contáctenos	POST
10005	Textos Lite	GET
10013	Verificar email	GET
10100	Crear Cuenta	POST
10101	Verificación de Sesión	GET
10103	Cambiar Contraseña	POST
10104	Recuperación de Contraseña	POST
10106	Modificar correo eletrónico	POST
10107	Registrar con Facebook	POST
10108	Ingreso con Facebook	GET
10109	Conectar con mi cuenta de facebook	POST
10110	Cambiar Foto de Usuario	POST
10111	Cerrar Sesión	GET
20000	Home Page	GET
20002	Editar información de usuario	POST

*/
$UserType=0;
$MaxItems=45;
$control_img="imagen";

// Identificar mi tipo de usuario
if($verificar){
	$s="SELECT x_usuario.TYPE_USUARIO
		,		x_usuario.VERIF_USUARIO
		FROM x_usuario
		WHERE ID_USUARIO=:_USUARIO LIMIT 1";
	$req = $dbEmpresa->prepare($s);
	$req->bindParam(':_USUARIO', $_USUARIO);
	$req->execute();
	if($reg = $req->fetch())
		$UserType=$reg['VERIF_USUARIO']==1?$reg['TYPE_USUARIO']:0;
}

// Identificar Proyecto
$proyecto=isset($_REQUEST["proyecto"])?$_REQUEST["proyecto"]:0; 
$IdProyecto=0;	
$IdUserProyect=0;
$PyValCalif=0;
if($proyecto!=0){		
	$s='SELECT 
			ID_PROY
		,	ID_USUARIO
	FROM y_proyectos 
	WHERE y_proyectos.ID_PROY=:proyecto LIMIT 1';
	$req = $dbEmpresa->prepare($s);
	$req->bindParam(':proyecto', $proyecto);
	$req->execute();
	if($reg = $req->fetch()){
		$IdProyecto=$reg['ID_PROY'];
		$IdUserProyect=$reg['ID_USUARIO'];
	}	
}

// Identificar Foto de Proyecto
$pyfoto=isset($_REQUEST["pyfoto"])?$_REQUEST["pyfoto"]:0; 
$IdPYFoto=0;	
if($IdProyecto!=0&&$pyfoto!=0){		
	$s='SELECT 
			ID_FOTO
	FROM y_proyectos_fotos 
	WHERE y_proyectos_fotos.ID_PROY=:IdProyecto AND y_proyectos_fotos.ID_FOTO=:pyfoto LIMIT 1';
	$req = $dbEmpresa->prepare($s);
	$req->bindParam(':IdProyecto', $IdProyecto);
	$req->bindParam(':pyfoto', $pyfoto);
	$req->execute();
	if($reg = $req->fetch())
		$IdPYFoto=$reg['ID_FOTO'];
}
// Identificar Commentario de Proyecto
$pycomment=isset($_REQUEST["pycomment"])?$_REQUEST["pycomment"]:0; 
$IdComment=0;	
$IdCommentUser=0;
if($IdProyecto!=0&&$pycomment!=0){		
	$s='SELECT 
			ID_COMMENT
		,	ID_USUARIO
	FROM y_proyectos_comment 
	WHERE y_proyectos_comment.ID_PROY=:IdProyecto AND y_proyectos_comment.ID_COMMENT=:pycomment LIMIT 1';
	$req = $dbEmpresa->prepare($s);
	$req->bindParam(':IdProyecto', $IdProyecto);
	$req->bindParam(':pycomment', $pycomment);
	$req->execute();
	if($reg = $req->fetch()){
		$IdComment=$reg['ID_COMMENT'];
		$IdCommentUser=$reg['ID_USUARIO'];
	}
}


//Identificar Usuario (p.e. Enviar un mensaje)
$user=isset($_REQUEST["user"])?$_REQUEST["user"]:0;
$IdUser=0;
if($user!=''){		
	$s='SELECT 
			ID_USUARIO
	FROM adm_usuarios 
	WHERE (adm_usuarios.ID_USUARIO=:user OR adm_usuarios.ALIAS=:user OR adm_usuarios.CORREO_U=:user) LIMIT 1';
	$req = $dbEmpresa->prepare($s);
	$req->bindParam(':user', $user);
	$req->execute();
	if($reg = $req->fetch())
		$IdUser=$reg['ID_USUARIO'];
}

//Identificar Oferta
$oferta=isset($_REQUEST["oferta"])?$_REQUEST["oferta"]:0;
$IdOferta=0;	
$IdUserOferta=0;
if($oferta!=0){	 
	$s='SELECT 
			ID_OFERTA
		,	ID_USUARIO
	FROM x_ofertas 
	WHERE x_ofertas.ID_OFERTA=:oferta LIMIT 1';
	$req = $dbEmpresa->prepare($s);
	$req->bindParam(':oferta', $oferta);
	$req->execute();
	if($reg = $req->fetch()){
		$IdOferta=$reg['ID_OFERTA'];
		$IdUserOferta=$reg['ID_USUARIO'];
	}	
}

//Identificar Factura
$factura=isset($_REQUEST["factura"])?$_REQUEST["factura"]:0;
$IdFactura=0;	
$IdUserFactura=0;
if($factura!=0){	 
	$s='SELECT 
			ID_FACT
		,	ID_USUARIO
	FROM y_facturas 
	WHERE y_facturas.ID_FACT=:factura LIMIT 1';
	$req = $dbEmpresa->prepare($s);
	$req->bindParam(':factura', $factura);
	$req->execute();
	if($reg = $req->fetch()){
		$IdFactura=$reg['ID_FACT'];
		$IdUserFactura=$reg['ID_USUARIO'];
	}	
}

//Identificar Cotización
$cotizacion=isset($_REQUEST["cotizacion"])?$_REQUEST["cotizacion"]:0;
$IdCotiz=0;	
$IdUserCotiz=0;
$IdUserOwnCotiz=0;
if($cotizacion!=0){	 
	$s='SELECT 
			ID_COTIZ
		,	ID_USUARIO_M
		,	ID_USUARIO_U
	FROM y_cotizacion 
	WHERE y_cotizacion.ID_COTIZ=:cotizacion LIMIT 1';

	$req = $dbEmpresa->prepare($s);
	$req->bindParam(':cotizacion', $cotizacion);
	$req->execute();
	if($reg = $req->fetch()){
		$IdCotiz=$reg['ID_COTIZ'];
		$IdUserCotiz=$reg['ID_USUARIO_U'];
		$IdUserOwnCotiz=$reg['ID_USUARIO_M'];
	}	
}
include 'api_032_fnc.php';


if($tp==20000){ //Home Page	
	$_PREFIX=GetPrefixURL($dbEmpresa);
	$ESTTZ = new DateTimeZone('UTC');
	$hoyOBJ = new DateTime(date(DATE_ATOM),$ESTTZ); 
	$hoySTR=$hoyOBJ->format('Y-m-d H:i');
	
	// Maestros
	$_filtros=' WHERE adm_usuarios.HAB_U=0 AND x_usuario.TYPE_USUARIO=1 AND x_usuario.VERIF_USUARIO=1 ';
	if($verificar&&$UserType==1)
		$_filtros.=' AND adm_usuarios.ID_USUARIO<>:_USUARIO ';
	if($IdUser!=0)
		$_filtros.=' AND adm_usuarios.ID_USUARIO<>:IdUser ';

	$s=$sqlCons[0][500].$_filtros.' ORDER BY x_usuario.DEST_USUARIO DESC LIMIT 4';
	$req = $dbEmpresa->prepare($s);
	if($verificar&&$UserType==1)
		$req->bindParam(':_USUARIO', $_USUARIO);	
	if($IdUser!=0)
		$req->bindParam(':IdUser', $IdUser);
	$req->execute();
	$salidas['maestros']=Maestros($req);

	// OFERTAS
	$_filtros=" WHERE '$hoySTR' BETWEEN x_ofertas.FECHAI_OFERTA AND x_ofertas.FECHAF_OFERTA AND x_ofertas.HAB_OFERTA=0 AND adm_usuarios.HAB_U=0  ";
	$s=$sqlCons[0][502].$_filtros.$sqlOrder[0][502].' LIMIT 6';
	$req = $dbEmpresa->prepare($s);	
	$req->execute();
	$salidas['ofertas']=Ofertas($req);

	// Publicidad
	$_filtros=" WHERE '$hoySTR' BETWEEN y_publicidad.FECHAI_PUBL AND y_publicidad.FECHAF_PUBL AND y_publicidad.HAB_PUBL=0 AND y_publicidad.ACTI_PUBL=1 ";
	$s=$sqlCons[0][507].$_filtros." LIMIT 1";
	$req = $dbEmpresa->prepare($s);	
	$req->execute();
	$salidas['publicidad']=Publicidad($req);
}
// instaladores
elseif($tp==5000){
	$_PREFIX=GetPrefixURL($dbEmpresa);	
	if($user==''){
		$PagActual=$_REQUEST["page"]!=''?$_REQUEST["page"]:1;
		$IniDato=($PagActual-1)*$MaxItems;
        //////////////////////////////////////////////////////////////
		$espec=isset($_REQUEST["espec"])?$_REQUEST["espec"]:0; 
		$ciudad=isset($_REQUEST["ciudad"])?$_REQUEST["ciudad"]:0; 
		$term=isset($_REQUEST["term"])?urldecode($_REQUEST["term"]):''; 
		$term_query='%'.$term.'%';

		$_filtros=' WHERE adm_usuarios.HAB_U=0 AND x_usuario.TYPE_USUARIO=1 AND x_usuario.VERIF_USUARIO=1 ';
		if($espec!=0)	$_filtros.=' AND adm_usuarios.ID_USUARIO IN (
												SELECT x_usuario_espec.ID_USUARIO 
												FROM x_usuario_espec 
												WHERE x_usuario_espec.ID_ESPEC=:espec)';
		if($ciudad!=0)	$_filtros.=' AND x_usuario.ID_CIUDAD=:ciudad';


		if($term!='')	$_filtros.=' AND(adm_usuarios.ID_USUARIO IN (
												SELECT x_usuario_char.ID_USUARIO 
												FROM x_usuario_char 
												WHERE x_usuario_char.KEY_CHAR LIKE :Buscar)
										OR CONCAT(adm_usuarios.NOMBRE_U," ",adm_usuarios.APELLIDO_U) LIKE :Buscar
										OR adm_usuarios.NOMBRE_U LIKE :Buscar
										OR adm_usuarios.APELLIDO_U LIKE :Buscar)';

		/*INICIA*/		
		$s=$sqlCons[0][500].$_filtros; 
		$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";

		$reqC = $dbEmpresa->prepare($s); 
		if($espec!=0) 	$reqC->bindParam(':espec', $espec);
		if($ciudad!=0) 	$reqC->bindParam(':ciudad', $ciudad);
		if($term!='') 	$reqC->bindParam(':Buscar', $term_query, PDO::PARAM_STR);			
		$reqC->execute(); 
		$Total = $reqC->fetchColumn();
		$IniDato=($PagActual-1)*$MaxItems;	
		
		/*TABLA*/
		$s=$sqlCons[0][500].$_filtros." ORDER BY x_usuario.DEST_USUARIO DESC LIMIT $IniDato,$MaxItems";
		$reqItem = $dbEmpresa->prepare($s);	
		if($espec!=0) 	$reqItem->bindParam(':espec', $espec);
		if($ciudad!=0) 	$reqItem->bindParam(':ciudad', $ciudad);
		if($term!='') 	$reqItem->bindParam(':Buscar', $term_query, PDO::PARAM_STR);
		$reqItem->execute();
		$salidas['maestros']=Maestros($reqItem);
		$salidas['pages']=array('act'=>$PagActual,'max'=>$Total);
	}
	else{
		$_filter=' WHERE adm_usuarios.ID_USUARIO=:IdUser AND adm_usuarios.HAB_U=0 AND x_usuario.TYPE_USUARIO=1 AND x_usuario.VERIF_USUARIO=1 ';
		$s=$sqlCons[0][500].$_filter." LIMIT 1";
		$reqItem = $dbEmpresa->prepare($s);	
		$reqItem->bindParam(':IdUser', $IdUser);	
		$reqItem->execute();
		$options=array('full'=>true);
		$salidas['maestros']=Maestros($reqItem,$options);
	}
}
// Proyectos (Mostrar)
elseif($tp==5010){
	$_PREFIX=GetPrefixURL($dbEmpresa);
	if($proyecto==''){	
		$PagActual=$_REQUEST["page"]!=''?$_REQUEST["page"]:1;
		$IniDato=($PagActual-1)*$MaxItems;
		$own=$_REQUEST['own']==1;
		$term=isset($_REQUEST["term"])?urldecode($_REQUEST["term"]):''; 
		$term_query='%'.$term.'%';

		if($own){
			$_filtros=' WHERE adm_usuarios.HAB_U=0 AND adm_usuarios.ID_USUARIO=:_USUARIO ';
		}
		else{
			$_filtros=' WHERE adm_usuarios.HAB_U=0 AND y_proyectos.STATUS_PROY=1 AND y_proyectos.HAB_PROY=0 AND x_usuario.VERIF_USUARIO=1 ';
			if($IdUser!=0) 		$_filtros.=' AND adm_usuarios.ID_USUARIO=:IdUser ';				
			if($term!='')		$_filtros.=' AND (y_proyectos.NOMB_PROY LIKE :Buscar) ';
		}

		/*INICIA*/		
		//$s=$sqlCons[0][501].$_filtros; 
		//$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";

		$reqC = $dbEmpresa->prepare($s); 
		if($IdUser!=0) 	$reqC->bindParam(':IdUser', $IdUser);	
		if($own) 		$reqC->bindParam(':_USUARIO', $_USUARIO);
		if($term!='') 	$reqC->bindParam(':Buscar', $term_query, PDO::PARAM_STR);		
		$reqC->execute(); 
		$Total = $reqC->fetchColumn();

		$IniDato=($PagActual-1)*$MaxItems;	
		$MMaxItems=$MaxItems+1;

		$s=$sqlCons[0][501].$_filtros.$sqlOrder[0][501]." LIMIT $IniDato,$MMaxItems";
		$reqItem = $dbEmpresa->prepare($s);	
		if($IdUser!=0) 	$reqItem->bindParam(':IdUser', $IdUser);
		if($own) 		$reqItem->bindParam(':_USUARIO', $_USUARIO);
		if($term!='') 	$reqItem->bindParam(':Buscar', $term_query, PDO::PARAM_STR);
		$reqItem->execute();	
		$salidas['proyectos']=Proyectos($reqItem);
	  //$salidas['pages']=array('act'=>$PagActual,'max'=>$Total,'res_per_page'=>$MaxItems);
		$salidas['pages']=array('act'=>$PagActual,'max'=>$MMaxItems,'res_per_page'=>$MaxItems);
	}
	else{
		$_filtros=' WHERE y_proyectos.ID_PROY=:IdProyecto AND adm_usuarios.HAB_U=0 AND y_proyectos.HAB_PROY=0 ';
		$s=$sqlCons[0][501].$_filtros." LIMIT 1";
		$reqItem = $dbEmpresa->prepare($s);	
		$reqItem->bindParam(':IdProyecto', $IdProyecto);
		$reqItem->execute();
		$options=array('full'=>true);
		$salidas['proyectos']=Proyectos($reqItem,$options);
	}
}
// Proyectos (Nuevo/Editar/Eliminar)
elseif($tp==5015){
	$_PREFIX=GetPrefixURL($dbEmpresa);

	if($UserType==0)			$error=10002;
	if($_REQUEST['accion']!='edit'
		&&$_REQUEST['accion']!='delete'
		&&$_REQUEST['accion']!='new')	$error=10001;

	if($error==0){
		$Edit=$_REQUEST['accion']=='edit';
		$Delete=$_REQUEST['accion']=='delete';
		$New=$_REQUEST['accion']=='new';
		$Allow=(($Edit||$Delete)&&($IdUserProyect==$_USUARIO))||$New;
		if(!$Allow) $error=5011;
	}

	if($error==0&&($New||$Edit)&&$_REQUEST['nomb']=='')	$error=5012;

	if($error==0){
		if($New||$Edit){
			$id_print=$New?'NULL':$IdProyecto;
			try{ 
				$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
				$dbEmpresa->beginTransaction();
				$s="INSERT INTO y_proyectos
						(ID_PROY
					,	ID_USUARIO
					,	FECHAS_PROY
					,	NOMB_PROY
					,	DESC_PROY)
					VALUES
						($id_print
					,	:_USUARIO
					,	UTC_TIMESTAMP()
					,	:nomb
					,	:desc)
					ON DUPLICATE KEY UPDATE
						NOMB_PROY=:nomb
					,	DESC_PROY=:desc";
				$req = $dbEmpresa->prepare($s); 
				$req->bindParam(':_USUARIO', $_USUARIO);
				$req->bindParam(':nomb', $_REQUEST['nomb']);
				$req->bindParam(':desc', $_REQUEST['desc']);
				$req->execute();
				if($New) $IdProyecto=$dbEmpresa->lastInsertId();

				


				$dbEmpresa->commit();
				$salidas["transaction"]='OK';
				$salidas["IdProyecto"]=$IdProyecto;
			}
			catch (Exception $e){
				$dbEmpresa->rollBack();
				$err_str=$e->getMessage();
			}
		}
		elseif($Delete){
			try{ 
				$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
				$dbEmpresa->beginTransaction();

				$s='DELETE FROM y_proyectos WHERE ID_PROY=:IdProyecto LIMIT 1';
				$req = $dbEmpresa->prepare($s); 
				$req->bindParam(':IdProyecto', $IdProyecto);
				$req->execute();

				$dbEmpresa->commit();
				$salidas["transaction"]='OK';
			}
			catch (Exception $e){
				$dbEmpresa->rollBack();
				unset($e);

				$s='UPDATE y_proyectos 
					SET HAB_PROY=1
					WHERE ID_PROY=:IdProyecto LIMIT 1';
				$req = $dbEmpresa->prepare($s); 
				$req->bindParam(':IdProyecto', $IdProyecto);
				$req->execute();
				$salidas["transaction"]='OK';
			}
		}

		$s='UPDATE x_usuario
			SET PROYS_USAURIO=(SELECT COUNT(*) FROM y_proyectos WHERE ID_USUARIO=:_USUARIO AND HAB_PROY=0)
			WHERE ID_USUARIO=:_USUARIO LIMIT 1';
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':_USUARIO', $_USUARIO);
		$req->execute();
	}	
}
// ProyectosFOTO (Nuevo/Editar/Eliminar)
elseif($tp==5016){
	$_PREFIX=GetPrefixURL($dbEmpresa);

	if($UserType==0)			$error=10002;
	if($_REQUEST['accion']!='edit'
		&&$_REQUEST['accion']!='delete'
		&&$_REQUEST['accion']!='new'
		&&$_REQUEST['accion']!='order')	$error=10001;

	if($error==0){
		$Edit=$_REQUEST['accion']=='edit';
		$Delete=$_REQUEST['accion']=='delete';
		$New=$_REQUEST['accion']=='new';
		$Order=$_REQUEST['accion']=='order';
		$Allow=$IdUserProyect==$_USUARIO;
		if(!$Allow) $error=5011;
	}
	//setImagen
	if($error==0&&(($New||$Edit)&&$_REQUEST['title']==''))	$error=5013;

	if($error==0){
		if($New||$Edit||$Order){
			$id_print=$New?'NULL':$IdPYFoto;
			try{ 
				$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
				$dbEmpresa->beginTransaction();

				$ord=$_REQUEST['ord']==''?100:$_REQUEST['ord'];
				if($Order){
					if($_REQUEST['omain']==1){					
						$s='UPDATE y_proyectos_fotos
							SET MAIN_FOTO=0
							WHERE ID_PROY=:IdProyecto AND MAIN_FOTO=1';
						$req = $dbEmpresa->prepare($s); 
						$req->bindParam(':IdProyecto', $IdProyecto);
						$req->execute();

						$s='UPDATE y_proyectos_fotos
							SET MAIN_FOTO=1
							WHERE ID_PROY=:IdProyecto AND ID_FOTO=:IdPYFoto LIMIT 1';
						$req = $dbEmpresa->prepare($s); 
						$req->bindParam(':IdProyecto', $IdProyecto);
						$req->bindParam(':IdPYFoto', $IdPYFoto);
						$req->execute();						
					}
					else{
						$s="UPDATE y_proyectos_fotos
							SET ORD_FOTO=:ord
							WHERE ID_FOTO=:IdPYFoto LIMIT 1";
						$req = $dbEmpresa->prepare($s); 
						$req->bindParam(':IdPYFoto', $IdPYFoto);
						$req->bindParam(':ord', $ord);
						$req->execute();
					}				
				}
				else{
					$main=$_REQUEST['main']==1?1:0;
					if($_REQUEST['main']==1){
						$s='UPDATE y_proyectos_fotos
							SET MAIN_FOTO=0
							WHERE ID_PROY=:IdProyecto AND MAIN_FOTO=1';
						$req = $dbEmpresa->prepare($s); 
						$req->bindParam(':IdProyecto', $IdProyecto);
						$req->execute();	
					}

					$s="INSERT INTO y_proyectos_fotos
							(ID_FOTO
						,	ID_PROY
						,	FECHAS_FOTO
						,	TITLE_FOTO
						,	ORD_FOTO
						,	MAIN_FOTO)
						VALUES
							($id_print
						,	:IdProyecto
						,	UTC_TIMESTAMP()
						,	:title
						,	:ord
						,	:main)
						ON DUPLICATE KEY UPDATE
							TITLE_FOTO=:title
						,	ORD_FOTO=:ord
						,	MAIN_FOTO=:main";
					$req = $dbEmpresa->prepare($s); 
					$req->bindParam(':IdProyecto', $IdProyecto);
					$req->bindParam(':title', $_REQUEST['title']);
					$req->bindParam(':ord', $ord);
					$req->bindParam(':main', $main);
					$req->execute();
					if($New) $IdPYFoto=$dbEmpresa->lastInsertId();
					
					$salidas["upload"]='NO';
					// SOLO SE EDITA LA IMAGEN SI SE ENVIA setImage en 1
					if($_REQUEST["setImage"]==1&&isset($_FILES[$control_img])){

						$_PARAMETROS=ParametrosAPI($dbEmpresa,$_sysvars_r,$_sysvars);
						$cnf=501;
						$IdItem=$IdPYFoto;
						require 		"../phplib/s3/aws.phar";
						$UploadDeleteArgs=array(
									'S3_BUCKET'=>$_PARAMETROS["S3_BUCKET"]
								,	'PROYECTO'=>$_PROYECTO
								,	'EMPRESA'=>$_EMPRESA
								,	'MODULE'=>$cnf
								,	'OBJECT'=>$IdItem
								,	'TP_FILE'=>'img');

						$tamano=$_FILES[$control_img]["size"];
						$ubicacion=$_FILES[$control_img]["tmp_name"];
						$finfo = finfo_open(FILEINFO_MIME_TYPE); 	
						$tipo=finfo_file($finfo, $ubicacion);	
						finfo_close($finfo);
						$nombre=$_FILES[$control_img]["name"];				

						$tipo=$_FILES[$control_img]["type"];	
						$tipo_array=explode("/", $tipo); // Separa el mime					
							
						//if($tipo_array[0]=='image'){
							$AwsS3 = Aws::factory(array('key' => $_PARAMETROS['S3_KEY'],'secret' => $_PARAMETROS['S3_SECRET']));
							UploadFiles($AwsS3,$control_img,$dbEmpresa,$UploadDeleteArgs,$Info);	

							$s="UPDATE y_proyectos_fotos 
								SET ID_FILE=IFNULL((SELECT adm_files.ID_FILE FROM adm_files WHERE adm_files.F_MODULE=$cnf AND adm_files.ID_OBJECT=$IdItem AND adm_files.F_SUB_EXT='org' AND adm_files.F_TP_FILE='img' LIMIT 1),0)
								WHERE ID_FOTO=$IdItem";
							$dbEmpresa->exec($s);
							$salidas["upload"]='OK';
							
						/*}
						else $error=5014;*/
					}										
					$salidas["IdPYFoto"]=$IdPYFoto;	
				}	
				$dbEmpresa->commit();
				$salidas["transaction"]='OK';		
			}
			catch (Exception $e){
				$dbEmpresa->rollBack();
				$err_str=$e->getMessage();
			}
		}
		elseif($Delete){
			try{ 
				$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
				$dbEmpresa->beginTransaction();

				$s='DELETE FROM y_proyectos_fotos WHERE ID_FOTO=:IdPYFoto LIMIT 1';
				$req = $dbEmpresa->prepare($s); 
				$req->bindParam(':IdPYFoto', $IdPYFoto);
				$req->execute();

				// BORRA LA FOTO
				$_PARAMETROS=ParametrosAPI($dbEmpresa,$_sysvars_r,$_sysvars);
				$cnf=501;
				$IdItem=$IdPYFoto;
				require 		"../phplib/s3/aws.phar";
				$UploadDeleteArgs=array(
							'S3_BUCKET'=>$_PARAMETROS["S3_BUCKET"]
						,	'PROYECTO'=>$_PROYECTO
						,	'EMPRESA'=>$_EMPRESA
						,	'MODULE'=>$cnf
						,	'OBJECT'=>$IdItem
						,	'TP_FILE'=>'img');
				$AwsS3 = Aws::factory(array('key' => $_PARAMETROS['S3_KEY'],'secret' => $_PARAMETROS['S3_SECRET']));
				DeleteFiles($AwsS3,$dbEmpresa,$UploadDeleteArgs);

				$dbEmpresa->commit();
				$salidas["transaction"]='OK';
			}
			catch (Exception $e){
				$dbEmpresa->rollBack();
				$err_str=$e->getMessage();
			}
		}
	}	
}
// Proyectos Calificar
elseif($tp==5017){

	if($IdProyecto==0) $error=5016;
	
	if($error==0){
		$score=$_REQUEST['score']==''?1:$_REQUEST['score'];
		
		try{ 
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();
			$s="INSERT INTO y_proyectos_calif
					(ID_PROY
				,	ID_USAURIO
				,	VAL_CALIF
				,	FECHAS_CALIF)
				VALUES
					(:IdProyecto
				,	:_USUARIO
				,	:score
				,	UTC_TIMESTAMP())
				ON DUPLICATE KEY UPDATE
					VAL_CALIF=:score
				,	FECHAS_CALIF=UTC_TIMESTAMP()";
			$req = $dbEmpresa->prepare($s); 
			$req->bindParam(':IdProyecto', $IdProyecto);
			$req->bindParam(':_USUARIO', $_USUARIO);
			$req->bindParam(':score', $score);
			$req->execute();



			$s='SELECT COUNT(y_proyectos_calif.VAL_CALIF) AS CANT
			,	SUM(y_proyectos_calif.VAL_CALIF) AS SUM
			FROM y_proyectos_calif
			WHERE y_proyectos_calif.ID_PROY=:IdProyecto';
			$req = $dbEmpresa->prepare($s); 
			$req->bindParam(':IdProyecto', $IdProyecto);
			$req->execute();
			if($reg = $req->fetch()){
				$PCant=$reg['CANT'];
				$PSum=$reg['SUM'];
				if($PCant>0)	$PVal=$PSum/$PCant;			

				//General Proyecto
				$s='UPDATE y_proyectos
					SET 
						GCALIF_PROY=:PSum
					,	CCALIF_PROY=:PCant
					,	VCALIF_PROY=:PVal
					WHERE ID_PROY=:IdProyecto LIMIT 1';
				$req = $dbEmpresa->prepare($s); 
				$req->bindParam(':PSum', $PSum);
				$req->bindParam(':PCant', $PCant);
				$req->bindParam(':PVal', $PVal);
				$req->bindParam(':IdProyecto', $IdProyecto);
				$req->execute();

				$salidas["calif"]=array(
									'worst'=>1
								,	'best'	=>5
								,	'value'	=>$PVal);
			}

			$s='SELECT COUNT(y_proyectos_calif.VAL_CALIF) AS CANT
			,	SUM(y_proyectos_calif.VAL_CALIF) AS SUM
			FROM y_proyectos
			LEFT JOIN y_proyectos_calif ON y_proyectos_calif.ID_PROY=y_proyectos.ID_PROY
			WHERE y_proyectos.ID_USUARIO=:IdUserProyect';
			$req = $dbEmpresa->prepare($s); 
			$req->bindParam(':IdUserProyect', $IdUserProyect);
			$req->execute();
			if($reg = $req->fetch()){
				$PUCant=$reg['CANT'];
				$PUSum=$reg['SUM'];
				if($PUCant>0)	$PUVal=$PUSum/$PUCant;			

				//General Usuario
				$s='UPDATE x_usuario
					SET 
						GCALIF_USUARIO=:PUSum
					,	CCALIF_USUARIO=:PUCant
					,	VCALIF_USUARIO=:PUVal
					WHERE ID_USUARIO=:IdUserProyect LIMIT 1';
				$req = $dbEmpresa->prepare($s); 
				$req->bindParam(':IdUserProyect', $IdUserProyect);
				$req->bindParam(':PUSum', $PUSum);
				$req->bindParam(':PUCant', $PUCant);
				$req->bindParam(':PUVal', $PUVal);
				$req->execute();
			}
			$dbEmpresa->commit();
			$salidas["transaction"]='OK';
		
		}
		catch (Exception $e){
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}		
	}	
}
// Comentario (Nuevo/Editar/Eliminar)
elseif($tp==5018){
	$_PREFIX=GetPrefixURL($dbEmpresa);

	if($_REQUEST['accion']!='delete'
		&&$_REQUEST['accion']!='new')	$error=10001;

	if($error==0){
		$Delete=$_REQUEST['accion']=='delete';
		$New=$_REQUEST['accion']=='new';
		$Allow=($Delete&&$IdCommentUser==$_USUARIO)||$New;
		if(!$Allow) $error=5018;
	}
	//setImagen
	if($error==0&&($New&&$_REQUEST['comment']==''))	$error=5017;

	if($error==0){
		if($New){
			$id_print=$New?'NULL':$IdComment;
			try{ 
				$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
				$dbEmpresa->beginTransaction();

				$s="INSERT INTO y_proyectos_comment
						(ID_COMMENT
					,	ID_USUARIO
					,	ID_PROY
					,	FECHAS_COMMENT
					,	TEXT_COMMENT)
					VALUES
						($id_print
					,	:_USUARIO
					,	:IdProyecto
					,	UTC_TIMESTAMP()
					,	:comment)
					ON DUPLICATE KEY UPDATE
						TEXT_COMMENT=:comment";
				$req = $dbEmpresa->prepare($s); 
				$req->bindParam(':_USUARIO', $_USUARIO);
				$req->bindParam(':IdProyecto', $IdProyecto);
				$req->bindParam(':comment', $_REQUEST['comment']);
				$req->execute();
				if($New) $IdComment=$dbEmpresa->lastInsertId();
				
				$salidas["IdComment"]=$IdComment;					
				$dbEmpresa->commit();
				$salidas["transaction"]='OK';		
			}
			catch (Exception $e){
				$dbEmpresa->rollBack();
				$err_str=$e->getMessage();
			}
		}
		elseif($Delete){
			try{ 
				$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
				$dbEmpresa->beginTransaction();

				$s='DELETE FROM y_proyectos_comment WHERE ID_COMMENT=:IdComment LIMIT 1';
				$req = $dbEmpresa->prepare($s); 
				$req->bindParam(':IdComment', $IdComment);
				$req->execute();			

				$dbEmpresa->commit();
				$salidas["transaction"]='OK';
			}
			catch (Exception $e){
				$dbEmpresa->rollBack();
				$err_str=$e->getMessage();
			}
		}

		$s='UPDATE y_proyectos
			SET COMMENTS_PROY=(SELECT COUNT(*) FROM y_proyectos_comment WHERE ID_PROY=:IdProyecto)
			WHERE ID_PROY=:IdProyecto LIMIT 1';
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':IdProyecto', $IdProyecto);
		$req->execute();
	}	
}
// Ofertas
elseif($tp==5020){

	$_PREFIX=GetPrefixURL($dbEmpresa);
	$ESTTZ = new DateTimeZone('UTC');
	$hoyOBJ = new DateTime(date(DATE_ATOM),$ESTTZ); 
	$hoySTR=$hoyOBJ->format('Y-m-d H:i');

	
	if($IdOferta==0){
		$PagActual=$_REQUEST["page"]!=''?$_REQUEST["page"]:1;
		$IniDato=($PagActual-1)*$MaxItems;

		$espec=isset($_REQUEST["espec"])?$_REQUEST["espec"]:0; 
		$ciudad=isset($_REQUEST["ciudad"])?$_REQUEST["ciudad"]:0; 
		$term=isset($_REQUEST["term"])?urldecode($_REQUEST["term"]):''; 
		$term_query='%'.$term.'%';
		$own=$_REQUEST['own']==1;

		if($own)
			$_filtros=" WHERE adm_usuarios.HAB_U=0 AND x_ofertas.ID_USUARIO=:_USUARIO ";
		else{
			$_filtros=" WHERE adm_usuarios.HAB_U=0 AND x_ofertas.HAB_OFERTA=0 AND '$hoySTR' BETWEEN x_ofertas.FECHAI_OFERTA AND x_ofertas.FECHAF_OFERTA ";
			if($espec!=0)	$_filtros.=' AND z_espec.ID_ESPEC=:espec ';
			if($ciudad!=0)	$_filtros.=' AND x_ofertas.ID_CIUDAD=:ciudad ';
			if($term!='')	$_filtros.=' AND(x_ofertas.TITLE_OFERTA LIKE :Buscar) ';
		}

		/*INICIA*/		
		$s=$sqlCons[0][502].$_filtros; 
		//echo $s;
		$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";

		$reqC = $dbEmpresa->prepare($s); 
		if($own)
			$reqC->bindParam(':_USUARIO', $_USUARIO);
		else{
			if($espec!=0) 	$reqC->bindParam(':espec', $espec);
			if($ciudad!=0) 	$reqC->bindParam(':ciudad', $ciudad);
			if($term!='') 	$reqC->bindParam(':Buscar', $term_query, PDO::PARAM_STR);
		}		
		$reqC->execute(); 
		$Total = $reqC->fetchColumn();
		$IniDato=($PagActual-1)*$MaxItems;	
		
		/*TABLA*/
		$s=$sqlCons[0][502].$_filtros.$sqlOrder[0][502]." LIMIT $IniDato,$MaxItems";
		
		$reqItem = $dbEmpresa->prepare($s);	
		if($own)
			$reqItem->bindParam(':_USUARIO', $_USUARIO);
		else{
			if($espec!=0) 	$reqItem->bindParam(':espec', $espec);
			if($ciudad!=0) 	$reqItem->bindParam(':ciudad', $ciudad);
			if($term!='') 	$reqItem->bindParam(':Buscar', $term_query, PDO::PARAM_STR);
		}
		$reqItem->execute();
		$salidas['ofertas']=Ofertas($reqItem);
		$salidas['pages']=array('act'=>$PagActual,'max'=>$Total,'res_per_page'=>$MaxItems);
	}
	else{
		$_filtros=" WHERE adm_usuarios.HAB_U=0 AND x_ofertas.HAB_OFERTA=0 AND x_ofertas.ID_OFERTA=:IdOferta ";
		$s=$sqlCons[0][502].$_filtros." LIMIT 1";
		$reqItem = $dbEmpresa->prepare($s);	
		$reqItem->bindParam(':IdOferta', $IdOferta);
		$reqItem->execute();
		$salidas['ofertas']=Ofertas($reqItem);
	}
}
// Ofertas (Nuevo/Editar/Eliminar)
elseif($tp==5025){

	if($_REQUEST['accion']!='edit'
		&&$_REQUEST['accion']!='delete'
		&&$_REQUEST['accion']!='new')	$error=10001;

	if($error==0){
		$Edit=$_REQUEST['accion']=='edit';
		$Delete=$_REQUEST['accion']=='delete';
		$New=$_REQUEST['accion']=='new';
		$Allow=(($Edit||$Delete)&&($IdUserOferta==$_USUARIO))||$New;
		if(!$Allow) $error=5021;
	}
	//comentario
	if($error==0&&($New||$Edit)&&(
			$_REQUEST['fechai']==''
		||	$_REQUEST['fechaf']==''
		||	$_REQUEST['title']==''
		||	$_REQUEST['espec']==0
		||	$_REQUEST['ciudad']==0))	$error=5022;

	if($error==0){
		if($New||$Edit){
			$id_print=$New?'NULL':$IdOferta;
			try{ 
				$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
				$dbEmpresa->beginTransaction();
				$s="INSERT INTO x_ofertas
						(ID_OFERTA
					,	TITLE_OFERTA
					,	ID_USUARIO
					,	ID_CIUDAD
					,	ID_ESPEC
					,	FECHAS_OFERTA
					,	FECHAI_OFERTA
					,	FECHAF_OFERTA
					,	COMENT_OFERT
					,	CONTACT_OFERTA)
					VALUES
						($id_print
					,	:title
					,	:_USUARIO
					,	:ciudad
					,	:espec
					,	UTC_TIMESTAMP()
					,	STR_TO_DATE(:fechai,'%Y-%m-%d')
					,	STR_TO_DATE(:fechaf,'%Y-%m-%d')
					,	:comentario
					,	:contact)
					ON DUPLICATE KEY UPDATE
						TITLE_OFERTA=:title
					,	ID_CIUDAD=:ciudad
					,	ID_ESPEC=:espec
					,	FECHAI_OFERTA=STR_TO_DATE(:fechai,'%Y-%m-%d')
					,	FECHAF_OFERTA=STR_TO_DATE(:fechaf,'%Y-%m-%d')
					,	COMENT_OFERT=:comentario
					,	CONTACT_OFERTA=:contact";
				$req = $dbEmpresa->prepare($s); 
				$req->bindParam(':title',  $_REQUEST['title']);
				$req->bindParam(':_USUARIO', $_USUARIO);
				$req->bindParam(':ciudad', $_REQUEST['ciudad']);
				$req->bindParam(':espec', $_REQUEST['espec']);
				$req->bindParam(':fechai', $_REQUEST['fechai']);
				$req->bindParam(':fechaf', $_REQUEST['fechaf']);
				$req->bindParam(':comentario', $_REQUEST['comentario']);
				$req->bindParam(':contact', $_REQUEST['contact']);
				$req->execute();
				if($New) $IdOferta=$dbEmpresa->lastInsertId();
				$dbEmpresa->commit();
				$salidas["transaction"]='OK';
				$salidas["IdOferta"] = $IdOferta;
			}
			catch (Exception $e){
				$dbEmpresa->rollBack();
				$err_str=$e->getMessage();
			}
		}
		elseif($Delete){
			try{ 
				$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
				$dbEmpresa->beginTransaction();

				$s='DELETE FROM x_ofertas WHERE ID_OFERTA=:IdOferta LIMIT 1';
				$req = $dbEmpresa->prepare($s); 
				$req->bindParam(':IdOferta', $IdOferta);
				$req->execute();

				$dbEmpresa->commit();
				$salidas["transaction"]='OK';
			}
			catch (Exception $e){
				$dbEmpresa->rollBack();
				unset($e);

				$s='UPDATE x_ofertas 
					SET HAB_OFERTA=1
					WHERE ID_OFERTA=:IdOferta LIMIT 1';
				$req = $dbEmpresa->prepare($s); 
				$req->bindParam(':IdOferta', $IdOferta);
				$req->execute();
				$salidas["transaction"]='OK';

			}
		}
	}	
}
// Facturas
elseif($tp==5030){	
	$_PREFIX=GetPrefixURL($dbEmpresa);

	if($IdFactura==0){
		$PagActual=$_REQUEST["page"]!=''?$_REQUEST["page"]:1;
		$IniDato=($PagActual-1)*$MaxItems;

		$_filtros=' WHERE y_facturas.ID_USUARIO=:_USUARIO AND adm_usuarios.HAB_U=0 ';

		/*INICIA*/		
		$s=$sqlCons[0][503].$_filtros; 
		$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";

		$reqC = $dbEmpresa->prepare($s); 
		$reqC->bindParam(':_USUARIO', $_USUARIO);		
		$reqC->execute(); 
		$Total = $reqC->fetchColumn();
		$IniDato=($PagActual-1)*$MaxItems;	
		
		/*TABLA*/
		$s=$sqlCons[0][503].$_filtros.$sqlOrder[0][503]." LIMIT $IniDato,$MaxItems";
		$reqItem = $dbEmpresa->prepare($s);	
		$reqItem->bindParam(':_USUARIO', $_USUARIO);
		$reqItem->execute();

		$salidas['ofertas']=Facturas($reqItem);
		$salidas['pages']=array('act'=>$PagActual,'max'=>$Total,'res_per_page'=>$MaxItems);
	}
	else{
		/*TABLA*/
		$_filtros=' WHERE y_facturas.ID_USUARIO=:_USUARIO AND adm_usuarios.HAB_U=0 AND y_facturas.ID_FACT=:IdFactura ';
		$s=$sqlCons[0][503].$_filtros." LIMIT 1";
		$reqItem = $dbEmpresa->prepare($s);	
		$reqItem->bindParam(':_USUARIO', $_USUARIO);
		$reqItem->bindParam(':IdFactura', $IdFactura);
		$reqItem->execute();
		$salidas['facturas']=Facturas($reqItem);
	}
}
// Facturas (Nuevo/Editar/Eliminar)
elseif($tp==5035){

	if($UserType==0)			$error=10002;
	if($_REQUEST['accion']!='edit'
		&&$_REQUEST['accion']!='delete'
		&&$_REQUEST['accion']!='new')	$error=10001;

	if($error==0){
		$Edit=$_REQUEST['accion']=='edit';
		$Delete=$_REQUEST['accion']=='delete';
		$New=$_REQUEST['accion']=='new';
		$Allow=(($Edit||$Delete)&&($IdUserFactura==$_USUARIO))||$New;
		if(!$Allow) $error=5031;
	}

	if($error==0){
		if($New||$Edit){
			$id_print=$New?'NULL':$IdFactura;
			try{ 
				$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
				$dbEmpresa->beginTransaction();

				$points=$_REQUEST['points']==''?0:$_REQUEST['points'];
				$s="INSERT INTO y_facturas
						(ID_FACT
					,	ID_USUARIO
					,	VPOINT_FACT
					,	FECHAF_FACT
					,	FECHAS_FACT
					,	OBS_FACT)
					VALUES
						($id_print
					,	:_USUARIO
					,	:points
					,	UTC_TIMESTAMP()
					,	UTC_TIMESTAMP()
					,	:obs)
					ON DUPLICATE KEY UPDATE
						VPOINT_FACT=:points
					,	FECHAF_FACT=UTC_TIMESTAMP()
					,	OBS_FACT=:obs";
				$req = $dbEmpresa->prepare($s); 
				$req->bindParam(':_USUARIO',  $_USUARIO);
				$req->bindParam(':points', $_REQUEST['points']);
				$req->bindParam(':obs', $_REQUEST['obs']);				
				$req->execute();
				if($New) $IdFactura=$dbEmpresa->lastInsertId();
				$dbEmpresa->commit();
				$salidas["transaction"]='OK';
				$salidas["upload"]='NO';
				$salidas["IdFactura"]=$IdFactura;


				// SOLO SE EDITA LA IMAGEN SI SE ENVIA setImage en 1
				if($_REQUEST["setImage"]==1&&isset($_FILES[$control_img])){
					$_PARAMETROS=ParametrosAPI($dbEmpresa,$_sysvars_r,$_sysvars);
					$cnf=503;
					$IdItem=$IdFactura;
					require 		"../phplib/s3/aws.phar";
					$UploadDeleteArgs=array(
								'S3_BUCKET'=>$_PARAMETROS["S3_BUCKET"]
							,	'PROYECTO'=>$_PROYECTO
							,	'EMPRESA'=>$_EMPRESA
							,	'MODULE'=>$cnf
							,	'OBJECT'=>$IdItem
							,	'TP_FILE'=>'img');

					$tamano=$_FILES[$control_img]["size"];
					$ubicacion=$_FILES[$control_img]["tmp_name"];
					$finfo = finfo_open(FILEINFO_MIME_TYPE); 	
					$tipo=finfo_file($finfo, $ubicacion);	
					finfo_close($finfo);
					$nombre=$_FILES[$control_img]["name"];	
					/*if($tamano<=$fmax){
						if(fValid($tipo,$_files_clase[0])){*/
							$AwsS3 = Aws::factory(array('key' => $_PARAMETROS['S3_KEY'],'secret' => $_PARAMETROS['S3_SECRET']));
							UploadFiles($AwsS3,$control_img,$dbEmpresa,$UploadDeleteArgs,$Info);
							$s="UPDATE y_facturas 
								SET ID_FILE=IFNULL((SELECT adm_files.ID_FILE FROM adm_files WHERE adm_files.F_MODULE=$cnf AND adm_files.ID_OBJECT=$IdItem AND adm_files.F_SUB_EXT='org' AND adm_files.F_TP_FILE='img' LIMIT 1),0)
								WHERE ID_FACT=$IdItem";
							$dbEmpresa->exec($s);
							$salidas["upload"]='OK';
						/*}
						else $error=5035;
					}
					else $error=5034;*/
				}		
				
			}
			catch (Exception $e){
				$dbEmpresa->rollBack();
				$err_str=$e->getMessage();
			}
		}
		elseif($Delete){
			try{ 
				$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
				$dbEmpresa->beginTransaction();

				$s='DELETE FROM y_facturas WHERE ID_FACT=:IdFactura LIMIT 1';
				$req = $dbEmpresa->prepare($s); 
				$req->bindParam(':IdFactura', $IdFactura);
				$req->execute();
				$dbEmpresa->commit();
				// BORRA LA FOTO
				$_PARAMETROS=ParametrosAPI($dbEmpresa,$_sysvars_r,$_sysvars);
				$cnf=503;
				$IdItem=$IdFactura;
				require 		"../phplib/s3/aws.phar";
				$UploadDeleteArgs=array(
							'S3_BUCKET'=>$_PARAMETROS["S3_BUCKET"]
						,	'PROYECTO'=>$_PROYECTO
						,	'EMPRESA'=>$_EMPRESA
						,	'MODULE'=>$cnf
						,	'OBJECT'=>$IdItem
						,	'TP_FILE'=>'img');

				$AwsS3 = Aws::factory(array('key' => $_PARAMETROS['S3_KEY'],'secret' => $_PARAMETROS['S3_SECRET']));
				DeleteFiles($AwsS3,$dbEmpresa,$UploadDeleteArgs);
				$salidas["transaction"]='OK';
			}
			catch (Exception $e){
				$dbEmpresa->rollBack();
				$err_str=$e->getMessage();
			}
		}
	}
}
// Cotización (Nuevo/Editar/Eliminar)
elseif($tp==5040){	

	$_PREFIX=GetPrefixURL($dbEmpresa);
	if($IdCotiz==0){	
		$PagActual=$_REQUEST["page"]!=''?$_REQUEST["page"]:1;
		$IniDato=($PagActual-1)*$MaxItems;

		$_filtros=' WHERE y_cotizacion.ID_USUARIO_M=:_USUARIO OR y_cotizacion.ID_USUARIO_U=:_USUARIO ';

		/*INICIA*/		
		$s=$sqlCons[0][504].$_filtros; 
		$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";


		$reqC = $dbEmpresa->prepare($s); 
		$reqC->bindParam(':_USUARIO', $_USUARIO);			
		$reqC->execute(); 
		$Total = $reqC->fetchColumn();
		$IniDato=($PagActual-1)*$MaxItems;	
		
		/*TABLA*/
		$s=$sqlCons[0][504].$_filtros.$sqlOrder[0][504]." LIMIT $IniDato,$MaxItems";
		$reqItem = $dbEmpresa->prepare($s);	
		$reqItem->bindParam(':_USUARIO', $_USUARIO);
		$reqItem->execute();
		$salidas['cotizaciones']=Cotizaciones($reqItem);
		$salidas['pages']=array('act'=>$PagActual,'max'=>$Total,'res_per_page'=>$MaxItems);
	}
	else{
		$_filtros=' WHERE y_cotizacion.ID_COTIZ=:IdCotiz AND (y_cotizacion.ID_USUARIO_M=:_USUARIO OR y_cotizacion.ID_USUARIO_U=:_USUARIO) ';
		$s=$sqlCons[0][504].$_filtros." LIMIT 1";
		$reqItem = $dbEmpresa->prepare($s);	
		$reqItem->bindParam(':IdCotiz', $IdCotiz);
		$reqItem->bindParam(':_USUARIO', $_USUARIO);
		$reqItem->execute();
		$options=array('full'=>true);
		$salidas['cotizaciones']=Cotizaciones($reqItem,$options);
	}
}
// Usuario (Nuevo/Editar/Eliminar)
elseif($tp==5045){
	$NewUser=false;
	if($_REQUEST['accion']=='new'){
		if($IdUser==0){
			if(checkmail($_REQUEST["user"])){
				$email=$_REQUEST["user"];
				$names=explode('@', $email);
				$name=$names[0];
				$lastname='';
				$password=substr(sha1(time()),0,5);
				$Idioma=1;
				//////////////LINK//////////////
				$link=cambiar_url($name,2);		
				$link_busc=$link."%";		
				$s="SELECT adm_usuarios.ALIAS  AS LINK_PAGINA
					FROM adm_usuarios 
					WHERE (adm_usuarios.ALIAS LIKE :link OR adm_usuarios.ALIAS=:linkcompleto)"; 
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

				//USUARIO!		
				$s="INSERT INTO adm_usuarios
					(ALIAS
					,NOMBRE_U
					,APELLIDO_U
					,CORREO_U
					,PASSWORD_U
					,FECHA_U
					,ID_IDIOMA)
				VALUES(
					:links
					,:nombre
					,:apellido
					,:correo
					,:password
					,UTC_TIMESTAMP()
					,:idioma)";
				$Repreq = $dbEmpresa->prepare($s);
				$Repreq->bindParam(':links', $link); 
				$Repreq->bindParam(':nombre', $name);
				$Repreq->bindParam(':apellido', $lastname);
				$Repreq->bindParam(':correo', $email);
				$Repreq->bindParam(':password', sha1($password));
				$Repreq->bindParam(':idioma', $Idioma);
				$Repreq->execute();		

				$IdUser=$dbEmpresa->lastInsertId();

				$NewUser=true;
			}
			else
				$error=10002;
		}
		else{
			$s='SELECT adm_usuarios.NOMBRE_U
					,	adm_usuarios.APELLIDO_U
					,	adm_usuarios.CORREO_U
				FROM adm_usuarios
				WHERE adm_usuarios.ID_USUARIO=:_USUARIO LIMIT 1';
			$req = $dbEmpresa->prepare($s); 
			$req->bindParam(':_USUARIO',$IdUser);
			$req->execute();	
			$reg = $req->fetch();
			$email=$reg["CORREO_U"];
			$name=$reg["NOMBRE_U"];
			$lastname=$reg["APELLIDO_U"];
		}
	}


	//if($UserType==0)			
	if($_REQUEST['accion']!='edit'
		&&$_REQUEST['accion']!='delete'
		&&$_REQUEST['accion']!='new')	$error=10001;


	if($error==0){
		$Edit=$_REQUEST['accion']=='edit';
		$Delete=$_REQUEST['accion']=='delete';
		$New=$_REQUEST['accion']=='new';
		$Allow=(($Edit||$Delete)&&($IdUserOwnCotiz==$_USUARIO))||$New;
		if(!$Allow) $error=5041;
	}

	$nameC=JSON_PARSE($_REQUEST["item"]);
	$cant=JSON_PARSE($_REQUEST["cant"]);
	$price=JSON_PARSE($_REQUEST["price"]);

	if($error==0){
		if($New||$Edit){
			$id_print=$New?'NULL':$IdCotiz;
			try{ 
				$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
				$dbEmpresa->beginTransaction();

				// Calculo de Total

				$Total=0;				
				foreach ($nameC as $k=>$Item)
					$Total+=$cant[$k]*$price[$k];

				$s="INSERT INTO y_cotizacion
						(ID_COTIZ
					,	ID_USUARIO_M
					,	ID_USUARIO_U
					,	FECHAS_COTIZ
					,	VTOT_COTIZ)
					VALUES
						($id_print
					,	:_USUARIO
					,	:IdUser
					,	UTC_TIMESTAMP()
					,	:Total)
					ON DUPLICATE KEY UPDATE
						VTOT_COTIZ=:Total";
				$req = $dbEmpresa->prepare($s); 
				$req->bindParam(':_USUARIO',  $_USUARIO);
				$req->bindParam(':IdUser', $IdUser);
				$req->bindParam(':Total', $Total);				
				$req->execute();
				if($New) $IdCotiz=$dbEmpresa->lastInsertId();
				

				if($Edit){
					$s='DELETE FROM y_cotizacion_items WHERE ID_COTIZ=:IdCotiz';
					$req = $dbEmpresa->prepare($s); 
					$req->bindParam(':IdCotiz',  $IdCotiz);			
					$req->execute();
				}
				$s="INSERT INTO y_cotizacion_items
						(ID_COTIZ
					,	NAME_ITEM
					,	CANT_ITEM
					,	PREC_ITEM
					,	ORD_ITEM)
					VALUES
						(:IdCotiz
					,	:name
					,	:cant
					,	:price
					,	:ord)";
				$req = $dbEmpresa->prepare($s); 
				$req->bindValue(':IdCotiz',  $IdCotiz);
				foreach ($nameC as $k=>$Item){		
					$req->bindValue(':name', $nameC[$k]);
					$req->bindValue(':cant', $cant[$k]);		
					$req->bindValue(':price', $price[$k]);
					$req->bindValue(':ord', $k);					
					$req->execute();
				}

				$dbEmpresa->commit();
				$salidas["transaction"]='OK';
				$salidas["IdCotiz"]=$IdCotiz;

				try{
					/*******SEND EMAIL***********/	
					$_PARAMETROS=ParametrosAPI($dbEmpresa,$_sysvars_r,$_sysvars);					
					CuerpoMail($Email,$_PROYECTO,$_EMPRESA,$_CLIENTE,$_IDIOMA,$_TZ,$_GCLIENTE,$_USUARIO,$_GRUPO,$_PARAMETROS);		
					$to=array();
					$to[0]["mail"]=$email;
					$to[0]["name"]=$name.' '.$lastname;
					if($NewUser){					
						$Asunto=$Email[1][551]['title'];
						$html_cont=sprintf($Email[1][551]['body']
							,$name
							,$_sysvars['name']
							,$IdCotiz
							,$email
							,$password);
						$Alt=$Email[1][551]['alt'];
					}
					else{
						$Asunto=$Email[1][550]['title'];
						$html_cont=sprintf($Email[1][550]['body']
							,$name
							,$_sysvars['name']
							,$IdCotiz);
						$Alt=$Email[1][550]['alt'];

					}
					$rtamail=send_email_srv($_PARAMETROS,$Asunto,$html_cont,$to,array(),array(),true,$Alt);
					$salidas["rtamail"]=$rtamail;
				}
				catch (Exception $e){			
				}
				
			}
			catch (Exception $e){
				$dbEmpresa->rollBack();
				$err_str=$e->getMessage();
			}
		}
		elseif($Delete){
			try{ 
				$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
				$dbEmpresa->beginTransaction();

				$s='DELETE FROM y_cotizacion WHERE ID_COTIZ=:IdCotiz LIMIT 1';
				$req = $dbEmpresa->prepare($s); 
				$req->bindParam(':IdCotiz', $IdCotiz);
				$req->execute();
				$dbEmpresa->commit();
				
				$salidas["transaction"]='OK';
			}
			catch (Exception $e){
				$dbEmpresa->rollBack();
				$err_str=$e->getMessage();
			}
		}
	}
}
// Estado de Cuenta
elseif($tp==5050){		
	
	$PagActual=$_REQUEST["page"]!=''?$_REQUEST["page"]:1;
	$IniDato=($PagActual-1)*$MaxItems;

	$_filtros=' WHERE y_ecuenta.ID_USUARIO_M=:_USUARIO ';

	/*INICIA*/		
	$s=$sqlCons[0][505].$_filtros; 
	$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";

	$reqC = $dbEmpresa->prepare($s); 
	$reqC->bindParam(':_USUARIO', $_USUARIO);			
	$reqC->execute(); 
	$Total = $reqC->fetchColumn();
	$IniDato=($PagActual-1)*$MaxItems;	
	
	/*TABLA*/
	$s=$sqlCons[0][505].$_filtros.$sqlOrder[0][505]." LIMIT $IniDato,$MaxItems";
	$reqItem = $dbEmpresa->prepare($s);	
	$reqItem->bindParam(':_USUARIO', $_USUARIO);
	$reqItem->execute();
	$salidas['ecuenta']=ECuenta($reqItem);
	$salidas['pages']=array('act'=>$PagActual,'max'=>$Total,'res_per_page'=>$MaxItems);
}
// Noticias
elseif($tp==5060){		
	$_PREFIX=GetPrefixURL($dbEmpresa);
	$noticia=isset($_REQUEST["noticia"])?$_REQUEST["noticia"]:'';

	if($noticia==''){
		$PagActual=$_REQUEST["page"]!=''?$_REQUEST["page"]:1;
		$IniDato=($PagActual-1)*$MaxItems;

		$term=isset($_REQUEST["term"])?urldecode($_REQUEST["term"]):''; 
		$term_query='%'.$term.'%';

		$_filtros=' WHERE y_noti.HAB_NOTI=0 AND y_noti.ACTIV_NOTI=1 ';
		if($term!='')	$_filtros.=' AND (y_noti.TITLE_NOTI LIKE :Buscar)';

		/*INICIA*/		
		$s=$sqlCons[0][506].$_filtros; 
		$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";
		$reqC = $dbEmpresa->prepare($s); 	
		if($term!='') 	$reqC->bindParam(':Buscar', $term_query, PDO::PARAM_STR);
		$reqC->execute(); 
		$Total = $reqC->fetchColumn();
		$IniDato=($PagActual-1)*$MaxItems;	
		
		/*TABLA*/
		$s=$sqlCons[0][506].$_filtros.$sqlOrder[0][506]." LIMIT $IniDato,$MaxItems";
		$reqItem = $dbEmpresa->prepare($s);	
		if($term!='') 	$reqItem->bindParam(':Buscar', $term_query, PDO::PARAM_STR);			
		$reqItem->execute();

		$salidas['noticias']=Noticia($reqItem);
		$salidas['pages']=array('act'=>$PagActual,'max'=>$Total,'res_per_page'=>$MaxItems);
	}
	else{
		$_filtros=' WHERE (y_noti.ID_NOTI=:noticia OR y_noti.SLUG_NOTI=:noticia) AND y_noti.HAB_NOTI=0 AND y_noti.ACTIV_NOTI=1 ';
		$s=$sqlCons[0][506].$_filtros." LIMIT 1";
		$reqItem = $dbEmpresa->prepare($s);	
		$reqItem->bindParam(':noticia', $noticia);
		$reqItem->execute();

		$options=array('full'=>true);
		$salidas['noticias']=Noticia($reqItem,$options);
	}	
}
// Mensajes
elseif($tp==5080){		
	$_PREFIX=GetPrefixURL($dbEmpresa);
	$MaxItems=20;
	if($IdUser==0){
		$_filtros=' WHERE y_message_lst.ID_USUARIO=:_USUARIO ';
		$s=$sqlCons[1][508].$_filtros.$sqlOrder[1][508];
		$reqItem = $dbEmpresa->prepare($s);	
		$reqItem->bindParam(':_USUARIO', $_USUARIO);
		$reqItem->execute();

		$salidas['chat-list']=User($reqItem,$options);
	}
	else{
		$PagActual=$_REQUEST["page"]!=''?$_REQUEST["page"]:1;
		$IniDato=($PagActual-1)*$MaxItems;

		$_filtros=' WHERE 	(y_message.ID_USUARIO_E=:_USUARIO AND y_message.ID_USUARIO_R=:IdUser)
						OR 	(y_message.ID_USUARIO_E=:IdUser AND y_message.ID_USUARIO_R=:_USUARIO) ';

		/*INICIA*/		
		$s=$sqlCons[0][508].$_filtros; 
		$s="SELECT COUNT(*) FROM (".$s.") AS CONTEO";

		$reqC = $dbEmpresa->prepare($s); 
		$reqC->bindParam(':_USUARIO', $_USUARIO);	
		$reqC->bindParam(':IdUser', $IdUser);			
		$reqC->execute(); 
		$Total = $reqC->fetchColumn();
		$IniDato=($PagActual-1)*$MaxItems;	
		
		/*TABLA*/
		$OrderOthers=$sqlOrder[0][508];
		$s=$sqlCons[0][508].$_filtros.$OrderOthers." LIMIT $IniDato,$MaxItems";		
		$reqItem = $dbEmpresa->prepare($s);	
		$reqItem->bindParam(':_USUARIO', $_USUARIO);	
		$reqItem->bindParam(':IdUser', $IdUser);	
		$reqItem->execute();

		$salidas['chat-content']=Mensaje($reqItem,$options);
		$salidas['pages']=array('act'=>$PagActual,'max'=>$Total,'res_per_page'=>$MaxItems);
	}
}
// Nuevo Mensaje
elseif($tp==5085){
	if($IdUser==0)	$error=5081;	
	if($error==0){		
		try{ 
			$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
			$dbEmpresa->beginTransaction();

			// Calculo de Total

			$s="INSERT INTO y_message
				(	ID_USUARIO_E
				,	ID_USUARIO_R
				,	FECHAS_MSG
				,	MSG_TXT)
				VALUES
					(:_USUARIO
				,	:IdUser
				,	UTC_TIMESTAMP()
				,	:message)";
			$req = $dbEmpresa->prepare($s); 
			$req->bindParam(':_USUARIO', $_USUARIO);
			$req->bindParam(':IdUser', $IdUser);
			$req->bindParam(':message', $_REQUEST['message']);				
			$req->execute();
			$IdChat=$dbEmpresa->lastInsertId();

			$s="INSERT INTO y_message_lst
					(ID_USUARIO
				,	ID_USUARIO_CNT
				,	FECHAS_MSGLST
				,	LAST_ID)
				VALUES
					(:_USUARIO
				,	:IdUser
				,	UTC_TIMESTAMP()
				,	:IdChat)
				ON DUPLICATE KEY UPDATE
					FECHAS_MSGLST=UTC_TIMESTAMP()
				,	LAST_ID=:IdChat";
			$req = $dbEmpresa->prepare($s); 
			$req->bindParam(':_USUARIO', $_USUARIO);
			$req->bindParam(':IdUser', $IdUser);
			$req->bindParam(':IdChat', $IdChat);
			$req->execute();

			$s="INSERT INTO y_message_lst
					(ID_USUARIO
				,	ID_USUARIO_CNT
				,	FECHAS_MSGLST
				,	LAST_ID)
				VALUES
					(:IdUser
				,	:_USUARIO
				,	UTC_TIMESTAMP()
				,	:IdChat)
				ON DUPLICATE KEY UPDATE
					FECHAS_MSGLST=UTC_TIMESTAMP()
				,	LAST_ID=:IdChat";
			$req = $dbEmpresa->prepare($s); 
			$req->bindParam(':_USUARIO', $_USUARIO);
			$req->bindParam(':IdUser', $IdUser);
			$req->bindParam(':IdChat', $IdChat);
			$req->execute();


			$dbEmpresa->commit();
			$salidas["transaction"]='OK';
			$salidas["IdChat"]=$IdChat;




			$msg = array(	
					'type'			=> 'chat'
			,		'alert'       	=>	$_REQUEST['message']
			,		'mensaje'       =>	$_REQUEST['message']
			,	    'title'         => 	$_sysvars['name'].' '.$_sysvars['lastname']			
			,	    'vibrate'   	=> 1
			,	    'sound'     	=> 1
			,		'idUser' 		=>	$_USUARIO
			,		'idChat'		=>	$idChat
			,		'picUser'		=>	$_sysvars["display"]["prefix"].$_sysvars["display"]["t03"]
			);
			$salidas["msg"]=$msg;

			$Canal='U'.$IdUser;
			$_PARAMETROS=ParametrosAPI($dbEmpresa,$_sysvars_r,$_sysvars);
			include 	"../phplib/parse/autoload.php";
			$app_id=$_PARAMETROS['P_APPID'];
			$rest_key=$_PARAMETROS['P_RESTKEY'];
			$master_key=$_PARAMETROS['P_MASKEY'];	
			ParseClient::initialize( $app_id, $rest_key, $master_key );
			$query = ParseInstallation::query();
			ParsePush::send(array(
				"channels" => [ $Canal ]
			,	"data" => $msg
			));

			
			
		}
		catch (Exception $e){
			print_r($e);
			$dbEmpresa->rollBack();
			$err_str=$e->getMessage();
		}
		
	}
}

//Revisar si es instalador
if($tp==10100&&$verificar){
	$instalador=$_REQUEST['instalador']==1;
	if($instalador){
		$s='INSERT INTO z_instasol
				(ID_USUARIO
			,	FECHAS_SOL)
			VALUES
				(:_USUARIO
			,	UTC_TIMESTAMP())';
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':_USUARIO', $_USUARIO);
		$req->execute();

		$s='INSERT INTO x_usuario
				(ID_USUARIO
			,	TYPE_USUARIO)
			VALUES
				(:_USUARIO
			,	1)';
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':_USUARIO', $_USUARIO);
		$req->execute();

		/* ENVIA CORREO */
		$_PARAMETROS=ParametrosAPI($dbEmpresa,$_sysvars_r,$_sysvars);
		CuerpoMail($Email,$_PROYECTO,$_EMPRESA,$_CLIENTE,$_IDIOMA,$_TZ,$_GCLIENTE,$_USUARIO,$_GRUPO,$_PARAMETROS);


		$to=array();
		$to[0]["mail"]=$_PARAMETROS["M_TOMAIL"];
		$to[0]["name"]=$_PARAMETROS["M_TONAME"];
		$Asunto=$Email[1][552]['title'];
		$html_cont=sprintf($Email[1][552]['body']
			,$_PARAMETROS["M_TONAME"]
			,$_REQUEST['name']
			,$_REQUEST['lastname']
			,$_REQUEST['email']);
		$ALT=$Email[1][552]['alt'];
		$rtamail=send_email_srv($_PARAMETROS,$Asunto,$html_cont,$to,array(),array(),true,$ALT);
		$salidas["rta_mail_XX"]=$rtamail;
		/******************/

	}
	
}
if(($tp==10100||$tp==10101||$tp==10107||$tp==10108)&&$verificar){
	//Mis Datos
	$s=$sqlCons[0][500].' WHERE adm_usuarios.ID_USUARIO=:_USUARIO LIMIT 1';
	$req = $dbEmpresa->prepare($s); 
	$req->bindParam(':_USUARIO', $_USUARIO);
	$req->execute();
	if($reg = $req->fetch()){
		$salidas["_user"]['tel1']=is_null($reg['TEL1_USUARIO'])?'':$reg['TEL1_USUARIO'];
		$salidas["_user"]['type']=$reg['VERIF_USUARIO']==1?$reg['TYPE_USUARIO']:0;
		$salidas["_user"]['pretype']=$reg['TYPE_USUARIO'];
		$salidas["_user"]['cc']=$reg['CC_USUARIO'];
		$salidas["_user"]['city']=array('id'=>is_null($reg['ID_CIUDAD'])?0:$reg['ID_CIUDAD']
									,	'name'=>is_null($reg['NOMB_CIUDAD'])?'':$reg['NOMB_CIUDAD']);
		$salidas["_user"]['verif']=$reg['VERIF_USUARIO'];
		$salidas["_user"]['calif']=array(	'worst'	=>1
										,	'best'	=>5
										,	'value'	=>$reg['VCALIF_USUARIO']);
		$salidas["_user"]['bio']=$reg['BIO_USUARIO'];
		$salidas["_user"]['rs']=array();
		$salidas["_user"]['espec']=array();
	}
	//Mis RS
	$s=$sqlCons[2][500].'  '.$sqlOrder[2][500];
	$req = $dbEmpresa->prepare($s); 
	$req->bindParam(':id_usuario', $_USUARIO);
	$req->execute();
	while($reg = $req->fetch()){
		$Icon=RS_Class($reg["ID_URLS"]);
		$url=$reg['URLS'];
		if($reg["TIPO_URLS"]==11)			$url=$reg['URLS'];
		$salidas["_user"]['rs'][]=array(	'icon'	=>	is_null($Icon['cont'])?'':$Icon['cont']
										,	'class'	=>	is_null($Icon['class'])?'':$Icon['class']
										,	'icon_url'	=> 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c2/F_icon.svg/2000px-F_icon.svg.png'
										,	'url'	=>	$url);
	}
	//Mi Espec
	$s=$sqlCons[5][500].' '.$sqlOrder[5][500];
	$req = $dbEmpresa->prepare($s); 
	$req->bindParam(':id_usuario', $_USUARIO);
	$req->execute();
	while($reg = $req->fetch()){
		$salidas["_user"]['espec'][]=array('id'=>$reg['ID_ESPEC'],'name'=>$reg['NAME_ESPEC'],'select'=>$reg['SELECTED']!=0,'selectstr'=>$reg['SELECTED']!=0?'si':'no');
	}

	//Editar Perfil
	if($_REQUEST['_edit']==1){
		$s=$sqlCons[10][45].' '.$sqlOrder[10][45];
		$req = $dbEmpresa->prepare($s); 
		$req->execute();
		$salidas["_edit"]['ciudades']=PrintInArray($req);
	}


}
//Editar info de usuario
if($tp==20002){
	try{ 
		$dbEmpresa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				
		$dbEmpresa->beginTransaction();
		$s='INSERT INTO x_usuario
				(ID_USUARIO
			,	TEL1_USUARIO
			,	BIO_USUARIO
			,	ID_CIUDAD
			,	CC_USUARIO)
			VALUES
				(:_USUARIO
			,	:tel1
			,	:bio
			,	:ciudad
			,	:cc)
			ON DUPLICATE KEY UPDATE
				TEL1_USUARIO=:tel1
			,	BIO_USUARIO=:bio
			,	ID_CIUDAD=:ciudad
			,	CC_USUARIO=:cc';
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':_USUARIO', $_USUARIO);
		$req->bindParam(':tel1', $_REQUEST['tel1']);
		$req->bindParam(':bio', $_REQUEST['bio']);
		$req->bindParam(':ciudad', $_REQUEST['ciudad']);
		$req->bindParam(':cc', $_REQUEST['cc']);
		$req->execute();

		$redes=array();
		if($_REQUEST['fb']!='')				$redes[4]=$_REQUEST['fb'];
		if($_REQUEST['tw']!=''){
			$tw=$_REQUEST['tw'];
			if(substr($tw, 0,1)=='@')
				$redes[5]=$tw;
			else
				$redes[5]='@'.$tw;
		}
		if($_REQUEST['instagram']!='')		$redes[6]=$_REQUEST['instagram'];
		if($_REQUEST['gplus']!='')			$redes[16]=$_REQUEST['gplus'];
		if($_REQUEST['web']!='')			$redes[17]=$_REQUEST['web'];
		if($_REQUEST['linkedin']!='')		$redes[9]=$_REQUEST['linkedin'];



		$s='DELETE FROM x_usuario_rs WHERE ID_USUARIO=:_USUARIO';
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':_USUARIO', $_USUARIO);
		$req->execute();

		$s='INSERT INTO x_usuario_rs
				(ID_USUARIO
			,	ID_URLS
			,	URLS)
			VALUES
				(:_USUARIO
			,	:idUrl
			,	:url)';
		$req = $dbEmpresa->prepare($s); 
		$req->bindValue(':_USUARIO', $_USUARIO);
		foreach ($redes as $idUrl => $url) {		
			$req->bindValue(':idUrl', $idUrl);
			$req->bindValue(':url', $url);
			$req->execute();
		}

		$espec=JSON_PARSE($_REQUEST['espec']);
		$s='DELETE FROM x_usuario_espec WHERE ID_USUARIO=:_USUARIO';
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':_USUARIO', $_USUARIO);
		$req->execute();
		if(count($espec)){
			$s='INSERT INTO x_usuario_espec
					(ID_USUARIO
				,	ID_ESPEC)
				VALUES
					(:_USUARIO
				,	:espec)';
			$req = $dbEmpresa->prepare($s); 
			$req->bindParam(':_USUARIO', $_USUARIO);
			foreach ($espec as $especunid) {		
				$req->bindValue(':espec', $especunid);
				$req->execute();
			}
		}
		$dbEmpresa->commit();
		$salidas["transaction"]='OK';			
	}
	catch (Exception $e){
		$dbEmpresa->rollBack();
		$err_str=$e->getMessage();
	}	
}
if($error!=0) $salidas['get_err']=STR_ERR($error);
function STR_ERR($error){
	$tp=$GLOBALS['tp'];
	if($tp==10100){
		$err_str[1]='Los campos de |email| son obligatorios';
		$err_str[10]='El email debe tener un formato así nombre@dominio.com';
		$err_str[20]='El email esta actualmente en uso';
	}
	elseif($tp==10101){
		$err_str[7]='La verificación de usuario falló, asegurese de enviar |_ualias|, |_upassw: usando SHA1| y |_session|';
		$err_str[9]='El email debe tener un formato así nombre@dominio.com';
	}
	elseif($tp==10103){
		$err_str[13]='La contraseña anterior no es correcta';
		$err_str[15]='La nueva contraseña debe tener al menos 4 cuaracteres';
	}
	elseif($tp==20002||$tp==10106){
		$err_str[20]='El email esta actualmente en uso';
		$err_str[10]='El email debe tener un formato así nombre@dominio.com';
		$err_str[1]='Los campos de |email| |name| son obligatorios';
	}
	elseif($tp==10104){
		$err_str[514]='El email no existe en la plataforma';
		$err_str[10]='El email debe tener un formato así nombre@dominio.com';
	}


	$err_str[10002]='Tipo de Usuario no Válido. El usuario debe ser un usuario instalador';
	$err_str[10001]='Debe definir una acción antes de continuar usando la variable |accion|';

	$err_str[5011]='El usuario no es el propietario de el proyecto. Verifique la variable |proyecto|';
	$err_str[5012]='Variables |nomb| vacia';
	$err_str[5013]='Variables |title| vacia';
	$err_str[5014]='La imagen debe tener menos de  '.$fmax;
	$err_str[5015]='La imagen debe tener alguna de las siguientes extensiones:  '.implode(', ',$_files_clase[0]);
	$err_str[5016]='Proyecto no válido. Verifique la variable |proyecto|';
	$err_str[5017]='Variables |comment| vacia';
	$err_str[5018]='El usuario no puede borrar este comentario';
	

	$err_str[5021]='El usuario no es el propietario de la oferta. Verifique la variable |oferta|';
	$err_str[5022]='Variables |title string|, |fechai %d/%m/%aaaa|, |fechaf %d/%m/%aaaa|, |espec numeric| o |ciudad numeric| vacias';

	$err_str[5031]='El usuario no es el propietario de la factura. Verifique la variable |factura|';
	$err_str[5034]='La imagen debe tener menos de  '.$fmax;
	$err_str[5035]='La imagen debe tener alguna de las siguientes extensiones:  '.implode(', ',$_files_clase[0]);

	$err_str[5041]='El usuario no es el propietario de la cotización. Verifique la variable |cotizacion|';
	$err_str[5043]='Se debe seleccionar un usuario válido. Verifique la variable |user|';
	
	$err_str[5081]='La variable de usuario está vacia |user|';


	return $err_str[$error];
}
?>
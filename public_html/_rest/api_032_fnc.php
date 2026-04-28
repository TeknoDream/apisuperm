<?php
/*************************/
/******** MAESTROS *******/
/*************************/
function Maestros($req,$options){
	$_PREFIX=$GLOBALS['_PREFIX'];
	$ToImg=$GLOBALS['ArrayImg'];
	$_USUARIO=$GLOBALS['_USUARIO'];

	$dbEmpresa=$GLOBALS['dbEmpresa'];
	$sqlCons=$GLOBALS['sqlCons'];
	$sqlOrder=$GLOBALS['sqlOrder'];

	$ToImg["MODULO"]=36;
	$item=array();
	while($reg = $req->fetch()){
		$item_ids[]=$reg["ID_USUARIO"];
		$idItem=$reg["ID_USUARIO"];		
		$idLastItem=$reg["ID_USUARIO"];	

		$ToImg["OBJETO"]=$idItem;
		$ToImg["EXT"]=$reg["F_EXT"];
		$Picture=ImgBlanc($reg["M_IMG"],$ToImg);
		$Picture["hash"]=$reg["F_HASH"];
		$Picture["prefix"]=$_PREFIX;	


		$item[$idItem]=array(
					'id'		=>	$reg['ID_USUARIO']	
				,	'name' 		=>	$reg['NOMBRE_U']
				,	'lastname' 	=>	$reg['APELLIDO_U']
				,	'alias' 	=>	$reg['ALIAS']
				,	'email' 	=>	$reg['CORREO_U']
				,	'ciudad' 	=>	$reg['NOMB_CIUDAD']
				,	'bio'		=>	is_null($reg['BIO_USUARIO'])?'':$reg['BIO_USUARIO']
				,	'tel1' 		=>	is_null($reg['TEL1_USUARIO'])?'':$reg['TEL1_USUARIO']
				,	'tel2' 		=>	is_null($reg['TEL2_USUARIO'])?'':$reg['TEL2_USUARIO']
				,	'calif' 	=>	array(	'worst'	=>1
										,	'best'	=>5
										,	'calif'	=>$reg['VCALIF_USUARIO'])
				,	'display'	=>	$Picture
				,	'espec'		=>	array()
				,	'me'		=>	$reg['ID_USUARIO']==$_USUARIO
				,	'proyectos'=>	$reg['PROYS_USAURIO']
				,	'proyectos_det'=>array()
			);
		if($options['full']){
			$item[$idItem]['social']=array();
		}
	}

	$REST_COUNT=count($item_ids);
	$REST_RESULTS=$REST_COUNT>0;
	if($REST_RESULTS)
		$id_items=implode(",",$item_ids);

	if($REST_RESULTS){
		// Especialidad
		$s=$sqlCons[4][500]." WHERE z_espec.HAB_ESPEC=0 AND x_usuario_espec.ID_USUARIO IN ($id_items) ".$sqlOrder[4][500];
		$req = $dbEmpresa->prepare($s);	
		$req->execute();	
		while($regEsp = $req->fetch()){
			$idItem=$regEsp["ID_USUARIO"];	
			$item[$idItem]['espec'][]=$regEsp['NAME_ESPEC'];
		}

		if($options['full']){

			$_filtros=" WHERE y_proyectos.ID_USUARIO=$idLastItem AND adm_usuarios.HAB_U=0 AND y_proyectos.HAB_PROY=0 AND x_usuario.VERIF_USUARIO=1 ";
			$s=$sqlCons[0][501].$_filtros.$sqlOrder[0][501];
			$reqItem = $dbEmpresa->prepare($s);	
			$reqItem->execute();
			$item[$idLastItem]['proyectos_det']=Proyectos($reqItem,$options);

			// SOCIAL
			$s=$sqlCons[3][500]." WHERE fac_turls.HAB_URLS=0 AND x_usuario_rs.ID_USUARIO IN ($id_items) ".$sqlOrder[3][500];
			$req = $dbEmpresa->prepare($s);	
			$req->execute();	
			while($regSoc = $req->fetch()){
				$idItem=$regSoc["ID_USUARIO"];	
				$Icon=RS_Class($regSoc["ID_URLS"]);
				$url=$regSoc['URLS'];
				if($regSoc["TIPO_URLS"]==11)	$url='twitter.com/'.substr($regSoc['URLS'],1);
				$item[$idItem]["social"][]=array(	'icon'	=>	$Icon['cont']
												,	'class'	=>	$Icon['class']
												,	'url'	=>	$url
												,	'icon_url'	=> 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c2/F_icon.svg/2000px-F_icon.svg.png');
			}

			
		}
	}
	return OrderPrint($item);
}
/*************************/
/******* Proyectos *******/
/*************************/
function Proyectos($req,$options){
	$_PREFIX=$GLOBALS['_PREFIX'];

	$IdPYFoto=$GLOBALS['IdPYFoto'];

	$dbEmpresa=$GLOBALS['dbEmpresa'];
	$sqlCons=$GLOBALS['sqlCons'];
	$sqlOrder=$GLOBALS['sqlOrder'];

	$ToImg=$GLOBALS['ArrayImg'];	
	$ToImgUSER=$GLOBALS['ArrayImg'];	
	$_USUARIO=$GLOBALS['_USUARIO'];
	$verificar=$GLOBALS['verificar'];
	$UserType=$GLOBALS['UserType'];

	$ToImg["MODULO"]=501;
	$ToImgUSER["MODULO"]=36;
	$item=array();
	while($reg = $req->fetch()){
		$item_ids[]=$reg["ID_PROY"];
		$idItem=$reg["ID_PROY"];

		$ToImgUSER["OBJETO"]=$reg['ID_USUARIO'];
		$ToImgUSER["EXT"]=$reg["F_EXT"];
		$Picture=ImgBlanc($reg["M_IMG"],$ToImgUSER);
		$Picture["hash"]=$reg["F_HASH"];
		$Picture["prefix"]=$_PREFIX;


		$Owner=$reg['ID_USUARIO']==$_USUARIO;
		$item[$idItem]=array(	
					'id' 		=>	$reg['ID_PROY']
				,	'name' 		=>	$reg['NOMB_PROY']
				,	'desc' 		=>	$reg['DESC_PROY']
				,	'date' 		=>	$reg['FECHAS_PROY']
				,	'calif' 	=>	array(	'worst'	=>1
										,	'best'	=>5
										,	'value'	=>$reg['VCALIF_PROY'])
				,	'display'	=>array()
				,	'owner'		=>$Owner
				,	'user'		=>array('id'=>$reg['ID_USUARIO']
									,	'slug'=>$reg['ALIAS']
									,	'name'=>$reg['NOMBRE_U']
									,	'lastname'=>$reg['APELLIDO_U']
									,	'display'=>$Picture)
				,	'miCalif'	=>0
				,	'count_comments'=>$reg['COMMENTS_PROY']
				,	'comments'	=>array()
			);		
		if($options['full'])
			$item[$idItem]['pictures']=array();
	}

	$REST_COUNT=count($item_ids);
	$REST_RESULTS=$REST_COUNT>0;
	if($REST_RESULTS)
		$id_items=implode(",",$item_ids);

	if($REST_RESULTS){
		if($IdPYFoto!=0)
			$s=$sqlCons[1][501]." WHERE y_proyectos_fotos.ID_FOTO=$IdPYFoto ";
		elseif($options['full'])
			$s=$sqlCons[1][501]." WHERE y_proyectos_fotos.ID_PROY IN ($id_items) ".$sqlOrder[1][501];
		else
			$s=$sqlCons[1][501]." WHERE y_proyectos_fotos.ID_PROY IN ($id_items) ";
		$req = $dbEmpresa->prepare($s);	
		$req->execute();

		
		while($regPic = $req->fetch()){
			$idItem=$regPic["ID_PROY"];	
			$ToImg["OBJETO"]=$regPic['ID_FOTO'];
			$ToImg["EXT"]=$regPic["F_EXT"];
			$Picture=ImgBlanc($regPic["M_IMG"],$ToImg);
			$Picture["hash"]=$regPic["F_HASH"];
			$Picture["prefix"]=$_PREFIX;
			$FlagUnique=isset($item[$idItem]["display"]['id']);
			if($options['full']){
				$item[$idItem]["pictures"][]=array(
												'id'	=>	$regPic['ID_FOTO']
										,		'title'	=>	$regPic['TITLE_FOTO']
										,		'main'		=>	$regPic['MAIN_FOTO']==1
										,		'ord'		=>	$regPic['ORD_FOTO']
										,		'picture'	=>	$Picture);
			}
			if($regPic['MAIN_FOTO']==1||$IdPYFoto||!$FlagUnique)
				$item[$idItem]["display"]=$Picture;
		}	

		if($options['full']){
			$s=$sqlCons[4][501]." WHERE y_proyectos_comment.ID_PROY IN ($id_items) ".$sqlOrder[4][501];
			$req = $dbEmpresa->prepare($s);	
			$req->execute();
			while($regComment = $req->fetch()){

				$Picture=ImgBlanc($regComment["M_IMG"],$ToImgUSER);
				$Picture["hash"]=$regComment["F_HASH"];
				$Picture["prefix"]=$_PREFIX;

				$idItem=$regComment["ID_PROY"];
				$item[$idItem]["comments"][]=array('id'		=>$regComment["ID_COMMENT"]
												,'edit'		=>$regComment["ID_USUARIO"]==$_USUARIO
												,'edits'	=>$regComment["ID_USUARIO"]==$_USUARIO?"1":"0"
												,'comment'	=>$regComment["TEXT_COMMENT"]
												,'user'		=>$regComment["NOMBRE_U"].' '.$regComment["APELLIDO_U"]
												,'picture'	=>$Picture
												,'date'		=>$regComment["FECHAS_COMMENT"]);
			}
		}

		//Mi Calificacion
		if($verificar){
			$s=$sqlCons[3][501]." WHERE y_proyectos_calif.ID_USUARIO=:_USUARIO AND y_proyectos_calif.ID_PROY IN ($id_items) ";
			$req = $dbEmpresa->prepare($s);	
			$req->bindParam(':_USUARIO', $_USUARIO);
			$req->execute();
			while($regCal = $req->fetch()){
				$idItem=$regCal["ID_PROY"];
				$item[$idItem]["miCalif"]=$regCal["VAL_CALIF"];
			}
		}		

	}
	return OrderPrint($item);
}
/*************************/
/******* Ofertas *********/
/*************************/
function Ofertas($req,$options){
	$_USUARIO=$GLOBALS['_USUARIO'];
	$verificar=$GLOBALS['verificar'];
	$UserType=$GLOBALS['UserType'];


	$_PREFIX=$GLOBALS['_PREFIX'];
	$ToImg=$GLOBALS['ArrayImg'];
	$ToImg["MODULO"]=36;

	$item=array();
	while($reg = $req->fetch()){
		$item_ids[]=$reg["ID_OFERTA"];
		$idItem=$reg["ID_OFERTA"];

		$ToImg["OBJETO"]=$reg['ID_USUARIO'];
		$ToImg["EXT"]=$reg["F_EXT"];
		$Picture=ImgBlanc($reg["M_IMG"],$ToImg);
		$Picture["hash"]=$reg["F_HASH"];
		$Picture["prefix"]=$_PREFIX;

		$Owner=$reg['ID_USUARIO']==$_USUARIO;
		$item[]=array(	
					'id' 		=>	$reg['ID_OFERTA']
				,	'title' 	=>	$reg['TITLE_OFERTA']
				,	'comment' 	=>	$reg['COMENT_OFERT'].(($UserType==1||!$Owner)?(' - '.$reg['CONTACT_OFERTA']):'')

				,	'contact' 	=>	($UserType==1||$Owner)?$reg['CONTACT_OFERTA']:''
				,	'date_create' =>$reg['FECHAS_OFERTA']
				,	'datei' 	=>	$reg['FECHAI_OFERTA']
				,	'datef' 	=>	$reg['FECHAF_OFERTA']

				,	'datei_f' 	=>	$reg['FECHAI_OFERTAF']
				,	'datef_f' 	=>	$reg['FECHAF_OFERTAF']


				,	'espec' 	=>	array(	'id'	=>$reg['ID_ESPEC']
										,	'name'	=>$reg['NAME_ESPEC'])
				,	'city' 		=>	array(	'id'	=>$reg['ID_CIUDAD']
										,	'name'	=>$reg['NOMB_CIUDAD'])

				,	'user' 		=>	array(	'id'		=>$reg['ID_USUARIO']
										,	'alias'		=>$reg['ALIAS_U_OP']
										,	'name'		=>$reg['NOMBRE_U_OP']
										,	'lastname'	=>$reg['APELLIDO_U_OP']
										,	'display'	=>$Picture)
				,	'owner'		=>$Owner
			);		
	}
	return $item;
}
/*************************/
/******* Facturas ********/
/*************************/
function Facturas($req,$options){
	$_PREFIX=$GLOBALS['_PREFIX'];

	$ToImg=$GLOBALS['ArrayImg'];	
	$_USUARIO=$GLOBALS['_USUARIO'];
	$verificar=$GLOBALS['verificar'];
	$UserType=$GLOBALS['UserType'];

	$ToImg["MODULO"]=503;
	$item=array();
	while($reg = $req->fetch()){
		$item_ids[]=$reg["ID_FACT"];
		$idItem=$reg["ID_FACT"];

		$ToImg["OBJETO"]=$idItem;
		$ToImg["EXT"]=$reg["F_EXT"];
		$Picture=ImgBlanc($reg["M_IMG"],$ToImg);
		$Picture["hash"]=$reg["F_HASH"];
		$Picture["prefix"]=$_PREFIX;

		$item[]=array(	
					'id' 			=>	$reg['ID_FACT']
				,	'points' 		=>	$reg['VPOINT_FACT']==''?0:$reg['VPOINT_FACT']
				,	'obs' 			=>	$reg['OBS_FACT']	
				,	'date_user' 	=>	$reg['FECHAF_FACT']			
				,	'date_create' 	=>	$reg['FECHAS_FACT']	
				,	'validate'		=>	$reg['VALIDAT_FACT']			
				,	'picture'	=>	$Picture
			);		
	}	
	return $item;
}

/*************************/
/***** Cotizaciones ******/
/*************************/
function Cotizaciones($req,$options){
	$_PREFIX=$GLOBALS['_PREFIX'];

	$dbEmpresa=$GLOBALS['dbEmpresa'];
	$sqlCons=$GLOBALS['sqlCons'];
	$sqlOrder=$GLOBALS['sqlOrder'];

	$ToImg_USER_M=$GLOBALS['ArrayImg'];	
	$ToImg_USER_U=$GLOBALS['ArrayImg'];



	$_USUARIO=$GLOBALS['_USUARIO'];
	$verificar=$GLOBALS['verificar'];
	$UserType=$GLOBALS['UserType'];

	$ToImg_USER_M["MODULO"]=36;
	$ToImg_USER_U["MODULO"]=36;

	$item=array();
	while($reg = $req->fetch()){
		$item_ids[]=$reg["ID_COTIZ"];
		$idItem=$reg["ID_COTIZ"];

		$ToImg_USER_M["OBJETO"]=$reg['ID_USUARIO_M'];
		$ToImg_USER_M["EXT"]=$reg["F_EXT_M"];
		$Picture_M=ImgBlanc($reg["M_IMG_M"],$ToImg_USER_M);
		$Picture_M["hash"]=$reg["F_HASH_M"];
		$Picture_M["prefix"]=$_PREFIX;

		$ToImg_USER_U["OBJETO"]=$reg['ID_USUARIO_U'];
		$ToImg_USER_U["EXT"]=$reg["F_EXT_U"];
		$Picture_U=ImgBlanc($reg["M_IMG_U"],$ToImg_USER_U);
		$Picture_U["hash"]=$reg["F_HASH_U"];
		$Picture_U["prefix"]=$_PREFIX;

		$Maestro=$reg['ID_USUARIO_M']==$_USUARIO;
		$Usuario=$reg['ID_USUARIO_U']==$_USUARIO;

		$item[$idItem]=array(	
					'id'		=>	$reg['ID_COTIZ']
				,	'date' 		=>	$reg['FECHAS_COTIZ']
				,	'val' 		=>	$reg['VTOT_COTIZ']				
				,	'Maestro'	=>$Maestro
				,	'Usuario'	=>$Usuario
				,	'maestro'	=>$reg['NOMBRE_M'].' '.$reg['APELLIDO_M']
				,	'usuario'	=>$reg['NOMBRE_U'].' '.$reg['APELLIDO_U']
				,	'correo'	=>$reg['CORREO_U']
				,	'display_maestro'=>$Picture_M
				,	'display_usuario'=>$Picture_U
			);		
	}

	$REST_COUNT=count($item_ids);
	$REST_RESULTS=$REST_COUNT>0;
	if($REST_RESULTS)
		$id_items=implode(",",$item_ids);

	if($REST_RESULTS&&$options['full']){
		$s=$sqlCons[1][504]." WHERE y_cotizacion_items.ID_COTIZ IN ($id_items) ".$sqlOrder[1][504];	
		$req = $dbEmpresa->prepare($s);	
		$req->execute();
		while($regCotiz = $req->fetch()){
			$idItem=$regCotiz["ID_COTIZ"];				
			$item[$idItem]["item"][]=array(	'name'=>$regCotiz["NAME_ITEM"]
										,	'cant'=>$regCotiz["CANT_ITEM"]
										,	'price'=>$regCotiz["PREC_ITEM"]
										,	'total'=>$regCotiz["CANT_ITEM"]*$regCotiz["PREC_ITEM"]
										,	'order'=>$regCotiz["ORD_ITEM"]);
		}			
	}
	return OrderPrint($item);
}

/*************************/
/******* E-Cuenta ********/
/*************************/
function ECuenta($req,$options){
	$_USUARIO=$GLOBALS['_USUARIO'];
	$verificar=$GLOBALS['verificar'];
	$UserType=$GLOBALS['UserType'];
	$item=array();
	while($reg = $req->fetch()){
		$item_ids[]=$reg["ID_ECUENTA"];
		$idItem=$reg["ID_ECUENTA"];
		$item[]=array(	
					'id'		=>	$reg['ID_ECUENTA']
				,	'ini' 		=>	$reg['INIP_ECUENTA']
				,	'out'		=>	$reg['OUTP_ECUENTA']
				,	'date'		=>	$reg['FECHAS_ECUENTA']
				,	'comment' 	=>$reg['COMMEN_ECUENTA']
				,	'user' 		=>	array(	'id'		=>$reg['ID_USUARIO_A']
										,	'name'		=>$reg['NOMBRE_A']
										,	'lastname'	=>$reg['APELLIDO_A'])
			);		
	}
	return $item;
}
/*************************/
/******* Noticias ********/
/*************************/
function Noticia($req,$options){
	$_PREFIX=$GLOBALS['_PREFIX'];
	$ToImg=$GLOBALS['ArrayImg'];	
	$_USUARIO=$GLOBALS['_USUARIO'];
	$verificar=$GLOBALS['verificar'];
	$ToImg["MODULO"]=506;
	$item=array();
	while($reg = $req->fetch()){
		$item_ids[]=$reg["ID_NOTI"];
		$idItem=$reg["ID_NOTI"];

		$ToImg["OBJETO"]=$reg['ID_NOTI'];
		$ToImg["EXT"]=$reg["F_EXT"];
		$Picture=ImgBlanc($reg["M_IMG"],$ToImg);
		$Picture["hash"]=$reg["F_HASH"];
		$Picture["prefix"]=$_PREFIX;

		$sitem=array(	
					'id'		=>	$reg['ID_NOTI']
				,	'title' 	=>	$reg['TITLE_NOTI']
				,	'slug'		=>	$reg['SLUG_NOTI']
				,	'date'		=>	$reg['FECHAS_NOTI']
				,	'seo'		=>array(	'meta-title'		=>$reg['MTITLE_NOTI']
										,	'meta-description'	=>$reg['MDESC_NOTI'])
				,	'writtenby' =>	array(	'id'		=>$reg['ID_USUARIO']
										,	'name'		=>$reg['NOMBRE_U']
										,	'lastname'	=>$reg['APELLIDO_U'])
				,	'picture'	=>$Picture
			);		
		if($options['full'])	$sitem['content']=$reg['CONT_NOTI'];

		$item[]=$sitem;
	}
	return $item;
}
/*************************/
/******* Publicidad ******/
/*************************/
function Publicidad($req,$options){
	$_PREFIX=$GLOBALS['_PREFIX'];
	$ToImg=$GLOBALS['ArrayImg'];
	$ToImg["MODULO"]=507;
	$item=array();
	while($reg = $req->fetch()){
		$ToImg["OBJETO"]=$reg['ID_PUBL'];
		$ToImg["EXT"]=$reg["F_EXT"];
		$Picture=ImgBlanc($reg["M_IMG"],$ToImg);
		$Picture["hash"]=$reg["F_HASH"];
		$Picture["prefix"]=$_PREFIX;
		$item[]=array(	
					'title' 	=>	$reg['TITLE_PUBL']
				,	'slug'		=>	$reg['TYP_PUBL']				
				,	'picture'	=>$Picture
			);
	}
	return $item;
}
/*************************/
/**** Mensaje LISTA ******/
/*************************/
function User($req,$options){
	$_PREFIX=$GLOBALS['_PREFIX'];
	$ToImg_USER=$GLOBALS['ArrayImg'];	
	$_USUARIO=$GLOBALS['_USUARIO'];
	$ToImg_USER["MODULO"]=36;
	$item=array();
	while($reg = $req->fetch()){

		$ToImg_USER["OBJETO"]=$reg['ID_USUARIO'];
		$ToImg_USER["EXT"]=$reg["F_EXT"];
		$Picture=ImgBlanc($reg["M_IMG"],$ToImg_USER);
		$Picture["hash"]=$reg["F_HASH"];
		$Picture["prefix"]=$_PREFIX;

		
		$item[]=array(	'id'		=>$reg['ID_USUARIO']
					,	'name'		=>$reg['NOMBRE_U']
					,	'lastname'	=>$reg['APELLIDO_U']
					,	'display'	=>$Picture
					,	'last'		=>$reg['FECHAS_MSGLST']
			);		
	}
	
	return $item;
}
/*************************/
/******** Mensaje ********/
/*************************/
function Mensaje($req,$options){
	$_PREFIX=$GLOBALS['_PREFIX'];

	$ToImg_USER_U1=$GLOBALS['ArrayImg'];	
	$ToImg_USER_U2=$GLOBALS['ArrayImg'];

	$_USUARIO=$GLOBALS['_USUARIO'];
	$verificar=$GLOBALS['verificar'];
	$UserType=$GLOBALS['UserType'];

	$ToImg_USER_U1["MODULO"]=36;
	$ToImg_USER_U2["MODULO"]=36;

	$item=array();
	while($reg = $req->fetch()){


		$YoEnvio=$reg['ID_USUARIO_E']==$_USUARIO;
		$YoRecibo=$reg['ID_USUARIO_U']==$_USUARIO;

		$ToImg_USER_U1["OBJETO"]=$reg['ID_USUARIO_E'];
		$ToImg_USER_U1["EXT"]=$reg["F_EXT_E"];
		$Picture_U1=ImgBlanc($reg["M_IMG_E"],$ToImg_USER_U1);
		$Picture_U1["hash"]=$reg["F_HASH_E"];
		$Picture_U1["prefix"]=$_PREFIX;

		$ToImg_USER_U2["OBJETO"]=$reg['ID_USUARIO_U'];
		$ToImg_USER_U2["EXT"]=$reg["F_EXT_U"];
		$Picture_U2=ImgBlanc($reg["M_IMG_U"],$ToImg_USER_U2);
		$Picture_U2["hash"]=$reg["F_HASH_U"];
		$Picture_U2["prefix"]=$_PREFIX;

		
		$item[]=array(	
					'id'		=>	$reg['ID_MSG']
				,	'user1' 	=>	array(	'id'		=>$reg['ID_USUARIO_E']
										,	'name'		=>$reg['NOMBRE_E']
										,	'lastname'	=>$reg['APELLIDO_E'])
				,	'user2' 	=>	array(	'id'		=>$reg['ID_USUARIO_U']
										,	'name'		=>$reg['NOMBRE_U']
										,	'lastname'	=>$reg['APELLIDO_U'])
				,	'date'		=>	$reg['FECHAS_MSG']

				,	'display_envia'	=>$Picture_U1
				,	'display_recibe'=>$Picture_U2
				,	'YoEnvio'		=>$YoEnvio
				,	'mensaje'		=>$reg['MSG_TXT']
			);		
	}
	
	return $item;
}
?>
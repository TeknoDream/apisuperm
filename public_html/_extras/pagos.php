<?php
$result=$_POST; //->POST

if(($squery=="answer")||($squery=="confirm")){
	if($verificar){
		$s="INSERT INTO log_interpagos 
					(FECHA,PAGE,IP_USR,IdReference,Reference,Currency,BaseAmount,TaxAmount,TotalAmount,ShopperName,ShopperEmail,LenguajeInterface,
					PayMethod,RecurringBill,RecurringBillTimes,ExtraData1,ExtraData2,ExtraData3,Test,TransactionId,TransactionCode,TransactionMessage,
					TokenTransactionCode,PageAnswer,PageConfirm)
				VALUES 
			(UTC_TIMESTAMP(),:PAGE,:IP_USR,:IDReference,:Reference,:Currency,:BaseAmount,:TaxAmount,:TotalAmount,:ShopperName,
			:ShopperEmail,:LanguajeInterface,:PayMethod,:RecurringBill,:RecurringBillTimes,:ExtraData1,:ExtraData2,:ExtraData3,
			:Test,:TransactionId,:TransactionCode,:TransactionMessage,:TokenTransactionCode,:PageAnswer,:PageConfirm)";
		$req = $dbEmpresa->prepare($s);
		$req->bindParam(':PAGE', $squery);
		$req->bindParam(':IP_USR', $_SERVER["REMOTE_ADDR"]);
		$req->bindParam(':IDReference', $result["IDReference"]);
		$req->bindParam(':Reference', $result["Reference"]);
		$req->bindParam(':Currency', $result["Currency"]);
		$req->bindParam(':BaseAmount', $result["BaseAmount"]);
		$req->bindParam(':TaxAmount', $result["TaxAmount"]);
		$req->bindParam(':TotalAmount', $result["TotalAmount"]);
		$req->bindParam(':ShopperName', $result["ShopperName"]);
		
		$req->bindParam(':ShopperEmail', $result["ShopperEmail"]);
		$req->bindParam(':LanguajeInterface', $result["LanguajeInterface"]);
		$req->bindParam(':PayMethod', $result["PayMethod"]);
		$req->bindParam(':RecurringBill', $result["RecurringBill"]);
		$req->bindParam(':RecurringBillTimes', $result["RecurringBillTimes"]);
		$req->bindParam(':ExtraData1', $result["ExtraData1"]);
		$req->bindParam(':ExtraData2', $result["ExtraData2"]);
		$req->bindParam(':ExtraData3', $result["ExtraData3"]);
		
		$req->bindParam(':Test', $result["Test"]);
		$req->bindParam(':TransactionId', $result["TransactionId"]);
		$req->bindParam(':TransactionCode', $result["TransactionCode"]);
		$req->bindParam(':TransactionMessage', $result["TransactionMessage"]);
		$req->bindParam(':TokenTransactionCode', $result["TokenTransactionCode"]);
		$req->bindParam(':PageAnswer', $result["PageAnswer"]);
		$req->bindParam(':PageConfirm', $result["PageConfirm"]);
		$req ->execute();		
	
		include "phplib/mail/PHPMailer/class.phpmailer.php";
		include "phplib/mail/sendmail_new.php";
		include "phplib/consultas.php";
		include "phplib/plantilla/cuerpomail.php";


		if($_PROYECTO==13){
			$IDReference=explode("-",$result["IDReference"]);
			$id_cart=$IDReference[1];

			if($result["TransactionCode"]=="00") 		$estado=3;
			elseif($result["TransactionCode"]=="02")	$estado=10;
			elseif($result["TransactionCode"]=="13")	$estado=12;
			else 										$estado=9;

			$env_mail=(($result["TransactionCode"]=="00")||($result["TransactionCode"]=="02")||($result["TransactionCode"]=="13"));


			$lang=$result["LanguajeInterface"];
			$moneda=$result["Currency"];
			if($lang!=""){
				//DEFINIR IDIOMA
				$s=$sqlCons[1][76]." WHERE fac_idioma.NAV03=:lang LIMIT 1";
				$reqLang = $dbEmpresa->prepare($s);
				$reqLang->bindParam(':lang', $lang);
				$reqLang->execute();

				if($regLang = $reqLang->fetch())	$Idioma=$regLang["ID_IDIOMA"];
				else $Idioma=1;
			}
			else $Idioma=1;

			/////////////
			
			/*
			SOLO PARA PRUEBAS
			$s="SELECT b_shopping.ID_SCART ".
				"FROM b_shopping ".
				"WHERE b_shopping.ID_SCART=:id_cart AND fac_estados.TIPO_ESTADO=0 ".
					"AND SHA1(CONCAT(b_shopping.FECHAS_SCART,'-',b_shopping.RND_NUMBER))=:ExtraData1 ".
					"AND SHA1(CONCAT(b_shopping.IPUS_SCART,'-',b_shopping.RND_NUMBER))=:ExtraData2 ".
					"AND b_shopping.FECHAS_SCART=:ExtraData3 LIMIT 1";
			$reqCart = $dbEmpresa->prepare($s);
			$reqCart->bindParam(':id_cart', $id_cart);
			$reqCart->bindParam(':ExtraData1', $result["ExtraData1"]);
			$reqCart->bindParam(':ExtraData2', $result["ExtraData2"]);
			//$reqCart->bindParam(':ExtraData3', $result["ExtraData3"]);
			$reqCart ->execute();
			*/


			$s="SELECT b_shopping.ID_SCART 
				FROM b_shopping 
				LEFT JOIN b_shopping_estado ON b_shopping_estado.ID_SCART=b_shopping.ID_SCART AND b_shopping_estado.ID_ESTSCART=(
																	SELECT b_shopping_estado_pq.ID_ESTSCART
																	FROM b_shopping_estado b_shopping_estado_pq
																	WHERE b_shopping_estado_pq.ID_SCART=b_shopping.ID_SCART
																	ORDER BY b_shopping_estado_pq.FECHAS_ESTSCART DESC LIMIT 1)
				LEFT JOIN fac_estados ON fac_estados.ID_ESTADO=IFNULL(b_shopping_estado.ID_ESTADO,1) AND fac_estados.ID_IDIOMA=1
				WHERE b_shopping.ID_SCART=:id_cart AND fac_estados.TIPO_ESTADO=0 
				AND SHA1(CONCAT(b_shopping.FECHAS_SCART,'-',b_shopping.RND_NUMBER))=:ExtraData1
				AND SHA1(CONCAT(b_shopping.IPUS_SCART,'-',b_shopping.RND_NUMBER))=:ExtraData2 
				LIMIT 1";
			$reqCart = $dbEmpresa->prepare($s);
			$reqCart->bindParam(':id_cart', $id_cart);
			$reqCart->bindParam(':ExtraData1', $result["ExtraData1"]);
			$reqCart->bindParam(':ExtraData2', $result["ExtraData2"]);
			/*$reqCart->bindParam(':ExtraData3', $result["ExtraData3"]);*/
			$reqCart ->execute();

			/*$s=$sqlCons[2][1610]." WHERE b_shopping.ID_SCART=:id_cart AND fac_estados.TIPO_ESTADO=0 LIMIT 1";
			$reqCart = $dbEmpresa->prepare($s);
			$reqCart->bindParam(':id_cart', $id_cart);
			$reqCart->bindParam(':idioma', $Idioma);
			$reqCart ->execute();
			*/

			if($regCart = $reqCart->fetch()){
				$id_cart=$regCart["ID_SCART"];
				$s="UPDATE b_shopping_estado SET ULTIMA=0 WHERE ID_SCART=:id_cart AND ULTIMA=1";
				$req = $dbEmpresa->prepare($s);
				$req->bindParam(':id_cart', $id_cart);
				$req ->execute();

				$s="INSERT INTO b_shopping_estado
						(ID_SCART,ID_ESTADO,ID_USUARIO,FECHAS_ESTSCART,ULTIMA)
					VALUES(:id_cart,:estado,:usuario,UTC_TIMESTAMP(),1)";					
				$req = $dbEmpresa->prepare($s);
				$req->bindParam(':estado', $estado);
				$req->bindParam(':usuario',$_USUARIO);
				$req->bindParam(':id_cart', $id_cart);
				$req ->execute();

				try{
					if($env_mail){	

						$s=$sqlCons[2][1610]." WHERE b_shopping.ID_SCART=:id_cart LIMIT 1";
						$reqCompra = $dbEmpresa->prepare($s); 
						$reqCompra->bindParam(':id_cart', $id_cart);	//ESTA EN LA CONSULTA	
						$reqCompra->bindParam(':idioma', $Idioma);	
						$reqCompra->execute();
						$regCompra = $reqCompra->fetch();	

						$id_cart=$regCompra["ID_SCART"];	

						include("phplib/appfunc.php");
						$tablas=LCB_SendMail($dbEmpresa,$moneda,$Idioma,$id_cart);

						/*******SEND EMAIL***********/
						$ShopperName=$regCompra["NOMBRE_U_OP"].' '.$regCompra["APELLIDO_U_OP"];
						$ID_Reference=sprintf("%s %06s",$_textos[3043][0],$regCompra["ID_SCART"]);

						$to=array();			
						$UsrMail=0;
						$to[$UsrMail]["mail"]=$regCompra["CORREO_U_OP"];
						$to[$UsrMail]["name"]=$ShopperName;
						

						$cc=array();

						$cco=array();						
						$UsrMail=0;
						$cco[$UsrMail]["mail"]=$_PARAMETROS["M_TOMAIL"];
						$cco[$UsrMail]["name"]=$_PARAMETROS["M_TONAME"];
						

						$Asunto=$Email[1][350]['title'];							
						$html_cont=sprintf($Email[1][350]['body'],$ShopperName,$ID_Reference,implode("<hr />", $tablas));
						$rtamail=send_email_srv($_SESSION["EMPRESA"],$Asunto,$html_cont,$to,$cc,$cco,true,$Email[1][350]['alt']);


						$s="UPDATE b_shopping_contenido
							SET ACTIVO=2
							WHERE b_shopping_contenido.ID_SCART=:id_cart";
						$reqVCart = $dbEmpresa->prepare($s); 				
						$reqVCart->bindParam(':id_cart', $id_cart);		//ESTA EN LA CONSULTA
						$reqVCart->execute();

						
					}
				}
				catch (Exception $e){
				}

			}
			header('Location: '.$result["ExtraData3"].'?transac='.$result["TransactionCode"]);
			
		}
	}
}
?>
